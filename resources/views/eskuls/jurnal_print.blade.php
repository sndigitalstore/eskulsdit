<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Kegiatan Ekstrakurikuler {{ $eskul->name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; background: white; }

        .print-bar {
            position: fixed; top: 0; left: 0; right: 0;
            background: #064e3b; color: white;
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 24px; z-index: 1000;
            font-family: Arial, sans-serif;
        }
        .print-bar span { font-size: 0.92rem; font-weight: 700; }
        .print-btn {
            background: #22c55e; color: white; border: none;
            padding: 8px 20px; border-radius: 8px; font-weight: 700;
            font-size: 0.9rem; cursor: pointer;
        }

        .page {
            max-width: 210mm;
            margin: 60px auto 40px;
            padding: 25mm 25mm 20mm 30mm;
            background: white;
        }

        /* KOP */
        .kop {
            display: flex; align-items: center; gap: 18px;
            border-bottom: 4px double #000;
            padding-bottom: 12px; margin-bottom: 16px;
        }
        .kop img { width: 75px; height: 75px; object-fit: contain; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .yayasan  { font-size: 10pt; }
        .kop-text .sekolah  { font-size: 16pt; font-weight: 900; }
        .kop-text .alamat   { font-size: 9pt; color: #333; margin-top: 2px; }
        .kop-text .akreditasi { font-size: 9pt; color: #555; }

        /* JUDUL */
        .doc-title { text-align: center; margin: 18px 0 14px; }
        .doc-title h2 {
            font-size: 13pt; font-weight: 900;
            text-transform: uppercase; letter-spacing: 1px;
            border-bottom: 2px solid #000;
            display: inline-block; padding-bottom: 4px;
        }
        .doc-title p { font-size: 10.5pt; margin-top: 5px; }

        /* IDENTITAS */
        .tbl-identitas { width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 10.5pt; }
        .tbl-identitas td { padding: 3px 6px; vertical-align: top; }
        .tbl-identitas td:first-child { width: 150px; font-weight: 700; }
        .tbl-identitas td:nth-child(2) { width: 12px; }

        /* JUDUL TABEL */
        .tbl-section { font-size: 11pt; font-weight: 700; margin: 16px 0 8px; }

        /* TABEL JURNAL */
        .tbl-jurnal { width: 100%; border-collapse: collapse; font-size: 9.5pt; }
        .tbl-jurnal thead th {
            background: #064e3b; color: white;
            border: 1px solid #000; padding: 7px 5px;
            text-align: center; font-size: 9pt;
        }
        .tbl-jurnal tbody td {
            border: 1px solid #666; padding: 6px 6px;
            vertical-align: middle; font-size: 9pt;
        }
        .tbl-jurnal tbody tr:nth-child(even) td { background: #f9f9f9; }
        .tbl-jurnal .center { text-align: center; }

        /* REKAP */
        .rekap-section {
            margin-top: 18px;
            border: 1px solid #ccc; border-radius: 6px;
            padding: 12px 16px;
        }
        .rekap-section h3 { font-size: 10.5pt; font-weight: 700; margin-bottom: 8px; }
        .rekap-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .rekap-item { text-align: center; }
        .rekap-item .rk-label { font-size: 9pt; color: #555; }
        .rekap-item .rk-val { font-size: 14pt; font-weight: 900; color: #064e3b; }

        /* PENGESAHAN */
        .pengesahan {
            margin-top: 28px;
            display: flex; justify-content: space-between;
            font-size: 10.5pt;
        }
        .sign-box { text-align: center; }
        .sign-box .title { font-weight: 700; }
        .sign-box .space { height: 60px; }
        .sign-box .name {
            font-weight: 700; border-top: 1.5px solid #000;
            display: inline-block; padding-top: 3px; min-width: 150px;
        }
        .sign-box .nip { font-size: 9.5pt; }

        @media print {
            .print-bar { display: none !important; }
            .page { margin: 0; padding: 20mm 20mm 15mm 25mm; max-width: none; }
            body { padding: 0; }
            .tbl-jurnal thead th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="print-bar">
    <span>📔 Jurnal Kegiatan — {{ $eskul->name }} | Semester {{ $semester }} | {{ $activeYear->name }}</span>
    <button class="print-btn" onclick="window.print()">Cetak / Simpan PDF</button>
</div>

<div class="page">

    {{-- KOP --}}
    <div class="kop">
        @php $logoPath = public_path('images/logo.png'); @endphp
        @if(file_exists($logoPath))
        <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah">
        @else
        <div style="width:75px;height:75px;background:#e2e8f0;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;">🏫</div>
        @endif
        <div class="kop-text">
            <div class="yayasan">{{ $setting->yayasan_name ?? 'YAYASAN PENDIDIKAN ISLAM' }}</div>
            <div class="sekolah">{{ $setting->school_name ?? 'SD ISLAM TERPADU' }}</div>
            <div class="alamat">{{ $setting->address ?? 'Jl. Pendidikan No. 1, Kota' }}</div>
            <div class="akreditasi">Terakreditasi — NPSN: {{ $setting->npsn ?? '-' }}</div>
        </div>
    </div>

    {{-- JUDUL --}}
    <div class="doc-title">
        <h2>Jurnal Kegiatan Ekstrakurikuler</h2>
        <p>{{ $eskul->name }} — Semester {{ $semester }} Tahun Ajaran {{ $activeYear->name }}</p>
    </div>

    {{-- IDENTITAS --}}
    <table class="tbl-identitas">
        <tr><td>Nama Eskul</td><td>:</td><td><strong>{{ $eskul->name }}</strong></td></tr>
        <tr><td>Pembina</td><td>:</td><td>{{ $eskul->instructor_name ?? '-' }}</td></tr>
        <tr><td>Jadwal Latihan</td><td>:</td><td>{{ $eskul->schedule ?? '-' }}</td></tr>
        <tr><td>Semester / T.A.</td><td>:</td><td>Semester {{ $semester }} / {{ $activeYear->name }}</td></tr>
        <tr><td>Total Pertemuan</td><td>:</td><td>{{ $journals->count() }} kali</td></tr>
    </table>

    {{-- TABEL JURNAL --}}
    <div class="tbl-section">DAFTAR KEGIATAN PERTEMUAN:</div>

    @if($journals->count())
    <table class="tbl-jurnal">
        <thead>
            <tr>
                <th style="width:32px">Prtm<br>Ke-</th>
                <th style="width:90px">Tanggal</th>
                <th>Materi / Kegiatan</th>
                <th>Deskripsi</th>
                <th style="width:45px">Hadir</th>
                <th style="width:45px">Tdk<br>Hadir</th>
                <th style="width:40px">Izin</th>
                <th style="width:120px">Catatan / Paraf</th>
            </tr>
        </thead>
        <tbody>
            @foreach($journals as $j)
            <tr>
                <td class="center"><strong>{{ $j->pertemuan_ke }}</strong></td>
                <td class="center">{{ \Carbon\Carbon::parse($j->tanggal)->locale('id')->isoFormat('D MMM YYYY') }}</td>
                <td><strong>{{ $j->materi }}</strong></td>
                <td style="color:#333;">{{ $j->deskripsi ?? '' }}</td>
                <td class="center" style="color:#064e3b; font-weight:700;">{{ $j->hadir }}</td>
                <td class="center" style="color:#b91c1c; font-weight:700;">{{ $j->tidak_hadir }}</td>
                <td class="center" style="color:#d97706; font-weight:700;">{{ $j->izin }}</td>
                <td>{{ $j->catatan ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- REKAP --}}
    @php
        $totalHadir = $journals->sum('hadir');
        $totalAbsen = $journals->sum('tidak_hadir');
        $totalIzin  = $journals->sum('izin');
        $totalPrtm  = $journals->count();
    @endphp
    <div class="rekap-section">
        <h3>Rekap Kehadiran Seluruh Pertemuan:</h3>
        <div class="rekap-grid">
            <div class="rekap-item">
                <div class="rk-label">Total Pertemuan</div>
                <div class="rk-val">{{ $totalPrtm }}</div>
            </div>
            <div class="rekap-item">
                <div class="rk-label">Total Kehadiran</div>
                <div class="rk-val" style="color:#064e3b;">{{ $totalHadir }}</div>
            </div>
            <div class="rekap-item">
                <div class="rk-label">Total Tidak Hadir</div>
                <div class="rk-val" style="color:#b91c1c;">{{ $totalAbsen }}</div>
            </div>
            <div class="rekap-item">
                <div class="rk-label">Total Izin</div>
                <div class="rk-val" style="color:#d97706;">{{ $totalIzin }}</div>
            </div>
        </div>
    </div>
    @else
    <p style="font-size:10pt; color:#666; font-style:italic; padding: 8px 0;">Belum ada entri jurnal kegiatan.</p>
    @endif

    {{-- PENGESAHAN --}}
    <div class="pengesahan">
        <div class="sign-box">
            <p class="title">Pembina Ekstrakurikuler</p>
            <div class="space"></div>
            <p class="name">{{ $eskul->instructor_name ?? '...........................' }}</p>
        </div>
        <div class="sign-box">
            <p>Mengetahui,</p>
            <p class="title">Kepala Sekolah</p>
            <div class="space"></div>
            <p class="name">............................................</p>
        </div>
    </div>

</div>
</body>
</html>
