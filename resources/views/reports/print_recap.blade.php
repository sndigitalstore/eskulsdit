<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Eskul Kelas {{ $class }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px; vertical-align: middle; }
        th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        h2, h3, p { margin: 2px 0; }
        .no-print { margin: 20px 0; padding: 10px; background: #eee; border: 1px solid #ddd; text-align: right; }
        .btn { padding: 8px 15px; cursor: pointer; background: #333; color: #fff; border: none; border-radius: 4px; font-size: 10pt; text-decoration: none; display: inline-block;}
        .btn-close { background: #d32f2f; }
        .btn-print { background: #27ae60; }
        
        @media print {
            .no-print { display: none; }
            @page { margin: 1cm; size: A4; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <span style="float: left; color: #666; font-style: italic;">Tips: Gunakan opsi 'Save as PDF' saat mencetak untuk mengirim ke WA.</span>
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak / Simpan PDF</button>
        <button onclick="window.close()" class="btn btn-close">Tutup</button>
    </div>

    <!-- KOP SURAT RESMI -->
    <div class="print-header" style="font-family: 'Times New Roman', serif; margin-bottom: 20px;">
        <table style="border: none; width: 100%; margin-top: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 80px; vertical-align: middle; padding: 0;">
                    <!-- Pastikan logo.png ada di folder public -->
                    <img src="{{ asset('logo.png') }}" style="height: 70px; width: auto;">
                </td>
                <td style="border: none; text-align: left; vertical-align: middle; padding-left: 15px;">
                    <h2 style="margin: 0; font-size: 20pt; font-weight: bold; color: #333; line-height: 1.2;">SDIT AN NADZIR</h2>
                    <p style="margin: 5px 0 0; font-size: 12pt; color: #000; text-transform: uppercase; letter-spacing: 1px; font-weight: bold;">Rekapitulasi Kegiatan Ekstrakurikuler</p>
                    <p style="margin: 0; font-size: 11pt; color: #555;">Tahun Pelajaran {{ $yearName }}</p>
                </td>
                <td style="border: none; text-align: right; vertical-align: middle;">
                    <div style="font-family: sans-serif; font-size: 16pt; font-weight: bold; border: 2px solid #333; padding: 5px 20px; border-radius: 8px; display: inline-block;">
                        KELAS {{ $class }}
                    </div>
                </td>
            </tr>
        </table>
        <div style="border-bottom: 4px solid #3498db; margin-top: 15px; margin-bottom: 2px;"></div>
        <div style="border-bottom: 1px solid #3498db; margin-bottom: 20px;"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Nama Siswa</th>
                <th style="width: 25%">Eskul Pilihan</th>
                <th style="width: 25%">Jadwal & Info</th>
                <th style="width: 20%">Pembina</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
                @php
                    $eskuls = $student->eskuls;
                @endphp
                @if($eskuls->isEmpty())
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $student->name }}</td>
                        <td colspan="3" class="text-center" style="font-style: italic; color: #666;">-- Belum memilih eskul --</td>
                    </tr>
                @else
                    @foreach($eskuls as $i => $eskul)
                        <tr>
                            @if($i == 0)
                                <td class="text-center" rowspan="{{ $eskuls->count() }}">{{ $index + 1 }}</td>
                                <td rowspan="{{ $eskuls->count() }}" style="font-weight: bold;">{{ $student->name }}</td>
                            @endif
                            <td>{{ $eskul->name }}</td>
                            <td>{{ $eskul->schedule ?? '-' }}</td>
                            <td>{{ $eskul->instructor_name ?? '-' }}</td>
                        </tr>
                    @endforeach
                @endif
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data siswa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
        <div style="text-align: center; width: 220px;">
            <p>Wali Kelas {{ $class }}</p>
            <br><br><br>
            <p style="font-weight: bold; text-decoration: underline; margin-bottom: 2px;">
                {{ $homeroomTeacherName ?? '_________________________' }}
            </p>
        </div>
    </div>
</body>
</html>
