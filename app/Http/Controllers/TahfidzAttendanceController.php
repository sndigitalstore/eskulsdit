<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\MentoringGroup;
use App\Models\Student;
use App\Models\TahfidzAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TahfidzAttendanceController extends Controller
{
    /**
     * Auto-create table if not exists (self-healing)
     */
    private function ensureTableExists(): void
    {
        if (!Schema::hasTable('tahfidz_attendances')) {
            Schema::create('tahfidz_attendances', function ($table) {
                $table->id();
                $table->unsignedBigInteger('mentoring_group_id');
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('academic_year_id');
                $table->date('session_date');
                $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha'])->default('hadir');
                $table->string('capaian')->nullable();
                $table->string('hafalan_status')->default('LULUS')->nullable();
                $table->string('tugas_besok')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('recorded_by')->nullable();
                $table->timestamps();
                $table->unique(['mentoring_group_id', 'student_id', 'session_date'], 'unique_tahfidz_attendance');
                $table->index(['mentoring_group_id', 'session_date']);
            });
        } else {
            if (!Schema::hasColumn('tahfidz_attendances', 'capaian')) {
                Schema::table('tahfidz_attendances', function ($table) {
                    $table->string('capaian')->nullable()->after('status');
                });
            }
            if (!Schema::hasColumn('tahfidz_attendances', 'hafalan_status')) {
                Schema::table('tahfidz_attendances', function ($table) {
                    $table->string('hafalan_status')->default('LULUS')->nullable()->after('capaian');
                });
            }
            if (!Schema::hasColumn('tahfidz_attendances', 'tugas_besok')) {
                Schema::table('tahfidz_attendances', function ($table) {
                    $table->string('tugas_besok')->nullable()->after('hafalan_status');
                });
            }
        }
    }

    /**
     * Daftar sesi absensi untuk satu kelompok
     */
    public function index(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId     = $request->input('year_id', $activeYear?->id);

        // Ambil semua tanggal sesi unik
        $sessions = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->selectRaw('session_date, COUNT(*) as total_siswa,
                SUM(status = "hadir") as hadir,
                SUM(status = "sakit") as sakit,
                SUM(status = "izin") as izin,
                SUM(status = "alpha") as alpha')
            ->groupBy('session_date')
            ->orderBy('session_date', 'desc')
            ->get();

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $selectedYear  = AcademicYear::find($yearId);
        $totalSiswa    = $group->students()->count();

        return view('tahfidz.attendance_index', compact(
            'group', 'sessions', 'academicYears', 'selectedYear', 'yearId', 'totalSiswa'
        ));
    }

    /**
     * Form input absensi sesi baru / edit sesi yang ada
     */
    public function create(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId     = $request->input('year_id', $activeYear?->id);
        $date       = $request->input('date', date('Y-m-d'));

        // Ambil siswa kelompok
        $students = $group->students()->orderBy('class')->orderBy('name')->get();

        // Cek apakah sudah ada absensi di tanggal ini
        $existing = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->get()
            ->keyBy('student_id');

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        // Deteksi default juz yang diampu kelompok
        $defaultJuz = 30;
        $combinedText = ($group->target_level ?? '') . ' ' . ($group->name ?? '');

        // 1. Cek pola seperti 'Juz 29', 'Juz. 29', 'Juz29'
        if (preg_match('/(?:Juz[.\s]*)(\d+)/i', $combinedText, $m)) {
            $defaultJuz = (int) $m[1];
        } 
        // 2. Cek jika target_level berisi angka murni 1-30 (misal diinput '29' atau '28')
        elseif (preg_match('/\b([1-9]|[12]\d|30)\b/', $group->target_level ?? '', $m)) {
            $defaultJuz = (int) $m[1];
        } else {
            // 3. Periksa dari riwayat capaian hafalan terbaru kelompok ini jika ada
            $recentCapaian = TahfidzAttendance::where('mentoring_group_id', $group->id)
                ->whereNotNull('capaian')
                ->latest('id')
                ->take(20)
                ->pluck('capaian')
                ->join(' ');
            if (preg_match('/(Hasyr|Talaq|Jumu|Munafiq|Taghabun|Mumtahan|Mujadil|Tahrim|Saff)/i', $recentCapaian)) {
                $defaultJuz = 28;
            } elseif (preg_match('/(Mulk|Qalam|Haqqah|Ma\'arij|Nuh|Jinn|Muzzammil|Muddassir|Qiyamah|Insan|Mursalat)/i', $recentCapaian)) {
                $defaultJuz = 29;
            } elseif (preg_match('/(Naba|Nazi|Abasa|Takwir|Infitar|Mutaffif|Insyiqaq|Buruj|Tariq|Ala|Ghasyiyah|Fajr|Balad|Syams|Lail|Duha|Syarh|Tin|Alaq|Qadr|Bayyinah|Zalzalah|Adiyat|Qariah|Takasur|Asr|Humazah|Fil|Quraisy|Maun|Kausar|Kafirun|Nasr|Masad|Ikhlas|Falaq|Nas)/i', $recentCapaian)) {
                $defaultJuz = 30;
            }
        }

        return view('tahfidz.attendance_form', compact(
            'group', 'students', 'existing', 'date', 'yearId', 'academicYears', 'defaultJuz'
        ));
    }

    /**
     * Simpan / update absensi sesi (upsert per siswa)
     */
    public function store(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $request->validate([
            'session_date'                => 'required|date',
            'academic_year_id'            => 'required|exists:academic_years,id',
            'attendance'                  => 'required|array',
            'attendance.*.status'         => 'required|in:hadir,sakit,izin,alpha',
            'attendance.*.capaian'        => 'nullable|string|max:255',
            'attendance.*.hafalan_status' => 'nullable|string|max:50',
            'attendance.*.tugas_besok'    => 'nullable|string|max:255',
            'attendance.*.notes'          => 'nullable|string|max:500',
        ]);

        $date   = $request->session_date;
        $yearId = $request->academic_year_id;
        $userId = Auth::id();

        foreach ($request->attendance as $studentId => $data) {
            $capaian = trim($data['capaian'] ?? '');
            $tugas   = trim($data['tugas_besok'] ?? '');
            $notes   = trim($data['notes'] ?? '');

            TahfidzAttendance::updateOrCreate(
                [
                    'mentoring_group_id' => $group->id,
                    'student_id'         => $studentId,
                    'session_date'       => $date,
                ],
                [
                    'academic_year_id' => $yearId,
                    'status'           => $data['status'],
                    'capaian'          => $capaian !== '' ? $capaian : null,
                    'hafalan_status'   => $data['hafalan_status'] ?? 'LULUS',
                    'tugas_besok'      => $tugas !== '' ? $tugas : null,
                    'notes'            => $notes !== '' ? $notes : null,
                    'recorded_by'      => $userId,
                ]
            );
        }

        return redirect()
            ->route('tahfidz.attendance.session', [
                'group'      => $group->id,
                'date'       => $date,
                'year_id'    => $yearId,
                'open_share' => 1
            ])
            ->with('success', "Capaian mentoring & absensi sesi tanggal " . \Carbon\Carbon::parse($date)->isoFormat('D MMMM Y') . " berhasil disimpan! Format WhatsApp siap dibagikan.");
    }

    /**
     * Hapus seluruh absensi satu sesi (berdasarkan tanggal)
     */
    public function destroySession(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $date = $request->input('date');
        TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->delete();

        return redirect()
            ->route('tahfidz.attendance.index', ['group' => $group->id])
            ->with('success', "Absensi sesi " . date('d/m/Y', strtotime($date)) . " dihapus.");
    }

    /**
     * Detail absensi satu sesi
     */
    public function showSession(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $date = $request->input('date');
        $records = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->with('student')
            ->get()
            ->sortBy(fn($r) => $r->student->class . $r->student->name)
            ->values();

        $yearId          = $records->first()?->academic_year_id;
        $academicYears   = AcademicYear::orderBy('name', 'desc')->get();
        $whatsappMessage = self::generateWhatsappMessage($group, $date, $records);
        $openShare       = (bool) $request->query('open_share', false);

        return view('tahfidz.attendance_session', compact(
            'group', 'records', 'date', 'yearId', 'academicYears', 'whatsappMessage', 'openShare'
        ));
    }

    /**
     * Helper untuk format pesan WhatsApp laporan capaian hafalan mentoring tahfidz
     */
    public static function generateWhatsappMessage(MentoringGroup $group, string $date, $records): string
    {
        $formattedDate = \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM Y');

        $msg = "Assalamu'alaikum Warahmatullahi Wabarakatuh 🌙\n\n";
        $msg .= "Ayah dan Bunda yang kami hormati,\n\n";
        $msg .= "Alhamdulillah, dengan puji syukur kepada Allah SWT, berikut kami sampaikan laporan capaian hafalan putra-putri tercinta.\n\n";
        $msg .= "📅 *Tanggal:* " . $formattedDate . "\n\n";
        $msg .= "━━━━━━━━━━━━━━━━\n\n";

        $no = 1;
        foreach ($records as $rec) {
            $student = $rec->student;
            $name = $student ? $student->name : 'Siswa';
            $class = $student ? $student->class : '-';

            $msg .= "*{$no}. {$name}* (Kelas {$class})\n";

            if ($rec->status !== 'hadir') {
                $statusLabel = match($rec->status) {
                    'sakit' => '🤒 Sakit',
                    'izin'  => '📄 Izin',
                    'alpha' => '❌ Tidak Hadir (Alpha)',
                    default => ucfirst($rec->status)
                };
                $msg .= "📌 *Status Kehadiran:* {$statusLabel}\n";
                if (!empty($rec->notes)) {
                    $msg .= "💭 *Catatan Guru:* {$rec->notes}\n";
                }
            } else {
                $capaian = !empty($rec->capaian) ? $rec->capaian : '-';
                $isLulus = (strtoupper($rec->hafalan_status ?? 'LULUS') === 'LULUS');
                $statusStr = $isLulus ? "✅ *Status:* ✓ LULUS" : "❌ *Status:* ✗ BELUM LULUS";
                $tugas = !empty($rec->tugas_besok) ? $rec->tugas_besok : '-';
                $notes = !empty($rec->notes) ? $rec->notes : ($isLulus ? 'Barakallah..' : 'Lancarkan lagi ya sholih/sholihah..');

                $msg .= "📖 *Capaian Hari Ini:*\n";
                $msg .= "{$capaian}\n\n";
                $msg .= "{$statusStr}\n\n";
                $msg .= "📝 *Tugas Besok:*\n";
                $msg .= "{$tugas}\n";
                $msg .= "💭 *Catatan Guru:* {$notes}\n";
            }

            $msg .= "\n━━━━━━━━━━━━━━━━\n\n";
            $no++;
        }

        $msg .= "Mari kita terus mendoakan dan membimbing putra-putri kita dalam perjalanan menghafal Al-Qur'an. Setiap ayat yang dihafal adalah investasi untuk masa depan yang cerah dan amal ibadah yang berkelanjutan.\n\n";
        $msg .= "Terima kasih atas kepercayaan dan kerja sama\n\n";
        $msg .= "Semoga Allah SWT memberkahi setiap usaha kita, memudahkan hafalan putra-putri kita, dan menjadikan Al-Qur'an sebagai cahaya di hati dan amal ibadah di akhirat kelak. 🤲\n\n";
        $msg .= "Wassalamu'alaikum Warahmatullahi Wabarakatuh 🌟\n\n";
        $msg .= "_SDIT AN NADZIR_";

        return $msg;
    }

    /**
     * Print absensi satu sesi
     */
    public function printSession(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $date = $request->input('date');
        $records = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->with('student')
            ->get()
            ->sortBy(fn($r) => $r->student->class . $r->student->name)
            ->values();

        $yearId   = $records->first()?->academic_year_id;
        $yearName = AcademicYear::find($yearId)?->name ?? '-';

        return view('tahfidz.print_attendance', compact(
            'group', 'records', 'date', 'yearName'
        ));
    }

    /**
     * Print rekap absensi seluruh sesi dalam satu kelompok
     */
    public function printRecap(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId     = $request->input('year_id', $activeYear?->id);
        $yearName   = AcademicYear::find($yearId)?->name ?? '-';

        // Ambil semua tanggal sesi
        $sessionDates = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->distinct()
            ->orderBy('session_date')
            ->pluck('session_date');

        // Ambil semua siswa kelompok
        $students = $group->students()->orderBy('class')->orderBy('name')->get();

        // Semua absensi untuk lookup
        $allAttendances = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->get()
            ->groupBy('student_id');

        return view('tahfidz.print_attendance_recap', compact(
            'group', 'students', 'sessionDates', 'allAttendances', 'yearName', 'yearId'
        ));
    }
}
