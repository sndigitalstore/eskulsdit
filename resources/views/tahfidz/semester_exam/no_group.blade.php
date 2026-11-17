@extends('layouts.app')

@section('title', 'Ujian Hafalan Semester - Tahfidz')

@section('content')
<div style="max-width: 900px; margin: 40px auto; text-align: center; padding: 40px 20px; background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
    <div style="width: 80px; height: 80px; border-radius: 50%; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 20px;">
        <i class="fas fa-clipboard-check"></i>
    </div>
    <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Belum Ada Kelompok Tahfidz Aktif</h2>
    <p style="color: #64748b; font-size: 0.95rem; max-width: 500px; margin: 0 auto 24px; line-height: 1.6;">
        Akun Anda belum memiliki penugasan kelompok halaqah Tahfidz pada tahun ajaran yang dipilih, atau belum ada kelompok tahfidz yang terdaftar.
    </p>
    <a href="{{ route('dashboard') }}" class="btn-action-header btn-blue" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; border-radius: 10px; font-weight: 700; text-decoration: none;">
        <i class="fas fa-home"></i> Kembali ke Dashboard
    </a>
</div>
@endsection
