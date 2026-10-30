@extends('layouts.app')

@section('title', 'Riwayat Supervisi Guru - SIMUTQIN')
@section('page-title', 'Riwayat Supervisi Guru')

@section('content')
<div style="max-width: 1400px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

    <!-- TOP HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 8px; background: #ecfdf5; color: #059669; padding: 4px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 700; margin-bottom: 6px;">
                <i class="fas fa-clipboard-check"></i> Monitoring Kinerja Pendidik
            </div>
            <h2 style="margin: 0; font-size: 1.6rem; font-weight: 900; color: #0f172a; letter-spacing: -0.02em;">
                Daftar Riwayat Supervisi Guru
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 0.9rem; color: #64748b;">
                Arsip hasil observasi, evaluasi kinerja KBM, modul ajar, halaqah Qur'an, dan ekstrakurikuler.
            </p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            @if(in_array(auth()->user()->role, ['admin', 'headmaster', 'curriculum']))
                <a href="{{ route('supervisions.radar') }}" class="btn" style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #334155; font-weight: 700; padding: 9px 18px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-chart-line" style="color: #059669;"></i> Radar Mutu
                </a>
                <a href="{{ route('supervisions.create') }}" class="btn" style="background: #059669; color: white; font-weight: 700; padding: 9px 18px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);">
                    <i class="fas fa-plus-circle"></i> Input Supervisi Baru
                </a>
            @endif
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    @if(session('success'))
        <div style="background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; padding: 14px 18px; border-radius: 8px; font-weight: 600; font-size: 0.92rem; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle" style="font-size: 1.2rem; color: #10b981;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- FILTER CARD -->
    <div class="card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
        <form action="{{ route('supervisions.index') }}" method="GET" style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                
                <!-- Tahun Ajaran -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.82rem;">Tahun Ajaran</label>
                    <select name="academic_year_id" class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-size: 0.88rem;">
                        <option value="">-- Semua Tahun Ajaran --</option>
                        @foreach($allYears as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id', $activeYear ? $activeYear->id : '') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Semester -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.82rem;">Semester</label>
                    <select name="semester" class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-size: 0.88rem;">
                        <option value="">-- Semua Semester --</option>
                        <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                        <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                    </select>
                </div>

                <!-- Guru (Jika Admin/Kepsek) -->
                @if(!$isTeacherOnly)
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.82rem;">Guru yang Disupervisi</label>
                    <select name="teacher_id" class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-size: 0.88rem;">
                        <option value="">-- Semua Guru --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}" {{ request('teacher_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Tipe Supervisi -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.82rem;">Jenis Supervisi</label>
                    <select name="supervision_type" class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-size: 0.88rem;">
                        <option value="">-- Semua Jenis --</option>
                        <option value="kbm" {{ request('supervision_type') == 'kbm' ? 'selected' : '' }}>KBM / Pembelajaran di Kelas</option>
                        <option value="modul_ajar" {{ request('supervision_type') == 'modul_ajar' ? 'selected' : '' }}>Perencanaan / Modul Ajar</option>
                        <option value="quran" {{ request('supervision_type') == 'quran' ? 'selected' : '' }}>Pembinaan Al-Qur'an (Tahfidz/Tilawati)</option>
                        <option value="eskul" {{ request('supervision_type') == 'eskul' ? 'selected' : '' }}>Kegiatan Ekstrakurikuler</option>
                    </select>
                </div>

                <!-- Predikat -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.82rem;">Predikat</label>
                    <select name="predicate" class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-size: 0.88rem;">
                        <option value="">-- Semua Predikat --</option>
                        <option value="Sangat Baik" {{ request('predicate') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik (A)</option>
                        <option value="Baik" {{ request('predicate') == 'Baik' ? 'selected' : '' }}>Baik (B)</option>
                        <option value="Cukup" {{ request('predicate') == 'Cukup' ? 'selected' : '' }}>Cukup (C)</option>
                        <option value="Perlu Pembinaan" {{ request('predicate') == 'Perlu Pembinaan' ? 'selected' : '' }}>Perlu Pembinaan (D)</option>
                    </select>
                </div>

            </div>

            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guru, mata pelajaran, kelas, atau catatan..." class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-size: 0.88rem;">
                </div>
                <button type="submit" class="btn" style="background: #0f172a; color: white; font-weight: 700; padding: 8px 18px; border-radius: 8px; font-size: 0.88rem;">
                    <i class="fas fa-search"></i> Terapkan Filter
                </button>
                @if(request()->anyFilled(['teacher_id', 'supervision_type', 'predicate', 'search', 'semester']))
                    <a href="{{ route('supervisions.index') }}" class="btn" style="background: #f1f5f9; color: #475569; font-weight: 700; padding: 8px 16px; border-radius: 8px; font-size: 0.88rem; text-decoration: none;">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- DAFTAR SUPERVISI TABLE -->
    <div class="card" style="border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse; margin: 0;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em; text-align: left;">
                        <th style="padding: 14px 18px;">Tanggal</th>
                        <th style="padding: 14px 18px;">Guru yang Disupervisi</th>
                        <th style="padding: 14px 18px;">Jenis Supervisi</th>
                        <th style="padding: 14px 18px;">Kelas & Muatan</th>
                        <th style="padding: 14px 18px; text-align: center;">Skor & Predikat</th>
                        <th style="padding: 14px 18px;">Supervisor</th>
                        <th style="padding: 14px 18px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.88rem; color: #1e293b;">
                    @forelse($supervisions as $s)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;">
                            <td style="padding: 14px 18px; white-space: nowrap;">
                                <div style="font-weight: 700; color: #0f172a;">{{ $s->supervision_date->translatedFormat('d M Y') }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">{{ $s->supervision_date->diffForHumans() }}</div>
                            </td>

                            <td style="padding: 14px 18px;">
                                <div style="font-weight: 800; color: #0f172a;">{{ $s->teacher ? $s->teacher->name : '-' }}</div>
                                <div style="font-size: 0.76rem; color: #64748b;">
                                    @if($s->teacher && $s->teacher->homeroom_class)
                                        Wali Kelas {{ $s->teacher->homeroom_class }} &bull;
                                    @endif
                                    Guru SDIT An Nadzir
                                </div>
                            </td>

                            <td style="padding: 14px 18px;">
                                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; background: #f1f5f9; color: #334155;">
                                    <i class="fas {{ $s->type_icon }}" style="color: #059669;"></i>
                                    {{ $s->type_label }}
                                </span>
                            </td>

                            <td style="padding: 14px 18px;">
                                <div style="font-weight: 700; color: #1e293b;">{{ $s->subject_name ?: '-' }}</div>
                                <div style="font-size: 0.78rem; color: #64748b;">{{ $s->class_name ?: 'Semua Kelas' }}</div>
                            </td>

                            <td style="padding: 14px 18px; text-align: center;">
                                <div style="font-size: 1.15rem; font-weight: 900; color: {{ $s->predicate_color }};">
                                    {{ $s->total_score }}
                                </div>
                                <span style="display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 800; background: {{ $s->predicate_color }}15; color: {{ $s->predicate_color }};">
                                    {{ $s->predicate }}
                                </span>
                            </td>

                            <td style="padding: 14px 18px;">
                                <div style="font-weight: 700; color: #334155;">{{ $s->supervisor ? $s->supervisor->name : '-' }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">
                                    {{ $s->supervisor ? ucfirst($s->supervisor->role) : 'Supervisor' }}
                                </div>
                            </td>

                            <td style="padding: 14px 18px; text-align: center; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="{{ route('supervisions.show', $s->id) }}" title="Lihat Detail" class="btn" style="padding: 6px 10px; background: #f1f5f9; color: #0f172a; border-radius: 6px; font-size: 0.8rem; text-decoration: none;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('supervisions.print', $s->id) }}" target="_blank" title="Cetak Lembar Resmi" class="btn" style="padding: 6px 10px; background: #ecfdf5; color: #059669; border-radius: 6px; font-size: 0.8rem; text-decoration: none;">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    @if(in_array(auth()->user()->role, ['admin', 'headmaster', 'curriculum']))
                                        <a href="{{ route('supervisions.edit', $s->id) }}" title="Edit Supervisi" class="btn" style="padding: 6px 10px; background: #eff6ff; color: #2563eb; border-radius: 6px; font-size: 0.8rem; text-decoration: none;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    @if(in_array(auth()->user()->role, ['admin', 'headmaster']))
                                        <form action="{{ route('supervisions.destroy', $s->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data supervisi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Supervisi" style="padding: 6px 10px; background: #fef2f2; color: #dc2626; border: none; border-radius: 6px; font-size: 0.8rem; cursor: pointer;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 50px 20px; color: #64748b;">
                                <div style="width: 50px; height: 50px; margin: 0 auto 12px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #94a3b8;">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <div style="font-weight: 700; font-size: 0.95rem; color: #334155;">Tidak Ada Data Supervisi Ditemukan</div>
                                <div style="font-size: 0.84rem; margin-top: 4px;">Sesuaikan filter pencarian atau mulai input supervisi baru.</div>
                                @if(in_array(auth()->user()->role, ['admin', 'headmaster', 'curriculum']))
                                    <a href="{{ route('supervisions.create') }}" style="display: inline-block; margin-top: 12px; padding: 8px 18px; background: #059669; color: white; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 0.85rem;">
                                        + Input Supervisi Baru
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($supervisions->hasPages())
            <div style="padding: 16px 20px; background: #ffffff; border-top: 1px solid #e2e8f0;">
                {{ $supervisions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
