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
            @if(Auth::user()->role == 'admin')
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
            @endif
        </div>
    </div>
    


    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <div style="overflow-x: auto;">
        <table class="table" style="vertical-align: middle;">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Identitas Guru</th>
                    <th width="15%">Wali Kelas</th>
                    <th width="15%">Pembina Eskul</th>
                    <th width="25%">Penugasan Qur'an & Mapel</th>
                    @if(Auth::user()->role == 'admin')
                    <th width="15%" class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $index => $teacher)
                @php
                    $tilawatiList = $teacher->mentoringGroups->where('type', 'tilawati');
                    $tahfidzList = $teacher->mentoringGroups->where('type', 'tahfidz');
                    $subjectIds = $teacher->teaching_subject_ids ?? [];
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight: 800; color: #1e293b; font-size: 0.95rem;">{{ $teacher->name }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">
                            <span style="font-family: monospace; background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">{{ $teacher->username }}</span>
                            @if($teacher->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', str_starts_with($teacher->phone, '0') ? '62' . substr($teacher->phone, 1) : $teacher->phone) }}" target="_blank" style="margin-left: 6px; color: #10b981; text-decoration: none; font-weight: 600;">
                                    <i class="fab fa-whatsapp"></i> {{ $teacher->phone }}
                                </a>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($teacher->homeroom_class)
                            <span class="badge" style="background: #e0f2fe; color: #0284c7; font-weight: 700; padding: 6px 12px; border-radius: 8px; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fas fa-school"></i> Kelas {{ $teacher->homeroom_class }}
                            </span>
                        @else
                            <span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($teacher->eskul)
                            <span class="badge" style="background: #ecfdf5; color: #059669; font-weight: 700; padding: 6px 12px; border-radius: 8px; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fas fa-basketball-ball"></i> {{ $teacher->eskul->name }}
                            </span>
                        @else
                            <span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            {{-- Tilawati Groups --}}
                            @if($tilawatiList->isNotEmpty())
                                <div style="display: flex; align-items: center; gap: 4px; flex-wrap: wrap;">
                                    <span style="font-size: 0.72rem; font-weight: 800; color: #15803d; background: #dcfce7; padding: 2px 8px; border-radius: 6px;">
                                        <i class="fas fa-book-open"></i> Tilawati:
                                    </span>
                                    <span style="font-size: 0.8rem; color: #334155; font-weight: 600;">
                                        {{ $tilawatiList->pluck('name')->implode(', ') }}
                                    </span>
                                </div>
                            @endif

                            {{-- Tahfidz Groups --}}
                            @if($tahfidzList->isNotEmpty())
                                <div style="display: flex; align-items: center; gap: 4px; flex-wrap: wrap;">
                                    <span style="font-size: 0.72rem; font-weight: 800; color: #0369a1; background: #e0f2fe; padding: 2px 8px; border-radius: 6px;">
                                        <i class="fas fa-quran"></i> Tahfidz:
                                    </span>
                                    <span style="font-size: 0.8rem; color: #334155; font-weight: 600;">
                                        {{ $tahfidzList->pluck('name')->implode(', ') }}
                                    </span>
                                </div>
                            @endif

                            {{-- Teaching Subjects --}}
                            @if(!empty($subjectIds))
                                <div style="display: flex; align-items: center; gap: 4px; flex-wrap: wrap;">
                                    <span style="font-size: 0.72rem; font-weight: 800; color: #6d28d9; background: #f3e8ff; padding: 2px 8px; border-radius: 6px;">
                                        <i class="fas fa-graduation-cap"></i> Mapel:
                                    </span>
                                    <span style="font-size: 0.8rem; color: #334155; font-weight: 600;">
                                        @php
                                            $mySubjNames = collect($subjectIds)->map(fn($id) => $allSubjects[$id] ?? null)->filter()->implode(', ');
                                        @endphp
                                        {{ $mySubjNames ?: '-' }}
                                    </span>
                                </div>
                            @endif

                            @if($tilawatiList->isEmpty() && $tahfidzList->isEmpty() && empty($subjectIds))
                                <span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;">Tidak ada penugasan tambahan</span>
                            @endif
                        </div>
                    </td>
                    @if(Auth::user()->role == 'admin')
                    <td class="text-center">
                        <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                            <button type="button" class="btn-action" style="color: #e67e22; border: 1px solid #fed7aa; background: #fff7ed; padding: 6px 10px; border-radius: 8px; cursor: pointer;" title="Reset Password Guru Ini" onclick="promptResetSingle('{{ $teacher->id }}', '{{ addslashes($teacher->name) }}')">
                                <i class="fas fa-key"></i>
                            </button>
                            <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn-action" style="color: #0284c7; border: 1px solid #bae6fd; background: #f0f9ff; padding: 6px 10px; border-radius: 8px; text-decoration: none;" title="Edit Penugasan">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" data-confirm="Yakin ingin menghapus akun guru pembina ini? Data absensi dan nilai yang pernah diinput guru ini akan tetap ada." style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action" style="color: #ef4444; border: 1px solid #fecaca; background: #fef2f2; padding: 6px 10px; border-radius: 8px; cursor: pointer;" title="Hapus Akun">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ Auth::user()->role == 'admin' ? 6 : 5 }}" class="text-center" style="padding: 20px; color: #999;">
                        Belum ada akun guru pembina.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Hidden Form for Single Teacher Password Reset -->
<form id="resetSingleForm" action="" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="new_password" id="singleNewPasswordInput">
</form>

@push('scripts')
<script>
    function promptResetSingle(teacherId, teacherName) {
        Swal.fire({
            title: 'Reset Password Guru',
            text: `Masukkan password baru untuk ${teacherName}:`,
            input: 'text',
            inputValue: '123456',
            inputPlaceholder: 'Ketik password baru di sini (min. 6 karakter)',
            showCancelButton: true,
            confirmButtonText: 'Simpan Password Baru',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e67e22',
            inputValidator: (value) => {
                if (!value || value.length < 6) {
                    return 'Password minimal 6 karakter!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('resetSingleForm');
                form.action = `/teachers/${teacherId}/reset-password`;
                document.getElementById('singleNewPasswordInput').value = result.value;
                form.submit();
            }
        });
    }

    function promptResetAll() {
        Swal.fire({
            title: 'Atur Password Baru Masal',
            text: "Seluruh guru pembina akan menggunakan password ini untuk login.",
            input: 'text',
            inputValue: '123456',
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
