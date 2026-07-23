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
        background: linear-gradient(135deg, #7367f0, #9c8ff5);
        color: white;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        min-width: 42px;
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
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(115,103,240,0.1);
        color: #7367f0;
        border: 1px solid rgba(115,103,240,0.15);
        white-space: nowrap;
    }

    .eskul-chip-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #7367f0;
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
                <form action="{{ route('students.assign_grade_6_tahfidz') }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin mem-plotting seluruh siswa Kelas 6 ke Kelompok Tahfidz untuk Semester 2?');">
                    @csrf
                    <button type="submit" class="act-btn" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white;" title="Plotting Massal Siswa Kelas 6 ke Kelompok Tahfidz Semester 2">
                        <i class="fas fa-quran"></i> Plotting Tahfidz Kelas 6
                    </button>
                </form>

                <button id="btnBulkDelete" onclick="confirmBulkDelete()"
                        class="act-btn act-btn-danger" style="display:none;">
                    <i class="fas fa-trash"></i> Hapus Terpilih
                </button>

                <a href="{{ route('students.create') }}" class="act-btn act-btn-primary">
                    <i class="fas fa-plus"></i> Tambah
                </a>

                <button class="act-btn act-btn-success" onclick="openImportModal()">
                    <i class="fas fa-file-excel"></i> Import
                </button>

                <a href="{{ route('students.backup') }}" class="act-btn act-btn-dark">
                    <i class="fas fa-download"></i> Backup
                </a>
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
        $total     = $students->total();
        $withEskul = $students->getCollection()->filter(fn($s) => $s->eskuls->count() > 0)->count();
        $noEskul   = $students->getCollection()->filter(fn($s) => $s->eskuls->count() === 0)->count();
        $pageCount = $students->count();
    @endphp
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h3>{{ $total }}</h3>
                <p>Total Siswa</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-running"></i></div>
            <div class="stat-info">
                <h3>{{ $withEskul }}</h3>
                <p>Ikut Eskul</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-user-times"></i></div>
            <div class="stat-info">
                <h3>{{ $noEskul }}</h3>
                <p>Belum Eskul</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-list-ol"></i></div>
            <div class="stat-info">
                <h3>{{ $pageCount }}</h3>
                <p>Halaman Ini</p>
            </div>
        </div>
    </div>

    {{-- ===== TABLE CARD ===== --}}
    <div class="table-card">
        <div class="table-card-header">
            <h3><i class="fas fa-table" style="color:#7367f0; margin-right:6px;"></i>Data Siswa {{ $status == 'graduated' ? '(Lulus)' : '(Aktif)' }}</h3>
            <span class="bulk-info" id="bulkInfo">0 siswa dipilih</span>
        </div>

        <form action="{{ route('students.destroy_bulk') }}" method="POST" id="bulkDeleteForm">
            @csrf
            @method('DELETE')

            <div style="overflow-x: auto;">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th style="width:44px; text-align:center;">
                                @if(Auth::user()->role == 'admin')
                                    <input type="checkbox" id="checkAll" class="custom-check" onclick="toggleAll(this)">
                                @else
                                    #
                                @endif
                            </th>
                            <th style="width:40px; text-align:center;">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Ekstrakurikuler</th>
                            <th>Pembina</th>
                            @if(Auth::user()->role == 'admin')
                            <th style="text-align:center;">Aksi</th>
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
                                           class="check-item custom-check" onclick="toggleBtn()">
                                @else
                                    <span class="col-no">-</span>
                                @endif
                            </td>

                            {{-- No --}}
                            <td class="col-no">{{ $students->firstItem() + $i }}</td>

                            {{-- NIS --}}
                            <td>
                                @if($student->nis)
                                    <span class="nis-badge">{{ $student->nis }}</span>
                                @else
                                    <span class="nis-empty">—</span>
                                @endif
                            </td>

                            {{-- Nama --}}
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
                                        <span>Siswa {{ $student->class ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Kelas --}}
                            <td>
                                <span class="class-badge">{{ $student->class ?? '-' }}</span>
                            </td>

                            {{-- Eskul --}}
                            <td>
                                @if($student->eskuls->count())
                                    <div class="eskul-chips">
                                        @foreach($student->eskuls as $eskul)
                                            <span class="eskul-chip">
                                                <span class="eskul-chip-dot"></span>
                                                {{ $eskul->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="no-eskul">Belum terdaftar</span>
                                @endif
                            </td>

                            {{-- Pembina --}}
                            <td>
                                @if($student->eskuls->count())
                                    <div class="instructor-list">
                                        @foreach($student->eskuls as $eskul)
                                            <span class="instructor-name">
                                                <i class="fas fa-user-tie"></i>
                                                {{ $eskul->instructor_name ?? 'Belum ada' }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="no-eskul">—</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            @if(Auth::user()->role == 'admin')
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('students.show', $student->id) }}"
                                       class="row-btn row-btn-view" title="Lihat Riwayat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', array_merge(['student' => $student->id], request()->all())) }}"
                                       class="row-btn row-btn-edit" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button"
                                            onclick="deleteIndividual('{{ route('students.destroy', $student->id) }}')"
                                            class="row-btn row-btn-delete" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role == 'admin' ? 8 : 7 }}">
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
                <div class="modal-footer">
                    <button type="button" onclick="closeImportModal()" class="btn-cancel">Batal</button>
                    <button type="submit" class="btn-submit green">
                        <i class="fas fa-cloud-upload-alt" style="margin-right:6px;"></i>Upload & Import
                    </button>
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

    // ===== CHECKBOX =====
    function toggleAll(source) {
        document.querySelectorAll('.check-item').forEach(cb => cb.checked = source.checked);
        toggleBtn();
    }

    function toggleBtn() {
        const checked = document.querySelectorAll('.check-item:checked');
        const btn   = document.getElementById('btnBulkDelete');
        const info  = document.getElementById('bulkInfo');
        if (checked.length > 0) {
            btn.style.display  = 'inline-flex';
            info.style.display = 'block';
            info.innerText     = checked.length + ' siswa dipilih';
        } else {
            btn.style.display  = 'none';
            info.style.display = 'none';
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
        const count = document.querySelectorAll('.check-item:checked').length;
        if (count === 0) return;
        showConfirmModal('Apakah Anda yakin ingin menghapus ' + count + ' data siswa yang dipilih? Tindakan ini tidak dapat dibatalkan.', function () {
            document.getElementById('bulkDeleteForm').submit();
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
