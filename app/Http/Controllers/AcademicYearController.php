<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $years = AcademicYear::orderBy('name', 'desc')->get();
        return view('academic_years.index', compact('years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:academic_years,name',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'active_semester' => 'nullable|in:1,2',
        ]);

        $data = $request->except('is_active');
        $data['is_active'] = AcademicYear::count() === 0; // Only first year is active by default

        AcademicYear::create($data);

        return back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
             'name' => 'required|unique:academic_years,name,' . $academicYear->id,
             'start_date' => 'nullable|date',
             'end_date' => 'nullable|date',
             'active_semester' => 'nullable|in:1,2',
        ]);
        
        // Prevent accidental is_active update via this method
        $academicYear->update($request->except('is_active'));
        return back()->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function activate(AcademicYear $academicYear)
    {
        // Deactivate all
        AcademicYear::query()->update(['is_active' => false]);
        
        // Activate selected
        $academicYear->update(['is_active' => true]);
        
        return back()->with('success', 'Tahun ajaran aktif berhasil diubah ke ' . $academicYear->name);
    }

    public function copySemesterData(AcademicYear $academicYear)
    {
        // Only allow if current active semester is 2
        if ($academicYear->active_semester != '2') {
            return back()->with('error', 'Fitur ini hanya dapat digunakan saat Semester 2 aktif.');
        }

        $semester1Data = \Illuminate\Support\Facades\DB::table('student_eskul')
            ->where('academic_year_id', $academicYear->id)
            ->where('semester', '1')
            ->get();

        if ($semester1Data->isEmpty()) {
            return back()->with('warning', 'Tidak ada data eskul di Semester 1 yang bisa disalin.');
        }

        $count = 0;
        foreach ($semester1Data as $data) {
            // Check if student already has eskul in Semester 2 to avoid duplicates/overwrite
            $exists = \Illuminate\Support\Facades\DB::table('student_eskul')
                ->where('student_id', $data->student_id)
                ->where('academic_year_id', $academicYear->id)
                ->where('semester', '2')
                ->exists();

            if (!$exists) {
                \Illuminate\Support\Facades\DB::table('student_eskul')->insert([
                    'student_id' => $data->student_id,
                    'eskul_id' => $data->eskul_id,
                    'academic_year_id' => $academicYear->id,
                    'semester' => '2',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $count++;
            }
        }

        \App\Models\ActivityLog::log('System', 'Update', "Menyalin data eskul {$count} siswa dari Semester 1 ke Semester 2.");

        return back()->with('success', "Berhasil menyalin data eskul {$count} siswa ke Semester 2. Siswa yang ingin pindah eskul sekarang dapat mengisi formulir pendaftaran ulang.");
    }

    public function destroy(AcademicYear $academicYear)
    {
        if ($academicYear->is_active) {
            return back()->with('error', 'Tidak bisa menghapus tahun ajaran yang sedang aktif.');
        }

        \Illuminate\Support\Facades\DB::transaction(function() use ($academicYear) {
            // Delete all related records first to ensure referential integrity
            \App\Models\Student::where('academic_year_id', $academicYear->id)->delete();
            \Illuminate\Support\Facades\DB::table('student_eskul')->where('academic_year_id', $academicYear->id)->delete();
            \App\Models\Attendance::where('academic_year_id', $academicYear->id)->delete();
            \App\Models\Grade::where('academic_year_id', $academicYear->id)->delete();
            \App\Models\Achievement::where('academic_year_id', $academicYear->id)->delete();
            \App\Models\EskulHistory::where('academic_year_id', $academicYear->id)->delete();
            \App\Models\TeacherAttendance::where('academic_year_id', $academicYear->id)->delete();
            
            $academicYear->delete();
        });

        return back()->with('success', 'Tahun ajaran beserta seluruh data terkait berhasil dihapus.');
    }
}
