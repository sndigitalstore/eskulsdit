<?php

namespace App\Http\Controllers;

use App\Models\Eskul;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class GradeController extends Controller
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
        return view('grades.index', compact('eskuls', 'academicYears', 'activeYear'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'eskul_id' => 'required|exists:eskuls,id',
            'type' => 'required|in:daily,sas1,sas2',
            'date' => 'nullable|date', // Required if type is daily
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
        $semester = '1';

        $contextYear = \App\Models\AcademicYear::find($yearId);
        // Resolve semester dinamis dari tahun ajaran konteks
        $semester = $contextYear ? $contextYear->active_semester : '1';

        $eskul = Eskul::with(['students' => function($q) use ($yearId) {
             $q->wherePivot('academic_year_id', $yearId)
               ->where('status', '!=', 'graduated');
        }])->findOrFail($request->eskul_id);
        
        $type = $request->type;
        $date = $request->date; if ($type == 'daily' && !$date) $date = date('Y-m-d');
        $students = $eskul->students;

        // Fetch existing grades
        $query = Grade::where('eskul_id', $eskul->id)
                      ->where('type', $type)
                      ->where('academic_year_id', $yearId)
                      ->where('semester', $semester);
                      
        if ($type == 'daily') {
            $query->where('date', $date);
        }
        $existingGrades = $query->get()->keyBy('student_id');

        $isCalistung = $eskul->is_lockable;

        // Load student attendance for this date (only relevant for daily grades)
        $studentAttendance = collect();
        if ($type === 'daily' && $date) {
            $studentAttendance = Attendance::where('eskul_id', $eskul->id)
                ->where('date', $date)
                ->where('academic_year_id', $yearId)
                ->get()
                ->keyBy('student_id');
        }

        return view('grades.create', compact('eskul', 'type', 'date', 'students', 'existingGrades', 'yearId', 'semester', 'contextYear', 'isCalistung', 'studentAttendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eskul_id' => 'required|exists:eskuls,id',
            'type' => 'required|in:daily,sas1,sas2',
            'date' => 'nullable|date',
            'grades' => 'required|array',
            'grades' => 'required|array',
            // 'grades.*' => 'in:A,B,C', // Removed to allow arrays/JSON
        ]);

        if (auth()->user()->role == 'teacher') {
            if (!auth()->user()->eskul_id || auth()->user()->eskul_id != $request->eskul_id) {
                abort(403, 'Anda tidak memiliki akses ke eskul ini.');
            }
        }

        $eskulId = $request->eskul_id;
        $type = $request->type;
        $date = $request->date;
        $gradesData = $request->grades;

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

        foreach ($gradesData as $studentId => $score) {
            $condition = [
                'student_id' => $studentId,
                'eskul_id' => $eskulId,
                'type' => $type,
                'academic_year_id' => $yearId,
                'semester' => $semester,
            ];
            
            if ($type == 'daily') {
                 $condition['date'] = $date;
            }

            if (is_array($score)) {
                $score = json_encode($score);
            }

            Grade::updateOrCreate(
                $condition,
                ['score' => $score]
            );
        }

        return redirect()->route('grades.index')->with('success', 'Nilai berhasil disimpan!');
    }

    public function report(Request $request)
    {
        $eskulId = $request->eskul_id;
        $selectedEskul = null;
        $students = [];
        $grades = [];

        if ($eskulId) {
            $selectedEskul = Eskul::with('students')->find($eskulId);
            if ($selectedEskul) {
                $students = $selectedEskul->students;
                $grades = Grade::where('eskul_id', $eskulId)->get()->groupBy('student_id');
            }
        }

        return view('grades.report', compact('selectedEskul', 'students', 'grades'));
    }
}
