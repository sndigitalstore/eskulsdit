@extends('layouts.app')

@section('title', 'Munaqisy & Munaqosah Qur\'an')
@section('page-title', 'Munaqisy & Munaqosah Qur\'an')

@push('styles')
<style>
    /* Styling Khusus Munaqosah Modern & Responsif */
    .munaqosah-hero {
        background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0d9488 100%);
        border-radius: 22px;
        padding: 26px 30px;
        color: white;
        margin-bottom: 25px;
        box-shadow: 0 10px 25px -5px rgba(6, 95, 70, 0.3);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 18px;
        position: relative;
        overflow: hidden;
    }
    .munaqosah-hero-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        z-index: 2;
    }
    .munaqosah-hero-btn {
        font-weight: 800;
        padding: 10px 18px;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.88rem;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
    }
    .munaqosah-hero-btn:hover {
        transform: translateY(-2px);
    }
    .munaqosah-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 1rem;
        margin-bottom: 25px;
    }
    .munaqosah-kpi-card {
        background: white;
        border-radius: 18px;
        padding: 18px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
    }
    .munaqosah-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    }
    .munaqosah-tabs-bar {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }
    .munaqosah-tabs-bar::-webkit-scrollbar {
        height: 4px;
    }
    .munaqosah-tabs-bar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .munaqosah-tab-btn {
        padding: 9px 16px;
        border-radius: 12px;
        font-size: 0.86rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        transition: all 0.2s;
    }

    /* Desktop vs Mobile display */
    .munaqosah-desktop-table {
        display: block;
        overflow-x: auto;
    }
    .munaqosah-mobile-cards {
        display: none;
    }

    /* Modal Responsive */
    .munaqosah-modal-card {
        background: white;
        border-radius: 22px;
        max-width: 580px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    }

    /* Form Dropdown Profesional Ala Surah Picker */
    .form-control-pro, select.form-control-pro {
        height: 42px;
        background-color: #ffffff;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23059669' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 14px 14px;
        padding: 8px 36px 8px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        font-size: 0.86rem;
        font-weight: 700;
        color: #0f172a;
        outline: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    input[type="date"].form-control-pro {
        background-image: none;
        padding-right: 14px;
        cursor: pointer;
    }
    .form-control-pro:hover {
        border-color: #94a3b8;
        background-color: #f8fafc;
    }
    .form-control-pro:focus {
        border-color: #059669;
        background-color: #ffffff;
        box-shadow: 0 0 0 3.5px rgba(5, 150, 105, 0.15);
    }

    @media (max-width: 768px) {
        .munaqosah-hero {
            padding: 20px 18px;
            border-radius: 18px;
        }
        .munaqosah-hero h2 {
            font-size: 1.4rem !important;
        }
        .munaqosah-hero-actions {
            width: 100%;
            flex-direction: column;
        }
        .munaqosah-hero-btn {
            width: 100%;
            justify-content: center;
            padding: 11px 16px;
        }
        .munaqosah-kpi-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
        .munaqosah-kpi-card {
            padding: 14px 16px;
        }
        .munaqosah-kpi-card h3 {
            font-size: 1.5rem !important;
        }
        .munaqosah-kpi-card .kpi-icon {
            width: 38px !important;
            height: 38px !important;
            font-size: 1.1rem !important;
        }
        .munaqosah-desktop-table {
            display: none !important;
        }
        .munaqosah-mobile-cards {
            display: flex !important;
            flex-direction: column;
            gap: 14px;
        }
        .munaqosah-filter-form {
            width: 100%;
            flex-direction: column;
            align-items: stretch !important;
        }
        .munaqosah-filter-form select,
        .munaqosah-filter-form input {
            width: 100% !important;
        }
        .munaqosah-modal-card {
            max-width: 95% !important;
        }
    }

    @media (max-width: 480px) {
        .munaqosah-kpi-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div style="padding-bottom: 40px;">

    <!-- Hero Header Modern -->
    <div class="munaqosah-hero">
        <div style="z-index: 2;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; flex-wrap: wrap;">
                <span style="background: rgba(255,255,255,0.2); font-size: 0.76rem; font-weight: 800; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.05em; backdrop-filter: blur(4px);">
                    <i class="fas fa-award"></i> Standar Penjamin Mutu Qur'an
                </span>
                <span style="background: rgba(255,255,255,0.15); font-size: 0.76rem; font-weight: 700; padding: 4px 12px; border-radius: 20px;">
                    Tahun Ajaran {{ $activeYear->name ?? '-' }}
                </span>
                @if(!$isAdminOrCurriculum)
                <span style="background: rgba(16, 185, 129, 0.35); border: 1px solid rgba(255,255,255,0.3); font-size: 0.76rem; font-weight: 800; padding: 4px 12px; border-radius: 20px;">
                    <i class="fas fa-user-check"></i> Dewan Penguji Aktif
                </span>
                @endif
            </div>
            @if($isAdminOrCurriculum)
                <h2 style="font-size: 1.85rem; font-weight: 800; margin: 0 0 8px 0; letter-spacing: -0.5px;">
                    Munaqisy & Evaluasi Munaqosah
                </h2>
                <p style="margin: 0; font-size: 0.92rem; opacity: 0.92; max-width: 680px; line-height: 1.55;">
                    Kelola pendaftaran siswa tuntas juz tahfidz & jilid tilawati, manajemen mentor penguji (munaqisy), penilaian kelulusan berstandar, serta pemindahan otomatis ke jenjang lanjutan.
                </p>
            @else
                <h2 style="font-size: 1.85rem; font-weight: 800; margin: 0 0 8px 0; letter-spacing: -0.5px;">
                    Rekapan Peserta Munaqosah
                </h2>
                <p style="margin: 0; font-size: 0.92rem; opacity: 0.92; max-width: 680px; line-height: 1.55;">
                    Daftar siswa peserta ujian munaqosah tahfidz dan tilawati yang siap untuk diuji dan dinilai kelulusan serta kualitas bacaannya.
                </p>
            @endif
        </div>
        @if($isAdminOrCurriculum)
        <div class="munaqosah-hero-actions">
            <a href="{{ route('munaqosah.register') }}" class="munaqosah-hero-btn" style="background: #ffffff; color: #065f46; box-shadow: 0 4px 15px rgba(0,0,0,0.12);">
                <i class="fas fa-plus-circle" style="color: #059669;"></i> Ajukan Siswa Munaqosah
            </a>
            <button type="button" onclick="openBulkTilawatiMunaqosahModal()" class="munaqosah-hero-btn" style="background: #f59e0b; color: white; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);">
                <i class="fas fa-layer-group"></i> + Daftar 1 Kelompok Tilawati
            </button>
            <button type="button" onclick="shareWhatsAppRegistrationLink()" class="munaqosah-hero-btn" style="background: #25d366; color: white; box-shadow: 0 4px 15px rgba(37,211,102,0.35);">
                <i class="fab fa-whatsapp"></i> Bagikan Link Online (WA)
            </button>
            <a href="{{ route('munaqosah.print-recap', ['year_id' => $selectedYearId, 'status' => $statusFilter, 'type' => $typeFilter]) }}" target="_blank" class="munaqosah-hero-btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.35); backdrop-filter: blur(4px);">
                <i class="fas fa-print"></i> Cetak Rekap
            </a>
        </div>
        @endif
        <i class="fas fa-quran" style="position: absolute; right: -20px; bottom: -30px; font-size: 11rem; color: rgba(255,255,255,0.06); z-index: 1;"></i>
    </div>

    <!-- Banner Pop-up Otomatis Hasil Pengujian Siap Diteruskan ke Grup WA -->
    @if(session('munaqosah_group_wa'))
        @php
            $waData = session('munaqosah_group_wa');
        @endphp
        <div id="autoWaShareBanner" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 2.5px solid #10b981; border-radius: 18px; padding: 20px 24px; margin-bottom: 24px; box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.25); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 52px; height: 52px; border-radius: 16px; background: #25D366; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; flex-shrink: 0; box-shadow: 0 4px 14px rgba(37, 211, 102, 0.4);">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="background: {{ $waData['status'] === 'passed' ? '#10b981' : '#ef4444' }}; color: white; font-weight: 800; font-size: 0.76rem; padding: 2px 10px; border-radius: 20px;">
                            {{ $waData['status_label'] }} (Nilai: {{ $waData['final_score'] }})
                        </span>
                        <span style="font-weight: 700; font-size: 0.8rem; color: #047857;">Munaqosah {{ $waData['type'] }}</span>
                    </div>
                    <h3 style="margin: 4px 0 2px 0; font-size: 1.25rem; font-weight: 900; color: #064e3b;">
                        Hasil Pengujian Ananda {{ $waData['student_name'] }} (Kelas {{ $waData['class'] }})
                    </h3>
                    <p style="margin: 0; font-size: 0.85rem; color: #047857;">
                        Rekapan resmi telah terbit! Klik tombol di samping untuk <strong>sekali klik langsung membuka WhatsApp</strong> dan meneruskan rekapan ke grup WA.
                    </p>
                </div>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ $waData['wa_url'] }}" target="_blank" 
                   style="background: #25D366; color: white; padding: 11px 22px; border-radius: 12px; font-size: 0.92rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(37, 211, 102, 0.4);">
                    <i class="fab fa-whatsapp" style="font-size: 1.2rem;"></i> Kirim ke Grup WA Sekarang
                </a>
                <button type="button" onclick="openShareWaModal({{ json_encode($waData['message']) }}, '{{ addslashes($waData['student_name']) }}')"
                        style="background: white; border: 1.5px solid #a7f3d0; color: #065f46; padding: 11px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-eye"></i> Lihat & Salin Pesan
                </button>
            </div>
        </div>
    @endif

    <!-- Alert Success / Error -->
    @if(session('success'))
        <div style="background: #ecfdf5; border-left: 5px solid #10b981; padding: 14px 18px; border-radius: 14px; margin-bottom: 22px; color: #065f46; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);">
            <div style="display: flex; align-items: center; gap: 10px; font-weight: 600;">
                <i class="fas fa-check-circle" style="font-size: 1.25rem; color: #10b981;"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #065f46; font-size: 1.1rem; cursor: pointer;">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; border-left: 5px solid #ef4444; padding: 14px 18px; border-radius: 14px; margin-bottom: 22px; color: #991b1b; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);">
            <div style="display: flex; align-items: center; gap: 10px; font-weight: 600;">
                <i class="fas fa-exclamation-circle" style="font-size: 1.25rem; color: #ef4444;"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #991b1b; font-size: 1.1rem; cursor: pointer;">&times;</button>
        </div>
    @endif

    @if($isAdminOrCurriculum)
    <!-- KPI Metric Cards Simetris (Khusus Admin & Kurikulum) -->
    <div class="munaqosah-kpi-grid">
        <!-- Total Registrations -->
        <div class="munaqosah-kpi-card">
            <div>
                <span style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.03em;">
                    Total Pendaftar {{ ($dateFilter === $todayDate) ? 'Hari Ini' : '' }}
                </span>
                <h3 style="font-size: 1.85rem; font-weight: 800; color: #0f172a; margin: 4px 0 0 0;">{{ $totalCount }}</h3>
            </div>
            <div class="kpi-icon" style="width: 48px; height: 48px; border-radius: 14px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>

        <!-- Menunggu Ujian -->
        <div class="munaqosah-kpi-card" style="border-color: #fde68a;">
            <div>
                <span style="font-size: 0.78rem; font-weight: 700; color: #d97706; text-transform: uppercase; letter-spacing: 0.03em;">
                    Menunggu Ujian {{ ($dateFilter === $todayDate) ? 'Hari Ini' : '' }}
                </span>
                <h3 style="font-size: 1.85rem; font-weight: 800; color: #b45309; margin: 4px 0 0 0;">{{ $pendingCount + $scheduledCount }}</h3>
            </div>
            <div class="kpi-icon" style="width: 48px; height: 48px; border-radius: 14px; background: #fffbeb; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>

        <!-- Lulus Munaqosah -->
        <div class="munaqosah-kpi-card" style="border-color: #a7f3d0; background: linear-gradient(to bottom, #ffffff, #f0fdf4);">
            <div>
                <span style="font-size: 0.78rem; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 0.03em;">
                    Lulus {{ ($dateFilter === $todayDate) ? 'Hari Ini' : '' }}
                </span>
                <h3 style="font-size: 1.85rem; font-weight: 800; color: #047857; margin: 4px 0 0 0;">{{ $passedCount }}</h3>
            </div>
            <div class="kpi-icon" style="width: 48px; height: 48px; border-radius: 14px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
                <i class="fas fa-check-double"></i>
            </div>
        </div>

        <!-- Belum Lulus / Mengulang -->
        <div class="munaqosah-kpi-card" style="border-color: #fecaca;">
            <div>
                <span style="font-size: 0.78rem; font-weight: 700; color: #dc2626; text-transform: uppercase; letter-spacing: 0.03em;">
                    Belum Lulus {{ ($dateFilter === $todayDate) ? 'Hari Ini' : '' }}
                </span>
                <h3 style="font-size: 1.85rem; font-weight: 800; color: #b91c1c; margin: 4px 0 0 0;">{{ $failedCount }}</h3>
            </div>
            <div class="kpi-icon" style="width: 48px; height: 48px; border-radius: 14px; background: #fef2f2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
                <i class="fas fa-redo-alt"></i>
            </div>
        </div>

        <!-- Dewan Penguji (Munaqisy) - Admin/Kurikulum -->
        <div class="munaqosah-kpi-card" style="border-color: #c7d2fe;">
            <div>
                <span style="font-size: 0.78rem; font-weight: 700; color: #6366f1; text-transform: uppercase; letter-spacing: 0.03em;">Mentor Penguji</span>
                <h3 style="font-size: 1.85rem; font-weight: 800; color: #4f46e5; margin: 4px 0 0 0;">{{ $examinersCount }}</h3>
            </div>
            <div class="kpi-icon" style="width: 48px; height: 48px; border-radius: 14px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
    </div>
    @endif

    <!-- Navigation Tabs & Filters Modern & Responsif -->
    <div style="background: white; border-radius: 20px; padding: 18px 20px; border: 1px solid #e2e8f0; margin-bottom: 22px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 14px;">
        @if($isAdminOrCurriculum)
        <div class="munaqosah-tabs-bar">
            <a href="{{ route('munaqosah.index', ['tab' => 'recap', 'status' => 'all', 'year_id' => $selectedYearId, 'date' => $dateFilter]) }}" 
               class="munaqosah-tab-btn"
               style="{{ $activeTab === 'recap' && $statusFilter === 'all' && $examinerFilter !== 'me' ? 'background: #047857; color: white;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="fas fa-list-alt"></i> {{ ($dateFilter === $todayDate) ? 'Peserta Terdaftar Hari Ini' : 'Semua Rekapan Data' }} ({{ $totalCount }})
            </a>
            <a href="{{ route('munaqosah.index', ['tab' => 'recap', 'status' => 'passed', 'year_id' => $selectedYearId, 'date' => $dateFilter]) }}" 
               class="munaqosah-tab-btn"
               style="{{ $statusFilter === 'passed' ? 'background: #10b981; color: white;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="fas fa-check-circle"></i> Sudah Lulus ({{ $passedCount }})
            </a>
            <a href="{{ route('munaqosah.index', ['tab' => 'recap', 'status' => 'failed', 'year_id' => $selectedYearId, 'date' => $dateFilter]) }}" 
               class="munaqosah-tab-btn"
               style="{{ $statusFilter === 'failed' ? 'background: #ef4444; color: white;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="fas fa-times-circle"></i> Belum Lulus ({{ $failedCount }})
            </a>
            @if($examinerRecord || $myExamsCount > 0)
            <a href="{{ route('munaqosah.index', ['tab' => 'recap', 'examiner' => $examinerFilter === 'me' ? 'all' : 'me', 'year_id' => $selectedYearId, 'date' => $dateFilter]) }}" 
               class="munaqosah-tab-btn"
               style="{{ $examinerFilter === 'me' ? 'background: #4338ca; color: white;' : 'background: #eef2ff; color: #4338ca; border: 1.5px solid #c7d2fe;' }}">
                <i class="fas fa-user-check"></i> Peserta Ujian Saya ({{ $myExamsCount }})
            </a>
            @endif

            <a href="{{ route('munaqosah.index', ['tab' => 'schedules', 'year_id' => $selectedYearId, 'date' => $dateFilter]) }}" 
               class="munaqosah-tab-btn"
               style="{{ $activeTab === 'schedules' ? 'background: #0284c7; color: white;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="fas fa-calendar-check"></i> Jadwal & Ketersediaan Penguji ({{ $schedulesCount }})
            </a>
            <a href="{{ route('munaqosah.index', ['tab' => 'examiners', 'year_id' => $selectedYearId, 'date' => $dateFilter]) }}" 
               class="munaqosah-tab-btn"
               style="{{ $activeTab === 'examiners' ? 'background: #4f46e5; color: white;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="fas fa-user-graduate"></i> Dewan Penguji ({{ $examinersCount }})
            </a>
        </div>
        @endif

        <!-- Filter Form Simetris & Responsif -->
        <form method="GET" action="{{ route('munaqosah.index') }}" class="munaqosah-filter-form" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center; {{ $isAdminOrCurriculum ? 'border-top: 1px solid #f1f5f9; padding-top: 12px;' : '' }}">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <input type="hidden" name="examiner" value="{{ $examinerFilter }}">

            <!-- Filter Tanggal Masuk / Ujian (Hari Ini vs Kalender) -->
            <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                <input type="date" name="date" value="{{ $dateFilter !== 'all' ? $dateFilter : '' }}" onchange="this.form.submit()" 
                       class="form-control-pro" title="Pilih Tanggal Masuk / Ujian" style="min-width: 145px;">
                @if($dateFilter === $todayDate)
                    <span style="background: #ecfdf5; color: #047857; border: 1.5px solid #a7f3d0; padding: 7px 12px; border-radius: 10px; font-size: 0.8rem; font-weight: 800; white-space: nowrap; display: inline-flex; align-items: center; gap: 5px;">
                        <i class="fas fa-calendar-day"></i> Hari Ini
                    </span>
                @else
                    <a href="{{ route('munaqosah.index', ['tab' => $activeTab, 'date' => $todayDate, 'year_id' => $selectedYearId, 'status' => $statusFilter, 'type' => $typeFilter]) }}" 
                       style="background: #f1f5f9; color: #334155; padding: 8px 12px; border-radius: 10px; font-size: 0.82rem; font-weight: 700; text-decoration: none; white-space: nowrap; border: 1px solid #cbd5e1;">
                        <i class="far fa-calendar-check"></i> Hari Ini
                    </a>
                @endif
                @if($dateFilter !== 'all')
                    <a href="{{ route('munaqosah.index', ['tab' => $activeTab, 'date' => 'all', 'year_id' => $selectedYearId, 'status' => $statusFilter, 'type' => $typeFilter]) }}" 
                       style="background: #f8fafc; color: #64748b; border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 10px; font-size: 0.82rem; font-weight: 700; text-decoration: none; white-space: nowrap;">
                        Semua Tanggal
                    </a>
                @endif
            </div>

            <!-- Tipe Dropdown -->
            <select name="type" onchange="this.form.submit()" class="form-control-pro" style="flex-grow: 1;">
                <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>Semua Tipe (Tilawati & Tahfidz)</option>
                <option value="tahfidz" {{ $typeFilter === 'tahfidz' ? 'selected' : '' }}>Hafalan Tahfidz</option>
                <option value="tilawati" {{ $typeFilter === 'tilawati' ? 'selected' : '' }}>Tilawati Al-Qur'an</option>
            </select>

            <!-- Tahun Pelajaran Dropdown -->
            <select name="year_id" onchange="this.form.submit()" class="form-control-pro" style="flex-grow: 1;">
                @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ $selectedYearId == $ay->id ? 'selected' : '' }}>
                        {{ $ay->name }} (Semester {{ $ay->active_semester }})
                    </option>
                @endforeach
            </select>

            <div style="position: relative; flex-grow: 2; min-width: 180px;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama siswa / kelas..." 
                       class="form-control-pro" style="padding-left: 36px; width: 100%; box-sizing: border-box;">
                <i class="fas fa-search" style="position: absolute; left: 12px; top: 12px; color: #94a3b8; font-size: 0.85rem;"></i>
            </div>

            @if($search || $typeFilter !== 'all' || $statusFilter !== 'all' || ($dateFilter && $dateFilter !== 'all' && $dateFilter !== $todayDate))
                <a href="{{ route('munaqosah.index', ['tab' => $activeTab, 'date' => $isExaminerOnly ? $todayDate : 'all']) }}" style="color: #ef4444; font-size: 0.84rem; font-weight: 800; text-decoration: none; padding: 8px 14px; border-radius: 10px; background: #fef2f2; display: inline-flex; align-items: center; gap: 4px; border: 1px solid #fecaca;">
                    <i class="fas fa-undo"></i> Reset Filter
                </a>
            @endif
        </form>
    </div>

    @if($isAdminOrCurriculum && $activeTab === 'examiners')
        {{-- TAB DEWAN PENGUJI (MUNAQISY) --}}
        <div style="background: white; border-radius: 20px; padding: 25px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                        Daftar Mentor Penguji (Dewan Munaqisy)
                    </h3>
                    <p style="color: #64748b; font-size: 0.86rem; margin: 0;">
                        Guru dan asatidz yang memiliki wewenang untuk menguji dan menilai kenaikan juz/jilid tilawati siswa.
                    </p>
                </div>
                @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                    <button type="button" onclick="openExaminerModal()" style="background: #4f46e5; color: white; border: none; font-weight: 800; padding: 10px 18px; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);">
                        <i class="fas fa-user-plus"></i> Tambah Penguji Baru
                    </button>
                @endif
            </div>

            <!-- Desktop Table View -->
            <div class="munaqosah-desktop-table">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">No</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Nama Penguji</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Spesialisasi</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">No Sertifikat / SK</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Kontak WhatsApp</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Total Siswa Diuji</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Status</th>
                            @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                                <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examiners as $index => $ex)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <td style="padding: 12px 14px; color: #64748b; font-weight: 600;">{{ $index + 1 }}</td>
                                <td style="padding: 12px 14px;">
                                    <div style="font-weight: 700; color: #0f172a;">{{ $ex->user->name ?? '-' }}</div>
                                    <div style="font-size: 0.76rem; color: #64748b;">{{ $ex->user->email ?? '-' }}</div>
                                </td>
                                <td style="padding: 12px 14px;">
                                    @if($ex->specialization === 'both')
                                        <span style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 8px; font-size: 0.76rem; font-weight: 700;">
                                             Tahfidz & Tilawati
                                        </span>
                                    @elseif($ex->specialization === 'tahfidz')
                                        <span style="background: #ecfdf5; color: #047857; padding: 4px 10px; border-radius: 8px; font-size: 0.76rem; font-weight: 700;">
                                             Khusus Tahfidz
                                        </span>
                                    @else
                                        <span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 8px; font-size: 0.76rem; font-weight: 700;">
                                             Khusus Tilawati
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; color: #475569; font-family: monospace; font-size: 0.84rem;">
                                    {{ $ex->certificate_number ?? '-' }}
                                </td>
                                <td style="padding: 12px 14px; color: #475569;">
                                    @if($ex->phone || ($ex->user && $ex->user->phone))
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ex->phone ?? $ex->user->phone) }}" target="_blank" style="color: #059669; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fab fa-whatsapp"></i> {{ $ex->phone ?? $ex->user->phone }}
                                        </a>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; text-align: center; font-weight: 800; color: #0f172a;">
                                    {{ $ex->registrations_count }} Siswa
                                </td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    @if($ex->status === 'active')
                                        <span style="background: #ecfdf5; color: #059669; font-size: 0.75rem; font-weight: 800; padding: 3px 10px; border-radius: 20px;">
                                            Aktif
                                        </span>
                                    @else
                                        <span style="background: #f1f5f9; color: #64748b; font-size: 0.75rem; font-weight: 800; padding: 3px 10px; border-radius: 20px;">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                                    <td style="padding: 12px 14px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center;">
                                            <form method="POST" action="{{ route('munaqosah.examiners.toggle', $ex->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" title="{{ $ex->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}" 
                                                        style="background: #f8fafc; border: 1px solid #cbd5e1; color: #475569; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fas {{ $ex->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('munaqosah.examiners.destroy', $ex->id) }}" onsubmit="return confirm('Hapus penguji ini?')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Penguji" 
                                                        style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 35px; text-align: center; color: #94a3b8;">
                                    <i class="fas fa-user-slash" style="font-size: 2.5rem; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                                    Belum ada mentor penguji (munaqisy) yang didaftarkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View Dewan Penguji -->
            <div class="munaqosah-mobile-cards">
                @forelse($examiners as $index => $ex)
                    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 42px; height: 42px; border-radius: 12px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem;">
                                    {{ strtoupper(substr($ex->user->name ?? 'P', 0, 2)) }}
                                </div>
                                <div>
                                    <h4 style="margin: 0; font-size: 0.96rem; font-weight: 800; color: #0f172a;">{{ $ex->user->name ?? '-' }}</h4>
                                    <span style="font-size: 0.76rem; color: #64748b;">{{ $ex->user->email ?? '-' }}</span>
                                </div>
                            </div>
                            <div>
                                @if($ex->status === 'active')
                                    <span style="background: #ecfdf5; color: #059669; font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px;">
                                        Aktif
                                    </span>
                                @else
                                    <span style="background: #f1f5f9; color: #64748b; font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px;">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div style="background: #f8fafc; border-radius: 12px; padding: 10px 12px; margin-bottom: 12px; font-size: 0.82rem; display: flex; flex-direction: column; gap: 6px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #64748b;">Spesialisasi:</span>
                                <div>
                                    @if($ex->specialization === 'both')
                                        <span style="background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: 0.72rem;">Tahfidz & Tilawati</span>
                                    @elseif($ex->specialization === 'tahfidz')
                                        <span style="background: #ecfdf5; color: #047857; padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: 0.72rem;">Khusus Tahfidz</span>
                                    @else
                                        <span style="background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: 0.72rem;">Khusus Tilawati</span>
                                    @endif
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #64748b;">No Sertifikat / SK:</span>
                                <span style="font-family: monospace; font-weight: 600; color: #334155;">{{ $ex->certificate_number ?? '-' }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #64748b;">Total Siswa Diuji:</span>
                                <span style="font-weight: 800; color: #0f172a;">{{ $ex->registrations_count }} Siswa</span>
                            </div>
                            @if($ex->phone || ($ex->user && $ex->user->phone))
                                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #e2e8f0; padding-top: 6px; margin-top: 2px;">
                                    <span style="color: #64748b;">WhatsApp:</span>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ex->phone ?? $ex->user->phone) }}" target="_blank" style="color: #059669; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fab fa-whatsapp"></i> {{ $ex->phone ?? $ex->user->phone }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <form method="POST" action="{{ route('munaqosah.examiners.toggle', $ex->id) }}" style="flex: 1;">
                                    @csrf
                                    <button type="submit" style="width: 100%; padding: 8px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #334155; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                        <i class="fas {{ $ex->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                        <span>{{ $ex->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('munaqosah.examiners.destroy', $ex->id) }}" onsubmit="return confirm('Hapus penguji ini?')" style="flex: 1;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="width: 100%; padding: 8px; border-radius: 10px; border: 1px solid #fecaca; background: #fef2f2; color: #dc2626; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div style="background: white; border: 1px dashed #cbd5e1; border-radius: 16px; padding: 30px; text-align: center; color: #94a3b8;">
                        <i class="fas fa-user-slash" style="font-size: 2.2rem; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                        Belum ada mentor penguji yang didaftarkan.
                    </div>
                @endforelse
            </div>
        </div>
    @elseif($isAdminOrCurriculum && $activeTab === 'schedules')
        {{-- TAB JADWAL & KETERSEDIAAN PENGUJI (MUNAQISY) --}}
        <div style="background: white; border-radius: 20px; padding: 25px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                        Jadwal & Ketersediaan Penguji (Munaqisy)
                    </h3>
                    <p style="color: #64748b; font-size: 0.86rem; margin: 0;">
                        Atur jadwal ketersediaan hari, jam, dan kuota para penguji. Jadwal ini akan muncul otomatis di formulir pendaftaran siswa.
                    </p>
                </div>
                @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                    <button type="button" onclick="openAddScheduleModal()" style="background: #0284c7; padding: 10px 18px; border-radius: 12px; color: white; font-weight: 800; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);">
                        <i class="fas fa-calendar-plus"></i> + Tambah Jadwal Penguji
                    </button>
                @endif
            </div>

            <!-- Desktop Table View -->
            <div class="munaqosah-desktop-table">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; width: 45px;">No</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Nama Penguji (Munaqisy)</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Hari & Waktu</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Ruangan / Tempat</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Kategori</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Ketersediaan / Kuota</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Siswa Terdaftar di Sesi Ini</th>
                            @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                                <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $index => $sch)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <td style="padding: 12px 14px; color: #64748b; font-weight: 600;">{{ $index + 1 }}</td>
                                <td style="padding: 12px 14px;">
                                    <div style="font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <span>{{ $sch->examiner?->user?->name ?? 'Penguji' }}</span>
                                    </div>
                                    @if($sch->examiner?->phone)
                                        <div style="font-size: 0.74rem; color: #64748b; margin-left: 40px;">
                                            <i class="fab fa-whatsapp" style="color: #10b981;"></i> {{ $sch->examiner->phone }}
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px;">
                                    <div style="font-weight: 800; color: #0369a1;">
                                        <i class="far fa-calendar-alt"></i> Hari {{ $sch->day_name }}
                                        @if($sch->date)
                                             <span style="font-weight: 500; font-size: 0.78rem; color: #64748b;">({{ $sch->date->format('d/m/Y') }})</span>
                                        @endif
                                    </div>
                                    <div style="font-size: 0.82rem; color: #334155; font-weight: 600;">
                                        <i class="far fa-clock"></i> Pukul {{ $sch->time_start }} s/d {{ $sch->time_end }} WIB
                                    </div>
                                </td>
                                <td style="padding: 12px 14px; color: #475569;">
                                    <i class="fas fa-map-marker-alt" style="color: #94a3b8; margin-right: 4px;"></i>
                                    {{ $sch->room_location ?? 'Ruang Munaqosah' }}
                                </td>
                                <td style="padding: 12px 14px;">
                                    @if($sch->exam_type === 'all')
                                        <span style="background: #f1f5f9; color: #475569; padding: 3px 8px; border-radius: 6px; font-size: 0.74rem; font-weight: 700;">
                                            Semua
                                        </span>
                                    @elseif($sch->exam_type === 'tahfidz')
                                        <span style="background: #ecfdf5; color: #047857; padding: 3px 8px; border-radius: 6px; font-size: 0.74rem; font-weight: 700;">
                                            Tahfidz
                                        </span>
                                    @else
                                        <span style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 6px; font-size: 0.74rem; font-weight: 700;">
                                            Tilawati
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    @if($sch->is_full)
                                        <span style="background: #ecfdf5; color: #047857; font-weight: 800; padding: 5px 12px; border-radius: 20px; font-size: 0.78rem; border: 1.5px solid #10b981; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-check-circle" style="color: #10b981;"></i> Sudah Terpenuhi ({{ $sch->used_slots }}/{{ $sch->quota }})
                                        </span>
                                    @else
                                        <span style="background: #eff6ff; color: #1e40af; font-weight: 800; padding: 5px 12px; border-radius: 20px; font-size: 0.78rem; border: 1.5px solid #60a5fa; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-clock" style="color: #3b82f6;"></i> Tersedia ({{ $sch->used_slots }}/{{ $sch->quota }})
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px;">
                                    @if($sch->registrations->isNotEmpty())
                                        @foreach($sch->registrations as $reg)
                                            <div style="font-size: 0.8rem; color: #0f172a; font-weight: 700;">
                                                <i class="fas fa-user-check" style="color: #10b981; margin-right: 4px;"></i>
                                                {{ $reg->student->name ?? 'Siswa' }} 
                                                <span style="font-weight: normal; color: #64748b; font-size: 0.75rem;">(Kelas {{ $reg->student->class ?? '-' }} • {{ $reg->current_level }})</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span style="color: #94a3b8; font-size: 0.78rem; font-style: italic;">Belum ada siswa yang mendaftar di slot ini</span>
                                    @endif
                                </td>
                                @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                                    <td style="padding: 12px 14px; text-align: center;">
                                        <form method="POST" action="{{ route('munaqosah.schedules.destroy', $sch->id) }}" onsubmit="return confirm('Hapus slot jadwal penguji ini?')" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Slot Jadwal" 
                                                    style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 35px; text-align: center; color: #94a3b8;">
                                    <i class="fas fa-calendar-times" style="font-size: 2.5rem; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                                    Belum ada jadwal ketersediaan penguji yang ditambahkan.<br>
                                    <span style="font-size: 0.82rem; color: #64748b;">Klik tombol <strong>+ Tambah Jadwal Penguji</strong> di atas untuk mengatur ketersediaan hari dan jam penguji.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View Jadwal Penguji -->
            <div class="munaqosah-mobile-cards">
                @forelse($schedules as $index => $sch)
                    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; margin-bottom: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #0f172a;">
                                        {{ $sch->examiner?->user?->name ?? 'Penguji Munaqosah' }}
                                    </h4>
                                    @if($sch->examiner?->phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sch->examiner->phone) }}" target="_blank" style="font-size: 0.76rem; color: #059669; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fab fa-whatsapp"></i> {{ $sch->examiner->phone }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if($sch->is_full)
                                    <span style="background: #ecfdf5; color: #047857; font-weight: 800; padding: 3px 8px; border-radius: 20px; font-size: 0.72rem; border: 1px solid #10b981;">
                                        Penuh ({{ $sch->used_slots }}/{{ $sch->quota }})
                                    </span>
                                @else
                                    <span style="background: #eff6ff; color: #1e40af; font-weight: 800; padding: 3px 8px; border-radius: 20px; font-size: 0.72rem; border: 1px solid #60a5fa;">
                                        Tersedia ({{ $sch->used_slots }}/{{ $sch->quota }})
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div style="background: #f8fafc; border-radius: 12px; padding: 12px; margin-bottom: 12px; font-size: 0.82rem; display: flex; flex-direction: column; gap: 6px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #64748b;">Hari & Waktu:</span>
                                <span style="font-weight: 800; color: #0369a1;">
                                    Hari {{ $sch->day_name }} @if($sch->date) ({{ $sch->date->format('d/m') }}) @endif • {{ $sch->time_start }}-{{ $sch->time_end }}
                                </span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #64748b;">Ruangan / Lokasi:</span>
                                <span style="font-weight: 700; color: #334155;">{{ $sch->room_location ?? 'Ruang Munaqosah' }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #64748b;">Kategori:</span>
                                <div>
                                    @if($sch->exam_type === 'all')
                                        <span style="background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: 0.72rem;">Semua</span>
                                    @elseif($sch->exam_type === 'tahfidz')
                                        <span style="background: #ecfdf5; color: #047857; padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: 0.72rem;">Tahfidz</span>
                                    @else
                                        <span style="background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: 0.72rem;">Tilawati</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Siswa Terdaftar di Slot ini -->
                        <div style="margin-bottom: 10px;">
                            <span style="font-size: 0.76rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 4px;">Siswa di Sesi Ini:</span>
                            @if($sch->registrations->isNotEmpty())
                                @foreach($sch->registrations as $reg)
                                    <div style="font-size: 0.8rem; color: #0f172a; font-weight: 700; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 6px 10px; margin-bottom: 4px; display: flex; justify-content: space-between; align-items: center;">
                                        <span><i class="fas fa-user-check" style="color: #10b981; margin-right: 4px;"></i> {{ $reg->student->name ?? 'Siswa' }}</span>
                                        <span style="font-size: 0.72rem; color: #047857; font-weight: 800;">Kelas {{ $reg->student->class ?? '-' }}</span>
                                    </div>
                                @endforeach
                            @else
                                <span style="color: #94a3b8; font-size: 0.76rem; font-style: italic;">Belum ada siswa yang mendaftar di slot ini</span>
                            @endif
                        </div>

                        @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                            <div style="display: flex; justify-content: flex-end; border-top: 1px solid #f1f5f9; padding-top: 10px; margin-top: 6px;">
                                <form method="POST" action="{{ route('munaqosah.schedules.destroy', $sch->id) }}" onsubmit="return confirm('Hapus slot jadwal penguji ini?')" style="width: 100%;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="width: 100%; padding: 8px; border-radius: 10px; border: 1px solid #fecaca; background: #fef2f2; color: #dc2626; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                        <i class="fas fa-trash-alt"></i> Hapus Slot Jadwal
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div style="background: white; border: 1px dashed #cbd5e1; border-radius: 16px; padding: 30px; text-align: center; color: #94a3b8;">
                        <i class="fas fa-calendar-times" style="font-size: 2.2rem; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                        Belum ada jadwal ketersediaan penguji yang ditambahkan.
                    </div>
                @endforelse
            </div>
        </div>
        </div>
    @else
        {{-- TAB REKAPAN & DAFTAR EVALUASI MUNAQOSAH --}}
        <div style="background: white; border-radius: 20px; padding: 22px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">
                        Rekapan Peserta Munaqosah
                    </h3>
                    <span style="font-size: 0.82rem; color: #64748b;">
                        Menampilkan {{ $registrations->total() }} data pendaftaran & evaluasi
                    </span>
                </div>
            </div>

            <!-- Desktop Table View Rekapan Peserta -->
            <div class="munaqosah-desktop-table">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.86rem;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; width: 45px;">No</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Siswa & Kelas</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Tipe & Jenjang</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Pengusul (Guru)</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Penguji & Jadwal</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Nilai & Predikat</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Hasil</th>
                            @if($isAdminOrCurriculum)
                                <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Status Jenjang</th>
                            @endif
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $index => $item)
                            @php
                                $isMyStudent = ($item->examiner_user_id == Auth::id()) || ($item->examiner && $item->examiner->user_id == Auth::id());
                            @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s; {{ $isMyStudent ? 'background: #f8faff;' : '' }}" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='{{ $isMyStudent ? '#f8faff' : 'white' }}'">
                                <td style="padding: 12px 14px; color: #64748b; font-weight: 600; {{ $isMyStudent ? 'border-left: 3.5px solid #4f46e5;' : '' }}">
                                    {{ $registrations->firstItem() + $index }}
                                </td>
                                <td style="padding: 12px 14px;">
                                    <div style="font-weight: 800; color: #0f172a; font-size: 0.92rem; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                        <span>{{ $item->student->name ?? 'Siswa Tidak Ditemukan' }}</span>
                                        @if($isMyStudent)
                                            <span style="background: #e0e7ff; color: #3730a3; font-size: 0.7rem; font-weight: 800; padding: 2px 7px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fas fa-user-check"></i> Ujian Saya
                                            </span>
                                        @endif
                                    </div>
                                    <div style="font-size: 0.76rem; color: #64748b; display: flex; gap: 8px; align-items: center;">
                                        <span style="background: #e2e8f0; color: #334155; padding: 1px 6px; border-radius: 4px; font-weight: 700;">
                                            Kelas {{ $item->student->class ?? '-' }}
                                        </span>
                                        <span>NIS: {{ $item->student->nis ?? '-' }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 14px;">
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                                        <span style="background: {{ $item->type === 'tilawati' ? '#fef3c7' : '#ecfdf5' }}; color: {{ $item->type === 'tilawati' ? '#92400e' : '#047857' }}; padding: 2px 7px; border-radius: 6px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase;">
                                            {{ $item->type }}
                                        </span>
                                    </div>
                                    <div style="font-size: 0.84rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                        <span>{{ $item->current_level }}</span>
                                        <i class="fas fa-arrow-right" style="color: #10b981; font-size: 0.75rem;"></i>
                                        <span style="color: #059669; background: #ecfdf5; padding: 1px 6px; border-radius: 6px;">{{ $item->target_level }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 14px;">
                                    <div style="font-weight: 700; color: #334155;">{{ $item->proposer_name ?? ($item->proposer->name ?? 'Pengusul') }}</div>
                                    @if($item->proposer_phone)
                                        <div style="font-size: 0.74rem; color: #059669; font-weight: 600;">
                                            <i class="fab fa-whatsapp"></i> {{ $item->proposer_phone }}
                                        </div>
                                    @endif
                                    <div style="font-size: 0.74rem; color: #94a3b8;">
                                        {{ $item->registration_date ? $item->registration_date->isoFormat('D MMM Y') : '-' }}
                                    </div>
                                </td>
                                <td style="padding: 12px 14px;">
                                    @if($item->examinerUser || ($item->examiner && $item->examiner->user))
                                        <div style="font-weight: 700; color: #0f172a;">
                                            <i class="fas fa-user-tie" style="color: #4f46e5; margin-right: 4px;"></i>
                                            {{ $item->examinerUser->name ?? $item->examiner->user->name }}
                                        </div>
                                    @else
                                        <span style="color: #94a3b8; font-style: italic;">Belum ditentukan</span>
                                    @endif

                                    @if($item->exam_date)
                                        <div style="font-size: 0.76rem; color: #64748b; margin-top: 2px;">
                                            <i class="far fa-calendar-alt" style="margin-right: 4px;"></i>
                                            {{ $item->exam_date->isoFormat('D MMM Y') }}
                                            @if($item->exam_time) • {{ $item->exam_time }} @endif
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    @if($item->final_score !== null)
                                        <div style="font-size: 1.15rem; font-weight: 900; color: {{ $item->status === 'passed' ? '#047857' : '#b91c1c' }};">
                                            {{ number_format($item->final_score, 1) }}
                                        </div>
                                        <span style="font-size: 0.72rem; font-weight: 700; color: #475569;">
                                            {{ $item->predicate ?? '-' }}
                                        </span>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    @if($item->status === 'passed')
                                        <span style="background: #ecfdf5; color: #047857; font-size: 0.76rem; font-weight: 800; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-check-circle"></i> LULUS
                                        </span>
                                    @elseif($item->status === 'failed')
                                        <span style="background: #fef2f2; color: #b91c1c; font-size: 0.76rem; font-weight: 800; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-times-circle"></i> BELUM LULUS
                                        </span>
                                    @elseif($item->status === 'scheduled')
                                        <span style="background: #eff6ff; color: #1d4ed8; font-size: 0.76rem; font-weight: 800; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-calendar-check"></i> Terjadwal
                                        </span>
                                    @else
                                        <span style="background: #fffbeb; color: #b45309; font-size: 0.76rem; font-weight: 800; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                @if($isAdminOrCurriculum)
                                <td style="padding: 12px 14px; text-align: center;">
                                    @if($item->status === 'passed')
                                        @if($item->transition_status === 'promoted')
                                            <span style="background: #f1f5f9; color: #475569; font-size: 0.74rem; font-weight: 700; padding: 3px 8px; border-radius: 8px;">
                                                <i class="fas fa-check"></i> Sudah Dipindah
                                            </span>
                                        @else
                                            <span style="background: #fef3c7; color: #b45309; font-size: 0.74rem; font-weight: 800; padding: 3px 8px; border-radius: 8px; animation: pulse 2s infinite;">
                                                <i class="fas fa-exclamation-circle"></i> Menunggu Pindah
                                            </span>
                                        @endif
                                    @else
                                        <span style="color: #94a3b8; font-size: 0.8rem;">-</span>
                                    @endif
                                </td>
                                @endif
                                <td style="padding: 12px 14px; text-align: center;">
                                    <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                                        {{-- 1. Penilaian Penguji --}}
                                        <a href="{{ route('munaqosah.score', $item->id) }}" title="Form Penilaian Penguji" 
                                           style="background: #10b981; color: white; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-size: 0.78rem; font-weight: 800; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);">
                                            <i class="fas fa-edit"></i> {{ $item->final_score !== null ? 'Nilai' : 'Uji Sekarang' }}
                                        </a>

                                        {{-- Tombol Share WA Hasil Ujian Sekali Klik ke Grup --}}
                                        @if($item->final_score !== null)
                                            <button type="button" onclick="openShareWaModal({{ json_encode($item->buildWaGroupMessage()) }}, '{{ addslashes($item->student->name ?? 'Santri') }}')" 
                                                    title="Share Hasil Pengujian ke Grup WA" 
                                                    style="background: #25D366; color: white; border: none; padding: 6px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 6px rgba(37, 211, 102, 0.3);">
                                                <i class="fab fa-whatsapp"></i> WA
                                            </button>
                                        @endif

                                        @if($isAdminOrCurriculum)
                                            {{-- 2. Jadwalkan / Pilih Penguji (jika pending) --}}
                                            @if(in_array($item->status, ['pending', 'scheduled']))
                                                <button type="button" onclick="openScheduleModal({{ $item->id }}, '{{ addslashes($item->student->name ?? '') }}', '{{ $item->examiner_id ?? '' }}', '{{ $item->exam_date ? $item->exam_date->format('Y-m-d') : '' }}', '{{ $item->exam_time ?? '' }}', '{{ $item->room_location ?? '' }}')" 
                                                        title="Tetapkan Jadwal & Penguji" 
                                                        style="background: #3b82f6; color: white; border: none; padding: 6px 9px; border-radius: 8px; font-size: 0.78rem; cursor: pointer;">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </button>
                                            @endif

                                            {{-- 3. Pindahkan Kelompok (Jika Lulus & belum dipindah) --}}
                                            @if($item->status === 'passed' && $item->transition_status !== 'promoted')
                                                <button type="button" onclick="openPromoteModal({{ $item->id }}, '{{ addslashes($item->student->name ?? '') }}', '{{ $item->type }}', '{{ addslashes($item->target_level) }}')" 
                                                        title="Pindahkan ke Kelompok Baru" 
                                                        style="background: #f59e0b; color: white; border: none; padding: 6px 9px; border-radius: 8px; font-size: 0.78rem; cursor: pointer;">
                                                    <i class="fas fa-user-check"></i>
                                                </button>
                                            @endif

                                            {{-- 4. Cetak Sertifikat / Berita Acara (Jika Lulus) --}}
                                            @if($item->status === 'passed')
                                                <a href="{{ route('munaqosah.print-certificate', $item->id) }}" target="_blank" title="Cetak Surat Tanda Lulus" 
                                                   style="background: #6366f1; color: white; padding: 6px 9px; border-radius: 8px; text-decoration: none; font-size: 0.78rem;">
                                                    <i class="fas fa-certificate"></i>
                                                </a>
                                            @endif

                                            {{-- 5. Hapus Pendaftaran (Khusus Admin / Pembuat) --}}
                                            @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']) || $item->proposer_user_id === Auth::id())
                                                <form method="POST" action="{{ route('munaqosah.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data munaqosah ini?')" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Hapus" 
                                                            style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 6px 9px; border-radius: 8px; font-size: 0.78rem; cursor: pointer;">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isAdminOrCurriculum ? 9 : 8 }}" style="padding: 40px; text-align: center; color: #94a3b8;">
                                    <i class="fas fa-inbox" style="font-size: 2.8rem; margin-bottom: 12px; display: block; opacity: 0.4;"></i>
                                    Tidak ada data pendaftaran munaqosah untuk kriteria ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View Rekapan Peserta (Khusus Layar HP) -->
            <div class="munaqosah-mobile-cards">
                @forelse($registrations as $index => $item)
                    @php
                        $isMyStudent = ($item->examiner_user_id == Auth::id()) || ($item->examiner && $item->examiner->user_id == Auth::id());
                    @endphp
                    <div style="background: white; border: 1px solid {{ $isMyStudent ? '#c7d2fe' : '#e2e8f0' }}; border-radius: 18px; padding: 16px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); position: relative;">
                        @if($isMyStudent)
                            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: #4f46e5; border-radius: 18px 18px 0 0;"></div>
                        @endif

                        <!-- Header Card Peserta -->
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; border-radius: 12px; background: {{ $item->type === 'tilawati' ? '#fef3c7' : '#ecfdf5' }}; color: {{ $item->type === 'tilawati' ? '#92400e' : '#047857' }}; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                    <i class="fas {{ $item->type === 'tilawati' ? 'fa-book-reader' : 'fa-star-and-crescent' }}"></i>
                                </div>
                                <div>
                                    <h4 style="margin: 0; font-size: 0.98rem; font-weight: 800; color: #0f172a; line-height: 1.3;">
                                        {{ $item->student->name ?? 'Siswa' }}
                                    </h4>
                                    <div style="font-size: 0.74rem; color: #64748b; margin-top: 2px;">
                                        Kelas {{ $item->student->class ?? '-' }} @if($item->student->nis) • NIS: {{ $item->student->nis }} @endif
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                @if($item->status === 'passed')
                                    <span style="background: #ecfdf5; color: #047857; font-size: 0.72rem; font-weight: 800; padding: 3px 8px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-check-circle"></i> LULUS
                                    </span>
                                @elseif($item->status === 'failed')
                                    <span style="background: #fef2f2; color: #b91c1c; font-size: 0.72rem; font-weight: 800; padding: 3px 8px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-times-circle"></i> BELUM LULUS
                                    </span>
                                @elseif($item->status === 'scheduled')
                                    <span style="background: #eff6ff; color: #1d4ed8; font-size: 0.72rem; font-weight: 800; padding: 3px 8px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-calendar-check"></i> Terjadwal
                                    </span>
                                @else
                                    <span style="background: #fffbeb; color: #b45309; font-size: 0.72rem; font-weight: 800; padding: 3px 8px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @endif

                                @if($isMyStudent)
                                    <div style="margin-top: 3px;">
                                        <span style="background: #e0e7ff; color: #3730a3; font-size: 0.68rem; font-weight: 800; padding: 2px 6px; border-radius: 6px;">
                                            Ujian Saya
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Info Jenjang Kenaikan & Penguji -->
                        <div style="background: #f8fafc; border-radius: 12px; padding: 12px; margin-bottom: 12px; font-size: 0.82rem; display: flex; flex-direction: column; gap: 6px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #64748b;">Kenaikan Jenjang:</span>
                                <div style="font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                    <span>{{ $item->current_level }}</span>
                                    <i class="fas fa-arrow-right" style="color: #10b981; font-size: 0.72rem;"></i>
                                    <span style="color: #047857; background: #ecfdf5; padding: 2px 7px; border-radius: 6px;">{{ $item->target_level }}</span>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #64748b;">Penguji:</span>
                                <span style="font-weight: 700; color: #334155;">
                                    {{ $item->examinerUser->name ?? ($item->examiner->user->name ?? 'Belum Ditentukan') }}
                                </span>
                            </div>
                            @if($item->exam_date)
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="color: #64748b;">Waktu Ujian:</span>
                                    <span style="font-weight: 700; color: #0284c7;">
                                        {{ $item->exam_date->isoFormat('D MMM Y') }} @if($item->exam_time) • {{ $item->exam_time }} @endif
                                    </span>
                                </div>
                            @endif
                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #e2e8f0; padding-top: 6px; margin-top: 2px;">
                                <span style="color: #64748b;">Pengusul (Guru):</span>
                                <span style="font-weight: 600; color: #475569;">
                                    {{ $item->proposer_name ?? ($item->proposer->name ?? '-') }}
                                </span>
                            </div>
                                              <!-- Skor & Status Promoted jika ada -->
                        @if($item->final_score !== null || ($isAdminOrCurriculum && $item->status === 'passed'))
                            <div style="display: flex; justify-content: space-between; align-items: center; background: {{ $item->status === 'passed' ? '#f0fdf4' : '#fef2f2' }}; border: 1px solid {{ $item->status === 'passed' ? '#bbf7d0' : '#fecaca' }}; border-radius: 12px; padding: 8px 12px; margin-bottom: 12px;">
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; display: block;">Nilai Akhir:</span>
                                    <strong style="font-size: 1.15rem; color: {{ $item->status === 'passed' ? '#047857' : '#b91c1c' }};">
                                        {{ $item->final_score !== null ? number_format($item->final_score, 1) : '-' }}
                                    </strong>
                                    <span style="font-size: 0.75rem; color: #475569; font-weight: 700; margin-left: 4px;">({{ $item->predicate ?? '-' }})</span>
                                </div>
                                @if($isAdminOrCurriculum && $item->status === 'passed')
                                <div>
                                    @if($item->transition_status === 'promoted')
                                        <span style="background: #e2e8f0; color: #334155; font-size: 0.72rem; font-weight: 700; padding: 3px 8px; border-radius: 6px;">
                                            <i class="fas fa-check"></i> Sudah Dipindah
                                        </span>
                                    @else
                                        <span style="background: #fef3c7; color: #b45309; font-size: 0.72rem; font-weight: 800; padding: 3px 8px; border-radius: 6px;">
                                            <i class="fas fa-exclamation-circle"></i> Belum Dipindah
                                        </span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        @endif

                        <!-- Action Buttons Touch-Friendly Mobile -->
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                            {{-- 1. Penilaian Penguji --}}
                            <a href="{{ route('munaqosah.score', $item->id) }}" 
                               style="flex: {{ $isAdminOrCurriculum ? 2 : 1 }}; background: #10b981; color: white; padding: 9px 12px; border-radius: 10px; text-decoration: none; font-size: 0.82rem; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);">
                                <i class="fas fa-edit"></i> {{ $item->final_score !== null ? 'Nilai / Uji' : 'Mulai Uji' }}
                            </a>

                            {{-- Tombol Share WA Hasil Ujian di Mobile --}}
                            @if($item->final_score !== null)
                                <button type="button" onclick="openShareWaModal({{ json_encode($item->buildWaGroupMessage()) }}, '{{ addslashes($item->student->name ?? 'Santri') }}')" 
                                        style="flex: 1; background: #25D366; color: white; border: none; padding: 9px 10px; border-radius: 10px; font-size: 0.82rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; box-shadow: 0 2px 8px rgba(37, 211, 102, 0.3);">
                                    <i class="fab fa-whatsapp"></i> WA
                                </button>
                            @endif

                            @if($isAdminOrCurriculum)
                                {{-- 2. Jadwalkan / Pilih Penguji --}}
                                @if(in_array($item->status, ['pending', 'scheduled']))
                                    <button type="button" onclick="openScheduleModal({{ $item->id }}, '{{ addslashes($item->student->name ?? '') }}', '{{ $item->examiner_id ?? '' }}', '{{ $item->exam_date ? $item->exam_date->format('Y-m-d') : '' }}', '{{ $item->exam_time ?? '' }}', '{{ $item->room_location ?? '' }}')" 
                                            style="flex: 1; background: #3b82f6; color: white; border: none; padding: 9px 10px; border-radius: 10px; font-size: 0.82rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px;">
                                        <i class="fas fa-calendar-alt"></i> Jadwal
                                    </button>
                                @endif

                                {{-- 3. Pindahkan Kelompok --}}
                                @if($item->status === 'passed' && $item->transition_status !== 'promoted')
                                    <button type="button" onclick="openPromoteModal({{ $item->id }}, '{{ addslashes($item->student->name ?? '') }}', '{{ $item->type }}', '{{ addslashes($item->target_level) }}')" 
                                            style="flex: 1; background: #f59e0b; color: white; border: none; padding: 9px 10px; border-radius: 10px; font-size: 0.82rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px;">
                                        <i class="fas fa-user-check"></i> Pindah
                                    </button>
                                @endif

                                {{-- 4. Cetak Sertifikat --}}
                                @if($item->status === 'passed')
                                    <a href="{{ route('munaqosah.print-certificate', $item->id) }}" target="_blank" 
                                       style="flex: 1; background: #6366f1; color: white; padding: 9px 10px; border-radius: 10px; text-decoration: none; font-size: 0.82rem; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
                                        <i class="fas fa-certificate"></i> Surat
                                    </a>
                                @endif

                                {{-- 5. Hapus Pendaftaran --}}
                                <form method="POST" action="{{ route('munaqosah.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data munaqosah ini?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus" 
                                            style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 9px 12px; border-radius: 10px; font-size: 0.82rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="background: white; border: 1px dashed #cbd5e1; border-radius: 16px; padding: 35px; text-align: center; color: #94a3b8;">
                        <i class="fas fa-inbox" style="font-size: 2.4rem; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                        Tidak ada data pendaftaran munaqosah untuk kriteria ini.
                    </div>
                @endforelse
            </div>

            <div style="margin-top: 20px;">
                {{ $registrations->links() }}
            </div>
        </div>
    @endif

</div>

@if($isAdminOrCurriculum)
{{-- MODAL PENDAFTARAN SISWA MUNAQOSAH --}}
<div id="registerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); z-index: 9999; justify-content: center; align-items: center; padding: 16px; backdrop-filter: blur(4px);">
    <div class="munaqosah-modal-card" style="overflow: hidden; animation: fadeIn 0.25s ease-out;">
        <div style="background: linear-gradient(135deg, #065f46 0%, #047857 100%); color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-signature"></i> Form Pengajuan Munaqosah
            </h3>
            <button type="button" onclick="closeRegisterModal()" style="background: none; border: none; color: white; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="{{ route('munaqosah.register.store') }}" style="padding: 24px;" onsubmit="return validateModalRegisterForm()">
            @csrf
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Pilih Bidang Munaqosah <span style="color: #ef4444;">*</span></label>
                <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                    <label style="flex: 1; min-width: 180px; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 14px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="type" value="tahfidz" checked onchange="updateLevelOptions('tahfidz')">
                        <span style="font-weight: 700; color: #065f46;"><i class="fas fa-star-and-crescent"></i> Tahfidz (Hafalan Juz)</span>
                    </label>
                    <label style="flex: 1; min-width: 180px; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 14px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="type" value="tilawati" onchange="updateLevelOptions('tilawati')">
                        <span style="font-weight: 700; color: #92400e;"><i class="fas fa-book-reader"></i> Tilawati (Jilid)</span>
                    </label>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Pilih Siswa <span style="color: #ef4444;">*</span></label>
                <div style="position: relative;">
                    <input type="text" id="modal_student_search" autocomplete="off" placeholder="Ketik nama lengkap atau kelas siswa..." oninput="onSearchModalStudent(this.value)" 
                           style="width: 100%; padding: 10px 14px 10px 38px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 12px; color: #059669; font-size: 0.95rem;"></i>
                </div>

                <div id="modal_student_search_results" style="display: none; max-height: 180px; overflow-y: auto; background: white; border: 1.5px solid #10b981; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); margin-top: 6px; z-index: 50; position: relative;"></div>

                <input type="hidden" name="student_id" id="modal_selected_student_id" required>

                <div id="modal_student_selected_card" style="display: none; background: #ecfdf5; border: 1.5px solid #10b981; border-radius: 10px; padding: 10px 14px; margin-top: 8px; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-user-check" style="color: #059669; font-size: 1.1rem;"></i>
                        <div>
                            <strong id="modal_selected_student_name" style="font-size: 0.9rem; color: #065f46; display: block;"></strong>
                            <span id="modal_selected_student_meta" style="font-size: 0.76rem; color: #047857;"></span>
                        </div>
                    </div>
                    <button type="button" onclick="clearModalStudentSelection()" style="background: white; border: 1px solid #a7f3d0; color: #065f46; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">
                        <i class="fas fa-sync-alt"></i> Ganti
                    </button>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Jenjang Saat Ini <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="current_level" id="current_level_input" required placeholder="Contoh: Juz 30" 
                           style="width: 100%; padding: 9px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Target Jenjang Lanjutan <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="target_level" id="target_level_input" required placeholder="Contoh: Juz 29" 
                           style="width: 100%; padding: 9px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; box-sizing: border-box;">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Catatan Kesiapan Guru Pengusul</label>
                <textarea name="proposer_notes" rows="2" placeholder="Contoh: Ananda sudah tuntas tasmi' 1 majelis juz 30 dengan predikat lancar." 
                          style="width: 100%; padding: 9px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; box-sizing: border-box;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" onclick="closeRegisterModal()" style="padding: 9px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 9px 20px; border-radius: 10px; border: none; background: #047857; color: white; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(4, 120, 87, 0.25);">
                    <i class="fas fa-paper-plane"></i> Ajukan Munaqosah
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL JADWAL & PENETAPAN PENGUJI --}}
<div id="scheduleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); z-index: 9999; justify-content: center; align-items: center; padding: 16px; backdrop-filter: blur(4px);">
    <div class="munaqosah-modal-card" style="overflow: hidden;">
        <div style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;">
                <i class="fas fa-calendar-check"></i> Jadwalkan Ujian Munaqosah
            </h3>
            <button type="button" onclick="closeScheduleModal()" style="background: none; border: none; color: white; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>
        <form id="scheduleForm" method="POST" style="padding: 24px;">
            @csrf
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; color: #64748b; font-weight: 700;">Peserta Siswa:</label>
                <strong id="schedule_student_name" style="font-size: 1.05rem; color: #0f172a;"></strong>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Pilih Mentor Penguji (Munaqisy) <span style="color: #ef4444;">*</span></label>
                <select name="examiner_id" id="schedule_examiner_id" required class="form-control-pro" style="width: 100%;">
                    <option value="">-- Pilih Penguji --</option>
                    @foreach($examiners->where('status', 'active') as $ex)
                        <option value="{{ $ex->id }}">{{ $ex->user->name ?? 'Penguji' }} ({{ ucfirst($ex->specialization) }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Tanggal Ujian <span style="color: #ef4444;">*</span></label>
                    <input type="date" name="exam_date" id="schedule_exam_date" required style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Waktu Sesi</label>
                    <input type="text" name="exam_time" id="schedule_exam_time" placeholder="08:00 - 09:30" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; box-sizing: border-box;">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Ruangan / Tempat</label>
                <input type="text" name="room_location" id="schedule_room_location" placeholder="Masjid Lt. 2 / Kelas 3A" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; box-sizing: border-box;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeScheduleModal()" style="padding: 9px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 9px 20px; border-radius: 10px; border: none; background: #2563eb; color: white; font-weight: 800; cursor: pointer;">
                    <i class="fas fa-save"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH PENGUJI (MUNAQISY) --}}
