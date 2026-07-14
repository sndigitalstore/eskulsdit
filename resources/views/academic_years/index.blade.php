@extends('layouts.app')

@section('title', 'Tahun Ajaran')
@section('page-title', 'Pengaturan Tahun Ajaran')

@section('content')
<div class="card">
    <div class="page-header">
        <div>
            <h2>Daftar Tahun Ajaran</h2>
            <p style="color: #888;">Kelola tahun ajaran aktif sistem.</p>
        </div>
        <button onclick="document.getElementById('addYearModal').style.display='flex'" class="btn-action-header btn-blue">
            <i class="fas fa-plus"></i> Tambah Tahun Ajaran
        </button>
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
                <th>Tahun Ajaran</th>
                <th>Semester Aktif</th>
                <th>Status</th>
                <th>Periode</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($years as $year)
            <tr style="{{ $year->is_active ? 'background-color: #f0fdf4;' : '' }}">
                <td style="font-weight: bold;">
                    {{ $year->name }}
                    @if($year->is_active)
                        <span style="font-size: 0.8em; background: #2ecc71; color: white; padding: 2px 6px; border-radius: 4px; margin-left: 5px;">Aktif</span>
                    @endif
                </td>
                <td>
                    @if($year->is_active)
                        <form action="{{ route('academic-years.update', $year->id) }}" method="POST" style="display: flex; align-items: center; gap: 5px;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $year->name }}">
                            <select name="active_semester" onchange="this.form.submit()" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc; background: white; cursor: pointer;">
                                <option value="1" {{ $year->active_semester == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                                <option value="2" {{ $year->active_semester == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                            </select>
                        </form>
                    @else
                        <span style="color: #999;">-</span>
                    @endif
                </td>
                <td>
                    @if($year->is_active)
                        <span style="color: #2ecc71; font-weight: bold;">Sedang Berjalan</span>
                    @else
                        <span style="color: #888;">Tidak Aktif</span>
                    @endif
                </td>
                <td>
                    {{ $year->start_date ? date('d M Y', strtotime($year->start_date)) : '-' }} 
                    s/d 
                    {{ $year->end_date ? date('d M Y', strtotime($year->end_date)) : '-' }}
                </td>
                <td>
                    <div style="display: flex; gap: 5px;">
                        <form action="{{ route('academic-years.activate', $year->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-view" style="background: #2ecc71; opacity: {{ $year->is_active ? '0.5' : '1' }};" title="{{ $year->is_active ? 'Sudah Aktif' : 'Aktifkan' }}">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        
                        <!-- Copy Semester 1 Data to 2 (Only if Active Semester is 2) -->
                        @if($year->is_active && $year->active_semester == '2')
                        <form action="{{ route('academic-years.copy-semester', $year->id) }}" method="POST" style="display:inline;" data-confirm="Salin semua data pilihan eskul siswa dari Semester 1 ke Semester 2? Siswa yang sudah punya data di Sem 2 tidak akan ditimpa.">
                            @csrf
                            <button type="submit" class="btn-edit" style="background: #7367f0;" title="Salin Data dari Sem 1">
                                <i class="fas fa-copy"></i>
                            </button>
                        </form>
                        @endif

                        <!-- Delete (Only if not active) -->
                        @if(!$year->is_active)
                        <form action="{{ route('academic-years.destroy', $year->id) }}" method="POST" style="display:inline;" data-confirm="PERINGATAN KRITIS: Menghapus tahun ajaran akan menghapus SELURUH data absensi dan nilai pada tahun tersebut. Yakin ingin melanjutkan?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-edit" style="background: #e74c3c;" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Add Year -->
<div id="addYearModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; padding: 2rem; border-radius: 15px; width: 400px; max-width: 90%;">
        <h3>Tambah Tahun Ajaran</h3>
        <form action="{{ route('academic-years.store') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Nama Tahun Ajaran</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: 2025/2026" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Semester Awal</label>
                <select name="active_semester" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="1">Semester 1 (Ganjil)</option>
                    <option value="2">Semester 2 (Genap)</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="text-align: right; margin-top: 1rem;">
                <button type="button" onclick="document.getElementById('addYearModal').style.display='none'" style="padding: 10px 20px; background: #eee; border: none; border-radius: 8px; cursor: pointer; margin-right: 10px;">Batal</button>
                <button type="submit" style="padding: 10px 20px; background: #5381ff; color: white; border: none; border-radius: 8px; cursor: pointer;">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
