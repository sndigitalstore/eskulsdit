<?php
namespace App\Exports;

use App\Models\Grade;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BackupGradesSheet implements FromView, WithTitle, ShouldAutoSize
{
    protected $yearId;

    public function __construct($yearId)
    {
        $this->yearId = $yearId;
    }

    public function view(): View
    {
        // Fetch Students who have eskuls in the active year
        $students = Student::with(['eskuls' => function($q) {
            $q->wherePivot('academic_year_id', $this->yearId);
        }])->whereHas('eskuls', function($q) {
            $q->where('student_eskul.academic_year_id', $this->yearId);
        })
        ->orderBy('class')
        ->orderBy('name')
        ->get();

        $data = [];

        foreach ($students as $student) {
            foreach ($student->eskuls as $eskul) {
                // Fetch grades for this specific student-eskul-year combo
                $grades = Grade::where('student_id', $student->id)
                    ->where('eskul_id', $eskul->id)
                    ->where('academic_year_id', $this->yearId)
                    ->get();
                
                // Get Scores
                $sas1 = $grades->where('type', 'sas1')->first()->score ?? '-';
                $sas2 = $grades->where('type', 'sas2')->first()->score ?? '-';
                
                // For Daily, get the latest one
                $dailyGrade = $grades->where('type', 'daily')->sortByDesc('date')->first();
                $daily = $dailyGrade ? $dailyGrade->score : '-';

                // Helper buffer
                $formatScore = function($score) {
                    if (!$score || $score === '-') return '-';
                    $json = json_decode($score, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                         // Format Calistung Compact: B:Reading | T:Writing | H:Counting
                         $r = $json['reading'] ?? '-';
                         $w = $json['writing'] ?? '-';
                         $c = $json['counting'] ?? '-';
                         return "B:{$r} | T:{$w} | H:{$c}";
                    }
                    return $score;
                };

                $data[] = (object) [
                    'student_name' => $student->name,
                    'nis' => $student->nis,
                    'class' => $student->class,
                    'eskul_name' => $eskul->name,
                    'daily' => $formatScore($daily),
                    'sas1' => $formatScore($sas1),
                    'sas2' => $formatScore($sas2),
                ];
            }
        }

        // Convert to collection for convenience in view if needed, or just pass array
        return view('exports.backup_grades', ['rows' => $data]);
    }

    public function title(): string
    {
        return 'Nilai';
    }
}
