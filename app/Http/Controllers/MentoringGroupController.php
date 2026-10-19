<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\MentoringGroup;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class MentoringGroupController extends Controller
{
    public function __construct()
    {
        $this->ensureTablesExist();
    }

    /**
     * Memastikan tabel mentoring_groups dan pivot sudah ada secara otomatis (self-healing)
     */
    protected function ensureTablesExist(): void
    {
        if (!Schema::hasTable('mentoring_groups')) {
            Schema::create('mentoring_groups', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['tilawati', 'tahfidz'])->index();
                $table->string('name');
                $table->string('mentor_name')->nullable();
                $table->foreignId('mentor_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->string('target_level')->nullable();
                $table->string('schedule')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('mentoring_group_students')) {
            Schema::create('mentoring_group_students', function (Blueprint $table) {
                $table->id();
                $table->foreignId('mentoring_group_id')->constrained('mentoring_groups')->cascadeOnDelete();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->string('notes')->nullable();
                $table->timestamps();

                $table->unique(['mentoring_group_id', 'student_id'], 'group_student_unique');
            });
        }
    }

    /**
     * Deteksi tipe mentoring (tilawati atau tahfidz) dari route
     */
    protected function resolveType(Request $request): string
    {
        if ($request->routeIs('tilawati*') || $request->is('*tilawati*') || $request->input('type') === 'tilawati') {
            return 'tilawati';
        }
        return 'tahfidz';
    }

    public function index(Request $request)
    {
        $type = $this->resolveType($request);
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->input('year_id', $activeYear ? $activeYear->id : null);
        $search = $request->input('search');

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        $query = MentoringGroup::where('type', $type)
            ->withCount('students')
            ->with(['academicYear', 'mentorUser']);

        if ($selectedYearId) {
            $query->where('academic_year_id', $selectedYearId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mentor_name', 'like', "%{$search}%")
                  ->orWhere('target_level', 'like', "%{$search}%");
            });
        }

        $groups = $query->orderBy('name')->paginate(15)->appends($request->query());

        // Statistik
        $totalStudentsInYear = Student::forYear($selectedYearId)->where(function($q) {
            $q->where('status', '!=', 'graduated')->orWhereNull('status');
        })->count();

        $assignedStudentIds = DB::table('mentoring_group_students')
            ->join('mentoring_groups', 'mentoring_groups.id', '=', 'mentoring_group_students.mentoring_group_id')
            ->where('mentoring_groups.type', $type)
            ->when($selectedYearId, function ($q) use ($selectedYearId) {
                $q->where('mentoring_group_students.academic_year_id', $selectedYearId);
            })
            ->pluck('student_id')
            ->unique();

        $totalAssigned = $assignedStudentIds->count();
        $totalUnassigned = max(0, $totalStudentsInYear - $totalAssigned);

        $typeLabel = $type === 'tilawati' ? 'Tilawati' : 'Tahfidz';

        return view('mentoring.index', compact(
            'groups',
            'type',
            'typeLabel',
            'activeYear',
            'academicYears',
            'selectedYearId',
            'search',
            'totalStudentsInYear',
            'totalAssigned',
            'totalUnassigned'
        ));
    }

    public function create(Request $request)
    {
        $type = $this->resolveType($request);
        $typeLabel = $type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        $activeYear = AcademicYear::where('is_active', true)->first();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $teachers = User::whereIn('role', ['teacher', 'admin'])->orderBy('name')->get();

        return view('mentoring.create', compact('type', 'typeLabel', 'activeYear', 'academicYears', 'teachers'));
    }

    public function store(Request $request)
    {
        $type = $this->resolveType($request);

        $request->validate([
            'name' => 'required|string|max:255',
            'mentor_name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'target_level' => 'nullable|string|max:255',
            'schedule' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'mentor_user_id' => 'nullable|exists:users,id',
        ]);

        $group = MentoringGroup::create([
            'type' => $type,
            'name' => $request->name,
            'mentor_name' => $request->mentor_name,
            'mentor_user_id' => $request->mentor_user_id,
            'academic_year_id' => $request->academic_year_id,
            'target_level' => $request->target_level,
            'schedule' => $request->schedule,
            'notes' => $request->notes,
        ]);

        $typeLabel = $type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        ActivityLog::log("Mentoring {$typeLabel}", 'Create', "Membuat kelompok {$typeLabel}: {$group->name} (Pembimbing: {$group->mentor_name})");

        $redirectRoute = $type === 'tilawati' ? 'tilawati-groups.show' : 'tahfidz-groups.show';
        return redirect()->route($redirectRoute, $group->id)->with('success', "Kelompok {$typeLabel} \"{$group->name}\" berhasil dibuat! Silakan tambahkan anggota siswa ke kelompok ini.");
    }

    public function show(Request $request, $id)
    {
        $group = MentoringGroup::with(['students' => function($q) {
            $q->orderBy('class')->orderBy('name');
        }, 'academicYear', 'mentorUser'])->findOrFail($id);

        $type = $group->type;
        $typeLabel = $type === 'tilawati' ? 'Tilawati' : 'Tahfidz';

        // Kelas unik untuk filter penambahan siswa
        $classes = Student::forYear($group->academic_year_id)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')->orWhereNull('status');
            })
            ->select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        // Mendapatkan ID siswa yang sudah ada di kelompok ini
        $enrolledStudentIds = $group->students->pluck('id')->toArray();

        // Mendapatkan ID siswa yang sudah ada di kelompok LAIN dengan tipe & tahun yang sama
        $studentsInOtherGroups = DB::table('mentoring_group_students')
            ->join('mentoring_groups', 'mentoring_groups.id', '=', 'mentoring_group_students.mentoring_group_id')
            ->where('mentoring_groups.type', $type)
            ->where('mentoring_groups.id', '!=', $group->id)
            ->where('mentoring_groups.academic_year_id', $group->academic_year_id)
            ->select('mentoring_group_students.student_id', 'mentoring_groups.name as group_name')
            ->get()
            ->keyBy('student_id');

        return view('mentoring.show', compact('group', 'type', 'typeLabel', 'classes', 'enrolledStudentIds', 'studentsInOtherGroups'));
    }

    public function edit(Request $request, $id)
    {
        $group = MentoringGroup::with('academicYear')->findOrFail($id);
        $type = $group->type;
        $typeLabel = $type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $teachers = User::whereIn('role', ['teacher', 'admin'])->orderBy('name')->get();

        return view('mentoring.edit', compact('group', 'type', 'typeLabel', 'academicYears', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $group = MentoringGroup::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'mentor_name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'target_level' => 'nullable|string|max:255',
            'schedule' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'mentor_user_id' => 'nullable|exists:users,id',
        ]);

        $group->update($request->only([
            'name',
            'mentor_name',
            'mentor_user_id',
            'academic_year_id',
            'target_level',
            'schedule',
            'notes',
        ]));

        $typeLabel = $group->type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        ActivityLog::log("Mentoring {$typeLabel}", 'Update', "Memperbarui kelompok {$typeLabel}: {$group->name}");

        $redirectRoute = $group->type === 'tilawati' ? 'tilawati-groups.show' : 'tahfidz-groups.show';
        return redirect()->route($redirectRoute, $group->id)->with('success', "Data kelompok {$typeLabel} berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $group = MentoringGroup::findOrFail($id);
        $type = $group->type;
        $name = $group->name;
        $typeLabel = $type === 'tilawati' ? 'Tilawati' : 'Tahfidz';

        $group->delete();

        ActivityLog::log("Mentoring {$typeLabel}", 'Delete', "Menghapus kelompok {$typeLabel}: {$name}");

        $redirectRoute = $type === 'tilawati' ? 'tilawati-groups.index' : 'tahfidz-groups.index';
        return redirect()->route($redirectRoute)->with('success', "Kelompok {$typeLabel} \"{$name}\" berhasil dihapus.");
    }

    /**
     * Tambah siswa secara masal ke dalam kelompok
     */
    public function addStudents(Request $request, $id)
    {
        $group = MentoringGroup::findOrFail($id);

        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'notes' => 'nullable|string',
        ]);

        $studentIds = $request->student_ids;
        $notes = $request->notes;
        $yearId = $group->academic_year_id;
        $countAdded = 0;

        DB::beginTransaction();
        try {
            foreach ($studentIds as $sId) {
                // Jika siswa sudah ada di kelompok LAIN pada tahun & tipe yang sama, pindahkan (lepaskan dari kelompok lama)
                $oldGroupIds = DB::table('mentoring_group_students')
                    ->join('mentoring_groups', 'mentoring_groups.id', '=', 'mentoring_group_students.mentoring_group_id')
                    ->where('mentoring_groups.type', $group->type)
                    ->where('mentoring_groups.academic_year_id', $yearId)
                    ->where('mentoring_group_students.student_id', $sId)
                    ->pluck('mentoring_groups.id');

                if ($oldGroupIds->isNotEmpty()) {
                    DB::table('mentoring_group_students')
                        ->whereIn('mentoring_group_id', $oldGroupIds)
                        ->where('student_id', $sId)
                        ->delete();
                }

                // Masukkan ke kelompok ini
                DB::table('mentoring_group_students')->updateOrInsert(
                    [
                        'mentoring_group_id' => $group->id,
                        'student_id' => $sId,
                    ],
                    [
                        'academic_year_id' => $yearId,
                        'notes' => $notes,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                $countAdded++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage());
        }

        $typeLabel = $group->type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        ActivityLog::log("Mentoring {$typeLabel}", 'Update', "Menambahkan {$countAdded} siswa ke kelompok: {$group->name}");

        return back()->with('success', "Berhasil menambahkan {$countAdded} siswa ke kelompok \"{$group->name}\".");
    }

    /**
     * Hapus/keluarkan siswa dari kelompok
     */
    public function removeStudent($groupId, $studentId)
    {
        $group = MentoringGroup::findOrFail($groupId);
        $student = Student::findOrFail($studentId);

        $group->students()->detach($studentId);

        $typeLabel = $group->type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        ActivityLog::log("Mentoring {$typeLabel}", 'Update', "Mengeluarkan siswa {$student->name} dari kelompok: {$group->name}");

        return back()->with('success', "Siswa \"{$student->name}\" berhasil dikeluarkan dari kelompok.");
    }

    /**
     * Cetak dokumen satu kelompok
     */
    public function printGroup($id)
    {
        $group = MentoringGroup::with(['students' => function($q) {
            $q->orderBy('class')->orderBy('name');
        }, 'academicYear'])->findOrFail($id);

        $type = $group->type;
        $typeLabel = $type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        $yearName = $group->academicYear ? $group->academicYear->name : '-';

        return view('mentoring.print_group', compact('group', 'type', 'typeLabel', 'yearName'));
    }

    /**
     * Cetak dokumen seluruh kelompok (berpaginasi rapi per kelompok)
     */
    public function printAll(Request $request)
    {
        $type = $this->resolveType($request);
        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $request->input('year_id', $activeYear ? $activeYear->id : null);

        $academicYear = AcademicYear::find($yearId);
        $yearName = $academicYear ? $academicYear->name : '-';

        $groups = MentoringGroup::where('type', $type)
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->with(['students' => function($q) {
                $q->orderBy('class')->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $typeLabel = $type === 'tilawati' ? 'Tilawati' : 'Tahfidz';

        return view('mentoring.print_all', compact('groups', 'type', 'typeLabel', 'yearName', 'yearId'));
    }

    /**
     * API AJAX untuk mencari siswa berdasarkan kelas untuk modal penambahan siswa
     */
    public function getStudentsByClass(Request $request)
    {
        $class = $request->query('class');
        $yearId = $request->query('year_id');
        $groupId = $request->query('group_id');
        $type = $request->query('type', 'tilawati');

        $query = Student::where(function($q) {
            $q->where('status', '!=', 'graduated')->orWhereNull('status');
        });

        if ($yearId) {
            $query->forYear($yearId);
        }

        if ($class && $class !== 'ALL') {
            $query->where('class', $class);
        }

        $students = $query->orderBy('class')->orderBy('name')->get();

        // Cari grup saat ini jika siswa sudah masuk kelompok lain
        $enrolledOther = DB::table('mentoring_group_students')
            ->join('mentoring_groups', 'mentoring_groups.id', '=', 'mentoring_group_students.mentoring_group_id')
            ->where('mentoring_groups.type', $type)
            ->where('mentoring_groups.academic_year_id', $yearId)
            ->select('mentoring_group_students.student_id', 'mentoring_groups.id as group_id', 'mentoring_groups.name as group_name')
            ->get()
            ->keyBy('student_id');

        $enrolledInThisGroup = [];
        if ($groupId) {
            $enrolledInThisGroup = DB::table('mentoring_group_students')
                ->where('mentoring_group_id', $groupId)
                ->pluck('student_id')
                ->toArray();
        }

        $result = $students->map(function ($s) use ($enrolledOther, $enrolledInThisGroup, $groupId) {
            $inThis = in_array($s->id, $enrolledInThisGroup);
            $other = $enrolledOther->get($s->id);
            $otherGroupName = ($other && $other->group_id != $groupId) ? $other->group_name : null;

            return [
                'id' => $s->id,
                'name' => $s->name,
                'nis' => $s->nis ?? '-',
                'class' => $s->class,
                'is_in_this_group' => $inThis,
                'other_group_name' => $otherGroupName,
            ];
        });

        return response()->json($result);
    }
}
