@extends('layouts.app')

@section('title', 'Manajemen Pengumuman')
@section('page-title', 'Pusat Pengumuman Internal')

@section('content')
<div style="display: grid; grid-template-columns: 350px 1fr; gap: 25px;">
    <!-- Form Side -->
    <div>
        <div class="card">
            <h3 style="margin-bottom: 20px;">Buat Pengumuman Baru</h3>
            <form action="{{ route('announcements.store') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Judul</label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Jadwal Rapat Eskul" required>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Jenis</label>
                    <select name="type" class="form-control" required>
                        <option value="info">Info (Biru)</option>
                        <option value="primary">Utama (Ungu)</option>
                        <option value="success">Penting (Hijau)</option>
                        <option value="warning">Peringatan (Kuning)</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Isi Pengumuman</label>
                    <textarea name="content" class="form-control" rows="5" placeholder="Tulis pesan Anda di sini..." required></textarea>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; cursor: pointer; gap: 10px;">
                        <input type="checkbox" name="broadcast_wa" value="1" style="width: 18px; height: 18px; accent-color: #25d366;">
                        <span style="font-weight: 500; color: #2d3748;">
                            <i class="fab fa-whatsapp" style="color: #25d366;"></i> Kirim ke WA Seluruh Guru
                        </span>
                    </label>
                </div>
                <button type="submit" class="btn-submit" style="width: 100%;">
                    <i class="fas fa-paper-plane" style="margin-right: 8px;"></i> Terbitkan Sekarang
                </button>
            </form>
        </div>
    </div>

    <!-- List Side -->
    <div>
        <div class="card">
            <h3 style="margin-bottom: 20px;">Daftar Pengumuman Aktif</h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @forelse($announcements as $item)
                <div style="padding: 20px; border-radius: 12px; background: white; border: 1px solid #edf2f7; position: relative; {{ $item->type == 'info' ? 'border-left: 5px solid #3b82f6;' : ($item->type == 'warning' ? 'border-left: 5px solid #f59e0b;' : ($item->type == 'success' ? 'border-left: 5px solid #10b981;' : 'border-left: 5px solid #7367f0;')) }}">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <h4 style="margin: 0 0 5px 0; color: #1a202c;">{{ $item->title }}</h4>
                            <small style="color: #a0aec0;">Oleh: {{ $item->user->name }} • {{ $item->created_at->diffForHumans() }}</small>
                        </div>
                        <form action="{{ route('announcements.destroy', $item->id) }}" method="POST" data-confirm="Hapus pengumuman ini? Pengumuman tidak akan terlihat lagi di dashboard guru.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #cbd5e0; cursor: pointer; transition: color 0.2s;">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                    <div style="margin-top: 15px; color: #4a5568; line-height: 1.6; white-space: pre-line;">
                        {{ $item->content }}
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 40px; color: #a0aec0;">
                    <i class="fas fa-bullhorn" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.3;"></i>
                    <p>Belum ada pengumuman yang diterbitkan.</p>
                </div>
                @endforelse
            </div>
            
            <div style="margin-top: 20px;">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
