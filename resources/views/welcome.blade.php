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
            overflow-x: hidden;
            width: 100%;
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
            width: 100%;
            max-width: 100%;
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
            max-width: 100vw;
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
            border-radius: 14px;
            padding: 13px 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 94px;
            box-sizing: border-box;
            text-align: left;
            transition: all 0.25s ease;
        }

        .mockup-metric.metric-green {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 1px solid #bbf7d0;
        }

        .mockup-metric.metric-blue {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #bae6fd;
        }

        .mockup-metric:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
        }

        .metric-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mockup-metric .metric-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .mockup-metric.metric-green .metric-tag-icon { color: #10b981; font-size: 0.75rem; }
        .mockup-metric.metric-blue .metric-tag-icon { color: #0284c7; font-size: 0.75rem; }

        .mockup-metric .metric-val {
            font-size: 1.55rem;
            font-weight: 900;
            margin: 4px 0 2px;
            line-height: 1.1;
        }

        .mockup-metric.metric-green .metric-val { color: #064e3b; }
        .mockup-metric.metric-blue .metric-val { color: #0369a1; }

        .metric-sub {
            font-size: 0.72rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .mockup-metric.metric-green .metric-sub { color: #047857; }
        .mockup-metric.metric-blue .metric-sub { color: #0284c7; }

        .mockup-feed {
            border-radius: 12px;
            padding: 12px 14px;
            margin-top: 10px;
            border: 1px solid;
            text-align: left;
            transition: all 0.25s ease;
        }

        .mockup-feed.feed-green { background: #f0fdf4; border-color: #bbf7d0; }
        .mockup-feed.feed-amber { background: #fefce8; border-color: #fef08a; }
        .mockup-feed.feed-blue  { background: #f0f9ff; border-color: #bae6fd; }

        .mockup-feed:hover {
            transform: translateX(3px);
        }

        .mockup-feed-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 4px;
        }

        .mockup-feed-brand {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
        }

        .mockup-feed-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .feed-green .mockup-feed-icon { background: #dcfce7; color: #15803d; }
        .feed-amber .mockup-feed-icon { background: #fef9c3; color: #a16207; }
        .feed-blue  .mockup-feed-icon { background: #e0f2fe; color: #0369a1; }

        .mockup-feed-title {
            font-size: 0.82rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .feed-green .mockup-feed-title { color: #166534; }
        .feed-amber .mockup-feed-title { color: #854d0e; }
        .feed-blue  .mockup-feed-title { color: #075985; }

        .mockup-feed-badge {
            font-size: 0.68rem;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 6px;
            background: white;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .feed-green .mockup-feed-badge { color: #15803d; }
        .feed-amber .mockup-feed-badge { color: #a16207; }
        .feed-blue  .mockup-feed-badge { color: #0369a1; }

        .mockup-feed-desc {
            font-size: 0.74rem;
            color: #64748b;
            line-height: 1.4;
            padding-left: 37px;
            white-space: normal;
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
                padding: 0.75rem 16px;
                width: 100%;
                box-sizing: border-box;
            }

            nav.scrolled {
                padding: 0.65rem 16px;
            }

            nav.main-nav .nav-menu {
                display: none;
            }

            .mobile-nav-actions {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .brand-logo {
                width: 36px;
                height: 36px;
            }

            .brand-title {
                font-size: 1.15rem;
                white-space: nowrap;
            }

            .brand-subtitle {
                font-size: 0.64rem;
                white-space: nowrap;
            }

            /* Blobs on mobile */
            .blob-1 {
                width: 280px;
                height: 280px;
                top: -60px;
                left: -60px;
            }

            .blob-2 {
                width: 260px;
                height: 260px;
                top: 8%;
                right: -60px;
            }

            .blob-3 {
                width: 220px;
                height: 220px;
            }

            /* Hero Section on Mobile */
            header.hero-section {
                padding: 92px 16px 40px;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 28px;
                width: 100%;
                box-sizing: border-box;
            }

            .hero-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;
                max-width: 500px;
                margin: 0 auto;
                text-align: center;
            }

            .hero-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 7px;
                font-size: 0.75rem;
                padding: 6px 14px;
                margin: 0 auto 15px;
                max-width: 100%;
                box-sizing: border-box;
                text-align: center;
            }

            .hero-title {
                font-size: 1.85rem;
                line-height: 1.25;
                margin-bottom: 14px;
                text-align: center;
                word-break: break-word;
            }

            .hero-desc {
                font-size: 0.88rem;
                line-height: 1.62;
                margin: 0 auto 22px;
                text-align: center;
                color: #475569;
                max-width: 100%;
            }

            .hero-cta-group {
                display: flex;
                flex-direction: column;
                width: 100%;
                max-width: 360px;
                gap: 10px;
                margin: 0 auto;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 13px 18px;
                font-size: 0.92rem;
                border-radius: 12px;
                box-sizing: border-box;
                text-align: center;
            }

            /* Mockup Card on Mobile - 100% Symmetrical, Zero Cutoff */
            .hero-mockup-wrapper {
                width: 100%;
                max-width: 440px;
                margin: 0 auto;
            }

            .hero-mockup-card {
                padding: 16px 14px;
                border-radius: 18px;
                width: 100%;
                box-sizing: border-box;
                box-shadow: 0 10px 30px rgba(6, 78, 59, 0.08), 0 1px 3px rgba(0, 0, 0, 0.05);
            }

            .mockup-header {
                padding-bottom: 11px;
                margin-bottom: 12px;
            }

            .mockup-metrics-grid {
                gap: 8px;
                margin-bottom: 12px;
            }

            .mockup-metric {
                padding: 11px 11px;
                min-height: 90px;
                border-radius: 12px;
            }

            .mockup-metric .metric-label {
                font-size: 0.68rem;
            }

            .mockup-metric .metric-val {
                font-size: 1.35rem;
                margin: 2px 0 2px;
            }

            .mockup-metric .metric-sub {
                font-size: 0.68rem;
                gap: 4px;
            }

            .mockup-feed {
                padding: 10px 11px;
                margin-top: 8px;
                border-radius: 11px;
            }

            .mockup-feed-header {
                margin-bottom: 3px;
                gap: 8px;
            }

            .mockup-feed-brand {
                gap: 8px;
            }

            .mockup-feed-icon {
                width: 26px;
                height: 26px;
                font-size: 0.74rem;
                border-radius: 7px;
            }

            .mockup-feed-title {
                font-size: 0.8rem;
            }

            .mockup-feed-badge {
                font-size: 0.65rem;
                padding: 2px 7px;
            }

            .mockup-feed-desc {
                font-size: 0.73rem;
                padding-left: 34px;
                line-height: 1.38;
                white-space: normal;
                overflow: visible;
                text-overflow: clip;
            }

            /* 4 Pillars Section on Mobile */
            section.section-pillars {
                padding: 50px 16px;
            }

            .section-header {
                margin-bottom: 30px;
            }

            .section-tag {
                font-size: 0.72rem;
                padding: 4px 12px;
            }

            .section-title {
                font-size: 1.65rem;
                line-height: 1.25;
            }

            .section-desc {
                font-size: 0.88rem;
                line-height: 1.55;
            }

            .pillars-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .pillar-card {
                padding: 20px 16px;
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
                font-size: 0.86rem;
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

            /* Stats Grid 2x2 on Mobile - Perfectly Symmetrical */
            section.section-stats {
                padding: 45px 16px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10px;
            }

            .stat-item {
                background: rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.14);
                border-radius: 14px;
                padding: 14px 10px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                min-height: 105px;
                box-sizing: border-box;
            }

            .stat-number {
                font-size: 1.95rem;
                line-height: 1.1;
            }

            .stat-label {
                font-size: 0.8rem;
                font-weight: 700;
                margin-top: 4px;
            }

            .stat-desc {
                font-size: 0.68rem;
                line-height: 1.35;
                margin-top: 2px;
                opacity: 0.85;
            }

            /* Features Grid on Mobile */
            section.section-features {
                padding: 50px 16px;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .feature-box {
                padding: 16px 14px;
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
                padding: 25px 16px 50px;
            }

            .cta-banner-card {
                padding: 26px 16px;
                border-radius: 16px;
                text-align: center;
                flex-direction: column;
                gap: 18px;
            }

            .cta-banner-content h3 {
                font-size: 1.35rem;
                line-height: 1.25;
            }

            .cta-banner-content p {
                font-size: 0.85rem;
            }

            .cta-banner-card .btn-hero-primary {
                width: 100%;
                max-width: 320px;
                margin: 0 auto;
                justify-content: center;
            }

            /* Footer on Mobile */
            footer.main-footer {
                padding: 40px 16px 20px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 26px;
                margin-bottom: 30px;
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
            header.hero-section {
                padding: 85px 12px 35px;
            }

            .brand-title {
                font-size: 1.05rem;
            }

            .brand-subtitle {
                font-size: 0.6rem;
            }

            .mobile-quick-btn span {
                display: none;
            }

            .mobile-quick-btn {
                padding: 6px 9px;
            }

            .hero-title {
                font-size: 1.65rem;
            }

            .hero-badge {
                font-size: 0.72rem;
                padding: 5px 10px;
            }

            .mockup-metric {
                padding: 9px 8px;
                min-height: 84px;
            }

            .mockup-metric .metric-val {
                font-size: 1.25rem;
            }

            .mockup-feed {
                padding: 8px 9px;
            }

            .mockup-feed-title {
                font-size: 0.78rem;
            }

            .mockup-feed-desc {
                font-size: 0.71rem;
                padding-left: 0;
                margin-top: 4px;
            }

            .stats-grid {
                gap: 8px;
            }

            .stat-number {
                font-size: 1.7rem;
            }

            .stat-item {
                padding: 12px 6px;
                min-height: 95px;
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
                    <div class="mockup-metric metric-green">
                        <div class="metric-top">
                            <span class="metric-label">Siswa Terbina</span>
                            <span class="metric-tag-icon"><i class="fas fa-user-check"></i></span>
                        </div>
                        <div class="metric-val">{{ $totalSiswa }}</div>
                        <div class="metric-sub">
                            <i class="fas fa-check-circle"></i> Terdata & Aktif
                        </div>
                    </div>
                    <div class="mockup-metric metric-blue">
                        <div class="metric-top">
                            <span class="metric-label">Halaqah Qur'an</span>
                            <span class="metric-tag-icon"><i class="fas fa-quran"></i></span>
                        </div>
                        <div class="metric-val">{{ $totalKelompok }}</div>
                        <div class="metric-sub">
                            <i class="fas fa-bookmark"></i> Tilawati & Tahfidz
                        </div>
                    </div>
                </div>

                <!-- Feed items inside mockup -->
                <div class="mockup-feed feed-green">
                    <div class="mockup-feed-header">
                        <div class="mockup-feed-brand">
                            <div class="mockup-feed-icon">
                                <i class="fas fa-quran"></i>
                            </div>
                            <span class="mockup-feed-title">Pembinaan Al-Qur'an</span>
                        </div>
                        <span class="mockup-feed-badge">Terbina</span>
                    </div>
                    <div class="mockup-feed-desc">Setoran Tahfidz, Tilawati & Ujian Munaqosah</div>
                </div>

                <div class="mockup-feed feed-amber">
                    <div class="mockup-feed-header">
                        <div class="mockup-feed-brand">
                            <div class="mockup-feed-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="mockup-feed-title">Mutaba'ah Karakter Siswa</span>
                        </div>
                        <span class="mockup-feed-badge">Terpantau</span>
                    </div>
                    <div class="mockup-feed-desc">Pembiasaan Shalat, Adab Harian & Kepemimpinan</div>
                </div>

                <div class="mockup-feed feed-blue">
                    <div class="mockup-feed-header">
                        <div class="mockup-feed-brand">
                            <div class="mockup-feed-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <span class="mockup-feed-title">Kurikulum & Prestasi</span>
                        </div>
                        <span class="mockup-feed-badge">Resmi</span>
                    </div>
                    <div class="mockup-feed-desc">Laporan Capaian Belajar & Ekstrakurikuler</div>
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
