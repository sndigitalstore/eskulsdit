@extends('layouts.app')

@section('title', 'Penilaian Siswa')
@section('page-title', 'Penilaian Ekstrakurikuler')

@push('styles')
<style>
    /* Reset & Container Utama */
    .grades-select-wrapper {
        max-width: 580px;
        margin: 1.5rem auto 3rem;
        padding: 0 12px;
    }

    /* Card Utama - Override paksa padding layout */
    .card.grades-select-card {
        padding: 0 !important;
        border-radius: 24px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        background: #ffffff;
        overflow: hidden;
    }

    /* Header Kartu */
    .grades-select-header {
        background: linear-gradient(180deg, #f5f3ff 0%, #ffffff 100%);
        padding: 28px 24px 20px;
        border-bottom: 1px solid #f1f5f9;
        text-align: center;
    }
    .grades-select-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: #ede9fe;
        color: #7c3aed;
        border: 1.5px solid #ddd6fe;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 14px;
        box-shadow: 0 6px 16px rgba(124, 58, 237, 0.15);
    }
    .grades-select-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 6px;
        letter-spacing: -0.3px;
    }
    .grades-select-desc {
        font-size: 0.88rem;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }

    /* Form Body */
    .grades-select-body {
        padding: 24px;
    }
    .form-group-custom {
        margin-bottom: 20px;
    }
    .form-label-custom {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        font-weight: 750;
        color: #334155;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .form-label-custom i {
        color: #7c3aed;
        font-size: 0.95rem;
    }
    .input-control-custom {
        width: 100% !important;
        height: 50px !important;
        border-radius: 14px !important;
        border: 1.5px solid #cbd5e1 !important;
        padding: 0 16px !important;
        font-size: 0.94rem !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        background-color: #ffffff !important;
        transition: all 0.2s ease !important;
        outline: none !important;
        box-sizing: border-box !important;
        display: block !important;
    }
    .input-control-custom:focus {
        border-color: #7c3aed !important;
        box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.15) !important;
    }

    /* Tombol Utama */
    .btn-action-primary {
        width: 100%;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        color: #ffffff;
        border: none;
        font-size: 0.98rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 20px rgba(109, 40, 217, 0.28);
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
    }
    .btn-action-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(109, 40, 217, 0.38);
        color: #ffffff;
    }
    .btn-action-primary:active {
        transform: translateY(0);
    }

    /* Footer Rekap & Laporan */
    .grades-select-footer {
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        padding: 22px 24px;
        text-align: center;
    }
    .footer-label {
        font-size: 0.8rem;
        font-weight: 750;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-bottom: 4px;
    }
    .footer-desc {
        font-size: 0.82rem;
        color: #94a3b8;
        margin: 0 0 14px;
    }
    .btn-action-secondary {
        width: 100%;
        height: 48px;
        border-radius: 14px;
        background: #ffffff;
        border: 1.5px solid #db2777;
        color: #db2777;
        font-size: 0.92rem;
        font-weight: 750;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(219, 39, 119, 0.08);
    }
    .btn-action-secondary:hover {
        background: #fdf2f8;
        border-color: #be185d;
        color: #be185d;
        transform: translateY(-1px);
    }

    /* Penyesuaian Khusus HP */
    @media (max-width: 640px) {
        .grades-select-wrapper {
            margin: 0.75rem auto 2rem;
            padding: 0 6px;
        }
        .grades-select-header {
            padding: 22px 18px 16px;
        }
        .grades-select-icon {
            width: 48px;
            height: 48px;
            font-size: 1.35rem;
            border-radius: 14px;
            margin-bottom: 10px;
        }
        .grades-select-title {
            font-size: 1.2rem;
        }
        .grades-select-body {
            padding: 18px 16px;
        }
        .grades-select-footer {
            padding: 18px 16px;
        }
        .input-control-custom {
            height: 48px !important;
            font-size: 0.9rem !important;
        }
        .btn-action-primary {
            height: 48px;
            font-size: 0.94rem;
        }
        .btn-action-secondary {
            height: 46px;
            font-size: 0.88rem;
        }
    }
</style>
@endpush

