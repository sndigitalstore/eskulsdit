<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Achievement;
use App\Models\Setting;
use App\Models\User;
use App\Models\AcademicYear;

class CertificateController extends Controller
{
    /**
     * Generate Piagam Keikutsertaan Ekstrakurikuler Siswa
     */
    public function studentCertificate($id)
    {
        $student = Student::with(['eskuls', 'academicYear'])->findOrFail($id);

        $headmasterSetting = Setting::where('key', 'headmaster_name')->value('value');
        $headmasterUser = User::where('role', 'headmaster')->first();
        $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : 'Nur\'asiah, S.Pd.I');

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearName = $student->academicYear ? $student->academicYear->name : ($activeYear ? $activeYear->name : date('Y'));

        return view('certificates.student', compact('student', 'headmasterName', 'yearName'));
    }

    /**
     * Generate Piagam Penghargaan Prestasi Siswa
     */
    public function achievementCertificate($id)
    {
        $achievement = Achievement::with(['student', 'academicYear'])->findOrFail($id);

        $headmasterSetting = Setting::where('key', 'headmaster_name')->value('value');
        $headmasterUser = User::where('role', 'headmaster')->first();
        $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : 'Nur\'asiah, S.Pd.I');

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearName = $achievement->academicYear ? $achievement->academicYear->name : ($activeYear ? $activeYear->name : date('Y'));

        return view('certificates.achievement', compact('achievement', 'headmasterName', 'yearName'));
    }
}