<div id="examinerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); z-index: 9999; justify-content: center; align-items: center; padding: 16px; backdrop-filter: blur(4px);">
    <div class="munaqosah-modal-card" style="overflow: hidden;">
        <div style="background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); color: white; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;">
                <i class="fas fa-user-plus"></i> Tambah Mentor Penguji (Munaqisy)
            </h3>
            <button type="button" onclick="closeExaminerModal()" style="background: none; border: none; color: white; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="{{ route('munaqosah.examiners.store') }}" style="padding: 24px;">
            @csrf
            
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Pilih Akun Guru / Asatidz <span style="color: #ef4444;">*</span></label>
                <select name="user_id" required class="form-control-pro" style="width: 100%;">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($teachers as $tc)
                        <option value="{{ $tc->id }}">{{ $tc->name }} ({{ $tc->email }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Spesialisasi Pengujian <span style="color: #ef4444;">*</span></label>
                <select name="specialization" required class="form-control-pro" style="width: 100%;">
                    <option value="both">Keduanya (Tahfidz & Tilawati)</option>
                    <option value="tahfidz">Khusus Tahfidz</option>
                    <option value="tilawati">Khusus Tilawati</option>
                </select>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Nomor Sertifikat / SK Munaqisy</label>
                <input type="text" name="certificate_number" placeholder="Contoh: SK-MNQ/2026/012" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">No WhatsApp</label>
                <input type="text" name="phone" placeholder="08xxxxxxxxxx" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; box-sizing: border-box;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeExaminerModal()" style="padding: 9px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 9px 20px; border-radius: 10px; border: none; background: #4f46e5; color: white; font-weight: 800; cursor: pointer;">
                    <i class="fas fa-check"></i> Daftarkan Penguji
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PINDAHKAN KELOMPOK / NAIK JENJANG --}}
<div id="promoteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); z-index: 9999; justify-content: center; align-items: center; padding: 16px; backdrop-filter: blur(4px);">
    <div class="munaqosah-modal-card" style="overflow: hidden;">
        <div style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-user-check"></i> Pindahkan Kelompok Siswa Lulus
            </h3>
            <button type="button" onclick="closePromoteModal()" style="background: none; border: none; color: white; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>
        <form id="promoteForm" method="POST" style="padding: 24px;">
            @csrf
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px; margin-bottom: 18px;">
                <div style="font-size: 0.82rem; color: #166534; font-weight: 700; margin-bottom: 2px;">Siswa Lulus Munaqosah:</div>
                <div id="promote_student_name" style="font-size: 1.05rem; font-weight: 900; color: #0f172a; margin-bottom: 6px;"></div>
                <div style="font-size: 0.82rem; color: #166534;">
                    Rekomendasi Jenjang Lanjutan: <strong id="promote_target_level" style="color: #047857; text-decoration: underline;"></strong>
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.86rem; color: #334155; margin-bottom: 8px;">
                    Pilih Kelompok Baru Tujuan Siswa <span style="color: #ef4444;">*</span>
                </label>
                <select name="target_group_id" id="target_group_select" required class="form-control-pro" style="width: 100%;">
                    <option value="">-- Pilih Kelompok Mentoring --</option>
                    @foreach($groups as $grp)
                        <option value="{{ $grp->id }}" data-type="{{ $grp->type }}" data-target="{{ $grp->target_level }}">
                            [{{ strtoupper($grp->type) }}] {{ $grp->name }} • Target: {{ $grp->target_level ?? 'Umum' }} (Mentor: {{ $grp->mentor_name ?? '-' }})
                        </option>
                    @endforeach
                </select>
                <small style="display: block; color: #64748b; font-size: 0.78rem; margin-top: 6px;">
                    Siswa otomatis akan dilepaskan dari kelompok sebelumnya dan didaftarkan pada kelompok baru di atas.
                </small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" onclick="closePromoteModal()" style="padding: 9px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 9px 22px; border-radius: 10px; border: none; background: #059669; color: white; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);">
                    <i class="fas fa-check-circle"></i> Pindahkan Siswa Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH JADWAL KETERSEDIAAN PENGUJI --}}
