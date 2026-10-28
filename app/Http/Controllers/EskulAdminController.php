<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eskul;
use App\Models\EskulProgram;
use App\Models\EskulProgramItem;
use App\Models\EskulJournal;
use App\Models\AcademicYear;
use App\Models\Setting;

class EskulAdminController extends Controller
{
    /**
     * Halaman Program Kerja: form isi & kelola kegiatan (Mendukung 1 Tahun Pelajaran & Per Semester)
     */
    public function programIndex(Request $request, Eskul $eskul)
    {
        $this->authorizeEskul($eskul);

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $semester = $activeYear->active_semester;
        $filterSemester = $request->query('semester', 'all'); // 'all' (1 Tahun Penuh), '1', atau '2'

        // Ambil atau buat program eskul
        $program = EskulProgram::firstOrCreate(
            ['eskul_id' => $eskul->id, 'academic_year_id' => $activeYear->id, 'semester' => $semester],
            [
                'visi'        => '',
                'misi'        => '',
                'tujuan'      => '',
                'sasaran'     => '',
                'sarana'      => '',
                'sumber_dana' => '',
                'kepsek_name' => Setting::get('headmaster_name', "Nur'asiah, S.Pd.I"),
                'nip_kepsek'  => Setting::get('headmaster_nip', "141502002"),
            ]
        );

        // Pre-populate data visi & misi default jika masih kosong atau jika nip_kepsek belum terisi
        if (empty($program->visi) || empty($program->nip_kepsek) || $program->nip_kepsek === '-') {
            $program->update([
                'visi'        => $program->visi ?: "Menjadikan ekstrakurikuler {$eskul->name} sebagai sarana pembentukan karakter islami, sportivitas, kreativitas, dan pencapaian prestasi unggul peserta didik.",
                'misi'        => $program->misi ?: "1. Menumbuhkan minat, bakat, dan potensi peserta didik dalam bidang {$eskul->name} secara bertahap dan terukur.\n2. Menanamkan nilai-nilai kedisiplinan, akhlakul karimah, kemandirian, dan kerja sama tim.\n3. Melaksanakan pelatihan rutin secara berkesinambungan dengan metode yang menyenangkan.\n4. Mempersiapkan peserta didik untuk berpartisipasi dan berprestasi dalam berbagai ajang lomba / unjuk bakat.",
                'tujuan'      => $program->tujuan ?: "1. Menyalurkan dan mengembangkan potensi minat bakat siswa secara optimal.\n2. Meningkatkan keterampilan teknik dasar dan lanjutan dalam {$eskul->name}.\n3. Membangun kepercayaan diri, kebugaran jasmani, dan jiwa kepemimpinan peserta didik.",
                'sasaran'     => $program->sasaran ?: "Peserta didik SDIT AN NADZIR yang terdaftar dalam ekstrakurikuler {$eskul->name} (Target: Minimal 85% siswa mencapai kompetensi tuntas berpredikat Baik).",
                'sarana'      => $program->sarana ?: "Area latihan sekolah, modul panduan materi eskul, peralatan penunjang, serta fasilitas pendukung lainnya.",
                'sumber_dana' => $program->sumber_dana ?: "Bantuan Operasional Sekolah (BOS) / Anggaran Yayasan dan kas mandiri ekstrakurikuler.",
                'kepsek_name' => Setting::get('headmaster_name', "Nur'asiah, S.Pd.I"),
                'nip_kepsek'  => Setting::get('headmaster_nip', "141502002"),
            ]);
            $program->refresh();
        }

        // Cek jika item kegiatan belum ada sama sekali untuk tahun ajaran ini, otomatis buat 12 bulan (Program 1 Tahun)
        $totalItemsCount = EskulProgramItem::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $activeYear->id)
            ->count();

        if ($totalItemsCount === 0) {
            $this->seedDefaultProgramItems($eskul, $activeYear);
        }

