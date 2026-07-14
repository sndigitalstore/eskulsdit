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
