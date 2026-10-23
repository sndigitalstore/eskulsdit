@extends('layouts.app')

@section('title', 'Rekapitulasi Presensi - ' . ($eskul->name ?? 'Eskul'))
@section('page-title', 'Rekapitulasi Presensi')

@push('styles')
<style>
    /* ========================================================
       REKAPITULASI PRESENSI - MODERN & FULLY RESPONSIVE SYSTEM
       ======================================================== */
    .recap-container {
        max-width: 1100px;
        margin: 0 auto;
        padding-bottom: 2rem;
    }

    /* Top Navigation Back Pill */
    .top-nav-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .btn-nav-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #334155;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 8px 18px;
        border-radius: 999px;
        font-size: 0.86rem;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-nav-back:hover {
        background: #f8fafc;
        color: #059669;
        border-color: #cbd5e1;
        transform: translateX(-3px);
    }

    .nav-actions-group {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-preview-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        color: #047857;
        border: 1.5px solid #a7f3d0;
        padding: 8px 18px;
        border-radius: 999px;
        font-size: 0.88rem;
        font-weight: 750;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(5, 150, 105, 0.08);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-preview-action:hover {
        background: #ecfdf5;
        border-color: #059669;
        transform: translateY(-2px);
    }

    .btn-print-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        border: none;
        padding: 9px 20px;
        border-radius: 999px;
        font-size: 0.88rem;
        font-weight: 750;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
    }

    .btn-print-action:hover {
        background: #0f172a;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.25);
    }

    /* Main Hero Banner */
    .recap-hero-card {
        background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
        border: 1.5px solid #d1fae5;
        border-radius: 20px;
        padding: 1.6rem 1.8rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 8px 25px -5px rgba(16, 185, 129, 0.08);
        position: relative;
        overflow: hidden;
    }

    .recap-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .recap-hero-title {
        font-size: 1.45rem;
        font-weight: 900;
        color: #064e3b;
        letter-spacing: -0.02em;
        margin: 0 0 6px 0;
        line-height: 1.25;
    }

    .recap-hero-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #475569;
        font-size: 0.88rem;
        font-weight: 600;
        flex-wrap: wrap;
    }

    .recap-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Symmetrical 4-Grid KPI Cards */
    .kpi-recap-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 1.25rem;
    }

    .kpi-recap-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.1rem 1rem;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .kpi-recap-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
    }

    .kpi-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .kpi-val {
        font-size: 1.35rem;
        font-weight: 900;
        line-height: 1.15;
        letter-spacing: -0.02em;
    }

    .kpi-lbl {
        font-size: 0.74rem;
        color: #64748b;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-top: 2px;
    }

    .kpi-students .kpi-icon-box { background: #e0f2fe; color: #0284c7; }
    .kpi-students .kpi-val { color: #0284c7; }

    .kpi-sessions .kpi-icon-box { background: #f3e8ff; color: #7c3aed; }
    .kpi-sessions .kpi-val { color: #7c3aed; }

    .kpi-rate .kpi-icon-box { background: #ecfdf5; color: #059669; }
    .kpi-rate .kpi-val { color: #059669; }

    .kpi-present .kpi-icon-box { background: #fef3c7; color: #d97706; }
    .kpi-present .kpi-val { color: #d97706; }

    /* Riwayat Pertemuan Drawer / Strip */
    .sessions-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1.5px solid #e2e8f0;
        padding: 1.25rem 1.4rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
    }

    .sessions-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        cursor: pointer;
        user-select: none;
    }

    .sessions-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .sessions-chips-scroll {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .session-chip {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #334155;
        font-weight: 600;
        transition: all 0.2s;
    }

    .session-chip:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    .session-chip strong {
        color: #0f172a;
        font-weight: 750;
    }

    .session-chip .btn-del-date {
        background: #fee2e2;
        color: #ef4444;
        border: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.65rem;
        transition: background 0.2s;
    }

    .session-chip .btn-del-date:hover {
        background: #ef4444;
        color: #ffffff;
    }

    /* Main Table Container */
    .recap-content-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    }

    .card-header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 10px;
    }

    .card-title-text {
        font-size: 1.15rem;
        font-weight: 850;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    /* Desktop Table Styling */
    .recap-desktop-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }

    .recap-desktop-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 800;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 12px 14px;
        border-top: 1px solid #e2e8f0;
        border-bottom: 2px solid #cbd5e1;
        text-align: center;
    }

    .recap-desktop-table th.text-left { text-align: left; }

    .recap-desktop-table td {
        padding: 14px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #334155;
        text-align: center;
        vertical-align: middle;
        background: #ffffff;
        transition: background 0.15s;
    }

    .recap-desktop-table tr:hover td {
        background: #f8fafc;
    }

    .recap-desktop-table td.text-left { text-align: left; }

    .recap-pill-stat {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 28px;
        padding: 0 8px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 0.86rem;
    }

    .pill-stat-h { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .pill-stat-s { background: #fefce8; color: #ca8a04; border: 1px solid #fef08a; }
    .pill-stat-i { background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; }
    .pill-stat-a { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    .rate-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .rate-high { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .rate-mid { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
    .rate-low { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    /* ========================================================
       MOBILE-FIRST SYMMETRICAL RECAP CARDS (HP ONLY)
       ======================================================== */
    .recap-mobile-cards {
        display: none;
    }

    .student-recap-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.15rem;
        margin-bottom: 12px;
        box-shadow: 0 3px 10px rgba(15, 23, 42, 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .student-recap-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
    }

    /* Card Top Row */
    .student-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .student-id-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        flex: 1;
    }

    .student-idx-num {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #475569;
        font-size: 0.8rem;
        font-weight: 850;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid #cbd5e1;
    }

    .student-name-box {
        min-width: 0;
        flex: 1;
    }

    .student-name-text {
        font-size: 0.95rem;
        font-weight: 850;
        color: #0f172a;
        line-height: 1.3;
        display: block;
        word-break: break-word;
    }

    .student-class-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.72rem;
        font-weight: 750;
        color: #64748b;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 6px;
        margin-top: 3px;
    }

    /* Symmetrical 4-Column Micro Stats Grid on Mobile */
    .student-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
        margin-bottom: 10px;
    }

    .stat-micro-box {
        border-radius: 12px;
        padding: 8px 4px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .stat-micro-num {
        font-size: 1.05rem;
        font-weight: 900;
        line-height: 1.1;
    }

    .stat-micro-lbl {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        margin-top: 2px;
    }

    .stat-box-h { background: #ecfdf5; border: 1px solid #a7f3d0; color: #059669; }
    .stat-box-s { background: #fefce8; border: 1px solid #fef08a; color: #ca8a04; }
    .stat-box-i { background: #fff7ed; border: 1px solid #fed7aa; color: #ea580c; }
    .stat-box-a { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }

    /* Progress Bar for Attendance Rate */
    .rate-bar-container {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 6px;
    }

    .rate-progress-track {
        flex: 1;
        height: 6px;
        background: #f1f5f9;
        border-radius: 999px;
        overflow: hidden;
    }

    .rate-progress-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 0.3s ease;
    }

    .fill-high { background: linear-gradient(90deg, #10b981, #059669); }
    .fill-mid { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .fill-low { background: linear-gradient(90deg, #ef4444, #dc2626); }

    .rate-percent-text {
        font-size: 0.74rem;
        font-weight: 800;
        color: #475569;
        min-width: 38px;
        text-align: right;
    }

    /* Mobile Responsive Breakpoints */
    @media (max-width: 768px) {
        .recap-container { padding: 0 4px 2rem 4px; }
        .top-nav-bar { flex-direction: column; align-items: stretch; gap: 8px; }
        .btn-nav-back, .btn-print-action, .btn-preview-action { justify-content: center; width: 100%; }
        .nav-actions-group { width: 100%; flex-direction: column; }

        .recap-hero-card { padding: 1.25rem 1rem; border-radius: 16px; }
        .recap-hero-title { font-size: 1.2rem; }

        /* Symmetrical 2x2 Grid on Mobile */
        .kpi-recap-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8px !important;
        }
        .kpi-recap-card {
            padding: 0.85rem 0.75rem !important;
            gap: 8px !important;
            border-radius: 14px !important;
        }
        .kpi-icon-box {
            width: 36px !important;
            height: 36px !important;
            font-size: 0.95rem !important;
            border-radius: 10px !important;
        }
        .kpi-val { font-size: 1.15rem !important; }
        .kpi-lbl { font-size: 0.68rem !important; }

        /* Sessions Card on Mobile */
        .sessions-card { padding: 1rem !important; border-radius: 16px !important; }
        .sessions-chips-scroll {
            max-height: 180px;
            overflow-y: auto;
            padding-right: 2px;
        }
        .session-chip {
            padding: 5px 9px !important;
            font-size: 0.75rem !important;
            width: 100%;
            justify-content: space-between;
        }

        /* Swap Table to Symmetrical Cards on Mobile */
        .recap-content-card { padding: 1rem !important; border-radius: 16px !important; }
        .recap-desktop-table { display: none !important; }
        .recap-mobile-cards { display: block !important; }
    }

    /* ========================================================
       DEDICATED 1-LEMBAR PRINT STYLING & OFFICIAL DOCUMENT
       ======================================================== */
    .official-print-document {
        display: none;
    }

    /* Print Preview Modal on Screen */
    .print-preview-modal-backdrop {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(4px);
        z-index: 99999;
        overflow-y: auto;
        padding: 20px 10px;
        align-items: center;
        justify-content: center;
    }
    .print-preview-modal-backdrop.active {
        display: flex;
    }
    .print-preview-dialog {
        background: #ffffff;
        width: 100%;
        max-width: 850px;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: 92vh;
    }
    .print-preview-topbar {
        background: #0f172a;
        color: #ffffff;
        padding: 12px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .print-preview-scrollbody {
        padding: 24px;
        overflow-y: auto;
        background: #f8fafc;
    }
    .preview-paper-sheet {
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        padding: 25px;
        border: 1px solid #e2e8f0;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 8mm 10mm 8mm 10mm;
        }

        html, body {
            background: #ffffff !important;
            color: #000000 !important;
            font-family: 'Times New Roman', Times, Georgia, serif !important;
            margin: 0 !important;
            padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Sembunyikan seluruh antarmuka web, sidebar, header, tombol, dan modal */
        .sidebar, .header, .top-nav-bar, .recap-container, .sidebar-overlay, .no-print, main footer, .print-preview-modal-backdrop {
            display: none !important;
        }

        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            height: auto !important;
        }

        .content {
            margin: 0 !important;
            padding: 0 !important;
            animation: none !important;
        }

        /* Tampilkan SATU-SATUNYA lembar dokumen resmi print */
        .official-print-document {
            display: block !important;
            width: 100% !important;
            background: #ffffff !important;
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .official-print-table tr {
            page-break-inside: avoid !important;
        }

        .print-signatures-block {
            page-break-inside: avoid !important;
        }
    }
</style>
@endpush

@section('content')
@php
    $totalStudents = $students->count();
    $totalSessions = count($recordedDates);
    $sumHadir = $students->sum('h');
    $sumSakit = $students->sum('s');
    $sumIzin = $students->sum('i');
    $sumAlpa = $students->sum('a');
    $totalRecords = $sumHadir + $sumSakit + $sumIzin + $sumAlpa;
    $attendanceRate = $totalRecords > 0 ? round(($sumHadir / $totalRecords) * 100) : 0;

    $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
    $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
    $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : "Nur'asiah, S.Pd.I");

    $instructorName = !empty($eskul->instructor_name) ? $eskul->instructor_name : (Auth::user()->role === 'teacher' ? Auth::user()->name : 'Pembina Ekstrakurikuler');
@endphp

<!-- ========================================================
     A. WEB / MOBILE INTERACTIVE VIEW (PILIHAN GURU & ADMIN)
     ======================================================== -->
<div class="recap-container no-print">

    <!-- 1. Top Navigation Bar (No Print) -->
    <div class="top-nav-bar">
        @if(Auth::user()->role === 'teacher')
        <a href="{{ route('attendance.create', ['eskul_id' => $eskul->id, 'date' => date('Y-m-d')]) }}" class="btn-nav-back">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Lembar Presensi</span>
        </a>
        @else
        <a href="{{ route('attendance.index') }}" class="btn-nav-back">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Pemilihan Presensi</span>
        </a>
        @endif

        <div class="nav-actions-group">
            <button type="button" onclick="openPrintPreview()" class="btn-preview-action">
                <i class="fas fa-eye"></i>
                <span>Pratinjau Lembar Cetak</span>
            </button>
            <button onclick="window.print()" class="btn-print-action">
                <i class="fas fa-print"></i>
                <span>Cetak Rekap Presensi</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 12px 16px; border-radius: 14px; margin-bottom: 15px; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.1rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- 2. Hero Header Banner -->
    <div class="recap-hero-card">
        <div class="recap-hero-badge">
            <i class="fas fa-chart-line"></i> Rekapitulasi Presensi
        </div>
        <h1 class="recap-hero-title">{{ $eskul->name }}</h1>
        <div class="recap-hero-meta">
            <div class="recap-meta-item">
                <i class="fas fa-calendar-alt" style="color: #059669;"></i>
                <span>Tahun: <strong>{{ $year->name }}</strong></span>
            </div>
            <div class="recap-meta-item">
                <i class="fas fa-layer-group" style="color: #0284c7;"></i>
                <span>Semester: <strong>{{ $year->active_semester ?? '1' }} ({{ ($year->active_semester ?? '1') == '1' ? 'Ganjil' : 'Genap' }})</strong></span>
            </div>
            @if(!empty($eskul->instructor_name))
            <div class="recap-meta-item">
                <i class="fas fa-chalkboard-teacher" style="color: #7c3aed;"></i>
                <span>Pembina: <strong>{{ $eskul->instructor_name }}</strong></span>
            </div>
            @endif
        </div>
    </div>

    <!-- 3. Symmetrical KPI Summary Cards (2x2 on Mobile, 4x1 on Desktop) -->
    <div class="kpi-recap-grid">
        <div class="kpi-recap-card kpi-students">
            <div class="kpi-icon-box">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="kpi-val">{{ $totalStudents }}</div>
                <div class="kpi-lbl">Total Siswa</div>
            </div>
        </div>

        <div class="kpi-recap-card kpi-sessions">
            <div class="kpi-icon-box">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <div class="kpi-val">{{ $totalSessions }}</div>
                <div class="kpi-lbl">Pertemuan</div>
            </div>
        </div>

        <div class="kpi-recap-card kpi-rate">
            <div class="kpi-icon-box">
                <i class="fas fa-percentage"></i>
            </div>
            <div>
                <div class="kpi-val">{{ $attendanceRate }}%</div>
                <div class="kpi-lbl">Rata-rata Hadir</div>
            </div>
        </div>

        <div class="kpi-recap-card kpi-present">
            <div class="kpi-icon-box">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div>
                <div class="kpi-val">{{ $sumHadir }}</div>
                <div class="kpi-lbl">Total Hadir</div>
            </div>
        </div>
    </div>

    <!-- 4. Riwayat Tanggal Absensi (Pertemuan Ter-input) -->
    @if(isset($recordedDates) && count($recordedDates) > 0)
    <div class="sessions-card">
        <div class="sessions-header">
            <h4 class="sessions-title">
                <i class="far fa-calendar-alt" style="color: #6366f1;"></i>
                <span>Riwayat Pertemuan Terlaksana ({{ count($recordedDates) }} Tanggal)</span>
            </h4>
        </div>
        <div class="sessions-chips-scroll">
            @foreach($recordedDates as $rd)
                <div class="session-chip">
                    <span>
                        <i class="fas fa-check" style="color: #10b981; font-size: 0.7rem; margin-right: 4px;"></i>
                        <strong>{{ \Carbon\Carbon::parse($rd->date)->isoFormat('D MMMM Y') }}</strong>
                        <span style="color: #64748b; font-size: 0.75rem;">({{ $rd->student_count }} siswa)</span>
                    </span>
                    
                    @if(Auth::user()->role === 'admin')
                        <form action="{{ route('attendance.destroy-date') }}" method="POST" class="confirm-delete" data-confirm="Apakah Anda yakin ingin menghapus seluruh data absensi siswa tanggal {{ \Carbon\Carbon::parse($rd->date)->isoFormat('D MMMM Y') }}? Rekapitulasi absensi akan otomatis berkurang." style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="eskul_id" value="{{ $eskul->id }}">
                            <input type="hidden" name="date" value="{{ $rd->date }}">
                            <button type="submit" class="btn-del-date" title="Hapus Data Tanggal Ini">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- 5. Main Content Card (Desktop Table + Mobile Symmetrical Cards) -->
    <div class="recap-content-card">
        <div class="card-header-bar">
            <h3 class="card-title-text">
                <i class="fas fa-user-check" style="color: #059669;"></i>
                <span>Daftar Kehadiran Siswa</span>
            </h3>
            <span style="font-size: 0.8rem; font-weight: 700; color: #64748b;">
                Menampilkan <strong>{{ $totalStudents }}</strong> Siswa
            </span>
        </div>

        <!-- A. Desktop View Table (Screens > 768px) -->
        <table class="recap-desktop-table">
            <thead>
                <tr>
                    <th width="6%">No</th>
                    <th width="36%" class="text-left">Nama Siswa</th>
                    <th width="14%">Kelas</th>
                    <th width="10%">Hadir</th>
                    <th width="10%">Sakit</th>
                    <th width="10%">Izin</th>
                    <th width="10%">Alpa</th>
                    <th width="14%">Tingkat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                @php
                    $studentTotal = $student->h + $student->s + $student->i + $student->a;
                    $studentRate = $studentTotal > 0 ? round(($student->h / $studentTotal) * 100) : 0;
                @endphp
                <tr>
                    <td>
                        <span style="font-weight: 800; color: #64748b;">{{ $index + 1 }}</span>
                    </td>
                    <td class="text-left">
                        <strong style="color: #0f172a; font-size: 0.92rem;">{{ $student->name }}</strong>
                    </td>
                    <td>
                        <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 8px; font-weight: 750; font-size: 0.82rem;">
                            {{ $student->class }}
                        </span>
                    </td>
                    <td>
                        <span class="recap-pill-stat pill-stat-h">{{ $student->h }}</span>
                    </td>
                    <td>
                        <span class="recap-pill-stat pill-stat-s">{{ $student->s }}</span>
                    </td>
                    <td>
                        <span class="recap-pill-stat pill-stat-i">{{ $student->i }}</span>
                    </td>
                    <td>
                        <span class="recap-pill-stat pill-stat-a">{{ $student->a }}</span>
                    </td>
                    <td>
                        <span class="rate-badge {{ $studentRate >= 80 ? 'rate-high' : ($studentRate >= 60 ? 'rate-mid' : 'rate-low') }}">
                            {{ $studentRate }}%
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 2.5rem; color: #94a3b8; font-weight: 600;">
                        <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                        Belum ada data siswa untuk ekstrakurikuler ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- B. Mobile Symmetrical Card View (Screens <= 768px) -->
        <div class="recap-mobile-cards">
            @forelse($students as $index => $student)
            @php
                $studentTotal = $student->h + $student->s + $student->i + $student->a;
                $studentRate = $studentTotal > 0 ? round(($student->h / $studentTotal) * 100) : 0;
            @endphp
            <div class="student-recap-card">
                <!-- Top Row: Number, Name, Class & Rate Badge -->
                <div class="student-card-top">
                    <div class="student-id-wrapper">
                        <div class="student-idx-num">#{{ $index + 1 }}</div>
                        <div class="student-name-box">
                            <span class="student-name-text">{{ $student->name }}</span>
                            <span class="student-class-tag">
                                <i class="fas fa-graduation-cap"></i> Kelas {{ $student->class }}
                            </span>
                        </div>
                    </div>
                    <div class="rate-badge {{ $studentRate >= 80 ? 'rate-high' : ($studentRate >= 60 ? 'rate-mid' : 'rate-low') }}">
                        {{ $studentRate }}%
                    </div>
                </div>

                <!-- 4 Symmetrical Micro Stats Grid -->
                <div class="student-stats-grid">
                    <div class="stat-micro-box stat-box-h">
                        <span class="stat-micro-num">{{ $student->h }}</span>
                        <span class="stat-micro-lbl">Hadir</span>
                    </div>
                    <div class="stat-micro-box stat-box-s">
                        <span class="stat-micro-num">{{ $student->s }}</span>
                        <span class="stat-micro-lbl">Sakit</span>
                    </div>
                    <div class="stat-micro-box stat-box-i">
                        <span class="stat-micro-num">{{ $student->i }}</span>
                        <span class="stat-micro-lbl">Izin</span>
                    </div>
                    <div class="stat-micro-box stat-box-a">
                        <span class="stat-micro-num">{{ $student->a }}</span>
                        <span class="stat-micro-lbl">Alpa</span>
                    </div>
                </div>

                <!-- Progress Bar Track -->
                <div class="rate-bar-container">
                    <div class="rate-progress-track">
                        <div class="rate-progress-fill {{ $studentRate >= 80 ? 'fill-high' : ($studentRate >= 60 ? 'fill-mid' : 'fill-low') }}" style="width: {{ $studentRate }}%;"></div>
                    </div>
                    <span class="rate-percent-text">{{ $studentRate }}%</span>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 2rem 1rem; color: #94a3b8; font-weight: 600;">
                <i class="fas fa-inbox" style="font-size: 2.2rem; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                Belum ada data siswa untuk ekstrakurikuler ini.
            </div>
            @endforelse
        </div>

    </div>

</div>

<!-- ========================================================
     B. PRATINJAU MODAL DI LAYAR (SEBELUM DICETAK)
     ======================================================== -->
<div id="printPreviewModal" class="print-preview-modal-backdrop no-print" onclick="if(event.target === this) closePrintPreview();">
    <div class="print-preview-dialog">
        <div class="print-preview-topbar">
            <div style="font-weight: 800; font-size: 0.95rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-alt" style="color: #34d399;"></i>
                <span>Pratinjau Lembar Cetak Resmi (Standar 1 Lembar A4)</span>
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="button" onclick="window.print()" style="background: #10b981; color: white; border: none; padding: 6px 14px; border-radius: 6px; font-weight: 700; font-size: 0.82rem; cursor: pointer;">
                    <i class="fas fa-print"></i> Cetak Sekarang
                </button>
                <button type="button" onclick="closePrintPreview()" style="background: #475569; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 700; font-size: 0.82rem; cursor: pointer;">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
        <div class="print-preview-scrollbody">
            <div class="preview-paper-sheet">
                @include('attendance.partials.official_print_content')
            </div>
        </div>
    </div>
</div>

<!-- ========================================================
     C. SATU-SATUNYA DOKUMEN CETAK 1 LEMBAR (KHUSUS MODE PRINT)
     ======================================================== -->
<div class="official-print-document">
    @include('attendance.partials.official_print_content')
</div>

<script>
    function openPrintPreview() {
        const modal = document.getElementById('printPreviewModal');
        if (modal) modal.classList.add('active');
    }

    function closePrintPreview() {
        const modal = document.getElementById('printPreviewModal');
        if (modal) modal.classList.remove('active');
    }
</script>
@endsection
