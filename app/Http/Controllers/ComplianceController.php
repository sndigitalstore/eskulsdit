<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eskul;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\TeacherAttendance;
use App\Models\Grade;
use App\Models\Attendance;
use Carbon\Carbon;

class ComplianceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'headmaster'])) {
            abort(403, 'Akses ditolak.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return view('compliance.index', [
                'complianceData' => collect(),
                'totalEskuls' => 0,
                'completeCount' => 0,
                'actionNeededCount' => 0,
                'statusFilter' => 'all',
                'searchKeyword' => '',
            ]);
        }

        $statusFilter = $request->input('status', 'all');
        $searchKeyword = trim($request->input('search', ''));

        $query = Eskul::activeYear()->orderBy('name');
        if (!empty($searchKeyword)) {
            $query->where(function($q) use ($searchKeyword) {
                $q->where('name', 'like', "%{$searchKeyword}%")
                  ->orWhere('instructor_name', 'like', "%{$searchKeyword}%");
            });
        }

        $eskuls = $query->get();
        $complianceData = collect();

        $totalEskuls = 0;
        $completeCount = 0;
        $actionNeededCount = 0;

        foreach ($eskuls as $eskul) {
            $totalEskuls++;

            // 1. Guru Pembina
            $pembina = User::where('role', 'teacher')
                ->activeYear()
                ->where('eskul_id', $eskul->id)
                ->first();

            if (!$pembina && !empty($eskul->instructor_name)) {
                $pembina = User::where('role', 'teacher')
                    ->activeYear()
                    ->where('name', 'LIKE', "%{$eskul->instructor_name}%")
                    ->first();
            }

            // 2. Absensi Siswa (30 Hari Terakhir)
            $missingStudentDates = $eskul->getMissingAttendanceDates(30);
            $studentAttendanceComplete = empty($missingStudentDates);

            // 3. Absensi Guru Pembina
            $teacherAttendanceCount = 0;
            $missingTeacherAttendance = false;
            if ($pembina) {
                $teacherAttendanceCount = TeacherAttendance::where('user_id', $pembina->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->count();
                // Jika ada absensi siswa tapi guru belum pernah absen guru sama sekali
                $studentAttendanceRecordsCount = Attendance::where('eskul_id', $eskul->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->distinct('date')
                    ->count('date');

                if ($studentAttendanceRecordsCount > 0 && $teacherAttendanceCount === 0) {
                    $missingTeacherAttendance = true;
                }
            }

            // 4. Input Nilai Siswa (Semester Aktif)
            $enrolledStudentsCount = $eskul->students()
                ->wherePivot('academic_year_id', $activeYear->id)
                ->wherePivot('semester', (string)$activeYear->semester)
                ->count();

            $gradedStudentsCount = 0;
            if ($enrolledStudentsCount > 0) {
                $gradedStudentsCount = Grade::where('eskul_id', $eskul->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester', (string)$activeYear->semester)
                    ->distinct('student_id')
                    ->count('student_id');
            }

            $gradesComplete = ($enrolledStudentsCount === 0) || ($gradedStudentsCount >= $enrolledStudentsCount);

            // Ringkasan Status
            $needsAction = (!$studentAttendanceComplete) || ($missingTeacherAttendance) || (!$gradesComplete);

            if ($needsAction) {
                $actionNeededCount++;
            } else {
                $completeCount++;
            }

            // Rincian Keterangan Butuh Tindakan
            $actionReasons = [];
            if (!$studentAttendanceComplete) {
                $actionReasons[] = 'Absensi Siswa terlewat (' . count($missingStudentDates) . ' pertemuan)';
            }
            if ($missingTeacherAttendance) {
                $actionReasons[] = 'Absensi Guru Pembina belum diisi';
            }
            if (!$gradesComplete) {
                $ungradedCount = max(0, $enrolledStudentsCount - $gradedStudentsCount);
                $actionReasons[] = 'Nilai Siswa belum lengkap (' . $ungradedCount . ' dari ' . $enrolledStudentsCount . ' siswa)';
            }

            // Template Pesan WA Pengingat
            $waMessage = '';
            if ($pembina) {
                $pembinaName = $pembina->name;
                $eskulName = $eskul->name;

                $msgLines = [];
                $msgLines[] = "Assalamualaikum Wr. Wb. Yth. Ust/Ustazah *{$pembinaName}*,";
                $msgLines[] = "Mohon perhatiiannya untuk melengkapi administrasi ekstrakurikuler *{$eskulName}* di SIM Eskul SDIT AN NADZIR:";
                
                if (!$studentAttendanceComplete) {
                    $formattedDates = array_map(function($d) {
                        return Carbon::parse($d)->isoFormat('D MMMM Y');
                    }, $missingStudentDates);
                    $msgLines[] = "• *Absensi Siswa*: Terlewat pada tanggal (" . implode(', ', $formattedDates) . ")";
                }
                if ($missingTeacherAttendance) {
                    $msgLines[] = "• *Absensi Guru*: Belum terisi pada sistem";
                }
                if (!$gradesComplete) {
                    $ungradedCount = max(0, $enrolledStudentsCount - $gradedStudentsCount);
                    $msgLines[] = "• *Nilai Siswa*: Belum diisi untuk {$ungradedCount} siswa (Semester {$activeYear->semester})";
                }
                
                $msgLines[] = "\nMohon dapat segera menginputnya melalui sistem. Terima kasih atas perhatian dan kerjasamanya. 🙏";
                $waMessage = implode("\n", $msgLines);
            }

            $item = [
                'eskul' => $eskul,
                'pembina' => $pembina,
                'missing_student_dates' => $missingStudentDates,
                'student_attendance_complete' => $studentAttendanceComplete,
                'missing_teacher_attendance' => $missingTeacherAttendance,
                'teacher_attendance_count' => $teacherAttendanceCount,
                'enrolled_students_count' => $enrolledStudentsCount,
                'graded_students_count' => $gradedStudentsCount,
                'grades_complete' => $gradesComplete,
                'needs_action' => $needsAction,
                'action_reasons' => $actionReasons,
                'wa_message' => $waMessage,
            ];

            // Filter Berdasarkan Status
            if ($statusFilter === 'action_needed' && !$needsAction) {
                continue;
            }
            if ($statusFilter === 'complete' && $needsAction) {
                continue;
            }

            $complianceData->push($item);
        }

        return view('compliance.index', compact(
            'complianceData',
            'totalEskuls',
            'completeCount',
            'actionNeededCount',
            'statusFilter',
            'searchKeyword',
            'activeYear'
        ));
    }
}
