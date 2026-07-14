<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AcademicYear;

class GlobalSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $students = collect();
        $activeYear = AcademicYear::where('is_active', true)->first();

        if ($query) {
            $term = str_replace(' ', '%', $query);

            $students = Student::activeYear()
                ->with(['eskuls' => function($q) use ($activeYear) {
                    if ($activeYear) {
                        $q->where('student_eskul.academic_year_id', $activeYear->id)
                          ->where('student_eskul.semester', $activeYear->active_semester);
                    }
                }])
                ->where(function($q) use ($term, $query) {
                    $q->where('students.name', 'like', "%{$term}%")
                      ->orWhere('students.class', 'like', "%{$term}%")
                      ->orWhere('students.nis', 'like', "%{$query}%");
                })
                ->limit(50)
                ->get();
        }

        return view('search.results', compact('students', 'query'));
    }
}
