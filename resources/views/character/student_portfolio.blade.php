<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio Karakter Siswa – {{ $student->name }} (Kelas {{ $student->class }})</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #1e293b;
            background: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }
        .no-print-bar {
            background: #0f172a;
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
        .page-container {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 12mm 18mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        .doc-title {
            text-align: center;
            margin: 6px 0 16px;
        }
        .doc-title h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: 800;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
            color: #064e3b;
        }
        .doc-title p {
            margin: 3px 0 0;
            font-size: 9pt;
            color: #475569;
            font-family: Arial, sans-serif;
        }
        .student-info-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 14px;
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            margin-bottom: 14px;
        }
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 16px;
            font-family: Arial, sans-serif;
        }
        .kpi-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 10px;
            text-align: center;
            background: #f8fafc;
        }
        .kpi-title { font-size: 7.5pt; text-transform: uppercase; color: #64748b; font-weight: bold; }
        .kpi-val { font-size: 1.25rem; font-weight: 900; margin: 3px 0; }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 8pt;
            margin-bottom: 14px;
        }
        .table-custom th {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-weight: bold;
            color: #1e293b;
            text-align: left;
        }
        .table-custom td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            vertical-align: top;
        }
        .section-header {
            font-family: Arial, sans-serif;
            font-size: 9.5pt;
            font-weight: 800;
            color: #0f172a;
            margin: 14px 0 6px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .feedback-box {
            border: 1px dashed #cbd5e1;
            background: #fafaf9;
            border-radius: 6px;
            padding: 10px 14px;
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            margin-bottom: 16px;
        }
        .signatures {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 8.8pt;
        }
        .sig-col { width: 30%; }
        .sig-space { height: 50px; }
        .sig-name { font-weight: bold; text-decoration: underline; }
        @media print {
            .no-print-bar { display: none !important; }
            body { background: white !important; }
            .page-container {
                box-shadow: none !important;
                margin: 0 auto !important;
                width: 100% !important;
                padding: 5mm 8mm !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div style="font-weight: bold;">
            <i class="fas fa-user-graduate"></i> Portofolio Perkembangan Karakter Siswa
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak Portofolio (A4)
            </button>
            <button onclick="window.close()" style="background: #475569; color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-weight: bold;">
                Tutup
            </button>
        </div>
    </div>

    <div class="page-container">
        <!-- Kop Surat Resmi -->
        @include('partials.official_kop')

        <div class="doc-title">
            <h2>PORTOFOLIO REKAM JEJAK KARAKTER SISWA (SMILE)</h2>
            <p>Instrumen Pembinaan Berkelanjutan Berbasis Profil Pelajar Pancasila & Standar Mutu Karakter SIT</p>
        </div>

        <div class="student-info-box">
            <div>
                <div><strong>Nama Siswa:</strong> {{ $student->name }}</div>
                <div><strong>NIS / NISN:</strong> {{ $student->nis ?? '-' }}</div>
                <div><strong>Kelas / Rombel:</strong> Kelas {{ $student->class }}</div>
            </div>
            <div>
                <div><strong>Tahun Pelajaran:</strong> {{ $year->name ?? '2026/2027' }}</div>
                <div><strong>Nama Orang Tua / Wali:</strong> {{ $student->parent_name ?? 'Wali Murid' }}</div>
                <div><strong>Dicetak Pada:</strong> {{ now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        <!-- KPI Grid Neraca Karakter -->
        <div class="kpi-grid">
            <div class="kpi-card" style="border-left: 3px solid #10b981;">
                <div class="kpi-title">Apresiasi Kebaikan</div>
                <div class="kpi-val" style="color: #047857;">{{ $appreciations->count() }} <span style="font-size: 8pt; font-weight: normal; color: #64748b;">kali</span></div>
                <div style="font-size: 7.5pt; color: #059669; font-weight: bold;">+{{ $totalAppreciationPoints }} Poin</div>
            </div>
            <div class="kpi-card" style="border-left: 3px solid #ef4444;">
                <div class="kpi-title">Kasus Pembinaan</div>
                <div class="kpi-val" style="color: #b91c1c;">{{ $cases->count() }} <span style="font-size: 8pt; font-weight: normal; color: #64748b;">catatan</span></div>
                <div style="font-size: 7.5pt; color: #dc2626; font-weight: bold;">-{{ $totalCasePoints }} Poin</div>
            </div>
            <div class="kpi-card" style="border-left: 3px solid #0284c7;">
                <div class="kpi-title">Neraca Poin Bersih</div>
                <div class="kpi-val" style="color: {{ $netPoints >= 0 ? '#0369a1' : '#b91c1c' }};">
                    {{ $netPoints > 0 ? '+' : '' }}{{ $netPoints }}
                </div>
                <div style="font-size: 7.5pt; color: #64748b;">Saldo Perilaku</div>
            </div>
            <div class="kpi-card" style="border-left: 3px solid #8b5cf6;">
                <div class="kpi-title">Predikat Karakter</div>
                <div class="kpi-val" style="font-size: 0.95rem; color: {{ $predicate['color'] }}; margin-top: 6px;">
                    {{ $predicate['label'] }}
                </div>
            </div>
        </div>

        <!-- Tabel 1: Rekam Apresiasi Kebaikan -->
        <div class="section-header">
            <i class="fas fa-award" style="color: #10b981;"></i> 1. Catatan Apresiasi & Keteladanan Akhlak (SMILE & 8 Dimensi Profil Lulusan)
        </div>
        <table class="table-custom">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="14%">Tanggal</th>
                    <th width="20%">Kategori SMILE</th>
                    <th width="35%">Uraian Kebaikan & Prestasi</th>
                    <th width="16%">8 Dimensi Profil Lulusan</th>
                    <th width="10%" style="text-align: center;">Poin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appreciations as $idx => $appr)
                    <tr>
                        <td align="center">{{ $idx + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($appr->date)->translatedFormat('d M Y') }}</td>
                        <td><strong>{{ $appr->category }}</strong></td>
                        <td>
                            <strong>{{ $appr->title }}</strong>
                            @if(!empty($appr->description))
                                <div style="color: #64748b; font-size: 7.5pt; margin-top: 2px;">{{ $appr->description }}</div>
                            @endif
                        </td>
                        <td>{{ $appr->dimension_p5 ?: 'Adab Islami SIT' }}</td>
                        <td align="center" style="font-weight: bold; color: #047857;">+{{ $appr->point }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" align="center" style="color: #94a3b8; padding: 12px;">Belum ada catatan apresiasi kebaikan pada tahun ajaran ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tabel 2: Rekam Kasus Pembinaan & Aksi Restorasi -->
        <div class="section-header">
            <i class="fas fa-hand-holding-heart" style="color: #ef4444;"></i> 2. Catatan Pembinaan & Aksi Restorasi Karakter (Disiplin Positif)
        </div>
        <table class="table-custom">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="14%">Tanggal</th>
                    <th width="18%">Klasifikasi Masalah</th>
                    <th width="28%">Uraian & Bentuk Pelanggaran</th>
                    <th width="25%">Aksi Restorasi / Konsekuensi Edukatif</th>
                    <th width="10%" style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cases as $idx => $cs)
                    <tr>
                        <td align="center">{{ $idx + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($cs->date)->translatedFormat('d M Y') }}</td>
                        <td>
                            <strong>{{ $cs->category }}</strong>
                            <div style="font-size: 7.2pt; color: #b45309; text-transform: capitalize;">Tingkat {{ $cs->severity }} (-{{ $cs->point }}p)</div>
                        </td>
                        <td>
                            <strong>{{ $cs->title }}</strong>
                            @if(!empty($cs->description))
                                <div style="color: #64748b; font-size: 7.2pt; margin-top: 2px;">{{ $cs->description }}</div>
                            @endif
                        </td>
                        <td>
                            <span style="color: #065f46; font-weight: 600;">{{ $cs->restorative_action ?: ($cs->action_taken ?: 'Teguran & pembinaan adab') }}</span>
                        </td>
                        <td align="center">
                            <span style="font-size: 7.2pt; font-weight: bold; color: {{ $cs->status === 'selesai' ? '#15803d' : '#b91c1c' }};">
                                {{ $cs->status_badge['label'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" align="center" style="color: #10b981; padding: 12px;">Alhamdulillah, tidak ada catatan pelanggaran tata tertib pada tahun ajaran ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Catatan Wali Kelas & Umpan Balik Ortu -->
        <div class="feedback-box">
            <strong>Catatan & Refleksi Wali Kelas:</strong><br>
            <span style="font-style: italic; color: #334155; line-height: 1.5;">
                Ananda {{ $student->name }} menunjukkan dinamika perkembangan karakter yang terpantau secara transparan. Diharapkan kerjasama aktif antara pihak sekolah dan orang tua di rumah agar nilai-nilai kebaikan SMILE (Sholeh, Muslih, Intellectual Curiosity, Leadership, Environmentally) senantiasa tertanam kokoh dalam kepribadian ananda.
            </span>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="sig-col">
                <p>Orang Tua / Wali Murid,</p>
                <div class="sig-space"></div>
                <p class="sig-name">( {{ $student->parent_name ?: 'Wali Murid' }} )</p>
            </div>
            <div class="sig-col">
                <p>Wali Kelas,</p>
                <div class="sig-space"></div>
                <p class="sig-name">( Guru Wali Kelas {{ $student->class }} )</p>
            </div>
            <div class="sig-col">
                <p>Cinangka, {{ now()->translatedFormat('d F Y') }}<br>Kepala SDIT AN NADZIR,</p>
                <div class="sig-space"></div>
                <p class="sig-name">{{ $headmasterName }}</p>
            </div>
        </div>

    </div>

</body>
</html>
