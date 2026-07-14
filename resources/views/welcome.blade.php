<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Ekstrakurikuler - SDIT AN NADZIR</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="Sistem Informasi Manajemen Ekstrakurikuler SDIT AN NADZIR. Pantau kehadiran, nilai, dan perkembangan ekstrakurikuler siswa dalam satu platform cerdas.">
    
    <style>
        @php
            $branding = \App\Models\Setting::whereIn('key', ['app_primary_color', 'app_accent_color'])->pluck('value', 'key');
            $primaryColor = $branding['app_primary_color'] ?? '#059669';
            $accentColor = $branding['app_accent_color'] ?? '#f59e0b';

            // Real Statistics Data
            $totalEskul = \App\Models\Eskul::count();
            $totalSiswa = \App\Models\Student::count();
            $totalGuru = \App\Models\User::where('role', 'teacher')->count();
        @endphp
        
        :root {
            --primary: {{ $primaryColor }};
            --primary-dark: {{ $primaryColor }}cc;
            --primary-light: {{ $primaryColor }}33;
            --accent: {{ $accentColor }};
            --bg-light: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --card-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-light: #0f172a;
                --text-main: #f1f5f9;
                --text-muted: #94a3b8;
                --glass-bg: rgba(30, 41, 59, 0.7);
            }
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4 { font-family: 'Outfit', sans-serif; }

        /* Animations */
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 20px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Background Shapes */
        .bg-shapes {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
        }

        .shape-1 { width: 500px; height: 500px; background: #a7f3d0; top: -100px; right: -100px; }
        .shape-2 { width: 400px; height: 400px; background: #d1fae5; bottom: -100px; left: -100px; }
        .shape-3 { width: 300px; height: 300px; background: #fef3c7; top: 40%; left: 10%; }

        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 10%;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--primary-dark);
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.5px;
        }
        
        @media (prefers-color-scheme: dark) {
            .logo { color: var(--primary-light); }
        }

        .nav-links { display: flex; gap: 30px; align-items: center; }
        
        .nav-link {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .nav-link:hover { color: var(--primary); }

        .btn-login-nav {
            background: var(--primary);
            color: white;
            padding: 10px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.2);
        }

        .btn-login-nav:hover {
            transform: translateY(-2px);
            background: var(--primary-dark);
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3);
        }

        /* Hero Section */
        header.hero {
            padding: 180px 10% 100px;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            align-items: center;
            gap: 50px;
        }

        .hero-text h1 {
            font-size: 4.5rem;
            font-weight: 900;
            color: var(--text-main);
            line-height: 1.05;
            margin-bottom: 25px;
            animation: slideIn 0.8s ease-out;
            letter-spacing: -1px;
        }

        .hero-text h1 span {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-text p {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 600px;
            margin-bottom: 40px;
            animation: fadeIn 0.8s ease-out 0.2s both;
        }

        .cta-group {
            display: flex;
            gap: 20px;
            animation: fadeIn 0.8s ease-out 0.4s both;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 20px 40px;
            border-radius: 18px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            animation: pulse 2s infinite;
        }

        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        }

        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 20px 40px;
            border-radius: 18px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        .hero-visual {
            position: relative;
            animation: fadeIn 1s ease-out 0.5s both;
        }

        .glass-mockup {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 35px;
            padding: 25px;
            box-shadow: 0 40px 80px -15px rgba(0,0,0,0.2);
            transform: perspective(1000px) rotateY(-20deg) rotateX(10deg);
            animation: float 6s ease-in-out infinite;
        }

        .mockup-content {
            background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
            height: 400px;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .mockup-content i { font-size: 6rem; margin-bottom: 25px; opacity: 0.9; }

        /* Features Section */
        .features {
            padding: 120px 10%;
            background: white;
        }
        
        @media (prefers-color-scheme: dark) {
            .features { background: rgba(15, 23, 42, 0.5); }
        }

        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }

        .section-header h2 {
            font-size: 3rem;
            color: var(--text-main);
            margin-bottom: 20px;
            font-weight: 800;
        }

        .section-header p {
            color: var(--text-muted);
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
        }

        .feature-card {
            padding: 50px 40px;
            background: var(--bg-light);
            border-radius: 30px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 0;
        }

        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 60px -15px rgba(0,0,0,0.1);
        }

        .feature-card:hover::before { opacity: 0.05; }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            position: relative;
            z-index: 1;
            transition: all 0.3s;
        }

        .feature-card:hover .feature-icon {
            background: var(--primary);
            color: white;
            transform: scale(1.1) rotate(-5deg);
        }

        .feature-card h3 {
            font-size: 1.6rem;
            margin-bottom: 18px;
            color: var(--text-main);
            position: relative;
            z-index: 1;
        }

        .feature-card p {
            color: var(--text-muted);
            line-height: 1.8;
            position: relative;
            z-index: 1;
        }

        /* Stats Section */
        .stats {
            padding: 80px 10%;
            background: var(--primary-dark);
            color: white;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .stat-item h3 { font-size: 3.5rem; font-weight: 800; margin-bottom: 10px; }
        .stat-item p { font-size: 1.1rem; opacity: 0.7; font-weight: 600; }

        /* Footer */
        footer {
            padding: 100px 10% 50px;
            background: #020617;
            color: white;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 100px;
            margin-bottom: 80px;
        }

        .footer-brand h3 {
            font-size: 2rem;
            margin-bottom: 25px;
            background: linear-gradient(135deg, white 0%, var(--primary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .footer-brand p {
            opacity: 0.6;
            line-height: 1.8;
            max-width: 450px;
            font-size: 1.1rem;
        }

        .footer-links h4 {
            font-size: 1.25rem;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .footer-links ul { list-style: none; }
        .footer-links li { margin-bottom: 18px; }

        .footer-links a {
            color: white;
            opacity: 0.6;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 1.05rem;
        }

        .footer-links a:hover { opacity: 1; color: var(--primary-light); padding-left: 5px; }

        .copyright {
            padding-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            opacity: 0.5;
            font-size: 1rem;
        }

        /* Mobile Responsiveness */
        @media (max-width: 1200px) {
            .hero-text h1 { font-size: 3.5rem; }
            header.hero { gap: 30px; }
        }

        @media (max-width: 768px) {
            nav { padding: 1rem 5%; }
            .nav-links { display: none; }
            
            /* Show a small login button on mobile header */
            .mobile-login-btn {
                display: block !important;
                background: var(--primary);
                color: white;
                padding: 8px 16px;
                border-radius: 50px;
                text-decoration: none;
                font-weight: 700;
                font-size: 0.85rem;
            }

            header.hero {
                padding: 120px 5% 60px;
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-text h1 { 
                font-size: 2.25rem; 
                line-height: 1.2;
                margin-bottom: 20px;
            }

            .hero-text p { 
                font-size: 1rem; 
                margin-bottom: 30px;
            }

            .cta-group { 
                flex-direction: column;
                gap: 15px; 
                width: 100%;
            }

            .btn-primary, .btn-outline {
                width: 100%;
                justify-content: center;
                padding: 16px;
                font-size: 1rem;
            }

            .hero-visual { display: none; }
            .features-grid { grid-template-columns: 1fr; gap: 20px; }
            .section-header h2 { font-size: 2rem; }
            .stats { flex-direction: column; gap: 40px; padding: 60px 10%; }
            .footer-grid { grid-template-columns: 1fr; gap: 40px; text-align: center; }
            .footer-brand p { margin: 0 auto; }
        }

        .mobile-login-btn { display: none; }
    </style>
</head>
<body>

    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <nav>
        <a href="/" class="logo">
            <i class="fas fa-graduation-cap" style="font-size: 1.8rem; color: var(--primary);"></i>
            <span>SIM ESKUL</span>
        </a>
        <div class="nav-links">
            <a href="#fitur" class="nav-link">Fitur Unggulan</a>
            <a href="#statistik" class="nav-link">Statistik</a>
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-login-nav">Ke Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-login-nav">Login Admin</a>
            @endauth
        </div>
        <!-- Mobile Only Login -->
        @guest
            <a href="{{ route('login') }}" class="mobile-login-btn">Login</a>
        @else
            <a href="{{ url('/dashboard') }}" class="mobile-login-btn">Dashboard</a>
        @endguest
    </nav>

    <header class="hero">
        <div class="hero-text">
            <h1>Kelola Potensi Siswa <br> <span>Lebih Cerdas.</span></h1>
            <p>Sistem Informasi Manajemen Ekstrakurikuler SDIT AN NADZIR. Platform modern untuk memantau bakat, kehadiran, dan prestasi siswa secara real-time.</p>
            <div class="cta-group">
                <a href="{{ route('login') }}" class="btn-primary">
                    Mulai Sekarang <i class="fas fa-chevron-right"></i>
                </a>
                <a href="#fitur" class="btn-outline">Jelajahi Fitur</a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="glass-mockup">
                <div class="mockup-content">
                    <i class="fas fa-rocket"></i>
                    <h2 style="font-size: 2rem; margin-bottom: 10px;">Dashboard Elite</h2>
                    <p style="opacity: 0.8;">Interface modern dengan analisis data tajam untuk setiap kegiatan sekolah.</p>
                </div>
            </div>
        </div>
    </header>

    <section class="stats" id="statistik">
        <div class="stat-item">
            <h3>{{ $totalEskul }}</h3>
            <p>Pilihan Ekstrakurikuler</p>
        </div>
        <div class="stat-item">
            <h3>{{ $totalSiswa }}</h3>
            <p>Siswa Terdaftar</p>
        </div>
        <div class="stat-item">
            <h3>{{ $totalGuru }}</h3>
            <p>Guru Pembina</p>
        </div>
    </section>

    <section class="features" id="fitur">
        <div class="section-header">
            <h2>Transformasi Digital Sekolah</h2>
            <p>Kami menghadirkan solusi komprehensif untuk menjawab tantangan manajemen kegiatan sekolah di era digital.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <h3>Absensi Smart</h3>
                <p>Pencatatan kehadiran sesi latihan secara digital. Otomatisasi rekapitulasi per semester untuk penilaian raport.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Monitoring Nilai</h3>
                <p>Input nilai SAS (Sumatif Akhir Semester) dengan mudah. Grafik perkembangan kemampuan siswa di setiap bidang eskul.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-fingerprint"></i>
                </div>
                <h3>Keamanan Audit</h3>
                <p>Setiap aktivitas perubahan data terekam dalam sistem audit trail. Keamanan data siswa adalah prioritas utama kami.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-satellite-dish"></i>
                </div>
                <h3>Broadcasting</h3>
                <p>Kirim pengumuman penting ke seluruh pembina melalui dashboard. Komunikasi internal jadi lebih cepat dan efektif.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-file-export"></i>
                </div>
                <h3>Laporan Instan</h3>
                <p>Ekspor data siswa, absensi, dan nilai ke format Excel dalam hitungan detik. Siap untuk integrasi data dapodik.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-magic"></i>
                </div>
                <h3>Branding Kustom</h3>
                <p>Sesuaikan tampilan dashboard dengan identitas warna sekolah. Fleksibilitas tinggi untuk kenyamanan visual admin.</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-brand">
                <h3>SIM ESKUL AN NADZIR</h3>
                <p>Membentuk generasi Qur'ani yang unggul dalam akademik dan berbakat dalam minat. SDIT AN NADZIR terus berinovasi untuk memberikan pendidikan berkualitas terbaik.</p>
            </div>
            <div class="footer-links">
                <h4>Akses Cepat</h4>
                <ul>
                    <li><a href="#">Beranda</a></li>
                    <li><a href="#fitur">Fitur Utama</a></li>
                    <li><a href="#statistik">Statistik</a></li>
                    <li><a href="{{ route('login') }}">Login Admin</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Dukungan</h4>
                <ul>
                    <li><a href="#">Pusat Bantuan</a></li>
                    <li><a href="#">Panduan Admin</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; {{ date('Y') }} SDIT AN NADZIR. Didesain dengan penuh dedikasi untuk kemajuan pendidikan Indonesia.
        </div>
    </footer>

    <script>
        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Navbar Scroll Animation
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 100) {
                nav.style.padding = '0.8rem 10%';
                nav.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
            } else {
                nav.style.padding = '1.5rem 10%';
                nav.style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>
