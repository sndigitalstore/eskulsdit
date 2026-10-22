<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Hafalan Kelompok – {{ $group->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page { size: A4 portrait; margin: 12mm 15mm 15mm 15mm; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt; color: #000;
            background: #f1f5f9; margin: 0; padding: 0;
            -webkit-print-color-adjust: exact;
        }
        .no-print-bar {
            background: #1e293b; color: white;
            padding: 12px 24px; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 9999;
        }
        .btn-print {
            background: #0f766e; color: white; border: none;
            padding: 8px 20px; border-radius: 8px; font-weight: bold; font-size: 0.9rem;
            cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
        }
        .page-container {
            background: white; width: 210mm; min-height: 297mm;
            margin: 20px auto; padding: 15mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1); box-sizing: border-box;
            page-break-after: always;
        }
        .page-container:last-child { page-break-after: auto; }
        .kop-surat {
            border-bottom: 3px double #000; padding-bottom: 8px;
            margin-bottom: 14px; display: flex; align-items: center; gap: 15px;
        }
        .kop-logo { width: 65px; height: auto; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h1 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop-text h2 { margin: 2px 0 0 0; font-size: 11pt; font-weight: bold; }
        .kop-text p  { margin: 2px 0 0 0; font-size: 8pt; font-style: italic; color: #555; }
        .title-box { text-align: center; margin: 10px 0 12px; }
        .title-box h3 { margin: 0; font-size: 11pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; }
        .title-box p  { margin: 3px 0 0; font-size: 9pt; font-family: sans-serif; color: #334155; }
        .student-header {
            background: #f0fdf4; border: 1px solid #86efac;
            border-radius: 6px; padding: 8px 14px; margin: 14px 0 8px;
            display: flex; justify-content: space-between; align-items: center;
            font-family: sans-serif;
        }
        .student-name { font-weight: 800; font-size: 11pt; color: #0f172a; }
        .student-meta { font-size: 8.5pt; color: #475569; }
        .report-table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 8pt; margin-bottom: 8px; }
        .report-table th {
            background: #dcfce7; color: #14532d; font-weight: bold;
            text-align: center; border: 1px solid #86efac; padding: 5px 4px;
        }
        .report-table td { border: 1px solid #d1fae5; padding: 4px 5px; vertical-align: middle; }
        .report-table tr:nth-child(even) td { background: #f0fdf4; }
        .badge {
            display: inline-block; padding: 1px 6px; border-radius: 8px;
            font-size: 7.5pt; font-weight: bold; color: white;
        }
        .empty-note { text-align: center; color: #94a3b8; font-style: italic; padding: 10px; font-size: 8.5pt; }
        .signature-section { margin-top: 28px; font-family: 'Times New Roman', serif; font-size: 10pt; page-break-inside: avoid; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-table td { border: none; text-align: center; width: 50%; vertical-align: top; }
        @media print {
            .no-print-bar { display: none !important; }
            body { background: white; }
            .page-container { width: 100%; margin: 0; padding: 0; box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <span style="font-family: sans-serif; font-weight: bold;">
            <i class="fas fa-quran" style="color: #34d399;"></i>
            Rekap Hafalan Kelompok {{ $group->name }} – TP {{ $yearName }}
            ({{ $students->count() }} Siswa)
        </span>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('reports.tahfidz.export', ['group_id' => $group->id, 'year_id' => $yearId]) }}" class="btn-print" style="background: #0284c7; text-decoration: none;">
                <i class="fas fa-file-excel"></i> Download Excel
            </a>
            <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Cetak / PDF</button>
            <button onclick="window.close()" style="background: #475569; color: white; border: none; padding: 8px 14px; border-radius: 8px; font-weight: bold; cursor: pointer;">Tutup</button>
        </div>
    </div>

    @php
        $currentDate = \Carbon\Carbon::now()->isoFormat('D MMMM Y');
        $headmasterName = \App\Models\Setting::where('key','headmaster_name')->value('value')
            ?? optional(\App\Models\User::where('role','headmaster')->first())->name
            ?? "Nur'asiah, S.Pd.I";
        $qualityBg = fn($q) => match($q) {
            'lancar'          => '#16a34a',
            'cukup'           => '#d97706',
            'perlu_perbaikan' => '#dc2626',
            default           => '#64748b',
        };
        $qualityLbl = fn($q) => match($q) {
            'lancar'          => 'Lancar',
            'cukup'           => 'Cukup',
            'perlu_perbaikan' => 'Perlu Perbaikan',
            default           => '-',
        };
        $typeBg  = fn($t) => $t === 'setoran_baru' ? '#0ea5e9' : '#8b5cf6';
        $typeLbl = fn($t) => $t === 'setoran_baru' ? 'Setoran' : "Muroja'ah";
    @endphp

    <div class="page-container">
        <!-- KOP -->
        <div class="kop-surat">
            <img src="{{ asset('logo.png') }}" class="kop-logo" alt="Logo" onerror="this.style.display='none'">
            <div class="kop-text">
                <h1>AN NADZIR ISLAMIC SCHOOL</h1>
                <h2>SDIT AN NADZIR</h2>
                <p>Bendungan Karet Cisirih, Kp. Cukang RT.09/RW.02, Desa Kamasan, Kecamatan Cinangka, Kabupaten Serang, Provinsi Banten 42167</p>
            </div>
        </div>

        <!-- JUDUL -->
        <div class="title-box">
            <h3>REKAP HAFALAN KELOMPOK MENTORING TAHFIDZ</h3>
            <p>
                Kelompok: <strong>{{ $group->name }}</strong> &nbsp;|&nbsp;
                Pembimbing: <strong>{{ $group->mentor_name }}</strong> &nbsp;|&nbsp;
                Tahun Pelajaran: <strong>{{ $yearName }}</strong>
            </p>
        </div>

        @forelse($students as $student)
            <div class="student-header">
                <div>
                    <div class="student-name">{{ $student->name }}</div>
                    <div class="student-meta">
                        Kelas {{ $student->class }}
                        @if($student->nis) · NIS: {{ $student->nis }} @endif
                    </div>
                </div>
                <div style="font-family:sans-serif; font-size: 8.5pt; text-align: right; color: #0f766e; font-weight: 700;">
                    {{ $student->hafalan->count() }} Catatan &nbsp;·&nbsp;
                    <span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 6px; border: 1px solid #86efac;">
                        <i class="fas fa-align-left"></i> {{ (int) $student->hafalan->sum('jumlah_baris') }} Baris
                    </span>
                </div>
            </div>

            @if($student->hafalan->isEmpty())
                <div class="empty-note">Belum ada catatan hafalan untuk siswa ini.</div>
            @else
                <table class="report-table">
                    <thead>
                        <tr>
                            <th style="width:4%;">No</th>
                            <th style="width:11%;">Tanggal</th>
                            <th style="width:22%; text-align:left; padding-left:6px;">Surah</th>
                            <th style="width:6%;">Juz</th>
                            <th style="width:10%;">Ayat</th>
                            <th style="width:8%;">Baris</th>
                            <th style="width:11%;">Jenis</th>
                            <th style="width:11%;">Kualitas</th>
                            <th style="text-align:left; padding-left:6px;">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->hafalan as $i => $rec)
                            <tr>
                                <td style="text-align:center; color:#94a3b8;">{{ $i+1 }}</td>
                                <td style="text-align:center;">{{ \Carbon\Carbon::parse($rec->session_date)->format('d/m/Y') }}</td>
                                <td style="font-weight:bold; padding-left:6px;">{{ $rec->surah_name }}</td>
                                <td style="text-align:center;">{{ $rec->juz ?? '-' }}</td>
                                <td style="text-align:center;">
                                    @if($rec->start_ayah && $rec->end_ayah)
                                        {{ $rec->start_ayah }}-{{ $rec->end_ayah }}
                                    @else -
                                    @endif
                                </td>
                                <td style="text-align:center; font-weight:bold; color:#047857;">
                                    {{ $rec->jumlah_baris > 0 ? $rec->jumlah_baris . ' Baris' : '-' }}
                                </td>
                                <td style="text-align:center;">
                                    <span class="badge" style="background:{{ $typeBg($rec->type) }};">{{ $typeLbl($rec->type) }}</span>
                                </td>
                                <td style="text-align:center;">
                                    <span class="badge" style="background:{{ $qualityBg($rec->quality) }};">{{ $qualityLbl($rec->quality) }}</span>
                                </td>
                                <td style="padding-left:6px; font-size:7.5pt; color:#334155;">{{ $rec->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @empty
            <div style="text-align:center; padding:40px; color:#94a3b8; font-style:italic;">
                Belum ada siswa di kelompok ini.
            </div>
        @endforelse

        <!-- TANDA TANGAN -->
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
