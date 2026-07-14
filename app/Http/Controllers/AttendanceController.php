<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Eskul;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role == 'teacher') {
            $eskuls = $user->eskul_id 
                ? Eskul::has('students')->where('id', $user->eskul_id)->get()
                : collect();
        } else {
            $eskuls = Eskul::has('students')->get();
        }
        
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = $academicYears->where('is_active', true)->first();
        return view('attendance.index', compact('eskuls', 'academicYears', 'activeYear'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'eskul_id' => 'required|exists:eskuls,id',
            'date' => 'required|date',
        ]);

        if (auth()->user()->role == 'teacher') {
            if (!auth()->user()->eskul_id || auth()->user()->eskul_id != $request->eskul_id) {
                abort(403, 'Anda tidak memiliki akses ke eskul ini.');
            }
        }

        // Determine context (History or Active)
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        
        $yearId = $request->academic_year_id;
        $semester = $request->semester;

        if (!$yearId) {
             // Fallback to active if not specified
             if (!$activeYear) return back()->withErrors(['msg' => 'Tidak ada tahun ajaran aktif.']);
             $yearId = $activeYear->id;
        }

        // Resolve semester: pakai dari request jika ada (untuk riwayat), otherwise ikuti tahun aktif
        $contextYear = \App\Models\AcademicYear::find($yearId);
        if (!$semester) {
            $semester = $contextYear ? $contextYear->active_semester : '1';
        }

        $eskul = Eskul::with(['students' => function($q) use ($yearId) {
             $q->wherePivot('academic_year_id', $yearId)
               ->where('status', '!=', 'graduated');
        }])->findOrFail($request->eskul_id);
        
        $date = $request->date;
        $students = $eskul->students;

        // Check if attendance already exists for this date and eskul
        $existingAttendance = Attendance::where('eskul_id', $eskul->id)
            ->where('date', $date)
            ->where('academic_year_id', $yearId)
            ->where('semester', $semester)
            ->get()
            ->keyBy('student_id');

        return view('attendance.create', compact('eskul', 'date', 'students', 'existingAttendance', 'yearId', 'semester', 'contextYear'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eskul_id' => 'required|exists:eskuls,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'in:present,absent,sick,permission',
            'notes' => 'nullable|array',
        ]);

        if (auth()->user()->role == 'teacher') {
            if (!auth()->user()->eskul_id || auth()->user()->eskul_id != $request->eskul_id) {
                abort(403, 'Anda tidak memiliki akses ke eskul ini.');
            }
        }

        $eskulId = $request->eskul_id;
        $date = $request->date;
        $attendanceData = $request->attendance;
        $notes = $request->notes ?? [];

        // Determine context (Use request input for history support)
        // Determine context (Use request input for history support)
        if ($request->filled('academic_year_id')) {
            $yearId = $request->academic_year_id;
        } else {
            // Fallback to active (Backwards compatibility protection)
            $activeYear = \App\Models\AcademicYear::where('is_active', true)->firstOrFail();
            $yearId = $activeYear->id;
        }
        // Resolve semester dari context tahun ajaran
        if ($request->filled('semester')) {
            $semester = $request->semester;
        } else {
            $contextYear = \App\Models\AcademicYear::find($yearId);
            $semester = $contextYear ? $contextYear->active_semester : '1';
        }

        foreach ($attendanceData as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'eskul_id' => $eskulId,
                    'date' => $date,
                    'academic_year_id' => $yearId,
                    'semester' => $semester,
                ],
                [
                    'status' => $status,
                    'note' => $notes[$studentId] ?? null,
                ]
            );
        }

        return redirect()->route('attendance.index')->with('success', 'Absensi berhasil disimpan!');
    }

    public function report(Request $request)
    {
        $eskulId = $request->eskul_id;
        $yearId = $request->academic_year_id;
        // Context
        if ($yearId) {
             $year = \App\Models\AcademicYear::find($yearId);
        } else {
             $year = \App\Models\AcademicYear::where('is_active', true)->first();
             $yearId = $year ? $year->id : null;
        }

        // Resolve semester: ikuti semester aktif dari tahun yang dipilih
        $semester = $request->semester;
        if (!$semester) {
            $semester = $year ? $year->active_semester : '1';
        }

        if(empty($eskulId)) {
            return redirect()->route('attendance.index')->with('error', 'Silakan pilih ekstrakurikuler terlebih dahulu.');
        }

        $eskul = Eskul::with(['students' => function($q) use ($yearId) {
            $q->wherePivot('academic_year_id', $yearId)
              ->where('status', '!=', 'graduated');
        }])->findOrFail($eskulId);

        // Fetch Attendance Summary for this Eskul & Year
        $students = $eskul->students->map(function($student) use ($eskulId, $yearId, $semester) {
             $summary = Attendance::where('student_id', $student->id)
                ->where('eskul_id', $eskulId)
                ->where('academic_year_id', $yearId)
                ->where('semester', $semester)
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

             $student->h = $summary['present'] ?? 0;
             $student->s = $summary['sick'] ?? 0;
             $student->i = $summary['permission'] ?? 0;
             $student->a = $summary['absent'] ?? 0;
             
             return $student;
        });

        // Get dates for column headers (optional, maybe detailed view later)
        // For now, user requested "recap" similar to grades index "Report", which is usually a summary table.
        
        return view('attendance.report', compact('eskul', 'students', 'year'));
    }
}
