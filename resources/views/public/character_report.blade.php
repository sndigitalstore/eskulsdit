<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Karakter & TPPK – {{ $schoolName }}</title>
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
            --emerald: #10b981;
            --rose: #e11d48;
            --amber: #d97706;
            --blue: #0284c7;
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
            background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 50%, #fef2f2 100%);
            min-height: 100vh;
            color: var(--text-dark);
            padding: 24px 16px 60px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Blobs */
        .ambient-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            z-index: 0;
            pointer-events: none;
            opacity: 0.55;
        }
        .blob-1 {
            width: 450px;
            height: 450px;
            background: rgba(225, 29, 72, 0.15);
            top: -100px;
            right: -100px;
        }
        .blob-2 {
            width: 480px;
            height: 480px;
            background: rgba(16, 185, 129, 0.2);
            bottom: -150px;
            left: -120px;
        }

        .main-container {
            max-width: 820px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* Header Navigation */
        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            color: #334155;
            font-size: 0.86rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }
        .btn-back:hover {
            background: #fff;
            transform: translateX(-2px);
            color: var(--primary);
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #064e3b;
            background: rgba(255, 255, 255, 0.8);
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid #d1fae5;
        }

        /* Hero Banner Card */
        .hero-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 30px 32px;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
            text-align: center;
        }
        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.85rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
            margin-bottom: 8px;
        }
        .hero-desc {
            color: #64748b;
            font-size: 0.95rem;
            max-width: 650px;
            margin: 0 auto;
            line-height: 1.5;
        }

        /* Tabs Switcher */
        .tab-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 24px;
        }
        .tab-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px 20px;
            border-radius: 18px;
            border: 2px solid transparent;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            text-align: center;
        }
        .tab-btn.tab-pengaduan {
            background: #fff;
            color: #64748b;
            border-color: #f1f5f9;
        }
        .tab-btn.tab-pengaduan.active {
            background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
            color: #be123c;
            border-color: #fecdd3;
            box-shadow: 0 6px 20px rgba(225, 29, 72, 0.15);
        }
        .tab-btn.tab-apresiasi {
            background: #fff;
            color: #64748b;
            border-color: #f1f5f9;
        }
        .tab-btn.tab-apresiasi.active {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #047857;
            border-color: #a7f3d0;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.15);
        }

        /* Form Card */
        .form-card {
            background: #fff;
            border-radius: 24px;
            padding: 32px 36px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.05);
        }

        .notice-banner {
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            font-size: 0.88rem;
            line-height: 1.5;
        }
        .notice-rose {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
        }
        .notice-green {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .form-section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 800;
            color: #1e293b;
            margin: 24px 0 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-section-title:first-of-type {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            display: block;
            font-size: 0.86rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
        }
        .form-label .req {
            color: #ef4444;
        }
        .form-help {
            font-size: 0.78rem;
            color: #64748b;
            margin-top: 4px;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 11px 14px;
            font-size: 0.92rem;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
            color: #0f172a;
            font-family: inherit;
            transition: all 0.2s ease;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            background: #fff;
            border-color: #0284c7;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.12);
        }
        .form-textarea {
            min-height: 110px;
            resize: vertical;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        /* Checkbox Box */
        .checkbox-box {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.2s;
        }
        .checkbox-box:hover {
            border-color: #cbd5e1;
            background: #f1f5f9;
        }
        .checkbox-box input[type="checkbox"] {
            margin-top: 3px;
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        /* Captcha Box */
        .captcha-box {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border: 1.5px dashed #94a3b8;
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .captcha-equation {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .captcha-input {
            max-width: 140px;
            font-size: 1.2rem;
            font-weight: 800;
            text-align: center;
        }

        /* Submit Buttons */
        .btn-submit {
            width: 100%;
            padding: 16px 24px;
            border: none;
            border-radius: 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.25s ease;
            margin-top: 24px;
        }
        .btn-submit-rose {
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
            color: #fff;
            box-shadow: 0 8px 24px rgba(225, 29, 72, 0.3);
        }
        .btn-submit-rose:hover {
            background: linear-gradient(135deg, #be123c 0%, #9f1239 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(225, 29, 72, 0.4);
        }
        .btn-submit-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
        }
        .btn-submit-green:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(16, 185, 129, 0.4);
        }

        .alert-error {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            font-weight: 600;
        }

        /* Footer */
        .public-footer {
            text-align: center;
            margin-top: 40px;
            color: #64748b;
            font-size: 0.85rem;
        }

        @media (max-width: 640px) {
            .hero-card {
                padding: 24px 18px;
            }
            .hero-title {
                font-size: 1.45rem;
            }
            .form-card {
                padding: 22px 18px;
            }
            .tab-wrapper {
                grid-template-columns: 1fr;
            }
            .grid-2, .grid-3 {
                grid-template-columns: 1fr;
            }
            .captcha-box {
                flex-direction: column;
                align-items: stretch;
            }
            .captcha-input {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Glow Blobs -->
    <div class="ambient-blob blob-1"></div>
    <div class="ambient-blob blob-2"></div>

    <div class="main-container">

        <!-- Top Navigation -->
        <div class="top-nav">
            <a href="{{ url('/') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Beranda Utama
            </a>
            <div class="brand-badge">
                <img src="{{ asset('logo.png') }}" style="width: 20px; height: 20px; object-fit: contain;" alt="Logo">
                <span>{{ $schoolName }}</span>
            </div>
        </div>

        <!-- Hero Card -->
        <div class="hero-card">
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #eff6ff; color: #1d4ed8; padding: 4px 12px; border-radius: 999px; font-size: 0.76rem; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; border: 1px solid #bfdbfe;">
                <i class="fas fa-shield-alt"></i> Layanan Komunikasi Terpadu Warga Sekolah
            </div>
            <h1 class="hero-title">Kanal Pengaduan & Apresiasi Karakter</h1>
            <p class="hero-desc">
                Ruang terbuka, aman, dan terpercaya bagi seluruh orang tua, siswa, guru, dan masyarakat untuk melaporkan insiden/kejadian (TPPK) atau mengabarkan perbuatan baik serta prestasi siswa.
            </p>
        </div>

        <!-- Switcher Tabs -->
        <div class="tab-wrapper">
            <a href="{{ route('public.character.index', ['tab' => 'pengaduan']) }}" class="tab-btn tab-pengaduan {{ $activeTab === 'pengaduan' ? 'active' : '' }}">
                <i class="fas fa-exclamation-triangle" style="font-size: 1.15rem;"></i>
                <div>
                    <div>Laporkan Insiden (TPPK)</div>
                    <div style="font-size: 0.72rem; font-weight: 600; opacity: 0.85;">Bullying, Kekerasan & Tata Tertib</div>
                </div>
            </a>
            <a href="{{ route('public.character.index', ['tab' => 'apresiasi']) }}" class="tab-btn tab-apresiasi {{ $activeTab === 'apresiasi' ? 'active' : '' }}">
                <i class="fas fa-award" style="font-size: 1.15rem;"></i>
                <div>
                    <div>Catat Apresiasi Kebaikan</div>
                    <div style="font-size: 0.72rem; font-weight: 600; opacity: 0.85;">Kejujuran, Akhlak & Prestasi</div>
                </div>
            </a>
        </div>

        <!-- TAB 1: FORM PENGADUAN INSIDEN / TPPK -->
        @if($activeTab === 'pengaduan')
            <div class="form-card">
                <div class="notice-banner notice-rose">
                    <i class="fas fa-user-shield" style="font-size: 1.4rem; color: #e11d48; margin-top: 2px;"></i>
                    <div>
                        <strong style="color: #9f1239;">Jaminan Kerahasiaan & Perlindungan Pelapor:</strong>
                        <div style="margin-top: 2px; color: #881337;">
                            Setiap laporan masuk akan ditinjau secara seksama dan rahasia oleh <strong>Tim Pencegahan dan Penanganan Kekerasan (TPPK)</strong> serta Bidang Kesiswaan {{ $schoolName }}. Poin pelanggaran tidak akan langsung diterapkan sebelum proses verifikasi dan klarifikasi selesai.
                        </div>
                    </div>
                </div>

                @if(session('error_case'))
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error_case') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-error">
                        <ul style="margin-left: 20px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('public.character.store-case') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Bagian 1: Identifikasi Siswa -->
                    <div class="form-section-title">
                        <i class="fas fa-user-graduate" style="color: #e11d48;"></i> 1. Siswa yang Dilaporkan
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Pilih Kelas Terlebih Dahulu <span class="req">*</span></label>
                            <select id="case_class_select" class="form-select" onchange="filterStudentsByClass('case')">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c }}">Kelas {{ $c }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Siswa Terlapor <span class="req">*</span></label>
                            <select name="student_id" id="case_student_select" class="form-select">
                                <option value="">-- Pilih Nama Siswa --</option>
                                @foreach($students as $s)
                                    <option value="{{ $s->id }}" data-class="{{ $s->class }}" {{ old('student_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }} (Kelas {{ $s->class }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Fallback Manual Input -->
                    <div class="checkbox-box" style="margin-bottom: 18px;" onclick="toggleManualInput('case')">
                        <input type="checkbox" id="case_manual_toggle">
                        <div>
                            <div style="font-size: 0.86rem; font-weight: 700; color: #334155;">Nama siswa tidak ditemukan / Siswa belum terdaftar di daftar di atas?</div>
                            <div style="font-size: 0.75rem; color: #64748b;">Centang di sini untuk mengetik nama dan kelas perkiraan secara manual.</div>
                        </div>
                    </div>

                    <div id="case_manual_box" style="display: none; background: #fff5f5; border: 1px dashed #fca5a5; padding: 14px 16px; border-radius: 12px; margin-bottom: 18px;">
                        <div class="grid-2">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Ketik Nama Siswa</label>
                                <input type="text" name="student_name_raw" value="{{ old('student_name_raw') }}" placeholder="Contoh: Fulan bin Fulan" class="form-input">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Ketik Perkiraan Kelas</label>
                                <input type="text" name="student_class_raw" value="{{ old('student_class_raw') }}" placeholder="Contoh: Kelas 3A / Tidak Tahu" class="form-input">
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 2: Kronologi Kejadian -->
                    <div class="form-section-title">
                        <i class="fas fa-file-alt" style="color: #e11d48;"></i> 2. Rincian & Kronologi Kejadian
                    </div>

                    <div class="grid-3">
                        <div class="form-group">
                            <label class="form-label">Tanggal Kejadian <span class="req">*</span></label>
                            <input type="date" name="date" class="form-input" value="{{ old('date', date('Y-m-d')) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kategori Insiden <span class="req">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Perundungan (Bullying)" {{ old('category') == 'Perundungan (Bullying)' ? 'selected' : '' }}>Perundungan (Bullying)</option>
                                <option value="Kekerasan Fisik" {{ old('category') == 'Kekerasan Fisik' ? 'selected' : '' }}>Kekerasan Fisik (Pemukulan/Dorongan)</option>
                                <option value="Kekerasan Verbal / Psikis" {{ old('category') == 'Kekerasan Verbal / Psikis' ? 'selected' : '' }}>Kekerasan Verbal (Ejekan/Hinaan)</option>
                                <option value="Kedisiplinan & Tata Tertib" {{ old('category') == 'Kedisiplinan & Tata Tertib' ? 'selected' : '' }}>Kedisiplinan & Tata Tertib</option>
                                <option value="Adab & Kesopanan" {{ old('category') == 'Adab & Kesopanan' ? 'selected' : '' }}>Adab & Kesopanan Guru/Teman</option>
                                <option value="Perusakan Fasilitas" {{ old('category') == 'Perusakan Fasilitas' ? 'selected' : '' }}>Perusakan Fasilitas Sekolah</option>
                                <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Klasifikasi TPPK Permendikbud <span class="req">*</span></label>
                            <select name="tppk_category" class="form-select" required>
                                <option value="non_tppk" {{ old('tppk_category') == 'non_tppk' ? 'selected' : '' }}>Pelanggaran Tata Tertib Biasa (Non-TPPK)</option>
                                <option value="perundungan" {{ old('tppk_category') == 'perundungan' ? 'selected' : '' }}>Perundungan / Bullying</option>
                                <option value="kekerasan_fisik" {{ old('tppk_category') == 'kekerasan_fisik' ? 'selected' : '' }}>Kekerasan Fisik</option>
                                <option value="kekerasan_psikis" {{ old('tppk_category') == 'kekerasan_psikis' ? 'selected' : '' }}>Kekerasan Psikis / Verbal</option>
                                <option value="kekerasan_seksual" {{ old('tppk_category') == 'kekerasan_seksual' ? 'selected' : '' }}>Kekerasan Seksual / Pelecehan</option>
                                <option value="diskriminasi" {{ old('tppk_category') == 'diskriminasi' ? 'selected' : '' }}>Diskriminasi & Intoleransi</option>
                                <option value="lainnya" {{ old('tppk_category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Lokasi Kejadian</label>
                            <input type="text" name="location" class="form-input" value="{{ old('location') }}" placeholder="Contoh: Di samping kantin, Dalam ruang kelas 4B, dsb">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Perkiraan Tingkat Urgensi / Keparahan <span class="req">*</span></label>
                            <select name="severity" class="form-select" required>
                                <option value="ringan" {{ old('severity') == 'ringan' ? 'selected' : '' }}>Ringan (Dapat diselesaikan secara persuasif)</option>
                                <option value="sedang" {{ old('severity') == 'sedang' ? 'selected' : '' }}>Sedang (Memerlukan pembinaan kesiswaan)</option>
                                <option value="berat" {{ old('severity') == 'berat' ? 'selected' : '' }}>Berat (Membutuhkan tindakan darurat Tim TPPK & Kepala Sekolah)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Judul Singkat Pengaduan <span class="req">*</span></label>
                        <input type="text" name="title" class="form-input" value="{{ old('title') }}" placeholder="Contoh: Kejadian saling dorong dan ejek nama orang tua saat jam istirahat" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Uraian / Kronologi Detail Kejadian <span class="req">*</span></label>
                        <textarea name="description" class="form-textarea" placeholder="Jelaskan apa yang terjadi, waktu perkiraan, saksi yang melihat, dan dampak yang dialami korban secara jujur dan jelas..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Unggah Bukti Foto / Dokumen Pendukung (Opsional)</label>
                        <input type="file" name="evidence" class="form-input" accept="image/jpeg,image/png,image/jpg,application/pdf">
                        <div class="form-help">Format didukung: JPG, PNG, PDF (Maksimal 3 MB).</div>
                    </div>

                    <!-- Bagian 3: Identitas Pelapor -->
                    <div class="form-section-title">
                        <i class="fas fa-id-card" style="color: #e11d48;"></i> 3. Identitas Pelapor
                    </div>

                    <div class="form-group">
                        <label class="form-label">Siapakah Anda? <span class="req">*</span></label>
                        <select name="reporter_type" class="form-select" required>
                            <option value="orang_tua" {{ old('reporter_type') == 'orang_tua' ? 'selected' : '' }}>Orang Tua / Wali Murid</option>
                            <option value="siswa" {{ old('reporter_type') == 'siswa' ? 'selected' : '' }}>Siswa / Teman Sebaya</option>
                            <option value="guru" {{ old('reporter_type') == 'guru' ? 'selected' : '' }}>Guru / Staf Sekolah</option>
                            <option value="masyarakat" {{ old('reporter_type') == 'masyarakat' ? 'selected' : '' }}>Warga Sekitar / Masyarakat</option>
                        </select>
                    </div>

                    <div class="checkbox-box" style="margin-bottom: 18px;" onclick="toggleAnon('case')">
                        <input type="checkbox" name="is_anonymous" value="1" id="case_anon_toggle" {{ old('is_anonymous') ? 'checked' : '' }}>
                        <div>
                            <div style="font-size: 0.88rem; font-weight: 800; color: #991b1b;">
                                <i class="fas fa-user-secret"></i> Laporkan Secara Rahasia (Anonim)
                            </div>
                            <div style="font-size: 0.76rem; color: #64748b;">
                                Nama dan kontak Anda tidak akan dicantumkan dalam laporan. Sistem hanya mencatat Anda sebagai "Warga Sekolah".
                            </div>
                        </div>
                    </div>

                    <div id="case_reporter_details" style="{{ old('is_anonymous') ? 'display: none;' : '' }}">
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap Pelapor</label>
                                <input type="text" name="reporter_name" class="form-input" value="{{ old('reporter_name') }}" placeholder="Contoh: Ayahanda Ananda Umar">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nomor WhatsApp / HP Aktif</label>
                                <input type="text" name="reporter_contact" class="form-input" value="{{ old('reporter_contact') }}" placeholder="Contoh: 0812xxxxxxxx (Untuk klarifikasi TPPK)">
                            </div>
                        </div>
                    </div>

                    <!-- Keamanan Anti-Spam -->
                    <div class="form-group" style="margin-top: 24px;">
                        <label class="form-label">Pertanyaan Keamanan Anti-Spam <span class="req">*</span></label>
                        <div class="captcha-box">
                            <div class="captcha-equation">
                                <i class="fas fa-shield-alt" style="color: #e11d48;"></i>
                                <span>Berapakah hasil dari <strong>{{ $num1 }} + {{ $num2 }}</strong> ?</span>
                            </div>
                            <input type="number" name="captcha" class="form-input captcha-input" placeholder="Jawaban" required>
                        </div>
                        <div class="form-help">Hitungan sederhana untuk memastikan laporan dikirim oleh manusia.</div>
                    </div>

                    <button type="submit" class="btn-submit btn-submit-rose">
                        <i class="fas fa-paper-plane"></i> Kirim Laporan Pengaduan ke TPPK
                    </button>
                </form>
            </div>

        <!-- TAB 2: FORM APRESIASI KEBAIKAN SISWA -->
        @else
            <div class="form-card">
                <div class="notice-banner notice-green">
                    <i class="fas fa-star" style="font-size: 1.4rem; color: #059669; margin-top: 2px;"></i>
                    <div>
                        <strong style="color: #065f46;">Apresiasi Pembiasaan Karakter & Adab:</strong>
                        <div style="margin-top: 2px; color: #047857;">
                            Terima kasih telah berbagi kabar baik! Setiap kebaikan sekecil apapun (menemukan barang teman, santun kepada guru, ibadah tertib, hingga prestasi di luar sekolah) adalah mutiara yang berharga bagi pembinaan karakter di {{ $schoolName }}.
                        </div>
                    </div>
                </div>

                @if(session('error_appr'))
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error_appr') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-error">
                        <ul style="margin-left: 20px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('public.character.store-appreciation') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Bagian 1: Identifikasi Siswa -->
                    <div class="form-section-title">
                        <i class="fas fa-user-check" style="color: #10b981;"></i> 1. Siswa Penerima Apresiasi
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Pilih Kelas <span class="req">*</span></label>
                            <select id="appr_class_select" class="form-select" onchange="filterStudentsByClass('appr')">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c }}">Kelas {{ $c }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Siswa <span class="req">*</span></label>
                            <select name="student_id" id="appr_student_select" class="form-select">
                                <option value="">-- Pilih Nama Siswa --</option>
                                @foreach($students as $s)
                                    <option value="{{ $s->id }}" data-class="{{ $s->class }}" {{ old('student_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }} (Kelas {{ $s->class }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Fallback Manual Input -->
                    <div class="checkbox-box" style="margin-bottom: 18px;" onclick="toggleManualInput('appr')">
                        <input type="checkbox" id="appr_manual_toggle">
                        <div>
                            <div style="font-size: 0.86rem; font-weight: 700; color: #334155;">Nama siswa tidak ditemukan dalam daftar?</div>
                            <div style="font-size: 0.75rem; color: #64748b;">Centang untuk mengetik nama ananda secara langsung.</div>
                        </div>
                    </div>

                    <div id="appr_manual_box" style="display: none; background: #f0fdf4; border: 1px dashed #86efac; padding: 14px 16px; border-radius: 12px; margin-bottom: 18px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Ketik Nama Siswa</label>
                            <input type="text" name="student_name_raw" value="{{ old('student_name_raw') }}" placeholder="Contoh: Fatimah Az-Zahra" class="form-input">
                        </div>
                    </div>

                    <!-- Bagian 2: Rincian Apresiasi -->
                    <div class="form-section-title">
                        <i class="fas fa-medal" style="color: #10b981;"></i> 2. Bentuk Kebaikan / Prestasi
                    </div>

                    <div class="grid-3">
                        <div class="form-group">
                            <label class="form-label">Tanggal Kebaikan <span class="req">*</span></label>
                            <input type="date" name="date" class="form-input" value="{{ old('date', date('Y-m-d')) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nilai Karakter SMILE <span class="req">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="">-- Pilih Nilai Karakter --</option>
                                <option value="Sholeh" {{ old('category') == 'Sholeh' ? 'selected' : '' }}>Sholeh (Ibadah, Mengaji, Sholat & Dzikir)</option>
                                <option value="Muslih" {{ old('category') == 'Muslih' ? 'selected' : '' }}>Muslih (Jujur, Menolong, Berbagi & Adab)</option>
                                <option value="Intellectual Curiosity" {{ old('category') == 'Intellectual Curiosity' ? 'selected' : '' }}>Intellectual (Prestasi Luar Sekolah, Membaca)</option>
                                <option value="Leadership" {{ old('category') == 'Leadership' ? 'selected' : '' }}>Leadership (Kepemimpinan, Tanggung Jawab)</option>
                                <option value="Environmentally" {{ old('category') == 'Environmentally' ? 'selected' : '' }}>Environmentally (Peduli Kebersihan & Alam)</option>
                                <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Dimensi P5 (Kurikulum)</label>
                            <select name="dimension_p5" class="form-select">
                                <option value="Berakhlak Mulia" {{ old('dimension_p5') == 'Berakhlak Mulia' ? 'selected' : '' }}>Berakhlak Mulia</option>
                                <option value="Mandiri" {{ old('dimension_p5') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                <option value="Gotong Royong" {{ old('dimension_p5') == 'Gotong Royong' ? 'selected' : '' }}>Gotong Royong</option>
                                <option value="Bernalar Kritis" {{ old('dimension_p5') == 'Bernalar Kritis' ? 'selected' : '' }}>Bernalar Kritis</option>
                                <option value="Kreatif" {{ old('dimension_p5') == 'Kreatif' ? 'selected' : '' }}>Kreatif</option>
                                <option value="Berkebinekaan Global" {{ old('dimension_p5') == 'Berkebinekaan Global' ? 'selected' : '' }}>Berkebinekaan Global</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Judul Apresiasi / Kebaikan <span class="req">*</span></label>
                        <input type="text" name="title" class="form-input" value="{{ old('title') }}" placeholder="Contoh: Mengembalikan uang teman yang terjatuh di masjid sekolah" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cerita / Kisah Kebaikan yang Dilakukan <span class="req">*</span></label>
                        <textarea name="description" class="form-textarea" placeholder="Ceritakan bagaimana ananda menunjukkan sikap terpuji atau prestasinya..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Foto Kegiatan / Piagam Pendukung (Opsional)</label>
                        <input type="file" name="evidence" class="form-input" accept="image/jpeg,image/png,image/jpg,application/pdf">
                        <div class="form-help">Format didukung: JPG, PNG, PDF (Maksimal 3 MB).</div>
                    </div>

                    <!-- Bagian 3: Identitas Pemberi Apresiasi -->
                    <div class="form-section-title">
                        <i class="fas fa-heart" style="color: #10b981;"></i> 3. Identitas Pengirim Apresiasi
                    </div>

                    <div class="form-group">
                        <label class="form-label">Siapakah Anda? <span class="req">*</span></label>
                        <select name="reporter_type" class="form-select" required>
                            <option value="orang_tua" {{ old('reporter_type') == 'orang_tua' ? 'selected' : '' }}>Orang Tua / Wali Murid</option>
                            <option value="guru" {{ old('reporter_type') == 'guru' ? 'selected' : '' }}>Guru / Pembina / Staf</option>
                            <option value="siswa" {{ old('reporter_type') == 'siswa' ? 'selected' : '' }}>Teman Sebaya</option>
                            <option value="masyarakat" {{ old('reporter_type') == 'masyarakat' ? 'selected' : '' }}>Tetangga / Warga Masyarakat</option>
                        </select>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Nama Pengirim</label>
                            <input type="text" name="reporter_name" class="form-input" value="{{ old('reporter_name') }}" placeholder="Contoh: Bundanya Aisyah / Pak RT">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor WhatsApp / HP (Opsional)</label>
                            <input type="text" name="reporter_contact" class="form-input" value="{{ old('reporter_contact') }}" placeholder="Contoh: 0812xxxxxxxx">
                        </div>
                    </div>

                    <!-- Keamanan Anti-Spam -->
                    <div class="form-group" style="margin-top: 24px;">
                        <label class="form-label">Pertanyaan Keamanan Anti-Spam <span class="req">*</span></label>
                        <div class="captcha-box">
                            <div class="captcha-equation">
                                <i class="fas fa-shield-alt" style="color: #10b981;"></i>
                                <span>Berapakah hasil dari <strong>{{ $num1 }} + {{ $num2 }}</strong> ?</span>
                            </div>
                            <input type="number" name="captcha" class="form-input captcha-input" placeholder="Jawaban" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit btn-submit-green">
                        <i class="fas fa-paper-plane"></i> Kirim Catatan Apresiasi Kebaikan
                    </button>
                </form>
            </div>
        @endif

        <div class="public-footer">
            <div>&copy; {{ date('Y') }} <strong>SIMUTQIN {{ $schoolName }}</strong>. Seluruh Hak Cipta Dilindungi.</div>
            <div style="margin-top: 6px;">Hotline Layanan Sekolah: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $schoolPhone) }}" target="_blank" style="color: #047857; font-weight: 700; text-decoration: none;"><i class="fab fa-whatsapp"></i> {{ $schoolPhone }}</a></div>
        </div>

    </div>

    <script>
        function filterStudentsByClass(type) {
            const classSelect = document.getElementById(type + '_class_select');
            const studentSelect = document.getElementById(type + '_student_select');
            const selectedClass = classSelect.value;

            for (let i = 0; i < studentSelect.options.length; i++) {
                const opt = studentSelect.options[i];
                if (!opt.value) continue; // Skip header option

                const optClass = opt.getAttribute('data-class');
                if (!selectedClass || optClass === selectedClass) {
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                }
            }

            studentSelect.value = '';
        }

        function toggleManualInput(type) {
            const chk = document.getElementById(type + '_manual_toggle');
            const box = document.getElementById(type + '_manual_box');
            if (chk.checked) {
                box.style.display = 'block';
            } else {
                box.style.display = 'none';
            }
        }

        function toggleAnon(type) {
            const chk = document.getElementById(type + '_anon_toggle');
            const box = document.getElementById(type + '_reporter_details');
            if (chk.checked) {
                box.style.display = 'none';
            } else {
                box.style.display = 'block';
            }
        }
    </script>
</body>
</html>
