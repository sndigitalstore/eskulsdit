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

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function mentoringGroups()
    {
        return $this->belongsToMany(MentoringGroup::class, 'mentoring_group_students')
                    ->withPivot(['id', 'academic_year_id', 'notes'])
                    ->withTimestamps();
    }

    public function tilawatiGroup($yearId = null)
    {
        $query = $this->mentoringGroups()->where('mentoring_groups.type', 'tilawati');
        if ($yearId) {
            $query->where('mentoring_group_students.academic_year_id', $yearId);
        }
        return $query->first();
    }

    public function tahfidzGroup($yearId = null)
    {
        $query = $this->mentoringGroups()->where('mentoring_groups.type', 'tahfidz');
        if ($yearId) {
            $query->where('mentoring_group_students.academic_year_id', $yearId);
        }
        return $query->first();
    }

    public function tahfidzRecords()
    {
        return $this->hasMany(TahfidzRecord::class);
    }

    public function tilawatiAttendances()
    {
        return $this->hasMany(TilawatiAttendance::class);
    }

    public function tasmiExams()
    {
        return $this->hasMany(TasmiExam::class);
    }

    public function characterCases()
    {
        return $this->hasMany(CharacterCase::class);
    }

    public function characterAppreciations()
    {
        return $this->hasMany(CharacterAppreciation::class);
    }

    public function academicScores()
    {
        return $this->hasMany(AcademicScore::class);
    }

    public function academicSummaries()
    {
        return $this->hasMany(AcademicSummary::class);
    }

    public function munaqosahRegistrations()
    {
        return $this->hasMany(MunaqosahRegistration::class);
    }

    /**
     * Accessor untuk menyamarkan nomor telepon wali (Privacy Masking).
     * Contoh: 081234567890 -> 0812-****-7890
     */
    public function getMaskedParentPhoneAttribute()
    {
        if (empty($this->parent_phone)) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', (string)$this->parent_phone);
        $len = strlen($digits);

        if ($len <= 4) {
            return str_repeat('*', $len);
        }

        if ($len <= 7) {
            return substr($digits, 0, 3) . '****';
        }

        $prefix = substr($digits, 0, 4);
        $suffix = substr($digits, -4);
        return $prefix . '-****-' . $suffix;
    }
}
