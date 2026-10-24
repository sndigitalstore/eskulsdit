@extends('layouts.app')

@section('title', 'Kenaikan Kelas & Kelulusan')
@section('page-title', 'Kenaikan Kelas & Kelulusan')

@push('styles')
<style>
    .promotions-page {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    /* ===== TOPBAR CARD ===== */
    .promotions-header-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid #eef2f7;
    }

    .promotions-header-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .promotions-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(2, 132, 199, 0.25);
    }

    .promotions-header-text h2 {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.25;
    }

    .promotions-header-text p {
        font-size: 0.84rem;
        color: #64748b;
        margin: 3px 0 0 0;
    }

    /* ===== FILTER CARD ===== */
    .filter-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid #eef2f7;
    }

    .filter-form-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr auto;
        gap: 14px;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #334155;
    }

    .filter-input-wrap {
        position: relative;
    }

    .filter-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.88rem;
        pointer-events: none;
    }

    .filter-control {
        width: 100%;
        padding: 10px 14px 10px 38px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        font-size: 0.88rem;
        color: #1e293b;
        font-weight: 600;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .filter-control:focus {
        outline: none;
        border-color: #0284c7;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.12);
    }

    .btn-filter-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 22px;
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2);
        white-space: nowrap;
        height: 42px;
        box-sizing: border-box;
    }

    .btn-filter-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(2, 132, 199, 0.3);
    }

    .filter-hint {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ===== ACTION BAR CARD ===== */
    .promotion-action-bar {
        background: #ffffff;
        border-radius: 16px;
        padding: 16px 20px;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .action-bar-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .class-info-title {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
    }

    .selected-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .select-all-btn-label {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #475569;
        background: #f1f5f9;
        padding: 8px 14px;
        border-radius: 10px;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s;
    }

    .select-all-btn-label:hover {
        background: #e2e8f0;
    }

    .custom-check {
        width: 18px;
        height: 18px;
        accent-color: #0284c7;
        cursor: pointer;
    }

    .btn-promote-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 22px;
        border-radius: 10px;
        border: none;
        font-size: 0.88rem;
        font-weight: 800;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-promote-action.green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .btn-promote-action.blue {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    }

    .btn-promote-action.gray {
        background: #64748b;
    }

    .btn-promote-action:hover {
        transform: translateY(-1px);
        filter: brightness(1.05);
    }

    /* ===== DUAL VIEW: TABLE & CARDS ===== */
    .table-container {
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

    .promotions-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .promotions-table th {
        background: #f8fafc;
        padding: 13px 18px;
        font-size: 0.8rem;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .promotions-table td {
        padding: 14px 18px;
        font-size: 0.88rem;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .promotions-table tbody tr:hover {
        background: #f8fafc;
    }

    .promotions-table tbody tr.row-selected {
        background: #f0fdf4;
    }

    .status-badge-active {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.76rem;
        font-weight: 700;
    }

    /* ===== MOBILE VIEW (< 769px) ===== */
    .mobile-promotions-view {
        display: none;
    }

    @media (max-width: 768px) {
        .promotions-header-card {
            padding: 16px 14px;
            border-radius: 14px;
        }

        .promotions-header-icon {
            width: 38px;
            height: 38px;
            font-size: 1.05rem;
        }

        .promotions-header-text h2 {
            font-size: 1.12rem;
        }

        .promotions-header-text p {
            font-size: 0.78rem;
        }

        .filter-card {
            padding: 16px 14px;
            border-radius: 14px;
        }

        .filter-form-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .btn-filter-submit {
            width: 100%;
            height: 44px;
        }

        .promotion-action-bar {
            padding: 14px;
            border-radius: 14px;
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .action-bar-left {
            justify-content: space-between;
            width: 100%;
        }

        .btn-promote-action {
            width: 100%;
            padding: 13px 18px;
            font-size: 0.9rem;
        }

        /* Hide table on mobile, show cards */
        .desktop-table-view {
            display: none !important;
        }

        .mobile-promotions-view {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 12px;
            background: #f8fafc;
        }

        .student-promo-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }

        .student-promo-card.selected {
            background: #f0fdf4;
            border-color: #10b981;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.12);
        }

        .promo-card-check {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .promo-card-check input {
            width: 20px;
            height: 20px;
            accent-color: #0284c7;
        }

        .promo-card-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
            color: white;
            font-size: 0.92rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .promo-card-info {
            flex: 1;
            min-width: 0;
        }

        .promo-student-name {
            font-size: 0.92rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 3px 0;
            line-height: 1.3;
            word-break: break-word;
        }

        .promo-meta-row {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .promo-class-pill {
            font-size: 0.72rem;
            font-weight: 700;
            color: #0284c7;
            background: #e0f2fe;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .promo-card-status {
            flex-shrink: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="promotions-page">

    {{-- 1. Header Card --}}
    <div class="promotions-header-card">
        <div class="promotions-header-title">
            <div class="promotions-header-icon">
                <i class="fas fa-level-up-alt"></i>
            </div>
            <div class="promotions-header-text">
                <h2>Kenaikan Kelas & Kelulusan</h2>
                <p>Proses kenaikan jenjang kelas atau kelulusan siswa secara masal dan terintegrasi.</p>
            </div>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 14px 18px; border-radius: 12px; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.9rem;">
            <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.1rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- 2. Filter Card --}}
    <div class="filter-card">
        <form action="{{ route('promotions.index') }}" method="GET">
            <div class="filter-form-grid">
                <div class="filter-group">
                    <label class="filter-label">Tahun Ajaran Asal:</label>
                    <div class="filter-input-wrap">
                        <i class="fas fa-calendar-alt"></i>
                        <select name="source_academic_year_id" class="filter-control">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $sourceYearId == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Pilih Kelas Asal:</label>
                    <div class="filter-input-wrap">
                        <i class="fas fa-chalkboard"></i>
                        <input type="text" name="class" value="{{ $class }}" placeholder="Contoh: 1A, 5B, 6A" class="filter-control" required>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn-filter-submit">
                        <i class="fas fa-search"></i>
                        <span>Tampilkan Siswa</span>
                    </button>
                </div>
            </div>
            <div class="filter-hint">
                <i class="fas fa-info-circle" style="color: #0284c7;"></i>
                <span>Masukkan nama kelas asal persis sesuai data siswa (Contoh: <strong>1A</strong>, <strong>2B</strong>, <strong>6</strong>).</span>
            </div>
        </form>
    </div>

    {{-- 3. Results / Promotion Process --}}
    @if($class)
        @if(count($students) > 0)
            @php
                preg_match('/^(\d+)/', $class, $matches);
                $level = isset($matches[1]) ? (int)$matches[1] : 0;
                $actionText = 'PROSES SISWA';
                $confirmColor = '#0284c7';
                $btnColorClass = 'blue';

                if ($level == 6) {
                    $actionText = 'LULUSKAN SISWA DIPILIH';
                    $confirmColor = '#10b981';
                    $btnColorClass = 'green';
                    $confirmTitle = 'Luluskan Siswa?';
                    $confirmText = 'Siswa yang dipilih akan ditandai LULUS dan dikeluarkan dari daftar aktif.';
                } elseif ($level > 0 && $level < 6) {
                    $nextClass = ($level + 1) . substr($class, strlen((string)$level));
                    $actionText = 'NAIKKAN KE KELAS ' . $nextClass;
                    $confirmColor = '#0284c7';
                    $btnColorClass = 'blue';
                    $confirmTitle = 'Naikkan Kelas?';
                    $confirmText = 'Siswa yang dipilih akan dipindahkan ke kelas ' . $nextClass . '.';
                } else {
                    $confirmTitle = 'Proses Siswa?';
                    $confirmText = 'Apakah Anda yakin ingin memproses siswa ini?';
                    $btnColorClass = 'gray';
                }
            @endphp

            <form id="promotionForm" action="{{ route('promotions.promote') }}" method="POST">
                @csrf
                <input type="hidden" name="class_from" value="{{ $class }}">
                <input type="hidden" name="source_academic_year_id" value="{{ $sourceYearId }}">

                {{-- Action Bar --}}
                <div class="promotion-action-bar">
                    <div class="action-bar-left">
                        <div class="class-info-title">
                            Kelas {{ $class }} <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">({{ count($students) }} siswa)</span>
                        </div>
                        <span class="selected-badge-pill" id="selectedBadge">
                            <i class="fas fa-check"></i> <span id="selectedCount">0</span> dipilih
                        </span>
                        <label class="select-all-btn-label">
                            <input type="checkbox" id="selectAllMaster" class="custom-check" onchange="toggleSelectAll(this.checked)">
                            <span>Pilih Semua</span>
                        </label>
                    </div>

                    <div>
                        @if(Auth::user()->role == 'admin')
                            <button type="button" onclick="confirmPromotion('{{ $confirmTitle }}', '{{ $confirmText }}', '{{ $confirmColor }}')" class="btn-promote-action {{ $btnColorClass }}">
                                @if($level == 6)
                                    <i class="fas fa-graduation-cap"></i>
                                @else
                                    <i class="fas fa-level-up-alt"></i>
                                @endif
                                <span>{{ $actionText }}</span>
                            </button>
                        @else
                            <div style="background: #fffbeb; color: #92400e; padding: 8px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; border: 1px solid #fde68a;">
                                <i class="fas fa-lock"></i> Hanya Kesiswaan/Admin yang dapat memproses kenaikan kelas.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Container for Table and Mobile Cards --}}
                <div class="table-container" style="margin-top: 14px;">
                    {{-- 1. Desktop Table View (>= 769px) --}}
                    <div class="desktop-table-view">
                        <table class="promotions-table">
                            <thead>
                                <tr>
                                    <th width="48" style="text-align: center;">
                                        <input type="checkbox" id="selectAllTable" class="custom-check" onchange="toggleSelectAll(this.checked)">
                                    </th>
                                    <th width="50" style="text-align: center;">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas Saat Ini</th>
                                    <th style="text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $idx => $student)
                                <tr id="row-{{ $student->id }}">
                                    <td style="text-align: center;">
                                        <input type="checkbox" name="ids[]" value="{{ $student->id }}" class="student-checkbox custom-check" onchange="updateSelectedCount()">
                                    </td>
                                    <td style="text-align: center; color: #94a3b8; font-weight: 700;">{{ $idx + 1 }}</td>
                                    <td style="font-weight: 700; color: #0f172a;">{{ $student->name }}</td>
                                    <td>
                                        <span class="promo-class-pill">Kelas {{ $student->class }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="status-badge-active"><i class="fas fa-check-circle"></i> Aktif</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- 2. Mobile Card View (< 769px) --}}
                    <div class="mobile-promotions-view">
                        @foreach($students as $idx => $student)
                        <div class="student-promo-card" id="card-{{ $student->id }}" onclick="toggleStudentCard('{{ $student->id }}', event)">
                            <div class="promo-card-check">
                                <input type="checkbox" name="ids[]" value="{{ $student->id }}" class="student-checkbox custom-check" id="check-{{ $student->id }}" onchange="onCardCheckChange('{{ $student->id }}')">
                            </div>
                            <div class="promo-card-avatar">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                            <div class="promo-card-info">
                                <h4 class="promo-student-name">{{ $student->name }}</h4>
                                <div class="promo-meta-row">
                                    <span class="promo-class-pill">Kelas {{ $student->class }}</span>
                                    <span style="font-size: 0.72rem; color: #94a3b8; font-weight: 600;">#{{ $idx + 1 }}</span>
                                </div>
                            </div>
                            <div class="promo-card-status">
                                <span class="status-badge-active"><i class="fas fa-check-circle"></i></span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </form>
        @else
            <div style="text-align: center; padding: 48px 20px; background: white; border-radius: 16px; border: 1px solid #eef2f7; box-shadow: 0 2px 12px rgba(0,0,0,0.04);">
                <i class="fas fa-user-slash" style="font-size: 2.8rem; margin-bottom: 14px; color: #cbd5e1;"></i>
                <h4 style="font-size: 1.05rem; font-weight: 700; color: #334155; margin-bottom: 4px;">Tidak Ada Siswa Aktif</h4>
                <p style="font-size: 0.86rem; color: #64748b;">Tidak ditemukan siswa aktif di kelas <strong>{{ $class }}</strong> pada tahun ajaran yang dipilih.</p>
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 48px 20px; background: #ffffff; border-radius: 16px; border: 2px dashed #cbd5e1;">
            <i class="fas fa-users" style="font-size: 2.8rem; margin-bottom: 14px; color: #0284c7;"></i>
            <h4 style="font-size: 1.05rem; font-weight: 700; color: #334155; margin-bottom: 4px;">Siap Memproses Kenaikan / Kelulusan</h4>
            <p style="font-size: 0.86rem; color: #64748b;">Pilih Tahun Ajaran dan masukkan nama kelas asal di atas untuk menampilkan daftar siswa.</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.student-checkbox:checked');
        const total = document.querySelectorAll('.student-checkbox');
        const countSpan = document.getElementById('selectedCount');
        if (countSpan) countSpan.textContent = checked.length;

        // Sync master checkboxes
        const isAll = checked.length > 0 && checked.length === total.length;
        const master1 = document.getElementById('selectAllMaster');
        const master2 = document.getElementById('selectAllTable');
        if (master1) master1.checked = isAll;
        if (master2) master2.checked = isAll;

        // Highlight cards & rows
        total.forEach(cb => {
            const card = document.getElementById('card-' + cb.value);
            const row = document.getElementById('row-' + cb.value);
            if (cb.checked) {
                if (card) card.classList.add('selected');
                if (row) row.classList.add('row-selected');
            } else {
                if (card) card.classList.remove('selected');
                if (row) row.classList.remove('row-selected');
            }
        });
    }

    function toggleSelectAll(checked) {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = checked;
        });
        updateSelectedCount();
    }

    function toggleStudentCard(studentId, event) {
        // Prevent toggle if clicking directly on checkbox to avoid double trigger
        if (event.target.tagName.toLowerCase() === 'input') return;
        const cb = document.getElementById('check-' + studentId);
        if (cb) {
            cb.checked = !cb.checked;
            updateSelectedCount();
        }
    }

    function onCardCheckChange(studentId) {
        updateSelectedCount();
    }

    function confirmPromotion(title, text, color) {
        const checkboxes = document.querySelectorAll('.student-checkbox:checked');
        if (checkboxes.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Belum Ada Siswa Dipilih',
                text: 'Silakan centang minimal satu siswa yang akan dinaikkan kelas atau diluluskan.',
                confirmButtonColor: '#0284c7',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: color,
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('promotionForm').submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', updateSelectedCount);
</script>
@endpush
@endsection
