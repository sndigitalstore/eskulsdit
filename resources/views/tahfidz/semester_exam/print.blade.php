<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Ujian Hafalan Semester {{ $semester }} – {{ $group->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm 12mm 12mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 9pt;
            color: #000;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
        }

        .no-print-bar {
            background: #065f46;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .btn-print {
            background: #10b981;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
        }

        .btn-close {
            background: #475569;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .page-container {
            background: white;
            width: 297mm;
            min-height: 210mm;
            margin: 15px auto;
            padding: 10mm 15mm;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        /* KOP SURAT RESMI */
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .kop-logo {
            position: absolute;
            left: 5px;
            top: 2px;
            width: 65px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            margin: 0 auto;
        }

        .title-box {
            text-align: center;
            margin-bottom: 14px;
        }

        .title-box h3 {
            margin: 0;
            font-size: 11.5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }

        .title-box p {
            margin: 2px 0 0 0;
            font-size: 8.5pt;
            color: #333;
        }

        /* DATA TABLE */
        .recap-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 15px;
        }

        .recap-table th {
            border: 1px solid #000;
            background: #f1f5f9;
            padding: 6px 6px;
            font-weight: bold;
            text-align: center;
            font-size: 8pt;
            text-transform: uppercase;
        }

        .recap-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
        }

        .surah-item-chip {
            display: inline-block;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 2px 6px;
            border-radius: 4px;
            margin: 1px 2px;
            font-size: 7.5pt;
            white-space: nowrap;
        }

        .surah-item-chip strong {
            color: #047857;
        }

        /* TANDA TANGAN 2 PIHAK */
        .signature-section {
            margin-top: 15px;
            font-family: 'Times New Roman', serif;
            font-size: 9.5pt;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            border: none;
            text-align: center;
            width: 45%;
            vertical-align: top;
            padding: 0 15px;
        }

        @media print {
            .no-print-bar { display: none !important; }
            body { background: white; }
            .page-container { width: 100%; margin: 0; padding: 0; box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <span style="font-weight: 700;">
            <i class="fas fa-print"></i> Rekapitulasi Ujian Hafalan Semester {{ $semester }} – {{ $group->name }} ({{ $students->count() }} Santri)
        </span>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Cetak / Simpan PDF (A4 Landscape)</button>
            <button onclick="window.close()" class="btn-close">Tutup</button>
        </div>
    </div>

    <div class="page-container">
        <!-- KOP SURAT RESMI -->
        <div class="kop-surat">
            <img src="{{ asset('logo.png') }}" class="kop-logo" alt="Logo">
            <div class="kop-text">
                <p style="font-weight: 700; margin: 0; font-size: 9.5pt; letter-spacing: 1px; color: #065f46; text-transform: uppercase;">YAYASAN AN NADZIR ISLAMIC SCHOOL</p>
                <h1 style="margin: 2px 0 0 0; font-size: 14pt; font-weight: 900; text-transform: uppercase; color: #000;">SDIT AN NADZIR</h1>
                <h2 style="margin: 2px 0 0 0; font-size: 9.5pt; font-weight: bold; color: #0369a1;">SISTEM INFORMASI MUTABA'AH, TAHFIDZ, KARAKTER & INTEGRASI NILAI (SIMUTQIN)</h2>
                <p style="font-size: 8.5pt; color: #475569; margin: 2px 0;">NNS: 101280432037 · NPSN: 20616174 · Akreditasi: A (Amat Baik)</p>
                <p style="margin: 2px 0 0 0; font-size: 8pt; font-style: italic; color: #333;">Bendungan Karet Cisirih KP. Cukang RT. 09 RW. 02 Desa Kamasan Kecamatan Cinangka Kabupaten Serang - Banten 42167</p>
            </div>
        </div>

        <!-- JUDUL LAPORAN -->
        <div class="title-box">
            <h3>REKAPITULASI NILAI UJIAN HAFALAN AL-QUR'AN AKHIR SEMESTER {{ $semester }} ({{ $semester == '1' ? 'GANJIL' : 'GENAP' }})</h3>
            <p>
                Kelompok: <strong>{{ $group->name }}</strong> &bull; Musyrif: <strong>{{ $mentorName }}</strong> &bull; Target: <strong>{{ $group->target_level ?? 'Juz 30' }}</strong> &bull; Tahun Pelajaran: <strong>{{ $academicYear->name }}</strong>
            </p>
        </div>

        <!-- TABEL HASIL UJIAN -->
        <table class="recap-table">
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 10%;">NIS</th>
                    <th style="width: 20%; text-align: left;">Nama Santri</th>
                    <th style="width: 7%;">Kelas</th>
                    <th style="width: 37%; text-align: left;">Rincian Surat yang Diujikan & Nilai</th>
                    <th style="width: 8%;">Rata-Rata</th>
                    <th style="width: 14%;">Predikat Kelulusan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $k => $std)
                    @php
                        $exam = $exams->get($std->id);
                        $items = $exam ? $exam->items : collect();
                        $avg = $exam ? (float)$exam->average_score : 0;
                        $pred = $exam ? $exam->predicate : '-';
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $k + 1 }}</td>
                        <td style="text-align: center; font-family: monospace;">{{ $std->nis ?? '-' }}</td>
                        <td style="font-weight: bold;">{{ $std->name }}</td>
                        <td style="text-align: center;">{{ $std->class }}</td>
                        <td>
                            @forelse($items as $item)
                                @php
                                    $itemJuz = \App\Http\Controllers\TahfidzAttendanceController::determineJuzForSurah($item->surah_name, $item->juz);
                                @endphp
                                <span class="surah-item-chip">
                                    {{ $item->surah_name }}@if($itemJuz) (Juz {{ $itemJuz }})@endif: <strong>{{ number_format($item->score, 0) }}</strong>
                                </span>
                            @empty
                                <span style="color: #94a3b8; font-style: italic; font-size: 7.5pt;">Belum ada surat yang dinilai pada Semester {{ $semester }}</span>
                            @endforelse

                            @if($semester == '2' && isset($sem1Exams) && ($s1Exam = $sem1Exams->get($std->id)) && $s1Exam->items->isNotEmpty())
                                <div style="margin-top: 4px; padding-top: 3px; border-top: 1px dashed #cbd5e1; font-size: 7pt; color: #166534;">
                                    <span style="font-weight: bold;"><i class="fas fa-check-double"></i> Tuntas Sem 1:</span>
                                    @foreach($s1Exam->items as $s1)
                                        <span style="display: inline-block; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 1px 4px; border-radius: 3px; margin: 1px 1px;">
                                            {{ $s1->surah_name }}: <strong>{{ number_format($s1->score, 0) }}</strong>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: 900; font-size: 9.5pt; color: {{ $avg >= 80 ? '#15803d' : ($avg >= 70 ? '#b45309' : '#b91c1c') }};">
                            {{ $avg > 0 ? number_format($avg, 1) : '-' }}
                        </td>
                        <td style="text-align: center; font-weight: bold; font-size: 8pt;">
                            @if($avg >= 90)
                                <span style="color: #15803d;">Mumtaz (A)</span>
                            @elseif($avg >= 80)
                                <span style="color: #0369a1;">Jayyid Jiddan (B)</span>
                            @elseif($avg >= 70)
                                <span style="color: #b45309;">Jayyid (C)</span>
                            @elseif($avg > 0)
                                <span style="color: #b91c1c;">Maqbul (D)</span>
                            @else
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px; color: #94a3b8; font-style: italic;">
                            Belum ada santri terdaftar di kelompok ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- TANDA TANGAN RESMI 2 PIHAK -->
        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td style="width: 45%;">
                        Mengetahui,<br>
                        <strong>Kepala Sekolah</strong><br>
                        <div style="height: 55px;"></div>
                        <strong style="text-decoration: underline;">{{ $headmasterName }}</strong><br>
                        <span style="font-size: 8pt; color: #555;">NIY. {{ $headmasterNip }}</span>
                    </td>
                    <td style="width: 10%;"></td>
                    <td style="width: 45%;">
                        Cinangka, {{ $currentDate }}<br>
                        <strong>Musyrif / Pembina Tahfidz</strong><br>
                        <div style="height: 55px;"></div>
                        <strong style="text-decoration: underline;">{{ $mentorName !== '-' ? $mentorName : '....................................' }}</strong><br>
                        <span style="font-size: 8pt; color: #555;">Musyrif Pengampu Tahfidz</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
