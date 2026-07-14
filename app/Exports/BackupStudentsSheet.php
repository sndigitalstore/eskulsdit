<?php
namespace App\Exports;

use App\Models\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BackupStudentsSheet implements FromView, WithTitle, ShouldAutoSize
{
    protected $yearId;

    public function __construct($yearId)
    {
        $this->yearId = $yearId;
    }

    public function view(): View
    {
        $students = Student::with(['eskuls' => function($q) {
            $q->wherePivot('academic_year_id', $this->yearId);
        }])->whereHas('eskuls', function($q) {
            $q->where('student_eskul.academic_year_id', $this->yearId);
        })
        ->orderBy('class')
        ->orderBy('name')
        ->get();

        return view('exports.backup_students', compact('students'));
    }

    public function title(): string
    {
        return 'Data Siswa';
    }
}
