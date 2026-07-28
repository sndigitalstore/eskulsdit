@php
    $currentPath = request()->path();
    
    $pageHelpData = [
        'dashboard' => [
            'title' => 'Dashboard Utama',
            'subtitle' => 'Pusat Kendali & Ringkasan Informasi Sistem',
            'icon' => 'fas fa-th-large',
            'color' => '#6366f1',
            'description' => 'Halaman ini menampilkan gambaran umum aktivitas sekolah, statistik siswa & eskul, serta pengumuman terbaru.',
            'features' => [
                ['title' => 'Statistik Ringkas', 'desc' => 'Jumlah total siswa aktif, total eskul, dan rekapitulasi kehadiran guru hari ini.'],
                ['title' => 'Eskul Hari Ini', 'desc' => 'Jadwal ekstrakurikuler yang menyelenggarakan kegiatan pada hari ini.'],
                ['title' => 'Peringatan Absen Mingguan', 'desc' => 'Daftar eskul yang belum mengisi presensi pertemuan minggu ini.'],
                ['title' => 'Papan Pengumuman', 'desc' => 'Informasi dan instruksi resmi dari Administrator Sekolah.'],
            ],
            'tips' => 'Guru Pembina & Wali Kelas dapat langsung melihat ringkasan tugas mereka di halaman ini setelah login.'
        ],
        'students' => [
            'title' => 'Manajemen Data Siswa',
            'subtitle' => 'Pengelolaan Master Data Siswa & Keikutsertaan Eskul',
            'icon' => 'fas fa-users',
            'color' => '#0284c7',
            'description' => 'Halaman ini digunakan untuk mengelola data siswa, pembagian eskul, serta pencetakan kartu anggota.',
            'features' => [
                ['title' => 'Tambah / Edit Siswa', 'desc' => 'Menambahkan data siswa baru secara manual atau mengubah informasi siswa yang ada.'],
                ['title' => 'Mega Import Excel', 'desc' => 'Mengunggah file Excel data siswa massal sekaligus otomatis memetakan kelas & eskul.'],
                ['title' => 'Filter Kelas & Status', 'desc' => 'Menyaring daftar siswa berdasarkan kelas (1-6) atau status keaktifan.'],
                ['title' => 'Cetak Kartu Siswa', 'desc' => 'Mencetak kartu anggota ekstrakurikuler siswa dalam format siap cetak.'],
            ],
            'tips' => 'Gunakan tombol "Mega Import" untuk memasukkan data siswa awal tahun ajaran secara cepat.'
        ],
        'promotions' => [
            'title' => 'Kenaikan Kelas & Kelulusan',
            'subtitle' => 'Pemindahan Kelas Otomatis untuk Tahun Ajaran Baru',
            'icon' => 'fas fa-level-up-alt',
            'color' => '#16a34a',
            'description' => 'Fitur ini memudahkan proses pemindahan seluruh siswa dari kelas lama ke kelas baru saat pergantian tahun ajaran.',
            'features' => [
                ['title' => 'Pilih Kelas Asal & Tujuan', 'desc' => 'Memilih rombel asal (misal: 1A) lalu menaikkan seluruh siswanya ke rombel baru (misal: 2A).'],
                ['title' => 'Kelulusan Kelas 6', 'desc' => 'Siswa kelas 6 yang diproses kenaikan kelas akan otomatis ditandai berstatus Lulus/Alumni.'],
                ['title' => 'Verifikasi Data', 'desc' => 'Memastikan tidak ada data siswa yang tertinggal saat transisi tahun pelajaran.'],
            ],
            'tips' => 'Lakukan proses kenaikan kelas setelah Tahun Ajaran baru dibuat di menu Pengaturan.'
        ],
        'achievements' => [
            'title' => 'Data Prestasi Siswa',
            'subtitle' => 'Pencatatan Kejuaraan & Penghargaan Siswa',
            'icon' => 'fas fa-trophy',
            'color' => '#d97706',
            'description' => 'Dokumentasi prestasi akademik maupun non-akademik yang diraih oleh siswa SDIT AN NADZIR.',
            'features' => [
                ['title' => 'Catat Prestasi Baru', 'desc' => 'Memasukkan nama siswa, nama kejuaraan, tingkat (Kota/Provinsi/Nasional), dan foto piala/sertifikat.'],
                ['title' => 'Import Masal', 'desc' => 'Mengunggah banyak data prestasi sekaligus via format Excel.'],
                ['title' => 'Cetak Rekap Prestasi', 'desc' => 'Mencetak daftar prestasi siswa untuk keperluan laporan sekolah atau akreditasi.'],
            ],
            'tips' => 'Foto bukti sertifikat/piala dapat diunggah langsung agar tersimpan secara digital.'
        ],
        'teachers' => [
            'title' => 'Kelola Pembina & Wali Kelas',
            'subtitle' => 'Manajemen Akun Pengguna Guru & Wali Kelas',
            'icon' => 'fas fa-chalkboard-teacher',
            'color' => '#8b5cf6',
            'description' => 'Halaman pembuatan dan pengelolaan akun login untuk Guru Pembina Eskul dan Wali Kelas.',
            'features' => [
                ['title' => 'Tambah / Edit Akun', 'desc' => 'Membuat akun baru, menentukan eskul yang diampu, atau menetapkan kelas bimbingan (Wali Kelas).'],
                ['title' => 'Reset Password Instan 🔑', 'desc' => 'Reset kata sandi akun guru secara individu lewat tombol kunci jika guru lupa password.'],
                ['title' => 'Cetak Slip Akun', 'desc' => 'Mencetak slip berisi username & password default untuk dibagikan ke guru.'],
                ['title' => 'Import Akun Masal', 'desc' => 'Mengimpor daftar akun guru sekaligus via format teks/Excel.'],
            ],
            'tips' => 'Wali Kelas yang tidak mengampu eskul dapat didaftarkan dengan mengosongkan pilihan eskul.'
        ],
        'eskuls' => [
            'title' => 'Manajemen Ekstrakurikuler',
            'subtitle' => 'Pengaturan Daftar Kegiatan Eskul Sekolah',
            'icon' => 'fas fa-basketball-ball',
            'color' => '#ea580c',
            'description' => 'Mengelola daftar jenis kegiatan eskul, kategori, kuota pendaftaran, dan jadwal pelaksanaan.',
            'features' => [
                ['title' => 'Tambah / Edit Eskul', 'desc' => 'Mengatur nama eskul, kategori (Olahraga, Sains, Seni, Bahasa), hari & jam kegiatan.'],
                ['title' => 'Ekspor Daftar Peserta', 'desc' => 'Mengunduh file Excel berisi seluruh siswa yang memilih eskul tertentu.'],
                ['title' => 'Batas Kuota', 'desc' => 'Menetapkan batas maksimal siswa yang dapat mendaftar di masing-masing eskul.'],
            ],
            'tips' => 'Pastikan jadwal eskul sudah diisi agar muncul secara akurat di Dashboard.'
        ],
        'teacher-attendance' => [
            'title' => 'Absensi Guru Pembina',
            'subtitle' => 'Pencatatan Kehadiran Fisik Pembina Eskul',
            'icon' => 'fas fa-user-clock',
            'color' => '#059669',
            'description' => 'Halaman pencatatan kehadiran guru pembina saat mendampingi kegiatan eskul di sekolah.',
            'features' => [
                ['title' => 'Isi Absen Hari Ini', 'desc' => 'Guru memilih status kehadiran (Hadir/Izin/Sakit), catatan kegiatan, dan lampiran foto.'],
                ['title' => 'Rekapitulasi Kehadiran', 'desc' => 'Admin dapat memantau riwayat kehadiran seluruh guru pembina per periode.'],
                ['title' => 'Cetak / Ekspor Laporan', 'desc' => 'Mengunduh rekap absensi guru untuk keperluan administrasi/insentif.'],
            ],
            'tips' => 'Guru pembina disarankan mengisi absensi ini setiap kali kegiatan eskul berlangsung.'
        ],
        'attendance' => [
            'title' => 'Absensi Siswa Eskul',
            'subtitle' => 'Pencatatan Presensi Kehadiran Siswa Pertemuan Eskul',
            'icon' => 'fas fa-clipboard-list',
            'color' => '#2563eb',
            'description' => 'Digunakan oleh Guru Pembina untuk mendata kehadiran siswa peserta eskul pada setiap pertemuan.',
            'features' => [
                ['title' => 'Input Absen Pertemuan', 'desc' => 'Pilih eskul dan tanggal kegiatan, lalu tandai siswa yang Hadir, Sakit, Izin, atau Alpha.'],
                ['title' => 'Laporan Mingguan', 'desc' => 'Melihat statistik tingkat kehadiran siswa sepanjang semester.'],
            ],
            'tips' => 'Data absensi ini akan otomatis memengaruhi persentase kehadiran pada Rapor Eskul siswa.'
        ],
        'grades' => [
            'title' => 'Input Nilai Eskul',
            'subtitle' => 'Penilaian Predikat & Deskripsi Perkembangan Siswa',
            'icon' => 'fas fa-star',
            'color' => '#ca8a04',
            'description' => 'Halaman untuk memasukkan capaian nilai predikat dan deskripsi narasi perkembangan siswa per semester.',
            'features' => [
                ['title' => 'Input Nilai Langsung', 'desc' => 'Memilih predikat (A / B / C / D) dan mengisikan deskripsi kemampuan siswa.'],
                ['title' => 'Import Nilai via Excel', 'desc' => 'Mengunduh format Excel nilai, mengisinya di komputer, lalu mengunggahnya kembali.'],
                ['title' => 'Auto-Deskripsi', 'desc' => 'Menggunakan opsi deskripsi standar yang disiapkan sistem untuk mempercepat pengisian.'],
            ],
            'tips' => 'Gunakan fitur Import Excel jika ingin mengisi nilai seluruh siswa secara offline terlebih dahulu.'
        ],
        'reports' => [
            'title' => 'Laporan & Rapor Eskul',
            'subtitle' => 'Pencetakan & Rekapitulasi Rapor Ekstrakurikuler',
            'icon' => 'fas fa-file-alt',
            'color' => '#dc2626',
            'description' => 'Halaman khusus bagi Wali Kelas dan Admin untuk merekap dan mencetak Rapor Eskul siswa per kelas.',
            'features' => [
                ['title' => 'Cetak Rapor Per Kelas', 'desc' => 'Mencetak Rapor Eskul resmi untuk seluruh siswa di kelas bimbingan secara kolektif.'],
                ['title' => 'Ekspor Rekap Excel', 'desc' => 'Mengunduh rekap nilai eskul seluruh siswa kelas dalam format file Excel.'],
                ['title' => 'Filter Semester & Tahun', 'desc' => 'Melihat riwayat nilai eskul siswa pada semester atau tahun ajaran sebelumnya.'],
            ],
            'tips' => 'Wali Kelas dapat mencetak Rapor Eskul ini untuk dilampirkan bersama Rapor Akademik siswa.'
        ],
        'settings' => [
            'title' => 'Pengaturan Aplikasi',
            'subtitle' => 'Konfigurasi Global Sistem & Tahun Ajaran',
            'icon' => 'fas fa-cog',
            'color' => '#475569',
            'description' => 'Halaman pusat pengaturan konfigurasi sekolah, Tahun Ajaran aktif, semester, dan tampilan.',
            'features' => [
                ['title' => 'Kelola Tahun Ajaran', 'desc' => 'Membuat Tahun Ajaran baru dan mengaktifkan semester berjalan (Semester 1 atau 2).'],
                ['title' => 'Atur Form Pendaftaran', 'desc' => 'Membuka/menutup form pendaftaran online siswa dan membatasi jumlah eskul yang boleh dipilih.'],
                ['title' => 'Kustomisasi Tema', 'desc' => 'Mengubah warna utama dan warna aksen tampilan aplikasi.'],
                ['title' => 'Profil Sekolah', 'desc' => 'Mengatur nama sekolah, teks kaki (footer), dan informasi umum.'],
            ],
            'tips' => 'Pastikan Tahun Ajaran dan Semester aktif sudah benar sebelum memulai pengisian nilai/absen.'
        ],
        'announcements' => [
            'title' => 'Papan Pengumuman',
            'subtitle' => 'Pesan Internal untuk Guru & Pembina',
            'icon' => 'fas fa-bullhorn',
            'color' => '#e11d48',
            'description' => 'Media komunikasi resmi dari Admin kepada seluruh Guru Pembina dan Wali Kelas.',
            'features' => [
                ['title' => 'Terbit Pengumuman', 'desc' => 'Membuat pengumuman baru berisi judul, isi instruksi, dan tanggal berlaku.'],
                ['title' => 'Tampil di Dashboard', 'desc' => 'Pengumuman aktif akan muncul di kotak pesan Dashboard saat guru login.'],
            ],
            'tips' => 'Gunakan fitur ini untuk menyampaikan tenggat waktu pengisian nilai atau jadwal rapat.'
        ],
        'logs' => [
            'title' => 'Riwayat Log Aktivitas',
            'subtitle' => 'Audit Trail Aksi Pengguna Sistem',
            'icon' => 'fas fa-history',
            'color' => '#0f766e',
            'description' => 'Catatan rekam jejak digital atas setiap penambahan, perubahan, atau penghapusan data di aplikasi.',
            'features' => [
                ['title' => 'Pantau Pengguna', 'desc' => 'Melihat siapa pengguna yang melakukan aksi, jenis perubahan, dan waktunya.'],
                ['title' => 'Keamanan Data', 'desc' => 'Mencegah terjadinya kesalahan tanpa terdeteksi dan menjaga transparansi pengelolaan.'],
            ],
            'tips' => 'Gunakan filter log jika ingin memeriksa riwayat aktivitas pada tanggal tertentu.'
        ],
        'import-portal' => [
            'title' => 'Import Portal Satu Pintu',
            'subtitle' => 'Pusat Unggah Data Excel Terpadu',
            'icon' => 'fas fa-file-import',
            'color' => '#2563eb',
            'description' => 'Fasilitas mengunggah berbagai jenis data (Siswa, Guru, Nilai, Absen) dari satu tempat.',
            'features' => [
                ['title' => 'Unduh Template Excel', 'desc' => 'Menyediakan berkas template Excel resmi yang sudah disesuaikan formatnya.'],
                ['title' => 'Proses Import Cepat', 'desc' => 'Sistem akan otomatis mengecek validasi baris data sebelum memasukkannya ke database.'],
            ],
            'tips' => 'Selalu gunakan template resmi agar format kolom dibaca dengan benar oleh sistem.'
        ],
        'profile' => [
            'title' => 'Profil Saya & Ubah Password',
            'subtitle' => 'Manajemen Identitas & Keamanan Akun Anda',
            'icon' => 'fas fa-key',
            'color' => '#4f46e5',
            'description' => 'Halaman untuk melihat rincian akun Anda serta memperbarui kata sandi akun secara mandiri.',
            'features' => [
                ['title' => 'Info Akun', 'desc' => 'Menampilkan nama lengkap, username, peran (Admin/Guru/Wali Kelas), dan eskul binaan.'],
                ['title' => 'Ubah Password', 'desc' => 'Mengganti password default dengan password baru pilihan Anda demi keamanan.'],
            ],
            'tips' => 'Segera ubah password default dari Admin setelah Anda berhasil login pertama kali.'
        ],
        'guide' => [
            'title' => 'Pusat Bantuan & Panduan Sistem',
            'subtitle' => 'Panduan Penggunaan Lengkap Aplikasi',
            'icon' => 'fas fa-book-open',
            'color' => '#0284c7',
            'description' => 'Halaman dokumentasi menyeluruh berisi petunjuk langkah-demi-langkah penggunaan aplikasi.',
            'features' => [
                ['title' => 'Panduan Alur Kerja', 'desc' => 'Langkah persiapan awal tahun ajaran, pengisian absen, hingga pencetakan rapor.'],
                ['title' => 'Tips & Solusi Problem', 'desc' => 'Jawaban atas pertanyaan umum dan solusi jika mengalami kendala teknis.'],
            ],
            'tips' => 'Pelajari halaman panduan ini untuk memahami seluruh ekosistem fitur sistem eskul.'
        ]
    ];

    // Determine current active page key
    $matchedKey = 'dashboard';
    foreach ($pageHelpData as $key => $info) {
        if ($key === 'dashboard' && ($currentPath === 'dashboard' || $currentPath === '/')) {
            $matchedKey = 'dashboard';
            break;
        } elseif ($key !== 'dashboard' && str_contains($currentPath, $key)) {
            $matchedKey = $key;
            break;
        }
    }

    $currentHelp = $pageHelpData[$matchedKey] ?? $pageHelpData['dashboard'];
