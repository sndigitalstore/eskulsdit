<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\MentoringGroup;
use App\Models\Student;
use App\Models\TahfidzAttendance;
use App\Models\TahfidzRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TahfidzAttendanceController extends Controller
{
    /**
     * Map nama surah (lowercase cleaned) ke nomor Juz Al-Qur'an
     */
    private static function getSurahJuzMap(): array
    {
        return [
            // Juz 30 (Surat ke-78 s.d 114)
            'annaba' => 30, 'annaziat' => 30, 'abasa' => 30, 'attakwir' => 30,
            'alinfitar' => 30, 'almuthaffifin' => 30, 'almutaffifin' => 30, 'alinsyiqaq' => 30,
            'alburuj' => 30, 'attariq' => 30, 'alala' => 30, 'alghasyiyah' => 30,
            'alfajr' => 30, 'albalad' => 30, 'asysyams' => 30, 'allail' => 30,
            'adduha' => 30, 'asysyarh' => 30, 'attin' => 30, 'alalaq' => 30,
            'alqadr' => 30, 'albayyinah' => 30, 'azzalzalah' => 30, 'aladiyat' => 30,
            'alqariah' => 30, 'attakatsur' => 30, 'attakasur' => 30, 'alasr' => 30,
            'alhumazah' => 30, 'alfil' => 30, 'quraisy' => 30, 'almaun' => 30,
            'alkausar' => 30, 'alkafirun' => 30, 'annasr' => 30, 'allahab' => 30,
            'almasad' => 30, 'alikhlas' => 30, 'alfalaq' => 30, 'annas' => 30,
            
            // Juz 29 (Surat ke-67 s.d 77)
            'almulk' => 29, 'alqalam' => 29, 'alhaqqah' => 29, 'almaarij' => 29,
            'nuh' => 29, 'aljinn' => 29, 'almuzzammil' => 29, 'almuzammil' => 29,
            'almuddassir' => 29, 'alqiyamah' => 29, 'alinsan' => 29, 'almursalat' => 29,
            
            // Juz 28 (Surat ke-58 s.d 66)
            'almujadilah' => 28, 'alhasyr' => 28, 'almumtahanah' => 28, 'assaff' => 28,
            'aljumuah' => 28, 'almunafiqun' => 28, 'attaghabun' => 28, 'attalaq' => 28,
            'attahrim' => 28,
            
            // Juz 27
            'azzariyat' => 27, 'attur' => 27, 'annajm' => 27, 'alqamar' => 27,
            'arrahman' => 27, 'alwaqiah' => 27, 'alhadid' => 27,
            
            // Juz 26
            'alahqaf' => 26, 'muhammad' => 26, 'alfath' => 26, 'alhujurat' => 26, 'qaf' => 26,
            
            // Juz 1-25
            'alfatihah' => 1, 'albaqarah' => 2, 'aliimran' => 3, 'annisa' => 4,
            'almaidah' => 6, 'alanam' => 7, 'alaraf' => 8, 'alanfal' => 9,
            'attaubah' => 10, 'yunus' => 11, 'hud' => 12, 'yusuf' => 12,
            'arraid' => 13, 'ibrahim' => 13, 'alhijr' => 14, 'annahl' => 14,
            'alisra' => 15, 'alkahf' => 15, 'maryam' => 16, 'thaha' => 16,
            'alanbiya' => 17, 'alhajj' => 17, 'almuminun' => 18, 'annur' => 18,
            'alfurqan' => 18, 'asysyuara' => 19, 'annaml' => 19, 'alqasas' => 20,
            'alankabut' => 20, 'arrum' => 21, 'luqman' => 21, 'assajdah' => 21,
            'alahzab' => 21, 'saba' => 22, 'fatir' => 22, 'yasin' => 22,
            'assaffat' => 23, 'sad' => 23, 'azzumar' => 23, 'ghafir' => 24,
            'fussilat' => 24, 'asysyura' => 25, 'azzukhruf' => 25, 'addukhan' => 25,
            'aljasiyah' => 25,
        ];
    }

    /**
     * Tentukan nomor Juz berdasarkan nama surat
     */
    public static function determineJuzForSurah(string $surahName, ?int $defaultJuz = 30): int
    {
        $clean = strtolower(preg_replace('/[^a-z0-9]/', '', $surahName));
        $clean = preg_replace('/^(?:surat|surah|qs)/', '', $clean);
        
        $map = self::getSurahJuzMap();
        if (isset($map[$clean])) {
            return $map[$clean];
        }

        foreach ($map as $key => $juz) {
            if (str_contains($clean, $key) || str_contains($key, $clean)) {
                return $juz;
            }
        }

        return $defaultJuz ?? 30;
    }

    /**
     * Parse teks capaian menjadi surah_name, start_ayah, end_ayah, juz
     */
    public static function parseCapaian(string $capaian, ?int $defaultJuz = 30): array
    {
        $raw = trim($capaian);
        $text = preg_replace('/^(?:QS\.?|Surat|Surah)\s+/i', '', $raw);
        $text = trim($text);

        $startAyah = null;
        $endAyah   = null;
        $surahName = $text;

        if (preg_match('/(?:ayat|:)\s*(\d+)\s*(?:[-–]|s\.?d\.?)\s*(\d+)/i', $text, $m)) {
            $startAyah = (int) $m[1];
            $endAyah   = (int) $m[2];
            $surahName = trim(preg_replace('/(?:ayat|:)\s*\d+\s*(?:[-–]|s\.?d\.?)\s*\d+.*/i', '', $text));
        } elseif (preg_match('/(?:ayat|:)\s*(\d+)/i', $text, $m)) {
            $startAyah = (int) $m[1];
            $endAyah   = (int) $m[1];
            $surahName = trim(preg_replace('/(?:ayat|:)\s*\d+.*/i', '', $text));
        } elseif (preg_match('/^(.*?)\s+(\d+)\s*[-–]\s*(\d+)$/i', $text, $m)) {
            $surahName = trim($m[1]);
            $startAyah = (int) $m[2];
            $endAyah   = (int) $m[3];
        } elseif (preg_match('/^(.*?)\s+(\d+)$/i', $text, $m) && !preg_match('/juz\s+\d+/i', $text)) {
            $surahName = trim($m[1]);
            $startAyah = (int) $m[2];
            $endAyah   = (int) $m[2];
        }

        $surahName = trim($surahName, " :-,");
        if (empty($surahName)) {
            $surahName = $raw;
        }

        $juz = self::determineJuzForSurah($surahName, $defaultJuz);

        return [
            'surah_name' => $surahName,
            'start_ayah' => $startAyah,
            'end_ayah'   => $endAyah,
            'juz'        => $juz,
        ];
    }

    /**
     * Sinkronisasi data lama dari tahfidz_attendances ke tahfidz_records
     * agar riwayat absensi lampau otomatis masuk ke rapor dan kartu hafalan.
     */
    public static function syncPastAttendances(): void
    {
        if (!Schema::hasTable('tahfidz_attendances') || !Schema::hasTable('tahfidz_records')) {
            return;
        }

        $unsynced = TahfidzAttendance::where('status', 'hadir')
            ->whereNotNull('capaian')
            ->where('capaian', '!=', '')
            ->get();

        foreach ($unsynced as $att) {
            $exists = TahfidzRecord::where('student_id', $att->student_id)
                ->where('mentoring_group_id', $att->mentoring_group_id)
                ->where('session_date', $att->session_date)
                ->exists();

            if (!$exists) {
                $parsed = self::parseCapaian($att->capaian);
                $isLulus = (strtoupper($att->hafalan_status ?? 'LULUS') === 'LULUS');

                $fullNotes = $att->notes ?? '';
                if ($att->tugas_besok) {
                    $fullNotes = $fullNotes !== '' ? "{$fullNotes} (Tugas besok: {$att->tugas_besok})" : "Tugas besok: {$att->tugas_besok}";
                }

                TahfidzRecord::create([
                    'student_id'         => $att->student_id,
                    'mentoring_group_id' => $att->mentoring_group_id,
                    'academic_year_id'   => $att->academic_year_id,
                    'surah_name'         => $parsed['surah_name'],
                    'juz'                => $parsed['juz'],
                    'start_ayah'         => $parsed['start_ayah'],
                    'end_ayah'           => $parsed['end_ayah'],
                    'jumlah_baris'       => $att->jumlah_baris ?? 0,
                    'type'               => 'setoran_baru',
                    'quality'            => $isLulus ? 'lancar' : 'perlu_perbaikan',
                    'nilai'              => $att->nilai ?? null,
                    'session_date'       => $att->session_date,
                    'notes'              => $fullNotes !== '' ? $fullNotes : null,
                    'recorded_by'        => $att->recorded_by,
                ]);
            }
        }
    }

    protected static bool $tableChecked = false;

    /**
     * Auto-create table if not exists (self-healing)
     */
    public static function ensureTableExists(): void
    {
        if (self::$tableChecked) {
            return;
        }
        self::$tableChecked = true;

        try {
            if (!Schema::hasTable('tahfidz_attendances')) {
                Schema::create('tahfidz_attendances', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('mentoring_group_id');
                    $table->unsignedBigInteger('student_id');
                    $table->unsignedBigInteger('academic_year_id');
                    $table->date('session_date');
                    $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha'])->default('hadir');
                    $table->string('capaian')->nullable();
                    $table->unsignedSmallInteger('jumlah_baris')->nullable()->default(0);
                    $table->string('hafalan_status')->default('LULUS')->nullable();
                    $table->string('nilai', 50)->nullable();
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
                if (!Schema::hasColumn('tahfidz_attendances', 'jumlah_baris')) {
                    Schema::table('tahfidz_attendances', function ($table) {
                        $table->unsignedSmallInteger('jumlah_baris')->nullable()->default(0)->after('capaian');
                    });
                }
                if (!Schema::hasColumn('tahfidz_attendances', 'hafalan_status')) {
                    Schema::table('tahfidz_attendances', function ($table) {
                        $table->string('hafalan_status')->default('LULUS')->nullable()->after('jumlah_baris');
                    });
                }
                if (!Schema::hasColumn('tahfidz_attendances', 'nilai')) {
                    Schema::table('tahfidz_attendances', function ($table) {
                        $table->string('nilai', 50)->nullable()->after('hafalan_status');
                    });
                }
                if (!Schema::hasColumn('tahfidz_attendances', 'tugas_besok')) {
                    Schema::table('tahfidz_attendances', function ($table) {
                        $table->string('tugas_besok')->nullable()->after('nilai');
                    });
                }
            }

            if (Schema::hasTable('tahfidz_records') && !Schema::hasColumn('tahfidz_records', 'nilai')) {
                Schema::table('tahfidz_records', function ($table) {
                    $table->string('nilai', 50)->nullable()->after('quality');
                });
            }
        } catch (\Throwable $e) {
            // Silently continue if schema modification fails due to privileges/locks
        }

        // Jalankan auto-sync bila ada data riwayat yang belum sinkron
        try {
            self::syncPastAttendances();
        } catch (\Throwable $e) {
            // Silently continue
        }
    }

    /**
     * Daftar seluruh 114 Surat dalam Al-Qur'an secara berurutan
     */
    public static function getAllSurahNames(): array
    {
        return [
            'Al-Fatihah', 'Al-Baqarah', 'Ali \'Imran', 'An-Nisa\'', 'Al-Ma\'idah',
            'Al-An\'am', 'Al-A\'raf', 'Al-Anfal', 'At-Taubah', 'Yunus',
            'Hud', 'Yusuf', 'Ar-Ra\'d', 'Ibrahim', 'Al-Hijr',
            'An-Nahl', 'Al-Isra\'', 'Al-Kahf', 'Maryam', 'Thaha',
            'Al-Anbiya\'', 'Al-Hajj', 'Al-Mu\'minun', 'An-Nur', 'Al-Furqan',
            'Asy-Syu\'ara\'', 'An-Naml', 'Al-Qasas', 'Al-\'Ankabut', 'Ar-Rum',
            'Luqman', 'As-Sajdah', 'Al-Ahzab', 'Saba\'', 'Fatir',
            'Yasin', 'As-Saffat', 'Sad', 'Az-Zumar', 'Ghafir',
            'Fussilat', 'Asy-Syura', 'Az-Zukhruf', 'Ad-Dukhan', 'Al-Jasiyah',
            'Al-Ahqaf', 'Muhammad', 'Al-Fath', 'Al-Hujurat', 'Qaf',
            'Az-Zariyat', 'At-Tur', 'An-Najm', 'Al-Qamar', 'Ar-Rahman',
            'Al-Waqi\'ah', 'Al-Hadid', 'Al-Mujadilah', 'Al-Hasyr', 'Al-Mumtahanah',
            'As-Saff', 'Al-Jumu\'ah', 'Al-Munafiqun', 'At-Taghabun', 'At-Talaq',
            'At-Tahrim', 'Al-Mulk', 'Al-Qalam', 'Al-Haqqah', 'Al-Ma\'arij',
            'Nuh', 'Al-Jinn', 'Al-Muzzammil', 'Al-Muddassir', 'Al-Qiyamah',
            'Al-Insan', 'Al-Mursalat', 'An-Naba\'', 'An-Nazi\'at', '\'Abasa',
            'At-Takwir', 'Al-Infitar', 'Al-Mutaffifin', 'Al-Insyiqaq', 'Al-Buruj',
            'At-Tariq', 'Al-A\'la', 'Al-Ghasyiyah', 'Al-Fajr', 'Al-Balad',
            'Asy-Syams', 'Al-Lail', 'Ad-Duha', 'Asy-Syarh', 'At-Tin',
            'Al-\'Alaq', 'Al-Qadr', 'Al-Bayyinah', 'Az-Zalzalah', 'Al-\'Adiyat',
            'Al-Qari\'ah', 'At-Takasur', 'Al-\'Asr', 'Al-Humazah', 'Al-Fil',
            'Quraisy', 'Al-Ma\'un', 'Al-Kausar', 'Al-Kafirun', 'An-Nasr',
            'Al-Lahab', 'Al-Ikhlas', 'Al-Falaq', 'An-Nas'
        ];
    }

    /**
     * Ekstraksi target Juz dari string target_level (misal: "Juz 29 dan 28", "Juz 30", "28, 29")
     */
    public static function extractTargetJuz(?string $targetText): array
    {
        if (empty($targetText)) {
            return [30];
        }

        $list = [];
        if (preg_match_all('/(?:juz\s*)(\d+)/i', $targetText, $m) && !empty($m[1])) {
            foreach ($m[1] as $val) {
                $n = (int) $val;
                if ($n >= 1 && $n <= 30 && !in_array($n, $list)) {
                    $list[] = $n;
                }
            }
        }
        if (preg_match_all('/\b([1-9]|[12][0-9]|30)\b/', $targetText, $m) && !empty($m[1])) {
            foreach ($m[1] as $val) {
                $n = (int) $val;
                if ($n >= 1 && $n <= 30 && !in_array($n, $list)) {
                    $list[] = $n;
                }
            }
        }

        return !empty($list) ? $list : [30];
    }

    /**
     * Daftar seluruh 114 Surat dalam Al-Qur'an dikelompokkan berdasarkan nomor Juz
     */
    public static function getAllJuzSurahs(): array
    {
        return [
            30 => [
                'An-Naba\'', 'An-Nazi\'at', '\'Abasa', 'At-Takwir', 'Al-Infitar',
                'Al-Mutaffifin', 'Al-Insyiqaq', 'Al-Buruj', 'At-Tariq', 'Al-A\'la',
                'Al-Ghasyiyah', 'Al-Fajr', 'Al-Balad', 'Asy-Syams', 'Al-Lail',
                'Ad-Duha', 'Asy-Syarh', 'At-Tin', 'Al-\'Alaq', 'Al-Qadr',
                'Al-Bayyinah', 'Az-Zalzalah', 'Al-\'Adiyat', 'Al-Qari\'ah', 'At-Takasur',
                'Al-\'Asr', 'Al-Humazah', 'Al-Fil', 'Quraisy', 'Al-Ma\'un',
                'Al-Kausar', 'Al-Kafirun', 'An-Nasr', 'Al-Lahab', 'Al-Ikhlas',
                'Al-Falaq', 'An-Nas'
            ],
            29 => [
                'Al-Mulk', 'Al-Qalam', 'Al-Haqqah', 'Al-Ma\'arij', 'Nuh',
                'Al-Jinn', 'Al-Muzzammil', 'Al-Muddassir', 'Al-Qiyamah', 'Al-Insan',
                'Al-Mursalat'
            ],
            28 => [
                'Al-Mujadilah', 'Al-Hasyr', 'Al-Mumtahanah', 'As-Saff', 'Al-Jumu\'ah',
                'Al-Munafiqun', 'At-Taghabun', 'At-Talaq', 'At-Tahrim'
            ],
            27 => [
                'Az-Zariyat', 'At-Tur', 'An-Najm', 'Al-Qamar', 'Ar-Rahman', 'Al-Waqi\'ah', 'Al-Hadid'
            ],
            26 => [
                'Al-Ahqaf', 'Muhammad', 'Al-Fath', 'Al-Hujurat', 'Qaf'
            ],
            25 => [
                'Fussilat', 'Asy-Syura', 'Az-Zukhruf', 'Ad-Dukhan', 'Al-Jasiyah'
            ],
            24 => [
                'Az-Zumar', 'Ghafir'
            ],
            23 => [
                'Yasin', 'As-Saffat', 'Sad'
            ],
            22 => [
                'Al-Ahzab', 'Saba\'', 'Fatir'
            ],
            21 => [
                'Al-Anbiya\'', 'Al-Hajj', 'Luqman', 'As-Sajdah', 'Ar-Rum'
            ],
            20 => [
                'An-Naml', 'Al-Qasas', 'Al-\'Ankabut'
            ],
            19 => [
                'Al-Furqan', 'Asy-Syu\'ara\''
            ],
            18 => [
                'Al-Kahf', 'Maryam', 'Thaha', 'Al-Mu\'minun', 'An-Nur'
            ],
            17 => [
                'Al-Isra\''
            ],
            16 => [
                'Al-Kahf'
            ],
            15 => [
                'Al-Hijr', 'An-Nahl'
            ],
            14 => [
                'Al-Hijr', 'An-Nahl'
            ],
            13 => [
                'Yusuf', 'Ar-Ra\'d', 'Ibrahim'
            ],
            12 => [
                'Hud', 'Yusuf'
            ],
            11 => [
                'Yunus', 'Hud'
            ],
            10 => [
                'Al-Anfal', 'At-Taubah'
            ],
            9 => [
                'Al-A\'raf', 'Al-Anfal'
            ],
            8 => [
                'Al-An\'am', 'Al-A\'raf'
            ],
            7 => [
                'Al-Ma\'idah', 'Al-An\'am'
            ],
            6 => [
                'An-Nisa\'', 'Al-Ma\'idah'
            ],
            5 => [
                'An-Nisa\''
            ],
            4 => [
                'Ali \'Imran', 'An-Nisa\''
            ],
            3 => [
                'Al-Baqarah', 'Ali \'Imran'
            ],
            2 => [
                'Al-Baqarah'
            ],
            1 => [
                'Al-Fatihah', 'Al-Baqarah'
            ],
        ];
    }

    private function checkGroupAccess(MentoringGroup $group, bool $mustBeMentor = false): void
    {
        $user = Auth::user();
        if ($user->role === 'teacher') {
            $isMentor = ($group->mentor_user_id == $user->id) || (trim(strtolower($group->mentor_name)) == trim(strtolower($user->name)));
            if ($mustBeMentor) {
                if (!$isMentor) {
                    abort(403, 'Akses ditolak. Anda bukan pembimbing halaqah kelompok ini.');
                }
            } else {
                $isHomeroom = !empty($user->homeroom_class);
                $hasStudentInClass = false;
                if ($isHomeroom) {
                    $hasStudentInClass = $group->students()->where('class', $user->homeroom_class)->exists();
                }
                if (!$isMentor && !$hasStudentInClass) {
                    abort(403, 'Akses ditolak. Anda tidak memiliki akses ke kelompok Tahfidz ini.');
                }
            }
        }
    }

    /**
     * Daftar sesi absensi untuk satu kelompok
     */
    public function index(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();
        $this->checkGroupAccess($group);

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
        $this->checkGroupAccess($group, true);

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
     * Simpan / update absensi sesi (upsert per siswa) dan SINKRONISASI OTOMATIS ke TahfidzRecord
     */
    public function store(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();
        $this->checkGroupAccess($group, true);

        $request->validate([
            'session_date'                => 'required|date',
            'academic_year_id'            => 'required|exists:academic_years,id',
            'attendance'                  => 'required|array',
            'attendance.*.status'         => 'required|in:hadir,sakit,izin,alpha',
            'attendance.*.surah_name'     => 'nullable|string|max:100',
            'attendance.*.ayat'           => 'nullable|string|max:100',
            'attendance.*.capaian'        => 'nullable|string|max:255',
            'attendance.*.nilai'          => 'nullable|string|max:50',
            'attendance.*.jumlah_baris'   => 'nullable|integer|min:0|max:500',
            'attendance.*.hafalan_status' => 'nullable|string|max:50',
            'attendance.*.tugas_besok'    => 'nullable|string|max:255',
            'attendance.*.notes'          => 'nullable|string|max:500',
        ]);

        $date   = $request->session_date;
        $yearId = $request->academic_year_id;
        $userId = Auth::id();

        foreach ($request->attendance as $studentId => $data) {
            $surahNameInput = trim($data['surah_name'] ?? '');
            $ayatInput      = trim($data['ayat'] ?? '');
            $capaianInput   = trim($data['capaian'] ?? '');

            if ($surahNameInput !== '') {
                $ayatPart = $ayatInput !== '' ? (preg_match('/^(?:ayat|:)/i', $ayatInput) ? ' ' . $ayatInput : ' ayat ' . $ayatInput) : '';
                $capaian  = trim("Surat {$surahNameInput}{$ayatPart}");
            } else {
                $capaian  = $capaianInput;
            }

            $nilai       = isset($data['nilai']) && trim($data['nilai']) !== '' ? trim($data['nilai']) : null;
            $jumlahBaris = isset($data['jumlah_baris']) && $data['jumlah_baris'] !== '' ? max(0, (int)$data['jumlah_baris']) : 0;
            $tugas       = trim($data['tugas_besok'] ?? '');
            $notes       = trim($data['notes'] ?? '');
            $status      = $data['status'];
            $hafalanStatus = $data['hafalan_status'] ?? 'LULUS';

            TahfidzAttendance::updateOrCreate(
                [
                    'mentoring_group_id' => $group->id,
                    'student_id'         => $studentId,
                    'session_date'       => $date,
                ],
                [
                    'academic_year_id' => $yearId,
                    'status'           => $status,
                    'capaian'          => $capaian !== '' ? $capaian : null,
                    'jumlah_baris'     => $jumlahBaris,
                    'hafalan_status'   => $hafalanStatus,
                    'nilai'            => $nilai,
                    'tugas_besok'      => $tugas !== '' ? $tugas : null,
                    'notes'            => $notes !== '' ? $notes : null,
                    'recorded_by'      => $userId,
                ]
            );

            // SINKRONISASI OTOMATIS KE TAHFIDZ_RECORDS (REKAPAN, RAPOR, KARTU HAFALAN, RUANG HALAQAH)
            if ($status === 'hadir' && $capaian !== '') {
                $parsed = self::parseCapaian($capaian);
                $isLulus = (strtoupper($hafalanStatus) === 'LULUS');

                $fullNotes = $notes;
                if ($tugas !== '') {
                    $fullNotes = $fullNotes !== '' ? "{$fullNotes} (Tugas besok: {$tugas})" : "Tugas besok: {$tugas}";
                }

                TahfidzRecord::updateOrCreate(
                    [
                        'student_id'         => $studentId,
                        'mentoring_group_id' => $group->id,
                        'session_date'       => $date,
                    ],
                    [
                        'academic_year_id' => $yearId,
                        'surah_name'       => $parsed['surah_name'],
                        'juz'              => $parsed['juz'],
                        'start_ayah'       => $parsed['start_ayah'],
                        'end_ayah'         => $parsed['end_ayah'],
                        'jumlah_baris'     => $jumlahBaris,
                        'type'             => 'setoran_baru',
                        'quality'          => $isLulus ? 'lancar' : 'perlu_perbaikan',
                        'nilai'            => $nilai,
                        'notes'            => $fullNotes !== '' ? $fullNotes : null,
                        'recorded_by'      => $userId,
                    ]
                );
            } else {
                // Jika siswa tidak hadir atau capaian kosong, hapus record setoran pada tanggal ini jika ada
                TahfidzRecord::where('student_id', $studentId)
                    ->where('mentoring_group_id', $group->id)
                    ->where('session_date', $date)
                    ->delete();
            }
        }

        if ($request->input('redirect_to') === 'workspace' || !in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum'])) {
            return redirect()
                ->route('tahfidz-groups.index', [
                    'group_id'   => $group->id,
                    'date'       => $date,
                    'open_share' => 1
                ])
                ->with('success', "Alhamdulillah, absensi & setoran sesi tanggal " . \Carbon\Carbon::parse($date)->locale('id')->isoFormat('D MMMM Y') . " berhasil disimpan dan tersinkron!");
        }

        return redirect()
            ->route('tahfidz.attendance.session', [
                'group'      => $group->id,
                'date'       => $date,
                'year_id'    => $yearId,
                'open_share' => 1
            ])
            ->with('success', "Capaian mentoring & absensi sesi tanggal " . \Carbon\Carbon::parse($date)->isoFormat('D MMMM Y') . " berhasil disimpan dan otomatis tersinkron ke Rekapan, Rapor & Ruang Halaqah!");
    }

    /**
     * Hapus seluruh absensi satu sesi (berdasarkan tanggal) beserta setoran yang tersinkron
     */
    public function destroySession(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();
        $this->checkGroupAccess($group, true);

        $date = $request->input('date');
        if (!$date) {
            return redirect()
                ->route('tahfidz.attendance.index', ['group' => $group->id])
                ->with('error', 'Tanggal sesi tidak ditemukan atau tidak valid.');
        }

        TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->delete();

        TahfidzRecord::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->delete();

        return redirect()
            ->route('tahfidz.attendance.index', ['group' => $group->id])
            ->with('success', "Absensi & setoran sesi " . date('d/m/Y', strtotime($date)) . " berhasil dihapus.");
    }

    /**
     * Detail absensi satu sesi
     */
    public function showSession(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();
        $this->checkGroupAccess($group);

        $date = $request->input('date');
        if (!$date) {
            $latestDate = TahfidzAttendance::where('mentoring_group_id', $group->id)
                ->whereNotNull('session_date')
                ->latest('session_date')
                ->value('session_date');
            $date = $latestDate ? \Carbon\Carbon::parse($latestDate)->format('Y-m-d') : date('Y-m-d');
        }

        $records = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->with('student')
            ->get()
            ->sortBy(fn($r) => ($r->student?->class ?? '') . ($r->student?->name ?? ''))
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
    public static function generateWhatsappMessage(MentoringGroup $group, ?string $date = null, $records = []): string
    {
        $dateStr = $date ?: date('Y-m-d');
        $formattedDate = \Carbon\Carbon::parse($dateStr)->locale('id')->isoFormat('dddd, D MMMM Y');

        $msg = "Assalamu'alaikum Warahmatullahi Wabarakatuh 🌙\n\n";
        $msg .= "Ayah dan Bunda yang kami hormati,\n\n";
        $msg .= "Alhamdulillah, dengan puji syukur kepada Allah SWT, berikut kami sampaikan laporan capaian hafalan putra-putri tercinta.\n\n";
        $msg .= "📅 *Tanggal:* " . $formattedDate . "\n\n";
        $msg .= "━━━━━━━━━━━━━━━━\n\n";

        $no = 1;
        if (!empty($records)) {
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
                    $nilaiStr = !empty($rec->nilai) ? "⭐ *Nilai:* {$rec->nilai}\n" : "";
                    $tugas = !empty($rec->tugas_besok) ? $rec->tugas_besok : '-';
                    $notes = !empty($rec->notes) ? $rec->notes : ($isLulus ? 'Barakallah..' : 'Lancarkan lagi ya sholih/sholihah..');

                    $barisText = ($rec->jumlah_baris && $rec->jumlah_baris > 0) ? " ({$rec->jumlah_baris} Baris)" : "";
                    $msg .= "📖 *Capaian Hari Ini:*\n";
                    $msg .= "{$capaian}{$barisText}\n";
                    if ($nilaiStr !== "") {
                        $msg .= "{$nilaiStr}";
                    }
                    $msg .= "{$statusStr}\n\n";
                    $msg .= "📝 *Tugas Besok:*\n";
                    $msg .= "{$tugas}\n";
                    $msg .= "💭 *Catatan Guru:* {$notes}\n";
                }

                $msg .= "\n━━━━━━━━━━━━━━━━\n\n";
                $no++;
            }
        }

        $msg .= "Mari kita terus mendoakan dan membimbing putra-putri kita dalam perjalanan menghafal Al-Qur'an. Setiap ayat yang dihafal adalah investasi untuk masa depan yang cerah dan amal ibadah yang berkelanjutan.\n\n";
        $msg .= "Terima kasih atas kepercayaan dan kerja sama\n\n";
        $msg .= "Semoga Allah SWT memberkahi setiap usaha kita, memudahkan hafalan putra-putri kita, dan menjadikan Al-Qur'an sebagai cahaya di hati dan amal ibadah di akhirat kelak. 🤲\n\n";
        $msg .= "Wassalamu'alaikum Warahmatullahi Wabarakatuh 🌟\n\n";
        $msg .= "_SIMUTQIN SDIT AN NADZIR_\n";
        $msg .= "🔗 Cek Status & Rekap Ananda: https://simutqin.annadzir.sch.id/cek-status";

        return $msg;
    }

    /**
     * Print absensi satu sesi
     */
    public function printSession(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();
        $this->checkGroupAccess($group);

        $date = $request->input('date');
        if (!$date) {
            $latestDate = TahfidzAttendance::where('mentoring_group_id', $group->id)
                ->whereNotNull('session_date')
                ->latest('session_date')
                ->value('session_date');
            $date = $latestDate ? \Carbon\Carbon::parse($latestDate)->format('Y-m-d') : date('Y-m-d');
        }

        $records = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->with('student')
            ->get()
            ->sortBy(fn($r) => ($r->student?->class ?? '') . ($r->student?->name ?? ''))
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
        $this->checkGroupAccess($group);

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
