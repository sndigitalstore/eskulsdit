@extends('layouts.app')

@section('title', 'Panduan Penggunaan')
@section('page-title', 'Pusat Bantuan & Panduan Admin')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    
    <!-- Hero Section -->
    <div class="card" style="background: var(--accent-gradient); color: white; padding: 40px; border-radius: 20px; margin-bottom: 30px; text-align: center;">
        <i class="fas fa-book-open" style="font-size: 4rem; margin-bottom: 20px; opacity: 0.9;"></i>
        <h2 style="font-size: 2.2rem; margin-bottom: 10px;">Selamat Datang di Sistem Eskul</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 700px; margin: 0 auto;">Panduan singkat ini akan membantu Anda menguasai seluruh fitur sistem manajemen ekstrakurikuler SDIT AN NADZIR dengan cepat dan efektif.</p>
    </div>

    <!-- Guide Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
        
        <!-- Step 1 -->
        <div class="card" style="border-top: 5px solid #3498db;">
            <div style="display: flex; align-items: flex-start; gap: 15px;">
                <div style="background: #e1f5fe; color: #3498db; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0;">1</div>
                <div>
                    <h3 style="margin: 0 0 10px 0;">Manajemen Siswa (Mega Import)</h3>
                    <p style="font-size: 0.9rem; color: #666; line-height: 1.6;">
                        Gunakan tombol <b>"Ganti Data / Mega Import"</b> di menu Data Siswa untuk memasukkan ribuan data sekaligus. <br>
                        Sistem mendukung file Excel standar sekolah. Pastikan ada kolom <b>Nama</b> dan <b>Kelas</b>.
                    </p>
                    <ul style="font-size: 0.85rem; color: #555; padding-left: 20px; margin-top: 10px;">
                        <li>Otomatis mendeteksi Kelas (Roman/Angka).</li>
                        <li>Otomatis membuat data Eskul & Pembina.</li>
                        <li>Otomatis mencocokkan data prestasi & absen.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="card" style="border-top: 5px solid #9b59b6;">
            <div style="display: flex; align-items: flex-start; gap: 15px;">
                <div style="background: #f3e5f5; color: #9b59b6; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0;">2</div>
                <div>
                    <h3 style="margin: 0 0 10px 0;">Transisi Semester</h3>
                    <p style="font-size: 0.9rem; color: #666; line-height: 1.6;">
                        Saat berganti semester (Ganjil ke Genap), masuk ke menu <b>Pengaturan</b> lalu ubah **Semester Data**.
                    </p>
                    <div style="background: #fff9c4; padding: 10px; border-radius: 8px; font-size: 0.8rem; margin-top: 10px; border: 1px solid #fbc02d;">
                        <i class="fas fa-exclamation-triangle"></i> <b>Penting:</b> Nilai dan absen akan dipisahkan berdasarkan semester yang aktif. Data lama tidak akan terhapus, namun tidak muncul di laporan semester baru.
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="card" style="border-top: 5px solid #2ecc71;">
            <div style="display: flex; align-items: flex-start; gap: 15px;">
                <div style="background: #e8f5e9; color: #2ecc71; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0;">3</div>
                <div>
                    <h3 style="margin: 0 0 10px 0;">Komunikasi (Pengumuman)</h3>
                    <p style="font-size: 0.9rem; color: #666; line-height: 1.6;">
                        Gunakan menu <b>Papan Pengumuman</b> untuk memberikan instruksi kepada seluruh Guru Pembina.
                    </p>
                    <p style="font-size: 0.85rem; color: #555; margin-top: 10px;">
                        Setiap kali Admin menerbitkan pengumuman, kotak pesan akan muncul di <b>Dashboard Utama</b> guru saat mereka login.
                    </p>
                </div>
            </div>
        </div>

        <!-- Step 4 -->
        <div class="card" style="border-top: 5px solid #e74c3c;">
            <div style="display: flex; align-items: flex-start; gap: 15px;">
                <div style="background: #ffebee; color: #e74c3c; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0;">4</div>
                <div>
                    <h3 style="margin: 0 0 10px 0;">Audit Log & Keamanan</h3>
                    <p style="font-size: 0.9rem; color: #666; line-height: 1.6;">
                        Pantau siapa saja yang mengubah data melalui menu <b>Riwayat Log</b>.
                    </p>
                    <p style="font-size: 0.85rem; color: #555; margin-top: 10px;">
                        Jika terjadi kesalahan massal (salah import, dll), Admin bisa melakukan **Reset Total Database** melalui tombol merah di menu Data Siswa.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!-- Tips Section -->
    <div class="card" style="margin-top: 40px; border: 1px dashed #ccc; background: #fafafa;">
        <h3 style="margin-bottom: 20px;"><i class="fas fa-lightbulb" style="color: #f1c40f;"></i> Tips Produktivitas</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div style="font-size: 0.9rem; line-height: 1.6;">
                <b>1. Filter Global:</b> Gunakan kotak pencarian di bagian atas layar untuk mencari Siswa atau Guru dari halaman mana pun.
            </div>
            <div style="font-size: 0.9rem; line-height: 1.6;">
                <b>2. Branding Flash:</b> Ingin suasana baru? Ganti warna tema di menu Pengaturan untuk mengubah mood aplikasi secara instan.
            </div>
            <div style="font-size: 0.9rem; line-height: 1.6;">
                <b>3. Auto-Save:</b> Orang tua tidak perlu khawatir data hilang saat mengisi form pendaftaran eskul jika browser tertutup mendadak.
            </div>
            <div style="font-size: 0.9rem; line-height: 1.6;">
                <b>4. Cetak Akun:</b> Di menu Guru Pembina, ada tombol **Cetak Akun** untuk menghasilkan slip kecil berisi username/password bagi setiap guru.
            </div>
        </div>
    </div>

</div>
@endsection
