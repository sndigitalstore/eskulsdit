<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Lulus Munaqosah - {{ $registration->student->name ?? 'Siswa' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #1e293b;
            line-height: 1.45;
            background: #fff;
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }
        .cert-container {
            border: 4px double #065f46;
            padding: 25px 35px;
            position: relative;
            box-sizing: border-box;
            background: #fff;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #065f46;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-right: 18px;
        }
        .school-info {
            flex: 1;
            text-align: center;
        }
        .school-info h2 {
            font-size: 15pt;
            font-weight: bold;
            margin: 0;
            color: #065f46;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .school-info h3 {
            font-size: 12pt;
            font-weight: bold;
            margin: 2px 0;
            color: #1e293b;
        }
        .school-info p {
            font-size: 8.5pt;
            margin: 0;
            color: #475569;
        }
        .cert-title {
            text-align: center;
            margin: 15px 0 10px 0;
        }
        .cert-title h1 {
            font-size: 14pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #065f46;
            margin: 0;
            text-decoration: underline;
        }
        .cert-title .cert-no {
            font-size: 9.5pt;
            color: #475569;
            margin-top: 3px;
        }
        .intro-text {
            text-align: justify;
            margin: 12px 0;
            font-size: 10.5pt;
        }
        .bio-table {
            width: 85%;
            margin: 0 auto 12px auto;
            border-collapse: collapse;
            font-size: 10.5pt;
        }
        .bio-table td {
            padding: 3px 6px;
        }
        .score-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            font-size: 10pt;
        }
        .score-table th, .score-table td {
            border: 1px solid #334155;
            padding: 6px 10px;
        }
        .score-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
        }
        .signatures {
            margin-top: 25px;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        .sign-box {
            width: 42%;
            text-align: center;
            font-size: 10.5pt;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="background: #f1f5f9; padding: 12px; text-align: center; border-bottom: 1px solid #cbd5e1; margin-bottom: 15px;">
        <button onclick="window.print()" style="background: #047857; color: white; border: none; padding: 8px 20px; font-size: 14px; font-weight: bold; border-radius: 6px; cursor: pointer;">
            🖨️ Cetak Surat Keterangan Munaqosah
        </button>
    </div>

    <div class="cert-container">
        <!-- Header / Kop Surat -->
        <div class="header">
            <img src="{{ asset('logo.png') }}" class="logo" alt="Logo">
            <div class="school-info">
                <h2>YAYASAN PENDIDIKAN ISLAM AN NADZIR</h2>
                <h3>SDIT AN NADZIR KABUPATEN SERANG</h3>
                <p>Bendungan Karet Cisirih KP. Cukang RT. 09 RW. 02 Desa Kamasan Kecamatan Cinangka Kabupaten Serang - Banten 42167</p>
                <p>Website: sditannadzir.sch.id | Email: sditannadzir@gmail.com</p>
            </div>
        </div>

        <!-- Judul Dokumen -->
        <div class="cert-title">
            <h1>SURAT TANDA LULUS MUNAQOSAH</h1>
            <div class="cert-no">Nomor: MNQ/SDIT-AN/{{ date('Y') }}/{{ str_pad($registration->id, 4, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- Paragraf Pembuka -->
        <p class="intro-text">
            Dewan Munaqisy Lembaga Penjamin Mutu Al-Qur'an SDIT An Nadzir menerangkan dengan sesungguhnya bahwa santri:
        </p>

        <!-- Biodata Siswa -->
        <table class="bio-table">
            <tr>
                <td style="width: 32%;"><strong>Nama Santri</strong></td>
                <td style="width: 3%;">:</td>
                <td style="font-weight: bold; font-size: 11pt;">{{ strtoupper($registration->student->name ?? '-') }}</td>
            </tr>
            <tr>
                <td><strong>Nomor Induk Siswa (NIS)</strong></td>
                <td>:</td>
                <td>{{ $registration->student->nis ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Kelas</strong></td>
                <td>:</td>
                <td>{{ $registration->student->class ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Bidang Munaqosah</strong></td>
                <td>:</td>
                <td><strong>{{ $registration->type === 'tilawati' ? 'Tilawati Al-Qur\'an' : 'Tahfidz Al-Qur\'an' }}</strong></td>
            </tr>
            <tr>
                <td><strong>Materi / Jenjang Ujian</strong></td>
                <td>:</td>
                <td><strong>{{ $registration->current_level }}</strong> Menuju <strong>{{ $registration->target_level }}</strong></td>
            </tr>
        </table>

        <p class="intro-text">
            Telah mengikuti <strong>Ujian Munaqosah</strong> pada tanggal 
            <strong>{{ $registration->exam_date ? $registration->exam_date->isoFormat('D MMMM Y') : ($registration->tested_at ? $registration->tested_at->isoFormat('D MMMM Y') : date('d F Y')) }}</strong> 
            di hadapan Dewan Penguji (Munaqisy), dengan rincian perolehan nilai sebagai berikut:
        </p>

        <!-- Rincian Nilai Rubrik -->
        <table class="score-table">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th>Aspek / Rubrik Penilaian</th>
                    <th style="width: 18%;">Bobot</th>
                    <th style="width: 20%;">Nilai (0 - 100)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>Kelancaran Hafalan / Fashahah</td>
                    <td style="text-align: center;">30%</td>
                    <td style="text-align: center; font-weight: bold;">{{ number_format($registration->score_fashahah ?? 0, 1) }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">2</td>
                    <td>Kaidah Hukum Tajwid & Mad</td>
                    <td style="text-align: center;">30%</td>
                    <td style="text-align: center; font-weight: bold;">{{ number_format($registration->score_tajwid ?? 0, 1) }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">3</td>
                    <td>Makharijul Huruf & Sifatul Huruf</td>
                    <td style="text-align: center;">25%</td>
                    <td style="text-align: center; font-weight: bold;">{{ number_format($registration->score_makhraj ?? 0, 1) }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">4</td>
                    <td>Adab Tilawah & Irama / Lagu Tartil</td>
                    <td style="text-align: center;">15%</td>
                    <td style="text-align: center; font-weight: bold;">{{ number_format($registration->score_adab_lagu ?? 0, 1) }}</td>
                </tr>
                <tr style="background: #f8fafc; font-weight: bold;">
                    <td colspan="3" style="text-align: right; padding-right: 15px;">NILAI AKHIR / PREDIKAT :</td>
                    <td style="text-align: center; font-size: 11pt; color: #065f46;">
                        {{ number_format($registration->final_score ?? 0, 1) }} ({{ strtoupper($registration->predicate ?? 'MUMTAZ') }})
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="intro-text" style="margin-top: 10px;">
            Berdasarkan hasil penilaian di atas, santri dinyatakan 
            <strong style="color: #065f46; font-size: 11pt;">LULUS MUNAQOSAH</strong> 
            dan direkomendasikan untuk melanjutkan ke <strong>{{ $registration->target_level }}</strong>.
        </p>

        <!-- Tanda Tangan -->
        <div class="signatures">
            <div class="sign-box">
                <p style="margin: 0 0 50px 0;">
                    Dewan Penguji (Munaqisy),
                </p>
                <p style="font-weight: bold; margin: 0; text-decoration: underline;">
                    {{ $registration->examinerUser->name ?? ($registration->examiner->user->name ?? 'Penguji Munaqosah') }}
                </p>
                <p style="margin: 0; font-size: 8.5pt; color: #475569;">
                    No. Sertifikat: {{ $registration->examiner->certificate_number ?? '-' }}
                </p>
            </div>

            <div class="sign-box">
                <p style="margin: 0 0 50px 0;">
                    Cinangka, {{ date('d F Y') }}<br>
                    Kepala Sekolah SDIT An Nadzir,
                </p>
                <p style="font-weight: bold; margin: 0; text-decoration: underline;">
                    {{ $headmaster->name ?? 'Kepala Sekolah' }}
                </p>
                <p style="margin: 0; font-size: 8.5pt; color: #475569;">
                    NIP/NIY: {{ $headmaster->username ?? '-' }}
                </p>
            </div>
        </div>

    </div>

</body>
</html>
