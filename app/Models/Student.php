<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'class', 'status', 'photo', 'nis', 'parent_phone'];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function eskuls()
    {
        return $this->belongsToMany(Eskul::class, 'student_eskul');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }
}
