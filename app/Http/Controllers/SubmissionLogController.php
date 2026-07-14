<?php

namespace App\Http\Controllers;

use App\Models\SubmissionLog;
use Illuminate\Http\Request;

class SubmissionLogController extends Controller
{
    public function index(Request $request)
    {
        $query = SubmissionLog::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('student_name', 'like', "%{$search}%")
                  ->orWhere('student_class', 'like', "%{$search}%");
        }

        $logs = $query->paginate(20);

        return view('logs.index', compact('logs'));
    }
}
