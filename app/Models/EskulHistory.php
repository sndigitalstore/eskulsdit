<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EskulHistory extends Model
{
    protected $fillable = ['eskul_id', 'academic_year_id', 'semester', 'alias_name', 'instructor_name', 'schedule'];

    public function eskul()
    {
        return $this->belongsTo(Eskul::class);
    }
}
