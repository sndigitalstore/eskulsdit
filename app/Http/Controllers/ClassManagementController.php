<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClassManagementController extends Controller
{
    /**
     * Pastikan hanya TU, Admin, dan Kepala Sekolah yang dapat mengakses.
     */
    private function checkAccess()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['tu', 'admin', 'headmaster'])) {
            abort(403, 'Akses dibatasi. Manajemen & Ploting Kelas hanya dapat dikelola oleh Tata Usaha (TU), Administrator, dan Kepala Sekolah.');
        }
    }

    /**
     * Halaman Utama: Daftar Kelas, Distribusi Rombel, dan Workspace Ploting Siswa.
     */
    public function index(Request $request)
    {
        $this->checkAccess();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        // 1. Data Seluruh Master Kelas terdaftar beserta jumlah siswa di tahun ajaran aktif
        $classes = SchoolClass::orderBy('name')->get()->map(function ($cls) use ($activeYear) {
            $studentQuery = Student::where('class', $cls->name);
            if ($activeYear) {
                $studentQuery->where('academic_year_id', $activeYear->id)->where('status', 'active');
            }
            $cls->student_count = $studentQuery->count();
            $cls->male_count = (clone $studentQuery)->where('gender', 'L')->count();
            $cls->female_count = (clone $studentQuery)->where('gender', 'P')->count();
            return $cls;
        });

        // 2. Filter Workspace Ploting
        // Sumber Tahun Ajaran (default: tahun aktif, atau tahun sebelumnya jika dipilih)
        $sourceYearId = $request->input('source_academic_year_id', $activeYear?->id);
        $sourceClass = $request->input('source_class'); // misal: '1A', '1 Abu Bakar', 'grade_1', 'no_class'
        $search = $request->input('search');
        $gender = $request->input('gender');
        $program = $request->input('program');

        $students = collect();
        if ($sourceYearId) {
            $query = Student::where('academic_year_id', $sourceYearId)
                ->where('status', 'active');

            if ($sourceClass) {
                if ($sourceClass === 'no_class') {
                    $query->where(function ($q) {
                        $q->whereNull('class')->orWhere('class', '');
                    });
                } elseif (str_starts_with($sourceClass, 'grade_')) {
                    $gradeNum = str_replace('grade_', '', $sourceClass);
                    $query->where('class', 'like', "{$gradeNum}%");
                } else {
                    $query->where('class', $sourceClass);
                }
            }

            if ($search) {
                $term = str_replace(' ', '%', $search);
                $query->where(function ($q) use ($term, $search) {
                    $q->where('name', 'like', "%{$term}%")
                      ->orWhere('nis', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%");
                });
            }

            if ($gender) {
                $query->where('gender', $gender);
            }

            if ($program) {
                $query->where('school_program', $program);
            }

            $students = $query->orderBy('name')->get();
        }

        // 3. Rekap Siswa per Tingkat di Tahun Aktif untuk Statistik Ringkas
        $totalActiveStudents = $activeYear ? Student::where('academic_year_id', $activeYear->id)->where('status', 'active')->count() : 0;

        return view('class_management.index', compact(
            'activeYear',
            'academicYears',
            'classes',
            'students',
            'sourceYearId',
            'sourceClass',
            'search',
            'gender',
            'program',
            'totalActiveStudents'
        ));
    }

    /**
     * Tambah Master Nama Kelas Baru (misal: "1 Abu Bakar", "2 Umar", "3A", dll.)
     */
    public function storeClass(Request $request)
    {
        $this->checkAccess();

        $request->validate([
            'name' => 'required|string|max:50|unique:school_classes,name',
        ], [
            'name.required' => 'Nama kelas wajib diisi.',
            'name.unique' => 'Nama kelas ini sudah ada di daftar master kelas.',
        ]);

        $className = trim($request->name);
        SchoolClass::create(['name' => $className]);

        ActivityLog::log('ClassManagement', 'Create', "Menambahkan master kelas baru: {$className}");

        return redirect()->route('class-management.index')->with('success', "Kelas \"{$className}\" berhasil ditambahkan!");
    }

    /**
     * Update / Rename Nama Kelas
     * Opsi: Sekaligus perbarui nama kelas pada seluruh siswa aktif yang ada di kelas tersebut.
     */
    public function updateClass(Request $request, SchoolClass $schoolClass)
    {
        $this->checkAccess();

        $request->validate([
            'name' => 'required|string|max:50|unique:school_classes,name,' . $schoolClass->id,
        ], [
            'name.required' => 'Nama kelas wajib diisi.',
            'name.unique' => 'Nama kelas tersebut sudah digunakan.',
        ]);

        $oldName = $schoolClass->name;
        $newName = trim($request->name);

        $schoolClass->update(['name' => $newName]);

        $affectedStudents = 0;
        if ($request->boolean('update_existing_students')) {
            $activeYear = AcademicYear::where('is_active', true)->first();
            $query = Student::where('class', $oldName);
            if ($activeYear) {
                $query->where('academic_year_id', $activeYear->id);
            }
            $affectedStudents = $query->update(['class' => $newName]);

            // Perbarui juga data wali kelas guru jika ada
            User::where('homeroom_class', $oldName)->update(['homeroom_class' => $newName]);
        }

        ActivityLog::log('ClassManagement', 'Update', "Mengubah nama kelas dari \"{$oldName}\" menjadi \"{$newName}\". Siswa terupdate: {$affectedStudents}");

        $msg = "Nama kelas berhasil diubah menjadi \"{$newName}\"!";
        if ($affectedStudents > 0) {
            $msg .= " Sebanyak {$affectedStudents} data siswa di tahun aktif otomatis disesuaikan ke kelas baru.";
        }

        return redirect()->route('class-management.index')->with('success', $msg);
    }

    /**
     * Hapus Master Kelas
     */
    public function destroyClass(SchoolClass $schoolClass)
    {
        $this->checkAccess();

        $className = $schoolClass->name;
        $activeYear = AcademicYear::where('is_active', true)->first();

        $studentCount = 0;
        if ($activeYear) {
            $studentCount = Student::where('academic_year_id', $activeYear->id)
                ->where('class', $className)
                ->where('status', 'active')
                ->count();
        }

        if ($studentCount > 0) {
            return back()->with('error', "Kelas \"{$className}\" tidak dapat dihapus karena masih memiliki {$studentCount} siswa aktif. Silakan ploting / pindahkan siswa terlebih dahulu.");
        }

        $schoolClass->delete();

        ActivityLog::log('ClassManagement', 'Delete', "Menghapus master kelas: {$className}");

        return redirect()->route('class-management.index')->with('success', "Master kelas \"{$className}\" berhasil dihapus!");
    }

    /**
     * Eksekusi Ploting & Pengelompokan Siswa secara Masal (Batch Plotting).
     * Mendukung:
     * 1. Pengelompokan ulang antar-kelas pada tahun ajaran aktif.
     * 2. Ploting dari tahun ajaran sebelumnya ke tahun ajaran baru (dengan kelas dan nama baru).
     */
    public function plotStudents(Request $request)
    {
        $this->checkAccess();

        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'target_class' => 'required|string|max:50',
            'source_academic_year_id' => 'required|exists:academic_years,id',
        ], [
            'student_ids.required' => 'Pilih minimal satu siswa untuk di-ploting.',
            'target_class.required' => 'Pilih atau tentukan kelas tujuan.',
            'source_academic_year_id.required' => 'Tahun ajaran sumber tidak valid.',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada Tahun Ajaran aktif sebagai target ploting.');
        }

        $targetClass = trim($request->target_class);
        // Pastikan nama kelas tersimpan di master SchoolClass
        SchoolClass::firstOrCreate(['name' => $targetClass]);

        $sourceYearId = (int)$request->source_academic_year_id;
        $studentIds = $request->student_ids;

        $students = Student::whereIn('id', $studentIds)->get();

        $successCount = 0;
        $replicatedCount = 0;

        foreach ($students as $student) {
            if ($student->academic_year_id == $activeYear->id) {
                // KASUS A: Ploting Ulang di Tahun Ajaran Aktif (Regrouping)
                $student->update([
                    'class' => $targetClass,
                    'status' => 'active'
                ]);
                $successCount++;
            } else {
                // KASUS B: Ploting dari Tahun Ajaran Sebelumnya ke Tahun Ajaran Baru
                // Cek apakah siswa sudah terdaftar di tahun ajaran aktif
                $existingActiveStudent = Student::where('nis', $student->nis)
                    ->where('academic_year_id', $activeYear->id)
                    ->first();

                if ($existingActiveStudent) {
                    $existingActiveStudent->update([
                        'class' => $targetClass,
                        'status' => 'active'
                    ]);
                    $successCount++;
                } else {
                    // Replikasi data siswa ke tahun ajaran aktif dengan kelas baru
                    $newStudent = $student->replicate();
                    $newStudent->academic_year_id = $activeYear->id;
                    $newStudent->class = $targetClass;
                    $newStudent->status = 'active';
                    $newStudent->save();

                    $replicatedCount++;
                    $successCount++;
                }
            }
        }

        ActivityLog::log('ClassManagement', 'Plotting', "Melakukan ploting {$successCount} siswa ke kelas \"{$targetClass}\" (Tahun Ajaran {$activeYear->name}).");

        $msg = "Berhasil memploting {$successCount} siswa ke kelas \"{$targetClass}\"!";
        if ($replicatedCount > 0) {
            $msg .= " ({$replicatedCount} siswa baru berhasil didaftarkan ke Tahun Ajaran {$activeYear->name}).";
        }

        return redirect()->route('class-management.index', [
            'source_academic_year_id' => $activeYear->id,
            'source_class' => $targetClass
        ])->with('success', $msg);
    }

    /**
     * Quick Move: Pindahkan 1 siswa langsung ke kelas lain.
     */
    public function quickMove(Request $request, Student $student)
    {
        $this->checkAccess();

        $request->validate([
            'target_class' => 'required|string|max:50',
        ]);

        $targetClass = trim($request->target_class);
        SchoolClass::firstOrCreate(['name' => $targetClass]);

        $oldClass = $student->class;
        $student->update(['class' => $targetClass]);

        ActivityLog::log('ClassManagement', 'Move', "Memindahkan siswa {$student->name} dari kelas {$oldClass} ke {$targetClass}.");

        return back()->with('success', "Siswa {$student->name} berhasil dipindahkan ke kelas {$targetClass}.");
    }
}
