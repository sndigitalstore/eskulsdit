@extends('layouts.app')

@section('title', 'Form Penilaian')
@section('page-title', 'Form Penilaian')

@push('styles')
<style>
    .radio-group { display: flex; gap: 15px; flex-wrap: wrap; }
    .radio-option { display: flex; align-items: center; gap: 5px; cursor: pointer; }
    .radio-option input { accent-color: #ff7eb3; transform: scale(1.2); }

    /* Mobile Responsive Card View */
    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr { display: block; }
        thead tr { position: absolute; top: -9999px; left: -9999px; } /* Hide Header */
        
        tr { 
            background: #fff; 
            margin-bottom: 1rem; 
            border: 1px solid #eee; 
            border-radius: 12px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 1rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        
        td { 
            border: none; 
            padding: 0; 
            position: relative; 
            width: 100%;
        }

        /* Number Column */
        td:first-child { 
            width: auto;
            font-weight: bold; 
            color: #555; 
            font-size: 1rem; 
            margin-bottom: 0px; 
            margin-right: 15px; 
        }
        
        /* Name Column */
        td:nth-child(2) {
            width: auto;
            flex: 1;
        }

        /* Grade Options */
        td:nth-child(3) {
            margin-top: 15px;
            width: 100%;
        }

        .radio-group {
            display: flex;
            flex-direction: column; /* Stack vertically for cleanliness */
            gap: 8px;
            background: transparent;
            border: none;
            padding: 5px 0;
        }

        .radio-option {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 0.95rem;
            border: 1px solid #eee;
            transition: background 0.2s;
        }

        .radio-option:active { background: #eef2f7; }
        
        /* Make the radio circle slightly larger */
        .radio-option input { transform: scale(1.3); margin-right: 10px; }
    }

    /* Calistung Styles */
    .calistung-block {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .calistung-row {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fdfdfd;
        padding: 5px 10px;
        border: 1px solid #f0f0f0;
        border-radius: 8px;
    }
    .calistung-label {
        width: 100px;
        font-weight: 500;
        font-size: 0.9rem;
    }
    .calistung-radios {
        display: flex;
        gap: 10px;
    }
    @media (max-width: 768px) {
        .calistung-row {
            flex-direction: column;
            align-items: flex-start;
        }
        .calistung-label {
            width: 100%;
            margin-bottom: 5px;
        }
    }
</style>
@endpush

@section('content')
<a href="{{ route('grades.index') }}" style="display: inline-block; margin-bottom: 1rem; color: #888; text-decoration: none;"><i class="fas fa-arrow-left"></i> Kembali</a>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    @if(!$contextYear->is_active || $contextYear->active_semester != $semester)
        <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffeeba;">
            <i class="fas fa-history"></i> <strong>Mode Riwayat:</strong> Anda sedang melihat/mengubah data untuk 
            <strong>Tahun {{ $contextYear->name }} Semester {{ $semester }}</strong>.
        </div>
    @endif

    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 2px solid #f0f0f0; padding-bottom: 1rem;">
        <div class="info-block">
            <p style="color: #888; margin-bottom: 5px; font-size: 0.9rem;">Ekstrakurikuler</p>
            <strong style="font-size: 1.1rem;">{{ $eskul->name }}</strong>
        </div>
        <div class="info-block" style="text-align: right;">
            <p style="color: #888; margin-bottom: 5px; font-size: 0.9rem;">Jenis Penilaian</p>
            <strong style="font-size: 1.1rem;">
                @if($type == 'daily') Nilai Harian ({{ \Carbon\Carbon::parse($date)->format('d M Y') }})
                @elseif($type == 'sas1') Nilai SAS 1
                @elseif($type == 'sas2') Nilai SAS 2
                @endif
            </strong>
        </div>
    </div>

    <form action="{{ route('grades.store') }}" method="POST">
        @csrf
        <input type="hidden" name="eskul_id" value="{{ $eskul->id }}">
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="academic_year_id" value="{{ $yearId }}">
        <input type="hidden" name="semester" value="{{ $semester }}">

        <table>
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th width="40%">Nama Siswa</th>
                    <th width="50%">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                @php
                    $score = $existingGrades[$student->id]->score ?? '';
                    $attendRec = $studentAttendance[$student->id] ?? null;
                    $isAbsent = $attendRec && in_array($attendRec->status, ['absent', 'sick', 'permission']);
                    $attendBadge = '';
                    $attendColor = '';
                    if ($attendRec) {
                        match($attendRec->status) {
                            'sick'       => [$attendBadge = 'Sakit',    $attendColor = '#f39c12'],
                            'permission' => [$attendBadge = 'Izin',     $attendColor = '#3498db'],
                            'absent'     => [$attendBadge = 'Alpha',    $attendColor = '#e74c3c'],
                            default      => [$attendBadge = 'Hadir',    $attendColor = '#27ae60'],
                        };
                    }
                @endphp
                <tr style="{{ $isAbsent ? 'opacity: 0.65; background: #fffbf0;' : '' }}">
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $student->name }}</strong><br>
                        <small style="color: #999;">{{ $student->class }}</small>
                        @if($attendRec)
                            <span style="
                                display: inline-block; margin-top: 4px;
                                background: {{ $attendColor }}22; 
                                color: {{ $attendColor }}; 
                                border: 1px solid {{ $attendColor }}44;
                                padding: 2px 8px; border-radius: 20px; 
                                font-size: 0.75rem; font-weight: 700;
                            ">
                                <i class="fas fa-{{ $attendRec->status == 'present' ? 'check-circle' : 'exclamation-circle' }}"></i>
                                {{ $attendBadge }}
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($isAbsent)
                            {{-- Student is absent: show info banner, no need to fill grade --}}
                            <div style="background: #fff8eb; border: 1px dashed #f39c12; border-radius: 10px; padding: 12px 15px; color: #d35400; font-size: 0.9rem;">
                                <i class="fas fa-ban"></i> 
                                Nilai tidak diisi — siswa <strong>{{ $attendBadge }}</strong> pada tanggal ini.
                                @if($attendRec->note)
                                    <br><small style="color:#999;">Keterangan: {{ $attendRec->note }}</small>
                                @endif
                            </div>
                        @else
                        @php
                            $isCalistungClass1 = ($isCalistung ?? false) && \Illuminate\Support\Str::startsWith($student->class, '1');
                            $jsonScore = json_decode($score, true);
                            $valReading = is_array($jsonScore) ? ($jsonScore['reading'] ?? '') : '';
                            $valWriting = is_array($jsonScore) ? ($jsonScore['writing'] ?? '') : '';
                            $valCounting = is_array($jsonScore) ? ($jsonScore['counting'] ?? '') : '';
                        @endphp

                        @if($isCalistungClass1)
                        <div class="calistung-block">
                            <!-- Membaca -->
                            <div class="calistung-row">
                                <span class="calistung-label">Membaca</span>
                                <div class="calistung-radios">
                                    <label class="radio-option"><input type="radio" name="grades[{{ $student->id }}][reading]" value="A" {{ $valReading == 'A' ? 'checked' : '' }} required> A</label>
                                    <label class="radio-option"><input type="radio" name="grades[{{ $student->id }}][reading]" value="B" {{ $valReading == 'B' ? 'checked' : '' }}> B</label>
                                    <label class="radio-option"><input type="radio" name="grades[{{ $student->id }}][reading]" value="C" {{ $valReading == 'C' ? 'checked' : '' }}> C</label>
                                </div>
                            </div>
                            <!-- Menulis -->
                            <div class="calistung-row">
                                <span class="calistung-label">Menulis</span>
                                <div class="calistung-radios">
                                    <label class="radio-option"><input type="radio" name="grades[{{ $student->id }}][writing]" value="A" {{ $valWriting == 'A' ? 'checked' : '' }} required> A</label>
                                    <label class="radio-option"><input type="radio" name="grades[{{ $student->id }}][writing]" value="B" {{ $valWriting == 'B' ? 'checked' : '' }}> B</label>
                                    <label class="radio-option"><input type="radio" name="grades[{{ $student->id }}][writing]" value="C" {{ $valWriting == 'C' ? 'checked' : '' }}> C</label>
                                </div>
                            </div>
                            <!-- Menghitung -->
                            <div class="calistung-row">
                                <span class="calistung-label">Menghitung</span>
                                <div class="calistung-radios">
                                    <label class="radio-option"><input type="radio" name="grades[{{ $student->id }}][counting]" value="A" {{ $valCounting == 'A' ? 'checked' : '' }} required> A</label>
                                    <label class="radio-option"><input type="radio" name="grades[{{ $student->id }}][counting]" value="B" {{ $valCounting == 'B' ? 'checked' : '' }}> B</label>
                                    <label class="radio-option"><input type="radio" name="grades[{{ $student->id }}][counting]" value="C" {{ $valCounting == 'C' ? 'checked' : '' }}> C</label>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="grades[{{ $student->id }}]" value="A" {{ $score == 'A' ? 'checked' : '' }} required> A (Sangat Baik)
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="grades[{{ $student->id }}]" value="B" {{ $score == 'B' ? 'checked' : '' }}> B (Baik)
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="grades[{{ $student->id }}]" value="C" {{ $score == 'C' ? 'checked' : '' }}> C (Cukup)
                            </label>
                        </div>
                        @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($students->isEmpty())
            <p style="text-align: center; color: #999; margin: 2rem;">Belum ada siswa di eskul ini.</p>
        @else
            <button type="submit" class="btn-submit" style="display: block; width: 200px; margin: 2rem auto 0; justify-content: center;">Simpan Nilai</button>
        @endif
    </form>
</div>
@endsection
