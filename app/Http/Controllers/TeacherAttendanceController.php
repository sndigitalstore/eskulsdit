<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherAttendance;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;
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
                ->with('user')
                ->orderBy('date', 'desc')
                ->get();
                
            return view('teacher_attendance.admin_index', compact('attendances', 'activeYear', 'month'));
        } else {
            // Teacher View: My Attendance
            $myAttendances = TeacherAttendance::where('user_id', $user->id)
                ->where('academic_year_id', $activeYear->id)
                ->orderBy('date', 'desc')
                ->paginate(10);
                
            $todayAttendance = TeacherAttendance::where('user_id', $user->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('date', now()->toDateString())
                ->first();
                
            return view('teacher_attendance.index', compact('myAttendances', 'todayAttendance', 'activeYear'));
        }
    }

    public function store(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) return back()->with('error', 'Tidak ada tahun ajaran aktif.');

        $request->validate([
            'status' => 'required|in:present,sick,permission',
            'note' => 'nullable|string|max:255',
            'substitute_name' => 'nullable|string|max:255',
        ]);

        // Check double
        $exists = TeacherAttendance::where('user_id', Auth::id())
            ->where('date', now()->toDateString())
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini.');
        }

        TeacherAttendance::create([
            'user_id' => Auth::id(),
            'academic_year_id' => $activeYear->id,
            'date' => now()->toDateString(),
            'clock_in_time' => now()->toTimeString(),
            'status' => $request->status,
            'note' => $request->note,
            'substitute_name' => in_array($request->status, ['sick', 'permission']) ? $request->substitute_name : null,
        ]);

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
