@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@push('styles')
<style>
    /* Settings Page Modern Styles */
    :root {
        --settings-emerald: #059669;
        --settings-emerald-dark: #064e3b;
        --settings-emerald-light: #ecfdf5;
        --settings-teal: #0d9488;
        --settings-amber: #f59e0b;
        --settings-sky: #0284c7;
        --settings-purple: #7c3aed;
        --settings-slate: #0f172a;
        --settings-border: #e2e8f0;
    }

    .settings-main-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.04), 0 4px 6px -2px rgba(15, 23, 42, 0.02);
        padding: 28px;
        max-width: 1000px;
        margin: 0 auto;
        position: relative;
    }

    /* Top Header */
    .settings-hero-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding-bottom: 22px;
        border-bottom: 1px solid #edf2f7;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .hero-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .hero-header-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        color: var(--settings-emerald);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.12);
        flex-shrink: 0;
    }

    .hero-header-title {
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--settings-slate);
        letter-spacing: -0.01em;
        margin: 0;
        line-height: 1.2;
    }

    .hero-header-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 3px;
        font-weight: 500;
    }

    .btn-save-master {
        background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
        color: #ffffff;
        border: none;
        padding: 10px 22px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.25);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: inherit;
    }

    .btn-save-master:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.35);
        background: linear-gradient(135deg, #047857 0%, #0f766e 100%);
    }

    /* Segmented Navigation Tabs */
    .settings-nav-tabs {
        display: flex;
        gap: 8px;
        background: #f8fafc;
        padding: 6px;
        border-radius: 16px;
        border: 1px solid #edf2f7;
        margin-bottom: 24px;
        overflow-x: auto;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .settings-nav-tabs::-webkit-scrollbar {
        display: none;
    }

    .tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border-radius: 12px;
        border: 1px solid transparent;
        background: transparent;
        color: #64748b;
        font-weight: 600;
        font-size: 0.86rem;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .tab-btn:hover {
        color: var(--settings-slate);
        background: rgba(255, 255, 255, 0.6);
    }

    .tab-btn.active {
        background: #ffffff;
        color: var(--settings-emerald);
        border-color: #d1fae5;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        font-weight: 700;
    }

    .tab-btn i {
        font-size: 0.95rem;
    }

    /* Tab Panes */
    .tab-pane {
        display: none;
        animation: tabFadeIn 0.35s ease forwards;
    }

    .tab-pane.active {
        display: block;
    }

    @keyframes tabFadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Section Cards Inside Tabs */
    .settings-section-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 22px 24px;
        margin-bottom: 22px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }

    .section-box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .box-title-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .box-icon-pill {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .box-title-text {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--settings-slate);
        margin: 0;
    }

    .box-subtitle-text {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 1px;
    }

    /* Modern Form Controls */
    .form-group-modern {
        margin-bottom: 18px;
    }

    .form-label-modern {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.84rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 7px;
    }

    .form-label-modern .req-star {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-control-modern {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        font-size: 0.9rem;
        color: #1e293b;
        font-family: inherit;
        transition: all 0.2s ease;
        outline: none;
    }

    .form-control-modern:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        background: #ffffff;
    }

    .input-hint-text {
        font-size: 0.76rem;
        color: #64748b;
        margin-top: 5px;
        display: block;
        line-height: 1.4;
    }

    /* Grids */
    .grid-col-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .grid-col-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    /* Status Badges in Select */
    .select-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 999px;
    }

    /* Template Tags Pill Bar */
    .tag-pills-bar {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .tag-insert-btn {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
        font-family: monospace;
    }

    .tag-insert-btn:hover {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #047857;
    }

    /* User Account Cards */
    .user-account-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px;
        transition: all 0.2s ease;
    }

    .user-account-card:hover {
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        border-color: #cbd5e1;
    }

    .user-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid #edf2f7;
    }

    .user-card-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .user-card-name {
        font-weight: 700;
        font-size: 0.92rem;
        color: var(--settings-slate);
        line-height: 1.2;
    }

    .user-card-role {
        font-size: 0.72rem;
        font-weight: 600;
        margin-top: 2px;
    }

    /* Password eye input */
    .input-password-wrap {
        position: relative;
    }

    .input-password-wrap input {
        padding-right: 36px;
    }

    .toggle-password-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px;
        font-size: 0.85rem;
    }

    .toggle-password-btn:hover {
        color: #475569;
    }

    /* Color Palette Presets */
    .color-preset-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .color-preset-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .color-preset-pill:hover {
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    .color-preset-circle {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    /* Backup & Danger Zone */
    .backup-hero-card {
        background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
        border: 1px solid #bbf7d0;
        border-radius: 18px;
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .btn-download-backup {
        background: #059669;
        color: white;
        text-decoration: none;
        padding: 12px 22px;
        border-radius: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.25);
        transition: all 0.2s ease;
        font-size: 0.92rem;
    }

    .btn-download-backup:hover {
        background: #047857;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(5, 150, 105, 0.35);
    }

    .danger-hero-card {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        border-radius: 18px;
        padding: 22px 24px;
    }

    .danger-action-row {
        background: white;
        border: 1px solid #ffe4e6;
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .btn-danger-clean {
        background: #e11d48;
        color: white;
        border: none;
        padding: 9px 18px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 3px 10px rgba(225, 29, 72, 0.2);
        transition: all 0.2s ease;
    }

    .btn-danger-clean:hover {
        background: #be123c;
        transform: translateY(-1px);
    }

    /* Bottom Floating/Sticky Save Action Bar */
    .settings-bottom-bar {
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #edf2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .settings-main-card {
            padding: 18px 16px;
            border-radius: 18px;
        }

        .settings-hero-header {
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
        }

        .btn-save-master {
            width: 100%;
            justify-content: center;
            padding: 11px 20px;
        }

        .grid-col-3, .grid-col-2 {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .backup-hero-card {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-download-backup {
            width: 100%;
            justify-content: center;
        }

        .danger-action-row {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-danger-clean {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-main-card">

    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 14px 18px; border-radius: 14px; margin-bottom: 22px; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.9rem;">
            <i class="fas fa-check-circle" style="color: #059669; font-size: 1.15rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" id="mainSettingsForm">
        @csrf

        <!-- Top Hero Header -->
        <div class="settings-hero-header">
            <div class="hero-header-left">
                <div class="hero-header-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <div>
                    <h2 class="hero-header-title">Konfigurasi Aplikasi</h2>
                    <div class="hero-header-subtitle">
                        Kelola formulir eskul, tahun ajaran, WhatsApp gateway, dan seluruh akun staf SIMUTQIN.
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-save-master" id="btnSubmitTop">
                <i class="fas fa-save"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>

        <!-- Segmented Navigation Tabs -->
        <div class="settings-nav-tabs">
            <button type="button" class="tab-btn active" data-tab="tab-formulir">
                <i class="fas fa-calendar-alt"></i>
                <span>Periode & Formulir</span>
            </button>
            <button type="button" class="tab-btn" data-tab="tab-whatsapp">
                <i class="fab fa-whatsapp" style="color: #10b981;"></i>
                <span>WhatsApp Gateway</span>
            </button>
            <button type="button" class="tab-btn" data-tab="tab-identitas">
                <i class="fas fa-school"></i>
                <span>Identitas & Branding</span>
            </button>
            <button type="button" class="tab-btn" data-tab="tab-users">
                <i class="fas fa-users-cog"></i>
                <span>Kelola Akun Staf</span>
            </button>
            <button type="button" class="tab-btn" data-tab="tab-backup">
                <i class="fas fa-shield-alt"></i>
                <span>Backup & Sistem</span>
            </button>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 1: PERIODE & FORMULIR                                -->
        <!-- ======================================================== -->
        <div class="tab-pane active" id="tab-formulir">
            <div class="settings-section-box">
                <div class="section-box-header">
                    <div class="box-title-group">
                        <div class="box-icon-pill" style="background: #ecfdf5; color: #059669;">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div>
                            <div class="box-title-text">Pengaturan Pendaftaran Ekstrakurikuler</div>
                            <div class="box-subtitle-text">Kontrol judul, petunjuk pengisian wali murid, dan status akses formulir online.</div>
                        </div>
                    </div>
                </div>

                <div class="form-group-modern">
                    <label class="form-label-modern" for="form_title">
                        <span>Judul Formulir Pilihan Eskul <span class="req-star">*</span></span>
                    </label>
                    <input type="text" 
                           id="form_title" 
                           name="form_title" 
                           class="form-control-modern" 
                           value="{{ $settings['form_title'] ?? 'Pilihan Ekstrakurikuler' }}" 
                           required>
                    <span class="input-hint-text">Judul yang akan ditampilkan di bagian paling atas formulir pendaftaran wali murid.</span>
                </div>

                <div class="form-group-modern">
                    <label class="form-label-modern" for="form_description">
                        <span>Deskripsi / Petunjuk Pengisian Formulir <span class="req-star">*</span></span>
                    </label>
                    <textarea id="form_description" 
                              name="form_description" 
                              class="form-control-modern" 
                              rows="3" 
                              required>{{ $settings['form_description'] ?? 'Silakan lengkapi data ananda untuk memilih kegiatan ekstrakurikuler.' }}</textarea>
                    <span class="input-hint-text">Tuliskan pesan pembuka, sambutan, atau ketentuan eskul yang perlu dibaca orang tua.</span>
                </div>

                <!-- 3 Columns for Academic Year, Semester, and Status -->
                <div class="grid-col-3" style="margin-top: 14px;">
                    <div class="form-group-modern">
                        <label class="form-label-modern" for="active_academic_year_id">
                            <span>Tahun Ajaran Aktif</span>
                            <a href="/academic-years" style="font-size: 0.76rem; color: #0284c7; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fas fa-external-link-alt"></i> Kelola
                            </a>
                        </label>
                        <select name="active_academic_year_id" id="active_academic_year_id" class="form-control-modern" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_active ? '(Sedang Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <span class="input-hint-text">Tahun ajaran default untuk pencatatan nilai & presensi.</span>
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern" for="active_semester">
                            <span>Semester Data</span>
                        </label>
                        <select name="active_semester" id="active_semester" class="form-control-modern">
                            <option value="1" {{ ($settings['active_semester'] ?? '1') == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                            <option value="2" {{ ($settings['active_semester'] ?? '') == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                        </select>
                        <span class="input-hint-text">Semester yang sedang berjalan saat ini.</span>
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern" for="form_status">
                            <span>Status Formulir Pendaftaran</span>
                        </label>
                        <select name="form_status" id="form_status" class="form-control-modern">
                            <option value="open" {{ ($settings['form_status'] ?? 'open') == 'open' ? 'selected' : '' }}>🟢 Dibuka (Wali Bisa Memilih)</option>
                            <option value="closed" {{ ($settings['form_status'] ?? '') == 'closed' ? 'selected' : '' }}>🔴 Ditutup (Pendaftaran Dikunci)</option>
                        </select>
                        <span class="input-hint-text">Bila ditutup, halaman pendaftaran akan otomatis dinonaktifkan.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 2: WHATSAPP GATEWAY                                  -->
        <!-- ======================================================== -->
        <div class="tab-pane" id="tab-whatsapp">
            <div class="settings-section-box">
                <div class="section-box-header">
                    <div class="box-title-group">
                        <div class="box-icon-pill" style="background: #ecfdf5; color: #10b981;">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <div class="box-title-text">Integrasi WhatsApp Gateway</div>
                            <div class="box-subtitle-text">Kirimkan notifikasi instan otomatis ke nomor WhatsApp orang tua saat santri berhasil mendaftar eskul.</div>
                        </div>
                    </div>
                </div>

                <div class="grid-col-2">
                    <div class="form-group-modern">
                        <label class="form-label-modern" for="wa_gateway_enabled">
                            <span>Status Pengiriman Otomatis</span>
                        </label>
                        <select name="wa_gateway_enabled" id="wa_gateway_enabled" class="form-control-modern">
                            <option value="no" {{ ($settings['wa_gateway_enabled'] ?? 'no') == 'no' ? 'selected' : '' }}>⚪ Non-Aktif (Jangan Kirim Pesan)</option>
                            <option value="yes" {{ ($settings['wa_gateway_enabled'] ?? '') == 'yes' ? 'selected' : '' }}>🟢 Aktif (Kirim Notifikasi Otomatis)</option>
                        </select>
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern" for="wa_gateway_provider">
                            <span>Provider Layanan WhatsApp</span>
                        </label>
                        <select name="wa_gateway_provider" id="wa_gateway_provider" class="form-control-modern">
                            <option value="fonnte" {{ ($settings['wa_gateway_provider'] ?? 'fonnte') == 'fonnte' ? 'selected' : '' }}>Fonnte (api.fonnte.com)</option>
                            <option value="wablas" {{ ($settings['wa_gateway_provider'] ?? '') == 'wablas' ? 'selected' : '' }}>Wablas (api.wablas.com)</option>
                        </select>
                    </div>
                </div>

                <div class="grid-col-2">
                    <div class="form-group-modern">
                        <label class="form-label-modern" for="wa_gateway_token">
                            <span>API Token / Secret Key</span>
                        </label>
                        <div class="input-password-wrap">
                            <input type="password" 
                                   id="wa_gateway_token" 
                                   name="wa_gateway_token" 
                                   class="form-control-modern" 
                                   value="{{ $settings['wa_gateway_token'] ?? '' }}" 
                                   placeholder="Tempel token API Fonnte / Wablas">
                            <button type="button" class="toggle-password-btn" onclick="togglePass('wa_gateway_token', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="input-hint-text">Didapat dari dashboard akun Fonnte atau Wablas sekolah.</span>
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern" for="wa_gateway_sender">
                            <span>Nomor WhatsApp Pengirim (Opsional)</span>
                        </label>
                        <input type="text" 
                               id="wa_gateway_sender" 
                               name="wa_gateway_sender" 
                               class="form-control-modern" 
                               value="{{ $settings['wa_gateway_sender'] ?? '' }}" 
                               placeholder="Contoh: 08123456789 (Khusus Wablas)">
                        <span class="input-hint-text">Kosongkan jika menggunakan Fonnte standar.</span>
                    </div>
                </div>

                <div class="form-group-modern" style="margin-top: 6px;">
                    <label class="form-label-modern" for="wa_message_template">
                        <span>Format / Template Pesan Sukses Pendaftaran</span>
                    </label>
                    <textarea id="wa_message_template" 
                              name="wa_message_template" 
                              class="form-control-modern" 
                              rows="4" 
                              placeholder="Ketik draf pesan WhatsApp...">{{ $settings['wa_message_template'] ?? "Assalamu'alaikum Bapak/Ibu,\n\nAlhamdulillah pendaftaran kegiatan ekstrakurikuler untuk:\nNama: {nama_siswa}\nKelas: {kelas}\nEkstrakurikuler: {nama_eskul}\nTahun Ajaran: {tahun_ajaran}\n\nTelah berhasil tersimpan di sistem SIMUTQIN SDIT AN NADZIR. Jazakumullahu khairan." }}</textarea>
                    
                    <div style="margin-top: 8px;">
                        <span style="font-size: 0.75rem; color: #475569; font-weight: 600;">Klik placeholder berikut untuk memasukkan ke template:</span>
                        <div class="tag-pills-bar">
                            <button type="button" class="tag-insert-btn" onclick="insertTag('{nama_siswa}')">+ {nama_siswa}</button>
                            <button type="button" class="tag-insert-btn" onclick="insertTag('{kelas}')">+ {kelas}</button>
                            <button type="button" class="tag-insert-btn" onclick="insertTag('{nama_eskul}')">+ {nama_eskul}</button>
                            <button type="button" class="tag-insert-btn" onclick="insertTag('{tahun_ajaran}')">+ {tahun_ajaran}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 3: IDENTITAS & BRANDING                              -->
        <!-- ======================================================== -->
        <div class="tab-pane" id="tab-identitas">
            <div class="grid-col-2">
                
                <!-- Identitas Sekolah -->
                <div class="settings-section-box">
                    <div class="section-box-header">
                        <div class="box-title-group">
                            <div class="box-icon-pill" style="background: #e0f2fe; color: #0284c7;">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div>
                                <div class="box-title-text">Identitas Sekolah</div>
                                <div class="box-subtitle-text">Informasi untuk rapor dan dokumen cetak.</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern" for="headmaster_name">
                            <span>Nama Kepala Sekolah</span>
                        </label>
                        <input type="text" 
                               id="headmaster_name" 
                               name="headmaster_name" 
                               class="form-control-modern" 
                               value="{{ $settings['headmaster_name'] ?? 'Nur\'asiah, S.Pd.I' }}">
                        <span class="input-hint-text">Akan tercetak di lembar rapor & sertifikat resmi.</span>
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern" for="app_credits">
                            <span>Teks Footer (Credits)</span>
                        </label>
                        <input type="text" 
                               id="app_credits" 
                               name="app_credits" 
                               class="form-control-modern" 
                               value="{{ $settings['app_credits'] ?? '' }}" 
                               placeholder="Contoh: © {{ date('Y') }} SDIT AN NADZIR (Najibudin, S.Pd).">
                        <span class="input-hint-text">Ditampilkan pada catatan kaki setiap halaman.</span>
                    </div>
                </div>

                <!-- Tema Warna & Branding -->
                <div class="settings-section-box">
                    <div class="section-box-header">
                        <div class="box-title-group">
                            <div class="box-icon-pill" style="background: #ede9fe; color: #7c3aed;">
                                <i class="fas fa-palette"></i>
                            </div>
                            <div>
                                <div class="box-title-text">Tema Warna Aplikasi</div>
                                <div class="box-subtitle-text">Sesuaikan warna sidebar & tombol aksen.</div>
                            </div>
                        </div>
                    </div>

                    <div class="grid-col-2">
                        <div class="form-group-modern">
                            <label class="form-label-modern" for="app_primary_color">
                                <span>Warna Utama (Sidebar)</span>
                            </label>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="color" 
                                       id="app_primary_color" 
                                       name="app_primary_color" 
                                       value="{{ $settings['app_primary_color'] ?? '#0f172a' }}" 
                                       style="width: 44px; height: 42px; border: 1px solid #cbd5e1; border-radius: 8px; cursor: pointer; padding: 2px;">
                                <input type="text" 
                                       id="app_primary_color_hex" 
                                       class="form-control-modern" 
                                       value="{{ $settings['app_primary_color'] ?? '#0f172a' }}" 
                                       style="font-family: monospace;" 
                                       oninput="document.getElementById('app_primary_color').value = this.value">
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern" for="app_accent_color">
                                <span>Warna Aksen (Tombol)</span>
                            </label>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="color" 
                                       id="app_accent_color" 
                                       name="app_accent_color" 
                                       value="{{ $settings['app_accent_color'] ?? '#059669' }}" 
                                       style="width: 44px; height: 42px; border: 1px solid #cbd5e1; border-radius: 8px; cursor: pointer; padding: 2px;">
                                <input type="text" 
                                       id="app_accent_color_hex" 
                                       class="form-control-modern" 
                                       value="{{ $settings['app_accent_color'] ?? '#059669' }}" 
                                       style="font-family: monospace;" 
                                       oninput="document.getElementById('app_accent_color').value = this.value">
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 10px;">
                        <span style="font-size: 0.75rem; color: #475569; font-weight: 600;">Preset Warna Cepat:</span>
                        <div class="color-preset-bar">
                            <button type="button" class="color-preset-pill" onclick="setPresetColor('#064e3b', '#059669')">
                                <span class="color-preset-circle" style="background: #059669;"></span> Emerald Qur'ani
                            </button>
                            <button type="button" class="color-preset-pill" onclick="setPresetColor('#0f172a', '#0284c7')">
                                <span class="color-preset-circle" style="background: #0284c7;"></span> Sky Oceanic
                            </button>
                            <button type="button" class="color-preset-pill" onclick="setPresetColor('#1e1b4b', '#6366f1')">
                                <span class="color-preset-circle" style="background: #6366f1;"></span> Royal Indigo
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 4: KELOLA AKUN STAF                                  -->
        <!-- ======================================================== -->
        <div class="tab-pane" id="tab-users">
            <div class="settings-section-box">
                <div class="section-box-header">
                    <div class="box-title-group">
                        <div class="box-icon-pill" style="background: #fdf4ff; color: #a855f7;">
                            <i class="fas fa-users-gear"></i>
                        </div>
                        <div>
                            <div class="box-title-text">Kelola Akun & Hak Akses Pengguna</div>
                            <div class="box-subtitle-text">Pengaturan kredensial login akun Administrator, Kepala Sekolah, dan Tim Kurikulum.</div>
                        </div>
                    </div>
                </div>

                <div class="grid-col-2" style="gap: 18px;">
                    
                    <!-- Admin Utama (User Saat Ini) -->
                    <div class="user-account-card">
                        <div class="user-card-header">
                            <div class="user-card-avatar" style="background: #ecfdf5; color: #059669;">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div style="flex: 1;">
                                <div class="user-card-name">Admin Utama (Akun Aktif)</div>
                                <span class="user-card-role" style="color: #059669;">Super Administrator</span>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern" for="admin_name">Nama Lengkap</label>
                            <input type="text" id="admin_name" name="admin_name" class="form-control-modern" value="{{ Auth::user()->name }}" required>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern" for="change_password">Ganti Password Baru</label>
                            <div class="input-password-wrap">
                                <input type="password" id="change_password" name="change_password" class="form-control-modern" placeholder="Kosongkan jika tidak ingin diubah">
                                <button type="button" class="toggle-password-btn" onclick="togglePass('change_password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Admin 2 (Operator) -->
                    <div class="user-account-card">
                        <div class="user-card-header">
                            <div class="user-card-avatar" style="background: #f1f5f9; color: #475569;">
                                <i class="fas fa-user-gear"></i>
                            </div>
                            <div style="flex: 1;">
                                <div class="user-card-name">Admin 2 (Operator Sekolah)</div>
                                <span class="user-card-role" style="color: #475569;">Operator Administrasi</span>
                            </div>
                        </div>

                        @if($admin2)
                            <div class="form-group-modern">
                                <label class="form-label-modern" for="admin2_name">Nama Lengkap</label>
                                <input type="text" id="admin2_name" name="admin2_name" class="form-control-modern" value="{{ $admin2->name }}" required>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern" for="admin2_username">Username</label>
                                <input type="text" id="admin2_username" name="admin2_username" class="form-control-modern" value="{{ $admin2->username }}" required>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern" for="admin2_password">Ganti Password Baru</label>
                                <div class="input-password-wrap">
                                    <input type="password" id="admin2_password" name="admin2_password" class="form-control-modern" placeholder="Kosongkan jika tidak diubah">
                                    <button type="button" class="toggle-password-btn" onclick="togglePass('admin2_password', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <p style="color: #ef4444; font-size: 0.85rem;"><i class="fas fa-exclamation-circle"></i> Akun Admin 2 belum dibuat.</p>
                        @endif
                    </div>

                    <!-- Kepala Sekolah -->
                    <div class="user-account-card">
                        <div class="user-card-header">
                            <div class="user-card-avatar" style="background: #fef3c7; color: #b45309;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div style="flex: 1;">
                                <div class="user-card-name">Kepala Sekolah</div>
                                <span class="user-card-role" style="color: #b45309;">Akses Pengawasan & Rapor</span>
                            </div>
                        </div>

                        @if($headmaster)
                            <div class="form-group-modern">
                                <label class="form-label-modern" for="headmaster_user_name">Nama Lengkap</label>
                                <input type="text" id="headmaster_user_name" name="headmaster_user_name" class="form-control-modern" value="{{ $headmaster->name }}" required>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern" for="headmaster_user_username">Username</label>
                                <input type="text" id="headmaster_user_username" name="headmaster_user_username" class="form-control-modern" value="{{ $headmaster->username }}" required>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern" for="headmaster_user_password">Ganti Password Baru</label>
                                <div class="input-password-wrap">
                                    <input type="password" id="headmaster_user_password" name="headmaster_user_password" class="form-control-modern" placeholder="Kosongkan jika tidak diubah">
                                    <button type="button" class="toggle-password-btn" onclick="togglePass('headmaster_user_password', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <p style="color: #ef4444; font-size: 0.85rem;"><i class="fas fa-exclamation-circle"></i> Akun Kepala Sekolah belum dibuat.</p>
                        @endif
                    </div>

                    <!-- Tim Kurikulum -->
                    <div class="user-account-card" style="background: #f0f9ff; border-color: #bae6fd;">
                        <div class="user-card-header">
                            <div class="user-card-avatar" style="background: #e0f2fe; color: #0284c7;">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div style="flex: 1;">
                                <div class="user-card-name">Tim Kurikulum</div>
                                <span class="user-card-role" style="color: #0284c7;">SMILE Kurikulum & Tahfidz</span>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern" for="curriculum_user_name">Nama Lengkap</label>
                            <input type="text" id="curriculum_user_name" name="curriculum_user_name" class="form-control-modern" value="{{ $curriculum->name ?? 'Tim Kurikulum SDIT AN NADZIR' }}" required>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern" for="curriculum_user_username">Username</label>
                            <input type="text" id="curriculum_user_username" name="curriculum_user_username" class="form-control-modern" value="{{ $curriculum->username ?? 'kurikulum' }}" required>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern" for="curriculum_user_password">Ganti Password Baru</label>
                            <div class="input-password-wrap">
                                <input type="password" id="curriculum_user_password" name="curriculum_user_password" class="form-control-modern" placeholder="{{ $curriculum ? 'Kosongkan jika tidak diubah' : 'Default: kurikulum123' }}">
                                <button type="button" class="toggle-password-btn" onclick="togglePass('curriculum_user_password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 5: BACKUP & SISTEM                                   -->
        <!-- ======================================================== -->
        <div class="tab-pane" id="tab-backup">
            
            <!-- 1-Klik Backup Card -->
            <div class="backup-hero-card">
                <div style="max-width: 620px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                        <span style="background: #10b981; color: white; padding: 2px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase;">Aman & Cepat</span>
                        <h4 style="margin: 0; color: #065f46; font-size: 1.15rem; font-weight: 800;">
                            Pencadangan Database Sistem (1-Klik Backup)
                        </h4>
                    </div>
                    <p style="color: #374151; font-size: 0.85rem; margin: 0 0 10px; line-height: 1.5;">
                        Unduh seluruh data santri, setoran Al-Qur'an, mutaba'ah karakter SMILE, rapor, dan konfigurasi aplikasi langsung ke file SQL murni (<code>.sql</code>). File ini siap digunakan untuk restore sewaktu-waktu melalui cPanel phpMyAdmin.
                    </p>
                    <div style="display: flex; gap: 14px; font-size: 0.78rem; color: #047857; flex-wrap: wrap; font-weight: 600;">
                        <span><i class="fas fa-check-circle"></i> Langsung Unduh</span>
                        <span><i class="fas fa-shield-alt"></i> Data Terstruktur Utuh</span>
                        <span><i class="fas fa-bolt"></i> Siap Pakai di Hosting</span>
                    </div>
                </div>

                <div>
                    <a href="{{ route('settings.backup') }}" class="btn-download-backup">
                        <i class="fas fa-download"></i> Unduh Database (.sql)
                    </a>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="danger-hero-card">
                <div style="display: flex; align-items: center; gap: 8px; color: #be123c;">
                    <i class="fas fa-triangle-exclamation" style="font-size: 1.1rem;"></i>
                    <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800;">Zona Bahaya (Danger Zone)</h4>
                </div>
                <p style="color: #881337; font-size: 0.8rem; margin: 4px 0 0;">
                    Tindakan di bawah ini bersifat permanen dan tidak dapat dibatalkan di basis data.
                </p>

                <div class="danger-action-row">
                    <div>
                        <div style="font-weight: 700; font-size: 0.92rem; color: #881337;">Bersihkan Riwayat Log Aktivitas</div>
                        <div style="font-size: 0.78rem; color: #9f1239; margin-top: 2px;">
                            Menghapus seluruh rekaman log aktivitas audit sistem yang tersimpan untuk meringankan ukuran basis data.
                        </div>
                    </div>
                    <button type="button" onclick="confirmClearLogs()" class="btn-danger-clean">
                        <i class="fas fa-trash-can"></i> Bersihkan Log
                    </button>
                </div>
            </div>

        </div>

        <!-- Bottom Action Bar -->
        <div class="settings-bottom-bar">
            <div style="font-size: 0.8rem; color: #64748b; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-circle-info" style="color: #059669;"></i>
                <span>Perubahan yang Anda lakukan akan tersimpan secara terpadu di seluruh modul SIMUTQIN.</span>
            </div>

            <button type="submit" class="btn-save-master" id="btnSubmitBottom">
                <i class="fas fa-save"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>

    </form>

    <!-- Hidden Form for Clear Logs -->
    <form id="clearLogsForm" action="{{ route('settings.clear-logs') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>

<script>
    // Tab Switching Logic with Hash Support
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');

        function activateTab(tabId) {
            tabButtons.forEach(btn => {
                if (btn.getAttribute('data-tab') === tabId) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            tabPanes.forEach(pane => {
                if (pane.id === tabId) {
                    pane.classList.add('active');
                } else {
                    pane.classList.remove('active');
                }
            });

            window.location.hash = tabId.replace('tab-', '');
        }

        tabButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                activateTab(targetTab);
            });
        });

        // Check if URL has hash
        if (window.location.hash) {
            const hashTab = 'tab-' + window.location.hash.replace('#', '');
            const targetPane = document.getElementById(hashTab);
            if (targetPane) {
                activateTab(hashTab);
            }
        }

        // Color input sync with hex text
        const primaryColorPicker = document.getElementById('app_primary_color');
        const primaryColorHex = document.getElementById('app_primary_color_hex');
        if (primaryColorPicker && primaryColorHex) {
            primaryColorPicker.addEventListener('input', function() {
                primaryColorHex.value = this.value;
            });
        }

        const accentColorPicker = document.getElementById('app_accent_color');
        const accentColorHex = document.getElementById('app_accent_color_hex');
        if (accentColorPicker && accentColorHex) {
            accentColorPicker.addEventListener('input', function() {
                accentColorHex.value = this.value;
            });
        }

        // Form submit micro-interaction feedback
        const form = document.getElementById('mainSettingsForm');
        form.addEventListener('submit', function() {
            const btns = [document.getElementById('btnSubmitTop'), document.getElementById('btnSubmitBottom')];
            btns.forEach(btn => {
                if (btn) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Menyimpan...</span>';
                    btn.style.opacity = '0.85';
                    btn.style.pointerEvents = 'none';
                }
            });
        });
    });

    // Toggle Password Visibility
    function togglePass(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Insert Tag into WhatsApp Template Textarea
    function insertTag(tag) {
        const textarea = document.getElementById('wa_message_template');
        if (!textarea) return;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;

        textarea.value = text.substring(0, start) + tag + text.substring(end);
        textarea.selectionStart = textarea.selectionEnd = start + tag.length;
        textarea.focus();
    }

    // Set Preset Colors
    function setPresetColor(primary, accent) {
        document.getElementById('app_primary_color').value = primary;
        document.getElementById('app_primary_color_hex').value = primary;
        document.getElementById('app_accent_color').value = accent;
        document.getElementById('app_accent_color_hex').value = accent;
    }

    // SweetAlert2 Clear Logs Confirmation
    function confirmClearLogs() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Bersihkan Riwayat Log?',
                text: 'Seluruh riwayat log audit aktivitas akan dihapus permanen dari basis data.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fas fa-trash-can"></i> Ya, Bersihkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('clearLogsForm').submit();
                }
            });
        } else {
            if (confirm('Apakah Anda yakin ingin menghapus SELURUH riwayat log aktivitas? Tindakan ini permanen.')) {
                document.getElementById('clearLogsForm').submit();
            }
        }
    }
</script>
@endsection
