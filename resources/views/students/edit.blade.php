@extends('layouts.app')

@section('title', 'Edit Data Siswa')
@section('page-title', 'Edit Data Siswa')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('students.index') }}" style="color: #666; text-decoration: none; font-size: 0.9rem;">
            <i class="fas fa-arrow-left"></i> Kembali ke Data Siswa
        </a>
    </div>

    <h2 style="margin-bottom: 20px;">Edit Siswa</h2>
    
    <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="last_sync" value="{{ $student->updated_at->format('Y-m-d H:i:s') }}">
        @foreach(request()->all() as $key => $value)
            @if(!in_array($key, ['_token', '_method']))
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach
        
        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">NIS</label>
            <input type="text" name="nis" class="form-control" value="{{ old('nis', $student->nis) }}" required>
            @error('nis')<span style="color: red; font-size: 0.8rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $student->name) }}" required>
            @error('name')<span style="color: red; font-size: 0.8rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Kelas</label>
            <input type="text" name="class" class="form-control" value="{{ old('class', $student->class) }}" required>
             @error('class')<span style="color: red; font-size: 0.8rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Foto Siswa</label>
            @if($student->photo)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $student->photo) }}" alt="Current Photo" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 2px solid #eee;">
                    <div style="font-size: 0.8rem; color: #666; margin-top: 5px;">Foto saat ini</div>
                </div>
            @endif
            <input type="file" name="photo" class="form-control" accept="image/*">
            <small style="color: #888;">Upload foto baru jika ingin mengganti.</small>
             @error('photo')<span style="color: red; font-size: 0.8rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 25px; border: 1px solid #eee; padding: 15px; border-radius: 10px; background: #fafafa;">
            <h4 style="margin-bottom: 15px; color: #2c3e50; font-size: 1.1rem; border-bottom: 2px solid #e0e0e0; padding-bottom: 10px;">
                <i class="fas fa-running"></i> Kelola Pilihan Eskul (Pindah Eskul)
            </h4>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">Pilihan Eskul 1 (Wajib)</label>
                <select name="eskul_1_id" class="form-control" required style="border: 2px solid #ddd;">
                    <option value="">-- Pilih Eskul --</option>
                    @foreach($allEskuls as $eskul)
                        <option value="{{ $eskul->id }}" 
                            {{ ($student->eskuls->count() > 0 && $student->eskuls[0]->id == $eskul->id) ? 'selected' : '' }}>
                            {{ $eskul->name }} ({{ $eskul->instructor_name ?? 'Belum ada pembina' }})
                        </option>
                    @endforeach
                </select>
                <small style="color: #666; display: block; margin-top: 5px;">* Ubah pilihan di atas untuk memindahkan siswa ke eskul lain.</small>
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">Pilihan Eskul 2 (Opsional)</label>
                <select name="eskul_2_id" class="form-control" style="border: 2px solid #ddd;">
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

        <button type="submit" class="btn-submit" style="width: 100%;">Simpan Perubahan</button>
    </form>
</div>
@endsection
