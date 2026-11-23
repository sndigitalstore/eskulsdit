@extends('layouts.app')

@section('title', 'Monitoring Jurnal Kegiatan Eskul')
@section('page-title', 'Monitoring Jurnal Ekstrakurikuler')

@push('styles')
<style>
    .jm-container {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 60px;
    }

    .jm-header {
        background: linear-gradient(135deg, #064e3b 0%, #022c22 100%);
        color: white;
        border-radius: 20px;
        padding: 26px 30px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        box-shadow: 0 10px 25px -5px rgba(6, 78, 59, 0.2);
    }
    .jm-header h1 {
        font-size: 1.55rem;
        font-weight: 800;
        margin: 0;
        line-height: 1.2;
    }
    .jm-header p {
        font-size: 0.9rem;
        color: #a7f3d0;
        margin: 6px 0 0 0;
    }
    .jm-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(52, 211, 153, 0.2);
        color: #6ee7b7;
        border: 1px solid rgba(110, 231, 183, 0.35);
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    /* KPI Tiles */
    .stat-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    }
    .stat-info .label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .stat-info .value {
        font-size: 1.7rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.1;
    }
    .stat-info .subtext {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 4px;
        font-weight: 600;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }

    /* Toolbar Filter */
    .toolbar-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .filter-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .filter-tab {
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid transparent;
        transition: all 0.15s ease;
    }
    .filter-tab.active-all {
        background: #0f172a;
        color: #ffffff;
    }
    .filter-tab.active-filled {
        background: #059669;
        color: #ffffff;
    }
    .filter-tab.active-empty {
        background: #dc2626;
        color: #ffffff;
    }
    .filter-tab.inactive {
        background: #f1f5f9;
        color: #64748b;
        border-color: #e2e8f0;
    }
    .filter-tab.inactive:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .search-input {
        padding: 8px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.85rem;
        background: #f8fafc;
        color: #0f172a;
        min-width: 220px;
        outline: none;
        transition: border-color 0.2s;
    }
    .search-input:focus {
        border-color: #059669;
        background: #ffffff;
    }
    .btn-search {
        background: #059669;
        color: white;
        border: none;
        border-radius: 9px;
        padding: 8px 14px;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
    }
    .btn-reset {
        background: #e2e8f0;
        color: #475569;
        border: none;
        border-radius: 9px;
        padding: 8px 12px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
    }

    /* Table Card */
    .table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03);
    }
    .jm-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }
    .jm-table thead th {
        background: #064e3b;
        color: #ffffff;
        padding: 13px 16px;
        font-weight: 700;
        font-size: 0.82rem;
        text-align: left;
        white-space: nowrap;
    }
    .jm-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s ease;
    }
    .jm-table tbody tr:hover {
        background: #f0fdf4;
    }
    .jm-table tbody td {
        padding: 14px 16px;
        color: #334155;
        vertical-align: middle;
    }

    .eskul-title {
        font-weight: 800;
        color: #0f172a;
        font-size: 0.95rem;
    }
    .eskul-meta {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .badge-pembina {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 700;
        color: #1e293b;
        font-size: 0.85rem;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 750;
        white-space: nowrap;
    }
    .badge-status.active {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .badge-status.empty {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .btn-action-view {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .btn-action-view:hover {
        background: #059669;
        color: #ffffff;
    }
    .btn-action-print {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .btn-action-print:hover {
        background: #2563eb;
        color: #ffffff;
    }
    .btn-action-wa {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .btn-action-wa:hover {
        background: #16a34a;
        color: #ffffff;
    }

    @media (max-width: 768px) {
        .jm-header {
            padding: 20px;
        }
        .stat-row {
            grid-template-columns: 1fr 1fr;
        }
        .search-input {
            min-width: 140px;
        }
    }
</style>
@endpush

@section('content')
<div class="jm-container">

    {{-- Header Banner Pengawasan Admin --}}
    <div class="jm-header">
        <div>
            <div class="jm-badge">
                <i class="fas fa-shield-alt"></i> Panel Kontrol & Pengawasan Admin
            </div>
            <h1>Monitoring Jurnal Kegiatan Eskul</h1>
            <p>
                Pantau pelaksanaan pertemuan, catatan materi, dan absensi yang diinput oleh masing-masing guru pembina ekstrakurikuler.
                <br>Tahun Ajaran: <strong>{{ $activeYear->name }}</strong> (Semester {{ $semester }})
            </p>
        </div>
        <div>
            <a href="{{ route('eskuls.index') }}" style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 9px 18px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 7px;">
                <i class="fas fa-shapes"></i> Kelola Eskul
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="stat-row">
        <!-- Total Eskul -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="label">Total Eskul</div>
                <div class="value">{{ $totalEskuls }}</div>
                <div class="subtext">Cabang Ekstrakurikuler</div>
            </div>
            <div class="stat-icon" style="background: #f8fafc; color: #475569;">
                <i class="fas fa-basketball-ball"></i>
            </div>
        </div>

        <!-- Sudah Mengisi -->
        <div class="stat-card" style="border-color: #a7f3d0;">
            <div class="stat-info">
                <div class="label" style="color: #059669;">Jurnal Terisi</div>
                <div class="value" style="color: #065f46;">{{ $filledCount }}</div>
                <div class="subtext" style="color: #059669;">
                    {{ $totalEskuls > 0 ? round(($filledCount / $totalEskuls) * 100) : 0 }}% Eskul Aktif
                </div>
            </div>
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>

        <!-- Belum Mengisi -->
        <div class="stat-card" style="border-color: {{ $emptyCount > 0 ? '#fecaca' : '#e2e8f0' }};">
            <div class="stat-info">
                <div class="label" style="color: {{ $emptyCount > 0 ? '#dc2626' : '#64748b' }};">Belum Mengisi</div>
                <div class="value" style="color: {{ $emptyCount > 0 ? '#991b1b' : '#334155' }};">{{ $emptyCount }}</div>
                <div class="subtext" style="color: {{ $emptyCount > 0 ? '#dc2626' : '#94a3b8' }};">
                    Perlu diingatkan
                </div>
            </div>
            <div class="stat-icon" style="background: {{ $emptyCount > 0 ? '#fef2f2' : '#f8fafc' }}; color: {{ $emptyCount > 0 ? '#dc2626' : '#94a3b8' }};">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>

        <!-- Total Sesi Pertemuan -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="label" style="color: #2563eb;">Total Pertemuan</div>
                <div class="value" style="color: #1e3a8a;">{{ $totalAllMeetings }}</div>
                <div class="subtext">Sesi Jurnal Tercatat</div>
            </div>
            <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="toolbar-card">
        <div class="filter-tabs">
            <a href="{{ route('eskuls.jurnal.monitor', ['status' => 'all', 'search' => $searchKeyword]) }}" 
               class="filter-tab {{ $statusFilter === 'all' ? 'active-all' : 'inactive' }}">
                <i class="fas fa-list"></i> Semua Eskul ({{ $totalEskuls }})
            </a>
            <a href="{{ route('eskuls.jurnal.monitor', ['status' => 'filled', 'search' => $searchKeyword]) }}" 
               class="filter-tab {{ $statusFilter === 'filled' ? 'active-filled' : 'inactive' }}">
                <i class="fas fa-check-circle"></i> Jurnal Terisi ({{ $filledCount }})
            </a>
            <a href="{{ route('eskuls.jurnal.monitor', ['status' => 'empty', 'search' => $searchKeyword]) }}" 
               class="filter-tab {{ $statusFilter === 'empty' ? 'active-empty' : 'inactive' }}">
                <i class="fas fa-exclamation-triangle"></i> Belum Mengisi ({{ $emptyCount }})
            </a>
        </div>

        <form method="GET" action="{{ route('eskuls.jurnal.monitor') }}" class="search-box">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <input type="text" name="search" value="{{ $searchKeyword }}" placeholder="Cari eskul atau pembina..." class="search-input">
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i>
            </button>
            @if(!empty($searchKeyword) || $statusFilter !== 'all')
                <a href="{{ route('eskuls.jurnal.monitor') }}" class="btn-reset" title="Reset filter">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel Monitoring --}}
    <div class="table-card">
        <div style="overflow-x: auto;">
            <table class="jm-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">No</th>
                        <th style="width: 25%;">Ekstrakurikuler</th>
                        <th style="width: 22%;">Guru Pembina</th>
                        <th style="width: 12%; text-align: center;">Total Pertemuan</th>
                        <th style="width: 20%;">Pertemuan Terakhir</th>
                        <th style="width: 11%; text-align: center;">Status Jurnal</th>
                        <th style="width: 10%; text-align: center;">Aksi Kontrol</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monitorData as $item)
                        @php
                            $esk = $item['eskul'];
                            $pembina = $item['pembina'];
                            $pembinaName = $pembina ? $pembina->name : ($esk->instructor_name ?? 'Belum ditentukan');
                            $latest = $item['latest_journal'];
                        @endphp
                        <tr>
                            <td style="text-align: center; font-weight: 700; color: #64748b;">
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <div class="eskul-title">{{ $esk->name }}</div>
                                <div class="eskul-meta">
                                    <i class="far fa-clock"></i> {{ $esk->schedule ?? 'Jadwal belum diset' }}
                                    @if($esk->location)
                                        &nbsp;·&nbsp; <i class="fas fa-map-marker-alt"></i> {{ $esk->location }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="badge-pembina">
                                    <i class="fas fa-user-tie" style="color: #059669;"></i>
                                    <span>{{ $pembinaName }}</span>
                                </div>
                                @if($pembina && $pembina->phone)
                                    <div style="font-size: 0.76rem; color: #059669; margin-top: 3px; font-weight: 600;">
                                        <i class="fab fa-whatsapp"></i> {{ $pembina->phone }}
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($item['total_meetings'] > 0)
                                    <span style="display: inline-block; background: #ecfdf5; color: #047857; border: 1.5px solid #a7f3d0; font-weight: 800; font-size: 0.95rem; padding: 4px 12px; border-radius: 999px;">
                                        {{ $item['total_meetings'] }} Pertemuan
                                    </span>
                                @else
                                    <span style="display: inline-block; background: #f8fafc; color: #94a3b8; border: 1px solid #e2e8f0; font-weight: 700; font-size: 0.85rem; padding: 3px 10px; border-radius: 999px;">
                                        0 Pertemuan
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($latest)
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.84rem;">
                                        {{ \Carbon\Carbon::parse($latest->tanggal)->locale('id')->isoFormat('D MMM YYYY') }}
                                        <span style="font-size: 0.72rem; color: #059669; font-weight: 600;">(Prtm {{ $latest->pertemuan_ke }})</span>
                                    </div>
                                    <div style="font-size: 0.78rem; color: #475569; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 220px;" title="{{ $latest->materi }}">
                                        {{ $latest->materi }}
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-style: italic; font-size: 0.8rem;">
                                        Belum ada pertemuan tercatat
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($item['is_filled'])
                                    <span class="badge-status active">
                                        <i class="fas fa-check-circle"></i> Aktif Terisi
                                    </span>
                                @else
                                    <span class="badge-status empty">
                                        <i class="fas fa-exclamation-circle"></i> Belum Mengisi
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; align-items: center; gap: 6px;">
                                    <a href="{{ route('eskuls.jurnal.index', $esk->id) }}" class="btn-action btn-action-view" title="Buka dan Review Jurnal">
                                        <i class="fas fa-eye"></i> Tinjau
                                    </a>
                                    <a href="{{ route('eskuls.jurnal.print', $esk->id) }}" target="_blank" class="btn-action btn-action-print" title="Cetak Jurnal (PDF)">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    @if($item['wa_url'])
                                        <a href="{{ $item['wa_url'] }}" target="_blank" class="btn-action btn-action-wa" title="Ingatkan Guru Pembina via WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                                <i class="fas fa-search" style="font-size: 2.2rem; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                                <strong style="color: #475569; font-size: 0.95rem; display: block;">Tidak ada data eskul yang sesuai kriteria.</strong>
                                <span style="font-size: 0.82rem;">Coba ganti filter atau kata kunci pencarian Anda.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
