@extends('layouts.app')

@section('title', 'Detail Absensi – ' . \Carbon\Carbon::parse($date)->isoFormat('D MMMM Y'))
@section('page-title', 'Detail Absensi Sesi')

@section('content')

@php
    $statusColor = fn($s) => match($s) {
        'hadir' => ['bg'=>'#dcfce7','text'=>'#16a34a'],
        'sakit' => ['bg'=>'#fef3c7','text'=>'#d97706'],
        'izin'  => ['bg'=>'#e0f2fe','text'=>'#0369a1'],
        'alpha' => ['bg'=>'#fee2e2','text'=>'#dc2626'],
        default => ['bg'=>'#f1f5f9','text'=>'#64748b'],
    };
    $statusLabel = fn($s) => match($s) {
        'hadir' => 'Hadir', 'sakit' => 'Sakit',
        'izin'  => 'Izin',  'alpha' => 'Alpha', default => '-',
    };
    $hadir = $records->where('status','hadir')->count();
    $sakit = $records->where('status','sakit')->count();
    $izin  = $records->where('status','izin')->count();
    $alpha = $records->where('status','alpha')->count();
    $total = $records->count();
    $sessionDate = \Carbon\Carbon::parse($date);
@endphp

<style>
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 16px; }
.back-link:hover { color: #0f766e; }
.summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px; }
.summary-card { border-radius: 12px; padding: 16px; text-align: center; }
.summary-card .num { font-size: 2rem; font-weight: 900; line-height: 1; }
.summary-card .lbl { font-size: 0.75rem; font-weight: 600; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
.record-row { display: flex; align-items: center; gap: 14px; padding: 13px 20px; border-bottom: 1px solid #f1f5f9; }
.record-row:last-child { border-bottom: none; }
.avatar { width: 36px; height: 36px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0; }
.status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; }
</style>

<a href="{{ route('tahfidz.attendance.index', ['group' => $group->id, 'year_id' => $yearId]) }}" class="back-link">
    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Sesi
</a>

{{-- HEADER --}}
<div class="card" style="padding: 0; overflow: hidden; margin-bottom: 20px;">
    <div style="background: linear-gradient(135deg, #1e3a5f, #0f766e); padding: 22px 24px; color: white; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <div style="font-size: 0.8rem; opacity: 0.75; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Detail Absensi</div>
            <h3 style="margin: 0; font-size: 1.3rem; font-weight: 800;">
                <i class="fas fa-calendar-check" style="opacity: 0.8; margin-right: 8px;"></i>
                {{ $sessionDate->isoFormat('dddd, D MMMM Y') }}
            </h3>
            <div style="margin-top: 6px; font-size: 0.85rem; opacity: 0.8;">
                Kelompok: {{ $group->name }} &nbsp;·&nbsp; Pembimbing: {{ $group->mentor_name }}
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('tahfidz.attendance.create', ['group' => $group->id, 'date' => $date, 'year_id' => $yearId]) }}"
               style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; text-decoration: none; padding: 7px 14px; border-radius: 8px; font-size: 0.85rem; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('tahfidz.attendance.print-session', ['group' => $group->id, 'date' => $date]) }}" target="_blank"
               style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; text-decoration: none; padding: 7px 14px; border-radius: 8px; font-size: 0.85rem; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-print"></i> Cetak
            </a>
            <form method="POST" action="{{ route('tahfidz.attendance.destroy-session', ['group' => $group->id]) }}" onsubmit="return confirm('Hapus seluruh absensi sesi ini?')">
                @csrf @method('DELETE')
                <input type="hidden" name="date" value="{{ $date }}">
                <button type="submit" style="background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.4); color: white; padding: 7px 14px; border-radius: 8px; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 600;">
                    <i class="fas fa-trash-alt"></i> Hapus Sesi
                </button>
            </form>
        </div>
    </div>

    {{-- STATS --}}
    <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
        <div class="summary-grid">
            <div class="summary-card" style="background: #dcfce7;">
                <div class="num" style="color: #16a34a;">{{ $hadir }}</div>
                <div class="lbl" style="color: #16a34a;">Hadir</div>
            </div>
            <div class="summary-card" style="background: #fef3c7;">
                <div class="num" style="color: #d97706;">{{ $sakit }}</div>
                <div class="lbl" style="color: #d97706;">Sakit</div>
            </div>
            <div class="summary-card" style="background: #e0f2fe;">
                <div class="num" style="color: #0369a1;">{{ $izin }}</div>
                <div class="lbl" style="color: #0369a1;">Izin</div>
            </div>
            <div class="summary-card" style="background: #fee2e2;">
                <div class="num" style="color: #dc2626;">{{ $alpha }}</div>
                <div class="lbl" style="color: #dc2626;">Alpha</div>
            </div>
        </div>
        @php $pct = $total > 0 ? round(($hadir / $total) * 100) : 0; @endphp
        <div style="background: #e2e8f0; border-radius: 999px; height: 8px; overflow: hidden;">
            <div style="background: #16a34a; height: 100%; width: {{ $pct }}%; border-radius: 999px; transition: width 0.5s;"></div>
        </div>
        <div style="text-align: right; font-size: 0.8rem; color: #64748b; margin-top: 4px;">
            Kehadiran: <strong style="color: #16a34a;">{{ $pct }}%</strong> ({{ $hadir }} dari {{ $total }} siswa)
        </div>
    </div>

    {{-- DAFTAR SISWA --}}
    @forelse($records as $i => $rec)
        @php $sc = $statusColor($rec->status); @endphp
        <div class="record-row">
            <div style="font-size: 0.85rem; color: #94a3b8; width: 24px; text-align: center; font-weight: 600;">{{ $i + 1 }}</div>
            <div class="avatar">{{ substr($rec->student->name, 0, 1) }}</div>
            <div style="flex: 1;">
                <div style="font-weight: 700; color: #0f172a;">{{ $rec->student->name }}</div>
                <div style="font-size: 0.75rem; color: #64748b;">Kelas {{ $rec->student->class }}{{ $rec->student->nis ? ' · NIS: '.$rec->student->nis : '' }}</div>
            </div>
            <div>
                <span class="status-badge" style="background: {{ $sc['bg'] }}; color: {{ $sc['text'] }};">
                    {{ $statusLabel($rec->status) }}
                </span>
            </div>
            <div style="font-size: 0.82rem; color: #64748b; max-width: 200px;">
                {{ $rec->notes ?: '-' }}
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 40px; color: #94a3b8;">Tidak ada data absensi.</div>
    @endforelse
</div>

@endsection
