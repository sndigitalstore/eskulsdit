@extends('layouts.app')

@section('title', 'Manajemen Kelompok Mentoring')
@section('page-title', 'Manajemen Kelompok')

@push('styles')
<style>
    .mentoring-tab-nav {
        display: flex;
        gap: 8px;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 20px;
        overflow-x: auto;
    }
    .mentoring-tab-btn {
        padding: 10px 18px;
        font-weight: 700;
        font-size: 0.92rem;
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
        color: #2563eb;
        border-bottom-color: #2563eb;
    }
    .tab-badge {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
        background: #f1f5f9;
        color: #475569;
    }
    .mentoring-tab-btn.active .tab-badge {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .modal-container {
        background: white;
        border-radius: 16px;
        width: 95%;
        max-width: 580px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        animation: modalFadeIn 0.2s ease-out;
    }
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.96); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
@endpush

@section('content')
<div style="margin-bottom: 30px;">

    <!-- TOP HEADER & TITLE -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 22px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 1.45rem; font-weight: 800; color: #0f172a; letter-spacing: -0.5px;">
                        Manajemen Kelompok Mentoring
                    </h2>
                    <p style="margin: 2px 0 0 0; color: #64748b; font-size: 0.88rem;">
                        Pusat kendali kelompok Qur'an (Tilawati & Tahfidz), distribusi siswa, serta penugasan guru pembimbing.
                    </p>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <button type="button" onclick="openCreateGroupModal()" class="btn-action-header btn-green" style="padding: 9px 16px; font-size: 0.88rem; background: #059669;">
                <i class="fas fa-plus-circle"></i> Buat Kelompok Baru
            </button>
            <div class="dropdown" style="display: inline-block;">
                <a href="{{ route('tahfidz-groups.print', ['year_id' => $selectedYearId]) }}" target="_blank" class="btn-action-header btn-dark" style="background: #334155; padding: 9px 15px; font-size: 0.88rem;">
                    <i class="fas fa-print"></i> Cetak Rekap
                </a>
            </div>
        </div>
    </div>

    <!-- STATS OVERVIEW CARDS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <!-- Card 1: Tilawati -->
        <div class="card" style="padding: 18px 20px; border-left: 4px solid #059669; margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.8rem; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-book-reader"></i> Kelompok Tilawati
                    </span>
                    <h3 style="margin: 8px 0 2px 0; font-size: 1.7rem; font-weight: 800; color: #1e293b;">
                        {{ $totalTilawatiGroups }} <span style="font-size: 0.95rem; font-weight: 600; color: #64748b;">Kelompok</span>
                    </h3>
                    <div style="font-size: 0.84rem; color: #475569; margin-top: 6px;">
                        <strong>{{ $tilawatiAssignedCount }}</strong> siswa terkelompokkan
                        @if($tilawatiUnassignedCount > 0)
                            • <span style="color: #ea580c; font-weight: 700;">{{ $tilawatiUnassignedCount }} belum</span>
                        @else
                            • <span style="color: #059669; font-weight: 600;"><i class="fas fa-check"></i> Lengkap</span>
                        @endif
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-book-open"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Tahfidz -->
        <div class="card" style="padding: 18px 20px; border-left: 4px solid #0284c7; margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.8rem; font-weight: 700; color: #0284c7; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-quran"></i> Kelompok Tahfidz
                    </span>
                    <h3 style="margin: 8px 0 2px 0; font-size: 1.7rem; font-weight: 800; color: #1e293b;">
                        {{ $totalTahfidzGroups }} <span style="font-size: 0.95rem; font-weight: 600; color: #64748b;">Kelompok</span>
                    </h3>
                    <div style="font-size: 0.84rem; color: #475569; margin-top: 6px;">
                        <strong>{{ $tahfidzAssignedCount }}</strong> siswa terkelompokkan
                        @if($tahfidzUnassignedCount > 0)
                            • <span style="color: #ea580c; font-weight: 700;">{{ $tahfidzUnassignedCount }} belum</span>
                        @else
                            • <span style="color: #0284c7; font-weight: 600;"><i class="fas fa-check"></i> Lengkap</span>
                        @endif
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #f0f9ff; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-quran"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Siswa Aktif -->
        <div class="card" style="padding: 18px 20px; border-left: 4px solid #8b5cf6; margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.8rem; font-weight: 700; color: #8b5cf6; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-users"></i> Total Siswa TP Ini
                    </span>
                    <h3 style="margin: 8px 0 2px 0; font-size: 1.7rem; font-weight: 800; color: #1e293b;">
                        {{ $totalActiveStudents }} <span style="font-size: 0.95rem; font-weight: 600; color: #64748b;">Siswa</span>
                    </h3>
                    <div style="font-size: 0.84rem; color: #64748b; margin-top: 6px;">
                        Tahun Pelajaran <strong>{{ $activeYear ? $activeYear->name : '-' }}</strong>
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #f5f3ff; color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Pembimbing -->
        <div class="card" style="padding: 18px 20px; border-left: 4px solid #f59e0b; margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.8rem; font-weight: 700; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-chalkboard-teacher"></i> Guru Pembimbing
                    </span>
                    <h3 style="margin: 8px 0 2px 0; font-size: 1.7rem; font-weight: 800; color: #1e293b;">
                        {{ $totalMentors }} <span style="font-size: 0.95rem; font-weight: 600; color: #64748b;">Ustadz/ah</span>
                    </h3>
                    <div style="font-size: 0.84rem; color: #64748b; margin-top: 6px;">
                        Membimbing Tilawati & Tahfidz
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #fffbeb; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & NAV CONTROLS -->
    <div class="card" style="padding: 16px 20px; margin-bottom: 20px;">
        <form action="{{ route('mentoring.management') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <input type="hidden" name="tab" value="{{ $tab }}">
            @if($tab === 'unassigned')
                <input type="hidden" name="unassigned_type" value="{{ $unassignedType }}">
            @endif

            <div style="min-width: 180px;">
                <label style="display: block; font-size: 0.76rem; font-weight: 700; color: #64748b; margin-bottom: 4px; text-transform: uppercase;">Tahun Pelajaran</label>
                <select name="year_id" class="form-control" onchange="this.form.submit()" style="padding: 8px 12px; font-size: 0.88rem;">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            TP: {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($tab !== 'unassigned')
                <div style="min-width: 200px;">
                    <label style="display: block; font-size: 0.76rem; font-weight: 700; color: #64748b; margin-bottom: 4px; text-transform: uppercase;">Filter Guru Pembimbing</label>
                    <select name="mentor_user_id" class="form-control" onchange="this.form.submit()" style="padding: 8px 12px; font-size: 0.88rem;">
                        <option value="">-- Semua Guru Pembimbing --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $mentorFilter == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 1; min-width: 220px;">
                    <label style="display: block; font-size: 0.76rem; font-weight: 700; color: #64748b; margin-bottom: 4px; text-transform: uppercase;">Pencarian</label>
                    <div style="position: relative;">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kelompok, mentor, target..." class="form-control" style="padding: 8px 12px 8px 34px; font-size: 0.88rem;">
                        <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
                    </div>
                </div>
            @endif

            <div style="align-self: flex-end;">
                <button type="submit" class="btn-action-header btn-blue" style="padding: 8px 16px; font-size: 0.88rem;">
                    <i class="fas fa-filter"></i> Terapkan
                </button>
                @if($search || $mentorFilter)
                    <a href="{{ route('mentoring.management', ['year_id' => $selectedYearId, 'tab' => $tab]) }}" class="btn-action-header btn-dark" style="background: #94a3b8; padding: 8px 14px; font-size: 0.88rem;">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABS NAVIGATION -->
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

    <!-- TAB CONTENT -->
    @if($tab !== 'unassigned')
        <!-- GROUP LIST TABLE -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #1e293b;">
                        Daftar Kelompok Mentoring
                        @if($tab === 'tilawati') (Tilawati) @elseif($tab === 'tahfidz') (Tahfidz) @endif
                    </h3>
                    <span style="background: #f1f5f9; color: #475569; font-size: 0.8rem; font-weight: 700; padding: 2px 10px; border-radius: 12px;">
                        {{ $groups->count() }} Kelompok
                    </span>
                </div>

                <div style="display: flex; gap: 8px;">
                    @if($tab === 'tilawati')
                        <a href="{{ route('tilawati-groups.create') }}" class="btn-action-header btn-green" style="padding: 6px 12px; font-size: 0.82rem; background: #059669;">
                            <i class="fas fa-plus"></i> Buat Tilawati
                        </a>
                    @elseif($tab === 'tahfidz')
                        <a href="{{ route('tahfidz-groups.create') }}" class="btn-action-header btn-green" style="padding: 6px 12px; font-size: 0.82rem; background: #0284c7;">
                            <i class="fas fa-plus"></i> Buat Tahfidz
                        </a>
                    @endif
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; margin-top: 0;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 12px 16px; width: 5%; text-align: center;">No</th>
                            <th style="padding: 12px 16px; text-align: left; width: 12%;">Tipe</th>
                            <th style="padding: 12px 16px; text-align: left;">Nama Kelompok</th>
                            <th style="padding: 12px 16px; text-align: left;">Guru Pembimbing</th>
                            <th style="padding: 12px 16px; text-align: left;">Target Jenjang</th>
                            <th style="padding: 12px 16px; text-align: left;">Jadwal / Tempat</th>
                            <th style="padding: 12px 16px; text-align: center; width: 10%;">Jumlah Siswa</th>
                            <th style="padding: 12px 16px; text-align: center; width: 220px;">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $index => $group)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;">
                                <td style="padding: 14px 16px; text-align: center; font-weight: bold; color: #64748b;">
                                    {{ $index + 1 }}
                                </td>
                                <td style="padding: 14px 16px;">
                                    @if($group->type === 'tilawati')
                                        <span style="display: inline-flex; align-items: center; gap: 5px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 3px 10px; border-radius: 20px; font-weight: 700; font-size: 0.78rem;">
                                            <i class="fas fa-book-reader"></i> Tilawati
                                        </span>
                                    @else
                                        <span style="display: inline-flex; align-items: center; gap: 5px; background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; padding: 3px 10px; border-radius: 20px; font-weight: 700; font-size: 0.78rem;">
                                            <i class="fas fa-quran"></i> Tahfidz
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px;">
                                    <a href="{{ $group->type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" style="font-weight: 700; color: #0f172a; text-decoration: none; font-size: 0.95rem;">
                                        {{ $group->name }}
                                    </a>
                                    @if($group->notes)
                                        <div style="font-size: 0.78rem; color: #64748b; margin-top: 3px;">
                                            {{ Str::limit($group->notes, 40) }}
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; color: #475569; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem;">
                                            {{ substr($group->mentor_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: #1e293b; font-size: 0.9rem;">{{ $group->mentor_name }}</div>
                                            @if($group->mentorUser)
                                                <small style="color: #059669; font-size: 0.74rem;"><i class="fas fa-check-circle"></i> Terhubung Akun</small>
                                            @else
                                                <small style="color: #d97706; font-size: 0.74rem;"><i class="fas fa-exclamation-circle"></i> Belum Link Akun</small>
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
                                <td style="padding: 14px 16px; color: #475569; font-size: 0.88rem;">
                                    {{ $group->schedule ?? '-' }}
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <span style="display: inline-block; background: #f1f5f9; color: #0f172a; padding: 4px 12px; border-radius: 8px; font-weight: 700; font-size: 0.9rem;">
                                        {{ $group->students_count }} Siswa
                                    </span>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                                        <!-- Kelola Siswa -->
                                        <a href="{{ $group->type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" 
                                           class="btn-action-header btn-blue" 
                                           style="padding: 5px 8px; font-size: 0.78rem;" 
                                           title="Kelola Siswa">
                                            <i class="fas fa-users"></i>
                                        </a>

                                        @if($group->type === 'tahfidz')
                                            <!-- Absensi Sesi -->
                                            <a href="{{ route('tahfidz.attendance.index', ['group' => $group->id]) }}" 
                                               class="btn-action-header" 
                                               style="background: #7c3aed; color: white; padding: 5px 8px; font-size: 0.78rem;" 
                                               title="Absensi Sesi">
                                                <i class="fas fa-clipboard-check"></i>
                                            </a>
                                            <!-- Rekap Hafalan -->
                                            <a href="{{ route('tahfidz.print-group-recap', ['group' => $group->id]) }}" 
                                               target="_blank" 
                                               class="btn-action-header" 
                                               style="background: #0f766e; color: white; padding: 5px 8px; font-size: 0.78rem;" 
                                               title="Rekap Hafalan">
                                                <i class="fas fa-quran"></i>
                                            </a>
                                        @endif

                                        <!-- Cetak Kelompok -->
                                        <a href="{{ route('mentoring-groups.print-group', $group->id) }}" 
                                           target="_blank" 
                                           class="btn-action-header btn-dark" 
                                           style="background: #475569; padding: 5px 8px; font-size: 0.78rem;" 
                                           title="Cetak Daftar Kelompok">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ $group->type === 'tilawati' ? route('tilawati-groups.edit', $group->id) : route('tahfidz-groups.edit', $group->id) }}" 
                                           class="btn-action-header btn-orange" 
                                           style="padding: 5px 8px; font-size: 0.78rem;" 
                                           title="Edit Kelompok">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Hapus -->
                                        <button type="button" 
                                                onclick="confirmDeleteGroup('{{ $group->id }}', '{{ $group->name }}', '{{ $group->type }}')" 
                                                class="btn-action-header btn-red" 
                                                style="padding: 5px 8px; font-size: 0.78rem;" 
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
                                    <h4 style="margin: 0; color: #475569; font-size: 1.1rem;">Tidak Ditemukan Kelompok</h4>
                                    <p style="margin: 6px 0 18px 0; font-size: 0.9rem;">
                                        Belum ada kelompok yang terdaftar untuk filter atau pencarian saat ini.
                                    </p>
                                    <button type="button" onclick="openCreateGroupModal()" class="btn-action-header btn-green" style="background: #059669;">
                                        <i class="fas fa-plus-circle"></i> Buat Kelompok Sekarang
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- TAB 4: DISTRIBUSI SISWA BELUM TERKELOMPOKKAN -->
        <div class="card" style="padding: 22px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 18px;">
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">
                        Distribusi Cepat Siswa Belum Berkelompok
                    </h3>
                    <p style="margin: 4px 0 0 0; font-size: 0.88rem; color: #64748b;">
                        Pilih siswa dan masukkan sekaligus ke dalam kelompok tujuan dalam 1 klik.
                    </p>
                </div>

                <!-- Tipe Toggle (Tilawati vs Tahfidz) -->
                <div style="display: flex; background: #f1f5f9; padding: 4px; border-radius: 12px; gap: 4px;">
                    <a href="{{ route('mentoring.management', ['year_id' => $selectedYearId, 'tab' => 'unassigned', 'unassigned_type' => 'tilawati', 'class_filter' => $classFilter]) }}" 
                       style="padding: 7px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; {{ $unassignedType === 'tilawati' ? 'background: white; color: #059669; box-shadow: 0 2px 4px rgba(0,0,0,0.06);' : 'color: #64748b;' }}">
                        <i class="fas fa-book-reader"></i> Tilawati ({{ $tilawatiUnassignedCount }})
                    </a>
                    <a href="{{ route('mentoring.management', ['year_id' => $selectedYearId, 'tab' => 'unassigned', 'unassigned_type' => 'tahfidz', 'class_filter' => $classFilter]) }}" 
                       style="padding: 7px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; {{ $unassignedType === 'tahfidz' ? 'background: white; color: #0284c7; box-shadow: 0 2px 4px rgba(0,0,0,0.06);' : 'color: #64748b;' }}">
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
                    <select name="class_filter" class="form-control" onchange="this.form.submit()" style="padding: 8px 12px; font-size: 0.88rem;">
                        <option value="">-- Semua Kelas ({{ $unassignedStudents->count() }} siswa) --</option>
                        @foreach($availableClasses as $c)
                            <option value="{{ $c }}" {{ $classFilter == $c ? 'selected' : '' }}>
                                Kelas {{ $c }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($classFilter)
                    <a href="{{ route('mentoring.management', ['year_id' => $selectedYearId, 'tab' => 'unassigned', 'unassigned_type' => $unassignedType]) }}" class="btn-action-header btn-dark" style="background: #94a3b8; padding: 8px 12px; font-size: 0.85rem;">
                        Reset Filter Kelas
                    </a>
                @endif
            </form>

            @if($unassignedStudents->isEmpty())
                <div style="text-align: center; padding: 45px 20px; background: #f8fafc; border-radius: 12px; border: 2px dashed #cbd5e1;">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 12px;">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <h4 style="margin: 0; color: #1e293b; font-size: 1.1rem; font-weight: 700;">Seluruh Siswa Sudah Memiliki Kelompok {{ ucfirst($unassignedType) }}!</h4>
                    <p style="margin: 6px 0 0 0; color: #64748b; font-size: 0.9rem;">
                        Tidak ada siswa yang belum masuk kelompok untuk kategori {{ ucfirst($unassignedType) }} pada filter ini.
                    </p>
                </div>
            @else
                <!-- Form Mass Assign -->
                <form action="{{ route('mentoring.quick-assign') }}" method="POST" id="quickAssignForm">
                    @csrf
                    <!-- Action Bar -->
                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 14px 18px; border-radius: 12px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e40af; font-size: 0.9rem; cursor: pointer;">
                                <input type="checkbox" id="selectAllUnassigned" onchange="toggleSelectAll(this)" style="width: 18px; height: 18px; cursor: pointer;">
                                Pilih Semua (<span id="selectedCountText">0</span> siswa dipilih)
                            </label>
                        </div>

                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <select name="group_id" class="form-control" required style="padding: 8px 12px; font-size: 0.88rem; min-width: 260px;">
                                <option value="">-- Pilih Kelompok {{ ucfirst($unassignedType) }} Tujuan --</option>
                                @foreach($availableGroups->where('type', $unassignedType) as $g)
                                    <option value="{{ $g->id }}">
                                        {{ $g->name }} (Mentor: {{ $g->mentor_name }})
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" id="btnAssignSubmit" disabled class="btn-action-header btn-blue" style="padding: 8px 18px; font-size: 0.88rem;">
                                <i class="fas fa-arrow-right"></i> Masukkan ke Kelompok
                            </button>
                        </div>
                    </div>

                    <!-- Students Table -->
                    <div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 12px;">
                        <table style="width: 100%; border-collapse: collapse; margin-top: 0;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                    <th style="padding: 10px 14px; width: 40px; text-align: center;">Pilih</th>
                                    <th style="padding: 10px 14px; text-align: left;">Nama Siswa</th>
                                    <th style="padding: 10px 14px; text-align: center; width: 120px;">NIS</th>
                                    <th style="padding: 10px 14px; text-align: center; width: 100px;">Kelas</th>
                                    <th style="padding: 10px 14px; text-align: center; width: 120px;">Jenis Kelamin</th>
                                    <th style="padding: 10px 14px; text-align: left;">Status Kelompok Lain</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unassignedStudents as $student)
                                    <tr style="border-bottom: 1px solid #f1f5f9; cursor: pointer;" onclick="toggleRowCheckbox(event, 'chk_{{ $student->id }}')">
                                        <td style="padding: 12px 14px; text-align: center;" onclick="event.stopPropagation()">
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="chk_{{ $student->id }}" class="student-checkbox" onchange="updateSelectedCount()" style="width: 17px; height: 17px; cursor: pointer;">
                                        </td>
                                        <td style="padding: 12px 14px; font-weight: 700; color: #0f172a;">
                                            {{ $student->name }}
                                        </td>
                                        <td style="padding: 12px 14px; text-align: center; color: #64748b; font-family: monospace;">
                                            {{ $student->nis ?? '-' }}
                                        </td>
                                        <td style="padding: 12px 14px; text-align: center;">
                                            <span style="background: #f1f5f9; color: #0f172a; padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: 0.85rem;">
                                                {{ $student->class }}
                                            </span>
                                        </td>
                                        <td style="padding: 12px 14px; text-align: center; color: #475569; font-size: 0.85rem;">
                                            {{ $student->gender == 'L' ? 'Laki-laki' : ($student->gender == 'P' ? 'Perempuan' : '-') }}
                                        </td>
                                        <td style="padding: 12px 14px; font-size: 0.82rem; color: #64748b;">
                                            <span style="color: #dc2626; font-weight: 600;"><i class="fas fa-times-circle"></i> Belum ada {{ ucfirst($unassignedType) }}</span>
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
        <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">
                    Buat Kelompok Mentoring Baru
                </h3>
            </div>
            <button type="button" onclick="closeCreateGroupModal()" style="background: none; border: none; font-size: 1.2rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('tilawati-groups.store') }}" method="POST" id="createGroupForm">
            @csrf
            <div style="padding: 24px; max-height: 75vh; overflow-y: auto;">
                <!-- PILIH TIPE MENTORING -->
                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 0.88rem;">
                        Tipe Mentoring <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <label style="border: 2px solid #059669; background: #ecfdf5; padding: 12px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; gap: 10px;" id="lblTilawati">
                            <input type="radio" name="type" value="tilawati" checked onchange="switchGroupType('tilawati')" style="width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #047857; display: block; font-size: 0.9rem;">Tilawati</strong>
                                <small style="color: #065f46; font-size: 0.75rem;">Pembinaan Baca Al-Qur'an</small>
                            </div>
                        </label>
                        <label style="border: 1px solid #cbd5e1; background: #f8fafc; padding: 12px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; gap: 10px;" id="lblTahfidz">
                            <input type="radio" name="type" value="tahfidz" onchange="switchGroupType('tahfidz')" style="width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #0369a1; display: block; font-size: 0.9rem;">Tahfidz</strong>
                                <small style="color: #075985; font-size: 0.75rem;">Hafalan & Sesi Setoran</small>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- NAMA KELOMPOK -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.88rem;">
                        Nama Kelompok <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="Contoh: Halqah Abu Bakar, Tilawati Jilid 2, Tahfidz Juz 30-A" class="form-control" style="padding: 10px 12px; font-size: 0.9rem;">
                </div>

                <!-- TAHUN PELAJARAN -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.88rem;">
                        Tahun Pelajaran <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="academic_year_id" required class="form-control" style="padding: 10px 12px; font-size: 0.9rem;">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- GURU PEMBIMBING -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.88rem;">
                        Guru Pembimbing / Mentor <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="mentor_user_id" id="modalMentorUserId" required onchange="handleMentorSelect(this)" class="form-control" style="padding: 10px 12px; font-size: 0.9rem;">
                        <option value="">-- Pilih Akun Guru --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" data-name="{{ $teacher->name }}">{{ $teacher->name }} ({{ $teacher->role }})</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="mentor_name" id="modalMentorName">
                    <small style="color: #64748b; font-size: 0.78rem; margin-top: 4px; display: block;">
                        Kelompok ini akan otomatis tampil di akun guru yang dipilih.
                    </small>
                </div>

                <!-- TARGET JENJANG -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.88rem;">
                        Target / Jenjang Capaian
                    </label>
                    <input type="text" name="target_level" placeholder="Contoh: Jilid 3, Al-Qur'an, Juz 30, Juz 29" class="form-control" style="padding: 10px 12px; font-size: 0.9rem;">
                </div>

                <!-- JADWAL & TEMPAT -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.88rem;">
                        Jadwal & Tempat
                    </label>
                    <input type="text" name="schedule" placeholder="Contoh: Setiap Senin-Kamis 07:30 - Ruang 2A" class="form-control" style="padding: 10px 12px; font-size: 0.9rem;">
                </div>

                <!-- CATATAN -->
                <div style="margin-bottom: 8px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.88rem;">
                        Catatan Tambahan
                    </label>
                    <textarea name="notes" rows="2" placeholder="Catatan opsional mengenai kriteria siswa kelompok ini..." class="form-control" style="padding: 8px 12px; font-size: 0.88rem;"></textarea>
                </div>
            </div>

            <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeCreateGroupModal()" class="btn-action-header btn-dark" style="background: #e2e8f0; color: #334155; padding: 9px 18px;">
                    Batal
                </button>
                <button type="submit" class="btn-action-header btn-green" id="btnSubmitGroup" style="padding: 9px 20px; background: #059669;">
                    <i class="fas fa-check"></i> Simpan Kelompok
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateGroupModal() {
        document.getElementById('createGroupModal').style.display = 'flex';
    }

    function closeCreateGroupModal(e) {
        if (!e || e.target.id === 'createGroupModal' || e.target.closest('button')) {
            document.getElementById('createGroupModal').style.display = 'none';
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
