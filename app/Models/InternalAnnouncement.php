<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalAnnouncement extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
