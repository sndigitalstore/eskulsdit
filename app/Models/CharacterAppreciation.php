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
        'dimension_p5',
        'title',
        'description',
        'point',
        'reward',
        'certificate_no',
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
        if (str_contains($cat, 'sholeh')) {
            return ['bg' => '#dcfce7', 'color' => '#15803d', 'icon' => 'fa-pray'];
        } elseif (str_contains($cat, 'muslih')) {
            return ['bg' => '#fef3c7', 'color' => '#b45309', 'icon' => 'fa-hands-helping'];
        } elseif (str_contains($cat, 'intellectual') || str_contains($cat, 'curiosity') || str_contains($cat, 'intelektual') || str_contains($cat, 'rasa ingin tahu')) {
            return ['bg' => '#e0f2fe', 'color' => '#0369a1', 'icon' => 'fa-brain'];
        } elseif (str_contains($cat, 'leader') || str_contains($cat, 'pemimpin')) {
            return ['bg' => '#ede9fe', 'color' => '#7c3aed', 'icon' => 'fa-crown'];
        } elseif (str_contains($cat, 'environ') || str_contains($cat, 'lingkungan')) {
            return ['bg' => '#d1fae5', 'color' => '#065f46', 'icon' => 'fa-leaf'];
        }
        return ['bg' => '#f1f5f9', 'color' => '#334155', 'icon' => 'fa-star'];
    }

    /**
     * Helper to get Dimension Profil Lulusan / P5 Theme
     */
    public function getDimensionP5ThemeAttribute()
    {
        $dim = strtolower($this->dimension_p5 ?? '');
        if (str_contains($dim, 'iman') || str_contains($dim, 'takwa') || str_contains($dim, 'taqwa') || str_contains($dim, 'spiritual')) {
            return ['bg' => '#ecfdf5', 'color' => '#047857', 'icon' => 'fa-star-and-crescent', 'label' => 'Keimanan & Ketakwaan'];
        } elseif (str_contains($dim, 'kewargaan') || str_contains($dim, 'kebinekaan')) {
            return ['bg' => '#fef2f2', 'color' => '#b91c1c', 'icon' => 'fa-flag', 'label' => 'Kewargaan'];
        } elseif (str_contains($dim, 'kritis') || str_contains($dim, 'nalar')) {
            return ['bg' => '#eff6ff', 'color' => '#1d4ed8', 'icon' => 'fa-brain', 'label' => 'Penalaran Kritis'];
        } elseif (str_contains($dim, 'kreatif')) {
            return ['bg' => '#faf5ff', 'color' => '#7c3aed', 'icon' => 'fa-lightbulb', 'label' => 'Kreativitas'];
        } elseif (str_contains($dim, 'kolaborasi') || str_contains($dim, 'gotong') || str_contains($dim, 'royong')) {
            return ['bg' => '#f0fdf4', 'color' => '#15803d', 'icon' => 'fa-users', 'label' => 'Kolaborasi'];
        } elseif (str_contains($dim, 'mandiri')) {
            return ['bg' => '#fff7ed', 'color' => '#c2410c', 'icon' => 'fa-user-check', 'label' => 'Kemandirian'];
        } elseif (str_contains($dim, 'kesehatan') || str_contains($dim, 'sehat') || str_contains($dim, 'raga')) {
            return ['bg' => '#ecfdf5', 'color' => '#059669', 'icon' => 'fa-heartbeat', 'label' => 'Kesehatan'];
        } elseif (str_contains($dim, 'komunikasi') || str_contains($dim, 'tutur') || str_contains($dim, 'wicara')) {
            return ['bg' => '#e0f2fe', 'color' => '#0284c7', 'icon' => 'fa-comments', 'label' => 'Komunikasi'];
        }
        return ['bg' => '#f8fafc', 'color' => '#475569', 'icon' => 'fa-award', 'label' => $this->dimension_p5 ?: 'Profil Lulusan'];
    }
}
