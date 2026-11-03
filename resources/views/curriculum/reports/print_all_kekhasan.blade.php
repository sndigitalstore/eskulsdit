<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Semua Rapor Kekhasan – Kelas {{ $class }} (SDIT AN NADZIR)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm 12mm 10mm 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 8.8pt;
            color: #0f172a;
            background: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }
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
        }
        .btn-print {
            background: #10b981;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.9rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-close {
            background: #334155;
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
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 10mm 15mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            box-sizing: border-box;
            page-break-after: always;
        }
        .page-container:last-child {
            page-break-after: auto;
        }
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 6px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .kop-logo { width: 60px; height: auto; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h1 { margin: 0; font-size: 13pt; font-weight: bold; text-transform: uppercase; color: #000; }
        .kop-text h2 { margin: 2px 0 0 0; font-size: 10.5pt; font-weight: bold; color: #065f46; text-transform: uppercase; }
        .kop-text p  { margin: 2px 0 0 0; font-size: 7.8pt; font-style: italic; color: #333; }

        .doc-title {
            text-align: center;
            margin: 6px 0 10px;
        }
        .doc-title h3 {
            margin: 0;
            font-size: 11.5pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
            color: #064e3b;
        }
        .doc-title p {
            margin: 3px 0 0;
            font-size: 8pt;
            font-family: Arial, sans-serif;
            color: #475569;
            font-weight: 600;
        }

        .student-info {
            width: 100%;
            margin-bottom: 10px;
            font-family: Arial, sans-serif;
            font-size: 8pt;
            border-collapse: collapse;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        .student-info td {
            padding: 4px 8px;
            vertical-align: middle;
        }

        .section-header {
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            font-weight: 800;
            color: #064e3b;
            background: #ecfdf5;
            padding: 4px 8px;
            border-left: 4px solid #059669;
            margin: 8px 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 7.8pt;
            margin-bottom: 8px;
        }
        .report-table th, .report-table td {
            border: 1px solid #94a3b8;
            padding: 3.5px 5px;
            vertical-align: middle;
        }
        .report-table th {
            background-color: #f1f5f9 !important;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
        }

        .notes-box {
            border: 1px dashed #cbd5e1;
            background: #fafaf9;
            border-radius: 6px;
            padding: 6px 10px;
            font-family: Arial, sans-serif;
            font-size: 7.8pt;
            margin-bottom: 10px;
            line-height: 1.35;
        }

        .signatures-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-top: 14px;
            font-family: Arial, sans-serif;
            font-size: 8pt;
            text-align: center;
            page-break-inside: avoid;
        }
        .sig-block {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 70px;
        }

        @media print {
            .no-print-bar { display: none !important; }
            body { background: white !important; }
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
            <i class="fas fa-certificate" style="color: #f59e0b;"></i> Cetak Kolektif Rapor Kekhasan & Karakter Islami (Kelas {{ $class }} &bull; {{ $students->count() }} Siswa)
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak Rapor Seluruh Siswa (A4)
            </button>
            <button onclick="window.close()" class="btn-close">Tutup</button>
        </div>
    </div>

    @php
        $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
        $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
        $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : 'Nur\'asiah, S.Pd.I');
        $headmasterNip = \App\Models\Setting::where('key', 'headmaster_nip')->value('value') ?: '-';

        $gradDimensions = [
            [
                'name' => 'Keimanan & Ketakwaan',
                'smile' => 'S - Sholeh',
                'desc' => 'Taat menjalankan ibadah shalat fardhu dan dhuha, tadarus Al-Qur\'an, berdoa sebelum/sesudah belajar, serta senantiasa mengamalkan adab mulia dalam tutur kata.',
                'keywords' => ['iman', 'takwa', 'taqwa', 'sholeh', 'ibadah', 'sholat']
            ],
            [
                'name' => 'Kewargaan',
                'smile' => 'M - Muslih',
                'desc' => 'Menunjukkan kecintaan kepada tanah air, mematuhi tata tertib sekolah, menghormati guru dan orang tua, serta memiliki kepedulian terhadap kemaslahatan bersama.',
                'keywords' => ['kewargaan', 'warga', 'muslih', 'tertib', 'adab']
            ],
            [
                'name' => 'Penalaran Kritis',
                'smile' => 'I - Intellectual',
                'desc' => 'Mampu berpikir logis dan analitis, aktif bertanya hal-hal bernilai ilmu, gemar membaca (literasi), serta terampil memecahkan masalah dalam tugas belajar.',
                'keywords' => ['kritis', 'nalar', 'curiosity', 'intellectual', 'tanya']
            ],
            [
                'name' => 'Kreativitas',
                'smile' => 'I - Intellectual',
                'desc' => 'Memiliki daya imajinasi dan orisinalitas tinggi, berani mencoba hal-hal baru yang bermanfaat, serta mampu menghasilkan karya inovatif dalam pembelajaran.',
                'keywords' => ['kreatif', 'kreasi', 'karya', 'inovasi']
            ],
            [
                'name' => 'Kolaborasi',
                'smile' => 'M - Muslih',
                'desc' => 'Terampil bekerja sama dalam kelompok, berjiwa gotong-royong, memiliki empati, gemar tolong-menolong, dan menghargai peran serta pendapat teman.',
                'keywords' => ['kolaborasi', 'gotong', 'royong', 'teman', 'bantu']
            ],
            [
                'name' => 'Kemandirian',
                'smile' => 'L - Leadership',
                'desc' => 'Bertanggung jawab atas perlengkapan dan tugas pribadinya, disiplin waktu, memiliki motivasi belajar intrinsik, dan tidak bergantung berlebihan pada orang lain.',
                'keywords' => ['mandiri', 'tanggung jawab', 'disiplin', 'tugas']
            ],
            [
                'name' => 'Kesehatan',
                'smile' => 'S - Sholeh',
                'desc' => 'Senantiasa menjaga kebersihan diri (thaharah), kebugaran jasmani, merawat kesehatan fisik, serta memiliki ketenangan dan kematangan emosi.',
                'keywords' => ['kesehatan', 'sehat', 'thaharah', 'bersih', 'jasmani']
            ],
            [
                'name' => 'Komunikasi',
                'smile' => 'L - Leadership',
                'desc' => 'Mampu menyampaikan ide dan pendapat secara santun, menyimak dengan penuh perhatian, terampil berdialog, serta percaya diri dalam interaksi sosial.',
                'keywords' => ['komunikasi', 'bicara', 'santun', 'pendapat', 'leadership']
            ],
        ];
    @endphp

    @foreach($students as $stu)
        @php
            $sd = $studentData[$stu->id] ?? null;
            $characterAppreciations = $sd['characterAppreciations'] ?? collect();
            $tilawatiGroup = $sd['tilawatiGroup'] ?? null;
            $tilawatiPivot = $sd['tilawatiPivot'] ?? null;
            $tahfidzGroup = $sd['tahfidzGroup'] ?? null;
            $latestTahfidzRecord = $sd['latestTahfidzRecord'] ?? null;
            $eskuls = $sd['eskuls'] ?? collect();
            $eskulGrades = $sd['eskulGrades'] ?? [];
            $appreciationPoints = $sd['appreciationPoints'] ?? 0;
            $casePoints = $sd['casePoints'] ?? 0;
            $netPoint = $sd['netPoint'] ?? 0;
            $summary = $sd['summary'] ?? null;
        @endphp

        <div class="page-container">

            <!-- KOP SURAT RESMI -->
            <div class="kop-surat">
                <img src="{{ asset('assets/images/logo.png') }}" class="kop-logo" alt="Logo SDIT An Nadzir" onerror="this.style.display='none'">
                <div class="kop-text">
                    <h1>AN NADZIR ISLAMIC SCHOOL</h1>
                    <h2>SEKOLAH DASAR ISLAM TERPADU (SDIT) AN NADZIR</h2>
                    <p>NPSN: 69978738 &bull; Izin Operasional: 421.2/18-Disdikbud/2018 &bull; Akreditasi: B</p>
                    <p>Jl. Raya Karang Bolong Km. 140, Desa Cinangka, Kec. Cinangka, Kab. Serang, Banten 42167</p>
                </div>
            </div>

            <!-- TITLE -->
            <div class="doc-title">
                <h3>RAPOR KEKHASAN & PEMBINAAN KARAKTER ISLAMI SISWA</h3>
                <p>INTEGRASI PEMBINAAN AL-QUR'AN, KARAKTER SMILE, DAN 8 PROFIL LULUSAN (DEEP LEARNING)</p>
            </div>

            <!-- IDENTITAS SISWA -->
            <table class="student-info">
                <tr>
                    <td style="width: 15%;"><strong>Nama Siswa</strong></td>
                    <td style="width: 38%;">: <strong>{{ $stu->name }}</strong></td>
                    <td style="width: 18%;"><strong>Tahun Pelajaran</strong></td>
                    <td style="width: 29%;">: {{ $academicYear ? $academicYear->name : '2026/2027' }}</td>
                </tr>
                <tr>
                    <td><strong>NIS / NISN</strong></td>
                    <td>: {{ $stu->nis ?? '-' }} / {{ $stu->nisn ?? '-' }}</td>
                    <td><strong>Semester / Periode</strong></td>
                    <td>: Semester {{ $assessmentInfo['semester'] }} ({{ $assessmentInfo['short'] }})</td>
                </tr>
                <tr>
                    <td><strong>Kelas / Rombel</strong></td>
                    <td>: Kelas {{ $stu->class }}</td>
                    <td><strong>Wali Kelas</strong></td>
                    <td>: {{ ($data['homeroomTeacher'] ?? $homeroomTeacher) ? ($data['homeroomTeacher'] ?? $homeroomTeacher)->name : 'Wali Kelas ' . $stu->class }}</td>
                </tr>
            </table>

            <!-- BAGIAN I: PEMBINAAN AL-QUR'AN -->
            <div class="section-header">
                I. Capaian Pembinaan Al-Qur'an (Tahfidz & Tilawati)
            </div>
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th style="width: 150px; text-align: left;">Program Pembinaan</th>
                        <th style="width: 140px; text-align: left;">Capaian Materi / Juz</th>
                        <th style="width: 80px;">Kelancaran</th>
                        <th style="text-align: left;">Catatan Perkembangan Pembimbing</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">1</td>
                        <td><strong>Tilawati (Tartil Al-Qur'an)</strong></td>
                        <td>
                            @if($tilawatiGroup)
                                {{ $tilawatiGroup->name }} &bull; {{ $tilawatiPivot && $tilawatiPivot->current_page ? 'Halaman ' . $tilawatiPivot->current_page : 'Jilid Berjalan' }}
                            @else
                                Program Pembiasaan Tilawati
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: bold; color: #166534;">
                            {{ $tilawatiPivot->fluency_predicate ?? 'Lancar' }}
                        </td>
                        <td style="font-size: 7.5pt;">
                            {{ $tilawatiPivot->notes ?? 'Mampu membaca tartil dengan makharijul huruf dan tajwid yang konsisten.' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">2</td>
                        <td><strong>Tahfidz (Hafalan Al-Qur'an)</strong></td>
                        <td>
                            @if($latestTahfidzRecord)
                                {{ $latestTahfidzRecord->surah ?: 'Juz 30' }} {{ $latestTahfidzRecord->ayat ? '(Ayat ' . $latestTahfidzRecord->ayat . ')' : '' }}
                            @else
                                Juz 30 (Juz 'Amma)
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: bold; color: #166534;">
                            {{ $latestTahfidzRecord->quality ?? 'Lancar' }}
                        </td>
                        <td style="font-size: 7.5pt;">
                            @if($latestTahfidzRecord && $latestTahfidzRecord->notes)
                                {{ $latestTahfidzRecord->notes }}
                            @else
                                Aktif menyetorkan hafalan dan muraja'ah bersama kelompok halaqah Al-Qur'an.
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- BAGIAN II: 8 PROFIL LULUSAN & KARAKTER SMILE -->
            <div class="section-header">
                II. Capaian Karakter Kekhasan SMILE & 8 Profil Lulusan (Deep Learning)
            </div>
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th style="width: 155px; text-align: left;">Dimensi Profil Lulusan</th>
                        <th style="width: 75px; text-align: center;">Pilar SMILE</th>
                        <th style="width: 75px; text-align: center;">Predikat</th>
                        <th style="text-align: left;">Uraian Capaian Pembiasaan Perilaku Otentik</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gradDimensions as $idx => $dim)
                        @php
                            $dimAppreciation = $characterAppreciations->first(function($app) use ($dim) {
                                $c = strtolower($app->category ?? '');
                                $d = strtolower($app->dimension_p5 ?? '');
                                $t = strtolower($app->title ?? '');
                                foreach ($dim['keywords'] as $kw) {
                                    if (str_contains($c, $kw) || str_contains($d, $kw) || str_contains($t, $kw)) {
                                        return true;
                                    }
                                }
                                return false;
                            });
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                            <td><strong>{{ $dim['name'] }}</strong></td>
                            <td style="text-align: center; color: #065f46; font-weight: bold; font-size: 7.5pt;">{{ $dim['smile'] }}</td>
                            <td style="text-align: center; font-weight: bold; color: {{ $dimAppreciation ? '#15803d' : '#0369a1' }}; font-size: 7.8pt;">
                                {{ $dimAppreciation ? 'SBh (Sangat Baik)' : 'BSH (Berkembang)' }}
                            </td>
                            <td style="font-size: 7.6pt; line-height: 1.3;">
                                @if($dimAppreciation)
                                    <strong style="color: #065f46;">{{ $dimAppreciation->title }}:</strong> {{ $dimAppreciation->description ?? $dim['desc'] }}
                                @else
                                    {{ $dim['desc'] }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- BAGIAN III: EKSTRAKURIKULER & MINAT BAKAT -->
            @if($eskuls->count() > 0)
                <div class="section-header">
                    III. Ekstrakurikuler & Pengembangan Minat Bakat
                </div>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th style="width: 30px;">No</th>
                            <th style="width: 170px; text-align: left;">Nama Ekstrakurikuler</th>
                            <th style="width: 80px;">Predikat</th>
                            <th style="text-align: left;">Keterangan & Catatan Pembina</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eskuls as $eIdx => $esk)
                            @php $eg = $eskulGrades[$esk->id] ?? null; @endphp
                            <tr>
                                <td style="text-align: center;">{{ $eIdx + 1 }}</td>
                                <td><strong>{{ $esk->name }}</strong></td>
                                <td style="text-align: center; font-weight: bold; color: #166534;">
                                    {{ $eg?->grade ?? 'A (Sangat Aktif)' }}
                                </td>
                                <td style="font-size: 7.5pt;">
                                    {{ $eg?->description ?? 'Aktif mengikuti seluruh agenda latihan mingguan dan menunjukkan sportivitas serta bakat yang baik.' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- BAGIAN IV: NERACA POIN KARAKTER & CATATAN WALI KELAS -->
            <div class="notes-box">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; margin-bottom: 6px;">
                    <div>
                        <strong>Neraca Poin Pembiasaan Karakter SMILE:</strong>
                        <span style="color: #15803d; font-weight: bold; margin-left: 6px;">+{{ $appreciationPoints }} Apresiasi</span> &bull;
                        <span style="color: {{ $casePoints > 0 ? '#b91c1c' : '#64748b' }}; font-weight: bold;">-{{ $casePoints }} Catatan Kasus</span> &bull;
                        <strong>Poin Bersih: </strong>
                        <span style="background: {{ $netPoint >= 0 ? '#dcfce7; color: #15803d;' : '#fee2e2; color: #991b1b;' }} padding: 1px 8px; border-radius: 4px; font-weight: bold;">
                            {{ $netPoint >= 0 ? '+'.$netPoint : $netPoint }} Poin ({{ $netPoint >= 20 ? 'Bintang Karakter Teladan' : 'Karakter Baik & Kondusif' }})
                        </span>
                    </div>
                </div>
                <div>
                    <strong>Catatan & Refleksi Wali Kelas:</strong><br>
                    <span style="font-style: italic; color: #334155;">
                        {{ $summary?->homeroom_notes ?: "Ananda {$stu->name} menunjukkan perkembangan karakter dan capaian Al-Qur'an yang positif. Diharapkan sinergi aktif bersama orang tua agar nilai-nilai kebaikan SMILE senantiasa kokoh dalam kepribadian ananda." }}
                    </span>
                </div>
            </div>

            <!-- PENGESAHAN 3 TINGKAT -->
            <div class="signatures-grid">
                <div class="sig-block">
                    <div>
                        Mengetahui,<br>
                        <strong>Orang Tua / Wali Murid</strong>
                    </div>
                    <div>
                        <br><br><br>
                        <strong><u>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</u></strong><br>
                        <span style="font-size: 7.5pt; color: #555;">Orang Tua / Wali Siswa</span>
                    </div>
                </div>

                @php
                    $curTeacher = $data['homeroomTeacher'] ?? $homeroomTeacher;
                @endphp
                <div class="sig-block">
                    <div>
                        Cinangka, {{ now()->translatedFormat('d F Y') }}<br>
                        <strong>Wali Kelas {{ $stu->class }}</strong>
                    </div>
                    <div>
                        <br><br><br>
                        <strong><u>{{ $curTeacher ? $curTeacher->name : 'Wali Kelas ' . $stu->class }}</u></strong><br>
                        <span style="font-size: 7.5pt; color: #555;">NIP/NIY: {{ $curTeacher?->nip ?? '-' }}</span>
                    </div>
                </div>

                <div class="sig-block">
                    <div>
                        Mengetahui,<br>
                        <strong>Kepala SDIT AN NADZIR</strong>
                    </div>
                    <div>
                        <br><br><br>
                        <strong><u>{{ $headmasterName }}</u></strong><br>
                        <span style="font-size: 7.5pt; color: #555;">NIP/NIY: {{ $headmasterNip }}</span>
                    </div>
                </div>
            </div>

        </div>
    @endforeach

</body>
</html>

