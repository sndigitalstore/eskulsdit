@extends('layouts.app')

@section('title', 'Data Absensi Guru')
@section('page-title', 'Data Absensi Guru')

@section('content')
<div class="card" style="overflow-x: auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <form action="{{ route('teacher-attendance.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex: 1; flex-wrap: wrap;">
            <input type="month" name="month" value="{{ $month }}" class="form-control" onchange="this.form.submit()" style="max-width: 200px;">
            <button type="submit" class="btn-action-header btn-blue" style="white-space: nowrap;"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('teacher-attendance.export', ['month' => $month]) }}" class="btn-action-header btn-green" style="white-space: nowrap; text-decoration: none;">
                <i class="fas fa-file-excel"></i> Excel Bulanan
            </a>
            <a href="{{ route('teacher-attendance.export') }}" class="btn-action-header btn-dark" style="white-space: nowrap; text-decoration: none;">
                <i class="fas fa-download"></i> Excel Semua
            </a>
            <button type="button" onclick="document.getElementById('manualAttendanceModal').style.display='flex'" class="btn-action-header btn-orange" style="white-space: nowrap;">
                <i class="fas fa-plus"></i> Input Manual
            </button>
        </form>
        
         <div style="font-weight: bold; color: #555; text-align: right; margin-left: auto;">
             {{ \Carbon\Carbon::parse($month . '-01')->isoFormat('MMMM Y') }}
         </div>
    </div>

    @if(session('success'))
        <div style="background: #e0fbf0; color: #2ecc71; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #ffe0e0; color: #e74c3c; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nama Guru</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
            <tr>
                <td>{{ $attendance->user->name }}</td>
                <td>{{ $attendance->clock_in_time }}</td>
                <td>
                    @if($attendance->status == 'present') <span style="background: #e0fbef; color: #27ae60; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">Hadir</span>
                    @elseif($attendance->status == 'sick') <span style="background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">Sakit</span>
                    @elseif($attendance->status == 'permission') <span style="background: #cce5ff; color: #004085; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">Izin</span>
                    @else <span style="background: #f8d7da; color: #721c24; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">Alpha</span>
                    @endif
                </td>
                <td>
                    {{ $attendance->note }}
                    @if($attendance->substitute_name)
                        <div style="margin-top: 5px; background: #fffbe6; border: 1px dashed #f1c40f; padding: 5px; border-radius: 5px; font-size: 0.8rem; color: #d35400;">
                            <i class="fas fa-exchange-alt"></i> Pengganti: <b>{{ $attendance->substitute_name }}</b>
                        </div>
                    @endif
                </td>
                <td>
                    <form action="{{ route('teacher-attendance.destroy', $attendance->id) }}" method="POST" class="confirm-delete" data-confirm="Apakah Anda yakin ingin menghapus data absensi guru ini?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer;"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #bbb;">Tidak ada data absensi pada tanggal ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Input Absensi Manual -->
<div id="manualAttendanceModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; padding: 2rem; border-radius: 15px; width: 450px; max-width: 90%; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: #2c3e50;"><i class="fas fa-fingerprint"></i> Input Absensi Guru Manual</h3>
        
        <form action="{{ route('teacher-attendance.store') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #4a5568;">Pilih Guru</label>
                <select name="user_id" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="">-- Pilih Guru Pembina --</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}">{{ $t->name }} {{ $t->eskul ? '('.$t->eskul->name.')' : '' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #4a5568;">Pilih Tanggal</label>
                <input type="date" name="date" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;" max="{{ date('Y-m-d') }}">
            </div>

            <div class="form-group" style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #4a5568;">Jam Masuk (Waktu Masuk)</label>
                <input type="time" name="clock_in_time" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;" value="14:00">
            </div>

            <div class="form-group" style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #4a5568;">Status Kehadiran</label>
                <select name="status" id="manualStatus" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="present">Hadir</option>
                    <option value="sick">Sakit</option>
                    <option value="permission">Izin</option>
                </select>
            </div>

            <!-- Substitute Teacher Section (Only if sick/permission) -->
            <div id="manualSubstituteGroup" style="margin-bottom: 1.2rem; display: none; background: #fff8eb; padding: 15px; border-radius: 10px; border: 1px dashed #f39c12;">
                <label style="display:block; margin-bottom: 8px; color: #d35400; font-weight: bold;"><i class="fas fa-user-friends"></i> Informasi Guru Pengganti</label>

                <div style="display: flex; gap: 15px; margin-bottom: 12px; font-size: 0.88rem;">
                    <label style="cursor: pointer;">
                        <input type="radio" name="substitute_type" value="registered" checked id="manualTypeRegistered"> Guru Terdaftar
                    </label>
                    <label style="cursor: pointer; margin-left: 10px;">
                        <input type="radio" name="substitute_type" value="manual" id="manualTypeManual"> Ketik Manual
                    </label>
                </div>

                <div id="manualSubstituteUserSelect" style="margin-bottom: 8px;">
                    <select name="substitute_user_id" class="form-control" style="border-color: #f39c12; width: 100%; padding: 8px; border-radius: 6px; background: white;">
                        <option value="">-- Pilih Guru Pengganti --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }} {{ $t->eskul ? '('.$t->eskul->name.')' : '' }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="manualSubstituteNameInput" style="display: none; margin-bottom: 8px;">
                    <input type="text" name="substitute_name" class="form-control" placeholder="Masukkan nama guru pengganti..." style="border-color: #f39c12; width: 100%; padding: 8px; border-radius: 6px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #4a5568;">Catatan / Keterangan</label>
                <input type="text" name="note" class="form-control" placeholder="Catatan opsional..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div style="text-align: right;">
                <button type="button" onclick="document.getElementById('manualAttendanceModal').style.display='none'" style="padding: 10px 20px; background: #eee; border: none; border-radius: 8px; cursor: pointer; margin-right: 10px; font-weight: 600; color: #4a5568;">Batal</button>
                <button type="submit" class="btn-submit" style="width: auto; padding: 10px 25px; border-radius: 8px;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const manualStatus = document.getElementById('manualStatus');
    const manualSubstituteGroup = document.getElementById('manualSubstituteGroup');
    const manualTypeRegistered = document.getElementById('manualTypeRegistered');
    const manualTypeManual = document.getElementById('manualTypeManual');
    const manualSubstituteUserSelect = document.getElementById('manualSubstituteUserSelect');
    const manualSubstituteNameInput = document.getElementById('manualSubstituteNameInput');

    manualStatus.addEventListener('change', function() {
        if (this.value === 'sick' || this.value === 'permission') {
            manualSubstituteGroup.style.display = 'block';
        } else {
            manualSubstituteGroup.style.display = 'none';
        }
    });

    function toggleManualSubstituteFields() {
        if (manualTypeRegistered.checked) {
            manualSubstituteUserSelect.style.display = 'block';
            manualSubstituteNameInput.style.display = 'none';
        } else {
            manualSubstituteUserSelect.style.display = 'none';
            manualSubstituteNameInput.style.display = 'block';
        }
    }

    manualTypeRegistered.addEventListener('change', toggleManualSubstituteFields);
    manualTypeManual.addEventListener('change', toggleManualSubstituteFields);

    // Parse URL Query Parameters and Pre-fill form if open_modal=1
    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const openModal = urlParams.get('open_modal');
        const userId = urlParams.get('user_id');
        const date = urlParams.get('date');

        if (openModal === '1') {
            const modal = document.getElementById('manualAttendanceModal');
            if (modal) {
                modal.style.display = 'flex';
            }
            if (userId) {
                const userSelect = document.querySelector('select[name="user_id"]');
                if (userSelect) userSelect.value = userId;
            }
            if (date) {
                const dateInput = document.querySelector('input[name="date"]');
                if (dateInput) dateInput.value = date;
            }
        }
    });
</script>
@endsection
