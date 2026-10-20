<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterAppreciation extends Model
{
    protected $fillable = [
        'academic_year_id',
        'student_id',
        'date',
        'category',
        'title',
        'description',
        'point',
        'reward',
        'given_by',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
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
     * Helper to get category badge theme (SMILE values)
     */
    public function getCategoryThemeAttribute()
    {
        $cat = strtolower($this->category);
        if (str_contains($cat, 'shiddiq') || str_contains($cat, 'jujur')) {
            return ['bg' => '#dcfce7', 'color' => '#15803d', 'icon' => 'fa-check-double'];
        } elseif (str_contains($cat, 'mandiri')) {
            return ['bg' => '#e0e7ff', 'color' => '#4338ca', 'icon' => 'fa-hiking'];
        } elseif (str_contains($cat, 'ibadah') || str_contains($cat, 'inovatif')) {
            return ['bg' => '#e0f2fe', 'color' => '#0369a1', 'icon' => 'fa-pray'];
        } elseif (str_contains($cat, 'leader') || str_contains($cat, 'pemimpin')) {
            return ['bg' => '#fef3c7', 'color' => '#b45309', 'icon' => 'fa-crown'];
        } elseif (str_contains($cat, 'empati') || str_contains($cat, 'peduli')) {
            return ['bg' => '#fce7f3', 'color' => '#be185d', 'icon' => 'fa-heart'];
        }
        return ['bg' => '#f1f5f9', 'color' => '#334155', 'icon' => 'fa-star'];
    }
}
