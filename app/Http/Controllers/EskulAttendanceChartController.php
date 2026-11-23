<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eskul;
use App\Models\Attendance;
use App\Models\TeacherAttendance;
use App\Models\Grade;
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
        $isTeacherOnly = ($user->role === 'teacher' && !in_array($user->role, ['admin', 'headmaster', 'curriculum']));

        // 1. Academic Year Context
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = $academicYears->where('is_active', true)->first();
        $selectedYearId = $request->input('academic_year_id', $activeYear ? $activeYear->id : ($academicYears->first()->id ?? null));
        $contextYear = $academicYears->where('id', $selectedYearId)->first() ?: $activeYear;

        // 2. Daftar Ekstrakurikuler (Bagi Akun Guru/Pembina dibatasi HANYA kelompok binaannya saja)
        $eskulsQuery = Eskul::query();
        if ($selectedYearId) {
            $eskulsQuery->where('academic_year_id', $selectedYearId);
        }

        if ($isTeacherOnly) {
            $eskulsQuery->where(function($q) use ($user) {
                if (!empty($user->eskul_id)) {
                    $q->where('id', $user->eskul_id);
                }
                if (!empty($user->name)) {
                    $q->orWhere('instructor_name', $user->name)
                      ->orWhere('instructor_name', 'LIKE', '%' . $user->name . '%');
                }
            });
        }

        $eskuls = $eskulsQuery->orderBy('name')->get();

        // Fallback jika belum ditemukan dengan filter academic_year_id untuk pembina
        if ($isTeacherOnly && $eskuls->isEmpty()) {
            $eskuls = Eskul::where(function($q) use ($user) {
                if (!empty($user->eskul_id)) {
                    $q->where('id', $user->eskul_id);
                }
                if (!empty($user->name)) {
                    $q->orWhere('instructor_name', $user->name)
                      ->orWhere('instructor_name', 'LIKE', '%' . $user->name . '%');
                }
            })->orderBy('name')->get();
        } elseif (!$isTeacherOnly && $eskuls->isEmpty()) {
            $eskuls = Eskul::orderBy('name')->get();
        }

        // 3. User & Eskul Selection
        $teacherEskulIds = $isTeacherOnly ? $eskuls->pluck('id')->toArray() : [];

        if ($isTeacherOnly) {
            if ($eskuls->count() === 1) {
                $selectedEskulId = $eskuls->first()->id;
            } elseif ($eskuls->count() > 1) {
                $requestedId = $request->input('eskul_id');
                if ($requestedId && in_array((int)$requestedId, $teacherEskulIds)) {
                    $selectedEskulId = (int)$requestedId;
                } else {
                    $selectedEskulId = ($requestedId === 'all') ? 'all' : $teacherEskulIds[0];
                }
            } else {
                $selectedEskulId = 'none';
            }
        } else {
            $selectedEskulId = $request->input('eskul_id', 'all');
        }

        $selectedEskul = ($selectedEskulId !== 'all' && $selectedEskulId !== 'none') 
            ? $eskuls->where('id', $selectedEskulId)->first() 
            : null;

        // Bersihkan sisa data duplikat jika ada agar grafik & statistik 100% akurat
        AttendanceController::cleanupDuplicateAttendances($selectedEskul ? (int)$selectedEskul->id : null);

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
        $gradeQuery = Grade::query();

        if ($periodMode !== 'year' && $selectedYearId) {
            $studentQuery->where('academic_year_id', $selectedYearId);
            $coachQuery->where('academic_year_id', $selectedYearId);
            $gradeQuery->where('academic_year_id', $selectedYearId);
        }

        if ($selectedEskulId !== 'all' && $selectedEskulId !== 'none') {
            $studentQuery->where('eskul_id', $selectedEskulId);
            $gradeQuery->where('eskul_id', $selectedEskulId);
            $coachQuery->where(function($q) use ($selectedEskulId, $selectedEskul) {
                $q->whereHas('user', function($uq) use ($selectedEskulId, $selectedEskul) {
                    $uq->where('eskul_id', $selectedEskulId);
                    if ($selectedEskul && !empty($selectedEskul->instructor_name)) {
                        $uq->orWhere('name', $selectedEskul->instructor_name);
                    }
                })->orWhereHas('substituteUser', function($sq) use ($selectedEskulId, $selectedEskul) {
                    $sq->where('eskul_id', $selectedEskulId);
                    if ($selectedEskul && !empty($selectedEskul->instructor_name)) {
                        $sq->orWhere('name', $selectedEskul->instructor_name);
                    }
                });
            });
        } elseif ($isTeacherOnly) {
            if (!empty($teacherEskulIds)) {
                $studentQuery->whereIn('eskul_id', $teacherEskulIds);
                $gradeQuery->whereIn('eskul_id', $teacherEskulIds);
                $coachQuery->where(function($q) use ($teacherEskulIds, $eskuls, $user) {
                    $instructors = $eskuls->pluck('instructor_name')->filter()->unique()->toArray();
                    $q->where('user_id', $user->id)
                      ->orWhereHas('user', function($uq) use ($teacherEskulIds, $instructors) {
                          $uq->whereIn('eskul_id', $teacherEskulIds);
                          if (!empty($instructors)) {
                              $uq->orWhereIn('name', $instructors);
                          }
                      })->orWhereHas('substituteUser', function($sq) use ($teacherEskulIds) {
                          $sq->whereIn('eskul_id', $teacherEskulIds);
                      });
                });
            } else {
                $studentQuery->whereRaw('1 = 0');
                $coachQuery->whereRaw('1 = 0');
                $gradeQuery->whereRaw('1 = 0');
            }
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

        // ========================================================
        // 6b. ANALISIS & REKAPITULASI PENILAIAN (HARIAN, SAS 1, SAS 2)
        // ========================================================
        $parsePredicates = function($records) {
            $countA = 0;
            $countB = 0;
            $countC = 0;
            $total = 0;

            foreach ($records as $r) {
                $val = $r->score;
                if (empty($val) || $val === '-') continue;

                $json = json_decode($val, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                    foreach (['reading', 'writing', 'counting'] as $field) {
                        if (!empty($json[$field])) {
                            $letter = strtoupper(trim($json[$field]));
                            if ($letter === 'A') $countA++;
                            elseif ($letter === 'B') $countB++;
                            elseif ($letter === 'C') $countC++;
                            $total++;
                        }
                    }
                } else {
                    $letter = strtoupper(trim($val));
                    if ($letter === 'A') $countA++;
                    elseif ($letter === 'B') $countB++;
                    elseif ($letter === 'C') $countC++;
                    $total++;
                }
            }

            $pctA = $total > 0 ? round(($countA / $total) * 100, 1) : 0;
            $pctB = $total > 0 ? round(($countB / $total) * 100, 1) : 0;
            $pctC = $total > 0 ? round(($countC / $total) * 100, 1) : 0;
            $pctGood = $total > 0 ? round((($countA + $countB) / $total) * 100, 1) : 0;

            $dominant = '-';
            if ($total > 0) {
                if ($countA >= $countB && $countA >= $countC) {
                    $dominant = 'A (Sangat Baik)';
                } elseif ($countB >= $countA && $countB >= $countC) {
                    $dominant = 'B (Baik)';
                } else {
                    $dominant = 'C (Cukup)';
                }
            }

            return [
                'total'         => $total,
                'records_count' => $records->count(),
                'count_a'       => $countA,
                'count_b'       => $countB,
                'count_c'       => $countC,
                'pct_a'         => $pctA,
                'pct_b'         => $pctB,
                'pct_c'         => $pctC,
                'pct_good'      => $pctGood,
                'dominant'      => $dominant,
            ];
        };

        // Ambil semua data nilai pada konteks tahun ajaran & eskul
        $allGrades = $gradeQuery->get();

        // 1. Nilai Harian (dengan filter waktu aktif)
        $dailyRecords = $allGrades->where('type', 'daily')->filter(function($item) use ($periodMode, $startDate, $endDate, $selectedMonth, $selectedSemester) {
            if (empty($item->date)) {
                if ($periodMode === 'semester' && $selectedSemester !== 'all') {
                    return (string)$item->semester === (string)$selectedSemester;
                }
                return true;
            }
            $d = Carbon::parse($item->date);
            if ($periodMode === 'date') {
                return $item->date >= $startDate && $item->date <= $endDate;
            } elseif ($periodMode === 'month') {
                $mParts = explode('-', $selectedMonth);
                if (count($mParts) === 2) {
                    return $d->year === (int)$mParts[0] && $d->month === (int)$mParts[1];
                }
            } elseif ($periodMode === 'semester') {
                if ($selectedSemester === '1') {
                    return ((string)$item->semester === '1') || ($d->month >= 7 && $d->month <= 12);
                } elseif ($selectedSemester === '2') {
                    return ((string)$item->semester === '2') || ($d->month >= 1 && $d->month <= 6);
                }
            }
            return true;
        });

        // 2. Nilai SAS 1 (Sumatif Akhir Semester 1)
        $sas1Records = $allGrades->where('type', 'sas1');

        // 3. Nilai SAS 2 (Sumatif Akhir Semester 2)
        $sas2Records = $allGrades->where('type', 'sas2');

        // Ringkasan Statistik Predikat
        $dailyGradeStats = $parsePredicates($dailyRecords);
        $sas1GradeStats  = $parsePredicates($sas1Records);
        $sas2GradeStats  = $parsePredicates($sas2Records);
        $totalGradesCount = $dailyRecords->count() + $sas1Records->count() + $sas2Records->count();

        // Datasets untuk Grouped Bar Chart Distribusi Predikat (Harian vs SAS 1 vs SAS 2)
        $gradeComparisonLabels = ['Nilai Harian', 'SAS 1 (Ganjil)', 'SAS 2 (Genap)'];
        $gradeCompPctA   = [$dailyGradeStats['pct_a'], $sas1GradeStats['pct_a'], $sas2GradeStats['pct_a']];
        $gradeCompPctB   = [$dailyGradeStats['pct_b'], $sas1GradeStats['pct_b'], $sas2GradeStats['pct_b']];
        $gradeCompPctC   = [$dailyGradeStats['pct_c'], $sas1GradeStats['pct_c'], $sas2GradeStats['pct_c']];
        $gradeCompCountA = [$dailyGradeStats['count_a'], $sas1GradeStats['count_a'], $sas2GradeStats['count_a']];
        $gradeCompCountB = [$dailyGradeStats['count_b'], $sas1GradeStats['count_b'], $sas2GradeStats['count_b']];
        $gradeCompCountC = [$dailyGradeStats['count_c'], $sas1GradeStats['count_c'], $sas2GradeStats['count_c']];
        $gradeCompTotals = [$dailyGradeStats['total'], $sas1GradeStats['total'], $sas2GradeStats['total']];

        // 7. Generate Datasets for Charts Based on Period Mode
        $mainChartLabels = [];
        $mainChartCoachRate = [];
        $mainChartStudentRate = [];
        $mainChartDailyGradeRate = [];
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

                // Daily Grades on this date
                $sDayDaily = $allGrades->where('type', 'daily')->where('date', $dt);
                $sDayDailyStats = $parsePredicates($sDayDaily);
                $mainChartDailyGradeRate[] = ($sDayDailyStats['total'] > 0) ? $sDayDailyStats['pct_good'] : null;
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
            $fullYearDailyGrades = $allGrades->where('type', 'daily');

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

                // Filter daily grade for this month & year
                $mDaily = $fullYearDailyGrades->filter(function($item) use ($m) {
                    if (empty($item->date)) return false;
                    $d = Carbon::parse($item->date);
                    return $d->month === $m['num'] && $d->year === $m['year'];
                });
                $mDailyStats = $parsePredicates($mDaily);
                $mainChartDailyGradeRate[] = ($mDailyStats['total'] > 0) ? $mDailyStats['pct_good'] : null;
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

                // Daily grade in semester
                $sDailySem = $allGrades->where('type', 'daily')->filter(function($item) use ($sem) {
                    if (!empty($item->semester)) return (string)$item->semester === (string)$sem['key'];
                    if (empty($item->date)) return false;
                    $d = Carbon::parse($item->date);
                    return ($sem['key'] === '1') ? ($d->month >= 7 && $d->month <= 12) : ($d->month >= 1 && $d->month <= 6);
                });
                $sDailySemStats = $parsePredicates($sDailySem);
                $mainChartDailyGradeRate[] = ($sDailySemStats['total'] > 0) ? $sDailySemStats['pct_good'] : null;
            }
        } elseif ($periodMode === 'year') {
            // Multi-Year Comparison
            $targetYears = $academicYears->take(4)->reverse()->values();
            foreach ($targetYears as $yr) {
                $mainChartLabels[] = $yr->name;

                // Coach for year
                $yCoachQuery = TeacherAttendance::where('academic_year_id', $yr->id);
                if ($selectedEskulId !== 'all' && $selectedEskulId !== 'none') {
                    $yCoachQuery->where(function($q) use ($selectedEskulId, $selectedEskul) {
                        $q->whereHas('user', function($uq) use ($selectedEskulId, $selectedEskul) {
                            $uq->where('eskul_id', $selectedEskulId);
                            if ($selectedEskul && !empty($selectedEskul->instructor_name)) {
                                $uq->orWhere('name', $selectedEskul->instructor_name);
                            }
                        })->orWhereHas('substituteUser', function($sq) use ($selectedEskulId, $selectedEskul) {
                            $sq->where('eskul_id', $selectedEskulId);
                            if ($selectedEskul && !empty($selectedEskul->instructor_name)) {
                                $sq->orWhere('name', $selectedEskul->instructor_name);
                            }
                        });
                    });
                } elseif ($isTeacherOnly && !empty($teacherEskulIds)) {
                    $yCoachQuery->where(function($q) use ($teacherEskulIds, $user) {
                        $q->where('user_id', $user->id)
                          ->orWhereHas('user', function($uq) use ($teacherEskulIds) {
                              $uq->whereIn('eskul_id', $teacherEskulIds);
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
                if ($selectedEskulId !== 'all' && $selectedEskulId !== 'none') {
                    $yStudentQuery->where('eskul_id', $selectedEskulId);
                } elseif ($isTeacherOnly && !empty($teacherEskulIds)) {
                    $yStudentQuery->whereIn('eskul_id', $teacherEskulIds);
                }
                $yStudentRecords = $yStudentQuery->get();
                $yStudentTotal = $yStudentRecords->count();
                $yStudentPresent = $yStudentRecords->where('status', 'present')->count();
                $mainChartStudentRate[] = ($yStudentTotal > 0) ? round(($yStudentPresent / $yStudentTotal) * 100, 1) : 0;
                $mainChartStudentPresentCount[] = $yStudentPresent;

                // Grade for year
                $yDaily = Grade::where('academic_year_id', $yr->id)->where('type', 'daily');
                if ($selectedEskulId !== 'all' && $selectedEskulId !== 'none') {
                    $yDaily->where('eskul_id', $selectedEskulId);
                } elseif ($isTeacherOnly && !empty($teacherEskulIds)) {
                    $yDaily->whereIn('eskul_id', $teacherEskulIds);
                }
                $yDailyStats = $parsePredicates($yDaily->get());
                $mainChartDailyGradeRate[] = ($yDailyStats['total'] > 0) ? $yDailyStats['pct_good'] : null;
            }
        }

        // 8. Breakdown and Horizontal Comparison per Eskul
        $eskulBreakdown = collect();
        $eskulComparisonLabels = [];
        $eskulComparisonStudentRate = [];
        $eskulComparisonCoachRate = [];

        $eskulGradeCompLabels = [];
        $eskulGradeCompDailyRate = [];
        $eskulGradeCompSas1Rate = [];
        $eskulGradeCompSas2Rate = [];

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
                    if (!empty($eskulItem->instructor_name)) {
                        $uq->orWhere('name', $eskulItem->instructor_name);
                    }
                })->orWhereHas('substituteUser', function($sq) use ($eskulItem) {
                    $sq->where('eskul_id', $eskulItem->id);
                    if (!empty($eskulItem->instructor_name)) {
                        $sq->orWhere('name', $eskulItem->instructor_name);
                    }
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
            if (!$assignedCoach && !empty($eskulItem->instructor_name)) {
                $assignedCoach = User::where('name', 'LIKE', '%' . $eskulItem->instructor_name . '%')->first();
            }
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

            // Grade Stats per Eskul
            $eDailyGrades = $dailyRecords->where('eskul_id', $eskulItem->id);
            $eSas1Grades  = $sas1Records->where('eskul_id', $eskulItem->id);
            $eSas2Grades  = $sas2Records->where('eskul_id', $eskulItem->id);

            $eDailyStats = $parsePredicates($eDailyGrades);
            $eSas1Stats  = $parsePredicates($eSas1Grades);
            $eSas2Stats  = $parsePredicates($eSas2Grades);

            $eskulGradeCompLabels[] = $eskulItem->name;
            $eskulGradeCompDailyRate[] = $eDailyStats['pct_good'];
            $eskulGradeCompSas1Rate[]  = $eSas1Stats['pct_good'];
            $eskulGradeCompSas2Rate[]  = $eSas2Stats['pct_good'];

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
                'daily_stats' => (object)$eDailyStats,
                'sas1_stats'  => (object)$eSas1Stats,
                'sas2_stats'  => (object)$eSas2Stats,
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
            'isTeacherOnly',
            'user',
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
            'mainChartDailyGradeRate',
            'mainChartStudentPresentCount',
            'dailyGradeStats',
            'sas1GradeStats',
            'sas2GradeStats',
            'totalGradesCount',
            'gradeComparisonLabels',
            'gradeCompPctA',
            'gradeCompPctB',
            'gradeCompPctC',
            'gradeCompCountA',
            'gradeCompCountB',
            'gradeCompCountC',
            'gradeCompTotals',
            'eskulGradeCompLabels',
            'eskulGradeCompDailyRate',
            'eskulGradeCompSas1Rate',
            'eskulGradeCompSas2Rate',
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
