@extends('layouts.app')

@section('title', 'Detail Siswa')
@section('page-title', 'Detail Siswa')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; margin-bottom: 5px;">{{ $student->name }}</h2>
            <div style="display: flex; gap: 10px; align-items: center;">
                <span style="background: #eee; padding: 5px 12px; border-radius: 8px; font-weight: 500;">
                    <i class="fas fa-chalkboard-teacher" style="margin-right: 5px; color: #888;"></i> Kelas Terakhir: {{ $student->class }}
                </span>
                @if($student->status == 'graduated' || $student->status == 'lulus')
                    <span style="background: #dcfce7; color: #166534; padding: 5px 12px; border-radius: 8px; font-weight: 600;">
                        <i class="fas fa-graduation-cap" style="margin-right: 5px;"></i> LULUS
                    </span>
                @elseif($student->status == 'active' || $student->status == 'aktif')
                    <span style="background: #dbeafe; color: #1e40af; padding: 5px 12px; border-radius: 8px; font-weight: 600;">
                        <i class="fas fa-check-circle" style="margin-right: 5px;"></i> AKTIF
                    </span>
                @else
                    <span style="background: #f3f4f6; color: #374151; padding: 5px 12px; border-radius: 8px; font-weight: 600;">
                        {{ strtoupper($student->status) }}
                    </span>
                @endif
            </div>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('students.portfolio', $student->id) }}" target="_blank" class="btn" style="background: #8b5cf6; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(139,92,246,0.3);">
                <i class="fas fa-id-card-alt"></i> Cetak Portofolio
            </a>
            <a href="{{ route('students.card', $student->id) }}" target="_blank" class="btn" style="background: #0284c7; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(2,132,199,0.3);">
                <i class="fas fa-id-card"></i> Cetak Kartu
            </a>
            @if(in_array(Auth::user()->role, ['admin', 'tu']))
            <a href="{{ route('students.edit', $student->id) }}" class="btn" style="background: #f59e0b; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-pen"></i> Edit
            </a>
            @endif
            <a href="{{ route('students.index', ['status' => $student->status == 'graduated' ? 'graduated' : 'active']) }}" class="btn-filter" style="background: #95a5a6; padding: 8px 16px; border-radius: 8px; color: white; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- KARTU BIODATA LENGKAP SISWA --}}
    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px; margin-bottom: 25px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
            <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-address-card" style="color: #3b82f6;"></i> Biodata Lengkap Siswa
            </h3>
            <span style="font-size: 0.8rem; color: #64748b; font-weight: 500;">
                Tahun Ajaran Terdaftar: <strong>{{ $student->academicYear ? $student->academicYear->name : '-' }}</strong>
            </span>
        </div>

        <div style="display: grid; grid-template-columns: auto 1fr 1fr; gap: 24px; align-items: start;">
            {{-- Foto Siswa --}}
            <div style="text-align: center;">
                <div style="width: 110px; height: 110px; border-radius: 14px; overflow: hidden; background: #e2e8f0; display: flex; align-items: center; justify-content: center; border: 3px solid #cbd5e1; box-shadow: 0 4px 10px rgba(0,0,0,0.06); margin: 0 auto 8px;">
                    @if($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="fas fa-user-graduate" style="font-size: 3.2rem; color: #94a3b8;"></i>
                    @endif
                </div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">
                    {{ $student->school_program ?: 'Reguler' }}
                </div>
            </div>

            {{-- Kolom Data Diri --}}
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="font-size: 0.82rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">
                    <i class="fas fa-user"></i> Data Pribadi
                </div>
                <table style="width: 100%; font-size: 0.88rem; border-collapse: collapse;">
                    <tr>
                        <td style="color: #64748b; width: 130px; padding: 4px 0;">NIS / NISN</td>
                        <td style="font-weight: 600; color: #1e293b; padding: 4px 0;">
                            {{ $student->nis ?: '-' }} / {{ $student->nisn ?: '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Jenis Kelamin</td>
                        <td style="font-weight: 600; color: #1e293b; padding: 4px 0;">
                            @if($student->gender == 'L')
                                <span style="background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">Laki-laki (L)</span>
                            @elseif($student->gender == 'P')
                                <span style="background: #fce7f3; color: #be185d; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">Perempuan (P)</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Tempat, Tgl Lahir</td>
                        <td style="font-weight: 600; color: #1e293b; padding: 4px 0;">
                            {{ $student->birth_place_date ?: '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Asal Sekolah (TK)</td>
                        <td style="font-weight: 600; color: #1e293b; padding: 4px 0;">
                            {{ $student->previous_school ?: '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Tgl Diterima</td>
                        <td style="font-weight: 600; color: #1e293b; padding: 4px 0;">
                            {{ $student->admission_date ?: '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Alamat</td>
                        <td style="font-weight: 600; color: #1e293b; padding: 4px 0;">
                            {{ $student->address ?: '-' }}
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Kolom Data Orang Tua --}}
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="font-size: 0.82rem; font-weight: 700; color: #10b981; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">
                    <i class="fas fa-users"></i> Orang Tua / Wali
                </div>
                <table style="width: 100%; font-size: 0.88rem; border-collapse: collapse;">
                    <tr>
                        <td style="color: #64748b; width: 130px; padding: 4px 0;">Nama Ayah</td>
                        <td style="font-weight: 600; color: #1e293b; padding: 4px 0;">
                            {{ $student->father_name ?: '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Pekerjaan Ayah</td>
                        <td style="font-weight: 600; color: #1e293b; padding: 4px 0;">
                            {{ $student->father_job ?: '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">No HP Ayah</td>
                        <td style="font-weight: 600; color: #1e293b; padding: 4px 0;">
                            @if($student->father_phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->father_phone) }}" target="_blank" style="color: #16a34a; text-decoration: none;">
                                    <i class="fab fa-whatsapp"></i> {{ $student->father_phone }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Nama Ibu</td>
                        <td style="font-weight: 600; color: #1e293b; padding: 4px 0;">
                            {{ $student->mother_name ?: '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Pekerjaan Ibu</td>
                        <td style="font-weight: 600; color: #1e293b; padding: 4px 0;">
                            {{ $student->mother_job ?: '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">No HP Ibu</td>
                        <td style="font-weight: 600; color: #1e293b; padding: 4px 0;">
                            @if($student->mother_phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->mother_phone) }}" target="_blank" style="color: #16a34a; text-decoration: none;">
                                    <i class="fab fa-whatsapp"></i> {{ $student->mother_phone }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    @if(empty($history))
        <div style="text-align: center; padding: 3rem; color: #999;">
            <i class="fas fa-history" style="font-size: 3rem; margin-bottom: 1rem; color: #eee;"></i><br>
            Belum ada data riwayat akademik.
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            @foreach($history as $yearName => $data)
            <div style="border: 1px solid #eee; border-radius: 12px; overflow: hidden;">
                <div style="background: #f8fafc; padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between;">
                    <h3 style="font-size: 1.1rem; color: #475569; display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <span>
                            <i class="fas fa-calendar-alt" style="margin-right: 8px; color: #3498db;"></i> Tahun Ajaran: <strong>{{ $yearName }}</strong>
                        </span>
                        @if(isset($data[0]['class']))
                            <span style="font-size: 0.85rem; font-weight: 500; background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 6px;">
                                Kelas {{ $data[0]['class'] }}
                            </span>
                        @endif
                    </h3>
                </div>
                
                <table style="margin: 0; width: 100%;">
                    <thead>
                        <tr style="background: white;">
                            <th style="padding-left: 20px;">Ekstrakurikuler</th>
                            <th>Pembina</th>
                            <th style="text-align: center;">Nilai SAS 1</th>
                            <th style="text-align: center;">Nilai SAS 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                        <tr>
                            <td style="padding-left: 20px;">
                                <span style="font-weight: 600; color: #2c3e50;">{{ $item['eskul']->name }}</span>
                            </td>
                            <td>
                                <span style="color: #64748b;">{{ $item['eskul']->instructor_name ?? '-' }}</span>
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                @php
                                    $score1 = $item['sas1'];
                                    $decoded1 = json_decode($score1, true);
                                    $isJson1 = (json_last_error() === JSON_ERROR_NONE && is_array($decoded1));
                                @endphp

                                @if($score1 !== '-')
                                    @if($isJson1)
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start; display: inline-flex;">
                                            @foreach($decoded1 as $key => $val)
                                                @php
                                                    $label = match($key) {
                                                        'reading' => 'Membaca',
                                                        'writing' => 'Menulis',
                                                        'counting' => 'Berhitung',
                                                        default => ucfirst($key)
                                                    };
                                                    // Color coding
                                                    $bg = match($val) {
                                                        'A' => '#dcfce7', // Light green
                                                        'B' => '#dbeafe', // Light blue
                                                        'C' => '#fef3c7', // Light yellow
                                                        'D' => '#fee2e2', // Light red
                                                        default => '#f1f5f9'
                                                    };
                                                    $text = match($val) {
                                                        'A' => '#166534',
                                                        'B' => '#1e40af',
                                                        'C' => '#92400e',
                                                        'D' => '#b91c1c',
                                                        default => '#475569'
                                                    };
                                                @endphp
                                                <span style="font-size: 0.75rem; background: {{ $bg }}; color: {{ $text }}; padding: 3px 8px; border-radius: 4px; font-weight: 600; white-space: nowrap; border: 1px solid rgba(0,0,0,0.05);">
                                                    {{ $label }}: {{ $val }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="background: #eff6ff; color: #2563eb; padding: 6px 15px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">{{ $score1 }}</span>
                                    @endif
                                @else
                                    <span style="color: #cbd5e1;">-</span>
                                @endif
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                @php
                                    $score2 = $item['sas2'];
                                    $decoded2 = json_decode($score2, true);
                                    $isJson2 = (json_last_error() === JSON_ERROR_NONE && is_array($decoded2));
                                @endphp

                                @if($score2 !== '-')
                                    @if($isJson2)
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start; display: inline-flex;">
                                            @foreach($decoded2 as $key => $val)
                                                @php
                                                    $label = match($key) {
                                                        'reading' => 'Membaca',
                                                        'writing' => 'Menulis',
                                                        'counting' => 'Berhitung',
                                                        default => ucfirst($key)
                                                    };
                                                    $bg = match($val) {
                                                        'A' => '#dcfce7',
                                                        'B' => '#dbeafe',
                                                        'C' => '#fef3c7',
                                                        'D' => '#fee2e2',
                                                        default => '#f1f5f9'
                                                    };
                                                    $text = match($val) {
                                                        'A' => '#166534',
                                                        'B' => '#1e40af',
                                                        'C' => '#92400e',
                                                        'D' => '#b91c1c',
                                                        default => '#475569'
                                                    };
                                                @endphp
                                                <span style="font-size: 0.75rem; background: {{ $bg }}; color: {{ $text }}; padding: 3px 8px; border-radius: 4px; font-weight: 600; white-space: nowrap; border: 1px solid rgba(0,0,0,0.05);">
                                                    {{ $label }}: {{ $val }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="background: #eff6ff; color: #2563eb; padding: 6px 15px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">{{ $score2 }}</span>
                                    @endif
                                @else
                                    <span style="color: #cbd5e1;">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        </div>
    @endif

    <!-- Data Prestasi Section -->
    <div style="margin-top: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
             <h3 style="font-size: 1.2rem; color: #2c3e50; border-left: 4px solid #f1c40f; padding-left: 10px;">
                <i class="fas fa-trophy" style="color: #f1c40f; margin-right: 10px;"></i> Data Prestasi
            </h3>
            <a href="{{ route('achievements.create', ['student_id' => $student->id]) }}" class="btn-submit" style="padding: 8px 15px; font-size: 0.9rem;">
                <i class="fas fa-plus"></i> Tambah Prestasi
            </a>
        </div>

        @if($achievements->isEmpty())
             <div style="background: #fffbe7; color: #9a7d0a; padding: 20px; border-radius: 10px; text-align: center; border: 1px dashed #f1c40f;">
                Belum ada data prestasi yang tercatat.
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($achievements as $achievement)
                <div style="background: white; border: 1px solid #f9e79f; padding: 15px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 50px; height: 50px; background: #fffbe7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #f1c40f;">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 1.1rem; color: #2c3e50;">{{ $achievement->name }}</div>
                            <div style="color: #666; font-size: 0.9rem; margin-top: 3px;">
                                <span style="background: #f1c40f; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-right: 5px;">{{ $achievement->level }}</span>
                                <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($achievement->date)->translatedFormat('d F Y') }}
                                @if($achievement->organizer)
                                | Oleh: {{ $achievement->organizer }}
                                @endif
                            </div>
                            @if($achievement->description)
                            <div style="margin-top: 5px; font-size: 0.85rem; color: #888; font-style: italic;">
                                "{{ $achievement->description }}"
                            </div>
                            @endif
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('achievements.edit', $achievement->id) }}" style="color: #3498db; background: #ebf5fb; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.2s;" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('achievements.destroy', $achievement->id) }}" method="POST" onsubmit="return confirm('Hapus prestasi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #e74c3c; background: #fce7e7; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.2s; border: none; cursor: pointer;" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
