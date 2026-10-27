@php
    $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
    $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
    $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : "Nur'asiah, S.Pd.I");

    $instructorName = !empty($selectedEskul->instructor_name) ? $selectedEskul->instructor_name : (Auth::user()->role === 'teacher' ? Auth::user()->name : 'Pembina Ekstrakurikuler');
    $totalStudents = count($students);
@endphp

<div class="official-sheet-wrapper" style="font-family: 'Times New Roman', Times, Georgia, serif; color: #000000; line-height: 1.25; background: #ffffff;">

    <!-- 1. KOP SURAT RESMI SDIT AN NADZIR -->
    <div class="print-kop-wrapper" style="margin-bottom: 6px;">
        <table style="width: 100%; border: none; margin: 0; border-collapse: collapse;">
            <tr style="border: none;">
                <td style="width: 65px; border: none; vertical-align: middle; padding: 0;">
                    <img src="{{ asset('logo.png') }}" style="height: 60px; width: auto; display: block;" alt="Logo SDIT">
                </td>
                <td style="border: none; text-align: center; vertical-align: middle; padding: 0 10px;">
                    <div style="font-size: 8.5pt; font-weight: bold; color: #333333; text-transform: uppercase; letter-spacing: 1.5px; line-height: 1.15;">
                        YAYASAN PENDIDIKAN ISLAM AN NADZIR
                    </div>
                    <div style="font-size: 17pt; font-weight: 900; color: #064e3b; letter-spacing: 0.5px; line-height: 1.2; margin: 2px 0;">
                        SDIT AN NADZIR
                    </div>
                    <div style="font-size: 7.5pt; font-style: italic; color: #444444; line-height: 1.25;">
                        Bendungan Karet Cisirih, Kp. Cukang RT.09/RW.02, Desa Kamasan, Kec. Cinangka, Serang, Banten 42167
                    </div>
                </td>
                <td style="width: 65px; border: none; padding: 0;">
                    <!-- Spacer penyeimbang simetris -->
                </td>
            </tr>
        </table>
        <div style="border-bottom: 2.5px solid #000000; margin-top: 5px; margin-bottom: 2px;"></div>
        <div style="border-bottom: 0.8px solid #000000; margin-bottom: 8px;"></div>
    </div>

    <!-- 2. JUDUL DOKUMEN & IDENTITAS -->
    <div style="text-align: center; margin-bottom: 8px;">
        <h2 style="font-size: 11pt; font-weight: 900; text-transform: uppercase; text-decoration: underline; margin: 0 0 4px 0; color: #000000; letter-spacing: 0.5px;">
            REKAPITULASI PENILAIAN EKSTRAKURIKULER
        </h2>
        <div style="font-size: 8pt; color: #222222; margin: 0;">
            Kegiatan: <strong style="text-transform: uppercase;">{{ $selectedEskul->name ?? '-' }}</strong> &bull; Total: <strong>{{ $totalStudents }} Siswa</strong>
        </div>
    </div>

    <!-- 3. METADATA SINGKAT -->
    <table style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 6px; font-size: 8pt;">
        <tr style="border: none;">
            <td style="border: none; padding: 1.5px 0; width: 50%;">
                Guru Pembina: <strong>{{ $instructorName }}</strong>
            </td>
            <td style="border: none; padding: 1.5px 0; width: 50%; text-align: right;">
                Model Penilaian: <strong>{{ ($selectedEskul->is_lockable ?? false) ? 'Calistung (3 Aspek)' : 'Reguler (Harian, SAS 1 & 2)' }}</strong>
            </td>
        </tr>
    </table>

    <!-- 4. TABEL NILAI (1 LEMBAR) -->
    <table class="official-print-table" style="width: 100%; border-collapse: collapse; font-size: 8pt; margin-bottom: 6px;">
        <thead>
            <tr style="background: #f1f5f9; color: #000000;">
                <th rowspan="2" style="border: 1px solid #333333; padding: 3px 2px; width: 4%; text-align: center; font-weight: bold;">No</th>
                <th rowspan="2" style="border: 1px solid #333333; padding: 3px 3px; width: 10%; text-align: center; font-weight: bold;">NIS</th>
                <th rowspan="2" style="border: 1px solid #333333; padding: 3px 6px; width: 30%; text-align: left; font-weight: bold;">Nama Siswa</th>
                <th rowspan="2" style="border: 1px solid #333333; padding: 3px 2px; width: 8%; text-align: center; font-weight: bold;">Kelas</th>
                <th rowspan="2" style="border: 1px solid #333333; padding: 3px 4px; width: 14%; text-align: center; font-weight: bold;">Materi / Aspek</th>
                <th colspan="3" style="border: 1px solid #333333; padding: 3px 4px; width: 34%; text-align: center; font-weight: bold;">Capaian Nilai</th>
            </tr>
            <tr style="background: #f1f5f9; color: #000000;">
                <th style="border: 1px solid #333333; padding: 2px 2px; width: 12%; text-align: center; font-weight: bold;">Harian</th>
                <th style="border: 1px solid #333333; padding: 2px 2px; width: 11%; text-align: center; font-weight: bold;">SAS 1</th>
                <th style="border: 1px solid #333333; padding: 2px 2px; width: 11%; text-align: center; font-weight: bold;">SAS 2</th>
            </tr>
        </thead>
        <tbody>
            @php
                $isCalistung = $selectedEskul->is_lockable ?? false;
            @endphp

            @forelse($students as $index => $student)
            @php
                $studentGrades = $grades[$student->id] ?? collect([]);
                $dailyGrades = $studentGrades->where('type', 'daily');
                $sas1Raw = $studentGrades->where('type', 'sas1')->first()->score ?? null;
                $sas2Raw = $studentGrades->where('type', 'sas2')->first()->score ?? null;
                
                $dailyScore = '-';
                $dailyJson = null;
                if ($dailyGrades->isNotEmpty()) {
                    $latest = $dailyGrades->sortByDesc('date')->first();
                    $dailyScore = $latest->score;
                    $dailyJson = json_decode($dailyScore, true);
                }
                
                $sas1Json = json_decode($sas1Raw, true);
                $sas2Json = json_decode($sas2Raw, true);

                $isStudentCalistung = $isCalistung && \Illuminate\Support\Str::startsWith($student->class, '1');
            @endphp

            @if($isStudentCalistung)
                <!-- Calistung 3 Rows: Membaca, Menulis, Menghitung -->
                <tr style="{{ $index % 2 == 1 ? 'background: #fafafa;' : 'background: #ffffff;' }}">
                    <td rowspan="3" style="border: 1px solid #333333; padding: 2px 2px; text-align: center;">{{ $index + 1 }}</td>
                    <td rowspan="3" style="border: 1px solid #333333; padding: 2px 3px; text-align: center; font-weight: 600; color: #334155;">{{ $student->nis ?? '-' }}</td>
                    <td rowspan="3" style="border: 1px solid #333333; padding: 2px 6px; text-align: left; font-weight: bold;">{{ strtoupper($student->name) }}</td>
                    <td rowspan="3" style="border: 1px solid #333333; padding: 2px 2px; text-align: center;">{{ $student->class }}</td>
                    <td style="border: 1px solid #333333; padding: 2px 4px; text-align: center; font-weight: bold;">Membaca</td>
                    <td style="border: 1px solid #333333; padding: 2px 2px; text-align: center; font-weight: bold;">{{ is_array($dailyJson) ? ($dailyJson['reading'] ?? '-') : '-' }}</td>
                    <td style="border: 1px solid #333333; padding: 2px 2px; text-align: center;">{{ is_array($sas1Json) ? ($sas1Json['reading'] ?? '-') : '-' }}</td>
                    <td style="border: 1px solid #333333; padding: 2px 2px; text-align: center;">{{ is_array($sas2Json) ? ($sas2Json['reading'] ?? '-') : '-' }}</td>
                </tr>
                <tr style="{{ $index % 2 == 1 ? 'background: #fafafa;' : 'background: #ffffff;' }}">
                    <td style="border: 1px solid #333333; padding: 2px 4px; text-align: center; font-weight: bold;">Menulis</td>
                    <td style="border: 1px solid #333333; padding: 2px 2px; text-align: center; font-weight: bold;">{{ is_array($dailyJson) ? ($dailyJson['writing'] ?? '-') : '-' }}</td>
                    <td style="border: 1px solid #333333; padding: 2px 2px; text-align: center;">{{ is_array($sas1Json) ? ($sas1Json['writing'] ?? '-') : '-' }}</td>
                    <td style="border: 1px solid #333333; padding: 2px 2px; text-align: center;">{{ is_array($sas2Json) ? ($sas2Json['writing'] ?? '-') : '-' }}</td>
                </tr>
                <tr style="{{ $index % 2 == 1 ? 'background: #fafafa;' : 'background: #ffffff;' }}">
                    <td style="border: 1px solid #333333; padding: 2px 4px; text-align: center; font-weight: bold;">Menghitung</td>
                    <td style="border: 1px solid #333333; padding: 2px 2px; text-align: center; font-weight: bold;">{{ is_array($dailyJson) ? ($dailyJson['counting'] ?? '-') : '-' }}</td>
                    <td style="border: 1px solid #333333; padding: 2px 2px; text-align: center;">{{ is_array($sas1Json) ? ($sas1Json['counting'] ?? '-') : '-' }}</td>
                    <td style="border: 1px solid #333333; padding: 2px 2px; text-align: center;">{{ is_array($sas2Json) ? ($sas2Json['counting'] ?? '-') : '-' }}</td>
                </tr>
            @else
                <!-- Regular Row -->
                <tr style="{{ $index % 2 == 1 ? 'background: #fafafa;' : 'background: #ffffff;' }}">
                    <td style="border: 1px solid #333333; padding: 3px 2px; text-align: center;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #333333; padding: 3px 3px; text-align: center; font-weight: 600; color: #334155;">{{ $student->nis ?? '-' }}</td>
                    <td style="border: 1px solid #333333; padding: 3px 6px; text-align: left; font-weight: bold;">{{ strtoupper($student->name) }}</td>
                    <td style="border: 1px solid #333333; padding: 3px 2px; text-align: center;">{{ $student->class }}</td>
                    <td style="border: 1px solid #333333; padding: 3px 4px; text-align: center; color: #555;">Umum</td>
                    <td style="border: 1px solid #333333; padding: 3px 2px; text-align: center; font-weight: bold;">{{ $dailyScore }}</td>
                    <td style="border: 1px solid #333333; padding: 3px 2px; text-align: center;">{{ $sas1Raw ?? '-' }}</td>
                    <td style="border: 1px solid #333333; padding: 3px 2px; text-align: center;">{{ $sas2Raw ?? '-' }}</td>
                </tr>
            @endif
            @empty
            <tr>
                <td colspan="8" style="border: 1px solid #333333; padding: 12px; text-align: center; color: #666666;">
                    Tidak ada data nilai siswa terdaftar.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 5. FOOTER & TANDA TANGAN RESMI -->
    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 7pt; color: #444444; margin-bottom: 8px;">
        <span>* Nilai SAS 1 (Semester Ganjil) &bull; SAS 2 (Semester Genap)</span>
        <span>Dicetak via SIMUTQIN SDIT AN NADZIR &bull; {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB</span>
    </div>

    <div class="print-signatures-block" style="margin-top: 12px; page-break-inside: avoid;">
        <table style="width: 100%; border: none; border-collapse: collapse; font-size: 8.5pt;">
            <tr style="border: none;">
                <td style="border: none; width: 50%; text-align: center; vertical-align: top; padding: 0 10px;">
                    Mengetahui,<br>
                    <strong>Kepala SDIT AN NADZIR</strong>
                    <br><br><br><br>
                    <strong><u>{{ $headmasterName }}</u></strong>
                </td>
                <td style="border: none; width: 50%; text-align: center; vertical-align: top; padding: 0 10px;">
                    Cinangka, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}<br>
                    <strong>Guru Pembina Ekstrakurikuler</strong>
                    <br><br><br><br>
                    <strong><u>{{ $instructorName }}</u></strong>
                </td>
            </tr>
        </table>
    </div>

</div>
