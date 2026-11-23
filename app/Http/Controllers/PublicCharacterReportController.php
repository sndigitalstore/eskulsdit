<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\CharacterCase;
use App\Models\CharacterAppreciation;
use App\Models\Setting;
use App\Services\ImageOptimizer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class PublicCharacterReportController extends Controller
{
    /**
     * Pastikan tabel dan kolom baru tersedia
     */
    protected function ensureSchema()
    {
        if (!Schema::hasColumn('character_cases', 'is_public_report')) {
            try {
                Artisan::call('migrate', ['--force' => true]);
            } catch (\Throwable $e) {
                // Ignore
            }
        }
    }

    /**
     * Halaman Utama Pengaduan Insiden & Apresiasi Karakter Publik (Tanpa Login)
     */
    public function index(Request $request)
    {
        $this->ensureSchema();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $yearId = $activeYear ? $activeYear->id : null;

        $classes = Student::forYear($yearId)
            ->select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        $students = Student::forYear($yearId)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')->orWhereNull('status');
            })
            ->select('id', 'name', 'nis', 'class')
            ->orderBy('name')
            ->get();

        // Buat Captcha Matematika Sederhana Ramah Anak
        $num1 = rand(2, 9);
        $num2 = rand(1, 8);
        session(['char_public_captcha' => $num1 + $num2]);

        $activeTab = $request->query('tab', 'pengaduan'); // 'pengaduan' atau 'apresiasi'

        $schoolName = Setting::get('school_name', 'SDIT AN NADZIR');
        $schoolPhone = Setting::get('school_phone', '0812-3456-7890');

        return view('public.character_report', compact(
            'classes', 'students', 'num1', 'num2', 'activeTab', 'schoolName', 'schoolPhone', 'activeYear'
        ));
    }

    /**
     * Simpan Pengaduan Insiden / Pelanggaran / Bullying (TPPK)
     */
    public function storeCase(Request $request)
    {
        $this->ensureSchema();

        // Verifikasi Captcha Anti-Spam
        $expectedCaptcha = session('char_public_captcha');
        if (empty($request->captcha) || (int)$request->captcha !== (int)$expectedCaptcha) {
            return back()->withInput()->with('error_case', 'Jawaban hitungan keamanan salah. Silakan coba lagi.');
        }

        $validated = $request->validate([
            'student_id'       => 'nullable|exists:students,id',
            'student_name_raw' => 'nullable|string|max:150',
            'student_class_raw'=> 'nullable|string|max:50',
            'date'             => 'required|date',
            'category'         => 'required|string|max:100',
            'tppk_category'    => 'required|string|max:50',
            'title'            => 'required|string|max:255',
            'description'      => 'required|string|max:3000',
            'location'         => 'nullable|string|max:150',
            'severity'         => 'required|in:ringan,sedang,berat',
            'reporter_type'    => 'required|string|max:50',
            'is_anonymous'     => 'nullable|boolean',
            'reporter_name'    => 'nullable|string|max:100',
            'reporter_contact' => 'nullable|string|max:100',
            'evidence'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        // Tentukan student_id (bisa dari dropdown, atau cari berdasar nama/kelas jika input bebas)
        $studentId = $validated['student_id'] ?? null;
        if (!$studentId && !empty($validated['student_name_raw'])) {
            $foundStudent = Student::forYear($activeYear ? $activeYear->id : null)
                ->where('name', 'like', '%' . trim($validated['student_name_raw']) . '%')
                ->first();
            if ($foundStudent) {
                $studentId = $foundStudent->id;
            }
        }

        if (!$studentId) {
            return back()->withInput()->with('error_case', 'Harap pilih atau sebutkan nama siswa yang dilaporkan.');
        }

        // Upload bukti foto/dokumen jika ada
        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            try {
                $file = $request->file('evidence');
                if (str_starts_with($file->getMimeType(), 'image/')) {
                    $evidencePath = ImageOptimizer::optimizeAndStore($file, 'character_evidence', 1200, 80);
                } else {
                    $evidencePath = $file->store('character_evidence', 'public');
                }
            } catch (\Throwable $e) {
                // Fallback store
                $evidencePath = $request->file('evidence')->store('character_evidence', 'public');
            }
        }

        $isAnonymous = !empty($validated['is_anonymous']);
        $reporterLabel = $isAnonymous 
            ? 'Warga Sekolah (Anonim / Rahasia)' 
            : (($validated['reporter_name'] ?? 'Pelapor') . ' [' . ucfirst($validated['reporter_type']) . ']');

        $case = CharacterCase::create([
            'academic_year_id'    => $activeYear ? $activeYear->id : null,
            'student_id'          => $studentId,
            'date'                => $validated['date'],
            'category'            => $validated['category'],
            'tppk_category'       => $validated['tppk_category'],
            'title'               => $validated['title'],
            'description'         => $validated['description'],
            'location'            => $validated['location'],
            'severity'            => $validated['severity'],
            'point'               => 0, // Poin awal 0 sampai diverifikasi oleh Kesiswaan/TPPK
            'status'              => 'dalam_pembinaan',
            'handling_level'      => 'wali_kelas',
            'reported_by'         => $reporterLabel,
            'parent_notified'     => false,
            'notes'               => 'Pengaduan Masuk dari Halaman Publik (TPPK).',
            'is_public_report'    => true,
            'reporter_type'       => $validated['reporter_type'],
            'reporter_contact'    => $isAnonymous ? null : ($validated['reporter_contact'] ?? null),
            'is_anonymous'        => $isAnonymous,
            'evidence_file'       => $evidencePath,
            'verification_status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('public.character.success', [
            'type' => 'pengaduan',
            'id'   => $case->id,
        ]);
    }

    /**
     * Simpan Apresiasi Kebaikan / Prestasi Siswa Publik
     */
    public function storeAppreciation(Request $request)
    {
        $this->ensureSchema();

        // Verifikasi Captcha Anti-Spam
        $expectedCaptcha = session('char_public_captcha');
        if (empty($request->captcha) || (int)$request->captcha !== (int)$expectedCaptcha) {
            return back()->withInput()->with('error_appr', 'Jawaban hitungan keamanan salah. Silakan coba lagi.');
        }

        $validated = $request->validate([
            'student_id'       => 'nullable|exists:students,id',
            'student_name_raw' => 'nullable|string|max:150',
            'date'             => 'required|date',
            'category'         => 'required|string|max:100',
            'dimension_p5'     => 'nullable|string|max:100',
            'title'            => 'required|string|max:255',
            'description'      => 'required|string|max:3000',
            'reporter_type'    => 'required|string|max:50',
            'is_anonymous'     => 'nullable|boolean',
            'reporter_name'    => 'nullable|string|max:100',
            'reporter_contact' => 'nullable|string|max:100',
            'evidence'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        $studentId = $validated['student_id'] ?? null;
        if (!$studentId && !empty($validated['student_name_raw'])) {
            $foundStudent = Student::forYear($activeYear ? $activeYear->id : null)
                ->where('name', 'like', '%' . trim($validated['student_name_raw']) . '%')
                ->first();
            if ($foundStudent) {
                $studentId = $foundStudent->id;
            }
        }

        if (!$studentId) {
            return back()->withInput()->with('error_appr', 'Harap pilih nama siswa yang diapresiasi.');
        }

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            try {
                $file = $request->file('evidence');
                if (str_starts_with($file->getMimeType(), 'image/')) {
                    $evidencePath = ImageOptimizer::optimizeAndStore($file, 'character_evidence', 1200, 80);
                } else {
                    $evidencePath = $file->store('character_evidence', 'public');
                }
            } catch (\Throwable $e) {
                $evidencePath = $request->file('evidence')->store('character_evidence', 'public');
            }
        }

        $isAnonymous = !empty($validated['is_anonymous']);
        $givenByLabel = $isAnonymous 
            ? 'Warga Sekolah / Masyarakat' 
            : (($validated['reporter_name'] ?? 'Masyarakat') . ' [' . ucfirst($validated['reporter_type']) . ']');

        $appreciation = CharacterAppreciation::create([
            'academic_year_id'    => $activeYear ? $activeYear->id : null,
            'student_id'          => $studentId,
            'date'                => $validated['date'],
            'category'            => $validated['category'],
            'dimension_p5'        => $validated['dimension_p5'] ?? 'Berakhlak Mulia',
            'title'               => $validated['title'],
            'description'         => $validated['description'],
            'point'               => 0, // Poin awal 0 sampai diverifikasi
            'reward'              => 'Apresiasi Warga Sekolah',
            'given_by'            => $givenByLabel,
            'notes'               => 'Apresiasi Masuk dari Halaman Publik.',
            'is_public_report'    => true,
            'reporter_type'       => $validated['reporter_type'],
            'reporter_contact'    => $isAnonymous ? null : ($validated['reporter_contact'] ?? null),
            'is_anonymous'        => $isAnonymous,
            'evidence_file'       => $evidencePath,
            'verification_status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('public.character.success', [
            'type' => 'apresiasi',
            'id'   => $appreciation->id,
        ]);
    }

    /**
     * Halaman Sukses Setelah Pengiriman Laporan Publik
     */
    public function success(Request $request)
    {
        $type = $request->query('type', 'pengaduan');
        $id = $request->query('id');

        $schoolName = Setting::get('school_name', 'SDIT AN NADZIR');
        $schoolPhone = Setting::get('school_phone', '0812-3456-7890');

        return view('public.character_success', compact('type', 'id', 'schoolName', 'schoolPhone'));
    }
}
