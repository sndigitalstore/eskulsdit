@extends('layouts.app')

@section('title', 'Dashboard Data Siswa - Tata Usaha')
@section('page-title', 'Dashboard Tata Usaha (TU)')

@push('styles')
<style>
    .tu-hero {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);
        color: white;
        padding: 30px 32px;
        border-radius: 20px;
        box-shadow: 0 10px 25px -5px rgba(6, 78, 59, 0.3);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    .tu-hero::after {
        content: '\f501';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: 20px;
        bottom: -20px;
        font-size: 8.5rem;
        color: rgba(255, 255, 255, 0.08);
        pointer-events: none;
    }
    .tu-quick-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }
    .tu-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        color: white;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .tu-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.14);
        color: white;
    }
    .btn-tu-primary   { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .btn-tu-info      { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .btn-tu-purple    { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .btn-tu-amber     { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .btn-tu-dark      { background: linear-gradient(135deg, #475569 0%, #334155 100%); }

    /* KPI STAT CARDS */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }
    .kpi-card {
        background: white;
        border-radius: 16px;
        padding: 22px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    }
    .kpi-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .kpi-icon-wrap {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .kpi-value {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 4px;
    }
    .kpi-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
    }
    .kpi-footer {
        margin-top: 12px;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
        font-size: 0.78rem;
        color: #64748b;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* GRADE GRID */
    .grade-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 14px;
        margin-bottom: 25px;
    }
    .grade-card {
        background: white;
        border-radius: 14px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        transition: border-color 0.2s;
    }
    .grade-card:hover {
        border-color: #10b981;
    }
    .grade-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .grade-count {
        font-size: 1.5rem;
        font-weight: 800;
        color: #065f46;
    }
    .grade-details {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 6px;
        display: flex;
        gap: 10px;
    }

    /* RECENT STUDENTS TABLE */
    .student-row-actions {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .btn-mini {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        color: white;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: transform 0.15s, opacity 0.15s;
    }
    .btn-mini:hover {
        transform: scale(1.08);
        opacity: 0.9;
        color: white;
    }

    @media(max-width: 768px) {
        .tu-hero { flex-direction: column; text-align: left; gap: 15px; }
        .tu-hero::after { display: none; }
    }
</style>
@endpush

@section('content')

{{-- HERO BANNER --}}
<div class="tu-hero">
    <div style="z-index: 2;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
            <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                <i class="fas fa-school"></i> Tata Usaha (TU)
            </span>
            <span style="background: #ecfdf5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800;">
                Tahun Ajaran {{ $activeYear ? $activeYear->name : '-' }}
            </span>
        </div>
        <h2 style="font-size: 1.85rem; font-weight: 800; margin: 0 0 6px; letter-spacing: -0.5px;">
            Dashboard Fokus Data Siswa
        </h2>
        <p style="margin: 0; color: #d1fae5; font-size: 0.95rem;">
            Pusat administrasi, rekapitulasi biodata, dan penerbitan portofolio seluruh siswa SDIT AN NADZIR.
        </p>
    </div>
    <div style="z-index: 2; text-align: right;">
        <div style="font-size: 2rem; font-weight: 800; color: #a7f3d0; font-family: monospace;">
            {{ date('H:i') }} <span style="font-size: 0.9rem; font-family: sans-serif;">WIB</span>
        </div>
        <div style="font-size: 0.85rem; color: #d1fae5; font-weight: 500;">
            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
        </div>
    </div>
</div>

{{-- QUICK ACTION BAR --}}
<div class="tu-quick-actions">
    <a href="{{ route('students.create', ['from' => 'dashboard']) }}" class="tu-action-btn btn-tu-primary">
        <i class="fas fa-user-plus"></i> + Tambah Siswa Baru
    </a>
    <button type="button" onclick="openTuImportModal()" class="tu-action-btn btn-tu-purple" style="border: none; cursor: pointer;">
        <i class="fas fa-file-import"></i> Import Masal Siswa (Excel)
    </button>
    <a href="{{ route('students.index') }}" class="tu-action-btn btn-tu-info">
        <i class="fas fa-users"></i> Kelola Semua Siswa
    </a>
    <a href="{{ route('class-management.index') }}" class="tu-action-btn btn-tu-amber">
        <i class="fas fa-layer-group"></i> Manajemen & Ploting Kelas
    </a>
    <a href="{{ route('students.backup') }}" class="tu-action-btn btn-tu-dark">
        <i class="fas fa-file-excel"></i> Backup / Export Data Excel
    </a>
</div>

{{-- KPI STAT CARDS --}}
<div class="kpi-grid">
    {{-- Total Siswa Aktif --}}
    <div class="kpi-card">
        <div class="kpi-card-top">
            <div>
                <div class="kpi-value">{{ $totalStudents }}</div>
                <div class="kpi-label">Total Siswa Aktif</div>
            </div>
            <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <div class="kpi-footer">
            <span>Alumni / Lulus: <strong>{{ $totalAlumni }}</strong></span>
            <a href="{{ route('students.index', ['status' => 'active']) }}" style="color: #059669; font-weight: 600; text-decoration: none;">Lihat &rarr;</a>
        </div>
    </div>

    {{-- Gender Breakdown --}}
    <div class="kpi-card">
        <div class="kpi-card-top">
            <div>
                <div class="kpi-value" style="font-size: 1.65rem;">
                    <span style="color: #0284c7;">{{ $maleStudents }} L</span>
                    <span style="color: #cbd5e1; font-weight: 300;">/</span>
                    <span style="color: #db2777;">{{ $femaleStudents }} P</span>
                </div>
                <div class="kpi-label">Rasio Jenis Kelamin</div>
            </div>
            <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                <i class="fas fa-venus-mars"></i>
            </div>
        </div>
        <div class="kpi-footer">
            <span>{{ $otherGender > 0 ? $otherGender . ' belum ditentukan' : 'Semua gender terdata' }}</span>
            <span style="color: #64748b;">
                {{ $totalStudents > 0 ? round(($maleStudents / $totalStudents) * 100) . '% L' : '0%' }}
            </span>
        </div>
    </div>

    {{-- Program Sekolah --}}
    <div class="kpi-card">
        <div class="kpi-card-top">
            <div>
                <div class="kpi-value" style="font-size: 1.65rem;">
                    <span style="color: #10b981;">{{ $fullDayStudents }}</span>
                    <span style="color: #cbd5e1; font-weight: 300;">/</span>
                    <span style="color: #8b5cf6;">{{ $boardingStudents }}</span>
                </div>
                <div class="kpi-label">Full Day / Boarding</div>
            </div>
            <div class="kpi-icon-wrap" style="background: #ede9fe; color: #7c3aed;">
                <i class="fas fa-building"></i>
            </div>
        </div>
        <div class="kpi-footer">
            <span>Full Day: <strong>{{ $fullDayStudents }}</strong> • Boarding: <strong>{{ $boardingStudents }}</strong></span>
        </div>
    </div>

    {{-- Kelengkapan NISN --}}
    <div class="kpi-card">
        <div class="kpi-card-top">
            <div>
                <div class="kpi-value" style="color: {{ $missingNisn > 0 ? '#ea580c' : '#059669' }};">
                    {{ $completeNisn }}
                    <span style="font-size: 0.95rem; font-weight: 600; color: #94a3b8;">/ {{ $totalStudents }}</span>
                </div>
                <div class="kpi-label">Kelengkapan NISN</div>
            </div>
            <div class="kpi-icon-wrap" style="background: {{ $missingNisn > 0 ? '#fff7ed' : '#ecfdf5' }}; color: {{ $missingNisn > 0 ? '#ea580c' : '#059669' }};">
                <i class="fas fa-id-card"></i>
            </div>
        </div>
        <div class="kpi-footer">
            @if($missingNisn > 0)
                <span style="color: #ea580c; font-weight: 600;"><i class="fas fa-exclamation-triangle"></i> {{ $missingNisn }} belum ada NISN</span>
            @else
                <span style="color: #059669; font-weight: 600;"><i class="fas fa-check-circle"></i> 100% NISN Lengkap</span>
            @endif
            <span>Data Ortu: <strong>{{ $withParentData }}</strong></span>
        </div>
    </div>
</div>

{{-- ===== SECTION: KELENGKAPAN BIODATA SISWA ===== --}}
<div class="card" style="margin-bottom: 25px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; flex-wrap:wrap; gap:10px; border-bottom:1px solid #f1f5f9; padding-bottom:12px;">
        <div>
            <h3 style="font-size:1.05rem; font-weight:700; color:#1e293b; margin:0 0 2px; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-clipboard-check" style="color:#7c3aed;"></i> Kelengkapan Biodata Siswa
            </h3>
            <p style="margin:0; font-size:0.78rem; color:#64748b;">
                Klik <strong>Ekspor</strong> pada setiap baris untuk mengunduh daftar siswa yang belum terisi.
            </p>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <div style="text-align:center;">
                <div style="font-size:1.6rem; font-weight:800; color:{{ $overallPct >= 80 ? '#059669' : ($overallPct >= 50 ? '#d97706' : '#dc2626') }};">
                    {{ $overallPct }}%
                </div>
                <div style="font-size:0.72rem; color:#64748b; font-weight:600;">Rata-rata kelengkapan</div>
            </div>
            <a href="{{ route('students.export_incomplete', ['filter' => 'all_incomplete']) }}"
               style="display:inline-flex; align-items:center; gap:6px; padding:9px 16px; border-radius:10px; background:linear-gradient(135deg,#dc2626,#b91c1c); color:#fff; text-decoration:none; font-size:0.82rem; font-weight:700; white-space:nowrap;">
                <i class="fas fa-file-excel"></i> Export Semua Tidak Lengkap
            </a>
        </div>
    </div>

    <div style="display:grid; gap:10px;">
        @foreach($biodataStats as $stat)
        @php
            $pct = $stat['pct'];
            $barColor = $pct >= 80 ? '#10b981' : ($pct >= 50 ? '#f59e0b' : '#ef4444');
            $bgBar    = $pct >= 80 ? '#ecfdf5' : ($pct >= 50 ? '#fffbeb' : '#fef2f2');
        @endphp
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:12px 16px; display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
            {{-- Icon --}}
            <div style="width:38px; height:38px; border-radius:10px; background:{{ $bgBar }}; color:{{ $stat['color'] }}; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0;">
                <i class="{{ $stat['icon'] }}"></i>
            </div>
            {{-- Label & Count --}}
            <div style="flex:1; min-width:140px;">
                <div style="font-size:0.85rem; font-weight:700; color:#1e293b;">{{ $stat['label'] }}</div>
                <div style="font-size:0.76rem; color:#64748b;">
                    <span style="color:#059669; font-weight:600;">{{ $stat['filled'] }} terisi</span>
                    @if($stat['missing'] > 0)
                        &nbsp;•&nbsp; <span style="color:#dc2626; font-weight:600;">{{ $stat['missing'] }} belum diisi</span>
                    @else
                        &nbsp;•&nbsp; <span style="color:#059669; font-weight:600;"><i class="fas fa-check-circle"></i> Semua lengkap!</span>
                    @endif
                </div>
            </div>
            {{-- Progress Bar --}}
            <div style="flex:2; min-width:160px;">
                <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                    <span style="font-size:0.72rem; color:#64748b;">Kelengkapan</span>
                    <span style="font-size:0.78rem; font-weight:800; color:{{ $barColor }};">{{ $pct }}%</span>
                </div>
                <div style="background:#e2e8f0; border-radius:99px; height:8px; overflow:hidden;">
                    <div style="width:{{ $pct }}%; height:100%; background:{{ $barColor }}; border-radius:99px; transition:width .6s ease;"></div>
                </div>
            </div>
            {{-- Export Button --}}
            @if($stat['missing'] > 0)
            <a href="{{ route('students.export_incomplete', ['filter' => $stat['filter']]) }}"
               title="Ekspor daftar {{ $stat['missing'] }} siswa yang belum terisi {{ $stat['label'] }}"
               style="display:inline-flex; align-items:center; gap:5px; padding:7px 12px; border-radius:8px; border:1.5px solid {{ $stat['color'] }}; color:{{ $stat['color'] }}; text-decoration:none; font-size:0.76rem; font-weight:700; white-space:nowrap; flex-shrink:0; background:#fff;">
                <i class="fas fa-download"></i> Ekspor ({{ $stat['missing'] }})
            </a>
            @else
            <div style="padding:7px 12px; border-radius:8px; background:#ecfdf5; color:#059669; font-size:0.76rem; font-weight:700; white-space:nowrap; flex-shrink:0;">
                <i class="fas fa-check"></i> Lengkap
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

{{-- BREAKDOWN SISWA PER TINGKAT KELAS --}}
<div class="card" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
        <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-chart-pie" style="color: #10b981;"></i> Distribusi Siswa per Tingkat Kelas
        </h3>
        <span style="font-size: 0.8rem; color: #64748b;">
            Tahun Ajaran: <strong>{{ $activeYear ? $activeYear->name : '-' }}</strong>
        </span>
    </div>

    @if(empty($gradeStatistics))
        <div style="text-align: center; padding: 2rem; color: #94a3b8;">
            Belum ada data rombel kelas pada tahun ajaran ini.
        </div>
    @else
        <div class="grade-grid">
            @foreach($gradeStatistics as $gradeKey => $gData)
            <div class="grade-card">
                <div class="grade-title">
                    <span>Kelas {{ $gradeKey }}</span>
                    <span style="font-size: 0.72rem; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 10px; font-weight: 700;">
                        {{ count($gData['classes']) }} Rombel
                    </span>
                </div>
                <div class="grade-count">{{ $gData['total'] }} <span style="font-size: 0.8rem; font-weight: 500; color: #64748b;">siswa</span></div>
                <div class="grade-details">
                    <span style="color: #0284c7;"><i class="fas fa-male"></i> {{ $gData['male'] }}</span>
                    <span style="color: #db2777;"><i class="fas fa-female"></i> {{ $gData['female'] }}</span>
                </div>
                <div style="margin-top: 8px; display: flex; flex-wrap: wrap; gap: 4px;">
                    @foreach($gData['classes'] as $cls)
                        <span style="font-size: 0.7rem; background: #f1f5f9; color: #334155; padding: 2px 6px; border-radius: 4px; font-weight: 600;" title="{{ $cls['count'] }} siswa ({{ $cls['male'] }}L, {{ $cls['female'] }}P)">
                            {{ $cls['name'] }} ({{ $cls['count'] }})
                        </span>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- DATA SISWA TERBARU & PENCARIAN CEPAT DENGAN CETAK PORTOFOLIO --}}
<div class="card" id="section-students">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0 0 4px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-id-card-alt" style="color: #8b5cf6;"></i> Data Siswa & Cetak Portofolio
            </h3>
            <p style="margin: 0; font-size: 0.82rem; color: #64748b;">
                Kelola biodata, edit, hapus, dan cetak dokumen portofolio serta kartu siswa.
            </p>
        </div>
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <input type="text" id="quickSearchInput" placeholder="Cari nama, NIS, kelas..." style="padding: 8px 14px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem; width: 190px;" onkeyup="filterQuickTable()">
            <button type="button" onclick="openTuImportTracingModal()" style="display:inline-flex; align-items:center; gap:6px; background:#f5f3ff; color:#7c3aed; border:1.5px solid #c4b5fd; padding:8px 12px; font-size:0.84rem; font-weight:700; border-radius:8px; cursor:pointer;" title="Tracing Daftar Siswa Hasil Import Masal">
                <i class="fas fa-search-location"></i> Tracing Import ({{ count($lastImportedStudents ?? []) }})
            </button>
            <a href="{{ route('students.create', ['from' => 'dashboard']) }}" style="display:inline-flex; align-items:center; gap:6px; background:#7c3aed; color:#fff; padding:8px 14px; font-size:0.84rem; font-weight:700; border-radius:8px; text-decoration:none;">
                <i class="fas fa-user-plus"></i> Tambah Siswa
            </a>
            <a href="{{ route('students.index') }}" class="btn-filter" style="background: #065f46; color: white; padding: 8px 14px; font-size: 0.85rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
                Semua Siswa &rarr;
            </a>
        </div>
    </div>

    {{-- Filter Kelas Bar --}}
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 14px; background: #f8fafc; padding: 10px 14px; border-radius: 12px; border: 1px solid #e2e8f0; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 6px; overflow-x: auto; max-width: 100%; padding-bottom: 2px; -webkit-overflow-scrolling: touch;">
            <span style="font-size: 0.78rem; font-weight: 700; color: #475569; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; margin-right: 4px;">
                <i class="fas fa-filter" style="color: #7c3aed;"></i> Filter Kelas:
            </span>
            <a href="{{ route('dashboard') }}#section-students"
               style="padding: 5px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; text-decoration: none; white-space: nowrap; transition: all 0.15s; {{ empty($selectedClass) ? 'background:#7c3aed; color:#fff; box-shadow:0 2px 4px rgba(124,58,237,0.25);' : 'background:#fff; color:#475569; border:1px solid #cbd5e1;' }}">
                Semua
            </a>
            @if(isset($availableClasses))
                @foreach($availableClasses as $cls)
                <a href="{{ route('dashboard', ['class' => $cls]) }}#section-students"
                   style="padding: 5px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; text-decoration: none; white-space: nowrap; transition: all 0.15s; {{ $selectedClass == $cls ? 'background:#7c3aed; color:#fff; box-shadow:0 2px 4px rgba(124,58,237,0.25);' : 'background:#fff; color:#475569; border:1px solid #cbd5e1;' }}">
                    Kelas {{ $cls }}
                </a>
                @endforeach
            @endif
        </div>

        @if(!empty($selectedClass))
        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
            <span style="font-size: 0.78rem; color: #7c3aed; font-weight: 700; background: #ede9fe; padding: 3px 10px; border-radius: 6px;">
                Kelas {{ $selectedClass }} &bull; {{ count($recentStudents) }} siswa
            </span>
            <a href="{{ route('dashboard') }}#section-students" style="color: #ef4444; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 3px;" title="Reset filter">
                <i class="fas fa-times-circle"></i> Reset
            </a>
        </div>
        @else
        <div style="font-size: 0.75rem; color: #64748b; flex-shrink: 0;">
            Menampilkan {{ count($recentStudents) }} siswa terbaru
        </div>
        @endif
    </div>

    <div style="font-size:0.75rem; color:#64748b; margin-bottom:8px; display:flex; align-items:center; gap:5px;">
        <i class="fas fa-info-circle" style="color:#7c3aed;"></i>
        <span>Geser tabel ke kanan/kiri di layar HP untuk melihat seluruh kolom data dan aksi siswa.</span>
    </div>

    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; touch-action: pan-x pan-y;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; min-width: 820px;" id="tuStudentsTable">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                    <th style="padding: 10px 12px; width: 45px;">No</th>
                    <th style="padding: 10px 12px;">Nama Siswa</th>
                    <th style="padding: 10px 12px;">NIS / NISN</th>
                    <th style="padding: 10px 12px;">Kelas</th>
                    <th style="padding: 10px 12px;">Gender</th>
                    <th style="padding: 10px 12px;">Program</th>
                    <th style="padding: 10px 12px;">Kontak Ortu</th>
                    <th style="padding: 10px 12px; text-align: center; width: 210px;">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentStudents as $idx => $s)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background='white'">
                    <td style="padding: 10px 12px; color: #64748b;">{{ $idx + 1 }}</td>
                    <td style="padding: 10px 12px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 34px; height: 34px; border-radius: 50%; background: #e2e8f0; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.85rem; color: #64748b; font-weight: 700;">
                                @if($s->photo)
                                    <img src="{{ asset('storage/' . $s->photo) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    {{ strtoupper(substr($s->name, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <strong style="color: #1e293b; display: block;">{{ $s->name }}</strong>
                                <span style="font-size: 0.75rem; color: #94a3b8;">
                                    {{ $s->birth_place_date ?: 'TTL belum diisi' }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 10px 12px;">
                        <span style="font-weight: 600; color: #334155;">{{ $s->nis }}</span>
                        @if($s->nisn)
                            <div style="font-size: 0.75rem; color: #64748b;">NISN: {{ $s->nisn }}</div>
                        @else
                            <div style="font-size: 0.72rem; color: #f59e0b;">(NISN -)</div>
                        @endif
                    </td>
                    <td style="padding: 10px 12px;">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: 0.8rem;">
                            {{ $s->class }}
                        </span>
                    </td>
                    <td style="padding: 10px 12px;">
                        @if($s->gender == 'L')
                            <span style="color: #0284c7; font-weight: 700;">L</span>
                        @elseif($s->gender == 'P')
                            <span style="color: #db2777; font-weight: 700;">P</span>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                    <td style="padding: 10px 12px;">
                        <span style="font-size: 0.78rem; font-weight: 600; color: {{ $s->school_program == 'Boarding' ? '#7c3aed' : '#059669' }};">
                            {{ $s->school_program ?: 'Reguler' }}
                        </span>
                    </td>
                    <td style="padding: 10px 12px;">
                        @php
                            $phone = $s->father_phone ?: ($s->mother_phone ?: $s->parent_phone);
                        @endphp
                        @if($phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}" target="_blank" style="color: #16a34a; text-decoration: none; font-size: 0.82rem; font-weight: 600;">
                                <i class="fab fa-whatsapp"></i> {{ $phone }}
                            </a>
                        @else
                            <span style="color: #cbd5e1; font-size: 0.8rem;">-</span>
                        @endif
                    </td>
                    <td style="padding: 10px 12px; text-align: center;">
                        <div class="student-row-actions" style="justify-content: center; gap: 4px;">
                            {{-- Lihat Biodata --}}
                            <a href="{{ route('students.show', ['student' => $s->id, 'from' => 'dashboard']) }}" class="btn-mini" style="background: #64748b;" title="Lihat Detail Biodata">
                                <i class="fas fa-eye"></i>
                            </a>
                            {{-- Edit --}}
                            <a href="{{ route('students.edit', ['student' => $s->id, 'from' => 'dashboard']) }}" class="btn-mini" style="background: #f59e0b;" title="Edit Data Siswa">
                                <i class="fas fa-pen"></i>
                            </a>
                            {{-- Hapus --}}
                            <button type="button" onclick="confirmDeleteTuStudent('{{ route('students.destroy', $s->id) }}', '{{ addslashes($s->name) }}')" class="btn-mini" style="background: #ef4444; border:none; cursor:pointer;" title="Hapus Siswa">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            {{-- Cetak Portofolio --}}
                            <a href="{{ route('students.portfolio', ['student' => $s->id, 'from' => 'dashboard']) }}" class="btn-mini" style="background: #8b5cf6;" title="Cetak Portofolio Lengkap Siap Cetak">
                                <i class="fas fa-id-card-alt"></i>
                            </a>
                            {{-- Cetak Kartu --}}
                            <a href="{{ route('students.card', ['student' => $s->id, 'from' => 'dashboard']) }}" class="btn-mini" style="background: #0284c7;" title="Cetak Kartu Siswa">
                                <i class="fas fa-id-card"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 2rem; color: #94a3b8;">
                        @if(!empty($selectedClass))
                            Tidak ada siswa aktif di Kelas {{ $selectedClass }}.
                        @else
                            Belum ada data siswa terdaftar di tahun ajaran ini.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL IMPORT MASAL DATA SISWA (TU) --}}
<div id="tuImportModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,0.55); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:16px;">
    <div style="background:#fff; border-radius:20px; width:100%; max-width:580px; box-shadow:0 24px 48px rgba(15,23,42,0.22); overflow:hidden; animation:fadeInScale .2s ease;">
        <div style="background:linear-gradient(135deg,#6d28d9,#7c3aed); color:#fff; padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:12px; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; font-size:1.15rem;">
                    <i class="fas fa-file-import"></i>
                </div>
                <div>
                    <h3 style="margin:0; font-size:1.1rem; font-weight:700;">Import Masal Data Siswa (Excel)</h3>
                    <p style="margin:2px 0 0; font-size:0.78rem; color:#ede9fe;">Mendukung multi-sheet per kelas (kls IA, kls IB, dst.)</p>
                </div>
            </div>
            <button type="button" onclick="closeTuImportModal()" style="background:rgba(255,255,255,0.18); border:none; color:#fff; width:34px; height:34px; border-radius:10px; cursor:pointer; font-size:1rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="tuImportForm" action="{{ route('students.import_excel') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateTuImport(event)" style="padding:24px;">
            @csrf
            <input type="hidden" name="redirect_to" value="dashboard">

            <div style="background:#f5f3ff; border:1px solid #ddd6fe; border-radius:12px; padding:12px 14px; margin-bottom:16px; font-size:0.82rem; color:#4c1d95; line-height:1.5;">
                <strong><i class="fas fa-magic"></i> Deteksi Otomatis Format Sekolah:</strong><br>
                File Excel format sekolah (kolom <em>NIS, NISN, Nama Lengkap Siswa, L/P, Tempat dan Tanggal Lahir, Alamat, Asal Sekolah, Nama Orang Tua [Ayah/Ibu], Pekerjaan [Ayah/Ibu], No. Telp/HP [Ayah/Ibu], Tgl diterima, Full Day/Boarding</em>) langsung terbaca otomatis termasuk seluruh tab sheet kelas.
            </div>

            <div id="tuDropZone" onclick="document.getElementById('tuImportFile').click()"
                 ondragover="event.preventDefault(); this.style.borderColor='#7c3aed'; this.style.background='#f5f3ff';"
                 ondragleave="this.style.borderColor='#cbd5e1'; this.style.background='#f8fafc';"
                 ondrop="handleTuDrop(event)"
                 style="border:2px dashed #cbd5e1; background:#f8fafc; border-radius:14px; padding:26px 16px; text-align:center; cursor:pointer; transition:all .2s; margin-bottom:16px;">
                <i class="fas fa-cloud-upload-alt" style="font-size:2.2rem; color:#7c3aed; margin-bottom:8px;"></i>
                <div style="font-weight:700; color:#1e293b; font-size:0.95rem;">Klik atau seret file Excel ke sini</div>
                <div style="font-size:0.78rem; color:#64748b; margin-top:4px;">Format didukung: .xlsx, .xls, .csv</div>
                <div id="tuFileNameLabel" style="margin-top:10px; font-size:0.85rem; font-weight:700; color:#059669; display:none;"></div>
                <input type="file" id="tuImportFile" name="file" accept=".xlsx,.xls,.csv" style="position:absolute; width:1px; height:1px; opacity:0; pointer-events:none; left:-9999px;" onchange="updateTuFileName(this)">
            </div>

            <div style="margin-bottom:18px;">
                <div style="font-size:0.82rem; font-weight:700; color:#334155; margin-bottom:8px;">Mode Sinkronisasi Jika Nama / NIS Anak Sudah Ada:</div>
                <label style="display:flex; align-items:flex-start; gap:10px; padding:10px 12px; border:1.5px solid #c4b5fd; background:#faf5ff; border-radius:10px; cursor:pointer; margin-bottom:8px;">
                    <input type="radio" name="update_mode" value="fill_empty" checked style="margin-top:3px; accent-color:#7c3aed;">
                    <div>
                        <div style="font-size:0.84rem; font-weight:700; color:#4c1d95;">Lengkapi Data yang Belum Terisi (Rekomendasi)</div>
                        <div style="font-size:0.76rem; color:#6b7280;">Otomatis melengkapi kolom kosong (misal: Jenis Kelamin, Nama Orang Tua, Alamat, NISN, Full Day/Boarding) tanpa menimpa data yang sudah terisi.</div>
                    </div>
                </label>
                <label style="display:flex; align-items:flex-start; gap:10px; padding:10px 12px; border:1px solid #e2e8f0; background:#fff; border-radius:10px; cursor:pointer;">
                    <input type="radio" name="update_mode" value="overwrite" style="margin-top:3px; accent-color:#7c3aed;">
                    <div>
                        <div style="font-size:0.84rem; font-weight:700; color:#334155;">Perbarui / Timpa Semua Kolom dari Excel</div>
                        <div style="font-size:0.76rem; color:#6b7280;">Seluruh kolom biodata yang terisi di file Excel akan menimpa data lama siswa yang cocok.</div>
                    </div>
                </label>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; border-top:1px solid #f1f5f9; padding-top:16px;">
                <a href="{{ route('students.import_template') }}" style="display:inline-flex; align-items:center; gap:6px; padding:9px 14px; border-radius:10px; border:1.5px solid #7c3aed; color:#7c3aed; text-decoration:none; font-size:0.82rem; font-weight:600;">
                    <i class="fas fa-download"></i> Unduh Template Excel
                </a>
                <div style="display:flex; gap:8px;">
                    <button type="button" onclick="closeTuImportModal()" style="padding:9px 16px; border-radius:10px; border:1px solid #cbd5e1; background:#fff; color:#475569; font-weight:600; font-size:0.85rem; cursor:pointer;">Batal</button>
                    <button type="submit" id="tuBtnSubmitImport" style="padding:9px 18px; border-radius:10px; border:none; background:linear-gradient(135deg,#7c3aed,#6d28d9); color:#fff; font-weight:700; font-size:0.85rem; cursor:pointer; display:inline-flex; align-items:center; gap:8px;">
                        <i class="fas fa-upload"></i> Upload &amp; Proses Import
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TRACING HASIL IMPORT MASAL SISWA --}}
<div id="tuImportTracingModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.65); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#fff; border-radius:20px; max-width:900px; width:100%; max-height:90vh; display:flex; flex-direction:column; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); overflow:hidden;">
        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#7c3aed,#4c1d95); padding:18px 24px; color:#fff; display:flex; justify-content:space-between; align-items:center;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:12px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                    <i class="fas fa-search-location"></i>
                </div>
                <div>
                    <h3 style="margin:0; font-size:1.1rem; font-weight:700;">Tracing Siswa Hasil Import Masal</h3>
                    <p style="margin:2px 0 0; font-size:0.78rem; color:#ddd6fe;">
                        {{ !empty($lastImportBatch['file']) ? 'File: ' . $lastImportBatch['file'] . ' (' . ($lastImportBatch['time'] ?? '') . ')' : 'Daftar riwayat siswa baru & biodata yang baru diperbarui' }}
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeTuImportTracingModal()" style="background:rgba(255,255,255,0.2); border:none; color:#fff; width:34px; height:34px; border-radius:10px; cursor:pointer; font-size:1rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Filter & Summary Bar --}}
        <div style="padding:12px 24px; background:#f8fafc; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                @if(!empty($lastImportBatch))
                    <span style="font-size:0.78rem; background:#ecfdf5; color:#065f46; font-weight:700; padding:4px 10px; border-radius:20px; border:1px solid #a7f3d0;">
                        <i class="fas fa-user-plus"></i> {{ $lastImportBatch['created'] }} Siswa Baru
                    </span>
                    <span style="font-size:0.78rem; background:#f5f3ff; color:#6d28d9; font-weight:700; padding:4px 10px; border-radius:20px; border:1px solid #ddd6fe;">
                        <i class="fas fa-check-double"></i> {{ $lastImportBatch['updated'] }} Dilengkapi
                    </span>
                    @if(!empty($lastImportBatch['unchanged']))
                    <span style="font-size:0.78rem; background:#f1f5f9; color:#475569; font-weight:700; padding:4px 10px; border-radius:20px; border:1px solid #cbd5e1;">
                        <i class="fas fa-check"></i> {{ $lastImportBatch['unchanged'] }} Sudah Lengkap
                    </span>
                    @endif
                @else
                    <span style="font-size:0.78rem; color:#64748b; font-weight:600;">
                        Menampilkan {{ count($lastImportedStudents ?? []) }} data siswa terbaru di sistem
                    </span>
                @endif
            </div>
            <div>
                <input type="text" id="tracingSearchInput" placeholder="Filter tracing nama / kelas..." onkeyup="filterTracingTable()" style="padding:6px 12px; border:1.5px solid #cbd5e1; border-radius:8px; font-size:0.82rem; width:220px;">
            </div>
        </div>

        {{-- Table Container --}}
        <div style="flex:1; overflow-y:auto; overflow-x:auto; padding:16px 24px; -webkit-overflow-scrolling:touch;">
            <table style="width:100%; border-collapse:collapse; font-size:0.85rem; min-width:700px;" id="tuTracingTable">
                <thead>
                    <tr style="background:#f1f5f9; border-bottom:2px solid #e2e8f0; text-align:left;">
                        <th style="padding:8px 10px; width:40px;">No</th>
                        <th style="padding:8px 10px;">Nama Siswa</th>
                        <th style="padding:8px 10px;">NIS / NISN</th>
                        <th style="padding:8px 10px;">Kelas</th>
                        <th style="padding:8px 10px;">Status</th>
                        <th style="padding:8px 10px;">Keterangan</th>
                        <th style="padding:8px 10px; text-align:center; width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lastImportedStudents ?? [] as $tIdx => $tStu)
                    @php
                        $badgeBg = '#ecfdf5'; $badgeColor = '#059669';
                        if (($tStu['status'] ?? '') === 'Dilengkapi') {
                            $badgeBg = '#f5f3ff'; $badgeColor = '#7c3aed';
                        } elseif (($tStu['status'] ?? '') === 'Sudah Lengkap') {
                            $badgeBg = '#f1f5f9'; $badgeColor = '#475569';
                        }
                    @endphp
                    <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background='white'">
                        <td style="padding:8px 10px; color:#64748b;">{{ $tIdx + 1 }}</td>
                        <td style="padding:8px 10px; font-weight:700; color:#1e293b;">
                            {{ $tStu['name'] }}
                            @if(!empty($tStu['gender']) && $tStu['gender'] !== '-')
                                <span style="font-size:0.75rem; color:{{ $tStu['gender'] == 'L' ? '#0284c7' : '#db2777' }};">({{ $tStu['gender'] }})</span>
                            @endif
                        </td>
                        <td style="padding:8px 10px;">
                            <span style="font-weight:600; color:#334155;">{{ $tStu['nis'] }}</span>
                            @if(!empty($tStu['nisn']) && $tStu['nisn'] !== '-')
                                <div style="font-size:0.72rem; color:#64748b;">NISN: {{ $tStu['nisn'] }}</div>
                            @endif
                        </td>
                        <td style="padding:8px 10px;">
                            <span style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:5px; font-weight:700; font-size:0.78rem;">
                                {{ $tStu['class'] }}
                            </span>
                        </td>
                        <td style="padding:8px 10px;">
                            <span style="background:{{ $badgeBg }}; color:{{ $badgeColor }}; font-weight:700; font-size:0.75rem; padding:3px 8px; border-radius:6px; white-space:nowrap;">
                                {{ $tStu['status'] }}
                            </span>
                        </td>
                        <td style="padding:8px 10px; font-size:0.78rem; color:#64748b;">
                            {{ $tStu['details'] }}
                        </td>
                        <td style="padding:8px 10px; text-align:center;">
                            <div style="display:flex; gap:4px; justify-content:center;">
                                <a href="{{ route('students.show', ['student' => $tStu['id'], 'from' => 'dashboard']) }}" class="btn-mini" style="background:#64748b;" title="Lihat Profil">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', ['student' => $tStu['id'], 'from' => 'dashboard']) }}" class="btn-mini" style="background:#f59e0b;" title="Edit Biodata">
                                    <i class="fas fa-pen"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:2rem; color:#94a3b8;">
                            Belum ada riwayat tracing import siswa yang tersimpan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div style="padding:14px 24px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end;">
            <button type="button" onclick="closeTuImportTracingModal()" style="padding:8px 20px; border-radius:10px; border:1px solid #cbd5e1; background:#fff; color:#475569; font-weight:600; cursor:pointer;">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openTuImportModal() {
    document.getElementById('tuImportModal').style.display = 'flex';
}
function closeTuImportModal() {
    document.getElementById('tuImportModal').style.display = 'none';
}
function updateTuFileName(input) {
    const label = document.getElementById('tuFileNameLabel');
    if (input.files && input.files.length > 0) {
        label.textContent = 'File dipilih: ' + input.files[0].name;
        label.style.display = 'block';
    } else {
        label.style.display = 'none';
    }
}
function handleTuDrop(e) {
    e.preventDefault();
    const zone = document.getElementById('tuDropZone');
    zone.style.borderColor = '#cbd5e1';
    zone.style.background = '#f8fafc';
    const files = e.dataTransfer.files;
    if (files && files.length > 0) {
        const input = document.getElementById('tuImportFile');
        try {
            const dt = new DataTransfer();
            for (let i = 0; i < files.length; i++) {
                dt.items.add(files[i]);
            }
            input.files = dt.files;
        } catch (err) {
            input.files = files;
        }
        updateTuFileName(input);
    }
}
function validateTuImport(e) {
    const fileInput = document.getElementById('tuImportFile');
    if (!fileInput.files || fileInput.files.length === 0) {
        e.preventDefault();
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'File Belum Dipilih',
                text: 'Silakan pilih atau seret file Excel (.xlsx, .xls) data siswa terlebih dahulu.',
                confirmButtonColor: '#7c3aed'
            });
        } else {
            alert('Silakan pilih file Excel terlebih dahulu.');
        }
        return false;
    }
    const btn = document.getElementById('tuBtnSubmitImport');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses Import Data...';
        btn.style.opacity = '0.75';
        btn.style.cursor = 'not-allowed';
    }
    return true;
}
function openTuImportTracingModal() {
    document.getElementById('tuImportTracingModal').style.display = 'flex';
}
function closeTuImportTracingModal() {
    document.getElementById('tuImportTracingModal').style.display = 'none';
}
document.getElementById('tuImportTracingModal').addEventListener('click', function(e) {
    if (e.target === this) closeTuImportTracingModal();
});
function filterTracingTable() {
    var input = document.getElementById("tracingSearchInput");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("tuTracingTable");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
        var tdName = tr[i].getElementsByTagName("td")[1];
        var tdNis = tr[i].getElementsByTagName("td")[2];
        var tdClass = tr[i].getElementsByTagName("td")[3];
        var tdStatus = tr[i].getElementsByTagName("td")[4];
        if (tdName || tdNis || tdClass || tdStatus) {
            var txtValue = (tdName ? tdName.textContent : "") + " " +
                           (tdNis ? tdNis.textContent : "") + " " +
                           (tdClass ? tdClass.textContent : "") + " " +
                           (tdStatus ? tdStatus.textContent : "");
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
function confirmDeleteTuStudent(deleteUrl, studentName) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hapus Siswa?',
            html: `Apakah Anda yakin ingin menghapus data siswa <strong>${studentName}</strong>?<br><small style="color:#ef4444; margin-top:4px; display:block;">Semua riwayat nilai, absensi, dan portofolio anak ini akan ikut terhapus.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus Siswa',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="from" value="dashboard">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    } else {
        if (confirm(`Apakah Anda yakin ingin menghapus siswa ${studentName}?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="from" value="dashboard">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
}
document.getElementById('tuImportModal').addEventListener('click', function(e) {
    if (e.target === this) closeTuImportModal();
});
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Import Data Siswa Berhasil',
                html: {!! json_encode(session('success')) !!},
                showDenyButton: true,
                confirmButtonText: 'Selesai',
                denyButtonText: '<i class="fas fa-search-location"></i> Buka Tracing Siswa',
                confirmButtonColor: '#7c3aed',
                denyButtonColor: '#059669'
            }).then((res) => {
                if (res.isDenied) {
                    openTuImportTracingModal();
                }
            });
        }
    @endif

    @if(session('error'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memproses Data',
                html: {!! json_encode(session('error')) !!},
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#ef4444'
            });
        }
    @endif

    @if(session('warning_html'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Catatan Import Siswa',
                html: {!! json_encode(session('warning_html')) !!},
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#f59e0b'
            });
        }
    @endif
});
function filterQuickTable() {
    var input = document.getElementById("quickSearchInput");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("tuStudentsTable");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
        var tdName = tr[i].getElementsByTagName("td")[1];
        var tdNis = tr[i].getElementsByTagName("td")[2];
        var tdClass = tr[i].getElementsByTagName("td")[3];
        if (tdName || tdNis || tdClass) {
            var txtValue = (tdName ? tdName.textContent : "") + " " +
                           (tdNis ? tdNis.textContent : "") + " " +
                           (tdClass ? tdClass.textContent : "");
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>
@endpush

@endsection
