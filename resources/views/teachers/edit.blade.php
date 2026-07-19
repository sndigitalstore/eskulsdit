@extends('layouts.app')

@section('title', 'Edit Akun Guru')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 style="margin-bottom: 20px;">Edit Akun Guru Pembina</h3>
    
    <form action="{{ route('teachers.update', $teacher->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Nama Lengkap Guru</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $teacher->name) }}" required>
            @error('name')<div style="color: red; font-size: 0.8rem;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Pilih Eskul Binaan</label>
            <select name="eskul_id" class="form-control" required>
                <option value="">-- Pilih Eskul --</option>
                @foreach($eskuls as $eskul)
                    <option value="{{ $eskul->id }}" {{ old('eskul_id', $teacher->eskul_id) == $eskul->id ? 'selected' : '' }}>
                        {{ $eskul->name }}
                    </option>
                @endforeach
            </select>
            @error('eskul_id')<div style="color: red; font-size: 0.8rem;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Kelas Binaan (Wali Kelas)</label>
            <select name="homeroom_class" class="form-control">
                <option value="">-- Bukan Wali Kelas (None) --</option>
                @foreach($classes as $class)
                    <option value="{{ $class->name }}" {{ old('homeroom_class', $teacher->homeroom_class) == $class->name ? 'selected' : '' }}>
                        Kelas {{ $class->name }}
                    </option>
                @endforeach
            </select>
            <small style="color: #666; margin-top: 5px; display: block;">Pilih kelas jika guru ini ditugaskan sebagai Wali Kelas.</small>
            @error('homeroom_class')<div style="color: red; font-size: 0.8rem;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Username Login</label>
            <input type="text" name="username" class="form-control" value="{{ old('username', $teacher->username) }}" required>
            @error('username')<div style="color: red; font-size: 0.8rem;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Nomor WhatsApp</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $teacher->phone) }}" placeholder="Contoh: 08123456789">
            @error('phone')<div style="color: red; font-size: 0.8rem;">{{ $message }}</div>@enderror
        </div>

        <div style="border-top: 1px solid #eee; margin: 20px 0; padding-top: 20px;">
            <p style="font-size: 0.8rem; color: #888; margin-bottom: 10px;">Kosongkan jika tidak ingin mengubah password.</p>
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" class="form-control" minlength="6">
                @error('password')<div style="color: red; font-size: 0.8rem;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control" minlength="6">
            </div>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
            <a href="{{ route('teachers.index') }}" class="btn-submit" style="background: #ccc; width: auto; text-align: center;">Batal</a>
        </div>
    </form>
</div>
@endsection
