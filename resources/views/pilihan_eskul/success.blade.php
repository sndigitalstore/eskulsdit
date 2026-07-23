<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berhasil Disimpan - SIM Ekstrakurikuler</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 50%, #f8fafc 100%);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #0f172a;
            position: relative;
        }

        .bg-shapes {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.5;
        }

        .shape-1 { width: 450px; height: 450px; background: #a7f3d0; top: -100px; right: -100px; }
        .shape-2 { width: 400px; height: 400px; background: #c7d2fe; bottom: -100px; left: -100px; }

        .card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.08);
            max-width: 520px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 10;
            overflow: hidden;
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            margin-top: 10px;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        p {
            color: #475569;
            margin-bottom: 30px;
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .btn-again {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: white;
            text-decoration: none;
            font-size: 0.98rem;
            font-weight: 700;
            padding: 14px 30px;
            border-radius: 50px;
            transition: all 0.3s;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);
        }

        .btn-again:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.45);
            color: white;
        }

        .check-icon-wrapper {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 16px;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.2);
        }
    </style>
</head>
<body>

    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <div class="card">
        <img src="{{ asset('header_banner.png') }}" alt="Header Banner" style="width: 100%; height: auto; display: block;">
        
        <div style="padding: 40px 30px;">
            <div class="check-icon-wrapper">
                <i class="fas fa-check"></i>
            </div>
            <h1>Terima Kasih!</h1>
            <p>Pilihan Ekstrakurikuler <strong>{{ session('student_name') }}</strong> telah berhasil direkam.</p>
            <div>
                <a href="{{ route('pilihan-eskul.form') }}" class="btn-again">
                    <i class="fas fa-redo"></i> Isi Formulir Lagi
                </a>
            </div>
        </div>
    </div>
</body>
</html>
