<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\CharacterAppreciation;
use App\Models\CharacterCase;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class CharacterDashboardController extends Controller
{
    /**
     * Cache versioning helper for zero-downtime and universal driver compatibility (File, Redis, Database)
     */
    public static function getCacheVersion(): string
    {
        return (string) Cache::get('char_analytics_cache_version', '1');
    }

    /**
     * Invalidate analytics and character dashboard cache on data mutations
     */
    public static function invalidateAnalyticsCache(): void
    {
        try {
            Cache::put('char_analytics_cache_version', (string) microtime(true), 86400 * 30);
        } catch (\Throwable $e) {
            // Ignore
        }
    }

    protected function ensureTablesExist()
    {
        if (!Schema::hasTable('character_cases') || !Schema::hasTable('character_appreciations') || !Schema::hasColumn('character_cases', 'tppk_category')) {
            try {
                Artisan::call('migrate', ['--force' => true]);
            } catch (\Throwable $e) {
                // Ignore
            }
        }
    }

    public function dashboard(Request $request)
    {
        $this->ensureTablesExist();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $selectedClass = $request->class;

        $user = Auth::user();
        if ($user->role === 'teacher') {
            if (empty($user->homeroom_class)) {
                abort(403, 'Akses ditolak. Anda tidak memiliki penugasan Wali Kelas.');
            }
            $selectedClass = $user->homeroom_class;
            $classes = collect([$user->homeroom_class]);
        } else {
            $classes = Student::forYear($selectedYearId)
                ->select('class')->distinct()->orderBy('class')->pluck('class');
        }

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        // Scopes
        $caseBase = CharacterCase::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($selectedClass && $selectedClass !== 'ALL', fn($q) => $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass)));

        $apprBase = CharacterAppreciation::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($selectedClass && $selectedClass !== 'ALL', fn($q) => $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass)));

        // Cached dashboard analytics (10 minutes TTL with auto-invalidation on new case/appreciation)
        $cacheVersion = self::getCacheVersion();
        $dashCacheKey = "char_dashboard_v{$cacheVersion}_{$selectedYearId}_" . ($selectedClass ?: 'ALL');

        $cachedDash = Cache::remember($dashCacheKey, 600, function() use ($selectedYearId, $selectedClass, $caseBase, $apprBase) {
            // Stat cards
            $totalCases = (clone $caseBase)->count();
            $resolvedCases = (clone $caseBase)->where('status', 'selesai')->count();
            $activeCases = (clone $caseBase)->where('status', 'dalam_pembinaan')->count();
            $tppkCases = (clone $caseBase)->where(function($q) {
                $q->whereNotNull('tppk_category')->where('tppk_category', '!=', 'non_tppk');
            })->count();

            $totalAppreciations = (clone $apprBase)->count();
            $totalAppreciationPoints = (clone $apprBase)->sum('point');

            // Top students by appreciation
            $topStudents = Student::forYear($selectedYearId)
                ->when($selectedClass && $selectedClass !== 'ALL', fn($q) => $q->where('class', $selectedClass))
                ->withCount([
                    'characterAppreciations as appreciation_count' => function($q) use ($selectedYearId) {
                        $q->when($selectedYearId, fn($sub) => $sub->where('academic_year_id', $selectedYearId));
                    }
                ])
                ->withSum([
                    'characterAppreciations as total_points' => function($q) use ($selectedYearId) {
                        $q->when($selectedYearId, fn($sub) => $sub->where('academic_year_id', $selectedYearId));
                    }
                ], 'point')
                ->having('appreciation_count', '>', 0)
                ->orderByDesc('total_points')
                ->take(5)
                ->get();

            // Students needing attention (most cases)
            $attentionStudents = Student::forYear($selectedYearId)
                ->when($selectedClass && $selectedClass !== 'ALL', fn($q) => $q->where('class', $selectedClass))
                ->withCount([
                    'characterCases as case_count' => function($q) use ($selectedYearId) {
                        $q->when($selectedYearId, fn($sub) => $sub->where('academic_year_id', $selectedYearId));
                    }
                ])
                ->having('case_count', '>', 0)
                ->orderByDesc('case_count')
                ->take(5)
                ->get();

            // Recent activity
            $recentCases = (clone $caseBase)->with('student')->orderBy('date', 'desc')->take(5)->get();
            $recentAppreciations = (clone $apprBase)->with('student')->orderBy('date', 'desc')->take(5)->get();

            return compact(
                'totalCases',
                'resolvedCases',
                'activeCases',
                'tppkCases',
                'totalAppreciations',
                'totalAppreciationPoints',
                'topStudents',
                'attentionStudents',
                'recentCases',
                'recentAppreciations'
            );
        });

        return view('character.dashboard', array_merge([
            'academicYears' => $academicYears,
            'classes' => $classes,
            'selectedYearId' => $selectedYearId,
            'selectedClass' => $selectedClass,
        ], $cachedDash));
    }

    public function report(Request $request)
    {
        $this->ensureTablesExist();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $selectedClass = $request->class;

        $user = Auth::user();
        if ($user->role === 'teacher') {
            if (empty($user->homeroom_class)) {
                abort(403, 'Akses ditolak. Anda tidak memiliki penugasan Wali Kelas.');
            }
            $selectedClass = $user->homeroom_class;
            $classes = collect([$user->homeroom_class]);
        } else {
            $classes = Student::forYear($selectedYearId)
                ->select('class')->distinct()->orderBy('class')->pluck('class');
        }

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        $students = collect();

        if ($selectedClass) {
            $query = Student::forYear($selectedYearId)
                ->where(function($q) {
                    $q->where('status', '!=', 'graduated')->orWhereNull('status');
                });

            if ($selectedClass !== 'ALL') {
                $query->where('class', $selectedClass);
            }

            $students = $query->with([
                'characterCases' => fn($q) => $q->when($selectedYearId, fn($sub) => $sub->where('academic_year_id', $selectedYearId)),
                'characterAppreciations' => fn($q) => $q->when($selectedYearId, fn($sub) => $sub->where('academic_year_id', $selectedYearId))
            ])->orderBy('class')->orderBy('name')->get();

            foreach ($students as $student) {
                $student->cases_count = $student->characterCases->count();
                $student->cases_point_total = $student->characterCases->sum('point');
                $student->unresolved_cases_count = $student->characterCases->where('status', 'dalam_pembinaan')->count();

                $student->appreciations_count = $student->characterAppreciations->count();
                $student->appreciation_points_total = $student->characterAppreciations->sum('point');

                // Compute Net Balance & Predicate
                $netPoint = $student->appreciation_points_total - $student->cases_point_total;
                $student->net_point = $netPoint;

                if ($student->cases_count == 0 && $student->appreciations_count >= 3) {
                    $student->predicate = ['label' => 'Bintang Karakter SMILE', 'badge' => '#dcfce7', 'color' => '#15803d'];
                } elseif ($student->unresolved_cases_count > 2 || $netPoint < -10) {
                    $student->predicate = ['label' => 'Perlu Pendampingan Intensif', 'badge' => '#fee2e2', 'color' => '#991b1b'];
                } elseif ($student->cases_count > 0 && $student->unresolved_cases_count > 0) {
                    $student->predicate = ['label' => 'Dalam Pembinaan', 'badge' => '#fef3c7', 'color' => '#b45309'];
                } else {
                    $student->predicate = ['label' => 'Baik & Kondusif', 'badge' => '#e0f2fe', 'color' => '#0369a1'];
                }
            }
        }

        return view('character.report', compact(
            'academicYears',
            'classes',
            'selectedYearId',
            'selectedClass',
            'students'
        ));
    }

    public function printClassReport(Request $request)
    {
        $this->ensureTablesExist();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $selectedClass = $request->class;

        $user = Auth::user();
        if ($user->role === 'teacher') {
            if (empty($user->homeroom_class) || ($selectedClass && $selectedClass !== $user->homeroom_class)) {
                abort(403, 'Akses ditolak. Anda hanya dapat mencetak laporan karakter untuk kelas binaan Anda.');
            }
            $selectedClass = $user->homeroom_class;
        }

        $year = AcademicYear::find($selectedYearId);

        $query = Student::forYear($selectedYearId)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')->orWhereNull('status');
            });

        if ($selectedClass && $selectedClass !== 'ALL') {
            $query->where('class', $selectedClass);
        }

        $students = $query->with([
            'characterCases' => fn($q) => $q->when($selectedYearId, fn($sub) => $sub->where('academic_year_id', $selectedYearId)),
            'characterAppreciations' => fn($q) => $q->when($selectedYearId, fn($sub) => $sub->where('academic_year_id', $selectedYearId))
        ])->orderBy('class')->orderBy('name')->get();

        foreach ($students as $student) {
            $student->cases_count = $student->characterCases->count();
            $student->cases_point_total = $student->characterCases->sum('point');
            $student->unresolved_cases_count = $student->characterCases->where('status', 'dalam_pembinaan')->count();

            $student->appreciations_count = $student->characterAppreciations->count();
            $student->appreciation_points_total = $student->characterAppreciations->sum('point');

            $netPoint = $student->appreciation_points_total - $student->cases_point_total;
            $student->net_point = $netPoint;

            if ($student->cases_count == 0 && $student->appreciations_count >= 3) {
                $student->predicate_label = 'Bintang Karakter';
            } elseif ($student->unresolved_cases_count > 2 || $netPoint < -10) {
                $student->predicate_label = 'Perlu Pendampingan';
            } elseif ($student->cases_count > 0 && $student->unresolved_cases_count > 0) {
                $student->predicate_label = 'Dalam Pembinaan';
            } else {
                $student->predicate_label = 'Baik & Kondusif';
            }
        }

        return view('character.print_class_report', compact(
            'students',
            'year',
            'selectedClass'
        ));
    }

    /**
     * Portofolio Rekam Jejak Karakter Siswa (Dokumen Penilaian Otentik Akreditasi & Rapor Karakter)
     */
    public function studentPortfolio(Request $request, Student $student)
    {
        $this->ensureTablesExist();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $year = AcademicYear::find($selectedYearId);

        $appreciations = CharacterAppreciation::where('student_id', $student->id)
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->orderBy('date', 'desc')
            ->get();

        $cases = CharacterCase::where('student_id', $student->id)
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->orderBy('date', 'desc')
            ->get();

        $totalAppreciationPoints = $appreciations->sum('point');
        $totalCasePoints = $cases->sum('point');
        $netPoints = $totalAppreciationPoints - $totalCasePoints;

        $unresolvedCount = $cases->where('status', 'dalam_pembinaan')->count();

        if ($cases->count() == 0 && $appreciations->count() >= 3) {
            $predicate = ['label' => 'Bintang Karakter SMILE Utama', 'badge' => '#dcfce7', 'color' => '#15803d'];
        } elseif ($unresolvedCount > 2 || $netPoints < -10) {
            $predicate = ['label' => 'Perlu Pendampingan Intensif', 'badge' => '#fee2e2', 'color' => '#991b1b'];
        } elseif ($cases->count() > 0 && $unresolvedCount > 0) {
            $predicate = ['label' => 'Dalam Pembinaan', 'badge' => '#fef3c7', 'color' => '#b45309'];
        } else {
            $predicate = ['label' => 'Baik & Kondusif', 'badge' => '#e0f2fe', 'color' => '#0369a1'];
        }

        $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
        $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
        $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : "Nur'asiah, S.Pd.I");

        return view('character.student_portfolio', compact(
            'student',
            'year',
            'appreciations',
            'cases',
            'totalAppreciationPoints',
            'totalCasePoints',
            'netPoints',
            'predicate',
            'headmasterName'
        ));
    }

    /**
     * Membangun dataset analitik capaian karakter (Harian, Bulanan, Semester, Tahunan) dengan Query Caching cerdas
     */
    protected function buildAnalyticsData(Request $request)
    {
        $this->ensureTablesExist();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $academicYear = AcademicYear::find($selectedYearId);
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        $selectedClass = $request->class;
        $user = Auth::user();
        if ($user->role === 'teacher') {
            if (!empty($user->homeroom_class)) {
                $selectedClass = $user->homeroom_class;
                $classes = collect([$user->homeroom_class]);
            } else {
                $classes = Student::forYear($selectedYearId)->select('class')->distinct()->whereNotNull('class')->orderBy('class')->pluck('class');
            }
        } else {
            $classes = Student::forYear($selectedYearId)->select('class')->distinct()->whereNotNull('class')->orderBy('class')->pluck('class');
        }

        // Periode: daily, monthly, semester, yearly
        $period = $request->input('period', 'monthly');
        if (!in_array($period, ['daily', 'monthly', 'semester', 'yearly'])) {
            $period = 'monthly';
        }

        $days = (int) $request->input('days', 30);
        if (!in_array($days, [7, 14, 30, 60])) {
            $days = 30;
        }

        // Base query
        $caseBase = CharacterCase::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($selectedClass && $selectedClass !== 'ALL', fn($q) => $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass)));

        $apprBase = CharacterAppreciation::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($selectedClass && $selectedClass !== 'ALL', fn($q) => $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass)));

        // Caching 10 menit untuk dataset analitik agregasi
        $cacheVersion = self::getCacheVersion();
        $cacheKey = "char_analytics_v{$cacheVersion}_{$selectedYearId}_" . ($selectedClass ?: 'ALL') . "_{$period}_{$days}";

        $cachedMetrics = Cache::remember($cacheKey, 600, function() use ($selectedYearId, $selectedClass, $period, $days, $caseBase, $apprBase, $academicYear) {

            // Executive KPI stats
            $totalCases = (clone $caseBase)->count();
            $resolvedCases = (clone $caseBase)->where('status', 'selesai')->count();
            $activeCases = (clone $caseBase)->where('status', 'dalam_pembinaan')->count();
            $totalCasePoints = (clone $caseBase)->sum('point');

            $totalAppreciations = (clone $apprBase)->count();
            $totalAppreciationPoints = (clone $apprBase)->sum('point');
            $netPoints = $totalAppreciationPoints - $totalCasePoints;

            $tppkCases = (clone $caseBase)->where(function($q) {
                $q->whereNotNull('tppk_category')->where('tppk_category', '!=', 'non_tppk');
            })->count();

            $resolveRate = $totalCases > 0 ? round(($resolvedCases / $totalCases) * 100, 1) : 100;

            // Data Trend Series based on selected period
            $trendLabels = [];
            $trendCases = [];
            $trendAppreciations = [];
            $trendPoints = [];
            $tableData = [];

            if ($period === 'daily') {
                // Rentang Harian: X hari terakhir s.d hari ini
                $startDate = \Carbon\Carbon::now()->subDays($days - 1)->startOfDay();
                $endDate = \Carbon\Carbon::now()->endOfDay();

                $casesByDate = (clone $caseBase)
                    ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->selectRaw('date, count(*) as count, sum(point) as points')
                    ->groupBy('date')
                    ->get()
                    ->keyBy(fn($item) => $item->date ? $item->date->format('Y-m-d') : '');

                $apprByDate = (clone $apprBase)
                    ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->selectRaw('date, count(*) as count, sum(point) as points')
                    ->groupBy('date')
                    ->get()
                    ->keyBy(fn($item) => $item->date ? $item->date->format('Y-m-d') : '');

                $cursor = clone $startDate;
                while ($cursor->lte($endDate)) {
                    $ymd = $cursor->format('Y-m-d');
                    $label = $cursor->translatedFormat('d M');
                    $trendLabels[] = $label;

                    $cCount = $casesByDate[$ymd]->count ?? 0;
                    $cPoints = $casesByDate[$ymd]->points ?? 0;
                    $aCount = $apprByDate[$ymd]->count ?? 0;
                    $aPoints = $apprByDate[$ymd]->points ?? 0;

                    $trendCases[] = $cCount;
                    $trendAppreciations[] = $aCount;
                    $trendPoints[] = $aPoints;

                    $tableData[] = [
                        'label' => $cursor->translatedFormat('l, d F Y'),
                        'cases_count' => $cCount,
                        'case_points' => $cPoints,
                        'appr_count' => $aCount,
                        'appr_points' => $aPoints,
                        'net' => $aPoints - $cPoints,
                    ];

                    $cursor->addDay();
                }

                // Urutkan tabel dari tanggal terbaru untuk tampilan
                $tableDataReversed = array_reverse($tableData);
            } elseif ($period === 'monthly') {
                // 12 Bulan Tahun Pelajaran (Juli s.d Juni)
                $yearStart = 2026;
                if ($academicYear && preg_match('/(\d{4})/', $academicYear->name, $matches)) {
                    $yearStart = (int) $matches[1];
                }

                $months = [
                    ['num' => 7,  'year' => $yearStart,     'name' => 'Juli'],
                    ['num' => 8,  'year' => $yearStart,     'name' => 'Agustus'],
                    ['num' => 9,  'year' => $yearStart,     'name' => 'September'],
                    ['num' => 10, 'year' => $yearStart,     'name' => 'Oktober'],
                    ['num' => 11, 'year' => $yearStart,     'name' => 'November'],
                    ['num' => 12, 'year' => $yearStart,     'name' => 'Desember'],
                    ['num' => 1,  'year' => $yearStart + 1, 'name' => 'Januari'],
                    ['num' => 2,  'year' => $yearStart + 1, 'name' => 'Februari'],
                    ['num' => 3,  'year' => $yearStart + 1, 'name' => 'Maret'],
                    ['num' => 4,  'year' => $yearStart + 1, 'name' => 'April'],
                    ['num' => 5,  'year' => $yearStart + 1, 'name' => 'Mei'],
                    ['num' => 6,  'year' => $yearStart + 1, 'name' => 'Juni'],
                ];

                foreach ($months as $m) {
                    $trendLabels[] = $m['name'];

                    $mCases = (clone $caseBase)->whereMonth('date', $m['num'])->whereYear('date', $m['year'])->get();
                    $mApprs = (clone $apprBase)->whereMonth('date', $m['num'])->whereYear('date', $m['year'])->get();

                    $cCount = $mCases->count();
                    $cPoints = $mCases->sum('point');
                    $aCount = $mApprs->count();
                    $aPoints = $mApprs->sum('point');

                    $trendCases[] = $cCount;
                    $trendAppreciations[] = $aCount;
                    $trendPoints[] = $aPoints;

                    $tableData[] = [
                        'label' => $m['name'] . ' ' . $m['year'],
                        'cases_count' => $cCount,
                        'case_points' => $cPoints,
                        'appr_count' => $aCount,
                        'appr_points' => $aPoints,
                        'net' => $aPoints - $cPoints,
                    ];
                }

                $tableDataReversed = array_reverse($tableData);
            } elseif ($period === 'semester') {
                // Semester Ganjil vs Semester Genap
                $yearStart = 2026;
                if ($academicYear && preg_match('/(\d{4})/', $academicYear->name, $matches)) {
                    $yearStart = (int) $matches[1];
                }

                $semesters = [
                    [
                        'name' => 'Semester 1 (Ganjil)',
                        'start' => "$yearStart-07-01",
                        'end' => "$yearStart-12-31"
                    ],
                    [
                        'name' => 'Semester 2 (Genap)',
                        'start' => ($yearStart + 1) . "-01-01",
                        'end' => ($yearStart + 1) . "-06-30"
                    ]
                ];

                foreach ($semesters as $s) {
                    $trendLabels[] = $s['name'];

                    $sCases = (clone $caseBase)->whereBetween('date', [$s['start'], $s['end']])->get();
                    $sApprs = (clone $apprBase)->whereBetween('date', [$s['start'], $s['end']])->get();

                    $cCount = $sCases->count();
                    $cPoints = $sCases->sum('point');
                    $aCount = $sApprs->count();
                    $aPoints = $sApprs->sum('point');

                    $trendCases[] = $cCount;
                    $trendAppreciations[] = $aCount;
                    $trendPoints[] = $aPoints;

                    $tableData[] = [
                        'label' => $s['name'],
                        'cases_count' => $cCount,
                        'case_points' => $cPoints,
                        'appr_count' => $aCount,
                        'appr_points' => $aPoints,
                        'net' => $aPoints - $cPoints,
                    ];
                }

                $tableDataReversed = $tableData;
            } else {
                // Tahunan (Yearly - komparasi antar tahun ajaran)
                $allYears = AcademicYear::orderBy('name', 'asc')->take(5)->get();
                foreach ($allYears as $y) {
                    $trendLabels[] = $y->name;

                    $yCaseBase = CharacterCase::where('academic_year_id', $y->id)
                        ->when($selectedClass && $selectedClass !== 'ALL', fn($q) => $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass)));
                    $yApprBase = CharacterAppreciation::where('academic_year_id', $y->id)
                        ->when($selectedClass && $selectedClass !== 'ALL', fn($q) => $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass)));

                    $cCount = $yCaseBase->count();
                    $cPoints = $yCaseBase->sum('point');
                    $aCount = $yApprBase->count();
                    $aPoints = $yApprBase->sum('point');

                    $trendCases[] = $cCount;
                    $trendAppreciations[] = $aCount;
                    $trendPoints[] = $aPoints;

                    $tableData[] = [
                        'label' => 'Tahun Ajaran ' . $y->name . ($y->is_active ? ' (Aktif)' : ''),
                        'cases_count' => $cCount,
                        'case_points' => $cPoints,
                        'appr_count' => $aCount,
                        'appr_points' => $aPoints,
                        'net' => $aPoints - $cPoints,
                    ];
                }

                $tableDataReversed = array_reverse($tableData);
            }

            // 2. Sebaran 8 Dimensi Profil Lulusan Kemendikdasmen (Deep Learning)
            $dimensionLabels = [
                'Keimanan & Ketakwaan',
                'Kewargaan',
                'Penalaran Kritis',
                'Kreativitas',
                'Kolaborasi',
                'Kemandirian',
                'Kesehatan',
                'Komunikasi'
            ];

            $apprList = (clone $apprBase)->get();
            $dimensionCounts = array_fill_keys($dimensionLabels, 0);
            foreach ($apprList as $ap) {
                $dim = strtolower($ap->dimension_p5 ?? '');
                if (str_contains($dim, 'iman') || str_contains($dim, 'takwa') || str_contains($dim, 'taqwa') || str_contains($dim, 'spiritual')) {
                    $dimensionCounts['Keimanan & Ketakwaan']++;
                } elseif (str_contains($dim, 'kewargaan') || str_contains($dim, 'kebinekaan')) {
                    $dimensionCounts['Kewargaan']++;
                } elseif (str_contains($dim, 'kritis') || str_contains($dim, 'nalar')) {
                    $dimensionCounts['Penalaran Kritis']++;
                } elseif (str_contains($dim, 'kreatif')) {
                    $dimensionCounts['Kreativitas']++;
                } elseif (str_contains($dim, 'kolaborasi') || str_contains($dim, 'gotong') || str_contains($dim, 'royong')) {
                    $dimensionCounts['Kolaborasi']++;
                } elseif (str_contains($dim, 'mandiri')) {
                    $dimensionCounts['Kemandirian']++;
                } elseif (str_contains($dim, 'kesehatan') || str_contains($dim, 'sehat') || str_contains($dim, 'raga')) {
                    $dimensionCounts['Kesehatan']++;
                } elseif (str_contains($dim, 'komunikasi') || str_contains($dim, 'tutur') || str_contains($dim, 'wicara')) {
                    $dimensionCounts['Komunikasi']++;
                } else {
                    $dimensionCounts['Keimanan & Ketakwaan']++;
                }
            }

            // 3. Kategori Kasus & Tingkat Keparahan
            $severityCounts = [
                'ringan' => (clone $caseBase)->where('severity', 'ringan')->count(),
                'sedang' => (clone $caseBase)->where('severity', 'sedang')->count(),
                'berat'  => (clone $caseBase)->where('severity', 'berat')->count(),
            ];

            $statusCounts = [
                'selesai' => (clone $caseBase)->where('status', 'selesai')->count(),
                'dalam_pembinaan' => (clone $caseBase)->where('status', 'dalam_pembinaan')->count(),
            ];

            // 4. Komparasi Antar Kelas (Rombel)
            $classComparison = [];
            $allClassList = Student::forYear($selectedYearId)->select('class')->distinct()->whereNotNull('class')->orderBy('class')->pluck('class');
            foreach ($allClassList as $cls) {
                $clsStudentCount = Student::forYear($selectedYearId)->where('class', $cls)->count();
                $clsApprCount = CharacterAppreciation::where('academic_year_id', $selectedYearId)->whereHas('student', fn($q) => $q->where('class', $cls))->count();
                $clsApprPoints = CharacterAppreciation::where('academic_year_id', $selectedYearId)->whereHas('student', fn($q) => $q->where('class', $cls))->sum('point');
                $clsCaseCount = CharacterCase::where('academic_year_id', $selectedYearId)->whereHas('student', fn($q) => $q->where('class', $cls))->count();
                $clsCasePoints = CharacterCase::where('academic_year_id', $selectedYearId)->whereHas('student', fn($q) => $q->where('class', $cls))->sum('point');

                $classComparison[] = [
                    'class' => $cls,
                    'students_count' => $clsStudentCount,
                    'appr_count' => $clsApprCount,
                    'appr_points' => $clsApprPoints,
                    'case_count' => $clsCaseCount,
                    'case_points' => $clsCasePoints,
                    'net_points' => $clsApprPoints - $clsCasePoints,
                ];
            }

            // 5. Sebaran Predikat Karakter Siswa
            $studentsQuery = Student::forYear($selectedYearId)
                ->when($selectedClass && $selectedClass !== 'ALL', fn($q) => $q->where('class', $selectedClass))
                ->where(function($q) {
                    $q->where('status', '!=', 'graduated')->orWhereNull('status');
                })
                ->with([
                    'characterCases' => fn($q) => $q->when($selectedYearId, fn($sub) => $sub->where('academic_year_id', $selectedYearId)),
                    'characterAppreciations' => fn($q) => $q->when($selectedYearId, fn($sub) => $sub->where('academic_year_id', $selectedYearId))
                ])
                ->get();

            $predicateCounts = [
                'Bintang Karakter SMILE' => 0,
                'Baik & Kondusif' => 0,
                'Dalam Pembinaan' => 0,
                'Perlu Pendampingan Intensif' => 0,
            ];

            foreach ($studentsQuery as $st) {
                $cCount = $st->characterCases->count();
                $aCount = $st->characterAppreciations->count();
                $unresolved = $st->characterCases->where('status', 'dalam_pembinaan')->count();
                $net = $st->characterAppreciations->sum('point') - $st->characterCases->sum('point');

                if ($cCount == 0 && $aCount >= 3) {
                    $predicateCounts['Bintang Karakter SMILE']++;
                } elseif ($unresolved > 2 || $net < -10) {
                    $predicateCounts['Perlu Pendampingan Intensif']++;
                } elseif ($cCount > 0 && $unresolved > 0) {
                    $predicateCounts['Dalam Pembinaan']++;
                } else {
                    $predicateCounts['Baik & Kondusif']++;
                }
            }

            return compact(
                'totalCases',
                'resolvedCases',
                'activeCases',
                'totalCasePoints',
                'totalAppreciations',
                'totalAppreciationPoints',
                'netPoints',
                'tppkCases',
                'resolveRate',
                'trendLabels',
                'trendCases',
                'trendAppreciations',
                'trendPoints',
                'tableData',
                'tableDataReversed',
                'dimensionLabels',
                'dimensionCounts',
                'severityCounts',
                'statusCounts',
                'classComparison',
                'predicateCounts'
            );
        });

        $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
        $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
        $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : "Nur'asiah, S.Pd.I");
        $headmasterNip = \App\Models\Setting::get('headmaster_nip', '141502002');

        return array_merge([
            'academicYears' => $academicYears,
            'academicYear' => $academicYear,
            'classes' => $classes,
            'selectedYearId' => $selectedYearId,
            'selectedClass' => $selectedClass,
            'period' => $period,
            'days' => $days,
            'headmasterName' => $headmasterName,
            'headmasterNip' => $headmasterNip
        ], $cachedMetrics);
    }

    public function analytics(Request $request)
    {
        $data = $this->buildAnalyticsData($request);
        return view('character.analytics', $data);
    }

    public function printAnalytics(Request $request)
    {
        $data = $this->buildAnalyticsData($request);
        return view('character.analytics_print', $data);
    }
}
