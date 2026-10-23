@extends('layouts.app')

@section('title', 'Kelola Ekstrakurikuler')
@section('page-title', 'Kelola Ekstrakurikuler')

@push('styles')
<style>
    /* Container Utama */
    .eskul-container {
        max-width: 1240px;
        margin: 0 auto;
        padding-bottom: 50px;
    }

    /* 1. Hero Banner */
    .eskul-hero-banner {
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

    .hero-left {
        max-width: 720px;
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

    .hero-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* 2. Summary KPI Cards */
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
    .stat-metric-card.border-purple { border-left: 5px solid #8b5cf6; }
    .stat-metric-card.border-amber { border-left: 5px solid #f59e0b; }

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

    .text-purple { color: #7c3aed; }
    .bg-purple-light { background: #f3e8ff; }

    .text-amber { color: #d97706; }
    .bg-amber-light { background: #fef3c7; }

    /* Alert Box */
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

    /* 4. Search Toolbar */
    .search-toolbar-card {
        background: white;
        border-radius: 18px;
        padding: 14px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }

    .search-input-box {
        position: relative;
        flex: 1;
        max-width: 480px;
        display: flex;
        align-items: center;
    }

    .search-icon {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        font-size: 0.95rem;
        pointer-events: none;
    }

    .search-field {
        width: 100%;
        padding: 10px 38px 10px 40px;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        background: #f8fafc;
        font-size: 0.92rem;
        color: #0f172a;
        outline: none;
        transition: all 0.2s;
    }

    .search-field:focus {
        background: white;
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }

    .search-clear-btn {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px;
    }

    .search-clear-btn:hover {
        color: #dc2626;
    }

    .search-meta-text {
        font-size: 0.88rem;
        color: #64748b;
    }

    /* 5. Grid Card Eskul (Soft, Elegan) */
    .eskul-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
        gap: 18px;
        margin-bottom: 50px;
    }

    .eskul-card {
        background: white;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .eskul-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }

    /* Card Header */
    .eskul-card-header {
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        padding: 16px 18px;
        border-bottom: 1px solid #f1f5f9;
    }

    .header-top-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 8px;
    }

    .title-wrapper {
        min-width: 0;
        flex: 1;
    }

    .eskul-name {
        font-size: 1.12rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .eskul-alias-sub {
        font-size: 0.75rem;
        color: #64748b;
        font-style: italic;
        display: block;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .member-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .member-chip.chip-full {
        background: #fffbeb;
        color: #b45309;
        border-color: #fde68a;
    }

    /* Sesi Badges */
    .sesi-badges-row {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 8px;
    }

    .sesi-badge {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 6px;
    }

    .sesi-all { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .sesi-1 { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .sesi-2 { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .sesi-3 { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .sesi-4 { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }

    /* Jadwal Row */
    .schedule-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
    }

    .schedule-row i {
        color: #0284c7;
    }

    .schedule-row span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Card Body */
    .eskul-card-body {
        padding: 14px 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 12px;
    }

    .instructor-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .instructor-avatar {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: #0369a1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.05rem;
        flex-shrink: 0;
        border: 1px solid #bae6fd;
    }

    .instructor-details {
        min-width: 0;
        flex: 1;
    }

    .instructor-label {
        font-size: 0.68rem;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .instructor-name {
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.3;
    }

    /* Settings Quick Bar */
    .settings-quick-bar {
        border-top: 1px dashed #e2e8f0;
        padding-top: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        font-size: 0.85rem;
    }

    .toggle-active-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        user-select: none;
    }

    .toggle-active-label input {
        display: none;
    }

    .toggle-custom-box {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 1.5px solid #cbd5e1;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        color: transparent;
        transition: all 0.2s;
    }

    .toggle-active-label input:checked + .toggle-custom-box {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }

    .toggle-text {
        font-weight: 600;
        color: #475569;
        font-size: 0.85rem;
    }

    .quota-input-wrapper {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .quota-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #64748b;
    }

    .eskul-quota-input {
        width: 52px;
        padding: 4px 6px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 800;
        text-align: center;
        color: #0f172a;
        outline: none;
        transition: all 0.2s;
        background: #f8fafc;
    }

    .eskul-quota-input:focus {
        background: white;
        border-color: #0284c7;
        box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.15);
    }

    /* Settings Read Only */
    .settings-read-only {
        border-top: 1px dashed #e2e8f0;
        padding-top: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.82rem;
    }

    .quota-readonly-text {
        color: #475569;
    }

    .status-active-pill {
        color: #059669;
        font-weight: 700;
    }

    .status-inactive-pill {
        color: #dc2626;
        font-weight: 700;
    }

    /* Card Actions Footer */
    .eskul-card-footer {
        padding: 12px 16px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-card-action {
        flex: 1;
        padding: 8px 10px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.82rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-action-detail {
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .btn-action-detail:hover {
        background: #0284c7;
        color: white;
        border-color: #0284c7;
    }

    .btn-action-excel {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .btn-action-excel:hover {
        background: #059669;
        color: white;
        border-color: #059669;
    }

    .btn-card-icon {
        width: 35px;
        height: 35px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        font-size: 0.88rem;
    }

    .btn-action-edit {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .btn-action-edit:hover {
        background: #f59e0b;
        color: white;
        border-color: #f59e0b;
    }

    .btn-action-delete {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .btn-action-delete:hover {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
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
    }

    .btn-solid:hover { transform: translateY(-2px); }
    .btn-solid:active { transform: translateY(0); }

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

    .btn-solid-amber {
        background: #d97706;
        color: white;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
    }
    .btn-solid-amber:hover {
        background: #b45309;
        box-shadow: 0 6px 16px rgba(217, 119, 6, 0.45);
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

    .btn-solid-danger {
        background: #dc2626;
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }
    .btn-solid-danger:hover {
        background: #b91c1c;
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.45);
        color: white;
    }

    /* Empty State */
    .empty-state-card {
        grid-column: 1 / -1;
        background: white;
        border-radius: 20px;
        padding: 50px 20px;
        text-align: center;
        border: 1px solid #e2e8f0;
    }

    .empty-icon-circle {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        background: #f1f5f9;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 14px;
    }

    .empty-state-card h3 {
        margin: 0 0 6px;
        font-size: 1.15rem;
        font-weight: 800;
        color: #1e293b;
    }

    .empty-state-card p {
        color: #64748b;
        margin: 0;
        font-size: 0.92rem;
    }

    /* Modal Styling */
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
        max-width: 580px;
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

    .modal-body-content {
        padding: 24px;
    }

    .modal-info-summary {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .info-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        display: block;
        margin-bottom: 2px;
    }

    .info-val {
        color: #0f172a;
        font-size: 0.95rem;
    }

    .empty-members-box {
        text-align: center;
        padding: 30px 15px;
        color: #94a3b8;
        font-size: 0.92rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .empty-members-box i {
        font-size: 2rem;
        color: #cbd5e1;
    }

    .members-table-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        max-height: 400px;
        overflow-y: auto;
    }

    .members-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }

    .members-table th {
        background: #f8fafc;
        padding: 12px 16px;
        color: #475569;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.78rem;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .members-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .class-pill {
        background: #e0f2fe;
        color: #0369a1;
        font-weight: 700;
        font-size: 0.78rem;
        padding: 3px 10px;
        border-radius: 999px;
    }

    .form-row-modern {
        margin-bottom: 16px;
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

    .field-hint {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 4px;
        display: block;
    }

    .form-grid-two {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .target-group-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 12px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
    }

    .target-group-option {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-size: 0.88rem;
        color: #334155;
    }

    .target-group-option input {
        width: 17px;
        height: 17px;
        accent-color: #0284c7;
        cursor: pointer;
    }

    .target-group-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 2px 0;
    }

    .notice-info-box {
        background: #eef2ff;
        color: #3730a3;
        border: 1px solid #c7d2fe;
        padding: 12px 14px;
        border-radius: 12px;
        font-size: 0.85rem;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 16px;
        line-height: 1.4;
    }

    .notice-info-box i {
        margin-top: 2px;
        color: #4f46e5;
    }

    .notice-warning-box {
        background: #fdf2f8;
        color: #9d174d;
        border: 1px solid #fbcfe8;
        padding: 12px 14px;
        border-radius: 12px;
        font-size: 0.85rem;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 16px;
        line-height: 1.4;
    }

    .notice-warning-box i {
        margin-top: 2px;
        color: #db2777;
    }

    .lock-mode-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        padding: 14px 16px;
        border-radius: 12px;
        margin-top: 14px;
    }

    .lock-mode-label {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        cursor: pointer;
    }

    .lock-mode-label input {
        width: 18px;
        height: 18px;
        accent-color: #d97706;
        margin-top: 2px;
    }

    .lock-mode-title {
        color: #92400e;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 2px;
    }

    .lock-mode-desc {
        color: #78350f;
        font-size: 0.8rem;
        line-height: 1.4;
        display: block;
    }

    .delete-icon-circle {
        width: 64px;
        height: 64px;
        background: #fee2e2;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #dc2626;
        font-size: 1.8rem;
        margin-bottom: 14px;
    }

    .modal-dialog-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 24px;
        background: #fafafa;
        border-top: 1px solid #f1f5f9;
        border-radius: 0 0 22px 22px;
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
    /* RESPONSIVE DESIGN (TABLET & HP)            */
    /* ========================================== */
    @media (max-width: 992px) {
        .eskul-hero-banner {
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-right {
            width: 100%;
        }

        .hero-right .btn-solid {
            flex: 1;
        }

        .eskul-grid {
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        }
    }

    @media (max-width: 640px) {
        .eskul-hero-banner {
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

        .hero-right {
            flex-direction: column;
            width: 100%;
        }

        .hero-right .btn-solid {
            width: 100%;
        }

        .search-toolbar-card {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input-box {
            max-width: 100%;
        }

        .eskul-grid {
            grid-template-columns: 1fr;
        }

        .form-grid-two {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
@php
    $totalEskuls = $eskuls->count();
    $totalStudents = $eskuls->sum(function($e) { return $e->students->count(); });
    $totalQuota = $eskuls->sum('quota');
    $activeCount = $eskuls->where('is_active', true)->count();
@endphp

<div class="eskul-container">

    <!-- ========================================== -->
    <!-- 1. HERO BANNER IDENTITAS EKSTRAKURIKULER    -->
    <!-- ========================================== -->
    <div class="eskul-hero-banner">
        <div class="hero-left">
            <div class="hero-badge-tag">
                <i class="fas fa-basketball-ball"></i>
                <span>Manajemen Ekstrakurikuler SIMUTQIN</span>
            </div>
            <h2 class="hero-title">Kegiatan Ekstrakurikuler</h2>
            <p class="hero-description">
                Kelola daftar kegiatan bakat & minat siswa, jadwal pelaksanaan mingguan, guru pembina, kuota, serta pengaturan formulir pendaftaran.
            </p>
        </div>

        @if(Auth::user()->role == 'admin')
        <div class="hero-right">
            <button type="button" onclick="openModal('create-eskul-modal')" class="btn-solid btn-solid-blue" title="Tambah Ekstrakurikuler Baru">
                <i class="fas fa-plus"></i>
                <span>Tambah Eskul</span>
            </button>
            <button type="button" onclick="openModal('update-schedule-modal')" class="btn-solid btn-solid-amber" title="Atur Jadwal Pelaksanaan Serentak">
                <i class="fas fa-clock"></i>
                <span>Atur Jadwal</span>
            </button>
        </div>
        @endif
    </div>

    <!-- ========================================== -->
    <!-- 2. SUMMARY STAT CARDS (KPI WIDGETS)       -->
    <!-- ========================================== -->
    <div class="stat-grid-summary">
        <!-- Card 1: Total Eskul -->
        <div class="stat-metric-card border-blue">
            <div class="metric-body">
                <div>
                    <span class="metric-label">Total Kegiatan Eskul</span>
                    <h3 class="metric-number text-blue">{{ $totalEskuls }}</h3>
                    <div class="metric-footnote"><i class="fas fa-layer-group"></i> Terdaftar di Sistem</div>
                </div>
                <div class="metric-icon-box bg-blue-light text-blue">
                    <i class="fas fa-shapes"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Siswa Terdaftar -->
        <div class="stat-metric-card border-green">
            <div class="metric-body">
                <div>
                    <span class="metric-label">Total Siswa Terdaftar</span>
                    <h3 class="metric-number text-green">{{ $totalStudents }}</h3>
                    <div class="metric-footnote"><i class="fas fa-user-graduate"></i> Anggota Aktif Semester Ini</div>
                </div>
                <div class="metric-icon-box bg-green-light text-green">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Kapasitas Kuota -->
        <div class="stat-metric-card border-purple">
            <div class="metric-body">
                <div>
                    <span class="metric-label">Kapasitas Kuota</span>
                    <h3 class="metric-number text-purple">{{ $totalQuota }}</h3>
                    <div class="metric-footnote"><i class="fas fa-chair"></i> Total Kursi Tersedia</div>
                </div>
                <div class="metric-icon-box bg-purple-light text-purple">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Aktif di Formulir -->
        <div class="stat-metric-card border-amber">
            <div class="metric-body">
                <div>
                    <span class="metric-label">Aktif di Formulir</span>
                    <h3 class="metric-number text-amber">{{ $activeCount }}</h3>
                    <div class="metric-footnote"><i class="fas fa-toggle-on"></i> Siap Dipilih Wali Murid</div>
                </div>
                <div class="metric-icon-box bg-amber-light text-amber">
                    <i class="fas fa-check-double"></i>
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
    <!-- 4. PENCARIAN & FILTER CEPAT (LIVE SEARCH)  -->
    <!-- ========================================== -->
    <div class="search-toolbar-card">
        <div class="search-input-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="eskulSearchInput" class="search-field" placeholder="Cari kegiatan ekstrakurikuler atau nama guru pembina..." onkeyup="filterEskuls()">
            <button type="button" id="clearSearchBtn" onclick="clearSearch()" class="search-clear-btn" style="display: none;" title="Hapus pencarian">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="search-meta-text">
            Menampilkan <span id="visibleCount" style="font-weight: 800; color: #0f172a;">{{ $totalEskuls }}</span> dari {{ $totalEskuls }} kegiatan
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 5. GRID CARD EKSTRAKURIKULER (SOFT & ELEGAN) -->
    <!-- ========================================== -->
    <div class="eskul-grid" id="eskulGrid">
        @forelse($eskuls as $eskul)
            @php
                $currentHistory = $eskul->histories->first();
                $displayName = ($currentHistory && !empty($currentHistory->alias_name)) ? $currentHistory->alias_name : $eskul->name;
                $displayInstructor = ($currentHistory && !empty($currentHistory->instructor_name)) ? $currentHistory->instructor_name : $eskul->instructor_name;
                $displaySchedule = ($currentHistory && !empty($currentHistory->schedule)) ? $currentHistory->schedule : $eskul->schedule;
                $studentCount = $eskul->students->count();
                $quota = $eskul->quota;
                $isFull = $quota > 0 && $studentCount >= $quota;
            @endphp

            <div class="eskul-card" 
                 data-name="{{ strtolower($displayName . ' ' . $eskul->name) }}" 
                 data-instructor="{{ strtolower($displayInstructor ?? '') }}"
                 data-schedule="{{ strtolower($displaySchedule ?? '') }}">
                
                <!-- Card Header (Soft & Clean) -->
                <div class="eskul-card-header">
                    <div class="header-top-row">
                        <div class="title-wrapper">
                            <h3 class="eskul-name" title="{{ $displayName }}">{{ $displayName }}</h3>
                            @if($eskul->name != $displayName)
                                <span class="eskul-alias-sub">({{ $eskul->name }})</span>
                            @endif
                        </div>
                        <div class="member-chip {{ $isFull ? 'chip-full' : '' }}" title="{{ $studentCount }} dari {{ $quota }} kuota terisi">
                            <i class="fas fa-users"></i>
                            <span>{{ $studentCount }}/{{ $quota }}</span>
                        </div>
                    </div>

                    <!-- Target Group Sesi Badges -->
                    <div class="sesi-badges-row">
                        @php
                            $groups = $eskul->target_groups;
                            $sesiLabels = [
                                'all'    => ['label' => 'Semua Kelas', 'class' => 'sesi-all'],
                                'sesi_1' => ['label' => 'Sesi 1: Kelas 1', 'class' => 'sesi-1'],
                                'sesi_2' => ['label' => 'Sesi 2: Kelas 2', 'class' => 'sesi-2'],
                                'sesi_3' => ['label' => 'Sesi 3: Kelas 3', 'class' => 'sesi-3'],
                                'sesi_4' => ['label' => 'Sesi 4: Kelas 4-6', 'class' => 'sesi-4'],
                            ];
                        @endphp
                        @foreach($groups as $g)
                            @if(isset($sesiLabels[$g]))
                                <span class="sesi-badge {{ $sesiLabels[$g]['class'] }}">
                                    {{ $sesiLabels[$g]['label'] }}
                                </span>
                            @endif
                        @endforeach
                    </div>

                    <!-- Jadwal Sesi -->
                    <div class="schedule-row">
                        <i class="far fa-calendar-alt"></i>
                        <span title="{{ $displaySchedule ?? 'Jadwal belum diatur' }}">{{ $displaySchedule ?? 'Jadwal belum diatur' }}</span>
                    </div>
                </div>

                <!-- Card Body (Pembina & Kuota) -->
                <div class="eskul-card-body">
                    <!-- Pembina Info -->
                    <div class="instructor-row">
                        <div class="instructor-avatar">
                            {{ strtoupper(substr($displayInstructor ?? '?', 0, 1)) }}
                        </div>
                        <div class="instructor-details">
                            <div class="instructor-label">Guru Pembina</div>
                            <div class="instructor-name" title="{{ $displayInstructor ?? 'Belum ditentukan' }}">
                                {{ $displayInstructor ?? 'Belum ditentukan' }}
                            </div>
                        </div>
                    </div>

                    <!-- Kuota & Status Aktif -->
                    @if(auth()->user()->role == 'admin')
                        <div class="settings-quick-bar">
                            <label class="toggle-active-label" title="Centang untuk menampilkan di formulir pilihan eskul">
                                <input type="checkbox" class="eskul-active-checkbox" data-id="{{ $eskul->id }}" {{ $eskul->is_active ? 'checked' : '' }}>
                                <span class="toggle-custom-box"><i class="fas fa-check"></i></span>
                                <span class="toggle-text">Aktif di Form</span>
                            </label>

                            <div class="quota-input-wrapper" title="Ubah kuota langsung (otomatis tersimpan)">
                                <span class="quota-label">Kuota:</span>
                                <input type="number" class="eskul-quota-input" data-id="{{ $eskul->id }}" value="{{ $quota }}" min="1">
                            </div>
                        </div>
                    @else
                        <div class="settings-read-only">
                            <span class="quota-readonly-text">Kuota: <strong>{{ $quota }} Siswa</strong></span>
                            @if($eskul->is_active)
                                <span class="status-active-pill"><i class="fas fa-check-circle"></i> Aktif di Form</span>
                            @else
                                <span class="status-inactive-pill"><i class="fas fa-times-circle"></i> Nonaktif</span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Card Actions Footer (Clean & Soft Action Row) -->
                <div class="eskul-card-footer">
                    <button type="button" onclick="openModal('view-{{ $eskul->id }}')" class="btn-card-action btn-action-detail" title="Lihat Daftar Anggota Siswa">
                        <i class="fas fa-eye"></i>
                        <span>Detail</span>
                    </button>

                    <a href="{{ route('eskuls.export', $eskul->id) }}" class="btn-card-action btn-action-excel" title="Download Rekap Anggota ke Excel">
                        <i class="fas fa-file-excel"></i>
                        <span>Excel</span>
                    </a>

                    @if(Auth::user()->role == 'admin')
                        <button type="button" onclick="openModal('edit-{{ $eskul->id }}')" class="btn-card-icon btn-action-edit" title="Edit Data Kegiatan">
                            <i class="fas fa-pen"></i>
                        </button>

                        <button type="button" 
                                data-url="{{ route('eskuls.destroy', $eskul->id) }}" 
                                data-name="{{ $eskul->name }}"
                                onclick="openDeleteModal(this.dataset.url, this.dataset.name)" 
                                class="btn-card-icon btn-action-delete" 
                                title="Hapus Kegiatan">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state-card">
                <div class="empty-icon-circle">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3>Belum Ada Ekstrakurikuler</h3>
                <p>Belum ada kegiatan ekstrakurikuler yang terdaftar untuk tahun ajaran aktif saat ini.</p>
                @if(Auth::user()->role == 'admin')
                    <button type="button" onclick="openModal('create-eskul-modal')" class="btn-solid btn-solid-blue" style="margin-top: 10px;">
                        <i class="fas fa-plus"></i> Tambah Sekarang
                    </button>
                @endif
            </div>
        @endforelse
    </div>
</div>

<!-- ========================================================================= -->
<!-- 6. MODAL DAFTAR SISWA & MODAL EDIT ESKUL                                  -->
<!-- ========================================================================= -->
@foreach($eskuls as $eskul)
    @php
        $currentHistory = $eskul->histories->first();
        $displayInstructor = $currentHistory ? $currentHistory->instructor_name : $eskul->instructor_name;
        $displaySchedule = $currentHistory ? $currentHistory->schedule : $eskul->schedule;
        $displayName = ($currentHistory && $currentHistory->alias_name) ? $currentHistory->alias_name : $eskul->name;
    @endphp

    <!-- Modal Lihat Siswa -->
    <div id="view-{{ $eskul->id }}" class="custom-modal-backdrop" style="display: none;">
        <div class="custom-modal-dialog">
            <div class="modal-dialog-header">
                <div class="modal-title-left">
                    <div class="modal-icon-header bg-blue-light text-blue">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="modal-title-text">{{ $displayName }}</h3>
                        <p class="modal-subtitle-text">Daftar anggota siswa terdaftar semester ini</p>
                    </div>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeModal('view-{{ $eskul->id }}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body-content">
                <div class="modal-info-summary">
                    <div>
                        <span class="info-label">Pembina</span>
                        <strong class="info-val">{{ $displayInstructor ?? 'Belum ditentukan' }}</strong>
                    </div>
                    <div>
                        <span class="info-label">Jadwal Sesi</span>
                        <strong class="info-val">{{ $displaySchedule ?? '-' }}</strong>
                    </div>
                </div>

                @if($eskul->students->isEmpty())
                    <div class="empty-members-box">
                        <i class="fas fa-user-clock"></i>
                        <span>Belum ada siswa yang mendaftar pada eskul ini.</span>
                    </div>
                @else
                    <div class="members-table-wrapper">
                        <table class="members-table">
                            <thead>
                                <tr>
                                    <th style="width: 12%; text-align: center;">No</th>
                                    <th>Nama Siswa</th>
                                    <th style="width: 25%; text-align: center;">Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($eskul->students as $idx => $student)
                                <tr>
                                    <td style="text-align: center; font-weight: 700; color: #64748b;">{{ $idx + 1 }}</td>
                                    <td style="font-weight: 700; color: #0f172a;">{{ $student->name }}</td>
                                    <td style="text-align: center;">
                                        <span class="class-pill">{{ $student->class }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="modal-dialog-footer">
                <a href="{{ route('eskuls.export', $eskul->id) }}" class="btn-solid btn-solid-green" style="font-size: 0.85rem;">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <button type="button" onclick="closeModal('view-{{ $eskul->id }}')" class="btn-modal-cancel">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @if(Auth::user()->role == 'admin')
    <!-- Modal Edit Eskul -->
    <div id="edit-{{ $eskul->id }}" class="custom-modal-backdrop" style="display: none;">
        <div class="custom-modal-dialog">
            <div class="modal-dialog-header">
                <div class="modal-title-left">
                    <div class="modal-icon-header bg-amber-light text-amber">
                        <i class="fas fa-pen"></i>
                    </div>
                    <div>
                        <h3 class="modal-title-text">Edit Ekstrakurikuler</h3>
                        <p class="modal-subtitle-text">Perbarui konfigurasi kegiatan {{ $eskul->name }}</p>
                    </div>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeModal('edit-{{ $eskul->id }}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('eskuls.update', $eskul->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body-content">
                    <div class="notice-info-box">
                        <i class="fas fa-info-circle"></i>
                        <span>Perubahan nama pembina & jadwal hanya berlaku untuk <strong>Semester Aktif</strong> tanpa merusak rekaman semester lampau.</span>
                    </div>

                    <div class="form-row-modern">
                        <label class="form-label-modern">Istilah Nama (Alias) - Semester Ini</label>
                        <input type="text" name="alias_name" class="form-control-modern" value="{{ $displayName }}" placeholder="Contoh: Karate Kelas Kecil">
                        <span class="field-hint">Nama spesifik yang akan tampil pada semester aktif ini saja.</span>
                    </div>

                    <div class="form-row-modern">
                        <label class="form-label-modern">Nama Eskul Utama (Sistem) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control-modern" value="{{ $eskul->name }}" required>
                        <span class="field-hint">Nama dasar kegiatan untuk database master.</span>
                    </div>

                    <div class="form-row-modern">
                        <label class="form-label-modern">Sesi Pendaftaran / Sasaran Kelas <span class="text-danger">*</span></label>
                        @php $currentGroups = $eskul->target_groups; @endphp
                        <div class="target-group-container">
                            <label class="target-group-option">
                                <input type="checkbox" name="target_group[]" value="all" {{ in_array('all', $currentGroups) ? 'checked' : '' }} onchange="handleAllCheckbox(this, 'edit-{{ $eskul->id }}')">
                                <span class="target-group-text font-bold">Semua Kelas (Umum)</span>
                            </label>
                            <div class="target-group-divider"></div>
                            <label class="target-group-option">
                                <input type="checkbox" name="target_group[]" value="sesi_1" {{ in_array('sesi_1', $currentGroups) ? 'checked' : '' }} class="sesi-cb-edit-{{ $eskul->id }}">
                                <span class="target-group-text">Sesi 1: Kelas 1</span>
                            </label>
                            <label class="target-group-option">
                                <input type="checkbox" name="target_group[]" value="sesi_2" {{ in_array('sesi_2', $currentGroups) ? 'checked' : '' }} class="sesi-cb-edit-{{ $eskul->id }}">
                                <span class="target-group-text">Sesi 2: Kelas 2</span>
                            </label>
                            <label class="target-group-option">
                                <input type="checkbox" name="target_group[]" value="sesi_3" {{ in_array('sesi_3', $currentGroups) ? 'checked' : '' }} class="sesi-cb-edit-{{ $eskul->id }}">
                                <span class="target-group-text">Sesi 3: Kelas 3</span>
                            </label>
                            <label class="target-group-option">
                                <input type="checkbox" name="target_group[]" value="sesi_4" {{ in_array('sesi_4', $currentGroups) ? 'checked' : '' }} class="sesi-cb-edit-{{ $eskul->id }}">
                                <span class="target-group-text">Sesi 4: Kelas Besar (Kelas 4-6)</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-grid-two">
                        <div class="form-row-modern">
                            <label class="form-label-modern">Nama Guru Pembina</label>
                            <input type="text" name="instructor_name" class="form-control-modern" value="{{ $displayInstructor }}">
                        </div>

                        <div class="form-row-modern">
                            <label class="form-label-modern">Jadwal Latihan</label>
                            <input type="text" name="schedule" class="form-control-modern" value="{{ $displaySchedule }}" placeholder="Contoh: Jum'at, 13:30 WIB">
                        </div>
                    </div>

                    <div class="form-grid-two">
                        <div class="form-row-modern">
                            <label class="form-label-modern">Kuota Maksimal Siswa <span class="text-danger">*</span></label>
                            <input type="number" name="quota" class="form-control-modern" value="{{ $eskul->quota }}" required min="1">
                        </div>

                        <div class="form-row-modern" style="display: flex; align-items: flex-end; padding-bottom: 6px;">
                            <label class="toggle-active-label">
                                <input type="checkbox" name="is_active" value="1" {{ $eskul->is_active ? 'checked' : '' }}>
                                <span class="toggle-custom-box"><i class="fas fa-check"></i></span>
                                <span class="toggle-text" style="font-weight: 700;">Tampilkan di Formulir</span>
                            </label>
                        </div>
                    </div>

                    <div class="lock-mode-box">
                        <label class="lock-mode-label">
                            <input type="checkbox" name="is_lockable" value="1" {{ $eskul->is_lockable ? 'checked' : '' }}>
                            <div>
                                <strong class="lock-mode-title"><i class="fas fa-lock"></i> Modus Penguncian (Wajib Lulus)</strong>
                                <span class="lock-mode-desc">Jika aktif, siswa di eskul ini <strong>TIDAK BISA PINDAH</strong> kecuali telah tuntas evaluasi nilai "A" (misal: Calistung).</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="modal-dialog-footer">
                    <button type="button" onclick="closeModal('edit-{{ $eskul->id }}')" class="btn-modal-cancel">
                        Batal
                    </button>
                    <button type="submit" class="btn-solid btn-solid-blue">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endforeach

@if(Auth::user()->role == 'admin')
<!-- ========================================================================= -->
<!-- 7. MODAL TAMBAH ESKUL BARU                                                -->
<!-- ========================================================================= -->
<div id="create-eskul-modal" class="custom-modal-backdrop" style="display: none;">
    <div class="custom-modal-dialog">
        <div class="modal-dialog-header">
            <div class="modal-title-left">
                <div class="modal-icon-header bg-blue-light text-blue">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h3 class="modal-title-text">Tambah Ekstrakurikuler Baru</h3>
                    <p class="modal-subtitle-text">Tambahkan kegiatan eskul baru ke tahun ajaran aktif</p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('create-eskul-modal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('eskuls.store') }}" method="POST">
            @csrf
            <div class="modal-body-content">
                <div class="form-row-modern">
                    <label class="form-label-modern">Nama Eskul Utama <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control-modern" placeholder="Contoh: Karate A" required>
                </div>

                <div class="form-row-modern">
                    <label class="form-label-modern">Istilah Nama (Alias) - Opsional</label>
                    <input type="text" name="alias_name" class="form-control-modern" placeholder="Contoh: Karate Kelas Kecil">
                    <span class="field-hint">Nama alternatif yang akan tampil di pilihan siswa semester ini.</span>
                </div>

                <div class="form-row-modern">
                    <label class="form-label-modern">Sesi Pendaftaran / Sasaran Kelas <span class="text-danger">*</span></label>
                    <div class="target-group-container">
                        <label class="target-group-option">
                            <input type="checkbox" name="target_group[]" value="all" checked onchange="handleAllCheckbox(this, 'create')">
                            <span class="target-group-text font-bold">Semua Kelas (Umum)</span>
                        </label>
                        <div class="target-group-divider"></div>
                        <label class="target-group-option">
                            <input type="checkbox" name="target_group[]" value="sesi_1" class="sesi-cb-create">
                            <span class="target-group-text">Sesi 1: Kelas 1</span>
                        </label>
                        <label class="target-group-option">
                            <input type="checkbox" name="target_group[]" value="sesi_2" class="sesi-cb-create">
                            <span class="target-group-text">Sesi 2: Kelas 2</span>
                        </label>
                        <label class="target-group-option">
                            <input type="checkbox" name="target_group[]" value="sesi_3" class="sesi-cb-create">
                            <span class="target-group-text">Sesi 3: Kelas 3</span>
                        </label>
                        <label class="target-group-option">
                            <input type="checkbox" name="target_group[]" value="sesi_4" class="sesi-cb-create">
                            <span class="target-group-text">Sesi 4: Kelas Besar (Kelas 4-6)</span>
                        </label>
                    </div>
                </div>

                <div class="form-grid-two">
                    <div class="form-row-modern">
                        <label class="form-label-modern">Nama Guru Pembina</label>
                        <input type="text" name="instructor_name" class="form-control-modern" placeholder="Nama Guru Pembina">
                    </div>

                    <div class="form-row-modern">
                        <label class="form-label-modern">Jadwal Latihan</label>
                        <input type="text" name="schedule" class="form-control-modern" placeholder="Contoh: Jum'at, 13:30 WIB">
                    </div>
                </div>

                <div class="form-grid-two">
                    <div class="form-row-modern">
                        <label class="form-label-modern">Kuota Siswa <span class="text-danger">*</span></label>
                        <input type="number" name="quota" class="form-control-modern" value="25" required min="1">
                    </div>

                    <div class="form-row-modern" style="display: flex; align-items: flex-end; padding-bottom: 6px;">
                        <label class="toggle-active-label">
                            <input type="checkbox" name="is_active" value="1" checked>
                            <span class="toggle-custom-box"><i class="fas fa-check"></i></span>
                            <span class="toggle-text" style="font-weight: 700;">Aktif di Formulir</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="modal-dialog-footer">
                <button type="button" onclick="closeModal('create-eskul-modal')" class="btn-modal-cancel">
                    Batal
                </button>
                <button type="submit" class="btn-solid btn-solid-blue">
                    <i class="fas fa-plus-circle"></i> Tambah Eskul
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 8. MODAL ATUR JADWAL SERENTAK                                             -->
<!-- ========================================================================= -->
<div id="update-schedule-modal" class="custom-modal-backdrop" style="display: none;">
    <div class="custom-modal-dialog" style="max-width: 480px;">
        <div class="modal-dialog-header">
            <div class="modal-title-left">
                <div class="modal-icon-header bg-amber-light text-amber">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h3 class="modal-title-text">Atur Jadwal Serentak</h3>
                    <p class="modal-subtitle-text">Terapkan jadwal yang sama ke seluruh kegiatan eskul aktif</p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('update-schedule-modal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('eskuls.bulk-update-schedule') }}" method="POST">
            @csrf
            <div class="modal-body-content">
                <div class="notice-warning-box">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><strong>Perhatian:</strong> Jadwal ini HANYA akan diterapkan pada eskul yang memiliki siswa aktif di tahun ajaran ini.</span>
                </div>

                <div class="form-row-modern">
                    <label class="form-label-modern">Hari & Waktu Pelaksanaan <span class="text-danger">*</span></label>
                    <input type="text" name="schedule" class="form-control-modern" placeholder="Contoh: Jum'at, Pukul 13.30 - 15.00 WIB" required>
                </div>
            </div>

            <div class="modal-dialog-footer">
                <button type="button" onclick="closeModal('update-schedule-modal')" class="btn-modal-cancel">
                    Batal
                </button>
                <button type="submit" class="btn-solid btn-solid-amber">
                    <i class="fas fa-check"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 9. MODAL HAPUS ESKUL                                                      -->
<!-- ========================================================================= -->
<div id="delete-modal" class="custom-modal-backdrop" style="display: none;">
    <div class="custom-modal-dialog" style="max-width: 420px; text-align: center;">
        <div class="modal-body-content" style="padding: 30px 24px 20px;">
            <div class="delete-icon-circle">
                <i class="fas fa-trash-alt"></i>
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 8px;">Hapus Ekstrakurikuler?</h3>
            <p style="color: #64748b; font-size: 0.92rem; line-height: 1.5; margin: 0 0 24px;">
                Anda yakin ingin menghapus <strong id="delete-name" style="color: #0f172a;"></strong>?<br>
                Seluruh data pendaftaran dan rekam nilai siswa pada kegiatan ini akan ikut terhapus.
            </p>
            
            <form id="delete-form" action="" method="POST">
                @csrf
                @method('DELETE')
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button type="button" onclick="closeModal('delete-modal')" class="btn-modal-cancel" style="flex: 1;">
                        Batal
                    </button>
                    <button type="submit" class="btn-solid btn-solid-danger" style="flex: 1;">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- ========================================================================= -->
<!-- CSS STYLES: SIMPEL, ELEGAN, SOFT & RAMAH LAPTOP/HP                        -->
<!-- ========================================================================= -->


<!-- ========================================================================= -->
<!-- JAVASCRIPT: MODAL, FILTER CEPAT & QUICK UPDATE AJAX                       -->
<!-- ========================================================================= -->
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.display = 'flex';
            document.body.classList.add('modal-open');
            const mainContent = document.querySelector('.main-content');
            if (mainContent) mainContent.classList.add('modal-open');
            modal.scrollTop = 0;
            const dialog = modal.querySelector('.custom-modal-dialog');
            if (dialog) dialog.scrollTop = 0;
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.display = 'none';
            document.body.classList.remove('modal-open');
            const mainContent = document.querySelector('.main-content');
            if (mainContent) mainContent.classList.remove('modal-open');
        }
    }

    function openDeleteModal(url, name) {
        document.getElementById('delete-form').action = url;
        document.getElementById('delete-name').innerText = name;
        openModal('delete-modal');
    }

    // Close on click outside modal content
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('custom-modal-backdrop')) {
            e.target.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // Close on Escape key
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.custom-modal-backdrop').forEach(modal => {
                modal.style.display = 'none';
            });
            document.body.style.overflow = 'auto';
        }
    });

    // Live search filter across cards
    function filterEskuls() {
        const input = document.getElementById('eskulSearchInput');
        const filter = input.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.eskul-card');
        const clearBtn = document.getElementById('clearSearchBtn');
        let visible = 0;

        clearBtn.style.display = filter.length > 0 ? 'block' : 'none';

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const instructor = card.getAttribute('data-instructor') || '';
            const schedule = card.getAttribute('data-schedule') || '';

            if (name.includes(filter) || instructor.includes(filter) || schedule.includes(filter)) {
                card.style.display = 'flex';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        const countSpan = document.getElementById('visibleCount');
        if (countSpan) {
            countSpan.innerText = visible;
        }
    }

    function clearSearch() {
        const input = document.getElementById('eskulSearchInput');
        input.value = '';
        filterEskuls();
        input.focus();
    }

    /**
     * Uncheck target groups appropriately
     */
    function handleAllCheckbox(checkbox, formKey) {
        if (checkbox.value === 'all' && checkbox.checked) {
            document.querySelectorAll('.sesi-cb-' + formKey).forEach(cb => cb.checked = false);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input[type="checkbox"][name="target_group[]"]').forEach(function(cb) {
            if (cb.value !== 'all') {
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        const container = this.closest('.target-group-container');
                        if (container) {
                            const allCb = container.querySelector('input[value="all"]');
                            if (allCb) allCb.checked = false;
                        }
                    }
                });
            }
        });

        // AJAX Quick Update Active Checkbox
        document.querySelectorAll('.eskul-active-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                const isActive = this.checked ? 1 : 0;
                
                fetch(`/eskuls/${id}/quick-update`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ is_active: isActive })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal memperbarui status aktif eskul.');
                });
            });
        });

        // AJAX Quick Update Quota
        document.querySelectorAll('.eskul-quota-input').forEach(input => {
            input.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                const quota = this.value;
                
                if (quota < 1) {
                    alert('Kuota minimal 1!');
                    return;
                }

                fetch(`/eskuls/${id}/quick-update`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ quota: quota })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal memperbarui kuota eskul.');
                });
            });
        });
    });
</script>
@endsection
