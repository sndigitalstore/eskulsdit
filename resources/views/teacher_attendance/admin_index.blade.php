@extends('layouts.app')

@section('title', 'Data Absensi Guru')
@section('page-title', 'Data Absensi Guru')

@section('content')
<div class="card" style="overflow-x: auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <form action="{{ route('teacher-attendance.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex: 1; flex-wrap: wrap;">
            <input type="month" name="month" value="{{ $month }}" class="form-control" onchange="this.form.submit()" style="max-width: 200px;">
            <button type="submit" class="btn-action-header btn-blue" style="white-space: nowrap;"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('teacher-attendance.export', ['month' => $month]) }}" class="btn-action-header btn-green" style="white-space: nowrap; text-decoration: none;">
                <i class="fas fa-file-excel"></i> Excel Bulanan
            </a>
            <a href="{{ route('teacher-attendance.export') }}" class="btn-action-header btn-dark" style="white-space: nowrap; text-decoration: none;">
                <i class="fas fa-download"></i> Excel Semua
            </a>
        </form>
        
         <div style="font-weight: bold; color: #555; text-align: right; margin-left: auto;">
             {{ \Carbon\Carbon::parse($month . '-01')->isoFormat('MMMM Y') }}
         </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Guru</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
            <tr>
                <td>{{ $attendance->user->name }}</td>
                <td>{{ $attendance->clock_in_time }}</td>
                <td>
                    @if($attendance->status == 'present') <span style="background: #e0fbef; color: #27ae60; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">Hadir</span>
                    @elseif($attendance->status == 'sick') <span style="background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">Sakit</span>
                    @elseif($attendance->status == 'permission') <span style="background: #cce5ff; color: #004085; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">Izin</span>
                    @else <span style="background: #f8d7da; color: #721c24; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">Alpha</span>
                    @endif
                </td>
                <td>
                    {{ $attendance->note }}
                    @if($attendance->substitute_name)
                        <div style="margin-top: 5px; background: #fffbe6; border: 1px dashed #f1c40f; padding: 5px; border-radius: 5px; font-size: 0.8rem; color: #d35400;">
                            <i class="fas fa-exchange-alt"></i> Pengganti: <b>{{ $attendance->substitute_name }}</b>
                        </div>
                    @endif
                </td>
                <td>
                    <form action="{{ route('teacher-attendance.destroy', $attendance->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer;"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #bbb;">Tidak ada data absensi pada tanggal ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
