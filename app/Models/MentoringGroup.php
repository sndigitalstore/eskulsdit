<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentoringGroup extends Model
{
    protected $fillable = [
        'type',
        'name',
        'mentor_name',
        'mentor_user_id',
        'academic_year_id',
        'target_level',
        'schedule',
        'notes',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function mentorUser()
    {
        return $this->belongsTo(User::class, 'mentor_user_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'mentoring_group_students')
                    ->withPivot(['id', 'academic_year_id', 'notes'])
                    ->withTimestamps();
    }

    public function scopeTilawati($query)
    {
        return $query->where('type', 'tilawati');
    }

    public function scopeTahfidz($query)
    {
        return $query->where('type', 'tahfidz');
    }

    public function scopeForYear($query, $yearId)
    {
        if ($yearId) {
            return $query->where('academic_year_id', $yearId);
        }
        return $query;
    }
}
