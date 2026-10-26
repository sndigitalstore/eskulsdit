@extends('layouts.app')

@section('title', 'Manajemen & Ploting Kelas')
@section('page-title', 'Manajemen & Ploting Kelas')

@push('styles')
<style>
    .cm-header-banner {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
        color: white;
        padding: 24px 28px;
        border-radius: 18px;
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.25);
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    .cm-tab-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 8px;
    }
    .cm-tab-btn {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        border: none;
        background: transparent;
        color: #64748b;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .cm-tab-btn.active {
        background: #2563eb;
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }
    .cm-tab-btn:hover:not(.active) {
        background: #f1f5f9;
        color: #1e293b;
    }

    /* MASTER KELAS CARDS */
    .class-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 16px;
        margin-bottom: 25px;
    }
    .class-card-item {
        background: white;
        border-radius: 14px;
        padding: 18px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        transition: transform 0.15s, border-color 0.15s, box-shadow 0.15s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .class-card-item:hover {
        transform: translateY(-2px);
        border-color: #3b82f6;
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.1);
    }
    .class-card-name {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .class-card-stats {
        margin: 12px 0;
        display: flex;
        gap: 12px;
        font-size: 0.85rem;
    }

    /* PLOTTING WORKSPACE */
    .plotting-controls {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 20px;
        margin-bottom: 20px;
    }
    .plot-bar-sticky {
        position: sticky;
        bottom: 20px;
        background: #0f172a;
        color: white;
        padding: 14px 22px;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 100;
        margin-top: 15px;
        flex-wrap: wrap;
        gap: 12px;
    }

    /* MODAL */
    .modal-backdrop {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-box {
        background: white;
        border-radius: 16px;
        max-width: 480px;
        width: 100%;
        padding: 26px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
</style>
@endpush

@section('content')

{{-- HEADER BANNER --}}
<div class="cm-header-banner">
    <div>
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
            <span style="background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                <i class="fas fa-layer-group"></i> Tata Usaha & Kesiswaan
            </span>
            <span style="background: #ecfdf5; color: #065f46; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 800;">
                Tahun Ajaran Aktif: {{ $activeYear ? $activeYear->name : '-' }}
            </span>
        </div>
        <h2 style="font-size: 1.65rem; font-weight: 800; margin: 0 0 4px; letter-spacing: -0.5px;">
            Manajemen & Ploting Kelas Baru
        </h2>
        <p style="margin: 0; color: #bfdbfe; font-size: 0.9rem;">
            Atur nama kelas baru, rombak kelompok belajar, dan ploting siswa antar-kelas atau antar-tahun ajaran sesuai kebutuhan.
        </p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" class="btn" onclick="openAddClassModal()" style="background: #10b981; color: white; padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 0.88rem; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
            <i class="fas fa-plus-circle"></i> + Tambah Kelas Baru
        </button>
    </div>
</div>

@if(session('success'))
    <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 0.92rem; font-weight: 600;">
        <i class="fas fa-check-circle" style="font-size: 1.2rem; color: #10b981;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 0.92rem; font-weight: 600;">
        <i class="fas fa-exclamation-circle" style="font-size: 1.2rem; color: #ef4444;"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if($errors->any())
    <div style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 18px; font-size: 0.88rem;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- TAB NAVIGATION --}}
<div class="cm-tab-bar">
    <button type="button" class="cm-tab-btn active" id="tabBtnPlotting" onclick="switchTab('plotting')">
        <i class="fas fa-users-cog"></i> Workspace Ploting Siswa
    </button>
    <button type="button" class="cm-tab-btn" id="tabBtnClasses" onclick="switchTab('classes')">
        <i class="fas fa-chalkboard"></i> Master & Kelola Nama Kelas ({{ $classes->count() }})
    </button>
</div>

{{-- ========================================================================= --}}
{{-- TAB 1: WORKSPACE PLOTING SISWA --}}
{{-- ========================================================================= --}}
<div id="tabContentPlotting">
    {{-- CONTROLS & FILTER --}}
    <form method="GET" action="{{ route('class-management.index') }}" id="filterPlotForm">
        <div class="plotting-controls">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                <span style="font-size: 0.85rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fas fa-filter"></i> 1. Tentukan Sumber Siswa yang Ingin Di-Ploting
                </span>
                <span style="font-size: 0.8rem; color: #64748b;">
                    Total Siswa Ditemukan: <strong>{{ $students->count() }}</strong>
                </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; align-items: end;">
                {{-- Sumber Tahun Ajaran --}}
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">
                        Dari Tahun Ajaran
                    </label>
                    <select name="source_academic_year_id" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.85rem; font-weight: 600;" onchange="document.getElementById('filterPlotForm').submit()">
                        @foreach($academicYears as $y)
                            <option value="{{ $y->id }}" {{ $sourceYearId == $y->id ? 'selected' : '' }}>
                                {{ $y->name }} {{ $y->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Sumber Kelas --}}
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">
                        Kelas Asal
                    </label>
                    <select name="source_class" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.85rem;" onchange="document.getElementById('filterPlotForm').submit()">
                        <option value="">-- Semua Kelas --</option>
                        <option value="no_class" {{ $sourceClass === 'no_class' ? 'selected' : '' }}>• Siswa Tanpa Kelas</option>
                        <optgroup label="Berdasarkan Tingkat">
                            <option value="grade_1" {{ $sourceClass === 'grade_1' ? 'selected' : '' }}>Seluruh Tingkat Kelas 1</option>
                            <option value="grade_2" {{ $sourceClass === 'grade_2' ? 'selected' : '' }}>Seluruh Tingkat Kelas 2</option>
                            <option value="grade_3" {{ $sourceClass === 'grade_3' ? 'selected' : '' }}>Seluruh Tingkat Kelas 3</option>
                            <option value="grade_4" {{ $sourceClass === 'grade_4' ? 'selected' : '' }}>Seluruh Tingkat Kelas 4</option>
                            <option value="grade_5" {{ $sourceClass === 'grade_5' ? 'selected' : '' }}>Seluruh Tingkat Kelas 5</option>
                            <option value="grade_6" {{ $sourceClass === 'grade_6' ? 'selected' : '' }}>Seluruh Tingkat Kelas 6</option>
                        </optgroup>
                        <optgroup label="Berdasarkan Rombel Kelas">
                            @foreach($classes as $c)
                                <option value="{{ $c->name }}" {{ $sourceClass === $c->name ? 'selected' : '' }}>Kelas {{ $c->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                {{-- Filter Gender --}}
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">
                        Jenis Kelamin
                    </label>
                    <select name="gender" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.85rem;" onchange="document.getElementById('filterPlotForm').submit()">
                        <option value="">Semua (L & P)</option>
                        <option value="L" {{ $gender === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ $gender === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                {{-- Filter Program --}}
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">
                        Program Sekolah
                    </label>
                    <select name="program" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.85rem;" onchange="document.getElementById('filterPlotForm').submit()">
                        <option value="">Semua Program</option>
                        <option value="Full Day" {{ $program === 'Full Day' ? 'selected' : '' }}>Full Day</option>
                        <option value="Boarding" {{ $program === 'Boarding' ? 'selected' : '' }}>Boarding</option>
                    </select>
                </div>

                {{-- Pencarian Nama --}}
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">
                        Cari Siswa
                    </label>
                    <input type="text" name="search" placeholder="Nama / NIS..." value="{{ $search }}" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.85rem;">
                </div>

                <div>
                    <button type="submit" class="btn" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; width: 100%; border: none; cursor: pointer;">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- FORM EKSEKUSI PLOTING --}}
    <form method="POST" action="{{ route('class-management.plot') }}" id="batchPlotForm" onsubmit="return confirmPlotAction()">
        @csrf
        <input type="hidden" name="source_academic_year_id" value="{{ $sourceYearId }}">

        <div class="card" style="padding: 0; overflow: hidden; border-radius: 14px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
            <div style="padding: 14px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <label style="display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: 0.85rem; color: #1e293b; cursor: pointer;">
                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" style="width: 16px; height: 16px; cursor: pointer;">
                        <span>Pilih Semua Siswa</span>
                    </label>
                    <span id="selectionSummary" style="font-size: 0.82rem; background: #e0f2fe; color: #0369a1; padding: 3px 10px; border-radius: 20px; font-weight: 700;">
                        0 Siswa Dipilih
                    </span>
                </div>
                <div style="font-size: 0.8rem; color: #64748b;">
                    Tips: Centang siswa yang ingin Anda kelompokkan ke kelas tujuan.
                </div>
            </div>

            <div style="overflow-x: auto; max-height: 520px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                    <thead>
                        <tr style="background: white; border-bottom: 2px solid #e2e8f0; text-align: left; position: sticky; top: 0; z-index: 10;">
                            <th style="padding: 10px 14px; width: 40px; text-align: center;">#</th>
                            <th style="padding: 10px 12px; width: 45px;">Pilih</th>
                            <th style="padding: 10px 12px;">Nama Siswa</th>
                            <th style="padding: 10px 12px;">NIS / NISN</th>
                            <th style="padding: 10px 12px;">Kelas Saat Ini</th>
                            <th style="padding: 10px 12px; text-align: center;">Gender</th>
                            <th style="padding: 10px 12px;">Program</th>
                            <th style="padding: 10px 14px; text-align: center; width: 140px;">Pindah Langsung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $idx => $st)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" id="row-{{ $st->id }}" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                            <td style="padding: 10px 14px; text-align: center; color: #94a3b8; font-size: 0.8rem;">
                                {{ $idx + 1 }}
                            </td>
                            <td style="padding: 10px 12px; text-align: center;">
                                <input type="checkbox" name="student_ids[]" value="{{ $st->id }}" class="student-checkbox" data-gender="{{ $st->gender }}" onchange="updateSelectionCounter()" style="width: 16px; height: 16px; cursor: pointer;">
                            </td>
                            <td style="padding: 10px 12px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; color: #475569; overflow: hidden; flex-shrink: 0;">
                                        @if($st->photo)
                                            <img src="{{ asset('storage/' . $st->photo) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            {{ strtoupper(substr($st->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <strong style="color: #1e293b; display: block;">{{ $st->name }}</strong>
                                        <span style="font-size: 0.75rem; color: #94a3b8;">{{ $st->birth_place_date ?: '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 10px 12px;">
                                <span style="font-weight: 600; color: #334155;">{{ $st->nis }}</span>
                                @if($st->nisn)<span style="font-size: 0.75rem; color: #64748b; display: block;">NISN: {{ $st->nisn }}</span>@endif
                            </td>
                            <td style="padding: 10px 12px;">
                                @if($st->class)
                                    <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: 0.8rem;">
                                        {{ $st->class }}
                                    </span>
                                @else
                                    <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600;">(Belum ada kelas)</span>
                                @endif
                            </td>
                            <td style="padding: 10px 12px; text-align: center;">
                                @if($st->gender == 'L')
                                    <span style="background: #e0f2fe; color: #0284c7; font-weight: 700; padding: 2px 7px; border-radius: 4px; font-size: 0.75rem;">L</span>
                                @elseif($st->gender == 'P')
                                    <span style="background: #fce7f3; color: #db2777; font-weight: 700; padding: 2px 7px; border-radius: 4px; font-size: 0.75rem;">P</span>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td style="padding: 10px 12px;">
                                <span style="font-size: 0.78rem; font-weight: 600; color: {{ $st->school_program == 'Boarding' ? '#7c3aed' : '#059669' }};">
                                    {{ $st->school_program ?: 'Reguler' }}
                                </span>
                            </td>
                            <td style="padding: 10px 14px; text-align: center;">
                                <button type="button" class="btn" onclick="openQuickMoveModal('{{ $st->id }}', '{{ addslashes($st->name) }}', '{{ $st->class }}')" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; cursor: pointer;">
                                    <i class="fas fa-arrow-right"></i> Pindah
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3rem; color: #94a3b8;">
                                <i class="fas fa-user-slash" style="font-size: 2.5rem; margin-bottom: 8px; color: #cbd5e1; display: block;"></i>
                                Tidak ada data siswa yang cocok dengan kriteria filter di atas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- STICKY ACTION BAR --}}
        <div class="plot-bar-sticky">
            <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                <div>
                    <span style="font-size: 0.78rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; display: block;">
                        Target Ploting (Tahun Ajaran {{ $activeYear ? $activeYear->name : '-' }})
                    </span>
                    <strong style="font-size: 1.05rem;">
                        <span id="stickySelectedCount">0</span> Siswa Terpilih
                    </strong>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">Pilih Kelas Baru:</label>
                    <select name="target_class" id="targetClassSelect" style="padding: 8px 12px; border-radius: 8px; border: 1px solid #475569; background: #1e293b; color: white; font-weight: 700; font-size: 0.9rem;" required>
                        <option value="">-- Pilih Kelas Tujuan --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->name }}">{{ $c->name }} (Saat ini: {{ $c->student_count }} siswa)</option>
                        @endforeach
                    </select>
                </div>
                <div style="font-size: 0.8rem; color: #94a3b8;">
                    atau ketik nama kelas baru:
                    <input type="text" id="customTargetClass" placeholder="Contoh: 1 Abu Bakar" style="padding: 6px 10px; border-radius: 6px; border: 1px solid #475569; background: #1e293b; color: white; font-size: 0.82rem; width: 140px;" oninput="handleCustomClassInput(this)">
                </div>
            </div>
            <div>
                <button type="submit" class="btn" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 22px; border-radius: 10px; font-weight: 800; font-size: 0.92rem; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(16,185,129,0.4);">
                    <i class="fas fa-check-circle"></i> Eksekusi Ploting Siswa
                </button>
            </div>
        </div>
    </form>
</div>

{{-- ========================================================================= --}}
{{-- TAB 2: MASTER & KELOLA NAMA KELAS --}}
{{-- ========================================================================= --}}
<div id="tabContentClasses" style="display: none;">
    <div class="card" style="margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; flex-wrap: wrap; gap: 10px;">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 0 0 4px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-chalkboard" style="color: #2563eb;"></i> Master Daftar Nama Kelas
                </h3>
                <p style="margin: 0; font-size: 0.82rem; color: #64748b;">
                    Daftar rombel kelas yang terdaftar di sistem. Anda dapat menambah nama kelas baru sesuai kebutuhan sekolah.
                </p>
            </div>
            <button type="button" class="btn" onclick="openAddClassModal()" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; border: none; cursor: pointer;">
                <i class="fas fa-plus"></i> Tambah Kelas
            </button>
        </div>

        <div class="class-cards-grid">
            @forelse($classes as $cls)
            <div class="class-card-item">
                <div>
                    <div class="class-card-name">
                        <span>{{ $cls->name }}</span>
                        <span style="font-size: 0.75rem; background: #ecfdf5; color: #065f46; padding: 2px 8px; border-radius: 10px; font-weight: 800;">
                            {{ $cls->student_count }} Siswa
                        </span>
                    </div>
                    <div class="class-card-stats">
                        <span style="color: #0284c7; font-weight: 600;"><i class="fas fa-male"></i> {{ $cls->male_count }} L</span>
                        <span style="color: #db2777; font-weight: 600;"><i class="fas fa-female"></i> {{ $cls->female_count }} P</span>
                    </div>
                </div>

                <div style="border-top: 1px solid #f1f5f9; padding-top: 10px; margin-top: 6px; display: flex; justify-content: space-between; align-items: center;">
                    <a href="{{ route('class-management.index', ['source_academic_year_id' => $activeYear?->id, 'source_class' => $cls->name]) }}" style="font-size: 0.78rem; color: #2563eb; font-weight: 700; text-decoration: none;" onclick="switchTab('plotting')">
                        <i class="fas fa-users"></i> Lihat Siswa &rarr;
                    </a>
                    <div style="display: flex; gap: 6px;">
                        <button type="button" onclick="openEditClassModal('{{ $cls->id }}', '{{ addslashes($cls->name) }}')" style="background: #f1f5f9; border: 1px solid #cbd5e1; width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #475569; cursor: pointer;" title="Ubah Nama Kelas">
                            <i class="fas fa-pen" style="font-size: 0.72rem;"></i>
                        </button>
                        <form method="POST" action="{{ route('class-management.classes.destroy', $cls->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus master kelas {{ $cls->name }}?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #fee2e2; border: 1px solid #fca5a5; width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #dc2626; cursor: pointer;" title="Hapus Master Kelas">
                                <i class="fas fa-trash" style="font-size: 0.72rem;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: #94a3b8;">
                Belum ada master kelas terdaftar. Klik "+ Tambah Kelas Baru" untuk menambahkan.
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODALS --}}
{{-- ========================================================================= --}}

