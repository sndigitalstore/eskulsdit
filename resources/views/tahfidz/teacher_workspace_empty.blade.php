@extends('layouts.app')

@section('title', 'Ruang Halaqah Tahfidz')
@section('page-title', 'Mentoring Tahfidz')

@section('content')
<div style="max-width: 650px; margin: 40px auto; text-align: center;">
    <div class="card" style="padding: 40px 30px; border-radius: 18px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);">
        <div style="width: 72px; height: 72px; border-radius: 20px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 20px auto;">
            <i class="fas fa-quran"></i>
        </div>
        <h2 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
            Belum Ada Kelompok Tahfidz Terkait
        </h2>
        <p style="color: #64748b; font-size: 0.95rem; line-height: 1.6; margin-bottom: 24px;">
            Akun Anda (<strong>{{ Auth::user()->name }}</strong>) belum ditugaskan sebagai musyrif / pembimbing pada kelompok Tahfidz untuk Tahun Pelajaran aktif (<strong>{{ $activeYear?->name ?? '-' }}</strong>).
        </p>
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 24px; text-align: left; font-size: 0.88rem; color: #334155;">
            <div style="font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                <i class="fas fa-lightbulb" style="color: #f59e0b;"></i> Apa yang perlu dilakukan?
            </div>
            Silakan hubungi <strong>Tim Kurikulum</strong> atau <strong>Administrator</strong> untuk memplot nama Anda ke dalam salah satu kelompok Halaqah Tahfidz melalui menu <em>Manajemen Kelompok</em>.
        </div>
        <a href="{{ route('dashboard') }}" class="btn-action-header btn-green" style="background: #059669; padding: 10px 22px; font-weight: 700;">
            <i class="fas fa-home"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
