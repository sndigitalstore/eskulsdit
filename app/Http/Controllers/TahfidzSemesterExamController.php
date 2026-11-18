<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\MentoringGroup;
use App\Models\Setting;
use App\Models\Student;
use App\Models\TahfidzRecord;
use App\Models\TahfidzSemesterExam;
use App\Models\TahfidzSemesterExamItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class TahfidzSemesterExamController extends Controller
{
    public function __construct()
    {
        $this->ensureTablesExist();
    }

    /**
     * Self-healing: memastikan tabel tahfidz_semester_exams selalu siap
     */
    protected function ensureTablesExist(): void
    {
        if (!Schema::hasTable('tahfidz_semester_exams')) {
            Schema::create('tahfidz_semester_exams', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('mentoring_group_id')->constrained('mentoring_groups')->cascadeOnDelete();
                $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
                $table->enum('semester', ['1', '2'])->default('1');
                $table->date('exam_date');
                $table->foreignId('examiner_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('examiner_name')->nullable();
                $table->decimal('average_score', 5, 2)->default(0);
                $table->string('predicate')->default('Jayyid');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->unique(['student_id', 'academic_year_id', 'semester'], 'std_year_sem_unique');
                $table->index(['mentoring_group_id', 'academic_year_id', 'semester'], 'grp_year_sem_idx');
            });
        }

        if (!Schema::hasTable('tahfidz_semester_exam_items')) {
            Schema::create('tahfidz_semester_exam_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tahfidz_semester_exam_id')->constrained('tahfidz_semester_exams')->cascadeOnDelete();
                $table->string('surah_name');
                $table->unsignedSmallInteger('juz')->nullable();
                $table->decimal('score', 5, 2)->default(0);
                $table->string('predicate')->nullable();
                $table->string('notes')->nullable();
                $table->unsignedSmallInteger('order_num')->default(1);
                $table->timestamps();

                $table->index('tahfidz_semester_exam_id', 'exam_items_exam_id_idx');
            });
        }
    }

    /**
     * Bersihkan nama surat dari embel-embel teks seperti ayat, tasmi, dsb.
     */
    public static function cleanSurahName(?string $raw): string
    {
        if (!$raw || trim($raw) === '') {
            return '';
        }
        $clean = trim($raw);
        $clean = preg_replace('/^(?:QS\.?|Surat|Surah)\s+/i', '', $clean);
        $clean = preg_replace('/\s*(?:ayat\s*tasmi|tasmi|\(juz\s*\d+\)|juz\s*\d+|ayat\s*\d+.*|:\s*\d+.*).*/i', '', $clean);
        return trim($clean, " :-,()'");
    }

    /**
     * Dapatkan mapping seluruh 114 surat ke nomor Juz-nya
     */
    public static function getSurahToJuzMap(): array
    {
        $allSurahs = TahfidzAttendanceController::getAllSurahNames();
        $map = [];
        foreach ($allSurahs as $surah) {
            $map[$surah] = TahfidzAttendanceController::determineJuzForSurah($surah);
        }
        return $map;
    }

    /**
     * Halaman Utama: Form Penilaian Ujian Hafalan Semester Halaqah
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdminOrPrivileged = in_array($user->role, ['admin', 'headmaster', 'curriculum']);

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = (int)($request->query('year_id') ?? ($activeYear?->id ?? 1));
        $yearObj = AcademicYear::find($selectedYearId) ?? $activeYear;

        // Semester: '1' atau '2'
        $selectedSemester = (string)($request->query('semester') ?? ($activeYear?->active_semester ?? '1'));
        if (!in_array($selectedSemester, ['1', '2'])) {
            $selectedSemester = '1';
        }

        // Tentukan rentang tanggal semester
        $periodInfo = ReportController::resolveTahfidzPeriod('semester_' . $selectedSemester, null, $selectedYearId);
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];

        // Kelompok Tahfidz
        if ($isAdminOrPrivileged) {
            $myGroups = MentoringGroup::where('type', 'tahfidz')
                ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
                ->orderBy('name')
                ->get();
        } else {
            $myGroups = MentoringGroup::where('type', 'tahfidz')
                ->where(function($q) use ($user) {
                    $q->where('mentor_user_id', $user->id)
                      ->orWhere('mentor_name', $user->name);
                })
                ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
                ->orderBy('name')
                ->get();
        }

        if ($myGroups->isEmpty()) {
            return view('tahfidz.semester_exam.no_group', compact('myGroups', 'academicYears', 'selectedYearId', 'selectedSemester'));
        }

        $selectedGroupId = (int)($request->query('group_id') ?? $myGroups->first()->id);
        $activeGroup = $myGroups->firstWhere('id', $selectedGroupId) ?? $myGroups->first();

        // Santri dalam kelompok ini
        $students = $activeGroup->students()->orderBy('class')->orderBy('name')->get();
        $studentIds = $students->pluck('id');

        // Ambil data ujian yang sudah tersimpan untuk semester & tahun ini
        $existingExams = TahfidzSemesterExam::with('items')
            ->whereIn('student_id', $studentIds)
            ->where('mentoring_group_id', $activeGroup->id)
            ->where('academic_year_id', $selectedYearId)
            ->where('semester', $selectedSemester)
            ->get()
            ->keyBy('student_id');

        // Ambil data ujian Semester 1 jika sedang di Semester 2 (agar riwayat tuntas tetap ada & tidak diuji ulang)
        $sem1Exams = collect();
        if ($selectedSemester === '2') {
            $sem1Exams = TahfidzSemesterExam::with('items')
                ->whereIn('student_id', $studentIds)
                ->where('academic_year_id', $selectedYearId)
                ->where('semester', '1')
                ->get()
                ->keyBy('student_id');
        }

        // Ambil riwayat setoran siswa di semester ini untuk AUTO-POPULATE surat tuntas
        $semesterRecords = TahfidzRecord::whereIn('student_id', $studentIds)
            ->where('mentoring_group_id', $activeGroup->id)
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->whereIn('quality', ['lancar', 'cukup'])
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('session_date', [$startDate, $endDate]))
            ->orderBy('session_date', 'asc')
            ->get()
            ->groupBy('student_id');

        // Mapping data per siswa: jika belum ada ujian, pre-populate surat yang telah disetor
        $studentsExamData = [];
        $totalEvaluated = 0;
        $totalScores = [];

        foreach ($students as $std) {
            $savedExam = $existingExams->get($std->id);
            $itemsList = [];
            $isSaved = false;

            // Ekstrak riwayat ujian Semester 1 jika ada (untuk ditampilkan sebagai riwayat tuntas permanen)
            $sem1ItemsList = [];
            $sem1SurahKeys = [];
            $stdSem1Exam = $sem1Exams->get($std->id);
            if ($stdSem1Exam && $stdSem1Exam->items->isNotEmpty()) {
                foreach ($stdSem1Exam->items as $s1It) {
                    $cleanSurah = self::cleanSurahName($s1It->surah_name);
                    $sem1ItemsList[] = [
                        'surah_name' => $cleanSurah ?: $s1It->surah_name,
                        'juz'        => $s1It->juz ?? TahfidzAttendanceController::determineJuzForSurah($cleanSurah ?: $s1It->surah_name),
                        'score'      => (float)$s1It->score,
                        'predicate'  => $s1It->predicate ?? TahfidzSemesterExam::resolvePredicate((float)$s1It->score),
                        'notes'      => $s1It->notes ?? '',
                    ];
                    $normKey = strtolower(preg_replace('/[^a-z0-9]/', '', $cleanSurah ?: $s1It->surah_name));
                    $sem1SurahKeys[$normKey] = true;
                }
            }

            if ($savedExam && $savedExam->items->isNotEmpty()) {
                $isSaved = true;
                $totalEvaluated++;
                if ($savedExam->average_score > 0) {
                    $totalScores[] = (float)$savedExam->average_score;
                }
                foreach ($savedExam->items as $it) {
                    $cleanName = self::cleanSurahName($it->surah_name);
                    $itemsList[] = [
                        'surah_name' => $cleanName ?: $it->surah_name,
                        'juz'        => $it->juz ?? TahfidzAttendanceController::determineJuzForSurah($cleanName ?: $it->surah_name),
                        'score'      => (float)$it->score,
                        'predicate'  => $it->predicate ?? TahfidzSemesterExam::resolvePredicate((float)$it->score),
                        'notes'      => $it->notes ?? '',
                    ];
                }
            } else {
                // Auto-populate dari histori setoran santri di semester ini
                $recs = $semesterRecords->get($std->id, collect());

                // Jika kosong pada rentang tanggal semester, ambil riwayat setoran siswa di kelompok ini
                if ($recs->isEmpty()) {
                    $recs = TahfidzRecord::where('student_id', $std->id)
                        ->where('mentoring_group_id', $activeGroup->id)
                        ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
                        ->whereIn('quality', ['lancar', 'cukup'])
                        ->orderBy('session_date', 'asc')
                        ->get();
                }

                $groupedSurahs = [];
                foreach ($recs as $rec) {
                    $cleanName = self::cleanSurahName($rec->surah_name);
                    if (empty($cleanName)) continue;

                    // JIKA SEMESTER 2: Lewati surat yang SUDAH tuntas diujikan di Semester 1!
                    if ($selectedSemester === '2') {
                        $normKey = strtolower(preg_replace('/[^a-z0-9]/', '', $cleanName));
                        if (isset($sem1SurahKeys[$normKey])) {
                            continue; // Sudah tuntas di semester 1, fokus ke surat baru
                        }
                    }

                    if (!isset($groupedSurahs[$cleanName])) {
                        $groupedSurahs[$cleanName] = [
                            'scores' => [],
                            'juz'    => $rec->juz ?? TahfidzAttendanceController::determineJuzForSurah($cleanName),
                        ];
                    }
                    if ($rec->nilai) {
                        $groupedSurahs[$cleanName]['scores'][] = (float)$rec->nilai;
                    }
                }

                foreach ($groupedSurahs as $surahName => $sData) {
                    $avgScore = count($sData['scores']) > 0 ? round(array_sum($sData['scores']) / count($sData['scores'])) : 85;
                    $itemsList[] = [
                        'surah_name' => $surahName,
                        'juz'        => $sData['juz'] ?? TahfidzAttendanceController::determineJuzForSurah($surahName),
                        'score'      => $avgScore,
                        'predicate'  => TahfidzSemesterExam::resolvePredicate((float)$avgScore),
                        'notes'      => '',
                    ];
                }
            }

            $currentAvg = $savedExam ? (float)$savedExam->average_score : (count($itemsList) > 0 ? round(array_sum(array_column($itemsList, 'score')) / count($itemsList), 2) : 0);
            $currentPredicate = $savedExam ? $savedExam->predicate : TahfidzSemesterExam::resolvePredicate($currentAvg);

            $studentsExamData[$std->id] = [
                'student'           => $std,
                'exam_id'           => $savedExam?->id,
                'is_saved'          => $isSaved,
                'exam_date'         => $savedExam?->exam_date?->format('Y-m-d') ?? date('Y-m-d'),
                'average_score'     => $currentAvg,
                'predicate'         => $currentPredicate,
                'notes'             => $savedExam?->notes ?? '',
                'items'             => $itemsList,
                'sem1_items'        => $sem1ItemsList,
                'history_count'     => $semesterRecords->get($std->id, collect())->count(),
            ];
        }

        $allSurahs = TahfidzAttendanceController::getAllSurahNames();
        $surahToJuzMap = self::getSurahToJuzMap();
        $groupAverage = count($totalScores) > 0 ? round(array_sum($totalScores) / count($totalScores), 2) : 0;

        return view('tahfidz.semester_exam.index', compact(
            'myGroups', 'activeGroup', 'academicYears', 'selectedYearId', 'yearObj',
            'selectedSemester', 'students', 'studentsExamData', 'allSurahs', 'surahToJuzMap',
            'totalEvaluated', 'groupAverage', 'isAdminOrPrivileged', 'startDate', 'endDate'
        ));
    }

    /**
     * Simpan Hasil Ujian Semester (Mendukung Simpan Sekaligus atau Per Siswa AJAX)
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:mentoring_groups,id',
            'year_id'  => 'required|exists:academic_years,id',
            'semester' => 'required|in:1,2',
            'students' => 'required|array',
        ]);

        $user = Auth::user();
        $group = MentoringGroup::findOrFail($request->group_id);
        $yearId = (int)$request->year_id;
        $semester = (string)$request->semester;

        // Validasi hak akses guru
        $isAdminOrPrivileged = in_array($user->role, ['admin', 'headmaster', 'curriculum']);
        if (!$isAdminOrPrivileged) {
            $isMyGroup = ($group->mentor_user_id == $user->id || $group->mentor_name == $user->name);
            if (!$isMyGroup) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Akses ditolak: Ini bukan kelompok binaan Anda.'], 403);
                }
                return back()->with('error', 'Akses ditolak: Ini bukan kelompok binaan Anda.');
            }
        }

        $examinerName = $group->mentorUser?->name ?? $group->mentor_name ?? $user->name;

        DB::beginTransaction();
        try {
            $savedCount = 0;

            foreach ($request->students as $studentId => $payload) {
                $items = $payload['items'] ?? [];
                if (empty($items)) {
                    // Jika tidak ada surat sama sekali, lewati atau hapus jika sengaja dikosongkan
                    continue;
                }

                $examDate = !empty($payload['exam_date']) ? Carbon::parse($payload['exam_date']) : Carbon::now();
                $notes = $payload['notes'] ?? null;

                // Hitung rata-rata skor
                $scores = [];
                $validItems = [];
                $order = 1;

                foreach ($items as $it) {
                    $surah = self::cleanSurahName($it['surah_name'] ?? '');
                    if (empty($surah)) continue;

                    $score = min(100, max(0, (float)($it['score'] ?? 0)));
                    $scores[] = $score;
                    
                    // Otomatis tentukan nomor Juz jika kosong atau tidak diinputkan guru
                    $juz = !empty($it['juz']) ? (int)$it['juz'] : TahfidzAttendanceController::determineJuzForSurah($surah);

                    $validItems[] = [
                        'surah_name' => $surah,
                        'juz'        => $juz,
                        'score'      => $score,
                        'predicate'  => TahfidzSemesterExam::resolvePredicate($score),
                        'notes'      => $it['notes'] ?? null,
                        'order_num'  => $order++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (empty($validItems)) {
                    continue;
                }

                $avgScore = count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : 0;
                $predicate = TahfidzSemesterExam::resolvePredicate($avgScore);

                // Update or Create Exam Header
                $exam = TahfidzSemesterExam::updateOrCreate(
                    [
                        'student_id'         => $studentId,
                        'academic_year_id'   => $yearId,
                        'semester'           => $semester,
                    ],
                    [
                        'mentoring_group_id' => $group->id,
                        'exam_date'          => $examDate->format('Y-m-d'),
                        'examiner_user_id'   => $user->id,
                        'examiner_name'      => $examinerName,
                        'average_score'      => $avgScore,
                        'predicate'          => $predicate,
                        'notes'              => $notes,
                    ]
                );

                // Sinkronisasi items (hapus lama, insert baru)
                $exam->items()->delete();
                foreach ($validItems as &$v) {
                    $v['tahfidz_semester_exam_id'] = $exam->id;
                }
                TahfidzSemesterExamItem::insert($validItems);

                $savedCount++;
            }

            DB::commit();

            ActivityLog::log(
                'Tahfidz',
                'Ujian Semester',
                "Menyimpan nilai Ujian Hafalan Semester {$semester} untuk kelompok {$group->name} ({$savedCount} siswa dievaluasi)."
            );

            if ($request->ajax()) {
                $firstStudentId = array_key_first($request->students);
                $singleData = null;
                if (count($request->students) === 1 && $firstStudentId) {
                    $exam = TahfidzSemesterExam::where('student_id', $firstStudentId)
                        ->where('academic_year_id', $yearId)
                        ->where('semester', $semester)
                        ->with('items')
                        ->first();
                    if ($exam) {
                        $singleData = [
                            'student_id'    => (int)$firstStudentId,
                            'average_score' => number_format($exam->average_score, 1),
                            'predicate'     => $exam->predicate,
                            'items_count'   => $exam->items->count(),
                        ];
                    }
                }

                return response()->json([
                    'success'     => true,
                    'message'     => "Nilai Ujian Semester {$semester} berhasil disimpan untuk {$savedCount} santri!",
                    'saved_count' => $savedCount,
                    'student'     => $singleData,
                ]);
            }

            return back()->with('success', "Alhamdulillah! Nilai Ujian Hafalan Semester {$semester} berhasil disimpan untuk {$savedCount} santri.");

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Cetak Rekap Resmi Hasil Ujian Hafalan Semester (A4 Landscape)
     */
    public function printRecap(Request $request)
    {
        $groupId = (int)$request->query('group_id');
        $yearId = (int)$request->query('year_id');
        $semester = (string)($request->query('semester') ?? '1');

        $group = MentoringGroup::findOrFail($groupId);
        $academicYear = AcademicYear::findOrFail($yearId);

        $students = $group->students()->orderBy('class')->orderBy('name')->get();
        $studentIds = $students->pluck('id');

        $exams = TahfidzSemesterExam::with('items')
            ->whereIn('student_id', $studentIds)
            ->where('mentoring_group_id', $group->id)
            ->where('academic_year_id', $yearId)
            ->where('semester', $semester)
            ->get()
            ->keyBy('student_id');

        $sem1Exams = collect();
        if ($semester === '2') {
            $sem1Exams = TahfidzSemesterExam::with('items')
                ->whereIn('student_id', $studentIds)
                ->where('academic_year_id', $yearId)
                ->where('semester', '1')
                ->get()
                ->keyBy('student_id');
        }

        $headmasterName = Setting::get('headmaster_name', "Nur'asiah, S.Pd.I");
        $headmasterNip = Setting::get('headmaster_nip', '141502002');
        $currentDate = Carbon::now()->isoFormat('D MMMM Y');
        $mentorName = $group->mentorUser?->name ?? $group->mentor_name ?? '-';

        return view('tahfidz.semester_exam.print', compact(
            'group', 'academicYear', 'semester', 'students', 'exams', 'sem1Exams',
            'headmasterName', 'headmasterNip', 'mentorName', 'currentDate'
        ));
    }
}
