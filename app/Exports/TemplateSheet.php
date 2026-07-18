<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TemplateSheet implements FromArray, WithTitle, WithStyles
{
    protected string $sheetTitle;
    protected array $rows;

    public function __construct(string $title, array $rows)
    {
        $this->sheetTitle = $title;
        $this->rows = $rows;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow    = $sheet->getHighestRow();

        // Style header row (row 1)
        $headerRange = "A1:{$highestColumn}1";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1A5276'], // Dark blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FFAAAAAA'],
                ],
            ],
        ]);

        // Style data rows (zebra striping)
        for ($row = 2; $row <= $highestRow; $row++) {
            $bgColor = ($row % 2 === 0) ? 'FFD6EAF8' : 'FFFFFFFF'; // light blue or white
            $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $bgColor],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['argb' => 'FFCCCCCC'],
                    ],
                ],
            ]);
        }

        // Auto-size columns
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);
    }
}
