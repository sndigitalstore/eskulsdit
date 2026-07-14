# Panduan Lengkap Administrator & Guru - Sistem Informasi Ekstrakurikuler

Dokumen ini berisi panduan penggunaan terbaru untuk Sistem Informasi Ekstrakurikuler SDIT AN NADZIR.

## 🚀 Langkah Awal Penggunaan (Setup Awal)
Jika Anda baru pertama kali menggunakan aplikasi ini atau memulai tahun ajaran baru, ikuti urutan langkah berikut agar data terinput dengan benar:

1. **Atur Tahun Ajaran & Semester**: 
   - Buka menu **Tahun Ajaran**. 
   - Tambahkan tahun baru (misal: 2024/2025) dan pastikan statusnya **Aktif**.
   - Set Semester ke **Semester 1**.

2. **Input Data Ekstrakurikuler**:
   - Buka menu **Data Eskul**.
   - Masukkan daftar eskul (Panahan, Futsal, Drumband, dll).
   - Atur **Kuota Maksimal** untuk setiap eskul (ini akan membatasi jumlah pendaftar di formulir).

3. **Buat Akun Guru Pembina**:
   - Buka menu **Kelola Pembina** > **Import Masal**.
   - Tempel daftar guru dari Excel agar mereka bisa login dan mulai mengisi absen/nilai.

4. **Import Data Siswa**:
   - Buka menu **Data Siswa** > **Import Excel**.
   - Unggah data siswa dari Excel. Gunakan kolom minimal: `Nama Siswa`, `Kelas`, dan `NIS`.
   - Ini akan menyiapkan "Bank Data Siswa" agar bisa dipilih saat pendaftaran eskul.

5. **Buka Pendaftaran (Eskul Selection)**:
   - Setelah semua siap, buka Dashboard, lalu klik **Bagikan Formulir**.
   - Bagikan link tersebut ke orang tua siswa melalui WhatsApp.
   - Siswa yang mendaftar akan otomatis masuk ke daftar eskul masing-masing.

---

## 1. Akses & Login
- **URL Login**: `/login` (Desain baru yang menarik)
- **Dashboard**: Tampilan cepat statistik siswa, grafik popularitas eskul, dan widget "Aksi Cepat".
- **Akses Cepat**: Tombol *Share* Formulir dan Link Prestasi (QR Code) tersedia langsung di Dashboard.

## 2. Fitur Unggulan Baru

### 🌟 Kartu Pelajar Digital
1. Buka menu **Data Siswa**.
2. Klik tombol **Kartu** pada salah satu siswa.
3. Anda bisa melihat kartu dengan **QR Code** unik.
4. Klik **Download PNG** untuk menyimpan kartu.
5. **Upload Foto**: Saat *Edit* atau *Tambah Siswa*, Anda bisa mengupload foto asli siswa untuk ditampilkan di kartu.

### 🔍 Pencarian Global (Quick Search)
- Di bagian atas layar (Header), terdapat kolom pencarian besar.
- Ketik **Nama**, **Kelas**, atau **ID** untuk langsung menemukan siswa dan mengakses profil/kartu mereka.

### 📅 Atur Jadwal Serentak
1. Buka menu **Ekstrakurikuler**.
2. Klik tombol ungu **Atur Jadwal Serentak**.
3. Masukkan jadwal (Contoh: "Rabu, 13.00 - 15.00").
4. Sistem otomatis mengupdate jadwal untuk **SEMUA** eskul sekaligus.

### 🎓 Lulusan Calistung & Sistem Kunci (Lock System)
1. **Sistem Kunci Otomatis**:
   - Eskul Calistung kini memiliki **"Mode Penguncian"**.
   - Admin bisa mengaktifkan/menonaktifkan kunci ini di menu **Data Eskul** > **Edit** > Centang **Modus Penguncian**.
   - Jika aktif, siswa TIDAK BISA pindah eskul kecuali sudah LULUS (Nilai A semua).
2. **Download Lulusan**:
   - Buka menu **Laporan**.
   - Klik kotak hijau **"Lulusan Calistung"** untuk download data siswa yang sudah lulus dan siap pindah.

