<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Ayah Bunda – SIMUTQIN SDIT AN NADZIR</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon & PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#064e3b">
    <link rel="apple-touch-icon" href="/logo.png">

    <style>
        :root {
            --primary: #064e3b;
            --primary-light: #047857;
            --accent: #0284c7;
            --emerald: #10b981;
            --emerald-glow: rgba(16, 185, 129, 0.25);
            --bg-base: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 50%, #f8fafc 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: var(--text-dark);
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Animated Blobs in Background */
        .ambient-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
            opacity: 0.6;
            animation: floatOrb 14s ease-in-out infinite alternate;
        }

        .blob-1 {
            width: 380px;
            height: 380px;
            background: rgba(16, 185, 129, 0.28);
            top: -100px;
            right: -80px;
        }

        .blob-2 {
            width: 420px;
            height: 420px;
            background: rgba(2, 132, 199, 0.2);
            bottom: -120px;
            left: -100px;
            animation-delay: -5s;
        }

        .blob-3 {
            width: 280px;
            height: 280px;
            background: rgba(251, 191, 36, 0.18);
            top: 45%;
            left: 20%;
            animation-duration: 18s;
        }

        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -25px) scale(1.08); }
            100% { transform: translate(-25px, 20px) scale(0.95); }
        }

        /* Back Button to Landing Page */
        .back-nav {
            position: absolute;
            top: 24px;
            left: 24px;
            z-index: 10;
        }

        .btn-back-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            color: #334155;
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 0.86rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-back-home:hover {
            background: #ffffff;
            color: var(--primary-light);
            transform: translateX(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        /* Main Card Container */
        .portal-card {
            width: 100%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(6, 78, 59, 0.18), 0 0 0 1px rgba(255, 255, 255, 0.8);
            overflow: hidden;
            position: relative;
            z-index: 2;
            animation: cardAppear 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Card Header */
        .portal-header {
            background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #0284c7 100%);
            padding: 38px 30px 32px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .portal-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.18), transparent 60%);
            pointer-events: none;
        }

        .logo-wrapper {
            width: 82px;
            height: 82px;
            background: #ffffff;
            border-radius: 22px;
            padding: 10px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2), 0 0 0 4px rgba(255, 255, 255, 0.25);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            animation: softPulse 4s ease-in-out infinite alternate;
        }

        @keyframes softPulse {
            0% { transform: translateY(0); }
            100% { transform: translateY(-4px); }
        }

        .logo-wrapper:hover {
            transform: scale(1.06) rotate(-2deg);
        }

        .logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(6px);
            color: #d1fae5;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 8px;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .portal-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            margin: 0;
            color: #ffffff;
            letter-spacing: -0.4px;
            line-height: 1.2;
        }

        .portal-subtitle {
            margin-top: 6px;
            font-size: 0.88rem;
            color: #d1fae5;
            font-weight: 500;
            opacity: 0.95;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .portal-tagline {
            margin-top: 8px;
            font-size: 0.78rem;
            color: #e0f2fe;
            font-style: italic;
            opacity: 0.9;
        }

        /* Card Content */
        .portal-content {
            padding: 32px 30px 24px;
        }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.88rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .form-label span.req {
            font-size: 0.75rem;
            color: #059669;
            font-weight: 600;
        }

        .input-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 18px;
            color: #94a3b8;
            font-size: 1rem;
            transition: color 0.25s, transform 0.25s;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 15px 18px 15px 50px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.95rem;
            font-family: inherit;
            font-weight: 600;
            color: #0f172a;
            outline: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .form-input::placeholder {
            color: #94a3b8;
            font-weight: 500;
        }

        .form-input:focus {
            background: #ffffff;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }

        .input-box:focus-within .input-icon-left {
            color: #059669;
            transform: scale(1.1);
        }

        .helper-card {
            margin-top: 10px;
            background: #f0fdf4;
            border: 1px dashed #a7f3d0;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.78rem;
            color: #065f46;
            line-height: 1.45;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .helper-card i {
            color: #059669;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .error-badge {
            margin-top: 8px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Submit Button */
        .btn-submit-search {
            width: 100%;
            margin-top: 22px;
            padding: 15px 22px;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 0.98rem;
            font-weight: 800;
            font-family: inherit;
            letter-spacing: 0.01em;
            cursor: pointer;
            box-shadow: 0 8px 24px -4px rgba(5, 150, 105, 0.45);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit-search:hover {
            background: linear-gradient(135deg, #047857 0%, #064e3b 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -4px rgba(5, 150, 105, 0.55);
        }

        .btn-submit-search:active {
            transform: translateY(0) scale(0.99);
        }

        .btn-submit-search i {
            font-size: 1rem;
            transition: transform 0.25s;
        }

        .btn-submit-search:hover i {
            transform: scale(1.15) rotate(-5deg);
        }

        /* Pillars Value Pills Preview */
        .feature-pills {
            margin-top: 22px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-around;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pill-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.74rem;
            font-weight: 700;
            color: #475569;
            background: #f8fafc;
            padding: 6px 10px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .pill-item:hover {
            background: #ffffff;
            color: var(--primary-light);
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .pill-item i {
            font-size: 0.8rem;
        }

        /* Card Footer */
        .portal-footer {
            text-align: center;
            padding: 16px 24px;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            font-size: 0.78rem;
            color: #64748b;
        }

        .portal-footer strong {
            color: #334155;
        }

        @media (max-width: 540px) {
            .back-nav {
                position: static;
                width: 100%;
                max-width: 500px;
                margin-bottom: 14px;
            }
            body {
                flex-direction: column;
                padding: 16px 12px;
            }
            .portal-header {
                padding: 30px 20px 24px;
            }
            .portal-title {
                font-size: 1.5rem;
            }
            .portal-content {
                padding: 24px 20px 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Animated Background Lights -->
    <div class="ambient-blob blob-1"></div>
    <div class="ambient-blob blob-2"></div>
    <div class="ambient-blob blob-3"></div>

    <!-- Tombol Kembali ke Beranda -->
    <div class="back-nav">
        <a href="/" class="btn-back-home">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    <!-- Main Portal Card -->
    <div class="portal-card">
        
        <!-- Header -->
        <div class="portal-header">
            <div class="logo-wrapper">
                <img src="{{ asset('logo.png') }}" alt="Logo SDIT AN NADZIR">
            </div>
            <div>
                <span class="portal-badge">
                    <i class="fas fa-heart"></i> Portal Ayah & Bunda
                </span>
                <h1 class="portal-title">Perkembangan Ananda</h1>
                <div class="portal-subtitle">
                    <i class="fas fa-shield-alt"></i> SIMUTQIN SDIT AN NADZIR
                </div>
                <div class="portal-tagline">
                    "Menemani Tumbuh Kembang Prestasi & Karakter Qur'ani"
                </div>
            </div>
        </div>

        <!-- Content & Form -->
        <div class="portal-content">
            <form action="{{ route('student-status.search') }}" method="POST" id="searchForm">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="keywordInput">
                        <span>Pencarian Data Ananda</span>
                        <span class="req">NIS atau Nama Lengkap</span>
                    </label>
                    <div class="input-box">
                        <i class="fas fa-search input-icon-left"></i>
                        <input type="text" 
                               id="keywordInput"
                               name="keyword" 
                               class="form-input" 
                               placeholder="Contoh: 212201001 atau Aisyah" 
                               value="{{ old('keyword') }}"
                               required 
                               autocomplete="off"
                               autofocus>
                    </div>

                    <div class="helper-card">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            Ketik <strong>Nomor Induk Siswa (NIS)</strong> atau <strong>Nama Lengkap</strong> ananda untuk membuka lembar capaian pembinaan secara aman.
                        </div>
                    </div>

                    @error('keyword')
                        <div class="error-badge">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit-search" id="btnSubmit">
                    <i class="fas fa-search"></i>
                    <span>Lihat Perkembangan Ananda</span>
                </button>
            </form>

            <!-- Feature Badges Preview -->
            <div class="feature-pills">
                <div class="pill-item" style="color: #047857;">
                    <i class="fas fa-quran"></i> Hafalan Qur'an
                </div>
                <div class="pill-item" style="color: #b45309;">
                    <i class="fas fa-star"></i> Mutaba'ah Karakter
                </div>
                <div class="pill-item" style="color: #0284c7;">
                    <i class="fas fa-graduation-cap"></i> Nilai & Prestasi
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="portal-footer">
            &copy; {{ date('Y') }} <strong>SDIT AN NADZIR</strong> • Pendidikan Berkarakter Qur'ani
        </div>

    </div>

    <script>
        // Form submit micro-feedback
        document.getElementById('searchForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnSubmit');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Mencari Data Ananda...</span>';
            btn.style.opacity = '0.9';
            btn.style.pointerEvents = 'none';
        });
    </script>

</body>
</html>
