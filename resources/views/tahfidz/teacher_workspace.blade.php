@extends('layouts.app')

@section('title', 'Mentoring Tahfidz – ' . $activeGroup->name)
@section('page-title', 'Mentoring Tahfidz')

@push('styles')
<style>
    /* Styling Ruang Halaqah Sederhana & Ramah Guru */
    .halaqah-container {
        max-width: 1200px;
        margin: 0 auto 75px auto;
    }

    /* Pilihan Kelompok (jika punya > 1) */
    .group-nav-tabs {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 6px;
        margin-bottom: 14px;
        scrollbar-width: none;
    }
    .group-nav-tabs::-webkit-scrollbar { display: none; }
    .group-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 10px;
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 0.84rem;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .group-tab-btn:hover { background: #e2e8f0; color: #0f172a; }
    .group-tab-btn.active {
        background: #047857;
        color: #ffffff;
        border-color: #047857;
        box-shadow: 0 3px 10px rgba(4, 120, 87, 0.2);
    }

    /* Banner Utama Halaqah */
    .halaqah-card-header {
        background: linear-gradient(135deg, #065f46 0%, #047857 55%, #059669 100%);
        border-radius: 16px;
        padding: 18px 22px;
        color: white;
        margin-bottom: 14px;
        box-shadow: 0 6px 20px -4px rgba(5, 150, 105, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .halaqah-title {
        margin: 0 0 3px 0;
        font-size: 1.45rem;
        font-weight: 800;
        text-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    .header-meta-info {
        font-size: 0.84rem;
        opacity: 0.95;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .session-date-picker-wrap {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        padding: 6px 12px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .session-date-picker-wrap input[type="date"] {
        background: white;
        border: none;
        border-radius: 6px;
        padding: 5px 8px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #0f172a;
        cursor: pointer;
        outline: none;
    }

    /* 4 Kotak Hijau Statistik Informatif & Simpel */
    .kpi-green-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 16px;
    }
    .kpi-green-card {
        background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
        border: 1.5px solid #a7f3d0;
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 2px 6px rgba(5, 150, 105, 0.04);
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .kpi-green-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.1);
        border-color: #6ee7b7;
    }
    .kpi-green-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #047857;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    .kpi-green-label {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #047857;
        letter-spacing: 0.3px;
        margin-bottom: 1px;
    }
    .kpi-green-value {
        font-size: 1.25rem;
        font-weight: 900;
        color: #064e3b;
        line-height: 1.1;
    }
    .kpi-green-unit {
        font-size: 0.74rem;
        font-weight: 600;
        color: #059669;
    }

    /* Success / WA Banner */
    .wa-success-banner {
        background: #ecfdf5;
        border: 1.5px solid #86efac;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        box-shadow: 0 3px 10px rgba(16, 185, 129, 0.1);
    }

    /* Student Item Container */
    .student-entry-card {
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .student-entry-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }
    .student-entry-card.is-absent {
        background: #f8fafc;
        border-color: #e2e8f0;
        opacity: 0.92;
    }

    /* Student Header Row */
    .student-card-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .student-info-flex {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 200px;
        flex: 1;
    }
    .student-avatar-num {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #ecfdf5;
        color: #047857;
        font-weight: 800;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #a7f3d0;
        flex-shrink: 0;
    }
    .student-fullname {
        font-weight: 800;
        font-size: 0.95rem;
        color: #0f172a;
        line-height: 1.25;
    }
    .student-meta-row {
        font-size: 0.76rem;
        color: #64748b;
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
    }

    /* Segmented Control Kehadiran */
    .att-segmented-container {
        display: inline-flex;
        background: #f1f5f9;
        border-radius: 10px;
        padding: 3px;
        gap: 3px;
    }
    .att-segmented-item {
        margin: 0;
        padding: 0;
        cursor: pointer;
    }
    .att-pill-radio { display: none; }
    .att-segmented-btn {
        padding: 6px 12px;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        color: #64748b;
        user-select: none;
        transition: all 0.15s ease;
        white-space: nowrap;
    }
    .att-pill-radio:checked + .att-pill-hadir {
        background: #16a34a;
        color: white;
        box-shadow: 0 2px 6px rgba(22, 163, 74, 0.3);
    }
    .att-pill-radio:checked + .att-pill-sakit {
        background: #d97706;
        color: white;
        box-shadow: 0 2px 6px rgba(217, 119, 6, 0.3);
    }
    .att-pill-radio:checked + .att-pill-izin {
        background: #0284c7;
        color: white;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.3);
    }
    .att-pill-radio:checked + .att-pill-alpha {
        background: #dc2626;
        color: white;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
    }

    /* Completion Recap Section */
    .completion-card {
        background: white;
        border: 1.5px solid #a7f3d0;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 4px 16px rgba(5, 150, 105, 0.08);
    }
    .completion-hero {
        background: linear-gradient(135deg, #065f46 0%, #047857 60%, #059669 100%);
        color: white;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .completion-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.2);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .completion-title {
        margin: 0 0 3px 0;
        font-size: 1.3rem;
        font-weight: 800;
    }
    .completion-subtitle {
        margin: 0;
        font-size: 0.84rem;
        opacity: 0.95;
    }
    .completion-actions {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }
    .btn-recap-wa, .btn-recap-copy, .btn-recap-edit {
        height: 38px;
        min-height: 38px;
        min-width: 116px;
        padding: 0 16px;
        border-radius: 9px;
        font-weight: 700;
        font-size: 0.82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        white-space: nowrap;
        text-decoration: none;
        box-sizing: border-box;
        line-height: 1;
        user-select: none;
        border: none;
        outline: none;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-recap-wa {
        background: #16a34a;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
    }
    .btn-recap-wa:hover {
        background: #15803d;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(22, 163, 74, 0.35);
    }
    .btn-recap-wa:active {
        transform: translateY(0);
    }
    .btn-recap-copy {
        background: #0284c7;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
    }
    .btn-recap-copy:hover {
        background: #0369a1;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(2, 132, 199, 0.35);
    }
    .btn-recap-copy:active {
        transform: translateY(0);
    }
    .btn-recap-copy.copied {
        background: #059669 !important;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.35) !important;
    }
    .btn-recap-edit {
        background: #475569;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(71, 85, 105, 0.25);
    }
    .btn-recap-edit:hover {
        background: #334155;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(71, 85, 105, 0.35);
    }
    .btn-recap-edit:active {
        transform: translateY(0);
    }

    /* Recap Table */
    .recap-table-wrap {
        overflow-x: auto;
    }
    .recap-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .recap-table th {
        background: #f8fafc;
        padding: 10px 14px;
        font-size: 0.74rem;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border-bottom: 1px solid #e2e8f0;
    }
    .recap-table td {
        padding: 11px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.84rem;
        vertical-align: middle;
    }
    .recap-table tr:last-child td {
        border-bottom: none;
    }
    .recap-table tr:hover td {
        background: #f8fafc;
    }

    /* Badges */
    .badge-hadir { background: #dcfce7; color: #15803d; font-weight: 700; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; }
    .badge-sakit { background: #fef3c7; color: #b45309; font-weight: 700; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; }
    .badge-izin  { background: #e0f2fe; color: #0369a1; font-weight: 700; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; }
    .badge-alpha { background: #fee2e2; color: #b91c1c; font-weight: 700; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; }
    
    .badge-lulus { background: #ecfdf5; color: #047857; font-weight: 800; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; border: 1px solid #a7f3d0; }
    .badge-ulang { background: #fef2f2; color: #dc2626; font-weight: 800; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; border: 1px solid #fecaca; }
    .badge-nilai { background: #fef9c3; color: #854d0e; font-weight: 800; padding: 3px 8px; border-radius: 6px; font-size: 0.78rem; border: 1px solid #fde047; display: inline-flex; align-items: center; gap: 3px; }

    .recap-mobile-list {
        display: none;
        padding: 12px;
    }

    /* Input Hafalan Section Desktop */
    .hafalan-box-wrap {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px dashed #e2e8f0;
        display: grid;
        grid-template-columns: 2fr 1.1fr 0.9fr 0.8fr 1.3fr 2fr;
        gap: 10px;
        align-items: center;
    }
    .hafalan-field-label {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 3px;
        letter-spacing: 0.3px;
        display: block;
    }
    .input-clean {
        width: 100%;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 7px 11px;
        font-size: 0.85rem;
        color: #0f172a;
        font-weight: 600;
        outline: none;
        transition: border 0.15s, box-shadow 0.15s;
        box-sizing: border-box;
        height: 38px;
        background: white;
    }
    .input-clean:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }

    /* ================================================================ */
    /* MODERN SURAH PICKER (MOBILE-FIRST, ZERO FULLSCREEN TAKEOVER)     */
    /* ================================================================ */
    .hafalan-col-surah {
        position: relative;
    }
    .student-entry-card.has-open-picker {
        z-index: 100;
        position: relative;
    }
    .surah-picker-wrap {
        position: relative;
        width: 100%;
    }
    .surah-native-hidden {
        position: absolute !important;
        opacity: 0 !important;
        pointer-events: none !important;
        width: 1px !important;
        height: 1px !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        z-index: -1 !important;
    }
    .surah-picker-trigger {
        width: 100%;
        height: 38px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 10px;
        cursor: pointer;
        user-select: none;
        box-sizing: border-box;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
        gap: 6px;
    }
    .surah-picker-trigger:hover {
        border-color: #059669;
        background: #f8fafc;
    }
    .surah-picker-wrap.is-open .surah-picker-trigger {
        border-color: #059669;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }
    .surah-trigger-left {
        display: flex;
        align-items: center;
        gap: 7px;
        overflow: hidden;
        min-width: 0;
        flex: 1;
    }
    .surah-trigger-icon {
        font-size: 0.95rem;
        flex-shrink: 0;
        line-height: 1;
    }
    .surah-trigger-text {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .surah-trigger-text.is-placeholder {
        color: #94a3b8;
        font-weight: 500;
    }
    .surah-trigger-arrow {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        flex-shrink: 0;
        transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), color 0.2s ease;
    }
    .surah-picker-wrap.is-open .surah-trigger-arrow {
        transform: rotate(180deg);
        color: #059669;
    }
    .surah-chevron {
        font-size: 0.72rem;
    }

    /* Popover Menu: Menempel rapi tepat di bawah trigger, tidak menutupi layar HP */
    .surah-picker-popover {
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 14px 32px -4px rgba(15, 23, 42, 0.2), 0 4px 12px rgba(0, 0, 0, 0.06);
        z-index: 1050;
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: surahPopoverIn 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        min-width: 250px;
    }
    @keyframes surahPopoverIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .surah-picker-wrap.is-open .surah-picker-popover {
        display: flex;
    }

    /* Kotak Pencarian Surat Cepat */
    .surah-search-box {
        padding: 8px 10px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .surah-search-icon {
        color: #94a3b8;
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    .surah-search-input {
        width: 100%;
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.84rem;
        font-weight: 600;
        color: #0f172a;
        padding: 2px 0;
    }
    .surah-search-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    /* List Opsi Surat */
    .surah-options-list {
        max-height: 220px;
        overflow-y: auto;
        padding: 4px;
        -webkit-overflow-scrolling: touch;
    }
    .surah-options-list::-webkit-scrollbar {
        width: 5px;
    }
    .surah-options-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 6px;
    }

    .surah-optgroup-title {
        font-size: 0.72rem;
        font-weight: 800;
        color: #047857;
        background: #ecfdf5;
        padding: 5px 8px;
        border-radius: 6px;
        margin: 4px 2px 2px 2px;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .surah-option-item {
        padding: 8px 10px;
        border-radius: 7px;
        font-size: 0.84rem;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        cursor: pointer;
        transition: background 0.12s ease;
        margin-bottom: 2px;
        user-select: none;
    }
    .surah-option-item:hover {
        background: #f1f5f9;
    }
    .surah-option-item.is-selected {
        background: #ecfdf5;
        color: #047857;
        font-weight: 700;
    }
    .surah-item-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .surah-item-badge {
        font-size: 0.7rem;
        font-weight: 600;
        color: #059669;
        background: #d1fae5;
        padding: 1px 6px;
        border-radius: 4px;
        flex-shrink: 0;
    }
    .surah-item-check {
        color: #059669;
        font-size: 0.78rem;
        opacity: 0;
        flex-shrink: 0;
    }
    .surah-option-item.is-selected .surah-item-check {
        opacity: 1;
    }
    .surah-empty-state {
        padding: 14px 10px;
        text-align: center;
        font-size: 0.8rem;
        color: #94a3b8;
        font-weight: 500;
    }
    @media (max-width: 640px) {
        .surah-picker-popover {
            max-height: 250px;
        }
        .surah-options-list {
            max-height: 195px;
        }
        .surah-search-input {
            font-size: 16px !important;
        }
    }

    /* Status Kelulusan Pill */
    .lulus-pills {
        display: flex;
        background: #f1f5f9;
        border-radius: 8px;
        padding: 3px;
        gap: 2px;
        height: 38px;
        box-sizing: border-box;
        align-items: center;
    }
    .lulus-pill-btn {
        flex: 1;
        text-align: center;
        padding: 5px 6px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 800;
        cursor: pointer;
        color: #64748b;
        user-select: none;
        transition: all 0.15s;
        display: block;
        line-height: 1.2;
    }
    .lulus-radio:checked + .btn-lulus {
        background: #16a34a;
        color: white;
    }
    .lulus-radio:checked + .btn-ulang {
        background: #dc2626;
        color: white;
    }

    /* Sticky Bottom Action Bar - Compact & Clean */
    .sticky-bottom-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(10px);
        border-top: 1px solid #e2e8f0;
        padding: 8px 18px;
        z-index: 1000;
        box-shadow: 0 -3px 14px rgba(0,0,0,0.06);
    }
    .sticky-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }
    .sticky-info-text {
        font-size: 0.82rem;
        color: #475569;
        font-weight: 600;
    }
    .sticky-actions-wrap {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .btn-sticky-wa {
        background: #25D366;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: transform 0.15s;
    }
    .btn-sticky-save {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 0.88rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 10px rgba(4, 120, 87, 0.3);
        transition: transform 0.15s;
    }
    .btn-sticky-save:hover, .btn-sticky-wa:hover {
        transform: translateY(-1px);
    }

    /* Modal WhatsApp Preview */
    .modal-wa {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .modal-wa.open { display: flex; }
    .modal-wa-box {
        background: white;
        border-radius: 18px;
        width: 100%;
        max-width: 560px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        overflow: hidden;
        animation: scaleUp 0.2s ease;
    }
    @keyframes scaleUp {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    /* ============================================================== */
    /* RESPONSIVE KHUSUS SMARTPHONE / HANDPHONE GURU (RAPI & RINGKAS) */
    /* ============================================================== */
    @media (max-width: 820px) {
        .halaqah-container {
            margin-bottom: 65px;
        }
        .halaqah-card-header {
            padding: 12px 14px;
            border-radius: 14px;
            margin-bottom: 12px;
            gap: 10px;
        }
        .halaqah-title {
            font-size: 1.15rem !important;
        }
        .header-meta-info {
            font-size: 0.76rem !important;
            gap: 8px !important;
        }
        .session-date-picker-wrap {
            padding: 4px 8px;
            gap: 6px;
            border-radius: 8px;
        }
        .session-date-picker-wrap input[type="date"] {
            padding: 4px 6px;
            font-size: 0.76rem;
        }
        .btn-header-wa {
            padding: 6px 12px !important;
            font-size: 0.78rem !important;
            border-radius: 8px !important;
        }

        /* 4 Kotak Hijau di HP: 2 Kolom Ringkas & Ramping */
        .kpi-green-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }
        .kpi-green-card {
            padding: 9px 11px;
            gap: 8px;
            border-radius: 10px;
        }
        .kpi-green-icon {
            width: 32px;
            height: 32px;
            font-size: 0.95rem;
            border-radius: 8px;
        }
        .kpi-green-label {
            font-size: 0.64rem;
        }
        .kpi-green-value {
            font-size: 1.12rem;
        }
        .kpi-green-unit {
            font-size: 0.7rem;
        }

        /* Kartu Siswa di HP: Rapi, Padat & Tidak Memakan Tempat */
        .student-entry-card {
            padding: 11px 12px;
            margin-bottom: 10px;
            border-radius: 12px;
        }
        .student-card-header-row {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
        }
        .student-info-flex {
            justify-content: space-between;
        }
        .student-avatar-num {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }
        .student-fullname {
            font-size: 0.9rem;
        }
        .student-meta-row {
            font-size: 0.72rem;
        }

        /* Segmented Control Kehadiran di HP: Membentang Rapi 100% Lebar (4 Tab Sama Rata) */
        .att-segmented-container {
            width: 100%;
            display: flex;
            padding: 2px;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .att-segmented-item {
            flex: 1;
            display: flex;
        }
        .att-segmented-btn {
            width: 100%;
            padding: 6px 4px;
            font-size: 0.74rem;
            border-radius: 6px;
        }

        /* Input Hafalan di HP: Grid 2 Kolom Simetris (Tidak Menumpuk Raksasa) */
        .hafalan-box-wrap {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 10px;
            padding-top: 10px;
        }
        .hafalan-col-surah {
            grid-column: span 2;
        }
        .hafalan-col-ayat {
            grid-column: span 1;
        }
        .hafalan-col-nilai {
            grid-column: span 1;
        }
        .hafalan-col-baris {
            grid-column: span 1;
        }
        .hafalan-col-status {
            grid-column: span 1;
        }
        .hafalan-col-notes {
            grid-column: span 2;
        }
        .recap-table-wrap { display: none; }
        .recap-mobile-list { display: block; }
        .completion-hero {
            padding: 16px;
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }
        .completion-title {
            font-size: 1.15rem;
        }
        .completion-actions {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 6px;
            width: 100%;
        }
        .btn-recap-wa, .btn-recap-copy, .btn-recap-edit {
            width: 100%;
            min-width: 0;
            height: 38px;
            min-height: 38px;
            padding: 0 4px;
            font-size: 0.76rem;
            border-radius: 8px;
            gap: 5px;
            white-space: nowrap;
        }
        .btn-recap-wa i, .btn-recap-copy i, .btn-recap-edit i {
            font-size: 0.82rem;
            flex-shrink: 0;
        }
        .input-clean {
            height: 35px;
            padding: 6px 9px;
            font-size: 0.82rem;
            border-radius: 7px;
        }
        .lulus-pills {
            height: 35px;
        }
        .lulus-pill-btn {
            font-size: 0.72rem;
            padding: 4px;
        }
        .hafalan-field-label {
            font-size: 0.67rem;
            margin-bottom: 2px;
        }

        /* Sticky Bottom Bar di HP: Sleek Single Row (Tidak Menutupi Layar) */
        .sticky-bottom-bar {
            padding: 8px 12px;
            padding-bottom: max(8px, env(safe-area-inset-bottom));
        }
        .sticky-content {
            flex-direction: row !important;
            justify-content: space-between !important;
            gap: 8px !important;
        }
        .sticky-info-text {
            display: none !important;
        }
        .sticky-actions-wrap {
            width: 100%;
            display: flex;
            gap: 8px;
        }
        .btn-sticky-wa {
            flex: 1;
            justify-content: center;
            padding: 9px 10px;
            font-size: 0.8rem;
            border-radius: 8px;
        }
        .btn-sticky-save {
            flex: 2;
            justify-content: center;
            padding: 9px 14px;
            font-size: 0.84rem;
            border-radius: 8px;
        }
    }

    /* ========================================================= */
    /* PENCARIAN & DROPDOWN PEMILIH SANTRI SETORAN (INPUT SATU PER SATU) */
    /* ========================================================= */
    .student-search-selection-card {
        background: #ffffff;
        border: 1.5px solid #a7f3d0;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 16px;
        box-shadow: 0 4px 16px rgba(5, 150, 105, 0.06);
    }
    .search-selection-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 14px;
    }
    .search-selection-title-box {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .search-selection-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #ecfdf5;
        border: 1.5px solid #a7f3d0;
        color: #047857;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .search-selection-title {
        margin: 0 0 2px 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #064e3b;
    }
    .search-selection-subtitle {
        margin: 0;
        font-size: 0.8rem;
        color: #64748b;
    }

    /* Filter Pills */
    .student-filter-pills {
        display: inline-flex;
        background: #f1f5f9;
        border-radius: 10px;
        padding: 3px;
        gap: 3px;
    }
    .filter-pill {
        border: none;
        background: transparent;
        color: #64748b;
        font-size: 0.76rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 7px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .filter-pill:hover {
        color: #0f172a;
    }
    .filter-pill.active {
        background: #047857;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(4, 120, 87, 0.25);
    }

    /* Custom Searchable Dropdown */
    .custom-student-dropdown {
        position: relative;
        width: 100%;
    }
    .student-dropdown-trigger {
        width: 100%;
        min-height: 48px;
        background: #ffffff;
        border: 2px solid #10b981;
        border-radius: 12px;
        padding: 8px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        gap: 10px;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .student-dropdown-trigger:hover {
        border-color: #059669;
        box-shadow: 0 3px 12px rgba(16, 185, 129, 0.15);
    }
    .custom-student-dropdown.is-open .student-dropdown-trigger {
        border-color: #047857;
        box-shadow: 0 0 0 3.5px rgba(5, 150, 105, 0.18);
    }
    .trigger-left {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        flex: 1;
    }
    .trigger-placeholder {
        font-size: 0.88rem;
        font-weight: 700;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .trigger-selected-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
    }
    .trigger-selected-name {
        font-size: 0.95rem;
        font-weight: 800;
        color: #064e3b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .trigger-selected-badge {
        font-size: 0.72rem;
        font-weight: 700;
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        padding: 1px 7px;
        border-radius: 6px;
        white-space: nowrap;
    }
    .trigger-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .badge-active-group {
        background: #f0fdf4;
        color: #047857;
        font-size: 0.74rem;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        border: 1px solid #a7f3d0;
    }
    #triggerChevron {
        font-size: 0.85rem;
        color: #64748b;
        transition: transform 0.2s ease;
    }
    .custom-student-dropdown.is-open #triggerChevron {
        transform: rotate(180deg);
        color: #047857;
    }

    /* Dropdown Menu */
    .student-dropdown-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1.5px solid #a7f3d0;
        border-radius: 14px;
        box-shadow: 0 16px 36px -4px rgba(6, 78, 59, 0.18), 0 4px 12px rgba(0,0,0,0.06);
        z-index: 1080;
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: dropDownIn 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes dropDownIn {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .custom-student-dropdown.is-open .student-dropdown-menu {
        display: flex;
    }
    .dropdown-search-box {
        padding: 10px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dropdown-search-icon {
        color: #047857;
        font-size: 0.9rem;
    }
    .dropdown-search-input {
        width: 100%;
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.88rem;
        font-weight: 600;
        color: #0f172a;
    }
    .dropdown-search-input::placeholder {
        color: #94a3b8;
    }
    .clear-search-btn {
        background: #e2e8f0;
        border: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        line-height: 1;
        cursor: pointer;
        color: #475569;
    }
    .dropdown-options-list {
        max-height: 310px;
        overflow-y: auto;
        padding: 6px;
        -webkit-overflow-scrolling: touch;
    }
    .dropdown-student-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 9px 12px;
        border-radius: 9px;
        cursor: pointer;
        transition: all 0.12s ease;
        margin-bottom: 2px;
        border: 1px solid transparent;
    }
    .dropdown-student-item:hover {
        background: #f0fdf4;
        border-color: #a7f3d0;
    }
    .dropdown-student-item.is-active {
        background: #ecfdf5;
        border-color: #6ee7b7;
    }
    .item-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #334155;
        font-size: 0.82rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .dropdown-student-item.is-active .item-avatar {
        background: #047857;
        color: white;
    }
    .item-info {
        flex: 1;
        min-width: 0;
    }
    .item-name {
        font-size: 0.88rem;
        font-weight: 800;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .item-meta {
        font-size: 0.74rem;
        color: #64748b;
        margin-top: 1px;
    }
    .badge-status-sudah {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-status-belum {
        background: #f1f5f9;
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-status-absen {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        white-space: nowrap;
    }
    .dropdown-empty-state {
        padding: 24px 12px;
        text-align: center;
        color: #94a3b8;
        font-size: 0.84rem;
        font-weight: 600;
    }

    /* Empty Placeholder Card (When No Student Selected) */
    .empty-selection-placeholder {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 38px 20px;
        text-align: center;
        margin-bottom: 20px;
        transition: all 0.2s ease;
    }
    .empty-placeholder-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #ecfdf5;
        border: 2px solid #a7f3d0;
        color: #047857;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px auto;
    }
    .empty-placeholder-title {
        margin: 0 0 6px 0;
        font-size: 1.15rem;
        font-weight: 800;
        color: #1e293b;
    }
    .empty-placeholder-desc {
        margin: 0 auto;
        max-width: 540px;
        font-size: 0.84rem;
        color: #64748b;
        line-height: 1.5;
    }

    /* Active Student Form Card (Kotak Form) */
    .active-student-form-card {
        background: #ffffff;
        border: 2px solid #059669;
        border-radius: 16px;
        padding: 18px 20px;
        margin-bottom: 20px;
        box-shadow: 0 8px 24px -4px rgba(5, 150, 105, 0.16);
        animation: formCardSlideIn 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes formCardSlideIn {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .active-card-top-indicator {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #065f46 0%, #047857 100%);
        color: white;
        padding: 8px 14px;
        border-radius: 10px;
        margin-bottom: 14px;
        font-size: 0.82rem;
        font-weight: 800;
    }
    .btn-close-form {
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.74rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: background 0.15s ease;
    }
    .btn-close-form:hover {
        background: rgba(255,255,255,0.35);
    }

    /* Tombol Simpan Form Siswa */
    .single-save-row {
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px dashed #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .btn-save-single {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff !important;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.92rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(4, 120, 87, 0.3);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-save-single:hover {
        background: linear-gradient(135deg, #047857 0%, #065f46 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(4, 120, 87, 0.4);
    }
    .btn-save-single:active {
        transform: translateY(0);
    }
    .save-feedback-badge {
        font-size: 0.82rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
    }
    .save-feedback-badge.success {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    /* Daftar Seluruh Santri di Kelompok */
    .group-students-roster-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .roster-header {
        background: #f8fafc;
        border-bottom: 1.5px solid #e2e8f0;
        padding: 14px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .roster-title {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .roster-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .roster-table th {
        background: #ffffff;
        padding: 10px 14px;
        font-size: 0.72rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border-bottom: 1px solid #e2e8f0;
    }
    .roster-table td {
        padding: 11px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.84rem;
        vertical-align: middle;
    }
    .roster-table tr:hover td {
        background: #f0fdf4;
    }
    .roster-table tr.is-selected-row td {
        background: #ecfdf5 !important;
        border-color: #a7f3d0;
    }
    .btn-action-select {
        background: #ecfdf5;
        color: #047857;
        border: 1.5px solid #a7f3d0;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 0.78rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s ease;
    }
    .btn-action-select:hover {
        background: #047857;
        color: #ffffff;
        border-color: #047857;
    }
    .btn-action-select.is-edit {
        background: #f1f5f9;
        color: #334155;
        border-color: #cbd5e1;
    }
    .btn-action-select.is-edit:hover {
        background: #334155;
        color: white;
    }
    .quick-btn-mini {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: #f1f5f9;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        transition: all 0.15s;
    }
    .quick-btn-mini:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .btn-wa-mini {
        background: #ecfdf5;
        color: #16a34a;
        border-color: #bbf7d0;
    }
    .btn-wa-mini:hover {
        background: #22c55e;
        color: white;
    }
    @media (max-width: 820px) {
        .student-search-selection-card {
            padding: 12px 14px;
        }
        .search-selection-header {
            flex-direction: column;
            align-items: stretch;
        }
        .student-filter-pills {
            width: 100%;
            display: flex;
        }
        .filter-pill {
            flex: 1;
            text-align: center;
            padding: 6px 4px;
        }
        .single-save-row {
            flex-direction: column;
            align-items: stretch;
        }
        .btn-save-single {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="halaqah-container">

    {{-- 1. TAB JIKA GURU MEMBIMBING LEBIH DARI 1 KELOMPOK --}}
    @if($myGroups->count() > 1)
        <div class="group-nav-tabs">
            @foreach($myGroups as $grp)
                <a href="{{ route('tahfidz-groups.index', ['group_id' => $grp->id, 'date' => $sessionDate]) }}" 
                   class="group-tab-btn {{ $grp->id == $activeGroup->id ? 'active' : '' }}">
                    <i class="fas fa-quran"></i>
                    <span>{{ $grp->name }}</span>
                    <span style="font-size: 0.72rem; padding: 1px 6px; border-radius: 6px; background: rgba(0,0,0,0.12);">
                        {{ $grp->students_count }} siswa
                    </span>
                </a>
            @endforeach
        </div>
    @endif

    {{-- BANNER SUKSES PENDAFTARAN MUNAQOSAH + TOMBOL KIRIM WA KE WALI SISWA --}}
    @if(session('munaqosah_wa_data'))
        @php
            $waData = session('munaqosah_wa_data');
            $waUrl = "https://api.whatsapp.com/send?phone=" . ($waData['phone'] ?? '') . "&text=" . urlencode($waData['message'] ?? '');
        @endphp
        <div style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 1.5px solid #6ee7b7; border-radius: 16px; padding: 14px 18px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.12); animation: fadeInDown 0.3s ease;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 12px; background: #059669; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #064e3b;">
                        Alhamdulillah! Pendaftaran Munaqosah {{ $waData['student_name'] }} Berhasil
                    </h4>
                    <p style="margin: 2px 0 0 0; font-size: 0.8rem; color: #047857;">
                        Siswa telah terplot ke dewan penguji. Bagikan kabar gembira dan jadwal ujian ke Orang Tua / Wali Siswa?
                    </p>
                </div>
            </div>
            <div style="display: flex; gap: 8px;">
                @if(!empty($waData['phone']))
                    <a href="{{ $waUrl }}" target="_blank" 
                       style="background: #25D366; color: white; padding: 9px 16px; border-radius: 10px; font-size: 0.84rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 3px 10px rgba(37, 211, 102, 0.35);">
                        <i class="fab fa-whatsapp" style="font-size: 1.1rem;"></i> Kirim Jadwal ke WhatsApp Ortu
                    </a>
                @else
                    <button type="button" onclick="copyMunaqosahWa('{{ addslashes($waData['message']) }}')"
                            style="background: #059669; color: white; border: none; padding: 9px 16px; border-radius: 10px; font-size: 0.84rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fas fa-copy"></i> Salin Pesan WA
                    </button>
                @endif
            </div>
        </div>
    @endif

    {{-- 2. BANNER UTAMA RUANG HALAQAH --}}
    <div class="halaqah-card-header">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 5px; background: rgba(255, 255, 255, 0.2); padding: 3px 10px; border-radius: 16px; font-size: 0.74rem; font-weight: 700; margin-bottom: 4px; text-transform: uppercase;">
                <i class="fas fa-star-and-crescent"></i> Ruang Halaqah Sederhana
            </div>
            <h1 class="halaqah-title">
                {{ $activeGroup->name }}
            </h1>
            <div class="header-meta-info">
                <span><i class="fas fa-user-tie"></i> Musyrif: <strong>{{ $activeGroup->mentor_name ?? Auth::user()->name }}</strong></span>
                <span><i class="fas fa-bullseye"></i> Target: <strong>{{ $activeGroup->target_level ?? 'Juz 30' }}</strong></span>
                <span><i class="fas fa-users"></i> <strong>{{ $activeGroup->students->count() }} Siswa</strong></span>
            </div>
            @if($myGroups->count() > 1)
                <div style="margin-top: 8px; display: inline-flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.18); padding: 4px 10px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.25);">
                    <span style="font-size: 0.78rem; font-weight: 700; color: #ffffff;"><i class="fas fa-layer-group"></i> Ganti Kelompok:</span>
                    <select onchange="window.location.href=this.value" style="background: white; color: #064e3b; font-weight: 800; font-size: 0.8rem; border: none; border-radius: 6px; padding: 4px 8px; cursor: pointer; outline: none;">
                        @foreach($myGroups as $grp)
                            <option value="{{ route('tahfidz-groups.index', ['group_id' => $grp->id, 'date' => $sessionDate]) }}" {{ $grp->id == $activeGroup->id ? 'selected' : '' }}>
                                {{ $grp->name }} ({{ $grp->students_count }} Siswa)
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        {{-- KANAN: PEMILIH TANGGAL SESI & SHARE WA CEPAT --}}
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <div class="session-date-picker-wrap">
                <span style="font-size: 0.78rem; font-weight: 700;">📅 Tanggal:</span>
                <input type="date" value="{{ $sessionDate }}" id="sessionDatePicker" 
                       onchange="changeSessionDate(this.value)">
                @if($sessionDate !== $today)
                    <button type="button" onclick="changeSessionDate('{{ $today }}')" 
                            style="background: white; border: none; padding: 4px 8px; border-radius: 5px; font-size: 0.72rem; font-weight: 800; color: #047857; cursor: pointer;">
                        Hari Ini
                    </button>
                @endif
            </div>

            <button type="button" onclick="openWaModal()" class="btn-header-wa"
                    style="background: #25D366; color: white; border: none; padding: 8px 14px; border-radius: 10px; font-weight: 800; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 3px 10px rgba(37, 211, 102, 0.3);">
                <i class="fab fa-whatsapp" style="font-size: 1rem;"></i>
                <span>Laporan WA Sesi</span>
            </button>
        </div>
    </div>

    {{-- 3. KOTAK INFORMASI STATISTIK HIJAU (INFORMATIF & SIMPEL) --}}
    @php
        $totalSiswa = $activeGroup->students->count();
        $kehadiranHariIni = $sessionAttendances->where('status', 'hadir')->count();
        $sudahSetorHariIni = $sessionAttendances->where('status', 'hadir')->filter(fn($a) => !empty($a->capaian))->count();
        $totalBaris = (int) ($totalGroupLines ?? 0);
    @endphp

    <div class="kpi-green-grid">
        <div class="kpi-green-card">
            <div class="kpi-green-icon">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="kpi-green-label">Total Siswa</div>
                <div class="kpi-green-value">
                    {{ $totalSiswa }} <span class="kpi-green-unit">Siswa</span>
                </div>
            </div>
        </div>

        <div class="kpi-green-card">
            <div class="kpi-green-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <div class="kpi-green-label">Kehadiran Hari Ini</div>
                <div class="kpi-green-value">
                    {{ $kehadiranHariIni }} <span class="kpi-green-unit">/ {{ $totalSiswa }} Hadir</span>
                </div>
            </div>
        </div>

        <div class="kpi-green-card">
            <div class="kpi-green-icon">
                <i class="fas fa-book-reader"></i>
            </div>
            <div>
                <div class="kpi-green-label">Sudah Setoran</div>
                <div class="kpi-green-value">
                    {{ $sudahSetorHariIni }} <span class="kpi-green-unit">Siswa</span>
                </div>
            </div>
        </div>

        <div class="kpi-green-card">
            <div class="kpi-green-icon">
                <i class="fas fa-align-left"></i>
            </div>
            <div>
                <div class="kpi-green-label">Total Baris</div>
                <div class="kpi-green-value">
                    {{ number_format($totalBaris, 0, ',', '.') }} <span class="kpi-green-unit">Baris</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. BANNER SUKSES SIMPAN & KIRIM LAPORAN WA (Hanya tampil saat bukan tampilan ringkasan sesi tersimpan) --}}
    @if((session('success') || $openShare) && (!$isSessionSaved || $isEditMode))
        <div class="wa-success-banner" id="successBanner">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: #22c55e; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <h4 style="margin: 0; color: #166534; font-weight: 800; font-size: 0.92rem;">
                        {{ session('success') ?? 'Data Sesi ' . \Carbon\Carbon::parse($sessionDate)->locale('id')->isoFormat('dddd, D MMMM Y') . ' Berhasil Disimpan!' }}
                    </h4>
                    <p style="margin: 2px 0 0 0; color: #15803d; font-size: 0.8rem;">
                        Semua data langsung tersinkron. Siap dikirim ke WhatsApp?
                    </p>
                </div>
            </div>
            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                <button type="button" onclick="openWaModal()" 
                        style="background: #25D366; color: white; border: none; padding: 7px 14px; border-radius: 8px; font-weight: 800; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fab fa-whatsapp"></i> Buka WhatsApp
                </button>
                <button type="button" onclick="copyWaText(this)" 
                        style="background: #0284c7; color: white; border: none; padding: 7px 12px; border-radius: 8px; font-weight: 700; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fas fa-copy"></i> Salin
                </button>
            </div>
        </div>
    @endif

    {{-- 5. BANNER SELESAI SELURUH SISWA (JIKA SEMUA SISWA SUDAH SETORAN) --}}
    @if($totalSiswa > 0 && $sudahSetorHariIni >= $totalSiswa)
        <div style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 1.5px solid #6ee7b7; border-radius: 16px; padding: 14px 18px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.12);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 12px; background: #059669; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                    <i class="fas fa-check-double"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 0.96rem; font-weight: 800; color: #064e3b;">
                        Alhamdulillah! Seluruh Siswa Telah Setoran ({{ $sudahSetorHariIni }}/{{ $totalSiswa }} Siswa)
                    </h4>
                    <p style="margin: 2px 0 0 0; font-size: 0.8rem; color: #047857;">
                        Sesi tanggal {{ \Carbon\Carbon::parse($sessionDate)->locale('id')->isoFormat('D MMMM Y') }} tuntas. Anda dapat membagikan laporan WA atau mengedit setoran siswa kapan saja.
                    </p>
                </div>
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="button" onclick="openWaModal()" 
                        style="background: #25D366; color: white; border: none; padding: 8px 14px; border-radius: 9px; font-size: 0.82rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 3px 10px rgba(37, 211, 102, 0.3);">
                    <i class="fab fa-whatsapp"></i> Laporan WA Sesi
                </button>
                <button type="button" onclick="copyWaText(this)" 
                        style="background: #0284c7; color: white; border: none; padding: 8px 12px; border-radius: 9px; font-size: 0.82rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-copy"></i> Salin
                </button>
            </div>
        </div>
    @endif

    {{-- 6. KOTAK PENCARIAN & DROPDOWN PEMILIH SANTRI SESUAI NAMA KELOMPOK --}}
    <div class="student-search-selection-card">
        <div class="search-selection-header">
            <div class="search-selection-title-box">
                <div class="search-selection-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div>
                    <h3 class="search-selection-title">Pencarian & Pilih Nama Santri Setoran</h3>
                    <p class="search-selection-subtitle">
                        Kelompok: <strong>{{ $activeGroup->name }}</strong> &bull; Input setoran bergantian satu per satu. Form hafalan dan tombol simpan akan muncul untuk anak yang dipilih.
                    </p>
                </div>
            </div>

            {{-- FILTER PILLS: SEMUA, BELUM SETOR, SUDAH SETOR --}}
            <div class="student-filter-pills">
                <button type="button" class="filter-pill active" id="filter-pill-all" onclick="filterStudentDropdown('all')">
                    Semua (<span id="count-pill-all">{{ $totalSiswa }}</span>)
                </button>
                <button type="button" class="filter-pill" id="filter-pill-belum" onclick="filterStudentDropdown('belum')">
                    Belum Setor (<span id="count-pill-belum">{{ max(0, $totalSiswa - $sudahSetorHariIni) }}</span>)
                </button>
                <button type="button" class="filter-pill" id="filter-pill-sudah" onclick="filterStudentDropdown('sudah')">
                    Sudah Setor (<span id="count-pill-sudah">{{ $sudahSetorHariIni }}</span>)
                </button>
            </div>
        </div>

        {{-- SEARCHABLE DROPDOWN TRIGGER & PANEL --}}
        <div class="custom-student-dropdown" id="studentSearchDropdownWrap">
            <button type="button" class="student-dropdown-trigger" id="studentDropdownTrigger" onclick="toggleStudentDropdown(event)">
                <div class="trigger-left" id="triggerDisplayArea">
                    <i class="fas fa-user-graduate" style="color: #059669; font-size: 1.15rem; flex-shrink: 0;"></i>
                    <span class="trigger-placeholder" id="triggerPlaceholderText">-- Klik di sini untuk Cari / Pilih Nama Siswa yang Setoran --</span>
                </div>
                <div class="trigger-right">
                    <span class="badge-active-group">{{ $activeGroup->name }}</span>
                    <i class="fas fa-chevron-down" id="triggerChevron"></i>
                </div>
            </button>

            {{-- MENU POPUP PENCARIAN --}}
            <div class="student-dropdown-menu" id="studentDropdownMenu">
                <div class="dropdown-search-box">
                    <i class="fas fa-search dropdown-search-icon"></i>
                    <input type="text" id="studentSearchField" class="dropdown-search-input" 
                           placeholder="Ketik nama anak atau kelas untuk mencari..." 
                           oninput="handleStudentSearchInput(this.value)" autocomplete="off">
                    <button type="button" class="clear-search-btn" id="clearSearchBtn" onclick="clearStudentSearchInput()" style="display: none;">&times;</button>
                </div>
                <div class="dropdown-options-list" id="studentDropdownOptionsList">
                    @foreach($activeGroup->students as $idx => $std)
                        @php
                            $existingAtt = $sessionAttendances->get($std->id);
                            $hasSetor = $existingAtt && !empty($existingAtt->capaian);
                            $stdStatusKey = $hasSetor ? 'sudah' : ($existingAtt && $existingAtt->status !== 'hadir' ? 'absen' : 'belum');
                        @endphp
                        <div class="dropdown-student-item" 
                             id="dropdown-item-{{ $std->id }}"
                             data-student-id="{{ $std->id }}"
                             data-name="{{ strtolower($std->name) }}"
                             data-class="{{ strtolower($std->class) }}"
                             data-status="{{ $stdStatusKey }}"
                             onclick="selectStudent('{{ $std->id }}')">
                            <div class="item-avatar">{{ $idx + 1 }}</div>
                            <div class="item-info">
                                <div class="item-name">{{ $std->name }}</div>
                                <div class="item-meta">
                                    Kelas {{ $std->class }} 
                                    @if(isset($studentTargetProgress[$std->id]))
                                        &bull; Target: {{ $studentTargetProgress[$std->id]['percent'] }}%
                                    @endif
                                </div>
                            </div>
                            <div class="item-badge-status" id="dropdown-status-{{ $std->id }}">
                                @if($hasSetor)
                                    <span class="badge-status-sudah">
                                        <i class="fas fa-check-circle"></i> Sudah: {{ Str::limit($existingAtt->capaian, 18) }}
                                    </span>
                                @elseif($existingAtt && $existingAtt->status !== 'hadir')
                                    <span class="badge-status-absen">
                                        {{ ucfirst($existingAtt->status) }}
                                    </span>
                                @else
                                    <span class="badge-status-belum">
                                        <i class="far fa-clock"></i> Belum Setor
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <div class="dropdown-empty-state" id="dropdownEmptyNotice" style="display: none;">
                        <i class="fas fa-search"></i> Nama anak tidak ditemukan
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 7. AREA KOTAK FORM SETORAN SISWA AKTIF (MUNCUL SATU PER SATU SAAT DIPILIH) --}}
    <div id="activeStudentFormArea">
        {{-- EMPTY STATE: TAMPIL JIKA BELUM ADA SISWA YANG DIPILIH --}}
        <div class="empty-selection-placeholder" id="emptySelectionPlaceholder">
            <div class="empty-placeholder-icon">
                <i class="fas fa-quran"></i>
            </div>
            <h4 class="empty-placeholder-title">Silakan Pilih Nama Santri di Atas</h4>
            <p class="empty-placeholder-desc">
                Pilih atau cari nama santri yang sedang maju setoran melalui kolom pencarian di atas, atau klik tombol <strong>"Input Setoran"</strong> pada daftar di bawah. Kotak form isian hafalan dan tombol simpan akan langsung muncul di sini.
            </p>
        </div>

        {{-- FORM WORKSPACE PER SISWA (HANYA MUNCUL SATU PER SATU SAAT DIPILIH) --}}
        @foreach($activeGroup->students as $idx => $std)
            @php
                $existingAtt = $sessionAttendances->get($std->id);
                $currentStatus = $existingAtt ? $existingAtt->status : 'hadir';
                $currentCapaian = $existingAtt ? $existingAtt->capaian : '';
                $currentNilai = $existingAtt ? ($existingAtt->nilai ?? '') : '';
                $currentBaris = $existingAtt ? ($existingAtt->jumlah_baris ?? 0) : '';
                $currentLulus = $existingAtt ? strtoupper($existingAtt->hafalan_status ?? 'LULUS') : 'LULUS';
                $currentNotes = $existingAtt ? ($existingAtt->tugas_besok ?? $existingAtt->notes ?? '') : '';

                // Parse capaian untuk auto-select dropdown surat & input ayat
                $parsedCapaian = \App\Http\Controllers\TahfidzAttendanceController::parseCapaian($currentCapaian);
                $selectedSurah = $parsedCapaian['surah_name'] ?? '';
                $selectedAyat = '';
                if (!empty($parsedCapaian['start_ayah'])) {
                    $selectedAyat = $parsedCapaian['start_ayah'] . (!empty($parsedCapaian['end_ayah']) && $parsedCapaian['end_ayah'] != $parsedCapaian['start_ayah'] ? '-' . $parsedCapaian['end_ayah'] : '');
                }

                // Riwayat hafalan sebelumnya untuk panduan guru
                $lastRec = $latestRecords->get($std->id)?->first();
                $cleanPhone = preg_replace('/[^0-9]/', '', $std->parent_phone ?? '');
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }
            @endphp

            <div class="active-student-form-card" id="student-workspace-{{ $std->id }}" style="display: none;">
                
                {{-- INDIKATOR TOP BAR: STATUS AKTIF + TOMBOL GANTI/TUTUP FORM --}}
                <div class="active-card-top-indicator">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="display: inline-block; width: 9px; height: 9px; border-radius: 50%; background: #4ade80; box-shadow: 0 0 8px #4ade80;"></span>
                        <span>FORM INPUT SETORAN AKTIF &bull; NO. {{ $idx + 1 }}</span>
                    </div>
                    <button type="button" class="btn-close-form" onclick="deselectStudent()" title="Tutup form dan kembali ke pencarian">
                        <i class="fas fa-times"></i> <span>Ganti / Tutup Form</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('tahfidz.attendance.store', ['group' => $activeGroup->id]) }}" 
                      class="single-student-form" id="form-student-{{ $std->id }}" data-student-id="{{ $std->id }}">
                    @csrf
                    <input type="hidden" name="session_date" value="{{ $sessionDate }}">
                    <input type="hidden" name="academic_year_id" value="{{ $activeGroup->academic_year_id ?? $activeYear?->id }}">
                    <input type="hidden" name="redirect_to" value="workspace">
                    <input type="hidden" name="selected_student_id" value="{{ $std->id }}">

                    <div class="student-entry-card {{ $currentStatus !== 'hadir' ? 'is-absent' : '' }}" id="card-student-{{ $std->id }}" style="border: none; padding: 0; box-shadow: none; margin-bottom: 0;">
                        {{-- BARIS ATAS: IDENTITAS + QUICK ACTIONS --}}
                        <div class="student-card-header-row" style="margin-bottom: 14px;">
                            <div class="student-info-flex">
                                <div class="student-avatar-num" style="width: 44px; height: 44px; font-size: 1.1rem;">
                                    {{ $idx + 1 }}
                                </div>
                                <div>
                                    <div class="student-fullname" style="font-size: 1.15rem; color: #064e3b;">
                                        {{ $std->name }}
                                    </div>
                                    <div class="student-meta-row">
                                        <span style="background: #f1f5f9; padding: 2px 8px; border-radius: 5px; font-weight: 700; color: #334155;">
                                            Kelas {{ $std->class }}
                                        </span>
                                        @php
                                            $prog = $studentTargetProgress[$std->id] ?? null;
                                            $isTuntas = $prog && ($prog['is_completed'] || $prog['percent'] >= 100);
                                        @endphp
                                        @if($isTuntas)
                                            <span style="background: #fef3c7; color: #92400e; border: 1.5px solid #fde68a; font-weight: 800; font-size: 0.72rem; padding: 2px 8px; border-radius: 5px; display: inline-flex; align-items: center; gap: 3px;">
                                                <i class="fas fa-star" style="color: #f59e0b;"></i> Target 100% Tuntas
                                            </span>
                                        @elseif($prog && $prog['percent'] > 0)
                                            <span style="background: #f0fdf4; color: #166534; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 5px;">
                                                Target: {{ $prog['completed'] }}/{{ $prog['total'] }} ({{ $prog['percent'] }}%)
                                            </span>
                                        @endif
                                        @if($lastRec)
                                            <span style="color: #047857; font-weight: 600;">
                                                📌 Lalu: <strong>{{ $lastRec->surah_name }}</strong>{{ $lastRec->start_ayah ? " ({$lastRec->start_ayah}-{$lastRec->end_ayah})" : '' }}
                                            </span>
                                            <button type="button" 
                                                    onclick="selectStudentSurah('{{ $std->id }}', '{{ addslashes($lastRec->surah_name) }}', '{{ $lastRec->start_ayah ? $lastRec->start_ayah . ($lastRec->end_ayah && $lastRec->end_ayah != $lastRec->start_ayah ? '-' . $lastRec->end_ayah : '') : '' }}')"
                                                    style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; border-radius: 4px; padding: 2px 6px; font-size: 0.72rem; font-weight: 700; cursor: pointer;">
                                                Gunakan
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- QUICK BUTTONS: KARTU, WA ORTU, MUNAQOSAH --}}
                            <div style="display: flex; align-items: center; gap: 6px;">
                                @if(isset($munaqosahRegistrations[$std->id]))
                                    <span style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; padding: 5px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;" title="Terdaftar Munaqosah: {{ $munaqosahRegistrations[$std->id]->exam_time ?? 'Menunggu Jadwal' }}">
                                        <i class="fas fa-award"></i> <span>Munaqosah</span>
                                    </span>
                                @elseif($isTuntas)
                                    <button type="button" 
                                            onclick="openQuickMunaqosahModal({{ $std->id }}, '{{ addslashes($std->name) }}', '{{ $std->class }}')"
                                            style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; padding: 5px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(217, 119, 6, 0.35);"
                                            title="Target 100% Tuntas! Klik untuk Daftarkan Munaqosah">
                                        <i class="fas fa-star" style="color: #fef08a;"></i> <span>Tuntas (Daftar Munaqosah)</span>
                                    </button>
                                @else
                                    <button type="button" 
                                            onclick="openQuickMunaqosahModal({{ $std->id }}, '{{ addslashes($std->name) }}', '{{ $std->class }}')"
                                            style="background: #f8fafc; border: 1px solid #cbd5e1; color: #475569; padding: 5px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;"
                                            title="Daftarkan Ujian Munaqosah">
                                        <i class="fas fa-award"></i> <span>Munaqosah</span>
                                    </button>
                                @endif

                                <a href="{{ route('tahfidz.student-card', ['group' => $activeGroup->id, 'student' => $std->id]) }}" 
                                   target="_blank" class="quick-btn-mini" title="Buka Kartu & Rekap Hafalan Siswa">
                                    <i class="fas fa-id-card"></i>
                                </a>

                                @if(!empty($cleanPhone))
                                    <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}" 
                                       target="_blank" class="quick-btn-mini btn-wa-mini" title="Chat WhatsApp Wali Murid">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- PRESENSI KEHADIRAN (SEGMENTED CONTROL) --}}
                        <div style="margin-bottom: 12px; background: #f8fafc; padding: 8px 12px; border-radius: 10px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                            <span style="font-size: 0.8rem; font-weight: 800; color: #475569; text-transform: uppercase;">
                                <i class="fas fa-user-check"></i> Status Kehadiran:
                            </span>
                            <div class="att-segmented-container">
                                <label class="att-segmented-item">
                                    <input type="radio" name="attendance[{{ $std->id }}][status]" value="hadir" 
                                           class="att-pill-radio" {{ $currentStatus === 'hadir' ? 'checked' : '' }}
                                           onchange="toggleAttendanceStatus('{{ $std->id }}', 'hadir')">
                                <span class="att-segmented-btn att-pill-hadir">Hadir</span>
                            </label>
                            <label class="att-segmented-item">
                                <input type="radio" name="attendance[{{ $std->id }}][status]" value="sakit" 
                                       class="att-pill-radio" {{ $currentStatus === 'sakit' ? 'checked' : '' }}
                                       onchange="toggleAttendanceStatus('{{ $std->id }}', 'sakit')">
                                <span class="att-segmented-btn att-pill-sakit">Sakit</span>
                            </label>
                            <label class="att-segmented-item">
                                <input type="radio" name="attendance[{{ $std->id }}][status]" value="izin" 
                                       class="att-pill-radio" {{ $currentStatus === 'izin' ? 'checked' : '' }}
                                       onchange="toggleAttendanceStatus('{{ $std->id }}', 'izin')">
                                <span class="att-segmented-btn att-pill-izin">Izin</span>
                            </label>
                            <label class="att-segmented-item">
                                <input type="radio" name="attendance[{{ $std->id }}][status]" value="alpha" 
                                       class="att-pill-radio" {{ $currentStatus === 'alpha' ? 'checked' : '' }}
                                       onchange="toggleAttendanceStatus('{{ $std->id }}', 'alpha')">
                                <span class="att-segmented-btn att-pill-alpha">Alpha</span>
                            </label>
                        </div>
                    </div>

                    {{-- FORM INPUT CAPAIAN HAFALAN (HANYA AKTIF JIKA HADIR) --}}
                    <div class="hafalan-box-wrap" id="hafalan-section-{{ $std->id }}" 
                         style="{{ $currentStatus !== 'hadir' ? 'display: none;' : '' }}">
                        
                        {{-- 1. SURAH (DROPDOWN TARGET SESUAI JUZ ADMIN) --}}
                        <div class="hafalan-col-surah">
                            <label class="hafalan-field-label">📖 Surat (Target Juz)</label>
                            <div class="surah-picker-wrap" id="surah-picker-wrap-{{ $std->id }}">
                                <select name="attendance[{{ $std->id }}][surah_name]" 
                                        id="surah-select-{{ $std->id }}"
                                        class="surah-native-hidden" tabindex="-1" aria-hidden="true">
                                    <option value="">-- Pilih Surat --</option>
                                    @foreach(($targetJuzGroups ?? []) as $juzNum => $sList)
                                        <optgroup label="⭐ Target: Juz {{ $juzNum }}">
                                            @foreach($sList as $sName)
                                                @php
                                                    $cleanS = strtolower(preg_replace('/[^a-z0-9]/', '', $sName));
                                                    $cleanSel = strtolower(preg_replace('/[^a-z0-9]/', '', $selectedSurah));
                                                    $isSel = ($cleanS === $cleanSel || str_contains($cleanSel, $cleanS) || str_contains($cleanS, $cleanSel));
                                                @endphp
                                                <option value="{{ $sName }}" {{ $isSel ? 'selected' : '' }}>
                                                    {{ $sName }} (Juz {{ $juzNum }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                    @if(!empty($otherSurahs))
                                        <optgroup label="📖 Surat / Juz Lainnya">
                                            @foreach(($otherSurahs ?? []) as $sName)
                                                @php
                                                    $cleanS = strtolower(preg_replace('/[^a-z0-9]/', '', $sName));
                                                    $cleanSel = strtolower(preg_replace('/[^a-z0-9]/', '', $selectedSurah));
                                                    $isSel = ($cleanS === $cleanSel || str_contains($cleanSel, $cleanS) || str_contains($cleanS, $cleanSel));
                                                @endphp
                                                <option value="{{ $sName }}" {{ $isSel ? 'selected' : '' }}>
                                                    {{ $sName }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>

                                <div class="surah-picker-trigger" 
                                     id="surah-trigger-{{ $std->id }}"
                                     onclick="toggleSurahPicker('{{ $std->id }}', event)">
                                    <div class="surah-trigger-left">
                                        <span class="surah-trigger-icon">📖</span>
                                        <span class="surah-trigger-text {{ empty($selectedSurah) ? 'is-placeholder' : '' }}" 
                                              id="surah-trigger-text-{{ $std->id }}">
                                            {{ !empty($selectedSurah) ? $selectedSurah : '-- Pilih Surat --' }}
                                        </span>
                                    </div>
                                    <div class="surah-trigger-arrow">
                                        <i class="fas fa-chevron-down surah-chevron"></i>
                                    </div>
                                </div>

                                <div class="surah-picker-popover" id="surah-popover-{{ $std->id }}">
                                    <div class="surah-search-box">
                                        <i class="fas fa-search surah-search-icon"></i>
                                        <input type="text" 
                                               class="surah-search-input" 
                                               id="surah-search-{{ $std->id }}"
                                               placeholder="Ketik nama surat..." 
                                               autocomplete="off"
                                               oninput="filterSurahOptions('{{ $std->id }}', this.value)"
                                               onclick="event.stopPropagation()">
                                    </div>
                                    <div class="surah-options-list" id="surah-options-{{ $std->id }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. AYAT --}}
                        <div class="hafalan-col-ayat">
                            <label class="hafalan-field-label">🔢 Ayat</label>
                            <input type="text" name="attendance[{{ $std->id }}][ayat]" 
                                   id="ayat-input-{{ $std->id }}"
                                   class="input-clean" 
                                   value="{{ $selectedAyat }}"
                                   placeholder="cth: 1-15">
                        </div>

                        {{-- 3. NILAI --}}
                        <div class="hafalan-col-nilai">
                            <label class="hafalan-field-label">⭐ Nilai</label>
                            <input type="text" name="attendance[{{ $std->id }}][nilai]" 
                                   class="input-clean input-nilai" 
                                   value="{{ $currentNilai }}"
                                   placeholder="cth: 90">
                        </div>

                        {{-- 4. JUMLAH BARIS --}}
                        <div class="hafalan-col-baris">
                            <label class="hafalan-field-label">📏 Baris</label>
                            <input type="number" name="attendance[{{ $std->id }}][jumlah_baris]" 
                                   class="input-clean" 
                                   min="0" max="500"
                                   value="{{ $currentBaris !== '' ? $currentBaris : '' }}"
                                   placeholder="0">
                        </div>

                        {{-- 5. STATUS LULUS / BELUM --}}
                        <div class="hafalan-col-status">
                            <label class="hafalan-field-label">🎯 Status</label>
                            <div class="lulus-pills">
                                <label class="lulus-pill-btn" style="padding: 0; margin: 0; width: 100%;">
                                    <input type="radio" name="attendance[{{ $std->id }}][hafalan_status]" value="LULUS" 
                                           class="lulus-radio" style="display: none;" 
                                           {{ $currentLulus === 'LULUS' ? 'checked' : '' }}>
                                    <span class="lulus-pill-btn btn-lulus">✓ Lulus</span>
                                </label>
                                <label class="lulus-pill-btn" style="padding: 0; margin: 0; width: 100%;">
                                    <input type="radio" name="attendance[{{ $std->id }}][hafalan_status]" value="BELUM LULUS" 
                                           class="lulus-radio" style="display: none;"
                                           {{ $currentLulus === 'BELUM LULUS' ? 'checked' : '' }}>
                                    <span class="lulus-pill-btn btn-ulang">✕ Ulang</span>
                                </label>
                            </div>
                        </div>

                        {{-- 6. TUGAS BESOK / CATATAN GURU --}}
                        <div class="hafalan-col-notes">
                            <label class="hafalan-field-label">📝 Tugas Besok / Catatan</label>
                            <input type="text" name="attendance[{{ $std->id }}][tugas_besok]" 
                                   class="input-clean" 
                                   value="{{ $currentNotes }}"
                                   placeholder="Cth: Lanjut ayat 16-25">
                        </div>

                    </div>

                    {{-- KETERANGAN TIDAK HADIR --}}
                    <div id="absent-note-{{ $std->id }}" 
                         style="margin-top: 8px; font-size: 0.78rem; color: #64748b; font-style: italic; {{ $currentStatus === 'hadir' ? 'display: none;' : '' }}">
                        Siswa berstatus <strong id="absent-text-{{ $std->id }}">{{ strtoupper($currentStatus) }}</strong> (tidak perlu mengisi setoran hafalan).
                    </div>
                </div>

                {{-- BARIS TOMBOL SIMPAN SETORAN SISWA --}}
                <div class="single-save-row">
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <button type="submit" class="btn-save-single" id="btn-save-{{ $std->id }}">
                            <i class="fas fa-save"></i>
                            <span>Simpan Setoran {{ $std->name }}</span>
                        </button>
                        <div id="save-feedback-{{ $std->id }}" class="save-feedback-badge" style="display: none;"></div>
                    </div>

                    <button type="button" onclick="deselectStudent()" 
                            style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 9px 16px; border-radius: 9px; font-weight: 700; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                        <i class="fas fa-check"></i> Selesai / Ganti Santri &rarr;
                    </button>
                </div>

            </form>
        </div>
    @endforeach
</div>

{{-- 8. DAFTAR SELURUH SANTRI DI KELOMPOK (STATUS SETORAN HARI INI) --}}
<div class="group-students-roster-card">
    <div class="roster-header">
        <h3 class="roster-title">
            <i class="fas fa-users-cog" style="color: #047857;"></i>
            <span>Daftar Santri Kelompok {{ $activeGroup->name }}</span>
            <span style="font-size: 0.76rem; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 2px 8px; border-radius: 6px; font-weight: 700;">
                Total: {{ $totalSiswa }} Siswa
            </span>
        </h3>
        <div style="font-size: 0.82rem; color: #64748b;">
            Hari Ini: <strong style="color: #047857;" id="roster-stat-setor">{{ $sudahSetorHariIni }}</strong> Sudah Setor &bull; 
            <strong style="color: #b45309;" id="roster-stat-belum">{{ max(0, $totalSiswa - $sudahSetorHariIni) }}</strong> Belum Setor
        </div>
    </div>

    <div class="recap-table-wrap">
        <table class="roster-table">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">No</th>
                    <th>Nama Santri</th>
                    <th>Kelas</th>
                    <th>Capaian Target</th>
                    <th>Hafalan Lalu</th>
                    <th>Status Setoran Hari Ini</th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeGroup->students as $idx => $std)
                    @php
                        $existingAtt = $sessionAttendances->get($std->id);
                        $hasSetor = $existingAtt && !empty($existingAtt->capaian);
                        $lastRec = $latestRecords->get($std->id)?->first();
                        $prog = $studentTargetProgress[$std->id] ?? null;
                    @endphp
                    <tr id="roster-row-{{ $std->id }}">
                        <td style="text-align: center; font-weight: 800; color: #64748b;">{{ $idx + 1 }}</td>
                        <td>
                            <strong style="color: #0f172a; font-size: 0.88rem;">{{ $std->name }}</strong>
                        </td>
                        <td>
                            <span style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-weight: 700; color: #334155; font-size: 0.76rem;">
                                Kelas {{ $std->class }}
                            </span>
                        </td>
                        <td>
                            @if($prog && $prog['percent'] > 0)
                                <span style="background: #f0fdf4; color: #166534; font-size: 0.74rem; font-weight: 700; padding: 2px 7px; border-radius: 4px;">
                                    {{ $prog['completed'] }}/{{ $prog['total'] }} ({{ $prog['percent'] }}%)
                                </span>
                            @else
                                <span style="color: #94a3b8; font-size: 0.75rem;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($lastRec)
                                <span style="color: #047857; font-size: 0.78rem; font-weight: 600;">
                                    {{ $lastRec->surah_name }}{{ $lastRec->start_ayah ? " ({$lastRec->start_ayah}-{$lastRec->end_ayah})" : '' }}
                                </span>
                            @else
                                <span style="color: #94a3b8; font-size: 0.75rem;">Belum ada riwayat</span>
                            @endif
                        </td>
                        <td id="roster-status-td-{{ $std->id }}">
                            @if($hasSetor)
                                <span class="badge-status-sudah">
                                    <i class="fas fa-check-circle"></i> {{ $existingAtt->capaian }}
                                    @if($existingAtt->nilai)
                                        (Nilai: {{ $existingAtt->nilai }})
                                    @endif
                                </span>
                            @elseif($existingAtt && $existingAtt->status !== 'hadir')
                                <span class="badge-status-absen">
                                    {{ ucfirst($existingAtt->status) }}
                                </span>
                            @else
                                <span class="badge-status-belum">
                                    <i class="far fa-clock"></i> Belum Setor
                                </span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <button type="button" 
                                    class="btn-action-select {{ $hasSetor ? 'is-edit' : '' }}" 
                                    id="roster-btn-{{ $std->id }}"
                                    onclick="selectStudent('{{ $std->id }}')">
                                @if($hasSetor)
                                    <i class="fas fa-edit"></i> <span>Ubah</span>
                                @else
                                    <i class="fas fa-plus-circle"></i> <span>Input</span>
                                @endif
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">
                            Belum ada siswa di kelompok ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- 9. STICKY BOTTOM BAR --}}
@if($activeGroup->students->isNotEmpty())
    <div class="sticky-bottom-bar">
        <div class="sticky-content">
            <div class="sticky-info-text">
                <i class="fas fa-calendar-day" style="color: #059669; margin-right: 4px;"></i>
                Sesi: <strong>{{ \Carbon\Carbon::parse($sessionDate)->locale('id')->isoFormat('D MMMM Y') }}</strong>
                <span style="margin: 0 6px; color: #cbd5e1;">&bull;</span>
                Kelompok: <strong>{{ $activeGroup->name }}</strong>
                <span style="margin: 0 6px; color: #cbd5e1;">&bull;</span>
                Sudah Setoran: <strong id="sticky-setor-count" style="color: #047857;">{{ $sudahSetorHariIni }}</strong> / {{ $totalSiswa }} Siswa
            </div>

            <div class="sticky-actions-wrap">
                <button type="button" onclick="openWaModal()" class="btn-sticky-wa">
                    <i class="fab fa-whatsapp"></i> Share WA Sesi
                </button>
            </div>
        </div>
    </div>
@endif
</div>

{{-- MODAL PREVIEW & SHARE LAPORAN WHATSAPP --}}
<div class="modal-wa" id="waModal" onclick="if(event.target === this) closeWaModal()">
    <div class="modal-wa-box">
        <div style="background: #075e54; color: white; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 7px; font-weight: 800; font-size: 1rem;">
                <i class="fab fa-whatsapp" style="font-size: 1.2rem; color: #25D366;"></i>
                <span>Laporan WhatsApp Halaqah</span>
            </div>
            <button type="button" onclick="closeWaModal()" style="background: transparent; border: none; color: white; font-size: 1.3rem; cursor: pointer; line-height: 1;">
                &times;
            </button>
        </div>

        <div style="padding: 16px;">
            <p style="margin: 0 0 8px 0; font-size: 0.82rem; color: #475569;">
                Teks laporan otomatis tersusun rapi untuk dikirim ke Grup WhatsApp Kelas atau Paguyuban Wali:
            </p>

            <textarea id="waMessageTextarea" readonly 
                      style="width: 100%; height: 240px; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 10px; font-family: monospace; font-size: 0.8rem; color: #0f172a; background: #f8fafc; resize: none; box-sizing: border-box; line-height: 1.45;">{{ $whatsappMessage }}</textarea>

            <div style="display: flex; gap: 8px; margin-top: 14px; flex-wrap: wrap;">
                @php
                    $encodedMsg = urlencode($whatsappMessage);
                @endphp
                <a href="https://api.whatsapp.com/send?text={{ $encodedMsg }}" target="_blank" 
                   style="flex: 2; background: #25D366; color: white; border: none; padding: 10px 14px; border-radius: 8px; font-weight: 800; font-size: 0.85rem; text-decoration: none; text-align: center; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                    <i class="fab fa-whatsapp" style="font-size: 1.1rem;"></i> Buka WhatsApp
                </a>
                <button type="button" onclick="copyWaText()" 
                        style="flex: 1; background: #f1f5f9; color: #0f172a; border: 1px solid #cbd5e1; padding: 10px 12px; border-radius: 8px; font-weight: 700; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 5px;">
                    <i class="fas fa-copy"></i> Salin
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Ganti Tanggal Sesi
    function changeSessionDate(newDate) {
        if (!newDate) return;
        window.location.href = "{{ route('tahfidz-groups.index', ['group_id' => $activeGroup->id]) }}&date=" + newDate;
    }

    // Toggle Hadir vs Tidak Hadir (Sakit / Izin / Alpha)
    function toggleAttendanceStatus(studentId, status) {
        const card = document.getElementById('card-student-' + studentId);
        const hafalanSection = document.getElementById('hafalan-section-' + studentId);
        const absentNote = document.getElementById('absent-note-' + studentId);
        const absentText = document.getElementById('absent-text-' + studentId);

        if (status === 'hadir') {
            card.classList.remove('is-absent');
            hafalanSection.style.display = 'grid';
            absentNote.style.display = 'none';
        } else {
            card.classList.add('is-absent');
            hafalanSection.style.display = 'none';
            absentNote.style.display = 'block';
            absentText.innerText = status.toUpperCase();
        }
    }

    // ================================================================
    // MODERN SURAH PICKER (FAST, INLINE, ZERO SCREEN TAKEOVER)
    // ================================================================
    function initSurahPickers() {
        document.querySelectorAll('.surah-picker-wrap').forEach(wrap => {
            const studentId = wrap.id.replace('surah-picker-wrap-', '');
            if (wrap.dataset.initialized === 'true') return;
            wrap.dataset.initialized = 'true';
            renderSurahOptionsList(studentId);
        });
    }

    function renderSurahOptionsList(studentId) {
        const selectEl = document.getElementById('surah-select-' + studentId);
        const optionsContainer = document.getElementById('surah-options-' + studentId);
        if (!selectEl || !optionsContainer) return;

        optionsContainer.innerHTML = '';

        const children = Array.from(selectEl.children);

        children.forEach(child => {
            if (child.tagName === 'OPTGROUP') {
                const groupTitle = document.createElement('div');
                groupTitle.className = 'surah-optgroup-title';
                groupTitle.innerHTML = child.label;
                optionsContainer.appendChild(groupTitle);

                Array.from(child.children).forEach(opt => {
                    appendOptionItem(optionsContainer, studentId, opt);
                });
            } else if (child.tagName === 'OPTION') {
                if (child.value !== '') {
                    appendOptionItem(optionsContainer, studentId, child);
                }
            }
        });

        const emptyNotice = document.createElement('div');
        emptyNotice.className = 'surah-empty-state';
        emptyNotice.style.display = 'none';
        emptyNotice.textContent = 'Surat tidak ditemukan';
        optionsContainer.appendChild(emptyNotice);
    }

    function appendOptionItem(container, studentId, opt) {
        const selectEl = document.getElementById('surah-select-' + studentId);
        const item = document.createElement('div');
        item.className = 'surah-option-item';
        item.dataset.value = opt.value;
        item.dataset.text = opt.text;

        const isSelected = (selectEl.value === opt.value);
        if (isSelected) item.classList.add('is-selected');

        let mainName = opt.text;
        let badgeHtml = '';
        const match = opt.text.match(/^(.*?)\s*\((Juz\s*\d+)\)$/i);
        if (match) {
            mainName = match[1];
            badgeHtml = `<span class="surah-item-badge">${match[2]}</span>`;
        }

        item.innerHTML = `
            <div style="display: flex; align-items: center; gap: 8px; min-width: 0; overflow: hidden;">
                <span class="surah-item-name">${mainName}</span>
                ${badgeHtml}
            </div>
            <i class="fas fa-check surah-item-check"></i>
        `;

        item.addEventListener('click', function(e) {
            e.stopPropagation();
            chooseSurah(studentId, opt.value, opt.text);
        });

        container.appendChild(item);
    }

    function toggleSurahPicker(studentId, e) {
        if (e) e.stopPropagation();
        const wrap = document.getElementById('surah-picker-wrap-' + studentId);
        const card = document.getElementById('card-student-' + studentId);
        const searchInput = document.getElementById('surah-search-' + studentId);

        if (!wrap) return;

        const willOpen = !wrap.classList.contains('is-open');

        closeAllSurahPickers();

        if (willOpen) {
            wrap.classList.add('is-open');
            if (card) card.classList.add('has-open-picker');
            if (searchInput) {
                searchInput.value = '';
                filterSurahOptions(studentId, '');
                setTimeout(() => searchInput.focus(), 60);
            }
            const optionsContainer = document.getElementById('surah-options-' + studentId);
            const selectedItem = optionsContainer?.querySelector('.surah-option-item.is-selected');
            if (selectedItem) {
                setTimeout(() => {
                    selectedItem.scrollIntoView({ block: 'nearest' });
                }, 50);
            }
        }
    }

    function chooseSurah(studentId, value, fullText) {
        const selectEl = document.getElementById('surah-select-' + studentId);
        const triggerText = document.getElementById('surah-trigger-text-' + studentId);
        const optionsContainer = document.getElementById('surah-options-' + studentId);

        if (selectEl) {
            selectEl.value = value;
            selectEl.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (triggerText) {
            triggerText.textContent = value || '-- Pilih Surat --';
            if (value) {
                triggerText.classList.remove('is-placeholder');
            } else {
                triggerText.classList.add('is-placeholder');
            }
        }

        if (optionsContainer) {
            optionsContainer.querySelectorAll('.surah-option-item').forEach(it => {
                if (it.dataset.value === value) {
                    it.classList.add('is-selected');
                } else {
                    it.classList.remove('is-selected');
                }
            });
        }

        closeAllSurahPickers();
    }

    function filterSurahOptions(studentId, query) {
        const optionsContainer = document.getElementById('surah-options-' + studentId);
        if (!optionsContainer) return;

        const cleanQ = query.toLowerCase().replace(/[^a-z0-9]/g, '');
        const items = optionsContainer.querySelectorAll('.surah-option-item');
        const titles = optionsContainer.querySelectorAll('.surah-optgroup-title');
        const emptyState = optionsContainer.querySelector('.surah-empty-state');

        let visibleCount = 0;

        items.forEach(item => {
            const itemText = (item.dataset.text || item.dataset.value || '').toLowerCase().replace(/[^a-z0-9]/g, '');
            if (!cleanQ || itemText.includes(cleanQ)) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        titles.forEach(title => {
            let next = title.nextElementSibling;
            let groupHasVisible = false;
            while (next && !next.classList.contains('surah-optgroup-title') && !next.classList.contains('surah-empty-state')) {
                if (next.style.display !== 'none') {
                    groupHasVisible = true;
                    break;
                }
                next = next.nextElementSibling;
            }
            title.style.display = groupHasVisible ? 'flex' : 'none';
        });

        if (emptyState) {
            emptyState.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
    }

    function closeAllSurahPickers() {
        document.querySelectorAll('.surah-picker-wrap.is-open').forEach(w => {
            w.classList.remove('is-open');
        });
        document.querySelectorAll('.student-entry-card.has-open-picker').forEach(c => {
            c.classList.remove('has-open-picker');
        });
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.surah-picker-wrap')) {
            closeAllSurahPickers();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllSurahPickers();
        }
    });

    // ================================================================
    // LOGIKA PENCARIAN & PEMILIH SANTRI SETORAN (INPUT SATU PER SATU)
    // ================================================================
    let currentSelectedStudentId = null;

    function toggleStudentDropdown(e) {
        if (e) e.stopPropagation();
        const wrap = document.getElementById('studentSearchDropdownWrap');
        if (!wrap) return;
        const willOpen = !wrap.classList.contains('is-open');
        wrap.classList.toggle('is-open', willOpen);
        if (willOpen) {
            const input = document.getElementById('studentSearchField');
            if (input) {
                setTimeout(() => input.focus(), 60);
            }
        }
    }

    function closeStudentDropdown() {
        const wrap = document.getElementById('studentSearchDropdownWrap');
        if (wrap) wrap.classList.remove('is-open');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#studentSearchDropdownWrap')) {
            closeStudentDropdown();
        }
    });

    function handleStudentSearchInput(query) {
        const cleanQ = query.toLowerCase().trim();
        const clearBtn = document.getElementById('clearSearchBtn');
        if (clearBtn) {
            clearBtn.style.display = cleanQ ? 'flex' : 'none';
        }
        applyDropdownFilters();
    }

    function clearStudentSearchInput() {
        const input = document.getElementById('studentSearchField');
        if (input) {
            input.value = '';
            handleStudentSearchInput('');
            input.focus();
        }
    }

    let currentFilterStatus = 'all';
    function filterStudentDropdown(status) {
        currentFilterStatus = status;
        document.querySelectorAll('.filter-pill').forEach(btn => btn.classList.remove('active'));
        const activeBtn = document.getElementById('filter-pill-' + status);
        if (activeBtn) activeBtn.classList.add('active');
        applyDropdownFilters();
    }

    function applyDropdownFilters() {
        const searchInput = document.getElementById('studentSearchField');
        const cleanQ = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const items = document.querySelectorAll('.dropdown-student-item');
        let visibleCount = 0;

        items.forEach(item => {
            const name = item.dataset.name || '';
            const cls = item.dataset.class || '';
            const status = item.dataset.status || '';

            const matchSearch = !cleanQ || name.includes(cleanQ) || cls.includes(cleanQ);
            let matchFilter = true;
            if (currentFilterStatus === 'belum') {
                matchFilter = (status === 'belum' || status === 'absen');
            } else if (currentFilterStatus === 'sudah') {
                matchFilter = (status === 'sudah');
            }

            if (matchSearch && matchFilter) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        const emptyNotice = document.getElementById('dropdownEmptyNotice');
        if (emptyNotice) {
            emptyNotice.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
    }

    // Memilih siswa: Memunculkan form input khusus anak tersebut
    function selectStudent(studentId) {
        currentSelectedStudentId = studentId;

        // Sembunyikan empty placeholder
        const placeholder = document.getElementById('emptySelectionPlaceholder');
        if (placeholder) placeholder.style.display = 'none';

        // Sembunyikan form anak lain
        document.querySelectorAll('.active-student-form-card').forEach(card => {
            card.style.display = 'none';
        });

        // Munculkan kotak form anak yang dipilih
        const targetWorkspace = document.getElementById('student-workspace-' + studentId);
        if (targetWorkspace) {
            targetWorkspace.style.display = 'block';
        }

        // Update tampilan trigger dropdown
        const triggerArea = document.getElementById('triggerDisplayArea');
        const dropdownItem = document.getElementById('dropdown-item-' + studentId);
        const studentName = dropdownItem?.querySelector('.item-name')?.innerText || 'Santri';
        const studentClass = dropdownItem?.querySelector('.item-meta')?.innerText || '';

        if (triggerArea) {
            triggerArea.innerHTML = `
                <div class="trigger-selected-wrap">
                    <span style="width: 28px; height: 28px; border-radius: 6px; background: #047857; color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800;">
                        <i class="fas fa-user-check"></i>
                    </span>
                    <div>
                        <div class="trigger-selected-name">${studentName}</div>
                        <div style="font-size: 0.72rem; color: #047857; font-weight: 600;">${studentClass} &bull; Form Aktif</div>
                    </div>
                </div>
            `;
        }

        // Highlight di daftar dropdown
        document.querySelectorAll('.dropdown-student-item').forEach(it => it.classList.remove('is-active'));
        if (dropdownItem) dropdownItem.classList.add('is-active');

        // Highlight di tabel roster
        document.querySelectorAll('.roster-table tr').forEach(r => r.classList.remove('is-selected-row'));
        const rosterRow = document.getElementById('roster-row-' + studentId);
        if (rosterRow) rosterRow.classList.add('is-selected-row');

        closeStudentDropdown();

        // Scroll halus ke kotak form yang muncul
        const formArea = document.getElementById('activeStudentFormArea');
        if (formArea) {
            const yOffset = -70;
            const y = formArea.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({ top: y, behavior: 'smooth' });
        }
    }

    // Menutup / Ganti Siswa
    function deselectStudent() {
        currentSelectedStudentId = null;

        const placeholder = document.getElementById('emptySelectionPlaceholder');
        if (placeholder) placeholder.style.display = 'block';

        document.querySelectorAll('.active-student-form-card').forEach(card => {
            card.style.display = 'none';
        });

        const triggerArea = document.getElementById('triggerDisplayArea');
        if (triggerArea) {
            triggerArea.innerHTML = `
                <i class="fas fa-user-graduate" style="color: #059669; font-size: 1.15rem; flex-shrink: 0;"></i>
                <span class="trigger-placeholder" id="triggerPlaceholderText">-- Klik di sini untuk Cari / Pilih Nama Siswa yang Setoran --</span>
            `;
        }

        document.querySelectorAll('.dropdown-student-item').forEach(it => it.classList.remove('is-active'));
        document.querySelectorAll('.roster-table tr').forEach(r => r.classList.remove('is-selected-row'));
    }

    // Inisialisasi AJAX Form Submission untuk Setiap Form Anak
    function initSingleStudentForms() {
        document.querySelectorAll('.single-student-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const studentId = form.dataset.studentId;
                const submitBtn = document.getElementById('btn-save-' + studentId);
                const feedbackBadge = document.getElementById('save-feedback-' + studentId);
                const origBtnHtml = submitBtn ? submitBtn.innerHTML : '';

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Menyimpan...</span>';
                }

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(data => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-check"></i> <span>Tersimpan!</span>';
                        setTimeout(() => {
                            submitBtn.innerHTML = origBtnHtml;
                        }, 2200);
                    }

                    if (feedbackBadge) {
                        const now = new Date();
                        const timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
                        feedbackBadge.className = 'save-feedback-badge success';
                        feedbackBadge.style.display = 'inline-flex';
                        feedbackBadge.innerHTML = `<i class="fas fa-check-circle"></i> Berhasil disimpan pukul ${timeStr}`;
                    }

                    handleSaveSuccessUi(studentId, data);

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.student_name + ' Berhasil Disimpan!',
                            text: 'Data hafalan & kehadiran langsung tersinkron.',
                            showConfirmButton: false,
                            timer: 2400,
                            timerProgressBar: true
                        });
                    }
                })
                .catch(err => {
                    console.error('AJAX Submit Error, fallbacking to POST:', err);
                    form.submit();
                });
            });
        });
    }

    function handleSaveSuccessUi(studentId, data) {
        if (data.kehadiran_count !== undefined) {
            const el = document.querySelector('.kpi-green-grid .kpi-green-card:nth-child(2) .kpi-green-value');
            if (el) el.innerHTML = `${data.kehadiran_count} <span class="kpi-green-unit">/ {{ $totalSiswa }} Hadir</span>`;
        }
        if (data.sudah_setor_count !== undefined) {
            const el = document.querySelector('.kpi-green-grid .kpi-green-card:nth-child(3) .kpi-green-value');
            if (el) el.innerHTML = `${data.sudah_setor_count} <span class="kpi-green-unit">Siswa</span>`;

            const rSetor = document.getElementById('roster-stat-setor');
            if (rSetor) rSetor.innerText = data.sudah_setor_count;
            const rBelum = document.getElementById('roster-stat-belum');
            if (rBelum) rBelum.innerText = Math.max(0, {{ $totalSiswa }} - data.sudah_setor_count);

            const pSudah = document.getElementById('count-pill-sudah');
            if (pSudah) pSudah.innerText = data.sudah_setor_count;
            const pBelum = document.getElementById('count-pill-belum');
            if (pBelum) pBelum.innerText = Math.max(0, {{ $totalSiswa }} - data.sudah_setor_count);

            const stCount = document.getElementById('sticky-setor-count');
            if (stCount) stCount.innerText = data.sudah_setor_count;
        }
        if (data.total_baris !== undefined) {
            const el = document.querySelector('.kpi-green-grid .kpi-green-card:nth-child(4) .kpi-green-value');
            if (el) el.innerHTML = `${data.total_baris.toLocaleString('id-ID')} <span class="kpi-green-unit">Baris</span>`;
        }

        const dropdownItem = document.getElementById('dropdown-item-' + studentId);
        const dropdownStatus = document.getElementById('dropdown-status-' + studentId);
        const hasSetor = data.status === 'hadir' && data.capaian;

        if (dropdownItem) {
            dropdownItem.dataset.status = hasSetor ? 'sudah' : (data.status !== 'hadir' ? 'absen' : 'belum');
        }

        if (dropdownStatus) {
            if (hasSetor) {
                dropdownStatus.innerHTML = `<span class="badge-status-sudah"><i class="fas fa-check-circle"></i> Sudah: ${data.capaian.substring(0, 18)}</span>`;
            } else if (data.status !== 'hadir') {
                dropdownStatus.innerHTML = `<span class="badge-status-absen">${data.status.toUpperCase()}</span>`;
            } else {
                dropdownStatus.innerHTML = `<span class="badge-status-belum"><i class="far fa-clock"></i> Belum Setor</span>`;
            }
        }

        const rosterTd = document.getElementById('roster-status-td-' + studentId);
        const rosterBtn = document.getElementById('roster-btn-' + studentId);

        if (rosterTd) {
            if (hasSetor) {
                const nilaiPart = data.nilai ? ` (Nilai: ${data.nilai})` : '';
                rosterTd.innerHTML = `<span class="badge-status-sudah"><i class="fas fa-check-circle"></i> ${data.capaian}${nilaiPart}</span>`;
            } else if (data.status !== 'hadir') {
                rosterTd.innerHTML = `<span class="badge-status-absen">${data.status.toUpperCase()}</span>`;
            } else {
                rosterTd.innerHTML = `<span class="badge-status-belum"><i class="far fa-clock"></i> Belum Setor</span>`;
            }
        }

        if (rosterBtn) {
            if (hasSetor) {
                rosterBtn.className = 'btn-action-select is-edit';
                rosterBtn.innerHTML = '<i class="fas fa-edit"></i> <span>Ubah</span>';
            } else {
                rosterBtn.className = 'btn-action-select';
                rosterBtn.innerHTML = '<i class="fas fa-plus-circle"></i> <span>Input</span>';
            }
        }

        if (data.whatsapp_message) {
            const ta = document.getElementById('waMessageTextarea');
            if (ta) ta.value = data.whatsapp_message;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        initSingleStudentForms();
        @if(!empty($selectedStudentId))
            selectStudent('{{ $selectedStudentId }}');
        @endif
        initSurahPickers();
    });

    // Pilih nama surah pada dropdown dan set ayat dari riwayat sebelumnya
    function selectStudentSurah(studentId, surahName, ayat) {
        const select = document.getElementById('surah-select-' + studentId);
        const ayatInput = document.getElementById('ayat-input-' + studentId);

        if (select) {
            const cleanTarget = surahName.toLowerCase().replace(/[^a-z0-9]/g, '');
            let found = false;
            for (let i = 0; i < select.options.length; i++) {
                const optVal = select.options[i].value.toLowerCase().replace(/[^a-z0-9]/g, '');
                if (optVal && (optVal === cleanTarget || optVal.includes(cleanTarget) || cleanTarget.includes(optVal))) {
                    select.selectedIndex = i;
                    chooseSurah(studentId, select.options[i].value, select.options[i].text);
                    found = true;
                    break;
                }
            }
            if (!found) {
                const opt = document.createElement('option');
                opt.value = surahName;
                opt.text = surahName;
                opt.selected = true;
                select.appendChild(opt);
                renderSurahOptionsList(studentId);
                chooseSurah(studentId, surahName, surahName);
            }
        }

        if (ayatInput && ayat) {
            ayatInput.value = ayat;
            ayatInput.focus();
        }
    }

    // Modal WhatsApp
    function openWaModal() {
        document.getElementById('waModal').classList.add('open');
    }
    function closeWaModal() {
        document.getElementById('waModal').classList.remove('open');
    }

    // Salin teks laporan WA dengan feedback interaktif dan toast
    function copyWaText(btn) {
        const textarea = document.getElementById('waMessageTextarea');
        const text = textarea ? textarea.value : '';
        if (!text) return;

        const notifySuccess = () => {
            if (btn) {
                const origHtml = btn.innerHTML;
                btn.classList.add('copied');
                btn.innerHTML = '<i class="fas fa-check"></i> <span>Tersalin!</span>';
                setTimeout(() => {
                    btn.classList.remove('copied');
                    btn.innerHTML = origHtml;
                }, 2000);
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Laporan Berhasil Disalin!',
                    text: 'Silakan tempel (paste) ke WhatsApp.',
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true
                });
            }
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(notifySuccess).catch(() => {
                fallbackCopyText(text, notifySuccess);
            });
        } else {
            fallbackCopyText(text, notifySuccess);
        }
    }

    function fallbackCopyText(text, callback) {
        const temp = document.createElement('textarea');
        temp.value = text;
        temp.style.position = 'fixed';
        temp.style.left = '-9999px';
        temp.style.top = '0';
        document.body.appendChild(temp);
        temp.focus();
        temp.select();
        try {
            document.execCommand('copy');
            if (callback) callback();
        } catch (e) {
            prompt('Salin teks laporan WhatsApp berikut:', text);
        }
        document.body.removeChild(temp);
    }

    // Modal Daftarkan Munaqosah Cepat dari Ruang Halaqah
    function openQuickMunaqosahModal(studentId, studentName, studentClass, currentLevel) {
        document.getElementById('quick_munaqosah_student_id').value = studentId;
        document.getElementById('quick_munaqosah_student_name').textContent = studentName;
        document.getElementById('quick_munaqosah_student_class').textContent = 'Kelas ' + (studentClass || '-');
        
        const curLevel = currentLevel || '{{ $activeGroup->target_level ?? "Juz 30" }}';
        document.getElementById('quick_munaqosah_current_level').value = curLevel;

        // Auto suggest next target level
        let nextLevel = 'Juz 29';
        const curLower = curLevel.toLowerCase();
        if (curLower.includes('30')) nextLevel = 'Juz 29';
        else if (curLower.includes('29')) nextLevel = 'Juz 28';
        else if (curLower.includes('28')) nextLevel = 'Juz 27';
        else if (curLower.includes('27')) nextLevel = 'Juz 26';
        else if (curLower.includes('26')) nextLevel = 'Juz 25';
        else if (curLower.includes('jilid 1')) nextLevel = 'Jilid 2';
        else if (curLower.includes('jilid 2')) nextLevel = 'Jilid 3';
        else if (curLower.includes('jilid 3')) nextLevel = 'Jilid 4';
        else if (curLower.includes('jilid 4')) nextLevel = 'Jilid 5';
        else if (curLower.includes('jilid 5')) nextLevel = 'Al-Qur\'an';

        document.getElementById('quick_munaqosah_target_level').value = nextLevel;

        document.getElementById('quickMunaqosahModal').style.display = 'flex';
    }

    function closeQuickMunaqosahModal() {
        document.getElementById('quickMunaqosahModal').style.display = 'none';
    }

    function copyMunaqosahWa(msg) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(msg).then(() => {
                alert('✅ Pesan jadwal munaqosah berhasil disalin! Silakan paste/tempel ke WhatsApp orang tua.');
            }).catch(() => {
                prompt('Salin pesan pengumuman munaqosah berikut:', msg);
            });
        } else {
            prompt('Salin pesan pengumuman munaqosah berikut:', msg);
        }
    }

    @if($openShare)
    // ================================================================
    // LOGIKA PENCARIAN & PEMILIH SANTRI SETORAN (INPUT SATU PER SATU)
    // ================================================================
    let currentSelectedStudentId = null;

    function toggleStudentDropdown(e) {
        if (e) e.stopPropagation();
        const wrap = document.getElementById('studentSearchDropdownWrap');
        if (!wrap) return;
        const willOpen = !wrap.classList.contains('is-open');
        wrap.classList.toggle('is-open', willOpen);
        if (willOpen) {
            const input = document.getElementById('studentSearchField');
            if (input) {
                setTimeout(() => input.focus(), 60);
            }
        }
    }

    function closeStudentDropdown() {
        const wrap = document.getElementById('studentSearchDropdownWrap');
        if (wrap) wrap.classList.remove('is-open');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#studentSearchDropdownWrap')) {
            closeStudentDropdown();
        }
    });

    function handleStudentSearchInput(query) {
        const cleanQ = query.toLowerCase().trim();
        const clearBtn = document.getElementById('clearSearchBtn');
        if (clearBtn) {
            clearBtn.style.display = cleanQ ? 'flex' : 'none';
        }
        applyDropdownFilters();
    }

    function clearStudentSearchInput() {
        const input = document.getElementById('studentSearchField');
        if (input) {
            input.value = '';
            handleStudentSearchInput('');
            input.focus();
        }
    }

    let currentFilterStatus = 'all';
    function filterStudentDropdown(status) {
        currentFilterStatus = status;
        document.querySelectorAll('.filter-pill').forEach(btn => btn.classList.remove('active'));
        const activeBtn = document.getElementById('filter-pill-' + status);
        if (activeBtn) activeBtn.classList.add('active');
        applyDropdownFilters();
    }

    function applyDropdownFilters() {
        const searchInput = document.getElementById('studentSearchField');
        const cleanQ = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const items = document.querySelectorAll('.dropdown-student-item');
        let visibleCount = 0;

        items.forEach(item => {
            const name = item.dataset.name || '';
            const cls = item.dataset.class || '';
            const status = item.dataset.status || '';

            const matchSearch = !cleanQ || name.includes(cleanQ) || cls.includes(cleanQ);
            let matchFilter = true;
            if (currentFilterStatus === 'belum') {
                matchFilter = (status === 'belum' || status === 'absen');
            } else if (currentFilterStatus === 'sudah') {
                matchFilter = (status === 'sudah');
            }

            if (matchSearch && matchFilter) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        const emptyNotice = document.getElementById('dropdownEmptyNotice');
        if (emptyNotice) {
            emptyNotice.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
    }

    // Memilih siswa: Memunculkan form input khusus anak tersebut
    function selectStudent(studentId) {
        currentSelectedStudentId = studentId;

        // Sembunyikan empty placeholder
        const placeholder = document.getElementById('emptySelectionPlaceholder');
        if (placeholder) placeholder.style.display = 'none';

        // Sembunyikan form anak lain
        document.querySelectorAll('.active-student-form-card').forEach(card => {
            card.style.display = 'none';
        });

        // Munculkan kotak form anak yang dipilih
        const targetWorkspace = document.getElementById('student-workspace-' + studentId);
        if (targetWorkspace) {
            targetWorkspace.style.display = 'block';
        }

        // Update tampilan trigger dropdown
        const triggerArea = document.getElementById('triggerDisplayArea');
        const dropdownItem = document.getElementById('dropdown-item-' + studentId);
        const studentName = dropdownItem?.querySelector('.item-name')?.innerText || 'Santri';
        const studentClass = dropdownItem?.querySelector('.item-meta')?.innerText || '';

        if (triggerArea) {
            triggerArea.innerHTML = `
                <div class="trigger-selected-wrap">
                    <span style="width: 28px; height: 28px; border-radius: 6px; background: #047857; color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800;">
                        <i class="fas fa-user-check"></i>
                    </span>
                    <div>
                        <div class="trigger-selected-name">${studentName}</div>
                        <div style="font-size: 0.72rem; color: #047857; font-weight: 600;">${studentClass} &bull; Form Aktif</div>
                    </div>
                </div>
            `;
        }

        // Highlight di daftar dropdown
        document.querySelectorAll('.dropdown-student-item').forEach(it => it.classList.remove('is-active'));
        if (dropdownItem) dropdownItem.classList.add('is-active');

        // Highlight di tabel roster
        document.querySelectorAll('.roster-table tr').forEach(r => r.classList.remove('is-selected-row'));
        const rosterRow = document.getElementById('roster-row-' + studentId);
        if (rosterRow) rosterRow.classList.add('is-selected-row');

        closeStudentDropdown();

        // Scroll halus ke kotak form yang muncul
        const formArea = document.getElementById('activeStudentFormArea');
        if (formArea) {
            const yOffset = -70;
            const y = formArea.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({ top: y, behavior: 'smooth' });
        }
    }

    // Menutup / Ganti Siswa
    function deselectStudent() {
        currentSelectedStudentId = null;

        const placeholder = document.getElementById('emptySelectionPlaceholder');
        if (placeholder) placeholder.style.display = 'block';

        document.querySelectorAll('.active-student-form-card').forEach(card => {
            card.style.display = 'none';
        });

        const triggerArea = document.getElementById('triggerDisplayArea');
        if (triggerArea) {
            triggerArea.innerHTML = `
                <i class="fas fa-user-graduate" style="color: #059669; font-size: 1.15rem; flex-shrink: 0;"></i>
                <span class="trigger-placeholder" id="triggerPlaceholderText">-- Klik di sini untuk Cari / Pilih Nama Siswa yang Setoran --</span>
            `;
        }

        document.querySelectorAll('.dropdown-student-item').forEach(it => it.classList.remove('is-active'));
        document.querySelectorAll('.roster-table tr').forEach(r => r.classList.remove('is-selected-row'));
    }

    // Inisialisasi AJAX Form Submission untuk Setiap Form Anak
    function initSingleStudentForms() {
        document.querySelectorAll('.single-student-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const studentId = form.dataset.studentId;
                const submitBtn = document.getElementById('btn-save-' + studentId);
                const feedbackBadge = document.getElementById('save-feedback-' + studentId);
                const origBtnHtml = submitBtn ? submitBtn.innerHTML : '';

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Menyimpan...</span>';
                }

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(data => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-check"></i> <span>Tersimpan!</span>';
                        setTimeout(() => {
                            submitBtn.innerHTML = origBtnHtml;
                        }, 2200);
                    }

                    if (feedbackBadge) {
                        const now = new Date();
                        const timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
                        feedbackBadge.className = 'save-feedback-badge success';
                        feedbackBadge.style.display = 'inline-flex';
                        feedbackBadge.innerHTML = `<i class="fas fa-check-circle"></i> Berhasil disimpan pukul ${timeStr}`;
                    }

                    handleSaveSuccessUi(studentId, data);

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.student_name + ' Berhasil Disimpan!',
                            text: 'Data hafalan & kehadiran langsung tersinkron.',
                            showConfirmButton: false,
                            timer: 2400,
                            timerProgressBar: true
                        });
                    }
                })
                .catch(err => {
                    console.error('AJAX Submit Error, fallbacking to POST:', err);
                    form.submit();
                });
            });
        });
    }

    function handleSaveSuccessUi(studentId, data) {
        if (data.kehadiran_count !== undefined) {
            const el = document.querySelector('.kpi-green-grid .kpi-green-card:nth-child(2) .kpi-green-value');
            if (el) el.innerHTML = `${data.kehadiran_count} <span class="kpi-green-unit">/ {{ $totalSiswa }} Hadir</span>`;
        }
        if (data.sudah_setor_count !== undefined) {
            const el = document.querySelector('.kpi-green-grid .kpi-green-card:nth-child(3) .kpi-green-value');
            if (el) el.innerHTML = `${data.sudah_setor_count} <span class="kpi-green-unit">Siswa</span>`;

            const rSetor = document.getElementById('roster-stat-setor');
            if (rSetor) rSetor.innerText = data.sudah_setor_count;
            const rBelum = document.getElementById('roster-stat-belum');
            if (rBelum) rBelum.innerText = Math.max(0, {{ $totalSiswa }} - data.sudah_setor_count);

            const pSudah = document.getElementById('count-pill-sudah');
            if (pSudah) pSudah.innerText = data.sudah_setor_count;
            const pBelum = document.getElementById('count-pill-belum');
            if (pBelum) pBelum.innerText = Math.max(0, {{ $totalSiswa }} - data.sudah_setor_count);

            const stCount = document.getElementById('sticky-setor-count');
            if (stCount) stCount.innerText = data.sudah_setor_count;
        }
        if (data.total_baris !== undefined) {
            const el = document.querySelector('.kpi-green-grid .kpi-green-card:nth-child(4) .kpi-green-value');
            if (el) el.innerHTML = `${data.total_baris.toLocaleString('id-ID')} <span class="kpi-green-unit">Baris</span>`;
        }

        const dropdownItem = document.getElementById('dropdown-item-' + studentId);
        const dropdownStatus = document.getElementById('dropdown-status-' + studentId);
        const hasSetor = data.status === 'hadir' && data.capaian;

        if (dropdownItem) {
            dropdownItem.dataset.status = hasSetor ? 'sudah' : (data.status !== 'hadir' ? 'absen' : 'belum');
        }

        if (dropdownStatus) {
            if (hasSetor) {
                dropdownStatus.innerHTML = `<span class="badge-status-sudah"><i class="fas fa-check-circle"></i> Sudah: ${data.capaian.substring(0, 18)}</span>`;
            } else if (data.status !== 'hadir') {
                dropdownStatus.innerHTML = `<span class="badge-status-absen">${data.status.toUpperCase()}</span>`;
            } else {
                dropdownStatus.innerHTML = `<span class="badge-status-belum"><i class="far fa-clock"></i> Belum Setor</span>`;
            }
        }

        const rosterTd = document.getElementById('roster-status-td-' + studentId);
        const rosterBtn = document.getElementById('roster-btn-' + studentId);

        if (rosterTd) {
            if (hasSetor) {
                const nilaiPart = data.nilai ? ` (Nilai: ${data.nilai})` : '';
                rosterTd.innerHTML = `<span class="badge-status-sudah"><i class="fas fa-check-circle"></i> ${data.capaian}${nilaiPart}</span>`;
            } else if (data.status !== 'hadir') {
                rosterTd.innerHTML = `<span class="badge-status-absen">${data.status.toUpperCase()}</span>`;
            } else {
                rosterTd.innerHTML = `<span class="badge-status-belum"><i class="far fa-clock"></i> Belum Setor</span>`;
            }
        }

        if (rosterBtn) {
            if (hasSetor) {
                rosterBtn.className = 'btn-action-select is-edit';
                rosterBtn.innerHTML = '<i class="fas fa-edit"></i> <span>Ubah</span>';
            } else {
                rosterBtn.className = 'btn-action-select';
                rosterBtn.innerHTML = '<i class="fas fa-plus-circle"></i> <span>Input</span>';
            }
        }

        if (data.whatsapp_message) {
            const ta = document.getElementById('waMessageTextarea');
            if (ta) ta.value = data.whatsapp_message;
        }
    }

        document.addEventListener('DOMContentLoaded', function() {
        initSingleStudentForms();
        @if(!empty($selectedStudentId))
            selectStudent('{{ $selectedStudentId }}');
        @endif
            openWaModal();
        });
    @endif
