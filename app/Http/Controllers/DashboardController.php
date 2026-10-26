<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Eskul;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Get Active Academic Year
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        // User Context
        $user = auth()->user();

        // TU (Tata Usaha) → Dedicated Student-Focused Dashboard
        if ($user->role === 'tu') {
            return $this->tuDashboard($activeYear);
        }

        $isTeacher = $user->role == 'teacher';
        $teacherEskulId = $isTeacher ? $user->eskul_id : null;

        // Scoped Student Count
        if ($isTeacher) {
            if ($teacherEskulId) {
                $studentCount = Student::activeYear()->active()
                    ->whereHas('eskuls', function($q) use ($teacherEskulId, $activeYear) {
                        $q->where('student_eskul.eskul_id', $teacherEskulId);
                        if ($activeYear) {
                            $q->where('student_eskul.academic_year_id', $activeYear->id)
                              ->where('student_eskul.semester', $activeYear->active_semester);
                        }
                    })->count();
            } elseif (!empty($user->homeroom_class)) {
                $studentCount = Student::activeYear()->active()
                    ->where('class', $user->homeroom_class)->count();
            } else {
                $studentCount = 0;
            }
        } else {
            $studentCount = Student::activeYear()->active()->count();
        }

        // Active Year Context
        $yearId = $activeYear ? $activeYear->id : null;
        $semester = $activeYear ? $activeYear->active_semester : '1';

        // Eskul Count: Count all Eskuls registered for the active year
        if ($isTeacher) {
            $eskulCount = $teacherEskulId ? Eskul::activeYear()->where('id', $teacherEskulId)->count() : 0;
        } else {
            $eskulCount = Eskul::activeYear()->count();
        }

        // Teacher Count: Count distinct teachers for the active year
        if ($isTeacher) {
            $teacherCount = 1;
        } else {
            $teacherCount = \App\Models\User::where('role', 'teacher')->activeYear()->count();
        }

        // Calculate Grade Statistics (scoped to active year and teacher eskul if applicable)
        if ($isTeacher) {
            if ($teacherEskulId) {
                $classData = Student::activeYear()
                    ->whereHas('eskuls', function($q) use ($teacherEskulId, $activeYear) {
                        $q->where('student_eskul.eskul_id', $teacherEskulId);
                        if ($activeYear) {
                            $q->where('student_eskul.academic_year_id', $activeYear->id)
                              ->where('student_eskul.semester', $activeYear->active_semester);
                        }
                    })
                    ->select('class', DB::raw('count(*) as count'))
                    ->groupBy('class')
                    ->get();
            } elseif (!empty($user->homeroom_class)) {
                $classData = Student::activeYear()
                    ->where('class', $user->homeroom_class)
                    ->select('class', DB::raw('count(*) as count'))
                    ->groupBy('class')
                    ->get();
            } else {
                $classData = collect();
            }
        } else {
            $classData = Student::activeYear()
                ->select('class', DB::raw('count(*) as count'))
                ->groupBy('class')
                ->get();
        }

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
        ksort($gradeStatistics);

        // 3. Actionable: Eskuls Missing Attendance This Week (Active Year/Semester Only)
        $daysMap = [
            'senin'   => 'Monday',
            'selasa'  => 'Tuesday',
            'rabu'    => 'Wednesday',
            'kamis'   => 'Thursday',
            'jumat'   => 'Friday',
            'sabtu'   => 'Saturday',
            'minggu'  => 'Sunday',
        ];

        $activeEskuls = Eskul::activeYear()->whereHas('students', function($q) use ($activeYear) {
             if ($activeYear) {
                $q->where('student_eskul.academic_year_id', $activeYear->id)
                  ->where('student_eskul.semester', $activeYear->active_semester);
            }
        })
        ->when($isTeacher, function($q) use ($teacherEskulId) {
            return $q->where('id', $teacherEskulId);
        })
        ->get();

        $eskulMissingAttendance = [];
        $todayStr = now()->toDateString();

        foreach ($activeEskuls as $eskul) {
            if (empty($eskul->schedule)) continue;

            // Find target day in schedule
            $targetDayEnglish = null;
            $scheduleLower = strtolower($eskul->schedule);
            foreach ($daysMap as $indo => $eng) {
                if (str_contains($scheduleLower, $indo)) {
                    $targetDayEnglish = $eng;
                    break;
                }
            }

            if (!$targetDayEnglish) continue;

            // Resolve target date in the current Friday-Thursday cycle
            $scheduleDate = null;
            $nowCarbon = now();
            $cycleStart = ($nowCarbon->dayOfWeek === \Carbon\Carbon::FRIDAY)
                ? $nowCarbon->copy()
                : $nowCarbon->copy()->previous(\Carbon\Carbon::FRIDAY);

            for ($d = 0; $d < 7; $d++) {
                $dateInWeek = $cycleStart->copy()->addDays($d);
                if ($dateInWeek->format('l') === $targetDayEnglish) {
                    $scheduleDate = $dateInWeek->toDateString();
                    break;
                }
            }

            // Only check if schedule date is today or in the past
            if ($scheduleDate && $scheduleDate <= $todayStr) {
                // Check student attendance
                $studentAttendanceExists = \App\Models\Attendance::where('eskul_id', $eskul->id)
                    ->whereDate('date', $scheduleDate)
                    ->exists();

                // Find pembina
                $pembina = \App\Models\User::where('role', 'teacher')
                    ->where('eskul_id', $eskul->id)
                    ->activeYear()
                    ->first();

                // Check teacher attendance
                $teacherAttendanceExists = true;
                if ($pembina) {
                    $teacherAttendanceExists = \App\Models\TeacherAttendance::where('user_id', $pembina->id)
                        ->whereDate('date', $scheduleDate)
                        ->exists();
                }

                $studentMissing = !$studentAttendanceExists;
                $teacherMissing = !$teacherAttendanceExists;

                if ($studentMissing || $teacherMissing) {
                    $eskulMissingAttendance[] = (object)[
                        'id' => $eskul->id,
                        'name' => $eskul->name,
                        'schedule' => $eskul->schedule,
                        'date' => $scheduleDate,
                        'pembina' => $pembina,
                        'student_missing' => $studentMissing,
                        'teacher_missing' => $teacherMissing,
                    ];
                }
            }
        }

        // Limit to 5 items to keep dashboard clean
        $eskulMissingAttendance = array_slice($eskulMissingAttendance, 0, 5);

        // --- CHART DATA PREPARATION ---

        // 1. Eskul Popularity (Top 5)
        $popularEskuls = Eskul::activeYear()->withCount(['students' => function($q) use ($activeYear) {
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
        
        $todaySchedule = Eskul::activeYear()->where('schedule', 'LIKE', "%{$todayName}%")
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
            ->when($isTeacher, function($q) use ($teacherEskulId) {
                $q->where('student_eskul.eskul_id', $teacherEskulId);
            })
            ->where('students.status', 'active')
            ->groupBy('class')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 6. Accountability: Recent Activities (Last 5 Attendance Inputs)
        $recentActivities = \App\Models\Attendance::with(['student', 'eskul'])
            ->when($activeYear, function($q) use ($activeYear) {
                return $q->where('academic_year_id', $activeYear->id)
                         ->where('semester', $activeYear->active_semester);
            })
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

        // 9. Calculate Participant Counts by Category (Olahraga, Sains, Bahasa, Seni)
        $categoryQuery = Eskul::activeYear();
        if ($isTeacher) {
            $categoryQuery->where('id', $teacherEskulId);
        }

        $eskulsWithStudents = $categoryQuery->with(['students' => function($q) use ($yearId, $semester) {
            if ($yearId) {
                $q->where('student_eskul.academic_year_id', $yearId)
                  ->where('student_eskul.semester', $semester);
            }
            $q->where('status', '!=', 'graduated');
        }])->get();

        $categoryCounts = [
            'Olahraga' => 0,
            'Sains' => 0,
            'Bahasa' => 0,
            'Seni' => 0,
            'Lainnya' => 0,
        ];

        foreach ($eskulsWithStudents as $eskul) {
            $cat = 'Lainnya';
            $nameLower = strtolower($eskul->name);
            if (str_contains($nameLower, 'futsal') || str_contains($nameLower, 'karate') || str_contains($nameLower, 'panahan') || str_contains($nameLower, 'badminton') || str_contains($nameLower, 'pramuka')) {
                $cat = 'Olahraga';
            } elseif (str_contains($nameLower, 'matematika') || str_contains($nameLower, 'science') || str_contains($nameLower, 'sains') || str_contains($nameLower, 'ipa')) {
                $cat = 'Sains';
            } elseif (str_contains($nameLower, 'english') || str_contains($nameLower, 'bahasa') || str_contains($nameLower, 'calistung')) {
                $cat = 'Bahasa';
            } elseif (str_contains($nameLower, 'seni') || str_contains($nameLower, 'lukis') || str_contains($nameLower, 'vokal') || str_contains($nameLower, 'tari') || str_contains($nameLower, 'musik')) {
                $cat = 'Seni';
            }
            
            if (isset($categoryCounts[$cat])) {
                $categoryCounts[$cat] += $eskul->students->count();
            }
        }
        
        // Wali Kelas Context
        $homeroomClass = $isTeacher ? $user->homeroom_class : null;
        $isHomeroomTeacher = !empty($homeroomClass);
        
        $homeroomStudentCount = 0;
        $homeroomRegisteredCount = 0;
        $homeroomUnregisteredCount = 0;
        $homeroomUnregisteredList = collect();
        $homeroomEskulDistribution = collect();
        $homeroomMunaqosahResults = collect();
        $homeroomUpcomingExams = collect();
        $homeroomMunaqosahStats = [
            'total_tested' => 0,
            'passed' => 0,
            'failed' => 0,
            'waiting' => 0,
        ];

        if ($isHomeroomTeacher) {
            $class = $homeroomClass;
            
            // Total active students in homeroom class
            $homeroomStudentCount = Student::activeYear()
                ->where('class', $class)
                ->where(function($q) {
                    $q->where('status', '!=', 'graduated')->orWhereNull('status');
                })
                ->count();
                
            // Students who have selected at least 1 eskul
            $homeroomRegisteredStudents = Student::activeYear()
                ->where('class', $class)
                ->where(function($q) {
                    $q->where('status', '!=', 'graduated')->orWhereNull('status');
                })
                ->whereHas('eskuls', function($q) use ($yearId, $semester) {
                    if ($yearId) {
                        $q->where('student_eskul.academic_year_id', $yearId)
                          ->where('student_eskul.semester', $semester);
                    }
                })
                ->get();
                
            $homeroomRegisteredCount = $homeroomRegisteredStudents->count();
            
            // Students who have NOT selected any eskul
            $homeroomUnregisteredList = Student::activeYear()
                ->where('class', $class)
                ->where(function($q) {
                    $q->where('status', '!=', 'graduated')->orWhereNull('status');
                })
                ->whereDoesntHave('eskuls', function($q) use ($yearId, $semester) {
                    if ($yearId) {
                        $q->where('student_eskul.academic_year_id', $yearId)
                          ->where('student_eskul.semester', $semester);
                    }
                })
                ->orderBy('name')
                ->get();
                
            $homeroomUnregisteredCount = $homeroomUnregisteredList->count();
            
            // Sebaran Eskul Kelas Binaan
            $homeroomEskulDistribution = DB::table('student_eskul')
                ->join('students', 'student_eskul.student_id', '=', 'students.id')
                ->join('eskuls', 'student_eskul.eskul_id', '=', 'eskuls.id')
                ->where('students.class', $class)
                ->where('student_eskul.academic_year_id', $yearId)
                ->where('student_eskul.semester', $semester)
                ->where(function($q) {
                    $q->where('students.status', '!=', 'graduated')->orWhereNull('students.status');
                })
                ->select('eskuls.name', DB::raw('count(*) as total'))
                ->groupBy('eskuls.name')
                ->orderByDesc('total')
                ->get();

            // Hasil & Antrean Munaqosah Kelas Binaan
            if (Schema::hasTable('munaqosah_registrations')) {
                $homeroomMunaqosahQuery = \App\Models\MunaqosahRegistration::with([
                    'student',
                    'examiner.user',
                    'examinerUser',
                    'fromGroup',
                    'promotedToGroup'
                ])
                ->whereHas('student', function($q) use ($class) {
                    $q->where('class', $class);
                })
                ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId));

                // Hasil yang sudah diuji oleh penguji munaqisy (Lulus maupun Belum Lulus)
                $homeroomMunaqosahResults = (clone $homeroomMunaqosahQuery)
                    ->whereIn('status', ['passed', 'failed'])
                    ->orderBy('tested_at', 'desc')
                    ->limit(10)
                    ->get();

                // Santri kelas yang sedang terjadwal / menunggu ujian
                $homeroomUpcomingExams = (clone $homeroomMunaqosahQuery)
                    ->whereIn('status', ['pending', 'scheduled'])
                    ->orderBy('exam_date', 'asc')
                    ->limit(6)
                    ->get();

                $homeroomMunaqosahStats['total_tested'] = (clone $homeroomMunaqosahQuery)->whereIn('status', ['passed', 'failed'])->count();
                $homeroomMunaqosahStats['passed'] = (clone $homeroomMunaqosahQuery)->where('status', 'passed')->count();
                $homeroomMunaqosahStats['failed'] = (clone $homeroomMunaqosahQuery)->where('status', 'failed')->count();
                $homeroomMunaqosahStats['waiting'] = (clone $homeroomMunaqosahQuery)->whereIn('status', ['pending', 'scheduled'])->count();
            }
        }

        // 6. Eskul Attendance Compliance Check (Missing Dates)
        $missingDates = [];
        $teacherEskul = null;
        if ($isTeacher && $teacherEskulId) {
            $teacherEskul = Eskul::find($teacherEskulId);
            if ($teacherEskul) {
                $missingDates = $teacherEskul->getMissingAttendanceDates(30);
            }
        }

        // 7. Kelompok Mentoring (Tilawati & Tahfidz) untuk Guru
        $myTilawatiGroups = collect();
        $myTahfidzGroups = collect();
        $myMentoringStudentCount = 0;
        $mentorMunaqosahResults = collect();

        if ($isTeacher) {
            $myTilawatiGroups = \App\Models\MentoringGroup::where('type', 'tilawati')
                ->where(function($q) use ($user) {
                    $q->where('mentor_user_id', $user->id)
                      ->orWhere('mentor_name', $user->name);
                })
                ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                ->withCount('students')
                ->get();

            $myTahfidzGroups = \App\Models\MentoringGroup::where('type', 'tahfidz')
                ->where(function($q) use ($user) {
                    $q->where('mentor_user_id', $user->id)
                      ->orWhere('mentor_name', $user->name);
                })
                ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                ->withCount('students')
                ->get();

            $allMyMentoringGroupIds = $myTilawatiGroups->pluck('id')->merge($myTahfidzGroups->pluck('id'))->unique();
            if ($allMyMentoringGroupIds->isNotEmpty()) {
                $myMentoringStudentCount = DB::table('mentoring_group_students')
                    ->whereIn('mentoring_group_id', $allMyMentoringGroupIds)
                    ->distinct('student_id')
                    ->count('student_id');

                // Hasil Ujian Munaqosah Santri Binaan Halaqah Qur'an
                if (Schema::hasTable('munaqosah_registrations')) {
                    $mentorMunaqosahResults = \App\Models\MunaqosahRegistration::with([
                        'student',
                        'examiner.user',
                        'examinerUser',
                        'fromGroup',
                        'promotedToGroup'
                    ])
                    ->where(function($q) use ($user, $allMyMentoringGroupIds) {
                        $q->whereIn('from_group_id', $allMyMentoringGroupIds)
                          ->orWhere('proposer_user_id', $user->id)
                          ->orWhereHas('student.mentoringGroups', function($sub) use ($allMyMentoringGroupIds) {
                              $sub->whereIn('mentoring_groups.id', $allMyMentoringGroupIds);
                          });
                    })
                    ->whereIn('status', ['passed', 'failed'])
                    ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                    ->orderBy('tested_at', 'desc')
                    ->limit(8)
                    ->get();
                }
            }

            if ($studentCount === 0 && $myMentoringStudentCount > 0) {
                $studentCount = $myMentoringStudentCount;
            }
        }

        // 8. Penugasan Guru Mapel (Mata Pelajaran Kurikulum Merdeka)
        $mySubjects = collect();
        $mySubjectsCount = 0;
        if ($isTeacher && !empty($user->teaching_subject_ids)) {
            $mySubjects = \App\Models\Subject::whereIn('id', $user->teaching_subject_ids)->orderBy('order_num')->get();
            $mySubjectsCount = $mySubjects->count();
        }

        // 9. Hitung total peran aktif untuk switcher dashboard multi-tugas
        $teacherRoleCount = 0;
        if ($isTeacher) {
            if ($isHomeroomTeacher) $teacherRoleCount++;
            if ($teacherEskul) $teacherRoleCount++;
            if ($myTilawatiGroups->isNotEmpty() || $myTahfidzGroups->isNotEmpty()) $teacherRoleCount++;
            if ($mySubjectsCount > 0) $teacherRoleCount++;
        }

        // 10. Indikator Ketercapaian Mutu & Program Unggulan (Tilawati, Tahfidz, Akademik, Karakter)
        $totalActiveGlobalStudents = Student::forYear($yearId)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')->orWhereNull('status');
            })
            ->count();

        // A. Ketercapaian Tilawati
        $tilawatiAssignedStudents = 0;
        $tilawatiAdvancedStudents = 0;
        $tilawatiPercentage = 0;
        $tilawatiCoveragePercentage = 0;

        if (Schema::hasTable('mentoring_group_students') && Schema::hasTable('mentoring_groups')) {
            $tilawatiAssignedStudents = DB::table('mentoring_group_students')
                ->join('mentoring_groups', 'mentoring_group_students.mentoring_group_id', '=', 'mentoring_groups.id')
                ->where('mentoring_groups.type', 'tilawati')
                ->when($yearId, fn($q) => $q->where('mentoring_groups.academic_year_id', $yearId))
                ->distinct('mentoring_group_students.student_id')
                ->count('mentoring_group_students.student_id');

            $tilawatiAdvancedStudents = DB::table('mentoring_group_students')
                ->join('mentoring_groups', 'mentoring_group_students.mentoring_group_id', '=', 'mentoring_groups.id')
                ->where('mentoring_groups.type', 'tilawati')
                ->when($yearId, fn($q) => $q->where('mentoring_groups.academic_year_id', $yearId))
                ->where(function($q) {
                    $q->where('mentoring_groups.target_level', 'LIKE', '%Jilid 4%')
                      ->orWhere('mentoring_groups.target_level', 'LIKE', '%Jilid 5%')
                      ->orWhere('mentoring_groups.target_level', 'LIKE', '%Jilid 6%')
                      ->orWhere('mentoring_groups.target_level', 'LIKE', '%Al-Qur%')
                      ->orWhere('mentoring_group_students.notes', 'LIKE', '%Jilid 4%')
                      ->orWhere('mentoring_group_students.notes', 'LIKE', '%Jilid 5%')
                      ->orWhere('mentoring_group_students.notes', 'LIKE', '%Jilid 6%')
                      ->orWhere('mentoring_group_students.notes', 'LIKE', '%Al-Qur%')
                      ->orWhere('mentoring_group_students.notes', 'LIKE', '%Tuntas%')
                      ->orWhere('mentoring_group_students.notes', 'LIKE', '%Lulus%');
                })
                ->distinct('mentoring_group_students.student_id')
                ->count('mentoring_group_students.student_id');

            $tilawatiPercentage = $tilawatiAssignedStudents > 0 
                ? round(($tilawatiAdvancedStudents / $tilawatiAssignedStudents) * 100) 
                : 0;

            $tilawatiCoveragePercentage = $totalActiveGlobalStudents > 0
                ? round(($tilawatiAssignedStudents / $totalActiveGlobalStudents) * 100)
                : 0;
        }

        // B. Ketercapaian Tahfidz
        $totalTahfidzRecords = 0;
        $tahfidzLancarRecords = 0;
        $tahfidzCukupRecords = 0;
        $tahfidzPerluPerbaikanRecords = 0;
        $tahfidzActiveStudents = 0;
        $tahfidzPercentage = 0;

        if (Schema::hasTable('tahfidz_records')) {
            $tahfidzRecordQuery = \App\Models\TahfidzRecord::when($yearId, fn($q) => $q->where('academic_year_id', $yearId));
            $totalTahfidzRecords = (clone $tahfidzRecordQuery)->count();
            $tahfidzLancarRecords = (clone $tahfidzRecordQuery)->where('quality', 'lancar')->count();
            $tahfidzCukupRecords = (clone $tahfidzRecordQuery)->where('quality', 'cukup')->count();
            $tahfidzPerluPerbaikanRecords = (clone $tahfidzRecordQuery)->where('quality', 'perlu_perbaikan')->count();
            $tahfidzActiveStudents = (clone $tahfidzRecordQuery)->distinct('student_id')->count('student_id');

            $tahfidzPercentage = $totalTahfidzRecords > 0 
                ? round(($tahfidzLancarRecords / $totalTahfidzRecords) * 100) 
                : 0;
        }

        // C. Ketercapaian Akademik
        $totalAcademicScores = 0;
        $passedAcademicScores = 0;
        $averageAcademicScore = 0;
        $academicPercentage = 0;

        if (Schema::hasTable('academic_scores')) {
            $academicScoreQuery = \App\Models\AcademicScore::when($yearId, fn($q) => $q->where('academic_year_id', $yearId));
            $totalAcademicScores = (clone $academicScoreQuery)->count();
            $passedAcademicScores = (clone $academicScoreQuery)->where('score', '>=', 75)->count();
            $averageAcademicScore = $totalAcademicScores > 0 ? round((clone $academicScoreQuery)->avg('score'), 1) : 0;

            $academicPercentage = $totalAcademicScores > 0 
                ? round(($passedAcademicScores / $totalAcademicScores) * 100) 
                : 0;
        }

        // D. Ketercapaian Karakter
        $totalCases = 0;
        $resolvedCases = 0;
        $activeCases = 0;
        $totalAppreciations = 0;
        $totalAppreciationPoints = 0;
        $studentsWithActiveCases = 0;
        $characterPercentage = 100;
        $caseResolutionRate = 100;

        if (Schema::hasTable('character_cases') && Schema::hasTable('character_appreciations')) {
            $caseQuery = \App\Models\CharacterCase::when($yearId, fn($q) => $q->where('academic_year_id', $yearId));
            $totalCases = (clone $caseQuery)->count();
            $resolvedCases = (clone $caseQuery)->where('status', 'selesai')->count();
            $activeCases = (clone $caseQuery)->where('status', 'dalam_pembinaan')->count();
            $studentsWithActiveCases = (clone $caseQuery)->where('status', 'dalam_pembinaan')->distinct('student_id')->count('student_id');

            $appreciationQuery = \App\Models\CharacterAppreciation::when($yearId, fn($q) => $q->where('academic_year_id', $yearId));
            $totalAppreciations = (clone $appreciationQuery)->count();
            $totalAppreciationPoints = (clone $appreciationQuery)->sum('point');

            $characterPercentage = $totalActiveGlobalStudents > 0
                ? round((max(0, $totalActiveGlobalStudents - $studentsWithActiveCases) / $totalActiveGlobalStudents) * 100)
                : 100;

            $caseResolutionRate = $totalCases > 0 ? round(($resolvedCases / $totalCases) * 100) : 100;
        }

        $programAchievements = [
            'tilawati' => [
                'percentage' => $tilawatiPercentage,
                'coverage_percentage' => $tilawatiCoveragePercentage,
                'assigned_count' => $tilawatiAssignedStudents,
                'advanced_count' => $tilawatiAdvancedStudents,
                'total_students' => $totalActiveGlobalStudents,
            ],
            'tahfidz' => [
                'percentage' => $tahfidzPercentage,
                'total_records' => $totalTahfidzRecords,
                'lancar_count' => $tahfidzLancarRecords,
                'cukup_count' => $tahfidzCukupRecords,
                'perlu_perbaikan_count' => $tahfidzPerluPerbaikanRecords,
                'active_students' => $tahfidzActiveStudents,
            ],
            'academic' => [
                'percentage' => $academicPercentage,
                'total_scores' => $totalAcademicScores,
                'passed_scores' => $passedAcademicScores,
                'average_score' => $averageAcademicScore,
            ],
            'character' => [
                'percentage' => $characterPercentage,
                'total_cases' => $totalCases,
                'resolved_cases' => $resolvedCases,
                'active_cases' => $activeCases,
                'total_appreciations' => $totalAppreciations,
                'appreciation_points' => $totalAppreciationPoints,
                'resolution_rate' => $caseResolutionRate,
            ],
        ];

        // E. Siswa Lulus Munaqosah yang Menunggu Dipindahkan Jenjang / Kelompok
        $pendingMunaqosahPromotions = collect();
        if (Schema::hasTable('munaqosah_registrations')) {
            $pendingMunaqosahPromotions = \App\Models\MunaqosahRegistration::with([
                    'student', 
                    'academicYear', 
                    'examinerUser', 
                    'examiner.user',
                    'fromGroup'
                ])
                ->where('status', 'passed')
                ->where('transition_status', 'pending_promotion')
                ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                ->orderBy('tested_at', 'desc')
                ->get();
        }

        $allMentoringGroups = collect();
        if (Schema::hasTable('mentoring_groups')) {
            $allMentoringGroups = \App\Models\MentoringGroup::when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                ->orderBy('type')
                ->orderBy('name')
                ->get();
        }

        // 13. Pengingat Dewan Munaqisy (Khusus Akun Penguji Terdaftar Aktif)
        $isMunaqisyExaminer = false;
        $myUpcomingExams = collect();
        if ($user && Schema::hasTable('munaqisy_examiners')) {
            $examinerRecord = \App\Models\MunaqisyExaminer::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();
            if ($examinerRecord) {
                $isMunaqisyExaminer = true;
                if (Schema::hasTable('munaqosah_registrations')) {
                    $myUpcomingExams = \App\Models\MunaqosahRegistration::with(['student', 'examiner.user', 'schedule'])
                        ->where(function($q) use ($user, $examinerRecord) {
                            $q->where('examiner_user_id', $user->id)
                              ->orWhere('examiner_id', $examinerRecord->id);
                        })
                        ->whereIn('status', ['registered', 'pending', 'scheduled'])
                        ->orderBy('exam_date', 'asc')
                        ->orderBy('created_at', 'desc')
                        ->get();
                }
            }
        }

        // Tentukan apakah user ini HANYA bertugas sebagai dewan penguji/munaqisy
        $isOnlyExaminer = false;
        if ($isMunaqisyExaminer && !in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            if (!$isHomeroomTeacher && !$teacherEskul && $myTilawatiGroups->isEmpty() && $myTahfidzGroups->isEmpty() && ($mySubjectsCount ?? 0) === 0) {
                $isOnlyExaminer = true;
            }
        }

        return view('dashboard', compact(
            'studentCount', 'eskulCount', 'teacherCount', 'gradeStatistics', 
            'chartEskulLabels', 'chartEskulData', 'chartAttendanceData', 
            'eskulMissingAttendance', 'activeYear',
            'todaySchedule', 'topClasses', 'recentActivities',
            'teacherAttendanceToday', 'teacherAttendancePresent', 'registeredTeacherAccounts',
            'announcements', 'categoryCounts',
            
            'homeroomClass', 'isHomeroomTeacher', 'homeroomStudentCount', 
            'homeroomRegisteredCount', 'homeroomUnregisteredCount', 
            'homeroomUnregisteredList', 'homeroomEskulDistribution',
            'homeroomMunaqosahResults', 'homeroomUpcomingExams', 'homeroomMunaqosahStats',
            
            'missingDates', 'teacherEskul',
            
            'myTilawatiGroups', 'myTahfidzGroups', 'myMentoringStudentCount', 'mentorMunaqosahResults',
            'mySubjects', 'mySubjectsCount', 'teacherRoleCount',
            'programAchievements',
            'pendingMunaqosahPromotions',
            'allMentoringGroups',
            'isMunaqisyExaminer',
            'isOnlyExaminer',
            'myUpcomingExams'
        ));
    }

    /**
     * Dashboard khusus akun TU (Tata Usaha) dengan fokus penuh pada seluruh data siswa.
     */
    protected function tuDashboard($activeYear)
    {
        $yearId = $activeYear ? $activeYear->id : null;

        // Total Siswa
        $totalStudents = Student::activeYear()->active()->count();
        $totalAlumni = Student::activeYear()->whereIn('status', ['graduated', 'lulus'])->count();

        // Gender breakdown
        $maleStudents = Student::activeYear()->active()->where('gender', 'L')->count();
        $femaleStudents = Student::activeYear()->active()->where('gender', 'P')->count();
        $otherGender = max(0, $totalStudents - ($maleStudents + $femaleStudents));

        // School Program
        $fullDayStudents = Student::activeYear()->active()->where('school_program', 'Full Day')->count();
        $boardingStudents = Student::activeYear()->active()->where('school_program', 'Boarding')->count();

        // NISN Completeness
        $completeNisn = Student::activeYear()->active()->whereNotNull('nisn')->where('nisn', '!=', '')->count();
        $missingNisn = max(0, $totalStudents - $completeNisn);

        // Data Ortu Completeness
        $withParentData = Student::activeYear()->active()
            ->where(function($q) {
                $q->where(function($sq) {
                    $sq->whereNotNull('father_name')->where('father_name', '!=', '');
                })->orWhere(function($sq) {
                    $sq->whereNotNull('mother_name')->where('mother_name', '!=', '');
                });
            })->count();

        // Rekap Per Tingkat Kelas (1 s/d 6)
        $classes = Student::activeYear()->active()
            ->select(
                'class',
                DB::raw('count(*) as count'),
                DB::raw('sum(case when gender = "L" then 1 else 0 end) as male_count'),
                DB::raw('sum(case when gender = "P" then 1 else 0 end) as female_count')
            )
            ->groupBy('class')
            ->orderBy('class')
            ->get();

        $gradeStatistics = [];
        foreach ($classes as $c) {
            preg_match('/^\d+/', $c->class, $matches);
            $grade = $matches[0] ?? 'Lainnya';
            if (!isset($gradeStatistics[$grade])) {
                $gradeStatistics[$grade] = [
                    'grade' => $grade,
                    'total' => 0,
                    'male' => 0,
                    'female' => 0,
                    'classes' => []
                ];
            }
            $gradeStatistics[$grade]['total'] += (int)$c->count;
            $gradeStatistics[$grade]['male'] += (int)$c->male_count;
            $gradeStatistics[$grade]['female'] += (int)$c->female_count;
            $gradeStatistics[$grade]['classes'][] = [
                'name' => $c->class,
                'count' => (int)$c->count,
                'male' => (int)$c->male_count,
                'female' => (int)$c->female_count
            ];
        }
        ksort($gradeStatistics);

        // Siswa Terbaru (15 siswa)
        $recentStudents = Student::activeYear()->active()
            ->with('eskuls')
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        // Aktivitas log siswa terbaru (jika ada)
        $studentLogs = [];
        if (Schema::hasTable('activity_logs')) {
            $studentLogs = \App\Models\ActivityLog::where('module', 'Students')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
        }

        return view('dashboard_tu', compact(
            'activeYear',
            'totalStudents',
            'totalAlumni',
            'maleStudents',
            'femaleStudents',
            'otherGender',
            'fullDayStudents',
            'boardingStudents',
            'completeNisn',
            'missingNisn',
            'withParentData',
            'gradeStatistics',
            'recentStudents',
            'studentLogs'
        ));
    }
}

