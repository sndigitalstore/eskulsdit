<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CalistungGraduatesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if (!$activeYear) return collect([]);

        // Get Calistung Eskuls (case insensitive search)
        $calistungEskuls = \App\Models\Eskul::where('name', 'like', '%Calistung%')->pluck('id');

        if ($calistungEskuls->isEmpty()) return collect([]);

        // Get grades for these eskuls in active year
        $grades = \App\Models\Grade::with(['student', 'eskul'])
            ->whereIn('eskul_id', $calistungEskuls)
            ->where('academic_year_id', $activeYear->id)
            ->get();

        $graduates = [];

        foreach ($grades as $grade) {
            $studentId = $grade->student_id;
            $scoreStr = $grade->score;
            
            // Attempt to decode JSON or parse format
            $score = json_decode($scoreStr, true);
            $parsed = [];

            if (is_array($score)) {
                $parsed = $score;
            } else {
                 // Try parsing raw string formats
                 // 1. Single Letter A (Means all A)
                 if (preg_match('/^[A]$/i', trim($scoreStr))) {
                     $parsed = ['reading' => 'A', 'writing' => 'A', 'counting' => 'A'];
                 }
                 // 2. Pipe Format "B:A | T:A ..."
                 elseif (str_contains($scoreStr, '|')) {
                     $parts = explode('|', $scoreStr);
                     foreach ($parts as $part) {
                         $sub = explode(':', $part);
                         if (count($sub) >= 2) {
                             $k = strtoupper(trim($sub[0]));
                             $v = trim($sub[1]);
                             if ($k == 'B') $parsed['reading'] = $v;
                             if ($k == 'T') $parsed['writing'] = $v;
                             if ($k == 'H') $parsed['counting'] = $v;
                         }
                     }
                 }
                 // 3. Fallback "Membaca: A"
                 else {
                      if (preg_match('/Membaca\s*[:=]\s*(.*?)(?=\s+Menulis|\s+Berhitung|$)/ui', $scoreStr, $m)) $parsed['reading'] = trim($m[1]);
                      if (preg_match('/Menulis\s*[:=]\s*(.*?)(?=\s+Berhitung|$)/ui', $scoreStr, $m)) $parsed['writing'] = trim($m[1]);
                      if (preg_match('/Berhitung\s*[:=]\s*(.*?)(?=$)/ui', $scoreStr, $m)) $parsed['counting'] = trim($m[1]);
                 }
            }
            
            // Check for 'A' (Case insensitive)
            $achievements = [];
            if (isset($parsed['reading']) && strtoupper(trim($parsed['reading'])) === 'A') $achievements[] = 'Membaca';
            if (isset($parsed['writing']) && strtoupper(trim($parsed['writing'])) === 'A') $achievements[] = 'Menulis';
            if (isset($parsed['counting']) && strtoupper(trim($parsed['counting'])) === 'A') $achievements[] = 'Berhitung';

            if (count($achievements) === 3) {
                if (!isset($graduates[$studentId])) {
                     $graduates[$studentId] = [
                        'name' => $grade->student->name ?? 'Unknown',
                        'class' => $grade->student->class ?? '-',
                        'eskul' => $grade->eskul->name ?? 'Calistung',
                        'achievements' => [],
                     ];
                }
                
                // Merge new achievements
                $graduates[$studentId]['achievements'] = array_unique(array_merge($graduates[$studentId]['achievements'], $achievements));
            }
        }

        // Transform to collection for Excel
        return collect(array_values($graduates))->map(function ($item) {
            return [
                'Nama Siswa' => $item['name'],
                'Kelas' => $item['class'],
                'Eskul Asal' => $item['eskul'],
                'Kompetensi Lulus (Nilai A)' => implode(', ', $item['achievements']),
                'Keterangan' => 'Direkomendasikan Lanjut Eskul Lain',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Kelas',
            'Eskul Asal',
            'Kompetensi Lulus (Nilai A)',
            'Keterangan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
