<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['student_id', 'eskul_id', 'academic_year_id', 'semester', 'type', 'score', 'date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function eskul()
    {
        return $this->belongsTo(Eskul::class);
    }
}
