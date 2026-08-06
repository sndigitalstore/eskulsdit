<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Kadaluarsa - SIM Ekstrakurikuler</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            padding: 20px;
            color: #1e293b;
        }
        .card {
            background: white;
            padding: 40px 30px;
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            max-width: 450px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        .icon {
            width: 80px;
            height: 80px;
            background: #fee2e2;
            color: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin: 0 auto 20px;
        }
        h2 { font-weight: 800; margin-bottom: 10px; color: #0f172a; }
        p { color: #64748b; font-size: 0.95rem; line-height: 1.5; margin-bottom: 25px; }
        .btn {
            display: inline-block;
            background: #10b981;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 700;
            transition: background 0.2s;
        }
        .btn:hover { background: #059669; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <i class="fas fa-hourglass-end"></i>
        </div>
        <h2>Link Tautan Tidak Valid / Kadaluarsa</h2>
        <p>Tautan absensi guru pengganti ini sudah kadaluarsa atau tidak ditemukan. Silakan hubungi Guru Pembina utama atau Admin Sekolah untuk meminta tautan baru.</p>
        <a href="{{ url('/') }}" class="btn"><i class="fas fa-home me-2"></i> Kembali ke Beranda</a>
    </div>
</body>
</html>
