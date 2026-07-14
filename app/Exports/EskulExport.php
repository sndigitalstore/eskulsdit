<?php

namespace App\Exports;

use App\Models\Eskul;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EskulExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $eskul;

    public function __construct(Eskul $eskul)
    {
        $this->eskul = $eskul;
    }

    public function view(): View
    {
        return view('exports.eskul', [
            'eskul' => $this->eskul
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
