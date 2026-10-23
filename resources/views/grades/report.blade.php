@extends('layouts.app')

@section('title', 'Rekapitulasi Penilaian - ' . ($selectedEskul->name ?? 'Eskul'))
@section('page-title', 'Rekapitulasi Penilaian')

@push('styles')
<style>
    /* ========================================================
       REKAPITULASI PENILAIAN - MODERN & FULLY RESPONSIVE SYSTEM
       ======================================================== */
    .recap-grades-container {
        max-width: 1100px;
        margin: 0 auto;
        padding-bottom: 2rem;
    }

    /* Top Navigation Bar */
    .top-nav-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .btn-nav-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #334155;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 8px 18px;
        border-radius: 999px;
        font-size: 0.86rem;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-nav-back:hover {
        background: #f8fafc;
        color: #7c3aed;
        border-color: #cbd5e1;
        transform: translateX(-3px);
    }

    .btn-print-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        border: none;
        padding: 9px 20px;
        border-radius: 999px;
        font-size: 0.88rem;
        font-weight: 750;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
    }

    .btn-print-action:hover {
        background: #0f172a;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.25);
    }

    /* Hero Banner */
    .recap-hero-card {
        background: linear-gradient(135deg, #ffffff 0%, #f5f3ff 100%);
        border: 1.5px solid #ddd6fe;
        border-radius: 20px;
        padding: 1.6rem 1.8rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 8px 25px -5px rgba(124, 58, 237, 0.08);
        position: relative;
        overflow: hidden;
    }

    .recap-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f5f3ff;
        color: #7c3aed;
        border: 1px solid #ddd6fe;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .recap-hero-title {
        font-size: 1.45rem;
        font-weight: 900;
        color: #4c1d95;
        letter-spacing: -0.02em;
        margin: 0 0 6px 0;
        line-height: 1.25;
    }

    .recap-hero-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #475569;
        font-size: 0.88rem;
        font-weight: 600;
        flex-wrap: wrap;
    }

    .recap-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* KPI Summary Cards */
    .kpi-grades-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 1.25rem;
    }

    .kpi-grades-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.1rem 1.2rem;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .kpi-grades-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
    }

    .kpi-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .kpi-val {
        font-size: 1.35rem;
        font-weight: 900;
        line-height: 1.15;
        letter-spacing: -0.02em;
    }

    .kpi-lbl {
        font-size: 0.74rem;
        color: #64748b;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-top: 2px;
    }

    .kpi-students .kpi-icon-box { background: #ede9fe; color: #7c3aed; }
    .kpi-students .kpi-val { color: #7c3aed; }

    .kpi-category .kpi-icon-box { background: #e0f2fe; color: #0284c7; }
    .kpi-category .kpi-val { color: #0284c7; }

    .kpi-format .kpi-icon-box { background: #fef3c7; color: #d97706; }
    .kpi-format .kpi-val { color: #d97706; }

    /* Main Table & Card Container */
    .recap-content-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    }

    .card-header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 10px;
    }

    .card-title-text {
        font-size: 1.15rem;
        font-weight: 850;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    /* Desktop Table */
    .grades-desktop-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }

    .grades-desktop-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 800;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 11px 12px;
        border-top: 1px solid #e2e8f0;
        border-bottom: 2px solid #cbd5e1;
        text-align: center;
    }

    .grades-desktop-table th.text-left { text-align: left; }

    .grades-desktop-table td {
        padding: 12px 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #334155;
        text-align: center;
        vertical-align: middle;
        background: #ffffff;
    }

    .grades-desktop-table tr:hover td {
        background: #f8fafc;
    }

    .grades-desktop-table td.text-left { text-align: left; }

    .score-badge-cell {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        padding: 4px 8px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 0.88rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #0f172a;
    }

    .score-badge-cell.has-val {
        background: #f5f3ff;
        border-color: #ddd6fe;
        color: #6d28d9;
    }

    /* ========================================================
       MOBILE-FIRST SYMMETRICAL RECAP CARDS (HP ONLY)
       ======================================================== */
    .grades-mobile-cards {
        display: none;
    }

    .student-grade-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.15rem;
        margin-bottom: 12px;
        box-shadow: 0 3px 10px rgba(15, 23, 42, 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .student-grade-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
    }

    /* Top Row */
    .grade-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .grade-id-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        flex: 1;
    }

    .grade-idx-num {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f3e8ff 0%, #ede9fe 100%);
        color: #7c3aed;
        font-size: 0.8rem;
        font-weight: 850;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid #ddd6fe;
    }

    .grade-name-box {
        min-width: 0;
        flex: 1;
    }

    .grade-name-text {
        font-size: 0.95rem;
        font-weight: 850;
        color: #0f172a;
        line-height: 1.3;
        display: block;
        word-break: break-word;
    }

    .grade-class-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.72rem;
        font-weight: 750;
        color: #64748b;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 6px;
        margin-top: 3px;
    }

    /* Symmetrical 3-Column Micro Scores Grid on Mobile (Umum) */
    .grade-scores-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        background: #faf5ff;
        border: 1px solid #ede9fe;
        border-radius: 14px;
        padding: 10px 8px;
    }

    .score-micro-box {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .score-micro-num {
        font-size: 1.15rem;
        font-weight: 900;
        color: #6d28d9;
        line-height: 1.15;
    }

    .score-micro-lbl {
        font-size: 0.68rem;
        font-weight: 800;
        color: #7c3aed;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        margin-top: 3px;
    }

    .score-micro-sub {
        font-size: 0.65rem;
        color: #94a3b8;
        font-weight: 600;
    }

    /* Calistung Specific Card View */
    .calistung-aspect-card {
        background: #faf5ff;
        border: 1px solid #ede9fe;
        border-radius: 12px;
        padding: 8px 10px;
        margin-bottom: 6px;
    }

    .calistung-aspect-card:last-child {
        margin-bottom: 0;
    }

    .aspect-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }

    .aspect-title {
        font-size: 0.78rem;
        font-weight: 800;
        color: #5b21b6;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .aspect-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
    }

    .aspect-stat-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 4px 6px;
        text-align: center;
    }

    .aspect-stat-val {
        font-size: 0.92rem;
        font-weight: 900;
        color: #0f172a;
    }

    .aspect-stat-lbl {
        font-size: 0.62rem;
        color: #64748b;
        font-weight: 750;
        text-transform: uppercase;
    }

    /* Print Styles */
    @media print {
        body { background: white !important; }
        .sidebar, .header, .btn-print-action, .top-nav-bar, .no-print { display: none !important; }
        .recap-grades-container, .recap-content-card, .recap-hero-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }
        .grades-desktop-table { display: table !important; }
        .grades-mobile-cards { display: none !important; }
        .grades-desktop-table th, .grades-desktop-table td {
            border: 1px solid #bbb !important;
            padding: 8px !important;
            font-size: 9pt !important;
        }
    }

    /* Mobile Responsive Breakpoints */
    @media (max-width: 768px) {
        .recap-grades-container { padding: 0 4px 2rem 4px; }
        .top-nav-bar { flex-direction: column; align-items: stretch; gap: 8px; }
        .btn-nav-back, .btn-print-action { justify-content: center; width: 100%; }

        .recap-hero-card { padding: 1.25rem 1rem; border-radius: 16px; }
        .recap-hero-title { font-size: 1.2rem; }

        .kpi-grades-grid {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 6px !important;
        }
        .kpi-grades-card {
            padding: 0.75rem 0.6rem !important;
            gap: 6px !important;
            border-radius: 14px !important;
            flex-direction: column !important;
            text-align: center !important;
        }
        .kpi-icon-box {
            width: 32px !important;
            height: 32px !important;
            font-size: 0.9rem !important;
            border-radius: 8px !important;
            margin: 0 auto;
        }
        .kpi-val { font-size: 1.1rem !important; }
        .kpi-lbl { font-size: 0.65rem !important; }

        .recap-content-card { padding: 1rem !important; border-radius: 16px !important; }
        .grades-desktop-table { display: none !important; }
        .grades-mobile-cards { display: block !important; }
    }
