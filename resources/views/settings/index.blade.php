@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    
    @if(session('success'))
        <div style="background: #e0fbf0; color: #2ecc71; padding: 15px; border-radius: 12px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
            <div>
                <h3 style="margin: 0; font-size: 1.5rem;">Konfigurasi Aplikasi</h3>
                <p style="margin: 5px 0 0; color: #888; font-size: 0.9rem;">Kelola semua pengaturan formulir dan akun dalam satu halaman.</p>
            </div>
            <button type="submit" class="btn-action-header btn-blue">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>

        <!-- SECTION 1: FORMULIR -->
        <div style="background: #fafafa; padding: 20px; border-radius: 12px; margin-bottom: 30px;">
            <h4 style="margin-bottom: 20px; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px;">
                <i class="fas fa-file-alt" style="margin-right: 8px; color: var(--accent-color);"></i> Pengaturan Periode & Formulir
            </h4>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Judul Formulir Pilihan Eskul</label>
                <input type="text" name="form_title" class="form-control" value="{{ $settings['form_title'] ?? 'Pilihan Ekstrakurikuler' }}" required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Deskripsi / Instruksi Formulir</label>
                <textarea name="form_description" class="form-control" rows="3" required>{{ $settings['form_description'] ?? 'Silakan lengkapi data ananda untuk memilih kegiatan ekstrakurikuler.' }}</textarea>
            </div>

            <!-- TIGA KOLOM UNTUK KEMUDAHAN UNIT WAKTU AKADEMIS & STATUS FORM -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label style="margin: 0; font-weight: 600;">Tahun Ajaran Aktif</label>
                        <a href="/academic-years" style="font-size: 0.8rem; color: #2980b9; text-decoration: none; font-weight: 600;">
                            <i class="fas fa-edit"></i> Kelola
                        </a>
                    </div>
                    <select name="active_academic_year_id" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Semester Data</label>
                    <select name="active_semester" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="1" {{ ($settings['active_semester'] ?? '1') == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                        <option value="2" {{ ($settings['active_semester'] ?? '') == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Status Formulir</label>
                    <select name="form_status" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="open" {{ ($settings['form_status'] ?? 'open') == 'open' ? 'selected' : '' }}>Dibuka (Aktif)</option>
                        <option value="closed" {{ ($settings['form_status'] ?? '') == 'closed' ? 'selected' : '' }}>Ditutup (Non-Aktif)</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Kuota Maksimal Per Eskul</label>
                <input type="number" name="eskul_quota" class="form-control" value="{{ $settings['eskul_quota'] ?? 25 }}" required min="1">
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label style="margin: 0; font-weight: 600;">Eskul yang Ditampilkan di Formulir</label>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" onclick="selectAllEskuls(true)" style="background: #e2e8f0; border: none; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; cursor: pointer; font-weight: 600; color: #475569;">Pilih Semua</button>
                        <button type="button" onclick="selectAllEskuls(false)" style="background: #e2e8f0; border: none; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; cursor: pointer; font-weight: 600; color: #475569;">Hapus Semua</button>
                    </div>
                </div>
                <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px; max-height: 200px; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px;">
                    @php
                        $allowed = json_decode($settings['allowed_eskuls'] ?? '[]', true);
                    @endphp
                    @foreach($eskuls as $eskul)
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="eskul-checkbox" id="eskul_{{ $eskul->id }}" name="allowed_eskuls[]" value="{{ $eskul->id }}" 
                            {{ in_array($eskul->id, $allowed) ? 'checked' : '' }}>
                        <label for="eskul_{{ $eskul->id }}" style="cursor: pointer; font-size: 0.9rem;">{{ $eskul->name }}</label>
                    </div>
                    @endforeach
                </div>
                <div style="margin-top: 8px; display: flex; justify-content: flex-end;">
                     <a href="{{ route('eskuls.index') }}" style="font-size: 0.85rem; color: #2980b9; text-decoration: none; font-weight: 500;">
                         <i class="fas fa-plus-circle"></i> Tambah Data Eskul Baru
                     </a>
                </div>
            </div>
        </div>

        <!-- SECTION 1.5: WHATSAPP GATEWAY -->
        <div style="background: #fafafa; padding: 20px; border-radius: 12px; margin-bottom: 30px;">
            <h4 style="margin-bottom: 20px; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px;">
                <i class="fab fa-whatsapp" style="margin-right: 8px; color: #25d366; font-size: 1.2rem;"></i> Integrasi WhatsApp Gateway (Notifikasi Pendaftaran)
            </h4>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Status WhatsApp Gateway</label>
                    <select name="wa_gateway_enabled" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="no" {{ ($settings['wa_gateway_enabled'] ?? 'no') == 'no' ? 'selected' : '' }}>Non-Aktif (Mati)</option>
                        <option value="yes" {{ ($settings['wa_gateway_enabled'] ?? '') == 'yes' ? 'selected' : '' }}>Aktif (Kirim Notifikasi)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Provider API</label>
                    <select name="wa_gateway_provider" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="fonnte" {{ ($settings['wa_gateway_provider'] ?? 'fonnte') == 'fonnte' ? 'selected' : '' }}>Fonnte (api.fonnte.com)</option>
                        <option value="wablas" {{ ($settings['wa_gateway_provider'] ?? '') == 'wablas' ? 'selected' : '' }}>Wablas (api.wablas.com)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Token API / API Key</label>
                    <input type="text" name="wa_gateway_token" class="form-control" value="{{ $settings['wa_gateway_token'] ?? '' }}" placeholder="Masukkan Token API Fonnte / Wablas">
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Nomor WhatsApp Pengirim (Optional)</label>
                    <input type="text" name="wa_gateway_sender" class="form-control" value="{{ $settings['wa_gateway_sender'] ?? '' }}" placeholder="Contoh: 08123456789 (Khusus Wablas jika diperlukan)">
                </div>
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Template Pesan Sukses</label>
                <textarea name="wa_message_template" class="form-control" rows="3" placeholder="Gunakan placeholder: {nama_siswa}, {kelas}, {nama_eskul}, {tahun_ajaran}">{{ $settings['wa_message_template'] ?? "Halo Bapak/Ibu, pendaftaran eskul untuk Ananda {nama_siswa} (Kelas {kelas}) ke eskul {nama_eskul} pada tahun ajaran {tahun_ajaran} berhasil disimpan. Terima kasih." }}</textarea>
                <small style="color: #666; margin-top: 5px; display: block;">
                    Placeholder yang didukung: <strong>{nama_siswa}</strong>, <strong>{kelas}</strong>, <strong>{nama_eskul}</strong>, <strong>{tahun_ajaran}</strong>.
                </small>
            </div>
        </div>

        <!-- SECTION 2: IDENTITAS & PROFIL -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div style="background: #fafafa; padding: 20px; border-radius: 12px;">
                <h4 style="margin-bottom: 20px; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px;">
                    <i class="fas fa-id-card" style="margin-right: 8px; color: var(--accent-color);"></i> Identitas Aplikasi
                </h4>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Footer Text (Credits)</label>
                    <input type="text" name="app_credits" class="form-control" value="{{ $settings['app_credits'] ?? '' }}" placeholder="Contoh: SDIT An Nadzir - 2024">
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Nama Kepala Sekolah</label>
                    <input type="text" name="headmaster_name" class="form-control" value="{{ $settings['headmaster_name'] ?? 'Nur\'asiah, S.Pd.I' }}">
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Tema Warna (Branding)</label>
                    <div style="display: flex; gap: 15px;">
                        <div style="flex: 1;">
                            <small>Warna Utama (Sidebar/Header)</small>
                            <input type="color" name="app_primary_color" class="form-control" style="height: 45px; padding: 5px;" value="{{ $settings['app_primary_color'] ?? '#1c1130' }}">
                        </div>
                        <div style="flex: 1;">
                            <small>Warna Aksen (Tombol/Icon)</small>
                            <input type="color" name="app_accent_color" class="form-control" style="height: 45px; padding: 5px;" value="{{ $settings['app_accent_color'] ?? '#7367f0' }}">
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: #fafafa; padding: 20px; border-radius: 12px;">
                <h4 style="margin-bottom: 20px; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px;">
                    <i class="fas fa-user-shield" style="margin-right: 8px; color: var(--accent-color);"></i> Profil Admin
                </h4>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Nama Lengkap</label>
                    <input type="text" name="admin_name" class="form-control" value="{{ Auth::user()->name }}" required>
                </div>
                 <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Ganti Password</label>
                    <input type="password" name="change_password" class="form-control" placeholder="Kosongkan jika tidak ubah">
                </div>
            </div>
        </div>

        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
             <button type="submit" class="btn-action-header btn-blue" style="width: 100%; justify-content: center; height: 50px; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(41, 128, 185, 0.2);">
                <i class="fas fa-save" style="margin-right: 10px;"></i> SIMPAN SEMUA PENGATURAN
            </button>
        </div>
    </form>

    <!-- SECTION 3: ZONE BAHAYA (DANGER ZONE) -->
    <div style="background: #fff5f5; border: 1px solid #ffccd2; padding: 20px; border-radius: 12px; margin-top: 40px;">
        <h4 style="margin-top: 0; margin-bottom: 10px; color: #c0392b; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-exclamation-triangle"></i> Zona Bahaya (Danger Zone)
        </h4>
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 15px; margin-top: 0;">Tindakan di bawah ini bersifat permanen dan tidak dapat dibatalkan di database.</p>
        <div style="display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #ffccd2;">
            <div>
                <h5 style="margin: 0; font-size: 1rem; color: #2c3e50;">Bersihkan Riwayat Log Aktivitas</h5>
                <p style="margin: 5px 0 0; color: #7f8c8d; font-size: 0.85rem;">Menghapus seluruh catatan log aktivitas audit sistem yang tercatat di database.</p>
            </div>
            <button type="button" onclick="confirmClearLogs()" style="background: #e74c3c; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(231, 76, 60, 0.2);">
                <i class="fas fa-trash-alt"></i> Bersihkan Log
            </button>
        </div>
    </div>

    <!-- Hidden Form for Clear Logs -->
    <form id="clearLogsForm" action="{{ route('settings.clear-logs') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>

<script>
// Fungsi pilih / hapus semua eskul
function selectAllEskuls(status) {
    document.querySelectorAll('.eskul-checkbox').forEach(cb => {
        cb.checked = status;
    });
}

// Fungsi konfirmasi hapus log aktivitas
function confirmClearLogs() {
    if (confirm('Apakah Anda yakin ingin menghapus SELURUH riwayat log aktivitas? Tindakan ini tidak dapat dibatalkan.')) {
        document.getElementById('clearLogsForm').submit();
    }
}
</script>
@endsection
