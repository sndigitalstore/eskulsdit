@extends('layouts.app')

@section('title', 'Data Prestasi')
@section('page-title', 'Data Prestasi Siswa')

@push('styles')
<style>
    .achievements-page {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    /* ===== TOPBAR CARD ===== */
    .achievements-header-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .achievements-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .achievements-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.25);
    }

    .achievements-header-text h2 {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.25;
    }

    .achievements-header-text p {
        font-size: 0.84rem;
        color: #64748b;
        margin: 3px 0 0 0;
    }

    .achievements-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-act {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 10px;
        font-size: 0.84rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        white-space: nowrap;
        min-height: 38px;
        box-sizing: border-box;
    }

    .btn-act:hover {
        transform: translateY(-1px);
        filter: brightness(1.05);
    }

    .btn-act-dark {
        background: #334155;
        color: #ffffff;
    }

    .btn-act-green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
    }

    .btn-act-blue {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
    }

    /* ===== TABLE CONTAINER ===== */
    .table-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .desktop-table-view {
        display: block;
        overflow-x: auto;
    }

    .achievements-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .achievements-table th {
        background: #f8fafc;
        padding: 13px 18px;
        font-size: 0.8rem;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .achievements-table td {
        padding: 14px 18px;
        font-size: 0.88rem;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .achievements-table tbody tr:hover {
        background: #f8fafc;
    }

    .level-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.76rem;
        font-weight: 700;
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .student-link {
        font-weight: 700;
        color: #0f172a;
        text-decoration: none;
        transition: color 0.2s;
    }

    .student-link:hover {
        color: #0284c7;
    }

    .btn-row-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-row-cert { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .btn-row-edit { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .btn-row-delete { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    .btn-row-action:hover {
        transform: translateY(-2px);
    }

    /* ===== MOBILE VIEW (< 769px) ===== */
    .mobile-achievements-view {
        display: none;
    }

    @media (max-width: 768px) {
        .achievements-header-card {
            padding: 16px 14px;
            border-radius: 14px;
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
        }

        .achievements-header-icon {
            width: 38px;
            height: 38px;
            font-size: 1.1rem;
        }

        .achievements-header-text h2 {
            font-size: 1.15rem;
        }

        .achievements-header-text p {
            font-size: 0.78rem;
        }

        .achievements-header-actions {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .btn-act {
            width: 100%;
            padding: 9px 4px;
            font-size: 0.78rem;
            border-radius: 9px;
            min-height: 38px;
        }

        /* Hide desktop table, show mobile cards */
        .desktop-table-view {
            display: none !important;
        }

        .mobile-achievements-view {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 12px;
            background: #f8fafc;
        }

        .achievement-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            gap: 11px;
            transition: all 0.2s ease;
        }

        .m-ach-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .m-ach-date {
            font-size: 0.74rem;
            color: #64748b;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .m-ach-title {
            font-size: 0.96rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            line-height: 1.35;
            display: flex;
            align-items: flex-start;
            gap: 7px;
        }

        .m-ach-title i {
            color: #f59e0b;
            font-size: 0.95rem;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .m-ach-desc {
            font-size: 0.8rem;
            color: #64748b;
            font-style: italic;
            line-height: 1.45;
            background: #f8fafc;
            padding: 8px 10px;
            border-radius: 8px;
            border-left: 3px solid #f59e0b;
        }

        /* Student & Details Box */
        .m-ach-details {
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 10px;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .m-ach-student-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .m-student-name-box {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.88rem;
            font-weight: 700;
            color: #1e293b;
        }

        .m-student-class-badge {
            font-size: 0.72rem;
            font-weight: 700;
            color: #0284c7;
            background: #e0f2fe;
            padding: 2px 7px;
            border-radius: 5px;
        }

        .m-ach-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
            font-size: 0.74rem;
            color: #64748b;
            padding-top: 5px;
            border-top: 1px solid #f1f5f9;
        }

        .m-ach-organizer {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
        }

        .m-ach-period {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
            color: #475569;
        }

        /* Card Actions */
        .m-ach-actions {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr;
            gap: 8px;
            padding-top: 4px;
        }

        .m-btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 8px 6px;
            border-radius: 9px;
            font-size: 0.76rem;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            min-height: 36px;
            box-sizing: border-box;
            width: 100%;
        }

        .m-btn-cert {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .m-btn-edit {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .m-btn-delete {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .pagination-wrap {
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-align: center;
        }
    }

    @media (max-width: 380px) {
        .achievements-header-actions {
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }

        .btn-act {
            font-size: 0.72rem;
            padding: 8px 2px;
        }

        .btn-act span {
            font-size: 0.72rem;
        }

        .m-ach-actions {
            grid-template-columns: 1fr;
            gap: 6px;
        }
    }
</style>
@endpush

@section('content')
<div class="achievements-page">

    {{-- 1. Topbar Card --}}
    <div class="achievements-header-card">
        <div class="achievements-header-left">
            <div class="achievements-header-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="achievements-header-text">
                <h2>Data Prestasi Siswa</h2>
                <p>Total <strong>{{ $achievements->total() }}</strong> prestasi kejuaraan & penghargaan tercatat.</p>
            </div>
        </div>

        <div class="achievements-header-actions">
            <a href="{{ route('achievements.print') }}" target="_blank" class="btn-act btn-act-dark" title="Cetak Rekap Laporan">
                <i class="fas fa-print"></i> <span>Cetak</span>
            </a>
            @if(Auth::user()->role == 'admin')
            <a href="{{ route('achievements.bulk') }}" class="btn-act btn-act-green" title="Input Masal Prestasi">
                <i class="fas fa-file-import"></i> <span>Masal</span>
            </a>
            <a href="{{ route('achievements.create') }}" class="btn-act btn-act-blue" title="Tambah Prestasi Baru">
                <i class="fas fa-plus"></i> <span>Tambah</span>
            </a>
            @endif
        </div>
    </div>

    {{-- 2. Data Card (Table on Desktop, Cards on Mobile) --}}
    <div class="table-card">
        @if($achievements->isEmpty())
            <div style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                <i class="fas fa-trophy" style="font-size: 3rem; margin-bottom: 12px; color: #e2e8f0;"></i>
                <h4 style="font-size: 1rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Belum Ada Data Prestasi</h4>
                <p style="font-size: 0.84rem; color: #94a3b8;">Belum ada catatan kejuaraan atau prestasi siswa dalam sistem.</p>
            </div>
        @else
            {{-- A. Desktop Table View (>= 769px) --}}
            <div class="desktop-table-view">
                <table class="achievements-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Siswa</th>
                            <th>Nama Prestasi</th>
                            <th>Tingkat</th>
                            <th>Periode</th>
                            <th>Penyelenggara</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($achievements as $achievement)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: #1e293b;">
                                    {{ \Carbon\Carbon::parse($achievement->date)->translatedFormat('d M Y') }}
                                </div>
                                <div style="font-size: 0.76rem; color: #94a3b8; margin-top: 2px;">
                                    {{ $achievement->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td>
                                <div>
                                    <a href="{{ route('students.show', $achievement->student_id) }}" class="student-link">
                                        {{ $achievement->student->name ?? 'Siswa Dihapus' }}
                                    </a>
                                </div>
                                <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">
                                    Kelas {{ $achievement->student->class ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-medal" style="color: #f59e0b;"></i> {{ $achievement->name }}
                                </div>
                                @if($achievement->description)
                                <div style="font-size: 0.78rem; color: #64748b; font-style: italic; margin-top: 3px;">
                                    {{ \Illuminate\Support\Str::limit($achievement->description, 50) }}
                                </div>
                                @endif
                            </td>
                            <td>
                                <span class="level-badge">
                                    <i class="fas fa-award"></i> {{ $achievement->level }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #334155;">
                                    {{ $achievement->academicYear->name ?? '-' }}
                                </div>
                                <div style="font-size: 0.76rem; color: #64748b;">
                                    Semester {{ $achievement->semester ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.84rem; color: #475569;">
                                    {{ $achievement->organizer ?? '-' }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                    <a href="{{ route('achievements.certificate', $achievement->id) }}" target="_blank" class="btn-row-action btn-row-cert" title="Cetak Piagam Digital">
                                        <i class="fas fa-certificate"></i>
                                    </a>
                                    @if(Auth::user()->role == 'admin')
                                    <a href="{{ route('achievements.edit', $achievement->id) }}" class="btn-row-action btn-row-edit" title="Edit Data Prestasi">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('achievements.destroy', $achievement->id) }}" method="POST" onsubmit="return confirm('Hapus data prestasi ini?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-row-action btn-row-delete" title="Hapus Prestasi">
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

            {{-- B. Mobile Card View (< 769px) --}}
            <div class="mobile-achievements-view">
                @foreach($achievements as $achievement)
                <div class="achievement-card">
                    {{-- Header Row: Level Badge & Date --}}
                    <div class="m-ach-header">
                        <span class="level-badge">
                            <i class="fas fa-award"></i> {{ $achievement->level }}
                        </span>
                        <span class="m-ach-date">
                            <i class="fas fa-calendar-alt" style="color: #94a3b8;"></i>
                            {{ \Carbon\Carbon::parse($achievement->date)->translatedFormat('d M Y') }}
                        </span>
                    </div>

                    {{-- Title & Optional Description --}}
                    <div>
                        <h4 class="m-ach-title">
                            <i class="fas fa-medal"></i>
                            <span>{{ $achievement->name }}</span>
                        </h4>
                        @if($achievement->description)
                            <div class="m-ach-desc" style="margin-top: 6px;">
                                "{{ $achievement->description }}"
                            </div>
                        @endif
                    </div>

                    {{-- Student & Metadata Details Box --}}
                    <div class="m-ach-details">
                        <div class="m-ach-student-row">
                            <div class="m-student-name-box">
                                <i class="fas fa-user-graduate" style="color: #0284c7;"></i>
                                <a href="{{ route('students.show', $achievement->student_id) }}" class="student-link">
                                    {{ $achievement->student->name ?? 'Siswa Dihapus' }}
                                </a>
                            </div>
                            <span class="m-student-class-badge">
                                Kelas {{ $achievement->student->class ?? '-' }}
                            </span>
                        </div>

                        <div class="m-ach-meta-row">
                            <div class="m-ach-organizer">
                                <i class="fas fa-building" style="color: #94a3b8;"></i>
                                <span>{{ $achievement->organizer ?? 'Penyelenggara: -' }}</span>
                            </div>
                            <div class="m-ach-period">
                                <i class="fas fa-calendar-check" style="color: #94a3b8;"></i>
                                <span>{{ $achievement->academicYear->name ?? '-' }} (Sem {{ $achievement->semester ?? '-' }})</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions Footer --}}
                    <div class="m-ach-actions">
                        <a href="{{ route('achievements.certificate', $achievement->id) }}" target="_blank" class="m-btn-action m-btn-cert" title="Cetak Piagam Digital">
                            <i class="fas fa-certificate"></i>
                            <span>Piagam</span>
                        </a>
                        @if(Auth::user()->role == 'admin')
                        <a href="{{ route('achievements.edit', $achievement->id) }}" class="m-btn-action m-btn-edit" title="Edit Data Prestasi">
                            <i class="fas fa-edit"></i>
                            <span>Edit</span>
                        </a>
                        <form action="{{ route('achievements.destroy', $achievement->id) }}" method="POST" onsubmit="return confirm('Hapus data prestasi ini?');" style="display: block; width: 100%;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="m-btn-action m-btn-delete" title="Hapus Prestasi">
                                <i class="fas fa-trash"></i>
                                <span>Hapus</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($achievements->hasPages())
            <div class="pagination-wrap" style="padding: 16px 20px; border-top: 1px solid #f1f5f9;">
                {{ $achievements->links() }}
            </div>
            @endif
        @endif
    </div>

</div>
@endsection
