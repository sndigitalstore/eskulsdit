@extends('layouts.app')

@section('title', 'Grafik & Rekap Presensi & Nilai Eskul')
@section('page-title', 'Grafik & Rekapitulasi Presensi & Nilai Ekstrakurikuler')

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

    /* 5b. SECTION DIVIDER & GRADE CHARTS */
    .section-divider-banner {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 16px 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }
    .section-divider-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .section-divider-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        flex-shrink: 0;
    }
    .section-divider-left h2 {
        font-size: 1.18rem;
        font-weight: 850;
        color: #0f172a;
        margin: 0 0 3px 0;
        letter-spacing: -0.2px;
    }
    .section-divider-left p {
        font-size: 0.82rem;
        color: #64748b;
        margin: 0;
    }
    .badge-pill-purple {
        background: #f5f3ff;
        color: #7c3aed;
        border: 1px solid #ddd6fe;
        font-size: 0.78rem;
        font-weight: 800;
        padding: 6px 14px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .grade-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
        gap: 16px;
    }
    .grade-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 20px 22px;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        gap: 14px;
        transition: all 0.2s ease;
    }
    .grade-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07);
    }
    .grade-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .grade-badge-tag {
        font-size: 0.74rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 8px;
        letter-spacing: 0.03em;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .grade-donut-row {
        display: flex;
        align-items: center;
        gap: 18px;
    }
    .grade-donut-canvas-box {
        position: relative;
        width: 105px;
        height: 105px;
        flex-shrink: 0;
    }
    .grade-donut-center-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.15rem;
        font-weight: 900;
        color: #0f172a;
        pointer-events: none;
        text-align: center;
        line-height: 1;
    }
    .grade-donut-center-sub {
        font-size: 0.62rem;
        font-weight: 700;
        color: #64748b;
        display: block;
        margin-top: 2px;
    }
    .grade-predikat-bars {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .grade-pred-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.76rem;
        font-weight: 750;
    }
    .grade-pred-bar-bg {
        width: 100%;
        height: 5px;
        background: #f1f5f9;
        border-radius: 999px;
        overflow: hidden;
        margin-top: 2px;
    }
    .grade-pred-bar-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 0.3s ease;
    }
    .grade-charts-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
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
    .print-only {
        display: none;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm 10mm 12mm 10mm;
        }

        body {
            background: #ffffff !important;
            color: #000000 !important;
            padding: 0 !important;
            margin: 0 !important;
            font-size: 10pt !important;
        }

        .no-print, 
        .charts-hero, 
        .charts-hero-actions, 
        .mode-tabs-wrap, 
        .filter-card, 
        .sidebar, 
        .header, 
        .main-header, 
        .navbar,
        footer {
            display: none !important;
        }

        .print-only {
            display: block !important;
        }

        .charts-page {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            gap: 16px !important;
        }

        .print-doc-header {
            text-align: center;
            border-bottom: 2.5px solid #059669;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .print-doc-header h2 {
            font-size: 13pt;
            font-weight: 800;
            color: #064e3b;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .print-doc-header p {
            font-size: 9pt;
            color: #334155;
            margin: 0 0 3px 0;
            line-height: 1.4;
        }

        .print-meta {
            font-size: 7.8pt;
            color: #64748b;
            font-style: italic;
        }

        /* KPI Cards in Print */
        .kpi-grid {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 10px !important;
            margin-bottom: 14px !important;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .kpi-card {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 10px !important;
            padding: 10px 12px !important;
            gap: 6px !important;
            page-break-inside: avoid;
            break-inside: avoid;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .kpi-icon-box {
            width: 32px !important;
            height: 32px !important;
            font-size: 1rem !important;
        }

        .kpi-main-val {
            font-size: 1.5rem !important;
        }

        .kpi-sub-label {
            font-size: 0.72rem !important;
        }

        /* Chart Boxes in Print */
        .charts-row-main {
            display: block !important;
        }

        .chart-box {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            padding: 14px 16px !important;
            margin-bottom: 16px !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        .chart-canvas-wrap {
            height: 250px !important;
            max-height: 250px !important;
            width: 100% !important;
        }

        .doughnut-dual-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 20px !important;
        }

        /* Table in Print */
        .table-card {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            overflow: visible !important;
            margin-top: 14px !important;
        }

        .recap-table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .recap-table thead {
            display: table-header-group !important;
        }

        .recap-table th {
            background: #f1f5f9 !important;
            color: #1e293b !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 7.5pt !important;
            padding: 8px 6px !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .recap-table td {
            border: 1px solid #e2e8f0 !important;
            font-size: 7.8pt !important;
            padding: 7px 6px !important;
        }

        .recap-table tr {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* Print Signatures Block */
        .print-signatures {
            display: flex !important;
            justify-content: space-between !important;
            margin-top: 25px !important;
            padding: 0 40px !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        .grade-kpi-grid {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 10px !important;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .grade-charts-row {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 10px !important;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .sig-col {
            text-align: center;
            width: 250px;
        }

        .sig-col p {
            margin: 0;
            font-size: 9pt;
            color: #1e293b;
        }

        .sig-space {
            height: 60px;
        }

        .sig-name {
            font-weight: 800;
            text-decoration: underline;
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
        .grade-charts-row {
            grid-template-columns: 1fr;
        }
        .grade-kpi-grid {
            grid-template-columns: 1fr;
        }
        .section-divider-banner {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="charts-page">

    {{-- PRINT ONLY: KOP SURAT RESMI & HEADER DOKUMEN --}}
    <div class="print-only">
        @include('partials.official_kop')

        <div class="print-doc-header">
            <h2>LAPORAN REKAPITULASI & GRAFIK PRESENSI & PENILAIAN EKSTRAKURIKULER</h2>
            <p>
                Tahun Ajaran: <strong>{{ $contextYear ? $contextYear->name : '-' }}</strong> &bull;
                Ekstrakurikuler: <strong>{{ $selectedEskul ? $selectedEskul->name : ($isTeacherOnly ? ($eskuls->count() > 0 ? 'Kelompok Binaan' : 'Belum Ada Eskul') : 'Seluruh Ekstrakurikuler (' . $eskuls->count() . ' Cabang)') }}</strong>
                @if($periodMode === 'date')
                    &bull; Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                @elseif($periodMode === 'month')
                    &bull; Periode: {{ \Carbon\Carbon::parse($selectedMonth . '-01')->translatedFormat('F Y') }}
                @elseif($periodMode === 'semester')
                    &bull; Semester: {{ $selectedSemester === '1' ? '1 (Ganjil)' : ($selectedSemester === '2' ? '2 (Genap)' : 'Semua Semester') }}
                @elseif($periodMode === 'year')
                    &bull; Rekap Multi-Tahun Ajaran
                @endif
            </p>
            <div class="print-meta">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB &bull; SIMUTQIN SDIT AN NADZIR</div>
        </div>
    </div>

    {{-- 1. HERO BANNER (LAYAR MONITOR) --}}
    <div class="charts-hero">
        <div class="charts-hero-left">
            <div class="charts-hero-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="charts-hero-text">
                <h1>Grafik & Rekap Presensi & Nilai Ekstrakurikuler</h1>
                <p>
                    Visualisasi analitik kehadiran pembina & siswa serta persentase capaian nilai harian, SAS 1, dan SAS 2 ekstrakurikuler.<br>
                    Tahun Ajaran: <strong>{{ $contextYear ? $contextYear->name : '-' }}</strong> &bull; 
                    Ekstrakurikuler: <strong>{{ $selectedEskul ? $selectedEskul->name : ($isTeacherOnly ? ($eskuls->count() > 0 ? 'Kelompok Binaan Anda' : 'Belum Ada Eskul') : 'Seluruh Eskul') }}</strong>
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
                    @if($isTeacherOnly && $eskuls->count() <= 1)
                        <select name="eskul_id" class="filter-input" disabled style="background: #f1f5f9; cursor: not-allowed; font-weight: 700; color: #064e3b;">
                            @if($eskuls->isNotEmpty())
                                <option value="{{ $eskuls->first()->id }}" selected>
                                    {{ $eskuls->first()->name }} (Kelompok Anda)
                                </option>
                            @else
                                <option value="">(Belum Ada Eskul Binaan)</option>
                            @endif
                        </select>
                    @else
                        <select name="eskul_id" class="filter-input" onchange="document.getElementById('filterChartsForm').submit()">
                            @if($isTeacherOnly)
                                <option value="all" {{ $selectedEskulId === 'all' ? 'selected' : '' }}>-- Seluruh Kelompok Binaan Saya ({{ $eskuls->count() }}) --</option>
                            @else
                                <option value="all" {{ $selectedEskulId === 'all' ? 'selected' : '' }}>-- Seluruh Ekstrakurikuler --</option>
                            @endif
                            @foreach($eskuls as $esk)
                                <option value="{{ $esk->id }}" {{ (string)$selectedEskulId === (string)$esk->id ? 'selected' : '' }}>
                                    {{ $esk->name }} {{ $isTeacherOnly ? '(Binaan Anda)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    @endif
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
                    <i class="fas fa-shapes"></i> {{ $selectedEskul ? $selectedEskul->name : ($isTeacherOnly ? $eskuls->count() . ' Cabang Binaan' : $eskuls->count() . ' Cabang Eskul') }}
                </span>
            </div>
        </div>

        {{-- Card 4: Eskul Paling Aktif / Performa Kelompok --}}
        <div class="kpi-card" style="border-left: 4px solid #8b5cf6;">
            <div class="kpi-card-top">
                <span class="kpi-tag" style="background: #f5f3ff; color: #6d28d9;">
                    {{ ($isTeacherOnly && $eskuls->count() <= 1) ? 'Performa Kelompok Anda' : 'Performa Tertinggi' }}
                </span>
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
    @if(!$isTeacherOnly && $eskuls->count() > 1 && $selectedEskulId === 'all')
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
    @elseif($isTeacherOnly && $eskuls->count() > 1 && $selectedEskulId === 'all')
    <div class="chart-box">
        <div class="chart-header">
            <h3>
                <i class="fas fa-chart-bar" style="color: #8b5cf6;"></i>
                Komparasi Kehadiran Kelompok Ekstrakurikuler Binaan Anda
            </h3>
            <span class="chart-badge">Siswa vs Pembina (%)</span>
        </div>
        <div class="chart-canvas-wrap" style="min-height: {{ max(300, $eskuls->count() * 38) }}px;">
            <canvas id="eskulComparisonChart"></canvas>
        </div>
    </div>
    @endif

    {{-- 7. SECTION ANALISIS & GRAFIK CAPAIAN NILAI EKSTRAKURIKULER --}}
    <div class="section-divider-banner">
        <div class="section-divider-left">
            <div class="section-divider-icon">
                <i class="fas fa-award"></i>
            </div>
            <div>
                <h2>Analisis & Capaian Nilai Ekstrakurikuler</h2>
                <p>Distribusi persentase predikat siswa pada Nilai Harian (Per Pertemuan), SAS 1 (Ganjil), dan SAS 2 (Genap)</p>
            </div>
        </div>
        <div class="section-divider-badges">
            <span class="badge-pill-purple">
                <i class="fas fa-layer-group"></i> Total {{ number_format($totalGradesCount) }} Data Nilai Tercatat
            </span>
        </div>
    </div>

    {{-- 8. 3 KARTU RINGKASAN DISTRIBUSI PREDIKAT NILAI --}}
    <div class="grade-kpi-grid">
        {{-- Card 1: Nilai Harian --}}
        <div class="grade-card" style="border-top: 4px solid #8b5cf6;">
            <div class="grade-card-top">
                <span class="grade-badge-tag" style="background: #f5f3ff; color: #6d28d9;">
                    <i class="fas fa-calendar-day"></i> Nilai Harian
                </span>
                <span style="font-size: 0.78rem; font-weight: 700; color: #64748b;">
                    {{ number_format($dailyGradeStats['records_count']) }} input
                </span>
            </div>

            <div class="grade-donut-row">
                <div class="grade-donut-canvas-box">
                    <canvas id="dailyGradeDoughnutChart"></canvas>
                    <div class="grade-donut-center-text">
                        {{ $dailyGradeStats['pct_good'] }}%
                        <span class="grade-donut-center-sub">TUNTAS</span>
                    </div>
                </div>

                <div class="grade-predikat-bars">
                    {{-- Predikat A --}}
                    <div>
                        <div class="grade-pred-item" style="color: #059669;">
                            <span><i class="fas fa-circle" style="font-size: 0.55rem;"></i> Predikat A (Sangat Baik)</span>
                            <span>{{ $dailyGradeStats['pct_a'] }}% <span style="font-weight: 600; color: #94a3b8;">({{ $dailyGradeStats['count_a'] }})</span></span>
                        </div>
                        <div class="grade-pred-bar-bg">
                            <div class="grade-pred-bar-fill" style="width: {{ $dailyGradeStats['pct_a'] }}%; background: #10b981;"></div>
                        </div>
                    </div>

                    {{-- Predikat B --}}
                    <div>
                        <div class="grade-pred-item" style="color: #0284c7;">
                            <span><i class="fas fa-circle" style="font-size: 0.55rem;"></i> Predikat B (Baik)</span>
                            <span>{{ $dailyGradeStats['pct_b'] }}% <span style="font-weight: 600; color: #94a3b8;">({{ $dailyGradeStats['count_b'] }})</span></span>
                        </div>
                        <div class="grade-pred-bar-bg">
                            <div class="grade-pred-bar-fill" style="width: {{ $dailyGradeStats['pct_b'] }}%; background: #0284c7;"></div>
                        </div>
                    </div>

                    {{-- Predikat C --}}
                    <div>
                        <div class="grade-pred-item" style="color: #d97706;">
                            <span><i class="fas fa-circle" style="font-size: 0.55rem;"></i> Predikat C (Cukup)</span>
                            <span>{{ $dailyGradeStats['pct_c'] }}% <span style="font-weight: 600; color: #94a3b8;">({{ $dailyGradeStats['count_c'] }})</span></span>
                        </div>
                        <div class="grade-pred-bar-bg">
                            <div class="grade-pred-bar-fill" style="width: {{ $dailyGradeStats['pct_c'] }}%; background: #f59e0b;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="font-size: 0.78rem; color: #64748b; padding-top: 8px; border-top: 1px dashed #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <span>Predikat Dominan:</span>
                <strong style="color: #0f172a;">{{ $dailyGradeStats['dominant'] }}</strong>
            </div>
        </div>

        {{-- Card 2: Nilai SAS 1 --}}
        <div class="grade-card" style="border-top: 4px solid #0d9488;">
            <div class="grade-card-top">
                <span class="grade-badge-tag" style="background: #f0fdfa; color: #0f766e;">
                    <i class="fas fa-clipboard-check"></i> SAS 1 (Ganjil)
                </span>
                <span style="font-size: 0.78rem; font-weight: 700; color: #64748b;">
                    {{ number_format($sas1GradeStats['records_count']) }} siswa
                </span>
            </div>

            <div class="grade-donut-row">
                <div class="grade-donut-canvas-box">
                    <canvas id="sas1GradeDoughnutChart"></canvas>
                    <div class="grade-donut-center-text">
                        {{ $sas1GradeStats['pct_good'] }}%
                        <span class="grade-donut-center-sub">TUNTAS</span>
                    </div>
                </div>

                <div class="grade-predikat-bars">
                    {{-- Predikat A --}}
                    <div>
                        <div class="grade-pred-item" style="color: #059669;">
                            <span><i class="fas fa-circle" style="font-size: 0.55rem;"></i> Predikat A (Sangat Baik)</span>
                            <span>{{ $sas1GradeStats['pct_a'] }}% <span style="font-weight: 600; color: #94a3b8;">({{ $sas1GradeStats['count_a'] }})</span></span>
                        </div>
                        <div class="grade-pred-bar-bg">
                            <div class="grade-pred-bar-fill" style="width: {{ $sas1GradeStats['pct_a'] }}%; background: #10b981;"></div>
                        </div>
                    </div>

                    {{-- Predikat B --}}
                    <div>
                        <div class="grade-pred-item" style="color: #0284c7;">
                            <span><i class="fas fa-circle" style="font-size: 0.55rem;"></i> Predikat B (Baik)</span>
                            <span>{{ $sas1GradeStats['pct_b'] }}% <span style="font-weight: 600; color: #94a3b8;">({{ $sas1GradeStats['count_b'] }})</span></span>
                        </div>
                        <div class="grade-pred-bar-bg">
                            <div class="grade-pred-bar-fill" style="width: {{ $sas1GradeStats['pct_b'] }}%; background: #0284c7;"></div>
                        </div>
                    </div>

                    {{-- Predikat C --}}
                    <div>
                        <div class="grade-pred-item" style="color: #d97706;">
                            <span><i class="fas fa-circle" style="font-size: 0.55rem;"></i> Predikat C (Cukup)</span>
                            <span>{{ $sas1GradeStats['pct_c'] }}% <span style="font-weight: 600; color: #94a3b8;">({{ $sas1GradeStats['count_c'] }})</span></span>
                        </div>
                        <div class="grade-pred-bar-bg">
                            <div class="grade-pred-bar-fill" style="width: {{ $sas1GradeStats['pct_c'] }}%; background: #f59e0b;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="font-size: 0.78rem; color: #64748b; padding-top: 8px; border-top: 1px dashed #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <span>Predikat Dominan:</span>
                <strong style="color: #0f172a;">{{ $sas1GradeStats['dominant'] }}</strong>
            </div>
        </div>

        {{-- Card 3: Nilai SAS 2 --}}
        <div class="grade-card" style="border-top: 4px solid #f59e0b;">
            <div class="grade-card-top">
                <span class="grade-badge-tag" style="background: #fffbeb; color: #b45309;">
                    <i class="fas fa-clipboard-list"></i> SAS 2 (Genap)
                </span>
                <span style="font-size: 0.78rem; font-weight: 700; color: #64748b;">
                    {{ number_format($sas2GradeStats['records_count']) }} siswa
                </span>
            </div>

            <div class="grade-donut-row">
                <div class="grade-donut-canvas-box">
                    <canvas id="sas2GradeDoughnutChart"></canvas>
                    <div class="grade-donut-center-text">
                        {{ $sas2GradeStats['pct_good'] }}%
                        <span class="grade-donut-center-sub">TUNTAS</span>
                    </div>
                </div>

                <div class="grade-predikat-bars">
                    {{-- Predikat A --}}
                    <div>
                        <div class="grade-pred-item" style="color: #059669;">
                            <span><i class="fas fa-circle" style="font-size: 0.55rem;"></i> Predikat A (Sangat Baik)</span>
                            <span>{{ $sas2GradeStats['pct_a'] }}% <span style="font-weight: 600; color: #94a3b8;">({{ $sas2GradeStats['count_a'] }})</span></span>
                        </div>
                        <div class="grade-pred-bar-bg">
                            <div class="grade-pred-bar-fill" style="width: {{ $sas2GradeStats['pct_a'] }}%; background: #10b981;"></div>
                        </div>
                    </div>

                    {{-- Predikat B --}}
                    <div>
                        <div class="grade-pred-item" style="color: #0284c7;">
                            <span><i class="fas fa-circle" style="font-size: 0.55rem;"></i> Predikat B (Baik)</span>
                            <span>{{ $sas2GradeStats['pct_b'] }}% <span style="font-weight: 600; color: #94a3b8;">({{ $sas2GradeStats['count_b'] }})</span></span>
                        </div>
                        <div class="grade-pred-bar-bg">
                            <div class="grade-pred-bar-fill" style="width: {{ $sas2GradeStats['pct_b'] }}%; background: #0284c7;"></div>
                        </div>
                    </div>

                    {{-- Predikat C --}}
                    <div>
                        <div class="grade-pred-item" style="color: #d97706;">
                            <span><i class="fas fa-circle" style="font-size: 0.55rem;"></i> Predikat C (Cukup)</span>
                            <span>{{ $sas2GradeStats['pct_c'] }}% <span style="font-weight: 600; color: #94a3b8;">({{ $sas2GradeStats['count_c'] }})</span></span>
                        </div>
                        <div class="grade-pred-bar-bg">
                            <div class="grade-pred-bar-fill" style="width: {{ $sas2GradeStats['pct_c'] }}%; background: #f59e0b;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="font-size: 0.78rem; color: #64748b; padding-top: 8px; border-top: 1px dashed #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <span>Predikat Dominan:</span>
                <strong style="color: #0f172a;">{{ $sas2GradeStats['dominant'] }}</strong>
            </div>
        </div>
    </div>

    {{-- 9. DUAL CHARTS: KOMPARASI PREDIKAT & KOMPARASI NILAI PER ESKUL --}}
    <div class="grade-charts-row">
        {{-- Chart Left: Grouped Bar Chart Harian vs SAS 1 vs SAS 2 --}}
        <div class="chart-box">
            <div class="chart-header">
                <h3>
                    <i class="fas fa-chart-bar" style="color: #8b5cf6;"></i>
                    Komparasi Distribusi Predikat (Harian vs SAS 1 vs SAS 2)
                </h3>
                <span class="chart-badge">Predikat A, B, C (%)</span>
            </div>
            <div class="chart-canvas-wrap" style="min-height: 290px;">
                <canvas id="gradeComparisonBarChart"></canvas>
            </div>
        </div>

        {{-- Chart Right: Horizontal Bar Chart Capaian Nilai per Eskul --}}
        <div class="chart-box">
            <div class="chart-header">
                <h3>
                    <i class="fas fa-tasks" style="color: #059669;"></i>
                    Komparasi Mutu Capaian Nilai per Cabang Eskul
                </h3>
                <span class="chart-badge">% Tuntas (A & B)</span>
            </div>
            <div class="chart-canvas-wrap" style="min-height: {{ max(290, $eskuls->count() * 42) }}px;">
                <canvas id="eskulGradeComparisonChart"></canvas>
            </div>
        </div>
    </div>

    {{-- 10. TABEL REKAPITULASI DETAIL PRESENSI & PENILAIAN PER ESKUL --}}
    <div class="table-card">
        <div class="table-card-header">
            <h3>
                <i class="fas fa-table" style="color: #059669;"></i>
                {{ $isTeacherOnly ? 'Tabel Rekapitulasi Presensi & Nilai Kelompok Anda' : 'Tabel Rekapitulasi Presensi & Nilai Ekstrakurikuler' }}
            </h3>
            <span style="font-size: 0.8rem; font-weight: 700; color: #64748b;">
                Menampilkan {{ $eskulBreakdown->count() }} Ekstrakurikuler
            </span>
        </div>

        <div class="table-responsive">
            <table class="recap-table">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th width="17%">Ekstrakurikuler & Jadwal</th>
                        <th width="12%">Pembina</th>
                        <th width="5%" style="text-align: center;">Sesi</th>
                        <th width="13%">Presensi Pembina</th>
                        <th width="14%">Presensi Siswa</th>
                        <th width="13%">Nilai Harian</th>
                        <th width="10%">SAS 1</th>
                        <th width="10%">SAS 2</th>
                        <th width="3%" style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eskulBreakdown as $idx => $item)
                    <tr>
                        <td align="center" style="font-weight: 700; color: #94a3b8;">{{ $idx + 1 }}</td>
                        <td>
                            <div style="font-weight: 800; color: #0f172a; font-size: 0.90rem;">
                                {{ $item->name }}
                            </div>
                            <div style="font-size: 0.74rem; color: #64748b; margin-top: 2px;">
                                <i class="far fa-clock"></i> {{ $item->schedule }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.85rem;">
                                {{ $item->coach_name }}
                            </div>
                        </td>
                        <td align="center">
                            <span style="font-weight: 800; font-size: 0.95rem; color: #0f172a;">
                                {{ $item->sessions_count }}
                            </span>
                            <div style="font-size: 0.70rem; color: #94a3b8;">sesi</div>
                        </td>
                        <td>
                            <div style="display: flex; justify-content: space-between; align-items: baseline; font-size: 0.80rem;">
                                <span style="font-weight: 800; color: {{ $item->coach_rate >= 80 ? '#047857' : ($item->coach_rate >= 60 ? '#d97706' : '#dc2626') }};">
                                    {{ $item->coach_rate }}%
                                </span>
                                <span style="font-size: 0.72rem; color: #64748b;">
                                    {{ $item->coach_present + $item->coach_substituted }}/{{ $item->coach_total }}
                                </span>
                            </div>
                            <div class="rate-bar-bg">
                                <div class="rate-bar-fill" style="width: {{ $item->coach_rate }}%; background: linear-gradient(90deg, #10b981 0%, #059669 100%);"></div>
                            </div>
                            <div style="font-size: 0.70rem; color: #94a3b8; margin-top: 2px;">
                                H: {{ $item->coach_present }} &bull; Ganti: {{ $item->coach_substituted }}
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; justify-content: space-between; align-items: baseline; font-size: 0.80rem;">
                                <span style="font-weight: 800; color: {{ $item->student_rate >= 80 ? '#0369a1' : ($item->student_rate >= 60 ? '#d97706' : '#dc2626') }};">
                                    {{ $item->student_rate }}%
                                </span>
                                <span style="font-size: 0.72rem; color: #64748b;">
                                    {{ $item->student_present }}/{{ $item->student_total }}
                                </span>
                            </div>
                            <div class="rate-bar-bg">
                                <div class="rate-bar-fill" style="width: {{ $item->student_rate }}%; background: linear-gradient(90deg, #0284c7 0%, #0369a1 100%);"></div>
                            </div>
                            <div style="font-size: 0.70rem; color: #94a3b8; margin-top: 2px;">
                                H: {{ $item->student_present }} &bull; S: {{ $item->student_sick }} &bull; I: {{ $item->student_permission }} &bull; A: {{ $item->student_absent }}
                            </div>
                        </td>
                        {{-- Nilai Harian --}}
                        <td>
                            @if(isset($item->daily_stats) && $item->daily_stats->total > 0)
                                <div style="display: flex; justify-content: space-between; align-items: baseline; font-size: 0.80rem;">
                                    <span style="font-weight: 800; color: {{ $item->daily_stats->pct_good >= 80 ? '#047857' : ($item->daily_stats->pct_good >= 60 ? '#d97706' : '#dc2626') }};">
                                        {{ $item->daily_stats->pct_good }}% Tuntas
                                    </span>
                                    <span style="font-size: 0.70rem; color: #64748b;">
                                        {{ $item->daily_stats->records_count }} data
                                    </span>
                                </div>
                                <div class="rate-bar-bg">
                                    <div class="rate-bar-fill" style="width: {{ $item->daily_stats->pct_good }}%; background: linear-gradient(90deg, #8b5cf6 0%, #6d28d9 100%);"></div>
                                </div>
                                <div style="font-size: 0.70rem; color: #94a3b8; margin-top: 2px;">
                                    A: {{ $item->daily_stats->pct_a }}% &bull; B: {{ $item->daily_stats->pct_b }}% &bull; C: {{ $item->daily_stats->pct_c }}%
                                </div>
                            @else
                                <span style="font-size: 0.75rem; color: #94a3b8; font-style: italic;">Belum ada nilai</span>
                            @endif
                        </td>
                        {{-- Nilai SAS 1 --}}
                        <td>
                            @if(isset($item->sas1_stats) && $item->sas1_stats->total > 0)
                                <div style="display: flex; justify-content: space-between; align-items: baseline; font-size: 0.80rem;">
                                    <span style="font-weight: 800; color: {{ $item->sas1_stats->pct_good >= 80 ? '#047857' : ($item->sas1_stats->pct_good >= 60 ? '#d97706' : '#dc2626') }};">
                                        {{ $item->sas1_stats->pct_good }}%
                                    </span>
                                    <span style="font-size: 0.70rem; color: #64748b;">
                                        {{ $item->sas1_stats->records_count }} siswa
                                    </span>
                                </div>
                                <div class="rate-bar-bg">
                                    <div class="rate-bar-fill" style="width: {{ $item->sas1_stats->pct_good }}%; background: linear-gradient(90deg, #0d9488 0%, #059669 100%);"></div>
                                </div>
                                <div style="font-size: 0.70rem; color: #94a3b8; margin-top: 2px;">
                                    A: {{ $item->sas1_stats->pct_a }}% &bull; B: {{ $item->sas1_stats->pct_b }}%
                                </div>
                            @else
                                <span style="font-size: 0.75rem; color: #94a3b8; font-style: italic;">Belum diinput</span>
                            @endif
                        </td>
                        {{-- Nilai SAS 2 --}}
                        <td>
                            @if(isset($item->sas2_stats) && $item->sas2_stats->total > 0)
                                <div style="display: flex; justify-content: space-between; align-items: baseline; font-size: 0.80rem;">
                                    <span style="font-weight: 800; color: {{ $item->sas2_stats->pct_good >= 80 ? '#047857' : ($item->sas2_stats->pct_good >= 60 ? '#d97706' : '#dc2626') }};">
                                        {{ $item->sas2_stats->pct_good }}%
                                    </span>
                                    <span style="font-size: 0.70rem; color: #64748b;">
                                        {{ $item->sas2_stats->records_count }} siswa
                                    </span>
                                </div>
                                <div class="rate-bar-bg">
                                    <div class="rate-bar-fill" style="width: {{ $item->sas2_stats->pct_good }}%; background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);"></div>
                                </div>
                                <div style="font-size: 0.70rem; color: #94a3b8; margin-top: 2px;">
                                    A: {{ $item->sas2_stats->pct_a }}% &bull; B: {{ $item->sas2_stats->pct_b }}%
                                </div>
                            @else
                                <span style="font-size: 0.75rem; color: #94a3b8; font-style: italic;">Belum diinput</span>
                            @endif
                        </td>
                        <td align="center">
                            <span class="badge-predikat" style="background: {{ $item->badge_color }}15; color: {{ $item->badge_color }}; border: 1px solid {{ $item->badge_color }}35;" title="{{ $item->predikat }}">
                                <i class="fas fa-circle" style="font-size: 0.45rem;"></i>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" align="center" style="padding: 30px; color: #94a3b8;">
                            <i class="fas fa-info-circle" style="font-size: 1.5rem; margin-bottom: 6px; display: block;"></i>
                            @if($isTeacherOnly)
                                Belum ada catatan presensi atau nilai untuk kelompok ekstrakurikuler binaan Anda pada filter & periode waktu ini.
                            @else
                                Belum ada catatan presensi atau nilai pada filter ekstrakurikuler & periode waktu ini.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tanda Tangan Resmi Hanya Saat Cetak Dokumen --}}
    @php
        $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
        $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
        $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : 'Nur\'asiah, S.Pd.I');
    @endphp
    <div class="print-signatures print-only">
        <div class="sig-col">
            <p>Cinangka, {{ now()->translatedFormat('d F Y') }}</p>
            <p class="sig-title" style="margin-top: 3px;">
                {{ $isTeacherOnly ? 'Pembina Ekstrakurikuler,' : 'Koordinator Ekstrakurikuler,' }}
            </p>
            <div class="sig-space"></div>
            <p class="sig-name">{{ $isTeacherOnly ? (auth()->user()->name ?? 'Pembina Eskul') : 'Najibudin, S.Pd' }}</p>
        </div>
        <div class="sig-col">
            <p>&nbsp;</p>
            <p class="sig-title" style="margin-top: 3px;">Mengetahui,<br><b>Kepala SDIT AN NADZIR</b></p>
            <div class="sig-space"></div>
            <p class="sig-name">{{ $headmasterName }}</p>
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
                    },
                    {
                        label: '% Capaian Nilai Harian (A & B)',
                        data: {!! json_encode($mainChartDailyGradeRate) !!},
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.12)',
                        borderWidth: 3,
                        pointBackgroundColor: '#8b5cf6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: false,
                        spanGaps: true
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

    // --- CHART 5: DOUGHNUT NILAI HARIAN ---
    const ctxDailyGrade = document.getElementById('dailyGradeDoughnutChart');
    if (ctxDailyGrade) {
        new Chart(ctxDailyGrade.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Predikat A', 'Predikat B', 'Predikat C'],
                datasets: [{
                    data: [
                        {{ $dailyGradeStats['count_a'] }},
                        {{ $dailyGradeStats['count_b'] }},
                        {{ $dailyGradeStats['count_c'] }}
                    ],
                    backgroundColor: ['#10b981', '#0284c7', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                const total = {{ $dailyGradeStats['total'] }};
                                const pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                                return ' ' + ctx.label + ': ' + ctx.raw + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // --- CHART 6: DOUGHNUT NILAI SAS 1 ---
    const ctxSas1Grade = document.getElementById('sas1GradeDoughnutChart');
    if (ctxSas1Grade) {
        new Chart(ctxSas1Grade.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Predikat A', 'Predikat B', 'Predikat C'],
                datasets: [{
                    data: [
                        {{ $sas1GradeStats['count_a'] }},
                        {{ $sas1GradeStats['count_b'] }},
                        {{ $sas1GradeStats['count_c'] }}
                    ],
                    backgroundColor: ['#10b981', '#0284c7', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                const total = {{ $sas1GradeStats['total'] }};
                                const pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                                return ' ' + ctx.label + ': ' + ctx.raw + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // --- CHART 7: DOUGHNUT NILAI SAS 2 ---
    const ctxSas2Grade = document.getElementById('sas2GradeDoughnutChart');
    if (ctxSas2Grade) {
        new Chart(ctxSas2Grade.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Predikat A', 'Predikat B', 'Predikat C'],
                datasets: [{
                    data: [
                        {{ $sas2GradeStats['count_a'] }},
                        {{ $sas2GradeStats['count_b'] }},
                        {{ $sas2GradeStats['count_c'] }}
                    ],
                    backgroundColor: ['#10b981', '#0284c7', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                const total = {{ $sas2GradeStats['total'] }};
                                const pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                                return ' ' + ctx.label + ': ' + ctx.raw + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // --- CHART 8: GROUPED BAR KOMPARASI PREDIKAT (HARIAN vs SAS 1 vs SAS 2) ---
    const ctxGradeComp = document.getElementById('gradeComparisonBarChart');
    if (ctxGradeComp) {
        const gradeLabels = {!! json_encode($gradeComparisonLabels) !!};
        const pctA = {!! json_encode($gradeCompPctA) !!};
        const pctB = {!! json_encode($gradeCompPctB) !!};
        const pctC = {!! json_encode($gradeCompPctC) !!};
        const countA = {!! json_encode($gradeCompCountA) !!};
        const countB = {!! json_encode($gradeCompCountB) !!};
        const countC = {!! json_encode($gradeCompCountC) !!};

        new Chart(ctxGradeComp.getContext('2d'), {
            type: 'bar',
            data: {
                labels: gradeLabels,
                datasets: [
                    {
                        label: '% Predikat A (Sangat Baik)',
                        data: pctA,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        barPercentage: 0.75,
                        categoryPercentage: 0.75
                    },
                    {
                        label: '% Predikat B (Baik)',
                        data: pctB,
                        backgroundColor: '#0284c7',
                        borderRadius: 6,
                        barPercentage: 0.75,
                        categoryPercentage: 0.75
                    },
                    {
                        label: '% Predikat C (Cukup)',
                        data: pctC,
                        backgroundColor: '#f59e0b',
                        borderRadius: 6,
                        barPercentage: 0.75,
                        categoryPercentage: 0.75
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(val) { return val + '%'; },
                            font: { family: 'Nunito', weight: '700' }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { family: 'Nunito', weight: '750', size: 12 } },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: 'Nunito', weight: '700', size: 11 },
                            usePointStyle: true,
                            padding: 12
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                const idx = ctx.dataIndex;
                                let cnt = 0;
                                if (ctx.datasetIndex === 0) cnt = countA[idx];
                                else if (ctx.datasetIndex === 1) cnt = countB[idx];
                                else if (ctx.datasetIndex === 2) cnt = countC[idx];
                                return ' ' + ctx.dataset.label + ': ' + ctx.raw + '% (' + cnt + ' data)';
                            }
                        }
                    }
                }
            }
        });
    }

    // --- CHART 9: KOMPARASI MUTU NILAI PER ESKUL ---
    const ctxEskulGradeComp = document.getElementById('eskulGradeComparisonChart');
    if (ctxEskulGradeComp) {
        const eskulGradeLabels = {!! json_encode($eskulGradeCompLabels) !!};
        const dailyRate = {!! json_encode($eskulGradeCompDailyRate) !!};
        const sas1Rate = {!! json_encode($eskulGradeCompSas1Rate) !!};
        const sas2Rate = {!! json_encode($eskulGradeCompSas2Rate) !!};

        new Chart(ctxEskulGradeComp.getContext('2d'), {
            type: 'bar',
            data: {
                labels: eskulGradeLabels,
                datasets: [
                    {
                        label: '% Harian Tuntas (A & B)',
                        data: dailyRate,
                        backgroundColor: '#8b5cf6',
                        borderRadius: 6,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    },
                    {
                        label: '% SAS 1 Tuntas (A & B)',
                        data: sas1Rate,
                        backgroundColor: '#0d9488',
                        borderRadius: 6,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    },
                    {
                        label: '% SAS 2 Tuntas (A & B)',
                        data: sas2Rate,
                        backgroundColor: '#f59e0b',
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
