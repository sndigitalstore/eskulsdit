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
     * Halaman Program Kerja: form isi & cetak
     */
    public function programIndex(Eskul $eskul)
    {
        $this->authorizeEskul($eskul);

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $semester = $activeYear->active_semester;

        $program = EskulProgram::firstOrCreate(
            ['eskul_id' => $eskul->id, 'academic_year_id' => $activeYear->id, 'semester' => $semester],
            [
                'visi'    => '',
                'misi'    => '',
                'tujuan'  => '',
                'sasaran' => '',
            ]
        );

        $items = EskulProgramItem::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester', $semester)
            ->orderBy('urutan')
            ->get();

        $setting = Setting::first();

        return view('eskuls.program', compact('eskul', 'activeYear', 'semester', 'program', 'items', 'setting'));
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

        EskulProgram::updateOrCreate(
            ['eskul_id' => $eskul->id, 'academic_year_id' => $activeYear->id, 'semester' => $semester],
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
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $semester   = $activeYear->active_semester;

        $maxUrutan = EskulProgramItem::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester', $semester)
            ->max('urutan') ?? 0;

        EskulProgramItem::create(array_merge($validated, [
            'eskul_id'        => $eskul->id,
            'academic_year_id'=> $activeYear->id,
            'semester'        => $semester,
            'urutan'          => $maxUrutan + 1,
        ]));

        return back()->with('success', 'Kegiatan berhasil ditambahkan!');
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
     * Halaman cetak Program Kerja (PDF/Print)
     */
    public function programPrint(Eskul $eskul)
    {
        $this->authorizeEskul($eskul);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $semester   = $activeYear->active_semester;

        $program = EskulProgram::where([
            'eskul_id'        => $eskul->id,
            'academic_year_id'=> $activeYear->id,
            'semester'        => $semester,
        ])->first();

        $items = EskulProgramItem::where('eskul_id', $eskul->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester', $semester)
            ->orderBy('urutan')
            ->get();

        $eskul->load(['students' => function($q) use ($activeYear, $semester) {
            $q->wherePivot('academic_year_id', $activeYear->id)
              ->wherePivot('semester', $semester)
              ->where('status', '!=', 'graduated');
        }]);

        $setting = Setting::first();

        return view('eskuls.program_print', compact('eskul', 'activeYear', 'semester', 'program', 'items', 'setting'));
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

        $setting = Setting::first();

        return view('eskuls.jurnal', compact('eskul', 'activeYear', 'semester', 'journals', 'setting'));
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
     * Cetak Jurnal Kegiatan
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

        $setting = Setting::first();

        return view('eskuls.jurnal_print', compact('eskul', 'activeYear', 'semester', 'journals', 'setting'));
    }

    /* ========================
     *  HELPER
     * ======================== */
    private function authorizeEskul(Eskul $eskul)
    {
        $user = auth()->user();
        if ($user->role === 'teacher' && $user->eskul_id != $eskul->id) {
            abort(403, 'Akses ditolak.');
        }
    }
}
