<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Rapor Kelas {{ $class }} – {{ $assessmentInfo['short'] }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            font-size: 8.5pt;
            background: #fff;
        }

        .no-print {
            background: #f1f5f9;
            padding: 10px 20px;
            text-align: right;
            border-bottom: 1px solid #cbd5e1;
            margin-bottom: 16px;
        }

        .btn-print {
            background: #0284c7;
            color: white;
            border: none;
            padding: 8px 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            font-family: sans-serif;
            font-size: 13px;
        }

        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }

        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 2.5px double #000;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .kop-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-right: 14px;
        }

        .kop-text {
            text-align: center;
            flex: 1;
        }

        .kop-text h1 {
            font-size: 14pt;
            margin: 0;
            font-weight: bold;
        }

        .kop-text h2 {
            font-size: 10.5pt;
            margin: 1px 0;
            font-weight: bold;
        }

        .kop-text p {
            font-size: 8pt;
            margin: 0;
            color: #444;
        }

        .report-title {
            text-align: center;
            margin-bottom: 12px;
        }

        .report-title h3 {
            font-size: 11pt;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .report-title p {
            font-size: 9pt;
            margin: 2px 0 0 0;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 8pt;
        }

        .table-data th, .table-data td {
            border: 1px solid #333;
            padding: 4px 5px;
        }

        .table-data th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .signatures-grid {
            display: flex;
            justify-content: space-between;
            margin-top: 18px;
            page-break-inside: avoid;
            padding: 0 40px;
        }

        .sig-col {
            width: 35%;
            text-align: center;
            font-size: 9pt;
        }

        .sig-space {
            height: 55px;
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak Rekap Nilai Kelas (Landscape A4)</button>
    </div>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <img src="{{ asset('logo.png') }}" class="kop-logo" alt="Logo">
        <div class="kop-text">
            <h1>SDIT AN NADZIR</h1>
            <h2>REKAPITULASI NILAI RAPOR AKADEMIK SISWA – SMILE KURIKULUM</h2>
            <p>Membentuk Generasi Qur'ani, Cerdas, Mandiri, dan Berakhlak Mulia</p>
        </div>
    </div>

    <!-- REPORT TITLE -->
    <div class="report-title">
        <h3>REKAPITULASI NILAI {{ $assessmentInfo['name'] }} ({{ $assessmentInfo['short'] }})</h3>
        <p>Kelas: <strong>{{ $class }}</strong> | Tahun Pelajaran: <strong>{{ $academicYear->name ?? '-' }}</strong> | Semester: <strong>{{ $assessmentInfo['semester'] == 1 ? '1 (Ganjil)' : '2 (Genap)' }}</strong></p>
    </div>

    <!-- TABLE REKAP -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 70px;">NIS</th>
                <th style="text-align: left; min-width: 150px;">Nama Siswa</th>
                @foreach($subjects as $sb)
                    <th style="width: 48px;" title="{{ $sb->name }}">
                        <div>{{ $sb->code ?: substr($sb->name, 0, 4) }}</div>
                        <div style="font-size: 6.5pt; color: #555;">KKTP:{{ (int)$sb->kkm }}</div>
                    </th>
                @endforeach
                <th style="width: 55px; background-color: #e5e7eb;">Total</th>
                <th style="width: 55px; background-color: #e5e7eb;">Rerata</th>
                <th style="width: 45px; background-color: #fef3c7;">Rank</th>
            </tr>
        </thead>
        <tbody>
            @php
                $recapRows = [];
                foreach($students as $stu) {
                    $sum = 0;
                    $count = 0;
                    $stuScores = [];
                    foreach($subjects as $sb) {
                        $sc = $scoresMap[$stu->id][$sb->id] ?? null;
                        $val = $sc ? (float)$sc->score : null;
                        $stuScores[$sb->id] = $val;
                        if ($val !== null) {
                            $sum += $val;
                            $count++;
                        }
                    }
                    $avg = $count > 0 ? round($sum / $count, 2) : 0;
                    $recapRows[] = [
                        'student' => $stu,
                        'scores' => $stuScores,
                        'total' => $sum,
                        'count' => $count,
                        'average' => $avg,
                    ];
                }

                // Rank
                $ranks = $recapRows;
                usort($ranks, fn($a, $b) => $b['average'] <=> $a['average']);
                $rankMap = [];
                $r = 1;
                foreach($ranks as $item) {
                    $rankMap[$item['student']->id] = $item['count'] > 0 ? $r++ : '-';
                }

                $columnSums = [];
                $columnCounts = [];
                foreach($subjects as $sb) {
                    $columnSums[$sb->id] = 0;
                    $columnCounts[$sb->id] = 0;
                }
            @endphp

            @foreach($recapRows as $idx => $row)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $row['student']->nis ?? '-' }}</td>
                    <td class="text-left font-bold">{{ $row['student']->name }}</td>
                    @foreach($subjects as $sb)
                        @php
                            $v = $row['scores'][$sb->id];
                            if ($v !== null) {
                                $columnSums[$sb->id] += $v;
                                $columnCounts[$sb->id]++;
                            }
                        @endphp
                        <td class="text-center {{ $v !== null && $v < $sb->kkm ? 'font-bold' : '' }}" style="{{ $v !== null && $v < $sb->kkm ? 'color: #b91c1c;' : '' }}">
                            {{ $v !== null ? ($v == round($v) ? (int)$v : $v) : '-' }}
                        </td>
                    @endforeach
                    <td class="text-center font-bold" style="background-color: #f9fafb;">
                        {{ $row['total'] > 0 ? (round($row['total']) == $row['total'] ? (int)$row['total'] : $row['total']) : '-' }}
                    </td>
                    <td class="text-center font-bold" style="background-color: #f9fafb;">
                        {{ $row['average'] > 0 ? $row['average'] : '-' }}
                    </td>
                    <td class="text-center font-bold" style="background-color: #fffbeb;">
                        {{ $rankMap[$row['student']->id] ?? '-' }}
                    </td>
                </tr>
            @endforeach

            <!-- RATA-RATA MAPEL KELAS -->
            <tr style="background-color: #f3f4f6; font-weight: bold;">
                <td colspan="3" class="text-right">Rata-Rata Kelas</td>
                @foreach($subjects as $sb)
                    @php
                        $c = $columnCounts[$sb->id];
                        $s = $columnSums[$sb->id];
                        $mapelAvg = $c > 0 ? round($s / $c, 1) : '-';
                    @endphp
                    <td class="text-center">{{ $mapelAvg }}</td>
                @endforeach
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>

    @php
        $headmasterName = \App\Models\Setting::get('headmaster_name', "Nur'asiah, S.Pd.I");
        $headmasterNip = \App\Models\Setting::get('headmaster_nip', '-');
    @endphp

    <!-- SIGNATURES -->
    <div class="signatures-grid">
        <div class="sig-col">
            <div>Bandar Lampung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div><strong>Wali Kelas {{ $class }}</strong></div>
            <div class="sig-space"></div>
            <div><strong><u>{{ $homeroomTeacher?->name ?? 'Wali Kelas ' . $class }}</u></strong></div>
            <div style="font-size: 8pt; color: #444;">NIP/NIY: {{ $homeroomTeacher?->nip ?? '-' }}</div>
        </div>

        <div class="sig-col">
            <div>Mengetahui,</div>
            <div><strong>Kepala Sekolah SDIT AN NADZIR</strong></div>
            <div class="sig-space"></div>
            <div><strong><u>{{ $headmasterName }}</u></strong></div>
            <div style="font-size: 8pt; color: #444;">NIP/NIY: {{ $headmasterNip }}</div>
        </div>
    </div>

</body>
</html>
