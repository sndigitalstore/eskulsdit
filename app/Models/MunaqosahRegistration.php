<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MunaqosahRegistration extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'from_group_id',
        'proposer_user_id',
        'proposer_notes',
        'examiner_id',
        'examiner_user_id',
        'type', // 'tilawati', 'tahfidz'
        'current_level',
        'target_level',
        'registration_date',
        'exam_date',
        'exam_time',
        'room_location',
        'status', // 'pending', 'scheduled', 'passed', 'failed'
        'score_tajwid',
        'score_fashahah',
        'score_makhraj',
        'score_adab_lagu',
        'final_score',
        'predicate', // 'Mumtaz', 'Jayyid Jiddan', 'Jayyid', 'Maqbul', 'Rasib'
        'examiner_notes',
        'recommendation',
        'tested_at',
        'transition_status', // 'none', 'pending_promotion', 'promoted'
        'promoted_to_group_id',
        'promoted_at',
        'promoted_by',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'exam_date' => 'date',
        'tested_at' => 'datetime',
        'promoted_at' => 'datetime',
        'score_tajwid' => 'float',
        'score_fashahah' => 'float',
        'score_makhraj' => 'float',
        'score_adab_lagu' => 'float',
        'final_score' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function fromGroup()
    {
        return $this->belongsTo(MentoringGroup::class, 'from_group_id');
    }

    public function proposer()
    {
        return $this->belongsTo(User::class, 'proposer_user_id');
    }

    public function examiner()
    {
        return $this->belongsTo(MunaqisyExaminer::class, 'examiner_id');
    }

    public function examinerUser()
    {
        return $this->belongsTo(User::class, 'examiner_user_id');
    }

    public function promotedToGroup()
    {
        return $this->belongsTo(MentoringGroup::class, 'promoted_to_group_id');
    }

    public function promotedByUser()
    {
        return $this->belongsTo(User::class, 'promoted_by');
    }

    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePendingPromotion($query)
    {
        return $query->where('status', 'passed')->where('transition_status', 'pending_promotion');
    }

    public function scopeForYear($query, $yearId)
    {
        if ($yearId) {
            return $query->where('academic_year_id', $yearId);
        }
        return $query;
    }

    public function scopeForType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }

    public static function getPredicateByScore(?float $score): string
    {
        if ($score === null) return '-';
        if ($score >= 90) return 'Mumtaz';
        if ($score >= 80) return 'Jayyid Jiddan';
        if ($score >= 70) return 'Jayyid';
        if ($score >= 60) return 'Maqbul';
        return 'Rasib / Perlu Mengulang';
    }
}
