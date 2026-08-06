@extends('layouts.app')

@section('title', 'Absensi Saya')
@section('page-title', 'Absensi Saya')

@section('content')
<div class="card" style="margin-bottom: 20px;">
    <h3><i class="fas fa-clock"></i> Absen Hari Ini</h3>
    <div style="font-size: 0.9rem; color: #888; margin-bottom: 15px;">
        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
    </div>

    @if($todayAttendance)
        <div style="background: #e0fbf0; color: #2ecc71; padding: 15px; border-radius: 12px; display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
            <i class="fas fa-check-circle" style="font-size: 2rem;"></i>
            <div>
                <h4 style="margin: 0;">Sudah Absen!</h4>
                <p style="margin: 0;">
                    Status: <b>{{ ucfirst($todayAttendance->status) }}</b> | 
                    Jam: <b>{{ $todayAttendance->clock_in_time }}</b>
                    @if($todayAttendance->substitute_name)
                        <br><i class="fas fa-user-friends"></i> Guru Pengganti: <b>{{ $todayAttendance->substitute_name }}</b>
                    @endif
                </p>
            </div>
        </div>

        @if($substituteToken && in_array($todayAttendance->status, ['sick', 'permission']))
            @php
                $substituteUrl = route('substitute.attendance.show', $substituteToken->token);
                $waText = urlencode("Assalamu'alaikum/Halo, berikut adalah tautan untuk mengisi absensi siswa eskul hari ini: " . $substituteUrl);
            @endphp
            <div style="background: #eff6ff; border: 1.5px solid #3b82f6; padding: 16px; border-radius: 14px; margin-top: 10px;">
                <h5 style="margin: 0 0 6px 0; color: #1e40af; font-size: 1rem;"><i class="fas fa-link me-1"></i> Tautan Absensi Siswa Untuk Guru Pengganti</h5>
                <p style="margin: 0 0 12px 0; font-size: 0.85rem; color: #3b82f6;">Bagikan link ini ke guru pengganti agar mereka bisa mengisi absensi siswa secara langsung tanpa perlu login.</p>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="btn" style="background: #25d366; color: white; border: none; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-weight: bold; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fab fa-whatsapp"></i> Bagikan ke WhatsApp
                    </a>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $substituteUrl }}'); alert('Tautan absensi berhasil disalin!');" class="btn" style="background: #3b82f6; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-weight: bold; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fas fa-copy"></i> Salin Tautan
                    </button>
                </div>
            </div>
        @endif
    @else
        <form action="{{ route('teacher-attendance.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                <label class="radio-option" style="border: 1px solid #ddd; padding: 15px; border-radius: 10px; cursor: pointer;">
                    <input type="radio" name="status" value="present" checked> Hadir
                </label>
                <label class="radio-option" style="border: 1px solid #ddd; padding: 15px; border-radius: 10px; cursor: pointer;">
                    <input type="radio" name="status" value="sick"> Sakit
                </label>
                <label class="radio-option" style="border: 1px solid #ddd; padding: 15px; border-radius: 10px; cursor: pointer;">
                    <input type="radio" name="status" value="permission"> Izin
                </label>
            </div>
            
            <div class="form-group" style="margin-bottom: 15px;">
                <input type="text" name="note" class="form-control" placeholder="Catatan Tambahan (Opsional)...">
            </div>

            <div class="form-group" id="substituteGroup" style="margin-bottom: 15px; display: none; background: #fff8eb; padding: 15px; border-radius: 10px; border: 1px dashed #f39c12;">
                <label style="display:block; margin-bottom: 8px; color: #d35400; font-weight: bold;"><i class="fas fa-user-friends"></i> Informasi Guru Pengganti</label>

                <div style="display: flex; gap: 15px; margin-bottom: 12px; font-size: 0.88rem;">
                    <label style="cursor: pointer;">
                        <input type="radio" name="substitute_type" value="registered" checked id="typeRegistered"> Guru Terdaftar di SIM
                    </label>
                    <label style="cursor: pointer;">
                        <input type="radio" name="substitute_type" value="manual" id="typeManual"> Ketik Nama Manual / Pihak Luar
                    </label>
                </div>

                <div id="substituteUserSelect" style="margin-bottom: 8px;">
                    <select name="substitute_user_id" class="form-control" style="border-color: #f39c12; width: 100%; padding: 8px; border-radius: 6px;">
                        <option value="">-- Pilih Guru Pengganti --</option>
                        @foreach($teachersList as $t)
                            <option value="{{ $t->id }}">{{ $t->name }} {{ $t->eskul ? '('.$t->eskul->name.')' : '' }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="substituteNameInput" style="display: none; margin-bottom: 8px;">
                    <input type="text" name="substitute_name" class="form-control" placeholder="Masukkan nama guru pengganti..." style="border-color: #f39c12; width: 100%; padding: 8px; border-radius: 6px;">
                </div>

                <small style="color: #e67e22; display: block; line-height: 1.4;">
                    <i class="fas fa-info-circle"></i> Jika memilih guru terdaftar, eskul Anda otomatis muncul di akun mereka hari ini (Opsi 2).<br>
                    Tautan absensi tanpa login (Opsi 1) juga akan dibuat otomatis untuk dibagikan.
                </small>
            </div>

            <script>
                const statusRadios = document.querySelectorAll('input[name="status"]');
                const typeRegistered = document.getElementById('typeRegistered');
                const typeManual = document.getElementById('typeManual');
                const substituteUserSelect = document.getElementById('substituteUserSelect');
                const substituteNameInput = document.getElementById('substituteNameInput');
                const substituteGroup = document.getElementById('substituteGroup');

                function toggleSubstituteFields() {
                    if (typeRegistered.checked) {
                        substituteUserSelect.style.display = 'block';
                        substituteNameInput.style.display = 'none';
                    } else {
                        substituteUserSelect.style.display = 'none';
                        substituteNameInput.style.display = 'block';
                    }
                }

                typeRegistered.addEventListener('change', toggleSubstituteFields);
                typeManual.addEventListener('change', toggleSubstituteFields);

                statusRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if(this.value === 'sick' || this.value === 'permission') {
                            substituteGroup.style.display = 'block';
                        } else {
                            substituteGroup.style.display = 'none';
                        }
                    });
                });
            </script>

            <button type="submit" class="btn-submit" style="width: 100%; justify-content: center;">
                <i class="fas fa-fingerprint"></i> KONFIRMASI KEHADIRAN
            </button>
        </form>
    @endif
</div>

<div class="card" style="overflow-x: auto;">
    <h3><i class="fas fa-history"></i> Riwayat Absensi</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($myAttendances as $attendance)
            <tr>
                <td>{{ date('d/m/Y', strtotime($attendance->date)) }}</td>
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
                        <br><span style="font-size: 0.8rem; color: #e67e22;"><i class="fas fa-exchange-alt"></i> Diganti: <b>{{ $attendance->substitute_name }}</b></span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; color: #bbb;">Belum ada riwayat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 15px;">
        {{ $myAttendances->links() }}
    </div>
</div>
@endsection
