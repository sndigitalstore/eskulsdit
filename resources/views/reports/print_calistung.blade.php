<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Lulusan Calistung</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 10pt; color: #000; }
        /* Reset for Print */
        @media print {
            .no-print { display: none; }
            @page { margin: 1cm; size: A4; }
        }
        
        table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 9pt; margin-top: 15px; }
        table, th, td { border: 1px solid #333; }
        th { background-color: #f8f9fa; color: #333; font-weight: bold; text-align: center; padding: 6px; }
        td { padding: 6px; vertical-align: middle; line-height: 1.2; }
        
        .no-print { margin: 20px 0; padding: 10px; background: #eee; border: 1px solid #ddd; text-align: right; font-family: sans-serif; }
        .btn { padding: 8px 15px; cursor: pointer; background: #333; color: #fff; border: none; border-radius: 4px; font-size: 10pt; text-decoration: none; display: inline-block;}
        .btn-close { background: #d32f2f; }
        .btn-print { background: #27ae60; }
    </style>
</head>
<body>
    <div class="no-print">
        <span style="float: left; color: #666; font-style: italic;">Laporan Lulusan Calistung (Nilai A)</span>
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak / Simpan PDF</button>
        <button onclick="window.close()" class="btn btn-close">Tutup</button>
    </div>

    <!-- KOP SURAT RESMI -->
    <div class="print-header" style="font-family: 'Times New Roman', serif; margin-bottom: 20px;">
        <table style="border: none; width: 100%; margin-top: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 80px; vertical-align: middle; padding: 0;">
                    <img src="{{ asset('logo.png') }}" style="height: 70px; width: auto;">
                </td>
                <td style="border: none; text-align: left; vertical-align: middle; padding-left: 15px;">
                    <h2 style="margin: 0; font-size: 20pt; font-weight: bold; color: #333; line-height: 1.2;">SDIT AN NADZIR</h2>
                    <p style="margin: 5px 0 0; font-size: 12pt; color: #000; text-transform: uppercase; letter-spacing: 1px; font-weight: bold;">Laporan Lulusan Calistung</p>
                    <p style="margin: 0; font-size: 11pt; color: #555;">Tahun Pelajaran {{ $yearName }}</p>
                </td>
                <td style="border: none; text-align: right; vertical-align: middle;">
                    <!-- Kosong atau Statistik Ringkas -->
                </td>
            </tr>
        </table>
        <div style="border-bottom: 4px solid #3498db; margin-top: 15px; margin-bottom: 2px;"></div>
        <div style="border-bottom: 1px solid #3498db; margin-bottom: 15px;"></div>
    </div>

    <!-- Main Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%; text-align: left;">Nama Siswa</th>
                <th style="width: 10%;">Kelas</th>
                <th style="width: 20%;">Eskul Asal</th>
                <th style="width: 20%;">Kompetensi Lulus (Nilai A)</th>
                <th style="width: 20%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($graduates as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td><strong>{{ $item['name'] }}</strong></td>
                    <td style="text-align: center;">{{ $item['class'] }}</td>
                    <td>{{ $item['eskul'] }}</td>
                    <td>{{ implode(', ', $item['achievements']) }}</td>
                    <td style="font-style: italic; color: #555;">Direkomendasikan Lanjut Eskul Lain</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data siswa lulusan Calistung pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @php
        $headmaster = \App\Models\Setting::where('key', 'headmaster_name')->value('value') ?? 'Nur\'asiah, S.Pd.I';
    @endphp
    <!-- Footer -->
    <div style="margin-top: 30px; display: flex; justify-content: flex-end; font-family: Arial, sans-serif;">
        <div style="text-align: center; width: 220px;">
            <p>Mengetahui,</p>
            <p style="margin-bottom: 60px;">Kepala Sekolah</p>
            <p style="font-weight: bold; text-decoration: underline; margin: 0;">{{ $headmaster }}</p>
        </div>
    </div>
    
    <div style="text-align: right; font-size: 8pt; color: #aaa; margin-top: 20px;">
        Dicetak pada: {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
