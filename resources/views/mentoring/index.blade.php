@extends('layouts.app')

@section('title', ($isAdmin ? 'Kelompok ' : 'Kelompok Saya - ') . $typeLabel)
@section('page-title', ($isAdmin ? 'Kelompok ' : 'Kelompok Saya - ') . $typeLabel)

@section('content')
<div style="margin-bottom: 25px;">
    <!-- TOP HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: {{ $type === 'tilawati' ? '#ecfdf5' : '#f0f9ff' }}; color: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    <i class="fas {{ $type === 'tilawati' ? 'fa-book-reader' : 'fa-quran' }}"></i>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 1.4rem; font-weight: 800; color: #0f172a;">
                        {{ $isAdmin ? "Kelompok {$typeLabel}" : "Kelompok {$typeLabel} Saya" }}
                    </h2>
                    <p style="margin: 2px 0 0 0; color: #64748b; font-size: 0.88rem;">
                        @if($isAdmin)
                            Kelola kelompok bimbingan {{ strtolower($typeLabel) }}, distribusi siswa, dan penugasan mentor.
                        @else
                            Daftar kelompok {{ strtolower($typeLabel) }} yang diamanahkan kepada Anda.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            @if($isAdmin)
                <a href="{{ route('mentoring.management', ['tab' => $type]) }}" class="btn-action-header btn-blue" style="padding: 9px 15px; font-size: 0.88rem;">
                    <i class="fas fa-layer-group"></i> Manajemen Kelompok Hub
                </a>
                <a href="{{ $type === 'tilawati' ? route('tilawati-groups.create') : route('tahfidz-groups.create') }}" class="btn-action-header btn-green" style="background: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; padding: 9px 16px; font-size: 0.88rem;">
                    <i class="fas fa-plus"></i> Buat Kelompok Baru
                </a>
            @endif
            <a href="{{ $type === 'tilawati' ? route('tilawati-groups.print', ['year_id' => $selectedYearId]) : route('tahfidz-groups.print', ['year_id' => $selectedYearId]) }}" target="_blank" class="btn-action-header btn-dark" style="background: #334155; padding: 9px 15px; font-size: 0.88rem;">
                <i class="fas fa-print"></i> Cetak Rekap
            </a>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 20px;">
        <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 15px; border-left: 4px solid {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; margin-bottom: 0;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: {{ $type === 'tilawati' ? '#ecfdf5' : '#f0f9ff' }}; color: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                <i class="fas {{ $type === 'tilawati' ? 'fa-book-reader' : 'fa-quran' }}"></i>
            </div>
            <div>
                <span style="font-size: 0.82rem; color: #64748b; font-weight: 600; text-transform: uppercase;">
                    {{ $isAdmin ? 'Total Kelompok' : 'Kelompok Saya' }}
                </span>
                <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #1e293b;">{{ $groups->total() }}</h3>
            </div>
        </div>

        <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 15px; border-left: 4px solid #10b981; margin-bottom: 0;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <span style="font-size: 0.82rem; color: #64748b; font-weight: 600; text-transform: uppercase;">
                    {{ $isAdmin ? 'Siswa Terkelompokkan' : 'Total Siswa Bimbingan' }}
                </span>
                <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #1e293b;">
                    {{ $totalAssigned }} 
                    @if($isAdmin)
                        <small style="font-size: 0.8rem; font-weight: normal; color: #64748b;">/ {{ $totalStudentsInYear }}</small>
                    @else
                        <small style="font-size: 0.8rem; font-weight: normal; color: #64748b;">Siswa</small>
                    @endif
                </h3>
            </div>
        </div>

        @if($isAdmin)
            <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 15px; border-left: 4px solid #f59e0b; margin-bottom: 0;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fffbeb; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div>
                    <span style="font-size: 0.82rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Belum Masuk Kelompok</span>
                    <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #1e293b;">{{ $totalUnassigned }}</h3>
                </div>
            </div>
        @else
            <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 15px; border-left: 4px solid #8b5cf6; margin-bottom: 0;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #f5f3ff; color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <span style="font-size: 0.82rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Tahun Pelajaran</span>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #1e293b;">{{ $activeYear ? $activeYear->name : '-' }}</h3>
                </div>
            </div>
        @endif
    </div>

    <!-- ACTION & FILTER BAR -->
    <div class="card" style="padding: 16px 20px; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <form action="{{ $type === 'tilawati' ? route('tilawati-groups.index') : route('tahfidz-groups.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; flex: 1;">
                <div style="min-width: 200px;">
                    <select name="year_id" class="form-control" onchange="this.form.submit()" style="padding: 8px 12px; font-size: 0.9rem;">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                TP: {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="min-width: 220px; position: relative;">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kelompok, target..." class="form-control" style="padding: 8px 12px 8px 34px; font-size: 0.9rem;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
                </div>
                <button type="submit" class="btn-action-header btn-blue" style="padding: 8px 14px;">
                    Filter
                </button>
                @if($search)
                    <a href="{{ $type === 'tilawati' ? route('tilawati-groups.index', ['year_id' => $selectedYearId]) : route('tahfidz-groups.index', ['year_id' => $selectedYearId]) }}" class="btn-action-header btn-dark" style="background: #94a3b8; padding: 8px 14px;">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- GROUP LIST -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #1e293b;">
                {{ $isAdmin ? "Daftar Kelompok {$typeLabel}" : "Kelompok {$typeLabel} yang Anda Bimbing" }}
            </h3>
            <span style="font-size: 0.85rem; color: #64748b;">
                Menampilkan <strong>{{ $groups->count() }}</strong> kelompok
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 0;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px 16px; width: 5%; text-align: center;">No</th>
                        <th style="padding: 12px 16px; text-align: left;">Nama Kelompok</th>
                        <th style="padding: 12px 16px; text-align: left;">Pembimbing / Mentor</th>
                        <th style="padding: 12px 16px; text-align: left;">Target / Jenjang</th>
                        <th style="padding: 12px 16px; text-align: left;">Jadwal & Tempat</th>
                        <th style="padding: 12px 16px; text-align: center;">Jumlah Anggota</th>
                        <th style="padding: 12px 16px; text-align: center; width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groups as $index => $group)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;">
                            <td style="padding: 14px 16px; text-align: center; font-weight: bold; color: #64748b;">
                                {{ $groups->firstItem() + $index }}
                            </td>
                            <td style="padding: 14px 16px;">
                                <a href="{{ $type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" style="font-weight: 700; color: #0f172a; text-decoration: none; font-size: 0.95rem;">
                                    {{ $group->name }}
                                </a>
                                @if($group->notes)
                                    <div style="font-size: 0.78rem; color: #64748b; margin-top: 3px;">
                                        <i class="fas fa-info-circle"></i> {{ Str::limit($group->notes, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 14px 16px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; color: #475569; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem;">
                                        {{ substr($group->mentor_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #334155;">{{ $group->mentor_name }}</div>
                                        @if($group->mentorUser)
                                            <small style="color: #059669; font-size: 0.75rem;"><i class="fas fa-check-circle"></i> Akun Guru Aktif</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 14px 16px;">
                                @if($group->target_level)
                                    <span style="display: inline-block; background: {{ $type === 'tilawati' ? '#ecfdf5' : '#f0f9ff' }}; color: {{ $type === 'tilawati' ? '#047857' : '#0369a1' }}; border: 1px solid {{ $type === 'tilawati' ? '#a7f3d0' : '#bae6fd' }}; padding: 3px 10px; border-radius: 20px; font-weight: 600; font-size: 0.82rem;">
                                        {{ $group->target_level }}
                                    </span>
                                @else
                                    <span style="color: #94a3b8; font-style: italic;">-</span>
                                @endif
                            </td>
                            <td style="padding: 14px 16px; color: #475569; font-size: 0.88rem;">
                                {{ $group->schedule ?? '-' }}
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <span style="display: inline-block; background: #f1f5f9; color: #0f172a; padding: 4px 12px; border-radius: 8px; font-weight: 700; font-size: 0.9rem;">
                                    {{ $group->students_count }} Siswa
                                </span>
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                    <!-- Kelola Siswa -->
                                    <a href="{{ $type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" class="btn-action-header btn-blue" style="padding: 6px 10px; font-size: 0.8rem;" title="{{ $isAdmin ? 'Lihat & Kelola Siswa' : 'Daftar Siswa' }}">
                                        <i class="fas fa-users"></i> {{ $isAdmin ? 'Siswa' : 'Daftar Siswa' }}
                                    </a>

                                    @if($type === 'tahfidz')
                                        <!-- Absensi Sesi -->
                                        <a href="{{ route('tahfidz.attendance.index', ['group' => $group->id]) }}" class="btn-action-header" style="background: #7c3aed; color: white; padding: 6px 10px; font-size: 0.8rem;" title="Absensi Kehadiran Sesi">
                                            <i class="fas fa-clipboard-check"></i> Absensi
                                        </a>
                                        <!-- Rekap Hafalan -->
                                        <a href="{{ route('tahfidz.print-group-recap', ['group' => $group->id]) }}" target="_blank" class="btn-action-header" style="background: #0f766e; color: white; padding: 6px 10px; font-size: 0.8rem;" title="Rekap Hafalan">
                                            <i class="fas fa-quran"></i> Rekap
                                        </a>
                                    @endif

                                    <!-- Cetak -->
                                    <a href="{{ route('mentoring-groups.print-group', $group->id) }}" target="_blank" class="btn-action-header btn-dark" style="background: #475569; padding: 6px 10px; font-size: 0.8rem;" title="Cetak Kelompok">
                                        <i class="fas fa-print"></i>
                                    </a>

                                    @if($isAdmin)
                                        <!-- Edit Kelompok -->
                                        <a href="{{ $type === 'tilawati' ? route('tilawati-groups.edit', $group->id) : route('tahfidz-groups.edit', $group->id) }}" class="btn-action-header btn-orange" style="padding: 6px 10px; font-size: 0.8rem;" title="Edit Kelompok">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif

                                    @if($isAdmin)
                                        <button type="button" onclick="confirmDelete('{{ $group->id }}', '{{ $group->name }}')" class="btn-action-header btn-red" style="padding: 6px 10px; font-size: 0.8rem;" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form id="delete-form-{{ $group->id }}" action="{{ $type === 'tilawati' ? route('tilawati-groups.destroy', $group->id) : route('tahfidz-groups.destroy', $group->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 45px 20px; color: #94a3b8;">
                                <div style="font-size: 2.5rem; margin-bottom: 10px; color: #cbd5e1;">
                                    <i class="fas {{ $type === 'tilawati' ? 'fa-book-reader' : 'fa-quran' }}"></i>
                                </div>
                                <h4 style="margin: 0; color: #475569;">
                                    {{ $isAdmin ? "Belum Ada Kelompok {$typeLabel}" : "Anda Belum Memiliki Kelompok {$typeLabel}" }}
                                </h4>
                                <p style="margin: 6px 0 16px 0; font-size: 0.9rem;">
                                    @if($isAdmin)
                                        Mulai dengan membuat kelompok {{ strtolower($typeLabel) }} pertama untuk mengelompokkan siswa.
                                    @else
                                        Saat ini Anda belum ditugaskan membimbing kelompok {{ strtolower($typeLabel) }}. Hubungi Kesiswaan / Admin jika ada penugasan baru.
                                    @endif
                                </p>
                                @if($isAdmin)
                                    <a href="{{ $type === 'tilawati' ? route('tilawati-groups.create') : route('tahfidz-groups.create') }}" class="btn-action-header btn-green" style="background: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }};">
                                        <i class="fas fa-plus"></i> Buat Kelompok Sekarang
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($groups->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
                {{ $groups->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Kelompok?',
            text: `Apakah Anda yakin ingin menghapus kelompok "${name}"? Siswa di dalamnya akan dilepaskan dari kelompok ini.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
