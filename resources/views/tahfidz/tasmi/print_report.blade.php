<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Tasmi' – {{ $tasmi->student->name }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #0f172a;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
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
            font-family: Arial, sans-serif;
        }
        .btn-print {
            background: #059669;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-close {
            background: #475569;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        .report-sheet {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 15mm 18mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.08);
            position: relative;
        }

        .kop-surat {
            border-bottom: 3px double #047857;
            padding-bottom: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .kop-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        .kop-text {
            flex: 1;
            text-align: center;
        }
        .kop-text h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: 800;
            color: #064e3b;
        }
        .kop-text h1 {
            margin: 2px 0;
            font-size: 18pt;
            font-weight: 900;
            color: #047857;
            letter-spacing: 0.5px;
        }
        .kop-text p {
            margin: 2px 0 0 0;
            font-size: 8.5pt;
            color: #475569;
            font-family: Arial, sans-serif;
        }

        .title-box {
            text-align: center;
            margin-bottom: 18px;
        }
        .title-box h3 {
            margin: 0;
            font-size: 13pt;
            font-weight: 800;
            text-decoration: underline;
            color: #0f172a;
        }
        .title-box p {
            margin: 4px 0 0 0;
            font-size: 9.5pt;
            color: #334155;
            font-family: Arial, sans-serif;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 9.5pt;
        }
        .data-table td {
            padding: 5px 6px;
            vertical-align: top;
        }
        .data-table td.lbl {
            width: 25%;
            font-weight: bold;
            color: #334155;
        }
        .data-table td.sep {
            width: 3%;
            text-align: center;
        }
        .data-table td.val {
            width: 72%;
            color: #0f172a;
        }

        .rubrik-table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 9pt;
            margin-bottom: 16px;
        }
        .rubrik-table th {
            background: #e6f4ea;
            border: 1px solid #059669;
            color: #064e3b;
            padding: 8px 10px;
            text-align: center;
            font-weight: 700;
        }
        .rubrik-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
        }

        .notes-box {
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            border-left: 4px solid #059669;
            border-radius: 4px;
            padding: 10px 14px;
            font-style: italic;
            margin-bottom: 24px;
            font-size: 9.5pt;
            line-height: 1.4;
        }

        .sig-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 20px;
            text-align: center;
            font-size: 9.5pt;
        }

        @media print {
            .no-print-bar { display: none !important; }
            body { background: white !important; }
            .report-sheet {
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div><strong>Berita Acara Ujian Tasmi' Al-Qur'an Bil Ghaib</strong></div>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Cetak Berita Acara</button>
            <button onclick="window.close()" class="btn-close">Tutup</button>
        </div>
    </div>

    <div class="report-sheet">
        <!-- KOP -->
        <div class="kop-surat">
            <img src="{{ asset('logo.png') }}" class="kop-logo" alt="Logo">
            <div class="kop-text">
                <h2>YAYASAN PENDIDIKAN DAN SOSIAL AN NADZIR</h2>
                <h1>SDIT AN NADZIR</h1>
                <p>NPSN: 69949618 · Akreditasi: A · Jl. Raya Pendidikan No. 01, Cisauk, Tangerang, Banten</p>
                <p>Telp: (021) 5432-1098 · Email: info@annadzir.sch.id</p>
            </div>
        </div>

        <!-- TITLE -->
        <div class="title-box">
            <h3>BERITA ACARA PENILAIAN UJIAN TASMI' BIL GHAIB</h3>
            <p>Nomor: {{ date('Y') }}/TASMI/SDIT-AN/{{ str_pad($tasmi->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>

        <p style="margin-bottom: 12px; line-height: 1.5;">
            Pada hari ini, <strong>{{ \Carbon\Carbon::parse($tasmi->exam_date)->translatedFormat('l') }}</strong>
            tanggal <strong>{{ \Carbon\Carbon::parse($tasmi->exam_date)->translatedFormat('d F Y') }}</strong>,
            telah diselenggarakan Ujian Tasmi' Al-Qur'an Sekali Duduk (Bil Ghaib) tanpa melihat mushaf untuk santri/siswa:
        </p>

        <!-- IDENTITAS -->
        <table class="data-table">
            <tr>
                <td class="lbl">Nama Lengkap Siswa</td>
                <td class="sep">:</td>
                <td class="val"><strong>{{ $tasmi->student->name }}</strong></td>
            </tr>
            <tr>
                <td class="lbl">Kelas / NIS</td>
                <td class="sep">:</td>
                <td class="val">{{ $tasmi->student->class }} @if($tasmi->student->nis) / {{ $tasmi->student->nis }} @endif</td>
            </tr>
            <tr>
                <td class="lbl">Halaqah / Musyrif</td>
                <td class="sep">:</td>
                <td class="val">{{ $tasmi->mentoringGroup->name ?? 'Tahfidz Al-Qur\'an' }} ({{ $tasmi->mentoringGroup->mentor_name ?? '-' }})</td>
            </tr>
            <tr>
                <td class="lbl">Kategori Ujian</td>
                <td class="sep">:</td>
                <td class="val"><strong>{{ $tasmi->category_label }} ({{ $tasmi->juz_tested }})</strong></td>
            </tr>
            <tr>
                <td class="lbl">Penguji / Munaqisy</td>
                <td class="sep">:</td>
                <td class="val">{{ $tasmi->examiner_name }}</td>
            </tr>
        </table>

        <!-- RUBRIK KESALAHAN -->
        <div style="font-weight: bold; margin-bottom: 6px; font-family: Arial, sans-serif; font-size: 9.5pt; color: #064e3b;">
            Rincian Kesalahan & Evaluasi Penguji:
        </div>
        <table class="rubrik-table">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 45%; text-align: left;">Kategori Kesalahan</th>
                    <th style="width: 20%;">Jumlah Salah</th>
                    <th style="width: 27%;">Bobot Pengurangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td><strong>Salah Fath (Ditebak / Diingatkan Pembacaan)</strong></td>
                    <td style="text-align: center; font-weight: bold; color: #dc2626;">{{ $tasmi->mistakes_fath }} kali</td>
                    <td style="text-align: center;">-{{ $tasmi->mistakes_fath * 2.0 }} poin (-2.0 / salah)</td>
                </tr>
                <tr>
                    <td style="text-align: center;">2</td>
                    <td><strong>Salah Kaidah Tajwid & Makharijul Huruf</strong></td>
                    <td style="text-align: center; font-weight: bold; color: #d97706;">{{ $tasmi->mistakes_tajwid }} kali</td>
                    <td style="text-align: center;">-{{ $tasmi->mistakes_tajwid * 1.0 }} poin (-1.0 / salah)</td>
                </tr>
                <tr>
                    <td style="text-align: center;">3</td>
                    <td><strong>Salah Waqaf & Ibtida' (Pemberhentian/Permulaan)</strong></td>
                    <td style="text-align: center; font-weight: bold; color: #0284c7;">{{ $tasmi->mistakes_waqaf }} kali</td>
                    <td style="text-align: center;">-{{ $tasmi->mistakes_waqaf * 0.5 }} poin (-0.5 / salah)</td>
                </tr>
                <tr style="background: #f1f5f9; font-weight: bold;">
                    <td colspan="2" style="text-align: right; padding-right: 14px;">TOTAL KESALAHAN & NILAI AKHIR:</td>
                    <td style="text-align: center; font-size: 10.5pt; color: #0f172a;">{{ $tasmi->total_mistakes }} kali</td>
                    <td style="text-align: center; font-size: 11pt; color: #047857;">{{ $tasmi->final_score }} / 100</td>
                </tr>
            </tbody>
        </table>

        <!-- KEPUTUSAN HASIL -->
        <div style="background: {{ $tasmi->status === 'passed' ? '#ecfdf5' : '#fef2f2' }}; border: 1.5px solid {{ $tasmi->status === 'passed' ? '#10b981' : '#ef4444' }}; padding: 10px 16px; border-radius: 6px; margin-bottom: 14px; text-align: center;">
            <div style="font-size: 9pt; color: #475569; text-transform: uppercase; font-family: Arial, sans-serif; font-weight: bold;">Keputusan Dewan Penguji:</div>
            <div style="font-size: 13pt; font-weight: 800; color: {{ $tasmi->status === 'passed' ? '#065f46' : '#b91c1c' }}; margin-top: 2px;">
                {{ $tasmi->status === 'passed' ? 'DINYATAKAN LULUS UJIAN TASMI\' BIL GHAIB' : 'DINYATAKAN PERLU PENGUATAN / UJIAN ULANG' }}
            </div>
            <div style="font-size: 10pt; font-weight: 700; color: #0f766e; margin-top: 2px;">
                Predikat: {{ $tasmi->predicate }}
            </div>
        </div>

        <!-- CATATAN EVALUASI -->
        <div style="font-weight: bold; margin-bottom: 4px; font-family: Arial, sans-serif; font-size: 9pt; color: #064e3b;">
            Catatan & Rekomendasi Penguji:
        </div>
        <div class="notes-box">
            "{{ $tasmi->notes ?: 'Alhamdulillah ananda mampu memperdengarkan hafalan dengan baik dan tartil. Pertahankan hafalan dengan istiqomah muroja\'ah setiap hari.' }}"
        </div>

        <!-- TANDA TANGAN -->
        <div class="sig-grid">
            <div>
                <div>Mengetahui,</div>
                <div style="margin-bottom: 50px;">Kepala SDIT An Nadzir</div>
                <div style="font-weight: bold; text-decoration: underline;">{{ $headmasterName }}</div>
                <div style="font-size: 8.5pt; color: #64748b;">NIP / NPY: 19820415.2010.01</div>
            </div>
            <div>
                <div>Tangerang, {{ \Carbon\Carbon::parse($tasmi->exam_date)->translatedFormat('d F Y') }}</div>
                <div style="margin-bottom: 50px;">Munaqisy / Penguji Tasmi'</div>
                <div style="font-weight: bold; text-decoration: underline;">{{ $tasmi->examiner_name }}</div>
                <div style="font-size: 8.5pt; color: #64748b;">Penguji Tahfidz Bil Ghaib</div>
            </div>
        </div>
    </div>

</body>
</html>
