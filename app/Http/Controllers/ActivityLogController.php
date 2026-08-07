<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->username !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Administrator Utama (Admin 1) yang dapat mengakses riwayat log.');
        }

        $query = ActivityLog::with('user')->latest();

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(50);
        $users = \App\Models\User::orderBy('name')->get();
        
        return view('logs.index', compact('logs', 'users'));
    }

    public function clear(Request $request)
    {
        if (auth()->user()->username !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Administrator Utama (Admin 1) yang dapat mengosongkan riwayat log.');
        }

        $days = $request->input('days', 30);
        
        if ($days == 'all') {
            ActivityLog::truncate();
            ActivityLog::log('System', 'Delete', 'Admin membersihkan SELURUH riwayat log.');
            return back()->with('success', 'Seluruh riwayat log telah dihapus.');
        }

        $count = ActivityLog::where('created_at', '<', now()->subDays($days))->count();
        ActivityLog::where('created_at', '<', now()->subDays($days))->delete();
        
        ActivityLog::log('System', 'Delete', "Admin membersihkan log lebih dari {$days} hari ({$count} data).");

        return back()->with('success', "{$count} data log (lebih dari {$days} hari) telah dibersihkan.");
    }
}
