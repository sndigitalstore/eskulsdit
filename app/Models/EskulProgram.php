<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EskulProgram extends Model
{
    protected $fillable = [
        'eskul_id', 'academic_year_id', 'semester',
        'visi', 'misi', 'tujuan', 'sasaran',
        'sarana', 'sumber_dana',
        'catatan_kepsek', 'kepsek_name', 'nip_kepsek',
    ];

    public function eskul()
    {
        return $this->belongsTo(Eskul::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function items()
    {
        return $this->hasMany(EskulProgramItem::class, 'eskul_id', 'eskul_id')
                    ->where('academic_year_id', $this->academic_year_id)
                    ->where('semester', $this->semester)
                    ->orderBy('urutan');
    }
}
