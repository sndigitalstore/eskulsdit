<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Seluruh Kelompok {{ $typeLabel }} - Tahun Pelajaran {{ $yearName }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #000;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }

        .no-print-bar {
            background: #1e293b;
            color: white;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 9999;
        }

        .btn-print-now {
            background: #10b981;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.9rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .page-container {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 15mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            box-sizing: border-box;
            page-break-after: always;
        }

        .page-container:last-child {
            page-break-after: auto;
        }

        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .kop-logo {
            width: 65px;
            height: auto;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text h1 {
            margin: 0;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text h2 {
            margin: 2px 0 0 0;
            font-size: 12pt;
            font-weight: bold;
            color: #1e293b;
        }

        .kop-text p {
            margin: 2px 0 0 0;
            font-size: 8.5pt;
            font-style: italic;
            color: #475569;
        }

        .report-title-box {
            text-align: center;
            margin: 14px 0 16px 0;
        }

        .report-title-box h3 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .report-title-box p {
            margin: 4px 0 0 0;
            font-size: 9.5pt;
            font-family: sans-serif;
            color: #334155;
        }

        .meta-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-family: sans-serif;
            font-size: 9pt;
        }

        .meta-info-table td {
            padding: 3px 0;
            border: none;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
        }

        .report-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            border: 1px solid #94a3b8;
            padding: 7px 6px;
        }

        .report-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: middle;
        }

        .signature-section {
            margin-top: 30px;
            width: 100%;
            font-family: 'Times New Roman', serif;
            font-size: 10pt;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            border: none;
            vertical-align: top;
            text-align: center;
            width: 50%;
        }

        @media print {
            .no-print-bar { display: none !important; }
            body { background: white; }
            .page-container {
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-print" style="color: #34d399; font-size: 1.2rem;"></i>
            <span style="font-weight: bold; font-family: sans-serif;">Pratinjau Cetak Seluruh Kelompok {{ $typeLabel }} ({{ $groups->count() }} Kelompok) - TP {{ $yearName }}</span>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <button onclick="window.print()" class="btn-print-now">
                <i class="fas fa-print"></i> Cetak Seluruh Kelompok (PDF / Printer)
            </button>
            <button onclick="window.close()" style="background: #475569; color: white; border: none; padding: 8px 14px; border-radius: 8px; font-weight: bold; font-size: 0.85rem; cursor: pointer;">
                Tutup
            </button>
        </div>
    </div>

    @php
        $currentDateIndo = \Carbon\Carbon::now()->isoFormat('D MMMM Y');
        $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
        $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
        $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : 'Nur\'asiah, S.Pd.I');
    @endphp

    @forelse($groups as $group)
        <div class="page-container">
            
            <!-- KOP SURAT RESMI -->
            <div class="kop-surat">
                <img src="{{ asset('logo.png') }}" class="kop-logo" alt="Logo SDIT AN NADZIR" onerror="this.style.display='none'">
                <div class="kop-text">
                    <h1>AN NADZIR ISLAMIC SCHOOL</h1>
                    <h2>SDIT AN NADZIR</h2>
                    <p>Bendungan Karet Cisirih, Kp. Cukang RT.09/RW.02, Desa Kamasan, Kecamatan Cinangka, Kabupaten Serang, Provinsi Banten 42167</p>
                </div>
            </div>

            <!-- JUDUL DOKUMEN -->
            <div class="report-title-box">
                <h3>DAFTAR PEMBAGIAN KELOMPOK MENTORING {{ strtoupper($typeLabel) }}</h3>
                <p>Tahun Pelajaran {{ $yearName }}</p>
            </div>

            <!-- META INFO KELOMPOK -->
            <table class="meta-info-table">
                <tr>
                    <td style="width: 18%; font-weight: bold;">Nama Kelompok</td>
                    <td style="width: 32%;">: <strong>{{ $group->name }}</strong></td>
                    <td style="width: 18%; font-weight: bold;">Target / Jenjang</td>
                    <td style="width: 32%;">: {{ $group->target_level ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Pembimbing / Mentor</td>
                    <td>: <strong>{{ $group->mentor_name }}</strong></td>
                    <td style="font-weight: bold;">Jadwal & Tempat</td>
                    <td>: {{ $group->schedule ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Jumlah Anggota</td>
                    <td>: <strong>{{ $group->students->count() }} Orang Siswa</strong></td>
                    <td style="font-weight: bold;">Tanggal Cetak</td>
                    <td>: {{ $currentDateIndo }}</td>
                </tr>
            </table>

            <!-- TABEL ANGGOTA SISWA -->
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 36%; text-align: left; padding-left: 8px;">Nama Lengkap Siswa</th>
                        <th style="width: 15%;">NIS</th>
                        <th style="width: 14%;">Kelas</th>
                        <th style="width: 30%; text-align: left; padding-left: 8px;">Catatan / Target Capaian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($group->students as $index => $student)
                        <tr>
                            <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                            <td style="font-weight: bold; color: #0f172a;">{{ $student->name }}</td>
                            <td style="text-align: center; color: #475569;">{{ $student->nis ?? '-' }}</td>
                            <td style="text-align: center; font-weight: 600;">{{ $student->class }}</td>
                            <td style="color: #334155;">{{ $student->pivot->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 25px; color: #94a3b8; font-style: italic;">
                                Belum ada siswa yang terdaftar di kelompok ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- TANDA TANGAN -->
            <div class="signature-section">
                <table class="signature-table">
                    <tr>
                        <td>
                            Mengetahui,<br>
                            <strong>Kepala SDIT AN NADZIR</strong>
                            <br><br><br><br><br>
                            <strong><u>{{ $headmasterName }}</u></strong>
                        </td>
                        <td>
                            Cinangka, {{ $currentDateIndo }}<br>
                            <strong>Pembimbing Mentoring {{ $typeLabel }}</strong>
                            <br><br><br><br><br>
                            <strong><u>{{ $group->mentor_name }}</u></strong>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    @empty
        <div class="page-container" style="text-align: center; padding: 50px;">
            <p style="color: #64748b; font-size: 1.1rem; font-style: italic;">
                Tidak ada data kelompok {{ $typeLabel }} untuk dicetak pada Tahun Pelajaran {{ $yearName }}.
            </p>
        </div>
    @endforelse

</body>
</html>
