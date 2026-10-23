@extends('layouts.app')

@section('title', 'Absensi Saya')
@section('page-title', 'Presensi Pembina Ekstrakurikuler')

@push('styles')
<style>
    /* Container Utama */
    .presence-my-container {
        max-width: 1000px;
        margin: 0 auto;
        padding-bottom: 50px;
    }

    /* 1. Hero / Header Card */
    .presence-my-hero {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white;
        padding: 22px 26px;
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .hero-tag-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(16, 185, 129, 0.18);
        color: #34d399;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        border: 1px solid rgba(52, 211, 153, 0.35);
        margin-bottom: 8px;
    }

    .hero-title-main {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
        color: #f8fafc;
        letter-spacing: -0.4px;
    }

    .hero-subtitle-desc {
        margin: 4px 0 0 0;
        color: #94a3b8;
        font-size: 0.88rem;
        line-height: 1.4;
    }

    .date-chip-live {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 8px 14px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: white;
    }

    .date-chip-live i {
        font-size: 1.3rem;
        color: #38bdf8;
    }

    .date-chip-label {
        font-size: 0.68rem;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
    }

    .date-chip-val {
        font-size: 0.92rem;
        font-weight: 800;
        color: #f8fafc;
    }

    /* 2. Main Action Card */
    .card-presence-action {
        background: white;
        border-radius: 18px;
        padding: 22px 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        margin-bottom: 22px;
    }

    .section-card-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-card-title i {
        color: #0284c7;
    }

    /* Status Sudah Absen */
    .success-attendance-box {
        background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
        border: 1.5px solid #a7f3d0;
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 16px;
    }

    .success-icon-badge {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #10b981;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
    }

    .success-info-content {
        flex: 1;
    }

    .success-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #065f46;
        margin: 0 0 6px 0;
    }

    .success-meta-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 0.88rem;
        color: #047857;
    }

    .pill-detail-success {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: white;
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: 700;
        border: 1px solid #d1fae5;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        max-width: 100%;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    /* Substitute Share Box */
    .substitute-share-card {
        background: #f0f9ff;
        border: 1.5px solid #bae6fd;
        border-radius: 16px;
        padding: 18px 20px;
        margin-top: 14px;
    }

    .share-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #0369a1;
        font-size: 0.98rem;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .share-card-desc {
        font-size: 0.85rem;
        color: #0284c7;
        margin: 0 0 14px 0;
        line-height: 1.45;
    }

    .share-buttons-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .btn-share-wa {
        background: #25d366;
        color: white;
        border: none;
        text-decoration: none;
        padding: 0 16px;
        height: 40px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
        transition: all 0.2s;
    }

    .btn-share-wa:hover {
        background: #1eb855;
        color: white;
        transform: translateY(-2px);
    }

    .btn-share-copy {
        background: #0284c7;
        color: white;
        border: none;
        padding: 0 16px;
        height: 40px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        transition: all 0.2s;
    }

    .btn-share-copy:hover {
        background: #0369a1;
        transform: translateY(-2px);
    }

    /* 3. Form Presensi: 3-Pills Symmetrical Status Selector */
    .status-segmented-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 16px;
    }

    .status-radio-option {
        cursor: pointer;
        position: relative;
        display: block;
        margin: 0;
    }

    .status-radio-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        pointer-events: none;
    }

    .status-pill-box {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 44px;
        padding: 0 10px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 700;
        border: 1.5px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        transition: all 0.2s ease;
        text-align: center;
        box-sizing: border-box;
    }

    .status-pill-box i {
        font-size: 1rem;
    }

    /* Checked States */
    .status-radio-option input[type="radio"]:checked + .pill-present {
        background: #ecfdf5;
        border-color: #10b981;
        color: #047857;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }

    .status-radio-option input[type="radio"]:checked + .pill-sick {
        background: #fef3c7;
        border-color: #f59e0b;
        color: #b45309;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
    }

    .status-radio-option input[type="radio"]:checked + .pill-permission {
        background: #e0f2fe;
        border-color: #0284c7;
        color: #0369a1;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    }

    /* Note Input */
    .form-group-note {
        position: relative;
        margin-bottom: 16px;
    }

    .input-note-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.95rem;
    }

    .form-control-note {
        width: 100%;
        padding: 10px 14px 10px 40px;
        font-size: 0.92rem;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        outline: none;
        transition: all 0.2s;
        box-sizing: border-box;
        background: #f8fafc;
    }

    .form-control-note:focus {
        background: white;
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }

    /* Substitute Section */
    .substitute-box-styled {
        background: #fffbeb;
        border: 1.5px dashed #f59e0b;
        padding: 16px;
        border-radius: 14px;
        margin-bottom: 16px;
        animation: fadeInSoft 0.25s ease-out;
    }

    @keyframes fadeInSoft {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .substitute-box-title {
        font-weight: 800;
        font-size: 0.9rem;
        color: #b45309;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .substitute-switch-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 12px;
    }

    .sub-switch-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #78350f;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(254, 243, 199, 0.6);
        padding: 8px 12px;
        border-radius: 10px;
        border: 1px solid #fde68a;
    }

    .sub-switch-label input {
        margin: 0;
    }

    .form-control-sub {
        width: 100%;
        padding: 10px 14px;
        font-size: 0.92rem;
        border-radius: 12px;
        border: 1.5px solid #f59e0b;
        background: white;
        outline: none;
        box-sizing: border-box;
    }

    .sub-info-hint {
        color: #d97706;
        display: block;
        margin-top: 10px;
        font-size: 0.78rem;
        line-height: 1.45;
    }

    /* Submit Button */
    .btn-submit-attendance {
        width: 100%;
        height: 44px;
        background: #0284c7;
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 800;
        font-size: 0.92rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        transition: all 0.2s ease;
    }

    .btn-submit-attendance:hover {
        background: #0369a1;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(2, 132, 199, 0.45);
    }

    .btn-submit-attendance:active {
        transform: translateY(0);
    }

    /* 4. Table Riwayat Presensi */
    .card-presence-history {
        background: white;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .history-card-header {
        padding: 16px 20px;
        background: #fafafa;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .history-heading {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .history-count-chip {
        font-size: 0.76rem;
        font-weight: 700;
        background: #e0f2fe;
        color: #0284c7;
        padding: 2px 8px;
        border-radius: 999px;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .history-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 12px 18px;
        border-bottom: 2px solid #e2e8f0;
    }

    .history-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .history-table tbody tr:hover {
        background: #f8fafc;
    }

    /* Status Badges */
    .history-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-present { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .badge-sick { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .badge-permission { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .badge-alpha { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    /* ========================================== */
    /* RESPONSIVE DESIGN FOR MOBILE PHONES        */
    /* ========================================== */
    @media (max-width: 768px) {
        .presence-my-container {
            padding: 0 2px 40px 2px;
        }

        .presence-my-hero {
            padding: 16px;
            border-radius: 16px;
            margin-bottom: 14px;
            gap: 12px;
        }

        .hero-title-main {
            font-size: 1.25rem;
        }

        .hero-subtitle-desc {
            font-size: 0.8rem;
        }

        .date-chip-live {
            width: 100%;
            box-sizing: border-box;
            padding: 8px 12px;
            border-radius: 10px;
        }

        .card-presence-action {
            padding: 14px 14px;
            border-radius: 16px;
            margin-bottom: 14px;
        }

        .section-card-title {
            font-size: 0.98rem;
            margin-bottom: 12px;
        }

        /* Success attendance box */
        .success-attendance-box {
            padding: 14px;
            border-radius: 14px;
            gap: 12px;
        }

        .success-icon-badge {
            width: 38px;
            height: 38px;
            font-size: 1.25rem;
            border-radius: 10px;
        }

        .success-title {
            font-size: 1rem;
        }

        .success-meta-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .pill-detail-success {
            font-size: 0.78rem;
            padding: 3px 8px;
        }

        /* Symmetrical Segmented Radio Pills */
        .status-segmented-grid {
            gap: 6px;
            margin-bottom: 12px;
        }

        .status-pill-box {
            height: 38px;
            padding: 0 4px;
            font-size: 0.8rem;
            border-radius: 10px;
            gap: 4px;
        }

        .status-pill-box i {
            font-size: 0.88rem;
        }

        .form-control-note {
            height: 38px;
            font-size: 0.85rem;
            padding: 6px 12px 6px 36px;
            border-radius: 10px;
        }

        .input-note-icon {
            left: 12px;
            font-size: 0.85rem;
        }

        .substitute-box-styled {
            padding: 12px;
            border-radius: 12px;
        }

        .substitute-switch-grid {
            grid-template-columns: 1fr;
            gap: 6px;
        }

        .sub-switch-label {
            font-size: 0.78rem;
            padding: 6px 10px;
        }

        .form-control-sub {
            height: 38px;
            font-size: 0.85rem;
            padding: 6px 10px;
            border-radius: 10px;
        }

        .btn-submit-attendance {
            height: 40px;
            font-size: 0.85rem;
            border-radius: 10px;
        }

        .share-buttons-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .btn-share-wa, 
        .btn-share-copy {
            height: 36px;
            padding: 0 10px;
            font-size: 0.78rem;
            border-radius: 10px;
            gap: 5px;
            white-space: nowrap;
        }

        /* History Table to Mobile Cards */
        .card-presence-history {
            border-radius: 16px;
        }

        .history-card-header {
            padding: 12px 14px;
        }

        .history-heading {
            font-size: 0.95rem;
        }

        .table-history-responsive {
            overflow: visible;
        }

        .history-table, 
        .history-table thead, 
        .history-table tbody, 
        .history-table tr, 
        .history-table td {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }

        .history-table thead {
            display: none;
        }

        .history-table tbody {
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .history-table tbody tr {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 14px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 6px;
            align-items: start;
            position: relative;
        }

        .history-table td {
            padding: 0 !important;
            border: none !important;
        }

        /* Date Strip (Cell 1) */
        .history-table td:nth-child(1) {
            grid-column: 1 / 2;
            grid-row: 1;
            font-weight: 700;
            font-size: 0.88rem;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 0;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        /* Status Badge (Cell 3) */
        .history-table td:nth-child(3) {
            grid-column: 2 / 3;
            grid-row: 1;
            position: static !important;
            align-self: start;
            justify-self: end;
            display: flex;
            align-items: flex-start;
            margin-left: 6px;
        }

        .history-status-badge {
            font-size: 0.72rem;
            padding: 3px 8px;
            gap: 4px;
            white-space: nowrap;
        }

        /* Time Clock (Cell 2) */
        .history-table td:nth-child(2) {
            grid-column: 1 / -1;
            grid-row: 2;
            font-size: 0.78rem;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Note & Substitute (Cell 4) */
        .history-table td:nth-child(4) {
            grid-column: 1 / -1;
            grid-row: 3;
            font-size: 0.8rem;
            color: #334155;
            background: #f8fafc;
            padding: 6px 10px !important;
            border-radius: 8px;
            border: 1px solid #f1f5f9;
            margin-top: 4px;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .history-table td:nth-child(4):empty {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="presence-my-container">

    <!-- 1. Hero / Header Banner -->
    <div class="presence-my-hero">
        <div>
            <div class="hero-tag-badge">
                <i class="fas fa-fingerprint"></i>
                <span>Presensi Pembina</span>
            </div>
            <h2 class="hero-title-main">Presensi Harian Eskul</h2>
            <p class="hero-subtitle-desc">
                Konfirmasi kehadiran Anda sebagai pembina ekstrakurikuler hari ini dengan cepat dan akurat.
            </p>
        </div>
        <div class="date-chip-live">
            <i class="fas fa-calendar-alt"></i>
            <div>
                <div class="date-chip-label">Hari Ini</div>
                <div class="date-chip-val">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
            </div>
        </div>
    </div>

    <!-- 2. Form / Card Presensi Hari Ini -->
    <div class="card-presence-action">
        <h3 class="section-card-title">
            <i class="fas fa-clock"></i>
            <span>Status Presensi Hari Ini</span>
        </h3>

        @if($todayAttendance)
            <!-- Status Sudah Absen -->
            <div class="success-attendance-box">
                <div class="success-icon-badge">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="success-info-content">
                    <h4 class="success-title">Presensi Berhasil Dicatat!</h4>
                    <div class="success-meta-row">
                        <span class="pill-detail-success">
                            @if($todayAttendance->status === 'present')
                                <i class="fas fa-check text-success"></i> Status: <strong>Hadir</strong>
                            @elseif($todayAttendance->status === 'sick')
                                <i class="fas fa-notes-medical text-warning"></i> Status: <strong>Sakit</strong>
                            @elseif($todayAttendance->status === 'permission')
                                <i class="fas fa-envelope-open-text text-info"></i> Status: <strong>Izin</strong>
                            @else
                                <i class="fas fa-times-circle text-danger"></i> Status: <strong>Alpha</strong>
                            @endif
                        </span>

                        <span class="pill-detail-success">
                            <i class="far fa-clock"></i> Jam Masuk: <strong>{{ $todayAttendance->clock_in_time }} WIB</strong>
                        </span>

                        @if($todayAttendance->substitute_name)
                            <span class="pill-detail-success" style="color: #b45309; border-color: #fde68a; background: #fffbeb;">
                                <i class="fas fa-user-friends"></i> Pengganti: <strong>{{ $todayAttendance->substitute_name }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bagikan Tautan Guru Pengganti (Khusus Sakit/Izin) -->
            @if($substituteToken && in_array($todayAttendance->status, ['sick', 'permission']))
                @php
                    $substituteUrl = route('substitute.attendance.show', $substituteToken->token);
                    $waText = urlencode("Assalamu'alaikum/Halo, berikut adalah tautan untuk mengisi absensi siswa eskul hari ini: " . $substituteUrl);
                @endphp
                <div class="substitute-share-card">
                    <div class="share-card-header">
                        <i class="fas fa-link"></i>
                        <span>Tautan Presensi Siswa Untuk Guru Pengganti</span>
                    </div>
                    <p class="share-card-desc">
                        Bagikan tautan ini ke guru pengganti agar mereka dapat mengisi presensi siswa secara langsung tanpa perlu login ke sistem SIMUTQIN.
                    </p>
                    <div class="share-buttons-grid">
                        <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="btn-share-wa">
                            <i class="fab fa-whatsapp"></i>
                            <span>Kirim ke WhatsApp</span>
                        </a>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $substituteUrl }}'); alert('Tautan presensi berhasil disalin ke clipboard!');" class="btn-share-copy">
                            <i class="fas fa-copy"></i>
                            <span>Salin Tautan</span>
                        </button>
                    </div>
                </div>
            @endif
        @else
            <!-- Form Presensi Belum Dilakukan -->
            <form action="{{ route('teacher-attendance.store') }}" method="POST">
                @csrf
                
                <!-- 3 Symmetrical Status Selector Pills -->
                <div class="status-segmented-grid">
                    <label class="status-radio-option">
                        <input type="radio" name="status" value="present" checked id="statusPresent">
                        <span class="status-pill-box pill-present">
                            <i class="fas fa-check-circle"></i>
                            <span>Hadir</span>
                        </span>
                    </label>

                    <label class="status-radio-option">
                        <input type="radio" name="status" value="sick" id="statusSick">
                        <span class="status-pill-box pill-sick">
                            <i class="fas fa-notes-medical"></i>
                            <span>Sakit</span>
                        </span>
                    </label>

                    <label class="status-radio-option">
                        <input type="radio" name="status" value="permission" id="statusPermission">
                        <span class="status-pill-box pill-permission">
                            <i class="fas fa-envelope-open-text"></i>
                            <span>Izin</span>
                        </span>
                    </label>
                </div>
                
                <!-- Catatan Opsional -->
                <div class="form-group-note">
                    <i class="far fa-comment-dots input-note-icon"></i>
                    <input type="text" name="note" class="form-control-note" placeholder="Catatan tambahan (opsional)...">
                </div>

                <!-- Bagian Guru Pengganti (Muncul saat Sakit / Izin) -->
                <div class="substitute-box-styled" id="substituteGroup" style="display: none;">
                    <div class="substitute-box-title">
                        <i class="fas fa-user-friends"></i>
                        <span>Informasi Guru Pengganti</span>
                    </div>

                    <div class="substitute-switch-grid">
                        <label class="sub-switch-label">
                            <input type="radio" name="substitute_type" value="registered" checked id="typeRegistered">
                            <span><i class="fas fa-user-check"></i> Guru Terdaftar di SIM</span>
                        </label>
                        <label class="sub-switch-label">
                            <input type="radio" name="substitute_type" value="manual" id="typeManual">
                            <span><i class="fas fa-keyboard"></i> Ketik Manual / Pihak Luar</span>
                        </label>
                    </div>

                    <div id="substituteUserSelect" style="margin-bottom: 8px;">
                        <select name="substitute_user_id" class="form-control-sub">
                            <option value="">-- Pilih Guru Pengganti --</option>
                            @foreach($teachersList as $t)
                                <option value="{{ $t->id }}">{{ $t->name }} {{ $t->eskul ? '('.$t->eskul->name.')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="substituteNameInput" style="display: none; margin-bottom: 8px;">
                        <input type="text" name="substitute_name" class="form-control-sub" placeholder="Ketik nama lengkap guru pengganti...">
                    </div>

                    <small class="sub-info-hint">
                        <i class="fas fa-info-circle"></i> Jika memilih guru terdaftar, eskul Anda otomatis muncul di akun mereka hari ini. Tautan presensi tanpa login juga otomatis dibuat untuk dibagikan.
                    </small>
                </div>

                <!-- Tombol Konfirmasi Presensi -->
                <button type="submit" class="btn-submit-attendance">
                    <i class="fas fa-fingerprint"></i>
                    <span>Konfirmasi Presensi Sekarang</span>
                </button>
            </form>
        @endif
    </div>

    <!-- 3. Tabel / Riwayat Absensi Saya -->
    <div class="card-presence-history">
        <div class="history-card-header">
            <h3 class="history-heading">
                <i class="fas fa-history text-muted"></i>
                <span>Riwayat Presensi Saya</span>
            </h3>
            <span class="history-count-chip">{{ $myAttendances->total() }} Sesi Tercatat</span>
        </div>

        <div class="table-history-responsive">
            <table class="history-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Tanggal</th>
                        <th style="width: 20%;">Waktu Masuk</th>
                        <th style="width: 20%; text-align: center;">Status</th>
                        <th style="width: 35%;">Catatan / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myAttendances as $attendance)
                        @php
                            $dateParsed = \Carbon\Carbon::parse($attendance->date)->locale('id');
                            $dateStr = $dateParsed->isoFormat('dddd, D MMMM Y');
                        @endphp
                        <tr>
                            <td>
                                <i class="far fa-calendar-alt text-muted me-1"></i>
                                <span>{{ $dateStr }}</span>
                            </td>
                            <td>
                                <i class="far fa-clock text-muted me-1"></i>
                                <span>{{ $attendance->clock_in_time ? $attendance->clock_in_time . ' WIB' : '-' }}</span>
                            </td>
                            <td style="text-align: center;">
                                @if($attendance->status == 'present')
                                    <span class="history-status-badge badge-present">
                                        <i class="fas fa-check-circle"></i> Hadir
                                    </span>
                                @elseif($attendance->status == 'sick')
                                    <span class="history-status-badge badge-sick">
                                        <i class="fas fa-notes-medical"></i> Sakit
                                    </span>
                                @elseif($attendance->status == 'permission')
                                    <span class="history-status-badge badge-permission">
                                        <i class="fas fa-envelope-open-text"></i> Izin
                                    </span>
                                @else
                                    <span class="history-status-badge badge-alpha">
                                        <i class="fas fa-times-circle"></i> Alpha
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($attendance->note))
                                    <div>{{ $attendance->note }}</div>
                                @endif
                                @if($attendance->substitute_name)
                                    <div style="font-size: 0.8rem; color: #b45309; margin-top: 3px;">
                                        <i class="fas fa-user-friends"></i> Pengganti: <strong>{{ $attendance->substitute_name }}</strong>
                                    </div>
                                @endif
                                @if(empty($attendance->note) && !$attendance->substitute_name)
                                    <span style="color: #cbd5e1;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #94a3b8; padding: 35px 20px;">
                                <i class="far fa-folder-open" style="font-size: 2rem; margin-bottom: 8px; display: block; color: #cbd5e1;"></i>
                                <span>Belum ada riwayat presensi yang tercatat.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($myAttendances->hasPages())
            <div style="padding: 14px 18px; border-top: 1px solid #f1f5f9; display: flex; justify-content: center;">
                {{ $myAttendances->links() }}
            </div>
        @endif
    </div>

</div>

<!-- JAVASCRIPT LOGIC FORM -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusRadios = document.querySelectorAll('input[name="status"]');
        const typeRegistered = document.getElementById('typeRegistered');
        const typeManual = document.getElementById('typeManual');
        const substituteUserSelect = document.getElementById('substituteUserSelect');
        const substituteNameInput = document.getElementById('substituteNameInput');
        const substituteGroup = document.getElementById('substituteGroup');

        function toggleSubstituteFields() {
            if (typeRegistered && typeRegistered.checked) {
                if (substituteUserSelect) substituteUserSelect.style.display = 'block';
                if (substituteNameInput) substituteNameInput.style.display = 'none';
            } else if (typeManual && typeManual.checked) {
                if (substituteUserSelect) substituteUserSelect.style.display = 'none';
                if (substituteNameInput) substituteNameInput.style.display = 'block';
            }
        }

        if (typeRegistered) typeRegistered.addEventListener('change', toggleSubstituteFields);
        if (typeManual) typeManual.addEventListener('change', toggleSubstituteFields);

        if (statusRadios.length > 0 && substituteGroup) {
            statusRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'sick' || this.value === 'permission') {
                        substituteGroup.style.display = 'block';
                    } else {
                        substituteGroup.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection
