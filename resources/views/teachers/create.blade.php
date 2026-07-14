@extends('layouts.app')

@section('title', 'Tambah Akun Guru')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 style="margin-bottom: 20px;">Buat Akun Guru Pembina Baru</h3>
    
    <form action="{{ route('teachers.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label>Nama Lengkap Guru</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')<div style="color: red; font-size: 0.8rem;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Pilih Eskul Binaan</label>
            <select name="eskul_id" class="form-control" required>
                <option value="">-- Pilih Eskul --</option>
                @foreach($eskuls as $eskul)
                    <option value="{{ $eskul->id }}" {{ old('eskul_id') == $eskul->id ? 'selected' : '' }}>
                        {{ $eskul->name }}
                    </option>
                @endforeach
            </select>
            @error('eskul_id')<div style="color: red; font-size: 0.8rem;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Username Login</label>
            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
            @error('username')<div style="color: red; font-size: 0.8rem;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Nomor WhatsApp</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Contoh: 08123456789">
            @error('phone')<div style="color: red; font-size: 0.8rem;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required minlength="6">
            @error('password')<div style="color: red; font-size: 0.8rem;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required minlength="6">
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn-submit">Simpan Akun</button>
            <a href="{{ route('teachers.index') }}" class="btn-submit" style="background: #ccc; width: auto; text-align: center;">Batal</a>
        </div>
    </form>
</div>
@endsection
