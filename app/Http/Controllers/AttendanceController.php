<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Eskul;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Bersihkan baris presensi ganda jika ada (Self-healing deduplication)
     */
    public static function cleanupDuplicateAttendances(?int $eskulId = null): void
    {
        try {
            $duplicates = \DB::table('attendances')
                ->select('student_id', 'eskul_id', 'date', 'academic_year_id', 'semester', \DB::raw('MIN(id) as keep_id'), \DB::raw('COUNT(*) as total'))
                ->when($eskulId, fn($q) => $q->where('eskul_id', $eskulId))
                ->groupBy('student_id', 'eskul_id', 'date', 'academic_year_id', 'semester')
                ->having('total', '>', 1)
                ->get();

            foreach ($duplicates as $dup) {
                \DB::table('attendances')
                    ->where('student_id', $dup->student_id)
                    ->where('eskul_id', $dup->eskul_id)
                    ->where('date', $dup->date)
                    ->where('academic_year_id', $dup->academic_year_id)
                    ->where('semester', $dup->semester)
                    ->where('id', '!=', $dup->keep_id)
                    ->delete();
            }
        } catch (\Throwable $e) {
            // Silently continue
        }
    }

    private function getAllowedEskulIds($user)
    {
        $eskulIds = [];
        if ($user->eskul_id) {
            $eskulIds[] = $user->eskul_id;
        }

        // Find delegated Eskuls for this user (where user was assigned as substitute)
        $delegatedEskulIds = \App\Models\TeacherAttendance::where('substitute_user_id', $user->id)
            ->whereIn('status', ['sick', 'permission'])
            ->with('user')
            ->get()
            ->map(function ($ta) {
                return $ta->user ? $ta->user->eskul_id : null;
            })
            ->filter()
            ->toArray();

        return array_unique(array_merge($eskulIds, $delegatedEskulIds));
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->role == 'teacher') {
            if ($user->eskul_id && !$request->has('select')) {
                return redirect()->route('attendance.create', [
                    'eskul_id' => $user->eskul_id,
                    'date' => date('Y-m-d')
                ]);
            }
            $allowedIds = $this->getAllowedEskulIds($user);
            $eskuls = Eskul::activeYear()->has('students')->whereIn('id', $allowedIds)->get();
        } else {
            $eskuls = Eskul::activeYear()->has('students')->get();
        }
        
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = $academicYears->where('is_active', true)->first();
        return view('attendance.index', compact('eskuls', 'academicYears', 'activeYear'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'eskul_id' => 'required|exists:eskuls,id',
            'date' => 'required|date',
        ]);

        if (auth()->user()->role == 'teacher') {
            $allowedIds = $this->getAllowedEskulIds(auth()->user());
            if (!in_array($request->eskul_id, $allowedIds)) {
                abort(403, 'Anda tidak memiliki akses ke eskul ini.');
            }
        }

        // Determine context (History or Active)
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        
        $yearId = $request->academic_year_id;
        $semester = $request->semester;

        if (!$yearId) {
             // Fallback to active if not specified
             if (!$activeYear) return back()->withErrors(['msg' => 'Tidak ada tahun ajaran aktif.']);
             $yearId = $activeYear->id;
        }

        // Resolve semester: pakai dari request jika ada (untuk riwayat), otherwise ikuti tahun aktif
        $contextYear = \App\Models\AcademicYear::find($yearId);
        if (!$semester) {
            $semester = $contextYear ? $contextYear->active_semester : '1';
        }

        $eskul = Eskul::with(['students' => function($q) use ($yearId) {
             $q->wherePivot('academic_year_id', $yearId)
               ->where('status', '!=', 'graduated');
        }])->findOrFail($request->eskul_id);
        
        $date = $request->date;
        $students = $eskul->students;

        // Bersihkan sisa data duplikat jika ada
        self::cleanupDuplicateAttendances($request->eskul_id);

        // Check if attendance already exists for this date and eskul
        $existingAttendance = Attendance::where('eskul_id', $eskul->id)
            ->where('date', $date)
            ->where('academic_year_id', $yearId)
            ->where('semester', $semester)
            ->get()
            ->keyBy('student_id');

        $isAlreadySaved = $existingAttendance->isNotEmpty();
        $savedAt = $isAlreadySaved ? ($existingAttendance->first()->updated_at ?? $existingAttendance->first()->created_at) : null;
        $savedCount = $existingAttendance->count();

        return view('attendance.create', compact('eskul', 'date', 'students', 'existingAttendance', 'yearId', 'semester', 'contextYear', 'isAlreadySaved', 'savedAt', 'savedCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eskul_id' => 'required|exists:eskuls,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'in:present,absent,sick,permission',
            'notes' => 'nullable|array',
        ]);

        if (auth()->user()->role == 'teacher') {
            $allowedIds = $this->getAllowedEskulIds(auth()->user());
            if (!in_array($request->eskul_id, $allowedIds)) {
                abort(403, 'Anda tidak memiliki akses ke eskul ini.');
            }
        }

        $eskulId = $request->eskul_id;
        $date = $request->date;
        $attendanceData = $request->attendance;
        $notes = $request->notes ?? [];

        // Determine context (Use request input for history support)
        if ($request->filled('academic_year_id')) {
            $yearId = $request->academic_year_id;
        } else {
            // Fallback to active (Backwards compatibility protection)
            $activeYear = \App\Models\AcademicYear::where('is_active', true)->firstOrFail();
            $yearId = $activeYear->id;
        }
        // Resolve semester dari context tahun ajaran
        if ($request->filled('semester')) {
            $semester = $request->semester;
        } else {
            $contextYear = \App\Models\AcademicYear::find($yearId);
            $semester = $contextYear ? $contextYear->active_semester : '1';
        }

        // 1. CEK PENGISIAN GANDA: Jika presensi sudah tersimpan di tanggal ini, guru tidak boleh mengisi ulang (kunci 1x pengisian)
        $alreadySaved = Attendance::where('eskul_id', $eskulId)
            ->where('date', $date)
            ->where('academic_year_id', $yearId)
            ->where('semester', $semester)
            ->exists();

        if ($alreadySaved && auth()->user()->role === 'teacher') {
            $formattedDate = \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y');
            return redirect()->route('attendance.create', [
                'eskul_id' => $eskulId,
                'date' => $date,
                'academic_year_id' => $yearId,
                'semester' => $semester
            ])->with('error', "Presensi eskul pada tanggal {$formattedDate} sudah tersimpan. Guru tidak dapat melakukan pengisian 2 kali pada tanggal yang sama untuk mencegah data ganda.");
        }

        // 2. Enforce once-per-week student attendance check (Friday - Thursday cycle)
        $carbonDate = \Carbon\Carbon::parse($date);
        $startOfWeek = ($carbonDate->dayOfWeek === \Carbon\Carbon::FRIDAY)
            ? $carbonDate->copy()
            : $carbonDate->copy()->previous(\Carbon\Carbon::FRIDAY);
        $endOfWeek = $startOfWeek->copy()->addDays(6);

        $existingInWeek = Attendance::where('eskul_id', $eskulId)
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->where('date', '!=', $date)
            ->first();

        if ($existingInWeek) {
            $formattedExistingDate = \Carbon\Carbon::parse($existingInWeek->date)->isoFormat('D MMMM Y');
            return back()->with('error', "Absensi eskul ini sudah pernah diisi pada tanggal {$formattedExistingDate} di periode pekan berjalan (Jum'at - Kamis). Absensi siswa hanya diperbolehkan 1 kali per pekan.");
        }

        // 3. Simpan data presensi secara atomik dalam transaksi & bersihkan duplikasi
        \Illuminate\Support\Facades\DB::transaction(function() use ($attendanceData, $eskulId, $date, $yearId, $semester, $notes) {
            // Hapus data lama yang relevan jika ada (mencegah double record)
            Attendance::where('eskul_id', $eskulId)
                ->where('date', $date)
                ->where('academic_year_id', $yearId)
                ->where('semester', $semester)
                ->whereIn('student_id', array_keys($attendanceData))
                ->delete();

            $now = now();
            $records = [];
            foreach ($attendanceData as $studentId => $status) {
                $records[] = [
                    'student_id'       => $studentId,
                    'eskul_id'         => $eskulId,
                    'date'             => $date,
                    'status'           => $status,
                    'note'             => $notes[$studentId] ?? null,
                    'academic_year_id' => $yearId,
                    'semester'         => $semester,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
            }
            Attendance::insert($records);
        });

        $eskul = Eskul::find($eskulId);
        $eskulName = $eskul ? $eskul->name : 'Unknown';
        \App\Models\ActivityLog::log('Attendance', 'Create', "Mencatat absensi eskul {$eskulName} untuk tanggal {$date}.");

        if (auth()->user()->role == 'teacher') {
            return redirect()->route('attendance.create', [
                'eskul_id' => $eskulId,
                'date' => $date,
                'academic_year_id' => $yearId,
                'semester' => $semester
            ])->with('success', 'Presensi siswa berhasil disimpan dan data telah terkunci!');
        }

        return redirect()->route('attendance.index')->with('success', 'Absensi berhasil disimpan!');
    }

    public function report(Request $request)
    {
        $eskulId = $request->eskul_id;
        $yearId = $request->academic_year_id;

        if ($eskulId) {
            self::cleanupDuplicateAttendances((int)$eskulId);
        }

        if (auth()->user()->role == 'teacher') {
            $allowedIds = $this->getAllowedEskulIds(auth()->user());
            if (empty($allowedIds)) {
                abort(403, 'Akses ditolak. Anda tidak memiliki penugasan pembina eskul.');
            }
            if ($eskulId && !in_array($eskulId, $allowedIds)) {
                abort(403, 'Anda tidak memiliki akses ke laporan absensi eskul ini.');
            }
            if (empty($eskulId)) {
                $eskulId = $allowedIds[0];
            }
        }

        // Context
        if ($yearId) {
             $year = \App\Models\AcademicYear::find($yearId);
        } else {
             $year = \App\Models\AcademicYear::where('is_active', true)->first();
             $yearId = $year ? $year->id : null;
        }

        // Resolve semester: ikuti semester aktif dari tahun yang dipilih
        $semester = $request->semester;
        if (!$semester) {
            $semester = $year ? $year->active_semester : '1';
        }

        if(empty($eskulId)) {
            return redirect()->route('attendance.index')->with('error', 'Silakan pilih ekstrakurikuler terlebih dahulu.');
        }

        $eskul = Eskul::with(['students' => function($q) use ($yearId) {
            $q->wherePivot('academic_year_id', $yearId)
              ->where('status', '!=', 'graduated');
        }])->findOrFail($eskulId);

        // Fetch Attendance Summary for this Eskul & Year
        $students = $eskul->students->map(function($student) use ($eskulId, $yearId, $semester) {
             $summary = Attendance::where('student_id', $student->id)
                ->where('eskul_id', $eskulId)
                ->where('academic_year_id', $yearId)
                ->where('semester', $semester)
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

             $student->h = $summary['present'] ?? 0;
             $student->s = $summary['sick'] ?? 0;
             $student->i = $summary['permission'] ?? 0;
             $student->a = $summary['absent'] ?? 0;
             
             return $student;
        });

        // Get recorded dates for this eskul & year/semester
        $recordedDates = Attendance::where('eskul_id', $eskulId)
            ->where('academic_year_id', $yearId)
            ->where('semester', $semester)
            ->select('date', \DB::raw('count(*) as student_count'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
        
        return view('attendance.report', compact('eskul', 'students', 'year', 'recordedDates'));
    }

    public function destroyDate(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Kesiswaan / Admin yang dapat menghapus data absensi.');
        }

        $request->validate([
            'eskul_id' => 'required|exists:eskuls,id',
            'date' => 'required|date',
        ]);

        $eskul = Eskul::find($request->eskul_id);
        $eskulName = $eskul ? $eskul->name : 'Unknown';

        Attendance::where('eskul_id', $request->eskul_id)
            ->where('date', $request->date)
            ->delete();

        \App\Models\ActivityLog::log('Attendance', 'Delete', "Menghapus data absensi eskul {$eskulName} pada tanggal {$request->date}.");

        return back()->with('success', 'Data absensi pada tanggal tersebut berhasil dihapus!');
    }
}
