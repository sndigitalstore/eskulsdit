<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eskul;
use App\Models\AcademicYear;
use App\Exports\GradeTemplateExport;
use App\Imports\GradesImport;
use Maatwebsite\Excel\Facades\Excel;

class GradeImportController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang bisa melakukan import nilai.');
        }

        return view('grades.import');
    }

    public function downloadTemplate(Request $request)
    {
        // Keep this for manual backup if needed, or remove? 
        // User asked for simplicity. Let's keep it but it won't be the main focus.
        // Or just leave it as is, but the view will hide it.
        // I will focus on the import method changes here.
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
        
        // Validation needs inputs. If view removes them, this will fail.
        // Let's assume we might remove this feature or keep it behind a modal.
        // For now, let's leave this method alone and focus on 'import'.
        // Actually, if I remove dropdowns from view, this breaks. 
        // I will treat 'downloadTemplate' as legacy/advanced.
        
        $request->validate([
            'eskul_id' => 'required|exists:eskuls,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $eskul = Eskul::find($request->eskul_id);
        $fileName = 'Format_Nilai_' . str_replace(' ', '_', $eskul->name) . '.xlsx';
        return Excel::download(new GradeTemplateExport($request->eskul_id, $request->academic_year_id), $fileName);
    }

    public function import(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // Increase resources for importing
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $request->validate([
            'file' => 'required|file',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
            return back()->with('error', 'Format file harus berupa Excel (.xlsx, .xls) atau CSV (.csv).');
        }

        try {
            // Smart Import: Detect everything from the file
            Excel::import(new GradesImport(), $file);
            return back()->with('success', 'Smart Import Berhasil! Data nilai telah masuk ke sistem.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
