@extends('layouts.app')

@section('title', 'Data Kasus & Pembinaan Santri – SMILE Karakter')
@section('page-title', 'Data Kasus & Pembinaan')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i> Catatan Kasus & Pembinaan Karakter
            </h3>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.88rem;">
                Dokumentasi kasus indisipliner, pelanggaran adab, dan tindak lanjut pembinaan akhlak santri.
            </p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('character-cases.print', request()->query()) }}" target="_blank" class="btn-action-header btn-dark" style="text-decoration: none; padding: 10px 18px; font-weight: 700;">
                <i class="fas fa-print"></i> Cetak Rekap (A4)
            </a>
            <a href="{{ route('character-cases.create') }}" class="btn-action-header btn-red" style="text-decoration: none; padding: 10px 18px; font-weight: 700; background: #ef4444;">
                <i class="fas fa-plus-circle"></i> Catat Kasus Baru
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
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 20px;">
        <div class="card" style="padding: 14px 18px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Kasus</span>
                <div style="font-size: 1.5rem; font-weight: 900; color: #1e293b;">{{ $stats['total'] }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                <i class="fas fa-list-ol"></i>
            </div>
        </div>
        <div class="card" style="padding: 14px 18px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Tingkat Ringan</span>
                <div style="font-size: 1.5rem; font-weight: 900; color: #475569;">{{ $stats['ringan'] }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                <i class="fas fa-info-circle"></i>
            </div>
        </div>
        <div class="card" style="padding: 14px 18px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #b45309; text-transform: uppercase;">Tingkat Sedang</span>
                <div style="font-size: 1.5rem; font-weight: 900; color: #b45309;">{{ $stats['sedang'] }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                <i class="fas fa-exclamation"></i>
            </div>
        </div>
        <div class="card" style="padding: 14px 18px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #991b1b; text-transform: uppercase;">Tingkat Berat</span>
                <div style="font-size: 1.5rem; font-weight: 900; color: #991b1b;">{{ $stats['berat'] }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: #fee2e2; color: #991b1b; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                <i class="fas fa-radiation"></i>
            </div>
        </div>
        <div class="card" style="padding: 14px 18px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #1d4ed8; text-transform: uppercase;">Dalam Pembinaan</span>
                <div style="font-size: 1.5rem; font-weight: 900; color: #1d4ed8;">{{ $stats['dalam_pembinaan'] }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: #eff6ff; color: #1d4ed8; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                <i class="fas fa-user-clock"></i>
            </div>
        </div>
        <div class="card" style="padding: 14px 18px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #15803d; text-transform: uppercase;">Selesai Dibina</span>
                <div style="font-size: 1.5rem; font-weight: 900; color: #15803d;">{{ $stats['selesai'] }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                <i class="fas fa-check-double"></i>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card" style="margin-bottom: 20px; padding: 18px 24px;">
        <form action="{{ route('character-cases.index') }}" method="GET">
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
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #475569; font-size: 0.82rem;">Tingkat Keparahan</label>
                    <select name="severity" class="form-control" style="height: 40px; font-size: 0.88rem;">
                        <option value="">-- Semua Tingkat --</option>
                        <option value="ringan" {{ $selectedSeverity == 'ringan' ? 'selected' : '' }}>Ringan</option>
                        <option value="sedang" {{ $selectedSeverity == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="berat" {{ $selectedSeverity == 'berat' ? 'selected' : '' }}>Berat</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #475569; font-size: 0.82rem;">Status Pembinaan</label>
                    <select name="status" class="form-control" style="height: 40px; font-size: 0.88rem;">
                        <option value="">-- Semua Status --</option>
                        <option value="dalam_pembinaan" {{ $selectedStatus == 'dalam_pembinaan' ? 'selected' : '' }}>Dalam Pembinaan</option>
                        <option value="selesai" {{ $selectedStatus == 'selesai' ? 'selected' : '' }}>Selesai Dibina</option>
                        <option value="panggilan_ortu" {{ $selectedStatus == 'panggilan_ortu' ? 'selected' : '' }}>Panggilan Orang Tua</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #475569; font-size: 0.82rem;">Kategori Kasus</label>
                    <select name="category" class="form-control" style="height: 40px; font-size: 0.88rem;">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $selectedCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #475569; font-size: 0.82rem;">Cari Nama / Kasus</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Kata kunci..." style="height: 40px; font-size: 0.88rem;">
                </div>
                <div>
                    <button type="submit" class="btn-action-header btn-blue" style="height: 40px; width: 100%; justify-content: center; font-weight: 700;">
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
                Menampilkan {{ $cases->total() }} Catatan Kasus
            </div>
            @if(request()->anyFilled(['class', 'severity', 'status', 'category', 'search']))
                <a href="{{ route('character-cases.index') }}" style="font-size: 0.82rem; color: #ef4444; font-weight: 600; text-decoration: none;">
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
                        <th style="padding: 12px 10px; text-align: left; width: 220px;">Santri & Kelas</th>
                        <th style="padding: 12px 10px; text-align: left;">Kategori & Kasus</th>
                        <th style="padding: 12px 10px; text-align: center; width: 110px;">Tingkat & Poin</th>
                        <th style="padding: 12px 10px; text-align: left; width: 220px;">Tindakan / Komitmen</th>
                        <th style="padding: 12px 10px; text-align: center; width: 130px;">Status</th>
                        <th style="padding: 12px 10px; text-align: center; width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cases as $idx => $case)
                        <tr style="border-bottom: 1px solid #f1f5f9; font-size: 0.85rem;">
                            <td style="padding: 12px 10px; text-align: center; color: #64748b;">
                                {{ $cases->firstItem() + $idx }}
                            </td>
                            <td style="padding: 12px 10px; color: #334155; white-space: nowrap;">
                                <div style="font-weight: 700;">{{ \Carbon\Carbon::parse($case->date)->format('d/m/Y') }}</div>
                                @if($case->location)
                                    <div style="font-size: 0.72rem; color: #64748b;"><i class="fas fa-map-marker-alt"></i> {{ $case->location }}</div>
                                @endif
                            </td>
                            <td style="padding: 12px 10px;">
                                <strong style="color: #1e293b; font-size: 0.92rem;">{{ $case->student->name ?? '-' }}</strong>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">
                                    <span style="display: inline-block; padding: 1px 6px; border-radius: 4px; background: #f1f5f9; font-weight: 700; color: #334155;">
                                        Kelas {{ $case->student->class ?? '-' }}
                                    </span>
                                    • NIS: {{ $case->student->nis ?? '-' }}
                                </div>
                            </td>
                            <td style="padding: 12px 10px;">
                                <div style="font-size: 0.75rem; font-weight: 700; color: #b45309; text-transform: uppercase;">
                                    {{ $case->category }}
                                </div>
                                <div style="font-weight: 700; color: #1e293b; margin-top: 2px;">
                                    {{ $case->title }}
                                </div>
                                @if($case->description)
                                    <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px; line-height: 1.3;">
                                        {{ Str::limit($case->description, 100) }}
                                    </div>
                                @endif
                                @if($case->reported_by)
                                    <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 4px;">
                                        Pelapor: {{ $case->reported_by }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 12px 10px; text-align: center;">
                                <span style="display: inline-block; padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; background: {{ $case->severity_badge['bg'] }}; color: {{ $case->severity_badge['color'] }}; border: 1px solid {{ $case->severity_badge['border'] }};">
                                    {{ $case->severity_badge['label'] }}
                                </span>
                                <div style="font-size: 0.75rem; color: #ef4444; font-weight: 800; margin-top: 4px;">
                                    -{{ $case->point }} Poin
                                </div>
                            </td>
                            <td style="padding: 12px 10px;">
                                @if($case->action_taken)
                                    <div style="font-size: 0.78rem; color: #334155; line-height: 1.3;">
                                        <strong style="color: #047857;">Tindakan:</strong> {{ Str::limit($case->action_taken, 80) }}
                                    </div>
                                @endif
                                @if($case->follow_up)
                                    <div style="font-size: 0.78rem; color: #64748b; line-height: 1.3; margin-top: 2px;">
                                        <strong>Komitmen:</strong> {{ Str::limit($case->follow_up, 80) }}
                                    </div>
                                @endif
                                @if(!$case->action_taken && !$case->follow_up)
                                    <span style="color: #94a3b8; font-size: 0.78rem; font-style: italic;">Belum ada catatan tindakan</span>
                                @endif
                            </td>
                            <td style="padding: 12px 10px; text-align: center;">
                                <span style="display: inline-flex; align-items: center; gap: 5px; padding: 3px 8px; border-radius: 999px; font-weight: 700; font-size: 0.75rem; background: {{ $case->status_badge['bg'] }}; color: {{ $case->status_badge['color'] }};">
                                    <i class="fas {{ $case->status_badge['icon'] }}"></i>
                                    {{ $case->status_badge['label'] }}
                                </span>
                                @if($case->parent_notified)
                                    <div style="font-size: 0.7rem; color: #059669; margin-top: 4px; font-weight: 600;">
                                        <i class="fas fa-check"></i> Ortu Diberitahu
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 12px 10px; text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    <a href="{{ route('character-cases.edit', $case->id) }}" class="btn-action-header btn-orange" style="padding: 4px 8px; font-size: 0.75rem; text-decoration: none;" title="Edit Catatan">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('character-cases.destroy', $case->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan kasus ini?');" style="display: inline;">
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
                                <i class="fas fa-clipboard-check" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                <h4 style="margin: 0; color: #334155;">Tidak Ada Data Kasus Pembinaan</h4>
                                <p style="margin: 4px 0 0 0; font-size: 0.88rem;">Tidak ditemukan catatan kasus sesuai dengan kriteria filter saat ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cases->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
                {{ $cases->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
