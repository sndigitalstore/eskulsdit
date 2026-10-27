<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EskulProgramItem extends Model
{
    protected $fillable = [
        'eskul_id', 'academic_year_id', 'semester',
        'bulan', 'kegiatan', 'tujuan_kegiatan',
        'tempat', 'waktu', 'penanggung_jawab', 'urutan',
    ];

    public function eskul()
    {
        return $this->belongsTo(Eskul::class);
    }
}
