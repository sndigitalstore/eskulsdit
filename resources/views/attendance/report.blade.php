@extends('layouts.app')

@section('title', 'Laporan Absensi per Eskul')
@section('page-title', 'Laporan Absensi')

@section('content')
<div class="card">
    <div class="page-header" style="margin-bottom: 2rem;">
        <div>
            <h2>Rekapitulasi Absensi</h2>
            <p style="color: #666; margin: 0;">Eskul: <strong>{{ $eskul->name }}</strong> | Tahun: {{ $year->name }}</p>
        </div>
        <button onclick="window.print()" class="btn-submit" style="width: auto; background: #333;">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
    </div>

    <style>
        @media print {
            body { background: white; }
            .sidebar, .header, .btn-submit, .page-header button { display: none !important; }
            .card { box-shadow: none; border: none; padding: 0; }
            .content { margin: 0; padding: 0; }
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #f8f9fa; font-weight: 600; color: #333; }
        td { color: #555; }
        .text-left { text-align: left; }
    </style>

    @if(session('success'))
        <div class="no-print" style="background: #e0fbf0; color: #2ecc71; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Section Riwayat Tanggal Absensi (Khusus Kelola/Hapus jika ganda) -->
    @if(isset($recordedDates) && count($recordedDates) > 0)
    <div class="no-print" style="margin-bottom: 25px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
        <h4 style="margin-top: 0; margin-bottom: 12px; color: #1e293b; font-size: 1rem; display: flex; align-items: center; gap: 8px;">
            <i class="far fa-calendar-alt" style="color: #6366f1;"></i> Riwayat Tanggal Absensi Ter-input ({{ count($recordedDates) }} Pertemuan)
        </h4>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            @foreach($recordedDates as $rd)
                <div style="background: white; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 8px; font-size: 0.88rem; display: flex; align-items: center; gap: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <span><strong>{{ \Carbon\Carbon::parse($rd->date)->isoFormat('D MMMM Y') }}</strong> ({{ $rd->student_count }} siswa)</span>
                    
                    @if(Auth::user()->role === 'admin')
                        <form action="{{ route('attendance.destroy-date') }}" method="POST" class="confirm-delete" data-confirm="Apakah Anda yakin ingin menghapus seluruh data absensi siswa tanggal {{ \Carbon\Carbon::parse($rd->date)->isoFormat('D MMMM Y') }}? Rekapitulasi absensi akan otomatis berkurang." style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="eskul_id" value="{{ $eskul->id }}">
                            <input type="hidden" name="date" value="{{ $rd->date }}">
                            <button type="submit" title="Hapus Data Tanggal Ini" style="background: #fee2e2; color: #ef4444; border: none; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s;">
                                <i class="fas fa-trash" style="font-size: 0.75rem;"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%" class="text-left">Nama Siswa</th>
                    <th width="15%">Kelas</th>
                    <th width="10%" style="color: #27ae60;">Hadir</th>
                    <th width="10%" style="color: #f1c40f;">Sakit</th>
                    <th width="10%" style="color: #e67e22;">Izin</th>
                    <th width="10%" style="color: #e74c3c;">Alpa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">
                        <strong>{{ $student->name }}</strong>
                    </td>
                    <td>{{ $student->class }}</td>
                    <td style="font-weight: bold; color: #27ae60;">{{ $student->h }}</td>
                    <td style="font-weight: bold; color: #f1c40f;">{{ $student->s }}</td>
                    <td style="font-weight: bold; color: #e67e22;">{{ $student->i }}</td>
                    <td style="font-weight: bold; color: #e74c3c;">{{ $student->a }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 2rem; color: #999;">Belum ada data siswa untuk tahun ajaran ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
