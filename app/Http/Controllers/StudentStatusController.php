<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Eskul;
use Illuminate\Support\Facades\DB;

class StudentStatusController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('q')) {
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
        
        $keyword = $request->keyword ?? $request->q;

        // Find student across ALL academic years (not just active)
        $student = Student::with(['eskuls', 'achievements'])->where('nis', $keyword)->first();
        
        if (!$student) {
             $student = Student::with(['eskuls', 'achievements'])->where('name', 'LIKE', $keyword)->first();
        }
        
        if (!$student) {
             $student = Student::with(['eskuls', 'achievements'])->where('name', 'LIKE', "%{$keyword}%")->first();
        }
        
        if (!$student) {
            return back()->withErrors(['keyword' => 'Data siswa tidak ditemukan dengan NIS atau Nama tersebut.']);
        }

        $activeYear = AcademicYear::where('is_active', true)->first();

        // =====================================================================
        // BUILD FULL HISTORY across ALL academic years
        // =====================================================================
        $allAcademicYears = AcademicYear::orderBy('name', 'asc')->get();
        $historyData = [];

        foreach ($allAcademicYears as $year) {
            // Find student record for this specific year
            // (A student may have a record in multiple years under same NIS)
            $studentInYear = Student::where('academic_year_id', $year->id)
                ->where(function($q) use ($student) {
                    if ($student->nis) {
                        $q->where('nis', $student->nis);
                    } else {
                        $q->where('name', $student->name);
                    }
                })->first();

            if (!$studentInYear) continue;

            // Get all unique eskul IDs for this student in this year from:
            // 1. Enrolled eskuls
            // 2. Graded eskuls
            // 3. Attended eskuls
            $enrolledEskulIds = DB::table('student_eskul')
                ->where('student_id', $studentInYear->id)
                ->where('academic_year_id', $year->id)
                ->pluck('eskul_id')
                ->toArray();
                
            $gradedEskulIds = Grade::where('student_id', $studentInYear->id)
                ->where('academic_year_id', $year->id)
                ->pluck('eskul_id')
                ->toArray();
                
            $attendanceEskulIds = Attendance::where('student_id', $studentInYear->id)
                ->where('academic_year_id', $year->id)
                ->pluck('eskul_id')
                ->toArray();
                
            $allEskulIds = array_unique(array_merge($enrolledEskulIds, $gradedEskulIds, $attendanceEskulIds));

            if (empty($allEskulIds)) continue; // Skip years with no activity

            $eskuls = Eskul::whereIn('id', $allEskulIds)->get();

            $yearEskulData = [];
            foreach ($eskuls as $eskul) {
                // Attendance summary
                $attendanceCounts = Attendance::where('student_id', $studentInYear->id)
                    ->where('eskul_id', $eskul->id)
                    ->where('academic_year_id', $year->id)
                    ->selectRaw('status, count(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status');

                // Grades (Both Semesters)
                $grade1 = Grade::where('student_id', $studentInYear->id)
                    ->where('eskul_id', $eskul->id)
                    ->where('academic_year_id', $year->id)
                    ->where('type', 'sas1')
                    ->first();

                $grade2 = Grade::where('student_id', $studentInYear->id)
                    ->where('eskul_id', $eskul->id)
                    ->where('academic_year_id', $year->id)
                    ->where('type', 'sas2')
                    ->first();

                $yearEskulData[] = [
                    'eskul_name'  => $eskul->name,
                    'instructor'  => $eskul->instructor_name,
                    'attendance'  => [
                        'H' => $attendanceCounts['present']    ?? 0,
                        'S' => $attendanceCounts['sick']       ?? 0,
                        'I' => $attendanceCounts['permission'] ?? 0,
                        'A' => $attendanceCounts['absent']     ?? 0,
                    ],
                    'grades' => [
                        'sas1' => $grade1->score ?? '-',
                        'sas2' => $grade2->score ?? '-',
                    ],
                ];
            }

            $historyData[] = [
                'year_name'    => $year->name,
                'year_id'      => $year->id,
                'is_active'    => $year->is_active,
                'student_class'=> $studentInYear->class,
                'student_id'   => $studentInYear->id,
                'eskul_data'   => $yearEskulData,
            ];
        }

        // =====================================================================
        // Current active year data (for backwards compat with $reportData)
        // =====================================================================
        $reportData = [];
        $currentYearHistory = collect($historyData)->firstWhere('is_active', true);
        if ($currentYearHistory) {
            $reportData = $currentYearHistory['eskul_data'];
        }

        // All achievements (across all years)
        $achievements = \App\Models\Achievement::where(function($q) use ($student) {
            if ($student->nis) {
                // Match by NIS across all student records
                $studentIds = Student::where('nis', $student->nis)->pluck('id')->toArray();
                $q->whereIn('student_id', $studentIds);
            } else {
                $q->where('student_id', $student->id);
            }
        })->orderBy('date', 'desc')->get();

        return view('status.result', compact('student', 'activeYear', 'reportData', 'historyData', 'achievements'));
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
