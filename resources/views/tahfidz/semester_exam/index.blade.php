@extends('layouts.app')

@section('title', 'Ujian Hafalan Akhir Semester - Tahfidz')

@section('content')
<style>
    .exam-header-card {
        background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0f766e 100%);
        border-radius: 18px;
        padding: 24px 28px;
        color: white;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px -5px rgba(6, 95, 70, 0.3);
    }
    .semester-pill-nav {
        display: inline-flex;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 30px;
        padding: 4px;
        gap: 4px;
    }
    .semester-pill-btn {
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.82rem;
        color: white;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .semester-pill-btn.active {
        background: white;
        color: #065f46;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .kpi-exam-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .kpi-exam-card {
        background: white;
        border-radius: 14px;
        padding: 18px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .kpi-exam-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }
    .student-exam-card {
        background: white;
        border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(0,0,0,0.03);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .student-exam-card.saved {
        border-color: #a7f3d0;
    }
    .student-exam-header {
        background: #f8fafc;
        padding: 16px 22px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .student-exam-card.saved .student-exam-header {
        background: #f0fdf4;
    }
    .surah-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }
    .surah-table th {
        background: #f8fafc;
        padding: 10px 14px;
        font-weight: 700;
        color: #475569;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .surah-table td {
        padding: 8px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .input-exam-score {
        width: 90px;
        padding: 7px 10px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-weight: 800;
        font-size: 1rem;
        text-align: center;
        color: #0f172a;
        transition: all 0.2s ease;
    }
    .input-exam-score:focus {
        border-color: #059669;
        outline: none;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }
    .btn-add-surah {
        background: #ecfdf5;
        color: #059669;
        border: 1.5px dashed #a7f3d0;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .btn-add-surah:hover {
        background: #d1fae5;
        border-color: #059669;
    }
    .badge-grade {
        padding: 3px 9px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 0.75rem;
        display: inline-block;
        letter-spacing: 0.3px;
    }
    .grade-A { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .grade-B { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
    .grade-C { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .grade-D { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    
    .sticky-exam-bar {
        position: sticky;
        bottom: 20px;
        z-index: 90;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(8px);
        color: white;
        padding: 14px 24px;
        border-radius: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        margin-top: 30px;
    }
</style>

<div class="container-fluid" style="padding: 0 15px; max-width: 1300px; margin: 0 auto;">

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div style="background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle" style="font-size: 1.2rem; color: #10b981;"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-exclamation-circle" style="font-size: 1.2rem; color: #ef4444;"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- HEADER CARD --}}
    <div class="exam-header-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div>
                <span style="background: rgba(255, 255, 255, 0.2); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fas fa-quran"></i> Evaluasi Akhir Semester Halaqah
                </span>
                <h1 style="font-size: 1.65rem; font-weight: 900; margin: 8px 0 4px 0; color: white;">
                    Ujian Hafalan Akhir Semester (Tahfidz)
                </h1>
                <p style="margin: 0; font-size: 0.88rem; opacity: 0.9; max-width: 700px; line-height: 1.5;">
                    Penilaian hafalan surat yang telah disetorkan santri per kelompok pada akhir semester. Data surat otomatis tersaji sesuai capaian tuntas ananda, guru tinggal menguji ulang dan memasukkan nilai.
                </p>
            </div>

            {{-- TOMBOL FILTER SEMESTER & CETAK --}}
            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                <div class="semester-pill-nav">
                    <a href="{{ route('tahfidz.semester-exam.index', ['group_id' => $activeGroup->id, 'year_id' => $selectedYearId, 'semester' => '1']) }}" 
                       class="semester-pill-btn {{ $selectedSemester === '1' ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Semester 1 (Ganjil)
                    </a>
                    <a href="{{ route('tahfidz.semester-exam.index', ['group_id' => $activeGroup->id, 'year_id' => $selectedYearId, 'semester' => '2']) }}" 
                       class="semester-pill-btn {{ $selectedSemester === '2' ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i> Semester 2 (Genap)
                    </a>
                </div>

                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('tahfidz.semester-exam.print', ['group_id' => $activeGroup->id, 'year_id' => $selectedYearId, 'semester' => $selectedSemester]) }}" 
                       target="_blank" 
                       class="btn-action-header" 
                       style="background: white; color: #065f46; font-weight: 800; padding: 8px 16px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-size: 0.82rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <i class="fas fa-print"></i> Cetak Rekap Ujian (A4)
                    </a>
                </div>
            </div>
        </div>

        {{-- FILTER BAR KELOMPOK & TAHUN AJARAN --}}
        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <span style="font-weight: 700; font-size: 0.85rem;"><i class="fas fa-users"></i> Pilih Kelompok:</span>
                <select onchange="window.location.href=this.value" style="padding: 7px 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.4); background: white; color: #1e293b; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    @foreach($myGroups as $grp)
                        <option value="{{ route('tahfidz.semester-exam.index', ['group_id' => $grp->id, 'year_id' => $selectedYearId, 'semester' => $selectedSemester]) }}" {{ $activeGroup->id === $grp->id ? 'selected' : '' }}>
                            {{ $grp->name }} ({{ $grp->students->count() }} Santri)
                        </option>
                    @endforeach
                </select>

                <span style="font-weight: 700; font-size: 0.85rem; margin-left: 10px;"><i class="fas fa-clock"></i> Tahun Ajaran:</span>
                <select onchange="window.location.href=this.value" style="padding: 7px 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.4); background: white; color: #1e293b; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    @foreach($academicYears as $y)
                        <option value="{{ route('tahfidz.semester-exam.index', ['group_id' => $activeGroup->id, 'year_id' => $y->id, 'semester' => $selectedSemester]) }}" {{ $selectedYearId === $y->id ? 'selected' : '' }}>
                            {{ $y->name }} {{ $y->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="font-size: 0.82rem; opacity: 0.95;">
                <i class="fas fa-user-tie"></i> Musyrif: <strong>{{ $activeGroup->mentorUser?->name ?? $activeGroup->mentor_name ?? '-' }}</strong>
            </div>
        </div>
    </div>

    {{-- KPI STATS --}}
    <div class="kpi-exam-grid">
        <div class="kpi-exam-card">
            <div class="kpi-exam-icon" style="background: #e0f2fe; color: #0284c7;">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Santri</div>
                <div style="font-size: 1.4rem; font-weight: 900; color: #0f172a;">{{ $students->count() }} <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Orang</span></div>
            </div>
        </div>

        <div class="kpi-exam-card">
            <div class="kpi-exam-icon" style="background: #ecfdf5; color: #059669;">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Sudah Dievaluasi</div>
                <div style="font-size: 1.4rem; font-weight: 900; color: #059669;">{{ $totalEvaluated }} <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">/ {{ $students->count() }} Santri</span></div>
            </div>
        </div>

        <div class="kpi-exam-card">
            <div class="kpi-exam-icon" style="background: #fef3c7; color: #d97706;">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Rata-Rata Kelompok</div>
                <div style="font-size: 1.4rem; font-weight: 900; color: #b45309;">
                    {{ $groupAverage > 0 ? number_format($groupAverage, 1) : '-' }}
                    @if($groupAverage > 0)
                        <span class="badge-grade grade-{{ $groupAverage >= 90 ? 'A' : ($groupAverage >= 80 ? 'B' : ($groupAverage >= 70 ? 'C' : 'D')) }}" style="font-size: 0.7rem;">
                            {{ \App\Models\TahfidzSemesterExam::resolvePredicate($groupAverage) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="kpi-exam-card">
            <div class="kpi-exam-icon" style="background: #ede9fe; color: #7c3aed;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Periode Evaluasi</div>
                <div style="font-size: 0.95rem; font-weight: 800; color: #6d28d9; margin-top: 2px;">
                    Semester {{ $selectedSemester }} ({{ $selectedSemester == '1' ? 'Ganjil' : 'Genap' }})
                </div>
                <div style="font-size: 0.72rem; color: #64748b;">{{ $yearObj->name ?? '-' }}</div>
            </div>
        </div>
    </div>

    {{-- KOTAK INFORMASI OTOMATISASI --}}
    <div style="background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 12px; padding: 14px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: #dbeafe; color: #1d4ed8; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;">
                <i class="fas fa-magic"></i>
            </div>
            <div style="font-size: 0.85rem; color: #1e3a8a; line-height: 1.5;">
                <strong>Otomatisasi Capaian Surat:</strong> Baris surat di bawah ini secara otomatis diambil dari riwayat setoran tuntas santri pada <strong>Semester {{ $selectedSemester }}</strong>. Guru tinggal menguji ulang dan memasukkan nilai akhir. Guru juga dapat menambah atau menghapus surat sesuai kebutuhan ujian.
            </div>
        </div>
        <button type="button" onclick="saveAllForms()" class="btn-action-header btn-green" style="background: #059669; font-weight: 800; padding: 9px 18px; border-radius: 8px; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);">
            <i class="fas fa-save"></i> Simpan Semua Nilai
        </button>
    </div>

    {{-- FORM UTAMA EVALUASI SANTRI --}}
    <form id="bulkExamForm" method="POST" action="{{ route('tahfidz.semester-exam.store') }}">
        @csrf
        <input type="hidden" name="group_id" value="{{ $activeGroup->id }}">
        <input type="hidden" name="year_id" value="{{ $selectedYearId }}">
        <input type="hidden" name="semester" value="{{ $selectedSemester }}">

        @forelse($students as $index => $std)
            @php
                $examData = $studentsExamData[$std->id] ?? null;
                $isSaved = $examData && $examData['is_saved'];
                $items = $examData['items'] ?? [];
                $avg = $examData['average_score'] ?? 0;
                $pred = $examData['predicate'] ?? 'Jayyid';
            @endphp

            <div class="student-exam-card {{ $isSaved ? 'saved' : '' }}" id="card-student-{{ $std->id }}" data-student-id="{{ $std->id }}">
                {{-- HEADER KARTU SISWA --}}
                <div class="student-exam-header">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: {{ $isSaved ? '#dcfce7; color: #15803d;' : '#f1f5f9; color: #64748b;' }} display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; border: 1.5px solid {{ $isSaved ? '#86efac' : '#cbd5e1' }};">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <h3 style="font-size: 1.05rem; font-weight: 800; margin: 0; color: #0f172a;">{{ $std->name }}</h3>
                                <span style="background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 5px; font-size: 0.72rem; font-weight: 700;">
                                    Kelas {{ $std->class }}
                                </span>
                                <span style="color: #64748b; font-size: 0.75rem;">NIS: {{ $std->nis ?? '-' }}</span>
                            </div>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 3px;">
                                <span id="status-badge-{{ $std->id }}">
                                    @if($isSaved)
                                        <span style="color: #059669; font-weight: 700;"><i class="fas fa-check-circle"></i> Nilai Ujian Tersimpan</span>
                                    @else
                                        <span style="color: #d97706; font-weight: 700;"><i class="fas fa-clock"></i> Belum Disimpan (Draft Auto-Populate)</span>
                                    @endif
                                </span>
                                &bull; Capaian Semester: <strong>{{ count($items) }} Surat</strong>
                            </div>
                        </div>
                    </div>

                    {{-- TANGGAL UJIAN & RATA-RATA --}}
                    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <label style="font-size: 0.75rem; font-weight: 700; color: #64748b;"><i class="fas fa-calendar-day"></i> Tgl Ujian:</label>
                            <input type="date" name="students[{{ $std->id }}][exam_date]" value="{{ $examData['exam_date'] ?? date('Y-m-d') }}" style="padding: 5px 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.8rem; font-weight: 600;">
                        </div>

                        {{-- BADGE RATA-RATA SANTRI --}}
                        <div style="background: white; border: 1.5px solid #e2e8f0; padding: 6px 14px; border-radius: 10px; display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.75rem; font-weight: 700; color: #64748b;">Rata-Rata:</span>
                            <span id="avg-score-{{ $std->id }}" style="font-size: 1.1rem; font-weight: 900; color: #047857;">{{ $avg > 0 ? number_format($avg, 1) : '-' }}</span>
                            <span id="pred-badge-{{ $std->id }}" class="badge-grade grade-{{ $avg >= 90 ? 'A' : ($avg >= 80 ? 'B' : ($avg >= 70 ? 'C' : 'D')) }}">
                                {{ $pred }}
                            </span>
                        </div>

                        {{-- TOMBOL SIMPAN INDIVIDUAL --}}
                        <button type="button" onclick="saveSingleStudent({{ $std->id }})" class="btn-action-header" style="background: #047857; color: white; padding: 7px 14px; border-radius: 8px; font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 5px; border: none; cursor: pointer;">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>

                {{-- TABEL SURAT YANG DIUJIKAN --}}
                <div style="padding: 12px 20px;">
                    <table class="surah-table" id="table-surahs-{{ $std->id }}">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 32%;">Nama Surat Diuji</th>
                                <th style="width: 14%;">Juz (Opsional)</th>
                                <th style="width: 18%; text-align: center;">Nilai Ujian (0 - 100)</th>
                                <th style="width: 14%; text-align: center;">Predikat</th>
                                <th style="width: 17%;">Catatan Singkat</th>
                                <th style="width: 5%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-surahs-{{ $std->id }}">
                            @forelse($items as $iIndex => $item)
                                <tr id="row-surah-{{ $std->id }}-{{ $iIndex }}">
                                    <td class="row-num" style="text-align: center; color: #64748b; font-weight: bold;">{{ $iIndex + 1 }}</td>
                                    <td>
                                        <input type="text" 
                                               list="surahDatalist" 
                                               name="students[{{ $std->id }}][items][{{ $iIndex }}][surah_name]" 
                                               value="{{ $item['surah_name'] }}" 
                                               class="form-control" 
                                               style="font-weight: 700; color: #0f172a; padding: 6px 10px; font-size: 0.88rem;" 
                                               required 
                                               placeholder="Ketik/Pilih Nama Surat">
                                    </td>
                                    <td>
                                        <input type="number" 
                                               name="students[{{ $std->id }}][items][{{ $iIndex }}][juz]" 
                                               value="{{ $item['juz'] ?? '' }}" 
                                               min="1" max="30" 
                                               class="form-control" 
                                               style="width: 70px; padding: 6px 8px; font-size: 0.85rem;" 
                                               placeholder="Juz">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" 
                                               name="students[{{ $std->id }}][items][{{ $iIndex }}][score]" 
                                               value="{{ $item['score'] }}" 
                                               min="0" max="100" step="0.5" 
                                               class="input-exam-score score-input-{{ $std->id }}" 
                                               oninput="calculateStudentAvg({{ $std->id }})" 
                                               required>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="item-pred-badge badge-grade grade-{{ $item['score'] >= 90 ? 'A' : ($item['score'] >= 80 ? 'B' : ($item['score'] >= 70 ? 'C' : 'D')) }}">
                                            {{ $item['predicate'] ?? \App\Models\TahfidzSemesterExam::resolvePredicate((float)$item['score']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               name="students[{{ $std->id }}][items][{{ $iIndex }}][notes]" 
                                               value="{{ $item['notes'] }}" 
                                               class="form-control" 
                                               style="font-size: 0.82rem; padding: 5px 8px;" 
                                               placeholder="Contoh: Lancar, tajwid rapi">
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" onclick="removeSurahRow('{{ $std->id }}', {{ $iIndex }})" style="background: #fee2e2; color: #ef4444; border: none; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;" title="Hapus Surat">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-row-{{ $std->id }}">
                                    <td colspan="7" style="text-align: center; padding: 18px; color: #94a3b8; font-style: italic;">
                                        Belum ada surat yang terdeteksi disetor pada Semester {{ $selectedSemester }}. Klik tombol <strong>+ Tambah Surat Diuji</strong> di bawah untuk memasukkan materi ujian ananda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div style="margin-top: 12px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <button type="button" onclick="addSurahRow({{ $std->id }})" class="btn-add-surah">
                            <i class="fas fa-plus-circle"></i> Tambah Surat Diuji
                        </button>

                        <div style="display: flex; align-items: center; gap: 8px;">
                            <label style="font-size: 0.78rem; font-weight: 700; color: #64748b;">Catatan Umum Musyrif:</label>
                            <input type="text" name="students[{{ $std->id }}][notes]" value="{{ $examData['notes'] ?? '' }}" placeholder="Catatan evaluasi keseluruhan (opsional)" class="form-control" style="font-size: 0.82rem; min-width: 280px; padding: 6px 10px;">
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div style="background: white; border-radius: 16px; padding: 40px; text-align: center; color: #64748b;">
                <i class="fas fa-user-slash" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px;"></i>
                <h3>Belum ada santri di kelompok ini</h3>
                <p>Silakan daftarkan santri ke kelompok tahfidz terlebih dahulu.</p>
            </div>
        @endforelse

        {{-- STICKY ACTION BAR --}}
        @if($students->isNotEmpty())
            <div class="sticky-exam-bar">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <i class="fas fa-info-circle" style="color: #38bdf8; font-size: 1.4rem;"></i>
                    <div>
                        <div style="font-weight: 800; font-size: 0.95rem;">Kelompok: {{ $activeGroup->name }} &bull; Semester {{ $selectedSemester }}</div>
                        <div style="font-size: 0.78rem; opacity: 0.8;">Pastikan nilai setiap surat yang diujikan telah dimasukkan dengan teliti sebelum menyimpan.</div>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-action-header" style="background: #10b981; color: white; padding: 10px 24px; border-radius: 10px; font-weight: 800; font-size: 0.92rem; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);">
                        <i class="fas fa-check-circle"></i> Simpan Seluruh Kelompok
                    </button>
                </div>
            </div>
        @endif
    </form>

    {{-- DATALIST SURAH UNTUK AUTOCOMPLETE --}}
    <datalist id="surahDatalist">
        @foreach($allSurahs as $sName)
            <option value="{{ $sName }}">
        @endforeach
    </datalist>

</div>

{{-- JAVASCRIPT LOGIC DENGAN REAL-TIME CALCULATION & AUTO-POPULATE --}}
<script>
    let surahCounters = {};

    @foreach($students as $std)
        surahCounters[{{ $std->id }}] = {{ count($studentsExamData[$std->id]['items'] ?? []) }};
    @endforeach

    function resolvePredicateJs(score) {
        if (score >= 90) return { label: 'Mumtaz', cls: 'grade-A' };
        if (score >= 80) return { label: 'Jayyid Jiddan', cls: 'grade-B' };
        if (score >= 70) return { label: 'Jayyid', cls: 'grade-C' };
        return { label: 'Maqbul', cls: 'grade-D' };
    }

    function calculateStudentAvg(studentId) {
        const inputs = document.querySelectorAll('.score-input-' + studentId);
        let total = 0;
        let count = 0;

        inputs.forEach(inp => {
            const row = inp.closest('tr');
            const val = parseFloat(inp.value);
            const badge = row.querySelector('.item-pred-badge');

            if (!isNaN(val)) {
                total += val;
                count++;
                if (badge) {
                    const pred = resolvePredicateJs(val);
                    badge.textContent = pred.label;
                    badge.className = 'item-pred-badge badge-grade ' + pred.cls;
                }
            } else if (badge) {
                badge.textContent = '-';
                badge.className = 'item-pred-badge badge-grade';
            }
        });

        const avgEl = document.getElementById('avg-score-' + studentId);
        const predEl = document.getElementById('pred-badge-' + studentId);

        if (count > 0) {
            const avg = total / count;
            const pred = resolvePredicateJs(avg);
            if (avgEl) avgEl.textContent = avg.toFixed(1);
            if (predEl) {
                predEl.textContent = pred.label;
                predEl.className = 'badge-grade ' + pred.cls;
            }
        } else {
            if (avgEl) avgEl.textContent = '-';
            if (predEl) {
                predEl.textContent = 'Belum Ada';
                predEl.className = 'badge-grade';
            }
        }
    }

    function addSurahRow(studentId) {
        const tbody = document.getElementById('tbody-surahs-' + studentId);
        const emptyRow = document.getElementById('empty-row-' + studentId);
        if (emptyRow) emptyRow.remove();

        if (!surahCounters[studentId]) surahCounters[studentId] = 0;
        const idx = surahCounters[studentId]++;
        const rowNum = tbody.querySelectorAll('tr').length + 1;

        const tr = document.createElement('tr');
        tr.id = `row-surah-${studentId}-${idx}`;
        tr.innerHTML = `
            <td class="row-num" style="text-align: center; color: #64748b; font-weight: bold;">${rowNum}</td>
            <td>
                <input type="text" 
                       list="surahDatalist" 
                       name="students[${studentId}][items][${idx}][surah_name]" 
                       class="form-control" 
                       style="font-weight: 700; color: #0f172a; padding: 6px 10px; font-size: 0.88rem;" 
                       required 
                       placeholder="Ketik/Pilih Nama Surat">
            </td>
            <td>
                <input type="number" 
                       name="students[${studentId}][items][${idx}][juz]" 
                       min="1" max="30" 
                       class="form-control" 
                       style="width: 70px; padding: 6px 8px; font-size: 0.85rem;" 
                       placeholder="Juz">
            </td>
            <td style="text-align: center;">
                <input type="number" 
                       name="students[${studentId}][items][${idx}][score]" 
                       value="85" 
                       min="0" max="100" step="0.5" 
                       class="input-exam-score score-input-${studentId}" 
                       oninput="calculateStudentAvg(${studentId})" 
                       required>
            </td>
            <td style="text-align: center;">
                <span class="item-pred-badge badge-grade grade-B">Jayyid Jiddan</span>
            </td>
            <td>
                <input type="text" 
                       name="students[${studentId}][items][${idx}][notes]" 
                       class="form-control" 
                       style="font-size: 0.82rem; padding: 5px 8px;" 
                       placeholder="Catatan singkat">
            </td>
            <td style="text-align: center;">
                <button type="button" onclick="removeSurahRow('${studentId}', ${idx})" style="background: #fee2e2; color: #ef4444; border: none; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;" title="Hapus Surat">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        calculateStudentAvg(studentId);
    }

    function removeSurahRow(studentId, idx) {
        const row = document.getElementById(`row-surah-${studentId}-${idx}`);
        if (row) {
            row.remove();
            // Re-index row numbers
            const tbody = document.getElementById('tbody-surahs-' + studentId);
            tbody.querySelectorAll('tr').forEach((r, i) => {
                const num = r.querySelector('.row-num');
                if (num) num.textContent = i + 1;
            });
            calculateStudentAvg(studentId);
        }
    }

    function saveSingleStudent(studentId) {
        const card = document.getElementById('card-student-' + studentId);
        const inputs = card.querySelectorAll('input, select, textarea');
        const formData = new FormData();

        formData.append('_token', '{{ csrf_token() }}');
        formData.append('group_id', '{{ $activeGroup->id }}');
        formData.append('year_id', '{{ $selectedYearId }}');
        formData.append('semester', '{{ $selectedSemester }}');

        inputs.forEach(inp => {
            if (inp.name) {
                formData.append(inp.name, inp.value);
            }
        });

        const btn = card.querySelector('.student-exam-header button');
        const originalBtn = btn ? btn.innerHTML : '';
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;
        }

        fetch('{{ route("tahfidz.semester-exam.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.innerHTML = originalBtn;
                btn.disabled = false;
            }
            if (data.success) {
                card.classList.add('saved');
                const badge = document.getElementById('status-badge-' + studentId);
                if (badge) {
                    badge.innerHTML = '<span style="color: #059669; font-weight: 700;"><i class="fas fa-check-circle"></i> Nilai Ujian Tersimpan</span>';
                }
                alert('✓ ' + data.message);
            } else {
                alert('✕ ' + (data.message || 'Gagal menyimpan data'));
            }
        })
        .catch(err => {
            if (btn) {
                btn.innerHTML = originalBtn;
                btn.disabled = false;
            }
            alert('✕ Terjadi kesalahan koneksi saat menyimpan.');
            console.error(err);
        });
    }

    function saveAllForms() {
        document.getElementById('bulkExamForm').submit();
    }
</script>
@endsection
