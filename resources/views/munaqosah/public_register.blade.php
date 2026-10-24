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
            <p>Pengajuan evaluasi kelulusan santri tuntas Juz (Tahfidz) atau Jilid (Tilawati) oleh Guru Pembimbing / Wali Santri.</p>
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

            <!-- 2. Pilih Santri -->
            <div class="form-section">
                <label class="section-label">2. Pilih Santri Peserta Munaqosah <span style="color: red;">*</span></label>
                
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 10px; margin-bottom: 8px;">
                    <div>
                        <select id="class_filter" onchange="filterStudentsByClass()" class="input-control" style="padding: 11px 10px; font-weight: 600;">
                            <option value="ALL">Semua Kelas</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls }}">Kelas {{ $cls }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="position: relative;">
                        <input type="text" id="student_search" placeholder="Cari nama santri..." onkeyup="filterStudentOptions()" class="input-control" style="padding-left: 36px;">
                        <i class="fas fa-search" style="position: absolute; left: 12px; top: 15px; color: #94a3b8; font-size: 0.85rem;"></i>
                    </div>
                </div>

                <select name="student_id" id="student_select" required size="5" onchange="onStudentPicked(this)" class="input-control" style="padding: 6px; height: 130px;">
                    <option value="" disabled selected>-- Pilih salah satu santri dari daftar di bawah --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" data-class="{{ $st->class }}" data-name="{{ $st->name }}">
                            {{ $st->name }} — Kelas {{ $st->class }} @if($st->nis) (NIS: {{ $st->nis }}) @endif
                        </option>
                    @endforeach
                </select>

                <div id="student_badge" style="display: none; align-items: center; justify-content: space-between; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px; padding: 10px 14px; margin-top: 8px;">
                    <span style="color: #065f46; font-size: 0.86rem;">
                        <i class="fas fa-check-circle" style="color: #10b981; margin-right: 6px;"></i>
                        Santri terpilih: <strong id="student_badge_name"></strong>
                    </span>
                    <span style="font-size: 0.75rem; background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 4px; font-weight: 700;">OK</span>
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

            <!-- 4. Data Pendaftar / Pengusul -->
            <div class="form-section">
                <label class="section-label">4. Identitas Pengusul / Pendaftar <span style="color: red;">*</span></label>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Status Pendaftar:</label>
                        <select name="proposer_role" class="input-control" style="font-weight: 600;">
                            <option value="Guru Pembimbing / Asatidz">Guru Pembimbing / Asatidz</option>
                            <option value="Orang Tua / Wali Santri">Orang Tua / Wali Santri</option>
                            <option value="Koordinator Qur'an">Koordinator Qur'an</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Nama Lengkap Pengusul <span style="color: red;">*</span></label>
                        <input type="text" name="proposer_name" required placeholder="Nama Ustadz/Ustadzah atau Wali..." class="input-control">
                    </div>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Nomor WhatsApp Aktif Pengusul <span style="color: red;">*</span></label>
                    <input type="tel" name="proposer_phone" required placeholder="Contoh: 081234567890 (Untuk konfirmasi jadwal ujian)" class="input-control">
                </div>

                <div>
                    <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Catatan Kesiapan Santri (Opsional):</label>
                    <textarea name="proposer_notes" rows="2" placeholder="Contoh: Ananda telah menyelesaikan hafalan Juz 30 dan tasmi' 1 majelis dengan lancar..." class="input-control" style="font-size: 0.86rem; line-height: 1.4;"></textarea>
                </div>
            </div>

            <!-- Persetujuan Kesiapan -->
            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 14px; margin-bottom: 24px; display: flex; gap: 10px; align-items: flex-start;">
                <input type="checkbox" id="check_agree" required style="width: 20px; height: 20px; accent-color: #059669; margin-top: 2px;">
                <label for="check_agree" style="font-size: 0.82rem; color: #065f46; line-height: 1.45; cursor: pointer;">
                    <strong>Konfirmasi Kesiapan:</strong> Santri yang diajukan telah menyelesaikan materi target Juz/Jilid dengan bimbingan dan siap mengikuti evaluasi Munaqosah resmi SDIT AN NADZIR.
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
        <p style="margin-top: 4px;">Ingin cek rekapan capaian? <a href="{{ route('student-status.index') }}">Cek Status & Rapor Santri</a></p>
    </div>

</div>

<script>
function onTypeChanged(type) {
    const cardTahfidz = document.getElementById('card_tahfidz');
    const cardTilawati = document.getElementById('card_tilawati');
    const pillsTahfidz = document.getElementById('pills_tahfidz');
    const pillsTilawati = document.getElementById('pills_tilawati');
    const cur = document.getElementById('current_level');
    const tgt = document.getElementById('target_level');

    if (type === 'tahfidz') {
        cardTahfidz.className = 'type-card active-tahfidz';
        cardTilawati.className = 'type-card';
        pillsTahfidz.style.display = 'block';
        pillsTilawati.style.display = 'none';
        cur.value = 'Juz 30';
        tgt.value = 'Juz 29';
    } else {
        cardTilawati.className = 'type-card active-tilawati';
        cardTahfidz.className = 'type-card';
        pillsTahfidz.style.display = 'none';
        pillsTilawati.style.display = 'block';
        cur.value = 'Jilid 1';
        tgt.value = 'Jilid 2';
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

function filterStudentsByClass() {
    const selectedClass = document.getElementById('class_filter').value;
    const select = document.getElementById('student_select');
    const query = document.getElementById('student_search').value.toLowerCase();

    for (let opt of select.options) {
        if (!opt.value) continue;
        const optClass = opt.getAttribute('data-class');
        const text = opt.text.toLowerCase();
        const matchClass = (selectedClass === 'ALL' || optClass === selectedClass);
        const matchQuery = text.includes(query);

        opt.style.display = (matchClass && matchQuery) ? '' : 'none';
    }
}

function filterStudentOptions() {
    filterStudentsByClass();
}

function onStudentPicked(select) {
    const opt = select.options[select.selectedIndex];
    const badge = document.getElementById('student_badge');
    const badgeName = document.getElementById('student_badge_name');

    if (opt && opt.value) {
        badge.style.display = 'flex';
        badgeName.textContent = opt.getAttribute('data-name') + ' (Kelas ' + opt.getAttribute('data-class') + ')';
    } else {
        badge.style.display = 'none';
    }
}

function validatePublicForm() {
    const studentSelect = document.getElementById('student_select');
    if (!studentSelect.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilih Santri Terlebih Dahulu',
            text: 'Silakan pilih santri yang akan diajukan dari kotak daftar santri.',
            confirmButtonColor: '#059669'
        });
        studentSelect.focus();
        return false;
    }
    return true;
}
</script>

</body>
</html>
