@extends('layouts.app')

@section('title', 'Form Presensi Siswa')
@section('page-title', 'Form Presensi Ekstrakurikuler')

@push('styles')
<style>
    /* Styling Dasar Form Presensi Modern */
    .btn-back-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 0.86rem;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
        margin-bottom: 1.25rem;
    }
    .btn-back-pill:hover {
        background: #f8fafc;
        color: #0f172a;
        transform: translateX(-3px);
    }

    /* Kartu Lembar Presensi */
    .presensi-main-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        padding: 0;
        overflow: hidden;
        margin-bottom: 2.5rem;
    }

    /* Header Lembar Presensi */
    .presensi-card-header {
        background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);
        padding: 24px 28px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .presensi-info-group {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .presensi-icon-badge {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: #dcfce7;
        color: #059669;
        border: 1.5px solid #bbf7d0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .presensi-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.2;
    }

    .presensi-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Control Bar Aksi Cepat */
    .batch-control-bar {
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        padding: 14px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .batch-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 0.82rem;
        font-weight: 750;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }
    .batch-btn-present {
        background: #ecfdf5;
        color: #065f46;
        border-color: #a7f3d0;
    }
    .batch-btn-present:hover {
        background: #d1fae5;
        transform: translateY(-1px);
    }
    .batch-btn-perm {
        background: #eff6ff;
        color: #1e40af;
        border-color: #bfdbfe;
    }
    .batch-btn-perm:hover {
        background: #dbeafe;
        transform: translateY(-1px);
    }

    /* Tabel Presensi Desktop */
    .presensi-table {
        width: 100%;
        border-collapse: collapse;
    }
    .presensi-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.8rem;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 20px;
        border-bottom: 1.5px solid #e2e8f0;
        text-align: left;
    }
    .presensi-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .presensi-table tr:hover td {
        background: #fafcfb;
    }

    /* Avatar & Info Santri */
    .student-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .student-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #e2e8f0;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.88rem;
        font-weight: 800;
        flex-shrink: 0;
    }
    .student-name {
        font-size: 0.95rem;
        font-weight: 750;
        color: #0f172a;
        margin: 0;
    }
    .student-class-badge {
        display: inline-block;
        background: #f1f5f9;
        color: #475569;
        border-radius: 6px;
        padding: 2px 8px;
        font-size: 0.74rem;
        font-weight: 700;
        margin-top: 3px;
    }

    /* Segmented Status Radios (Pill Style) */
    .segmented-status-group {
        display: inline-flex;
        gap: 6px;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    .status-pill {
        position: relative;
        cursor: pointer;
        user-select: none;
        margin: 0;
    }
    .status-pill input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        margin: 0;
        pointer-events: none;
    }
    .pill-content {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 7px 12px;
        border-radius: 9px;
        font-size: 0.82rem;
        font-weight: 750;
        color: #64748b;
        background: transparent;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .status-pill:hover .pill-content {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.5);
    }

    /* Checked State for Present (Emerald) */
    .status-pill.status-present input:checked + .pill-content {
        background: #059669;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(5, 150, 105, 0.3);
    }
    /* Checked State for Sick (Amber) */
    .status-pill.status-sick input:checked + .pill-content {
        background: #d97706;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(217, 119, 6, 0.3);
    }
    /* Checked State for Permission (Sky Blue) */
    .status-pill.status-permission input:checked + .pill-content {
        background: #0284c7;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.3);
    }
    /* Checked State for Absent (Rose/Red) */
    .status-pill.status-absent input:checked + .pill-content {
        background: #e11d48;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(225, 29, 72, 0.3);
    }

    /* Input Catatan */
    .note-input-field {
        width: 100%;
        height: 40px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        padding: 0 12px;
        font-size: 0.84rem;
        color: #1e293b;
        background: #ffffff;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .note-input-field:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }

    /* Mobile Responsive Card Layout */
    @media (max-width: 768px) {
        .presensi-card-header {
            padding: 18px 20px;
        }
        .batch-control-bar {
            padding: 12px 16px;
        }
        .presensi-table, 
        .presensi-table thead, 
        .presensi-table tbody, 
        .presensi-table tr, 
        .presensi-table td {
            display: block;
            width: 100%;
        }
        .presensi-table thead {
            display: none;
        }
        .presensi-table tr {
            padding: 16px;
            border-bottom: 8px solid #f8fafc;
        }
        .presensi-table td {
            padding: 0;
            border: none;
        }
        .presensi-table td:first-child {
            display: none; /* Hide No on mobile */
        }
        .presensi-table td:nth-child(2) {
            margin-bottom: 12px;
        }
        .presensi-table td:nth-child(3) {
            margin-bottom: 10px;
        }
        .segmented-status-group {
            display: flex;
            width: 100%;
            justify-content: space-between;
        }
        .status-pill {
            flex: 1;
            text-align: center;
        }
        .pill-content {
            width: 100%;
            padding: 10px 4px;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<div style="max-width: 980px; margin: 0 auto;">
    <!-- Tombol Kembali -->
    <a href="{{ route('attendance.index') }}" class="btn-back-pill">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Pemilihan Kegiatan</span>
    </a>

    <!-- Notifikasi Riwayat / Semester -->
    @if(!$contextYear->is_active || $contextYear->active_semester != $semester)
        <div style="background: #fffbeb; border: 1.5px solid #fde68a; color: #92400e; padding: 14px 18px; border-radius: 14px; margin-bottom: 18px; display: flex; align-items: center; gap: 12px; font-size: 0.9rem; font-weight: 600;">
            <i class="fas fa-history" style="font-size: 1.15rem; color: #d97706;"></i>
            <div>
                <strong>Mode Arsip / Riwayat:</strong> Anda sedang meninjau data untuk 
                <u>Tahun {{ $contextYear->name }} Semester {{ $semester }}</u>.
            </div>
        </div>
    @endif

    <!-- Kartu Utama Presensi -->
    <div class="presensi-main-card">
        <!-- Header Info Kegiatan -->
        <div class="presensi-card-header">
            <div class="presensi-info-group">
                <div class="presensi-icon-badge">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div>
                    <h3 class="presensi-title">{{ $eskul->name }}</h3>
                    <div class="presensi-subtitle">
                        <span><i class="far fa-calendar-alt" style="color: #059669;"></i> {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}</span>
                        <span>•</span>
                        <span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 800;">Tahun {{ $contextYear->name }}</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="background: #f1f5f9; color: #334155; font-size: 0.84rem; font-weight: 750; padding: 6px 14px; border-radius: 10px; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-users" style="color: #059669;"></i>
                    <strong>{{ $students->count() }}</strong> Santri Terdaftar
                </span>
            </div>
        </div>

        <!-- Form Simpan Presensi -->
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="eskul_id" value="{{ $eskul->id }}">
            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="academic_year_id" value="{{ $yearId }}">
            <input type="hidden" name="semester" value="{{ $semester }}">

            <!-- Bar Aksi Cepat (Batch Actions) -->
            <div class="batch-control-bar">
                <span style="font-size: 0.82rem; font-weight: 700; color: #64748b; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-bolt" style="color: #f59e0b;"></i> Set Kehadiran Cepat:
                </span>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="button" onclick="setAllAttendance('present')" class="batch-btn batch-btn-present">
                        <i class="fas fa-check-double"></i> Set Semua Hadir
                    </button>
                    <button type="button" onclick="setAllAttendance('permission', 'Eskul Diliburkan')" class="batch-btn batch-btn-perm">
                        <i class="fas fa-umbrella-beach"></i> Set Semua Libur / Izin
                    </button>
                </div>
            </div>

            <!-- Tabel Daftar Santri -->
            <table class="presensi-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th style="width: 35%;">Nama Santri</th>
                        <th style="width: 35%;">Status Kehadiran</th>
                        <th style="width: 30%;">Keterangan / Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                    @php
                        $status = $existingAttendance[$student->id]->status ?? 'present';
                        $note = $existingAttendance[$student->id]->note ?? '';
                    @endphp
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: #94a3b8; font-size: 0.86rem;">
                            {{ $index + 1 }}
                        </td>
                        <td>
                            <div class="student-info-cell">
                                <div class="student-avatar" style="background: {{ ['#f0fdf4', '#eff6ff', '#fef3c7', '#fdf2f8', '#faf5ff'][$index % 5] }}; color: {{ ['#059669', '#2563eb', '#d97706', '#db2777', '#7c3aed'][$index % 5] }};">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="student-name">{{ $student->name }}</h4>
                                    <span class="student-class-badge">
                                        <i class="fas fa-graduation-cap" style="font-size: 0.7rem; margin-right: 3px;"></i> Kelas {{ $student->class }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="segmented-status-group">
                                <label class="status-pill status-present" title="Hadir">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="present" {{ $status == 'present' ? 'checked' : '' }}>
                                    <span class="pill-content"><i class="fas fa-check"></i> Hadir</span>
                                </label>
                                <label class="status-pill status-sick" title="Sakit">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="sick" {{ $status == 'sick' ? 'checked' : '' }}>
                                    <span class="pill-content"><i class="fas fa-head-side-cough"></i> Sakit</span>
                                </label>
                                <label class="status-pill status-permission" title="Izin">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="permission" {{ $status == 'permission' ? 'checked' : '' }}>
                                    <span class="pill-content"><i class="fas fa-envelope-open-text"></i> Izin</span>
                                </label>
                                <label class="status-pill status-absent" title="Alpa / Tanpa Keterangan">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="absent" {{ $status == 'absent' ? 'checked' : '' }}>
                                    <span class="pill-content"><i class="fas fa-times"></i> Alpa</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <input type="text" name="notes[{{ $student->id }}]" class="note-input-field note-input" placeholder="Tulis catatan jika izin/sakit..." value="{{ $note }}">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px 20px; color: #64748b;">
                            <i class="fas fa-user-slash" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                            <strong style="color: #334155;">Belum Ada Santri Terdaftar</strong>
                            <p style="margin: 4px 0 0; font-size: 0.85rem;">Silakan tambahkan santri ke kegiatan eskul ini melalui menu Kelola Eskul.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Footer & Tombol Simpan Solid -->
            @if($students->isNotEmpty())
            <div style="padding: 24px 28px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span style="font-size: 0.84rem; color: #64748b;">
                    Pastikan seluruh data kehadiran sudah sesuai sebelum menyimpan.
                </span>
                <button type="submit" class="btn-submit" style="height: 48px; padding: 0 32px; border-radius: 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; border: none; font-size: 0.95rem; font-weight: 800; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 6px 16px rgba(5, 150, 105, 0.25); cursor: pointer; transition: all 0.25s ease;">
                    <i class="fas fa-save"></i>
                    <span>Simpan Presensi Pertemuan</span>
                </button>
            </div>
            @endif
        </form>
    </div>
</div>

<script>
    function setAllAttendance(status, defaultNote = '') {
        const radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);
        radios.forEach(r => {
            r.checked = true;
            r.dispatchEvent(new Event('change'));
        });
        if (defaultNote) {
            const noteInputs = document.querySelectorAll('.note-input');
            noteInputs.forEach(n => {
                if (!n.value) n.value = defaultNote;
            });
        }
    }
</script>
@endsection
