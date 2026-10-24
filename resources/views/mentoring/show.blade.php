@extends('layouts.app')

@section('title', 'Detail Kelompok ' . $typeLabel . ': ' . $group->name)
@section('page-title', 'Kelompok ' . $typeLabel)

@push('styles')
<style>
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0; 
        top: 0; 
        width: 100%; 
        height: 100%; 
        overflow: hidden; 
        background-color: rgba(15, 23, 42, 0.6); 
        backdrop-filter: blur(4px);
        animation: fadeIn 0.25s ease-out;
        align-items: center;
        justify-content: center;
    }
    .modal.active { display: flex; }
    .modal-content {
        background-color: #ffffff;
        width: 92%;
        max-width: 750px;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
        display: flex;
        flex-direction: column;
        max-height: 85vh;
        animation: popIn 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes popIn {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }
    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-body {
        padding: 1.25rem 1.5rem;
        overflow-y: auto;
        flex: 1;
    }
    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: #f8fafc;
        border-radius: 0 0 16px 16px;
    }
    .student-item {
        padding: 10px 14px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: background 0.15s;
    }
    .student-item:hover { background: #f8fafc; }

    /* Responsive Mobile vs Desktop untuk Show Kelompok */
    @media (max-width: 768px) {
        .group-show-hero {
            padding: 18px 16px !important;
            border-radius: 16px !important;
        }
        .group-show-actions {
            width: 100% !important;
            flex-direction: column !important;
            gap: 8px !important;
        }
        .group-show-actions a,
        .group-show-actions button {
            width: 100% !important;
            justify-content: center !important;
            text-align: center !important;
        }
        .group-show-desktop-table {
            display: none !important;
        }
        .group-show-mobile-cards {
            display: flex !important;
            flex-direction: column !important;
            gap: 12px !important;
            padding: 12px !important;
        }
        .modal-content {
            width: 96% !important;
            max-height: 92vh !important;
        }
        .modal-header {
            padding: 14px 16px !important;
        }
        .modal-body {
            padding: 12px 14px !important;
        }
        .modal-footer {
            padding: 12px 16px !important;
        }
    }
    @media (min-width: 769px) {
        .group-show-mobile-cards {
            display: none !important;
        }
    }
    .student-mobile-card {
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
</style>
@endpush

@section('content')
<div style="margin-bottom: 25px;">
    <!-- BREADCRUMB -->
    <div style="margin-bottom: 15px;">
        <a href="{{ $type === 'tilawati' ? route('tilawati-groups.index') : route('tahfidz-groups.index') }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kelompok {{ $typeLabel }}
        </a>
    </div>

    {{-- BANNER SUKSES PENDAFTARAN MUNAQOSAH + TOMBOL KIRIM WA KE WALI SISWA --}}
    @if(session('munaqosah_wa_data'))
        @php
            $waData = session('munaqosah_wa_data');
            $waUrl = "https://api.whatsapp.com/send?phone=" . ($waData['phone'] ?? '') . "&text=" . urlencode($waData['message'] ?? '');
        @endphp
        <div style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 1.5px solid #6ee7b7; border-radius: 16px; padding: 14px 18px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.12);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 12px; background: #059669; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #064e3b;">
                        Alhamdulillah! Pendaftaran Munaqosah {{ $waData['student_name'] }} Berhasil
                    </h4>
                    <p style="margin: 2px 0 0 0; font-size: 0.8rem; color: #047857;">
                        Siswa telah terplot ke dewan penguji. Bagikan kabar gembira dan jadwal ujian ke Orang Tua / Wali Siswa?
                    </p>
                </div>
            </div>
            <div style="display: flex; gap: 8px;">
                @if(!empty($waData['phone']))
                    <a href="{{ $waUrl }}" target="_blank" 
                       style="background: #25D366; color: white; padding: 9px 16px; border-radius: 10px; font-size: 0.84rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 3px 10px rgba(37, 211, 102, 0.35);">
                        <i class="fab fa-whatsapp" style="font-size: 1.1rem;"></i> Kirim Jadwal ke WhatsApp Ortu
                    </a>
                @else
                    <button type="button" onclick="copyMunaqosahWa('{{ addslashes($waData['message']) }}')"
                            style="background: #059669; color: white; border: none; padding: 9px 16px; border-radius: 10px; font-size: 0.84rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fas fa-copy"></i> Salin Pesan WA
                    </button>
                @endif
            </div>
        </div>
    @endif

    <!-- GROUP HEADER CARD (MODERN & RESPONSIF) -->
    <div class="card group-show-hero" style="padding: 24px; margin-bottom: 24px; border-top: 4px solid {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; align-items: flex-start; gap: 16px; flex: 1 1 300px;">
                <div style="width: 56px; height: 56px; border-radius: 14px; background: {{ $type === 'tilawati' ? '#ecfdf5' : '#f0f9ff' }}; color: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.04);">
                    <i class="fas {{ $type === 'tilawati' ? 'fa-book-reader' : 'fa-quran' }}"></i>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <h2 style="margin: 0; font-size: 1.45rem; font-weight: 800; color: #0f172a; letter-spacing: -0.5px;">
                            {{ $group->name }}
                        </h2>
                        <span style="background: {{ $type === 'tilawati' ? '#ecfdf5' : '#f0f9ff' }}; color: {{ $type === 'tilawati' ? '#047857' : '#0369a1' }}; border: 1px solid {{ $type === 'tilawati' ? '#a7f3d0' : '#bae6fd' }}; font-size: 0.74rem; font-weight: 800; padding: 2px 10px; border-radius: 20px; text-transform: uppercase;">
                            {{ $typeLabel }}
                        </span>
                        @if($group->target_level)
                            <span style="background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; font-size: 0.76rem; font-weight: 800; padding: 2px 10px; border-radius: 20px;">
                                <i class="fas fa-bullseye"></i> {{ $group->target_level }}
                            </span>
                        @endif
                    </div>

                    <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-top: 10px; font-size: 0.86rem; color: #475569;">
                        <div>
                            <i class="fas fa-chalkboard-teacher" style="color: #64748b;"></i> Pembimbing: 
                            <strong style="color: #1e293b;">{{ $group->mentor_name }}</strong>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt" style="color: #64748b;"></i> TP: 
                            <strong style="color: #1e293b;">{{ $group->academicYear->name ?? '-' }}</strong>
                        </div>
                        @if($group->schedule)
                            <div>
                                <i class="fas fa-clock" style="color: #64748b;"></i> Jadwal: 
                                <strong style="color: #1e293b;">{{ $group->schedule }}</strong>
                            </div>
                        @endif
                    </div>

                    @if($group->notes)
                        <div style="margin-top: 10px; font-size: 0.84rem; color: #64748b; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border-left: 3px solid #cbd5e1;">
                            {{ $group->notes }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="group-show-actions" style="display: flex; gap: 8px; flex-wrap: wrap;">
                @if($isAdmin)
                    <button type="button" onclick="openAddStudentsModal()" class="btn-action-header btn-green" style="background: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; border-radius: 10px; padding: 9px 16px; font-weight: 800; font-size: 0.84rem;">
                        <i class="fas fa-user-plus"></i> + Tambah Siswa
                    </button>
                @endif
                @if($type === 'tilawati')
                    <a href="{{ route('tilawati.attendance.index', ['group' => $group->id]) }}" class="btn-action-header" style="background: #065f46; color: white; text-decoration: none; padding: 9px 14px; border-radius: 10px; font-size: 0.84rem; display: inline-flex; align-items: center; gap: 6px; font-weight: 700;">
                        <i class="fas fa-history"></i> Riwayat Sesi & WA
                    </a>
                    <a href="{{ route('tilawati.attendance.create', ['group' => $group->id]) }}" class="btn-action-header" style="background: #059669; color: white; text-decoration: none; padding: 9px 14px; border-radius: 10px; font-size: 0.84rem; display: inline-flex; align-items: center; gap: 6px; font-weight: 800; box-shadow: 0 4px 10px rgba(5,150,105,0.3);">
                        <i class="fas fa-plus-circle"></i> + Input Sesi (4 Hal Peraga & 1 Hal Buku)
                    </a>
                    <a href="{{ route('tilawati.attendance.monthly', ['group' => $group->id]) }}" class="btn-action-header" style="background: #0d9488; color: white; text-decoration: none; padding: 9px 14px; border-radius: 10px; font-size: 0.84rem; display: inline-flex; align-items: center; gap: 6px; font-weight: 700;">
                        <i class="fas fa-calendar-alt"></i> Rekap Bulanan
                    </a>
                    <a href="{{ route('tilawati.attendance.semester', ['group' => $group->id]) }}" class="btn-action-header" style="background: #0284c7; color: white; text-decoration: none; padding: 9px 14px; border-radius: 10px; font-size: 0.84rem; display: inline-flex; align-items: center; gap: 6px; font-weight: 700;">
                        <i class="fas fa-file-invoice"></i> Rekap Semester
                    </a>
                    <button type="button" onclick="openBulkMunaqosahModal()" class="btn-action-header" style="background: #f59e0b; color: white; border: none; padding: 9px 14px; border-radius: 10px; font-size: 0.84rem; display: inline-flex; align-items: center; gap: 6px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 10px rgba(245,158,11,0.3);">
                        <i class="fas fa-award"></i> Daftarkan 1 Kelompok ke Munaqosah
                    </button>
                @endif
                @if($type === 'tahfidz')
                    <a href="{{ route('tahfidz.attendance.index', ['group' => $group->id]) }}" class="btn-action-header" style="background: #7c3aed; color: white; text-decoration: none; padding: 9px 14px; border-radius: 10px; font-size: 0.84rem; display: inline-flex; align-items: center; gap: 6px; font-weight: 700;">
                        <i class="fas fa-clipboard-check"></i> Riwayat Sesi & WA
                    </a>
                    <a href="{{ route('tahfidz.attendance.create', ['group' => $group->id]) }}" class="btn-action-header" style="background: #25d366; color: white; text-decoration: none; padding: 9px 14px; border-radius: 10px; font-size: 0.84rem; display: inline-flex; align-items: center; gap: 6px; font-weight: 800; box-shadow: 0 4px 10px rgba(37,211,102,0.3);">
                        <i class="fab fa-whatsapp"></i> + Input Sesi & Share WA
                    </a>
                    <a href="{{ route('tahfidz.print-group-recap', ['group' => $group->id]) }}" target="_blank" class="btn-action-header btn-dark" style="background: #0f766e; border-radius: 10px; padding: 9px 14px; font-size: 0.84rem; font-weight: 700;">
                        <i class="fas fa-quran"></i> Rekap Hafalan
                    </a>
                @endif
                <a href="{{ route('mentoring-groups.print-group', $group->id) }}" target="_blank" class="btn-action-header btn-dark" style="background: #334155; border-radius: 10px; padding: 9px 14px; font-size: 0.84rem; font-weight: 700;">
                    <i class="fas fa-print"></i> Cetak Daftar
                </a>
                @if($isAdmin)
                    <a href="{{ $type === 'tilawati' ? route('tilawati-groups.edit', $group->id) : route('tahfidz-groups.edit', $group->id) }}" class="btn-action-header btn-orange" style="border-radius: 10px; padding: 9px 14px; font-size: 0.84rem; font-weight: 700;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- MEMBER STUDENTS LIST -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #1e293b;">
                    Daftar Anggota Siswa
                </h3>
                <span style="background: #e2e8f0; color: #1e293b; font-size: 0.8rem; font-weight: 700; padding: 2px 10px; border-radius: 12px;">
                    {{ $group->students->count() }} Siswa
                </span>
            </div>
            @if($isAdmin && $group->students->isNotEmpty())
                <span style="font-size: 0.84rem; color: #64748b;">
                    Klik tombol <strong>+ Tambah Siswa</strong> di atas untuk menambahkan siswa lainnya.
                </span>
            @endif
        </div>

        {{-- A. DESKTOP TABLE VIEW --}}
        <div class="group-show-desktop-table" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 0;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px 16px; width: 5%; text-align: center;">No</th>
                        <th style="padding: 12px 16px; text-align: left;">Nama Lengkap Siswa</th>
                        <th style="padding: 12px 16px; text-align: center; width: 12%;">NIS</th>
                        <th style="padding: 12px 16px; text-align: center; width: 10%;">Kelas</th>
                        <th style="padding: 12px 16px; text-align: left;">Catatan / Target Siswa</th>
                        <th style="padding: 12px 16px; text-align: center; width: 14%;">Tanggal Masuk</th>
                        <th style="padding: 12px 16px; text-align: center; width: 14%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($group->students as $index => $student)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 16px; text-align: center; font-weight: bold; color: #64748b;">
                                {{ $index + 1 }}
                            </td>
                            <td style="padding: 14px 16px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem;">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a;">{{ $student->name }}</div>
                                        @if($student->parent_phone)
                                            <small style="color: #64748b; font-size: 0.75rem;"><i class="fab fa-whatsapp"></i> {{ $student->parent_phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 14px 16px; text-align: center; color: #475569; font-family: monospace; font-size: 0.9rem;">
                                {{ $student->nis ?? '-' }}
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <span style="background: #f1f5f9; color: #0f172a; padding: 3px 8px; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">
                                    {{ $student->class }}
                                </span>
                            </td>
                            <td style="padding: 14px 16px; color: #475569; font-size: 0.88rem;">
                                {{ $student->pivot->notes ?? '-' }}
                            </td>
                            <td style="padding: 14px 16px; text-align: center; color: #64748b; font-size: 0.85rem;">
                                {{ $student->pivot->created_at ? $student->pivot->created_at->format('d/m/Y') : '-' }}
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap; align-items: center;">
                                    {{-- Status / Pendaftaran Munaqosah Cepat --}}
                                    @if(isset($munaqosahRegistrations[$student->id]))
                                        <span style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; padding: 4px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;" title="Terdaftar: {{ $munaqosahRegistrations[$student->id]->exam_time ?? 'Menunggu Jadwal' }}">
                                            <i class="fas fa-award"></i> Terdaftar
                                        </span>
                                    @else
                                        <button type="button" 
                                                onclick="openQuickMunaqosahTilawatiModal({{ $student->id }}, '{{ addslashes($student->name) }}', '{{ $student->class }}', '{{ $group->target_level ?? '' }}')"
                                                style="padding: 5px 10px; font-size: 0.75rem; background: #0284c7; color: white; border: none; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; font-weight: 700; box-shadow: 0 2px 5px rgba(2, 132, 199, 0.25);"
                                                title="Daftarkan Munaqosah {{ $typeLabel }}">
                                            <i class="fas fa-award"></i> Munaqosah
                                        </button>
                                    @endif

                                    @if($type === 'tahfidz')
                                        <a href="{{ route('tahfidz.student-card', ['group' => $group->id, 'student' => $student->id]) }}" 
                                           class="btn-action-header" 
                                           style="padding: 5px 10px; font-size: 0.75rem; background: #0f766e; color: white; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;"
                                           title="Lihat Kartu Hafalan">
                                            <i class="fas fa-quran"></i> Hafalan
                                        </a>
                                    @endif

                                    @if($isAdmin)
                                        <button type="button" onclick="confirmRemoveStudent('{{ $student->id }}', '{{ $student->name }}')" class="btn-action-header btn-red" style="padding: 5px 10px; font-size: 0.75rem;" title="Keluarkan dari Kelompok">
                                            <i class="fas fa-user-minus"></i> Hapus
                                        </button>
                                        <form id="remove-form-{{ $student->id }}" action="{{ route('mentoring-groups.remove-student', ['group' => $group->id, 'student' => $student->id]) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <div style="font-size: 2.2rem; margin-bottom: 8px; color: #cbd5e1;">
                                    <i class="fas fa-users-slash"></i>
                                </div>
                                <h4 style="margin: 0; color: #475569;">Belum Ada Anggota di Kelompok Ini</h4>
                                @if($isAdmin)
                                    <p style="margin: 6px 0 16px 0; font-size: 0.9rem;">Tambahkan siswa ke dalam kelompok ini sekarang.</p>
                                    <button type="button" onclick="openAddStudentsModal()" class="btn-action-header btn-green" style="background: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }};">
                                        <i class="fas fa-user-plus"></i> + Tambah Siswa Sekarang
                                    </button>
                                @else
                                    <p style="margin: 6px 0 0 0; font-size: 0.9rem;">Anggota kelompok ditentukan dan diatur oleh Administrator atau Tim Kurikulum.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- B. MOBILE CARD LIST SISWA (SUPER RAPI & ELEGAN DI SMARTPHONE) --}}
        <div class="group-show-mobile-cards">
            @forelse($group->students as $index => $student)
                <div class="student-mobile-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.82rem;">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                                    {{ $student->name }}
                                </div>
                                <div style="display: flex; gap: 8px; align-items: center; margin-top: 2px;">
                                    <span style="background: #f1f5f9; color: #0f172a; padding: 1px 7px; border-radius: 5px; font-weight: 700; font-size: 0.74rem;">
                                        Kelas {{ $student->class }}
                                    </span>
                                    @if($student->nis)
                                        <span style="color: #64748b; font-size: 0.74rem; font-family: monospace;">{{ $student->nis }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <span style="font-size: 0.74rem; font-weight: 700; color: #94a3b8;">#{{ $index + 1 }}</span>
                    </div>

                    @if($student->parent_phone)
                        <div style="margin-bottom: 8px; font-size: 0.76rem;">
                            <a href="https://api.whatsapp.com/send?phone={{ preg_replace('/[^0-9]/', '', $student->parent_phone) }}" target="_blank" style="color: #16a34a; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fab fa-whatsapp"></i> {{ $student->parent_phone }} (Wali)
                            </a>
                        </div>
                    @endif

                    @if($student->pivot->notes)
                        <div style="font-size: 0.78rem; color: #475569; background: #f8fafc; padding: 6px 10px; border-radius: 7px; margin-bottom: 10px;">
                            {{ $student->pivot->notes }}
                        </div>
                    @endif

                    <div style="display: flex; gap: 6px; justify-content: flex-end; border-top: 1px solid #f1f5f9; padding-top: 8px; align-items: center;">
                        @if(isset($munaqosahRegistrations[$student->id]))
                            <span style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; padding: 5px 10px; border-radius: 7px; font-size: 0.75rem; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fas fa-award"></i> Terdaftar Munaqosah
                            </span>
                        @else
                            <button type="button" 
                                    onclick="openQuickMunaqosahTilawatiModal({{ $student->id }}, '{{ addslashes($student->name) }}', '{{ $student->class }}', '{{ $group->target_level ?? '' }}')"
                                    style="padding: 6px 12px; font-size: 0.78rem; background: #0284c7; color: white; border: none; border-radius: 7px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; font-weight: 800;">
                                <i class="fas fa-award"></i> Daftar Munaqosah
                            </button>
                        @endif

                        @if($type === 'tahfidz')
                            <a href="{{ route('tahfidz.student-card', ['group' => $group->id, 'student' => $student->id]) }}" 
                               class="btn-action-header" 
                               style="padding: 6px 12px; font-size: 0.78rem; background: #0f766e; color: white; text-decoration: none; border-radius: 7px; display: inline-flex; align-items: center; gap: 4px; font-weight: 700;">
                                <i class="fas fa-quran"></i> Hafalan
                            </a>
                        @endif

                        @if($isAdmin)
                            <button type="button" onclick="confirmRemoveStudent('{{ $student->id }}', '{{ $student->name }}')" class="btn-action-header btn-red" style="padding: 6px 10px; font-size: 0.78rem; border-radius: 7px;" title="Keluarkan dari Kelompok">
                                <i class="fas fa-user-minus"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 30px; color: #94a3b8;">
                    <i class="fas fa-users-slash" style="font-size: 2rem; margin-bottom: 8px;"></i>
                    <p style="margin: 0;">Belum ada anggota di kelompok ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@if($isAdmin)
<!-- MODAL TAMBAH SISWA -->
<div id="addStudentsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">
                    Tambah Siswa ke Kelompok: {{ $group->name }}
                </h3>
                <small style="color: #64748b;">Pilih satu atau beberapa siswa untuk dimasukkan ke kelompok ini.</small>
            </div>
            <button type="button" onclick="closeAddStudentsModal()" style="border: none; background: transparent; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div style="padding: 12px 1.5rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 140px;">
                <select id="modalClassFilter" class="form-control-pro" onchange="loadStudentsForModal()" style="width: 100%;">
                    <option value="ALL">-- Semua Kelas --</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls }}">Kelas {{ $cls }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 2; min-width: 200px; position: relative;">
                <input type="text" id="modalSearchFilter" oninput="filterModalStudentList()" placeholder="Cari nama atau NIS..." class="form-control" style="padding: 8px 12px 8px 32px; font-size: 0.88rem;">
                <i class="fas fa-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem;"></i>
            </div>
        </div>

        <form action="{{ route('mentoring-groups.add-students', $group->id) }}" method="POST" id="addStudentsForm">
            @csrf

            <div class="modal-body" style="padding: 0; max-height: 50vh; overflow-y: auto;">
                <div style="padding: 10px 1.5rem; background: #f1f5f9; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 0.85rem; color: #334155; cursor: pointer; margin: 0;">
                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)">
                        Pilih Semua yang Ditampilkan
                    </label>
                    <span id="selectedCountBadge" style="font-size: 0.8rem; font-weight: 700; color: #0284c7;">
                        0 Dipilih
                    </span>
                </div>

                <div id="studentListContainer">
                    <div style="padding: 30px; text-align: center; color: #94a3b8;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem;"></i>
                        <p style="margin: 8px 0 0 0; font-size: 0.88rem;">Memuat daftar siswa...</p>
                    </div>
                </div>
            </div>

            <!-- CATATAN OPSIONAL -->
            <div style="padding: 12px 1.5rem; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <label style="display: block; margin-bottom: 4px; font-size: 0.82rem; font-weight: 600; color: #334155;">
                    Catatan untuk Siswa yang Dipilih (Opsional)
                </label>
                <input type="text" name="notes" placeholder="Contoh: Mulai Jilid 2 Hal 10, atau Juz 30" class="form-control" style="padding: 7px 12px; font-size: 0.85rem;">
            </div>

            <div class="modal-footer">
                <button type="button" id="btnCancelModal" onclick="closeAddStudentsModal()" class="btn-action-header btn-dark" style="background: #94a3b8; padding: 8px 16px;">
                    Batal
                </button>
                <button type="submit" id="btnSubmitAddStudents" class="btn-action-header btn-green" style="background: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; padding: 8px 20px;" disabled>
                    <i class="fas fa-plus-circle"></i> Tambahkan Siswa Terpilih
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let allFetchedStudents = [];
    let hasAddedStudents = false;

    function openAddStudentsModal() {
        document.getElementById('addStudentsModal').classList.add('active');
        hasAddedStudents = false;
        const btnCancel = document.getElementById('btnCancelModal');
        if (btnCancel) {
            btnCancel.innerHTML = 'Batal';
            btnCancel.style.background = '#94a3b8';
        }
        loadStudentsForModal();
    }

    function closeAddStudentsModal() {
        document.getElementById('addStudentsModal').classList.remove('active');
        if (hasAddedStudents) {
            window.location.reload();
        }
    }

    function loadStudentsForModal() {
        const classVal = document.getElementById('modalClassFilter').value;
        const container = document.getElementById('studentListContainer');
        container.innerHTML = `
            <div style="padding: 30px; text-align: center; color: #94a3b8;">
                <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem;"></i>
                <p style="margin: 8px 0 0 0; font-size: 0.88rem;">Memuat siswa...</p>
            </div>
        `;

        const url = `{{ route('mentoring.api.students-by-class') }}?class=${encodeURIComponent(classVal)}&year_id={{ $group->academic_year_id }}&group_id={{ $group->id }}&type={{ $group->type }}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                allFetchedStudents = data;
                renderStudentList(data);
            })
            .catch(err => {
                container.innerHTML = `
                    <div style="padding: 20px; text-align: center; color: #ef4444;">
                        Gagal memuat data siswa. Silakan coba lagi.
                    </div>
                `;
            });
    }

    function filterModalStudentList() {
        const query = document.getElementById('modalSearchFilter').value.toLowerCase();
        const filtered = allFetchedStudents.filter(s => {
            return s.name.toLowerCase().includes(query) || s.nis.toLowerCase().includes(query) || s.class.toLowerCase().includes(query);
        });
        renderStudentList(filtered);
    }

    function renderStudentList(students) {
        const container = document.getElementById('studentListContainer');
        if (students.length === 0) {
            container.innerHTML = `
                <div style="padding: 30px; text-align: center; color: #94a3b8;">
                    Tidak ditemukan siswa yang cocok.
                </div>
            `;
            updateSelectedBadge();
            return;
        }

        let html = '';
        students.forEach(student => {
            let statusBadge = '';
            let disabledAttr = '';
            
            if (student.is_in_this_group) {
                statusBadge = '<span style="background: #e0f2fe; color: #0369a1; font-size: 0.72rem; padding: 2px 8px; border-radius: 4px; font-weight: 600;"><i class="fas fa-check"></i> Sudah di Kelompok Ini</span>';
                disabledAttr = 'disabled';
            } else if (student.other_group_name) {
                statusBadge = `<span style="background: #fef3c7; color: #b45309; font-size: 0.72rem; padding: 2px 8px; border-radius: 4px; font-weight: 600;"><i class="fas fa-exchange-alt"></i> Pindah dari: ${student.other_group_name}</span>`;
            } else {
                statusBadge = '<span style="background: #ecfdf5; color: #047857; font-size: 0.72rem; padding: 2px 8px; border-radius: 4px; font-weight: 600;">Belum Ada Kelompok</span>';
            }

            html += `
                <label class="student-item" style="cursor: ${student.is_in_this_group ? 'not-allowed' : 'pointer'}; margin: 0; opacity: ${student.is_in_this_group ? '0.6' : '1'};">
                    <input type="checkbox" name="student_ids[]" value="${student.id}" class="student-checkbox" ${disabledAttr} onchange="updateSelectedBadge()">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <strong style="color: #0f172a; font-size: 0.9rem;">${student.name}</strong>
                            <span style="background: #f1f5f9; color: #475569; font-size: 0.75rem; padding: 1px 6px; border-radius: 4px;">Kelas ${student.class}</span>
                            ${statusBadge}
                        </div>
                        <small style="color: #64748b; font-size: 0.78rem;">NIS: ${student.nis}</small>
                    </div>
                </label>
            `;
        });

        container.innerHTML = html;
        updateSelectedBadge();
    }

    function toggleSelectAll(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.student-checkbox:not(:disabled)');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
        });
        updateSelectedBadge();
    }

    function updateSelectedBadge() {
        const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
        document.getElementById('selectedCountBadge').innerText = `${checkedCount} Dipilih`;
        const submitBtn = document.getElementById('btnSubmitAddStudents');
        submitBtn.disabled = (checkedCount === 0);
    }

    function confirmRemoveStudent(id, name) {
        Swal.fire({
            title: 'Keluarkan Siswa?',
            text: `Apakah Anda yakin ingin mengeluarkan siswa "${name}" dari kelompok ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Keluarkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('remove-form-' + id).submit();
            }
        });
    }

    // Close modal on click outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('addStudentsModal');
        if (e.target === modal) {
            closeAddStudentsModal();
        }
    });

    // AJAX submit form to stay on page
    document.getElementById('addStudentsForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = document.getElementById('btnSubmitAddStudents');
        const originalBtnHtml = submitBtn.innerHTML;

        const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
        if (checkedBoxes.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Siswa',
                text: 'Silakan centang minimal satu siswa untuk dimasukkan ke kelompok ini.'
            });
            return;
        }

        const formData = new FormData(form);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Gagal menambahkan siswa ke kelompok.');
            }
            return data;
        })
        .then(data => {
            hasAddedStudents = true;

            if (typeof Toast !== 'undefined') {
                Toast.fire({
                    icon: 'success',
                    title: data.message
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }

            // Reset optional notes
            const notesInput = form.querySelector('input[name="notes"]');
            if (notesInput) notesInput.value = '';

            // Reset select all checkbox
            const selectAll = document.getElementById('selectAllCheckbox');
            if (selectAll) selectAll.checked = false;

            // Change Batal button to Selesai
            const btnCancel = document.getElementById('btnCancelModal');
            if (btnCancel) {
                btnCancel.innerHTML = '<i class="fas fa-check"></i> Selesai';
                btnCancel.style.background = '#059669';
                btnCancel.style.color = '#ffffff';
            }

            // Reload students in modal keeping the currently selected class filter
            loadStudentsForModal();
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: err.message
            });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
            updateSelectedBadge();
        });
    });
