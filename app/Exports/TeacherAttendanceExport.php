<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeacherAttendanceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $attendances;
    
    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        $data = [];
        $rowNumber = 0;
        
        foreach ($this->attendances as $attendance) {
            $rowNumber++;
            $status = match($attendance->status) {
                'present' => 'Hadir',
                'sick' => 'Sakit',
                'permission' => 'Izin',
                default => 'Alpha',
            };
            
            $data[] = [
                $rowNumber,
                date('d M Y', strtotime($attendance->date)),
                $attendance->user->name ?? '-',
                $attendance->clock_in_time ?? '-',
                $status,
                $attendance->substitute_name ?? '-',
                $attendance->note ?? '-'
            ];
        }

        // Add Spacers
        $data[] = ['', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', ''];

        // Add Summary Title
        $data[] = ['REKAPITULASI TOTAL (UNTUK PENGHITUNGAN INSENTIF)', '', '', '', '', '', ''];
        
        // Add Summary Headers
        $data[] = ['No', 'Nama Guru', 'Hadir', 'Sakit', 'Izin', 'Alpha', 'Total Sesi'];

        $summary = $this->attendances->groupBy('user_id');
        $no = 1;
        foreach($summary as $userId => $records) {
            $teacherName = $records->first()->user->name ?? '-';
            $hadir = $records->where('status', 'present')->count();
            $sakit = $records->where('status', 'sick')->count();
            $izin = $records->where('status', 'permission')->count();
            $alpha = $records->where('status', 'alpha')->count();
            $total = $records->count();

            $data[] = [
                $no++,
                $teacherName,
                $hadir . ' Kali',
                $sakit . ' Kali',
                $izin . ' Kali',
                $alpha . ' Kali',
                $total . ' Kali'
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            ['LAPORAN ABSENSI HARIAN & REKAP BULANAN GURU SDIT AN NADZIR'],
            [],
            [
                'No',
                'Tanggal',
                'Nama Guru',
                'Waktu Masuk',
                'Status Kehadiran',
                'Guru Pengganti',
                'Catatan/Alasan'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $totalDailyRows = $this->attendances->count();
        $lastDailyRow = $totalDailyRows + 3;
        
        $summaryStartIndex = $lastDailyRow + 3;
        
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        $sheet->getStyle('A3:G3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => 'FF059669']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);
        
        if($totalDailyRows > 0) {
            $sheet->getStyle('A4:G' . $lastDailyRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
            ]);
        }
        
        $sheet->mergeCells('A' . $summaryStartIndex . ':G' . $summaryStartIndex);
        $sheet->getStyle('A' . $summaryStartIndex)->getFont()->setBold(true)->setSize(12);
        
        $summaryHeaderRow = $summaryStartIndex + 1;
        $sheet->getStyle('A' . $summaryHeaderRow . ':G' . $summaryHeaderRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => 'FFFDE047']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);
        
        $uniqueTeachersCount = $this->attendances->groupBy('user_id')->count();
        $summaryLastRow = $summaryHeaderRow + $uniqueTeachersCount;
        
        if($uniqueTeachersCount > 0) {
            $sheet->getStyle('A' . ($summaryHeaderRow + 1) . ':G' . $summaryLastRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
            ]);
        }
        
        return [];
    }
}
