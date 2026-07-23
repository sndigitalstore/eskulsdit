@extends('layouts.app')

@section('title', 'Tambah Siswa')
@section('page-title', 'Tambah Siswa')

@section('content')
<a href="{{ route('students.index') }}" style="display: inline-block; margin-bottom: 1rem; color: #888; text-decoration: none;"><i class="fas fa-arrow-left"></i> Kembali</a>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    @if ($errors->any())
        <div style="background: #ffe0e3; color: #ff4757; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <ul style="list-style: none; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 8px; color: #888;">NIS</label>
            <input type="text" name="nis" class="form-control" placeholder="Nomor Induk Siswa" required>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Nama Siswa</label>
            <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Kelas</label>
            <input type="text" name="class" list="classList" class="form-control" placeholder="Ketik atau pilih kelas" value="{{ old('class') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            <datalist id="classList">
                @foreach(\App\Models\SchoolClass::orderBy('name')->get() as $class)
                    <option value="{{ $class->name }}">
                @endforeach
            </datalist>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Foto Siswa (Opsional)</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
        </div>

        <div style="margin-bottom: 1.5rem; border: 1px solid #eee; padding: 15px; border-radius: 10px; background: #fafafa;">
            <h4 style="margin-bottom: 12px; color: #2c3e50; font-size: 1rem; border-bottom: 2px solid #e0e0e0; padding-bottom: 8px;">
                <i class="fas fa-running"></i> Pilihan Ekstrakurikuler (Opsional)
            </h4>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #555;">Pilih Ekstrakurikuler</label>
                <select name="eskul_1_id" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
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

            <div class="form-group" style="margin-bottom: 0.5rem;">
                <label style="display: block; margin-bottom: 6px; color: #777; font-size: 0.85rem;">Atau Ketik Eskul Baru (Jika belum ada di daftar di atas)</label>
                <input type="text" name="eskul_name" class="form-control" placeholder="Contoh: Futsal" value="{{ old('eskul_name') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin-bottom: 0.5rem; margin-top: 0.75rem;">
                <label style="display: block; margin-bottom: 6px; color: #777; font-size: 0.85rem;">Nama Pembina (Jika buat eskul baru)</label>
                <input type="text" name="instructor_name" class="form-control" placeholder="Contoh: Pak Budi" value="{{ old('instructor_name') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <small style="color: #888; display: block; margin-top: 5px; font-size: 0.8rem;">
                * Kosongkan bagian ini jika siswa/orang tua belum menentukan pilihan eskul.
            </small>
        </div>

        <button type="submit" class="btn-submit" style="width: 100%;">Simpan Data</button>
    </form>
</div>
@endsection
