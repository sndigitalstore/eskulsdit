<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Wali Kelas Seluruh Kelas - SDIT AN NADZIR</title>
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
        }

        .no-print-bar {
            background: #1e293b;
            color: white;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
        }

        .page-container {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 15mm;
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

        .kop-logo {
            width: 60px;
            height: auto;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text h1 {
            margin: 0;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text h2 {
            margin: 2px 0 0 0;
            font-size: 11pt;
            font-weight: bold;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
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
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            border: none;
            text-align: center;
            width: 50%;
        }

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
            <span style="font-weight: bold; font-family: sans-serif;">Pratinjau Cetak Rekapitulasi Wali Kelas (Semua Kelas)</span>
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
        $currentDateIndo = \Carbon\Carbon::now()->isoFormat('D MMMM Y');
    @endphp

    @foreach($recapByClass as $className => $data)
        @php
            $students = $data['students'];
            $homeroomName = $data['homeroom_teacher'] ?? 'Wali Kelas ' . $className;
        @endphp

        <div class="page-container {{ !$loop->last ? 'page-break' : '' }}">
            
            <div class="kop-surat">
                <img src="{{ asset('logo.png') }}" class="kop-logo" alt="Logo SDIT AN NADZIR" onerror="this.style.display='none'">
                <div class="kop-text">
                    <h1>SDIT AN NADZIR</h1>
                    <h2>REKAPITULASI PILIHAN EKSTRAKURIKULER SISWA</h2>
                    <p style="margin: 2px 0 0 0; font-size: 8.5pt;">Tahun Pelajaran {{ $yearName }} — Kelas {{ $className }}</p>
                </div>
            </div>

            <table class="report-table" style="margin-top: 15px;">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 30%; text-align: left; padding-left: 8px;">Nama Siswa</th>
                        <th style="width: 12%;">NIS</th>
                        <th style="width: 35%; text-align: left; padding-left: 8px;">Pilihan Ekstrakurikuler</th>
                        <th style="width: 18%;">Status Keikutsertaan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                        @php
                            $studentEskuls = $student->eskuls;
                        @endphp
                        <tr>
                            <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                            <td style="font-weight: bold;">{{ $student->name }}</td>
                            <td style="text-align: center;">{{ $student->nis ?? '-' }}</td>
                            <td>
                                @if($studentEskuls->isNotEmpty())
                                    {{ $studentEskuls->pluck('name')->implode(', ') }}
                                @else
                                    <span style="color: #94a3b8; font-style: italic;">Belum memilih</span>
                                @endif
                            </td>
                            <td style="text-align: center; font-weight: bold; color: {{ $studentEskuls->isNotEmpty() ? '#16a34a' : '#dc2626' }};">
                                {{ $studentEskuls->isNotEmpty() ? 'Aktif' : 'Belum' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px; color: #94a3b8;">
                                Tidak ada data siswa untuk kelas {{ $className }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

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
