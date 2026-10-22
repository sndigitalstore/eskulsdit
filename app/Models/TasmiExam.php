<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TasmiExam extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'mentoring_group_id',
        'category',
        'juz_tested',
        'exam_date',
        'examiner_name',
        'examiner_user_id',
        'mistakes_fath',
        'mistakes_waqaf',
        'mistakes_tajwid',
        'total_mistakes',
        'final_score',
        'predicate',
        'status',
        'notes',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'final_score' => 'float',
        'mistakes_fath' => 'integer',
        'mistakes_waqaf' => 'integer',
        'mistakes_tajwid' => 'integer',
        'total_mistakes' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function mentoringGroup()
    {
        return $this->belongsTo(MentoringGroup::class);
    }

    public function examinerUser()
    {
        return $this->belongsTo(User::class, 'examiner_user_id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'setengah_juz' => 'Tasmi\' Setengah Juz',
            'satu_juz'     => 'Tasmi\' 1 Juz Bil Ghaib',
            'dua_juz'      => 'Tasmi\' 2 Juz Bil Ghaib',
            'tiga_juz'     => 'Tasmi\' 3 Juz Bil Ghaib',
            default        => 'Tasmi\' Al-Qur\'an',
        };
    }
}
