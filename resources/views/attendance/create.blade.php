@extends('layouts.app')

@section('title', 'Form Presensi')
@section('page-title', 'Form Presensi')

@push('styles')
<style>
    .radio-group { display: flex; gap: 15px; flex-wrap: wrap; }
    .radio-option { display: flex; align-items: center; gap: 5px; cursor: pointer; }
    .radio-option input { accent-color: #ff7eb3; transform: scale(1.2); }
    
    .color-present { color: #2ecc71; }
    .color-absent { color: #e74c3c; }
    .color-sick { color: #f1c40f; }
    .color-perm { color: #3498db; }

    .note-input { width: 100%; padding: 8px; border: 1px solid #eee; border-radius: 5px; }

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
            display: flex; /* Flexbox for layout */
            flex-wrap: wrap;
            align-items: center;
        }
        
        td { 
            border: none; 
            padding: 0; 
            position: relative;
            width: 100%; /* Default to full width (Inputs, Notes) */
        }

        /* Number Column: Auto width */
        td:first-child { 
            width: auto; 
            font-weight: bold; 
            color: #555; 
            font-size: 1rem; 
            margin-right: 15px; 
            margin-bottom: 0;
        }
        
        /* Name Column: Flex 1 to take remaining space next to Number */
        td:nth-child(2) {
            width: auto;
            flex: 1;
        }
        
        /* Inputs and Notes push to new line */
        td:nth-child(3), td:nth-child(4) {
            margin-top: 15px;
            width: 100%;
        }

        .radio-group {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap; /* Force one line */
            justify-content: space-between;
            gap: 5px;
            background: transparent;
            border: none;
            padding: 5px 0;
            overflow-x: auto; /* Scroll if screen too tiny */
        }

        .radio-option {
            flex: 1;
            justify-content: center;
            padding: 8px 5px;
            background: #f4f6f8;
            border-radius: 50px;
            font-size: 0.85rem;
            white-space: nowrap;
        }
        
        .radio-option input { transform: scale(1); margin-right: 4px; }
        
        .note-input {
            margin-top: 10px;
            width: 100%;
            padding: 10px;
            background: #fafafa;
        }
    }
</style>
@endpush

@section('content')
<a href="{{ route('attendance.index') }}" style="display: inline-block; margin-bottom: 1rem; color: #888; text-decoration: none;"><i class="fas fa-arrow-left"></i> Kembali</a>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    @if(!$contextYear->is_active || $contextYear->active_semester != $semester)
        <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffeeba;">
            <i class="fas fa-history"></i> <strong>Mode Riwayat:</strong> Anda sedang melihat/mengubah data untuk 
            <strong>Tahun {{ $contextYear->name }} Semester {{ $semester }}</strong>. 
            (Saat ini Aktif: {{ \App\Models\AcademicYear::where('is_active', true)->value('name') }} 
            Semester {{ \App\Models\AcademicYear::where('is_active', true)->value('active_semester') }})
        </div>
    @endif

    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 2px solid #f0f0f0; padding-bottom: 1rem;">
        <div>
            <p style="margin-bottom: 5px; color: #888; font-size: 0.9rem;">Ekstrakurikuler</p>
            <strong style="font-size: 1.1rem;">{{ $eskul->name }}</strong>
        </div>
        <div style="text-align: right;">
            <p style="margin-bottom: 5px; color: #888; font-size: 0.9rem;">Tanggal</p>
            <strong style="font-size: 1.1rem;">{{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}</strong>
        </div>
    </div>

    <form action="{{ route('attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="eskul_id" value="{{ $eskul->id }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="academic_year_id" value="{{ $yearId }}">
        <input type="hidden" name="semester" value="{{ $semester }}">

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Status Kehadiran</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                @php
                    $status = $existingAttendance[$student->id]->status ?? 'present';
                    $note = $existingAttendance[$student->id]->note ?? '';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $student->name }}</strong><br>
                        <small style="color: #999;">{{ $student->class }}</small>
                    </td>
                    <td>
                        <div class="radio-group">
                            <label class="radio-option color-present">
                                <input type="radio" name="attendance[{{ $student->id }}]" value="present" {{ $status == 'present' ? 'checked' : '' }}> Hadir
                            </label>
                            <label class="radio-option color-sick">
                                <input type="radio" name="attendance[{{ $student->id }}]" value="sick" {{ $status == 'sick' ? 'checked' : '' }}> Sakit
                            </label>
                            <label class="radio-option color-perm">
                                <input type="radio" name="attendance[{{ $student->id }}]" value="permission" {{ $status == 'permission' ? 'checked' : '' }}> Izin
                            </label>
                            <label class="radio-option color-absent">
                                <input type="radio" name="attendance[{{ $student->id }}]" value="absent" {{ $status == 'absent' ? 'checked' : '' }}> Alpa
                            </label>
                        </div>
                    </td>
                    <td>
                        <input type="text" name="notes[{{ $student->id }}]" class="note-input" placeholder="Catatan..." value="{{ $note }}">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($students->isEmpty())
            <p style="text-align: center; color: #999; margin: 2rem;">Belum ada siswa di eskul ini.</p>
        @else
            <button type="submit" class="btn-submit" style="display: block; width: 200px; margin: 2rem auto 0;">Simpan Absensi</button>
        @endif
    </form>
</div>
@endsection
