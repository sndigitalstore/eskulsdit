<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Kerja Tahunan — {{ $eskul->name }} ({{ $activeYear->name }})</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #f1f5f9;
            padding: 0;
            line-height: 1.5;
        }

        .print-bar {
            position: fixed; top: 0; left: 0; right: 0;
            background: #1e3a5f; color: white;
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 24px; z-index: 1000;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .print-bar .title-info { font-size: 0.92rem; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .print-bar .actions { display: flex; align-items: center; gap: 10px; }
        .print-btn {
            background: #10b981; color: white; border: none;
            padding: 8px 22px; border-radius: 8px; font-weight: 800;
            font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        }
        .print-btn:hover { background: #059669; }
        .close-btn {
            background: rgba(255,255,255,0.15); color: white; border: none;
            padding: 8px 16px; border-radius: 8px; font-weight: 700;
            font-size: 0.88rem; cursor: pointer; text-decoration: none;
        }
        .close-btn:hover { background: rgba(255,255,255,0.25); }

        .period-selector {
            background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3);
            color: white; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;
        }

        .page {
            max-width: 210mm;
            margin: 70px auto 40px;
            padding: 20mm 22mm 20mm 25mm;
            background: white;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            border-radius: 4px;
        }

        /* KOP SURAT RESMI */
        .kop {
            display: flex;
            align-items: center;
            gap: 18px;
            border-bottom: 3.5px double #000;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .kop img {
            width: 82px;
            height: 82px;
            object-fit: contain;
        }
        .kop-text {
            flex: 1;
            text-align: center;
        }
        .kop-text .yayasan {
            font-size: 11pt;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #222;
        }
        .kop-text .sekolah {
            font-size: 19pt;
            font-weight: 900;
            line-height: 1.15;
            letter-spacing: 1px;
            margin: 2px 0;
            color: #000;
        }
        .kop-text .npsn {
            font-size: 8.5pt;
            font-weight: 700;
            color: #333;
            margin-bottom: 2px;
        }
        .kop-text .alamat {
            font-size: 8.5pt;
            color: #222;
            line-height: 1.35;
        }

        /* JUDUL DOKUMEN */
        .doc-title {
            text-align: center;
            margin: 16px 0 16px;
        }
        .doc-title h2 {
            font-size: 13pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #000;
            display: inline-block;
            padding-bottom: 3px;
        }
        .doc-title .sub {
            font-size: 11pt;
            font-weight: 700;
            margin-top: 5px;
            text-transform: uppercase;
        }
        .doc-title .periode {
            font-size: 10pt;
            color: #333;
            margin-top: 2px;
        }

        /* TABLE IDENTITAS */
        .tbl-identitas {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 10.5pt;
        }
        .tbl-identitas td {
            padding: 3px 6px;
            vertical-align: top;
        }
        .tbl-identitas td:first-child {
            width: 180px;
            font-weight: 700;
        }
        .tbl-identitas td:nth-child(2) {
            width: 12px;
        }

        /* SECTION HEADER */
        .section-title {
            background: #1e3a5f;
            color: white;
            font-size: 10pt;
            font-weight: 700;
            padding: 4px 10px;
            margin: 14px 0 6px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .section-body {
            font-size: 10.5pt;
            padding: 2px 4px;
            line-height: 1.6;
            text-align: justify;
        }
        .section-body p { margin-bottom: 4px; }
        .section-body ol, .section-body ul { padding-left: 22px; }
        .section-body ol li, .section-body ul li { margin-bottom: 3px; }

        /* TABLE PROGRAM */
        .tbl-program {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-top: 8px;
        }
        .tbl-program thead th {
            background: #f1f5f9;
            color: #000;
            border: 1px solid #000;
            padding: 7px 5px;
            text-align: center;
            font-size: 9pt;
            font-weight: 800;
        }
        .tbl-program tbody td {
            border: 1px solid #444;
            padding: 6px 6px;
            vertical-align: top;
            font-size: 9pt;
            line-height: 1.35;
        }
        .tbl-program tbody tr:nth-child(even) td { background: #fafafa; }
        .tbl-program .center { text-align: center; }

        /* LEMBAR PENGESAHAN */
        .pengesahan-wrap {
            margin-top: 32px;
            page-break-inside: avoid;
        }
        .pengesahan-meta {
            text-align: right;
            font-size: 10.5pt;
            margin-bottom: 12px;
        }
        .pengesahan {
            display: flex;
            justify-content: space-between;
            font-size: 10.5pt;
        }
        .sign-box {
            text-align: center;
            width: 250px;
        }
        .sign-box .role {
            font-weight: 700;
            margin-top: 3px;
        }
        .sign-box .space {
            height: 65px;
        }
        .sign-box .name {
            font-weight: 700;
            border-top: 1.5px solid #000;
            display: inline-block;
            padding-top: 3px;
            min-width: 180px;
        }
        .sign-box .nip {
            font-size: 9.5pt;
            margin-top: 2px;
        }

        /* PRINT MEDIA STYLES */
        @media print {
            .print-bar { display: none !important; }
            body { background: white; padding: 0; }
            .page {
                margin: 0;
                padding: 12mm 15mm 12mm 20mm;
                max-width: none;
                box-shadow: none;
                border-radius: 0;
            }
            .section-title {
                background: #1e3a5f !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .tbl-program thead th {
                background: #e2e8f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

{{-- Print Bar --}}
<div class="print-bar">
    <div class="title-info">
        <span>🖨️ Program Kerja Eskul — {{ $eskul->name }}</span>
        <span>·</span>
        <select class="period-selector" onchange="window.location.href = '{{ route('eskuls.program.print', $eskul) }}?semester=' + this.value">
            <option value="all" {{ ($filterSemester ?? 'all') === 'all' ? 'selected' : '' }}>1 Tahun Pelajaran Penuh (Juli - Juni)</option>
            <option value="1" {{ ($filterSemester ?? '') === '1' ? 'selected' : '' }}>Semester 1 / Ganjil (Juli - Des)</option>
            <option value="2" {{ ($filterSemester ?? '') === '2' ? 'selected' : '' }}>Semester 2 / Genap (Jan - Jun)</option>
        </select>
    </div>
    <div class="actions">
        <button class="print-btn" onclick="window.print()">
            Cetak / Simpan PDF
        </button>
        <button class="close-btn" onclick="window.close()">
            Tutup
        </button>
    </div>
</div>

<div class="page">

    {{-- KOP SURAT RESMI SDIT AN NADZIR (Sesuai Dokumen Resmi Sekolah) --}}
    @include('partials.official_kop')

    {{-- JUDUL DOKUMEN --}}
    <div class="doc-title">
        <h2>PROGRAM KERJA TAHUNAN EKSTRAKURIKULER</h2>
        <div class="sub">{{ $eskul->name }}</div>
        <div class="periode">
            Tahun Pelajaran {{ $activeYear->name }} 
            @if(($filterSemester ?? 'all') !== 'all')
                — Semester {{ $filterSemester == '1' ? 'Ganjil (I)' : 'Genap (II)' }}
            @else
                (1 Tahun Pelajaran)
            @endif
        </div>
    </div>

    {{-- IDENTITAS PROGRAM --}}
    <table class="tbl-identitas">
        <tr>
            <td>Nama Ekstrakurikuler</td>
            <td>:</td>
            <td><strong>{{ $eskul->name }}</strong></td>
        </tr>
        <tr>
            <td>Pembina / Pelatih</td>
            <td>:</td>
            <td>{{ $eskul->instructor_name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Hari & Jadwal Latihan</td>
            <td>:</td>
            <td>{{ $eskul->schedule ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jumlah Peserta Terdaftar</td>
            <td>:</td>
            <td>{{ $eskul->students->count() }} siswa</td>
        </tr>
        <tr>
            <td>Tahun Pelajaran</td>
            <td>:</td>
            <td>{{ $activeYear->name }} @if(($filterSemester ?? 'all') !== 'all') (Semester {{ $filterSemester }}) @else (1 Tahun Penuh) @endif</td>
        </tr>
        @if($program && $program->sasaran)
        <tr>
            <td>Sasaran Kegiatan</td>
            <td>:</td>
            <td>{{ $program->sasaran }}</td>
        </tr>
        @endif
    </table>

    {{-- LANDASAN: VISI, MISI, TUJUAN, SARANA, DANA --}}
    @if($program)

        {{-- VISI --}}
        @if($program->visi)
        <div class="section-title">A. VISI EKSTRAKURIKULER</div>
        <div class="section-body">
            <p>{{ $program->visi }}</p>
        </div>
        @endif

        {{-- MISI --}}
        @if($program->misi)
        <div class="section-title">B. MISI EKSTRAKURIKULER</div>
        <div class="section-body">
            @php $misiLines = array_filter(array_map('trim', explode("\n", $program->misi))); @endphp
            @if(count($misiLines) > 1)
            <ol>
                @foreach($misiLines as $line)
                    @php $clean = preg_replace('/^\d+[\.\)]\s*/', '', $line); @endphp
                    <li>{{ $clean }}</li>
                @endforeach
            </ol>
            @else
            <p>{{ $program->misi }}</p>
            @endif
        </div>
        @endif

        {{-- TUJUAN --}}
        @if($program->tujuan)
        <div class="section-title">C. TUJUAN PROGRAM</div>
        <div class="section-body">
            @php $tujLines = array_filter(array_map('trim', explode("\n", $program->tujuan))); @endphp
            @if(count($tujLines) > 1)
            <ol>
                @foreach($tujLines as $line)
                    @php $clean = preg_replace('/^\d+[\.\)]\s*/', '', $line); @endphp
                    <li>{{ $clean }}</li>
                @endforeach
            </ol>
            @else
            <p>{{ $program->tujuan }}</p>
            @endif
        </div>
        @endif

        {{-- SARANA & PRASARANA --}}
        @if($program->sarana)
        <div class="section-title">D. SARANA DAN PRASARANA</div>
        <div class="section-body">
            <p>{{ $program->sarana }}</p>
        </div>
        @endif

        {{-- SUMBER DANA --}}
        @if($program->sumber_dana)
        <div class="section-title">E. SUMBER PENDANAAN</div>
        <div class="section-body">
            <p>{{ $program->sumber_dana }}</p>
        </div>
        @endif

    @endif

    {{-- TABEL MATRIKS RENCANA KEGIATAN --}}
    <div class="section-title">F. MATRIKS RENCANA KEGIATAN {{ ($filterSemester ?? 'all') === 'all' ? '1 TAHUN PELAJARAN (12 BULAN)' : 'SEMESTER ' . $filterSemester }}</div>
    
    @if($items->count())
    <table class="tbl-program">
        <thead>
            <tr>
                <th style="width:28px">No</th>
                <th style="width:45px">Sem.</th>
                <th style="width:70px">Bulan</th>
                <th>Rencana Kegiatan</th>
                <th style="width:145px">Tujuan / Indikator Capaian</th>
                <th style="width:85px">Tempat</th>
                <th style="width:85px">Waktu</th>
                <th style="width:115px">Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $item)
            <tr>
                <td class="center">{{ $i+1 }}</td>
                <td class="center"><strong>{{ $item->semester }}</strong></td>
                <td><strong>{{ $item->bulan }}</strong></td>
                <td>{{ $item->kegiatan }}</td>
                <td>{{ $item->tujuan_kegiatan ?? '-' }}</td>
                <td>{{ $item->tempat ?? '-' }}</td>
                <td>{{ $item->waktu ?? '-' }}</td>
                <td>{{ $item->penanggung_jawab ?? $eskul->instructor_name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="font-size:10pt; padding:8px 4px; color:#666; font-style:italic;">Belum ada rencana kegiatan yang terdaftar untuk periode ini.</p>
    @endif

    {{-- LEMBAR PENGESAHAN RESMI --}}
    <div class="pengesahan-wrap">
        <div class="pengesahan-meta">
            {{ $schoolCity }}, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}
        </div>
        <div class="pengesahan">
            <div class="sign-box">
                <p>Mengetahui,</p>
                <p class="role">Pembina Ekstrakurikuler</p>
                <div class="space"></div>
                <p class="name">{{ $eskul->instructor_name ?? '...........................' }}</p>
            </div>
            <div class="sign-box">
                <p>Mengesahkan,</p>
                <p class="role">Kepala Sekolah</p>
                <div class="space"></div>
                <p class="name">{{ $program->kepsek_name ?: $headmasterName }}</p>
                <p class="nip">NIY: {{ $program->nip_kepsek ?: ($headmasterNip ?: '141502002') }}</p>
            </div>
        </div>
    </div>

</div>

</body>
</html>
