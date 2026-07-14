<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Prestasi Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; padding: 40px; background: white; color: #333; }
        
        /* Header Decoration */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 20px;
            border-bottom: 3px double #059669; /* Emerald Green */
            padding-top: 10px;
        }
        .page-header h1 {
            color: #064e3b; /* Deep Green */
            font-size: 24pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            line-height: 1.2;
        }
        .header-logo {
            position: absolute;
            left: 0;
            top: 0;
            height: 80px; /* Adjust size as needed */
            object-fit: contain;
        }
        
        @media print {
            .header-logo {
                -webkit-print-color-adjust: exact;
            }
        }
        .page-header p {
            margin: 5px 0 0;
            color: #047857;
            font-size: 12pt;
        }
        
        /* Colorful Table */
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.05);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
            border: 1px solid #d1fae5;
        }
        
        thead {
            background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
            color: white;
            -webkit-print-color-adjust: exact; /* Ensure background prints */
            print-color-adjust: exact;
        }
        
        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10pt;
        }
        
        /* Zebra Striping */
        tbody tr:nth-child(even) {
            background-color: #f2fbf5; /* Soft mint green */
            -webkit-print-color-adjust: exact;
        }
        
        /* Levels Coloring */
        .level-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
            -webkit-print-color-adjust: exact;
        }
        
        .level-sekolah { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .level-kecamatan { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
        .level-kabupaten { background: #fae8ff; color: #86198f; border: 1px solid #f5d0fe; }
        .level-provinsi { background: #fce7f3; color: #9d174d; border: 1px solid #fbcfe8; }
        .level-nasional { background: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
        .level-internasional { background: #fef08a; color: #854d0e; border: 1px solid #fde047; }
        .level-open { background: #ccfbf1; color: #115e59; border: 1px solid #99f6e4; }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 10pt;
            page-break-inside: avoid;
        }
        
        @media print {
            body { padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none; }
            table { box-shadow: none; }
        }
    </style>
</head>
<body onload="window.print()">

    @php
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $headmaster = \App\Models\Setting::where('key', 'headmaster_name')->value('value') ?? 'Nur\'asiah, S.Pd.I';
    @endphp

    <div class="page-header">
        <img src="{{ asset('logo.png') }}" class="header-logo" alt="Logo">
        <div style="display: inline-block; width: 100%;"> <!-- Wrap text to center distinct from logo -->
            <h1>Laporan Prestasi Siswa</h1>
            <p>SDIT AN NADZIR - Tahun Ajaran {{ $activeYear ? $activeYear->name : '...' }}</p>
            <div style="font-size: 10pt; margin-top: 5px; color: #888;">Dicetak pada: {{ now()->translatedFormat('d F Y') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Nama Siswa</th>
                <th width="25%">Nama Prestasi</th>
                <th width="15%">Tingkat</th>
                <th width="20%">Penyelenggara</th>
            </tr>
        </thead>
        <tbody>
            @foreach($achievements as $index => $item)
            @php
                $levelClass = 'level-sekolah';
                if(str_contains(strtolower($item->level), 'kecamatan')) $levelClass = 'level-kecamatan';
                if(str_contains(strtolower($item->level), 'kabupaten')) $levelClass = 'level-kabupaten';
                if(str_contains(strtolower($item->level), 'provinsi')) $levelClass = 'level-provinsi';
                if(str_contains(strtolower($item->level), 'nasional')) $levelClass = 'level-nasional';
                if(str_contains(strtolower($item->level), 'internasional')) $levelClass = 'level-internasional';
                if(str_contains(strtolower($item->level), 'open')) $levelClass = 'level-open';
            @endphp
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}</td>
                <td>
                    <strong>{{ $item->student->name }}</strong><br>
                    <span style="font-size: 9pt; color: #666;">Kelas {{ $item->student->class }}</span>
                </td>
                <td>{{ $item->name }}</td>
                <td align="center">
                    <span class="level-badge {{ $levelClass }}">
                        {{ $item->level }}
                    </span>
                </td>
                <td>{{ $item->organizer ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Signature Block -->
    <div class="footer" style="text-align: right; margin-top: 50px; padding-right: 50px;">
        <p>Cinangka, {{ now()->translatedFormat('d F Y') }}</p>
        <p>Mengetahui,</p>
        <p style="margin-bottom: 80px;"><strong>Kepala Sekolah</strong></p>
        
        <p style="text-decoration: underline; font-weight: bold; margin-bottom: 2px;">{{ $headmaster }}</p>
    </div>

</body>
</html>
