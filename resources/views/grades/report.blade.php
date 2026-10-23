@extends('layouts.app')

@section('title', 'Laporan Nilai')
@section('page-title', 'Laporan Nilai')

@push('styles')
<style>
    .score-cell { font-weight: 600; text-align: center; }
    @media print {
        .back-btn { display: none; }
        .card { box-shadow: none; border: none; }
    }
</style>
@endpush

@section('content')
<div class="no-print" style="margin-bottom: 1.25rem;">
    @if(Auth::user()->role === 'teacher')
    <a href="{{ route('grades.create', ['eskul_id' => $selectedEskul->id ?? Auth::user()->eskul_id, 'type' => 'daily', 'date' => date('Y-m-d')]) }}" style="display: inline-flex; align-items: center; gap: 8px; color: #475569; background: #ffffff; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 999px; font-size: 0.86rem; font-weight: 700; text-decoration: none; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: all 0.2s ease;">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Lembar Penilaian</span>
    </a>
    @else
    <a href="{{ route('grades.index') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #475569; background: #ffffff; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 999px; font-size: 0.86rem; font-weight: 700; text-decoration: none; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: all 0.2s ease;">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Pemilihan Penilaian</span>
    </a>
    @endif
</div>

<div class="card" style="max-width: 1000px; margin: 0 auto; border-radius: 18px; border: 1.5px solid #e2e8f0; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">
    <div style="text-align: center; margin-bottom: 2rem;">
        <div style="display: inline-flex; align-items: center; gap: 6px; background: #f5f3ff; color: #7c3aed; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; margin-bottom: 8px;">
            <i class="fas fa-file-invoice"></i> Rekapitulasi Penilaian
        </div>
        <h2 style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 5px;">REKAP NILAI EKSTRAKURIKULER</h2>
        <h3 style="font-size: 1.15rem; font-weight: 750; margin-bottom: 5px; color: #7c3aed;">{{ $selectedEskul->name ?? '-' }}</h3>
        <p style="color: #64748b; font-size: 0.88rem; margin: 0;">Pembina: <strong>{{ $selectedEskul->instructor_name ?? '-' }}</strong></p>
    </div>

    @if($selectedEskul)
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
                <th rowspan="2" style="vertical-align: middle;">Nama Siswa</th>
                <th rowspan="2" style="vertical-align: middle;">Kelas</th>
                <th rowspan="2" style="vertical-align: middle;">Materi</th>
                <th colspan="3" style="text-align: center;">Penilaian</th>
            </tr>
            <tr>
                <th style="text-align: center;">Harian (Rata-rata/Terakhir)</th>
                <th style="text-align: center;">SAS 1</th>
                <th style="text-align: center;">SAS 2</th>
            </tr>
        </thead>
        <tbody>
            @php
                $isCalistung = $selectedEskul->is_lockable ?? false;
            @endphp

            @foreach($students as $index => $student)
            @php
                $studentGrades = $grades[$student->id] ?? collect([]);
                
                $dailyGrades = $studentGrades->where('type', 'daily');
                $sas1Raw = $studentGrades->where('type', 'sas1')->first()->score ?? null;
                $sas2Raw = $studentGrades->where('type', 'sas2')->first()->score ?? null;
                
                // Decode if JSON
                $dailyScore = '-';
                $dailyJson = null;
                if ($dailyGrades->isNotEmpty()) {
                    $latest = $dailyGrades->sortByDesc('date')->first();
                    $dailyScore = $latest->score;
                    $dailyJson = json_decode($dailyScore, true);
                }
                
                $sas1Json = json_decode($sas1Raw, true);
                $sas2Json = json_decode($sas2Raw, true);

                $isStudentCalistung = $isCalistung && \Illuminate\Support\Str::startsWith($student->class, '1');
            @endphp
            
            @if($isStudentCalistung)
                <!-- Row for Membaca -->
                <tr>
                    <td rowspan="3" style="text-align: center; vertical-align: middle;">{{ $index + 1 }}</td>
                    <td rowspan="3" style="vertical-align: middle;">
                        <strong>{{ $student->name }}</strong>
                    </td>
                    <td rowspan="3" style="vertical-align: middle;">{{ $student->class }}</td>
                    <td style="padding: 4px 8px; background: #fdfdfd;">Membaca</td>
                    <td class="score-cell">{{ is_array($dailyJson) ? ($dailyJson['reading'] ?? '-') : '-' }}</td>
                    <td class="score-cell">{{ is_array($sas1Json) ? ($sas1Json['reading'] ?? '-') : '-' }}</td>
                    <td class="score-cell">{{ is_array($sas2Json) ? ($sas2Json['reading'] ?? '-') : '-' }}</td>
                </tr>
                <!-- Row for Menulis -->
                <tr>
                    <td style="padding: 4px 8px; background: #fdfdfd;">Menulis</td>
                    <td class="score-cell">{{ is_array($dailyJson) ? ($dailyJson['writing'] ?? '-') : '-' }}</td>
                    <td class="score-cell">{{ is_array($sas1Json) ? ($sas1Json['writing'] ?? '-') : '-' }}</td>
                    <td class="score-cell">{{ is_array($sas2Json) ? ($sas2Json['writing'] ?? '-') : '-' }}</td>
                </tr>
                <!-- Row for Menghitung -->
                <tr>
                    <td style="padding: 4px 8px; background: #fdfdfd;">Menghitung</td>
                    <td class="score-cell">{{ is_array($dailyJson) ? ($dailyJson['counting'] ?? '-') : '-' }}</td>
                    <td class="score-cell">{{ is_array($sas1Json) ? ($sas1Json['counting'] ?? '-') : '-' }}</td>
                    <td class="score-cell">{{ is_array($sas2Json) ? ($sas2Json['counting'] ?? '-') : '-' }}</td>
                </tr>
            @else
                <!-- Standard Row -->
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->class }}</td>
                    <td style="color: #aaa; font-style: italic;">Umum</td>
                    <td class="score-cell">{{ $dailyScore }} @if($dailyGrades->count() > 1) <small>({{ $dailyGrades->count() }}x)</small> @endif</td>
                    <td class="score-cell">{{ $sas1Raw ?? '-' }}</td>
                    <td class="score-cell">{{ $sas2Raw ?? '-' }}</td>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @else
        <p style="text-align: center; color: #999; padding: 2rem;">Data tidak ditemukan.</p>
    @endif
    
    <div style="margin-top: 2rem; text-align: right;" class="no-print">
        <button onclick="window.print()" class="btn-submit" style="background: #333; box-shadow: none; display: inline-flex; width: auto;">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
    </div>
</div>
@endsection
