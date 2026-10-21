@extends('layouts.app')

@section('title', 'Raport Akademik – ' . $assessmentInfo['name'])
@section('page-title', 'Raport Akademik (' . $assessmentInfo['short'] . ')')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">

    <!-- 5-Tab Selector: Eskul vs Tilawati vs Tahfidz vs Akademik vs Karakter -->
    <div class="no-print" style="margin-bottom: 22px;">
        <div style="display: flex; gap: 8px; background: #f1f5f9; padding: 6px; border-radius: 14px; border: 1px solid #e2e8f0; width: fit-content; max-width: 100%; overflow-x: auto;">
            @if(Auth::user()->role !== 'curriculum')
            <a href="{{ route('reports.index', ['tab' => 'eskul']) }}" 
               style="padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; color: #64748b; background: transparent;">
                <i class="fas fa-basketball-ball" style="color: #64748b;"></i>
                <span>Laporan Eskul</span>
            </a>
            @endif
            
            <a href="{{ route('reports.index', ['tab' => 'tilawati']) }}" 
               style="padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; color: #64748b; background: transparent;">
                <i class="fas fa-book-open" style="color: #16a34a;"></i>
                <span>Laporan Tilawati</span>
                <span style="font-size: 0.72rem; padding: 2px 8px; border-radius: 999px; font-weight: 700; background: #dcfce7; color: #15803d;">Jilid</span>
            </a>
            
            <a href="{{ route('reports.index', ['tab' => 'tahfidz']) }}" 
               style="padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; color: #64748b; background: transparent;">
                <i class="fas fa-quran" style="color: #0284c7;"></i>
                <span>Laporan Tahfidz</span>
                <span style="font-size: 0.72rem; padding: 2px 8px; border-radius: 999px; font-weight: 700; background: #e0f2fe; color: #0369a1;">Hafalan</span>
            </a>

            <a href="{{ route('academic-reports.index') }}" 
               style="padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; color: #7c3aed; background: #ffffff; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08);">
                <i class="fas fa-graduation-cap" style="color: #7c3aed;"></i>
                <span>Laporan Akademik</span>
                <span style="font-size: 0.72rem; padding: 2px 8px; border-radius: 999px; font-weight: 700; background: #f3e8ff; color: #7c3aed;">Raport</span>
            </a>

            @if(Auth::user()->role !== 'curriculum')
            <a href="{{ route('character.report') }}" 
               style="padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; color: #d97706; background: transparent;">
                <i class="fas fa-smile-beam" style="color: #d97706;"></i>
                <span>Laporan Karakter</span>
                <span style="font-size: 0.72rem; padding: 2px 8px; border-radius: 999px; font-weight: 700; background: #fef3c7; color: #b45309;">SMILE</span>
            </a>
            @endif
        </div>
    </div>

    <!-- TOP HEADER BANNER -->
    <div class="card" style="margin-bottom: 24px; background: linear-gradient(135deg, #0369a1 0%, #0284c7 60%, #0ea5e9 100%); color: white; border: none; padding: 24px 28px; position: relative; overflow: hidden; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.3);">
        <div style="position: absolute; right: 20px; bottom: -20px; font-size: 8rem; color: rgba(255, 255, 255, 0.08); pointer-events: none;">
            <i class="fas fa-graduation-cap"></i>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; position: relative; z-index: 2;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.18); padding: 4px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
                    <i class="fas fa-book-open"></i> SMILE Kurikulum Merdeka
                </div>
                <h2 style="margin: 0; font-size: 1.6rem; font-weight: 900; letter-spacing: -0.02em;">
                    Raport Akademik SDIT AN NADZIR
                </h2>
                <p style="margin: 6px 0 0 0; opacity: 0.92; font-size: 0.92rem; max-width: 680px;">
                    Pengelolaan nilai sumatif, capaian pembelajaran, rekapitulasi kelas, serta pencetakan lembar rapor resmi Kurikulum Merdeka.
                </p>
            </div>
            
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                @if($selectedClass && $students->count() > 0)
                    <a href="{{ route('academic-reports.print-all-students', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'assessment' => $selectedAssessment]) }}" target="_blank" class="btn-action-header" style="background: white; color: #0284c7; font-weight: 700; padding: 10px 18px; border-radius: 12px; text-decoration: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <i class="fas fa-print"></i> Cetak Semua Rapor (A4)
                    </a>
                    <a href="{{ route('academic-reports.export-leger', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'assessment' => $selectedAssessment]) }}" class="btn-action-header" style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.4); font-weight: 700; padding: 10px 18px; border-radius: 12px; text-decoration: none; color: white;">
                        <i class="fas fa-file-excel"></i> Unduh Leger
                    </a>
                    <a href="{{ route('academic-reports.print-recap', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'assessment' => $selectedAssessment]) }}" target="_blank" class="btn-action-header" style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.4); font-weight: 700; padding: 10px 18px; border-radius: 12px; text-decoration: none; color: white;">
                        <i class="fas fa-table"></i> Rekap Kelas
                    </a>
                @endif
                @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                    <a href="{{ route('subjects.index') }}" class="btn-action-header" style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.4); font-weight: 700; padding: 10px 18px; border-radius: 12px; text-decoration: none; color: white;">
                        <i class="fas fa-cog"></i> Kelola Mapel
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- ASSESSMENT SWITCHER (STS 1, SAS 1, STS 2, SAS 2) -->
    <div style="display: flex; gap: 12px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 4px;">
        @php
            $assessments = [
                'STS_1' => ['label' => 'STS 1', 'desc' => 'Tengah Semester 1 (Ganjil)', 'icon' => 'fa-calendar-alt', 'color' => '#0284c7'],
                'SAS_1' => ['label' => 'SAS 1', 'desc' => 'Akhir Semester 1 (Ganjil)', 'icon' => 'fa-check-circle', 'color' => '#16a34a'],
                'STS_2' => ['label' => 'STS 2', 'desc' => 'Tengah Semester 2 (Genap)', 'icon' => 'fa-calendar-check', 'color' => '#d97706'],
                'SAS_2' => ['label' => 'SAS 2', 'desc' => 'Akhir Semester 2 (Genap)', 'icon' => 'fa-award', 'color' => '#7c3aed'],
            ];
        @endphp

        @foreach($assessments as $key => $meta)
            @php $isActive = $selectedAssessment === $key; @endphp
            <a href="{{ route('academic-reports.index', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'assessment' => $key, 'tab' => $activeTab, 'subject_id' => $selectedSubjectId]) }}" 
               style="flex: 1; min-width: 200px; text-decoration: none; padding: 14px 18px; border-radius: 14px; background: {{ $isActive ? 'linear-gradient(135deg, ' . $meta['color'] . ', ' . $meta['color'] . 'dd)' : '#ffffff' }}; color: {{ $isActive ? '#ffffff' : '#475569' }}; border: {{ $isActive ? 'none' : '1px solid #e2e8f0' }}; box-shadow: {{ $isActive ? '0 8px 20px -4px ' . $meta['color'] . '66' : '0 2px 4px rgba(0,0,0,0.02)' }}; transition: all 0.2s; display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: {{ $isActive ? 'rgba(255,255,255,0.25)' : $meta['color'] . '15' }}; color: {{ $isActive ? '#ffffff' : $meta['color'] }}; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fas {{ $meta['icon'] }}"></i>
                </div>
                <div>
                    <div style="font-size: 1.1rem; font-weight: 800; line-height: 1.2;">{{ $meta['label'] }}</div>
                    <div style="font-size: 0.78rem; opacity: {{ $isActive ? '0.9' : '0.7' }}; margin-top: 2px;">{{ $meta['desc'] }}</div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- FILTER CARD -->
    <div class="card" style="margin-bottom: 24px; padding: 18px 24px; border-radius: 14px;">
        <form action="{{ route('academic-reports.index') }}" method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
            <input type="hidden" name="assessment" value="{{ $selectedAssessment }}">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">

            <div style="flex: 1; min-width: 220px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.85rem;">
                    <i class="fas fa-calendar" style="color: #0284c7;"></i> Tahun Pelajaran
                </label>
                <select name="year_id" class="form-control" onchange="this.form.submit()" required>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.85rem;">
                    <i class="fas fa-chalkboard" style="color: #0284c7;"></i> Kelas
                </label>
                <select name="class" class="form-control" onchange="this.form.submit()" required {{ $isHomeroom ? 'disabled' : '' }}>
                    @foreach($classes as $c)
                        <option value="{{ $c }}" {{ $selectedClass == $c ? 'selected' : '' }}>Kelas {{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div style="min-width: 140px;">
                <button type="submit" class="btn-action-header btn-blue" style="height: 44px; width: 100%; justify-content: center; font-weight: 700; background: #0284c7;">
                    <i class="fas fa-filter"></i> Terapkan
                </button>
            </div>
        </form>
    </div>

    @if($selectedClass && $students->count() > 0)
        <!-- STATS BAR -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div class="card" style="padding: 16px 20px; border-left: 4px solid #0284c7; border-radius: 12px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Siswa</div>
                <div style="font-size: 1.6rem; font-weight: 900; color: #1e293b; margin-top: 4px;">{{ $classStats['total_students'] }}</div>
                <div style="font-size: 0.78rem; color: #0284c7; margin-top: 2px;">Kelas {{ $selectedClass }}</div>
            </div>
            <div class="card" style="padding: 16px 20px; border-left: 4px solid #16a34a; border-radius: 12px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Rata-Rata Kelas</div>
                <div style="font-size: 1.6rem; font-weight: 900; color: #16a34a; margin-top: 4px;">{{ $classStats['class_average'] }}</div>
                <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">Skala 0 - 100</div>
            </div>
            <div class="card" style="padding: 16px 20px; border-left: 4px solid #f59e0b; border-radius: 12px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Tertinggi / Terendah</div>
                <div style="font-size: 1.4rem; font-weight: 900; color: #1e293b; margin-top: 4px;">
                    <span style="color: #16a34a;">{{ $classStats['highest_score'] }}</span> / <span style="color: #ef4444;">{{ $classStats['lowest_score'] }}</span>
                </div>
                <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">Nilai Rata-rata Siswa</div>
            </div>
            <div class="card" style="padding: 16px 20px; border-left: 4px solid #8b5cf6; border-radius: 12px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Ketuntasan KKTP</div>
                <div style="font-size: 1.6rem; font-weight: 900; color: #8b5cf6; margin-top: 4px;">{{ $classStats['passed_percentage'] }}%</div>
                <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">{{ $classStats['passed_count'] }} siswa di atas KKTP</div>
            </div>
        </div>

        <!-- NAVIGATION TABS -->
        <div style="display: flex; gap: 8px; border-bottom: 2px solid #e2e8f0; margin-bottom: 20px;">
            <a href="{{ route('academic-reports.index', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'assessment' => $selectedAssessment, 'tab' => 'recap', 'subject_id' => $selectedSubjectId]) }}" 
               style="padding: 12px 20px; font-weight: 700; font-size: 0.92rem; text-decoration: none; color: {{ $activeTab === 'recap' ? '#0284c7' : '#64748b' }}; border-bottom: 3px solid {{ $activeTab === 'recap' ? '#0284c7' : 'transparent' }}; margin-bottom: -2px; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-table"></i> Rekapitulasi Rapor Kelas
            </a>
            <a href="{{ route('academic-reports.index', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'assessment' => $selectedAssessment, 'tab' => 'input', 'subject_id' => $selectedSubjectId]) }}" 
               style="padding: 12px 20px; font-weight: 700; font-size: 0.92rem; text-decoration: none; color: {{ $activeTab === 'input' ? '#0284c7' : '#64748b' }}; border-bottom: 3px solid {{ $activeTab === 'input' ? '#0284c7' : 'transparent' }}; margin-bottom: -2px; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-edit"></i> Input Nilai per Mapel
            </a>
            <a href="{{ route('academic-reports.index', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'assessment' => $selectedAssessment, 'tab' => 'attendance', 'subject_id' => $selectedSubjectId]) }}" 
               style="padding: 12px 20px; font-weight: 700; font-size: 0.92rem; text-decoration: none; color: {{ $activeTab === 'attendance' ? '#0284c7' : '#64748b' }}; border-bottom: 3px solid {{ $activeTab === 'attendance' ? '#0284c7' : 'transparent' }}; margin-bottom: -2px; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-clipboard-list"></i> Kehadiran & Catatan Wali
            </a>
        </div>

        <!-- TAB 1: REKAPITULASI RAPOR KELAS -->
        @if($activeTab === 'recap')
            <div class="card" style="padding: 0; overflow: hidden; border-radius: 14px;">
                <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #1e293b;">
                            Rekap Nilai Siswa: Kelas {{ $selectedClass }} – {{ $assessmentInfo['name'] }}
                        </h4>
                        <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">
                            Klik nama siswa atau tombol Cetak Rapor untuk mencetak lembar rapor individu A4
                        </div>
                    </div>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <a href="{{ route('academic-reports.print-all-students', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'assessment' => $selectedAssessment]) }}" target="_blank" class="btn btn-sm btn-primary" style="font-weight: 700; background: #0284c7;">
                            <i class="fas fa-print"></i> Cetak Semua Rapor (A4)
                        </a>
                        <a href="{{ route('academic-reports.export-leger', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'assessment' => $selectedAssessment]) }}" class="btn btn-sm btn-success" style="font-weight: 700; background: #16a34a;">
                            <i class="fas fa-file-excel"></i> Unduh Leger
                        </a>
                        <a href="{{ route('academic-reports.print-recap', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'assessment' => $selectedAssessment]) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="font-weight: 700;">
                            <i class="fas fa-table"></i> Cetak Rekap Kelas
                        </a>
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 650px; overflow-y: auto;">
                    <table class="table" style="width: 100%; border-collapse: collapse; margin: 0; font-size: 0.84rem;">
                        <thead style="position: sticky; top: 0; z-index: 10; background: #f8fafc;">
                            <tr style="border-bottom: 2px solid #cbd5e1; color: #475569;">
                                <th style="padding: 10px; text-align: center; width: 45px;">No</th>
                                <th style="padding: 10px; text-align: left; width: 85px;">NIS</th>
                                <th style="padding: 10px; text-align: left; min-width: 180px;">Nama Siswa</th>
                                @foreach($subjects as $sb)
                                    <th style="padding: 8px 6px; text-align: center; min-width: 58px;" title="{{ $sb->name }} (KKTP: {{ (int)$sb->kkm }})">
                                        <div style="font-weight: 800; color: #0f172a;">{{ $sb->code ?: substr($sb->name, 0, 4) }}</div>
                                        <div style="font-size: 0.68rem; color: #64748b;">{{ (int)$sb->kkm }}</div>
                                    </th>
                                @endforeach
                                <th style="padding: 10px; text-align: center; min-width: 65px; background: #f1f5f9; font-weight: 800;">Total</th>
                                <th style="padding: 10px; text-align: center; min-width: 65px; background: #f1f5f9; font-weight: 800;">Rerata</th>
                                <th style="padding: 10px; text-align: center; min-width: 50px; background: #fef3c7; font-weight: 800; color: #92400e;">Rank</th>
                                <th style="padding: 10px; text-align: center; min-width: 110px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse($recapTable as $stuId => $row)
                                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                    <td style="padding: 10px; text-align: center; color: #64748b;">{{ $no++ }}</td>
                                    <td style="padding: 10px; font-weight: 600; color: #475569;">{{ $row['student']->nis ?? '-' }}</td>
                                    <td style="padding: 10px;">
                                        <div style="font-weight: 700; color: #0f172a;">{{ $row['student']->name }}</div>
                                    </td>
                                    @foreach($subjects as $sb)
                                        @php
                                            $sc = $row['scores'][$sb->id]['score'] ?? null;
                                            $isPass = $row['scores'][$sb->id]['is_pass'] ?? false;
                                        @endphp
                                        <td style="padding: 8px 4px; text-align: center;">
                                            @if($sc !== null)
                                                <span style="display: inline-block; padding: 2px 6px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; background: {{ $isPass ? '#dcfce7' : '#fee2e2' }}; color: {{ $isPass ? '#166534' : '#991b1b' }};">
                                                    {{ $sc == round($sc) ? (int)$sc : $sc }}
                                                </span>
                                            @else
                                                <span style="color: #cbd5e1;">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td style="padding: 10px; text-align: center; background: #f8fafc; font-weight: 800; color: #0f172a;">
                                        {{ $row['total'] > 0 ? (round($row['total']) == $row['total'] ? (int)$row['total'] : $row['total']) : '-' }}
                                    </td>
                                    <td style="padding: 10px; text-align: center; background: #f8fafc; font-weight: 800; color: {{ $row['average'] >= 75 ? '#16a34a' : ($row['average'] > 0 ? '#ef4444' : '#64748b') }};">
                                        {{ $row['average'] > 0 ? $row['average'] : '-' }}
                                    </td>
                                    <td style="padding: 10px; text-align: center; background: #fffbeb; font-weight: 800; color: #92400e;">
                                        @if($row['rank'] !== '-')
                                            <span style="display: inline-block; padding: 2px 8px; border-radius: 999px; background: #fef3c7; color: #b45309; font-size: 0.8rem;">
                                                #{{ $row['rank'] }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="padding: 10px; text-align: center;">
                                        <a href="{{ route('academic-reports.print-student', ['student' => $row['student']->id, 'year_id' => $selectedYearId, 'assessment' => $selectedAssessment]) }}" 
                                           target="_blank" 
                                           class="btn btn-sm" 
                                           style="background: #0284c7; color: white; padding: 4px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fas fa-print"></i> Cetak Rapor
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 6 + $subjects->count() }}" style="padding: 40px; text-align: center; color: #94a3b8;">
                                        Belum ada siswa terdaftar pada kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- TAB 2: INPUT NILAI KOLEKTIF PER MAPEL -->
        @if($activeTab === 'input')
            <div class="card" style="padding: 24px; border-radius: 14px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 16px;">
                    <div>
                        <h4 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-edit" style="color: #0284c7;"></i> Form Input Nilai: {{ $assessmentInfo['name'] }}
                        </h4>
                        <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.85rem;">
                            Pilih Mata Pelajaran lalu isikan nilai angka siswa (0-100) dan catatan capaian kompetensi.
                        </p>
                    </div>

                    <!-- Subject Selector -->
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <label style="font-weight: 700; color: #475569; font-size: 0.88rem; margin: 0;">Pilih Mapel:</label>
                        <select class="form-control" style="width: auto; min-width: 260px; font-weight: 700;" onchange="window.location.href='{{ route('academic-reports.index', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'assessment' => $selectedAssessment, 'tab' => 'input']) }}&subject_id=' + this.value">
                            @foreach($subjects as $sb)
                                <option value="{{ $sb->id }}" {{ $selectedSubjectId == $sb->id ? 'selected' : '' }}>
                                    {{ $sb->name }} (KKTP: {{ (int)$sb->kkm }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($selectedSubject)
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <span style="background: #16a34a; color: white; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.85rem;">
                                {{ $selectedSubject->code ?: 'MAPEL' }}
                            </span>
                            <span style="font-weight: 800; color: #166534; font-size: 0.95rem;">
                                {{ $selectedSubject->name }}
                            </span>
                            <span style="color: #64748b; font-size: 0.85rem;">
                                (Kategori: {{ $selectedSubject->category }} | KKTP Minimum: <strong>{{ (int)$selectedSubject->kkm }}</strong>)
                            </span>
                        </div>
                        
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <a href="{{ route('academic-reports.export-template', ['year_id' => $selectedYearId, 'class' => $selectedClass, 'assessment' => $selectedAssessment, 'subject_id' => $selectedSubject->id]) }}" 
                               class="btn btn-sm btn-outline-success" 
                               style="font-weight: 700; background: white; border-color: #16a34a; color: #166534; border-radius: 8px; padding: 6px 12px;">
                                <i class="fas fa-file-download"></i> Format Excel / CSV
                            </a>
                            <button type="button" 
                                    onclick="openImportScoresModal()" 
                                    class="btn btn-sm btn-outline-primary" 
                                    style="font-weight: 700; background: white; border-color: #0284c7; color: #0284c7; border-radius: 8px; padding: 6px 12px;">
                                <i class="fas fa-file-upload"></i> Import Nilai
                            </button>
                            <button type="button" 
                                    onclick="autoGenerateNotes('{{ addslashes($selectedSubject->name) }}')" 
                                    class="btn btn-sm" 
                                    style="font-weight: 700; background: #fef3c7; border: 1px solid #f59e0b; color: #b45309; border-radius: 8px; padding: 6px 12px;">
                                <i class="fas fa-magic"></i> Auto-Generate Capaian
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('academic-reports.store-scores') }}" method="POST">
                        @csrf
                        <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
                        <input type="hidden" name="class" value="{{ $selectedClass }}">
                        <input type="hidden" name="assessment_type" value="{{ $selectedAssessment }}">
                        <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">

                        <div class="table-responsive">
                            <table class="table" style="width: 100%; border-collapse: collapse; margin: 0;">
                                <thead>
                                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 0.82rem; color: #475569;">
                                        <th style="padding: 10px; text-align: center; width: 45px;">No</th>
                                        <th style="padding: 10px; text-align: left; width: 90px;">NIS</th>
                                        <th style="padding: 10px; text-align: left; min-width: 200px;">Nama Siswa</th>
                                        <th style="padding: 10px; text-align: center; width: 130px;">Nilai (0 - 100)</th>
                                        <th style="padding: 10px; text-align: center; width: 110px;">Predikat</th>
                                        <th style="padding: 10px; text-align: left; min-width: 300px;">Capaian Pembelajaran / Catatan Guru</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $stu)
                                        @php
                                            $sc = $scoresMap[$stu->id][$selectedSubject->id] ?? null;
                                            $val = $sc ? (float)$sc->score : null;
                                            $pred = $sc?->predicate ?? ($val !== null ? \App\Models\AcademicScore::calculatePredicate($val) : '');
                                        @endphp
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 10px; text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                                            <td style="padding: 10px; color: #64748b; font-weight: 600;">{{ $stu->nis ?? '-' }}</td>
                                            <td style="padding: 10px;">
                                                <div style="font-weight: 700; color: #0f172a;">{{ $stu->name }}</div>
                                            </td>
                                            <td style="padding: 8px 10px; text-align: center;">
                                                <input type="number" 
                                                       step="0.01" 
                                                       min="0" 
                                                       max="100" 
                                                       name="scores[{{ $stu->id }}]" 
                                                       id="score_{{ $stu->id }}"
                                                       data-student-id="{{ $stu->id }}"
                                                       value="{{ $val !== null ? (round($val) == $val ? (int)$val : $val) : '' }}" 
                                                       placeholder="0 - 100" 
                                                       class="form-control score-input" 
                                                       style="text-align: center; font-weight: 700; height: 38px; border-radius: 8px;"
                                                       oninput="updatePredicate(this, '{{ $stu->id }}')">
                                            </td>
                                            <td style="padding: 8px 10px; text-align: center;">
                                                <select name="predicates[{{ $stu->id }}]" id="predicate_{{ $stu->id }}" class="form-control" style="text-align: center; font-weight: 800; height: 38px; border-radius: 8px;">
                                                    <option value="A" {{ $pred == 'A' ? 'selected' : '' }}>A (Sangat Baik)</option>
                                                    <option value="B" {{ $pred == 'B' ? 'selected' : '' }}>B (Baik)</option>
                                                    <option value="C" {{ $pred == 'C' ? 'selected' : '' }}>C (Cukup)</option>
                                                    <option value="D" {{ $pred == 'D' ? 'selected' : '' }}>D (Perlu Bimbingan)</option>
                                                </select>
                                            </td>
                                            <td style="padding: 8px 10px;">
                                                <input type="text" 
                                                       name="notes[{{ $stu->id }}]" 
                                                       id="note_{{ $stu->id }}"
                                                       value="{{ $sc?->achievement_note ?? '' }}" 
                                                       placeholder="Contoh: Menunjukkan penguasaan sangat baik dalam materi..." 
                                                       class="form-control note-input" 
                                                       style="height: 38px; border-radius: 8px; font-size: 0.85rem;">
                                            </td>
                                        </tr>
                                    @endforeach
                        </div>

                        <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
                            <button type="submit" class="btn btn-primary" style="background: #0284c7; font-weight: 800; padding: 12px 28px; border-radius: 12px; font-size: 0.95rem; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);">
                                <i class="fas fa-save"></i> Simpan Nilai {{ $selectedSubject->name }}
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        @endif

        <!-- TAB 3: KEHADIRAN & CATATAN WALI -->
        @if($activeTab === 'attendance')
            <div class="card" style="padding: 24px; border-radius: 14px; margin-bottom: 24px;">
                <div style="margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 16px;">
                    <h4 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-clipboard-list" style="color: #0284c7;"></i> Rekap Presensi & Catatan Wali Kelas: {{ $assessmentInfo['name'] }}
                    </h4>
                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.85rem;">
                        Data kehadiran (Sakit, Izin, Alpa) dan pesan motivasi/evaluasi wali kelas yang akan tercetak pada lembar rapor resmi siswa.
                    </p>
                </div>

                <form action="{{ route('academic-reports.store-summaries') }}" method="POST">
                    @csrf
                    <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
                    <input type="hidden" name="class" value="{{ $selectedClass }}">
                    <input type="hidden" name="assessment_type" value="{{ $selectedAssessment }}">

                    <div class="table-responsive">
                        <table class="table" style="width: 100%; border-collapse: collapse; margin: 0;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 0.82rem; color: #475569;">
                                    <th style="padding: 10px; text-align: center; width: 45px;">No</th>
                                    <th style="padding: 10px; text-align: left; width: 90px;">NIS</th>
                                    <th style="padding: 10px; text-align: left; min-width: 180px;">Nama Siswa</th>
                                    <th style="padding: 10px; text-align: center; width: 85px;">Sakit (S)</th>
                                    <th style="padding: 10px; text-align: center; width: 85px;">Izin (I)</th>
                                    <th style="padding: 10px; text-align: center; width: 85px;">Alpa (A)</th>
                                    <th style="padding: 10px; text-align: left; min-width: 320px;">Catatan & Motivasi Wali Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $stu)
                                    @php $sm = $summariesMap[$stu->id] ?? null; @endphp
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px; text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                                        <td style="padding: 10px; color: #64748b; font-weight: 600;">{{ $stu->nis ?? '-' }}</td>
                                        <td style="padding: 10px;">
                                            <div style="font-weight: 700; color: #0f172a;">{{ $stu->name }}</div>
                                        </td>
                                        <td style="padding: 8px;">
                                            <input type="number" min="0" name="students[{{ $stu->id }}][sick]" value="{{ $sm?->sick_count ?? 0 }}" class="form-control" style="text-align: center; font-weight: 700; height: 38px; border-radius: 8px;">
                                        </td>
                                        <td style="padding: 8px;">
                                            <input type="number" min="0" name="students[{{ $stu->id }}][permission]" value="{{ $sm?->permission_count ?? 0 }}" class="form-control" style="text-align: center; font-weight: 700; height: 38px; border-radius: 8px;">
                                        </td>
                                        <td style="padding: 8px;">
                                            <input type="number" min="0" name="students[{{ $stu->id }}][unexcused]" value="{{ $sm?->unexcused_count ?? 0 }}" class="form-control" style="text-align: center; font-weight: 700; height: 38px; border-radius: 8px;">
                                        </td>
                                        <td style="padding: 8px;">
                                            <input type="text" name="students[{{ $stu->id }}][notes]" value="{{ $sm?->homeroom_notes ?? '' }}" placeholder="Pesan evaluasi & motivasi belajar ananda..." class="form-control" style="height: 38px; border-radius: 8px; font-size: 0.85rem;">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="background: #0284c7; font-weight: 800; padding: 12px 28px; border-radius: 12px; font-size: 0.95rem; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);">
                            <i class="fas fa-save"></i> Simpan Kehadiran & Catatan
                        </button>
                    </div>
                </form>
            </div>
        @endif

    @else
        <div class="card" style="padding: 40px; text-align: center; color: #64748b; border-radius: 14px;">
            <i class="fas fa-chalkboard-teacher" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px;"></i>
            <h4 style="margin: 0; color: #1e293b; font-weight: 800;">Silakan Pilih Kelas</h4>
            <p style="margin: 6px 0 0 0; font-size: 0.9rem;">Pilih kelas pada filter di atas untuk menampilkan dan mengelola data raport akademik.</p>
        </div>
    @endif

