<?php

namespace App\Http\Controllers;

use App\Models\Eskul;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get all unique classes for the filter dropdown
        $classes = Student::select('class')->distinct()->orderBy('class')->pluck('class');
        
        // Get active academic year
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        // Get all academic years for dropdown
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();
        
        $selectedClass = $request->class;
        $selectedPeriod = $request->period ?? 'all'; // 1, 2, or all
        $selectedYearId = $request->year_id ?? ($activeYear ? $activeYear->id : null); 
        
        $students = [];
        
        if ($selectedClass && $selectedYearId) {
            $students = Student::where('class', $selectedClass)
                ->with([
                    // Load current active enrollments
                    'eskuls' => function($query) use ($selectedYearId) {
                        $query->wherePivot('academic_year_id', $selectedYearId);
                    }, 
                    // Load all grades for this year (with Eskul info) - to catch history
                    'grades' => function($query) use ($selectedYearId) {
                        $query->where('academic_year_id', $selectedYearId)
                              ->whereIn('type', ['sas1', 'sas2'])
                              ->with('eskul'); 
                    }
                ])
                ->get();
        }

        return view('reports.index', compact('classes', 'students', 'selectedClass', 'selectedPeriod', 'selectedYearId', 'academicYears', 'activeYear'));
    }
    public function exportCalistung()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CalistungGraduatesExport, 'Siswa_Lulus_Calistung_' . date('Ymd_His') . '.xlsx');
    }

    public function exportClass(Request $request)
    {
        $class = $request->query('class');
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearId = $request->query('year_id') ?? ($activeYear ? $activeYear->id : null);
        $period = $request->query('period') ?? 'all';

        if (!$class || !$yearId) {
            return back()->with('error', 'Kelas dan Tahun Ajaran harus dipilih.');
        }

        // Simplest filename to avoid header issues
        $filename = 'Laporan_Eskul_' . preg_replace('/[^A-Za-z0-9]/', '', $class) . '.xlsx';
        
        try {
            // Store to disk 'public'
            \Maatwebsite\Excel\Facades\Excel::store(new \App\Exports\ClassEskulExport($class, $yearId, $period), $filename, 'public');
            
            // Redirect to static file using built-in PHP server compatible URL
            // Since user uses 'php artisan serve', asset() points to localhost:8000
            
            return redirect('storage/' . $filename);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat file Excel: ' . $e->getMessage());
        }
    }

    public function printRecapClass(Request $request)
    {
        $class = $request->query('class');
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearId = $request->query('year_id') ?? ($activeYear ? $activeYear->id : null);
        $period = $request->query('period') ?? 'all';

        if (!$class || !$yearId) {
            return back()->with('error', 'Kelas dan Tahun Ajaran harus dipilih.');
        }

        $students = \App\Models\Student::where('class', $class)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')
                  ->orWhereNull('status');
            })
            ->with(['eskuls' => function($q) use ($yearId, $period) {
                $q->wherePivot('academic_year_id', $yearId);
                if ($period != 'all') {
                    $q->wherePivot('semester', $period);
                }
            }])
            ->orderBy('name')
            ->get();
            
        $yearName = $activeYear ? $activeYear->name : '-';
        if ($yearId && $activeYear->id != $yearId) {
             $y = \App\Models\AcademicYear::find($yearId);
             if($y) $yearName = $y->name;
        }

        return view('reports.print_recap', compact('students', 'class', 'yearName'));
    }

    public function printFullClass(Request $request)
    {
        $class = $request->query('class');
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearId = $request->query('year_id') ?? ($activeYear ? $activeYear->id : null);
        $period = $request->query('period') ?? 'all';

        if (!$class || !$yearId) {
            return back()->with('error', 'Kelas dan Tahun Ajaran harus dipilih.');
        }

        $students = \App\Models\Student::where('class', $class)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')
                  ->orWhereNull('status');
            })
            // Similar eager loading logic
            ->with(['eskuls' => function($q) use ($yearId, $period) {
                $q->wherePivot('academic_year_id', $yearId);
                if ($period != 'all') {
                    $q->wherePivot('semester', $period);
                }
            }])
            ->orderBy('name')
            ->get();
            
        $yearName = $activeYear ? $activeYear->name : '-';
        if ($yearId && $activeYear->id != $yearId) {
             $y = \App\Models\AcademicYear::find($yearId);
             if($y) $yearName = $y->name;
        }

        // Pass yearId to view for attendance calculation query
        return view('reports.print_full', compact('students', 'class', 'yearName', 'yearId', 'period'));
    }

    public function printCalistungGraduates()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $yearName = $activeYear ? $activeYear->name : '-';
        if (!$activeYear) return back()->with('error', 'Tidak ada tahun ajaran aktif.');

        // Logic from Export class to find graduates
        $calistungEskuls = \App\Models\Eskul::where('name', 'like', '%Calistung%')->pluck('id');

        $graduates = [];
        if ($calistungEskuls->isNotEmpty()) {
            $grades = \App\Models\Grade::with(['student', 'eskul'])
                ->whereIn('eskul_id', $calistungEskuls)
                ->where('academic_year_id', $activeYear->id)
                ->get();

            foreach ($grades as $grade) {
                $studentId = $grade->student_id;
                $scoreStr = $grade->score;
                
                $score = json_decode($scoreStr, true);
                $parsed = [];

                if (is_array($score)) {
                    $parsed = $score;
                } else {
                     if (preg_match('/^[A]$/i', trim($scoreStr))) {
                         $parsed = ['reading' => 'A', 'writing' => 'A', 'counting' => 'A'];
                     }
                     elseif (str_contains($scoreStr, '|')) {
                         $parts = explode('|', $scoreStr);
                         foreach ($parts as $part) {
                             $sub = explode(':', $part);
                             if (count($sub) >= 2) {
                                 $k = strtoupper(trim($sub[0]));
                                 $v = trim($sub[1]);
                                 if ($k == 'B') $parsed['reading'] = $v;
                                 if ($k == 'T') $parsed['writing'] = $v;
                                 if ($k == 'H') $parsed['counting'] = $v;
                             }
                         }
                     }
                     else {
                          if (preg_match('/Membaca\s*[:=]\s*(.*?)(?=\s+Menulis|\s+Berhitung|$)/ui', $scoreStr, $m)) $parsed['reading'] = trim($m[1]);
                          if (preg_match('/Menulis\s*[:=]\s*(.*?)(?=\s+Berhitung|$)/ui', $scoreStr, $m)) $parsed['writing'] = trim($m[1]);
                          if (preg_match('/Berhitung\s*[:=]\s*(.*?)(?=$)/ui', $scoreStr, $m)) $parsed['counting'] = trim($m[1]);
                     }
                }
                
                $achievements = [];
                if (isset($parsed['reading']) && strtoupper(trim($parsed['reading'])) === 'A') $achievements[] = 'Membaca';
                if (isset($parsed['writing']) && strtoupper(trim($parsed['writing'])) === 'A') $achievements[] = 'Menulis';
                if (isset($parsed['counting']) && strtoupper(trim($parsed['counting'])) === 'A') $achievements[] = 'Berhitung';

                if (!empty($achievements)) {
                    if (!isset($graduates[$studentId])) {
                         $graduates[$studentId] = [
                            'name' => $grade->student->name ?? 'Unknown',
                            'class' => $grade->student->class ?? '-',
                            'eskul' => $grade->eskul->name ?? 'Calistung',
                            'achievements' => [],
                         ];
                    }
                    $graduates[$studentId]['achievements'] = array_unique(array_merge($graduates[$studentId]['achievements'], $achievements));
                }
            }
        }
        
        // Sort by Name
        $graduates = collect(array_values($graduates))->sortBy('name');

        return view('reports.print_calistung', compact('graduates', 'yearName'));
    }
}