{{-- MODAL TAMBAH KELAS --}}
<div class="modal-backdrop" id="addClassModal">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #1e293b; margin: 0;">
                <i class="fas fa-plus-circle" style="color: #10b981;"></i> Tambah Master Kelas Baru
            </h3>
            <button type="button" onclick="closeModal('addClassModal')" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>
        <form method="POST" action="{{ route('class-management.classes.store') }}">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #475569; margin-bottom: 6px;">
                    Nama Kelas / Rombel <span style="color: red;">*</span>
                </label>
                <input type="text" name="name" placeholder="Contoh: 1 Abu Bakar, 1 Umar, 2A, dll." style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.9rem;" required autofocus>
                <small style="color: #64748b; font-size: 0.78rem; display: block; margin-top: 4px;">
                    Gunakan format nama yang diinginkan sekolah, misalnya nama sahabat nabi atau kode angka+huruf.
                </small>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('addClassModal')" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 18px; border-radius: 8px; border: none; background: #10b981; color: white; font-weight: 700; font-size: 0.85rem; cursor: pointer;">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT KELAS --}}
<div class="modal-backdrop" id="editClassModal">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #1e293b; margin: 0;">
                <i class="fas fa-edit" style="color: #f59e0b;"></i> Ubah / Rename Nama Kelas
            </h3>
            <button type="button" onclick="closeModal('editClassModal')" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>
        <form method="POST" id="editClassForm" action="">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #475569; margin-bottom: 6px;">
                    Nama Kelas Baru <span style="color: red;">*</span>
                </label>
                <input type="text" name="name" id="editClassNameInput" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.9rem;" required>
            </div>
            <div style="margin-bottom: 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px;">
                <label style="display: flex; align-items: flex-start; gap: 8px; font-size: 0.82rem; color: #1e293b; cursor: pointer;">
                    <input type="checkbox" name="update_existing_students" value="1" checked style="margin-top: 2px;">
                    <span>
                        <strong>Otomatis perbarui siswa:</strong> Ganti juga nama kelas pada seluruh siswa aktif di tahun ini yang saat ini berada di kelas tersebut.
                    </span>
                </label>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('editClassModal')" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 18px; border-radius: 8px; border: none; background: #2563eb; color: white; font-weight: 700; font-size: 0.85rem; cursor: pointer;">Perbarui Nama Kelas</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL QUICK MOVE (1 SISWA) --}}
