<?php

namespace App\Http\Controllers;

use App\Models\AcademicScore;
use App\Models\AcademicSummary;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicReportController extends Controller
{
    public const ASSESSMENT_TYPES = [
        'STS_1' => [
            'key' => 'STS_1',
            'short' => 'STS 1',
            'name' => 'Sumatif Tengah Semester 1',
            'term' => 'Tengah Semester Ganjil',
            'semester' => 1,
            'badge' => '#0284c7',
        ],
        'SAS_1' => [
            'key' => 'SAS_1',
            'short' => 'SAS 1',
            'name' => 'Sumatif Akhir Semester 1',
            'term' => 'Akhir Semester Ganjil',
            'semester' => 1,
            'badge' => '#16a34a',
        ],
        'STS_2' => [
            'key' => 'STS_2',
            'short' => 'STS 2',
            'name' => 'Sumatif Tengah Semester 2',
            'term' => 'Tengah Semester Genap',
            'semester' => 2,
            'badge' => '#f59e0b',
        ],
        'SAS_2' => [
            'key' => 'SAS_2',
            'short' => 'SAS 2',
            'name' => 'Sumatif Akhir Semester 2',
            'term' => 'Akhir Semester Genap',
            'semester' => 2,
            'badge' => '#8b5cf6',
        ],
    ];

    public function index(Request $request)
    {
        $user = Auth::user();
        $isTeacher = $user->role === 'teacher';
        $isHomeroom = $isTeacher && !empty($user->homeroom_class);

        // Active & available academic years
        $activeYear = AcademicYear::where('is_active', true)->first();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $selectedYearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $selectedYear = AcademicYear::find($selectedYearId);

        // Assessment type: STS_1, SAS_1, STS_2, SAS_2
        $selectedAssessment = $request->assessment ?? 'STS_1';
        if (!array_key_exists($selectedAssessment, self::ASSESSMENT_TYPES)) {
            $selectedAssessment = 'STS_1';
        }
        $assessmentInfo = self::ASSESSMENT_TYPES[$selectedAssessment];

        // Available classes
        if ($isHomeroom) {
            $selectedClass = $user->homeroom_class;
            $classes = collect([$user->homeroom_class]);
        } else {
            $classes = Student::forYear($selectedYearId)
                ->select('class')->distinct()->whereNotNull('class')->orderBy('class')->pluck('class');
            $selectedClass = $request->class ?? ($classes->first() ?? null);
        }

        // Active tab: 'recap', 'input', 'attendance'
        $activeTab = $request->tab ?? 'recap';
        if (!in_array($activeTab, ['recap', 'input', 'attendance'])) {
            $activeTab = 'recap';
        }

        // Active subjects
        $subjects = Subject::where('is_active', true)->orderBy('order_num', 'asc')->get();
        $selectedSubjectId = $request->subject_id ?? ($subjects->first()?->id ?? null);
        $selectedSubject = Subject::find($selectedSubjectId);

        // Students in selected class
        $students = collect();
        $scoresMap = []; // [student_id][subject_id] => AcademicScore
        $summariesMap = []; // [student_id] => AcademicSummary
        $recapTable = []; // [student_id => ['student' => ..., 'scores' => [...], 'total' => ..., 'average' => ..., 'rank' => ...]]
        $classStats = [
            'total_students' => 0,
            'class_average' => 0,
            'highest_score' => 0,
            'lowest_score' => 0,
            'passed_count' => 0,
            'passed_percentage' => 0,
        ];

        if ($selectedClass) {
            $students = Student::forYear($selectedYearId)
                ->where('class', $selectedClass)
                ->where('status', 'active')
                ->orderBy('name', 'asc')
                ->get();

            $studentIds = $students->pluck('id');

            // Fetch scores
            $scores = AcademicScore::where('academic_year_id', $selectedYearId)
                ->where('assessment_type', $selectedAssessment)
                ->whereIn('student_id', $studentIds)
                ->get();

            foreach ($scores as $sc) {
                $scoresMap[$sc->student_id][$sc->subject_id] = $sc;
            }

            // Fetch summaries
            $summaries = AcademicSummary::where('academic_year_id', $selectedYearId)
                ->where('assessment_type', $selectedAssessment)
                ->whereIn('student_id', $studentIds)
                ->get();

            foreach ($summaries as $sm) {
                $summariesMap[$sm->student_id] = $sm;
            }

            // Build recap matrix & ranking
            $totalAverages = 0;
            $allStudentAverages = [];

            foreach ($students as $stu) {
                $stuScores = [];
                $sum = 0;
                $count = 0;

                foreach ($subjects as $sb) {
                    $scoreObj = $scoresMap[$stu->id][$sb->id] ?? null;
                    $val = $scoreObj ? (float)$scoreObj->score : null;
                    $stuScores[$sb->id] = [
                        'score' => $val,
                        'predicate' => $scoreObj?->predicate ?? ($val !== null ? AcademicScore::calculatePredicate($val) : '-'),
                        'is_pass' => $val !== null ? ($val >= $sb->kkm) : false,
                    ];

                    if ($val !== null) {
                        $sum += $val;
                        $count++;
                    }
                }

                $avg = $count > 0 ? round($sum / $count, 2) : 0;
                if ($count > 0) {
                    $allStudentAverages[] = $avg;
                }

                $recapTable[$stu->id] = [
                    'student' => $stu,
                    'scores' => $stuScores,
                    'total' => $sum,
                    'count' => $count,
                    'average' => $avg,
                    'summary' => $summariesMap[$stu->id] ?? null,
                ];
            }

            // Calculate ranks
            uasort($recapTable, fn($a, $b) => $b['average'] <=> $a['average']);
            $rank = 1;
            foreach ($recapTable as $stuId => &$row) {
                $row['rank'] = $row['count'] > 0 ? $rank++ : '-';
            }
            unset($row);

            // Sort back by student name for clean tabular display
            uasort($recapTable, fn($a, $b) => strcmp($a['student']->name, $b['student']->name));

            // Compute statistics
            $totalGraded = count($allStudentAverages);
            if ($totalGraded > 0) {
                $classStats['total_students'] = $students->count();
                $classStats['class_average'] = round(array_sum($allStudentAverages) / $totalGraded, 2);
                $classStats['highest_score'] = max($allStudentAverages);
                $classStats['lowest_score'] = min($allStudentAverages);
                $passed = 0;
                foreach ($allStudentAverages as $a) {
                    if ($a >= 75) $passed++;
                }
                $classStats['passed_count'] = $passed;
                $classStats['passed_percentage'] = round(($passed / $totalGraded) * 100, 1);
            }
        }

        return view('curriculum.reports.index', compact(
            'academicYears',
            'selectedYearId',
            'selectedYear',
            'classes',
            'selectedClass',
            'selectedAssessment',
            'assessmentInfo',
            'activeTab',
            'subjects',
            'selectedSubjectId',
            'selectedSubject',
            'students',
            'scoresMap',
            'summariesMap',
            'recapTable',
            'classStats',
            'isHomeroom'
        ));
    }

    /**
     * Bulk store or update scores for a selected subject, class, and assessment type.
     */
    public function storeScores(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'subject_id' => 'required|exists:subjects,id',
            'assessment_type' => 'required|string',
            'class' => 'required|string',
            'scores' => 'required|array',
        ]);

        $yearId = $request->academic_year_id;
        $subjectId = $request->subject_id;
        $assessmentType = $request->assessment_type;
        $scoresData = $request->scores;
        $notesData = $request->notes ?? [];
        $predicatesData = $request->predicates ?? [];

        $savedCount = 0;
        foreach ($scoresData as $studentId => $scoreVal) {
            if ($scoreVal === null || $scoreVal === '') {
                // Remove if emptied
                AcademicScore::where([
                    'academic_year_id' => $yearId,
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'assessment_type' => $assessmentType,
                ])->delete();
                continue;
            }

            $numVal = (float)$scoreVal;
            $predicate = $predicatesData[$studentId] ?? AcademicScore::calculatePredicate($numVal);
            $note = $notesData[$studentId] ?? null;

            AcademicScore::updateOrCreate(
                [
                    'academic_year_id' => $yearId,
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'assessment_type' => $assessmentType,
                ],
                [
                    'score' => $numVal,
                    'predicate' => $predicate,
                    'achievement_note' => $note,
                    'created_by' => Auth::id(),
                ]
            );
            $savedCount++;
        }

        return redirect()->route('academic-reports.index', [
            'year_id' => $yearId,
            'class' => $request->class,
            'assessment' => $assessmentType,
            'subject_id' => $subjectId,
            'tab' => 'input',
        ])->with('success', "Nilai untuk {$savedCount} siswa berhasil disimpan dengan rapi!");
    }

    /**
     * Bulk store or update attendance and homeroom notes for class report.
     */
    public function storeSummaries(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'assessment_type' => 'required|string',
            'class' => 'required|string',
            'students' => 'required|array',
        ]);

        $yearId = $request->academic_year_id;
        $assessmentType = $request->assessment_type;
        $students = $request->students;

        foreach ($students as $studentId => $data) {
            AcademicSummary::updateOrCreate(
                [
                    'academic_year_id' => $yearId,
                    'student_id' => $studentId,
                    'assessment_type' => $assessmentType,
                ],
                [
                    'sick_count' => (int)($data['sick'] ?? 0),
                    'permission_count' => (int)($data['permission'] ?? 0),
                    'unexcused_count' => (int)($data['unexcused'] ?? 0),
                    'homeroom_notes' => $data['notes'] ?? null,
                ]
            );
        }

        return redirect()->route('academic-reports.index', [
            'year_id' => $yearId,
            'class' => $request->class,
            'assessment' => $assessmentType,
            'tab' => 'attendance',
        ])->with('success', 'Catatan kehadiran dan catatan wali kelas berhasil disimpan!');
    }

    /**
     * Print official A4 individual academic report card for a student.
     */
    public function printStudent(Request $request, Student $student)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $academicYear = AcademicYear::find($yearId);

        $assessmentType = $request->assessment ?? 'STS_1';
        if (!array_key_exists($assessmentType, self::ASSESSMENT_TYPES)) {
            $assessmentType = 'STS_1';
        }
        $assessmentInfo = self::ASSESSMENT_TYPES[$assessmentType];

        $subjects = Subject::where('is_active', true)->orderBy('order_num', 'asc')->get();

        $scores = AcademicScore::where('academic_year_id', $yearId)
            ->where('student_id', $student->id)
            ->where('assessment_type', $assessmentType)
            ->get()
            ->keyBy('subject_id');

        $summary = AcademicSummary::where('academic_year_id', $yearId)
            ->where('student_id', $student->id)
            ->where('assessment_type', $assessmentType)
            ->first();

        // Extra eskul records for this student and year/semester
        $eskuls = $student->eskuls()
            ->wherePivot('academic_year_id', $yearId)
            ->wherePivot('semester', $assessmentInfo['semester'])
            ->get();

        $eskulGrades = Grade::where('student_id', $student->id)
            ->where('academic_year_id', $yearId)
            ->where('semester', $assessmentInfo['semester'])
            ->get()
            ->keyBy('eskul_id');

        // Homeroom teacher
        $homeroomTeacher = User::where('homeroom_class', $student->class)->first();

        // Qur'an Mentoring: Tilawati & Tahfidz
        $tilawatiGroup = $student->tilawatiGroup($yearId);
        $tilawatiPivot = $tilawatiGroup ? $student->mentoringGroups()->where('mentoring_groups.id', $tilawatiGroup->id)->first()?->pivot : null;

        $tahfidzGroup = $student->tahfidzGroup($yearId);
        $latestTahfidzRecord = $student->tahfidzRecords()
            ->where('academic_year_id', $yearId)
            ->latest('session_date')
            ->first();
        $totalHafalanRecords = $student->tahfidzRecords()
            ->where('academic_year_id', $yearId)
            ->count();

        // Karakter SMILE: Sholeh, Muslih, Intellectual Curiosity, Leadership, Environmentally Friendly
        $characterAppreciations = $student->characterAppreciations()
            ->where('academic_year_id', $yearId)
            ->latest('date')
            ->get();

        return view('curriculum.reports.print_student', compact(
            'student',
            'academicYear',
            'assessmentType',
            'assessmentInfo',
            'subjects',
            'scores',
            'summary',
            'eskuls',
            'eskulGrades',
            'homeroomTeacher',
            'tilawatiGroup',
            'tilawatiPivot',
            'tahfidzGroup',
            'latestTahfidzRecord',
            'totalHafalanRecords',
            'characterAppreciations'
        ));
    }

    /**
     * Print official A4 individual academic report cards for ALL students in a class (Bulk Print).
     */
    public function printAllStudents(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $academicYear = AcademicYear::find($yearId);

        $assessmentType = $request->assessment ?? 'STS_1';
        if (!array_key_exists($assessmentType, self::ASSESSMENT_TYPES)) {
            $assessmentType = 'STS_1';
        }
        $assessmentInfo = self::ASSESSMENT_TYPES[$assessmentType];

        $class = $request->class;
        $students = Student::forYear($yearId)
            ->where('class', $class)
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        $subjects = Subject::where('is_active', true)->orderBy('order_num', 'asc')->get();

        $scores = AcademicScore::where('academic_year_id', $yearId)
            ->where('assessment_type', $assessmentType)
            ->whereIn('student_id', $students->pluck('id'))
            ->get();

        $scoresMap = [];
        foreach ($scores as $sc) {
            $scoresMap[$sc->student_id][$sc->subject_id] = $sc;
        }

        $summaries = AcademicSummary::where('academic_year_id', $yearId)
            ->where('assessment_type', $assessmentType)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        $homeroomTeacher = User::where('homeroom_class', $class)->first();

        // Preload student specific data
        $studentData = [];
        foreach ($students as $stu) {
            $tilawatiGroup = $stu->tilawatiGroup($yearId);
            $tilawatiPivot = $tilawatiGroup ? $stu->mentoringGroups()->where('mentoring_groups.id', $tilawatiGroup->id)->first()?->pivot : null;

            $tahfidzGroup = $stu->tahfidzGroup($yearId);
            $latestTahfidz = $stu->tahfidzRecords()->where('academic_year_id', $yearId)->latest('session_date')->first();

            $eskuls = $stu->eskuls()
                ->wherePivot('academic_year_id', $yearId)
                ->wherePivot('semester', $assessmentInfo['semester'])
                ->get();

            $eskulGrades = Grade::where('student_id', $stu->id)
                ->where('academic_year_id', $yearId)
                ->where('semester', $assessmentInfo['semester'])
                ->get()
                ->keyBy('eskul_id');

            $appreciations = $stu->characterAppreciations()->where('academic_year_id', $yearId)->latest('date')->get();

            $studentData[$stu->id] = [
                'scores' => $scoresMap[$stu->id] ?? [],
                'summary' => $summaries[$stu->id] ?? null,
                'tilawatiGroup' => $tilawatiGroup,
                'tilawatiPivot' => $tilawatiPivot,
                'tahfidzGroup' => $tahfidzGroup,
                'latestTahfidz' => $latestTahfidz,
                'eskuls' => $eskuls,
                'eskulGrades' => $eskulGrades,
                'appreciations' => $appreciations,
            ];
        }

        return view('curriculum.reports.print_all_students', compact(
            'academicYear',
            'class',
            'assessmentType',
            'assessmentInfo',
            'subjects',
            'students',
            'studentData',
            'homeroomTeacher'
        ));
    }

    /**
     * Print official A4 landscape class recap for academic report.
     */
    public function printClassRecap(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $request->year_id ?? ($activeYear ? $activeYear->id : null);
        $academicYear = AcademicYear::find($yearId);

        $assessmentType = $request->assessment ?? 'STS_1';
        if (!array_key_exists($assessmentType, self::ASSESSMENT_TYPES)) {
            $assessmentType = 'STS_1';
        }
        $assessmentInfo = self::ASSESSMENT_TYPES[$assessmentType];

        $class = $request->class;
        $students = Student::forYear($yearId)
            ->where('class', $class)
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        $subjects = Subject::where('is_active', true)->orderBy('order_num', 'asc')->get();

        $scores = AcademicScore::where('academic_year_id', $yearId)
            ->where('assessment_type', $assessmentType)
            ->whereIn('student_id', $students->pluck('id'))
            ->get();

        $scoresMap = [];
        foreach ($scores as $sc) {
            $scoresMap[$sc->student_id][$sc->subject_id] = $sc;
        }

        $homeroomTeacher = User::where('homeroom_class', $class)->first();

        return view('curriculum.reports.print_class_recap', compact(
            'academicYear',
            'class',
            'assessmentType',
            'assessmentInfo',
            'subjects',
            'students',
            'scoresMap',
            'homeroomTeacher'
        ));
    }

    /**
     * Download CSV template for grading a specific subject and class.
     */
    public function exportTemplate(Request $request)
    {
        $yearId = $request->year_id ?? AcademicYear::where('is_active', true)->value('id');
        $class = $request->class;
        $subjectId = $request->subject_id;
        $assessment = $request->assessment ?? 'STS_1';

        $subject = Subject::findOrFail($subjectId);
        $students = Student::forYear($yearId)
            ->where('class', $class)
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        $existingScores = AcademicScore::where('academic_year_id', $yearId)
            ->where('subject_id', $subjectId)
            ->where('assessment_type', $assessment)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        $filename = "Format_Nilai_{$subject->code}_{$class}_{$assessment}.csv";

        return response()->streamDownload(function() use ($students, $existingScores) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
            fputcsv($handle, ['NIS', 'Nama Siswa', 'Nilai (0-100)', 'Catatan Capaian Pembelajaran'], ';');

            foreach ($students as $stu) {
                $sc = $existingScores[$stu->id] ?? null;
                fputcsv($handle, [
                    $stu->nis ?? '',
                    $stu->name,
                    $sc ? $sc->score : '',
                    $sc ? $sc->achievement_note : '',
                ], ';');
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Import scores from CSV or Excel file.
     */
    public function importScores(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'subject_id' => 'required|exists:subjects,id',
            'assessment_type' => 'required|string',
            'class' => 'required|string',
            'file' => 'required|file',
        ]);

        $file = $request->file('file');
        $yearId = $request->academic_year_id;
        $subjectId = $request->subject_id;
        $assessmentType = $request->assessment_type;
        $class = $request->class;

        $students = Student::forYear($yearId)
            ->where('class', $class)
            ->where('status', 'active')
            ->get();
        $studentsByNis = $students->whereNotNull('nis')->keyBy('nis');
        $studentsByName = $students->keyBy(fn($s) => strtolower(trim($s->name)));

        $path = $file->getRealPath();
        $importedCount = 0;

        if (($handle = fopen($path, 'r')) !== false) {
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            $firstRow = true;
            $delimiter = ';';

            while (($row = fgetcsv($handle, 2000, $delimiter)) !== false) {
                if (count($row) === 1 && str_contains($row[0], ',')) {
                    $row = str_getcsv($row[0], ',');
                }

                if ($firstRow) {
                    $firstRow = false;
                    if (isset($row[0]) && (stripos($row[0], 'nis') !== false || stripos($row[0], 'nama') !== false)) {
                        continue;
                    }
                }

                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                $nis = trim($row[0] ?? '');
                $name = trim($row[1] ?? '');
                $rawScore = isset($row[2]) ? trim($row[2]) : '';
                $rawNote = isset($row[3]) ? trim($row[3]) : null;

                $matchedStudent = null;
                if (!empty($nis) && isset($studentsByNis[$nis])) {
                    $matchedStudent = $studentsByNis[$nis];
                } elseif (!empty($name) && isset($studentsByName[strtolower($name)])) {
                    $matchedStudent = $studentsByName[strtolower($name)];
                }

                if (!$matchedStudent) {
                    continue;
                }

                if ($rawScore === '' || $rawScore === null) {
                    continue;
                }

                $scoreVal = (float)str_replace(',', '.', $rawScore);
                $predicate = AcademicScore::calculatePredicate($scoreVal);

                AcademicScore::updateOrCreate(
                    [
                        'academic_year_id' => $yearId,
                        'student_id' => $matchedStudent->id,
                        'subject_id' => $subjectId,
                        'assessment_type' => $assessmentType,
                    ],
                    [
                        'score' => $scoreVal,
                        'predicate' => $predicate,
                        'achievement_note' => $rawNote,
                        'created_by' => Auth::id(),
                    ]
                );
                $importedCount++;
            }
            fclose($handle);
        }

        return redirect()->route('academic-reports.index', [
            'year_id' => $yearId,
            'class' => $class,
            'assessment' => $assessmentType,
            'subject_id' => $subjectId,
            'tab' => 'input',
        ])->with('success', "Import nilai berhasil! {$importedCount} nilai siswa telah berhasil diperbarui ke sistem.");
    }

    /**
     * Export complete class grade ledger (Leger Nilai) as CSV.
     */
    public function exportLeger(Request $request)
    {
        $yearId = $request->year_id ?? AcademicYear::where('is_active', true)->value('id');
        $academicYear = AcademicYear::find($yearId);
        $class = $request->class;
        $assessment = $request->assessment ?? 'STS_1';
        $assessmentInfo = self::ASSESSMENT_TYPES[$assessment] ?? self::ASSESSMENT_TYPES['STS_1'];

        $subjects = Subject::where('is_active', true)->orderBy('order_num', 'asc')->get();
        $students = Student::forYear($yearId)
            ->where('class', $class)
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        $scores = AcademicScore::where('academic_year_id', $yearId)
            ->where('assessment_type', $assessment)
            ->whereIn('student_id', $students->pluck('id'))
            ->get();

        $scoresMap = [];
        foreach ($scores as $sc) {
            $scoresMap[$sc->student_id][$sc->subject_id] = $sc;
        }

        $filename = "Leger_Nilai_Kelas_{$class}_{$assessment}.csv";

        return response()->streamDownload(function() use ($students, $subjects, $scoresMap) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            $headers = ['No', 'NIS', 'Nama Siswa'];
            foreach ($subjects as $sb) {
                $headers[] = $sb->name . ' (' . ($sb->code ?: substr($sb->name, 0, 4)) . ')';
            }
            $headers[] = 'Total Nilai';
            $headers[] = 'Rata-Rata';
            $headers[] = 'Predikat Rerata';

            fputcsv($handle, $headers, ';');

            $no = 1;
            foreach ($students as $stu) {
                $row = [$no++, $stu->nis ?? '', $stu->name];
                $sum = 0;
                $count = 0;

                foreach ($subjects as $sb) {
                    $sc = $scoresMap[$stu->id][$sb->id] ?? null;
                    $val = $sc ? (float)$sc->score : null;
                    $row[] = $val !== null ? $val : '';
                    if ($val !== null) {
                        $sum += $val;
                        $count++;
                    }
                }

                $avg = $count > 0 ? round($sum / $count, 2) : 0;
                $row[] = $count > 0 ? $sum : '';
                $row[] = $count > 0 ? $avg : '';
                $row[] = $count > 0 ? AcademicScore::calculatePredicate($avg) : '';

                fputcsv($handle, $row, ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
