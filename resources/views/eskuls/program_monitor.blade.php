@extends('layouts.app')

@section('title', 'Monitoring Program Kerja Eskul')
@section('page-title', 'Monitoring Program Kerja Ekstrakurikuler')

@push('styles')
<style>
    .pm-container {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 60px;
    }

    .pm-header {
        background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
        color: white;
        border-radius: 20px;
        padding: 26px 30px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
    }
    .pm-header h1 {
        font-size: 1.55rem;
        font-weight: 800;
        margin: 0;
        line-height: 1.2;
    }
    .pm-header p {
        font-size: 0.9rem;
        color: #94a3b8;
        margin: 6px 0 0 0;
    }
    .pm-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(59, 130, 246, 0.25);
        color: #93c5fd;
        border: 1px solid rgba(147, 197, 253, 0.35);
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
    .filter-tab.active-complete {
        background: #059669;
        color: #ffffff;
    }
    .filter-tab.active-draft {
        background: #d97706;
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
        border-color: #2563eb;
        background: #ffffff;
    }
    .btn-search {
        background: #2563eb;
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
    .pm-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }
    .pm-table thead th {
        background: #1e3a5f;
        color: #ffffff;
        padding: 13px 16px;
        font-weight: 700;
        font-size: 0.82rem;
        text-align: left;
        white-space: nowrap;
    }
    .pm-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s ease;
    }
    .pm-table tbody tr:hover {
        background: #f8fafc;
    }
    .pm-table tbody td {
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
    .badge-status.complete {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .badge-status.draft {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
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
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .btn-action-view:hover {
        background: #2563eb;
        color: #ffffff;
    }
    .btn-action-print {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .btn-action-print:hover {
        background: #059669;
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
        .pm-header {
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
<div class="pm-container">

    {{-- Header Banner Pengawasan Admin --}}
    <div class="pm-header">
        <div>
            <div class="pm-badge">
                <i class="fas fa-shield-alt"></i> Panel Kontrol & Pengawasan Admin
            </div>
            <h1>Monitoring Program Kerja Ekstrakurikuler</h1>
            <p>
                Pantau kelengkapan dokumen Program Kerja Tahunan, Visi, Misi, dan Rencana Kegiatan Bulanan yang disusun oleh masing-masing guru pembina.
                <br>Tahun Pelajaran: <strong>{{ $activeYear->name }}</strong>
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
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>

        <!-- Lengkap & Siap Cetak -->
        <div class="stat-card" style="border-color: #a7f3d0;">
            <div class="stat-info">
                <div class="label" style="color: #059669;">Lengkap & Disusun</div>
                <div class="value" style="color: #065f46;">{{ $completeCount }}</div>
                <div class="subtext" style="color: #059669;">
                    {{ $totalEskuls > 0 ? round(($completeCount / $totalEskuls) * 100) : 0 }}% Dokumen Tuntas
                </div>
            </div>
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <i class="fas fa-check-double"></i>
            </div>
        </div>

        <!-- Draft / Sebagian -->
        <div class="stat-card" style="border-color: {{ $draftCount > 0 ? '#fde68a' : '#e2e8f0' }};">
            <div class="stat-info">
                <div class="label" style="color: {{ $draftCount > 0 ? '#b45309' : '#64748b' }};">Sedang Disusun</div>
                <div class="value" style="color: {{ $draftCount > 0 ? '#78350f' : '#334155' }};">{{ $draftCount }}</div>
                <div class="subtext" style="color: {{ $draftCount > 0 ? '#d97706' : '#94a3b8' }};">
                    Draft Rencana
                </div>
            </div>
            <div class="stat-icon" style="background: {{ $draftCount > 0 ? '#fffbeb' : '#f8fafc' }}; color: {{ $draftCount > 0 ? '#d97706' : '#94a3b8' }};">
                <i class="fas fa-edit"></i>
            </div>
        </div>

        <!-- Belum Menyusun -->
        <div class="stat-card" style="border-color: {{ $emptyCount > 0 ? '#fecaca' : '#e2e8f0' }};">
            <div class="stat-info">
                <div class="label" style="color: {{ $emptyCount > 0 ? '#dc2626' : '#64748b' }};">Belum Menyusun</div>
                <div class="value" style="color: {{ $emptyCount > 0 ? '#991b1b' : '#334155' }};">{{ $emptyCount }}</div>
                <div class="subtext" style="color: {{ $emptyCount > 0 ? '#dc2626' : '#94a3b8' }};">
                    Perlu diingatkan
                </div>
            </div>
            <div class="stat-icon" style="background: {{ $emptyCount > 0 ? '#fef2f2' : '#f8fafc' }}; color: {{ $emptyCount > 0 ? '#dc2626' : '#94a3b8' }};">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="toolbar-card">
        <div class="filter-tabs">
            <a href="{{ route('eskuls.program.monitor', ['status' => 'all', 'search' => $searchKeyword]) }}" 
               class="filter-tab {{ $statusFilter === 'all' ? 'active-all' : 'inactive' }}">
                <i class="fas fa-list"></i> Semua Eskul ({{ $totalEskuls }})
            </a>
            <a href="{{ route('eskuls.program.monitor', ['status' => 'complete', 'search' => $searchKeyword]) }}" 
               class="filter-tab {{ $statusFilter === 'complete' ? 'active-complete' : 'inactive' }}">
                <i class="fas fa-check-double"></i> Lengkap ({{ $completeCount }})
            </a>
            <a href="{{ route('eskuls.program.monitor', ['status' => 'draft', 'search' => $searchKeyword]) }}" 
               class="filter-tab {{ $statusFilter === 'draft' ? 'active-draft' : 'inactive' }}">
                <i class="fas fa-edit"></i> Draft ({{ $draftCount }})
            </a>
            <a href="{{ route('eskuls.program.monitor', ['status' => 'empty', 'search' => $searchKeyword]) }}" 
               class="filter-tab {{ $statusFilter === 'empty' ? 'active-empty' : 'inactive' }}">
                <i class="fas fa-exclamation-triangle"></i> Belum Menyusun ({{ $emptyCount }})
            </a>
        </div>

        <form method="GET" action="{{ route('eskuls.program.monitor') }}" class="search-box">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <input type="text" name="search" value="{{ $searchKeyword }}" placeholder="Cari eskul atau pembina..." class="search-input">
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i>
            </button>
            @if(!empty($searchKeyword) || $statusFilter !== 'all')
                <a href="{{ route('eskuls.program.monitor') }}" class="btn-reset" title="Reset filter">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel Monitoring --}}
    <div class="table-card">
        <div style="overflow-x: auto;">
            <table class="pm-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">No</th>
                        <th style="width: 25%;">Ekstrakurikuler</th>
                        <th style="width: 22%;">Guru Pembina</th>
                        <th style="width: 14%; text-align: center;">Visi, Misi & Sasaran</th>
                        <th style="width: 13%; text-align: center;">Rencana Kegiatan</th>
                        <th style="width: 13%; text-align: center;">Status Dokumen</th>
                        <th style="width: 13%; text-align: center;">Aksi Kontrol</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monitorData as $item)
                        @php
                            $esk = $item['eskul'];
                            $pembina = $item['pembina'];
                            $pembinaName = $pembina ? $pembina->name : ($esk->instructor_name ?? 'Belum ditentukan');
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
                                    <i class="fas fa-user-tie" style="color: #2563eb;"></i>
                                    <span>{{ $pembinaName }}</span>
                                </div>
                                @if($pembina && $pembina->phone)
                                    <div style="font-size: 0.76rem; color: #059669; margin-top: 3px; font-weight: 600;">
                                        <i class="fab fa-whatsapp"></i> {{ $pembina->phone }}
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($item['has_visi'] && $item['has_misi'])
                                    <span style="display: inline-flex; align-items: center; gap: 4px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-weight: 700; font-size: 0.78rem; padding: 4px 10px; border-radius: 999px;">
                                        <i class="fas fa-check"></i> Lengkap
                                    </span>
                                @elseif($item['has_visi'] || $item['has_misi'])
                                    <span style="display: inline-flex; align-items: center; gap: 4px; background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-weight: 700; font-size: 0.78rem; padding: 4px 10px; border-radius: 999px;">
                                        <i class="fas fa-pen"></i> Sebagian
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 4px; background: #f8fafc; color: #94a3b8; border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.78rem; padding: 4px 10px; border-radius: 999px;">
                                        <i class="far fa-clock"></i> Belum Diisi
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($item['item_count'] > 0)
                                    <span style="display: inline-block; background: #eff6ff; color: #1d4ed8; border: 1.5px solid #bfdbfe; font-weight: 800; font-size: 0.88rem; padding: 4px 12px; border-radius: 999px;">
                                        {{ $item['item_count'] }} Kegiatan
                                    </span>
                                @else
                                    <span style="display: inline-block; background: #f8fafc; color: #94a3b8; border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.82rem; padding: 3px 10px; border-radius: 999px;">
                                        0 Kegiatan
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($item['is_complete'])
                                    <span class="badge-status complete">
                                        <i class="fas fa-check-double"></i> Dokumen Lengkap
                                    </span>
                                @elseif($item['is_draft'])
                                    <span class="badge-status draft">
                                        <i class="fas fa-edit"></i> Draft Program
                                    </span>
                                @else
                                    <span class="badge-status empty">
                                        <i class="fas fa-exclamation-circle"></i> Belum Menyusun
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; align-items: center; gap: 6px;">
                                    <a href="{{ route('eskuls.program.index', $esk->id) }}" class="btn-action btn-action-view" title="Buka dan Review Program Kerja">
                                        <i class="fas fa-eye"></i> Tinjau
                                    </a>
                                    <a href="{{ route('eskuls.program.print', $esk->id) }}" target="_blank" class="btn-action btn-action-print" title="Cetak Dokumen Resmi (PDF)">
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
                                <strong style="color: #475569; font-size: 0.95rem; display: block;">Tidak ada data program kerja eskul yang sesuai kriteria.</strong>
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