<div id="addScheduleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); z-index: 9999; justify-content: center; align-items: center; padding: 16px; backdrop-filter: blur(4px);">
    <div class="munaqosah-modal-card" style="overflow: hidden; animation: fadeIn 0.25s ease-out;">
        <div style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-calendar-plus"></i> Atur Jadwal & Ketersediaan Penguji
            </h3>
            <button type="button" onclick="closeAddScheduleModal()" style="background: none; border: none; color: white; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="{{ route('munaqosah.schedules.store') }}" style="padding: 24px;">
            @csrf
            
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                    Pilih Mentor Penguji (Munaqisy) <span style="color: #ef4444;">*</span>
                </label>
                <select name="examiner_id" required class="form-control-pro" style="width: 100%;">
                    <option value="">-- Pilih Penguji --</option>
                    @foreach($examiners->where('status', 'active') as $ex)
                        <option value="{{ $ex->id }}">{{ $ex->user->name ?? 'Penguji' }} ({{ ucfirst($ex->specialization) }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                        Hari Ketersediaan <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="day_name" required class="form-control-pro" style="width: 100%;">
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu" selected>Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Ahad">Ahad</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                        Tanggal Spesifik (Opsional)
                    </label>
                    <input type="date" name="date" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                        Jam Mulai <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="time_start" required value="08:00" placeholder="08:00" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem; font-weight: 600;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                        Jam Selesai <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="time_end" required value="09:00" placeholder="09:00" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem; font-weight: 600;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                        Ruangan / Lokasi Ujian
                    </label>
                    <input type="text" name="room_location" placeholder="Contoh: Ruang Musholla / Kelas 1A" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                        Kuota Siswa <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="quota" required min="1" max="20" value="1" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; font-weight: 700;">
                </div>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                    Kategori Ujian
                </label>
                <select name="exam_type" class="form-control-pro" style="width: 100%;">
                    <option value="all">Semua (Tahfidz & Tilawati)</option>
                    <option value="tahfidz">Khusus Tahfidz</option>
                    <option value="tilawati">Khusus Tilawati</option>
                </select>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Catatan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Catatan kesiapan penguji..." style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" onclick="closeAddScheduleModal()" style="padding: 9px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 9px 20px; border-radius: 10px; border: none; background: #0284c7; color: white; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);">
                    <i class="fas fa-save"></i> Simpan Jadwal Ketersediaan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
function openRegisterModal() {
    const el = document.getElementById('registerModal');
    if (el) el.style.display = 'flex';
}
function closeRegisterModal() {
    const el = document.getElementById('registerModal');
    if (el) el.style.display = 'none';
}

@php
    $modalStudentsList = \App\Models\Student::activeYear()->active()->orderBy('class')->orderBy('name')->get(['id', 'name', 'class', 'nis']);
@endphp
const modalStudentsData = @json($modalStudentsList);

function onSearchModalStudent(query) {
    const q = query.trim().toLowerCase();
    const box = document.getElementById('modal_student_search_results');
    if (!box) return;

    if (q.length < 2) {
        box.style.display = 'none';
        box.innerHTML = '';
        return;
    }

    const matches = modalStudentsData.filter(st => {
        const nameMatch = st.name && st.name.toLowerCase().includes(q);
        const classMatch = st.class && String(st.class).toLowerCase().includes(q);
        const nisMatch = st.nis && String(st.nis).toLowerCase().includes(q);
        return nameMatch || classMatch || nisMatch;
    }).slice(0, 15);

    if (matches.length === 0) {
        box.innerHTML = '<div style="padding: 10px 14px; font-size: 0.82rem; color: #94a3b8; text-align: center;">Tidak ditemukan siswa dengan kata kunci "<strong>' + escapeModalHtml(query) + '</strong>"</div>';
        box.style.display = 'block';
        return;
    }

    let html = '';
    matches.forEach(st => {
        const studentName = escapeModalHtml(st.name);
        const studentClass = escapeModalHtml(st.class);
        const studentNis = escapeModalHtml(st.nis || '');
        
        html += '<div onclick="selectModalStudent(' + st.id + ', \'' + studentName + '\', \'' + studentClass + '\', \'' + studentNis + '\')" ' +
                'style="padding: 8px 12px; border-bottom: 1px solid #f1f5f9; cursor: pointer; display: flex; align-items: center; justify-content: space-between; transition: background 0.15s;" ' +
                'onmouseover="this.style.background=\'#ecfdf5\'" onmouseout="this.style.background=\'white\'">' +
                '<div>' +
                    '<strong style="color: #0f172a; font-size: 0.86rem; display: block;">' + studentName + '</strong>' +
                    '<span style="font-size: 0.74rem; color: #64748b;">Kelas ' + studentClass + (studentNis ? ' • NIS: ' + studentNis : '') + '</span>' +
                '</div>' +
                '<span style="font-size: 0.72rem; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-weight: 700;">Pilih</span>' +
                '</div>';
    });

    box.innerHTML = html;
    box.style.display = 'block';
}

function selectModalStudent(id, name, cls, nis) {
    document.getElementById('modal_selected_student_id').value = id;
    document.getElementById('modal_selected_student_name').textContent = name;
    document.getElementById('modal_selected_student_meta').textContent = 'Kelas ' + cls + (nis ? ' • NIS: ' + nis : '');
    document.getElementById('modal_student_selected_card').style.display = 'flex';

    document.getElementById('modal_student_search').value = name;
    document.getElementById('modal_student_search_results').style.display = 'none';
}

function clearModalStudentSelection() {
    document.getElementById('modal_selected_student_id').value = '';
    document.getElementById('modal_student_selected_card').style.display = 'none';
    const input = document.getElementById('modal_student_search');
    input.value = '';
    input.focus();
}

function escapeModalHtml(text) {
    if (!text) return '';
    return String(text).replace(/'/g, "\\'").replace(/"/g, '&quot;');
}

function validateModalRegisterForm() {
    const studentId = document.getElementById('modal_selected_student_id').value;
    if (!studentId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Siswa Terlebih Dahulu',
                text: 'Silakan ketik nama lengkap siswa pada kolom pencarian dan klik nama siswa yang sesuai.',
                confirmButtonColor: '#059669'
            });
        } else {
            alert('Silakan ketik nama lengkap siswa pada kolom pencarian dan klik nama siswa yang sesuai.');
        }
        document.getElementById('modal_student_search').focus();
        return false;
    }
    return true;
}

