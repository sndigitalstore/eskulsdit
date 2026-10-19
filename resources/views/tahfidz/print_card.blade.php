<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Hafalan – {{ $student->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page { size: A4 portrait; margin: 12mm 15mm 15mm 15mm; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #000;
            background: #f1f5f9;
            margin: 0; padding: 0;
            -webkit-print-color-adjust: exact;
        }

        .no-print-bar {
            background: #1e293b; color: white;
            padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 9999;
        }
        .btn-print {
            background: #0f766e; color: white; border: none;
            padding: 8px 20px; border-radius: 8px; font-weight: bold;
            font-size: 0.9rem; cursor: pointer;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .page-container {
            background: white; width: 210mm; min-height: 297mm;
            margin: 20px auto; padding: 15mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1); box-sizing: border-box;
        }
        .kop-surat {
            border-bottom: 3px double #000; padding-bottom: 8px;
            margin-bottom: 14px; display: flex; align-items: center; gap: 15px;
        }
        .kop-logo { width: 65px; height: auto; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h1 { margin: 0; font-size: 15pt; font-weight: bold; text-transform: uppercase; }
        .kop-text h2 { margin: 2px 0 0 0; font-size: 12pt; font-weight: bold; }
        .kop-text p  { margin: 2px 0 0 0; font-size: 8pt; font-style: italic; color: #555; }

        .title-box { text-align: center; margin: 14px 0 10px; }
        .title-box h3 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; }
        .title-box p  { margin: 4px 0 0; font-size: 9pt; font-family: sans-serif; color: #334155; }

        .identity-box {
            border: 1px solid #cbd5e1; border-radius: 6px;
            padding: 10px 14px; margin-bottom: 14px;
            font-family: sans-serif; font-size: 9pt;
        }
        .identity-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 20px; }
        .identity-grid .lbl { color: #475569; font-size: 8.5pt; }
        .identity-grid .val { font-weight: bold; }

        .stats-row {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 8px; margin-bottom: 14px; font-family: sans-serif;
        }
        .stat-card {
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 8px; padding: 10px; text-align: center;
        }
        .stat-card .n { font-size: 1.6rem; font-weight: 800; color: #0f766e; line-height: 1; }
        .stat-card .l { font-size: 7.5pt; color: #64748b; margin-top: 2px; }

        .report-table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 8.5pt; margin-bottom: 16px; }
        .report-table th {
            background: #e0f2fe; color: #0c4a6e;
            font-weight: bold; text-align: center;
            border: 1px solid #94a3b8; padding: 6px 5px;
        }
        .report-table td { border: 1px solid #cbd5e1; padding: 5px 6px; vertical-align: middle; }
        .report-table tr:nth-child(even) td { background: #f8fafc; }

        .badge {
            display: inline-block; padding: 2px 7px; border-radius: 10px;
            font-size: 7.5pt; font-weight: bold; color: white;
        }

        .signature-section { margin-top: 30px; font-family: 'Times New Roman', serif; font-size: 10pt; }
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
            Kartu Hafalan – {{ $student->name }} ({{ $group->name }})
        </span>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Cetak / Simpan PDF</button>
            <button onclick="window.close()" style="background: #475569; color: white; border: none; padding: 8px 14px; border-radius: 8px; font-weight: bold; cursor: pointer;">Tutup</button>
        </div>
    </div>

    @php
        $currentDate = \Carbon\Carbon::now()->isoFormat('D MMMM Y');
        $headmasterName = \App\Models\Setting::where('key','headmaster_name')->value('value')
            ?? optional(\App\Models\User::where('role','headmaster')->first())->name
            ?? "Nur'asiah, S.Pd.I";
        $totalSetoran  = $records->where('type','setoran_baru')->count();
        $totalMurojaah = $records->where('type','murojaah')->count();
        $surahUnique   = $records->pluck('surah_name')->unique()->count();

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
        $typeBg = fn($t) => $t === 'setoran_baru' ? '#0ea5e9' : '#8b5cf6';
        $typeLbl = fn($t) => $t === 'setoran_baru' ? 'Setoran Baru' : "Muroja'ah";
    @endphp

    <div class="page-container">

        <!-- KOP SURAT -->
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
            <h3>KARTU HAFALAN AL-QURAN – MENTORING TAHFIDZ</h3>
            <p>Tahun Pelajaran {{ $yearName }}</p>
        </div>

        <!-- IDENTITAS SISWA -->
        <div class="identity-box">
            <div class="identity-grid">
                <div>
                    <div class="lbl">Nama Siswa</div>
                    <div class="val">{{ $student->name }}</div>
                </div>
                <div>
                    <div class="lbl">Kelompok Mentoring</div>
                    <div class="val">{{ $group->name }}</div>
                </div>
                <div style="margin-top:6px;">
                    <div class="lbl">Kelas</div>
                    <div class="val">{{ $student->class }}</div>
                </div>
                <div style="margin-top:6px;">
                    <div class="lbl">Pembimbing / Mentor</div>
                    <div class="val">{{ $group->mentor_name }}</div>
                </div>
                @if($student->nis)
                <div>
                    <div class="lbl">NIS</div>
                    <div class="val">{{ $student->nis }}</div>
                </div>
                @endif
                @if($group->schedule)
                <div>
                    <div class="lbl">Jadwal Pertemuan</div>
                    <div class="val">{{ $group->schedule }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- STATISTIK -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="n">{{ $totalSetoran }}</div>
                <div class="l">Setoran Baru</div>
            </div>
            <div class="stat-card">
                <div class="n">{{ $totalMurojaah }}</div>
                <div class="l">Muroja'ah</div>
            </div>
            <div class="stat-card">
                <div class="n">{{ $surahUnique }}</div>
                <div class="l">Surah Dicatat</div>
            </div>
        </div>

        <!-- TABEL HAFALAN -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width:4%;">No</th>
                    <th style="width:12%;">Tanggal</th>
                    <th style="width:22%; text-align:left; padding-left:8px;">Nama Surah</th>
                    <th style="width:8%;">Juz</th>
                    <th style="width:12%;">Ayat</th>
                    <th style="width:14%;">Jenis</th>
                    <th style="width:13%;">Kualitas</th>
                    <th style="text-align:left; padding-left:8px;">Catatan Mentor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $i => $rec)
                    <tr>
                        <td style="text-align:center; color:#94a3b8;">{{ $i+1 }}</td>
                        <td style="text-align:center;">
                            {{ \Carbon\Carbon::parse($rec->session_date)->format('d/m/Y') }}
                        </td>
                        <td style="font-weight:bold; padding-left:8px;">{{ $rec->surah_name }}</td>
                        <td style="text-align:center;">{{ $rec->juz ? $rec->juz : '-' }}</td>
                        <td style="text-align:center; font-size:8pt;">
                            @if($rec->start_ayah && $rec->end_ayah)
                                {{ $rec->start_ayah }}–{{ $rec->end_ayah }}
                            @else -
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <span class="badge" style="background:{{ $typeBg($rec->type) }};">
                                {{ $typeLbl($rec->type) }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <span class="badge" style="background:{{ $qualityBg($rec->quality) }};">
                                {{ $qualityLbl($rec->quality) }}
                            </span>
                        </td>
                        <td style="padding-left:8px; font-size:8pt; color:#334155;">{{ $rec->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:20px; color:#94a3b8; font-style:italic;">
                            Belum ada catatan hafalan.
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
                        Cinangka, {{ $currentDate }}<br>
                        <strong>Pembimbing Tahfidz</strong>
                        <br><br><br><br><br>
                        <strong><u>{{ $group->mentor_name }}</u></strong>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>
