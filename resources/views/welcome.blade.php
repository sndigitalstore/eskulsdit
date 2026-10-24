<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMUTQIN SDIT AN NADZIR – Menemani Tumbuh Kembang Prestasi & Karakter Qur'ani</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- PWA Manifest & Favicon -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#064e3b">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="apple-touch-icon" href="/logo.png">
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="SIMUTQIN SDIT AN NADZIR - Sistem Informasi Mutaba'ah, Tahfidz, Karakter, dan Integrasi Nilai. Menemani Tumbuh Kembang Prestasi dan Karakter Qur'ani.">

    @php
        try {
            $branding = \App\Models\Setting::whereIn('key', ['app_primary_color', 'app_accent_color'])->pluck('value', 'key');
            $primaryColor = $branding['app_primary_color'] ?? '#064e3b';
            $accentColor = $branding['app_accent_color'] ?? '#0284c7';

            // Real Statistics Data
            $totalEskul = \App\Models\Eskul::count();
            $totalSiswa = \App\Models\Student::where('status', 'active')->count();
            if ($totalSiswa == 0) {
                $totalSiswa = \App\Models\Student::count();
            }
            $totalGuru = \App\Models\User::where('role', 'teacher')->count();
            $totalKelompok = \App\Models\MentoringGroup::count();
            $totalPrestasi = \App\Models\Achievement::count();
        } catch (\Throwable $e) {
            $primaryColor = '#064e3b';
            $accentColor = '#0284c7';
            $totalEskul = 12;
            $totalSiswa = 350;
            $totalGuru = 28;
            $totalKelompok = 24;
            $totalPrestasi = 15;
        }
    @endphp

    <style>
        :root {
            --primary: #064e3b;
            --primary-mid: #047857;
            --primary-light: #10b981;
            --secondary: #0284c7;
            --secondary-light: #38bdf8;
            --amber: #f59e0b;
            --purple: #8b5cf6;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
            --card-glass: rgba(255, 255, 255, 0.88);
            --card-border: rgba(226, 232, 240, 0.8);
            --shadow-subtle: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-elevated: 0 20px 35px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(6, 78, 59, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(2, 132, 199, 0.06) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(16, 185, 129, 0.04) 0px, transparent 50%);
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.02em;
        }

        /* Ambient Glow Blobs */
        .ambient-blobs {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.45;
        }

        .blob-1 {
            width: 600px;
            height: 600px;
            background: #a7f3d0;
            top: -150px;
            left: -150px;
        }

        .blob-2 {
            width: 550px;
            height: 550px;
            background: #bae6fd;
            top: 10%;
            right: -120px;
        }

        .blob-3 {
            width: 450px;
            height: 450px;
            background: #fde68a;
            bottom: 5%;
            left: 20%;
            opacity: 0.3;
        }

        /* Header Navigation */
        nav.main-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 1.1rem 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            z-index: 1000;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }

        nav.scrolled {
            padding: 0.85rem 7%;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.06));
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }

        .brand-title {
            font-size: 1.35rem;
            font-weight: 900;
            color: #064e3b;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .brand-subtitle {
            font-size: 0.72rem;
            font-weight: 800;
            color: #0284c7;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .nav-link {
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            font-size: 0.93rem;
            transition: color 0.2s ease;
            position: relative;
        }

        .nav-link:hover {
            color: #064e3b;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: #047857;
            transition: width 0.25s ease;
            border-radius: 2px;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-nav-portal {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #064e3b 0%, #047857 100%);
            color: white;
            text-decoration: none;
            padding: 9px 20px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.88rem;
            box-shadow: 0 4px 12px rgba(6, 78, 59, 0.25);
            transition: all 0.25s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-nav-portal:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(6, 78, 59, 0.35);
            color: white;
        }

        /* Hero Section */
        header.hero-section {
            padding: 150px 7% 90px;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            align-items: center;
            gap: 50px;
            position: relative;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(6, 78, 59, 0.08);
            border: 1px solid rgba(6, 78, 59, 0.2);
            color: #064e3b;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(6, 78, 59, 0.04);
        }

        .hero-title {
            font-size: 3.3rem;
            line-height: 1.16;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 20px;
        }

        .hero-title span.highlight-green {
            background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-title span.highlight-blue {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 1.1rem;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 34px;
            max-width: 620px;
        }

        .hero-cta-group {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #064e3b 0%, #047857 60%, #059669 100%);
            color: white;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 10px 24px -4px rgba(6, 78, 59, 0.35);
            transition: all 0.25s ease;
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px -4px rgba(6, 78, 59, 0.45);
            color: white;
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: #334155;
            text-decoration: none;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            border: 1px solid #cbd5e1;
            box-shadow: var(--shadow-subtle);
            transition: all 0.25s ease;
        }

        .btn-hero-secondary:hover {
            background: #f8fafc;
            color: #064e3b;
            border-color: #94a3b8;
            transform: translateY(-2px);
        }

        /* Hero UI Glass Mockup */
        .hero-mockup-wrapper {
            position: relative;
        }

        .hero-mockup-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: var(--shadow-elevated), 0 0 0 1px rgba(226, 232, 240, 0.8);
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .hero-mockup-card:hover {
            transform: translateY(-4px);
        }

        .mockup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .mockup-badge-live {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 800;
            color: #047857;
            background: #dcfce7;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .mockup-badge-live .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulseGlow 2s infinite;
        }

        .mockup-metrics-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .mockup-metric {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
        }

        .mockup-metric.active-theme {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border-color: #bbf7d0;
        }

        .mockup-metric .metric-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .mockup-metric .metric-val {
            font-size: 1.45rem;
            font-weight: 900;
            color: #064e3b;
            margin-top: 2px;
        }

        .mockup-feed {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }

        .mockup-feed-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mockup-feed-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
        }

        /* 4 Pillars Section */
        section.section-pillars {
            padding: 90px 7%;
            background: white;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .section-header {
            text-align: center;
            max-width: 760px;
            margin: 0 auto 55px;
        }

        .section-tag {
            display: inline-block;
            background: #e0f2fe;
            color: #0284c7;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
        }

        .section-title {
            font-size: 2.35rem;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .section-desc {
            font-size: 1.05rem;
            color: #64748b;
            line-height: 1.6;
        }

        .pillars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }

        .pillar-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 28px 24px;
            box-shadow: var(--shadow-subtle);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .pillar-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-elevated);
            border-color: #cbd5e1;
        }

        .pillar-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--card-color, #047857);
        }

        .pillar-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: var(--icon-bg, #ecfdf5);
            color: var(--icon-color, #047857);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 20px;
        }

        .pillar-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .pillar-text {
            font-size: 0.92rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
        }

        .pillar-features-list {
            list-style: none;
            padding: 0;
            margin: 0;
            border-top: 1px solid #f1f5f9;
            padding-top: 16px;
        }

        .pillar-features-list li {
            font-size: 0.84rem;
            color: #334155;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pillar-features-list li i {
            color: #10b981;
            font-size: 0.75rem;
        }

        /* Stats Row Section */
        section.section-stats {
            padding: 70px 7%;
            background: linear-gradient(135deg, #064e3b 0%, #065f46 60%, #047857 100%);
            color: white;
            position: relative;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .stat-item {
            padding: 10px;
        }

        .stat-number {
            font-size: 2.8rem;
            font-weight: 900;
            color: #fef08a;
            line-height: 1.1;
            font-family: 'Outfit', sans-serif;
        }

        .stat-label {
            font-size: 0.95rem;
            font-weight: 700;
            opacity: 0.92;
            margin-top: 6px;
        }

        .stat-desc {
            font-size: 0.8rem;
            opacity: 0.75;
            margin-top: 2px;
        }

        /* Features Grid Section */
        section.section-features {
            padding: 90px 7%;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }

        .feature-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-subtle);
            transition: all 0.25s ease;
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .feature-box:hover {
            border-color: #0284c7;
            transform: translateY(-3px);
            box-shadow: var(--shadow-elevated);
        }

        .feature-box-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #e0f2fe;
            color: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .feature-box-content h4 {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .feature-box-content p {
            font-size: 0.88rem;
            color: #64748b;
            line-height: 1.55;
        }

        /* CTA Banner */
        section.section-cta {
            padding: 60px 7% 90px;
        }

        .cta-banner-card {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            border-radius: 22px;
            padding: 45px 50px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 30px;
            box-shadow: 0 20px 40px -10px rgba(2, 132, 199, 0.4);
            position: relative;
            overflow: hidden;
        }

        .cta-banner-card::after {
            content: '';
            position: absolute;
            right: -60px;
            bottom: -60px;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .cta-banner-content h3 {
            font-size: 1.85rem;
            font-weight: 900;
            margin-bottom: 8px;
        }

        .cta-banner-content p {
            font-size: 0.98rem;
            opacity: 0.9;
            max-width: 580px;
        }

        /* Footer */
        footer.main-footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 70px 7% 30px;
            border-top: 1px solid #1e293b;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.2fr;
            gap: 40px;
            margin-bottom: 50px;
        }

        .footer-brand h4 {
            color: white;
            font-size: 1.3rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .footer-brand p {
            font-size: 0.88rem;
            line-height: 1.6;
            color: #94a3b8;
            max-width: 380px;
        }

        .footer-col h5 {
            color: white;
            font-size: 0.95rem;
            font-weight: 800;
            margin-bottom: 18px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .footer-links-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links-list li {
            margin-bottom: 10px;
        }

        .footer-links-list a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.88rem;
            transition: color 0.2s ease;
        }

        .footer-links-list a:hover {
            color: #38bdf8;
        }

        .footer-bottom {
            border-top: 1px solid #1e293b;
            padding-top: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 0.82rem;
            color: #64748b;
        }

        /* ===== MOBILE DRAWER & NAV ACTIONS ===== */
        .mobile-nav-actions {
            display: none;
            align-items: center;
            gap: 8px;
        }

        .mobile-quick-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(6, 78, 59, 0.08);
            color: #064e3b;
            border: 1px solid rgba(6, 78, 59, 0.18);
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .mobile-quick-btn:active {
            background: rgba(6, 78, 59, 0.16);
            transform: scale(0.97);
        }

        .mobile-menu-toggle {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mobile-menu-toggle:active {
            background: #e2e8f0;
            transform: scale(0.95);
        }

        .mobile-drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 1100;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .mobile-drawer-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-drawer {
            position: fixed;
            top: 0;
            right: 0;
            width: 82%;
            max-width: 320px;
            height: 100%;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            z-index: 1200;
            box-shadow: -8px 0 30px rgba(0, 0, 0, 0.12);
            transform: translateX(100%);
            transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .mobile-drawer.active {
            transform: translateX(0);
        }

        .mobile-drawer-header {
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
        }

        .mobile-drawer-close {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #f1f5f9;
            border: none;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .mobile-drawer-close:active {
            background: #ea5455;
            color: white;
        }

        .mobile-drawer-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
            justify-content: space-between;
        }

        .mobile-nav-links {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .m-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            text-decoration: none;
            color: #334155;
            font-weight: 700;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .m-nav-link i {
            color: #047857;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .m-nav-link:hover, .m-nav-link:active {
            background: #f1f5f9;
            color: #064e3b;
        }

        .m-nav-highlight {
            background: #ecfdf5;
            color: #064e3b;
            border: 1px solid #bbf7d0;
            justify-content: space-between;
        }

        .m-badge-pill {
            font-size: 0.68rem;
            font-weight: 800;
            background: #10b981;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .mobile-drawer-footer {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #f1f5f9;
        }

        .m-btn-portal {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: linear-gradient(135deg, #064e3b 0%, #047857 100%);
            color: white;
            text-decoration: none;
            padding: 13px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 0.92rem;
            box-shadow: 0 4px 14px rgba(6, 78, 59, 0.3);
            transition: all 0.2s ease;
        }

        .m-btn-portal:active {
            transform: scale(0.98);
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            header.hero-section {
                grid-template-columns: 1fr;
                padding: 120px 6% 60px;
                text-align: center;
                gap: 40px;
            }

            .hero-desc {
                margin: 0 auto 28px;
            }

            .hero-cta-group {
                justify-content: center;
            }

            .hero-mockup-wrapper {
                max-width: 580px;
                margin: 0 auto;
                width: 100%;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            nav.main-nav {
                padding: 0.75rem 5%;
            }

            nav.scrolled {
                padding: 0.65rem 5%;
            }

            nav.main-nav .nav-menu {
                display: none;
            }

            .mobile-nav-actions {
                display: flex;
            }

            .brand-logo {
                width: 38px;
                height: 38px;
            }

            .brand-title {
                font-size: 1.18rem;
            }

            .brand-subtitle {
                font-size: 0.65rem;
            }

            /* Hero Section on Mobile */
            header.hero-section {
                padding: 95px 5% 45px;
                gap: 32px;
            }

            .hero-badge {
                font-size: 0.75rem;
                padding: 6px 12px;
                margin-bottom: 14px;
            }

            .hero-title {
                font-size: 1.95rem;
                line-height: 1.22;
                margin-bottom: 14px;
            }

            .hero-desc {
                font-size: 0.92rem;
                line-height: 1.6;
                margin-bottom: 22px;
            }

            .hero-cta-group {
                display: flex;
                flex-direction: column;
                width: 100%;
                gap: 10px;
                max-width: 380px;
                margin: 0 auto;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                width: 100%;
                justify-content: center;
                padding: 12px 18px;
                font-size: 0.9rem;
                border-radius: 12px;
            }

            /* Mockup Card on Mobile */
            .hero-mockup-card {
                padding: 18px 14px;
                border-radius: 18px;
            }

            .mockup-header {
                padding-bottom: 10px;
                margin-bottom: 12px;
            }

            .mockup-metric {
                padding: 10px 12px;
            }

            .mockup-metric .metric-val {
                font-size: 1.3rem;
            }

            .mockup-feed {
                padding: 9px 10px;
                gap: 8px;
            }

            .mockup-feed-left {
                gap: 8px;
                flex: 1;
                min-width: 0;
            }

            .mockup-feed-icon {
                width: 28px;
                height: 28px;
                font-size: 0.78rem;
            }

            .mockup-feed-left div:last-child {
                flex: 1;
                min-width: 0;
                text-align: left;
            }

            .mockup-feed-left div:last-child div:first-child {
                font-size: 0.78rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .mockup-feed-left div:last-child div:last-child {
                font-size: 0.68rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .mockup-feed > span {
                flex-shrink: 0;
                font-size: 0.65rem;
                padding: 2px 6px;
            }

            /* 4 Pillars Section on Mobile */
            section.section-pillars {
                padding: 55px 5%;
            }

            .section-header {
                margin-bottom: 35px;
            }

            .section-tag {
                font-size: 0.72rem;
                padding: 4px 12px;
            }

            .section-title {
                font-size: 1.7rem;
                line-height: 1.25;
            }

            .section-desc {
                font-size: 0.9rem;
                line-height: 1.55;
            }

            .pillars-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .pillar-card {
                padding: 20px 18px;
                border-radius: 16px;
            }

            .pillar-icon-box {
                width: 44px;
                height: 44px;
                font-size: 1.15rem;
                margin-bottom: 14px;
                border-radius: 10px;
            }

            .pillar-title {
                font-size: 1.15rem;
                margin-bottom: 6px;
            }

            .pillar-text {
                font-size: 0.88rem;
                line-height: 1.55;
                margin-bottom: 14px;
            }

            .pillar-features-list {
                padding-top: 12px;
            }

            .pillar-features-list li {
                font-size: 0.8rem;
                margin-bottom: 6px;
            }

            /* Stats Grid 2x2 on Mobile */
            section.section-stats {
                padding: 50px 5%;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 12px;
            }

            .stat-item {
                background: rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.12);
                border-radius: 14px;
                padding: 16px 10px;
            }

            .stat-number {
                font-size: 2.1rem;
            }

            .stat-label {
                font-size: 0.82rem;
                margin-top: 4px;
            }

            .stat-desc {
                font-size: 0.7rem;
            }

            /* Features Grid on Mobile */
            section.section-features {
                padding: 55px 5%;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .feature-box {
                padding: 16px;
                border-radius: 14px;
                gap: 12px;
            }

            .feature-box-icon {
                width: 38px;
                height: 38px;
                font-size: 1.05rem;
                border-radius: 9px;
            }

            .feature-box-content h4 {
                font-size: 0.95rem;
                margin-bottom: 4px;
            }

            .feature-box-content p {
                font-size: 0.82rem;
                line-height: 1.5;
            }

            /* CTA Banner on Mobile */
            section.section-cta {
                padding: 30px 5% 60px;
            }

            .cta-banner-card {
                padding: 28px 18px;
                border-radius: 18px;
                text-align: center;
                flex-direction: column;
                gap: 18px;
            }

            .cta-banner-content h3 {
                font-size: 1.4rem;
                line-height: 1.25;
            }

            .cta-banner-content p {
                font-size: 0.86rem;
            }

            .cta-banner-card .btn-hero-primary {
                width: 100%;
                justify-content: center;
            }

            /* Footer on Mobile */
            footer.main-footer {
                padding: 45px 5% 20px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 28px;
                margin-bottom: 35px;
            }

            .footer-brand h4 {
                font-size: 1.15rem;
            }

            .footer-brand p {
                font-size: 0.84rem;
            }

            .footer-col h5 {
                font-size: 0.88rem;
                margin-bottom: 12px;
            }

            .footer-links-list li {
                margin-bottom: 8px;
            }

            .footer-links-list a {
                font-size: 0.84rem;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 8px;
                font-size: 0.78rem;
                padding-top: 18px;
            }
        }

        /* Narrow Phones (< 380px) */
        @media (max-width: 380px) {
            .brand-title {
                font-size: 1.05rem;
            }

            .hero-title {
                font-size: 1.72rem;
            }

            .mobile-quick-btn span {
                display: none;
            }

            .mobile-quick-btn {
                padding: 7px 9px;
            }

            .stats-grid {
                gap: 8px;
            }

            .stat-number {
                font-size: 1.85rem;
            }

            .stat-item {
                padding: 12px 6px;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Blobs -->
    <div class="ambient-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="main-nav" id="navbar">
        <a href="/" class="brand-container">
            <img src="{{ asset('logo.png') }}" alt="Logo SDIT AN NADZIR" class="brand-logo">
            <div class="brand-text">
                <span class="brand-title">SIMUTQIN</span>
                <span class="brand-subtitle">SDIT AN NADZIR</span>
            </div>
        </a>

        <div class="nav-menu">
            <a href="#pilar" class="nav-link">4 Pilar Pendidikan</a>
            <a href="#keunggulan" class="nav-link">Fitur Terpadu</a>
            <a href="#statistik" class="nav-link">Statistik</a>
            <a href="{{ route('student-status.index') }}" class="nav-link" style="color: #047857; font-weight: 700;">
                <i class="fas fa-search" style="font-size: 0.8rem; color: #059669;"></i> Portal Ayah Bunda
            </a>
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-nav-portal">
                    <i class="fas fa-th-large"></i> Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-nav-portal">
                    <i class="fas fa-lock"></i> Masuk Portal
                </a>
            @endauth
        </div>

        <!-- Mobile Navigation Actions -->
        <div class="mobile-nav-actions">
            <a href="{{ route('student-status.index') }}" class="mobile-quick-btn" title="Portal Ayah Bunda">
                <i class="fas fa-search"></i>
                <span>Cek Ananda</span>
            </a>
            <button type="button" class="mobile-menu-toggle" id="mobileMenuBtn" aria-label="Buka Menu" onclick="toggleMobileMenu()">
                <i class="fas fa-bars" id="menuIcon"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Drawer Overlay & Sidebar -->
    <div class="mobile-drawer-overlay" id="mobileDrawerOverlay" onclick="closeMobileMenu()"></div>
    <div class="mobile-drawer" id="mobileDrawer">
        <div class="mobile-drawer-header">
            <div class="brand-container">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="brand-logo" style="width: 36px; height: 36px;">
                <div class="brand-text">
                    <span class="brand-title" style="font-size: 1.15rem;">SIMUTQIN</span>
                    <span class="brand-subtitle" style="font-size: 0.65rem;">SDIT AN NADZIR</span>
                </div>
            </div>
            <button type="button" class="mobile-drawer-close" onclick="closeMobileMenu()" aria-label="Tutup Menu">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mobile-drawer-body">
            <div class="mobile-nav-links">
                <a href="#pilar" class="m-nav-link" onclick="closeMobileMenu()">
                    <i class="fas fa-cubes"></i>
                    <span>4 Pilar Pendidikan</span>
                </a>
                <a href="#keunggulan" class="m-nav-link" onclick="closeMobileMenu()">
                    <i class="fas fa-star"></i>
                    <span>Fitur Terpadu</span>
                </a>
                <a href="#statistik" class="m-nav-link" onclick="closeMobileMenu()">
                    <i class="fas fa-chart-pie"></i>
                    <span>Statistik Sekolah</span>
                </a>
                <a href="{{ route('student-status.index') }}" class="m-nav-link m-nav-highlight" onclick="closeMobileMenu()">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-search"></i>
                        <span>Portal Ayah Bunda</span>
                    </div>
                    <span class="m-badge-pill">Mandiri</span>
                </a>
            </div>

            <div class="mobile-drawer-footer">
                @auth
                    <a href="{{ url('/dashboard') }}" class="m-btn-portal">
                        <i class="fas fa-tachometer-alt"></i> Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="m-btn-portal">
                        <i class="fas fa-lock"></i> Masuk Portal Guru & Staf
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-heart" style="color: #059669;"></i> Amanah Pendidikan Terpadu SDIT AN NADZIR
            </div>
            <h1 class="hero-title">
                Mendidik dengan <span class="highlight-green">Hati</span>, Menemani Tumbuh Kembang <span class="highlight-blue">Generasi Qur'ani.</span>
            </h1>
            <p class="hero-desc">
                <strong>SIMUTQIN SDIT AN NADZIR</strong> hadir sebagai jembatan kasih antara sekolah dan keluarga. Memudahkan Asatidz membimbing dengan teliti, serta memberi ketenangan bagi Ayah Bunda untuk memantau kelancaran hafalan Al-Qur'an, pembiasaan akhlak harian, dan capaian prestasi ananda secara mudah dan transparan.
            </p>
            <div class="hero-cta-group">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-hero-primary">
                        <i class="fas fa-tachometer-alt"></i> Buka Dashboard Sistem
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-hero-primary">
                        <i class="fas fa-sign-in-alt"></i> Masuk Portal Guru & Staf
                    </a>
                @endauth
                <a href="{{ route('student-status.index') }}" class="btn-hero-secondary">
                    <i class="fas fa-search" style="color: #0284c7;"></i> Cek Perkembangan Ananda
                </a>
            </div>
        </div>

        <!-- Hero Visual Mockup -->
        <div class="hero-mockup-wrapper">
            <div class="hero-mockup-card">
                <div class="mockup-header">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <img src="{{ asset('logo.png') }}" style="width: 26px; height: 26px; object-fit: contain;" alt="Logo">
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 800; color: #064e3b; line-height: 1.1;">SIMUTQIN DASHBOARD</div>
                            <div style="font-size: 0.68rem; font-weight: 700; color: #0284c7;">SDIT AN NADZIR</div>
                        </div>
                    </div>
                    <div class="mockup-badge-live">
                        <div class="pulse-dot"></div> Live System
                    </div>
                </div>

                <div class="mockup-metrics-grid">
                    <div class="mockup-metric active-theme">
                        <div class="metric-label">Siswa Terbina</div>
                        <div class="metric-val">{{ $totalSiswa }}</div>
                        <div style="font-size: 0.72rem; color: #047857; font-weight: 600; margin-top: 2px;">
                            <i class="fas fa-check-circle"></i> Terdata & Aktif
                        </div>
                    </div>
                    <div class="mockup-metric">
                        <div class="metric-label">Halaqah Qur'an</div>
                        <div class="metric-val" style="color: #0284c7;">{{ $totalKelompok }}</div>
                        <div style="font-size: 0.72rem; color: #0284c7; font-weight: 600; margin-top: 2px;">
                            <i class="fas fa-quran"></i> Tilawati & Tahfidz
                        </div>
                    </div>
                </div>

                <!-- Feed items inside mockup -->
                <div class="mockup-feed" style="background: #f0fdf4; border-color: #bbf7d0;">
                    <div class="mockup-feed-left">
                        <div class="mockup-feed-icon" style="background: #dcfce7; color: #15803d;">
                            <i class="fas fa-quran"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.8rem; font-weight: 800; color: #166534;">Pembinaan Al-Qur'an</div>
                            <div style="font-size: 0.72rem; color: #64748b;">Setoran Tahfidz, Tilawati & Ujian Munaqosah</div>
                        </div>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 800; color: #15803d; background: white; padding: 2px 8px; border-radius: 6px;">Terbina</span>
                </div>

                <div class="mockup-feed" style="background: #fefce8; border-color: #fef08a;">
                    <div class="mockup-feed-left">
                        <div class="mockup-feed-icon" style="background: #fef9c3; color: #a16207;">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.8rem; font-weight: 800; color: #854d0e;">Mutaba'ah Karakter Siswa</div>
                            <div style="font-size: 0.72rem; color: #64748b;">Pembiasaan Shalat, Adab Harian & Kepemimpinan</div>
                        </div>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 800; color: #a16207; background: white; padding: 2px 8px; border-radius: 6px;">Terpantau</span>
                </div>

                <div class="mockup-feed" style="background: #f0f9ff; border-color: #bae6fd;">
                    <div class="mockup-feed-left">
                        <div class="mockup-feed-icon" style="background: #e0f2fe; color: #0369a1;">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.8rem; font-weight: 800; color: #075985;">Kurikulum & Prestasi</div>
                            <div style="font-size: 0.72rem; color: #64748b;">Laporan Capaian Belajar & Ekstrakurikuler</div>
                        </div>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 800; color: #0369a1; background: white; padding: 2px 8px; border-radius: 6px;">Resmi</span>
                </div>
            </div>
        </div>
    </header>

    <!-- 4 Core Pillars Section -->
    <section class="section-pillars" id="pilar">
        <div class="section-header">
            <span class="section-tag">Struktur Ekosistem</span>
            <h2 class="section-title">4 Pilar Utama SIMUTQIN</h2>
            <p class="section-desc">
                Pondasi terpadu untuk memastikan setiap siswa berkembang optimal dalam pemahaman agama, keluhuran budi pekerti, kecakapan akademis, dan potensi bakat.
            </p>
        </div>

        <div class="pillars-grid">
            <!-- Pilar 1: Pembinaan Qur'an -->
            <div class="pillar-card" style="--card-color: #047857;">
                <div class="pillar-icon-box" style="--icon-bg: #ecfdf5; --icon-color: #047857;">
                    <i class="fas fa-quran"></i>
                </div>
                <h3 class="pillar-title">Pembinaan Al-Qur'an</h3>
                <p class="pillar-text">
                    Sistem pemantauan berjenjang untuk pembelajaran Al-Qur'an metode Tilawati dan akselerasi setoran Tahfidz siswa.
                </p>
                <ul class="pillar-features-list">
                    <li><i class="fas fa-check"></i> Mentoring Tilawati Berjilid</li>
                    <li><i class="fas fa-check"></i> Kartu Setoran Hafalan Digital</li>
                    <li><i class="fas fa-check"></i> Presensi Sesi Halaqah & Mutaba'ah</li>
                    <li><i class="fas fa-check"></i> Cetak Rekapitulasi Tahfidz & Tilawati</li>
                </ul>
            </div>

            <!-- Pilar 2: SMILE Karakter -->
            <div class="pillar-card" style="--card-color: #d97706;">
                <div class="pillar-icon-box" style="--icon-bg: #fffbeb; --icon-color: #d97706;">
                    <i class="fas fa-smile-beam"></i>
                </div>
                <h3 class="pillar-title">SMILE Karakter</h3>
                <p class="pillar-text">
                    Pemantauan adab dan akhlak siswa berlandaskan nilai Sholeh, Muslih, Intellectual Curiosity, Leadership, & Environmentally Friendly.
                </p>
                <ul class="pillar-features-list">
                    <li><i class="fas fa-check"></i> Buku Saku Apresiasi & Kebaikan</li>
                    <li><i class="fas fa-check"></i> Pencatatan Kasus & Tindak Lanjut</li>
                    <li><i class="fas fa-check"></i> Neraca Poin Karakter Siswa</li>
                    <li><i class="fas fa-check"></i> Lembar Evaluasi Karakter per Kelas</li>
                </ul>
            </div>

            <!-- Pilar 3: SMILE Kurikulum -->
            <div class="pillar-card" style="--card-color: #0284c7;">
                <div class="pillar-icon-box" style="--icon-bg: #e0f2fe; --icon-color: #0284c7;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="pillar-title">SMILE Kurikulum</h3>
                <p class="pillar-text">
                    Modul penilaian akademik Kurikulum Merdeka yang akuntabel, transparan, dan terintegrasi langsung dengan cetak lembar rapor resmi.
                </p>
                <ul class="pillar-features-list">
                    <li><i class="fas fa-check"></i> Asesmen STS 1, SAS 1, STS 2, SAS 2</li>
                    <li><i class="fas fa-check"></i> Input Nilai Cepat & Capaian Belajar</li>
                    <li><i class="fas fa-check"></i> Rekap Nilai Matriks & Ranking Kelas</li>
                    <li><i class="fas fa-check"></i> Cetak Lembar Rapor A4 Resmi</li>
                </ul>
            </div>

            <!-- Pilar 4: Bakat & Prestasi -->
            <div class="pillar-card" style="--card-color: #8b5cf6;">
                <div class="pillar-icon-box" style="--icon-bg: #f5f3ff; --icon-color: #8b5cf6;">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3 class="pillar-title">Bakat & Prestasi</h3>
                <p class="pillar-text">
                    Penyaluran minat bakat melalui kegiatan ekstrakurikuler serta rekam jejak sertifikat digital prestasi siswa.
                </p>
                <ul class="pillar-features-list">
                    <li><i class="fas fa-check"></i> Pilihan Ekstrakurikuler Mandiri</li>
                    <li><i class="fas fa-check"></i> Presensi Latihan & Penilaian Eskul</li>
                    <li><i class="fas fa-check"></i> Penerbitan Sertifikat Digital Terverifikasi</li>
                    <li><i class="fas fa-check"></i> Database Portofolio Kejuaraan Siswa</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Realtime School Stats Section -->
    <section class="section-stats" id="statistik">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $totalSiswa }}</div>
                <div class="stat-label">Siswa Terdaftar</div>
                <div class="stat-desc">Peserta Didik Aktif SDIT AN NADZIR</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $totalKelompok }}</div>
                <div class="stat-label">Kelompok Qur'an</div>
                <div class="stat-desc">Halaqah Tilawati & Tahfidz</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $totalEskul }}</div>
                <div class="stat-label">Pilihan Eskul</div>
                <div class="stat-desc">Wadah Minat & Bakat Siswa</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $totalGuru }}</div>
                <div class="stat-label">Dewan Guru & Pembina</div>
                <div class="stat-desc">Tenaga Pendidik Profesional</div>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section class="section-features" id="keunggulan">
        <div class="section-header">
            <span class="section-tag">Efisiensi & Akuntabilitas</span>
            <h2 class="section-title">Dirancang untuk Mutu Pendidikan Terbaik</h2>
            <p class="section-desc">
                Teknologi pintar yang menyederhanakan birokrasi dan administrasi sekolah sehingga dewan guru dapat lebih fokus membina siswa.
            </p>
        </div>

        <div class="features-grid">
            <div class="feature-box">
                <div class="feature-box-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="feature-box-content">
                    <h4>Cetak Rapor & Laporan A4 Resmi</h4>
                    <p>Format dokumen cetak ber-KOP resmi SDIT AN NADZIR, siap cetak langsung atau disimpan sebagai file PDF tanpa perlu penyesuaian layout ulang.</p>
                </div>
            </div>

            <div class="feature-box">
                <div class="feature-box-icon" style="background: #ecfdf5; color: #047857;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="feature-box-content">
                    <h4>Hak Akses Berjenjang & Aman</h4>
                    <p>Pemisahan wewenang yang rapi antara Administrator, Kepala Sekolah, Wali Kelas, dan Pembina Kegiatan untuk integritas data sekolah.</p>
                </div>
            </div>

            <div class="feature-box">
                <div class="feature-box-icon" style="background: #fffbeb; color: #d97706;">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="feature-box-content">
                    <h4>Portal Cek Mandiri Siswa</h4>
                    <p>Wali murid dan siswa dapat mengecek status kegiatan, kehadiran, dan capaian pembinaan secara mandiri melalui NIS.</p>
                </div>
            </div>

            <div class="feature-box">
                <div class="feature-box-icon" style="background: #fdf2f8; color: #db2777;">
                    <i class="fas fa-award"></i>
                </div>
                <div class="feature-box-content">
                    <h4>Sertifikat Digital QR Code</h4>
                    <p>Penerbitan sertifikat digital resmi untuk siswa berprestasi yang dilengkapi tautan verifikasi keaslian berbasis QR code.</p>
                </div>
            </div>

            <div class="feature-box">
                <div class="feature-box-icon" style="background: #f5f3ff; color: #7c3aed;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="feature-box-content">
                    <h4>Presensi & Kehadiran Guru</h4>
                    <p>Pencatatan absensi harian dan rekap kehadiran dewan guru dan pembina ekstrakurikuler secara digital dan terpusat.</p>
                </div>
            </div>

            <div class="feature-box">
                <div class="feature-box-icon" style="background: #f0fdf4; color: #16a34a;">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div class="feature-box-content">
                    <h4>Dukungan Aplikasi Mobile PWA</h4>
                    <p>Dapat diinstal langsung di layar utama smartphone Android maupun iOS tanpa membebani penyimpanan perangkat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-cta">
        <div class="cta-banner-card">
            <div class="cta-banner-content">
                <h3>Siap Mewujudkan Manajemen Sekolah Modern?</h3>
                <p>Masuk ke portal SIMUTQIN untuk mulai mengelola data akademik, pembinaan Al-Qur'an, dan pembiasaan karakter siswa.</p>
            </div>
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-hero-primary" style="background: white; color: #0284c7; box-shadow: 0 4px 14px rgba(0,0,0,0.15);">
                        <i class="fas fa-th-large"></i> Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-hero-primary" style="background: white; color: #0284c7; box-shadow: 0 4px 14px rgba(0,0,0,0.15);">
                        <i class="fas fa-lock"></i> Masuk Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <h4>
                    <img src="{{ asset('logo.png') }}" style="width: 32px; height: 32px; object-fit: contain;" alt="Logo">
                    SIMUTQIN SDIT AN NADZIR
                </h4>
                <p>
                    Sistem Informasi Mutaba'ah, Tahfidz, Karakter, dan Integrasi Nilai. Menemani tumbuh kembang generasi Qur'ani yang berakhlak mulia, cerdas, berkarakter unggul, dan siap berprestasi.
                </p>
                <div style="margin-top: 14px; font-size: 0.85rem; color: #cbd5e1;">
                    <i class="fas fa-map-marker-alt" style="color: #38bdf8; margin-right: 6px;"></i> Bendungan Karet Cisirih KP. Cukang RT. 09 RW. 02 Desa Kamasan Kecamatan Cinangka Kabupaten Serang - Banten 42167
                </div>
            </div>

            <div class="footer-col">
                <h5>Pilar Sistem</h5>
                <ul class="footer-links-list">
                    <li><a href="#pilar">Pembinaan Qur'an</a></li>
                    <li><a href="#pilar">SMILE Karakter</a></li>
                    <li><a href="#pilar">SMILE Kurikulum</a></li>
                    <li><a href="#pilar">Ekstrakurikuler</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Navigasi</h5>
                <ul class="footer-links-list">
                    <li><a href="/">Beranda</a></li>
                    <li><a href="#keunggulan">Fitur Utama</a></li>
                    <li><a href="#statistik">Statistik Sekolah</a></li>
                    <li><a href="{{ route('student-status.index') }}">Cek Status Siswa</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Akses Pengguna</h5>
                <ul class="footer-links-list">
                    <li><a href="{{ route('login') }}"><i class="fas fa-lock" style="font-size: 0.75rem;"></i> Login Portal Staf / Guru</a></li>
                    <li><a href="{{ route('guide.index') }}"><i class="fas fa-book" style="font-size: 0.75rem;"></i> Panduan Penggunaan</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div>
                &copy; {{ date('Y') }} <strong>SDIT AN NADZIR</strong> (Najibudin, S.Pd).
            </div>
            <div>
                SIMUTQIN – Menemani Tumbuh Kembang Prestasi & Karakter Qur'ani
            </div>
        </div>
    </footer>

    <script>
        // Mobile Drawer Navigation
        function toggleMobileMenu() {
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('mobileDrawerOverlay');
            if (!drawer || !overlay) return;
            const isOpen = drawer.classList.contains('active');
            if (isOpen) {
                closeMobileMenu();
            } else {
                drawer.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMobileMenu() {
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('mobileDrawerOverlay');
            if (drawer) drawer.classList.remove('active');
            if (overlay) overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMobileMenu();
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 40) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered successfully!', reg))
                    .catch(err => console.log('Service Worker registration failed:', err));
            });
        }
    </script>
</body>
</html>
