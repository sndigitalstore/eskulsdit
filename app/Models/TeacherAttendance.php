<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    protected $fillable = ['user_id', 'academic_year_id', 'date', 'status', 'note', 'clock_in_time', 'substitute_name', 'substitute_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function substituteUser()
    {
        return $this->belongsTo(User::class, 'substitute_user_id');
    }
}
