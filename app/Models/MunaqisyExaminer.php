<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MunaqisyExaminer extends Model
{
    protected $fillable = [
        'user_id',
        'specialization', // 'tilawati', 'tahfidz', 'both'
        'certificate_number',
        'phone',
        'status', // 'active', 'inactive'
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registrations()
    {
        return $this->hasMany(MunaqosahRegistration::class, 'examiner_id');
    }

    public function testedRegistrations()
    {
        return $this->hasMany(MunaqosahRegistration::class, 'examiner_id')->whereIn('status', ['passed', 'failed']);
    }

    public function schedules()
    {
        return $this->hasMany(MunaqisySchedule::class, 'examiner_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForType($query, $type)
    {
        if ($type === 'tilawati') {
            return $query->whereIn('specialization', ['tilawati', 'both']);
        } elseif ($type === 'tahfidz') {
            return $query->whereIn('specialization', ['tahfidz', 'both']);
        }
        return $query;
    }
}
