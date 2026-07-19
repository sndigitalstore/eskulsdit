<?php
namespace App\Exports;

use App\Models\Achievement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BackupAchievementsSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $yearId;

    public function __construct($yearId)
    {
        $this->yearId = $yearId;
    }

    public function collection()
    {
        $query = Achievement::with(['student', 'academicYear']);
        
        if ($this->yearId) {
            $query->where(function($q) {
                $q->where('academic_year_id', $this->yearId)
                  ->orWhere(function($subQ) {
                      $subQ->whereNull('academic_year_id')
                           ->whereHas('student', function($studentQ) {
                               $studentQ->where('academic_year_id', $this->yearId);
                           });
                  });
            });
        }

        return $query->orderBy('date', 'desc')
            ->get()
            ->map(function($achievement) {
                return [
                    'student_nis' => $achievement->student->nis ?? '-',
                    'student_name' => $achievement->student->name ?? 'Unknown',
                    'student_class' => $achievement->student->class ?? '-',
                    'achievement_name' => $achievement->name,
                    'level' => $achievement->level,
                    'organizer' => $achievement->organizer,
                    'date' => $achievement->date,
                    'description' => $achievement->description,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Nama Prestasi',
            'Tingkat',
            'Penyelenggara',
            'Tanggal',
            'Keterangan',
        ];
    }

    public function title(): string
    {
        return 'Data Prestasi';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