### 🏆 Data Prestasi Siswa
Fitur baru untuk mencatat sejarah juara siswa.
1. Buka menu **Data Prestasi**.
2. Klik **Tambah Prestasi Baru**.
3. **Pencarian Cerdas**: Ketik nama siswa, sistem langsung memunculkan saran (Live Search).
4. Data yang direkam: Nama Lomba, Tingkat (Kab/Kota/Prov), Tanggal, dll.
5. **Backup Aman**: Data prestasi OTOMATIS tersimpan saat Anda melakukan Backup Data Siswa (Excel).

### 📊 Rekap Absensi Cepat (Siswa)
1. Buka menu **Absensi**.
2. Pilih Tahun Ajaran dan Eskul.
3. Klik tombol putih **[ 📊 Lihat Rekap Absensi ]** yang ada di bawah tombol lanjut.
4. Tabel rekapitulasi kehadiran (Hadir/Sakit/Izin/Alpa) akan langsung muncul tanpa perlu pindah halaman.

### 👨‍🏫 Absensi Guru & Fitur "Guru Pengganti"
Sistem kini memiliki fitur khusus untuk menjamin keberlangsungan eskul saat pembina berhalangan.
1. **Guru Pengganti**: Saat guru memilih status **Sakit** atau **Izin**, sistem otomatis meminta input **Nama Guru Pengganti**.
2. **Akuntabilitas**: Nama pengganti akan tercatat dalam database dan muncul di laporan bulanan admin.
3. **Download Rekap Bulanan**:
   - Menu **Absensi Guru** (Admin) > Pilih **Bulan/Tahun** > Klik **Excel Bulanan**.
   - File Excel sudah diformat rapi dengan kop hijau.
   - **Fitur Spesial**: Di bagian bawah file Excel terdapat tabel **Rekapitulasi Total (Insentif)** yang menghitung otomatis jumlah kehadiran setiap guru untuk memudahkan bendahara membayar honor.

### 🔗 Integrasi Absensi ke Penilaian
Penilaian siswa kini lebih akurat karena terhubung dengan data kehadiran.
1. Saat guru mengisi **Nilai Harian**, sistem mengecek apakah siswa hadir pada tanggal tersebut.
2. Siswa yang **Sakit/Izin/Alpha** akan otomatis diberi tanda/badge khusus.
3. **Input Terkunci**: Guru tidak bisa (dan tidak perlu) mengisi nilai untuk siswa yang tidak hadir. Hal ini mencegah kesalahan data.

### 🚀 Mega Import & Restore (Pemulihan Total)
Sifatnya sangat kuat, bisa memindahkan/mengembalikan seluruh data sekaligus.
1. Buka menu **Data Siswa** > **Import Excel**.
2. Masukkan file **Backup `.xlsx`** hasil download sebelumnya (dari tombol Backup Data Siswa).
3. **Hasil**: Seluruh data Siswa, Absensi, Nilai, Prestasi, hingga Absensi Guru akan terisi kembali secara otomatis sesuai sheet masing-masing.

### 📥 Input Masal Prestasi (Fast Entry)
1. Buka menu **Data Prestasi** > Klik tombol hijau **Input Masal**.
2. Copy-Paste data dari Excel dengan urutan: `Nama Siswa | Nama Prestasi | Tingkat | Tanggal | Penyelenggara`.
3. Klik **Proses**, dan sistem akan mencocokkan nama siswa secara cerdas (Fuzzy Match).


## 3. Manajemen Kenaikan Kelas & Kelulusan (Semester/Tahun Baru)
Sistem memiliki fitur pintar untuk memproses kenaikan kelas masal.

1. Buka menu **Kenaikan Kelas**.
2. Ketik nama kelas asal (Misal: `5A`).
3. Klik **Tampilkan Siswa**.
4. **Aksi Otomatis**:
   - Jika kelas `1-5`: Tombol akan berbunyi **"NAIKKAN KE KELAS [X]"**. Warna Biru.
   - Jika kelas `6`: Tombol akan berbunyi **"LULUSKAN SISWA"**. Warna Hijau.
5. Konfirmasi menggunakan *Pop-up* modern.
6. **PENTING**: Siswa yang sudah berstatus **Lulus (Graduated)**:
   - Tidak akan muncul lagi di Absensi/Nilai.
   - Tidak akan dihitung dalam Kuota Eskul (sehingga kuota eskul menjadi lega kembali).
   - Tidak bisa dipilih saat mengisi formulir pendaftaran eskul.

