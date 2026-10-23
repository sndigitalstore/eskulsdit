<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Eskul;
use App\Models\Grade;
use App\Models\MentoringGroup;
use App\Models\Student;
use App\Models\TahfidzAttendance;
use App\Models\TahfidzRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isTeacher = $user->role === 'teacher';
        $isHomeroom = $isTeacher && !empty($user->homeroom_class);
        $isCurriculum = $user->role === 'curriculum';

        $activeTab = $request->query('tab');
        if ($activeTab === 'akademik') {
            return redirect()->route('academic-reports.index');
        }
        if ($activeTab === 'karakter') {
            return redirect()->route('character.report');
        }
        if (!$activeTab) {
            $activeTab = $isCurriculum ? 'tilawati' : 'eskul';
        }
        if ($isCurriculum && $activeTab === 'eskul') {
            $activeTab = 'tilawati';
        }
        if (!in_array($activeTab, ['eskul', 'tilawati', 'tahfidz'])) {
            $activeTab = $isCurriculum ? 'tilawati' : 'eskul';
        }

        // Get active academic year
        $activeYear = AcademicYear::where('is_active', true)->first();
        // Get all academic years for dropdown
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        $selectedClass = $request->class;
        $selectedPeriod = $request->period ?? 'all'; // 1, 2, or all
        $selectedYearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $selectedGroupId = $request->group_id;

        if ($isHomeroom) {
            $selectedClass = $user->homeroom_class;
            $classes = collect([$user->homeroom_class]);
        } else {
            // Get all unique classes for the filter dropdown, scoped to the selected year
            $classes = Student::forYear($selectedYearId)
                ->select('class')->distinct()->orderBy('class')->pluck('class');
        }

        $students = [];
        $allClassesData = [];
        $tilawatiGroups = collect();
        $tahfidzGroups = collect();
        $quranStats = [];

        if ($activeTab === 'tilawati') {
            $tilawatiGroups = MentoringGroup::where('type', 'tilawati')
                ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
                ->when($isTeacher && !$isHomeroom, function($q) use ($user) {
                    $q->where(function($sub) use ($user) {
                        $sub->where('mentor_user_id', $user->id)
                            ->orWhere('mentor_name', $user->name);
                    });
                })
                ->orderBy('name')
                ->get();

            if ($selectedClass || $selectedGroupId) {
                $studentsQuery = Student::forYear($selectedYearId)
                    ->where(function($q) {
                        $q->where('status', '!=', 'graduated')->orWhereNull('status');
                    });

                if ($selectedClass && $selectedClass !== 'ALL') {
                    $studentsQuery->where('class', $selectedClass);
                }

                if ($selectedGroupId) {
                    $studentsQuery->whereHas('mentoringGroups', function($q) use ($selectedGroupId) {
                        $q->where('mentoring_groups.id', $selectedGroupId);
                    });
                }

                $students = $studentsQuery->with([
                    'mentoringGroups' => function($q) use ($selectedYearId) {
                        $q->where('mentoring_groups.type', 'tilawati')
                          ->when($selectedYearId, fn($sub) => $sub->where('mentoring_groups.academic_year_id', $selectedYearId));
                    }
                ])->orderBy('class')->orderBy('name')->get();

                // Compute Tilawati KPI
                $totalSiswa = $students->count();
                $assignedCount = 0;
                $jilidCounts = [
                    'Jilid 1' => 0, 'Jilid 2' => 0, 'Jilid 3' => 0,
                    'Jilid 4' => 0, 'Jilid 5' => 0, 'Jilid 6' => 0,
                    'Al-Qur\'an' => 0, 'Lainnya' => 0
                ];

                foreach ($students as $std) {
                    $grp = $std->mentoringGroups->first();
                    if ($grp) {
                        $assignedCount++;
                        $target = $grp->target_level ?? $grp->pivot->notes ?? '';
                        $matched = false;
                        foreach (['Jilid 1', 'Jilid 2', 'Jilid 3', 'Jilid 4', 'Jilid 5', 'Jilid 6', 'Al-Qur\'an'] as $j) {
                            if (stripos($target, $j) !== false) {
                                $jilidCounts[$j]++;
                                $matched = true;
                                break;
                            }
                        }
                        if (!$matched) {
                            $jilidCounts['Lainnya']++;
                        }
                    }
                }

                $quranStats = [
                    'total_students' => $totalSiswa,
                    'assigned_count' => $assignedCount,
                    'unassigned_count' => max(0, $totalSiswa - $assignedCount),
                    'jilid_counts' => $jilidCounts,
                ];
            }
        } elseif ($activeTab === 'tahfidz') {
            $tahfidzGroups = MentoringGroup::where('type', 'tahfidz')
                ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
                ->when($isTeacher && !$isHomeroom, function($q) use ($user) {
                    $q->where(function($sub) use ($user) {
                        $sub->where('mentor_user_id', $user->id)
                            ->orWhere('mentor_name', $user->name);
                    });
                })
                ->orderBy('name')
                ->get();

            // Self-healing check for live environments
            \App\Http\Controllers\TahfidzAttendanceController::ensureTableExists();

            // Resolve Period (Semua / Semester 1 / Semester 2 / Bulanan)
            $selectedPeriodType = $request->input('period_type', 'all');
            $selectedMonth = $request->input('month');
            $periodInfo = self::resolveTahfidzPeriod($selectedPeriodType, $selectedMonth, $selectedYearId);
            $startDate = $periodInfo['start_date'];
            $endDate = $periodInfo['end_date'];
            $periodTitle = $periodInfo['period_title'];
            $periodLabel = $periodInfo['period_label'];
            $academicMonths = $periodInfo['academic_months'];

            if ($selectedClass || $selectedGroupId) {
                $studentsQuery = Student::forYear($selectedYearId)
                    ->where(function($q) {
                        $q->where('status', '!=', 'graduated')->orWhereNull('status');
                    });

                if ($selectedClass && $selectedClass !== 'ALL') {
                    $studentsQuery->where('class', $selectedClass);
                }

                if ($selectedGroupId) {
                    $studentsQuery->whereHas('mentoringGroups', function($q) use ($selectedGroupId) {
                        $q->where('mentoring_groups.id', $selectedGroupId);
                    });
                }

                $students = $studentsQuery->with([
                    'mentoringGroups' => function($q) use ($selectedYearId) {
                        $q->where('mentoring_groups.type', 'tahfidz')
                          ->when($selectedYearId, fn($sub) => $sub->where('mentoring_groups.academic_year_id', $selectedYearId));
                    },
                    'tahfidzRecords' => function($q) use ($selectedYearId, $startDate, $endDate) {
                        $q->when($selectedYearId, fn($sub) => $sub->where('academic_year_id', $selectedYearId))
                          ->when($startDate && $endDate, fn($sub) => $sub->whereBetween('session_date', [$startDate, $endDate]))
                          ->orderBy('session_date', 'desc')
                          ->orderBy('id', 'desc');
                    }
                ])->orderBy('class')->orderBy('name')->get();

                // Compute Tahfidz KPI
                $totalSiswa = $students->count();
                $totalSetoran = 0;
                $totalBarisAll = 0;
                $totalLancar = 0;
                $allStudentIds = $students->pluck('id');
                $allScores = [];

                // Load attendances in one batch with period date filter
                $attendances = TahfidzAttendance::whereIn('student_id', $allStudentIds)
                    ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('session_date', [$startDate, $endDate]))
                    ->get()
                    ->groupBy('student_id');

                foreach ($students as $std) {
                    $records = $std->tahfidzRecords;
                    $std->total_records_count = $records->count();
                    $std->total_baris = (int) $records->sum('jumlah_baris');
                    $std->baru_count = $records->where('type', 'setoran_baru')->count();
                    $std->murojaah_count = $records->where('type', 'murojaah')->count();
                    $std->latest_record = $records->first();

                    $lancar = $records->where('quality', 'lancar')->count();
                    $cukup = $records->where('quality', 'cukup')->count();
                    $perbaikan = $records->where('quality', 'perlu_perbaikan')->count();

                    $std->quality_counts = [
                        'lancar' => $lancar,
                        'cukup' => $cukup,
                        'perlu_perbaikan' => $perbaikan,
                    ];

                    $totalSetoran += $records->count();
                    $totalBarisAll += $std->total_baris;
                    $totalLancar += $lancar;

                    // Nilai Rata-rata
                    $numScores = $records->pluck('nilai')->filter(fn($v) => is_numeric($v))->map(fn($v) => (float)$v);
                    if ($numScores->isNotEmpty()) {
                        $std->avg_nilai = round($numScores->average(), 1);
                        foreach ($numScores as $sc) {
                            $allScores[] = $sc;
                        }
                    } else {
                        $std->avg_nilai = null;
                    }

                    // Predikat Mutu
                    if ($std->avg_nilai !== null) {
                        if ($std->avg_nilai >= 90) $std->predikat_label = 'Mumtaz (A)';
                        elseif ($std->avg_nilai >= 80) $std->predikat_label = 'Jayyid Jiddan (B)';
                        elseif ($std->avg_nilai >= 70) $std->predikat_label = 'Jayyid (C)';
                        else $std->predikat_label = 'Maqbul (D)';
                    } elseif ($std->total_records_count > 0) {
                        $lancarRate = round(($lancar / $std->total_records_count) * 100);
                        if ($lancarRate >= 80) $std->predikat_label = 'Mutqin / Lancar';
                        elseif ($lancarRate >= 50) $std->predikat_label = 'Cukup Lancar';
                        else $std->predikat_label = 'Perlu Bimbingan';
                    } else {
                        $std->predikat_label = '-';
                    }

                    $stdAttendances = $attendances->get($std->id, collect());
                    $std->attendance_summary = [
                        'hadir' => $stdAttendances->where('status', 'hadir')->count(),
                        'sakit' => $stdAttendances->where('status', 'sakit')->count(),
                        'izin'  => $stdAttendances->where('status', 'izin')->count(),
                        'alpha' => $stdAttendances->where('status', 'alpha')->count(),
                        'total' => $stdAttendances->count(),
                    ];
                }

                $lancarPercentage = $totalSetoran > 0 ? round(($totalLancar / $totalSetoran) * 100, 1) : 0;
                $overallAvgNilai = count($allScores) > 0 ? round(array_sum($allScores) / count($allScores), 1) : '-';

                $quranStats = [
                    'total_students' => $totalSiswa,
                    'total_setoran' => $totalSetoran,
                    'total_baris' => $totalBarisAll,
                    'lancar_percentage' => $lancarPercentage,
                    'avg_nilai' => $overallAvgNilai,
                ];
            }
        } else {
            // Existing eskul logic
            if ($selectedClass && $selectedYearId) {
                if ($selectedClass === 'ALL') {
                    foreach ($classes as $cls) {
                        $clsStudents = Student::forYear($selectedYearId)
                            ->where('class', $cls)
                            ->where(function($q) {
                                $q->where('status', '!=', 'graduated')->orWhereNull('status');
                            })
                            ->with([
                                'eskuls' => function($query) use ($selectedYearId) {
                                    $query->wherePivot('academic_year_id', $selectedYearId);
                                }, 
                                'grades' => function($query) use ($selectedYearId) {
                                    $query->where('academic_year_id', $selectedYearId)
                                          ->whereIn('type', ['sas1', 'sas2'])
                                          ->with('eskul'); 
                                }
                            ])
                            ->orderBy('name')
                            ->get();

                        $allClassesData[$cls] = $clsStudents;
                    }
                } else {
                    $students = Student::where('class', $selectedClass)
                        ->with([
                            'eskuls' => function($query) use ($selectedYearId) {
                                $query->wherePivot('academic_year_id', $selectedYearId);
                            }, 
                            'grades' => function($query) use ($selectedYearId) {
                                $query->where('academic_year_id', $selectedYearId)
                                      ->whereIn('type', ['sas1', 'sas2'])
                                      ->with('eskul'); 
                            }
                        ])
                        ->orderBy('name')
                        ->get();
                }
            }
        }

        $selectedPeriodType = $selectedPeriodType ?? 'all';
        $selectedMonth = $selectedMonth ?? null;
        $periodTitle = $periodTitle ?? "REKAPITULASI LAPORAN EVALUASI & CAPAIAN TAHFIDZ AL-QUR'AN";
        $periodLabel = $periodLabel ?? '';
        $academicMonths = $academicMonths ?? [];

        return view('reports.index', compact(
            'activeTab',
            'classes', 
            'students', 
            'allClassesData', 
            'selectedClass', 
            'selectedPeriod', 
            'selectedYearId', 
            'selectedGroupId',
            'academicYears', 
            'activeYear',
            'tilawatiGroups',
            'tahfidzGroups',
            'quranStats',
            'selectedPeriodType',
            'selectedMonth',
            'periodTitle',
            'periodLabel',
            'academicMonths'
        ));
    }
    public function exportCalistung()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak: Hanya Admin yang dapat mengunduh laporan kelulusan Calistung.');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CalistungGraduatesExport, 'Siswa_Lulus_Calistung_' . date('Ymd_His') . '.xlsx');
    }

    /**
     * Ekspor Rekap Tahfidz Komprehensif ke Excel (Mendukung Bulanan & Semester)
     */
    public function exportTahfidz(Request $request)
    {
        $class = $request->query('class');
        $groupId = $request->query('group_id');
        $yearId = $request->query('year_id');
        $periodType = $request->query('period_type', 'all');
        $month = $request->query('month');

        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $yearId ?? ($activeYear?->id);

        $periodInfo = self::resolveTahfidzPeriod($periodType, $month, $selectedYearId);
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];
        $periodLabel = $periodInfo['period_label'];

        $groupName = '';
        if ($groupId) {
            $grp = \App\Models\MentoringGroup::find($groupId);
            if ($grp) {
                $groupName = 'Kelompok_' . str_replace([' ', '/', '\\'], '_', $grp->name) . '_';
            }
        }

        $classClean = ($class && $class !== 'ALL') ? 'Kelas_' . str_replace(' ', '_', $class) . '_' : '';
        $periodSlug = 'Semua_Periode';
        if ($periodType === 'semester_1') {
            $periodSlug = 'Semester_Ganjil';
        } elseif ($periodType === 'semester_2') {
            $periodSlug = 'Semester_Genap';
        } elseif ($periodType === 'monthly') {
            $monthNames = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
            $periodSlug = 'Bulanan_' . ($monthNames[$periodInfo['month']] ?? 'Bulan_' . $periodInfo['month']) . '_' . (($periodInfo['month'] >= 7) ? $periodInfo['start_year'] : $periodInfo['end_year']);
        }

        $filename = 'Laporan_Tahfidz_' . $periodSlug . '_' . $groupName . $classClean . str_replace(['/', '\\', ' '], '_', $periodInfo['year_name']) . '_' . date('Ymd_His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\TahfidzRecapExport($selectedYearId, $class, $groupId, $startDate, $endDate, $periodLabel),
            $filename
        );
    }


    public function exportClass(Request $request)
    {
        $class = $request->query('class');
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'teacher' && !empty($user->homeroom_class)) {
            $class = $user->homeroom_class;
        }

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearId = $request->query('year_id') ?? ($activeYear ? $activeYear->id : null);
        $period = $request->query('period') ?? 'all';

        if (!$class || !$yearId) {
            return back()->with('error', 'Kelas dan Tahun Ajaran harus dipilih.');
        }

        // Simplest filename to avoid header issues
        $filename = 'Laporan_Eskul_' . preg_replace('/[^A-Za-z0-9]/', '', $class) . '.xlsx';
        
        try {
            // Store to disk 'public'
            \Maatwebsite\Excel\Facades\Excel::store(new \App\Exports\ClassEskulExport($class, $yearId, $period), $filename, 'public');
            
            // Redirect to static file using built-in PHP server compatible URL
            // Since user uses 'php artisan serve', asset() points to localhost:8000
            
            return redirect('storage/' . $filename);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat file Excel: ' . $e->getMessage());
        }
    }

    public function printRecapClass(Request $request)
    {
        $class = $request->query('class');
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'teacher' && !empty($user->homeroom_class)) {
            $class = $user->homeroom_class;
        }

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearId = $request->query('year_id') ?? ($activeYear ? $activeYear->id : null);
        $period = $request->query('period') ?? 'all';

        if (!$class || !$yearId) {
            return back()->with('error', 'Kelas dan Tahun Ajaran harus dipilih.');
        }

        $students = \App\Models\Student::forYear($yearId)
            ->where('class', $class)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')
                  ->orWhereNull('status');
            })
            ->with(['eskuls' => function($q) use ($yearId, $period) {
                $q->wherePivot('academic_year_id', $yearId);
                if ($period != 'all') {
                    $q->wherePivot('semester', $period);
                }
            }])
            ->orderBy('name')
            ->get();
            
        $yearName = $activeYear ? $activeYear->name : '-';
        if ($yearId && $activeYear->id != $yearId) {
             $y = \App\Models\AcademicYear::find($yearId);
             if($y) $yearName = $y->name;
        }

        // Find homeroom teacher name for this class
        $homeroomTeacher = \App\Models\User::where('role', 'teacher')
            ->where('homeroom_class', $class)
            ->first();
        $homeroomTeacherName = $homeroomTeacher ? $homeroomTeacher->name : null;

        return view('reports.print_recap', compact('students', 'class', 'yearName', 'homeroomTeacherName'));
    }

    public function printFullClass(Request $request)
    {
        $class = $request->query('class');
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'teacher' && !empty($user->homeroom_class)) {
            $class = $user->homeroom_class;
        }

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearId = $request->query('year_id') ?? ($activeYear ? $activeYear->id : null);
        $period = $request->query('period') ?? 'all';

        if (!$class || !$yearId) {
            return back()->with('error', 'Kelas dan Tahun Ajaran harus dipilih.');
        }

        $students = \App\Models\Student::forYear($yearId)
            ->where('class', $class)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')
                  ->orWhereNull('status');
            })
            // Similar eager loading logic
            ->with(['eskuls' => function($q) use ($yearId, $period) {
                $q->wherePivot('academic_year_id', $yearId);
                if ($period != 'all') {
                    $q->wherePivot('semester', $period);
                }
            }])
            ->orderBy('name')
            ->get();
            
        $yearName = $activeYear ? $activeYear->name : '-';
        if ($yearId && $activeYear->id != $yearId) {
             $y = \App\Models\AcademicYear::find($yearId);
             if($y) $yearName = $y->name;
        }

        // Find homeroom teacher name for this class
        $homeroomTeacher = \App\Models\User::where('role', 'teacher')
            ->where('homeroom_class', $class)
            ->first();
        $homeroomTeacherName = $homeroomTeacher ? $homeroomTeacher->name : null;

        // Pass yearId to view for attendance calculation query
        return view('reports.print_full', compact('students', 'class', 'yearName', 'yearId', 'period', 'homeroomTeacherName'));
    }

    public function printCalistungGraduates()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak: Hanya Admin yang dapat mencetak laporan kelulusan Calistung.');
        }
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearName = $activeYear ? $activeYear->name : '-';
        if (!$activeYear) return back()->with('error', 'Tidak ada tahun ajaran aktif.');

        // Logic from Export class to find graduates
        $calistungEskuls = \App\Models\Eskul::where('name', 'like', '%Calistung%')->pluck('id');

        $graduates = [];
        if ($calistungEskuls->isNotEmpty()) {
            $grades = \App\Models\Grade::with(['student', 'eskul'])
                ->whereIn('eskul_id', $calistungEskuls)
                ->where('academic_year_id', $activeYear->id)
                ->get();

            foreach ($grades as $grade) {
                $studentId = $grade->student_id;
                $scoreStr = $grade->score;
                
                $score = json_decode($scoreStr, true);
                $parsed = [];

                if (is_array($score)) {
                    $parsed = $score;
                } else {
                     if (preg_match('/^[A]$/i', trim($scoreStr))) {
                         $parsed = ['reading' => 'A', 'writing' => 'A', 'counting' => 'A'];
                     }
                     elseif (str_contains($scoreStr, '|')) {
                         $parts = explode('|', $scoreStr);
                         foreach ($parts as $part) {
                             $sub = explode(':', $part);
                             if (count($sub) >= 2) {
                                 $k = strtoupper(trim($sub[0]));
                                 $v = trim($sub[1]);
                                 if ($k == 'B') $parsed['reading'] = $v;
                                 if ($k == 'T') $parsed['writing'] = $v;
                                 if ($k == 'H') $parsed['counting'] = $v;
                             }
                         }
                     }
                     else {
                          if (preg_match('/Membaca\s*[:=]\s*(.*?)(?=\s+Menulis|\s+Berhitung|$)/ui', $scoreStr, $m)) $parsed['reading'] = trim($m[1]);
                          if (preg_match('/Menulis\s*[:=]\s*(.*?)(?=\s+Berhitung|$)/ui', $scoreStr, $m)) $parsed['writing'] = trim($m[1]);
                          if (preg_match('/Berhitung\s*[:=]\s*(.*?)(?=$)/ui', $scoreStr, $m)) $parsed['counting'] = trim($m[1]);
                     }
                }
                
                $achievements = [];
                if (isset($parsed['reading']) && strtoupper(trim($parsed['reading'])) === 'A') $achievements[] = 'Membaca';
                if (isset($parsed['writing']) && strtoupper(trim($parsed['writing'])) === 'A') $achievements[] = 'Menulis';
                if (isset($parsed['counting']) && strtoupper(trim($parsed['counting'])) === 'A') $achievements[] = 'Berhitung';

                if (count($achievements) === 3) {
                    if (!isset($graduates[$studentId])) {
                         $graduates[$studentId] = [
                            'name' => $grade->student->name ?? 'Unknown',
                            'class' => $grade->student->class ?? '-',
                            'eskul' => $grade->eskul->name ?? 'Calistung',
                            'achievements' => [],
                         ];
                    }
                    $graduates[$studentId]['achievements'] = array_unique(array_merge($graduates[$studentId]['achievements'], $achievements));
                }
            }
        }
        
        // Sort by Name
        $graduates = collect(array_values($graduates))->sortBy('name');

        return view('reports.print_calistung', compact('graduates', 'yearName'));
    }

    public function printAll(Request $request)
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearId = $request->query('year_id') ?? ($activeYear ? $activeYear->id : null);
        $period = $request->query('period') ?? 'all';

        if (!$yearId) {
            return back()->with('error', 'Tahun Ajaran harus dipilih.');
        }

        $yearObj = \App\Models\AcademicYear::find($yearId);
        $yearName = $yearObj ? $yearObj->name : '-';

        $classes = Student::forYear($yearId)
            ->select('class')->distinct()->orderBy('class')->pluck('class');

        $reportsByClass = [];
        foreach ($classes as $cls) {
            $students = Student::forYear($yearId)
                ->where('class', $cls)
                ->where(function($q) {
                    $q->where('status', '!=', 'graduated')->orWhereNull('status');
                })
                ->with(['eskuls' => function($q) use ($yearId, $period) {
                    $q->wherePivot('academic_year_id', $yearId);
                    if ($period != 'all') {
                        $q->wherePivot('semester', $period);
                    }
                }, 'grades' => function($q) use ($yearId) {
                    $q->where('academic_year_id', $yearId)
                      ->whereIn('type', ['sas1', 'sas2'])
                      ->with('eskul');
                }, 'attendances' => function($q) use ($yearId, $period) {
                    $q->where(function($sub) use ($yearId) {
                        $sub->where('academic_year_id', $yearId)
                            ->orWhereNull('academic_year_id');
                    });
                    if ($period != 'all') {
                        $q->where(function($sub) use ($period) {
                            $sub->where('semester', $period)
                                ->orWhereNull('semester');
                        });
                    }
                }])
                ->orderBy('name')
                ->get();

            $homeroomTeacher = \App\Models\User::where('role', 'teacher')
                ->where('homeroom_class', $cls)
                ->first();

            $reportsByClass[$cls] = [
                'students' => $students,
                'homeroom_teacher' => $homeroomTeacher ? $homeroomTeacher->name : null,
            ];
        }

        return view('reports.print_all', compact('reportsByClass', 'yearName', 'yearId', 'period', 'activeYear'));
    }

    public function printAllRecap(Request $request)
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearId = $request->query('year_id') ?? ($activeYear ? $activeYear->id : null);
        $period = $request->query('period') ?? 'all';

        if (!$yearId) {
            return back()->with('error', 'Tahun Ajaran harus dipilih.');
        }

        $yearObj = \App\Models\AcademicYear::find($yearId);
        $yearName = $yearObj ? $yearObj->name : '-';

        $classes = Student::forYear($yearId)
            ->select('class')->distinct()->orderBy('class')->pluck('class');

        $recapByClass = [];
        foreach ($classes as $cls) {
            $students = Student::forYear($yearId)
                ->where('class', $cls)
                ->where(function($q) {
                    $q->where('status', '!=', 'graduated')->orWhereNull('status');
                })
                ->with(['eskuls' => function($q) use ($yearId, $period) {
                    $q->wherePivot('academic_year_id', $yearId);
                    if ($period != 'all') {
                        $q->wherePivot('semester', $period);
                    }
                }])
                ->orderBy('name')
                ->get();

            $homeroomTeacher = \App\Models\User::where('role', 'teacher')
                ->where('homeroom_class', $cls)
                ->first();

            $recapByClass[$cls] = [
                'students' => $students,
                'homeroom_teacher' => $homeroomTeacher ? $homeroomTeacher->name : null,
            ];
        }

        return view('reports.print_all_recap', compact('recapByClass', 'yearName', 'yearId', 'period', 'activeYear'));
    }

    /**
     * Cetak Laporan Resmi Pembinaan Tilawati (Format A4 Resmi & Akuntabel)
     */
    public function printTilawati(Request $request)
    {
        $class = $request->query('class');
        $groupId = $request->query('group_id');
        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $request->query('year_id') ?? ($activeYear ? $activeYear->id : null);

        $user = Auth::user();
        if ($user->role === 'teacher' && !empty($user->homeroom_class)) {
            $class = $user->homeroom_class;
        }

        $query = Student::forYear($yearId)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')->orWhereNull('status');
            });

        if ($class && $class !== 'ALL') {
            $query->where('class', $class);
        }

        if ($groupId) {
            $query->whereHas('mentoringGroups', function($q) use ($groupId) {
                $q->where('mentoring_groups.id', $groupId);
            });
        }

        $students = $query->with([
            'mentoringGroups' => function($q) use ($yearId) {
                $q->where('mentoring_groups.type', 'tilawati')
                  ->when($yearId, fn($sub) => $sub->where('mentoring_groups.academic_year_id', $yearId));
            }
        ])->orderBy('class')->orderBy('name')->get();

        $yearName = $activeYear ? $activeYear->name : '-';
        if ($yearId && $activeYear && $activeYear->id != $yearId) {
            $y = AcademicYear::find($yearId);
            if ($y) $yearName = $y->name;
        }

        $groupObj = $groupId ? MentoringGroup::find($groupId) : null;
        $homeroomTeacher = $class && $class !== 'ALL' 
            ? User::where('role', 'teacher')->where('homeroom_class', $class)->first() 
            : null;

        return view('reports.print_tilawati', compact(
            'students', 'class', 'groupObj', 'yearName', 'yearId', 'homeroomTeacher', 'activeYear'
        ));
    }

    /**
     * Cetak Laporan Resmi Pembinaan Tahfidz (Format A4 Resmi & Akuntabel)
     * Mendukung Laporan Bulanan, Semester (Ganjil/Genap), dan Tahunan
     */
    public function printTahfidz(Request $request)
    {
        $class = $request->query('class');
        $groupId = $request->query('group_id');
        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $request->query('year_id') ?? ($activeYear ? $activeYear->id : null);
        $periodType = $request->query('period_type', 'all');
        $month = $request->query('month');

        $user = Auth::user();
        if ($user->role === 'teacher' && !empty($user->homeroom_class)) {
            $class = $user->homeroom_class;
        }

        // Safe table check
        \App\Http\Controllers\TahfidzAttendanceController::ensureTableExists();

        $periodInfo = self::resolveTahfidzPeriod($periodType, $month, $yearId);
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];
        $periodTitle = $periodInfo['period_title'];
        $periodLabel = $periodInfo['period_label'];

        $query = Student::forYear($yearId)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')->orWhereNull('status');
            });

        if ($class && $class !== 'ALL') {
            $query->where('class', $class);
        }

        if ($groupId) {
            $query->whereHas('mentoringGroups', function($q) use ($groupId) {
                $q->where('mentoring_groups.id', $groupId);
            });
        }

        $students = $query->with([
            'mentoringGroups' => function($q) use ($yearId) {
                $q->where('mentoring_groups.type', 'tahfidz')
                  ->when($yearId, fn($sub) => $sub->where('mentoring_groups.academic_year_id', $yearId));
            },
            'tahfidzRecords' => function($q) use ($yearId, $startDate, $endDate) {
                $q->when($yearId, fn($sub) => $sub->where('academic_year_id', $yearId))
                  ->when($startDate && $endDate, fn($sub) => $sub->whereBetween('session_date', [$startDate, $endDate]))
                  ->orderBy('session_date', 'desc')
                  ->orderBy('id', 'desc');
            }
        ])->orderBy('class')->orderBy('name')->get();

        $allStudentIds = $students->pluck('id');
        $attendances = TahfidzAttendance::whereIn('student_id', $allStudentIds)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('session_date', [$startDate, $endDate]))
            ->get()
            ->groupBy('student_id');

        $totalAllSetoran = 0;
        $totalAllLancar = 0;
        $totalAllBaris = 0;
        $allScores = [];

        foreach ($students as $std) {
            $records = $std->tahfidzRecords;
            $std->total_records_count = $records->count();
            $std->total_baris = (int) $records->sum('jumlah_baris');
            $std->baru_count = $records->where('type', 'setoran_baru')->count();
            $std->murojaah_count = $records->where('type', 'murojaah')->count();
            $std->latest_record = $records->first();

            $lancar = $records->where('quality', 'lancar')->count();
            $cukup = $records->where('quality', 'cukup')->count();
            $perbaikan = $records->where('quality', 'perlu_perbaikan')->count();

            $std->quality_counts = [
                'lancar' => $lancar,
                'cukup' => $cukup,
                'perlu_perbaikan' => $perbaikan,
            ];

            $totalAllSetoran += $records->count();
            $totalAllLancar += $lancar;
            $totalAllBaris += $std->total_baris;

            // Rata-rata Nilai
            $numScores = $records->pluck('nilai')->filter(fn($v) => is_numeric($v))->map(fn($v) => (float)$v);
            if ($numScores->isNotEmpty()) {
                $std->avg_nilai = round($numScores->average(), 1);
                foreach ($numScores as $sc) {
                    $allScores[] = $sc;
                }
            } else {
                $std->avg_nilai = null;
            }

            // Predikat Mutu
            if ($std->avg_nilai !== null) {
                if ($std->avg_nilai >= 90) $std->predikat_label = 'Mumtaz (A)';
                elseif ($std->avg_nilai >= 80) $std->predikat_label = 'Jayyid Jiddan (B)';
                elseif ($std->avg_nilai >= 70) $std->predikat_label = 'Jayyid (C)';
                else $std->predikat_label = 'Maqbul (D)';
            } elseif ($std->total_records_count > 0) {
                $lancarRate = round(($lancar / $std->total_records_count) * 100);
                if ($lancarRate >= 80) $std->predikat_label = 'Mutqin / Lancar';
                elseif ($lancarRate >= 50) $std->predikat_label = 'Cukup Lancar';
                else $std->predikat_label = 'Perlu Bimbingan';
            } else {
                $std->predikat_label = '-';
            }

            $stdAttendances = $attendances->get($std->id, collect());
            $std->attendance_summary = [
                'hadir' => $stdAttendances->where('status', 'hadir')->count(),
                'sakit' => $stdAttendances->where('status', 'sakit')->count(),
                'izin'  => $stdAttendances->where('status', 'izin')->count(),
                'alpha' => $stdAttendances->where('status', 'alpha')->count(),
            ];
        }

        $mutqinPercentage = $totalAllSetoran > 0 ? round(($totalAllLancar / $totalAllSetoran) * 100, 1) : 0;
        $overallAvgNilai = count($allScores) > 0 ? round(array_sum($allScores) / count($allScores), 1) : '-';

        $yearName = $periodInfo['year_name'];
        $groupObj = $groupId ? MentoringGroup::find($groupId) : null;
        $homeroomTeacher = $class && $class !== 'ALL' 
            ? User::where('role', 'teacher')->where('homeroom_class', $class)->first() 
            : null;

        return view('reports.print_tahfidz', compact(
            'students', 'class', 'groupObj', 'yearName', 'yearId', 'homeroomTeacher', 'activeYear', 
            'totalAllSetoran', 'mutqinPercentage', 'totalAllBaris', 'overallAvgNilai',
            'periodTitle', 'periodLabel', 'periodType', 'periodInfo'
        ));
    }

    /**
     * Cetak Raport Tahfidz Standar Nasional (Format Resmi A4 Portrait)
     * Dapat mencetak per-siswa maupun massal (se-kelas / se-kelompok)
     * Mendukung filter Semester 1 (Ganjil) dan Semester 2 (Genap)
     */
    public function raportTahfidz(Request $request)
    {
        $studentId = $request->query('student_id');
        $class = $request->query('class');
        $groupId = $request->query('group_id');
        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $request->query('year_id') ?? ($activeYear ? $activeYear->id : null);

        $periodType = $request->query('period_type', $request->query('period', 'all'));
        $semester = $request->query('semester');
        if ($semester === '1' || $semester === 'ganjil') $periodType = 'semester_1';
        if ($semester === '2' || $semester === 'genap') $periodType = 'semester_2';

        $periodInfo = self::resolveTahfidzPeriod($periodType, null, $yearId);
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];
        $semesterLabel = $periodInfo['semester_name'] ?? ($activeYear && $activeYear->active_semester == '2' ? 'Semester Genap (II)' : 'Semester Ganjil (I)');

        $user = Auth::user();
        if ($user->role === 'teacher' && !empty($user->homeroom_class) && !$studentId) {
            $class = $user->homeroom_class;
        }

        $query = Student::forYear($yearId)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')->orWhereNull('status');
            });

        if ($studentId) {
            $query->where('id', $studentId);
        } else {
            if ($class && $class !== 'ALL') {
                $query->where('class', $class);
            }
            if ($groupId) {
                $query->whereHas('mentoringGroups', function($q) use ($groupId) {
                    $q->where('mentoring_groups.id', $groupId);
                });
            }
        }

        $students = $query->with([
            'mentoringGroups' => function($q) use ($yearId) {
                $q->where('mentoring_groups.type', 'tahfidz')
                  ->when($yearId, fn($sub) => $sub->where('mentoring_groups.academic_year_id', $yearId));
            },
            'tahfidzRecords' => function($q) use ($yearId, $startDate, $endDate) {
                $q->when($yearId, fn($sub) => $sub->where('academic_year_id', $yearId))
                  ->when($startDate && $endDate, fn($sub) => $sub->whereBetween('session_date', [$startDate, $endDate]))
                  ->orderBy('session_date', 'asc')
                  ->orderBy('id', 'asc');
            }
        ])->orderBy('class')->orderBy('name')->get();

        $allStudentIds = $students->pluck('id');
        $attendances = TahfidzAttendance::whereIn('student_id', $allStudentIds)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('session_date', [$startDate, $endDate]))
            ->get()
            ->groupBy('student_id');

        $yearName = $periodInfo['year_name'];
        $groupObj = $groupId ? MentoringGroup::find($groupId) : null;
        $homeroomTeacher = $class && $class !== 'ALL' 
            ? User::where('role', 'teacher')->where('homeroom_class', $class)->first() 
            : null;

        $headmasterName = \App\Models\Setting::where('key','headmaster_name')->value('value')
            ?? optional(\App\Models\User::where('role','headmaster')->first())->name
            ?? "Nur'asiah, S.Pd.I";

        // Precompute standard national assessment rubric for each student
        foreach ($students as $std) {
            $records = $std->tahfidzRecords;
            $std->total_records_count = $records->count();
            $std->total_baris = (int) $records->sum('jumlah_baris');
            $std->baru_count = $records->where('type', 'setoran_baru')->count();
            $std->murojaah_count = $records->where('type', 'murojaah')->count();
            $std->latest_record = $records->sortByDesc('session_date')->first();

            $lancar = $records->where('quality', 'lancar')->count();
            $cukup = $records->where('quality', 'cukup')->count();
            $perbaikan = $records->where('quality', 'perlu_perbaikan')->count();

            $std->lancar_count = $lancar;
            $std->cukup_count = $cukup;
            $std->perbaikan_count = $perbaikan;

            $lancarRate = $std->total_records_count > 0 ? round(($lancar / $std->total_records_count) * 100) : 0;
            $std->lancar_rate = $lancarRate;

            // Presensi
            $stdAtt = $attendances->get($std->id, collect());
            $hadir = $stdAtt->where('status', 'hadir')->count();
            $sakit = $stdAtt->where('status', 'sakit')->count();
            $izin  = $stdAtt->where('status', 'izin')->count();
            $alpha = $stdAtt->where('status', 'alpha')->count();
            $totalAtt = $stdAtt->count();
            $attRate = $totalAtt > 0 ? round(($hadir / $totalAtt) * 100) : 100;

            $std->att_summary = [
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin'  => $izin,
                'alpha' => $alpha,
                'total' => $totalAtt,
                'rate'  => $attRate,
            ];

            // Kategori Predikat Standar Nasional (JSIT / Depag)
            // Nilai Hifdz (Kelancaran)
            if ($std->total_records_count === 0) {
                $scoreHifdz = 75;
            } elseif ($lancarRate >= 85) {
                $scoreHifdz = min(98, 90 + (int)round(($lancarRate - 85) * 0.5));
            } elseif ($lancarRate >= 65) {
                $scoreHifdz = 80 + (int)round(($lancarRate - 65) * 0.45);
            } else {
                $scoreHifdz = 70 + (int)round($lancarRate * 0.15);
            }

            // Nilai Tajwid (selaras dengan hifdz)
            $scoreTajwid = min(96, max(72, $scoreHifdz - ($lancarRate >= 80 ? 0 : 2)));

            // Nilai Fashahah & Makhraj
            $scoreMakhraj = min(95, max(74, $scoreHifdz - ($lancarRate >= 80 ? 1 : 2)));

            // Nilai Adab & Disiplin
            $scoreAdab = min(98, max(75, (int)round(($attRate * 0.4) + ($scoreHifdz * 0.6))));

            // Rata-rata
            $scoreRata = (int)round(($scoreHifdz + $scoreTajwid + $scoreMakhraj + $scoreAdab) / 4);

            $predikatFn = function($val) {
                if ($val >= 90) return ['grade' => 'A', 'label' => 'Mumtaz (Istimewa)'];
                if ($val >= 80) return ['grade' => 'B', 'label' => 'Jayyid Jiddan (Amat Baik)'];
                if ($val >= 70) return ['grade' => 'C', 'label' => 'Jayyid (Baik)'];
                return ['grade' => 'D', 'label' => 'Maqbul (Perlu Bimbingan)'];
            };

            $std->assessment = [
                'hifdz' => [
                    'title' => 'Kelancaran Hafalan (Tahfidz & Hifdz)',
                    'score' => $scoreHifdz,
                    'pred'  => $predikatFn($scoreHifdz),
                    'desc'  => 'Mampu melafalkan ayat hafalan secara runtut, lancar tanpa jeda/lupa, serta daya ingat hafalan mutqin.'
                ],
                'tajwid' => [
                    'title' => 'Ketepatan Kaidah Tajwid (Ahkamut Tajwid)',
                    'score' => $scoreTajwid,
                    'pred'  => $predikatFn($scoreTajwid),
                    'desc'  => 'Penerapan hukum nun mati/tanwin, mim mati, mad thabi\'i/far\'i, ghunnah, serta waqaf dan ibtida\' tepat.'
                ],
                'makhraj' => [
                    'title' => 'Fashahah & Makharijul Huruf',
                    'score' => $scoreMakhraj,
                    'pred'  => $predikatFn($scoreMakhraj),
                    'desc'  => 'Ketepatan melafalkan makhraj huruf hijaiyah dan sifat-sifat huruf secara jelas, fasih, dan tartil.'
                ],
                'adab' => [
                    'title' => 'Adab, Kedisiplinan & Karakter Qur\'ani',
                    'score' => $scoreAdab,
                    'pred'  => $predikatFn($scoreAdab),
                    'desc'  => 'Menunjukkan adab mulia terhadap mushaf Al-Qur\'an, tertib menyimak tilawah, dan antusias muroja\'ah.'
                ],
                'rata' => [
                    'title' => 'NILAI AKHIR TAHFIDZ AL-QUR\'AN',
                    'score' => $scoreRata,
                    'pred'  => $predikatFn($scoreRata),
                    'status'=> $scoreRata >= 80 ? 'LULUS DENGAN PREDIKAT BAIK' : 'LULUS DENGAN CATATAN PENINGKATAN'
                ]
            ];

            // Narasi Motivasi Pembimbing
            $surahName = $std->latest_record ? $std->latest_record->surah_name : ($std->mentoringGroups->first()?->target_level ?? 'Juz 30');
            if ($scoreRata >= 90) {
                $std->catatan_guru = "Barakallahu fiik ananda {$std->name}. Prestasi hafalan ananda sangat membanggakan dengan daya ingat hafalan yang mutqin dan bacaan yang tartil (terakhir: {$surahName}). Pertahankan hafalan dengan muroja'ah istiqomah setiap selesai shalat fardhu bersama orang tua.";
            } elseif ($scoreRata >= 80) {
                $std->catatan_guru = "Alhamdulillah ananda {$std->name} telah menyelesaikan capaian hafalan dengan baik hingga {$surahName}. Tingkatkan kembali pengulangan (muroja'ah) harian di rumah agar hafalan semakin lancar, kokoh, dan siap mengikuti munaqosyah.";
            } else {
                $std->catatan_guru = "Alhamdulillah ananda {$std->name} terus berproses mempelajari hafalan Al-Qur'an. Mohon bimbingan dan dampingan intensif dari Ayah Bunda di rumah untuk membiasakan tilawah dan muroja'ah bersama setiap hari.";
            }
        }

        return view('reports.raport_tahfidz', compact(
            'students', 'class', 'groupObj', 'yearName', 'yearId', 'homeroomTeacher', 'activeYear', 'headmasterName', 'studentId', 'semesterLabel'
        ));
    }

    /**
     * Helper resolusi periode Tahfidz (Semua, Semester 1, Semester 2, atau Bulanan)
     */
    public static function resolveTahfidzPeriod(?string $periodType, ?string $monthInput, ?int $yearId): array
    {
        $periodType = in_array($periodType, ['all', 'semester_1', 'semester_2', 'monthly']) ? $periodType : 'all';

        $yearObj = $yearId ? AcademicYear::find($yearId) : AcademicYear::where('is_active', true)->first();
        if (!$yearObj) {
            $yearObj = AcademicYear::orderBy('name', 'desc')->first();
        }

        $yearName = $yearObj?->name ?? (date('Y') . '/' . (date('Y') + 1));
        
        // Parse start and end year from name like "2025/2026"
        $parts = explode('/', $yearName);
        $startYear = isset($parts[0]) && is_numeric(trim($parts[0])) ? (int)trim($parts[0]) : (int)date('Y');
        $endYear = isset($parts[1]) && is_numeric(trim($parts[1])) ? (int)trim($parts[1]) : ($startYear + 1);

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $academicMonths = [
            ['month' => 7, 'year' => $startYear, 'label' => 'Juli ' . $startYear . ' (Semester Ganjil)'],
            ['month' => 8, 'year' => $startYear, 'label' => 'Agustus ' . $startYear . ' (Semester Ganjil)'],
            ['month' => 9, 'year' => $startYear, 'label' => 'September ' . $startYear . ' (Semester Ganjil)'],
            ['month' => 10, 'year' => $startYear, 'label' => 'Oktober ' . $startYear . ' (Semester Ganjil)'],
            ['month' => 11, 'year' => $startYear, 'label' => 'November ' . $startYear . ' (Semester Ganjil)'],
            ['month' => 12, 'year' => $startYear, 'label' => 'Desember ' . $startYear . ' (Semester Ganjil)'],
            ['month' => 1, 'year' => $endYear, 'label' => 'Januari ' . $endYear . ' (Semester Genap)'],
            ['month' => 2, 'year' => $endYear, 'label' => 'Februari ' . $endYear . ' (Semester Genap)'],
            ['month' => 3, 'year' => $endYear, 'label' => 'Maret ' . $endYear . ' (Semester Genap)'],
            ['month' => 4, 'year' => $endYear, 'label' => 'April ' . $endYear . ' (Semester Genap)'],
            ['month' => 5, 'year' => $endYear, 'label' => 'Mei ' . $endYear . ' (Semester Genap)'],
            ['month' => 6, 'year' => $endYear, 'label' => 'Juni ' . $endYear . ' (Semester Genap)'],
        ];

        $startDate = null;
        $endDate = null;
        $periodTitle = "REKAPITULASI LAPORAN EVALUASI & CAPAIAN TAHFIDZ AL-QUR'AN";
        $periodLabel = "Tahun Pelajaran " . $yearName;
        $semesterName = null;
        $selectedMonth = null;

        if ($periodType === 'semester_1') {
            $startDate = sprintf('%04d-07-01', $startYear);
            $endDate = sprintf('%04d-12-31', $startYear);
            $periodTitle = "REKAPITULASI LAPORAN SEMESTER PEMBINAAN TAHFIDZ AL-QUR'AN";
            $periodLabel = "Semester Ganjil (I) · Tahun Pelajaran " . $yearName;
            $semesterName = "Semester Ganjil (I)";
        } elseif ($periodType === 'semester_2') {
            $startDate = sprintf('%04d-01-01', $endYear);
            $endDate = sprintf('%04d-06-30', $endYear);
            $periodTitle = "REKAPITULASI LAPORAN SEMESTER PEMBINAAN TAHFIDZ AL-QUR'AN";
            $periodLabel = "Semester Genap (II) · Tahun Pelajaran " . $yearName;
            $semesterName = "Semester Genap (II)";
        } elseif ($periodType === 'monthly') {
            $m = is_numeric($monthInput) ? (int)$monthInput : (int)date('n');
            if ($m < 1 || $m > 12) $m = 7;
            $selectedMonth = $m;
            $mYear = ($m >= 7) ? $startYear : $endYear;
            $daysInMonth = (int)date('t', strtotime(sprintf('%04d-%02d-01', $mYear, $m)));
            $startDate = sprintf('%04d-%02d-01', $mYear, $m);
            $endDate = sprintf('%04d-%02d-%02d', $mYear, $m, $daysInMonth);
            $mName = $monthNames[$m] ?? "Bulan $m";
            $semLabel = ($m >= 7) ? "Semester Ganjil" : "Semester Genap";
            $periodTitle = "REKAPITULASI LAPORAN BULANAN PEMBINAAN TAHFIDZ AL-QUR'AN";
            $periodLabel = "Bulan {$mName} {$mYear} ({$semLabel} · TP {$yearName})";
            $semesterName = $semLabel;
        }

        return [
            'period_type'     => $periodType,
            'month'           => $selectedMonth,
            'start_date'      => $startDate,
            'end_date'        => $endDate,
            'start_year'      => $startYear,
            'end_year'        => $endYear,
            'year_name'       => $yearName,
            'period_title'    => $periodTitle,
            'period_label'    => $periodLabel,
            'academic_months' => $academicMonths,
            'semester_name'   => $semesterName,
        ];
    }

}
