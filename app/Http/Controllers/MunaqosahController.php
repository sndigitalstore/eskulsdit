<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\MentoringGroup;
use App\Models\MunaqisyExaminer;
use App\Models\MunaqisySchedule;
use App\Models\MunaqosahRegistration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class MunaqosahController extends Controller
{
    public function __construct()
    {
        $this->ensureTablesExist();
    }

    /**
     * Self-healing: memastikan tabel munaqisy_examiners, munaqosah_registrations, dan munaqisy_schedules selalu ada
     */
    protected function ensureTablesExist(): void
    {
        if (!Schema::hasTable('munaqisy_examiners')) {
            Schema::create('munaqisy_examiners', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->enum('specialization', ['tilawati', 'tahfidz', 'both'])->default('both');
                $table->string('certificate_number')->nullable();
                $table->string('phone')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('munaqisy_schedules')) {
            Schema::create('munaqisy_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('examiner_id')->constrained('munaqisy_examiners')->cascadeOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->string('day_name')->nullable();
                $table->date('date')->nullable();
                $table->string('time_start', 10)->default('08:00');
                $table->string('time_end', 10)->default('09:00');
                $table->enum('exam_type', ['all', 'tilawati', 'tahfidz'])->default('all');
                $table->string('room_location')->nullable();
                $table->integer('quota')->default(1);
                $table->enum('status', ['available', 'full', 'inactive'])->default('available');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('munaqosah_registrations')) {
            Schema::create('munaqosah_registrations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->foreignId('from_group_id')->nullable()->constrained('mentoring_groups')->nullOnDelete();
                $table->foreignId('proposer_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('proposer_name')->nullable();
                $table->string('proposer_phone')->nullable();
                $table->text('proposer_notes')->nullable();
                
                $table->foreignId('examiner_id')->nullable()->constrained('munaqisy_examiners')->nullOnDelete();
                $table->foreignId('examiner_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('schedule_id')->nullable();
                $table->enum('type', ['tilawati', 'tahfidz'])->index();
                $table->string('current_level');
                $table->string('target_level');
                $table->date('registration_date')->nullable();
                $table->date('exam_date')->nullable();
                $table->string('exam_time')->nullable();
                $table->string('room_location')->nullable();
                $table->enum('status', ['pending', 'scheduled', 'passed', 'failed'])->default('pending')->index();

                $table->decimal('score_tajwid', 5, 2)->nullable();
                $table->decimal('score_fashahah', 5, 2)->nullable();
                $table->decimal('score_makhraj', 5, 2)->nullable();
                $table->decimal('score_adab_lagu', 5, 2)->nullable();
                $table->decimal('final_score', 5, 2)->nullable();
                $table->string('predicate')->nullable();
                $table->text('examiner_notes')->nullable();
                $table->string('recommendation')->nullable();
                $table->timestamp('tested_at')->nullable();

                $table->enum('transition_status', ['none', 'pending_promotion', 'promoted'])->default('none')->index();
                $table->foreignId('promoted_to_group_id')->nullable()->constrained('mentoring_groups')->nullOnDelete();
                $table->timestamp('promoted_at')->nullable();
                $table->foreignId('promoted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
            });
        } else {
            // Self-healing: tambahkan kolom proposer_name & proposer_phone jika belum ada
            if (!Schema::hasColumn('munaqosah_registrations', 'proposer_name')) {
                Schema::table('munaqosah_registrations', function (Blueprint $table) {
                    $table->string('proposer_name')->nullable()->after('proposer_user_id');
                    $table->string('proposer_phone')->nullable()->after('proposer_name');
                });
            }
            if (!Schema::hasColumn('munaqosah_registrations', 'schedule_id')) {
                Schema::table('munaqosah_registrations', function (Blueprint $table) {
                    $table->foreignId('schedule_id')->nullable()->after('examiner_user_id');
                });
            }
        }
    }

    /**
     * Halaman Utama Pengelolaan Munaqisy & Munaqosah
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->input('year_id', $activeYear ? $activeYear->id : null);
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        $activeTab = $request->input('tab', 'recap'); // 'recap', 'examiners', 'pending_promotions'
        $statusFilter = $request->input('status', 'all'); // 'all', 'pending', 'scheduled', 'passed', 'failed'
        $typeFilter = $request->input('type', 'all'); // 'all', 'tilawati', 'tahfidz'
        $examinerFilter = $request->input('examiner', 'all'); // 'all', 'me'
        $search = $request->input('search');

        $user = Auth::user();
        $examinerRecord = MunaqisyExaminer::where('user_id', $user->id)->first();

        // Query Registrations
        $query = MunaqosahRegistration::with([
            'student', 
            'academicYear', 
            'fromGroup', 
            'proposer', 
            'examiner.user', 
            'examinerUser',
            'promotedToGroup'
        ]);

        if ($selectedYearId) {
            $query->where('academic_year_id', $selectedYearId);
        }

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($typeFilter !== 'all') {
            $query->where('type', $typeFilter);
        }

        if ($examinerFilter === 'me') {
            $query->where(function($q) use ($user, $examinerRecord) {
                $q->where('examiner_user_id', $user->id);
                if ($examinerRecord) {
                    $q->orWhere('examiner_id', $examinerRecord->id);
                }
            });
        }

        if ($search) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('class', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Rekap Statistik KPI
        $baseKpiQuery = MunaqosahRegistration::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId));
        $totalCount = (clone $baseKpiQuery)->count();
        $pendingCount = (clone $baseKpiQuery)->where('status', 'pending')->count();
        $scheduledCount = (clone $baseKpiQuery)->where('status', 'scheduled')->count();
        $passedCount = (clone $baseKpiQuery)->where('status', 'passed')->count();
        $failedCount = (clone $baseKpiQuery)->where('status', 'failed')->count();
        $pendingPromotionCount = (clone $baseKpiQuery)->where('status', 'passed')->where('transition_status', 'pending_promotion')->count();

        // Hitung jumlah siswa ujian khusus penguji yang login
        $myExamsCount = (clone $baseKpiQuery)->where(function($q) use ($user, $examinerRecord) {
            $q->where('examiner_user_id', $user->id);
            if ($examinerRecord) {
                $q->orWhere('examiner_id', $examinerRecord->id);
            }
        })->count();

        // Dewan Penguji (Munaqisy)
        $examiners = MunaqisyExaminer::with('user')->withCount('registrations')->get();
        $examinersCount = $examiners->where('status', 'active')->count();

        // Jadwal Ketersediaan Penguji (Munaqisy)
        $schedules = MunaqisySchedule::with(['examiner.user', 'registrations.student'])
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->orderBy('day_name')
            ->orderBy('time_start')
            ->get();
        $schedulesCount = $schedules->count();

        // Data Guru & Siswa untuk modal pendaftaran & penugasan penguji
        $teachers = User::whereIn('role', ['teacher', 'admin', 'curriculum'])->orderBy('name')->get();
        $groups = MentoringGroup::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))->orderBy('name')->get();

        return view('munaqosah.index', compact(
            'registrations',
            'examiners',
            'schedules',
            'schedulesCount',
            'academicYears',
            'selectedYearId',
            'activeYear',
            'activeTab',
            'statusFilter',
            'typeFilter',
            'examinerFilter',
            'examinerRecord',
            'myExamsCount',
            'search',
            'totalCount',
            'pendingCount',
            'scheduledCount',
            'passedCount',
            'failedCount',
            'pendingPromotionCount',
            'examinersCount',
            'teachers',
            'groups'
        ));
    }

    /**
     * Halaman / Form Pendaftaran Munaqosah Internal (Guru / Admin)
     */
    public function registerForm(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $students = Student::activeYear()->active()->orderBy('class')->orderBy('name')->get();
        $groups = MentoringGroup::when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))->orderBy('name')->get();
        $examiners = MunaqisyExaminer::with('user')->where('status', 'active')->get();

        $selectedStudentId = $request->input('student_id');
        $selectedType = $request->input('type', 'tahfidz');
        $selectedGroupId = $request->input('from_group_id');

        $selectedStudent = null;
        $suggestedCurrentLevel = null;
        $suggestedTargetLevel = null;

        if ($selectedStudentId) {
            $selectedStudent = Student::find($selectedStudentId);
            if ($selectedStudent) {
                if (!$selectedGroupId) {
                    $detectedGrp = $selectedType === 'tahfidz' 
                        ? $selectedStudent->tahfidzGroup($activeYear?->id) 
                        : $selectedStudent->tilawatiGroup($activeYear?->id);
                    if ($detectedGrp) {
                        $selectedGroupId = $detectedGrp->id;
                    }
                }

                // Cek kelompok untuk menyarankan level
                if ($selectedGroupId) {
                    $grp = MentoringGroup::find($selectedGroupId);
                    if ($grp && !empty($grp->target_level)) {
                        $suggestedCurrentLevel = $grp->target_level;
                    }
                }
            }
        }

        $schedules = MunaqisySchedule::with(['examiner.user', 'registrations.student'])
            ->where('status', '!=', 'inactive')
            ->orderBy('day_name')
            ->orderBy('time_start')
            ->get();

        return view('munaqosah.register', compact(
            'students', 
            'groups', 
            'examiners', 
            'schedules',
            'activeYear',
            'selectedStudentId',
            'selectedType',
            'selectedGroupId',
            'selectedStudent',
            'suggestedCurrentLevel'
        ));
    }

    /**
     * Simpan pendaftaran munaqosah siswa (Internal Guru/Admin)
     */
    public function registerStore(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:tilawati,tahfidz',
            'current_level' => 'required|string|max:100',
            'target_level' => 'required|string|max:100',
            'from_group_id' => 'nullable|exists:mentoring_groups,id',
            'proposer_notes' => 'nullable|string',
            'proposer_name' => 'nullable|string|max:150',
            'proposer_phone' => 'nullable|string|max:30',
            'schedule_id' => 'nullable|exists:munaqisy_schedules,id',
            'exam_date' => 'nullable|date',
            'examiner_id' => 'nullable|exists:munaqisy_examiners,id',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $student = Student::findOrFail($request->student_id);

        $examinerId = $request->examiner_id;
        $examinerUserId = null;
        $examDate = $request->exam_date;
        $examTime = $request->exam_time;
        $roomLocation = $request->room_location;
        $scheduleId = $request->schedule_id;
        $sch = null;

        if ($scheduleId) {
            $sch = MunaqisySchedule::with('examiner')->find($scheduleId);
            if ($sch) {
                $examinerId = $sch->examiner_id;
                $examinerUserId = $sch->examiner?->user_id;
                $examTime = "{$sch->time_start} - {$sch->time_end} WIB";
                if ($sch->date) {
                    $examDate = $sch->date;
                }
                if ($sch->room_location) {
                    $roomLocation = $sch->room_location;
                }
            }
        } elseif ($examinerId) {
            $examiner = MunaqisyExaminer::find($examinerId);
            if ($examiner) {
                $examinerUserId = $examiner->user_id;
            }
        }

        $status = ($examinerId && ($examDate || $scheduleId)) ? 'scheduled' : 'pending';

        $proposerName = $request->proposer_name ?: (Auth::user()?->name ?? 'Guru Pembimbing');
        $proposerPhone = $request->proposer_phone ?: (Auth::user()?->phone ?? null);

        $registration = MunaqosahRegistration::create([
            'student_id' => $student->id,
            'academic_year_id' => $activeYear ? $activeYear->id : null,
            'from_group_id' => $request->from_group_id,
            'proposer_user_id' => Auth::id(),
            'proposer_name' => $proposerName,
            'proposer_phone' => $proposerPhone,
            'proposer_notes' => $request->proposer_notes,
            'schedule_id' => $scheduleId,
            'examiner_id' => $examinerId,
            'examiner_user_id' => $examinerUserId,
            'type' => $request->type,
            'current_level' => $request->current_level,
            'target_level' => $request->target_level,
            'registration_date' => now()->toDateString(),
            'exam_date' => $examDate,
            'exam_time' => $examTime,
            'room_location' => $roomLocation,
            'status' => $status,
            'transition_status' => 'none',
        ]);

        if (!empty($sch) && $sch->quota > 0 && ($sch->registrations()->count() >= $sch->quota)) {
            $sch->update(['status' => 'full']);
        }

        $typeLabel = $request->type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        ActivityLog::log("Munaqosah {$typeLabel}", 'Pendaftaran', "Mendaftarkan siswa {$student->name} untuk munaqosah {$typeLabel} ({$request->current_level} -> {$request->target_level})");

        if ($request->filled('redirect_back')) {
            return back()->with('success', "Alhamdulillah! Pendaftaran munaqosah {$typeLabel} untuk ananda {$student->name} ({$request->current_level}) berhasil didaftarkan!");
        }

        return redirect()->route('munaqosah.index')->with('success', "Alhamdulillah! Pendaftaran munaqosah {$typeLabel} untuk ananda {$student->name} ({$request->current_level}) berhasil diajukan!");
    }

    /**
     * Halaman Publik Pendaftaran Munaqosah (Bisa diakses via link WA tanpa login)
     */
    public function publicRegisterForm(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $classes = Student::activeYear()->active()->distinct()->orderBy('class')->pluck('class');
        $groups = MentoringGroup::when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))->orderBy('name')->get();
        $students = Student::activeYear()->active()->orderBy('class')->orderBy('name')->get(['id', 'name', 'class', 'nis']);
        $schedules = MunaqisySchedule::with(['examiner.user', 'registrations.student'])
            ->where('status', '!=', 'inactive')
            ->orderBy('day_name')
            ->orderBy('time_start')
            ->get();

        return view('munaqosah.public_register', compact('activeYear', 'classes', 'groups', 'students', 'schedules'));
    }

    /**
     * Simpan Pendaftaran Publik Munaqosah
     */
    public function publicRegisterStore(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:tilawati,tahfidz',
            'current_level' => 'required|string|max:100',
            'target_level' => 'required|string|max:100',
            'proposer_name' => 'required|string|max:150',
            'proposer_phone' => 'nullable|string|max:30', // Opsional sesuai permintaan
            'proposer_role' => 'nullable|string|max:50',
            'from_group_id' => 'nullable|exists:mentoring_groups,id',
            'schedule_id' => 'nullable|exists:munaqisy_schedules,id',
            'proposer_notes' => 'nullable|string',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $student = Student::findOrFail($request->student_id);

        $examinerId = null;
        $examinerUserId = null;
        $examDate = null;
        $examTime = null;
        $roomLocation = null;
        $status = 'pending';
        $sch = null;

        if ($request->schedule_id) {
            $sch = MunaqisySchedule::with('examiner')->find($request->schedule_id);
            if ($sch) {
                $examinerId = $sch->examiner_id;
                $examinerUserId = $sch->examiner?->user_id;
                $examTime = "{$sch->time_start} - {$sch->time_end} WIB";
                $roomLocation = $sch->room_location;
                if ($sch->date) {
                    $examDate = $sch->date;
                }
                $status = 'scheduled';
            }
        }

        $notes = $request->proposer_notes;
        if ($request->proposer_role) {
            $notes = "[Pendaftar: " . strtoupper($request->proposer_role) . "] " . ($notes ?? '');
        }

        $registration = MunaqosahRegistration::create([
            'student_id' => $student->id,
            'academic_year_id' => $activeYear ? $activeYear->id : null,
            'from_group_id' => $request->from_group_id,
            'proposer_user_id' => Auth::id(), // null jika guest
            'proposer_name' => $request->proposer_name,
            'proposer_phone' => $request->proposer_phone,
            'proposer_notes' => trim($notes),
            'schedule_id' => $request->schedule_id,
            'examiner_id' => $examinerId,
            'examiner_user_id' => $examinerUserId,
            'exam_date' => $examDate,
            'exam_time' => $examTime,
            'room_location' => $roomLocation,
            'type' => $request->type,
            'current_level' => $request->current_level,
            'target_level' => $request->target_level,
            'registration_date' => now()->toDateString(),
            'status' => $status,
            'transition_status' => 'none',
        ]);

        if (!empty($sch) && $sch->quota > 0 && ($sch->registrations()->count() >= $sch->quota)) {
            $sch->update(['status' => 'full']);
        }

        $typeLabel = $request->type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        $phoneInfo = $request->proposer_phone ? " ({$request->proposer_phone})" : "";
        ActivityLog::log("Munaqosah {$typeLabel}", 'Pendaftaran Publik', "Pendaftaran mandiri siswa {$student->name} ({$request->current_level} -> {$request->target_level}) oleh {$request->proposer_name}{$phoneInfo}");

        return redirect()->route('munaqosah.public-register.success', $registration->id);
    }

    /**
     * Halaman Sukses Pendaftaran Publik
     */
    public function publicRegisterSuccess($id)
    {
        $registration = MunaqosahRegistration::with(['student', 'academicYear', 'fromGroup'])->findOrFail($id);
        return view('munaqosah.public_success', compact('registration'));
    }

    /**
     * Jadwalkan Ujian & Tentukan Munaqisy Penguji
     */
    public function scheduleExam(Request $request, $id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            return back()->with('error', 'Hanya Administrator dan Kurikulum yang dapat menjadwalkan munaqosah.');
        }

        $registration = MunaqosahRegistration::findOrFail($id);

        $request->validate([
            'examiner_id' => 'required|exists:munaqisy_examiners,id',
            'exam_date' => 'required|date',
            'exam_time' => 'nullable|string|max:50',
            'room_location' => 'nullable|string|max:100',
        ]);

        $examiner = MunaqisyExaminer::findOrFail($request->examiner_id);

        $registration->update([
            'examiner_id' => $examiner->id,
            'examiner_user_id' => $examiner->user_id,
            'exam_date' => $request->exam_date,
            'exam_time' => $request->exam_time,
            'room_location' => $request->room_location,
            'status' => 'scheduled',
        ]);

        ActivityLog::log('Munaqosah', 'Penjadwalan', "Menjadwalkan munaqosah {$registration->student->name} dengan penguji {$examiner->user->name}");

        return back()->with('success', 'Jadwal dan Penguji Munaqosah berhasil ditetapkan.');
    }

    /**
     * Halaman / Form Penilaian Penguji (Munaqisy Scoring)
     */
    public function scoreForm($id)
    {
        $registration = MunaqosahRegistration::with(['student', 'examiner.user', 'proposer'])->findOrFail($id);
        $user = Auth::user();

        // Izin: admin, kurikulum, kepala sekolah, atau penguji yang bersangkutan
        $isAuthorized = in_array($user->role, ['admin', 'headmaster', 'curriculum']) 
            || ($registration->examiner_user_id == $user->id) 
            || ($registration->examiner && $registration->examiner->user_id == $user->id);

        if (!$isAuthorized) {
            return redirect()->route('munaqosah.index')->with('error', 'Anda tidak memiliki hak akses penilaian untuk peserta ini.');
        }

        return view('munaqosah.score', compact('registration'));
    }

    /**
     * Simpan Hasil Penilaian Penguji
     */
    public function scoreStore(Request $request, $id)
    {
        $registration = MunaqosahRegistration::with('student')->findOrFail($id);
        $user = Auth::user();

        $isAuthorized = in_array($user->role, ['admin', 'headmaster', 'curriculum']) 
            || ($registration->examiner_user_id == $user->id) 
            || ($registration->examiner && $registration->examiner->user_id == $user->id);

        if (!$isAuthorized) {
            return redirect()->route('munaqosah.index')->with('error', 'Akses penilaian ditolak.');
        }

        $request->validate([
            'score_fashahah' => 'required|numeric|min:0|max:100',
            'score_tajwid' => 'required|numeric|min:0|max:100',
            'score_makhraj' => 'required|numeric|min:0|max:100',
            'score_adab_lagu' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:passed,failed',
            'examiner_notes' => 'nullable|string',
            'recommendation' => 'nullable|string|max:255',
        ]);

        // Perhitungan Bobot Nilai Standar Nasional:
        // Fashahah / Kelancaran: 30%
        // Tajwid: 30%
        // Makharijul Huruf: 25%
        // Adab / Lagu / Irama: 15%
        $fashahah = (float) $request->score_fashahah;
        $tajwid = (float) $request->score_tajwid;
        $makhraj = (float) $request->score_makhraj;
        $adab = (float) $request->score_adab_lagu;

        $finalScore = round(($fashahah * 0.30) + ($tajwid * 0.30) + ($makhraj * 0.25) + ($adab * 0.15), 1);
        $predicate = MunaqosahRegistration::getPredicateByScore($finalScore);

        $status = $request->status;
        // Jika Lulus, set status transisi menjadi pending_promotion agar muncul di Dashboard Admin/Kurikulum
        $transitionStatus = ($status === 'passed') ? 'pending_promotion' : 'none';

        $registration->update([
            'score_fashahah' => $fashahah,
            'score_tajwid' => $tajwid,
            'score_makhraj' => $makhraj,
            'score_adab_lagu' => $adab,
            'final_score' => $finalScore,
            'predicate' => $predicate,
            'status' => $status,
            'transition_status' => $transitionStatus,
            'examiner_notes' => $request->examiner_notes,
            'recommendation' => $request->recommendation ?? ($status === 'passed' ? "Naik ke {$registration->target_level}" : "Ulangi {$registration->current_level}"),
            'tested_at' => now(),
            'examiner_user_id' => $user->id,
        ]);

        $statusLabel = $status === 'passed' ? 'LULUS' : 'BELUM LULUS';
        ActivityLog::log('Munaqosah', 'Penilaian', "Munaqisy menginput nilai ujian {$registration->student->name}: Nilai {$finalScore} ({$predicate}) - Status: {$statusLabel}");

        return redirect()->route('munaqosah.index')->with('success', "Penilaian munaqosah ananda {$registration->student->name} berhasil disimpan dengan status: {$statusLabel}!");
    }

    /**
     * Eksekusi Pemindahan Jenjang / Kelompok Siswa setelah Lulus Munaqosah
     * Dipanggil dari Dashboard Alert maupun Halaman Munaqisy
     */
    public function promote(Request $request, $id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Hanya Administrator dan Kurikulum yang dapat memindahkan jenjang siswa.'], 403);
            }
            return back()->with('error', 'Hanya Administrator dan Kurikulum yang dapat memindahkan jenjang siswa.');
        }

        $registration = MunaqosahRegistration::with(['student', 'academicYear'])->findOrFail($id);

        $request->validate([
            'target_group_id' => 'required|exists:mentoring_groups,id',
        ]);

        $newGroup = MentoringGroup::findOrFail($request->target_group_id);
        $student = $registration->student;
        $yearId = $registration->academic_year_id ?? ($newGroup->academic_year_id ?? null);

        DB::beginTransaction();
        try {
            // 1. Lepaskan siswa dari kelompok lama yang bertipe sama (tilawati/tahfidz) di tahun ajaran aktif
            $oldGroupIds = DB::table('mentoring_group_students')
                ->join('mentoring_groups', 'mentoring_groups.id', '=', 'mentoring_group_students.mentoring_group_id')
                ->where('mentoring_groups.type', $registration->type)
                ->when($yearId, fn($q) => $q->where('mentoring_groups.academic_year_id', $yearId))
                ->where('mentoring_group_students.student_id', $student->id)
                ->pluck('mentoring_groups.id');

            if ($oldGroupIds->isNotEmpty()) {
                DB::table('mentoring_group_students')
                    ->whereIn('mentoring_group_id', $oldGroupIds)
                    ->where('student_id', $student->id)
                    ->delete();
            }

            // 2. Daftarkan siswa ke kelompok baru
            DB::table('mentoring_group_students')->updateOrInsert(
                [
                    'mentoring_group_id' => $newGroup->id,
                    'student_id' => $student->id,
                ],
                [
                    'academic_year_id' => $yearId,
                    'notes' => "Naik jenjang munaqosah dari {$registration->current_level} ke {$registration->target_level}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // 3. Update status pendaftaran munaqosah menjadi 'promoted'
            $registration->update([
                'transition_status' => 'promoted',
                'promoted_to_group_id' => $newGroup->id,
                'promoted_at' => now(),
                'promoted_by' => $user->id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memindahkan siswa: ' . $e->getMessage()], 422);
            }
            return back()->with('error', 'Gagal memindahkan siswa: ' . $e->getMessage());
        }

        $typeLabel = $registration->type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        ActivityLog::log("Munaqosah {$typeLabel}", 'Pindah Kelompok', "Memindahkan ananda {$student->name} ke kelompok baru \"{$newGroup->name}\" ({$newGroup->target_level})");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Alhamdulillah! Ananda {$student->name} berhasil dipindahkan ke kelompok {$newGroup->name}.",
                'group_name' => $newGroup->name
            ]);
        }

        return back()->with('success', "Alhamdulillah! Ananda {$student->name} berhasil dipindahkan ke kelompok {$newGroup->name}!");
    }

    /**
     * Manajemen Penguji (Munaqisy)
     */
    public function examinerStore(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            return back()->with('error', 'Hanya Administrator dan Kurikulum yang dapat mengelola dewan penguji.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialization' => 'required|in:tilawati,tahfidz,both',
            'certificate_number' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:25',
            'notes' => 'nullable|string',
        ]);

        // Cek jika sudah terdaftar
        $existing = MunaqisyExaminer::where('user_id', $request->user_id)->first();
        if ($existing) {
            $existing->update([
                'specialization' => $request->specialization,
                'certificate_number' => $request->certificate_number,
                'phone' => $request->phone,
                'status' => 'active',
                'notes' => $request->notes,
            ]);
            return back()->with('success', 'Data Mentor Penguji (Munaqisy) berhasil diperbarui.');
        }

        MunaqisyExaminer::create([
            'user_id' => $request->user_id,
            'specialization' => $request->specialization,
            'certificate_number' => $request->certificate_number,
            'phone' => $request->phone,
            'status' => 'active',
            'notes' => $request->notes,
        ]);

        ActivityLog::log('Munaqisy', 'Tambah Penguji', "Menambahkan mentor penguji baru ID: {$request->user_id}");

        return back()->with('success', 'Mentor Penguji (Munaqisy) berhasil ditambahkan.');
    }

    public function examinerToggle($id)
    {
        $examiner = MunaqisyExaminer::findOrFail($id);
        $examiner->status = ($examiner->status === 'active') ? 'inactive' : 'active';
        $examiner->save();

        return back()->with('success', "Status penguji {$examiner->user->name} diubah menjadi {$examiner->status}.");
    }

    public function examinerDestroy($id)
    {
        $examiner = MunaqisyExaminer::findOrFail($id);
        $name = $examiner->user->name ?? 'Penguji';
        $examiner->delete();

        return back()->with('success', "Mentor penguji {$name} berhasil dihapus.");
    }

    /**
     * Simpan Jadwal Ketersediaan Penguji (Munaqisy Schedule) oleh Admin & Kurikulum
     */
    public function scheduleStore(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            return back()->with('error', 'Hanya Administrator dan Kurikulum yang dapat mengatur jadwal penguji.');
        }

        $request->validate([
            'examiner_id' => 'required|exists:munaqisy_examiners,id',
            'day_name' => 'required|string|max:20',
            'date' => 'nullable|date',
            'time_start' => 'required|string|max:10',
            'time_end' => 'required|string|max:10',
            'quota' => 'required|integer|min:1|max:50',
            'room_location' => 'nullable|string|max:100',
            'exam_type' => 'required|in:all,tilawati,tahfidz',
            'notes' => 'nullable|string|max:255',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        $schedule = MunaqisySchedule::create([
            'examiner_id' => $request->examiner_id,
            'academic_year_id' => $activeYear?->id,
            'day_name' => $request->day_name,
            'date' => $request->date,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'quota' => $request->quota ?? 1,
            'room_location' => $request->room_location,
            'exam_type' => $request->exam_type ?? 'all',
            'status' => 'available',
            'notes' => $request->notes,
        ]);

        $examiner = MunaqisyExaminer::find($request->examiner_id);
        $examinerName = $examiner?->user?->name ?? 'Penguji';

        ActivityLog::log('Munaqosah', 'Jadwal Penguji', "Menambahkan ketersediaan jadwal {$examinerName} ({$request->day_name}, {$request->time_start}-{$request->time_end})");

        return back()->with('success', "Alhamdulillah! Jadwal ketersediaan {$examinerName} hari {$request->day_name} ({$request->time_start} s/d {$request->time_end}) berhasil ditambahkan.");
    }

    /**
     * Hapus Slot Jadwal Penguji
     */
    public function scheduleDestroy($id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            return back()->with('error', 'Hanya Administrator dan Kurikulum yang dapat menghapus jadwal penguji.');
        }

        $schedule = MunaqisySchedule::findOrFail($id);
        $schedule->delete();

        ActivityLog::log('Munaqosah', 'Hapus Jadwal', "Menghapus slot jadwal penguji ID: {$id}");

        return back()->with('success', 'Jadwal ketersediaan penguji berhasil dihapus.');
    }

    /**
     * Hapus Pendaftaran Munaqosah
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $registration = MunaqosahRegistration::findOrFail($id);

        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum']) && $registration->proposer_user_id !== $user->id) {
            return back()->with('error', 'Anda tidak berhak menghapus data ini.');
        }

        $scheduleId = $registration->schedule_id;
        $studentName = $registration->student->name ?? 'Siswa';
        $registration->delete();

        if ($scheduleId) {
            $sch = MunaqisySchedule::find($scheduleId);
            if ($sch && $sch->status === 'full' && $sch->registrations()->count() < $sch->quota) {
                $sch->update(['status' => 'available']);
            }
        }

        ActivityLog::log('Munaqosah', 'Hapus Data', "Menghapus data munaqosah siswa {$studentName}");

        return back()->with('success', "Data munaqosah {$studentName} berhasil dihapus.");
    }

    /**
     * Cetak Berita Acara / Surat Tanda Lulus Munaqosah Siswa
     */
    public function printCertificate($id)
    {
        $registration = MunaqosahRegistration::with([
            'student', 
            'academicYear', 
            'examiner.user', 
            'examinerUser',
            'proposer'
        ])->findOrFail($id);

        $headmaster = User::where('role', 'headmaster')->first();
        $branding = \App\Models\Setting::whereIn('key', ['school_name', 'school_address', 'school_headmaster'])->pluck('value', 'key');

        return view('munaqosah.print_certificate', compact('registration', 'headmaster', 'branding'));
    }

    /**
     * Cetak Rekapan Data Munaqosah (Lulus & Belum Lulus)
     */
    public function printRecap(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->input('year_id', $activeYear ? $activeYear->id : null);
        $status = $request->input('status', 'all');
        $type = $request->input('type', 'all');

        $query = MunaqosahRegistration::with(['student', 'academicYear', 'examiner.user', 'examinerUser']);

        if ($selectedYearId) {
            $query->where('academic_year_id', $selectedYearId);
        }
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $registrations = $query->orderBy('type')->orderBy('status')->orderBy('student_id')->get();
        $selectedYear = AcademicYear::find($selectedYearId);

        return view('munaqosah.print_recap', compact('registrations', 'selectedYear', 'status', 'type'));
    }
}