<div class="modal-backdrop" id="quickMoveModal">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 0;">
                <i class="fas fa-exchange-alt" style="color: #2563eb;"></i> Pindahkan Siswa
            </h3>
            <button type="button" onclick="closeModal('quickMoveModal')" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>
        <form method="POST" id="quickMoveForm" action="">
            @csrf
            <div style="margin-bottom: 14px; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.8rem; color: #64748b;">Nama Siswa:</div>
                <strong style="font-size: 0.95rem; color: #1e293b;" id="quickMoveStudentName">-</strong>
                <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px;">Kelas Saat Ini: <span id="quickMoveStudentClass" style="font-weight: 700; color: #2563eb;">-</span></div>
            </div>
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #475569; margin-bottom: 6px;">
                    Pilih Kelas Baru <span style="color: red;">*</span>
                </label>
                <select name="target_class" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.9rem;" required>
                    <option value="">-- Pilih Kelas Tujuan --</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->name }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('quickMoveModal')" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 18px; border-radius: 8px; border: none; background: #2563eb; color: white; font-weight: 700; font-size: 0.85rem; cursor: pointer;">Pindahkan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tab) {
    if (tab === 'plotting') {
        document.getElementById('tabContentPlotting').style.display = 'block';
        document.getElementById('tabContentClasses').style.display = 'none';
        document.getElementById('tabBtnPlotting').classList.add('active');
        document.getElementById('tabBtnClasses').classList.remove('active');
    } else {
        document.getElementById('tabContentPlotting').style.display = 'none';
        document.getElementById('tabContentClasses').style.display = 'block';
        document.getElementById('tabBtnPlotting').classList.remove('active');
        document.getElementById('tabBtnClasses').classList.add('active');
    }
}

