<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\MentoringGroup;
use App\Models\Student;
use App\Models\TasmiExam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class TasmiExamController extends Controller
{
    public function __construct()
    {
        $this->ensureTableExists();
    }

    /**
     * Self-healing: memastikan tabel tasmi_exams selalu siap
     */
    protected function ensureTableExists(): void
    {
        if (!Schema::hasTable('tasmi_exams')) {
            Schema::create('tasmi_exams', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->foreignId('mentoring_group_id')->nullable()->constrained('mentoring_groups')->nullOnDelete();
                $table->enum('category', ['setengah_juz', 'satu_juz', 'dua_juz', 'tiga_juz'])->default('satu_juz');
                $table->string('juz_tested')->default('Juz 30');
                $table->date('exam_date');
                $table->string('examiner_name');
                $table->foreignId('examiner_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->unsignedSmallInteger('mistakes_fath')->default(0);
                $table->unsignedSmallInteger('mistakes_waqaf')->default(0);
                $table->unsignedSmallInteger('mistakes_tajwid')->default(0);
                $table->unsignedSmallInteger('total_mistakes')->default(0);
                $table->decimal('final_score', 5, 2)->default(100.00);
                $table->string('predicate')->default('Mumtaz');
                $table->enum('status', ['passed', 'retest'])->default('passed');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['student_id', 'academic_year_id']);
                $table->index(['exam_date']);
            });
        }
    }

    /**
     * Halaman Utama: Daftar & Monitoring Ujian Tasmi' Sekali Duduk
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->input('year_id', $activeYear?->id);
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $statusFilter = $request->input('status');
        $search = $request->input('search');

        $query = TasmiExam::with(['student', 'academicYear', 'mentoringGroup', 'examinerUser'])
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($statusFilter, fn($q) => $q->where('status', $statusFilter))
            ->when($search, function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                       ->orWhere('class', 'like', "%{$search}%")
                       ->orWhere('nis', 'like', "%{$search}%");
                });
            })
            ->orderBy('exam_date', 'desc')
            ->orderBy('id', 'desc');

        $exams = $query->paginate(15)->withQueryString();

        // Statistik Tasmi'
        $allStatsQuery = TasmiExam::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId));
        $totalExams = (clone $allStatsQuery)->count();
        $passedCount = (clone $allStatsQuery)->where('status', 'passed')->count();
        $mumtazCount = (clone $allStatsQuery)->where('predicate', 'like', '%Mumtaz%')->count();
        $retestCount = (clone $allStatsQuery)->where('status', 'retest')->count();

        // Data Siswa Aktif untuk Modal Form Pendaftaran Ujian
        $students = Student::forYear($selectedYearId)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')->orWhereNull('status');
            })
            ->orderBy('class')
            ->orderBy('name')
            ->get(['id', 'name', 'class', 'nis']);

        $tahfidzGroups = MentoringGroup::where('type', 'tahfidz')
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->orderBy('name')
            ->get();

        $examiners = User::whereIn('role', ['teacher', 'admin'])->orderBy('name')->get();

        return view('tahfidz.tasmi.index', compact(
            'exams', 'academicYears', 'selectedYearId', 'activeYear',
            'totalExams', 'passedCount', 'mumtazCount', 'retestCount',
            'students', 'tahfidzGroups', 'examiners', 'statusFilter', 'search'
        ));
    }

    /**
     * Simpan Data Hasil Ujian Tasmi'
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'         => 'required|exists:students,id',
            'mentoring_group_id' => 'nullable|exists:mentoring_groups,id',
            'academic_year_id'   => 'required|exists:academic_years,id',
            'category'           => 'required|in:setengah_juz,satu_juz,dua_juz,tiga_juz',
            'juz_tested'         => 'required|string|max:50',
            'exam_date'          => 'required|date',
            'examiner_name'      => 'required|string|max:100',
            'examiner_user_id'   => 'nullable|exists:users,id',
            'mistakes_fath'      => 'required|integer|min:0|max:50',
            'mistakes_waqaf'     => 'required|integer|min:0|max:50',
            'mistakes_tajwid'    => 'required|integer|min:0|max:50',
            'notes'              => 'nullable|string|max:1000',
        ]);

        $fath = (int)$validated['mistakes_fath'];
        $waqaf = (int)$validated['mistakes_waqaf'];
        $tajwid = (int)$validated['mistakes_tajwid'];
        $totalMistakes = $fath + $waqaf + $tajwid;

        // Formula Skor Penilaian Tasmi' Resmi:
        // Bobot: Fath (salah fatal/ditebak) = -2.0 | Tajwid = -1.0 | Waqaf = -0.5
        $calculatedScore = 100.0 - (($fath * 2.0) + ($tajwid * 1.0) + ($waqaf * 0.5));
        $finalScore = max(60.0, round($calculatedScore, 2));

        // Penentuan Predikat Sesuai Standar JSIT / LPTQ
        if ($totalMistakes <= 3) {
            $predicate = 'Mumtaz (Istimewa / Sangat Lancar)';
            $status = 'passed';
        } elseif ($totalMistakes <= 7) {
            $predicate = 'Jayyid Jiddan (Amat Baik)';
            $status = 'passed';
        } elseif ($totalMistakes <= 12) {
            $predicate = 'Jayyid (Baik)';
            $status = 'passed';
        } else {
            $predicate = 'Maqbul (Perlu Penguatan & Uji Ulang)';
            $status = 'retest';
        }

        $exam = TasmiExam::create([
            ...$validated,
            'total_mistakes' => $totalMistakes,
            'final_score'    => $finalScore,
            'predicate'      => $predicate,
            'status'         => $status,
        ]);

        ActivityLog::log('Tahfidz', 'Tasmi', "Mencatat Ujian Tasmi' {$exam->category_label} ({$exam->juz_tested}) untuk siswa ID {$exam->student_id} dengan skor {$finalScore}");

        return redirect()->route('tasmi.index')->with('success', "Hasil Ujian Tasmi' berhasil dicatat! Predikat: {$predicate} (Skor: {$finalScore})");
    }

    /**
     * Hapus Data Ujian Tasmi'
     */
    public function destroy(TasmiExam $tasmi)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Hanya Administrator yang dapat menghapus riwayat ujian tasmi.');
        }

        $tasmi->delete();
        ActivityLog::log('Tahfidz', 'Tasmi', "Menghapus data ujian tasmi ID {$tasmi->id}");

        return redirect()->route('tasmi.index')->with('success', 'Data ujian tasmi berhasil dihapus.');
    }

    /**
     * Cetak Syahadah / Piagam Ujian Tasmi' Al-Qur'an Bil Ghaib (A4 Landscape Resmi)
     */
    public function printCertificate(TasmiExam $tasmi)
    {
        $tasmi->load(['student', 'academicYear', 'mentoringGroup']);

        $headmasterName = \App\Models\Setting::where('key', 'headmaster_name')->value('value')
            ?? optional(\App\Models\User::where('role', 'headmaster')->first())->name
            ?? "Nur'asiah, S.Pd.I";

        return view('tahfidz.tasmi.print_certificate', compact('tasmi', 'headmasterName'));
    }

    /**
     * Cetak Berita Acara Ujian Tasmi' Sekali Duduk (A4 Portrait Resmi)
     */
    public function printReport(TasmiExam $tasmi)
    {
        $tasmi->load(['student', 'academicYear', 'mentoringGroup', 'examinerUser']);

        $headmasterName = \App\Models\Setting::where('key', 'headmaster_name')->value('value')
            ?? optional(\App\Models\User::where('role', 'headmaster')->first())->name
            ?? "Nur'asiah, S.Pd.I";

        return view('tahfidz.tasmi.print_report', compact('tasmi', 'headmasterName'));
    }
}
