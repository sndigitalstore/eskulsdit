<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syahadah Tasmi' – {{ $tasmi->student->name }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Cinzel:wght@600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background: #cbd5e1;
            color: #0f172a;
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
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .btn-print {
            background: #f59e0b;
            color: #78350f;
            border: none;
            padding: 9px 22px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.92rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-close {
            background: #334155;
            color: white;
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        /* Certificate Container */
        .cert-wrapper {
            width: 297mm;
            height: 210mm;
            margin: 20px auto;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 12mm;
            position: relative;
            overflow: hidden;
            background-image: radial-gradient(#f0fdf4 15%, transparent 16%);
            background-size: 16px 16px;
        }

        /* Outer Double Golden Border */
        .cert-border-outer {
            width: 100%;
            height: 100%;
            border: 4px solid #b45309;
            padding: 4px;
            position: relative;
        }
        .cert-border-inner {
            width: 100%;
            height: 100%;
            border: 2px solid #d97706;
            outline: 1px solid #fef3c7;
            padding: 16px 30px;
            position: relative;
            background: rgba(255, 255, 255, 0.97);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Corner Islamic Motifs */
        .corner {
            position: absolute;
            width: 48px;
            height: 48px;
            border: 3px solid #b45309;
        }
        .corner-tl { top: 0; left: 0; border-right: none; border-bottom: none; }
        .corner-tr { top: 0; right: 0; border-left: none; border-bottom: none; }
        .corner-bl { bottom: 0; left: 0; border-right: none; border-top: none; }
        .corner-br { bottom: 0; right: 0; border-left: none; border-top: none; }

        .arabic-basmalah {
            font-family: 'Amiri', serif;
            font-size: 24pt;
            color: #065f46;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .cert-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-bottom: 6px;
        }
        .cert-header img {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }
        .instansi-title {
            font-family: 'Cinzel', serif;
            font-size: 14pt;
            font-weight: 800;
            color: #064e3b;
            letter-spacing: 1px;
            margin: 0;
        }
        .instansi-sub {
            font-size: 8pt;
            color: #475569;
            margin: 1px 0 0 0;
            letter-spacing: 0.5px;
        }

        .cert-title-box {
            margin: 8px 0;
        }
        .cert-syahadah-title {
            font-family: 'Cinzel', serif;
            font-size: 22pt;
            font-weight: 800;
            color: #b45309;
            letter-spacing: 2px;
            margin: 0;
            text-shadow: 1px 1px 0 rgba(0,0,0,0.05);
        }
        .cert-syahadah-sub {
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #047857;
            letter-spacing: 3px;
            margin-top: 2px;
        }

        .cert-recipient-intro {
            font-size: 9.5pt;
            color: #475569;
            margin: 6px 0 2px 0;
            font-style: italic;
        }
        .cert-name {
            font-family: 'Cinzel', serif;
            font-size: 20pt;
            font-weight: 800;
            color: #064e3b;
            border-bottom: 2px solid #b45309;
            display: inline-block;
            padding: 0 35px 4px 35px;
            margin: 4px 0 2px 0;
            letter-spacing: 0.5px;
        }
        .cert-meta {
            font-size: 9.5pt;
            color: #334155;
            font-weight: 600;
            margin-top: 2px;
        }

        .cert-desc {
            font-size: 9.5pt;
            color: #1e293b;
            line-height: 1.5;
            max-width: 820px;
            margin: 8px auto 10px auto;
        }
        .cert-desc strong {
            color: #065f46;
        }

        .cert-grade-box {
            display: inline-flex;
            align-items: center;
            gap: 18px;
            background: #fefce8;
            border: 1.5px solid #fde047;
            padding: 6px 24px;
            border-radius: 30px;
            margin-bottom: 12px;
        }
        .grade-item {
            font-size: 9pt;
            color: #854d0e;
            font-weight: 600;
        }
        .grade-item strong {
            color: #b45309;
            font-size: 10.5pt;
        }

        /* Signatures Area */
        .signatures-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 6px;
            font-size: 9pt;
            text-align: center;
        }
        .sig-title {
            color: #64748b;
            font-size: 8.5pt;
            margin-bottom: 45px;
        }
        .sig-name {
            font-weight: 800;
            color: #0f172a;
            border-bottom: 1px solid #334155;
            display: inline-block;
            padding-bottom: 2px;
            min-width: 170px;
        }
        .sig-role {
            font-size: 8pt;
            color: #64748b;
            margin-top: 2px;
        }

        @media print {
            .no-print-bar { display: none !important; }
            body { background: white !important; }
            .cert-wrapper {
                margin: 0 !important;
                box-shadow: none !important;
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-award" style="color: #f59e0b; font-size: 1.2rem;"></i>
            <strong>Syahadah Kelulusan Ujian Tasmi' Bil Ghaib</strong>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak Syahadah (PDF)
            </button>
            <button onclick="window.close()" class="btn-close">Tutup</button>
        </div>
    </div>

    <div class="cert-wrapper">
        <div class="cert-border-outer">
            <div class="corner corner-tl"></div>
            <div class="corner corner-tr"></div>
            <div class="corner corner-bl"></div>
            <div class="corner corner-br"></div>

            <div class="cert-border-inner">

                <!-- TOP INSTANSI -->
                <div>
                    <div class="arabic-basmalah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>
                    <div class="cert-header">
                        <img src="{{ asset('logo.png') }}" alt="Logo SDIT AN NADZIR">
                        <div>
                            <h2 class="instansi-title">SDIT AN NADZIR</h2>
                            <p class="instansi-sub">LEMBAGA PENDIDIKAN DASAR ISLAM TERPADU · PENJAMINAN MUTU AL-QUR'AN</p>
                        </div>
                    </div>
                </div>

                <!-- TITLE -->
                <div class="cert-title-box">
                    <h1 class="cert-syahadah-title">SYAHADAH TASMI' AL-QUR'AN</h1>
                    <div class="cert-syahadah-sub">SERTIFIKAT KELULUSAN UJIAN SEKALI DUDUK (BIL GHAIB)</div>
                </div>

                <!-- RECIPIENT -->
                <div>
                    <div class="cert-recipient-intro">Diberikan dengan penuh rasa syukur dan bangga kepada:</div>
                    <div class="cert-name">{{ strtoupper($tasmi->student->name) }}</div>
                    <div class="cert-meta">
                        Kelas {{ $tasmi->student->class }}
                        @if($tasmi->student->nis) · Nomor Induk Siswa (NIS): {{ $tasmi->student->nis }} @endif
                    </div>
                </div>

                <!-- BODY TEXT -->
                <div>
                    <div class="cert-desc">
                        Telah berhasil menyelesaikan dan memperdengarkan hafalan Al-Qur'an secara sempurna dalam <strong>Ujian Sekali Duduk (Tasmi' Bil Ghaib)</strong>
                        kategori <strong>{{ $tasmi->category_label }}</strong> pada <strong>{{ $tasmi->juz_tested }}</strong> tanpa melihat mushaf,
                        dengan memenuhi kaidah ahkamut tajwid, fashahah makharijul huruf, serta kelancaran hifdz yang mutqin.
                    </div>

                    <div class="cert-grade-box">
                        <div class="grade-item">Kategori: <strong>{{ $tasmi->category_label }}</strong></div>
                        <div class="grade-item">·</div>
                        <div class="grade-item">Nilai Akhir: <strong>{{ $tasmi->final_score }}</strong></div>
                        <div class="grade-item">·</div>
                        <div class="grade-item">Predikat: <strong>{{ $tasmi->predicate }}</strong></div>
                    </div>
                </div>

                <!-- SIGNATURES -->
                <div class="signatures-grid">
                    <div>
                        <div class="sig-title">Musyrif / Pembina Halaqah</div>
                        <div class="sig-name">{{ $tasmi->mentoringGroup?->mentor_name ?? 'Musyrif Tahfidz' }}</div>
                        <div class="sig-role">Pembina Tahfidz</div>
                    </div>
                    <div>
                        <div class="sig-title">
                            Cinangka, {{ \Carbon\Carbon::parse($tasmi->exam_date)->translatedFormat('d F Y') }}<br>
                            Munaqisy / Penguji Tasmi'
                        </div>
                        <div class="sig-name">{{ $tasmi->examiner_name }}</div>
                        <div class="sig-role">Penguji Al-Qur'an</div>
                    </div>
                    <div>
                        <div class="sig-title">Mengetahui,<br>Kepala SDIT An Nadzir</div>
                        <div class="sig-name">{{ $headmasterName }}</div>
                        <div class="sig-role">Kepala Sekolah</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
