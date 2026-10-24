@extends('layouts.app')

@section('title', 'Sesi Pertemuan & Absensi Tilawati - ' . $group->name)
@section('page-title', 'Sesi Pertemuan Tilawati')

@push('styles')
<style>
    .tilawati-hero {
        background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
        border-radius: 20px;
        padding: 24px 28px;
        color: white;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    .tilawati-stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 24px;
    }
    .tilawati-stat-card {
        background: white;
        border-radius: 16px;
        padding: 18px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s;
    }
    .tilawati-stat-card:hover {
        transform: translateY(-2px);
    }
    .btn-action-pro {
        padding: 9px 16px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-action-pro:hover {
        transform: translateY(-2px);
    }
    .modal-backdrop {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 16px;
    }
    .modal-content-card {
        background: white;
        border-radius: 20px;
        max-width: 580px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    }
</style>
@endpush

@section('content')
<div style="margin-bottom: 25px;">

    <!-- Breadcrumb & Navigasi -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <a href="{{ route('tilawati-groups.show', $group->id) }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Detail Kelompok
        </a>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('tilawati.attendance.monthly', $group->id) }}" class="btn-action-pro" style="background: white; border: 1.5px solid #cbd5e1; color: #334155;">
                <i class="fas fa-calendar-alt" style="color: #0d9488;"></i> Rekap Bulanan
            </a>
            <a href="{{ route('tilawati.attendance.semester', $group->id) }}" class="btn-action-pro" style="background: white; border: 1.5px solid #cbd5e1; color: #334155;">
                <i class="fas fa-file-invoice" style="color: #6366f1;"></i> Rekap Semester
            </a>
        </div>
    </div>

    <!-- Hero Card -->
    <div class="tilawati-hero">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <span style="background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 20px; font-size: 0.74rem; font-weight: 800; text-transform: uppercase;">
                    Metode Tilawati Klasikal & Sorogan
                </span>
                <span style="background: #fef08a; color: #854d0e; padding: 3px 10px; border-radius: 20px; font-size: 0.76rem; font-weight: 800;">
                    <i class="fas fa-book-open"></i> {{ $group->target_level ?: 'Jilid 1' }}
                </span>
            </div>
            <h2 style="font-size: 1.6rem; font-weight: 900; margin: 8px 0 4px 0;">
                {{ $group->name }}
            </h2>
            <div style="font-size: 0.88rem; opacity: 0.92; display: flex; gap: 16px; flex-wrap: wrap;">
                <span><i class="fas fa-chalkboard-teacher"></i> Pembimbing: <strong>{{ $group->mentor_name }}</strong></span>
                <span><i class="fas fa-users"></i> Anggota: <strong>{{ $totalSiswa }} Santri</strong></span>
                <span><i class="fas fa-calendar"></i> TP: <strong>{{ $selectedYear->name ?? '-' }}</strong></span>
            </div>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('tilawati.attendance.create', $group->id) }}" class="btn-action-pro" style="background: #ffffff; color: #065f46; font-size: 0.92rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <i class="fas fa-plus-circle" style="color: #059669;"></i> + Input Sesi Hari Ini
            </a>
            
            <button type="button" onclick="openBulkMunaqosahModal()" class="btn-action-pro" style="background: #f59e0b; color: white; font-size: 0.92rem; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);">
                <i class="fas fa-award"></i> Daftarkan 1 Kelompok ke Munaqosah
            </button>
        </div>
    </div>

    <!-- Aturan Tilawati Banner -->
    <div style="background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 14px; padding: 14px 18px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-info-circle" style="color: #059669; font-size: 1.25rem;"></i>
            <div style="font-size: 0.84rem; color: #166534;">
                <strong>Standar Pembelajaran Tilawati:</strong> Setiap pertemuan membaca <strong>4 Halaman di Alat Peraga</strong> dan <strong>1 Halaman di Buku Tilawati</strong>. Ketentuan ketuntasan: Nilai <strong>≥ 70%</strong> Lanjut ke halaman berikutnya, dan Nilai <strong>&lt; 70%</strong> Mengulang halaman yang sama.
            </div>
        </div>
        <span style="background: #dcfce7; color: #15803d; font-size: 0.76rem; font-weight: 800; padding: 4px 10px; border-radius: 8px;">
            Standar Ketuntasan: 70%
        </span>
    </div>

    <!-- Stat Cards -->
    @php
        $totalSessionsCount = $sessions->count();
        $totalPassedGlobal = $sessions->sum('passed_count');
        $totalRepeatGlobal = $sessions->sum('repeat_count');
        $totalHadirGlobal = $sessions->sum('hadir_count');
        $globalPassingRate = $totalHadirGlobal > 0 ? round(($totalPassedGlobal / $totalHadirGlobal) * 100) : 0;
    @endphp
    <div class="tilawati-stat-grid">
        <div class="tilawati-stat-card">
            <div>
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Sesi</span>
                <h3 style="font-size: 1.8rem; font-weight: 900; color: #0f172a; margin: 4px 0 0 0;">{{ $totalSessionsCount }}</h3>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fas fa-history"></i>
            </div>
        </div>

        <div class="tilawati-stat-card">
            <div>
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Hadir</span>
                <h3 style="font-size: 1.8rem; font-weight: 900; color: #0284c7; margin: 4px 0 0 0;">{{ $totalHadirGlobal }}</h3>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #f0f9ff; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fas fa-user-check"></i>
            </div>
        </div>

        <div class="tilawati-stat-card">
            <div>
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Tingkat Lanjut (≥ 70%)</span>
                <h3 style="font-size: 1.8rem; font-weight: 900; color: #059669; margin: 4px 0 0 0;">{{ $globalPassingRate }}%</h3>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fas fa-check-double"></i>
            </div>
        </div>

        <div class="tilawati-stat-card">
            <div>
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Perlu Mengulang</span>
                <h3 style="font-size: 1.8rem; font-weight: 900; color: #e11d48; margin: 4px 0 0 0;">{{ $totalRepeatGlobal }}</h3>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #fff1f2; color: #e11d48; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fas fa-redo"></i>
            </div>
        </div>
    </div>

    <!-- Riwayat Sesi Pertemuan Table Card -->
    <div class="card" style="padding: 0; overflow: hidden; border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        <div style="padding: 18px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">
                    <i class="fas fa-calendar-check" style="color: #059669;"></i> Riwayat Sesi Pembelajaran Tilawati
                </h3>
                <p style="font-size: 0.82rem; color: #64748b; margin: 2px 0 0 0;">
                    Daftar tanggal pertemuan, materi 4 hal peraga & 1 hal buku, dan ketuntasan santri.
                </p>
            </div>

            <!-- Filter Tahun Ajaran -->
            <form action="{{ route('tilawati.attendance.index', $group->id) }}" method="GET" style="display: flex; gap: 8px; align-items: center;">
                <select name="year_id" onchange="this.form.submit()" style="padding: 7px 12px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.85rem; font-weight: 700; color: #0f172a; background: white;">
                    @foreach($academicYears as $y)
                        <option value="{{ $y->id }}" {{ $yearId == $y->id ? 'selected' : '' }}>TP {{ $y->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($sessions->isEmpty())
            <div style="padding: 50px 20px; text-align: center;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: #f0fdf4; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 16px auto;">
                    <i class="fas fa-book-reader"></i>
                </div>
                <h4 style="font-weight: 800; color: #1e293b; margin: 0 0 6px 0;">Belum Ada Sesi Pertemuan</h4>
                <p style="font-size: 0.88rem; color: #64748b; margin: 0 0 16px 0; max-width: 400px; margin-inline: auto;">
                    Mulai catat presensi santri dan penilaian 4 halaman peraga & 1 halaman buku tilawati hari ini.
                </p>
                <a href="{{ route('tilawati.attendance.create', $group->id) }}" class="btn-action-pro" style="background: #059669; color: white;">
                    <i class="fas fa-plus"></i> Input Sesi Pertemuan Pertama
                </a>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; text-align: left;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-weight: 800; font-size: 0.82rem; text-transform: uppercase;">
                            <th style="padding: 12px 16px;">Tanggal Sesi</th>
                            <th style="padding: 12px 16px;">Jilid</th>
                            <th style="padding: 12px 16px;">Alat Peraga (4 Hal)</th>
                            <th style="padding: 12px 16px;">Buku Tilawati (1 Hal)</th>
                            <th style="padding: 12px 16px; text-align: center;">Kehadiran</th>
                            <th style="padding: 12px 16px; text-align: center;">Ketuntasan Santri</th>
                            <th style="padding: 12px 16px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $sess)
                            @php
                                $sDate = $sess->session_date->format('Y-m-d');
                                $formattedDate = $sess->session_date->translatedFormat('d M Y');
                                $dayName = $sess->session_date->translatedFormat('l');
                            @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 800; color: #0f172a;">{{ $formattedDate }}</div>
                                    <small style="color: #64748b; font-size: 0.78rem;">{{ $dayName }}</small>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <span style="background: #ecfdf5; color: #047857; font-weight: 800; padding: 3px 8px; border-radius: 6px; font-size: 0.78rem;">
                                        {{ $sess->jilid }}
                                    </span>
                                </td>
                                <td style="padding: 14px 16px; font-weight: 700; color: #0f172a;">
                                    <i class="fas fa-chalkboard" style="color: #0d9488; margin-right: 4px;"></i>
                                    Hal {{ $sess->peraga_page_start }} s/d {{ $sess->peraga_page_end }}
                                </td>
                                <td style="padding: 14px 16px; font-weight: 700; color: #0f172a;">
                                    <i class="fas fa-book-open" style="color: #6366f1; margin-right: 4px;"></i>
                                    Halaman {{ $sess->buku_page }}
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <span style="background: #f0fdf4; color: #166534; font-weight: 800; padding: 4px 10px; border-radius: 8px;">
                                        {{ $sess->hadir_count }} / {{ $sess->total_students }} Hadir
                                    </span>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                        <span style="background: #dcfce7; color: #15803d; font-weight: 800; padding: 3px 8px; border-radius: 6px; font-size: 0.76rem;" title="Lanjut (Skor ≥ 70%)">
                                            ✓ {{ $sess->passed_count }} Lanjut
                                        </span>
                                        @if($sess->repeat_count > 0)
                                            <span style="background: #fee2e2; color: #b91c1c; font-weight: 800; padding: 3px 8px; border-radius: 6px; font-size: 0.76rem;" title="Mengulang (Skor < 70%)">
                                                ↻ {{ $sess->repeat_count }} Ulang
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <div style="display: flex; gap: 5px; justify-content: center;">
                                        <a href="{{ route('tilawati.attendance.session', ['group' => $group->id, 'date' => $sDate]) }}" 
                                           style="background: #059669; color: white; padding: 6px 11px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.78rem;" title="Detail Sesi & Share WA">
                                            <i class="fas fa-eye"></i> Detail & WA
                                        </a>
                                        <a href="{{ route('tilawati.attendance.create', ['group' => $group->id, 'date' => $sDate]) }}" 
                                           style="background: #3b82f6; color: white; padding: 6px 9px; border-radius: 8px; text-decoration: none; font-size: 0.78rem;" title="Edit Sesi">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('tilawati.attendance.print-session', ['group' => $group->id, 'date' => $sDate]) }}" target="_blank"
                                           style="background: #475569; color: white; padding: 6px 9px; border-radius: 8px; text-decoration: none; font-size: 0.78rem;" title="Cetak Lembar Sesi">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

