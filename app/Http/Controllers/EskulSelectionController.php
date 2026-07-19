<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Eskul;
use App\Models\AcademicYear;
use App\Models\Setting;

class EskulSelectionController extends Controller
{
    public function create()
    {
        $status = Setting::where('key', 'form_status')->value('value') ?? 'open';
        
        if ($status == 'closed') {
            return view('pilihan_eskul.closed');
        }

        $title = Setting::where('key', 'form_title')->value('value') ?? 'Pilihan Ekstrakurikuler';
        $description = Setting::where('key', 'form_description')->value('value') ?? 'Silakan lengkapi data ananda untuk memilih kegiatan.';
        $quota = Setting::where('key', 'eskul_quota')->value('value') ?? 25;

        $allowedJson = Setting::where('key', 'allowed_eskuls')->value('value');
        $allowedIds = $allowedJson ? json_decode($allowedJson, true) : null;

        if ($allowedIds !== null) {
             // If setting exists, filter by it.
             $eskuls = Eskul::withCount(['students' => function($q) {
                  $activeYear = AcademicYear::where('is_active', true)->first();
                  if ($activeYear) {
                      $q->where('student_eskul.academic_year_id', $activeYear->id)
                        ->where('student_eskul.semester', $activeYear->active_semester)
                        ->where('status', '!=', 'graduated');
                  }
             }])->whereIn('id', $allowedIds)->get();
        } else {
             // If no setting, default to all.
             $eskuls = Eskul::withCount(['students' => function($q) {
                  $activeYear = AcademicYear::where('is_active', true)->first();
                  if ($activeYear) {
                      $q->where('student_eskul.academic_year_id', $activeYear->id)
                        ->where('student_eskul.semester', $activeYear->active_semester)
                        ->where('status', '!=', 'graduated');
                  }
             }])->get();
        }
        
        $classes = Student::activeYear()
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')
                  ->orWhereNull('status');
            })
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        if ($classes->isEmpty()) {
            $classes = \App\Models\SchoolClass::orderBy('name')->pluck('name');
        }

        return view('pilihan_eskul.form', compact('eskuls', 'title', 'description', 'quota', 'classes'));
    }

    public function getStudentsByClass(Request $request)
    {
        $class = $request->query('class');
        if (!$class) return response()->json([]);
        
        $activeYear = AcademicYear::where('is_active', true)->first();

        $students = Student::activeYear()
            ->where('class', $class)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')
                  ->orWhereNull('status');
            })
            ->with([
                'eskuls' => function($query) use ($activeYear) {
                    if ($activeYear) {
                        $query->where('student_eskul.academic_year_id', $activeYear->id);
                    }
                },
                'grades' => function($query) use ($activeYear) {
                    if ($activeYear) {
                        $query->where('academic_year_id', $activeYear->id)->orderBy('id', 'desc');
                    }
                }
            ])
            ->orderBy('name')
            ->get()
            ->map(function ($student) use ($activeYear) {
                $isAlreadyRegistered = false;
                $alreadyRegisteredMsg = '';
                $isLockedCalistung = false;
                $calistungMsg = '';
                $currentEskul = '';

                if ($activeYear) {
                    // Check if already registered in current active semester
                    $hasCurrentEnrollment = $student->eskuls->where('pivot.semester', $activeYear->active_semester)->isNotEmpty();
                    if ($hasCurrentEnrollment) {
                        $isAlreadyRegistered = true;
                        if ($activeYear->active_semester == '1') {
                            $alreadyRegisteredMsg = 'Ananda sudah terdaftar di eskul semester ini. Pendaftaran dikunci.';
                        } else {
                            $alreadyRegisteredMsg = 'Ananda sudah terdaftar. Mengisi kembali akan memindahkan pilihan eskul sebelumnya.';
                        }
                    }

                    // 1. Try to find CURRENT semester enrollment
                    $eskulPivot = $student->eskuls->where('pivot.semester', $activeYear->active_semester)->first();

                    $enrollmentSource = 'current';

                    // 2. If not found, try ANY semester in this active year (e.g. History from Sem 1)
                    if (!$eskulPivot) {
                        $eskulPivot = $student->eskuls->sortByDesc('pivot.semester')->first();
                        $enrollmentSource = 'history';
                    }

                    if ($eskulPivot) {
                        $currentEskul = $eskulPivot->name . ($enrollmentSource === 'history' ? ' (Semester Lalu)' : '');
                        
                        if ($eskulPivot->is_lockable) {
                            // Get Grade (Optimized via Eager Loading)
                            $grade = $student->grades->where('eskul_id', $eskulPivot->id)->first();
                                
                            $achievements = [];
                            if ($grade) {
                                $scoreStr = $grade->score;
                                $achievements = [];
                                $json = json_decode($scoreStr, true);
                                
                                if (is_array($json)) {
                                    if (isset($json['reading']) && strtoupper(trim($json['reading'])) === 'A') $achievements[] = 'Membaca';
                                    if (isset($json['writing']) && strtoupper(trim($json['writing'])) === 'A') $achievements[] = 'Menulis';
                                    if (isset($json['counting']) && strtoupper(trim($json['counting'])) === 'A') $achievements[] = 'Berhitung';
                                } else {
                                    $scoreStrUpper = strtoupper($scoreStr);
                                    if (trim($scoreStrUpper) === 'A') {
                                        $achievements = ['Membaca', 'Menulis', 'Berhitung'];
                                    } else {
                                        if (preg_match('/(?:MEMBACA|B)\s*[:=]?\s*A/i', $scoreStrUpper)) $achievements[] = 'Membaca';
                                        if (preg_match('/(?:MENULIS|T)\s*[:=]?\s*A/i', $scoreStrUpper)) $achievements[] = 'Menulis';
                                        if (preg_match('/(?:BERHITUNG|H)\s*[:=]?\s*A/i', $scoreStrUpper)) $achievements[] = 'Berhitung';
                                    }
                                }
                            }
                            
                            // Lock if not all A
                            if (count($achievements) < 3) {
                                $isLockedCalistung = true;
                                $calistungMsg = 'Mohon maaf ananda belum bisa pindah eskul karena harus fokus pada calistung.';
                            }
                        }
                    }

                    $isGrade6Lock = false;
                    $grade6Msg = "";
                    if ($activeYear && $activeYear->active_semester == '2' && str_starts_with($student->class, '6')) {
                        $isGrade6Lock = true;
                        $grade6Msg = "Khusus Kelas 6 di Semester 2 berfokus pada Program Tahfidz per kelas dan tidak perlu mengisi pilihan eskul lagi.";
                    }
                }
                
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'current_eskul' => $currentEskul,
                    'is_locked' => $isLockedCalistung || $isGrade6Lock || ($isAlreadyRegistered && $activeYear && $activeYear->active_semester == '1'),
                    'lock_message' => $isGrade6Lock ? $grade6Msg : ($isLockedCalistung ? $calistungMsg : ($isAlreadyRegistered && $activeYear && $activeYear->active_semester == '1' ? $alreadyRegisteredMsg : '')),
                    'is_already_registered' => $isAlreadyRegistered,
                    'already_registered_msg' => $alreadyRegisteredMsg,
                    'can_choose_sesi_2' => ($activeYear && $activeYear->active_semester == '2' && !$isLockedCalistung && str_starts_with($student->class, '1'))
                ];
            });

        return response()->json($students);
    }

    public function store(Request $request)
    {
        $quota = Setting::where('key', 'eskul_quota')->value('value') ?? 25;

        $quotaValidator = function($attribute, $value, $fail) use ($quota) {
            $activeYear = AcademicYear::where('is_active', true)->first();
            $yearId = $activeYear ? $activeYear->id : null;
            $semester = $activeYear ? $activeYear->active_semester : '1';

            $eskulToCheck = Eskul::find($value);
            if (!$eskulToCheck) return;

            $count = $eskulToCheck->students()
                ->wherePivot('academic_year_id', $yearId)
                ->wherePivot('semester', $semester)
                ->count();
                
            if ($count >= $quota) {
                $fail('Mohon Maaf Eskul "' . $eskulToCheck->name . '" sudah memenuhi kuota silahkan pilih eskul lain Terimakasih.');
            }
        };

        $groupValidator = function($attribute, $value, $fail) use ($request) {
            $eskulToCheck = Eskul::find($value);
            if (!$eskulToCheck) return;

            $groups = $eskulToCheck->target_groups; // pakai accessor dari model
            if (in_array('all', $groups)) return; // Semua kelas boleh

            $class = $request->class;
            $studentGroup = null;
            if ($class) {
                if (str_starts_with($class, '1')) {
                    $studentGroup = 'sesi_1';

                    // Cek apakah Semester 2 dan siswa lulus Calistung (semua A)
                    $activeYear = AcademicYear::where('is_active', true)->first();
                    if ($activeYear && $activeYear->active_semester == '2') {
                        $student = Student::find($request->student_id);
                        if ($student) {
                            $calistungEskul = Eskul::where('name', 'like', '%Calistung%')->first();
                            if ($calistungEskul) {
                                $grade = \App\Models\Grade::where('student_id', $student->id)
                                    ->where('eskul_id', $calistungEskul->id)
                                    ->where('academic_year_id', $activeYear->id)
                                    ->first();

                                if ($grade) {
                                    $achievements = [];
                                    $json = json_decode($grade->score, true);
                                    if (is_array($json)) {
                                        if (isset($json['reading']) && strtoupper(trim($json['reading'])) === 'A') $achievements[] = 'Membaca';
                                        if (isset($json['writing']) && strtoupper(trim($json['writing'])) === 'A') $achievements[] = 'Menulis';
                                        if (isset($json['counting']) && strtoupper(trim($json['counting'])) === 'A') $achievements[] = 'Berhitung';
                                    }

                                    if (count($achievements) === 3) {
                                        // Lulus Calistung! Boleh pilih Sesi 2 (Kelas 2)
                                        if ($eskulToCheck->isForGroup('sesi_2')) {
                                            return; // VALID!
                                        }
                                    }
                                }
                            }
                        }
                    }
                } elseif (str_starts_with($class, '2')) {
                    $studentGroup = 'sesi_2';
                } elseif (str_starts_with($class, '3')) {
                    $studentGroup = 'sesi_3';
                } elseif (str_starts_with($class, '4') || str_starts_with($class, '5') || str_starts_with($class, '6')) {
                    $studentGroup = 'sesi_4';
                }
            }

            if ($studentGroup && !$eskulToCheck->isForGroup($studentGroup)) {
                $fail('Mohon maaf, eskul "' . $eskulToCheck->name . '" tidak diperuntukkan bagi kelas Anda.');
            }
        };

        $request->validate([
            'class' => 'required|string',
            'student_id' => 'required|exists:students,id',
            'parent_phone' => 'required|string|min:10',
            'eskul_1' => ['required', 'exists:eskuls,id', $quotaValidator, $groupValidator],
            'agreement' => 'required|accepted',
        ], [
            'student_id.required' => 'Silakan pilih Nama Siswa dari daftar.',
            'parent_phone.required' => 'Nomor WhatsApp wajib diisi untuk notifikasi.',
            'agreement.accepted' => 'Anda harus menyetujui pernyataan untuk melanjutkan.',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->withErrors(['msg' => 'Tidak ada tahun ajaran aktif. Silakan hubungi admin.']);
        }

        // Fetch Student
        $student = Student::findOrFail($request->student_id);

        // --- NEW LOGIC: Prevent Grade 6 Enrollment in Semester 2 ---
        if ($activeYear && $activeYear->active_semester == '2' && str_starts_with($student->class, '6')) {
            return back()->withErrors(['student_id' => 'Mohon maaf, khusus kelas 6 di Semester 2 berfokus pada Program Tahfidz dan tidak diperkenankan mendaftar eskul pilihan lainnya.']);
        }

        // Update student phone number if it's new
        $student->update(['parent_phone' => $request->parent_phone]);

        // Determine active semester logic (Year Based now)
        $semester = $activeYear ? $activeYear->active_semester : '1';

        // --- FIXED LOGIC: Strict Backend Check for Semester 1 (Prevent overwrite) ---
        if ($activeYear && $activeYear->active_semester == '1') {
            $existingEskul = \Illuminate\Support\Facades\DB::table('student_eskul')
                ->where('student_id', $student->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('semester', '1')
                ->first();
                
            if ($existingEskul) {
                return back()->withErrors(['eskul_1' => 'Gagal: Ananda sudah terdaftar di eskul. Pilihan awal di Semester 1 tidak dapat diganti lewat form ini. Silakan hubungi wali kelas.']);
            }
        }

        $chosenEskul = Eskul::find($request->eskul_1);

        // --- FIXED LOGIC: Transaction with lock to prevent Race Condition ---
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $activeYear, $semester, $student, $quota) {
                // Secondary quota check within transaction to prevent race conditions
                $currentCount = \Illuminate\Support\Facades\DB::table('student_eskul')
                    ->where('eskul_id', $request->eskul_1)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester', $semester)
                    ->lockForUpdate()
                    ->count();

                if ($currentCount >= $quota) {
                    throw new \Exception('Mohon Maaf, kuota eskul baru saja terisi penuh beberapa saat lalu. Silakan pilih eskul lain.');
                }

                // Delete existing choices if they bypass the semester 1 block (usually for Sem 2 Transfer)
                \Illuminate\Support\Facades\DB::table('student_eskul')
                    ->where('student_id', $student->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester', $semester)
                    ->delete();

                // Attach new choices with context
                $student->eskuls()->attach($request->eskul_1, [
                    'academic_year_id' => $activeYear->id,
                    'semester' => $semester
                ]);
            });
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['eskul_1' => $e->getMessage()]);
        }
        
        // --- Create Submission Log (Audit Trail) ---
        try {
            \App\Models\SubmissionLog::create([
                'student_name' => $student->name,
                'student_class' => $student->class,
                'choice_1' => $chosenEskul->name ?? 'Unknown',
                'parent_phone' => $request->parent_phone,
                'choice_2' => null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SubmissionLog Error: ' . $e->getMessage());
        }
        // ---------------------------------------------

        // --- Send WhatsApp Notification ---
        if ($chosenEskul) {
            $template = \App\Models\Setting::where('key', 'wa_message_template')->value('value');
            
            if (!empty($template)) {
                $semesterName = $semester == '1' ? 'Ganjil' : 'Genap';
                $waMessage = str_replace(
                    ['{nama_siswa}', '{kelas}', '{nama_eskul}', '{tahun_ajaran}', '{semester}'],
                    [$student->name, $student->class, $chosenEskul->name, $activeYear->name ?? '-', $semesterName],
                    $template
                );
            } else {
                $waMessage = view('messages.whatsapp_registration', [
                    'student' => $student,
                    'chosenEskul' => $chosenEskul,
                    'semester' => $semester,
                    'activeYear' => $activeYear
                ])->render();
            }

            $formattedNumber = \App\Services\WhatsappService::formatNumber($request->parent_phone);
            \App\Services\WhatsappService::send($formattedNumber, $waMessage);
        }
        
        return redirect()->route('pilihan-eskul.success')->with('student_name', $student->name);
    }

    public function success()
    {
        return view('pilihan_eskul.success');
    }
}
