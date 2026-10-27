@extends('layouts.app')

@section('title', 'Program Kerja Eskul - ' . $eskul->name)
@section('page-title', 'Program Kerja Ekstrakurikuler')

@push('styles')
<style>
    .pk-container { max-width: 1100px; margin: 0 auto; padding-bottom: 60px; }

    .pk-header {
        background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
        color: white;
        border-radius: 20px;
        padding: 24px 28px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }
    .pk-header h1 { font-size: 1.5rem; font-weight: 800; margin: 0; }
    .pk-header p  { font-size: 0.9rem; color: #94a3b8; margin: 4px 0 0 0; }

    .pk-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(59,130,246,0.2); color: #93c5fd;
        border: 1px solid rgba(147,197,253,0.3);
        padding: 4px 12px; border-radius: 999px;
        font-size: 0.8rem; font-weight: 700; margin-bottom: 8px;
    }

    .pk-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px 28px;
        margin-bottom: 22px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }
    .pk-card h2 {
        font-size: 1rem; font-weight: 700; color: #1e293b;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 10px; margin-bottom: 18px;
        display: flex; align-items: center; gap: 8px;
    }
    .pk-card h2 .section-icon {
        width: 32px; height: 32px; border-radius: 9px;
        background: #eff6ff; color: #2563eb;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
    }

    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 6px; }
    .form-group input, .form-group textarea {
        width: 100%; padding: 10px 14px;
        border: 1.5px solid #cbd5e1; border-radius: 10px;
        font-size: 0.9rem; color: #1e293b;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #f8fafc;
        resize: vertical;
    }
    .form-group input:focus, .form-group textarea:focus {
        border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        outline: none; background: white;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white; border: none; border-radius: 10px;
        padding: 10px 22px; font-weight: 700; font-size: 0.88rem;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        text-decoration: none; transition: all 0.2s;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(37,99,235,0.3); }

    .btn-success {
        background: linear-gradient(135deg, #059669, #047857);
        color: white; border: none; border-radius: 10px;
        padding: 10px 22px; font-weight: 700; font-size: 0.88rem;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        text-decoration: none; transition: all 0.2s;
    }
    .btn-success:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(5,150,105,0.3); }

    .btn-danger-sm {
        background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;
        border-radius: 7px; padding: 5px 10px; font-size: 0.78rem;
        font-weight: 600; cursor: pointer; transition: all 0.15s;
    }
    .btn-danger-sm:hover { background: #fee2e2; }

    .btn-outline {
        background: white; color: #475569; border: 1.5px solid #cbd5e1;
        border-radius: 10px; padding: 10px 20px;
        font-weight: 600; font-size: 0.88rem; cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
        text-decoration: none; transition: all 0.2s;
    }
    .btn-outline:hover { border-color: #2563eb; color: #2563eb; }

    .items-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
    .items-table thead th {
        background: #1e3a5f; color: white;
        padding: 10px 12px; text-align: left; font-weight: 700;
        font-size: 0.8rem;
    }
    .items-table thead th:first-child { border-radius: 8px 0 0 0; }
    .items-table thead th:last-child  { border-radius: 0 8px 0 0; }
    .items-table tbody tr { border-bottom: 1px solid #f1f5f9; }
    .items-table tbody tr:hover { background: #f8fafc; }
    .items-table tbody td { padding: 9px 12px; color: #334155; vertical-align: middle; }

    .empty-state {
        text-align: center; padding: 40px 20px;
        color: #94a3b8; font-size: 0.9rem;
    }
    .empty-state .empty-icon { font-size: 2.5rem; margin-bottom: 10px; }

    .success-flash {
        background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;
        border-radius: 12px; padding: 12px 18px; margin-bottom: 18px;
        font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;
    }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }

    @media (max-width: 700px) {
        .grid-2 { grid-template-columns: 1fr; }
        .grid-3 { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<div class="pk-container">

    {{-- Flash --}}
    @if(session('success'))
    <div class="success-flash">✅ {{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="pk-header">
        <div>
            <div class="pk-badge">📋 Program Kerja — Semester {{ $semester }} | {{ $activeYear->name }}</div>
            <h1>{{ $eskul->name }}</h1>
            <p>Pembina: {{ $eskul->instructor_name ?? '-' }} &nbsp;·&nbsp; Jadwal: {{ $eskul->schedule ?? '-' }}</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('eskuls.program.print', $eskul) }}" target="_blank" class="btn-success">
                🖨️ Cetak Program Kerja
            </a>
            <a href="{{ route('eskuls.jurnal.index', $eskul) }}" class="btn-outline">
                📔 Jurnal Kegiatan
            </a>
            <a href="{{ route('eskuls.index') }}" class="btn-outline">← Kembali</a>
        </div>
    </div>

    {{-- FORM: Identitas Program --}}
    <div class="pk-card">
        <h2><span class="section-icon">🎯</span> Identitas & Landasan Program</h2>
        <form method="POST" action="{{ route('eskuls.program.store', $eskul) }}">
            @csrf
            <div class="form-group">
                <label>Visi Ekstrakurikuler</label>
                <textarea name="visi" rows="2" placeholder="Contoh: Menjadi wadah pengembangan bakat siswa yang kreatif, mandiri, dan berprestasi...">{{ old('visi', $program->visi) }}</textarea>
            </div>
            <div class="form-group">
                <label>Misi</label>
                <textarea name="misi" rows="4" placeholder="1. Mengembangkan potensi siswa...&#10;2. Melatih kedisiplinan...">{{ old('misi', $program->misi) }}</textarea>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Tujuan Program</label>
                    <textarea name="tujuan" rows="3" placeholder="Tujuan pelaksanaan ekstrakurikuler...">{{ old('tujuan', $program->tujuan) }}</textarea>
                </div>
                <div class="form-group">
                    <label>Sasaran (Target Peserta)</label>
                    <textarea name="sasaran" rows="3" placeholder="Contoh: Seluruh siswa kelas 1 sampai 6 yang mendaftar">{{ old('sasaran', $program->sasaran) }}</textarea>
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Sarana & Prasarana</label>
                    <textarea name="sarana" rows="3" placeholder="Contoh: Aula sekolah, alat musik, kostum...">{{ old('sarana', $program->sarana) }}</textarea>
                </div>
                <div class="form-group">
                    <label>Sumber Dana</label>
                    <textarea name="sumber_dana" rows="3" placeholder="Contoh: Anggaran BOS, iuran siswa, donasi...">{{ old('sumber_dana', $program->sumber_dana) }}</textarea>
                </div>
            </div>
            <hr style="border:1px solid #f1f5f9; margin: 18px 0;">
            <p style="font-size:0.82rem; color:#64748b; font-weight:700; margin-bottom:10px;">DATA PENGESAHAN (untuk keperluan tanda tangan)</p>
            <div class="grid-2">
                <div class="form-group">
                    <label>Nama Kepala Sekolah</label>
                    <input type="text" name="kepsek_name" value="{{ old('kepsek_name', $program->kepsek_name) }}" placeholder="...">
                </div>
                <div class="form-group">
                    <label>NIP Kepala Sekolah</label>
                    <input type="text" name="nip_kepsek" value="{{ old('nip_kepsek', $program->nip_kepsek) }}" placeholder="...">
                </div>
            </div>
            <button type="submit" class="btn-primary">💾 Simpan Program</button>
        </form>
    </div>

    {{-- TABEL: Rencana Kegiatan per Bulan --}}
    <div class="pk-card">
        <h2><span class="section-icon">📅</span> Rencana Kegiatan (Program Bulanan)</h2>

        @if($items->count())
        <div style="overflow-x:auto; margin-bottom:22px;">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:35px">No</th>
                        <th style="width:90px">Bulan</th>
                        <th>Kegiatan</th>
                        <th>Tujuan Kegiatan</th>
                        <th>Tempat</th>
                        <th>Waktu</th>
                        <th>Penanggung Jawab</th>
                        <th style="width:60px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i => $item)
                    <tr>
                        <td style="text-align:center">{{ $i+1 }}</td>
                        <td><strong>{{ $item->bulan }}</strong></td>
                        <td>{{ $item->kegiatan }}</td>
                        <td>{{ $item->tujuan_kegiatan ?? '-' }}</td>
                        <td>{{ $item->tempat ?? '-' }}</td>
                        <td>{{ $item->waktu ?? '-' }}</td>
                        <td>{{ $item->penanggung_jawab ?? $eskul->instructor_name ?? '-' }}</td>
                        <td>
                            <form method="POST" action="{{ route('eskuls.program.item.destroy', [$eskul, $item]) }}" onsubmit="return confirm('Hapus kegiatan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger-sm">🗑</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">📅</div>
            <p>Belum ada rencana kegiatan. Tambahkan di bawah ini.</p>
        </div>
        @endif

        {{-- Form Tambah Item --}}
        <details style="margin-top:10px;">
            <summary style="cursor:pointer; font-weight:700; font-size:0.9rem; color:#2563eb; padding:8px 0; user-select:none;">
                ➕ Tambah Kegiatan Baru
            </summary>
            <div style="padding-top: 14px;">
                <form method="POST" action="{{ route('eskuls.program.item.store', $eskul) }}">
                    @csrf
                    <div class="grid-3">
                        <div class="form-group">
                            <label>Bulan <span style="color:red">*</span></label>
                            <input type="text" name="bulan" list="bulan-list" placeholder="Juli, Agustus ..." required>
                            <datalist id="bulan-list">
                                @foreach(['Juli','Agustus','September','Oktober','November','Desember','Januari','Februari','Maret','April','Mei','Juni'] as $b)
                                <option value="{{ $b }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Kegiatan <span style="color:red">*</span></label>
                            <input type="text" name="kegiatan" placeholder="Nama kegiatan" required>
                        </div>
                        <div class="form-group">
                            <label>Tujuan Kegiatan</label>
                            <input type="text" name="tujuan_kegiatan" placeholder="Tujuan singkat">
                        </div>
                        <div class="form-group">
                            <label>Tempat</label>
                            <input type="text" name="tempat" placeholder="Aula, Lapangan, ...">
                        </div>
                        <div class="form-group">
                            <label>Waktu</label>
                            <input type="text" name="waktu" placeholder="Minggu ke-1, Setiap Jumat...">
                        </div>
                        <div class="form-group">
                            <label>Penanggung Jawab</label>
                            <input type="text" name="penanggung_jawab" placeholder="{{ $eskul->instructor_name ?? '' }}" value="{{ $eskul->instructor_name ?? '' }}">
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">➕ Tambah Kegiatan</button>
                </form>
            </div>
        </details>
    </div>

</div>
@endsection
