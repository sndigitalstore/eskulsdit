<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Pengaturan hanya dapat diakses oleh Administrator Utama.');
        }

        $settings = Setting::all()->pluck('value', 'key');
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = $academicYears->where('is_active', true)->first();
        $eskuls = \App\Models\Eskul::activeYear()->get();

        // Load Admin 2 and Headmaster users
        $admin2 = \App\Models\User::where('username', 'admin2')->first();
        $headmaster = \App\Models\User::where('role', 'headmaster')->first();

        return view('settings.index', compact('settings', 'eskuls', 'academicYears', 'activeYear', 'admin2', 'headmaster'));
    }

    public function update(Request $request)
    {
        // 1. Update Administrator Profile
        // We check for 'admin_name' to identify if profile fields are present
        if ($request->has('admin_name')) {
            $request->validate([
                'admin_name' => 'required|string|max:255',
                'change_password' => 'nullable|string|min:6',
            ]);

            $user = \Illuminate\Support\Facades\Auth::user();
            $user->name = $request->admin_name;
            
            if ($request->filled('change_password')) {
                $user->password = \Illuminate\Support\Facades\Hash::make($request->change_password);
            }
            $user->save();
        }

        // 1.5 Update Admin 2 Profile
        if ($request->has('admin2_name')) {
            $request->validate([
                'admin2_name' => 'required|string|max:255',
                'admin2_username' => 'required|string|max:255',
                'admin2_password' => 'nullable|string|min:6',
            ]);

            $admin2 = \App\Models\User::where('username', 'admin2')->first();
            if ($admin2) {
                // Check uniqueness of username if it changed
                if ($request->admin2_username !== $admin2->username) {
                    $request->validate([
                        'admin2_username' => 'unique:users,username,' . $admin2->id,
                    ]);
                }
                $admin2->name = $request->admin2_name;
                $admin2->username = $request->admin2_username;
                if ($request->filled('admin2_password')) {
                    $admin2->password = \Illuminate\Support\Facades\Hash::make($request->admin2_password);
                }
                $admin2->save();
            }
        }

        // 1.6 Update Headmaster Profile
        if ($request->has('headmaster_user_name')) {
            $request->validate([
                'headmaster_user_name' => 'required|string|max:255',
                'headmaster_user_username' => 'required|string|max:255',
                'headmaster_user_password' => 'nullable|string|min:6',
            ]);

            $headmaster = \App\Models\User::where('role', 'headmaster')->first();
            if ($headmaster) {
                // Check uniqueness of username if it changed
                if ($request->headmaster_user_username !== $headmaster->username) {
                    $request->validate([
                        'headmaster_user_username' => 'unique:users,username,' . $headmaster->id,
                    ]);
                }
                $headmaster->name = $request->headmaster_user_name;
                $headmaster->username = $request->headmaster_user_username;
                if ($request->filled('headmaster_user_password')) {
                    $headmaster->password = \Illuminate\Support\Facades\Hash::make($request->headmaster_user_password);
                }
                $headmaster->save();
            }
        }

        // SYNC: Update active academic year if changed
        if ($request->has('active_academic_year_id')) {
            $yearId = $request->active_academic_year_id;
            \App\Models\AcademicYear::query()->update(['is_active' => false]);
            \App\Models\AcademicYear::where('id', $yearId)->update(['is_active' => true]);
        }

        // 2. Update Settings
        // Exclude tokens, methods, and the profile specific fields
        $data = $request->except([
            '_token', '_method', 'admin_name', 'change_password', 
            'admin2_name', 'admin2_username', 'admin2_password',
            'headmaster_user_name', 'headmaster_user_username', 'headmaster_user_password',
            'active_academic_year_id'
        ]);
        
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // SYNC: If active_semester is changed here, we MUST update the Active Academic Year record too
        if ($request->has('active_semester')) {
             $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
             if ($activeYear) {
                 $activeYear->update(['active_semester' => $request->active_semester]);
             }
        }

        if ($request->has('admin_name') || $request->has('admin2_name') || $request->has('headmaster_user_name')) {
            \App\Models\ActivityLog::log('Settings', 'Update', 'Memperbarui profil staf/administrator/kepala sekolah');
        } else {
            \App\Models\ActivityLog::log('Settings', 'Update', 'Memperbarui konfigurasi sistem');
        }

        return redirect()->back()->with('success', 'Semua konfigurasi berhasil disimpan!');
    }

    public function clearLogs()
    {
        if (auth()->user()->username !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Administrator Utama (Admin 1) yang dapat mengosongkan riwayat log.');
        }

        \App\Models\ActivityLog::truncate();
        \App\Models\ActivityLog::log('Settings', 'Delete', 'Membersihkan semua riwayat log aktivitas sistem.');
        return redirect()->back()->with('success', 'Semua riwayat log aktivitas berhasil dibersihkan!');
    }
}