document.addEventListener('click', function(e) {
    const box = document.getElementById('modal_student_search_results');
    const input = document.getElementById('modal_student_search');
    if (box && input && !box.contains(e.target) && e.target !== input) {
        box.style.display = 'none';
    }
});

function openAddScheduleModal() {
    const el = document.getElementById('addScheduleModal');
    if (el) el.style.display = 'flex';
}
function closeAddScheduleModal() {
    const el = document.getElementById('addScheduleModal');
    if (el) el.style.display = 'none';
}

function openScheduleModal(id, studentName, examinerId, examDate, examTime, room) {
    const el = document.getElementById('scheduleModal');
    if (!el) return;
    document.getElementById('schedule_student_name').textContent = studentName;
    document.getElementById('schedule_examiner_id').value = examinerId || '';
    document.getElementById('schedule_exam_date').value = examDate || '';
    document.getElementById('schedule_exam_time').value = examTime || '';
    document.getElementById('schedule_room_location').value = room || '';
    document.getElementById('scheduleForm').action = '/munaqosah/' + id + '/schedule';
    el.style.display = 'flex';
}
function closeScheduleModal() {
    const el = document.getElementById('scheduleModal');
    if (el) el.style.display = 'none';
}

function openExaminerModal() {
    const el = document.getElementById('examinerModal');
    if (el) el.style.display = 'flex';
}
function closeExaminerModal() {
    const el = document.getElementById('examinerModal');
    if (el) el.style.display = 'none';
}

