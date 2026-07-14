<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIM Ekstrakurikuler</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Nunito', sans-serif; }
        
        body {
            min-height: 100vh;
            width: 100vw;
            overflow-x: hidden;
            display: flex;
            background: #ffffff;
            position: relative;
        }

        /* --- Animations --- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes float { 
            0% { transform: translate(0, 0) rotate(0deg); } 
            50% { transform: translate(15px, -20px) rotate(5deg); }
            100% { transform: translate(0, 0) rotate(0deg); } 
        }

        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* --- Left Side: Form --- */
        .login-section {
            width: 45%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 8vw;
            position: relative;
            background: #ffffff;
            z-index: 10;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 3rem;
            animation: slideInLeft 0.8s ease-out forwards;
        }

        .brand img { 
            height: 50px; 
            filter: drop-shadow(0 4px 6px rgba(16, 185, 129, 0.2));
            transition: transform 0.3s;
        }
        .brand:hover img { transform: scale(1.1) rotate(-5deg); }

        .brand h1 { 
            font-size: 1.5rem; 
            font-weight: 700; 
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 0.5px;
        }

        .welcome-text { animation: fadeInUp 0.8s ease-out 0.2s both; }
        .welcome-text h2 { font-size: 2.2rem; color: #064e3b; margin-bottom: 10px; font-weight: 700; }
        .welcome-text p { color: #64748b; margin-bottom: 2.5rem; font-size: 1.05rem; line-height: 1.6; }

        /* Form Styling */
        form { animation: fadeInUp 0.8s ease-out 0.4s both; }

        .input-group { margin-bottom: 1.8rem; position: relative; }
        
        .input-group label {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            padding: 0 5px;
            font-size: 1rem;
            font-weight: 400;
        }

        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label {
            top: 0;
            font-size: 0.85rem;
            color: #059669;
            font-weight: 600;
        }

        .input-group input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
            color: #064e3b;
            background: transparent;
            box-shadow: 0 2px 5px rgba(0,0,0,0.01);
        }

        .input-group input:focus { 
            border-color: #10b981; 
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15); 
        }

        .input-icon-right {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .input-icon-right:hover { color: #059669; }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: all 0.5s;
        }

        .btn-login:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4); 
            animation: pulseGlow 2s infinite;
        }
        
        .btn-login:hover::after { left: 150%; }
        .btn-login:active { transform: translateY(0); }

        .footer-text {
            margin-top: 2.5rem; 
            text-align: center; 
            color: #94a3b8; 
            font-size: 0.9rem;
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        /* --- Right Side: Decoration with Green Theme --- */
        .visual-section {
            width: 55%;
            background: linear-gradient(-45deg, #022c22, #064e3b, #047857, #065f46);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 4rem;
        }

        .visual-content {
            z-index: 2;
            max-width: 550px;
            animation: fadeInUp 1.2s cubic-bezier(0.2, 0.8, 0.2, 1) 0.5s both;
        }
        
        .visual-content h3 { 
            font-size: 2.8rem; 
            font-weight: 700; 
            margin-bottom: 25px; 
            line-height: 1.2;
            text-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        .visual-content p { 
            font-size: 1.15rem; 
            opacity: 0.9; 
            line-height: 1.7; 
            font-weight: 300;
        }

        /* Enhanced Abstract shapes */
        .shape { 
            position: absolute; 
            border-radius: 50%; 
            filter: blur(60px); 
            opacity: 0.6; 
            animation: float 15s ease-in-out infinite alternate; 
            z-index: 1;
        }
        .shape-1 { width: 450px; height: 450px; background: #34d399; top: -150px; right: -100px; animation-duration: 20s; }
        .shape-2 { width: 350px; height: 350px; background: #059669; bottom: -100px; left: -100px; animation-duration: 25s; animation-delay: -5s;}
        .shape-3 { width: 250px; height: 250px; background: #a7f3d0; top: 30%; left: 20%; filter: blur(80px); opacity: 0.4; animation-duration: 18s; animation-delay: -10s;}
        .shape-4 { width: 150px; height: 150px; background: #fcd34d; bottom: 20%; right: 10%; filter: blur(40px); opacity: 0.3; animation-duration: 22s;}

        /* Modern Glass Card Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.08); /* slight transparency */
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 3.5rem 3rem;
            margin-top: 2rem;
            transform: translateY(20px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            transition: all 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        
        .glass-card:hover { 
            transform: translateY(0) scale(1.02); 
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 30px 60px rgba(0,0,0,0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.05) 100%);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 25px;
            animation: float 6s ease-in-out infinite;
        }

        /* Error Message Animation */
        .error-msg {
            background: #fef2f2; color: #b91c1c;
            padding: 14px 18px; border-radius: 12px;
            font-size: 0.95rem; margin-bottom: 25px;
            display: flex; align-items: flex-start; gap: 12px;
            border-left: 5px solid #ef4444;
            animation: slideInLeft 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.05);
        }

        .error-msg i { margin-top: 3px; font-size: 1.1rem; }

        /* Responsiveness */
        @media (max-width: 900px) {
            body { 
                flex-direction: column; 
                overflow-y: auto;
            }
            .visual-section { 
                position: fixed;
                top: 0; left: 0;
                width: 100%; height: 100%;
                z-index: 1;
                padding: 2rem;
            }
            .visual-content { display: none; } /* Hide the text card to keep login clean */
            
            .login-section { 
                width: 100%; 
                min-height: 100vh;
                padding: 2rem 5vw; 
                background: transparent; 
                z-index: 10;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .brand { justify-content: center; margin-bottom: 2rem; }
            .welcome-text { text-align: center; }
            .welcome-text h2 { color: #f8fafc; text-shadow: 0 4px 10px rgba(0,0,0,0.3); }
            .welcome-text p { color: #e2e8f0; }
            
            form { 
                background: rgba(255, 255, 255, 0.95); 
                backdrop-filter: blur(10px);
                padding: 35px 25px; 
                border-radius: 24px; 
                box-shadow: 0 20px 50px rgba(0,0,0,0.2);
                width: 100%;
                max-width: 450px;
            }
            
            .brand h1 { background: white; -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
            .footer-text { color: #f1f5f9; text-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        }
    </style>
</head>
<body>

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

    <!-- Right: Visual Decoration with Dynamic Green Gradient -->
    <div class="visual-section">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>

        <div class="visual-content">
            <div class="glass-card">
                <div class="icon-wrapper">
                    <i class="fas fa-leaf" style="font-size: 2.2rem; color: #a7f3d0;"></i>
                </div>
                <h3>Eksplorasi Potensi <br> Generasi Islam</h3>
                <p>Pantau perkembangan, rekap nilai, dan kelola kegiatan ekstrakurikuler siswa dengan kemudahan visual yang indah melalui satu pintu yang terintegrasi secara cerdas.</p>
            </div>
        </div>
    </div>

    <script>
        // Password Toggle Script with tiny animation
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
            
            // Add click effect
            this.style.transform = 'translateY(-50%) scale(0.8)';
            setTimeout(() => {
                this.style.transform = 'translateY(-50%) scale(1)';
            }, 150);
        });
        
        // Add subtle animation delay for inputs
        document.querySelectorAll('.input-group').forEach((group, index) => {
            group.style.animation = `fadeInUp 0.8s ease-out ${0.4 + (index * 0.1)}s both`;
        });
    </script>
</body>
</html>
