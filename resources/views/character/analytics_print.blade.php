<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Analitik Capaian Karakter Siswa - SDIT AN NADZIR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page { 
            size: A4 landscape; 
            margin: 8mm 12mm 10mm 12mm; 
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9pt;
            color: #0f172a;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }
        .no-print-bar {
            background: #1e293b;
            color: white;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 9999;
            font-family: Arial, sans-serif;
        }
        .btn-print {
            background: #065f46;
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
            padding: 10mm 14mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 6px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .kop-logo { 
            width: 65px; 
            height: auto; 
            object-fit: contain; 
        }
        .kop-text { 
            flex: 1; 
            text-align: center; 
        }
        .kop-text h1 { 
            margin: 0; 
            font-size: 14pt; 
            font-weight: bold; 
            text-transform: uppercase; 
            color: #065f46; 
            letter-spacing: 0.5px;
        }
        .kop-text h2 { 
            margin: 1px 0 0 0; 
            font-size: 11.5pt; 
            font-weight: bold; 
            color: #000; 
        }
        .kop-text p { 
            margin: 1px 0 0 0; 
            font-size: 8pt; 
            color: #334155; 
            line-height: 1.3;
        }
        .title-box { 
            text-align: center; 
            margin: 6px 0 10px; 
        }
        .title-box h3 { 
            margin: 0; 
            font-size: 11pt; 
            font-weight: bold; 
            text-transform: uppercase; 
            text-decoration: underline; 
            letter-spacing: 0.5px; 
            color: #064e3b;
        }
        .title-box p { 
            margin: 3px 0 0; 
            font-size: 8.5pt; 
            font-family: Arial, sans-serif; 
            color: #475569; 
        }

        /* KPI Cards Grid */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 8px;
            margin-bottom: 10px;
            font-family: Arial, sans-serif;
        }
        .kpi-card {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 8px;
            text-align: center;
            background: #f8fafc;
        }
        .kpi-title {
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 2px;
        }
        .kpi-val {
            font-size: 12pt;
            font-weight: 900;
            line-height: 1.1;
        }
        .kpi-sub {
            font-size: 6.8pt;
            color: #64748b;
            margin-top: 2px;
        }

        /* Dual Column Sections */
        .section-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 12px;
            margin-bottom: 10px;
            font-family: Arial, sans-serif;
        }
        .sub-card {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 10px;
            background: #ffffff;
        }
        .sub-card-title {
            font-size: 8pt;
            font-weight: bold;
            color: #064e3b;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 4px;
            margin-bottom: 6px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Tables */
        .report-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-family: Arial, sans-serif; 
            font-size: 7.8pt; 
            margin-bottom: 10px; 
        }
        .report-table th, .report-table td { 
            border: 1px solid #94a3b8; 
            padding: 4px 6px; 
            vertical-align: middle; 
        }
        .report-table th { 
            background-color: #f1f5f9 !important; 
            color: #1e293b; 
            font-weight: bold; 
            text-align: center; 
        }
        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 6.8pt;
            font-weight: bold;
        }

        /* Signatures */
        .signatures-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 14px;
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            text-align: center;
            page-break-inside: avoid;
        }
        .sig-block {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 85px;
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

    <!-- Print Control Bar (Screen Only) -->
    <div class="no-print-bar">
        <div style="font-size: 0.95rem; font-weight: bold; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-chart-line" style="color: #6ee7b7;"></i>
            <span>Laporan Eksekutif Capaian Karakter Siswa (A4 Landscape)</span>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak Laporan Resmi
            </button>
            <button onclick="window.close()" class="btn-close">Tutup</button>
        </div>
    </div>

    <div class="page-container">

        <!-- KOP SURAT RESMI -->
        <div class="kop-surat">
            <img src="{{ asset('assets/images/logo.png') }}" class="kop-logo" alt="Logo SDIT An Nadzir" onerror="this.onerror=null;this.src='{{ asset('logo.png') }}';">
            <div class="kop-text">
                <h1>AN NADZIR ISLAMIC SCHOOL</h1>
                <h2>SEKOLAH DASAR ISLAM TERPADU (SDIT) AN NADZIR</h2>
                <p>NPSN: 69978738 &bull; Izin Operasional: 421.2/18-Disdikbud/2018 &bull; Akreditasi: B</p>
                <p>Jl. Raya Karang Bolong Km. 140, Desa Cinangka, Kec. Cinangka, Kab. Serang, Banten 42167</p>
            </div>
        </div>

        <!-- JUDUL LAPORAN & METADATA -->
        <div class="title-box">
            <h3>LAPORAN EKSEKUTIF ANALITIK & CAPAIAN KARAKTER SISWA (SMILE KARAKTER)</h3>
            <p>
                Tahun Pelajaran: <strong>{{ $academicYear->name ?? '-' }}</strong>
                &nbsp;&bull;&nbsp;
                Skala Periode: <strong>
                    @if($period === 'daily')
                        Harian ({{ $days }} Hari Terakhir)
                    @elseif($period === 'monthly')
                        Bulanan (Juli s.d. Juni)
                    @elseif($period === 'semester')
                        Semester (Ganjil & Genap)
                    @else
                        Tahunan (Komparasi Multi-Tahun)
                    @endif
                </strong>
                &nbsp;&bull;&nbsp;
                Filter Rombel: <strong>{{ $selectedClass && $selectedClass !== 'ALL' ? 'Kelas ' . $selectedClass : 'Seluruh Kelas (Global)' }}</strong>
                &nbsp;&bull;&nbsp;
                Tanggal Cetak: <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</strong>
            </p>
        </div>

        <!-- KPI SUMMARY GRID -->
        <div class="kpi-grid">
            <div class="kpi-card" style="border-top: 3px solid #059669;">
                <div class="kpi-title">Total Apresiasi</div>
                <div class="kpi-val" style="color: #059669;">{{ number_format($totalAppreciations, 0, ',', '.') }}</div>
                <div class="kpi-sub">+{{ number_format($totalAppreciationPoints, 0, ',', '.') }} Poin Kebaikan</div>
            </div>

            <div class="kpi-card" style="border-top: 3px solid #dc2626;">
                <div class="kpi-title">Kasus Tercatat</div>
                <div class="kpi-val" style="color: #dc2626;">{{ number_format($totalCases, 0, ',', '.') }}</div>
                <div class="kpi-sub">-{{ number_format($totalCasePoints, 0, ',', '.') }} Poin Kasus</div>
            </div>

            <div class="kpi-card" style="border-top: 3px solid #0284c7;">
                <div class="kpi-title">Kasus Tuntas</div>
                <div class="kpi-val" style="color: #0284c7;">{{ $resolvedCases }}</div>
                <div class="kpi-sub">Tingkat Tuntas: <strong>{{ $resolveRate }}%</strong></div>
            </div>

            <div class="kpi-card" style="border-top: 3px solid #d97706;">
                <div class="kpi-title">Dalam Pembinaan</div>
                <div class="kpi-val" style="color: #d97706;">{{ $activeCases }}</div>
                <div class="kpi-sub">Restoratif Berjalan</div>
            </div>

            <div class="kpi-card" style="border-top: 3px solid #7c3aed;">
                <div class="kpi-title">Kasus TPPK</div>
                <div class="kpi-val" style="color: #7c3aed;">{{ $tppkCases }}</div>
                <div class="kpi-sub">Atensi Khusus</div>
            </div>

            <div class="kpi-card" style="border-top: 3px solid {{ $netPoints >= 0 ? '#059669' : '#dc2626' }};">
                <div class="kpi-title">Poin Bersih (Net)</div>
                <div class="kpi-val" style="color: {{ $netPoints >= 0 ? '#059669' : '#dc2626' }};">
                    {{ $netPoints > 0 ? '+' : '' }}{{ number_format($netPoints, 0, ',', '.') }}
                </div>
                <div class="kpi-sub">{{ $netPoints >= 0 ? 'Indeks Positif' : 'Perlu Intervensi' }}</div>
            </div>
        </div>

        <!-- 2 KOLOM SUB-SEKSI: 8 DIMENSI PROFIL LULUSAN & PREDIKAT SISWA -->
        <div class="section-grid">
            <!-- Kolom 1: Distribusi 8 Dimensi Profil Lulusan Kemendikdasmen -->
            <div class="sub-card">
                <div class="sub-card-title">
                    <i class="fas fa-certificate" style="color: #059669;"></i>
                    Capaian 8 Dimensi Profil Lulusan Kemendikdasmen (Deep Learning)
                </div>
                <table style="width: 100%; border-collapse: collapse; font-size: 7.2pt;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #cbd5e1; color: #475569;">
                            <th style="padding: 3px 5px; text-align: left;">Dimensi Karakter Lulusan</th>
                            <th style="padding: 3px 5px; text-align: center; width: 85px;">Pilar SMILE</th>
                            <th style="padding: 3px 5px; text-align: right; width: 65px;">Frekuensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $dimMap = [
                                'Keimanan & Ketakwaan' => 'Sholeh (S)',
                                'Kewargaan'           => 'Muslih (M)',
                                'Penalaran Kritis'    => 'Intellectual (I)',
                                'Kreativitas'         => 'Intellectual (I)',
                                'Kolaborasi'          => 'Muslih (M)',
                                'Kemandirian'         => 'Leadership (L)',
                                'Kesehatan'           => 'Leadership (L)',
                                'Komunikasi'          => 'Intellectual (I)',
                            ];
                        @endphp
                        @foreach($dimensionLabels as $idx => $dim)
                            <tr style="border-bottom: 1px dashed #e2e8f0;">
                                <td style="padding: 3px 5px; font-weight: bold; color: #1e293b;">
                                    {{ $idx + 1 }}. {{ $dim }}
                                </td>
                                <td style="padding: 3px 5px; text-align: center; color: #065f46; font-weight: 600;">
                                    {{ $dimMap[$dim] ?? 'SMILE' }}
                                </td>
                                <td style="padding: 3px 5px; text-align: right; font-weight: bold; color: #047857;">
                                    {{ $dimensionCounts[$idx] ?? 0 }} kali
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Kolom 2: Sebaran Predikat Karakter & Klasifikasi Kasus -->
            <div class="sub-card">
                <div class="sub-card-title">
                    <i class="fas fa-user-check" style="color: #0284c7;"></i>
                    Sebaran Predikat Karakter & Kepatuhan Siswa
                </div>
                <table style="width: 100%; border-collapse: collapse; font-size: 7.5pt; margin-bottom: 6px;">
                    <tbody>
                        <tr style="border-bottom: 1px dashed #e2e8f0;">
                            <td style="padding: 4px 6px;">
                                <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                    Bintang Karakter SMILE
                                </span>
                            </td>
                            <td style="padding: 4px 6px; text-align: right; font-weight: bold; font-size: 9pt; color: #b45309;">
                                {{ $predicateCounts['Bintang Karakter SMILE'] ?? 0 }} siswa
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px dashed #e2e8f0;">
                            <td style="padding: 4px 6px;">
                                <span class="badge" style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">
                                    Baik & Kondusif
                                </span>
                            </td>
                            <td style="padding: 4px 6px; text-align: right; font-weight: bold; font-size: 9pt; color: #15803d;">
                                {{ $predicateCounts['Baik & Kondusif'] ?? 0 }} siswa
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px dashed #e2e8f0;">
                            <td style="padding: 4px 6px;">
                                <span class="badge" style="background: #fef9c3; color: #854d0e; border: 1px solid #fef08a;">
                                    Dalam Pembinaan Restoratif
                                </span>
                            </td>
                            <td style="padding: 4px 6px; text-align: right; font-weight: bold; font-size: 9pt; color: #a16207;">
                                {{ $predicateCounts['Dalam Pembinaan'] ?? 0 }} siswa
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 6px;">
                                <span class="badge" style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">
                                    Perlu Pendampingan Intensif
                                </span>
                            </td>
                            <td style="padding: 4px 6px; text-align: right; font-weight: bold; font-size: 9pt; color: #b91c1c;">
                                {{ $predicateCounts['Perlu Pendampingan Intensif'] ?? 0 }} siswa
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div style="font-size: 7pt; color: #475569; background: #f8fafc; padding: 4px 8px; border-radius: 4px; border: 1px solid #e2e8f0; line-height: 1.3;">
                    <strong>Klasifikasi Kasus Pembinaan:</strong>
                    Ringan: <strong>{{ $severityCounts['ringan'] ?? 0 }}</strong> |
                    Sedang: <strong>{{ $severityCounts['sedang'] ?? 0 }}</strong> |
                    Berat: <strong>{{ $severityCounts['berat'] ?? 0 }}</strong> |
                    TPPK: <strong>{{ $tppkCases }}</strong>
                </div>
            </div>
        </div>

        <!-- TABEL REKAPITULASI CAPAIAN PER PERIODE -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="text-align: left;">Periode / Waktu Evaluasi</th>
                    <th style="width: 100px; color: #047857;">Apresiasi (Frekuensi)</th>
                    <th style="width: 80px; color: #059669;">Poin (+)</th>
                    <th style="width: 100px; color: #b91c1c;">Kasus (Frekuensi)</th>
                    <th style="width: 80px; color: #dc2626;">Poin (-)</th>
                    <th style="width: 85px;">Poin Bersih</th>
                    <th style="width: 110px;">Status Kepatuhan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tableData as $idx => $row)
                    @php
                        $net = $row['net'] ?? 0;
                        $hasCase = ($row['cases_count'] ?? 0) > 0;
                        $hasAppr = ($row['appr_count'] ?? 0) > 0;
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td style="font-weight: bold; color: #1e293b;">{{ $row['label'] }}</td>
                        <td style="text-align: center; font-weight: bold; color: #047857;">
                            {{ $row['appr_count'] }} kali
                        </td>
                        <td style="text-align: center; font-weight: bold; color: #059669;">
                            +{{ $row['appr_points'] }}
                        </td>
                        <td style="text-align: center; font-weight: bold; color: {{ $hasCase ? '#b91c1c' : '#64748b' }};">
                            {{ $row['cases_count'] }} kejadian
                        </td>
                        <td style="text-align: center; font-weight: bold; color: {{ $hasCase ? '#dc2626' : '#64748b' }};">
                            -{{ $row['case_points'] }}
                        </td>
                        <td style="text-align: center; font-weight: 900; color: {{ $net >= 0 ? '#059669' : '#dc2626' }};">
                            {{ $net > 0 ? '+' : '' }}{{ $net }}
                        </td>
                        <td style="text-align: center;">
                            @if(!$hasCase && $hasAppr)
                                <span class="badge" style="background: #dcfce7; color: #15803d;">Sangat Baik</span>
                            @elseif(!$hasCase && !$hasAppr)
                                <span class="badge" style="background: #f1f5f9; color: #64748b;">Kondusif</span>
                            @else
                                <span class="badge" style="background: #fee2e2; color: #991b1b;">Tindak Lanjut</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 12px; color: #94a3b8;">
                            Belum ada rekaman aktivitas karakter pada rentang periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- TANDA TANGAN DOKUMEN RESMI (TRIPLE SIGNATURE) -->
        <div class="signatures-grid">
            <div class="sig-block">
                <div>
                    Mengetahui,<br>
                    <strong>Kepala SDIT AN NADZIR</strong>
                </div>
                <div>
                    <br><br><br>
                    <strong><u>{{ $headmasterName }}</u></strong><br>
                    <span style="font-size: 7.5pt; color: #475569;">NIP/NIY: {{ $headmasterNip }}</span>
                </div>
            </div>

            <div class="sig-block">
                <div>
                    Penyusun Laporan,<br>
                    <strong>Bidang Kesiswaan & BK</strong>
                </div>
                <div>
                    <br><br><br>
                    <strong><u>Najibudin, S.Pd</u></strong><br>
                    <span style="font-size: 7.5pt; color: #475569;">Koordinator Karakter Siswa</span>
                </div>
            </div>

            <div class="sig-block">
                <div>
                    Diverifikasi Oleh,<br>
                    <strong>{{ $selectedClass && $selectedClass !== 'ALL' ? 'Wali Kelas ' . $selectedClass : 'Koordinator Wali Kelas' }}</strong>
                </div>
                <div>
                    <br><br><br>
                    <strong><u>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</u></strong><br>
                    <span style="font-size: 7.5pt; color: #475569;">Dewan Asatidz / Guru Pamong</span>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
