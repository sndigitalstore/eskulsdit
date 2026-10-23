@extends('layouts.app')

@section('title', 'Kepatuhan Pembina')
@section('page-title', 'Kepatuhan Administrasi Pembina')

@push('styles')
<style>
    /* Container Utama */
    .compliance-container {
        max-width: 1250px;
        margin: 0 auto;
        padding-bottom: 50px;
    }

    /* 1. Hero / Header Banner */
    .compliance-hero-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white;
        padding: 24px 28px;
        border-radius: 20px;
        margin-bottom: 22px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 18px;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .hero-badge-shield {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        border: 1px solid rgba(52, 211, 153, 0.35);
    }

    .hero-year-chip {
        font-size: 0.84rem;
        color: #cbd5e1;
        font-weight: 600;
    }

    .compliance-hero-title {
        font-size: 1.65rem;
        font-weight: 800;
        margin: 8px 0 0 0;
        color: #f8fafc;
        letter-spacing: -0.4px;
    }

    .compliance-hero-desc {
        margin: 6px 0 0 0;
        color: #94a3b8;
        font-size: 0.92rem;
        line-height: 1.5;
    }

    .btn-hero-reminder {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        padding: 12px 22px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 0.92rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 6px 18px rgba(220, 38, 38, 0.4);
        transition: all 0.25s ease;
        white-space: nowrap;
        text-decoration: none;
    }

    .btn-hero-reminder:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(220, 38, 38, 0.5);
        color: white;
    }

    /* 2. Mode Selector Tabs */
    .mode-tabs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, max-content));
        gap: 12px;
        margin-bottom: 22px;
    }

    .mode-tab-link {
        padding: 12px 22px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 0.92rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .mode-tab-link.active-today {
        background: #0284c7;
        color: white;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        border: 1px solid transparent;
    }

    .mode-tab-link.active-cumulative {
        background: #0f172a;
        color: white;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.35);
        border: 1px solid transparent;
    }

    .mode-tab-link.inactive {
        background: white;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .mode-tab-link.inactive:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .tab-badge-live {
        font-size: 0.72rem;
        padding: 2px 8px;
        border-radius: 999px;
        background: rgba(255,255,255,0.25);
        font-weight: 700;
    }

    /* 3. Stat Summary Cards */
    .stat-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 18px;
        margin-bottom: 22px;
    }

    .stat-card-box {
        padding: 18px 20px;
        border-radius: 18px;
        background: white;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 14px rgba(0,0,0,0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-sizing: border-box;
    }

    .stat-card-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    }

    .stat-card-blue { border-left: 5px solid #0284c7; }
    .stat-card-green { border-left: 5px solid #10b981; }
    .stat-card-red { border-left: 5px solid #ef4444; }

    .stat-body-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-title-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .stat-value-num {
        font-size: 1.85rem;
        font-weight: 800;
        margin: 4px 0 0 0;
        line-height: 1.1;
    }

    .stat-icon-wrapper {
        width: 46px;
        height: 46px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .stat-subtext-note {
        margin-top: 8px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* 4. Filter Toolbar */
    .toolbar-filter-card {
        padding: 18px 22px;
        border-radius: 18px;
        margin-bottom: 22px;
        background: white;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .toolbar-flex-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .status-pills-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .status-filter-pill {
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 0.86rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .pill-all-active { background: #1e293b; color: white; }
    .pill-all-inactive { background: #f1f5f9; color: #64748b; }

    .pill-action-active { background: #dc2626; color: white; }
    .pill-action-inactive { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    .pill-complete-active { background: #059669; color: white; }
    .pill-complete-inactive { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }

    .date-filter-group {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .date-input-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        padding: 6px 12px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
    }

    .date-input-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #475569;
        margin: 0;
        white-space: nowrap;
    }

    .date-picker-input {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #0f172a;
        outline: none;
        cursor: pointer;
    }

    .checkbox-scheduled-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.84rem;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
        user-select: none;
        white-space: nowrap;
    }

    .search-filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-input-box {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-size: 0.88rem;
        outline: none;
        width: 230px;
        transition: all 0.2s;
    }

    .search-input-box:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }

    .btn-search-submit {
        padding: 8px 16px;
        background: #0284c7;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-search-submit:hover {
        background: #0369a1;
    }

    .btn-search-reset {
        padding: 8px 12px;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 10px;
        font-size: 0.88rem;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.2s;
    }

    .btn-search-reset:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* 5. Main Table Card */
    .compliance-table-card {
        padding: 0;
        border-radius: 20px;
        overflow: hidden;
        background: white;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }

    .compliance-table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .compliance-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.9rem;
    }

    .compliance-table th {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        color: #475569;
        padding: 16px 20px;
        font-weight: 800;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .compliance-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .compliance-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .compliance-table tbody tr:hover {
        background-color: #fbfcfe;
    }

    /* Status Pills */
    .pill-status-done {
        background: #ecfdf5;
        color: #047857;
        font-size: 0.78rem;
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .pill-status-pending {
        background: #fef2f2;
        color: #dc2626;
        font-size: 0.78rem;
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .pill-status-warning {
        background: #fef3c7;
        color: #d97706;
        font-size: 0.78rem;
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .pill-status-info {
        background: #eff6ff;
        color: #2563eb;
        font-size: 0.78rem;
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .badge-compliance-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .tag-action-needed {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .tag-complete {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .btn-contact-wa {
        background: #10b981;
        color: white;
        padding: 8px 14px;
        font-size: 0.8rem;
        font-weight: 700;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-contact-wa:hover {
        background: #059669;
        transform: translateY(-2px);
        color: white;
    }

    /* Helper container for mobile-only parts in table */
    .mobile-stat-boxes {
        display: none;
    }

    /* ========================================================================= */
    /* RESPONSIVE DESIGN (TABLET & MOBILE)                                       */
    /* ========================================================================= */
    @media (max-width: 992px) {
        .compliance-hero-banner {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-hero-reminder {
            width: 100%;
            justify-content: center;
        }

        .toolbar-flex-container {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input-box {
            width: 100%;
        }

        .search-filter-group {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .compliance-container {
            padding: 0 2px 40px 2px;
        }

        /* 1. Hero Section */
        .compliance-hero-banner {
            padding: 16px 18px;
            border-radius: 16px;
            margin-bottom: 14px;
            gap: 12px;
        }

        .compliance-hero-title {
            font-size: 1.25rem;
            margin-top: 6px;
        }

        .compliance-hero-desc {
            font-size: 0.82rem;
            margin-top: 4px;
        }

        .btn-hero-reminder {
            width: 100%;
            height: 40px;
            font-size: 0.84rem;
            border-radius: 10px;
            justify-content: center;
            box-sizing: border-box;
        }

        /* 2. Symmetrical Mode Tabs (50% / 50%) */
        .mode-tabs-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 14px;
        }

        .mode-tab-link {
            padding: 10px 8px;
            font-size: 0.78rem;
            border-radius: 10px;
            justify-content: center;
            text-align: center;
            gap: 6px;
            min-height: 42px;
        }

        /* 3. Symmetrical 3-Column Micro KPI Grid */
        .stat-summary-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            margin-bottom: 14px;
        }

        .stat-card-box {
            padding: 10px 6px;
            border-radius: 12px;
            border-left-width: 3px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80px;
        }

        .stat-body-flex {
            flex-direction: column;
            align-items: center;
            gap: 2px;
            width: 100%;
        }

        .stat-icon-wrapper {
            display: none; /* Hide heavy icon box to give number and label full breathing room */
        }

        .stat-value-num {
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1;
            margin: 0;
        }

        .stat-title-label {
            font-size: 0.64rem;
            font-weight: 700;
            line-height: 1.2;
            margin-top: 4px;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .stat-subtext-note {
            display: none !important; /* Hide long subtext on phone for 100% height symmetry */
        }

        /* 4. Toolbar Filter Card */
        .toolbar-filter-card {
            padding: 12px 14px;
            border-radius: 14px;
            margin-bottom: 14px;
        }

        .status-pills-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            width: 100%;
        }

        .status-filter-pill {
            padding: 0 4px;
            height: 36px;
            font-size: 0.75rem;
            border-radius: 8px;
            justify-content: center;
            text-align: center;
            box-sizing: border-box;
            gap: 4px;
        }

        .date-filter-group {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
            width: 100%;
        }

        .date-input-wrapper {
            width: 100%;
            box-sizing: border-box;
            justify-content: space-between;
        }

        .date-picker-input {
            flex: 1;
        }

        .search-filter-group {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 6px;
            width: 100%;
        }

        .search-input-box {
            height: 38px;
            font-size: 0.85rem;
            padding: 6px 10px;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .btn-search-submit {
            height: 38px;
            padding: 0 14px;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .btn-search-reset {
            height: 38px;
            padding: 0 10px;
            border-radius: 8px;
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* 5. Transform Table into Modern Symmetrical Mobile Cards without Name Clipping */
        .compliance-table-card {
            border-radius: 16px;
        }

        .compliance-table-responsive {
            overflow: visible;
        }

        .compliance-table,
        .compliance-table thead,
        .compliance-table tbody,
        .compliance-table th,
        .compliance-table td,
        .compliance-table tr {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }

        .compliance-table thead {
            display: none;
        }

        .compliance-table tbody {
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .compliance-table tbody tr {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 14px 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            gap: 10px;
            position: relative;
        }

        .compliance-table tbody tr.row-needs-action {
            border-color: #fca5a5;
            background: #fffdfd;
        }

        .compliance-table tbody tr.row-complete {
            border-color: #86efac;
            background: #ffffff;
        }

        .compliance-table td {
            padding: 0 !important;
            border: none !important;
        }

        /* Top Row of Card: Eskul, Nama Pembina Lengkap, Status Badge */
        .card-header-mobile {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
        }

        .card-header-info {
            flex: 1;
            min-width: 0; /* Prevents overflow truncation! */
        }

        .eskul-title-bold {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 3px;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        /* Nama Pembina Utuh tanpa terpotong */
        .pembina-name-line {
            font-size: 0.88rem;
            font-weight: 700;
            color: #334155;
            display: flex;
            align-items: flex-start;
            gap: 6px;
            line-height: 1.35;
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: normal !important;
        }

        .pembina-name-line i {
            color: #6366f1;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .pembina-meta-row {
            font-size: 0.78rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 4px;
        }

        .pembina-meta-row span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Status Badge pojok kanan atas */
        .card-header-badge {
            flex-shrink: 0;
            align-self: flex-start;
        }

        /* Symmetrical 2/3 Column Mini Boxes for Attendance */
        .mobile-stat-boxes {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            width: 100%;
        }

        .mobile-stat-boxes.three-cols {
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }

        .mini-stat-tile {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 8px 6px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .mini-tile-label {
            font-size: 0.66rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .mini-stat-tile .pill-status-done,
        .mini-stat-tile .pill-status-pending,
        .mini-stat-tile .pill-status-warning,
        .mini-stat-tile .pill-status-info {
            padding: 3px 8px;
            font-size: 0.7rem;
            gap: 3px;
        }

        /* Reasons container if action needed */
        .action-reasons-box {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.78rem;
            color: #b91c1c;
            line-height: 1.4;
        }

        .action-reasons-box ul {
            margin: 4px 0 0 0;
            padding-left: 18px;
        }

        /* Action Button */
        .card-action-bar-mobile {
            border-top: 1px dashed #e2e8f0;
            padding-top: 8px;
            margin-top: 2px;
            width: 100%;
        }

        .btn-contact-wa {
            width: 100%;
            height: 38px;
            font-size: 0.82rem;
            border-radius: 10px;
            justify-content: center;
            box-sizing: border-box;
        }

        /* Hide desktop table cells on mobile */
        .desktop-cell-hide {
            display: none !important;
        }

        /* Modal Dialog on Mobile */
        .custom-reminder-modal-dialog {
            max-width: 95% !important;
            margin: 10px !important;
            border-radius: 18px !important;
        }

        .reminder-modal-header {
            padding: 14px 16px !important;
        }

        .reminder-modal-body {
            padding: 14px 16px !important;
        }

        .reminder-pembina-card {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 8px !important;
        }

        .reminder-pembina-card .btn-contact-wa {
            width: 100%;
            height: 36px;
        }
    }
</style>
@endpush

@section('content')
<div class="compliance-container">
    
    <!-- 1. Hero / Header Section -->
    <div class="compliance-hero-banner">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                <span class="hero-badge-shield">
                    <i class="fas fa-shield-alt"></i> Pemantauan Disiplin Pembina
                </span>
                <span class="hero-year-chip">
                    Tahun Ajaran: <strong>{{ $activeYear ? $activeYear->name : '-' }}</strong> (Semester {{ $activeYear ? ($activeYear->active_semester ?? '1') : '-' }})
                </span>
            </div>
            <h2 class="compliance-hero-title">
                Kepatuhan Administrasi Pembina Eskul
            </h2>
            <p class="compliance-hero-desc">
                Pantau pelaksanaan absensi harian dan kelengkapan administrasi ekstrakurikuler secara langsung & otomatis.
            </p>
        </div>

        @if($actionNeededCount > 0)
        <!-- Tombol Utama: Ingatkan Pembina (Trigger Modal) -->
        <div>
            <button type="button" onclick="openReminderModal()" class="btn-hero-reminder">
                <i class="fab fa-whatsapp" style="font-size: 1.25rem;"></i>
                <span>Ingatkan Pembina ({{ $actionNeededCount }} {{ $viewMode === 'today' ? 'Belum Absen' : 'Belum Lengkap' }})</span>
            </button>
        </div>
        @endif
    </div>

    <!-- 2. Mode Selector Tabs: Hari Ini vs Akumulatif (Symmetrical 50/50 on Mobile) -->
    <div class="mode-tabs-grid">
        <a href="{{ route('compliance.index', ['mode' => 'today', 'date' => $selectedDate, 'status' => $statusFilter, 'search' => $searchKeyword, 'only_scheduled' => $onlyScheduled ? 1 : 0]) }}" 
           class="mode-tab-link {{ $viewMode === 'today' ? 'active-today' : 'inactive' }}">
            <i class="fas fa-calendar-day"></i>
            <span>Jadwal Hari Ini</span>
            <span class="tab-badge-live">Live</span>
        </a>

        <a href="{{ route('compliance.index', ['mode' => 'cumulative', 'status' => $statusFilter, 'search' => $searchKeyword]) }}" 
           class="mode-tab-link {{ $viewMode === 'cumulative' ? 'active-cumulative' : 'inactive' }}">
            <i class="fas fa-history"></i>
            <span>Akumulasi 30 Hari</span>
        </a>
    </div>

    <!-- 3. Stat Summary Cards (3 Symmetrical Micro Cards on Mobile) -->
    <div class="stat-summary-grid">
        <!-- Card 1: Total Eskul -->
        <div class="stat-card-box stat-card-blue">
            <div class="stat-body-flex">
                <div>
                    <span class="stat-title-label">
                        {{ $viewMode === 'today' ? 'Eskul Hari Ini' : 'Total Eskul' }}
                    </span>
                    <h3 class="stat-value-num" style="color: #0284c7;">{{ $totalEskuls }}</h3>
                </div>
                <div class="stat-icon-wrapper" style="background: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-basketball-ball"></i>
                </div>
            </div>
            @if($viewMode === 'today')
            <div class="stat-subtext-note" style="color: #64748b;">
                <i class="far fa-clock"></i> Sesi: <strong>{{ $fullDateIndo }}</strong>
            </div>
            @endif
        </div>

        <!-- Card 2: Tertib / Lengkap -->
        <div class="stat-card-box stat-card-green">
            <div class="stat-body-flex">
                <div>
                    <span class="stat-title-label">
                        {{ $viewMode === 'today' ? 'Tertib / Hadir' : 'Lengkap Patuh' }}
                    </span>
                    <h3 class="stat-value-num" style="color: #047857;">{{ $completeCount }}</h3>
                </div>
                <div class="stat-icon-wrapper" style="background: #ecfdf5; color: #10b981;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-subtext-note" style="color: #059669; font-weight: 600;">
                <i class="fas fa-shield-alt"></i> {{ $viewMode === 'today' ? 'Pembina sudah absen' : 'Administrasi beres' }}
            </div>
        </div>

        <!-- Card 3: Belum Absen / Butuh Tindakan -->
        <div class="stat-card-box stat-card-red">
            <div class="stat-body-flex">
                <div>
                    <span class="stat-title-label">
                        {{ $viewMode === 'today' ? 'Belum Absen' : 'Butuh Tindakan' }}
                    </span>
                    <h3 class="stat-value-num" style="color: #dc2626;">{{ $actionNeededCount }}</h3>
                </div>
                <div class="stat-icon-wrapper" style="background: #fef2f2; color: #ef4444;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <div class="stat-subtext-note" style="color: #dc2626; font-weight: 600;">
                <i class="fas fa-bell"></i> Perlu diingatkan ke pembina
            </div>
        </div>
    </div>

    <!-- 4. Filter & Search Toolbar Section -->
    <div class="toolbar-filter-card">
        <form method="GET" action="{{ route('compliance.index') }}" id="complianceFilterForm">
            <input type="hidden" name="mode" value="{{ $viewMode }}">
            
            <div class="toolbar-flex-container">
                
                <!-- Status Pills -->
                <div class="status-pills-row">
                    <a href="{{ route('compliance.index', ['mode' => $viewMode, 'date' => $selectedDate, 'status' => 'all', 'search' => $searchKeyword, 'only_scheduled' => $onlyScheduled ? 1 : 0]) }}" 
                       class="status-filter-pill {{ $statusFilter === 'all' ? 'pill-all-active' : 'pill-all-inactive' }}">
                        Semua ({{ $totalEskuls }})
                    </a>
                    
                    <a href="{{ route('compliance.index', ['mode' => $viewMode, 'date' => $selectedDate, 'status' => 'action_needed', 'search' => $searchKeyword, 'only_scheduled' => $onlyScheduled ? 1 : 0]) }}" 
                       class="status-filter-pill {{ $statusFilter === 'action_needed' ? 'pill-action-active' : 'pill-action-inactive' }}">
                        <i class="fas fa-bell"></i> {{ $viewMode === 'today' ? 'Belum Absen' : 'Tindakan' }} ({{ $actionNeededCount }})
                    </a>
                    
                    <a href="{{ route('compliance.index', ['mode' => $viewMode, 'date' => $selectedDate, 'status' => 'complete', 'search' => $searchKeyword, 'only_scheduled' => $onlyScheduled ? 1 : 0]) }}" 
                       class="status-filter-pill {{ $statusFilter === 'complete' ? 'pill-complete-active' : 'pill-complete-inactive' }}">
                        <i class="fas fa-check-double"></i> {{ $viewMode === 'today' ? 'Tertib' : 'Lengkap' }} ({{ $completeCount }})
                    </a>
                </div>

                <!-- Date Picker & Scheduled Toggle (Khusus Mode Today) -->
                @if($viewMode === 'today')
                <div class="date-filter-group">
                    <div class="date-input-wrapper">
                        <label for="dateFilter" class="date-input-label">
                            <i class="fas fa-calendar-alt text-primary"></i> Sesi:
                        </label>
                        <input type="date" id="dateFilter" name="date" value="{{ $selectedDate }}" onchange="document.getElementById('complianceFilterForm').submit();"
                               class="date-picker-input">
                    </div>

                    <label class="checkbox-scheduled-label">
                        <input type="checkbox" name="only_scheduled" value="1" {{ $onlyScheduled ? 'checked' : '' }} onchange="document.getElementById('complianceFilterForm').submit();" style="width: 16px; height: 16px; accent-color: #0284c7;">
                        <span>Hanya eskul terjadwal</span>
                    </label>
                </div>
                @endif

                <!-- Search Input -->
                <div class="search-filter-group">
                    <input type="text" name="search" value="{{ $searchKeyword }}" placeholder="Cari eskul atau pembina..." 
                           class="search-input-box">
                    <button type="submit" class="btn-search-submit" title="Cari Data">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(!empty($searchKeyword) || $statusFilter !== 'all' || ($viewMode === 'today' && $selectedDate !== now()->toDateString()))
                        <a href="{{ route('compliance.index', ['mode' => $viewMode]) }}" class="btn-search-reset" title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    <!-- 5. Main Table & Mobile Responsive Cards -->
    <div class="compliance-table-card">
        <div class="compliance-table-responsive">
            <table class="compliance-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Ekstrakurikuler & Pembina</th>
                        <th style="width: 16%; text-align: center;">
                            {{ $viewMode === 'today' ? 'Absensi Siswa' : 'Absen Siswa (30 Hari)' }}
                        </th>
                        <th style="width: 16%; text-align: center;">
                            {{ $viewMode === 'today' ? 'Presensi Pembina' : 'Presensi Guru' }}
                        </th>
                        @if($viewMode === 'cumulative')
                        <th style="width: 14%; text-align: center;">Nilai Siswa</th>
                        @endif
                        <th style="width: 14%;">Status Kepatuhan</th>
                        <th style="width: 10%; text-align: center;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complianceData as $item)
                        @php
                            $esk = $item['eskul'];
                            $pembina = $item['pembina'];
                            $pembinaName = $pembina ? $pembina->name : ($esk->instructor_name ?? 'Belum ditentukan');
                            $needsAction = $item['needs_action'];
                            $reasons = $item['action_reasons'];
                            
                            // WhatsApp Contact URL Generator
                            $phoneClean = $pembina && $pembina->phone ? preg_replace('/[^0-9]/', '', $pembina->phone) : null;
                            if ($phoneClean && str_starts_with($phoneClean, '0')) {
                                $phoneClean = '62' . substr($phoneClean, 1);
                            }
                            $waUrl = $phoneClean ? "https://api.whatsapp.com/send?phone={$phoneClean}&text=" . rawurlencode($item['wa_message']) : null;
                        @endphp
                        <tr class="{{ $needsAction ? 'row-needs-action' : 'row-complete' }}">
                            
                            <!-- Kolom 1: Eskul & Pembina (Desktop & Header Card di Mobile) -->
                            <td>
                                <!-- Tampilan Mobile: Header Card Lengkap (Nama Guru tidak terpotong) -->
                                <div class="card-header-mobile">
                                    <div class="card-header-info">
                                        <div class="eskul-title-bold">{{ $esk->name }}</div>
                                        <div class="pembina-name-line">
                                            <i class="fas fa-user-tie"></i>
                                            <span>{{ $pembinaName }}</span>
                                        </div>
                                        <div class="pembina-meta-row">
                                            @if($pembina && $pembina->phone)
                                                <span style="color: #059669; font-weight: 600;">
                                                    <i class="fab fa-whatsapp"></i> {{ $pembina->phone }}
                                                </span>
                                            @endif
                                            <span>
                                                <i class="far fa-clock"></i> {{ $esk->schedule ?? 'Jadwal belum diset' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Status Kepatuhan Badge (Pojok Kanan Atas di HP) -->
                                    <div class="card-header-badge">
                                        @if($viewMode === 'today')
                                            @if(!$item['missing_teacher_attendance'])
                                                <span class="badge-compliance-tag tag-complete">
                                                    <i class="fas fa-check-circle"></i> TERTIB
                                                </span>
                                            @else
                                                <span class="badge-compliance-tag tag-action-needed">
                                                    <i class="fas fa-bell"></i> PERLU TINDAKAN
                                                </span>
                                            @endif
                                        @else
                                            @if($needsAction)
                                                <span class="badge-compliance-tag tag-action-needed">
                                                    <i class="fas fa-bell"></i> PERLU TINDAKAN
                                                </span>
                                            @else
                                                <span class="badge-compliance-tag tag-complete">
                                                    <i class="fas fa-check-double"></i> LENGKAP
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <!-- Tampilan Khusus Mobile: Kotak Mini Stat Presensi Simetris -->
                                <div class="mobile-stat-boxes {{ $viewMode === 'cumulative' ? 'three-cols' : '' }}" style="margin-top: 10px;">
                                    <!-- Mini Tile: Absensi Siswa -->
                                    <div class="mini-stat-tile">
                                        <span class="mini-tile-label">Siswa</span>
                                        @if($item['student_attendance_complete'])
                                            <span class="pill-status-done"><i class="fas fa-check-circle"></i> Sudah</span>
                                        @else
                                            <span class="pill-status-pending"><i class="fas fa-times-circle"></i> Belum</span>
                                        @endif
                                    </div>

                                    <!-- Mini Tile: Presensi Pembina -->
                                    <div class="mini-stat-tile">
                                        <span class="mini-tile-label">Pembina</span>
                                        @if(!$item['missing_teacher_attendance'])
                                            <span class="pill-status-done"><i class="fas fa-check-circle"></i> Hadir</span>
                                        @else
                                            <span class="pill-status-warning"><i class="fas fa-exclamation-circle"></i> Belum</span>
                                        @endif
                                    </div>

                                    <!-- Mini Tile: Nilai (Khusus Akumulatif) -->
                                    @if($viewMode === 'cumulative')
                                    <div class="mini-stat-tile">
                                        <span class="mini-tile-label">Nilai</span>
                                        @if($item['grades_complete'])
                                            <span class="pill-status-done"><i class="fas fa-star"></i> Lengkap</span>
                                        @else
                                            @php $ungraded = max(0, $item['enrolled_students_count'] - $item['graded_students_count']); @endphp
                                            <span class="pill-status-info"><i class="fas fa-edit"></i> {{ $ungraded }}</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                <!-- Catatan Kekurangan di Mobile jika butuh tindakan -->
                                @if($needsAction && !empty($reasons))
                                <div class="action-reasons-box" style="margin-top: 8px;">
                                    <strong style="font-size: 0.76rem; display: block; margin-bottom: 2px;">
                                        <i class="fas fa-exclamation-circle"></i> Perlu Dilengkapi:
                                    </strong>
                                    <ul>
                                        @foreach($reasons as $reason)
                                            <li>{{ $reason }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- Tombol Aksi di Mobile (Full Width Simetris) -->
                                <div class="card-action-bar-mobile">
                                    @if($waUrl)
                                        <a href="{{ $waUrl }}" target="_blank" class="btn-contact-wa" title="Hubungi Pembina via WhatsApp">
                                            <i class="fab fa-whatsapp" style="font-size: 1rem;"></i>
                                            <span>Hubungi Pembina (WhatsApp)</span>
                                        </a>
                                    @elseif($needsAction)
                                        <div style="font-size: 0.78rem; color: #94a3b8; font-style: italic; text-align: center; padding: 6px;">
                                            <i class="fas fa-phone-slash"></i> No. WhatsApp pembina belum terdaftar
                                        </div>
                                    @else
                                        <div style="font-size: 0.8rem; color: #059669; font-weight: 700; text-align: center; padding: 6px;">
                                            <i class="fas fa-check-double"></i> Administrasi Lengkap & Tertib
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Kolom 2: Absensi Siswa (Desktop Only) -->
                            <td class="desktop-cell-hide" style="text-align: center;">
                                @if($item['student_attendance_complete'])
                                    <span class="pill-status-done">
                                        <i class="fas fa-check-circle"></i> Sudah Diisi
                                    </span>
                                @else
                                    <span class="pill-status-pending">
                                        <i class="fas fa-times-circle"></i> Belum Diisi
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom 3: Absensi Guru Pembina (Desktop Only) -->
                            <td class="desktop-cell-hide" style="text-align: center;">
                                @if(!$item['missing_teacher_attendance'])
                                    <span class="pill-status-done">
                                        <i class="fas fa-check-circle"></i> Sudah Absen
                                    </span>
                                @else
                                    <span class="pill-status-warning">
                                        <i class="fas fa-exclamation-circle"></i> Belum Absen
                                    </span>
                                @endif
                            </td>

                            @if($viewMode === 'cumulative')
                            <!-- Kolom 4: Nilai Siswa (Mode Akumulatif - Desktop Only) -->
                            <td class="desktop-cell-hide" style="text-align: center;">
                                @if($item['grades_complete'])
                                    <span class="pill-status-done">
                                        <i class="fas fa-star"></i> Lengkap
                                    </span>
                                @else
                                    @php $ungraded = max(0, $item['enrolled_students_count'] - $item['graded_students_count']); @endphp
                                    <span class="pill-status-info">
                                        <i class="fas fa-edit"></i> {{ $ungraded }} Belum
                                    </span>
                                @endif
                            </td>
                            @endif

                            <!-- Kolom 5: Status Kepatuhan & Rincian (Desktop Only) -->
                            <td class="desktop-cell-hide">
                                @if($viewMode === 'today')
                                    @if(!$item['missing_teacher_attendance'])
                                        <div class="badge-compliance-tag tag-complete">
                                            <i class="fas fa-check-circle"></i> SUDAH ABSEN
                                        </div>
                                        @if($item['student_attendance_complete'])
                                            <div style="font-size: 0.78rem; color: #059669; margin-top: 4px; font-weight: 600;">
                                                <i class="fas fa-check-double"></i> Siswa & pembina lengkap
                                            </div>
                                        @else
                                            <div style="font-size: 0.78rem; color: #64748b; margin-top: 4px;">
                                                <i class="fas fa-info-circle text-primary"></i> Absensi siswa belum
                                            </div>
                                        @endif
                                    @else
                                        <div class="badge-compliance-tag tag-action-needed" style="margin-bottom: 4px;">
                                            <i class="fas fa-bell"></i> BUTUH TINDAKAN
                                        </div>
                                        <ul style="margin: 4px 0 0 0; padding-left: 18px; font-size: 0.78rem; color: #475569; line-height: 1.4;">
                                            <li>Absen Pembina belum tercatat</li>
                                            @if(!$item['student_attendance_complete'])
                                                <li>Absen Siswa belum diisi</li>
                                            @endif
                                        </ul>
                                    @endif
                                @else
                                    @if($needsAction)
                                        <div class="badge-compliance-tag tag-action-needed" style="margin-bottom: 4px;">
                                            <i class="fas fa-bell"></i> BUTUH TINDAKAN
                                        </div>
                                        <ul style="margin: 4px 0 0 0; padding-left: 18px; font-size: 0.78rem; color: #475569; line-height: 1.4;">
                                            @foreach($reasons as $reason)
                                                <li>{{ $reason }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="badge-compliance-tag tag-complete">
                                            <i class="fas fa-check-double"></i> TERTIB / LENGKAP
                                        </div>
                                        <div style="font-size: 0.78rem; color: #64748b; margin-top: 4px;">
                                            Administrasi terpenuhi
                                        </div>
                                    @endif
                                @endif
                            </td>

                            <!-- Kolom 6: Tindakan Kontak Pembina (Desktop Only) -->
                            <td class="desktop-cell-hide" style="text-align: center;">
                                @if($waUrl)
                                    <a href="{{ $waUrl }}" target="_blank" class="btn-contact-wa" title="Hubungi Pembina via WhatsApp">
                                        <i class="fab fa-whatsapp"></i> Hubungi
                                    </a>
                                @elseif($needsAction)
                                    <span style="font-size: 0.78rem; color: #94a3b8; font-style: italic; font-weight: 600;">
                                        No. WA kosong
                                    </span>
                                @else
                                    <span style="font-size: 0.8rem; color: #10b981; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-check-circle"></i> Tertib
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $viewMode === 'cumulative' ? 6 : 5 }}" style="padding: 40px 20px; text-align: center; color: #94a3b8;">
                                <i class="fas fa-calendar-check" style="font-size: 2.5rem; margin-bottom: 12px; color: #cbd5e1; display: block;"></i>
                                <span style="font-weight: 700; font-size: 1rem; color: #475569; display: block; margin-bottom: 4px;">
                                    Tidak ada data yang cocok dengan kriteria filter.
                                </span>
                                @if($viewMode === 'today' && $onlyScheduled)
                                    <span style="font-size: 0.85rem; color: #64748b;">
                                        Mungkin tidak ada kegiatan eskul yang dijadwalkan pada hari ini ({{ $fullDateIndo }}). Coba nonaktifkan centang <em>"Hanya eskul terjadwal"</em>.
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: INGATKAN PEMBINA (REKAP DAFTAR & BROADCAST WHATSAPP)                 -->
<!-- ========================================================================= -->
<div id="reminderModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); z-index: 99999; backdrop-filter: blur(5px); justify-content: center; align-items: center; padding: 16px;">
    <div class="custom-reminder-modal-dialog" style="background: white; border-radius: 20px; width: 100%; max-width: 760px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid #e2e8f0; animation: modalFadeIn 0.25s ease-out;">
        
        <!-- Modal Header -->
        <div class="reminder-modal-header" style="padding: 22px 26px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: #fafafa; border-radius: 20px 20px 0 0;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0;">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: #0f172a;">
                        Ingatkan Pembina Eskul
                    </h3>
                    <p style="margin: 3px 0 0 0; font-size: 0.82rem; color: #64748b;">
                        Pelaksanaan: <strong>{{ $fullDateIndo }}</strong> • Total: <strong style="color: #dc2626;">{{ $actionNeededList->count() }} Pembina {{ $viewMode === 'today' ? 'Belum Absen' : 'Belum Lengkap' }}</strong>
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeReminderModal()" style="background: none; border: none; font-size: 1.6rem; color: #94a3b8; cursor: pointer; padding: 4px 8px; line-height: 1;" title="Tutup">&times;</button>
        </div>

        <div class="reminder-modal-body" style="padding: 22px 26px;">
            
            <!-- Box 1: Draf Pesan Siaran WhatsApp (Untuk Grup Guru / Pembina) -->
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 16px; margin-bottom: 22px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 10px;">
                    <span style="font-size: 0.88rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-bullhorn" style="color: #0284c7;"></i> Draf Siaran WhatsApp (Broadcast Grup)
                    </span>
                    <button type="button" onclick="copyBroadcastText()" id="btnCopyBroadcast" 
                            style="background: #0284c7; color: white; border: none; padding: 6px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                        <i class="far fa-copy"></i> <span id="btnCopyText">Salin Teks</span>
                    </button>
                </div>
                
                <textarea id="broadcastTextArea" readonly rows="6" 
                          style="width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-family: monospace; font-size: 0.83rem; background: white; color: #334155; resize: vertical; line-height: 1.5; box-sizing: border-box;">{{ $broadcastMessage }}</textarea>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; flex-wrap: wrap; gap: 8px;">
                    <span style="font-size: 0.76rem; color: #64748b;">
                        <i class="fas fa-info-circle"></i> Sudah memuat salam, nama eskul, nama pembina, dan kekurangan.
                    </span>
                    @php
                        $waBroadcastUrl = "https://api.whatsapp.com/send?text=" . rawurlencode($broadcastMessage);
                    @endphp
                    <a href="{{ $waBroadcastUrl }}" target="_blank" 
                       style="background: #10b981; color: white; padding: 6px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fab fa-whatsapp"></i> Buka WhatsApp
                    </a>
                </div>
            </div>

            <!-- Box 2: Daftar Pembina & Chat WA Personal (Japri) -->
            <div>
                <h4 style="margin: 0 0 12px 0; font-size: 0.98rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-users-cog" style="color: #6366f1;"></i> Daftar Pembina yang Perlu Dihubungi Pribadi (Japri):
                </h4>

                @if($actionNeededList->isEmpty())
                    <div style="background: #ecfdf5; color: #047857; padding: 16px; border-radius: 12px; text-align: center; font-weight: 700; font-size: 0.92rem;">
                        <i class="fas fa-check-circle" style="font-size: 1.2rem; vertical-align: middle; margin-right: 6px;"></i>
                        Luar biasa! Tidak ada pembina yang belum melengkapi administrasi pada sesi ini.
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 10px; max-height: 280px; overflow-y: auto; padding-right: 4px;">
                        @foreach($actionNeededList as $act)
                            @php
                                $pmb = $act['pembina'];
                                $esk = $act['eskul'];
                                $pmbName = $pmb ? $pmb->name : ($esk->instructor_name ?? 'Belum ditentukan');
                                $phoneClean = $pmb && $pmb->phone ? preg_replace('/[^0-9]/', '', $pmb->phone) : null;
                                if ($phoneClean && str_starts_with($phoneClean, '0')) {
                                    $phoneClean = '62' . substr($phoneClean, 1);
                                }
                                $personalWaUrl = $phoneClean ? "https://api.whatsapp.com/send?phone={$phoneClean}&text=" . rawurlencode($act['wa_message']) : null;
                            @endphp
                            <div class="reminder-pembina-card" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: #fff5f5; border: 1px solid #fed7d7; border-radius: 12px; gap: 10px; box-sizing: border-box;">
                                <div style="min-width: 0; flex: 1;">
                                    <div style="font-weight: 800; color: #1e293b; font-size: 0.92rem; word-break: break-word; overflow-wrap: break-word;">
                                        {{ $esk->name }} <span style="font-weight: 600; color: #64748b;">— {{ $pmbName }}</span>
                                    </div>
                                    <div style="display: flex; gap: 6px; margin-top: 4px; flex-wrap: wrap;">
                                        @if(!$act['student_attendance_complete'])
                                            <span style="font-size: 0.72rem; background: #fee2e2; color: #dc2626; padding: 2px 7px; border-radius: 6px; font-weight: 700;">
                                                <i class="fas fa-times-circle"></i> Absen Siswa
                                            </span>
                                        @endif
                                        @if($act['missing_teacher_attendance'])
                                            <span style="font-size: 0.72rem; background: #fef3c7; color: #b45309; padding: 2px 7px; border-radius: 6px; font-weight: 700;">
                                                <i class="fas fa-exclamation-circle"></i> Absen Pembina
                                            </span>
                                        @endif
                                        @if($pmb && $pmb->phone)
                                            <span style="font-size: 0.72rem; color: #059669; font-weight: 600;">
                                                <i class="fab fa-whatsapp"></i> {{ $pmb->phone }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div style="flex-shrink: 0;">
                                    @if($personalWaUrl)
                                        <a href="{{ $personalWaUrl }}" target="_blank" 
                                           style="background: #10b981; color: white; padding: 7px 14px; font-size: 0.8rem; font-weight: 700; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);">
                                            <i class="fab fa-whatsapp"></i> Chat Japri
                                        </a>
                                    @else
                                        <span style="font-size: 0.74rem; color: #94a3b8; font-style: italic;">
                                            No WA Kosong
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Modal Footer -->
        <div style="padding: 14px 26px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; border-radius: 0 0 20px 20px;">
            <button type="button" onclick="closeReminderModal()" style="padding: 8px 18px; background: #e2e8f0; color: #475569; border: none; border-radius: 10px; font-weight: 700; font-size: 0.86rem; cursor: pointer;">
                Tutup
            </button>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function openReminderModal() {
        const modal = document.getElementById('reminderModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeReminderModal() {
        const modal = document.getElementById('reminderModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    function copyBroadcastText() {
        const textArea = document.getElementById('broadcastTextArea');
        if (!textArea) return;

        textArea.select();
        textArea.setSelectionRange(0, 99999); // Mobile support

        navigator.clipboard.writeText(textArea.value).then(() => {
            const btnText = document.getElementById('btnCopyText');
            const originalText = btnText.innerText;
            btnText.innerText = 'Tersalin! ✓';
            const btn = document.getElementById('btnCopyBroadcast');
            btn.style.background = '#10b981';

            setTimeout(() => {
                btnText.innerText = originalText;
                btn.style.background = '#0284c7';
            }, 2500);
        }).catch(err => {
            alert('Gagal menyalin teks, silakan salin secara manual.');
        });
    }

    // Close on outside click
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('reminderModal');
        if (event.target === modal) {
            closeReminderModal();
        }
    });

    // Close on Escape key
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeReminderModal();
        }
    });
</script>
<style>
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.96);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
@endpush
@endsection
