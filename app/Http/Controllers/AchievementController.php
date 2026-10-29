<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AchievementController extends Controller
{
    protected static bool $schemaChecked = false;

    /**
     * Self-healing: Memastikan kolom category dan school_name tersedia serta student_id nullable
     */
    public static function ensureTableSchema(): void
    {
        if (self::$schemaChecked) {
            return;
        }
        self::$schemaChecked = true;

        try {
            if (!Schema::hasColumn('achievements', 'category')) {
                Schema::table('achievements', function ($table) {
                    $table->string('category', 20)->default('student')->after('id');
                });
            }
            if (!Schema::hasColumn('achievements', 'school_name')) {
                Schema::table('achievements', function ($table) {
                    $table->string('school_name')->nullable()->after('student_id');
                });
            }

            // Memastikan student_id nullable agar prestasi sekolah (Juara Umum, dll) bisa diinput tanpa siswa
            DB::statement("ALTER TABLE achievements MODIFY student_id BIGINT UNSIGNED NULL");
        } catch (\Exception $e) {
            // Silently handled if already modified
        }
    }

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->role === 'tu') {
                abort(403, 'Akses dibatasi. Kelola Prestasi hanya dapat diakses oleh Administrator, Kepala Sekolah, dan Guru.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        self::ensureTableSchema();

        $query = Achievement::with(['student', 'academicYear']);

        // 1. Filter Kategori (Semua, Prestasi Siswa, Prestasi Sekolah)
        if ($request->filled('category') && $request->category !== 'all') {
            if ($request->category === 'school') {
                $query->where(function($q) {
                    $q->where('category', 'school')->orWhereNull('student_id');
                });
            } elseif ($request->category === 'student') {
                $query->where(function($q) {
                    $q->where('category', 'student')->whereNotNull('student_id');
                });
            }
        }

        // 2. Filter Tanggal Spesifik
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            // 3. Filter Bulan & Tahun
            if ($request->filled('month')) {
                if (str_contains($request->month, '-')) {
                    [$mYear, $mMonth] = explode('-', $request->month);
                    $query->whereYear('date', $mYear)->whereMonth('date', $mMonth);
                } else {
                    $query->whereMonth('date', $request->month);
                }
            }

            if ($request->filled('year')) {
                $query->whereYear('date', $request->year);
            }
        }

        // 4. Filter Tingkat Prestasi
        if ($request->filled('level') && $request->level !== 'all') {
            $query->where('level', $request->level);
        }

        // 5. Filter Pencarian Keyword (Nama Siswa, Nama Prestasi, Penyelenggara, Nama Sekolah)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('school_name', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('class', 'like', "%{$search}%")
                         ->orWhere('nisn', 'like', "%{$search}%")
                         ->orWhere('nis', 'like', "%{$search}%");
                  });
            });
        }

        $achievements = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(20)->withQueryString();

        // Data list tahun untuk dropdown
        $availableYears = Achievement::selectRaw('YEAR(date) as yr')
            ->whereNotNull('date')
            ->distinct()
            ->orderBy('yr', 'desc')
            ->pluck('yr')
            ->filter()
            ->values()
            ->all();

        $currentYear = (int) date('Y');
        if (!in_array($currentYear, $availableYears)) {
            array_unshift($availableYears, $currentYear);
            rsort($availableYears);
        }

        $totalAll = Achievement::count();
        $totalSchool = Achievement::where('category', 'school')->orWhereNull('student_id')->count();
        $totalStudent = Achievement::where('category', 'student')->whereNotNull('student_id')->count();

        $isFiltered = $request->filled('date') 
            || $request->filled('month') 
            || $request->filled('year') 
            || ($request->filled('level') && $request->level !== 'all') 
            || ($request->filled('category') && $request->category !== 'all')
            || $request->filled('search');

        return view('achievements.index', compact('achievements', 'availableYears', 'totalAll', 'totalSchool', 'totalStudent', 'isFiltered'));
    }

    public function print(Request $request)
    {
        self::ensureTableSchema();

        $query = Achievement::with(['student', 'academicYear']);

        if ($request->filled('category') && $request->category !== 'all') {
            if ($request->category === 'school') {
                $query->where(function($q) {
                    $q->where('category', 'school')->orWhereNull('student_id');
                });
            } elseif ($request->category === 'student') {
                $query->where(function($q) {
                    $q->where('category', 'student')->whereNotNull('student_id');
                });
            }
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            if ($request->filled('month')) {
                if (str_contains($request->month, '-')) {
                    [$mYear, $mMonth] = explode('-', $request->month);
                    $query->whereYear('date', $mYear)->whereMonth('date', $mMonth);
                } else {
                    $query->whereMonth('date', $request->month);
                }
            }

            if ($request->filled('year')) {
                $query->whereYear('date', $request->year);
            }
        }

        if ($request->filled('level') && $request->level !== 'all') {
            $query->where('level', $request->level);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('school_name', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('class', 'like', "%{$search}%");
                  });
            });
        }

        $achievements = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        $filterDescription = null;
        if ($request->filled('date')) {
            $filterDescription = 'Tanggal: ' . \Carbon\Carbon::parse($request->date)->translatedFormat('d F Y');
        } elseif ($request->filled('month')) {
            $monthNum = (int) (str_contains($request->month, '-') ? explode('-', $request->month)[1] : $request->month);
            $monthName = \Carbon\Carbon::create(null, $monthNum, 1)->translatedFormat('F');
            $yearStr = $request->filled('year') ? $request->year : (str_contains($request->month, '-') ? explode('-', $request->month)[0] : date('Y'));
            $filterDescription = "Bulan {$monthName} {$yearStr}";
        } elseif ($request->filled('year')) {
            $filterDescription = "Tahun {$request->year}";
        }

        if ($request->category === 'school') {
            $filterDescription = ($filterDescription ? $filterDescription . ' &bull; ' : '') . 'Kategori: Prestasi Sekolah/Lembaga';
        } elseif ($request->category === 'student') {
            $filterDescription = ($filterDescription ? $filterDescription . ' &bull; ' : '') . 'Kategori: Prestasi Siswa';
        }

        return view('achievements.print', compact('achievements', 'filterDescription'));
    }

    public function create(Request $request)
    {
        self::ensureTableSchema();

        $student_id = $request->query('student_id');
        $category = $request->query('category', 'student');
        $student = null;
        $students = [];

        if ($student_id) {
            $student = Student::find($student_id);
        } else {
            $students = Student::activeYear()->active()->orderBy('name')->get();
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        return view('achievements.create', compact('student', 'students', 'activeYear', 'academicYears', 'category'));
    }

    public function store(Request $request)
    {
        self::ensureTableSchema();

        $category = $request->input('category', 'student');

        if ($category === 'school') {
            $request->validate([
                'academic_year_id' => 'required|exists:academic_years,id',
                'semester'         => 'required|in:1,2',
                'name'             => 'required|string|max:255',
                'level'            => 'required|string|max:255',
                'date'             => 'required|date',
                'school_name'      => 'nullable|string|max:255',
                'organizer'        => 'nullable|string|max:255',
                'description'      => 'nullable|string',
            ]);

            $data = $request->all();
            $data['student_id'] = null;
            $data['category'] = 'school';
            $data['school_name'] = trim($request->input('school_name')) ?: 'SDIT An Nadzir';

            $achievement = Achievement::create($data);

            \App\Models\ActivityLog::log('Achievements', 'Create', "Menambahkan prestasi sekolah: {$achievement->name}");

            return redirect()->route('achievements.index')
                ->with('success', "Alhamdulillah, Prestasi Sekolah/Lembaga '{$achievement->name}' berhasil disimpan!");
        }

        // Kategori Prestasi Siswa
        $request->validate([
            'student_id'       => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester'         => 'required|in:1,2',
            'name'             => 'required|string|max:255',
            'level'            => 'required|string|max:255',
            'date'             => 'required|date',
            'organizer'        => 'nullable|string|max:255',
            'description'      => 'nullable|string',
        ]);

        $data = $request->all();
        $data['category'] = 'student';

        $achievement = Achievement::create($data);

        \App\Models\ActivityLog::log('Achievements', 'Create', "Menambahkan prestasi siswa: {$achievement->name} untuk siswa ID: {$achievement->student_id}");

        if ($request->input('action') === 'finish') {
            if ($request->has('from_student') && $request->from_student) {
                return redirect()->route('students.show', $request->student_id)
                    ->with('success', 'Prestasi siswa berhasil ditambahkan!');
            }
            return redirect()->route('achievements.index')
                ->with('success', 'Prestasi siswa berhasil ditambahkan!');
        }

        if ($request->has('from_student') && $request->from_student) {
            return redirect()->route('achievements.create', ['student_id' => $request->student_id, 'from_student' => 1])
                ->with('success', 'Prestasi berhasil ditambahkan! Silakan input data prestasi berikutnya.');
        }

        return redirect()->route('achievements.create')
            ->with('success', 'Prestasi berhasil ditambahkan! Silakan input data prestasi berikutnya.');
    }

    public function edit(Achievement $achievement)
    {
        self::ensureTableSchema();

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        return view('achievements.edit', compact('achievement', 'academicYears'));
    }

    public function update(Request $request, Achievement $achievement)
    {
        self::ensureTableSchema();

        $category = $request->input('category', $achievement->category ?: 'student');

        if ($category === 'school') {
            $request->validate([
                'academic_year_id' => 'required|exists:academic_years,id',
                'semester'         => 'required|in:1,2',
                'name'             => 'required|string|max:255',
                'level'            => 'required|string|max:255',
                'date'             => 'required|date',
                'school_name'      => 'nullable|string|max:255',
                'organizer'        => 'nullable|string|max:255',
                'description'      => 'nullable|string',
            ]);

            $data = $request->all();
            $data['student_id'] = null;
            $data['category'] = 'school';
            $data['school_name'] = trim($request->input('school_name')) ?: 'SDIT An Nadzir';

            $achievement->update($data);

            \App\Models\ActivityLog::log('Achievements', 'Update', "Memperbarui prestasi sekolah: {$achievement->name}");

            return redirect()->route('achievements.index')
                ->with('success', 'Prestasi Sekolah berhasil diperbarui!');
        }

        $request->validate([
            'student_id'       => 'nullable|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester'         => 'required|in:1,2',
            'name'             => 'required|string|max:255',
            'level'            => 'required|string|max:255',
            'date'             => 'required|date',
            'organizer'        => 'nullable|string|max:255',
            'description'      => 'nullable|string',
        ]);

        $data = $request->all();
        $data['category'] = 'student';

        $achievement->update($data);

        \App\Models\ActivityLog::log('Achievements', 'Update', "Memperbarui prestasi: {$achievement->name}");

        if ($achievement->student_id) {
            return redirect()->route('students.show', $achievement->student_id)
                ->with('success', 'Prestasi berhasil diperbarui!');
        }
        return redirect()->route('achievements.index')
            ->with('success', 'Prestasi berhasil diperbarui!');
    }

    public function bulk()
    {
        self::ensureTableSchema();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        return view('achievements.bulk', compact('activeYear', 'academicYears'));
    }

    public function storeBulk(Request $request)
    {
        self::ensureTableSchema();

        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester'         => 'required|in:1,2',
            'bulk_data'        => 'required|string',
        ]);

        $lines = explode("\n", str_replace("\r", "", $request->bulk_data));
        $count = 0;
        $activeYearId = $request->academic_year_id;
        $semester = $request->semester;

        DB::beginTransaction();
        try {
            foreach ($lines as $line) {
                if (empty(trim($line))) continue;

                // Format: Nama Siswa/Sekolah [0] | Kelas [1] | Nama Prestasi [2] | Tingkat [3] | Tanggal [4] | Penyelenggara [5] | Keterangan [6]
                $cols = explode("\t", $line);
                
                if (count($cols) >= 3) {
                    $studentName = trim($cols[0]);
                    $className = trim($cols[1] ?? '');
                    $achievementName = trim($cols[2]);
                    $level = trim($cols[3] ?? 'Sekolah');
                    $date = isset($cols[4]) && !empty(trim($cols[4])) ? trim($cols[4]) : date('Y-m-d');
                    $organizer = isset($cols[5]) ? trim($cols[5]) : null;
                    $description = isset($cols[6]) ? trim($cols[6]) : null;

                    $lowerName = strtolower($studentName);

                    // Deteksi jika ini adalah prestasi sekolah / juara umum lembaga
                    if (in_array($lowerName, ['sekolah', 'sdit', 'sdit an nadzir', 'sdit annadzir', 'lembaga', 'kontingen', 'kontingen sdit an nadzir']) || str_contains($lowerName, 'juara umum sekolah')) {
                        Achievement::create([
                            'category'         => 'school',
                            'school_name'      => 'SDIT An Nadzir',
                            'student_id'       => null,
                            'academic_year_id' => $activeYearId,
                            'semester'         => $semester,
                            'name'             => $achievementName,
                            'level'            => $level,
                            'date'             => \Carbon\Carbon::parse($date)->format('Y-m-d'),
                            'organizer'        => $organizer,
                            'description'      => $description,
                        ]);
                        $count++;
                        continue;
                    }

                    // Mencocokkan Siswa dengan Nama DAN Kelas
                    $query = Student::activeYear()->where('name', 'like', "%{$studentName}%");
                    
                    if (!empty($className)) {
                        $query->where('class', 'like', "%{$className}%");
                    }
                    
                    $student = $query->first();
                    
                    if ($student) {
                        Achievement::create([
                            'category'         => 'student',
                            'student_id'       => $student->id,
                            'academic_year_id' => $activeYearId,
                            'semester'         => $semester,
                            'name'             => $achievementName,
                            'level'            => $level,
                            'date'             => \Carbon\Carbon::parse($date)->format('Y-m-d'),
                            'organizer'        => $organizer,
                            'description'      => $description,
                        ]);
                        $count++;
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses data: ' . $e->getMessage())->withInput();
        }

        \App\Models\ActivityLog::log('Achievements', 'Import', "Menambahkan {$count} data prestasi secara masal.");

        return redirect()->route('achievements.index')->with('success', "$count data prestasi berhasil ditambahkan secara masal!");
    }

    public function destroy(Achievement $achievement)
    {
        \App\Models\ActivityLog::log('Achievements', 'Delete', "Menghapus data prestasi: {$achievement->name}");
        $achievement->delete();
        return back()->with('success', 'Prestasi berhasil dihapus!');
    }
}
