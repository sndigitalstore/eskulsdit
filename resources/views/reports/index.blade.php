@extends('layouts.app')

@section('title', 'Laporan Kelas')
@section('page-title', 'Laporan Kelas')

@push('styles')
<style>
    .form-row { display: flex; gap: 20px; align-items: flex-end; }
    .form-group { flex: 1; }
    
    @media print {
        .filter-section, .btn-print { display: none; }
        .card { box-shadow: none; border: none; padding: 0; }
        .content { padding: 0; }
        body { background: white; }
    }
</style>
@endpush

@section('content')
<style>
    @media print {
        @page { 
            size: A4; 
            margin: 0.5cm; 
        }
        body { 
            background: white; 
            margin: 0; 
            padding: 0;
            font-family: 'Times New Roman', serif;
            color: #000;
        }
        .filter-section, .btn-print, .sidebar, .header, .page-header { 
            display: none !important; 
        }
        .main-content { margin: 0; padding: 0; }
        .card { box-shadow: none; border: none; padding: 0; }
        .content { padding: 0; }
        
        .print-header { display: block !important; margin-bottom: 5px; border-bottom: none; }
        .print-stats { display: flex !important; margin-bottom: 5px; padding: 5px !important; }
        .print-footer { display: block !important; margin-top: 5px; }
        
        /* Modern Table Optimized for Print */
        table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 8pt; }
        table, th, td { border: 1px solid #ccc; }
        th { background-color: #f8f9fa !important; color: #333; font-weight: bold; text-align: center; border-bottom: 1px solid #999 !important; padding: 3px 2px; }
        td { padding: 2px 4px; vertical-align: middle; color: #000; line-height: 1.1; }
        
        /* Remove preview box shadows for print */
        .print-preview-box {
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
        }

        .no-print { display: none !important; }
    }

    .print-header, .print-footer { display: none; }
    
    .print-preview-box {
        background: white; 
        width: 210mm; 
        min-height: 297mm; 
        margin: 0 auto; 
        padding: 20mm; 
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        display: none;
    }

    .show-preview .print-preview-box { display: block; }
    .show-preview .filter-section { display: none; }
</style>

<div id="main-container">
    <div class="card filter-section no-print">
        <h3 style="margin-bottom: 1.5rem;">Filter Laporan</h3>
        <form action="{{ route('reports.index') }}" method="GET">
            <div class="form-row">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; color: #888;">Tahun Pelajaran</label>
                    <select name="year_id" class="form-control" required>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" 
                                {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; color: #888;">Periode Laporan</label>
                    <select name="period" class="form-control">
                        <option value="all" {{ $selectedPeriod == 'all' ? 'selected' : '' }}>Satu Tahun (Sm 1 & 2)</option>
                        <option value="1" {{ $selectedPeriod == '1' ? 'selected' : '' }}>Semester 1</option>
                        <option value="2" {{ $selectedPeriod == '2' ? 'selected' : '' }}>Semester 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; color: #888;">Kelas</label>
                    <select name="class" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}" {{ $selectedClass == $class ? 'selected' : '' }}>{{ $class }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="padding-bottom: 1px;">
                    <button type="submit" class="btn-action-header btn-blue" style="height: 48px; width: 100%; justify-content: center;">Tampilkan</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Special Feature: Calistung Export -->
    <div class="card no-print" style="margin-bottom: 20px; border-left: 5px solid #2ecc71;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h4 style="margin-bottom: 5px;"><i class="fas fa-graduation-cap" style="color: #2ecc71;"></i> Lulusan Calistung</h4>
                <p style="font-size: 0.9rem; color: #666; margin: 0;">Unduh data siswa yang mendapatkan nilai A pada Calistung (Membaca/Menulis/Berhitung).</p>
            </div>
            </div>
            <div style="display: flex; gap: 8px;">
                <a href="{{ route('reports.print-calistung-graduates') }}" target="_blank" class="btn-action-header btn-orange">
                    <i class="fas fa-print"></i> Cetak Laporan
                </a>
                <a href="{{ route('reports.calistung-graduates') }}" class="btn-action-header btn-green">
                    <i class="fas fa-file-excel"></i> Download Excel
                </a>
            </div>
        </div>
    </div>

    @if($selectedClass && isset($students))
    
    <!-- Action Buttons -->
    <div class="no-print" style="margin: 20px 0; display: flex; gap: 8px; justify-content: flex-end;">
         <a href="{{ route('reports.print-recap-class', ['class' => $selectedClass, 'year_id' => $selectedYearId, 'period' => $selectedPeriod]) }}" target="_blank" class="btn-action-header btn-green">
            <i class="fas fa-print"></i> Cetak Rekap (Wali Kelas)
        </a>
         <button onclick="togglePreview()" id="btnPreview" class="btn-action-header btn-blue">
            <i class="fas fa-eye"></i> Mode Preview Cetak
        </button>
        <a href="{{ route('reports.print-full', ['class' => $selectedClass, 'year_id' => $selectedYearId, 'period' => $selectedPeriod]) }}" target="_blank" class="btn-action-header btn-dark">
            <i class="fas fa-print"></i> Cetak Laporan (Nilai & Absensi)
        </a>
    </div>

    <!-- Printable Content (Container for A4 preview) -->
    <div id="printableArea" class="card" style="position: relative;">
        <!-- KOP SURAT MODERN (Blue Line Style) -->
        <div class="print-header" style="font-family: 'Times New Roman', serif; margin-bottom: 20px;">
            <table style="border: none; width: 100%;">
                <tr style="border: none;">
                    <td style="border: none; width: 60px; vertical-align: middle;">
                        <img src="{{ asset('logo.png') }}" style="height: 50px; width: auto;">
                    </td>
                    <td style="border: none; text-align: left; vertical-align: middle; padding-left: 15px;">
                        <h2 style="margin: 0; font-size: 16pt; font-weight: bold; color: #333;">SDIT AN NADZIR</h2>
                        <p style="margin: 0; font-size: 10pt; color: #666; text-transform: uppercase; letter-spacing: 1px;">Laporan Ekstrakurikuler</p>
                        @php
                            $semesterText = 'Semester 1 & 2';
                            if ($selectedPeriod == '1') $semesterText = 'Semester 1';
                            if ($selectedPeriod == '2') $semesterText = 'Semester 2';
                        @endphp
                        <p style="margin: 0; font-size: 10pt; color: #888;">Kelas {{ $selectedClass }} &nbsp;|&nbsp; {{ $semesterText }} &nbsp;|&nbsp; Tahun Pelajaran {{ \App\Models\AcademicYear::find($selectedYearId)->name ?? '-' }}</p>
                    </td>
                </tr>
            </table>
            <div style="border-bottom: 3px solid #3498db; margin-top: 10px;"></div>
        </div>

        <!-- Statistics Block (Grey Box) -->
        <div class="print-stats" style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; font-family: sans-serif; font-size: 9pt;">
            <div style="text-align: left;">
                <strong style="display: block; font-size: 10pt; color: #333;">Statistik Kelas {{ $selectedClass }}</strong>
                <span style="color: #666;">Total Siswa: {{ $students->count() }} orang</span>
            </div>
            <div style="text-align: center;">
                 @php
                    $totalEskuls = \App\Models\Eskul::whereHas('students', function($q) use ($selectedClass) {
                        $q->where('class', $selectedClass);
                    })->count();
                @endphp
                <strong style="display: block; color: #333;">Jumlah Eskul: {{ $totalEskuls }}</strong>
            </div>
            <div style="text-align: right; max-width: 40%;">
                 @php
                    $activeEskuls = \App\Models\Eskul::whereHas('students', function($q) use ($selectedClass) {
                        $q->where('class', $selectedClass);
                    })->pluck('name')->take(3);
                @endphp
                <span style="color: #666;">Eskul: {{ $activeEskuls->implode(', ') }}{{ $totalEskuls > 3 ? ', dll' : '' }}</span>
            </div>
        </div>

        <!-- Main Table -->
        <table style="width: 100%; border-collapse: collapse; font-family: sans-serif;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd;">
                    <th style="width: 5%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd;">No</th>
                    <th style="width: 25%; text-align: left; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd;">Nama Lengkap</th>
                    <th style="width: 15%; text-align: left; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd;">Ekstrakurikuler</th>
                    <th style="width: 10%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd; font-size: 9px;">Sm 1</th>
                    <th style="width: 10%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd; font-size: 9px;">Sm 2</th>
                    <!-- Attendance Stats -->
                    <th style="width: 4%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd; font-size: 9px;">H</th>
                    <th style="width: 4%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd; font-size: 9px;">S</th>
                    <th style="width: 4%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd; font-size: 9px;">I</th>
                    <th style="width: 4%; text-align: center; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd; font-size: 9px;">A</th>
                    
                    <th style="width: 19%; text-align: left; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd;">Prestasi & Penghargaan</th>
                    <th style="width: 15%; text-align: left; padding: 8px; background: white !important; border: 1px solid #ddd; border-bottom: 2px solid #ddd;">Info Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                    @php
                        // Merge Current Eskuls + Historical Eskuls from Grades + Attendance
                        $currentEskuls = $student->eskuls;
                        $historicalEskuls = $student->grades->map(function($grade) {
                            return $grade->eskul;
                        })->filter(); // remove nulls if any grade has no eskul
                        
                        $attendanceEskuls = \App\Models\Eskul::whereIn('id', \App\Models\Attendance::where('student_id', $student->id)
                            ->where('academic_year_id', $selectedYearId)
                            ->pluck('eskul_id')
                            ->toArray())->get();
                        
                        // Combine and Unique by ID
                        $allEskuls = $currentEskuls->merge($historicalEskuls)->merge($attendanceEskuls)->unique('id')->sortBy('name');
                    @endphp

                    @if($allEskuls->isEmpty())
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="white-space: nowrap;">{{ $student->name }}</td>
                        <td style="color: #999;">-</td>
                        <td style="text-align: center;">-</td>
                        <td style="text-align: center;">-</td>
                        <td>-</td>
                    </tr>
                    @else
                        @foreach($allEskuls as $i => $eskul)
                            @php
                                $grade1 = $student->grades->where('eskul_id', $eskul->id)->where('type', 'sas1')->first();
                                $grade2 = $student->grades->where('eskul_id', $eskul->id)->where('type', 'sas2')->first();

                                // Calculate Attendance
                                $attendanceCounts = \App\Models\Attendance::where('student_id', $student->id)
                                    ->where('eskul_id', $eskul->id)
                                    ->where('academic_year_id', $selectedYearId)
                                    ->selectRaw('status, count(*) as count')
                                    ->groupBy('status')
                                    ->pluck('count', 'status');
                                
                                $h = $attendanceCounts['present'] ?? 0;
                                $s = $attendanceCounts['sick'] ?? 0;
                                $i_perm = $attendanceCounts['permission'] ?? 0;
                                $a = $attendanceCounts['absent'] ?? 0;

                                // Helper for formatting
                                $formatScore = function($score) use ($eskul) {
                                  if (!$score) return '-';
                                  
                                  // Check if Calistung
                                  if (\Illuminate\Support\Str::contains(strtolower($eskul->name), 'calistung') || \Illuminate\Support\Str::contains(strtolower($eskul->name), 'baca')) {
                                      $data = [];
                                      
                                      // Try JSON first
                                      $json = json_decode($score, true);
                                      if (is_array($json)) {
                                          $data = $json;
                                      } else {
                                          // CUSTOM PARSING LOGIC IF NOT JSON
                                          // 1. Pipe Format "B:A | T:B | H:A"
                                          if (str_contains($score, '|')) {
                                               $parts = explode('|', $score);
                                               foreach ($parts as $part) {
                                                   $sub = explode(':', $part);
                                                   if (count($sub) >= 2) {
                                                       $k = strtoupper(trim($sub[0]));
                                                       $v = trim($sub[1]);
                                                       if ($k === 'B') $data['reading'] = $v;
                                                       if ($k === 'T') $data['writing'] = $v;
                                                       if ($k === 'H') $data['counting'] = $v;
                                                   }
                                               }
                                          }
                                          
                                          // 2. Single Letter Expansion (A, B, C)
                                          elseif (preg_match('/^[A-C]$/i', trim($score))) {
                                              $l = strtoupper(trim($score));
                                              $data = ['reading' => $l, 'writing' => $l, 'counting' => $l];
                                          }
                                          
                                          // 3. Fallback to standard Regex parsing for "Key: Value" string
                                          else {
                                              if (preg_match('/Membaca\s*[:=]\s*(.*?)(?=\s+Menulis|\s+Berhitung|$)/ui', $score, $m)) $data['reading'] = trim($m[1]);
                                              if (preg_match('/Menulis\s*[:=]\s*(.*?)(?=\s+Berhitung|$)/ui', $score, $m)) $data['writing'] = trim($m[1]);
                                              if (preg_match('/Berhitung\s*[:=]\s*(.*?)(?=$)/ui', $score, $m)) $data['counting'] = trim($m[1]);
                                          }
                                          
                                          // Cleanup: If any value is too long or contains other keywords, strip them
                                          foreach(['reading', 'writing', 'counting'] as $key) {
                                              if (isset($data[$key])) {
                                                  // Remove any potential leaked keywords
                                                  $data[$key] = preg_replace('/(Membaca|Menulis|Berhitung|Reading|Writing|Counting).*$/i', '', $data[$key]);
                                                  $data[$key] = trim($data[$key], " \t\n\r\0\x0B:,;");
                                              }
                                          }
                                          
                                          if (empty($data) && str_contains($score, "\n")) {
                                               $lines = explode("\n", $score);
                                               foreach ($lines as $line) {
                                                   $parts = explode(':', $line);
                                                   if (count($parts) < 2) continue;
                                                   $k = strtolower(trim($parts[0]));
                                                   $v = trim($parts[1]);
                                                   if (str_contains($k, 'membaca')) $data['reading'] = $v;
                                                   if (str_contains($k, 'menulis')) $data['writing'] = $v;
                                                   if (str_contains($k, 'berhitung')) $data['counting'] = $v;
                                               }
                                          }
                                          
                                          // Fallback: If still empty but looks like Calistung string "Membaca: A ..."
                                          if (empty($data)) {
                                               // Simple string replacements to force the format if parsing failed
                                               // This handles cases where delimiters might be weird
                                               $cleanScore = $score;
                                               
                                               // Manual extraction attempts for loose formats
                                               if (preg_match('/Membaca\s*[:=]\s*([A-Z0-9]+)/i', $score, $r)) $data['reading'] = $r[1];
                                               if (preg_match('/Menulis\s*[:=]\s*([A-Z0-9]+)/i', $score, $r)) $data['writing'] = $r[1];
                                               if (preg_match('/Berhitung\s*[:=]\s*([A-Z0-9]+)/i', $score, $r)) $data['counting'] = $r[1];
                                               
                                               // If still empty, assume the user JUST wants to display it but with short labels
                                               if (empty($data) && (stripos($score, 'Membaca') !== false)) {
                                                    // Force string replacement for display
                                                    $cleanScore = str_ireplace(['Membaca:', 'Membaca', 'Reading'], 'Baca:', $cleanScore);
                                                    $cleanScore = str_ireplace(['Menulis:', 'Menulis', 'Writing'], 'Tulis:', $cleanScore);
                                                    $cleanScore = str_ireplace(['Berhitung:', 'Berhitung', 'Counting'], 'Hitung:', $cleanScore);
                                                    return "<div style='font-size: 9px; font-weight: bold; white-space: pre-wrap; text-align: left;'>{$cleanScore}</div>";
                                               }
                                          }
                                      }
                                      }

                                      if (!empty($data)) {
                                         // Ultra-Compact Inline Display for A4 Efficiency
                                         // B: A | T: B | H: A
                                         $r = $data['reading'] ?? '-';
                                         $w = $data['writing'] ?? '-';
                                         $c = $data['counting'] ?? '-';
                                         
                                         return "
                                            <div style='font-size: 8px; font-family: Arial, sans-serif; line-height: 1.1; white-space: nowrap;'>
                                                <span style='color:#666;'>B:</span><b>{$r}</b> <span style='color:#ccc;'>|</span>
                                                <span style='color:#666;'>T:</span><b>{$w}</b> <span style='color:#ccc;'>|</span>
                                                <span style='color:#666;'>H:</span><b>{$c}</b>
                                            </div>
                                         ";
                                      }
                                  return "<div style='font-size: 11px; font-weight: bold;'>{$score}</div>";
                                };
                            @endphp
                            <tr>
                                @if($loop->first)
                                    <td rowspan="{{ $allEskuls->count() }}" style="vertical-align: middle; text-align: center;">{{ $index + 1 }}</td>
                                    <td rowspan="{{ $allEskuls->count() }}" style="vertical-align: middle; white-space: nowrap;">
                                        <strong>{{ $student->name }}</strong>
                                    </td>
                                @endif
                                <td>{{ $eskul->name }}</td>
                                <!-- Use padding 2px to accommodate inner table -->
                                <td style="text-align: center; padding: 2px;">{!! $formatScore($grade1->score ?? null) !!}</td>
                                <td style="text-align: center; padding: 2px;">{!! $formatScore($grade2->score ?? null) !!}</td>
                                
                                <td style="text-align: center; background: #fafafa;">{{ $h }}</td>
                                <td style="text-align: center; background: #fafafa;">{{ $s }}</td>
                                <td style="text-align: center; background: #fafafa;">{{ $i_perm }}</td>
                                <td style="text-align: center; background: #fafafa;">{{ $a }}</td>

                                <td style="vertical-align: top; padding: 5px;">
                                    @php
                                        $studentAchievements = \App\Models\Achievement::where('student_id', $student->id)
                                            ->where('academic_year_id', $selectedYearId);
                                        
                                        if ($selectedPeriod != 'all') {
                                            $studentAchievements->where('semester', $selectedPeriod);
                                        }

                                        $achieved = $studentAchievements->orderBy('date', 'desc')->get();
                                    @endphp
                                    
                                    @if($achieved->isEmpty())
                                        <span style="color: #ccc; font-size: 8pt;">-</span>
                                    @else
                                        <ul style="margin: 0; padding-left: 15px; font-size: 8pt; color: #2c3e50;">
                                            @foreach($achieved as $ach)
                                                <li style="margin-bottom: 3px;">
                                                    <strong>{{ $ach->name }}</strong> 
                                                    <span style="color: #7f8c8d;">({{ $ach->level }})</span>
                                                    @if($selectedPeriod == 'all')
                                                        <span style="background: #f1c40f; color: #fff; padding: 0 4px; border-radius: 3px; font-size: 7pt;">Sm {{ $ach->semester }}</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>

                                <td style="font-size: 8pt; color: #555;">
                                    {{ $eskul->instructor_name ?? '-' }}
                                    @if($eskul->schedule)
                                    <br><span style="color: #2980b9; font-size: 7.5pt;">{{ $eskul->schedule }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
        
        <!-- Minimal Footer -->
         <div class="print-footer" style="margin-top: 10px; font-family: sans-serif; font-size: 8pt; color: #888; text-align: right;">
            Dicetak pada: {{ date('d/m/Y H:i') }}
        </div>
    </div>
    @elseif(request('class'))
         <div class="card" style="text-align: center; color: #999; padding: 3rem;">
            <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; color: #eee;"></i>
            <p>Silakan pilih filter untuk menampilkan laporan.</p>
        </div>
    @endif
</div>

<script>
    function togglePreview() {
        var container = document.getElementById('printableArea');
        var btn = document.getElementById('btnPreview');
        
        if (container.classList.contains('print-preview-box')) {
            // Disable Preview
            container.classList.remove('print-preview-box');
            document.body.classList.remove('show-preview');
            btn.innerHTML = '<i class="fas fa-eye"></i> Mode Preview Cetak';
            container.style.border = '1px solid rgba(0,0,0,0.02)'; // Reset to standard card style
            
            // Hide Print elements in normal view
            document.querySelector('.print-header').style.display = 'none';
            document.querySelector('.print-footer').style.display = 'none';
        } else {
            // Enable Preview (A4 Paper Look)
            container.classList.add('print-preview-box');
            document.body.classList.add('show-preview');
            btn.innerHTML = '<i class="fas fa-times"></i> Tutup Preview';
            
            // Show Print elements in preview
            document.querySelector('.print-header').style.display = 'block';
            document.querySelector('.print-footer').style.display = 'block';
        }
    }
</script>
@endsection
