<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\CharacterAppreciation;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class CharacterAppreciationController extends Controller
{
    /**
     * Auto check and migrate tables if missing
     */
    protected function ensureTableExists()
    {
        if (!Schema::hasTable('character_appreciations')) {
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

        $query = CharacterAppreciation::with(['student', 'academicYear'])
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($selectedClass && $selectedClass !== 'ALL', function($q) use ($selectedClass) {
                $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass));
            })
            ->when($selectedCategory, fn($q) => $q->where('category', $selectedCategory))
            ->when($search, function($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('reward', 'like', "%{$search}%")
                        ->orWhere('given_by', 'like', "%{$search}%")
                        ->orWhereHas('student', fn($sq) => $sq->where('name', 'like', "%{$search}%")->orWhere('nis', 'like', "%{$search}%"));
                });
            });

        // Statistics query scoped to academic year & class
        $statsBase = CharacterAppreciation::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($selectedClass && $selectedClass !== 'ALL', function($q) use ($selectedClass) {
                $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass));
            });

        $stats = [
            'total' => (clone $statsBase)->count(),
            'total_points' => (clone $statsBase)->sum('point'),
            'unique_students' => (clone $statsBase)->distinct('student_id')->count('student_id'),
        ];

        $appreciations = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $categories = [
            'Sholeh (Keshalehan Pribadi)',
            'Muslih (Pembawa Kebaikan)',
            'Intellectual Curiosity (Rasa Ingin Tahu)',
            'Leadership (Kepemimpinan)',
            'Environmentally Friendly (Peduli Lingkungan)',
            'Adab & Kesopanan',
            'Prestasi Karakter',
            'Lainnya',
        ];

        return view('character.appreciations.index', compact(
            'appreciations',
            'stats',
            'academicYears',
            'classes',
            'selectedYearId',
            'selectedClass',
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
            'Sholeh (Keshalehan Pribadi)' => 'S - Sholeh (Taat ibadah, dzikir, adab mulia, berbakti)',
            'Muslih (Pembawa Kebaikan)' => 'M - Muslih (Membawa kebaikan, mengajak kebaikan, mendamaikan)',
            'Intellectual Curiosity (Rasa Ingin Tahu)' => 'I - Intellectual Curiosity (Rasa ingin tahu ilmiah, gemar literasi, tekun, inovatif)',
            'Leadership (Kepemimpinan)' => 'L - Leadership (Kepemimpinan, tanggung jawab, inisiatif, keteladanan)',
            'Environmentally Friendly (Peduli Lingkungan)' => 'E - Environmentally Friendly (Peduli kebersihan, kerapian, kelestarian lingkungan)',
            'Adab & Kesopanan' => 'Adab & Akhlak Mulia Terpuji',
            'Prestasi Karakter' => 'Prestasi & Bintang Karakter Sekolah',
            'Lainnya' => 'Lainnya',
        ];

        return view('character.appreciations.create', compact(
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
            'point' => 'required|integer|min:1|max:100',
            'reward' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'given_by' => 'nullable|string|max:150',
            'notes' => 'nullable|string',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        $appreciation = CharacterAppreciation::create([
            'academic_year_id' => $request->academic_year_id ?? ($activeYear ? $activeYear->id : null),
            'student_id' => $request->student_id,
            'date' => $request->date,
            'category' => $request->category,
            'title' => $request->title,
            'point' => $request->point,
            'reward' => $request->reward,
            'description' => $request->description,
            'given_by' => $request->given_by ?? Auth::user()->name,
            'notes' => $request->notes,
        ]);

        $student = Student::find($request->student_id);
        ActivityLog::log('Karakter', 'Create Apresiasi', "Memberikan apresiasi: {$appreciation->title} (+{$appreciation->point} poin) untuk santri {$student->name}");

        return redirect()->route('character-appreciations.index')
            ->with('success', "Apresiasi karakter untuk santri '{$student->name}' berhasil dicatat.");
    }

    public function edit(CharacterAppreciation $character_appreciation)
    {
        $appreciation = $character_appreciation;
        $activeYear = AcademicYear::where('is_active', true)->first();

        $classes = Student::forYear($appreciation->academic_year_id ?? ($activeYear ? $activeYear->id : null))
            ->select('class')->distinct()->orderBy('class')->pluck('class');

        $students = Student::forYear($appreciation->academic_year_id ?? ($activeYear ? $activeYear->id : null))
            ->where('class', $appreciation->student->class ?? null)
            ->orderBy('name')
            ->get();

        $categories = [
            'Sholeh (Keshalehan Pribadi)' => 'S - Sholeh (Taat ibadah, dzikir, adab mulia, berbakti)',
            'Muslih (Pembawa Kebaikan)' => 'M - Muslih (Membawa kebaikan, mengajak kebaikan, mendamaikan)',
            'Intellectual Curiosity (Rasa Ingin Tahu)' => 'I - Intellectual Curiosity (Rasa ingin tahu ilmiah, gemar literasi, tekun, inovatif)',
            'Leadership (Kepemimpinan)' => 'L - Leadership (Kepemimpinan, tanggung jawab, inisiatif, keteladanan)',
            'Environmentally Friendly (Peduli Lingkungan)' => 'E - Environmentally Friendly (Peduli kebersihan, kerapian, kelestarian lingkungan)',
            'Adab & Kesopanan' => 'Adab & Akhlak Mulia Terpuji',
            'Prestasi Karakter' => 'Prestasi & Bintang Karakter Sekolah',
            'Lainnya' => 'Lainnya',
        ];

        return view('character.appreciations.edit', compact('appreciation', 'students', 'classes', 'categories'));
    }

    public function update(Request $request, CharacterAppreciation $character_appreciation)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'point' => 'required|integer|min:1|max:100',
            'reward' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'given_by' => 'nullable|string|max:150',
            'notes' => 'nullable|string',
        ]);

        $character_appreciation->update([
            'date' => $request->date,
            'category' => $request->category,
            'title' => $request->title,
            'point' => $request->point,
            'reward' => $request->reward,
            'description' => $request->description,
            'given_by' => $request->given_by,
            'notes' => $request->notes,
        ]);

        ActivityLog::log('Karakter', 'Update Apresiasi', "Memperbarui apresiasi ID: {$character_appreciation->id} ({$character_appreciation->title})");

        return redirect()->route('character-appreciations.index')
            ->with('success', 'Data apresiasi karakter santri berhasil diperbarui.');
    }

    public function destroy(CharacterAppreciation $character_appreciation)
    {
        $id = $character_appreciation->id;
        $title = $character_appreciation->title;
        $character_appreciation->delete();

        ActivityLog::log('Karakter', 'Delete Apresiasi', "Menghapus apresiasi ID: {$id} ({$title})");

        return redirect()->route('character-appreciations.index')
            ->with('success', 'Data apresiasi berhasil dihapus.');
    }

    public function print(Request $request)
    {
        $this->ensureTableExists();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $selectedClass = $request->class;
        $selectedCategory = $request->category;

        $year = AcademicYear::find($selectedYearId);

        $appreciations = CharacterAppreciation::with('student')
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->when($selectedClass && $selectedClass !== 'ALL', function($q) use ($selectedClass) {
                $q->whereHas('student', fn($sq) => $sq->where('class', $selectedClass));
            })
            ->when($selectedCategory, fn($q) => $q->where('category', $selectedCategory))
            ->orderBy('date', 'desc')
            ->get();

        return view('character.appreciations.print', compact(
            'appreciations',
            'year',
            'selectedClass',
            'selectedCategory'
        ));
    }
}
