@extends('layouts.app')

@section('title', 'Laporan & Rekapitulasi Karakter Siswa – SMILE Karakter')
@section('page-title', 'Laporan & Rekapitulasi Karakter')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-signature" style="color: #0284c7;"></i> Laporan Neraca & Pemantauan Karakter Siswa
            </h3>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.88rem;">
                Evaluasi komprehensif perkembangan akhlak siswa: akumulasi apresiasi, catatan kasus pembinaan, dan predikat karakter.
            </p>
        </div>
        @if($selectedClass && $students->count() > 0)
            <div>
                <a href="{{ route('character.report.print', ['year_id' => $selectedYearId, 'class' => $selectedClass]) }}" target="_blank" class="btn-action-header btn-blue" style="text-decoration: none; padding: 10px 18px; font-weight: 700; background: #0284c7;">
                    <i class="fas fa-print"></i> Cetak Laporan Karakter Kelas (A4)
                </a>
            </div>
        @endif
    </div>

    <!-- Filter Card -->
    <div class="card" style="margin-bottom: 24px; padding: 18px 24px;">
        <form action="{{ route('character.report') }}" method="GET">
            <div style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 220px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.85rem;">Tahun Pelajaran</label>
                    <select name="year_id" class="form-control" required>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.85rem;">Pilih Kelas</label>
                    <select name="class" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @if(in_array(Auth::user()->role, ['admin', 'headmaster']))
                            <option value="ALL" {{ $selectedClass == 'ALL' ? 'selected' : '' }} style="font-weight: bold; color: #0284c7;">
                                🌟 -- SEMUA KELAS (REKAP KESELURUHAN) --
                            </option>
                        @endif
                        @foreach($classes as $c)
                            <option value="{{ $c }}" {{ $selectedClass == $c ? 'selected' : '' }}>Kelas {{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="min-width: 140px;">
                    <button type="submit" class="btn-action-header btn-blue" style="height: 44px; width: 100%; justify-content: center; font-weight: 700;">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if($selectedClass)
        @if($students->count() > 0)
            <!-- Table Card -->
            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                    <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #1e293b;">
                        Daftar Evaluasi Karakter: {{ $selectedClass === 'ALL' ? 'Seluruh Kelas' : 'Kelas ' . $selectedClass }} ({{ $students->count() }} Siswa)
                    </h4>
                    <span style="font-size: 0.8rem; color: #64748b;">
                        Format SMILE Karakter SDIT AN NADZIR
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table" style="width: 100%; border-collapse: collapse; margin: 0;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 0.82rem; color: #475569;">
                                <th style="padding: 12px 10px; text-align: center; width: 45px;">No</th>
                                <th style="padding: 12px 10px; text-align: left; width: 90px;">NIS</th>
                                <th style="padding: 12px 10px; text-align: left;">Nama Siswa</th>
                                <th style="padding: 12px 10px; text-align: center; width: 60px;">Kelas</th>
                                <th style="padding: 12px 10px; text-align: center; width: 110px;">Apresiasi (+)</th>
                                <th style="padding: 12px 10px; text-align: center; width: 110px;">Kasus (-)</th>
                                <th style="padding: 12px 10px; text-align: center; width: 100px;">Poin Bersih</th>
                                <th style="padding: 12px 10px; text-align: center; width: 170px;">Predikat Karakter</th>
                                <th style="padding: 12px 10px; text-align: center; width: 120px;">Aksi Cepat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $idx => $st)
                                <tr style="border-bottom: 1px solid #f1f5f9; font-size: 0.88rem;">
                                    <td style="padding: 12px 10px; text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                                    <td style="padding: 12px 10px; font-family: monospace; font-size: 0.82rem; color: #64748b;">{{ $st->nis ?? '-' }}</td>
                                    <td style="padding: 12px 10px;">
                                        <strong style="color: #1e293b;">{{ $st->name }}</strong>
                                        @if($st->gender)
                                            <span style="font-size: 0.72rem; padding: 2px 6px; border-radius: 4px; margin-left: 4px; background: {{ $st->gender == 'L' ? '#e0f2fe; color: #0369a1;' : '#fce7f3; color: #be185d;' }}">
                                                {{ $st->gender }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 10px; text-align: center;">
                                        <span style="display: inline-block; padding: 2px 7px; border-radius: 6px; background: #f1f5f9; font-weight: 700; font-size: 0.8rem; color: #334155;">
                                            {{ $st->class }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px 10px; text-align: center;">
                                        <div style="font-weight: 800; color: #15803d; font-size: 0.95rem;">
                                            +{{ $st->appreciation_points_total }}
                                        </div>
                                        <div style="font-size: 0.72rem; color: #64748b;">
                                            {{ $st->appreciations_count }}x apresiasi
                                        </div>
                                    </td>
                                    <td style="padding: 12px 10px; text-align: center;">
                                        <div style="font-weight: 800; color: {{ $st->cases_count > 0 ? '#ef4444' : '#64748b' }}; font-size: 0.95rem;">
                                            -{{ $st->cases_point_total }}
                                        </div>
                                        <div style="font-size: 0.72rem; color: #64748b;">
                                            {{ $st->cases_count }}x kasus
                                            @if($st->unresolved_cases_count > 0)
                                                <span style="color: #ef4444; font-weight: 700;">({{ $st->unresolved_cases_count }} aktif)</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 12px 10px; text-align: center;">
                                        <span style="display: inline-block; padding: 3px 9px; border-radius: 6px; font-weight: 800; font-size: 0.88rem; background: {{ $st->net_point >= 0 ? '#dcfce7; color: #15803d;' : '#fee2e2; color: #991b1b;' }}">
                                            {{ $st->net_point >= 0 ? '+'.$st->net_point : $st->net_point }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px 10px; text-align: center;">
                                        <span style="display: inline-block; padding: 3px 10px; border-radius: 999px; font-weight: 700; font-size: 0.78rem; background: {{ $st->predicate['badge'] }}; color: {{ $st->predicate['color'] }}; white-space: nowrap;">
                                            {{ $st->predicate['label'] }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px 10px; text-align: center;">
                                        <div style="display: flex; gap: 4px; justify-content: center;">
                                            <a href="{{ route('character-appreciations.create', ['student_id' => $st->id]) }}" class="btn-action-header btn-green" style="padding: 3px 8px; font-size: 0.75rem; text-decoration: none; background: #10b981;" title="Beri Apresiasi">
                                                <i class="fas fa-plus"></i> <i class="fas fa-award"></i>
                                            </a>
                                            <a href="{{ route('character-cases.create', ['student_id' => $st->id]) }}" class="btn-action-header btn-red" style="padding: 3px 8px; font-size: 0.75rem; text-decoration: none; background: #ef4444;" title="Catat Kasus">
                                                <i class="fas fa-plus"></i> <i class="fas fa-exclamation"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card" style="text-align: center; color: #64748b; padding: 3.5rem 1.5rem;">
                <i class="fas fa-users-slash" style="font-size: 2.8rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                <h4 style="color: #334155; margin-bottom: 6px;">Tidak Ditemukan Data Siswa</h4>
                <p style="font-size: 0.9rem; margin: 0; max-width: 500px; margin: 0 auto;">Tidak ada siswa yang terdaftar pada kelas yang dipilih untuk tahun ajaran ini.</p>
            </div>
        @endif
    @else
        <div class="card" style="text-align: center; color: #64748b; padding: 3.5rem 1.5rem;">
            <i class="fas fa-search" style="font-size: 2.8rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
            <h4 style="color: #334155; margin-bottom: 6px;">Pilih Kelas untuk Menampilkan Laporan</h4>
            <p style="font-size: 0.9rem; margin: 0; max-width: 500px; margin: 0 auto;">Silakan pilih Kelas di atas, lalu klik <strong>Tampilkan</strong> untuk melihat neraca poin apresiasi dan catatan kasus siswa.</p>
        </div>
    @endif

</div>
@endsection
