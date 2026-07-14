<?php

namespace App\Exports;

use App\Models\Eskul;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GradeTemplateExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $eskulId;
    protected $yearId;
    protected $class;

    public function __construct($eskulId, $yearId, $class = null)
    {
        $this->eskulId = $eskulId;
        $this->yearId = $yearId;
        $this->class = $class;
    }

    public function collection()
    {
        $eskul = Eskul::with(['students' => function($q) {
            $q->wherePivot('academic_year_id', $this->yearId)
              ->where('status', '!=', 'graduated');
            
            if ($this->class) {
                $q->where('class', $this->class);
            }
            
            $q->orderBy('class', 'asc')
              ->orderBy('name', 'asc');
        }])->findOrFail($this->eskulId);

        return $eskul->students;
    }

    public function headings(): array
    {
        return [
            'ID Siswa (JANGAN DIUBAH)',
            'Nama Siswa',
            'Kelas',
            'Nilai Harian (0-100)',
            'Nilai SAS 1 (0-100)',
            'Nilai SAS 2 (A/B/C/0-100)', // Just generic score text
            'Keterangan (Opsional)'
        ];
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->name,
            $student->class,
            '', // Empty for input
            '', // Empty for input
            '', // Empty for input
            ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '5381ff']]],
        ];
    }
}
