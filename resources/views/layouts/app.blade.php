<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Eskul - @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @php
        $branding = \App\Models\Setting::whereIn('key', ['app_primary_color', 'app_accent_color'])->pluck('value', 'key');
        $primaryColor = $branding['app_primary_color'] ?? '#1c1130';
        $accentColor = $branding['app_accent_color'] ?? '#7367f0';
    @endphp
    <style>
        :root {
            --primary-bg: #f4f5fa;
            --sidebar-bg: {{ $primaryColor }};
            --header-bg: #ffffff;
            --text-main: #333333;
            --text-light: #888888;
            --accent-color: {{ $accentColor }};
            --accent-gradient: linear-gradient(135deg, {{ $accentColor }} 0%, {{ $accentColor }}cc 100%); 
            --card-shadow: 0 4px 20px 0 rgba(0,0,0,0.05); 
            --transition-speed: 0.4s;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Nunito', sans-serif; }
        
        body { 
            background-color: var(--primary-bg); 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
            color: var(--text-main); 
        }

        /* Animation Keyframes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Sidebar Styling */
        .sidebar { 
            width: 260px; 
            background: linear-gradient(180deg, #2b1f4d 0%, #17112c 100%); 
            display: flex; 
            flex-direction: column; 
            border-right: none; 
            transition: all var(--transition-speed);
            z-index: 100;
            color: #d1cceb;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header { 
            padding: 2rem 1.5rem; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            animation: fadeIn 0.5s ease-out;
        }
        
        .sidebar-header img { height: 35px; transition: transform 0.3s; }
        .sidebar-header:hover img { transform: rotate(10deg); }
        .sidebar-header h2 { font-size: 1.1rem; font-weight: 800; color: white; letter-spacing: 0.5px; white-space: nowrap; }

        .nav-links { list-style: none; padding: 1rem; flex: 1; overflow-y: auto; }
        
        .nav-item { margin-bottom: 0.5rem; animation: slideInLeft 0.5s ease-out forwards; opacity: 0; }
        .nav-item:nth-child(1) { animation-delay: 0.1s; }
        .nav-item:nth-child(2) { animation-delay: 0.2s; }
        .nav-item:nth-child(3) { animation-delay: 0.3s; }
        .nav-item:nth-child(4) { animation-delay: 0.4s; }
        .nav-item:nth-child(5) { animation-delay: 0.5s; }
        .nav-item:nth-child(6) { animation-delay: 0.6s; }

        .nav-link { 
            display: flex; 
            align-items: center; 
            padding: 12px 15px; 
            text-decoration: none; 
            color: #d1cceb; 
            border-radius: 12px; 
            font-weight: 500; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(8px);
        }

        .nav-link.active { 
            background: linear-gradient(135deg, #e54261 0%, #ff6a88 100%); 
            color: white; 
            box-shadow: 0 6px 20px rgba(229, 66, 97, 0.4); 
        }

        .nav-link i { margin-right: 12px; font-size: 1.2rem; width: 25px; text-align: center; }

        .logout-btn { 
            margin: 1.5rem; 
            padding: 12px; 
            border: 1px solid rgba(255,255,255,0.1); 
            background-color: rgba(255,255,255,0.05); 
            color: #d1cceb; 
            border-radius: 12px; 
            cursor: pointer; 
            font-weight: 600; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px; 
            text-decoration: none; 
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #e54261 0%, #ff6a88 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 6px 20px rgba(229, 66, 97, 0.3);
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
            background-color: var(--header-bg); 
            padding: 1rem 2rem; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.02); 
            position: sticky;
            top: 0;
            z-index: 90;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.8);
        }

        .header h1 { font-size: 1.5rem; color: var(--text-main); }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-profile b {
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        .content { 
            padding: 2rem; 
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Card & Elements */
        .card { 
            background: white; 
            border-radius: 15px; /* Apex boxes are slightly less rounded */
            padding: 2rem; 
            box-shadow: var(--card-shadow); 
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
            border: none;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        }

        /* Buttons */
        .btn-submit, .btn-filter, .btn-add { 
            background: var(--accent-gradient); 
            color: white; 
            border: none; 
            border-radius: 50px; 
            padding: 12px 25px;
            font-weight: 600; 
            cursor: pointer; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover, .btn-filter:hover, .btn-add:hover { 
            transform: translateY(-3px) scale(1.02); 
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        /* Form Controls */
        .form-control { 
            width: 100%; 
            padding: 12px 15px; 
            border: 1px solid #eee; 
            border-radius: 12px; 
            font-size: 1rem; 
            transition: all 0.3s; 
            background: #fafafa;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            background: white;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
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
                left: -280px;
                top: 0;
                height: 100%;
                z-index: 1000;
                box-shadow: 5px 0 15px rgba(0,0,0,0.1);
                visibility: hidden; /* Ensure it doesn't catch clicks when closed */
            }
            .sidebar.active { 
                left: 0; 
                visibility: visible;
            }
            
            .mobile-toggle { display: block; }
            .close-sidebar-btn { display: block !important; }

            /* Overlay when sidebar is open */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                backdrop-filter: blur(3px);
            }
            .sidebar-overlay.active { display: block; }
        }

        @media (max-width: 768px) {
            .content { padding: 1rem; }
            .header { padding: 0.8rem 1rem; }
            .header h1 { font-size: 1.1rem; }
            
            /* Stack Grid Columns on Dashboard/etc */
            div[style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
            }
            
            /* Adjust table readability or overflow */
            .card { overflow-x: auto; }
            
            /* Hide User Name on Mobile */
            .user-name-display { display: none; }
            
            /* Truncate Title on Mobile */
            .header-title {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 200px;
            }
        }
        @media (max-width: 768px) {
            .desktop-only { display: none !important; }
            
            .header {
                flex-direction: column;
                align-items: stretch; /* Stretch to fill width */
                gap: 15px;
            }
            
            .header > div {
                 display: flex;
                 justify-content: space-between;
                 width: 100%;
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
            <h2>SDIT AN NADZIR</h2>
            <div style="margin-left: auto; display: none;" class="close-sidebar-btn" onclick="toggleSidebar()">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <ul class="nav-links">
            <li class="nav-item">
                <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </li>
            @if(Auth::user()->role == 'admin')
            <li class="nav-item">
                <a href="/students" class="nav-link {{ request()->is('students*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Data Siswa
                </a>
            </li>
            <li class="nav-item">
                <a href="/promotions" class="nav-link {{ request()->is('promotions*') ? 'active' : '' }}">
                    <i class="fas fa-level-up-alt"></i> Kenaikan Kelas
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('achievements.index') }}" class="nav-link {{ request()->is('achievements*') ? 'active' : '' }}">
                    <i class="fas fa-trophy"></i> Data Prestasi
                </a>
            </li>
            <li class="nav-item">
                <a href="/teachers" class="nav-link {{ request()->is('teachers*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i> Kelola Pembina
                </a>
            </li>
            <li class="nav-item">
                <a href="/eskuls" class="nav-link {{ request()->is('eskuls*') ? 'active' : '' }}">
                    <i class="fas fa-basketball-ball"></i>
                    <span>Ekstrakurikuler</span>
                </a>
            </li>
            @else
            <!-- Teacher only sees "My Eskul" info? Actually teacher doesn't need Eskul Management. -->
            @endif

            <li class="nav-item">
                <a href="{{ route('teacher-attendance.index') }}" class="nav-link {{ request()->is('teacher-attendance*') ? 'active' : '' }}">
                    <i class="fas fa-user-clock"></i>
                    <span>{{ Auth::user()->role == 'admin' ? 'Absensi Guru' : 'Absensi Saya' }}</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="/attendance" class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Absensi</span>
                </a>
            </li>
             <li class="nav-item">
                <a href="/grades" class="nav-link {{ request()->is('grades*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>Nilai</span>
                </a>
            </li>

            @if(Auth::user()->role == 'admin')
            <li class="nav-item">
                <a href="/reports" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/academic-years" class="nav-link {{ request()->is('academic-years*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Tahun Ajaran</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/settings" class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan Form</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('announcements.index') }}" class="nav-link {{ request()->is('announcements*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Pengumuman</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('logs.index') }}" class="nav-link {{ request()->is('logs*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Riwayat Log</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('guide.index') }}" class="nav-link {{ request()->is('guide*') ? 'active' : '' }}">
                    <i class="fas fa-book-open"></i>
                    <span>Panduan Admin</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pilihan-eskul.form') }}" target="_blank" class="nav-link">
                    <i class="fas fa-link"></i>
                    <span>Form Pilihan Eskul</span>
                </a>
            </li>
            @endif
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
             <div class="user-profile">
                @if(isset($activeYear) && $activeYear)
                    <span style="display: inline-block; margin-right: 10px; background: #e0fbf0; color: #2ecc71; padding: 5px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; white-space: nowrap;">
                        {{ $activeYear->name }}
                    </span>
                @endif
                <div class="user-name-display">
                    <span style="margin-right: 5px; color: #888;">Halo, </span>
                    <b>{{ Auth::user()->name }}</b>
                </div>
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>

        @php
            $footerText = \App\Models\Setting::where('key', 'app_credits')->value('value');
        @endphp
        @if($footerText)
            <footer style="text-align: center; padding: 20px; color: #aaa; font-size: 0.8rem; border-top: 1px solid #eee; margin-top: auto;">
                {{ $footerText }}
            </footer>
        @endif
    </main>

    @stack('scripts')
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
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
    </script>
</body>
</html>