</script>
@endif

<!-- Modal Pop-up Daftarkan Munaqosah Cepat untuk Kelompok Tilawati / Mentoring -->
<div id="quickMunaqosahTilawatiModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 520px; padding: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); max-height: 90vh; overflow-y: auto;">
        
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #eff6ff; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">Daftarkan Munaqosah {{ $typeLabel }}</h3>
                    <p style="margin: 2px 0 0 0; color: #64748b; font-size: 0.8rem;">Daftarkan siswa langsung ke dewan penguji</p>
                </div>
            </div>
            <button type="button" onclick="closeQuickMunaqosahTilawatiModal()" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer; padding: 4px;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('munaqosah.register.store') }}">
            @csrf
            <input type="hidden" name="redirect_back" value="1">
            <input type="hidden" name="student_id" id="quick_tilawati_student_id">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="from_group_id" value="{{ $group->id }}">
            <input type="hidden" name="proposer_name" value="{{ Auth::user()->name }}">

            <!-- Info Siswa & Kelompok -->
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 12px 14px; margin-bottom: 16px;">
                <div style="font-weight: 800; color: #0f172a; font-size: 0.95rem;" id="quick_tilawati_student_name">
                    Nama Siswa
                </div>
                <div style="font-size: 0.78rem; color: #64748b; margin-top: 3px; display: flex; gap: 10px; align-items: center;">
                    <span id="quick_tilawati_student_class">Kelas -</span>
                    <span>•</span>
                    <span>Kelompok: <strong>{{ $group->name }}</strong></span>
                </div>
            </div>

            <!-- Jenjang Munaqosah -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">
                        Jenjang Diuji <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="current_level" id="quick_tilawati_current_level" required 
                           value="{{ $group->target_level ?? ($type === 'tilawati' ? 'Jilid 1' : 'Juz 30') }}"
                           style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; font-weight: 700; background: #fff;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">
                        Target Lanjutan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="target_level" id="quick_tilawati_target_level" required 
                           value="{{ $type === 'tilawati' ? 'Jilid 2' : 'Juz 29' }}"
                           style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; font-weight: 700; background: #fff;">
                </div>
            </div>

            <!-- Dropdown Pilihan Jadwal & Penguji Munaqisy -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                    <i class="far fa-calendar-check" style="color: #0284c7;"></i> Pilih Jadwal & Penguji / Munaqisy Tersedia
                </label>
                <select name="schedule_id" class="form-control-pro" style="width: 100%;">
                    <option value="">-- Tanpa Jadwal (Jadwalkan Nanti oleh Panitia) --</option>
                    @foreach($munaqosahSchedules as $sch)
                        @php
                            $isFull = $sch->is_full;
                            $exName = $sch->examiner?->user?->name ?? 'Penguji';
                            $slotTxt = "{$exName} • {$sch->day_name} ({$sch->time_start} - {$sch->time_end} WIB)";
                            if ($sch->room_location) {
                                $slotTxt .= " · {$sch->room_location}";
                            }
                        @endphp
                        <option value="{{ $sch->id }}" {{ $isFull ? 'disabled' : '' }}>
                            {{ $slotTxt }} {{ $isFull ? '[SUDAH TERPENUHI ✓]' : "(Sisa: " . ($sch->quota - $sch->used_slots) . " kuota)" }}
                        </option>
                    @endforeach
                </select>
                <span style="font-size: 0.74rem; color: #64748b; margin-top: 4px; display: block;">
                    Jadwal ini diatur oleh Admin & Kurikulum. Jika dipilih, siswa langsung otomatis terplot ke penguji tersebut.
                </span>
            </div>

            <!-- Catatan Pengusul (Opsional) -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">
                    Catatan Guru Pembimbing (Opsional)
                </label>
                <textarea name="proposer_notes" rows="2" placeholder="Contoh: Siswa sudah tuntas fashohah dan gharib jilid ini..." style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.84rem;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                <button type="button" onclick="closeQuickMunaqosahTilawatiModal()" style="padding: 10px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 22px; border-radius: 10px; border: none; background: #0284c7; color: white; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3); display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-check-circle"></i> Konfirmasi & Daftarkan Langsung
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openQuickMunaqosahTilawatiModal(studentId, studentName, studentClass, targetLevel) {
        document.getElementById('quick_tilawati_student_id').value = studentId;
        document.getElementById('quick_tilawati_student_name').textContent = studentName;
        document.getElementById('quick_tilawati_student_class').textContent = 'Kelas ' + (studentClass || '-');

        const curLevel = targetLevel || '{{ $group->target_level ?? ($type === "tilawati" ? "Jilid 1" : "Juz 30") }}';
        document.getElementById('quick_tilawati_current_level').value = curLevel;

        // Auto suggest target lanjutan
        let nextLevel = 'Jilid 2';
        const curLower = curLevel.toLowerCase();
        if (curLower.includes('jilid 1')) nextLevel = 'Jilid 2';
        else if (curLower.includes('jilid 2')) nextLevel = 'Jilid 3';
        else if (curLower.includes('jilid 3')) nextLevel = 'Jilid 4';
        else if (curLower.includes('jilid 4')) nextLevel = 'Jilid 5';
        else if (curLower.includes('jilid 5')) nextLevel = 'Al-Qur\'an';
        else if (curLower.includes('al-qur\'an') || curLower.includes('alquran')) nextLevel = 'Gharib / Tajwid';
        else if (curLower.includes('30')) nextLevel = 'Juz 29';
        else if (curLower.includes('29')) nextLevel = 'Juz 28';
        else if (curLower.includes('28')) nextLevel = 'Juz 27';
        else if (curLower.includes('27')) nextLevel = 'Juz 26';
        else if (curLower.includes('26')) nextLevel = 'Juz 25';

        document.getElementById('quick_tilawati_target_level').value = nextLevel;

        const modal = document.getElementById('quickMunaqosahTilawatiModal');
        modal.style.display = 'flex';
    }

    function closeQuickMunaqosahTilawatiModal() {
        const modal = document.getElementById('quickMunaqosahTilawatiModal');
        modal.style.display = 'none';
    }

    function copyMunaqosahWa(msg) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(msg).then(() => {
                alert('✅ Pesan jadwal munaqosah berhasil disalin! Silakan paste/tempel ke WhatsApp orang tua.');
            }).catch(() => {
                prompt('Salin pesan pengumuman munaqosah berikut:', msg);
            });
        } else {
            prompt('Salin pesan pengumuman munaqosah berikut:', msg);
        }
    }

    function openBulkMunaqosahModal() {
        document.getElementById('bulkMunaqosahModal').style.display = 'flex';
    }
    function closeBulkMunaqosahModal() {
        document.getElementById('bulkMunaqosahModal').style.display = 'none';
    }
