<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Sesi – {{ $group->name }} – {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page { size: A4 portrait; margin: 12mm 15mm 15mm 15mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 10pt; color: #000; background: #f1f5f9; margin: 0; padding: 0; -webkit-print-color-adjust: exact; }
        .no-print-bar { background: #1e293b; color: white; padding: 12px 24px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 9999; }
        .btn-print { background: #0f766e; color: white; border: none; padding: 8px 20px; border-radius: 8px; font-weight: bold; font-size: 0.9rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
        .page-container { background: white; width: 210mm; min-height: 297mm; margin: 20px auto; padding: 15mm; box-shadow: 0 0 15px rgba(0,0,0,0.1); box-sizing: border-box; }
        .kop-surat { border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 14px; display: flex; align-items: center; gap: 15px; }
        .kop-logo { width: 65px; height: auto; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h1 { margin: 0; font-size: 15pt; font-weight: bold; text-transform: uppercase; }
        .kop-text h2 { margin: 2px 0 0 0; font-size: 12pt; font-weight: bold; }
        .kop-text p  { margin: 2px 0 0 0; font-size: 8pt; font-style: italic; color: #555; }
        .title-box { text-align: center; margin: 14px 0 12px; }
        .title-box h3 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; }
        .title-box p  { margin: 4px 0 0; font-size: 9pt; font-family: sans-serif; }
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-family: sans-serif; font-size: 9pt; }
        .meta-table td { padding: 2px 0; border: none; }
        .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 8px; margin-bottom: 12px; font-family: sans-serif; }
        .stat-card { border-radius: 8px; padding: 8px; text-align: center; }
        .stat-card .n { font-size: 1.4rem; font-weight: 900; line-height: 1; }
        .stat-card .l { font-size: 7.5pt; margin-top: 2px; }
        .report-table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 9pt; margin-bottom: 16px; }
        .report-table th { background: #dcfce7; color: #14532d; font-weight: bold; text-align: center; border: 1px solid #86efac; padding: 6px 5px; }
        .report-table td { border: 1px solid #d1fae5; padding: 5px 6px; vertical-align: middle; }
        .report-table tr:nth-child(even) td { background: #f0fdf4; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 8pt; font-weight: bold; color: white; }
        .signature-section { margin-top: 28px; font-family: 'Times New Roman', serif; font-size: 10pt; page-break-inside: avoid; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-table td { border: none; text-align: center; width: 50%; vertical-align: top; }
        @media print { .no-print-bar { display: none !important; } body { background: white; } .page-container { width: 100%; margin: 0; padding: 0; box-shadow: none; } }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <span style="font-family:sans-serif; font-weight:bold;">
            <i class="fas fa-clipboard-check" style="color:#34d399;"></i>
            Absensi {{ $group->name }} – {{ \Carbon\Carbon::parse($date)->isoFormat('D MMMM Y') }}
        </span>
        <div style="display:flex; gap:10px;">
            <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Cetak / PDF</button>
            <button onclick="window.close()" style="background:#475569; color:white; border:none; padding:8px 14px; border-radius:8px; font-weight:bold; cursor:pointer;">Tutup</button>
        </div>
    </div>

    @php
        $currentDate = \Carbon\Carbon::now()->isoFormat('D MMMM Y');
        $sessionDate = \Carbon\Carbon::parse($date);
        $headmasterName = \App\Models\Setting::where('key','headmaster_name')->value('value')
            ?? optional(\App\Models\User::where('role','headmaster')->first())->name
            ?? "Nur'asiah, S.Pd.I";
        $hadir = $records->where('status','hadir')->count();
        $sakit = $records->where('status','sakit')->count();
        $izin  = $records->where('status','izin')->count();
        $alpha = $records->where('status','alpha')->count();
        $total = $records->count();
        $statusBg = fn($s) => match($s) { 'hadir'=>'#16a34a','sakit'=>'#d97706','izin'=>'#0ea5e9','alpha'=>'#dc2626',default=>'#64748b'};
        $statusLbl = fn($s) => match($s) { 'hadir'=>'Hadir','sakit'=>'Sakit','izin'=>'Izin','alpha'=>'Alpha',default=>'-'};
    @endphp

    <div class="page-container">
        <div class="kop-surat">
            <img src="{{ asset('logo.png') }}" class="kop-logo" alt="Logo" onerror="this.style.display='none'">
            <div class="kop-text">
                <h1>AN NADZIR ISLAMIC SCHOOL</h1>
                <h2>SDIT AN NADZIR</h2>
                <p>Bendungan Karet Cisirih, Kp. Cukang RT.09/RW.02, Desa Kamasan, Kecamatan Cinangka, Kabupaten Serang, Provinsi Banten 42167</p>
            </div>
        </div>

        <div class="title-box">
            <h3>DAFTAR HADIR MENTORING TAHFIDZ</h3>
            <p>{{ $yearName }} &nbsp;|&nbsp; Sesi: <strong>{{ $sessionDate->isoFormat('dddd, D MMMM Y') }}</strong></p>
        </div>

        <table class="meta-table">
            <tr>
                <td style="width:18%; font-weight:bold;">Kelompok</td>
                <td style="width:32%;">: <strong>{{ $group->name }}</strong></td>
                <td style="width:18%; font-weight:bold;">Pembimbing</td>
                <td>: <strong>{{ $group->mentor_name }}</strong></td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Tanggal Sesi</td>
                <td>: {{ $sessionDate->isoFormat('D MMMM Y') }}</td>
                <td style="font-weight:bold;">Jadwal</td>
                <td>: {{ $group->schedule ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Jumlah Siswa</td>
                <td>: {{ $total }} Orang</td>
                <td style="font-weight:bold;">Tanggal Cetak</td>
                <td>: {{ $currentDate }}</td>
            </tr>
        </table>

        <div class="stats-row">
            <div class="stat-card" style="background:#dcfce7;"><div class="n" style="color:#16a34a;">{{ $hadir }}</div><div class="l" style="color:#16a34a;">Hadir</div></div>
            <div class="stat-card" style="background:#fef3c7;"><div class="n" style="color:#d97706;">{{ $sakit }}</div><div class="l" style="color:#d97706;">Sakit</div></div>
            <div class="stat-card" style="background:#e0f2fe;"><div class="n" style="color:#0369a1;">{{ $izin }}</div><div class="l" style="color:#0369a1;">Izin</div></div>
            <div class="stat-card" style="background:#fee2e2;"><div class="n" style="color:#dc2626;">{{ $alpha }}</div><div class="l" style="color:#dc2626;">Alpha</div></div>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:40%; text-align:left; padding-left:8px;">Nama Lengkap Siswa</th>
                    <th style="width:15%;">NIS</th>
                    <th style="width:10%;">Kelas</th>
                    <th style="width:14%;">Status</th>
                    <th style="text-align:left; padding-left:8px;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $i => $rec)
                    <tr>
                        <td style="text-align:center;">{{ $i+1 }}</td>
                        <td style="font-weight:bold; padding-left:8px;">{{ $rec->student->name }}</td>
                        <td style="text-align:center; font-family:monospace; font-size:8.5pt;">{{ $rec->student->nis ?? '-' }}</td>
                        <td style="text-align:center; font-weight:600;">{{ $rec->student->class }}</td>
                        <td style="text-align:center;">
                            <span class="badge" style="background:{{ $statusBg($rec->status) }};">{{ $statusLbl($rec->status) }}</span>
                        </td>
                        <td style="padding-left:8px; font-size:8.5pt; color:#334155;">{{ $rec->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center; padding:20px; font-style:italic; color:#94a3b8;">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>Mengetahui,<br><strong>Kepala SDIT AN NADZIR</strong><br><br><br><br><br><strong><u>{{ $headmasterName }}</u></strong></td>
                    <td>Cinangka, {{ $currentDate }}<br><strong>Pembimbing Tahfidz</strong><br><br><br><br><br><strong><u>{{ $group->mentor_name }}</u></strong></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
