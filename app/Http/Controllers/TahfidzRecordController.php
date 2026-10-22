<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\MentoringGroup;
use App\Models\Student;
use App\Models\TahfidzAttendance;
use App\Models\TahfidzRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TahfidzRecordController extends Controller
{
    /**
     * Auto-create table if not exists (self-healing, no CLI needed)
     */
    private function ensureTableExists(): void
    {
        if (!Schema::hasTable('tahfidz_records')) {
            Schema::create('tahfidz_records', function ($table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('mentoring_group_id');
                $table->unsignedBigInteger('academic_year_id');
                $table->string('surah_name');
                $table->unsignedSmallInteger('juz')->nullable();
                $table->unsignedSmallInteger('start_ayah')->nullable();
                $table->unsignedSmallInteger('end_ayah')->nullable();
                $table->enum('type', ['setoran_baru', 'murojaah'])->default('setoran_baru');
                $table->enum('quality', ['lancar', 'cukup', 'perlu_perbaikan'])->default('lancar');
                $table->date('session_date');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('recorded_by')->nullable();
                $table->timestamps();
                $table->index(['student_id', 'academic_year_id']);
                $table->index(['mentoring_group_id', 'session_date']);
            });
        }
    }

    /**
     * Halaman kartu hafalan seorang siswa dalam kelompok
     */
    public function studentCard(Request $request, MentoringGroup $group, Student $student)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId     = $request->input('year_id', $activeYear?->id);

        $records = TahfidzRecord::where('student_id', $student->id)
            ->where('mentoring_group_id', $group->id)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->orderBy('session_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $academicYears  = AcademicYear::orderBy('name', 'desc')->get();
        $selectedYear   = AcademicYear::find($yearId);

        // Statistik ringkas
        $totalSetoran   = $records->where('type', 'setoran_baru')->count();
        $totalMurojaah  = $records->where('type', 'murojaah')->count();
        $surahUnique    = $records->pluck('surah_name')->unique()->count();

        // Target Hafalan Progress (Juz 30 & Juz 29)
        $juz30Surahs = [
            'An-Naba', 'An-Nazi\'at', 'Abasa', 'At-Takwir', 'Al-Infitar',
            'Al-Mutaffifin', 'Al-Insyiqaq', 'Al-Buruj', 'At-Tariq', 'Al-A\'la',
            'Al-Ghasyiyah', 'Al-Fajr', 'Al-Balad', 'Asy-Syams', 'Al-Lail',
            'Ad-Duha', 'Asy-Syarh', 'At-Tin', 'Al-\'Alaq', 'Al-Qadr',
            'Al-Bayyinah', 'Az-Zalzalah', 'Al-\'Adiyat', 'Al-Qari\'ah', 'At-Takasur',
            'Al-\'Asr', 'Al-Humazah', 'Al-Fil', 'Quraisy', 'Al-Ma\'un',
            'Al-Kausar', 'Al-Kafirun', 'An-Nasr', 'Al-Masad', 'Al-Ikhlas',
            'Al-Falaq', 'An-Nas'
        ];

        $juz29Surahs = [
            'Al-Mulk', 'Al-Qalam', 'Al-Haqqah', 'Al-Ma\'arij', 'Nuh',
            'Al-Jinn', 'Al-Muzammil', 'Al-Muddassir', 'Al-Qiyamah', 'Al-Insan', 'Al-Mursalat'
        ];

        // Map status surah untuk Juz 30
        $recordedSurahNames = $records->groupBy(function($r) {
            return strtolower(preg_replace('/[^a-z0-9]/', '', $r->surah_name));
        });

        $checkSurahStatus = function($surahList, $juzNum) use ($recordedSurahNames) {
            $list = [];
            $completedCount = 0;
            foreach ($surahList as $name) {
                $cleanKey = strtolower(preg_replace('/[^a-z0-9]/', '', $name));
                $foundRecords = $recordedSurahNames->get($cleanKey, collect());
                
                $status = 'unrecorded'; // unrecorded, lancar, cukup, perlu_perbaikan
                if ($foundRecords->isNotEmpty()) {
                    if ($foundRecords->contains('quality', 'lancar')) {
                        $status = 'lancar';
                        $completedCount++;
                    } elseif ($foundRecords->contains('quality', 'cukup')) {
                        $status = 'cukup';
                        $completedCount++;
                    } else {
                        $status = 'perlu_perbaikan';
                    }
                }

                $list[] = [
                    'name' => $name,
                    'juz' => $juzNum,
                    'status' => $status,
                    'count' => $foundRecords->count(),
                ];
            }

            return [
                'surahs' => $list,
                'total' => count($surahList),
                'completed' => $completedCount,
                'percent' => count($surahList) > 0 ? round(($completedCount / count($surahList)) * 100) : 0,
            ];
        };

        $juz30Data = $checkSurahStatus($juz30Surahs, 30);
        $juz29Data = $checkSurahStatus($juz29Surahs, 29);

        return view('tahfidz.student_card', compact(
            'group', 'student', 'records',
            'academicYears', 'selectedYear', 'yearId',
            'totalSetoran', 'totalMurojaah', 'surahUnique',
            'juz30Data', 'juz29Data'
        ));
    }

    /**
     * Simpan record setoran hafalan baru
     */
    public function store(Request $request, MentoringGroup $group, Student $student)
    {
        $this->ensureTableExists();

        $validated = $request->validate([
            'surah_name'   => 'required|string|max:100',
            'juz'          => 'nullable|integer|min:1|max:30',
            'start_ayah'   => 'nullable|integer|min:1',
            'end_ayah'     => 'nullable|integer|min:1',
            'type'         => 'required|in:setoran_baru,murojaah',
            'quality'      => 'required|in:lancar,cukup,perlu_perbaikan',
            'session_date' => 'required|date',
            'notes'        => 'nullable|string|max:500',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $newRecord = TahfidzRecord::create([
            ...$validated,
            'student_id'        => $student->id,
            'mentoring_group_id'=> $group->id,
            'recorded_by'       => Auth::id(),
        ]);

        // SINKRONISASI OTOMATIS KE TAHFIDZ_ATTENDANCE SESI MENTORING
        if (Schema::hasTable('tahfidz_attendances')) {
            $ayahSuffix = $newRecord->start_ayah 
                ? " ayat {$newRecord->start_ayah}" . ($newRecord->end_ayah && $newRecord->end_ayah != $newRecord->start_ayah ? "-{$newRecord->end_ayah}" : "") 
                : "";
            $capaianStr = $newRecord->surah_name . $ayahSuffix;
            $hafalanStatus = ($newRecord->quality === 'lancar' || $newRecord->quality === 'cukup') ? 'LULUS' : 'BELUM LULUS';

            TahfidzAttendance::updateOrCreate(
                [
                    'mentoring_group_id' => $group->id,
                    'student_id'         => $student->id,
                    'session_date'       => $newRecord->session_date,
                ],
                [
                    'academic_year_id' => $validated['academic_year_id'],
                    'status'           => 'hadir',
                    'capaian'          => $capaianStr,
                    'hafalan_status'   => $hafalanStatus,
                    'notes'            => $newRecord->notes,
                    'recorded_by'      => Auth::id(),
                ]
            );
        }

        $qualityLabel = match($newRecord->quality) {
            'lancar' => 'Lancar (Mumtaz)',
            'cukup'  => 'Cukup Lancar',
            default  => 'Perlu Perbaikan / Bimbingan',
        };

        $typeLabel = $newRecord->type === 'setoran_baru' ? 'Ziyadah (Hafalan Baru)' : 'Muroja\'ah (Mengulang)';
        $ayahLabel = $newRecord->start_ayah 
            ? "Ayat {$newRecord->start_ayah}" . ($newRecord->end_ayah && $newRecord->end_ayah != $newRecord->start_ayah ? "-{$newRecord->end_ayah}" : '')
            : 'Selesai';

        $redirect = ($request->input('redirect_to') === 'workspace' || $request->has('from_workspace'))
            ? redirect()->route('tahfidz-groups.index', ['group_id' => $group->id])
            : redirect()->route('tahfidz.student-card', ['group' => $group->id, 'student' => $student->id, 'year_id' => $validated['academic_year_id']]);

        return $redirect
            ->with('success', "Setoran hafalan ananda {$student->name} ({$validated['surah_name']}) berhasil disimpan dan otomatis tersinkron ke Rekapan & Absensi!")
            ->with('last_record', [
                'id'            => $newRecord->id,
                'student_id'    => $student->id,
                'student_name'  => $student->name,
                'student_class' => $student->class,
                'parent_phone'  => $student->parent_phone,
                'surah_name'    => $newRecord->surah_name,
                'ayah'          => $ayahLabel,
                'quality'       => $newRecord->quality,
                'quality_label' => $qualityLabel,
                'type'          => $newRecord->type,
                'type_label'    => $typeLabel,
                'date'          => \Carbon\Carbon::parse($newRecord->session_date)->isoFormat('dddd, D MMMM Y'),
                'notes'         => $newRecord->notes ?? 'Pertahankan hafalan dengan muroja\'ah istiqomah di rumah.',
                'mentor'        => Auth::user()->name,
            ]);
    }

    /**
     * Hapus satu record setoran dan sinkronkan ke absensi jika perlu
     */
    public function destroy(Request $request, TahfidzRecord $record)
    {
        $this->ensureTableExists();

        $groupId    = $record->mentoring_group_id;
        $studentId  = $record->student_id;
        $yearId     = $record->academic_year_id;
        $surah      = $record->surah_name;
        $sessionDate= $record->session_date;

        $record->delete();

        // Sinkronisasi pembaruan ke TahfidzAttendance
        if (Schema::hasTable('tahfidz_attendances')) {
            $remaining = TahfidzRecord::where('student_id', $studentId)
                ->where('mentoring_group_id', $groupId)
                ->where('session_date', $sessionDate)
                ->first();

            if ($remaining) {
                $ayahSuffix = $remaining->start_ayah ? " ayat {$remaining->start_ayah}" . ($remaining->end_ayah && $remaining->end_ayah != $remaining->start_ayah ? "-{$remaining->end_ayah}" : "") : "";
                $capaianStr = $remaining->surah_name . $ayahSuffix;
                TahfidzAttendance::where('student_id', $studentId)
                    ->where('mentoring_group_id', $groupId)
                    ->where('session_date', $sessionDate)
                    ->update([
                        'capaian'        => $capaianStr,
                        'hafalan_status' => ($remaining->quality === 'lancar' || $remaining->quality === 'cukup') ? 'LULUS' : 'BELUM LULUS',
                    ]);
            } else {
                TahfidzAttendance::where('student_id', $studentId)
                    ->where('mentoring_group_id', $groupId)
                    ->where('session_date', $sessionDate)
                    ->update([
                        'capaian' => null,
                    ]);
            }
        }

        return redirect()
            ->route('tahfidz.student-card', ['group' => $groupId, 'student' => $studentId, 'year_id' => $yearId])
            ->with('success', "Catatan setoran {$surah} dihapus.");
    }

    /**
     * Print Raport Tahfidz Standar Nasional Siswa
     */
    public function printCard(Request $request, MentoringGroup $group, Student $student)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId     = $request->input('year_id', $activeYear?->id);

        return app(ReportController::class)->raportTahfidz($request->merge([
            'student_id' => $student->id,
            'group_id'   => $group->id,
            'year_id'    => $yearId
        ]));
    }

    /**
     * Print rekap seluruh kelompok tahfidz
     */
    public function printGroup(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId     = $request->input('year_id', $activeYear?->id);
        $yearName   = AcademicYear::find($yearId)?->name ?? '-';

        // Ambil seluruh siswa kelompok beserta record hafalan mereka
        $students = $group->students()
            ->orderBy('class')
            ->orderBy('name')
            ->get()
            ->map(function ($student) use ($group, $yearId) {
                $student->hafalan = TahfidzRecord::where('student_id', $student->id)
                    ->where('mentoring_group_id', $group->id)
                    ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                    ->orderBy('session_date', 'asc')
                    ->get();
                return $student;
            });

        return view('tahfidz.print_group_recap', compact(
            'group', 'students', 'yearName', 'yearId'
        ));
    }
}
