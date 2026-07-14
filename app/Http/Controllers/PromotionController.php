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
        $students = [];

        if ($class) {
            $students = Student::active()->where('class', $class)->get();
        }

        return view('promotions.index', compact('students', 'class'));
    }

    public function promote(Request $request)
    {
        $request->validate([
            'class_from' => 'required|string',
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
        ]);

        $classFrom = $request->class_from;
        $studentIds = $request->ids;

        // Extract number from class (e.g., "1A" -> 1)
        // Adjust regex to capture leading number
        preg_match('/^(\d+)/', $classFrom, $matches);
        $gradeLevel = isset($matches[1]) ? (int)$matches[1] : 0;

        if ($gradeLevel === 6) {
            // GRADUATION
            Student::whereIn('id', $studentIds)->update([
                'status' => 'graduated'
            ]);
            $message = count($studentIds) . " siswa kelas 6 berhasil diluluskan!";
        } else {
            // PROMOTION
            $nextLevel = $gradeLevel + 1;
            // Try to keep the same suffix letter if exists (e.g. 1A -> 2A)
            $suffix = substr($classFrom, strlen((string)$gradeLevel));
            
            // Simple logic: If class is just "1", next is "2". If "1A", next is "2A".
            // But if bulk promotion, maybe we just increment the number?
            // Let's assume user promotes per class section (e.g. 1A -> 2A).
            
            $nextClass = $nextLevel . $suffix;

            Student::whereIn('id', $studentIds)->update([
                'class' => $nextClass
            ]);
            $message = count($studentIds) . " siswa berhasil naik ke kelas " . $nextClass . "!";
        }

        return back()->with('success', $message);
    }
}
