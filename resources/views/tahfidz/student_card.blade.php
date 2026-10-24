@extends('layouts.app')

@section('title', 'Kartu Hafalan – ' . $student->name)
@section('page-title', 'Kartu Hafalan Tahfidz')

@section('content')

@php
    $qualityColor = fn($q) => match($q) {
        'lancar'          => '#16a34a',
        'cukup'           => '#d97706',
        'perlu_perbaikan' => '#dc2626',
        default           => '#64748b',
    };
    $qualityLabel = fn($q) => match($q) {
        'lancar'          => 'Lancar',
        'cukup'           => 'Cukup',
        'perlu_perbaikan' => 'Perlu Perbaikan',
        default           => '-',
    };
    $typeColor = fn($t) => $t === 'setoran_baru' ? '#0ea5e9' : '#8b5cf6';
    $typeLabel = fn($t) => $t === 'setoran_baru' ? 'Setoran Baru' : 'Muroja\'ah';
@endphp

<style>
.card-header-custom {
    background: linear-gradient(135deg, #1e3a5f 0%, #0f766e 100%);
    border-radius: 16px 16px 0 0;
    padding: 28px 30px;
    color: white;
}
.stat-box {
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    padding: 16px 20px;
    text-align: center;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.2);
}
.stat-box .stat-num { font-size: 2rem; font-weight: 800; line-height: 1; }
.stat-box .stat-lbl { font-size: 0.75rem; opacity: 0.85; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
.form-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 24px;
}
.form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 14px; }
.form-group label { display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.4px; }
.form-group input, .form-group select, .form-group textarea {
    width: 100%; padding: 9px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px;
    font-size: 0.9rem; background: white; color: #0f172a; box-sizing: border-box;
    transition: border-color 0.2s;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    outline: none; border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15,118,110,0.1);
}
.btn-save {
    background: linear-gradient(135deg, #0f766e, #0ea5e9);
    color: white; border: none; padding: 10px 26px;
    border-radius: 9px; font-weight: 700; font-size: 0.9rem;
    cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
    transition: transform 0.15s, box-shadow 0.15s;
}
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(15,118,110,0.3); }
.table-records th { background: #f1f5f9; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; padding: 10px 12px; }
.table-records td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.88rem; }
.table-records tr:hover td { background: #f8fafc; }
.badge-type { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; color: white; }
.badge-quality { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; color: white; }
.btn-del { background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.85rem; padding: 4px 8px; border-radius: 6px; transition: background 0.15s; }
.btn-del:hover { background: #fee2e2; }
.empty-state { text-align: center; padding: 40px; color: #94a3b8; }
.empty-state i { font-size: 3rem; margin-bottom: 12px; display: block; }

/* SYNC INFO CARD & COLLAPSIBLE FORM */
.sync-info-card {
    background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
    border: 1.5px solid #a7f3d0;
    border-radius: 14px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.06);
}
.sync-info-left {
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1;
    min-width: 260px;
}
.sync-info-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: #10b981;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.25);
}
.sync-info-title {
    font-size: 0.94rem;
    font-weight: 700;
    color: #065f46;
    margin-bottom: 2px;
}
.sync-info-desc {
    font-size: 0.83rem;
    color: #047857;
    line-height: 1.45;
}
.btn-toggle-manual-entry {
    background: white;
    border: 1.5px solid #0f766e;
    color: #0f766e;
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 0.84rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    white-space: nowrap;
}
.btn-toggle-manual-entry:hover {
    background: #0f766e;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);
}
.btn-toggle-manual-entry.is-open {
    background: #f1f5f9;
    border-color: #94a3b8;
    color: #475569;
    box-shadow: none;
}
.manual-form-wrapper {
    display: none;
    margin-bottom: 24px;
    animation: fadeInDown 0.3s ease forwards;
}
.manual-form-wrapper.is-open {
    display: block;
}
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* RESPONSIVE MOBILE STYLES */
@media (max-width: 768px) {
    .card-header-custom {
        padding: 20px 16px !important;
        border-radius: 14px 14px 0 0 !important;
    }
    .card-header-custom h2 {
        font-size: 1.35rem !important;
    }
    .card-header-flex-top {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 14px !important;
    }
    .header-actions-wrap {
        width: 100% !important;
        flex-direction: column !important;
        gap: 8px !important;
    }
    .header-actions-wrap a, .header-actions-wrap form, .header-actions-wrap select {
        width: 100% !important;
        justify-content: center !important;
        text-align: center !important;
        box-sizing: border-box;
    }
    .stats-grid-card {
        grid-template-columns: 1fr 1fr 1fr !important;
        gap: 8px !important;
    }
    .stat-box {
        padding: 10px 8px !important;
    }
    .stat-box .stat-num {
        font-size: 1.35rem !important;
    }
    .stat-box .stat-lbl {
        font-size: 0.65rem !important;
    }
    .card-body-custom {
        padding: 16px 14px !important;
    }
    .form-card {
        padding: 16px 14px !important;
    }
    .form-row {
        grid-template-columns: 1fr !important;
        gap: 10px !important;
    }
    .btn-save {
        width: 100% !important;
        justify-content: center !important;
        padding: 12px !important;
    }
    .desktop-records-table {
        display: none !important;
    }
    .mobile-records-list {
        display: flex !important;
        flex-direction: column;
        gap: 10px;
    }
}
@media (min-width: 769px) {
    .desktop-records-table {
        display: block !important;
    }
    .mobile-records-list {
        display: none !important;
    }
}
</style>

<div style="max-width: 1000px; margin: 0 auto;">

    {{-- HEADER --}}
    <div class="card-header-custom">
        <div class="card-header-flex-top" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; flex-wrap: wrap;">
            <div>
                <div style="font-size: 0.8rem; opacity: 0.75; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-users"></i>
                    <a href="{{ route('tahfidz-groups.index', ['group_id' => $group->id]) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ $group->name }}</a>
                    <span>›</span> Kartu Hafalan
                </div>
                <h2 style="margin: 0; font-size: 1.6rem; font-weight: 800; word-break: break-word;">
                    <i class="fas fa-quran" style="margin-right: 10px; opacity: 0.8;"></i>
                    {{ $student->name }}
                </h2>
                <div style="margin-top: 8px; font-size: 0.85rem; opacity: 0.8;">
                    Kelas {{ $student->class }}
                    @if($student->nis) · NIS: {{ $student->nis }} @endif
                    · Pembimbing: {{ $group->mentor_name }}
                </div>
            </div>
            <div class="header-actions-wrap" style="display: flex; gap: 8px; flex-wrap: wrap;">
                {{-- Filter tahun --}}
                <form method="GET" style="display: flex; align-items: center; gap: 6px;">
                    <select name="year_id" onchange="this.form.submit()" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 8px; padding: 7px 10px; font-size: 0.85rem; cursor: pointer;">
                        @foreach($academicYears as $yr)
                            <option value="{{ $yr->id }}" {{ $yr->id == $yearId ? 'selected' : '' }} style="color: #0f172a; background: white;">
                                TP {{ $yr->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
                @if($existingRegistration)
                    <span style="background: #e0f2fe; border: 1px solid #7dd3fc; color: #0369a1; padding: 7px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 6px;" title="Terdaftar Munaqosah: {{ $existingRegistration->exam_time ?? 'Menunggu Jadwal' }}">
                        <i class="fas fa-check-circle" style="color: #0284c7;"></i> Terdaftar Munaqosah
                    </span>
                @else
                    <button type="button" onclick="openCardMunaqosahModal()"
                       style="background: #0284c7; border: 1px solid rgba(255,255,255,0.4); color: white; cursor: pointer; padding: 7px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition: all 0.2s;"
                       onmouseover="this.style.background='#0369a1'" onmouseout="this.style.background='#0284c7'">
                        <i class="fas fa-award"></i> Daftarkan Munaqosah
                    </button>
                @endif
                <a href="{{ route('tahfidz.print-card', ['group' => $group->id, 'student' => $student->id, 'year_id' => $yearId]) }}" target="_blank"
                   style="background: #059669; border: 1px solid rgba(255,255,255,0.4); color: white; text-decoration: none; padding: 7px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition: all 0.2s;"
                   onmouseover="this.style.background='#10b981'" onmouseout="this.style.background='#059669'">
                    <i class="fas fa-file-invoice"></i> Cetak Raport Tahfidz
                </a>
                <a href="{{ route('tahfidz-groups.index', ['group_id' => $group->id]) }}"
                   style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; text-decoration: none; padding: 7px 14px; border-radius: 8px; font-size: 0.85rem; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="stats-grid-card" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 20px;">
            <div class="stat-box" style="background: rgba(220, 252, 231, 0.25); border: 1.5px solid rgba(134, 239, 172, 0.45);">
                <div class="stat-num" style="color: #6ee7b7;">{{ (int) $records->sum('jumlah_baris') }}</div>
                <div class="stat-lbl" style="color: #a7f3d0;">Baris Dihafal</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">{{ $totalSetoran }}</div>
                <div class="stat-lbl">Setoran Baru</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">{{ $totalMurojaah }}</div>
                <div class="stat-lbl">Muroja'ah</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">{{ $surahUnique }}</div>
                <div class="stat-lbl">Surah Dicatat</div>
            </div>
        </div>
    </div>

    <div class="card-body-custom" style="background: white; border-radius: 0 0 16px 16px; border: 1px solid #e2e8f0; border-top: none; padding: 28px;">

        @if(session('success'))
            <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- TARGET HAFALAN PROGRESS & CHECKLIST --}}
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 22px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 14px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-bullseye" style="color: #0f766e; font-size: 1.1rem;"></i>
                    <h4 style="margin: 0; color: #0f172a; font-size: 1rem; font-weight: 700;">
                        Target Hafalan Siswa
                    </h4>
                    <span style="font-size: 0.8rem; background: #ccfbf1; color: #0f766e; padding: 2px 8px; border-radius: 6px; font-weight: 700;">
                        {{ $group->target_level ?? 'Juz 30' }}
                    </span>
                </div>
                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                    @php
                        $targetJuzList = $targetJuzList ?? [30, 29];
                        $targetJuzData = $targetJuzData ?? [30 => ($juz30Data ?? []), 29 => ($juz29Data ?? [])];
                    @endphp
                    @foreach($targetJuzList as $idx => $juzNum)
                        @php $jData = $targetJuzData[$juzNum] ?? ['completed' => 0, 'total' => 0]; @endphp
                        <button type="button" onclick="switchJuzTab({{ $juzNum }})" id="btnTab{{ $juzNum }}"
                                class="juz-tab-btn"
                                style="padding: 6px 14px; border-radius: 8px; border: 1px solid {{ $idx === 0 ? '#0f766e' : '#cbd5e1' }}; background: {{ $idx === 0 ? '#0f766e' : 'white' }}; color: {{ $idx === 0 ? 'white' : '#475569' }}; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                            Juz {{ $juzNum }} ({{ $jData['completed'] }}/{{ $jData['total'] }})
                        </button>
                    @endforeach
                </div>
            </div>

            @foreach($targetJuzList as $idx => $juzNum)
                @php $jData = $targetJuzData[$juzNum] ?? ['percent' => 0, 'completed' => 0, 'total' => 0, 'surahs' => []]; @endphp
                <div id="contentJuz{{ $juzNum }}" class="juz-tab-content" style="{{ $idx > 0 ? 'display: none;' : '' }}">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 0.85rem;">
                        <span style="color: #475569; font-weight: 600;">Capaian Juz {{ $juzNum }}</span>
                        <strong style="color: #0f766e; font-size: 0.95rem;">{{ $jData['percent'] }}% Terpenuhi ({{ $jData['completed'] }} dari {{ $jData['total'] }} Surah)</strong>
                    </div>
                    <div style="width: 100%; height: 10px; background: #e2e8f0; border-radius: 20px; overflow: hidden; margin-bottom: 16px;">
                        <div style="width: {{ $jData['percent'] }}%; height: 100%; background: linear-gradient(90deg, #10b981, #0f766e); border-radius: 20px; transition: width 0.5s ease;"></div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 14px; font-size: 0.76rem; color: #64748b; margin-bottom: 12px; flex-wrap: wrap;">
                        <span style="display: inline-flex; align-items: center; gap: 4px;"><span style="width: 10px; height: 10px; border-radius: 3px; background: #10b981; display: inline-block;"></span> Lancar ({{ collect($jData['surahs'])->where('status', 'lancar')->count() }})</span>
                        <span style="display: inline-flex; align-items: center; gap: 4px;"><span style="width: 10px; height: 10px; border-radius: 3px; background: #f59e0b; display: inline-block;"></span> Cukup ({{ collect($jData['surahs'])->where('status', 'cukup')->count() }})</span>
                        <span style="display: inline-flex; align-items: center; gap: 4px;"><span style="width: 10px; height: 10px; border-radius: 3px; background: #ef4444; display: inline-block;"></span> Perlu Perbaikan ({{ collect($jData['surahs'])->where('status', 'perlu_perbaikan')->count() }})</span>
                        <span style="display: inline-flex; align-items: center; gap: 4px;"><span style="width: 10px; height: 10px; border-radius: 3px; background: #e2e8f0; display: inline-block;"></span> Belum Setor ({{ collect($jData['surahs'])->where('status', 'unrecorded')->count() }})</span>
                        <span style="font-style: italic; color: #0f766e; margin-left: auto;">*Klik nama surah untuk otomatis mengisi formulir setoran</span>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                        @foreach($jData['surahs'] as $item)
                            @php
                                $bg = match($item['status']) {
                                    'lancar' => '#dcfce7',
                                    'cukup'  => '#fef3c7',
                                    'perlu_perbaikan' => '#fee2e2',
                                    default  => '#ffffff',
                                };
                                $borderColor = match($item['status']) {
                                    'lancar' => '#86efac',
                                    'cukup'  => '#fde047',
                                    'perlu_perbaikan' => '#fca5a5',
                                    default  => '#cbd5e1',
                                };
                                $textColor = match($item['status']) {
                                    'lancar' => '#15803d',
                                    'cukup'  => '#854d0e',
                                    'perlu_perbaikan' => '#b91c1c',
                                    default  => '#475569',
                                };
                                $icon = match($item['status']) {
                                    'lancar' => 'fa-check-circle',
                                    'cukup'  => 'fa-check',
                                    'perlu_perbaikan' => 'fa-exclamation-circle',
                                    default  => 'fa-circle-notch',
                                };
                            @endphp
                            <button type="button" onclick="selectSurah('{{ $item['name'] }}', {{ $juzNum }})"
                                    style="background: {{ $bg }}; border: 1.5px solid {{ $borderColor }}; color: {{ $textColor }}; padding: 4px 10px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; transition: transform 0.15s;"
                                    onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='none'"
                                    title="{{ $item['status'] !== 'unrecorded' ? 'Sudah disetor ' . $item['count'] . 'x (' . $item['status'] . ')' : 'Belum pernah disetor' }}">
                                <i class="fas {{ $icon }}" style="font-size: 0.72rem;"></i>
                                {{ $item['name'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <script>
        function switchJuzTab(juz) {
            document.querySelectorAll('.juz-tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.juz-tab-btn').forEach(btn => {
                btn.style.background = 'white';
                btn.style.color = '#475569';
                btn.style.borderColor = '#cbd5e1';
            });

            const activeContent = document.getElementById('contentJuz' + juz);
            const activeBtn = document.getElementById('btnTab' + juz);
            if (activeContent) activeContent.style.display = 'block';
            if (activeBtn) {
                activeBtn.style.background = '#0f766e';
                activeBtn.style.color = 'white';
                activeBtn.style.borderColor = '#0f766e';
            }
        }

        function toggleManualForm(forceOpen = null) {
            const wrapper = document.getElementById('manualFormWrapper');
            const btn = document.getElementById('btnToggleManual');
            const icon = document.getElementById('manualToggleIcon');
            const text = document.getElementById('manualToggleText');
            if (!wrapper) return;

            const willOpen = (forceOpen !== null) ? forceOpen : !wrapper.classList.contains('is-open');

            if (willOpen) {
                wrapper.classList.add('is-open');
                if (btn) btn.classList.add('is-open');
                if (icon) icon.className = 'fas fa-chevron-up';
                if (text) text.innerText = 'Tutup Form Susulan';
            } else {
                wrapper.classList.remove('is-open');
                if (btn) btn.classList.remove('is-open');
                if (icon) icon.className = 'fas fa-plus-circle';
                if (text) text.innerText = 'Input Setoran Susulan';
            }
        }

        function selectSurah(surahName, juz) {
            toggleManualForm(true);

            const inputSurah = document.querySelector('input[name="surah_name"]');
            const inputJuz = document.querySelector('input[name="juz"]');
            const formCard = document.querySelector('.form-card');

            if (inputSurah) {
                inputSurah.value = surahName;
                inputSurah.style.borderColor = '#0f766e';
                inputSurah.style.boxShadow = '0 0 0 3px rgba(15,118,110,0.2)';
                setTimeout(() => {
                    inputSurah.style.boxShadow = '';
                }, 1500);
            }
            if (inputJuz) {
                inputJuz.value = juz;
            }
            if (formCard) {
                formCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        </script>

        {{-- INFO SYNC DARI RUANG HALAQAH + TOMBOL FORM SUSULAN --}}
        <div class="sync-info-card">
            <div class="sync-info-left">
                <div class="sync-info-icon">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="sync-info-content">
                    <div class="sync-info-title">Pencatatan Hafalan Otomatis</div>
                    <div class="sync-info-desc">Setoran hafalan dan kehadiran santri otomatis tersinkron saat guru menginput di <strong>Ruang Halaqah</strong>. Kartu ini menampilkan rekapitulasi resmi ananda.</div>
                </div>
            </div>
            <div>
                <button type="button" class="btn-toggle-manual-entry {{ $errors->any() ? 'is-open' : '' }}" id="btnToggleManual" onclick="toggleManualForm()">
                    <i class="fas {{ $errors->any() ? 'fa-chevron-up' : 'fa-plus-circle' }}" id="manualToggleIcon"></i>
                    <span id="manualToggleText">{{ $errors->any() ? 'Tutup Form Susulan' : 'Input Setoran Susulan' }}</span>
                </button>
            </div>
        </div>

        {{-- FORM INPUT SETORAN SUSULAN (COLLAPSIBLE) --}}
        <div id="manualFormWrapper" class="manual-form-wrapper {{ $errors->any() ? 'is-open' : '' }}">
            <div class="form-card">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
                    <div>
                        <h4 style="margin: 0; color: #0f172a; font-size: 1rem; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-plus-circle" style="color: #0f766e;"></i> Form Setoran Susulan / Tambahan
                        </h4>
                        <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">
                            Gunakan form ini hanya jika ananda menyetor di luar jam KBM halaqah reguler.
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 0.76rem; color: #065f46; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 4px 8px; border-radius: 6px; font-weight: 600;">
                            <i class="fas fa-sync-alt"></i> Auto-Sync Rapor & Absensi
                        </span>
                        <button type="button" onclick="toggleManualForm(false)" style="background: none; border: none; color: #64748b; font-size: 0.85rem; cursor: pointer; padding: 4px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;" title="Tutup Formulir">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('tahfidz.store', ['group' => $group->id, 'student' => $student->id]) }}">
                    @csrf

                    <input type="hidden" name="academic_year_id" value="{{ $yearId }}">

                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Nama Surah <span style="color: red;">*</span></label>
                            <input type="text" name="surah_name" list="surah-list" placeholder="misal: Al-Baqarah" required value="{{ old('surah_name') }}">
                            <datalist id="surah-list">
                                @php
                                $surahs = ['Al-Fatihah','Al-Baqarah','Ali Imran','An-Nisa','Al-Maidah','Al-Anam','Al-Araf','Al-Anfal','At-Taubah','Yunus','Hud','Yusuf','Ar-Rad','Ibrahim','Al-Hijr','An-Nahl','Al-Isra','Al-Kahfi','Maryam','Thaha','Al-Anbiya','Al-Hajj','Al-Mu\'minun','An-Nur','Al-Furqan','Asy-Syuara','An-Naml','Al-Qasas','Al-Ankabut','Ar-Rum','Luqman','As-Sajdah','Al-Ahzab','Saba','Fatir','Yasin','As-Saffat','Sad','Az-Zumar','Ghafir','Fussilat','Asy-Syura','Az-Zukhruf','Ad-Dukhan','Al-Jatsiyah','Al-Ahqaf','Muhammad','Al-Fath','Al-Hujurat','Qaf','Az-Zariyat','At-Tur','An-Najm','Al-Qamar','Ar-Rahman','Al-Waqi\'ah','Al-Hadid','Al-Mujadilah','Al-Hasyr','Al-Mumtahanah','As-Saf','Al-Jumuah','Al-Munafiqun','At-Taghabun','At-Talaq','At-Tahrim','Al-Mulk','Al-Qalam','Al-Haqqah','Al-Ma\'arij','Nuh','Al-Jinn','Al-Muzammil','Al-Muddassir','Al-Qiyamah','Al-Insan','Al-Mursalat','An-Naba','An-Nazi\'at','Abasa','At-Takwir','Al-Infitar','Al-Mutaffifin','Al-Insyiqaq','Al-Buruj','At-Tariq','Al-A\'la','Al-Ghasyiyah','Al-Fajr','Al-Balad','Asy-Syams','Al-Lail','Ad-Duha','Asy-Syarh','At-Tin','Al-\'Alaq','Al-Qadr','Al-Bayyinah','Az-Zalzalah','Al-\'Adiyat','Al-Qari\'ah','At-Takasur','Al-\'Asr','Al-Humazah','Al-Fil','Quraisy','Al-Ma\'un','Al-Kausar','Al-Kafirun','An-Nasr','Al-Masad','Al-Ikhlas','Al-Falaq','An-Nas'];
                                @endphp
                                @foreach($surahs as $s)
                                    <option value="{{ $s }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Juz Ke-</label>
                            <input type="number" name="juz" min="1" max="30" placeholder="1-30" value="{{ old('juz') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Ayat Mulai</label>
                            <input type="number" name="start_ayah" min="1" placeholder="Ayat pertama" value="{{ old('start_ayah') }}">
                        </div>
                        <div class="form-group">
                            <label>Ayat Selesai</label>
                            <input type="number" name="end_ayah" min="1" placeholder="Ayat terakhir" value="{{ old('end_ayah') }}">
                        </div>
                        <div class="form-group">
                            <label>Jumlah Baris</label>
                            <input type="number" name="jumlah_baris" min="0" max="500" placeholder="mis: 15 baris" value="{{ old('jumlah_baris') }}">
                        </div>
                        <div class="form-group">
                            <label>Jenis <span style="color: red;">*</span></label>
                            <select name="type" required class="form-control-pro" style="width: 100%;">
                                <option value="setoran_baru" {{ old('type') == 'setoran_baru' ? 'selected' : '' }}>Setoran Baru</option>
                                <option value="murojaah" {{ old('type') == 'murojaah' ? 'selected' : '' }}>Muroja'ah</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kualitas <span style="color: red;">*</span></label>
                            <select name="quality" required class="form-control-pro" style="width: 100%;">
                                <option value="lancar" {{ old('quality') == 'lancar' ? 'selected' : '' }}>⭐ Lancar</option>
                                <option value="cukup" {{ old('quality') == 'cukup' ? 'selected' : '' }}>👍 Cukup</option>
                                <option value="perlu_perbaikan" {{ old('quality') == 'perlu_perbaikan' ? 'selected' : '' }}>⚠️ Perlu Perbaikan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nilai (Opsional)</label>
                            <input type="text" name="nilai" placeholder="cth: 90" value="{{ old('nilai') }}">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Setoran <span style="color: red;">*</span></label>
                            <input type="date" name="session_date" required value="{{ old('session_date', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 18px;">
                        <label>Catatan Mentor (opsional)</label>
                        <textarea name="notes" rows="2" placeholder="Catatan tambahan untuk siswa...">{{ old('notes') }}</textarea>
                    </div>

                    @if($errors->any())
                        <div style="color: #dc2626; font-size: 0.85rem; margin-bottom: 12px;">
                            @foreach($errors->all() as $err) <div>• {{ $err }}</div> @endforeach
                        </div>
                    @endif

                    <div style="display: flex; gap: 10px; align-items: center;">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> Simpan Setoran
                        </button>
                        <button type="button" onclick="toggleManualForm(false)" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; padding: 10px 18px; border-radius: 9px; font-weight: 600; font-size: 0.88rem; cursor: pointer;">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABEL RIWAYAT SETORAN --}}
        <h4 style="margin: 0 0 14px 0; color: #0f172a; font-size: 1rem; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-history" style="color: #0f766e;"></i>
            Riwayat Setoran
            <span style="font-size: 0.8rem; font-weight: 500; color: #64748b;">({{ $records->count() }} catatan)</span>
        </h4>

        @if($records->isEmpty())
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <p style="margin: 0; font-size: 0.95rem;">Belum ada catatan setoran untuk tahun pelajaran ini.</p>
                <p style="margin: 6px 0 0 0; font-size: 0.85rem;">Gunakan form di atas untuk menambahkan setoran pertama.</p>
            </div>
        @else
            <div class="desktop-records-table" style="overflow-x: auto;">
                <table class="table-records" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="width: 4%; text-align: center;">No</th>
                            <th style="width: 11%;">Tanggal</th>
                            <th style="width: 18%;">Surah</th>
                            <th style="width: 7%; text-align: center;">Juz</th>
                            <th style="width: 10%; text-align: center;">Ayat</th>
                            <th style="width: 8%; text-align: center;">Nilai</th>
                            <th style="width: 8%; text-align: center;">Baris</th>
                            <th style="width: 11%; text-align: center;">Jenis</th>
                            <th style="width: 11%; text-align: center;">Kualitas</th>
                            <th>Catatan</th>
                            <th style="width: 9%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $i => $rec)
                            @php
                                $rAyah = $rec->start_ayah ? "Ayat {$rec->start_ayah}" . ($rec->end_ayah && $rec->end_ayah != $rec->start_ayah ? "-{$rec->end_ayah}" : '') : 'Selesai';
                                $rQuality = $qualityLabel($rec->quality);
                                $rType = $typeLabel($rec->type);
                                $rDate = \Carbon\Carbon::parse($rec->session_date)->isoFormat('dddd, D MMMM Y');
                                $rNotes = $rec->notes ?? 'Pertahankan hafalan dengan muroja\'ah istiqomah di rumah.';
                            @endphp
                            <tr>
                                <td style="text-align: center; color: #94a3b8; font-size: 0.8rem;">{{ $i + 1 }}</td>
                                <td style="color: #475569; font-size: 0.85rem;">
                                    {{ \Carbon\Carbon::parse($rec->session_date)->isoFormat('D MMM Y') }}
                                </td>
                                <td style="font-weight: 700; color: #0f172a;">{{ $rec->surah_name }}</td>
                                <td style="text-align: center; color: #0f766e; font-weight: 600;">
                                    {{ $rec->juz ? 'Juz '.$rec->juz : '-' }}
                                </td>
                                <td style="text-align: center; font-size: 0.82rem; color: #475569;">
                                    @if($rec->start_ayah && $rec->end_ayah)
                                        {{ $rec->start_ayah }} – {{ $rec->end_ayah }}
                                    @else -
                                    @endif
                                </td>
                                <td style="text-align: center; font-size: 0.82rem; font-weight: 800; color: #b45309;">
                                    {{ $rec->nilai ? '⭐ ' . $rec->nilai : '-' }}
                                </td>
                                <td style="text-align: center; font-size: 0.82rem; font-weight: 700; color: #047857;">
                                    {{ $rec->jumlah_baris > 0 ? $rec->jumlah_baris . ' Baris' : '-' }}
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge-type" style="background: {{ $typeColor($rec->type) }};">
                                        {{ $typeLabel($rec->type) }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge-quality" style="background: {{ $qualityColor($rec->quality) }};">
                                        {{ $qualityLabel($rec->quality) }}
                                    </span>
                                </td>
                                <td style="font-size: 0.83rem; color: #64748b;">{{ $rec->notes ?? '-' }}</td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 4px; justify-content: center; align-items: center;">
                                        <button type="button" 
                                                onclick="openWaShareModal(
                                                    '{{ addslashes($student->name) }}',
                                                    '{{ $student->class }}',
                                                    '{{ $student->parent_phone ?? '' }}',
                                                    '{{ addslashes($rec->surah_name) }}',
                                                    '{{ addslashes($rAyah) }}',
                                                    '{{ addslashes($rQuality) }}',
                                                    '{{ addslashes($rType) }}',
                                                    '{{ addslashes($rDate) }}',
                                                    '{{ addslashes($rNotes) }}'
                                                )"
                                                style="background: none; border: none; color: #16a34a; cursor: pointer; font-size: 1rem; padding: 4px;"
                                                title="Bagikan Setoran ini ke WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </button>
                                        <form method="POST" action="{{ route('tahfidz.destroy', $rec->id) }}" onsubmit="return confirm('Hapus setoran {{ $rec->surah_name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-del" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- MOBILE RECORDS LIST (CARD-BASED, NO CLIPPING) -->
            <div class="mobile-records-list">
                @foreach($records as $i => $rec)
                    @php
                        $rAyah = $rec->start_ayah ? "Ayat {$rec->start_ayah}" . ($rec->end_ayah && $rec->end_ayah != $rec->start_ayah ? "-{$rec->end_ayah}" : '') : 'Selesai';
                        $rQuality = $qualityLabel($rec->quality);
                        $rType = $typeLabel($rec->type);
                        $rDate = \Carbon\Carbon::parse($rec->session_date)->isoFormat('dddd, D MMMM Y');
                        $rNotes = $rec->notes ?? 'Pertahankan hafalan dengan muroja\'ah istiqomah di rumah.';
                    @endphp
                    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.02);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; margin-bottom: 6px;">
                            <div>
                                <strong style="color: #0f172a; font-size: 0.95rem;">{{ $rec->surah_name }}</strong>
                                <span style="font-size: 0.82rem; color: #475569; font-weight: 600;">
                                    ({{ $rAyah }})
                                </span>
                            </div>
                            <span style="font-size: 0.74rem; color: #64748b; white-space: nowrap;">
                                {{ \Carbon\Carbon::parse($rec->session_date)->format('d/m/Y') }}
                            </span>
                        </div>

                        <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap; margin-bottom: 8px;">
                            <span class="badge-type" style="background: {{ $typeColor($rec->type) }}; font-size: 0.72rem; padding: 2px 8px;">
                                {{ $typeLabel($rec->type) }}
                            </span>
                            <span class="badge-quality" style="background: {{ $qualityColor($rec->quality) }}; font-size: 0.72rem; padding: 2px 8px;">
                                {{ $qualityLabel($rec->quality) }}
                            </span>
                            @if($rec->juz)
                                <span style="font-size: 0.72rem; background: #f1f5f9; color: #0f766e; padding: 2px 8px; border-radius: 12px; font-weight: 700;">
                                    Juz {{ $rec->juz }}
                                </span>
                            @endif
                            @if($rec->jumlah_baris > 0)
                                <span style="font-size: 0.72rem; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 2px 8px; border-radius: 12px; font-weight: 700;">
                                    <i class="fas fa-align-left"></i> {{ $rec->jumlah_baris }} Baris
                                </span>
                            @endif
                        </div>

                        @if($rec->notes)
                            <div style="font-size: 0.8rem; color: #475569; background: #f8fafc; padding: 6px 10px; border-radius: 6px; border-left: 3px solid #0f766e; margin-bottom: 8px;">
                                "{{ $rec->notes }}"
                            </div>
                        @endif

                        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 8px; padding-top: 6px; border-top: 1px dashed #f1f5f9;">
                            <button type="button" 
                                    onclick="openWaShareModal(
                                        '{{ addslashes($student->name) }}',
                                        '{{ $student->class }}',
                                        '{{ $student->parent_phone ?? '' }}',
                                        '{{ addslashes($rec->surah_name) }}',
                                        '{{ addslashes($rAyah) }}',
                                        '{{ addslashes($rQuality) }}',
                                        '{{ addslashes($rType) }}',
                                        '{{ addslashes($rDate) }}',
                                        '{{ addslashes($rNotes) }}'
                                    )"
                                    style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #059669; padding: 6px 12px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fab fa-whatsapp" style="color: #25D366;"></i> Kirim WA
                            </button>
                            <form method="POST" action="{{ route('tahfidz.destroy', $rec->id) }}" onsubmit="return confirm('Hapus setoran {{ $rec->surah_name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del" style="padding: 6px 10px; font-size: 0.78rem;" title="Hapus">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- MODAL SHARE WA DI KARTU SISWA -->
<div id="cardWaShareModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.65); backdrop-filter: blur(4px); z-index: 1050; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: white; border-radius: 16px; width: 100%; max-width: 580px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3); overflow: hidden;">
        <div style="padding: 16px 20px; background: #f0fdf4; border-bottom: 1px solid #86efac; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fab fa-whatsapp" style="font-size: 1.3rem; color: #16a34a;"></i>
                <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #14532d;">Kirim Laporan Tahfidz ke WhatsApp</h4>
            </div>
            <button type="button" onclick="closeWaShareModal()" style="border: none; background: transparent; font-size: 1.2rem; color: #64748b; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div style="padding: 20px;">
            <textarea id="cardWaTextarea" rows="11" class="form-control" style="font-family: monospace; font-size: 0.84rem; line-height: 1.5; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1;"></textarea>
            <div id="cardPhoneInfo" style="margin-top: 12px; font-size: 0.85rem; color: #065f46; background: #ecfdf5; padding: 8px 12px; border-radius: 8px; border: 1px solid #a7f3d0;">
                <i class="fas fa-phone-alt"></i> HP Orang Tua: <strong id="cardPhoneVal">-</strong>
            </div>
        </div>
        <div style="padding: 14px 20px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 8px; flex-wrap: wrap;">
            <button type="button" onclick="copyCardWaText()" class="btn-action-header btn-dark" style="background: #64748b; padding: 8px 14px; font-size: 0.85rem;">
                <i class="fas fa-copy"></i> Salin Teks
            </button>
            <button type="button" onclick="shareCardWaToGroup()" class="btn-action-header btn-blue" style="background: #0284c7; padding: 8px 16px; font-size: 0.85rem;">
                <i class="fas fa-share-alt"></i> Share ke Grup WA
            </button>
            <button type="button" id="btnSendCardDirect" onclick="sendCardWaDirect()" class="btn-action-header btn-green" style="background: #25D366; font-weight: 800; padding: 8px 18px; font-size: 0.85rem;">
                <i class="fab fa-whatsapp"></i> Kirim ke No Ortu
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let activeCardWaPhone = '';

    function openWaShareModal(studentName, studentClass, parentPhone, surahName, ayah, quality, type, dateStr, notes) {
        activeCardWaPhone = parentPhone.replace(/[^0-9]/g, '');
        if (activeCardWaPhone.startsWith('0')) {
            activeCardWaPhone = '62' + activeCardWaPhone.substring(1);
        }

        const phoneValElem = document.getElementById('cardPhoneVal');
        const btnDirect = document.getElementById('btnSendCardDirect');

        if (activeCardWaPhone) {
            phoneValElem.innerText = parentPhone;
            btnDirect.style.display = 'inline-flex';
        } else {
            phoneValElem.innerText = 'Belum Terdata';
            btnDirect.style.display = 'none';
        }

        const mentorName = '{{ addslashes($group->mentor_name ?? Auth::user()->name) }}';

        const message = 
`Assalamu'alaikum Warahmatullahi Wabarakatuh Ayah/Bunda.

Berikut laporan capaian Tahfidz ananda di *SDIT An Nadzir*:
👤 *Nama:* ${studentName}
🏫 *Kelas:* ${studentClass}
📖 *Setoran:* QS. ${surahName} (${ayah})
📌 *Jenis:* ${type}
⭐ *Kualitas:* ${quality}
📝 *Catatan Musyrif:* ${notes}
📅 *Tanggal:* ${dateStr}
🕌 *Musyrif:* ${mentorName}

Alhamdulillah ananda telah menyetorkan hafalan hari ini. Mohon Ayah/Bunda terus mendampingi dan memotivasi pengulangan (muroja'ah) ananda di rumah.

Jazakumullahu Khairan Katsiran.
Wassalamu'alaikum Warahmatullahi Wabarakatuh.
_*SDIT An Nadzir - Mencetak Generasi Qur'ani*_`;

        document.getElementById('cardWaTextarea').value = message;
        const modal = document.getElementById('cardWaShareModal');
        modal.style.display = 'flex';
    }

    function closeWaShareModal() {
        document.getElementById('cardWaShareModal').style.display = 'none';
    }

    function sendCardWaDirect() {
        const text = document.getElementById('cardWaTextarea').value;
        if (!activeCardWaPhone) {
            alert('Nomor HP Orang Tua belum tersedia.');
            return;
        }
        window.open(`https://api.whatsapp.com/send?phone=${activeCardWaPhone}&text=${encodeURIComponent(text)}`, '_blank');
    }

    function shareCardWaToGroup() {
        const text = document.getElementById('cardWaTextarea').value;
        window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`, '_blank');
    }

    function copyCardWaText() {
        const text = document.getElementById('cardWaTextarea').value;
        navigator.clipboard.writeText(text).then(() => {
            alert('Teks laporan berhasil disalin!');
        });
    }

    // Auto-trigger jika baru saja simpan setoran
    @if(session('last_record'))
        document.addEventListener('DOMContentLoaded', function() {
            const lr = @json(session('last_record'));
            if (lr) {
                openWaShareModal(
                    lr.student_name,
                    lr.student_class,
                    lr.parent_phone || '',
                    lr.surah_name,
                    lr.ayah,
                    lr.quality_label || lr.quality,
                    lr.type_label || lr.type,
                    lr.date,
                    lr.notes
                );
            }
        });
    @endif
    // Modal Munaqosah Cepat dari Kartu Hafalan
    function openCardMunaqosahModal() {
        document.getElementById('cardMunaqosahModal').style.display = 'flex';
    }
    function closeCardMunaqosahModal() {
        document.getElementById('cardMunaqosahModal').style.display = 'none';
    }
</script>

<!-- Modal Daftarkan Munaqosah dari Kartu Hafalan Siswa -->
<div id="cardMunaqosahModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 520px; padding: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); max-height: 90vh; overflow-y: auto;">
        
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: #047857; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">Daftarkan Munaqosah</h3>
                    <p style="margin: 2px 0 0 0; color: #64748b; font-size: 0.8rem;">Ajukan ujian hafalan resmi langsung ke dewan penguji</p>
                </div>
            </div>
            <button type="button" onclick="closeCardMunaqosahModal()" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer; padding: 4px;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('munaqosah.register.store') }}">
            @csrf
            <input type="hidden" name="redirect_back" value="1">
            <input type="hidden" name="student_id" value="{{ $student->id }}">
            <input type="hidden" name="type" value="tahfidz">
            <input type="hidden" name="from_group_id" value="{{ $group->id }}">
            <input type="hidden" name="proposer_name" value="{{ Auth::user()->name }}">

            <!-- Info Siswa & Kelompok -->
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 12px 14px; margin-bottom: 16px;">
                <div style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                    {{ $student->name }}
                </div>
                <div style="font-size: 0.78rem; color: #64748b; margin-top: 3px; display: flex; gap: 10px; align-items: center;">
                    <span>Kelas {{ $student->class }}</span>
                    <span>•</span>
                    <span>Kelompok: <strong>{{ $group->name }}</strong></span>
                </div>
            </div>

            @php
                $curLevel = $group->target_level ?? 'Juz 30';
                $curLower = strtolower($curLevel);
                $nextLevel = 'Juz 29';
                if (str_contains($curLower, '30')) $nextLevel = 'Juz 29';
                elseif (str_contains($curLower, '29')) $nextLevel = 'Juz 28';
                elseif (str_contains($curLower, '28')) $nextLevel = 'Juz 27';
                elseif (str_contains($curLower, '27')) $nextLevel = 'Juz 26';
            @endphp

            <!-- Jenjang Munaqosah -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">
                        Jenjang Diuji <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="current_level" required value="{{ $curLevel }}"
                           style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; font-weight: 700; background: #fff;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">
                        Target Lanjutan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="target_level" required value="{{ $nextLevel }}"
                           style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; font-weight: 700; background: #fff;">
                </div>
            </div>

            <!-- Dropdown Pilihan Jadwal & Penguji Munaqisy -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                    <i class="far fa-calendar-check" style="color: #0284c7;"></i> Pilih Jadwal & Penguji / Munaqisy Tersedia
                </label>
                <select name="schedule_id" class="form-control-pro" style="width: 100%;">
                    <option value="">-- Tanpa Jadwal (Jadwalkan Nanti oleh Panitia) --</option>
                    @foreach($munaqosahSchedules as $sch)
                        @php
                            $isFull = $sch->is_full;
                            $exName = $sch->examiner?->user?->name ?? 'Penguji';
                            $slotTxt = "{$exName} • {$sch->day_name} ({$sch->time_start} - {$sch->time_end} WIB)";
                            if ($sch->room_location) {
                                $slotTxt .= " · {$sch->room_location}";
                            }
                        @endphp
                        <option value="{{ $sch->id }}" {{ $isFull ? 'disabled' : '' }}>
                            {{ $slotTxt }} {{ $isFull ? '[SUDAH TERPENUHI ✓]' : "(Sisa: " . ($sch->quota - $sch->used_slots) . " kuota)" }}
                        </option>
                    @endforeach
                </select>
                <span style="font-size: 0.74rem; color: #64748b; margin-top: 4px; display: block;">
                    Jadwal diatur oleh Admin & Kurikulum. Jika dipilih, siswa langsung otomatis terplot ke penguji tersebut.
                </span>
            </div>

            <!-- Catatan Pengusul (Opsional) -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">
                    Catatan Guru Pembimbing (Opsional)
                </label>
                <textarea name="proposer_notes" rows="2" placeholder="Catatan kesiapan siswa..." style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.84rem;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                <button type="button" onclick="closeCardMunaqosahModal()" style="padding: 10px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 22px; border-radius: 10px; border: none; background: #047857; color: white; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(4, 120, 87, 0.3); display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-check-circle"></i> Konfirmasi & Daftarkan Langsung
                </button>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection

