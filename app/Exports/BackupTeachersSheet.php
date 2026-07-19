<?php
namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BackupTeachersSheet implements FromView, WithTitle, ShouldAutoSize
{
    protected $yearId;

    public function __construct($yearId)
    {
        $this->yearId = $yearId;
    }

    public function view(): View
    {
        $teachers = User::where('role', 'teacher')
            ->where('academic_year_id', $this->yearId)
            ->with('eskul')
            ->orderBy('name')
            ->get();

        return view('exports.backup_teachers', compact('teachers'));
    }

    public function title(): string
    {
        return 'Data Guru';
    }
}
