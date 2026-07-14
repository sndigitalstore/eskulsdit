<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Lengkap Kelas {{ $class }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 10pt; color: #000; }
        /* Reset for Print */
        @media print {
            .no-print { display: none; }
            @page { margin: 1cm; size: A4; }
        }
        
        /* Table Styles from Index */
        table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 9pt; margin-top: 15px; }
        table, th, td { border: 1px solid #333; }
        th { background-color: #f8f9fa; color: #333; font-weight: bold; text-align: center; padding: 4px; }
        td { padding: 4px; vertical-align: middle; line-height: 1.2; }
        
        .no-print { margin: 20px 0; padding: 10px; background: #eee; border: 1px solid #ddd; text-align: right; font-family: sans-serif; }
        .btn { padding: 8px 15px; cursor: pointer; background: #333; color: #fff; border: none; border-radius: 4px; font-size: 10pt; text-decoration: none; display: inline-block;}
        .btn-close { background: #d32f2f; }
        .btn-print { background: #27ae60; }

        /* Stats Box */
        .stats-box {
            background: #f8f9fa; 
            padding: 10px; 
            border: 1px solid #999; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            font-family: Arial, sans-serif; 
            font-size: 9pt;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <span style="float: left; color: #666; font-style: italic;">Laporan Lengkap (Nilai & Absensi)</span>
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak / Simpan PDF</button>
        <button onclick="window.close()" class="btn btn-close">Tutup</button>
    </div>

    <!-- KOP SURAT RESMI -->
    <div class="print-header" style="font-family: 'Times New Roman', serif; margin-bottom: 20px;">
        <table style="border: none; width: 100%; margin-top: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 80px; vertical-align: middle; padding: 0;">
                    <img src="{{ asset('logo.png') }}" style="height: 70px; width: auto;">
                </td>
                <td style="border: none; text-align: left; vertical-align: middle; padding-left: 15px;">
                    <h2 style="margin: 0; font-size: 20pt; font-weight: bold; color: #333; line-height: 1.2;">SDIT AN NADZIR</h2>
                    <p style="margin: 5px 0 0; font-size: 12pt; color: #000; text-transform: uppercase; letter-spacing: 1px; font-weight: bold;">Laporan Ekstrakurikuler</p>
                    <p style="margin: 0; font-size: 11pt; color: #555;">
                        @php
                            $semesterText = 'Semester 1 & 2';
                            if ($period == '1') $semesterText = 'Semester 1';
                            if ($period == '2') $semesterText = 'Semester 2';
                        @endphp
                        {{ $semesterText }} | Tahun Pelajaran {{ $yearName }}
                    </p>
                </td>
                <td style="border: none; text-align: right; vertical-align: middle;">
                    <div style="font-family: sans-serif; font-size: 16pt; font-weight: bold; border: 2px solid #333; padding: 5px 20px; border-radius: 8px; display: inline-block;">
                        KELAS {{ $class }}
                    </div>
                </td>
            </tr>
        </table>
        <div style="border-bottom: 4px solid #3498db; margin-top: 15px; margin-bottom: 2px;"></div>
        <div style="border-bottom: 1px solid #3498db; margin-bottom: 15px;"></div>
    </div>

    <!-- Stats -->
    <div class="stats-box">
        <div style="text-align: left;">
            <strong style="display: block;">Statistik Kelas {{ $class }}</strong>
            <span>Total Siswa: {{ $students->count() }} orang</span>
        </div>
        <div style="text-align: center;">
             @php
                $totalEskuls = \App\Models\Eskul::whereHas('students', function($q) use ($class) {
                    $q->where('class', $class);
                })->count();
            @endphp
            <strong style="display: block;">Jumlah Eskul: {{ $totalEskuls }}</strong>
        </div>
        <div style="text-align: right; max-width: 40%;">
             @php
                $activeEskuls = \App\Models\Eskul::whereHas('students', function($q) use ($class) {
                    $q->where('class', $class);
                })->pluck('name')->take(3);
            @endphp
            <span>Eskul: {{ $activeEskuls->implode(', ') }}{{ $totalEskuls > 3 ? ', dll' : '' }}</span>
        </div>
    </div>

    <!-- Main Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%; text-align: left;">Nama Lengkap</th>
                <th style="width: 15%; text-align: left;">Ekstrakurikuler</th>
                <th style="width: 10%;">Sm 1</th>
                <th style="width: 10%;">Sm 2</th>
                <!-- Attendance Stats -->
                <th style="width: 5%;">H</th>
                <th style="width: 5%;">S</th>
                <th style="width: 5%;">I</th>
                <th style="width: 5%;">A</th>
                
                <th style="width: 15%; text-align: left;">Prestasi & Penghargaan</th>
                <th style="width: 10%; text-align: left;">Pembina / Jadwal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                @php
                    // Merge Current Eskuls + Historical Eskuls from Grades
                    $currentEskuls = $student->eskuls;
                    $historicalEskuls = $student->grades->map(function($grade) {
                        return $grade->eskul;
                    })->filter(); // remove nulls if any grade has no eskul
                    
                    // Combine and Unique by ID
                    $allEskuls = $currentEskuls->merge($historicalEskuls)->unique('id')->sortBy('name');
                @endphp

                @if($allEskuls->isEmpty())
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="white-space: nowrap;">{{ $student->name }}</td>
                    <td style="color: #999;">-</td>
                    <td style="text-align: center;">-</td>
                    <td style="text-align: center;">-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
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
                                // Use the passed yearId from controller
                                ->where('academic_year_id', $yearId)
                                ->selectRaw('status, count(*) as count')
                                ->groupBy('status')
                                ->pluck('count', 'status');
                            
                            $h = $attendanceCounts['present'] ?? 0;
                            $s = $attendanceCounts['sick'] ?? 0;
                            $i_perm = $attendanceCounts['permission'] ?? 0;
                            $a = $attendanceCounts['absent'] ?? 0;

                            // Helper for formatting (Copied logic)
                            // We need to redefine or pass helper. 
                            // Since this is a new file, we define specific helpers here or use raw php
                        @endphp
                        <tr>
                            @if($loop->first)
                                <td rowspan="{{ $allEskuls->count() }}" style="vertical-align: middle; text-align: center;">{{ $index + 1 }}</td>
                                <td rowspan="{{ $allEskuls->count() }}" style="vertical-align: middle;">
                                    <strong>{{ $student->name }}</strong>
                                </td>
                            @endif
                            <td>{{ $eskul->name }}</td>
                            
                            <!-- Grades -->
                            <td style="text-align: center;">
                                @if($grade1)
                                    @if(strlen($grade1->score) > 3) <span style="font-size: 8pt;">{{ $grade1->score }}</span>
                                    @else <b>{{ $grade1->score }}</b> @endif
                                @else - @endif
                            </td>
                            <td style="text-align: center;">
                                @if($grade2)
                                    @if(strlen($grade2->score) > 3) <span style="font-size: 8pt;">{{ $grade2->score }}</span>
                                    @else <b>{{ $grade2->score }}</b> @endif
                                @else - @endif
                            </td>
                            
                            <!-- Attendance -->
                            <td style="text-align: center;">{{ $h }}</td>
                            <td style="text-align: center;">{{ $s }}</td>
                            <td style="text-align: center;">{{ $i_perm }}</td>
                            <td style="text-align: center;">{{ $a }}</td>

                            <td style="vertical-align: top; padding: 4px;">
                                @php
                                    $studentAchievements = \App\Models\Achievement::where('student_id', $student->id)
                                        ->where('academic_year_id', $yearId);
                                    
                                    if ($period != 'all') {
                                        $studentAchievements->where('semester', $period);
                                    }

                                    $achieved = $studentAchievements->orderBy('date', 'desc')->get();
                                @endphp
                                
                                @if($achieved->isEmpty())
                                    <span style="color: #ccc; font-size: 7pt;">-</span>
                                @else
                                    <div style="font-size: 7.5pt; color: #2c3e50; line-height: 1.1;">
                                        @foreach($achieved as $ach)
                                            <div style="margin-bottom: 2px;">
                                                • <b>{{ $ach->name }}</b> 
                                                <span style="color: #7f8c8d;">({{ $ach->level }})</span>
                                                @if($period == 'all')
                                                    <small style="border: 0.5px solid #ccc; padding: 0 2px; border-radius: 2px;">Sm {{ $ach->semester }}</small>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            <td style="font-size: 8pt; color: #555;">
                                @php
                                    // Determine which semester data to show. If 'all', use '2' (latest/active) or logic based on current date.
                                    // Or better: Show both if different? For now, let's follow user request to match the semester context.
                                    // If period is specific (1 or 2), use it. If 'all', use 2 (final report usually reflects end state).
                                    $effSem = ($period == 'all') ? '2' : $period;
                                    $iName = $eskul->getInstructorAt($yearId, $effSem);
                                    $sSched = $eskul->getScheduleAt($yearId, $effSem);
                                @endphp
                                {{ $iName ?? '-' }}
                                @if($sSched)
                                <br><span style="color: #2980b9;">{{ $sSched }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
    
    <!-- Footer -->
    <div style="margin-top: 30px; display: flex; justify-content: flex-end; font-family: Arial, sans-serif;">
        <div style="text-align: center; width: 200px;">
            <p style="margin-bottom: 60px;">Wali Kelas {{ $class }}</p>
            <p>_________________________</p>
        </div>
    </div>
    
    <div style="text-align: right; font-size: 8pt; color: #aaa; margin-top: 20px;">
        Dicetak pada: {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
