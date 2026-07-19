@extends('layouts.app')

@section('title', 'Kelola Guru Pembina')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: #333;">Daftar Akun Guru Pembina</h3>
        <div style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end;">
            <a href="{{ route('teachers.print') }}" target="_blank" class="btn-action-header btn-dark">
                <i class="fas fa-print"></i> Cetak Akun
            </a>
            <a href="{{ route('teachers.bulk') }}" class="btn-action-header btn-green">
                <i class="fas fa-file-import"></i> Import Masal
            </a>
            <button type="button" class="btn-action-header btn-orange" onclick="promptResetAll()">
                <i class="fas fa-key"></i> Reset Password
            </button>
            <form id="resetAllForm" action="{{ route('teachers.reset-all') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="new_password" id="newPasswordInput">
            </form>
            <a href="{{ route('teachers.create') }}" class="btn-action-header btn-blue">
                <i class="fas fa-plus"></i> Tambah Akun
            </a>
        </div>
    </div>
    


    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Guru</th>
                <th>Username</th>
                <th>No WA</th>
                <th>Eskul Binaan</th>
                <th>Wali Kelas</th>
                <th width="15%" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $index => $teacher)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $teacher->name }}</td>
                <td>{{ $teacher->username }}</td>
                <td>{{ $teacher->phone ?? '-' }}</td>
                <td>
                    <span class="badge" style="background: #e3f2fd; color: #1976d2;">
                        {{ $teacher->eskul->name ?? '-' }}
                    </span>
                </td>
                <td>
                    @if($teacher->homeroom_class)
                        <span class="badge" style="background: #e8f5e9; color: #2e7d32;">
                            Kelas {{ $teacher->homeroom_class }}
                        </span>
                    @else
                        <span style="color: #999;">-</span>
                    @endif
                </td>
                <td class="text-center">
                    <div style="display: flex; gap: 5px; justify-content: center;">
                        <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn-action" style="color: #f39c12;">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" data-confirm="Yakin ingin menghapus akun guru pembina ini? Data absensi dan nilai yang pernah diinput guru ini akan tetap ada." style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action" style="color: #e74c3c; border: none; background: none; cursor: pointer;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px; color: #999;">
                    Belum ada akun guru pembina.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@push('scripts')
<script>
    function promptResetAll() {
        Swal.fire({
            title: 'Atur Password Baru',
            text: "Seluruh guru pembina akan menggunakan password ini untuk login.",
            input: 'text',
            inputPlaceholder: 'Ketik password baru di sini (min. 6 karakter)',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset Semua',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e67e22',
            inputValidator: (value) => {
                if (!value || value.length < 6) {
                    return 'Password minimal 6 karakter!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('newPasswordInput').value = result.value;
                document.getElementById('resetAllForm').submit();
            }
        })
    }
</script>
@endpush
@endsection
