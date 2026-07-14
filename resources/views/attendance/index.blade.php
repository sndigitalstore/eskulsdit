@extends('layouts.app')

@section('title', 'Absensi')
@section('page-title', 'Input Absensi')

@section('content')
<div class="card" style="max-width: 500px; margin: 2rem auto;">
    <h3 style="margin-bottom: 1.5rem; text-align: center;">Pilih Kegiatan</h3>
    
    @if(session('success'))
        <div style="background: #e0fbf0; color: #2ecc71; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('attendance.create') }}" method="GET">
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Tahun Ajaran</label>
            <select name="academic_year_id" class="form-control" required>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ ($activeYear && $activeYear->id == $year->id) ? 'selected' : '' }}>
                        {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Ekstrakurikuler</label>
            <select name="eskul_id" class="form-control" required>
                <option value="">-- Pilih Eskul --</option>
                @foreach($eskuls as $eskul)
                    <option value="{{ $eskul->id }}">{{ $eskul->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Tanggal Kegiatan</label>
            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <button type="submit" class="btn-submit" style="width: 100%; justify-content: center;">Lanjut ke Presensi <i class="fas fa-arrow-right"></i></button>
    </form>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">
    
    <div style="text-align: center;">
        <h4 style="margin-bottom: 15px; font-weight: 500;">Lihat Rekap Absensi</h4>
        <form id="report-form" action="{{ route('attendance.report') }}" method="GET">
            <input type="hidden" name="academic_year_id" id="report-year-id">
            <input type="hidden" name="eskul_id" id="report-eskul-id">
            <button type="button" class="btn-submit" style="background: white; border: 1px solid #3498db; color: #3498db; width: 100%; justify-content: center; display: flex;" onclick="submitReport()">
                <i class="fas fa-chart-bar" style="margin-right: 8px;"></i> Lihat Laporan
            </button>
        </form>
    </div>
</div>

<script>
    function submitReport() {
        // Sync values
        var mainYear = document.querySelector('select[name="academic_year_id"]').value;
        var mainEskul = document.querySelector('select[name="eskul_id"]').value;

        if (!mainEskul) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Eskul',
                text: 'Silakan pilih ekstrakurikuler terlebih dahulu di form atas.',
                confirmButtonColor: '#3498db'
            });
            return;
        }

        document.getElementById('report-year-id').value = mainYear;
        document.getElementById('report-eskul-id').value = mainEskul;
        document.getElementById('report-form').submit();
    }
</script>
@endsection
