<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Student;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
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
        $query = Achievement::with(['student', 'academicYear']);

        // 1. Filter Tanggal Spesifik
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            // 2. Filter Bulan & Tahun
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

        // 3. Filter Tingkat Prestasi
        if ($request->filled('level') && $request->level !== 'all') {
            $query->where('level', $request->level);
        }

        // 4. Filter Pencarian Keyword (Nama Siswa, Nama Prestasi, Penyelenggara)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
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
        $isFiltered = $request->filled('date') || $request->filled('month') || $request->filled('year') || ($request->filled('level') && $request->level !== 'all') || $request->filled('search');

        return view('achievements.index', compact('achievements', 'availableYears', 'totalAll', 'isFiltered'));
    }

    public function print(Request $request)
    {
        $query = Achievement::with(['student', 'academicYear']);

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

        return view('achievements.print', compact('achievements', 'filterDescription'));
    }
    public function create(Request $request)
    {
        $student_id = $request->query('student_id');
        $student = null;
        $students = [];

        if ($student_id) {
            $student = Student::find($student_id);
        } else {
            // If no student selected, we need list for dropdown
            // Optimization: Maybe limit or use ajax search if too many. 
            // For now, let's just get active students.
            $students = Student::activeYear()->active()->orderBy('name')->get();
        }

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();

        return view('achievements.create', compact('student', 'students', 'activeYear', 'academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|in:1,2',
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'date' => 'required|date',
            'organizer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $achievement = Achievement::create($request->all());

        \App\Models\ActivityLog::log('Achievements', 'Create', "Menambahkan prestasi: {$achievement->name} untuk siswa ID: {$achievement->student_id}");

        if ($request->input('action') === 'finish') {
            if ($request->has('from_student') && $request->from_student) {
                return redirect()->route('students.show', $request->student_id)
                    ->with('success', 'Prestasi berhasil ditambahkan!');
            }
            return redirect()->route('achievements.index')
                ->with('success', 'Prestasi berhasil ditambahkan!');
        }

        // Tetap di halaman input data prestasi untuk memudahkan penginputan berlanjut
        if ($request->has('from_student') && $request->from_student) {
            return redirect()->route('achievements.create', ['student_id' => $request->student_id, 'from_student' => 1])
                ->with('success', 'Prestasi berhasil ditambahkan! Silakan input data prestasi berikutnya.');
        }

        return redirect()->route('achievements.create')
            ->with('success', 'Prestasi berhasil ditambahkan! Silakan input data prestasi berikutnya.');
    }

    public function edit(Achievement $achievement)
    {
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();
        return view('achievements.edit', compact('achievement', 'academicYears'));
    }

    public function update(Request $request, Achievement $achievement)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|in:1,2',
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'date' => 'required|date',
            'organizer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $achievement->update($request->all());

        \App\Models\ActivityLog::log('Achievements', 'Update', "Memperbarui prestasi: {$achievement->name}");

        return redirect()->route('students.show', $achievement->student_id)
            ->with('success', 'Prestasi berhasil diperbarui!');
    }

    public function bulk()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();
        return view('achievements.bulk', compact('activeYear', 'academicYears'));
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|in:1,2',
            'bulk_data' => 'required|string',
        ]);

        $lines = explode("\n", str_replace("\r", "", $request->bulk_data));
        $count = 0;
        $activeYearId = $request->academic_year_id;
        $semester = $request->semester;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($lines as $line) {
                if (empty(trim($line))) continue;

                // Format sesuai Request: Nama Siswa [0] | Kelas [1] | Nama Prestasi [2] | Tingkat [3] | Tanggal [4] | Penyelenggara [5] | Keterangan [6]
                $cols = explode("\t", $line);
                
                if (count($cols) >= 3) {
                    $studentName = trim($cols[0]);
                    $className = trim($cols[1]); // New field in index 1
                    $achievementName = trim($cols[2]);
                    $level = trim($cols[3] ?? 'Sekolah');
                    $date = isset($cols[4]) && !empty(trim($cols[4])) ? trim($cols[4]) : date('Y-m-d');
                    $organizer = isset($cols[5]) ? trim($cols[5]) : null;
                    $description = isset($cols[6]) ? trim($cols[6]) : null;

                    // Mencocokkan Siswa dengan Nama DAN Kelas agar lebih akurat
                    $query = Student::activeYear()->where('name', 'like', "%{$studentName}%");
                    
                    if (!empty($className)) {
                        $query->where('class', 'like', "%{$className}%");
                    }
                    
                    $student = $query->first();
                    
                    if ($student) {
                        Achievement::create([
                            'student_id' => $student->id,
                            'academic_year_id' => $activeYearId,
                            'semester' => $semester,
                            'name' => $achievementName,
                            'level' => $level,
                            'date' => \Carbon\Carbon::parse($date)->format('Y-m-d'),
                            'organizer' => $organizer,
                            'description' => $description,
                        ]);
                        $count++;
                    }
                }
            }
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memproses data: ' . $e->getMessage())->withInput();
        }

        \App\Models\ActivityLog::log('Achievements', 'Import', "Menambahkan {$count} data prestasi secara masal.");

        return redirect()->route('achievements.index')->with('success', "$count data prestasi berhasil ditambahkan secara masal!");
    }

    public function destroy(Achievement $achievement)
    {
        \App\Models\ActivityLog::log('Achievements', 'Delete', "Menghapus data prestasi: {$achievement->name}");
        $student_id = $achievement->student_id;
        $achievement->delete();
        return back()->with('success', 'Prestasi berhasil dihapus!');
    }
}
