@extends('layouts.app')

@section('title', ($isAdmin ? 'Kelompok ' : 'Kelompok Saya - ') . $typeLabel)
@section('page-title', ($isAdmin ? 'Kelompok ' : 'Kelompok Saya - ') . $typeLabel)

@push('styles')
<style>
    /* Responsive Styling Kelompok Qur'an */
    .quran-hero-banner {
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
        box-shadow: 0 10px 25px -5px rgba(6, 95, 70, 0.25);
    }
    .quran-hero-bg-tilawati {
        background: linear-gradient(135deg, #065f46 0%, #047857 55%, #0d9488 100%);
    }
    .quran-hero-bg-tahfidz {
        background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #0284c7 100%);
    }
    .quran-hero-watermark {
        position: absolute;
        right: -15px;
        bottom: -25px;
        font-size: 9.5rem;
        color: rgba(255, 255, 255, 0.07);
        pointer-events: none;
        z-index: 1;
    }
    .quran-hero-content {
        position: relative;
        z-index: 2;
    }
    .quran-hero-actions {
        position: relative;
        z-index: 2;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }
    .quran-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }
    .quran-kpi-card {
        background: white;
        border-radius: 16px;
        padding: 16px 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 3px 12px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .quran-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }
    .quran-kpi-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    /* Desktop vs Mobile Card Views */
    @media (max-width: 768px) {
        .quran-hero-banner {
            padding: 18px 16px !important;
            border-radius: 16px !important;
        }
        .quran-hero-banner h2 {
            font-size: 1.35rem !important;
        }
        .quran-hero-actions {
            width: 100% !important;
            flex-direction: column !important;
        }
        .quran-hero-actions a,
        .quran-hero-actions button {
            width: 100% !important;
            justify-content: center !important;
            text-align: center !important;
        }
        .quran-kpi-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }
        .quran-kpi-card {
            padding: 12px 14px !important;
        }
        .quran-kpi-icon {
            width: 38px !important;
            height: 38px !important;
            font-size: 1.1rem !important;
        }
        .quran-desktop-table-wrap {
            display: none !important;
        }
        .quran-mobile-card-list {
            display: flex !important;
            flex-direction: column !important;
            gap: 12px !important;
            padding: 12px !important;
        }
    }
    @media (min-width: 769px) {
        .quran-mobile-card-list {
            display: none !important;
        }
    }
    .group-mobile-item {
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

    <!-- 1. HERO BANNER MODERN -->
    <div class="quran-hero-banner {{ $type === 'tilawati' ? 'quran-hero-bg-tilawati' : 'quran-hero-bg-tahfidz' }}">
        <div class="quran-hero-content">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.18); backdrop-filter: blur(4px); padding: 4px 14px; border-radius: 20px; font-size: 0.76rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                <i class="fas {{ $type === 'tilawati' ? 'fa-book-reader' : 'fa-quran' }}"></i>
                <span>Program Pembinaan {{ $typeLabel }}</span>
                <span>•</span>
                <span>TP {{ $activeYear->name ?? '-' }}</span>
            </div>
            <h2 style="margin: 0; font-size: 1.65rem; font-weight: 800; letter-spacing: -0.5px;">
                {{ $isAdmin ? "Kelompok Pembinaan {$typeLabel}" : "Kelompok {$typeLabel} Saya" }}
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 0.88rem; opacity: 0.92; max-width: 620px; line-height: 1.5;">
                @if($isAdmin)
                    Pusat kendali kelompok {{ strtolower($typeLabel) }}, distribusi peserta didik, pemantauan musyrif/pembimbing, dan monitoring capaian tuntas.
                @else
                    Amanah kelompok {{ strtolower($typeLabel) }} yang Anda bimbing. Pantau perkembangan bacaan & hafalan santri binaan.
                @endif
            </p>
        </div>

        <div class="quran-hero-actions">
            @if($isAdmin)
                <a href="{{ $type === 'tilawati' ? route('tilawati-groups.create') : route('tahfidz-groups.create') }}" 
                   style="background: white; color: {{ $type === 'tilawati' ? '#065f46' : '#0369a1' }}; font-weight: 800; padding: 10px 18px; border-radius: 12px; font-size: 0.86rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
                    <i class="fas fa-plus-circle"></i> Buat Kelompok Baru
                </a>
                <a href="{{ route('mentoring.management', ['tab' => $type]) }}" 
                   style="background: rgba(255,255,255,0.2); color: white; border: 1.5px solid rgba(255,255,255,0.4); font-weight: 700; padding: 9px 16px; border-radius: 12px; font-size: 0.86rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-layer-group"></i> Manajemen Hub
                </a>
                @if($type === 'tahfidz')
                    <a href="{{ route('reports.index', ['tab' => 'tahfidz', 'year_id' => $selectedYearId]) }}" 
                       style="background: rgba(255,255,255,0.2); color: white; border: 1.5px solid rgba(255,255,255,0.4); font-weight: 700; padding: 9px 16px; border-radius: 12px; font-size: 0.86rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-chart-pie"></i> Rekap Laporan
                    </a>
                @endif
            @endif
            <a href="{{ $type === 'tilawati' ? route('tilawati-groups.print', ['year_id' => $selectedYearId]) : route('tahfidz-groups.print', ['year_id' => $selectedYearId]) }}" 
               target="_blank" 
               style="background: rgba(15, 23, 42, 0.45); color: white; border: 1px solid rgba(255,255,255,0.3); font-weight: 700; padding: 9px 16px; border-radius: 12px; font-size: 0.86rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-print"></i> Cetak Rekap
            </a>
        </div>
        <i class="fas {{ $type === 'tilawati' ? 'fa-book-reader' : 'fa-quran' }} quran-hero-watermark"></i>
    </div>

    <!-- 2. STATS OVERVIEW CARDS (SIMETRIS & RESPONSIF) -->
    <div class="quran-kpi-grid">
        <div class="quran-kpi-card" style="border-left: 4px solid {{ $type === 'tilawati' ? '#059669' : '#0284c7' }};">
            <div class="quran-kpi-icon" style="background: {{ $type === 'tilawati' ? '#ecfdf5' : '#f0f9ff' }}; color: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }};">
                <i class="fas {{ $type === 'tilawati' ? 'fa-book-reader' : 'fa-quran' }}"></i>
            </div>
            <div>
                <span style="font-size: 0.74rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.3px; display: block;">
                    {{ $isAdmin ? 'Total Kelompok' : 'Kelompok Saya' }}
                </span>
                <h3 style="margin: 2px 0 0 0; font-size: 1.45rem; font-weight: 900; color: #0f172a;">
                    {{ $groups->total() }} <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Kelompok</span>
                </h3>
            </div>
        </div>

        <div class="quran-kpi-card" style="border-left: 4px solid #10b981;">
            <div class="quran-kpi-icon" style="background: #ecfdf5; color: #10b981;">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <span style="font-size: 0.74rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.3px; display: block;">
                    {{ $isAdmin ? 'Siswa Terkelompokkan' : 'Total Siswa Binaan' }}
                </span>
                <h3 style="margin: 2px 0 0 0; font-size: 1.45rem; font-weight: 900; color: #0f172a;">
                    {{ $totalAssigned }} 
                    @if($isAdmin)
                        <small style="font-size: 0.78rem; font-weight: 600; color: #64748b;">/ {{ $totalStudentsInYear }}</small>
                    @else
                        <small style="font-size: 0.78rem; font-weight: 600; color: #64748b;">Siswa</small>
                    @endif
                </h3>
            </div>
        </div>

        @if($isAdmin)
            <div class="quran-kpi-card" style="border-left: 4px solid {{ $totalUnassigned > 0 ? '#f59e0b' : '#10b981' }};">
                <div class="quran-kpi-icon" style="background: {{ $totalUnassigned > 0 ? '#fffbeb' : '#ecfdf5' }}; color: {{ $totalUnassigned > 0 ? '#d97706' : '#059669' }};">
                    <i class="fas {{ $totalUnassigned > 0 ? 'fa-user-clock' : 'fa-check-double' }}"></i>
                </div>
                <div>
                    <span style="font-size: 0.74rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.3px; display: block;">
                        Belum Masuk Kelompok
                    </span>
                    <h3 style="margin: 2px 0 0 0; font-size: 1.45rem; font-weight: 900; color: {{ $totalUnassigned > 0 ? '#b45309' : '#047857' }};">
                        {{ $totalUnassigned }} <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Siswa</span>
                    </h3>
                </div>
            </div>
        @else
            <div class="quran-kpi-card" style="border-left: 4px solid #8b5cf6;">
                <div class="quran-kpi-icon" style="background: #f5f3ff; color: #8b5cf6;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <span style="font-size: 0.74rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.3px; display: block;">
                        Tahun Pelajaran
                    </span>
                    <h3 style="margin: 2px 0 0 0; font-size: 1.15rem; font-weight: 900; color: #0f172a;">
                        {{ $activeYear ? $activeYear->name : '-' }}
                    </h3>
                </div>
            </div>
        @endif

        <div class="quran-kpi-card" style="border-left: 4px solid #6366f1;">
            <div class="quran-kpi-icon" style="background: #eef2ff; color: #4f46e5;">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div>
                <span style="font-size: 0.74rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.3px; display: block;">
                    TP Terpilih
                </span>
                <h3 style="margin: 2px 0 0 0; font-size: 1.15rem; font-weight: 900; color: #0f172a;">
                    {{ $academicYears->firstWhere('id', $selectedYearId)->name ?? '-' }}
                </h3>
            </div>
        </div>
    </div>

    <!-- 3. FILTER & SEARCH CONTROL -->
    <div style="background: white; border-radius: 16px; padding: 14px 18px; border: 1px solid #e2e8f0; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <form action="{{ $type === 'tilawati' ? route('tilawati-groups.index') : route('tahfidz-groups.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 180px;">
                <select name="year_id" class="form-control" onchange="this.form.submit()" style="padding: 9px 12px; font-size: 0.88rem; font-weight: 600; border-radius: 10px; border: 1px solid #cbd5e1;">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            TP: {{ $year->name }} {{ $year->is_active ? '(Aktif Saat Ini)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 2; min-width: 220px; position: relative;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kelompok, guru pembimbing, jenjang target..." class="form-control" style="padding: 9px 12px 9px 34px; font-size: 0.88rem; border-radius: 10px; border: 1px solid #cbd5e1;">
                <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
            </div>
            <button type="submit" class="btn-action-header btn-blue" style="padding: 9px 16px; border-radius: 10px; font-weight: 700;">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if($search)
                <a href="{{ $type === 'tilawati' ? route('tilawati-groups.index', ['year_id' => $selectedYearId]) : route('tahfidz-groups.index', ['year_id' => $selectedYearId]) }}" class="btn-action-header btn-dark" style="background: #64748b; padding: 9px 14px; border-radius: 10px;">
                    <i class="fas fa-undo"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <!-- 4. DAFTAR KELOMPOK (DESKTOP TABLE & MOBILE CARDS) -->
    <div style="background: white; border-radius: 18px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03); overflow: hidden;">
        
        <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; background: #f8fafc;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #0f172a;">
                    {{ $isAdmin ? "Daftar Kelompok {$typeLabel}" : "Kelompok {$typeLabel} yang Anda Bimbing" }}
                </h3>
                <span style="background: #e2e8f0; color: #334155; font-size: 0.78rem; font-weight: 800; padding: 2px 10px; border-radius: 12px;">
                    {{ $groups->total() }} Kelompok
                </span>
            </div>
            <span style="font-size: 0.82rem; color: #64748b;">
                Halaman {{ $groups->currentPage() }} dari {{ max(1, $groups->lastPage()) }}
            </span>
        </div>

        {{-- A. DESKTOP TABLE VIEW --}}
        <div class="quran-desktop-table-wrap" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 0; font-size: 0.88rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 13px 16px; width: 45px; text-align: center; color: #475569; font-weight: 800;">No</th>
                        <th style="padding: 13px 16px; text-align: left; color: #475569; font-weight: 800;">Nama Kelompok</th>
                        <th style="padding: 13px 16px; text-align: left; color: #475569; font-weight: 800;">Pembimbing / Mentor</th>
                        <th style="padding: 13px 16px; text-align: left; color: #475569; font-weight: 800;">Target / Jenjang</th>
                        <th style="padding: 13px 16px; text-align: left; color: #475569; font-weight: 800;">Jadwal & Tempat</th>
                        <th style="padding: 13px 16px; text-align: center; color: #475569; font-weight: 800;">Jumlah Siswa</th>
                        <th style="padding: 13px 16px; text-align: center; width: 230px; color: #475569; font-weight: 800;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groups as $index => $group)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                            <td style="padding: 14px 16px; text-align: center; font-weight: 700; color: #64748b;">
                                {{ $groups->firstItem() + $index }}
                            </td>
                            <td style="padding: 14px 16px;">
                                <a href="{{ $type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" style="font-weight: 800; color: #0f172a; text-decoration: none; font-size: 0.95rem;">
                                    {{ $group->name }}
                                </a>
                                @if($group->notes)
                                    <div style="font-size: 0.76rem; color: #64748b; margin-top: 2px;">
                                        <i class="fas fa-info-circle" style="color: #94a3b8;"></i> {{ Str::limit($group->notes, 45) }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 14px 16px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; color: #334155; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.78rem;">
                                        {{ substr($group->mentor_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #1e293b;">{{ $group->mentor_name }}</div>
                                        @if($group->mentorUser)
                                            <small style="color: #059669; font-size: 0.72rem; font-weight: 600;"><i class="fas fa-check-circle"></i> Akun Guru Aktif</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 14px 16px;">
                                @if($group->target_level)
                                    <span style="display: inline-block; background: {{ $type === 'tilawati' ? '#ecfdf5' : '#f0f9ff' }}; color: {{ $type === 'tilawati' ? '#047857' : '#0369a1' }}; border: 1px solid {{ $type === 'tilawati' ? '#a7f3d0' : '#bae6fd' }}; padding: 3px 10px; border-radius: 20px; font-weight: 700; font-size: 0.78rem;">
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
                                    <a href="{{ $type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" class="btn-action-header btn-blue" style="padding: 6px 10px; font-size: 0.78rem; border-radius: 7px; font-weight: 700;" title="Kelola Anggota Siswa">
                                        <i class="fas fa-users"></i> {{ $isAdmin ? 'Siswa' : 'Daftar' }}
                                    </a>

                                    @if($type === 'tahfidz')
                                        <!-- Absensi Sesi -->
                                        <a href="{{ route('tahfidz.attendance.index', ['group' => $group->id]) }}" class="btn-action-header" style="background: #7c3aed; color: white; padding: 6px 10px; font-size: 0.78rem; border-radius: 7px; font-weight: 700;" title="Absensi Kehadiran Sesi">
                                            <i class="fas fa-clipboard-check"></i>
                                        </a>
                                        <!-- Rekap Hafalan -->
                                        <a href="{{ route('tahfidz.print-group-recap', ['group' => $group->id]) }}" target="_blank" class="btn-action-header" style="background: #0f766e; color: white; padding: 6px 10px; font-size: 0.78rem; border-radius: 7px; font-weight: 700;" title="Rekap Hafalan">
                                            <i class="fas fa-quran"></i>
                                        </a>
                                    @endif

                                    <!-- Cetak -->
                                    <a href="{{ route('mentoring-groups.print-group', $group->id) }}" target="_blank" class="btn-action-header btn-dark" style="background: #475569; padding: 6px 9px; font-size: 0.78rem; border-radius: 7px;" title="Cetak Kartu Kelompok">
                                        <i class="fas fa-print"></i>
                                    </a>

                                    @if($isAdmin)
                                        <!-- Edit Kelompok -->
                                        <a href="{{ $type === 'tilawati' ? route('tilawati-groups.edit', $group->id) : route('tahfidz-groups.edit', $group->id) }}" class="btn-action-header btn-orange" style="padding: 6px 9px; font-size: 0.78rem; border-radius: 7px;" title="Edit Data Kelompok">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <!-- Hapus Kelompok -->
                                        <button type="button" onclick="confirmDelete('{{ $group->id }}', '{{ $group->name }}')" class="btn-action-header btn-red" style="padding: 6px 9px; font-size: 0.78rem; border-radius: 7px;" title="Hapus Kelompok">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form id="delete-form-{{ $group->id }}" action="{{ $type === 'tilawati' ? route('tilawati-groups.destroy', $group->id) : route('tahfidz-groups.destroy', $group->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 45px 20px; color: #94a3b8;">
                                <div style="font-size: 2.5rem; margin-bottom: 10px; color: #cbd5e1;">
                                    <i class="fas {{ $type === 'tilawati' ? 'fa-book-reader' : 'fa-quran' }}"></i>
                                </div>
                                <h4 style="margin: 0; color: #475569; font-weight: 800;">
                                    {{ $isAdmin ? "Belum Ada Kelompok {$typeLabel}" : "Anda Belum Memiliki Kelompok {$typeLabel}" }}
                                </h4>
                                <p style="margin: 6px 0 16px 0; font-size: 0.88rem;">
                                    @if($isAdmin)
                                        Mulai dengan membuat kelompok {{ strtolower($typeLabel) }} pertama untuk mengelompokkan siswa.
                                    @else
                                        Saat ini Anda belum ditugaskan membimbing kelompok {{ strtolower($typeLabel) }}. Hubungi Kesiswaan / Admin jika ada penugasan baru.
                                    @endif
                                </p>
                                @if($isAdmin)
                                    <a href="{{ $type === 'tilawati' ? route('tilawati-groups.create') : route('tahfidz-groups.create') }}" class="btn-action-header btn-green" style="background: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }};">
                                        <i class="fas fa-plus"></i> Buat Kelompok Sekarang
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- B. MOBILE CARD VIEW (SUPER ELEGAN & SIMETRIS DI HP) --}}
        <div class="quran-mobile-card-list">
            @forelse($groups as $group)
                <div class="group-mobile-item">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; gap: 8px;">
                        <div>
                            <a href="{{ $type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" style="font-weight: 800; color: #0f172a; text-decoration: none; font-size: 1.05rem;">
                                {{ $group->name }}
                            </a>
                            <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px; font-size: 0.82rem; color: #475569;">
                                <i class="fas fa-user-tie" style="color: #64748b;"></i>
                                <strong>{{ $group->mentor_name }}</strong>
                            </div>
                        </div>
                        <span style="background: #f1f5f9; color: #0f172a; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 0.82rem; white-space: nowrap;">
                            {{ $group->students_count }} Siswa
                        </span>
                    </div>

                    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center; margin-bottom: 12px; font-size: 0.78rem;">
                        @if($group->target_level)
                            <span style="background: {{ $type === 'tilawati' ? '#ecfdf5' : '#f0f9ff' }}; color: {{ $type === 'tilawati' ? '#047857' : '#0369a1' }}; border: 1px solid {{ $type === 'tilawati' ? '#a7f3d0' : '#bae6fd' }}; padding: 2px 8px; border-radius: 12px; font-weight: 700;">
                                <i class="fas fa-bullseye"></i> {{ $group->target_level }}
                            </span>
                        @endif
                        @if($group->schedule)
                            <span style="color: #64748b;">
                                <i class="fas fa-clock"></i> {{ $group->schedule }}
                            </span>
                        @endif
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 6px; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                        <a href="{{ $type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" 
                           class="btn-action-header btn-blue" 
                           style="padding: 7px 10px; font-size: 0.78rem; text-align: center; justify-content: center; border-radius: 8px; font-weight: 700;">
                            <i class="fas fa-users"></i> Siswa
                        </a>
                        @if($type === 'tahfidz')
                            <a href="{{ route('tahfidz.attendance.index', ['group' => $group->id]) }}" 
                               class="btn-action-header" 
                               style="background: #7c3aed; color: white; padding: 7px 10px; font-size: 0.78rem; text-align: center; justify-content: center; border-radius: 8px; font-weight: 700;">
                                <i class="fas fa-clipboard-check"></i> Absensi
                            </a>
                        @endif
                        <a href="{{ route('mentoring-groups.print-group', $group->id) }}" 
                           target="_blank" 
                           class="btn-action-header btn-dark" 
                           style="background: #475569; padding: 7px 10px; font-size: 0.78rem; text-align: center; justify-content: center; border-radius: 8px;">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                        @if($isAdmin)
                            <a href="{{ $type === 'tilawati' ? route('tilawati-groups.edit', $group->id) : route('tahfidz-groups.edit', $group->id) }}" 
                               class="btn-action-header btn-orange" 
                               style="padding: 7px 10px; font-size: 0.78rem; text-align: center; justify-content: center; border-radius: 8px;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" 
                                    onclick="confirmDelete('{{ $group->id }}', '{{ $group->name }}')" 
                                    class="btn-action-header btn-red" 
                                    style="padding: 7px 10px; font-size: 0.78rem; text-align: center; justify-content: center; border-radius: 8px;">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 30px 16px; color: #94a3b8;">
                    <i class="fas {{ $type === 'tilawati' ? 'fa-book-reader' : 'fa-quran' }}" style="font-size: 2rem; margin-bottom: 8px;"></i>
                    <p style="margin: 0; font-size: 0.9rem;">Belum ada kelompok yang terdaftar.</p>
                </div>
            @endforelse
        </div>

        @if($groups->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                {{ $groups->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Kelompok?',
            text: `Apakah Anda yakin ingin menghapus kelompok "${name}"? Siswa di dalamnya akan dilepaskan dari kelompok ini.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