</script>

<!-- Modal Pop-up Daftarkan Munaqosah Cepat dari Ruang Halaqah -->
<div id="quickMunaqosahModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 520px; padding: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); max-height: 90vh; overflow-y: auto;">
        
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: #047857; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">Daftarkan Munaqosah</h3>
                    <p style="margin: 2px 0 0 0; color: #64748b; font-size: 0.8rem;">Daftarkan siswa tuntas langsung ke penguji</p>
                </div>
            </div>
            <button type="button" onclick="closeQuickMunaqosahModal()" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer; padding: 4px;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('munaqosah.register.store') }}">
            @csrf
            <input type="hidden" name="redirect_back" value="1">
            <input type="hidden" name="student_id" id="quick_munaqosah_student_id">
            <input type="hidden" name="type" value="tahfidz">
            <input type="hidden" name="from_group_id" value="{{ $activeGroup->id }}">
            <input type="hidden" name="proposer_name" value="{{ Auth::user()->name }}">

            <!-- Info Siswa & Kelompok -->
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 12px 14px; margin-bottom: 16px;">
                <div style="font-weight: 800; color: #0f172a; font-size: 0.95rem;" id="quick_munaqosah_student_name">
                    Nama Siswa
                </div>
                <div style="font-size: 0.78rem; color: #64748b; margin-top: 3px; display: flex; gap: 10px; align-items: center;">
                    <span id="quick_munaqosah_student_class">Kelas -</span>
                    <span>•</span>
                    <span>Kelompok: <strong>{{ $activeGroup->name }}</strong></span>
                </div>
            </div>

            <!-- Jenjang Munaqosah -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">
                        Jenjang Diuji <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="current_level" id="quick_munaqosah_current_level" required 
                           value="{{ $activeGroup->target_level ?? 'Juz 30' }}"
                           style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; font-weight: 700; background: #fff;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">
                        Target Lanjutan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="target_level" id="quick_munaqosah_target_level" required 
                           value="Juz 29"
                           style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; font-weight: 700; background: #fff;">
                </div>
            </div>

            <!-- Dropdown Pilihan Jadwal & Penguji Munaqisy -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                    <i class="far fa-calendar-check" style="color: #0284c7;"></i> Pilih Jadwal & Penguji / Munaqisy Tersedia
                </label>
                <select name="schedule_id" class="form-control-pro" style="width: 100%;">
                    <option value="">-- Tanpa Jadwal (Jadwalkan Nanti oleh Panitia) --</option>
                    @foreach($munaqosahSchedules as $sch)
                        @php
                            $isFull = $sch->is_full;
                            $exName = $sch->examiner?->user?->name ?? 'Penguji';
                            $slotTxt = "{$exName} • {$sch->day_name} ({$sch->time_start} - {$sch->time_end} WIB)";
                            if ($sch->room_location) {
                                $slotTxt .= " · {$sch->room_location}";
                            }
                        @endphp
                        <option value="{{ $sch->id }}" {{ $isFull ? 'disabled' : '' }}>
                            {{ $slotTxt }} {{ $isFull ? '[SUDAH TERPENUHI ✓]' : "(Sisa: " . ($sch->quota - $sch->used_slots) . " kuota)" }}
                        </option>
                    @endforeach
                </select>
                <span style="font-size: 0.74rem; color: #64748b; margin-top: 4px; display: block;">
                    Jadwal ini diatur oleh Admin & Kurikulum. Jika dipilih, siswa langsung otomatis terplot ke penguji tersebut.
                </span>
            </div>

            <!-- Catatan Pengusul (Opsional) -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">
                    Catatan Guru Pembimbing (Opsional)
                </label>
                <textarea name="proposer_notes" rows="2" placeholder="Contoh: Siswa sudah menyelesaikan tasmi' mandiri 1 juz lancar..." style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.84rem;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                <button type="button" onclick="closeQuickMunaqosahModal()" style="padding: 10px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 22px; border-radius: 10px; border: none; background: #047857; color: white; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(4, 120, 87, 0.3); display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-check-circle"></i> Konfirmasi & Daftarkan Langsung
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
