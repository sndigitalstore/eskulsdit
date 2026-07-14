<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentApiController extends Controller
{
    /**
     * Search students by name or NIS (Live Search).
     */
    public function search(Request $request)
    {
        $query = $request->query('q');
        if (!$query || strlen($query) < 2) return response()->json([]);

        // Enhance search flexibility: "Muh Zaky" matches "Muhammad Zaky"
        $term = str_replace(' ', '%', $query); // 'muh zaky' -> 'muh%zaky'
        
        $students = Student::activeYear()
                            ->where(function($q) use ($term, $query) {
                                $q->where('name', 'like', "%{$term}%")
                                  ->orWhere('nis', 'like', "%{$query}%");
                            })
                            ->select('id', 'name', 'nis', 'class', 'status') 
                            ->limit(20)
                            ->get();

        return response()->json($students);
    }

    /**
     * Get students by class (Dropdown helper).
     */
    public function getByClass(Request $request)
    {
        $class = $request->query('class');
        if (!$class) return response()->json([]);
        
        $students = Student::activeYear()
            ->where('class', $class)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')
                  ->orWhereNull('status');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'status']);
            
        return response()->json($students);
    }
}
