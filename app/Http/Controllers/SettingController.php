<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = $academicYears->where('is_active', true)->first();
        $eskuls = \App\Models\Eskul::activeYear()->get();
        return view('settings.index', compact('settings', 'eskuls', 'academicYears', 'activeYear'));
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

        // SYNC: Update active academic year if changed
        if ($request->has('active_academic_year_id')) {
            $yearId = $request->active_academic_year_id;
            \App\Models\AcademicYear::query()->update(['is_active' => false]);
            \App\Models\AcademicYear::where('id', $yearId)->update(['is_active' => true]);
        }

        // 2. Update Settings
        // Exclude tokens, methods, and the profile specific fields
        $data = $request->except(['_token', '_method', 'admin_name', 'change_password', 'active_academic_year_id']);

        // Handle allowed_eskuls specifically because if unrestricted/unchecked it might be missing
        $data['allowed_eskuls'] = json_encode($request->input('allowed_eskuls', []));
        
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

        if ($request->has('admin_name')) {
            \App\Models\ActivityLog::log('Settings', 'Update', 'Memperbarui profil administrator');
        } else {
            \App\Models\ActivityLog::log('Settings', 'Update', 'Memperbarui konfigurasi sistem');
        }

        return redirect()->back()->with('success', 'Semua konfigurasi berhasil disimpan!');
    }

    public function clearLogs()
    {
        \App\Models\ActivityLog::truncate();
        \App\Models\ActivityLog::log('Settings', 'Delete', 'Membersihkan semua riwayat log aktivitas sistem.');
        return redirect()->back()->with('success', 'Semua riwayat log aktivitas berhasil dibersihkan!');
    }
}
