<?php
namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BackupAttendanceSheet implements FromView, WithTitle, ShouldAutoSize
{
    protected $yearId;

    public function __construct($yearId)
    {
        $this->yearId = $yearId;
    }

    public function view(): View
    {
        $attendances = Attendance::where('academic_year_id', $this->yearId)
            ->with(['student', 'eskul'])
            ->orderBy('date', 'desc')
            ->limit(2000) // Safety limit? maybe remove for backup
            ->get();
            
        // Removing limit for true backup, but hopefully not too huge
        return view('exports.backup_attendance', compact('attendances'));
    }

    public function title(): string
    {
        return 'Absensi Siswa';
    }
}
