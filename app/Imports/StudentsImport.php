<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentsImport
{
    /**
     * Process the array data from Excel::toArray()
     */
    public static function processArrays($sheets)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            throw new \Exception('Tidak ada tahun ajaran aktif.');
        }

        // Cache existing students to minimize DB queries
        $existingStudents = Student::all();
        $mapNis = [];
        $mapNameClass = [];
        
        foreach ($existingStudents as $stu) {
            if ($stu->nis) {
                $mapNis[$stu->nis] = $stu;
            }
            $key = strtoupper(trim($stu->name)) . '|' . strtoupper(trim($stu->class));
            $mapNameClass[$key] = $stu;
        }

        $counts = [
            'students' => 0,
            'attendance' => 0,
            'grades' => 0,
            'achievements' => 0,
            'teacher_attendance' => 0
        ];

        foreach ($sheets as $sheetIndex => $rows) {
            $class = null;
            $headerRowIndex = -1;
            
            // 1. Scan first few rows for "Kelas" and Header signature
            foreach ($rows as $index => $row) {
                if ($index > 20) break;
                
                $rowString = implode(' ', array_map(function($item) { return $item ?? ''; }, $row));
                
                // Improved Class Detection
                // Matches "Kelas I A", "Kelas : IA", "Kelas 1A"
                if (!$class && preg_match('/Kelas\s*:?\s*([VI]+[A-Z]?|[0-9]+[A-Z]?)/i', $rowString, $matches)) {
                    $rawClass = strtoupper($matches[1]); 
                    $class = self::normalizeClass($rawClass);
                }

                // Header Row Detection
                if ($headerRowIndex === -1) {
                    $foundNis = false;
                    $foundName = false;
                    $foundTeacherName = false;
                    $foundSecondary = false;

                    foreach ($row as $cell) {
                        if (!$cell) continue;
                        $cellUpper = strtoupper(trim($cell));
                        
                        // Checks
                        if ($cellUpper === 'NIS' || $cellUpper === 'NO INDUK') $foundNis = true;
                        if (Str::contains($cellUpper, 'NAMA LENGKAP') || $cellUpper === 'NAMA SISWA' || $cellUpper === 'NAMA') $foundName = true;
                        if ($cellUpper === 'NAMA GURU') $foundTeacherName = true;

                        // Secondary Markers to confirm header row if NIS is missing
                        if ($cellUpper === 'KELAS' || str_contains($cellUpper, 'EKSTRAKURIKULER') || $cellUpper === 'TANGGAL' || $cellUpper === 'STATUS') {
                            $foundSecondary = true;
                        }
                    }
                    
                    // Accept header if:
                    // 1. NIS and Name found (Standard)
                    // 2. Name and Secondary found (Robust fallback for sheets lacking NIS but having structure)
                    // 3. Teacher Name found (Teacher Attendance)
                    if (($foundNis && $foundName) || ($foundName && $foundSecondary) || $foundTeacherName) {
                        $headerRowIndex = $index;
                        break; 
                    }
                }
            }
            
            if ($headerRowIndex === -1) continue; 
            
            // 2. Process Data Rows
            $headers = array_map(function($h) { return strtolower(trim($h ?? '')); }, $rows[$headerRowIndex]);

            // --- TEACHER ATTENDANCE PROCESSING ---
            $isTeacherSheet = false;
            foreach ($headers as $h) {
                if ($h === 'nama guru') $isTeacherSheet = true;
            }

            if ($isTeacherSheet) {
                $colName = -1; $colDate = -1; $colTime = -1; $colStatus = -1; $colNote = -1;
                foreach ($headers as $k => $h) {
                    if ($h === 'nama guru') $colName = $k;
                    if ($h === 'tanggal') $colDate = $k;
                    if ($h === 'waktu' || str_contains($h, 'waktu')) $colTime = $k;
                    if ($h === 'status') $colStatus = $k;
                    if ($h === 'catatan') $colNote = $k;
                }

                if ($colName !== -1 && $colDate !== -1) {
                    for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
                        $row = $rows[$i];
                        if (!isset($row[$colName])) continue;
                        $name = trim($row[$colName]);
                        if (!$name || $name === '-') continue;

                        // Find Teacher User
                        $user = \App\Models\User::where('role', 'teacher')
                            ->where('name', 'LIKE', $name) 
                            ->first();
                        
                        // Fallback: Try matching username or partial name
                        if (!$user) {
                             $user = \App\Models\User::where('role', 'teacher')->where('name', 'LIKE', "%{$name}%")->first();
                        }

                        if ($user) {
                             $dateRaw = isset($row[$colDate]) ? trim($row[$colDate]) : null;
                             $time = ($colTime !== -1 && isset($row[$colTime])) ? trim($row[$colTime]) : null;
                             $statusRaw = ($colStatus !== -1 && isset($row[$colStatus])) ? strtolower(trim($row[$colStatus])) : 'present';
                             $note = ($colNote !== -1 && isset($row[$colNote])) ? trim($row[$colNote]) : null;

                             $date = null;
                             try {
                                if (is_numeric($dateRaw)) {
                                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateRaw)->format('Y-m-d');
                                } else {
                                    $date = date('Y-m-d', strtotime($dateRaw));
                                }
                            } catch (\Exception $e) {}

                            if ($date) {
                                // Map Status
                                $status = 'present';
                                if (str_contains($statusRaw, 'sakit')) $status = 'sick';
                                elseif (str_contains($statusRaw, 'izin')) $status = 'permission';
                                elseif (str_contains($statusRaw, 'alpa') || str_contains($statusRaw, 'alpha')) $status = 'absent';
                                
                                \App\Models\TeacherAttendance::updateOrCreate(
                                    [
                                        'user_id' => $user->id,
                                        'date' => $date,
                                    ],
                                    [
                                        'academic_year_id' => $activeYear->id,
                                        'status' => $status,
                                        'clock_in_time' => $time,
                                        'note' => $note
                                    ]
                                );
                                $counts['teacher_attendance']++;
                            }
                        }
                    }
                }
                // Done with Teacher Sheet, move to next sheet
                continue;
            }
            
            // --- STUDENT PROCESSING (Standard) ---
            
            // Check for per-row Class column
            $colClass = -1;
            foreach ($headers as $k => $h) {
                if ($h === 'kelas') $colClass = $k;
            }

            // If no global class detected, strictly require a Class column
            // if (!$class && $colClass === -1) continue; // REMOVED strict check here, verified per row later


            // ... Column detection ...
            $colNis = -1;
            // ... (keep existing NIS/Name detection) ...
            foreach ($headers as $k => $h) {
                 // ... existing logic ...
                 if ($h === 'nis' || $h === 'no induk') $colNis = $k;
                 elseif ($colNis === -1 && str_contains($h, 'nis') && !str_contains($h, 'nisn')) $colNis = $k;
            }
            // Re-implement Name detection for context
            $colName = -1;
            $bestNameScore = 0;
            foreach ($headers as $k => $h) {
                if ($h === '') continue;
                if (str_contains($h, 'orang tua') || str_contains($h, 'ayah') || str_contains($h, 'ibu') || str_contains($h, 'wife') || str_contains($h, 'husband') || str_contains($h, 'wali')) continue;
                
                $score = 0;
                if ($h === 'nama lengkap siswa') $score = 5;
                elseif (str_contains($h, 'nama lengkap siswa')) $score = 4;
                elseif ($h === 'nama siswa') $score = 4;
                elseif (str_contains($h, 'nama siswa')) $score = 3;
                elseif ($h === 'nama') $score = 2; 
                elseif (str_contains($h, 'nama')) $score = 1;

                if ($score > $bestNameScore) {
                    $colName = $k;
                    $bestNameScore = $score;
                }
            }
            if ($colName === -1) continue;

            // Detect Attendance Specific Columns
            $colDate = -1;
            $colStatus = -1;
            $colNote = -1;
            $colEskul = -1; // Also needed for attendance

            foreach ($headers as $k => $h) {
                if ($h === 'tanggal') $colDate = $k;
                if ($h === 'status' || $h === 'kehadiran') $colStatus = $k;
                if ($h === 'catatan' || $h === 'keterangan') $colNote = $k;
                if ($h === 'ekstrakurikuler' || $h === 'eskul' || str_contains($h, 'ekstrakurikuler')) $colEskul = $k; 
            }
            
            // Is this an Attendance Sheet?
            $isAttendanceSheet = ($colDate !== -1 && $colStatus !== -1);

            // Scan rows
            for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (!isset($row[$colName])) continue;
                
                $name = trim($row[$colName]);
                if ($name === '' || $name === '-') continue;
                if (strtolower($name) === 'nama lengkap' || strtolower($name) === 'nama siswa') continue;

                // Determine Class for this row
                $rowClass = $class;
                if ($colClass !== -1 && isset($row[$colClass]) && $row[$colClass]) {
                    $rowClass = self::normalizeClass(trim($row[$colClass]));
                }
                
                if (!$rowClass) continue; // Skip if no class context

                $nis = ($colNis !== -1 && isset($row[$colNis])) ? trim($row[$colNis]) : null;
                
                // Lookup Student (Priority Strategy)
                $student = null;
                
                // 1. Try NIS Match (Highest Precision)
                if ($nis && isset($mapNis[$nis])) {
                    $student = $mapNis[$nis];
                }
                
                // 2. Try Name + Class Match (Exact)
                if (!$student) {
                    $key = strtoupper(trim($name)) . '|' . strtoupper(trim($rowClass));
                    if (isset($mapNameClass[$key])) {
                        $student = $mapNameClass[$key];
                    }
                }
                
                // 3. Try Loose Name Match (Same class, similar name) - for small typos
                if (!$student) {
                    $student = \App\Models\Student::where('class', $rowClass)
                        ->where('name', 'LIKE', "%{$name}%")
                        ->first();
                }
                
                // Create or Update Student (Only if purely student list or if missing)
                // For attendance sheet, preferably we don't create new students unless we are sure.
                // But let's keep consistency: if data is there, we use it. Since name+class is there.
                
                if ($student) {
                    // Update only if strictly creating a robust student database
                    // For now, let's just ensure they exist.
                } else {
                    $student = Student::create([
                        'name' => $name,
                        'class' => $rowClass,
                        'nis' => $nis,
                        'status' => 'active'
                    ]);
                    $counts['students']++;
                    if ($student->nis) $mapNis[$student->nis] = $student;
                    $key = strtoupper(trim($student->name)) . '|' . strtoupper(trim($student->class));
                    $mapNameClass[$key] = $student;
                }
                
                // --- PROCESS ATTENDANCE ---
                if ($isAttendanceSheet && $student) {
                     if ($colEskul !== -1 && isset($row[$colEskul]) && $row[$colEskul]) {
                        $eskulName = trim($row[$colEskul]);
                        $eskul = \App\Models\Eskul::where('name', $eskulName)->first();
                        if (!$eskul) $eskul = \App\Models\Eskul::where('name', 'LIKE', "%{$eskulName}%")->first(); // Loose match
                        
                        if ($eskul) {
                            $dateRaw = trim($row[$colDate]);
                            $statusRaw = strtolower(trim($row[$colStatus]));
                            $note = ($colNote !== -1 && isset($row[$colNote])) ? trim($row[$colNote]) : null;
                            
                            // Map Status
                            $status = 'present';
                            if (str_contains($statusRaw, 'sakit')) $status = 'sick';
                            elseif (str_contains($statusRaw, 'izin')) $status = 'permission';
                            elseif (str_contains($statusRaw, 'alpa') || str_contains($statusRaw, 'alpha')) $status = 'absent';
                            
                            // Parse Date (Assuming d-m-Y from backup or Excel numeric)
                            $date = null;
                            try {
                                if (is_numeric($dateRaw)) {
                                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateRaw)->format('Y-m-d');
                                } else {
                                    $date = date('Y-m-d', strtotime($dateRaw));
                                }
                            } catch (\Exception $e) {}
                            
                            if ($date) {
                                \App\Models\Attendance::updateOrCreate(
                                    [
                                        'student_id' => $student->id,
                                        'eskul_id' => $eskul->id,
                                        'date' => $date,
                                        'academic_year_id' => $activeYear->id // Assuming backup is also consistent with active year or we just force it
                                    ],
                                    [
                                        'status' => $status,
                                        'note' => $note
                                    ]
                                );
                                $counts['attendance']++;
                            }
                        }
                     }
                     // Continue to process Eskul/Enrollment/Grades if applicable in the same row
                }
                
                // Detect Eskul & Pembina Column
                $colEskul = -1;
                $colPembina = -1;
                foreach ($headers as $k => $h) {
                    if ($h === 'ekstrakurikuler' || $h === 'eskul' || str_contains($h, 'ekstrakurikuler')) {
                        $colEskul = $k; 
                    }
                    if ($h === 'pembina' || $h === 'instruktur' || $h === 'guru' || $h === 'pelatih' || str_contains($h, 'pembina') || str_contains($h, 'pembimbing')) {
                         $colPembina = $k;
                    }
                }

                if ($colEskul !== -1 && isset($row[$colEskul]) && $row[$colEskul]) {
                    $eskulName = trim($row[$colEskul]);
                    
                    // 1. Try Exact Match
                    $eskul = \App\Models\Eskul::where('name', $eskulName)->first();
                    
                    // 2. Try Loose Match
                    if (!$eskul) {
                         $eskul = \App\Models\Eskul::where('name', 'LIKE', "%{$eskulName}%")->first();
                    }

                    // 3. Create if Not Found (Auto-Discovery)
                    if (!$eskul && $eskulName !== '' && $eskulName !== '-') {
                        $instructor = ($colPembina !== -1 && isset($row[$colPembina])) ? trim($row[$colPembina]) : null;
                        
                        $eskul = \App\Models\Eskul::create([
                            'name' => $eskulName,
                            'instructor_name' => $instructor
                        ]);
                    }
                    
                    // 4. Update Pembina if present in Excel (even if not empty, to allow changes in Sm 2)
                    if ($eskul && $colPembina !== -1 && isset($row[$colPembina])) {
                        $instructor = trim($row[$colPembina]);
                        if ($instructor && $instructor !== '-' && $instructor !== $eskul->instructor_name) {
                            $eskul->update(['instructor_name' => $instructor]);
                        }
                    }
                    
                    if ($eskul) {
                        // Enroll student if not already IN THIS SEMESTER
                        $currentSemester = $activeYear->active_semester ?? '1';
                        $isEnrolled = DB::table('student_eskul')
                            ->where('student_id', $student->id)
                            ->where('eskul_id', $eskul->id)
                            ->where('academic_year_id', $activeYear->id)
                            ->where('semester', $currentSemester)
                            ->exists();
                        
                        if (!$isEnrolled) {
                             $student->eskuls()->attach($eskul->id, [
                                'academic_year_id' => $activeYear->id,
                                'semester' => $currentSemester
                            ]);
                        }

                        // Look for Grade Columns
                        $colDaily = -1;
                        $colSas1 = -1; 
                        $colSas2 = -1;

                        foreach ($headers as $k => $h) {
                            if (str_contains($h, 'nilai harian') || str_contains($h, 'harian')) $colDaily = $k;
                            if (str_contains($h, 'sas 1') || str_contains($h, 'sas1')) $colSas1 = $k;
                            if (str_contains($h, 'sas 2') || str_contains($h, 'sas2')) $colSas2 = $k;
                        }

                        // Helper for Calistung Parsing
                        $parseScore = function($sc) {
                             if (!$sc || $sc === '-') return null;
                             if (is_string($sc) && (str_contains(strtolower($sc), 'membaca:') || str_contains(strtolower($sc), 'menulis:'))) {
                                $parsed = [];
                                if (preg_match('/Membaca\s*:\s*([^\r\n]*)($|\s+Menulis)/i', $sc, $matches)) $parsed['reading'] = trim($matches[1]);
                                if (preg_match('/Menulis\s*:\s*([^\r\n]*)($|\s+Berhitung)/i', $sc, $matches)) $parsed['writing'] = trim($matches[1]);
                                if (preg_match('/Berhitung\s*:\s*([^\r\n]*)/i', $sc, $matches)) $parsed['counting'] = trim($matches[1]);
                                if (!empty($parsed)) return json_encode($parsed);
                            }
                            if (is_string($sc) && str_contains($sc, "\n")) {
                                 $lines = explode("\n", $sc);
                                 $parsed = [];
                                 foreach ($lines as $line) {
                                     if (str_contains(strtolower($line), 'membaca:')) $parsed['reading'] = trim(explode(':', $line)[1] ?? '');
                                     if (str_contains(strtolower($line), 'menulis:')) $parsed['writing'] = trim(explode(':', $line)[1] ?? '');
                                     if (str_contains(strtolower($line), 'berhitung:')) $parsed['counting'] = trim(explode(':', $line)[1] ?? '');
                                 }
                                 if (!empty($parsed)) return json_encode($parsed);
                            }
                            return $sc;
                        };
                        
                        $saveGrade = function($type, $rawScore) use ($student, $eskul, $activeYear, $currentSemester, $parseScore, &$counts) {
                             if (!$rawScore) return;
                             $score = $parseScore($rawScore);
                             if (!$score) return;
                             
                             \App\Models\Grade::updateOrCreate(
                                [
                                    'student_id' => $student->id,
                                    'eskul_id' => $eskul->id,
                                    'academic_year_id' => $activeYear->id,
                                    'semester' => $currentSemester,
                                    'type' => $type
                                ],
                                [
                                    'score' => $score,
                                    'date' => now()
                                ]
                            );
                            $counts['grades']++;
                        };

                        if ($colDaily !== -1 && isset($row[$colDaily])) $saveGrade('daily', $row[$colDaily]);
                        if ($colSas1 !== -1 && isset($row[$colSas1])) $saveGrade('sas1', $row[$colSas1]);
                        if ($colSas2 !== -1 && isset($row[$colSas2])) $saveGrade('sas2', $row[$colSas2]);
                    }
                }

            }

            // 4. Process Achievements (Data Prestasi)
            // Detect Achievement specific columns
            $colAchievementName = -1;
            $colLevel = -1;
            $colOrganizer = -1;
            // $colDate and $colNote already detected above (Tanggal, Keterangan)

            foreach ($headers as $k => $h) {
                if (str_contains($h, 'nama prestasi')) $colAchievementName = $k;
                if (str_contains($h, 'tingkat')) $colLevel = $k;
                if (str_contains($h, 'penyelenggara')) $colOrganizer = $k;
            }

            if ($colAchievementName !== -1 && $colLevel !== -1) {
                // This is an Achievements Sheet
                for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    
                    // Identify Student
                    $name = isset($row[$colName]) ? trim($row[$colName]) : '';
                    if (!$name) continue;

                    // Lookup Student (Re-use existing student detection logic or simple lookup)
                    // We can reuse the $mapNis / $mapNameClass strategy
                    $student = null;
                    $nis = ($colNis !== -1 && isset($row[$colNis])) ? trim($row[$colNis]) : null;

                    if ($nis && isset($mapNis[$nis])) {
                        $student = $mapNis[$nis];
                    }
                    if (!$student) {
                         // Fallback to name match (assuming class matches mostly or just name unique)
                         // Try with detected rowClass first
                         $rowClass = $class; 
                         if ($colClass !== -1 && isset($row[$colClass])) $rowClass = self::normalizeClass(trim($row[$colClass]));
                         
                         $key = strtoupper($name) . '|' . strtoupper($rowClass ?? '');
                         if (isset($mapNameClass[$key])) {
                             $student = $mapNameClass[$key];
                         } else {
                             // Try just name if class is fuzzy
                             // Note: This matches first student with same name.
                             $student = Student::where('name', $name)->first(); 
                         }
                    }

                    if ($student) {
                        $achievementName = trim($row[$colAchievementName]);
                        $level = trim($row[$colLevel]);
                        if (!$achievementName) continue;

                        $dateRaw = ($colDate !== -1 && isset($row[$colDate])) ? trim($row[$colDate]) : null;
                        $date = now();
                        if ($dateRaw) {
                            try {
                                if (is_numeric($dateRaw)) {
                                     $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateRaw);
                                } else {
                                     $date = date('Y-m-d', strtotime($dateRaw));
                                }
                            } catch (\Exception $e) {}
                        }

                        $organizer = ($colOrganizer !== -1 && isset($row[$colOrganizer])) ? trim($row[$colOrganizer]) : null;
                        $description = ($colNote !== -1 && isset($row[$colNote])) ? trim($row[$colNote]) : null;

                        \App\Models\Achievement::updateOrCreate(
                            [
                                'student_id' => $student->id,
                                'name' => $achievementName,
                                'level' => $level,
                            ],
                            [
                                'date' => $date,
                                'organizer' => $organizer,
                                'description' => $description
                            ]
                        );
                        $counts['achievements']++;
                    }
                }
            } 
            // End Achievement Processing
        }
        
        return $counts;
    }

    private static function normalizeClass($raw)
    {
        // Handle "Kls IA", "Kls 6B" etc already handled by regex capturing group
        // Just normalize Roman Numerals
        $romans = [
            'VI' => '6', 'V' => '5', 'IV' => '4', 'III' => '3', 'II' => '2', 'I' => '1'
        ];
        
        foreach ($romans as $roman => $number) {
            // Use word boundary or simple start match
            // "I A" -> "1A"
            // "III" -> "3"
            if (Str::startsWith($raw, $roman)) {
                 return Str::replaceFirst($roman, $number, $raw);
            }
        }
        // Remove spaces "1 A" -> "1A"
        return str_replace(' ', '', $raw);
    }
}
