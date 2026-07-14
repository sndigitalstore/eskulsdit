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
            $query->where('academic_year_id', $this->yearId);
        }

        return $query->orderBy('date', 'desc')
            ->get()
            ->map(function($achievement) {
                return [
                    'student_name' => $achievement->student->name ?? 'Unknown',
                    'student_class' => $achievement->student->class ?? '-',
                    'year' => $achievement->academicYear->name ?? '-',
                    'semester' => $achievement->semester ? 'Semester ' . $achievement->semester : '-',
                    'achievement_name' => $achievement->name,
                    'level' => $achievement->level,
                    'date' => $achievement->date,
                    'organizer' => $achievement->organizer,
                    'description' => $achievement->description,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Kelas',
            'Tahun Pelajaran',
            'Semester',
            'Nama Prestasi',
            'Tingkat',
            'Tanggal',
            'Penyelenggara',
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
