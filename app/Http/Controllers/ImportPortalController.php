<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Exports\ImportTemplateExport;
use App\Models\ActivityLog;

class ImportPortalController extends Controller
{
    /**
     * Tampilkan halaman portal import satu pintu.
     */
    public function index()
    {
        // Ambil 10 log aktivitas terbaru yang berkaitan dengan impor
        $recentLogs = ActivityLog::where('description', 'like', '%import%')
            ->orWhere('description', 'like', '%Import%')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('import_portal.index', compact('recentLogs'));
    }

    /**
     * Unduh template Excel multi-sheet.
     */
    public function downloadTemplate()
    {
        return Excel::download(new ImportTemplateExport(), 'template-import-eskul.xlsx');
    }

    /**
     * Proses upload & import file Excel mega.
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240',
        ], [
            'excel_file.required' => 'File Excel wajib diunggah.',
            'excel_file.mimes'    => 'Format file harus .xlsx atau .xls.',
            'excel_file.max'      => 'Ukuran file maksimal 10 MB.',
        ]);

        try {
            // Naikkan batas memori untuk file besar
            ini_set('memory_limit', '512M');
            set_time_limit(300);

            $sheets = Excel::toArray(new \stdClass(), $request->file('excel_file'));
            $counts = StudentsImport::processArrays($sheets);

            $summary = implode(', ', array_filter([
                $counts['students']          ? "{$counts['students']} siswa"           : null,
                $counts['attendance']        ? "{$counts['attendance']} absensi siswa" : null,
                $counts['grades']            ? "{$counts['grades']} nilai"             : null,
                $counts['achievements']      ? "{$counts['achievements']} prestasi"    : null,
                $counts['teacher_attendance']? "{$counts['teacher_attendance']} absensi guru" : null,
                ($counts['teachers'] ?? 0)   ? "{$counts['teachers']} data guru"       : null,
            ]));

            if (!$summary) {
                $summary = 'Tidak ada data baru yang diproses (mungkin sudah ada atau format tidak dikenali).';
            }

            // Catat log aktivitas
            ActivityLog::create([
                'user_id'     => auth()->id(),
                'description' => "Import Excel Satu Pintu: {$summary}",
                'ip_address'  => $request->ip(),
            ]);

            return redirect()->route('import-portal.index')
                ->with('success', "✅ Import berhasil! Data yang diproses: {$summary}.");

        } catch (\Exception $e) {
            Log::error('ImportPortal error: ' . $e->getMessage());
            return redirect()->route('import-portal.index')
                ->with('error', '❌ Terjadi kesalahan saat mengimpor: ' . $e->getMessage());
        }
    }
}
