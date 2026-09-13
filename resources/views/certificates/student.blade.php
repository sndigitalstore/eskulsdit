<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piagam Keikutsertaan - {{ $student->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Montserrat:wght@400;500;600;700&family=Pinyon+Script&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            background: #e2e8f0;
            font-family: 'Montserrat', sans-serif;
            color: #1e293b;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background: #1e293b;
            padding: 10px 20px;
            border-radius: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            display: flex;
            gap: 12px;
        }

        .btn-print {
            background: #10b981;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-close {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        @media print {
            .no-print { display: none; }
            body { background: white; }
        }

        .cert-container {
            width: 297mm;
            height: 210mm;
            margin: 0 auto;
            background: #ffffff;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            padding: 12mm;
        }

        /* Certificate Double Ornamental Border */
        .cert-border-outer {
            border: 4px solid #065f46;
            height: 100%;
            box-sizing: border-box;
            padding: 6px;
            position: relative;
            border-radius: 4px;
        }

        .cert-border-inner {
            border: 2px dashed #d97706;
            height: 100%;
            box-sizing: border-box;
            padding: 25px 35px;
            position: relative;
            background: radial-gradient(circle at center, #ffffff 0%, #fafaf9 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
        }

        /* Header Logo & Title */
        .cert-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 5px;
        }

        .cert-logo {
            height: 60px;
            width: auto;
        }

        .institution-title {
            font-family: 'Cinzel', serif;
            font-size: 14pt;
            letter-spacing: 2px;
            color: #065f46;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
        }

        .school-sub {
            font-size: 10pt;
            color: #64748b;
            margin: 2px 0 0 0;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .cert-main-title {
            font-family: 'Cinzel', serif;
            font-size: 26pt;
            font-weight: 800;
            color: #92400e;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin: 10px 0 2px 0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        }

        .cert-subtitle {
            font-size: 10.5pt;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .given-to {
            font-size: 10.5pt;
            color: #64748b;
            font-style: italic;
            margin-bottom: 5px;
        }

        .student-name {
            font-family: 'Pinyon Script', cursive;
            font-size: 42pt;
            color: #065f46;
            margin: 5px 0;
            line-height: 1.1;
        }

        .student-class-info {
            font-size: 11pt;
            color: #334155;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .cert-body-text {
            font-size: 10.5pt;
            color: #475569;
            max-width: 800px;
            margin: 0 auto 15px auto;
            line-height: 1.5;
        }

        .eskul-badges {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .eskul-badge-item {
            background: #ecfdf5;
            border: 1.5px solid #10b981;
            color: #047857;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 10.5pt;
        }

        /* Footer Signatures & Seal */
        .cert-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 10px;
            padding: 0 40px;
        }

        .sig-box {
            text-align: center;
            width: 220px;
        }

        .sig-title {
            font-size: 9.5pt;
            color: #64748b;
            margin-bottom: 55px;
        }

        .sig-name {
            font-weight: 700;
            font-size: 11pt;
            color: #0f172a;
            border-bottom: 1.5px solid #0f172a;
            display: inline-block;
            padding-bottom: 2px;
            margin: 0;
        }

        .cert-seal {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            border: 3px double #ffffff;
            box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            font-family: 'Cinzel', serif;
        }

        .cert-seal span {
            font-size: 7.5pt;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .cert-seal strong {
            font-size: 11pt;
            font-weight: 800;
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan PDF</button>
        <button onclick="window.close()" class="btn-close">Tutup</button>
    </div>

    <div class="cert-container">
        <div class="cert-border-outer">
            <div class="cert-border-inner">

                <!-- Header -->
                <div>
                    <div class="cert-header">
                        <img src="{{ asset('logo.png') }}" class="cert-logo" alt="Logo">
                        <div>
                            <h1 class="institution-title">AN NADZIR ISLAMIC SCHOOL</h1>
                            <p class="school-sub">SDIT AN NADZIR — TAHUN PELAJARAN {{ $yearName }}</p>
                        </div>
                    </div>

                    <h2 class="cert-main-title">PIAGAM KEIKUTSERTAAN</h2>
                    <p class="cert-subtitle">KEGIATAN EKSTRAKURIKULER SISWA</p>
                </div>

                <!-- Body -->
                <div>
                    <p class="given-to">Diberikan Kepada Siswa/i Terbaik:</p>
                    <div class="student-name">{{ $student->name }}</div>
                    <div class="student-class-info">
                        NIS: {{ $student->nis ?? '-' }} &nbsp;|&nbsp; KELAS: {{ $student->class ?? '-' }}
                    </div>

                    <p class="cert-body-text">
                        Atas keikutsertaan aktif, dedikasi, serta kedisiplinan dalam mengikuti kegiatan Ekstrakurikuler di SDIT An Nadzir pada Tahun Pelajaran {{ $yearName }}.
                    </p>

                    <div class="eskul-badges">
                        @forelse($student->eskuls as $eskul)
                            <span class="eskul-badge-item">✨ {{ $eskul->name }}</span>
                        @empty
                            <span class="eskul-badge-item" style="border-color:#cbd5e1; color:#64748b;">Ekstrakurikuler Terdaftar</span>
                        @endforelse
                    </div>
                </div>

                <!-- Footer Signatures -->
                <div class="cert-footer">
                    <div class="sig-box">
                        <p class="sig-title">Mengetahui,<br><b>Kepala SDIT An Nadzir</b></p>
                        <p class="sig-name">{{ $headmasterName }}</p>
                    </div>

                    <div class="cert-seal">
                        <span>LULUS</span>
                        <strong>ESKUL</strong>
                        <span>{{ date('Y') }}</span>
                    </div>

                    <div class="sig-box">
                        <p class="sig-title">Cinangka, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br><b>Wali Kelas {{ $student->class }}</b></p>
                        <p class="sig-name">{{ $student->homeroom_teacher_name ?? 'Wali Kelas' }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
