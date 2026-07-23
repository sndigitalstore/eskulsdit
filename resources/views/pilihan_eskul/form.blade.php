<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - SDIT AN NADZIR</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #047857;
            --accent: #6366f1;
            --bg-color: #f1f5f9;
            --error-color: #ef4444;
            --text-color: #0f172a;
            --text-muted: #64748b;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 50%, #f8fafc 100%);
            background-attachment: fixed;
            padding: 40px 20px;
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }

        /* Ambient Pastel Blobs */
        .bg-shapes {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.55;
        }

        .shape-1 { width: 550px; height: 550px; background: #c7d2fe; top: -120px; right: -120px; }
        .shape-2 { width: 500px; height: 500px; background: #a7f3d0; bottom: -120px; left: -120px; }

        .container {
            width: 100%;
            max-width: 660px;
            z-index: 10;
            position: relative;
        }

        /* Main Glass Wizard Card */
        .wizard-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 25px 60px -10px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(255,255,255,0.6);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .banner-wrapper img {
            width: 100%;
            height: auto;
            display: block;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Stepper Header */
        .stepper-header {
            padding: 24px 30px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .stepper-track {
            position: absolute;
            top: 44px;
            left: 60px;
            right: 60px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 4px;
            z-index: 1;
        }

        .stepper-progress {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #10b981 0%, #6366f1 100%);
            border-radius: 4px;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .step-badge {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            z-index: 2;
            cursor: pointer;
        }

        .badge-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #ffffff;
            border: 2.5px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #64748b;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        }

        .step-badge.active .badge-icon {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            border-color: #ffffff;
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
            transform: scale(1.1);
        }

        .step-badge.completed .badge-icon {
            background: #6366f1;
            border-color: #ffffff;
            color: #ffffff;
        }

        .step-badge span {
            font-size: 0.78rem;
            font-weight: 700;
            color: #64748b;
            transition: color 0.3s;
        }

        .step-badge.active span { color: #0f172a; }

        /* Wizard Body Steps */
        .wizard-body {
            padding: 24px 32px 34px;
        }

        .wizard-step {
            display: none;
            opacity: 0;
            transform: translateX(20px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .wizard-step.step-active {
            display: block;
            opacity: 1;
            transform: translateX(0);
        }

        .step-title {
            margin-bottom: 24px;
        }

        .step-num {
            font-size: 0.78rem;
            font-weight: 800;
            color: #10b981;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 4px;
        }

        .step-title h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.65rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .step-title p {
            font-size: 0.92rem;
            color: #64748b;
            margin-top: 4px;
        }

        .question-label {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: block;
            color: #0f172a;
        }

        .required-star { color: var(--error-color); margin-left: 3px; }

        /* Modern Select & Inputs */
        select.input-select {
            width: 100%;
            padding: 14px 44px 14px 20px;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            font-size: 0.98rem;
            outline: none;
            background: #ffffff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E") no-repeat right 16px center/16px 16px;
            -webkit-appearance: none;
            appearance: none;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            color: #0f172a;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.02);
        }

        select.input-select:hover {
            border-color: #94a3b8;
            background-color: #f8fafc;
        }

        select.input-select:focus {
            border-color: var(--primary);
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.16);
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #10b981;
            font-size: 1.2rem;
        }

        .input-text-icon {
            width: 100%;
            padding: 15px 20px 15px 48px;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            font-size: 1rem;
            outline: none;
            transition: all 0.25s;
            background: #ffffff;
            color: #0f172a;
            font-weight: 600;
        }

        .input-text-icon:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.16);
        }

        /* Eskul Options Cards */
        .eskul-grid-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            cursor: pointer;
            border-radius: 18px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .radio-option:hover {
            background-color: #f0fdf4;
            border-color: #a7f3d0;
            transform: translateX(4px);
        }

        .radio-option:has(input[type="radio"]:checked) {
            background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%);
            border-color: #10b981;
            box-shadow: 0 4px 18px rgba(16, 185, 129, 0.2);
        }

        .radio-option input[type="radio"] {
            margin-right: 14px;
            width: 22px;
            height: 22px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .eskul-info-wrap {
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .eskul-title-text {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
        }

        .badge-available {
            background: #d1fae5;
            color: #047857;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.72rem;
            font-weight: 800;
        }

        .badge-full {
            background: #fee2e2;
            color: #b91c1c;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.72rem;
            font-weight: 800;
        }

        /* Agreement Box */
        .agreement-box {
            background: rgba(241, 245, 249, 0.7);
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            padding: 16px 20px;
        }

        .checkbox-custom-label {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
        }

        .checkbox-custom-label input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-top: 2px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .checkbox-text {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #334155;
            font-weight: 600;
        }

        /* Buttons Action Row */
        .wizard-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .btn-prev {
            background: #ffffff;
            color: #64748b;
            border: 1.5px solid #cbd5e1;
            border-radius: 50px;
            padding: 14px 28px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-prev:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
        }

        .btn-next {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 14px 32px;
            font-size: 0.98rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(16, 185, 129, 0.45);
        }

        .btn-submit {
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px 36px;
            font-size: 1.02rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.35);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.5);
        }

        .clear-form {
            color: var(--text-muted);
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            background: none;
            padding: 10px 16px;
            transition: color 0.2s;
        }

        .clear-form:hover { color: var(--error-color); }

        .error-msg {
            color: var(--error-color);
            font-size: 0.88rem;
            margin-top: 8px;
            display: flex;
            align-items: center;
            font-weight: 600;
        }

        .error-msg i { margin-right: 6px; }

        .footer-branding {
            text-align: center;
            margin-top: 25px;
            font-size: 0.88rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        @media (max-width: 580px) {
            body { padding: 20px 12px; }
            .wizard-body { padding: 20px 20px 28px; }
            .stepper-header { padding: 20px 16px 10px; }
            .stepper-track { left: 40px; right: 40px; }
        }
    </style>
</head>
<body>

    <!-- Ambient Pastel Blobs -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

<div class="container">
    <form action="{{ route('pilihan-eskul.store') }}" method="POST" id="wizard-form">
        @csrf

        <!-- Single Central Glass Wizard Card -->
        <div class="wizard-card">
            <!-- Header Banner -->
            <div class="banner-wrapper">
                <img src="{{ asset('header_banner.png') }}" alt="Header Banner">
            </div>

            <!-- Title & Ketentuan Pendaftaran -->
            <div style="padding: 24px 30px 14px; border-bottom: 1px dashed rgba(203, 213, 225, 0.8);">
                <h1 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; letter-spacing: -0.5px;">
                    {{ $title }}
                </h1>
                @if($description)
                <div style="background: rgba(240, 253, 244, 0.8); border: 1.5px solid #a7f3d0; padding: 14px 18px; border-radius: 16px; font-size: 0.88rem; color: #047857; line-height: 1.6; display: flex; gap: 12px; align-items: flex-start; margin-top: 10px;">
                    <i class="fas fa-info-circle" style="font-size: 1.1rem; color: #10b981; margin-top: 2px;"></i>
                    <div style="flex: 1;">
                        <strong style="display: block; font-weight: 700; color: #065f46; margin-bottom: 3px;">Ketentuan & Petunjuk Pendaftaran:</strong>
                        {!! nl2br(e($description)) !!}
                    </div>
                </div>
                @endif
            </div>

            <!-- Stepper Header Progress -->
            <div class="stepper-header">
                <div class="stepper-track">
                    <div class="stepper-progress" id="wizard-progress-bar"></div>
                </div>
                <div class="step-badge active" id="step-badge-1" onclick="goToStep(1)">
                    <div class="badge-icon"><i class="fas fa-user-graduate"></i></div>
                    <span>Siswa</span>
                </div>
                <div class="step-badge" id="step-badge-2" onclick="goToStep(2)">
                    <div class="badge-icon"><i class="fab fa-whatsapp"></i></div>
                    <span>Kontak</span>
                </div>
                <div class="step-badge" id="step-badge-3" onclick="goToStep(3)">
                    <div class="badge-icon"><i class="fas fa-running"></i></div>
                    <span>Pilihan</span>
                </div>
            </div>

            <!-- Wizard Body -->
            <div class="wizard-body">
                <!-- STEP 1: Identitas Siswa -->
                <div class="wizard-step step-active" id="wizard-step-1">
                    <div class="step-title">
                        <span class="step-num">Langkah 1 dari 3</span>
                        <h2>Identitas Siswa</h2>
                        <p>Pilih kelas dan cari nama siswa yang akan didaftarkan.</p>
                    </div>

                    <!-- Input Kelas -->
                    <div class="form-group-group">
                        <label class="question-label">1. Pilih Kelas <span class="required-star">*</span></label>
                        <select name="class" id="class-select" class="input-select" required onchange="loadStudents()">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls }}" {{ old('class') == $cls ? 'selected' : '' }}>Kelas {{ $cls }}</option>
                            @endforeach
                        </select>
                        @error('class')
                            <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Input Nama Siswa -->
                    <div class="form-group-group" style="margin-top: 22px;">
                        <label class="question-label">2. Nama Lengkap Siswa <span class="required-star">*</span></label>
                        <select name="student_id" id="student-select" class="input-select" disabled required>
                            <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                        </select>
                        <div style="font-size: 0.82rem; color: #64748b; margin-top: 8px;">
                            <i class="fas fa-info-circle"></i> Jika nama siswa tidak ditemukan, silakan hubungi admin sekolah.
                        </div>
                        <div id="current-eskul-info" style="display: none; margin-top: 12px; padding: 14px 18px; border-radius: 16px; font-size: 0.88rem; line-height: 1.6;">
                        </div>
                        @error('student_id')
                            <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="wizard-actions" style="margin-top: 32px;">
                        <div></div>
                        <button type="button" class="btn-next" onclick="nextStep(1)">
                            <span>Lanjut Ke Kontak</span> <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: Informasi Kontak -->
                <div class="wizard-step" id="wizard-step-2">
                    <div class="step-title">
                        <span class="step-num">Langkah 2 dari 3</span>
                        <h2>Kontak Orang Tua</h2>
                        <p>Nomor WhatsApp untuk konfirmasi pendaftaran eskul (opsional).</p>
                    </div>

                    <div class="form-group-group">
                        <label class="question-label">Nomor WhatsApp Orang Tua / Wali <span style="font-size: 0.82rem; font-weight: 500; color: #64748b;">(Opsional)</span></label>
                        <div class="input-with-icon">
                            <i class="fab fa-whatsapp input-icon"></i>
                            <input type="text" name="parent_phone" id="parent-phone-input" class="input-text-icon" placeholder="Contoh: 081234567890 (Boleh dikosongkan)" value="{{ old('parent_phone') }}">
                        </div>
                        <div style="font-size: 0.82rem; color: #64748b; margin-top: 10px;">
                            <i class="fas fa-shield-alt"></i> Boleh dikosongkan. Jika diisi, digunakan untuk bukti notifikasi pendaftaran berhasil.
                        </div>
                        @error('parent_phone')
                            <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="wizard-actions" style="margin-top: 35px;">
                        <button type="button" class="btn-prev" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left"></i> <span>Kembali</span>
                        </button>
                        <button type="button" class="btn-next" onclick="nextStep(2)">
                            <span>Lanjut Ke Pilih Eskul</span> <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 3: Pilih Eskul & Konfirmasi -->
                <div class="wizard-step" id="wizard-step-3">
                    <div class="step-title">
                        <span class="step-num">Langkah 3 dari 3</span>
                        <h2>Pilih Kegiatan Ekstrakurikuler</h2>
                        <p>Pilih 1 kegiatan eskul yang diminati siswa untuk semester ini.</p>
                    </div>

                    <div class="form-group-group">
                        <label class="question-label">Daftar Pilihan Ekstrakurikuler <span class="required-star">*</span></label>
                        <div class="eskul-grid-options">
                            @foreach($eskuls as $eskul)
                            @php $isFull = $eskul->students_count >= $quota; @endphp
                            <label class="radio-option eskul-option" data-target-group="{{ json_encode($eskul->target_groups) }}" style="{{ $isFull ? 'opacity: 0.55; cursor: not-allowed;' : '' }}">
                                <input type="radio" name="eskul_1" value="{{ $eskul->id }}" data-is-full="{{ $isFull ? 'true' : 'false' }}" {{ old('eskul_1') == $eskul->id ? 'checked' : '' }} {{ $isFull ? 'disabled' : '' }} required>
                                <div class="eskul-info-wrap">
                                    <span class="eskul-title-text">{{ $eskul->name }}</span>
                                    @if($isFull)
                                        <span class="badge-full">Penuh</span>
                                    @else
                                        <span class="badge-available">Tersedia</span>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('eskul_1')
                            <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pernyataan Setuju -->
                    <div class="agreement-box" style="margin-top: 24px;">
                        <label class="checkbox-custom-label">
                            <input type="checkbox" name="agreement" id="agreement-checkbox" value="1" required>
                            <span class="checkbox-text">
                                Dengan ini menyatakan telah memilih ekskul yang sesuai dengan minat anak kami serta menyetujui ketentuan sekolah yang ditetapkan. <span class="required-star">*</span>
                            </span>
                        </label>
                        @error('agreement')
                            <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="wizard-actions" style="margin-top: 35px;">
                        <button type="button" class="btn-prev" onclick="prevStep(3)">
                            <i class="fas fa-arrow-left"></i> <span>Kembali</span>
                        </button>
                        <button type="submit" class="btn-submit">
                            <span>Kirim Pilihan Eskul</span> <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; margin-top: 18px;">
            <button type="reset" class="clear-form">
                <i class="fas fa-undo"></i> Kosongkan Formulir
            </button>
        </div>

        <div class="footer-branding">
            SDIT AN NADZIR &copy; {{ date('Y') }}
        </div>
    </form>
</div>

<script>
    var currentStep = 1;
    var studentsData = {};

    function updateWizardProgress(step) {
        currentStep = step;
        var progressBar = document.getElementById('wizard-progress-bar');
        
        // Progress percentage
        var percent = (step - 1) * 50;
        progressBar.style.width = percent + '%';

        // Update badges
        for (let i = 1; i <= 3; i++) {
            let badge = document.getElementById('step-badge-' + i);
            let stepView = document.getElementById('wizard-step-' + i);
            
            if (i === step) {
                badge.className = 'step-badge active';
                stepView.className = 'wizard-step step-active';
            } else if (i < step) {
                badge.className = 'step-badge completed';
                stepView.className = 'wizard-step';
            } else {
                badge.className = 'step-badge';
                stepView.className = 'wizard-step';
            }
        }
    }

    function goToStep(targetStep) {
        if (targetStep > currentStep) {
            if (!validateStep(currentStep)) return;
        }
        updateWizardProgress(targetStep);
    }

    function nextStep(step) {
        if (validateStep(step)) {
            updateWizardProgress(step + 1);
        }
    }

    function prevStep(step) {
        updateWizardProgress(step - 1);
    }

    function validateStep(step) {
        if (step === 1) {
            var classSelect = document.getElementById('class-select');
            var studentSelect = document.getElementById('student-select');
            
            if (!classSelect.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Kelas',
                    text: 'Silakan pilih kelas terlebih dahulu.',
                    confirmButtonColor: '#10b981'
                });
                return false;
            }
            if (!studentSelect.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Nama Siswa',
                    text: 'Silakan pilih nama siswa dari daftar terlebih dahulu.',
                    confirmButtonColor: '#10b981'
                });
                return false;
            }
        } else if (step === 2) {
            var parentPhone = document.getElementById('parent-phone-input');
            if (parentPhone && parentPhone.value.trim().length > 0 && parentPhone.value.trim().length < 8) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No. WhatsApp Tidak Valid',
                    text: 'Jika diisi, mohon masukkan nomor WhatsApp yang valid (minimal 8 digit).',
                    confirmButtonColor: '#10b981'
                });
                return false;
            }
        }
        return true;
    }

    function loadStudents() {
        var classSelect = document.getElementById('class-select');
        var studentSelect = document.getElementById('student-select');
        var infoDiv = document.getElementById('current-eskul-info');
        
        if(infoDiv) infoDiv.style.display = 'none';
        resetFormState();

        var selectedClass = classSelect.value;
        var oldStudentId = "{{ old('student_id') }}";

        filterEskulOptions(selectedClass);

        if (!selectedClass) {
            studentSelect.innerHTML = '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
            studentSelect.disabled = true;
            return;
        }

        studentSelect.innerHTML = '<option value="">Memuat data siswa...</option>';
        studentSelect.disabled = true;

        fetch('{{ route("pilihan-eskul.students") }}?class=' + selectedClass)
            .then(response => response.json())
            .then(data => {
                studentsData = {};
                if (data.length > 0) {
                    var options = '<option value="">-- Pilih Nama Siswa --</option>';
                    data.forEach(function(student) {
                        studentsData[student.id] = student;
                        var selected = (oldStudentId == student.id) ? 'selected' : '';
                        options += '<option value="' + student.id + '" ' + selected + '>' + student.name + '</option>';
                    });
                    studentSelect.innerHTML = options;
                    studentSelect.disabled = false;

                    if (oldStudentId) {
                        setTimeout(() => {
                           var event = new Event('change');
                           studentSelect.dispatchEvent(event);
                        }, 50);
                    }
                } else {
                    studentSelect.innerHTML = '<option value="">Tidak ada data siswa di kelas ini</option>';
                    studentSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                studentSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    }

    function resetFormState() {
        var radios = document.querySelectorAll('input[name="eskul_1"]');
        var submitBtn = document.querySelector('.btn-submit');
        
        radios.forEach(r => {
             if (r.getAttribute('data-is-full') !== 'true') {
                 r.disabled = false;
                 r.parentElement.style.opacity = '1';
                 r.parentElement.style.cursor = 'pointer';
             }
        });
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.innerHTML = '<span>Kirim Pilihan Eskul</span> <i class="fas fa-paper-plane"></i>';
        }
    }

    document.getElementById('student-select').addEventListener('change', function() {
        var studentId = this.value;
        var infoDiv = document.getElementById('current-eskul-info');
        if (!infoDiv) return;

        resetFormState();
        infoDiv.style.display = 'none';

        var selectedClass = document.getElementById('class-select').value;

        if (studentId && studentsData[studentId]) {
            var s = studentsData[studentId];
            
            filterEskulOptions(selectedClass, s.can_choose_sesi_2);
            
            if (s.is_locked) {
                infoDiv.style.display = 'block';
                infoDiv.style.backgroundColor = '#ffebee';
                infoDiv.style.color = '#c62828';
                infoDiv.style.border = '1px solid #ffcdd2';
                infoDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <b>PERHATIAN!</b><br>' + s.lock_message;
                
                var radios = document.querySelectorAll('input[name="eskul_1"]');
                radios.forEach(r => {
                    r.disabled = true;
                    r.checked = false;
                    r.parentElement.style.opacity = '0.5';
                    r.parentElement.style.cursor = 'not-allowed';
                });
                
                var submitBtn = document.querySelector('.btn-submit');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                    submitBtn.innerHTML = 'Terkunci (Wajib Calistung / Tahfidz)';
                }
                
            } else if (s.is_already_registered) {
                 infoDiv.style.display = 'block';
                 infoDiv.style.backgroundColor = '#e0f7fa';
                 infoDiv.style.color = '#006064';
                 infoDiv.style.border = '1px solid #b2ebf2';
                 infoDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + s.already_registered_msg;

                 var radios = document.querySelectorAll('input[name="eskul_1"]');
                 radios.forEach(r => {
                    r.disabled = true;
                    r.checked = false;
                    r.parentElement.style.opacity = '0.5';
                    r.parentElement.style.cursor = 'not-allowed';
                 });
                 
                 var submitBtn = document.querySelector('.btn-submit');
                 if (submitBtn) {
                     submitBtn.disabled = true;
                     submitBtn.style.opacity = '0.5';
                     submitBtn.innerHTML = 'Sudah Terdaftar';
                 }

            } else if (s.current_eskul) {
                 infoDiv.style.display = 'block';
                 infoDiv.style.backgroundColor = '#e8f0fe';
                 infoDiv.style.color = '#1967d2';
                 infoDiv.style.border = 'none';
                 infoDiv.innerHTML = '<i class="fas fa-info-circle"></i> Siswa ini terdaftar di eskul: <b>' + s.current_eskul + '</b>. Memilih eskul baru akan otomatis menggantikan data lama.';
            }
        }
    });

    document.querySelector('.clear-form').addEventListener('click', (e) => {
        e.preventDefault();
        Swal.fire({
            title: 'Bersihkan Formulir?',
            text: "Seluruh data yang telah diisi akan dikosongkan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Bersihkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('wizard-form').reset();
                location.reload();
            }
        });
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonColor: '#10b981'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#10b981'
        });
    @endif

    function filterEskulOptions(className, canChooseSesi2 = false) {
        let studentGroup = null;
        if (className) {
            if (className.startsWith('1')) {
                studentGroup = canChooseSesi2 ? 'sesi_2' : 'sesi_1';
            } else if (className.startsWith('2')) {
                studentGroup = 'sesi_2';
            } else if (className.startsWith('3')) {
                studentGroup = 'sesi_3';
            } else if (className.startsWith('4') || className.startsWith('5') || className.startsWith('6')) {
                studentGroup = 'sesi_4';
            }
        }

        let options = document.querySelectorAll('.eskul-option');
        options.forEach(opt => {
            let targetGroups;
            try {
                targetGroups = JSON.parse(opt.getAttribute('data-target-group'));
            } catch(e) {
                targetGroups = [opt.getAttribute('data-target-group')];
            }
            let radio = opt.querySelector('input[type="radio"]');

            let show = !className || targetGroups.includes('all') || (studentGroup && targetGroups.includes(studentGroup));

            if (show) {
                opt.style.display = 'flex';
                let isFull = radio.getAttribute('data-is-full') === 'true';
                if (!isFull) {
                    radio.disabled = false;
                }
            } else {
                opt.style.display = 'none';
                radio.disabled = true;
                radio.checked = false;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var classSelect = document.getElementById('class-select');
        if (classSelect && classSelect.value) {
            loadStudents();
            filterEskulOptions(classSelect.value);
        }
        updateWizardProgress(1);
    });
</script>
</body>
</html>

