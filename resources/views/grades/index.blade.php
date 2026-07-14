@extends('layouts.app')

@section('title', 'Input Nilai')
@section('page-title', 'Input Nilai')

@section('content')
<div class="card" style="max-width: 500px; margin: 2rem auto;">
    <h3 style="margin-bottom: 1.5rem; text-align: center;">Pilih Kriteria Nilai</h3>
    
    @if(session('success'))
        <div style="background: #e0fbf0; color: #2ecc71; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('grades.create') }}" method="GET">
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Tahun Ajaran</label>
            <select name="academic_year_id" class="form-control" required>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ ($activeYear && $activeYear->id == $year->id) ? 'selected' : '' }}>
                        {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Ekstrakurikuler</label>
            <select name="eskul_id" class="form-control" id="eskul-select" required>
                <option value="">-- Pilih Eskul --</option>
                @foreach($eskuls as $eskul)
                    <option value="{{ $eskul->id }}" {{ request('eskul_id') == $eskul->id ? 'selected' : '' }}>{{ $eskul->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Jenis Penilaian</label>
            <select name="type" class="form-control" id="type-select" required onchange="toggleDate()">
                <option value="daily">Nilai Harian</option>
                <option value="sas1">Nilai SAS 1</option>
                <option value="sas2">Nilai SAS 2</option>
            </select>
        </div>

        <div class="form-group" id="date-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
        </div>

        <button type="submit" class="btn-submit" style="width: 100%; justify-content: center;">Mulai Penilaian <i class="fas fa-arrow-right"></i></button>
    </form>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">
    
    <div style="text-align: center;">
        <h4 style="margin-bottom: 15px; font-weight: 500;">Lihat Rekap Nilai</h4>
        <form id="report-form" action="{{ route('grades.report') }}" method="GET">
            <input type="hidden" name="eskul_id" id="report-eskul-id">
            <input type="hidden" name="academic_year_id" id="report-year-id">
            <input type="hidden" name="semester" id="report-semester">
            <button type="button" class="btn-submit" style="background: white; border: 1px solid #ff7eb3; color: #ff7eb3; width: 100%; justify-content: center;" onclick="submitReport()">
                <i class="fas fa-file-invoice"></i> Lihat Laporan
            </button>
        </form>
    </div>

    @if(auth()->user()->role == 'admin')
    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('grades.import') }}" style="color: #3498db; text-decoration: none; font-size: 0.9rem;">
            <i class="fas fa-file-excel"></i> Import Nilai Masal (Excel)
        </a>
    </div>
    @endif
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
        var eskulId = document.getElementById('report-eskul-id').value;
        if (!eskulId) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Eskul',
                text: 'Silakan pilih ekstrakurikuler terlebih dahulu di form atas.',
                confirmButtonColor: '#3498db'
            });
            return;
        }

        // Sync values
        var yearId = document.querySelector('select[name="academic_year_id"]').value;
        document.getElementById('report-year-id').value = yearId;
        // In this form, semester is not selectable, but we pass it as empty to let controller resolve
        document.getElementById('report-form').submit();
    }
</script>
@endpush
