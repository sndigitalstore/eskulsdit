<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// DEBUG ROUTE (Temporary)


Route::get('/', function () {
    return view('welcome');
});

Route::get('/offline', function () {
    return view('offline');
})->name('offline');



Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'username' => ['required'],
        'password' => ['required'],
    ]);

    // Auto-create default curriculum account if it doesn't exist yet on live database
    if ($request->username === 'kurikulum' && !\App\Models\User::where('username', 'kurikulum')->where('role', 'curriculum')->exists()) {
        \App\Models\User::create([
            'name' => 'Tim Kurikulum SDIT AN NADZIR',
            'username' => 'kurikulum',
            'role' => 'curriculum',
            'email' => 'kurikulum@school.com',
            'password' => \Illuminate\Support\Facades\Hash::make('kurikulum123'),
        ]);
    }

    $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
    $activeYearId = $activeYear ? $activeYear->id : null;

    $user = \App\Models\User::where('username', $request->username)
        ->where(function($query) use ($activeYearId) {
            $query->whereIn('role', ['admin', 'headmaster', 'curriculum'])
                  ->orWhere('academic_year_id', $activeYearId);
        })
        ->first();

    if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'username' => 'Login gagal! Username atau password salah, atau akun Anda tidak aktif di Tahun Ajaran ini.',
    ])->onlyInput('username');
});

Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
});

