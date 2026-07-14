<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionLog extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'student_name',
        'student_class',
        'choice_1',
        'parent_phone',
        'choice_2',
        'ip_address',
        'user_agent'
    ];
}
