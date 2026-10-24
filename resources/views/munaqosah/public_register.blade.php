<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Munaqosah Qur'an – SDIT AN NADZIR</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #059669;
            --primary-dark: #065f46;
            --accent: #0f766e;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f8fafc 100%);
            min-height: 100vh;
            padding: 30px 16px 60px 16px;
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            width: 100%;
            max-width: 680px;
            margin: 0 auto;
        }
        .header-logo-card {
            text-align: center;
            margin-bottom: 24px;
        }
        .logo-img {
            width: 78px;
            height: 78px;
            object-fit: contain;
            filter: drop-shadow(0 4px 10px rgba(5,150,105,0.2));
            margin-bottom: 12px;
        }
        .school-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.55rem;
            font-weight: 800;
            color: #065f46;
            letter-spacing: -0.5px;
            line-height: 1.25;
        }
        .school-subtitle {
            font-size: 0.88rem;
            color: #047857;
            font-weight: 600;
            margin-top: 4px;
        }
        .badge-year {
            display: inline-block;
            background: white;
            border: 1px solid #a7f3d0;
            color: #065f46;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
            margin-top: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }
        .form-main-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 12px 40px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .card-hero {
            background: linear-gradient(135deg, #065f46 0%, #059669 100%);
            padding: 24px 28px;
            color: white;
            position: relative;
        }
        .card-hero h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .card-hero p {
            font-size: 0.84rem;
            opacity: 0.92;
            line-height: 1.45;
        }
        .form-body {
            padding: 28px 24px;
        }
        .form-section {
            margin-bottom: 24px;
        }
        .section-label {
            font-size: 0.9rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
            display: block;
        }
        .type-selector-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .type-card {
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .type-card.active-tahfidz {
            border-color: #059669;
            background: #f0fdf4;
        }
        .type-card.active-tilawati {
            border-color: #d97706;
            background: #fffbeb;
        }
        .input-control {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            font-size: 0.9rem;
            color: #0f172a;
            background: white;
            outline: none;
            transition: all 0.2s;
        }
        .input-control:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5,150,105,0.15);
        }
        .form-control-pro, select.input-control {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23059669' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 14px center !important;
            background-size: 14px 14px !important;
            padding-right: 38px !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            cursor: pointer;
        }
        .pills-container {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 8px;
        }
        .btn-pill {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            color: #334155;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-pill:hover, .btn-pill.active {
            background: #059669;
            color: white;
            border-color: #059669;
        }
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            border: none;
            padding: 15px 24px;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(5,150,105,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5,150,105,0.45);
        }
        .footer-text {
            text-align: center;
            margin-top: 24px;
            font-size: 0.8rem;
            color: #64748b;
        }
        .footer-text a {
            color: #059669;
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Header Logo -->
    <div class="header-logo-card">
        <img src="{{ asset('logo.png') }}" class="logo-img" alt="Logo SDIT AN NADZIR" onerror="this.src='/images/logo.png'">
        <h1 class="school-title">SDIT AN NADZIR</h1>
        <p class="school-subtitle">Sistem Penjamin Mutu Al-Qur'an (SIMUTQIN)</p>
        <span class="badge-year">Tahun Pelajaran {{ $activeYear->name ?? '-' }}</span>
    </div>

    <!-- Main Card Form -->
    <div class="form-main-card">
        <div class="card-hero">
            <h2>Formulir Pendaftaran Munaqosah</h2>
            <p>Pengajuan evaluasi kelulusan siswa tuntas Juz (Tahfidz) atau Jilid (Tilawati) oleh Guru Pembimbing / Orang Tua / Wali Siswa.</p>
        </div>

        <form action="{{ route('munaqosah.public-register.store') }}" method="POST" class="form-body" onsubmit="return validatePublicForm()">
            @csrf

            <!-- 1. Tipe Munaqosah -->
            <div class="form-section">
                <label class="section-label">1. Bidang Pembinaan Munaqosah <span style="color: red;">*</span></label>
                <div class="type-selector-grid">
                    <label id="card_tahfidz" class="type-card active-tahfidz">
                        <input type="radio" name="type" value="tahfidz" checked onchange="onTypeChanged('tahfidz')" style="width: 18px; height: 18px; accent-color: #059669;">
                        <div>
                            <strong style="color: #065f46; font-size: 0.94rem; display: block;">
                                <i class="fas fa-star-and-crescent" style="color: #10b981;"></i> Tahfidz
                            </strong>
                            <span style="font-size: 0.76rem; color: #047857;">Munaqosah Tuntas Juz</span>
                        </div>
                    </label>

                    <label id="card_tilawati" class="type-card">
                        <input type="radio" name="type" value="tilawati" onchange="onTypeChanged('tilawati')" style="width: 18px; height: 18px; accent-color: #d97706;">
                        <div>
                            <strong style="color: #92400e; font-size: 0.94rem; display: block;">
                                <i class="fas fa-book-reader" style="color: #f59e0b;"></i> Tilawati
                            </strong>
                            <span style="font-size: 0.76rem; color: #b45309;">Kenaikan Jilid 1 - 6</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 2. Pilih Siswa (Tahfidz) atau Pilih Kelompok (Tilawati) -->
            <div class="form-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label id="point2_title" class="section-label" style="margin-bottom: 0;">
                        2. Pilih Siswa Peserta Munaqosah <span style="color: red;">*</span>
                    </label>
                    <span id="point2_badge" style="font-size: 0.76rem; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 12px; font-weight: 700;">
                        Total {{ $students->count() }} siswa
                    </span>
                </div>
                <p id="point2_desc" style="font-size: 0.78rem; color: #64748b; margin-top: 0; margin-bottom: 10px;">
                    Ketik nama lengkap siswa di bawah, pilihan nama & kelas akan muncul otomatis:
                </p>

                <!-- A. Mode Tahfidz: Input Pencarian Langsung -->
                <div id="point2_tahfidz_container">
                    <div style="position: relative;">
                        <input type="text" id="student_search_live" autocomplete="off"
                               placeholder="Ketik nama lengkap siswa di sini (cth: Muhammad, Aisyah, dll)..." 
                               class="input-control" 
                               oninput="onSearchStudentLive(this.value)"
                               style="padding-left: 42px; font-size: 0.95rem; font-weight: 600;">
                        <i class="fas fa-search" style="position: absolute; left: 14px; top: 15px; color: #059669; font-size: 1.05rem;"></i>
                    </div>

                    <!-- Floating Container Hasil Pencarian -->
                    <div id="student_search_results" style="display: none; max-height: 230px; overflow-y: auto; background: white; border: 1.5px solid #10b981; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); margin-top: 6px; z-index: 50;">
                        <!-- Diisi dinamis oleh JavaScript -->
                    </div>

                    <!-- Hidden Input untuk dikirim ke backend -->
                    <input type="hidden" name="student_id" id="selected_student_id">

                    <!-- Siswa Terpilih (Card Konfirmasi) -->
                    <div id="student_selected_card" style="display: none; background: #ecfdf5; border: 1.5px solid #10b981; border-radius: 12px; padding: 12px 16px; margin-top: 10px; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 42px; height: 42px; border-radius: 10px; background: #d1fae5; color: #047857; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.74rem; color: #059669; font-weight: 800; text-transform: uppercase;">Siswa Terpilih:</div>
                                <h4 id="selected_student_name_label" style="margin: 0; font-size: 1rem; font-weight: 800; color: #065f46;"></h4>
                                <p id="selected_student_meta_label" style="margin: 2px 0 0 0; font-size: 0.78rem; color: #047857;"></p>
                            </div>
                        </div>
                        <button type="button" onclick="clearStudentSelection()" style="background: white; border: 1px solid #a7f3d0; color: #065f46; font-size: 0.8rem; font-weight: 700; padding: 6px 12px; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-sync-alt"></i> Ganti
                        </button>
                    </div>
                </div>

                <!-- B. Mode Tilawati: Pilih Kelompok Tilawati yang Diatur Kurikulum -->
                <div id="point2_tilawati_container" style="display: none;">
                    <div style="position: relative;">
                        <select name="group_id" id="tilawati_group_select" class="input-control form-control-pro" style="height: 46px; font-weight: 700;" onchange="onSelectTilawatiGroup(this.value)">
                            <option value="">-- Silakan Pilih Kelompok Tilawati --</option>
                            @foreach($tilawatiGroups as $tGrp)
                                @php
                                    $studentCount = $tGrp->students->count();
                                    $mentor = $tGrp->mentor_name ?? ($tGrp->mentorUser->name ?? 'Pembimbing');
                                    $targetLvl = $tGrp->target_level ?? 'Jilid 1';
                                @endphp
                                <option value="{{ $tGrp->id }}">
                                    📖 {{ $tGrp->name }} • Pembimbing: {{ $mentor }} ({{ $studentCount }} Santri) • Tingkat: {{ $targetLvl }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kartu Detail & Preview Anggota Santri -->
                    <div id="tilawati_group_preview_card" style="display: none; background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 14px; padding: 16px; margin-top: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div>
                                    <h4 id="preview_group_name" style="margin: 0; font-size: 0.98rem; font-weight: 800; color: #92400e;">
                                        Kelompok Tilawati
                                    </h4>
                                    <span id="preview_group_meta" style="font-size: 0.78rem; color: #b45309; font-weight: 600;">
                                        Pembimbing: -
                                    </span>
                                </div>
                            </div>
                            <span id="preview_group_count_badge" style="background: #f59e0b; color: white; font-weight: 800; font-size: 0.74rem; padding: 3px 8px; border-radius: 16px;">
                                0 Santri
                            </span>
                        </div>

                        <!-- Daftar Nama Santri dalam Kelompok -->
                        <div style="margin-top: 10px; border-top: 1px dashed #fcd34d; padding-top: 8px;">
                            <label style="display: block; font-size: 0.74rem; font-weight: 800; color: #78350f; text-transform: uppercase; margin-bottom: 6px;">
                                <i class="fas fa-users" style="color: #f59e0b; margin-right: 4px;"></i> Anggota Santri yang Akan Dimunaqosah:
                            </label>
                            <div id="preview_group_students_list" style="display: flex; flex-wrap: wrap; gap: 5px; max-height: 160px; overflow-y: auto;">
                                <!-- Chips nama siswa -->
                            </div>
                        </div>

                        <div style="margin-top: 10px; font-size: 0.76rem; color: #92400e; display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-check-circle" style="color: #10b981;"></i>
                            <span>Sekali klik pendaftaran, <strong>seluruh santri di kelompok ini</strong> akan langsung diajukan bersamaan.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Jenjang Kelulusan -->
            <div class="form-section" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px;">
                <label class="section-label" style="margin-bottom: 4px;">
                    <i class="fas fa-layer-group" style="color: #059669; margin-right: 4px;"></i>
                    3. Jenjang yang Dituntaskan & Target Baru <span style="color: red;">*</span>
                </label>
                <p style="font-size: 0.78rem; color: #64748b; margin-bottom: 12px;">Klik tombol pilihan di bawah agar target terisi otomatis:</p>

                {{-- Pills Tahfidz --}}
                <div id="pills_tahfidz">
                    <div class="pills-container">
                        @foreach([30, 29, 28, 27, 26, 1, 2, 3] as $pz)
                            <button type="button" onclick="chooseJuz({{ $pz }})" class="btn-pill">Juz {{ $pz }}</button>
                        @endforeach
                    </div>
                </div>

                {{-- Pills Tilawati --}}
                <div id="pills_tilawati" style="display: none;">
                    <div class="pills-container">
                        @foreach(['Jilid 1', 'Jilid 2', 'Jilid 3', 'Jilid 4', 'Jilid 5', 'Jilid 6', 'Al-Qur\'an / Ghorib'] as $pj)
                            <button type="button" onclick="chooseJilid('{{ $pj }}')" class="btn-pill">{{ $pj }}</button>
                        @endforeach
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 14px;">
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">
                            Jenjang Tuntas:
                        </label>
                        <input type="text" name="current_level" id="current_level" required value="Juz 30" class="input-control" style="font-weight: 700; color: #065f46;">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">
                            Target Berikutnya:
                        </label>
                        <input type="text" name="target_level" id="target_level" required value="Juz 29" class="input-control" style="font-weight: 700; color: #0f766e;">
                    </div>
                </div>
            </div>

            <!-- 4. Pilih Jadwal & Ketersediaan Penguji (Munaqisy) -->
            <div class="form-section" style="background: #f0fdf4; border: 1.5px solid #a7f3d0; border-radius: 16px; padding: 18px;">
                <label class="section-label" style="color: #065f46; margin-bottom: 4px;">
                    <i class="fas fa-calendar-check" style="color: #059669; margin-right: 4px;"></i>
                    4. Pilih Jadwal & Penguji / Munaqisy (Opsional)
                </label>
                <p style="font-size: 0.78rem; color: #047857; margin-bottom: 12px;">
                    Pilih sesi ketersediaan penguji yang sudah disiapkan Admin & Kurikulum. Jika slot sudah terisi, akan muncul tanda centang/penuh (✓).
                </p>

                <select name="schedule_id" id="schedule_id" class="input-control form-control-pro" style="font-weight: 700; color: #065f46; border-color: #10b981; background-color: white;">
                    <option value="">-- Tetapkan / Jadwalkan Nanti oleh Panitia Munaqosah --</option>
                    @foreach($schedules as $sch)
                        @if(!$sch->is_full)
                            <option value="{{ $sch->id }}">
                                ✓ {{ $sch->examiner?->user?->name ?? 'Penguji' }} — Hari {{ $sch->day_name }} ({{ $sch->time_start }} - {{ $sch->time_end }} WIB) [{{ $sch->room_location ?? 'Ruang Munaqosah' }}] • Tersedia
                            </option>
                        @else
                            <option value="{{ $sch->id }}" disabled style="color: #94a3b8; background: #f8fafc;">
                                🔒 {{ $sch->examiner?->user?->name ?? 'Penguji' }} — Hari {{ $sch->day_name }} ({{ $sch->time_start }} - {{ $sch->time_end }} WIB) • (Sudah Terpenuhi ✓)
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- 5. Data Pendaftar / Pengusul -->
            <div class="form-section">
                <label class="section-label">5. Identitas Pengusul / Pendaftar <span style="color: red;">*</span></label>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Status Pendaftar:</label>
                        <select name="proposer_role" class="input-control form-control-pro" style="font-weight: 700;">
                            <option value="Guru Pembimbing / Asatidz">Guru Pembimbing / Asatidz</option>
                            <option value="Orang Tua / Wali Siswa">Orang Tua / Wali Siswa</option>
                            <option value="Koordinator Qur'an">Koordinator Qur'an</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Nama Lengkap Pengusul <span style="color: red;">*</span></label>
                        <input type="text" name="proposer_name" required placeholder="Nama Ustadz/Ustadzah atau Wali..." class="input-control">
                    </div>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">
                        Nomor WhatsApp Pengusul <span style="font-weight: normal; color: #64748b;">(Opsional)</span>
                    </label>
                    <input type="tel" name="proposer_phone" placeholder="Contoh: 081234567890 (Untuk konfirmasi panitia)" class="input-control">
                </div>

                <div>
                    <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Catatan Kesiapan Siswa (Opsional):</label>
                    <textarea name="proposer_notes" rows="2" placeholder="Contoh: Ananda telah menyelesaikan hafalan Juz 30 dan tasmi' 1 majelis dengan lancar..." class="input-control" style="font-size: 0.86rem; line-height: 1.4;"></textarea>
                </div>
            </div>

            <!-- Persetujuan Kesiapan -->
            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 14px; margin-bottom: 24px; display: flex; gap: 10px; align-items: flex-start;">
                <input type="checkbox" id="check_agree" required style="width: 20px; height: 20px; accent-color: #059669; margin-top: 2px;">
                <label for="check_agree" style="font-size: 0.82rem; color: #065f46; line-height: 1.45; cursor: pointer;">
                    <strong>Konfirmasi Kesiapan:</strong> Siswa yang diajukan telah menyelesaikan materi target Juz/Jilid dengan bimbingan dan siap mengikuti evaluasi Munaqosah resmi SDIT AN NADZIR.
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Kirim Pendaftaran Munaqosah
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer-text">
        <p>&copy; {{ date('Y') }} SDIT AN NADZIR. All rights reserved.</p>
        <p style="margin-top: 4px;">Ingin cek rekapan capaian? <a href="{{ route('student-status.index') }}">Cek Status & Rapor Siswa</a></p>
    </div>

</div>

<script>
const allStudentsData = @json($students);
const allTilawatiGroupsData = @json($tilawatiGroups);

function onTypeChanged(type) {
    const cardTahfidz = document.getElementById('card_tahfidz');
    const cardTilawati = document.getElementById('card_tilawati');
    const pillsTahfidz = document.getElementById('pills_tahfidz');
    const pillsTilawati = document.getElementById('pills_tilawati');
    const cur = document.getElementById('current_level');
    const tgt = document.getElementById('target_level');

    const point2Title = document.getElementById('point2_title');
    const point2Badge = document.getElementById('point2_badge');
    const point2Desc = document.getElementById('point2_desc');
    const p2Tahfidz = document.getElementById('point2_tahfidz_container');
    const p2Tilawati = document.getElementById('point2_tilawati_container');
    const selectTilawatiGroup = document.getElementById('tilawati_group_select');

    if (type === 'tahfidz') {
        cardTahfidz.className = 'type-card active-tahfidz';
        cardTilawati.className = 'type-card';
        pillsTahfidz.style.display = 'block';
        pillsTilawati.style.display = 'none';

        // Poin 2: Kembali ke Mode Pencarian Siswa Tahfidz
        if (point2Title) point2Title.innerHTML = '2. Pilih Siswa Peserta Munaqosah <span style="color: red;">*</span>';
        if (point2Badge) point2Badge.textContent = 'Total ' + allStudentsData.length + ' siswa';
        if (point2Desc) point2Desc.textContent = 'Ketik nama lengkap siswa di bawah, pilihan nama & kelas akan muncul otomatis:';

        if (p2Tahfidz) p2Tahfidz.style.display = 'block';
        if (p2Tilawati) p2Tilawati.style.display = 'none';

        if (!cur.value || cur.value.startsWith('Jilid')) {
            cur.value = 'Juz 30';
            tgt.value = 'Juz 29';
        }
    } else {
        cardTilawati.className = 'type-card active-tilawati';
        cardTahfidz.className = 'type-card';
        pillsTahfidz.style.display = 'none';
        pillsTilawati.style.display = 'block';

        // Poin 2: Pindah ke Mode Pilihan Kelompok Tilawati
        if (point2Title) point2Title.innerHTML = '2. Pilih Kelompok Tilawati Peserta Munaqosah <span style="color: red;">*</span>';
        if (point2Badge) point2Badge.textContent = allTilawatiGroupsData.length + ' Kelompok Tilawati';
        if (point2Desc) point2Desc.textContent = 'Silakan pilih kelompok. Pada kolom tersebut berisi seluruh kelompok Tilawati yang sudah diatur oleh Admin atau Kurikulum:';

        if (p2Tahfidz) p2Tahfidz.style.display = 'none';
        if (p2Tilawati) p2Tilawati.style.display = 'block';

        if (!cur.value || cur.value.startsWith('Juz')) {
            cur.value = 'Jilid 1';
            tgt.value = 'Jilid 2';
        }

        if (selectTilawatiGroup && selectTilawatiGroup.value) {
            onSelectTilawatiGroup(selectTilawatiGroup.value);
        }
    }
}

function onSelectTilawatiGroup(groupId) {
    const card = document.getElementById('tilawati_group_preview_card');
    if (!groupId) {
        if (card) card.style.display = 'none';
        return;
    }

    const grp = allTilawatiGroupsData.find(g => String(g.id) === String(groupId));
    if (!grp) {
        if (card) card.style.display = 'none';
        return;
    }

    const nameEl = document.getElementById('preview_group_name');
    const metaEl = document.getElementById('preview_group_meta');
    const badgeEl = document.getElementById('preview_group_count_badge');
    const listEl = document.getElementById('preview_group_students_list');

    const mentorName = grp.mentor_name || (grp.mentor_user ? grp.mentor_user.name : '-');
    const students = grp.students || [];

    if (nameEl) nameEl.textContent = grp.name;
    if (metaEl) metaEl.textContent = 'Pembimbing: ' + mentorName + ' • Target: ' + (grp.target_level || 'Jilid 1');
    if (badgeEl) badgeEl.textContent = students.length + ' Santri';

    if (listEl) {
        if (students.length === 0) {
            listEl.innerHTML = '<span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Belum ada anggota santri di kelompok ini.</span>';
        } else {
            let html = '';
            students.forEach((st, idx) => {
                html += '<span style="background: white; border: 1px solid #fde68a; color: #92400e; font-size: 0.76rem; font-weight: 700; padding: 4px 10px; border-radius: 8px; display: inline-flex; align-items: center; gap: 4px;">' +
                        '<strong>' + (idx + 1) + '.</strong> ' + escapeHtml(st.name) + ' (' + escapeHtml(st.class) + ')' +
                        '</span>';
            });
            listEl.innerHTML = html;
        }
    }

    if (card) card.style.display = 'block';

    // Otomatis isi Jenjang di Poin 3
    const targetLvl = grp.target_level || 'Jilid 1';
    const cur = document.getElementById('current_level');
    const tgt = document.getElementById('target_level');
    if (cur && tgt) {
        cur.value = targetLvl;
        const jilidMap = {
            'Jilid 1': 'Jilid 2',
            'Jilid 2': 'Jilid 3',
            'Jilid 3': 'Jilid 4',
            'Jilid 4': 'Jilid 5',
            'Jilid 5': 'Jilid 6',
            'Jilid 6': 'Al-Qur\'an / Ghorib',
            'Al-Qur\'an / Ghorib': 'Tashih Akhir'
        };
        tgt.value = jilidMap[targetLvl] || 'Jenjang Lanjutan';
    }
}

function chooseJuz(juz) {
    const cur = document.getElementById('current_level');
    const tgt = document.getElementById('target_level');
    cur.value = 'Juz ' + juz;

    let nextJuz = juz - 1;
    if (nextJuz < 1) nextJuz = 'Selesai 30 Juz';
    else nextJuz = 'Juz ' + nextJuz;
    tgt.value = nextJuz;
}

function chooseJilid(jilid) {
    const cur = document.getElementById('current_level');
    const tgt = document.getElementById('target_level');
    cur.value = jilid;

    const jilidMap = {
        'Jilid 1': 'Jilid 2',
        'Jilid 2': 'Jilid 3',
        'Jilid 3': 'Jilid 4',
        'Jilid 4': 'Jilid 5',
        'Jilid 5': 'Jilid 6',
        'Jilid 6': 'Al-Qur\'an / Ghorib',
        'Al-Qur\'an / Ghorib': 'Tashih Akhir'
    };
    tgt.value = jilidMap[jilid] || 'Jenjang Lanjutan';
}

function onSearchStudentLive(query) {
    const q = query.trim().toLowerCase();
    const box = document.getElementById('student_search_results');

    if (q.length < 2) {
        box.style.display = 'none';
        box.innerHTML = '';
        return;
    }

    const matches = allStudentsData.filter(st => {
        const nameMatch = st.name && st.name.toLowerCase().includes(q);
        const nisMatch = st.nis && String(st.nis).toLowerCase().includes(q);
        return nameMatch || nisMatch;
    }).slice(0, 15);

    if (matches.length === 0) {
        box.innerHTML = '<div style="padding: 14px 16px; font-size: 0.85rem; color: #94a3b8; text-align: center;">Tidak ditemukan siswa dengan nama "<strong>' + escapeHtml(query) + '</strong>"</div>';
        box.style.display = 'block';
        return;
    }

    let html = '';
    matches.forEach(st => {
        const studentName = escapeHtml(st.name);
        const studentClass = escapeHtml(st.class);
        const studentNis = escapeHtml(st.nis || '');
        
        html += '<div onclick="selectStudentLive(' + st.id + ', \'' + studentName + '\', \'' + studentClass + '\', \'' + studentNis + '\')" ' +
                'style="padding: 10px 14px; border-bottom: 1px solid #f1f5f9; cursor: pointer; display: flex; align-items: center; justify-content: space-between; transition: background 0.15s;" ' +
                'onmouseover="this.style.background=\'#ecfdf5\'" onmouseout="this.style.background=\'white\'">' +
                '<div>' +
                    '<strong style="color: #0f172a; font-size: 0.92rem; display: block;">' + studentName + '</strong>' +
                    '<span style="font-size: 0.76rem; color: #64748b;">Kelas ' + studentClass + (studentNis ? ' • NIS: ' + studentNis : '') + '</span>' +
                '</div>' +
                '<span style="font-size: 0.75rem; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 6px; font-weight: 700;">Pilih</span>' +
                '</div>';
    });

    box.innerHTML = html;
    box.style.display = 'block';
}

function selectStudentLive(id, name, cls, nis) {
    document.getElementById('selected_student_id').value = id;
    document.getElementById('selected_student_name_label').textContent = name;
    document.getElementById('selected_student_meta_label').textContent = 'Kelas ' + cls + (nis ? ' • NIS: ' + nis : '');
    document.getElementById('student_selected_card').style.display = 'flex';

    document.getElementById('student_search_live').value = name;
    document.getElementById('student_search_results').style.display = 'none';
}

function clearStudentSelection() {
    document.getElementById('selected_student_id').value = '';
    document.getElementById('student_selected_card').style.display = 'none';
    const input = document.getElementById('student_search_live');
    input.value = '';
    input.focus();
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/'/g, "\\'").replace(/"/g, '&quot;');
}

function validatePublicForm() {
    const curType = document.querySelector('input[name="type"]:checked')?.value || 'tahfidz';
    if (curType === 'tahfidz') {
        const studentId = document.getElementById('selected_student_id').value;
        if (!studentId) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Siswa Terlebih Dahulu',
                text: 'Silakan ketik nama lengkap siswa pada kolom pencarian dan klik nama siswa yang sesuai.',
                confirmButtonColor: '#059669'
            });
            document.getElementById('student_search_live').focus();
            return false;
        }
    } else {
        const groupId = document.getElementById('tilawati_group_select').value;
        if (!groupId) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Kelompok Tilawati',
                text: 'Silakan pilih kelompok Tilawati yang akan diajukan untuk munaqosah.',
                confirmButtonColor: '#d97706'
            });
            document.getElementById('tilawati_group_select').focus();
            return false;
        }
    }
    return true;
}

// Tutup floating search box jika klik di luar
document.addEventListener('click', function(e) {
    const box = document.getElementById('student_search_results');
    const input = document.getElementById('student_search_live');
    if (box && input && !box.contains(e.target) && e.target !== input) {
        box.style.display = 'none';
    }
});
</script>

</body>
</html>
