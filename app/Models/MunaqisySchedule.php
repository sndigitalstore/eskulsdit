<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MunaqisySchedule extends Model
{
    protected $fillable = [
        'examiner_id',
        'academic_year_id',
        'day_name',        // e.g. 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'
        'date',            // nullable date untuk tanggal spesifik jika ada
        'time_start',      // e.g. '08:00'
        'time_end',        // e.g. '09:00'
        'exam_type',       // 'all', 'tilawati', 'tahfidz'
        'room_location',   // e.g. 'Ruang Musholla', 'Kelas 1A'
        'quota',           // integer default 1
        'status',          // 'available', 'full', 'inactive'
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'quota' => 'integer',
    ];

    public function examiner()
    {
        return $this->belongsTo(MunaqisyExaminer::class, 'examiner_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function registrations()
    {
        return $this->hasMany(MunaqosahRegistration::class, 'schedule_id');
    }

    /**
     * Hitung jumlah siswa yang mendaftar pada slot ini
     */
    public function getUsedSlotsAttribute(): int
    {
        return $this->registrations()->count();
    }

    /**
     * Cek apakah slot sudah penuh / terpenuhi
     */
    public function getIsFullAttribute(): bool
    {
        if ($this->status === 'full') {
            return true;
        }
        if ($this->quota > 0 && $this->used_slots >= $this->quota) {
            return true;
        }
        return false;
    }

    /**
     * Teks deskripsi format slot jadwal
     */
    public function getFormattedSlotAttribute(): string
    {
        $dayOrDate = $this->date ? $this->date->format('d/m/Y') . " ({$this->day_name})" : $this->day_name;
        $examinerName = $this->examiner?->user?->name ?? 'Penguji';
        $time = "{$this->time_start} - {$this->time_end}";
        $room = $this->room_location ? " [{$this->room_location}]" : "";
        return "{$examinerName} — {$dayOrDate} ({$time}){$room}";
    }
}
