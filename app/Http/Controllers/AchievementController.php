<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Student;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        // Maybe list all achievements or generally accessed via student
        // For now, let's just allow creating from student page.
        // But if we want a global list:
        $achievements = Achievement::with('student')->latest()->paginate(20);
        return view('achievements.index', compact('achievements'));
    }

    public function print()
    {
        // Fetch all achievements (or maybe filtered by year if needed later)
        // For now, print all latest
        $achievements = Achievement::with('student')->orderBy('date', 'desc')->get();
        return view('achievements.print', compact('achievements'));
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

        return redirect()->route('students.show', $request->student_id)
            ->with('success', 'Prestasi berhasil ditambahkan!');
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
