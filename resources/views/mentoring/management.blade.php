@extends('layouts.app')

@section('title', 'Manajemen Kelompok Mentoring')
@section('page-title', 'Manajemen Kelompok')

@push('styles')
<style>
    /* Styling Header & Tabs */
    .mgmt-hero-banner {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 45%, #0f766e 100%);
        border-radius: 20px;
        padding: 24px 28px;
        color: white;
        margin-bottom: 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(6, 78, 59, 0.25);
    }
    .mgmt-hero-watermark {
        position: absolute;
        right: -15px;
        bottom: -25px;
        font-size: 9.5rem;
        color: rgba(255, 255, 255, 0.07);
        pointer-events: none;
        z-index: 1;
    }
    .mgmt-hero-content {
        position: relative;
        z-index: 2;
    }
    .mgmt-hero-actions {
        position: relative;
        z-index: 2;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }
    .mgmt-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }
    .mgmt-kpi-card {
        background: white;
        border-radius: 16px;
        padding: 16px 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 3px 12px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .mgmt-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }
    .mgmt-kpi-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
    .mentoring-tab-nav {
        display: flex;
        gap: 8px;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 20px;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .mentoring-tab-nav::-webkit-scrollbar { display: none; }
    .mentoring-tab-btn {
        padding: 10px 18px;
        font-weight: 700;
        font-size: 0.9rem;
        color: #64748b;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .mentoring-tab-btn:hover {
        color: #0f172a;
    }
    .mentoring-tab-btn.active {
        color: #047857;
        border-bottom-color: #047857;
    }
    .tab-badge {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.74rem;
        font-weight: 800;
        background: #f1f5f9;
        color: #475569;
    }
    .mentoring-tab-btn.active .tab-badge {
        background: #ecfdf5;
        color: #047857;
    }

    /* Modal Overlay & Container */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 16px;
        box-sizing: border-box;
    }
    .modal-container {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 580px;
        max-height: 90vh !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
        animation: modalFadeIn 0.2s ease-out;
    }
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.96); }
        to { opacity: 1; transform: scale(1); }
    }
    .modal-header-section {
        flex-shrink: 0;
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
    }
    .modal-body-scrollable {
        padding: 20px 24px;
        overflow-y: auto;
        flex: 1 1 auto;
        min-height: 0;
        -webkit-overflow-scrolling: touch;
    }
    .modal-footer-section {
        flex-shrink: 0;
        padding: 14px 24px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.03);
    }

    /* Responsif Mobile vs Desktop */
    @media (max-width: 768px) {
        .mgmt-hero-banner {
            padding: 18px 16px !important;
            border-radius: 16px !important;
        }
        .mgmt-hero-banner h2 {
            font-size: 1.35rem !important;
        }
        .mgmt-hero-actions {
            width: 100% !important;
            flex-direction: column !important;
        }
        .mgmt-hero-actions button,
        .mgmt-hero-actions a {
            width: 100% !important;
            justify-content: center !important;
            text-align: center !important;
        }
        .mgmt-kpi-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }
        .mgmt-kpi-card {
            padding: 12px 14px !important;
        }
        .mgmt-kpi-icon {
            width: 38px !important;
            height: 38px !important;
            font-size: 1.1rem !important;
        }
        .mgmt-desktop-table-wrap {
            display: none !important;
        }
        .mgmt-mobile-card-list {
            display: flex !important;
            flex-direction: column !important;
            gap: 12px !important;
            padding: 12px !important;
        }
        .modal-overlay {
            padding: 8px;
        }
        .modal-container {
            max-height: 94vh !important;
            border-radius: 16px;
        }
        .modal-header-section {
            padding: 14px 16px !important;
        }
        .modal-body-scrollable {
            padding: 16px 16px !important;
        }
        .modal-footer-section {
            padding: 12px 16px !important;
        }
        .modal-footer-section button {
            flex: 1 1 50%;
            padding: 10px 14px !important;
            font-size: 0.88rem !important;
            justify-content: center;
        }
    }
    @media (min-width: 769px) {
        .mgmt-mobile-card-list {
            display: none !important;
        }
    }
    .mgmt-mobile-item {
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
</style>
@endpush

@section('content')
<div style="margin-bottom: 30px;">

    <!-- 1. HERO BANNER PUSAT KENDALI KELOMPOK -->
    <div class="mgmt-hero-banner">
        <div class="mgmt-hero-content">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.18); backdrop-filter: blur(4px); padding: 4px 14px; border-radius: 20px; font-size: 0.76rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                <i class="fas fa-layer-group"></i>
                <span>Pusat Kendali Kelompok Qur'an</span>
                <span>•</span>
                <span>TP {{ $activeYear->name ?? '-' }}</span>
            </div>
            <h2 style="margin: 0; font-size: 1.65rem; font-weight: 800; letter-spacing: -0.5px;">
                Manajemen Kelompok Mentoring
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 0.88rem; opacity: 0.92; max-width: 620px; line-height: 1.5;">
                Pusat kendali kelompok Qur'an (Tilawati & Tahfidz), distribusi cepat siswa belum terbagi, serta penugasan guru pembimbing.
            </p>
        </div>

        <div class="mgmt-hero-actions">
            <button type="button" onclick="openCreateGroupModal()" 
                    style="background: white; color: #065f46; font-weight: 800; padding: 10px 18px; border-radius: 12px; font-size: 0.86rem; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
                <i class="fas fa-plus-circle"></i> Buat Kelompok Baru
            </button>
            <a href="{{ route('tahfidz-groups.print', ['year_id' => $selectedYearId]) }}" 
               target="_blank" 
               style="background: rgba(15, 23, 42, 0.45); color: white; border: 1px solid rgba(255,255,255,0.3); font-weight: 700; padding: 9px 16px; border-radius: 12px; font-size: 0.86rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-print"></i> Cetak Rekap
            </a>
        </div>
        <i class="fas fa-layer-group mgmt-hero-watermark"></i>
    </div>

    <!-- 2. STATS OVERVIEW CARDS (SIMETRIS & ELEGAN) -->
    <div class="mgmt-kpi-grid">
        <!-- Card 1: Tilawati -->
        <div class="mgmt-kpi-card" style="border-left: 4px solid #059669;">
            <div>
                <span style="font-size: 0.74rem; font-weight: 800; color: #059669; text-transform: uppercase; letter-spacing: 0.3px; display: block;">
                    <i class="fas fa-book-reader"></i> Kelompok Tilawati
                </span>
                <h3 style="margin: 4px 0 2px 0; font-size: 1.45rem; font-weight: 900; color: #1e293b;">
                    {{ $totalTilawatiGroups }} <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Kelompok</span>
                </h3>
                <div style="font-size: 0.76rem; color: #475569; margin-top: 3px;">
                    <strong>{{ $tilawatiAssignedCount }}</strong> siswa
                    @if($tilawatiUnassignedCount > 0)
                        • <span style="color: #ea580c; font-weight: 800;">{{ $tilawatiUnassignedCount }} belum</span>
                    @else
                        • <span style="color: #059669; font-weight: 700;">✓ Tuntas</span>
                    @endif
                </div>
            </div>
            <div class="mgmt-kpi-icon" style="background: #ecfdf5; color: #059669;">
                <i class="fas fa-book-open"></i>
            </div>
        </div>

        <!-- Card 2: Tahfidz -->
        <div class="mgmt-kpi-card" style="border-left: 4px solid #0284c7;">
            <div>
                <span style="font-size: 0.74rem; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 0.3px; display: block;">
                    <i class="fas fa-quran"></i> Kelompok Tahfidz
                </span>
                <h3 style="margin: 4px 0 2px 0; font-size: 1.45rem; font-weight: 900; color: #1e293b;">
                    {{ $totalTahfidzGroups }} <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Kelompok</span>
                </h3>
                <div style="font-size: 0.76rem; color: #475569; margin-top: 3px;">
                    <strong>{{ $tahfidzAssignedCount }}</strong> siswa
                    @if($tahfidzUnassignedCount > 0)
                        • <span style="color: #ea580c; font-weight: 800;">{{ $tahfidzUnassignedCount }} belum</span>
                    @else
                        • <span style="color: #0284c7; font-weight: 700;">✓ Tuntas</span>
                    @endif
                </div>
            </div>
            <div class="mgmt-kpi-icon" style="background: #f0f9ff; color: #0284c7;">
                <i class="fas fa-quran"></i>
            </div>
        </div>

        <!-- Card 3: Total Siswa Aktif -->
        <div class="mgmt-kpi-card" style="border-left: 4px solid #8b5cf6;">
            <div>
                <span style="font-size: 0.74rem; font-weight: 800; color: #8b5cf6; text-transform: uppercase; letter-spacing: 0.3px; display: block;">
                    <i class="fas fa-users"></i> Total Siswa TP Ini
                </span>
                <h3 style="margin: 4px 0 2px 0; font-size: 1.45rem; font-weight: 900; color: #1e293b;">
                    {{ $totalActiveStudents }} <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Siswa</span>
                </h3>
                <div style="font-size: 0.76rem; color: #64748b; margin-top: 3px;">
                    Tahun Pelajaran <strong>{{ $activeYear ? $activeYear->name : '-' }}</strong>
                </div>
            </div>
            <div class="mgmt-kpi-icon" style="background: #f5f3ff; color: #8b5cf6;">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>

        <!-- Card 4: Total Pembimbing -->
        <div class="mgmt-kpi-card" style="border-left: 4px solid #f59e0b;">
            <div>
                <span style="font-size: 0.74rem; font-weight: 800; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.3px; display: block;">
                    <i class="fas fa-chalkboard-teacher"></i> Guru Pembimbing
                </span>
                <h3 style="margin: 4px 0 2px 0; font-size: 1.45rem; font-weight: 900; color: #1e293b;">
                    {{ $totalMentors }} <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Ustadz/ah</span>
                </h3>
                <div style="font-size: 0.76rem; color: #64748b; margin-top: 3px;">
                    Membimbing Tilawati & Tahfidz
                </div>
            </div>
            <div class="mgmt-kpi-icon" style="background: #fffbeb; color: #f59e0b;">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
    </div>

    <!-- 3. FILTER & NAV CONTROLS -->
    <div style="background: white; border-radius: 16px; padding: 14px 18px; border: 1px solid #e2e8f0; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <form action="{{ route('mentoring.management') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <input type="hidden" name="tab" value="{{ $tab }}">
            @if($tab === 'unassigned')
                <input type="hidden" name="unassigned_type" value="{{ $unassignedType }}">
            @endif

            <div style="min-width: 170px;">
                <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #64748b; margin-bottom: 4px; text-transform: uppercase;">Tahun Pelajaran</label>
                <select name="year_id" class="form-control-pro" onchange="this.form.submit()" style="width: 100%;">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            TP: {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($tab !== 'unassigned')
                <div style="min-width: 190px;">
                    <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #64748b; margin-bottom: 4px; text-transform: uppercase;">Filter Guru Pembimbing</label>
                    <select name="mentor_user_id" class="form-control-pro" onchange="this.form.submit()" style="width: 100%;">
                        <option value="">-- Semua Pembimbing --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $mentorFilter == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #64748b; margin-bottom: 4px; text-transform: uppercase;">Pencarian</label>
                    <div style="position: relative;">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kelompok, mentor, target..." class="form-control" style="padding: 8px 12px 8px 34px; font-size: 0.86rem; border-radius: 10px; border: 1px solid #cbd5e1;">
                        <i class="fas fa-search" style="position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem;"></i>
                    </div>
                </div>
            @endif

            <div style="align-self: flex-end; display: flex; gap: 8px;">
                <button type="submit" class="btn-action-header btn-blue" style="padding: 8px 16px; font-size: 0.86rem; border-radius: 10px; font-weight: 700;">
                    <i class="fas fa-filter"></i> Terapkan
                </button>
                @if($search || $mentorFilter)
                    <a href="{{ route('mentoring.management', ['year_id' => $selectedYearId, 'tab' => $tab]) }}" class="btn-action-header btn-dark" style="background: #64748b; padding: 8px 12px; font-size: 0.86rem; border-radius: 10px;">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 4. TABS NAVIGATION -->
    <div class="mentoring-tab-nav">
        <a href="{{ route('mentoring.management', ['year_id' => $selectedYearId, 'tab' => 'all']) }}" 
           class="mentoring-tab-btn {{ $tab === 'all' ? 'active' : '' }}">
            <i class="fas fa-th-list"></i> Semua Kelompok
            <span class="tab-badge">{{ $totalTilawatiGroups + $totalTahfidzGroups }}</span>
        </a>

        <a href="{{ route('mentoring.management', ['year_id' => $selectedYearId, 'tab' => 'tilawati']) }}" 
           class="mentoring-tab-btn {{ $tab === 'tilawati' ? 'active' : '' }}">
            <i class="fas fa-book-reader" style="color: #059669;"></i> Kelompok Tilawati
            <span class="tab-badge" style="background: #ecfdf5; color: #047857;">{{ $totalTilawatiGroups }}</span>
        </a>

        <a href="{{ route('mentoring.management', ['year_id' => $selectedYearId, 'tab' => 'tahfidz']) }}" 
           class="mentoring-tab-btn {{ $tab === 'tahfidz' ? 'active' : '' }}">
            <i class="fas fa-quran" style="color: #0284c7;"></i> Kelompok Tahfidz
            <span class="tab-badge" style="background: #f0f9ff; color: #0369a1;">{{ $totalTahfidzGroups }}</span>
        </a>

        <a href="{{ route('mentoring.management', ['year_id' => $selectedYearId, 'tab' => 'unassigned', 'unassigned_type' => $unassignedType]) }}" 
           class="mentoring-tab-btn {{ $tab === 'unassigned' ? 'active' : '' }}">
            <i class="fas fa-user-plus" style="color: #ea580c;"></i> Distribusi Siswa
            <span class="tab-badge" style="background: #fff7ed; color: #ea580c; font-weight: 800;">
                {{ $unassignedType === 'tilawati' ? $tilawatiUnassignedCount : $tahfidzUnassignedCount }} Belum Terbagi
            </span>
        </a>
    </div>

    <!-- 5. TAB CONTENT -->
    @if($tab !== 'unassigned')
        <!-- GROUP LIST CARD -->
        <div style="background: white; border-radius: 18px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03); overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; background: #f8fafc;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #0f172a;">
                        Daftar Kelompok Mentoring
                        @if($tab === 'tilawati') (Tilawati) @elseif($tab === 'tahfidz') (Tahfidz) @endif
                    </h3>
                    <span style="background: #f1f5f9; color: #475569; font-size: 0.78rem; font-weight: 800; padding: 2px 10px; border-radius: 12px;">
                        {{ $groups->count() }} Kelompok
                    </span>
                </div>

                <div style="display: flex; gap: 8px;">
                    @if($tab === 'tilawati')
                        <a href="{{ route('tilawati-groups.create') }}" class="btn-action-header btn-green" style="padding: 6px 14px; font-size: 0.82rem; background: #059669; border-radius: 8px; font-weight: 700;">
                            <i class="fas fa-plus"></i> Buat Tilawati
                        </a>
                    @elseif($tab === 'tahfidz')
                        <a href="{{ route('tahfidz-groups.create') }}" class="btn-action-header btn-green" style="padding: 6px 14px; font-size: 0.82rem; background: #0284c7; border-radius: 8px; font-weight: 700;">
                            <i class="fas fa-plus"></i> Buat Tahfidz
                        </a>
                    @endif
                </div>
            </div>

            {{-- A. DESKTOP TABLE VIEW --}}
            <div class="mgmt-desktop-table-wrap" style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; margin-top: 0; font-size: 0.88rem;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 12px 16px; width: 45px; text-align: center; color: #475569; font-weight: 800;">No</th>
                            <th style="padding: 12px 16px; text-align: left; width: 110px; color: #475569; font-weight: 800;">Tipe</th>
                            <th style="padding: 12px 16px; text-align: left; color: #475569; font-weight: 800;">Nama Kelompok</th>
                            <th style="padding: 12px 16px; text-align: left; color: #475569; font-weight: 800;">Guru Pembimbing</th>
                            <th style="padding: 12px 16px; text-align: left; color: #475569; font-weight: 800;">Target Jenjang</th>
                            <th style="padding: 12px 16px; text-align: left; color: #475569; font-weight: 800;">Jadwal / Tempat</th>
                            <th style="padding: 12px 16px; text-align: center; width: 110px; color: #475569; font-weight: 800;">Jumlah Siswa</th>
                            <th style="padding: 12px 16px; text-align: center; width: 230px; color: #475569; font-weight: 800;">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $index => $group)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <td style="padding: 14px 16px; text-align: center; font-weight: 700; color: #64748b;">
                                    {{ $index + 1 }}
                                </td>
                                <td style="padding: 14px 16px;">
                                    @if($group->type === 'tilawati')
                                        <span style="display: inline-flex; align-items: center; gap: 5px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 3px 10px; border-radius: 20px; font-weight: 700; font-size: 0.76rem;">
                                            <i class="fas fa-book-reader"></i> Tilawati
                                        </span>
                                    @else
                                        <span style="display: inline-flex; align-items: center; gap: 5px; background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; padding: 3px 10px; border-radius: 20px; font-weight: 700; font-size: 0.76rem;">
                                            <i class="fas fa-quran"></i> Tahfidz
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px;">
                                    <a href="{{ $group->type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" style="font-weight: 800; color: #0f172a; text-decoration: none; font-size: 0.95rem;">
                                        {{ $group->name }}
                                    </a>
                                    @if($group->notes)
                                        <div style="font-size: 0.76rem; color: #64748b; margin-top: 2px;">
                                            {{ Str::limit($group->notes, 40) }}
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; color: #334155; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.78rem;">
                                            {{ substr($group->mentor_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; color: #1e293b; font-size: 0.88rem;">{{ $group->mentor_name }}</div>
                                            @if($group->mentorUser)
                                                <small style="color: #059669; font-size: 0.72rem; font-weight: 600;"><i class="fas fa-check-circle"></i> Terhubung Akun</small>
                                            @else
                                                <small style="color: #d97706; font-size: 0.72rem; font-weight: 600;"><i class="fas fa-exclamation-circle"></i> Belum Link Akun</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 14px 16px;">
                                    @if($group->target_level)
                                        <span style="display: inline-block; background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; padding: 2px 8px; border-radius: 12px; font-weight: 700; font-size: 0.78rem;">
                                            {{ $group->target_level }}
                                        </span>
                                    @else
                                        <span style="color: #94a3b8; font-style: italic;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px; color: #475569;">
                                    {{ $group->schedule ?? '-' }}
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <span style="display: inline-block; background: #f1f5f9; color: #0f172a; padding: 4px 12px; border-radius: 8px; font-weight: 800; font-size: 0.86rem;">
                                        {{ $group->students_count }} Siswa
                                    </span>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                                        <!-- Kelola Siswa -->
                                        <a href="{{ $group->type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" 
                                           class="btn-action-header btn-blue" 
                                           style="padding: 6px 9px; font-size: 0.78rem; border-radius: 7px; font-weight: 700;" 
                                           title="Kelola Siswa">
                                            <i class="fas fa-users"></i>
                                        </a>

                                        @if($group->type === 'tahfidz')
                                            <!-- Absensi Sesi -->
                                            <a href="{{ route('tahfidz.attendance.index', ['group' => $group->id]) }}" 
                                               class="btn-action-header" 
                                               style="background: #7c3aed; color: white; padding: 6px 9px; font-size: 0.78rem; border-radius: 7px;" 
                                               title="Jurnal Sesi & Laporan WA">
                                                <i class="fas fa-clipboard-check"></i>
                                            </a>
                                            <!-- Rekap Hafalan -->
                                            <a href="{{ route('tahfidz.print-group-recap', ['group' => $group->id]) }}" 
                                               target="_blank" 
                                               class="btn-action-header" 
                                               style="background: #0f766e; color: white; padding: 6px 9px; font-size: 0.78rem; border-radius: 7px;" 
                                               title="Rekap Hafalan">
                                                <i class="fas fa-quran"></i>
                                            </a>
                                        @endif

                                        <!-- Cetak Kelompok -->
                                        <a href="{{ route('mentoring-groups.print-group', $group->id) }}" 
                                           target="_blank" 
                                           class="btn-action-header btn-dark" 
                                           style="background: #475569; padding: 6px 9px; font-size: 0.78rem; border-radius: 7px;" 
                                           title="Cetak Daftar Kelompok">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ $group->type === 'tilawati' ? route('tilawati-groups.edit', $group->id) : route('tahfidz-groups.edit', $group->id) }}" 
                                           class="btn-action-header btn-orange" 
                                           style="padding: 6px 9px; font-size: 0.78rem; border-radius: 7px;" 
                                           title="Edit Kelompok">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Hapus -->
                                        <button type="button" 
                                                onclick="confirmDeleteGroup('{{ $group->id }}', '{{ $group->name }}', '{{ $group->type }}')" 
                                                class="btn-action-header btn-red" 
                                                style="padding: 6px 9px; font-size: 0.78rem; border-radius: 7px;" 
                                                title="Hapus Kelompok">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                        <form id="delete-group-form-{{ $group->id }}" 
                                              action="{{ $group->type === 'tilawati' ? route('tilawati-groups.destroy', $group->id) : route('tahfidz-groups.destroy', $group->id) }}" 
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 50px 20px; color: #94a3b8;">
                                    <div style="font-size: 2.8rem; margin-bottom: 12px; color: #cbd5e1;">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    <h4 style="margin: 0; color: #475569; font-size: 1.1rem; font-weight: 800;">Tidak Ditemukan Kelompok</h4>
                                    <p style="margin: 6px 0 18px 0; font-size: 0.88rem;">
                                        Belum ada kelompok yang terdaftar untuk filter atau pencarian saat ini.
                                    </p>
                                    <button type="button" onclick="openCreateGroupModal()" class="btn-action-header btn-green" style="background: #059669; border-radius: 8px;">
                                        <i class="fas fa-plus-circle"></i> Buat Kelompok Sekarang
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- B. MOBILE CARD VIEW (SUPER RAPI & SIMETRIS DI SMARTPHONE) --}}
            <div class="mgmt-mobile-card-list">
                @forelse($groups as $group)
                    <div class="mgmt-mobile-item">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; gap: 8px;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 3px;">
                                    @if($group->type === 'tilawati')
                                        <span style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 2px 7px; border-radius: 12px; font-weight: 800; font-size: 0.72rem;">
                                            Tilawati
                                        </span>
                                    @else
                                        <span style="background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; padding: 2px 7px; border-radius: 12px; font-weight: 800; font-size: 0.72rem;">
                                            Tahfidz
                                        </span>
                                    @endif
                                    @if($group->target_level)
                                        <span style="background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; padding: 2px 7px; border-radius: 12px; font-weight: 700; font-size: 0.72rem;">
                                            {{ $group->target_level }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ $group->type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" style="font-weight: 800; color: #0f172a; text-decoration: none; font-size: 1.05rem;">
                                    {{ $group->name }}
                                </a>
                            </div>
                            <span style="background: #f1f5f9; color: #0f172a; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 0.82rem; white-space: nowrap;">
                                {{ $group->students_count }} Siswa
                            </span>
                        </div>

                        <div style="font-size: 0.82rem; color: #475569; margin-bottom: 10px;">
                            <i class="fas fa-user-tie" style="color: #64748b;"></i> Pembimbing: <strong>{{ $group->mentor_name }}</strong>
                            @if($group->schedule)
                                <div style="margin-top: 3px; color: #64748b; font-size: 0.76rem;">
                                    <i class="fas fa-clock"></i> {{ $group->schedule }}
                                </div>
                            @endif
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(75px, 1fr)); gap: 6px; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                            <a href="{{ $group->type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" 
                               class="btn-action-header btn-blue" 
                               style="padding: 7px 10px; font-size: 0.78rem; text-align: center; justify-content: center; border-radius: 8px; font-weight: 700;">
                                <i class="fas fa-users"></i> Siswa
                            </a>
                            @if($group->type === 'tahfidz')
                                <a href="{{ route('tahfidz.attendance.index', ['group' => $group->id]) }}" 
                                   class="btn-action-header" 
                                   style="background: #7c3aed; color: white; padding: 7px 10px; font-size: 0.78rem; text-align: center; justify-content: center; border-radius: 8px; font-weight: 700;">
                                    <i class="fas fa-clipboard-check"></i> Absen
                                </a>
                            @endif
                            <a href="{{ route('mentoring-groups.print-group', $group->id) }}" 
                               target="_blank" 
                               class="btn-action-header btn-dark" 
                               style="background: #475569; padding: 7px 10px; font-size: 0.78rem; text-align: center; justify-content: center; border-radius: 8px;">
                                <i class="fas fa-print"></i> Cetak
                            </a>
                            <a href="{{ $group->type === 'tilawati' ? route('tilawati-groups.edit', $group->id) : route('tahfidz-groups.edit', $group->id) }}" 
                               class="btn-action-header btn-orange" 
                               style="padding: 7px 10px; font-size: 0.78rem; text-align: center; justify-content: center; border-radius: 8px;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" 
                                    onclick="confirmDeleteGroup('{{ $group->id }}', '{{ $group->name }}', '{{ $group->type }}')" 
                                    class="btn-action-header btn-red" 
                                    style="padding: 7px 10px; font-size: 0.78rem; text-align: center; justify-content: center; border-radius: 8px;">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 30px 16px; color: #94a3b8;">
                        <i class="fas fa-folder-open" style="font-size: 2rem; margin-bottom: 8px;"></i>
                        <p style="margin: 0; font-size: 0.9rem;">Belum ada kelompok yang terdaftar.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @else
        <!-- TAB 4: DISTRIBUSI SISWA BELUM TERKELOMPOKKAN -->
        <div style="background: white; border-radius: 18px; padding: 22px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 18px;">
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">
                        Distribusi Cepat Siswa Belum Berkelompok
                    </h3>
                    <p style="margin: 4px 0 0 0; font-size: 0.86rem; color: #64748b;">
                        Pilih siswa dan masukkan sekaligus ke dalam kelompok tujuan dalam 1 klik.
                    </p>
                </div>

                <!-- Tipe Toggle (Tilawati vs Tahfidz) -->
                <div style="display: flex; background: #f1f5f9; padding: 4px; border-radius: 12px; gap: 4px;">
                    <a href="{{ route('mentoring.management', ['year_id' => $selectedYearId, 'tab' => 'unassigned', 'unassigned_type' => 'tilawati', 'class_filter' => $classFilter]) }}" 
                       style="padding: 7px 16px; border-radius: 8px; font-size: 0.84rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; {{ $unassignedType === 'tilawati' ? 'background: white; color: #059669; box-shadow: 0 2px 6px rgba(0,0,0,0.06);' : 'color: #64748b;' }}">
                        <i class="fas fa-book-reader"></i> Tilawati ({{ $tilawatiUnassignedCount }})
                    </a>
                    <a href="{{ route('mentoring.management', ['year_id' => $selectedYearId, 'tab' => 'unassigned', 'unassigned_type' => 'tahfidz', 'class_filter' => $classFilter]) }}" 
                       style="padding: 7px 16px; border-radius: 8px; font-size: 0.84rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; {{ $unassignedType === 'tahfidz' ? 'background: white; color: #0284c7; box-shadow: 0 2px 6px rgba(0,0,0,0.06);' : 'color: #64748b;' }}">
                        <i class="fas fa-quran"></i> Tahfidz ({{ $tahfidzUnassignedCount }})
                    </a>
                </div>
            </div>

            <!-- Filter Kelas Form -->
            <form action="{{ route('mentoring.management') }}" method="GET" style="display: flex; gap: 10px; align-items: center; margin-bottom: 20px; flex-wrap: wrap;">
                <input type="hidden" name="tab" value="unassigned">
                <input type="hidden" name="year_id" value="{{ $selectedYearId }}">
                <input type="hidden" name="unassigned_type" value="{{ $unassignedType }}">

                <div style="min-width: 180px;">
                    <select name="class_filter" class="form-control-pro" onchange="this.form.submit()" style="width: 100%;">
                        <option value="">-- Semua Kelas ({{ $unassignedStudents->count() }} siswa) --</option>
                        @foreach($availableClasses as $c)
                            <option value="{{ $c }}" {{ $classFilter == $c ? 'selected' : '' }}>
                                Kelas {{ $c }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($classFilter)
                    <a href="{{ route('mentoring.management', ['year_id' => $selectedYearId, 'tab' => 'unassigned', 'unassigned_type' => $unassignedType]) }}" class="btn-action-header btn-dark" style="background: #64748b; padding: 8px 12px; font-size: 0.85rem; border-radius: 10px;">
                        <i class="fas fa-undo"></i> Reset Filter Kelas
                    </a>
                @endif
            </form>

            @if($unassignedStudents->isEmpty())
                <div style="text-align: center; padding: 45px 20px; background: #f8fafc; border-radius: 14px; border: 2px dashed #cbd5e1;">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 12px;">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <h4 style="margin: 0; color: #1e293b; font-size: 1.1rem; font-weight: 800;">Seluruh Siswa Sudah Memiliki Kelompok {{ ucfirst($unassignedType) }}!</h4>
                    <p style="margin: 6px 0 0 0; color: #64748b; font-size: 0.88rem;">
                        Alhamdulillah, seluruh siswa sudah terdistribusi ke dalam kelompok pembinaan {{ ucfirst($unassignedType) }}.
                    </p>
                </div>
            @else
                <!-- Form Mass Assign -->
                <form action="{{ route('mentoring.quick-assign') }}" method="POST" id="quickAssignForm">
                    @csrf
                    <!-- Action Bar -->
                    <div style="background: #eff6ff; border: 1.5px solid #bfdbfe; padding: 14px 18px; border-radius: 14px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: #1e40af; font-size: 0.88rem; cursor: pointer;">
                                <input type="checkbox" id="selectAllUnassigned" onchange="toggleSelectAll(this)" style="width: 18px; height: 18px; cursor: pointer; accent-color: #2563eb;">
                                Pilih Semua (<span id="selectedCountText">0</span> siswa dipilih)
                            </label>
                        </div>

                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; flex: 1; justify-content: flex-end;">
                            <select name="group_id" class="form-control-pro" required style="min-width: 260px;">
                                <option value="">-- Pilih Kelompok {{ ucfirst($unassignedType) }} Tujuan --</option>
                                @foreach($availableGroups->where('type', $unassignedType) as $g)
                                    <option value="{{ $g->id }}">
                                        {{ $g->name }} (Mentor: {{ $g->mentor_name }})
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" id="btnAssignSubmit" disabled class="btn-action-header btn-blue" style="padding: 9px 18px; font-size: 0.86rem; border-radius: 10px; font-weight: 800;">
                                <i class="fas fa-arrow-right"></i> Masukkan ke Kelompok
                            </button>
                        </div>
                    </div>

                    <!-- Students Table -->
                    <div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 14px;">
                        <table style="width: 100%; border-collapse: collapse; margin-top: 0; font-size: 0.88rem;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                    <th style="padding: 11px 14px; width: 45px; text-align: center;">Pilih</th>
                                    <th style="padding: 11px 14px; text-align: left; color: #475569; font-weight: 800;">Nama Lengkap Siswa</th>
                                    <th style="padding: 11px 14px; text-align: center; width: 120px; color: #475569; font-weight: 800;">NIS</th>
                                    <th style="padding: 11px 14px; text-align: center; width: 100px; color: #475569; font-weight: 800;">Kelas</th>
                                    <th style="padding: 11px 14px; text-align: center; width: 120px; color: #475569; font-weight: 800;">Jenis Kelamin</th>
                                    <th style="padding: 11px 14px; text-align: left; color: #475569; font-weight: 800;">Status Kelompok Lain</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unassignedStudents as $student)
                                    <tr style="border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background 0.15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'" onclick="toggleRowCheckbox(event, 'chk_{{ $student->id }}')">
                                        <td style="padding: 12px 14px; text-align: center;" onclick="event.stopPropagation()">
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="chk_{{ $student->id }}" class="student-checkbox" onchange="updateSelectedCount()" style="width: 18px; height: 18px; cursor: pointer; accent-color: #2563eb;">
                                        </td>
                                        <td style="padding: 12px 14px; font-weight: 700; color: #0f172a;">
                                            {{ $student->name }}
                                        </td>
                                        <td style="padding: 12px 14px; text-align: center; color: #64748b; font-family: monospace;">
                                            {{ $student->nis ?? '-' }}
                                        </td>
                                        <td style="padding: 12px 14px; text-align: center;">
                                            <span style="background: #f1f5f9; color: #0f172a; padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: 0.82rem;">
                                                {{ $student->class }}
                                            </span>
                                        </td>
                                        <td style="padding: 12px 14px; text-align: center; color: #475569; font-size: 0.84rem;">
                                            {{ $student->gender == 'L' ? 'Laki-laki' : ($student->gender == 'P' ? 'Perempuan' : '-') }}
                                        </td>
                                        <td style="padding: 12px 14px; font-size: 0.82rem; color: #64748b;">
                                            <span style="color: #dc2626; font-weight: 700;"><i class="fas fa-times-circle"></i> Belum ada {{ ucfirst($unassignedType) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            @endif
        </div>
    @endif

</div>

<!-- MODAL BUAT KELOMPOK BARU -->
<div id="createGroupModal" class="modal-overlay" onclick="closeCreateGroupModal(event)">
    <div class="modal-container" onclick="event.stopPropagation()">
        <!-- MODAL HEADER -->
        <div class="modal-header-section">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">
                    Buat Kelompok Mentoring Baru
                </h3>
            </div>
            <button type="button" onclick="closeCreateGroupModal()" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; border-radius: 6px;" title="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('tilawati-groups.store') }}" method="POST" id="createGroupForm" style="display: flex; flex-direction: column; flex: 1 1 auto; min-height: 0; margin: 0; overflow: hidden;">
            @csrf
            <!-- MODAL BODY (SCROLLABLE) -->
            <div class="modal-body-scrollable">
                <!-- PILIH TIPE MENTORING -->
                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 0.86rem;">
                        Tipe Mentoring <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <label style="border: 2px solid #059669; background: #ecfdf5; padding: 12px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; gap: 10px;" id="lblTilawati">
                            <input type="radio" name="type" value="tilawati" checked onchange="switchGroupType('tilawati')" style="width: 18px; height: 18px; accent-color: #059669;">
                            <div>
                                <strong style="color: #047857; display: block; font-size: 0.9rem;">Tilawati</strong>
                                <small style="color: #065f46; font-size: 0.74rem;">Pembinaan Baca Al-Qur'an</small>
                            </div>
                        </label>
                        <label style="border: 1px solid #cbd5e1; background: #f8fafc; padding: 12px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; gap: 10px;" id="lblTahfidz">
                            <input type="radio" name="type" value="tahfidz" onchange="switchGroupType('tahfidz')" style="width: 18px; height: 18px; accent-color: #0284c7;">
                            <div>
                                <strong style="color: #0369a1; display: block; font-size: 0.9rem;">Tahfidz</strong>
                                <small style="color: #075985; font-size: 0.74rem;">Hafalan & Sesi Setoran</small>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- NAMA KELOMPOK -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.86rem;">
                        Nama Kelompok <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="Contoh: Halqah Abu Bakar, Tilawati Jilid 2, Tahfidz Juz 30-A" class="form-control" style="padding: 10px 12px; font-size: 0.88rem; border-radius: 10px; border: 1px solid #cbd5e1;">
                </div>

                <!-- TAHUN PELAJARAN -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.86rem;">
                        Tahun Pelajaran <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="academic_year_id" required class="form-control-pro" style="width: 100%;">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- GURU PEMBIMBING -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.86rem;">
                        Guru Pembimbing / Mentor <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="mentor_user_id" id="modalMentorUserId" required onchange="handleMentorSelect(this)" class="form-control-pro" style="width: 100%;">
                        <option value="">-- Pilih Akun Guru --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" data-name="{{ $teacher->name }}">{{ $teacher->name }} ({{ $teacher->role }})</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="mentor_name" id="modalMentorName">
                    <small style="color: #64748b; font-size: 0.76rem; margin-top: 4px; display: block;">
                        Kelompok ini akan otomatis tampil di akun guru yang dipilih.
                    </small>
                </div>

                <!-- TARGET JENJANG -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.86rem;">
                        Target / Jenjang Capaian
                    </label>
                    <input type="text" name="target_level" list="mgmtTargetSuggestions" placeholder="Pilih atau ketik, contoh: Juz 30, Juz 29, Jilid 3" class="form-control" style="padding: 10px 12px; font-size: 0.88rem; border-radius: 10px; border: 1px solid #cbd5e1;">
                    <datalist id="mgmtTargetSuggestions">
                        <option value="Juz 30 (An-Naba s.d An-Nas)">
                        <option value="Juz 29 (Al-Mulk s.d Al-Mursalat)">
                        <option value="Juz 28 (Al-Mujadilah s.d At-Tahrim)">
                        <option value="Juz 27 (Az-Zariyat s.d Al-Hadid)">
                        <option value="Juz 26 (Al-Ahqaf s.d Az-Zariyat)">
                        <option value="Juz 1 (Al-Fatihah & Al-Baqarah)">
                        <option value="Juz 2">
                        <option value="Juz 3">
                        <option value="Jilid 1">
                        <option value="Jilid 2">
                        <option value="Jilid 3">
                        <option value="Jilid 4">
                        <option value="Jilid 5">
                        <option value="Jilid 6">
                        <option value="Al-Qur'an">
                        <option value="Ghorib & Tajwid">
                    </datalist>
                </div>

                <!-- JADWAL & TEMPAT -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.86rem;">
                        Jadwal & Tempat
                    </label>
                    <input type="text" name="schedule" placeholder="Contoh: Setiap Selasa - Jum'at 07:30 - Ruang 1B" class="form-control" style="padding: 10px 12px; font-size: 0.88rem; border-radius: 10px; border: 1px solid #cbd5e1;">
                </div>

                <!-- CATATAN -->
                <div style="margin-bottom: 8px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.86rem;">
                        Catatan Tambahan
                    </label>
                    <textarea name="notes" rows="2" placeholder="Catatan opsional mengenai kriteria siswa kelompok ini..." class="form-control" style="padding: 8px 12px; font-size: 0.86rem; border-radius: 10px; border: 1px solid #cbd5e1;"></textarea>
                </div>
            </div>

            <!-- MODAL FOOTER -->
            <div class="modal-footer-section">
                <button type="button" onclick="closeCreateGroupModal()" class="btn-action-header btn-dark" style="background: #e2e8f0; color: #334155; padding: 10px 20px; font-weight: 700; border-radius: 10px;">
                    Batal
                </button>
                <button type="submit" class="btn-action-header btn-green" id="btnSubmitGroup" style="padding: 10px 22px; background: #059669; color: white; font-weight: 800; border-radius: 10px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);">
                    <i class="fas fa-check"></i> Simpan Kelompok
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateGroupModal() {
        document.getElementById('createGroupModal').style.display = 'flex';
        document.body.classList.add('modal-open');
    }

    function closeCreateGroupModal(e) {
        if (!e || e.target.id === 'createGroupModal' || e.target.closest('button')) {
            document.getElementById('createGroupModal').style.display = 'none';
            document.body.classList.remove('modal-open');
        }
    }

    function switchGroupType(type) {
        const form = document.getElementById('createGroupForm');
        const lblTilawati = document.getElementById('lblTilawati');
        const lblTahfidz = document.getElementById('lblTahfidz');
        const btnSubmit = document.getElementById('btnSubmitGroup');

        if (type === 'tilawati') {
            form.action = "{{ route('tilawati-groups.store') }}";
            lblTilawati.style.border = '2px solid #059669';
            lblTilawati.style.background = '#ecfdf5';
            lblTahfidz.style.border = '1px solid #cbd5e1';
            lblTahfidz.style.background = '#f8fafc';
            btnSubmit.style.background = '#059669';
        } else {
            form.action = "{{ route('tahfidz-groups.store') }}";
            lblTahfidz.style.border = '2px solid #0284c7';
            lblTahfidz.style.background = '#f0f9ff';
            lblTilawati.style.border = '1px solid #cbd5e1';
            lblTilawati.style.background = '#f8fafc';
            btnSubmit.style.background = '#0284c7';
        }
    }

    function handleMentorSelect(selectEl) {
        const selected = selectEl.options[selectEl.selectedIndex];
        const name = selected ? selected.getAttribute('data-name') : '';
        document.getElementById('modalMentorName').value = name || '';
    }

    function confirmDeleteGroup(id, name, type) {
        Swal.fire({
            title: 'Hapus Kelompok?',
            text: `Yakin ingin menghapus kelompok "${name}"? Siswa di dalamnya akan dilepaskan dari kelompok ini.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-group-form-${id}`).submit();
            }
        });
    }

    function toggleSelectAll(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
        updateSelectedCount();
    }

    function toggleRowCheckbox(e, chkId) {
        const cb = document.getElementById(chkId);
        if (cb && e.target.tagName !== 'INPUT') {
            cb.checked = !cb.checked;
            updateSelectedCount();
        }
    }

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.student-checkbox:checked').length;
        const total = document.querySelectorAll('.student-checkbox').length;
        const countText = document.getElementById('selectedCountText');
        const submitBtn = document.getElementById('btnAssignSubmit');
        const masterCb = document.getElementById('selectAllUnassigned');

        if (countText) countText.textContent = checked;
        if (submitBtn) submitBtn.disabled = checked === 0;
        if (masterCb) {
            masterCb.checked = (total > 0 && checked === total);
            masterCb.indeterminate = (checked > 0 && checked < total);
        }
    }
</script>
@endsection