@section('content')
<div class="grades-select-wrapper">
    <!-- Flash Success Message -->
    @if(session('success'))
        <div style="background: #f5f3ff; border: 1.5px solid #ddd6fe; color: #5b21b6; padding: 14px 18px; border-radius: 16px; margin-bottom: 18px; display: flex; align-items: center; gap: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.08);">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #7c3aed; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas fa-check"></i>
            </div>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Card Utama Pilih Kriteria Nilai -->
    <div class="card grades-select-card">
        
        <!-- Header Kartu Bernuansa Ungu/Indigo Soft -->
        <div class="grades-select-header">
            <div class="grades-select-icon">
                <i class="fas fa-star"></i>
            </div>
            <h3 class="grades-select-title">
                Input Penilaian Siswa
            </h3>
            <p class="grades-select-desc">
                Pilih kriteria penilaian, eskul, dan periode untuk mulai menginput capaian siswa.
            </p>
        </div>

        <!-- Form Pemilihan Kriteria -->
        <form action="{{ route('grades.create') }}" method="GET" class="grades-select-body">
            <!-- Tahun Ajaran -->
            <div class="form-group-custom">
                <label class="form-label-custom">
                    <i class="fas fa-calendar-alt"></i> Tahun Ajaran
                </label>
                <select name="academic_year_id" class="input-control-custom" required>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ ($activeYear && $activeYear->id == $year->id) ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_active ? '— (Tahun Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pilihan Eskul -->
            <div class="form-group-custom">
                <label class="form-label-custom">
                    <i class="fas fa-shapes"></i> Ekstrakurikuler
                </label>
                <select name="eskul_id" class="input-control-custom" id="eskul-select" required>
                    <option value="">-- Pilih Kegiatan Ekstrakurikuler --</option>
                    @foreach($eskuls as $eskul)
                        <option value="{{ $eskul->id }}" {{ request('eskul_id') == $eskul->id ? 'selected' : '' }}>{{ $eskul->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jenis Penilaian -->
            <div class="form-group-custom">
                <label class="form-label-custom">
                    <i class="fas fa-clipboard-list"></i> Jenis Penilaian
                </label>
                <select name="type" class="input-control-custom" id="type-select" required onchange="toggleDate()">
                    <option value="daily">Nilai Harian / Per Pertemuan</option>
                    <option value="sas1">Nilai Akhir Semester 1 (SAS 1)</option>
                    <option value="sas2">Nilai Akhir Semester 2 (SAS 2)</option>
                </select>
            </div>

            <!-- Tanggal Pertemuan (Hanya untuk Harian) -->
            <div class="form-group-custom" id="date-group" style="margin-bottom: 24px;">
                <label class="form-label-custom">
                    <i class="far fa-calendar-check"></i> Tanggal Penilaian Harian
                </label>
                <input type="date" name="date" class="input-control-custom" value="{{ date('Y-m-d') }}">
            </div>

            <!-- Tombol Mulai Penilaian (Solid Purple/Indigo) -->
            <button type="submit" class="btn-action-primary">
                <span>Mulai Lembar Penilaian</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <!-- Bagian Rekap & Laporan Bawah -->
        <div class="grades-select-footer">
            <span class="footer-label">
                <i class="fas fa-chart-line" style="color: #db2777;"></i> Rekapitulasi Nilai
            </span>
            <p class="footer-desc">
                Lihat akumulasi nilai siswa dan cetak lembar raport ekstrakurikuler
            </p>

            <form id="report-form" action="{{ route('grades.report') }}" method="GET">
                <input type="hidden" name="eskul_id" id="report-eskul-id">
                <input type="hidden" name="academic_year_id" id="report-year-id">
                <input type="hidden" name="semester" id="report-semester">
                <button type="button" onclick="submitReport()" class="btn-action-secondary">
                    <i class="fas fa-file-invoice"></i>
                    <span>Buka Rekapitulasi & Raport Nilai</span>
                </button>
            </form>

            @if(auth()->user()->role == 'admin')
            <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed #e2e8f0;">
                <a href="{{ route('grades.import') }}" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; color: #0284c7; text-decoration: none; font-size: 0.86rem; font-weight: 700; background: #f0f9ff; padding: 8px 16px; border-radius: 20px; border: 1px solid #bae6fd; width: 100%; box-sizing: border-box;">
                    <i class="fas fa-file-excel" style="color: #059669;"></i>
                    <span>Import Nilai Masal via Excel</span>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Tips Singkat -->
    <div style="margin-top: 16px; text-align: center;">
        <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #64748b; background: rgba(255, 255, 255, 0.8); padding: 6px 14px; border-radius: 20px; border: 1px solid #e2e8f0;">
            <i class="fas fa-lightbulb" style="color: #f59e0b;"></i>
            Penilaian harian dapat langsung diinput sesuai kehadiran siswa per pertemuan.
        </span>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleDate() {
        var type = document.getElementById('type-select').value;
        var dateGroup = document.getElementById('date-group');
        if (type === 'daily') {
            dateGroup.style.display = 'block';
        } else {
            dateGroup.style.display = 'none';
        }
    }

    // Sync report eskul id
    document.getElementById('eskul-select').addEventListener('change', function() {
        document.getElementById('report-eskul-id').value = this.value;
    });

    function submitReport() {
        var eskulSelect = document.getElementById('eskul-select');
        var eskulId = eskulSelect.value;
        if (!eskulId) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Ekstrakurikuler',
                text: 'Silakan pilih eskul terlebih dahulu pada opsi di atas untuk melihat rekapitulasi nilai.',
                confirmButtonColor: '#7c3aed',
                confirmButtonText: 'Baik, Saya Pilih'
            });
            return;
        }

        // Sync values
        var yearId = document.querySelector('select[name="academic_year_id"]').value;
        document.getElementById('report-year-id').value = yearId;
        document.getElementById('report-eskul-id').value = eskulId;
        document.getElementById('report-form').submit();
    }
</script>
@endpush
