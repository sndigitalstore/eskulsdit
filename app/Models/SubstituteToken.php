<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubstituteToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'eskul_id',
        'user_id',
        'date',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'date' => 'date',
    ];

    public function eskul()
    {
        return $this->belongsTo(Eskul::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return $this->expires_at->isFuture();
    }
}
