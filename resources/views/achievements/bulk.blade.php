@extends('layouts.app')

@section('title', 'Input Prestasi Masal')
@section('page-title', 'Tambah Prestasi Secara Masal')

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div style="background: #fffbe7; border-left: 5px solid #f1c40f; padding: 20px; border-radius: 8px; margin-bottom: 2rem;">
        <h3 style="color: #9a7d0a; margin-bottom: 10px;"><i class="fas fa-lightbulb"></i> Petunjuk Import Masal</h3>
        <p style="color: #856404; font-size: 0.95rem; line-height: 1.6;">
            Gunakan format kolom berikut dari Excel (Copy-Paste): <br>
            <code>Nama Siswa [TAB] Kelas [TAB] Nama Prestasi [TAB] Tingkat [TAB] Tanggal (Opsional) [TAB] Penyelenggara [TAB] Keterangan</code>
        </p>
        <ul style="color: #856404; font-size: 0.9rem; margin-top: 10px; padding-left: 20px;">
            <li>Sistem akan mencari nama siswa yang paling mendekati di database.</li>
            <li>Tingkat harus diisi (contoh: Sekolah, Provinsi, Nasional, dll).</li>
            <li>Tanggal default adalah hari ini jika dikosongkan (Format: YYYY-MM-DD).</li>
        </ul>
    </div>

    <form action="{{ route('achievements.store_bulk') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 2rem;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Tahun Pelajaran</label>
                <select name="academic_year_id" class="form-control" required>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ ($activeYear && $activeYear->id == $year->id) ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Semester</label>
                <select name="semester" class="form-control" required>
                    <option value="1" {{ ($activeYear && $activeYear->active_semester == '1') ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                    <option value="2" {{ ($activeYear && $activeYear->active_semester == '2') ? 'selected' : '' }}>Semester 2 (Genap)</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 2rem;">
            <label style="display: block; margin-bottom: 10px; font-weight: 600;">Data Prestasi (Tempel dari Excel)</label>
            <textarea name="bulk_data" class="form-control" rows="12" placeholder="Contoh:&#10;Ahmad Yusuf	Juara 1 Lomba Adzan	Kecamatan	2026-04-10	KUA Cinangka&#10;Siti Aminah	Pemenang Favorit Mewarnai	Provinsi	2026-03-15	Bank BJB" style="font-family: monospace; font-size: 0.9rem; padding: 15px; border: 2px solid #e2e8f0; border-radius: 12px;" required></textarea>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <a href="{{ route('achievements.index') }}" class="btn-submit" style="background: #94a3b8; border: none; width: auto;">Kembali</a>
            <button type="submit" class="btn-submit" style="width: auto; padding: 0 30px;">
                <i class="fas fa-upload" style="margin-right: 8px;"></i> Proses Data Masal
            </button>
        </div>
    </form>
</div>
@endsection
