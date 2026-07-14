<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Grade;

class StudentStatusController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('q')) {
            // Re-use logic or redirect to 'search' via POST is tricky with redirect, 
            // easier to just handle here or call search logic.
            // But search is POST route usually. Let's make index handle it if needed or simple redirect view
            // Better: update search method to handle both but index is GET search is POST.
            // Let's forward to search logic manually if we want result immediately
            $request->merge(['keyword' => $request->q]);
            return $this->search($request);
        }
        return view('status.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required',
        ]);
        
        // Find user by searched NIS or if accessed from query string ?q=NIS
        $keyword = $request->keyword ?? $request->q;

        // Try to find by NIS first (Exact Match) - scoped to active year
        $student = Student::activeYear()->with(['eskuls', 'achievements'])->where('nis', $keyword)->first();
        
        if (!$student) {
             $student = Student::activeYear()->with(['eskuls', 'achievements'])->where('name', 'LIKE', $keyword)->first();
        }
        
        if (!$student) {
             $student = Student::activeYear()->with(['eskuls', 'achievements'])->where('name', 'LIKE', "%{$keyword}%")->first();
        }
        
        if (!$student) {
            return back()->withErrors(['keyword' => 'Data siswa tidak ditemukan dengan NIS atau Nama tersebut.']);
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->withErrors(['msg' => 'Tidak ada tahun ajaran aktif.']);
        }

        // Fetch data for the student for the active year
        $reportData = [];

        foreach ($student->eskuls as $eskul) {
            // Check if student is enrolled in this eskul for the active year
            $isEnrolled = \Illuminate\Support\Facades\DB::table('student_eskul')
                            ->where('student_id', $student->id)
                            ->where('eskul_id', $eskul->id)
                            ->where('academic_year_id', $activeYear->id)
                            ->exists();
            
            if (!$isEnrolled) continue;

            // Attendance
            $attendanceCounts = Attendance::where('student_id', $student->id)
                ->where('eskul_id', $eskul->id)
                ->where('academic_year_id', $activeYear->id)
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            // Grades (Both Semesters)
            $grade1 = Grade::where('student_id', $student->id)
                ->where('eskul_id', $eskul->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('type', 'sas1')
                ->first();

            $grade2 = Grade::where('student_id', $student->id)
                ->where('eskul_id', $eskul->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('type', 'sas2')
                ->first();

            $reportData[] = [
                'eskul_name' => $eskul->name,
                'instructor' => $eskul->instructor_name,
                'attendance' => [
                    'H' => $attendanceCounts['present'] ?? 0,
                    'S' => $attendanceCounts['sick'] ?? 0,
                    'I' => $attendanceCounts['permission'] ?? 0,
                    'A' => $attendanceCounts['absent'] ?? 0,
                ],
                'grades' => [
                    'sas1' => $grade1->score ?? '-',
                    'sas2' => $grade2->score ?? '-',
                ]
            ];
        }

        return view('status.result', compact('student', 'activeYear', 'reportData'));
    }
    
    // API Helper to reuse the class-student fetch logic
    public function getStudents(Request $request)
    {
        $class = $request->query('class');
        if (!$class) return response()->json([]);
        
        $students = Student::activeYear()->where('class', $class)->orderBy('name')->get(['id', 'name']);
        return response()->json($students);
    }

}
