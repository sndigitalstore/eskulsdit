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
            <select name="class" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                <option value="">-- Pilih Kelas --</option>
                @foreach(\App\Models\SchoolClass::orderBy('name')->get() as $class)
                    <option value="{{ $class->name }}" {{ old('class') == $class->name ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Foto Siswa (Opsional)</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Nama Ekstrakurikuler</label>
            <input type="text" name="eskul_name" class="form-control" placeholder="Contoh: Futsal" required>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 8px; color: #888;">Nama Pembina</label>
            <input type="text" name="instructor_name" class="form-control" placeholder="Contoh: Pak Budi">
        </div>

        <button type="submit" class="btn-submit">Simpan Data</button>
    </form>
</div>
@endsection
