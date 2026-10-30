<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervision extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'semester',
        'supervisor_id',
        'teacher_id',
        'supervision_type',
        'supervision_date',
        'class_name',
        'subject_name',
        'aspects_score',
        'total_score',
        'predicate',
        'strengths',
        'improvements',
        'recommendations',
        'follow_up_date',
        'status',
    ];

    protected $casts = [
        'aspects_score' => 'array',
        'supervision_date' => 'date',
        'follow_up_date' => 'date',
        'total_score' => 'float',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function scopeActiveYear($query)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if ($activeYear) {
            $query->where('academic_year_id', $activeYear->id)
                  ->where('semester', $activeYear->active_semester);
        }
        return $query;
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->supervision_type) {
            'kbm' => 'Supervisi KBM & Pembelajaran',
            'modul_ajar' => 'Supervisi Perencanaan / Modul Ajar',
            'quran' => 'Supervisi Pembinaan Al-Qur\'an',
            'eskul' => 'Supervisi Ekstrakurikuler',
            default => ucfirst($this->supervision_type),
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->supervision_type) {
            'kbm' => 'fa-chalkboard-teacher',
            'modul_ajar' => 'fa-file-signature',
            'quran' => 'fa-quran',
            'eskul' => 'fa-shapes',
            default => 'fa-clipboard-check',
        };
    }

    public function getPredicateColorAttribute(): string
    {
        if (str_contains($this->predicate, 'Sangat Baik')) return '#059669'; // Emerald
        if (str_contains($this->predicate, 'Baik')) return '#2563eb'; // Blue
        if (str_contains($this->predicate, 'Cukup')) return '#d97706'; // Amber
        return '#dc2626'; // Rose
    }

    public static function getRubricDefinition(string $type): array
    {
        return match ($type) {
            'kbm' => [
                'title' => 'Supervisi Pelaksanaan KBM / Pembelajaran di Kelas',
                'description' => 'Observasi langsung proses belajar mengajar, interaksi guru-siswa, dan penanaman karakter Islami di kelas.',
                'aspects' => [
                    [
                        'id' => 'kbm_1',
                        'title' => 'Apersepsi, Motivasi & Internalisasi Nilai Islam',
                        'desc' => 'Membuka KBM dengan salam, doa adab belajar, apersepsi mengaitkan materi dengan kehidupan nyata dan nilai-nilai Islami/Al-Qur\'an.',
                    ],
                    [
                        'id' => 'kbm_2',
                        'title' => 'Penguasaan Materi & Alur Tujuan Pembelajaran (TP)',
                        'desc' => 'Menyampaikan konsep materi secara runtut, jelas, tanpa miskonsepsi, dan selaras dengan Tujuan Pembelajaran Kurikulum Merdeka.',
                    ],
                    [
                        'id' => 'kbm_3',
                        'title' => 'Penerapan Model & Strategi Pembelajaran Aktif',
                        'desc' => 'Menggunakan strategi berpusat pada siswa (PBL, Inkuiri, Diskusi, Games edukatif) yang memicu nalar kritis dan antusiasme siswa.',
                    ],
                    [
                        'id' => 'kbm_4',
                        'title' => 'Pemanfaatan Media Pembelajaran & Teknologi/IT',
                        'desc' => 'Menggunakan media konkret, proyektor/audio-visual, atau lembar aktivitas belajar secara efektif dan tepat guna.',
                    ],
                    [
                        'id' => 'kbm_5',
                        'title' => 'Pembelajaran Berdiferensiasi & Layanan Individual',
                        'desc' => 'Memberikan pendampingan berimbang bagi siswa dengan beragam kecepatan belajar (slow vs fast learners).',
                    ],
                    [
                        'id' => 'kbm_6',
                        'title' => 'Pengelolaan Kelas & Pembiasaan Adab Siswa',
                        'desc' => 'Menjaga ketertiban kelas dengan disiplin positif, membiasakan adab berbicara santun, saling menghargai, dan suasana kondusif.',
                    ],
                    [
                        'id' => 'kbm_7',
                        'title' => 'Asesmen Formatif & Umpan Balik Berkala',
                        'desc' => 'Mengajukan pertanyaan pemantik, mengecek pemahaman siswa di tengah KBM, dan memberikan feedback perbaikan yang suportif.',
                    ],
                    [
                        'id' => 'kbm_8',
                        'title' => 'Refleksi, Rangkuman & Penutup KBM',
                        'desc' => 'Menyimpulkan materi bersama siswa, melakukan refleksi perasaan/pembelajaran, menyampaikan tindak lanjut, dan menutup dengan doa.',
                    ],
                ]
            ],
            'modul_ajar' => [
                'title' => 'Supervisi Perencanaan & Modul Ajar (RPP)',
                'description' => 'Penilaian kesiapan administrasi guru: Capaian Pembelajaran (CP), TP, ATP, skenario ajar, dan instrumen asesmen.',
                'aspects' => [
                    [
                        'id' => 'ma_1',
                        'title' => 'Kesesuaian CP, TP, dan Alur Tujuan Pembelajaran (ATP)',
                        'desc' => 'Tujuan pembelajaran dirumuskan operasional (ABCD), relevan dengan fase perkembangan siswa dan panduan Kurikulum Merdeka.',
                    ],
                    [
                        'id' => 'ma_2',
                        'title' => 'Rancangan Asesmen Diagnostik & Formatif',
                        'desc' => 'Tersedia instrumen pemetaan awal kesiapan belajar siswa serta asesmen formatif selama proses pembelajaran.',
                    ],
                    [
                        'id' => 'ma_3',
                        'title' => 'Skenario Langkah Pembelajaran Berdiferensiasi',
                        'desc' => 'Rancangan tahapan kegiatan mencakup diferensiasi konten, proses, atau produk sesuai profil belajar murid.',
                    ],
                    [
                        'id' => 'ma_4',
                        'title' => 'Kualitas LKPD & Bahan Ajar / Sumber Belajar',
                        'desc' => 'Lembar Kerja Peserta Didik (LKPD) kontekstual, menarik, memantik kreativitas, dan mendukung capaian kompetensi.',
                    ],
                    [
                        'id' => 'ma_5',
                        'title' => 'Integrasi Nilai Islam & Karakter Pelajar Pancasila',
                        'desc' => 'Modul ajar mencantumkan penanaman adab Islami, nilai Al-Qur\'an, dan dimensi karakter Profil Pelajar Pancasila.',
                    ],
                    [
                        'id' => 'ma_6',
                        'title' => 'Rancangan Asesmen Sumatif & Program Remedial/Pengayaan',
                        'desc' => 'Memiliki rubrik/kriteria ketuntasan (KKTP) yang jelas serta panduan tindak lanjut bagi siswa yang belum tuntas.',
                    ],
                ]
            ],
            'quran' => [
                'title' => 'Supervisi Pembinaan Al-Qur\'an (Tahfidz & Tilawati)',
                'description' => 'Observasi pelaksanaan halaqah Qur\'an, metode klasikal/talaqqi, fashahah, dan ketertiban mutaba\'ah.',
                'aspects' => [
                    [
                        'id' => 'qr_1',
                        'title' => 'Kedisiplinan Waktu & Adab Majelis Al-Qur\'an',
                        'desc' => 'Memulai halaqah tepat waktu, memastikan wudhu, kerapian duduk halaqah, adab terhadap mushaf, dan doa pembuka/tadarus bersama.',
                    ],
                    [
                        'id' => 'qr_2',
                        'title' => 'Penerapan Metode Standar (Tilawati / Tahfidz Talaqqi)',
                        'desc' => 'Menerapkan teknik peraga klasikal, baca-simak, atau talaqqi-tikrar hafalan baru sesuai metode standar sekolah.',
                    ],
                    [
                        'id' => 'qr_3',
                        'title' => 'Standar Tajwid, Makharijul Huruf, dan Fashahah',
                        'desc' => 'Ketelitian dan kejelian guru dalam membetulkan kesalahan panjang-pendek (mad), ghunnah, tebal-tipis, dan waqaf-ibtida.',
                    ],
                    [
                        'id' => 'qr_4',
                        'title' => 'Ketertiban Administrasi, Setoran & SIMUTQIN',
                        'desc' => 'Mencatat riwayat capaian ayat/jilid di buku mutaba\'ah dan menginput presensi serta capaian ke sistem SIMUTQIN secara tertib.',
                    ],
                    [
                        'id' => 'qr_5',
                        'title' => 'Pengelolaan Suasana Halaqah & Motivasi Santri',
                        'desc' => 'Menjaga fokus halaqah tetap tenang, tidak gaduh, menyemangati santri dengan apresiasi verbal/hadiah, dan tidak monoton.',
                    ],
                    [
                        'id' => 'qr_6',
                        'title' => 'Pendampingan Siswa Slow Learner / Butuh Bimbingan',
                        'desc' => 'Memberikan waktu tambahan dan pendekatan sabar bagi siswa yang mengalami hambatan bacaan/daya ingat hafalan.',
                    ],
                ]
            ],
            'eskul' => [
                'title' => 'Supervisi Kegiatan Ekstrakurikuler',
                'description' => 'Evaluasi pelaksanaan bimbingan eskul, kedisiplinan pembina, keselamatan siswa, dan penyiapan bibit prestasi.',
                'aspects' => [
                    [
                        'id' => 'es_1',
                        'title' => 'Kesiapan Program Kerja & Silabus Latihan Eskul',
                        'desc' => 'Memiliki target capaian keterampilan mingguan/bulanan yang terprogram dengan baik sepanjang tahun ajaran.',
                    ],
                    [
                        'id' => 'es_2',
                        'title' => 'Kedisiplinan Kehadiran & Pengisian Presensi di SIMUTQIN',
                        'desc' => 'Hadir tepat waktu sebelum sesi dimulai, mengisi absensi pembina dan presensi kehadiran siswa serta jurnal eskul di sistem.',
                    ],
                    [
                        'id' => 'es_3',
                        'title' => 'Penguasaan Materi & Sistematika Pelatihan/KBM Eskul',
                        'desc' => 'Melatih tahapan materi dari pemanasan/dasar, inti praktik, hingga evaluasi akhir secara terstruktur dan aman.',
                    ],
                    [
                        'id' => 'es_4',
                        'title' => 'Pengawasan Keselamatan & Penanaman Akhlak Sportif',
                        'desc' => 'Memastikan keamanan fisik siswa, menjaga peralatan sekolah dengan baik, serta membiasakan adab dan sportivitas.',
                    ],
                    [
                        'id' => 'es_5',
                        'title' => 'Pemetaan Potensi Prestasi & Penyiapan Lomba',
                        'desc' => 'Mengidentifikasi siswa berbakat khusus dan menyiapkan delegasi untuk kompetisi/kejuaraan antarsekolah.',
                    ],
                ]
            ],
            default => [
                'title' => 'Supervisi Standar',
                'description' => 'Instrumen observasi dan evaluasi.',
                'aspects' => [],
            ],
        };
    }
}
