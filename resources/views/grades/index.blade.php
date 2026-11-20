@extends('layouts.app')

@section('title', 'Penilaian Ekstrakurikuler')
@section('page-title', 'Penilaian Ekstrakurikuler')

@push('styles')
<style>
    /* ========================================================
       A. STYLES KHUSUS ADMIN (CONTROL CENTER & SUPERVISI)
       ======================================================== */
    .admin-grades-container {
        display: flex;
        flex-direction: column;
        gap: 24px;
        margin-bottom: 40px;
    }

    /* 1. Header Banner */
    .admin-hero-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
        border-radius: 24px;
        padding: 26px 30px;
        color: white;
        box-shadow: 0 12px 30px -6px rgba(49, 46, 129, 0.25);
        border: 1.5px solid rgba(99, 102, 241, 0.4);
        position: relative;
        overflow: hidden;
    }
    .admin-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        position: relative;
        z-index: 2;
    }
    .admin-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #c7d2fe;
        margin-bottom: 8px;
    }
    .admin-hero-title {
        font-size: 1.6rem;
        font-weight: 850;
        margin: 0 0 6px;
        letter-spacing: -0.4px;
    }
    .admin-hero-subtitle {
        font-size: 0.88rem;
        color: #e0e7ff;
        margin: 0;
        max-width: 650px;
        line-height: 1.5;
        opacity: 0.9;
    }
    .admin-hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn-hero-primary {
        background: #10b981;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 0.86rem;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-hero-primary:hover {
        background: #059669;
        transform: translateY(-2px);
    }
    .btn-hero-secondary {
        background: rgba(255, 255, 255, 0.12);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 10px 16px;
        border-radius: 12px;
        font-size: 0.86rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(8px);
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-hero-secondary:hover {
        background: rgba(255, 255, 255, 0.22);
    }

    /* 2. Filter Bar */
    .admin-filter-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .filter-group-inline {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .filter-label-inline {
        font-size: 0.82rem;
        font-weight: 750;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .filter-select-inline {
        padding: 7px 14px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        font-size: 0.85rem;
        font-weight: 700;
        color: #0f172a;
        background: #f8fafc;
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-select-inline:focus {
        border-color: #6366f1;
        background: #ffffff;
    }

    /* 3. KPI Grid */
    .admin-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }
    .admin-kpi-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1.5px solid #e2e8f0;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .admin-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    }
    .admin-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
    .admin-kpi-num {
        font-size: 1.65rem;
        font-weight: 850;
        color: #0f172a;
        line-height: 1.15;
    }
    .admin-kpi-lbl {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        margin-top: 3px;
    }

    /* 4. Table Monitoring Card */
    .admin-table-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }
    .table-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }
    .table-title {
        font-size: 1.15rem;
        font-weight: 850;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .table-search-input {
        padding: 8px 16px 8px 36px;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        font-size: 0.84rem;
        outline: none;
        width: 250px;
        background: #f8fafc;
        transition: all 0.2s;
    }
    .table-search-input:focus {
        border-color: #6366f1;
        background: #ffffff;
        width: 280px;
    }

    .monitoring-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.88rem;
    }
    .monitoring-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 800;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 14px;
        border-top: 1px solid #e2e8f0;
        border-bottom: 2px solid #cbd5e1;
        text-align: left;
    }
    .monitoring-table td {
        padding: 14px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
    }
    .monitoring-table tr:hover td {
        background: #f8fafc;
    }

    /* Badges */
    .badge-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .badge-status.complete {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }
    .badge-status.partial {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    .badge-status.empty {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    /* Action Buttons in Table */
    .btn-table-action {
        padding: 6px 12px;
        border-radius: 9px;
        font-size: 0.78rem;
        font-weight: 750;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
    }
    .btn-table-report {
        background: #f5f3ff;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
    }
    .btn-table-report:hover {
        background: #ede9fe;
        color: #5b21b6;
    }
    .btn-table-input {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .btn-table-input:hover {
        background: #d1fae5;
        color: #065f46;
    }

    /* 5. Riwayat Card */
    .recent-sessions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
        gap: 14px;
        margin-top: 14px;
    }
    .session-card-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 10px;
        transition: all 0.2s;
    }
    .session-card-item:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }
    .session-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }
    .session-title {
        font-weight: 800;
        font-size: 0.92rem;
        color: #0f172a;
        margin: 0 0 2px 0;
    }
    .session-meta {
        font-size: 0.78rem;
        color: #64748b;
    }
    .session-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 8px;
        border-top: 1px dashed #e2e8f0;
    }

    /* Modal Form Custom */
    .modal-backdrop-custom {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(6px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-content-custom {
        background: #ffffff;
        border-radius: 24px;
        max-width: 540px;
        width: 100%;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        animation: modalScaleUp 0.25s ease-out;
    }
    @keyframes modalScaleUp {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* ========================================================
       B. STYLES KHUSUS GURU (FORM FOKUS INPUT)
       ======================================================== */
    .grades-select-wrapper {
        max-width: 580px;
        margin: 1.5rem auto 3rem;
        padding: 0 12px;
    }
    .card.grades-select-card {
        padding: 0 !important;
        border-radius: 24px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        background: #ffffff;
    }
    .grades-select-header {
        background: linear-gradient(180deg, #f5f3ff 0%, #ffffff 100%);
        padding: 28px 24px 20px;
        border-bottom: 1px solid #f1f5f9;
        text-align: center;
    }
    .grades-select-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: #ede9fe;
        color: #7c3aed;
        border: 1.5px solid #ddd6fe;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 14px;
    }
    .grades-select-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 6px;
    }
    .grades-select-desc {
        font-size: 0.88rem;
        color: #64748b;
        margin: 0;
    }
    .grades-select-body {
        padding: 24px;
    }
    .form-group-custom {
        margin-bottom: 20px;
    }
    .form-label-custom {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        font-weight: 750;
        color: #334155;
        margin-bottom: 8px;
        text-transform: uppercase;
    }
    .form-label-custom i {
        color: #7c3aed;
    }
    .input-control-custom {
        width: 100% !important;
        height: 48px !important;
        border-radius: 14px !important;
        border: 1.5px solid #cbd5e1 !important;
        padding: 0 16px !important;
        font-size: 0.94rem !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        background-color: #ffffff !important;
    }
    .btn-action-primary {
        width: 100%;
        padding: 13px 24px;
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        color: white;
        border: none;
        border-radius: 14px;
        font-weight: 750;
        font-size: 0.95rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 6px 18px rgba(124, 58, 237, 0.3);
    }
    .grades-select-footer {
        background: #f8fafc;
        padding: 20px 24px;
        border-top: 1px solid #f1f5f9;
        text-align: center;
    }
    .btn-action-secondary {
        width: 100%;
        padding: 11px 20px;
        background: #ffffff;
        color: #db2777;
        border: 1.5px solid #fbcfe8;
        border-radius: 14px;
        font-weight: 750;
        font-size: 0.9rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    @media (max-width: 768px) {
        .admin-hero-card {
            padding: 20px 18px;
        }
        .admin-hero-title {
            font-size: 1.3rem;
        }
        .admin-kpi-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .admin-kpi-card {
            padding: 14px 12px;
            gap: 10px;
        }
        .admin-kpi-num {
            font-size: 1.35rem;
        }
        .admin-kpi-icon {
            width: 38px;
            height: 38px;
            font-size: 1.1rem;
        }
        .table-search-input {
            width: 100% !important;
        }
    }
</style>
@endpush

@section('content')

@if(in_array(Auth::user()->role, ['admin', 'headmaster']))
    {{-- =========================================================================
         TAMPILAN KHUSUS ADMINISTRATOR / KEPALA SEKOLAH (CONTROL CENTER SUPERVISI)
         ========================================================================= --}}
    <div class="admin-grades-container">

        <!-- 1. Hero Header Banner -->
        <div class="admin-hero-card">
            <div class="admin-hero-top">
                <div>
                    <span class="admin-hero-badge">
                        <i class="fas fa-shield-alt"></i> Pusat Kontrol & Supervisi
                    </span>
                    <h1 class="admin-hero-title">Monitoring Penilaian Ekstrakurikuler</h1>
                    <p class="admin-hero-subtitle">
                        Pantau kelengkapan penilaian harian per pertemuan serta kesiapan cetak raport SAS 1 & SAS 2 untuk seluruh kegiatan ekstrakurikuler SDIT An Nadzir.
                    </p>
                </div>
                <div class="admin-hero-actions">
                    <button type="button" onclick="openInputModal()" class="btn-hero-primary">
                        <i class="fas fa-plus-circle"></i>
                        <span>Input Nilai Baru</span>
                    </button>
                    <a href="{{ route('grades.import') }}" class="btn-hero-secondary">
                        <i class="fas fa-file-excel" style="color: #34d399;"></i>
                        <span>Import Excel Masal</span>
                    </a>
                </div>
            </div>
            <!-- Decor Icon -->
            <i class="fas fa-chart-pie" style="position: absolute; right: -15px; bottom: -20px; font-size: 8.5rem; color: rgba(255, 255, 255, 0.05); z-index: 1;"></i>
        </div>

        <!-- 2. Filter Bar (Tahun Ajaran & Semester) -->
        <div class="admin-filter-bar">
            <form id="filter-academic-form" action="{{ route('grades.index') }}" method="GET" style="margin: 0; width: 100%; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div class="filter-group-inline">
                    <span class="filter-label-inline">
                        <i class="fas fa-calendar-alt" style="color: #6366f1;"></i> Tahun Ajaran:
                    </span>
                    <select name="academic_year_id" class="filter-select-inline" onchange="document.getElementById('filter-academic-form').submit()">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $yearId == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '— (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>

                    <span class="filter-label-inline" style="margin-left: 8px;">
                        <i class="fas fa-layer-group" style="color: #6366f1;"></i> Semester:
                    </span>
                    <select name="semester" class="filter-select-inline" onchange="document.getElementById('filter-academic-form').submit()">
                        <option value="1" {{ $selectedSemester == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                        <option value="2" {{ $selectedSemester == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                    </select>
                </div>

                <div style="font-size: 0.82rem; color: #64748b; font-weight: 600;">
                    <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
                    Menampilkan data tahun: <strong style="color: #0f172a;">{{ $selectedYear ? $selectedYear->name : '-' }}</strong> • Semester <strong style="color: #0f172a;">{{ $selectedSemester }}</strong>
                </div>
            </form>
        </div>

        <!-- 3. KPI Indikator Kontrol (4 Kartu) -->
        <div class="admin-kpi-grid">
            <!-- Total Eskul -->
            <div class="admin-kpi-card">
                <div class="admin-kpi-icon" style="background: #e0e7ff; color: #4338ca;">
                    <i class="fas fa-shapes"></i>
                </div>
                <div>
                    <div class="admin-kpi-num">{{ $kpi->total_eskul }}</div>
                    <div class="admin-kpi-lbl">Total Ekstrakurikuler</div>
                </div>
            </div>

            <!-- Aktif Nilai Harian -->
            <div class="admin-kpi-card">
                <div class="admin-kpi-icon" style="background: #dcfce7; color: #15803d;">
                    <i class="far fa-calendar-check"></i>
                </div>
                <div>
                    <div class="admin-kpi-num">{{ $kpi->daily_active_count }} <span style="font-size: 0.9rem; font-weight: 500; color: #64748b;">/ {{ $kpi->total_eskul }}</span></div>
                    <div class="admin-kpi-lbl">Aktif Nilai Harian</div>
                </div>
            </div>

            <!-- Kesiapan SAS 1 -->
            <div class="admin-kpi-card">
                <div class="admin-kpi-icon" style="background: #fef3c7; color: #b45309;">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <div class="admin-kpi-num">{{ $selectedSemester == '1' ? $kpi->sas1_complete_count : $kpi->sas2_complete_count }} <span style="font-size: 0.9rem; font-weight: 500; color: #64748b;">/ {{ $kpi->total_eskul }}</span></div>
                    <div class="admin-kpi-lbl">Siap Raport (SAS {{ $selectedSemester }})</div>
                </div>
            </div>

            <!-- Total Nilai Terekam -->
            <div class="admin-kpi-card">
                <div class="admin-kpi-icon" style="background: #f5f3ff; color: #6d28d9;">
                    <i class="fas fa-database"></i>
                </div>
                <div>
                    <div class="admin-kpi-num">{{ number_format($kpi->total_grades_count, 0, ',', '.') }}</div>
                    <div class="admin-kpi-lbl">Nilai Masuk Terarsip</div>
                </div>
            </div>
        </div>

        <!-- 4. Tabel Monitoring Progres per Ekstrakurikuler -->
        <div class="admin-table-card">
            <div class="table-header-flex">
                <div>
                    <h3 class="table-title">
                        <i class="fas fa-tasks" style="color: #6366f1;"></i>
                        <span>Progres Penilaian Siswa per Kegiatan Eskul</span>
                    </h3>
                    <p style="margin: 4px 0 0; font-size: 0.82rem; color: #64748b;">
                        Status kelengkapan pengisian nilai harian serta nilai akhir semester dari setiap pembina.
                    </p>
                </div>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 11px; color: #94a3b8; font-size: 0.85rem;"></i>
                    <input type="text" id="filterEskulInput" class="table-search-input" placeholder="Cari eskul / pembina..." onkeyup="filterEskulTable()">
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="monitoring-table" id="eskulMonitoringTable">
                    <thead>
                        <tr>
                            <th width="4%">No</th>
                            <th width="24%">Ekstrakurikuler</th>
                            <th width="18%">Pembina / Tutor</th>
                            <th width="10%">Siswa</th>
                            <th width="14%">Nilai Harian</th>
                            <th width="11%">Nilai SAS 1</th>
                            <th width="11%">Nilai SAS 2</th>
                            <th width="8%" style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eskulProgress as $index => $item)
                        <tr class="eskul-row" data-name="{{ strtolower($item->name) }}" data-instructor="{{ strtolower($item->instructor_name ?? '') }}">
                            <td><strong>{{ $index + 1 }}</strong></td>
                            <td>
                                <div style="font-weight: 800; color: #0f172a; font-size: 0.92rem;">
                                    {{ $item->name }}
                                </div>
                                <div style="font-size: 0.76rem; color: #64748b; margin-top: 2px;">
                                    <span style="background: #f1f5f9; padding: 2px 7px; border-radius: 6px; font-weight: 600;">
                                        {{ $item->is_lockable ? 'Calistung' : 'Reguler' }}
                                    </span>
                                    @if($item->schedule)
                                        <span style="margin-left: 4px;"><i class="far fa-clock"></i> {{ $item->schedule }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #334155;">
                                    {{ $item->instructor_name ?: 'Belum ditentukan' }}
                                </div>
                                @if($item->last_updated)
                                    <div style="font-size: 0.74rem; color: #94a3b8; margin-top: 2px;">
                                        Input: {{ \Carbon\Carbon::parse($item->last_updated)->locale('id')->diffForHumans() }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge-status complete" style="font-size: 0.78rem;">
                                    <i class="fas fa-users"></i> {{ $item->student_count }}
                                </span>
                            </td>
                            <td>
                                @if($item->daily_session_count > 0)
                                    <span style="color: #059669; font-weight: 800; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-check-circle"></i> {{ $item->daily_session_count }} Pertemuan
                                    </span>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-minus-circle"></i> Belum ada
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($item->sas1_status === 'complete')
                                    <span class="badge-status complete">
                                        <i class="fas fa-check"></i> Lengkap
                                    </span>
                                @elseif($item->sas1_status === 'partial')
                                    <span class="badge-status partial">
                                        <i class="fas fa-exclamation"></i> Sebagian
                                    </span>
                                @else
                                    <span class="badge-status empty">
                                        <i class="far fa-clock"></i> Belum Diisi
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($item->sas2_status === 'complete')
                                    <span class="badge-status complete">
                                        <i class="fas fa-check"></i> Lengkap
                                    </span>
                                @elseif($item->sas2_status === 'partial')
                                    <span class="badge-status partial">
                                        <i class="fas fa-exclamation"></i> Sebagian
                                    </span>
                                @else
                                    <span class="badge-status empty">
                                        <i class="far fa-clock"></i> Belum Diisi
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="{{ route('grades.report', ['eskul_id' => $item->id, 'academic_year_id' => $yearId, 'semester' => $selectedSemester]) }}" class="btn-table-action btn-table-report" title="Buka Raport Nilai">
                                        <i class="fas fa-file-invoice"></i> Rekap
                                    </a>
                                    <a href="{{ route('grades.create', ['eskul_id' => $item->id, 'type' => 'daily', 'academic_year_id' => $yearId, 'semester' => $selectedSemester]) }}" class="btn-table-action btn-table-input" title="Buka Form Input">
                                        <i class="fas fa-edit"></i> Nilai
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2.5rem; color: #94a3b8;">
                                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                                Belum ada data ekstrakurikuler terdaftar pada tahun ajaran ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 5. Riwayat Input Penilaian Terbaru (Akses Cepat Revisi / Reset) -->
        <div class="admin-table-card">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 8px;">
                <div>
                    <h3 class="table-title">
                        <i class="fas fa-history" style="color: #ec4899;"></i>
                        <span>Riwayat Penilaian Masuk Terbaru</span>
                    </h3>
                    <p style="margin: 4px 0 0; font-size: 0.82rem; color: #64748b;">
                        Sesi penilaian yang baru saja diinput oleh pembina. Anda dapat membuka atau langsung mereset jika guru salah input.
                    </p>
                </div>
                <span style="font-size: 0.78rem; color: #64748b; font-weight: 700;">
                    8 Aktivitas Terakhir
                </span>
            </div>

            <div class="recent-sessions-grid">
                @forelse($recentGradingSessions as $session)
                    @php
                        $typeName = $session->type === 'daily' ? 'Nilai Harian' : strtoupper($session->type);
                        $dateFormatted = ($session->type === 'daily' && $session->date)
                            ? \Carbon\Carbon::parse($session->date)->isoFormat('D MMM Y')
                            : 'Periode ' . $typeName;
                    @endphp
                    <div class="session-card-item">
                        <div class="session-top">
                            <div>
                                <h4 class="session-title">{{ $session->eskul->name ?? 'Eskul' }}</h4>
                                <div class="session-meta">
                                    <span style="font-weight: 700; color: #4338ca;">{{ $typeName }}</span>
                                    <span>•</span>
                                    <span>{{ $dateFormatted }}</span>
                                </div>
                            </div>
                            <span class="badge-status complete" style="font-size: 0.72rem;">
                                {{ $session->student_count }} Siswa
                            </span>
                        </div>
                        <div class="session-bottom">
                            <span style="font-size: 0.72rem; color: #94a3b8;">
                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($session->last_updated)->diffForHumans() }}
                            </span>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <a href="{{ route('grades.create', ['eskul_id' => $session->eskul_id, 'type' => $session->type, 'date' => $session->date, 'academic_year_id' => $session->academic_year_id, 'semester' => $session->semester]) }}" style="font-size: 0.76rem; font-weight: 750; color: #3b82f6; text-decoration: none; padding: 4px 8px; background: #eff6ff; border-radius: 6px; border: 1px solid #bfdbfe;">
                                    <i class="fas fa-eye"></i> Buka
                                </a>
                                <form action="{{ route('grades.destroy-date') }}" method="POST" class="confirm-delete" data-confirm="Apakah Anda yakin ingin mengosongkan / mereset nilai {{ $session->eskul->name ?? 'Eskul' }} pada {{ $dateFormatted }}? Semua inputan tanggal ini akan dihapus bersih agar guru dapat menginput ulang." style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="eskul_id" value="{{ $session->eskul_id }}">
                                    <input type="hidden" name="type" value="{{ $session->type }}">
                                    <input type="hidden" name="date" value="{{ $session->date }}">
                                    <input type="hidden" name="academic_year_id" value="{{ $session->academic_year_id }}">
                                    <input type="hidden" name="semester" value="{{ $session->semester }}">
                                    <button type="submit" style="background: #fee2e2; border: 1px solid #fecdd3; color: #ef4444; border-radius: 6px; padding: 4px 8px; font-size: 0.76rem; cursor: pointer;" title="Reset Nilai Tanggal Ini">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: #94a3b8; font-size: 0.85rem;">
                        Belum ada riwayat penilaian tersimpan pada tahun ajaran ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL INPUT NILAI MANUAL ADMIN --}}
    <div id="modalInputNilaiManual" class="modal-backdrop-custom">
        <div class="modal-content-custom">
            <div style="background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%); padding: 22px 24px; color: white; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.15rem; font-weight: 800;">Input Lembar Penilaian Baru</h4>
                        <span style="font-size: 0.78rem; opacity: 0.85;">Pilih eskul dan kriteria penilaian siswa</span>
                    </div>
                </div>
                <button type="button" onclick="closeInputModal()" style="background: none; border: none; color: white; font-size: 1.25rem; cursor: pointer; padding: 4px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('grades.create') }}" method="GET" style="padding: 24px;">
                <!-- Tahun Ajaran -->
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        <i class="fas fa-calendar-alt"></i> Tahun Ajaran
                    </label>
                    <select name="academic_year_id" class="input-control-custom" required>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $yearId == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '— (Tahun Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilihan Eskul -->
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        <i class="fas fa-shapes"></i> Ekstrakurikuler
                    </label>
                    <select name="eskul_id" class="input-control-custom" required>
                        <option value="">-- Pilih Ekstrakurikuler --</option>
                        @foreach($eskuls as $eskul)
                            <option value="{{ $eskul->id }}">{{ $eskul->name }} ({{ $eskul->instructor_name ?: 'Belum ada pembina' }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Penilaian -->
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        <i class="fas fa-clipboard-list"></i> Jenis Penilaian
                    </label>
                    <select name="type" class="input-control-custom" id="modal-type-select" required onchange="toggleModalDate()">
                        <option value="daily">Nilai Harian / Per Pertemuan</option>
                        <option value="sas1">Nilai Akhir Semester 1 (SAS 1)</option>
                        <option value="sas2">Nilai Akhir Semester 2 (SAS 2)</option>
                    </select>
                </div>

                <!-- Tanggal Harian -->
                <div class="form-group-custom" id="modal-date-group">
                    <label class="form-label-custom">
                        <i class="far fa-calendar-check"></i> Tanggal Penilaian Harian
                    </label>
                    <input type="date" name="date" class="input-control-custom" value="{{ date('Y-m-d') }}">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; padding-top: 18px; border-top: 1px solid #e2e8f0;">
                    <button type="button" onclick="closeInputModal()" style="padding: 10px 18px; border-radius: 12px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #475569; font-weight: 700; font-size: 0.88rem; cursor: pointer;">
                        Batal
                    </button>
                    <button type="submit" style="padding: 10px 22px; border-radius: 12px; border: none; background: #10b981; color: white; font-weight: 800; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
                        <span>Buka Lembar Penilaian</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

@else
    {{-- =========================================================================
         TAMPILAN KHUSUS GURU / PEMBINA (FORM FOKUS INPUT)
         ========================================================================= --}}
    <div class="grades-select-wrapper">
        <div class="card grades-select-card">
            <!-- Header Kartu -->
            <div class="grades-select-header">
                <div class="grades-select-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="grades-select-title">
                    Input Penilaian Siswa
                </h3>
                <p class="grades-select-desc">
                    Pilih kriteria penilaian, eskul, dan periode untuk mulai menginput capaian siswa.
                </p>
            </div>

            <!-- Form Pemilihan Kriteria -->
            <form action="{{ route('grades.create') }}" method="GET" class="grades-select-body">
                <!-- Tahun Ajaran -->
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        <i class="fas fa-calendar-alt"></i> Tahun Ajaran
                    </label>
                    <select name="academic_year_id" class="custom-select-modern" data-theme="purple" data-icon="fas fa-calendar-alt" data-placeholder="Pilih Tahun Ajaran" required>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ ($activeYear && $activeYear->id == $year->id) ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '— (Tahun Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilihan Eskul -->
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        <i class="fas fa-shapes"></i> Ekstrakurikuler
                    </label>
                    <select name="eskul_id" class="custom-select-modern" id="eskul-select" data-theme="purple" data-icon="fas fa-shapes" data-placeholder="-- Pilih Kegiatan Ekstrakurikuler --" data-search-placeholder="Ketik nama eskul..." required>
                        <option value="">-- Pilih Kegiatan Ekstrakurikuler --</option>
                        @foreach($eskuls as $eskul)
                            <option value="{{ $eskul->id }}" data-badge="{{ $eskul->schedule ?: ($eskul->instructor_name ?: '') }}" {{ request('eskul_id') == $eskul->id ? 'selected' : '' }}>{{ $eskul->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Penilaian -->
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        <i class="fas fa-clipboard-list"></i> Jenis Penilaian
                    </label>
                    <select name="type" class="custom-select-modern" id="type-select" data-theme="purple" data-icon="fas fa-clipboard-list" data-placeholder="Pilih Jenis Penilaian" required onchange="toggleDate()">
                        <option value="daily">Nilai Harian / Per Pertemuan</option>
                        <option value="sas1">Nilai Akhir Semester 1 (SAS 1)</option>
                        <option value="sas2">Nilai Akhir Semester 2 (SAS 2)</option>
                    </select>
                </div>

                <!-- Tanggal Pertemuan (Hanya untuk Harian) -->
                <div class="form-group-custom" id="date-group" style="margin-bottom: 24px;">
                    <label class="form-label-custom">
                        <i class="far fa-calendar-check"></i> Tanggal Penilaian Harian
                    </label>
                    <input type="date" name="date" class="input-control-custom" value="{{ date('Y-m-d') }}">
                </div>

                <!-- Tombol Mulai Penilaian -->
                <button type="submit" class="btn-action-primary">
                    <span>Mulai Lembar Penilaian</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <!-- Bagian Rekap & Laporan Bawah -->
            <div class="grades-select-footer">
                <span class="footer-label" style="display: block; font-weight: 800; font-size: 0.95rem; color: #0f172a; margin-bottom: 4px;">
                    <i class="fas fa-chart-line" style="color: #db2777;"></i> Rekapitulasi Nilai
                </span>
                <p class="footer-desc" style="font-size: 0.8rem; color: #64748b; margin-bottom: 14px;">
                    Lihat akumulasi nilai siswa dan cetak lembar raport ekstrakurikuler
                </p>

                <form id="report-form" action="{{ route('grades.report') }}" method="GET">
                    <input type="hidden" name="eskul_id" id="report-eskul-id">
                    <input type="hidden" name="academic_year_id" id="report-year-id">
                    <input type="hidden" name="semester" id="report-semester">
                    <button type="button" onclick="submitReport()" class="btn-action-secondary">
                        <i class="fas fa-file-invoice"></i>
                        <span>Buka Rekapitulasi & Raport Nilai</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Tips Singkat -->
        <div style="margin-top: 16px; text-align: center;">
            <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #64748b; background: rgba(255, 255, 255, 0.8); padding: 6px 14px; border-radius: 20px; border: 1px solid #e2e8f0;">
                <i class="fas fa-lightbulb" style="color: #f59e0b;"></i>
                Penilaian harian dapat langsung diinput sesuai kehadiran siswa per pertemuan.
            </span>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
    // Modal Admin Controls
    function openInputModal() {
        const modal = document.getElementById('modalInputNilaiManual');
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function closeInputModal() {
        const modal = document.getElementById('modalInputNilaiManual');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Close modal on backdrop click
    document.getElementById('modalInputNilaiManual')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeInputModal();
        }
    });

    function toggleModalDate() {
        const type = document.getElementById('modal-type-select').value;
        const dateGroup = document.getElementById('modal-date-group');
        if (dateGroup) {
            dateGroup.style.display = (type === 'daily') ? 'block' : 'none';
        }
    }

    // Client-side search for Monitoring Table
    function filterEskulTable() {
        const input = document.getElementById('filterEskulInput');
        const query = input.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.eskul-row');

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const instructor = row.getAttribute('data-instructor') || '';
            if (name.includes(query) || instructor.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Teacher View functions
    function toggleDate() {
        var typeSelect = document.getElementById('type-select');
        var dateGroup = document.getElementById('date-group');
        if (typeSelect && dateGroup) {
            dateGroup.style.display = (typeSelect.value === 'daily') ? 'block' : 'none';
        }
    }

    // Sync report eskul id for teacher
    document.getElementById('eskul-select')?.addEventListener('change', function() {
        var reportEskul = document.getElementById('report-eskul-id');
        if (reportEskul) reportEskul.value = this.value;
    });

    function submitReport() {
        var eskulSelect = document.getElementById('eskul-select');
        if (!eskulSelect || !eskulSelect.value) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Ekstrakurikuler',
                    text: 'Silakan pilih eskul terlebih dahulu pada opsi di atas untuk melihat rekapitulasi nilai.',
                    confirmButtonColor: '#7c3aed',
                    confirmButtonText: 'Baik, Saya Pilih'
                });
            } else {
                alert('Silakan pilih eskul terlebih dahulu pada opsi di atas.');
            }
            return;
        }

        var yearSelect = document.querySelector('select[name="academic_year_id"]');
        var yearId = yearSelect ? yearSelect.value : '';
        document.getElementById('report-year-id').value = yearId;
        document.getElementById('report-eskul-id').value = eskulSelect.value;
        document.getElementById('report-form').submit();
    }
</script>
@endpush