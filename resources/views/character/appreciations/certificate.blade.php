<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piagam Penghargaan Karakter – {{ $appreciation->student->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Great+Vibes&family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            background: #0f172a;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }
        .no-print-bar {
            background: #1e293b;
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
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.9rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .cert-outer {
            width: 280mm;
            height: 194mm;
            margin: 20px auto;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            padding: 12mm;
            box-sizing: border-box;
            position: relative;
        }
        .cert-border-outer {
            width: 100%;
            height: 100%;
            border: 4px solid #065f46;
            padding: 5mm;
            box-sizing: border-box;
            position: relative;
        }
        .cert-border-inner {
            width: 100%;
            height: 100%;
            border: 1.5px dashed #d97706;
            padding: 6mm 15mm;
            box-sizing: border-box;
            text-align: center;
            background: radial-gradient(circle at center, #ffffff 60%, #fdfbf7 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }
        .cert-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 26rem;
            color: rgba(6, 95, 70, 0.03);
            pointer-events: none;
            z-index: 1;
        }
        .cert-header {
            position: relative;
            z-index: 2;
        }
        .cert-logo-text {
            font-size: 1.15rem;
            font-weight: 800;
            color: #065f46;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0;
        }
        .cert-sublogo {
            font-size: 0.75rem;
            color: #475569;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        .cert-title {
            font-family: 'Cinzel', serif;
            font-size: 1.9rem;
            font-weight: 900;
            color: #b45309;
            margin: 10px 0 2px;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .cert-no {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .cert-recipient-intro {
            font-size: 0.95rem;
            color: #475569;
            margin: 8px 0 0;
            font-style: italic;
        }
        .cert-recipient-name {
            font-family: 'Cinzel', serif;
            font-size: 1.85rem;
            font-weight: 900;
            color: #064e3b;
            letter-spacing: 1px;
            margin: 6px 0;
            border-bottom: 2px solid #d97706;
            display: inline-block;
            padding: 0 30px 4px;
        }
        .cert-meta {
            font-size: 0.88rem;
            color: #334155;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .cert-desc {
            font-size: 0.95rem;
            color: #1e293b;
            line-height: 1.5;
            max-width: 85%;
            margin: 0 auto;
        }
        .cert-badge-row {
            margin-top: 8px;
        }
        .cert-pill {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 800;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            margin: 0 4px;
        }
        .cert-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }
        .cert-sig-col {
            text-align: center;
            width: 220px;
        }
        .cert-sig-space {
            height: 48px;
        }
        .cert-sig-name {
            font-size: 0.92rem;
            font-weight: 800;
            color: #0f172a;
            border-bottom: 1px solid #1e293b;
            padding-bottom: 2px;
            margin: 0;
        }
        .cert-sig-title {
            font-size: 0.78rem;
            color: #64748b;
            margin-top: 3px;
        }
        @media print {
            .no-print-bar { display: none !important; }
            body { background: white !important; }
            .cert-outer {
                box-shadow: none !important;
                margin: 0 auto !important;
                width: 100% !important;
                height: 100% !important;
                padding: 4mm !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div style="font-weight: bold;">
            <i class="fas fa-award" style="color: #f59e0b;"></i> Piagam Penghargaan Bintang Karakter SMILE
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ $appreciation->getWaUrl() }}" target="_blank" 
               style="background: #25D366; color: white; padding: 8px 18px; border-radius: 8px; font-weight: bold; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 6px rgba(37,211,102,0.3);">
                <i class="fab fa-whatsapp" style="font-size: 1.1rem;"></i> Kabari Ortu via WA
            </a>
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak Piagam (A4 Landscape)
            </button>
            <button onclick="window.close()" style="background: #475569; color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-weight: bold;">
                Tutup
            </button>
        </div>
    </div>

    <div class="cert-outer">
        <div class="cert-border-outer">
            <div class="cert-border-inner">
                
                <i class="fas fa-award cert-watermark"></i>

                <div class="cert-header">
                    <p class="cert-logo-text">SDIT AN NADZIR CINANGKA</p>
                    <p class="cert-sublogo">SEKOLAH DASAR ISLAM TERPADU &bull; AKREDITASI A &bull; SISTEM MUTU TERPADU (SIMUTQIN)</p>
                    <h1 class="cert-title">PIAGAM PENGHARGAAN</h1>
                    <div class="cert-no">Nomor: {{ $appreciation->certificate_no ?: ('PIAGAM/SMILE/' . date('Ym') . '/' . str_pad($appreciation->id, 4, '0', STR_PAD_LEFT)) }}</div>
                </div>

                <div>
                    <p class="cert-recipient-intro">Diberikan sebagai bentuk apresiasi dan keteladanan akhlak mulia kepada:</p>
                    <div class="cert-recipient-name">{{ $appreciation->student->name }}</div>
                    <div class="cert-meta">
                        NIS: {{ $appreciation->student->nis ?? '-' }} &bull; Kelas {{ $appreciation->student->class }} &bull; Tahun Pelajaran {{ $appreciation->academicYear->name ?? '2026/2027' }}
                    </div>
                    
                    <p class="cert-desc">
                        Atas prestasi pembiasaan karakter luhur dan keteladanan nyata: <strong>"{{ $appreciation->title }}"</strong>.
                        @if(!empty($appreciation->description))
                            <br><span style="font-style: italic; font-size: 0.88rem; color: #475569;">"{{ $appreciation->description }}"</span>
                        @endif
                    </p>

                    <div class="cert-badge-row">
                        <span class="cert-pill" style="background: #fef3c7; color: #92400e; border-color: #fde68a;">
                            <i class="fas fa-star"></i> Kategori SMILE: {{ $appreciation->category }}
                        </span>
                        <span class="cert-pill" style="background: #ecfdf5; color: #065f46; border-color: #a7f3d0;">
                            <i class="fas fa-award"></i> Dimensi P5: {{ $appreciation->dimension_p5 ?: 'Adab Islami SIT' }}
                        </span>
                        <span class="cert-pill" style="background: #ede9fe; color: #6b21a8; border-color: #ddd6fe;">
                            <i class="fas fa-plus-circle"></i> +{{ $appreciation->point }} Poin Kebaikan
                        </span>
                    </div>
                </div>

                <div class="cert-footer">
                    <div class="cert-sig-col">
                        <p class="cert-sig-title">Wali Kelas / Guru Pengampu,</p>
                        <div class="cert-sig-space"></div>
                        <p class="cert-sig-name">{{ $appreciation->given_by ?: 'Wali Kelas' }}</p>
                    </div>

                    <div style="text-align: center;">
                        <div style="width: 55px; height: 55px; border-radius: 50%; border: 2px dashed #d97706; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #d97706;">
                            <i class="fas fa-ribbon" style="font-size: 1.6rem;"></i>
                        </div>
                        <span style="font-size: 0.72rem; color: #64748b; font-weight: bold; margin-top: 3px; display: block;">TERVERIFIKASI SIMUTQIN</span>
                    </div>

                    <div class="cert-sig-col">
                        <p class="cert-sig-title">Cinangka, {{ \Carbon\Carbon::parse($appreciation->date)->translatedFormat('d F Y') }}<br>Kepala SDIT AN NADZIR,</p>
                        <div class="cert-sig-space"></div>
                        <p class="cert-sig-name">{{ $headmasterName }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
