<?php
namespace App\Exports;

use App\Models\TeacherAttendance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BackupTeacherAttendanceSheet implements FromView, WithTitle, ShouldAutoSize
{
    protected $yearId;

    public function __construct($yearId)
    {
        $this->yearId = $yearId;
    }

    public function view(): View
    {
        $attendances = TeacherAttendance::where('academic_year_id', $this->yearId)
            ->with('user')
            ->orderBy('date', 'desc')
            ->get();
            
        return view('exports.backup_teacher_attendance', compact('attendances'));
    }

    public function title(): string
    {
        return 'Absensi Guru';
    }
}
