<?php

namespace App\Http\Controllers;

use App\Models\Eskul;
use Illuminate\Http\Request;

class EskulController extends Controller
{
    public function index(Request $request)
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearId = $activeYear ? $activeYear->id : null;
        $semester = $activeYear ? $activeYear->active_semester : '1';

        $user = auth()->user();
        $isTeacher = $user->role == 'teacher';
        $teacherEskulId = $isTeacher ? $user->eskul_id : null;

        $query = Eskul::query();
        if ($isTeacher) {
            $query->where('id', $teacherEskulId);
        }

        // Fetch eskuls with context-specific students and history (instructor/schedule)
        $eskuls = $query->with([
            'students' => function($q) use ($yearId, $semester) {
                if ($yearId) {
                    $q->where('student_eskul.academic_year_id', $yearId)
                      ->where('student_eskul.semester', $semester);
                }
                $q->where('status', '!=', 'graduated');
            },
            'histories' => function($q) use ($yearId, $semester) {
                if ($yearId) {
                    $q->where('academic_year_id', $yearId)
                      ->where('semester', $semester);
                }
            }
        ])->get();

        $selectedEskul = null;
        $students = [];

        return view('eskuls.index', compact('eskuls', 'selectedEskul', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alias_name' => 'nullable|string|max:255',
            'instructor_name' => 'nullable|string|max:255',
            'schedule' => 'nullable|string|max:255',
        ]);

        $eskul = Eskul::create([
            'name' => $validated['name'],
            'instructor_name' => $validated['instructor_name'],
            'schedule' => $validated['schedule']
        ]);

        // Create Initial History for Active Semester
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if ($activeYear) {
            \App\Models\EskulHistory::create([
                'eskul_id' => $eskul->id,
                'academic_year_id' => $activeYear->id,
                'semester' => $activeYear->active_semester,
                'alias_name' => $validated['alias_name'] ?? $eskul->name,
                'instructor_name' => $eskul->instructor_name,
                'schedule' => $eskul->schedule
            ]);
        }

        return back()->with('success', 'Ekstrakurikuler berhasil ditambahkan!');
    }

    public function update(Request $request, Eskul $eskul)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alias_name' => 'nullable|string|max:255',
            'instructor_name' => 'nullable|string|max:255',
            'schedule' => 'nullable|string|max:255',
            'is_lockable' => 'boolean',
        ]);

        $eskul->name = $validated['name'];
        $eskul->instructor_name = $validated['instructor_name'];
        $eskul->schedule = $validated['schedule'];
        $eskul->is_lockable = $request->has('is_lockable'); 
        $eskul->save();

        // Update/Create History for Active Semester
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if ($activeYear) {
            \App\Models\EskulHistory::updateOrCreate(
                [
                    'eskul_id' => $eskul->id,
                    'academic_year_id' => $activeYear->id,
                    'semester' => $activeYear->active_semester
                ],
                [
                    'alias_name' => $validated['alias_name'] ?? $eskul->name,
                    'instructor_name' => $validated['instructor_name'],
                    'schedule' => $validated['schedule']
                ]
            );
        }

        return back()->with('success', 'Data ekstrakurikuler berhasil diperbarui!');
    }

    public function bulkUpdateSchedule(Request $request)
    {
        $validated = $request->validate([
            'schedule' => 'required|string|max:255',
        ]);

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        // Update ONLY eskuls that have active students in the active academic year
        Eskul::whereHas('students', function($q) use ($activeYear) {
            $q->where('student_eskul.academic_year_id', $activeYear->id)
              ->where('student_eskul.semester', $activeYear->active_semester)
              ->where('status', '!=', 'graduated');
        })->update(['schedule' => $validated['schedule']]);

        return back()->with('success', 'Jadwal ekskul yang AKTIF berhasil diperbarui!');
    }

    public function export(Eskul $eskul)
    {
        // Contextualize export to active year and active students
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearId = $activeYear ? $activeYear->id : null;
        $semester = $activeYear ? $activeYear->active_semester : '1';
        
        $eskul->load(['students' => function($q) use ($yearId, $semester) {
             if ($yearId) {
                 $q->wherePivot('academic_year_id', $yearId)
                   ->wherePivot('semester', $semester);
             }
             $q->where('status', '!=', 'graduated');
        }]);

        $filename = 'Data_Eskul_' . str_replace(' ', '_', $eskul->name) . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\EskulExport($eskul), $filename);
    }

    public function exportAll()
    {
        $filename = 'Data_Semua_Eskul_' . date('Y-m-d') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearId = $activeYear ? $activeYear->id : null;
        $semester = $activeYear ? $activeYear->active_semester : '1';

        $eskuls = Eskul::with(['students' => function($q) use ($yearId, $semester) {
             if ($yearId) {
                 $q->wherePivot('academic_year_id', $yearId)
                   ->wherePivot('semester', $semester);
             }
             $q->where('status', '!=', 'graduated');
        }])->get();

        $callback = function () use ($eskuls) {
            echo "<html>";
            echo "<head>";
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
            echo "<style>
                    table { border-collapse: collapse; width: 100%; }
                    th { background-color: #ffc000; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; height: 30px; }
                    td { border: 1px solid #000000; padding: 5px; vertical-align: middle; }
                    .text-center { text-align: center; }
                  </style>";
            echo "</head>";
            echo "<body>";
            
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th style='width: 50px;'>No</th>";
            echo "<th style='width: 250px;'>Nama Siswa</th>";
            echo "<th style='width: 100px;'>Kelas</th>";
            echo "<th style='width: 200px;'>Ekstrakurikuler</th>";
            echo "<th style='width: 200px;'>Pembina</th>";
            echo "<th style='width: 150px;'>Jadwal</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $no = 1;
            foreach ($eskuls as $eskul) {
                if ($eskul->students->isEmpty()) {
                    continue; 
                }
                foreach ($eskul->students as $student) {
                    echo "<tr>";
                    echo "<td class='text-center'>" . $no++ . "</td>";
                    echo "<td>" . $student->name . "</td>";
                    echo "<td class='text-center'>" . $student->class . "</td>";
                    echo "<td>" . $eskul->name . "</td>";
                    echo "<td>" . ($eskul->instructor_name ?? '-') . "</td>";
                    echo "<td>" . ($eskul->schedule ?? '-') . "</td>";
                    echo "</tr>";
                }
            }

            echo "</tbody>";
            echo "</table>";
            echo "</body>";
            echo "</html>";
        };

        return response()->stream($callback, 200, $headers);

    }

    public function destroy(Eskul $eskul)
    {
        $eskul->delete();
        return back()->with('success', 'Ekstrakurikuler berhasil dihapus!');
    }
}