</div>

<!-- MODAL IMPORT NILAI EXCEL / CSV -->
<div id="importScoresModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; width: 92%; max-width: 520px; box-shadow: 0 25px 50px -12px rgba(15,23,42,0.25); overflow: hidden;">
        <div style="padding: 18px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-upload" style="color: #0284c7;"></i> Import Nilai Excel / CSV
            </h4>
            <button type="button" onclick="closeImportScoresModal()" style="border: none; background: none; font-size: 1.4rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('academic-reports.import-scores') }}" method="POST" enctype="multipart/form-data" style="padding: 24px;">
            @csrf
            <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
            <input type="hidden" name="class" value="{{ $selectedClass }}">
            <input type="hidden" name="assessment_type" value="{{ $selectedAssessment }}">
            <input type="hidden" name="subject_id" value="{{ $selectedSubject?->id }}">

            <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 10px; padding: 12px 16px; margin-bottom: 18px; font-size: 0.85rem; color: #0369a1;">
                <div>Target Mapel: <b>{{ $selectedSubject?->name }}</b></div>
                <div>Kelas: <b>{{ $selectedClass }}</b> | Periode: <b>{{ $assessmentInfo['short'] }}</b></div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                    Pilih File Excel / CSV Hasil Rekap Nilai:
                </label>
                <input type="file" name="file" accept=".csv,.txt" required class="form-control" style="padding: 8px; height: auto;">
                <small style="display: block; margin-top: 6px; color: #64748b; font-size: 0.78rem;">
                    Gunakan file yang diunduh dari tombol <b>Format Excel / CSV</b> untuk format kolom yang sesuai.
                </small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" onclick="closeImportScoresModal()" class="btn btn-secondary" style="border-radius: 8px; font-weight: 700;">Batal</button>
                <button type="submit" class="btn btn-primary" style="background: #0284c7; border-radius: 8px; font-weight: 800;">
                    <i class="fas fa-upload"></i> Unggah & Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openImportScoresModal() {
    document.getElementById('importScoresModal').style.display = 'flex';
}

