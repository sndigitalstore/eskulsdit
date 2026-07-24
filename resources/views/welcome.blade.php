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
            --primary-dark: #047857;
            --primary-light: #10b981;
            --accent: {{ $accentColor }};
            --bg-light: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --card-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.07);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 50%, #f8fafc 100%);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
            min-height: 100vh;
        }

        h1, h2, h3, h4 { font-family: 'Outfit', sans-serif; }

        /* Animations */
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(1deg); }
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

        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Ambient Pastel Blobs */
        .bg-shapes {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.6;
        }

        .shape-1 { width: 550px; height: 550px; background: #c7d2fe; top: -120px; right: -100px; }
        .shape-2 { width: 450px; height: 450px; background: #a7f3d0; bottom: -100px; left: -100px; }
        .shape-3 { width: 350px; height: 350px; background: #fef08a; top: 35%; left: 20%; opacity: 0.4; }

        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 8%;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #0f172a;
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: -0.5px;
        }

        .logo-img {
            height: 42px;
            width: auto;
            object-fit: contain;
        }

        .nav-links { display: flex; gap: 32px; align-items: center; }
        
        .nav-link {
            text-decoration: none;
            color: #334155;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .nav-link:hover { color: #10b981; }

        .btn-login-nav {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 11px 26px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-login-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.45);
        }

        /* Hero Section */
        header.hero {
            padding: 160px 8% 90px;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            align-items: center;
            gap: 40px;
        }

        .hero-text h1 {
            font-size: 4.2rem;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.08;
            margin-bottom: 24px;
            animation: slideIn 0.8s ease-out;
            letter-spacing: -1.5px;
        }

        .hero-text h1 span {
            background: linear-gradient(135deg, #10b981 0%, #059669 40%, #4338ca 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-text p {
            font-size: 1.2rem;
            color: #475569;
            max-width: 580px;
            margin-bottom: 38px;
            animation: fadeIn 0.8s ease-out 0.2s both;
            line-height: 1.7;
        }

        .cta-group {
            display: flex;
            gap: 18px;
            animation: fadeIn 0.8s ease-out 0.4s both;
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: white;
            padding: 18px 36px;
            border-radius: 18px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);
            animation: pulseGlow 2.5s infinite;
        }

        .btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.5);
        }

        .btn-outline {
            border: 2px solid #10b981;
            color: #047857;
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(10px);
            padding: 18px 36px;
            border-radius: 18px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.05rem;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            background: #10b981;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25);
        }

        /* Hero Visual - Inspired directly by Reference Image 2 UI Glass Mockup */
        .hero-visual {
            position: relative;
            animation: fadeIn 1s ease-out 0.5s both;
        }

        .glass-mockup-frame {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 32px;
            padding: 14px;
            box-shadow: 0 30px 70px -15px rgba(15, 23, 42, 0.15), 0 0 0 1px rgba(255,255,255,0.5);
            animation: float 6s ease-in-out infinite;
        }

        /* Mini Dashboard Mockup matching Image 2 */
        .ui-mockup-container {
            background: #f8fafc;
            border-radius: 24px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 140px 1fr;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
            min-height: 380px;
        }

        /* Left Pastel Gradient Sidebar in Mockup */
        .ui-sidebar {
            background: linear-gradient(180deg, #a7f3d0 0%, #7dd3fc 50%, #c084fc 100%);
            padding: 18px 12px;
            color: #0f172a;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .ui-sidebar-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 0.85rem;
            color: #065f46;
            margin-bottom: 8px;
        }

        .ui-sidebar-avatar {
            width: 28px;
            height: 28px;
            background: rgba(255,255,255,0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            color: #047857;
        }

        .ui-menu-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            color: rgba(15, 23, 42, 0.75);
            padding: 8px 10px;
            border-radius: 10px;
            background: rgba(255,255,255,0.25);
        }

        .ui-menu-item.active {
            background: rgba(255,255,255,0.85);
            color: #047857;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Right Content Area in Mockup */
        .ui-content {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #ffffff;
        }

        .ui-header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ui-search-input {
            background: #f1f5f9;
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 0.72rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 6px;
            width: 170px;
        }

        /* Widgets Grid inside Mockup */
        .ui-widgets-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .ui-widget-card {
            border-radius: 16px;
            padding: 12px;
            color: #0f172a;
            position: relative;
            overflow: hidden;
        }

        .ui-widget-card.card-1 {
            background: linear-gradient(135deg, #fef08a 0%, #fde047 100%);
        }

        .ui-widget-card.card-2 {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        }

        .ui-widget-card h5 {
            font-size: 0.7rem;
            color: #475569;
            margin-bottom: 4px;
            font-weight: 700;
        }

        .ui-widget-card .value {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0f172a;
        }

        /* Chart Mockup Widget */
        .ui-chart-widget {
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 12px;
        }

        .ui-chart-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.72rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
        }

        /* Mock Progress Circles matching Image 2 */
        .ui-donuts-flex {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-top: 6px;
        }

        .ui-donut {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 800;
            color: #0f172a;
            position: relative;
        }

        .ui-donut-1 {
            background: conic-gradient(#10b981 0% 70%, #e2e8f0 70% 100%);
        }
        .ui-donut-2 {
            background: conic-gradient(#6366f1 0% 85%, #e2e8f0 85% 100%);
        }
        .ui-donut-3 {
            background: conic-gradient(#f59e0b 0% 60%, #e2e8f0 60% 100%);
        }

        .ui-donut-inner {
            width: 32px;
            height: 32px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Table List Row inside Mockup */
        .ui-table-mini {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 14px;
            padding: 8px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.7rem;
        }

        /* Stats Row Section */
        .stats {
            padding: 40px 8% 80px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .stat-card-glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            padding: 30px 24px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05);
            transition: all 0.3s;
        }

        .stat-card-glass:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.1);
            background: rgba(255, 255, 255, 0.9);
        }

        .stat-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
        }

        .stat-icon-wrapper.green { background: #d1fae5; color: #059669; }
        .stat-icon-wrapper.indigo { background: #e0e7ff; color: #4338ca; }
        .stat-icon-wrapper.amber { background: #fef3c7; color: #d97706; }

        .stat-info-text h3 {
            font-size: 2.2rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-info-text p {
            font-size: 0.92rem;
            color: #64748b;
            font-weight: 600;
        }

        /* Features Section */
        .features {
            padding: 100px 8%;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(15px);
            border-top: 1px solid rgba(226, 232, 240, 0.8);
        }

        .section-header {
            text-align: center;
            margin-bottom: 70px;
        }

        .section-header h2 {
            font-size: 2.8rem;
            color: #0f172a;
            margin-bottom: 16px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .section-header p {
            color: #64748b;
            font-size: 1.15rem;
            max-width: 750px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .feature-card {
            padding: 42px 32px;
            background: #ffffff;
            border-radius: 26px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.04);
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.1);
            border-color: #cbd5e1;
        }

        .feature-icon {
            width: 68px;
            height: 68px;
            background: linear-gradient(135deg, #ecfdf5 0%, #e0e7ff 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #059669;
            margin-bottom: 24px;
            transition: all 0.3s;
        }

        .feature-card:hover .feature-icon {
            background: linear-gradient(135deg, #10b981 0%, #4338ca 100%);
            color: white;
            transform: scale(1.08) rotate(-4deg);
        }

        .feature-card h3 {
            font-size: 1.45rem;
            margin-bottom: 14px;
            color: #0f172a;
            font-weight: 700;
        }

        .feature-card p {
            color: #64748b;
            line-height: 1.7;
            font-size: 0.98rem;
        }

        /* Footer */
        footer {
            padding: 90px 8% 40px;
            background: #0f172a;
            color: white;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 80px;
            margin-bottom: 70px;
        }

        .footer-brand h3 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #ffffff 0%, #a7f3d0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .footer-brand p {
            opacity: 0.7;
            line-height: 1.8;
            max-width: 420px;
            font-size: 1rem;
        }

        .footer-links h4 {
            font-size: 1.15rem;
            margin-bottom: 24px;
            font-weight: 700;
            color: #f8fafc;
        }

        .footer-links ul { list-style: none; }
        .footer-links li { margin-bottom: 14px; }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.98rem;
        }

        .footer-links a:hover { color: #34d399; padding-left: 4px; }

        .copyright {
            padding-top: 36px;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            opacity: 0.6;
            font-size: 0.92rem;
        }

        /* Mobile Responsiveness */
        @media (max-width: 1100px) {
            .hero-text h1 { font-size: 3.4rem; }
            header.hero { grid-template-columns: 1fr; text-align: center; gap: 40px; }
            .hero-text p { margin: 0 auto 30px; }
            .cta-group { justify-content: center; }
            .stats { grid-template-columns: 1fr; gap: 16px; }
            .features-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; gap: 40px; }
        }

        @media (max-width: 768px) {
            nav { padding: 1rem 5%; }
            .nav-links { display: none; }
            
            .mobile-login-btn {
                display: block !important;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                padding: 8px 18px;
                border-radius: 50px;
                text-decoration: none;
                font-weight: 700;
                font-size: 0.85rem;
            }

            header.hero { padding: 120px 5% 50px; }

            .hero-text h1 { font-size: 2.3rem; line-height: 1.25; }

            .cta-group { flex-direction: column; width: 100%; }

            .btn-primary, .btn-outline { width: 100%; justify-content: center; padding: 16px; }
        }

        .mobile-login-btn { display: none; }
    </style>
</head>
<body>

    <!-- Pastel Ambient Blobs -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Navigation -->
    <nav>
        <a href="/" class="logo">
            <img src="{{ asset('logo.png') }}" alt="Logo SDIT AN NADZIR" class="logo-img">
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

    <!-- Hero Section -->
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

        <!-- Hero Visual Mockup matching Reference Image 2 -->
        <div class="hero-visual">
            <div class="glass-mockup-frame">
                <div class="ui-mockup-container">
                    <!-- Left Pastel Sidebar -->
                    <div class="ui-sidebar">
                        <div class="ui-sidebar-brand">
                            <div class="ui-sidebar-avatar">.D</div>
                            <span>Dashboard</span>
                        </div>
                        <div class="ui-menu-item active">
                            <i class="fas fa-home"></i> Beranda
                        </div>
                        <div class="ui-menu-item">
                            <i class="fas fa-running"></i> Eskul
                        </div>
                        <div class="ui-menu-item">
                            <i class="fas fa-users"></i> Siswa
                        </div>
                        <div class="ui-menu-item">
                            <i class="fas fa-chart-pie"></i> Laporan
                        </div>
                    </div>

                    <!-- Right Content Mockup -->
                    <div class="ui-content">
                        <div class="ui-header-bar">
                            <div class="ui-search-input">
                                <i class="fas fa-search"></i> Cari siswa, eskul...
                            </div>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <i class="fas fa-bell" style="font-size: 0.75rem; color: #94a3b8;"></i>
                                <div style="width: 22px; height: 22px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 700; color: #475569;">A</div>
                            </div>
                        </div>

                        <!-- Mini Cards Grid -->
                        <div class="ui-widgets-grid">
                            <div class="ui-widget-card card-1">
                                <h5>Total Eskul</h5>
                                <div class="value">{{ $totalEskul }}</div>
                            </div>
                            <div class="ui-widget-card card-2">
                                <h5>Siswa Aktif</h5>
                                <div class="value">{{ $totalSiswa }}</div>
                            </div>
                        </div>

                        <!-- Donut Charts Widget matching Image 2 -->
                        <div class="ui-chart-widget">
                            <div class="ui-chart-header">
                                <span>Partisipasi Eskul</span>
                                <span style="color: #10b981; font-weight: 800;">Realtime</span>
                            </div>
                            <div class="ui-donuts-flex">
                                <div class="ui-donut ui-donut-1">
                                    <div class="ui-donut-inner">70%</div>
                                </div>
                                <div class="ui-donut ui-donut-2">
                                    <div class="ui-donut-inner">85%</div>
                                </div>
                                <div class="ui-donut ui-donut-3">
                                    <div class="ui-donut-inner">60%</div>
                                </div>
                            </div>
                        </div>

                        <!-- Mini Table Row -->
                        <div class="ui-table-mini">
                            <span style="font-weight: 700; color: #334155;">Pramuka & Tahfidz</span>
                            <span style="background: #d1fae5; color: #047857; padding: 2px 8px; border-radius: 10px; font-weight: 700; font-size: 0.65rem;">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Stats Row Section -->
    <section class="stats" id="statistik">
        <div class="stat-card-glass">
            <div class="stat-icon-wrapper green">
                <i class="fas fa-basketball-ball"></i>
            </div>
            <div class="stat-info-text">
                <h3>{{ $totalEskul }}</h3>
                <p>Pilihan Ekstrakurikuler</p>
            </div>
        </div>
        <div class="stat-card-glass">
            <div class="stat-icon-wrapper indigo">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-info-text">
                <h3>{{ $totalSiswa }}</h3>
                <p>Siswa Terdaftar</p>
            </div>
        </div>
        <div class="stat-card-glass">
            <div class="stat-icon-wrapper amber">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-info-text">
                <h3>{{ $totalGuru }}</h3>
                <p>Guru Pembina</p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
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

    <!-- Footer -->
    <footer>
        <div class="footer-grid">
            <div class="footer-brand">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                    <img src="{{ asset('logo.png') }}" alt="Logo SDIT AN NADZIR" style="height: 38px; width: auto; object-fit: contain;">
                    <h3 style="margin: 0;">SIM ESKUL AN NADZIR</h3>
                </div>
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
            if (window.scrollY > 80) {
                nav.style.padding = '0.8rem 8%';
                nav.style.boxShadow = '0 10px 30px rgba(15, 23, 42, 0.08)';
            } else {
                nav.style.padding = '1.25rem 8%';
                nav.style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>

