<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubstituteToken;
use App\Models\Eskul;
use App\Models\Attendance;
use App\Models\AcademicYear;

class SubstituteAttendanceController extends Controller
{
    public function show($tokenStr)
    {
        $token = SubstituteToken::where('token', $tokenStr)->first();

        if (!$token || !$token->isValid()) {
            return view('attendance.substitute_expired');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $eskul = Eskul::with(['students' => function ($q) use ($activeYear) {
            $q->wherePivot('academic_year_id', $activeYear->id)
              ->where('status', '!=', 'graduated');
        }])->findOrFail($token->eskul_id);

        $date = $token->date->toDateString();
        $semester = $activeYear->active_semester ?? '1';

        $existingAttendance = Attendance::where('eskul_id', $eskul->id)
            ->where('date', $date)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester', $semester)
            ->get()
            ->keyBy('student_id');

        return view('attendance.substitute', compact('token', 'eskul', 'date', 'activeYear', 'semester', 'existingAttendance'));
    }

    public function store(Request $request, $tokenStr)
    {
        $token = SubstituteToken::where('token', $tokenStr)->first();

        if (!$token || !$token->isValid()) {
            return view('attendance.substitute_expired');
        }

        $request->validate([
            'attendance' => 'required|array',
            'attendance.*' => 'in:present,absent,sick,permission',
            'notes' => 'nullable|array',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $eskulId = $token->eskul_id;
        $date = $token->date->toDateString();
        $semester = $activeYear->active_semester ?? '1';
        $attendanceData = $request->attendance;
        $notes = $request->notes ?? [];

        foreach ($attendanceData as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'eskul_id' => $eskulId,
                    'date' => $date,
                    'academic_year_id' => $activeYear->id,
                    'semester' => $semester,
                ],
                [
                    'status' => $status,
                    'note' => $notes[$studentId] ?? null,
                ]
            );
        }

        $eskul = Eskul::find($eskulId);
        $eskulName = $eskul ? $eskul->name : 'Unknown';
        \App\Models\ActivityLog::create([
            'user_id' => null,
            'module' => 'Attendance',
            'action' => 'Create',
            'description' => "Absensi eskul {$eskulName} tanggal {$date} diisi oleh Guru Pengganti ({$token->substitute_name}) melalui tautan publik.",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('substitute.attendance.show', $tokenStr)->with('success', 'Absensi siswa berhasil disimpan oleh Guru Pengganti!');
    }
}
