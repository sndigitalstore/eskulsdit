<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'academic_year_id',
        'name',
        'class',
        'status',
        'photo',
        'nis',
        'parent_phone',
    ];

    /**
     * Auto-fill academic_year_id with the active year when creating a new student,
     * if it hasn't been explicitly set.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            if (empty($student->academic_year_id)) {
                $activeYear = AcademicYear::where('is_active', true)->first();
                if ($activeYear) {
                    $student->academic_year_id = $activeYear->id;
                }
            }
        });
    }

    /**
     * Scope to filter students by the currently active academic year.
     */
    public function scopeActiveYear($query)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if ($activeYear) {
            return $query->where('students.academic_year_id', $activeYear->id);
        }
        return $query;
    }

    /**
     * Scope to filter students by a specific academic year ID.
     */
    public function scopeForYear($query, $yearId)
    {
        return $query->where('students.academic_year_id', $yearId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function eskuls()
    {
        return $this->belongsToMany(Eskul::class, 'student_eskul')
                    ->withPivot(['academic_year_id', 'semester'])
                    ->withTimestamps();
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }
}
