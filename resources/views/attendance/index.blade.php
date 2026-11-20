@extends('layouts.app')

@section('title', 'Presensi Siswa')
@section('page-title', 'Presensi Ekstrakurikuler')

@push('styles')
<style>
    /* ========================================================
       A. STYLES KHUSUS ADMIN (CONTROL CENTER & SUPERVISI)
       ======================================================== */
    .admin-attendance-container {
        display: flex;
        flex-direction: column;
        gap: 24px;
        margin-bottom: 40px;
    }

    /* 1. Header Banner Emerald */
    .admin-hero-card {
        background: linear-gradient(135deg, #064e3b 0%, #047857 60%, #059669 100%);
        border-radius: 24px;
        padding: 26px 30px;
        color: white;
        box-shadow: 0 12px 30px -6px rgba(4, 120, 87, 0.25);
        border: 1.5px solid rgba(52, 211, 153, 0.35);
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
        color: #a7f3d0;
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
        color: #d1fae5;
        margin: 0;
        max-width: 650px;
        line-height: 1.5;
        opacity: 0.95;
    }
    .admin-hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn-hero-primary {
        background: #ffffff;
        color: #065f46;
        border: none;
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 0.86rem;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-hero-primary:hover {
        background: #f0fdf4;
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
        border-color: #10b981;
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
        border-color: #10b981;
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
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .btn-table-report:hover {
        background: #d1fae5;
        color: #065f46;
    }
    .btn-table-input {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .btn-table-input:hover {
        background: #dbeafe;
        color: #1e40af;
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
    .presensi-select-wrapper {
        max-width: 580px;
        margin: 1.5rem auto 3rem;
        padding: 0 12px;
    }
    .card.presensi-select-card {
        padding: 0 !important;
        border-radius: 24px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        background: #ffffff;
    }
    .presensi-select-header {
        background: linear-gradient(180deg, #ecfdf5 0%, #ffffff 100%);
        padding: 28px 24px 20px;
        border-bottom: 1px solid #f1f5f9;
        text-align: center;
    }
    .presensi-select-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: #dcfce7;
        color: #059669;
        border: 1.5px solid #bbf7d0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 14px;
    }
    .presensi-select-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 6px;
    }
    .presensi-select-desc {
        font-size: 0.88rem;
        color: #64748b;
        margin: 0;
    }
    .presensi-select-body {
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
        color: #059669;
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
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.3);
    }
    .presensi-select-footer {
        background: #f8fafc;
        padding: 20px 24px;
        border-top: 1px solid #f1f5f9;
        text-align: center;
    }
    .btn-action-secondary {
        width: 100%;
        padding: 11px 20px;
        background: #ffffff;
        color: #0284c7;
        border: 1.5px solid #bae6fd;
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
    <div class="admin-attendance-container">

        <!-- 1. Hero Header Banner Emerald -->
        <div class="admin-hero-card">
            <div class="admin-hero-top">
                <div>
                    <span class="admin-hero-badge">
                        <i class="fas fa-shield-alt"></i> Pusat Kontrol & Supervisi
                    </span>
                    <h1 class="admin-hero-title">Monitoring Presensi Siswa Ekstrakurikuler</h1>
                    <p class="admin-hero-subtitle">
                        Pantau kedisiplinan dan aktivitas presensi siswa dari seluruh kegiatan ekstrakurikuler SDIT An Nadzir secara real-time per pertemuan.
                    </p>
                </div>
                <div class="admin-hero-actions">
                    <button type="button" onclick="openInputModal()" class="btn-hero-primary">
                        <i class="fas fa-plus-circle" style="color: #059669;"></i>
                        <span>Input Presensi Baru</span>
                    </button>
                    <a href="{{ route('eskul.attendance-charts') }}" class="btn-hero-secondary">
                        <i class="fas fa-chart-bar" style="color: #34d399;"></i>
                        <span>Grafik & Rekapitulasi</span>
                    </a>
                </div>
            </div>
            <!-- Decor Icon -->
            <i class="fas fa-calendar-check" style="position: absolute; right: -15px; bottom: -20px; font-size: 8.5rem; color: rgba(255, 255, 255, 0.05); z-index: 1;"></i>
        </div>

        <!-- 2. Filter Bar (Tahun Ajaran & Semester) -->
        <div class="admin-filter-bar">
            <form id="filter-academic-form" action="{{ route('attendance.index') }}" method="GET" style="margin: 0; width: 100%; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div class="filter-group-inline">
                    <span class="filter-label-inline">
                        <i class="fas fa-calendar-alt" style="color: #059669;"></i> Tahun Ajaran:
                    </span>
                    <select name="academic_year_id" class="filter-select-inline" onchange="document.getElementById('filter-academic-form').submit()">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $yearId == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '— (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>

                    <span class="filter-label-inline" style="margin-left: 8px;">
                        <i class="fas fa-layer-group" style="color: #059669;"></i> Semester:
                    </span>
                    <select name="semester" class="filter-select-inline" onchange="document.getElementById('filter-academic-form').submit()">
                        <option value="1" {{ $selectedSemester == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                        <option value="2" {{ $selectedSemester == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                    </select>
                </div>

                <div style="font-size: 0.82rem; color: #64748b; font-weight: 600;">
                    <i class="fas fa-info-circle" style="color: #059669;"></i>
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

            <!-- Eskul Aktif Presensi -->
            <div class="admin-kpi-card">
                <div class="admin-kpi-icon" style="background: #dcfce7; color: #15803d;">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <div class="admin-kpi-num">{{ $kpi->active_attendance_count }} <span style="font-size: 0.9rem; font-weight: 500; color: #64748b;">/ {{ $kpi->total_eskul }}</span></div>
                    <div class="admin-kpi-lbl">Eskul Aktif Presensi</div>
                </div>
            </div>

            <!-- Total Sesi Pertemuan -->
            <div class="admin-kpi-card">
                <div class="admin-kpi-icon" style="background: #fef3c7; color: #b45309;">
                    <i class="far fa-calendar-check"></i>
                </div>
                <div>
                    <div class="admin-kpi-num">{{ $kpi->total_sessions_count }}</div>
                    <div class="admin-kpi-lbl">Sesi Pertemuan Tercatat</div>
                </div>
            </div>

            <!-- Total Rekaman Kehadiran Siswa -->
            <div class="admin-kpi-card">
                <div class="admin-kpi-icon" style="background: #ecfdf5; color: #059669;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <div class="admin-kpi-num">{{ number_format($kpi->total_attendances_count, 0, ',', '.') }}</div>
                    <div class="admin-kpi-lbl">Total Log Kehadiran</div>
                </div>
            </div>
        </div>

        <!-- 4. Tabel Monitoring Progres Presensi per Ekstrakurikuler -->
        <div class="admin-table-card">
            <div class="table-header-flex">
                <div>
                    <h3 class="table-title">
                        <i class="fas fa-tasks" style="color: #059669;"></i>
                        <span>Progres Presensi Siswa per Kegiatan Eskul</span>
                    </h3>
                    <p style="margin: 4px 0 0; font-size: 0.82rem; color: #64748b;">
                        Status keaktifan pengisian absensi siswa, total sesi pertemuan, serta tanggal pertemuan terakhir.
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
                            <th width="26%">Ekstrakurikuler</th>
                            <th width="20%">Pembina / Tutor</th>
                            <th width="10%">Siswa</th>
                            <th width="14%">Sesi Pertemuan</th>
                            <th width="14%">Presensi Terakhir</th>
                            <th width="12%" style="text-align: right;">Aksi</th>
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
                                @if($item->session_count > 0)
                                    <span style="color: #059669; font-weight: 800; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-check-circle"></i> {{ $item->session_count }} Pertemuan
                                    </span>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-minus-circle"></i> Belum ada
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($item->latest_date)
                                    <div style="font-weight: 700; font-size: 0.84rem; color: #0f172a;">
                                        <i class="far fa-calendar-alt" style="color: #059669;"></i>
                                        {{ \Carbon\Carbon::parse($item->latest_date)->isoFormat('D MMM Y') }}
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.78rem;">-</span>
                                @endif
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="{{ route('attendance.report', ['eskul_id' => $item->id, 'academic_year_id' => $yearId]) }}" class="btn-table-action btn-table-report" title="Buka Rekapitulasi Presensi">
                                        <i class="fas fa-file-invoice"></i> Rekap
                                    </a>
                                    <a href="{{ route('attendance.create', ['eskul_id' => $item->id, 'date' => date('Y-m-d')]) }}" class="btn-table-action btn-table-input" title="Buka Lembar Presensi">
                                        <i class="fas fa-edit"></i> Absen
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2.5rem; color: #94a3b8;">
                                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                                Belum ada data ekstrakurikuler terdaftar pada tahun ajaran ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 5. Riwayat Input Presensi Terbaru (Akses Cepat Revisi / Reset) -->
        <div class="admin-table-card">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 8px;">
                <div>
                    <h3 class="table-title">
                        <i class="fas fa-history" style="color: #0284c7;"></i>
                        <span>Riwayat Presensi Siswa Masuk Terbaru</span>
                    </h3>
                    <p style="margin: 4px 0 0; font-size: 0.82rem; color: #64748b;">
                        Sesi presensi yang baru saja diinput oleh pembina. Anda dapat membuka atau langsung mereset jika guru salah input.
                    </p>
                </div>
                <span style="font-size: 0.78rem; color: #64748b; font-weight: 700;">
                    8 Aktivitas Terakhir
                </span>
            </div>

            <div class="recent-sessions-grid">
                @forelse($recentAttendanceSessions as $session)
                    @php
                        $dateFormatted = $session->date
                            ? \Carbon\Carbon::parse($session->date)->isoFormat('dddd, D MMM Y')
                            : '-';
                    @endphp
                    <div class="session-card-item">
                        <div class="session-top">
                            <div>
                                <h4 class="session-title">{{ $session->eskul->name ?? 'Eskul' }}</h4>
                                <div class="session-meta">
                                    <span style="font-weight: 700; color: #047857;">{{ $dateFormatted }}</span>
                                </div>
                            </div>
                            <span class="badge-status complete" style="font-size: 0.72rem;">
                                {{ $session->student_count }} Siswa ({{ $session->present_count }} Hadir)
                            </span>
                        </div>
                        <div class="session-bottom">
                            <span style="font-size: 0.72rem; color: #94a3b8;">
                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($session->last_updated)->diffForHumans() }}
                            </span>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <a href="{{ route('attendance.create', ['eskul_id' => $session->eskul_id, 'date' => $session->date]) }}" style="font-size: 0.76rem; font-weight: 750; color: #0284c7; text-decoration: none; padding: 4px 8px; background: #f0f9ff; border-radius: 6px; border: 1px solid #bae6fd;">
                                    <i class="fas fa-eye"></i> Buka
                                </a>
                                <form action="{{ route('attendance.destroy-date') }}" method="POST" class="confirm-delete" data-confirm="Apakah Anda yakin ingin mengosongkan / mereset presensi {{ $session->eskul->name ?? 'Eskul' }} tanggal {{ $dateFormatted }}? Semua inputan absensi tanggal ini akan dihapus bersih agar guru dapat menginput ulang." style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="eskul_id" value="{{ $session->eskul_id }}">
                                    <input type="hidden" name="date" value="{{ $session->date }}">
                                    <button type="submit" style="background: #fee2e2; border: 1px solid #fecdd3; color: #ef4444; border-radius: 6px; padding: 4px 8px; font-size: 0.76rem; cursor: pointer;" title="Reset Presensi Tanggal Ini">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: #94a3b8; font-size: 0.85rem;">
                        Belum ada riwayat presensi tersimpan pada tahun ajaran ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL INPUT PRESENSI MANUAL ADMIN --}}
    <div id="modalInputPresensiManual" class="modal-backdrop-custom">
        <div class="modal-content-custom">
            <div style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%); padding: 22px 24px; color: white; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.15rem; font-weight: 800;">Input Lembar Presensi Baru</h4>
                        <span style="font-size: 0.78rem; opacity: 0.85;">Pilih eskul dan tanggal pertemuan siswa</span>
                    </div>
                </div>
                <button type="button" onclick="closeInputModal()" style="background: none; border: none; color: white; font-size: 1.25rem; cursor: pointer; padding: 4px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('attendance.create') }}" method="GET" style="padding: 24px;">
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

                <!-- Tanggal Pertemuan -->
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        <i class="far fa-calendar-check"></i> Tanggal Pertemuan
                    </label>
                    <input type="date" name="date" class="input-control-custom" value="{{ date('Y-m-d') }}" required>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; padding-top: 18px; border-top: 1px solid #e2e8f0;">
                    <button type="button" onclick="closeInputModal()" style="padding: 10px 18px; border-radius: 12px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #475569; font-weight: 700; font-size: 0.88rem; cursor: pointer;">
                        Batal
                    </button>
                    <button type="submit" style="padding: 10px 22px; border-radius: 12px; border: none; background: #10b981; color: white; font-weight: 800; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
                        <span>Buka Lembar Presensi</span>
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
    <div class="presensi-select-wrapper">
        <!-- Flash Success Message -->
        @if(session('success'))
            <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; color: #065f46; padding: 14px 18px; border-radius: 16px; margin-bottom: 18px; display: flex; align-items: center; gap: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.08);">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-check"></i>
                </div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Card Utama Pilih Kegiatan Presensi -->
        <div class="card presensi-select-card">
            
            <!-- Header Kartu Bernuansa Emerald Soft -->
            <div class="presensi-select-header">
                <div class="presensi-select-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3 class="presensi-select-title">
                    Input Presensi Siswa
                </h3>
                <p class="presensi-select-desc">
                    Pilih ekstrakurikuler dan tanggal pertemuan untuk mulai mengisi presensi kehadiran siswa.
                </p>
            </div>

            <!-- Form Pemilihan -->
            <form action="{{ route('attendance.create') }}" method="GET" class="presensi-select-body">
                <!-- Tahun Ajaran -->
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        <i class="fas fa-calendar-alt"></i> Tahun Ajaran
                    </label>
                    <select name="academic_year_id" class="custom-select-modern" data-theme="emerald" data-icon="fas fa-calendar-alt" data-placeholder="Pilih Tahun Ajaran" required>
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
                    <select name="eskul_id" class="custom-select-modern" data-theme="emerald" data-icon="fas fa-shapes" data-placeholder="-- Pilih Kegiatan Ekstrakurikuler --" data-search-placeholder="Ketik nama eskul..." required>
                        <option value="">-- Pilih Kegiatan Ekstrakurikuler --</option>
                        @foreach($eskuls as $eskul)
                            <option value="{{ $eskul->id }}" data-badge="{{ $eskul->schedule ?: ($eskul->instructor_name ?: '') }}">
                                {{ $eskul->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Kegiatan -->
                <div class="form-group-custom" style="margin-bottom: 24px;">
                    <label class="form-label-custom">
                        <i class="far fa-calendar-check"></i> Tanggal Pertemuan
                    </label>
                    <input type="date" name="date" class="input-control-custom" value="{{ date('Y-m-d') }}" required>
                </div>

                <!-- Tombol Lanjut ke Presensi -->
                <button type="submit" class="btn-action-primary">
                    <span>Lanjut ke Lembar Presensi</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <!-- Bagian Rekap & Laporan Bawah -->
            <div class="presensi-select-footer">
                <span class="footer-label" style="display: block; font-weight: 800; font-size: 0.95rem; color: #0f172a; margin-bottom: 4px;">
                    <i class="fas fa-chart-line" style="color: #0284c7;"></i> Rekapitulasi Presensi
                </span>
                <p class="footer-desc" style="font-size: 0.8rem; color: #64748b; margin-bottom: 14px;">
                    Lihat akumulasi kehadiran siswa seluruh pertemuan dan cetak berkas laporan
                </p>

                <form id="report-form" action="{{ route('attendance.report') }}" method="GET">
                    <input type="hidden" name="academic_year_id" id="report-year-id">
                    <input type="hidden" name="eskul_id" id="report-eskul-id">
                    <button type="button" onclick="submitReport()" class="btn-action-secondary">
                        <i class="fas fa-file-invoice"></i>
                        <span>Buka Rekapitulasi & Cetak Laporan</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Tips Singkat -->
        <div style="margin-top: 16px; text-align: center;">
            <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #64748b; background: rgba(255, 255, 255, 0.8); padding: 6px 14px; border-radius: 20px; border: 1px solid #e2e8f0;">
                <i class="fas fa-lightbulb" style="color: #f59e0b;"></i>
                Presensi dapat diinput fleksibel melalui HP atau Laptop secara instan.
            </span>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
    // Modal Admin Controls
    function openInputModal() {
        const modal = document.getElementById('modalInputPresensiManual');
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function closeInputModal() {
        const modal = document.getElementById('modalInputPresensiManual');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Close modal on backdrop click
    document.getElementById('modalInputPresensiManual')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeInputModal();
        }
    });

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
    document.querySelector('select[name="eskul_id"]')?.addEventListener('change', function() {
        var reportEskul = document.getElementById('report-eskul-id');
        if (reportEskul) reportEskul.value = this.value;
    });

    function submitReport() {
        var mainYear = document.querySelector('select[name="academic_year_id"]')?.value;
        var mainEskul = document.querySelector('select[name="eskul_id"]')?.value;

        if (!mainEskul) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Ekstrakurikuler',
                    text: 'Silakan pilih eskul terlebih dahulu pada opsi di atas untuk melihat rekapitulasi.',
                    confirmButtonColor: '#059669',
                    confirmButtonText: 'Baik, Saya Pilih'
                });
            } else {
                alert('Silakan pilih eskul terlebih dahulu pada opsi di atas.');
            }
            return;
        }

        document.getElementById('report-year-id').value = mainYear;
        document.getElementById('report-eskul-id').value = mainEskul;
        document.getElementById('report-form').submit();
    }
</script>
@endpush