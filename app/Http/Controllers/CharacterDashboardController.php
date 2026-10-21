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
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user() && Auth::user()->role === 'curriculum') {
                abort(403, 'Akses modul pemantauan karakter khusus untuk Kesiswaan dan Guru.');
            }
            return $next($request);
        });
    }

    protected function ensureTablesExist()
    {
        if (!Schema::hasTable('character_cases') || !Schema::hasTable('character_appreciations')) {
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
        if ($user->role === 'teacher' && !empty($user->homeroom_class) && !$selectedClass) {
            $selectedClass = $user->homeroom_class;
        }

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $classes = Student::forYear($selectedYearId)
            ->select('class')->distinct()->orderBy('class')->pluck('class');

        // Scopes
        $caseBase = CharacterCase::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($selectedClass && $selectedClass !== 'ALL', fn($q) => $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass)));

        $apprBase = CharacterAppreciation::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($selectedClass && $selectedClass !== 'ALL', fn($q) => $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass)));

        // Stat cards
        $totalCases = (clone $caseBase)->count();
        $resolvedCases = (clone $caseBase)->where('status', 'selesai')->count();
        $activeCases = (clone $caseBase)->where('status', 'dalam_pembinaan')->count();

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
        if ($user->role === 'teacher' && !empty($user->homeroom_class) && !$selectedClass) {
            $selectedClass = $user->homeroom_class;
        }

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $classes = Student::forYear($selectedYearId)
            ->select('class')->distinct()->orderBy('class')->pluck('class');

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
}
