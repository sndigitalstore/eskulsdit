<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EskulJournal extends Model
{
    protected $fillable = [
        'eskul_id', 'academic_year_id', 'semester',
        'tanggal', 'pertemuan_ke', 'materi',
        'deskripsi', 'hadir', 'tidak_hadir', 'izin', 'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'hadir'   => 'integer',
        'tidak_hadir' => 'integer',
        'izin'    => 'integer',
    ];

    public function eskul()
    {
        return $this->belongsTo(Eskul::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
