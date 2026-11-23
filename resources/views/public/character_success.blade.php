<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Berhasil Terkirim – {{ $schoolName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#064e3b">
    <link rel="apple-touch-icon" href="/logo.png">

    <style>
        :root {
            --primary: #064e3b;
            --emerald: #10b981;
            --rose: #e11d48;
            --blue: #0284c7;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 50%, #fef2f2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: #0f172a;
        }

        .success-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(14px);
            border-radius: 28px;
            padding: 44px 36px;
            max-width: 580px;
            width: 100%;
            text-align: center;
            border: 1px solid #e2e8f0;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.06);
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
        }
        .icon-circle-green {
            background: #dcfce7;
            color: #10b981;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.25);
        }
        .icon-circle-rose {
            background: #ffe4e6;
            color: #e11d48;
            box-shadow: 0 10px 25px rgba(225, 29, 72, 0.25);
        }

        .title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.7rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .desc {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .ticket-box {
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: inline-block;
            width: 100%;
        }
        .ticket-label {
            font-size: 0.76rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .ticket-number {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 900;
            color: #064e3b;
            margin-top: 4px;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 24px;
            border-radius: 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.98rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-home {
            background: #064e3b;
            color: #fff;
        }
        .btn-home:hover {
            background: #047857;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 78, 59, 0.25);
        }
        .btn-wa {
            background: #25D366;
            color: #fff;
        }
        .btn-wa:hover {
            background: #20ba5c;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 211, 102, 0.25);
        }
        .btn-back-form {
            background: #f1f5f9;
            color: #475569;
        }
        .btn-back-form:hover {
            background: #e2e8f0;
            color: #1e293b;
        }
    </style>
</head>
<body>

    <div class="success-card">
        @if($type === 'pengaduan')
            <div class="icon-circle icon-circle-rose">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1 class="title">Laporan Pengaduan Diterima</h1>
            <p class="desc">
                Jazakumullah khairan atas kepedulian Anda. Laporan pengaduan telah aman tersimpan dalam sistem dan berstatus <strong>Menunggu Verifikasi</strong> oleh Tim Pencegahan & Penanganan Kekerasan (TPPK) serta Kesiswaan <strong>{{ $schoolName }}</strong>.
            </p>
            <div class="ticket-box">
                <div class="ticket-label">Nomor Tiket Pengaduan</div>
                <div class="ticket-number">TPPK-{{ str_pad($id ?? '1', 5, '0', STR_PAD_LEFT) }}</div>
            </div>
        @else
            <div class="icon-circle icon-circle-green">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="title">Apresiasi Kebaikan Terkirim</h1>
            <p class="desc">
                Alhamdulillah! Terima kasih telah mengabarkan kebaikan. Catatan ini akan ditinjau oleh wali kelas & kesiswaan <strong>{{ $schoolName }}</strong> untuk dimasukkan ke dalam portofolio apresiasi karakter ananda.
            </p>
            <div class="ticket-box">
                <div class="ticket-label">Nomor Catatan Apresiasi</div>
                <div class="ticket-number">SMILE-{{ str_pad($id ?? '1', 5, '0', STR_PAD_LEFT) }}</div>
            </div>
        @endif

        <div class="btn-group">
            <a href="{{ url('/') }}" class="btn btn-home">
                <i class="fas fa-home"></i> Kembali ke Halaman Utama
            </a>
            <a href="{{ route('public.character.index', ['tab' => $type]) }}" class="btn btn-back-form">
                <i class="fas fa-plus-circle"></i> Kirim Laporan / Apresiasi Lainnya
            </a>
            @if($schoolPhone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $schoolPhone) }}?text=Halo%20Admin%20TPPK%20{{ urlencode($schoolName) }},%20saya%20telah%20mengirim%20laporan%20dengan%20nomor%20tiket%20{{ $type === 'pengaduan' ? 'TPPK-' . str_pad($id ?? '1', 5, '0', STR_PAD_LEFT) : 'SMILE-' . str_pad($id ?? '1', 5, '0', STR_PAD_LEFT) }}" target="_blank" class="btn btn-wa">
                    <i class="fab fa-whatsapp"></i> Hubungi Hotline Kesiswaan / TPPK
                </a>
            @endif
        </div>
    </div>

</body>
</html>