        // Ambil data item kegiatan berdasarkan filter
        $itemsQuery = EskulProgramItem::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $activeYear->id);

        if ($filterSemester !== 'all') {
            $itemsQuery->where('semester', $filterSemester);
        }

        $items = $itemsQuery->orderBy('urutan')->get();

        $schoolData = $this->getSchoolData();

        return view('eskuls.program', array_merge(
            compact('eskul', 'activeYear', 'semester', 'filterSemester', 'program', 'items'),
            $schoolData
        ));
    }

    /**
     * Muat / Reset Template Rencana Kegiatan 1 Tahun Pelajaran (12 Bulan)
     */
    public function programGenerateTemplate(Eskul $eskul)
    {
        $this->authorizeEskul($eskul);

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        // Hapus item lama pada tahun ajaran ini
        EskulProgramItem::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $activeYear->id)
            ->delete();

        // Buat 12 bulan baru
        $this->seedDefaultProgramItems($eskul, $activeYear);

        // Pastikan visi & misi juga terisi standar
        $semester = $activeYear->active_semester;
        $program = EskulProgram::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester', $semester)
            ->first();

        if ($program) {
            $program->update([
                'visi'        => "Menjadikan ekstrakurikuler {$eskul->name} sebagai sarana pembentukan karakter islami, sportivitas, kreativitas, dan pencapaian prestasi unggul peserta didik.",
                'misi'        => "1. Menumbuhkan minat, bakat, dan potensi peserta didik dalam bidang {$eskul->name} secara bertahap dan terukur.\n2. Menanamkan nilai-nilai kedisiplinan, akhlakul karimah, kemandirian, dan kerja sama tim.\n3. Melaksanakan pelatihan rutin secara berkesinambungan dengan metode yang menyenangkan.\n4. Mempersiapkan peserta didik untuk berpartisipasi dan berprestasi dalam berbagai ajang lomba / unjuk bakat.",
                'tujuan'      => "1. Menyalurkan dan mengembangkan potensi minat bakat siswa secara optimal.\n2. Meningkatkan keterampilan teknik dasar dan lanjutan dalam {$eskul->name}.\n3. Membangun kepercayaan diri, kebugaran jasmani, dan jiwa kepemimpinan peserta didik.",
                'sasaran'     => "Peserta didik SDIT AN NADZIR yang terdaftar dalam ekstrakurikuler {$eskul->name} (Target: Minimal 85% siswa mencapai kompetensi tuntas berpredikat Baik).",
                'sarana'      => "Area latihan sekolah, modul panduan materi eskul, peralatan penunjang, serta fasilitas pendukung lainnya.",
                'sumber_dana' => "Bantuan Operasional Sekolah (BOS) / Anggaran Yayasan dan kas mandiri ekstrakurikuler.",
                'kepsek_name' => Setting::get('headmaster_name', "Nur'asiah, S.Pd.I"),
                'nip_kepsek'  => Setting::get('headmaster_nip', "141502002"),
            ]);
        }

        \App\Models\ActivityLog::log('Eskul', 'Create', "Memuat template rencana kegiatan 1 tahun untuk eskul: {$eskul->name}.");

        return back()->with('success', 'Rencana Kegiatan 1 Tahun Pelajaran (12 Bulan) berhasil dimuat dan terisi lengkap!');
    }

    /**
     * Simpan / update Program Kerja (identitas & teks)
     */
    public function programStore(Request $request, Eskul $eskul)
    {
        $this->authorizeEskul($eskul);

        $validated = $request->validate([
            'visi'         => 'nullable|string|max:500',
            'misi'         => 'nullable|string|max:2000',
            'tujuan'       => 'nullable|string|max:2000',
            'sasaran'      => 'nullable|string|max:1000',
            'sarana'       => 'nullable|string|max:2000',
            'sumber_dana'  => 'nullable|string|max:1000',
            'kepsek_name'  => 'nullable|string|max:255',
            'nip_kepsek'   => 'nullable|string|max:50',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $semester   = $activeYear->active_semester;

        // Simpan untuk semester aktif
        EskulProgram::updateOrCreate(
            ['eskul_id' => $eskul->id, 'academic_year_id' => $activeYear->id, 'semester' => $semester],
            $validated
        );

        // Sinkronkan juga ke semester pasangannya jika ada agar konsisten 1 tahun
        $otherSemester = ($semester === '1') ? '2' : '1';
        EskulProgram::updateOrCreate(
            ['eskul_id' => $eskul->id, 'academic_year_id' => $activeYear->id, 'semester' => $otherSemester],
            $validated
        );

        \App\Models\ActivityLog::log('Eskul', 'Update', "Memperbarui program kerja eskul: {$eskul->name}.");

        return back()->with('success', 'Program kerja berhasil disimpan!');
    }

    /**
     * Simpan item kegiatan (baris program kerja)
     */
    public function programItemStore(Request $request, Eskul $eskul)
    {
        $this->authorizeEskul($eskul);

        $validated = $request->validate([
            'bulan'             => 'required|string|max:30',
            'kegiatan'          => 'required|string|max:255',
            'tujuan_kegiatan'   => 'nullable|string|max:255',
            'tempat'            => 'nullable|string|max:100',
            'waktu'             => 'nullable|string|max:100',
            'penanggung_jawab'  => 'nullable|string|max:100',
            'semester'          => 'nullable|in:1,2',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        // Tentukan semester otomatis berdasarkan nama bulan jika tidak diinput
        $semester1Months = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $semester = $request->filled('semester') 
            ? $request->semester 
            : (in_array(trim($request->bulan), $semester1Months) ? '1' : '2');

        $maxUrutan = EskulProgramItem::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $activeYear->id)
            ->max('urutan') ?? 0;

        EskulProgramItem::create(array_merge($validated, [
            'eskul_id'        => $eskul->id,
            'academic_year_id'=> $activeYear->id,
            'semester'        => $semester,
            'urutan'          => $maxUrutan + 1,
        ]));

        return back()->with('success', 'Kegiatan baru berhasil ditambahkan!');
    }

    /**
     * Hapus item kegiatan
     */
    public function programItemDestroy(Eskul $eskul, EskulProgramItem $item)
    {
        $this->authorizeEskul($eskul);
        $item->delete();
        return back()->with('success', 'Kegiatan berhasil dihapus!');
    }

    /**
     * Halaman cetak Program Kerja (Format Resmi Akreditasi & PKKS 1 Tahun Pelajaran)
     */
    public function programPrint(Request $request, Eskul $eskul)
    {
        $this->authorizeEskul($eskul);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $semester   = $activeYear->active_semester;
        $filterSemester = $request->query('semester', 'all'); // 'all' (1 Tahun Penuh), '1', atau '2'

        $program = EskulProgram::where([
            'eskul_id'        => $eskul->id,
            'academic_year_id'=> $activeYear->id,
            'semester'        => $semester,
        ])->first();

        if (!$program) {
            $program = EskulProgram::where('eskul_id', $eskul->id)
                ->where('academic_year_id', $activeYear->id)
                ->first();
        }

        $itemsQuery = EskulProgramItem::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $activeYear->id);

        if ($filterSemester !== 'all') {
            $itemsQuery->where('semester', $filterSemester);
        }

        $items = $itemsQuery->orderBy('urutan')->get();

        $eskul->load(['students' => function($q) use ($activeYear) {
            $q->wherePivot('academic_year_id', $activeYear->id)
              ->where('status', '!=', 'graduated');
        }]);

        $schoolData = $this->getSchoolData();

        return view('eskuls.program_print', array_merge(
            compact('eskul', 'activeYear', 'semester', 'filterSemester', 'program', 'items'),
            $schoolData
        ));
    }

    /* ========================
     *  JURNAL KEGIATAN
     * ======================== */

    /**
     * Index Jurnal Kegiatan
     */
    public function jurnalIndex(Eskul $eskul)
    {
        $this->authorizeEskul($eskul);

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $semester = $activeYear->active_semester;

        $journals = EskulJournal::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester', $semester)
            ->orderBy('tanggal')
            ->get();

        $schoolData = $this->getSchoolData();

        return view('eskuls.jurnal', array_merge(
            compact('eskul', 'activeYear', 'semester', 'journals'),
            $schoolData
        ));
    }

    /**
     * Simpan entri jurnal baru
     */
    public function jurnalStore(Request $request, Eskul $eskul)
    {
        $this->authorizeEskul($eskul);

        $validated = $request->validate([
            'tanggal'      => 'required|date',
            'materi'       => 'required|string|max:255',
            'deskripsi'    => 'nullable|string|max:1000',
            'hadir'        => 'required|integer|min:0',
            'tidak_hadir'  => 'required|integer|min:0',
            'izin'         => 'required|integer|min:0',
            'catatan'      => 'nullable|string|max:500',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $semester   = $activeYear->active_semester;

        // Hitung pertemuan ke-
        $lastPertemuan = EskulJournal::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester', $semester)
            ->max('pertemuan_ke') ?? 0;

        EskulJournal::create(array_merge($validated, [
            'eskul_id'         => $eskul->id,
            'academic_year_id' => $activeYear->id,
            'semester'         => $semester,
            'pertemuan_ke'     => $lastPertemuan + 1,
        ]));

        \App\Models\ActivityLog::log('Eskul', 'Create', "Menambahkan jurnal kegiatan eskul: {$eskul->name} tanggal {$validated['tanggal']}.");

        return back()->with('success', 'Jurnal kegiatan berhasil ditambahkan!');
    }

    /**
     * Hapus entri jurnal
     */
    public function jurnalDestroy(Eskul $eskul, EskulJournal $journal)
    {
        $this->authorizeEskul($eskul);
        $journal->delete();

        // Re-number pertemuan_ke
        $journals = EskulJournal::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $journal->academic_year_id)
            ->where('semester', $journal->semester)
            ->orderBy('tanggal')
            ->get();

        foreach ($journals as $i => $j) {
            $j->update(['pertemuan_ke' => $i + 1]);
        }

        return back()->with('success', 'Jurnal berhasil dihapus!');
    }

    /**
     * Cetak Jurnal Kegiatan (Format Resmi Akreditasi & PKKS)
     */
    public function jurnalPrint(Eskul $eskul)
    {
        $this->authorizeEskul($eskul);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $semester   = $activeYear->active_semester;

        $journals = EskulJournal::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester', $semester)
            ->orderBy('tanggal')
            ->get();

        $schoolData = $this->getSchoolData();

        return view('eskuls.jurnal_print', array_merge(
            compact('eskul', 'activeYear', 'semester', 'journals'),
            $schoolData
        ));
    }

    /* ========================
     *  HELPER METHODS
     * ======================== */

    private function authorizeEskul(Eskul $eskul)
    {
        $user = auth()->user();
        if ($user->role === 'teacher' && $user->eskul_id != $eskul->id) {
            abort(403, 'Akses ditolak.');
        }
    }

    /**
     * Ambil data lengkap identitas sekolah dari Setting
     */
    private function getSchoolData(): array
    {
        $settings = Setting::all()->pluck('value', 'key');

        return [
            'settings'       => $settings,
            'schoolName'     => Setting::get('school_name', 'SDIT AN NADZIR'),
            'yayasanName'    => Setting::get('yayasan_name', 'YAYASAN AN NADZIR ISLAMIC SCHOOL'),
            'schoolAddress'  => Setting::get('school_address', 'Bendungan Karet Cisirih KP. Cukang RT. 09 RW. 02 Desa Kamasan Kecamatan Cinangka Kabupaten Serang - Banten 42167'),
            'schoolCity'     => Setting::get('school_city', 'Serang'),
            'npsn'           => Setting::get('npsn', '20616174'),
            'nns'            => Setting::get('nns', '101280432037'),
            'headmasterName' => Setting::get('headmaster_name', "Nur'asiah, S.Pd.I"),
            'headmasterNip'  => Setting::get('headmaster_nip', "141502002"),
        ];
    }

    /**
     * Seed rencana kegiatan 12 bulan (Program 1 Tahun Penuh) sesuai standar eskul SDIT
     */
    private function seedDefaultProgramItems(Eskul $eskul, AcademicYear $activeYear): void
    {
        $defaultActivities = [
            // Semester 1 (Ganjil)
            [
                'semester'         => '1',
                'bulan'            => 'Juli',
                'kegiatan'         => "Orientasi, Sosialisasi, dan Perekrutan Anggota Baru Eskul {$eskul->name}",
                'tujuan_kegiatan'  => 'Mengenalkan visi, misi, jadwal, dan aturan eskul kepada siswa & wali murid',
                'tempat'           => 'Ruang Kelas / Aula Sekolah',
                'waktu'            => 'Minggu ke-3 & 4',
                'penanggung_jawab' => $eskul->instructor_name ?? 'Pembina Eskul',
                'urutan'           => 1,
            ],
            [
                'semester'         => '1',
                'bulan'            => 'Agustus',
                'kegiatan'         => 'Pembentukan Struktur Regu/Kelompok & Pelatihan Materi Dasar I',
                'tujuan_kegiatan'  => 'Menata organisasi anggota dan penanaman disiplin serta teknik dasar awal',
                'tempat'           => 'Area Latihan Eskul',
                'waktu'            => 'Setiap Pekan Latihan',
                'penanggung_jawab' => $eskul->instructor_name ?? 'Pembina Eskul',
                'urutan'           => 2,
            ],
            [
                'semester'         => '1',
                'bulan'            => 'September',
                'kegiatan'         => "Pendalaman Materi Dasar & Penguatan Karakter Disiplin Siswa",
                'tujuan_kegiatan'  => "Memperkuat daya tahan fisik, konsistensi, dan penguasaan teknik dasar {$eskul->name}",
                'tempat'           => 'Area Latihan Eskul',
                'waktu'            => 'Setiap Pekan Latihan',
                'penanggung_jawab' => $eskul->instructor_name ?? 'Pembina Eskul',
                'urutan'           => 3,
            ],
            [
                'semester'         => '1',
                'bulan'            => 'Oktober',
                'kegiatan'         => 'Latihan Variasi Keterampilan Tingkat Menengah & Kerja Sama Tim',
                'tujuan_kegiatan'  => 'Melatih kekompakan, variasi gerak/materi, dan responsivitas peserta didik',
                'tempat'           => 'Area Latihan Eskul',
                'waktu'            => 'Setiap Pekan Latihan',
                'penanggung_jawab' => $eskul->instructor_name ?? 'Pembina Eskul',
                'urutan'           => 4,
            ],
            [
                'semester'         => '1',
                'bulan'            => 'November',
                'kegiatan'         => 'Simulasi Uji Praktik Mandiri & Latihan Gabungan Bersama',
                'tujuan_kegiatan'  => 'Menguji kematangan teknik dan kesiapan mental dalam suasana unjuk kemampuan',
                'tempat'           => 'Lapangan / Aula Utama',
                'waktu'            => 'Minggu ke-2 & 3',
                'penanggung_jawab' => $eskul->instructor_name ?? 'Pembina Eskul',
                'urutan'           => 5,
            ],
            [
                'semester'         => '1',
                'bulan'            => 'Desember',
                'kegiatan'         => 'Evaluasi Akhir Semester Ganjil, Unjuk Gelar Mini & Penilaian Rapor',
                'tujuan_kegiatan'  => 'Mengukur ketercapaian kompetensi semester 1 dan memberikan masukan pembinaan',
                'tempat'           => 'Lingkungan Sekolah',
                'waktu'            => 'Minggu ke-1 & 2',
                'penanggung_jawab' => $eskul->instructor_name ?? 'Pembina Eskul',
                'urutan'           => 6,
            ],

            // Semester 2 (Genap)
            [
                'semester'         => '2',
                'bulan'            => 'Januari',
                'kegiatan'         => 'Refleksi Semester 1 & Pengenalan Materi Tingkat Lanjutan (Advance)',
                'tujuan_kegiatan'  => 'Mengawali semester genap dengan motivasi baru dan pengayaan teknik lanjutan',
                'tempat'           => 'Area Latihan Eskul',
                'waktu'            => 'Minggu ke-2 & 3',
                'penanggung_jawab' => $eskul->instructor_name ?? 'Pembina Eskul',
                'urutan'           => 7,
            ],
            [
                'semester'         => '2',
                'bulan'            => 'Februari',
                'kegiatan'         => 'Pelatihan Intensif & Penjaringan Tim Inti / Delegasi Unggulan',
                'tujuan_kegiatan'  => 'Membentuk kelompok regu inti yang siap dipersiapkan untuk perlombaan / event',
                'tempat'           => 'Area Latihan Eskul',
                'waktu'            => 'Setiap Pekan Latihan',
                'penanggung_jawab' => $eskul->instructor_name ?? 'Pembina Eskul',
                'urutan'           => 8,
            ],
            [
                'semester'         => '2',
                'bulan'            => 'Maret',
                'kegiatan'         => 'Pemantapan Formasi, Taktik, serta Pembinaan Mental dan Akhlak',
                'tujuan_kegiatan'  => 'Meningkatkan ketahanan mental dan penanaman sikap rendah hati serta sportivitas',
                'tempat'           => 'Area Latihan Eskul',
                'waktu'            => 'Setiap Pekan Latihan',
                'penanggung_jawab' => $eskul->instructor_name ?? 'Pembina Eskul',
                'urutan'           => 9,
            ],
            [
                'semester'         => '2',
                'bulan'            => 'April',
                'kegiatan'         => 'Persiapan Menghadapi Festival, Lomba Antar Gugus / Uji Kenaikan Tingkat',
                'tujuan_kegiatan'  => 'Mematangkan kesiapan performa dan detail materi untuk ajang kompetisi',
                'tempat'           => 'Aula / Lapangan Sekolah',
                'waktu'            => 'Setiap Pekan Latihan',
                'penanggung_jawab' => $eskul->instructor_name ?? 'Pembina Eskul',
                'urutan'           => 10,
            ],
            [
                'semester'         => '2',
                'bulan'            => 'Mei',
                'kegiatan'         => 'Partisipasi dalam Ajang Lomba Eksternal / Gebyar Prestasi Siswa',
                'tujuan_kegiatan'  => 'Menampilkan hasil latihan, mengasah daya juang, dan meraih prestasi terbaik',
                'tempat'           => 'Tempat Kegiatan / Sekolah',
                'waktu'            => 'Sesuai Jadwal Agenda',
                'penanggung_jawab' => $eskul->instructor_name ?? 'Pembina Eskul',
                'urutan'           => 11,
            ],
            [
                'semester'         => '2',
                'bulan'            => 'Juni',
                'kegiatan'         => 'Pentas Seni / Gelar Prestasi Akhirussanah & Pelaporan Tahunan PKKS',
                'tujuan_kegiatan'  => 'Demonstrasi unjuk kebolehan akhir tahun, rekapitulasi penilaian, dan evaluasi program 1 tahun',
                'tempat'           => 'Panggung Wisuda / Sekolah',
                'waktu'            => 'Minggu ke-1 & 2',
                'penanggung_jawab' => $eskul->instructor_name ?? 'Pembina Eskul',
                'urutan'           => 12,
            ],
        ];

        foreach ($defaultActivities as $item) {
            EskulProgramItem::create(array_merge($item, [
                'eskul_id'         => $eskul->id,
                'academic_year_id' => $activeYear->id,
            ]));
        }
    }
}
