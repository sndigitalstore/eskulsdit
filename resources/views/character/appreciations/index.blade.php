@extends('layouts.app')

@section('title', 'Data Apresiasi & Kebaikan Siswa – SMILE Karakter')
@section('page-title', 'Data Apresiasi & Kebaikan')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-award" style="color: #10b981;"></i> Catatan Apresiasi & Perilaku Terpuji Siswa
            </h3>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.88rem;">
                Dokumentasi pembiasaan karakter unggul siswa berlandaskan nilai <strong>SMILE (Sholeh, Muslih, Intellectual Curiosity, Leadership, Environmentally Friendly)</strong>.
            </p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('character-appreciations.print', request()->query()) }}" target="_blank" class="btn-action-header btn-dark" style="text-decoration: none; padding: 10px 18px; font-weight: 700;">
                <i class="fas fa-print"></i> Cetak Rekap (A4)
            </a>
            <a href="{{ route('character-appreciations.create') }}" class="btn-action-header btn-green" style="text-decoration: none; padding: 10px 18px; font-weight: 700; background: #10b981;">
                <i class="fas fa-plus-circle"></i> Beri Apresiasi Baru
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div style="background: #dcfce7; border-left: 4px solid #16a34a; color: #166534; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; justify-content: space-between;">
            <div><i class="fas fa-check-circle" style="margin-right: 8px;"></i> {{ session('success') }}</div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 1.1rem; cursor: pointer; color: #166534;">&times;</button>
        </div>
    @endif

    <!-- Mini KPI Stat Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px;">
        <div class="card" style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid #10b981;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Apresiasi</span>
                <div style="font-size: 1.6rem; font-weight: 900; color: #1e293b;">{{ $stats['total'] }}</div>
                <div style="font-size: 0.75rem; color: #10b981; font-weight: 600;">Catatan Kebaikan</div>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #dcfce7; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                <i class="fas fa-medal"></i>
            </div>
        </div>

        <div class="card" style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid #f59e0b;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Poin Terkumpul</span>
                <div style="font-size: 1.6rem; font-weight: 900; color: #b45309;">+{{ $stats['total_points'] }}</div>
                <div style="font-size: 0.75rem; color: #b45309; font-weight: 600;">Poin Karakter Positif</div>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                <i class="fas fa-star"></i>
            </div>
        </div>

        <div class="card" style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid #0284c7;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Siswa Berprestasi</span>
                <div style="font-size: 1.6rem; font-weight: 900; color: #0284c7;">{{ $stats['unique_students'] }}</div>
                <div style="font-size: 0.75rem; color: #0284c7; font-weight: 600;">Siswa Penerima Apresiasi</div>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card" style="margin-bottom: 20px; padding: 18px 24px;">
        <form action="{{ route('character-appreciations.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; align-items: flex-end;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #475569; font-size: 0.82rem;">Tahun Pelajaran</label>
                    <select name="year_id" class="form-control" style="height: 40px; font-size: 0.88rem;">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #475569; font-size: 0.82rem;">Kelas</label>
                    <select name="class" class="form-control" style="height: 40px; font-size: 0.88rem;">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c }}" {{ $selectedClass == $c ? 'selected' : '' }}>Kelas {{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #475569; font-size: 0.82rem;">Kategori Nilai SMILE</label>
                    <select name="category" class="form-control" style="height: 40px; font-size: 0.88rem;">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $selectedCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #475569; font-size: 0.82rem;">Cari Nama / Bentuk Apresiasi</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Kata kunci..." style="height: 40px; font-size: 0.88rem;">
                </div>
                <div>
                    <button type="submit" class="btn-action-header btn-green" style="height: 40px; width: 100%; justify-content: center; font-weight: 700; background: #10b981;">
                        <i class="fas fa-search"></i> Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="font-size: 0.92rem; font-weight: 700; color: #1e293b;">
                Menampilkan {{ $appreciations->total() }} Catatan Apresiasi
            </div>
            @if(request()->anyFilled(['class', 'category', 'search']))
                <a href="{{ route('character-appreciations.index') }}" style="font-size: 0.82rem; color: #ef4444; font-weight: 600; text-decoration: none;">
                    <i class="fas fa-times-circle"></i> Reset Filter
                </a>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse; margin: 0;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 0.82rem; color: #475569;">
                        <th style="padding: 12px 10px; text-align: center; width: 45px;">No</th>
                        <th style="padding: 12px 10px; text-align: left; width: 100px;">Tanggal</th>
                        <th style="padding: 12px 10px; text-align: left; width: 220px;">Siswa & Kelas</th>
                        <th style="padding: 12px 10px; text-align: left; width: 160px;">Nilai SMILE</th>
                        <th style="padding: 12px 10px; text-align: left;">Bentuk Perilaku Terpuji & Uraian</th>
                        <th style="padding: 12px 10px; text-align: center; width: 120px;">Poin & Reward</th>
                        <th style="padding: 12px 10px; text-align: left; width: 150px;">Pemberi Apresiasi</th>
                        <th style="padding: 12px 10px; text-align: center; width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appreciations as $idx => $appr)
                        <tr style="border-bottom: 1px solid #f1f5f9; font-size: 0.85rem;">
                            <td style="padding: 12px 10px; text-align: center; color: #64748b;">
                                {{ $appreciations->firstItem() + $idx }}
                            </td>
                            <td style="padding: 12px 10px; color: #334155; white-space: nowrap;">
                                <div style="font-weight: 700;">{{ \Carbon\Carbon::parse($appr->date)->format('d/m/Y') }}</div>
                            </td>
                            <td style="padding: 12px 10px;">
                                <strong style="color: #1e293b; font-size: 0.92rem;">{{ $appr->student->name ?? '-' }}</strong>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">
                                    <span style="display: inline-block; padding: 1px 6px; border-radius: 4px; background: #f1f5f9; font-weight: 700; color: #334155;">
                                        Kelas {{ $appr->student->class ?? '-' }}
                                    </span>
                                    • NIS: {{ $appr->student->nis ?? '-' }}
                                </div>
                            </td>
                            <td style="padding: 12px 10px;">
                                <span style="display: inline-flex; align-items: center; gap: 5px; padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: 0.78rem; background: {{ $appr->category_theme['bg'] }}; color: {{ $appr->category_theme['color'] }};">
                                    <i class="fas {{ $appr->category_theme['icon'] }}"></i>
                                    {{ $appr->category }}
                                </span>
                            </td>
                            <td style="padding: 12px 10px;">
                                <div style="font-weight: 700; color: #1e293b;">
                                    {{ $appr->title }}
                                </div>
                                @if($appr->description)
                                    <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px; line-height: 1.3;">
                                        {{ Str::limit($appr->description, 100) }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 12px 10px; text-align: center;">
                                <span style="display: inline-block; padding: 2px 8px; border-radius: 999px; font-weight: 800; font-size: 0.82rem; background: #dcfce7; color: #15803d;">
                                    +{{ $appr->point }} Poin
                                </span>
                                @if($appr->reward)
                                    <div style="font-size: 0.72rem; color: #0284c7; font-weight: 600; margin-top: 3px;">
                                        <i class="fas fa-gift"></i> {{ $appr->reward }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 12px 10px;">
                                <div style="font-weight: 600; color: #334155;">
                                    {{ $appr->given_by ?? '-' }}
                                </div>
                            </td>
                            <td style="padding: 12px 10px; text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    <a href="{{ route('character-appreciations.edit', $appr->id) }}" class="btn-action-header btn-orange" style="padding: 4px 8px; font-size: 0.75rem; text-decoration: none;" title="Edit Apresiasi">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('character-appreciations.destroy', $appr->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan apresiasi ini?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-header btn-red" style="padding: 4px 8px; font-size: 0.75rem; background: #ef4444;" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px 20px; color: #64748b;">
                                <i class="fas fa-award" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                <h4 style="margin: 0; color: #334155;">Belum Ada Catatan Apresiasi</h4>
                                <p style="margin: 4px 0 0 0; font-size: 0.88rem;">Belum ditemukan data apresiasi sesuai kriteria filter saat ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appreciations->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
                {{ $appreciations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
