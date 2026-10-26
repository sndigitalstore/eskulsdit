@extends('layouts.app')

@section('title', 'Dashboard Data Siswa - Tata Usaha')
@section('page-title', 'Dashboard Tata Usaha (TU)')

@push('styles')
<style>
    .tu-hero {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);
        color: white;
        padding: 30px 32px;
        border-radius: 20px;
        box-shadow: 0 10px 25px -5px rgba(6, 78, 59, 0.3);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    .tu-hero::after {
        content: '\f501';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: 20px;
        bottom: -20px;
        font-size: 8.5rem;
        color: rgba(255, 255, 255, 0.08);
        pointer-events: none;
    }
    .tu-quick-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }
    .tu-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        color: white;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .tu-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.14);
        color: white;
    }
    .btn-tu-primary   { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .btn-tu-info      { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .btn-tu-purple    { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .btn-tu-amber     { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .btn-tu-dark      { background: linear-gradient(135deg, #475569 0%, #334155 100%); }

    /* KPI STAT CARDS */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }
    .kpi-card {
        background: white;
        border-radius: 16px;
        padding: 22px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    }
    .kpi-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .kpi-icon-wrap {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .kpi-value {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 4px;
    }
    .kpi-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
    }
    .kpi-footer {
        margin-top: 12px;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
        font-size: 0.78rem;
        color: #64748b;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* GRADE GRID */
    .grade-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 14px;
        margin-bottom: 25px;
    }
    .grade-card {
        background: white;
        border-radius: 14px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        transition: border-color 0.2s;
    }
    .grade-card:hover {
        border-color: #10b981;
    }
    .grade-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .grade-count {
        font-size: 1.5rem;
        font-weight: 800;
        color: #065f46;
    }
    .grade-details {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 6px;
        display: flex;
        gap: 10px;
    }

    /* RECENT STUDENTS TABLE */
    .student-row-actions {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .btn-mini {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        color: white;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: transform 0.15s, opacity 0.15s;
    }
    .btn-mini:hover {
        transform: scale(1.08);
        opacity: 0.9;
        color: white;
    }

    @media(max-width: 768px) {
        .tu-hero { flex-direction: column; text-align: left; gap: 15px; }
        .tu-hero::after { display: none; }
    }
</style>
@endpush

@section('content')

{{-- HERO BANNER --}}
<div class="tu-hero">
    <div style="z-index: 2;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
            <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                <i class="fas fa-school"></i> Tata Usaha (TU)
            </span>
            <span style="background: #ecfdf5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800;">
                Tahun Ajaran {{ $activeYear ? $activeYear->name : '-' }}
            </span>
        </div>
        <h2 style="font-size: 1.85rem; font-weight: 800; margin: 0 0 6px; letter-spacing: -0.5px;">
            Dashboard Fokus Data Siswa
        </h2>
        <p style="margin: 0; color: #d1fae5; font-size: 0.95rem;">
            Pusat administrasi, rekapitulasi biodata, dan penerbitan portofolio seluruh siswa SDIT AN NADZIR.
        </p>
    </div>
    <div style="z-index: 2; text-align: right;">
        <div style="font-size: 2rem; font-weight: 800; color: #a7f3d0; font-family: monospace;">
            {{ date('H:i') }} <span style="font-size: 0.9rem; font-family: sans-serif;">WIB</span>
        </div>
        <div style="font-size: 0.85rem; color: #d1fae5; font-weight: 500;">
            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
        </div>
    </div>
</div>

{{-- QUICK ACTION BAR --}}
<div class="tu-quick-actions">
    <a href="{{ route('students.create') }}" class="tu-action-btn btn-tu-primary">
        <i class="fas fa-user-plus"></i> + Tambah Siswa Baru
    </a>
    <a href="{{ route('students.index') }}" class="tu-action-btn btn-tu-info">
        <i class="fas fa-users"></i> Kelola Semua Siswa
    </a>
    <a href="{{ route('class-management.index') }}" class="tu-action-btn btn-tu-amber">
        <i class="fas fa-layer-group"></i> Manajemen & Ploting Kelas
    </a>
    <a href="{{ route('students.backup') }}" class="tu-action-btn btn-tu-dark">
        <i class="fas fa-file-excel"></i> Backup / Export Data Excel
    </a>
</div>

{{-- KPI STAT CARDS --}}
<div class="kpi-grid">
    {{-- Total Siswa Aktif --}}
    <div class="kpi-card">
        <div class="kpi-card-top">
            <div>
                <div class="kpi-value">{{ $totalStudents }}</div>
                <div class="kpi-label">Total Siswa Aktif</div>
            </div>
            <div class="kpi-icon-wrap" style="background: #ecfdf5; color: #059669;">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <div class="kpi-footer">
            <span>Alumni / Lulus: <strong>{{ $totalAlumni }}</strong></span>
            <a href="{{ route('students.index', ['status' => 'active']) }}" style="color: #059669; font-weight: 600; text-decoration: none;">Lihat &rarr;</a>
        </div>
    </div>

    {{-- Gender Breakdown --}}
    <div class="kpi-card">
        <div class="kpi-card-top">
            <div>
                <div class="kpi-value" style="font-size: 1.65rem;">
                    <span style="color: #0284c7;">{{ $maleStudents }} L</span>
                    <span style="color: #cbd5e1; font-weight: 300;">/</span>
                    <span style="color: #db2777;">{{ $femaleStudents }} P</span>
                </div>
                <div class="kpi-label">Rasio Jenis Kelamin</div>
            </div>
            <div class="kpi-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                <i class="fas fa-venus-mars"></i>
            </div>
        </div>
        <div class="kpi-footer">
            <span>{{ $otherGender > 0 ? $otherGender . ' belum ditentukan' : 'Semua gender terdata' }}</span>
            <span style="color: #64748b;">
                {{ $totalStudents > 0 ? round(($maleStudents / $totalStudents) * 100) . '% L' : '0%' }}
            </span>
        </div>
    </div>

    {{-- Program Sekolah --}}
    <div class="kpi-card">
        <div class="kpi-card-top">
            <div>
                <div class="kpi-value" style="font-size: 1.65rem;">
                    <span style="color: #10b981;">{{ $fullDayStudents }}</span>
                    <span style="color: #cbd5e1; font-weight: 300;">/</span>
                    <span style="color: #8b5cf6;">{{ $boardingStudents }}</span>
                </div>
                <div class="kpi-label">Full Day / Boarding</div>
            </div>
            <div class="kpi-icon-wrap" style="background: #ede9fe; color: #7c3aed;">
                <i class="fas fa-building"></i>
            </div>
        </div>
        <div class="kpi-footer">
            <span>Full Day: <strong>{{ $fullDayStudents }}</strong> • Boarding: <strong>{{ $boardingStudents }}</strong></span>
        </div>
    </div>

    {{-- Kelengkapan NISN --}}
    <div class="kpi-card">
        <div class="kpi-card-top">
            <div>
                <div class="kpi-value" style="color: {{ $missingNisn > 0 ? '#ea580c' : '#059669' }};">
                    {{ $completeNisn }}
                    <span style="font-size: 0.95rem; font-weight: 600; color: #94a3b8;">/ {{ $totalStudents }}</span>
                </div>
                <div class="kpi-label">Kelengkapan NISN</div>
            </div>
            <div class="kpi-icon-wrap" style="background: {{ $missingNisn > 0 ? '#fff7ed' : '#ecfdf5' }}; color: {{ $missingNisn > 0 ? '#ea580c' : '#059669' }};">
                <i class="fas fa-id-card"></i>
            </div>
        </div>
        <div class="kpi-footer">
            @if($missingNisn > 0)
                <span style="color: #ea580c; font-weight: 600;"><i class="fas fa-exclamation-triangle"></i> {{ $missingNisn }} belum ada NISN</span>
            @else
                <span style="color: #059669; font-weight: 600;"><i class="fas fa-check-circle"></i> 100% NISN Lengkap</span>
            @endif
            <span>Data Ortu: <strong>{{ $withParentData }}</strong></span>
        </div>
    </div>
</div>

{{-- BREAKDOWN SISWA PER TINGKAT KELAS --}}
<div class="card" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
        <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-chart-pie" style="color: #10b981;"></i> Distribusi Siswa per Tingkat Kelas
        </h3>
        <span style="font-size: 0.8rem; color: #64748b;">
            Tahun Ajaran: <strong>{{ $activeYear ? $activeYear->name : '-' }}</strong>
        </span>
    </div>

    @if(empty($gradeStatistics))
        <div style="text-align: center; padding: 2rem; color: #94a3b8;">
            Belum ada data rombel kelas pada tahun ajaran ini.
        </div>
    @else
        <div class="grade-grid">
            @foreach($gradeStatistics as $gradeKey => $gData)
            <div class="grade-card">
                <div class="grade-title">
                    <span>Kelas {{ $gradeKey }}</span>
                    <span style="font-size: 0.72rem; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 10px; font-weight: 700;">
                        {{ count($gData['classes']) }} Rombel
                    </span>
                </div>
                <div class="grade-count">{{ $gData['total'] }} <span style="font-size: 0.8rem; font-weight: 500; color: #64748b;">siswa</span></div>
                <div class="grade-details">
                    <span style="color: #0284c7;"><i class="fas fa-male"></i> {{ $gData['male'] }}</span>
                    <span style="color: #db2777;"><i class="fas fa-female"></i> {{ $gData['female'] }}</span>
                </div>
                <div style="margin-top: 8px; display: flex; flex-wrap: wrap; gap: 4px;">
                    @foreach($gData['classes'] as $cls)
                        <span style="font-size: 0.7rem; background: #f1f5f9; color: #334155; padding: 2px 6px; border-radius: 4px; font-weight: 600;" title="{{ $cls['count'] }} siswa ({{ $cls['male'] }}L, {{ $cls['female'] }}P)">
                            {{ $cls['name'] }} ({{ $cls['count'] }})
                        </span>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- DATA SISWA TERBARU & PENCARIAN CEPAT DENGAN CETAK PORTOFOLIO --}}
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0 0 4px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-id-card-alt" style="color: #8b5cf6;"></i> Data Siswa & Cetak Portofolio
            </h3>
            <p style="margin: 0; font-size: 0.82rem; color: #64748b;">
                Klik tombol <span style="background: #8b5cf6; color: white; padding: 1px 6px; border-radius: 4px; font-size: 0.75rem;"><i class="fas fa-id-card-alt"></i> Portofolio</span> untuk mencetak dokumen portofolio siap cetak masing-masing anak.
            </p>
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <input type="text" id="quickSearchInput" placeholder="Cari nama, NIS, kelas..." style="padding: 8px 14px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem; width: 220px;" onkeyup="filterQuickTable()">
            <a href="{{ route('students.index') }}" class="btn-filter" style="background: #065f46; color: white; padding: 8px 14px; font-size: 0.85rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
                Lihat Semua Siswa &rarr;
            </a>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;" id="tuStudentsTable">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                    <th style="padding: 10px 12px; width: 45px;">No</th>
                    <th style="padding: 10px 12px;">Nama Siswa</th>
                    <th style="padding: 10px 12px;">NIS / NISN</th>
                    <th style="padding: 10px 12px;">Kelas</th>
                    <th style="padding: 10px 12px;">Gender</th>
                    <th style="padding: 10px 12px;">Program</th>
                    <th style="padding: 10px 12px;">Kontak Ortu</th>
                    <th style="padding: 10px 12px; text-align: center; width: 170px;">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentStudents as $idx => $s)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background='white'">
                    <td style="padding: 10px 12px; color: #64748b;">{{ $idx + 1 }}</td>
                    <td style="padding: 10px 12px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 34px; height: 34px; border-radius: 50%; background: #e2e8f0; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.85rem; color: #64748b; font-weight: 700;">
                                @if($s->photo)
                                    <img src="{{ asset('storage/' . $s->photo) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    {{ strtoupper(substr($s->name, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <strong style="color: #1e293b; display: block;">{{ $s->name }}</strong>
                                <span style="font-size: 0.75rem; color: #94a3b8;">
                                    {{ $s->birth_place_date ?: 'TTL belum diisi' }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 10px 12px;">
                        <span style="font-weight: 600; color: #334155;">{{ $s->nis }}</span>
                        @if($s->nisn)
                            <div style="font-size: 0.75rem; color: #64748b;">NISN: {{ $s->nisn }}</div>
                        @else
                            <div style="font-size: 0.72rem; color: #f59e0b;">(NISN -)</div>
                        @endif
                    </td>
                    <td style="padding: 10px 12px;">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: 0.8rem;">
                            {{ $s->class }}
                        </span>
                    </td>
                    <td style="padding: 10px 12px;">
                        @if($s->gender == 'L')
                            <span style="color: #0284c7; font-weight: 700;">L</span>
                        @elseif($s->gender == 'P')
                            <span style="color: #db2777; font-weight: 700;">P</span>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                    <td style="padding: 10px 12px;">
                        <span style="font-size: 0.78rem; font-weight: 600; color: {{ $s->school_program == 'Boarding' ? '#7c3aed' : '#059669' }};">
                            {{ $s->school_program ?: 'Reguler' }}
                        </span>
                    </td>
                    <td style="padding: 10px 12px;">
                        @php
                            $phone = $s->father_phone ?: ($s->mother_phone ?: $s->parent_phone);
                        @endphp
                        @if($phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}" target="_blank" style="color: #16a34a; text-decoration: none; font-size: 0.82rem; font-weight: 600;">
                                <i class="fab fa-whatsapp"></i> {{ $phone }}
                            </a>
                        @else
                            <span style="color: #cbd5e1; font-size: 0.8rem;">-</span>
                        @endif
                    </td>
                    <td style="padding: 10px 12px; text-align: center;">
                        <div class="student-row-actions" style="justify-content: center;">
                            {{-- Cetak Portofolio --}}
                            <a href="{{ route('students.portfolio', $s->id) }}" target="_blank" class="btn-mini" style="background: #8b5cf6;" title="Cetak Portofolio Lengkap Siap Cetak">
                                <i class="fas fa-id-card-alt"></i>
                            </a>
                            {{-- Cetak Kartu --}}
                            <a href="{{ route('students.card', $s->id) }}" target="_blank" class="btn-mini" style="background: #0284c7;" title="Cetak Kartu Siswa">
                                <i class="fas fa-id-card"></i>
                            </a>
                            {{-- Lihat Biodata --}}
                            <a href="{{ route('students.show', $s->id) }}" class="btn-mini" style="background: #64748b;" title="Lihat Detail Biodata">
                                <i class="fas fa-eye"></i>
                            </a>
                            {{-- Edit --}}
                            <a href="{{ route('students.edit', $s->id) }}" class="btn-mini" style="background: #f59e0b;" title="Edit Data Siswa">
                                <i class="fas fa-pen"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 2rem; color: #94a3b8;">
                        Belum ada data siswa terdaftar di tahun ajaran ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function filterQuickTable() {
    var input = document.getElementById("quickSearchInput");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("tuStudentsTable");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
        var tdName = tr[i].getElementsByTagName("td")[1];
        var tdNis = tr[i].getElementsByTagName("td")[2];
        var tdClass = tr[i].getElementsByTagName("td")[3];
        if (tdName || tdNis || tdClass) {
            var txtValue = (tdName ? tdName.textContent : "") + " " +
                           (tdNis ? tdNis.textContent : "") + " " +
                           (tdClass ? tdClass.textContent : "");
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>
@endpush

@endsection
