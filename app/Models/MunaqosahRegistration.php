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
     * Membangun teks format WhatsApp resmi untuk diteruskan ke grup WA
     */
    public function buildWaGroupMessage(): string
    {
        $studentName = $this->student->name ?? 'Santri';
        $studentClass = $this->student->class ?? '-';
        $studentNis = $this->student->nis ?? '-';
        $typeLabel = $this->type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        $examinerName = $this->examinerUser->name ?? ($this->examiner->name ?? 'Dewan Munaqisy');
        $testedDate = $this->tested_at ? $this->tested_at->translatedFormat('l, d F Y - H:i') . ' WIB' : ($this->exam_date ? $this->exam_date->translatedFormat('d F Y') : date('d F Y'));
        $statusEmoji = ($this->status === 'passed') ? '🎉 *LULUS*' : '🔄 *BELUM LULUS (MENGULANG)*';

        $msg = "*REKAPAN RESMI HASIL MUNAQOSAH AL-QUR'AN*\n";
        $msg .= "*SDIT AN NADZIR*\n";
        $msg .= "━━━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "Alhamdulillah, telah selesai dilaksanakan evaluasi munaqosah ananda:\n\n";
        $msg .= "👤 *Nama Santri:* {$studentName}\n";
        $msg .= "🏫 *Kelas:* {$studentClass} | *NIS:* {$studentNis}\n";
        $msg .= "📖 *Bidang Ujian:* Munaqosah {$typeLabel}\n";
        $msg .= "🎯 *Tingkat Kenaikan:* {$this->current_level} ➔ {$this->target_level}\n";
        $msg .= "👨‍🏫 *Dewan Penguji:* {$examinerName}\n";
        $msg .= "📅 *Waktu Uji:* {$testedDate}\n";
        $msg .= "─────────────────────\n";
        $msg .= "📊 *Rincian Nilai Pengujian:*\n";
        $msg .= "• Kelancaran & Fashahah : *" . ($this->score_fashahah ?? 0) . "* (Bobot 30%)\n";
        $msg .= "• Kaidah Tajwid         : *" . ($this->score_tajwid ?? 0) . "* (Bobot 30%)\n";
        $msg .= "• Makharijul Huruf      : *" . ($this->score_makhraj ?? 0) . "* (Bobot 25%)\n";
        $msg .= "• Adab & Irama Tilawah  : *" . ($this->score_adab_lagu ?? 0) . "* (Bobot 15%)\n";
        $msg .= "─────────────────────\n";
        $msg .= "🏆 *NILAI AKHIR : " . ($this->final_score ?? 0) . "*\n";
        $msg .= "🎖 *PREDIKAT    : " . ($this->predicate ?? '-') . "*\n";
        $msg .= "📋 *KEPUTUSAN   : {$statusEmoji}*\n";
        if (!empty($this->recommendation)) {
            $msg .= "📌 *REKOMENDASI : {$this->recommendation}*\n";
        }
        if (!empty($this->examiner_notes)) {
            $msg .= "\n💬 *Catatan Dewan Munaqisy:*\n_{$this->examiner_notes}_\n";
        }
        $msg .= "━━━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "Barakallahu Fiikum. Semoga ananda senantiasa mencintai Al-Qur'an dan istiqomah dalam mengamalkannya.\n";

        return $msg;
    }
}

