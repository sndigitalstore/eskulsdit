<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $class = $request->input('class');
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = $academicYears->where('is_active', true)->first();
        
        // Default source academic year is the year before active year, or active year if it's the only one
        $sourceYearId = $request->input('source_academic_year_id');
        if (!$sourceYearId && $activeYear) {
            $olderYear = AcademicYear::where('id', '<', $activeYear->id)->orderBy('id', 'desc')->first();
            $sourceYearId = $olderYear ? $olderYear->id : $activeYear->id;
        }

        $students = [];
        if ($class && $sourceYearId) {
            $students = Student::where('academic_year_id', $sourceYearId)
                ->where('status', 'active')
                ->where('class', $class)
                ->get();
        }

        return view('promotions.index', compact('students', 'class', 'academicYears', 'sourceYearId', 'activeYear'));
    }

    public function promote(Request $request)
    {
        $request->validate([
            'class_from' => 'required|string',
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
            'source_academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $classFrom = $request->class_from;
        $studentIds = $request->ids;
        $sourceYearId = $request->source_academic_year_id;

        // Find the active year (as target)
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif sebagai target kenaikan kelas.');
        }

        // Extract grade level (e.g., "1A" -> 1)
        preg_match('/^(\d+)/', $classFrom, $matches);
        $gradeLevel = isset($matches[1]) ? (int)$matches[1] : 0;

        if ($gradeLevel === 6) {
            // GRADUATION: Update status in the source year record
            Student::whereIn('id', $studentIds)
                ->where('academic_year_id', $sourceYearId)
                ->update([
                    'status' => 'graduated'
                ]);
            $message = count($studentIds) . " siswa kelas 6 berhasil diluluskan!";
        } else {
            // PROMOTION: Create NEW records for the target academic year
            $nextLevel = $gradeLevel + 1;
            $suffix = substr($classFrom, strlen((string)$gradeLevel));
            $nextClass = $nextLevel . $suffix;

            $studentsToPromote = Student::whereIn('id', $studentIds)
                ->where('academic_year_id', $sourceYearId)
                ->get();

            $promotedCount = 0;
            foreach ($studentsToPromote as $student) {
                // Check if the student already exists in the target year to avoid duplicates
                $exists = Student::where('nis', $student->nis)
                    ->where('academic_year_id', $activeYear->id)
                    ->exists();

                if (!$exists) {
                    // Duplicate/clone student record to target year
                    $newStudent = $student->replicate();
                    $newStudent->academic_year_id = $activeYear->id;
                    $newStudent->class = $nextClass;
                    $newStudent->status = 'active'; // Reset status to active in new year
                    $newStudent->save();
                    
                    $promotedCount++;
                }
            }

            $message = $promotedCount . " siswa berhasil naik ke kelas " . $nextClass . " di Tahun Ajaran " . $activeYear->name . "!";
        }

        return redirect()->route('promotions.index', [
            'class' => $classFrom,
            'source_academic_year_id' => $sourceYearId
        ])->with('success', $message);
    }
}
