<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koneksi Terputus - SIM Eskul SDIT AN NADZIR</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 50%, #f0fdf4 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #0f172a;
            text-align: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 3.5rem 2.5rem;
            border-radius: 24px;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.1);
            max-width: 420px;
            width: 90%;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .icon-wrapper {
            width: 90px;
            height: 90px;
            background: #fee2e2;
            color: #ef4444;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 1.75rem;
            font-size: 2.75rem;
            animation: pulse 2s infinite;
            box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.2);
        }
        h1 {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            color: #1e293b;
        }
        p {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn-retry {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 0.9rem 2rem;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 6px 20px -2px rgba(16, 185, 129, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .btn-retry:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -4px rgba(16, 185, 129, 0.55);
        }
        .btn-retry:active {
            transform: translateY(0);
        }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-wrapper">
            <i class="fas fa-wifi-slash"></i>
        </div>
        <h1>Koneksi Terputus</h1>
        <p>Sepertinya perangkat Anda sedang offline. Halaman ini memerlukan koneksi internet untuk memuat data sekolah terbaru.</p>
        <button class="btn-retry" onclick="window.location.reload()">
            <i class="fas fa-sync-alt"></i> Coba Memuat Ulang
        </button>
    </div>
</body>
</html>
