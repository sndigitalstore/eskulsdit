<?php

namespace App\Imports;

use App\Models\Grade;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GradesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if (!$activeYear) return; // Or handle error

        $yearId = $activeYear->id;
        $semester = $activeYear->active_semester ?? '1';

        foreach ($rows as $row) {
            // 1. Detect Student
            // Priority 1: ID
            $studentId = $row['id_siswa_jangan_diubah'] ?? null;

            // Priority 2: Name (If ID is missing)
            if (!$studentId) {
                $name = $row['nama_lengkap'] ?? $row['nama_siswa'] ?? $row['nama'] ?? null;
                if ($name) {
                    // Scope student lookup to active year only
                    $student = \App\Models\Student::where('academic_year_id', $yearId)
                        ->where('name', $name)->first();
                    if ($student) {
                        $studentId = $student->id;
                    }
                }
            }

            if (!$studentId) continue;
            
            // 2. Detect Eskul
            $eskulId = null;
            $eskulName = $row['ekstrakurikuler'] ?? null;
            $isCalistung = false;
            
            if ($eskulName) {
                // Priority 1: Exact Match
                $eskul = \App\Models\Eskul::where('name', trim($eskulName))->first();
                
                // Priority 2: Automatic Creation (If not found)
                if (!$eskul) {
                    $eskul = \App\Models\Eskul::create([
                        'name' => trim($eskulName),
                        'instructor_name' => 'Belum ditentukan',
                        'schedule' => 'Belum diatur'
                    ]);
                    
                    // Create Initial History for the new Eskul
                    \App\Models\EskulHistory::create([
                        'eskul_id' => $eskul->id,
                        'academic_year_id' => $yearId,
                        'semester' => $semester,
                        'alias_name' => $eskul->name,
                        'instructor_name' => $eskul->instructor_name,
                        'schedule' => $eskul->schedule
                    ]);
                }
                
                $eskulId = $eskul->id;
                if (stripos($eskul->name, 'Calistung') !== false) {
                    $isCalistung = true;
                }
            }
            
            // If no eskul found row-by-row, maybe skip? The data has 'Ekstrakurikuler' column.
            if (!$eskulId) continue;

            // 3. Save Grades
            // Daily Score
            if (isset($row['nilai_harian_0_100']) && $row['nilai_harian_0_100'] !== null) {
                $this->saveGrade($studentId, $eskulId, $yearId, $semester, 'daily', $row['nilai_harian_0_100'], $isCalistung);
            }

            // SAS 1 (Standard & Custom)
            if (isset($row['nilai_sas_1_0_100']) && $row['nilai_sas_1_0_100'] !== null) {
                $this->saveGrade($studentId, $eskulId, $yearId, $semester, 'sas1', $row['nilai_sas_1_0_100'], $isCalistung);
            }
            if (isset($row['sas_1']) && $row['sas_1'] !== null) {
                $this->saveGrade($studentId, $eskulId, $yearId, $semester, 'sas1', $row['sas_1'], $isCalistung);
            }

            // SAS 2 (Standard & Custom)
            if (isset($row['nilai_sas_2_abc0_100']) && $row['nilai_sas_2_abc0_100'] !== null) {
                $this->saveGrade($studentId, $eskulId, $yearId, $semester, 'sas2', $row['nilai_sas_2_abc0_100'], $isCalistung);
            }
            if (isset($row['sas_2']) && $row['sas_2'] !== null) {
                $this->saveGrade($studentId, $eskulId, $yearId, $semester, 'sas2', $row['sas_2'], $isCalistung);
            }
        }
    }

    private function saveGrade($studentId, $eskulId, $yearId, $semester, $type, $score, $isCalistung = false)
    {
        // Special Logic for Calistung
        if ($isCalistung && is_string($score)) {
            $parsed = [];
            $cleanScore = trim($score);

            // 1. Single Letter Expansion (A, B, C)
            // User requirement: "jika pada kolom khusus calistung hanya ada nilai A ... maka sistem akan otomastis menyimpan B:B | T:B |H:B"
            // Wait, example says: "Calistung A nilai SAS 1 nya B, maka otomatis sistem menyimpan B:B | T:B |H:B"
            // (Assuming typo in user prompt "B:B" from "B", but user said "B:A | T:B | H:A" as first example).
            // Basically expand the single letter to all 3 components.
            if (preg_match('/^[A-C]$/i', $cleanScore)) {
                $parsed = [
                    'reading' => $cleanScore,
                    'writing' => $cleanScore,
                    'counting' => $cleanScore
                ];
                $score = json_encode($parsed);
            }
            // 2. Parse "B:A | T:B | H:A" format
            elseif (str_contains($cleanScore, '|')) {
                $parts = explode('|', $cleanScore);
                foreach ($parts as $part) {
                    $subObj = explode(':', $part);
                    if (count($subObj) == 2) {
                        $key = strtoupper(trim($subObj[0]));
                        $val = trim($subObj[1]);
                        if ($key == 'B') $parsed['reading'] = $val;
                        if ($key == 'T') $parsed['writing'] = $val;
                        if ($key == 'H') $parsed['counting'] = $val;
                    }
                }
                if (!empty($parsed)) {
                    $score = json_encode($parsed);
                }
            }
        }

        // Parse complex string format for Calistung (Old standard: "Membaca: A Menulis: B Berhitung: A")
        if (is_string($score) && !str_contains($score, '{') && (str_contains(strtolower($score), 'membaca:') || str_contains(strtolower($score), 'menulis:'))) {
            $parsed = [];
            
            // Membaca: [Value]
            if (preg_match('/Membaca\s*:\s*([^\r\n]*)($|\s+Menulis)/i', $score, $matches)) {
                $parsed['reading'] = trim($matches[1]);
            }
            // Menulis: [Value]
            if (preg_match('/Menulis\s*:\s*([^\r\n]*)($|\s+Berhitung)/i', $score, $matches)) {
                $parsed['writing'] = trim($matches[1]);
            }
            // Berhitung: [Value]
            if (preg_match('/Berhitung\s*:\s*([^\r\n]*)/i', $score, $matches)) {
                 $parsed['counting'] = trim($matches[1]);
            }

            if (!empty($parsed)) {
                $score = json_encode($parsed);
            }
        }
        
        // Also handle the case where Excel might split multi-line cells 
        if (is_string($score) && !str_contains($score, '{') && (str_contains($score, "\n"))) {
             $lines = explode("\n", $score);
             $parsed = [];
             foreach ($lines as $line) {
                 if (str_contains(strtolower($line), 'membaca:')) {
                     $parts = explode(':', $line);
                     $parsed['reading'] = isset($parts[1]) ? trim($parts[1]) : '';
                 }
                 if (str_contains(strtolower($line), 'menulis:')) {
                     $parts = explode(':', $line);
                     $parsed['writing'] = isset($parts[1]) ? trim($parts[1]) : '';
                 }
                 if (str_contains(strtolower($line), 'berhitung:')) {
                     $parts = explode(':', $line);
                     $parsed['counting'] = isset($parts[1]) ? trim($parts[1]) : '';
                 }
             }
             if (!empty($parsed)) {
                 $score = json_encode($parsed);
             }
        }

        Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'eskul_id' => $eskulId,
                'academic_year_id' => $yearId,
                'semester' => $semester,
                'type' => $type
            ],
            [
                'score' => $score,
                'date' => now(), // Default import date
            ]
        );
    }
}
