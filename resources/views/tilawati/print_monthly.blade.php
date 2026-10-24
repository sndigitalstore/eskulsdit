<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Bulanan Tilawati - {{ $group->name }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 10pt; color: #111; margin: 15px; }
        .kop { text-align: center; border-bottom: 3px double #111; padding-bottom: 8px; margin-bottom: 14px; }
        .kop h2 { margin: 0; font-size: 15pt; font-weight: 800; text-transform: uppercase; }
        .kop p { margin: 2px 0 0 0; font-size: 9pt; color: #444; }
        .meta-table { width: 100%; margin-bottom: 12px; font-size: 9.5pt; }
        .meta-table td { padding: 3px 5px; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 9pt; }
        .data-table th, .data-table td { border: 1px solid #333; padding: 6px 5px; text-align: left; }
        .data-table th { background: #f0fdf4; font-weight: 800; text-align: center; }
        .ttd-wrap { margin-top: 30px; display: flex; justify-content: space-between; page-break-inside: avoid; }
        .ttd-box { width: 220px; text-align: center; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 14px; background: #f1f5f9; padding: 10px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-weight: 700; color: #334155;">Lembar Resmi Rekapan Bulanan Pembelajaran Tilawati</span>
        <button onclick="window.print()" style="background: #059669; color: white; border: none; padding: 8px 18px; border-radius: 6px; font-weight: 800; cursor: pointer;">
            🖨️ Cetak Dokumen / Simpan PDF
        </button>
    </div>

    <div class="kop">
        <h2>SDIT AN NADZIR</h2>
        <p>REKAPITULASI BULANAN PEMBELAJARAN TILAWATI</p>
        <p style="font-size: 8.5pt; color: #666;">Jl. Rancamanyar, Baleendah, Bandung - Jawa Barat</p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 18%; font-weight: 700;">Kelompok Tilawati</td>
            <td style="width: 2%;">:</td>
            <td style="width: 40%;">{{ $group->name }} ({{ $group->target_level ?: 'Jilid 1' }})</td>
            <td style="width: 18%; font-weight: 700;">Bulan & Tahun</td>
            <td style="width: 2%;">:</td>
            <td style="width: 20%;">{{ $monthNames[$month] }} {{ $year }}</td>
        </tr>
        <tr>
            <td style="font-weight: 700;">Pembimbing</td>
            <td>:</td>
            <td>{{ $group->mentor_name }}</td>
            <td style="font-weight: 700;">Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $group->academicYear->name ?? '-' }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Nama Santri</th>
                <th style="width: 50px;">Kelas</th>
                @foreach($sessions as $sess)
                    <th style="font-size: 7.5pt; width: 45px;">
                        {{ $sess->session_date->format('d/m') }}
                        <div style="font-size: 6.5pt; color: #047857;">H.{{ $sess->buku_page }}</div>
                    </th>
                @endforeach
                <th style="width: 45px;">Hadir</th>
                <th style="width: 45px;">Lanjut</th>
                <th style="width: 45px;">Ulang</th>
                <th style="width: 55px;">% Capai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $idx => $st)
                @php
                    $stAtts = $attendances->get($st->id, collect())->keyBy(fn($item) => $item->session_date->format('Y-m-d'));
                    $hadir = $stAtts->where('status', 'hadir')->count();
                    $lanjut = $stAtts->where('status', 'hadir')->where('is_passed', true)->count();
                    $ulang = $stAtts->where('status', 'hadir')->where('is_passed', false)->count();
                    $percent = count($sessions) > 0 ? round(($lanjut / count($sessions)) * 100) : 0;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-weight: 700;">{{ $st->name }}</td>
                    <td style="text-align: center;">{{ $st->class ?? '-' }}</td>
                    @foreach($sessions as $sess)
                        @php
                            $att = $stAtts->get($sess->session_date->format('Y-m-d'));
                        @endphp
                        <td style="text-align: center; font-size: 8pt;">
                            @if($att)
                                @if($att->status === 'hadir')
                                    {{ $att->is_passed ? '✓' : '↻' }}
                                @else
                                    {{ strtoupper(substr($att->status, 0, 1)) }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                    <td style="text-align: center; font-weight: 700;">{{ $hadir }}</td>
                    <td style="text-align: center; font-weight: 800; color: #15803d;">{{ $lanjut }}</td>
                    <td style="text-align: center; font-weight: 800; color: #b91c1c;">{{ $ulang }}</td>
                    <td style="text-align: center; font-weight: 800;">{{ $percent }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd-wrap">
        <div class="ttd-box">
            <p>Mengetahui,<br>Kepala SDIT AN NADZIR</p>
            <div style="height: 50px;"></div>
            <p style="font-weight: 700; text-decoration: underline;">Ust. Kepala Sekolah, S.Pd.I</p>
        </div>
        <div class="ttd-box">
            <p>Bandung, {{ date('d') }} {{ $monthNames[$month] }} {{ $year }}<br>Guru Pembimbing Tilawati</p>
            <div style="height: 50px;"></div>
            <p style="font-weight: 700; text-decoration: underline;">{{ $group->mentor_name }}</p>
        </div>
    </div>

</body>
</html>
