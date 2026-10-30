<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Hasil Supervisi - {{ $supervision->teacher ? $supervision->teacher->name : 'Guru' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f1f5f9;
            margin: 0;
            padding: 20px 0;
            color: #0f172a;
            font-size: 10pt;
        }

        .print-container {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm 20mm 20mm 20mm;
            margin: 0 auto;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
        }

        .action-bar {
            width: 210mm;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-print {
            background: #059669;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
        }

        .btn-close {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .doc-title {
            text-align: center;
            margin: 15px 0 20px 0;
            border-bottom: 2.5px solid #059669;
            padding-bottom: 10px;
        }

        .doc-title h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #064e3b;
        }

        .doc-title p {
            margin: 4px 0 0 0;
            font-size: 9pt;
            color: #475569;
            font-weight: 600;
        }

        .info-grid {
            width: 100%;
            margin-bottom: 16px;
            font-size: 9.5pt;
        }

        .info-grid td {
            padding: 3px 6px;
            vertical-align: top;
        }

        .rubric-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 9pt;
        }

        .rubric-table th, .rubric-table td {
            border: 1px solid #94a3b8;
            padding: 6px 8px;
        }

        .rubric-table th {
            background: #f1f5f9;
            text-transform: uppercase;
            font-weight: 800;
            font-size: 8.5pt;
        }

        .notes-box {
            border: 1px solid #94a3b8;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 12px;
            font-size: 9pt;
            line-height: 1.45;
        }

        .notes-box h4 {
            margin: 0 0 4px 0;
            font-size: 9pt;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
        }

        .sig-row {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            font-size: 9.5pt;
            page-break-inside: avoid;
        }

        .sig-col {
            width: 45%;
            text-align: center;
        }

        .sig-space {
            height: 65px;
        }

        .sig-name {
            font-weight: 800;
            text-decoration: underline;
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .action-bar {
                display: none !important;
            }

            .print-container {
                width: 100% !important;
                min-height: auto !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            @page {
                size: A4 portrait;
                margin: 12mm 15mm 15mm 15mm;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <a href="{{ route('supervisions.show', $supervision->id) }}" class="btn-close">
            <i class="fas fa-arrow-left"></i> Kembali ke Aplikasi
        </a>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Cetak Dokumen (Print / PDF)
        </button>
    </div>

    <div class="print-container">
        
        <!-- KOP SURAT RESMI SDIT AN NADZIR -->
        @include('partials.official_kop')

        <!-- JUDUL DOKUMEN -->
        <div class="doc-title">
            <h2>BERITA ACARA & LEMBAR HASIL SUPERVISI EDUKATIF</h2>
            <p>{{ strtoupper($supervision->type_label) }}</p>
        </div>

        <!-- IDENTITAS PELAKSANAAN -->
        <table class="info-grid">
            <tr>
                <td style="width: 22%;">Nama Guru</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;"><strong>{{ $supervision->teacher ? $supervision->teacher->name : '-' }}</strong></td>
                <td style="width: 18%;">Hari, Tanggal</td>
                <td style="width: 2%;">:</td>
                <td style="width: 28%;">{{ $supervision->supervision_date->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td>Mata Pelajaran / Bidang</td>
                <td>:</td>
                <td>{{ $supervision->subject_name ?: '-' }}</td>
                <td>Tahun Pelajaran</td>
                <td>:</td>
                <td>{{ $supervision->academicYear ? $supervision->academicYear->name : '-' }}</td>
            </tr>
            <tr>
                <td>Kelas / Rombel</td>
                <td>:</td>
                <td>{{ $supervision->class_name ?: 'Semua Kelas' }}</td>
                <td>Semester</td>
                <td>:</td>
                <td>{{ $supervision->semester == '1' ? '1 (Ganjil)' : '2 (Genap)' }}</td>
            </tr>
            <tr>
                <td>Supervisor / Penilai</td>
                <td>:</td>
                <td><strong>{{ $supervision->supervisor ? $supervision->supervisor->name : '-' }}</strong></td>
                <td>Predikat Kinerja</td>
                <td>:</td>
                <td><strong style="color: #065f46;">{{ $supervision->predicate }} ({{ $supervision->total_score }})</strong></td>
            </tr>
        </table>

        <!-- TABEL ASPEK & INDIKATOR OBSERVASI -->
        <table class="rubric-table">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center;">No</th>
                    <th>Komponen & Indikator Pengamatan</th>
                    <th style="width: 70px; text-align: center;">Skor (1-4)</th>
                    <th style="width: 110px; text-align: center;">Kategori</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $aspects = $supervision->aspects_score ?: [];
                    $totalPoints = 0;
                    $maxPoints = count($aspects) * 4;
                @endphp
                @foreach($aspects as $key => $item)
                    @php
                        $score = is_array($item) ? ($item['score'] ?? 3) : (int)$item;
                        $title = is_array($item) ? ($item['title'] ?? $key) : $key;
                        $desc = is_array($item) ? ($item['desc'] ?? '') : '';
                        $totalPoints += $score;
                    @endphp
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{ $no++ }}</td>
                        <td>
                            <div style="font-weight: 700;">{{ $title }}</div>
                            @if($desc)
                                <div style="font-size: 8pt; color: #475569; margin-top: 1px;">{{ $desc }}</div>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: 800; font-size: 10pt;">{{ $score }}</td>
                        <td style="text-align: center; font-weight: 600;">
                            {{ $score == 4 ? 'Sangat Baik' : ($score == 3 ? 'Baik' : ($score == 2 ? 'Cukup' : 'Kurang')) }}
                        </td>
                    </tr>
                @endforeach
                <tr style="background: #f8fafc; font-weight: 800;">
                    <td colspan="2" style="text-align: right; padding-right: 12px;">Total Skor Perolehan (Maksimal: {{ $maxPoints }})</td>
                    <td style="text-align: center; font-size: 10.5pt;">{{ $totalPoints }}</td>
                    <td style="text-align: center;">-</td>
                </tr>
                <tr style="background: #ecfdf5; font-weight: 900; color: #064e3b;">
                    <td colspan="2" style="text-align: right; padding-right: 12px;">Nilai Akhir (Skala 0 - 100)</td>
                    <td style="text-align: center; font-size: 11.5pt;">{{ $supervision->total_score }}</td>
                    <td style="text-align: center; font-size: 9.5pt;">{{ $supervision->predicate }}</td>
                </tr>
            </tbody>
        </table>

        <div style="font-size: 8pt; color: #64748b; margin-bottom: 12px; font-style: italic;">
            * Rentang Kategori: 91 - 100 = Sangat Baik (A) &bull; 81 - 90 = Baik (B) &bull; 71 - 80 = Cukup (C) &bull; < 71 = Perlu Pembinaan (D)
        </div>

        <!-- CATATAN EVALUASI & REKOMENDASI -->
        <div class="notes-box">
            <h4>I. Kelebihan & Aspek Positif yang Ditemukan (Strengths):</h4>
            <div style="white-space: pre-line;">{{ $supervision->strengths ?: 'Telah melaksanakan KBM / administrasi sesuai dengan kaidah yang baik.' }}</div>
        </div>

        <div class="notes-box">
            <h4>II. Aspek yang Perlu Ditingkatkan (Areas of Improvement):</h4>
            <div style="white-space: pre-line;">{{ $supervision->improvements ?: 'Tetap konsisten mempertahankan kedisiplinan dan inovasi pembelajaran.' }}</div>
        </div>

        <div class="notes-box">
            <h4>III. Rekomendasi & Rencana Tindak Lanjut Kepala Sekolah:</h4>
            <div style="white-space: pre-line;">
                {{ $supervision->recommendations ?: 'Melanjutkan KBM dan berkoordinasi secara berkala dengan tim kurikulum/penjamin mutu.' }}
                @if($supervision->follow_up_date)
                    <br><strong>Target Evaluasi Lanjutan:</strong> {{ $supervision->follow_up_date->translatedFormat('d F Y') }}
                @endif
            </div>
        </div>

        <!-- TANDA TANGAN RESMI -->
        @php
            $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
            $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
            $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : 'Nur\'asiah, S.Pd.I');
        @endphp
        <div class="sig-row">
            <div class="sig-col">
                <p>Guru yang Disupervisi,</p>
                <div class="sig-space"></div>
                <p class="sig-name">{{ $supervision->teacher ? $supervision->teacher->name : '-' }}</p>
                <p style="margin: 2px 0 0 0; font-size: 8.5pt; color: #475569;">NIP/NUPTK: -</p>
            </div>

            <div class="sig-col">
                <p>Cinangka, {{ $supervision->supervision_date->translatedFormat('d F Y') }}<br><b>Kepala SDIT AN NADZIR</b></p>
                <div class="sig-space"></div>
                <p class="sig-name">{{ $headmasterName }}</p>
                <p style="margin: 2px 0 0 0; font-size: 8.5pt; color: #475569;">Supervisor</p>
            </div>
        </div>

    </div>

</body>
</html>
