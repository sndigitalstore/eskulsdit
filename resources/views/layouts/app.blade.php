<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMUTQIN SDIT AN NADZIR - @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- PWA Manifest & Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#10b981">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="apple-touch-icon" href="/logo.png">
    @php
        $branding = \App\Models\Setting::whereIn('key', ['app_primary_color', 'app_accent_color'])->pluck('value', 'key');
        $primaryColor = $branding['app_primary_color'] ?? '#0f172a';
        $accentColor = $branding['app_accent_color'] ?? '#6366f1';
    @endphp
    <style>
        :root {
            --primary-bg: #f8fafc;
            --sidebar-bg: {{ $primaryColor }};
            --header-bg: rgba(255, 255, 255, 0.75);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --accent-color: {{ $accentColor }};
            --accent-gradient: linear-gradient(135deg, {{ $accentColor }} 0%, #4f46e5 100%); 
            --card-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 4px 6px -2px rgba(15, 23, 42, 0.02); 
            --transition-speed: 0.3s;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
        
        html {
            scroll-behavior: smooth;
        }

        body { 
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 50%, #f0fdf4 100%);
            background-attachment: fixed;
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
            color: var(--text-main); 
            -webkit-font-smoothing: antialiased;
        }

        /* Animation Keyframes Halus & Soft */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-12px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Sidebar Styling - Matching Reference Image 2 Pastel Gradient */
        .sidebar { 
            width: 270px; 
            background: linear-gradient(180deg, #a7f3d0 0%, #7dd3fc 45%, #c084fc 100%); 
            display: flex; 
            flex-direction: column; 
            border-right: 1px solid rgba(255, 255, 255, 0.6); 
            transition: all var(--transition-speed);
            z-index: 100;
            color: #0f172a;
            box-shadow: 4px 0 25px rgba(15, 23, 42, 0.05);
        }

        .sidebar-header { 
            padding: 1.75rem 1.5rem; 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            animation: fadeIn 0.4s ease-out;
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
        }
        
        .sidebar-header img { 
            width: 44px; 
            height: 44px; 
            object-fit: cover;
            border-radius: 50%;
            padding: 3px;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(6, 95, 70, 0.2);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease; 
        }
        .sidebar-header:hover img { 
            transform: scale(1.08) rotate(6deg); 
            box-shadow: 0 6px 20px rgba(6, 95, 70, 0.35);
        }
        .sidebar-header h2 { font-size: 1.15rem; font-weight: 800; color: #065f46; letter-spacing: 0.3px; white-space: nowrap; }

        .nav-links { list-style: none; padding: 1.25rem 0.85rem; flex: 1; overflow-y: auto; }
        
        .nav-item { margin-bottom: 0.35rem; }

        .nav-link { 
            display: flex; 
            align-items: center; 
            padding: 11px 16px; 
            text-decoration: none; 
            color: rgba(15, 23, 42, 0.78); 
            border-radius: 14px; 
            font-weight: 700; 
            font-size: 0.92rem;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1); 
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
        }

        .nav-link:hover {
            color: #047857;
            background: rgba(255, 255, 255, 0.55);
            transform: translateX(4px);
        }

        .nav-link.active { 
            background: rgba(255, 255, 255, 0.92); 
            color: #047857; 
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06); 
            border: 1px solid rgba(255, 255, 255, 0.95);
        }

        .nav-link i { margin-right: 12px; font-size: 1.15rem; width: 24px; text-align: center; }

        /* Dropdown Accordion Styles - Buttery Smooth & Soft Transition */
        .nav-dropdown {
            margin-bottom: 0.35rem;
            position: relative;
        }

        .nav-dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 16px;
            text-decoration: none;
            color: rgba(15, 23, 42, 0.78);
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.92rem;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            user-select: none;
            border: 1px solid transparent;
        }

        .nav-dropdown-toggle .toggle-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-dropdown-toggle .toggle-content i.main-icon {
            font-size: 1.15rem;
            width: 24px;
            text-align: center;
        }

        .nav-dropdown-toggle .dropdown-chevron {
            font-size: 0.72rem;
            color: rgba(15, 23, 42, 0.55);
            transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1), color 0.25s ease;
        }

        .nav-dropdown-toggle:hover {
            color: #047857;
            background: rgba(255, 255, 255, 0.55);
            transform: translateX(4px);
        }

        .nav-dropdown-toggle:hover .dropdown-chevron {
            color: #047857;
        }

        .nav-dropdown.active-parent > .nav-dropdown-toggle {
            background: rgba(255, 255, 255, 0.55);
            color: #047857;
            border-color: rgba(255, 255, 255, 0.7);
        }

        .nav-dropdown.open > .nav-dropdown-toggle .dropdown-chevron {
            transform: rotate(180deg);
            color: #047857;
        }

        /* Submenu dengan transisi max-height & opacity yang sangat halus */
        .nav-submenu {
            list-style: none;
            padding: 0 0 0 10px;
            margin: 0 0 0 14px;
            border-left: 2px solid rgba(255, 255, 255, 0.55);
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 3px;
            visibility: hidden;
            transition: max-height 0.35s cubic-bezier(0.16, 1, 0.3, 1), 
                        opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1), 
                        padding 0.25s ease, 
                        margin 0.25s ease;
        }

        .nav-dropdown.open > .nav-submenu {
            max-height: 520px;
            opacity: 1;
            visibility: visible;
            padding: 4px 0 4px 10px;
            margin: 4px 0 6px 14px;
        }

        .nav-subitem {
            list-style: none;
        }

        .nav-sublink {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            text-decoration: none;
            color: rgba(15, 23, 42, 0.72);
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.84rem;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .nav-sublink i {
            font-size: 0.92rem;
            width: 18px;
            text-align: center;
            opacity: 0.85;
            transition: transform 0.2s ease;
        }

        .nav-sublink:hover {
            color: #047857;
            background: rgba(255, 255, 255, 0.6);
            transform: translateX(3px);
        }

        .nav-sublink:hover i {
            transform: scale(1.1);
        }

        .nav-sublink.active {
            background: rgba(255, 255, 255, 0.92);
            color: #047857;
            font-weight: 800;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.95);
        }

        .nav-sublink.active i {
            color: #059669;
            opacity: 1;
        }

        .logout-btn { 
            margin: 1.25rem 1rem; 
            padding: 12px; 
            border: 1px solid rgba(255,255,255,0.7); 
            background: rgba(255,255,255,0.4); 
            color: #991b1b; 
            border-radius: 12px; 
            cursor: pointer; 
            font-weight: 700; 
            font-size: 0.9rem;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px; 
            text-decoration: none; 
            transition: all 0.25s;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.35);
            transform: translateY(-2px);
        }

        /* Main Content Styling */
        .main-content { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            overflow-y: auto; 
            scroll-behavior: smooth;
        }

        .header { 
            background: var(--header-bg); 
            padding: 1rem 2.25rem; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            position: sticky;
            top: 0;
            z-index: 90;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .header h1 { font-size: 1.4rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.3px; }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-profile b {
            color: #1e293b;
            font-weight: 700;
        }

        .content { 
            padding: 2.25rem; 
            animation: fadeIn 0.32s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            will-change: opacity, transform;
        }

        /* Card & Elements */
        .card { 
            background: rgba(255, 255, 255, 0.85); 
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px; 
            padding: 2rem; 
            box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05), 0 4px 6px -2px rgba(15, 23, 42, 0.02); 
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 35px -5px rgba(15, 23, 42, 0.08);
        }

        /* Buttons */
        .btn-submit, .btn-filter, .btn-add { 
            background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
            color: #ffffff; 
            border: none; 
            border-radius: 50px; 
            padding: 11px 24px;
            font-size: 0.92rem;
            font-weight: 700; 
            cursor: pointer; 
            transition: all 0.25s ease;
            box-shadow: 0 6px 18px -2px rgba(16, 185, 129, 0.35);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover, .btn-filter:hover, .btn-add:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 10px 25px -4px rgba(16, 185, 129, 0.5);
            color: #ffffff;
        }

        /* Form Controls */
        .form-control { 
            width: 100%; 
            padding: 11px 16px; 
            border: 1.5px solid #cbd5e1; 
            border-radius: 14px; 
            font-size: 0.95rem; 
            font-weight: 500;
            transition: all 0.2s ease; 
            background: #ffffff;
            color: #0f172a;
        }

        .form-control:focus {
            outline: none;
            border-color: #10b981;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.14);
        }

        /* Table Styling */
        table { width: 100%; border-collapse: separate; border-spacing: 0 10px; margin-top: 1rem; }
        th { color: var(--text-light); font-weight: 600; padding: 1rem; text-align: left; border-bottom: 2px solid #f0f0f0; }
        td { background: white; padding: 1.2rem 1rem; border-top: 1px solid #f9f9f9; border-bottom: 1px solid #f9f9f9; transition: all 0.2s; }
        tr td:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; border-left: 1px solid #f9f9f9; }
        tr td:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-right: 1px solid #f9f9f9; }
        
        tr:hover td {
            background: #f8f9fa; /* Apex light hover */
            transform: scale(1.01);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }

        /* Pagination Styling */
        .pagination {
            display: flex;
            list-style: none;
            gap: 5px;
            justify-content: center;
            padding: 1rem 0;
        }

        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            color: var(--text-light);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
            min-width: 35px;
        }

        .page-link:hover {
            color: var(--accent-color);
            border-color: var(--accent-color);
            background: #f4f5fa;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: var(--accent-gradient);
            color: white;
            border: none;
            box-shadow: 0 6px 15px rgba(115, 103, 240, 0.3);
        }

        .page-item.disabled .page-link {
            background: #f9f9f9;
            color: #ccc;
            cursor: not-allowed;
        }

        @media print {
            .sidebar, .header, .btn-print, .no-print { display: none; }
            .card { box-shadow: none; border: none; padding: 0; }
            .content { padding: 0; animation: none; }
            tr:hover td { background: white; transform: none; }
        }

        /* Mobile Responsiveness */
        .mobile-toggle { display: none; margin-right: 15px; font-size: 1.5rem; cursor: pointer; color: var(--text-main); }

        @media (max-width: 900px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100%;
                z-index: 1000;
                box-shadow: 6px 0 25px rgba(15, 23, 42, 0.16);
                transform: translateX(-105%);
                transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.35s;
                visibility: hidden;
                will-change: transform;
            }
            .sidebar.active { 
                transform: translateX(0); 
                visibility: visible;
            }
            
            .mobile-toggle { display: block; }
            .close-sidebar-btn { display: block !important; }

            /* Overlay saat sidebar HP terbuka dengan fade lembut */
            .sidebar-overlay {
                display: block;
                position: fixed;
                top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(15, 23, 42, 0.45);
                z-index: 999;
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.32s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .sidebar-overlay.active { 
                opacity: 1;
                pointer-events: auto;
            }
        }

        @media (max-width: 768px) {
            .content { padding: 1rem; }
            .header { padding: 0.8rem 1rem; }
            .header h1 { font-size: 1.1rem; }
            
            /* Stack Grid Columns on Dashboard/etc */
            div[style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
            }
            
            /* Adjust table readability or overflow and reduce padding */
            .card { 
                overflow-x: auto; 
                padding: 1.25rem !important;
            }
            
            /* Keep avatar clickable but hide details on Mobile */
            .user-name-display div:nth-child(2),
            .user-name-display i { 
                display: none !important; 
            }
            .user-name-display {
                padding: 0 !important;
                border: none !important;
                background: transparent !important;
                box-shadow: none !important;
            }

            /* Shrink help button on mobile to save space */
            button[onclick="showContextualHelp()"] span {
                display: none !important;
            }
            button[onclick="showContextualHelp()"] {
                padding: 0 !important;
                border-radius: 50% !important;
                width: 34px;
                height: 34px;
                display: inline-flex;
                justify-content: center;
                align-items: center;
            }
            
            /* Truncate Title on Mobile */
            .header-title {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 150px;
            }
        }
        @media (max-width: 768px) {
            .desktop-only { display: none !important; }
            
            .header {
                flex-direction: row !important;
                justify-content: space-between !important;
                align-items: center !important;
                gap: 10px;
            }
            
            /* Hide Year Badge on very small mobile screens to prevent wrapping */
            @media (max-width: 576px) {
                .header .user-profile span {
                    display: none !important;
                }
            }
            
            .header > div {
                 display: flex;
                 align-items: center;
            }

            .search-container {
                width: 100%;
                margin: 0 !important;
            }
            
            /* Utils */
            .responsive-flex {
                flex-direction: column !important;
                text-align: center;
            }
            
            .responsive-flex > div {
                text-align: center !important;
                width: 100%;
            }

            /* Dashboard Hero specific */
            .dashboard-hero {
                padding: 15px !important;
            }
            .dashboard-hero h2 { font-size: 1.4rem !important; }
            .dashboard-hero #clock { font-size: 1.5rem !important; }
            .dashboard-hero .decor-icon { display: none; } /* Hide large icon on mobile */
            
            /* Ensure cards have spacing when stacked */
            .card { margin-bottom: 15px; }
        }
        
        /* Search Bar Styling */
        .search-container {
            flex: 1; 
            margin: 0 2rem; 
            display: flex; 
            justify-content: flex-start;
        }
        
        .search-bar {
            position: relative;
            max-width: 400px;
            width: 100%;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border-radius: 50px;
            border: 1px solid #f0f0f0;
            background: #f8f9fa;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .search-bar input:focus {
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-color: var(--accent-color);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        /* Compact Action Buttons Header (Global Style) */
        .btn-action-header {
            padding: 8px 15px;      
            font-size: 0.85rem;     
            font-weight: 500;
            border-radius: 8px;     
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            color: white;
        }
        .btn-action-header:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            color: white;
        }
        .btn-action-header:active {
            transform: translateY(0);
        }
        
        .btn-dark { background: #34495e; color: white; }
        .btn-orange { background: #e67e22; color: white; }
        .btn-blue { background: #3498db; color: white; }
        .btn-red { background: #e74c3c; color: white; }
        .btn-green { background: #2ecc71; color: white; }
        
        .btn-action-header i { font-size: 0.9rem; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="mainSidebar">
        <div class="sidebar-header">
            <img src="{{ asset('logo.png') }}" alt="Logo">
            <div style="display: flex; flex-direction: column; line-height: 1.25;">
                <h2 style="font-size: 1.18rem; font-weight: 900; letter-spacing: 0.02em; margin: 0; color: #064e3b;">SIMUTQIN</h2>
                <span style="font-size: 0.72rem; font-weight: 800; color: #065f46; letter-spacing: 0.05em; text-transform: uppercase;">SDIT AN NADZIR</span>
            </div>
            <div style="margin-left: auto; display: none;" class="close-sidebar-btn" onclick="toggleSidebar()">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <ul class="nav-links">
            {{-- 1. Dashboard (Direct Link) --}}
            <li class="nav-item">
                <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> <span>Dashboard</span>
                </a>
            </li>

            {{-- 2. Pembinaan Qur'an (Dropdown) --}}
            @php
                $isQuranActive = request()->is('mentoring-management*', 'tilawati-groups*', 'tahfidz-groups*', 'munaqosah*', 'tasmi*');
                $loggedUser = Auth::user();
                $isTeacher = $loggedUser->role === 'teacher';
                $isCurriculum = $loggedUser->role === 'curriculum';
                $isAdminOrHeadmaster = in_array($loggedUser->role, ['admin', 'headmaster']);
                $isAdminOrCurriculumOrHeadmaster = in_array($loggedUser->role, ['admin', 'headmaster', 'curriculum']);

                $hasTilawati = false;
                $hasTahfidz = false;
                $isMunaqisy = false;
                if ($isTeacher) {
                    $hasTilawati = \App\Models\MentoringGroup::where('type', 'tilawati')
                        ->where(function($q) use ($loggedUser) {
                            $q->where('mentor_user_id', $loggedUser->id)
                              ->orWhere('mentor_name', $loggedUser->name);
                        })->exists();
                    $hasTahfidz = \App\Models\MentoringGroup::where('type', 'tahfidz')
                        ->where(function($q) use ($loggedUser) {
                            $q->where('mentor_user_id', $loggedUser->id)
                              ->orWhere('mentor_name', $loggedUser->name);
                        })->exists();
                    if (\Illuminate\Support\Facades\Schema::hasTable('munaqisy_examiners')) {
                        $isMunaqisy = \App\Models\MunaqisyExaminer::where('user_id', $loggedUser->id)->where('status', 'active')->exists();
                    }
                }
                $showQuranMenu = $isAdminOrCurriculumOrHeadmaster || ($isTeacher && ($hasTilawati || $hasTahfidz || $isMunaqisy));
            @endphp

            @if($showQuranMenu)
            <li class="nav-dropdown {{ $isQuranActive ? 'open active-parent' : '' }}" id="dropdown-quran">
                <div class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <div class="toggle-content">
                        <i class="fas fa-quran main-icon"></i>
                        <span>Pembinaan Qur'an</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-chevron"></i>
                </div>
                <ul class="nav-submenu">
                    @if($isAdminOrCurriculumOrHeadmaster)
                    <li class="nav-subitem">
                        <a href="{{ route('mentoring.management') }}" class="nav-sublink {{ request()->is('mentoring-management*') ? 'active' : '' }}">
                            <i class="fas fa-layer-group"></i>
                            <span>Manajemen Kelompok</span>
                        </a>
                    </li>
                    @endif

                    @if($isAdminOrCurriculumOrHeadmaster || ($isTeacher && $hasTilawati))
                    <li class="nav-subitem">
                        <a href="{{ route('tilawati-groups.index') }}" class="nav-sublink {{ request()->is('tilawati-groups*') ? 'active' : '' }}">
                            <i class="fas fa-book-reader"></i>
                            <span>{{ $isTeacher ? 'Mentoring Tilawati' : 'Kelompok Tilawati' }}</span>
                        </a>
                    </li>
                    @endif

                    @if($isAdminOrCurriculumOrHeadmaster || ($isTeacher && $hasTahfidz))
                    <li class="nav-subitem">
                        <a href="{{ route('tahfidz-groups.index') }}" class="nav-sublink {{ request()->is('tahfidz-groups*') ? 'active' : '' }}">
                            <i class="fas fa-star-and-crescent"></i>
                            <span>{{ $isTeacher ? 'Mentoring Tahfidz' : 'Kelompok Tahfidz' }}</span>
                        </a>
                    </li>
                    @endif

                    {{-- Fitur Munaqisy & Munaqosah (Kelola Tuntas Juz & Jilid) --}}
                    @if($isAdminOrCurriculumOrHeadmaster || ($isTeacher && $isMunaqisy))
                    <li class="nav-subitem">
                        <a href="{{ route('munaqosah.index') }}" class="nav-sublink {{ request()->is('munaqosah*') ? 'active' : '' }}">
                            <i class="fas fa-award"></i>
                            <span>Munaqisy (Munaqosah)</span>
                        </a>
                    </li>

                    {{-- Fitur Ujian Tasmi' Sekali Duduk --}}
                    <li class="nav-subitem">
                        <a href="{{ route('tasmi.index') }}" class="nav-sublink {{ request()->is('tasmi*') ? 'active' : '' }}">
                            <i class="fas fa-certificate"></i>
                            <span>Ujian Tasmi' (Bil Ghaib)</span>
                        </a>
                    </li>
                    @endif

                    @if($isAdminOrCurriculumOrHeadmaster)
                    <li class="nav-subitem">
                        <a href="{{ route('reports.index', ['tab' => 'tilawati']) }}" class="nav-sublink {{ request()->is('reports*') && request()->get('tab') !== 'eskul' ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i>
                            <span>Laporan & Rekap Qur'an</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            {{-- 3. Ekstrakurikuler (Dropdown - Khusus Kesiswaan, Guru & Kepala Sekolah) --}}
            @if(!$isCurriculum)
            @php
                $isEskulActive = request()->is('eskuls*', 'attendance*', 'grades*', 'compliance*', 'teacher-attendance*');
            @endphp
            <li class="nav-dropdown {{ $isEskulActive ? 'open active-parent' : '' }}" id="dropdown-eskul">
                <div class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <div class="toggle-content">
                        <i class="fas fa-basketball-ball main-icon"></i>
                        <span>Ekstrakurikuler</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-chevron"></i>
                </div>
                <ul class="nav-submenu">
                    @if($isAdminOrHeadmaster)
                    <li class="nav-subitem">
                        <a href="/eskuls" class="nav-sublink {{ request()->is('eskuls*') ? 'active' : '' }}">
                            <i class="fas fa-shapes"></i>
                            <span>Kelola Eskul</span>
                        </a>
                    </li>
                    <li class="nav-subitem">
                        <a href="{{ route('teacher-attendance.index') }}" class="nav-sublink {{ request()->is('teacher-attendance*') ? 'active' : '' }}">
                            <i class="fas fa-user-clock"></i>
                            <span>Absensi Pembina</span>
                        </a>
                    </li>
                    @endif

                    @if($isTeacher)
                    <li class="nav-subitem">
                        <a href="{{ route('teacher-attendance.index') }}" class="nav-sublink {{ request()->is('teacher-attendance*') ? 'active' : '' }}">
                            <i class="fas fa-user-clock"></i>
                            <span>Absensi Saya</span>
                        </a>
                    </li>
                    @endif

                    <li class="nav-subitem">
                        <a href="/attendance" class="nav-sublink {{ request()->is('attendance*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check"></i>
                            <span>Presensi Siswa</span>
                        </a>
                    </li>
                    <li class="nav-subitem">
                        <a href="/grades" class="nav-sublink {{ request()->is('grades*') ? 'active' : '' }}">
                            <i class="fas fa-star"></i>
                            <span>Penilaian Siswa</span>
                        </a>
                    </li>
                    @if($isAdminOrHeadmaster)
                    <li class="nav-subitem">
                        <a href="{{ route('compliance.index') }}" class="nav-sublink {{ request()->is('compliance*') ? 'active' : '' }}">
                            <i class="fas fa-user-check"></i>
                            <span>Kepatuhan Pembina</span>
                        </a>
                    </li>
                    <li class="nav-subitem">
                        <a href="{{ route('pilihan-eskul.form') }}" target="_blank" class="nav-sublink">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Form Pilihan Siswa</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            {{-- 4. SMILE Karakter (Dropdown - Khusus Kesiswaan & Guru) --}}
            @if(!$isCurriculum)
            @php
                $isCharacterActive = request()->is('character*', 'character-cases*', 'character-appreciations*');
            @endphp
            <li class="nav-dropdown {{ $isCharacterActive ? 'open active-parent' : '' }}" id="dropdown-karakter">
                <div class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <div class="toggle-content">
                        <i class="fas fa-smile-beam main-icon" style="color: #f59e0b;"></i>
                        <span>SMILE Karakter</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-chevron"></i>
                </div>
                <ul class="nav-submenu">
                    <li class="nav-subitem">
                        <a href="{{ route('character.dashboard') }}" class="nav-sublink {{ request()->is('character') ? 'active' : '' }}">
                            <i class="fas fa-chart-pie" style="color: #6366f1;"></i>
                            <span>Pantauan Karakter</span>
                        </a>
                    </li>
                    <li class="nav-subitem">
                        <a href="{{ route('character-cases.index') }}" class="nav-sublink {{ request()->is('character-cases*') ? 'active' : '' }}">
                            <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>
                            <span>Data Kasus</span>
                        </a>
                    </li>
                    <li class="nav-subitem">
                        <a href="{{ route('character-appreciations.index') }}" class="nav-sublink {{ request()->is('character-appreciations*') ? 'active' : '' }}">
                            <i class="fas fa-award" style="color: #10b981;"></i>
                            <span>Data Apresiasi</span>
                        </a>
                    </li>
                    <li class="nav-subitem">
                        <a href="{{ route('character.report') }}" class="nav-sublink {{ request()->is('character/report*') ? 'active' : '' }}">
                            <i class="fas fa-file-signature" style="color: #0284c7;"></i>
                            <span>Laporan & Rekap</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            {{-- 5. SMILE Kurikulum (Dropdown - Raport Akademik Kurikulum Merdeka) --}}
            @php
                $isKurikulumActive = request()->is('curriculum*');
            @endphp
            <li class="nav-dropdown {{ $isKurikulumActive ? 'open active-parent' : '' }}" id="dropdown-kurikulum">
                <div class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <div class="toggle-content">
                        <i class="fas fa-graduation-cap main-icon" style="color: #0284c7;"></i>
                        <span>SMILE Kurikulum</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-chevron"></i>
                </div>
                <ul class="nav-submenu">
                    @php
                        $isReportRoute = request()->is('curriculum/academic-reports*');
                        $currentAssessment = request()->get('assessment', 'STS_1');
                    @endphp
                    <li class="nav-subitem">
                        <a href="{{ route('academic-reports.index', ['assessment' => 'STS_1']) }}" class="nav-sublink {{ $isReportRoute && $currentAssessment === 'STS_1' ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt" style="color: #0284c7;"></i>
                            <span>Raport STS 1 (Ganjil)</span>
                        </a>
                    </li>
                    <li class="nav-subitem">
                        <a href="{{ route('academic-reports.index', ['assessment' => 'SAS_1']) }}" class="nav-sublink {{ $isReportRoute && $currentAssessment === 'SAS_1' ? 'active' : '' }}">
                            <i class="fas fa-check-circle" style="color: #16a34a;"></i>
                            <span>Raport SAS 1 (Ganjil)</span>
                        </a>
                    </li>
                    <li class="nav-subitem">
                        <a href="{{ route('academic-reports.index', ['assessment' => 'STS_2']) }}" class="nav-sublink {{ $isReportRoute && $currentAssessment === 'STS_2' ? 'active' : '' }}">
                            <i class="fas fa-calendar-check" style="color: #d97706;"></i>
                            <span>Raport STS 2 (Genap)</span>
                        </a>
                    </li>
                    <li class="nav-subitem">
                        <a href="{{ route('academic-reports.index', ['assessment' => 'SAS_2']) }}" class="nav-sublink {{ $isReportRoute && $currentAssessment === 'SAS_2' ? 'active' : '' }}">
                            <i class="fas fa-award" style="color: #7c3aed;"></i>
                            <span>Raport SAS 2 (Genap)</span>
                        </a>
                    </li>
                    @if($isAdminOrCurriculumOrHeadmaster)
                    <li class="nav-subitem">
                        <a href="{{ route('subjects.index') }}" class="nav-sublink {{ request()->is('curriculum/subjects*') ? 'active' : '' }}">
                            <i class="fas fa-book" style="color: #10b981;"></i>
                            <span>Mata Pelajaran & KKTP</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>

            {{-- 6. Kesiswaan & Pembina (Dropdown - Khusus Admin & Kepala Sekolah) --}}
            @if($isAdminOrHeadmaster)
            @php
                $isKesiswaanActive = request()->is('students*', 'promotions*', 'achievements*', 'teachers*', 'import-portal*');
            @endphp
            <li class="nav-dropdown {{ $isKesiswaanActive ? 'open active-parent' : '' }}" id="dropdown-kesiswaan">
                <div class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <div class="toggle-content">
                        <i class="fas fa-user-graduate main-icon"></i>
                        <span>Kesiswaan & Guru</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-chevron"></i>
                </div>
                <ul class="nav-submenu">
                    <li class="nav-subitem">
                        <a href="/students" class="nav-sublink {{ request()->is('students*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Data Siswa</span>
                        </a>
                    </li>
                    <li class="nav-subitem">
                        <a href="/promotions" class="nav-sublink {{ request()->is('promotions*') ? 'active' : '' }}">
                            <i class="fas fa-level-up-alt"></i>
                            <span>Kenaikan Kelas</span>
                        </a>
                    </li>
                    <li class="nav-subitem">
                        <a href="{{ route('achievements.index') }}" class="nav-sublink {{ request()->is('achievements*') ? 'active' : '' }}">
                            <i class="fas fa-trophy"></i>
                            <span>Data Prestasi</span>
                        </a>
                    </li>
                    <li class="nav-subitem">
                        <a href="/teachers" class="nav-sublink {{ request()->is('teachers*') ? 'active' : '' }}">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Kelola Guru/Pembina</span>
                        </a>
                    </li>
                    @if(Auth::user()->role === 'admin')
                    <li class="nav-subitem">
                        <a href="{{ route('import-portal.index') }}" class="nav-sublink {{ request()->is('import-portal*') ? 'active' : '' }}">
                            <i class="fas fa-file-import"></i>
                            <span>Import Data Excel</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            {{-- 7. Laporan & Rapor (Dropdown: Eskul, Tilawati, Tahfidz, Akademik, Karakter) --}}
            @php
                $isReportActive = request()->is('reports*');
                $isAcademicReportActive = request()->is('curriculum/academic-reports*');
                $isCharacterReportActive = request()->is('character/report*');
                $isAnyReportActive = $isReportActive || $isAcademicReportActive || $isCharacterReportActive;

                $canSeeEskulReport = !$isCurriculum && ($isAdminOrHeadmaster || ($isTeacher && !empty($loggedUser->homeroom_class)));
                $canSeeTilawatiReport = $isAdminOrCurriculumOrHeadmaster || ($isTeacher && (!empty($loggedUser->homeroom_class) || $hasTilawati));
                $canSeeTahfidzReport = $isAdminOrCurriculumOrHeadmaster || ($isTeacher && (!empty($loggedUser->homeroom_class) || $hasTahfidz));
                $canSeeAcademicReport = $isAdminOrCurriculumOrHeadmaster || ($isTeacher && !empty($loggedUser->homeroom_class));
                $canSeeCharacterReport = !$isCurriculum && ($isAdminOrHeadmaster || ($isTeacher && !empty($loggedUser->homeroom_class)));

                $showReportDropdown = $canSeeEskulReport || $canSeeTilawatiReport || $canSeeTahfidzReport || $canSeeAcademicReport || $canSeeCharacterReport;
            @endphp

            @if($showReportDropdown)
            <li class="nav-dropdown {{ $isAnyReportActive ? 'open active-parent' : '' }}" id="dropdown-laporan">
                <div class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <div class="toggle-content">
                        <i class="fas fa-file-invoice main-icon" style="color: #6366f1;"></i>
                        <span>Laporan & Rapor</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-chevron"></i>
                </div>
                <ul class="nav-submenu">
                    @if($canSeeEskulReport)
                    <li class="nav-subitem">
                        <a href="{{ route('reports.index', ['tab' => 'eskul']) }}" class="nav-sublink {{ $isReportActive && request()->get('tab', 'eskul') === 'eskul' ? 'active' : '' }}">
                            <i class="fas fa-basketball-ball" style="color: #0284c7;"></i>
                            <span>Laporan Eskul</span>
                        </a>
                    </li>
                    @endif

                    @if($canSeeTilawatiReport)
                    <li class="nav-subitem">
                        <a href="{{ route('reports.index', ['tab' => 'tilawati']) }}" class="nav-sublink {{ $isReportActive && request()->get('tab') === 'tilawati' ? 'active' : '' }}">
                            <i class="fas fa-book-open" style="color: #16a34a;"></i>
                            <span>Laporan Tilawati</span>
                        </a>
                    </li>
                    @endif

                    @if($canSeeTahfidzReport)
                    <li class="nav-subitem">
                        <a href="{{ route('reports.index', ['tab' => 'tahfidz']) }}" class="nav-sublink {{ $isReportActive && request()->get('tab') === 'tahfidz' ? 'active' : '' }}">
                            <i class="fas fa-quran" style="color: #0284c7;"></i>
                            <span>Laporan Tahfidz</span>
                        </a>
                    </li>
                    @endif

                    @if($canSeeAcademicReport)
                    <li class="nav-subitem">
                        <a href="{{ route('academic-reports.index') }}" class="nav-sublink {{ $isAcademicReportActive ? 'active' : '' }}">
                            <i class="fas fa-graduation-cap" style="color: #8b5cf6;"></i>
                            <span>Laporan Akademik</span>
                        </a>
                    </li>
                    @endif

                    @if($canSeeCharacterReport)
                    <li class="nav-subitem">
                        <a href="{{ route('character.report') }}" class="nav-sublink {{ $isCharacterReportActive ? 'active' : '' }}">
                            <i class="fas fa-smile-beam" style="color: #f59e0b;"></i>
                            <span>Laporan Karakter</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            {{-- 6. Pengaturan & Sistem (Dropdown) --}}
            @php
                $isSystemActive = request()->is('settings*', 'announcements*', 'logs*', 'guide*', 'profile*');
            @endphp
            <li class="nav-dropdown {{ $isSystemActive ? 'open active-parent' : '' }}" id="dropdown-system">
                <div class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <div class="toggle-content">
                        <i class="fas fa-sliders-h main-icon"></i>
                        <span>Sistem & Akun</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-chevron"></i>
                </div>
                <ul class="nav-submenu">
                    @if($isAdminOrCurriculumOrHeadmaster)
                    <li class="nav-subitem">
                        <a href="{{ route('announcements.index') }}" class="nav-sublink {{ request()->is('announcements*') ? 'active' : '' }}">
                            <i class="fas fa-bullhorn"></i>
                            <span>Pengumuman</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->role === 'admin')
                    <li class="nav-subitem">
                        <a href="/settings" class="nav-sublink {{ request()->is('settings*') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>
                            <span>Pengaturan Form</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-subitem">
                        <a href="{{ route('guide.index') }}" class="nav-sublink {{ request()->is('guide*') ? 'active' : '' }}">
                            <i class="fas fa-book-open"></i>
                            <span>Panduan Penggunaan</span>
                        </a>
                    </li>
                    @if(Auth::user()->username === 'admin')
                    <li class="nav-subitem">
                        <a href="{{ route('logs.index') }}" class="nav-sublink {{ request()->is('logs*') ? 'active' : '' }}">
                            <i class="fas fa-history"></i>
                            <span>Riwayat Log</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-subitem">
                        <a href="{{ route('profile.index') }}" class="nav-sublink {{ request()->is('profile*') ? 'active' : '' }}">
                            <i class="fas fa-key"></i>
                            <span>Ubah Password</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </aside>

    <main class="main-content">
        <header class="header">
            <div style="display: flex; align-items: center;">
                <i class="fas fa-bars mobile-toggle" onclick="toggleSidebar()"></i>
                <h1 class="header-title">@yield('page-title', 'Dashboard')</h1>
            </div>

            <!-- Global Search Bar -->
            @if(Auth::user()->role == 'admin')
            <div class="search-container desktop-only">
                <form action="{{ route('global-search') }}" method="GET" class="search-bar">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="q" placeholder="Cari siswa, nis, atau kelas..." value="{{ request('q') }}">
                </form>
            </div>
            @endif
             <div class="user-profile" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <button type="button" onclick="showContextualHelp()" style="display: inline-flex; align-items: center; gap: 6px; background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; padding: 6px 14px; border-radius: 30px; font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: all 0.2s; white-space: nowrap; box-shadow: 0 2px 5px rgba(2, 132, 199, 0.08);" title="Petunjuk & Fitur Halaman Ini">
                    <i class="fas fa-question-circle" style="font-size: 0.9rem;"></i> <span>Bantuan Halaman</span>
                </button>
                @if(isset($activeYear) && $activeYear)
                    <span style="display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 6px 14px; border-radius: 30px; font-size: 0.8rem; font-weight: 700; white-space: nowrap; box-shadow: 0 2px 5px rgba(5, 150, 105, 0.08);">
                        <i class="fas fa-calendar-check" style="font-size: 0.85rem;"></i> {{ $activeYear->name }} (Sem {{ $activeYear->active_semester ?? '1' }})
                    </span>
                @endif
                <a href="{{ route('profile.index') }}" class="user-name-display" title="Klik untuk Ubah Profil & Password" style="display: flex; align-items: center; gap: 10px; background: #ffffff; padding: 6px 14px 6px 8px; border-radius: 30px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.03); text-decoration: none; transition: transform 0.2s, box-shadow 0.2s;">
                    <div style="width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-size: 0.85rem; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ Auth::user()->name }}</span>
                        <span style="font-size: 0.7rem; color: #64748b; font-weight: 600; text-transform: uppercase;">
                            @if(Auth::user()->role == 'admin')
                                Kesiswaan
                            @elseif(Auth::user()->role == 'headmaster')
                                Kepala Sekolah
                            @else
                                Guru / Wali Kelas
                            @endif
                        </span>
                    </div>
                    <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: #94a3b8; margin-left: 4px;"></i>
                </a>
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>

        @php
            $footerText = \App\Models\Setting::where('key', 'app_credits')->value('value') ?: '© ' . date('Y') . ' SDIT AN NADZIR (Najibudin, S.Pd).';
        @endphp
        <footer style="text-align: center; padding: 20px; color: #94a3b8; font-size: 0.82rem; border-top: 1px solid #e2e8f0; margin-top: auto;">
            {{ $footerText }}
        </footer>
    </main>

    @stack('scripts')
    @include('partials.page_help_script')
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function toggleNavDropdown(el) {
            const dropdown = el.closest('.nav-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('open');
            }
        }

        // SweetAlert2 Toast Mixin
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}'
            });
        @endif
        
        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: '{{ session('warning') }}'
            });
        @endif

        @if(session('warning_html'))
            Swal.fire({
                icon: 'warning',
                title: 'Catatan / Peringatan',
                html: `{!! session('warning_html') !!}`,
                confirmButtonColor: '#10b981'
            });
        @endif

        // Modern Confirmation Handler
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.classList.contains('confirm-delete') || form.hasAttribute('data-confirm')) {
                if (form.dataset.confirmed) {
                    delete form.dataset.confirmed;
                    return;
                }

                e.preventDefault();
                const message = form.getAttribute('data-confirm') || 'Apakah Anda yakin ingin menghapus data ini?';
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e54261',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'animated fadeInDown faster'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.dataset.confirmed = true;
                        form.submit();
                    }
                });
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
