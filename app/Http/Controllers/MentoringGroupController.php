<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\MentoringGroup;
use App\Models\Student;
use App\Models\TahfidzAttendance;
use App\Models\TahfidzRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Manajemen Kelompok Terpusat untuk Admin
     */
    public function management(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            return redirect()->route('dashboard')->with('error', 'Akses manajemen kelompok hanya untuk Administrator dan Tim Kurikulum.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->input('year_id', $activeYear ? $activeYear->id : null);
        $tab = $request->input('tab', 'all'); // 'all', 'tilawati', 'tahfidz', 'unassigned'
        $search = $request->input('search');
        $mentorFilter = $request->input('mentor_user_id');

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $teachers = User::whereIn('role', ['teacher', 'admin'])->orderBy('name')->get();

        // Query Kelompok
        $groupsQuery = MentoringGroup::withCount('students')
            ->with(['academicYear', 'mentorUser'])
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId));

        if ($tab === 'tilawati') {
            $groupsQuery->where('type', 'tilawati');
        } elseif ($tab === 'tahfidz') {
            $groupsQuery->where('type', 'tahfidz');
        }

        if ($mentorFilter) {
            $groupsQuery->where('mentor_user_id', $mentorFilter);
        }

        if ($search) {
            $groupsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mentor_name', 'like', "%{$search}%")
                  ->orWhere('target_level', 'like', "%{$search}%");
            });
        }

        $groups = $groupsQuery->orderBy('type')->orderBy('name')->get();

        // Summary KPI
        $totalTilawatiGroups = MentoringGroup::where('type', 'tilawati')
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))->count();
        $totalTahfidzGroups = MentoringGroup::where('type', 'tahfidz')
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))->count();

        $totalActiveStudents = Student::forYear($selectedYearId)->where(function($q) {
            $q->where('status', '!=', 'graduated')->orWhereNull('status');
        })->count();

        // Siswa terdaftar di Tilawati
        $assignedTilawatiIds = DB::table('mentoring_group_students')
            ->join('mentoring_groups', 'mentoring_groups.id', '=', 'mentoring_group_students.mentoring_group_id')
            ->where('mentoring_groups.type', 'tilawati')
            ->when($selectedYearId, fn($q) => $q->where('mentoring_groups.academic_year_id', $selectedYearId))
            ->pluck('student_id')->unique();

        // Siswa terdaftar di Tahfidz
        $assignedTahfidzIds = DB::table('mentoring_group_students')
            ->join('mentoring_groups', 'mentoring_groups.id', '=', 'mentoring_group_students.mentoring_group_id')
            ->where('mentoring_groups.type', 'tahfidz')
            ->when($selectedYearId, fn($q) => $q->where('mentoring_groups.academic_year_id', $selectedYearId))
            ->pluck('student_id')->unique();

        $tilawatiAssignedCount = $assignedTilawatiIds->count();
        $tahfidzAssignedCount  = $assignedTahfidzIds->count();

        $tilawatiUnassignedCount = max(0, $totalActiveStudents - $tilawatiAssignedCount);
        $tahfidzUnassignedCount  = max(0, $totalActiveStudents - $tahfidzAssignedCount);

        $totalMentors = MentoringGroup::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->whereNotNull('mentor_name')->distinct('mentor_name')->count('mentor_name');

        // Untuk Tab Distribusi / Siswa Belum Terkelompokkan
        $unassignedType = $request->input('unassigned_type', 'tilawati');
        $classFilter = $request->input('class_filter');

        $assignedIdsForUnassigned = $unassignedType === 'tilawati' ? $assignedTilawatiIds : $assignedTahfidzIds;

        $unassignedStudentsQuery = Student::forYear($selectedYearId)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')->orWhereNull('status');
            })
            ->whereNotIn('id', $assignedIdsForUnassigned);

        if ($classFilter && $classFilter !== 'ALL') {
            $unassignedStudentsQuery->where('class', $classFilter);
        }

        $unassignedStudents = $unassignedStudentsQuery->orderBy('class')->orderBy('name')->get();

        // Ambil kelas unik untuk dropdown filter
        $availableClasses = Student::forYear($selectedYearId)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')->orWhereNull('status');
            })
            ->select('class')->distinct()->orderBy('class')->pluck('class');

        // Kelompok yang tersedia untuk target cepat
        $availableGroups = MentoringGroup::when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->orderBy('type')->orderBy('name')->get();

        return view('mentoring.management', compact(
            'groups',
            'tab',
            'activeYear',
            'academicYears',
            'selectedYearId',
            'search',
            'mentorFilter',
            'teachers',
            'totalTilawatiGroups',
            'totalTahfidzGroups',
            'totalActiveStudents',
            'tilawatiAssignedCount',
            'tahfidzAssignedCount',
            'tilawatiUnassignedCount',
            'tahfidzUnassignedCount',
            'totalMentors',
            'unassignedType',
            'classFilter',
            'unassignedStudents',
            'availableClasses',
            'availableGroups'
        ));
    }

    /**
     * Pembagian Siswa Cepat ke Kelompok (Admin)
     */
    public function quickAssign(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            return redirect()->route('dashboard')->with('error', 'Akses hanya untuk Administrator dan Tim Kurikulum.');
        }

        $request->validate([
            'group_id' => 'required|exists:mentoring_groups,id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
        ]);

        $group = MentoringGroup::findOrFail($request->group_id);
        $studentIds = $request->student_ids;
        $yearId = $group->academic_year_id;
        $count = 0;

        DB::beginTransaction();
        try {
            foreach ($studentIds as $sId) {
                // Hapus dari kelompok lama di tipe & tahun yang sama jika ada
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

                DB::table('mentoring_group_students')->updateOrInsert(
                    [
                        'mentoring_group_id' => $group->id,
                        'student_id' => $sId,
                    ],
                    [
                        'academic_year_id' => $yearId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membagikan siswa: ' . $e->getMessage());
        }

        $typeLabel = $group->type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        ActivityLog::log("Mentoring {$typeLabel}", 'Update', "Distribusi cepat {$count} siswa ke kelompok: {$group->name}");

        return back()->with('success', "Berhasil memasukkan {$count} siswa ke dalam kelompok {$group->name}!");
    }

    public function index(Request $request)
    {
        $type = $this->resolveType($request);
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'headmaster', 'curriculum']);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->input('year_id', $activeYear ? $activeYear->id : null);
        $search = $request->input('search');

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        // KHUSUS GURU TAHFIDZ: Langsung buka Ruang Halaqah Tahfidz (Daftar Siswa & Quick Setoran/Presensi/Share WA)
        if (!$isAdmin && $type === 'tahfidz') {
            $myGroups = MentoringGroup::where('type', 'tahfidz')
                ->where(function ($q) use ($user) {
                    $q->where('mentor_user_id', $user->id)
                      ->orWhere('mentor_name', $user->name);
                })
                ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
                ->withCount('students')
                ->get();

            if ($myGroups->isEmpty()) {
                return view('tahfidz.teacher_workspace_empty', compact('activeYear', 'academicYears', 'selectedYearId'));
            }

            $selectedGroupId = $request->input('group_id') ?? $myGroups->first()->id;
            $activeGroup = $myGroups->firstWhere('id', $selectedGroupId) ?? $myGroups->first();

            $today = date('Y-m-d');
            $activeGroup->load(['students' => function($q) {
                $q->orderBy('class')->orderBy('name');
            }]);

            $studentIds = $activeGroup->students->pluck('id');

            // Riwayat setoran siswa di kelompok ini (terbaru per siswa)
            $latestRecords = TahfidzRecord::whereIn('student_id', $studentIds)
                ->where('mentoring_group_id', $activeGroup->id)
                ->orderBy('session_date', 'desc')
                ->orderBy('id', 'desc')
                ->get()
                ->groupBy('student_id');

            // Absensi hari ini
            $todayAttendances = TahfidzAttendance::whereIn('student_id', $studentIds)
                ->where('mentoring_group_id', $activeGroup->id)
                ->where('session_date', $today)
                ->get()
                ->keyBy('student_id');

            // Setoran hari ini untuk rekap grup WA
            $todayRecords = TahfidzRecord::whereIn('student_id', $studentIds)
                ->where('mentoring_group_id', $activeGroup->id)
                ->where('session_date', $today)
                ->orderBy('id', 'asc')
                ->get()
                ->groupBy('student_id');

            // Daftar Surah Juz 30 dan Juz 29 untuk modal input setoran
            $surahListJuz30 = [
                'An-Naba\'', 'An-Nazi\'at', '\'Abasa', 'At-Takwir', 'Al-Infitar',
                'Al-Mutaffifin', 'Al-Insyiqaq', 'Al-Buruj', 'At-Tariq', 'Al-A\'la',
                'Al-Ghasyiyah', 'Al-Fajr', 'Al-Balad', 'Asy-Syams', 'Al-Lail',
                'Ad-Duha', 'Asy-Syarh', 'At-Tin', 'Al-\'Alaq', 'Al-Qadr',
                'Al-Bayyinah', 'Az-Zalzalah', 'Al-\'Adiyat', 'Al-Qari\'ah', 'At-Takasur',
                'Al-\'Asr', 'Al-Humazah', 'Al-Fil', 'Quraisy', 'Al-Ma\'un',
                'Al-Kausar', 'Al-Kafirun', 'An-Nasr', 'Al-Lahab', 'Al-Ikhlas',
                'Al-Falaq', 'An-Nas'
            ];

            $surahListJuz29 = [
                'Al-Mulk', 'Al-Qalam', 'Al-Haqqah', 'Al-Ma\'arij', 'Nuh',
                'Al-Jinn', 'Al-Muzzammil', 'Al-Muddassir', 'Al-Qiyamah', 'Al-Insan',
                'Al-Mursalat'
            ];

            return view('tahfidz.teacher_workspace', compact(
                'myGroups', 'activeGroup', 'activeYear', 'academicYears', 'selectedYearId',
                'latestRecords', 'todayAttendances', 'todayRecords',
                'surahListJuz30', 'surahListJuz29', 'today'
            ));
        }

        $query = MentoringGroup::where('type', $type)
            ->withCount('students')
            ->with(['academicYear', 'mentorUser']);

        if (!$isAdmin) {
            // Guru hanya melihat kelompok yang dibimbingnya
            $query->where(function ($q) use ($user) {
                $q->where('mentor_user_id', $user->id)
                  ->orWhere('mentor_name', $user->name);
            });
        }

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
        $totalStudentsInYear = 0;
        $totalAssigned = 0;
        $totalUnassigned = 0;

        if ($isAdmin) {
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
        } else {
            // Guru: total siswa dalam kelompok yang dibimbingnya
            $myGroupIds = MentoringGroup::where('type', $type)
                ->where(function ($q) use ($user) {
                    $q->where('mentor_user_id', $user->id)
                      ->orWhere('mentor_name', $user->name);
                })
                ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
                ->pluck('id');

            $totalAssigned = DB::table('mentoring_group_students')
                ->whereIn('mentoring_group_id', $myGroupIds)
                ->distinct('student_id')
                ->count('student_id');
        }

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
            'totalUnassigned',
            'isAdmin'
        ));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            return redirect()->route('dashboard')->with('error', 'Hanya administrator dan kurikulum yang dapat membuat kelompok baru.');
        }

        $type = $this->resolveType($request);
        $typeLabel = $type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        $activeYear = AcademicYear::where('is_active', true)->first();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $teachers = User::whereIn('role', ['teacher', 'admin'])->orderBy('name')->get();

        return view('mentoring.create', compact('type', 'typeLabel', 'activeYear', 'academicYears', 'teachers'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            return redirect()->route('dashboard')->with('error', 'Hanya administrator dan kurikulum yang dapat membuat kelompok baru.');
        }

        $type = $this->resolveType($request);

        $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'target_level' => 'nullable|string|max:255',
            'schedule' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'mentor_user_id' => 'nullable|exists:users,id',
            'mentor_name' => 'nullable|string|max:255',
        ]);

        $mentorUserId = $request->mentor_user_id;
        $mentorName = $request->mentor_name;
        if ($mentorUserId) {
            $userObj = User::find($mentorUserId);
            if ($userObj) {
                $mentorName = $userObj->name;
            }
        }

        if (empty($mentorName)) {
            $mentorName = 'Belum Ditentukan';
        }

        $group = MentoringGroup::create([
            'type' => $type,
            'name' => $request->name,
            'mentor_name' => $mentorName,
            'mentor_user_id' => $mentorUserId,
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
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'headmaster', 'curriculum']);

        $group = MentoringGroup::with(['students' => function($q) {
            $q->orderBy('class')->orderBy('name');
        }, 'academicYear', 'mentorUser'])->findOrFail($id);

        $isMyGroup = ($group->mentor_user_id == $user->id) || (trim(strtolower($group->mentor_name)) == trim(strtolower($user->name)));

        // Jika bukan admin dan bukan kelompok bimbingannya, beri pesan peringatan atau batasi
        if (!$isAdmin && !$isMyGroup) {
            // Guru tetap bisa melihat atau dibatasi
        }

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

        return view('mentoring.show', compact('group', 'type', 'typeLabel', 'classes', 'enrolledStudentIds', 'studentsInOtherGroups', 'isAdmin', 'isMyGroup'));
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'headmaster', 'curriculum']);

        if (!$isAdmin) {
            return back()->with('error', 'Hanya administrator dan kurikulum yang dapat mengubah data kelompok.');
        }

        $group = MentoringGroup::with('academicYear')->findOrFail($id);
        $isMyGroup = false;

        $type = $group->type;
        $typeLabel = $type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $teachers = User::whereIn('role', ['teacher', 'admin', 'curriculum'])->orderBy('name')->get();

        return view('mentoring.edit', compact('group', 'type', 'typeLabel', 'academicYears', 'teachers', 'isAdmin', 'isMyGroup'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'headmaster', 'curriculum']);

        if (!$isAdmin) {
            return back()->with('error', 'Hanya administrator dan kurikulum yang dapat mengubah data kelompok.');
        }

        $group = MentoringGroup::findOrFail($id);

        if ($isAdmin) {
            $request->validate([
                'name' => 'required|string|max:255',
                'academic_year_id' => 'required|exists:academic_years,id',
                'target_level' => 'nullable|string|max:255',
                'schedule' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
                'mentor_user_id' => 'nullable|exists:users,id',
                'mentor_name' => 'nullable|string|max:255',
            ]);

            $mentorUserId = $request->mentor_user_id;
            $mentorName = $request->mentor_name;
            if ($mentorUserId) {
                $userObj = User::find($mentorUserId);
                if ($userObj) {
                    $mentorName = $userObj->name;
                }
            }

            if (empty($mentorName)) {
                $mentorName = $group->mentor_name ?? 'Belum Ditentukan';
            }

            $group->update([
                'name' => $request->name,
                'mentor_name' => $mentorName,
                'mentor_user_id' => $mentorUserId,
                'academic_year_id' => $request->academic_year_id,
                'target_level' => $request->target_level,
                'schedule' => $request->schedule,
                'notes' => $request->notes,
            ]);
        } else {
            // Guru mengupdate rincian jadwal/target kelompok sendiri
            $request->validate([
                'target_level' => 'nullable|string|max:255',
                'schedule' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);

            $group->update($request->only([
                'target_level',
                'schedule',
                'notes',
            ]));
        }

        $typeLabel = $group->type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        ActivityLog::log("Mentoring {$typeLabel}", 'Update', "Memperbarui kelompok {$typeLabel}: {$group->name}");

        $redirectRoute = $group->type === 'tilawati' ? 'tilawati-groups.show' : 'tahfidz-groups.show';
        return redirect()->route($redirectRoute, $group->id)->with('success', "Data kelompok {$typeLabel} berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            return back()->with('error', 'Hanya administrator dan kurikulum yang dapat menghapus kelompok.');
        }

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
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya administrator dan kurikulum yang dapat menambahkan siswa ke kelompok.'
                ], 403);
            }
            return back()->with('error', 'Hanya administrator dan kurikulum yang dapat menambahkan siswa ke kelompok.');
        }

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
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan siswa: ' . $e->getMessage()
                ], 422);
            }
            return back()->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage());
        }

        $typeLabel = $group->type === 'tilawati' ? 'Tilawati' : 'Tahfidz';
        ActivityLog::log("Mentoring {$typeLabel}", 'Update', "Menambahkan {$countAdded} siswa ke kelompok: {$group->name}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Berhasil menambahkan {$countAdded} siswa ke kelompok \"{$group->name}\".",
                'count' => $countAdded,
            ]);
        }

        $redirectRoute = $group->type === 'tilawati' ? 'tilawati-groups.show' : 'tahfidz-groups.show';
        return redirect()->route($redirectRoute, $group->id)->with('success', "Berhasil menambahkan {$countAdded} siswa ke kelompok \"{$group->name}\".");
    }

    /**
     * Hapus/keluarkan siswa dari kelompok
     */
    public function removeStudent($groupId, $studentId)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'headmaster', 'curriculum'])) {
            return back()->with('error', 'Hanya administrator dan kurikulum yang dapat mengeluarkan siswa dari kelompok.');
        }

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