@endphp

<script>
    function showContextualHelp() {
        const helpData = @json($currentHelp);
        
        let featuresHtml = '';
        helpData.features.forEach(f => {
            featuresHtml += `
                <div style="text-align: left; background: #f8fafc; padding: 10px 14px; border-radius: 8px; border-left: 4px solid ${helpData.color}; margin-bottom: 8px;">
                    <div style="font-weight: 700; color: #1e293b; font-size: 0.9rem;">
                        <i class="fas fa-check-circle" style="color: ${helpData.color}; margin-right: 6px;"></i> ${f.title}
                    </div>
                    <div style="font-size: 0.825rem; color: #64748b; margin-top: 3px; line-height: 1.4;">${f.desc}</div>
                </div>
            `;
        });

        Swal.fire({
            title: `<div style="display:flex; align-items:center; justify-content:center; gap:10px; color:${helpData.color}; font-size:1.2rem;">
                        <i class="${helpData.icon}"></i> ${helpData.title}
                    </div>`,
            html: `
                <div style="font-size: 0.85rem; color: #64748b; margin-top: -5px; margin-bottom: 15px; font-weight: 600;">${helpData.subtitle}</div>
                <div style="text-align: left; font-size: 0.875rem; color: #334155; margin-bottom: 15px; background: #f1f5f9; padding: 12px; border-radius: 10px; line-height: 1.5;">
                    ${helpData.description}
                </div>
                <div style="font-weight: 700; text-align: left; margin-bottom: 8px; color: #0f172a; font-size: 0.9rem;">
                    <i class="fas fa-star" style="color: #f59e0b;"></i> Fitur Utama di Halaman Ini:
                </div>
                <div style="max-height: 240px; overflow-y: auto; padding-right: 4px;">
                    ${featuresHtml}
                </div>
                <div style="margin-top: 15px; background: #fffbeb; border: 1px solid #fde68a; padding: 10px; border-radius: 8px; text-align: left; font-size: 0.8rem; color: #92400e;">
                    <i class="fas fa-lightbulb" style="color: #d97706; margin-right: 5px;"></i> <b>Tips:</b> ${helpData.tips}
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-book-open"></i> Buka Panduan Lengkap',
            cancelButtonText: 'Tutup',
            confirmButtonColor: helpData.color,
            cancelButtonColor: '#94a3b8',
            width: '580px',
            customClass: {
                popup: 'swal-help-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('guide.index') }}";
            }
        });
    }
</script>
