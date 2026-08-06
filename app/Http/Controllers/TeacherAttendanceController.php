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
                
            return view('teacher_attendance.admin_index', compact('attendances', 'activeYear', 'month'));
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

        $request->validate([
            'status' => 'required|in:present,sick,permission',
            'note' => 'nullable|string|max:255',
            'substitute_type' => 'nullable|in:registered,manual',
            'substitute_user_id' => 'nullable|exists:users,id',
            'substitute_name' => 'nullable|string|max:255',
        ]);

        // Check double
        $exists = TeacherAttendance::where('user_id', $user->id)
            ->where('date', now()->toDateString())
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini.');
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

        $attendance = TeacherAttendance::create([
            'user_id' => $user->id,
            'academic_year_id' => $activeYear->id,
            'date' => now()->toDateString(),
            'clock_in_time' => now()->toTimeString(),
            'status' => $request->status,
            'note' => $request->note,
            'substitute_name' => $substituteName,
            'substitute_user_id' => $substituteUserId,
        ]);

        // Create substitute token for public link if teacher is absent and has an eskul assigned
        if (in_array($request->status, ['sick', 'permission']) && $user->eskul_id) {
            SubstituteToken::create([
                'token' => Str::random(32),
                'eskul_id' => $user->eskul_id,
                'user_id' => $user->id,
                'date' => now()->toDateString(),
                'expires_at' => now()->endOfDay(),
            ]);
        }

        return back()->with('success', 'Terima kasih, absensi berhasil disimpan.');
    }
    
    public function destroy(TeacherAttendance $teacherAttendance)
    {
        // Only admin
        if (Auth::user()->role !== 'admin') abort(403);
        
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
