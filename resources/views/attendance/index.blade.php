@extends('layouts.app')

@section('title', 'Presensi Siswa')
@section('page-title', 'Presensi Ekstrakurikuler')

@section('content')
<div style="max-width: 620px; margin: 1rem auto 2.5rem;">
    <!-- Flash Success Message -->
    @if(session('success'))
        <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; color: #065f46; padding: 14px 18px; border-radius: 14px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.08);">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas fa-check"></i>
            </div>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Card Utama Pilih Kegiatan Presensi -->
    <div class="card" style="background: #ffffff; border-radius: 20px; border: 1.5px solid #e2e8f0; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05); overflow: hidden; padding: 0;">
        
        <!-- Header Kartu Bernuansa Emerald Soft -->
        <div style="background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%); padding: 28px 28px 18px; border-bottom: 1px solid #f1f5f9; text-align: center;">
            <div style="width: 54px; height: 54px; border-radius: 16px; background: #dcfce7; color: #059669; border: 1.5px solid #bbf7d0; display: inline-flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.12);">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 6px; letter-spacing: -0.3px;">
                Input Presensi Siswa
            </h3>
            <p style="font-size: 0.88rem; color: #64748b; margin: 0; line-height: 1.4;">
                Pilih ekstrakurikuler dan tanggal pertemuan untuk mulai mengisi presensi kehadiran santri.
            </p>
        </div>

        <!-- Form Pemilihan -->
        <form action="{{ route('attendance.create') }}" method="GET" style="padding: 24px 28px 20px;">
            <!-- Tahun Ajaran -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.86rem; font-weight: 700; color: #334155; margin-bottom: 8px;">
                    <i class="fas fa-calendar-alt" style="color: #059669;"></i> Tahun Ajaran
                </label>
                <div style="position: relative;">
                    <select name="academic_year_id" class="form-control" required style="width: 100%; height: 48px; border-radius: 12px; border: 1.5px solid #cbd5e1; padding: 0 16px; font-size: 0.92rem; font-weight: 600; color: #1e293b; background: #ffffff; transition: all 0.2s; outline: none;">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ ($activeYear && $activeYear->id == $year->id) ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '— (Tahun Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Pilihan Eskul -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.86rem; font-weight: 700; color: #334155; margin-bottom: 8px;">
                    <i class="fas fa-shapes" style="color: #059669;"></i> Ekstrakurikuler
                </label>
                <div style="position: relative;">
                    <select name="eskul_id" class="form-control" required style="width: 100%; height: 48px; border-radius: 12px; border: 1.5px solid #cbd5e1; padding: 0 16px; font-size: 0.92rem; font-weight: 600; color: #1e293b; background: #ffffff; transition: all 0.2s; outline: none;">
                        <option value="">-- Pilih Kegiatan Ekstrakurikuler --</option>
                        @foreach($eskuls as $eskul)
                            <option value="{{ $eskul->id }}">{{ $eskul->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tanggal Kegiatan -->
            <div class="form-group" style="margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.86rem; font-weight: 700; color: #334155; margin-bottom: 8px;">
                    <i class="far fa-calendar-check" style="color: #059669;"></i> Tanggal Pertemuan
                </label>
                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required style="width: 100%; height: 48px; border-radius: 12px; border: 1.5px solid #cbd5e1; padding: 0 16px; font-size: 0.92rem; font-weight: 600; color: #1e293b; background: #ffffff; transition: all 0.2s; outline: none;">
            </div>

            <!-- Tombol Lanjut ke Presensi (Solid Emerald) -->
            <button type="submit" class="btn-submit" style="width: 100%; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; border: none; font-size: 0.96rem; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 8px 18px rgba(5, 150, 105, 0.25); cursor: pointer; transition: all 0.25s ease;">
                <span>Lanjut ke Lembar Presensi</span>
                <i class="fas fa-arrow-right" style="transition: transform 0.2s;"></i>
            </button>
        </form>

        <!-- Bagian Rekap & Laporan Bawah -->
        <div style="background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 22px 28px; text-align: center;">
            <div style="margin-bottom: 12px;">
                <span style="font-size: 0.82rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fas fa-chart-line" style="color: #0284c7; margin-right: 4px;"></i> Rekapitulasi Presensi
                </span>
                <p style="margin: 4px 0 0; font-size: 0.82rem; color: #94a3b8;">
                    Lihat akumulasi kehadiran santri seluruh pertemuan dan cetak berkas
                </p>
            </div>

            <form id="report-form" action="{{ route('attendance.report') }}" method="GET">
                <input type="hidden" name="academic_year_id" id="report-year-id">
                <input type="hidden" name="eskul_id" id="report-eskul-id">
                <button type="button" onclick="submitReport()" style="width: 100%; height: 46px; border-radius: 12px; background: #ffffff; border: 1.5px solid #0284c7; color: #0284c7; font-size: 0.9rem; font-weight: 750; display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 6px rgba(2, 132, 199, 0.08);">
                    <i class="fas fa-file-invoice"></i>
                    <span>Buka Rekapitulasi & Cetak Laporan</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Tips Singkat -->
    <div style="margin-top: 16px; text-align: center;">
        <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #64748b; background: rgba(255, 255, 255, 0.7); padding: 6px 14px; border-radius: 20px; border: 1px solid #e2e8f0;">
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
