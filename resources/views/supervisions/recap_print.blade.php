<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Pelaksanaan Supervisi Guru (PKKS & Akreditasi) - SDIT AN NADZIR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page { 
            size: A4 landscape; 
            margin: 10mm 12mm 12mm 12mm; 
        }
        * {
            box-sizing: border-box;
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
            background: #059669;
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
            text-decoration: none;
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
        .kop-logo { width: 65px; height: auto; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h1 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; color: #000; }
        .kop-text h2 { margin: 2px 0 0 0; font-size: 11pt; font-weight: bold; color: #065f46; text-transform: uppercase; }
        .kop-text p  { margin: 2px 0 0 0; font-size: 8pt; font-style: italic; color: #333; }
        
        .title-box { text-align: center; margin: 10px 0 14px; }
        .title-box h3 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; letter-spacing: 0.5px; }
        .title-box p  { margin: 4px 0 0; font-size: 9pt; font-family: Arial, sans-serif; color: #334155; }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 12px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 14px;
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            margin-bottom: 14px;
        }
        .info-grid div { line-height: 1.45; }

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
            background-color: #ecfdf5 !important; 
            color: #064e3b; 
            font-weight: bold; 
            text-align: center; 
        }

        .signatures-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            text-align: center;
            page-break-inside: avoid;
        }
        .sig-block {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 90px;
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
            <i class="fas fa-file-contract"></i> Laporan Rekapitulasi Supervisi Guru (Instrumen PKKS & Akreditasi)
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak Laporan (A4 Landscape)
            </button>
            <button onclick="window.close()" class="btn-close">Tutup</button>
        </div>
    </div>

    <div class="page-container">
        
        <!-- KOP SURAT RESMI -->
        <div class="kop-surat">
            <img src="{{ asset('assets/images/logo.png') }}" class="kop-logo" alt="Logo SDIT An Nadzir" onerror="this.style.display='none'">
            <div class="kop-text">
                <h1>YAYASAN PENDIDIKAN ISLAM AN NADZIR</h1>
                <h2>SEKOLAH DASAR ISLAM TERPADU (SDIT) AN NADZIR</h2>
                <p>NPSN: 69978738 &bull; Izin Operasional: 421.2/18-Disdikbud/2018 &bull; Akreditasi: B</p>
                <p>Jl. Raya Karang Bolong Km. 140, Desa Cinangka, Kec. Cinangka, Kab. Serang, Banten 42167</p>
            </div>
        </div>

        <!-- TITLE -->
        <div class="title-box">
            <h3>REKAPITULASI PELAKSANAAN SUPERVISI AKADEMIK & PEMBINAAN GURU</h3>
            <p><strong>BUKTI FISIK PENILAIAN KINERJA KEPALA SEKOLAH (PKKS) & AKREDITASI SATUAN PENDIDIKAN</strong></p>
        </div>

        <!-- RINGKASAN DATA -->
        <div class="info-grid">
            <div>
                <strong>Tahun Pelajaran:</strong> {{ $selectedYear ? $selectedYear->name : 'Semua Tahun' }}<br>
                <strong>Semester:</strong> {{ $selectedSemester == 'all' ? 'Seluruh Semester (Tahunan)' : 'Semester ' . $selectedSemester . ' (' . ($selectedSemester == '1' ? 'Ganjil' : 'Genap') . ')' }}<br>
                <strong>Fokus Observasi:</strong> {{ $selectedType ? strtoupper(str_replace('_', ' ', $selectedType)) : 'Seluruh Bidang (KBM, Modul Ajar, Al-Qur\'an, Eskul)' }}
            </div>
            <div>
                <strong>Total Guru Terdaftar:</strong> {{ $totalTeachers }} Guru &bull; <strong>Telah Disupervisi:</strong> {{ $uniqueSupervised }} Guru ({{ $totalTeachers > 0 ? round(($uniqueSupervised / $totalTeachers) * 100, 1) : 0 }}%)<br>
                <strong>Rata-rata Skor Sekolah:</strong> <span style="font-weight: bold; color: #065f46;">{{ $avgScore }} / 100</span><br>
                <strong>Distribusi Predikat:</strong> A: {{ $predicateStats['A'] }} &bull; B: {{ $predicateStats['B'] }} &bull; C: {{ $predicateStats['C'] }} &bull; D: {{ $predicateStats['D'] }}
            </div>
        </div>

        <!-- TABEL DATA SUPERVISI -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 75px;">Tanggal</th>
                    <th style="width: 160px; text-align: left;">Nama Guru / Tenaga Pendidik</th>
                    <th style="width: 120px; text-align: left;">Mapel / Kelas / Eskul</th>
                    <th style="width: 100px;">Jenis Supervisi</th>
                    <th style="width: 50px;">Skor</th>
                    <th style="width: 80px;">Predikat</th>
                    <th style="text-align: left;">Catatan Kekuatan & Rekomendasi Tindak Lanjut</th>
                    <th style="width: 85px;">Target Evaluasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($supervisions as $idx => $s)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td style="text-align: center; white-space: nowrap;">
                            {{ $s->supervision_date ? $s->supervision_date->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                            <strong>{{ $s->teacher ? $s->teacher->name : '-' }}</strong>
                            <div style="font-size: 7pt; color: #555;">
                                @if($s->teacher && $s->teacher->homeroom_class) Wali Kelas {{ $s->teacher->homeroom_class }} @endif
                            </div>
                        </td>
                        <td>
                            <div>{{ $s->subject_name ?: ($s->eskul_name ?: '-') }}</div>
                            @if($s->class_name)
                                <div style="font-size: 7pt; color: #555;">Kelas: {{ $s->class_name }}</div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <span style="font-weight: 600;">{{ $s->type_label }}</span>
                        </td>
                        <td style="text-align: center; font-weight: bold; font-size: 9pt;">
                            {{ $s->total_score }}
                        </td>
                        <td style="text-align: center; font-weight: bold; color: {{ $s->predicate_color }};">
                            {{ $s->predicate }}
                        </td>
                        <td style="font-size: 7.5pt; line-height: 1.3;">
                            @if($s->strengths)
                                <div style="color: #065f46;"><strong>Kelebihan:</strong> {{ \Illuminate\Support\Str::limit($s->strengths, 75) }}</div>
                            @endif
                            @if($s->recommendations)
                                <div style="color: #1e40af; margin-top: 2px;"><strong>Rekomendasi:</strong> {{ \Illuminate\Support\Str::limit($s->recommendations, 80) }}</div>
                            @endif
                            @if(!$s->strengths && !$s->recommendations)
                                <span style="color: #888; font-style: italic;">Observasi terlaksana sesuai standar mutu.</span>
                            @endif
                        </td>
                        <td style="text-align: center; font-size: 7.5pt; white-space: nowrap;">
                            {{ $s->follow_up_date ? $s->follow_up_date->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 25px; color: #64748b;">
                            Tidak ada data pelaksanaan supervisi pada kriteria filter ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @php
            $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
            $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
            $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : 'Nur\'asiah, S.Pd.I');
            $headmasterNip = \App\Models\Setting::where('key', 'headmaster_nip')->value('value') ?: '-';
        @endphp

        <!-- 3 TIER SIGNATURES (PKKS & PENGAWAS SEKOLAH) -->
        <div class="signatures-grid">
            <div class="sig-block">
                <div>
                    Mengetahui,<br>
                    <strong>Pengawas Pembina / Ketua Yayasan</strong>
                </div>
                <div>
                    <br><br><br>
                    <strong><u>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</u></strong><br>
                    <span style="font-size: 8pt; color: #555;">NIP / NUPTK: -</span>
                </div>
            </div>

            <div class="sig-block">
                <div>
                    Cinangka, {{ now()->translatedFormat('d F Y') }}<br>
                    <strong>Tim Penjamin Mutu / Kurikulum</strong>
                </div>
                <div>
                    <br><br><br>
                    <strong><u>{{ auth()->user()->name }}</u></strong><br>
                    <span style="font-size: 8pt; color: #555;">Supervisor / Penjamin Mutu</span>
                </div>
            </div>

            <div class="sig-block">
                <div>
                    Kepala Sekolah,<br>
                    <strong>SDIT AN NADZIR</strong>
                </div>
                <div>
                    <br><br><br>
                    <strong><u>{{ $headmasterName }}</u></strong><br>
                    <span style="font-size: 8pt; color: #555;">NIP/NIY: {{ $headmasterNip }}</span>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
