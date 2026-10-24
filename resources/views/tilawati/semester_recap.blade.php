@extends('layouts.app')

@section('title', 'Rekap Semester & Evaluasi Munaqosah - ' . $group->name)
@section('page-title', 'Rekap Semester Tilawati')

@push('styles')
<style>
    .recap-container {
        max-width: 1100px;
        margin: 0 auto;
        padding-bottom: 50px;
    }
    .semester-hero {
        background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0f766e 100%);
        border-radius: 20px;
        padding: 24px 28px;
        color: white;
        margin-bottom: 22px;
        box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    .munaqosah-ready-card {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 2px solid #fde68a;
        border-radius: 18px;
        padding: 18px 22px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.15);
    }
    .badge-munaqosah-ready {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.76rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 2px 6px rgba(217, 119, 6, 0.3);
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
<div class="recap-container">

    <!-- Top Navigation -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
        <a href="{{ route('tilawati.attendance.index', $group->id) }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat Pertemuan
        </a>
        <span style="font-size: 0.82rem; font-weight: 800; color: #047857; background: #ecfdf5; padding: 4px 14px; border-radius: 20px;">
            <i class="fas fa-file-invoice"></i> Evaluasi Akhir Semester & Target Kenaikan
        </span>
    </div>

    <!-- Hero Card -->
    <div class="semester-hero">
        <div>
            <span style="background: rgba(255,255,255,0.2); font-size: 0.74rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; text-transform: uppercase;">
                Laporan Capaian Semester {{ $semester == '1' ? 'Ganjil' : 'Genap' }}
            </span>
            <h2 style="font-size: 1.6rem; font-weight: 900; margin: 8px 0 4px 0;">
                {{ $group->name }}
            </h2>
            <div style="font-size: 0.88rem; opacity: 0.92; display: flex; gap: 16px; flex-wrap: wrap;">
                <span><i class="fas fa-chalkboard-teacher"></i> Pembimbing: <strong>{{ $group->mentor_name }}</strong></span>
                <span><i class="fas fa-users"></i> {{ $students->count() }} Santri</span>
                <span><i class="fas fa-calendar-check"></i> Total: <strong>{{ $totalSessions }} Pertemuan</strong></span>
            </div>
        </div>

        <!-- Filter Semester Form -->
        <form action="{{ route('tilawati.attendance.semester', $group->id) }}" method="GET" style="display: flex; gap: 8px; align-items: center; background: rgba(255,255,255,0.15); padding: 8px 12px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.25);">
            <select name="semester" style="padding: 7px 10px; border-radius: 8px; border: none; font-weight: 700; font-size: 0.84rem; background: white; color: #0f172a;">
                <option value="1" {{ $semester == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                <option value="2" {{ $semester == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
            </select>
            <button type="submit" style="background: #ffffff; color: #065f46; border: none; padding: 7px 12px; border-radius: 8px; font-weight: 800; font-size: 0.84rem; cursor: pointer;">
                Pilih
            </button>
        </form>
    </div>

    <!-- Banner Kesiapan Munaqosah Jilid 6 -->
    @if($readyForMunaqosahCount > 0 || stripos($group->target_level ?? '', 'Jilid 6') !== false)
        <div class="munaqosah-ready-card">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 14px; background: #f59e0b; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #78350f;">
                        Kesiapan Munaqosah Tilawati (Khatam Jilid 6 ➔ Al-Qur'an)
                    </h4>
                    <p style="margin: 2px 0 0 0; font-size: 0.82rem; color: #92400e;">
                        Sebanyak <strong>{{ $readyForMunaqosahCount }} dari {{ $students->count() }} santri</strong> telah mencapai target ketuntasan Jilid 6 dan siap diuji oleh Dewan Munaqisy.
                    </p>
                </div>
            </div>

            <button type="button" onclick="openBulkMunaqosahModal()" 
                    style="background: #f59e0b; color: white; border: none; padding: 11px 22px; border-radius: 12px; font-size: 0.9rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);">
                <i class="fas fa-award"></i> Daftarkan 1 Kelompok Ini ke Munaqosah
            </button>
        </div>
    @endif

    <!-- Table Rekap Capaian Semester -->
    <div class="card" style="padding: 0; overflow: hidden; border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        <div style="padding: 16px 22px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">
                <i class="fas fa-chart-line" style="color: #059669;"></i> Ringkasan Progres & Kelulusan Pertemuan Tiap Santri
            </h3>
            <span style="font-size: 0.78rem; color: #64748b;">
                Standar Lanjut Halaman: <strong>≥ 70%</strong>
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; text-align: left;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-weight: 800; font-size: 0.8rem; text-transform: uppercase;">
                        <th style="padding: 12px 18px; width: 40px; text-align: center;">No</th>
                        <th style="padding: 12px 18px;">Nama Santri</th>
                        <th style="padding: 12px 18px; width: 80px; text-align: center;">Kelas</th>
                        <th style="padding: 12px 18px;">Posisi Awal Semester</th>
                        <th style="padding: 12px 18px;">Posisi Akhir Semester</th>
                        <th style="padding: 12px 18px; text-align: center;">Kehadiran</th>
                        <th style="padding: 12px 18px; text-align: center;">Rasio Lanjut (≥ 70%)</th>
                        <th style="padding: 12px 18px; text-align: center;">Status Munaqosah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $idx => $st)
                        @php
                            $rec = $studentRecaps[$st->id] ?? [
                                'initial' => '-', 'final' => '-', 'present' => 0, 'passed' => 0, 'repeat' => 0, 'rate' => 0, 'is_ready' => false
                            ];
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                            <td style="padding: 12px 18px; text-align: center; font-weight: 700; color: #64748b;">{{ $idx + 1 }}</td>
                            <td style="padding: 12px 18px;">
                                <div style="font-weight: 800; color: #0f172a;">{{ $st->name }}</div>
                                <small style="color: #64748b; font-size: 0.78rem;">NIS: {{ $st->nis ?? '-' }}</small>
                            </td>
                            <td style="padding: 12px 18px; text-align: center; font-weight: 700; color: #64748b;">
                                {{ $st->class ?? '-' }}
                            </td>
                            <td style="padding: 12px 18px; font-weight: 700; color: #475569;">
                                {{ $rec['initial'] }}
                            </td>
                            <td style="padding: 12px 18px; font-weight: 800; color: #0f172a;">
                                <span style="background: #ecfdf5; color: #047857; padding: 3px 8px; border-radius: 6px;">
                                    {{ $rec['final'] }}
                                </span>
                            </td>
                            <td style="padding: 12px 18px; text-align: center;">
                                <span style="font-weight: 800; color: #0284c7;">{{ $rec['present'] }}</span> / {{ $totalSessions }}
                            </td>
                            <td style="padding: 12px 18px; text-align: center;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 2px;">
                                    <span style="font-weight: 900; font-size: 0.95rem; color: {{ $rec['rate'] >= 70 ? '#059669' : '#dc2626' }};">
                                        {{ $rec['rate'] }}%
                                    </span>
                                    <small style="font-size: 0.72rem; color: #64748b;">
                                        ({{ $rec['passed'] }} Lanjut, {{ $rec['repeat'] }} Ulang)
                                    </small>
                                </div>
                            </td>
                            <td style="padding: 12px 18px; text-align: center;">
                                @if($rec['is_ready'])
                                    <span class="badge-munaqosah-ready">
                                        <i class="fas fa-graduation-cap"></i> Siap Munaqosah
                                    </span>
                                @else
                                    <span style="background: #f1f5f9; color: #64748b; font-weight: 700; font-size: 0.74rem; padding: 3px 8px; border-radius: 6px;">
                                        Bimbingan Berjalan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Pendaftaran Kolektif 1 Kelompok ke Munaqosah -->
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
                    <strong style="color: #2563eb;">{{ $students->count() }} Santri</strong>
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
                    <i class="fas fa-check-circle"></i> Daftarkan Sekarang ({{ $students->count() }} Santri)
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
