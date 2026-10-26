<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio Siswa - {{ $student->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Outfit', 'Arial', sans-serif;
            background: #f0f4f8;
            color: #1e293b;
            padding: 20px;
            font-size: 13px;
        }

        /* ACTION BAR */
        .action-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn-print { background: #2563eb; color: #fff; box-shadow: 0 4px 14px rgba(37,99,235,.3); }
        .btn-back  { background: #fff; color: #475569; box-shadow: 0 4px 14px rgba(0,0,0,.07); }

        /* PORTFOLIO PAPER */
        .portfolio {
            max-width: 820px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,.12);
        }

        /* HEADER BANNER */
        .pf-header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 60%, #0ea5e9 100%);
            color: #fff;
            padding: 30px 36px 24px;
            display: flex;
            align-items: center;
            gap: 24px;
            position: relative;
            overflow: hidden;
        }
        .pf-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
        }
        .pf-header::after {
            content: '';
            position: absolute;
            bottom: -60px; right: 80px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
        }
        .pf-photo-wrap {
            flex-shrink: 0;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,.5);
            overflow: hidden;
            background: rgba(255,255,255,.15);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pf-photo-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .pf-photo-wrap .pf-photo-icon { font-size: 2.5rem; color: rgba(255,255,255,.6); }
        .pf-header-info { flex: 1; }
        .pf-school-name {
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            opacity: .85;
            margin-bottom: 4px;
        }
        .pf-student-name {
            font-size: 1.6rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 6px;
        }
        .pf-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        .pf-badge {
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 50px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .pf-badge.active { background: rgba(16,185,129,.25); border-color: rgba(16,185,129,.4); }
        .pf-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            opacity: .7;
            margin-bottom: 2px;
        }

        /* BODY */
        .pf-body { padding: 28px 36px; }

        /* SECTION */
        .pf-section {
            margin-bottom: 24px;
        }
        .pf-section-title {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748b;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .pf-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
        .pf-section-title i { color: #3b82f6; font-size: 0.85rem; }

        /* 2-COL GRID */
        .pf-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 20px;
        }
        .pf-field { margin-bottom: 4px; }
        .pf-field-label {
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .8px;
        }
        .pf-field-value {
            font-size: 0.88rem;
            color: #1e293b;
            font-weight: 500;
            padding: 4px 0;
            border-bottom: 1px dashed #f1f5f9;
        }
        .pf-field-value.empty { color: #cbd5e1; font-style: italic; }

        /* STAT BOXES */
        .pf-stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }
        .pf-stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 8px;
            text-align: center;
        }
        .pf-stat-box .stat-val {
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1;
        }
        .pf-stat-box .stat-lbl {
            font-size: 0.68rem;
            color: #64748b;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: .8px;
        }
        .color-green { color: #10b981; }
        .color-yellow { color: #f59e0b; }
        .color-blue { color: #3b82f6; }
        .color-red { color: #ef4444; }
        .color-gray { color: #64748b; }

        /* TABLE */
        .pf-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }
        .pf-table th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 700;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: .8px;
            padding: 8px 12px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }
        .pf-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .pf-table tr:last-child td { border-bottom: none; }
        .pf-table tr:hover td { background: #fafbfc; }

        /* ESKUL TAGS */
        .eskul-tags { display: flex; flex-wrap: wrap; gap: 8px; }
        .eskul-tag {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 50px;
            padding: 5px 14px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* ACHIEVEMENT TAG */
        .ach-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
            background: #fefce8;
            border-left: 3px solid #f59e0b;
            margin-bottom: 6px;
        }
        .ach-row i { color: #f59e0b; }
        .ach-name { font-weight: 600; font-size: 0.85rem; flex: 1; }
        .ach-date { font-size: 0.75rem; color: #94a3b8; }

        /* CHARACTER */
        .char-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 7px 10px;
            border-radius: 8px;
            margin-bottom: 5px;
        }
        .char-row.case { background: #fff1f2; border-left: 3px solid #ef4444; }
        .char-row.apprec { background: #f0fdf4; border-left: 3px solid #22c55e; }

        /* MUNAQOSAH */
        .munaqa-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .munaqa-badge.lulus { background: #dcfce7; color: #166534; }
        .munaqa-badge.pending { background: #fef9c3; color: #854d0e; }
        .munaqa-badge.fail { background: #fee2e2; color: #991b1b; }

        /* FOOTER */
        .pf-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 18px 36px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.78rem;
            color: #94a3b8;
        }
        .pf-footer strong { color: #475569; }

        /* SIGNATURE */
        .pf-signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: 24px 36px 0;
        }
        .pf-sig { text-align: center; }
        .pf-sig-line {
            margin-top: 60px;
            border-top: 1px solid #94a3b8;
            padding-top: 6px;
            font-size: 0.8rem;
            color: #475569;
            font-weight: 600;
        }

        /* PRINT */
        @media print {
            body { background: #fff; padding: 0; }
            .action-bar { display: none; }
            .portfolio { box-shadow: none; border-radius: 0; max-width: 100%; }
            .pf-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .pf-stat-box, .ach-row, .char-row { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .pf-section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

{{-- ACTION BAR --}}
<div class="action-bar">
    <a href="{{ route('students.index') }}" class="btn btn-back">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <button onclick="window.print()" class="btn btn-print">
        <i class="fas fa-print"></i> Cetak Portofolio
    </button>
</div>

<div class="portfolio">

    {{-- HEADER --}}
    <div class="pf-header">
        <div class="pf-photo-wrap">
            @if($student->photo)
                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
            @else
                <i class="fas fa-user pf-photo-icon"></i>
            @endif
        </div>
        <div class="pf-header-info">
            <div class="pf-school-name">
                {{ \App\Models\Setting::get('school_name', 'SDIT AN NADZIR') }}
            </div>
            <div class="pf-student-name">{{ $student->name }}</div>
            <div class="pf-badges">
                <div class="pf-badge">
                    <i class="fas fa-chalkboard-teacher"></i>
                    Kelas {{ $student->class }}
                </div>
                @if($student->nis)
                <div class="pf-badge">
                    <i class="fas fa-id-badge"></i>
                    NIS {{ $student->nis }}
                </div>
                @endif
                @if($student->nisn)
                <div class="pf-badge">
                    <i class="fas fa-fingerprint"></i>
                    NISN {{ $student->nisn }}
                </div>
                @endif
                @if($activeYear)
                <div class="pf-badge">
                    <i class="fas fa-calendar-alt"></i>
                    TA {{ $activeYear->name }}
                </div>
                @endif
                <div class="pf-badge {{ $student->status == 'active' ? 'active' : '' }}">
                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                    {{ strtoupper($student->status ?? 'Aktif') }}
                </div>
            </div>
        </div>
    </div>

    <div class="pf-body">

        {{-- IDENTITAS DIRI --}}
        <div class="pf-section">
            <div class="pf-section-title">
                <i class="fas fa-user-circle"></i> Identitas Diri
            </div>
            <div class="pf-grid-2">
                <div class="pf-field">
                    <div class="pf-field-label">Jenis Kelamin</div>
                    <div class="pf-field-value {{ empty($student->gender) ? 'empty' : '' }}">
                        {{ $student->gender == 'L' ? 'Laki-laki' : ($student->gender == 'P' ? 'Perempuan' : '—') }}
                    </div>
                </div>
                <div class="pf-field">
                    <div class="pf-field-label">Tempat, Tanggal Lahir</div>
                    <div class="pf-field-value {{ empty($student->birth_place_date) ? 'empty' : '' }}">
                        {{ $student->birth_place_date ?: '—' }}
                    </div>
                </div>
                <div class="pf-field" style="grid-column: span 2;">
                    <div class="pf-field-label">Alamat</div>
                    <div class="pf-field-value {{ empty($student->address) ? 'empty' : '' }}">
                        {{ $student->address ?: '—' }}
                    </div>
                </div>
                <div class="pf-field">
                    <div class="pf-field-label">Asal Sekolah (TK/PAUD)</div>
                    <div class="pf-field-value {{ empty($student->previous_school) ? 'empty' : '' }}">
                        {{ $student->previous_school ?: '—' }}
                    </div>
                </div>
                <div class="pf-field">
                    <div class="pf-field-label">Tanggal Masuk</div>
                    <div class="pf-field-value {{ empty($student->admission_date) ? 'empty' : '' }}">
                        {{ $student->admission_date ?: '—' }}
                    </div>
                </div>
                <div class="pf-field">
                    <div class="pf-field-label">Program Sekolah</div>
                    <div class="pf-field-value {{ empty($student->school_program) ? 'empty' : '' }}">
                        {{ $student->school_program ?: '—' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- DATA ORANG TUA --}}
        <div class="pf-section">
            <div class="pf-section-title">
                <i class="fas fa-users"></i> Data Orang Tua / Wali
            </div>
            <div class="pf-grid-2">
                <div class="pf-field">
                    <div class="pf-field-label">Nama Ayah</div>
                    <div class="pf-field-value {{ empty($student->father_name) ? 'empty' : '' }}">
                        {{ $student->father_name ?: '—' }}
                    </div>
                </div>
                <div class="pf-field">
                    <div class="pf-field-label">Nama Ibu</div>
                    <div class="pf-field-value {{ empty($student->mother_name) ? 'empty' : '' }}">
                        {{ $student->mother_name ?: '—' }}
                    </div>
                </div>
                <div class="pf-field">
                    <div class="pf-field-label">Pekerjaan Ayah</div>
                    <div class="pf-field-value {{ empty($student->father_job) ? 'empty' : '' }}">
                        {{ $student->father_job ?: '—' }}
                    </div>
                </div>
                <div class="pf-field">
                    <div class="pf-field-label">Pekerjaan Ibu</div>
                    <div class="pf-field-value {{ empty($student->mother_job) ? 'empty' : '' }}">
                        {{ $student->mother_job ?: '—' }}
                    </div>
                </div>
                <div class="pf-field">
                    <div class="pf-field-label">No. HP Ayah</div>
                    <div class="pf-field-value {{ empty($student->father_phone) ? 'empty' : '' }}">
                        {{ $student->father_phone ?: '—' }}
                    </div>
                </div>
                <div class="pf-field">
                    <div class="pf-field-label">No. HP Ibu</div>
                    <div class="pf-field-value {{ empty($student->mother_phone) ? 'empty' : '' }}">
                        {{ $student->mother_phone ?: '—' }}
                    </div>
                </div>
                @if($student->parent_phone)
                <div class="pf-field" style="grid-column: span 2;">
                    <div class="pf-field-label">No. HP Wali Utama</div>
                    <div class="pf-field-value">{{ $student->parent_phone }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- KEHADIRAN ESKUL --}}
        @if($student->eskuls->count() > 0)
        <div class="pf-section">
            <div class="pf-section-title">
                <i class="fas fa-running"></i> Ekstrakurikuler
            </div>
            <div class="eskul-tags">
                @foreach($student->eskuls as $eskul)
                    <div class="eskul-tag">
                        <i class="fas fa-star" style="font-size: 0.7rem; color: #3b82f6;"></i>
                        {{ $eskul->name }}
                        @if($eskul->instructor_name)
                            <span style="color:#93c5fd; font-weight: 400;">&mdash; {{ $eskul->instructor_name }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- REKAP KEHADIRAN --}}
        <div class="pf-section">
            <div class="pf-section-title">
                <i class="fas fa-calendar-check"></i> Rekap Kehadiran Eskul (Tahun Ajaran Ini)
            </div>
            <div class="pf-stats">
                <div class="pf-stat-box">
                    <div class="stat-val color-gray">{{ $attendanceSummary['total'] }}</div>
                    <div class="stat-lbl">Total Sesi</div>
                </div>
                <div class="pf-stat-box">
                    <div class="stat-val color-green">{{ $attendanceSummary['hadir'] }}</div>
                    <div class="stat-lbl">Hadir</div>
                </div>
                <div class="pf-stat-box">
                    <div class="stat-val color-blue">{{ $attendanceSummary['izin'] }}</div>
                    <div class="stat-lbl">Izin</div>
                </div>
                <div class="pf-stat-box">
                    <div class="stat-val color-yellow">{{ $attendanceSummary['sakit'] }}</div>
                    <div class="stat-lbl">Sakit</div>
                </div>
                <div class="pf-stat-box">
                    <div class="stat-val color-red">{{ $attendanceSummary['alpha'] }}</div>
                    <div class="stat-lbl">Alpha</div>
                </div>
            </div>
            @if($attendanceSummary['total'] > 0)
                @php
                    $pct = round(($attendanceSummary['hadir'] / $attendanceSummary['total']) * 100);
                    $color = $pct >= 80 ? '#10b981' : ($pct >= 60 ? '#f59e0b' : '#ef4444');
                @endphp
                <div style="margin-top: 12px; display: flex; align-items: center; gap: 10px;">
                    <div style="flex: 1; background: #f1f5f9; border-radius: 50px; height: 8px; overflow: hidden;">
                        <div style="background: {{ $color }}; height: 100%; width: {{ $pct }}%; border-radius: 50px;"></div>
                    </div>
                    <div style="font-size: 0.82rem; font-weight: 700; color: {{ $color }}; min-width: 44px;">{{ $pct }}%</div>
                    <div style="font-size: 0.75rem; color: #94a3b8;">Kehadiran</div>
                </div>
            @endif
        </div>

        {{-- NILAI ESKUL --}}
        @if($grades->count() > 0)
        <div class="pf-section">
            <div class="pf-section-title">
                <i class="fas fa-star"></i> Nilai Ekstrakurikuler
            </div>
            <table class="pf-table">
                <thead>
                    <tr>
                        <th>Ekstrakurikuler</th>
                        <th style="text-align:center">Nilai SAS 1</th>
                        <th style="text-align:center">Nilai SAS 2</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $gradesByEskul = $grades->groupBy('eskul_id');
                    @endphp
                    @foreach($gradesByEskul as $eskulId => $eskulGrades)
                        @php
                            $eskulObj = $eskulGrades->first()->eskul;
                            $sas1 = $eskulGrades->where('type', 'sas1')->first();
                            $sas2 = $eskulGrades->where('type', 'sas2')->first();
                        @endphp
                        <tr>
                            <td>{{ $eskulObj ? $eskulObj->name : '-' }}</td>
                            <td style="text-align:center; font-weight: 600;">
                                @if($sas1)
                                    @php $s = json_decode($sas1->score, true); @endphp
                                    @if(is_array($s))
                                        {{ implode(' / ', array_values(array_filter($s))) }}
                                    @else
                                        {{ $sas1->score ?? '-' }}
                                    @endif
                                @else —
                                @endif
                            </td>
                            <td style="text-align:center; font-weight: 600;">
                                @if($sas2)
                                    @php $s = json_decode($sas2->score, true); @endphp
                                    @if(is_array($s))
                                        {{ implode(' / ', array_values(array_filter($s))) }}
                                    @else
                                        {{ $sas2->score ?? '-' }}
                                    @endif
                                @else —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- TAHFIDZ --}}
        @if($tahfidzGroup || count($tahfidzRecords))
        <div class="pf-section">
            <div class="pf-section-title">
                <i class="fas fa-book-open"></i> Tahfidz Al-Qur'an
            </div>
            @if($tahfidzGroup)
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 14px; margin-bottom: 12px; font-size: 0.85rem; color: #166534;">
                    <i class="fas fa-users"></i> Kelompok: <strong>{{ $tahfidzGroup->name }}</strong>
                    @if($tahfidzGroup->mentor_name) &mdash; Pembimbing: {{ $tahfidzGroup->mentor_name }} @endif
                </div>
            @endif
            @if(count($tahfidzRecords) > 0)
            <table class="pf-table">
                <thead>
                    <tr>
                        <th>Surah / Juz</th>
                        <th>Ayat</th>
                        <th>Nilai</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tahfidzRecords->take(10) as $rec)
                    <tr>
                        <td>{{ $rec->surah_name ? $rec->surah_name . ($rec->juz ? ' (Juz ' . $rec->juz . ')' : '') : ('Juz ' . ($rec->juz ?? '-')) }}</td>
                        <td>{{ $rec->start_ayah ?? '' }}{{ $rec->end_ayah ? ' – ' . $rec->end_ayah : '' }}</td>
                        <td><strong>{{ $rec->nilai ?? $rec->quality_label ?? '-' }}</strong></td>
                        <td>{{ $rec->session_date ? \Carbon\Carbon::parse($rec->session_date)->format('d/m/Y') : \Carbon\Carbon::parse($rec->created_at)->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if(count($tahfidzRecords) > 10)
                <div style="text-align: right; font-size: 0.75rem; color: #94a3b8; margin-top: 6px;">
                    Dan {{ count($tahfidzRecords) - 10 }} catatan lainnya...
                </div>
            @endif
            @else
                <div style="color: #94a3b8; font-size: 0.82rem; text-align: center; padding: 12px;">Belum ada catatan hafalan.</div>
            @endif
        </div>
        @endif

        {{-- TILAWATI --}}
        @if($tilawatiGroup)
        <div class="pf-section">
            <div class="pf-section-title">
                <i class="fas fa-quran"></i> Tilawati
            </div>
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 10px 14px; font-size: 0.85rem; color: #1d4ed8;">
                <i class="fas fa-users"></i> Kelompok: <strong>{{ $tilawatiGroup->name }}</strong>
                @if($tilawatiGroup->mentor_name) &mdash; Pembimbing: {{ $tilawatiGroup->mentor_name }} @endif
            </div>
        </div>
        @endif

        {{-- MUNAQOSAH --}}
        @if($munaqosahRegs->count() > 0)
        <div class="pf-section">
            <div class="pf-section-title">
                <i class="fas fa-graduation-cap"></i> Munaqosah Qur'an
            </div>
            <table class="pf-table">
                <thead>
                    <tr>
                        <th>Jenis</th>
                        <th>Jilid/Juz</th>
                        <th>Status</th>
                        <th>Nilai</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($munaqosahRegs as $reg)
                    <tr>
                        <td>{{ $reg->type == 'tilawati' ? 'Tilawati' : ($reg->type == 'tahfidz' ? 'Tahfidz' : ucfirst($reg->type ?? '-')) }}</td>
                        <td>{{ $reg->target_level ?? $reg->current_level ?? '-' }}</td>
                        <td>
                            @if($reg->status == 'lulus' || $reg->status == 'passed')
                                <span class="munaqa-badge lulus"><i class="fas fa-check-circle"></i> Lulus</span>
                            @elseif($reg->status == 'pending' || $reg->status == 'scheduled')
                                <span class="munaqa-badge pending"><i class="fas fa-clock"></i> Terjadwal</span>
                            @elseif($reg->status == 'failed')
                                <span class="munaqa-badge fail"><i class="fas fa-times-circle"></i> Belum Lulus</span>
                            @else
                                <span class="munaqa-badge pending">{{ $reg->status ?? '-' }}</span>
                            @endif
                        </td>
                        <td>{{ $reg->final_score !== null ? $reg->final_score : '-' }}</td>
                        <td>{{ $reg->created_at ? \Carbon\Carbon::parse($reg->created_at)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- PRESTASI --}}
        @if($achievements->count() > 0)
        <div class="pf-section">
            <div class="pf-section-title">
                <i class="fas fa-trophy"></i> Prestasi & Penghargaan
            </div>
            @foreach($achievements as $ach)
            <div class="ach-row">
                <i class="fas fa-medal"></i>
                <div class="ach-name">{{ $ach->name ?? '-' }}</div>
                <div class="ach-date">{{ $ach->date ? \Carbon\Carbon::parse($ach->date)->format('d M Y') : '' }}</div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- KARAKTER --}}
        @if($characterAppreciations->count() > 0 || $characterCases->count() > 0)
        <div class="pf-section">
            <div class="pf-section-title">
                <i class="fas fa-heart"></i> Catatan Karakter
            </div>
            @if($characterAppreciations->count() > 0)
                <div style="margin-bottom: 10px; font-size: 0.78rem; font-weight: 700; color: #16a34a; text-transform: uppercase; letter-spacing: .8px;">
                    <i class="fas fa-thumbs-up"></i> Apresiasi
                </div>
                @foreach($characterAppreciations as $apprec)
                <div class="char-row apprec">
                    <i class="fas fa-star" style="color: #22c55e;"></i>
                    <div style="flex:1; font-size: 0.82rem;">{{ $apprec->description ?? $apprec->title ?? '-' }}</div>
                    <div style="font-size: 0.75rem; color: #86efac;">{{ $apprec->date ? \Carbon\Carbon::parse($apprec->date)->format('d/m/Y') : '' }}</div>
                </div>
                @endforeach
            @endif
            @if($characterCases->count() > 0)
                <div style="margin-bottom: 10px; margin-top: 10px; font-size: 0.78rem; font-weight: 700; color: #dc2626; text-transform: uppercase; letter-spacing: .8px;">
                    <i class="fas fa-exclamation-triangle"></i> Catatan Kasus
                </div>
                @foreach($characterCases as $case)
                <div class="char-row case">
                    <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>
                    <div style="flex:1; font-size: 0.82rem;">{{ $case->description ?? $case->title ?? '-' }}</div>
                    <div style="font-size: 0.75rem; color: #fca5a5;">{{ $case->date ? \Carbon\Carbon::parse($case->date)->format('d/m/Y') : '' }}</div>
                </div>
                @endforeach
            @endif
        </div>
        @endif

        {{-- TANDA TANGAN --}}
        <div class="pf-signatures">
            <div class="pf-sig">
                <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 4px;">
                    {{ \App\Models\Setting::get('school_city', 'Serang') }}, {{ date('d F Y') }}
                </div>
                <div style="font-size: 0.78rem; color: #475569; margin-bottom: 4px;">Orang Tua / Wali</div>
                <div class="pf-sig-line">
                    {{ $student->father_name ?: ($student->mother_name ?: '( __________________ )') }}
                </div>
            </div>
            <div class="pf-sig">
                <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 4px;">&nbsp;</div>
                <div style="font-size: 0.78rem; color: #475569; margin-bottom: 4px;">Kepala Sekolah</div>
                <div class="pf-sig-line">
                    {{ \App\Models\Setting::get('headmaster_name', 'Kepala Sekolah') }}
                </div>
            </div>
        </div>

    </div>{{-- end pf-body --}}

    {{-- FOOTER --}}
    <div class="pf-footer">
        <span>
            <strong>{{ \App\Models\Setting::get('school_name', 'SDIT AN NADZIR') }}</strong>
            @php $addr = \App\Models\Setting::get('school_address', ''); @endphp
            @if($addr) &mdash; {{ $addr }} @endif
        </span>
        <span>Dicetak: {{ date('d F Y, H:i') }}</span>
    </div>

</div>{{-- end portfolio --}}

</body>
</html>
