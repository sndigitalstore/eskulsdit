<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahfidzAttendance extends Model
{
    protected $table = 'tahfidz_attendances';

    protected $fillable = [
        'mentoring_group_id',
        'student_id',
        'academic_year_id',
        'session_date',
        'status',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function mentoringGroup(): BelongsTo
    {
        return $this->belongsTo(MentoringGroup::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
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

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'hadir' => 'Hadir',
            'sakit' => 'Sakit',
            'izin'  => 'Izin',
            'alpha' => 'Alpha',
            default => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'hadir' => '#16a34a',
            'sakit' => '#d97706',
            'izin'  => '#0ea5e9',
            'alpha' => '#dc2626',
            default => '#64748b',
        };
    }

    public function getStatusBgAttribute(): string
    {
        return match($this->status) {
            'hadir' => '#dcfce7',
            'sakit' => '#fef3c7',
            'izin'  => '#e0f2fe',
            'alpha' => '#fee2e2',
            default => '#f1f5f9',
        };
    }
}
