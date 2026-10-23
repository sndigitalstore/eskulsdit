@extends('layouts.app')

@section('title', 'Data Presensi Pembina')
@section('page-title', 'Data Presensi Pembina')

@push('styles')
<style>
    /* Container Utama */
    .presence-container {
        max-width: 1240px;
        margin: 0 auto;
        padding-bottom: 50px;
    }

    /* 1. Hero Banner */
    .presence-hero-banner {
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

    .hero-content-left {
        max-width: 760px;
    }

    .hero-badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(16, 185, 129, 0.18);
        color: #34d399;
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        border: 1px solid rgba(52, 211, 153, 0.35);
        margin-bottom: 10px;
    }

    .hero-title {
        font-size: 1.65rem;
        font-weight: 800;
        margin: 0;
        color: #f8fafc;
        letter-spacing: -0.4px;
    }

    .hero-description {
        margin: 6px 0 0 0;
        color: #94a3b8;
        font-size: 0.92rem;
        line-height: 1.5;
    }

    .academic-year-chip {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 14px;
        padding: 10px 18px;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        color: white;
    }

    .academic-year-chip i {
        font-size: 1.4rem;
        color: #38bdf8;
    }

    .chip-label {
        font-size: 0.72rem;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .chip-val {
        font-size: 0.95rem;
        font-weight: 800;
        color: #f8fafc;
    }

    /* 2. Stat Summary Cards */
    .stat-grid-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .stat-metric-card {
        background: white;
        border-radius: 18px;
        padding: 18px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .stat-metric-card.border-blue { border-left: 5px solid #0284c7; }
    .stat-metric-card.border-green { border-left: 5px solid #10b981; }
    .stat-metric-card.border-amber { border-left: 5px solid #f59e0b; }
    .stat-metric-card.border-purple { border-left: 5px solid #8b5cf6; }

    .metric-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .metric-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .metric-number {
        font-size: 1.85rem;
        font-weight: 800;
        margin: 4px 0 2px 0;
        letter-spacing: -0.5px;
    }

    .metric-footnote {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .metric-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .text-blue { color: #0284c7; }
    .bg-blue-light { background: #e0f2fe; }

    .text-green { color: #059669; }
    .bg-green-light { background: #dcfce7; }

    .text-amber { color: #d97706; }
    .bg-amber-light { background: #fef3c7; }

    .text-purple { color: #7c3aed; }
    .bg-purple-light { background: #f3e8ff; }

    /* 3. Alert Box */
    .alert-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 14px;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 0.92rem;
    }

    .alert-success-custom {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .alert-error-custom {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    /* 4. Toolbar Filter & Solid Buttons */
    .toolbar-presence-card {
        background: white;
        border-radius: 18px;
        padding: 16px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        margin-bottom: 22px;
    }

    .toolbar-inner-form {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .filter-month-container {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .month-label-wrapper {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.88rem;
        font-weight: 700;
        color: #475569;
    }

    .month-controls-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .month-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-calendar-icon {
        position: absolute;
        left: 14px;
        color: #0284c7;
        font-size: 0.95rem;
        pointer-events: none;
    }

    .form-month-input {
        padding: 10px 14px 10px 38px;
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        outline: none;
        transition: all 0.2s;
        cursor: pointer;
    }

    .form-month-input:focus {
        background: white;
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }

    /* Tombol Solid Universal */
    .btn-solid {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.88rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .btn-solid:hover {
        transform: translateY(-2px);
    }

    .btn-solid:active {
        transform: translateY(0);
    }

    .btn-solid-blue {
        background: #0284c7;
        color: white;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
    }
    .btn-solid-blue:hover {
        background: #0369a1;
        box-shadow: 0 6px 16px rgba(2, 132, 199, 0.45);
        color: white;
    }

    .btn-solid-green {
        background: #059669;
        color: white;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }
    .btn-solid-green:hover {
        background: #047857;
        box-shadow: 0 6px 16px rgba(5, 150, 105, 0.45);
        color: white;
    }

    .btn-solid-dark {
        background: #1e293b;
        color: white;
        box-shadow: 0 4px 12px rgba(30, 41, 59, 0.25);
    }
    .btn-solid-dark:hover {
        background: #0f172a;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.4);
        color: white;
    }

    .btn-solid-orange {
        background: #ea580c;
        color: white;
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.35);
    }
    .btn-solid-orange:hover {
        background: #c2410c;
        box-shadow: 0 6px 18px rgba(234, 88, 12, 0.5);
        color: white;
    }

    .action-buttons-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* 5. Tabel Presensi */
    .presence-table-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .table-header-meta {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #fafafa;
    }

    .meta-heading {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
    }

    .meta-badge-count {
        background: #e0f2fe;
        color: #0284c7;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 999px;
        margin-left: 8px;
    }

    .meta-period-text {
        font-size: 0.88rem;
        color: #64748b;
    }

    .table-responsive-box {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .presence-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .presence-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.82rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 22px;
        border-bottom: 2px solid #e2e8f0;
    }

    .presence-table td {
        padding: 16px 22px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.92rem;
    }

    .presence-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .presence-table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Profil Guru Cell */
    .teacher-profile-flex {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .teacher-avatar-circle {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: #0369a1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.15rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.15);
    }

    .teacher-name-text {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.95rem;
        line-height: 1.3;
    }

    .teacher-eskul-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0fdf4;
        color: #166534;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        margin-top: 4px;
        border: 1px solid #bbf7d0;
    }

    .teacher-role-muted {
        font-size: 0.78rem;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* Waktu Box */
    .time-record-box {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .time-clock-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        color: #0f172a;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 4px 10px;
        border-radius: 8px;
        width: fit-content;
        border: 1px solid #e2e8f0;
    }

    .time-clock-pill i {
        color: #0284c7;
    }

    .date-sub-text {
        font-size: 0.78rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Status Pills */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-present {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .status-sick {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .status-permission {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .status-alpha {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    /* Catatan & Guru Pengganti */
    .note-balloon {
        display: inline-flex;
        align-items: flex-start;
        gap: 6px;
        color: #334155;
        font-size: 0.85rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 6px 10px;
        border-radius: 8px;
        line-height: 1.4;
        max-width: 100%;
        margin-bottom: 4px;
    }

    .note-balloon i {
        color: #64748b;
        margin-top: 2px;
    }

    .substitute-teacher-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fffbeb;
        color: #b45309;
        border: 1px dashed #f59e0b;
        font-size: 0.78rem;
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: 600;
        margin-top: 4px;
    }

    .text-dash-muted {
        color: #cbd5e1;
        font-weight: bold;
    }

    /* Tombol Aksi Hapus */
    .btn-action-delete {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }

    .btn-action-delete:hover {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.3);
    }

    /* Empty State */
    .empty-state-box {
        text-align: center;
        padding: 50px 20px;
    }

    .empty-icon-wrapper {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        background: #f1f5f9;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin-bottom: 16px;
    }

    .empty-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #334155;
        margin: 0 0 6px 0;
    }

    .empty-desc {
        color: #64748b;
        font-size: 0.92rem;
        max-width: 520px;
        margin: 0 auto;
        line-height: 1.5;
    }

    /* 6. Modal Modern */
    .custom-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(6px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .custom-modal-dialog {
        background: white;
        border-radius: 22px;
        width: 100%;
        max-width: 520px;
        max-height: 92vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid #e2e8f0;
        animation: modalScaleIn 0.25s ease-out;
    }

    @keyframes modalScaleIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    .modal-dialog-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fafafa;
        border-radius: 22px 22px 0 0;
    }

    .modal-title-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-icon-header {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #e0f2fe;
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .modal-title-text {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .modal-subtitle-text {
        font-size: 0.8rem;
        color: #64748b;
        margin: 2px 0 0 0;
    }

    .modal-close-btn {
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .modal-close-btn:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .modal-body-form {
        padding: 24px;
    }

    .form-row-modern {
        margin-bottom: 16px;
    }

    .form-grid-two {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .form-label-modern {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control-modern {
        width: 100%;
        padding: 10px 14px;
        font-size: 0.92rem;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        outline: none;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .form-control-modern:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }

    .status-options-toggle {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .status-radio-card {
        cursor: pointer;
        display: block;
        margin: 0;
    }

    .status-radio-card input {
        display: none;
    }

    .status-radio-pill {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        border: 1.5px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        transition: all 0.2s;
    }

    .status-radio-card input:checked + .present-pill {
        background: #ecfdf5;
        border-color: #10b981;
        color: #047857;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
    }

    .status-radio-card input:checked + .sick-pill {
        background: #fef3c7;
        border-color: #f59e0b;
        color: #b45309;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2);
    }

    .status-radio-card input:checked + .permission-pill {
        background: #e0f2fe;
        border-color: #0284c7;
        color: #0369a1;
        box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2);
    }

    /* Substitute Group */
    .substitute-group-container {
        background: #fffbeb;
        border: 1px dashed #f59e0b;
        padding: 14px 16px;
        border-radius: 14px;
        margin-bottom: 16px;
    }

    .substitute-group-header {
        font-weight: 800;
        font-size: 0.88rem;
        color: #b45309;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .substitute-type-switch {
        display: flex;
        gap: 16px;
        margin-bottom: 12px;
    }

    .type-switch-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #78350f;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .modal-dialog-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid #f1f5f9;
    }

    .btn-modal-cancel {
        padding: 10px 18px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        font-weight: 700;
        color: #475569;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-modal-cancel:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* ========================================== */
    /* RESPONSIVE DESIGN (TABLET & MOBILE)       */
    /* ========================================== */
    @media (max-width: 992px) {
        .presence-hero-banner {
            flex-direction: column;
            align-items: flex-start;
        }

        .academic-year-chip {
            width: 100%;
        }

        .toolbar-inner-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-month-container {
            width: 100%;
            justify-content: space-between;
        }

        .month-controls-row {
            flex: 1;
        }

        .month-input-wrapper {
            flex: 1;
        }

        .form-month-input {
            width: 100%;
        }

        .action-buttons-group {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .action-buttons-group .btn-solid-orange {
            grid-column: span 2;
        }
    }

    @media (max-width: 640px) {
        .presence-hero-banner {
            padding: 18px 20px;
            border-radius: 16px;
        }

        .hero-title {
            font-size: 1.35rem;
        }

        .stat-grid-summary {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .metric-number {
            font-size: 1.5rem;
        }

        .month-controls-row {
            flex-direction: column;
            align-items: stretch;
            width: 100%;
        }

        .action-buttons-group {
            grid-template-columns: 1fr;
        }

        .action-buttons-group .btn-solid-orange {
            grid-column: span 1;
        }

        .presence-table th, 
        .presence-table td {
            padding: 12px 14px;
        }

        .form-grid-two {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
@php
    $totalCount = $attendances->count();
    $presentCount = $attendances->where('status', 'present')->count();
    $permissionCount = $attendances->whereIn('status', ['sick', 'permission'])->count();
    $substituteCount = $attendances->filter(function($item) {
        return !empty($item->substitute_name) || !empty($item->substitute_user_id);
    })->count();
    $monthCarbon = \Carbon\Carbon::parse($month . '-01')->locale('id');
    $monthIndo = $monthCarbon->isoFormat('MMMM Y');
@endphp

<div class="presence-container">

    <!-- ========================================== -->
    <!-- 1. HERO / BANNER IDENTITAS PRESENSI       -->
    <!-- ========================================== -->
    <div class="presence-hero-banner">
        <div class="hero-content-left">
            <div class="hero-badge-tag">
                <i class="fas fa-fingerprint"></i>
                <span>Disiplin Presensi Ekstrakurikuler</span>
            </div>
            <h2 class="hero-title">Rekapitulasi Presensi Pembina</h2>
            <p class="hero-description">
                Pantau riwayat presensi harian pembina eskul, ketertiban kehadiran, serta penugasan guru pengganti periode <strong>{{ $monthIndo }}</strong>.
            </p>
        </div>
        <div class="hero-content-right">
            <div class="academic-year-chip">
                <i class="fas fa-calendar-check"></i>
                <div>
                    <div class="chip-label">Tahun Ajaran Aktif</div>
                    <div class="chip-val">{{ $activeYear ? $activeYear->name : '-' }} (Sem {{ $activeYear ? ($activeYear->active_semester ?? '1') : '-' }})</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 2. SUMMARY STAT CARDS (KPI WIDGETS)       -->
    <!-- ========================================== -->
    <div class="stat-grid-summary">
        <!-- Card 1: Total Presensi -->
        <div class="stat-metric-card border-blue">
            <div class="metric-body">
                <div class="metric-info">
                    <span class="metric-label">Total Sesi Presensi</span>
                    <h3 class="metric-number text-blue">{{ $totalCount }}</h3>
                    <div class="metric-footnote"><i class="far fa-calendar-alt"></i> Bulan {{ $monthIndo }}</div>
                </div>
                <div class="metric-icon-box bg-blue-light text-blue">
                    <i class="fas fa-clipboard-check"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Hadir Bertugas -->
        <div class="stat-metric-card border-green">
            <div class="metric-body">
                <div class="metric-info">
                    <span class="metric-label">Hadir Bertugas</span>
                    <h3 class="metric-number text-green">{{ $presentCount }}</h3>
                    <div class="metric-footnote"><i class="fas fa-user-check"></i> Pembina Hadir Langsung</div>
                </div>
                <div class="metric-icon-box bg-green-light text-green">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Izin & Sakit -->
        <div class="stat-metric-card border-amber">
            <div class="metric-body">
                <div class="metric-info">
                    <span class="metric-label">Izin & Sakit</span>
                    <h3 class="metric-number text-amber">{{ $permissionCount }}</h3>
                    <div class="metric-footnote"><i class="fas fa-envelope-open-text"></i> Terkonfirmasi Izin / Sakit</div>
                </div>
                <div class="metric-icon-box bg-amber-light text-amber">
                    <i class="fas fa-notes-medical"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Guru Pengganti -->
        <div class="stat-metric-card border-purple">
            <div class="metric-body">
                <div class="metric-info">
                    <span class="metric-label">Guru Pengganti</span>
                    <h3 class="metric-number text-purple">{{ $substituteCount }}</h3>
                    <div class="metric-footnote"><i class="fas fa-people-arrows"></i> Sesi Didelegasikan</div>
                </div>
                <div class="metric-icon-box bg-purple-light text-purple">
                    <i class="fas fa-user-friends"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 3. ALERT NOTIFIKASI                        -->
    <!-- ========================================== -->
    @if(session('success'))
        <div class="alert-box alert-success-custom">
            <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
            <div class="alert-text">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert-box alert-error-custom">
            <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="alert-text">{{ session('error') }}</div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- 4. TOOLBAR KONTROL FILTER & AKSI SOLID     -->
    <!-- ========================================== -->
    <div class="toolbar-presence-card">
        <form action="{{ route('teacher-attendance.index') }}" method="GET" class="toolbar-inner-form" id="attendanceFilterForm">
            
            <!-- Kolom Kiri: Pilihan Bulan & Filter -->
            <div class="filter-month-container">
                <div class="month-label-wrapper">
                    <i class="fas fa-filter text-muted"></i>
                    <span>Pilih Periode:</span>
                </div>
                <div class="month-controls-row">
                    <div class="month-input-wrapper">
                        <i class="far fa-calendar-alt input-calendar-icon"></i>
                        <input type="month" name="month" value="{{ $month }}" class="form-month-input" onchange="document.getElementById('attendanceFilterForm').submit()">
                    </div>
                    <button type="submit" class="btn-solid btn-solid-blue" title="Muat Data Periode Terpilih">
                        <i class="fas fa-search"></i>
                        <span>Tampilkan</span>
                    </button>
                </div>
            </div>

            <!-- Kolom Kanan: Tombol-tombol Aksi Solid -->
            <div class="action-buttons-group">
                <a href="{{ route('teacher-attendance.export', ['month' => $month]) }}" class="btn-solid btn-solid-green" title="Export Excel Rekap Bulan Ini">
                    <i class="fas fa-file-excel"></i>
                    <span>Excel Bulanan</span>
                </a>

                <a href="{{ route('teacher-attendance.export') }}" class="btn-solid btn-solid-dark" title="Export Seluruh Data Presensi">
                    <i class="fas fa-file-download"></i>
                    <span>Excel Semua</span>
                </a>

                <button type="button" onclick="openManualAttendanceModal()" class="btn-solid btn-solid-orange" title="Tambah Presensi Pembina Secara Manual">
                    <i class="fas fa-plus"></i>
                    <span>Input Manual</span>
                </button>
            </div>
        </form>
    </div>

    <!-- ========================================== -->
    <!-- 5. TABEL DATA PRESENSI PEMBINA             -->
    <!-- ========================================== -->
    <div class="presence-table-card">
        
        <div class="table-header-meta">
            <div class="meta-left">
                <span class="meta-heading">Daftar Kehadiran</span>
                <span class="meta-badge-count">{{ $attendances->count() }} Data Tercatat</span>
            </div>
            <div class="meta-right">
                <span class="meta-period-text">
                    <i class="far fa-clock"></i> Periode: <strong>{{ $monthIndo }}</strong>
                </span>
            </div>
        </div>

        <div class="table-responsive-box">
            <table class="presence-table">
                <thead>
                    <tr>
                        <th style="width: 32%;">Guru Pembina</th>
                        <th style="width: 22%;">Waktu Presensi</th>
                        <th style="width: 15%; text-align: center;">Status</th>
                        <th style="width: 23%;">Catatan / Guru Pengganti</th>
                        <th style="width: 8%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        @php
                            $user = $attendance->user;
                            $userName = $user ? $user->name : 'Guru Pembina';
                            $eskulName = ($user && $user->eskul) ? $user->eskul->name : null;
                            $dateParsed = \Carbon\Carbon::parse($attendance->date)->locale('id');
                            $dateStr = $dateParsed->isoFormat('dddd, D MMMM Y');
                        @endphp
                        <tr>
                            <!-- 1. Nama Guru & Eskul -->
                            <td>
                                <div class="teacher-profile-flex">
                                    <div class="teacher-avatar-circle">
                                        {{ strtoupper(substr($userName, 0, 1)) }}
                                    </div>
                                    <div class="teacher-profile-info">
                                        <div class="teacher-name-text">{{ $userName }}</div>
                                        @if($eskulName)
                                            <div class="teacher-eskul-badge">
                                                <i class="fas fa-medal"></i>
                                                <span>{{ $eskulName }}</span>
                                            </div>
                                        @else
                                            <div class="teacher-role-muted">Pembina Ekstrakurikuler</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- 2. Waktu & Tanggal -->
                            <td>
                                <div class="time-record-box">
                                    <div class="time-clock-pill">
                                        <i class="far fa-clock"></i>
                                        <span>{{ $attendance->clock_in_time ?? '-' }} WIB</span>
                                    </div>
                                    <div class="date-sub-text">
                                        <i class="far fa-calendar"></i>
                                        <span>{{ $dateStr }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- 3. Status Kehadiran -->
                            <td style="text-align: center;">
                                @if($attendance->status === 'present')
                                    <span class="status-pill status-present">
                                        <i class="fas fa-check-circle"></i> Hadir
                                    </span>
                                @elseif($attendance->status === 'sick')
                                    <span class="status-pill status-sick">
                                        <i class="fas fa-notes-medical"></i> Sakit
                                    </span>
                                @elseif($attendance->status === 'permission')
                                    <span class="status-pill status-permission">
                                        <i class="fas fa-envelope-open-text"></i> Izin
                                    </span>
                                @else
                                    <span class="status-pill status-alpha">
                                        <i class="fas fa-times-circle"></i> Alpha
                                    </span>
                                @endif
                            </td>

                            <!-- 4. Catatan & Guru Pengganti -->
                            <td>
                                @if(!empty($attendance->note))
                                    <div class="note-balloon">
                                        <i class="far fa-comment-dots"></i>
                                        <span>{{ $attendance->note }}</span>
                                    </div>
                                @endif

                                @if($attendance->substituteUser || $attendance->substitute_name)
                                    @php
                                        $subName = $attendance->substituteUser ? $attendance->substituteUser->name : $attendance->substitute_name;
                                    @endphp
                                    <div class="substitute-teacher-pill">
                                        <i class="fas fa-people-arrows"></i>
                                        <span>Pengganti: <strong>{{ $subName }}</strong></span>
                                    </div>
                                @endif

                                @if(empty($attendance->note) && !$attendance->substituteUser && !$attendance->substitute_name)
                                    <span class="text-dash-muted">-</span>
                                @endif
                            </td>

                            <!-- 5. Tombol Aksi Hapus -->
                            <td style="text-align: center;">
                                <form action="{{ route('teacher-attendance.destroy', $attendance->id) }}" method="POST" class="confirm-delete" data-confirm="Apakah Anda yakin ingin menghapus data presensi {{ $userName }} pada {{ $dateStr }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete" title="Hapus Data Presensi Ini">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state-box">
                                    <div class="empty-icon-wrapper">
                                        <i class="fas fa-calendar-times"></i>
                                    </div>
                                    <h4 class="empty-title">Belum Ada Presensi Pembina di Bulan Ini</h4>
                                    <p class="empty-desc">
                                        Tidak ditemukan data absensi pembina ekstrakurikuler untuk periode <strong>{{ $monthIndo }}</strong>. Silakan pilih bulan lain atau klik <strong>Input Manual</strong> untuk menambahkan presensi baru.
                                    </p>
                                    <button type="button" onclick="openManualAttendanceModal()" class="btn-solid btn-solid-orange" style="margin-top: 10px;">
                                        <i class="fas fa-plus"></i> Input Manual Sekarang
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 6. MODAL MODERN: INPUT ABSENSI GURU MANUAL                                -->
<!-- ========================================================================= -->
<div id="manualAttendanceModal" class="custom-modal-backdrop" style="display: none;">
    <div class="custom-modal-dialog">
        
        <!-- Header Modal -->
        <div class="modal-dialog-header">
            <div class="modal-title-left">
                <div class="modal-icon-header">
                    <i class="fas fa-fingerprint"></i>
                </div>
                <div>
                    <h3 class="modal-title-text">Input Presensi Pembina Manual</h3>
                    <p class="modal-subtitle-text">Catat kehadiran atau izin pembina secara langsung ke sistem SIMUTQIN</p>
                </div>
            </div>
            <button type="button" onclick="closeManualAttendanceModal()" class="modal-close-btn" title="Tutup Modal">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body Form Modal -->
        <form action="{{ route('teacher-attendance.store') }}" method="POST" class="modal-body-form">
            @csrf
            
            <div class="form-row-modern">
                <label class="form-label-modern"><i class="fas fa-user-tie"></i> Pilih Guru Pembina <span class="text-danger">*</span></label>
                <div class="select-wrapper-modern">
                    <select name="user_id" class="form-control-modern" required>
                        <option value="">-- Pilih Guru / Pembina --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">
                                {{ $t->name }} {{ $t->eskul ? '('.$t->eskul->name.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-grid-two">
                <div class="form-row-modern">
                    <label class="form-label-modern"><i class="far fa-calendar-alt"></i> Tanggal Presensi <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control-modern" required max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                </div>

                <div class="form-row-modern">
                    <label class="form-label-modern"><i class="far fa-clock"></i> Jam Masuk <span class="text-danger">*</span></label>
                    <input type="time" name="clock_in_time" class="form-control-modern" required value="{{ date('H:i') }}">
                </div>
            </div>

            <div class="form-row-modern">
                <label class="form-label-modern"><i class="fas fa-clipboard-list"></i> Status Kehadiran <span class="text-danger">*</span></label>
                <div class="status-options-toggle">
                    <label class="status-radio-card">
                        <input type="radio" name="status" value="present" checked onchange="handleStatusChange(this.value)">
                        <span class="status-radio-pill present-pill"><i class="fas fa-check-circle"></i> Hadir</span>
                    </label>
                    <label class="status-radio-card">
                        <input type="radio" name="status" value="sick" onchange="handleStatusChange(this.value)">
                        <span class="status-radio-pill sick-pill"><i class="fas fa-notes-medical"></i> Sakit</span>
                    </label>
                    <label class="status-radio-card">
                        <input type="radio" name="status" value="permission" onchange="handleStatusChange(this.value)">
                        <span class="status-radio-pill permission-pill"><i class="fas fa-envelope-open-text"></i> Izin</span>
                    </label>
                </div>
            </div>

            <!-- Bagian Guru Pengganti (Khusus jika Sakit/Izin) -->
            <div id="manualSubstituteGroup" class="substitute-group-container" style="display: none;">
                <div class="substitute-group-header">
                    <i class="fas fa-user-friends"></i>
                    <span>Informasi Guru Pengganti</span>
                </div>

                <div class="substitute-type-switch">
                    <label class="type-switch-label">
                        <input type="radio" name="substitute_type" value="registered" checked id="manualTypeRegistered" onchange="toggleManualSubstituteFields()">
                        <span><i class="fas fa-user-check"></i> Guru Terdaftar</span>
                    </label>
                    <label class="type-switch-label">
                        <input type="radio" name="substitute_type" value="manual" id="manualTypeManual" onchange="toggleManualSubstituteFields()">
                        <span><i class="fas fa-keyboard"></i> Ketik Manual</span>
                    </label>
                </div>

                <div id="manualSubstituteUserSelect" class="form-row-modern" style="margin-bottom: 0;">
                    <select name="substitute_user_id" class="form-control-modern bg-white">
                        <option value="">-- Pilih Guru Pengganti --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }} {{ $t->eskul ? '('.$t->eskul->name.')' : '' }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="manualSubstituteNameInput" class="form-row-modern" style="display: none; margin-bottom: 0;">
                    <input type="text" name="substitute_name" class="form-control-modern bg-white" placeholder="Ketik nama lengkap guru pengganti...">
                </div>
            </div>

            <div class="form-row-modern">
                <label class="form-label-modern"><i class="far fa-comment-alt"></i> Catatan / Keterangan (Opsional)</label>
                <input type="text" name="note" class="form-control-modern" placeholder="Contoh: Menggantikan sesi latihan tanding eskul...">
            </div>

            <div class="modal-dialog-footer">
                <button type="button" onclick="closeManualAttendanceModal()" class="btn-modal-cancel">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-solid btn-solid-blue">
                    <i class="fas fa-save"></i> Simpan Presensi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- CSS STYLES: MODERN, SOLID, DAN ULTRA RESPONSIF                            -->
<!-- ========================================================================= -->


<!-- ========================================================================= -->
<!-- JAVASCRIPT: LOGIKA MODAL & INTERAKSI                                      -->
<!-- ========================================================================= -->
<script>
    function openManualAttendanceModal() {
        const modal = document.getElementById('manualAttendanceModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeManualAttendanceModal() {
        const modal = document.getElementById('manualAttendanceModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Close on click outside modal dialog
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('manualAttendanceModal');
        if (e.target === modal) {
            closeManualAttendanceModal();
        }
    });

    // Close on Escape key
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeManualAttendanceModal();
        }
    });

    function handleStatusChange(status) {
        const subGroup = document.getElementById('manualSubstituteGroup');
        if (status === 'sick' || status === 'permission') {
            subGroup.style.display = 'block';
        } else {
            subGroup.style.display = 'none';
        }
    }

    function toggleManualSubstituteFields() {
        const regRadio = document.getElementById('manualTypeRegistered');
        const userSelect = document.getElementById('manualSubstituteUserSelect');
        const nameInput = document.getElementById('manualSubstituteNameInput');

        if (regRadio && regRadio.checked) {
            userSelect.style.display = 'block';
            nameInput.style.display = 'none';
        } else {
            userSelect.style.display = 'none';
            nameInput.style.display = 'block';
        }
    }

    // Auto open modal if URL params specify open_modal=1
    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const openModal = urlParams.get('open_modal');
        const userId = urlParams.get('user_id');
        const date = urlParams.get('date');

        if (openModal === '1') {
            openManualAttendanceModal();
            if (userId) {
                const userSelect = document.querySelector('select[name="user_id"]');
                if (userSelect) userSelect.value = userId;
            }
            if (date) {
                const dateInput = document.querySelector('input[name="date"]');
                if (dateInput) dateInput.value = date;
            }
        }
    });
</script>
@endsection
