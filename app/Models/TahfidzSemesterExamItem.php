<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahfidzSemesterExamItem extends Model
{
    use HasFactory;

    protected $table = 'tahfidz_semester_exam_items';

    protected $fillable = [
        'tahfidz_semester_exam_id',
        'surah_name',
        'juz',
        'score',
        'predicate',
        'notes',
        'order_num',
    ];

    protected $casts = [
        'score'     => 'float',
        'juz'       => 'integer',
        'order_num' => 'integer',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(TahfidzSemesterExam::class, 'tahfidz_semester_exam_id');
    }
}
