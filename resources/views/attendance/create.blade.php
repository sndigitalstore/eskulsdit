@extends('layouts.app')

@section('title', 'Form Presensi Siswa')
@section('page-title', 'Form Presensi Ekstrakurikuler')

@push('styles')
<style>
    /* Reset & Container */
    .presensi-form-wrapper {
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

    /* Kartu Lembar Presensi */
    .card.presensi-main-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        padding: 0 !important;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Header Lembar Presensi */
    .presensi-card-header {
        background: linear-gradient(180deg, #ecfdf5 0%, #ffffff 100%);
        padding: 22px 26px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .presensi-header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        flex-wrap: wrap;
        gap: 12px;
    }
    .presensi-info-group {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .presensi-icon-badge {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #dcfce7;
        color: #059669;
        border: 1.5px solid #bbf7d0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.12);
    }
    .presensi-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.2;
    }
    .presensi-subtitle {
        font-size: 0.84rem;
        color: #64748b;
        font-weight: 600;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .presensi-header-toolbar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .date-picker-pill {
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
    .date-picker-pill label {
        font-size: 0.8rem;
        font-weight: 750;
        color: #475569;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
    }
    .date-picker-pill input[type="date"] {
        border: none;
        background: transparent;
        font-size: 0.84rem;
        font-weight: 700;
        color: #0f172a;
        outline: none;
        cursor: pointer;
        padding: 0;
    }
    .rekap-link-pill {
        background: #f0fdf4;
        color: #166534;
        font-size: 0.82rem;
        font-weight: 750;
        height: 38px;
        padding: 0 16px;
        border-radius: 11px;
        border: 1.5px solid #bbf7d0;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-sizing: border-box;
        transition: all 0.2s;
    }
    .rekap-link-pill:hover {
        background: #dcfce7;
        color: #14532d;
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
    .badge-year {
        background: #dcfce7;
        color: #166534;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 800;
    }

    /* Control Bar Aksi Cepat */
    .batch-control-bar {
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        padding: 14px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .batch-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 16px;
        border-radius: 12px;
        font-size: 0.84rem;
        font-weight: 750;
        cursor: pointer;
        border: 1.5px solid transparent;
        transition: all 0.2s ease;
    }
    .batch-btn-present {
        background: #ecfdf5;
        color: #065f46;
        border-color: #a7f3d0;
    }
    .batch-btn-present:hover {
        background: #d1fae5;
        transform: translateY(-1px);
    }
    .batch-btn-perm {
        background: #eff6ff;
        color: #1e40af;
        border-color: #bfdbfe;
    }
    .batch-btn-perm:hover {
        background: #dbeafe;
        transform: translateY(-1px);
    }

    /* Tabel Presensi Desktop */
    .presensi-table {
        width: 100%;
        border-collapse: collapse;
    }
    .presensi-table th {
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
    .presensi-table td {
        padding: 16px 22px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .presensi-table tr:hover td {
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

    /* Segmented Status Radios (Pill Style) */
    .segmented-status-group {
        display: inline-flex;
        gap: 6px;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 14px;
        border: 1.5px solid #e2e8f0;
    }
    .status-pill {
        position: relative;
        cursor: pointer;
        user-select: none;
        margin: 0;
    }
    .status-pill input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        margin: 0;
        pointer-events: none;
    }
    .pill-content {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 0.84rem;
        font-weight: 750;
        color: #64748b;
        background: #ffffff;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .status-pill:hover .pill-content {
        color: #0f172a;
        border-color: #cbd5e1;
    }

    /* Checked State for Present (Emerald) */
    .status-pill.status-present input:checked + .pill-content {
        background: #059669;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);
    }
    /* Checked State for Sick (Amber) */
    .status-pill.status-sick input:checked + .pill-content {
        background: #d97706;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(217, 119, 6, 0.3);
    }
    /* Checked State for Permission (Sky Blue) */
    .status-pill.status-permission input:checked + .pill-content {
        background: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(2, 132, 199, 0.3);
    }
    /* Checked State for Absent (Rose/Red) */
    .status-pill.status-absent input:checked + .pill-content {
        background: #e11d48;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(225, 29, 72, 0.3);
    }

    /* Input Catatan */
    .note-input-field {
        width: 100%;
        height: 42px;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        padding: 0 14px;
        font-size: 0.86rem;
        font-weight: 600;
        color: #1e293b;
        background: #ffffff;
        outline: none;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .note-input-field:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }

    /* Tombol Simpan Footer Desktop */
    .presensi-desktop-footer {
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
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        border: none;
        font-size: 0.96rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 6px 18px rgba(5, 150, 105, 0.28);
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .btn-submit-main:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(5, 150, 105, 0.38);
        color: #ffffff;
    }

    /* Sticky Bottom Bar untuk Layar HP */
    .presensi-mobile-sticky-bar {
        display: none;
    }

    /* ======================================================== */
    /* KHUSUS TAMPILAN HP (MOBILE OPTIMIZED CALIBER) */
    /* ======================================================== */
    @media (max-width: 768px) {
        .presensi-form-wrapper {
            margin: 0.4rem auto 4.5rem;
            padding: 0 4px;
        }
        .btn-back-pill {
            padding: 6px 14px;
            font-size: 0.78rem;
            margin-bottom: 0.65rem;
            border-radius: 999px;
        }
        .presensi-card-header {
            padding: 14px 12px;
            gap: 10px;
        }
        .presensi-header-top {
            gap: 8px;
        }
        .presensi-icon-badge {
            width: 38px;
            height: 38px;
            font-size: 1.1rem;
            border-radius: 10px;
        }
        .presensi-title {
            font-size: 1.05rem;
        }
        .presensi-subtitle {
            font-size: 0.76rem;
            gap: 4px;
        }
        .siswa-count-pill {
            height: 32px;
            padding: 0 10px;
            font-size: 0.75rem;
            border-radius: 8px;
        }

        /* Toolbar Cepat di HP: 2 Kolom Seimbang & Simetris */
        .presensi-header-toolbar {
            display: grid;
            grid-template-columns: 1fr 90px;
            gap: 6px;
            width: 100%;
        }
        .date-picker-pill {
            width: 100%;
            height: 35px;
            padding: 0 8px;
            border-radius: 8px;
            justify-content: space-between;
        }
        .date-picker-pill label {
            font-size: 0.74rem;
        }
        .date-picker-pill input[type="date"] {
            font-size: 0.78rem;
            width: 115px;
        }
        .rekap-link-pill {
            width: 100%;
            height: 35px;
            padding: 0 6px;
            font-size: 0.76rem;
            border-radius: 8px;
        }

        /* Batch Control Bar 2 Kolom Seimbang di HP */
        .batch-control-bar {
            padding: 8px 12px;
            flex-direction: column;
            align-items: stretch;
            gap: 6px;
        }
        .batch-action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            width: 100%;
        }
        .batch-btn {
            width: 100%;
            height: 33px;
            padding: 0 6px;
            font-size: 0.74rem;
            border-radius: 8px;
        }

        /* Transformasi Tabel menjadi Mobile Card */
        .presensi-table, 
        .presensi-table thead, 
        .presensi-table tbody, 
        .presensi-table tr, 
        .presensi-table td {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }
        .presensi-table thead {
            display: none;
        }
        .presensi-table tr {
            background: #ffffff;
            padding: 12px 10px;
            border-bottom: 5px solid #f1f5f9;
        }
        .presensi-table td {
            padding: 0;
            border: none;
        }
        .presensi-table td:first-child {
            display: none;
        }
        .presensi-table td:nth-child(2) {
            margin-bottom: 8px;
        }
        .presensi-table td:nth-child(3) {
            margin-bottom: 6px;
        }

        /* Segmented Status Grid 4 Kolom: Ramping, Kompak, Simetris */
        .segmented-status-group {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
            width: 100%;
            padding: 3px;
            background: #f1f5f9;
            border-radius: 9px;
            box-sizing: border-box;
        }
        .status-pill {
            width: 100%;
            margin: 0;
        }
        .pill-content {
            width: 100%;
            height: 33px;
            padding: 0 2px;
            font-size: 0.72rem;
            font-weight: 750;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 3px;
            border-radius: 7px;
            box-sizing: border-box;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }
        .pill-content i {
            font-size: 0.72rem;
        }

        .note-input-field {
            height: 33px;
            font-size: 0.78rem;
            padding: 0 10px;
            border-radius: 7px;
        }

        /* Sembunyikan Footer Desktop pada HP */
        .presensi-desktop-footer {
            display: none;
        }

        /* Sticky Bar Bawah Ramping & Pas */
        .presensi-mobile-sticky-bar {
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
        .presensi-mobile-sticky-bar button {
            width: 100%;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
            font-size: 0.88rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 3px 10px rgba(5, 150, 105, 0.25);
            cursor: pointer;
        }
    }
</style>
@endpush

@section('content')
<div class="presensi-form-wrapper">
    <!-- Tombol Kembali -->
    @if(Auth::user()->role === 'teacher')
    <a href="{{ route('dashboard') }}" class="btn-back-pill">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Beranda</span>
    </a>
    @else
    <a href="{{ route('attendance.index') }}" class="btn-back-pill">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Pemilihan Kegiatan</span>
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

    <!-- Notifikasi Data Sudah Pernah Tersimpan & Terkunci -->
    @if(!empty($isAlreadySaved))
        <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 18px; padding: 16px 20px; margin-bottom: 18px; display: flex; align-items: flex-start; gap: 14px; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.08);">
            <div style="width: 42px; height: 42px; border-radius: 12px; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 4px;">
                    <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #065f46;">
                        Data Presensi Siswa Sudah Tersimpan
                    </h4>
                    <span style="background: #d1fae5; color: #047857; font-size: 0.76rem; font-weight: 800; padding: 4px 10px; border-radius: 999px; border: 1px solid #6ee7b7; display: inline-flex; align-items: center; gap: 5px;">
                        <i class="fas fa-lock"></i> Terkunci &bull; {{ $savedCount ?? $students->count() }} Siswa Tercatat
                    </span>
                </div>
                <p style="margin: 0 0 6px; font-size: 0.86rem; color: #047857; line-height: 1.45;">
                    Presensi untuk tanggal <strong>{{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}</strong> pada kegiatan <strong>{{ $eskul->name }}</strong> sudah tersimpan di sistem @if(!empty($savedAt)) (Penyimpanan terakhir: <strong>{{ \Carbon\Carbon::parse($savedAt)->isoFormat('D MMMM Y, HH:mm') }} WIB</strong>)@endif.
                </p>
                @if(Auth::user()->role === 'teacher')
                    <div style="background: rgba(255, 255, 255, 0.85); border: 1px solid #a7f3d0; border-radius: 8px; padding: 7px 12px; font-size: 0.8rem; color: #065f46; display: flex; align-items: center; gap: 6px; font-weight: 600;">
                        <i class="fas fa-shield-alt" style="color: #10b981;"></i>
                        <span>Untuk mencegah data ganda, guru/tutor tidak dapat melakukan pengisian 2 kali pada tanggal yang sama. Hubungi Administrator / Kesiswaan jika perlu perbaikan data.</span>
                    </div>
                @else
                    <div style="background: rgba(255, 255, 255, 0.9); border: 1px solid #bae6fd; border-radius: 12px; padding: 10px 14px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-top: 6px;">
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: #0369a1; font-weight: 600;">
                            <i class="fas fa-user-shield" style="color: #0284c7; font-size: 1rem;"></i>
                            <span>Mode Administrator: Anda dapat memperbarui presensi atau mengosongkan/mereset inputan tanggal ini jika guru perlu revisi.</span>
                        </div>
                        <button type="button" onclick="confirmResetAttendance()" style="background: #fee2e2; border: 1px solid #fca5a5; color: #b91c1c; font-size: 0.78rem; font-weight: 800; padding: 6px 14px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <i class="fas fa-trash-alt"></i>
                            <span>Kosongkan / Reset Presensi</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @php
        $isLockedForTeacher = !empty($isAlreadySaved) && Auth::user()->role === 'teacher';
    @endphp

    <!-- Form Simpan Presensi -->
    <form id="attendance-form" action="{{ route('attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="eskul_id" value="{{ $eskul->id }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="academic_year_id" value="{{ $yearId }}">
        <input type="hidden" name="semester" value="{{ $semester }}">

        <!-- Kartu Utama Presensi -->
        <div class="card presensi-main-card">
            <!-- Header Info Kegiatan -->
            <div class="presensi-card-header">
                <div class="presensi-header-top">
                    <div class="presensi-info-group">
                        <div class="presensi-icon-badge">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div>
                            <h3 class="presensi-title">{{ $eskul->name }}</h3>
                            <div class="presensi-subtitle">
                                <span><i class="far fa-calendar-alt" style="color: #059669;"></i> {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}</span>
                                <span>•</span>
                                <span class="badge-year">Tahun {{ $contextYear->name }}</span>
                                @if(!empty($isAlreadySaved))
                                    <span>•</span>
                                    <span style="background: #dcfce7; color: #15803d; font-size: 0.74rem; font-weight: 800; padding: 2px 8px; border-radius: 999px; border: 1px solid #86efac;">
                                        <i class="fas fa-check-circle"></i> Sudah Tersimpan
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <span class="siswa-count-pill">
                        <i class="fas fa-users" style="color: #059669;"></i>
                        <strong>{{ $students->count() }}</strong> Siswa
                    </span>
                </div>

                <!-- Toolbar Cepat: Tanggal & Rekap (Simetris & Kompak) -->
                <div class="presensi-header-toolbar">
                    <div class="date-picker-pill">
                        <label for="changeDateTop">
                            <i class="far fa-calendar-alt" style="color: #059669;"></i> Tanggal:
                        </label>
                        <input type="date" id="changeDateTop" value="{{ $date }}" 
                            onchange="window.location.href='{{ route('attendance.create') }}?eskul_id={{ $eskul->id }}&date=' + this.value + '&academic_year_id={{ $yearId }}&semester={{ $semester }}'">
                    </div>

                    <a href="{{ route('attendance.report', ['eskul_id' => $eskul->id, 'academic_year_id' => $yearId, 'semester' => $semester]) }}" class="rekap-link-pill">
                        <i class="fas fa-chart-line"></i>
                        <span>Rekap</span>
                    </a>
                </div>
            </div>

            <!-- Bar Aksi Cepat (Batch Actions) -->
            <div class="batch-control-bar">
                <span style="font-size: 0.82rem; font-weight: 750; color: #64748b; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-bolt" style="color: #f59e0b;"></i> Set Kehadiran Sekaligus:
                </span>
                <div class="batch-action-grid">
                    @if(!$isLockedForTeacher)
                        <button type="button" onclick="setAllAttendance('present')" class="batch-btn batch-btn-present">
                            <i class="fas fa-check-double"></i> Set Semua Hadir
                        </button>
                        <button type="button" onclick="setAllAttendance('permission', 'Eskul Diliburkan')" class="batch-btn batch-btn-perm">
                            <i class="fas fa-umbrella-beach"></i> Set Semua Libur
                        </button>
                    @else
                        <span style="font-size: 0.8rem; color: #94a3b8; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fas fa-lock"></i> Aksi cepat dinonaktifkan (Data Terkunci)
                        </span>
                    @endif
                </div>
            </div>

            <!-- Tabel Daftar Siswa (Otomatis Menjadi Mobile Card di HP) -->
            <table class="presensi-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th style="width: 35%;">Nama Siswa</th>
                        <th style="width: 35%;">Status Kehadiran</th>
                        <th style="width: 30%;">Keterangan / Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                    @php
                        $status = $existingAttendance[$student->id]->status ?? 'present';
                        $note = $existingAttendance[$student->id]->note ?? '';
                    @endphp
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: #94a3b8; font-size: 0.86rem;">
                            {{ $index + 1 }}
                        </td>
                        <td>
                            <div class="student-info-cell">
                                <div class="student-avatar" style="background: {{ ['#ecfdf5', '#eff6ff', '#fef3c7', '#fdf2f8', '#faf5ff'][$index % 5] }}; color: {{ ['#059669', '#2563eb', '#d97706', '#db2777', '#7c3aed'][$index % 5] }};">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="student-name">{{ $student->name }}</h4>
                                    <span class="student-class-badge">
                                        <i class="fas fa-graduation-cap" style="font-size: 0.7rem; margin-right: 3px;"></i> Kelas {{ $student->class }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="segmented-status-group">
                                <label class="status-pill status-present" title="Hadir">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="present" {{ $status == 'present' ? 'checked' : '' }} {{ $isLockedForTeacher ? 'disabled' : '' }}>
                                    <span class="pill-content">
                                        <i class="fas fa-check"></i>
                                        <span>Hadir</span>
                                    </span>
                                </label>
                                <label class="status-pill status-sick" title="Sakit">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="sick" {{ $status == 'sick' ? 'checked' : '' }} {{ $isLockedForTeacher ? 'disabled' : '' }}>
                                    <span class="pill-content">
                                        <i class="fas fa-head-side-cough"></i>
                                        <span>Sakit</span>
                                    </span>
                                </label>
                                <label class="status-pill status-permission" title="Izin">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="permission" {{ $status == 'permission' ? 'checked' : '' }} {{ $isLockedForTeacher ? 'disabled' : '' }}>
                                    <span class="pill-content">
                                        <i class="fas fa-envelope-open-text"></i>
                                        <span>Izin</span>
                                    </span>
                                </label>
                                <label class="status-pill status-absent" title="Alpa / Tanpa Keterangan">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="absent" {{ $status == 'absent' ? 'checked' : '' }} {{ $isLockedForTeacher ? 'disabled' : '' }}>
                                    <span class="pill-content">
                                        <i class="fas fa-times"></i>
                                        <span>Alpa</span>
                                    </span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <input type="text" name="notes[{{ $student->id }}]" class="note-input-field note-input" placeholder="Tulis catatan jika izin/sakit..." value="{{ $note }}" {{ $isLockedForTeacher ? 'readonly' : '' }}>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px 20px; color: #64748b;">
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
            <div class="presensi-desktop-footer">
                <span style="font-size: 0.85rem; color: #64748b;">
                    @if($isLockedForTeacher)
                        Data presensi pada tanggal ini sudah tersimpan dan terkunci.
                    @else
                        Pastikan seluruh data kehadiran siswa sudah sesuai sebelum menyimpan.
                    @endif
                </span>
                @if($isLockedForTeacher)
                    <button type="button" class="btn-submit-main" disabled style="background: #94a3b8; border-color: #94a3b8; cursor: not-allowed; box-shadow: none;">
                        <i class="fas fa-lock"></i>
                        <span>Presensi Sudah Tersimpan (Terkunci)</span>
                    </button>
                @else
                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        @if(!empty($isAlreadySaved) && Auth::user()->role === 'admin')
                            <button type="button" onclick="confirmResetAttendance()" style="padding: 10px 18px; border-radius: 12px; background: #fff1f2; border: 1.5px solid #fecdd3; color: #e11d48; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                                <i class="fas fa-trash-alt"></i>
                                <span>Reset / Kosongkan Presensi</span>
                            </button>
                        @endif
                        <button type="submit" class="btn-submit-main">
                            <i class="fas fa-{{ !empty($isAlreadySaved) ? 'sync-alt' : 'save' }}"></i>
                            <span>{{ !empty($isAlreadySaved) ? 'Perbarui Presensi Pertemuan' : 'Simpan Presensi Pertemuan' }}</span>
                        </button>
                    </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sticky Dock Bar Khusus Layar HP -->
        @if($students->isNotEmpty())
        <div class="presensi-mobile-sticky-bar">
            @if($isLockedForTeacher)
                <button type="button" disabled style="background: #94a3b8; cursor: not-allowed; box-shadow: none;">
                    <i class="fas fa-lock"></i>
                    <span>Presensi Sudah Tersimpan (Terkunci)</span>
                </button>
            @else
                <div style="display: flex; gap: 8px; width: 100%;">
                    @if(!empty($isAlreadySaved) && Auth::user()->role === 'admin')
                        <button type="button" onclick="confirmResetAttendance()" style="flex: 0 0 auto; background: #fff1f2; border: 1.5px solid #fecdd3; color: #e11d48; padding: 0 14px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fas fa-trash-alt"></i>
                            <span>Reset</span>
                        </button>
                    @endif
                    <button type="submit" style="flex: 1;">
                        <i class="fas fa-{{ !empty($isAlreadySaved) ? 'sync-alt' : 'save' }}"></i>
                        <span>{{ !empty($isAlreadySaved) ? 'Perbarui' : 'Simpan' }} ({{ $students->count() }})</span>
                    </button>
                </div>
            @endif
        </div>
        @endif
    </form>
</div>

@if(!empty($isAlreadySaved) && Auth::user()->role === 'admin')
    <form id="form-reset-attendance" action="{{ route('attendance.destroy-date') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="eskul_id" value="{{ $eskul->id }}">
        <input type="hidden" name="date" value="{{ $date }}">
    </form>
@endif

<script>
    function confirmResetAttendance() {
        const targetDate = "{{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}";
        if (confirm(`Apakah Anda yakin ingin mengosongkan / mereset seluruh data absensi siswa tanggal ${targetDate}?\n\nSetelah dikosongkan, inputan presensi akan bersih dan guru/tutor dapat melakukan pengisian ulang dari awal.`)) {
            const form = document.getElementById('form-reset-attendance');
            if (form) {
                form.submit();
            }
        }
    }
    function setAllAttendance(status, defaultNote = '') {
        const radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);
        radios.forEach(r => {
            if (!r.disabled) {
                r.checked = true;
                r.dispatchEvent(new Event('change'));
            }
        });
        if (defaultNote) {
            const noteInputs = document.querySelectorAll('.note-input');
            noteInputs.forEach(n => {
                if (!n.readOnly && !n.value) n.value = defaultNote;
            });
        }
    }

    // Proteksi anti double click saat form dikirim
    document.getElementById('attendance-form')?.addEventListener('submit', function(e) {
        const submitBtns = this.querySelectorAll('button[type="submit"]');
        submitBtns.forEach(btn => {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        });
    });
</script>
@endsection