function closeImportScoresModal() {
    document.getElementById('importScoresModal').style.display = 'none';
}

function autoGenerateNotes(subjectName) {
    if (!confirm('Apakah Anda ingin mengisi otomatis catatan capaian kompetensi untuk seluruh siswa yang memiliki nilai berdasarkan kriteria Kurikulum Merdeka?')) {
        return;
    }

    var scoreInputs = document.querySelectorAll('.score-input');
    var count = 0;

    scoreInputs.forEach(function(input) {
        var stuId = input.getAttribute('data-student-id');
        var noteInput = document.getElementById('note_' + stuId);
        if (!noteInput) return;

        var val = parseFloat(input.value);
        if (isNaN(val) || val === null || input.value.trim() === '') return;

        var note = '';
        if (val >= 90) {
            note = 'Menunjukkan pemahaman yang sangat istimewa dalam menguasai seluruh materi dan capaian pembelajaran ' + subjectName + '.';
        } else if (val >= 80) {
            note = 'Menunjukkan penguasaan yang baik dalam mencapai tujuan pembelajaran utama ' + subjectName + '.';
        } else if (val >= 75) {
            note = 'Menguasai capaian pembelajaran ' + subjectName + ' dengan cukup baik, perlu pendampingan pengayaan materi.';
        } else {
            note = 'Perlu bimbingan dan pendampingan lebih intensif dalam menguasai kompetensi dasar ' + subjectName + '.';
        }

        noteInput.value = note;
        count++;
    });

    alert('Berhasil membuat deskripsi capaian pembelajaran otomatis untuk ' + count + ' siswa!');
}

function updatePredicate(input, studentId) {
    var val = parseFloat(input.value);
    var select = document.getElementById('predicate_' + studentId);
    if (!select || isNaN(val)) return;

    if (val >= 90) {
        select.value = 'A';
    } else if (val >= 80) {
        select.value = 'B';
    } else if (val >= 70) {
        select.value = 'C';
    } else {
        select.value = 'D';
    }
}
</script>
@endsection