</script>

<!-- Modal Pendaftaran 1 Kelompok ke Munaqosah -->
<div id="bulkMunaqosahModal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 9999; padding: 16px;">
    <div style="background: white; border-radius: 20px; max-width: 580px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <div style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 40px; height: 40px; border-radius: 12px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a;">
                        Pendaftaran Munaqosah Tilawati (1 Kelompok)
                    </h3>
                    <p style="margin: 0; font-size: 0.78rem; color: #64748b;">
                        Daftarkan seluruh santri {{ $group->name }} sekaligus dalam satu klik.
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeBulkMunaqosahModal()" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('munaqosah.register-group.store') }}" method="POST" style="padding: 24px;">
            @csrf
            <input type="hidden" name="group_id" value="{{ $group->id }}">

            <!-- Info Kelompok & Anggota -->
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 14px; margin-bottom: 18px;">
                <div style="display: flex; justify-content: space-between; font-size: 0.84rem; margin-bottom: 4px;">
                    <span style="color: #64748b;">Kelompok:</span>
                    <strong style="color: #0f172a;">{{ $group->name }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.84rem; margin-bottom: 4px;">
                    <span style="color: #64748b;">Tingkat Saat Ini:</span>
                    <strong style="color: #047857;">{{ $group->target_level ?: 'Jilid 6' }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.84rem;">
                    <span style="color: #64748b;">Jumlah Santri Terdaftar:</span>
                    <strong style="color: #2563eb;">{{ $group->students->count() }} Santri</strong>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                    Tingkat Kenaikan / Target Munaqosah <span style="color: #ef4444;">*</span>:
                </label>
                <input type="text" name="target_level" required value="Al-Qur'an (Khatam Tilawati)"
                       placeholder="Contoh: Al-Qur'an (Khatam Tilawati) atau Jilid Berikutnya"
                       style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; font-weight: 700; box-sizing: border-box;">
                <small style="color: #64748b; font-size: 0.74rem;">Bila siswa lulus Jilid 6, target otomatis naik ke Al-Qur'an.</small>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                    Pilih Jadwal Ujian / Sesi Penguji (Opsional):
                </label>
                <select name="schedule_id" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; font-weight: 700; color: #0f172a; background: white; box-sizing: border-box;">
                    <option value="">-- Tetapkan Nanti (Status Menunggu / Pending) --</option>
                    @foreach($munaqosahSchedules as $sch)
                        <option value="{{ $sch->id }}">
                            {{ $sch->day_name }}, {{ $sch->time_start }}-{{ $sch->time_end }} | Penguji: {{ $sch->examiner->name ?? 'Penguji' }} ({{ $sch->room_location ?? 'Ruang Munaqosah' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 22px;">
                <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                    Catatan Pengusul untuk Dewan Penguji:
                </label>
                <textarea name="proposer_notes" rows="2" placeholder="Catatan penguasaan fashahah/tajwid santri kelompok..."
                          style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.84rem; box-sizing: border-box;">Pendaftaran Kolektif Santri {{ $group->name }} (Lulus {{ $group->target_level ?: 'Jilid 6' }})</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeBulkMunaqosahModal()" style="padding: 10px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 22px; border-radius: 10px; border: none; background: #f59e0b; color: white; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">
                    <i class="fas fa-check-circle"></i> Daftarkan Sekarang ({{ $group->students->count() }} Santri)
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