function openPromoteModal(id, studentName, type, targetLevel) {
    const el = document.getElementById('promoteModal');
    if (!el) return;
    document.getElementById('promote_student_name').textContent = studentName;
    document.getElementById('promote_target_level').textContent = targetLevel;
    document.getElementById('promoteForm').action = '/munaqosah/' + id + '/promote';
    
    // Auto-filter or highlight matching group options
    const select = document.getElementById('target_group_select');
    if (select) {
        for (let opt of select.options) {
            if (!opt.value) continue;
            const optType = opt.getAttribute('data-type');
            const optTarget = opt.getAttribute('data-target') || '';
            if (optType === type) {
                opt.style.display = '';
                if (targetLevel && optTarget.toLowerCase().includes(targetLevel.toLowerCase())) {
                    opt.selected = true;
                }
            } else {
                opt.style.display = 'none';
            }
        }
    }
    el.style.display = 'flex';
}
function closePromoteModal() {
    const el = document.getElementById('promoteModal');
    if (el) el.style.display = 'none';
}

function shareWhatsAppRegistrationLink() {
    const publicUrl = "{{ route('munaqosah.public-register') }}";
    const waMsg = "Assalamu'alaikum Warahmatullahi Wabarakatuh 🌙\n\nBapak/Ibu Guru Pembimbing & Orang Tua / Wali Siswa SDIT AN NADZIR yang dirahmati Allah,\n\nBagi siswa yang telah menuntaskan target hafalan Juz Al-Qur'an (Tahfidz) atau Jilid (Tilawati), pendaftaran Ujian Munaqosah resmi kini dapat diajukan secara online melalui tautan berikut:\n\n🔗 " + publicUrl + "\n\nMohon pastikan ananda telah menyelesaikan tasmi' mandiri di hadapan guru pembimbing.\n\nJazakumullah Khairan Katsiran 🤲";

    if (navigator.clipboard) {
        navigator.clipboard.writeText(waMsg).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Teks & Link WA Disalin!',
                html: 'Teks pengantar dan link formulir pendaftaran online munaqosah berhasil disalin ke clipboard.<br><br><a href="https://wa.me/?text=' + encodeURIComponent(waMsg) + '" target="_blank" style="display: inline-block; background: #25d366; color: white; padding: 10px 18px; border-radius: 10px; font-weight: bold; text-decoration: none; margin-top: 8px;"><i class="fab fa-whatsapp"></i> Buka WhatsApp Sekarang</a>',
                showConfirmButton: true,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#059669'
            });
        }).catch(err => {
            prompt("Salin teks pesan pendaftaran berikut:", waMsg);
        });
    } else {
        prompt("Salin teks pesan pendaftaran berikut:", waMsg);
    }
}

