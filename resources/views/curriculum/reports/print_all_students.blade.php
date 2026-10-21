<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Semua Rapor – Kelas {{ $class }} ({{ $assessmentInfo['short'] }})</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.2cm 1.5cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111;
            line-height: 1.35;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            background: #fff;
        }

        .no-print {
            background: #f1f5f9;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #cbd5e1;
            margin-bottom: 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .btn-print {
            background: #0284c7;
            color: white;
            border: none;
            padding: 10px 22px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            font-family: sans-serif;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        }

        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .page-break { page-break-after: always; }
        }

        .page-break {
            page-break-after: always;
        }

        .student-report-card {
            padding-bottom: 20px;
        }

        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }

        .kop-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-right: 18px;
        }

        .kop-text {
            text-align: center;
            flex: 1;
        }

        .kop-text h1 {
            font-size: 15pt;
            margin: 0;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .kop-text h2 {
            font-size: 11pt;
            margin: 2px 0;
            font-weight: bold;
            color: #222;
        }

        .kop-text p {
            font-size: 8.5pt;
            margin: 1px 0;
            color: #444;
        }

        .report-title {
            text-align: center;
            margin-bottom: 14px;
        }

        .report-title h3 {
            font-size: 12pt;
            margin: 0;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .report-title p {
            margin: 3px 0 0 0;
            font-size: 9.5pt;
            color: #333;
        }

        .student-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 9.5pt;
        }

        .student-info td {
            padding: 2.5px 4px;
            vertical-align: top;
        }

        .section-title {
            font-size: 10pt;
            font-weight: bold;
            margin: 10px 0 6px 0;
            text-transform: uppercase;
            border-left: 3px solid #0284c7;
            padding-left: 6px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9pt;
        }

        .table-data th, .table-data td {
            border: 1px solid #333;
            padding: 4.5px 6px;
            vertical-align: middle;
        }

        .table-data th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .notes-box {
            border: 1px solid #333;
            padding: 8px 10px;
            min-height: 45px;
            font-size: 9pt;
            margin-bottom: 14px;
        }

        .signatures-grid {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .sig-col {
            width: 30%;
            text-align: center;
            font-size: 9.5pt;
        }

        .sig-space {
            height: 60px;
        }
    </style>
</head>
<body>

    <div class="no-print">
        <div style="font-family: sans-serif;">
            <div style="font-weight: bold; font-size: 16px; color: #1e293b;">
                Cetak Masal Rapor Kelas {{ $class }}
            </div>
            <div style="font-size: 13px; color: #64748b; margin-top: 2px;">
                Total: <b>{{ $students->count() }} siswa</b> – Periode: {{ $assessmentInfo['name'] }} ({{ $assessmentInfo['short'] }})
            </div>
        </div>
        <div>
            <button class="btn-print" onclick="window.print()">🖨️ Cetak Semua Rapor (Print / PDF)</button>
        </div>
    </div>

    @php
        $headmasterName = \App\Models\Setting::get('headmaster_name', "Nur'asiah, S.Pd.I");
        $headmasterNip = \App\Models\Setting::get('headmaster_nip', '-');
    @endphp

    @foreach($students as $sIdx => $student)
        @php
            $data = $studentData[$student->id] ?? [];
            $scores = $data['scores'] ?? [];
            $summary = $data['summary'] ?? null;
            $tilawatiGroup = $data['tilawatiGroup'] ?? null;
            $tilawatiPivot = $data['tilawatiPivot'] ?? null;
            $tahfidzGroup = $data['tahfidzGroup'] ?? null;
            $latestTahfidzRecord = $data['latestTahfidz'] ?? null;
            $eskuls = $data['eskuls'] ?? collect();
            $eskulGrades = $data['eskulGrades'] ?? collect();
            $characterAppreciations = $data['appreciations'] ?? collect();
        @endphp

        <div class="student-report-card {{ !$loop->last ? 'page-break' : '' }}">
            <!-- KOP SURAT -->
            <div class="kop-surat">
                <img src="{{ asset('logo.png') }}" class="kop-logo" alt="Logo">
                <div class="kop-text">
                    <h1>SDIT AN NADZIR</h1>
                    <h2>LAPORAN HASIL BELAJAR PESERTA DIDIK (RAPOR AKADEMIK)</h2>
                    <p>SMILE Kurikulum Merdeka – Membentuk Generasi Qur'ani, Cerdas, dan Berkarakter Mulia</p>
                    <p style="font-size: 8pt; font-style: italic;">Komplek Perguruan SDIT An Nadzir, Bandar Lampung</p>
                </div>
            </div>

            <!-- REPORT TITLE -->
            <div class="report-title">
                <h3>RAPOR {{ $assessmentInfo['name'] }} ({{ $assessmentInfo['short'] }})</h3>
                <p>Tahun Pelajaran: {{ $academicYear->name ?? '-' }} – Semester {{ $assessmentInfo['semester'] == 1 ? '1 (Ganjil)' : '2 (Genap)' }}</p>
            </div>

            <!-- STUDENT INFO -->
            <table class="student-info">
                <tr>
                    <td style="width: 18%;">Nama Peserta Didik</td>
                    <td style="width: 2%;">:</td>
                    <td style="width: 45%; font-weight: bold;">{{ $student->name }}</td>
                    <td style="width: 15%;">Kelas</td>
                    <td style="width: 2%;">:</td>
                    <td style="width: 18%; font-weight: bold;">{{ $student->class }}</td>
                </tr>
                <tr>
                    <td>NIS / NISN</td>
                    <td>:</td>
                    <td>{{ $student->nis ?? '-' }}</td>
                    <td>Semester</td>
                    <td>:</td>
                    <td>{{ $assessmentInfo['semester'] }} ({{ $assessmentInfo['semester'] == 1 ? 'Ganjil' : 'Genap' }})</td>
                </tr>
                <tr>
                    <td>Nama Sekolah</td>
                    <td>:</td>
                    <td>SDIT AN NADZIR</td>
                    <td>Tahun Ajaran</td>
                    <td>:</td>
                    <td>{{ $academicYear->name ?? '-' }}</td>
                </tr>
            </table>

            <!-- TABLE NILAI AKADEMIK -->
            <div class="section-title">A. Nilai Capaian Kompetensi Mata Pelajaran</div>
            <table class="table-data">
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th style="text-align: left; width: 200px;">Mata Pelajaran</th>
                        <th style="width: 50px;">KKTP</th>
                        <th style="width: 55px;">Nilai Akhir</th>
                        <th style="width: 60px;">Predikat</th>
                        <th style="text-align: left;">Capaian Kompetensi / Catatan Belajar</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalScore = 0;
                        $countScore = 0;
                    @endphp
                    @foreach($subjects as $index => $sb)
                        @php
                            $sc = $scores[$sb->id] ?? null;
                            $val = $sc ? (float)$sc->score : null;
                            if ($val !== null) {
                                $totalScore += $val;
                                $countScore++;
                            }
                            $pred = $sc?->predicate ?? ($val !== null ? \App\Models\AcademicScore::calculatePredicate($val) : '-');
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $sb->name }}</strong>
                                @if($sb->category !== 'Wajib / Nasional')
                                    <div style="font-size: 7.5pt; color: #555;">({{ $sb->category }})</div>
                                @endif
                            </td>
                            <td class="text-center">{{ (int)$sb->kkm }}</td>
                            <td class="text-center font-bold">
                                {{ $val !== null ? ($val == round($val) ? (int)$val : $val) : '-' }}
                            </td>
                            <td class="text-center font-bold">{{ $pred }}</td>
                            <td style="font-size: 8.5pt;">
                                @if(!empty($sc?->achievement_note))
                                    {{ $sc->achievement_note }}
                                @elseif($val !== null)
                                    @if($val >= 90)
                                        Menunjukkan pemahaman yang sangat istimewa dalam menguasai seluruh tujuan pembelajaran.
                                    @elseif($val >= 80)
                                        Menunjukkan penguasaan yang baik dalam mencapai tujuan pembelajaran utama.
                                    @elseif($val >= 70)
                                        Menguasai capaian pembelajaran dengan cukup baik, perlu pendampingan pada materi lanjutan.
                                    @else
                                        Perlu bimbingan dan pendampingan lebih intensif dalam menguasai kompetensi dasar.
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td colspan="3" class="text-right">Total Nilai</td>
                        <td class="text-center">{{ $totalScore > 0 ? (round($totalScore) == $totalScore ? (int)$totalScore : $totalScore) : '-' }}</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr style="background-color: #f2f2f2; font-weight: bold;">
                        <td colspan="3" class="text-right">Rata-Rata Nilai</td>
                        <td class="text-center" style="color: #000;">
                            {{ $countScore > 0 ? round($totalScore / $countScore, 2) : '-' }}
                        </td>
                        <td colspan="2" style="font-size: 8.5pt;">
                            @php $avg = $countScore > 0 ? round($totalScore / $countScore, 2) : 0; @endphp
                            Predikat Rata-Rata: <strong>{{ $countScore > 0 ? \App\Models\AcademicScore::calculatePredicate($avg) : '-' }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- TABLE PEMBINAAN AL-QUR'AN -->
            <div class="section-title">B. Perkembangan Pembinaan Al-Qur'an</div>
            <table class="table-data" style="margin-bottom: 12px;">
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th style="text-align: left; width: 170px;">Program Qur'an</th>
                        <th style="text-align: left; width: 170px;">Kelompok & Pembina</th>
                        <th style="text-align: left; width: 170px;">Capaian / Tingkat</th>
                        <th style="text-align: left;">Keterangan & Catatan Pembina</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td><strong>Tilawati (Tahsin & Tajwid)</strong></td>
                        <td>
                            {{ $tilawatiGroup->name ?? 'Belum terplot' }}
                            @if(!empty($tilawatiGroup?->mentor_name))
                                <div style="font-size: 7.5pt; color: #555;">Ustadz/ah: {{ $tilawatiGroup->mentor_name }}</div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ !empty($tilawatiPivot?->notes) ? $tilawatiPivot->notes : ($tilawatiGroup?->target_level ?? 'Jilid Berjalan') }}</strong>
                        </td>
                        <td style="font-size: 8.5pt;">
                            Mengikuti bimbingan makharijul huruf, tajwid, dan kelancaran membaca Al-Qur'an dengan tertib.
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td><strong>Tahfidz Al-Qur'an (Hafalan)</strong></td>
                        <td>
                            {{ $tahfidzGroup->name ?? 'Belum terplot' }}
                            @if(!empty($tahfidzGroup?->mentor_name))
                                <div style="font-size: 7.5pt; color: #555;">Ustadz/ah: {{ $tahfidzGroup->mentor_name }}</div>
                            @endif
                        </td>
                        <td>
                            @if($latestTahfidzRecord)
                                <strong>{{ $latestTahfidzRecord->surah_name }}</strong>
                                <div style="font-size: 7.5pt; color: #0284c7;">
                                    Ayat {{ $latestTahfidzRecord->start_ayah }}-{{ $latestTahfidzRecord->end_ayah }} (Juz {{ $latestTahfidzRecord->juz }})
                                </div>
                            @else
                                <strong>{{ $tahfidzGroup?->target_level ?? 'Juz 30' }}</strong>
                            @endif
                        </td>
                        <td style="font-size: 8.5pt;">
                            @if($latestTahfidzRecord)
                                Kualitas setoran: <strong>{{ $latestTahfidzRecord->quality ?? 'Lancar' }}</strong>. {{ $latestTahfidzRecord->notes ?? "Terus tingkatkan muraja'ah hafalan di rumah." }}
                            @else
                                Aktif menyetorkan hafalan dan muraja'ah bersama kelompok pembinaan tahfidz.
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- TABLE PEMBIASAAN KARAKTER SMILE -->
            <div class="section-title">C. Pembiasaan Karakter SMILE SDIT AN NADZIR</div>
            <table class="table-data" style="margin-bottom: 12px;">
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th style="text-align: left; width: 220px;">Dimensi Karakter SMILE</th>
                        <th style="width: 80px;">Predikat</th>
                        <th style="text-align: left;">Deskripsi Pembiasaan Perilaku</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $smileDimensions = [
                            ['code' => 'S', 'name' => 'Sholeh', 'desc' => 'Taat beribadah, tertib shalat fardhu & dhuha, mengamalkan adab Islami sehari-hari.'],
                            ['code' => 'M', 'name' => 'Muslih', 'desc' => 'Membawa kebaikan, gemar tolong-menolong, peduli sesama, dan mendamaikan lingkungan.'],
                            ['code' => 'I', 'name' => 'Intellectual Curiosity', 'desc' => 'Memiliki rasa ingin tahu tinggi, gemar membaca/literasi, dan semangat menggali ilmu.'],
                            ['code' => 'L', 'name' => 'Leadership', 'desc' => 'Bertanggung jawab, berani memimpin, disiplin, dan amanah dalam menjalankan tugas.'],
                            ['code' => 'E', 'name' => 'Environmentally Friendly', 'desc' => 'Menjaga kebersihan diri, merawat fasilitas kelas, dan mencintai kelestarian lingkungan.'],
                        ];
                    @endphp
                    @foreach($smileDimensions as $idx => $dim)
                        @php
                            $dimAppreciation = $characterAppreciations->first(function($app) use ($dim) {
                                return stripos($app->category, $dim['name']) !== false || stripos($app->title, $dim['name']) !== false;
                            });
                        @endphp
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>
                                <strong>{{ $dim['code'] }} – {{ $dim['name'] }}</strong>
                            </td>
                            <td class="text-center font-bold" style="color: #166534;">
                                {{ $dimAppreciation ? 'A (Sangat Baik)' : 'B (Baik)' }}
                            </td>
                            <td style="font-size: 8.5pt;">
                                @if($dimAppreciation)
                                    <strong>{{ $dimAppreciation->title }}:</strong> {{ $dimAppreciation->description ?? $dim['desc'] }}
                                @else
                                    {{ $dim['desc'] }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- TABLE KEGIATAN EKSTRAKURIKULER -->
            @if($eskuls->count() > 0)
                <div class="section-title">D. Kegiatan Ekstrakurikuler</div>
                <table class="table-data" style="margin-bottom: 12px;">
                    <thead>
                        <tr>
                            <th style="width: 30px;">No</th>
                            <th style="text-align: left; width: 220px;">Kegiatan Ekstrakurikuler</th>
                            <th style="width: 70px;">Predikat</th>
                            <th style="text-align: left;">Keterangan / Perkembangan Siswa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eskuls as $eIdx => $esk)
                            @php $g = $eskulGrades[$esk->id] ?? null; @endphp
                            <tr>
                                <td class="text-center">{{ $eIdx + 1 }}</td>
                                <td><strong>{{ $esk->name }}</strong></td>
                                <td class="text-center font-bold">{{ $g?->score ?? 'A' }}</td>
                                <td style="font-size: 8.5pt;">{{ $g?->description ?? 'Aktif mengikuti kegiatan pembinaan ekstrakurikuler dengan baik dan disiplin.' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- REKAP KETIDAKHADIRAN & CATATAN WALI KELAS -->
            <div style="display: flex; gap: 16px; margin-bottom: 10px;">
                <div style="width: 38%;">
                    <div class="section-title">E. Ketidakhadiran</div>
                    <table class="table-data">
                        <tr>
                            <td style="width: 70%;">Sakit (S)</td>
                            <td class="text-center font-bold" style="width: 30%;">{{ $summary?->sick_count ?? 0 }} hari</td>
                        </tr>
                        <tr>
                            <td>Izin (I)</td>
                            <td class="text-center font-bold">{{ $summary?->permission_count ?? 0 }} hari</td>
                        </tr>
                        <tr>
                            <td>Tanpa Keterangan (A)</td>
                            <td class="text-center font-bold">{{ $summary?->unexcused_count ?? 0 }} hari</td>
                        </tr>
                    </table>
                </div>
                <div style="width: 62%;">
                    <div class="section-title">F. Catatan & Motivasi Wali Kelas</div>
                    <div class="notes-box">
                        {{ $summary?->homeroom_notes ?? 'Pertahankan semangat belajar dan pembiasaan adab mulia ananda baik di sekolah maupun di rumah.' }}
                    </div>
                </div>
            </div>

            <!-- SIGNATURES -->
            <div class="signatures-grid">
                <div class="sig-col">
                    <div>Mengetahui,</div>
                    <div><strong>Orang Tua / Wali Siswa</strong></div>
                    <div class="sig-space"></div>
                    <div>( .................................................. )</div>
                </div>

                <div class="sig-col">
                    <div>Bandar Lampung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                    <div><strong>Wali Kelas {{ $student->class }}</strong></div>
                    <div class="sig-space"></div>
                    <div><strong><u>{{ $homeroomTeacher?->name ?? 'Wali Kelas ' . $student->class }}</u></strong></div>
                    <div style="font-size: 8pt; color: #444;">NIP/NIY: {{ $homeroomTeacher?->nip ?? '-' }}</div>
                </div>

                <div class="sig-col">
                    <div>Mengetahui,</div>
                    <div><strong>Kepala Sekolah SDIT AN NADZIR</strong></div>
                    <div class="sig-space"></div>
                    <div><strong><u>{{ $headmasterName }}</u></strong></div>
                    <div style="font-size: 8pt; color: #444;">NIP/NIY: {{ $headmasterNip }}</div>
                </div>
            </div>
        </div>
    @endforeach

</body>
</html>
