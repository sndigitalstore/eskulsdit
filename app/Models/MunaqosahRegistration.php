<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MunaqosahRegistration extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'from_group_id',
        'proposer_user_id',
        'proposer_name',
        'proposer_phone',
        'proposer_notes',
        'examiner_id',
        'examiner_user_id',
        'schedule_id',
        'type', // 'tilawati', 'tahfidz'
        'current_level',
        'target_level',
        'registration_date',
        'exam_date',
        'exam_time',
        'room_location',
        'status', // 'pending', 'scheduled', 'passed', 'failed'
        'score_tajwid',
        'score_fashahah',
        'score_makhraj',
        'score_adab_lagu',
        'final_score',
        'predicate', // 'Mumtaz', 'Jayyid Jiddan', 'Jayyid', 'Maqbul', 'Rasib'
        'examiner_notes',
        'recommendation',
        'tested_at',
        'transition_status', // 'none', 'pending_promotion', 'promoted'
        'promoted_to_group_id',
        'promoted_at',
        'promoted_by',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'exam_date' => 'date',
        'tested_at' => 'datetime',
        'promoted_at' => 'datetime',
        'score_tajwid' => 'float',
        'score_fashahah' => 'float',
        'score_makhraj' => 'float',
        'score_adab_lagu' => 'float',
        'final_score' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function fromGroup()
    {
        return $this->belongsTo(MentoringGroup::class, 'from_group_id');
    }

    public function proposer()
    {
        return $this->belongsTo(User::class, 'proposer_user_id');
    }

    public function examiner()
    {
        return $this->belongsTo(MunaqisyExaminer::class, 'examiner_id');
    }

    public function examinerUser()
    {
        return $this->belongsTo(User::class, 'examiner_user_id');
    }

    public function schedule()
    {
        return $this->belongsTo(MunaqisySchedule::class, 'schedule_id');
    }

    public function promotedToGroup()
    {
        return $this->belongsTo(MentoringGroup::class, 'promoted_to_group_id');
    }

    public function promotedByUser()
    {
        return $this->belongsTo(User::class, 'promoted_by');
    }

    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePendingPromotion($query)
    {
        return $query->where('status', 'passed')->where('transition_status', 'pending_promotion');
    }

    public function scopeForYear($query, $yearId)
    {
        if ($yearId) {
            return $query->where('academic_year_id', $yearId);
        }
        return $query;
    }

    public function scopeForType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }

    public static function getPredicateByScore(?float $score): string
    {
        if ($score === null) return '-';
        if ($score >= 90) return 'Mumtaz';
        if ($score >= 80) return 'Jayyid Jiddan';
        if ($score >= 70) return 'Jayyid';
        if ($score >= 60) return 'Maqbul';
        return 'Rasib / Perlu Mengulang';
    }

    /**
     * Membangun teks format WhatsApp resmi untuk seluruh siswa yang dimunaqosah hari itu (Rekapan Kolektif 1 Kali Klik)
     *
     * @param \Illuminate\Support\Collection|array $registrations
     * @param string|null $date
     * @param string|null $customGroup
     * @param string|null $customExaminer
     * @return string
     */
    public static function buildDailyBatchWaMessage($registrations, $date = null, $customGroup = null, $customExaminer = null): string
    {
        $regs = collect($registrations);
        if ($regs->isEmpty()) {
            return "Belum ada siswa yang dinilai pada hari ini.";
        }

        $dateObj = $date ? \Carbon\Carbon::parse($date) : now();
        $formattedDate = $dateObj->translatedFormat('l, d F Y');

        // Deteksi Tingkat Kenaikan (misal: Jilid 1, Juz 30, dsb)
        $targetLevels = $regs->pluck('target_level')->filter()->unique()->values();
        $tingkatKenaikan = $targetLevels->isNotEmpty() ? $targetLevels->join(', ') : ($regs->first()?->target_level ?? 'Jilid 1');

        // Deteksi Kelompok
        $groupNames = $regs->map(fn($r) => $r->fromGroup->name ?? null)->filter()->unique()->values();
        $kelompok = $customGroup ?: ($groupNames->isNotEmpty() ? $groupNames->join(', ') : ($regs->first()?->fromGroup->name ?? 'Kelompok Al-Qur\'an'));

        // Deteksi Dewan Penguji
        $examinerNames = $regs->map(fn($r) => $r->examinerUser->name ?? ($r->examiner->name ?? null))->filter()->unique()->values();
        $dewanPenguji = $customExaminer ?: ($examinerNames->isNotEmpty() ? $examinerNames->join(', ') : (\Illuminate\Support\Facades\Auth::user()?->name ?? 'Dewan Munaqisy'));

        $totalSiswa = $regs->count();
        $passedCount = $regs->where('status', 'passed')->count();
        $failedCount = $regs->where('status', 'failed')->count();

        $msg = "*REKAPAN RESMI HASIL MUNAQOSAH AL-QUR'AN*\n";
        $msg .= "*SDIT AN NADZIR*\n";
        $msg .= "━━━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "Alhamdulillah telah selesai dilaksanakan evaluasi munaqosah :\n\n";
        $msg .= "Hari Tanggal : {$formattedDate}\n";
        $msg .= "Tingkat Kenaikan : {$tingkatKenaikan}\n";
        $msg .= "Kelompok : {$kelompok}\n";
        $msg .= "Dewan Penguji : {$dewanPenguji}\n";
        $msg .= "─────────────────────\n";

        foreach ($regs as $idx => $r) {
            $no = $idx + 1;
            $studentName = $r->student->name ?? 'Santri';
            $studentClass = $r->student->class ?? '-';
            $keterangan = ($r->status === 'passed') ? 'Lanjut' : 'Belum Lanjut';
            $score = $r->final_score !== null ? $r->final_score : '-';

            if ($totalSiswa > 1) {
                $msg .= "{$no}. Nama Siswa : {$studentName}\n";
                $msg .= "   Kelas : {$studentClass}\n";
                $msg .= "   Keterangan : {$keterangan}";
                if ($score !== '-' && $score !== null) {
                    $msg .= " (Nilai: {$score})";
                }
                $msg .= "\n";
                if (!empty($r->recommendation) && $r->status !== 'passed') {
                    $msg .= "   Catatan : {$r->recommendation}\n";
                }
            } else {
                $msg .= "Nama Siswa : {$studentName}\n";
                $msg .= "Kelas : {$studentClass}\n";
                $msg .= "Keterangan : {$keterangan}";
                if ($score !== '-' && $score !== null) {
                    $msg .= " (Nilai: {$score})";
                }
                $msg .= "\n";
                if (!empty($r->recommendation) && $r->status !== 'passed') {
                    $msg .= "Catatan : {$r->recommendation}\n";
                }
            }
            $msg .= "\n";
        }

        $msg .= "─────────────────────\n";
        if ($totalSiswa > 1) {
            $msg .= "📊 Ringkasan Hasil:\n";
            $msg .= "• Total Siswa Diuji : {$totalSiswa} Siswa\n";
            $msg .= "• Lanjut            : {$passedCount} Siswa\n";
            if ($failedCount > 0) {
                $msg .= "• Belum Lanjut      : {$failedCount} Siswa\n";
            }
            $msg .= "━━━━━━━━━━━━━━━━━━━━━\n";
        }
        $msg .= "Barakallahu Fiikum. Semoga ananda senantiasa mencintai Al-Qur'an dan istiqomah dalam mengamalkannya. Aamiin Ya Rabbal 'Alamin. 🤲\n";

        return $msg;
    }

    /**
     * Membangun teks format WhatsApp resmi untuk satu santri (sesuai format baru)
     */
    public function buildWaGroupMessage(): string
    {
        return self::buildDailyBatchWaMessage(
            [$this],
            $this->tested_at ? $this->tested_at->format('Y-m-d') : ($this->exam_date ? $this->exam_date->format('Y-m-d') : date('Y-m-d')),
            $this->fromGroup->name ?? null,
            $this->examinerUser->name ?? ($this->examiner->name ?? null)
        );
    }
}