// Public Routes for Parents & Status Check (Protected with Rate Limiting)
Route::middleware(['throttle:30,1'])->group(function () {
    Route::get('/pilihan-eskul', [\App\Http\Controllers\EskulSelectionController::class, 'create'])->name('pilihan-eskul.form');
    Route::get('/pilihan-eskul/students', [\App\Http\Controllers\EskulSelectionController::class, 'getStudentsByClass'])->name('pilihan-eskul.students');
    Route::post('/pilihan-eskul', [\App\Http\Controllers\EskulSelectionController::class, 'store'])->name('pilihan-eskul.store');
    Route::get('/pilihan-eskul/sukses', [\App\Http\Controllers\EskulSelectionController::class, 'success'])->name('pilihan-eskul.success');

    Route::get('/cek-status', [\App\Http\Controllers\StudentStatusController::class, 'index'])->name('student-status.index');
    Route::get('/status', [\App\Http\Controllers\StudentStatusController::class, 'index'])->name('status.index');
    Route::get('/cek-status/students', [\App\Http\Controllers\StudentStatusController::class, 'getStudents'])->name('student-status.students');
    Route::get('/api/students/search', [\App\Http\Controllers\Api\StudentApiController::class, 'search'])->name('api.students.search');
    Route::post('/cek-status/cari', [\App\Http\Controllers\StudentStatusController::class, 'search'])->name('student-status.search');

    // Public Routes for Substitute Teacher Attendance (Option 1)
    Route::get('/substitute-attendance/{token}', [\App\Http\Controllers\SubstituteAttendanceController::class, 'show'])->name('substitute.attendance.show');
    Route::post('/substitute-attendance/{token}', [\App\Http\Controllers\SubstituteAttendanceController::class, 'store'])->name('substitute.attendance.store');

    // Public Routes for Munaqosah Registration
    Route::get('/daftar-munaqosah', [\App\Http\Controllers\MunaqosahController::class, 'publicRegisterForm'])->name('munaqosah.public-register');
    Route::post('/daftar-munaqosah', [\App\Http\Controllers\MunaqosahController::class, 'publicRegisterStore'])->name('munaqosah.public-register.store');
    Route::get('/daftar-munaqosah/sukses/{id}', [\App\Http\Controllers\MunaqosahController::class, 'publicRegisterSuccess'])->name('munaqosah.public-register.success');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::post('students/bulk', [\App\Http\Controllers\StudentController::class, 'store_bulk'])->name('students.store_bulk');
    Route::post('students/import-excel', [\App\Http\Controllers\StudentController::class, 'importExcel'])->name('students.import_excel');
    Route::post('students/assign-grade-6-tahfidz', [\App\Http\Controllers\StudentController::class, 'assignGrade6Tahfidz'])->name('students.assign_grade_6_tahfidz');
    Route::delete('students/bulk-destroy', [\App\Http\Controllers\StudentController::class, 'destroy_bulk'])->name('students.destroy_bulk');
    Route::get('students/backup', [\App\Http\Controllers\StudentController::class, 'backup'])->name('students.backup');
    Route::resource('students', \App\Http\Controllers\StudentController::class);
    Route::get('/promotions', [\App\Http\Controllers\PromotionController::class, 'index'])->name('promotions.index');
    Route::post('/promotions', [\App\Http\Controllers\PromotionController::class, 'promote'])->name('promotions.promote');
    Route::get('students/{student}/card', [\App\Http\Controllers\StudentController::class, 'card'])->name('students.card');
    Route::middleware(['role:admin,headmaster,teacher'])->group(function () {
        Route::get('/eskuls', [\App\Http\Controllers\EskulController::class, 'index'])->name('eskuls.index');
        Route::post('/eskuls', [\App\Http\Controllers\EskulController::class, 'store'])->name('eskuls.store');
        Route::delete('/eskuls/{eskul}', [\App\Http\Controllers\EskulController::class, 'destroy'])->name('eskuls.destroy');
        Route::post('/eskuls/bulk-update-schedule', [\App\Http\Controllers\EskulController::class, 'bulkUpdateSchedule'])->name('eskuls.bulk-update-schedule');
        Route::put('/eskuls/{eskul}', [\App\Http\Controllers\EskulController::class, 'update'])->name('eskuls.update');
        Route::patch('/eskuls/{eskul}/quick-update', [\App\Http\Controllers\EskulController::class, 'quickUpdate'])->name('eskuls.quick-update');
        Route::get('/eskuls/{eskul}/export', [\App\Http\Controllers\EskulController::class, 'export'])->name('eskuls.export');

        Route::get('/attendance/report', [\App\Http\Controllers\AttendanceController::class, 'report'])->name('attendance.report');
        Route::get('/attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/create', [\App\Http\Controllers\AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
        Route::delete('/attendance/delete-date', [\App\Http\Controllers\AttendanceController::class, 'destroyDate'])->name('attendance.destroy-date');

        Route::get('/teacher-attendance/export', [\App\Http\Controllers\TeacherAttendanceController::class, 'export'])->name('teacher-attendance.export');
        Route::resource('teacher-attendance', \App\Http\Controllers\TeacherAttendanceController::class)->only(['index', 'store', 'destroy']);

        Route::get('/grades/import', [\App\Http\Controllers\GradeImportController::class, 'index'])->name('grades.import');
        Route::post('/grades/import/template', [\App\Http\Controllers\GradeImportController::class, 'downloadTemplate'])->name('grades.import.template');
        Route::post('/grades/import/process', [\App\Http\Controllers\GradeImportController::class, 'import'])->name('grades.import.process');

        Route::get('/grades', [\App\Http\Controllers\GradeController::class, 'index'])->name('grades.index');
        Route::get('/grades/create', [\App\Http\Controllers\GradeController::class, 'create'])->name('grades.create');
        Route::post('/grades', [\App\Http\Controllers\GradeController::class, 'store'])->name('grades.store');
        Route::get('/grades/report', [\App\Http\Controllers\GradeController::class, 'report'])->name('grades.report');

        Route::get('/compliance', [\App\Http\Controllers\ComplianceController::class, 'index'])->name('compliance.index');
    });
    
    Route::get('/reports/print-all', [\App\Http\Controllers\ReportController::class, 'printAll'])->name('reports.print-all');
    Route::get('/reports/print-all-recap', [\App\Http\Controllers\ReportController::class, 'printAllRecap'])->name('reports.print-all-recap');
    Route::get('/reports/calistung-graduates', [\App\Http\Controllers\ReportController::class, 'exportCalistung'])->name('reports.calistung-graduates');
    Route::get('/reports/calistung-graduates-print', [\App\Http\Controllers\ReportController::class, 'printCalistungGraduates'])->name('reports.print-calistung-graduates');
    Route::get('/reports/print-full', [\App\Http\Controllers\ReportController::class, 'printFullClass'])->name('reports.print-full');
    Route::get('/reports/print-recap-class', [\App\Http\Controllers\ReportController::class, 'printRecapClass'])->name('reports.print-recap-class');
    Route::get('/reports/tilawati/print', [\App\Http\Controllers\ReportController::class, 'printTilawati'])->name('reports.tilawati.print');
    Route::get('/reports/tahfidz/print', [\App\Http\Controllers\ReportController::class, 'printTahfidz'])->name('reports.tahfidz.print');
    Route::get('/reports/tahfidz/raport', [\App\Http\Controllers\ReportController::class, 'raportTahfidz'])->name('reports.tahfidz.raport');
    Route::get('/reports/tahfidz/export', [\App\Http\Controllers\ReportController::class, 'exportTahfidz'])->name('reports.tahfidz.export');
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/run-migration', function () {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                return 'Migrasi berhasil dijalankan!';
            } catch (\Exception $e) {
                return 'Error: ' . $e->getMessage();
            }
        })->name('admin.run-migration');

        Route::resource('academic-years', \App\Http\Controllers\AcademicYearController::class);
        Route::post('academic-years/{academic_year}/activate', [\App\Http\Controllers\AcademicYearController::class, 'activate'])->name('academic-years.activate');
        Route::post('academic-years/{academic_year}/copy-semester', [\App\Http\Controllers\AcademicYearController::class, 'copySemesterData'])->name('academic-years.copy-semester');

        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
        Route::get('/settings/backup-database', [\App\Http\Controllers\SettingController::class, 'downloadBackup'])->name('settings.backup');
        Route::post('/settings/clear-logs', [\App\Http\Controllers\SettingController::class, 'clearLogs'])->name('settings.clear-logs');
        Route::post('/settings/profile', [\App\Http\Controllers\SettingController::class, 'updateProfile'])->name('settings.update-profile');

        Route::get('teachers/print', [\App\Http\Controllers\TeacherController::class, 'print'])->name('teachers.print');
        Route::get('teachers/bulk', [\App\Http\Controllers\TeacherController::class, 'bulk'])->name('teachers.bulk');
        Route::post('teachers/bulk', [\App\Http\Controllers\TeacherController::class, 'storeBulk'])->name('teachers.store_bulk');
        Route::post('teachers/reset-password', [\App\Http\Controllers\TeacherController::class, 'resetAllPasswords'])->name('teachers.reset-all');
        Route::post('teachers/{teacher}/reset-password', [\App\Http\Controllers\TeacherController::class, 'resetSinglePassword'])->name('teachers.reset-single');
        Route::resource('teachers', \App\Http\Controllers\TeacherController::class);

        Route::get('/logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('logs.index');
        Route::delete('/logs/clear', [\App\Http\Controllers\ActivityLogController::class, 'clear'])->name('logs.clear');

        Route::get('/import-portal', [\App\Http\Controllers\ImportPortalController::class, 'index'])->name('import-portal.index');
        Route::get('/import-portal/template', [\App\Http\Controllers\ImportPortalController::class, 'downloadTemplate'])->name('import-portal.template');
        Route::post('/import-portal/import', [\App\Http\Controllers\ImportPortalController::class, 'import'])->name('import-portal.import');
    });

    Route::get('/global-search', [\App\Http\Controllers\GlobalSearchController::class, 'index'])->name('global-search');
    Route::get('/achievements/print', [\App\Http\Controllers\AchievementController::class, 'print'])->name('achievements.print');
    
    // Announcements & Guide
    Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class)->only(['index', 'store', 'destroy']);
    Route::get('/guide', [\App\Http\Controllers\GuideController::class, 'index'])->name('guide.index');
    
    Route::get('/achievements/bulk', [\App\Http\Controllers\AchievementController::class, 'bulk'])->name('achievements.bulk');
    Route::post('/achievements/bulk', [\App\Http\Controllers\AchievementController::class, 'storeBulk'])->name('achievements.store_bulk');
    Route::resource('achievements', \App\Http\Controllers\AchievementController::class);

    // Digital Certificate Routes
    Route::get('/students/{student}/certificate', [\App\Http\Controllers\CertificateController::class, 'studentCertificate'])->name('students.certificate');
    Route::get('/achievements/{achievement}/certificate', [\App\Http\Controllers\CertificateController::class, 'achievementCertificate'])->name('achievements.certificate');

    // Mentoring Tilawati & Tahfidz Groups
    Route::get('/mentoring-management', [\App\Http\Controllers\MentoringGroupController::class, 'management'])->name('mentoring.management');
    Route::post('/mentoring-management/quick-assign', [\App\Http\Controllers\MentoringGroupController::class, 'quickAssign'])->name('mentoring.quick-assign');
    Route::get('/tilawati-groups/print', [\App\Http\Controllers\MentoringGroupController::class, 'printAll'])->name('tilawati-groups.print');
    Route::resource('tilawati-groups', \App\Http\Controllers\MentoringGroupController::class);

    Route::get('/tahfidz-groups/print', [\App\Http\Controllers\MentoringGroupController::class, 'printAll'])->name('tahfidz-groups.print');
    Route::resource('tahfidz-groups', \App\Http\Controllers\MentoringGroupController::class);

    Route::post('/mentoring-groups/{group}/students', [\App\Http\Controllers\MentoringGroupController::class, 'addStudents'])->name('mentoring-groups.add-students');
    Route::delete('/mentoring-groups/{group}/students/{student}', [\App\Http\Controllers\MentoringGroupController::class, 'removeStudent'])->name('mentoring-groups.remove-student');
    Route::get('/mentoring-groups/{group}/print', [\App\Http\Controllers\MentoringGroupController::class, 'printGroup'])->name('mentoring-groups.print-group');
    Route::get('/api/mentoring/students-by-class', [\App\Http\Controllers\MentoringGroupController::class, 'getStudentsByClass'])->name('mentoring.api.students-by-class');

    // Tahfidz Records (Kartu Hafalan Siswa)
    Route::get('/tahfidz-groups/{group}/students/{student}/card',   [\App\Http\Controllers\TahfidzRecordController::class, 'studentCard'])->name('tahfidz.student-card');
    Route::post('/tahfidz-groups/{group}/students/{student}/store', [\App\Http\Controllers\TahfidzRecordController::class, 'store'])->name('tahfidz.store');
    Route::delete('/tahfidz-records/{record}',                      [\App\Http\Controllers\TahfidzRecordController::class, 'destroy'])->name('tahfidz.destroy');
    Route::get('/tahfidz-groups/{group}/students/{student}/print',  [\App\Http\Controllers\TahfidzRecordController::class, 'printCard'])->name('tahfidz.print-card');
    Route::get('/tahfidz-groups/{group}/recap/print',               [\App\Http\Controllers\TahfidzRecordController::class, 'printGroup'])->name('tahfidz.print-group-recap');

    // Tahfidz Attendance (Absensi Kehadiran Sesi)
    Route::get('/tahfidz-groups/{group}/attendance',                       [\App\Http\Controllers\TahfidzAttendanceController::class, 'index'])->name('tahfidz.attendance.index');
    Route::get('/tahfidz-groups/{group}/attendance/create',                [\App\Http\Controllers\TahfidzAttendanceController::class, 'create'])->name('tahfidz.attendance.create');
    Route::post('/tahfidz-groups/{group}/attendance/store',                [\App\Http\Controllers\TahfidzAttendanceController::class, 'store'])->name('tahfidz.attendance.store');
    Route::get('/tahfidz-groups/{group}/attendance/session',               [\App\Http\Controllers\TahfidzAttendanceController::class, 'showSession'])->name('tahfidz.attendance.session');
    Route::delete('/tahfidz-groups/{group}/attendance/session',            [\App\Http\Controllers\TahfidzAttendanceController::class, 'destroySession'])->name('tahfidz.attendance.destroy-session');
    Route::get('/tahfidz-groups/{group}/attendance/session/print',         [\App\Http\Controllers\TahfidzAttendanceController::class, 'printSession'])->name('tahfidz.attendance.print-session');
    Route::get('/tahfidz-groups/{group}/attendance/recap/print',           [\App\Http\Controllers\TahfidzAttendanceController::class, 'printRecap'])->name('tahfidz.attendance.print-recap');

    // Tilawati Attendance & Rekap (4 Halaman Alat Peraga + 1 Halaman Buku Tilawati)
    Route::get('/tilawati-groups/{group}/attendance',                       [\App\Http\Controllers\TilawatiAttendanceController::class, 'index'])->name('tilawati.attendance.index');
    Route::get('/tilawati-groups/{group}/attendance/create',                [\App\Http\Controllers\TilawatiAttendanceController::class, 'create'])->name('tilawati.attendance.create');
    Route::post('/tilawati-groups/{group}/attendance/store',                [\App\Http\Controllers\TilawatiAttendanceController::class, 'store'])->name('tilawati.attendance.store');
    Route::get('/tilawati-groups/{group}/attendance/session',               [\App\Http\Controllers\TilawatiAttendanceController::class, 'showSession'])->name('tilawati.attendance.session');
    Route::delete('/tilawati-groups/{group}/attendance/session',            [\App\Http\Controllers\TilawatiAttendanceController::class, 'destroySession'])->name('tilawati.attendance.destroy-session');
    Route::get('/tilawati-groups/{group}/attendance/monthly',               [\App\Http\Controllers\TilawatiAttendanceController::class, 'monthly'])->name('tilawati.attendance.monthly');
    Route::get('/tilawati-groups/{group}/attendance/semester',              [\App\Http\Controllers\TilawatiAttendanceController::class, 'semester'])->name('tilawati.attendance.semester');
    Route::get('/tilawati-groups/{group}/attendance/session/print',         [\App\Http\Controllers\TilawatiAttendanceController::class, 'printSession'])->name('tilawati.attendance.print-session');
    Route::get('/tilawati-groups/{group}/attendance/monthly/print',         [\App\Http\Controllers\TilawatiAttendanceController::class, 'printMonthly'])->name('tilawati.attendance.print-monthly');

    // Munaqisy (Munaqosah Qur'an: Tilawati & Tahfidz)
    Route::get('/munaqosah',                                               [\App\Http\Controllers\MunaqosahController::class, 'index'])->name('munaqosah.index');
    Route::get('/munaqosah/register',                                      [\App\Http\Controllers\MunaqosahController::class, 'registerForm'])->name('munaqosah.register');
    Route::post('/munaqosah/register',                                     [\App\Http\Controllers\MunaqosahController::class, 'registerStore'])->name('munaqosah.register.store');
    Route::post('/munaqosah/register-group',                               [\App\Http\Controllers\MunaqosahController::class, 'registerGroupStore'])->name('munaqosah.register-group.store');
    Route::post('/munaqosah/{registration}/schedule',                      [\App\Http\Controllers\MunaqosahController::class, 'scheduleExam'])->name('munaqosah.schedule');
    Route::get('/munaqosah/{registration}/score',                         [\App\Http\Controllers\MunaqosahController::class, 'scoreForm'])->name('munaqosah.score');
    Route::post('/munaqosah/{registration}/score',                        [\App\Http\Controllers\MunaqosahController::class, 'scoreStore'])->name('munaqosah.score.store');
    Route::post('/munaqosah/{registration}/promote',                      [\App\Http\Controllers\MunaqosahController::class, 'promote'])->name('munaqosah.promote');
    Route::delete('/munaqosah/{registration}',                            [\App\Http\Controllers\MunaqosahController::class, 'destroy'])->name('munaqosah.destroy');
    Route::post('/munaqosah/examiners/store',                             [\App\Http\Controllers\MunaqosahController::class, 'examinerStore'])->name('munaqosah.examiners.store');
    Route::post('/munaqosah/examiners/{examiner}/toggle',                 [\App\Http\Controllers\MunaqosahController::class, 'examinerToggle'])->name('munaqosah.examiners.toggle');
    Route::delete('/munaqosah/examiners/{examiner}',                      [\App\Http\Controllers\MunaqosahController::class, 'examinerDestroy'])->name('munaqosah.examiners.destroy');
    Route::post('/munaqosah/schedules/store',                             [\App\Http\Controllers\MunaqosahController::class, 'scheduleStore'])->name('munaqosah.schedules.store');
    Route::delete('/munaqosah/schedules/{schedule}',                      [\App\Http\Controllers\MunaqosahController::class, 'scheduleDestroy'])->name('munaqosah.schedules.destroy');
    Route::get('/munaqosah/{registration}/print-certificate',             [\App\Http\Controllers\MunaqosahController::class, 'printCertificate'])->name('munaqosah.print-certificate');
    Route::get('/munaqosah/print/recap',                                  [\App\Http\Controllers\MunaqosahController::class, 'printRecap'])->name('munaqosah.print-recap');

    // Ujian Tasmi' Al-Qur'an (Tasmi' Bil Ghaib Sekali Duduk)
    Route::get('/tasmi',                                                  [\App\Http\Controllers\TasmiExamController::class, 'index'])->name('tasmi.index');
    Route::post('/tasmi',                                                 [\App\Http\Controllers\TasmiExamController::class, 'store'])->name('tasmi.store');
    Route::delete('/tasmi/{tasmi}',                                       [\App\Http\Controllers\TasmiExamController::class, 'destroy'])->name('tasmi.destroy');
    Route::get('/tasmi/{tasmi}/certificate',                              [\App\Http\Controllers\TasmiExamController::class, 'printCertificate'])->name('tasmi.certificate');
    Route::get('/tasmi/{tasmi}/report',                                   [\App\Http\Controllers\TasmiExamController::class, 'printReport'])->name('tasmi.report');

    // SMILE KARAKTER (Pemantauan Karakter Siswa)
    Route::middleware(['role:admin,headmaster,teacher'])->group(function () {
        Route::get('/character',                         [\App\Http\Controllers\CharacterDashboardController::class, 'dashboard'])->name('character.dashboard');
        Route::get('/character/report',                  [\App\Http\Controllers\CharacterDashboardController::class, 'report'])->name('character.report');
        Route::get('/character/report/print',            [\App\Http\Controllers\CharacterDashboardController::class, 'printClassReport'])->name('character.report.print');

        Route::get('/character-cases/print',             [\App\Http\Controllers\CharacterCaseController::class, 'print'])->name('character-cases.print');
        Route::resource('character-cases',               \App\Http\Controllers\CharacterCaseController::class);

        Route::get('/character-appreciations/print',     [\App\Http\Controllers\CharacterAppreciationController::class, 'print'])->name('character-appreciations.print');
        Route::resource('character-appreciations',       \App\Http\Controllers\CharacterAppreciationController::class);
    });

    // SMILE KURIKULUM (Raport Akademik STS 1, SAS 1, STS 2, SAS 2 & Mata Pelajaran)
    Route::get('/curriculum/academic-reports',                          [\App\Http\Controllers\AcademicReportController::class, 'index'])->name('academic-reports.index');
    Route::post('/curriculum/academic-reports/scores',                  [\App\Http\Controllers\AcademicReportController::class, 'storeScores'])->name('academic-reports.store-scores');
    Route::post('/curriculum/academic-reports/summaries',               [\App\Http\Controllers\AcademicReportController::class, 'storeSummaries'])->name('academic-reports.store-summaries');
    Route::get('/curriculum/academic-reports/print-all-students',        [\App\Http\Controllers\AcademicReportController::class, 'printAllStudents'])->name('academic-reports.print-all-students');
    Route::get('/curriculum/academic-reports/students/{student}/print', [\App\Http\Controllers\AcademicReportController::class, 'printStudent'])->name('academic-reports.print-student');
    Route::get('/curriculum/academic-reports/recap/print',              [\App\Http\Controllers\AcademicReportController::class, 'printClassRecap'])->name('academic-reports.print-recap');
    Route::get('/curriculum/academic-reports/export-template',          [\App\Http\Controllers\AcademicReportController::class, 'exportTemplate'])->name('academic-reports.export-template');
    Route::post('/curriculum/academic-reports/import-scores',           [\App\Http\Controllers\AcademicReportController::class, 'importScores'])->name('academic-reports.import-scores');
    Route::get('/curriculum/academic-reports/export-leger',             [\App\Http\Controllers\AcademicReportController::class, 'exportLeger'])->name('academic-reports.export-leger');

    Route::middleware(['role:admin,headmaster,curriculum'])->group(function () {
        Route::resource('curriculum/subjects', \App\Http\Controllers\SubjectController::class)->names('subjects');
    });

});




