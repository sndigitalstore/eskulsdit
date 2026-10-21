@php
    $tabTitle = 'Laporan Ekstrakurikuler';
    if (($activeTab ?? 'eskul') === 'tilawati') {
        $tabTitle = 'Laporan Pembinaan Tilawati';
    } elseif (($activeTab ?? 'eskul') === 'tahfidz') {
        $tabTitle = 'Laporan Pembinaan Tahfidz';
    }
@endphp
@extends('layouts.app')

@section('title', $tabTitle)
@section('page-title', $tabTitle)

@push('styles')
<style>
    .form-row { display: flex; gap: 20px; align-items: flex-end; }
    .form-group { flex: 1; }
    
    .report-tabs {
        display: flex;
        gap: 8px;
        background: #f1f5f9;
        padding: 6px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        width: fit-content;
        max-width: 100%;
        overflow-x: auto;
        margin-bottom: 24px;
    }
    .report-tab-btn {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.92rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .report-tab-btn.active {
        background: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
    }
    .report-tab-btn.inactive {
        color: #64748b;
        background: transparent;
    }
    .report-tab-btn.inactive:hover {
        color: #1e293b;
        background: rgba(255, 255, 255, 0.6);
    }

    .kpi-stat-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }
    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
    
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }
        .form-group {
            width: 100%;
        }
    }
    
    @media print {
        .filter-section, .btn-print, .report-tabs, .no-print { display: none !important; }
        .card { box-shadow: none; border: none; padding: 0; }
        .content { padding: 0; }
        body { background: white; }
    }
</style>
@endpush

