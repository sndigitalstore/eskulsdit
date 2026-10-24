<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TilawatiSession extends Model
{
    use HasFactory;

    protected $table = 'tilawati_sessions';

    protected $fillable = [
        'mentoring_group_id',
        'academic_year_id',
        'session_date',
        'jilid',
        'peraga_page_start',
        'peraga_page_end',
        'buku_page',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'session_date' => 'date',
        'peraga_page_start' => 'integer',
        'peraga_page_end' => 'integer',
        'buku_page' => 'integer',
    ];

    public function mentoringGroup()
    {
        return $this->belongsTo(MentoringGroup::class, 'mentoring_group_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function attendances()
    {
        return $this->hasMany(TilawatiAttendance::class, 'tilawati_session_id');
    }
}