<!-- Modal Pendaftaran 1 Kelompok Tilawati ke Munaqosah -->
<div id="bulkMunaqosahModal" class="modal-backdrop">
    <div class="modal-content-card">
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
                    <span style="color: #64748b;">Jumlah Santri Siap Uji:</span>
                    <strong style="color: #2563eb;">{{ $totalSiswa }} Santri</strong>
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

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.82rem; color: #0f172a; margin-bottom: 4px;">
                        Tanggal Uji Khusus:
                    </label>
                    <input type="date" name="exam_date" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.84rem; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.82rem; color: #0f172a; margin-bottom: 4px;">
                        Dewan Penguji (Munaqisy):
                    </label>
                    <select name="examiner_id" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.84rem; background: white; box-sizing: border-box;">
                        <option value="">-- Bebas / Tetapkan Nanti --</option>
                        @foreach($examiners as $ex)
                            <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 22px;">
                <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                    Catatan Pengusul untuk Dewan Penguji:
                </label>
                <textarea name="proposer_notes" rows="2" placeholder="Catatan penguasaan fashahah/tajwid anak-anak kelompok..."
                          style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.84rem; box-sizing: border-box;">Pendaftaran Kolektif Santri Lulus {{ $group->target_level ?: 'Jilid 6' }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeBulkMunaqosahModal()" style="padding: 10px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 22px; border-radius: 10px; border: none; background: #f59e0b; color: white; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">
                    <i class="fas fa-check-circle"></i> Daftarkan Sekarang ({{ $totalSiswa }} Santri)
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openBulkMunaqosahModal() {
    document.getElementById('bulkMunaqosahModal').style.display = 'flex';
}
function closeBulkMunaqosahModal() {
    document.getElementById('bulkMunaqosahModal').style.display = 'none';
}
</script>
@endsection
