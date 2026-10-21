<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\CharacterCase;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class CharacterCaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user() && Auth::user()->role === 'curriculum') {
                abort(403, 'Akses data kasus siswa khusus untuk Kesiswaan dan Guru.');
            }
            return $next($request);
        });
    }

    /**
     * Auto check and migrate tables if missing
     */
    protected function ensureTableExists()
    {
        if (!Schema::hasTable('character_cases')) {
            try {
                Artisan::call('migrate', ['--force' => true]);
            } catch (\Throwable $e) {
                // Ignore or log
            }
        }
    }

    public function index(Request $request)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $selectedClass = $request->class;
        $selectedSeverity = $request->severity;
        $selectedStatus = $request->status;
        $selectedCategory = $request->category;
        $search = $request->search;

        $user = Auth::user();
        $isTeacher = $user->role === 'teacher';
        $isHomeroom = $isTeacher && !empty($user->homeroom_class);

        if ($isHomeroom && !$selectedClass) {
            $selectedClass = $user->homeroom_class;
        }

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $classes = Student::forYear($selectedYearId)
            ->select('class')->distinct()->orderBy('class')->pluck('class');

        $query = CharacterCase::with(['student', 'academicYear'])
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($selectedClass && $selectedClass !== 'ALL', function($q) use ($selectedClass) {
                $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass));
            })
            ->when($selectedSeverity, fn($q) => $q->where('severity', $selectedSeverity))
            ->when($selectedStatus, fn($q) => $q->where('status', $selectedStatus))
            ->when($selectedCategory, fn($q) => $q->where('category', $selectedCategory))
            ->when($search, function($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('reported_by', 'like', "%{$search}%")
                        ->orWhereHas('student', fn($sq) => $sq->where('name', 'like', "%{$search}%")->orWhere('nis', 'like', "%{$search}%"));
                });
            });

        // Statistics query scoped to academic year & class
        $statsBase = CharacterCase::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($selectedClass && $selectedClass !== 'ALL', function($q) use ($selectedClass) {
                $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass));
            });

        $stats = [
            'total' => (clone $statsBase)->count(),
            'ringan' => (clone $statsBase)->where('severity', 'ringan')->count(),
            'sedang' => (clone $statsBase)->where('severity', 'sedang')->count(),
            'berat' => (clone $statsBase)->where('severity', 'berat')->count(),
            'dalam_pembinaan' => (clone $statsBase)->where('status', 'dalam_pembinaan')->count(),
            'selesai' => (clone $statsBase)->where('status', 'selesai')->count(),
            'panggilan_ortu' => (clone $statsBase)->where('status', 'panggilan_ortu')->count(),
        ];

        $cases = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $categories = [
            'Kedisiplinan',
            'Adab & Kesopanan',
            'Kehadiran & Kerapian',
            'Ibadah & Sholat',
            'Interaksi Sosial / Perundungan',
            'Kejujuran',
            'Ketertiban Kelas',
            'Lainnya',
        ];

        return view('character.cases.index', compact(
            'cases',
            'stats',
            'academicYears',
            'classes',
            'selectedYearId',
            'selectedClass',
            'selectedSeverity',
            'selectedStatus',
            'selectedCategory',
            'search',
            'categories'
        ));
    }

    public function create(Request $request)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedStudentId = $request->student_id;
        $selectedClass = $request->class;

        $user = Auth::user();
        if ($user->role === 'teacher' && !empty($user->homeroom_class) && !$selectedClass) {
            $selectedClass = $user->homeroom_class;
        }

        $classes = Student::forYear($activeYear ? $activeYear->id : null)
            ->select('class')->distinct()->orderBy('class')->pluck('class');

        $students = Student::forYear($activeYear ? $activeYear->id : null)
            ->when($selectedClass, fn($q) => $q->where('class', $selectedClass))
            ->orderBy('name')
            ->get();

        $selectedStudent = $selectedStudentId ? Student::find($selectedStudentId) : null;

        $categories = [
            'Kedisiplinan' => 'Kedisiplinan (Terlambat, perlengkapan, dll)',
            'Adab & Kesopanan' => 'Adab & Kesopanan (Tutur kata, sikap pada guru/teman)',
            'Kehadiran & Kerapian' => 'Kehadiran & Kerapian (Seragam, kebersihan diri)',
            'Ibadah & Sholat' => 'Ibadah & Sholat (Bercanda saat sholat, wudhu, dll)',
            'Interaksi Sosial / Perundungan' => 'Interaksi Sosial (Pertikaian, mengejek, dll)',
            'Kejujuran' => 'Kejujuran (Menyontek, tidak berkata benar)',
            'Ketertiban Kelas' => 'Ketertiban Kelas (Mengganggu pembelajaran)',
            'Lainnya' => 'Lainnya',
        ];

        return view('character.cases.create', compact(
            'students',
            'selectedStudent',
            'selectedClass',
            'classes',
            'activeYear',
            'categories'
        ));
    }

    public function store(Request $request)
    {
        $this->ensureTableExists();

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'severity' => 'required|in:ringan,sedang,berat',
            'point' => 'required|integer|min:0|max:100',
            'location' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'action_taken' => 'nullable|string',
            'follow_up' => 'nullable|string',
            'status' => 'required|in:dalam_pembinaan,selesai,panggilan_ortu',
            'reported_by' => 'nullable|string|max:150',
            'notes' => 'nullable|string',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        $case = CharacterCase::create([
            'academic_year_id' => $request->academic_year_id ?? ($activeYear ? $activeYear->id : null),
            'student_id' => $request->student_id,
            'date' => $request->date,
            'category' => $request->category,
            'title' => $request->title,
            'severity' => $request->severity,
            'point' => $request->point,
            'location' => $request->location,
            'description' => $request->description,
            'action_taken' => $request->action_taken,
            'follow_up' => $request->follow_up,
            'status' => $request->status,
            'reported_by' => $request->reported_by ?? Auth::user()->name,
            'parent_notified' => $request->has('parent_notified'),
            'notes' => $request->notes,
        ]);

        $student = Student::find($request->student_id);
        ActivityLog::log('Karakter', 'Create Kasus', "Mencatat kasus: {$case->title} ({$case->severity}) untuk siswa {$student->name}");

        return redirect()->route('character-cases.index')
            ->with('success', "Data kasus pembinaan siswa '{$student->name}' berhasil dicatat.");
    }

    public function edit(CharacterCase $character_case)
    {
        $case = $character_case;
        $activeYear = AcademicYear::where('is_active', true)->first();

        $classes = Student::forYear($case->academic_year_id ?? ($activeYear ? $activeYear->id : null))
            ->select('class')->distinct()->orderBy('class')->pluck('class');

        $students = Student::forYear($case->academic_year_id ?? ($activeYear ? $activeYear->id : null))
            ->where('class', $case->student->class ?? null)
            ->orderBy('name')
            ->get();

        $categories = [
            'Kedisiplinan' => 'Kedisiplinan (Terlambat, perlengkapan, dll)',
            'Adab & Kesopanan' => 'Adab & Kesopanan (Tutur kata, sikap pada guru/teman)',
            'Kehadiran & Kerapian' => 'Kehadiran & Kerapian (Seragam, kebersihan diri)',
            'Ibadah & Sholat' => 'Ibadah & Sholat (Bercanda saat sholat, wudhu, dll)',
            'Interaksi Sosial / Perundungan' => 'Interaksi Sosial (Pertikaian, mengejek, dll)',
            'Kejujuran' => 'Kejujuran (Menyontek, tidak berkata benar)',
            'Ketertiban Kelas' => 'Ketertiban Kelas (Mengganggu pembelajaran)',
            'Lainnya' => 'Lainnya',
        ];

        return view('character.cases.edit', compact('case', 'students', 'classes', 'categories'));
    }

    public function update(Request $request, CharacterCase $character_case)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'severity' => 'required|in:ringan,sedang,berat',
            'point' => 'required|integer|min:0|max:100',
            'location' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'action_taken' => 'nullable|string',
            'follow_up' => 'nullable|string',
            'status' => 'required|in:dalam_pembinaan,selesai,panggilan_ortu',
            'reported_by' => 'nullable|string|max:150',
            'notes' => 'nullable|string',
        ]);

        $character_case->update([
            'date' => $request->date,
            'category' => $request->category,
            'title' => $request->title,
            'severity' => $request->severity,
            'point' => $request->point,
            'location' => $request->location,
            'description' => $request->description,
            'action_taken' => $request->action_taken,
            'follow_up' => $request->follow_up,
            'status' => $request->status,
            'reported_by' => $request->reported_by,
            'parent_notified' => $request->has('parent_notified'),
            'notes' => $request->notes,
        ]);

        ActivityLog::log('Karakter', 'Update Kasus', "Memperbarui kasus ID: {$character_case->id} ({$character_case->title})");

        return redirect()->route('character-cases.index')
            ->with('success', 'Data catatan kasus pembinaan berhasil diperbarui.');
    }

    public function destroy(CharacterCase $character_case)
    {
        $id = $character_case->id;
        $title = $character_case->title;
        $character_case->delete();

        ActivityLog::log('Karakter', 'Delete Kasus', "Menghapus kasus ID: {$id} ({$title})");

        return redirect()->route('character-cases.index')
            ->with('success', 'Catatan kasus berhasil dihapus.');
    }

    public function print(Request $request)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $selectedClass = $request->class;
        $selectedSeverity = $request->severity;
        $selectedStatus = $request->status;

        $year = AcademicYear::find($selectedYearId);

        $cases = CharacterCase::with('student')
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($selectedClass && $selectedClass !== 'ALL', function($q) use ($selectedClass) {
                $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass));
            })
            ->when($selectedSeverity, fn($q) => $q->where('severity', $selectedSeverity))
            ->when($selectedStatus, fn($q) => $q->where('status', $selectedStatus))
            ->orderBy('date', 'desc')
            ->get();

        return view('character.cases.print', compact(
            'cases',
            'year',
            'selectedClass',
            'selectedSeverity',
            'selectedStatus'
        ));
    }
}
