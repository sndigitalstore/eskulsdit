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
        'teaching_subject_ids',
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
                  ->orWhereIn('users.role', ['admin', 'headmaster', 'curriculum']);
            });
        }
        return $query;
    }

    public function scopeForYear($query, $yearId)
    {
        return $query->where(function($q) use ($yearId) {
            $q->where('users.academic_year_id', $yearId)
              ->orWhereIn('users.role', ['admin', 'headmaster', 'curriculum']);
        });
    }

    public function isCurriculum(): bool
    {
        return $this->role === 'curriculum';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHeadmaster(): bool
    {
        return $this->role === 'headmaster';
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
            'teaching_subject_ids' => 'array',
        ];
    }
    
    public function eskul()
    {
        return $this->belongsTo(Eskul::class);
    }

    public function mentoringGroups()
    {
        return $this->hasMany(MentoringGroup::class, 'mentor_user_id');
    }

    public function isHomeroom(): bool
    {
        return $this->role === 'teacher' && !empty($this->homeroom_class);
    }

    public function isEskulCoach(): bool
    {
        return $this->role === 'teacher' && !empty($this->eskul_id);
    }

    public function isQuranMentor(): bool
    {
        if ($this->role !== 'teacher') return false;
        $inGroup = MentoringGroup::where(function($q) {
            $q->where('mentor_user_id', $this->id)
              ->orWhere('mentor_name', $this->name);
        })->exists();

        if ($inGroup) return true;

        if (\Illuminate\Support\Facades\Schema::hasTable('munaqisy_examiners')) {
            return \App\Models\MunaqisyExaminer::where('user_id', $this->id)->where('status', 'active')->exists();
        }

        return false;
    }

    public function isSubjectTeacher(): bool
    {
        return $this->role === 'teacher' && !empty($this->teaching_subject_ids);
    }

    public function teachingSubjects()
    {
        if (empty($this->teaching_subject_ids)) {
            return collect();
        }
        return Subject::whereIn('id', $this->teaching_subject_ids)->get();
    }

    public function getActiveRolesSummary(): array
    {
        $roles = [];
        if ($this->role === 'admin') $roles[] = 'Kesiswaan / Administrator';
        if ($this->role === 'headmaster') $roles[] = 'Kepala Sekolah';
        if ($this->role === 'curriculum') $roles[] = 'Tim Kurikulum';
        
        if ($this->role === 'teacher') {
            if ($this->isHomeroom()) {
                $roles[] = 'Wali Kelas ' . $this->homeroom_class;
            }
            if ($this->isEskulCoach()) {
                $roles[] = 'Pembina ' . ($this->eskul ? $this->eskul->name : 'Ekstrakurikuler');
            }
            
            $groups = MentoringGroup::where(function($q) {
                $q->where('mentor_user_id', $this->id)
                  ->orWhere('mentor_name', $this->name);
            })->get();

            $hasTilawati = $groups->where('type', 'tilawati')->isNotEmpty();
            $hasTahfidz = $groups->where('type', 'tahfidz')->isNotEmpty();
            if ($hasTilawati) $roles[] = 'Pembimbing Tilawati';
            if ($hasTahfidz) $roles[] = 'Pembimbing Tahfidz';

            if (\Illuminate\Support\Facades\Schema::hasTable('munaqisy_examiners')) {
                $isMunaqisy = \App\Models\MunaqisyExaminer::where('user_id', $this->id)->where('status', 'active')->exists();
                if ($isMunaqisy) {
                    $roles[] = 'Penguji Munaqisy';
                }
            }

            if ($this->isSubjectTeacher()) {
                $subjNames = $this->teachingSubjects()->pluck('name')->implode(', ');
                $roles[] = 'Guru Mapel' . ($subjNames ? ' (' . $subjNames . ')' : '');
            }

            if (empty($roles)) {
                $roles[] = 'Guru';
            }
        }

        return $roles;
    }
}
