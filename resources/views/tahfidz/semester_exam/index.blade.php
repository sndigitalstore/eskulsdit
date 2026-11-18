@extends('layouts.app')

@section('title', 'Ujian Hafalan Akhir Semester - Tahfidz')

@section('content')
<style>
    .exam-header-card {
        background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0f766e 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: white;
        margin-bottom: 20px;
        box-shadow: 0 10px 25px -5px rgba(6, 95, 70, 0.3);
    }
    .semester-pill-nav {
        display: inline-flex;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 30px;
        padding: 4px;
        gap: 4px;
    }
    .semester-pill-btn {
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.82rem;
        color: white;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .semester-pill-btn.active {
        background: white;
        color: #065f46;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .kpi-exam-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }
    .kpi-exam-card {
        background: white;
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .kpi-exam-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    /* SEARCH & STUDENT SELECTION CARD */
    .student-search-selection-card {
        background: #ffffff;
        border: 1.5px solid #a7f3d0;
        border-radius: 16px;
        padding: 18px 22px;
        margin-bottom: 20px;
        box-shadow: 0 4px 16px rgba(5, 150, 105, 0.06);
    }
    .search-selection-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 14px;
    }
    .search-selection-title-box {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .search-selection-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #ecfdf5;
        border: 1.5px solid #a7f3d0;
        color: #047857;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .search-selection-title {
        margin: 0 0 2px 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #064e3b;
    }
    .search-selection-subtitle {
        margin: 0;
        font-size: 0.8rem;
        color: #64748b;
    }
    .student-filter-pills {
        display: inline-flex;
        background: #f1f5f9;
        border-radius: 30px;
        padding: 3px;
        gap: 4px;
    }
    .filter-pill {
        border: none;
        background: transparent;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .filter-pill.active {
        background: #047857;
        color: white;
        box-shadow: 0 2px 6px rgba(4, 120, 87, 0.3);
    }

    /* SEARCHABLE DROPDOWN / SELECTOR */
    .custom-student-dropdown {
        position: relative;
    }
    .student-dropdown-trigger {
        width: 100%;
        background: #f8fafc;
        border: 2px solid #cbd5e1;
        border-radius: 12px;
        padding: 12px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
    }
    .student-dropdown-trigger:hover,
    .student-dropdown-trigger.open {
        background: #ffffff;
        border-color: #059669;
        box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.12);
    }
    .trigger-left {
        display: flex;
        align-items: center;
        gap: 12px;
        overflow: hidden;
    }
    .trigger-placeholder {
        font-weight: 700;
        color: #334155;
        font-size: 0.95rem;
    }
    .trigger-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }
    .badge-active-group {
        background: #d1fae5;
        color: #065f46;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 800;
    }

    /* MENU POPUP PENCARIAN */
    .student-dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        background: white;
        border-radius: 14px;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        z-index: 100;
        display: none;
        overflow: hidden;
    }
    .student-dropdown-menu.show {
        display: block;
    }
    .dropdown-search-box {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        position: relative;
        display: flex;
        align-items: center;
    }
    .dropdown-search-icon {
        position: absolute;
        left: 28px;
        color: #94a3b8;
        font-size: 0.9rem;
    }
    .dropdown-search-input {
        width: 100%;
        padding: 9px 36px 9px 38px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #0f172a;
        outline: none;
        transition: border-color 0.2s ease;
    }
    .dropdown-search-input:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }
    .clear-search-btn {
        position: absolute;
        right: 28px;
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0;
    }
    .dropdown-options-list {
        max-height: 320px;
        overflow-y: auto;
    }
    .dropdown-student-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 16px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: background 0.15s ease;
        gap: 12px;
    }
    .dropdown-student-item:hover {
        background: #f0fdf4;
    }
    .dropdown-student-item.selected {
        background: #ecfdf5;
        border-left: 4px solid #047857;
    }
    .item-avatar {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #e2e8f0;
        color: #475569;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    .dropdown-student-item.is-saved .item-avatar {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }
    .item-info {
        flex: 1;
        min-width: 0;
    }
    .item-name {
        font-weight: 700;
        font-size: 0.9rem;
        color: #0f172a;
        margin-bottom: 2px;
    }
    .item-meta {
        font-size: 0.74rem;
        color: #64748b;
    }
    .badge-status-sudah {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .badge-status-belum {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }

    /* ACTIVE FOCUSED STUDENT WORKSPACE CARD */
    .focused-student-card {
        background: white;
        border-radius: 18px;
        border: 2px solid #059669;
        box-shadow: 0 10px 30px rgba(5, 150, 105, 0.1);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .focused-student-header {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        padding: 18px 24px;
        border-bottom: 1.5px solid #a7f3d0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .focused-student-nav {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-nav-student {
        background: white;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .btn-nav-student:hover:not(:disabled) {
        background: #047857;
        border-color: #047857;
        color: white;
    }
    .btn-nav-student:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* SEMESTER 1 COMPLETED BOX */
    .sem1-history-box {
        background: #f0fdf4;
        border: 1.5px dashed #86efac;
        border-radius: 14px;
        padding: 14px 18px;
        margin: 16px 22px 0 22px;
    }
    .sem1-history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 10px;
    }
    .sem1-chip-item {
        background: white;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 0.78rem;
        color: #1e293b;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    /* SURAH TABLE */
    .surah-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }
    .surah-table th {
        background: #f8fafc;
        padding: 10px 14px;
        font-weight: 700;
        color: #475569;
        text-align: left;
        border-bottom: 1.5px solid #e2e8f0;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .surah-table td {
        padding: 9px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .input-exam-score {
        width: 85px;
        padding: 7px 10px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-weight: 800;
        font-size: 1rem;
        text-align: center;
        color: #0f172a;
        transition: all 0.2s ease;
    }
    .input-exam-score:focus {
        border-color: #059669;
        outline: none;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }
    .badge-auto-juz {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1.5px solid #bfdbfe;
        font-weight: 800;
        font-size: 0.82rem;
        padding: 6px 12px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }
    .badge-grade {
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 0.76rem;
        display: inline-block;
        letter-spacing: 0.3px;
    }
    .grade-A { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .grade-B { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
    .grade-C { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .grade-D { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    .btn-add-surah {
        background: #ecfdf5;
        color: #059669;
        border: 1.5px dashed #a7f3d0;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .btn-add-surah:hover {
        background: #d1fae5;
        border-color: #059669;
    }

    /* ACTION BAR FOCUSED STUDENT */
    .focused-action-bar {
        background: #f8fafc;
        padding: 16px 24px;
        border-top: 1.5px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .btn-save-focused {
        background: #047857;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.88rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(4, 120, 87, 0.25);
        transition: all 0.2s ease;
    }
    .btn-save-focused:hover {
        background: #065f46;
        transform: translateY(-1px);
    }
    .btn-save-next {
        background: #0284c7;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.88rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        transition: all 0.2s ease;
    }
    .btn-save-next:hover {
        background: #0369a1;
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid" style="padding: 0 15px; max-width: 1300px; margin: 0 auto;">

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div style="background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle" style="font-size: 1.2rem; color: #10b981;"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-exclamation-circle" style="font-size: 1.2rem; color: #ef4444;"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- HEADER CARD --}}
    <div class="exam-header-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div>
                <span style="background: rgba(255, 255, 255, 0.2); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fas fa-quran"></i> Evaluasi Akhir Semester Halaqah
                </span>
                <h1 style="font-size: 1.65rem; font-weight: 900; margin: 8px 0 4px 0; color: white;">
                    Ujian Hafalan Akhir Semester (Tahfidz)
                </h1>
                <p style="margin: 0; font-size: 0.88rem; opacity: 0.9; max-width: 720px; line-height: 1.5;">
                    Penilaian hafalan surat santri per halaqah. Data surat otomatis terisi sesuai capaian tuntas santri pada semester ini. Nomor Juz otomatis terisi mengikuti nama surat. Guru dapat mengedit nilai dan materi kapan saja.
                </p>
            </div>

            {{-- TOMBOL FILTER SEMESTER & CETAK --}}
            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                <div class="semester-pill-nav">
                    <a href="{{ route('tahfidz.semester-exam.index', ['group_id' => $activeGroup->id, 'year_id' => $selectedYearId, 'semester' => '1']) }}" 
                       class="semester-pill-btn {{ $selectedSemester === '1' ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Semester 1 (Ganjil)
                    </a>
                    <a href="{{ route('tahfidz.semester-exam.index', ['group_id' => $activeGroup->id, 'year_id' => $selectedYearId, 'semester' => '2']) }}" 
                       class="semester-pill-btn {{ $selectedSemester === '2' ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i> Semester 2 (Genap)
                    </a>
                </div>

                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('tahfidz.semester-exam.print', ['group_id' => $activeGroup->id, 'year_id' => $selectedYearId, 'semester' => $selectedSemester]) }}" 
                       target="_blank" 
                       class="btn-action-header" 
                       style="background: white; color: #065f46; font-weight: 800; padding: 8px 16px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-size: 0.82rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <i class="fas fa-print"></i> Cetak Rekap Ujian (A4)
                    </a>
                </div>
            </div>
        </div>

        {{-- FILTER BAR KELOMPOK & TAHUN AJARAN --}}
        <div style="margin-top: 18px; padding-top: 16px; border-top: 1px solid rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <span style="font-weight: 700; font-size: 0.85rem;"><i class="fas fa-users"></i> Kelompok:</span>
                <select onchange="window.location.href=this.value" style="padding: 7px 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.4); background: white; color: #1e293b; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    @foreach($myGroups as $grp)
                        <option value="{{ route('tahfidz.semester-exam.index', ['group_id' => $grp->id, 'year_id' => $selectedYearId, 'semester' => $selectedSemester]) }}" {{ $activeGroup->id === $grp->id ? 'selected' : '' }}>
                            {{ $grp->name }} ({{ $grp->students->count() }} Santri)
                        </option>
                    @endforeach
                </select>

                <span style="font-weight: 700; font-size: 0.85rem; margin-left: 10px;"><i class="fas fa-clock"></i> Tahun Ajaran:</span>
                <select onchange="window.location.href=this.value" style="padding: 7px 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.4); background: white; color: #1e293b; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    @foreach($academicYears as $y)
                        <option value="{{ route('tahfidz.semester-exam.index', ['group_id' => $activeGroup->id, 'year_id' => $y->id, 'semester' => $selectedSemester]) }}" {{ $selectedYearId === $y->id ? 'selected' : '' }}>
                            {{ $y->name }} {{ $y->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="font-size: 0.82rem; opacity: 0.95;">
                <i class="fas fa-user-tie"></i> Musyrif Pengampu: <strong>{{ $activeGroup->mentorUser?->name ?? $activeGroup->mentor_name ?? '-' }}</strong>
            </div>
        </div>
    </div>

    {{-- KPI STATS --}}
    <div class="kpi-exam-grid">
        <div class="kpi-exam-card">
            <div class="kpi-exam-icon" style="background: #e0f2fe; color: #0284c7;">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <div style="font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Santri</div>
                <div style="font-size: 1.35rem; font-weight: 900; color: #0f172a;">{{ $students->count() }} <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Orang</span></div>
            </div>
        </div>

        <div class="kpi-exam-card">
            <div class="kpi-exam-icon" style="background: #ecfdf5; color: #059669;">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <div style="font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Sudah Dievaluasi</div>
                <div style="font-size: 1.35rem; font-weight: 900; color: #059669;">
                    <span id="kpiEvaluatedCount">{{ $totalEvaluated }}</span> <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">/ {{ $students->count() }} Santri</span>
                </div>
            </div>
        </div>

        <div class="kpi-exam-card">
            <div class="kpi-exam-icon" style="background: #fef3c7; color: #d97706;">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <div style="font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Rata-Rata Kelompok</div>
                <div style="font-size: 1.35rem; font-weight: 900; color: #b45309;" id="kpiGroupAverage">
                    {{ $groupAverage > 0 ? number_format($groupAverage, 1) : '-' }}
                </div>
            </div>
        </div>

        <div class="kpi-exam-card">
            <div class="kpi-exam-icon" style="background: #ede9fe; color: #7c3aed;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <div style="font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Periode Evaluasi</div>
                <div style="font-size: 0.95rem; font-weight: 800; color: #6d28d9; margin-top: 2px;">
                    Semester {{ $selectedSemester }} ({{ $selectedSemester == '1' ? 'Ganjil' : 'Genap' }})
                </div>
            </div>
        </div>
    </div>

    {{-- 1. KOTAK PENCARIAN & PILIH NAMA SANTRI (ONE-BY-ONE FOCUSED SELECTOR) --}}
    <div class="student-search-selection-card">
        <div class="search-selection-header">
            <div class="search-selection-title-box">
                <div class="search-selection-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div>
                    <h3 class="search-selection-title">Pencarian & Pilih Nama Santri Ujian</h3>
                    <p class="search-selection-subtitle">
                        Kelompok: <strong>{{ $activeGroup->name }}</strong> &bull; Input ujian hafalan santri satu per satu. Nomor Juz otomatis terisi mengikuti surat yang dipilih.
                    </p>
                </div>
            </div>

            {{-- FILTER PILLS: SEMUA, BELUM DINILAI, SUDAH DINILAI --}}
            <div class="student-filter-pills">
                <button type="button" class="filter-pill active" id="filter-pill-all" onclick="filterStudentList('all')">
                    Semua (<span id="count-pill-all">{{ $students->count() }}</span>)
                </button>
                <button type="button" class="filter-pill" id="filter-pill-belum" onclick="filterStudentList('belum')">
                    Belum Dinilai (<span id="count-pill-belum">{{ max(0, $students->count() - $totalEvaluated) }}</span>)
                </button>
                <button type="button" class="filter-pill" id="filter-pill-sudah" onclick="filterStudentList('sudah')">
                    Sudah Dinilai (<span id="count-pill-sudah">{{ $totalEvaluated }}</span>)
                </button>
            </div>
        </div>

        {{-- SEARCHABLE DROPDOWN TRIGGER & PANEL --}}
        <div class="custom-student-dropdown" id="studentSearchDropdownWrap">
            <button type="button" class="student-dropdown-trigger" id="studentDropdownTrigger" onclick="toggleStudentDropdown(event)">
                <div class="trigger-left" id="triggerDisplayArea">
                    <i class="fas fa-user-graduate" style="color: #059669; font-size: 1.15rem; flex-shrink: 0;"></i>
                    <span class="trigger-placeholder" id="triggerPlaceholderText">-- Klik di sini untuk Cari / Pilih Nama Santri yang Diuji --</span>
                </div>
                <div class="trigger-right">
                    <span class="badge-active-group">{{ $activeGroup->name }}</span>
                    <i class="fas fa-chevron-down" id="triggerChevron" style="color: #64748b;"></i>
                </div>
            </button>

            {{-- MENU POPUP PENCARIAN --}}
            <div class="student-dropdown-menu" id="studentDropdownMenu">
                <div class="dropdown-search-box">
                    <i class="fas fa-search dropdown-search-icon"></i>
                    <input type="text" id="studentSearchField" class="dropdown-search-input" 
                           placeholder="Ketik nama santri atau kelas untuk mencari..." 
                           oninput="handleStudentSearchInput(this.value)" autocomplete="off">
                    <button type="button" class="clear-search-btn" id="clearSearchBtn" onclick="clearStudentSearchInput()" style="display: none;">&times;</button>
                </div>
                <div class="dropdown-options-list" id="studentDropdownOptionsList">
                    @foreach($students as $idx => $std)
                        @php
                            $exData = $studentsExamData[$std->id] ?? null;
                            $hasSaved = $exData && $exData['is_saved'];
                            $itemCount = count($exData['items'] ?? []);
                        @endphp
                        <div class="dropdown-student-item {{ $hasSaved ? 'is-saved' : '' }}" 
                             id="dropdown-item-{{ $std->id }}"
                             data-student-id="{{ $std->id }}"
                             data-name="{{ strtolower($std->name) }}"
                             data-class="{{ strtolower($std->class) }}"
                             data-status="{{ $hasSaved ? 'sudah' : 'belum' }}"
                             onclick="selectStudent({{ $std->id }})">
                            <div class="item-avatar" id="avatar-std-{{ $std->id }}">{{ $idx + 1 }}</div>
                            <div class="item-info">
                                <div class="item-name">{{ $std->name }}</div>
                                <div class="item-meta">
                                    Kelas {{ $std->class }} @if($std->nis)&bull; NIS: {{ $std->nis }}@endif
                                </div>
                            </div>
                            <div class="item-badge-status" id="dropdown-status-{{ $std->id }}">
                                @if($hasSaved)
                                    <span class="badge-status-sudah">
                                        <i class="fas fa-check-circle"></i> Sudah Dinilai: <strong>{{ number_format($exData['average_score'], 1) }}</strong> ({{ $exData['predicate'] }}) &bull; {{ $itemCount }} Surat
                                    </span>
                                @else
                                    <span class="badge-status-belum">
                                        <i class="far fa-clock"></i> Belum Dinilai &bull; Draft {{ $itemCount }} Surat
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <div class="dropdown-empty-state" id="dropdownEmptyNotice" style="display: none; padding: 18px; text-align: center; color: #94a3b8; font-size: 0.85rem;">
                        <i class="fas fa-search"></i> Santri tidak ditemukan
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. WORKSPACE AREA PENILAIAN FOKUS SATU PER SATU SANTRI --}}
    <div id="studentWorkspaceContainer">
        @forelse($students as $index => $std)
            @php
                $examData = $studentsExamData[$std->id] ?? null;
                $isSaved = $examData && $examData['is_saved'];
                $items = $examData['items'] ?? [];
                $sem1Items = $examData['sem1_items'] ?? [];
                $avg = $examData['average_score'] ?? 0;
                $pred = $examData['predicate'] ?? 'Jayyid';
            @endphp

            <div class="focused-student-card" id="studentCard-{{ $std->id }}" style="display: none;" data-student-id="{{ $std->id }}" data-student-index="{{ $index }}">
                
                {{-- HEADER KARTU FOKUS SANTRI & NAVIGASI --}}
                <div class="focused-student-header">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: {{ $isSaved ? '#dcfce7; color: #15803d; border: 1.5px solid #86efac;' : '#e0f2fe; color: #0284c7; border: 1.5px solid #bae6fd;' }} display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1.25rem;" id="cardAvatar-{{ $std->id }}">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <h2 style="font-size: 1.25rem; font-weight: 900; margin: 0; color: #0f172a;">{{ $std->name }}</h2>
                                <span style="background: #e2e8f0; color: #334155; padding: 3px 9px; border-radius: 6px; font-size: 0.74rem; font-weight: 800;">
                                    Kelas {{ $std->class }}
                                </span>
                                @if($std->nis)
                                    <span style="color: #64748b; font-size: 0.78rem;">NIS: {{ $std->nis }}</span>
                                @endif
                            </div>
                            <div style="font-size: 0.78rem; margin-top: 4px;" id="cardStatusBadge-{{ $std->id }}">
                                @if($isSaved)
                                    <span style="color: #059669; font-weight: 800;"><i class="fas fa-check-circle"></i> Nilai Ujian Semester Tersimpan (Dapat Diedit Kapan Saja)</span>
                                @else
                                    <span style="color: #d97706; font-weight: 800;"><i class="fas fa-clock"></i> Belum Disimpan (Draft Auto-Populate Setoran Harian)</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- NAVIGASI SEBELUMNYA / BERIKUTNYA --}}
                    <div class="focused-student-nav">
                        <button type="button" class="btn-nav-student" onclick="navigateStudent(-1)" {{ $index === 0 ? 'disabled' : '' }} title="Santri Sebelumnya">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </button>
                        <span style="font-size: 0.8rem; font-weight: 800; color: #475569; padding: 0 4px;">
                            {{ $index + 1 }} / {{ $students->count() }}
                        </span>
                        <button type="button" class="btn-nav-student" onclick="navigateStudent(1)" {{ $index === $students->count() - 1 ? 'disabled' : '' }} title="Santri Berikutnya">
                            Berikutnya <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                {{-- SUB HEADER INFO: TANGGAL UJIAN & LIVE AVERAGE --}}
                <div style="padding: 12px 24px; background: #ffffff; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569;"><i class="fas fa-calendar-day" style="color: #059669;"></i> Tanggal Ujian:</label>
                        <input type="date" id="examDate-{{ $std->id }}" value="{{ $examData['exam_date'] ?? date('Y-m-d') }}" style="padding: 6px 10px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.82rem; font-weight: 700; color: #0f172a;">
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 0.8rem; font-weight: 700; color: #64748b;">Rata-Rata Nilai:</span>
                        <span id="avgScoreDisplay-{{ $std->id }}" style="font-size: 1.25rem; font-weight: 900; color: #047857;">{{ $avg > 0 ? number_format($avg, 1) : '-' }}</span>
                        <span id="predBadgeDisplay-{{ $std->id }}" class="badge-grade grade-{{ $avg >= 90 ? 'A' : ($avg >= 80 ? 'B' : ($avg >= 70 ? 'C' : 'D')) }}">
                            {{ $pred }}
                        </span>
                    </div>
                </div>

                {{-- JIKA SEMESTER 2: BLOK RIWAYAT TUNTAS SEMESTER 1 (TETAP ADA & TIDAK HARUS DIUJI KEMBALI) --}}
                @if($selectedSemester === '2')
                    @if(!empty($sem1Items))
                        <div class="sem1-history-box">
                            <div class="sem1-history-header">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="background: #10b981; color: white; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.72rem;">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <strong style="color: #065f46; font-size: 0.88rem;">
                                        Riwayat Tuntas Ujian Semester 1 (Ganjil) &bull; {{ count($sem1Items) }} Surat Selesai
                                    </strong>
                                </div>
                                <span style="font-size: 0.72rem; background: #dcfce7; color: #166534; font-weight: 800; padding: 2px 8px; border-radius: 4px; border: 1px solid #86efac;">
                                    Tuntas &bull; Tidak Perlu Diuji Ulang
                                </span>
                            </div>
                            <div style="font-size: 0.76rem; color: #047857; margin-bottom: 8px; line-height: 1.4;">
                                Surat di bawah ini telah lulus diujikan pada Semester 1 dan nilainya tersimpan permanen. Ujian Semester 2 berfokus pada surat baru yang disetorkan santri pada tabel di bawah.
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @foreach($sem1Items as $s1)
                                    <div class="sem1-chip-item">
                                        <i class="fas fa-quran" style="color: #10b981;"></i>
                                        <strong>{{ $s1['surah_name'] }}</strong>
                                        <span style="color: #64748b;">(Juz {{ $s1['juz'] }})</span>:
                                        <strong style="color: #047857;">{{ number_format($s1['score'], 0) }}</strong>
                                        <span class="badge-grade grade-{{ $s1['score'] >= 90 ? 'A' : ($s1['score'] >= 80 ? 'B' : ($s1['score'] >= 70 ? 'C' : 'D')) }}" style="font-size: 0.68rem; padding: 1px 6px;">
                                            {{ $s1['predicate'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 10px; padding: 10px 16px; margin: 16px 22px 0 22px; font-size: 0.78rem; color: #64748b;">
                            <i class="fas fa-info-circle" style="color: #0284c7;"></i> Tidak ada catatan ujian Semester 1 untuk ananda ini. Seluruh surat hafalan yang disetorkan dapat diujikan di bawah.
                        </div>
                    @endif
                @endif

                {{-- TABEL SURAT YANG DIUJIKAN SEMESTER INI --}}
                <div style="padding: 18px 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                        <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #0f172a;">
                            <i class="fas fa-list-check" style="color: #059669;"></i> Daftar Surat yang Diujikan (Semester {{ $selectedSemester }})
                        </h4>
                        <span style="font-size: 0.76rem; color: #64748b;">
                            * Nomor Juz otomatis mengikuti surat. Skor 0-100.
                        </span>
                    </div>

                    <table class="surah-table" id="tableSurahs-{{ $std->id }}">
                        <thead>
                            <tr>
                                <th style="width: 5%; text-align: center;">No</th>
                                <th style="width: 32%;">Nama Surat Diuji</th>
                                <th style="width: 16%;">Juz (Otomatis)</th>
                                <th style="width: 16%; text-align: center;">Nilai (0 - 100)</th>
                                <th style="width: 14%; text-align: center;">Predikat</th>
                                <th style="width: 13%;">Catatan Singkat</th>
                                <th style="width: 4%; text-align: center;">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="tbodySurahs-{{ $std->id }}">
                            @forelse($items as $iIndex => $item)
                                @php
                                    $rowJuz = $item['juz'] ?? \App\Http\Controllers\TahfidzAttendanceController::determineJuzForSurah($item['surah_name']);
                                @endphp
                                <tr id="row-{{ $std->id }}-{{ $iIndex }}">
                                    <td class="row-num" style="text-align: center; color: #64748b; font-weight: bold;">{{ $iIndex + 1 }}</td>
                                    <td>
                                        <input type="text" 
                                               list="surahDatalist" 
                                               id="surahInput-{{ $std->id }}-{{ $iIndex }}"
                                               value="{{ $item['surah_name'] }}" 
                                               class="form-control surah-name-input" 
                                               style="font-weight: 700; color: #0f172a; padding: 7px 10px; font-size: 0.88rem; border-radius: 8px; border: 1.5px solid #cbd5e1;" 
                                               required 
                                               placeholder="Pilih/Ketik Surat"
                                               oninput="onSurahNameChange(this, {{ $std->id }}, {{ $iIndex }})"
                                               onchange="onSurahNameChange(this, {{ $std->id }}, {{ $iIndex }})">
                                    </td>
                                    <td>
                                        <span class="badge-auto-juz" id="badgeJuz-{{ $std->id }}-{{ $iIndex }}">
                                            <i class="fas fa-bookmark"></i> Juz {{ $rowJuz ?: 30 }}
                                        </span>
                                        <input type="hidden" id="hiddenJuz-{{ $std->id }}-{{ $iIndex }}" value="{{ $rowJuz ?: 30 }}">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" 
                                               id="scoreInput-{{ $std->id }}-{{ $iIndex }}"
                                               value="{{ $item['score'] }}" 
                                               min="0" max="100" step="0.5" 
                                               class="input-exam-score score-input-{{ $std->id }}" 
                                               oninput="calculateStudentAvg({{ $std->id }})" 
                                               required>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="item-pred-badge badge-grade grade-{{ $item['score'] >= 90 ? 'A' : ($item['score'] >= 80 ? 'B' : ($item['score'] >= 70 ? 'C' : 'D')) }}" id="predBadge-{{ $std->id }}-{{ $iIndex }}">
                                            {{ $item['predicate'] ?? \App\Models\TahfidzSemesterExam::resolvePredicate((float)$item['score']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               id="notesInput-{{ $std->id }}-{{ $iIndex }}"
                                               value="{{ $item['notes'] }}" 
                                               class="form-control" 
                                               style="font-size: 0.82rem; padding: 6px 10px; border-radius: 8px; border: 1.5px solid #cbd5e1;" 
                                               placeholder="Contoh: Lancar, tartil">
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" onclick="removeSurahRow({{ $std->id }}, {{ $iIndex }})" style="background: #fee2e2; color: #ef4444; border: none; width: 30px; height: 30px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;" title="Hapus Surat">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow-{{ $std->id }}">
                                    <td colspan="7" style="text-align: center; padding: 20px; color: #94a3b8; font-style: italic;">
                                        Belum ada surat baru yang disetor pada Semester {{ $selectedSemester }}. Klik tombol <strong>+ Tambah Surat Diuji</strong> di bawah untuk memasukkan materi ujian santri.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div style="margin-top: 14px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <button type="button" onclick="addSurahRow({{ $std->id }})" class="btn-add-surah">
                            <i class="fas fa-plus-circle"></i> Tambah Surat Diuji
                        </button>

                        <div style="display: flex; align-items: center; gap: 8px;">
                            <label style="font-size: 0.78rem; font-weight: 700; color: #64748b;">Catatan Umum Musyrif:</label>
                            <input type="text" id="generalNotes-{{ $std->id }}" value="{{ $examData['notes'] ?? '' }}" placeholder="Evaluasi umum penguji (opsional)" class="form-control" style="font-size: 0.82rem; min-width: 300px; padding: 7px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1;">
                        </div>
                    </div>
                </div>

                {{-- ACTION BAR BOTTOM UNTUK SANTRI FOKUS INI --}}
                <div class="focused-action-bar">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-info-circle" style="color: #059669; font-size: 1.2rem;"></i>
                        <span style="font-size: 0.82rem; color: #475569;">
                            Nilai langsung tersimpan ke pangkalan data rapor halaqah. Guru memiliki akses edit penuh kapan saja.
                        </span>
                    </div>

                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" class="btn-save-focused" id="btnSaveSingle-{{ $std->id }}" onclick="saveStudentExam({{ $std->id }}, false)">
                            <i class="fas fa-save"></i> Simpan Nilai Santri Ini
                        </button>
                        @if($index < $students->count() - 1)
                            <button type="button" class="btn-save-next" id="btnSaveNext-{{ $std->id }}" onclick="saveStudentExam({{ $std->id }}, true)">
                                <i class="fas fa-arrow-right"></i> Simpan & Lanjut ke Santri Berikutnya
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        @empty
            <div style="background: white; border-radius: 16px; padding: 40px; text-align: center; color: #64748b; border: 1.5px solid #e2e8f0;">
                <i class="fas fa-user-slash" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px;"></i>
                <h3 style="font-weight: 800; color: #0f172a;">Belum ada santri di kelompok ini</h3>
                <p>Silakan hubungi administrator untuk menambahkan santri ke kelompok binaan ini.</p>
            </div>
        @endforelse
    </div>

    {{-- DATALIST SURAH UNTUK AUTOCOMPLETE --}}
    <datalist id="surahDatalist">
        @foreach($allSurahs as $sName)
            <option value="{{ $sName }}">
        @endforeach
    </datalist>

</div>

{{-- JAVASCRIPT ENGINE UNTUK SEARCH, AUTO-JUZ, REAL-TIME CALCULATION & ONE-BY-ONE WORKSPACE --}}
<script>
    // Data Mappings
    const surahToJuzMap = @json($surahToJuzMap ?? []);
    const studentsList = [
        @foreach($students as $idx => $s)
            { id: {{ $s->id }}, name: @json($s->name), class: @json($s->class), isSaved: {{ ($studentsExamData[$s->id]['is_saved'] ?? false) ? 'true' : 'false' }} },
        @endforeach
    ];

    let currentActiveStudentId = null;
    let surahCounters = {};

    @foreach($students as $std)
        surahCounters[{{ $std->id }}] = {{ count($studentsExamData[$std->id]['items'] ?? []) }};
    @endforeach

    // Utility: Dapatkan nomor Juz otomatis dari nama surat
    function getJuzForSurah(surahName) {
        if (!surahName) return 30;
        const clean = surahName.trim().toLowerCase().replace(/[^a-z0-9]/g, '').replace(/^(?:surat|surah|qs)/, '');
        
        // Exact / Normalized match
        for (const [sName, juz] of Object.entries(surahToJuzMap)) {
            const sClean = sName.trim().toLowerCase().replace(/[^a-z0-9]/g, '').replace(/^(?:surat|surah|qs)/, '');
            if (sClean === clean) {
                return juz;
            }
        }
        // Substring match
        for (const [sName, juz] of Object.entries(surahToJuzMap)) {
            const sClean = sName.trim().toLowerCase().replace(/[^a-z0-9]/g, '').replace(/^(?:surat|surah|qs)/, '');
            if (sClean.includes(clean) || clean.includes(sClean)) {
                return juz;
            }
        }
        return 30;
    }

    // Auto-update Juz badge & hidden input saat nama surat diketik / dipilih
    function onSurahNameChange(input, studentId, idx) {
        const surah = input.value;
        const juz = getJuzForSurah(surah);
        const badge = document.getElementById(`badgeJuz-${studentId}-${idx}`);
        const hidden = document.getElementById(`hiddenJuz-${studentId}-${idx}`);
        if (badge) {
            badge.innerHTML = `<i class="fas fa-bookmark"></i> Juz ${juz}`;
        }
        if (hidden) {
            hidden.value = juz;
        }
    }

    // Resolve Nilai ke Predikat Mutu
    function resolvePredicateJs(score) {
        if (score >= 90) return { label: 'Mumtaz (A)', cls: 'grade-A' };
        if (score >= 80) return { label: 'Jayyid Jiddan (B)', cls: 'grade-B' };
        if (score >= 70) return { label: 'Jayyid (C)', cls: 'grade-C' };
        return { label: 'Maqbul (D)', cls: 'grade-D' };
    }

    // Hitung Live Rata-Rata Santri
    function calculateStudentAvg(studentId) {
        const tbody = document.getElementById('tbodySurahs-' + studentId);
        if (!tbody) return;

        const scoreInputs = tbody.querySelectorAll('.score-input-' + studentId);
        let total = 0;
        let count = 0;

        scoreInputs.forEach(inp => {
            const row = inp.closest('tr');
            const val = parseFloat(inp.value);
            const badge = row.querySelector('.item-pred-badge');

            if (!isNaN(val)) {
                total += val;
                count++;
                if (badge) {
                    const pred = resolvePredicateJs(val);
                    badge.textContent = pred.label;
                    badge.className = 'item-pred-badge badge-grade ' + pred.cls;
                }
            } else if (badge) {
                badge.textContent = '-';
                badge.className = 'item-pred-badge badge-grade';
            }
        });

        const avgEl = document.getElementById('avgScoreDisplay-' + studentId);
        const predEl = document.getElementById('predBadgeDisplay-' + studentId);

        if (count > 0) {
            const avg = total / count;
            const pred = resolvePredicateJs(avg);
            if (avgEl) avgEl.textContent = avg.toFixed(1);
            if (predEl) {
                predEl.textContent = pred.label;
                predEl.className = 'badge-grade ' + pred.cls;
            }
        } else {
            if (avgEl) avgEl.textContent = '-';
            if (predEl) {
                predEl.textContent = 'Belum Ada';
                predEl.className = 'badge-grade';
            }
        }
    }

    // Tambah Baris Surat Baru
    function addSurahRow(studentId) {
        const tbody = document.getElementById('tbodySurahs-' + studentId);
        const emptyRow = document.getElementById('emptyRow-' + studentId);
        if (emptyRow) emptyRow.remove();

        if (!surahCounters[studentId]) surahCounters[studentId] = 0;
        const idx = surahCounters[studentId]++;
        const rowNum = tbody.querySelectorAll('tr').length + 1;

        const defaultSurah = 'An-Naba';
        const defaultJuz = getJuzForSurah(defaultSurah);

        const tr = document.createElement('tr');
        tr.id = `row-${studentId}-${idx}`;
        tr.innerHTML = `
            <td class="row-num" style="text-align: center; color: #64748b; font-weight: bold;">${rowNum}</td>
            <td>
                <input type="text" 
                       list="surahDatalist" 
                       id="surahInput-${studentId}-${idx}"
                       value="" 
                       class="form-control surah-name-input" 
                       style="font-weight: 700; color: #0f172a; padding: 7px 10px; font-size: 0.88rem; border-radius: 8px; border: 1.5px solid #cbd5e1;" 
                       required 
                       placeholder="Pilih/Ketik Surat"
                       oninput="onSurahNameChange(this, ${studentId}, ${idx})"
                       onchange="onSurahNameChange(this, ${studentId}, ${idx})">
            </td>
            <td>
                <span class="badge-auto-juz" id="badgeJuz-${studentId}-${idx}">
                    <i class="fas fa-bookmark"></i> Juz 30
                </span>
                <input type="hidden" id="hiddenJuz-${studentId}-${idx}" value="30">
            </td>
            <td style="text-align: center;">
                <input type="number" 
                       id="scoreInput-${studentId}-${idx}"
                       value="85" 
                       min="0" max="100" step="0.5" 
                       class="input-exam-score score-input-${studentId}" 
                       oninput="calculateStudentAvg(${studentId})" 
                       required>
            </td>
            <td style="text-align: center;">
                <span class="item-pred-badge badge-grade grade-B" id="predBadge-${studentId}-${idx}">Jayyid Jiddan (B)</span>
            </td>
            <td>
                <input type="text" 
                       id="notesInput-${studentId}-${idx}"
                       value="" 
                       class="form-control" 
                       style="font-size: 0.82rem; padding: 6px 10px; border-radius: 8px; border: 1.5px solid #cbd5e1;" 
                       placeholder="Contoh: Lancar, tartil">
            </td>
            <td style="text-align: center;">
                <button type="button" onclick="removeSurahRow(${studentId}, ${idx})" style="background: #fee2e2; color: #ef4444; border: none; width: 30px; height: 30px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;" title="Hapus Surat">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        calculateStudentAvg(studentId);
    }

    // Hapus Baris Surat
    function removeSurahRow(studentId, idx) {
        const row = document.getElementById(`row-${studentId}-${idx}`);
        if (row) {
            row.remove();
            const tbody = document.getElementById('tbodySurahs-' + studentId);
            tbody.querySelectorAll('tr').forEach((r, i) => {
                const num = r.querySelector('.row-num');
                if (num) num.textContent = i + 1;
            });
            calculateStudentAvg(studentId);
        }
    }

    // Toggle Dropdown Pencarian
    function toggleStudentDropdown(e) {
        e.stopPropagation();
        const menu = document.getElementById('studentDropdownMenu');
        const trigger = document.getElementById('studentDropdownTrigger');
        const chevron = document.getElementById('triggerChevron');
        
        const isShown = menu.classList.contains('show');
        if (isShown) {
            menu.classList.remove('show');
            trigger.classList.remove('open');
            chevron.className = 'fas fa-chevron-down';
        } else {
            menu.classList.add('show');
            trigger.classList.add('open');
            chevron.className = 'fas fa-chevron-up';
            setTimeout(() => {
                document.getElementById('studentSearchField').focus();
            }, 100);
        }
    }

    // Handle Pencarian Santri Real-time
    function handleStudentSearchInput(query) {
        const q = query.trim().toLowerCase();
        const clearBtn = document.getElementById('clearSearchBtn');
        clearBtn.style.display = q ? 'block' : 'none';

        const items = document.querySelectorAll('.dropdown-student-item');
        let visibleCount = 0;

        items.forEach(item => {
            const name = item.dataset.name || '';
            const cls = item.dataset.class || '';
            const match = name.includes(q) || cls.includes(q);

            // Periksa juga kecocokan filter pill saat ini
            const activePill = document.querySelector('.filter-pill.active')?.id;
            let pillMatch = true;
            if (activePill === 'filter-pill-belum' && item.dataset.status !== 'belum') pillMatch = false;
            if (activePill === 'filter-pill-sudah' && item.dataset.status !== 'sudah') pillMatch = false;

            if (match && pillMatch) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        const empty = document.getElementById('dropdownEmptyNotice');
        if (empty) empty.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    function clearStudentSearchInput() {
        const inp = document.getElementById('studentSearchField');
        inp.value = '';
        handleStudentSearchInput('');
        inp.focus();
    }

    // Filter Dropdown by Pill (Semua, Belum Dinilai, Sudah Dinilai)
    function filterStudentList(filter) {
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
        document.getElementById(`filter-pill-${filter}`)?.classList.add('active');

        const query = document.getElementById('studentSearchField')?.value || '';
        handleStudentSearchInput(query);
    }

    // Pilih Santri Aktif untuk Ditampilkan
    function selectStudent(studentId) {
        currentActiveStudentId = studentId;

        // Tutup dropdown menu
        const menu = document.getElementById('studentDropdownMenu');
        const trigger = document.getElementById('studentDropdownTrigger');
        const chevron = document.getElementById('triggerChevron');
        menu.classList.remove('show');
        trigger.classList.remove('open');
        chevron.className = 'fas fa-chevron-down';

        // Update trigger display
        const item = document.getElementById('dropdown-item-' + studentId);
        if (item) {
            document.querySelectorAll('.dropdown-student-item').forEach(i => i.classList.remove('selected'));
            item.classList.add('selected');

            const name = item.querySelector('.item-name')?.textContent || '';
            const meta = item.querySelector('.item-meta')?.textContent || '';
            document.getElementById('triggerPlaceholderText').innerHTML = `
                <strong style="color: #064e3b; font-size: 1rem;">${name}</strong>
                <span style="color: #64748b; font-size: 0.8rem; margin-left: 8px;">(${meta.trim()})</span>
            `;
        }

        // Tampilkan kartu santri yang dipilih, sembunyikan santri lainnya
        document.querySelectorAll('.focused-student-card').forEach(card => {
            card.style.display = 'none';
        });

        const activeCard = document.getElementById('studentCard-' + studentId);
        if (activeCard) {
            activeCard.style.display = 'block';
            activeCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Navigasi Antar Santri (Sebelumnya / Berikutnya)
    function navigateStudent(direction) {
        if (!currentActiveStudentId) return;
        const currentIdx = studentsList.findIndex(s => s.id === currentActiveStudentId);
        if (currentIdx === -1) return;

        const nextIdx = currentIdx + direction;
        if (nextIdx >= 0 && nextIdx < studentsList.length) {
            selectStudent(studentsList[nextIdx].id);
        }
    }

    // SIMPAN NILAI UJIAN SANTRI SECARA AJAX
    function saveStudentExam(studentId, thenNext = false) {
        const card = document.getElementById('studentCard-' + studentId);
        if (!card) return;

        const tbody = document.getElementById('tbodySurahs-' + studentId);
        const rows = tbody.querySelectorAll('tr:not([id^="emptyRow"])');
        
        if (rows.length === 0) {
            alert('Perhatian: Silakan tambahkan minimal 1 surat yang diujikan sebelum menyimpan.');
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('group_id', '{{ $activeGroup->id }}');
        formData.append('year_id', '{{ $selectedYearId }}');
        formData.append('semester', '{{ $selectedSemester }}');

        const examDate = document.getElementById('examDate-' + studentId)?.value || '{{ date("Y-m-d") }}';
        const generalNotes = document.getElementById('generalNotes-' + studentId)?.value || '';

        formData.append(`students[${studentId}][exam_date]`, examDate);
        formData.append(`students[${studentId}][notes]`, generalNotes);

        let itemIndex = 0;
        let hasInvalidScore = false;

        rows.forEach(row => {
            const surahInp = row.querySelector('.surah-name-input');
            const juzInp = row.querySelector('input[type="hidden"]');
            const scoreInp = row.querySelector('.input-exam-score');
            const notesInp = row.querySelector('input[placeholder*="Lancar"]');

            const surahVal = surahInp ? surahInp.value.trim() : '';
            const juzVal = juzInp ? juzInp.value : getJuzForSurah(surahVal);
            const scoreVal = scoreInp ? parseFloat(scoreInp.value) : 0;
            const notesVal = notesInp ? notesInp.value.trim() : '';

            if (surahVal !== '') {
                if (isNaN(scoreVal) || scoreVal < 0 || scoreVal > 100) {
                    hasInvalidScore = true;
                }
                formData.append(`students[${studentId}][items][${itemIndex}][surah_name]`, surahVal);
                formData.append(`students[${studentId}][items][${itemIndex}][juz]`, juzVal);
                formData.append(`students[${studentId}][items][${itemIndex}][score]`, scoreVal);
                formData.append(`students[${studentId}][items][${itemIndex}][notes]`, notesVal);
                itemIndex++;
            }
        });

        if (hasInvalidScore) {
            alert('Nilai ujian harus berada pada rentang angka 0 sampai 100.');
            return;
        }

        if (itemIndex === 0) {
            alert('Mohon isi nama surat yang diujikan.');
            return;
        }

        // Tampilkan loading button
        const btnSave = document.getElementById('btnSaveSingle-' + studentId);
        const btnNext = document.getElementById('btnSaveNext-' + studentId);
        const origSaveHtml = btnSave ? btnSave.innerHTML : '';
        const origNextHtml = btnNext ? btnNext.innerHTML : '';

        if (btnSave) { btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; btnSave.disabled = true; }
        if (btnNext) { btnNext.disabled = true; }

        fetch('{{ route("tahfidz.semester-exam.store") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (btnSave) { btnSave.innerHTML = origSaveHtml; btnSave.disabled = false; }
            if (btnNext) { btnNext.innerHTML = origNextHtml; btnNext.disabled = false; }

            if (data.success) {
                // Update kartu fokus status
                const badge = document.getElementById('cardStatusBadge-' + studentId);
                if (badge) {
                    badge.innerHTML = '<span style="color: #059669; font-weight: 800;"><i class="fas fa-check-circle"></i> Nilai Ujian Semester Tersimpan (Dapat Diedit Kapan Saja)</span>';
                }

                // Update dropdown item status
                const dropItem = document.getElementById('dropdown-item-' + studentId);
                if (dropItem) {
                    dropItem.classList.add('is-saved');
                    dropItem.dataset.status = 'sudah';

                    const avatar = document.getElementById('avatar-std-' + studentId);
                    if (avatar) {
                        avatar.style.background = '#dcfce7';
                        avatar.style.color = '#15803d';
                        avatar.style.border = '1px solid #86efac';
                    }

                    const dropStatus = document.getElementById('dropdown-status-' + studentId);
                    if (dropStatus && data.student) {
                        dropStatus.innerHTML = `
                            <span class="badge-status-sudah">
                                <i class="fas fa-check-circle"></i> Sudah Dinilai: <strong>${data.student.average_score}</strong> (${data.student.predicate}) &bull; ${data.student.items_count} Surat
                            </span>
                        `;
                    }
                }

                // Update counter pill
                const stdObj = studentsList.find(s => s.id === studentId);
                if (stdObj && !stdObj.isSaved) {
                    stdObj.isSaved = true;
                    const evalEl = document.getElementById('kpiEvaluatedCount');
                    if (evalEl) {
                        const cur = parseInt(evalEl.textContent) || 0;
                        evalEl.textContent = cur + 1;
                    }
                    const countSudah = document.getElementById('count-pill-sudah');
                    const countBelum = document.getElementById('count-pill-belum');
                    if (countSudah) countSudah.textContent = (parseInt(countSudah.textContent) || 0) + 1;
                    if (countBelum) countBelum.textContent = Math.max(0, (parseInt(countBelum.textContent) || 0) - 1);
                }

                if (thenNext) {
                    navigateStudent(1);
                } else {
                    alert('✓ ' + data.message);
                }
            } else {
                alert('✕ ' + (data.message || 'Gagal menyimpan data'));
            }
        })
        .catch(err => {
            if (btnSave) { btnSave.innerHTML = origSaveHtml; btnSave.disabled = false; }
            if (btnNext) { btnNext.innerHTML = origNextHtml; btnNext.disabled = false; }
            alert('✕ Terjadi kesalahan koneksi saat menyimpan.');
            console.error(err);
        });
    }

    // Klik di luar dropdown untuk menutup popup
    document.addEventListener('click', function(e) {
        const wrap = document.getElementById('studentSearchDropdownWrap');
        const menu = document.getElementById('studentDropdownMenu');
        const trigger = document.getElementById('studentDropdownTrigger');
        const chevron = document.getElementById('triggerChevron');
        if (wrap && !wrap.contains(e.target)) {
            if (menu) menu.classList.remove('show');
            if (trigger) trigger.classList.remove('open');
            if (chevron) chevron.className = 'fas fa-chevron-down';
        }
    });

    // Inisialisasi: Pilih santri pertama secara otomatis saat halaman dibuka
    document.addEventListener('DOMContentLoaded', function() {
        if (studentsList.length > 0) {
            // Prioritaskan pilih santri yang belum dinilai pertama kali, atau santri pertama jika semua sudah
            const firstPending = studentsList.find(s => !s.isSaved);
            const initialId = firstPending ? firstPending.id : studentsList[0].id;
            selectStudent(initialId);
        }
    });
</script>
@endsection
