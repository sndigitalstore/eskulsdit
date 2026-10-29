@extends('layouts.app')

@section('title', 'Data Prestasi')
@section('page-title', 'Data Prestasi Siswa')

@push('styles')
<style>
    .achievements-page {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    /* ===== TOPBAR CARD ===== */
    .achievements-header-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .achievements-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .achievements-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.25);
    }

    .achievements-header-text h2 {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.25;
    }

    .achievements-header-text p {
        font-size: 0.84rem;
        color: #64748b;
        margin: 3px 0 0 0;
    }

    .achievements-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-act {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 10px;
        font-size: 0.84rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        white-space: nowrap;
        min-height: 38px;
        box-sizing: border-box;
    }

    .btn-act:hover {
        transform: translateY(-1px);
        filter: brightness(1.05);
    }

    .btn-act-dark {
        background: #334155;
        color: #ffffff;
    }

    .btn-act-green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
    }

    .btn-act-blue {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
    }

    /* ===== TABLE CONTAINER ===== */
    .table-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .desktop-table-view {
        display: block;
        overflow-x: auto;
    }

    .achievements-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .achievements-table th {
        background: #f8fafc;
        padding: 13px 18px;
        font-size: 0.8rem;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .achievements-table td {
        padding: 14px 18px;
        font-size: 0.88rem;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .achievements-table tbody tr:hover {
        background: #f8fafc;
    }

    .level-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.76rem;
        font-weight: 700;
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .student-link {
        font-weight: 700;
        color: #0f172a;
        text-decoration: none;
        transition: color 0.2s;
    }

    .student-link:hover {
        color: #0284c7;
    }

    .btn-row-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-row-cert { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .btn-row-edit { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .btn-row-delete { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    .btn-row-action:hover {
        transform: translateY(-2px);
    }

    /* ===== MOBILE VIEW (< 769px) ===== */
    .mobile-achievements-view {
        display: none;
    }

    @media (max-width: 768px) {
        .achievements-header-card {
            padding: 16px 14px;
            border-radius: 14px;
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
        }

        .achievements-header-icon {
            width: 38px;
            height: 38px;
            font-size: 1.1rem;
        }

        .achievements-header-text h2 {
            font-size: 1.15rem;
        }

        .achievements-header-text p {
            font-size: 0.78rem;
        }

        .achievements-header-actions {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .btn-act {
            width: 100%;
            padding: 9px 4px;
            font-size: 0.78rem;
            border-radius: 9px;
            min-height: 38px;
        }

        /* Hide desktop table, show mobile cards */
        .desktop-table-view {
            display: none !important;
        }

        .mobile-achievements-view {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 12px;
            background: #f8fafc;
        }

        .achievement-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            gap: 11px;
            transition: all 0.2s ease;
        }

        .m-ach-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .m-ach-date {
            font-size: 0.74rem;
            color: #64748b;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .m-ach-title {
            font-size: 0.96rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            line-height: 1.35;
            display: flex;
            align-items: flex-start;
            gap: 7px;
        }

        .m-ach-title i {
            color: #f59e0b;
            font-size: 0.95rem;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .m-ach-desc {
            font-size: 0.8rem;
            color: #64748b;
            font-style: italic;
            line-height: 1.45;
            background: #f8fafc;
            padding: 8px 10px;
            border-radius: 8px;
            border-left: 3px solid #f59e0b;
        }

        /* Student & Details Box */
        .m-ach-details {
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 10px;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .m-ach-student-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .m-student-name-box {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.88rem;
            font-weight: 700;
            color: #1e293b;
        }

        .m-student-class-badge {
            font-size: 0.72rem;
            font-weight: 700;
            color: #0284c7;
            background: #e0f2fe;
            padding: 2px 7px;
            border-radius: 5px;
        }

        .m-ach-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
            font-size: 0.74rem;
            color: #64748b;
            padding-top: 5px;
            border-top: 1px solid #f1f5f9;
        }

        .m-ach-organizer {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
        }

        .m-ach-period {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
            color: #475569;
        }

        /* Card Actions */
        .m-ach-actions {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr;
            gap: 8px;
            padding-top: 4px;
        }

        .m-btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 8px 6px;
            border-radius: 9px;
            font-size: 0.76rem;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            min-height: 36px;
            box-sizing: border-box;
            width: 100%;
        }

        .m-btn-cert {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .m-btn-edit {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .m-btn-delete {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .pagination-wrap {
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-align: center;
        }
    }

    @media (max-width: 380px) {
        .achievements-header-actions {
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }

        .btn-act {
            font-size: 0.72rem;
            padding: 8px 2px;
        }

        .btn-act span {
            font-size: 0.72rem;
        }

        .m-ach-actions {
            grid-template-columns: 1fr;
            gap: 6px;
        }
    }
    /* ===== FILTER CARD ===== */
    .achievements-filter-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 18px 22px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid #eef2f7;
    }

    .filter-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 14px;
    }

    .quick-pills-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .filter-label-tag {
        font-size: 0.78rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-right: 4px;
    }

    .quick-pill {
        padding: 6px 13px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .quick-pill:hover {
        background: #f1f5f9;
        color: #0f172a;
        transform: translateY(-1px);
    }

    .quick-pill.active {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
        border-color: #047857;
        box-shadow: 0 2px 8px rgba(4, 120, 87, 0.25);
    }

    .filter-active-indicator {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .active-badge {
        font-size: 0.78rem;
        font-weight: 700;
        color: #047857;
        background: #ecfdf5;
        padding: 5px 10px;
        border-radius: 8px;
        border: 1px solid #a7f3d0;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-reset-filter {
        font-size: 0.78rem;
        font-weight: 700;
        color: #b91c1c;
        background: #fef2f2;
        padding: 5px 10px;
        border-radius: 8px;
        border: 1px solid #fecaca;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }

    .btn-reset-filter:hover {
        background: #fee2e2;
        color: #991b1b;
    }

    .filter-controls-grid {
        display: grid;
        grid-template-columns: 140px minmax(240px, 1.4fr) 145px 125px 140px 1.2fr;
        gap: 12px;
        align-items: flex-end;
    }

    .date-range-flex {
        display: flex;
        align-items: center;
        gap: 5px;
        width: 100%;
    }

    .date-ctrl {
        flex: 1;
        min-width: 0;
        padding: 8px 8px;
    }

    .range-sep {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        white-space: nowrap;
    }

    .filter-field-col {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-col-label {
        font-size: 0.76rem;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 0;
    }

    .filter-ctrl {
        width: 100%;
        box-sizing: border-box;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 0.84rem;
        font-weight: 600;
        color: #1e293b;
        background: #f8fafc;
        min-height: 40px;
        transition: all 0.2s ease;
        outline: none;
    }

    .filter-ctrl:focus {
        border-color: #059669;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
    }

    .search-input-group {
        display: flex;
        align-items: center;
        gap: 6px;
        width: 100%;
    }

    .btn-filter-go {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
        min-height: 40px;
        white-space: nowrap;
        box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25);
        transition: all 0.2s ease;
    }

    .btn-filter-go:hover {
        transform: translateY(-1px);
        filter: brightness(1.05);
    }

    @media (max-width: 1180px) {
        .filter-controls-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        .search-col {
            grid-column: span 3;
        }
    }

    @media (max-width: 768px) {
        .achievements-filter-card {
            padding: 14px;
            border-radius: 14px;
        }
        .filter-top-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .filter-controls-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        .search-col {
            grid-column: span 1;
        }
    }
</style>
@endpush

@section('content')
<div class="achievements-page">

    {{-- 1. Topbar Card --}}
    <div class="achievements-header-card">
        <div class="achievements-header-left">
            <div class="achievements-header-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="achievements-header-text">
                <h2>Data Prestasi Siswa</h2>
                <p>
                    @if($isFiltered)
                        Menampilkan <strong>{{ $achievements->total() }}</strong> dari <strong>{{ $totalAll }}</strong> total prestasi tercatat (Filter Aktif).
                    @else
                        Total <strong>{{ $achievements->total() }}</strong> prestasi kejuaraan & penghargaan tercatat.
                    @endif
                </p>
            </div>
        </div>

        <div class="achievements-header-actions">
            <a href="{{ route('achievements.print', request()->query()) }}" target="_blank" class="btn-act btn-act-dark" title="Cetak Rekap Laporan Sesuai Filter">
                <i class="fas fa-print"></i> <span>Cetak</span>
            </a>
            @if(Auth::user()->role == 'admin')
            <a href="{{ route('achievements.bulk') }}" class="btn-act btn-act-green" title="Input Masal Prestasi">
                <i class="fas fa-file-import"></i> <span>Masal</span>
            </a>
            <a href="{{ route('achievements.create') }}" class="btn-act btn-act-blue" title="Tambah Prestasi Baru">
                <i class="fas fa-plus"></i> <span>Tambah</span>
            </a>
            @endif
        </div>
    </div>

    {{-- 2. Filter Bar (Filter Tanggal, Bulan, Tahun, Tingkat, & Pencarian) --}}
    <div class="achievements-filter-card">
        <form method="GET" action="{{ route('achievements.index') }}" id="achievementFilterForm">
            
            {{-- Baris Atas: Quick Filter Pills & Indikator Status Filter --}}
            <div class="filter-top-bar">
                <div class="quick-pills-wrap">
                    <span class="filter-label-tag"><i class="fas fa-sliders-h"></i> Filter Cepat:</span>
                    
                    {{-- Pill Semua --}}
                    <a href="{{ route('achievements.index') }}" 
                       class="quick-pill {{ (!$isFiltered && !request('category')) ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i> Semua ({{ $totalAll }})
                    </a>

                    {{-- Pill Prestasi Sekolah / Juara Umum --}}
                    <a href="{{ route('achievements.index', ['category' => 'school']) }}" 
                       class="quick-pill {{ request('category') === 'school' ? 'active' : '' }}" style="{{ request('category') === 'school' ? 'background: #f59e0b; border-color: #d97706; color: white;' : '' }}">
                        <i class="fas fa-trophy" style="{{ request('category') === 'school' ? 'color: white;' : 'color: #f59e0b;' }}"></i> Sekolah / Juara Umum ({{ $totalSchool }})
                    </a>

                    {{-- Pill Tgl 1 - 30 (Bulan Ini) --}}
                    @php
                        $presetStart30 = now()->format('Y-m-01');
                        $presetEnd30 = now()->format('Y-m-') . min(30, (int)now()->endOfMonth()->format('d'));
                        $isActiveTgl130 = request('start_date') === $presetStart30 && request('end_date') === $presetEnd30;
                    @endphp
                    <a href="{{ route('achievements.index', array_merge(request()->query(), ['start_date' => $presetStart30, 'end_date' => $presetEnd30, 'month' => '', 'year' => '', 'date' => ''])) }}" 
                       class="quick-pill {{ $isActiveTgl130 ? 'active' : '' }}" 
                       title="Filter data tanggal 1 s.d. 30 bulan ini"
                       style="{{ $isActiveTgl130 ? 'background: #059669; border-color: #047857; color: white;' : '' }}">
                        <i class="far fa-calendar-check" style="{{ $isActiveTgl130 ? 'color: white;' : 'color: #059669;' }}"></i> Tgl 1 - 30 (Bulan Ini)
                    </a>

                    {{-- Pill Hari Ini --}}
                    <a href="{{ route('achievements.index', ['date' => now()->toDateString()]) }}" 
                       class="quick-pill {{ request('date') === now()->toDateString() ? 'active' : '' }}">
                        <i class="fas fa-calendar-day"></i> Hari Ini
                    </a>

                    {{-- Pill Bulan Ini --}}
                    <a href="{{ route('achievements.index', ['month' => now()->format('m'), 'year' => now()->format('Y')]) }}" 
                       class="quick-pill {{ (request('month') == now()->format('m') && request('year') == now()->format('Y') && !request('date')) ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Bulan Ini ({{ now()->translatedFormat('F Y') }})
                    </a>
                </div>

                @if($isFiltered)
                <div class="filter-active-indicator">
                    <span class="active-badge">
                        <i class="fas fa-filter"></i> Ditemukan <strong>{{ $achievements->total() }}</strong> Data
                    </span>
                    <a href="{{ route('achievements.index') }}" class="btn-reset-filter" title="Hapus semua filter">
                        <i class="fas fa-times-circle"></i> Reset Filter
                    </a>
                </div>
                @endif
            </div>

            {{-- Baris Bawah: Form Kontrol (Tanggal, Bulan, Tahun, Tingkat, Pencarian) --}}
            <div class="filter-controls-grid">
                
                {{-- Hidden Category jika aktif --}}
                @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                {{-- 1. Filter Rentang Tanggal (Dari & Sampai, misal Tgl 1 - 30) --}}
                <div class="filter-field-col date-range-col">
                    <label class="filter-col-label">
                        <i class="far fa-calendar-alt" style="color: #f59e0b;"></i> Rentang Tanggal (1 - 30)
                    </label>
                    <div class="date-range-flex">
                        <input type="date" name="start_date" id="filterStartDate" 
                               value="{{ request('start_date', request('date')) }}" 
                               class="filter-ctrl date-ctrl" title="Dari Tanggal (Contoh: Tgl 1)" placeholder="Dari">
                        <span class="range-sep">s/d</span>
                        <input type="date" name="end_date" id="filterEndDate" 
                               value="{{ request('end_date', request('date')) }}" 
                               class="filter-ctrl date-ctrl" title="Sampai Tanggal (Contoh: Tgl 30)" placeholder="Sampai">
                    </div>
                </div>

                {{-- 2. Filter Bulan --}}
                <div class="filter-field-col">
                    <label class="filter-col-label">
                        <i class="fas fa-calendar-week" style="color: #059669;"></i> Bulan
                    </label>
                    <select name="month" id="filterMonthSelect" class="filter-ctrl" onchange="handleMonthYearChange()">
                        <option value="">-- Semua Bulan --</option>
                        @foreach([
                            '01' => '01 - Januari',
                            '02' => '02 - Februari',
                            '03' => '03 - Maret',
                            '04' => '04 - April',
                            '05' => '05 - Mei',
                            '06' => '06 - Juni',
                            '07' => '07 - Juli',
                            '08' => '08 - Agustus',
                            '09' => '09 - September',
                            '10' => '10 - Oktober',
                            '11' => '11 - November',
                            '12' => '12 - Desember'
                        ] as $mNum => $mName)
                            <option value="{{ $mNum }}" {{ (request('month') == $mNum || request('month') == (int)$mNum) ? 'selected' : '' }}>
                                {{ $mName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 3. Filter Tahun --}}
                <div class="filter-field-col">
                    <label class="filter-col-label">
                        <i class="fas fa-calendar" style="color: #0284c7;"></i> Tahun
                    </label>
                    <select name="year" id="filterYearSelect" class="filter-ctrl" onchange="handleMonthYearChange()">
                        <option value="">-- Semua Tahun --</option>
                        @foreach($availableYears as $yr)
                            <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>
                                {{ $yr }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 4. Filter Tingkat Prestasi --}}
                <div class="filter-field-col">
                    <label class="filter-col-label">
                        <i class="fas fa-award" style="color: #8b5cf6;"></i> Tingkat
                    </label>
                    <select name="level" class="filter-ctrl" onchange="this.form.submit()">
                        <option value="">-- Semua Tingkat --</option>
                        @foreach(['Sekolah', 'Kecamatan', 'Kabupaten/Kota', 'Provinsi', 'Nasional', 'Internasional', 'Open Tournament'] as $lvl)
                            <option value="{{ $lvl }}" {{ request('level') == $lvl ? 'selected' : '' }}>
                                {{ $lvl }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 5. Pencarian Keyword Siswa / Prestasi / Penyelenggara --}}
                <div class="filter-field-col search-col">
                    <label class="filter-col-label">
                        <i class="fas fa-search" style="color: #475569;"></i> Cari Siswa / Prestasi
                    </label>
                    <div class="search-input-group">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari nama siswa, prestasi, penyelenggara..." 
                               class="filter-ctrl">
                        <button type="submit" class="btn-filter-go" title="Terapkan Filter">
                            <i class="fas fa-filter"></i> <span>Filter</span>
                        </button>
                        @if($isFiltered)
                        <a href="{{ route('achievements.index') }}" class="btn-reset-filter" style="height: 40px; box-sizing: border-box; display: inline-flex; align-items: center; justify-content: center;" title="Reset Filter">
                            <i class="fas fa-undo"></i>
                        </a>
                        @endif
                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- 3. Data Card (Table on Desktop, Cards on Mobile) --}}
    <div class="table-card">
        @if($achievements->isEmpty())
            <div style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 12px; color: #cbd5e1;"></i>
                @if($isFiltered)
                    <h4 style="font-size: 1.05rem; font-weight: 700; color: #334155; margin-bottom: 6px;">Tidak Ada Prestasi Sesuai Filter</h4>
                    <p style="font-size: 0.84rem; color: #64748b; margin-bottom: 16px;">
                        Tidak ditemukan data prestasi pada kriteria filter tanggal atau bulan yang dipilih.
                    </p>
                    <a href="{{ route('achievements.index') }}" class="btn-act btn-act-blue" style="font-size: 0.82rem; padding: 8px 18px; display: inline-flex;">
                        <i class="fas fa-undo"></i> <span>Tampilkan Semua Prestasi</span>
                    </a>
                @else
                    <h4 style="font-size: 1rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Belum Ada Data Prestasi</h4>
                    <p style="font-size: 0.84rem; color: #94a3b8;">Belum ada catatan kejuaraan atau prestasi siswa dalam sistem.</p>
                @endif
            </div>
        @else
            {{-- A. Desktop Table View (>= 769px) --}}
            <div class="desktop-table-view">
                <table class="achievements-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Siswa / Lembaga</th>
                            <th>Nama Prestasi</th>
                            <th>Tingkat</th>
                            <th>Periode</th>
                            <th>Penyelenggara</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($achievements as $achievement)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: #1e293b;">
                                    {{ \Carbon\Carbon::parse($achievement->date)->translatedFormat('d M Y') }}
                                </div>
                                <div style="font-size: 0.76rem; color: #94a3b8; margin-top: 2px;">
                                    {{ $achievement->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td>
                                @if($achievement->category === 'school' || empty($achievement->student_id))
                                <div>
                                    <span style="font-weight: 800; color: #047857; display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-trophy" style="color: #f59e0b;"></i> {{ $achievement->school_name ?: 'SDIT AN NADZIR' }}
                                    </span>
                                </div>
                                <div style="font-size: 0.76rem; font-weight: 700; color: #b45309; margin-top: 2px;">
                                    <span style="background: #fef3c7; padding: 2px 7px; border-radius: 5px;">Prestasi Sekolah / Lembaga</span>
                                </div>
                                @else
                                <div>
                                    <a href="{{ route('students.show', $achievement->student_id) }}" class="student-link">
                                        {{ $achievement->student->name ?? 'Siswa Dihapus' }}
                                    </a>
                                </div>
                                <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">
                                    Kelas {{ $achievement->student->class ?? '-' }}
                                </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-medal" style="color: #f59e0b;"></i> {{ $achievement->name }}
                                </div>
                                @if($achievement->description)
                                <div style="font-size: 0.78rem; color: #64748b; font-style: italic; margin-top: 3px;">
                                    {{ \Illuminate\Support\Str::limit($achievement->description, 50) }}
                                </div>
                                @endif
                            </td>
                            <td>
                                <span class="level-badge">
                                    <i class="fas fa-award"></i> {{ $achievement->level }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #334155;">
                                    {{ $achievement->academicYear->name ?? '-' }}
                                </div>
                                <div style="font-size: 0.76rem; color: #64748b;">
                                    Semester {{ $achievement->semester ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.84rem; color: #475569;">
                                    {{ $achievement->organizer ?? '-' }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                    <a href="{{ route('achievements.certificate', $achievement->id) }}" target="_blank" class="btn-row-action btn-row-cert" title="Cetak Piagam Digital">
                                        <i class="fas fa-certificate"></i>
                                    </a>
                                    @if(Auth::user()->role == 'admin')
                                    <a href="{{ route('achievements.edit', $achievement->id) }}" class="btn-row-action btn-row-edit" title="Edit Data Prestasi">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('achievements.destroy', $achievement->id) }}" method="POST" onsubmit="return confirm('Hapus data prestasi ini?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-row-action btn-row-delete" title="Hapus Prestasi">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- B. Mobile Card View (< 769px) --}}
            <div class="mobile-achievements-view">
                @foreach($achievements as $achievement)
                <div class="achievement-card">
                    {{-- Header Row: Level Badge & Date --}}
                    <div class="m-ach-header">
                        <span class="level-badge">
                            <i class="fas fa-award"></i> {{ $achievement->level }}
                        </span>
                        <span class="m-ach-date">
                            <i class="fas fa-calendar-alt" style="color: #94a3b8;"></i>
                            {{ \Carbon\Carbon::parse($achievement->date)->translatedFormat('d M Y') }}
                        </span>
                    </div>

                    {{-- Title & Optional Description --}}
                    <div>
                        <h4 class="m-ach-title">
                            <i class="fas fa-medal"></i>
                            <span>{{ $achievement->name }}</span>
                        </h4>
                        @if($achievement->description)
                            <div class="m-ach-desc" style="margin-top: 6px;">
                                "{{ $achievement->description }}"
                            </div>
                        @endif
                    </div>

                    {{-- Student & Metadata Details Box --}}
                    <div class="m-ach-details">
                        <div class="m-ach-student-row">
                            @if($achievement->category === 'school' || empty($achievement->student_id))
                            <div class="m-student-name-box">
                                <i class="fas fa-trophy" style="color: #f59e0b;"></i>
                                <span style="font-weight: 800; color: #047857;">
                                    {{ $achievement->school_name ?: 'SDIT AN NADZIR' }}
                                </span>
                            </div>
                            <span class="m-student-class-badge" style="background: #fef3c7; color: #b45309;">
                                Prestasi Sekolah
                            </span>
                            @else
                            <div class="m-student-name-box">
                                <i class="fas fa-user-graduate" style="color: #0284c7;"></i>
                                <a href="{{ route('students.show', $achievement->student_id) }}" class="student-link">
                                    {{ $achievement->student->name ?? 'Siswa Dihapus' }}
                                </a>
                            </div>
                            <span class="m-student-class-badge">
                                Kelas {{ $achievement->student->class ?? '-' }}
                            </span>
                            @endif
                        </div>

                        <div class="m-ach-meta-row">
                            <div class="m-ach-organizer">
                                <i class="fas fa-building" style="color: #94a3b8;"></i>
                                <span>{{ $achievement->organizer ?? 'Penyelenggara: -' }}</span>
                            </div>
                            <div class="m-ach-period">
                                <i class="fas fa-calendar-check" style="color: #94a3b8;"></i>
                                <span>{{ $achievement->academicYear->name ?? '-' }} (Sem {{ $achievement->semester ?? '-' }})</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions Footer --}}
                    <div class="m-ach-actions">
                        <a href="{{ route('achievements.certificate', $achievement->id) }}" target="_blank" class="m-btn-action m-btn-cert" title="Cetak Piagam Digital">
                            <i class="fas fa-certificate"></i>
                            <span>Piagam</span>
                        </a>
                        @if(Auth::user()->role == 'admin')
                        <a href="{{ route('achievements.edit', $achievement->id) }}" class="m-btn-action m-btn-edit" title="Edit Data Prestasi">
                            <i class="fas fa-edit"></i>
                            <span>Edit</span>
                        </a>
                        <form action="{{ route('achievements.destroy', $achievement->id) }}" method="POST" onsubmit="return confirm('Hapus data prestasi ini?');" style="display: block; width: 100%;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="m-btn-action m-btn-delete" title="Hapus Prestasi">
                                <i class="fas fa-trash"></i>
                                <span>Hapus</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($achievements->hasPages())
            <div class="pagination-wrap" style="padding: 16px 20px; border-top: 1px solid #f1f5f9;">
                {{ $achievements->links() }}
            </div>
            @endif
        @endif
    </div>

</div>
@endsection
@push('scripts')
<script>
    // Handler saat dropdown bulan atau tahun diubah: reset rentang tanggal
    function handleMonthYearChange() {
        const startDate = document.getElementById('filterStartDate');
        const endDate = document.getElementById('filterEndDate');
        if (startDate) startDate.value = '';
        if (endDate) endDate.value = '';

        // Jika bulan dipilih tapi tahun belum dipilih, set tahun ke tahun saat ini
        const monthSelect = document.getElementById('filterMonthSelect');
        const yearSelect = document.getElementById('filterYearSelect');
        if (monthSelect && monthSelect.value && yearSelect && !yearSelect.value) {
            const curYr = new Date().getFullYear();
            for (let i = 0; i < yearSelect.options.length; i++) {
                if (yearSelect.options[i].value == curYr) {
                    yearSelect.selectedIndex = i;
                    break;
                }
            }
        }

        document.getElementById('achievementFilterForm').submit();
    }
</script>
@endpush
