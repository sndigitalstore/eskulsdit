<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\CharacterAppreciation;
use App\Models\CharacterCase;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class CharacterDashboardController extends Controller
{
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

        return view('character.dashboard', compact(
            'academicYears',
            'classes',
            'selectedYearId',
            'selectedClass',
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
        ));
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
}
