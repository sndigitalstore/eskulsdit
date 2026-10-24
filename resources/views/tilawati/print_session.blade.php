<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Sesi Pembelajaran Tilawati - {{ $group->name }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11pt; color: #111; margin: 20px; }
        .kop { text-align: center; border-bottom: 3px double #111; padding-bottom: 10px; margin-bottom: 16px; }
        .kop h2 { margin: 0; font-size: 16pt; font-weight: 800; text-transform: uppercase; }
        .kop p { margin: 2px 0 0 0; font-size: 9.5pt; color: #444; }
        .meta-table { width: 100%; margin-bottom: 16px; font-size: 10.5pt; }
        .meta-table td { padding: 4px 6px; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10pt; }
        .data-table th, .data-table td { border: 1px solid #333; padding: 7px 8px; text-align: left; }
        .data-table th { background: #f0fdf4; font-weight: 800; text-align: center; }
        .badge { font-weight: 800; padding: 2px 6px; border-radius: 4px; font-size: 8.5pt; }
        .badge-lanjut { background: #dcfce7; color: #15803d; }
        .badge-ulang { background: #fee2e2; color: #b91c1c; }
        .ttd-wrap { margin-top: 35px; display: flex; justify-content: space-between; page-break-inside: avoid; }
        .ttd-box { width: 220px; text-align: center; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 16px; background: #f1f5f9; padding: 12px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-weight: 700; color: #334155;">Lembar Resmi Rekapan Pertemuan Tilawati</span>
        <button onclick="window.print()" style="background: #059669; color: white; border: none; padding: 8px 18px; border-radius: 6px; font-weight: 800; cursor: pointer;">
            🖨️ Cetak Dokumen / Simpan PDF
        </button>
    </div>

    <div class="kop">
        <h2>SDIT AN NADZIR</h2>
        <p>REKAPITULASI PEMBELAJARAN AL-QUR'AN METODE TILAWATI</p>
        <p style="font-size: 8.5pt; color: #666;">Jl. Rancamanyar, Baleendah, Bandung - Jawa Barat</p>
    </div>

    @php
        $formattedDate = \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y');
    @endphp
    <table class="meta-table">
        <tr>
            <td style="width: 18%; font-weight: 700;">Kelompok Tilawati</td>
            <td style="width: 2%;">:</td>
            <td style="width: 40%;">{{ $group->name }} ({{ $session->jilid }})</td>
            <td style="width: 18%; font-weight: 700;">Hari, Tanggal</td>
            <td style="width: 2%;">:</td>
            <td style="width: 20%;">{{ $formattedDate }}</td>
        </tr>
        <tr>
            <td style="font-weight: 700;">Pembimbing</td>
            <td>:</td>
            <td>{{ $group->mentor_name }}</td>
            <td style="font-weight: 700;">Materi Peraga</td>
            <td>:</td>
            <td>Hal {{ $session->peraga_page_start }} s/d {{ $session->peraga_page_end }} (4 Hal)</td>
        </tr>
        <tr>
            <td style="font-weight: 700;">Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $group->academicYear->name ?? '-' }}</td>
            <td style="font-weight: 700;">Buku Tilawati</td>
            <td>:</td>
            <td>Halaman {{ $session->buku_page }} (1 Hal)</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th>Nama Santri</th>
                <th style="width: 60px;">Kelas</th>
                <th style="width: 70px;">Presensi</th>
                <th style="width: 60px;">Nilai</th>
                <th style="width: 90px;">Ketuntasan</th>
                <th>Catatan Evaluasi / Makhraj</th>
                <th style="width: 140px;">Tugas Pertemuan Depan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $idx => $att)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-weight: 700;">{{ $att->student->name ?? '-' }}</td>
                    <td style="text-align: center;">{{ $att->student->class ?? '-' }}</td>
                    <td style="text-align: center; text-transform: uppercase; font-weight: 700;">
                        {{ $att->status }}
                    </td>
                    <td style="text-align: center; font-weight: 800;">
                        {{ $att->score !== null ? $att->score : '-' }}
                    </td>
                    <td style="text-align: center;">
                        @if($att->status === 'hadir')
                            @if($att->is_passed)
                                <span class="badge badge-lanjut">LANJUT</span>
                            @else
                                <span class="badge badge-ulang">MENGULANG</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td style="font-size: 9pt;">{{ $att->notes ?? '-' }}</td>
                    <td style="font-size: 8.5pt;">{{ $att->tugas_besok ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd-wrap">
        <div class="ttd-box">
            <p>Mengetahui,<br>Kepala SDIT AN NADZIR</p>
            <div style="height: 60px;"></div>
            <p style="font-weight: 700; text-decoration: underline;">Ust. Kepala Sekolah, S.Pd.I</p>
        </div>
        <div class="ttd-box">
            <p>Bandung, {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}<br>Guru Pembimbing Tilawati</p>
            <div style="height: 60px;"></div>
            <p style="font-weight: 700; text-decoration: underline;">{{ $group->mentor_name }}</p>
        </div>
    </div>

</body>
</html>
