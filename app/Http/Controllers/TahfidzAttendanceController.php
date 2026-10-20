<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\MentoringGroup;
use App\Models\Student;
use App\Models\TahfidzAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TahfidzAttendanceController extends Controller
{
    /**
     * Auto-create table if not exists (self-healing)
     */
    private function ensureTableExists(): void
    {
        if (!Schema::hasTable('tahfidz_attendances')) {
            Schema::create('tahfidz_attendances', function ($table) {
                $table->id();
                $table->unsignedBigInteger('mentoring_group_id');
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('academic_year_id');
                $table->date('session_date');
                $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha'])->default('hadir');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('recorded_by')->nullable();
                $table->timestamps();
                $table->unique(['mentoring_group_id', 'student_id', 'session_date'], 'unique_tahfidz_attendance');
                $table->index(['mentoring_group_id', 'session_date']);
            });
        }
    }

    /**
     * Daftar sesi absensi untuk satu kelompok
     */
    public function index(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId     = $request->input('year_id', $activeYear?->id);

        // Ambil semua tanggal sesi unik
        $sessions = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->selectRaw('session_date, COUNT(*) as total_siswa,
                SUM(status = "hadir") as hadir,
                SUM(status = "sakit") as sakit,
                SUM(status = "izin") as izin,
                SUM(status = "alpha") as alpha')
            ->groupBy('session_date')
            ->orderBy('session_date', 'desc')
            ->get();

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $selectedYear  = AcademicYear::find($yearId);
        $totalSiswa    = $group->students()->count();

        return view('tahfidz.attendance_index', compact(
            'group', 'sessions', 'academicYears', 'selectedYear', 'yearId', 'totalSiswa'
        ));
    }

    /**
     * Form input absensi sesi baru / edit sesi yang ada
     */
    public function create(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId     = $request->input('year_id', $activeYear?->id);
        $date       = $request->input('date', date('Y-m-d'));

        // Ambil siswa kelompok
        $students = $group->students()->orderBy('class')->orderBy('name')->get();

        // Cek apakah sudah ada absensi di tanggal ini
        $existing = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->get()
            ->keyBy('student_id');

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        return view('tahfidz.attendance_form', compact(
            'group', 'students', 'existing', 'date', 'yearId', 'academicYears'
        ));
    }

    /**
     * Simpan / update absensi sesi (upsert per siswa)
     */
    public function store(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $request->validate([
            'session_date'      => 'required|date',
            'academic_year_id'  => 'required|exists:academic_years,id',
            'attendance'        => 'required|array',
            'attendance.*.status' => 'required|in:hadir,sakit,izin,alpha',
        ]);

        $date   = $request->session_date;
        $yearId = $request->academic_year_id;
        $userId = Auth::id();

        foreach ($request->attendance as $studentId => $data) {
            TahfidzAttendance::updateOrCreate(
                [
                    'mentoring_group_id' => $group->id,
                    'student_id'         => $studentId,
                    'session_date'       => $date,
                ],
                [
                    'academic_year_id' => $yearId,
                    'status'           => $data['status'],
                    'notes'            => $data['notes'] ?? null,
                    'recorded_by'      => $userId,
                ]
            );
        }

        return redirect()
            ->route('tahfidz.attendance.index', ['group' => $group->id, 'year_id' => $yearId])
            ->with('success', "Absensi sesi tanggal " . date('d/m/Y', strtotime($date)) . " berhasil disimpan!");
    }

    /**
     * Hapus seluruh absensi satu sesi (berdasarkan tanggal)
     */
    public function destroySession(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $date = $request->input('date');
        TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->delete();

        return redirect()
            ->route('tahfidz.attendance.index', ['group' => $group->id])
            ->with('success', "Absensi sesi " . date('d/m/Y', strtotime($date)) . " dihapus.");
    }

    /**
     * Detail absensi satu sesi
     */
    public function showSession(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $date = $request->input('date');
        $records = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->with('student')
            ->get()
            ->sortBy(fn($r) => $r->student->class . $r->student->name);

        $yearId       = $records->first()?->academic_year_id;
        $academicYears= AcademicYear::orderBy('name', 'desc')->get();

        return view('tahfidz.attendance_session', compact(
            'group', 'records', 'date', 'yearId', 'academicYears'
        ));
    }

    /**
     * Print absensi satu sesi
     */
    public function printSession(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $date = $request->input('date');
        $records = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->where('session_date', $date)
            ->with('student')
            ->get()
            ->sortBy(fn($r) => $r->student->class . $r->student->name)
            ->values();

        $yearId   = $records->first()?->academic_year_id;
        $yearName = AcademicYear::find($yearId)?->name ?? '-';

        return view('tahfidz.print_attendance', compact(
            'group', 'records', 'date', 'yearName'
        ));
    }

    /**
     * Print rekap absensi seluruh sesi dalam satu kelompok
     */
    public function printRecap(Request $request, MentoringGroup $group)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId     = $request->input('year_id', $activeYear?->id);
        $yearName   = AcademicYear::find($yearId)?->name ?? '-';

        // Ambil semua tanggal sesi
        $sessionDates = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->distinct()
            ->orderBy('session_date')
            ->pluck('session_date');

        // Ambil semua siswa kelompok
        $students = $group->students()->orderBy('class')->orderBy('name')->get();

        // Semua absensi untuk lookup
        $allAttendances = TahfidzAttendance::where('mentoring_group_id', $group->id)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->get()
            ->groupBy('student_id');

        return view('tahfidz.print_attendance_recap', compact(
            'group', 'students', 'sessionDates', 'allAttendances', 'yearName', 'yearId'
        ));
    }
}
