<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eskul extends Model
{
    protected $fillable = ['academic_year_id', 'name', 'target_group', 'instructor_name', 'schedule', 'is_lockable'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($eskul) {
            if (empty($eskul->academic_year_id)) {
                $activeYear = AcademicYear::where('is_active', true)->first();
                if ($activeYear) {
                    $eskul->academic_year_id = $activeYear->id;
                }
            }
        });
    }

    public function scopeActiveYear($query)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if ($activeYear) {
            return $query->where('eskuls.academic_year_id', $activeYear->id);
        }
        return $query;
    }

    public function scopeForYear($query, $yearId)
    {
        return $query->where('eskuls.academic_year_id', $yearId);
    }

    // Relation removed since it's just a string now
    /*
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    */

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_eskul')
                    ->withPivot(['academic_year_id', 'semester'])
                    ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    public function histories()
    {
        return $this->hasMany(EskulHistory::class);
    }

    public function getInstructorAt($yearId, $semester)
    {
        $history = $this->histories()
            ->where('academic_year_id', $yearId)
            ->where('semester', (string)$semester)
            ->first();
        return $history ? $history->instructor_name : $this->instructor_name;
    }

    public function getScheduleAt($yearId, $semester)
    {
        $history = $this->histories()
            ->where('academic_year_id', $yearId)
            ->where('semester', (string)$semester)
            ->first();
        return $history ? $history->schedule : $this->schedule;
    }

    public function getNameAt($yearId, $semester)
    {
        $history = $this->histories()
            ->where('academic_year_id', $yearId)
            ->where('semester', (string)$semester)
            ->first();
        return ($history && $history->alias_name) ? $history->alias_name : $this->name;
    }
}
