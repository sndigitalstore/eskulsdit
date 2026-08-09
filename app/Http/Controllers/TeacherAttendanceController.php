<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherAttendance;
use App\Models\AcademicYear;
use App\Models\User;
use App\Models\SubstituteToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeacherAttendanceExport;

class TeacherAttendanceController extends Controller
{
    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $user = Auth::user();
        
        if ($user->role == 'admin') {
            // Admin View: Filter by month
            $month = request('month', now()->format('Y-m'));
            $yearStr = substr($month, 0, 4);
            $monthStr = substr($month, 5, 2);
            
            $attendances = TeacherAttendance::where('academic_year_id', $activeYear->id)
                ->whereYear('date', $yearStr)
                ->whereMonth('date', $monthStr)
                ->with(['user', 'substituteUser'])
                ->orderBy('date', 'desc')
                ->get();

            $teachers = User::where('role', 'teacher')->orderBy('name')->get();
                
            return view('teacher_attendance.admin_index', compact('attendances', 'activeYear', 'month', 'teachers'));
        } else {
            // Teacher View: My Attendance
            $myAttendances = TeacherAttendance::where('user_id', $user->id)
                ->where('academic_year_id', $activeYear->id)
                ->with('substituteUser')
                ->orderBy('date', 'desc')
                ->paginate(10);
                
            $todayAttendance = TeacherAttendance::where('user_id', $user->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('date', now()->toDateString())
                ->first();

            $teachersList = User::where('role', 'teacher')
                ->where('id', '!=', $user->id)
                ->orderBy('name')
                ->get();

            $substituteToken = null;
            if ($todayAttendance && in_array($todayAttendance->status, ['sick', 'permission']) && $user->eskul_id) {
                $substituteToken = SubstituteToken::where('user_id', $user->id)
                    ->where('date', now()->toDateString())
                    ->latest()
                    ->first();
            }
                
            return view('teacher_attendance.index', compact('myAttendances', 'todayAttendance', 'activeYear', 'teachersList', 'substituteToken'));
        }
    }

    public function store(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) return back()->with('error', 'Tidak ada tahun ajaran aktif.');

        $user = Auth::user();
        $isAdmin = ($user->role === 'admin');

        $rules = [
            'status' => 'required|in:present,sick,permission',
            'note' => 'nullable|string|max:255',
            'substitute_type' => 'nullable|in:registered,manual',
            'substitute_user_id' => 'nullable|exists:users,id',
            'substitute_name' => 'nullable|string|max:255',
        ];

        if ($isAdmin) {
            $rules['user_id'] = 'required|exists:users,id';
            $rules['date'] = 'required|date';
            $rules['clock_in_time'] = 'nullable|string';
        }

        $request->validate($rules);

        $targetUserId = $isAdmin ? $request->user_id : $user->id;
        $targetDate = $isAdmin ? $request->date : now()->toDateString();

        $targetUser = User::find($targetUserId);
        if (!$targetUser) {
            return back()->with('error', 'User Guru tidak ditemukan.');
        }

        // Check double
        $exists = TeacherAttendance::where('user_id', $targetUserId)
            ->where('date', $targetDate)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Absensi untuk Guru ini pada tanggal tersebut sudah tercatat.');
        }

        // Enforce once-per-week teacher attendance check (Friday - Thursday cycle)
        $carbonDate = \Carbon\Carbon::parse($targetDate);
        $startOfWeek = ($carbonDate->dayOfWeek === \Carbon\Carbon::FRIDAY)
            ? $carbonDate->copy()
            : $carbonDate->copy()->previous(\Carbon\Carbon::FRIDAY);
        $endOfWeek = $startOfWeek->copy()->addDays(6);

        $existingInWeek = TeacherAttendance::where('user_id', $targetUserId)
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->where('date', '!=', $targetDate)
            ->first();

        if ($existingInWeek) {
            $formattedExistingDate = \Carbon\Carbon::parse($existingInWeek->date)->isoFormat('D MMMM Y');
            return back()->with('error', "Guru ini sudah melakukan absensi pada tanggal {$formattedExistingDate} di periode pekan berjalan (Jum'at - Kamis). Absensi guru hanya diperbolehkan 1 kali per pekan.");
        }

        $substituteName = null;
        $substituteUserId = null;

        if (in_array($request->status, ['sick', 'permission'])) {
            if ($request->substitute_type === 'registered' && $request->substitute_user_id) {
                $substituteUser = User::find($request->substitute_user_id);
                if ($substituteUser) {
                    $substituteUserId = $substituteUser->id;
                    $substituteName = $substituteUser->name;
                }
            } else {
                $substituteName = $request->substitute_name;
            }
        }

        $clockInTime = now()->toTimeString();
        if ($isAdmin && $request->filled('clock_in_time')) {
            $clockInTime = $request->clock_in_time;
            if (strlen($clockInTime) === 5) {
                $clockInTime .= ':00'; // Format HH:MM to HH:MM:SS
            }
        }

        $attendance = TeacherAttendance::create([
            'user_id' => $targetUserId,
            'academic_year_id' => $activeYear->id,
            'date' => $targetDate,
            'clock_in_time' => $clockInTime,
            'status' => $request->status,
            'note' => $request->note,
            'substitute_name' => $substituteName,
            'substitute_user_id' => $substituteUserId,
        ]);

        // Create substitute token for public link if teacher is absent and has an eskul assigned
        if (in_array($request->status, ['sick', 'permission']) && $targetUser->eskul_id) {
            SubstituteToken::create([
                'token' => Str::random(32),
                'eskul_id' => $targetUser->eskul_id,
                'user_id' => $targetUser->id,
                'date' => $targetDate,
                'expires_at' => \Carbon\Carbon::parse($targetDate)->endOfDay(),
            ]);
        }

        \App\Models\ActivityLog::create([
            'user_id' => $user->id,
            'module' => 'Teacher Attendance',
            'action' => 'Create',
            'description' => $isAdmin 
                ? "Admin memasukkan absensi guru {$targetUser->name} secara manual untuk tanggal {$targetDate}: " . strtoupper($request->status) . ($substituteName ? " (Guru Pengganti: {$substituteName})" : "")
                : "Guru {$user->name} melakukan absensi hari ini: " . strtoupper($request->status) . ($substituteName ? " (Guru Pengganti: {$substituteName})" : ""),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Absensi berhasil disimpan.');
    }
    
    public function destroy(TeacherAttendance $teacherAttendance)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        
        $teacherName = $teacherAttendance->user ? $teacherAttendance->user->name : 'Unknown';
        \App\Models\ActivityLog::log('Teacher Attendance', 'Delete', "Menghapus absensi guru tanggal {$teacherAttendance->date} untuk guru {$teacherName}.");
        
        $teacherAttendance->delete();
        return back()->with('success', 'Data absensi guru berhasil dihapus.');
    }

    public function export(Request $request)
    {
        // Only admin
        if (Auth::user()->role !== 'admin') abort(403);
        
        $month = $request->month;
        $query = TeacherAttendance::with('user');
        
        if ($month) {
            $yearStr = substr($month, 0, 4);
            $monthStr = substr($month, 5, 2);
            $query->whereYear('date', $yearStr)->whereMonth('date', $monthStr);
            $fileName = 'Rekap_Absensi_Guru_' . $month . '.xlsx';
        } else {
            $fileName = 'Rekap_Absensi_Guru_Semua.xlsx';
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        return Excel::download(new TeacherAttendanceExport($attendances), $fileName);
    }
}
