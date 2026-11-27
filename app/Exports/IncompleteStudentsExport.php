<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class IncompleteStudentsExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected Collection $students;
    protected string $title;
    protected string $year;

    public function __construct(Collection $students, string $title, string $year)
    {
        $this->students = $students;
        $this->title    = $title;
        $this->year     = $year;
    }

    public function title(): string
    {
        return 'Data Siswa';
    }

    public function headings(): array
    {
        return [
            ['LAPORAN KELENGKAPAN BIODATA SISWA - ' . strtoupper($this->title)],
            ['Tahun Ajaran: ' . $this->year . ' | Dicetak: ' . now()->format('d/m/Y H:i')],
            [], // blank row
            [
                'No',
                'NIS',
                'NISN',
                'Nama Lengkap Siswa',
                'Kelas',
                'Jenis Kelamin',
                'Tempat / Tgl Lahir',
                'Alamat',
                'Asal Sekolah',
                'Nama Ayah',
                'Nama Ibu',
                'Pekerjaan Ayah',
                'Pekerjaan Ibu',
                'No. HP Ayah',
                'No. HP Ibu',
                'Tgl Diterima',
                'Program',
                'Field Kosong',
            ],
        ];
    }

    public function collection(): Collection
    {
        $rows = collect();
        $no   = 1;

        foreach ($this->students as $s) {
            $emptyFields = [];
            if (empty($s->nisn))             $emptyFields[] = 'NISN';
            if (empty($s->gender))           $emptyFields[] = 'Jenis Kelamin';
            if (empty($s->birth_place_date)) $emptyFields[] = 'Tgl Lahir';
            if (empty($s->address))          $emptyFields[] = 'Alamat';
            if (empty($s->father_name) && empty($s->mother_name)) $emptyFields[] = 'Nama Ortu';
            $hasPhone = !empty($s->father_phone) || !empty($s->mother_phone) || !empty($s->parent_phone);
            if (!$hasPhone) $emptyFields[] = 'HP Ortu';
            if (empty($s->school_program))   $emptyFields[] = 'Program';
            if (empty($s->previous_school))  $emptyFields[] = 'Asal Sekolah';

            $rows->push([
                $no++,
                $s->nis ?? '',
                $s->nisn ?? '',
                $s->name,
                $s->class ?? '',
                $s->gender ?? '',
                $s->birth_place_date ?? '',
                $s->address ?? '',
                $s->previous_school ?? '',
                $s->father_name ?? '',
                $s->mother_name ?? '',
                $s->father_job ?? '',
                $s->mother_job ?? '',
                $s->father_phone ?? '',
                $s->mother_phone ?? '',
                $s->admission_date ?? '',
                $s->school_program ?? '',
                implode(', ', $emptyFields) ?: '-',
            ]);
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): void
    {
        // Title rows
        $sheet->mergeCells('A1:R1');
        $sheet->mergeCells('A2:R2');
        $sheet->mergeCells('A3:R3');

        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '065f46']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 10, 'italic' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '047857']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(24);

        // Header row (row 4)
        $sheet->getStyle('A4:R4')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e3a5f']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(28);

        // Data rows
        $lastRow = max(5, $this->students->count() + 4);
        $sheet->getStyle("A5:R{$lastRow}")->applyFromArray([
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'e2e8f0']]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);

        // Highlight "Field Kosong" column (R) in orange if not empty / '-'
        for ($row = 5; $row <= $lastRow; $row++) {
            $val = $sheet->getCell("R{$row}")->getValue();
            if ($val && $val !== '-') {
                $sheet->getStyle("R{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'dc2626']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fff7ed']],
                ]);
            }
        }

        // Freeze header
        $sheet->freezePane('A5');
    }
}
