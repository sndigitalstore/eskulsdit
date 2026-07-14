@extends('layouts.app')

@section('title', 'Import Akun Guru')
@section('page-title', 'Tambah Akun Guru Masal')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div style="background: #eff6ff; border-left: 5px solid #3b82f6; padding: 20px; border-radius: 8px; margin-bottom: 2rem;">
        <h3 style="color: #1e40af; margin-bottom: 10px;"><i class="fas fa-info-circle"></i> Panduan Import Guru</h3>
        <p style="color: #1e3a8a; font-size: 0.95rem; line-height: 1.6;">
            Gunakan format kolom berikut dari Excel (Copy-Paste): <br>
            <code>Nama Guru [TAB] Username [TAB] No WA (Opsional) [TAB] Nama Eskul (Opsional)</code>
        </p>
        <ul style="color: #1e3a8a; font-size: 0.9rem; margin-top: 10px; padding-left: 20px;">
            <li>Sistem akan otomatis membuat akun untuk setiap guru.</li>
            <li>Password default untuk akun baru adalah: <strong>123456</strong></li>
            <li>Username harus unik (tidak boleh sama antar guru).</li>
            <li>Nama Eskul harus sesuai dengan yang ada di database agar otomatis terhubung.</li>
        </ul>
    </div>

    <form action="{{ route('teachers.store_bulk') }}" method="POST">
        @csrf
        
        <div class="form-group" style="margin-bottom: 2rem;">
            <label style="display: block; margin-bottom: 10px; font-weight: 600;">Data Guru (Tempel dari Excel)</label>
            <textarea name="bulk_data" class="form-control" rows="12" placeholder="Contoh:&#10;Muhammad Ridwan	ridwan_guru	08123456789	Tahfidz&#10;Siti Fatimah	fatimah_guru	08987654321	Calistung A" style="font-family: monospace; font-size: 0.9rem; padding: 15px; border: 2px solid #e2e8f0; border-radius: 12px;" required></textarea>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <a href="{{ route('teachers.index') }}" class="btn-submit" style="background: #94a3b8; border: none; width: auto;">Kembali</a>
            <button type="submit" class="btn-submit" style="width: auto; padding: 0 30px;">
                <i class="fas fa-user-plus" style="margin-right: 8px;"></i> Buat Akun Masal
            </button>
        </div>
    </form>
</div>
@endsection
