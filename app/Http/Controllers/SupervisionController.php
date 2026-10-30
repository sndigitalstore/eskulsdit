<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supervision;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Eskul;
use App\Models\TeacherAttendance;
use App\Models\CharacterAppreciation;
use App\Models\CharacterCase;
use App\Models\Achievement;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SupervisionController extends Controller
{
    /**
     * Memastikan tabel supervisions ada, jalankan migrasi jika belum ada.
     */
    protected function ensureMigration()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('supervisions')) {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            } catch (\Exception $e) {}
        }
    }

    /**
     * Executive Dashboard: Radar Mutu Sekolah & Supervisi
     */
    public function radar(Request $request)
    {
        $this->ensureMigration();

        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            abort(403, 'Akses terbatas untuk Kepala Sekolah, Kurikulum, dan Administrator.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $activeYear ? $activeYear->id : null;
        $semester = $activeYear ? $activeYear->active_semester : '1';

        // 1. Data Seluruh Guru Aktif
        $teachers = User::where('role', 'teacher')->activeYear()->orderBy('name')->get();
        $totalTeachers = $teachers->count();

        // 2. Data Supervisi pada Tahun & Semester Aktif
        $supervisionQuery = Supervision::query();
        if ($yearId) {
            $supervisionQuery->where('academic_year_id', $yearId)
                             ->where('semester', $semester);
        }
        $allSupervisions = $supervisionQuery->with(['teacher', 'supervisor'])->get();

        // Guru yang sudah disupervisi (unique teacher_id)
        $supervisedTeacherIds = $allSupervisions->pluck('teacher_id')->unique()->toArray();
        $totalSupervisedTeachers = count($supervisedTeacherIds);
        $supervisionCoverage = $totalTeachers > 0 ? round(($totalSupervisedTeachers / $totalTeachers) * 100, 1) : 0;

        // Rata-rata Skor Sekolah
        $avgScore = $allSupervisions->count() > 0 ? round($allSupervisions->avg('total_score'), 1) : 0;

        // Distribusi Predikat
        $predicateDistribution = [
            'Sangat Baik' => $allSupervisions->filter(fn($s) => str_contains($s->predicate, 'Sangat Baik'))->count(),
            'Baik' => $allSupervisions->filter(fn($s) => str_contains($s->predicate, 'Baik') && !str_contains($s->predicate, 'Sangat'))->count(),
            'Cukup' => $allSupervisions->filter(fn($s) => str_contains($s->predicate, 'Cukup'))->count(),
            'Perlu Pembinaan' => $allSupervisions->filter(fn($s) => str_contains($s->predicate, 'Perlu Pembinaan'))->count(),
        ];

        // Distribusi Jenis Supervisi
        $typeCounts = [
            'kbm' => $allSupervisions->where('supervision_type', 'kbm')->count(),
            'modul_ajar' => $allSupervisions->where('supervision_type', 'modul_ajar')->count(),
            'quran' => $allSupervisions->where('supervision_type', 'quran')->count(),
            'eskul' => $allSupervisions->where('supervision_type', 'eskul')->count(),
        ];

        // 3. Early Warning: Guru yang Belum Pernah Disupervisi Semester Ini
        $unsupervisedTeachers = $teachers->filter(function($t) use ($supervisedTeacherIds) {
            return !in_array($t->id, $supervisedTeacherIds);
        });

        // 4. Radar Kepatuhan Guru / Pembina Eskul
        $coachAttendances = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('teacher_attendances') && $yearId) {
            $coachQuery = TeacherAttendance::where('academic_year_id', $yearId);
            if (\Illuminate\Support\Facades\Schema::hasColumn('teacher_attendances', 'semester')) {
                $coachQuery->where('semester', $semester);
            } elseif ($semester == '1') {
                $coachQuery->whereMonth('date', '>=', 7);
            } elseif ($semester == '2') {
                $coachQuery->whereMonth('date', '<=', 6);
            }
            $coachAttendances = $coachQuery->get();
        }
        $totalCoachSessions = $coachAttendances->count();
        $presentCoachSessions = $coachAttendances->whereIn('status', ['present', 'substitute'])->count();
        $coachComplianceRate = $totalCoachSessions > 0 ? round(($presentCoachSessions / $totalCoachSessions) * 100, 1) : 100;

        // 5. Radar Karakter & Prestasi Siswa
        $characterAppreciationsCount = 0;
        $characterCasesCount = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('character_appreciations') && $yearId) {
            $characterAppreciationsCount = CharacterAppreciation::where('academic_year_id', $yearId)->count();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('character_cases') && $yearId) {
            $characterCasesCount = CharacterCase::where('academic_year_id', $yearId)->count();
        }

        $achievementsCount = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('achievements') && $yearId) {
            $achievementsCount = Achievement::where('academic_year_id', $yearId)->count();
        }

        // 6. Riwayat Supervisi Terbaru (5 data terakhir)
        $recentSupervisions = Supervision::with(['teacher', 'supervisor'])
            ->orderBy('supervision_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        return view('supervisions.radar', compact(
            'activeYear',
            'totalTeachers',
            'totalSupervisedTeachers',
            'supervisionCoverage',
            'avgScore',
            'predicateDistribution',
            'typeCounts',
            'unsupervisedTeachers',
            'coachComplianceRate',
            'characterAppreciationsCount',
            'characterCasesCount',
            'achievementsCount',
            'recentSupervisions'
        ));
    }

    /**
     * Daftar Riwayat Supervisi Guru
     */
    public function index(Request $request)
    {
        $this->ensureMigration();

        $user = auth()->user();
        $isTeacherOnly = $user->role === 'teacher';

        $activeYear = AcademicYear::where('is_active', true)->first();
        $allYears = AcademicYear::orderBy('id', 'desc')->get();
        $teachers = User::where('role', 'teacher')->activeYear()->orderBy('name')->get();

        $query = Supervision::with(['teacher', 'supervisor', 'academicYear']);

        // Jika login sebagai guru biasa, hanya bisa melihat hasil supervisi miliknya
        if ($isTeacherOnly) {
            $query->where('teacher_id', $user->id);
        }

        // Filter Tahun Ajaran
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        } elseif ($activeYear && !$request->has('all_years')) {
            $query->where('academic_year_id', $activeYear->id);
        }

        // Filter Semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter Guru
        if ($request->filled('teacher_id') && !$isTeacherOnly) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter Tipe Supervisi
        if ($request->filled('supervision_type')) {
            $query->where('supervision_type', $request->supervision_type);
        }

        // Filter Predikat
        if ($request->filled('predicate')) {
            $query->where('predicate', 'like', '%' . $request->predicate . '%');
        }

        // Search Keyword
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->whereHas('teacher', function($qt) use ($s) {
                    $qt->where('name', 'like', "%{$s}%");
                })->orWhere('class_name', 'like', "%{$s}%")
                  ->orWhere('subject_name', 'like', "%{$s}%")
                  ->orWhere('strengths', 'like', "%{$s}%")
                  ->orWhere('recommendations', 'like', "%{$s}%");
            });
        }

        $supervisions = $query->orderBy('supervision_date', 'desc')
                              ->orderBy('id', 'desc')
                              ->paginate(15)
                              ->withQueryString();

        return view('supervisions.index', compact(
            'supervisions',
            'activeYear',
            'allYears',
            'teachers',
            'isTeacherOnly'
        ));
    }

    /**
     * Form Input Supervisi Baru
     */
    public function create(Request $request)
    {
        $this->ensureMigration();

        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            abort(403, 'Akses terbatas untuk Kepala Sekolah, Kurikulum, dan Administrator.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        $teachers = User::where('role', 'teacher')->activeYear()->orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $eskuls = Eskul::activeYear()->orderBy('name')->get();

        $selectedTeacherId = $request->query('teacher_id');
        $defaultType = $request->query('type', 'kbm');

        // Seluruh definisi rubrik untuk dynamic switching via JS
        $rubrics = [
            'kbm' => Supervision::getRubricDefinition('kbm'),
            'modul_ajar' => Supervision::getRubricDefinition('modul_ajar'),
            'quran' => Supervision::getRubricDefinition('quran'),
            'eskul' => Supervision::getRubricDefinition('eskul'),
        ];

        return view('supervisions.create', compact(
            'activeYear',
            'teachers',
            'classes',
            'subjects',
            'eskuls',
            'selectedTeacherId',
            'defaultType',
            'rubrics'
        ));
    }

    /**
     * Simpan Hasil Supervisi
     */
    public function store(Request $request)
    {
        $this->ensureMigration();

        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'supervision_type' => 'required|in:kbm,modul_ajar,quran,eskul',
            'supervision_date' => 'required|date',
            'class_name' => 'nullable|string|max:50',
            'subject_name' => 'nullable|string|max:120',
            'scores' => 'required|array',
            'strengths' => 'nullable|string',
            'improvements' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $type = $request->supervision_type;
        $rubric = Supervision::getRubricDefinition($type);
        $aspects = $rubric['aspects'];

        $aspectsScore = [];
        $totalPoints = 0;
        $maxPoints = count($aspects) * 4;

        foreach ($aspects as $aspect) {
            $scoreVal = isset($request->scores[$aspect['id']]) ? (int)$request->scores[$aspect['id']] : 3;
            $aspectsScore[$aspect['id']] = [
                'title' => $aspect['title'],
                'score' => $scoreVal,
                'desc' => $aspect['desc'],
            ];
            $totalPoints += $scoreVal;
        }

        $totalScore = $maxPoints > 0 ? round(($totalPoints / $maxPoints) * 100, 2) : 0;

        if ($totalScore >= 91) {
            $predicate = 'Sangat Baik (A)';
        } elseif ($totalScore >= 81) {
            $predicate = 'Baik (B)';
        } elseif ($totalScore >= 71) {
            $predicate = 'Cukup (C)';
        } else {
            $predicate = 'Perlu Pembinaan (D)';
        }

        $supervision = Supervision::create([
            'academic_year_id' => $activeYear ? $activeYear->id : null,
            'semester' => $activeYear ? $activeYear->active_semester : '1',
            'supervisor_id' => $user->id,
            'teacher_id' => $request->teacher_id,
            'supervision_type' => $type,
            'supervision_date' => $request->supervision_date,
            'class_name' => $request->class_name,
            'subject_name' => $request->subject_name,
            'aspects_score' => $aspectsScore,
            'total_score' => $totalScore,
            'predicate' => $predicate,
            'strengths' => $request->strengths,
            'improvements' => $request->improvements,
            'recommendations' => $request->recommendations,
            'follow_up_date' => $request->follow_up_date,
            'status' => 'completed',
        ]);

        return redirect()->route('supervisions.show', $supervision->id)
                         ->with('success', 'Instrumen supervisi berhasil disimpan dengan skor ' . $totalScore . ' [' . $predicate . ']');
    }

    /**
     * Tampilkan Detail Lembar Supervisi
     */
    public function show($id)
    {
        $this->ensureMigration();

        $supervision = Supervision::with(['teacher', 'supervisor', 'academicYear'])->findOrFail($id);

        // Keamanan akses: jika guru biasa, hanya boleh melihat supervisi miliknya
        $user = auth()->user();
        if ($user->role === 'teacher' && $supervision->teacher_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('supervisions.show', compact('supervision'));
    }

    /**
     * Form Edit Supervisi
     */
    public function edit($id)
    {
        $this->ensureMigration();

        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            abort(403, 'Akses ditolak.');
        }

        $supervision = Supervision::findOrFail($id);
        $activeYear = AcademicYear::where('is_active', true)->first();
        $teachers = User::where('role', 'teacher')->activeYear()->orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $eskuls = Eskul::activeYear()->orderBy('name')->get();

        $rubrics = [
            'kbm' => Supervision::getRubricDefinition('kbm'),
            'modul_ajar' => Supervision::getRubricDefinition('modul_ajar'),
            'quran' => Supervision::getRubricDefinition('quran'),
            'eskul' => Supervision::getRubricDefinition('eskul'),
        ];

        return view('supervisions.edit', compact(
            'supervision',
            'activeYear',
            'teachers',
            'classes',
            'subjects',
            'eskuls',
            'rubrics'
        ));
    }

    /**
     * Update Data Supervisi
     */
    public function update(Request $request, $id)
    {
        $this->ensureMigration();

        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            abort(403, 'Akses ditolak.');
        }

        $supervision = Supervision::findOrFail($id);

        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'supervision_type' => 'required|in:kbm,modul_ajar,quran,eskul',
            'supervision_date' => 'required|date',
            'class_name' => 'nullable|string|max:50',
            'subject_name' => 'nullable|string|max:120',
            'scores' => 'required|array',
            'strengths' => 'nullable|string',
            'improvements' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        $type = $request->supervision_type;
        $rubric = Supervision::getRubricDefinition($type);
        $aspects = $rubric['aspects'];

        $aspectsScore = [];
        $totalPoints = 0;
        $maxPoints = count($aspects) * 4;

        foreach ($aspects as $aspect) {
            $scoreVal = isset($request->scores[$aspect['id']]) ? (int)$request->scores[$aspect['id']] : 3;
            $aspectsScore[$aspect['id']] = [
                'title' => $aspect['title'],
                'score' => $scoreVal,
                'desc' => $aspect['desc'],
            ];
            $totalPoints += $scoreVal;
        }

        $totalScore = $maxPoints > 0 ? round(($totalPoints / $maxPoints) * 100, 2) : 0;

        if ($totalScore >= 91) {
            $predicate = 'Sangat Baik (A)';
        } elseif ($totalScore >= 81) {
            $predicate = 'Baik (B)';
        } elseif ($totalScore >= 71) {
            $predicate = 'Cukup (C)';
        } else {
            $predicate = 'Perlu Pembinaan (D)';
        }

        $supervision->update([
            'teacher_id' => $request->teacher_id,
            'supervision_type' => $type,
            'supervision_date' => $request->supervision_date,
            'class_name' => $request->class_name,
            'subject_name' => $request->subject_name,
            'aspects_score' => $aspectsScore,
            'total_score' => $totalScore,
            'predicate' => $predicate,
            'strengths' => $request->strengths,
            'improvements' => $request->improvements,
            'recommendations' => $request->recommendations,
            'follow_up_date' => $request->follow_up_date,
        ]);

        return redirect()->route('supervisions.show', $supervision->id)
                         ->with('success', 'Data supervisi berhasil diperbarui.');
    }

    /**
     * Hapus Data Supervisi
     */
    public function destroy($id)
    {
        $this->ensureMigration();

        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'headmaster'])) {
            abort(403, 'Akses ditolak.');
        }

        $supervision = Supervision::findOrFail($id);
        $supervision->delete();

        return redirect()->route('supervisions.index')
                         ->with('success', 'Data supervisi berhasil dihapus.');
    }

    /**
     * Cetak Lembar Berita Acara & Hasil Supervisi Resmi
     */
    public function print($id)
    {
        $this->ensureMigration();

        $supervision = Supervision::with(['teacher', 'supervisor', 'academicYear'])->findOrFail($id);

        $user = auth()->user();
        if ($user->role === 'teacher' && $supervision->teacher_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('supervisions.print', compact('supervision'));
    }
}

