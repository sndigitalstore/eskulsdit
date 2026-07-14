@extends('layouts.app')

@section('title', 'Kenaikan Kelas & Kelulusan')
@section('page-title', 'Kenaikan Kelas & Kelulusan')

@section('content')
<div class="card">
    <div class="page-header">
        <div>
            <h2>Kenaikan Kelas & Kelulusan</h2>
            <p style="color: #888;">Proses kenaikan kelas atau kelulusan siswa secara masal.</p>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #e0fbf0; color: #2ecc71; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('promotions.index') }}" method="GET" style="margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 10px;">
        <div style="display: flex; gap: 10px; align-items: center;">
            <label style="font-weight: 600;">Pilih Kelas Asal:</label>
            <input type="text" name="class" value="{{ $class }}" placeholder="Contoh: 1A, 5B, 6A" class="form-control" style="width: 150px; padding: 8px; border-radius: 8px; border: 1px solid #ddd;">
            <button type="submit" class="btn-view" style="padding: 8px 15px; background: #3498db; color: white;">
                <i class="fas fa-search"></i> Tampilkan Siswa
            </button>
        </div>
        <small style="color: #666;">Masukkan nama kelas persis sesuai data siswa (Contoh: 1A, 2, 6B).</small>
    </form>

    @if($class)
        @if(count($students) > 0)
            <form id="promotionForm" action="{{ route('promotions.promote') }}" method="POST">
                @csrf
                <input type="hidden" name="class_from" value="{{ $class }}">

                <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <strong style="color: #333;">Daftar Siswa Kelas {{ $class }} ({{ count($students) }} siswa)</strong>
                    <div>
                        <!-- Determine Action Label based on Class -->
                        @php
                            preg_match('/^(\d+)/', $class, $matches);
                            $level = isset($matches[1]) ? (int)$matches[1] : 0;
                            $actionText = 'PROSES SISWA'; // default
                            $confirmColor = '#3085d6';

                            if($level == 6) {
                                $actionText = 'LULUSKAN SISWA DIPILIH';
                                $confirmColor = '#2ecc71';
                                $confirmTitle = 'Luluskan Siswa?';
                                $confirmText = 'Siswa yang dipilih akan ditandai LULUS dan dikeluarkan dari daftar aktif.';
                            } elseif($level > 0 && $level < 6) {
                                $nextClass = ($level + 1) . substr($class, strlen((string)$level));
                                $actionText = 'NAIKKAN KE KELAS ' . $nextClass;
                                $confirmColor = '#3498db';
                                $confirmTitle = 'Naikkan Kelas?';
                                $confirmText = 'Siswa yang dipilih akan dipindahkan ke kelas ' . $nextClass . '.';
                            } else {
                                $confirmTitle = 'Proses Siswa?';
                                $confirmText = 'Apakah Anda yakin ingin memproses siswa ini?';
                            }
                        @endphp

                        @if($level == 6)
                            <button type="button" onclick="confirmPromotion('{{ $confirmTitle }}', '{{ $confirmText }}', '{{ $confirmColor }}')" class="btn-edit" style="background: #2ecc71; padding: 10px 20px; color: white; border: none; font-weight: bold;">
                                <i class="fas fa-graduation-cap"></i> {{ $actionText }}
                            </button>
                        @elseif($level > 0 && $level < 6)
                            <button type="button" onclick="confirmPromotion('{{ $confirmTitle }}', '{{ $confirmText }}', '{{ $confirmColor }}')" class="btn-view" style="background: #3498db; padding: 10px 20px; color: white; border: none; font-weight: bold;">
                                <i class="fas fa-level-up-alt"></i> {{ $actionText }}
                            </button>
                        @else
                             <button type="button" onclick="confirmPromotion('{{ $confirmTitle }}', '{{ $confirmText }}', '{{ $confirmColor }}')" class="btn-view" style="background: #95a5a6; padding: 10px 20px; color: white; border: none; font-weight: bold;">
                                PROSES (Level Tidak Terdeteksi)
                            </button>
                        @endif
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th width="40" style="text-align: center;"><input type="checkbox" id="selectAll"></th>
                            <th>Nama Siswa</th>
                            <th>Kelas Saat Ini</th>
                            <th>Status saat ini</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="ids[]" value="{{ $student->id }}" class="student-checkbox">
                            </td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->class }}</td>
                            <td><span class="badge" style="background: #eef2ff; color: #5381ff;">Aktif</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        @else
            <div style="text-align: center; padding: 40px; color: #666;">
                <i class="fas fa-user-slash" style="font-size: 3rem; margin-bottom: 15px; color: #ccc;"></i>
                <p>Tidak ada siswa aktif ditemukan di kelas <strong>{{ $class }}</strong>.</p>
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 40px; color: #666; background: #fff; border-radius: 10px; border: 1px dashed #ddd;">
            <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 15px; color: #3498db;"></i>
            <p>Silakan masukkan kelas asal untuk memulai proses kenaikan kelas atau kelulusan.</p>
        </div>
    @endif
</div>

<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.student-checkbox');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });

    function confirmPromotion(title, text, color) {
        // Javascript check if any selected
        const checkboxes = document.querySelectorAll('.student-checkbox:checked');
        if (checkboxes.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak ada siswa dipilih',
                text: 'Silakan pilih minimal satu siswa untuk diproses.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: color,
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('promotionForm').submit();
            }
        });
    }
</script>
@endsection
