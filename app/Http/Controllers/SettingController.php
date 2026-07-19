<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $eskuls = \App\Models\Eskul::activeYear()->get();
        return view('settings.index', compact('settings', 'eskuls'));
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

        // 2. Update Settings
        // Exclude tokens, methods, and the profile specific fields
        $data = $request->except(['_token', '_method', 'admin_name', 'change_password']);

        // Handle allowed_eskuls specifically because if unrestricted/unchecked it might be missing
        // But since we use 'except', if it's missing it won't be in $data.
        // We MUST ensure it updates to empty array if missing BUT we are in the context of the full form.
        // To be safe, we always check if the key exists in the "keys we know are checkboxes".
        // Or simpler: always set it.
        $data['allowed_eskuls'] = json_encode($request->input('allowed_eskuls', []));
        
        foreach ($data as $key => $value) {
            // value is already processed for allowed_eskuls above, but for others stick to string
            // actually allowed_eskuls is overwritten in $data so it's fine.
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // SYNC: If active_semester is changed here, we MUST update the Active Academic Year record too
        // because the request logic (GlobalSearch, etc) relies on $activeYear->active_semester
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
}
