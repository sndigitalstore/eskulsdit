<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\MentoringGroup;
use App\Models\Student;
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

        return view('tahfidz.student_card', compact(
            'group', 'student', 'records',
            'academicYears', 'selectedYear', 'yearId',
            'totalSetoran', 'totalMurojaah', 'surahUnique'
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

        TahfidzRecord::create([
            ...$validated,
            'student_id'        => $student->id,
            'mentoring_group_id'=> $group->id,
            'recorded_by'       => Auth::id(),
        ]);

        return redirect()
            ->route('tahfidz.student-card', ['group' => $group->id, 'student' => $student->id, 'year_id' => $validated['academic_year_id']])
            ->with('success', "Setoran hafalan {$validated['surah_name']} berhasil disimpan!");
    }

    /**
     * Hapus satu record setoran
     */
    public function destroy(Request $request, TahfidzRecord $record)
    {
        $this->ensureTableExists();

        $groupId  = $record->mentoring_group_id;
        $studentId= $record->student_id;
        $yearId   = $record->academic_year_id;
        $surah    = $record->surah_name;

        $record->delete();

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
