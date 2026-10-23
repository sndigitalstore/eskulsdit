@extends('layouts.app')

@section('title', 'Presensi Siswa')
@section('page-title', 'Presensi Ekstrakurikuler')

@push('styles')
<style>
    /* Reset & Container Utama */
    .presensi-select-wrapper {
        max-width: 580px;
        margin: 1.5rem auto 3rem;
        padding: 0 12px;
    }

    /* Card Utama - Override paksa padding layout */
    .card.presensi-select-card {
        padding: 0 !important;
        border-radius: 24px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        background: #ffffff;
        overflow: visible !important;
    }

    /* Header Kartu */
    .presensi-select-header {
        background: linear-gradient(180deg, #ecfdf5 0%, #ffffff 100%);
        padding: 28px 24px 20px;
        border-bottom: 1px solid #f1f5f9;
        text-align: center;
        border-top-left-radius: 22px;
        border-top-right-radius: 22px;
    }
    .presensi-select-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: #dcfce7;
        color: #059669;
        border: 1.5px solid #bbf7d0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 14px;
        box-shadow: 0 6px 16px rgba(5, 150, 105, 0.15);
    }
    .presensi-select-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 6px;
        letter-spacing: -0.3px;
    }
    .presensi-select-desc {
        font-size: 0.88rem;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }

    /* Form Body */
    .presensi-select-body {
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
        color: #059669;
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
        border-color: #059669 !important;
        box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.15) !important;
    }

    /* Tombol Utama */
    .btn-action-primary {
        width: 100%;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        border: none;
        font-size: 0.98rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 20px rgba(5, 150, 105, 0.28);
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
    }
    .btn-action-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(5, 150, 105, 0.38);
        color: #ffffff;
    }
    .btn-action-primary:active {
        transform: translateY(0);
    }

    /* Footer Rekap & Laporan */
    .presensi-select-footer {
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        padding: 22px 24px;
        text-align: center;
        border-bottom-left-radius: 22px;
        border-bottom-right-radius: 22px;
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
        border: 1.5px solid #0284c7;
        color: #0284c7;
        font-size: 0.92rem;
        font-weight: 750;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.08);
    }
    .btn-action-secondary:hover {
        background: #f0f9ff;
        border-color: #0369a1;
        color: #0369a1;
        transform: translateY(-1px);
    }

    /* Penyesuaian Khusus HP */
    @media (max-width: 640px) {
        .presensi-select-wrapper {
            margin: 0.75rem auto 2rem;
            padding: 0 6px;
        }
        .presensi-select-header {
            padding: 22px 18px 16px;
        }
        .presensi-select-icon {
            width: 48px;
            height: 48px;
            font-size: 1.35rem;
            border-radius: 14px;
            margin-bottom: 10px;
        }
        .presensi-select-title {
            font-size: 1.2rem;
        }
        .presensi-select-body {
            padding: 18px 16px;
        }
        .presensi-select-footer {
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
<div class="presensi-select-wrapper">
    <!-- Flash Success Message -->
    @if(session('success'))
        <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; color: #065f46; padding: 14px 18px; border-radius: 16px; margin-bottom: 18px; display: flex; align-items: center; gap: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.08);">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas fa-check"></i>
            </div>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Card Utama Pilih Kegiatan Presensi -->
    <div class="card presensi-select-card">
        
        <!-- Header Kartu Bernuansa Emerald Soft -->
        <div class="presensi-select-header">
            <div class="presensi-select-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h3 class="presensi-select-title">
                Input Presensi Siswa
            </h3>
            <p class="presensi-select-desc">
                Pilih ekstrakurikuler dan tanggal pertemuan untuk mulai mengisi presensi kehadiran siswa.
            </p>
        </div>

        <!-- Form Pemilihan -->
        <form action="{{ route('attendance.create') }}" method="GET" class="presensi-select-body">
            <!-- Tahun Ajaran -->
            <div class="form-group-custom">
                <label class="form-label-custom">
                    <i class="fas fa-calendar-alt"></i> Tahun Ajaran
                </label>
                <select name="academic_year_id" class="custom-select-modern" data-theme="emerald" data-icon="fas fa-calendar-alt" data-placeholder="Pilih Tahun Ajaran" required>
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
                <select name="eskul_id" class="custom-select-modern" data-theme="emerald" data-icon="fas fa-shapes" data-placeholder="-- Pilih Kegiatan Ekstrakurikuler --" data-search-placeholder="Ketik nama eskul..." required>
                    <option value="">-- Pilih Kegiatan Ekstrakurikuler --</option>
                    @foreach($eskuls as $eskul)
                        <option value="{{ $eskul->id }}" data-badge="{{ $eskul->schedule ?: ($eskul->instructor_name ?: '') }}">
                            {{ $eskul->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Kegiatan -->
            <div class="form-group-custom" style="margin-bottom: 24px;">
                <label class="form-label-custom">
                    <i class="far fa-calendar-check"></i> Tanggal Pertemuan
                </label>
                <input type="date" name="date" class="input-control-custom" value="{{ date('Y-m-d') }}" required>
            </div>

            <!-- Tombol Lanjut ke Presensi (Solid Emerald) -->
            <button type="submit" class="btn-action-primary">
                <span>Lanjut ke Lembar Presensi</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <!-- Bagian Rekap & Laporan Bawah -->
        <div class="presensi-select-footer">
            <span class="footer-label">
                <i class="fas fa-chart-line" style="color: #0284c7;"></i> Rekapitulasi Presensi
            </span>
            <p class="footer-desc">
                Lihat akumulasi kehadiran siswa seluruh pertemuan dan cetak berkas laporan
            </p>

            <form id="report-form" action="{{ route('attendance.report') }}" method="GET">
                <input type="hidden" name="academic_year_id" id="report-year-id">
                <input type="hidden" name="eskul_id" id="report-eskul-id">
                <button type="button" onclick="submitReport()" class="btn-action-secondary">
                    <i class="fas fa-file-invoice"></i>
                    <span>Buka Rekapitulasi & Cetak Laporan</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Tips Singkat -->
    <div style="margin-top: 16px; text-align: center;">
        <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #64748b; background: rgba(255, 255, 255, 0.8); padding: 6px 14px; border-radius: 20px; border: 1px solid #e2e8f0;">
            <i class="fas fa-lightbulb" style="color: #f59e0b;"></i>
            Presensi dapat diinput fleksibel melalui HP atau Laptop secara instan.
        </span>
    </div>
</div>

<script>
    function submitReport() {
        var mainYear = document.querySelector('select[name="academic_year_id"]').value;
        var mainEskul = document.querySelector('select[name="eskul_id"]').value;

        if (!mainEskul) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Ekstrakurikuler',
                text: 'Silakan pilih eskul terlebih dahulu pada opsi di atas untuk melihat rekapitulasi.',
                confirmButtonColor: '#059669',
                confirmButtonText: 'Baik, Saya Pilih'
            });
            return;
        }

        document.getElementById('report-year-id').value = mainYear;
        document.getElementById('report-eskul-id').value = mainEskul;
        document.getElementById('report-form').submit();
    }
</script>
@endsection
