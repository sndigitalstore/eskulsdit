<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Kerja Ekstrakurikuler {{ $eskul->name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: white;
            padding: 0;
        }

        .print-bar {
            position: fixed; top: 0; left: 0; right: 0;
            background: #1e3a5f; color: white;
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

        /* KOP SURAT */
        .kop {
            display: flex;
            align-items: center;
            gap: 18px;
            border-bottom: 4px double #000;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .kop img { width: 75px; height: 75px; object-fit: contain; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .yayasan { font-size: 10pt; font-weight: normal; }
        .kop-text .sekolah { font-size: 16pt; font-weight: 900; line-height: 1.2; }
        .kop-text .alamat { font-size: 9pt; color: #333; margin-top: 2px; }
        .kop-text .akreditasi { font-size: 9pt; color: #555; }

        /* JUDUL DOKUMEN */
        .doc-title {
            text-align: center;
            margin: 18px 0 14px;
        }
        .doc-title h2 {
            font-size: 13pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #000;
            display: inline-block;
            padding-bottom: 4px;
        }
        .doc-title p { font-size: 10.5pt; margin-top: 5px; }

        /* TABLE IDENTITAS */
        .tbl-identitas { width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 10.5pt; }
        .tbl-identitas td { padding: 4px 8px; vertical-align: top; }
        .tbl-identitas td:first-child { width: 160px; font-weight: 700; }
        .tbl-identitas td:nth-child(2) { width: 12px; }

        /* SECTION */
        .section-title {
            background: #1e3a5f;
            color: white;
            font-size: 10.5pt;
            font-weight: 700;
            padding: 5px 10px;
            margin: 14px 0 6px;
        }
        .section-body { font-size: 10.5pt; padding: 0 4px; line-height: 1.7; }
        .section-body p { margin-bottom: 4px; }
        .section-body ol, .section-body ul { padding-left: 20px; }
        .section-body ol li, .section-body ul li { margin-bottom: 2px; }

        /* TABLE PROGRAM */
        .tbl-program {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-top: 6px;
        }
        .tbl-program thead th {
            background: #1e3a5f;
            color: white;
            border: 1px solid #000;
            padding: 7px 6px;
            text-align: center;
            font-size: 9.5pt;
        }
        .tbl-program tbody td {
            border: 1px solid #666;
            padding: 6px 7px;
            vertical-align: middle;
            font-size: 9.5pt;
        }
        .tbl-program tbody tr:nth-child(even) td { background: #f9f9f9; }
        .tbl-program .center { text-align: center; }

        /* PENGESAHAN */
        .pengesahan {
            margin-top: 28px;
            display: flex;
            justify-content: space-between;
            font-size: 10.5pt;
        }
        .sign-box { text-align: center; }
        .sign-box .title { font-weight: 700; }
        .sign-box .space { height: 60px; }
        .sign-box .name { font-weight: 700; border-top: 1.5px solid #000; display: inline-block; padding-top: 3px; min-width: 150px; }
        .sign-box .nip { font-size: 9.5pt; }

        /* PRINT STYLES */
        @media print {
            .print-bar { display: none !important; }
            .page { margin: 0; padding: 20mm 20mm 15mm 25mm; max-width: none; }
            body { padding: 0; }
            .section-title { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .tbl-program thead th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="print-bar">
    <span>🖨️ Program Kerja — {{ $eskul->name }} | Semester {{ $semester }} | {{ $activeYear->name }}</span>
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
        <h2>Program Kerja Ekstrakurikuler</h2>
        <p>{{ $eskul->name }} — Semester {{ $semester }} Tahun Ajaran {{ $activeYear->name }}</p>
    </div>

    {{-- IDENTITAS PROGRAM --}}
    <table class="tbl-identitas">
        <tr><td>Nama Eskul</td><td>:</td><td><strong>{{ $eskul->name }}</strong></td></tr>
        <tr><td>Pembina</td><td>:</td><td>{{ $eskul->instructor_name ?? '-' }}</td></tr>
        <tr><td>Jadwal Latihan</td><td>:</td><td>{{ $eskul->schedule ?? '-' }}</td></tr>
        <tr><td>Jumlah Peserta</td><td>:</td><td>{{ $eskul->students->count() }} siswa</td></tr>
        <tr><td>Tahun Ajaran</td><td>:</td><td>{{ $activeYear->name }} — Semester {{ $semester }}</td></tr>
        @if($program && $program->sasaran)
        <tr><td>Sasaran</td><td>:</td><td>{{ $program->sasaran }}</td></tr>
        @endif
    </table>

    @if($program)

    {{-- VISI --}}
    @if($program->visi)
    <div class="section-title">A. VISI</div>
    <div class="section-body"><p>{{ $program->visi }}</p></div>
    @endif

    {{-- MISI --}}
    @if($program->misi)
    <div class="section-title">B. MISI</div>
    <div class="section-body">
        @php $misiLines = array_filter(explode("\n", $program->misi)); @endphp
        @if(count($misiLines) > 1)
        <ol>@foreach($misiLines as $line)<li>{{ trim($line, "1234567890. ") }}</li>@endforeach</ol>
        @else
        <p>{{ $program->misi }}</p>
        @endif
    </div>
    @endif

    {{-- TUJUAN --}}
    @if($program->tujuan)
    <div class="section-title">C. TUJUAN</div>
    <div class="section-body">
        @php $tujLines = array_filter(explode("\n", $program->tujuan)); @endphp
        @if(count($tujLines) > 1)
        <ol>@foreach($tujLines as $line)<li>{{ trim($line, "1234567890. ") }}</li>@endforeach</ol>
        @else
        <p>{{ $program->tujuan }}</p>
        @endif
    </div>
    @endif

    {{-- SARANA --}}
    @if($program->sarana)
    <div class="section-title">D. SARANA & PRASARANA</div>
    <div class="section-body"><p>{{ $program->sarana }}</p></div>
    @endif

    {{-- SUMBER DANA --}}
    @if($program->sumber_dana)
    <div class="section-title">E. SUMBER DANA</div>
    <div class="section-body"><p>{{ $program->sumber_dana }}</p></div>
    @endif

    @endif

    {{-- TABEL RENCANA KEGIATAN --}}
    <div class="section-title">F. RENCANA KEGIATAN</div>
    @if($items->count())
    <table class="tbl-program">
        <thead>
            <tr>
                <th style="width:32px">No</th>
                <th style="width:70px">Bulan</th>
                <th>Kegiatan</th>
                <th style="width:130px">Tujuan Kegiatan</th>
                <th style="width:85px">Tempat</th>
                <th style="width:85px">Waktu</th>
                <th style="width:110px">Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $item)
            <tr>
                <td class="center">{{ $i+1 }}</td>
                <td><strong>{{ $item->bulan }}</strong></td>
                <td>{{ $item->kegiatan }}</td>
                <td>{{ $item->tujuan_kegiatan ?? '-' }}</td>
                <td class="center">{{ $item->tempat ?? '-' }}</td>
                <td class="center">{{ $item->waktu ?? '-' }}</td>
                <td>{{ $item->penanggung_jawab ?? $eskul->instructor_name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="font-size:10pt; padding:8px 4px; color:#666; font-style:italic;">Rencana kegiatan belum diisi.</p>
    @endif

    {{-- PENGESAHAN --}}
    <div class="pengesahan">
        <div class="sign-box">
            <p class="title">Pembina Ekstrakurikuler</p>
            <div class="space"></div>
            <p class="name">{{ $eskul->instructor_name ?? '...........................' }}</p>
        </div>
        <div class="sign-box">
            @php
                $today = \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y');
            @endphp
            <p>Mengetahui,</p>
            <p class="title">Kepala Sekolah</p>
            <div class="space"></div>
            <p class="name">{{ $program->kepsek_name ?? '...........................' }}</p>
            @if($program && $program->nip_kepsek)
            <p class="nip">NIP: {{ $program->nip_kepsek }}</p>
            @endif
        </div>
    </div>

</div>
</body>
</html>
