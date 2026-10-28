<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SK Pembina Ekstrakurikuler - SDIT AN NADZIR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm 15mm 20mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000000;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
            line-height: 1.35;
            -webkit-print-color-adjust: exact;
        }

        /* Top Action Bar (No Print) */
        .no-print-bar {
            background: #0f172a;
            color: white;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            position: sticky;
            top: 0;
            z-index: 9999;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .bar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .bar-left i {
            font-size: 1.3rem;
            color: #10b981;
        }

        .bar-title {
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .bar-subtitle {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .bar-inputs {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .input-group-sk {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.08);
            padding: 4px 10px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.15);
        }

        .input-group-sk label {
            font-size: 0.74rem;
            color: #cbd5e1;
            font-weight: 600;
        }

        .input-group-sk input {
            background: #ffffff;
            color: #0f172a;
            border: none;
            padding: 5px 8px;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: 600;
            font-family: inherit;
        }

        .bar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print-now {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
            transition: all 0.2s ease;
        }

        .btn-print-now:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
        }

        .btn-close-preview {
            background: #334155;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-close-preview:hover {
            background: #475569;
        }

        /* Printable Sheets */
        .sk-sheet {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            margin: 25px auto;
            padding: 15mm 20mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            box-sizing: border-box;
            position: relative;
        }

        /* Official Letterhead (Kop Surat) */
        .kop-surat {
            border-bottom: 3px double #000000;
            padding-bottom: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .kop-logo {
            width: 72px;
            height: auto;
            flex-shrink: 0;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text .instansi-yayasan {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .kop-text .instansi-sekolah {
            font-size: 17pt;
            font-weight: bold;
            margin: 2px 0 0 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .kop-text .instansi-alamat {
            font-size: 8.5pt;
            margin: 3px 0 0 0;
            line-height: 1.3;
        }

        /* Document Title */
        .sk-title-box {
            text-align: center;
            margin-bottom: 14px;
        }

        .sk-title-box h3 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .sk-title-box .sk-number {
            font-size: 10.5pt;
            margin: 3px 0 0 0;
            font-weight: bold;
        }

        .sk-title-box .sk-about {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 6px 0 0 0;
            line-height: 1.3;
        }

        .sk-headmaster-title {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin: 12px 0;
            text-transform: uppercase;
        }

        /* Considerations Table (Menimbang & Mengingat) */
        .consideration-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10pt;
        }

        .consideration-table td {
            vertical-align: top;
            padding: 2.5px 0;
            line-height: 1.35;
        }

        .label-col {
            width: 15%;
            font-weight: bold;
        }

        .colon-col {
            width: 3%;
            text-align: center;
        }

        .num-col {
            width: 4%;
            vertical-align: top;
            padding-left: 2px;
        }

        .text-col {
            width: 78%;
            text-align: justify;
        }

        /* Decisions (Memutuskan) */
        .decision-title {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin: 10px 0 8px 0;
            letter-spacing: 1px;
        }

        .decision-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 15px;
        }

        .decision-table td {
            vertical-align: top;
            padding: 3px 0;
            line-height: 1.35;
        }

        .dictum-label {
            width: 18%;
            font-weight: bold;
        }

        .dictum-colon {
            width: 3%;
            text-align: center;
        }

        .dictum-text {
            width: 79%;
            text-align: justify;
        }

        .dictum-sublist {
            margin: 3px 0 0 0;
            padding-left: 18px;
        }

        .dictum-sublist li {
            margin-bottom: 2px;
            text-align: justify;
        }

        /* Signature Section */
        .signature-wrapper {
            width: 100%;
            margin-top: 15px;
            display: flex;
            justify-content: flex-end;
            font-size: 10.5pt;
        }

        .signature-box {
            width: 250px;
            text-align: left;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .tembusan-box {
            margin-top: 15px;
            font-size: 8.5pt;
            border-top: 1px dashed #999;
            padding-top: 8px;
            line-height: 1.35;
        }

        .tembusan-box ol {
            margin: 2px 0 0 0;
            padding-left: 18px;
        }

        /* Annex (Lampiran) Sheet */
        .annex-header {
            margin-bottom: 14px;
            font-size: 9.5pt;
            line-height: 1.3;
        }

        .annex-header-table {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 1.5px solid #000;
            padding-bottom: 8px;
        }

        .annex-header-table td {
            vertical-align: top;
            padding: 1.5px 0;
        }

        .annex-title {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin: 12px 0 14px 0;
            text-transform: uppercase;
            line-height: 1.35;
        }

        /* Table inside Annex */
        .annex-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-top: 10px;
        }

        .annex-table th, .annex-table td {
            border: 1px solid #000000;
            padding: 6px 8px;
            vertical-align: middle;
        }

        .annex-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
            text-transform: uppercase;
        }

        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }

        /* Print Media Styles */
        @media print {
            body {
                background: white;
            }

            .no-print-bar {
                display: none !important;
            }

            .sk-sheet {
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
                min-height: auto;
                page-break-after: always;
            }

            .sk-sheet:last-child {
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>

    {{-- Interactive Control Bar --}}
    <div class="no-print-bar">
        <div class="bar-left">
            <i class="fas fa-stamp"></i>
            <div>
                <div class="bar-title">Pratinjau Surat Keputusan (SK) Pembina Ekstrakurikuler</div>
                <div class="bar-subtitle">SDIT AN NADZIR — Tahun Pelajaran {{ $activeYear->name ?? '2026/2027' }}</div>
            </div>
        </div>

        <div class="bar-inputs">
            <div class="input-group-sk">
                <label for="skNumberInput">No. SK:</label>
                <input type="text" id="skNumberInput" value="421.2/019/SK-EKS/SDIT-AN/VII/{{ date('Y') }}" style="width: 250px;">
            </div>
            <div class="input-group-sk">
                <label for="skDateInput">Tgl SK:</label>
                <input type="text" id="skDateInput" value="14 Juli {{ date('Y') }}" style="width: 120px;">
            </div>
        </div>

        <div class="bar-actions">
            <button type="button" onclick="window.print()" class="btn-print-now">
                <i class="fas fa-print"></i> Cetak SK (PDF/Printer)
            </button>
            <button type="button" onclick="window.close()" class="btn-close-preview">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
    </div>

    {{-- LEMBAR 1: SURAT KEPUTUSAN KEPALA SEKOLAH --}}
    <div class="sk-sheet">
        <!-- KOP SURAT RESMI (Sesuai Dokumen Resmi Sekolah) -->
        @include('partials.official_kop')

        <!-- JUDUL SURAT KEPUTUSAN -->
        <div class="sk-title-box">
            <h3>SURAT KEPUTUSAN</h3>
            <div class="sk-number">Nomor : <span class="render-sk-number">421.2/019/SK-EKS/SDIT-AN/VII/{{ date('Y') }}</span></div>
            <div class="sk-about">
                TENTANG<br>
                PENETAPAN PEMBINA DAN PELATIH KEGIATAN EKSTRAKURIKULER<br>
                TAHUN PELAJARAN {{ $activeYear->name ?? '2026/2027' }}
            </div>
        </div>

        <div class="sk-headmaster-title">
            KEPALA SEKOLAH DASAR ISLAM TERPADU AN NADZIR
        </div>

        <!-- KONSIDERANS -->
        <table class="consideration-table">
            <tr>
                <td class="label-col">Menimbang</td>
                <td class="colon-col">:</td>
                <td class="num-col">a.</td>
                <td class="text-col">bahwa dalam rangka mengembangkan potensi, bakat, minat, kemampuan, kepribadian, dan kemandirian peserta didik di SDIT An Nadzir, perlu diselenggarakan kegiatan ekstrakurikuler yang bermutu dan berkesinambungan;</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="num-col">b.</td>
                <td class="text-col">bahwa untuk kelancaran, keteraturan, dan efektivitas pelaksanaan pembinaan kegiatan ekstrakurikuler, dipandang perlu menetapkan guru/pelatih sebagai Pembina Ekstrakurikuler;</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="num-col">c.</td>
                <td class="text-col">bahwa tenaga pendidik dan pelatih yang namanya tercantum dalam lampiran surat keputusan ini dinilai cakap, amanah, dan memenuhi syarat untuk melaksanakan tugas pembinaan tersebut.</td>
            </tr>
            <tr>
                <td class="label-col" style="padding-top: 6px;">Mengingat</td>
                <td class="colon-col" style="padding-top: 6px;">:</td>
                <td class="num-col" style="padding-top: 6px;">1.</td>
                <td class="text-col" style="padding-top: 6px;">Undang-Undang Nomor 20 Tahun 2003 tentang Sistem Pendidikan Nasional;</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="num-col">2.</td>
                <td class="text-col">Peraturan Pemerintah Nomor 19 Tahun 2005 tentang Standar Nasional Pendidikan sebagaimana telah diubah terakhir dengan Peraturan Pemerintah Nomor 57 Tahun 2021;</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="num-col">3.</td>
                <td class="text-col">Peraturan Menteri Pendidikan dan Kebudayaan Republik Indonesia Nomor 62 Tahun 2014 tentang Kegiatan Ekstrakurikuler pada Pendidikan Dasar dan Pendidikan Menengah;</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="num-col">4.</td>
                <td class="text-col">Kurikulum Operasional Satuan Pendidikan (KOSP) dan Rencana Kerja Sekolah SDIT An Nadzir Tahun Pelajaran {{ $activeYear->name ?? '2026/2027' }};</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="num-col">5.</td>
                <td class="text-col">Hasil Rapat Dewan Guru dan Manajemen SDIT An Nadzir tanggal 10 Juli {{ date('Y') }}.</td>
            </tr>
        </table>

        <!-- DIKTUM KEPUTUSAN -->
        <div class="decision-title">MEMUTUSKAN:</div>

        <table class="decision-table">
            <tr>
                <td class="dictum-label">Menetapkan</td>
                <td class="dictum-colon">:</td>
                <td class="dictum-text"><strong>KEPUTUSAN KEPALA SDIT AN NADZIR TENTANG PENETAPAN PEMBINA DAN PELATIH KEGIATAN EKSTRAKURIKULER TAHUN PELAJARAN {{ $activeYear->name ?? '2026/2027' }}.</strong></td>
            </tr>
            <tr>
                <td class="dictum-label">KESATU</td>
                <td class="dictum-colon">:</td>
                <td class="dictum-text">Mengangkat dan menugaskan guru/pelatih yang namanya tercantum dalam Lampiran Surat Keputusan ini sebagai <strong>Pembina dan Pelatih Kegiatan Ekstrakurikuler</strong> SDIT An Nadzir Tahun Pelajaran {{ $activeYear->name ?? '2026/2027' }}.</td>
            </tr>
            <tr>
                <td class="dictum-label">KEDUA</td>
                <td class="dictum-colon">:</td>
                <td class="dictum-text">
                    Pembina dan Pelatih Ekstrakurikuler mempunyai tugas dan wewenang:
                    <ol class="dictum-sublist">
                        <li>Menyusun program kerja, silabus pelatihan, dan rencana kegiatan pembinaan selama satu tahun pelajaran.</li>
                        <li>Melaksanakan bimbingan teknis dan pembinaan adab/karakter secara disiplin sesuai jadwal mingguan yang ditentukan.</li>
                        <li>Mencatat kehadiran (presensi) siswa dan menginput evaluasi capaian nilai siswa (Harian &amp; SAS) secara berkala pada sistem <strong>SIMUTQIN</strong>.</li>
                        <li>Mempersiapkan dan mendampingi peserta didik dalam mengikuti perlombaan/ajang prestasi.</li>
                        <li>Menyampaikan laporan evaluasi berkala pelaksanaan kegiatan kepada Kepala Sekolah melalui Waka Kesiswaan.</li>
                    </ol>
                </td>
            </tr>
            <tr>
                <td class="dictum-label">KETIGA</td>
                <td class="dictum-colon">:</td>
                <td class="dictum-text">Segala biaya yang timbul sebagai akibat pelaksanaan keputusan ini dibebankan pada Anggaran Pendapatan dan Belanja Sekolah (RAPBS) SDIT An Nadzir yang sesuai.</td>
            </tr>
            <tr>
                <td class="dictum-label">KEEMPAT</td>
                <td class="dictum-colon">:</td>
                <td class="dictum-text">Keputusan ini berlaku sejak tanggal ditetapkan, dan apabila terdapat kekeliruan dalam keputusan ini akan diadakan perbaikan sebagaimana mestinya.</td>
            </tr>
        </table>

        <!-- TANDA TANGAN -->
        <div class="signature-wrapper">
            <div class="signature-box">
                <div>Ditetapkan di : Cinangka, Serang</div>
                <div>Pada tanggal : <span class="render-sk-date">14 Juli {{ date('Y') }}</span></div>
                <div style="font-weight: bold; margin-top: 4px;">Kepala SDIT An Nadzir,</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $headmasterName }}</div>
                <div style="font-size: 9pt; color: #333;">NIP/NUPTK: -</div>
            </div>
        </div>

        <!-- TEMBUSAN -->
        <div class="tembusan-box">
            <strong>Tembusan disampaikan kepada Yth:</strong>
            <ol>
                <li>Ketua Yayasan An Nadzir</li>
                <li>Pengawas Pembina Dinas Pendidikan Kec. Cinangka</li>
                <li>Wakil Kepala Sekolah Bidang Kesiswaan &amp; Kurikulum</li>
                <li>Masing-masing Pembina/Pelatih yang bersangkutan untuk dilaksanakan</li>
                <li>Arsip / Dokumen Akreditasi Sekolah</li>
            </ol>
        </div>
    </div>

    {{-- LEMBAR 2: LAMPIRAN PEMBAGIAN TUGAS --}}
    <div class="sk-sheet">
        <!-- HEADER LAMPIRAN -->
        <table class="annex-header-table">
            <tr>
                <td style="width: 14%; font-weight: bold;">Lampiran</td>
                <td style="width: 2%;">:</td>
                <td style="width: 84%;">Surat Keputusan Kepala SDIT An Nadzir</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Nomor</td>
                <td>:</td>
                <td><span class="render-sk-number">421.2/019/SK-EKS/SDIT-AN/VII/{{ date('Y') }}</span></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Tanggal</td>
                <td>:</td>
                <td><span class="render-sk-date">14 Juli {{ date('Y') }}</span></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Tentang</td>
                <td>:</td>
                <td>Penetapan Pembina dan Pelatih Kegiatan Ekstrakurikuler Tahun Pelajaran {{ $activeYear->name ?? '2026/2027' }}</td>
            </tr>
        </table>

        <div class="annex-title">
            SUSUNAN PEMBINA DAN PELATIH KEGIATAN EKSTRAKURIKULER<br>
            SDIT AN NADZIR TAHUN PELAJARAN {{ $activeYear->name ?? '2026/2027' }}
        </div>

        <!-- TABEL PEMBINA -->
        <table class="annex-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 26%;">Nama Pembina / Pelatih</th>
                    <th style="width: 22%;">Bidang Ekstrakurikuler</th>
                    <th style="width: 17%;">Sasaran Peserta</th>
                    <th style="width: 18%;">Jadwal Latihan</th>
                    <th style="width: 12%;">Tugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $idx => $assign)
                <tr>
                    <td class="text-center font-bold">{{ $idx + 1 }}</td>
                    <td class="font-bold">{{ $assign['name'] }}</td>
                    <td>{{ $assign['eskul'] }}</td>
                    <td class="text-center">{{ $assign['target'] }}</td>
                    <td>{{ $assign['schedule'] }}</td>
                    <td class="text-center">{{ $assign['role'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; font-style: italic; color: #777;">
                        Belum ada data eskul / pembina aktif pada tahun ajaran ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- TANDA TANGAN LAMPIRAN -->
        <div class="signature-wrapper" style="margin-top: 30px;">
            <div class="signature-box">
                <div>Cinangka, <span class="render-sk-date">14 Juli {{ date('Y') }}</span></div>
                <div style="font-weight: bold; margin-top: 4px;">Kepala SDIT An Nadzir,</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $headmasterName }}</div>
                <div style="font-size: 9pt; color: #333;">NIP/NUPTK: -</div>
            </div>
        </div>
    </div>

    {{-- Interactive Live Synchronization Script --}}
    <script>
        const numberInput = document.getElementById('skNumberInput');
        const dateInput = document.getElementById('skDateInput');

        function syncSkValues() {
            const num = numberInput.value || '-';
            const dt = dateInput.value || '-';

            document.querySelectorAll('.render-sk-number').forEach(el => el.textContent = num);
            document.querySelectorAll('.render-sk-date').forEach(el => el.textContent = dt);
        }

        numberInput.addEventListener('input', syncSkValues);
        dateInput.addEventListener('input', syncSkValues);
    </script>
</body>
</html>
