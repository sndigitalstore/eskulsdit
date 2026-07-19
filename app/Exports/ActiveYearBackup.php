<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class ActiveYearBackup implements WithMultipleSheets
{
    use Exportable;

    protected $yearId;

    public function __construct($yearId)
    {
        $this->yearId = $yearId;
    }

    public function sheets(): array
    {
        return [
            new BackupStudentsSheet($this->yearId),
            new BackupGradesSheet($this->yearId),
            new BackupAttendanceSheet($this->yearId),
            new BackupAchievementsSheet($this->yearId),
            new BackupTeachersSheet($this->yearId),
            new BackupTeacherAttendanceSheet($this->yearId),
        ];
    }
}
