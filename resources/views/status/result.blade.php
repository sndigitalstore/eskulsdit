<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perkembangan Ananda - {{ $activeStudent->name ?? $student->name }} | SIMUTQIN SDIT AN NADZIR</title>
    
    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-emerald: #047857;
            --primary-teal: #0d9488;
            --emerald-dark: #064e3b;
            --emerald-light: #ecfdf5;
            --accent-amber: #f59e0b;
            --accent-amber-light: #fef3c7;
            --accent-sky: #0284c7;
            --accent-sky-light: #e0f2fe;
            --accent-purple: #7c3aed;
            --accent-purple-light: #ede9fe;
            --slate-dark: #0f172a;
            --slate-body: #334155;
            --slate-muted: #64748b;
            --slate-border: #e2e8f0;
            --bg-page: #f8fafc;
            --card-bg: rgba(255, 255, 255, 0.95);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-page);
            color: var(--slate-body);
            line-height: 1.6;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            padding-bottom: 60px;
        }

        /* Ambient Glowing Background Blobs */
        .ambient-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            z-index: 0;
            pointer-events: none;
            opacity: 0.55;
        }

        .blob-1 {
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, rgba(5, 150, 105, 0.05) 70%);
            top: -100px;
            right: -80px;
            animation: floatOrb 18s ease-in-out infinite alternate;
        }

        .blob-2 {
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.22) 0%, rgba(2, 132, 199, 0.04) 70%);
            bottom: 20%;
            left: -100px;
            animation: floatOrb 22s ease-in-out infinite alternate-reverse;
        }

        .blob-3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.16) 0%, rgba(217, 119, 6, 0.02) 70%);
            top: 45%;
            right: 5%;
            animation: floatOrb 16s ease-in-out infinite alternate;
        }

        @keyframes floatOrb {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(25px) scale(1.05); }
            100% { transform: translateY(-20px) scale(0.97); }
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(22px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Top Navigation Header */
        .top-navbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 12px 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .navbar-container {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .btn-nav-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            background: white;
            border: 1px solid #e2e8f0;
            color: #334155;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .btn-nav-back:hover {
            color: var(--primary-emerald);
            border-color: #a7f3d0;
            transform: translateX(-3px);
            box-shadow: 0 4px 12px rgba(4, 120, 87, 0.1);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
        }

        .nav-brand img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        .nav-brand-title {
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--slate-dark);
            line-height: 1.1;
        }

        .nav-brand-sub {
            font-size: 0.7rem;
            color: var(--primary-emerald);
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 999px;
            background: linear-gradient(135deg, #047857 0%, #0d9488 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            font-family: inherit;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(4, 120, 87, 0.2);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(4, 120, 87, 0.3);
        }

        /* Container */
        .main-container {
            position: relative;
            z-index: 10;
            max-width: 900px;
            margin: 24px auto 0;
            padding: 0 16px;
            animation: cardAppear 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Hero Student Card */
        .student-hero-card {
            background: white;
            border-radius: 24px;
            padding: 28px 24px 22px;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.04), 0 4px 10px -2px rgba(0, 0, 0, 0.02);
            text-align: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 22px;
        }

        .student-hero-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #047857, #0d9488, #0284c7, #f59e0b);
        }

        .avatar-wrapper {
            position: relative;
            width: 86px;
            height: 86px;
            margin: 0 auto 14px;
        }

        .avatar-glow {
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981, #06b6d4);
            opacity: 0.3;
            filter: blur(6px);
        }

        .student-avatar {
            position: relative;
            width: 86px;
            height: 86px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 3px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            color: var(--primary-emerald);
            box-shadow: 0 6px 16px rgba(4, 120, 87, 0.15);
        }

        .student-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--slate-dark);
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .student-badge-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .pill-class {
            background: #ecfdf5;
            color: #047857;
            border-color: #a7f3d0;
        }

        .pill-nis {
            background: #f1f5f9;
            color: #475569;
            border-color: #e2e8f0;
        }

        .pill-contact {
            background: #e0f2fe;
            color: #0369a1;
            border-color: #bae6fd;
        }

        .pill-homeroom {
            background: #fef3c7;
            color: #92400e;
            border-color: #fde68a;
        }

        /* Quick KPI Mini Glance Cards */
        .glance-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px dashed #e2e8f0;
            text-align: left;
        }

        .glance-item {
            background: #f8fafc;
            border-radius: 14px;
            padding: 12px 14px;
            border: 1px solid #edf2f7;
            transition: all 0.2s ease;
        }

        .glance-item:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }

        .glance-icon {
            font-size: 1rem;
            margin-bottom: 6px;
            display: inline-flex;
            width: 28px;
            height: 28px;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .glance-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--slate-muted);
            letter-spacing: 0.4px;
        }

        .glance-val {
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--slate-dark);
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Section Layouts */
        .section-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 6px 20px -3px rgba(0, 0, 0, 0.03);
            margin-bottom: 22px;
            position: relative;
        }

        .section-header-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .sec-title-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sec-icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .sec-h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--slate-dark);
            margin: 0;
            letter-spacing: -0.01em;
        }

        .sec-subtext {
            font-size: 0.78rem;
            color: var(--slate-muted);
            font-weight: 500;
        }

        /* ========================================================
           1. AL-QUR'AN & TAHFIDZ SECTION STYLING (EMERALD)
           ======================================================== */
        .quran-container {
            background: linear-gradient(135deg, #064e3b 0%, #047857 55%, #0d9488 100%);
            border-radius: 24px;
            padding: 26px 24px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 30px -5px rgba(4, 120, 87, 0.28);
            margin-bottom: 22px;
        }

        .quran-watermark {
            position: absolute;
            right: -25px;
            bottom: -30px;
            font-size: 180px;
            color: rgba(255, 255, 255, 0.05);
            pointer-events: none;
            transform: rotate(-10deg);
        }

        .quran-top-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
            margin-bottom: 20px;
        }

        .quran-badge-tag {
            background: rgba(255, 255, 255, 0.16);
            color: #d1fae5;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 4px 12px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(4px);
        }

        .quran-title-h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.55rem;
            font-weight: 800;
            color: #ffffff;
            margin-top: 6px;
            line-height: 1.2;
        }

        .quran-desc {
            font-size: 0.85rem;
            color: #a7f3d0;
            margin-top: 4px;
        }

        /* Dua Kartu Batasan Terakhir (Tahfidz & Tilawati) */
        .batasan-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            position: relative;
            z-index: 2;
            margin-bottom: 20px;
        }

        .batasan-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 18px;
            padding: 16px 18px;
            transition: all 0.25s ease;
        }

        .batasan-card:hover {
            background: rgba(255, 255, 255, 0.17);
            border-color: rgba(255, 255, 255, 0.35);
        }

        .batasan-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .batasan-type-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #fef08a;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .batasan-target-tag {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .batasan-main-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: white;
            line-height: 1.2;
            margin-bottom: 4px;
        }

        .batasan-sub-detail {
            font-size: 0.8rem;
            color: #d1fae5;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .batasan-mentor {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* KPI Row Tahfidz */
        .quran-kpi-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            position: relative;
            z-index: 2;
            margin-bottom: 18px;
        }

        .quran-kpi-box {
            background: rgba(0, 0, 0, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            padding: 12px;
            text-align: center;
        }

        .quran-kpi-num {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: white;
            line-height: 1.1;
        }

        .quran-kpi-desc {
            font-size: 0.68rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.4px;
            opacity: 0.85;
            margin-top: 2px;
        }

        /* Riwayat Setoran Feed */
        .recent-setoran-box {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 16px;
            padding: 14px 16px;
            position: relative;
            z-index: 2;
        }

        .recent-setoran-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #d1fae5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .setoran-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .setoran-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 10px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
        }

        .setoran-item:hover {
            background: rgba(255, 255, 255, 0.16);
        }

        .setoran-surah {
            font-weight: 700;
            font-size: 0.9rem;
            color: white;
        }

        .setoran-meta {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 2px;
            display: flex;
            gap: 6px;
            align-items: center;
            flex-wrap: wrap;
        }

        .quality-badge {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 3px 9px;
            border-radius: 999px;
            letter-spacing: 0.4px;
            white-space: nowrap;
        }

        /* Sertifikat Munaqosah Banner */
        .munaqosah-banner {
            margin-top: 14px;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.25) 0%, rgba(217, 119, 6, 0.3) 100%);
            border: 1px solid rgba(253, 230, 138, 0.4);
            border-radius: 14px;
            padding: 12px 16px;
            position: relative;
            z-index: 2;
        }

        .btn-certificate {
            background: #f59e0b;
            color: #78350f;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .btn-certificate:hover {
            background: #fbbf24;
            transform: translateY(-1px);
        }

        /* ========================================================
           2. KARAKTER QUR'ANI (MUTABA'AH & APRESIASI)
           ======================================================== */
        .character-card {
            border-left: 4px solid var(--accent-purple);
        }

        .character-stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 18px;
        }

        .char-stat-box {
            background: #faf5ff;
            border: 1px solid #f3e8ff;
            border-radius: 14px;
            padding: 12px 14px;
            text-align: center;
        }

        .char-stat-num {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--accent-purple);
        }

        .char-stat-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #6b21a8;
            margin-top: 2px;
        }

        .appreciation-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .appreciation-item {
            background: #fdf4ff;
            border: 1px solid #fae8ff;
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .appreciation-item:hover {
            background: white;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.06);
            border-color: #e9d5ff;
        }

        .appr-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: #581c87;
        }

        .appr-meta {
            font-size: 0.75rem;
            color: var(--slate-muted);
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .appr-desc {
            font-size: 0.8rem;
            color: #475569;
            margin-top: 6px;
            line-height: 1.45;
        }

        .star-point-badge {
            background: #fef08a;
            color: #854d0e;
            font-weight: 800;
            font-size: 0.78rem;
            padding: 4px 10px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        /* Cases Pembinaan Edukatif */
        .case-item {
            background: #fff1f2;
            border: 1px solid #ffe4e6;
            border-radius: 14px;
            padding: 14px 16px;
            margin-top: 10px;
        }

        .case-title {
            font-weight: 700;
            font-size: 0.9rem;
            color: #9f1239;
        }

        .case-note {
            font-size: 0.78rem;
            color: #881337;
            margin-top: 4px;
        }

        /* ========================================================
           3. PERKEMBANGAN SISWA & AKADEMIK
           ======================================================== */
        .academic-card {
            border-left: 4px solid var(--accent-sky);
        }

        .homeroom-note-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #bae6fd;
            border-radius: 16px;
            padding: 18px 20px;
            margin-bottom: 18px;
            position: relative;
        }

        .homeroom-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            flex-wrap: wrap;
            gap: 6px;
        }

        .homeroom-sender {
            font-size: 0.82rem;
            font-weight: 700;
            color: #0369a1;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .homeroom-content {
            font-size: 0.9rem;
            color: #1e293b;
            font-style: italic;
            line-height: 1.5;
        }

        .attendance-summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 14px;
        }

        .att-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px;
            text-align: center;
        }

        .att-num {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
        }

        .att-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--slate-muted);
        }

        /* ========================================================
           4. PRESTASI & KEJUARAAN (GOLD)
           ======================================================== */
        .prestasi-card {
            border-left: 4px solid var(--accent-amber);
            background: linear-gradient(180deg, #fffbeb 0%, #ffffff 100%);
        }

        .achievement-item {
            background: white;
            border: 1px solid #fef3c7;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.05);
            transition: all 0.2s ease;
        }

        .achievement-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.12);
        }

        .medal-badge {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #b45309;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            border: 1px solid #fcd34d;
        }

        .achieve-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            color: #78350f;
            line-height: 1.25;
        }

        .achieve-meta {
            font-size: 0.78rem;
            color: #92400e;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .achieve-desc {
            font-size: 0.82rem;
            color: #64748b;
            margin-top: 6px;
            line-height: 1.45;
        }

        /* ========================================================
           5. EKSTRAKURIKULER & TIMELINE
           ======================================================== */
        .timeline {
            position: relative;
            padding-left: 24px;
            border-left: 2px solid #e2e8f0;
            margin-left: 8px;
            margin-top: 10px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 26px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -31px;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #cbd5e1;
            border: 3px solid var(--bg-page);
        }

        .timeline-item.active::before {
            background: var(--primary-emerald);
            box-shadow: 0 0 0 4px rgba(4, 120, 87, 0.2);
        }

        .year-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .year-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--slate-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .active-year-badge {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            padding: 2px 10px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .class-badge {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 3px 12px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .eskul-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px 18px;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }

        .eskul-box:hover {
            background: white;
            box-shadow: 0 4px 14px rgba(0,0,0,0.03);
            border-color: #cbd5e1;
        }

        .eskul-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .eskul-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--slate-dark);
        }

        .instructor {
            font-size: 0.78rem;
            color: var(--slate-muted);
            margin-top: 2px;
        }

        .eskul-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }

        .eskul-stat-mini {
            background: white;
            border: 1px solid #edf2f7;
            border-radius: 10px;
            padding: 8px 6px;
            text-align: center;
        }

        .eskul-stat-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .eskul-stat-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--slate-muted);
        }

        .eskul-grades-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            padding-top: 10px;
            border-top: 1px dashed #e2e8f0;
        }

        .grade-card-mini {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 12px;
            text-align: center;
        }

        .grade-card-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--slate-muted);
            text-transform: uppercase;
        }

        .grade-card-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-emerald);
            margin-top: 2px;
        }

        /* Empty State */
        .empty-placeholder {
            text-align: center;
            padding: 28px 20px;
            background: #f8fafc;
            border-radius: 14px;
            border: 1px dashed #cbd5e1;
            color: var(--slate-muted);
        }

        .empty-placeholder i {
            font-size: 32px;
            margin-bottom: 8px;
            opacity: 0.6;
        }

        /* Footer */
        .page-footer {
            text-align: center;
            margin-top: 36px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 0.8rem;
            color: var(--slate-muted);
        }

        .page-footer strong {
            color: var(--slate-body);
        }

        /* Print Media Styles */
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                color: black !important;
            }
            .ambient-blob, .top-navbar, .btn-print, .btn-nav-back {
                display: none !important;
            }
            .main-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .student-hero-card, .section-card, .quran-container {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
                page-break-inside: avoid;
            }
            .quran-container {
                background: #f0fdf4 !important;
                color: #064e3b !important;
            }
            .quran-title-h2, .quran-kpi-num, .batasan-main-val, .setoran-surah {
                color: #064e3b !important;
            }
            .quran-desc, .batasan-sub-detail, .setoran-meta {
                color: #166534 !important;
            }
            .batasan-card, .quran-kpi-box, .recent-setoran-box, .setoran-item {
                background: white !important;
                border: 1px solid #d1fae5 !important;
            }
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            .glance-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .batasan-grid {
                grid-template-columns: 1fr;
            }
            .quran-kpi-row {
                grid-template-columns: repeat(2, 1fr);
            }
            .character-stats-row {
                grid-template-columns: 1fr;
            }
            .attendance-summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .student-title {
                font-size: 1.4rem;
            }
            .main-container {
                padding: 0 12px;
            }
            .section-card {
                padding: 18px 16px;
            }
            .quran-container {
                padding: 20px 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Glowing Background Elements -->
    <div class="ambient-blob blob-1"></div>
    <div class="ambient-blob blob-2"></div>
    <div class="ambient-blob blob-3"></div>

    <!-- Top Sticky Navigation -->
    <header class="top-navbar">
        <div class="navbar-container">
            <a href="{{ route('student-status.index') }}" class="btn-nav-back">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Pencarian</span>
            </a>

            <div class="nav-brand">
                <img src="{{ asset('logo.png') }}" alt="Logo SDIT AN NADZIR">
                <div>
                    <div class="nav-brand-title">SIMUTQIN</div>
                    <div class="nav-brand-sub">SDIT AN NADZIR</div>
                </div>
            </div>

            <button type="button" class="btn-print" onclick="window.print()">
                <i class="fas fa-print"></i>
                <span>Cetak Lembar</span>
            </button>
        </div>
    </header>

    <!-- Main Container -->
    <main class="main-container">

        <!-- ========================================== -->
        <!-- STUDENT HERO PROFILE CARD                  -->
        <!-- ========================================== -->
        <div class="student-hero-card">
            <div class="avatar-wrapper">
                <div class="avatar-glow"></div>
                <div class="student-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>

            <h1 class="student-title">{{ $activeStudent->name ?? $student->name }}</h1>

            <div class="student-badge-row">
                <span class="meta-pill pill-class">
                    <i class="fas fa-school"></i> Kelas: {{ $activeStudent->class ?? ($student->class ?? '-') }}
                </span>

                @if(!empty($student->nis))
                    <span class="meta-pill pill-nis">
                        <i class="fas fa-id-card"></i> NIS: {{ $student->nis }}
                    </span>
                @endif

                @if(!empty($student->masked_parent_phone))
                    <span class="meta-pill pill-contact" title="Nomor disamarkan demi privasi">
                        <i class="fas fa-shield-alt"></i> Kontak Wali: {{ $student->masked_parent_phone }}
                    </span>
                @endif

                @if(!empty($academicData['homeroom_teacher']))
                    <span class="meta-pill pill-homeroom">
                        <i class="fas fa-chalkboard-teacher"></i> Wali Kelas: {{ $academicData['homeroom_teacher']->name }}
                    </span>
                @endif
            </div>

            <!-- Quick Glance 4 Metrics -->
            <div class="glance-grid">
                <div class="glance-item">
                    <div class="glance-icon" style="background: #ecfdf5; color: #047857;">
                        <i class="fas fa-quran"></i>
                    </div>
                    <div class="glance-label">Batasan Tahfidz</div>
                    <div class="glance-val" title="{{ $quranData['latest_record'] ? $quranData['latest_record']->surah_name : ($quranData['tahfidz_group']->target_level ?? 'Juz 30') }}">
                        @if($quranData['latest_record'])
                            {{ $quranData['latest_record']->surah_name }}
                        @elseif($quranData['tahfidz_group'])
                            {{ $quranData['tahfidz_group']->target_level ?? 'Juz 30' }}
                        @else
                            Dalam Proses
                        @endif
                    </div>
                </div>

                <div class="glance-item">
                    <div class="glance-icon" style="background: #fef3c7; color: #b45309;">
                        <i class="fas fa-book-open-reader"></i>
                    </div>
                    <div class="glance-label">Batasan Tilawati</div>
                    <div class="glance-val" title="{{ $quranData['tilawati_pivot']->notes ?? ($quranData['tilawati_group']->target_level ?? 'Tilawati') }}">
                        {{ $quranData['tilawati_pivot']->notes ?? ($quranData['tilawati_group']->target_level ?? 'Tahap Pemetaan') }}
                    </div>
                </div>

                <div class="glance-item">
                    <div class="glance-icon" style="background: #ede9fe; color: #7c3aed;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="glance-label">Karakter Qur'ani</div>
                    <div class="glance-val">
                        {{ $characterData['total_points'] > 0 ? $characterData['total_points'] . ' Bintang' : ($characterData['total_appreciations'] > 0 ? $characterData['total_appreciations'] . ' Apresiasi' : 'Aktif & Tertib') }}
                    </div>
                </div>

                <div class="glance-item">
                    <div class="glance-icon" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="glance-label">Prestasi / Kejuaraan</div>
                    <div class="glance-val">
                        {{ $achievements->count() > 0 ? $achievements->count() . ' Kejuaraan' : 'Belajar Tekun' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 1. CAPAIAN AL-QUR'AN (TAHFIDZ & TILAWATI)  -->
        <!-- ========================================== -->
        <div class="quran-container">
            <i class="fas fa-quran quran-watermark"></i>

            <div class="quran-top-info">
                <div>
                    <span class="quran-badge-tag">
                        <i class="fas fa-book-quran"></i> Program Utama Pembiasaan Qur'ani
                    </span>
                    <h2 class="quran-title-h2">Capaian Tahfidz & Tilawati</h2>
                    <div class="quran-desc">
                        Laporan perkembangan capaian hafalan dan tartil Al-Qur'an ananda di SDIT AN NADZIR.
                    </div>
                </div>

                @if($quranData['tahfidz_group'])
                    <div style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); border-radius: 14px; padding: 10px 14px; backdrop-filter: blur(6px); text-align: right;">
                        <div style="font-size: 0.68rem; text-transform: uppercase; font-weight: 700; color: #fef08a;">Halaqah Aktif</div>
                        <div style="font-weight: 800; font-size: 0.95rem; color: #ffffff;">{{ $quranData['tahfidz_group']->name }}</div>
                        <div style="font-size: 0.72rem; opacity: 0.9;">Musyrif: {{ $quranData['tahfidz_group']->mentor_name ?? '-' }}</div>
                    </div>
                @endif
            </div>

            <!-- Dua Kartu Batasan Terakhir -->
            <div class="batasan-grid">
                
                <!-- Batasan Terakhir Tahfidz -->
                <div class="batasan-card">
                    <div class="batasan-header">
                        <span class="batasan-type-title">
                            <i class="fas fa-bookmark"></i> Batasan Terakhir Tahfidz
                        </span>
                        @if($quranData['latest_record'])
                            <span class="batasan-target-tag" style="background: #10b981;">
                                {{ $quranData['latest_record']->type === 'setoran_baru' ? 'Hafalan Baru' : 'Muroja\'ah' }}
                            </span>
                        @else
                            <span class="batasan-target-tag">Target: {{ $quranData['tahfidz_group']->target_level ?? 'Juz 30' }}</span>
                        @endif
                    </div>

                    @if($quranData['latest_record'])
                        <div class="batasan-main-val">
                            QS. {{ $quranData['latest_record']->surah_name }}
                        </div>
                        <div class="batasan-sub-detail">
                            <span>
                                @if($quranData['latest_record']->start_ayah)
                                    Ayat {{ $quranData['latest_record']->start_ayah }}{{ $quranData['latest_record']->end_ayah && $quranData['latest_record']->end_ayah != $quranData['latest_record']->start_ayah ? ' - ' . $quranData['latest_record']->end_ayah : '' }}
                                @else
                                    Juz {{ $quranData['latest_record']->juz ?? '30' }}
                                @endif
                            </span>
                            <span>•</span>
                            <span style="color: #fef08a; font-weight: 700;">
                                <i class="fas fa-check-circle"></i> {{ ucfirst($quranData['latest_record']->quality) }}
                            </span>
                        </div>
                    @else
                        <div class="batasan-main-val" style="font-size: 1.15rem;">
                            {{ $quranData['tahfidz_pivot']->notes ?? 'Dalam Bimbingan Halaqah' }}
                        </div>
                        <div class="batasan-sub-detail">
                            Target Level: {{ $quranData['tahfidz_group']->target_level ?? 'Juz 30 (Juz \'Amma)' }}
                        </div>
                    @endif

                    <div class="batasan-mentor">
                        <i class="fas fa-user-tie"></i>
                        <span>Pembimbing: <strong>{{ $quranData['tahfidz_group']->mentor_name ?? 'Musyrif Halaqah' }}</strong></span>
                        @if($quranData['latest_record'] && $quranData['latest_record']->session_date)
                            <span style="margin-left: auto; opacity: 0.85;">
                                {{ \Carbon\Carbon::parse($quranData['latest_record']->session_date)->translatedFormat('d M Y') }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Batasan Terakhir Tilawati -->
                <div class="batasan-card">
                    <div class="batasan-header">
                        <span class="batasan-type-title">
                            <i class="fas fa-book-open"></i> Batasan Terakhir Tilawati
                        </span>
                        <span class="batasan-target-tag" style="background: #0284c7;">
                            {{ $quranData['tilawati_group']->target_level ?? 'Tilawati' }}
                        </span>
                    </div>

                    <div class="batasan-main-val">
                        {{ $quranData['tilawati_pivot']->notes ?? ($quranData['tilawati_group']->target_level ?? 'Tahap Pemetaan Jilid') }}
                    </div>

                    <div class="batasan-sub-detail">
                        <span>Kelompok: {{ $quranData['tilawati_group']->name ?? 'Bimbingan Tilawati' }}</span>
                        <span>•</span>
                        <span style="color: #a7f3d0;">Tartil & Tajwid</span>
                    </div>

                    <div class="batasan-mentor">
                        <i class="fas fa-user-graduate"></i>
                        <span>Ustadz/Ustadzah: <strong>{{ $quranData['tilawati_group']->mentor_name ?? 'Guru Tilawati' }}</strong></span>
                    </div>
                </div>

            </div>

            <!-- KPI Row Hafalan -->
            <div class="quran-kpi-row">
                <div class="quran-kpi-box">
                    <div class="quran-kpi-num">{{ $quranData['total_records'] }}</div>
                    <div class="quran-kpi-desc">Total Setoran</div>
                </div>
                <div class="quran-kpi-box">
                    <div class="quran-kpi-num" style="color: #a7f3d0;">{{ $quranData['total_ziyadah'] }}</div>
                    <div class="quran-kpi-desc">Hafalan Baru (Ziyadah)</div>
                </div>
                <div class="quran-kpi-box">
                    <div class="quran-kpi-num" style="color: #fde047;">{{ $quranData['total_murojaah'] }}</div>
                    <div class="quran-kpi-desc">Muroja'ah</div>
                </div>
                <div class="quran-kpi-box">
                    <div class="quran-kpi-num" style="color: #6ee7b7;">{{ $quranData['lancar_rate'] }}%</div>
                    <div class="quran-kpi-desc">Tingkat Mutqin</div>
                </div>
            </div>

            <!-- Riwayat 5 Setoran Terakhir -->
            @if($quranData['recent_records']->isNotEmpty())
                <div class="recent-setoran-box">
                    <div class="recent-setoran-title">
                        <span><i class="fas fa-history"></i> Riwayat Setoran Terakhir</span>
                        <span style="font-size: 0.68rem; font-weight: normal; opacity: 0.85;">Pencatatan langsung oleh Musyrif</span>
                    </div>

                    <div class="setoran-list">
                        @foreach($quranData['recent_records'] as $rec)
                            <div class="setoran-item">
                                <div>
                                    <div class="setoran-surah">
                                        {{ $rec->surah_name }}
                                        <span style="font-size: 0.78rem; opacity: 0.9; font-weight: normal;">
                                            @if($rec->start_ayah)
                                                (Ayat {{ $rec->start_ayah }}{{ $rec->end_ayah && $rec->end_ayah != $rec->start_ayah ? '-' . $rec->end_ayah : '' }})
                                            @endif
                                        </span>
                                    </div>
                                    <div class="setoran-meta">
                                        <span>{{ \Carbon\Carbon::parse($rec->session_date)->translatedFormat('d M Y') }}</span>
                                        <span>•</span>
                                        <span>{{ $rec->type === 'setoran_baru' ? 'Ziyadah (Baru)' : 'Muroja\'ah' }}</span>
                                        @if($rec->notes)
                                            <span>•</span>
                                            <span style="color: #fef08a; font-style: italic;">"{{ $rec->notes }}"</span>
                                        @endif
                                    </div>
                                </div>

                                @php
                                    $qualityBg = match($rec->quality) {
                                        'lancar' => '#10b981',
                                        'cukup'  => '#f59e0b',
                                        default  => '#ef4444',
                                    };
                                @endphp
                                <span class="quality-badge" style="background: {{ $qualityBg }}; color: white;">
                                    {{ $rec->quality }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Sertifikat Munaqosah Jika Ada -->
            @if($quranData['munaqosah_list']->isNotEmpty())
                <div class="munaqosah-banner">
                    <div style="font-size: 0.82rem; font-weight: 700; color: #fef08a; display: flex; align-items: center; gap: 6px; margin-bottom: 8px;">
                        <i class="fas fa-award"></i> Sertifikat Munaqosah Al-Qur'an Yang Telah Diraih:
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        @foreach($quranData['munaqosah_list'] as $mun)
                            <div style="background: rgba(255,255,255,0.18); padding: 8px 12px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                                <div>
                                    <strong style="color: white; font-size: 0.88rem;">Lulus Munaqosah {{ ucfirst($mun->type) }} – {{ $mun->target_level }}</strong>
                                    <div style="font-size: 0.72rem; color: #fef08a;">
                                        Predikat: <strong>{{ $mun->predicate ?? 'Mumtaz' }}</strong> (Nilai: {{ $mun->final_score ?? '-' }})
                                        • Tanggal: {{ $mun->exam_date ? \Carbon\Carbon::parse($mun->exam_date)->translatedFormat('d F Y') : '-' }}
                                    </div>
                                </div>
                                <a href="{{ route('munaqosah.print-certificate', $mun->id) }}" target="_blank" class="btn-certificate">
                                    <i class="fas fa-file-pdf"></i> Lihat Sertifikat
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- ========================================== -->
        <!-- 2. PERKEMBANGAN KARAKTER QUR'ANI           -->
        <!-- ========================================== -->
        <div class="section-card character-card">
            <div class="section-header-title">
                <div class="sec-title-left">
                    <div class="sec-icon-badge" style="background: #ede9fe; color: #7c3aed;">
                        <i class="fas fa-heart-pulse"></i>
                    </div>
                    <div>
                        <h3 class="sec-h3">Pembinaan & Karakter Qur'ani</h3>
                        <div class="sec-subtext">Catatan mutaba'ah adab, apresiasi bintang kebaikan, dan pembiasaan karakter santri.</div>
                    </div>
                </div>
            </div>

            <!-- Rekap Karakter -->
            <div class="character-stats-row">
                <div class="char-stat-box">
                    <div class="char-stat-num">{{ $characterData['total_points'] > 0 ? $characterData['total_points'] : $characterData['total_appreciations'] }}</div>
                    <div class="char-stat-label">Bintang Kebaikan Diraih</div>
                </div>
                <div class="char-stat-box" style="background: #ecfdf5; border-color: #d1fae5;">
                    <div class="char-stat-num" style="color: #047857;">{{ $characterData['resolved_cases'] }}</div>
                    <div class="char-stat-label" style="color: #065f46;">Pembinaan Tuntas</div>
                </div>
                <div class="char-stat-box" style="background: #f0fdf4; border-color: #bbf7d0;">
                    <div class="char-stat-num" style="color: #16a34a;"><i class="fas fa-smile"></i></div>
                    <div class="char-stat-label" style="color: #15803d;">Status Adab & Disiplin</div>
                </div>
            </div>

            <!-- Daftar Apresiasi Karakter -->
            @if($characterData['appreciations']->isNotEmpty())
                <div style="font-weight: 700; font-size: 0.85rem; color: #475569; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-star" style="color: #f59e0b;"></i> Apresiasi Bintang Kebaikan & Akhlak Santri:
                </div>
                <div class="appreciation-grid">
                    @foreach($characterData['appreciations'] as $appr)
                        <div class="appreciation-item">
                            <div>
                                <div class="appr-title">{{ $appr->title }}</div>
                                <div class="appr-meta">
                                    <span style="background: #f3e8ff; color: #6b21a8; padding: 2px 8px; border-radius: 999px; font-weight: 700; font-size: 0.7rem;">
                                        {{ $appr->category }}
                                    </span>
                                    <span>•</span>
                                    <span>{{ \Carbon\Carbon::parse($appr->date)->translatedFormat('d M Y') }}</span>
                                    @if($appr->given_by)
                                        <span>•</span>
                                        <span>Oleh: {{ $appr->given_by }}</span>
                                    @endif
                                </div>
                                @if($appr->description)
                                    <div class="appr-desc">{{ $appr->description }}</div>
                                @endif
                            </div>
                            <div class="star-point-badge">
                                <i class="fas fa-star"></i> +{{ $appr->point ?? 1 }} Poin
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-placeholder">
                    <i class="fas fa-star" style="color: #cbd5e1;"></i>
                    <p style="font-size: 0.85rem; font-weight: 500;">Ananda tercatat senantiasa tertib dan aktif mengikuti pembiasaan adab Qur'ani sehari-hari di sekolah.</p>
                </div>
            @endif

            <!-- Catatan Pembinaan Disiplin (Jika Ada) -->
            @if($characterData['cases']->isNotEmpty())
                <div style="margin-top: 18px; padding-top: 14px; border-top: 1px dashed #e2e8f0;">
                    <div style="font-weight: 700; font-size: 0.82rem; color: #991b1b; display: flex; align-items: center; gap: 6px; margin-bottom: 8px;">
                        <i class="fas fa-info-circle"></i> Catatan Pendampingan Bersama (Kerjasama Sekolah & Orang Tua):
                    </div>
                    @foreach($characterData['cases'] as $case)
                        <div class="case-item">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
                                <div class="case-title">{{ $case->title }}</div>
                                <span style="font-size: 0.7rem; font-weight: 700; background: {{ $case->status === 'selesai' ? '#dcfce7' : '#fee2e2' }}; color: {{ $case->status === 'selesai' ? '#15803d' : '#991b1b' }}; padding: 2px 8px; border-radius: 999px;">
                                    {{ $case->status === 'selesai' ? 'Selesai Dibina' : 'Dalam Pendampingan' }}
                                </span>
                            </div>
                            <div class="case-note">
                                {{ $case->action_taken ?? ($case->description ?? 'Telah dilakukan bimbingan dan arahan secara persuasif.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- ========================================== -->
        <!-- 3. PERKEMBANGAN SISWA & AKADEMIK           -->
        <!-- ========================================== -->
        <div class="section-card academic-card">
            <div class="section-header-title">
                <div class="sec-title-left">
                    <div class="sec-icon-badge" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div>
                        <h3 class="sec-h3">Perkembangan Belajar & Kehadiran</h3>
                        <div class="sec-subtext">Informasi catatan perkembangan kelas, kehadiran belajar formal, dan catatan wali kelas.</div>
                    </div>
                </div>
            </div>

            <!-- Catatan Wali Kelas -->
            @if(!empty($academicData['latest_summary']) && !empty($academicData['latest_summary']->homeroom_notes))
                <div class="homeroom-note-box">
                    <div class="homeroom-header">
                        <div class="homeroom-sender">
                            <i class="fas fa-comment-dots"></i> Catatan Wali Kelas untuk Ayah & Bunda:
                        </div>
                        @if($academicData['homeroom_teacher'])
                            <div style="font-size: 0.72rem; color: #0369a1; font-weight: 600;">
                                {{ $academicData['homeroom_teacher']->name }}
                            </div>
                        @endif
                    </div>
                    <div class="homeroom-content">
                        "{{ $academicData['latest_summary']->homeroom_notes }}"
                    </div>
                </div>
            @endif

            <!-- Ringkasan Kehadiran Belajar Formal -->
            @if(!empty($academicData['latest_summary']))
                <div style="font-weight: 700; font-size: 0.82rem; color: #475569; margin-bottom: 8px;">
                    <i class="fas fa-calendar-check"></i> Rekap Kehadiran Belajar Semester Ini:
                </div>
                <div class="attendance-summary-grid">
                    <div class="att-item">
                        <div class="att-num" style="color: #047857;">
                            {{ max(0, 100 - ($academicData['latest_summary']->sick_count + $academicData['latest_summary']->permission_count + $academicData['latest_summary']->unexcused_count)) }}%
                        </div>
                        <div class="att-label">Tingkat Disiplin</div>
                    </div>
                    <div class="att-item">
                        <div class="att-num" style="color: #f59e0b;">{{ $academicData['latest_summary']->sick_count ?? 0 }}</div>
                        <div class="att-label">Sakit</div>
                    </div>
                    <div class="att-item">
                        <div class="att-num" style="color: #0284c7;">{{ $academicData['latest_summary']->permission_count ?? 0 }}</div>
                        <div class="att-label">Izin</div>
                    </div>
                    <div class="att-item">
                        <div class="att-num" style="color: #ef4444;">{{ $academicData['latest_summary']->unexcused_count ?? 0 }}</div>
                        <div class="att-label">Tanpa Ket.</div>
                    </div>
                </div>
            @else
                <div class="empty-placeholder">
                    <i class="fas fa-clipboard-user" style="color: #cbd5e1;"></i>
                    <p style="font-size: 0.85rem; font-weight: 500;">Ananda aktif mengikuti kegiatan belajar mengajar di kelas {{ $activeStudent->class ?? $student->class }}.</p>
                </div>
            @endif
        </div>

        <!-- ========================================== -->
        <!-- 4. PRESTASI & KEJUARAAN (SEMUA TAHUN)       -->
        <!-- ========================================== -->
        @if($achievements->isNotEmpty())
            <div class="section-card prestasi-card">
                <div class="section-header-title">
                    <div class="sec-title-left">
                        <div class="sec-icon-badge" style="background: #fef3c7; color: #b45309;">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div>
                            <h3 class="sec-h3">Prestasi & Kejuaraan Ananda</h3>
                            <div class="sec-subtext">Kebanggaan prestasi akademik dan non-akademik ananda selama menempuh pendidikan di SDIT AN NADZIR.</div>
                        </div>
                    </div>
                </div>

                <div class="achievement-list">
                    @foreach($achievements as $achievement)
                        <div class="achievement-item">
                            <div class="medal-badge">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div style="flex: 1;">
                                <div class="achieve-name">{{ $achievement->name }}</div>
                                <div class="achieve-meta">
                                    <span style="background: #fef3c7; color: #b45309; padding: 2px 8px; border-radius: 999px; font-weight: 700; font-size: 0.72rem;">
                                        {{ $achievement->level }}
                                    </span>
                                    <span>•</span>
                                    <span>{{ \Carbon\Carbon::parse($achievement->date)->translatedFormat('d F Y') }}</span>
                                </div>
                                @if($achievement->description)
                                    <div class="achieve-desc">
                                        {{ $achievement->description }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- 5. RIWAYAT EKSTRAKURIKULER & MINAT BAKAT    -->
        <!-- ========================================== -->
        <div class="section-card">
            <div class="section-header-title">
                <div class="sec-title-left">
                    <div class="sec-icon-badge" style="background: #f1f5f9; color: #334155;">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div>
                        <h3 class="sec-h3">Riwayat Ekstrakurikuler & Minat Bakat</h3>
                        <div class="sec-subtext">Rekam jejak keikutsertaan ekskul, kehadiran latihan, dan penilaian sumatif akhir semester.</div>
                    </div>
                </div>
            </div>

            @if(count($historyData) > 0)
                <div class="timeline">
                    @foreach($historyData as $yearGroup)
                        <div class="timeline-item {{ $yearGroup['is_active'] ? 'active' : '' }}">
                            
                            <!-- Year Header -->
                            <div class="year-header">
                                <div class="year-title">
                                    <i class="fas fa-calendar-check" style="color: {{ $yearGroup['is_active'] ? 'var(--primary-emerald)' : '#94a3b8' }};"></i>
                                    <span>TA {{ $yearGroup['year_name'] }}</span>
                                    @if($yearGroup['is_active'])
                                        <span class="active-year-badge">Tahun Ajaran Aktif</span>
                                    @endif
                                </div>
                                <span class="class-badge">Kelas {{ $yearGroup['student_class'] }}</span>
                            </div>

                            <!-- Eskul Cards -->
                            @foreach($yearGroup['eskul_data'] as $data)
                                <div class="eskul-box">
                                    <div class="eskul-top">
                                        <div>
                                            <div class="eskul-name">{{ $data['eskul_name'] }}</div>
                                            <div class="instructor">
                                                <i class="fas fa-chalkboard-teacher"></i> Pembina: <strong>{{ $data['instructor'] ?? 'Pembina Eskul' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kehadiran Eskul -->
                                    <div class="eskul-stats-grid">
                                        <div class="eskul-stat-mini">
                                            <div class="eskul-stat-val" style="color: #047857;">{{ $data['attendance']['H'] }}</div>
                                            <div class="eskul-stat-label">Hadir</div>
                                        </div>
                                        <div class="eskul-stat-mini">
                                            <div class="eskul-stat-val" style="color: #f59e0b;">{{ $data['attendance']['S'] }}</div>
                                            <div class="eskul-stat-label">Sakit</div>
                                        </div>
                                        <div class="eskul-stat-mini">
                                            <div class="eskul-stat-val" style="color: #0284c7;">{{ $data['attendance']['I'] }}</div>
                                            <div class="eskul-stat-label">Izin</div>
                                        </div>
                                        <div class="eskul-stat-mini">
                                            <div class="eskul-stat-val" style="color: #ef4444;">{{ $data['attendance']['A'] }}</div>
                                            <div class="eskul-stat-label">Alpa</div>
                                        </div>
                                    </div>

                                    <!-- Grades SAS 1 & 2 -->
                                    @php
                                        $isCalistung = \Illuminate\Support\Str::contains(strtolower($data['eskul_name']), 'calistung') || \Illuminate\Support\Str::contains(strtolower($data['eskul_name']), 'baca');
                                        
                                        $processGrade = function($score) use ($isCalistung) {
                                            if ($isCalistung) {
                                                $decoded = json_decode($score, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                    return $decoded;
                                                }
                                            }
                                            return $score;
                                        };

                                        $sas1 = $processGrade($data['grades']['sas1']);
                                        $sas2 = $processGrade($data['grades']['sas2']);
                                    @endphp

                                    <div class="eskul-grades-row">
                                        <div class="grade-card-mini">
                                            <div class="grade-card-label">Nilai Semester 1</div>
                                            @if(is_array($sas1))
                                                <div style="font-size: 0.72rem; text-align: left; margin-top: 4px; line-height: 1.4;">
                                                    <div>Baca: <strong>{{ $sas1['reading'] ?? '-' }}</strong></div>
                                                    <div>Tulis: <strong>{{ $sas1['writing'] ?? '-' }}</strong></div>
                                                    <div>Hitung: <strong>{{ $sas1['counting'] ?? '-' }}</strong></div>
                                                </div>
                                            @else
                                                <div class="grade-card-val" style="color: #d97706;">{{ $sas1 }}</div>
                                            @endif
                                        </div>

                                        <div class="grade-card-mini" style="background: #f0f9ff; border-color: #bae6fd;">
                                            <div class="grade-card-label" style="color: #0369a1;">Nilai Semester 2</div>
                                            @if(is_array($sas2))
                                                <div style="font-size: 0.72rem; text-align: left; margin-top: 4px; line-height: 1.4; color: #0369a1;">
                                                    <div>Baca: <strong>{{ $sas2['reading'] ?? '-' }}</strong></div>
                                                    <div>Tulis: <strong>{{ $sas2['writing'] ?? '-' }}</strong></div>
                                                    <div>Hitung: <strong>{{ $sas2['counting'] ?? '-' }}</strong></div>
                                                </div>
                                            @else
                                                <div class="grade-card-val" style="color: #0369a1;">{{ $sas2 }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-placeholder">
                    <i class="fas fa-folder-open" style="color: #cbd5e1;"></i>
                    <p style="font-size: 0.85rem; font-weight: 500;">Belum ada riwayat kegiatan ekstrakurikuler siswa ini di database.</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <footer class="page-footer">
            <p>&copy; {{ date('Y') }} <strong>SIMUTQIN SDIT AN NADZIR</strong> • Menemani Tumbuh Kembang Prestasi & Karakter Qur'ani</p>
        </footer>

    </main>

</body>
</html>
