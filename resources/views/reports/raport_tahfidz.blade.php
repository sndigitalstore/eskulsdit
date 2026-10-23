<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raport Tahfidz Standar Nasional – {{ count($students) == 1 ? $students->first()->name : ($class ? 'Kelas ' . $class : ($groupObj->name ?? 'Seluruh Siswa')) }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm 12mm 12mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9.5pt;
            color: #0f172a;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
        }

        /* Top Sticky Toolbar for Browser View */
        .no-print-bar {
            background: #064e3b;
            color: white;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 9999;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            font-family: Arial, sans-serif;
        }

        .btn-print {
            background: #059669;
            color: white;
            border: none;
            padding: 9px 22px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.92rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-print:hover {
            background: #10b981;
            transform: translateY(-1px);
        }

        .btn-close {
            background: #334155;
            color: white;
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-close:hover {
            background: #475569;
        }

        /* A4 Page Container */
        .raport-sheet {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 12mm 14mm 14mm 14mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.08);
            page-break-after: always;
            position: relative;
        }

        .raport-sheet:last-child {
            page-break-after: auto;
        }

        /* Official Institution Kop */
        .kop-surat {
            border-bottom: 3px double #047857;
            padding-bottom: 8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .kop-logo {
            width: 70px;
            height: auto;
            object-fit: contain;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text .instansi-yayasan {
            font-size: 9pt;
            font-weight: 700;
            color: #065f46;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .kop-text .instansi-nama {
            font-size: 15pt;
            font-weight: 900;
            color: #064e3b;
            text-transform: uppercase;
            margin: 2px 0 0 0;
            letter-spacing: 0.5px;
        }

        .kop-text .instansi-npsn {
            font-size: 8pt;
            font-weight: 700;
            color: #334155;
            margin: 2px 0 0 0;
            font-family: Arial, sans-serif;
        }

        .kop-text .instansi-alamat {
            font-size: 7.8pt;
            font-style: italic;
            color: #475569;
            margin: 2px 0 0 0;
            line-height: 1.3;
        }

        /* Title Box */
        .title-box {
            text-align: center;
            margin: 10px 0 12px 0;
        }

        .title-box h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: 800;
            text-transform: uppercase;
            color: #064e3b;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }

        .title-box .subtitle {
            margin: 3px 0 0 0;
            font-size: 9pt;
            font-weight: 700;
            color: #047857;
            font-family: Arial, sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .title-box .semester-info {
            margin: 2px 0 0 0;
            font-size: 8.5pt;
            color: #475569;
            font-family: Arial, sans-serif;
        }

        /* Identity Table */
        .identity-box {
            border: 1px solid #059669;
            background: #f0fdf4;
            border-radius: 6px;
            padding: 8px 14px;
            margin-bottom: 12px;
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
        }

        .identity-table {
            width: 100%;
            border-collapse: collapse;
        }

        .identity-table td {
            padding: 2.5px 4px;
            vertical-align: middle;
            border: none;
        }

        .identity-table .lbl {
            width: 19%;
            color: #334155;
            font-weight: 600;
        }

        .identity-table .sep {
            width: 2%;
            text-align: center;
            font-weight: bold;
        }

        .identity-table .val {
            width: 29%;
            color: #0f172a;
            font-weight: 700;
        }

        /* Overview KPI Cards */
        .overview-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 12px;
            font-family: Arial, sans-serif;
        }

        .overview-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-top: 3px solid #059669;
            border-radius: 6px;
            padding: 6px 10px;
            text-align: center;
        }

        .overview-card .card-lbl {
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.3px;
        }

        .overview-card .card-val {
            font-size: 11pt;
            font-weight: 800;
            color: #064e3b;
            margin-top: 2px;
        }

        .overview-card .card-sub {
            font-size: 7pt;
            color: #047857;
            margin-top: 1px;
            font-weight: 600;
        }

        /* Section Title Header */
        .section-header {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            font-weight: 800;
            color: #064e3b;
            margin: 10px 0 5px 0;
            display: flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .section-header i {
            color: #059669;
        }

        /* Standard Table Styling */
        .raport-table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 8pt;
            margin-bottom: 10px;
        }

        .raport-table th {
            background: #e6f4ea;
            color: #064e3b;
            font-weight: 700;
            text-align: center;
            border: 1px solid #a7d7b8;
            padding: 6px 4px;
        }

        .raport-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            vertical-align: middle;
            color: #0f172a;
            line-height: 1.25;
        }

        .raport-table tr:nth-child(even) td {
            background: #fafcfb;
        }

        .badge-pred {
            display: inline-block;
            font-weight: 800;
            font-size: 8pt;
            padding: 2px 8px;
            border-radius: 4px;
            text-align: center;
        }
        .pred-A { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .pred-B { background: #e0f2fe; color: #0369a1; border: 1px solid #7dd3fc; }
        .pred-C { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
        .pred-D { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

        /* Notes Box */
        .notes-box {
            border: 1px solid #cbd5e1;
            border-left: 4px solid #059669;
            background: #f8fafc;
            border-radius: 4px;
            padding: 8px 12px;
            font-family: 'Times New Roman', serif;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.4;
            font-style: italic;
            margin-bottom: 10px;
        }

        /* Predicate Criteria Guide (Mini Footer Table) */
        .criteria-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px 8px;
            font-family: Arial, sans-serif;
            font-size: 7pt;
            color: #475569;
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
        }

        .criteria-item strong {
            color: #0f172a;
        }

        /* Signatures Section */
        .signature-section {
            margin-top: 14px;
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
            text-align: center;
            width: 33.33%;
            vertical-align: top;
            padding: 0 8px;
            line-height: 1.25;
        }

        .signature-table .job-title {
            font-weight: 700;
            margin-bottom: 45px;
        }

        .signature-table .sign-name {
            font-weight: 800;
            text-decoration: underline;
        }

        .signature-table .sign-nip {
            font-size: 8pt;
            color: #475569;
            margin-top: 2px;
        }

        @media print {
            .no-print-bar { display: none !important; }
            body { background: white; }
            .raport-sheet {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- TOP TOOLBAR -->
    <div class="no-print-bar">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-quran" style="font-size: 1.3rem; color: #34d399;"></i>
            <div>
                <strong style="font-size: 1rem; letter-spacing: 0.3px;">Raport Tahfidz Al-Qur'an Standar Nasional</strong>
                <div style="font-size: 0.78rem; opacity: 0.85;">
                    {{ count($students) }} Lembar Dokumen Raport Siswa Terpilih · TP {{ $yearName }}
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak Dokumen / Simpan PDF
            </button>
            <button onclick="window.close()" class="btn-close">
                Tutup
            </button>
        </div>
    </div>

    @php
        $currentDate = \Carbon\Carbon::now()->isoFormat('D MMMM Y');
    @endphp

    @forelse($students as $student)
        @php
            $grp = $student->mentoringGroups->first() ?? $groupObj;
            $mentorName = $grp ? ($grp->mentorUser?->name ?? $grp->mentor_name ?? '-') : '-';
            $targetLevel = $grp ? ($grp->target_level ?? 'Juz 30') : 'Juz 30';

            $records = $student->tahfidzRecords;
            $assessment = $student->assessment;
            $att = $student->att_summary;
            $latest = $student->latest_record;
            $lancarPercent = $student->lancar_rate;

            $uniqueSurahs = $records->pluck('surah_name')->unique()->values();
        @endphp

        <div class="raport-sheet">
            <!-- 1. KOP SURAT RESMI -->
            <div class="kop-surat">
                <img src="{{ asset('logo.png') }}" class="kop-logo" alt="Logo SDIT AN NADZIR">
                <div class="kop-text">
                    <p class="instansi-yayasan">YAYASAN PENDIDIKAN DAN SOSIAL AN NADZIR</p>
                    <h1 class="instansi-nama">SDIT AN NADZIR</h1>
                    <p class="instansi-npsn">NSS: 102026501053 · NPSN: 69949618 · Akreditasi: A (Amat Baik)</p>
                    <p class="instansi-alamat">Bendungan Karet Cisirih KP. Cukang RT. 09 RW. 02 Desa Kamasan Kecamatan Cinangka Kabupaten Serang - Banten 42167</p>
                </div>
            </div>

            <!-- 2. JUDUL RAPORT -->
            <div class="title-box">
                <h2>LAPORAN HASIL BELAJAR TAHFIDZ AL-QUR'AN</h2>
                <div class="subtitle">(RAPORT CAPAIAN TAHFIDZ & TILAWAH STANDAR NASIONAL)</div>
                <div class="semester-info">
                    Semester {{ $activeYear && $activeYear->active_semester == '2' ? 'Genap (II)' : 'Ganjil (I)' }} · Tahun Pelajaran {{ $yearName }}
                </div>
            </div>

            <!-- 3. IDENTITAS SISWA -->
            <div class="identity-box">
                <table class="identity-table">
                    <tr>
                        <td class="lbl">Nama Lengkap Siswa</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $student->name }}</td>
                        <td class="lbl">Kelas / Rombel</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $student->class }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Nomor Induk Siswa (NIS)</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $student->nis ?? '-' }}</td>
                        <td class="lbl">Halaqah / Kelompok</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $grp ? $grp->name : 'Tahfidz Al-Qur\'an' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Target Hafalan Jenjang</td>
                        <td class="sep">:</td>
                        <td class="val" style="color: #047857;">{{ $targetLevel }}</td>
                        <td class="lbl">Guru Pembimbing (Musyrif)</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $mentorName }}</td>
                    </tr>
                </table>
            </div>

            <!-- 4. RINGKASAN CAPAIAN (OVERVIEW KPI) -->
            <div class="overview-grid">
                <div class="overview-card">
                    <div class="card-lbl">Capaian Terakhir</div>
                    <div class="card-val" style="font-size: 9.5pt;">
                        {{ $latest ? $latest->surah_name : 'Mulai Menghafal' }}
                    </div>
                    <div class="card-sub">
                        {{ $latest && $latest->start_ayah ? 'Ayat ' . $latest->start_ayah . ($latest->end_ayah && $latest->end_ayah != $latest->start_ayah ? '-'.$latest->end_ayah : '') : 'Target ' . $targetLevel }}
                    </div>
                </div>
                <div class="overview-card">
                    <div class="card-lbl">Frekuensi Setoran</div>
                    <div class="card-val">{{ $student->total_records_count }} <span style="font-size: 8pt; font-weight: 500;">kali</span></div>
                    <div class="card-sub">{{ $student->baru_count }} Ziyadah · {{ $student->murojaah_count }} Muroja'ah</div>
                </div>
                <div class="overview-card">
                    <div class="card-lbl">Tingkat Mutqin</div>
                    <div class="card-val">{{ $lancarPercent }}%</div>
                    <div class="card-sub">{{ $student->lancar_count }} Sesi Lancar Mutqin</div>
                </div>
                <div class="overview-card" style="border-top-color: #0284c7;">
                    <div class="card-lbl">Predikat Akhir</div>
                    <div class="card-val" style="color: #0369a1;">
                        {{ $assessment['rata']['pred']['grade'] }}
                    </div>
                    <div class="card-sub" style="color: #0284c7;">
                        {{ $assessment['rata']['pred']['label'] }}
                    </div>
                </div>
            </div>

            <!-- 5. BAGIAN A: ASPEK PENILAIAN MUTU HAFALAN STANDAR NASIONAL -->
            <div class="section-header">
                <i class="fas fa-clipboard-check"></i> A. Aspek Penilaian Mutu Hafalan & Karakter Qur'ani (Standar Nasional)
            </div>
            <table class="raport-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 32%; text-align: left;">Aspek Penilaian (Kompetensi)</th>
                        <th style="width: 10%;">Nilai (0-100)</th>
                        <th style="width: 12%;">Predikat</th>
                        <th style="width: 41%; text-align: left;">Deskripsi Capaian Kompetensi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center; font-weight: bold;">1</td>
                        <td>
                            <strong>{{ $assessment['hifdz']['title'] }}</strong>
                        </td>
                        <td style="text-align: center; font-weight: 800; font-size: 9pt;">{{ $assessment['hifdz']['score'] }}</td>
                        <td style="text-align: center;">
                            <span class="badge-pred pred-{{ $assessment['hifdz']['pred']['grade'] }}">
                                {{ $assessment['hifdz']['pred']['grade'] }} ({{ $assessment['hifdz']['pred']['label'] }})
                            </span>
                        </td>
                        <td>{{ $assessment['hifdz']['desc'] }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: bold;">2</td>
                        <td>
                            <strong>{{ $assessment['tajwid']['title'] }}</strong>
                        </td>
                        <td style="text-align: center; font-weight: 800; font-size: 9pt;">{{ $assessment['tajwid']['score'] }}</td>
                        <td style="text-align: center;">
                            <span class="badge-pred pred-{{ $assessment['tajwid']['pred']['grade'] }}">
                                {{ $assessment['tajwid']['pred']['grade'] }} ({{ $assessment['tajwid']['pred']['label'] }})
                            </span>
                        </td>
                        <td>{{ $assessment['tajwid']['desc'] }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: bold;">3</td>
                        <td>
                            <strong>{{ $assessment['makhraj']['title'] }}</strong>
                        </td>
                        <td style="text-align: center; font-weight: 800; font-size: 9pt;">{{ $assessment['makhraj']['score'] }}</td>
                        <td style="text-align: center;">
                            <span class="badge-pred pred-{{ $assessment['makhraj']['pred']['grade'] }}">
                                {{ $assessment['makhraj']['pred']['grade'] }} ({{ $assessment['makhraj']['pred']['label'] }})
                            </span>
                        </td>
                        <td>{{ $assessment['makhraj']['desc'] }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: bold;">4</td>
                        <td>
                            <strong>{{ $assessment['adab']['title'] }}</strong>
                        </td>
                        <td style="text-align: center; font-weight: 800; font-size: 9pt;">{{ $assessment['adab']['score'] }}</td>
                        <td style="text-align: center;">
                            <span class="badge-pred pred-{{ $assessment['adab']['pred']['grade'] }}">
                                {{ $assessment['adab']['pred']['grade'] }} ({{ $assessment['adab']['pred']['label'] }})
                            </span>
                        </td>
                        <td>{{ $assessment['adab']['desc'] }}</td>
                    </tr>
                    <tr style="background: #f0fdf4; border-top: 2px solid #059669;">
                        <td colspan="2" style="text-align: right; font-weight: 800; text-transform: uppercase; color: #064e3b; padding-right: 12px;">
                            {{ $assessment['rata']['title'] }}
                        </td>
                        <td style="text-align: center; font-weight: 900; font-size: 10pt; color: #064e3b;">
                            {{ $assessment['rata']['score'] }}
                        </td>
                        <td style="text-align: center;">
                            <span class="badge-pred pred-{{ $assessment['rata']['pred']['grade'] }}" style="font-size: 8.5pt;">
                                {{ $assessment['rata']['pred']['grade'] }} ({{ $assessment['rata']['pred']['label'] }})
                            </span>
                        </td>
                        <td style="font-weight: 700; color: #047857;">
                            {{ $assessment['rata']['status'] }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- 6. BAGIAN B: RINCIAN DAFTAR SURAT YANG TELAH DIHAFAL / DISETORKAN -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                <div class="section-header" style="margin: 0 0 5px 0;">
                    <i class="fas fa-book-open"></i> B. Rincian Histori Setoran & Muroja'ah Hafalan
                </div>
                <span style="font-size: 7.5pt; color: #64748b; font-family: Arial, sans-serif;">
                    Total {{ $records->count() }} Setoran Tercatat
                </span>
            </div>
            <table class="raport-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 14%;">Tanggal</th>
                        <th style="width: 25%; text-align: left;">Nama Surat</th>
                        <th style="width: 14%;">Batasan Ayat</th>
                        <th style="width: 14%;">Kategori</th>
                        <th style="width: 14%;">Mutu Hafalan</th>
                        <th style="width: 14%; text-align: left;">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Show up to 8 most relevant/recent records to fit neatly within one standard A4 page
                        $displayRecords = $records->sortByDesc('session_date')->take(8);
                    @endphp
                    @forelse($displayRecords as $k => $rec)
                        <tr>
                            <td style="text-align: center; color: #64748b;">{{ $k + 1 }}</td>
                            <td style="text-align: center;">{{ $rec->session_date ? $rec->session_date->format('d/m/Y') : '-' }}</td>
                            <td style="font-weight: 700; color: #0f172a;">
                                {{ $rec->surah_name }}
                                @if($rec->juz)
                                    <span style="font-size: 7pt; color: #64748b; font-weight: normal;">(Juz {{ $rec->juz }})</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                Ayat {{ $rec->start_ayah ?? 1 }}{{ $rec->end_ayah && $rec->end_ayah != $rec->start_ayah ? ' - ' . $rec->end_ayah : '' }}
                            </td>
                            <td style="text-align: center;">
                                <span style="font-size: 7pt; font-weight: 700; padding: 1px 6px; border-radius: 4px; background: {{ $rec->type === 'setoran_baru' ? '#e0f2fe; color: #0369a1;' : '#ede9fe; color: #6d28d9;' }}">
                                    {{ $rec->type === 'setoran_baru' ? 'Ziyadah (Baru)' : 'Muroja\'ah' }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                @if($rec->quality === 'lancar')
                                    <span style="color: #15803d; font-weight: bold;"><i class="fas fa-check-circle"></i> Lancar (Mutqin)</span>
                                @elseif($rec->quality === 'cukup')
                                    <span style="color: #b45309; font-weight: bold;"><i class="fas fa-info-circle"></i> Cukup Lancar</span>
                                @else
                                    <span style="color: #b91c1c; font-weight: bold;"><i class="fas fa-exclamation-circle"></i> Perlu Bimbingan</span>
                                @endif
                            </td>
                            <td style="font-size: 7.5pt; color: #475569;">
                                {{ $rec->notes ? \Illuminate\Support\Str::limit($rec->notes, 30) : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 12px; color: #94a3b8; font-style: italic;">
                                Belum ada riwayat setoran hafalan tercatat pada semester ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- 7. BAGIAN C: PRESENSI & CATATAN MOTIVASI PEMBIMBING (SIDE BY SIDE) -->
            <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 10px; margin-bottom: 8px;">
                <!-- Presensi -->
                <div>
                    <div class="section-header" style="margin: 0 0 4px 0;">
                        <i class="fas fa-calendar-check"></i> C. Presensi Halaqah
                    </div>
                    <table class="raport-table" style="margin-bottom: 0;">
                        <tr>
                            <td style="width: 45%;">Hadir</td>
                            <td style="width: 55%; font-weight: bold; color: #15803d;">: {{ $att['hadir'] }} Pertemuan</td>
                        </tr>
                        <tr>
                            <td>Sakit (S)</td>
                            <td style="font-weight: bold; color: #b45309;">: {{ $att['sakit'] }} Hari</td>
                        </tr>
                        <tr>
                            <td>Izin (I)</td>
                            <td style="font-weight: bold; color: #0284c7;">: {{ $att['izin'] }} Hari</td>
                        </tr>
                        <tr>
                            <td>Tanpa Keterangan (A)</td>
                            <td style="font-weight: bold; color: #dc2626;">: {{ $att['alpha'] }} Hari</td>
                        </tr>
                        <tr style="background: #f0fdf4;">
                            <td style="font-weight: 700;">Kehadiran</td>
                            <td style="font-weight: 800; color: #047857;">: {{ $att['rate'] }}%</td>
                        </tr>
                    </table>
                </div>

                <!-- Catatan Pembimbing -->
                <div>
                    <div class="section-header" style="margin: 0 0 4px 0;">
                        <i class="fas fa-comment-dots"></i> D. Catatan & Motivasi Guru Pembimbing
                    </div>
                    <div class="notes-box" style="min-height: 105px;">
                        "{{ $student->catatan_guru }}"
                    </div>
                </div>
            </div>

            <!-- 8. KRITERIA PREDIKAT STANDAR NASIONAL -->
            <div class="criteria-box">
                <span class="criteria-item"><strong>Kriteria Predikat:</strong></span>
                <span class="criteria-item"><span class="badge-pred pred-A">A</span> : 90 - 100 <strong>(Mumtaz / Istimewa)</strong></span>
                <span class="criteria-item"><span class="badge-pred pred-B">B</span> : 80 - 89 <strong>(Jayyid Jiddan / Baik Sekali)</strong></span>
                <span class="criteria-item"><span class="badge-pred pred-C">C</span> : 70 - 79 <strong>(Jayyid / Baik)</strong></span>
                <span class="criteria-item"><span class="badge-pred pred-D">D</span> : &lt; 70 <strong>(Maqbul / Bimbingan)</strong></span>
            </div>

            <!-- 9. PENGESAHAN RESMI (TANDA TANGAN 3 PIHAK) -->
            <div class="signature-section">
                <table class="signature-table">
                    <tr>
                        <td>
                            Mengetahui,<br>
                            <span class="job-title">Orang Tua / Wali Siswa</span>
                            <div style="height: 48px;"></div>
                            <span class="sign-name">...................................................</span>
                            <div class="sign-nip">Tanda Tangan Orang Tua</div>
                        </td>
                        <td>
                            Cinangka, {{ $currentDate }}<br>
                            <span class="job-title">Guru Pembimbing Tahfidz</span>
                            <div style="height: 48px;"></div>
                            <span class="sign-name">{{ $mentorName !== '-' ? $mentorName : '...................................................' }}</span>
                            <div class="sign-nip">Musyrif / Musyrifah Halaqah</div>
                        </td>
                        <td>
                            Mengetahui,<br>
                            <span class="job-title">Kepala SDIT AN NADZIR</span>
                            <div style="height: 48px;"></div>
                            <span class="sign-name">{{ $headmasterName }}</span>
                            <div class="sign-nip">NIP/NIY: SDIT-AN-201801</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 60px 20px; font-family: Arial, sans-serif;">
            <i class="fas fa-exclamation-circle" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px;"></i>
            <h3 style="color: #334155;">Tidak Ada Data Siswa Ditemukan</h3>
            <p style="color: #64748b;">Silakan pilih filter kelas atau kelompok tahfidz yang valid.</p>
        </div>
    @endforelse

</body>
</html>
