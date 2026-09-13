<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Ekstrakurikuler Keseluruhan Seluruh Kelas - SDIT AN NADZIR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #000;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }

        .no-print-bar {
            background: #1e293b;
            color: white;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 9999;
        }

        .btn-print-now {
            background: #10b981;
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
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }

        .page-container {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 15mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            box-sizing: border-box;
            position: relative;
        }

        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .kop-logo {
            width: 65px;
            height: auto;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text h1 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .kop-text h2 {
            margin: 2px 0 0 0;
            font-size: 12pt;
            font-weight: bold;
            color: #1e293b;
        }

        .kop-text p {
            margin: 2px 0 0 0;
            font-size: 8.5pt;
            font-style: italic;
            color: #475569;
        }

        .report-title-box {
            text-align: center;
            margin: 12px 0 16px 0;
        }

        .report-title-box h3 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .report-title-box p {
            margin: 4px 0 0 0;
            font-size: 9.5pt;
            font-family: sans-serif;
            color: #334155;
        }

        .meta-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-family: sans-serif;
            font-size: 9pt;
        }

        .meta-info-table td {
            padding: 3px 0;
            border: none;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
        }

        .report-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            border: 1px solid #94a3b8;
            padding: 6px 4px;
        }

        .report-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            vertical-align: middle;
        }

        .signature-section {
            margin-top: 25px;
            width: 100%;
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
            vertical-align: top;
            text-align: center;
            width: 50%;
        }

        .badge-grade {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 8pt;
        }

        .badge-a { background: #dcfce7; color: #15803d; }
        .badge-b { background: #dbeafe; color: #1d4ed8; }
        .badge-c { background: #fef9c3; color: #a16207; }

        @media print {
            .no-print-bar { display: none !important; }
            body { background: white; }
            .page-container {
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            .page-break {
                page-break-after: always;
                break-after: page;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-print" style="color: #34d399; font-size: 1.2rem;"></i>
            <span style="font-weight: bold; font-family: sans-serif;">Pratinjau Cetak Laporan Keseluruhan (All Classes)</span>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <button onclick="window.print()" class="btn-print-now">
                <i class="fas fa-print"></i> Cetak Sekarang (PDF / Printer)
            </button>
            <button onclick="window.close()" style="background: #475569; color: white; border: none; padding: 8px 14px; border-radius: 8px; font-weight: bold; font-size: 0.85rem; cursor: pointer;">
                Tutup
            </button>
        </div>
    </div>

    @php
        $classKeys = array_keys($reportsByClass);
        $totalClassesCount = count($classKeys);
        $currentDateIndo = \Carbon\Carbon::now()->isoFormat('D MMMM Y');
    @endphp

    @foreach($reportsByClass as $className => $data)
        @php
            $students = $data['students'];
            $homeroomName = $data['homeroom_teacher'] ?? 'Wali Kelas ' . $className;
        @endphp

        <div class="page-container {{ !$loop->last ? 'page-break' : '' }}">
            
            <!-- KOP SURAT RESMI -->
            <div class="kop-surat">
                <img src="{{ asset('logo.png') }}" class="kop-logo" alt="Logo SDIT AN NADZIR" onerror="this.style.display='none'">
                <div class="kop-text">
                    <h1>AN NADZIR ISLAMIC SCHOOL</h1>
                    <h2>SDIT AN NADZIR</h2>
                    <p>Bendungan Karet Cisirih, Kp. Cukang RT.09/RW.02, Desa Kamasan, Kecamatan Cinangka, Kabupaten Serang, Provinsi Banten 42167</p>
                </div>
            </div>

            <!-- JUDUL LAPORAN -->
            <div class="report-title-box">
                <h3>LAPORAN EKSTRAKURIKULER SISWA</h3>
                <p>Tahun Pelajaran {{ $yearName }} — {{ $period == '1' ? 'Semester 1' : ($period == '2' ? 'Semester 2' : 'Semester 1 & 2') }}</p>
            </div>

            <!-- META INFO KELAS -->
            <table class="meta-info-table">
                <tr>
                    <td style="width: 15%; font-weight: bold;">Kelas</td>
                    <td style="width: 35%;">: Kelas {{ $className }}</td>
                    <td style="width: 20%; font-weight: bold;">Total Siswa</td>
                    <td style="width: 30%;">: {{ $students->count() }} Orang Siswa</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Wali Kelas</td>
                    <td>: {{ $homeroomName }}</td>
                    <td style="font-weight: bold;">Tanggal Cetak</td>
                    <td>: {{ $currentDateIndo }}</td>
                </tr>
            </table>

            <!-- TABEL DAFTAR SISWA & NILAI ESKUL -->
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 4%;">No</th>
                        <th style="width: 24%; text-align: left; padding-left: 8px;">Nama Siswa</th>
                        <th style="width: 7%;">NIS</th>
                        <th style="width: 22%; text-align: left; padding-left: 8px;">Ekstrakurikuler</th>
                        <th style="width: 11%;">Nilai Sm 1</th>
                        <th style="width: 11%;">Nilai Sm 2</th>
                        <th style="width: 21%;">Absensi (Hadir / Sakit / Izin / Alpa)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                        @php
                            $studentEskuls = $student->eskuls;
                            $firstEskul = $studentEskuls->first();
                            
                            $sas1Grade = $student->grades->where('type', 'sas1')->first();
                            $sas2Grade = $student->grades->where('type', 'sas2')->first();

                            // Format Nilai SAS 1
                            $val1 = '-';
                            if ($sas1Grade) {
                                $scoreVal = $sas1Grade->score;
                                if (is_string($scoreVal) && (str_contains($scoreVal, ':') || str_contains($scoreVal, '{'))) {
                                    $val1 = 'Terisi (Detail)';
                                } else {
                                    $val1 = $scoreVal;
                                }
                            }

                            // Format Nilai SAS 2
                            $val2 = '-';
                            if ($sas2Grade) {
                                $scoreVal2 = $sas2Grade->score;
                                if (is_string($scoreVal2) && (str_contains($scoreVal2, ':') || str_contains($scoreVal2, '{'))) {
                                    $val2 = 'Terisi (Detail)';
                                } else {
                                    $val2 = $scoreVal2;
                                }
                            }

                            // Rekap Absensi Siswa
                            $attendances = \App\Models\Attendance::where('student_id', $student->id)
                                ->where('academic_year_id', $yearId)
                                ->get();

                            $hadir = $attendances->where('status', 'Hadir')->count();
                            $sakit = $attendances->where('status', 'Sakit')->count();
                            $izin = $attendances->where('status', 'Izin')->count();
                            $alpa = $attendances->where('status', 'Alpha')->count();

                            $attendanceStr = "H:{$hadir} | S:{$sakit} | I:{$izin} | A:{$alpa}";
                        @endphp
                        <tr>
                            <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                            <td style="font-weight: bold; color: #0f172a;">{{ $student->name }}</td>
                            <td style="text-align: center; color: #475569;">{{ $student->nis ?? '-' }}</td>
                            <td>
                                @if($studentEskuls->isNotEmpty())
                                    <ul style="margin: 0; padding-left: 14px;">
                                        @foreach($studentEskuls as $esk)
                                            <li>{{ $esk->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span style="color: #94a3b8; font-style: italic;">Belum daftar eskul</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($val1 !== '-')
                                    <span class="badge-grade {{ str_contains(strtoupper($val1), 'A') ? 'badge-a' : 'badge-b' }}">{{ $val1 }}</span>
                                @else
                                    <span style="color: #cbd5e1;">-</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($val2 !== '-')
                                    <span class="badge-grade {{ str_contains(strtoupper($val2), 'A') ? 'badge-a' : 'badge-b' }}">{{ $val2 }}</span>
                                @else
                                    <span style="color: #cbd5e1;">-</span>
                                @endif
                            </td>
                            <td style="text-align: center; font-size: 8pt; font-weight: bold; color: #334155;">
                                {{ $attendanceStr }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px; color: #94a3b8; font-style: italic;">
                                Tidak ada data siswa untuk kelas {{ $className }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- SEKSI TANDA TANGAN RESMI -->
            @php
                $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
                $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
                $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : 'Nur\'asiah, S.Pd.I');
            @endphp
            <div class="signature-section">
                <table class="signature-table">
                    <tr>
                        <td>
                            Mengetahui,<br>
                            <strong>Kepala SDIT AN NADZIR</strong>
                            <br><br><br><br><br>
                            <strong><u>{{ $headmasterName }}</u></strong>
                        </td>
                        <td>
                            Cinangka, {{ $currentDateIndo }}<br>
                            <strong>Wali Kelas {{ $className }}</strong>
                            <br><br><br><br><br>
                            <strong><u>{{ $homeroomName }}</u></strong>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    @endforeach

</body>
</html>
