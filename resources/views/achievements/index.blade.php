@extends('layouts.app')

@section('title', 'Data Prestasi')
@section('page-title', 'Data Prestasi Siswa')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3 style="margin-bottom: 5px;">Daftar Prestasi Siswa</h3>
            <p style="color: #666;">Total {{ $achievements->total() }} prestasi tercatat.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('achievements.print') }}" target="_blank" class="btn-action-header btn-dark">
                <i class="fas fa-print"></i> Cetak Laporan
            </a>
            <a href="{{ route('achievements.bulk') }}" class="btn-action-header btn-green">
                <i class="fas fa-file-import"></i> Input Masal
            </a>
            <a href="{{ route('achievements.create') }}" class="btn-action-header btn-blue">
                <i class="fas fa-plus"></i> Tambah Prestasi
            </a>
        </div>
    </div>

    @if($achievements->isEmpty())
        <div style="text-align: center; padding: 3rem; color: #999; background: #f9fafb; border-radius: 12px;">
            <i class="fas fa-trophy" style="font-size: 3rem; margin-bottom: 1rem; color: #eee;"></i><br>
            Belum ada data prestasi yang tercatat dalam sistem.
        </div>
    @else
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Nama Prestasi</th>
                        <th>Tingkat</th>
                        <th>Periode</th>
                        <th>Penyelenggara</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($achievements as $achievement)
                    <tr>
                        <td>
                            <div style="font-weight: 500;">
                                {{ \Carbon\Carbon::parse($achievement->date)->translatedFormat('d M Y') }}
                            </div>
                            <div style="font-size: 0.8rem; color: #999;">
                                {{ $achievement->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #2c3e50;">
                                <a href="{{ route('students.show', $achievement->student_id) }}" style="text-decoration: none; color: inherit;">
                                    {{ $achievement->student->name ?? 'Siswa Dihapus' }}
                                </a>
                            </div>
                            <div style="font-size: 0.85rem; color: #888;">
                                Kelas {{ $achievement->student->class ?? '-' }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #d4ac0d;">
                                <i class="fas fa-medal" style="margin-right: 5px;"></i> {{ $achievement->name }}
                            </div>
                            @if($achievement->description)
                            <div style="font-size: 0.85rem; color: #666; font-style: italic;">
                                {{ \Illuminate\Support\Str::limit($achievement->description, 50) }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <span style="background: #fef9e7; color: #d4ac0d; padding: 4px 10px; border-radius: 15px; font-size: 0.85rem; font-weight: 600; border: 1px solid #f9e79f;">
                                {{ $achievement->level }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 500; color: #444;">
                                {{ $achievement->academicYear->name ?? '-' }}
                            </div>
                            <div style="font-size: 0.8rem; color: #7f8c8d;">
                                Semester {{ $achievement->semester ?? '-' }}
                            </div>
                        </td>
                        <td>{{ $achievement->organizer ?? '-' }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('achievements.edit', $achievement->id) }}" class="btn-submit" style="padding: 5px 10px; font-size: 0.8rem; background: #3498db; width: auto;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('achievements.destroy', $achievement->id) }}" method="POST" onsubmit="return confirm('Hapus data prestasi ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-submit" style="padding: 5px 10px; font-size: 0.8rem; background: #e74c3c; width: auto; border: none;" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 20px;">
            {{ $achievements->links() }}
        </div>
    @endif
</div>
@endsection
