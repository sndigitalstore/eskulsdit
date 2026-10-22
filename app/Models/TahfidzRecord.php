<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahfidzRecord extends Model
{
    protected $table = 'tahfidz_records';

    protected $fillable = [
        'student_id',
        'mentoring_group_id',
        'academic_year_id',
        'surah_name',
        'juz',
        'start_ayah',
        'end_ayah',
        'jumlah_baris',
        'type',
        'quality',
        'session_date',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'session_date' => 'date',
        'juz'          => 'integer',
        'start_ayah'   => 'integer',
        'end_ayah'     => 'integer',
        'jumlah_baris' => 'integer',
    ];

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function mentoringGroup(): BelongsTo
    {
        return $this->belongsTo(MentoringGroup::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // -------------------------------------------------------
    // Accessors / Helpers
    // -------------------------------------------------------

    /** Label kualitas hafalan */
    public function getQualityLabelAttribute(): string
    {
        return match($this->quality) {
            'lancar'           => 'Lancar',
            'cukup'            => 'Cukup',
            'perlu_perbaikan'  => 'Perlu Perbaikan',
            default            => '-',
        };
    }

    /** Badge warna kualitas */
    public function getQualityColorAttribute(): string
    {
        return match($this->quality) {
            'lancar'           => '#16a34a',
            'cukup'            => '#d97706',
            'perlu_perbaikan'  => '#dc2626',
            default            => '#64748b',
        };
    }

    /** Label jenis setoran */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'setoran_baru' => 'Setoran Baru',
            'murojaah'     => "Murojaah",
            default        => '-',
        };
    }

    /** Rentang ayat sebagai string */
    public function getAyahRangeAttribute(): string
    {
        if ($this->start_ayah && $this->end_ayah) {
            return "Ayat {$this->start_ayah} – {$this->end_ayah}";
        }
        return '-';
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForGroup($query, $groupId)
    {
        return $query->where('mentoring_group_id', $groupId);
    }

    public function scopeForYear($query, $yearId)
    {
        return $query->where('academic_year_id', $yearId);
    }
}
