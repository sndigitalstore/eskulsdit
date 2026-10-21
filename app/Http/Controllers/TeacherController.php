<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Eskul;
use App\Models\Subject;
use App\Models\MentoringGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    protected function ensureColumnExists(): void
    {
        if (!Schema::hasColumn('users', 'teaching_subject_ids')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('teaching_subject_ids')->nullable()->after('homeroom_class');
            });
        }
    }

    public function index()
    {
        $this->ensureColumnExists();
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeYearId = $activeYear ? $activeYear->id : null;

        $teachers = User::where('role', 'teacher')
            ->activeYear()
            ->with(['eskul', 'mentoringGroups' => function($q) use ($activeYearId) {
                if ($activeYearId) $q->where('academic_year_id', $activeYearId);
            }])
            ->orderBy('name')
            ->get();

        $allSubjects = Subject::pluck('name', 'id');

        return view('teachers.index', compact('teachers', 'allSubjects', 'activeYear'));
    }

    public function create()
    {
        $this->ensureColumnExists();
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeYearId = $activeYear ? $activeYear->id : null;

        $eskuls = Eskul::activeYear()->orderBy('name')->get();
        $classes = \App\Models\SchoolClass::orderBy('name')->get();
        $subjects = Subject::where('is_active', true)->orderBy('order_num')->get();

        $tilawatiGroups = MentoringGroup::where('type', 'tilawati')
            ->when($activeYearId, fn($q) => $q->where('academic_year_id', $activeYearId))
            ->orderBy('name')
            ->get();

        $tahfidzGroups = MentoringGroup::where('type', 'tahfidz')
            ->when($activeYearId, fn($q) => $q->where('academic_year_id', $activeYearId))
            ->orderBy('name')
            ->get();

        return view('teachers.create', compact('eskuls', 'classes', 'subjects', 'tilawatiGroups', 'tahfidzGroups'));
    }

    public function store(Request $request)
    {
        $this->ensureColumnExists();
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeYearId = $activeYear ? $activeYear->id : null;

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('users')->where('academic_year_id', $activeYearId),
            ],
            'password' => 'required|string|min:6|confirmed',
            'eskul_id' => 'nullable|exists:eskuls,id',
            'phone' => 'nullable|string|max:20',
            'homeroom_class' => 'nullable|string|max:50',
            'teaching_subject_ids' => 'nullable|array',
            'teaching_subject_ids.*' => 'exists:subjects,id',
            'mentoring_group_ids' => 'nullable|array',
            'mentoring_group_ids.*' => 'exists:mentoring_groups,id',
        ]);

        $user = User::create([
            'academic_year_id' => $activeYearId,
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'eskul_id' => $request->eskul_id,
            'phone' => $request->phone,
            'homeroom_class' => $request->homeroom_class,
            'teaching_subject_ids' => $request->teaching_subject_ids ? array_map('intval', $request->teaching_subject_ids) : null,
        ]);

        // Hubungkan Kelompok Mentoring Qur'an jika dipilih
        if ($request->has('mentoring_group_ids') && is_array($request->mentoring_group_ids)) {
            MentoringGroup::whereIn('id', $request->mentoring_group_ids)->update([
                'mentor_user_id' => $user->id,
                'mentor_name' => $user->name,
            ]);
        }

        \App\Models\ActivityLog::log('Teacher', 'Create', "Membuat akun guru baru: {$request->name} (Username: {$request->username}).");

        return redirect()->route('teachers.index')->with('success', 'Akun Guru Pembina berhasil dibuat beserta penugasannya.');
    }
 
    public function edit(User $teacher)
    {
        if ($teacher->role !== 'teacher') return redirect()->route('teachers.index');
        $this->ensureColumnExists();

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeYearId = $activeYear ? $activeYear->id : null;

        $eskuls = Eskul::activeYear()->orderBy('name')->get();
        $classes = \App\Models\SchoolClass::orderBy('name')->get();
        $subjects = Subject::where('is_active', true)->orderBy('order_num')->get();

        $tilawatiGroups = MentoringGroup::where('type', 'tilawati')
            ->when($activeYearId, fn($q) => $q->where('academic_year_id', $activeYearId))
            ->orderBy('name')
            ->get();

        $tahfidzGroups = MentoringGroup::where('type', 'tahfidz')
            ->when($activeYearId, fn($q) => $q->where('academic_year_id', $activeYearId))
            ->orderBy('name')
            ->get();

        $assignedGroupIds = MentoringGroup::where('mentor_user_id', $teacher->id)
            ->when($activeYearId, fn($q) => $q->where('academic_year_id', $activeYearId))
            ->pluck('id')
            ->toArray();

        return view('teachers.edit', compact(
            'teacher', 'eskuls', 'classes', 'subjects', 
            'tilawatiGroups', 'tahfidzGroups', 'assignedGroupIds'
        ));
    }
 
    public function update(Request $request, User $teacher)
    {
        $this->ensureColumnExists();
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeYearId = $activeYear ? $activeYear->id : null;
 
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('users')
                    ->where('academic_year_id', $activeYearId)
                    ->ignore($teacher->id)
            ],
            'eskul_id' => 'nullable|exists:eskuls,id',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'homeroom_class' => 'nullable|string|max:50',
            'teaching_subject_ids' => 'nullable|array',
            'teaching_subject_ids.*' => 'exists:subjects,id',
            'mentoring_group_ids' => 'nullable|array',
            'mentoring_group_ids.*' => 'exists:mentoring_groups,id',
        ]);
 
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'eskul_id' => $request->eskul_id,
            'phone' => $request->phone,
            'homeroom_class' => $request->homeroom_class,
            'teaching_subject_ids' => $request->teaching_subject_ids ? array_map('intval', $request->teaching_subject_ids) : null,
        ];
 
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
 
        $teacher->update($data);

        // Sinkronisasi Kelompok Mentoring Qur'an
        $newGroupIds = $request->mentoring_group_ids ?? [];
        
        // 1. Lepas penugasan kelompok yang sebelumnya dipegang guru ini tapi tidak lagi dicentang
        MentoringGroup::where('mentor_user_id', $teacher->id)
            ->whereNotIn('id', $newGroupIds)
            ->update([
                'mentor_user_id' => null,
            ]);

        // 2. Tautkan kelompok yang dipilih ke guru ini
        if (!empty($newGroupIds)) {
            MentoringGroup::whereIn('id', $newGroupIds)->update([
                'mentor_user_id' => $teacher->id,
                'mentor_name' => $teacher->name,
            ]);
        }
 
        \App\Models\ActivityLog::log('Teacher', 'Update', "Memperbarui data akun & penugasan guru: {$teacher->name}.");
 
        return redirect()->route('teachers.index')->with('success', 'Data akun dan penugasan guru berhasil diperbarui.');
    }
 
    public function destroy(User $teacher)
    {
        if ($teacher->role !== 'teacher') return back()->with('error', 'Tidak bisa menghapus user ini.');
        
        \App\Models\ActivityLog::log('Teacher', 'Delete', "Menghapus akun guru: {$teacher->name}.");
        
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Akun berhasil dihapus.');
    }

    public function print()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $teachers = User::where('role', 'teacher')->activeYear()->with('eskul')->orderBy('name')->get();
        return view('teachers.print', compact('teachers', 'activeYear'));
    }

    public function bulk()
    {
        return view('teachers.bulk');
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            'bulk_data' => 'required|string',
        ]);

        $lines = explode("\n", str_replace("\r", "", $request->bulk_data));
        $count = 0;

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;

            // Format: Nama Guru [TAB] Username [TAB] No WA [TAB] Nama Eskul
            $cols = explode("\t", $line);
            
            if (count($cols) >= 2) {
                $name = trim($cols[0]);
                $username = trim($cols[1]);
                $phone = isset($cols[2]) ? trim($cols[2]) : null;
                $eskulName = isset($cols[3]) ? trim($cols[3]) : null;

                // Build data
                $userData = [
                    'name' => $name,
                    'username' => $username,
                    'phone' => $phone,
                    'role' => 'teacher',
                    'password' => Hash::make('123456'), // Default password
                ];

                // Find Eskul in the active academic year
                $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
                $activeYearId = $activeYear ? $activeYear->id : null;

                if ($eskulName) {
                    $eskul = Eskul::activeYear()->where('name', 'LIKE', "%{$eskulName}%")->first();
                    if ($eskul) {
                        $userData['eskul_id'] = $eskul->id;
                    }
                }

                $userData['academic_year_id'] = $activeYearId;

                User::updateOrCreate(
                    [
                        'username' => $username,
                        'academic_year_id' => $activeYearId
                    ],
                    $userData
                );
                $count++;
            }
        }

        return redirect()->route('teachers.index')->with('success', "$count akun guru pembina berhasil diimport/diperbarui.");
    }

    public function resetAllPasswords(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:6'
        ]);

        $count = \App\Models\User::where('role', 'teacher')->count();
        \App\Models\User::where('role', 'teacher')->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);

        \App\Models\ActivityLog::log('Settings', 'Update', "Admin mereset seluruh password guru menjadi yang baru.");

        return back()->with('success', "Berhasil! Password untuk {$count} akun guru telah diubah.");
    }

    public function resetSinglePassword(Request $request, User $teacher)
    {
        if ($teacher->role !== 'teacher') return back()->with('error', 'Aksi tidak diizinkan.');

        $request->validate([
            'new_password' => 'required|string|min:6'
        ]);

        $teacher->update([
            'password' => Hash::make($request->new_password)
        ]);

        \App\Models\ActivityLog::log('Teacher', 'Update', "Admin mereset password akun guru {$teacher->name}.");

        return back()->with('success', "Berhasil! Password untuk akun {$teacher->name} telah direset.");
    }
}
