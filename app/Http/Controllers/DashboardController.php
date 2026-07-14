<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Eskul;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get Active Academic Year
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        $studentCount = Student::activeYear()->count();

        // Active Year Context
        $yearId = $activeYear ? $activeYear->id : null;
        $semester = $activeYear ? $activeYear->active_semester : '1';

        // Eskul Count: Only count Eskuls that have students in the active academic year & semester
        $eskulCount = Eskul::whereHas('students', function($q) use ($yearId, $semester) {
             if ($yearId) {
                $q->where('student_eskul.academic_year_id', $yearId)
                  ->where('student_eskul.semester', $semester);
            }
            $q->where('status', '!=', 'graduated');
        })->count();

        // Teacher Count: Distinct instructors from Active Eskuls only
        $teacherCount = Eskul::whereHas('students', function($q) use ($yearId, $semester) {
             if ($yearId) {
                $q->where('student_eskul.academic_year_id', $yearId)
                  ->where('student_eskul.semester', $semester);
            }
            $q->where('status', '!=', 'graduated');
        })
        ->whereNotNull('instructor_name')
        ->where('instructor_name', '!=', '')
        ->distinct('instructor_name')
        ->count('instructor_name');

        // Calculate Grade Statistics (scoped to active year)
        $classData = Student::activeYear()
            ->select('class', DB::raw('count(*) as count'))
            ->groupBy('class')
            ->get();

        $gradeStatistics = [];
        foreach ($classData as $data) {
            // Extract grade level (e.g. "1" from "1A")
            preg_match('/^\d+/', $data->class, $matches);
            $grade = $matches[0] ?? 'Lainnya';
            
            if (!isset($gradeStatistics[$grade])) {
                $gradeStatistics[$grade] = [
                    'grade' => $grade,
                    'total_students' => 0,
                    'rombel_count' => 0,
                    'classes' => []
                ];
            }
            
            $gradeStatistics[$grade]['total_students'] += $data->count;
            $gradeStatistics[$grade]['rombel_count'] += 1;
            $gradeStatistics[$grade]['classes'][] = $data->class;
        }
        
        // Sort by grade level (numeric)
        // Sort by grade level (numeric)
        ksort($gradeStatistics);

        // User Context
        $user = auth()->user();
        $isTeacher = $user->role == 'teacher';
        $teacherEskulId = $isTeacher ? $user->eskul_id : null;

        // 3. Actionable: Eskuls Missing Attendance This Week (Active Year/Semester Only)
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        $eskulMissingAttendance = Eskul::whereHas('students', function($q) use ($activeYear) {
             if ($activeYear) {
                $q->where('student_eskul.academic_year_id', $activeYear->id)
                  ->where('student_eskul.semester', $activeYear->active_semester);
            }
        })
        ->when($isTeacher, function($q) use ($teacherEskulId) {
            // If teacher, only show their eskul
            return $q->where('id', $teacherEskulId);
        })
        ->whereDoesntHave('attendances', function($q) use ($startOfWeek, $endOfWeek) {
            $q->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()]);
        })
        ->limit(5)
        ->get();

        // --- CHART DATA PREPARATION ---

        // 1. Eskul Popularity (Top 5)
        $popularEskuls = Eskul::withCount(['students' => function($q) use ($activeYear) {
            if ($activeYear) {
                $q->where('student_eskul.academic_year_id', $activeYear->id);
            }
        }])
        ->orderByDesc('students_count')
        ->limit(5)
        ->get();

        $chartEskulLabels = $popularEskuls->pluck('name');
        $chartEskulData = $popularEskuls->pluck('students_count');

        // 2. Attendance Distribution (Active Year)
        $attendanceStats = \App\Models\Attendance::select('status', DB::raw('count(*) as total'))
            ->when($activeYear, function($q) use ($activeYear) {
                return $q->where('academic_year_id', $activeYear->id);
            })
            // Teacher sees global stats? Or maybe personalized?
            // User requested: "Jadwal dan Aktivitas Baru" specifically. 
            // Charts usually are kept global for "Big Picture" or can be ignored for now unless asked.
            ->groupBy('status')
            ->pluck('total', 'status');
        
        // Ensure all keys exist for consistency
        $chartAttendanceData = [
            $attendanceStats['present'] ?? 0,
            $attendanceStats['sick'] ?? 0,
            $attendanceStats['permission'] ?? 0,
            $attendanceStats['absent'] ?? 0
        ];
        // Labels: Hadir, Sakit, Izin, Alpha

        // 4. Actionable: Eskul Hari Ini (Today's Schedule)
        \Carbon\Carbon::setLocale('id'); // Ensure ID locale
        $todayName = \Carbon\Carbon::now()->translatedFormat('l'); // e.g. "Senin"
        
        $todaySchedule = Eskul::where('schedule', 'LIKE', "%{$todayName}%")
            ->whereHas('students', function($q) use ($activeYear) {
                 if ($activeYear) {
                    $q->where('student_eskul.academic_year_id', $activeYear->id)
                      ->where('student_eskul.semester', $activeYear->active_semester);
                 }
                 $q->where('status', '!=', 'graduated');
            })
            ->when($isTeacher, function($q) use ($teacherEskulId) {
                return $q->where('id', $teacherEskulId);
            })
            ->get();

        // 5. Objective: Top Participating Classes (Most students in Eskul)
        $topClasses = Student::select('class', DB::raw('count(distinct students.id) as total'))
            ->join('student_eskul', 'students.id', '=', 'student_eskul.student_id')
            ->when($yearId, function($q) use ($yearId, $activeYear) {
                $q->where('student_eskul.academic_year_id', $yearId);
                $q->where('student_eskul.semester', $activeYear ? $activeYear->active_semester : '1');
            })
            ->where('students.status', 'active')
            ->groupBy('class')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 6. Accountability: Recent Activities (Last 5 Attendance Inputs)
        $recentActivities = \App\Models\Attendance::with(['student', 'eskul'])
            ->when($isTeacher, function($q) use ($teacherEskulId) {
                return $q->where('eskul_id', $teacherEskulId);
            })
            ->orderBy('created_at', 'desc') // Ensure ordering
            ->limit(5)
            ->get();

        // 7. Teacher Attendance Today
        // 7. Teacher Attendance Today
        // We count distinct users who have submitted ANY attendance today
        $teacherAttendanceToday = \App\Models\TeacherAttendance::whereDate('date', now()->toDateString())
            ->count();
            
        $teacherAttendancePresent = \App\Models\TeacherAttendance::whereDate('date', now()->toDateString())
            ->where('status', 'present')
            ->count();

        // Count Real Accounts for context
        $registeredTeacherAccounts = \App\Models\User::where('role', 'teacher')->count();

        // 6. Recent Activities (Closing correctly)
        // (Just ensure the variable exists from previous block)
        
        // 8. Latest Announcements
        $announcements = \App\Models\InternalAnnouncement::where('is_active', true)->latest()->limit(3)->get();
        
        return view('dashboard', compact(
            'studentCount', 'eskulCount', 'teacherCount', 'gradeStatistics', 
            'chartEskulLabels', 'chartEskulData', 'chartAttendanceData', 
            'eskulMissingAttendance', 'activeYear',
            'todaySchedule', 'topClasses', 'recentActivities',
            'teacherAttendanceToday', 'teacherAttendancePresent', 'registeredTeacherAccounts',
            'announcements'
        ));
    }
}
