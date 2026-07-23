<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'active');
        // Scope to active academic year only
        $query = \App\Models\Student::activeYear()->with('eskuls');

        if ($status !== 'all') {
             $query->where('status', $status);
        }

        if ($request->has('search')) {
            $searchRaw = $request->search;
            $search = str_replace(' ', '%', $searchRaw);

            $query->where(function($q) use ($search, $searchRaw) {
                $q->where('students.name', 'like', "%{$search}%")
                  ->orWhere('students.class', 'like', "%{$search}%")
                  ->orWhere('students.nis', 'like', "%{$searchRaw}%")
                  ->orWhereHas('eskuls', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->paginate(10);
        return view('students.index', compact('students', 'status'));
    }

    public function backup()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif untuk dibackup.');
        }

        $filename = 'Backup_Data_Siswa_' . str_replace(['/', ' '], '_', $activeYear->name) . '_' . date('Ymd_His') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ActiveYearBackup($activeYear->id), $filename);
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        // Get active year first so we can validate NIS uniqueness within active year
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->withErrors(['msg' => 'Tidak ada tahun ajaran aktif. Silakan hubungi admin.'])->withInput();
        }

        $validated = $request->validate([
            'nis' => [
                'required', 'string', 'max:20',
                // NIS must be unique within the active academic year only
                Rule::unique('students', 'nis')->where('academic_year_id', $activeYear->id),
            ],
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:50',
            'eskul_name' => 'required|string|max:255',
            'instructor_name' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Auto-create/find SchoolClass so it stays registered
        $className = trim($validated['class']);
        \App\Models\SchoolClass::firstOrCreate(['name' => $className]);

        // Check if student with same name and class already exists in active year
        $studentObject = \App\Models\Student::activeYear()
                                      ->where('name', $validated['name'])
                                      ->where('class', $className)
                                      ->first();

        // If student exists, check if they are already enrolled in THIS YEAR
        if ($studentObject) {
             // Check pivot for YEAR only
             $isEnrolled = \Illuminate\Support\Facades\DB::table('student_eskul')
                ->where('student_id', $studentObject->id)
                ->where('academic_year_id', $activeYear->id)
                ->exists();

             if ($isEnrolled) {
                  return back()->withErrors(['name' => 'Siswa tersebut sudah terdaftar di tahun ajaran ini!'])->withInput();
             } else {
                  // Not enrolled in this year yet. We will proceed to ENROLL them.
                  $student = $studentObject;
             }
        } else {
             // Create new student (academic_year_id auto-filled by model boot event)
             $photoPath = null;
             if ($request->hasFile('photo')) {
                 $photoPath = $request->file('photo')->store('students', 'public');
             }

             $student = \App\Models\Student::create([
                'academic_year_id' => $activeYear->id,
                'nis' => $validated['nis'],
                'name' => $validated['name'],
                'class' => $className,
                'photo' => $photoPath,
            ]);
        }

        // Find or create Eskul in the active academic year
        $eskul = \App\Models\Eskul::firstOrCreate(
            [
                'name' => $validated['eskul_name'],
                'academic_year_id' => $activeYear->id
            ],
            ['instructor_name' => $validated['instructor_name']]
        );
        
        if ($validated['instructor_name'] && $eskul->wasRecentlyCreated === false) {
             $eskul->update(['instructor_name' => $validated['instructor_name']]);
        }

        // Attach eskul with context (Year only, Active Semester)
        $student->eskuls()->attach($eskul->id, [
            'academic_year_id' => $activeYear->id,
            'semester' => $activeYear->active_semester ?? '1'
        ]);

        \App\Models\ActivityLog::log('Students', 'Create', "Menambahkan siswa: {$student->name} (Kelas {$student->class})");

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function store_bulk(Request $request)
    {
        $data = $request->input('bulk_data');
        if (!$data) {
            return back()->withErrors(['bulk_data' => 'Data kosong!']);
        }

        $rows = explode("\n", $data);
        $count = 0;

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
               return back()->withErrors(['msg' => 'Tidak ada tahun ajaran aktif.']);
        }

        foreach ($rows as $row) {
            $row = trim($row);
            if (empty($row)) continue;

            // Format: Name [TAB] Class [TAB] Eskul [TAB] Instructor
            $cols = explode("\t", $row);
            
            if (count($cols) >= 3) {
                $name = trim($cols[0]);
                $class = trim($cols[1]);
                
                // Validate Class
                if (!\App\Models\SchoolClass::where('name', $class)->exists()) {
                     continue; // Skip invalid classes
                }

                $eskulName = trim($cols[2]);
                $instructorName = isset($cols[3]) ? trim($cols[3]) : null;

                // Check for existing student
                // Match existing student in active year only
                $existingStudent = \App\Models\Student::activeYear()
                    ->where('name', $name)->where('class', $class)->first();
                
                if ($existingStudent) {
                    $student = $existingStudent;
                    
                    // Check if already enrolled in this YEAR
                    $isEnrolled = \Illuminate\Support\Facades\DB::table('student_eskul')
                        ->where('student_id', $student->id)
                        ->where('academic_year_id', $activeYear->id)
                        ->exists();

                    if ($isEnrolled) {
                        // Skip strictly to avoid duplicates in same year
                        continue;
                    }
                } else {
                    $student = \App\Models\Student::create([
                        'academic_year_id' => $activeYear->id,
                        'name' => $name,
                        'class' => $class,
                    ]);
                }

                $eskul = \App\Models\Eskul::firstOrCreate(
                    [
                        'name' => $eskulName,
                        'academic_year_id' => $activeYear->id
                    ],
                    ['instructor_name' => $instructorName]
                );

                if ($instructorName && $eskul->wasRecentlyCreated === false) {
                    $eskul->update(['instructor_name' => $instructorName]);
                }

                $student->eskuls()->attach($eskul->id, [
                    'academic_year_id' => $activeYear->id,
                    'semester' => $activeYear->active_semester ?? '1'
                ]);
                
                $count++;
            }
        }

        \App\Models\ActivityLog::log('Students', 'Import', "Menambahkan {$count} siswa baru melalui Input Masal (Paste Excel).");
        return redirect()->route('students.index')->with('success', "$count data siswa berhasil diimport!");
    }

    public function importExcel(Request $request)
    {
        // Increase time limit and memory limit to handle large backups/imports
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $request->validate([
            'file' => 'required|file',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
            return back()->with('error', 'Format file harus berupa Excel (.xlsx, .xls) atau CSV (.csv).');
        }

        try {
            // Read all sheets as array
            $data = Excel::toArray(new \stdClass, $file);
            
            // Process the data (returns array of counts)
            $counts = \App\Imports\StudentsImport::processArrays($data);
            
            \App\Models\ActivityLog::log('System', 'Import', "Melakukan Mega Import dari Excel. Dipulihkan: {$counts['students']} siswa, {$counts['attendance']} absensi, {$counts['grades']} nilai.");
            
            $msg = "Import Berhasil! Semuanya dipulihkan: <br>";
            $msg .= "- {$counts['students']} Siswa Baru/Update <br>";
            $msg .= "- {$counts['attendance']} Rekap Absensi Siswa <br>";
            $msg .= "- {$counts['grades']} Data Nilai <br>";
            $msg .= "- {$counts['achievements']} Data Prestasi <br>";
            $msg .= "- {$counts['teacher_attendance']} Absensi Guru";

            return redirect()->route('students.index')->with('success', $msg);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function edit(\App\Models\Student $student)
    {
        // Contextualize: Only load eskuls for the active academic year
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        
        if ($activeYear) {
            $student->load(['eskuls' => function($q) use ($activeYear) {
                 $q->wherePivot('academic_year_id', $activeYear->id);
            }]);
        } else {
            $student->setRelation('eskuls', collect());
        }

        // Get the first eskul (assuming 1 main eskul for now to simplify edit form)
        $currentEskul = $student->eskuls->first();
        $allEskuls = \App\Models\Eskul::orderBy('name')->get();
        return view('students.edit', compact('student', 'currentEskul', 'allEskuls'));
    }

    public function update(Request $request, \App\Models\Student $student)
    {
        $validated = $request->validate([
            // NIS unique within same academic year, excluding self
            'nis' => [
                'required', 'string', 'max:20',
                Rule::unique('students', 'nis')
                    ->where('academic_year_id', $student->academic_year_id)
                    ->ignore($student->id),
            ],
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:50',
            'eskul_1_id' => 'nullable|exists:eskuls,id',
            'eskul_2_id' => 'nullable|exists:eskuls,id',
            'last_sync' => 'nullable|string'
        ]);

        // Auto-create/find SchoolClass so it stays registered
        $className = trim($validated['class']);
        \App\Models\SchoolClass::firstOrCreate(['name' => $className]);

        // LOCKING CHECK: If student was updated after form was loaded
        if ($request->filled('last_sync') && $student->updated_at->format('Y-m-d H:i:s') != $request->last_sync) {
             return back()->with('error', 'DATA GAGAL DISIMPAN: Siswa ini baru saja diperbarui oleh Admin lain. Silakan Refresh halaman untuk melihat data terbaru.');
        }

        $updateData = [
            'nis' => $validated['nis'],
            'name' => $validated['name'],
            'class' => $className,
        ];

        // Process photo if present (though removed from form, keep logic for robustness)
        if ($request->hasFile('photo')) {
            if ($student->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($student->photo);
            }
            $updateData['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($updateData);

        // Sync IDs
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
             return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        // Remove existing choices for THIS year to overwrite (Transfer)
        \Illuminate\Support\Facades\DB::table('student_eskul')
            ->where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->delete();

        // Attach Eskul 1 if provided
        if (!empty($validated['eskul_1_id'])) {
            $student->eskuls()->attach($validated['eskul_1_id'], [
                 'academic_year_id' => $activeYear->id,
                 'semester' => $activeYear->active_semester ?? '1'
            ]);
        }

        // Attach Eskul 2 if provided and different
        if (!empty($validated['eskul_2_id']) && $validated['eskul_2_id'] != $validated['eskul_1_id']) {
            $student->eskuls()->attach($validated['eskul_2_id'], [
                 'academic_year_id' => $activeYear->id,
                 'semester' => $activeYear->active_semester ?? '1'
            ]);
        }
        
        \App\Models\ActivityLog::log('Students', 'Update', "Memperbarui data siswa: {$student->name} (Kelas {$student->class})");

        return redirect()->route('students.index')->with('success', 'Data siswa dan pilihan eskul berhasil diperbarui!');
    }

    public function destroy(\App\Models\Student $student)
    {
        \App\Models\ActivityLog::log('Students', 'Delete', "Menghapus data siswa: {$student->name}");
        
        // Manually delete related data to ensure clean slate
        \App\Models\Attendance::where('student_id', $student->id)->delete();
        \App\Models\Grade::where('student_id', $student->id)->delete();
        \App\Models\Achievement::where('student_id', $student->id)->delete();
        $student->eskuls()->detach();
        $student->delete();
        
        return back()->with('success', 'Data siswa berhasil dihapus!');
    }

    public function destroy_bulk(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids) || count($ids) == 0) {
            return redirect()->route('students.index')->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        foreach ($ids as $id) {
            $student = \App\Models\Student::find($id);
            if ($student) {
                // Manually delete related data to ensure clean slate
                \App\Models\Attendance::where('student_id', $student->id)->delete();
                \App\Models\Grade::where('student_id', $student->id)->delete();
                \App\Models\Achievement::where('student_id', $student->id)->delete();
                $student->eskuls()->detach();
                $student->delete();
            }
        }

        \App\Models\ActivityLog::log('Students', 'Delete', "Menghapus " . count($ids) . " data siswa secara masal.");

        return back()->with('success', count($ids) . ' data siswa berhasil dihapus secara masal!');
    }

    public function show(\App\Models\Student $student)
    {
        // Find all student records belonging to this NIS to construct history
        $allRecords = \App\Models\Student::where('nis', $student->nis)
            ->with(['eskuls', 'grades'])
            ->get();

        // Extract all unique academic year IDs across all records
        $yearIds = $allRecords->pluck('academic_year_id')->unique();

        $academicYears = \App\Models\AcademicYear::whereIn('id', $yearIds)
            ->orderBy('name', 'desc')
            ->get();
            
        $history = [];
        
        foreach ($academicYears as $year) {
            // Find the student record for this specific academic year
            $recordForYear = $allRecords->where('academic_year_id', $year->id)->first();
            if (!$recordForYear) continue;

            $eskuls = $recordForYear->eskuls;
                
            $yearData = [];
            foreach ($eskuls as $eskul) {
                // Get grades from the loaded collection
                $sas1 = $recordForYear->grades
                    ->where('eskul_id', $eskul->id)
                    ->where('academic_year_id', $year->id)
                    ->where('type', 'sas1')
                    ->first();
                    
                $sas2 = $recordForYear->grades
                    ->where('eskul_id', $eskul->id)
                    ->where('academic_year_id', $year->id)
                    ->where('type', 'sas2')
                    ->first();
                    
                $yearData[] = [
                    'class' => $recordForYear->class,
                    'eskul' => $eskul,
                    'sas1' => $sas1 ? $sas1->score : '-',
                    'sas2' => $sas2 ? $sas2->score : '-',
                ];
            }
            
            if (!empty($yearData)) {
                $history[$year->name] = $yearData;
            }
        }

        // Fetch all achievements associated with all historical records of this student
        $achievements = \App\Models\Achievement::whereIn('student_id', $allRecords->pluck('id'))
            ->orderBy('date', 'desc')
            ->get();

        return view('students.show', compact('student', 'history', 'achievements'));
    }

    public function card(\App\Models\Student $student)
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if ($activeYear) {
            $student->load(['eskuls' => function($q) use ($activeYear) {
                 $q->wherePivot('academic_year_id', $activeYear->id);
            }]);
        }
        return view('students.card', compact('student', 'activeYear'));
    }
}