function updateLevelOptions(type) {
    const cur = document.getElementById('current_level_input');
    const tgt = document.getElementById('target_level_input');
    if (type === 'tahfidz') {
        cur.placeholder = 'Contoh: Juz 30';
        tgt.placeholder = 'Contoh: Juz 29';
    } else {
        cur.placeholder = 'Contoh: Jilid 2';
        tgt.placeholder = 'Contoh: Jilid 3';
    }
}

function filterStudentList() {
    const query = document.getElementById('student_search_input').value.toLowerCase();
    const select = document.getElementById('student_select');
    for (let option of select.options) {
        const text = option.text.toLowerCase();
        option.style.display = text.includes(query) ? '' : 'none';
    }
}

// 1. Modal Share WA Hasil Munaqisy Sekali Klik
let currentShareWaMessage = '';
function openShareWaModal(waMessage, studentName) {
    currentShareWaMessage = waMessage;
    document.getElementById('shareWaStudentName').textContent = studentName || 'Santri';
    document.getElementById('shareWaTextarea').value = waMessage;
    
    const waUrl = "https://api.whatsapp.com/send?text=" + encodeURIComponent(waMessage);
    document.getElementById('btnDirectOpenWa').href = waUrl;

    document.getElementById('shareWaGroupModal').style.display = 'flex';
}
function closeShareWaModal() {
    document.getElementById('shareWaGroupModal').style.display = 'none';
}
function copyShareWaModalText() {
    const copyText = document.getElementById("shareWaTextarea");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);

    const btn = document.getElementById("btnCopyModalWa");
    btn.innerHTML = '<i class="fas fa-check"></i> Berhasil Disalin!';
    btn.style.background = '#059669';
    btn.style.color = 'white';
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-copy"></i> Salin Teks Rekapan';
        btn.style.background = '#f8fafc';
        btn.style.color = '#334155';
    }, 2000);
}

