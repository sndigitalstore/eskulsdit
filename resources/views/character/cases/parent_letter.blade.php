<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pemberitahuan & Berita Acara Pembinaan Siswa – {{ $case->student->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
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
            padding: 15mm 20mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        .doc-title {
            text-align: center;
            margin: 10px 0 20px;
        }
        .doc-title h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: 800;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .doc-title p {
            margin: 4px 0 0;
            font-size: 9.5pt;
            color: #475569;
        }
        .info-table {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
            font-size: 10.5pt;
        }
        .content-box {
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            border-radius: 8px;
            padding: 14px 18px;
            margin: 14px 0;
        }
        .content-box h4 {
            margin: 0 0 8px 0;
            font-size: 10.5pt;
            color: #0f172a;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 4px;
        }
        .badge-tppk {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 8.5pt;
            font-weight: bold;
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
        .signatures {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
            text-align: center;
        }
        .sig-col {
            width: 30%;
        }
        .sig-space {
            height: 65px;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }
        @media print {
            .no-print-bar { display: none !important; }
            body { background: white !important; }
            .page-container {
                box-shadow: none !important;
                margin: 0 auto !important;
                width: 100% !important;
                padding: 5mm 10mm !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div style="font-weight: bold;">
            <i class="fas fa-file-contract"></i> Dokumen Pembinaan Siswa & Pemanggilan Orang Tua
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak Dokumen (A4)
            </button>
            <button onclick="window.close()" style="background: #475569; color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-weight: bold;">
                Tutup
            </button>
        </div>
    </div>

    <div class="page-container">
        <!-- Official Kop Surat -->
        @include('partials.official_kop')

        <div class="doc-title">
            <h2>BERITA ACARA PEMBINAAN SISWA & PANGGILAN ORANG TUA</h2>
            <p>Nomor: BA-SMILE/{{ date('Y') }}/{{ str_pad($case->id, 4, '0', STR_PAD_LEFT) }} &bull; Unit Kesiswaan & Bimbingan Konseling</p>
        </div>

        <p style="text-align: justify; line-height: 1.6; margin-bottom: 12px;">
            Pada hari ini, <strong>{{ \Carbon\Carbon::parse($case->date)->translatedFormat('l, d F Y') }}</strong>, telah dilaksanakan pendataan dan pembinaan karakter terhadap siswa SDIT AN NADZIR dengan rincian identitas sebagai berikut:
        </p>

        <table class="info-table">
            <tr>
                <td width="28%"><strong>Nama Lengkap Siswa</strong></td>
                <td width="2%">:</td>
                <td width="70%"><strong>{{ $case->student->name }}</strong></td>
            </tr>
            <tr>
                <td><strong>Nomor Induk Siswa (NIS)</strong></td>
                <td>:</td>
                <td>{{ $case->student->nis ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Kelas / Rombel</strong></td>
                <td>:</td>
                <td>Kelas {{ $case->student->class }}</td>
            </tr>
            <tr>
                <td><strong>Tahun Pelajaran</strong></td>
                <td>:</td>
                <td>{{ $case->academicYear->name ?? '2026/2027' }}</td>
            </tr>
            <tr>
                <td><strong>Nama Orang Tua / Wali</strong></td>
                <td>:</td>
                <td>{{ $case->student->parent_name ?? 'Bapak / Ibu Wali Murid' }}</td>
            </tr>
            <tr>
                <td><strong>No. Telp / HP Orang Tua</strong></td>
                <td>:</td>
                <td>{{ $case->student->parent_phone ?? '-' }}</td>
            </tr>
        </table>

        <div class="content-box">
            <h4>1. Klasifikasi & Uraian Masalah</h4>
            <div style="margin-bottom: 6px;">
                <strong>Kategori Pembinaan:</strong> {{ $case->category }} &bull; 
                <strong>Tingkat:</strong> <span style="text-transform: capitalize; font-weight: bold;">{{ $case->severity }} (-{{ $case->point }} poin)</span>
                @if($case->tppk_category && $case->tppk_category !== 'non_tppk')
                    <span class="badge-tppk" style="margin-left: 8px;">
                        <i class="fas fa-shield-alt"></i> {{ $case->tppk_badge['label'] }}
                    </span>
                @endif
            </div>
            <div style="margin-bottom: 6px;">
                <strong>Ringkasan Kejadian:</strong> {{ $case->title }} (Lokasi: {{ $case->location ?: 'Lingkungan Sekolah' }})
            </div>
            <div>
                <strong>Kronologi / Keterangan:</strong><br>
                <span style="font-style: italic; color: #334155;">{{ $case->description ?: 'Telah dilakukan verifikasi perilaku yang tidak sesuai dengan adab dan tata tertib sekolah.' }}</span>
            </div>
        </div>

        <div class="content-box">
            <h4>2. Tindakan Pembinaan & Aksi Restorasi Karakter (Disiplin Positif)</h4>
            <div style="margin-bottom: 6px;">
                <strong>Tindakan Langsung Sekolah:</strong> {{ $case->action_taken ?: 'Nasihat edukatif dan pengingat adab mulia' }}
            </div>
            <div style="margin-bottom: 6px;">
                <strong>Aksi Restorasi / Konsekuensi Edukatif:</strong><br>
                <span style="color: #065f46; font-weight: 600;">
                    {{ $case->restorative_action ?: 'Melaksanakan pembiasaan adab baik, tilawah Al-Qur\'an, dan komitmen tidak mengulangi perilaku serupa.' }}
                </span>
            </div>
            <div>
                <strong>Tingkat Penanganan:</strong> {{ $case->handling_level_badge['label'] }} (Pelapor/Pembina: {{ $case->reported_by ?: 'Guru / Wali Kelas' }})
            </div>
        </div>

        <div class="content-box">
            <h4>3. Kesepakatan & Komitmen Tindak Lanjut</h4>
            <div style="margin-bottom: 6px;">
                <strong>Komitmen Siswa:</strong> {{ $case->follow_up ?: 'Siswa bersedia memperbaiki sikap dan mematuhi tata tertib berkarakter SMILE.' }}
            </div>
            <div>
                <strong>Catatan Kerjasama Orang Tua:</strong> {{ $case->notes ?: 'Diharapkan pendampingan bersama di rumah agar pembiasaan karakter berjalan konsisten.' }}
            </div>
        </div>

        <p style="font-size: 10pt; line-height: 1.5; margin-top: 15px;">
            Demikian berita acara ini dibuat secara sadar, objektif, dan penuh rasa tanggung jawab demi kebaikan tumbuh kembang akhlak peserta didik.
        </p>

        <!-- Signatures Section -->
        <div class="signatures">
            <div class="sig-col">
                <p>Orang Tua / Wali Murid,</p>
                <div class="sig-space"></div>
                <p class="sig-name">( {{ $case->student->parent_name ?: '.........................................' }} )</p>
            </div>
            <div class="sig-col">
                <p>Guru BK / Wali Kelas,</p>
                <div class="sig-space"></div>
                <p class="sig-name">{{ $case->reported_by ?: 'Wali Kelas' }}</p>
            </div>
            <div class="sig-col">
                <p>Mengetahui,<br><b>Kepala SDIT AN NADZIR</b></p>
                <div class="sig-space"></div>
                <p class="sig-name">{{ $headmasterName }}</p>
            </div>
        </div>

    </div>

</body>
</html>
