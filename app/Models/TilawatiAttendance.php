<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TilawatiAttendance extends Model
{
    use HasFactory;

    protected $table = 'tilawati_attendances';

    protected $fillable = [
        'tilawati_session_id',
        'mentoring_group_id',
        'student_id',
        'academic_year_id',
        'session_date',
        'status',
        'jilid',
        'peraga_pages',
        'buku_page',
        'score',
        'is_passed',
        'action',
        'tugas_besok',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'session_date' => 'date',
        'buku_page' => 'integer',
        'score' => 'float',
        'is_passed' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(TilawatiSession::class, 'tilawati_session_id');
    }

    public function mentoringGroup()
    {
        return $this->belongsTo(MentoringGroup::class, 'mentoring_group_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
