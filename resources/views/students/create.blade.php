@extends('layouts.app')

@section('title', 'Tambah Siswa')
@section('page-title', 'Tambah Siswa')

@push('styles')
<style>
.form-section {
    border: 1px solid #e8ecf0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    background: #fafbfc;
}
.form-section h4 {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 16px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    font-size: 0.85rem;
    color: #475569;
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 9px 12px;
    border: 1.5px solid #dde1e7;
    border-radius: 8px;
    font-size: 0.9rem;
    color: #1e293b;
    background: #fff;
    transition: border-color 0.2s;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
}
@media(max-width: 600px) { .form-grid-2 { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<a href="{{ route('students.index') }}" style="display: inline-block; margin-bottom: 1rem; color: #888; text-decoration: none;"><i class="fas fa-arrow-left"></i> Kembali</a>

<div class="card" style="max-width: 720px; margin: 0 auto;">
    <h3 style="margin-bottom: 20px; font-size: 1.2rem; color: #1e293b;"><i class="fas fa-user-plus" style="color: #3b82f6; margin-right: 8px;"></i>Formulir Tambah Siswa</h3>

    @if ($errors->any())
        <div style="background: #ffe0e3; color: #ff4757; padding: 14px; border-radius: 8px; margin-bottom: 20px;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ===== IDENTITAS SISWA ===== --}}
        <div class="form-section">
            <h4><i class="fas fa-id-card" style="color:#3b82f6;"></i> Identitas Siswa</h4>
            <div class="form-grid-2">
                <div class="form-group">
                    <label>NIS <span style="color:red">*</span></label>
                    <input type="text" name="nis" placeholder="Nomor Induk Siswa" value="{{ old('nis') }}" required>
                </div>
                <div class="form-group">
                    <label>NISN</label>
                    <input type="text" name="nisn" placeholder="Nomor Induk Siswa Nasional" value="{{ old('nisn') }}">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Nama Lengkap <span style="color:red">*</span></label>
                    <input type="text" name="name" placeholder="Masukkan nama lengkap siswa" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>Kelas <span style="color:red">*</span></label>
                    <input type="text" name="class" list="classList" placeholder="Ketik atau pilih kelas" value="{{ old('class') }}" required>
                    <datalist id="classList">
                        @foreach(\App\Models\SchoolClass::orderBy('name')->get() as $class)
                            <option value="{{ $class->name }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="gender">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Tempat, Tanggal Lahir</label>
                    <input type="text" name="birth_place_date" placeholder="Contoh: Serang, 30 Januari 2018" value="{{ old('birth_place_date') }}">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Alamat</label>
                    <textarea name="address" rows="2" placeholder="Alamat lengkap tempat tinggal">{{ old('address') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Asal Sekolah (TK/PAUD)</label>
                    <input type="text" name="previous_school" placeholder="Contoh: TK Al-Ikhlas" value="{{ old('previous_school') }}">
                </div>
                <div class="form-group">
                    <label>Program Sekolah</label>
                    <select name="school_program">
                        <option value="">-- Pilih --</option>
                        <option value="Full Day" {{ old('school_program') == 'Full Day' ? 'selected' : '' }}>Full Day</option>
                        <option value="Boarding" {{ old('school_program') == 'Boarding' ? 'selected' : '' }}>Boarding</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Diterima / Masuk</label>
                    <input type="text" name="admission_date" placeholder="Contoh: 15 Juli 2023" value="{{ old('admission_date') }}">
                </div>
                <div class="form-group">
                    <label>Foto Siswa</label>
                    <input type="file" name="photo" accept="image/*">
                </div>
            </div>
        </div>

        {{-- ===== DATA ORANG TUA ===== --}}
        <div class="form-section">
            <h4><i class="fas fa-users" style="color:#10b981;"></i> Data Orang Tua / Wali</h4>
            <div class="form-grid-2">
                <div class="form-group">
                    <label>Nama Ayah</label>
                    <input type="text" name="father_name" placeholder="Nama lengkap ayah" value="{{ old('father_name') }}">
                </div>
                <div class="form-group">
                    <label>Nama Ibu</label>
                    <input type="text" name="mother_name" placeholder="Nama lengkap ibu" value="{{ old('mother_name') }}">
                </div>
                <div class="form-group">
                    <label>Pekerjaan Ayah</label>
                    <input type="text" name="father_job" placeholder="Pekerjaan ayah" value="{{ old('father_job') }}">
                </div>
                <div class="form-group">
                    <label>Pekerjaan Ibu</label>
                    <input type="text" name="mother_job" placeholder="Pekerjaan ibu" value="{{ old('mother_job') }}">
                </div>
                <div class="form-group">
                    <label>No. HP Ayah</label>
                    <input type="text" name="father_phone" placeholder="08xxxxxxxxxx" value="{{ old('father_phone') }}">
                </div>
                <div class="form-group">
                    <label>No. HP Ibu</label>
                    <input type="text" name="mother_phone" placeholder="08xxxxxxxxxx" value="{{ old('mother_phone') }}">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>No. HP Wali Utama (untuk komunikasi)</label>
                    <input type="text" name="parent_phone" placeholder="Nomor yang diutamakan untuk dihubungi" value="{{ old('parent_phone') }}">
                </div>
            </div>
        </div>

        {{-- ===== ESKUL ===== --}}
        <div class="form-section">
            <h4><i class="fas fa-running" style="color:#f59e0b;"></i> Pilihan Ekstrakurikuler (Opsional)</h4>
            <div class="form-group" style="margin-bottom: 12px;">
                <label>Pilih Ekstrakurikuler</label>
                <select name="eskul_1_id">
                    <option value="">-- Belum Memilih Eskul / Kosongkan --</option>
                    @if(isset($allEskuls))
                        @foreach($allEskuls as $eskul)
                            <option value="{{ $eskul->id }}" {{ old('eskul_1_id') == $eskul->id ? 'selected' : '' }}>
                                {{ $eskul->name }} ({{ $eskul->instructor_name ?? 'Belum ada pembina' }})
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 8px;">
                <label>Atau Ketik Eskul Baru (jika belum ada di daftar)</label>
                <input type="text" name="eskul_name" placeholder="Contoh: Futsal" value="{{ old('eskul_name') }}">
            </div>
            <div class="form-group" style="margin-bottom: 4px;">
                <label>Nama Pembina (Jika buat eskul baru)</label>
                <input type="text" name="instructor_name" placeholder="Contoh: Pak Budi" value="{{ old('instructor_name') }}">
            </div>
            <small style="color: #94a3b8; font-size: 0.8rem;">* Kosongkan jika siswa belum menentukan pilihan eskul.</small>
        </div>

        <button type="submit" class="btn-submit" style="width: 100%; padding: 13px; font-size: 1rem; border-radius: 10px;">
            <i class="fas fa-save"></i> Simpan Data Siswa
        </button>
    </form>
</div>
@endsection
