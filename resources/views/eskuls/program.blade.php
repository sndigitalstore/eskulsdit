@extends('layouts.app')

@section('title', 'Program Kerja Eskul - ' . $eskul->name)
@section('page-title', 'Program Kerja Ekstrakurikuler')

@push('styles')
<style>
    .pk-container { max-width: 1160px; margin: 0 auto; padding-bottom: 60px; }

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
        gap: 16px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
    }
    .pk-header h1 { font-size: 1.55rem; font-weight: 800; margin: 0; line-height: 1.2; }
    .pk-header p  { font-size: 0.9rem; color: #94a3b8; margin: 6px 0 0 0; }

    .pk-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(59,130,246,0.25); color: #93c5fd;
        border: 1px solid rgba(147,197,253,0.35);
        padding: 4px 12px; border-radius: 999px;
        font-size: 0.8rem; font-weight: 700; margin-bottom: 8px;
    }

    .pk-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .pk-card h2 {
        font-size: 1.05rem; font-weight: 800; color: #1e293b;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 12px; margin-bottom: 20px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
    }
    .pk-card h2 .title-left {
        display: flex; align-items: center; gap: 10px;
    }
    .pk-card h2 .section-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: #eff6ff; color: #2563eb;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
    }

    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #475569; margin-bottom: 6px; }
    .form-group input, .form-group textarea, .form-group select {
        width: 100%; padding: 10px 14px;
        border: 1.5px solid #cbd5e1; border-radius: 10px;
        font-size: 0.9rem; color: #1e293b;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #f8fafc;
        resize: vertical;
        font-family: inherit;
    }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
        border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
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

    .btn-warning-soft {
        background: #fffbeb; color: #b45309; border: 1.5px solid #fde68a;
        border-radius: 10px; padding: 10px 18px; font-weight: 700; font-size: 0.88rem;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        transition: all 0.2s;
    }
    .btn-warning-soft:hover { background: #fef3c7; border-color: #f59e0b; }

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

    /* Filter Pills */
    .filter-pills-bar {
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        margin-bottom: 18px;
    }
    .filter-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 16px; border-radius: 999px;
        font-size: 0.84rem; font-weight: 700;
        text-decoration: none; border: 1.5px solid #e2e8f0;
        color: #64748b; background: #ffffff;
        transition: all 0.2s ease;
    }
    .filter-pill:hover { border-color: #cbd5e1; color: #1e293b; }
    .filter-pill.active {
        background: #1e3a5f; color: #ffffff; border-color: #1e3a5f;
        box-shadow: 0 3px 10px rgba(30,58,95,0.25);
    }

    .badge-sem {
        font-size: 0.72rem; font-weight: 800; padding: 3px 8px; border-radius: 6px;
        display: inline-block; text-transform: uppercase;
    }
    .badge-sem-1 { background: #e0f2fe; color: #0369a1; }
    .badge-sem-2 { background: #dcfce7; color: #15803d; }

    .items-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
    .items-table thead th {
        background: #1e3a5f; color: white;
        padding: 11px 12px; text-align: left; font-weight: 700;
        font-size: 0.8rem;
    }
    .items-table thead th:first-child { border-radius: 8px 0 0 0; }
    .items-table thead th:last-child  { border-radius: 0 8px 0 0; }
    .items-table tbody tr { border-bottom: 1px solid #f1f5f9; }
    .items-table tbody tr:hover { background: #f8fafc; }
    .items-table tbody td { padding: 11px 12px; color: #334155; vertical-align: middle; }

    .empty-state {
        text-align: center; padding: 40px 20px;
        color: #94a3b8; font-size: 0.9rem;
    }
    .empty-state .empty-icon { font-size: 2.5rem; margin-bottom: 10px; }

    .success-flash {
        background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;
        border-radius: 12px; padding: 14px 20px; margin-bottom: 20px;
        font-weight: 700; font-size: 0.92rem; display: flex; align-items: center; gap: 10px;
    }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }

    @media (max-width: 768px) {
        .grid-2 { grid-template-columns: 1fr; }
        .grid-3 { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="pk-container">

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="success-flash">
        <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Top Hero Header --}}
    <div class="pk-header">
        <div>
            <div class="pk-badge">
                <i class="fas fa-school"></i> {{ $schoolName }} &nbsp;·&nbsp; Program Kerja Tahunan {{ $activeYear->name }}
            </div>
            <h1>{{ $eskul->name }}</h1>
            <p>
                <i class="fas fa-user-tie"></i> Pembina: <strong>{{ $eskul->instructor_name ?? '-' }}</strong> &nbsp;·&nbsp;
                <i class="fas fa-clock"></i> Jadwal: <strong>{{ $eskul->schedule ?? '-' }}</strong> &nbsp;·&nbsp;
                <i class="fas fa-users"></i> Peserta: <strong>{{ $eskul->students->count() }} Siswa</strong>
            </p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <a href="{{ route('eskuls.program.print', [$eskul, 'semester' => $filterSemester]) }}" target="_blank" class="btn-success">
                🖨️ Cetak Dokumen Resmi
            </a>
            <a href="{{ route('eskuls.jurnal.index', $eskul) }}" class="btn-outline">
                📔 Jurnal Kegiatan
            </a>
            <a href="{{ route('eskuls.index') }}" class="btn-outline">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- Identitas & Landasan Program (Visi, Misi, Tujuan, Sasaran, Pengesahan) --}}
    <div class="pk-card">
        <h2>
            <div class="title-left">
                <span class="section-icon"><i class="fas fa-bullseye"></i></span>
                <span>Landasan & Kebijakan Program Kerja</span>
            </div>
            <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">
                Tahun Pelajaran: {{ $activeYear->name }}
            </span>
        </h2>

        <form method="POST" action="{{ route('eskuls.program.store', $eskul) }}">
            @csrf

            <div class="form-group">
                <label>Visi Ekstrakurikuler</label>
                <textarea name="visi" rows="2" placeholder="Visi utama kegiatan ekstrakurikuler..." required>{{ old('visi', $program->visi) }}</textarea>
            </div>

            <div class="form-group">
                <label>Misi Ekstrakurikuler</label>
                <textarea name="misi" rows="4" placeholder="Tuliskan misi bertahap (pisahkan dengan nomor / baris baru)..." required>{{ old('misi', $program->misi) }}</textarea>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Tujuan Program</label>
                    <textarea name="tujuan" rows="3" placeholder="Tujuan yang diharapkan dicapai..." required>{{ old('tujuan', $program->tujuan) }}</textarea>
                </div>
                <div class="form-group">
                    <label>Sasaran & Target Peserta</label>
                    <textarea name="sasaran" rows="3" placeholder="Contoh: Seluruh siswa kelas 1 s.d. 6 yang terdaftar..." required>{{ old('sasaran', $program->sasaran) }}</textarea>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Sarana & Prasarana</label>
                    <textarea name="sarana" rows="2" placeholder="Fasilitas, lapangan, ruangan, perlengkapan...">{{ old('sarana', $program->sarana) }}</textarea>
                </div>
                <div class="form-group">
                    <label>Sumber Dana</label>
                    <textarea name="sumber_dana" rows="2" placeholder="BOS, Kas Mandiri, Yayasan...">{{ old('sumber_dana', $program->sumber_dana) }}</textarea>
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 18px 0;">

            <p style="font-size:0.85rem; color:#1e293b; font-weight:800; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                <i class="fas fa-file-signature" style="color: #2563eb;"></i> Data Pengesahan Dokumen Resmi (Akreditasi & PKKS)
            </p>

            <div class="grid-2">
                <div class="form-group">
                    <label>Nama Kepala Sekolah</label>
                    <input type="text" name="kepsek_name" value="{{ old('kepsek_name', $program->kepsek_name ?: $headmasterName) }}" placeholder="Nama Lengkap & Gelar Kepala Sekolah" required>
                </div>
                <div class="form-group">
                    <label>NIP / NIY Kepala Sekolah</label>
                    <input type="text" name="nip_kepsek" value="{{ old('nip_kepsek', $program->nip_kepsek ?: $headmasterNip) }}" placeholder="141502002">
                </div>
            </div>

            <button type="submit" class="btn-primary">
                💾 Simpan Landasan Program
            </button>
        </form>
    </div>

    {{-- Rencana Kegiatan (Matriks 1 Tahun / 12 Bulan) --}}
    <div class="pk-card">
        <h2>
            <div class="title-left">
                <span class="section-icon"><i class="fas fa-calendar-alt"></i></span>
                <span>Matriks Rencana Kegiatan (Program 1 Tahun Penuh)</span>
            </div>

            <div style="display: flex; gap: 8px; align-items: center;">
                <form method="POST" action="{{ route('eskuls.program.generate-template', $eskul) }}" onsubmit="return confirm('Muat ulang template standar 1 tahun (12 bulan)? Kegiatan lama pada tahun ini akan disesuaikan.')">
                    @csrf
                    <button type="submit" class="btn-warning-soft" title="Muat draf 12 bulan dari Juli hingga Juni">
                        ⚡ Muat / Reset Template 1 Tahun
                    </button>
                </form>
            </div>
        </h2>

        {{-- Filter Pills --}}
        <div class="filter-pills-bar">
            <span style="font-size: 0.82rem; font-weight: 700; color: #475569; margin-right: 4px;">Tampilkan Periode:</span>
            <a href="{{ route('eskuls.program.index', [$eskul, 'semester' => 'all']) }}" class="filter-pill {{ $filterSemester === 'all' ? 'active' : '' }}">
                <i class="fas fa-calendar"></i> 1 Tahun Penuh (Juli - Juni) ({{ $items->where('semester', '!=', '')->count() }})
            </a>
            <a href="{{ route('eskuls.program.index', [$eskul, 'semester' => '1']) }}" class="filter-pill {{ $filterSemester === '1' ? 'active' : '' }}">
                <i class="fas fa-sun"></i> Semester 1 / Ganjil (Juli - Des)
            </a>
            <a href="{{ route('eskuls.program.index', [$eskul, 'semester' => '2']) }}" class="filter-pill {{ $filterSemester === '2' ? 'active' : '' }}">
                <i class="fas fa-moon"></i> Semester 2 / Genap (Jan - Jun)
            </a>
        </div>

        @if($items->count())
        <div style="overflow-x:auto; margin-bottom:24px;">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:38px; text-align:center;">No</th>
                        <th style="width:75px">Semester</th>
                        <th style="width:100px">Bulan</th>
                        <th>Kegiatan Utama</th>
                        <th>Tujuan / Capaian</th>
                        <th style="width:110px">Tempat</th>
                        <th style="width:110px">Waktu</th>
                        <th style="width:130px">Penanggung Jawab</th>
                        <th style="width:50px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i => $item)
                    <tr>
                        <td style="text-align:center; font-weight:700;">{{ $i+1 }}</td>
                        <td>
                            @if($item->semester == '1')
                                <span class="badge-sem badge-sem-1">Sem 1</span>
                            @else
                                <span class="badge-sem badge-sem-2">Sem 2</span>
                            @endif
                        </td>
                        <td><strong>{{ $item->bulan }}</strong></td>
                        <td style="font-weight: 600; color: #0f172a;">{{ $item->kegiatan }}</td>
                        <td style="color: #475569; font-size: 0.85rem;">{{ $item->tujuan_kegiatan ?? '-' }}</td>
                        <td>{{ $item->tempat ?? '-' }}</td>
                        <td>{{ $item->waktu ?? '-' }}</td>
                        <td style="font-size: 0.85rem;">{{ $item->penanggung_jawab ?? $eskul->instructor_name ?? '-' }}</td>
                        <td style="text-align:center;">
                            <form method="POST" action="{{ route('eskuls.program.item.destroy', [$eskul, $item]) }}" onsubmit="return confirm('Hapus kegiatan bulan {{ $item->bulan }} ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger-sm" title="Hapus Baris">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-folder-open"></i></div>
            <p style="font-weight: 600; color: #64748b; margin-bottom: 12px;">Belum ada item kegiatan untuk periode ini.</p>
            <form method="POST" action="{{ route('eskuls.program.generate-template', $eskul) }}">
                @csrf
                <button type="submit" class="btn-primary">
                    ⚡ Muat Rencana Kerja 1 Tahun Otomatis
                </button>
            </form>
        </div>
        @endif

        {{-- Form Tambah Kegiatan Manual --}}
        <details style="margin-top:10px; background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 14px 18px;">
            <summary style="cursor:pointer; font-weight:700; font-size:0.92rem; color:#2563eb; user-select:none; display:inline-flex; align-items:center; gap:8px;">
                <i class="fas fa-plus-circle"></i> Tambah Baris Kegiatan Baru
            </summary>
            <div style="padding-top: 16px;">
                <form method="POST" action="{{ route('eskuls.program.item.store', $eskul) }}">
                    @csrf
                    <div class="grid-3">
                        <div class="form-group">
                            <label>Bulan Pelaksanaan <span style="color:red">*</span></label>
                            <input type="text" name="bulan" list="bulan-list" placeholder="Contoh: Juli, Agustus..." required>
                            <datalist id="bulan-list">
                                @foreach(['Juli','Agustus','September','Oktober','November','Desember','Januari','Februari','Maret','April','Mei','Juni'] as $b)
                                <option value="{{ $b }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Semester (Opsional)</label>
                            <select name="semester">
                                <option value="">Otomatis dari Nama Bulan</option>
                                <option value="1">Semester 1 (Ganjil)</option>
                                <option value="2">Semester 2 (Genap)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Kegiatan <span style="color:red">*</span></label>
                            <input type="text" name="kegiatan" placeholder="Nama materi / agenda kegiatan" required>
                        </div>
                    </div>

                    <div class="grid-3">
                        <div class="form-group">
                            <label>Tujuan / Capaian Kegiatan</label>
                            <input type="text" name="tujuan_kegiatan" placeholder="Tujuan yang diharapkan">
                        </div>
                        <div class="form-group">
                            <label>Tempat / Lokasi</label>
                            <input type="text" name="tempat" placeholder="Aula, Lapangan, Kelas...">
                        </div>
                        <div class="form-group">
                            <label>Waktu Pelaksanaan</label>
                            <input type="text" name="waktu" placeholder="Minggu ke-1, Setiap Jumat...">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label>Penanggung Jawab</label>
                            <input type="text" name="penanggung_jawab" placeholder="Nama Pembina / Pelatih" value="{{ $eskul->instructor_name ?? '' }}">
                        </div>
                        <div style="display:flex; align-items:flex-end; margin-bottom:16px;">
                            <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">
                                <i class="fas fa-plus"></i> Simpan Kegiatan Baru
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </details>
    </div>

</div>
@endsection
