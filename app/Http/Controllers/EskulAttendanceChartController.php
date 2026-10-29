<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eskul;
use App\Models\Attendance;
use App\Models\TeacherAttendance;
use App\Models\AcademicYear;
use App\Models\User;
use Carbon\Carbon;

class EskulAttendanceChartController extends Controller
{
    /**
     * Halaman Utama Grafik & Rekapitulasi Presensi Eskul
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'headmaster', 'teacher', 'curriculum'])) {
            abort(403, 'Akses terbatas untuk Administrator, Kepala Sekolah, dan Pembina Ekstrakurikuler.');
        }

        $isPrint = $request->routeIs('eskul.attendance-charts.print') || $request->has('print');

        // 1. Academic Year Context
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = $academicYears->where('is_active', true)->first();
        $selectedYearId = $request->input('academic_year_id', $activeYear ? $activeYear->id : ($academicYears->first()->id ?? null));
        $contextYear = $academicYears->where('id', $selectedYearId)->first() ?: $activeYear;

        // 2. Daftar Ekstrakurikuler
        $eskulsQuery = Eskul::query();
        if ($selectedYearId) {
            $eskulsQuery->where('academic_year_id', $selectedYearId);
        }
        $eskuls = $eskulsQuery->orderBy('name')->get();
        if ($eskuls->isEmpty()) {
            $eskuls = Eskul::orderBy('name')->get();
        }

        // 3. User & Eskul Selection
        $isTeacherOnly = ($user->role === 'teacher' && !in_array($user->role, ['admin', 'headmaster']));
        $defaultEskulId = ($isTeacherOnly && $user->eskul_id) ? $user->eskul_id : 'all';
        $selectedEskulId = $request->input('eskul_id', $defaultEskulId);
        $selectedEskul = ($selectedEskulId !== 'all') ? $eskuls->where('id', $selectedEskulId)->first() : null;

        // 4. Mode Waktu: 'date' (Rentang Tanggal), 'month' (Bulanan), 'semester' (Semester), 'year' (Tahunan)
        $periodMode = $request->input('mode', 'month');
        if (!in_array($periodMode, ['date', 'month', 'semester', 'year'])) {
            $periodMode = 'month';
        }

        // Default Filter Values
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $selectedSemester = $request->input('semester', $contextYear ? ($contextYear->active_semester ?: '1') : '1');

        // Parse Start & End Year from Academic Year Name (e.g. "2026/2027")
        $startYearNum = (int) date('Y');
        $endYearNum = $startYearNum + 1;
        if ($contextYear && preg_match('/(\d{4})\/(\d{4})/', $contextYear->name, $matches)) {
            $startYearNum = (int) $matches[1];
            $endYearNum = (int) $matches[2];
        }

        // 5. Build Base Filter Queries
        $studentQuery = Attendance::query();
        $coachQuery = TeacherAttendance::query()->with(['user.eskul', 'substituteUser']);

        if ($periodMode !== 'year' && $selectedYearId) {
            $studentQuery->where('academic_year_id', $selectedYearId);
            $coachQuery->where('academic_year_id', $selectedYearId);
        }

        if ($selectedEskulId !== 'all') {
            $studentQuery->where('eskul_id', $selectedEskulId);
            $coachQuery->where(function($q) use ($selectedEskulId) {
                $q->whereHas('user', function($uq) use ($selectedEskulId) {
                    $uq->where('eskul_id', $selectedEskulId);
                })->orWhereHas('substituteUser', function($sq) use ($selectedEskulId) {
                    $sq->where('eskul_id', $selectedEskulId);
                });
            });
        } else {
            // Coach query for all eskuls: restrict to teachers with eskul
            $coachQuery->whereHas('user', function($uq) {
                $uq->where('role', 'teacher')->whereNotNull('eskul_id');
            });
        }

        // Apply Time Constraints to KPI Aggregates
        $kpiStudentQuery = clone $studentQuery;
        $kpiCoachQuery = clone $coachQuery;

        if ($periodMode === 'date') {
            $kpiStudentQuery->whereBetween('date', [$startDate, $endDate]);
            $kpiCoachQuery->whereBetween('date', [$startDate, $endDate]);
        } elseif ($periodMode === 'month') {
            if ($request->filled('month')) {
                $mParts = explode('-', $selectedMonth);
                if (count($mParts) === 2) {
                    $kpiStudentQuery->whereYear('date', $mParts[0])->whereMonth('date', $mParts[1]);
                    $kpiCoachQuery->whereYear('date', $mParts[0])->whereMonth('date', $mParts[1]);
                }
            }
        } elseif ($periodMode === 'semester') {
            if ($selectedSemester === '1') {
                $kpiStudentQuery->where(function($q) {
                    $q->where('semester', '1')->orWhere(function($sub) {
                        $sub->whereMonth('date', '>=', 7)->whereMonth('date', '<=', 12);
                    });
                });
                $kpiCoachQuery->whereMonth('date', '>=', 7)->whereMonth('date', '<=', 12);
            } elseif ($selectedSemester === '2') {
                $kpiStudentQuery->where(function($q) {
                    $q->where('semester', '2')->orWhere(function($sub) {
                        $sub->whereMonth('date', '>=', 1)->whereMonth('date', '<=', 6);
                    });
                });
                $kpiCoachQuery->whereMonth('date', '>=', 1)->whereMonth('date', '<=', 6);
            }
        }

        // 6. Calculate KPI Summary Numbers
        // Student Stats
        $studentRecords = $kpiStudentQuery->get();
        $studentTotal = $studentRecords->count();
        $studentPresent = $studentRecords->where('status', 'present')->count();
        $studentSick = $studentRecords->where('status', 'sick')->count();
        $studentPermission = $studentRecords->where('status', 'permission')->count();
        $studentAbsent = $studentRecords->where('status', 'absent')->count();
        $studentRate = ($studentTotal > 0) ? round(($studentPresent / $studentTotal) * 100, 1) : 0;

        // Coach Stats
        $coachRecords = $kpiCoachQuery->get();
        $coachTotal = $coachRecords->count();
        $coachPresentOnly = $coachRecords->where('status', 'present')->count();
        $coachSubstituted = $coachRecords->filter(function($item) {
            return !empty($item->substitute_user_id) || !empty($item->substitute_name);
        })->count();
        $coachSick = $coachRecords->where('status', 'sick')->count();
        $coachPermission = $coachRecords->where('status', 'permission')->count();
        $coachAbsent = $coachRecords->where('status', 'absent')->count();
        // Presensi dihitung hadir mandiri + kehadiran pengganti yang didelegasikan
        $coachEffectivePresent = $coachPresentOnly + $coachSubstituted;
        $coachRate = ($coachTotal > 0) ? round(($coachEffectivePresent / $coachTotal) * 100, 1) : 0;

        // Total Sesi / Pertemuan Unik
        $sessionDates = $studentRecords->pluck('date')->concat($coachRecords->pluck('date'))->filter()->unique();
        $totalSessions = $sessionDates->count();

        // 7. Generate Datasets for Charts Based on Period Mode
        $mainChartLabels = [];
        $mainChartCoachRate = [];
        $mainChartStudentRate = [];
        $mainChartStudentPresentCount = [];

        if ($periodMode === 'date') {
            // Group by distinct dates in range
            $allDates = $sessionDates->sort()->values();
            if ($allDates->isEmpty()) {
                // If empty, generate past 7 days as placeholder
                $cursor = Carbon::parse($startDate);
                $endCursor = Carbon::parse($endDate);
                while ($cursor->lte($endCursor)) {
                    $allDates->push($cursor->toDateString());
                    $cursor->addDays(7); // weekly jump
                }
            }

            foreach ($allDates as $dt) {
                $cDate = Carbon::parse($dt);
                $mainChartLabels[] = $cDate->translatedFormat('d M');

                // Coach on this date
                $cDayRecords = $coachRecords->where('date', $dt);
                $cDayTotal = $cDayRecords->count();
                $cDayPresent = $cDayRecords->filter(function($i) {
                    return $i->status === 'present' || !empty($i->substitute_user_id) || !empty($i->substitute_name);
                })->count();
                $mainChartCoachRate[] = ($cDayTotal > 0) ? round(($cDayPresent / $cDayTotal) * 100, 1) : 0;

                // Student on this date
                $sDayRecords = $studentRecords->where('date', $dt);
                $sDayTotal = $sDayRecords->count();
                $sDayPresent = $sDayRecords->where('status', 'present')->count();
                $mainChartStudentRate[] = ($sDayTotal > 0) ? round(($sDayPresent / $sDayTotal) * 100, 1) : 0;
                $mainChartStudentPresentCount[] = $sDayPresent;
            }
        } elseif ($periodMode === 'month') {
            // 12 Months of Academic Year (Jul - Jun)
            $academicMonths = [
                ['num' => 7,  'year' => $startYearNum, 'name' => 'Juli'],
                ['num' => 8,  'year' => $startYearNum, 'name' => 'Agustus'],
                ['num' => 9,  'year' => $startYearNum, 'name' => 'September'],
                ['num' => 10, 'year' => $startYearNum, 'name' => 'Oktober'],
                ['num' => 11, 'year' => $startYearNum, 'name' => 'November'],
                ['num' => 12, 'year' => $startYearNum, 'name' => 'Desember'],
                ['num' => 1,  'year' => $endYearNum,   'name' => 'Januari'],
                ['num' => 2,  'year' => $endYearNum,   'name' => 'Februari'],
                ['num' => 3,  'year' => $endYearNum,   'name' => 'Maret'],
                ['num' => 4,  'year' => $endYearNum,   'name' => 'April'],
                ['num' => 5,  'year' => $endYearNum,   'name' => 'Mei'],
                ['num' => 6,  'year' => $endYearNum,   'name' => 'Juni'],
            ];

            // Use unfiltered base queries to populate full academic year monthly trend
            $fullYearStudentRecords = (clone $studentQuery)->get();
            $fullYearCoachRecords = (clone $coachQuery)->get();

            foreach ($academicMonths as $m) {
                $mainChartLabels[] = $m['name'];

                // Filter coach for this month & year
                $mCoach = $fullYearCoachRecords->filter(function($item) use ($m) {
                    $d = Carbon::parse($item->date);
                    return $d->month === $m['num'] && $d->year === $m['year'];
                });
                $mCoachTotal = $mCoach->count();
                $mCoachPresent = $mCoach->filter(function($i) {
                    return $i->status === 'present' || !empty($i->substitute_user_id) || !empty($i->substitute_name);
                })->count();
                $mainChartCoachRate[] = ($mCoachTotal > 0) ? round(($mCoachPresent / $mCoachTotal) * 100, 1) : null;

                // Filter student for this month & year
                $mStudent = $fullYearStudentRecords->filter(function($item) use ($m) {
                    $d = Carbon::parse($item->date);
                    return $d->month === $m['num'] && $d->year === $m['year'];
                });
                $mStudentTotal = $mStudent->count();
                $mStudentPresent = $mStudent->where('status', 'present')->count();
                $mainChartStudentRate[] = ($mStudentTotal > 0) ? round(($mStudentPresent / $mStudentTotal) * 100, 1) : null;
                $mainChartStudentPresentCount[] = $mStudentPresent;
            }
        } elseif ($periodMode === 'semester') {
            // Semester 1 vs Semester 2
            $semesters = [
                ['key' => '1', 'label' => 'Semester 1 (Ganjil)'],
                ['key' => '2', 'label' => 'Semester 2 (Genap)'],
            ];

            $fullYearStudentRecords = (clone $studentQuery)->get();
            $fullYearCoachRecords = (clone $coachQuery)->get();

            foreach ($semesters as $sem) {
                $mainChartLabels[] = $sem['label'];

                // Coach in semester
                $sCoach = $fullYearCoachRecords->filter(function($item) use ($sem) {
                    $d = Carbon::parse($item->date);
                    return ($sem['key'] === '1') ? ($d->month >= 7 && $d->month <= 12) : ($d->month >= 1 && $d->month <= 6);
                });
                $sCoachTotal = $sCoach->count();
                $sCoachPresent = $sCoach->filter(function($i) {
                    return $i->status === 'present' || !empty($i->substitute_user_id) || !empty($i->substitute_name);
                })->count();
                $mainChartCoachRate[] = ($sCoachTotal > 0) ? round(($sCoachPresent / $sCoachTotal) * 100, 1) : 0;

                // Student in semester
                $sStudent = $fullYearStudentRecords->filter(function($item) use ($sem) {
                    if (!empty($item->semester)) {
                        return (string)$item->semester === (string)$sem['key'];
                    }
                    $d = Carbon::parse($item->date);
                    return ($sem['key'] === '1') ? ($d->month >= 7 && $d->month <= 12) : ($d->month >= 1 && $d->month <= 6);
                });
                $sStudentTotal = $sStudent->count();
                $sStudentPresent = $sStudent->where('status', 'present')->count();
                $mainChartStudentRate[] = ($sStudentTotal > 0) ? round(($sStudentPresent / $sStudentTotal) * 100, 1) : 0;
                $mainChartStudentPresentCount[] = $sStudentPresent;
            }
        } elseif ($periodMode === 'year') {
            // Multi-Year Comparison
            $targetYears = $academicYears->take(4)->reverse()->values();
            foreach ($targetYears as $yr) {
                $mainChartLabels[] = $yr->name;

                // Coach for year
                $yCoachQuery = TeacherAttendance::where('academic_year_id', $yr->id);
                if ($selectedEskulId !== 'all') {
                    $yCoachQuery->where(function($q) use ($selectedEskulId) {
                        $q->whereHas('user', function($uq) use ($selectedEskulId) {
                            $uq->where('eskul_id', $selectedEskulId);
                        })->orWhereHas('substituteUser', function($sq) use ($selectedEskulId) {
                            $sq->where('eskul_id', $selectedEskulId);
                        });
                    });
                }
                $yCoachRecords = $yCoachQuery->get();
                $yCoachTotal = $yCoachRecords->count();
                $yCoachPresent = $yCoachRecords->filter(function($i) {
                    return $i->status === 'present' || !empty($i->substitute_user_id) || !empty($i->substitute_name);
                })->count();
                $mainChartCoachRate[] = ($yCoachTotal > 0) ? round(($yCoachPresent / $yCoachTotal) * 100, 1) : 0;

                // Student for year
                $yStudentQuery = Attendance::where('academic_year_id', $yr->id);
                if ($selectedEskulId !== 'all') {
                    $yStudentQuery->where('eskul_id', $selectedEskulId);
                }
                $yStudentRecords = $yStudentQuery->get();
                $yStudentTotal = $yStudentRecords->count();
                $yStudentPresent = $yStudentRecords->where('status', 'present')->count();
                $mainChartStudentRate[] = ($yStudentTotal > 0) ? round(($yStudentPresent / $yStudentTotal) * 100, 1) : 0;
                $mainChartStudentPresentCount[] = $yStudentPresent;
            }
        }

        // 8. Breakdown and Horizontal Comparison per Eskul
        $eskulBreakdown = collect();
        $eskulComparisonLabels = [];
        $eskulComparisonStudentRate = [];
        $eskulComparisonCoachRate = [];

        foreach ($eskuls as $eskulItem) {
            // Student stats for this eskul within the active time filter
            $sItemQuery = clone $kpiStudentQuery;
            $sItemRecords = (clone $sItemQuery)->where('eskul_id', $eskulItem->id)->get();
            $sTotal = $sItemRecords->count();
            $sHadir = $sItemRecords->where('status', 'present')->count();
            $sSakit = $sItemRecords->where('status', 'sick')->count();
            $sIzin  = $sItemRecords->where('status', 'permission')->count();
            $sAlpa  = $sItemRecords->where('status', 'absent')->count();
            $sRate  = ($sTotal > 0) ? round(($sHadir / $sTotal) * 100, 1) : 0;

            // Coach stats for this eskul
            $cItemQuery = clone $kpiCoachQuery;
            $cItemRecords = (clone $cItemQuery)->where(function($q) use ($eskulItem) {
                $q->whereHas('user', function($uq) use ($eskulItem) {
                    $uq->where('eskul_id', $eskulItem->id);
                })->orWhereHas('substituteUser', function($sq) use ($eskulItem) {
                    $sq->where('eskul_id', $eskulItem->id);
                });
            })->get();

            $cTotal = $cItemRecords->count();
            $cHadir = $cItemRecords->where('status', 'present')->count();
            $cSubst = $cItemRecords->filter(function($i) {
                return !empty($i->substitute_user_id) || !empty($i->substitute_name);
            })->count();
            $cSakit = $cItemRecords->where('status', 'sick')->count();
            $cIzin  = $cItemRecords->where('status', 'permission')->count();
            $cRate  = ($cTotal > 0) ? round((($cHadir + $cSubst) / $cTotal) * 100, 1) : 0;

            $sessionsCount = $sItemRecords->pluck('date')->concat($cItemRecords->pluck('date'))->filter()->unique()->count();

            // Resolve Coach Name
            $assignedCoach = User::where('eskul_id', $eskulItem->id)->first();
            $coachDisplayName = $eskulItem->instructor_name ?: ($assignedCoach ? $assignedCoach->name : 'Belum Ditentukan');

            // Predicate
            $compositeScore = ($sTotal > 0 && $cTotal > 0) ? round(($sRate * 0.6) + ($cRate * 0.4), 1) : ($sRate ?: $cRate);
            $predikat = 'Kurang Aktif';
            $badgeColor = '#ef4444'; // Red
            if ($compositeScore >= 85) {
                $predikat = 'Sangat Aktif';
                $badgeColor = '#10b981'; // Green
            } elseif ($compositeScore >= 70) {
                $predikat = 'Aktif / Baik';
                $badgeColor = '#0284c7'; // Blue
            } elseif ($compositeScore >= 50) {
                $predikat = 'Cukup';
                $badgeColor = '#f59e0b'; // Amber
            }

            $eskulBreakdown->push((object)[
                'id' => $eskulItem->id,
                'name' => $eskulItem->name,
                'schedule' => $eskulItem->schedule ?: 'Sesuai Jadwal',
                'coach_name' => $coachDisplayName,
                'sessions_count' => $sessionsCount,
                'student_total' => $sTotal,
                'student_present' => $sHadir,
                'student_sick' => $sSakit,
                'student_permission' => $sIzin,
                'student_absent' => $sAlpa,
                'student_rate' => $sRate,
                'coach_total' => $cTotal,
                'coach_present' => $cHadir,
                'coach_substituted' => $cSubst,
                'coach_sick' => $cSakit,
                'coach_permission' => $cIzin,
                'coach_rate' => $cRate,
                'composite_score' => $compositeScore,
                'predikat' => $predikat,
                'badge_color' => $badgeColor,
            ]);

            $eskulComparisonLabels[] = $eskulItem->name;
            $eskulComparisonStudentRate[] = $sRate;
            $eskulComparisonCoachRate[] = $cRate;
        }

        // Sort breakdown: highest composite score first
        $eskulBreakdown = $eskulBreakdown->sortByDesc('composite_score')->values();

        // Highest & Lowest Eskul
        $bestEskul = $eskulBreakdown->first();
        $needAttentionEskul = $eskulBreakdown->where('sessions_count', '>', 0)->sortBy('composite_score')->first();

        // 9. Data Structure for View
        return view('eskuls.attendance_charts', compact(
            'academicYears',
            'activeYear',
            'selectedYearId',
            'contextYear',
            'eskuls',
            'selectedEskulId',
            'selectedEskul',
            'periodMode',
            'startDate',
            'endDate',
            'selectedMonth',
            'selectedSemester',
            'studentTotal',
            'studentPresent',
            'studentSick',
            'studentPermission',
            'studentAbsent',
            'studentRate',
            'coachTotal',
            'coachPresentOnly',
            'coachSubstituted',
            'coachSick',
            'coachPermission',
            'coachAbsent',
            'coachEffectivePresent',
            'coachRate',
            'totalSessions',
            'bestEskul',
            'needAttentionEskul',
            'mainChartLabels',
            'mainChartCoachRate',
            'mainChartStudentRate',
            'mainChartStudentPresentCount',
            'eskulBreakdown',
            'eskulComparisonLabels',
            'eskulComparisonStudentRate',
            'eskulComparisonCoachRate',
            'isPrint'
        ));
    }

    /**
     * Halaman Cetak Laporan Rekapitulasi & Grafik Presensi
     */
    public function print(Request $request)
    {
        return $this->index($request);
    }
}