@section('content')
<style>
    @media print {
        @page { 
            size: A4; 
            margin: 0.5cm; 
        }
        body { 
            background: white; 
            margin: 0; 
            padding: 0;
            font-family: 'Times New Roman', serif;
            color: #000;
        }
        .filter-section, .btn-print, .sidebar, .header, .page-header { 
            display: none !important; 
        }
        .main-content { margin: 0; padding: 0; }
        .card { box-shadow: none; border: none; padding: 0; }
        .content { padding: 0; }
        
        .print-header { display: block !important; margin-bottom: 5px; border-bottom: none; }
        .print-stats { display: flex !important; margin-bottom: 5px; padding: 5px !important; }
        .print-footer { display: block !important; margin-top: 5px; }
        
        /* Modern Table Optimized for Print */
        table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 8pt; }
        table, th, td { border: 1px solid #ccc; }
        th { background-color: #f8f9fa !important; color: #333; font-weight: bold; text-align: center; border-bottom: 1px solid #999 !important; padding: 3px 2px; }
        td { padding: 2px 4px; vertical-align: middle; color: #000; line-height: 1.1; }
        
        /* Remove preview box shadows for print */
        .print-preview-box {
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
        }

        .no-print { display: none !important; }
    }

    .print-header, .print-footer { display: none; }
    
    .print-preview-box {
        background: white; 
        width: 210mm; 
        min-height: 297mm; 
        margin: 0 auto; 
        padding: 20mm; 
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        display: none;
    }

    .show-preview .print-preview-box { display: block; }
    .show-preview .filter-section { display: none; }
</style>

<div id="main-container">

    <!-- 3-Tab Selector: Eskul vs Tilawati vs Tahfidz -->
    <div class="no-print">
        <div class="report-tabs">
            <a href="{{ route('reports.index', ['tab' => 'eskul', 'year_id' => $selectedYearId, 'class' => $selectedClass]) }}" 
               class="report-tab-btn {{ ($activeTab ?? 'eskul') === 'eskul' ? 'active' : 'inactive' }}"
               style="{{ ($activeTab ?? 'eskul') === 'eskul' ? 'color: #2563eb;' : '' }}">
                <i class="fas fa-futbol" style="{{ ($activeTab ?? 'eskul') === 'eskul' ? 'color: #2563eb;' : '' }}"></i>
                <span>Ekstrakurikuler</span>
            </a>
            
            <a href="{{ route('reports.index', ['tab' => 'tilawati', 'year_id' => $selectedYearId, 'class' => $selectedClass]) }}" 
               class="report-tab-btn {{ ($activeTab ?? 'eskul') === 'tilawati' ? 'active' : 'inactive' }}"
               style="{{ ($activeTab ?? 'eskul') === 'tilawati' ? 'color: #16a34a;' : '' }}">
                <i class="fas fa-book-open" style="{{ ($activeTab ?? 'eskul') === 'tilawati' ? 'color: #16a34a;' : '' }}"></i>
                <span>Pembinaan Tilawati</span>
                <span style="font-size: 0.72rem; padding: 2px 8px; border-radius: 999px; font-weight: 700; background: {{ ($activeTab ?? 'eskul') === 'tilawati' ? '#dcfce7; color: #15803d;' : '#e2e8f0; color: #64748b;' }}">Jilid</span>
            </a>
            
            <a href="{{ route('reports.index', ['tab' => 'tahfidz', 'year_id' => $selectedYearId, 'class' => $selectedClass]) }}" 
               class="report-tab-btn {{ ($activeTab ?? 'eskul') === 'tahfidz' ? 'active' : 'inactive' }}"
               style="{{ ($activeTab ?? 'eskul') === 'tahfidz' ? 'color: #0284c7;' : '' }}">
                <i class="fas fa-quran" style="{{ ($activeTab ?? 'eskul') === 'tahfidz' ? 'color: #0284c7;' : '' }}"></i>
                <span>Pembinaan Tahfidz</span>
                <span style="font-size: 0.72rem; padding: 2px 8px; border-radius: 999px; font-weight: 700; background: {{ ($activeTab ?? 'eskul') === 'tahfidz' ? '#e0f2fe; color: #0369a1;' : '#e2e8f0; color: #64748b;' }}">Hafalan</span>
            </a>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- TAB 1: PEMBINAAN TILAWATI                                                 --}}
    {{-- ========================================================================= --}}
    @if(($activeTab ?? 'eskul') === 'tilawati')

        <!-- Filter Tilawati -->
        <div class="card filter-section no-print" style="margin-bottom: 20px; border-top: 4px solid #16a34a;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-book-open" style="color: #16a34a;"></i> Filter Laporan Pembinaan Tilawati
                    </h3>
                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.85rem;">
                        Laporan capaian Jilid 1–6 / Al-Qur'an, halaman mutakhir, serta catatan evaluasi fashohah & kelancaran siswa.
                    </p>
                </div>
                <span style="font-size: 0.82rem; background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 999px; font-weight: 600;">
                    <i class="fas fa-calendar-alt"></i> TA: {{ \App\Models\AcademicYear::find($selectedYearId)->name ?? '-' }}
                </span>
            </div>
            
            <form action="{{ route('reports.index') }}" method="GET">
                <input type="hidden" name="tab" value="tilawati">
                <div class="form-row">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #475569; font-size: 0.85rem;">Tahun Pelajaran</label>
                        <select name="year_id" class="form-control" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #475569; font-size: 0.85rem;">Kelas</label>
                        <select name="class" class="form-control">
                            <option value="">-- Semua Kelas / Pilih Kelas --</option>
                            @if(in_array(Auth::user()->role, ['admin', 'headmaster']))
                                <option value="ALL" {{ $selectedClass == 'ALL' ? 'selected' : '' }} style="font-weight: bold; color: #16a34a;">
                                    🌟 -- SEMUA KELAS (REKAP SEKOLAH) --
                                </option>
                            @endif
                            @foreach($classes as $c)
                                <option value="{{ $c }}" {{ $selectedClass == $c ? 'selected' : '' }}>Kelas {{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #475569; font-size: 0.85rem;">Kelompok Tilawati (Opsional)</label>
                        <select name="group_id" class="form-control">
                            <option value="">-- Semua Kelompok Tilawati --</option>
                            @foreach($tilawatiGroups as $grp)
                                <option value="{{ $grp->id }}" {{ $selectedGroupId == $grp->id ? 'selected' : '' }}>
                                    {{ $grp->name }} ({{ $grp->target_level ?? 'Jilid' }}) - {{ $grp->mentor_name ?? 'Tanpa Pembina' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="padding-bottom: 1px;">
                        <button type="submit" class="btn-action-header btn-green" style="height: 48px; width: 100%; justify-content: center; background: #16a34a; font-weight: 700;">
                            <i class="fas fa-filter"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($selectedClass || $selectedGroupId)
            @if(isset($students) && count($students) > 0)
                <!-- Actions Bar -->
                <div class="no-print" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div style="font-size: 0.9rem; color: #475569;">
                        Menampilkan <strong>{{ count($students) }} Siswa</strong> 
                        {{ $selectedClass ? ($selectedClass === 'ALL' ? 'seluruh kelas' : 'Kelas ' . $selectedClass) : '' }}
                        {{ $selectedGroupId ? '(Filter Kelompok aktif)' : '' }}
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('reports.tilawati.print', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'group_id' => $selectedGroupId]) }}" 
                           target="_blank" class="btn-action-header btn-green" style="background: #16a34a; font-weight: 700; padding: 10px 20px; font-size: 0.95rem;">
                            <i class="fas fa-print"></i> Cetak Laporan Tilawati (A4 Formal)
                        </a>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div class="no-print" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
                    <div class="kpi-stat-card">
                        <div class="kpi-icon" style="background: #dcfce7; color: #16a34a;">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Total Siswa</div>
                            <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">{{ $quranStats['total_students'] ?? count($students) }}</div>
                            <div style="font-size: 0.75rem; color: #16a34a;"><i class="fas fa-check-circle"></i> Terdaftar Aktif</div>
                        </div>
                    </div>
                    <div class="kpi-stat-card">
                        <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Siswa Berkelompok</div>
                            <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">{{ $quranStats['assigned_count'] ?? 0 }}</div>
                            <div style="font-size: 0.75rem; color: #64748b;">Belum: {{ $quranStats['unassigned_count'] ?? 0 }} anak</div>
                        </div>
                    </div>
                    <div class="kpi-stat-card" style="grid-column: span 2;">
                        <div style="width: 100%;">
                            <div style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">
                                Sebaran Level / Jilid Siswa
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @if(isset($quranStats['jilid_counts']))
                                    @foreach($quranStats['jilid_counts'] as $jName => $jCount)
                                        @if($jCount > 0)
                                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; background: {{ $jName === 'Al-Qur\'an' ? '#fef3c7' : '#f1f5f9' }}; color: {{ $jName === 'Al-Qur\'an' ? '#b45309' : '#334155' }}; border: 1px solid #e2e8f0;">
                                                <span>{{ $jName }}:</span>
                                                <strong style="color: #16a34a;">{{ $jCount }}</strong>
                                            </span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Tilawati -->
                <div class="card" style="padding: 0; overflow: hidden; border: 1px solid #e2e8f0;">
                    <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; font-size: 1rem; color: #1e293b; font-weight: 700;">
                            <i class="fas fa-list-check" style="color: #16a34a;"></i> Daftar Evaluasi Pembinaan Tilawati
                        </h4>
                        <span style="font-size: 0.78rem; color: #64748b;">
                            Format Akuntabel SDIT AN NADZIR
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table" style="width: 100%; border-collapse: collapse; margin: 0;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 0.82rem; color: #475569;">
                                    <th style="padding: 12px; text-align: center; width: 50px;">No</th>
                                    <th style="padding: 12px; text-align: left;">NIS</th>
                                    <th style="padding: 12px; text-align: left;">Nama Siswa</th>
                                    <th style="padding: 12px; text-align: center;">Kelas</th>
                                    <th style="padding: 12px; text-align: left;">Kelompok & Pembina</th>
                                    <th style="padding: 12px; text-align: left;">Target Jilid</th>
                                    <th style="padding: 12px; text-align: left;">Capaian Halaman / Fashohah</th>
                                    <th style="padding: 12px; text-align: center;">Status</th>
                                    <th style="padding: 12px; text-align: center; width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $idx => $student)
                                    @php
                                        $grp = $student->mentoringGroups->first();
                                        $notes = $grp->pivot->notes ?? null;
                                    @endphp
                                    <tr style="border-bottom: 1px solid #f1f5f9; font-size: 0.88rem;">
                                        <td style="padding: 12px; text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                                        <td style="padding: 12px; font-family: monospace; font-size: 0.82rem; color: #64748b;">{{ $student->nis ?? '-' }}</td>
                                        <td style="padding: 12px;">
                                            <strong style="color: #1e293b;">{{ $student->name }}</strong>
                                            @if($student->gender)
                                                <span style="font-size: 0.72rem; padding: 2px 6px; border-radius: 4px; margin-left: 4px; background: {{ $student->gender == 'L' ? '#e0f2fe; color: #0369a1;' : '#fce7f3; color: #be185d;' }}">
                                                    {{ $student->gender }}
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px; text-align: center;">
                                            <span style="display: inline-block; padding: 3px 8px; border-radius: 6px; background: #f1f5f9; font-weight: 700; font-size: 0.8rem; color: #334155;">
                                                {{ $student->class }}
                                            </span>
                                        </td>
                                        <td style="padding: 12px;">
                                            @if($grp)
                                                <div style="font-weight: 600; color: #0f172a;">{{ $grp->name }}</div>
                                                <div style="font-size: 0.78rem; color: #16a34a;">
                                                    <i class="fas fa-chalkboard-teacher"></i> {{ $grp->mentor_name ?? 'Ustadz/ah' }}
                                                </div>
                                            @else
                                                <span style="color: #ef4444; font-size: 0.8rem; font-style: italic;">
                                                    <i class="fas fa-exclamation-triangle"></i> Belum Masuk Kelompok
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px;">
                                            @if($grp && $grp->target_level)
                                                <span style="display: inline-block; padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                                                    {{ $grp->target_level }}
                                                </span>
                                            @else
                                                <span style="color: #94a3b8; font-size: 0.82rem;">-</span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px;">
                                            @if($notes)
                                                <div style="font-size: 0.85rem; color: #334155; line-height: 1.4;">{{ $notes }}</div>
                                            @else
                                                <span style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">Belum ada catatan pembina</span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px; text-align: center;">
                                            @if($grp)
                                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 999px; background: #dcfce7; color: #15803d; font-size: 0.75rem; font-weight: 600;">
                                                    <i class="fas fa-check"></i> Aktif
                                                </span>
                                            @else
                                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 999px; background: #fee2e2; color: #991b1b; font-size: 0.75rem; font-weight: 600;">
                                                    <i class="fas fa-times"></i> Belum
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px; text-align: center;">
                                            @if($grp)
                                                <a href="{{ route('tilawati-groups.show', $grp->id) }}" class="btn-action-header btn-blue" style="padding: 4px 10px; font-size: 0.78rem; text-decoration: none;" title="Lihat Kelompok">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            @else
                                                <a href="{{ route('mentoring.management', ['tab' => 'unassigned', 'unassigned_type' => 'tilawati', 'year_id' => $selectedYearId]) }}" class="btn-action-header btn-orange" style="padding: 4px 10px; font-size: 0.78rem; text-decoration: none;" title="Petakan Siswa">
                                                    <i class="fas fa-user-plus"></i> Petakan
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="card" style="text-align: center; color: #64748b; padding: 3.5rem 1.5rem;">
                    <i class="fas fa-book-open" style="font-size: 2.8rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                    <h4 style="color: #334155; margin-bottom: 6px;">Tidak Ada Data Siswa Tilawati</h4>
                    <p style="font-size: 0.9rem; margin: 0; max-width: 500px; margin: 0 auto;">Tidak ditemukan siswa yang sesuai dengan filter kelas atau kelompok Tilawati yang dipilih pada Tahun Pelajaran ini.</p>
                </div>
            @endif
        @else
            <div class="card" style="text-align: center; color: #64748b; padding: 3.5rem 1.5rem;">
                <i class="fas fa-search" style="font-size: 2.8rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                <h4 style="color: #334155; margin-bottom: 6px;">Pilih Filter Laporan Tilawati</h4>
                <p style="font-size: 0.9rem; margin: 0; max-width: 500px; margin: 0 auto;">Silakan pilih Kelas atau Kelompok Tilawati di atas, lalu klik <strong>Tampilkan</strong> untuk memuat data laporan capaian jilid siswa.</p>
            </div>
        @endif

    {{-- ========================================================================= --}}
    {{-- TAB 2: PEMBINAAN TAHFIDZ                                                  --}}
    {{-- ========================================================================= --}}
    @elseif(($activeTab ?? 'eskul') === 'tahfidz')

        <!-- Filter Tahfidz -->
        <div class="card filter-section no-print" style="margin-bottom: 20px; border-top: 4px solid #0284c7;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-quran" style="color: #0284c7;"></i> Filter Laporan Pembinaan Tahfidz
                    </h3>
                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.85rem;">
                        Laporan akuntabel setoran hafalan baru, murojaah, mutu kualitas (lancar/cukup/perlu perbaikan), dan rekap sima'an siswa.
                    </p>
                </div>
                <span style="font-size: 0.82rem; background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 999px; font-weight: 600;">
                    <i class="fas fa-calendar-alt"></i> TA: {{ \App\Models\AcademicYear::find($selectedYearId)->name ?? '-' }}
                </span>
            </div>
            
            <form action="{{ route('reports.index') }}" method="GET">
                <input type="hidden" name="tab" value="tahfidz">
                <div class="form-row">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #475569; font-size: 0.85rem;">Tahun Pelajaran</label>
                        <select name="year_id" class="form-control" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #475569; font-size: 0.85rem;">Kelas</label>
                        <select name="class" class="form-control">
                            <option value="">-- Semua Kelas / Pilih Kelas --</option>
                            @if(in_array(Auth::user()->role, ['admin', 'headmaster']))
                                <option value="ALL" {{ $selectedClass == 'ALL' ? 'selected' : '' }} style="font-weight: bold; color: #0284c7;">
                                    🌟 -- SEMUA KELAS (REKAP SEKOLAH) --
                                </option>
                            @endif
                            @foreach($classes as $c)
                                <option value="{{ $c }}" {{ $selectedClass == $c ? 'selected' : '' }}>Kelas {{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #475569; font-size: 0.85rem;">Kelompok Tahfidz (Opsional)</label>
                        <select name="group_id" class="form-control">
                            <option value="">-- Semua Kelompok Tahfidz --</option>
                            @foreach($tahfidzGroups as $grp)
                                <option value="{{ $grp->id }}" {{ $selectedGroupId == $grp->id ? 'selected' : '' }}>
                                    {{ $grp->name }} (Target: {{ $grp->target_level ?? 'Juz 30' }}) - {{ $grp->mentor_name ?? 'Tanpa Musyrif' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="padding-bottom: 1px;">
                        <button type="submit" class="btn-action-header btn-blue" style="height: 48px; width: 100%; justify-content: center; background: #0284c7; font-weight: 700; border-color: #0284c7;">
                            <i class="fas fa-filter"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($selectedClass || $selectedGroupId)
            @if(isset($students) && count($students) > 0)
                <!-- Actions Bar -->
                <div class="no-print" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div style="font-size: 0.9rem; color: #475569;">
                        Menampilkan <strong>{{ count($students) }} Siswa Tahfidz</strong> 
                        {{ $selectedClass ? ($selectedClass === 'ALL' ? 'seluruh kelas' : 'Kelas ' . $selectedClass) : '' }}
                        {{ $selectedGroupId ? '(Filter Kelompok aktif)' : '' }}
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('reports.tahfidz.print', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'group_id' => $selectedGroupId]) }}" 
                           target="_blank" class="btn-action-header btn-blue" style="background: #0284c7; font-weight: 700; padding: 10px 20px; font-size: 0.95rem;">
                            <i class="fas fa-print"></i> Cetak Laporan Evaluasi Tahfidz (A4 Landscape)
                        </a>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div class="no-print" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
                    <div class="kpi-stat-card">
                        <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-quran"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Total Siswa</div>
                            <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">{{ $quranStats['total_students'] ?? count($students) }}</div>
                            <div style="font-size: 0.75rem; color: #0284c7;"><i class="fas fa-check-circle"></i> Siswa Terdata</div>
                        </div>
                    </div>
                    <div class="kpi-stat-card">
                        <div class="kpi-icon" style="background: #f0fdf4; color: #16a34a;">
                            <i class="fas fa-history"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Total Setoran</div>
                            <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">{{ $quranStats['total_setoran'] ?? 0 }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">kali</span></div>
                            <div style="font-size: 0.75rem; color: #16a34a;">Setoran Baru & Murojaah</div>
                        </div>
                    </div>
                    <div class="kpi-stat-card">
                        <div class="kpi-icon" style="background: #fef3c7; color: #b45309;">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Tingkat Mutqin / Lancar</div>
                            <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">{{ $quranStats['lancar_percentage'] ?? 0 }}%</div>
                            <div style="font-size: 0.75rem; color: #b45309;">Kualitas Predikat Lancar</div>
                        </div>
                    </div>
                    <div class="kpi-stat-card">
                        <div class="kpi-icon" style="background: #ede9fe; color: #7c3aed;">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Akuntabilitas</div>
                            <div style="font-size: 1.05rem; font-weight: 800; color: #1e293b;">Format Terstandar</div>
                            <div style="font-size: 0.75rem; color: #7c3aed;">3 Tanda Tangan Pengesahan</div>
                        </div>
                    </div>
                </div>

                <!-- Table Tahfidz -->
                <div class="card" style="padding: 0; overflow: hidden; border: 1px solid #e2e8f0;">
                    <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; font-size: 1rem; color: #1e293b; font-weight: 700;">
                            <i class="fas fa-table-list" style="color: #0284c7;"></i> Rekapitulasi Capaian & Evaluasi Tahfidz
                        </h4>
                        <span style="font-size: 0.78rem; color: #64748b;">
                            Format Akuntabel SDIT AN NADZIR
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table" style="width: 100%; border-collapse: collapse; margin: 0;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 0.8rem; color: #475569;">
                                    <th style="padding: 10px 8px; text-align: center; width: 40px;">No</th>
                                    <th style="padding: 10px 8px; text-align: left; width: 85px;">NIS</th>
                                    <th style="padding: 10px 8px; text-align: left;">Nama Siswa</th>
                                    <th style="padding: 10px 8px; text-align: center; width: 60px;">Kelas</th>
                                    <th style="padding: 10px 8px; text-align: left;">Kelompok & Musyrif</th>
                                    <th style="padding: 10px 8px; text-align: left;">Target</th>
                                    <th style="padding: 10px 8px; text-align: left;">Setoran Terakhir</th>
                                    <th style="padding: 10px 8px; text-align: center; width: 90px;">Frekuensi</th>
                                    <th style="padding: 10px 8px; text-align: center;">Mutu Hafalan</th>
                                    <th style="padding: 10px 8px; text-align: center; width: 90px;">Sima'an</th>
                                    <th style="padding: 10px 8px; text-align: center; width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $idx => $student)
                                    @php
                                        $grp = $student->mentoringGroups->first();
                                        $latest = $student->latest_record;
                                        $qCounts = $student->quality_counts ?? ['lancar' => 0, 'cukup' => 0, 'perlu_perbaikan' => 0];
                                        $att = $student->attendance_summary ?? ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];
                                    @endphp
                                    <tr style="border-bottom: 1px solid #f1f5f9; font-size: 0.85rem;">
                                        <td style="padding: 10px 8px; text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                                        <td style="padding: 10px 8px; font-family: monospace; font-size: 0.8rem; color: #64748b;">{{ $student->nis ?? '-' }}</td>
                                        <td style="padding: 10px 8px;">
                                            <strong style="color: #1e293b;">{{ $student->name }}</strong>
                                            @if($student->gender)
                                                <span style="font-size: 0.7rem; padding: 1px 5px; border-radius: 4px; margin-left: 2px; background: {{ $student->gender == 'L' ? '#e0f2fe; color: #0369a1;' : '#fce7f3; color: #be185d;' }}">
                                                    {{ $student->gender }}
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 10px 8px; text-align: center;">
                                            <span style="display: inline-block; padding: 2px 7px; border-radius: 6px; background: #f1f5f9; font-weight: 700; font-size: 0.78rem; color: #334155;">
                                                {{ $student->class }}
                                            </span>
                                        </td>
                                        <td style="padding: 10px 8px;">
                                            @if($grp)
                                                <div style="font-weight: 600; color: #0f172a; font-size: 0.85rem;">{{ $grp->name }}</div>
                                                <div style="font-size: 0.75rem; color: #0284c7;">
                                                    <i class="fas fa-user-tie"></i> {{ $grp->mentor_name ?? 'Musyrif' }}
                                                </div>
                                            @else
                                                <span style="color: #ef4444; font-size: 0.78rem; font-style: italic;">
                                                    <i class="fas fa-exclamation-triangle"></i> Belum Masuk Kelompok
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 10px 8px;">
                                            @if($grp && $grp->target_level)
                                                <span style="display: inline-block; padding: 2px 7px; border-radius: 6px; font-weight: 600; font-size: 0.78rem; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">
                                                    {{ $grp->target_level }}
                                                </span>
                                            @else
                                                <span style="color: #94a3b8; font-size: 0.8rem;">-</span>
                                            @endif
                                        </td>
                                        <td style="padding: 10px 8px;">
                                            @if($latest)
                                                <div style="font-weight: 600; color: #1e293b; font-size: 0.85rem;">
                                                    {{ $latest->surah_name ?? 'Surah' }}
                                                    @if($latest->ayah_start)
                                                        <span style="font-size: 0.78rem; color: #64748b;">(Ayat {{ $latest->ayah_start }}{{ $latest->ayah_end && $latest->ayah_end != $latest->ayah_start ? '-'.$latest->ayah_end : '' }})</span>
                                                    @endif
                                                </div>
                                                <div style="font-size: 0.72rem; color: #64748b;">
                                                    <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($latest->session_date)->format('d/m/Y') }}
                                                    <span style="padding: 1px 5px; border-radius: 3px; font-size: 0.68rem; background: {{ $latest->type == 'setoran_baru' ? '#dcfce7; color: #15803d;' : '#fef3c7; color: #b45309;' }}">
                                                        {{ $latest->type == 'setoran_baru' ? 'Baru' : 'Murojaah' }}
                                                    </span>
                                                </div>
                                            @else
                                                <span style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">Belum ada setoran</span>
                                            @endif
                                        </td>
                                        <td style="padding: 10px 8px; text-align: center;">
                                            <div style="font-weight: 700; color: #1e293b; font-size: 0.85rem;">{{ $student->total_records_count ?? 0 }}x</div>
                                            <div style="font-size: 0.7rem; color: #64748b;">
                                                <span style="color: #15803d;" title="Setoran Baru">{{ $student->baru_count ?? 0 }}B</span> / 
                                                <span style="color: #b45309;" title="Murojaah">{{ $student->murojaah_count ?? 0 }}M</span>
                                            </div>
                                        </td>
                                        <td style="padding: 10px 8px; text-align: center;">
                                            <div style="display: flex; gap: 4px; justify-content: center; flex-wrap: wrap;">
                                                @if(($qCounts['lancar'] ?? 0) > 0)
                                                    <span style="font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; background: #dcfce7; color: #15803d; font-weight: 700;" title="Lancar/Mutqin">
                                                        L: {{ $qCounts['lancar'] }}
                                                    </span>
                                                @endif
                                                @if(($qCounts['cukup'] ?? 0) > 0)
                                                    <span style="font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; background: #fef3c7; color: #b45309; font-weight: 700;" title="Cukup">
                                                        C: {{ $qCounts['cukup'] }}
                                                    </span>
                                                @endif
                                                @if(($qCounts['perlu_perbaikan'] ?? 0) > 0)
                                                    <span style="font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; background: #fee2e2; color: #991b1b; font-weight: 700;" title="Perlu Perbaikan">
                                                        P: {{ $qCounts['perlu_perbaikan'] }}
                                                    </span>
                                                @endif
                                                @if(($student->total_records_count ?? 0) == 0)
                                                    <span style="color: #94a3b8; font-size: 0.75rem;">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td style="padding: 10px 8px; text-align: center; font-size: 0.75rem;">
                                            <span style="color: #15803d; font-weight: 700;">H:{{ $att['hadir'] }}</span> 
                                            <span style="color: #b45309;">S:{{ $att['sakit'] }}</span> 
                                            <span style="color: #0284c7;">I:{{ $att['izin'] }}</span> 
                                            <span style="color: #dc2626;">A:{{ $att['alpha'] }}</span>
                                        </td>
                                        <td style="padding: 10px 8px; text-align: center;">
                                            @if($grp)
                                                <div style="display: flex; gap: 4px; justify-content: center;">
                                                    <a href="{{ route('tahfidz.student-card', ['group' => $grp->id, 'student' => $student->id]) }}" class="btn-action-header btn-blue" style="padding: 3px 8px; font-size: 0.75rem; text-decoration: none;" title="Lihat Kartu Hafalan">
                                                        <i class="fas fa-id-card"></i>
                                                    </a>
                                                    <a href="{{ route('tahfidz.print-card', ['group' => $grp->id, 'student' => $student->id]) }}" target="_blank" class="btn-action-header btn-dark" style="padding: 3px 8px; font-size: 0.75rem; text-decoration: none;" title="Cetak Kartu Siswa">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <a href="{{ route('mentoring.management', ['tab' => 'unassigned', 'unassigned_type' => 'tahfidz', 'year_id' => $selectedYearId]) }}" class="btn-action-header btn-orange" style="padding: 3px 8px; font-size: 0.75rem; text-decoration: none;" title="Petakan Siswa">
                                                    <i class="fas fa-user-plus"></i> Petakan
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="card" style="text-align: center; color: #64748b; padding: 3.5rem 1.5rem;">
                    <i class="fas fa-quran" style="font-size: 2.8rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                    <h4 style="color: #334155; margin-bottom: 6px;">Tidak Ada Data Siswa Tahfidz</h4>
                    <p style="font-size: 0.9rem; margin: 0; max-width: 500px; margin: 0 auto;">Tidak ditemukan siswa yang sesuai dengan filter kelas atau kelompok Tahfidz yang dipilih pada Tahun Pelajaran ini.</p>
                </div>
            @endif
        @else
            <div class="card" style="text-align: center; color: #64748b; padding: 3.5rem 1.5rem;">
                <i class="fas fa-search" style="font-size: 2.8rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                <h4 style="color: #334155; margin-bottom: 6px;">Pilih Filter Laporan Tahfidz</h4>
                <p style="font-size: 0.9rem; margin: 0; max-width: 500px; margin: 0 auto;">Silakan pilih Kelas atau Kelompok Tahfidz di atas, lalu klik <strong>Tampilkan</strong> untuk memuat data rekapitulasi setoran hafalan siswa.</p>
            </div>
        @endif

    {{-- ========================================================================= --}}
    {{-- TAB 3: LAPORAN EKSTRAKURIKULER (ORIGINAL)                                  --}}
    {{-- ========================================================================= --}}
    @else

        <div class="card filter-section no-print">
            <h3 style="margin-bottom: 1.5rem;">Filter Laporan Ekstrakurikuler</h3>
            <form action="{{ route('reports.index') }}" method="GET">
                <input type="hidden" name="tab" value="eskul">
                <div class="form-row">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: #888;">Tahun Pelajaran</label>
                        <select name="year_id" class="form-control" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" 
                                    {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: #888;">Periode Laporan</label>
                        <select name="period" class="form-control">
                            <option value="all" {{ $selectedPeriod == 'all' ? 'selected' : '' }}>Satu Tahun (Sm 1 & 2)</option>
                            <option value="1" {{ $selectedPeriod == '1' ? 'selected' : '' }}>Semester 1</option>
                            <option value="2" {{ $selectedPeriod == '2' ? 'selected' : '' }}>Semester 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: #888;">Kelas</label>
                        <select name="class" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @if(in_array(Auth::user()->role, ['admin', 'headmaster']))
                                <option value="ALL" {{ $selectedClass == 'ALL' ? 'selected' : '' }} style="font-weight: bold; color: #2563eb;">
                                    🌟 -- CETAK SEMUA KELAS (LAPORAN KESELURAHAN) --
                                </option>
                            @endif
                            @foreach($classes as $class)
                                <option value="{{ $class }}" {{ $selectedClass == $class ? 'selected' : '' }}>Kelas {{ $class }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="padding-bottom: 1px;">
                        <button type="submit" class="btn-action-header btn-blue" style="height: 48px; width: 100%; justify-content: center;">Tampilkan</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Special Feature: Calistung Export -->
        <div class="card no-print" style="margin-bottom: 20px; border-left: 5px solid #2ecc71;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h4 style="margin-bottom: 5px;"><i class="fas fa-graduation-cap" style="color: #2ecc71;"></i> Lulusan Calistung</h4>
                    <p style="font-size: 0.9rem; color: #666; margin: 0;">Unduh data siswa yang mendapatkan nilai A pada Calistung (Membaca/Menulis/Berhitung).</p>
                </div>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('reports.print-calistung-graduates') }}" target="_blank" class="btn-action-header btn-orange">
                        <i class="fas fa-print"></i> Cetak Laporan
                    </a>
                    <a href="{{ route('reports.calistung-graduates') }}" class="btn-action-header btn-green">
                        <i class="fas fa-file-excel"></i> Download Excel
                    </a>
                </div>
            </div>
        </div>

        @if($selectedClass === 'ALL')
        <!-- Action Buttons for ALL Classes -->
        <div class="no-print" style="margin: 20px 0; display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
            <a href="{{ route('reports.print-all-recap', ['year_id' => $selectedYearId, 'period' => $selectedPeriod]) }}" target="_blank" class="btn-action-header btn-green">
                <i class="fas fa-print"></i> Cetak Rekap Wali Kelas (Semua Kelas)
            </a>
            <a href="{{ route('reports.print-all', ['year_id' => $selectedYearId, 'period' => $selectedPeriod]) }}" target="_blank" class="btn-action-header btn-dark" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                <i class="fas fa-print"></i> Cetak Laporan Keseluruhan (All Classes)
            </a>
        </div>

        <div class="card no-print" style="border-top: 4px solid #2563eb; padding: 24px; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 54px; height: 54px; border-radius: 14px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                    <i class="fas fa-print"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b;">Mode Cetak Laporan Keseluruhan (All Classes)</h3>
                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.92rem;">
                        Sistem siap mencetak laporan untuk seluruh kelas <strong>({{ count($allClassesData) }} Kelas)</strong> pada Tahun Ajaran <strong>{{ \App\Models\AcademicYear::find($selectedYearId)->name ?? '-' }}</strong>. Setiap kelas didesain A4 terpaginasi secara rapi.
                    </p>
                </div>
            </div>
            <div style="margin-top: 20px; display: flex; gap: 12px; justify-content: flex-end;">
                <a href="{{ route('reports.print-all', ['year_id' => $selectedYearId, 'period' => $selectedPeriod]) }}" target="_blank" class="btn-action-header btn-blue" style="padding: 10px 20px; font-weight: 700;">
                    <i class="fas fa-external-link-alt"></i> Buka Jendela Cetak Masal (A4)
                </a>
            </div>
        </div>
        @elseif($selectedClass && isset($students))
        
        <!-- Action Buttons -->
        <div class="no-print" style="margin: 20px 0; display: flex; gap: 8px; justify-content: flex-end;">
             <a href="{{ route('reports.print-recap-class', ['class' => $selectedClass, 'year_id' => $selectedYearId, 'period' => $selectedPeriod]) }}" target="_blank" class="btn-action-header btn-green">
                <i class="fas fa-print"></i> Cetak Rekap (Wali Kelas)
            </a>
             <button onclick="togglePreview()" id="btnPreview" class="btn-action-header btn-blue">
                <i class="fas fa-eye"></i> Mode Preview Cetak
            </button>
            <a href="{{ route('reports.print-full', ['class' => $selectedClass, 'year_id' => $selectedYearId, 'period' => $selectedPeriod]) }}" target="_blank" class="btn-action-header btn-dark">
                <i class="fas fa-print"></i> Cetak Laporan (Nilai & Absensi)
            </a>
        </div>

        <!-- Printable Content (Container for A4 preview) -->
        <div id="printableArea" class="card" style="position: relative;">
            <!-- KOP SURAT MODERN (Blue Line Style) -->
            <div class="print-header" style="font-family: 'Times New Roman', serif; margin-bottom: 20px;">
                <table style="border: none; width: 100%;">
                    <tr style="border: none;">
                        <td style="border: none; width: 60px; vertical-align: middle;">
                            <img src="{{ asset('logo.png') }}" style="height: 50px; width: auto;">
                        </td>
                        <td style="border: none; text-align: left; vertical-align: middle; padding-left: 15px;">
                            <h2 style="margin: 0; font-size: 16pt; font-weight: bold; color: #333;">SDIT AN NADZIR</h2>
                            <p style="margin: 0; font-size: 10pt; color: #666; text-transform: uppercase; letter-spacing: 1px;">Laporan Ekstrakurikuler</p>
                            @php
                                $semesterText = 'Semester 1 & 2';
                                if ($selectedPeriod == '1') $semesterText = 'Semester 1';
                                if ($selectedPeriod == '2') $semesterText = 'Semester 2';
                            @endphp
                            <p style="margin: 0; font-size: 10pt; color: #888;">Kelas {{ $selectedClass }} &nbsp;|&nbsp; {{ $semesterText }} &nbsp;|&nbsp; Tahun Pelajaran {{ \App\Models\AcademicYear::find($selectedYearId)->name ?? '-' }}</p>
                        </td>
                    </tr>
                </table>
                <div style="border-bottom: 3px solid #3498db; margin-top: 10px;"></div>
            </div>

            <!-- Statistics Block (Grey Box) -->
            <div class="print-stats" style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; font-family: sans-serif; font-size: 9pt;">
                <div style="text-align: left;">
                    <strong style="display: block; font-size: 10pt; color: #333;">Statistik Kelas {{ $selectedClass }}</strong>
                    <span style="color: #666;">Total Siswa: {{ $students->count() }} orang</span>
                </div>
                <div style="text-align: center;">
                     @php
                        $totalEskuls = \App\Models\Eskul::whereHas('students', function($q) use ($selectedClass) {
                            $q->where('class', $selectedClass);
                        })->count();
                    @endphp
                    <strong style="display: block; color: #333;">Jumlah Eskul: {{ $totalEskuls }}</strong>
                </div>
                <div style="text-align: right; max-width: 40%;">
                     @php
                        $activeEskuls = \App\Models\Eskul::whereHas('students', function($q) use ($selectedClass) {
                            $q->where('class', $selectedClass);
                        })->pluck('name')->take(3);
                    @endphp
                    <span style="color: #666;">Eskul: {{ $activeEskuls->implode(', ') }}{{ $totalEskuls > 3 ? ', dll' : '' }}</span>
                </div>
            </div>

            <!-- Main Table -->
            <table style="width: 100%; border-collapse: collapse; font-family: sans-serif;">
                <thead>
                    <tr style="border-bottom: 2px solid #ddd;">
                        <th style="width: 5%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd;">No</th>
                        <th style="width: 25%; text-align: left; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd;">Nama Lengkap</th>
                        <th style="width: 15%; text-align: left; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd;">Ekstrakurikuler</th>
                        <th style="width: 10%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd; font-size: 9px;">Sm 1</th>
                        <th style="width: 10%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd; font-size: 9px;">Sm 2</th>
                        <!-- Attendance Stats -->
                        <th style="width: 4%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd; font-size: 9px;">H</th>
                        <th style="width: 4%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd; font-size: 9px;">S</th>
                        <th style="width: 4%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd; font-size: 9px;">I</th>
                        <th style="width: 4%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd; font-size: 9px;">A</th>
                        
                        <th style="width: 19%; text-align: left; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd;">Prestasi & Penghargaan</th>
                        <th style="width: 15%; text-align: left; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd;">Info Kegiatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                        @php
                            // Merge Current Eskuls + Historical Eskuls from Grades + Attendance
                            $currentEskuls = $student->eskuls;
                            $historicalEskuls = $student->grades->map(function($grade) {
                                return $grade->eskul;
                            })->filter(); // remove nulls if any grade has no eskul
                            
                            $attendanceEskuls = \App\Models\Eskul::whereIn('id', \App\Models\Attendance::where('student_id', $student->id)
                                ->where('academic_year_id', $selectedYearId)
                                ->pluck('eskul_id')
                                ->toArray())->get();
                            
                            // Combine and Unique by ID
                            $allEskuls = $currentEskuls->merge($historicalEskuls)->merge($attendanceEskuls)->unique('id')->sortBy('name');
                        @endphp

                        @if($allEskuls->isEmpty())
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td style="white-space: nowrap;">{{ $student->name }}</td>
                            <td style="color: #999;">-</td>
                            <td style="text-align: center;">-</td>
                            <td style="text-align: center;">-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        @else
                            @foreach($allEskuls as $i => $eskul)
                                @php
                                    $grade1 = $student->grades->where('eskul_id', $eskul->id)->where('type', 'sas1')->first();
                                    $grade2 = $student->grades->where('eskul_id', $eskul->id)->where('type', 'sas2')->first();

                                    // Calculate Attendance
                                    $attQuery = \App\Models\Attendance::where('student_id', $student->id)
                                        ->where('eskul_id', $eskul->id)
                                        ->where(function($q) use ($selectedYearId) {
                                            $q->where('academic_year_id', $selectedYearId)->orWhereNull('academic_year_id');
                                        });

                                    if ($selectedPeriod != 'all') {
                                        $attQuery->where(function($q) use ($selectedPeriod) {
                                            $q->where('semester', $selectedPeriod)->orWhereNull('semester');
                                        });
                                    }

                                    $attendanceCounts = $attQuery
                                        ->selectRaw('status, count(*) as count')
                                        ->groupBy('status')
                                        ->pluck('count', 'status');
                                    
                                    $h = $attendanceCounts['present'] ?? 0;
                                    $s = $attendanceCounts['sick'] ?? 0;
                                    $i_perm = $attendanceCounts['permission'] ?? 0;
                                    $a = $attendanceCounts['absent'] ?? 0;

                                    // Helper for formatting
                                    $formatScore = function($score) use ($eskul) {
                                      if (!$score) return '-';
                                      
                                      // Check if Calistung
                                      if (\Illuminate\Support\Str::contains(strtolower($eskul->name), 'calistung') || \Illuminate\Support\Str::contains(strtolower($eskul->name), 'baca')) {
                                          $data = [];
                                          
                                          // Try JSON first
                                          $json = json_decode($score, true);
                                          if (is_array($json)) {
                                              $data = $json;
                                          } else {
                                              // CUSTOM PARSING LOGIC IF NOT JSON
                                              // 1. Pipe Format "B:A | T:B | H:A"
                                              if (str_contains($score, '|')) {
                                                   $parts = explode('|', $score);
                                                   foreach ($parts as $part) {
                                                       $sub = explode(':', $part);
                                                       if (count($sub) >= 2) {
                                                           $k = strtoupper(trim($sub[0]));
                                                           $v = trim($sub[1]);
                                                           if ($k === 'B') $data['reading'] = $v;
                                                           if ($k === 'T') $data['writing'] = $v;
                                                           if ($k === 'H') $data['counting'] = $v;
                                                       }
                                                   }
                                              }
                                              
                                              // 2. Single Letter Expansion (A, B, C)
                                              elseif (preg_match('/^[A-C]$/i', trim($score))) {
                                                  $v = strtoupper(trim($score));
                                                  $data = ['reading' => $v, 'writing' => $v, 'counting' => $v];
                                              }
                                          }

                                          if (!empty($data)) {
                                              return sprintf(
                                                  '<div style="font-size: 8pt; line-height: 1.2; text-align: left; display: inline-block;">' .
                                                  '<span style="display:inline-block; width:12px; font-weight:bold; color:#e67e22;">B:</span>%s ' .
                                                  '<span style="display:inline-block; width:12px; font-weight:bold; color:#27ae60;">T:</span>%s ' .
                                                  '<span style="display:inline-block; width:12px; font-weight:bold; color:#2980b9;">H:</span>%s' .
                                                  '</div>',
                                                  $data['reading'] ?? '-',
                                                  $data['writing'] ?? '-',
                                                  $data['counting'] ?? '-'
                                              );
                                          }
                                      }

                                      // Default Standard Format
                                      $color = '#2ecc71'; // A
                                      if ($score == 'B') $color = '#3498db';
                                      if ($score == 'C') $color = '#f1c40f';
                                      if ($score == 'D') $color = '#e74c3c';

                                      return "<span style='font-weight: bold; color: {$color}; font-size: 10pt;'>{$score}</span>";
                                    };
                                @endphp
                                <tr>
                                    @if($i == 0)
                                        <td rowspan="{{ $allEskuls->count() }}" style="text-align: center; vertical-align: top; padding-top: 8px;">{{ $index + 1 }}</td>
                                        <td rowspan="{{ $allEskuls->count() }}" style="vertical-align: top; padding-top: 8px; font-weight: bold;">
                                            {{ $student->name }}
                                            <div style="font-size: 8pt; font-weight: normal; color: #888;">NIS: {{ $student->nis ?? '-' }}</div>
                                        </td>
                                    @endif
                                    <td>
                                        <strong style="color: #2c3e50;">{{ $eskul->name }}</strong>
                                    </td>
                                    
                                    {{-- Grades --}}
                                    <td style="text-align: center; background: #fafafa;">
                                        {!! $formatScore($grade1->score ?? null) !!}
                                    </td>
                                    <td style="text-align: center; background: #fafafa;">
                                        {!! $formatScore($grade2->score ?? null) !!}
                                    </td>

                                    {{-- Attendance Stats (Grey Text for Zeros) --}}
                                    <td style="text-align: center; {{ $h == 0 ? 'color: #ccc;' : 'font-weight: bold;' }}">{{ $h }}</td>
                                    <td style="text-align: center; {{ $s == 0 ? 'color: #ccc;' : 'color: #f39c12; font-weight: bold;' }}">{{ $s }}</td>
                                    <td style="text-align: center; {{ $i_perm == 0 ? 'color: #ccc;' : 'color: #3498db; font-weight: bold;' }}">{{ $i_perm }}</td>
                                    <td style="text-align: center; {{ $a == 0 ? 'color: #ccc;' : 'color: #e74c3c; font-weight: bold;' }}">{{ $a }}</td>
                                    
                                    {{-- Achievements --}}
                                    <td>
                                        @php
                                            $studentAchievements = $student->achievements()
                                                ->where('academic_year_id', $selectedYearId)
                                                ->where('eskul_id', $eskul->id);

                                            if ($selectedPeriod != 'all') {
                                                $studentAchievements->where('semester', $selectedPeriod);
                                            }

                                            $achieved = $studentAchievements->orderBy('date', 'desc')->get();
                                        @endphp
                                        
                                        @if($achieved->isEmpty())
                                            <span style="color: #ccc; font-size: 8pt;">-</span>
                                        @else
                                            <ul style="margin: 0; padding-left: 15px; font-size: 8pt; color: #2c3e50;">
                                                @foreach($achieved as $ach)
                                                    <li style="margin-bottom: 3px;">
                                                        <strong>{{ $ach->name }}</strong> 
                                                        <span style="color: #7f8c8d;">({{ $ach->level }})</span>
                                                        @if($selectedPeriod == 'all')
                                                            <span style="background: #f1c40f; color: #fff; padding: 0 4px; border-radius: 3px; font-size: 7pt;">Sm {{ $ach->semester }}</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>

                                    <td style="font-size: 8pt; color: #555;">
                                        {{ $eskul->instructor_name ?? '-' }}
                                        @if($eskul->schedule)
                                        <br><span style="color: #2980b9; font-size: 7.5pt;">{{ $eskul->schedule }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
            
            <!-- Minimal Footer -->
            <div class="print-footer" style="margin-top: 10px; font-family: sans-serif; font-size: 8pt; color: #888; text-align: right;">
                Dicetak pada: {{ date('d/m/Y H:i') }}
            </div>
        </div>
        @elseif(request('class'))
             <div class="card" style="text-align: center; color: #999; padding: 3rem;">
                <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; color: #eee;"></i>
                <p>Silakan pilih filter untuk menampilkan laporan.</p>
            </div>
        @endif

    @endif

</div>

<script>
    function togglePreview() {
        var container = document.getElementById('printableArea');
        var btn = document.getElementById('btnPreview');
        if (!container || !btn) return;
        
        if (container.classList.contains('print-preview-box')) {
            // Disable Preview
            container.classList.remove('print-preview-box');
            document.body.classList.remove('show-preview');
            btn.innerHTML = '<i class="fas fa-eye"></i> Mode Preview Cetak';
            container.style.border = '1px solid rgba(0,0,0,0.02)'; // Reset to standard card style
            
            // Hide Print elements in normal view
            var pHeader = document.querySelector('.print-header');
            var pFooter = document.querySelector('.print-footer');
            if (pHeader) pHeader.style.display = 'none';
            if (pFooter) pFooter.style.display = 'none';
        } else {
            // Enable Preview (A4 Paper Look)
            container.classList.add('print-preview-box');
            document.body.classList.add('show-preview');
            btn.innerHTML = '<i class="fas fa-times"></i> Tutup Preview';
            
            // Show Print elements in preview
            var pHeader = document.querySelector('.print-header');
            var pFooter = document.querySelector('.print-footer');
            if (pHeader) pHeader.style.display = 'block';
            if (pFooter) pFooter.style.display = 'block';
        }
    }
</script>
@endsection
