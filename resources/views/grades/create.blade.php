@extends('layouts.app')

@section('title', 'Form Penilaian Siswa')
@section('page-title', 'Form Penilaian Ekstrakurikuler')

@push('styles')
<style>
    /* Reset & Container */
    .grades-form-wrapper {
        max-width: 980px;
        margin: 0 auto 3rem;
        padding: 0 10px;
    }

    /* Tombol Kembali */
    .btn-back-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        padding: 8px 18px;
        border-radius: 999px;
        font-size: 0.86rem;
        font-weight: 750;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
        margin-bottom: 1.25rem;
    }
    .btn-back-pill:hover {
        background: #f8fafc;
        color: #0f172a;
        transform: translateX(-3px);
    }

    /* Kartu Lembar Penilaian */
    .card.penilaian-main-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        padding: 0 !important;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Header Lembar Penilaian */
    .penilaian-card-header {
        background: linear-gradient(180deg, #f5f3ff 0%, #ffffff 100%);
        padding: 22px 26px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .penilaian-header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        flex-wrap: wrap;
        gap: 12px;
    }
    .penilaian-info-group {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .penilaian-icon-badge {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #ede9fe;
        color: #7c3aed;
        border: 1.5px solid #ddd6fe;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.12);
    }
    .penilaian-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.2;
    }
    .penilaian-subtitle {
        font-size: 0.84rem;
        color: #64748b;
        font-weight: 600;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .penilaian-top-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .rekap-grades-pill {
        background: #f5f3ff;
        color: #5b21b6;
        font-size: 0.82rem;
        font-weight: 750;
        height: 38px;
        padding: 0 16px;
        border-radius: 11px;
        border: 1.5px solid #ddd6fe;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-sizing: border-box;
        transition: all 0.2s;
    }
    .rekap-grades-pill:hover {
        background: #ede9fe;
        color: #4c1d95;
    }
    .siswa-count-pill {
        background: #f1f5f9;
        color: #334155;
        font-size: 0.82rem;
        font-weight: 750;
        height: 38px;
        padding: 0 14px;
        border-radius: 11px;
        border: 1.5px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-sizing: border-box;
    }
    .badge-sas {
        background: #ede9fe;
        color: #5b21b6;
        padding: 2px 8px;
        border-radius: 6px;
        font-weight: 800;
    }
    .badge-year {
        background: #f1f5f9;
        color: #475569;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 800;
    }

    /* Switcher Bar Kategori Nilai */
    .grades-type-switcher-bar {
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .grades-tabs-grid {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e2e8f0;
        padding: 4px;
        border-radius: 12px;
    }
    .grade-tab-item {
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 750;
        padding: 6px 14px;
        border-radius: 9px;
        transition: all 0.2s;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .grade-tab-item.active {
        background: #ffffff;
        color: #7c3aed;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .grade-date-picker-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        padding: 0 12px;
        height: 38px;
        border-radius: 11px;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        box-sizing: border-box;
    }
    .grade-date-picker-pill label {
        font-size: 0.8rem;
        font-weight: 750;
        color: #475569;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
    }
    .grade-date-picker-pill input[type="date"] {
        border: none;
        background: transparent;
        font-size: 0.84rem;
        font-weight: 700;
        color: #0f172a;
        outline: none;
        cursor: pointer;
        padding: 0;
    }

    /* Tabel Penilaian Desktop */
    .penilaian-table {
        width: 100%;
        border-collapse: collapse;
    }
    .penilaian-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.8rem;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 22px;
        border-bottom: 1.5px solid #e2e8f0;
        text-align: left;
    }
    .penilaian-table td {
        padding: 16px 22px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .penilaian-table tr:hover td {
        background: #fafcfb;
    }

    /* Avatar & Info Siswa */
    .student-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .student-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #e2e8f0;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 800;
        flex-shrink: 0;
    }
    .student-name {
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
    }
    .student-class-badge {
        display: inline-block;
        background: #f1f5f9;
        color: #475569;
        border-radius: 6px;
        padding: 2px 8px;
        font-size: 0.74rem;
        font-weight: 700;
        margin-top: 3px;
        border: 1px solid #e2e8f0;
    }

    /* Segmented Grade Radios (Desktop Style) */
    .segmented-grade-group {
        display: inline-flex;
        gap: 8px;
        background: #f8fafc;
        padding: 5px;
        border-radius: 14px;
        border: 1.5px solid #e2e8f0;
    }
    .grade-pill {
        position: relative;
        cursor: pointer;
        user-select: none;
        margin: 0;
    }
    .grade-pill input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        margin: 0;
        pointer-events: none;
    }
    .grade-pill-content {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 0.86rem;
        font-weight: 750;
        color: #64748b;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .grade-pill:hover .grade-pill-content {
        color: #0f172a;
        border-color: #cbd5e1;
    }

    /* Checked State for Grade A (Emerald / Green) */
    .grade-pill.grade-a input:checked + .grade-pill-content {
        background: #059669;
        color: #ffffff;
        border-color: #059669;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }
    /* Checked State for Grade B (Indigo / Blue) */
    .grade-pill.grade-b input:checked + .grade-pill-content {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    /* Checked State for Grade C (Amber / Orange) */
    .grade-pill.grade-c input:checked + .grade-pill-content {
        background: #d97706;
        color: #ffffff;
        border-color: #d97706;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
    }

    /* Calistung Matrix Styles */
    .calistung-card-matrix {
        display: flex;
        flex-direction: column;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 14px;
    }
    .calistung-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        background: #ffffff;
        padding: 6px 12px;
        border: 1px solid #f1f5f9;
        border-radius: 10px;
    }
    .calistung-label {
        font-weight: 750;
        font-size: 0.84rem;
        color: #334155;
        min-width: 90px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .calistung-radios {
        display: flex;
        gap: 6px;
    }
    .calistung-pill {
        position: relative;
        cursor: pointer;
        margin: 0;
    }
    .calistung-pill input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .calistung-pill-inner {
        padding: 5px 14px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 800;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        display: inline-block;
        transition: all 0.2s ease;
    }
    .calistung-pill input:checked + .calistung-pill-inner {
        background: #7c3aed;
        color: #ffffff;
        border-color: #7c3aed;
        box-shadow: 0 2px 6px rgba(124, 58, 237, 0.3);
    }

    /* Tombol Simpan Footer Desktop */
    .penilaian-desktop-footer {
        padding: 20px 28px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 14px;
    }
    .btn-submit-main {
        height: 50px;
        padding: 0 32px;
        border-radius: 14px;
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        color: #ffffff;
        border: none;
        font-size: 0.96rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 6px 18px rgba(109, 40, 217, 0.28);
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .btn-submit-main:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(109, 40, 217, 0.38);
        color: #ffffff;
    }

    /* Sticky Bottom Bar untuk Layar HP */
    .penilaian-mobile-sticky-bar {
        display: none;
    }

    /* ======================================================== */
    /* KHUSUS TAMPILAN HP (MOBILE OPTIMIZED CALIBER) */
    /* ======================================================== */
    @media (max-width: 768px) {
        .grades-form-wrapper {
            margin: 0.4rem auto 4.5rem;
            padding: 0 4px;
        }
        .btn-back-pill {
            padding: 6px 14px;
            font-size: 0.78rem;
            margin-bottom: 0.65rem;
            border-radius: 999px;
        }
        .penilaian-card-header {
            padding: 14px 12px;
            gap: 10px;
        }
        .penilaian-header-top {
            gap: 8px;
        }
        .penilaian-icon-badge {
            width: 38px;
            height: 38px;
            font-size: 1.1rem;
            border-radius: 10px;
        }
        .penilaian-title {
            font-size: 1.05rem;
        }
        .penilaian-subtitle {
            font-size: 0.76rem;
            gap: 4px;
        }
        .penilaian-top-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .rekap-grades-pill {
            height: 32px;
            padding: 0 10px;
            font-size: 0.75rem;
            border-radius: 8px;
        }
        .siswa-count-pill {
            height: 32px;
            padding: 0 10px;
            font-size: 0.75rem;
            border-radius: 8px;
        }

        /* Tab Switcher Kategori Nilai: 3 Kolom Rata & Simetris di HP */
        .grades-type-switcher-bar {
            padding: 8px 12px;
            flex-direction: column;
            gap: 6px;
            align-items: stretch;
        }
        .grades-tabs-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4px;
            width: 100%;
            background: #e2e8f0;
            padding: 3px;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .grade-tab-item {
            width: 100%;
            height: 33px;
            padding: 0 2px;
            font-size: 0.73rem;
            font-weight: 750;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
            text-align: center;
            box-sizing: border-box;
            white-space: nowrap;
        }
        .grade-tab-item i {
            font-size: 0.7rem;
        }

        /* Date Picker di HP: Lebar Penuh & Ramping */
        .grade-date-picker-pill {
            width: 100%;
            height: 34px;
            padding: 0 8px;
            border-radius: 8px;
            justify-content: space-between;
        }
        .grade-date-picker-pill label {
            font-size: 0.74rem;
        }
        .grade-date-picker-pill input[type="date"] {
            font-size: 0.78rem;
            width: 120px;
        }

        /* Transformasi Tabel menjadi Mobile Card */
        .penilaian-table, 
        .penilaian-table thead, 
        .penilaian-table tbody, 
        .penilaian-table tr, 
        .penilaian-table td {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }
        .penilaian-table thead {
            display: none;
        }
        .penilaian-table tr {
            background: #ffffff;
            padding: 12px 10px;
            border-bottom: 5px solid #f1f5f9;
        }
        .penilaian-table td {
            padding: 0;
            border: none;
        }
        .penilaian-table td:first-child {
            display: none;
        }
        .penilaian-table td:nth-child(2) {
            margin-bottom: 8px;
        }

        /* Segmented Grade Grid 3 Kolom: Ramping, Kompak & Simetris */
        .segmented-grade-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4px;
            width: 100%;
            padding: 3px;
            background: #f1f5f9;
            border-radius: 9px;
            box-sizing: border-box;
        }
        .grade-pill {
            width: 100%;
            margin: 0;
        }
        .grade-pill-content {
            width: 100%;
            height: 34px;
            padding: 0 4px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 4px;
            border-radius: 7px;
            box-sizing: border-box;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }
        .grade-pill-content .main-letter {
            font-size: 0.92rem;
            font-weight: 900;
            line-height: 1;
        }
        .grade-pill-content .sub-label {
            font-size: 0.68rem;
            font-weight: 700;
            opacity: 0.9;
            white-space: nowrap;
        }

        /* Calistung Responsif HP */
        .calistung-row {
            flex-direction: column;
            align-items: stretch;
            gap: 5px;
            padding: 6px 8px;
        }
        .calistung-radios {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4px;
            width: 100%;
        }
        .calistung-pill {
            width: 100%;
        }
        .calistung-pill-inner {
            width: 100%;
            text-align: center;
            padding: 0 4px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.74rem;
            box-sizing: border-box;
            border-radius: 7px;
        }

        /* Sembunyikan Footer Desktop pada HP */
        .penilaian-desktop-footer {
            display: none;
        }

        /* Sticky Bar Bawah Ramping & Pas */
        .penilaian-mobile-sticky-bar {
            display: block;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 999;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1.5px solid #e2e8f0;
            padding: 7px 12px;
            box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.08);
        }
        .penilaian-mobile-sticky-bar button {
            width: 100%;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            color: #ffffff;
            border: none;
            font-size: 0.88rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 3px 10px rgba(109, 40, 217, 0.25);
            cursor: pointer;
        }
    }
</style>
@endpush

@section('content')
<div class="grades-form-wrapper">
    <!-- Tombol Kembali -->
    @if(Auth::user()->role === 'teacher')
    <a href="{{ route('dashboard') }}" class="btn-back-pill">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Beranda</span>
    </a>
    @else
    <a href="{{ route('grades.index') }}" class="btn-back-pill">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Pemilihan Kriteria</span>
    </a>
    @endif

    <!-- Notifikasi Riwayat / Semester -->
    @if(!$contextYear->is_active || $contextYear->active_semester != $semester)
        <div style="background: #fffbeb; border: 1.5px solid #fde68a; color: #92400e; padding: 14px 18px; border-radius: 16px; margin-bottom: 18px; display: flex; align-items: center; gap: 12px; font-size: 0.88rem; font-weight: 600;">
            <i class="fas fa-history" style="font-size: 1.2rem; color: #d97706; flex-shrink: 0;"></i>
            <div>
                <strong>Mode Arsip / Riwayat:</strong> Anda sedang meninjau data untuk 
                <u>Tahun {{ $contextYear->name }} Semester {{ $semester }}</u>.
            </div>
        </div>
    @endif

    <!-- Form Simpan Nilai -->
    <form id="grade-form" action="{{ route('grades.store') }}" method="POST">
        @csrf
        <input type="hidden" name="eskul_id" value="{{ $eskul->id }}">
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="academic_year_id" value="{{ $yearId }}">
        <input type="hidden" name="semester" value="{{ $semester }}">

        <!-- Kartu Utama Penilaian -->
        <div class="card penilaian-main-card">
            <!-- Header Info Kegiatan & Penilaian -->
            <div class="penilaian-card-header">
                <div class="penilaian-header-top">
                    <div class="penilaian-info-group">
                        <div class="penilaian-icon-badge">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <h3 class="penilaian-title">{{ $eskul->name }}</h3>
                            <div class="penilaian-subtitle">
                                @if($type == 'daily')
                                    <span><i class="far fa-calendar-alt" style="color: #7c3aed;"></i> Nilai Harian: {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}</span>
                                @elseif($type == 'sas1')
                                    <span class="badge-sas"><i class="fas fa-award"></i> Asesmen Akhir Semester 1 (SAS 1)</span>
                                @elseif($type == 'sas2')
                                    <span class="badge-sas"><i class="fas fa-award"></i> Asesmen Akhir Semester 2 (SAS 2)</span>
                                @endif
                                <span>•</span>
                                <span class="badge-year">Tahun {{ $contextYear->name }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="penilaian-top-actions">
                        <a href="{{ route('grades.report', ['eskul_id' => $eskul->id, 'academic_year_id' => $yearId, 'semester' => $semester]) }}" class="rekap-grades-pill">
                            <i class="fas fa-chart-bar"></i>
                            <span>Rekap</span>
                        </a>

                        <span class="siswa-count-pill">
                            <i class="fas fa-user-graduate" style="color: #7c3aed;"></i>
                            <strong>{{ $students->count() }}</strong> Siswa
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tab Switcher Kategori Nilai (Harian, SAS 1, SAS 2) -->
            <div class="grades-type-switcher-bar">
                <div class="grades-tabs-grid">
                    <a href="{{ route('grades.create', ['eskul_id' => $eskul->id, 'type' => 'daily', 'date' => $date ?: date('Y-m-d'), 'academic_year_id' => $yearId, 'semester' => $semester]) }}" 
                       class="grade-tab-item {{ $type == 'daily' ? 'active' : '' }}">
                        <i class="fas fa-calendar-day"></i> Nilai Harian
                    </a>
                    <a href="{{ route('grades.create', ['eskul_id' => $eskul->id, 'type' => 'sas1', 'academic_year_id' => $yearId, 'semester' => $semester]) }}" 
                       class="grade-tab-item {{ $type == 'sas1' ? 'active' : '' }}">
                        <i class="fas fa-award"></i> SAS 1
                    </a>
                    <a href="{{ route('grades.create', ['eskul_id' => $eskul->id, 'type' => 'sas2', 'academic_year_id' => $yearId, 'semester' => $semester]) }}" 
                       class="grade-tab-item {{ $type == 'sas2' ? 'active' : '' }}">
                        <i class="fas fa-award"></i> SAS 2
                    </a>
                </div>

                @if($type == 'daily')
                <!-- Date Quick Picker Khusus Nilai Harian (Simetris di HP) -->
                <div class="grade-date-picker-pill">
                    <label for="changeGradeDateTop">
                        <i class="far fa-calendar-alt" style="color: #7c3aed;"></i> Tanggal:
                    </label>
                    <input type="date" id="changeGradeDateTop" value="{{ $date }}" 
                        onchange="window.location.href='{{ route('grades.create') }}?eskul_id={{ $eskul->id }}&type=daily&date=' + this.value + '&academic_year_id={{ $yearId }}&semester={{ $semester }}'">
                </div>
                @endif
            </div>

            <!-- Tabel Daftar Siswa & Opsi Nilai (Menjadi Mobile Card di HP) -->
            <table class="penilaian-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th style="width: 45%;">Nama Siswa & Presensi</th>
                        <th style="width: 50%;">Capaian Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                    @php
                        $score = $existingGrades[$student->id]->score ?? '';
                        $attendRec = $studentAttendance[$student->id] ?? null;
                        $isAbsent = $attendRec && in_array($attendRec->status, ['absent', 'sick', 'permission']);
                        $attendBadge = '';
                        $attendColor = '';
                        $attendBg = '';
                        if ($attendRec) {
                            match($attendRec->status) {
                                'sick'       => [$attendBadge = 'Sakit',    $attendColor = '#d97706', $attendBg = '#fef3c7'],
                                'permission' => [$attendBadge = 'Izin',     $attendColor = '#0284c7', $attendBg = '#e0f2fe'],
                                'absent'     => [$attendBadge = 'Alpa',    $attendColor = '#e11d48', $attendBg = '#ffe4e6'],
                                default      => [$attendBadge = 'Hadir',    $attendColor = '#059669', $attendBg = '#dcfce7'],
                            };
                        }
                    @endphp
                    <tr style="{{ $isAbsent ? 'background: #fffdf5;' : '' }}">
                        <td style="text-align: center; font-weight: 700; color: #94a3b8; font-size: 0.86rem;">
                            {{ $index + 1 }}
                        </td>
                        <td>
                            <div class="student-info-cell">
                                <div class="student-avatar" style="background: {{ ['#f5f3ff', '#eff6ff', '#f0fdf4', '#fdf2f8', '#fffbeb'][$index % 5] }}; color: {{ ['#7c3aed', '#2563eb', '#059669', '#db2777', '#d97706'][$index % 5] }};">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="student-name">{{ $student->name }}</h4>
                                    <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-top: 3px;">
                                        <span class="student-class-badge">
                                            <i class="fas fa-graduation-cap" style="font-size: 0.7rem; margin-right: 3px;"></i> Kelas {{ $student->class }}
                                        </span>
                                        @if($attendRec)
                                            <span style="background: {{ $attendBg }}; color: {{ $attendColor }}; border: 1px solid {{ $attendColor }}33; padding: 2px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fas fa-{{ $attendRec->status == 'present' ? 'check-circle' : 'exclamation-circle' }}"></i>
                                                {{ $attendBadge }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($isAbsent)
                                {{-- Jika siswa tidak hadir: tampilkan banner santun --}}
                                <div style="background: #fffbeb; border: 1.5px dashed #fde68a; border-radius: 14px; padding: 12px 14px; color: #92400e; font-size: 0.84rem; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-info-circle" style="color: #d97706; font-size: 1.15rem; flex-shrink: 0;"></i>
                                    <div>
                                        <span>Nilai dinonaktifkan — Siswa tercatat <strong>{{ $attendBadge }}</strong>.</span>
                                        @if($attendRec->note)
                                            <div style="color: #b45309; font-size: 0.78rem; margin-top: 2px;">
                                                Catatan: <em>{{ $attendRec->note }}</em>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                @php
                                    $isCalistungClass1 = ($isCalistung ?? false) && \Illuminate\Support\Str::startsWith($student->class, '1');
                                    $jsonScore = json_decode($score, true);
                                    $valReading = is_array($jsonScore) ? ($jsonScore['reading'] ?? '') : '';
                                    $valWriting = is_array($jsonScore) ? ($jsonScore['writing'] ?? '') : '';
                                    $valCounting = is_array($jsonScore) ? ($jsonScore['counting'] ?? '') : '';
                                @endphp

                                @if($isCalistungClass1)
                                    <div class="calistung-card-matrix">
                                        <!-- Membaca -->
                                        <div class="calistung-row">
                                            <span class="calistung-label"><i class="fas fa-book-reader" style="color: #7c3aed;"></i> Membaca</span>
                                            <div class="calistung-radios">
                                                <label class="calistung-pill"><input type="radio" name="grades[{{ $student->id }}][reading]" value="A" {{ $valReading == 'A' ? 'checked' : '' }} required><span class="calistung-pill-inner">A</span></label>
                                                <label class="calistung-pill"><input type="radio" name="grades[{{ $student->id }}][reading]" value="B" {{ $valReading == 'B' ? 'checked' : '' }}><span class="calistung-pill-inner">B</span></label>
                                                <label class="calistung-pill"><input type="radio" name="grades[{{ $student->id }}][reading]" value="C" {{ $valReading == 'C' ? 'checked' : '' }}><span class="calistung-pill-inner">C</span></label>
                                            </div>
                                        </div>
                                        <!-- Menulis -->
                                        <div class="calistung-row">
                                            <span class="calistung-label"><i class="fas fa-pen-nib" style="color: #0284c7;"></i> Menulis</span>
                                            <div class="calistung-radios">
                                                <label class="calistung-pill"><input type="radio" name="grades[{{ $student->id }}][writing]" value="A" {{ $valWriting == 'A' ? 'checked' : '' }} required><span class="calistung-pill-inner">A</span></label>
                                                <label class="calistung-pill"><input type="radio" name="grades[{{ $student->id }}][writing]" value="B" {{ $valWriting == 'B' ? 'checked' : '' }}><span class="calistung-pill-inner">B</span></label>
                                                <label class="calistung-pill"><input type="radio" name="grades[{{ $student->id }}][writing]" value="C" {{ $valWriting == 'C' ? 'checked' : '' }}><span class="calistung-pill-inner">C</span></label>
                                            </div>
                                        </div>
                                        <!-- Menghitung -->
                                        <div class="calistung-row">
                                            <span class="calistung-label"><i class="fas fa-calculator" style="color: #059669;"></i> Menghitung</span>
                                            <div class="calistung-radios">
                                                <label class="calistung-pill"><input type="radio" name="grades[{{ $student->id }}][counting]" value="A" {{ $valCounting == 'A' ? 'checked' : '' }} required><span class="calistung-pill-inner">A</span></label>
                                                <label class="calistung-pill"><input type="radio" name="grades[{{ $student->id }}][counting]" value="B" {{ $valCounting == 'B' ? 'checked' : '' }}><span class="calistung-pill-inner">B</span></label>
                                                <label class="calistung-pill"><input type="radio" name="grades[{{ $student->id }}][counting]" value="C" {{ $valCounting == 'C' ? 'checked' : '' }}><span class="calistung-pill-inner">C</span></label>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="segmented-grade-group">
                                        <label class="grade-pill grade-a" title="Predikat A (Sangat Baik)">
                                            <input type="radio" name="grades[{{ $student->id }}]" value="A" {{ $score == 'A' ? 'checked' : '' }} required>
                                            <span class="grade-pill-content">
                                                <span class="main-letter">A</span>
                                                <span class="sub-label">Sangat Baik</span>
                                            </span>
                                        </label>
                                        <label class="grade-pill grade-b" title="Predikat B (Baik)">
                                            <input type="radio" name="grades[{{ $student->id }}]" value="B" {{ $score == 'B' ? 'checked' : '' }}>
                                            <span class="grade-pill-content">
                                                <span class="main-letter">B</span>
                                                <span class="sub-label">Baik</span>
                                            </span>
                                        </label>
                                        <label class="grade-pill grade-c" title="Predikat C (Cukup)">
                                            <input type="radio" name="grades[{{ $student->id }}]" value="C" {{ $score == 'C' ? 'checked' : '' }}>
                                            <span class="grade-pill-content">
                                                <span class="main-letter">C</span>
                                                <span class="sub-label">Cukup</span>
                                            </span>
                                        </label>
                                    </div>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 40px 20px; color: #64748b;">
                            <i class="fas fa-user-slash" style="font-size: 2.2rem; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                            <strong style="color: #334155; font-size: 1rem;">Belum Ada Siswa Terdaftar</strong>
                            <p style="margin: 4px 0 0; font-size: 0.85rem;">Silakan tambahkan siswa ke kegiatan eskul ini melalui menu Kelola Eskul.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Footer Desktop -->
            @if($students->isNotEmpty())
            <div class="penilaian-desktop-footer">
                <span style="font-size: 0.85rem; color: #64748b;">
                    Nilai yang tersimpan akan otomatis masuk ke dalam rekapitulasi raport eskul.
                </span>
                <button type="submit" class="btn-submit-main">
                    <i class="fas fa-save"></i>
                    <span>Simpan Penilaian Siswa</span>
                </button>
            </div>
            @endif
        </div>

        <!-- Sticky Dock Bar Khusus Layar HP -->
        @if($students->isNotEmpty())
        <div class="penilaian-mobile-sticky-bar">
            <button type="submit">
                <i class="fas fa-save"></i>
                <span>Simpan Penilaian ({{ $students->count() }} Siswa)</span>
            </button>
        </div>
        @endif
    </form>
</div>
@endsection
