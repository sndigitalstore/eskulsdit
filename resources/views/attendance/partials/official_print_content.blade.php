@php
    $totalStudents = $students->count();
    $totalSessions = count($recordedDates);
    $sumHadir = $students->sum('h');
    $sumSakit = $students->sum('s');
    $sumIzin = $students->sum('i');
    $sumAlpa = $students->sum('a');
    $totalRecords = $sumHadir + $sumSakit + $sumIzin + $sumAlpa;
    $attendanceRate = $totalRecords > 0 ? round(($sumHadir / $totalRecords) * 100) : 0;

    $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
    $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
    $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : "Nur'asiah, S.Pd.I");

    $instructorName = !empty($eskul->instructor_name) ? $eskul->instructor_name : (Auth::user()->role === 'teacher' ? Auth::user()->name : 'Pembina Ekstrakurikuler');
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

    <!-- 2. JUDUL DOKUMEN & IDENTITAS ESKUL -->
    <div style="text-align: center; margin-bottom: 8px;">
        <h2 style="font-size: 11pt; font-weight: 900; text-transform: uppercase; text-decoration: underline; margin: 0 0 4px 0; color: #000000; letter-spacing: 0.5px;">
            REKAPITULASI PRESENSI EKSTRAKURIKULER
        </h2>
        <div style="font-size: 8pt; color: #222222; margin: 0;">
            Tahun Ajaran <strong>{{ $year->name }}</strong> &bull; Semester <strong>{{ $year->active_semester ?? '1' }} ({{ ($year->active_semester ?? '1') == '1' ? 'Ganjil' : 'Genap' }})</strong>
        </div>
    </div>

    <!-- 3. TABEL INFORMASI METADATA & REKAPITULASI SINGKAT -->
    <table style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 6px; font-size: 8pt;">
        <tr style="border: none;">
            <td style="border: none; padding: 1.5px 0; width: 50%;">
                Kegiatan Eskul: <strong style="text-transform: uppercase;">{{ $eskul->name }}</strong>
            </td>
            <td style="border: none; padding: 1.5px 0; width: 50%; text-align: right;">
                Total Pertemuan: <strong>{{ count($recordedDates) }} Sesi Terlaksana</strong>
            </td>
        </tr>
        <tr style="border: none;">
            <td style="border: none; padding: 1.5px 0;">
                Guru Pembina: <strong>{{ $instructorName }}</strong>
            </td>
            <td style="border: none; padding: 1.5px 0; width: 50%; text-align: right;">
                Peserta: <strong>{{ $totalStudents }} Siswa</strong> &bull; Rata-rata Hadir: <strong>{{ $attendanceRate }}%</strong>
            </td>
        </tr>
    </table>

    <!-- 4. TABEL PRESENSI SISWA STANDAR RESMI (1 LEMBAR) -->
    <table class="official-print-table" style="width: 100%; border-collapse: collapse; font-size: 8pt; margin-bottom: 6px;">
        <thead>
            <tr style="background: #f1f5f9; color: #000000;">
                <th style="border: 1px solid #333333; padding: 4px 3px; width: 5%; text-align: center; font-weight: bold;">No</th>
                <th style="border: 1px solid #333333; padding: 4px 6px; width: 42%; text-align: left; font-weight: bold;">Nama Siswa</th>
                <th style="border: 1px solid #333333; padding: 4px 3px; width: 11%; text-align: center; font-weight: bold;">Kelas</th>
                <th style="border: 1px solid #333333; padding: 4px 3px; width: 8.5%; text-align: center; font-weight: bold;">Hadir (H)</th>
                <th style="border: 1px solid #333333; padding: 4px 3px; width: 8%; text-align: center; font-weight: bold;">Sakit (S)</th>
                <th style="border: 1px solid #333333; padding: 4px 3px; width: 8%; text-align: center; font-weight: bold;">Izin (I)</th>
                <th style="border: 1px solid #333333; padding: 4px 3px; width: 8%; text-align: center; font-weight: bold;">Alpa (A)</th>
                <th style="border: 1px solid #333333; padding: 4px 3px; width: 9.5%; text-align: center; font-weight: bold;">% Hadir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
            @php
                $studentTotal = $student->h + $student->s + $student->i + $student->a;
                $studentRate = $studentTotal > 0 ? round(($student->h / $studentTotal) * 100) : 0;
            @endphp
            <tr style="{{ $index % 2 == 1 ? 'background: #fafafa;' : 'background: #ffffff;' }}">
                <td style="border: 1px solid #333333; padding: 3px 3px; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #333333; padding: 3px 6px; text-align: left; font-weight: bold;">
                    {{ strtoupper($student->name) }}
                </td>
                <td style="border: 1px solid #333333; padding: 3px 3px; text-align: center;">
                    {{ $student->class }}
                </td>
                <td style="border: 1px solid #333333; padding: 3px 3px; text-align: center; font-weight: bold;">
                    {{ $student->h }}
                </td>
                <td style="border: 1px solid #333333; padding: 3px 3px; text-align: center;">
                    {{ $student->s }}
                </td>
                <td style="border: 1px solid #333333; padding: 3px 3px; text-align: center;">
                    {{ $student->i }}
                </td>
                <td style="border: 1px solid #333333; padding: 3px 3px; text-align: center;">
                    {{ $student->a }}
                </td>
                <td style="border: 1px solid #333333; padding: 3px 3px; text-align: center; font-weight: bold;">
                    {{ $studentRate }}%
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="border: 1px solid #333333; padding: 12px; text-align: center; color: #666666;">
                    Tidak ada data siswa terdaftar pada ekstrakurikuler ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($totalStudents > 0)
        <tfoot>
            <tr style="background: #f1f5f9; font-weight: bold;">
                <td colspan="3" style="border: 1px solid #333333; padding: 3.5px 6px; text-align: right;">
                    TOTAL AKUMULASI :
                </td>
                <td style="border: 1px solid #333333; padding: 3.5px 3px; text-align: center;">
                    {{ $sumHadir }}
                </td>
                <td style="border: 1px solid #333333; padding: 3.5px 3px; text-align: center;">
                    {{ $sumSakit }}
                </td>
                <td style="border: 1px solid #333333; padding: 3.5px 3px; text-align: center;">
                    {{ $sumIzin }}
                </td>
                <td style="border: 1px solid #333333; padding: 3.5px 3px; text-align: center;">
                    {{ $sumAlpa }}
                </td>
                <td style="border: 1px solid #333333; padding: 3.5px 3px; text-align: center;">
                    {{ $attendanceRate }}%
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- 5. CATATAN KETERANGAN SINGKAT -->
    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 7pt; color: #444444; margin-bottom: 8px;">
        <span>* Keterangan: H: Hadir &bull; S: Sakit &bull; I: Izin &bull; A: Alpa / Tanpa Keterangan</span>
        <span>Dicetak via SIMUTQIN SDIT AN NADZIR &bull; {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB</span>
    </div>

    <!-- 6. LEMBAR TANDA TANGAN RESMI (2 KOLOM SIMETRIS) -->
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
