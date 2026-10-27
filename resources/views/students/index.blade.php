@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@push('styles')
<style>
    /* ===== PAGE WRAPPER ===== */
    .students-page {
        display: flex;
        flex-direction: column;
        gap: 20px;
        animation: fadeIn 0.4s ease;
    }

    /* ===== TOP BAR ===== */
    .students-topbar {
        background: #fff;
        border-radius: 16px;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .page-title-group h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        line-height: 1.3;
    }

    .page-title-group span {
        font-size: 0.8rem;
        color: #94a3b8;
        font-weight: 500;
    }

    /* Status Toggle Tabs */
    .status-tabs {
        display: flex;
        background: #f1f5f9;
        border-radius: 10px;
        padding: 3px;
        gap: 2px;
    }

    .status-tab {
        padding: 7px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 600;
        color: #64748b;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .status-tab.active {
        background: #fff;
        color: #7367f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .status-tab:hover:not(.active) {
        color: #334155;
        background: rgba(255,255,255,0.6);
    }

    .status-tab .tab-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.7;
    }

    /* Action Bar (right) */
    .topbar-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Search */
    .search-wrap {
        position: relative;
    }

    .search-wrap i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #b0bec5;
        font-size: 0.85rem;
        pointer-events: none;
    }

    .search-wrap input {
        padding: 9px 14px 9px 36px;
        border: 1.5px solid #e8edf2;
        border-radius: 10px;
        font-size: 0.85rem;
        width: 220px;
        background: #f8fafc;
        color: #334155;
        transition: all 0.25s;
        font-family: 'Nunito', sans-serif;
    }

    .search-wrap input:focus {
        outline: none;
        background: #fff;
        border-color: #7367f0;
        box-shadow: 0 0 0 3px rgba(115,103,240,0.12);
        width: 260px;
    }

    #searchSuggestions {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e8edf2;
        border-radius: 12px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
        display: none;
    }

    .suggestion-item {
        padding: 10px 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s;
    }

    .suggestion-item:last-child { border-bottom: none; }
    .suggestion-item:hover { background: #f8fafc; }
    .suggestion-info h4 { margin: 0; font-size: 0.875rem; color: #1e293b; }
    .suggestion-info p  { margin: 0; font-size: 0.75rem; color: #94a3b8; }

    /* Action Buttons in topbar */
    .act-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 10px;
        font-size: 0.82rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.22s ease;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    }

    .act-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.12); }
    .act-btn:active { transform: translateY(0); }

    .act-btn-primary   { background: #7367f0; color: #fff; }
    .act-btn-success   { background: #28c76f; color: #fff; }
    .act-btn-dark      { background: #3d4b5c; color: #fff; }
    .act-btn-danger    { background: #ea5455; color: #fff; }

    /* ===== ALERTS ===== */
    .alert {
        padding: 13px 18px;
        border-radius: 12px;
        font-size: 0.88rem;
        font-weight: 500;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .alert-success { background: #eafaf1; color: #1a7a4a; border: 1px solid #b7eacf; }
    .alert-danger  { background: #fef2f2; color: #c0392b; border: 1px solid #fbc8c8; }
    .alert-warning { background: #fef9e7; color: #7d6608; border: 1px solid #f9e79f; }
    .alert i { margin-top: 2px; }

    /* ===== STATS ROW ===== */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px;
    }

    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }

    .stat-card:hover { transform: translateY(-2px); }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .stat-icon.purple { background: rgba(115,103,240,0.12); color: #7367f0; }
    .stat-icon.green  { background: rgba(40,199,111,0.12);  color: #28c76f; }
    .stat-icon.orange { background: rgba(255,159,67,0.12);  color: #ff9f43; }
    .stat-icon.red    { background: rgba(234,84,85,0.12);   color: #ea5455; }

    .stat-info h3 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 3px;
    }

    .stat-info p {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 600;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .table-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table-card-header h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #334155;
        margin: 0;
    }

    .bulk-info {
        font-size: 0.8rem;
        color: #7367f0;
        font-weight: 600;
        background: rgba(115,103,240,0.08);
        padding: 4px 12px;
        border-radius: 20px;
        display: none;
    }

    /* The table */
    .students-table {
        width: 100%;
        border-collapse: collapse;
    }

    .students-table thead th {
        background: #f8fafc;
        padding: 13px 16px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #f1f5f9;
        white-space: nowrap;
    }

    .students-table thead th:first-child { border-radius: 0; padding-left: 24px; }
    .students-table thead th:last-child  { padding-right: 24px; text-align: center; }

    .students-table tbody tr {
        border-bottom: 1px solid #f8fafc;
        transition: background 0.15s;
    }

    .students-table tbody tr:last-child { border-bottom: none; }
    .students-table tbody tr:hover { background: #fafbff; }

    .students-table tbody td {
        padding: 13px 16px;
        font-size: 0.875rem;
        color: #334155;
        vertical-align: middle;
    }

    .students-table tbody td:first-child { padding-left: 24px; }
    .students-table tbody td:last-child  { padding-right: 24px; }

    /* Number column */
    .col-no {
        color: #cbd5e1;
        font-size: 0.78rem;
        font-weight: 600;
        text-align: center;
    }

    /* NIS Badge */
    .nis-badge {
        font-family: 'Courier New', monospace;
        font-size: 0.78rem;
        background: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        letter-spacing: 0.05em;
    }

    .nis-empty {
        color: #cbd5e1;
        font-size: 0.78rem;
    }

    /* Student name cell */
    .student-name-cell {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .student-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #7367f0, #9c8ff5);
        color: white;
        font-size: 0.85rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .student-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .student-name-text strong {
        display: block;
        font-size: 0.875rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.3;
    }

    .student-name-text span {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* Class badge */
    .class-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        min-width: 42px;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.25);
    }

    /* Eskul chips */
    .eskul-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .eskul-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.76rem;
        font-weight: 700;
        background: #e0e7ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
        white-space: nowrap;
    }

    .eskul-chip-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #4f46e5;
        flex-shrink: 0;
    }

    .no-eskul {
        color: #cbd5e1;
        font-size: 0.8rem;
        font-style: italic;
    }

    /* Instructor */
    .instructor-list {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .instructor-name {
        font-size: 0.82rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .instructor-name i {
        color: #cbd5e1;
        font-size: 0.7rem;
    }

    /* Row actions */
    .row-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .row-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .row-btn:hover { transform: translateY(-2px); }
    .row-btn-view   { background: rgba(52,152,219,0.1);  color: #3498db; }
    .row-btn-edit   { background: rgba(255,159,67,0.1);  color: #ff9f43; }
    .row-btn-delete { background: rgba(234,84,85,0.1);   color: #ea5455; }
    .row-btn-view:hover   { background: #3498db; color: #fff; }
    .row-btn-edit:hover   { background: #ff9f43; color: #fff; }
    .row-btn-delete:hover { background: #ea5455; color: #fff; }

    /* Checkbox */
    .custom-check {
        width: 17px;
        height: 17px;
        accent-color: #7367f0;
        cursor: pointer;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #cbd5e1;
        font-size: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .empty-state h4 {
        font-size: 1rem;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .empty-state p { font-size: 0.85rem; color: #b0bec5; }

    /* ===== PAGINATION ===== */
    .pagination-wrap {
        padding: 14px 24px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pagination-info { font-size: 0.8rem; color: #94a3b8; font-weight: 500; }

    /* Override Laravel default pagination */
    .pagination-wrap nav { display: flex; align-items: center; }
    .pagination-wrap nav .flex.items-center.justify-between { flex-wrap: wrap; gap: 8px; }
    .pagination-wrap span[aria-current="page"] span,
    .pagination-wrap a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        color: #64748b;
        border: 1px solid #e8edf2;
        transition: all 0.2s;
        margin: 0 2px;
    }
    .pagination-wrap span[aria-current="page"] span {
        background: #7367f0;
        color: #fff;
        border-color: #7367f0;
    }
    .pagination-wrap a:hover { background: #f1f5f9; color: #7367f0; border-color: #7367f0; }
    .pagination-wrap span[aria-disabled="true"] span { color: #d1d5db; cursor: not-allowed; }

    /* ===== MODALS ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(15,23,42,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
        animation: fadeIn 0.25s ease;
    }

    .modal-overlay.show { display: flex; }

    .modal-box {
        background: #fff;
        border-radius: 20px;
        width: 500px;
        max-width: 95vw;
        max-height: 90vh;
        overflow-y: auto;
        padding: 28px;
        position: relative;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        animation: popIn 0.3s cubic-bezier(0.175,0.885,0.32,1.275);
    }

    @keyframes popIn {
        from { opacity: 0; transform: scale(0.9) translateY(20px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .modal-header h3 { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0; }

    .modal-close {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: none;
        background: #f1f5f9;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .modal-close:hover { background: #ea5455; color: #fff; }

    /* Tabs */
    .modal-tabs {
        display: flex;
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 20px;
        gap: 0;
    }

    .modal-tab-btn {
        padding: 10px 20px;
        border: none;
        background: none;
        font-size: 0.85rem;
        font-weight: 600;
        color: #94a3b8;
        cursor: pointer;
        position: relative;
        transition: color 0.2s;
        font-family: 'Nunito', sans-serif;
    }

    .modal-tab-btn::after {
        content: '';
        position: absolute;
        bottom: -2px; left: 0; right: 0;
        height: 2px;
        background: #7367f0;
        border-radius: 2px;
        transform: scaleX(0);
        transition: transform 0.2s;
    }

    .modal-tab-btn.active { color: #7367f0; }
    .modal-tab-btn.active::after { transform: scaleX(1); }

    .form-hint {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-bottom: 14px;
        background: #f8fafc;
        padding: 10px 14px;
        border-radius: 10px;
        border-left: 3px solid #7367f0;
        line-height: 1.5;
    }

    .form-textarea {
        width: 100%;
        padding: 12px;
        border: 1.5px solid #e8edf2;
        border-radius: 10px;
        font-size: 0.85rem;
        font-family: monospace;
        resize: vertical;
        min-height: 160px;
        transition: border 0.2s;
        color: #334155;
    }

    .form-textarea:focus { outline: none; border-color: #7367f0; box-shadow: 0 0 0 3px rgba(115,103,240,0.1); }

    .drop-zone {
        background: #f8fafc;
        border: 2px dashed #c4d0e0;
        border-radius: 14px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s;
    }

    .drop-zone:hover, .drop-zone.dragover { border-color: #7367f0; background: rgba(115,103,240,0.04); }

    .drop-zone i { font-size: 2.5rem; color: #28c76f; margin-bottom: 12px; }
    .drop-zone p { margin: 0; color: #64748b; font-size: 0.875rem; font-weight: 500; }
    .drop-zone small { color: #94a3b8; font-size: 0.78rem; }
    #fileNameDisplay { margin-top: 10px; color: #7367f0; font-size: 0.85rem; font-weight: 600; }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-cancel {
        padding: 10px 20px;
        border: 1.5px solid #e8edf2;
        background: #fff;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        font-family: 'Nunito', sans-serif;
        transition: all 0.2s;
    }
    .btn-cancel:hover { background: #f1f5f9; }

    .btn-submit {
        padding: 10px 22px;
        border: none;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        font-family: 'Nunito', sans-serif;
        transition: all 0.2s;
    }
    .btn-submit.purple { background: linear-gradient(135deg, #7367f0, #9c8ff5); }
    .btn-submit.green  { background: linear-gradient(135deg, #28c76f, #20a25a); }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.15); }

    /* ===== CONFIRM MODAL ===== */
    .confirm-modal-box {
        background: #fff;
        border-radius: 20px;
        width: 380px;
        max-width: 95vw;
        padding: 32px 28px;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        animation: popIn 0.3s cubic-bezier(0.175,0.885,0.32,1.275);
    }

    .confirm-icon-wrap {
        width: 72px; height: 72px;
        background: #fef2f2;
        color: #ea5455;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 18px;
    }

    .confirm-modal-box h3 { font-size: 1.1rem; color: #1e293b; margin-bottom: 8px; }
    .confirm-modal-box p  { font-size: 0.875rem; color: #64748b; margin-bottom: 24px; line-height: 1.6; }

    .confirm-actions { display: flex; gap: 10px; justify-content: center; }

    .btn-confirm-cancel {
        padding: 10px 24px;
        border: 1.5px solid #e8edf2;
        background: #fff;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        font-family: 'Nunito', sans-serif;
        transition: all 0.2s;
    }
    .btn-confirm-cancel:hover { background: #f1f5f9; }

    .btn-confirm-delete {
        padding: 10px 24px;
        border: none;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 700;
        background: #ea5455;
        color: #fff;
        cursor: pointer;
        font-family: 'Nunito', sans-serif;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(234,84,85,0.3);
    }
    .btn-confirm-delete:hover { background: #d43434; transform: translateY(-1px); }

    /* ===== VIEW SWITCHING ===== */
    .desktop-table-view {
        display: block;
    }
    .mobile-students-view {
        display: none;
    }
    .mobile-select-all-wrap {
        display: none;
    }
    .topbar-admin-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .action-grid-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-plotting-tahfidz {
        display: inline-block;
    }
    .btn-plotting {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: white;
    }

    /* ===== MOBILE RESPONSIVE (< 769px) ===== */
    @media (max-width: 768px) {
        .students-page {
            gap: 14px;
        }

        /* Topbar Mobile */
        .students-topbar {
            padding: 16px 14px;
            border-radius: 14px;
            gap: 12px;
        }

        .topbar-left {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .page-title-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .page-title-group h2 {
            font-size: 1.15rem;
        }

        .status-tabs {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            padding: 4px;
            border-radius: 12px;
            background: #f1f5f9;
        }

        .status-tab {
            justify-content: center;
            padding: 9px 12px;
            font-size: 0.85rem;
            border-radius: 8px;
        }

        .topbar-actions {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .search-wrap {
            width: 100%;
        }

        .search-wrap input {
            width: 100% !important;
            box-sizing: border-box;
            padding: 10px 14px 10px 38px;
            font-size: 0.85rem;
        }

        .search-wrap input:focus {
            width: 100% !important;
        }

        #searchSuggestions {
            width: 100%;
            box-sizing: border-box;
        }

        .topbar-admin-actions {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
        }

        .form-plotting-tahfidz {
            width: 100%;
            display: block !important;
        }

        .form-plotting-tahfidz .btn-plotting {
            width: 100%;
            justify-content: center;
            padding: 11px 14px;
            font-size: 0.82rem;
            border-radius: 10px;
        }

        #btnBulkDelete {
            width: 100%;
            justify-content: center;
            padding: 11px 14px;
            margin: 0;
            border-radius: 10px;
        }

        .action-grid-buttons {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 8px !important;
            width: 100% !important;
        }

        .action-grid-buttons .act-btn {
            width: 100%;
            justify-content: center;
            padding: 10px 4px;
            font-size: 0.8rem;
            border-radius: 10px;
        }

        /* Stats Grid 2x2 */
        .stats-row {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }

        .stat-card {
            padding: 12px 14px;
            gap: 10px;
            border-radius: 14px;
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            font-size: 0.95rem;
            border-radius: 10px;
        }

        .stat-info h3 {
            font-size: 1.25rem;
            margin-bottom: 2px;
        }

        .stat-info p {
            font-size: 0.72rem;
        }

        /* Table Card & Header */
        .table-card {
            border-radius: 14px;
        }

        .table-card-header {
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .table-header-left {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .table-card-header h3 {
            font-size: 0.9rem;
        }

        .bulk-info {
            font-size: 0.75rem;
            padding: 3px 10px;
        }

        .mobile-select-all-wrap {
            display: flex;
            align-items: center;
        }

        .mobile-select-all-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #475569;
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            user-select: none;
            transition: background 0.2s;
        }

        .mobile-select-all-label:active {
            background: #e2e8f0;
        }

        /* Hide desktop table & show mobile cards */
        .desktop-table-view {
            display: none !important;
        }

        .mobile-students-view {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 12px;
            background: #f8fafc;
        }

        /* Mobile Student Card */
        .mobile-student-card {
            background: #ffffff;
            border: 1px solid #e8edf2;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: all 0.2s;
        }

        .m-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .m-card-user {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }

        .m-card-check {
            display: flex;
            align-items: center;
            padding-top: 8px;
            flex-shrink: 0;
        }

        .m-card-check .custom-check {
            width: 18px;
            height: 18px;
        }

        .m-card-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #7367f0, #9c8ff5);
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(115,103,240,0.22);
        }

        .m-card-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .m-card-title-group {
            flex: 1;
            min-width: 0;
        }

        .m-student-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 4px 0;
            line-height: 1.35;
            word-break: break-word;
        }

        .m-student-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .m-student-number {
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 700;
            background: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .m-nis-pill {
            font-family: 'Courier New', monospace;
            font-size: 0.72rem;
            background: #f1f5f9;
            color: #475569;
            padding: 2px 7px;
            border-radius: 5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            letter-spacing: 0.03em;
        }

        .m-nis-empty {
            font-size: 0.72rem;
            color: #cbd5e1;
        }

        .m-card-badge {
            flex-shrink: 0;
        }

        /* Eskul & Pembina in card body */
        .m-card-body {
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 10px;
            padding: 9px 12px;
        }

        .m-eskul-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .m-eskul-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
            padding-bottom: 5px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .m-eskul-item:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }

        .m-pembina-name {
            font-size: 0.78rem;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
        }

        .m-pembina-name i {
            color: #94a3b8;
            font-size: 0.72rem;
        }

        .m-no-eskul {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #94a3b8;
            font-size: 0.78rem;
            font-style: italic;
        }

        .m-no-eskul i {
            color: #cbd5e1;
        }

        /* Actions Footer */
        .m-card-actions {
            margin-top: 2px;
            padding-top: 10px;
            border-top: 1px solid #f1f5f9;
        }

        .m-card-actions.m-actions-4 {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 6px !important;
        }

        .m-card-actions.m-actions-2 {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8px !important;
        }

        .m-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 8px 4px;
            border-radius: 9px;
            font-size: 0.76rem;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            min-height: 36px;
        }

        .m-btn:active {
            transform: scale(0.97);
        }

        .m-btn-view {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #dbeafe;
        }

        .m-btn-cert {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .m-btn-edit {
            background: #f5f3ff;
            color: #6d28d9;
            border: 1px solid #ddd6fe;
        }

        .m-btn-delete {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        /* Mobile screens (< 520px) */
        @media (max-width: 520px) {
            .m-card-actions.m-actions-4 {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 8px !important;
            }
            .action-grid-buttons {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 6px !important;
            }
            .action-grid-buttons .act-btn {
                font-size: 0.76rem;
                padding: 10px 4px;
            }
        }

        /* Pagination on mobile */
        .pagination-wrap {
            padding: 12px 14px;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-align: center;
        }

        .pagination-wrap nav {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')

<div class="students-page">

    {{-- ===== TOP BAR ===== --}}
    <div class="students-topbar">
        <div class="topbar-left">
            <div class="page-title-group">
                <h2><i class="fas fa-users" style="color:#7367f0; margin-right:8px; font-size:1.1rem;"></i>Daftar Siswa</h2>
                <span>Tahun Ajaran Aktif</span>
            </div>

            {{-- Status Tabs --}}
            <div class="status-tabs">
                <a href="{{ route('students.index', ['status' => 'active']) }}"
                   class="status-tab {{ $status == 'active' ? 'active' : '' }}">
                    <span class="tab-dot"></span> Aktif
                </a>
                <a href="{{ route('students.index', ['status' => 'graduated']) }}"
                   class="status-tab {{ $status == 'graduated' ? 'active' : '' }}">
                    <span class="tab-dot" style="background:#28c76f;"></span> Lulus
                </a>
            </div>
        </div>

        <div class="topbar-actions">
            {{-- Search --}}
            <form action="{{ route('students.index') }}" method="GET" class="search-wrap" style="position: relative;">
                <input type="hidden" name="status" value="{{ $status }}">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" name="search"
                       placeholder="Cari nama / NIS..."
                       value="{{ request('search') }}"
                       autocomplete="off"
                       data-api-url="{{ route('api.students.search') }}">
                <div id="searchSuggestions"></div>
            </form>

            @if(Auth::user()->role == 'admin')
                <div class="topbar-admin-actions">
                    <form action="{{ route('students.assign_grade_6_tahfidz') }}" method="POST" class="form-plotting-tahfidz" onsubmit="return confirm('Apakah Anda yakin ingin mem-plotting seluruh siswa Kelas 6 ke Kelompok Tahfidz untuk Semester 2?');">
                        @csrf
                        <button type="submit" class="act-btn btn-plotting" title="Plotting Massal Siswa Kelas 6 ke Kelompok Tahfidz Semester 2">
                            <i class="fas fa-quran"></i> <span>Plotting Tahfidz Kelas 6</span>
                        </button>
                    </form>

                    <button id="btnBulkDelete" onclick="confirmBulkDelete()"
                            class="act-btn act-btn-danger" style="display:none;">
                        <i class="fas fa-trash"></i> <span>Hapus Terpilih</span>
                    </button>

                        <a href="{{ route('class-management.index') }}" class="act-btn" style="background: #2563eb; color: white;" title="Manajemen & Ploting Kelas">
                            <i class="fas fa-layer-group"></i> <span>Ploting Kelas</span>
                        </a>

                        <a href="{{ route('students.create') }}" class="act-btn act-btn-primary">
                            <i class="fas fa-plus"></i> <span>Tambah</span>
                        </a>

                        <button type="button" class="act-btn act-btn-success" onclick="openImportModal()">
                            <i class="fas fa-file-excel"></i> <span>Import</span>
                        </button>

                        <a href="{{ route('students.backup') }}" class="act-btn act-btn-dark">
                            <i class="fas fa-download"></i> <span>Backup</span>
                        </a>
                    </div>
                </div>
            @endif
            @if(Auth::user()->role == 'tu')
                <div class="topbar-admin-actions">
                    <div class="action-grid-buttons">
                        <a href="{{ route('class-management.index') }}" class="act-btn" style="background: #2563eb; color: white;" title="Manajemen & Ploting Kelas">
                            <i class="fas fa-layer-group"></i> <span>Ploting Kelas</span>
                        </a>
                        <a href="{{ route('students.create') }}" class="act-btn act-btn-primary">
                            <i class="fas fa-plus"></i> <span>Tambah Siswa</span>
                        </a>
                        <a href="{{ route('students.backup') }}" class="act-btn act-btn-dark">
                            <i class="fas fa-download"></i> <span>Backup</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ===== ALERTS ===== --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <div>{!! session('success') !!}</div>
        </div>
    @endif

    @if(session('warning_html'))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div><strong>Catatan Penting:</strong><br>{!! session('warning_html') !!}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>{!! session('error') !!}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <ul style="margin:0; padding-left:16px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===== STATS ROW ===== --}}
    @php
        $total     = $totalActive ?? $students->total();
        $male      = $totalMale ?? $students->getCollection()->filter(fn($s) => $s->gender === 'L')->count();
        $female    = $totalFemale ?? $students->getCollection()->filter(fn($s) => $s->gender === 'P')->count();
        $fullDay   = $totalFullDay ?? $students->getCollection()->filter(fn($s) => $s->school_program === 'Full Day')->count();
        $boarding  = $totalBoarding ?? $students->getCollection()->filter(fn($s) => $s->school_program === 'Boarding')->count();
    @endphp
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h3>{{ $total }}</h3>
                <p>Total Siswa {{ $status == 'graduated' ? 'Lulus' : 'Aktif' }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-male"></i></div>
            <div class="stat-info">
                <h3>{{ $male }}</h3>
                <p>Laki-laki (L)</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-female"></i></div>
            <div class="stat-info">
                <h3>{{ $female }}</h3>
                <p>Perempuan (P)</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-building"></i></div>
            <div class="stat-info">
                <h3 style="font-size:1.15rem;">{{ $fullDay }} / {{ $boarding }}</h3>
                <p>Full Day / Boarding</p>
            </div>
        </div>
    </div>

    {{-- ===== TABLE CARD ===== --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-header-left">
                <h3><i class="fas fa-table" style="color:#7367f0; margin-right:6px;"></i>Data Siswa Lengkap {{ $status == 'graduated' ? '(Lulus)' : '(Aktif)' }}</h3>
                <span class="bulk-info" id="bulkInfo">0 siswa dipilih</span>
            </div>
            @if(Auth::user()->role == 'admin')
                <div class="mobile-select-all-wrap">
                    <label class="mobile-select-all-label">
                        <input type="checkbox" id="checkAllMobile" class="custom-check" onchange="toggleAll(this)">
                        <span>Pilih Semua</span>
                    </label>
                </div>
            @endif
        </div>

        <form action="{{ route('students.destroy_bulk') }}" method="POST" id="bulkDeleteForm">
            @csrf
            @method('DELETE')

            {{-- 1. DESKTOP TABLE VIEW (>= 769px) --}}
            <div class="desktop-table-view" style="overflow-x: auto;">
                <table class="students-table" style="min-width: 1400px;">
                    <thead>
                        <tr>
                            <th style="width:40px; text-align:center;">
                                @if(Auth::user()->role == 'admin')
                                    <input type="checkbox" id="checkAll" class="custom-check" onchange="toggleAll(this)">
                                @else
                                    #
                                @endif
                            </th>
                            <th style="width:40px; text-align:center;">No</th>
                            <th style="min-width:90px;">NIS</th>
                            <th style="min-width:100px;">NISN</th>
                            <th style="min-width:180px;">Nama Lengkap</th>
                            <th style="text-align:center; width:55px;">L/P</th>
                            <th style="min-width:80px;">Kelas</th>
                            <th style="min-width:150px;">Tempat, Tgl Lahir</th>
                            <th style="min-width:170px;">Alamat</th>
                            <th style="min-width:130px;">Asal Sekolah</th>
                            <th style="min-width:150px;">Nama Orang Tua</th>
                            <th style="min-width:140px;">Pekerjaan Ortu</th>
                            <th style="min-width:140px;">No. HP Ortu</th>
                            <th style="min-width:110px;">Tgl Diterima</th>
                            <th style="text-align:center; min-width:110px;">Status Masuk</th>
                            @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'tu']))
                            <th style="text-align:center; min-width:170px;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $i => $student)
                        <tr>
                            {{-- Checkbox --}}
                            <td style="text-align:center;">
                                @if(Auth::user()->role == 'admin')
                                    <input type="checkbox" name="ids[]" value="{{ $student->id }}"
                                           class="check-item custom-check" onchange="onStudentCheck('{{ $student->id }}', this.checked)">
                                @else
                                    <span class="col-no">-</span>
                                @endif
                            </td>

                            {{-- No --}}
                            <td class="col-no" style="text-align:center;">{{ $students->firstItem() + $i }}</td>

                            {{-- NIS --}}
                            <td>
                                @if($student->nis)
                                    <span class="nis-badge">{{ $student->nis }}</span>
                                @else
                                    <span class="nis-empty">—</span>
                                @endif
                            </td>

                            {{-- NISN --}}
                            <td>
                                @if($student->nisn)
                                    <span style="font-weight:600; color:#334155; font-size:0.85rem;">{{ $student->nisn }}</span>
                                @else
                                    <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>

                            {{-- Nama Lengkap --}}
                            <td>
                                <div class="student-name-cell">
                                    <div class="student-avatar">
                                        @if($student->photo)
                                            <img src="{{ asset('storage/'.$student->photo) }}" alt="{{ $student->name }}">
                                        @else
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="student-name-text">
                                        <strong>{{ $student->name }}</strong>
                                        <span style="color:#64748b; font-size:0.75rem;">NIS: {{ $student->nis ?: '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- L/P (Gender) --}}
                            <td style="text-align:center;">
                                @if($student->gender == 'L')
                                    <span style="background:#e0f2fe; color:#0369a1; padding:2px 8px; border-radius:4px; font-weight:700; font-size:0.78rem;">L</span>
                                @elseif($student->gender == 'P')
                                    <span style="background:#fce7f3; color:#db2777; padding:2px 8px; border-radius:4px; font-weight:700; font-size:0.78rem;">P</span>
                                @else
                                    <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>

                            {{-- Kelas --}}
                            <td>
                                <span class="class-badge">{{ $student->class ?? '-' }}</span>
                            </td>

                            {{-- Tempat, Tgl Lahir --}}
                            <td>
                                <span style="font-size:0.83rem; color:#334155;">{{ $student->birth_place_date ?: '—' }}</span>
                            </td>

                            {{-- Alamat --}}
                            <td>
                                <div style="max-width:180px; font-size:0.8rem; line-height:1.35; color:#475569;" title="{{ $student->address }}">
                                    {{ $student->address ? \Illuminate\Support\Str::limit($student->address, 45) : '—' }}
                                </div>
                            </td>

                            {{-- Asal Sekolah --}}
                            <td>
                                <span style="font-size:0.83rem; color:#334155;">{{ $student->previous_school ?: '—' }}</span>
                            </td>

                            {{-- Nama Orang Tua (Ayah dan Ibu) --}}
                            <td>
                                <div style="font-size:0.82rem; line-height:1.35;">
                                    <div><span style="color:#64748b; font-size:0.75rem;">Ayah:</span> <strong>{{ $student->father_name ?: '—' }}</strong></div>
                                    <div><span style="color:#64748b; font-size:0.75rem;">Ibu:</span> <strong>{{ $student->mother_name ?: '—' }}</strong></div>
                                </div>
                            </td>

                            {{-- Pekerjaan Orang Tua --}}
                            <td>
                                <div style="font-size:0.78rem; line-height:1.35; color:#475569;">
                                    <div><span style="color:#94a3b8;">Ayah:</span> {{ $student->father_job ?: '—' }}</div>
                                    <div><span style="color:#94a3b8;">Ibu:</span> {{ $student->mother_job ?: '—' }}</div>
                                </div>
                            </td>

                            {{-- Nomor HP Orang Tua --}}
                            <td>
                                <div style="font-size:0.8rem; line-height:1.35;">
                                    @if($student->father_phone)
                                        <div>
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->father_phone) }}" target="_blank" style="color:#16a34a; text-decoration:none; font-weight:600;">
                                                <i class="fab fa-whatsapp"></i> {{ $student->father_phone }} <small style="color:#64748b;">(A)</small>
                                            </a>
                                        </div>
                                    @endif
                                    @if($student->mother_phone)
                                        <div>
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->mother_phone) }}" target="_blank" style="color:#16a34a; text-decoration:none; font-weight:600;">
                                                <i class="fab fa-whatsapp"></i> {{ $student->mother_phone }} <small style="color:#64748b;">(I)</small>
                                            </a>
                                        </div>
                                    @endif
                                    @if(!$student->father_phone && !$student->mother_phone)
                                        <span style="color:#94a3b8;">{{ $student->parent_phone ?: '—' }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Tanggal Diterima --}}
                            <td>
                                <span style="font-size:0.82rem; color:#475569;">{{ $student->admission_date ?: '—' }}</span>
                            </td>

                            {{-- Status Masuk (Fullday / Boarding) --}}
                            <td style="text-align:center;">
                                <span style="font-size:0.78rem; font-weight:700; padding:3px 8px; border-radius:6px; background:{{ $student->school_program == 'Boarding' ? '#ede9fe; color:#7c3aed' : '#ecfdf5; color:#059669' }};">
                                    {{ $student->school_program ?: 'Reguler' }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'tu']))
                            <td>
                                <div class="row-actions" style="justify-content:center;">
                                    <a href="{{ route('students.show', $student->id) }}"
                                       class="row-btn row-btn-view" title="Lihat Biodata Lengkap">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.portfolio', $student->id) }}"
                                       target="_blank"
                                       class="row-btn" style="background: #8b5cf6; color: white;" title="Portofolio Siswa (Siap Cetak)">
                                        <i class="fas fa-id-card-alt"></i>
                                    </a>
                                    <a href="{{ route('students.card', $student->id) }}"
                                       target="_blank"
                                       class="row-btn" style="background: #0284c7; color: white;" title="Cetak Kartu Siswa">
                                        <i class="fas fa-id-card"></i>
                                    </a>
                                    <a href="{{ route('students.certificate', $student->id) }}"
                                       target="_blank"
                                       class="row-btn" style="background: #f59e0b; color: white;" title="Cetak Piagam Digital">
                                        <i class="fas fa-certificate"></i>
                                    </a>
                                    @if(in_array(Auth::user()->role, ['admin', 'tu']))
                                    <a href="{{ route('students.edit', array_merge(['student' => $student->id], request()->all())) }}"
                                       class="row-btn row-btn-edit" title="Edit Data Siswa">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->role == 'admin')
                                    <button type="button"
                                             onclick="deleteIndividual('{{ route('students.destroy', $student->id) }}')"
                                             class="row-btn row-btn-delete" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ in_array(Auth::user()->role, ['admin', 'headmaster', 'tu']) ? 16 : 15 }}">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fas fa-users-slash"></i></div>
                                    <h4>Tidak ada data siswa</h4>
                                    <p>
                                        @if(request('search'))
                                            Hasil pencarian "{{ request('search') }}" tidak ditemukan.
                                        @else
                                            Belum ada siswa yang terdaftar.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 2. MOBILE CARD VIEW (< 769px) --}}
            <div class="mobile-students-view">
                @forelse($students as $i => $student)
                <div class="mobile-student-card" id="student-card-{{ $student->id }}">
                    {{-- Header: Avatar, Name, NIS, Class badge & Checkbox --}}
                    <div class="m-card-header">
                        <div class="m-card-user">
                            @if(Auth::user()->role == 'admin')
                                <div class="m-card-check">
                                    <input type="checkbox" name="ids[]" value="{{ $student->id }}"
                                           class="check-item custom-check" onchange="onStudentCheck('{{ $student->id }}', this.checked)">
                                </div>
                            @endif
                            <div class="m-card-avatar">
                                @if($student->photo)
                                    <img src="{{ asset('storage/'.$student->photo) }}" alt="{{ $student->name }}">
                                @else
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="m-card-title-group">
                                <h4 class="m-student-name">{{ $student->name }}</h4>
                                <div class="m-student-meta">
                                    <span class="m-student-number">#{{ $students->firstItem() + $i }}</span>
                                    @if($student->nis)
                                        <span class="m-nis-pill"><i class="fas fa-id-badge"></i> {{ $student->nis }}</span>
                                    @endif
                                    @if($student->gender)
                                        <span style="font-size:0.75rem; font-weight:700; color:{{ $student->gender == 'L' ? '#0284c7' : '#db2777' }};">
                                            ({{ $student->gender }})
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="m-card-badge">
                            <span class="class-badge">{{ $student->class ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Body: Biodata Lengkap Siswa --}}
                    <div class="m-card-body" style="padding: 12px 14px; font-size: 0.82rem; display: flex; flex-direction: column; gap: 6px;">
                        @if($student->nisn)
                            <div><span style="color:#64748b;">NISN:</span> <strong>{{ $student->nisn }}</strong></div>
                        @endif
                        @if($student->birth_place_date)
                            <div><span style="color:#64748b;">TTL:</span> {{ $student->birth_place_date }}</div>
                        @endif
                        @if($student->address)
                            <div><span style="color:#64748b;">Alamat:</span> {{ $student->address }}</div>
                        @endif
                        @if($student->previous_school)
                            <div><span style="color:#64748b;">Asal TK:</span> {{ $student->previous_school }}</div>
                        @endif
                        <div style="border-top:1px dashed #e2e8f0; padding-top:6px; margin-top:2px;">
                            <div><span style="color:#64748b;">Orang Tua:</span> Ayah: <strong>{{ $student->father_name ?: '-' }}</strong> • Ibu: <strong>{{ $student->mother_name ?: '-' }}</strong></div>
                            @if($student->father_phone || $student->mother_phone)
                                <div style="margin-top:2px;">
                                    @if($student->father_phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->father_phone) }}" target="_blank" style="color:#16a34a; text-decoration:none; margin-right:8px; font-weight:600;">
                                            <i class="fab fa-whatsapp"></i> WA Ayah: {{ $student->father_phone }}
                                        </a>
                                    @endif
                                    @if($student->mother_phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->mother_phone) }}" target="_blank" style="color:#16a34a; text-decoration:none; font-weight:600;">
                                            <i class="fab fa-whatsapp"></i> WA Ibu: {{ $student->mother_phone }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f1f5f9; padding-top:6px; margin-top:2px;">
                            <span style="color:#64748b; font-size:0.75rem;">Masuk: {{ $student->admission_date ?: '-' }}</span>
                            <span style="font-size:0.75rem; font-weight:700; padding:2px 6px; border-radius:4px; background:{{ $student->school_program == 'Boarding' ? '#ede9fe; color:#7c3aed' : '#ecfdf5; color:#059669' }};">
                                {{ $student->school_program ?: 'Reguler' }}
                            </span>
                        </div>
                    </div>

                    {{-- Footer: Actions --}}
                    @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'tu']))
                        <div class="m-card-actions" style="display:flex; gap:6px; padding:10px 14px; background:#f8fafc; border-top:1px solid #e2e8f0; flex-wrap:wrap;">
                            <a href="{{ route('students.show', $student->id) }}" class="m-btn m-btn-view" style="flex:1; text-align:center; padding:6px 8px; border-radius:6px; font-size:0.78rem; text-decoration:none;" title="Lihat Biodata Lengkap">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('students.portfolio', $student->id) }}" target="_blank" class="m-btn" style="flex:1; text-align:center; padding:6px 8px; border-radius:6px; font-size:0.78rem; background:#8b5cf6; color:white; text-decoration:none;" title="Cetak Portofolio">
                                <i class="fas fa-id-card-alt"></i> Portofolio
                            </a>
                            <a href="{{ route('students.card', $student->id) }}" target="_blank" class="m-btn" style="flex:1; text-align:center; padding:6px 8px; border-radius:6px; font-size:0.78rem; background:#0284c7; color:white; text-decoration:none;" title="Cetak Kartu">
                                <i class="fas fa-id-card"></i> Kartu
                            </a>
                            @if(in_array(Auth::user()->role, ['admin', 'tu']))
                                <a href="{{ route('students.edit', array_merge(['student' => $student->id], request()->all())) }}" class="m-btn m-btn-edit" style="flex:1; text-align:center; padding:6px 8px; border-radius:6px; font-size:0.78rem; text-decoration:none;" title="Edit Data Siswa">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                            @endif
                            @if(Auth::user()->role == 'admin')
                                <button type="button" onclick="deleteIndividual('{{ route('students.destroy', $student->id) }}')" class="m-btn m-btn-delete" style="flex:1; text-align:center; padding:6px 8px; border-radius:6px; font-size:0.78rem; cursor:pointer;" title="Hapus Siswa">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
                @empty
                <div class="empty-state" style="background:#fff; border-radius:14px; padding:36px 16px;">
                    <div class="empty-icon"><i class="fas fa-users-slash"></i></div>
                    <h4>Tidak ada data siswa</h4>
                    <p>
                        @if(request('search'))
                            Hasil pencarian "{{ request('search') }}" tidak ditemukan.
                        @else
                            Belum ada siswa yang terdaftar.
                        @endif
                    </p>
                </div>
                @endforelse
            </div>
        </form>

        {{-- Pagination --}}
        @if($students->hasPages())
        <div class="pagination-wrap">
            <span class="pagination-info">
                Menampilkan {{ $students->firstItem() }}–{{ $students->lastItem() }} dari {{ $students->total() }} siswa
            </span>
            {{ $students->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>{{-- end .students-page --}}


{{-- ===== HIDDEN FORM for Individual Delete ===== --}}
<form id="individualDeleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>


{{-- ===== IMPORT MODAL ===== --}}
<div id="importModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-file-import" style="color:#7367f0; margin-right:8px;"></i>Import Data Siswa</h3>
            <button class="modal-close" onclick="closeImportModal()"><i class="fas fa-times"></i></button>
        </div>

        <div class="modal-tabs">
            <button type="button" class="modal-tab-btn active" id="tabBtnPaste" onclick="switchTab('paste')">
                <i class="fas fa-paste" style="margin-right:5px;"></i>Copy-Paste
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnFile" onclick="switchTab('file')">
                <i class="fas fa-file-excel" style="margin-right:5px;"></i>Upload Excel
            </button>
        </div>

        {{-- Paste Panel --}}
        <div id="panelPaste">
            <p class="form-hint">
                Copy data dari Excel lalu paste di bawah ini.<br>
                <strong>Format kolom:</strong> Nama &nbsp;|&nbsp; Kelas &nbsp;|&nbsp; Eskul &nbsp;|&nbsp; Pembina
            </p>
            <form action="{{ route('students.store_bulk') }}" method="POST">
                @csrf
                <textarea name="bulk_data" class="form-textarea"
                          placeholder="Adi&#10;1A&#10;Futsal&#10;Pak Budi
Budi&#10;1B&#10;Tari&#10;Bu Siti"></textarea>
                <div class="modal-footer">
                    <button type="button" onclick="closeImportModal()" class="btn-cancel">Batal</button>
                    <button type="submit" class="btn-submit purple">
                        <i class="fas fa-upload" style="margin-right:6px;"></i>Import Data
                    </button>
                </div>
            </form>
        </div>

        {{-- File Panel --}}
        <div id="panelFile" style="display:none;">
            <form action="{{ route('students.import_excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="drop-zone" onclick="document.getElementById('fileInput').click()"
                     ondragover="event.preventDefault(); this.classList.add('dragover')"
                     ondragleave="this.classList.remove('dragover')"
                     ondrop="handleDrop(event)">
                    <i class="fas fa-file-excel"></i>
                    <p>Klik atau seret file Excel ke sini</p>
                    <small>Format: .xlsx, .xls, .csv | Header: Nama, Kelas, Eskul, Pembina</small>
                    <input type="file" id="fileInput" name="file"
                           accept=".xlsx,.xls,.csv" required
                           style="display:none;" onchange="updateFileName(this)">
                    <p id="fileNameDisplay"></p>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center;">
                    <a href="{{ route('import-portal.template') }}" class="btn-cancel" style="text-decoration:none; color:#7367f0; border:1.5px solid #7367f0; display:inline-flex; align-items:center; gap:6px;">
                        <i class="fas fa-download"></i> Unduh Template Excel
                    </a>
                    <div>
                        <button type="button" onclick="closeImportModal()" class="btn-cancel">Batal</button>
                        <button type="submit" class="btn-submit green">
                            <i class="fas fa-cloud-upload-alt" style="margin-right:6px;"></i>Upload & Import
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ===== CONFIRM DELETE MODAL ===== --}}
<div id="confirmModal" class="modal-overlay">
    <div class="confirm-modal-box">
        <div class="confirm-icon-wrap">
            <i class="fas fa-trash-alt"></i>
        </div>
        <h3>Konfirmasi Hapus</h3>
        <p id="confirmMessage">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="confirm-actions">
            <button onclick="closeConfirmModal()" class="btn-confirm-cancel">Batal</button>
            <button id="btnConfirmAction" class="btn-confirm-delete">Ya, Hapus!</button>
        </div>
    </div>
</div>


<script>
    let confirmCallback = null;

    // ===== CHECKBOX & BULK SELECTION =====
    function onStudentCheck(id, isChecked) {
        document.querySelectorAll(`input[name="ids[]"][value="${id}"]`).forEach(cb => {
            cb.checked = isChecked;
        });
        updateBulkUI();
    }

    function toggleAll(source) {
        const isChecked = source.checked;
        const checkAllDesk = document.getElementById('checkAll');
        const checkAllMob  = document.getElementById('checkAllMobile');
        if (checkAllDesk) checkAllDesk.checked = isChecked;
        if (checkAllMob)  checkAllMob.checked  = isChecked;

        document.querySelectorAll('input[name="ids[]"]').forEach(cb => {
            cb.checked = isChecked;
        });
        updateBulkUI();
    }

    function toggleBtn() {
        updateBulkUI();
    }

    function updateBulkUI() {
        const checkedBoxes = document.querySelectorAll('input[name="ids[]"]:checked');
        const uniqueIds = new Set();
        checkedBoxes.forEach(cb => uniqueIds.add(cb.value));

        const count = uniqueIds.size;
        const btn   = document.getElementById('btnBulkDelete');
        const info  = document.getElementById('bulkInfo');
        
        if (count > 0) {
            if (btn) btn.style.display  = 'inline-flex';
            if (info) {
                info.style.display = 'block';
                info.innerText     = count + ' siswa dipilih';
            }
        } else {
            if (btn) btn.style.display  = 'none';
            if (info) info.style.display = 'none';
        }
    }

    // ===== CONFIRM MODAL =====
    function showConfirmModal(message, callback) {
        document.getElementById('confirmMessage').innerText = message;
        confirmCallback = callback;
        document.getElementById('confirmModal').classList.add('show');
        document.getElementById('btnConfirmAction').focus();
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.remove('show');
        confirmCallback = null;
    }

    document.getElementById('btnConfirmAction').onclick = function () {
        if (confirmCallback) confirmCallback();
        closeConfirmModal();
    };

    function confirmBulkDelete() {
        const checkedBoxes = document.querySelectorAll('input[name="ids[]"]:checked');
        const uniqueIds = Array.from(new Set(Array.from(checkedBoxes).map(cb => cb.value)));
        if (uniqueIds.length === 0) return;

        showConfirmModal('Apakah Anda yakin ingin menghapus ' + uniqueIds.length + ' data siswa yang dipilih? Tindakan ini tidak dapat dibatalkan.', function () {
            const form = document.getElementById('bulkDeleteForm');
            // Disable existing checkboxes to avoid duplicate values in request
            form.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.disabled = true);
            // Append clean unique hidden inputs
            uniqueIds.forEach(id => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'ids[]';
                hidden.value = id;
                form.appendChild(hidden);
            });
            form.submit();
        });
    }

    function deleteIndividual(url) {
        showConfirmModal('Apakah Anda yakin ingin menghapus data siswa ini secara permanen?', function () {
            const form = document.getElementById('individualDeleteForm');
            form.action = url;
            form.submit();
        });
    }

    // ===== IMPORT MODAL =====
    function openImportModal()  { document.getElementById('importModal').classList.add('show'); }
    function closeImportModal() { document.getElementById('importModal').classList.remove('show'); }

    function switchTab(tab) {
        document.getElementById('panelPaste').style.display = tab === 'paste' ? 'block' : 'none';
        document.getElementById('panelFile').style.display  = tab === 'file'  ? 'block' : 'none';
        document.getElementById('tabBtnPaste').classList.toggle('active', tab === 'paste');
        document.getElementById('tabBtnFile').classList.toggle('active',  tab === 'file');
    }

    function updateFileName(input) {
        const name = input.files[0] ? input.files[0].name : '';
        document.getElementById('fileNameDisplay').innerText = name ? '📄 ' + name : '';
    }

    function handleDrop(e) {
        e.preventDefault();
        e.currentTarget.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) {
            const fi = document.getElementById('fileInput');
            const dt = new DataTransfer();
            dt.items.add(file);
            fi.files = dt.files;
            document.getElementById('fileNameDisplay').innerText = '📄 ' + file.name;
        }
    }

    // ===== CLOSE MODAL ON OUTSIDE CLICK =====
    document.addEventListener('click', function (e) {
        const confirmModal = document.getElementById('confirmModal');
        const importModal  = document.getElementById('importModal');
        if (e.target === confirmModal) closeConfirmModal();
        if (e.target === importModal)  closeImportModal();
    });

    // ===== KEYBOARD SHORTCUTS =====
    window.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeConfirmModal(); closeImportModal(); }
        if (e.key === 'Enter') {
            const cm = document.getElementById('confirmModal');
            if (cm.classList.contains('show') && confirmCallback) {
                e.preventDefault();
                confirmCallback();
                closeConfirmModal();
            }
        }
        if (e.key === 'Delete') {
            if (['INPUT','TEXTAREA'].includes(document.activeElement.tagName)) return;
            const count = document.querySelectorAll('.check-item:checked').length;
            if (count > 0) { e.preventDefault(); confirmBulkDelete(); }
        }
    });
</script>
<script src="{{ asset('js/student-search.js') }}"></script>
@endsection
