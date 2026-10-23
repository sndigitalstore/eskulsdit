@extends('layouts.app')

@section('title', 'Ruang Halaqah Tahfidz: ' . $activeGroup->name)
@section('page-title', 'Mentoring Tahfidz')

@push('styles')
<style>
    .halaqah-tabs {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 8px;
        margin-bottom: 20px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .halaqah-tabs::-webkit-scrollbar {
        display: none;
    }
    .halaqah-tab-item {
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
        transition: all 0.2s ease;
        white-space: nowrap;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }
    .halaqah-tab-item:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .halaqah-tab-item.active {
        background: #047857;
        color: #ffffff;
        border-color: #047857;
        box-shadow: 0 4px 12px rgba(4, 120, 87, 0.25);
    }
    .halaqah-banner {
        background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
        border-radius: 18px;
        padding: 24px 26px;
        color: white;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.3);
        position: relative;
        overflow: hidden;
    }
    .halaqah-banner::after {
        content: '\f68b';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: -20px;
        bottom: -30px;
        font-size: 11rem;
        color: rgba(255, 255, 255, 0.08);
        pointer-events: none;
    }
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .kpi-box {
        background: white;
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        border: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .kpi-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #ecfdf5;
        color: #047857;
        font-weight: 800;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #a7f3d0;
        flex-shrink: 0;
    }
    .btn-quick-setor {
        background: #059669;
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
        transition: all 0.15s;
    }
    .btn-quick-setor:hover {
        background: #047857;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);
    }
    .btn-quick-wa {
        background: #25D366;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s;
    }
    .btn-quick-wa:hover {
        background: #128C7E;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(37, 211, 102, 0.3);
    }
    .btn-quick-card {
        background: #f8fafc;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 8px 11px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.82rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s;
    }
    .btn-quick-card:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .badge-quality {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.74rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .badge-lancar { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-cukup  { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
    .badge-perlu  { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

    /* MODAL STYLES */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(5px);
        z-index: 1050;
        align-items: center;
        justify-content: center;
        padding: 14px;
    }
    .modal-overlay.active { display: flex; }
    .modal-card {
        background: white;
        border-radius: 18px;
        width: 100%;
        max-width: 620px;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35);
        overflow: hidden;
        animation: modalSlide 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        max-height: 94vh;
        display: flex;
        flex-direction: column;
    }
    @keyframes modalSlide {
        from { opacity: 0; transform: translateY(20px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-header-styled {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-body-scroll {
        padding: 20px;
        overflow-y: auto;
        flex: 1;
        -webkit-overflow-scrolling: touch;
    }
    .modal-footer-styled {
        padding: 14px 20px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .quick-template-chip {
        display: inline-block;
        font-size: 0.74rem;
        background: #f1f5f9;
        color: #475569;
        padding: 5px 10px;
        border-radius: 20px;
        cursor: pointer;
        margin: 3px;
        transition: all 0.15s;
        border: 1px solid #e2e8f0;
    }
    .quick-template-chip:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* RESPONSIVE ADAPTATIONS FOR SMARTPHONES */
    @media (min-width: 769px) {
        .mobile-cards-view { display: none !important; }
        .desktop-table-view { display: block !important; }
    }
    @media (max-width: 768px) {
        .desktop-table-view { display: none !important; }
        .mobile-cards-view { display: flex !important; flex-direction: column; gap: 12px; padding: 12px; }
        
        .halaqah-banner {
            padding: 18px 16px;
            border-radius: 14px;
        }
        .halaqah-banner h1 {
            font-size: 1.35rem !important;
        }
        .banner-content-wrap {
            flex-direction: column !important;
            gap: 14px !important;
        }
        .banner-actions {
            width: 100% !important;
            flex-direction: column !important;
            gap: 8px !important;
        }
        .banner-actions a, .banner-actions button {
            width: 100% !important;
            justify-content: center !important;
            text-align: center !important;
            box-sizing: border-box;
            padding: 10px 14px !important;
        }
        .kpi-grid {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .kpi-box {
            padding: 12px 14px;
            gap: 10px;
        }
        .kpi-box:nth-child(3) {
            grid-column: span 2;
        }
        .kpi-icon-wrap {
            width: 40px;
            height: 40px;
            font-size: 1.15rem;
        }
        .kpi-box h3, .kpi-box div[style*="font-size: 1.5rem"] {
            font-size: 1.25rem !important;
        }
        .modal-card {
            width: 100% !important;
            max-width: 100% !important;
            border-radius: 16px !important;
        }
        .modal-header-styled {
            padding: 14px 16px !important;
        }
        .modal-body-scroll {
            padding: 16px !important;
        }
        .modal-footer-styled {
            padding: 12px 16px !important;
            flex-direction: column-reverse !important;
            gap: 8px !important;
        }
        .modal-footer-styled button {
            width: 100% !important;
            justify-content: center !important;
        }
        .modal-grid-2col {
            grid-template-columns: 1fr !important;
            gap: 10px !important;
        }
        .quality-options-grid {
            grid-template-columns: 1fr !important;
            gap: 8px !important;
        }
    }
</style>
@endpush

@section('content')
<div style="margin-bottom: 30px;">

    <!-- TABS JIKA GURU MEMILIKI LEBIH DARI 1 KELOMPOK -->
    @if($myGroups->count() > 1)
        <div class="halaqah-tabs">
            @foreach($myGroups as $grp)
                <a href="{{ route('tahfidz-groups.index', ['group_id' => $grp->id]) }}" 
                   class="halaqah-tab-item {{ $grp->id == $activeGroup->id ? 'active' : '' }}">
                    <i class="fas fa-quran"></i>
                    <span>{{ $grp->name }}</span>
                    <span style="font-size: 0.72rem; padding: 2px 7px; border-radius: 10px; background: rgba(0,0,0,0.12); font-weight: 800;">
                        {{ $grp->students_count }}
                    </span>
                </a>
            @endforeach
        </div>
    @endif

    <!-- HALAQAH BANNER UTAMA -->
    <div class="halaqah-banner">
        <div class="banner-content-wrap" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; position: relative; z-index: 2;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(4px); padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; margin-bottom: 8px; letter-spacing: 0.5px; text-transform: uppercase;">
                    <i class="fas fa-star-and-crescent"></i> Ruang Halaqah Tahfidz
                </div>
                <h1 style="margin: 0 0 6px 0; font-size: 1.65rem; font-weight: 800; text-shadow: 0 2px 4px rgba(0,0,0,0.1); word-break: break-word;">
                    {{ $activeGroup->name }}
                </h1>
                <div style="display: flex; gap: 14px 20px; flex-wrap: wrap; font-size: 0.88rem; opacity: 0.95;">
                    <div><i class="fas fa-user-tie"></i> Musyrif: <strong>{{ $activeGroup->mentor_name ?? Auth::user()->name }}</strong></div>
                    <div><i class="fas fa-bullseye"></i> Target: <strong>{{ $activeGroup->target_level ?? 'Juz 30' }}</strong></div>
                    @if($activeGroup->schedule)
                        <div><i class="fas fa-clock"></i> Jadwal: <strong>{{ $activeGroup->schedule }}</strong></div>
                    @endif
                    <div><i class="fas fa-calendar-alt"></i> TP: <strong>{{ $activeYear?->name ?? 'Aktif' }}</strong></div>
                </div>
            </div>

            <!-- ACTION BUTTONS UTAMA HALAQAH -->
            <div class="banner-actions" style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                <a href="{{ route('tahfidz.attendance.create', ['group' => $activeGroup->id, 'date' => $today]) }}" 
                   class="btn-action-header" 
                   style="background: #ffffff; color: #047857; font-weight: 800; padding: 10px 18px; border-radius: 10px; box-shadow: 0 4px 14px rgba(0,0,0,0.18); display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: 2px solid #34d399;">
                    <i class="fas fa-door-open" style="font-size: 1.15rem; color: #059669;"></i>
                    <span>Input Sesi & Absensi (Satu Pintu)</span>
                </a>
                <a href="{{ route('reports.tahfidz.export', ['group_id' => $activeGroup->id, 'year_id' => $activeYear?->id]) }}" 
                   class="btn-action-header" 
                   style="background: #0284c7; color: #ffffff; font-weight: 700; padding: 10px 14px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(2,132,199,0.3);">
                    <i class="fas fa-file-excel"></i> Download Excel
                </a>
                <button type="button" onclick="openGroupShareModal()" 
                        class="btn-action-header" 
                        style="background: #25D366; color: #ffffff; font-weight: 800; padding: 10px 16px; border-radius: 10px; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35); display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fab fa-whatsapp" style="font-size: 1.15rem;"></i> Share WA
                </button>
                <a href="{{ route('tahfidz.print-group-recap', $activeGroup->id) }}" 
                   target="_blank" class="btn-action-header" 
                   style="background: rgba(255, 255, 255, 0.18); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.35); font-weight: 700; padding: 10px 14px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-print"></i> Cetak Rekap
                </a>
            </div>
        </div>
    </div>

    <!-- STATISTIK RINGKAS HALAQAH -->
    <div class="kpi-grid">
        <div class="kpi-box">
            <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div style="font-size: 0.74rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Santri</div>
                <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">{{ $activeGroup->students->count() }} <span style="font-size: 0.8rem; font-weight: 500; color: #64748b;">anak</span></div>
            </div>
        </div>

        <div class="kpi-box">
            <div class="kpi-icon-wrap" style="background: #eff6ff; color: #2563eb;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <div style="font-size: 0.74rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Kehadiran Hari Ini</div>
                <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">
                    {{ $todayAttendances->where('status', 'hadir')->count() }} 
                    <small style="font-size: 0.75rem; font-weight: normal; color: #64748b;">/ {{ $activeGroup->students->count() }}</small>
                </div>
            </div>
        </div>

        <div class="kpi-box">
            <div class="kpi-icon-wrap" style="background: #fef3c7; color: #d97706;">
                <i class="fas fa-book-open"></i>
            </div>
            <div>
                <div style="font-size: 0.74rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Sudah Setor Hari Ini</div>
                <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">
                    {{ $todayRecords->count() }} 
                    <small style="font-size: 0.75rem; font-weight: normal; color: #64748b;">Santri</small>
                </div>
            </div>
        </div>

        <div class="kpi-box">
            <div class="kpi-icon-wrap" style="background: #f0fdf4; color: #16a34a;">
                <i class="fas fa-align-left"></i>
            </div>
            <div>
                <div style="font-size: 0.74rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Baris Dihafal</div>
                <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">
                    {{ (int) ($totalGroupLines ?? 0) }} 
                    <small style="font-size: 0.75rem; font-weight: normal; color: #64748b;">Baris</small>
                </div>
            </div>
        </div>
    </div>

    <!-- WRAPPER DAFTAR SANTRI -->
    <div class="card" style="padding: 0; overflow: hidden; border-radius: 16px; border: 1px solid #e2e8f0;">
        <div style="padding: 16px 20px; background: #ffffff; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-user-graduate" style="color: #059669;"></i> Daftar Santri Halaqah
                </h3>
                <p style="margin: 3px 0 0 0; font-size: 0.8rem; color: #64748b;">
                    ⭐ <strong>Jalur Satu Pintu:</strong> Gunakan <strong>"Input Sesi & Absensi (Satu Pintu)"</strong> di atas untuk absensi & hafalan serentak, atau klik <strong>"+ Setor"</strong> untuk catatan cepat per santri.
                </p>
            </div>
            <div style="position: relative; width: 100%; max-width: 250px;">
                <input type="text" id="searchInput" onkeyup="filterStudentTable()" placeholder="Cari santri atau kelas..." class="form-control" style="padding: 8px 12px 8px 34px; font-size: 0.85rem; border-radius: 10px;">
                <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
            </div>
        </div>

        @php
            $attStyles = [
                'hadir' => 'background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;',
                'sakit' => 'background: #fef9c3; color: #854d0e; border: 1px solid #fef08a;',
                'izin'  => 'background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;',
                'alpha' => 'background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;',
            ];
        @endphp

        <!-- 1. TAMPILAN DESKTOP (TABEL BESAR) -->
        <div class="desktop-table-view table-responsive">
            <table class="table" style="margin-bottom: 0;">
                <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th style="padding: 13px 16px; width: 4%; text-align: center;">No</th>
                        <th style="padding: 13px 16px;">Nama Santri</th>
                        <th style="padding: 13px 16px; width: 9%; text-align: center;">Kelas</th>
                        <th style="padding: 13px 16px; width: 15%;">Kontak Ortu</th>
                        <th style="padding: 13px 16px; width: 26%;">Capaian Hafalan Terakhir</th>
                        <th style="padding: 13px 16px; width: 13%; text-align: center;">Kehadiran Hari Ini</th>
                        <th style="padding: 13px 16px; width: 21%; text-align: center;">Aksi Cepat (1-Klik)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeGroup->students as $idx => $std)
                        @php
                            $records = $latestRecords->get($std->id, collect());
                            $lastRec = $records->first();
                            $attToday = $todayAttendances->get($std->id);
                            
                            $shareSurah = $lastRec ? $lastRec->surah_name : ($activeGroup->target_level ?? 'Juz 30');
                            $shareAyah = $lastRec && $lastRec->start_ayah ? "Ayat {$lastRec->start_ayah}" . ($lastRec->end_ayah && $lastRec->end_ayah != $lastRec->start_ayah ? "-{$lastRec->end_ayah}" : '') : 'Lengkap';
                            if ($lastRec && ($lastRec->jumlah_baris ?? 0) > 0) {
                                $shareAyah .= " [{$lastRec->jumlah_baris} Baris]";
                            }
                            $shareQuality = $lastRec ? ($lastRec->quality == 'lancar' ? 'Lancar (Mumtaz)' : ($lastRec->quality == 'cukup' ? 'Cukup Lancar' : 'Perlu Bimbingan')) : 'Sedang Berproses';
                            $shareType = $lastRec ? ($lastRec->type == 'setoran_baru' ? 'Ziyadah (Hafalan Baru)' : 'Muroja\'ah (Mengulang)') : 'Hafalan Qur\'an';
                            $shareDate = $lastRec ? \Carbon\Carbon::parse($lastRec->session_date)->isoFormat('dddd, D MMMM Y') : \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y');
                            $shareNotes = $lastRec && $lastRec->notes ? $lastRec->notes : 'Pertahankan hafalan dengan muroja\'ah istiqomah di rumah.';
                        @endphp
                        <tr class="student-row" style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                            <td style="padding: 14px 16px; text-align: center; font-weight: 700; color: #64748b;">
                                {{ $idx + 1 }}
                            </td>
                            <td style="padding: 14px 16px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div class="student-avatar">
                                        {{ strtoupper(substr($std->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="student-name-text" style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                                            {{ $std->name }}
                                        </div>
                                        <small style="color: #64748b; font-family: monospace; font-size: 0.78rem;">
                                            NIS: {{ $std->nis ?? '-' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <span class="student-class-text" style="background: #f1f5f9; color: #334155; font-weight: 700; font-size: 0.82rem; padding: 3px 8px; border-radius: 6px;">
                                    {{ $std->class }}
                                </span>
                            </td>
                            <td style="padding: 14px 16px;">
                                @if($std->parent_phone)
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $std->parent_phone);
                                        if (str_starts_with($cleanPhone, '0')) {
                                            $cleanPhone = '62' . substr($cleanPhone, 1);
                                        }
                                    @endphp
                                    <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}" target="_blank" style="display: inline-flex; align-items: center; gap: 5px; color: #059669; text-decoration: none; font-weight: 600; font-size: 0.82rem;">
                                        <i class="fab fa-whatsapp" style="font-size: 0.95rem; color: #25D366;"></i> {{ $std->parent_phone }}
                                    </a>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">Belum ada No HP</span>
                                @endif
                            </td>
                            <td style="padding: 14px 16px;">
                                @if($lastRec)
                                    <div>
                                        <strong style="color: #0f172a; font-size: 0.88rem;">{{ $lastRec->surah_name }}</strong>
                                        @if($lastRec->start_ayah)
                                            <span style="color: #475569; font-size: 0.82rem;">
                                                (Ayat {{ $lastRec->start_ayah }}{{ $lastRec->end_ayah && $lastRec->end_ayah != $lastRec->start_ayah ? '-' . $lastRec->end_ayah : '' }})
                                            </span>
                                        @endif
                                        @if(($lastRec->jumlah_baris ?? 0) > 0)
                                            <span style="display: inline-block; background: #e0e7ff; color: #4338ca; font-weight: 700; font-size: 0.72rem; padding: 1px 6px; border-radius: 4px; margin-left: 4px;">
                                                📏 {{ $lastRec->jumlah_baris }} Baris
                                            </span>
                                        @endif
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px; flex-wrap: wrap;">
                                        <span class="badge-quality {{ $lastRec->quality == 'lancar' ? 'badge-lancar' : ($lastRec->quality == 'cukup' ? 'badge-cukup' : 'badge-perlu') }}">
                                            <i class="fas {{ $lastRec->quality == 'lancar' ? 'fa-check-circle' : 'fa-info-circle' }}"></i>
                                            {{ $lastRec->quality == 'lancar' ? 'Lancar' : ($lastRec->quality == 'cukup' ? 'Cukup' : 'Perlu Bimbingan') }}
                                        </span>
                                        <span style="font-size: 0.72rem; color: #64748b;">
                                            <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($lastRec->session_date)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.84rem; font-style: italic;">
                                        Belum ada rekaman setoran
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                @if($attToday)
                                    <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 0.76rem; font-weight: 800; text-transform: uppercase; {{ $attStyles[$attToday->status] ?? '' }}">
                                        {{ $attToday->status }}
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 3px 8px; border-radius: 20px; font-size: 0.73rem; font-weight: 600; background: #f1f5f9; color: #64748b; border: 1px dashed #cbd5e1;">
                                        Belum Diabsen
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                    <button type="button" class="btn-quick-setor" onclick="openQuickSetorModal('{{ $std->id }}', '{{ addslashes($std->name) }}', '{{ $std->class }}')" title="Catat Setoran">
                                        <i class="fas fa-plus"></i> Setor
                                    </button>
                                    <button type="button" class="btn-quick-wa" onclick="openPersonalShareModal('{{ addslashes($std->name) }}', '{{ $std->class }}', '{{ $std->parent_phone ?? '' }}', '{{ addslashes($shareSurah) }}', '{{ addslashes($shareAyah) }}', '{{ addslashes($shareQuality) }}', '{{ addslashes($shareType) }}', '{{ addslashes($shareDate) }}', '{{ addslashes($shareNotes) }}')" title="Share WA">
                                        <i class="fab fa-whatsapp"></i> WA
                                    </button>
                                    <a href="{{ route('tahfidz.student-card', ['group' => $activeGroup->id, 'student' => $std->id]) }}" class="btn-quick-card" title="Buku Kartu">
                                        <i class="fas fa-book-reader"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 50px 20px; color: #94a3b8;">
                                <i class="fas fa-users-slash" style="font-size: 2.5rem; margin-bottom: 12px; color: #cbd5e1;"></i>
                                <h4 style="margin: 0; color: #475569;">Belum Ada Santri di Kelompok Ini</h4>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 2. TAMPILAN MOBILE (KARTU SANTRI RESPONSIF - ZERO DATA CUT OFF) -->
        <div class="mobile-cards-view">
            @forelse($activeGroup->students as $idx => $std)
                @php
                    $records = $latestRecords->get($std->id, collect());
                    $lastRec = $records->first();
                    $attToday = $todayAttendances->get($std->id);
                    
                    $cleanPhone = preg_replace('/[^0-9]/', '', $std->parent_phone ?? '');
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                    
                    $shareSurah = $lastRec ? $lastRec->surah_name : ($activeGroup->target_level ?? 'Juz 30');
                    $shareAyah = $lastRec && $lastRec->start_ayah ? "Ayat {$lastRec->start_ayah}" . ($lastRec->end_ayah && $lastRec->end_ayah != $lastRec->start_ayah ? "-{$lastRec->end_ayah}" : '') : 'Lengkap';
                    $shareQuality = $lastRec ? ($lastRec->quality == 'lancar' ? 'Lancar (Mumtaz)' : ($lastRec->quality == 'cukup' ? 'Cukup Lancar' : 'Perlu Bimbingan')) : 'Sedang Berproses';
                    $shareType = $lastRec ? ($lastRec->type == 'setoran_baru' ? 'Ziyadah (Hafalan Baru)' : 'Muroja\'ah (Mengulang)') : 'Hafalan Qur\'an';
                    $shareDate = $lastRec ? \Carbon\Carbon::parse($lastRec->session_date)->isoFormat('dddd, D MMMM Y') : \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y');
                    $shareNotes = $lastRec && $lastRec->notes ? $lastRec->notes : 'Pertahankan hafalan dengan muroja\'ah istiqomah di rumah.';
                @endphp
                <div class="mobile-student-card" style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                    <!-- Identity & Presensi -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px; min-width: 0; flex: 1;">
                            <div class="student-avatar" style="width: 38px; height: 38px; font-size: 0.95rem;">
                                {{ strtoupper(substr($std->name, 0, 1)) }}
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <div class="student-name-text" style="font-weight: 800; font-size: 0.95rem; color: #0f172a; word-break: break-word; line-height: 1.3;">
                                    {{ $std->name }}
                                </div>
                                <div style="font-size: 0.76rem; color: #64748b; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-top: 2px;">
                                    <span class="student-class-text" style="background: #f1f5f9; padding: 1px 6px; border-radius: 4px; font-weight: 700; color: #334155;">
                                        Kelas {{ $std->class }}
                                    </span>
                                    @if($std->nis)
                                        <span>NIS: {{ $std->nis }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div style="flex-shrink: 0;">
                            @if($attToday)
                                <span style="display: inline-block; padding: 3px 8px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; {{ $attStyles[$attToday->status] ?? '' }}">
                                    {{ $attToday->status }}
                                </span>
                            @else
                                <span style="display: inline-block; padding: 2px 7px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; background: #f1f5f9; color: #64748b; border: 1px dashed #cbd5e1;">
                                    Belum Diabsen
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Middle: Capaian Terakhir -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 12px; margin-bottom: 12px;">
                        <div style="font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase;">
                            <i class="fas fa-quran" style="color: #059669;"></i> Capaian Hafalan Terakhir:
                        </div>
                        @if($lastRec)
                            <div style="font-size: 0.94rem; font-weight: 800; color: #0f172a; margin-top: 3px; word-break: break-word;">
                                {{ $lastRec->surah_name }}
                                @if($lastRec->start_ayah)
                                    <span style="font-size: 0.82rem; font-weight: 600; color: #475569;">
                                        (Ayat {{ $lastRec->start_ayah }}{{ $lastRec->end_ayah && $lastRec->end_ayah != $lastRec->start_ayah ? '-' . $lastRec->end_ayah : '' }})
                                    </span>
                                @endif
                                @if(($lastRec->jumlah_baris ?? 0) > 0)
                                    <span style="display: inline-block; background: #e0e7ff; color: #4338ca; font-weight: 700; font-size: 0.72rem; padding: 1px 6px; border-radius: 4px; margin-left: 4px;">
                                        📏 {{ $lastRec->jumlah_baris }} Baris
                                    </span>
                                @endif
                            </div>
                            <div style="display: flex; gap: 6px; align-items: center; margin-top: 4px; flex-wrap: wrap;">
                                <span class="badge-quality {{ $lastRec->quality == 'lancar' ? 'badge-lancar' : ($lastRec->quality == 'cukup' ? 'badge-cukup' : 'badge-perlu') }}">
                                    {{ $lastRec->quality == 'lancar' ? 'Lancar (Mumtaz)' : ($lastRec->quality == 'cukup' ? 'Cukup' : 'Perlu Bimbingan') }}
                                </span>
                                <span style="font-size: 0.72rem; color: #64748b;">
                                    <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($lastRec->session_date)->format('d/m/Y') }}
                                </span>
                                @if($lastRec->type == 'setoran_baru')
                                    <span style="font-size: 0.68rem; background: #e0f2fe; color: #0369a1; padding: 1px 5px; border-radius: 4px; font-weight: 600;">Ziyadah</span>
                                @else
                                    <span style="font-size: 0.68rem; background: #f3e8ff; color: #7e22ce; padding: 1px 5px; border-radius: 4px; font-weight: 600;">Muroja'ah</span>
                                @endif
                            </div>
                        @else
                            <div style="font-size: 0.82rem; color: #94a3b8; font-style: italic; margin-top: 3px;">
                                Belum ada rekaman setoran
                            </div>
                        @endif

                        @if($std->parent_phone)
                            <div style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed #e2e8f0; font-size: 0.78rem; display: flex; align-items: center; justify-content: space-between;">
                                <span style="color: #64748b;">Kontak Ortu:</span>
                                <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}" target="_blank" style="color: #059669; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fab fa-whatsapp" style="color: #25D366; font-size: 0.92rem;"></i> {{ $std->parent_phone }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Actions Bar (Mudah Ditekan dengan Jempol) -->
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <button type="button" 
                                class="btn-quick-setor" 
                                style="flex: 2; justify-content: center; padding: 9px 12px; font-size: 0.85rem;"
                                onclick="openQuickSetorModal('{{ $std->id }}', '{{ addslashes($std->name) }}', '{{ $std->class }}')">
                            <i class="fas fa-plus-circle"></i> Input Setoran
                        </button>
                        <button type="button" 
                                class="btn-quick-wa" 
                                style="flex: 1; justify-content: center; padding: 9px 12px; font-size: 0.85rem;"
                                onclick="openPersonalShareModal('{{ addslashes($std->name) }}', '{{ $std->class }}', '{{ $std->parent_phone ?? '' }}', '{{ addslashes($shareSurah) }}', '{{ addslashes($shareAyah) }}', '{{ addslashes($shareQuality) }}', '{{ addslashes($shareType) }}', '{{ addslashes($shareDate) }}', '{{ addslashes($shareNotes) }}')">
                            <i class="fab fa-whatsapp"></i> WA
                        </button>
                        <a href="{{ route('tahfidz.student-card', ['group' => $activeGroup->id, 'student' => $std->id]) }}" 
                           class="btn-quick-card" 
                           style="padding: 9px 12px; font-size: 0.85rem; justify-content: center;" 
                           title="Buka Kartu">
                            <i class="fas fa-book-reader"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 40px 16px; color: #94a3b8;">
                    <i class="fas fa-users-slash" style="font-size: 2.2rem; margin-bottom: 10px; color: #cbd5e1;"></i>
                    <h4 style="margin: 0; color: #475569;">Belum Ada Santri di Kelompok Ini</h4>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 1: INPUT SETORAN CEPAT (QUICK INPUT SETORAN)           -->
<!-- ============================================================== -->
<div id="quickSetorModal" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-header-styled" style="background: #ecfdf5; border-bottom: 2px solid #a7f3d0;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #059669; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                    <i class="fas fa-quran"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #064e3b; word-break: break-word;" id="modalStudentTitle">
                        Input Setoran Hafalan
                    </h3>
                    <small style="color: #047857;" id="modalStudentSub">Pilih surah, rentang ayat, dan kualitas setoran</small>
                </div>
            </div>
            <button type="button" onclick="closeQuickSetorModal()" style="border: none; background: transparent; font-size: 1.3rem; color: #64748b; cursor: pointer; padding: 4px;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="quickSetorForm" method="POST" action="">
            @csrf
            <input type="hidden" name="redirect_to" value="workspace">
            <input type="hidden" name="academic_year_id" value="{{ $selectedYearId ?? ($activeYear?->id) }}">

            <div class="modal-body-scroll">
                <!-- TANGGAL & TIPE SETORAN -->
                <div class="modal-grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 4px;">
                            <i class="fas fa-calendar-day"></i> Tanggal Setoran
                        </label>
                        <input type="date" name="session_date" value="{{ $today }}" class="form-control" required style="padding: 9px 12px; font-size: 0.88rem;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 4px;">
                            <i class="fas fa-tag"></i> Jenis Setoran
                        </label>
                        <select name="type" class="form-control" required style="padding: 9px 12px; font-size: 0.88rem;">
                            <option value="setoran_baru">Ziyadah (Hafalan Baru)</option>
                            <option value="murojaah">Muroja'ah (Mengulang)</option>
                        </select>
                    </div>
                </div>

                <!-- PILIHAN SURAH -->
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 4px;">
                        <i class="fas fa-book-open"></i> Nama Surah
                    </label>
                    <select name="surah_name" id="modalSurahSelect" class="form-control" required style="padding: 9px 12px; font-size: 0.9rem;" onchange="handleSurahSelection(this)">
                        <option value="">-- Pilih Surah --</option>
                        <optgroup label="📖 Juz 30 (An-Naba s.d. An-Nas)">
                            @foreach($surahListJuz30 as $sName)
                                <option value="{{ $sName }}" data-juz="30">{{ $sName }} (Juz 30)</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="📖 Juz 29 (Al-Mulk s.d. Al-Mursalat)">
                            @foreach($surahListJuz29 as $sName)
                                <option value="{{ $sName }}" data-juz="29">{{ $sName }} (Juz 29)</option>
                            @endforeach
                        </optgroup>
                        @if(isset($surahListJuz28))
                        <optgroup label="📖 Juz 28 (Al-Mujadilah s.d. At-Tahrim)">
                            @foreach($surahListJuz28 as $sName)
                                <option value="{{ $sName }}" data-juz="28">{{ $sName }} (Juz 28)</option>
                            @endforeach
                        </optgroup>
                        @endif
                    </select>
                    <input type="hidden" name="juz" id="modalJuzInput" value="30">
                </div>

                <!-- RENTANG AYAT & JUMLAH BARIS -->
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 4px;">
                            Ayat Mulai
                        </label>
                        <input type="number" name="start_ayah" id="modalStartAyah" value="1" min="1" class="form-control" placeholder="1" style="padding: 9px 12px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 4px;">
                            Ayat Selesai
                        </label>
                        <input type="number" name="end_ayah" id="modalEndAyah" min="1" class="form-control" placeholder="Selesai" style="padding: 9px 12px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #4338ca; margin-bottom: 4px;">
                            📏 Jml Baris
                        </label>
                        <input type="number" name="jumlah_baris" id="modalJumlahBaris" min="0" max="500" class="form-control" placeholder="mis: 15" style="padding: 9px 12px; border-color: #c7d2fe; font-weight: 700;">
                    </div>
                </div>

                <div style="background: #f0fdf4; border: 1px dashed #86efac; border-radius: 8px; padding: 8px 12px; margin-bottom: 14px; font-size: 0.78rem; color: #166534; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-sync-alt" style="color: #059669; font-size: 0.9rem;"></i>
                    <span><strong>Sistem Satu Pintu:</strong> Menyimpan setoran ini otomatis mencatat kehadiran <em>HADIR</em> dan sinkron ke rekapan & kartu santri.</span>
                </div>

                <!-- KUALITAS SETORAN -->
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 4px;">
                        <i class="fas fa-medal"></i> Kualitas Bacaan / Hafalan
                    </label>
                    <div class="quality-options-grid" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px;">
                        <label style="display: flex; align-items: center; gap: 8px; padding: 9px; border-radius: 10px; border: 2px solid #bbf7d0; background: #f0fdf4; cursor: pointer; font-size: 0.82rem; font-weight: 700; color: #166534;">
                            <input type="radio" name="quality" value="lancar" checked>
                            <span>Lancar (Mumtaz)</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; padding: 9px; border-radius: 10px; border: 2px solid #fef08a; background: #fefce8; cursor: pointer; font-size: 0.82rem; font-weight: 700; color: #854d0e;">
                            <input type="radio" name="quality" value="cukup">
                            <span>Cukup</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; padding: 9px; border-radius: 10px; border: 2px solid #fecaca; background: #fef2f2; cursor: pointer; font-size: 0.82rem; font-weight: 700; color: #991b1b;">
                            <input type="radio" name="quality" value="perlu_perbaikan">
                            <span>Perlu Perbaikan</span>
                        </label>
                    </div>
                </div>

                <!-- CATATAN MUSYRIF & QUICK TEMPLATES -->
                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 4px;">
                        <i class="fas fa-comment-dots"></i> Catatan Musyrif (Otomatis masuk pesan WA)
                    </label>
                    <textarea name="notes" id="modalNotesInput" rows="2" class="form-control" placeholder="Contoh: Bacaan tartil dan makhraj fasih. Pertahankan muroja'ah di rumah." style="padding: 9px 12px; font-size: 0.85rem; width: 100%; box-sizing: border-box;"></textarea>
                    
                    <div style="margin-top: 8px;">
                        <span style="font-size: 0.74rem; color: #64748b; font-weight: 600;">Template Cepat:</span>
                        <div style="margin-top: 4px; display: flex; flex-wrap: wrap; gap: 4px;">
                            <span class="quick-template-chip" onclick="insertTemplateNote('Alhamdulillah hafalan lancar, tartil, dan mutqin.')">✨ Lancar & Tartil</span>
                            <span class="quick-template-chip" onclick="insertTemplateNote('Alhamdulillah tuntas surah ini, siap lanjut surah berikutnya.')">🚀 Siap Lanjut Surah</span>
                            <span class="quick-template-chip" onclick="insertTemplateNote('Hafalan baik, mohon penguatan pada makhraj huruf dan panjang mad.')">🎯 Kuatkan Makhraj/Mad</span>
                            <span class="quick-template-chip" onclick="insertTemplateNote('Mohon didampingi muroja\'ah rutin bersama orang tua di rumah.')">🏡 Muroja\'ah di Rumah</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer-styled">
                <button type="button" onclick="closeQuickSetorModal()" class="btn-action-header btn-dark" style="background: #94a3b8; padding: 10px 18px;">
                    Batal
                </button>
                <button type="submit" class="btn-action-header btn-green" style="background: #059669; font-weight: 800; padding: 10px 24px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);">
                    <i class="fas fa-save"></i> Simpan Setoran
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 2: SHARE WHATSAPP PERSONAL LAPORAN SISWA                -->
<!-- ============================================================== -->
<div id="personalShareModal" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-header-styled" style="background: #f0fdf4; border-bottom: 2px solid #86efac;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #25D366; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #14532d;">
                        Kirim Laporan ke WhatsApp
                    </h3>
                    <small style="color: #15803d;" id="personalShareSub">Laporan untuk orang tua santri</small>
                </div>
            </div>
            <button type="button" onclick="closePersonalShareModal()" style="border: none; background: transparent; font-size: 1.3rem; color: #64748b; cursor: pointer; padding: 4px;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body-scroll">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; margin-bottom: 14px;">
                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #64748b; margin-bottom: 6px; text-transform: uppercase;">
                    <i class="fas fa-eye"></i> Pratinjau Pesan WhatsApp:
                </label>
                <textarea id="personalShareTextarea" rows="10" class="form-control" style="font-family: monospace; font-size: 0.82rem; line-height: 1.5; background: white; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; width: 100%; box-sizing: border-box;"></textarea>
            </div>

            <div id="parentPhoneBanner" style="display: flex; align-items: center; justify-content: space-between; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 10px 14px; border-radius: 10px;">
                <div style="display: flex; align-items: center; gap: 8px; color: #065f46; font-size: 0.82rem; word-break: break-all;">
                    <i class="fas fa-phone-alt"></i> HP Ortu: <strong id="parentPhoneNumber">-</strong>
                </div>
                <span id="parentPhoneStatus" style="font-size: 0.72rem; font-weight: 700; color: #047857; flex-shrink: 0;">Tersedia</span>
            </div>
        </div>

        <div class="modal-footer-styled">
            <button type="button" onclick="copyPersonalShareText()" class="btn-action-header btn-dark" style="background: #64748b; padding: 10px 16px;">
                <i class="fas fa-copy"></i> Salin Teks
            </button>
            <button type="button" onclick="openShareToGroup()" class="btn-action-header btn-blue" style="background: #0284c7; padding: 10px 18px;">
                <i class="fas fa-share-alt"></i> Share ke Grup WA
            </button>
            <button type="button" id="btnSendDirectWa" onclick="sendDirectPersonalWa()" class="btn-action-header btn-green" style="background: #25D366; color: white; font-weight: 800; padding: 10px 20px;">
                <i class="fab fa-whatsapp"></i> Kirim ke HP Ortu
            </button>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 3: SHARE REKAP SELURUH HALAQAH HARI INI KE GRUP WA       -->
<!-- ============================================================== -->
<div id="groupShareModal" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-header-styled" style="background: #ecfdf5; border-bottom: 2px solid #a7f3d0;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #047857; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #064e3b;">
                        Share Rekap ke Grup WA Paguyuban
                    </h3>
                    <small style="color: #047857;">Rangkuman seluruh setoran santri hari ini</small>
                </div>
            </div>
            <button type="button" onclick="closeGroupShareModal()" style="border: none; background: transparent; font-size: 1.3rem; color: #64748b; cursor: pointer; padding: 4px;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body-scroll">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; margin-bottom: 10px;">
                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #64748b; margin-bottom: 6px; text-transform: uppercase;">
                    <i class="fas fa-align-left"></i> Pesan Rekapitulasi Halaqah Hari Ini:
                </label>
                <textarea id="groupShareTextarea" rows="11" class="form-control" style="font-family: monospace; font-size: 0.82rem; line-height: 1.5; background: white; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; width: 100%; box-sizing: border-box;"></textarea>
            </div>
            <small style="color: #64748b; display: block; font-size: 0.78rem;">
                <i class="fas fa-info-circle"></i> Teks di atas otomatis merangkum semua santri yang sudah menyetor hari ini.
            </small>
        </div>

        <div class="modal-footer-styled">
            <button type="button" onclick="copyGroupShareText()" class="btn-action-header btn-dark" style="background: #64748b; padding: 10px 18px;">
                <i class="fas fa-copy"></i> Salin Teks
            </button>
            <button type="button" onclick="sendGroupShareWa()" class="btn-action-header btn-green" style="background: #25D366; color: white; font-weight: 800; padding: 10px 24px;">
                <i class="fab fa-whatsapp"></i> Buka WhatsApp & Kirim
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const currentGroupId = '{{ $activeGroup->id }}';
    const mentorName = '{{ addslashes($activeGroup->mentor_name ?? Auth::user()->name) }}';
    const halaqahName = '{{ addslashes($activeGroup->name) }}';
    const todayFormatted = '{{ \Carbon\Carbon::now()->isoFormat("dddd, D MMMM Y") }}';
    let currentPersonalPhone = '';

    // ==========================================
    // 1. FILTER PENCARIAN SISWA DI TABEL & MOBILE CARDS
    // ==========================================
    function filterStudentTable() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        
        // Filter desktop table rows
        const rows = document.querySelectorAll(".student-row");
        rows.forEach(row => {
            const name = row.querySelector(".student-name-text")?.innerText.toLowerCase() || '';
            const cls = row.querySelector(".student-class-text")?.innerText.toLowerCase() || '';
            if (name.includes(input) || cls.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });

        // Filter mobile cards
        const cards = document.querySelectorAll(".mobile-student-card");
        cards.forEach(card => {
            const name = card.querySelector(".student-name-text")?.innerText.toLowerCase() || '';
            const cls = card.querySelector(".student-class-text")?.innerText.toLowerCase() || '';
            if (name.includes(input) || cls.includes(input)) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    }

    // ==========================================
    // 2. MODAL INPUT SETORAN CEPAT
    // ==========================================
    function openQuickSetorModal(studentId, studentName, studentClass) {
        document.getElementById('modalStudentTitle').innerText = 'Setoran: ' + studentName;
        document.getElementById('modalStudentSub').innerText = 'Kelas ' + studentClass + ' • ' + halaqahName;
        
        const form = document.getElementById('quickSetorForm');
        form.action = `/tahfidz-groups/${currentGroupId}/students/${studentId}/store`;

        const jbInput = document.getElementById('modalJumlahBaris');
        if (jbInput) jbInput.value = '';

        document.getElementById('quickSetorModal').classList.add('active');
    }

    function closeQuickSetorModal() {
        document.getElementById('quickSetorModal').classList.remove('active');
    }

    function handleSurahSelection(select) {
        const selectedOption = select.options[select.selectedIndex];
        const juz = selectedOption.getAttribute('data-juz') || 30;
        document.getElementById('modalJuzInput').value = juz;
    }

    function insertTemplateNote(text) {
        const textarea = document.getElementById('modalNotesInput');
        if (textarea.value.trim() !== '') {
            textarea.value += ' ' + text;
        } else {
            textarea.value = text;
        }
    }

    // ==========================================
    // 3. MODAL SHARE WA PERSONAL
    // ==========================================
    function openPersonalShareModal(studentName, studentClass, parentPhone, surahName, ayah, quality, type, dateStr, notes) {
        currentPersonalPhone = parentPhone.replace(/[^0-9]/g, '');
        if (currentPersonalPhone.startsWith('0')) {
            currentPersonalPhone = '62' + currentPersonalPhone.substring(1);
        }

        document.getElementById('personalShareSub').innerText = 'Santri: ' + studentName + ' (' + studentClass + ')';
        
        const phoneElem = document.getElementById('parentPhoneNumber');
        const phoneStatusElem = document.getElementById('parentPhoneStatus');
        const btnDirect = document.getElementById('btnSendDirectWa');

        if (currentPersonalPhone) {
            phoneElem.innerText = parentPhone;
            phoneStatusElem.innerText = 'Tersedia';
            phoneStatusElem.style.color = '#047857';
            btnDirect.style.display = 'inline-flex';
        } else {
            phoneElem.innerText = 'Belum Ada';
            phoneStatusElem.innerText = 'Tidak Ada Nomor';
            phoneStatusElem.style.color = '#dc2626';
            btnDirect.style.display = 'none';
        }

        const message = 
`Assalamu'alaikum Warahmatullahi Wabarakatuh Ayah/Bunda.

Berikut laporan capaian Tahfidz ananda di *SDIT An Nadzir*:
👤 *Nama:* ${studentName}
🏫 *Kelas:* ${studentClass}
📖 *Setoran:* QS. ${surahName} (${ayah})
📌 *Jenis:* ${type}
⭐ *Kualitas:* ${quality}
📝 *Catatan Musyrif:* ${notes}
📅 *Tanggal:* ${dateStr}
🕌 *Musyrif:* ${mentorName}

Alhamdulillah ananda telah menyetorkan hafalan hari ini. Mohon Ayah/Bunda terus mendampingi dan memotivasi pengulangan (muroja'ah) ananda di rumah.

Jazakumullahu Khairan Katsiran.
Wassalamu'alaikum Warahmatullahi Wabarakatuh.
_*SDIT An Nadzir - Mencetak Generasi Qur'ani*_`;

        document.getElementById('personalShareTextarea').value = message;
        document.getElementById('personalShareModal').classList.add('active');
    }

    function closePersonalShareModal() {
        document.getElementById('personalShareModal').classList.remove('active');
    }

    function sendDirectPersonalWa() {
        const text = document.getElementById('personalShareTextarea').value;
        if (!currentPersonalPhone) {
            alert('Nomor HP Orang Tua belum terdata di sistem.');
            return;
        }
        const url = `https://api.whatsapp.com/send?phone=${currentPersonalPhone}&text=${encodeURIComponent(text)}`;
        window.open(url, '_blank');
    }

    function openShareToGroup() {
        const text = document.getElementById('personalShareTextarea').value;
        const url = `https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`;
        window.open(url, '_blank');
    }

    function copyPersonalShareText() {
        const text = document.getElementById('personalShareTextarea').value;
        navigator.clipboard.writeText(text).then(() => {
            alert('Teks laporan berhasil disalin ke clipboard!');
        });
    }

    // ==========================================
    // 4. MODAL SHARE REKAP KELOMPOK HARI INI
    // ==========================================
    function openGroupShareModal() {
        let rekapList = [];
        let count = 1;

        @if(isset($activeGroup->students))
            @foreach($activeGroup->students as $std)
                @php
                    $recToday = $todayRecords->get($std->id, collect())->first();
                    $attToday = $todayAttendances->get($std->id);
                @endphp
                @if($recToday)
                    rekapList.push(`${count++}. *{{ addslashes($std->name) }}* ({{ $std->class }}): QS. {{ addslashes($recToday->surah_name) }} (Ayat {{ $recToday->start_ayah }}{{ $recToday->end_ayah && $recToday->end_ayah != $recToday->start_ayah ? '-' . $recToday->end_ayah : '' }}){{ ($recToday->jumlah_baris ?? 0) > 0 ? ' [' . $recToday->jumlah_baris . ' Baris]' : '' }} - {{ $recToday->quality == 'lancar' ? 'Lancar ✅' : ($recToday->quality == 'cukup' ? 'Cukup Lancar 👍' : 'Perlu Penguatan 🔄') }}`);
                @elseif($attToday && $attToday->status != 'hadir')
                    rekapList.push(`• *{{ addslashes($std->name) }}* ({{ $std->class }}): [{{ strtoupper($attToday->status) }}]`);
                @endif
            @endforeach
        @endif

        let listText = rekapList.length > 0 
            ? rekapList.join('\n') 
            : 'Belum ada setoran hafalan yang dicatat untuk hari ini.';

        const groupMessage =
`Assalamu'alaikum Warahmatullahi Wabarakatuh Ayah/Bunda.

*Laporan Harian Halaqah Tahfidz SDIT An Nadzir*
🕌 *Kelompok:* ${halaqahName}
📅 *Hari/Tanggal:* ${todayFormatted}
👤 *Musyrif Pembimbing:* ${mentorName}

*Rekap Setoran Ananda Hari Ini:*
${listText}

Alhamdulillah kegiatan halaqah tahfidz hari ini berjalan tertib dan lancar. Mohon Ayah/Bunda senantiasa mendampingi dan membiasakan tilawah serta muroja'ah bersama ananda di rumah.

Jazakumullahu Khairan Katsiran.
Wassalamu'alaikum Warahmatullahi Wabarakatuh.
_*SDIT An Nadzir*_`;

        document.getElementById('groupShareTextarea').value = groupMessage;
        document.getElementById('groupShareModal').classList.add('active');
    }

    function closeGroupShareModal() {
        document.getElementById('groupShareModal').classList.remove('active');
    }

    function sendGroupShareWa() {
        const text = document.getElementById('groupShareTextarea').value;
        const url = `https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`;
        window.open(url, '_blank');
    }

    function copyGroupShareText() {
        const text = document.getElementById('groupShareTextarea').value;
        navigator.clipboard.writeText(text).then(() => {
            alert('Teks rekap halaqah berhasil disalin ke clipboard!');
        });
    }

    // ==========================================
    // 5. AUTO TRIGGER POPUP PASCA SIMPAN
    // ==========================================
    @if(session('last_record'))
        document.addEventListener('DOMContentLoaded', function() {
            const lr = @json(session('last_record'));
            if (lr) {
                openPersonalShareModal(
                    lr.student_name,
                    lr.student_class,
                    lr.parent_phone || '',
                    lr.surah_name,
                    lr.ayah,
                    lr.quality_label || lr.quality,
                    lr.type_label || lr.type,
                    lr.date,
                    lr.notes
                );
            }
        });
    @endif
</script>
@endpush
@endsection