// 2. Modal Pendaftaran 1 Kelompok Tilawati ke Munaqosah
function openBulkTilawatiMunaqosahModal() {
    document.getElementById('bulkTilawatiMunaqosahModal').style.display = 'flex';
}
function closeBulkTilawatiMunaqosahModal() {
    document.getElementById('bulkTilawatiMunaqosahModal').style.display = 'none';
}
function onBulkGroupSelected(selectEl) {
    const selectedOpt = selectEl.options[selectEl.selectedIndex];
    const targetLvl = selectedOpt.getAttribute('data-target') || 'Jilid 6';
    const targetInput = document.getElementById('bulk_target_level');
    
    if (targetLvl.toLowerCase().includes('jilid 6')) {
        targetInput.value = "Al-Qur'an (Khatam Tilawati)";
    } else {
        targetInput.value = "Naik ke Tingkat Lanjutan";
    }
}
</script>

<!-- Modal 1: Share Hasil Pengujian ke Grup WA (Sekali Klik) -->
<div id="shareWaGroupModal" class="modal-backdrop" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 99999; padding: 16px;">
    <div style="background: white; border-radius: 20px; max-width: 620px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <div style="padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #25D366; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(37, 211, 102, 0.35);">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">
                        Share Hasil Munaqosah ke Grup WA
                    </h3>
                    <p style="margin: 2px 0 0 0; font-size: 0.8rem; color: #64748b;">
                        Santri: <strong id="shareWaStudentName" style="color: #065f46;">-</strong>
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeShareWaModal()" style="background: none; border: none; font-size: 1.35rem; color: #94a3b8; cursor: pointer;">&times;</button>
        </div>

        <div style="padding: 24px;">
            <p style="font-size: 0.84rem; color: #475569; margin: 0 0 10px 0;">
                Format pesan WhatsApp di bawah ini telah dirancang resmi dan otomatis siap diteruskan ke grup dewan guru, grup Qur'an, maupun wali murid:
            </p>

            <textarea id="shareWaTextarea" rows="12" readonly
                      style="width: 100%; padding: 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-family: 'Consolas', 'Courier New', monospace; font-size: 0.82rem; line-height: 1.5; color: #0f172a; background: #f8fafc; box-sizing: border-box; resize: vertical;"></textarea>

            <div style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <button type="button" onclick="copyShareWaModalText()" id="btnCopyModalWa"
                        style="padding: 10px 18px; border-radius: 10px; border: 1.5px solid #cbd5e1; background: #f8fafc; color: #334155; font-weight: 800; font-size: 0.86rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-copy"></i> Salin Teks Rekapan
                </button>

                <div style="display: flex; gap: 8px;">
                    <button type="button" onclick="closeShareWaModal()" style="padding: 10px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: white; color: #64748b; font-weight: 700; cursor: pointer;">
                        Tutup
                    </button>
                    <a id="btnDirectOpenWa" href="#" target="_blank"
                       style="background: #25D366; color: white; padding: 10px 22px; border-radius: 10px; font-weight: 800; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(37, 211, 102, 0.4);">
                        <i class="fab fa-whatsapp" style="font-size: 1.15rem;"></i> Kirim ke WhatsApp Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Pendaftaran Kolektif 1 Kelompok Tilawati ke Munaqosah -->
