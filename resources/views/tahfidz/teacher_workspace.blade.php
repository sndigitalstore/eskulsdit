@extends('layouts.app')

@section('title', 'Mentoring Tahfidz – ' . $activeGroup->name)
@section('page-title', 'Mentoring Tahfidz')

@push('styles')
<style>
    /* Styling Ruang Halaqah Sederhana & Ramah Guru */
    .halaqah-container {
        max-width: 1200px;
        margin: 0 auto 80px auto;
    }

    /* Pilihan Kelompok (jika punya > 1) */
    .group-nav-tabs {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        margin-bottom: 16px;
        scrollbar-width: none;
    }
    .group-nav-tabs::-webkit-scrollbar { display: none; }
    .group-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 12px;
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 0.88rem;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .group-tab-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .group-tab-btn.active {
        background: #047857;
        color: #ffffff;
        border-color: #047857;
        box-shadow: 0 4px 12px rgba(4, 120, 87, 0.25);
    }

    /* Banner Utama Halaqah */
    .halaqah-card-header {
        background: linear-gradient(135deg, #065f46 0%, #047857 55%, #059669 100%);
        border-radius: 18px;
        padding: 22px 24px;
        color: white;
        margin-bottom: 20px;
        box-shadow: 0 8px 24px -4px rgba(5, 150, 105, 0.25);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .session-date-picker-wrap {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        padding: 8px 14px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    .session-date-picker-wrap input[type="date"] {
        background: white;
        border: none;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #0f172a;
        cursor: pointer;
        outline: none;
    }

    /* Kotak Hijau Statistik Informatif & Simpel */
    .kpi-green-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    .kpi-green-card {
        background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
        border: 1.5px solid #a7f3d0;
        border-radius: 14px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.05);
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .kpi-green-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.12);
        border-color: #6ee7b7;
    }
    .kpi-green-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #047857;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .kpi-green-label {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #047857;
        letter-spacing: 0.4px;
        margin-bottom: 2px;
    }
    .kpi-green-value {
        font-size: 1.35rem;
        font-weight: 900;
        color: #064e3b;
        line-height: 1.1;
    }
    .kpi-green-unit {
        font-size: 0.78rem;
        font-weight: 600;
        color: #059669;
    }

    /* Success / WA Banner */
    .wa-success-banner {
        background: #ecfdf5;
        border: 2px solid #86efac;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.12);
        animation: fadeInDown 0.3s ease;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Student Item Container */
    .student-entry-card {
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 20px;
        margin-bottom: 14px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .student-entry-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    }
    .student-entry-card.is-absent {
        background: #f8fafc;
        border-color: #e2e8f0;
        opacity: 0.9;
    }

    /* Pill Selector Kehadiran */
    .att-pills-wrap {
        display: inline-flex;
        background: #f1f5f9;
        border-radius: 12px;
        padding: 4px;
        gap: 4px;
        flex-wrap: wrap;
    }
    .att-pill-label {
        padding: 7px 14px;
        border-radius: 9px;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        user-select: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s ease;
        color: #64748b;
    }
    .att-pill-radio { display: none; }
    
    /* Warna aktif per status */
    .att-pill-radio:checked + .att-pill-hadir {
        background: #16a34a;
        color: white;
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.35);
    }
    .att-pill-radio:checked + .att-pill-sakit {
        background: #d97706;
        color: white;
        box-shadow: 0 2px 8px rgba(217, 119, 6, 0.35);
    }
    .att-pill-radio:checked + .att-pill-izin {
        background: #0284c7;
        color: white;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.35);
    }
    .att-pill-radio:checked + .att-pill-alpha {
        background: #dc2626;
        color: white;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.35);
    }

    /* Input Hafalan Section */
    .hafalan-box-wrap {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px dashed #e2e8f0;
        display: grid;
        grid-template-columns: 2.2fr 1fr 1.2fr 2fr;
        gap: 12px;
        align-items: center;
    }
    .hafalan-field-label {
        font-size: 0.74rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 4px;
        letter-spacing: 0.3px;
        display: flex;
        justify-content: space-between;
    }
    .input-clean {
        width: 100%;
        border: 1.5px solid #cbd5e1;
        border-radius: 9px;
        padding: 9px 12px;
        font-size: 0.88rem;
        color: #0f172a;
        font-weight: 600;
        outline: none;
        transition: border 0.15s, box-shadow 0.15s;
        box-sizing: border-box;
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
        gap: 3px;
    }
    .lulus-pill-btn {
        flex: 1;
        text-align: center;
        padding: 6px 8px;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 800;
        cursor: pointer;
        color: #64748b;
        transition: all 0.15s;
    }
    .lulus-radio:checked + .btn-lulus {
        background: #16a34a;
        color: white;
    }
    .lulus-radio:checked + .btn-ulang {
        background: #dc2626;
        color: white;
    }

    /* Sticky Bottom Action Bar */
    .sticky-bottom-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-top: 2px solid #e2e8f0;
        padding: 14px 20px;
        z-index: 1000;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
    }
    .sticky-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
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
        border-radius: 20px;
        width: 100%;
        max-width: 580px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        overflow: hidden;
        animation: scaleUp 0.2s ease;
    }
    @keyframes scaleUp {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    /* Responsiveness Mobile */
    @media (max-width: 820px) {
        .kpi-green-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .kpi-green-card {
            padding: 12px 14px;
        }
        .kpi-green-value {
            font-size: 1.2rem;
        }
        .hafalan-box-wrap {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        .halaqah-card-header {
            padding: 18px 16px;
        }
        .student-entry-card {
            padding: 14px 15px;
        }
        .sticky-bottom-bar {
            padding: 12px 14px;
        }
        .sticky-content {
            flex-direction: column;
            gap: 10px;
        }
        .btn-simpan-main {
            width: 100% !important;
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
                    <span style="font-size: 0.75rem; padding: 1px 7px; border-radius: 8px; background: rgba(0,0,0,0.12);">
                        {{ $grp->students_count }} santri
                    </span>
                </a>
            @endforeach
        </div>
    @endif

    {{-- 2. BANNER UTAMA RUANG HALAQAH --}}
    <div class="halaqah-card-header">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.2); padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">
                <i class="fas fa-star-and-crescent"></i> Ruang Halaqah Sederhana
            </div>
            <h1 style="margin: 0 0 4px 0; font-size: 1.55rem; font-weight: 800; text-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                {{ $activeGroup->name }}
            </h1>
            <div style="font-size: 0.88rem; opacity: 0.95; display: flex; gap: 14px; flex-wrap: wrap; margin-top: 4px;">
                <span><i class="fas fa-user-tie"></i> Musyrif: <strong>{{ $activeGroup->mentor_name ?? Auth::user()->name }}</strong></span>
                <span><i class="fas fa-bullseye"></i> Target: <strong>{{ $activeGroup->target_level ?? 'Juz 30' }}</strong></span>
                <span><i class="fas fa-users"></i> <strong>{{ $activeGroup->students->count() }} Santri</strong></span>
            </div>
        </div>

        {{-- KANAN: PEMILIH TANGGAL SESI & SHARE WA CEPAT --}}
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <div class="session-date-picker-wrap">
                <span style="font-size: 0.82rem; font-weight: 700;">📅 Tanggal Sesi:</span>
                <input type="date" value="{{ $sessionDate }}" id="sessionDatePicker" 
                       onchange="changeSessionDate(this.value)">
                @if($sessionDate !== $today)
                    <button type="button" onclick="changeSessionDate('{{ $today }}')" 
                            style="background: white; border: none; padding: 5px 9px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; color: #047857; cursor: pointer;">
                        Hari Ini
                    </button>
                @endif
            </div>

            <button type="button" onclick="openWaModal()" 
                    style="background: #25D366; color: white; border: none; padding: 10px 16px; border-radius: 12px; font-weight: 800; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35);">
                <i class="fab fa-whatsapp" style="font-size: 1.15rem;"></i>
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
                <div class="kpi-green-label">Sudah Setoran Hari Ini</div>
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
                <div class="kpi-green-label">Total Baris Dihafal</div>
                <div class="kpi-green-value">
                    {{ number_format($totalBaris, 0, ',', '.') }} <span class="kpi-green-unit">Baris</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. BANNER SUKSES SIMPAN & KIRIM LAPORAN WA --}}
    @if(session('success') || $openShare)
        <div class="wa-success-banner" id="successBanner">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: #22c55e; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <h4 style="margin: 0; color: #166534; font-weight: 800; font-size: 1rem;">
                        {{ session('success') ?? 'Data Sesi ' . \Carbon\Carbon::parse($sessionDate)->locale('id')->isoFormat('dddd, D MMMM Y') . ' Berhasil Disimpan!' }}
                    </h4>
                    <p style="margin: 3px 0 0 0; color: #15803d; font-size: 0.85rem;">
                        Semua data langsung tersinkron ke Rekapan, Rapor & Ruang Halaqah. Siap dikirim ke WhatsApp?
                    </p>
                </div>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <button type="button" onclick="openWaModal()" 
                        style="background: #25D366; color: white; border: none; padding: 9px 16px; border-radius: 10px; font-weight: 800; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fab fa-whatsapp"></i> Buka & Kirim ke WA Grup
                </button>
                <button type="button" onclick="copyWaText()" 
                        style="background: white; border: 1.5px solid #86efac; color: #166534; padding: 9px 14px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-copy"></i> Salin Teks
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

    {{-- 4. FORMULIR ALL-IN-ONE HALAQAH --}}
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
                
                {{-- BARIS ATAS: IDENTITAS + PILIHAN KEHADIRAN (1-TAP) --}}
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 12px; min-width: 240px; flex: 1;">
                        <div style="width: 42px; height: 42px; border-radius: 50%; background: #ecfdf5; color: #047857; font-weight: 800; font-size: 1rem; display: flex; align-items: center; justify-content: center; border: 2px solid #a7f3d0; flex-shrink: 0;">
                            {{ $idx + 1 }}
                        </div>
                        <div>
                            <div style="font-weight: 800; font-size: 1rem; color: #0f172a; line-height: 1.3;">
                                {{ $std->name }}
                            </div>
                            <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                <span style="background: #f1f5f9; padding: 1px 7px; border-radius: 5px; font-weight: 700; color: #334155;">
                                    Kelas {{ $std->class }}
                                </span>
                                @if($lastRec)
                                    <span style="color: #047857; font-weight: 600;">
                                        📌 Lalu: <strong>{{ $lastRec->surah_name }}</strong>{{ $lastRec->start_ayah ? " (Ayat {$lastRec->start_ayah}-{$lastRec->end_ayah})" : '' }}
                                    </span>
                                    <button type="button" 
                                            onclick="copyLastSurah('{{ $std->id }}', '{{ addslashes($lastRec->surah_name) }}')"
                                            style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; border-radius: 4px; padding: 1px 6px; font-size: 0.7rem; font-weight: 700; cursor: pointer;">
                                        Pakai Surah Ini
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- PILIHAN KEHADIRAN 1-TAP --}}
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div class="att-pills-wrap">
                            <label class="att-pill-label">
                                <input type="radio" name="attendance[{{ $std->id }}][status]" value="hadir" 
                                       class="att-pill-radio" {{ $currentStatus === 'hadir' ? 'checked' : '' }}
                                       onchange="toggleAttendanceStatus('{{ $std->id }}', 'hadir')">
                                <span class="att-pill-label att-pill-hadir">✓ Hadir</span>
                            </label>
                            <label class="att-pill-label">
                                <input type="radio" name="attendance[{{ $std->id }}][status]" value="sakit" 
                                       class="att-pill-radio" {{ $currentStatus === 'sakit' ? 'checked' : '' }}
                                       onchange="toggleAttendanceStatus('{{ $std->id }}', 'sakit')">
                                <span class="att-pill-label att-pill-sakit">🤒 Sakit</span>
                            </label>
                            <label class="att-pill-label">
                                <input type="radio" name="attendance[{{ $std->id }}][status]" value="izin" 
                                       class="att-pill-radio" {{ $currentStatus === 'izin' ? 'checked' : '' }}
                                       onchange="toggleAttendanceStatus('{{ $std->id }}', 'izin')">
                                <span class="att-pill-label att-pill-izin">📄 Izin</span>
                            </label>
                            <label class="att-pill-label">
                                <input type="radio" name="attendance[{{ $std->id }}][status]" value="alpha" 
                                       class="att-pill-radio" {{ $currentStatus === 'alpha' ? 'checked' : '' }}
                                       onchange="toggleAttendanceStatus('{{ $std->id }}', 'alpha')">
                                <span class="att-pill-label att-pill-alpha">❌ Alpha</span>
                            </label>
                        </div>

                        {{-- BUKU KARTU SISWA --}}
                        <a href="{{ route('tahfidz.student-card', ['group' => $activeGroup->id, 'student' => $std->id]) }}" 
                           target="_blank" title="Buka Kartu Hafalan"
                           style="background: #f8fafc; border: 1.5px solid #cbd5e1; color: #475569; padding: 7px 11px; border-radius: 9px; text-decoration: none; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="fas fa-book-reader" style="color: #047857;"></i>
                            <span>Kartu</span>
                        </a>

                        @if($cleanPhone)
                            <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}" target="_blank" title="Chat Ortu via WA"
                               style="background: #ecfdf5; border: 1.5px solid #86efac; color: #166534; padding: 7px 10px; border-radius: 9px; text-decoration: none; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fab fa-whatsapp" style="color: #25D366; font-size: 0.95rem;"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- BARIS BAWAH: INPUT CAPAIAN HAFALAN (HANYA AKTIF JIKA HADIR) --}}
                <div class="hafalan-box-wrap" id="hafalan-section-{{ $std->id }}" 
                     style="{{ $currentStatus !== 'hadir' ? 'display: none;' : '' }}">
                    
                    {{-- 1. SURAH & AYAT --}}
                    <div>
                        <div class="hafalan-field-label">
                            <span>📖 Capaian Surah & Ayat</span>
                        </div>
                        <input type="text" name="attendance[{{ $std->id }}][capaian]" 
                               id="capaian-input-{{ $std->id }}"
                               list="surahList"
                               class="input-clean" 
                               value="{{ $currentCapaian }}"
                               placeholder="Cth: An-Naba' 1-20"
                               autocomplete="off">
                    </div>

                    {{-- 2. JUMLAH BARIS (OPSIONAL) --}}
                    <div>
                        <div class="hafalan-field-label">
                            <span>📏 Jumlah Baris</span>
                        </div>
                        <input type="number" name="attendance[{{ $std->id }}][jumlah_baris]" 
                               class="input-clean" 
                               min="0" max="500"
                               value="{{ $currentBaris !== '' ? $currentBaris : '' }}"
                               placeholder="Cth: 10">
                    </div>

                    {{-- 3. STATUS LULUS / BELUM --}}
                    <div>
                        <div class="hafalan-field-label">
                            <span>🎯 Status Setor</span>
                        </div>
                        <div class="lulus-pills">
                            <label class="lulus-pill-btn" style="padding: 0; margin: 0;">
                                <input type="radio" name="attendance[{{ $std->id }}][hafalan_status]" value="LULUS" 
                                       class="lulus-radio" style="display: none;" 
                                       {{ $currentLulus === 'LULUS' ? 'checked' : '' }}>
                                <span class="lulus-pill-btn btn-lulus" style="display: block;">✓ LULUS</span>
                            </label>
                            <label class="lulus-pill-btn" style="padding: 0; margin: 0;">
                                <input type="radio" name="attendance[{{ $std->id }}][hafalan_status]" value="BELUM LULUS" 
                                       class="lulus-radio" style="display: none;"
                                       {{ $currentLulus === 'BELUM LULUS' ? 'checked' : '' }}>
                                <span class="lulus-pill-btn btn-ulang" style="display: block;">✗ ULANG</span>
                            </label>
                        </div>
                    </div>

                    {{-- 4. TUGAS BESOK / CATATAN GURU --}}
                    <div>
                        <div class="hafalan-field-label">
                            <span>📝 Tugas Besok / Catatan</span>
                        </div>
                        <input type="text" name="attendance[{{ $std->id }}][tugas_besok]" 
                               class="input-clean" 
                               value="{{ $currentNotes }}"
                               placeholder="Cth: Lanjut ayat 21-40">
                    </div>

                </div>

                {{-- KETERANGAN TIDAK HADIR --}}
                <div id="absent-note-{{ $std->id }}" 
                     style="margin-top: 10px; font-size: 0.82rem; color: #64748b; font-style: italic; {{ $currentStatus === 'hadir' ? 'display: none;' : '' }}">
                    Santri berstatus <strong id="absent-text-{{ $std->id }}">{{ strtoupper($currentStatus) }}</strong> (tidak perlu mengisi setoran hafalan).
                </div>

            </div>
        @empty
            <div style="background: white; border-radius: 16px; padding: 40px 20px; text-align: center; color: #94a3b8; border: 1px solid #e2e8f0;">
                <i class="fas fa-users-slash" style="font-size: 2.5rem; margin-bottom: 12px; color: #cbd5e1;"></i>
                <h4 style="margin: 0; color: #475569;">Belum Ada Santri di Kelompok Ini</h4>
                <p style="font-size: 0.85rem; margin-top: 6px;">Hubungi Admin / Tim Kurikulum untuk memasukkan santri ke kelompok Anda.</p>
            </div>
        @endforelse

        {{-- 5. STICKY BOTTOM BAR (TOMBOL SIMPAN UTAMA) --}}
        @if($activeGroup->students->isNotEmpty())
            <div class="sticky-bottom-bar">
                <div class="sticky-content">
                    <div style="font-size: 0.88rem; color: #334155;">
                        <i class="fas fa-calendar-day" style="color: #059669; margin-right: 5px;"></i>
                        Sesi Tanggal: <strong>{{ \Carbon\Carbon::parse($sessionDate)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong>
                        <span style="margin: 0 8px; color: #cbd5e1;">|</span>
                        Total: <strong>{{ $activeGroup->students->count() }} Santri</strong>
                    </div>

                    <div style="display: flex; gap: 10px; align-items: center;">
                        <button type="button" onclick="openWaModal()" 
                                style="background: #25D366; color: white; border: none; padding: 11px 18px; border-radius: 10px; font-weight: 800; font-size: 0.9rem; cursor: pointer; display: inline-flex; align-items: center; gap: 7px;">
                            <i class="fab fa-whatsapp" style="font-size: 1.1rem;"></i> Share WA
                        </button>

                        <button type="submit" class="btn-simpan-main" 
                                style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border: none; padding: 11px 26px; border-radius: 10px; font-weight: 800; font-size: 0.95rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(4, 120, 87, 0.4);">
                            <i class="fas fa-save" style="font-size: 1.1rem;"></i>
                            <span>SIMPAN ABSENSI & SETORAN</span>
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
        <div style="background: #075e54; color: white; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; font-size: 1.05rem;">
                <i class="fab fa-whatsapp" style="font-size: 1.3rem; color: #25D366;"></i>
                <span>Laporan WhatsApp Halaqah</span>
            </div>
            <button type="button" onclick="closeWaModal()" style="background: transparent; border: none; color: white; font-size: 1.3rem; cursor: pointer;">
                &times;
            </button>
        </div>

        <div style="padding: 20px;">
            <p style="margin: 0 0 10px 0; font-size: 0.85rem; color: #475569;">
                Teks laporan otomatis tersusun rapi untuk dikirim ke Grup WhatsApp Kelas atau Paguyuban Wali Santri:
            </p>

            <textarea id="waMessageTextarea" readonly 
                      style="width: 100%; height: 260px; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 12px; font-family: monospace; font-size: 0.82rem; color: #0f172a; background: #f8fafc; resize: none; box-sizing: border-box; line-height: 1.45;">{{ $whatsappMessage }}</textarea>

            <div style="display: flex; gap: 10px; margin-top: 16px; flex-wrap: wrap;">
                @php
                    $encodedMsg = urlencode($whatsappMessage);
                @endphp
                <a href="https://api.whatsapp.com/send?text={{ $encodedMsg }}" target="_blank" 
                   style="flex: 2; background: #25D366; color: white; border: none; padding: 11px 16px; border-radius: 10px; font-weight: 800; font-size: 0.9rem; text-decoration: none; text-align: center; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fab fa-whatsapp" style="font-size: 1.15rem;"></i> Kirim ke WhatsApp Sekarang
                </a>
                <button type="button" onclick="copyWaText()" 
                        style="flex: 1; background: #f1f5f9; color: #0f172a; border: 1px solid #cbd5e1; padding: 11px 14px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                    <i class="fas fa-copy"></i> Salin Teks
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
