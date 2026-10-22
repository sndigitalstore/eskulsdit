<?php

namespace App\Exports;

use App\Models\AcademicYear;
use App\Models\MentoringGroup;
use App\Models\Student;
use App\Models\TahfidzAttendance;
use App\Models\TahfidzRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TahfidzRecapExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $yearId;
    protected $class;
    protected $groupId;

    public function __construct($yearId = null, $class = null, $groupId = null)
    {
        $this->yearId = $yearId;
        $this->class = $class;
        $this->groupId = $groupId;
    }

    public function collection()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $this->yearId ?? ($activeYear?->id);

        $query = Student::forYear($selectedYearId)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')->orWhereNull('status');
            });

        if ($this->class && $this->class !== 'ALL') {
            $query->where('class', $this->class);
        }

        if ($this->groupId) {
            $query->whereHas('mentoringGroups', function($q) {
                $q->where('mentoring_groups.id', $this->groupId);
            });
        }

        $students = $query->with([
            'mentoringGroups' => function($q) use ($selectedYearId) {
                $q->where('mentoring_groups.type', 'tahfidz')
                  ->when($selectedYearId, fn($sub) => $sub->where('mentoring_groups.academic_year_id', $selectedYearId));
            },
            'tahfidzRecords' => function($q) use ($selectedYearId) {
                $q->when($selectedYearId, fn($sub) => $sub->where('academic_year_id', $selectedYearId))
                  ->orderBy('session_date', 'desc')
                  ->orderBy('id', 'desc');
            }
        ])->orderBy('class')->orderBy('name')->get();

        $allStudentIds = $students->pluck('id');
        $attendances = TahfidzAttendance::whereIn('student_id', $allStudentIds)
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->get()
            ->groupBy('student_id');

        $rows = [];
        $no = 1;

        foreach ($students as $std) {
            $grp = $std->mentoringGroups->first();
            $records = $std->tahfidzRecords;
            $latest = $records->first();

            $totalSetoran = $records->count();
            $totalBaris = (int) $records->sum('jumlah_baris');
            $baruCount = $records->where('type', 'setoran_baru')->count();
            $murojaahCount = $records->where('type', 'murojaah')->count();
            $lancarCount = $records->where('quality', 'lancar')->count();
            $lancarPercent = $totalSetoran > 0 ? round(($lancarCount / $totalSetoran) * 100) : 0;

            // Presensi
            $stdAtt = $attendances->get($std->id, collect());
            $hadir = $stdAtt->where('status', 'hadir')->count();
            $sakit = $stdAtt->where('status', 'sakit')->count();
            $izin = $stdAtt->where('status', 'izin')->count();
            $alpha = $stdAtt->where('status', 'alpha')->count();

            // Predikat Mutu
            if ($lancarPercent >= 85) {
                $pred = 'Mumtaz (Istimewa)';
                $rekomendasi = 'Siap Munaqosah';
            } elseif ($lancarPercent >= 65) {
                $pred = 'Jayyid Jiddan (Amat Baik)';
                $rekomendasi = 'Tingkatkan Muroja\'ah';
            } else {
                $pred = 'Jayyid (Cukup/Perlu Bimbingan)';
                $rekomendasi = 'Pendampingan Intensif';
            }

            $capaianStr = $latest 
                ? ($latest->surah_name . ($latest->start_ayah ? ' (Ayat ' . $latest->start_ayah . ($latest->end_ayah && $latest->end_ayah != $latest->start_ayah ? '-' . $latest->end_ayah : '') . ')' : '') . ($latest->jumlah_baris ? ' [' . $latest->jumlah_baris . ' Baris]' : ''))
                : '-';

            $rows[] = [
                'no'             => $no++,
                'nis'            => $std->nis ?? '-',
                'name'           => $std->name,
                'class'          => $std->class,
                'group'          => $grp ? $grp->name : 'Belum Diplotting',
                'mentor'         => $grp ? ($grp->mentorUser?->name ?? $grp->mentor_name ?? '-') : '-',
                'target'         => $grp ? ($grp->target_level ?? 'Juz 30') : 'Juz 30',
                'latest_capaian' => $capaianStr,
                'total_records'  => $totalSetoran,
                'total_baris'    => $totalBaris,
                'ziyadah'        => $baruCount,
                'murojaah'       => $murojaahCount,
                'mutqin_rate'    => $lancarPercent . '%',
                'hadir'          => $hadir,
                'sakit'          => $sakit,
                'izin'           => $izin,
                'alpha'          => $alpha,
                'predikat'       => $pred,
                'rekomendasi'    => $rekomendasi,
            ];
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'Nama Lengkap Siswa',
            'Kelas',
            'Kelompok / Halaqah',
            'Musyrif / Pembimbing',
            'Target Hafalan',
            'Capaian Terakhir',
            'Total Setoran (Kali)',
            'Total Baris Dihafal',
            'Ziyadah (Baru)',
            'Muroja\'ah',
            'Tingkat Mutqin (%)',
            'Kehadiran (H)',
            'Sakit (S)',
            'Izin (I)',
            'Alpha (A)',
            'Predikat Capaian',
            'Rekomendasi Mutu',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header Styling
        $sheet->getStyle('A1:S1')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
        $sheet->getStyle('A1:S1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:S1')->getFill()->getStartColor()->setARGB('FF047857'); // Emerald Green
        $sheet->getStyle('A1:S1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Center alignments
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2:B' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D2:D' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I2:Q' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