<div id="bulkTilawatiMunaqosahModal" class="modal-backdrop" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 99999; padding: 16px;">
    <div style="background: white; border-radius: 20px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <div style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 42px; height: 42px; border-radius: 12px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">
                        Daftarkan 1 Kelompok Tilawati ke Munaqosah
                    </h3>
                    <p style="margin: 2px 0 0 0; font-size: 0.78rem; color: #64748b;">
                        Pendaftaran kolektif sekaligus untuk santri Jilid 6 atau tingkat lainnya.
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeBulkTilawatiMunaqosahModal()" style="background: none; border: none; font-size: 1.35rem; color: #94a3b8; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('munaqosah.register-group.store') }}" method="POST" style="padding: 24px;">
            @csrf

            <!-- Pilih Kelompok Tilawati -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                    Pilih Kelompok Tilawati <span style="color: #ef4444;">*</span>:
                </label>
                <select name="group_id" required onchange="onBulkGroupSelected(this)" 
                        style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; font-weight: 700; color: #0f172a; background: white; box-sizing: border-box;">
                    <option value="">-- Pilih Kelompok Tilawati --</option>
                    @foreach($groups->where('type', 'tilawati') as $grp)
                        <option value="{{ $grp->id }}" data-target="{{ $grp->target_level }}">
                            {{ $grp->name }} ({{ $grp->target_level ?: 'Jilid 1' }}) - Pembimbing: {{ $grp->mentor_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Target Kenaikan -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                    Target Kenaikan Munaqosah <span style="color: #ef4444;">*</span>:
                </label>
                <input type="text" name="target_level" id="bulk_target_level" required value="Al-Qur'an (Khatam Tilawati)"
                       placeholder="Contoh: Al-Qur'an (Khatam Tilawati) atau Jilid Berikutnya"
                       style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; font-weight: 700; box-sizing: border-box;">
                <small style="color: #64748b; font-size: 0.74rem;">Jika santri lulus Jilid 6, target otomatis naik ke Al-Qur'an.</small>
            </div>

            <!-- Pilih Jadwal Ujian (Opsional) -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                    Pilih Jadwal Munaqisy / Sesi Pengujian (Opsional):
                </label>
                <select name="schedule_id" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.86rem; font-weight: 700; color: #0f172a; background: white; box-sizing: border-box;">
                    <option value="">-- Tetapkan Nanti (Status Menunggu / Pending) --</option>
                    @foreach($schedules as $sch)
                        <option value="{{ $sch->id }}">
                            {{ $sch->day_name }}, {{ $sch->time_start }}-{{ $sch->time_end }} | Penguji: {{ $sch->examiner->name ?? 'Penguji' }} ({{ $sch->room_location ?? 'Ruang Munaqosah' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.82rem; color: #0f172a; margin-bottom: 4px;">
                        Tanggal Uji Khusus:
                    </label>
                    <input type="date" name="exam_date" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.84rem; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.82rem; color: #0f172a; margin-bottom: 4px;">
                        Dewan Penguji (Munaqisy):
                    </label>
                    <select name="examiner_id" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.84rem; background: white; box-sizing: border-box;">
                        <option value="">-- Bebas / Tetapkan Nanti --</option>
                        @foreach($examiners as $ex)
                            <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 22px;">
                <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                    Catatan Pengusul untuk Dewan Penguji:
                </label>
                <textarea name="proposer_notes" rows="2" placeholder="Catatan penguasaan fashahah/tajwid santri kelompok..."
                          style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.84rem; box-sizing: border-box;">Pendaftaran Kolektif 1 Kelompok Tilawati</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeBulkTilawatiMunaqosahModal()" style="padding: 10px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 22px; border-radius: 10px; border: none; background: #f59e0b; color: white; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">
                    <i class="fas fa-check-circle"></i> Daftarkan Seluruh Santri Kelompok
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