</style>
@endpush

@section('content')
<div class="recap-grades-container">

    <!-- 1. Top Navigation Bar (No Print) -->
    <div class="top-nav-bar no-print">
        @if(Auth::user()->role === 'teacher')
        <a href="{{ route('grades.create', ['eskul_id' => $selectedEskul->id ?? Auth::user()->eskul_id, 'type' => 'daily', 'date' => date('Y-m-d')]) }}" class="btn-nav-back">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Lembar Penilaian</span>
        </a>
        @else
        <a href="{{ route('grades.index') }}" class="btn-nav-back">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Pemilihan Penilaian</span>
        </a>
        @endif

        <button onclick="window.print()" class="btn-print-action">
            <i class="fas fa-print"></i>
            <span>Cetak Rekap Nilai</span>
        </button>
    </div>

    <!-- 2. Hero Header Banner -->
    <div class="recap-hero-card">
        <div class="recap-hero-badge">
            <i class="fas fa-file-invoice"></i> Rekapitulasi Penilaian Siswa
        </div>
        <h1 class="recap-hero-title">{{ $selectedEskul->name ?? '-' }}</h1>
        <div class="recap-hero-meta">
            @if(!empty($selectedEskul->instructor_name))
            <div class="recap-meta-item">
                <i class="fas fa-chalkboard-teacher" style="color: #7c3aed;"></i>
                <span>Pembina: <strong>{{ $selectedEskul->instructor_name }}</strong></span>
            </div>
            @endif
            <div class="recap-meta-item">
                <i class="fas fa-shapes" style="color: #0284c7;"></i>
                <span>Tipe: <strong>{{ ($selectedEskul->is_lockable ?? false) ? 'Calistung (Kelas 1)' : 'Ekstrakurikuler Reguler' }}</strong></span>
            </div>
        </div>
    </div>

    <!-- 3. KPI Summary Grid -->
    <div class="kpi-grades-grid no-print">
        <div class="kpi-grades-card kpi-students">
            <div class="kpi-icon-box">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="kpi-val">{{ count($students) }}</div>
                <div class="kpi-lbl">Total Siswa</div>
            </div>
        </div>

        <div class="kpi-grades-card kpi-category">
            <div class="kpi-icon-box">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <div class="kpi-val">{{ ($selectedEskul->is_lockable ?? false) ? 'Calistung' : 'Reguler' }}</div>
                <div class="kpi-lbl">Model Nilai</div>
            </div>
        </div>

        <div class="kpi-grades-card kpi-format">
            <div class="kpi-icon-box">
                <i class="fas fa-award"></i>
            </div>
            <div>
                <div class="kpi-val">3 Komponen</div>
                <div class="kpi-lbl">Harian, SAS 1 & 2</div>
            </div>
        </div>
    </div>

    <!-- 4. Content Card (Desktop Table + Mobile Symmetrical Cards) -->
    <div class="recap-content-card">
        <div class="card-header-bar">
            <h3 class="card-title-text">
                <i class="fas fa-award" style="color: #7c3aed;"></i>
                <span>Daftar Nilai Siswa</span>
            </h3>
            <span style="font-size: 0.8rem; font-weight: 700; color: #64748b;">
                Total <strong>{{ count($students) }}</strong> Siswa
            </span>
        </div>

        @if($selectedEskul)
            <!-- A. Desktop View Table (Screens > 768px & Print) -->
            <table class="grades-desktop-table">
                <thead>
                    <tr>
                        <th rowspan="2" width="6%">No</th>
                        <th rowspan="2" class="text-left" width="34%">Nama Siswa</th>
                        <th rowspan="2" width="12%">Kelas</th>
                        <th rowspan="2" width="14%">Materi</th>
                        <th colspan="3" width="34%">Penilaian</th>
                    </tr>
                    <tr>
                        <th width="12%">Harian</th>
                        <th width="11%">SAS 1</th>
                        <th width="11%">SAS 2</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $isCalistung = $selectedEskul->is_lockable ?? false;
                    @endphp

                    @forelse($students as $index => $student)
                    @php
                        $studentGrades = $grades[$student->id] ?? collect([]);
                        
                        $dailyGrades = $studentGrades->where('type', 'daily');
                        $sas1Raw = $studentGrades->where('type', 'sas1')->first()->score ?? null;
                        $sas2Raw = $studentGrades->where('type', 'sas2')->first()->score ?? null;
                        
                        $dailyScore = '-';
                        $dailyJson = null;
                        if ($dailyGrades->isNotEmpty()) {
                            $latest = $dailyGrades->sortByDesc('date')->first();
                            $dailyScore = $latest->score;
                            $dailyJson = json_decode($dailyScore, true);
                        }
                        
                        $sas1Json = json_decode($sas1Raw, true);
                        $sas2Json = json_decode($sas2Raw, true);

                        $isStudentCalistung = $isCalistung && \Illuminate\Support\Str::startsWith($student->class, '1');
                    @endphp

                    @if($isStudentCalistung)
                        <!-- Row for Membaca -->
                        <tr>
                            <td rowspan="3" style="font-weight: 800; color: #64748b;">{{ $index + 1 }}</td>
                            <td rowspan="3" class="text-left">
                                <strong style="color: #0f172a; font-size: 0.92rem;">{{ $student->name }}</strong>
                            </td>
                            <td rowspan="3">
                                <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 8px; font-weight: 750; font-size: 0.82rem;">
                                    {{ $student->class }}
                                </span>
                            </td>
                            <td style="font-weight: 750; color: #5b21b6; background: #faf5ff;">Membaca</td>
                            <td>
                                <span class="score-badge-cell {{ is_array($dailyJson) && !empty($dailyJson['reading']) ? 'has-val' : '' }}">
                                    {{ is_array($dailyJson) ? ($dailyJson['reading'] ?? '-') : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="score-badge-cell {{ is_array($sas1Json) && !empty($sas1Json['reading']) ? 'has-val' : '' }}">
                                    {{ is_array($sas1Json) ? ($sas1Json['reading'] ?? '-') : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="score-badge-cell {{ is_array($sas2Json) && !empty($sas2Json['reading']) ? 'has-val' : '' }}">
                                    {{ is_array($sas2Json) ? ($sas2Json['reading'] ?? '-') : '-' }}
                                </span>
                            </td>
                        </tr>
                        <!-- Row for Menulis -->
                        <tr>
                            <td style="font-weight: 750; color: #5b21b6; background: #faf5ff;">Menulis</td>
                            <td>
                                <span class="score-badge-cell {{ is_array($dailyJson) && !empty($dailyJson['writing']) ? 'has-val' : '' }}">
                                    {{ is_array($dailyJson) ? ($dailyJson['writing'] ?? '-') : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="score-badge-cell {{ is_array($sas1Json) && !empty($sas1Json['writing']) ? 'has-val' : '' }}">
                                    {{ is_array($sas1Json) ? ($sas1Json['writing'] ?? '-') : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="score-badge-cell {{ is_array($sas2Json) && !empty($sas2Json['writing']) ? 'has-val' : '' }}">
                                    {{ is_array($sas2Json) ? ($sas2Json['writing'] ?? '-') : '-' }}
                                </span>
                            </td>
                        </tr>
                        <!-- Row for Menghitung -->
                        <tr>
                            <td style="font-weight: 750; color: #5b21b6; background: #faf5ff;">Menghitung</td>
                            <td>
                                <span class="score-badge-cell {{ is_array($dailyJson) && !empty($dailyJson['counting']) ? 'has-val' : '' }}">
                                    {{ is_array($dailyJson) ? ($dailyJson['counting'] ?? '-') : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="score-badge-cell {{ is_array($sas1Json) && !empty($sas1Json['counting']) ? 'has-val' : '' }}">
                                    {{ is_array($sas1Json) ? ($sas1Json['counting'] ?? '-') : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="score-badge-cell {{ is_array($sas2Json) && !empty($sas2Json['counting']) ? 'has-val' : '' }}">
                                    {{ is_array($sas2Json) ? ($sas2Json['counting'] ?? '-') : '-' }}
                                </span>
                            </td>
                        </tr>
                    @else
                        <!-- Standard Row -->
                        <tr>
                            <td style="font-weight: 800; color: #64748b;">{{ $index + 1 }}</td>
                            <td class="text-left">
                                <strong style="color: #0f172a; font-size: 0.92rem;">{{ $student->name }}</strong>
                            </td>
                            <td>
                                <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 8px; font-weight: 750; font-size: 0.82rem;">
                                    {{ $student->class }}
                                </span>
                            </td>
                            <td style="color: #94a3b8; font-weight: 600; font-size: 0.82rem;">Umum</td>
                            <td>
                                <span class="score-badge-cell {{ !empty($dailyScore) && $dailyScore !== '-' ? 'has-val' : '' }}">
                                    {{ $dailyScore }}
                                </span>
                                @if($dailyGrades->count() > 1)
                                    <div style="font-size: 0.68rem; color: #94a3b8; margin-top: 2px;">({{ $dailyGrades->count() }}x)</div>
                                @endif
                            </td>
                            <td>
                                <span class="score-badge-cell {{ !empty($sas1Raw) ? 'has-val' : '' }}">
                                    {{ $sas1Raw ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="score-badge-cell {{ !empty($sas2Raw) ? 'has-val' : '' }}">
                                    {{ $sas2Raw ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 2.5rem; color: #94a3b8; font-weight: 600;">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                            Belum ada data nilai siswa untuk ekstrakurikuler ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- B. Mobile Symmetrical Card View (Screens <= 768px) -->
            <div class="grades-mobile-cards no-print">
                @forelse($students as $index => $student)
                @php
                    $studentGrades = $grades[$student->id] ?? collect([]);
                    $dailyGrades = $studentGrades->where('type', 'daily');
                    $sas1Raw = $studentGrades->where('type', 'sas1')->first()->score ?? null;
                    $sas2Raw = $studentGrades->where('type', 'sas2')->first()->score ?? null;
                    
                    $dailyScore = '-';
                    $dailyJson = null;
                    if ($dailyGrades->isNotEmpty()) {
                        $latest = $dailyGrades->sortByDesc('date')->first();
                        $dailyScore = $latest->score;
                        $dailyJson = json_decode($dailyScore, true);
                    }
                    
                    $sas1Json = json_decode($sas1Raw, true);
                    $sas2Json = json_decode($sas2Raw, true);

                    $isStudentCalistung = ($selectedEskul->is_lockable ?? false) && \Illuminate\Support\Str::startsWith($student->class, '1');
                @endphp

                <div class="student-grade-card">
                    <!-- Top Row -->
                    <div class="grade-card-top">
                        <div class="grade-id-wrapper">
                            <div class="grade-idx-num">#{{ $index + 1 }}</div>
                            <div class="grade-name-box">
                                <span class="grade-name-text">{{ $student->name }}</span>
                                <span class="grade-class-tag">
                                    <i class="fas fa-graduation-cap"></i> Kelas {{ $student->class }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($isStudentCalistung)
                        <!-- Calistung 3-Aspect Cards -->
                        <div class="calistung-aspect-card">
                            <div class="aspect-header">
                                <span class="aspect-title"><i class="fas fa-book-reader"></i> Membaca</span>
                            </div>
                            <div class="aspect-grid">
                                <div class="aspect-stat-box">
                                    <div class="aspect-stat-val">{{ is_array($dailyJson) ? ($dailyJson['reading'] ?? '-') : '-' }}</div>
                                    <div class="aspect-stat-lbl">Harian</div>
                                </div>
                                <div class="aspect-stat-box">
                                    <div class="aspect-stat-val">{{ is_array($sas1Json) ? ($sas1Json['reading'] ?? '-') : '-' }}</div>
                                    <div class="aspect-stat-lbl">SAS 1</div>
                                </div>
                                <div class="aspect-stat-box">
                                    <div class="aspect-stat-val">{{ is_array($sas2Json) ? ($sas2Json['reading'] ?? '-') : '-' }}</div>
                                    <div class="aspect-stat-lbl">SAS 2</div>
                                </div>
                            </div>
                        </div>

                        <div class="calistung-aspect-card">
                            <div class="aspect-header">
                                <span class="aspect-title"><i class="fas fa-pen-fancy"></i> Menulis</span>
                            </div>
                            <div class="aspect-grid">
                                <div class="aspect-stat-box">
                                    <div class="aspect-stat-val">{{ is_array($dailyJson) ? ($dailyJson['writing'] ?? '-') : '-' }}</div>
                                    <div class="aspect-stat-lbl">Harian</div>
                                </div>
                                <div class="aspect-stat-box">
                                    <div class="aspect-stat-val">{{ is_array($sas1Json) ? ($sas1Json['writing'] ?? '-') : '-' }}</div>
                                    <div class="aspect-stat-lbl">SAS 1</div>
                                </div>
                                <div class="aspect-stat-box">
                                    <div class="aspect-stat-val">{{ is_array($sas2Json) ? ($sas2Json['writing'] ?? '-') : '-' }}</div>
                                    <div class="aspect-stat-lbl">SAS 2</div>
                                </div>
                            </div>
                        </div>

                        <div class="calistung-aspect-card">
                            <div class="aspect-header">
                                <span class="aspect-title"><i class="fas fa-calculator"></i> Menghitung</span>
                            </div>
                            <div class="aspect-grid">
                                <div class="aspect-stat-box">
                                    <div class="aspect-stat-val">{{ is_array($dailyJson) ? ($dailyJson['counting'] ?? '-') : '-' }}</div>
                                    <div class="aspect-stat-lbl">Harian</div>
                                </div>
                                <div class="aspect-stat-box">
                                    <div class="aspect-stat-val">{{ is_array($sas1Json) ? ($sas1Json['counting'] ?? '-') : '-' }}</div>
                                    <div class="aspect-stat-lbl">SAS 1</div>
                                </div>
                                <div class="aspect-stat-box">
                                    <div class="aspect-stat-val">{{ is_array($sas2Json) ? ($sas2Json['counting'] ?? '-') : '-' }}</div>
                                    <div class="aspect-stat-lbl">SAS 2</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Symmetrical 3-Column Grid for Umum -->
                        <div class="grade-scores-grid">
                            <div class="score-micro-box">
                                <span class="score-micro-num">{{ $dailyScore }}</span>
                                <span class="score-micro-lbl">Harian</span>
                                @if($dailyGrades->count() > 1)
                                    <span class="score-micro-sub">({{ $dailyGrades->count() }}x)</span>
                                @endif
                            </div>
                            <div class="score-micro-box">
                                <span class="score-micro-num">{{ $sas1Raw ?? '-' }}</span>
                                <span class="score-micro-lbl">SAS 1</span>
                                <span class="score-micro-sub">Ganjil</span>
                            </div>
                            <div class="score-micro-box">
                                <span class="score-micro-num">{{ $sas2Raw ?? '-' }}</span>
                                <span class="score-micro-lbl">SAS 2</span>
                                <span class="score-micro-sub">Genap</span>
                            </div>
                        </div>
                    @endif
                </div>
                @empty
                <div style="text-align: center; padding: 2rem 1rem; color: #94a3b8; font-weight: 600;">
                    <i class="fas fa-inbox" style="font-size: 2.2rem; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                    Belum ada data nilai siswa untuk ekstrakurikuler ini.
                </div>
                @endforelse
            </div>
        @else
            <div style="text-align: center; padding: 3rem 1rem; color: #94a3b8;">
                <i class="fas fa-exclamation-circle" style="font-size: 2.5rem; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                <h4 style="margin: 0 0 5px 0; color: #475569;">Data Tidak Ditemukan</h4>
                <p style="margin: 0; font-size: 0.88rem;">Silakan pilih ekstrakurikuler terlebih dahulu.</p>
            </div>
        @endif
    </div>

</div>
@endsection
