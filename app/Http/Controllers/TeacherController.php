<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Eskul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->with('eskul')->get();
        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        $eskuls = Eskul::orderBy('name')->get();
        return view('teachers.create', compact('eskuls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'eskul_id' => 'required|exists:eskuls,id',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'eskul_id' => $request->eskul_id,
            'phone' => $request->phone,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Akun Guru Pembina berhasil dibuat.');
    }

    public function edit(User $teacher)
    {
        if ($teacher->role !== 'teacher') return redirect()->route('teachers.index');
        $eskuls = Eskul::orderBy('name')->get();
        return view('teachers.edit', compact('teacher', 'eskuls'));
    }

    public function update(Request $request, User $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($teacher->id)],
            'eskul_id' => 'required|exists:eskuls,id',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'eskul_id' => $request->eskul_id,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $teacher->update($data);

        return redirect()->route('teachers.index')->with('success', 'Data akun berhasil diperbarui.');
    }

    public function destroy(User $teacher)
    {
        if ($teacher->role !== 'teacher') return back()->with('error', 'Tidak bisa menghapus user ini.');
        
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Akun berhasil dihapus.');
    }

    public function print()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $teachers = User::where('role', 'teacher')->with('eskul')->orderBy('name')->get();
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

                // Find Eskul
                if ($eskulName) {
                    $eskul = Eskul::where('name', 'LIKE', "%{$eskulName}%")->first();
                    if ($eskul) {
                        $userData['eskul_id'] = $eskul->id;
                    }
                }

                User::updateOrCreate(['username' => $username], $userData);
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
}
