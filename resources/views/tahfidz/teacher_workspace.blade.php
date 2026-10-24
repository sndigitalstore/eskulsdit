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
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }
    .btn-recap-wa {
        background: #16a34a;
        color: white;
        border: none;
        padding: 7px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
        transition: all 0.15s ease;
    }
    .btn-recap-wa:hover {
        background: #15803d;
        color: white;
    }
    .btn-recap-copy {
        background: #0284c7;
        color: white;
        border: none;
        padding: 7px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
        transition: all 0.15s ease;
    }
    .btn-recap-copy:hover {
        background: #0369a1;
        color: white;
    }
    .btn-recap-edit {
        background: #475569;
        color: white;
        border: none;
        padding: 7px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.82rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(71, 85, 105, 0.25);
        transition: all 0.15s ease;
    }
    .btn-recap-edit:hover {
        background: #334155;
        color: white;
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
            width: 100%;
        }
        .btn-recap-wa, .btn-recap-copy, .btn-recap-edit {
            flex: 1;
            justify-content: center;
            padding: 8px 10px;
            font-size: 0.8rem;
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

    {{-- 4. BANNER SUKSES SIMPAN & KIRIM LAPORAN WA --}}
    @if(session('success') || $openShare)
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
                <button type="button" onclick="copyWaText()" 
                        style="background: white; border: 1.5px solid #86efac; color: #166534; padding: 7px 12px; border-radius: 8px; font-weight: 700; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fas fa-copy"></i> Salin
                </button>
            </div>
        </div>
    @endif

    {{-- 5. KONTEN RUANG HALAQAH (JIKA SESI SUDAH DISIMPAN VS FORM INPUT BARU) --}}
    @if($isSessionSaved && !$isEditMode)
        {{-- TAMPILAN SESI SUDAH SELESAI DIINPUT & DISIMPAN --}}
        <div class="completion-card">
            <div class="completion-hero">
                <div>
                    <div class="completion-badge">
                        <i class="fas fa-check-circle"></i> Sesi Selesai Diinput & Tersimpan
                    </div>
                    <h2 class="completion-title">
                        Sesi {{ \Carbon\Carbon::parse($sessionDate)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </h2>
                    <p class="completion-subtitle">
                        Alhamdulillah, seluruh data absensi & setoran hafalan telah tersimpan. Data siap dibagikan ke orang tua siswa.
                    </p>
                </div>
                <div class="completion-actions">
                    <button type="button" onclick="openWaModal()" class="btn-recap-wa" title="Bagikan Laporan WhatsApp">
                        <i class="fab fa-whatsapp"></i> <span>Share WA</span>
                    </button>
                    <button type="button" onclick="copyWaText()" class="btn-recap-copy" title="Salin Teks Laporan">
                        <i class="fas fa-copy"></i> <span>Salin Laporan</span>
                    </button>
                    <a href="{{ route('tahfidz-groups.index', ['group_id' => $activeGroup->id, 'date' => $sessionDate, 'edit' => 1]) }}" class="btn-recap-edit" title="Ubah Data Sesi">
                        <i class="fas fa-edit"></i> <span>Ubah</span>
                    </a>
                </div>
            </div>

            <!-- Petunjuk Ganti Tanggal -->
            <div style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <span style="font-size: 0.82rem; color: #64748b;">
                    💡 <strong>Ingin menginput sesi berikutnya?</strong> Cukup ganti tanggal pada pemilih tanggal di atas. Form siswa otomatis siap diinput untuk tanggal baru.
                </span>
                <a href="{{ route('tahfidz-groups.index', ['group_id' => $activeGroup->id, 'date' => $sessionDate, 'edit' => 1]) }}" 
                   style="font-size: 0.82rem; font-weight: 700; color: #047857; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fas fa-edit"></i> <span>Ubah Data Sesi</span> &rarr;
                </a>
            </div>
        </div>

    @else
        {{-- FORM INPUT ABSENSI & CAPAIAN HARIAN --}}
        @if($isEditMode)
            <div style="background: #eff6ff; border: 1.5px solid #93c5fd; border-radius: 12px; padding: 12px 16px; margin-bottom: 14px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-edit" style="color: #2563eb; font-size: 1.1rem;"></i>
                    <div>
                        <strong style="color: #1e40af; font-size: 0.88rem;">Mode Koreksi Sesi Tanggal {{ \Carbon\Carbon::parse($sessionDate)->locale('id')->isoFormat('D MMMM Y') }}</strong>
                        <p style="margin: 1px 0 0 0; color: #3b82f6; font-size: 0.78rem;">Silakan ubah data yang diperlukan, lalu klik "Simpan Sesi" di bawah.</p>
                    </div>
                </div>
                <a href="{{ route('tahfidz-groups.index', ['group_id' => $activeGroup->id, 'date' => $sessionDate]) }}" 
                   style="background: white; border: 1px solid #93c5fd; color: #1e40af; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 0.8rem; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fas fa-times"></i> Batal / Kembali ke Ringkasan
                </a>
            </div>
        @endif

        <form method="POST" action="{{ route('tahfidz.attendance.store', ['group' => $activeGroup->id]) }}" id="mainHalaqahForm">
            @csrf
            <input type="hidden" name="session_date" value="{{ $sessionDate }}">
            <input type="hidden" name="academic_year_id" value="{{ $activeGroup->academic_year_id ?? $activeYear?->id }}">
            <input type="hidden" name="redirect_to" value="workspace">

            {{-- DAFTAR SISWA --}}
            @forelse($activeGroup->students as $idx => $std)
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

                <div class="student-entry-card {{ $currentStatus !== 'hadir' ? 'is-absent' : '' }}" id="card-student-{{ $std->id }}">
                    
                    {{-- BARIS ATAS: IDENTITAS + QUICK ACTIONS --}}
                    <div class="student-card-header-row">
                        <div class="student-info-flex">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="student-avatar-num">
                                    {{ $idx + 1 }}
                                </div>
                                <div>
                                    <div class="student-fullname">
                                        {{ $std->name }}
                                    </div>
                                    <div class="student-meta-row">
                                        <span style="background: #f1f5f9; padding: 1px 6px; border-radius: 4px; font-weight: 700; color: #334155;">
                                            Kelas {{ $std->class }}
                                        </span>
                                        @php
                                            $prog = $studentTargetProgress[$std->id] ?? null;
                                            $isTuntas = $prog && ($prog['is_completed'] || $prog['percent'] >= 100);
                                        @endphp
                                        @if($isTuntas)
                                            <span style="background: #fef3c7; color: #92400e; border: 1.5px solid #fde68a; font-weight: 800; font-size: 0.72rem; padding: 1px 7px; border-radius: 4px; display: inline-flex; align-items: center; gap: 3px;">
                                                <i class="fas fa-star" style="color: #f59e0b;"></i> Target 100% Tuntas ⭐
                                            </span>
                                        @elseif($prog && $prog['percent'] > 0)
                                            <span style="background: #f0fdf4; color: #166534; font-size: 0.72rem; font-weight: 700; padding: 1px 6px; border-radius: 4px;">
                                                Target: {{ $prog['completed'] }}/{{ $prog['total'] }} ({{ $prog['percent'] }}%)
                                            </span>
                                        @endif
                                        @if($lastRec)
                                            <span style="color: #047857; font-weight: 600;">
                                                📌 Lalu: <strong>{{ $lastRec->surah_name }}</strong>{{ $lastRec->start_ayah ? " ({$lastRec->start_ayah}-{$lastRec->end_ayah})" : '' }}
                                            </span>
                                            <button type="button" 
                                                    onclick="selectStudentSurah('{{ $std->id }}', '{{ addslashes($lastRec->surah_name) }}', '{{ $lastRec->start_ayah ? $lastRec->start_ayah . ($lastRec->end_ayah && $lastRec->end_ayah != $lastRec->start_ayah ? '-' . $lastRec->end_ayah : '') : '' }}')"
                                                    style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; border-radius: 4px; padding: 1px 5px; font-size: 0.68rem; font-weight: 700; cursor: pointer;">
                                                Gunakan
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- QUICK BUTTONS: KARTU, WA ORTU, & DAFTAR MUNAQOSAH --}}
                            <div style="display: flex; align-items: center; gap: 5px;">
                                @if(isset($munaqosahRegistrations[$std->id]))
                                    <span style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; padding: 4px 8px; border-radius: 7px; font-size: 0.72rem; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;" title="Terdaftar Munaqosah: {{ $munaqosahRegistrations[$std->id]->exam_time ?? 'Menunggu Jadwal' }}">
                                        <i class="fas fa-award"></i> <span style="display: none; @media(min-width: 600px){display: inline;}">Munaqosah</span>
                                    </span>
                                @elseif($isTuntas)
                                    <button type="button" 
                                            onclick="openQuickMunaqosahModal({{ $std->id }}, '{{ addslashes($std->name) }}', '{{ $std->class }}')"
                                            style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; padding: 4px 10px; border-radius: 7px; font-size: 0.72rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(217, 119, 6, 0.35);"
                                            title="Target 100% Tuntas! Klik untuk Daftarkan Munaqosah">
                                        <i class="fas fa-star" style="color: #fef08a;"></i> <span style="display: none; @media(min-width: 600px){display: inline;}">⭐ Tuntas (Daftar Munaqosah)</span>
                                    </button>
                                @else
                                    <button type="button" 
                                            onclick="openQuickMunaqosahModal({{ $std->id }}, '{{ addslashes($std->name) }}', '{{ $std->class }}')"
                                            style="background: #f0fdf4; border: 1.5px solid #86efac; color: #047857; padding: 4px 8px; border-radius: 7px; font-size: 0.72rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;"
                                            title="Daftarkan Siswa ini ke Ujian Munaqosah Langsung">
                                        <i class="fas fa-award" style="color: #059669;"></i> <span style="display: none; @media(min-width: 600px){display: inline;}">Daftar Munaqosah</span>
                                    </button>
                                @endif

                                <a href="{{ route('tahfidz.student-card', ['group' => $activeGroup->id, 'student' => $std->id]) }}" 
                                   target="_blank" title="Buka Kartu Hafalan"
                                   style="background: #f8fafc; border: 1px solid #cbd5e1; color: #475569; padding: 5px 8px; border-radius: 7px; text-decoration: none; font-size: 0.74rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fas fa-book-reader" style="color: #047857;"></i>
                                    <span style="display: none; @media(min-width: 600px){display: inline;}">Kartu</span>
                                </a>

                                @if($cleanPhone)
                                    <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}" target="_blank" title="Chat Ortu"
                                       style="background: #ecfdf5; border: 1px solid #86efac; color: #166534; padding: 5px 8px; border-radius: 7px; text-decoration: none; font-size: 0.74rem; font-weight: 700; display: inline-flex; align-items: center;">
                                        <i class="fab fa-whatsapp" style="color: #25D366; font-size: 0.85rem;"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- PILIHAN KEHADIRAN (SEGMENTED CONTROL 1-TAP) --}}
                        <div class="att-segmented-container">
                            <label class="att-segmented-item">
                                <input type="radio" name="attendance[{{ $std->id }}][status]" value="hadir" 
                                       class="att-pill-radio" {{ $currentStatus === 'hadir' ? 'checked' : '' }}
                                       onchange="toggleAttendanceStatus('{{ $std->id }}', 'hadir')">
                                <span class="att-segmented-btn att-pill-hadir">✓ Hadir</span>
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

                    {{-- BARIS BAWAH: INPUT CAPAIAN HAFALAN (HANYA AKTIF JIKA HADIR) --}}
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
                                    
                                    {{-- TARGET JUZ KELOMPOK DARI ADMIN (MUNCUL PALING ATAS) --}}
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

                                    {{-- JUZ LAINNYA --}}
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

                                <!-- Modern Trigger Box (Tidak menutupi layar HP) -->
                                <div class="surah-picker-trigger" 
                                     id="surah-trigger-{{ $std->id }}"
                                     onclick="toggleSurahPicker('{{ $std->id }}', event)"
                                     role="combobox" aria-expanded="false" tabindex="0">
                                    <div class="surah-trigger-left">
                                        <span class="surah-trigger-icon">📖</span>
                                        <span class="surah-trigger-text {{ empty($selectedSurah) ? 'is-placeholder' : '' }}" 
                                              id="surah-trigger-text-{{ $std->id }}">
                                            {{ $selectedSurah ?: '-- Pilih Surat --' }}
                                        </span>
                                    </div>
                                    <div class="surah-trigger-arrow">
                                        <i class="fas fa-chevron-down surah-chevron"></i>
                                    </div>
                                </div>

                                <!-- Modern Popover Dropdown with Fast Search -->
                                <div class="surah-picker-popover" id="surah-popover-{{ $std->id }}" onclick="event.stopPropagation()">
                                    <div class="surah-search-box">
                                        <i class="fas fa-search surah-search-icon"></i>
                                        <input type="text" 
                                               class="surah-search-input" 
                                               id="surah-search-{{ $std->id }}"
                                               placeholder="Ketik cari surat..."
                                               oninput="filterSurahOptions('{{ $std->id }}', this.value)"
                                               autocomplete="off">
                                    </div>
                                    <div class="surah-options-list" id="surah-options-{{ $std->id }}">
                                        <!-- Diisi secara otomatis oleh initSurahPickers() -->
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

                        {{-- 3. NILAI SETORAN --}}
                        <div class="hafalan-col-nilai">
                            <label class="hafalan-field-label">⭐ Nilai</label>
                            <input type="text" name="attendance[{{ $std->id }}][nilai]" 
                                   class="input-clean" 
                                   value="{{ $currentNilai }}"
                                   placeholder="cth: 90"
                                   style="text-align: center; font-weight: 800; color: #065f46;">
                        </div>

                        {{-- 4. JUMLAH BARIS (OPSIONAL) --}}
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
                                    <span class="lulus-pill-btn btn-ulang">✗ Ulang</span>
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
            @empty
                <div style="background: white; border-radius: 14px; padding: 36px 16px; text-align: center; color: #94a3b8; border: 1px solid #e2e8f0;">
                    <i class="fas fa-users-slash" style="font-size: 2.2rem; margin-bottom: 10px; color: #cbd5e1;"></i>
                    <h4 style="margin: 0; color: #475569;">Belum Ada Siswa di Kelompok Ini</h4>
                    <p style="font-size: 0.82rem; margin-top: 4px;">Hubungi Admin / Tim Kurikulum untuk memasukkan siswa ke kelompok Anda.</p>
                </div>
            @endforelse

            {{-- 6. STICKY BOTTOM BAR (TOMBOL SIMPAN UTAMA - COMPACT & CLEAN) --}}
            @if($activeGroup->students->isNotEmpty())
                <div class="sticky-bottom-bar">
                    <div class="sticky-content">
                        <div class="sticky-info-text">
                            <i class="fas fa-calendar-day" style="color: #059669; margin-right: 4px;"></i>
                            Sesi: <strong>{{ \Carbon\Carbon::parse($sessionDate)->locale('id')->isoFormat('D MMMM Y') }}</strong>
                            <span style="margin: 0 6px; color: #cbd5e1;">·</span>
                            Total: <strong>{{ $activeGroup->students->count() }} Siswa</strong>
                        </div>

                        <div class="sticky-actions-wrap">
                            <button type="button" onclick="openWaModal()" class="btn-sticky-wa">
                                <i class="fab fa-whatsapp"></i> Share WA
                            </button>

                            <button type="submit" class="btn-sticky-save">
                                <i class="fas fa-save"></i>
                                <span>Simpan Sesi</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        </form>
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

    document.addEventListener('DOMContentLoaded', function() {
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

    // Salin teks laporan WA
    function copyWaText() {
        const textarea = document.getElementById('waMessageTextarea');
        textarea.select();
        textarea.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(textarea.value).then(() => {
            alert('✅ Teks laporan WhatsApp berhasil disalin! Tinggal tempel (paste) di WhatsApp Web atau aplikasi WA.');
        }).catch(() => {
            document.execCommand('copy');
            alert('✅ Teks berhasil disalin!');
        });
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
        document.addEventListener('DOMContentLoaded', function() {
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
