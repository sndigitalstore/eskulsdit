<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berhasil Disimpan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e0f7fa;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            background: white;
            border-radius: 12px;
            border-top: 10px solid #2980b9;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .logo-img {
            max-height: 80px;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 24px;
            font-weight: 600;
            margin-top: 0;
            color: #2d3436;
        }
        p {
            color: #636e72;
            margin-bottom: 30px;
            font-size: 15px;
            line-height: 1.6;
        }
        .btn-again {
            display: inline-block;
            color: #2980b9;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            border: 1px solid #2980b9;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .btn-again:hover {
            background: #2980b9;
            color: white;
        }
        .check-icon {
            font-size: 50px;
            color: #2980b9;
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="card" style="padding: 0; overflow: hidden; max-width: 500px;">
        <img src="{{ asset('header_banner.png') }}" alt="Header Banner" style="width: 100%; height: auto; display: block;">
        
        <div style="padding: 40px;">
            <i class="fas fa-check-circle check-icon"></i>
            <h1>Terima Kasih!</h1>
            <p>Pilihan Ekstrakurikuler <strong>{{ session('student_name') }}</strong> telah berhasil direkam.</p>
            <div>
                <a href="{{ route('pilihan-eskul.form') }}" class="btn-again">Isi Formulir Lagi</a>
            </div>
        </div>
    </div>
</body>
</html>
