@extends('layouts.app')

@section('title', 'Ujian Hafalan Akhir Semester - Tahfidz')

@section('content')
<style>
    /* DESKTOP BASE STYLES */
    .exam-header-card {
        background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0f766e 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: white;
        margin-bottom: 18px;
        box-shadow: 0 10px 25px -5px rgba(6, 95, 70, 0.3);
    }
    .semester-pill-nav {
        display: inline-flex;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 30px;
        padding: 4px;
        gap: 4px;
    }
    .semester-pill-btn {
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.82rem;
        color: white;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .semester-pill-btn.active {
        background: white;
        color: #065f46;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    /* KPI STATS GRID */
    .kpi-exam-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }
    .kpi-exam-card {
        background: white;
        border-radius: 14px;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .kpi-exam-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* SEARCH & STUDENT SELECTION CARD */
    .student-search-selection-card {
        background: #ffffff;
        border: 1.5px solid #a7f3d0;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 18px;
        box-shadow: 0 4px 16px rgba(5, 150, 105, 0.06);
    }
    .search-selection-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
    }
    .search-selection-title-box {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .search-selection-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #ecfdf5;
        border: 1.5px solid #a7f3d0;
        color: #047857;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    .search-selection-title {
        margin: 0 0 2px 0;
        font-size: 1rem;
        font-weight: 800;
        color: #064e3b;
    }
    .search-selection-subtitle {
        margin: 0;
        font-size: 0.78rem;
        color: #64748b;
    }
    .student-filter-pills {
        display: inline-flex;
        background: #f1f5f9;
        border-radius: 30px;
        padding: 3px;
        gap: 4px;
    }
    .filter-pill {
        border: none;
        background: transparent;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.76rem;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .filter-pill.active {
        background: #047857;
        color: white;
        box-shadow: 0 2px 6px rgba(4, 120, 87, 0.3);
    }

    /* SEARCHABLE DROPDOWN TRIGGER */
    .custom-student-dropdown {
        position: relative;
    }
    .student-dropdown-trigger {
        width: 100%;
        background: #f8fafc;
        border: 2px solid #cbd5e1;
        border-radius: 12px;
        padding: 10px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
    }
    .student-dropdown-trigger:hover,
    .student-dropdown-trigger.open {
        background: #ffffff;
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
    }
    .trigger-left {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow: hidden;
    }
    .trigger-placeholder {
        font-weight: 700;
        color: #334155;
        font-size: 0.92rem;
    }
    .trigger-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .badge-active-group {
        background: #d1fae5;
        color: #065f46;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 800;
    }

    /* POPUP DROPDOWN MENU */
    .student-dropdown-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: white;
        border-radius: 14px;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        z-index: 110;
        display: none;
        overflow: hidden;
    }
    .student-dropdown-menu.show {
        display: block;
    }
    .dropdown-search-box {
        padding: 10px 14px;
        border-bottom: 1px solid #e2e8f0;
        position: relative;
        display: flex;
        align-items: center;
    }
    .dropdown-search-icon {
        position: absolute;
        left: 24px;
        color: #94a3b8;
        font-size: 0.85rem;
    }
    .dropdown-search-input {
        width: 100%;
        padding: 8px 32px 8px 34px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #0f172a;
        outline: none;
    }
    .clear-search-btn {
        position: absolute;
        right: 24px;
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0;
    }
    .dropdown-options-list {
        max-height: 300px;
        overflow-y: auto;
    }
    .dropdown-student-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 14px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: background 0.15s ease;
        gap: 10px;
    }
    .dropdown-student-item:hover {
        background: #f0fdf4;
    }
    .dropdown-student-item.selected {
        background: #ecfdf5;
        border-left: 4px solid #047857;
    }
    .item-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #e2e8f0;
        color: #475569;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        flex-shrink: 0;
    }
    .dropdown-student-item.is-saved .item-avatar {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }
    .item-info {
        flex: 1;
        min-width: 0;
    }
    .item-name {
        font-weight: 700;
        font-size: 0.88rem;
        color: #0f172a;
        margin-bottom: 1px;
    }
    .item-meta {
        font-size: 0.72rem;
        color: #64748b;
    }
    .badge-status-sudah {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
        padding: 2px 7px;
        border-radius: 5px;
        font-size: 0.7rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .badge-status-belum {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
        padding: 2px 7px;
        border-radius: 5px;
        font-size: 0.7rem;
        font-weight: 700;
        white-space: nowrap;
    }

    /* ACTIVE FOCUSED STUDENT WORKSPACE CARD */
    .focused-student-card {
        background: white;
        border-radius: 16px;
        border: 2px solid #059669;
        box-shadow: 0 8px 24px rgba(5, 150, 105, 0.08);
        margin-bottom: 20px;
        overflow: hidden;
    }
    .focused-student-header {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        padding: 16px 20px;
        border-bottom: 1.5px solid #a7f3d0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .focused-student-nav {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-nav-student {
        background: white;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s ease;
    }
    .btn-nav-student:hover:not(:disabled) {
        background: #047857;
        border-color: #047857;
        color: white;
    }
    .btn-nav-student:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* SEMESTER 1 COMPLETED ACCORDION */
    .sem1-history-box {
        background: #f0fdf4;
        border: 1.5px dashed #86efac;
        border-radius: 12px;
        padding: 12px 16px;
        margin: 14px 20px 0 20px;
    }

    /* SURAH TABLE - DESKTOP DEFAULT */
    .surah-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }
    .surah-table th {
        background: #f8fafc;
        padding: 10px 12px;
        font-weight: 700;
        color: #475569;
        text-align: left;
        border-bottom: 1.5px solid #e2e8f0;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .surah-table td {
        padding: 9px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .input-exam-score {
        width: 80px;
        padding: 6px 8px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-weight: 800;
        font-size: 1rem;
        text-align: center;
        color: #0f172a;
        transition: all 0.2s ease;
    }
    .input-exam-score:focus {
        border-color: #059669;
        outline: none;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }
    .badge-auto-juz {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1.5px solid #bfdbfe;
        font-weight: 800;
        font-size: 0.78rem;
        padding: 5px 10px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }
    .badge-grade {
        padding: 3px 9px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 0.74rem;
        display: inline-block;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }
    .grade-A { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .grade-B { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
    .grade-C { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .grade-D { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    .btn-add-surah {
        background: #ecfdf5;
        color: #059669;
        border: 1.5px dashed #a7f3d0;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .btn-add-surah:hover {
        background: #d1fae5;
        border-color: #059669;
    }

    /* ACTION BAR FOCUSED STUDENT */
    .focused-action-bar {
        background: #f8fafc;
        padding: 14px 20px;
        border-top: 1.5px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .btn-save-focused {
        background: #047857;
        color: white;
        border: none;
        padding: 9px 18px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(4, 120, 87, 0.25);
        transition: all 0.2s ease;
    }
    .btn-save-focused:hover {
        background: #065f46;
    }
    .btn-save-next {
        background: #0284c7;
        color: white;
        border: none;
        padding: 9px 18px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        transition: all 0.2s ease;
    }
    .btn-save-next:hover {
        background: #0369a1;
    }

    /* ==========================================================================
       RESPONSIVE OPTIMIZATION FOR MOBILE / SMARTPHONES (MAX-WIDTH: 768px)
       ========================================================================== */
    @media (max-width: 768px) {
        /* Prevents auto-zoom in mobile Safari / Chrome */
        input, select, textarea {
            font-size: 16px !important;
        }

        /* 1. Header Card */
        .exam-header-card {
            padding: 14px 16px !important;
            border-radius: 14px !important;
            margin-bottom: 12px !important;
        }
        .exam-header-card h1 {
            font-size: 1.2rem !important;
            margin: 4px 0 2px 0 !important;
        }
        .exam-header-desc {
            display: none !important;
        }
        .header-top-wrap {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 10px !important;
        }
        .semester-pill-nav {
            width: 100% !important;
            display: flex !important;
        }
        .semester-pill-btn {
            flex: 1 !important;
            justify-content: center !important;
            padding: 7px 4px !important;
            font-size: 0.78rem !important;
        }
        .header-filter-bar {
            margin-top: 10px !important;
            padding-top: 10px !important;
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 8px !important;
        }
        .header-filter-controls {
            display: flex !important;
            flex-direction: column !important;
            gap: 6px !important;
            width: 100% !important;
        }
        .header-filter-controls select {
            width: 100% !important;
            padding: 8px 10px !important;
            font-size: 14px !important;
        }
        .header-actions-row {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
        }

        /* 2. Compact KPI Summary Bar on Mobile */
        .kpi-exam-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8px !important;
            margin-bottom: 12px !important;
        }
        .kpi-exam-card {
            padding: 9px 10px !important;
            gap: 8px !important;
            border-radius: 10px !important;
        }
        .kpi-exam-icon {
            width: 34px !important;
            height: 34px !important;
            font-size: 1rem !important;
            border-radius: 8px !important;
        }
        .kpi-label {
            font-size: 0.65rem !important;
        }
        .kpi-val {
            font-size: 1.1rem !important;
        }

        /* 3. Search & Student Selector Card on Mobile */
        .student-search-selection-card {
            padding: 12px 14px !important;
            margin-bottom: 12px !important;
            border-radius: 14px !important;
        }
        .search-selection-header {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 8px !important;
            margin-bottom: 10px !important;
        }
        .search-selection-icon {
            display: none !important;
        }
        .search-selection-subtitle {
            display: none !important;
        }
        .student-filter-pills {
            width: 100% !important;
            display: flex !important;
        }
        .filter-pill {
            flex: 1 !important;
            text-align: center !important;
            justify-content: center !important;
            padding: 6px 4px !important;
            font-size: 0.74rem !important;
        }
        .student-dropdown-trigger {
            padding: 10px 14px !important;
        }
        .trigger-placeholder {
            font-size: 0.88rem !important;
        }

        /* 4. Active Student Workspace on Mobile */
        .focused-student-card {
            border-radius: 14px !important;
            margin-bottom: 78px !important; /* Safety margin for sticky action bar */
        }
        .focused-student-header {
            padding: 12px 14px !important;
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 10px !important;
        }
        .identity-header-left {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
        }
        .identity-avatar-box {
            width: 40px !important;
            height: 40px !important;
            font-size: 1.1rem !important;
            border-radius: 10px !important;
        }
        .identity-title-name {
            font-size: 1.05rem !important;
        }
        .focused-student-nav {
            width: 100% !important;
            justify-content: space-between !important;
            border-top: 1px dashed #a7f3d0 !important;
            padding-top: 8px !important;
        }
        .btn-nav-student {
            padding: 6px 14px !important;
            font-size: 0.78rem !important;
        }

        .student-subbar {
            padding: 10px 14px !important;
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 8px !important;
        }
        .subbar-date-group {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
        }
        .subbar-avg-group {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
            background: #f8fafc !important;
            padding: 6px 10px !important;
            border-radius: 8px !important;
            border: 1px solid #e2e8f0 !important;
        }
        .student-body-area {
            padding: 12px 12px 115px 12px !important;
            box-sizing: border-box !important;
        }

        .sem1-history-box {
            margin: 10px 12px 0 12px !important;
            padding: 10px 12px !important;
            box-sizing: border-box !important;
        }

        /* 5. TOUCH-FRIENDLY SURAH CARDS (RESPONSIF KARTU FLEKSIBEL DI HP - TIDAK ADA KONTEN TERPOTONG) */
        .surah-table thead {
            display: none !important;
        }
        .surah-table, 
        .surah-table tbody {
            display: block !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        .surah-row-card {
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            gap: 8px !important;
            background: #ffffff !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 12px !important;
            padding: 10px 12px !important;
            margin-bottom: 12px !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03) !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        .surah-row-card td {
            display: block !important;
            border: none !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }

        /* Baris 1: Nomor, Input Nama Surat, Tombol Hapus */
        .col-num {
            order: 1 !important;
            flex: 0 0 28px !important;
            width: 28px !important;
            height: 38px !important;
            background: #f1f5f9 !important;
            color: #475569 !important;
            font-weight: 800 !important;
            font-size: 0.85rem !important;
            border-radius: 8px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .col-surah {
            order: 2 !important;
            flex: 1 1 calc(100% - 78px) !important;
            min-width: 0 !important;
            box-sizing: border-box !important;
        }
        .col-surah input {
            width: 100% !important;
            height: 38px !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            box-sizing: border-box !important;
            padding: 6px 10px !important;
        }
        .col-action {
            order: 3 !important;
            flex: 0 0 32px !important;
            width: 32px !important;
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-end !important;
        }
        .col-action button {
            width: 32px !important;
            height: 38px !important;
            border-radius: 8px !important;
        }

        /* Baris 2: Badge Juz (Kiri), Input Nilai (Tengah), Predikat (Kanan) */
        .col-juz {
            order: 4 !important;
            flex: 0 0 auto !important;
            display: flex !important;
            align-items: center !important;
        }
        .badge-auto-juz {
            background: #dbeafe !important;
            color: #1e40af !important;
            border: 1.5px solid #93c5fd !important;
            font-weight: 800 !important;
            font-size: 0.8rem !important;
            padding: 6px 10px !important;
            border-radius: 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            white-space: nowrap !important;
        }
        .col-score {
            order: 5 !important;
            flex: 1 1 auto !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 5px !important;
        }
        .col-score::before {
            content: "Nilai:";
            font-size: 0.78rem;
            font-weight: 700;
            color: #475569;
        }
        .input-exam-score {
            width: 72px !important;
            height: 36px !important;
            font-size: 1.15rem !important;
            font-weight: 900 !important;
            border: 2px solid #059669 !important;
            border-radius: 8px !important;
            background: white !important;
            text-align: center !important;
            box-sizing: border-box !important;
            padding: 2px 4px !important;
        }
        .col-pred {
            order: 6 !important;
            flex: 0 0 auto !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-end !important;
        }
        .col-pred .item-pred-badge {
            font-size: 0.75rem !important;
            font-weight: 800 !important;
            padding: 6px 9px !important;
            border-radius: 6px !important;
            white-space: nowrap !important;
        }

        /* Baris 3: Catatan Singkat Full Width */
        .col-notes {
            order: 7 !important;
            flex: 0 0 100% !important;
            width: 100% !important;
            margin-top: 2px !important;
            box-sizing: border-box !important;
        }
        .col-notes input {
            width: 100% !important;
            height: 36px !important;
            font-size: 13px !important;
            box-sizing: border-box !important;
            padding: 6px 10px !important;
        }

        /* 6. Mobile Add Surah & Notes */
        .table-bottom-controls {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 10px !important;
        }
        .btn-add-surah {
            width: 100% !important;
            justify-content: center !important;
            padding: 10px !important;
            font-size: 0.85rem !important;
        }
        .general-notes-wrap {
            width: 100% !important;
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .general-notes-wrap input {
            width: 100% !important;
            min-width: 0 !important;
            padding: 8px 10px !important;
        }

        /* 7. Sticky Thumb-Friendly Bottom Bar on Mobile */
        .focused-action-bar {
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 999 !important;
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(10px) !important;
            border-top: 2px solid #10b981 !important;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.12) !important;
            padding: 10px 14px calc(10px + env(safe-area-inset-bottom, 0px)) 14px !important;
            margin: 0 !important;
            flex-direction: row !important;
            gap: 8px !important;
        }
        .action-bar-info {
            display: none !important;
        }
        .action-bar-buttons {
            width: 100% !important;
            display: flex !important;
            gap: 8px !important;
        }
        .btn-save-focused {
            flex: 1 !important;
            justify-content: center !important;
            padding: 12px 8px !important;
            font-size: 0.84rem !important;
        }
        .btn-save-next {
            flex: 1 !important;
            justify-content: center !important;
            padding: 12px 8px !important;
            font-size: 0.84rem !important;
        }
    }
</style>

<div class="container-fluid" style="padding: 0 12px; max-width: 1300px; margin: 0 auto;">

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div style="background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; padding: 12px 16px; border-radius: 10px; margin-bottom: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; font-size: 0.88rem;">
            <i class="fas fa-check-circle" style="font-size: 1.1rem; color: #10b981;"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; font-size: 0.88rem;">
            <i class="fas fa-exclamation-circle" style="font-size: 1.1rem; color: #ef4444;"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- HEADER CARD --}}
    <div class="exam-header-card">
        <div class="header-top-wrap" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 14px;">
            <div>
                <span style="background: rgba(255, 255, 255, 0.2); padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fas fa-quran"></i> Evaluasi Akhir Semester Halaqah
                </span>
                <h1 style="font-weight: 900; margin: 6px 0 2px 0; color: white;">
                    Ujian Hafalan Semester (Tahfidz)
                </h1>
                <p class="exam-header-desc" style="margin: 0; font-size: 0.84rem; opacity: 0.9; max-width: 720px; line-height: 1.4;">
                    Data surat otomatis terisi sesuai capaian tuntas santri pada semester ini. Nomor Juz otomatis mengikuti surat.
                </p>
            </div>

            {{-- TOMBOL FILTER SEMESTER & CETAK --}}
            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                <div class="semester-pill-nav">
                    <a href="{{ route('tahfidz.semester-exam.index', ['group_id' => $activeGroup->id, 'year_id' => $selectedYearId, 'semester' => '1']) }}" 
                       class="semester-pill-btn {{ $selectedSemester === '1' ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Sem 1 (Ganjil)
                    </a>
                    <a href="{{ route('tahfidz.semester-exam.index', ['group_id' => $activeGroup->id, 'year_id' => $selectedYearId, 'semester' => '2']) }}" 
                       class="semester-pill-btn {{ $selectedSemester === '2' ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i> Sem 2 (Genap)
                    </a>
                </div>
            </div>
        </div>

        {{-- FILTER BAR KELOMPOK, TAHUN AJARAN & CETAK --}}
        <div class="header-filter-bar" style="margin-top: 14px; padding-top: 12px; border-top: 1px solid rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div class="header-filter-controls" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <select onchange="window.location.href=this.value" style="padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.4); background: white; color: #1e293b; font-weight: 700; font-size: 0.82rem; cursor: pointer;">
                    @foreach($myGroups as $grp)
                        <option value="{{ route('tahfidz.semester-exam.index', ['group_id' => $grp->id, 'year_id' => $selectedYearId, 'semester' => $selectedSemester]) }}" {{ $activeGroup->id === $grp->id ? 'selected' : '' }}>
                            Kelompok: {{ $grp->name }} ({{ $grp->students->count() }} Santri)
                        </option>
                    @endforeach
                </select>

                <select onchange="window.location.href=this.value" style="padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.4); background: white; color: #1e293b; font-weight: 700; font-size: 0.82rem; cursor: pointer;">
                    @foreach($academicYears as $y)
                        <option value="{{ route('tahfidz.semester-exam.index', ['group_id' => $activeGroup->id, 'year_id' => $y->id, 'semester' => $selectedSemester]) }}" {{ $selectedYearId === $y->id ? 'selected' : '' }}>
                            TA {{ $y->name }} {{ $y->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="header-actions-row">
                <span class="header-mentor-info" style="font-size: 0.8rem; opacity: 0.95;">
                    <i class="fas fa-user-tie"></i> Musyrif: <strong>{{ $activeGroup->mentorUser?->name ?? $activeGroup->mentor_name ?? '-' }}</strong>
                </span>

                <a href="{{ route('tahfidz.semester-exam.print', ['group_id' => $activeGroup->id, 'year_id' => $selectedYearId, 'semester' => $selectedSemester]) }}" 
                   target="_blank" 
                   style="background: white; color: #065f46; font-weight: 800; padding: 6px 12px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; font-size: 0.76rem; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                    <i class="fas fa-print"></i> Cetak A4
                </a>
            </div>
        </div>
    </div>

    {{-- KPI STATS GRID (COMPACT ON MOBILE) --}}
    <div class="kpi-exam-grid">
        <div class="kpi-exam-card">
            <div class="kpi-exam-icon" style="background: #e0f2fe; color: #0284c7;">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <div class="kpi-label" style="font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Santri</div>
                <div class="kpi-val" style="font-size: 1.25rem; font-weight: 900; color: #0f172a;">{{ $students->count() }} <span style="font-size: 0.72rem; font-weight: 600; color: #64748b;">Orang</span></div>
            </div>
        </div>

        <div class="kpi-exam-card">
            <div class="kpi-exam-icon" style="background: #ecfdf5; color: #059669;">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <div class="kpi-label" style="font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Dievaluasi</div>
                <div class="kpi-val" style="font-size: 1.25rem; font-weight: 900; color: #059669;">
                    <span id="kpiEvaluatedCount">{{ $totalEvaluated }}</span> <span style="font-size: 0.72rem; font-weight: 600; color: #64748b;">/ {{ $students->count() }}</span>
                </div>
            </div>
        </div>

        <div class="kpi-exam-card">
            <div class="kpi-exam-icon" style="background: #fef3c7; color: #d97706;">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <div class="kpi-label" style="font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Rata Kelompok</div>
                <div class="kpi-val" style="font-size: 1.25rem; font-weight: 900; color: #b45309;" id="kpiGroupAverage">
                    {{ $groupAverage > 0 ? number_format($groupAverage, 1) : '-' }}
                </div>
            </div>
        </div>

        <div class="kpi-exam-card">
            <div class="kpi-exam-icon" style="background: #ede9fe; color: #7c3aed;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <div class="kpi-label" style="font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Periode</div>
                <div class="kpi-val" style="font-size: 0.92rem; font-weight: 800; color: #6d28d9; margin-top: 1px;">
                    Semester {{ $selectedSemester }}
                </div>
            </div>
        </div>
    </div>

    {{-- 1. KOTAK PENCARIAN & PILIH NAMA SANTRI --}}
    <div class="student-search-selection-card">
        <div class="search-selection-header">
            <div class="search-selection-title-box">
                <div class="search-selection-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div>
                    <h3 class="search-selection-title">Pencarian & Pilih Nama Santri</h3>
                    <p class="search-selection-subtitle">
                        Input ujian santri satu per satu. Nomor Juz otomatis terisi mengikuti surat yang dipilih.
                    </p>
                </div>
            </div>

            {{-- FILTER PILLS --}}
            <div class="student-filter-pills">
                <button type="button" class="filter-pill active" id="filter-pill-all" onclick="filterStudentList('all')">
                    Semua (<span id="count-pill-all">{{ $students->count() }}</span>)
                </button>
                <button type="button" class="filter-pill" id="filter-pill-belum" onclick="filterStudentList('belum')">
                    Belum (<span id="count-pill-belum">{{ max(0, $students->count() - $totalEvaluated) }}</span>)
                </button>
                <button type="button" class="filter-pill" id="filter-pill-sudah" onclick="filterStudentList('sudah')">
                    Sudah (<span id="count-pill-sudah">{{ $totalEvaluated }}</span>)
                </button>
            </div>
        </div>

        {{-- SEARCHABLE DROPDOWN TRIGGER --}}
        <div class="custom-student-dropdown" id="studentSearchDropdownWrap">
            <button type="button" class="student-dropdown-trigger" id="studentDropdownTrigger" onclick="toggleStudentDropdown(event)">
                <div class="trigger-left" id="triggerDisplayArea">
                    <i class="fas fa-user-graduate" style="color: #059669; font-size: 1.1rem; flex-shrink: 0;"></i>
                    <span class="trigger-placeholder" id="triggerPlaceholderText">-- Cari / Pilih Nama Santri --</span>
                </div>
                <div class="trigger-right">
                    <span class="badge-active-group">{{ $activeGroup->name }}</span>
                    <i class="fas fa-chevron-down" id="triggerChevron" style="color: #64748b; font-size: 0.8rem;"></i>
                </div>
            </button>

            {{-- MENU POPUP PENCARIAN --}}
            <div class="student-dropdown-menu" id="studentDropdownMenu">
                <div class="dropdown-search-box">
                    <i class="fas fa-search dropdown-search-icon"></i>
                    <input type="text" id="studentSearchField" class="dropdown-search-input" 
                           placeholder="Ketik nama santri atau kelas..." 
                           oninput="handleStudentSearchInput(this.value)" autocomplete="off">
                    <button type="button" class="clear-search-btn" id="clearSearchBtn" onclick="clearStudentSearchInput()" style="display: none;">&times;</button>
                </div>
                <div class="dropdown-options-list" id="studentDropdownOptionsList">
                    @foreach($students as $idx => $std)
                        @php
                            $exData = $studentsExamData[$std->id] ?? null;
                            $hasSaved = $exData && $exData['is_saved'];
                            $itemCount = count($exData['items'] ?? []);
                        @endphp
                        <div class="dropdown-student-item {{ $hasSaved ? 'is-saved' : '' }}" 
                             id="dropdown-item-{{ $std->id }}"
                             data-student-id="{{ $std->id }}"
                             data-name="{{ strtolower($std->name) }}"
                             data-class="{{ strtolower($std->class) }}"
                             data-status="{{ $hasSaved ? 'sudah' : 'belum' }}"
                             onclick="selectStudent({{ $std->id }})">
                            <div class="item-avatar" id="avatar-std-{{ $std->id }}">{{ $idx + 1 }}</div>
                            <div class="item-info">
                                <div class="item-name">{{ $std->name }}</div>
                                <div class="item-meta">
                                    Kelas {{ $std->class }} @if($std->nis)&bull; NIS: {{ $std->nis }}@endif
                                </div>
                            </div>
                            <div class="item-badge-status" id="dropdown-status-{{ $std->id }}">
                                @if($hasSaved)
                                    <span class="badge-status-sudah">
                                        <i class="fas fa-check-circle"></i> Sudah: <strong>{{ number_format($exData['average_score'], 1) }}</strong> ({{ $exData['predicate'] }})
                                    </span>
                                @else
                                    <span class="badge-status-belum">
                                        <i class="far fa-clock"></i> Belum Dinilai
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <div class="dropdown-empty-state" id="dropdownEmptyNotice" style="display: none; padding: 16px; text-align: center; color: #94a3b8; font-size: 0.85rem;">
                        <i class="fas fa-search"></i> Santri tidak ditemukan
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. WORKSPACE AREA PENILAIAN FOKUS SATU PER SATU SANTRI --}}
    <div id="studentWorkspaceContainer">
        @forelse($students as $index => $std)
            @php
                $examData = $studentsExamData[$std->id] ?? null;
                $isSaved = $examData && $examData['is_saved'];
                $items = $examData['items'] ?? [];
                $sem1Items = $examData['sem1_items'] ?? [];
                $avg = $examData['average_score'] ?? 0;
                $pred = $examData['predicate'] ?? 'Jayyid';
            @endphp

            <div class="focused-student-card" id="studentCard-{{ $std->id }}" style="display: none;" data-student-id="{{ $std->id }}" data-student-index="{{ $index }}">
                
                {{-- HEADER KARTU FOKUS SANTRI & NAVIGASI --}}
                <div class="focused-student-header">
                    <div class="identity-header-left">
                        <div class="identity-avatar-box" style="width: 44px; height: 44px; border-radius: 12px; background: {{ $isSaved ? '#dcfce7; color: #15803d; border: 1.5px solid #86efac;' : '#e0f2fe; color: #0284c7; border: 1.5px solid #bae6fd;' }} display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1.2rem;" id="cardAvatar-{{ $std->id }}">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                <h2 class="identity-title-name" style="font-size: 1.15rem; font-weight: 900; margin: 0; color: #0f172a;">{{ $std->name }}</h2>
                                <span style="background: #e2e8f0; color: #334155; padding: 2px 7px; border-radius: 5px; font-size: 0.72rem; font-weight: 800;">
                                    Kelas {{ $std->class }}
                                </span>
                            </div>
                            <div style="font-size: 0.75rem; margin-top: 3px;" id="cardStatusBadge-{{ $std->id }}">
                                @if($isSaved)
                                    <span style="color: #059669; font-weight: 800;"><i class="fas fa-check-circle"></i> Tersimpan (Dapat Diedit Kapan Saja)</span>
                                @else
                                    <span style="color: #d97706; font-weight: 800;"><i class="fas fa-clock"></i> Draft Belum Disimpan</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- NAVIGASI SEBELUMNYA / BERIKUTNYA --}}
                    <div class="focused-student-nav">
                        <button type="button" class="btn-nav-student" onclick="navigateStudent(-1)" {{ $index === 0 ? 'disabled' : '' }} title="Santri Sebelumnya">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </button>
                        <span style="font-size: 0.78rem; font-weight: 800; color: #475569; padding: 0 4px;">
                            {{ $index + 1 }} / {{ $students->count() }}
                        </span>
                        <button type="button" class="btn-nav-student" onclick="navigateStudent(1)" {{ $index === $students->count() - 1 ? 'disabled' : '' }} title="Santri Berikutnya">
                            Berikutnya <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                {{-- SUB HEADER INFO: TANGGAL UJIAN & LIVE AVERAGE --}}
                <div class="student-subbar" style="padding: 10px 20px; background: #ffffff; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <div class="subbar-date-group" style="display: flex; align-items: center; gap: 8px;">
                        <label style="font-size: 0.78rem; font-weight: 700; color: #475569;"><i class="fas fa-calendar-day" style="color: #059669;"></i> Tgl Ujian:</label>
                        <input type="date" id="examDate-{{ $std->id }}" value="{{ $examData['exam_date'] ?? date('Y-m-d') }}" style="padding: 5px 8px; border-radius: 6px; border: 1.5px solid #cbd5e1; font-size: 0.8rem; font-weight: 700; color: #0f172a;">
                    </div>

                    <div class="subbar-avg-group" style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 0.78rem; font-weight: 700; color: #64748b;">Rata-Rata:</span>
                        <span id="avgScoreDisplay-{{ $std->id }}" style="font-size: 1.15rem; font-weight: 900; color: #047857;">{{ $avg > 0 ? number_format($avg, 1) : '-' }}</span>
                        <span id="predBadgeDisplay-{{ $std->id }}" class="badge-grade grade-{{ $avg >= 90 ? 'A' : ($avg >= 80 ? 'B' : ($avg >= 70 ? 'C' : 'D')) }}">
                            {{ $pred }}
                        </span>
                    </div>
                </div>

                {{-- JIKA SEMESTER 2: RIWAYAT TUNTAS SEMESTER 1 (COLLAPSIBLE DI HP AGAR TIDAK RIBET) --}}
                @if($selectedSemester === '2')
                    @if(!empty($sem1Items))
                        <details class="sem1-history-box" style="cursor: pointer;">
                            <summary style="font-weight: 800; font-size: 0.82rem; color: #065f46; display: flex; align-items: center; justify-content: space-between; outline: none; list-style: none;">
                                <span><i class="fas fa-check-double text-emerald-600"></i> Riwayat Tuntas Semester 1 ({{ count($sem1Items) }} Surat)</span>
                                <span style="font-size: 0.7rem; background: #dcfce7; color: #166534; font-weight: 800; padding: 2px 7px; border-radius: 4px; border: 1px solid #86efac;">
                                    Tuntas &bull; Rincian ▾
                                </span>
                            </summary>
                            <div style="font-size: 0.74rem; color: #047857; margin: 8px 0 6px 0; line-height: 1.4;">
                                Surat di bawah telah lulus diujikan pada Semester 1 dan tersimpan permanen (tidak perlu diuji ulang):
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                @foreach($sem1Items as $s1)
                                    <div style="background: white; border: 1px solid #bbf7d0; border-radius: 6px; padding: 4px 8px; font-size: 0.74rem; color: #1e293b; display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-quran" style="color: #10b981; font-size: 0.7rem;"></i>
                                        <strong>{{ $s1['surah_name'] }}</strong>
                                        <span style="color: #64748b;">(Juz {{ $s1['juz'] }})</span>:
                                        <strong style="color: #047857;">{{ number_format($s1['score'], 0) }}</strong>
                                        <span class="badge-grade grade-{{ $s1['score'] >= 90 ? 'A' : ($s1['score'] >= 80 ? 'B' : ($s1['score'] >= 70 ? 'C' : 'D')) }}" style="font-size: 0.65rem; padding: 1px 5px;">
                                            {{ $s1['predicate'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    @endif
                @endif

                {{-- FORM DAFTAR SURAT DIUJI (RESPONSIF KARTU DI HP & TABEL DI DESKTOP) --}}
                <div class="student-body-area" style="padding: 16px 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 6px;">
                        <h4 style="margin: 0; font-size: 0.92rem; font-weight: 800; color: #0f172a;">
                            <i class="fas fa-list-check" style="color: #059669;"></i> Materi Surat Diuji (Semester {{ $selectedSemester }})
                        </h4>
                        <span style="font-size: 0.72rem; color: #64748b;">
                            * Juz otomatis terisi mengikuti surat
                        </span>
                    </div>

                    <table class="surah-table" id="tableSurahs-{{ $std->id }}">
                        <thead>
                            <tr>
                                <th style="width: 5%; text-align: center;">No</th>
                                <th style="width: 32%;">Nama Surat Diuji</th>
                                <th style="width: 16%;">Juz (Otomatis)</th>
                                <th style="width: 16%; text-align: center;">Nilai (0 - 100)</th>
                                <th style="width: 14%; text-align: center;">Predikat</th>
                                <th style="width: 13%;">Catatan Singkat</th>
                                <th style="width: 4%; text-align: center;">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="tbodySurahs-{{ $std->id }}">
                            @forelse($items as $iIndex => $item)
                                @php
                                    $rowJuz = \App\Http\Controllers\TahfidzAttendanceController::determineJuzForSurah($item['surah_name']);
                                @endphp
                                <tr id="row-{{ $std->id }}-{{ $iIndex }}" class="surah-row-card">
                                    <td class="col-num row-num" style="text-align: center; color: #64748b; font-weight: bold;">{{ $iIndex + 1 }}</td>
                                    <td class="col-surah">
                                        <input type="text" 
                                               list="surahDatalist" 
                                               id="surahInput-{{ $std->id }}-{{ $iIndex }}"
                                               value="{{ $item['surah_name'] }}" 
                                               class="form-control surah-name-input" 
                                               style="font-weight: 700; color: #0f172a; padding: 7px 10px; font-size: 0.88rem; border-radius: 8px; border: 1.5px solid #cbd5e1;" 
                                               required 
                                               placeholder="Pilih/Ketik Surat"
                                               oninput="onSurahNameChange(this, {{ $std->id }}, {{ $iIndex }})"
                                               onchange="onSurahNameChange(this, {{ $std->id }}, {{ $iIndex }})">
                                    </td>
                                    
                                    {{-- MIDDLE ZONE (JUZ, SCORE, PREDIKAT) --}}
                                    <td class="col-juz">
                                        <span class="badge-auto-juz" id="badgeJuz-{{ $std->id }}-{{ $iIndex }}">
                                            <i class="fas fa-bookmark"></i> Juz {{ $rowJuz ?: 30 }}
                                        </span>
                                        <input type="hidden" id="hiddenJuz-{{ $std->id }}-{{ $iIndex }}" value="{{ $rowJuz ?: 30 }}">
                                    </td>
                                    <td class="col-score" style="text-align: center;">
                                        <input type="number" 
                                               inputmode="decimal"
                                               id="scoreInput-{{ $std->id }}-{{ $iIndex }}"
                                               value="{{ $item['score'] }}" 
                                               min="0" max="100" step="0.5" 
                                               class="input-exam-score score-input-{{ $std->id }}" 
                                               oninput="calculateStudentAvg({{ $std->id }})" 
                                               required>
                                    </td>
                                    <td class="col-pred" style="text-align: center;">
                                        <span class="item-pred-badge badge-grade grade-{{ $item['score'] >= 90 ? 'A' : ($item['score'] >= 80 ? 'B' : ($item['score'] >= 70 ? 'C' : 'D')) }}" id="predBadge-{{ $std->id }}-{{ $iIndex }}">
                                            {{ $item['predicate'] ?? \App\Models\TahfidzSemesterExam::resolvePredicate((float)$item['score']) }}
                                        </span>
                                    </td>

                                    <td class="col-notes">
                                        <input type="text" 
                                               id="notesInput-{{ $std->id }}-{{ $iIndex }}"
                                               value="{{ $item['notes'] }}" 
                                               class="form-control" 
                                               style="font-size: 0.82rem; padding: 6px 10px; border-radius: 8px; border: 1.5px solid #cbd5e1;" 
                                               placeholder="Catatan singkat (opsional)">
                                    </td>
                                    <td class="col-action" style="text-align: center;">
                                        <button type="button" onclick="removeSurahRow({{ $std->id }}, {{ $iIndex }})" style="background: #fee2e2; color: #ef4444; border: none; width: 32px; height: 32px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;" title="Hapus Surat">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow-{{ $std->id }}">
                                    <td colspan="7" style="text-align: center; padding: 18px; color: #94a3b8; font-style: italic; font-size: 0.82rem;">
                                        Belum ada surat baru yang terdeteksi disetor pada Semester {{ $selectedSemester }}. Klik tombol <strong>+ Tambah Surat Diuji</strong> di bawah untuk memasukkan materi ujian santri.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="table-bottom-controls" style="margin-top: 12px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <button type="button" onclick="addSurahRow({{ $std->id }})" class="btn-add-surah">
                            <i class="fas fa-plus-circle"></i> Tambah Surat Diuji
                        </button>

                        <div class="general-notes-wrap" style="display: flex; align-items: center; gap: 8px;">
                            <label style="font-size: 0.76rem; font-weight: 700; color: #64748b; white-space: nowrap;">Catatan Musyrif:</label>
                            <input type="text" id="generalNotes-{{ $std->id }}" value="{{ $examData['notes'] ?? '' }}" placeholder="Catatan umum penguji (opsional)" class="form-control" style="font-size: 0.82rem; min-width: 260px; padding: 6px 10px; border-radius: 8px; border: 1.5px solid #cbd5e1;">
                        </div>
                    </div>
                </div>

                {{-- ACTION BAR BOTTOM UNTUK SANTRI FOKUS INI (STICKY DI HP) --}}
                <div class="focused-action-bar">
                    <div class="action-bar-info" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-info-circle" style="color: #059669; font-size: 1.1rem;"></i>
                        <span style="font-size: 0.8rem; color: #475569;">
                            Nilai tersimpan langsung ke rapor halaqah. Guru memiliki akses edit kapan saja.
                        </span>
                    </div>

                    <div class="action-bar-buttons" style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <button type="button" class="btn-save-focused" id="btnSaveSingle-{{ $std->id }}" onclick="saveStudentExam({{ $std->id }}, false)">
                            <i class="fas fa-save"></i> Simpan Nilai
                        </button>
                        @if($index < $students->count() - 1)
                            <button type="button" class="btn-save-next" id="btnSaveNext-{{ $std->id }}" onclick="saveStudentExam({{ $std->id }}, true)">
                                <i class="fas fa-arrow-right"></i> Simpan & Lanjut
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        @empty
            <div style="background: white; border-radius: 16px; padding: 40px; text-align: center; color: #64748b; border: 1.5px solid #e2e8f0;">
                <i class="fas fa-user-slash" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px;"></i>
                <h3 style="font-weight: 800; color: #0f172a;">Belum ada santri di kelompok ini</h3>
                <p>Silakan hubungi administrator untuk menambahkan santri ke kelompok binaan ini.</p>
            </div>
        @endforelse
    </div>

    {{-- DATALIST SURAH UNTUK AUTOCOMPLETE --}}
    <datalist id="surahDatalist">
        @foreach($allSurahs as $sName)
            <option value="{{ $sName }}">
        @endforeach
    </datalist>

</div>

{{-- JAVASCRIPT ENGINE UNTUK SEARCH, AUTO-JUZ, REAL-TIME CALCULATION & ONE-BY-ONE WORKSPACE --}}
<script>
    // Data Mappings
    const surahToJuzMap = @json($surahToJuzMap ?? []);
    const studentsList = [
        @foreach($students as $idx => $s)
            { id: {{ $s->id }}, name: @json($s->name), class: @json($s->class), isSaved: {{ ($studentsExamData[$s->id]['is_saved'] ?? false) ? 'true' : 'false' }} },
        @endforeach
    ];

    let currentActiveStudentId = null;
    let surahCounters = {};

    @foreach($students as $std)
        surahCounters[{{ $std->id }}] = {{ count($studentsExamData[$std->id]['items'] ?? []) }};
    @endforeach

    // Utility: Dapatkan nomor Juz otomatis dari nama surat
    function getJuzForSurah(surahName) {
        if (!surahName) return 30;
        const clean = surahName.trim().toLowerCase().replace(/[^a-z0-9]/g, '').replace(/^(?:surat|surah|qs)/, '');
        if (!clean) return 30;

        // 1. Cek langsung kata kunci surat di Juz 28 (Surat ke-58 s.d 66)
        if (clean.includes('hasyr') || clean.includes('hasr') || clean.includes('hasyir')) return 28;
        if (clean.includes('mujadil') || clean.includes('mujadalah')) return 28;
        if (clean.includes('mumtahan')) return 28;
        if (clean.includes('saff')) return 28;
        if (clean.includes('jumu') || clean.includes('jumat')) return 28;
        if (clean.includes('munafiq')) return 28;
        if (clean.includes('taghabun')) return 28;
        if (clean.includes('talaq') || clean.includes('thalaq')) return 28;
        if (clean.includes('tahrim')) return 28;

        // 2. Cek langsung kata kunci surat di Juz 29 (Surat ke-67 s.d 77)
        if (clean.includes('mulk') || clean.includes('tabar')) return 29;
        if (clean.includes('qalam') || clean.includes('nun')) return 29;
        if (clean.includes('haqqah')) return 29;
        if (clean.includes('maarij')) return 29;
        if (clean.includes('nuh')) return 29;
        if (clean.includes('jinn') || clean.includes('jin')) return 29;
        if (clean.includes('muzzammil') || clean.includes('muzammil')) return 29;
        if (clean.includes('muddassir') || clean.includes('muddatsir')) return 29;
        if (clean.includes('qiyamah')) return 29;
        if (clean.includes('insan') || clean.includes('addahr') || clean.includes('dahr')) return 29;
        if (clean.includes('mursalat')) return 29;

        // 3. Exact / Normalized match
        for (const [sName, juz] of Object.entries(surahToJuzMap)) {
            const sClean = sName.trim().toLowerCase().replace(/[^a-z0-9]/g, '').replace(/^(?:surat|surah|qs)/, '');
            if (sClean === clean) {
                return juz;
            }
        }
        // 4. Substring match
        for (const [sName, juz] of Object.entries(surahToJuzMap)) {
            const sClean = sName.trim().toLowerCase().replace(/[^a-z0-9]/g, '').replace(/^(?:surat|surah|qs)/, '');
            if (sClean.length >= 3 && clean.includes(sClean)) {
                return juz;
            }
        }
        return 30;
    }

    // Auto-update Juz badge & hidden input saat nama surat diketik / dipilih
    function onSurahNameChange(input, studentId, idx) {
        const surah = input.value.trim();
        const badge = document.getElementById(`badgeJuz-${studentId}-${idx}`);
        const hidden = document.getElementById(`hiddenJuz-${studentId}-${idx}`);
        if (!surah) {
            if (badge) badge.innerHTML = `<i class="fas fa-bookmark"></i> -`;
            if (hidden) hidden.value = '';
            return;
        }
        const juz = getJuzForSurah(surah);
        if (badge) {
            badge.innerHTML = `<i class="fas fa-bookmark"></i> Juz ${juz}`;
        }
        if (hidden) {
            hidden.value = juz;
        }
    }

    // Sinkronisasi badge Juz mengikuti nama surat yang ada di tampilan
    function syncAllJuzBadges(studentId = null) {
        const selector = studentId ? `#tbodySurahs-${studentId} tr.surah-row-card` : 'tr.surah-row-card';
        document.querySelectorAll(selector).forEach(row => {
            const surahInp = row.querySelector('.surah-name-input');
            const badge = row.querySelector('.badge-auto-juz');
            const hidden = row.querySelector('input[type="hidden"]');
            if (surahInp && surahInp.value.trim() !== '') {
                const correctJuz = getJuzForSurah(surahInp.value);
                if (badge) {
                    badge.innerHTML = `<i class="fas fa-bookmark"></i> Juz ${correctJuz}`;
                }
                if (hidden) {
                    hidden.value = correctJuz;
                }
            }
        });
    }

    // Resolve Nilai ke Predikat Mutu
    function resolvePredicateJs(score) {
        if (score >= 90) return { label: 'Mumtaz (A)', cls: 'grade-A' };
        if (score >= 80) return { label: 'Jayyid Jiddan (B)', cls: 'grade-B' };
        if (score >= 70) return { label: 'Jayyid (C)', cls: 'grade-C' };
        return { label: 'Maqbul (D)', cls: 'grade-D' };
    }

    // Hitung Live Rata-Rata Santri
    function calculateStudentAvg(studentId) {
        const tbody = document.getElementById('tbodySurahs-' + studentId);
        if (!tbody) return;

        const scoreInputs = tbody.querySelectorAll('.score-input-' + studentId);
        let total = 0;
        let count = 0;

        scoreInputs.forEach(inp => {
            const row = inp.closest('tr');
            const val = parseFloat(inp.value);
            const badge = row.querySelector('.item-pred-badge');

            if (!isNaN(val)) {
                total += val;
                count++;
                if (badge) {
                    const pred = resolvePredicateJs(val);
                    badge.textContent = pred.label;
                    badge.className = 'item-pred-badge badge-grade ' + pred.cls;
                }
            } else if (badge) {
                badge.textContent = '-';
                badge.className = 'item-pred-badge badge-grade';
            }
        });

        const avgEl = document.getElementById('avgScoreDisplay-' + studentId);
        const predEl = document.getElementById('predBadgeDisplay-' + studentId);

        if (count > 0) {
            const avg = total / count;
            const pred = resolvePredicateJs(avg);
            if (avgEl) avgEl.textContent = avg.toFixed(1);
            if (predEl) {
                predEl.textContent = pred.label;
                predEl.className = 'badge-grade ' + pred.cls;
            }
        } else {
            if (avgEl) avgEl.textContent = '-';
            if (predEl) {
                predEl.textContent = 'Belum Ada';
                predEl.className = 'badge-grade';
            }
        }
    }

    // Tambah Baris Surat Baru (Form dikosongkan agar guru mengisi manual)
    function addSurahRow(studentId) {
        const tbody = document.getElementById('tbodySurahs-' + studentId);
        const emptyRow = document.getElementById('emptyRow-' + studentId);
        if (emptyRow) emptyRow.remove();

        if (!surahCounters[studentId]) surahCounters[studentId] = 0;
        const idx = surahCounters[studentId]++;
        const rowNum = tbody.querySelectorAll('tr:not([id^="emptyRow"])').length + 1;

        const tr = document.createElement('tr');
        tr.id = `row-${studentId}-${idx}`;
        tr.className = 'surah-row-card';
        tr.innerHTML = `
            <td class="col-num row-num" style="text-align: center; color: #64748b; font-weight: bold;">${rowNum}</td>
            <td class="col-surah">
                <input type="text" 
                       list="surahDatalist" 
                       id="surahInput-${studentId}-${idx}"
                       value="" 
                       class="form-control surah-name-input" 
                       style="font-weight: 700; color: #0f172a; padding: 7px 10px; font-size: 0.88rem; border-radius: 8px; border: 1.5px solid #cbd5e1;" 
                       required 
                       placeholder="Pilih/Ketik Surat"
                       oninput="onSurahNameChange(this, ${studentId}, ${idx})"
                       onchange="onSurahNameChange(this, ${studentId}, ${idx})">
            </td>
            <td class="col-juz">
                <span class="badge-auto-juz" id="badgeJuz-${studentId}-${idx}">
                    <i class="fas fa-bookmark"></i> -
                </span>
                <input type="hidden" id="hiddenJuz-${studentId}-${idx}" value="">
            </td>
            <td class="col-score" style="text-align: center;">
                <input type="number" 
                       inputmode="decimal"
                       id="scoreInput-${studentId}-${idx}"
                       value="" 
                       min="0" max="100" step="0.5" 
                       class="input-exam-score score-input-${studentId}" 
                       placeholder="0-100"
                       oninput="calculateStudentAvg(${studentId})" 
                       required>
            </td>
            <td class="col-pred" style="text-align: center;">
                <span class="item-pred-badge badge-grade" id="predBadge-${studentId}-${idx}">-</span>
            </td>
            <td class="col-notes">
                <input type="text" 
                       id="notesInput-${studentId}-${idx}"
                       value="" 
                       class="form-control" 
                       style="font-size: 0.82rem; padding: 6px 10px; border-radius: 8px; border: 1.5px solid #cbd5e1;" 
                       placeholder="Catatan singkat (opsional)">
            </td>
            <td class="col-action" style="text-align: center;">
                <button type="button" onclick="removeSurahRow(${studentId}, ${idx})" style="background: #fee2e2; color: #ef4444; border: none; width: 32px; height: 32px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;" title="Hapus Baris">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        calculateStudentAvg(studentId);

        // Langsung fokus ke kolom input nama surat
        const newSurahInp = document.getElementById(`surahInput-${studentId}-${idx}`);
        if (newSurahInp) {
            newSurahInp.focus();
        }
    }

    // Hapus Baris Surat
    function removeSurahRow(studentId, idx) {
        const row = document.getElementById(`row-${studentId}-${idx}`);
        if (row) {
            row.remove();
            const tbody = document.getElementById('tbodySurahs-' + studentId);
            tbody.querySelectorAll('tr').forEach((r, i) => {
                const num = r.querySelector('.row-num');
                if (num) num.textContent = i + 1;
            });
            calculateStudentAvg(studentId);
        }
    }

    // Toggle Dropdown Pencarian
    function toggleStudentDropdown(e) {
        e.stopPropagation();
        const menu = document.getElementById('studentDropdownMenu');
        const trigger = document.getElementById('studentDropdownTrigger');
        const chevron = document.getElementById('triggerChevron');
        
        const isShown = menu.classList.contains('show');
        if (isShown) {
            menu.classList.remove('show');
            trigger.classList.remove('open');
            chevron.className = 'fas fa-chevron-down';
        } else {
            menu.classList.add('show');
            trigger.classList.add('open');
            chevron.className = 'fas fa-chevron-up';
            setTimeout(() => {
                document.getElementById('studentSearchField').focus();
            }, 100);
        }
    }

    // Handle Pencarian Santri Real-time
    function handleStudentSearchInput(query) {
        const q = query.trim().toLowerCase();
        const clearBtn = document.getElementById('clearSearchBtn');
        clearBtn.style.display = q ? 'block' : 'none';

        const items = document.querySelectorAll('.dropdown-student-item');
        let visibleCount = 0;

        items.forEach(item => {
            const name = item.dataset.name || '';
            const cls = item.dataset.class || '';
            const match = name.includes(q) || cls.includes(q);

            const activePill = document.querySelector('.filter-pill.active')?.id;
            let pillMatch = true;
            if (activePill === 'filter-pill-belum' && item.dataset.status !== 'belum') pillMatch = false;
            if (activePill === 'filter-pill-sudah' && item.dataset.status !== 'sudah') pillMatch = false;

            if (match && pillMatch) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        const empty = document.getElementById('dropdownEmptyNotice');
        if (empty) empty.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    function clearStudentSearchInput() {
        const inp = document.getElementById('studentSearchField');
        inp.value = '';
        handleStudentSearchInput('');
        inp.focus();
    }

    // Filter Dropdown by Pill
    function filterStudentList(filter) {
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
        document.getElementById(`filter-pill-${filter}`)?.classList.add('active');

        const query = document.getElementById('studentSearchField')?.value || '';
        handleStudentSearchInput(query);
    }

    // Pilih Santri Aktif
    function selectStudent(studentId) {
        currentActiveStudentId = studentId;

        const menu = document.getElementById('studentDropdownMenu');
        const trigger = document.getElementById('studentDropdownTrigger');
        const chevron = document.getElementById('triggerChevron');
        menu.classList.remove('show');
        trigger.classList.remove('open');
        chevron.className = 'fas fa-chevron-down';

        const item = document.getElementById('dropdown-item-' + studentId);
        if (item) {
            document.querySelectorAll('.dropdown-student-item').forEach(i => i.classList.remove('selected'));
            item.classList.add('selected');

            const name = item.querySelector('.item-name')?.textContent || '';
            const meta = item.querySelector('.item-meta')?.textContent || '';
            document.getElementById('triggerPlaceholderText').innerHTML = `
                <strong style="color: #064e3b; font-size: 0.95rem;">${name}</strong>
                <span style="color: #64748b; font-size: 0.76rem; margin-left: 6px;">(${meta.trim()})</span>
            `;
        }

        document.querySelectorAll('.focused-student-card').forEach(card => {
            card.style.display = 'none';
        });

        const activeCard = document.getElementById('studentCard-' + studentId);
        if (activeCard) {
            activeCard.style.display = 'block';
            syncAllJuzBadges(studentId);
            activeCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Navigasi Antar Santri
    function navigateStudent(direction) {
        if (!currentActiveStudentId) return;
        const currentIdx = studentsList.findIndex(s => s.id === currentActiveStudentId);
        if (currentIdx === -1) return;

        const nextIdx = currentIdx + direction;
        if (nextIdx >= 0 && nextIdx < studentsList.length) {
            selectStudent(studentsList[nextIdx].id);
        }
    }

    // SIMPAN NILAI UJIAN SECARA AJAX
    function saveStudentExam(studentId, thenNext = false) {
        const card = document.getElementById('studentCard-' + studentId);
        if (!card) return;

        const tbody = document.getElementById('tbodySurahs-' + studentId);
        const rows = tbody.querySelectorAll('tr:not([id^="emptyRow"])');
        
        if (rows.length === 0) {
            alert('Perhatian: Silakan tambahkan minimal 1 surat yang diujikan sebelum menyimpan.');
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('group_id', '{{ $activeGroup->id }}');
        formData.append('year_id', '{{ $selectedYearId }}');
        formData.append('semester', '{{ $selectedSemester }}');

        const examDate = document.getElementById('examDate-' + studentId)?.value || '{{ date("Y-m-d") }}';
        const generalNotes = document.getElementById('generalNotes-' + studentId)?.value || '';

        formData.append(`students[${studentId}][exam_date]`, examDate);
        formData.append(`students[${studentId}][notes]`, generalNotes);

        let itemIndex = 0;
        let hasInvalidScore = false;

        rows.forEach(row => {
            const surahInp = row.querySelector('.surah-name-input');
            const juzInp = row.querySelector('input[type="hidden"]');
            const scoreInp = row.querySelector('.input-exam-score');
            const notesInp = row.querySelector('input[placeholder*="Catatan"]');

            const surahVal = surahInp ? surahInp.value.trim() : '';
            const juzVal = getJuzForSurah(surahVal);
            const rawScore = scoreInp ? scoreInp.value.trim() : '';
            const scoreVal = rawScore !== '' ? parseFloat(rawScore) : NaN;
            const notesVal = notesInp ? notesInp.value.trim() : '';

            if (surahVal !== '') {
                if (isNaN(scoreVal) || scoreVal < 0 || scoreVal > 100) {
                    hasInvalidScore = true;
                }
                formData.append(`students[${studentId}][items][${itemIndex}][surah_name]`, surahVal);
                formData.append(`students[${studentId}][items][${itemIndex}][juz]`, juzVal);
                formData.append(`students[${studentId}][items][${itemIndex}][score]`, isNaN(scoreVal) ? 0 : scoreVal);
                formData.append(`students[${studentId}][items][${itemIndex}][notes]`, notesVal);
                itemIndex++;
            }
        });

        if (hasInvalidScore) {
            alert('Nilai ujian harus berada pada rentang angka 0 sampai 100.');
            return;
        }

        if (itemIndex === 0) {
            alert('Mohon isi nama surat yang diujikan.');
            return;
        }

        const btnSave = document.getElementById('btnSaveSingle-' + studentId);
        const btnNext = document.getElementById('btnSaveNext-' + studentId);
        const origSaveHtml = btnSave ? btnSave.innerHTML : '';
        const origNextHtml = btnNext ? btnNext.innerHTML : '';

        if (btnSave) { btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; btnSave.disabled = true; }
        if (btnNext) { btnNext.disabled = true; }

        fetch('{{ route("tahfidz.semester-exam.store") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (btnSave) { btnSave.innerHTML = origSaveHtml; btnSave.disabled = false; }
            if (btnNext) { btnNext.innerHTML = origNextHtml; btnNext.disabled = false; }

            if (data.success) {
                // Update badge status
                const badge = document.getElementById('cardStatusBadge-' + studentId);
                if (badge) {
                    badge.innerHTML = '<span style="color: #059669; font-weight: 800;"><i class="fas fa-check-circle"></i> Tersimpan (Dapat Diedit Kapan Saja)</span>';
                }

                // Update dropdown item status
                const dropItem = document.getElementById('dropdown-item-' + studentId);
                if (dropItem) {
                    dropItem.classList.add('is-saved');
                    dropItem.dataset.status = 'sudah';

                    const avatar = document.getElementById('avatar-std-' + studentId);
                    if (avatar) {
                        avatar.style.background = '#dcfce7';
                        avatar.style.color = '#15803d';
                        avatar.style.border = '1px solid #86efac';
                    }

                    const dropStatus = document.getElementById('dropdown-status-' + studentId);
                    if (dropStatus && data.student) {
                        dropStatus.innerHTML = `
                            <span class="badge-status-sudah">
                                <i class="fas fa-check-circle"></i> Sudah: <strong>${data.student.average_score}</strong> (${data.student.predicate})
                            </span>
                        `;
                    }
                }

                // Update counter pill & KPI
                const stdObj = studentsList.find(s => s.id === studentId);
                if (stdObj && !stdObj.isSaved) {
                    stdObj.isSaved = true;
                    const evalEl = document.getElementById('kpiEvaluatedCount');
                    if (evalEl) {
                        const cur = parseInt(evalEl.textContent) || 0;
                        evalEl.textContent = cur + 1;
                    }
                    const countSudah = document.getElementById('count-pill-sudah');
                    const countBelum = document.getElementById('count-pill-belum');
                    if (countSudah) countSudah.textContent = (parseInt(countSudah.textContent) || 0) + 1;
                    if (countBelum) countBelum.textContent = Math.max(0, (parseInt(countBelum.textContent) || 0) - 1);
                }

                if (thenNext) {
                    navigateStudent(1);
                } else {
                    alert('✓ ' + data.message);
                }
            } else {
                alert('✕ ' + (data.message || 'Gagal menyimpan data'));
            }
        })
        .catch(err => {
            if (btnSave) { btnSave.innerHTML = origSaveHtml; btnSave.disabled = false; }
            if (btnNext) { btnNext.innerHTML = origNextHtml; btnNext.disabled = false; }
            alert('✕ Terjadi kesalahan koneksi saat menyimpan.');
            console.error(err);
        });
    }

    // Tutup popup saat klik di luar
    document.addEventListener('click', function(e) {
        const wrap = document.getElementById('studentSearchDropdownWrap');
        const menu = document.getElementById('studentDropdownMenu');
        const trigger = document.getElementById('studentDropdownTrigger');
        const chevron = document.getElementById('triggerChevron');
        if (wrap && !wrap.contains(e.target)) {
            if (menu) menu.classList.remove('show');
            if (trigger) trigger.classList.remove('open');
            if (chevron) chevron.className = 'fas fa-chevron-down';
        }
    });

    // Inisialisasi: Pilih santri awal & Sinkronisasi Badge Juz
    document.addEventListener('DOMContentLoaded', function() {
        syncAllJuzBadges();
        if (studentsList.length > 0) {
            const firstPending = studentsList.find(s => !s.isSaved);
            const initialId = firstPending ? firstPending.id : studentsList[0].id;
            selectStudent(initialId);
        }
    });
</script>
@endsection
