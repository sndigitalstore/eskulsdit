<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Lulusan Calistung{{ $selectedClass !== 'ALL' ? ' - Kelas '.$selectedClass : '' }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 10pt; color: #000; }
        /* Reset for Print */
        @media print {
            .no-print { display: none; }
            @page { margin: 1.2cm; size: A4; }
            tr { page-break-inside: avoid; }
        }
        
        table { width: 100%; border-collapse: collapse; font-family: 'Times New Roman', serif; font-size: 9.5pt; margin-top: 15px; }
        table, th, td { border: 1px solid #333; }
        th { background-color: #f8f9fa; color: #333; font-weight: bold; text-align: center; padding: 6px; }
        td { padding: 6px; vertical-align: middle; line-height: 1.3; }
        
        .no-print { margin: 0; padding: 10px 16px; background: #fff; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; font-family: sans-serif; }
        .no-print-label { color: #64748b; font-style: italic; font-size: 0.88rem; }
        .no-print-actions { display: flex; align-items: center; gap: 10px; }
        .btn { padding: 8px 15px; cursor: pointer; background: #333; color: #fff; border: none; border-radius: 4px; font-size: 10pt; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;}
        .btn-close { background: #d32f2f; }
        .btn-print { background: #27ae60; }
        .filter-wrap { display: flex; align-items: center; gap: 8px; }
        .filter-wrap label { font-size: 0.85rem; color: #374151; font-weight: 600; white-space: nowrap; }
        .filter-wrap select { padding: 7px 12px; border: 1.5px solid #d1d5db; border-radius: 6px; font-size: 0.88rem; background: #f8fafc; color: #0f172a; cursor: pointer; }
        .filter-wrap select:focus { outline: none; border-color: #2563eb; }
        .kelas-badge { display: inline-block; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; padding: 2px 10px; border-radius: 999px; font-size: 10pt; font-weight: bold; }
    </style>
</head>
<body>
    <!-- Toolbar (Hanya Tampil di Layar, Tersembunyi saat Cetak) -->
    <div class="no-print">
        <span class="no-print-label">Laporan Lulusan Calistung (Nilai A)
            @if($selectedClass !== 'ALL')
                &nbsp;&mdash;&nbsp;<span class="kelas-badge">Kelas {{ $selectedClass }}</span>
            @else
                &nbsp;&mdash;&nbsp;<span style="font-weight:600; color:#374151;">Semua Kelas</span>
            @endif
        </span>
        <div class="no-print-actions">
            <!-- Filter Kelas -->
            <div class="filter-wrap">
                <label for="classFilter">🏷️ Filter Kelas:</label>
                <select id="classFilter" onchange="applyClassFilter(this.value)">
                    <option value="ALL" {{ $selectedClass === 'ALL' ? 'selected' : '' }}>Semua Kelas</option>
                    @foreach($availableClasses as $kelas)
                        <option value="{{ $kelas }}" {{ $selectedClass === $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
                    @endforeach
                </select>
            </div>
            <button onclick="window.print()" class="btn btn-print">🖨️ Cetak / Simpan PDF</button>
            <button onclick="window.close()" class="btn btn-close">Tutup</button>
        </div>
    </div>

    <!-- KOP SURAT RESMI -->
    <div class="print-header" style="font-family: 'Times New Roman', serif; margin-bottom: 20px;">
        <table style="border: none; width: 100%; margin-top: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 80px; vertical-align: middle; padding: 0;">
                    <img src="{{ asset('logo.png') }}" style="height: 70px; width: auto;">
                </td>
                <td style="border: none; text-align: left; vertical-align: middle; padding-left: 15px;">
                    <h3 style="margin: 0; font-size: 11pt; font-weight: bold; color: #555; text-transform: uppercase; letter-spacing: 1px;">AN NADZIR ISLAMIC SCHOOL</h3>
                    <h2 style="margin: 0; font-size: 20pt; font-weight: bold; color: #333; line-height: 1.2;">SDIT AN NADZIR</h2>
                    <p style="margin: 2px 0 5px 0; font-size: 8.5pt; color: #555;">Bendungan Karet Cisirih, Kp. Cukang RT.09/RW.02, Desa Kamasan, Kecamatan Cinangka, Kabupaten Serang, Provinsi Banten 42167</p>
                    <p style="margin: 5px 0 0; font-size: 12pt; color: #000; text-transform: uppercase; letter-spacing: 1px; font-weight: bold;">
                        Laporan Lulusan Calistung
                        @if($selectedClass !== 'ALL')
                            &mdash; Kelas {{ $selectedClass }}
                        @endif
                    </p>
                    <p style="margin: 0; font-size: 11pt; color: #555;">Tahun Pelajaran {{ $yearName }}</p>
                </td>
                <td style="border: none; text-align: right; vertical-align: middle;">
                    @if($selectedClass !== 'ALL')
                    <div style="background: #f0f9ff; border: 2px solid #bae6fd; border-radius: 10px; padding: 8px 14px; display: inline-block; text-align: center;">
                        <div style="font-size: 8.5pt; color: #0369a1; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Kelas</div>
                        <div style="font-size: 20pt; font-weight: 900; color: #0c4a6e; line-height: 1.1;">{{ $selectedClass }}</div>
                    </div>
                    @else
                    <div style="background: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 10px; padding: 8px 14px; display: inline-block; text-align: center;">
                        <div style="font-size: 8.5pt; color: #15803d; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Lulusan</div>
                        <div style="font-size: 20pt; font-weight: 900; color: #14532d; line-height: 1.1;">{{ $graduates->count() }}</div>
                        <div style="font-size: 8pt; color: #16a34a;">Semua Kelas</div>
                    </div>
                    @endif
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
                <th style="width: 28%; text-align: left;">Nama Siswa</th>
                <th style="width: 10%;">Kelas</th>
                <th style="width: 20%;">Eskul Asal</th>
                <th style="width: 20%;">Kompetensi Lulus (Nilai A)</th>
                <th style="width: 17%;">Keterangan</th>
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
                    <td colspan="6" style="text-align: center; padding: 20px;">
                        Tidak ada data siswa lulusan Calistung
                        @if($selectedClass !== 'ALL')
                            pada Kelas <strong>{{ $selectedClass }}</strong>
                        @endif
                        pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Ringkasan Statistik Per Kelas (hanya tampil saat Semua Kelas) -->
    @if($selectedClass === 'ALL' && $graduates->count() > 0)
    @php
        $byClass = $graduates->groupBy('class')->sortKeys();
    @endphp
    <div style="margin-top: 18px;">
        <table style="font-size: 9pt; width: auto; min-width: 320px;">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 5px 10px;">Kelas</th>
                    <th style="text-align: center; padding: 5px 10px;">Jumlah Lulusan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($byClass as $kls => $items)
                <tr>
                    <td style="padding: 4px 10px;">Kelas {{ $kls }}</td>
                    <td style="text-align: center; padding: 4px 10px; font-weight: bold;">{{ $items->count() }} siswa</td>
                </tr>
                @endforeach
                <tr style="background: #f1f5f9;">
                    <td style="padding: 5px 10px; font-weight: bold;">Total Keseluruhan</td>
                    <td style="text-align: center; padding: 5px 10px; font-weight: bold;">{{ $graduates->count() }} siswa</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
    
    @php
        $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
        $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
        $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : 'Nur\'asiah, S.Pd.I');
    @endphp
    <!-- Footer -->
    <div style="margin-top: 30px; display: flex; justify-content: flex-end; font-family: 'Times New Roman', serif;">
        <div style="text-align: center; width: 240px;">
            <p style="margin: 0;">Mengetahui,</p>
            <p style="margin: 5px 0 60px 0; font-weight: bold;">Kepala SDIT AN NADZIR</p>
            <p style="font-weight: bold; text-decoration: underline; margin: 0;">{{ $headmasterName }}</p>
        </div>
    </div>
    
    <div style="text-align: right; font-size: 8pt; color: #aaa; margin-top: 20px;">
        Dicetak pada: {{ date('d/m/Y H:i') }}
    </div>

    <script>
        function applyClassFilter(val) {
            const url = new URL(window.location.href);
            if (val === 'ALL') {
                url.searchParams.delete('class');
            } else {
                url.searchParams.set('class', val);
            }
            window.location.href = url.toString();
        }
    </script>
</body>
</html>
