@extends('layouts.app')

@section('title', 'Jurnal Kegiatan - ' . $eskul->name)
@section('page-title', 'Jurnal Kegiatan Ekstrakurikuler')

@push('styles')
<style>
    .jk-container { max-width: 1100px; margin: 0 auto; padding-bottom: 60px; }

    .jk-header {
        background: linear-gradient(135deg, #064e3b 0%, #022c22 100%);
        color: white; border-radius: 20px;
        padding: 24px 28px; margin-bottom: 24px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 14px;
    }
    .jk-header h1 { font-size: 1.5rem; font-weight: 800; margin: 0; }
    .jk-header p  { font-size: 0.9rem; color: #6ee7b7; margin: 4px 0 0 0; }

    .jk-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(52,211,153,0.2); color: #6ee7b7;
        border: 1px solid rgba(110,231,183,0.3);
        padding: 4px 12px; border-radius: 999px;
        font-size: 0.8rem; font-weight: 700; margin-bottom: 8px;
    }

    .jk-card {
        background: #fff; border: 1px solid #e2e8f0;
        border-radius: 18px; padding: 24px 28px; margin-bottom: 22px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }
    .jk-card h2 {
        font-size: 1rem; font-weight: 700; color: #1e293b;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 10px; margin-bottom: 18px;
        display: flex; align-items: center; gap: 8px;
    }
    .section-icon {
        width: 32px; height: 32px; border-radius: 9px;
        background: #ecfdf5; color: #059669;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
    }

    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 6px; }
    .form-group input, .form-group textarea, .form-group select {
        width: 100%; padding: 10px 14px;
        border: 1.5px solid #cbd5e1; border-radius: 10px;
        font-size: 0.9rem; color: #1e293b;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #f8fafc;
    }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
        border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,0.1);
        outline: none; background: white;
    }

    .btn-primary {
        background: linear-gradient(135deg, #059669, #047857);
        color: white; border: none; border-radius: 10px;
        padding: 10px 22px; font-weight: 700; font-size: 0.88rem;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        text-decoration: none; transition: all 0.2s;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(5,150,105,0.3); }

    .btn-outline {
        background: white; color: #475569; border: 1.5px solid #cbd5e1;
        border-radius: 10px; padding: 10px 20px;
        font-weight: 600; font-size: 0.88rem; cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
        text-decoration: none; transition: all 0.2s;
    }
    .btn-outline:hover { border-color: #059669; color: #059669; }

    .btn-danger-sm {
        background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;
        border-radius: 7px; padding: 5px 10px; font-size: 0.78rem;
        font-weight: 600; cursor: pointer; transition: all 0.15s;
    }
    .btn-danger-sm:hover { background: #fee2e2; }

    .journal-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
    .journal-table thead th {
        background: #064e3b; color: white;
        padding: 10px 12px; text-align: left; font-weight: 700;
        font-size: 0.8rem;
    }
    .journal-table thead th:first-child { border-radius: 8px 0 0 0; }
    .journal-table thead th:last-child  { border-radius: 0 8px 0 0; }
    .journal-table tbody tr { border-bottom: 1px solid #f1f5f9; }
    .journal-table tbody tr:hover { background: #f0fdf4; }
    .journal-table tbody td { padding: 9px 12px; color: #334155; vertical-align: middle; }

    .badge-hadir { color: #059669; font-weight: 700; }
    .badge-absen { color: #dc2626; font-weight: 700; }
    .badge-izin  { color: #d97706; font-weight: 700; }

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
    .grid-4 { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 14px; }

    .stat-row {
        display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 20px;
    }
    .stat-box {
        flex: 1; min-width: 140px;
        background: #f0fdf4; border: 1.5px solid #bbf7d0;
        border-radius: 14px; padding: 14px 18px;
    }
    .stat-box .label { font-size: 0.78rem; color: #059669; font-weight: 700; }
    .stat-box .value { font-size: 1.7rem; font-weight: 900; color: #065f46; }

    @media(max-width:700px) {
        .grid-2 { grid-template-columns: 1fr; }
        .grid-3 { grid-template-columns: 1fr 1fr; }
        .grid-4 { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<div class="jk-container">

    {{-- Flash --}}
    @if(session('success'))
    <div class="success-flash">✅ {{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="jk-header">
        <div>
            <div class="jk-badge">📔 Jurnal Kegiatan — Semester {{ $semester }} | {{ $activeYear->name }}</div>
            <h1>{{ $eskul->name }}</h1>
            <p>Pembina: {{ $eskul->instructor_name ?? '-' }} &nbsp;·&nbsp; Jadwal: {{ $eskul->schedule ?? '-' }}</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('eskuls.jurnal.print', $eskul) }}" target="_blank" class="btn-primary">
                🖨️ Cetak Jurnal
            </a>
            <a href="{{ route('eskuls.program.index', $eskul) }}" class="btn-outline">📋 Program Kerja</a>
            <a href="{{ route('eskuls.index') }}" class="btn-outline">← Kembali</a>
        </div>
    </div>

    {{-- Statistik Ringkas --}}
    @if($journals->count())
    @php
        $totalHadir      = $journals->sum('hadir');
        $totalTidakHadir = $journals->sum('tidak_hadir');
        $totalIzin       = $journals->sum('izin');
        $totalPertemuan  = $journals->count();
    @endphp
    <div class="stat-row">
        <div class="stat-box">
            <div class="label">⏰ Total Pertemuan</div>
            <div class="value">{{ $totalPertemuan }}</div>
        </div>
        <div class="stat-box" style="background:#eff6ff; border-color:#bfdbfe;">
            <div class="label" style="color:#1d4ed8;">✅ Total Kehadiran</div>
            <div class="value" style="color:#1e3a8a;">{{ $totalHadir }}</div>
        </div>
        <div class="stat-box" style="background:#fef2f2; border-color:#fecaca;">
            <div class="label" style="color:#dc2626;">❌ Total Tidak Hadir</div>
            <div class="value" style="color:#7f1d1d;">{{ $totalTidakHadir }}</div>
        </div>
        <div class="stat-box" style="background:#fffbeb; border-color:#fde68a;">
            <div class="label" style="color:#d97706;">📝 Total Izin</div>
            <div class="value" style="color:#78350f;">{{ $totalIzin }}</div>
        </div>
    </div>
    @endif

    {{-- TABEL Jurnal --}}
    <div class="jk-card">
        <h2><span class="section-icon">📔</span> Daftar Jurnal Kegiatan</h2>

        @if($journals->count())
        <div style="overflow-x:auto; margin-bottom: 20px;">
            <table class="journal-table">
                <thead>
                    <tr>
                        <th style="width:35px">Prtm</th>
                        <th style="width:110px">Tanggal</th>
                        <th>Materi / Kegiatan</th>
                        <th>Deskripsi</th>
                        <th style="width:60px; text-align:center">Hadir</th>
                        <th style="width:60px; text-align:center">Tdk Hadir</th>
                        <th style="width:50px; text-align:center">Izin</th>
                        <th>Catatan</th>
                        <th style="width:55px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($journals as $j)
                    <tr>
                        <td style="text-align:center; font-weight:700; color:#059669;">{{ $j->pertemuan_ke }}</td>
                        <td>{{ \Carbon\Carbon::parse($j->tanggal)->locale('id')->isoFormat('D MMM YYYY') }}</td>
                        <td><strong>{{ $j->materi }}</strong></td>
                        <td style="color:#64748b; font-size:0.83rem;">{{ $j->deskripsi ?? '-' }}</td>
                        <td class="badge-hadir" style="text-align:center;">{{ $j->hadir }}</td>
                        <td class="badge-absen" style="text-align:center;">{{ $j->tidak_hadir }}</td>
                        <td class="badge-izin"  style="text-align:center;">{{ $j->izin }}</td>
                        <td style="color:#64748b; font-size:0.83rem;">{{ $j->catatan ?? '-' }}</td>
                        <td>
                            <form method="POST" action="{{ route('eskuls.jurnal.destroy', [$eskul, $j]) }}" onsubmit="return confirm('Hapus entri jurnal ini?')">
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
            <div class="empty-icon">📔</div>
            <p>Belum ada entri jurnal kegiatan. Tambahkan pertemuan pertama di bawah ini.</p>
        </div>
        @endif

        {{-- FORM Tambah Jurnal --}}
        <details style="margin-top:6px;" @if($journals->isEmpty()) open @endif>
            <summary style="cursor:pointer; font-weight:700; font-size:0.9rem; color:#059669; padding:8px 0; user-select:none;">
                ➕ Tambah Entri Jurnal Pertemuan Baru
            </summary>
            <div style="padding-top:14px;">
                <form method="POST" action="{{ route('eskuls.jurnal.store', $eskul) }}">
                    @csrf
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Tanggal Pertemuan <span style="color:red">*</span></label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Materi / Kegiatan <span style="color:red">*</span></label>
                            <input type="text" name="materi" placeholder="Contoh: Latihan dasar vokal, Pengenalan alat musik..." required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Kegiatan</label>
                        <textarea name="deskripsi" rows="2" placeholder="Uraian singkat kegiatan yang dilakukan..."></textarea>
                    </div>
                    <div class="grid-4">
                        <div class="form-group">
                            <label>✅ Jumlah Hadir</label>
                            <input type="number" name="hadir" value="0" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>❌ Tidak Hadir</label>
                            <input type="number" name="tidak_hadir" value="0" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>📝 Izin</label>
                            <input type="number" name="izin" value="0" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>Catatan</label>
                            <input type="text" name="catatan" placeholder="(opsional)">
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">➕ Simpan Jurnal</button>
                </form>
            </div>
        </details>
    </div>

</div>
@endsection
