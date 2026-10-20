<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi – {{ $group->name }} – TP {{ $yearName }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page { size: A4 landscape; margin: 10mm 12mm 12mm 12mm; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 8.5pt; color: #000; background: #f1f5f9; margin: 0; padding: 0; -webkit-print-color-adjust: exact; }
        .no-print-bar { background: #1e293b; color: white; padding: 12px 24px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 9999; }
        .btn-print { background: #0f766e; color: white; border: none; padding: 8px 20px; border-radius: 8px; font-weight: bold; font-size: 0.9rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
        .page-container { background: white; width: 297mm; margin: 20px auto; padding: 10mm; box-shadow: 0 0 15px rgba(0,0,0,0.1); box-sizing: border-box; }
        .kop-surat { border-bottom: 3px double #000; padding-bottom: 6px; margin-bottom: 10px; display: flex; align-items: center; gap: 12px; }
        .kop-logo { width: 55px; height: auto; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h1 { margin: 0; font-size: 13pt; font-weight: bold; text-transform: uppercase; }
        .kop-text h2 { margin: 2px 0 0 0; font-size: 11pt; font-weight: bold; }
        .kop-text p  { margin: 2px 0 0 0; font-size: 7.5pt; font-style: italic; color: #555; }
        .title-box { text-align: center; margin: 8px 0 10px; }
        .title-box h3 { margin: 0; font-size: 11pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; }
        .title-box p  { margin: 3px 0 0; font-size: 8.5pt; }
        .report-table { width: 100%; border-collapse: collapse; font-size: 7.5pt; margin-bottom: 14px; }
        .report-table th { background: #dcfce7; color: #14532d; font-weight: bold; text-align: center; border: 1px solid #86efac; padding: 5px 4px; }
        .report-table td { border: 1px solid #d1fae5; padding: 4px 5px; vertical-align: middle; }
        .report-table tr:nth-child(even) td { background: #f0fdf4; }
        .cell-h { background: #16a34a !important; color: white; text-align: center; font-weight: bold; border-radius: 0; }
        .cell-s { background: #d97706 !important; color: white; text-align: center; font-weight: bold; }
        .cell-i { background: #0ea5e9 !important; color: white; text-align: center; font-weight: bold; }
        .cell-a { background: #dc2626 !important; color: white; text-align: center; font-weight: bold; }
        .cell-empty { background: #f8fafc !important; text-align: center; color: #cbd5e1; }
        .signature-section { margin-top: 24px; font-size: 9.5pt; page-break-inside: avoid; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-table td { border: none; text-align: center; width: 50%; vertical-align: top; }
        @media print { .no-print-bar { display: none !important; } body { background: white; } .page-container { width: 100%; margin: 0; padding: 0; box-shadow: none; } }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <span style="font-family:sans-serif; font-weight:bold;">
            <i class="fas fa-table" style="color:#34d399;"></i>
            Rekap Absensi – {{ $group->name }} – TP {{ $yearName }}
        </span>
        <div style="display:flex; gap:10px;">
            <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Cetak / PDF (Landscape)</button>
            <button onclick="window.close()" style="background:#475569; color:white; border:none; padding:8px 14px; border-radius:8px; font-weight:bold; cursor:pointer;">Tutup</button>
        </div>
    </div>

    @php
        $currentDate = \Carbon\Carbon::now()->isoFormat('D MMMM Y');
        $headmasterName = \App\Models\Setting::where('key','headmaster_name')->value('value')
            ?? optional(\App\Models\User::where('role','headmaster')->first())->name
            ?? "Nur'asiah, S.Pd.I";

        // Buat lookup: [student_id][session_date] => status
        $lookup = [];
        foreach ($allAttendances as $studentId => $recs) {
            foreach ($recs as $rec) {
                $lookup[$studentId][$rec->session_date->format('Y-m-d')] = $rec->status;
            }
        }

        $statusIcon = fn($s) => match($s) {
            'hadir' => 'H',
            'sakit' => 'S',
            'izin'  => 'I',
            'alpha' => 'A',
            default => '',
        };
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
            <h3>REKAP ABSENSI MENTORING TAHFIDZ</h3>
            <p>
                Kelompok: <strong>{{ $group->name }}</strong> &nbsp;|&nbsp;
                Pembimbing: <strong>{{ $group->mentor_name }}</strong> &nbsp;|&nbsp;
                Tahun Pelajaran: <strong>{{ $yearName }}</strong> &nbsp;|&nbsp;
                Total Sesi: <strong>{{ $sessionDates->count() }}</strong>
            </p>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th style="width:4%; text-align:left; padding-left:6px;">No</th>
                    <th style="width:20%; text-align:left; padding-left:6px;">Nama Siswa</th>
                    <th style="width:7%;">Kelas</th>
                    @foreach($sessionDates as $sd)
                        <th title="{{ \Carbon\Carbon::parse($sd)->isoFormat('D MMM Y') }}" style="min-width: 28px; font-size: 7pt;">
                            {{ \Carbon\Carbon::parse($sd)->format('d/m') }}
                        </th>
                    @endforeach
                    <th style="width:5%;">H</th>
                    <th style="width:5%;">S</th>
                    <th style="width:5%;">I</th>
                    <th style="width:5%;">A</th>
                    <th style="width:7%;">% Hadir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $student)
                    @php
                        $stRecords  = $lookup[$student->id] ?? [];
                        $hadirCount = collect($stRecords)->filter(fn($v) => $v === 'hadir')->count();
                        $sakitCount = collect($stRecords)->filter(fn($v) => $v === 'sakit')->count();
                        $izinCount  = collect($stRecords)->filter(fn($v) => $v === 'izin')->count();
                        $alphaCount = collect($stRecords)->filter(fn($v) => $v === 'alpha')->count();
                        $totalSesi  = $sessionDates->count();
                        $pct        = $totalSesi > 0 ? round(($hadirCount / $totalSesi) * 100) : 0;
                    @endphp
                    <tr>
                        <td style="padding-left:6px;">{{ $i+1 }}</td>
                        <td style="font-weight:bold; padding-left:6px;">{{ $student->name }}</td>
                        <td style="text-align:center; font-weight:600;">{{ $student->class }}</td>
                        @foreach($sessionDates as $sd)
                            @php $st = $stRecords[$sd->format('Y-m-d')] ?? null; @endphp
                            @if($st === 'hadir')
                                <td class="cell-h">H</td>
                            @elseif($st === 'sakit')
                                <td class="cell-s">S</td>
                            @elseif($st === 'izin')
                                <td class="cell-i">I</td>
                            @elseif($st === 'alpha')
                                <td class="cell-a">A</td>
                            @else
                                <td class="cell-empty">·</td>
                            @endif
                        @endforeach
                        <td style="text-align:center; font-weight:bold; color:#16a34a;">{{ $hadirCount }}</td>
                        <td style="text-align:center; font-weight:bold; color:#d97706;">{{ $sakitCount }}</td>
                        <td style="text-align:center; font-weight:bold; color:#0ea5e9;">{{ $izinCount }}</td>
                        <td style="text-align:center; font-weight:bold; color:#dc2626;">{{ $alphaCount }}</td>
                        <td style="text-align:center; font-weight:bold; color:{{ $pct >= 80 ? '#16a34a' : ($pct >= 60 ? '#d97706' : '#dc2626') }};">
                            {{ $pct }}%
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="{{ 4 + $sessionDates->count() + 5 }}" style="text-align:center; padding:20px; font-style:italic;">Tidak ada siswa.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- Keterangan --}}
        <div style="font-family: sans-serif; font-size: 7.5pt; color: #475569; margin-bottom: 12px; display: flex; gap: 20px;">
            <span><strong style="background:#16a34a; color:white; padding: 1px 6px; border-radius:3px;">H</strong> = Hadir</span>
            <span><strong style="background:#d97706; color:white; padding: 1px 6px; border-radius:3px;">S</strong> = Sakit</span>
            <span><strong style="background:#0ea5e9; color:white; padding: 1px 6px; border-radius:3px;">I</strong> = Izin</span>
            <span><strong style="background:#dc2626; color:white; padding: 1px 6px; border-radius:3px;">A</strong> = Alpha</span>
        </div>

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
