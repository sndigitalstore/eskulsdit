<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIM Ekstrakurikuler SDIT AN NADZIR</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body {
            min-height: 100vh;
            width: 100vw;
            overflow-x: hidden;
            display: flex;
            background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 50%, #f8fafc 100%);
            position: relative;
            color: #0f172a;
        }

        h1, h2, h3, h4 { font-family: 'Outfit', sans-serif; }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes float { 
            0% { transform: translateY(0px) rotate(0deg); } 
            50% { transform: translateY(-12px) rotate(1deg); }
            100% { transform: translateY(0px) rotate(0deg); } 
        }

        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Pastel Ambient Blobs */
        .bg-shapes {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.55;
            animation: float 14s ease-in-out infinite alternate;
        }

        .shape-1 { width: 500px; height: 500px; background: #c7d2fe; top: -120px; left: -100px; }
        .shape-2 { width: 450px; height: 450px; background: #a7f3d0; bottom: -100px; right: -100px; }
        .shape-3 { width: 350px; height: 350px; background: #fef08a; top: 30%; right: 25%; opacity: 0.35; }

        /* Container Layout */
        .login-wrapper {
            width: 100%;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 46% 54%;
            z-index: 10;
            position: relative;
        }

        /* --- Left Side: Form --- */
        .login-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 6vw;
            position: relative;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(25px);
            border-right: 1px solid rgba(226, 232, 240, 0.8);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 2.5rem;
            animation: slideInLeft 0.8s ease-out forwards;
        }

        .brand img { 
            width: 54px; 
            height: 54px; 
            object-fit: cover;
            border-radius: 50%;
            padding: 3px;
            background: linear-gradient(135deg, #10b981 0%, #6366f1 100%);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
        }

        .brand:hover img { 
            transform: scale(1.08) rotate(6deg); 
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.45);
        }

        .brand h1 { 
            font-size: 1.5rem; 
            font-weight: 800; 
            background: linear-gradient(135deg, #059669 0%, #4338ca 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .welcome-text { animation: fadeInUp 0.8s ease-out 0.2s both; }
        .welcome-text h2 { font-size: 2.1rem; color: #0f172a; margin-bottom: 8px; font-weight: 800; letter-spacing: -0.5px; }
        .welcome-text p { color: #64748b; margin-bottom: 2.2rem; font-size: 1.02rem; line-height: 1.6; }

        /* Form Styling */
        form { animation: fadeInUp 0.8s ease-out 0.4s both; }

        .input-group { margin-bottom: 1.6rem; position: relative; }
        
        .input-group label {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            padding: 0 6px;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 4px;
        }

        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label {
            top: 0;
            font-size: 0.82rem;
            color: #059669;
            font-weight: 700;
        }

        .input-group input {
            width: 100%;
            padding: 16px 48px 16px 18px;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            font-size: 0.98rem;
            outline: none;
            transition: all 0.3s ease;
            color: #0f172a;
            background: #ffffff;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);
        }

        .input-group input:focus { 
            border-color: #10b981; 
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15); 
        }

        .input-icon-right {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .input-icon-right:hover { color: #059669; }

        .btn-login {
            width: 100%;
            padding: 17px;
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            animation: pulseGlow 2.5s infinite;
        }

        .btn-login:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.5); 
        }
        
        .btn-login:active { transform: translateY(0); }

        .footer-text {
            margin-top: 2.2rem; 
            text-align: center; 
            color: #94a3b8; 
            font-size: 0.88rem;
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        /* --- Right Side: Inspired by Image 2 UI Glass Mockup --- */
        .visual-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 4vw;
            position: relative;
            overflow: hidden;
        }

        /* Outer Glass Frame matching Image 2 */
        .glass-mockup-frame {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 32px;
            padding: 16px;
            box-shadow: 0 30px 70px -15px rgba(15, 23, 42, 0.12);
            width: 100%;
            max-width: 540px;
            animation: float 6s ease-in-out infinite;
        }

        .ui-mockup-container {
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 140px 1fr;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
            min-height: 420px;
        }

        /* Left Pastel Gradient Sidebar in Mockup matching Image 2 */
        .ui-sidebar {
            background: linear-gradient(180deg, #a7f3d0 0%, #7dd3fc 50%, #c084fc 100%);
            padding: 20px 14px;
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
            background: rgba(255,255,255,0.88);
            color: #047857;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Right Content Area in Mockup */
        .ui-content {
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 14px;
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

        /* Donut Progress Chart Widget matching Image 2 */
        .ui-chart-widget {
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 14px;
        }

        .ui-chart-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 10px;
        }

        .ui-donuts-flex {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .ui-donut {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.68rem;
            font-weight: 800;
            color: #0f172a;
            position: relative;
        }

        .ui-donut-1 { background: conic-gradient(#10b981 0% 75%, #e2e8f0 75% 100%); }
        .ui-donut-2 { background: conic-gradient(#6366f1 0% 88%, #e2e8f0 88% 100%); }
        .ui-donut-3 { background: conic-gradient(#f59e0b 0% 65%, #e2e8f0 65% 100%); }

        .ui-donut-inner {
            width: 36px;
            height: 36px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Text Quote Card below UI Mockup */
        .visual-text-card {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(99, 102, 241, 0.08) 100%);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 18px;
            padding: 14px 18px;
            margin-top: 14px;
            text-align: center;
        }

        .visual-text-card h4 {
            font-size: 0.95rem;
            font-weight: 800;
            color: #065f46;
            margin-bottom: 4px;
        }

        .visual-text-card p {
            font-size: 0.8rem;
            color: #475569;
            line-height: 1.5;
        }

        /* Error Message Animation */
        .error-msg {
            background: #fef2f2; color: #b91c1c;
            padding: 14px 18px; border-radius: 14px;
            font-size: 0.92rem; margin-bottom: 22px;
            display: flex; align-items: flex-start; gap: 12px;
            border-left: 5px solid #ef4444;
            animation: slideInLeft 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.08);
        }

        .error-msg i { margin-top: 3px; font-size: 1.1rem; }

        /* Responsiveness */
        @media (max-width: 1024px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }
            .visual-section { display: none; }
            .login-section {
                padding: 3rem 8vw;
                background: rgba(255, 255, 255, 0.85);
                min-height: 100vh;
            }
        }
    </style>
</head>
<body>

    <!-- Pastel Ambient Blobs -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="login-wrapper">
        <!-- Left: Login Form -->
        <div class="login-section">
            <div class="brand">
                <img src="{{ asset('logo.png') }}" alt="Logo SDIT">
                <h1>SDIT AN NADZIR</h1>
            </div>

            <div class="welcome-text">
                <h2>Selamat Datang!</h2>
                <p>Sistem Informasi Manajemen Ekstrakurikuler yang cerdas, efisien, dan modern.</p>
            </div>

            @if ($errors->any())
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div style="margin-bottom: 4px;">{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                
                <div class="input-group">
                    <input type="text" id="username" name="username" placeholder=" " value="{{ old('username') }}" required autocomplete="off">
                    <label for="username">Nama Pengguna (Username)</label>
                    <i class="fas fa-user input-icon-right" style="cursor: default;"></i>
                </div>

                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder=" " required>
                    <label for="password">Kata Sandi</label>
                    <i class="fas fa-eye input-icon-right" id="togglePassword"></i>
                </div>

                <button type="submit" class="btn-login">
                    <span>Masuk Sekarang</span> <i class="fas fa-sign-in-alt"></i>
                </button>
            </form>

            <div class="footer-text">
                &copy; {{ date('Y') }} <strong>SDIT AN NADZIR</strong>. Aplikasi Manajemen Terpadu.
            </div>
        </div>

        <!-- Right Side: Inspired by Reference Image 2 UI Glass Mockup -->
        <div class="visual-section">
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
                                <i class="fas fa-search"></i> Cari siswa...
                            </div>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <i class="fas fa-bell" style="font-size: 0.75rem; color: #94a3b8;"></i>
                                <div style="width: 22px; height: 22px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 700; color: #475569;">A</div>
                            </div>
                        </div>

                        <!-- Mini Widgets Grid -->
                        <div class="ui-widgets-grid">
                            <div class="ui-widget-card card-1">
                                <h5>Total Eskul</h5>
                                <div class="value">12</div>
                            </div>
                            <div class="ui-widget-card card-2">
                                <h5>Kehadiran</h5>
                                <div class="value">98%</div>
                            </div>
                        </div>

                        <!-- Donut Progress Chart Widget matching Image 2 -->
                        <div class="ui-chart-widget">
                            <div class="ui-chart-header">
                                <span>Statistik Latihan</span>
                                <span style="color: #10b981; font-weight: 800;">Realtime</span>
                            </div>
                            <div class="ui-donuts-flex">
                                <div class="ui-donut ui-donut-1">
                                    <div class="ui-donut-inner">75%</div>
                                </div>
                                <div class="ui-donut ui-donut-2">
                                    <div class="ui-donut-inner">88%</div>
                                </div>
                                <div class="ui-donut ui-donut-3">
                                    <div class="ui-donut-inner">65%</div>
                                </div>
                            </div>
                        </div>

                        <!-- Text Quote Card inside Mockup -->
                        <div class="visual-text-card">
                            <h4>Eksplorasi Potensi Siswa</h4>
                            <p>Pantau rekap nilai, absensi, dan prestasi siswa dalam satu platform cerdas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password Toggle Script
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
            
            this.style.transform = 'translateY(-50%) scale(0.85)';
            setTimeout(() => {
                this.style.transform = 'translateY(-50%) scale(1)';
            }, 150);
        });
    </script>
</body>
</html>

