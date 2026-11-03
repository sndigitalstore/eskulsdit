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
     * Helper to get Dimension P5 & SIT Theme
     */
    public function getDimensionP5ThemeAttribute()
    {
        $dim = strtolower($this->dimension_p5 ?? '');
        if (str_contains($dim, 'beriman') || str_contains($dim, 'bertaqwa')) {
            return ['bg' => '#ecfdf5', 'color' => '#065f46', 'label' => 'Beriman & Bertaqwa'];
        } elseif (str_contains($dim, 'mandiri')) {
            return ['bg' => '#eff6ff', 'color' => '#1e40af', 'label' => 'Mandiri'];
        } elseif (str_contains($dim, 'gotong') || str_contains($dim, 'royong')) {
            return ['bg' => '#fef3c7', 'color' => '#92400e', 'label' => 'Gotong Royong'];
        } elseif (str_contains($dim, 'kritis')) {
            return ['bg' => '#f3e8ff', 'color' => '#6b21a8', 'label' => 'Bernalar Kritis'];
        } elseif (str_contains($dim, 'kreatif')) {
            return ['bg' => '#fdf2f8', 'color' => '#9d174d', 'label' => 'Kreatif'];
        } elseif (str_contains($dim, 'kebinekaan') || str_contains($dim, 'kebhinekaan')) {
            return ['bg' => '#ffedd5', 'color' => '#9a3412', 'label' => 'Berkebinekaan Global'];
        }
        return ['bg' => '#f8fafc', 'color' => '#475569', 'label' => $this->dimension_p5 ?: 'Karakter JSIT'];
    }
}
