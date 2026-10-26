@extends('layouts.app')

@section('title', 'Edit Data Siswa')
@section('page-title', 'Edit Data Siswa')

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
<div style="margin-bottom: 16px;">
    <a href="{{ route('students.index') }}" style="color: #666; text-decoration: none; font-size: 0.9rem;">
        <i class="fas fa-arrow-left"></i> Kembali ke Data Siswa
    </a>
</div>

<div class="card" style="max-width: 720px; margin: 0 auto;">
    <h3 style="margin-bottom: 20px; font-size: 1.2rem; color: #1e293b;"><i class="fas fa-user-edit" style="color: #f59e0b; margin-right: 8px;"></i>Edit Data Siswa</h3>

    @if ($errors->any())
        <div style="background: #ffe0e3; color: #ff4757; padding: 14px; border-radius: 8px; margin-bottom: 20px;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="last_sync" value="{{ $student->updated_at->format('Y-m-d H:i:s') }}">

        {{-- ===== IDENTITAS SISWA ===== --}}
        <div class="form-section">
            <h4><i class="fas fa-id-card" style="color:#3b82f6;"></i> Identitas Siswa</h4>
            <div class="form-grid-2">
                <div class="form-group">
                    <label>NIS <span style="color:red">*</span></label>
                    <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" required>
                    @error('nis')<span style="color:red;font-size:0.8rem">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>NISN</label>
                    <input type="text" name="nisn" placeholder="Nomor Induk Siswa Nasional" value="{{ old('nisn', $student->nisn) }}">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Nama Lengkap <span style="color:red">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required>
                    @error('name')<span style="color:red;font-size:0.8rem">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Kelas <span style="color:red">*</span></label>
                    <input type="text" name="class" list="classList" value="{{ old('class', $student->class) }}" required>
                    <datalist id="classList">
                        @foreach(\App\Models\SchoolClass::orderBy('name')->get() as $class)
                            <option value="{{ $class->name }}">
                        @endforeach
                    </datalist>
                    @error('class')<span style="color:red;font-size:0.8rem">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="gender">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Tempat, Tanggal Lahir</label>
                    <input type="text" name="birth_place_date" placeholder="Contoh: Serang, 30 Januari 2018" value="{{ old('birth_place_date', $student->birth_place_date) }}">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Alamat</label>
                    <textarea name="address" rows="2" placeholder="Alamat lengkap tempat tinggal">{{ old('address', $student->address) }}</textarea>
                </div>
                <div class="form-group">
                    <label>Asal Sekolah (TK/PAUD)</label>
                    <input type="text" name="previous_school" placeholder="Contoh: TK Al-Ikhlas" value="{{ old('previous_school', $student->previous_school) }}">
                </div>
                <div class="form-group">
                    <label>Program Sekolah</label>
                    <select name="school_program">
                        <option value="">-- Pilih --</option>
                        <option value="Full Day" {{ old('school_program', $student->school_program) == 'Full Day' ? 'selected' : '' }}>Full Day</option>
                        <option value="Boarding" {{ old('school_program', $student->school_program) == 'Boarding' ? 'selected' : '' }}>Boarding</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Diterima / Masuk</label>
                    <input type="text" name="admission_date" placeholder="Contoh: 15 Juli 2023" value="{{ old('admission_date', $student->admission_date) }}">
                </div>
                <div class="form-group">
                    <label>Foto Siswa</label>
                    @if($student->photo)
                        <div style="margin-bottom: 8px; display: flex; align-items: center; gap: 10px;">
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="Foto" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%; border: 2px solid #eee;">
                            <span style="font-size: 0.8rem; color: #666;">Foto saat ini</span>
                        </div>
                    @endif
                    <input type="file" name="photo" accept="image/*">
                    <small style="color: #888; font-size: 0.78rem;">Upload foto baru jika ingin mengganti.</small>
                </div>
            </div>
        </div>

        {{-- ===== DATA ORANG TUA ===== --}}
        <div class="form-section">
            <h4><i class="fas fa-users" style="color:#10b981;"></i> Data Orang Tua / Wali</h4>
            <div class="form-grid-2">
                <div class="form-group">
                    <label>Nama Ayah</label>
                    <input type="text" name="father_name" placeholder="Nama lengkap ayah" value="{{ old('father_name', $student->father_name) }}">
                </div>
                <div class="form-group">
                    <label>Nama Ibu</label>
                    <input type="text" name="mother_name" placeholder="Nama lengkap ibu" value="{{ old('mother_name', $student->mother_name) }}">
                </div>
                <div class="form-group">
                    <label>Pekerjaan Ayah</label>
                    <input type="text" name="father_job" placeholder="Pekerjaan ayah" value="{{ old('father_job', $student->father_job) }}">
                </div>
                <div class="form-group">
                    <label>Pekerjaan Ibu</label>
                    <input type="text" name="mother_job" placeholder="Pekerjaan ibu" value="{{ old('mother_job', $student->mother_job) }}">
                </div>
                <div class="form-group">
                    <label>No. HP Ayah</label>
                    <input type="text" name="father_phone" placeholder="08xxxxxxxxxx" value="{{ old('father_phone', $student->father_phone) }}">
                </div>
                <div class="form-group">
                    <label>No. HP Ibu</label>
                    <input type="text" name="mother_phone" placeholder="08xxxxxxxxxx" value="{{ old('mother_phone', $student->mother_phone) }}">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>No. HP Wali Utama (untuk komunikasi)</label>
                    <input type="text" name="parent_phone" placeholder="Nomor yang diutamakan untuk dihubungi" value="{{ old('parent_phone', $student->parent_phone) }}">
                </div>
            </div>
        </div>

        {{-- ===== ESKUL ===== --}}
        <div class="form-section">
            <h4><i class="fas fa-running" style="color:#f59e0b;"></i> Kelola Pilihan Eskul</h4>
            <div class="form-grid-2">
                <div class="form-group">
                    <label>Pilihan Eskul 1 (Opsional)</label>
                    <select name="eskul_1_id">
                        <option value="">-- Belum Memilih Eskul / Kosongkan --</option>
                        @foreach($allEskuls as $eskul)
                            <option value="{{ $eskul->id }}"
                                {{ ($student->eskuls->count() > 0 && $student->eskuls[0]->id == $eskul->id) ? 'selected' : '' }}>
                                {{ $eskul->name }} ({{ $eskul->instructor_name ?? 'Belum ada pembina' }})
                            </option>
                        @endforeach
                    </select>
                    <small style="color: #666; display: block; margin-top: 4px; font-size: 0.78rem;">* Kosongkan jika siswa belum mendaftar/memilih eskul.</small>
                </div>
                <div class="form-group">
                    <label>Pilihan Eskul 2 (Opsional)</label>
                    <select name="eskul_2_id">
                        <option value="">-- Tidak Ada / Kosongkan --</option>
                        @foreach($allEskuls as $eskul)
                            <option value="{{ $eskul->id }}"
                                {{ ($student->eskuls->count() > 1 && $student->eskuls[1]->id == $eskul->id) ? 'selected' : '' }}>
                                {{ $eskul->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-submit" style="width: 100%; padding: 13px; font-size: 1rem; border-radius: 10px;">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
    </form>
</div>
@endsection
