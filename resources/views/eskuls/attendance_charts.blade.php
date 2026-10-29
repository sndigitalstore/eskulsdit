@extends('layouts.app')

@section('title', 'Grafik & Rekap Presensi Eskul')
@section('page-title', 'Grafik & Rekapitulasi Presensi Ekstrakurikuler')

@push('styles')
<style>
    .charts-page {
        max-width: 1300px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 22px;
        padding-bottom: 50px;
    }

    /* 1. HERO BANNER */
    .charts-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #064e3b 100%);
        color: white;
        padding: 26px 30px;
        border-radius: 20px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        position: relative;
        overflow: hidden;
    }

    .charts-hero::after {
        content: '\f201';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: -15px;
        bottom: -25px;
        font-size: 11rem;
        color: rgba(255, 255, 255, 0.03);
        pointer-events: none;
    }

    .charts-hero-left {
        display: flex;
        align-items: center;
        gap: 18px;
        z-index: 1;
    }

    .charts-hero-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        box-shadow: 0 8px 18px rgba(16, 185, 129, 0.35);
        flex-shrink: 0;
    }

    .charts-hero-text h1 {
        font-size: 1.55rem;
        font-weight: 800;
        margin: 0 0 6px 0;
        letter-spacing: -0.3px;
        color: #f8fafc;
    }

    .charts-hero-text p {
        font-size: 0.88rem;
        color: #94a3b8;
        margin: 0;
        line-height: 1.45;
    }

    .charts-hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        z-index: 1;
    }

    .btn-hero {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 0.86rem;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-hero-print {
        background: #0284c7;
        color: white;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
    }

    .btn-hero-print:hover {
        background: #0369a1;
        transform: translateY(-1px);
        color: white;
    }

    .btn-hero-light {
        background: rgba(255, 255, 255, 0.12);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-hero-light:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* 2. MODE SELECTOR TABS */
    .mode-tabs-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        background: #ffffff;
        padding: 8px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .mode-tab-btn {
        flex: 1;
        min-width: 140px;
        padding: 11px 18px;
        border-radius: 12px;
        font-size: 0.88rem;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        border: 1.5px solid transparent;
        color: #64748b;
        background: transparent;
        cursor: pointer;
    }

    .mode-tab-btn:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .mode-tab-btn.active {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    /* 3. FILTER CARD */
    .filter-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 20px 24px;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) auto;
        gap: 14px;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-label {
        font-size: 0.78rem;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-input {
        width: 100%;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 9px 12px;
        font-size: 0.86rem;
        font-weight: 600;
        color: #1e293b;
        background: #f8fafc;
        box-sizing: border-box;
        outline: none;
        transition: all 0.2s;
    }

    .filter-input:focus {
        border-color: #059669;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
    }

    .filter-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .btn-filter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 0.86rem;
        font-weight: 800;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        min-height: 40px;
        box-sizing: border-box;
        text-decoration: none;
    }

    .btn-filter-apply {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.25);
    }

    .btn-filter-apply:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        color: white;
    }

    .btn-filter-reset {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    .btn-filter-reset:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* 4. KPI CARDS */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 16px;
    }

    .kpi-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 20px 22px;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        gap: 12px;
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07);
    }

    .kpi-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .kpi-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .kpi-tag {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 6px;
        letter-spacing: 0.03em;
    }

    .kpi-value-row {
        display: flex;
        align-items: baseline;
        gap: 10px;
    }

    .kpi-main-val {
        font-size: 2.1rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
    }

    .kpi-sub-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
    }

    .kpi-pills-row {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        padding-top: 8px;
        border-top: 1px dashed #e2e8f0;
    }

    .kpi-mini-pill {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* 5. CHARTS GRID */
    .charts-row-main {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 18px;
    }

    .chart-box {
        background: #ffffff;
        border-radius: 18px;
        padding: 22px 24px;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .chart-header h3 {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-badge {
        font-size: 0.74rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #475569;
    }

    .chart-canvas-wrap {
        position: relative;
        width: 100%;
        min-height: 280px;
    }

    .doughnut-dual-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    /* 6. TABLE CARD */
    .table-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .table-card-header {
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-card-header h3 {
        font-size: 1.08rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .recap-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .recap-table th {
        background: #f8fafc;
        padding: 13px 18px;
        font-size: 0.78rem;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .recap-table td {
        padding: 14px 18px;
        font-size: 0.88rem;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .recap-table tr:hover {
        background: #fafcff;
    }

    .badge-predikat {
        font-size: 0.74rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .rate-bar-bg {
        width: 100%;
        height: 6px;
        background: #e2e8f0;
        border-radius: 999px;
        overflow: hidden;
        margin-top: 4px;
    }

    .rate-bar-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 0.3s ease;
    }

    /* Print Styles */
    @media print {
        body {
            background: white !important;
            padding: 0 !important;
        }
        .no-print, .charts-hero-actions, .mode-tabs-wrap, .filter-card, .sidebar, .main-header, .navbar {
            display: none !important;
        }
        .charts-page {
            max-width: 100% !important;
            padding: 0 !important;
            gap: 15px !important;
        }
        .charts-hero {
            background: #0f172a !important;
            color: white !important;
            padding: 16px 20px !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .chart-box, .table-card, .kpi-card {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            page-break-inside: avoid;
        }
        .charts-row-main {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 992px) {
        .charts-row-main {
            grid-template-columns: 1fr;
        }
        .doughnut-dual-grid {
            grid-template-columns: 1fr;
        }
        .filter-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="charts-page">

    {{-- 1. HERO BANNER --}}
    <div class="charts-hero">
        <div class="charts-hero-left">
            <div class="charts-hero-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="charts-hero-text">
                <h1>Grafik & Rekap Presensi Ekstrakurikuler</h1>
                <p>
                    Visualisasi analitik kehadiran pembina & siswa eskul berbasis tanggal, bulanan, semester, dan tahunan.<br>
                    Tahun Ajaran: <strong>{{ $contextYear ? $contextYear->name : '-' }}</strong> &bull; 
                    Ekstrakurikuler: <strong>{{ $selectedEskul ? $selectedEskul->name : 'Seluruh Eskul' }}</strong>
                </p>
            </div>
        </div>

        <div class="charts-hero-actions no-print">
            <button onclick="window.print()" class="btn-hero btn-hero-print" title="Cetak Rekapan & Grafik">
                <i class="fas fa-print"></i> <span>Cetak Laporan</span>
            </button>
            <a href="{{ route('eskul.attendance-charts') }}" class="btn-hero btn-hero-light" title="Segarkan Data">
                <i class="fas fa-sync-alt"></i> <span>Reset Filter</span>
            </a>
        </div>
    </div>

    {{-- 2. MODE SELECTOR TABS --}}
    <div class="mode-tabs-wrap no-print">
        <a href="{{ route('eskul.attendance-charts', array_merge(request()->query(), ['mode' => 'date'])) }}" 
           class="mode-tab-btn {{ $periodMode === 'date' ? 'active' : '' }}">
            <i class="fas fa-calendar-day"></i>
            <span>Rentang Tanggal</span>
        </a>
        <a href="{{ route('eskul.attendance-charts', array_merge(request()->query(), ['mode' => 'month'])) }}" 
           class="mode-tab-btn {{ $periodMode === 'month' ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i>
            <span>Rekap Bulanan</span>
        </a>
        <a href="{{ route('eskul.attendance-charts', array_merge(request()->query(), ['mode' => 'semester'])) }}" 
           class="mode-tab-btn {{ $periodMode === 'semester' ? 'active' : '' }}">
            <i class="fas fa-layer-group"></i>
            <span>Rekap Semester</span>
        </a>
        <a href="{{ route('eskul.attendance-charts', array_merge(request()->query(), ['mode' => 'year'])) }}" 
           class="mode-tab-btn {{ $periodMode === 'year' ? 'active' : '' }}">
            <i class="fas fa-history"></i>
            <span>Rekap Tahunan</span>
        </a>
    </div>

    {{-- 3. FILTER CARD --}}
    <div class="filter-card no-print">
        <form method="GET" action="{{ route('eskul.attendance-charts') }}" id="filterChartsForm">
            <input type="hidden" name="mode" value="{{ $periodMode }}">

            <div class="filter-grid">
                {{-- Dropdown Pilih Ekstrakurikuler --}}
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-basketball-ball" style="color: #f59e0b;"></i> Ekstrakurikuler
                    </label>
                    <select name="eskul_id" class="filter-input" onchange="document.getElementById('filterChartsForm').submit()">
                        <option value="all" {{ $selectedEskulId === 'all' ? 'selected' : '' }}>-- Seluruh Ekstrakurikuler --</option>
                        @foreach($eskuls as $esk)
                            <option value="{{ $esk->id }}" {{ (string)$selectedEskulId === (string)$esk->id ? 'selected' : '' }}>
                                {{ $esk->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Dropdown Pilih Tahun Ajaran --}}
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-graduation-cap" style="color: #0284c7;"></i> Tahun Ajaran
                    </label>
                    <select name="academic_year_id" class="filter-input" onchange="document.getElementById('filterChartsForm').submit()">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ (string)$selectedYearId === (string)$ay->id ? 'selected' : '' }}>
                                {{ $ay->name }} {{ $ay->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- DYNAMIC CONTROLS SESUAI MODE --}}
                @if($periodMode === 'date')
                    {{-- Mode Tanggal: Dari Tanggal s/d Sampai Tanggal --}}
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="far fa-calendar-alt" style="color: #10b981;"></i> Dari Tanggal
                        </label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="filter-input">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="far fa-calendar-check" style="color: #10b981;"></i> Sampai Tanggal
                        </label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="filter-input">
                    </div>
                @elseif($periodMode === 'month')
                    {{-- Mode Bulan: Pilih Bulan Spesifik --}}
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-calendar-week" style="color: #10b981;"></i> Sorot Bulan
                        </label>
                        <input type="month" name="month" value="{{ $selectedMonth }}" class="filter-input" onchange="document.getElementById('filterChartsForm').submit()">
                    </div>
                @elseif($periodMode === 'semester')
                    {{-- Mode Semester: Pilih Semester --}}
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-adjust" style="color: #8b5cf6;"></i> Semester
                        </label>
                        <select name="semester" class="filter-input" onchange="document.getElementById('filterChartsForm').submit()">
                            <option value="all" {{ $selectedSemester === 'all' ? 'selected' : '' }}>Semua Semester (1 & 2)</option>
                            <option value="1" {{ $selectedSemester === '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                            <option value="2" {{ $selectedSemester === '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                        </select>
                    </div>
                @endif

                {{-- Tombol Terapkan & Reset --}}
                <div class="filter-buttons">
                    <button type="submit" class="btn-filter btn-filter-apply" title="Terapkan Filter">
                        <i class="fas fa-filter"></i> <span>Terapkan</span>
                    </button>
                    <a href="{{ route('eskul.attendance-charts', ['mode' => $periodMode]) }}" class="btn-filter btn-filter-reset" title="Kembalikan Filter Default">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- 4. KPI SUMMARY CARDS --}}
    <div class="kpi-grid">
        {{-- Card 1: Kehadiran Pembina --}}
        <div class="kpi-card" style="border-left: 4px solid #10b981;">
            <div class="kpi-card-top">
                <span class="kpi-tag" style="background: #ecfdf5; color: #047857;">Pembina Eskul</span>
                <div class="kpi-icon-box" style="background: #d1fae5; color: #059669;">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>
            <div class="kpi-value-row">
                <span class="kpi-main-val" style="color: #047857;">{{ $coachRate }}%</span>
                <span class="kpi-sub-label">Tingkat Hadir</span>
            </div>
            <div style="font-size: 0.82rem; color: #64748b; font-weight: 600;">
                {{ $coachEffectivePresent }} dari {{ $coachTotal }} total pertemuan pembina tercatat.
            </div>
            <div class="kpi-pills-row">
                <span class="kpi-mini-pill" style="background: #ecfdf5; color: #059669;">
                    <i class="fas fa-check"></i> Hadir: {{ $coachPresentOnly }}
                </span>
                <span class="kpi-mini-pill" style="background: #f5f3ff; color: #7c3aed;">
                    <i class="fas fa-exchange-alt"></i> Pengganti: {{ $coachSubstituted }}
                </span>
                <span class="kpi-mini-pill" style="background: #fef2f2; color: #dc2626;">
                    <i class="fas fa-envelope-open-text"></i> Izin/Sakit: {{ $coachPermission + $coachSick }}
                </span>
            </div>
        </div>

        {{-- Card 2: Kehadiran Siswa --}}
        <div class="kpi-card" style="border-left: 4px solid #0284c7;">
            <div class="kpi-card-top">
                <span class="kpi-tag" style="background: #e0f2fe; color: #0369a1;">Siswa Eskul</span>
                <div class="kpi-icon-box" style="background: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
            <div class="kpi-value-row">
                <span class="kpi-main-val" style="color: #0369a1;">{{ $studentRate }}%</span>
                <span class="kpi-sub-label">Tingkat Hadir</span>
            </div>
            <div style="font-size: 0.82rem; color: #64748b; font-weight: 600;">
                {{ number_format($studentPresent) }} siswa hadir dari {{ number_format($studentTotal) }} data presensi.
            </div>
            <div class="kpi-pills-row">
                <span class="kpi-mini-pill" style="background: #ecfdf5; color: #059669;">
                    <i class="fas fa-check-circle"></i> H: {{ $studentPresent }}
                </span>
                <span class="kpi-mini-pill" style="background: #fef3c7; color: #b45309;">
                    <i class="fas fa-file-medical"></i> S: {{ $studentSick }}
                </span>
                <span class="kpi-mini-pill" style="background: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-envelope"></i> I: {{ $studentPermission }}
                </span>
                <span class="kpi-mini-pill" style="background: #fef2f2; color: #dc2626;">
                    <i class="fas fa-times-circle"></i> A: {{ $studentAbsent }}
                </span>
            </div>
        </div>

        {{-- Card 3: Total Sesi Terlaksana --}}
        <div class="kpi-card" style="border-left: 4px solid #f59e0b;">
            <div class="kpi-card-top">
                <span class="kpi-tag" style="background: #fef3c7; color: #b45309;">Pertemuan</span>
                <div class="kpi-icon-box" style="background: #fef3c7; color: #d97706;">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="kpi-value-row">
                <span class="kpi-main-val" style="color: #d97706;">{{ $totalSessions }}</span>
                <span class="kpi-sub-label">Sesi Selesai</span>
            </div>
            <div style="font-size: 0.82rem; color: #64748b; font-weight: 600;">
                Pertemuan terlaksana pada rentang waktu yang difilter.
            </div>
            <div class="kpi-pills-row">
                <span class="kpi-mini-pill" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;">
                    <i class="fas fa-shapes"></i> {{ $selectedEskul ? $selectedEskul->name : $eskuls->count() . ' Cabang Eskul' }}
                </span>
            </div>
        </div>

        {{-- Card 4: Eskul Paling Aktif --}}
        <div class="kpi-card" style="border-left: 4px solid #8b5cf6;">
            <div class="kpi-card-top">
                <span class="kpi-tag" style="background: #f5f3ff; color: #6d28d9;">Performa Tertinggi</span>
                <div class="kpi-icon-box" style="background: #ede9fe; color: #7c3aed;">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
            <div class="kpi-value-row">
                <span class="kpi-main-val" style="font-size: 1.4rem; color: #6d28d9; line-height: 1.2;">
                    {{ $bestEskul ? \Illuminate\Support\Str::limit($bestEskul->name, 18) : '-' }}
                </span>
            </div>
            <div style="font-size: 0.82rem; color: #64748b; font-weight: 600;">
                Skor Keaktifan: <strong>{{ $bestEskul ? $bestEskul->composite_score : 0 }}%</strong>
            </div>
            <div class="kpi-pills-row">
                @if($bestEskul)
                <span class="kpi-mini-pill" style="background: #ecfdf5; color: #047857;">
                    <i class="fas fa-award"></i> {{ $bestEskul->predikat }}
                </span>
                <span class="kpi-mini-pill" style="background: #f8fafc; color: #64748b;">
                    Siswa: {{ $bestEskul->student_rate }}% &bull; Pembina: {{ $bestEskul->coach_rate }}%
                </span>
                @else
                <span class="kpi-mini-pill" style="background: #f8fafc; color: #94a3b8;">Belum ada data</span>
                @endif
            </div>
        </div>
    </div>

    {{-- 5. CHARTS ROW 1: TREN UTAMA & KOMPOSISI STATUS --}}
    <div class="charts-row-main">
        {{-- Grafik Tren Utama --}}
        <div class="chart-box">
            <div class="chart-header">
                <h3>
                    <i class="fas fa-chart-area" style="color: #10b981;"></i>
                    @if($periodMode === 'date')
                        Tren Kehadiran Harian (Rentang Tanggal)
                    @elseif($periodMode === 'month')
                        Tren Kehadiran Bulanan Sepanjang Tahun Ajaran
                    @elseif($periodMode === 'semester')
                        Perbandingan Kehadiran Antar Semester
                    @elseif($periodMode === 'year')
                        Perbandingan Kehadiran Antar Tahun Ajaran
                    @endif
                </h3>
                <span class="chart-badge">
                    <i class="fas fa-eye"></i> Pembina vs Siswa (%)
                </span>
            </div>
            <div class="chart-canvas-wrap">
                <canvas id="mainTrendChart"></canvas>
            </div>
        </div>

        {{-- Grafik Komposisi Doughnut --}}
        <div class="chart-box">
            <div class="chart-header">
                <h3>
                    <i class="fas fa-chart-pie" style="color: #0284c7;"></i>
                    Distribusi Status
                </h3>
                <span class="chart-badge">Pembina & Siswa</span>
            </div>
            
            <div class="doughnut-dual-grid">
                {{-- Doughnut Pembina --}}
                <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div style="font-size: 0.8rem; font-weight: 800; color: #047857; margin-bottom: 8px;">
                        <i class="fas fa-user-tie"></i> Pembina
                    </div>
                    <div style="position: relative; width: 140px; height: 140px;">
                        <canvas id="coachDoughnutChart"></canvas>
                    </div>
                    <div style="font-size: 0.74rem; color: #64748b; margin-top: 8px; font-weight: 600;">
                        {{ $coachTotal }} Sesi Pembina
                    </div>
                </div>

                {{-- Doughnut Siswa --}}
                <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div style="font-size: 0.8rem; font-weight: 800; color: #0284c7; margin-bottom: 8px;">
                        <i class="fas fa-user-graduate"></i> Siswa
                    </div>
                    <div style="position: relative; width: 140px; height: 140px;">
                        <canvas id="studentDoughnutChart"></canvas>
                    </div>
                    <div style="font-size: 0.74rem; color: #64748b; margin-top: 8px; font-weight: 600;">
                        {{ $studentTotal }} Presensi Siswa
                    </div>
                </div>
            </div>

            {{-- Legend Keterangan --}}
            <div style="padding-top: 10px; border-top: 1px dashed #e2e8f0; display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; font-size: 0.74rem; font-weight: 700;">
                <span style="color: #059669;"><i class="fas fa-circle" style="color: #10b981;"></i> Hadir</span>
                <span style="color: #7c3aed;"><i class="fas fa-circle" style="color: #8b5cf6;"></i> Pengganti</span>
                <span style="color: #0284c7;"><i class="fas fa-circle" style="color: #0284c7;"></i> Izin</span>
                <span style="color: #d97706;"><i class="fas fa-circle" style="color: #f59e0b;"></i> Sakit</span>
                <span style="color: #dc2626;"><i class="fas fa-circle" style="color: #ef4444;"></i> Alpa</span>
            </div>
        </div>
    </div>

    {{-- 6. CHARTS ROW 2: KOMPARASI KEHADIRAN ANTAR EKSTRAKURIKULER --}}
    @if($eskuls->count() > 1 && $selectedEskulId === 'all')
    <div class="chart-box">
        <div class="chart-header">
            <h3>
                <i class="fas fa-chart-bar" style="color: #8b5cf6;"></i>
                Komparasi Tingkat Kehadiran per Cabang Ekstrakurikuler
            </h3>
            <span class="chart-badge">Siswa vs Pembina (%)</span>
        </div>
        <div class="chart-canvas-wrap" style="min-height: {{ max(300, $eskuls->count() * 38) }}px;">
            <canvas id="eskulComparisonChart"></canvas>
        </div>
    </div>
    @endif

    {{-- 7. TABEL REKAPITULASI DETAIL PER ESKUL --}}
    <div class="table-card">
        <div class="table-card-header">
            <h3>
                <i class="fas fa-table" style="color: #059669;"></i>
                Tabel Rekapitulasi Presensi Ekstrakurikuler
            </h3>
            <span style="font-size: 0.8rem; font-weight: 700; color: #64748b;">
                Menampilkan {{ $eskulBreakdown->count() }} Ekstrakurikuler
            </span>
        </div>

        <div class="table-responsive">
            <table class="recap-table">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th width="22%">Ekstrakurikuler & Jadwal</th>
                        <th width="16%">Pembina</th>
                        <th width="8%" style="text-align: center;">Sesi</th>
                        <th width="18%">Presensi Pembina</th>
                        <th width="20%">Presensi Siswa</th>
                        <th width="12%" style="text-align: center;">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eskulBreakdown as $idx => $item)
                    <tr>
                        <td align="center" style="font-weight: 700; color: #94a3b8;">{{ $idx + 1 }}</td>
                        <td>
                            <div style="font-weight: 800; color: #0f172a; font-size: 0.92rem;">
                                {{ $item->name }}
                            </div>
                            <div style="font-size: 0.76rem; color: #64748b; margin-top: 2px;">
                                <i class="far fa-clock"></i> {{ $item->schedule }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #1e293b;">
                                {{ $item->coach_name }}
                            </div>
                        </td>
                        <td align="center">
                            <span style="font-weight: 800; font-size: 0.95rem; color: #0f172a;">
                                {{ $item->sessions_count }}
                            </span>
                            <div style="font-size: 0.72rem; color: #94a3b8;">pertemuan</div>
                        </td>
                        <td>
                            <div style="display: flex; justify-content: space-between; align-items: baseline; font-size: 0.82rem;">
                                <span style="font-weight: 800; color: {{ $item->coach_rate >= 80 ? '#047857' : ($item->coach_rate >= 60 ? '#d97706' : '#dc2626') }};">
                                    {{ $item->coach_rate }}%
                                </span>
                                <span style="font-size: 0.74rem; color: #64748b;">
                                    H: {{ $item->coach_present + $item->coach_substituted }}/{{ $item->coach_total }}
                                </span>
                            </div>
                            <div class="rate-bar-bg">
                                <div class="rate-bar-fill" style="width: {{ $item->coach_rate }}%; background: linear-gradient(90deg, #10b981 0%, #059669 100%);"></div>
                            </div>
                            <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 3px;">
                                Hadir: {{ $item->coach_present }} &bull; Pengganti: {{ $item->coach_substituted }} &bull; I/S: {{ $item->coach_permission + $item->coach_sick }}
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; justify-content: space-between; align-items: baseline; font-size: 0.82rem;">
                                <span style="font-weight: 800; color: {{ $item->student_rate >= 80 ? '#0369a1' : ($item->student_rate >= 60 ? '#d97706' : '#dc2626') }};">
                                    {{ $item->student_rate }}%
                                </span>
                                <span style="font-size: 0.74rem; color: #64748b;">
                                    {{ $item->student_present }}/{{ $item->student_total }}
                                </span>
                            </div>
                            <div class="rate-bar-bg">
                                <div class="rate-bar-fill" style="width: {{ $item->student_rate }}%; background: linear-gradient(90deg, #0284c7 0%, #0369a1 100%);"></div>
                            </div>
                            <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 3px;">
                                H: {{ $item->student_present }} &bull; S: {{ $item->student_sick }} &bull; I: {{ $item->student_permission }} &bull; A: {{ $item->student_absent }}
                            </div>
                        </td>
                        <td align="center">
                            <span class="badge-predikat" style="background: {{ $item->badge_color }}15; color: {{ $item->badge_color }}; border: 1px solid {{ $item->badge_color }}35;">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i> {{ $item->predikat }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" align="center" style="padding: 30px; color: #94a3b8;">
                            <i class="fas fa-info-circle" style="font-size: 1.5rem; margin-bottom: 6px; display: block;"></i>
                            Belum ada catatan presensi pada filter ekstrakurikuler & periode waktu ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. DATASETS DARI BACKEND
    const mainLabels = {!! json_encode($mainChartLabels) !!};
    const coachRateData = {!! json_encode($mainChartCoachRate) !!};
    const studentRateData = {!! json_encode($mainChartStudentRate) !!};

    // --- CHART 1: MAIN TREND CHART ---
    const ctxMain = document.getElementById('mainTrendChart');
    if (ctxMain) {
        new Chart(ctxMain.getContext('2d'), {
            type: 'line',
            data: {
                labels: mainLabels,
                datasets: [
                    {
                        label: '% Kehadiran Pembina',
                        data: coachRateData,
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.12)',
                        borderWidth: 3,
                        pointBackgroundColor: '#059669',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: true
                    },
                    {
                        label: '% Kehadiran Siswa',
                        data: studentRateData,
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(2, 132, 199, 0.10)',
                        borderWidth: 3,
                        pointBackgroundColor: '#0284c7',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function (val) { return val + '%'; },
                            font: { family: 'Nunito', weight: '700' }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { family: 'Nunito', weight: '600' } },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: 'Nunito', weight: '700', size: 12 },
                            usePointStyle: true,
                            padding: 14
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ' ' + ctx.dataset.label + ': ' + (ctx.raw !== null ? ctx.raw + '%' : '-');
                            }
                        }
                    }
                }
            }
        });
    }

    // --- CHART 2: DOUGHNUT PEMBINA ---
    const ctxCoach = document.getElementById('coachDoughnutChart');
    if (ctxCoach) {
        new Chart(ctxCoach.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Hadir Mandiri', 'Digantikan', 'Izin', 'Sakit'],
                datasets: [{
                    data: [
                        {{ $coachPresentOnly }},
                        {{ $coachSubstituted }},
                        {{ $coachPermission }},
                        {{ $coachSick }}
                    ],
                    backgroundColor: ['#10b981', '#8b5cf6', '#0284c7', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ' ' + ctx.label + ': ' + ctx.raw + ' Sesi';
                            }
                        }
                    }
                }
            }
        });
    }

    // --- CHART 3: DOUGHNUT SISWA ---
    const ctxStudent = document.getElementById('studentDoughnutChart');
    if (ctxStudent) {
        new Chart(ctxStudent.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alpa'],
                datasets: [{
                    data: [
                        {{ $studentPresent }},
                        {{ $studentSick }},
                        {{ $studentPermission }},
                        {{ $studentAbsent }}
                    ],
                    backgroundColor: ['#10b981', '#f59e0b', '#0284c7', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ' ' + ctx.label + ': ' + ctx.raw + ' Siswa';
                            }
                        }
                    }
                }
            }
        });
    }

    // --- CHART 4: ESKUL COMPARISON HORIZONTAL BAR ---
    const ctxComp = document.getElementById('eskulComparisonChart');
    if (ctxComp) {
        const compLabels = {!! json_encode($eskulComparisonLabels) !!};
        const compStudentData = {!! json_encode($eskulComparisonStudentRate) !!};
        const compCoachData = {!! json_encode($eskulComparisonCoachRate) !!};

        new Chart(ctxComp.getContext('2d'), {
            type: 'bar',
            data: {
                labels: compLabels,
                datasets: [
                    {
                        label: '% Kehadiran Siswa',
                        data: compStudentData,
                        backgroundColor: '#0284c7',
                        borderRadius: 6,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    },
                    {
                        label: '% Kehadiran Pembina',
                        data: compCoachData,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(val) { return val + '%'; },
                            font: { family: 'Nunito', weight: '700' }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    y: {
                        ticks: { font: { family: 'Nunito', weight: '700', size: 12 } },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: 'Nunito', weight: '700' },
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ' ' + ctx.dataset.label + ': ' + ctx.raw + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    @if(!empty($isPrint))
    setTimeout(function() {
        window.print();
    }, 800);
    @endif
});
</script>
@endpush
