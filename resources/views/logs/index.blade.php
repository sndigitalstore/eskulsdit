@extends('layouts.app')

@section('title', 'Riwayat Log Sistem')
@section('page-title', 'Riwayat Aktivitas Log')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Filter Pencarian</h3>
        <div style="display: flex; gap: 10px;">
            <form action="{{ route('logs.clear') }}" method="POST" data-confirm="Hapus log lebih dari 30 hari?">
                @csrf @method('DELETE')
                <input type="hidden" name="days" value="30">
                <button type="submit" class="btn-action-header btn-orange" style="font-size: 0.75rem;">
                    <i class="fas fa-broom"></i> Bersihkan > 30 Hari
                </button>
            </form>
            <form action="{{ route('logs.clear') }}" method="POST" data-confirm="PERINGATAN: Kosongkan SELURUH riwayat log? Tindakan ini tidak dapat dibatalkan!">
                @csrf @method('DELETE')
                <input type="hidden" name="days" value="all">
                <button type="submit" class="btn-action-header btn-red" style="font-size: 0.75rem;">
                    <i class="fas fa-trash-alt"></i> Kosongkan Semua
                </button>
            </form>
        </div>
    </div>
    <div style="margin-bottom: 20px;">
        <form action="{{ route('logs.index') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <select name="module" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Semua Modul --</option>
                    <option value="Students" {{ request('module') == 'Students' ? 'selected' : '' }}>Students</option>
                    <option value="Grades" {{ request('module') == 'Grades' ? 'selected' : '' }}>Grades</option>
                    <option value="Achievements" {{ request('module') == 'Achievements' ? 'selected' : '' }}>Achievements</option>
                    <option value="System" {{ request('module') == 'System' ? 'selected' : '' }}>System</option>
                </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <select name="user_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Semua User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->role }})
                        </option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('logs.index') }}" class="btn-submit" style="background: #94a3b8; width: auto;">Reset</a>
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th width="15%">Waktu</th>
                    <th width="12%">User</th>
                    <th width="10%">Modul</th>
                    <th width="10%">Aksi</th>
                    <th>Keterangan</th>
                    <th width="12%">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="font-size: 0.85rem; color: #666;">
                        {{ $log->created_at->format('d M Y') }}<br>
                        <small>{{ $log->created_at->format('H:i') }} WIB</small>
                    </td>
                    <td>
                        <div style="font-weight: 600; font-size: 0.9rem;">{{ $log->user->name ?? 'System' }}</div>
                        <small style="color: #888;">{{ $log->user->role ?? '-' }}</small>
                    </td>
                    <td>
                        <span class="badge" style="background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem;">
                            {{ $log->module }}
                        </span>
                    </td>
                    <td>
                        @php
                            $color = '#3b82f6';
                            if($log->action == 'Delete') $color = '#ef4444';
                            if($log->action == 'Create') $color = '#10b981';
                            if($log->action == 'Import') $color = '#8b5cf6';
                        @endphp
                        <span style="color: {{ $color }}; font-weight: 700; font-size: 0.85rem;">{{ $log->action }}</span>
                    </td>
                    <td style="font-size: 0.9rem; line-height: 1.4;">{{ $log->description }}</td>
                    <td style="font-size: 0.75rem; color: #94a3b8;">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 30px; color: #94a3b8;">Tidak ada data log ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $logs->appends(request()->query())->links() }}
    </div>
</div>
@endsection