## 4. Alur Kerja Administrator
**(Rutin Harian/Mingguan)**

1. **Kelola Siswa**: Tambah/Edit siswa + Upload Foto jika ada.
2. **Cek Absensi**: Gunakan fitur "Rekap Absensi Cepat" untuk memantau kehadiran.
3. **Cek Kuota Eskul**:
   - Buka formulir publik (`/pilihan-eskul`) untuk melihat status kuota.
   - Kuota otomatis bertambah jika ada siswa kelas 6 yang diluluskan.

**(Akhir Semester)**
1. **Input Nilai**: Pastikan semua nilai SAS masuk.
2. **Cetak Laporan**: Download PDF/Excel rapor eskul untuk dibagikan.
3. **Kenaikan Kelas**: Gunakan menu Kenaikan Kelas untuk memindahkan siswa ke jenjang berikutnya.
4. **Ganti Semester**:
   - Buka menu **Tahun Ajaran**.
   - Pada tahun aktif, ubah Semester 1 -> Semester 2 menggunakan dropdown langsung di tabel.

## 5. Tips & Trik
- **Login Menarik**: Tampilan login sekarang full animasi dan tetap indah saat dibuka di Handphone.
- **Tanda Tangan Resmi**: Pada Laporan Prestasi, tanda tangan Kepala Sekolah sudah berada di sisi kanan dengan lokasi **Cinangka**.
- **Admin vs Guru**: Admin memiliki akses penuh (Cetak Laporan, Edit Jadwal), sedangkan Guru hanya fokus pada Absensi, Nilai, dan Share Link.
- **Link Cepat**: Gunakan tombol "Bagikan Formulir" di Dashboard untuk mengirim link pendaftaran ke grup WhatsApp wali murid.

### 🛡️ Audit Trail (Log Aktivitas)
Sistem mencatat setiap aksi kritikal yang dilakukan oleh Admin atau Guru untuk menjaga integritas data.
1. Buka menu **Log Aktivitas**.
2. Anda dapat melihat detail: **Siapa** yang melakukan aksi, **Apa** yang dilakukan (Create/Update/Delete), **Kapan**, dan dari **IP Address** mana.
3. Fitur ini sangat berguna untuk menelusuri jika ada kesalahan input data atau penghapusan data yang tidak sengaja.
4. **Hapus Log**: Admin dapat membersihkan log lama berdasarkan rentang waktu tertentu.

### 📢 Internal Broadcast (Pengumuman)
Admin dapat mengirim pesan langsung ke dashboard seluruh pembina.
1. Buka menu **Pengumuman**.
2. Klik **Buat Pengumuman Baru**.
3. Pilih Tipe: `PENTING` (Warna Merah), `INFO` (Warna Biru), atau `SUKSES` (Warna Hijau).
4. Pesan akan langsung muncul di halaman Dashboard utama setiap pembina begitu mereka login.

### 🎨 Custom Branding & Pengaturan
Admin dapat menyesuaikan identitas aplikasi.
1. Buka menu **Pengaturan**.
2. **Warna Utama**: Ubah warna tema aplikasi (Sidebar, Tombol, Landing Page) sesuai identitas sekolah.
3. **Konfigurasi Form**: Aktifkan/Nonaktifkan field tertentu pada formulir pendaftaran (seperti NIS, No HP Ortu, dll).
4. **Profil Admin**: Update password admin secara berkala melalui tab profil.

### 📱 Notifikasi WhatsApp Real-time
Sistem kini mendukung pengiriman pesan otomatis ke WhatsApp untuk meningkatkan komunikasi.
1. **Konfirmasi Pendaftaran**: Saat orang tua mengisi formulir eskul, mereka wajib memasukkan No. WA. Setelah klik "Kirim", sistem otomatis mengirimkan rincian pendaftaran ke nomor tersebut.
2. **Siaran Pengumuman (Broadcast)**: 
   - Di menu **Pengumuman**, centang kotak **"Kirim ke WA Seluruh Guru"**.
   - Judul dan isi pengumuman akan dikirim langsung ke nomor WA masing-masing guru pembina yang terdaftar.
3. **PENTING**: Fitur ini memerlukan API Token dari penyedia layanan (default: Fonnte). Pastikan saldo/kuota API Anda mencukupi.


