<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perkembangan Karakter Siswa – {{ $selectedClass ? 'Kelas ' . $selectedClass : 'Semua Kelas' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page { 
            size: A4 landscape; 
            margin: 10mm 12mm 12mm 12mm; 
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9.5pt;
            color: #000;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }
        .no-print-bar {
            background: #1e293b;
            color: white;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 9999;
        }
        .btn-print {
            background: #0284c7;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.9rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-close {
            background: #475569;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
        .page-container {
            background: white;
            width: 297mm;
            min-height: 210mm;
            margin: 20px auto;
            padding: 12mm 15mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .kop-logo { width: 60px; height: auto; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h1 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; color: #000; }
        .kop-text h2 { margin: 2px 0 0 0; font-size: 11pt; font-weight: bold; color: #0369a1; }
        .kop-text p  { margin: 2px 0 0 0; font-size: 8pt; font-style: italic; color: #333; }
        .title-box { text-align: center; margin: 8px 0 12px; }
        .title-box h3 { margin: 0; font-size: 11.5pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; letter-spacing: 0.5px; }
        .title-box p  { margin: 3px 0 0; font-size: 9pt; font-family: Arial, sans-serif; color: #334155; }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 14px;
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            margin-bottom: 12px;
        }
        .info-grid div { line-height: 1.4; }

        .report-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-family: Arial, sans-serif; 
            font-size: 8pt; 
            margin-bottom: 14px; 
        }
        .report-table th, .report-table td { 
            border: 1px solid #94a3b8; 
            padding: 5px 6px; 
            vertical-align: middle; 
        }
        .report-table th { 
            background-color: #e0f2fe !important; 
            color: #0c4a6e; 
            font-weight: bold; 
            text-align: center; 
        }

        .signatures-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            text-align: center;
            page-break-inside: avoid;
        }
        .sig-block {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 80px;
        }

        @media print {
            .no-print-bar { display: none !important; }
            body { background: white; margin: 0; padding: 0; }
            .page-container {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                min-height: auto !important;
            }
        }
    </style>
</head>
<body>

    <!-- Print Control Bar -->
    <div class="no-print-bar">
        <div style="font-size: 0.95rem; font-weight: bold; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-file-signature"></i> Laporan Neraca & Pemantauan Karakter Siswa (A4 Landscape)
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak Dokumen
            </button>
            <button onclick="window.close()" class="btn-close">Tutup</button>
        </div>
    </div>

    <div class="page-container">

        <!-- KOP SURAT RESMI -->
        <div class="kop-surat">
            <img src="{{ asset('logo.png') }}" class="kop-logo" alt="Logo">
            <div class="kop-text">
                <h1>SDIT AN NADZIR</h1>
                <h2>SISTEM PEMANTAUAN AKHLAK & KARAKTER (SMILE Karakter)</h2>
                <p>Membentuk Generasi Qur'ani yang Berakhlak Mulia, Cerdas, Mandiri, dan Berkarakter</p>
            </div>
        </div>

        <!-- JUDUL LAPORAN -->
        <div class="title-box">
            <h3>LAPORAN EVALUASI & REKAPITULASI PEMBIASAAN KARAKTER SISWA</h3>
            <p>
                Tahun Ajaran: <strong>{{ $year->name ?? '-' }}</strong>
                &nbsp;|&nbsp; 
                Kelas: <strong>{{ $selectedClass ? ($selectedClass === 'ALL' ? 'Semua Kelas' : 'Kelas ' . $selectedClass) : 'Seluruh Tingkat' }}</strong>
            </p>
        </div>

        <!-- INFO STATISTIK -->
        <div class="info-grid">
            <div>
                <strong>Total Siswa Terevaluasi:</strong> {{ $students->count() }} orang<br>
                <strong>Total Apresiasi Kebaikan:</strong> {{ $students->sum('appreciations_count') }} catatan (+{{ $students->sum('appreciation_points_total') }} poin)<br>
                <strong>Total Kasus Pembinaan:</strong> {{ $students->sum('cases_count') }} kasus (-{{ $students->sum('cases_point_total') }} poin)
            </div>
            <div style="text-align: right;">
                <strong>Bintang Karakter SMILE:</strong> {{ $students->where('predicate_label', 'Bintang Karakter')->count() }} siswa<br>
                <strong>Dalam Pembinaan Intensif:</strong> {{ $students->where('predicate_label', 'Perlu Pendampingan')->count() }} siswa<br>
                <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </div>
        </div>

        <!-- TABEL DATA -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th style="width: 80px;">NIS</th>
                    <th>Nama Siswa</th>
                    <th style="width: 35px;">L/P</th>
                    <th style="width: 45px;">Kelas</th>
                    <th style="width: 100px;">Apresiasi (+)</th>
                    <th style="width: 100px;">Kasus Pembinaan (-)</th>
                    <th style="width: 75px;">Poin Bersih</th>
                    <th style="width: 160px;">Predikat Karakter</th>
                    <th style="width: 140px;">Catatan Pembina</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $idx => $st)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td style="text-align: center; font-family: monospace;">{{ $st->nis ?? '-' }}</td>
                        <td><strong>{{ $st->name }}</strong></td>
                        <td style="text-align: center;">{{ $st->gender ?? '-' }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $st->class }}</td>
                        <td style="text-align: center; color: #15803d; font-weight: bold;">
                            +{{ $st->appreciation_points_total }} <span style="font-size: 7pt; font-weight: normal; color: #555;">({{ $st->appreciations_count }}x)</span>
                        </td>
                        <td style="text-align: center; color: {{ $st->cases_count > 0 ? '#991b1b' : '#555' }}; font-weight: bold;">
                            -{{ $st->cases_point_total }} <span style="font-size: 7pt; font-weight: normal; color: #555;">({{ $st->cases_count }}x)</span>
                        </td>
                        <td style="text-align: center; font-weight: bold;">
                            {{ $st->net_point >= 0 ? '+'.$st->net_point : $st->net_point }}
                        </td>
                        <td style="text-align: center; font-weight: bold;">
                            {{ $st->predicate_label }}
                        </td>
                        <td style="font-size: 7.5pt;">
                            @if($st->unresolved_cases_count > 0)
                                <span style="color: #991b1b;">Ada {{ $st->unresolved_cases_count }} kasus aktif</span>
                            @elseif($st->appreciations_count > 2 && $st->cases_count == 0)
                                <span style="color: #15803d;">Sangat baik & teladan</span>
                            @else
                                <span style="color: #555;">Kondusif & tertib</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 20px; color: #666;">
                            Tidak ada data siswa pada filter kelas ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @php
            $headmasterName = \App\Models\Setting::get('headmaster_name', "Nur'asiah, S.Pd.I");
            $headmasterNip = \App\Models\Setting::get('headmaster_nip', '-');
        @endphp

        <!-- 3 TIER SIGNATURES -->
        <div class="signatures-grid">
            <div class="sig-block">
                <div>
                    Mengetahui,<br>
                    <strong>Kepala Sekolah SDIT AN NADZIR</strong>
                </div>
                <div>
                    <br><br><br>
                    <strong><u>{{ $headmasterName }}</u></strong><br>
                    <span style="font-size: 8pt; color: #555;">NIP/NIY: {{ $headmasterNip }}</span>
                </div>
            </div>

            <div class="sig-block">
                <div>
                    Penyusun Laporan,<br>
                    <strong>Bidang Kesiswaan & Karakter</strong>
                </div>
                <div>
                    <br><br><br>
                    <strong><u>{{ Auth::user()->name }}</u></strong><br>
                    <span style="font-size: 8pt; color: #555;">Koordinator Karakter Siswa</span>
                </div>
            </div>

            <div class="sig-block">
                <div>
                    Diverifikasi Oleh,<br>
                    <strong>Wali Kelas {{ $selectedClass && $selectedClass !== 'ALL' ? $selectedClass : '' }}</strong>
                </div>
                <div>
                    <br><br><br>
                    <strong><u>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</u></strong><br>
                    <span style="font-size: 8pt; color: #555;">Wali Kelas</span>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
