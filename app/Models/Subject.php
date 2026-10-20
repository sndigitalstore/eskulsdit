<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'kkm',
        'order_num',
        'is_active',
    ];

    protected $casts = [
        'kkm' => 'decimal:2',
        'is_active' => 'boolean',
        'order_num' => 'integer',
    ];

    public function scores()
    {
        return $this->hasMany(AcademicScore::class);
    }
}
