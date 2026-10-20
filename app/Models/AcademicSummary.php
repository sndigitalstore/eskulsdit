<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'student_id',
        'assessment_type',
        'sick_count',
        'permission_count',
        'unexcused_count',
        'homeroom_notes',
    ];

    protected $casts = [
        'sick_count' => 'integer',
        'permission_count' => 'integer',
        'unexcused_count' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
