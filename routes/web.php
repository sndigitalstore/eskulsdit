<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// DEBUG ROUTE (Temporary)


Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-migration', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return 'Migrasi berhasil dijalankan!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Temporary: Seed all school classes 1A-6C
Route::get('/seed-school-classes', function () {
    try {
        $classes = [
            '1A', '1B', '1C',
            '2A', '2B', '2C',
            '3A', '3B', '3C',
            '4A', '4B', '4C',
            '5A', '5B', '5C',
            '6A', '6B', '6C',
        ];
        $added = [];
        foreach ($classes as $class) {
            \App\Models\SchoolClass::firstOrCreate(['name' => $class]);
            $added[] = $class;
        }
        return 'Berhasil! Kelas yang ditambahkan: ' . implode(', ', $added) .
               '<br><br><b>Total kelas di database: ' . \App\Models\SchoolClass::count() . '</b>' .
               '<br><br><i>Silakan hapus route ini setelah selesai.</i>';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'username' => ['required'],
        'password' => ['required'],
    ]);

    $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
    $activeYearId = $activeYear ? $activeYear->id : null;

    $user = \App\Models\User::where('username', $request->username)
        ->where(function($query) use ($activeYearId) {
            $query->where('role', 'admin')
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

// Public Routes for Parents
Route::get('/pilihan-eskul', [\App\Http\Controllers\EskulSelectionController::class, 'create'])->name('pilihan-eskul.form');
Route::get('/pilihan-eskul/students', [\App\Http\Controllers\EskulSelectionController::class, 'getStudentsByClass'])->name('pilihan-eskul.students');
Route::post('/pilihan-eskul', [\App\Http\Controllers\EskulSelectionController::class, 'store'])->name('pilihan-eskul.store');
Route::get('/pilihan-eskul/sukses', [\App\Http\Controllers\EskulSelectionController::class, 'success'])->name('pilihan-eskul.success');

Route::get('/cek-status', [\App\Http\Controllers\StudentStatusController::class, 'index'])->name('student-status.index');
Route::get('/cek-status/students', [\App\Http\Controllers\StudentStatusController::class, 'getStudents'])->name('student-status.students');
Route::get('/api/students/search', [\App\Http\Controllers\Api\StudentApiController::class, 'search'])->name('api.students.search');
Route::post('/cek-status/cari', [\App\Http\Controllers\StudentStatusController::class, 'search'])->name('student-status.search');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::post('students/bulk', [\App\Http\Controllers\StudentController::class, 'store_bulk'])->name('students.store_bulk');
    Route::post('students/import-excel', [\App\Http\Controllers\StudentController::class, 'importExcel'])->name('students.import_excel');
    Route::post('students/assign-grade-6-tahfidz', [\App\Http\Controllers\StudentController::class, 'assignGrade6Tahfidz'])->name('students.assign_grade_6_tahfidz');
    Route::delete('students/bulk-destroy', [\App\Http\Controllers\StudentController::class, 'destroy_bulk'])->name('students.destroy_bulk');
    Route::get('students/backup', [\App\Http\Controllers\StudentController::class, 'backup'])->name('students.backup');
    Route::resource('students', \App\Http\Controllers\StudentController::class);
    Route::get('/promotions', [\App\Http\Controllers\PromotionController::class, 'index'])->name('promotions.index');
    Route::post('/promotions', [\App\Http\Controllers\PromotionController::class, 'promote'])->name('promotions.promote');
    Route::get('students/{student}/card', [\App\Http\Controllers\StudentController::class, 'card'])->name('students.card');
    Route::get('/eskuls', [\App\Http\Controllers\EskulController::class, 'index'])->name('eskuls.index');
    Route::post('/eskuls', [\App\Http\Controllers\EskulController::class, 'store'])->name('eskuls.store');
    Route::delete('/eskuls/{eskul}', [\App\Http\Controllers\EskulController::class, 'destroy'])->name('eskuls.destroy');
    Route::post('/eskuls/bulk-update-schedule', [\App\Http\Controllers\EskulController::class, 'bulkUpdateSchedule'])->name('eskuls.bulk-update-schedule');
    Route::put('/eskuls/{eskul}', [\App\Http\Controllers\EskulController::class, 'update'])->name('eskuls.update');
    Route::get('/eskuls/{eskul}/export', [\App\Http\Controllers\EskulController::class, 'export'])->name('eskuls.export');

    Route::get('/attendance/report', [\App\Http\Controllers\AttendanceController::class, 'report'])->name('attendance.report');
    Route::get('/attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [\App\Http\Controllers\AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');

    Route::get('/grades/import', [\App\Http\Controllers\GradeImportController::class, 'index'])->name('grades.import');
    Route::post('/grades/import/template', [\App\Http\Controllers\GradeImportController::class, 'downloadTemplate'])->name('grades.import.template');
    Route::post('/grades/import/process', [\App\Http\Controllers\GradeImportController::class, 'import'])->name('grades.import.process');

    Route::get('/grades', [\App\Http\Controllers\GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/create', [\App\Http\Controllers\GradeController::class, 'create'])->name('grades.create');
    Route::post('/grades', [\App\Http\Controllers\GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/report', [\App\Http\Controllers\GradeController::class, 'report'])->name('grades.report');
    
    Route::get('/reports/calistung-graduates-print', [\App\Http\Controllers\ReportController::class, 'printCalistungGraduates'])->name('reports.print-calistung-graduates');
    Route::get('/reports/print-full', [\App\Http\Controllers\ReportController::class, 'printFullClass'])->name('reports.print-full');
    Route::get('/reports/print-recap-class', [\App\Http\Controllers\ReportController::class, 'printRecapClass'])->name('reports.print-recap-class');
    Route::get('/reports/export-class', [\App\Http\Controllers\ReportController::class, 'exportClass'])->name('reports.export-class');
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/calistung-graduates', [\App\Http\Controllers\ReportController::class, 'exportCalistung'])->name('reports.calistung-graduates');
    
    Route::resource('academic-years', \App\Http\Controllers\AcademicYearController::class);
    Route::post('academic-years/{academic_year}/activate', [\App\Http\Controllers\AcademicYearController::class, 'activate'])->name('academic-years.activate');
    Route::post('academic-years/{academic_year}/copy-semester', [\App\Http\Controllers\AcademicYearController::class, 'copySemesterData'])->name('academic-years.copy-semester');

    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/clear-logs', [\App\Http\Controllers\SettingController::class, 'clearLogs'])->name('settings.clear-logs');
    Route::post('/settings/profile', [\App\Http\Controllers\SettingController::class, 'updateProfile'])->name('settings.update-profile');

    Route::get('/global-search', [\App\Http\Controllers\GlobalSearchController::class, 'index'])->name('global-search');
    
    Route::get('teachers/print', [\App\Http\Controllers\TeacherController::class, 'print'])->name('teachers.print');
    Route::get('teachers/bulk', [\App\Http\Controllers\TeacherController::class, 'bulk'])->name('teachers.bulk');
    Route::post('teachers/bulk', [\App\Http\Controllers\TeacherController::class, 'storeBulk'])->name('teachers.store_bulk');
    Route::post('teachers/reset-password', [\App\Http\Controllers\TeacherController::class, 'resetAllPasswords'])->name('teachers.reset-all');
    Route::resource('teachers', \App\Http\Controllers\TeacherController::class);
    Route::get('/achievements/print', [\App\Http\Controllers\AchievementController::class, 'print'])->name('achievements.print');
    
    // Activity Logs & Announcements
    Route::get('/logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('logs.index');
    Route::delete('/logs/clear', [\App\Http\Controllers\ActivityLogController::class, 'clear'])->name('logs.clear');
    Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class)->only(['index', 'store', 'destroy']);
    Route::get('/guide', [\App\Http\Controllers\GuideController::class, 'index'])->name('guide.index');
    
    Route::get('/achievements/bulk', [\App\Http\Controllers\AchievementController::class, 'bulk'])->name('achievements.bulk');
    Route::post('/achievements/bulk', [\App\Http\Controllers\AchievementController::class, 'storeBulk'])->name('achievements.store_bulk');
    Route::resource('achievements', \App\Http\Controllers\AchievementController::class);

    Route::get('/teacher-attendance/export', [\App\Http\Controllers\TeacherAttendanceController::class, 'export'])->name('teacher-attendance.export');
    Route::resource('teacher-attendance', \App\Http\Controllers\TeacherAttendanceController::class)->only(['index', 'store', 'destroy']);

    // Import Portal Satu Pintu
    Route::get('/import-portal', [\App\Http\Controllers\ImportPortalController::class, 'index'])->name('import-portal.index');
    Route::get('/import-portal/template', [\App\Http\Controllers\ImportPortalController::class, 'downloadTemplate'])->name('import-portal.template');
    Route::post('/import-portal/import', [\App\Http\Controllers\ImportPortalController::class, 'import'])->name('import-portal.import');

});
