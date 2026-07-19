<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eskul extends Model
{
    protected $fillable = ['academic_year_id', 'name', 'target_group', 'instructor_name', 'schedule', 'is_lockable'];

    /**
     * Selalu kembalikan target_group sebagai array, backward-compatible
     * dengan nilai lama (string) maupun nilai baru (JSON array).
     */
    public function getTargetGroupsAttribute(): array
    {
        $val = $this->target_group;
        if (is_array($val)) return $val;
        $decoded = json_decode($val, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        return [$val]; // nilai lama berupa string biasa
    }

    /**
     * Cek apakah eskul ini diperuntukkan bagi group tertentu.
     */
    public function isForGroup(string $studentGroup): bool
    {
        $groups = $this->target_groups;
        if (in_array('all', $groups)) return true;
        return in_array($studentGroup, $groups);
    }

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
