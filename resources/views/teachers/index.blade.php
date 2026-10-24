@extends('layouts.app')

@section('title', 'Kelola Guru Pembina')

@push('styles')
<style>
    .teachers-page {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    /* ===== TOPBAR CARD ===== */
    .teachers-header-card {
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

    .teachers-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .teachers-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #047857 0%, #10b981 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(4, 120, 87, 0.25);
    }

    .teachers-header-text h2 {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.25;
    }

    .teachers-header-text p {
        font-size: 0.84rem;
        color: #64748b;
        margin: 3px 0 0 0;
    }

    .teachers-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
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

    .btn-act-orange {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff;
    }

    .btn-act-blue {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
    }

    /* ===== TABLE CARD ===== */
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

    .teachers-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .teachers-table th {
        background: #f8fafc;
        padding: 13px 18px;
        font-size: 0.8rem;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .teachers-table td {
        padding: 14px 18px;
        font-size: 0.88rem;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .teachers-table tbody tr:hover {
        background: #f8fafc;
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

    .btn-row-key { background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; }
    .btn-row-edit { background: #f0f9ff; color: #0284c7; border: 1px solid #bae6fd; }
    .btn-row-delete { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    .btn-row-action:hover {
        transform: translateY(-2px);
    }

    /* ===== MOBILE VIEW (< 769px) ===== */
    .mobile-teachers-view {
        display: none;
    }

    @media (max-width: 768px) {
        .teachers-header-card {
            padding: 16px 14px;
            border-radius: 14px;
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
        }

        .teachers-header-icon {
            width: 38px;
            height: 38px;
            font-size: 1.1rem;
        }

        .teachers-header-text h2 {
            font-size: 1.15rem;
        }

        .teachers-header-text p {
            font-size: 0.78rem;
        }

        /* 2x2 Grid of Actions on Mobile */
        .teachers-header-actions {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .btn-act {
            width: 100%;
            padding: 10px 8px;
            font-size: 0.8rem;
            border-radius: 9px;
            min-height: 40px;
        }

        /* Hide desktop table, show mobile cards */
        .desktop-table-view {
            display: none !important;
        }

        .mobile-teachers-view {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 12px;
            background: #f8fafc;
        }

        .teacher-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .m-teacher-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .m-teacher-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }

        .m-teacher-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #047857 0%, #10b981 100%);
            color: white;
            font-size: 1rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 3px 8px rgba(4, 120, 87, 0.2);
        }

        .m-teacher-info {
            flex: 1;
            min-width: 0;
        }

        .m-teacher-name {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 4px 0;
            line-height: 1.3;
            word-break: break-word;
        }

        .m-teacher-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .m-username-pill {
            font-family: monospace;
            font-size: 0.72rem;
            background: #f1f5f9;
            color: #475569;
            padding: 2px 7px;
            border-radius: 5px;
            font-weight: 600;
        }

        .m-wa-btn {
            font-size: 0.74rem;
            color: #059669;
            background: #ecfdf5;
            padding: 2px 8px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .m-index-pill {
            font-size: 0.74rem;
            font-weight: 800;
            color: #94a3b8;
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 6px;
            flex-shrink: 0;
        }

        /* Assignment Rows inside Card Body */
        .m-teacher-assignments {
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 11px;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .m-assign-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
            font-size: 0.8rem;
        }

        .m-assign-label {
            color: #64748b;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        .m-assign-val {
            text-align: right;
            flex: 1;
        }

        .m-badge-homeroom {
            background: #e0f2fe;
            color: #0284c7;
            border: 1px solid #bae6fd;
            padding: 3px 9px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.74rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .m-badge-eskul {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            padding: 3px 9px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.74rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .m-badge-group {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 7px;
            border-radius: 5px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .m-badge-tilawati { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .m-badge-tahfidz  { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        .m-badge-mapel    { background: #f3e8ff; color: #6d28d9; border: 1px solid #e9d5ff; }

        .m-assign-empty {
            color: #94a3b8;
            font-style: italic;
            font-size: 0.76rem;
        }

        /* Symmetrical 3-button actions footer */
        .m-teacher-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            padding-top: 4px;
            border-top: 1px solid #f1f5f9;
        }

        .m-btn-teacher {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 9px 4px;
            border-radius: 9px;
            font-size: 0.78rem;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            min-height: 38px;
            box-sizing: border-box;
            width: 100%;
        }

        .m-btn-reset {
            background: #fff7ed;
            color: #ea580c;
            border: 1px solid #fed7aa;
        }

        .m-btn-edit {
            background: #f0f9ff;
            color: #0284c7;
            border: 1px solid #bae6fd;
        }

        .m-btn-delete {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
    }

    @media (max-width: 380px) {
        .teachers-header-actions {
            grid-template-columns: 1fr;
        }

        .btn-act {
            font-size: 0.78rem;
        }
    }
</style>
@endpush

@section('content')
<div class="teachers-page">

    {{-- 1. Topbar Card --}}
    <div class="teachers-header-card">
        <div class="teachers-header-left">
            <div class="teachers-header-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="teachers-header-text">
                <h2>Daftar Akun Guru Pembina</h2>
                <p>Total <strong>{{ count($teachers) }}</strong> akun dewan guru & pembina terdaftar.</p>
            </div>
        </div>

        <div class="teachers-header-actions">
            <a href="{{ route('teachers.print') }}" target="_blank" class="btn-act btn-act-dark" title="Cetak Rekap Akun Guru">
                <i class="fas fa-print"></i> <span>Cetak Akun</span>
            </a>
            @if(Auth::user()->role == 'admin')
            <a href="{{ route('teachers.bulk') }}" class="btn-act btn-act-green" title="Import Masal Akun Guru">
                <i class="fas fa-file-import"></i> <span>Import Masal</span>
            </a>
            <button type="button" class="btn-act btn-act-orange" onclick="promptResetAll()" title="Reset Password Seluruh Guru">
                <i class="fas fa-key"></i> <span>Reset Sandi</span>
            </button>
            <form id="resetAllForm" action="{{ route('teachers.reset-all') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="new_password" id="newPasswordInput">
            </form>
            <a href="{{ route('teachers.create') }}" class="btn-act btn-act-blue" title="Tambah Akun Guru Baru">
                <i class="fas fa-plus"></i> <span>Tambah Akun</span>
            </a>
            @endif
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 14px 18px; border-radius: 12px; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.9rem;">
            <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.1rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- 2. Data Card (Table on Desktop, Cards on Mobile) --}}
    <div class="table-card">
        {{-- A. Desktop Table View (>= 769px) --}}
        <div class="desktop-table-view">
            <table class="teachers-table">
                <thead>
                    <tr>
                        <th width="5%" style="text-align: center;">No</th>
                        <th width="25%">Identitas Guru</th>
                        <th width="15%">Wali Kelas</th>
                        <th width="15%">Pembina Eskul</th>
                        <th width="25%">Penugasan Qur'an & Mapel</th>
                        @if(Auth::user()->role == 'admin')
                        <th width="15%" style="text-align: center;">Aksi</th>
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
                        <td style="text-align: center; color: #94a3b8; font-weight: 700;">{{ $index + 1 }}</td>
                        <td>
                            <div style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">{{ $teacher->name }}</div>
                            <div style="font-size: 0.8rem; color: #64748b; margin-top: 3px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                <span style="font-family: monospace; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-weight: 600;">{{ $teacher->username }}</span>
                                @if($teacher->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', str_starts_with($teacher->phone, '0') ? '62' . substr($teacher->phone, 1) : $teacher->phone) }}" target="_blank" style="color: #059669; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fab fa-whatsapp"></i> {{ $teacher->phone }}
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($teacher->homeroom_class)
                                <span style="background: #e0f2fe; color: #0284c7; font-weight: 700; padding: 5px 10px; border-radius: 8px; display: inline-flex; align-items: center; gap: 5px; font-size: 0.78rem; border: 1px solid #bae6fd;">
                                    <i class="fas fa-school"></i> Kelas {{ $teacher->homeroom_class }}
                                </span>
                            @else
                                <span style="color: #94a3b8; font-size: 0.82rem; font-style: italic;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($teacher->eskul)
                                <span style="background: #ecfdf5; color: #059669; font-weight: 700; padding: 5px 10px; border-radius: 8px; display: inline-flex; align-items: center; gap: 5px; font-size: 0.78rem; border: 1px solid #a7f3d0;">
                                    <i class="fas fa-basketball-ball"></i> {{ $teacher->eskul->name }}
                                </span>
                            @else
                                <span style="color: #94a3b8; font-size: 0.82rem; font-style: italic;">—</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                @if($tilawatiList->isNotEmpty())
                                    <div style="display: flex; align-items: center; gap: 5px; flex-wrap: wrap;">
                                        <span style="font-size: 0.72rem; font-weight: 800; color: #15803d; background: #dcfce7; padding: 2px 7px; border-radius: 5px; border: 1px solid #bbf7d0;">
                                            <i class="fas fa-book-open"></i> Tilawati
                                        </span>
                                        <span style="font-size: 0.8rem; color: #334155; font-weight: 600;">
                                            {{ $tilawatiList->pluck('name')->implode(', ') }}
                                        </span>
                                    </div>
                                @endif

                                @if($tahfidzList->isNotEmpty())
                                    <div style="display: flex; align-items: center; gap: 5px; flex-wrap: wrap;">
                                        <span style="font-size: 0.72rem; font-weight: 800; color: #0369a1; background: #e0f2fe; padding: 2px 7px; border-radius: 5px; border: 1px solid #bae6fd;">
                                            <i class="fas fa-quran"></i> Tahfidz
                                        </span>
                                        <span style="font-size: 0.8rem; color: #334155; font-weight: 600;">
                                            {{ $tahfidzList->pluck('name')->implode(', ') }}
                                        </span>
                                    </div>
                                @endif

                                @if(!empty($subjectIds))
                                    <div style="display: flex; align-items: center; gap: 5px; flex-wrap: wrap;">
                                        <span style="font-size: 0.72rem; font-weight: 800; color: #6d28d9; background: #f3e8ff; padding: 2px 7px; border-radius: 5px; border: 1px solid #e9d5ff;">
                                            <i class="fas fa-graduation-cap"></i> Mapel
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
                                    <span style="color: #94a3b8; font-size: 0.82rem; font-style: italic;">Tidak ada penugasan tambahan</span>
                                @endif
                            </div>
                        </td>
                        @if(Auth::user()->role == 'admin')
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                <button type="button" class="btn-row-action btn-row-key" title="Reset Password Guru Ini" onclick="promptResetSingle('{{ $teacher->id }}', '{{ addslashes($teacher->name) }}')">
                                    <i class="fas fa-key"></i>
                                </button>
                                <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn-row-action btn-row-edit" title="Edit Data & Penugasan">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" data-confirm="Yakin ingin menghapus akun guru pembina ini? Data absensi dan nilai yang pernah diinput guru ini akan tetap ada." style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-row-action btn-row-delete" title="Hapus Akun">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->role == 'admin' ? 6 : 5 }}" style="text-align: center; padding: 40px; color: #94a3b8;">
                            Belum ada akun guru pembina terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- B. Mobile Card View (< 769px) --}}
        <div class="mobile-teachers-view">
            @forelse($teachers as $index => $teacher)
            @php
                $tilawatiList = $teacher->mentoringGroups->where('type', 'tilawati');
                $tahfidzList = $teacher->mentoringGroups->where('type', 'tahfidz');
                $subjectIds = $teacher->teaching_subject_ids ?? [];
            @endphp
            <div class="teacher-card">
                {{-- Card Header: Avatar, Name, Username, WA, Index --}}
                <div class="m-teacher-header">
                    <div class="m-teacher-profile">
                        <div class="m-teacher-avatar">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                        </div>
                        <div class="m-teacher-info">
                            <h4 class="m-teacher-name">{{ $teacher->name }}</h4>
                            <div class="m-teacher-meta">
                                <span class="m-username-pill">{{ $teacher->username }}</span>
                                @if($teacher->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', str_starts_with($teacher->phone, '0') ? '62' . substr($teacher->phone, 1) : $teacher->phone) }}" target="_blank" class="m-wa-btn">
                                        <i class="fab fa-whatsapp"></i> {{ $teacher->phone }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <span class="m-index-pill">#{{ $index + 1 }}</span>
                </div>

                {{-- Assignments Body --}}
                <div class="m-teacher-assignments">
                    {{-- Wali Kelas --}}
                    <div class="m-assign-item">
                        <span class="m-assign-label">
                            <i class="fas fa-school" style="color: #0284c7;"></i> Wali Kelas
                        </span>
                        <div class="m-assign-val">
                            @if($teacher->homeroom_class)
                                <span class="m-badge-homeroom">Kelas {{ $teacher->homeroom_class }}</span>
                            @else
                                <span class="m-assign-empty">Bukan Wali Kelas</span>
                            @endif
                        </div>
                    </div>

                    {{-- Pembina Eskul --}}
                    <div class="m-assign-item">
                        <span class="m-assign-label">
                            <i class="fas fa-basketball-ball" style="color: #059669;"></i> Eskul
                        </span>
                        <div class="m-assign-val">
                            @if($teacher->eskul)
                                <span class="m-badge-eskul">{{ $teacher->eskul->name }}</span>
                            @else
                                <span class="m-assign-empty">Bukan Pembina</span>
                            @endif
                        </div>
                    </div>

                    {{-- Halaqah & Mapel --}}
                    <div class="m-assign-item" style="border-top: 1px dashed #e2e8f0; padding-top: 6px;">
                        <span class="m-assign-label">
                            <i class="fas fa-tasks" style="color: #7c3aed;"></i> Penugasan
                        </span>
                        <div class="m-assign-val" style="display: flex; flex-direction: column; gap: 4px; align-items: flex-end;">
                            @if($tilawatiList->isNotEmpty())
                                <div>
                                    <span class="m-badge-group m-badge-tilawati">
                                        <i class="fas fa-book-open"></i> {{ $tilawatiList->pluck('name')->implode(', ') }}
                                    </span>
                                </div>
                            @endif

                            @if($tahfidzList->isNotEmpty())
                                <div>
                                    <span class="m-badge-group m-badge-tahfidz">
                                        <i class="fas fa-quran"></i> {{ $tahfidzList->pluck('name')->implode(', ') }}
                                    </span>
                                </div>
                            @endif

                            @if(!empty($subjectIds))
                                @php
                                    $mySubjNames = collect($subjectIds)->map(fn($id) => $allSubjects[$id] ?? null)->filter()->implode(', ');
                                @endphp
                                @if($mySubjNames)
                                <div>
                                    <span class="m-badge-group m-badge-mapel">
                                        <i class="fas fa-graduation-cap"></i> {{ $mySubjNames }}
                                    </span>
                                </div>
                                @endif
                            @endif

                            @if($tilawatiList->isEmpty() && $tahfidzList->isEmpty() && empty($subjectIds))
                                <span class="m-assign-empty">Tidak ada halaqah/mapel</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Card Actions Footer (Admin Only) --}}
                @if(Auth::user()->role == 'admin')
                <div class="m-teacher-actions">
                    <button type="button" class="m-btn-teacher m-btn-reset" title="Reset Password" onclick="promptResetSingle('{{ $teacher->id }}', '{{ addslashes($teacher->name) }}')">
                        <i class="fas fa-key"></i>
                        <span>Reset</span>
                    </button>
                    <a href="{{ route('teachers.edit', $teacher->id) }}" class="m-btn-teacher m-btn-edit" title="Edit Akun & Penugasan">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </a>
                    <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" data-confirm="Yakin ingin menghapus akun guru pembina ini? Data absensi dan nilai yang pernah diinput guru ini akan tetap ada." style="display: block; width: 100%;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="m-btn-teacher m-btn-delete" title="Hapus Akun">
                            <i class="fas fa-trash"></i>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @empty
            <div style="text-align: center; padding: 48px 20px; color: #94a3b8; background: white; border-radius: 14px;">
                <i class="fas fa-user-slash" style="font-size: 2.8rem; margin-bottom: 12px; color: #cbd5e1;"></i>
                <h4 style="font-size: 1rem; font-weight: 700; color: #475569;">Belum Ada Akun Guru</h4>
                <p style="font-size: 0.84rem;">Silakan tambahkan akun guru pembina melalui tombol di atas.</p>
            </div>
            @endforelse
        </div>
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
            confirmButtonColor: '#ea580c',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
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
            confirmButtonColor: '#ea580c',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
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
