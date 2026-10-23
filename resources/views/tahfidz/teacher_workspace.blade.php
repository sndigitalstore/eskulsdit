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

    /* Input Hafalan Section */
    .hafalan-box-wrap {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px dashed #e2e8f0;
        display: grid;
        grid-template-columns: 2.2fr 0.9fr 1.2fr 2fr;
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
    }
    .input-clean:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
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
        .hafalan-col-baris {
            grid-column: span 1;
        }
        .hafalan-col-status {
            grid-column: span 1;
        }
        .hafalan-col-notes {
            grid-column: span 2;
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
                        {{ $grp->students_count }} santri
                    </span>
                </a>
            @endforeach
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
                <span><i class="fas fa-users"></i> <strong>{{ $activeGroup->students->count() }} Santri</strong></span>
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
        $totalSantri = $activeGroup->students->count();
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
                <div class="kpi-green-label">Total Santri</div>
                <div class="kpi-green-value">
                    {{ $totalSantri }} <span class="kpi-green-unit">Anak</span>
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
                    {{ $kehadiranHariIni }} <span class="kpi-green-unit">/ {{ $totalSantri }} Hadir</span>
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
                    {{ $sudahSetorHariIni }} <span class="kpi-green-unit">Santri</span>
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

    {{-- DATALIST SURAH AL-QUR'AN AUTO-COMPLETE --}}
    <datalist id="surahList">
        @foreach($allSurahs as $surah)
            <option value="{{ $surah }}">
        @endforeach
    </datalist>

    {{-- 5. FORMULIR ALL-IN-ONE HALAQAH --}}
    <form method="POST" action="{{ route('tahfidz.attendance.store', ['group' => $activeGroup->id]) }}" id="mainHalaqahForm">
        @csrf
        <input type="hidden" name="session_date" value="{{ $sessionDate }}">
        <input type="hidden" name="academic_year_id" value="{{ $activeGroup->academic_year_id ?? $activeYear?->id }}">
        <input type="hidden" name="redirect_to" value="workspace">

        {{-- DAFTAR SANTRI --}}
        @forelse($activeGroup->students as $idx => $std)
            @php
                $existingAtt = $sessionAttendances->get($std->id);
                $currentStatus = $existingAtt ? $existingAtt->status : 'hadir';
                $currentCapaian = $existingAtt ? $existingAtt->capaian : '';
                $currentBaris = $existingAtt ? ($existingAtt->jumlah_baris ?? 0) : '';
                $currentLulus = $existingAtt ? strtoupper($existingAtt->hafalan_status ?? 'LULUS') : 'LULUS';
                $currentNotes = $existingAtt ? ($existingAtt->tugas_besok ?? $existingAtt->notes ?? '') : '';

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
                                    @if($lastRec)
                                        <span style="color: #047857; font-weight: 600;">
                                            📌 Lalu: <strong>{{ $lastRec->surah_name }}</strong>{{ $lastRec->start_ayah ? " ({$lastRec->start_ayah}-{$lastRec->end_ayah})" : '' }}
                                        </span>
                                        <button type="button" 
                                                onclick="copyLastSurah('{{ $std->id }}', '{{ addslashes($lastRec->surah_name) }}')"
                                                style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; border-radius: 4px; padding: 1px 5px; font-size: 0.68rem; font-weight: 700; cursor: pointer;">
                                            Gunakan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- QUICK BUTTONS: KARTU & WA ORTU --}}
                        <div style="display: flex; align-items: center; gap: 5px;">
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
                    
                    {{-- 1. SURAH & AYAT --}}
                    <div class="hafalan-col-surah">
                        <label class="hafalan-field-label">📖 Surah & Ayat</label>
                        <input type="text" name="attendance[{{ $std->id }}][capaian]" 
                               id="capaian-input-{{ $std->id }}"
                               list="surahList"
                               class="input-clean" 
                               value="{{ $currentCapaian }}"
                               placeholder="Cth: An-Naba' 1-20"
                               autocomplete="off">
                    </div>

                    {{-- 2. JUMLAH BARIS (OPSIONAL) --}}
                    <div class="hafalan-col-baris">
                        <label class="hafalan-field-label">📏 Baris</label>
                        <input type="number" name="attendance[{{ $std->id }}][jumlah_baris]" 
                               class="input-clean" 
                               min="0" max="500"
                               value="{{ $currentBaris !== '' ? $currentBaris : '' }}"
                               placeholder="0">
                    </div>

                    {{-- 3. STATUS LULUS / BELUM --}}
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

                    {{-- 4. TUGAS BESOK / CATATAN GURU --}}
                    <div class="hafalan-col-notes">
                        <label class="hafalan-field-label">📝 Tugas Besok / Catatan</label>
                        <input type="text" name="attendance[{{ $std->id }}][tugas_besok]" 
                               class="input-clean" 
                               value="{{ $currentNotes }}"
                               placeholder="Cth: Lanjut ayat 21-40">
                    </div>

                </div>

                {{-- KETERANGAN TIDAK HADIR --}}
                <div id="absent-note-{{ $std->id }}" 
                     style="margin-top: 8px; font-size: 0.78rem; color: #64748b; font-style: italic; {{ $currentStatus === 'hadir' ? 'display: none;' : '' }}">
                    Santri berstatus <strong id="absent-text-{{ $std->id }}">{{ strtoupper($currentStatus) }}</strong> (tidak perlu mengisi setoran hafalan).
                </div>

            </div>
        @empty
            <div style="background: white; border-radius: 14px; padding: 36px 16px; text-align: center; color: #94a3b8; border: 1px solid #e2e8f0;">
                <i class="fas fa-users-slash" style="font-size: 2.2rem; margin-bottom: 10px; color: #cbd5e1;"></i>
                <h4 style="margin: 0; color: #475569;">Belum Ada Santri di Kelompok Ini</h4>
                <p style="font-size: 0.82rem; margin-top: 4px;">Hubungi Admin / Tim Kurikulum untuk memasukkan santri ke kelompok Anda.</p>
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
                        Total: <strong>{{ $activeGroup->students->count() }} Santri</strong>
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

    // Salin nama surah dari riwayat sebelumnya
    function copyLastSurah(studentId, surahName) {
        const input = document.getElementById('capaian-input-' + studentId);
        if (input) {
            input.value = surahName + ' ';
            input.focus();
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

    @if($openShare)
        document.addEventListener('DOMContentLoaded', function() {
            openWaModal();
        });
    @endif
</script>
@endsection