function toggleSelectAll(masterCheckbox) {
    const checkboxes = document.querySelectorAll('.student-checkbox');
    checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
    updateSelectionCounter();
}

function updateSelectionCounter() {
    const checkboxes = document.querySelectorAll('.student-checkbox:checked');
    let male = 0;
    let female = 0;

    checkboxes.forEach(cb => {
        const g = cb.getAttribute('data-gender');
        if (g === 'L') male++;
        else if (g === 'P') female++;
    });

    const total = checkboxes.length;
    const summaryText = `${total} Siswa Dipilih (${male} L, ${female} P)`;
    document.getElementById('selectionSummary').textContent = summaryText;
    document.getElementById('stickySelectedCount').textContent = total;
}

function handleCustomClassInput(input) {
    const val = input.value.trim();
    const select = document.getElementById('targetClassSelect');
    if (val.length > 0) {
        select.required = false;
        select.value = "";
    } else {
        select.required = true;
    }
}

function confirmPlotAction() {
    const checked = document.querySelectorAll('.student-checkbox:checked');
    if (checked.length === 0) {
        alert('Silakan pilih minimal 1 siswa dengan mencentang kotak pada tabel.');
        return false;
    }

    const customVal = document.getElementById('customTargetClass').value.trim();
    const selectVal = document.getElementById('targetClassSelect').value;
    const target = customVal || selectVal;

    if (!target) {
        alert('Silakan pilih atau ketik nama kelas tujuan ploting!');
        return false;
    }

    // If custom class entered, update target_class
    if (customVal) {
        let hiddenTarget = document.getElementById('hiddenCustomTarget');
        if (!hiddenTarget) {
            hiddenTarget = document.createElement('input');
            hiddenTarget.type = 'hidden';
            hiddenTarget.name = 'target_class';
            hiddenTarget.id = 'hiddenCustomTarget';
            document.getElementById('batchPlotForm').appendChild(hiddenTarget);
        }
        hiddenTarget.value = customVal;
        document.getElementById('targetClassSelect').disabled = true;
    }

    return confirm(`Apakah Anda yakin ingin memploting ${checked.length} siswa ke kelas "${target}"?`);
}

function openAddClassModal() {
    document.getElementById('addClassModal').style.display = 'flex';
}

function openEditClassModal(id, name) {
    document.getElementById('editClassNameInput').value = name;
    document.getElementById('editClassForm').action = `/class-management/classes/${id}`;
    document.getElementById('editClassModal').style.display = 'flex';
}

function openQuickMoveModal(studentId, studentName, currentClass) {
    document.getElementById('quickMoveStudentName').textContent = studentName;
    document.getElementById('quickMoveStudentClass').textContent = currentClass || 'Belum ada';
    document.getElementById('quickMoveForm').action = `/class-management/students/${studentId}/quick-move`;
    document.getElementById('quickMoveModal').style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal-backdrop')) {
        event.target.style.display = 'none';
    }
}
</script>
@endpush

@endsection
