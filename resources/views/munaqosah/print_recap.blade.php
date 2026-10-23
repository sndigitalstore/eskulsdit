<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapan Data Hasil Munaqosah Qur'an</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #0f172a;
            line-height: 1.35;
            background: #fff;
            margin: 0;
            padding: 0;
            font-size: 10pt;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #065f46;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .logo {
            width: 65px;
            height: 65px;
            object-fit: contain;
            margin-right: 15px;
        }
        .school-info {
            flex: 1;
            text-align: center;
        }
        .school-info h2 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            color: #065f46;
            text-transform: uppercase;
        }
        .school-info h3 {
            font-size: 11pt;
            font-weight: bold;
            margin: 2px 0;
        }
        .school-info p {
            font-size: 8pt;
            margin: 0;
            color: #475569;
        }
        .title {
            text-align: center;
            margin: 10px 0 14px 0;
        }
        .title h1 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .title p {
            font-size: 9pt;
            color: #475569;
            margin: 3px 0 0 0;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-top: 10px;
        }
        table.data th, table.data td {
            border: 1px solid #334155;
            padding: 5px 8px;
        }
        table.data th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
        }
        .signatures {
            margin-top: 25px;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        .sign-box {
            width: 35%;
            text-align: center;
            font-size: 9.5pt;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="background: #f1f5f9; padding: 10px; text-align: center; border-bottom: 1px solid #cbd5e1; margin-bottom: 15px;">
        <button onclick="window.print()" style="background: #047857; color: white; border: none; padding: 7px 18px; font-size: 13px; font-weight: bold; border-radius: 6px; cursor: pointer;">
            🖨️ Cetak Rekapan Munaqosah
        </button>
    </div>

    <!-- Kop Surat -->
    <div class="header">
        <img src="{{ asset('logo.png') }}" class="logo" alt="Logo">
        <div class="school-info">
            <h2>YAYASAN AN NADZIR ISLAMIC SCHOOL</h2>
            <h3>SDIT AN NADZIR KABUPATEN SERANG</h3>
            <p style="font-size: 8.5pt; color: #475569; margin: 1px 0;">NNS: 101280432037 · NPSN: 20616174</p>
            <p>Bendungan Karet Cisirih KP. Cukang RT. 09 RW. 02 Desa Kamasan Kecamatan Cinangka Kabupaten Serang - Banten 42167</p>
            <p>Website: sditannadzir.sch.id | Email: sditannadzir@gmail.com</p>
        </div>
    </div>

    <!-- Judul Laporan -->
    <div class="title">
        <h1>REKAPAN HASIL EVALUASI & KELULUSAN MUNAQOSAH QUR'AN</h1>
        <p>
            Tahun Ajaran: <strong>{{ $selectedYear->name ?? 'Semua' }}</strong> 
            @if($type !== 'all') • Bidang: <strong>{{ strtoupper($type) }}</strong> @endif
            @if($status !== 'all') • Status: <strong>{{ strtoupper($status) }}</strong> @endif
        </p>
    </div>

    <!-- Data Table -->
    <table class="data">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Nama Santri</th>
                <th style="width: 50px;">Kelas</th>
                <th style="width: 65px;">NIS</th>
                <th style="width: 75px;">Bidang</th>
                <th style="width: 110px;">Jenjang Munaqosah</th>
                <th>Penguji (Munaqisy)</th>
                <th style="width: 55px;">Nilai</th>
                <th style="width: 80px;">Predikat</th>
                <th style="width: 85px;">Keputusan</th>
                <th>Catatan / Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $passedTotal = 0;
                $failedTotal = 0;
            @endphp
            @forelse($registrations as $idx => $r)
                @php
                    if ($r->status === 'passed') $passedTotal++;
                    if ($r->status === 'failed') $failedTotal++;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-weight: bold;">{{ $r->student->name ?? '-' }}</td>
                    <td style="text-align: center;">{{ $r->student->class ?? '-' }}</td>
                    <td style="text-align: center;">{{ $r->student->nis ?? '-' }}</td>
                    <td style="text-align: center; text-transform: uppercase; font-weight: bold;">
                        {{ $r->type }}
                    </td>
                    <td style="text-align: center;">
                        {{ $r->current_level }} ➜ {{ $r->target_level }}
                    </td>
                    <td>{{ $r->examinerUser->name ?? ($r->examiner->user->name ?? '-') }}</td>
                    <td style="text-align: center; font-weight: bold;">
                        {{ $r->final_score !== null ? number_format($r->final_score, 1) : '-' }}
                    </td>
                    <td style="text-align: center;">{{ $r->predicate ?? '-' }}</td>
                    <td style="text-align: center; font-weight: bold; color: {{ $r->status === 'passed' ? '#047857' : ($r->status === 'failed' ? '#b91c1c' : '#334155') }};">
                        @if($r->status === 'passed')
                            LULUS
                        @elseif($r->status === 'failed')
                            BELUM LULUS
                        @elseif($r->status === 'scheduled')
                            TERJADWAL
                        @else
                            PENDING
                        @endif
                    </td>
                    <td style="font-size: 8.5pt;">{{ $r->recommendation ?? $r->examiner_notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 20px; color: #94a3b8;">
                        Tidak ada data munaqosah yang sesuai dengan kriteria filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Statistik Ringkasan -->
    <div style="margin-top: 15px; display: flex; gap: 20px; font-size: 9pt;">
        <div>Total Peserta: <strong>{{ count($registrations) }}</strong></div>
        <div>Lulus Munaqosah: <strong style="color: #047857;">{{ $passedTotal }}</strong></div>
        <div>Belum Lulus: <strong style="color: #b91c1c;">{{ $failedTotal }}</strong></div>
        <div>Tingkat Kelulusan: <strong>{{ count($registrations) > 0 ? round(($passedTotal / count($registrations)) * 100, 1) : 0 }}%</strong></div>
    </div>

    <!-- Tanda Tangan -->
    <div class="signatures">
        <div class="sign-box">
            <p style="margin: 0 0 50px 0;">
                Mengetahui,<br>
                Koordinator Pembinaan Qur'an
            </p>
            <p style="font-weight: bold; margin: 0; text-decoration: underline;">
                Dewan Munaqisy SDIT An Nadzir
            </p>
        </div>

        <div class="sign-box">
            <p style="margin: 0 0 50px 0;">
                Cinangka, {{ date('d F Y') }}<br>
                Kepala Sekolah SDIT An Nadzir
            </p>
            <p style="font-weight: bold; margin: 0; text-decoration: underline;">
                {{ \App\Models\User::where('role', 'headmaster')->value('name') ?? 'Kepala Sekolah' }}
            </p>
        </div>
    </div>

</body>
</html>
