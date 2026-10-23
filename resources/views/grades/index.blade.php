@extends('layouts.app')

@section('title', 'Penilaian Siswa')
@section('page-title', 'Penilaian Ekstrakurikuler')

@section('content')
<div style="max-width: 620px; margin: 1rem auto 2.5rem;">
    <!-- Flash Success Message -->
    @if(session('success'))
        <div style="background: #f5f3ff; border: 1.5px solid #ddd6fe; color: #5b21b6; padding: 14px 18px; border-radius: 14px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.08);">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #7c3aed; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas fa-check"></i>
            </div>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Card Utama Pilih Kriteria Nilai -->
    <div class="card" style="background: #ffffff; border-radius: 20px; border: 1.5px solid #e2e8f0; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05); overflow: hidden; padding: 0;">
        
        <!-- Header Kartu Bernuansa Ungu/Indigo Soft -->
        <div style="background: linear-gradient(180deg, #f5f3ff 0%, #ffffff 100%); padding: 28px 28px 18px; border-bottom: 1px solid #f1f5f9; text-align: center;">
            <div style="width: 54px; height: 54px; border-radius: 16px; background: #ede9fe; color: #7c3aed; border: 1.5px solid #ddd6fe; display: inline-flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.12);">
                <i class="fas fa-star"></i>
            </div>
            <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 6px; letter-spacing: -0.3px;">
                Input Penilaian Siswa
            </h3>
            <p style="font-size: 0.88rem; color: #64748b; margin: 0; line-height: 1.4;">
                Pilih kriteria penilaian, eskul, dan periode untuk mulai menginput capaian santri.
            </p>
        </div>

        <!-- Form Pemilihan Kriteria -->
        <form action="{{ route('grades.create') }}" method="GET" style="padding: 24px 28px 20px;">
            <!-- Tahun Ajaran -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.86rem; font-weight: 700; color: #334155; margin-bottom: 8px;">
                    <i class="fas fa-calendar-alt" style="color: #7c3aed;"></i> Tahun Ajaran
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
                    <i class="fas fa-shapes" style="color: #7c3aed;"></i> Ekstrakurikuler
                </label>
                <div style="position: relative;">
                    <select name="eskul_id" class="form-control" id="eskul-select" required style="width: 100%; height: 48px; border-radius: 12px; border: 1.5px solid #cbd5e1; padding: 0 16px; font-size: 0.92rem; font-weight: 600; color: #1e293b; background: #ffffff; transition: all 0.2s; outline: none;">
                        <option value="">-- Pilih Kegiatan Ekstrakurikuler --</option>
                        @foreach($eskuls as $eskul)
                            <option value="{{ $eskul->id }}" {{ request('eskul_id') == $eskul->id ? 'selected' : '' }}>{{ $eskul->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Jenis Penilaian -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.86rem; font-weight: 700; color: #334155; margin-bottom: 8px;">
                    <i class="fas fa-clipboard-list" style="color: #7c3aed;"></i> Jenis Penilaian
                </label>
                <div style="position: relative;">
                    <select name="type" class="form-control" id="type-select" required onchange="toggleDate()" style="width: 100%; height: 48px; border-radius: 12px; border: 1.5px solid #cbd5e1; padding: 0 16px; font-size: 0.92rem; font-weight: 600; color: #1e293b; background: #ffffff; transition: all 0.2s; outline: none;">
                        <option value="daily">Nilai Harian / Per Pertemuan</option>
                        <option value="sas1">Nilai Akhir Semester 1 (SAS 1)</option>
                        <option value="sas2">Nilai Akhir Semester 2 (SAS 2)</option>
                    </select>
                </div>
            </div>

            <!-- Tanggal Pertemuan (Hanya untuk Harian) -->
            <div class="form-group" id="date-group" style="margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.86rem; font-weight: 700; color: #334155; margin-bottom: 8px;">
                    <i class="far fa-calendar-check" style="color: #7c3aed;"></i> Tanggal Penilaian Harian
                </label>
                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" style="width: 100%; height: 48px; border-radius: 12px; border: 1.5px solid #cbd5e1; padding: 0 16px; font-size: 0.92rem; font-weight: 600; color: #1e293b; background: #ffffff; transition: all 0.2s; outline: none;">
            </div>

            <!-- Tombol Mulai Penilaian (Solid Purple/Indigo) -->
            <button type="submit" class="btn-submit" style="width: 100%; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); color: #ffffff; border: none; font-size: 0.96rem; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 8px 18px rgba(109, 40, 217, 0.25); cursor: pointer; transition: all 0.25s ease;">
                <span>Mulai Lembar Penilaian</span>
                <i class="fas fa-arrow-right" style="transition: transform 0.2s;"></i>
            </button>
        </form>

        <!-- Bagian Rekap & Laporan Bawah -->
        <div style="background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 22px 28px; text-align: center;">
            <div style="margin-bottom: 12px;">
                <span style="font-size: 0.82rem; font-weight: 750; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fas fa-chart-line" style="color: #db2777; margin-right: 4px;"></i> Rekapitulasi Nilai
                </span>
                <p style="margin: 4px 0 0; font-size: 0.82rem; color: #94a3b8;">
                    Lihat akumulasi nilai santri dan cetak lembar raport ekstrakurikuler
                </p>
            </div>

            <form id="report-form" action="{{ route('grades.report') }}" method="GET">
                <input type="hidden" name="eskul_id" id="report-eskul-id">
                <input type="hidden" name="academic_year_id" id="report-year-id">
                <input type="hidden" name="semester" id="report-semester">
                <button type="button" onclick="submitReport()" style="width: 100%; height: 46px; border-radius: 12px; background: #ffffff; border: 1.5px solid #db2777; color: #db2777; font-size: 0.9rem; font-weight: 750; display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 6px rgba(219, 39, 119, 0.08);">
                    <i class="fas fa-file-invoice"></i>
                    <span>Buka Rekapitulasi & Raport Nilai</span>
                </button>
            </form>

            @if(auth()->user()->role == 'admin')
            <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed #e2e8f0;">
                <a href="{{ route('grades.import') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #0284c7; text-decoration: none; font-size: 0.86rem; font-weight: 700; background: #f0f9ff; padding: 6px 14px; border-radius: 20px; border: 1px solid #bae6fd;">
                    <i class="fas fa-file-excel" style="color: #059669;"></i>
                    <span>Import Nilai Masal via Excel</span>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Tips Singkat -->
    <div style="margin-top: 16px; text-align: center;">
        <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #64748b; background: rgba(255, 255, 255, 0.7); padding: 6px 14px; border-radius: 20px; border: 1px solid #e2e8f0;">
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
