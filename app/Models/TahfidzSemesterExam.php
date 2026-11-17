<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahfidzSemesterExam extends Model
{
    use HasFactory;

    protected $table = 'tahfidz_semester_exams';

    protected $fillable = [
        'student_id',
        'mentoring_group_id',
        'academic_year_id',
        'semester',
        'exam_date',
        'examiner_user_id',
        'examiner_name',
        'average_score',
        'predicate',
        'notes',
    ];

    protected $casts = [
        'exam_date'     => 'date',
        'average_score' => 'float',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function mentoringGroup(): BelongsTo
    {
        return $this->belongsTo(MentoringGroup::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function examiner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'examiner_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TahfidzSemesterExamItem::class, 'tahfidz_semester_exam_id')->orderBy('order_num')->orderBy('id');
    }

    /**
     * Hitung predikat standar kelulusan
     */
    public static function resolvePredicate(float $score): string
    {
        if ($score >= 90) return 'Mumtaz';
        if ($score >= 80) return 'Jayyid Jiddan';
        if ($score >= 70) return 'Jayyid';
        return 'Maqbul';
    }

    /**
     * Hitung ulang rata-rata dan predikat dari relasi items
     */
    public function recalculateAverage(): void
    {
        $avg = $this->items()->avg('score');
        $this->average_score = $avg !== null ? round((float)$avg, 2) : 0.00;
        $this->predicate = self::resolvePredicate((float)$this->average_score);
        $this->save();
    }
}
