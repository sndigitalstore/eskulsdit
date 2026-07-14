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

            $students = Student::with(['eskuls' => function($q) use ($activeYear) {
                    if ($activeYear) {
                        $q->where('student_eskul.academic_year_id', $activeYear->id)
                          ->where('student_eskul.semester', $activeYear->active_semester);
                    }
                }])
                ->where('name', 'like', "%{$term}%")
                ->orWhere('class', 'like', "%{$term}%")
                ->orWhere('nis', 'like', "%{$query}%") // Keep strict for NIS
                ->limit(50) // Cap results for performance
                ->get();
        }

        return view('search.results', compact('students', 'query'));
    }
}
