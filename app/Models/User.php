<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'academic_year_id',
        'name',
        'username',
        'role',
        'eskul_id',
        'email',
        'phone',
        'password',
        'homeroom_class',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if ($user->role === 'teacher' && empty($user->academic_year_id)) {
                $activeYear = AcademicYear::where('is_active', true)->first();
                if ($activeYear) {
                    $user->academic_year_id = $activeYear->id;
                }
            }
        });
    }

    public function scopeActiveYear($query)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if ($activeYear) {
            return $query->where(function($q) use ($activeYear) {
                $q->where('users.academic_year_id', $activeYear->id)
                  ->orWhere('users.role', 'admin');
            });
        }
        return $query;
    }

    public function scopeForYear($query, $yearId)
    {
        return $query->where(function($q) use ($yearId) {
            $q->where('users.academic_year_id', $yearId)
              ->orWhere('users.role', 'admin');
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function eskul()
    {
        return $this->belongsTo(Eskul::class);
    }
}
