<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterCase extends Model
{
    protected $fillable = [
        'academic_year_id',
        'student_id',
        'date',
        'category',
        'title',
        'description',
        'location',
        'severity',
        'point',
        'action_taken',
        'follow_up',
        'status',
        'reported_by',
        'parent_notified',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'parent_notified' => 'boolean',
        'point' => 'integer',
    ];

    /**
     * Auto-assign active academic year if not explicitly provided
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->academic_year_id)) {
                $activeYear = AcademicYear::where('is_active', true)->first();
                if ($activeYear) {
                    $model->academic_year_id = $activeYear->id;
                }
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Helper to get Indonesian severity label
     */
    public function getSeverityBadgeAttribute()
    {
        return match($this->severity) {
            'berat' => ['label' => 'Tingkat Berat', 'bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#f87171'],
            'sedang' => ['label' => 'Tingkat Sedang', 'bg' => '#fef3c7', 'color' => '#b45309', 'border' => '#fcd34d'],
            default => ['label' => 'Tingkat Ringan', 'bg' => '#f1f5f9', 'color' => '#475569', 'border' => '#cbd5e1'],
        };
    }

    /**
     * Helper to get Indonesian status label
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'selesai' => ['label' => 'Selesai Dibina', 'bg' => '#dcfce7', 'color' => '#15803d', 'icon' => 'fa-check-circle'],
            'panggilan_ortu' => ['label' => 'Panggilan Ortu', 'bg' => '#fee2e2', 'color' => '#b91c1c', 'icon' => 'fa-phone-alt'],
            default => ['label' => 'Dalam Pembinaan', 'bg' => '#eff6ff', 'color' => '#1d4ed8', 'icon' => 'fa-user-clock'],
        };
    }
}
