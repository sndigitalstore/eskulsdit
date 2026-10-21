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
    /**
     * Cek apakah jadwal eskul cocok dengan hari tanggal tertentu
     */
    public static function isScheduledOnDate(?string $scheduleStr, Carbon $date): bool
    {
        if (empty($scheduleStr)) return false;

        $dayName = strtolower($date->locale('id')->isoFormat('dddd')); // e.g. jumat, sabtu
        $cleanDay = str_replace("'", "", $dayName); // handles jum'at -> jumat
        $cleanSchedule = str_replace("'", "", strtolower($scheduleStr));

        if (str_contains($cleanSchedule, $cleanDay)) {
            return true;
        }

        // Handle variasi minggu / ahad
        if (($cleanDay === 'minggu' || $cleanDay === 'ahad') &&
            (str_contains($cleanSchedule, 'minggu') || str_contains($cleanSchedule, 'ahad'))) {
            return true;
        }

        return false;
    }

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
                'activeYear' => null,
                'viewMode' => 'today',
                'selectedDate' => now()->toDateString(),
                'fullDateIndo' => now()->locale('id')->isoFormat('dddd, D MMMM Y'),
                'onlyScheduled' => true,
                'actionNeededList' => collect(),
                'broadcastMessage' => '',
            ]);
        }

        $viewMode = $request->input('mode', 'today'); // 'today' (Live Hari H) atau 'cumulative' (30 Hari)
        $selectedDate = $request->input('date', now()->toDateString());
        $statusFilter = $request->input('status', 'all'); // 'all', 'action_needed', 'complete'
        $searchKeyword = trim($request->input('search', ''));
        $onlyScheduled = $request->has('only_scheduled') ? $request->boolean('only_scheduled') : true;

        $parsedDate = Carbon::parse($selectedDate)->locale('id');
        $dayName = $parsedDate->isoFormat('dddd');
        $fullDateIndo = $parsedDate->isoFormat('dddd, D MMMM Y');

        $query = Eskul::activeYear()->orderBy('name');
        if (!empty($searchKeyword)) {
            $query->where(function($q) use ($searchKeyword) {
                $q->where('name', 'like', "%{$searchKeyword}%")
                  ->orWhere('instructor_name', 'like', "%{$searchKeyword}%");
            });
        }

        $allEskuls = $query->get();
        $complianceData = collect();
        $actionNeededList = collect();

        $totalEskuls = 0;
        $completeCount = 0;
        $actionNeededCount = 0;

        $activeSemester = (string)($activeYear->active_semester ?? '1');

        foreach ($allEskuls as $eskul) {
            $isScheduled = self::isScheduledOnDate($eskul->schedule, $parsedDate);

            // Jika dalam mode today dan user memilih only_scheduled:
            if ($viewMode === 'today' && $onlyScheduled && !$isScheduled) {
                continue;
            }

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

            // Jumlah Siswa Terdaftar
            $enrolledStudentsCount = $eskul->students()
                ->wherePivot('academic_year_id', $activeYear->id)
                ->wherePivot('semester', $activeSemester)
                ->count();

            // 2. Evaluasi Berdasarkan Mode
            $actionReasons = [];
            $missingStudentDates = [];
            $studentAttendanceComplete = false;
            $missingTeacherAttendance = false;
            $teacherAttendanceCount = 0;
            $gradedStudentsCount = 0;
            $gradesComplete = true;

            if ($viewMode === 'today') {
                // --- MODE TODAY (Live Hari H Pelaksanaan) ---
                // Cek Absensi Siswa pada tanggal ini
                $studentAttendanceToday = Attendance::where('eskul_id', $eskul->id)
                    ->whereDate('date', $selectedDate)
                    ->exists();

                $studentAttendanceComplete = $studentAttendanceToday;
                if ($enrolledStudentsCount > 0 && !$studentAttendanceComplete) {
                    $actionReasons[] = 'Absensi Siswa hari ini (' . $parsedDate->isoFormat('D MMM') . ') belum diisi';
                }

                // Cek Absensi Guru Pembina pada tanggal ini
                if ($pembina) {
                    $teacherAttendanceToday = TeacherAttendance::where('user_id', $pembina->id)
                        ->whereDate('date', $selectedDate)
                        ->first();

                    $missingTeacherAttendance = ($teacherAttendanceToday === null);
                    $teacherAttendanceCount = $teacherAttendanceToday ? 1 : 0;
                    if ($missingTeacherAttendance) {
                        $actionReasons[] = 'Absensi Kehadiran Pembina hari ini belum tercatat';
                    }
                } else {
                    $missingTeacherAttendance = true;
                    $actionReasons[] = 'Belum ada akun Pembina yang tertaut';
                }

                // Pada mode today, butuh tindakan jika absensi hari ini belum beres
                $needsAction = !$studentAttendanceComplete || $missingTeacherAttendance;

            } else {
                // --- MODE CUMULATIVE (Akumulasi 30 Hari Terakhir & Nilai) ---
                $missingStudentDates = $eskul->getMissingAttendanceDates(30);
                $studentAttendanceComplete = empty($missingStudentDates);

                if ($pembina) {
                    $teacherAttendanceCount = TeacherAttendance::where('user_id', $pembina->id)
                        ->where('academic_year_id', $activeYear->id)
                        ->count();

                    $studentAttendanceRecordsCount = Attendance::where('eskul_id', $eskul->id)
                        ->where('academic_year_id', $activeYear->id)
                        ->distinct('date')
                        ->count('date');

                    if ($studentAttendanceRecordsCount > 0 && $teacherAttendanceCount === 0) {
                        $missingTeacherAttendance = true;
                    }
                }

                if ($enrolledStudentsCount > 0) {
                    $gradedStudentsCount = Grade::where('eskul_id', $eskul->id)
                        ->where('academic_year_id', $activeYear->id)
                        ->where('semester', $activeSemester)
                        ->distinct('student_id')
                        ->count('student_id');
                }
                $gradesComplete = ($enrolledStudentsCount === 0) || ($gradedStudentsCount >= $enrolledStudentsCount);

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

                $needsAction = (!$studentAttendanceComplete) || ($missingTeacherAttendance) || (!$gradesComplete);
            }

            if ($needsAction) {
                $actionNeededCount++;
            } else {
                $completeCount++;
            }

            // Template Pesan WA Personal per Pembina
            $waMessage = '';
            if ($pembina) {
                $pembinaName = $pembina->name;
                $eskulName = $eskul->name;

                $msgLines = [];
                $msgLines[] = "Assalamualaikum Wr. Wb. Yth. Ust/Ustazah *{$pembinaName}*,";
                if ($viewMode === 'today') {
                    $msgLines[] = "Mohon perhatiannya untuk melengkapi administrasi ekstrakurikuler *{$eskulName}* pada pelaksanaan hari ini (*{$fullDateIndo}*):";
                    if (!$studentAttendanceComplete) {
                        $msgLines[] = "• *Absensi Siswa*: Belum diisi di sistem";
                    }
                    if ($missingTeacherAttendance) {
                        $msgLines[] = "• *Absensi Pembina*: Belum melakukan presensi 'Absensi Saya'";
                    }
                } else {
                    $msgLines[] = "Mohon perhatiannya untuk melengkapi administrasi ekstrakurikuler *{$eskulName}* di SIM Eskul:";
                    foreach ($actionReasons as $reason) {
                        $msgLines[] = "• {$reason}";
                    }
                }
                $msgLines[] = "\nMohon dapat segera menginputnya melalui aplikasi: https://eskulsdit.annadzir.sch.id";
                $msgLines[] = "Jazakumullahu khairan atas kerjasamanya. 🙏";
                $waMessage = implode("\n", $msgLines);
            }

            $item = [
                'eskul' => $eskul,
                'pembina' => $pembina,
                'is_scheduled_today' => $isScheduled,
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

            if ($needsAction) {
                $actionNeededList->push($item);
            }

            // Filter Berdasarkan Status
            if ($statusFilter === 'action_needed' && !$needsAction) {
                continue;
            }
            if ($statusFilter === 'complete' && $needsAction) {
                continue;
            }

            $complianceData->push($item);
        }

        // Template Pesan Broadcast WhatsApp (Rekapan Seluruh Pembina yang Belum Lengkap)
        $broadcastLines = [];
        $broadcastLines[] = "📢 *PENGINGAT ADMINISTRASI ESKUL " . ($viewMode === 'today' ? "HARI INI" : "") . "*";
        $broadcastLines[] = "📅 *Hari/Tanggal*: {$fullDateIndo}";
        $broadcastLines[] = "";
        $broadcastLines[] = "Kepada Yth. Ust/Ustazah Pembina Ekstrakurikuler,";
        if ($actionNeededList->isNotEmpty()) {
            $broadcastLines[] = "Berikut daftar eskul yang *belum melengkapi administrasi " . ($viewMode === 'today' ? "pada pelaksanaan hari ini" : "") . "*:";
            $broadcastLines[] = "";
            $no = 1;
            foreach ($actionNeededList as $act) {
                $pName = $act['pembina'] ? $act['pembina']->name : ($act['eskul']->instructor_name ?? 'Belum ditentukan');
                $eName = $act['eskul']->name;
                $details = [];
                if (!$act['student_attendance_complete']) {
                    $details[] = 'Absen Siswa ❌';
                } else {
                    $details[] = 'Absen Siswa ✅';
                }
                if ($act['missing_teacher_attendance']) {
                    $details[] = 'Absen Guru ❌';
                } else {
                    $details[] = 'Absen Guru ✅';
                }
                $detailStr = implode(' | ', $details);
                $broadcastLines[] = "{$no}. *{$eName}* - {$pName} ({$detailStr})";
                $no++;
            }
        } else {
            $broadcastLines[] = "Alhamdulillah seluruh pembina yang bertugas telah tertib melengkapi administrasi! ✨";
        }
        $broadcastLines[] = "";
        $broadcastLines[] = "Mohon untuk dapat segera melengkapinya melalui SIM Eskul:";
        $broadcastLines[] = "🔗 https://eskulsdit.annadzir.sch.id";
        $broadcastLines[] = "";
        $broadcastLines[] = "Jazakumullahu khairan atas dedikasi dan kerjasamanya. 🙏";
        $broadcastMessage = implode("\n", $broadcastLines);

        return view('compliance.index', compact(
            'complianceData',
            'totalEskuls',
            'completeCount',
            'actionNeededCount',
            'statusFilter',
            'searchKeyword',
            'activeYear',
            'viewMode',
            'selectedDate',
            'fullDateIndo',
            'onlyScheduled',
            'actionNeededList',
            'broadcastMessage'
        ));
    }
}
