<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\AcademicYear;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassEskulExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $class;
    protected $yearId;
    protected $period;
    private $rowNumber = 0;

    public function __construct($class, $yearId, $period = 'all')
    {
        $this->class = $class;
        $this->yearId = $yearId;
        $this->period = $period;
    }

    public function collection()
    {
        return Student::where('class', $this->class)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')
                  ->orWhereNull('status');
            })
            ->with(['eskuls' => function($q) {
                $q->wherePivot('academic_year_id', $this->yearId);
                if ($this->period != 'all') {
                    $q->wherePivot('semester', $this->period);
                }
            }])
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Kelas',
            'Ekstrakurikuler',
            'Jadwal & Tempat',
            'Pembina'
        ];
    }

    public function map($student): array
    {
        $this->rowNumber++;
        
        $eskuls = $student->eskuls;

        if ($eskuls->isEmpty()) {
             return [
                 $this->rowNumber,
                 $student->name,
                 $student->class,
                 '-',
                 '-',
                 '-'
             ];
        }

        $eskulNames = $eskuls->map(fn($e) => $e->name)->implode("\n");
        $schedules = $eskuls->map(fn($e) => $e->schedule)->implode("\n");
        $instructors = $eskuls->map(fn($e) => $e->instructor_name)->implode("\n");

        return [
            $this->rowNumber,
            $student->name,
            $student->class,
            $eskulNames,
            $schedules,
            $instructors
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2980b9']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
            'A'  => ['alignment' => ['horizontal' => 'center', 'vertical' => 'top']], // No
            'B'  => ['alignment' => ['vertical' => 'top']], // Nama
            'C'  => ['alignment' => ['horizontal' => 'center', 'vertical' => 'top']], // Kelas
            'D'  => ['alignment' => ['wrapText' => true, 'vertical' => 'top']], // Eskul
            'E'  => ['alignment' => ['wrapText' => true, 'vertical' => 'top']], // Jadwal
            'F'  => ['alignment' => ['wrapText' => true, 'vertical' => 'top']], // Pembina
        ];
    }
}
