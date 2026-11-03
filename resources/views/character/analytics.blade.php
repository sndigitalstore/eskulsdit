@extends('layouts.app')

@section('title', 'Grafik Capaian Karakter Siswa – SMILE Karakter')
@section('page-title', 'Grafik & Analitik Karakter')

@push('styles')
<style>
    .analytics-hero {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #0f172a 100%);
        color: white;
        padding: 26px 30px;
        border-radius: 20px;
        box-shadow: 0 10px 25px -5px rgba(6, 78, 59, 0.35);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .analytics-hero::after {
        content: '\f201';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: 15px;
        bottom: -25px;
        font-size: 10rem;
        color: rgba(255, 255, 255, 0.04);
        pointer-events: none;
    }
    .period-tab-bar {
        display: inline-flex;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        gap: 6px;
        flex-wrap: wrap;
    }
    .period-tab-btn {
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        transition: all 0.2s ease;
    }
    .period-tab-btn.active {
        background: #ffffff;
        color: #065f46;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
    }
    .period-tab-btn:hover:not(.active) {
        background: rgba(255, 255, 255, 0.6);
        color: #1e293b;
    }
    .chart-container-card {
        background: white;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        padding: 22px 24px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    }
    .sub-days-pill {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        color: #64748b;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
    }
    .sub-days-pill.active {
        background: #065f46;
        color: white;
        border-color: #065f46;
    }
</style>
@endpush

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding-bottom: 60px;">

    <!-- 1. HERO BANNER & AKSI KESISWAAN -->
    <div class="analytics-hero">
        <div style="z-index: 1; max-width: 720px;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.16); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                <i class="fas fa-chart-line" style="color: #6ee7b7;"></i> Analitik & Evaluasi Kesiswaan
            </div>
            <h2 style="margin: 0 0 8px 0; font-size: 1.65rem; font-weight: 900; letter-spacing: -0.02em;">
                Grafik Capaian Karakter Siswa (SMILE)
            </h2>
            <p style="margin: 0; opacity: 0.92; font-size: 0.9rem; line-height: 1.5;">
                Pelaporan tren harian, bulanan, semester, dan tahunan mencakup <strong>Kasus Pembinaan Restoratif</strong>, <strong>Apresiasi Prestasi</strong>, dan <strong>8 Dimensi Profil Lulusan Kemendikdasmen</strong> secara akuntabel & profesional.
            </p>
        </div>

        <div style="z-index: 1; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <a href="{{ route('character.analytics.print', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'period' => $period, 'days' => $days]) }}" target="_blank" 
               class="btn-action-header" 
               style="background: #ffffff; color: #065f46; font-weight: 800; padding: 11px 20px; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(0,0,0,0.2);">
                <i class="fas fa-print"></i> Cetak Laporan Resmi (A4)
            </a>
            <a href="{{ route('character.dashboard') }}" class="btn-action-header" style="background: rgba(255, 255, 255, 0.18); color: white; font-weight: 700; padding: 11px 18px; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: 1px solid rgba(255,255,255,0.3);">
                <i class="fas fa-th-large"></i> Pantauan
            </a>
            <a href="{{ route('character.report') }}" class="btn-action-header" style="background: rgba(255, 255, 255, 0.18); color: white; font-weight: 700; padding: 11px 18px; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: 1px solid rgba(255,255,255,0.3);">
                <i class="fas fa-file-signature"></i> Rekap Karakter
            </a>
        </div>
    </div>

    <!-- 2. FILTER & PERIODE SWITCHER -->
    <div class="card" style="margin-bottom: 24px; padding: 20px 24px; border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
            <!-- Period Selector Tabs -->
            <div>
                <span style="font-size: 0.78rem; font-weight: 800; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;">
                    Pilih Skala Periode Pelaporan:
                </span>
                <div class="period-tab-bar">
                    <a href="{{ route('character.analytics', ['period' => 'daily', 'year_id' => $selectedYearId, 'class' => $selectedClass, 'days' => $days]) }}" 
                       class="period-tab-btn {{ $period === 'daily' ? 'active' : '' }}">
                        <i class="far fa-calendar"></i> Harian (Daily)
                    </a>
                    <a href="{{ route('character.analytics', ['period' => 'monthly', 'year_id' => $selectedYearId, 'class' => $selectedClass]) }}" 
                       class="period-tab-btn {{ $period === 'monthly' ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Bulanan (Monthly)
                    </a>
                    <a href="{{ route('character.analytics', ['period' => 'semester', 'year_id' => $selectedYearId, 'class' => $selectedClass]) }}" 
                       class="period-tab-btn {{ $period === 'semester' ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i> Semester (Ganjil / Genap)
                    </a>
                    <a href="{{ route('character.analytics', ['period' => 'yearly', 'year_id' => $selectedYearId, 'class' => $selectedClass]) }}" 
                       class="period-tab-btn {{ $period === 'yearly' ? 'active' : '' }}">
                        <i class="fas fa-history"></i> Tahunan (Komparatif)
                    </a>
                </div>
            </div>

            <!-- Sub-filter untuk harian: 7, 14, 30 hari -->
            @if($period === 'daily')
                <div>
                    <span style="font-size: 0.78rem; font-weight: 800; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;">
                        Rentang Waktu:
                    </span>
                    <div style="display: flex; gap: 6px;">
                        <a href="{{ route('character.analytics', ['period' => 'daily', 'days' => 7, 'year_id' => $selectedYearId, 'class' => $selectedClass]) }}" class="sub-days-pill {{ $days == 7 ? 'active' : '' }}">7 Hari Terakhir</a>
                        <a href="{{ route('character.analytics', ['period' => 'daily', 'days' => 14, 'year_id' => $selectedYearId, 'class' => $selectedClass]) }}" class="sub-days-pill {{ $days == 14 ? 'active' : '' }}">14 Hari Terakhir</a>
                        <a href="{{ route('character.analytics', ['period' => 'daily', 'days' => 30, 'year_id' => $selectedYearId, 'class' => $selectedClass]) }}" class="sub-days-pill {{ $days == 30 ? 'active' : '' }}">30 Hari Terakhir</a>
                    </div>
                </div>
            @endif
        </div>

        <form action="{{ route('character.analytics') }}" method="GET" style="display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap; border-top: 1px solid #f1f5f9; padding-top: 16px;">
            <input type="hidden" name="period" value="{{ $period }}">
            <input type="hidden" name="days" value="{{ $days }}">

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.82rem;">Tahun Pelajaran</label>
                <select name="year_id" class="form-control" style="height: 42px; border-radius: 10px;">
                    @foreach($academicYears as $y)
                        <option value="{{ $y->id }}" {{ $selectedYearId == $y->id ? 'selected' : '' }}>
                            {{ $y->name }} {{ $y->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 180px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.82rem;">Filter Kelas</label>
                <select name="class" class="form-control" style="height: 42px; border-radius: 10px;">
                    <option value="">-- Seluruh Kelas (Global) --</option>
                    @foreach($classes as $c)
                        <option value="{{ $c }}" {{ $selectedClass == $c ? 'selected' : '' }}>Kelas {{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" style="height: 42px; padding: 0 20px; font-weight: 700; background: #065f46; border: none; border-radius: 10px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-filter"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- 3. KPI SUMMARY STATS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        
        <!-- Apresiasi Kebaikan -->
        <div class="card" style="border-left: 5px solid #10b981; padding: 18px 20px; margin: 0; border-radius: 14px;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Total Apresiasi</div>
                    <div style="font-size: 1.8rem; font-weight: 900; color: #047857; margin: 4px 0;">{{ $totalAppreciations }} <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">kali</span></div>
                    <div style="font-size: 0.78rem; color: #10b981; font-weight: 700;">
                        <i class="fas fa-star"></i> +{{ $totalAppreciationPoints }} Poin Bintang
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #dcfce7; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-award"></i>
                </div>
            </div>
        </div>

        <!-- Data Kasus Pembinaan -->
        <div class="card" style="border-left: 5px solid #ef4444; padding: 18px 20px; margin: 0; border-radius: 14px;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Kasus Pembinaan</div>
                    <div style="font-size: 1.8rem; font-weight: 900; color: #b91c1c; margin: 4px 0;">{{ $totalCases }} <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">kejadian</span></div>
                    <div style="font-size: 0.78rem; color: #64748b; font-weight: 600;">
                        <span style="color: #ef4444; font-weight: 700;">{{ $activeCases }} dibina</span> • <span style="color: #10b981; font-weight: 700;">{{ $resolvedCases }} selesai</span>
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>

        <!-- Tingkat Pemulihan Restoratif -->
        <div class="card" style="border-left: 5px solid #0284c7; padding: 18px 20px; margin: 0; border-radius: 14px;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Penyelesaian Kasus</div>
                    <div style="font-size: 1.8rem; font-weight: 900; color: #0284c7; margin: 4px 0;">{{ $resolveRate }}%</div>
                    <div style="font-size: 0.78rem; color: #0369a1; font-weight: 700;">
                        <i class="fas fa-check-double"></i> Pendekatan Restoratif
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-hands-helping"></i>
                </div>
            </div>
        </div>

        <!-- Iklim Keamanan Satuan Pendidikan (TPPK / Anti Bullying) -->
        <div class="card" style="border-left: 5px solid #f59e0b; padding: 18px 20px; margin: 0; border-radius: 14px;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Iklim Keamanan (TPPK)</div>
                    <div style="font-size: 1.8rem; font-weight: 900; color: {{ $tppkCases > 0 ? '#b45309' : '#047857' }}; margin: 4px 0;">
                        {{ $tppkCases }} <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">kasus</span>
                    </div>
                    <div style="font-size: 0.78rem; font-weight: 700; color: {{ $tppkCases > 0 ? '#b45309' : '#047857' }};">
                        {{ $tppkCases > 0 ? 'Perlu Intervensi Satgas' : 'Aman & Bebas Perundungan' }}
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: {{ $tppkCases > 0 ? '#fef3c7' : '#ecfdf5' }}; color: {{ $tppkCases > 0 ? '#b45309' : '#047857' }}; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
        </div>

        <!-- Neraca Poin Bersih Sekolah -->
        <div class="card" style="border-left: 5px solid #8b5cf6; padding: 18px 20px; margin: 0; border-radius: 14px;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Poin Bersih (Net Score)</div>
                    <div style="font-size: 1.8rem; font-weight: 900; color: {{ $netPoints >= 0 ? '#6d28d9' : '#b91c1c' }}; margin: 4px 0;">
                        {{ $netPoints > 0 ? '+' : '' }}{{ $netPoints }}
                    </div>
                    <div style="font-size: 0.78rem; color: #6d28d9; font-weight: 700;">
                        <i class="fas fa-balance-scale"></i> Budaya Positif Dominan
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #ede9fe; color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-balance-scale"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- 4. CHARTS GRID ROW 1: TREN UTAMA & 8 DIMENSI PROFIL LULUSAN -->
    <div style="display: grid; grid-template-columns: 2fr 1.2fr; gap: 24px; margin-bottom: 24px;">
        
        <!-- Main Trend Line Chart -->
        <div class="chart-container-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;">
                <div>
                    <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-chart-line" style="color: #065f46;"></i> Tren Capaian Karakter: Kasus vs Apresiasi
                    </h3>
                    <span style="font-size: 0.8rem; color: #64748b;">
                        Skala: {{ ucfirst($period) }} &bull; Filter: {{ $selectedClass ? 'Kelas ' . $selectedClass : 'Seluruh Rombel' }}
                    </span>
                </div>
                <div style="display: flex; gap: 12px; font-size: 0.8rem; font-weight: 700;">
                    <span style="display: inline-flex; align-items: center; gap: 5px; color: #10b981;">
                        <span style="width: 12px; height: 12px; border-radius: 3px; background: #10b981; display: inline-block;"></span> Apresiasi
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 5px; color: #ef4444;">
                        <span style="width: 12px; height: 12px; border-radius: 3px; background: #ef4444; display: inline-block;"></span> Kasus
                    </span>
                </div>
            </div>
            <div style="position: relative; height: 320px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- 8 Dimensi Profil Lulusan Kemendikdasmen (Bar / Radar) -->
        <div class="chart-container-card">
            <div style="margin-bottom: 16px;">
                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-brain" style="color: #8b5cf6;"></i> 8 Dimensi Profil Lulusan
                </h3>
                <span style="font-size: 0.8rem; color: #64748b;">
                    Distribusi Apresiasi (Deep Learning / SKL)
                </span>
            </div>
            <div style="position: relative; height: 320px;">
                <canvas id="dimensionChart"></canvas>
            </div>
        </div>

    </div>

    <!-- 5. CHARTS GRID ROW 2: PREDIKAT KARAKTER & KATEGORI KASUS -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1.2fr; gap: 24px; margin-bottom: 24px;">
        
        <!-- Doughnut: Sebaran Predikat Karakter Siswa -->
        <div class="chart-container-card">
            <div style="margin-bottom: 16px;">
                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-users-cog" style="color: #0284c7;"></i> Sebaran Predikat Siswa
                </h3>
                <span style="font-size: 0.8rem; color: #64748b;">Kondisi Akhlak & Kepatuhan Peserta Didik</span>
            </div>
            <div style="position: relative; height: 250px;">
                <canvas id="predicateChart"></canvas>
            </div>
        </div>

        <!-- Doughnut: Tingkat Keparahan Kasus -->
        <div class="chart-container-card">
            <div style="margin-bottom: 16px;">
                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-tachometer-alt" style="color: #f59e0b;"></i> Tingkat Keparahan Kasus
                </h3>
                <span style="font-size: 0.8rem; color: #64748b;">Klasifikasi Kasus Pembinaan (Ringan/Sedang/Berat)</span>
            </div>
            <div style="position: relative; height: 250px;">
                <canvas id="severityChart"></canvas>
            </div>
        </div>

        <!-- Horizontal Bar: Komparasi Antar Kelas (Rombel) -->
        <div class="chart-container-card">
            <div style="margin-bottom: 16px;">
                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-school" style="color: #10b981;"></i> Poin Karakter per Kelas
                </h3>
                <span style="font-size: 0.8rem; color: #64748b;">Komparasi Poin Bersih Antar Rombel</span>
            </div>
            <div style="position: relative; height: 250px;">
                <canvas id="classChart"></canvas>
            </div>
        </div>

    </div>

    <!-- 6. TABEL REKAPITULASI CAPAIAN BERDASARKAN PERIODE -->
    <div class="card" style="border-radius: 18px; padding: 0; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <div style="padding: 18px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div>
                <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-table" style="color: #065f46;"></i> Tabel Rekapitulasi Capaian Karakter ({{ ucfirst($period) }})
                </h4>
                <span style="font-size: 0.82rem; color: #64748b;">Rincian data frekuensi kasus, tindakan restoratif, apresiasi kebaikan, dan kalkulasi poin bersih.</span>
            </div>
            <a href="{{ route('character.analytics.print', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'period' => $period, 'days' => $days]) }}" target="_blank" 
               class="btn btn-sm" style="background: #065f46; color: white; font-weight: 700; padding: 6px 14px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-file-pdf"></i> Unduh / Cetak Dokumen
            </a>
        </div>

        <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse; margin: 0; font-size: 0.88rem;">
                <thead style="background: #f1f5f9; color: #475569; position: sticky; top: 0; z-index: 5;">
                    <tr>
                        <th style="padding: 12px 18px; text-align: left;">Periode / Waktu</th>
                        <th style="padding: 12px; text-align: center; color: #047857;"><i class="fas fa-award"></i> Apresiasi (Frekuensi)</th>
                        <th style="padding: 12px; text-align: center; color: #047857;"><i class="fas fa-star"></i> Poin Apresiasi</th>
                        <th style="padding: 12px; text-align: center; color: #b91c1c;"><i class="fas fa-exclamation-triangle"></i> Kasus (Frekuensi)</th>
                        <th style="padding: 12px; text-align: center; color: #b91c1c;"><i class="fas fa-minus-circle"></i> Poin Kasus</th>
                        <th style="padding: 12px 18px; text-align: center; font-weight: 800;">Poin Bersih (Net)</th>
                        <th style="padding: 12px 18px; text-align: center;">Status Kepatuhan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tableDataReversed as $row)
                        @php
                            $net = $row['net'] ?? 0;
                            $hasCase = ($row['cases_count'] ?? 0) > 0;
                            $hasAppr = ($row['appr_count'] ?? 0) > 0;
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                            <td style="padding: 12px 18px; font-weight: 700; color: #1e293b;">
                                {{ $row['label'] }}
                            </td>
                            <td style="padding: 12px; text-align: center; font-weight: 700; color: #047857;">
                                {{ $row['appr_count'] }} kali
                            </td>
                            <td style="padding: 12px; text-align: center; font-weight: 700; color: #10b981;">
                                +{{ $row['appr_points'] }}
                            </td>
                            <td style="padding: 12px; text-align: center; font-weight: 700; color: {{ $hasCase ? '#b91c1c' : '#64748b' }};">
                                {{ $row['cases_count'] }} kejadian
                            </td>
                            <td style="padding: 12px; text-align: center; font-weight: 700; color: {{ $hasCase ? '#ef4444' : '#64748b' }};">
                                -{{ $row['case_points'] }}
                            </td>
                            <td style="padding: 12px 18px; text-align: center; font-weight: 900; color: {{ $net >= 0 ? '#047857' : '#b91c1c' }};">
                                {{ $net > 0 ? '+' : '' }}{{ $net }}
                            </td>
                            <td style="padding: 12px 18px; text-align: center;">
                                @if(!$hasCase && $hasAppr)
                                    <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 20px; font-size: 0.76rem; font-weight: 800;">
                                        <i class="fas fa-check-circle"></i> Sangat Baik
                                    </span>
                                @elseif(!$hasCase && !$hasAppr)
                                    <span style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 0.76rem; font-weight: 700;">
                                        Kondusif
                                    </span>
                                @else
                                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 20px; font-size: 0.76rem; font-weight: 800;">
                                        <i class="fas fa-shield-alt"></i> Tindak Lanjut
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center; color: #94a3b8;">
                                Belum ada rekaman capaian karakter pada rentang periode yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. DATA FROM BACKEND
    const trendLabels = {!! json_encode($trendLabels) !!};
    const trendCases = {!! json_encode($trendCases) !!};
    const trendAppreciations = {!! json_encode($trendAppreciations) !!};
    const trendPoints = {!! json_encode($trendPoints) !!};

    const dimensionLabels = {!! json_encode(array_keys($dimensionCounts)) !!};
    const dimensionData = {!! json_encode(array_values($dimensionCounts)) !!};

    const predicateLabels = {!! json_encode(array_keys($predicateCounts)) !!};
    const predicateData = {!! json_encode(array_values($predicateCounts)) !!};

    const severityLabels = ['Ringan', 'Sedang', 'Berat'];
    const severityData = [
        {{ $severityCounts['ringan'] ?? 0 }},
        {{ $severityCounts['sedang'] ?? 0 }},
        {{ $severityCounts['berat'] ?? 0 }}
    ];

    const classLabels = {!! json_encode(array_column($classComparison, 'class')) !!};
    const classNetPoints = {!! json_encode(array_column($classComparison, 'net_points')) !!};

    // --- CHART 1: MAIN TREND CHART (Kasus vs Apresiasi) ---
    const ctxTrend = document.getElementById('trendChart');
    if (ctxTrend) {
        new Chart(ctxTrend.getContext('2d'), {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [
                    {
                        label: 'Apresiasi Karakter',
                        data: trendAppreciations,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        tension: 0.35,
                        fill: true
                    },
                    {
                        label: 'Kasus Pembinaan',
                        data: trendCases,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.10)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ef4444',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        tension: 0.35,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }

    // --- CHART 2: 8 DIMENSI PROFIL LULUSAN (Bar Chart) ---
    const ctxDim = document.getElementById('dimensionChart');
    if (ctxDim) {
        new Chart(ctxDim.getContext('2d'), {
            type: 'bar',
            data: {
                labels: dimensionLabels,
                datasets: [{
                    label: 'Apresiasi',
                    data: dimensionData,
                    backgroundColor: [
                        '#059669', '#dc2626', '#2563eb', '#7c3aed',
                        '#16a34a', '#ea580c', '#0891b2', '#4f46e5'
                    ],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    },
                    y: {
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }

    // --- CHART 3: SEBARAN PREDIKAT KARAKTER SISWA (Doughnut) ---
    const ctxPred = document.getElementById('predicateChart');
    if (ctxPred) {
        new Chart(ctxPred.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: predicateLabels,
                datasets: [{
                    data: predicateData,
                    backgroundColor: ['#10b981', '#0284c7', '#f59e0b', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 10 } }
                    }
                }
            }
        });
    }

    // --- CHART 4: TINGKAT KEPARAHAN KASUS (Doughnut) ---
    const ctxSev = document.getElementById('severityChart');
    if (ctxSev) {
        new Chart(ctxSev.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: severityLabels,
                datasets: [{
                    data: severityData,
                    backgroundColor: ['#3b82f6', '#f59e0b', '#dc2626'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 10 } }
                    }
                }
            }
        });
    }

    // --- CHART 5: KOMPARASI POIN BERSIH PER KELAS (Bar) ---
    const ctxClass = document.getElementById('classChart');
    if (ctxClass) {
        new Chart(ctxClass.getContext('2d'), {
            type: 'bar',
            data: {
                labels: classLabels,
                datasets: [{
                    label: 'Poin Bersih',
                    data: classNetPoints,
                    backgroundColor: classNetPoints.map(val => val >= 0 ? '#059669' : '#dc2626'),
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        ticks: { stepSize: 5 }
                    }
                }
            }
        });
    }
});
</script>
@endpush
