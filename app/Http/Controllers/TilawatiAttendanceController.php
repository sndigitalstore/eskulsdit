<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\MentoringGroup;
use App\Models\Student;
use App\Models\TilawatiAttendance;
use App\Models\TilawatiSession;
use App\Models\MunaqisyExaminer;
use App\Models\MunaqisySchedule;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class TilawatiAttendanceController extends Controller
{
    protected static bool $tableChecked = false;

    /**
     * Memastikan tabel tilawati_sessions dan tilawati_attendances ada secara otomatis (self-healing)
     */
    public static function ensureTableExists(): void
    {
        if (self::$tableChecked) {
            return;
        }
        self::$tableChecked = true;

        try {
            if (!Schema::hasTable('tilawati_sessions')) {
                Schema::create('tilawati_sessions', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('mentoring_group_id');
                    $table->unsignedBigInteger('academic_year_id')->nullable();
                    $table->date('session_date');
                    $table->string('jilid', 50)->default('Jilid 1');
                    $table->unsignedSmallInteger('peraga_page_start')->default(1);
                    $table->unsignedSmallInteger('peraga_page_end')->default(4); // 4 Halaman Alat Peraga
                    $table->unsignedSmallInteger('buku_page')->default(1);        // 1 Halaman Buku Tilawati
                    $table->text('notes')->nullable();
                    $table->unsignedBigInteger('recorded_by')->nullable();
                    $table->timestamps();

                    $table->unique(['mentoring_group_id', 'session_date'], 'unique_tilawati_group_session');
                    $table->index(['mentoring_group_id', 'session_date']);
                    $table->index('academic_year_id');
                });
            }

            if (!Schema::hasTable('tilawati_attendances')) {
                Schema::create('tilawati_attendances', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('tilawati_session_id')->nullable();
                    $table->unsignedBigInteger('mentoring_group_id');
                    $table->unsignedBigInteger('student_id');
                    $table->unsignedBigInteger('academic_year_id')->nullable();
                    $table->date('session_date');
                    $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha'])->default('hadir');
                    $table->string('jilid', 50)->default('Jilid 1');
                    $table->string('peraga_pages', 50)->nullable();
                    $table->unsignedSmallInteger('buku_page')->nullable();
                    $table->decimal('score', 5, 2)->nullable();
                    $table->boolean('is_passed')->default(true);
                    $table->enum('action', ['lanjut', 'ulang'])->default('lanjut');
                    $table->string('tugas_besok')->nullable();
                    $table->text('notes')->nullable();
                    $table->unsignedBigInteger('recorded_by')->nullable();
                    $table->timestamps();

                    $table->unique(['mentoring_group_id', 'student_id', 'session_date'], 'unique_tilawati_attendance');
                    $table->index(['mentoring_group_id', 'session_date']);
                    $table->index(['student_id', 'session_date']);
                    $table->index('academic_year_id');
                });
            }
        } catch (\Throwable $e) {
            // Silently continue if schema manipulation fails
        }
    }

    /**
     * Memeriksa hak akses user ke kelompok Tilawati
     */
    private function checkGroupAccess(MentoringGroup $group, bool $mustBeMentor = false): void
    {
        $user = Auth::user();
        if ($user->role === 'teacher') {
            $isMentor = ($group->mentor_user_id == $user->id) || (trim(strtolower($group->mentor_name ?? '')) == trim(strtolower($user->name)));
            if ($mustBeMentor) {
                if (!$isMentor) {
                    abort(403, 'Akses ditolak. Anda bukan pembimbing kelompok Tilawati ini.');
                }
            } else {
                $isHomeroom = !empty($user->homeroom_class);
                $hasStudentInClass = false;
                if ($isHomeroom) {
                    $hasStudentInClass = $group->students()->where('class', $user->homeroom_class)->exists();
                }
                if (!$isMentor && !$hasStudentInClass) {
                    abort(403, 'Akses ditolak. Anda tidak memiliki akses ke kelompok Tilawati ini.');
                }
            }
        }
    }

    /**
     * Halaman Riwayat Sesi Pertemuan Tilawati Kelompok
     */
    public function index(Request $request, MentoringGroup $group)
    {
        self::ensureTableExists();
        $this->checkGroupAccess($group);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $request->input('year_id', $activeYear?->id);

        $sessions = TilawatiSession::where('mentoring_group_id', $group->id)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->withCount([
                'attendances as total_students',
                'attendances as hadir_count' => fn($q) => $q->where('status', 'hadir'),
                'attendances as passed_count' => fn($q) => $q->where('status', 'hadir')->where('is_passed', true),
                'attendances as repeat_count' => fn($q) => $q->where('status', 'hadir')->where('is_passed', false),
            ])
            ->orderBy('session_date', 'desc')
            ->get();

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $selectedYear = AcademicYear::find($yearId);
        $totalSiswa = $group->students()->count();

        // Cek apakah ada jadwal munaqosah aktif untuk pendaftaran 1 kelompok
        $munaqosahSchedules = MunaqisySchedule::with('examiner.user')
            ->where('status', '!=', 'inactive')
            ->whereIn('exam_type', ['all', 'tilawati'])
            ->orderBy('day_name')
            ->get();

        $examiners = MunaqisyExaminer::with('user')->where('status', 'active')->get();

        return view('tilawati.attendance_index', compact(
            'group', 'sessions', 'academicYears', 'selectedYear', 'yearId', 'totalSiswa', 'munaqosahSchedules', 'examiners'
        ));
    }

    /**
     * Form Input Sesi Pertemuan Harian (4 Halaman Alat Peraga + 1 Halaman Buku Tilawati)
     */
    public function create(Request $request, MentoringGroup $group)
    {
        self::ensureTableExists();
        $this->checkGroupAccess($group, true);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $request->input('year_id', $activeYear?->id);
        $date = $request->input('date', date('Y-m-d'));

        $students = $group->students()->orderBy('class')->orderBy('name')->get();

        // Cek apakah sudah ada sesi di tanggal ini
        $existingSession = TilawatiSession::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->first();

        $existingAttendances = TilawatiAttendance::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->get()
            ->keyBy('student_id');

        // Deteksi jilid kelompok
        $jilid = $group->target_level ?: 'Jilid 1';
        if ($existingSession) {
            $jilid = $existingSession->jilid;
            $peragaStart = $existingSession->peraga_page_start;
            $peragaEnd = $existingSession->peraga_page_end;
            $bukuPage = $existingSession->buku_page;
        } else {
            // Ambil sesi terakhir kelompok ini untuk auto-increment halaman
            $lastSession = TilawatiSession::where('mentoring_group_id', $group->id)
                ->where('session_date', '<', $date)
                ->orderBy('session_date', 'desc')
                ->first();

            if ($lastSession) {
                $jilid = $lastSession->jilid;
                // Default: naik 4 halaman alat peraga dan 1 halaman buku
                $peragaStart = $lastSession->peraga_page_end + 1;
                $peragaEnd = $peragaStart + 3;
                $bukuPage = $lastSession->buku_page + 1;
            } else {
                $peragaStart = 1;
                $peragaEnd = 4;
                $bukuPage = 1;
            }
        }

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        return view('tilawati.attendance_form', compact(
            'group', 'students', 'existingSession', 'existingAttendances', 'date', 'yearId',
            'academicYears', 'jilid', 'peragaStart', 'peragaEnd', 'bukuPage'
        ));
    }

    /**
     * Simpan / Update Sesi Pertemuan Harian Tilawati & Evaluasi Santri
     */
    public function store(Request $request, MentoringGroup $group)
    {
        self::ensureTableExists();
        $this->checkGroupAccess($group, true);

        $request->validate([
            'session_date'       => 'required|date',
            'academic_year_id'   => 'required|exists:academic_years,id',
            'jilid'              => 'required|string|max:50',
            'peraga_page_start'  => 'required|integer|min:1',
            'peraga_page_end'    => 'required|integer|min:1|gte:peraga_page_start',
            'buku_page'          => 'required|integer|min:1',
            'session_notes'      => 'nullable|string',
            'attendance'         => 'required|array',
            'attendance.*.status'=> 'required|in:hadir,sakit,izin,alpha',
            'attendance.*.score' => 'nullable|numeric|min:0|max:100',
            'attendance.*.notes' => 'nullable|string|max:500',
        ]);

        $date = $request->session_date;
        $yearId = $request->academic_year_id;
        $userId = Auth::id();
        $jilid = $request->jilid;
        $peragaStart = (int) $request->peraga_page_start;
        $peragaEnd = (int) $request->peraga_page_end;
        $bukuPage = (int) $request->buku_page;
        $peragaStr = "Hal {$peragaStart} - {$peragaEnd}";

        DB::beginTransaction();
        try {
            // 1. Simpan Header Sesi Tilawati
            $session = TilawatiSession::updateOrCreate(
                [
                    'mentoring_group_id' => $group->id,
                    'session_date'       => $date,
                ],
                [
                    'academic_year_id'   => $yearId,
                    'jilid'              => $jilid,
                    'peraga_page_start'  => $peragaStart,
                    'peraga_page_end'    => $peragaEnd,
                    'buku_page'          => $bukuPage,
                    'notes'              => $request->session_notes,
                    'recorded_by'        => $userId,
                ]
            );

            $passedCount = 0;
            $repeatCount = 0;
            $hadirCount = 0;
            $studentsSummary = [];

            // 2. Simpan Evaluasi Siswa
            foreach ($request->attendance as $studentId => $data) {
                $status = $data['status'];
                $score = isset($data['score']) && trim($data['score']) !== '' ? (float) $data['score'] : null;
                $studentNotes = trim($data['notes'] ?? '');

                // Aturan Kelulusan Pertemuan Tilawati:
                // Skor >= 70% => LANJUT
                // Skor < 70% => MENGULANG
                $isPassed = ($score !== null) ? ($score >= 70) : true;
                $action = $isPassed ? 'lanjut' : 'ulang';

                // Rekomendasi Tugas Besok
                if ($action === 'lanjut') {
                    $nextPeragaStart = $peragaEnd + 1;
                    $nextPeragaEnd = $nextPeragaStart + 3;
                    $nextBuku = $bukuPage + 1;
                    $tugasBesok = "Lanjut ke Peraga Hal {$nextPeragaStart}-{$nextPeragaEnd}, Buku {$jilid} Hal {$nextBuku}";
                } else {
                    $tugasBesok = "Mengulang Peraga Hal {$peragaStart}-{$peragaEnd}, Buku {$jilid} Hal {$bukuPage}";
                }

                if ($status === 'hadir') {
                    $hadirCount++;
                    if ($action === 'lanjut') {
                        $passedCount++;
                    } else {
                        $repeatCount++;
                    }
                }

                TilawatiAttendance::updateOrCreate(
                    [
                        'mentoring_group_id' => $group->id,
                        'student_id'         => $studentId,
                        'session_date'       => $date,
                    ],
                    [
                        'tilawati_session_id'=> $session->id,
                        'academic_year_id'   => $yearId,
                        'status'             => $status,
                        'jilid'              => $jilid,
                        'peraga_pages'       => $peragaStr,
                        'buku_page'          => $bukuPage,
                        'score'              => $score,
                        'is_passed'          => $isPassed,
                        'action'             => $action,
                        'tugas_besok'        => $tugasBesok,
                        'notes'              => $studentNotes !== '' ? $studentNotes : null,
                        'recorded_by'        => $userId,
                    ]
                );

                $st = Student::find($studentId);
                if ($st) {
                    $studentsSummary[] = [
                        'name' => $st->name,
                        'status' => $status,
                        'score' => $score,
                        'action' => $action,
                        'notes' => $studentNotes,
                    ];
                }
            }

            DB::commit();

            ActivityLog::log('Tilawati', 'Input Sesi', "Pembimbing menginput pertemuan Tilawati kelompok {$group->name} tanggal {$date}: Peraga {$peragaStr}, Buku Hal {$bukuPage}");

            // Siapkan Format Pesan WhatsApp untuk Dibagikan ke Grup
            $formattedDate = \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y');
            $waMessage = "*REKAPAN PEMBELAJARAN TILAWATI SDIT AN NADZIR*\n";
            $waMessage .= "━━━━━━━━━━━━━━━━━━━━━\n";
            $waMessage .= "📖 *Kelompok:* {$group->name}\n";
            $waMessage .= "👨‍🏫 *Pembimbing:* " . (Auth::user()->name) . "\n";
            $waMessage .= "📅 *Hari, Tanggal:* {$formattedDate}\n";
            $waMessage .= "📚 *Tingkat / Jilid:* {$jilid}\n";
            $waMessage .= "📋 *Materi Hari Ini:*\n";
            $waMessage .= "   • Alat Peraga: *{$peragaStr}* (4 Halaman)\n";
            $waMessage .= "   • Buku Tilawati: *Halaman {$bukuPage}* (1 Halaman)\n";
            $waMessage .= "─────────────────────\n";
            $waMessage .= "📊 *Statistik Kelas:*\n";
            $waMessage .= "• Hadir: {$hadirCount} santri\n";
            $waMessage .= "• Lanjut: {$passedCount} santri (Skor ≥ 70%)\n";
            if ($repeatCount > 0) {
                $waMessage .= "• Mengulang: {$repeatCount} santri (Skor < 70%)\n";
            }
            $waMessage .= "─────────────────────\n";
            $waMessage .= "*Catatan Evaluasi Santri:*\n";

            foreach ($studentsSummary as $idx => $s) {
                $no = $idx + 1;
                if ($s['status'] === 'hadir') {
                    $badge = $s['action'] === 'lanjut' ? '✅ Lanjut' : '🔄 Mengulang';
                    $scoreText = $s['score'] !== null ? " (Nilai: {$s['score']})" : "";
                    $waMessage .= "{$no}. *{$s['name']}*: {$badge}{$scoreText}\n";
                    if (!empty($s['notes'])) {
                        $waMessage .= "   _Catatan: {$s['notes']}_\n";
                    }
                } else {
                    $stLabel = strtoupper($s['status']);
                    $waMessage .= "{$no}. *{$s['name']}*: ⚠️ {$stLabel}\n";
                }
            }

            $waMessage .= "━━━━━━━━━━━━━━━━━━━━━\n";
            $waMessage .= "Barakallahu Fiikum. Mohon Ayah/Bunda mendampingi ananda mengulang bacaan di rumah.\n";

            $waPayload = [
                'group_name'   => $group->name,
                'session_date' => $date,
                'message'      => $waMessage,
                'wa_url'       => "https://api.whatsapp.com/send?text=" . urlencode($waMessage),
            ];

            return redirect()->route('tilawati.attendance.session', ['group' => $group->id, 'date' => $date])
                ->with('success', "Alhamdulillah! Pertemuan Tilawati tanggal {$formattedDate} berhasil disimpan.")
                ->with('tilawati_wa_share', $waPayload);

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan data pertemuan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilan Detail Sesi Pertemuan Tilawati
     */
    public function showSession(Request $request, MentoringGroup $group)
    {
        self::ensureTableExists();
        $this->checkGroupAccess($group);

        $date = $request->input('date', date('Y-m-d'));
        $session = TilawatiSession::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->first();

        $attendances = TilawatiAttendance::with('student')
            ->where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->get();

        if (!$session && $attendances->isEmpty()) {
            return redirect()->route('tilawati.attendance.create', ['group' => $group->id, 'date' => $date])
                ->with('info', "Belum ada rekapan sesi pada tanggal {$date}. Silakan isi form di bawah ini.");
        }

        $hadirCount = $attendances->where('status', 'hadir')->count();
        $passedCount = $attendances->where('status', 'hadir')->where('is_passed', true)->count();
        $repeatCount = $attendances->where('status', 'hadir')->where('is_passed', false)->count();

        return view('tilawati.attendance_session', compact(
            'group', 'session', 'attendances', 'date', 'hadirCount', 'passedCount', 'repeatCount'
        ));
    }

    /**
     * Hapus Sesi Pertemuan Tilawati
     */
    public function destroySession(Request $request, MentoringGroup $group)
    {
        self::ensureTableExists();
        $this->checkGroupAccess($group, true);

        $date = $request->input('date');
        if (!$date) {
            return back()->with('error', 'Tanggal sesi tidak valid.');
        }

        DB::beginTransaction();
        try {
            TilawatiAttendance::where('mentoring_group_id', $group->id)
                ->where('session_date', $date)
                ->delete();

            TilawatiSession::where('mentoring_group_id', $group->id)
                ->where('session_date', $date)
                ->delete();

            DB::commit();
            return redirect()->route('tilawati.attendance.index', $group->id)
                ->with('success', "Sesi pertemuan Tilawati tanggal {$date} berhasil dihapus.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus sesi: ' . $e->getMessage());
        }
    }

    /**
     * Rekap Bulanan Tilawati: Matriks kehadiran, ketuntasan (Lanjut/Ulang), dan progress per halaman
     */
    public function monthly(Request $request, MentoringGroup $group)
    {
        self::ensureTableExists();
        $this->checkGroupAccess($group);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $request->input('year_id', $activeYear?->id);
        $month = (int) $request->input('month', date('n'));
        $year = (int) $request->input('year', date('Y'));

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $students = $group->students()->orderBy('class')->orderBy('name')->get();

        // Ambil semua sesi dalam bulan tersebut
        $sessions = TilawatiSession::where('mentoring_group_id', $group->id)
            ->whereMonth('session_date', $month)
            ->whereYear('session_date', $year)
            ->orderBy('session_date')
            ->get();

        $sessionDates = $sessions->pluck('session_date')->map(fn($d) => $d->format('Y-m-d'))->toArray();

        // Ambil absensi dan nilai siswa pada tanggal-tanggal tersebut
        $attendances = TilawatiAttendance::where('mentoring_group_id', $group->id)
            ->whereIn('session_date', $sessionDates)
            ->get()
            ->groupBy('student_id');

        // Kalkulasi Statistik Bulanan per Siswa
        $studentStats = [];
        foreach ($students as $st) {
            $stAtts = $attendances->get($st->id, collect());
            $totalPresent = $stAtts->where('status', 'hadir')->count();
            $totalPassed = $stAtts->where('status', 'hadir')->where('is_passed', true)->count();
            $totalRepeat = $stAtts->where('status', 'hadir')->where('is_passed', false)->count();
            $totalSessions = count($sessionDates);
            $percentage = $totalSessions > 0 ? round(($totalPassed / $totalSessions) * 100) : 0;
            
            // Posisi halaman terakhir
            $lastAtt = $stAtts->sortByDesc('session_date')->first();
            $lastPosition = $lastAtt ? "{$lastAtt->jilid} Hal {$lastAtt->buku_page}" : "-";

            $studentStats[$st->id] = [
                'present' => $totalPresent,
                'passed'  => $totalPassed,
                'repeat'  => $totalRepeat,
                'percentage' => $percentage,
                'last_position' => $lastPosition,
            ];
        }

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('tilawati.monthly_recap', compact(
            'group', 'students', 'sessions', 'attendances', 'month', 'year', 'yearId',
            'academicYears', 'monthNames', 'studentStats'
        ));
    }

    /**
     * Rekap Semester Tilawati: Capaian Jilid awal vs akhir & Deteksi Lulus Jilid 6 untuk Munaqosah
     */
    public function semester(Request $request, MentoringGroup $group)
    {
        self::ensureTableExists();
        $this->checkGroupAccess($group);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $request->input('year_id', $activeYear?->id);
        $semester = $request->input('semester', '1'); // 1 = Ganjil, 2 = Genap

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $students = $group->students()->orderBy('class')->orderBy('name')->get();

        // Ambil semua absensi semester ini
        $attendances = TilawatiAttendance::where('mentoring_group_id', $group->id)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->orderBy('session_date')
            ->get()
            ->groupBy('student_id');

        $totalSessions = TilawatiSession::where('mentoring_group_id', $group->id)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->count();

        $studentRecaps = [];
        $readyForMunaqosahCount = 0;

        foreach ($students as $st) {
            $stAtts = $attendances->get($st->id, collect());
            $firstAtt = $stAtts->first();
            $lastAtt = $stAtts->last();

            $initialPosition = $firstAtt ? "{$firstAtt->jilid} Hal {$firstAtt->buku_page}" : ($group->target_level ?: 'Jilid 1');
            $finalPosition = $lastAtt ? "{$lastAtt->jilid} Hal {$lastAtt->buku_page}" : $initialPosition;

            $totalPresent = $stAtts->where('status', 'hadir')->count();
            $totalPassed = $stAtts->where('status', 'hadir')->where('is_passed', true)->count();
            $totalRepeat = $stAtts->where('status', 'hadir')->where('is_passed', false)->count();

            $rate = $totalPresent > 0 ? round(($totalPassed / $totalPresent) * 100) : 0;

            // Deteksi Tuntas Jilid 6 (Siap Munaqosah Tilawati)
            // Kriteria: Berada di Jilid 6 dengan capaian halaman >= 40 atau kelompok target Jilid 6 dengan rata-rata lanjut tinggi
            $isJilid6 = stripos($finalPosition, 'Jilid 6') !== false || stripos($group->target_level ?? '', 'Jilid 6') !== false;
            $isMunaqosahReady = $isJilid6 && ($rate >= 70 || ($lastAtt && $lastAtt->buku_page >= 35));

            if ($isMunaqosahReady) {
                $readyForMunaqosahCount++;
            }

            $studentRecaps[$st->id] = [
                'initial'   => $initialPosition,
                'final'     => $finalPosition,
                'present'   => $totalPresent,
                'passed'    => $totalPassed,
                'repeat'    => $totalRepeat,
                'rate'      => $rate,
                'is_ready'  => $isMunaqosahReady,
            ];
        }

        // Ambil penguji dan jadwal munaqosah untuk modal kolektif
        $munaqosahSchedules = MunaqisySchedule::with('examiner.user')
            ->where('status', '!=', 'inactive')
            ->whereIn('exam_type', ['all', 'tilawati'])
            ->orderBy('day_name')
            ->get();

        $examiners = MunaqisyExaminer::with('user')->where('status', 'active')->get();

        return view('tilawati.semester_recap', compact(
            'group', 'students', 'totalSessions', 'studentRecaps', 'yearId', 'semester',
            'academicYears', 'readyForMunaqosahCount', 'munaqosahSchedules', 'examiners'
        ));
    }

    /**
     * Cetak Lembar Rekap Sesi Pertemuan
     */
    public function printSession(Request $request, MentoringGroup $group)
    {
        self::ensureTableExists();
        $this->checkGroupAccess($group);

        $date = $request->input('date', date('Y-m-d'));
        $session = TilawatiSession::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->firstOrFail();

        $attendances = TilawatiAttendance::with('student')
            ->where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->get();

        return view('tilawati.print_session', compact('group', 'session', 'attendances', 'date'));
    }

    /**
     * Cetak Lembar Rekap Bulanan
     */
    public function printMonthly(Request $request, MentoringGroup $group)
    {
        self::ensureTableExists();
        $this->checkGroupAccess($group);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $request->input('year_id', $activeYear?->id);
        $month = (int) $request->input('month', date('n'));
        $year = (int) $request->input('year', date('Y'));

        $students = $group->students()->orderBy('class')->orderBy('name')->get();
        $sessions = TilawatiSession::where('mentoring_group_id', $group->id)
            ->whereMonth('session_date', $month)
            ->whereYear('session_date', $year)
            ->orderBy('session_date')
            ->get();

        $sessionDates = $sessions->pluck('session_date')->map(fn($d) => $d->format('Y-m-d'))->toArray();
        $attendances = TilawatiAttendance::where('mentoring_group_id', $group->id)
            ->whereIn('session_date', $sessionDates)
            ->get()
            ->groupBy('student_id');

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('tilawati.print_monthly', compact(
            'group', 'students', 'sessions', 'attendances', 'month', 'year', 'monthNames'
        ));
    }
}
