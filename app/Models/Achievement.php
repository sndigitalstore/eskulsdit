<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'category',
        'school_name',
        'student_id',
        'academic_year_id',
        'semester',
        'name',
        'level',
        'date',
        'organizer',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($achievement) {
            if (empty($achievement->academic_year_id)) {
                $activeYear = AcademicYear::where('is_active', true)->first();
                if ($activeYear) {
                    $achievement->academic_year_id = $activeYear->id;
                    $achievement->semester = $activeYear->active_semester;
                }
            }

            if (empty($achievement->category)) {
                $achievement->category = !empty($achievement->student_id) ? 'student' : 'school';
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

    public function isSchool(): bool
    {
        return $this->category === 'school' || empty($this->student_id);
    }

    public function getRecipientNameAttribute(): string
    {
        if ($this->isSchool()) {
            return $this->school_name ?: 'SDIT An Nadzir';
        }
        return $this->student?->name ?: 'Siswa';
    }
}
