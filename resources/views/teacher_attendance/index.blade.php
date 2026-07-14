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
        <div style="background: #e0fbf0; color: #2ecc71; padding: 15px; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-check-circle" style="font-size: 2rem;"></i>
            <div>
                <h4 style="margin: 0;">Sudah Absen!</h4>
                <p style="margin: 0;">
                    Status: <b>{{ ucfirst($todayAttendance->status) }}</b> | 
                    Jam: <b>{{ $todayAttendance->clock_in_time }}</b>
                </p>
            </div>
        </div>
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
                <label style="display:block; margin-bottom: 5px; color: #d35400; font-weight: bold;"><i class="fas fa-user-friends"></i> Informasi Guru Pengganti</label>
                <input type="text" name="substitute_name" class="form-control" placeholder="Masukkan nama guru yang menggantikan..." style="border-color: #f39c12;">
                <small style="color: #e67e22;">Agar rekap kehadiran dan jalannya Eskul tetap akuntabel.</small>
            </div>

            <script>
                document.querySelectorAll('input[name="status"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const substituteGroup = document.getElementById('substituteGroup');
                        if(this.value === 'sick' || this.value === 'permission') {
                            substituteGroup.style.display = 'block';
                            document.querySelector('input[name="substitute_name"]').required = true;
                        } else {
                            substituteGroup.style.display = 'none';
                            document.querySelector('input[name="substitute_name"]').required = false;
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
