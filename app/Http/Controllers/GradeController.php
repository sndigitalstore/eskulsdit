<?php

namespace App\Http\Controllers;

use App\Models\Eskul;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Bersihkan baris nilai ganda jika ada (Self-healing deduplication)
     */
    public static function cleanupDuplicateGrades(?int $eskulId = null): void
    {
        try {
            $duplicates = \DB::table('grades')
                ->select('student_id', 'eskul_id', 'type', 'date', 'academic_year_id', 'semester', \DB::raw('MIN(id) as keep_id'), \DB::raw('COUNT(*) as total'))
                ->when($eskulId, fn($q) => $q->where('eskul_id', $eskulId))
                ->groupBy('student_id', 'eskul_id', 'type', 'date', 'academic_year_id', 'semester')
                ->having('total', '>', 1)
                ->get();

            foreach ($duplicates as $dup) {
                $q = \DB::table('grades')
                    ->where('student_id', $dup->student_id)
                    ->where('eskul_id', $dup->eskul_id)
                    ->where('type', $dup->type)
                    ->where('academic_year_id', $dup->academic_year_id)
                    ->where('semester', $dup->semester)
                    ->where('id', '!=', $dup->keep_id);
                if ($dup->date) {
                    $q->where('date', $dup->date);
                } else {
                    $q->whereNull('date');
                }
                $q->delete();
            }
        } catch (\Throwable $e) {
            // Silently continue
        }
    }
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->role == 'teacher') {
            if ($user->eskul_id && !$request->has('select')) {
                return redirect()->route('grades.create', [
                    'eskul_id' => $user->eskul_id,
                    'type' => 'daily',
                    'date' => date('Y-m-d')
                ]);
            }
            $eskuls = $user->eskul_id 
                ? Eskul::activeYear()->has('students')->where('id', $user->eskul_id)->get()
                : collect();
            $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();
            $activeYear = $academicYears->where('is_active', true)->first();
            return view('grades.index', compact('eskuls', 'academicYears', 'activeYear'));
        }

        // ==========================================
        // DATA KHUSUS ADMIN / SUPERVISOR KESISWAAN
        // ==========================================
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = $academicYears->where('is_active', true)->first();

        $yearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        $selectedYear = $academicYears->firstWhere('id', $yearId) ?? $activeYear;
        $selectedSemester = $request->get('semester', $selectedYear ? $selectedYear->active_semester : '1');

        $eskuls = Eskul::forYear($yearId)->with(['students' => function($q) use ($yearId) {
            $q->wherePivot('academic_year_id', $yearId)
              ->where('status', '!=', 'graduated');
        }])->get();

        // 1 Query cepat untuk ringkasan penilaian semua eskul
        $gradesSummary = Grade::where('academic_year_id', $yearId)
            ->select(
                'eskul_id',
                'type',
                'date',
                \Illuminate\Support\Facades\DB::raw('count(distinct student_id) as student_count'),
                \Illuminate\Support\Facades\DB::raw('max(updated_at) as last_updated')
            )
            ->groupBy('eskul_id', 'type', 'date')
            ->get();

        // Susun data monitoring per eskul
        $eskulProgress = $eskuls->map(function($eskul) use ($gradesSummary, $selectedSemester) {
            $studentCount = $eskul->students->count();
            
            // Sesi nilai harian
            $dailySessions = $gradesSummary
                ->where('eskul_id', $eskul->id)
                ->where('type', 'daily')
                ->whereNotNull('date');
            $dailySessionCount = $dailySessions->count();

            // Nilai SAS 1
            $sas1Data = $gradesSummary
                ->where('eskul_id', $eskul->id)
                ->where('type', 'sas1');
            $sas1Count = $sas1Data->sum('student_count');
            $sas1Status = 'empty';
            if ($studentCount > 0 && $sas1Count >= $studentCount) {
                $sas1Status = 'complete';
            } elseif ($sas1Count > 0) {
                $sas1Status = 'partial';
            }

            // Nilai SAS 2
            $sas2Data = $gradesSummary
                ->where('eskul_id', $eskul->id)
                ->where('type', 'sas2');
            $sas2Count = $sas2Data->sum('student_count');
            $sas2Status = 'empty';
            if ($studentCount > 0 && $sas2Count >= $studentCount) {
                $sas2Status = 'complete';
            } elseif ($sas2Count > 0) {
                $sas2Status = 'partial';
            }

            // Aktivitas terakhir
            $lastUpdated = $gradesSummary->where('eskul_id', $eskul->id)->max('last_updated');

            return (object) [
                'id'                 => $eskul->id,
                'name'               => $eskul->name,
                'instructor_name'    => $eskul->instructor_name,
                'schedule'           => $eskul->schedule,
                'is_lockable'        => $eskul->is_lockable,
                'student_count'      => $studentCount,
                'daily_session_count'=> $dailySessionCount,
                'sas1_count'         => $sas1Count,
                'sas1_status'        => $sas1Status,
                'sas2_count'         => $sas2Count,
                'sas2_status'        => $sas2Status,
                'last_updated'       => $lastUpdated,
            ];
        });

        // KPI Ringkasan
        $kpi = (object) [
            'total_eskul'         => $eskuls->count(),
            'daily_active_count'  => $eskulProgress->where('daily_session_count', '>', 0)->count(),
            'sas1_complete_count' => $eskulProgress->where('sas1_status', 'complete')->count(),
            'sas2_complete_count' => $eskulProgress->where('sas2_status', 'complete')->count(),
            'total_grades_count'  => Grade::where('academic_year_id', $yearId)->count(),
        ];

        // 8 Riwayat input penilaian terbaru
        $recentGradingSessions = Grade::where('academic_year_id', $yearId)
            ->select(
                'eskul_id',
                'type',
                'date',
                'academic_year_id',
                'semester',
                \Illuminate\Support\Facades\DB::raw('count(distinct student_id) as student_count'),
                \Illuminate\Support\Facades\DB::raw('max(updated_at) as last_updated')
            )
            ->groupBy('eskul_id', 'type', 'date', 'academic_year_id', 'semester')
            ->orderByDesc('last_updated')
            ->take(8)
            ->with('eskul')
            ->get();

        return view('grades.index', compact(
            'eskuls',
            'academicYears',
            'activeYear',
            'selectedYear',
            'selectedSemester',
            'yearId',
            'eskulProgress',
            'kpi',
            'recentGradingSessions'
        ));
    }

    public function create(Request $request)
    {
        $request->validate([
            'eskul_id' => 'required|exists:eskuls,id',
            'type' => 'required|in:daily,sas1,sas2',
            'date' => 'nullable|date', // Required if type is daily
        ]);

        if (auth()->user()->role == 'teacher') {
            if (!auth()->user()->eskul_id || auth()->user()->eskul_id != $request->eskul_id) {
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
        $semester = '1';

        $contextYear = \App\Models\AcademicYear::find($yearId);
        // Resolve semester dinamis dari tahun ajaran konteks
        $semester = $contextYear ? $contextYear->active_semester : '1';

        $eskul = Eskul::with(['students' => function($q) use ($yearId) {
             $q->wherePivot('academic_year_id', $yearId)
               ->where('status', '!=', 'graduated');
        }])->findOrFail($request->eskul_id);
        
        $type = $request->type;
        $date = $request->date; if ($type == 'daily' && !$date) $date = date('Y-m-d');
        $students = $eskul->students;

        // Bersihkan sisa data nilai ganda jika ada
        self::cleanupDuplicateGrades($request->eskul_id);

        // Fetch existing grades
        $query = Grade::where('eskul_id', $eskul->id)
                      ->where('type', $type)
                      ->where('academic_year_id', $yearId)
                      ->where('semester', $semester);
                      
        if ($type == 'daily') {
            $query->where('date', $date);
        }
        $existingGrades = $query->get()->keyBy('student_id');
        $isAlreadySaved = $existingGrades->isNotEmpty();
        $savedAt = $isAlreadySaved ? ($existingGrades->first()->updated_at ?? $existingGrades->first()->created_at) : null;
        $savedCount = $existingGrades->count();

        $isCalistung = $eskul->is_lockable;

        // Load student attendance for this date (only relevant for daily grades)
        $studentAttendance = collect();
        if ($type === 'daily' && $date) {
            $studentAttendance = Attendance::where('eskul_id', $eskul->id)
                ->where('date', $date)
                ->where('academic_year_id', $yearId)
                ->get()
                ->keyBy('student_id');
        }

        return view('grades.create', compact('eskul', 'type', 'date', 'students', 'existingGrades', 'yearId', 'semester', 'contextYear', 'isCalistung', 'studentAttendance', 'isAlreadySaved', 'savedAt', 'savedCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eskul_id' => 'required|exists:eskuls,id',
            'type' => 'required|in:daily,sas1,sas2',
            'date' => 'nullable|date',
            'grades' => 'required|array',
            'grades' => 'required|array',
            // 'grades.*' => 'in:A,B,C', // Removed to allow arrays/JSON
        ]);

        if (auth()->user()->role == 'teacher') {
            if (!auth()->user()->eskul_id || auth()->user()->eskul_id != $request->eskul_id) {
                abort(403, 'Anda tidak memiliki akses ke eskul ini.');
            }
        }

        $eskulId = $request->eskul_id;
        $type = $request->type;
        $date = $request->date;
        $gradesData = $request->grades;

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

        // 1. CEK PENGISIAN GANDA: Jika nilai pada tanggal/tipe ini sudah ada, kunci pengisian untuk guru
        $checkQuery = Grade::where('eskul_id', $eskulId)
            ->where('type', $type)
            ->where('academic_year_id', $yearId)
            ->where('semester', $semester);
        if ($type === 'daily') {
            $checkQuery->where('date', $date);
        }
        $alreadySaved = $checkQuery->exists();

        if ($alreadySaved && auth()->user()->role === 'teacher') {
            $label = $type === 'daily' ? 'tanggal ' . \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') : strtoupper($type);
            return redirect()->route('grades.create', [
                'eskul_id' => $eskulId,
                'type' => $type,
                'date' => $date,
                'academic_year_id' => $yearId,
                'semester' => $semester
            ])->with('error', "Penilaian eskul untuk {$label} sudah tersimpan. Guru tidak dapat melakukan pengisian 2 kali pada tanggal/periode yang sama untuk mencegah data ganda.");
        }

        // 2. Simpan nilai secara atomik dalam transaksi database & bersihkan data ganda
        \Illuminate\Support\Facades\DB::transaction(function() use ($gradesData, $eskulId, $type, $date, $yearId, $semester) {
            $delQuery = Grade::where('eskul_id', $eskulId)
                ->where('type', $type)
                ->where('academic_year_id', $yearId)
                ->where('semester', $semester)
                ->whereIn('student_id', array_keys($gradesData));
            if ($type === 'daily') {
                $delQuery->where('date', $date);
            }
            $delQuery->delete();

            $now = now();
            $records = [];
            foreach ($gradesData as $studentId => $score) {
                if (is_array($score)) {
                    $score = json_encode($score);
                }
                $records[] = [
                    'student_id'       => $studentId,
                    'eskul_id'         => $eskulId,
                    'type'             => $type,
                    'date'             => $type === 'daily' ? $date : null,
                    'score'            => $score,
                    'academic_year_id' => $yearId,
                    'semester'         => $semester,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
            }
            Grade::insert($records);
        });

        $eskul = Eskul::find($eskulId);
        $eskulName = $eskul ? $eskul->name : 'Unknown';
        $typeLabel = $type === 'daily' ? 'Harian' : ($type === 'sas1' ? 'SAS 1' : 'SAS 2');
        \App\Models\ActivityLog::log('Grades', 'Create', "Mengisi nilai {$typeLabel} eskul {$eskulName} untuk tanggal " . ($date ?? date('Y-m-d')) . ".");

        if (auth()->user()->role == 'teacher') {
            return redirect()->route('grades.create', [
                'eskul_id' => $eskulId,
                'type' => $type,
                'date' => $date,
                'academic_year_id' => $yearId,
                'semester' => $semester
            ])->with('success', 'Nilai siswa berhasil disimpan dan data telah terkunci!');
        }

        return redirect()->route('grades.index')->with('success', 'Nilai berhasil disimpan!');
    }

    public function report(Request $request)
    {
        $user = auth()->user();
        if ($user->role == 'teacher') {
            if (!$user->eskul_id) {
                abort(403, 'Akses ditolak. Anda tidak memiliki penugasan pembina eskul.');
            }
            if ($request->filled('eskul_id') && $request->eskul_id != $user->eskul_id) {
                abort(403, 'Anda tidak memiliki akses ke laporan nilai eskul ini.');
            }
            $eskulId = $user->eskul_id;
        } else {
            $eskulId = $request->eskul_id;
        }

        $selectedEskul = null;
        $students = [];
        $grades = [];
        $recordedDates = collect();

        if ($eskulId) {
            self::cleanupDuplicateGrades((int)$eskulId);
        }

        // Contextual academic year & semester
        if ($request->filled('academic_year_id')) {
            $yearId = $request->academic_year_id;
        } else {
            $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
            $yearId = $activeYear ? $activeYear->id : null;
        }

        if ($request->filled('semester')) {
            $semester = $request->semester;
        } else {
            $contextYear = \App\Models\AcademicYear::find($yearId);
            $semester = $contextYear ? $contextYear->active_semester : '1';
        }

        if ($eskulId) {
            $selectedEskul = Eskul::with(['students' => function($q) use ($yearId, $semester) {
                if ($yearId) {
                    $q->wherePivot('academic_year_id', $yearId);
                }
                if ($semester) {
                    $q->wherePivot('semester', $semester);
                }
                $q->where('status', '!=', 'graduated');
            }])->find($eskulId);

            if ($selectedEskul) {
                $students = $selectedEskul->students;
                $gradesQuery = Grade::where('eskul_id', $eskulId);
                if ($yearId) {
                    $gradesQuery->where('academic_year_id', $yearId);
                }
                if ($semester) {
                    $gradesQuery->where('semester', $semester);
                }
                $grades = $gradesQuery->get()->groupBy('student_id');

                $recordedDates = Grade::where('eskul_id', $eskulId)
                    ->where('type', 'daily')
                    ->whereNotNull('date')
                    ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                    ->when($semester, fn($q) => $q->where('semester', $semester))
                    ->select('date', \Illuminate\Support\Facades\DB::raw('count(distinct student_id) as student_count'))
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->get();
            }
        }

        return view('grades.report', compact('selectedEskul', 'students', 'grades', 'recordedDates', 'yearId', 'semester'));
    }

    public function destroyDate(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Kesiswaan / Administrator yang dapat mereset atau mengosongkan data nilai.');
        }

        $request->validate([
            'eskul_id' => 'required|exists:eskuls,id',
            'type'     => 'required|in:daily,sas1,sas2',
            'date'     => 'nullable|date',
        ]);

        $eskul = Eskul::findOrFail($request->eskul_id);
        $type = $request->type;
        $date = $request->date;

        if ($request->filled('academic_year_id')) {
            $yearId = $request->academic_year_id;
        } else {
            $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
            $yearId = $activeYear ? $activeYear->id : null;
        }

        if ($request->filled('semester')) {
            $semester = $request->semester;
        } else {
            $contextYear = \App\Models\AcademicYear::find($yearId);
            $semester = $contextYear ? $contextYear->active_semester : '1';
        }

        $query = Grade::where('eskul_id', $eskul->id)
            ->where('type', $type);

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }
        if ($semester) {
            $query->where('semester', $semester);
        }
        if ($type === 'daily' && $date) {
            $query->where('date', $date);
        }

        $deletedCount = $query->delete();

        $typeLabel = $type === 'daily' ? 'Harian' : strtoupper($type);
        $dateLabel = ($type === 'daily' && $date)
            ? 'tanggal ' . \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y')
            : "periode {$typeLabel}";

        \App\Models\ActivityLog::log(
            'Grades',
            'Reset',
            "Mereset / mengosongkan {$deletedCount} data nilai eskul {$eskul->name} ({$typeLabel}) pada {$dateLabel} agar dapat diinput ulang."
        );

        if ($request->header('referer') && str_contains($request->header('referer'), 'grades/report')) {
            return redirect()->route('grades.report', [
                'eskul_id'         => $eskul->id,
                'academic_year_id' => $yearId,
                'semester'         => $semester
            ])->with('success', "Data nilai eskul {$eskul->name} ({$typeLabel}) pada {$dateLabel} berhasil dikosongkan ({$deletedCount} siswa di-reset).");
        }

        return redirect()->route('grades.create', [
            'eskul_id'         => $eskul->id,
            'type'             => $type,
            'date'             => $date,
            'academic_year_id' => $yearId,
            'semester'         => $semester
        ])->with('success', "Data nilai eskul {$eskul->name} ({$typeLabel}) pada {$dateLabel} berhasil dikosongkan ({$deletedCount} data nilai di-reset). Guru/tutor kini dapat menginput kembali nilai yang telah direvisi.");
    }
}
