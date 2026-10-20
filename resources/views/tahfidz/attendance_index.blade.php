@extends('layouts.app')

@section('title', 'Absensi – ' . $group->name)
@section('page-title', 'Absensi Mentoring Tahfidz')

@section('content')

<style>
.session-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: box-shadow 0.2s, transform 0.15s;
    text-decoration: none;
    color: inherit;
    margin-bottom: 10px;
}
.session-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); transform: translateY(-1px); }
.date-badge {
    background: linear-gradient(135deg, #0f766e, #0ea5e9);
    color: white;
    border-radius: 12px;
    padding: 10px 14px;
    text-align: center;
    min-width: 58px;
    flex-shrink: 0;
}
.date-badge .day { font-size: 1.5rem; font-weight: 900; line-height: 1; }
.date-badge .month { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }
.stat-pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 700;
}
.btn-new {
    background: linear-gradient(135deg, #0f766e, #0ea5e9);
    color: white; border: none; padding: 10px 22px;
    border-radius: 10px; font-weight: 700; font-size: 0.9rem;
    cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 8px;
    transition: transform 0.15s, box-shadow 0.15s;
}
.btn-new:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(15,118,110,0.35); color: white; }
.empty-state { text-align: center; padding: 60px 20px; }
.empty-state i { font-size: 3.5rem; color: #cbd5e1; margin-bottom: 16px; display: block; }
</style>

<div style="max-width: 900px; margin: 0 auto;">

    {{-- HEADER --}}
    <div style="background: linear-gradient(135deg, #1e3a5f 0%, #0f766e 100%); border-radius: 16px; padding: 26px 28px; color: white; margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
            <div>
                <div style="font-size: 0.8rem; opacity: 0.75; margin-bottom: 6px;">
                    <a href="{{ route('tahfidz-groups.show', $group->id) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> {{ $group->name }}
                    </a>
                    <span> › Absensi Sesi</span>
                </div>
                <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800;">
                    <i class="fas fa-clipboard-check" style="margin-right: 10px; opacity: 0.8;"></i>
                    Absensi Mentoring Tahfidz
                </h2>
                <div style="margin-top: 8px; font-size: 0.85rem; opacity: 0.8;">
                    Kelompok: <strong>{{ $group->name }}</strong> &nbsp;·&nbsp;
                    Pembimbing: <strong>{{ $group->mentor_name }}</strong> &nbsp;·&nbsp;
                    {{ $totalSiswa }} Siswa Terdaftar
                </div>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                {{-- Filter tahun --}}
                <form method="GET" style="display: flex; align-items: center; gap: 6px;">
                    <select name="year_id" onchange="this.form.submit()" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 8px; padding: 7px 10px; font-size: 0.85rem; cursor: pointer;">
                        @foreach($academicYears as $yr)
                            <option value="{{ $yr->id }}" {{ $yr->id == $yearId ? 'selected' : '' }} style="color: #0f172a; background: white;">
                                TP {{ $yr->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
                @if($sessions->isNotEmpty())
                    <a href="{{ route('tahfidz.attendance.print-recap', ['group' => $group->id, 'year_id' => $yearId]) }}" target="_blank"
                       style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; text-decoration: none; padding: 7px 14px; border-radius: 8px; font-size: 0.85rem; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-table"></i> Rekap Absensi
                    </a>
                @endif
                <a href="{{ route('tahfidz.attendance.create', ['group' => $group->id, 'year_id' => $yearId]) }}" class="btn-new">
                    <i class="fas fa-plus"></i> Input Absensi Baru
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- DAFTAR SESI --}}
    @forelse($sessions as $session)
        @php
            $sessionDate = \Carbon\Carbon::parse($session->session_date);
            $hadir = $session->hadir ?? 0;
            $sakit = $session->sakit ?? 0;
            $izin  = $session->izin  ?? 0;
            $alpha = $session->alpha ?? 0;
            $total = $session->total_siswa ?? 0;
            $pct   = $total > 0 ? round(($hadir / $total) * 100) : 0;
        @endphp

        <div class="session-card">
            {{-- Tanggal badge --}}
            <div class="date-badge">
                <div class="day">{{ $sessionDate->format('d') }}</div>
                <div class="month">{{ $sessionDate->isoFormat('MMM') }}</div>
            </div>

            {{-- Info sesi --}}
            <div style="flex: 1; min-width: 0;">
                <div style="font-weight: 700; font-size: 1rem; color: #0f172a; margin-bottom: 6px;">
                    {{ $sessionDate->isoFormat('dddd, D MMMM Y') }}
                </div>
                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                    <span class="stat-pill" style="background: #dcfce7; color: #16a34a;"><i class="fas fa-check"></i> {{ $hadir }} Hadir</span>
                    <span class="stat-pill" style="background: #fef3c7; color: #d97706;"><i class="fas fa-thermometer-half"></i> {{ $sakit }} Sakit</span>
                    <span class="stat-pill" style="background: #e0f2fe; color: #0369a1;"><i class="fas fa-file-alt"></i> {{ $izin }} Izin</span>
                    <span class="stat-pill" style="background: #fee2e2; color: #dc2626;"><i class="fas fa-times"></i> {{ $alpha }} Alpha</span>
                </div>
            </div>

            {{-- Progress kehadiran --}}
            <div style="text-align: center; min-width: 70px;">
                <div style="font-size: 1.4rem; font-weight: 900; color: {{ $pct >= 80 ? '#16a34a' : ($pct >= 60 ? '#d97706' : '#dc2626') }};">
                    {{ $pct }}%
                </div>
                <div style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.4px;">Hadir</div>
            </div>

            {{-- Aksi --}}
            <div style="display: flex; flex-direction: column; gap: 6px; min-width: fit-content;">
                <a href="{{ route('tahfidz.attendance.session', ['group' => $group->id, 'date' => $session->session_date]) }}"
                   style="background: #0f766e; color: white; text-decoration: none; padding: 6px 12px; border-radius: 7px; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-eye"></i> Detail
                </a>
                <a href="{{ route('tahfidz.attendance.create', ['group' => $group->id, 'date' => $session->session_date, 'year_id' => $yearId]) }}"
                   style="background: #f59e0b; color: white; text-decoration: none; padding: 6px 12px; border-radius: 7px; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('tahfidz.attendance.print-session', ['group' => $group->id, 'date' => $session->session_date]) }}" target="_blank"
                   style="background: #334155; color: white; text-decoration: none; padding: 6px 12px; border-radius: 7px; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-print"></i> Cetak
                </a>
            </div>
        </div>
    @empty
        <div class="empty-state" style="background: white; border-radius: 16px; border: 1px solid #e2e8f0;">
            <i class="fas fa-clipboard"></i>
            <h3 style="margin: 0 0 8px 0; color: #475569;">Belum Ada Sesi Absensi</h3>
            <p style="color: #94a3b8; margin: 0 0 20px 0; font-size: 0.9rem;">
                Mulai catat kehadiran siswa pada sesi pertama mentoring tahfidz.
            </p>
            <a href="{{ route('tahfidz.attendance.create', ['group' => $group->id, 'year_id' => $yearId]) }}" class="btn-new" style="display: inline-flex;">
                <i class="fas fa-plus"></i> Input Absensi Pertama
            </a>
        </div>
    @endforelse

</div>

@endsection
