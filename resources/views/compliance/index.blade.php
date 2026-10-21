@extends('layouts.app')

@section('content')
<div style="max-width: 1250px; margin: 0 auto; padding-bottom: 50px;">
    
    <!-- Hero / Header Section -->
    <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; padding: 26px 30px; border-radius: 20px; margin-bottom: 24px; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px; flex-wrap: wrap;">
                <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; border: 1px solid rgba(52, 211, 153, 0.3);">
                    <i class="fas fa-shield-alt"></i> Pemantauan Disiplin Pembina
                </span>
                <span style="font-size: 0.85rem; color: #cbd5e1; font-weight: 600;">
                    Tahun Ajaran: <strong>{{ $activeYear ? $activeYear->name : '-' }}</strong> (Semester {{ $activeYear ? ($activeYear->active_semester ?? '1') : '-' }})
                </span>
            </div>
            <h2 style="font-size: 1.65rem; font-weight: 800; margin: 0; color: #f8fafc; letter-spacing: -0.3px;">
                Kepatuhan Administrasi Pembina Eskul
            </h2>
            <p style="margin: 6px 0 0 0; color: #94a3b8; font-size: 0.92rem;">
                Pantau pelaksanaan absensi harian dan kelengkapan administrasi ekstrakurikuler secara langsung & otomatis.
            </p>
        </div>

        @if($actionNeededCount > 0)
        <!-- Tombol Utama: Ingatkan Pembina (Trigger Modal) -->
        <div>
            <button type="button" onclick="openReminderModal()" 
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border: none; padding: 12px 22px; border-radius: 14px; font-weight: 800; font-size: 0.95rem; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 6px 18px rgba(220, 38, 38, 0.4); transition: all 0.25s ease;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 22px rgba(220, 38, 38, 0.5)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 18px rgba(220, 38, 38, 0.4)';">
                <i class="fab fa-whatsapp" style="font-size: 1.3rem;"></i>
                <span>Ingatkan Pembina ({{ $actionNeededCount }} Belum Lengkap)</span>
            </button>
        </div>
        @endif
    </div>

    <!-- Mode Selector Tabs: Hari Ini vs Akumulatif -->
    <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
        <a href="{{ route('compliance.index', ['mode' => 'today', 'date' => $selectedDate, 'status' => $statusFilter, 'search' => $searchKeyword, 'only_scheduled' => $onlyScheduled ? 1 : 0]) }}" 
           style="padding: 12px 22px; border-radius: 14px; font-weight: 800; font-size: 0.93rem; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; transition: all 0.2s; {{ $viewMode === 'today' ? 'background: #0284c7; color: white; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);' : 'background: white; color: #475569; border: 1px solid #e2e8f0;' }}">
            <i class="fas fa-calendar-day"></i>
            <span>Jadwal Hari Ini / Hari Pelaksanaan</span>
            <span style="font-size: 0.75rem; padding: 2px 8px; border-radius: 999px; background: {{ $viewMode === 'today' ? 'rgba(255,255,255,0.25)' : '#f1f5f9' }}; font-weight: 700;">
                Live
            </span>
        </a>

        <a href="{{ route('compliance.index', ['mode' => 'cumulative', 'status' => $statusFilter, 'search' => $searchKeyword]) }}" 
           style="padding: 12px 22px; border-radius: 14px; font-weight: 800; font-size: 0.93rem; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; transition: all 0.2s; {{ $viewMode === 'cumulative' ? 'background: #0f172a; color: white; box-shadow: 0 4px 14px rgba(15, 23, 42, 0.35);' : 'background: white; color: #475569; border: 1px solid #e2e8f0;' }}">
            <i class="fas fa-history"></i>
            <span>Akumulasi 30 Hari & Nilai Siswa</span>
        </a>
    </div>

    <!-- Stat Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px; border-radius: 16px; background: white; border-left: 5px solid #0284c7; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 0;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 0.82rem; color: #64748b; font-weight: 700; text-transform: uppercase;">
                        {{ $viewMode === 'today' ? 'Eskul Terpantau Hari Ini' : 'Total Ekstrakurikuler' }}
                    </span>
                    <h3 style="font-size: 1.85rem; font-weight: 800; color: #0f172a; margin: 4px 0 0 0;">{{ $totalEskuls }}</h3>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-basketball-ball"></i>
                </div>
            </div>
            @if($viewMode === 'today')
            <div style="margin-top: 8px; font-size: 0.8rem; color: #64748b;">
                <i class="far fa-clock"></i> Sesi: <strong>{{ $fullDateIndo }}</strong>
            </div>
            @endif
        </div>

        <div class="card" style="padding: 20px; border-radius: 16px; background: white; border-left: 5px solid #10b981; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 0;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 0.82rem; color: #64748b; font-weight: 700; text-transform: uppercase;">
                        {{ $viewMode === 'today' ? 'Tertib Hari Ini' : 'Lengkap & Patuh' }}
                    </span>
                    <h3 style="font-size: 1.85rem; font-weight: 800; color: #047857; margin: 4px 0 0 0;">{{ $completeCount }}</h3>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div style="margin-top: 8px; font-size: 0.8rem; color: #059669; font-weight: 600;">
                <i class="fas fa-shield-alt"></i> Administrasi beres & tercatat
            </div>
        </div>

        <div class="card" style="padding: 20px; border-radius: 16px; background: white; border-left: 5px solid #ef4444; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 0;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 0.82rem; color: #64748b; font-weight: 700; text-transform: uppercase;">
                        {{ $viewMode === 'today' ? 'Belum Lengkap Hari Ini' : 'Butuh Tindakan' }}
                    </span>
                    <h3 style="font-size: 1.85rem; font-weight: 800; color: #dc2626; margin: 4px 0 0 0;">{{ $actionNeededCount }}</h3>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <div style="margin-top: 8px; font-size: 0.8rem; color: #dc2626; font-weight: 600;">
                <i class="fas fa-bell"></i> Perlu diingatkan ke pembina
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar Section -->
    <div class="card" style="padding: 20px 24px; border-radius: 16px; margin-bottom: 24px; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <form method="GET" action="{{ route('compliance.index') }}" id="complianceFilterForm">
            <input type="hidden" name="mode" value="{{ $viewMode }}">
            
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                
                <!-- Status Pills -->
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <a href="{{ route('compliance.index', ['mode' => $viewMode, 'date' => $selectedDate, 'status' => 'all', 'search' => $searchKeyword, 'only_scheduled' => $onlyScheduled ? 1 : 0]) }}" 
                       style="padding: 8px 16px; border-radius: 10px; font-size: 0.88rem; font-weight: 700; text-decoration: none; {{ $statusFilter === 'all' ? 'background: #1e293b; color: white;' : 'background: #f1f5f9; color: #64748b;' }}">
                        Semua ({{ $totalEskuls }})
                    </a>
                    
                    <a href="{{ route('compliance.index', ['mode' => $viewMode, 'date' => $selectedDate, 'status' => 'action_needed', 'search' => $searchKeyword, 'only_scheduled' => $onlyScheduled ? 1 : 0]) }}" 
                       style="padding: 8px 16px; border-radius: 10px; font-size: 0.88rem; font-weight: 700; text-decoration: none; {{ $statusFilter === 'action_needed' ? 'background: #dc2626; color: white;' : 'background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;' }}">
                        <i class="fas fa-bell"></i> Butuh Tindakan ({{ $actionNeededCount }})
                    </a>
                    
                    <a href="{{ route('compliance.index', ['mode' => $viewMode, 'date' => $selectedDate, 'status' => 'complete', 'search' => $searchKeyword, 'only_scheduled' => $onlyScheduled ? 1 : 0]) }}" 
                       style="padding: 8px 16px; border-radius: 10px; font-size: 0.88rem; font-weight: 700; text-decoration: none; {{ $statusFilter === 'complete' ? 'background: #059669; color: white;' : 'background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;' }}">
                        <i class="fas fa-check-double"></i> Lengkap ({{ $completeCount }})
                    </a>
                </div>

                <!-- Date Picker & Scheduled Toggle (Khusus Mode Today) -->
                @if($viewMode === 'today')
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 6px 12px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <label for="dateFilter" style="font-size: 0.82rem; font-weight: 700; color: #475569; margin: 0;">
                            <i class="fas fa-calendar-alt" style="color: #0284c7;"></i> Tanggal Sesi:
                        </label>
                        <input type="date" id="dateFilter" name="date" value="{{ $selectedDate }}" onchange="document.getElementById('complianceFilterForm').submit();"
                               style="border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px 8px; font-size: 0.85rem; font-weight: 600; color: #0f172a; outline: none;">
                    </div>

                    <label style="display: flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 700; color: #334155; cursor: pointer; user-select: none;">
                        <input type="checkbox" name="only_scheduled" value="1" {{ $onlyScheduled ? 'checked' : '' }} onchange="document.getElementById('complianceFilterForm').submit();" style="width: 16px; height: 16px; accent-color: #0284c7;">
                        <span>Hanya eskul terjadwal hari ini</span>
                    </label>
                </div>
                @endif

                <!-- Search Input -->
                <div style="display: flex; align-items: center; gap: 8px;">
                    <input type="text" name="search" value="{{ $searchKeyword }}" placeholder="Cari eskul atau nama pembina..." 
                           style="padding: 8px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem; outline: none; width: 220px;">
                    <button type="submit" style="padding: 8px 16px; background: #0284c7; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.88rem; cursor: pointer;">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(!empty($searchKeyword) || $statusFilter !== 'all' || ($viewMode === 'today' && $selectedDate !== now()->toDateString()))
                        <a href="{{ route('compliance.index', ['mode' => $viewMode]) }}" style="padding: 8px 12px; background: #f1f5f9; color: #64748b; border-radius: 10px; font-size: 0.88rem; text-decoration: none; font-weight: 700;">
                            Reset
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    <!-- Main Table -->
    <div class="card" style="padding: 0; border-radius: 16px; overflow: hidden; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569;">
                        <th style="padding: 16px 20px; font-weight: 700;">Ekstrakurikuler & Pembina</th>
                        <th style="padding: 16px 20px; font-weight: 700; text-align: center;">
                            {{ $viewMode === 'today' ? 'Absensi Siswa Hari Ini' : 'Absensi Siswa (30 Hari)' }}
                        </th>
                        <th style="padding: 16px 20px; font-weight: 700; text-align: center;">
                            {{ $viewMode === 'today' ? 'Absensi Guru Pembina' : 'Absensi Guru (Total Hadir)' }}
                        </th>
                        @if($viewMode === 'cumulative')
                        <th style="padding: 16px 20px; font-weight: 700; text-align: center;">Nilai Siswa</th>
                        @endif
                        <th style="padding: 16px 20px; font-weight: 700;">Status Kepatuhan</th>
                        <th style="padding: 16px 20px; font-weight: 700; text-align: center;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complianceData as $item)
                        @php
                            $esk = $item['eskul'];
                            $pembina = $item['pembina'];
                            $needsAction = $item['needs_action'];
                            $reasons = $item['action_reasons'];
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9; background: {{ $needsAction ? '#fffdfd' : 'white' }};">
                            
                            <!-- Kolom 1: Eskul & Pembina -->
                            <td style="padding: 16px 20px;">
                                <div style="font-weight: 800; font-size: 1rem; color: #0f172a; margin-bottom: 4px;">
                                    {{ $esk->name }}
                                </div>
                                <div style="font-size: 0.83rem; color: #64748b; display: flex; align-items: center; gap: 8px;">
                                    <span><i class="fas fa-user-tie" style="color: #94a3b8;"></i> {{ $pembina ? $pembina->name : ($esk->instructor_name ?? 'Belum ditentukan') }}</span>
                                </div>
                                @if($pembina && $pembina->phone)
                                    <div style="font-size: 0.78rem; color: #059669; font-weight: 600; margin-top: 2px;">
                                        <i class="fab fa-whatsapp"></i> {{ $pembina->phone }}
                                    </div>
                                @endif
                                <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 2px;">
                                    <i class="far fa-clock"></i> {{ $esk->schedule ?? 'Jadwal belum diset' }}
                                </div>
                            </td>

                            <!-- Kolom 2: Absensi Siswa -->
                            <td style="padding: 16px 20px; text-align: center; vertical-align: middle;">
                                @if($item['student_attendance_complete'])
                                    <span style="background: #ecfdf5; color: #047857; font-size: 0.78rem; padding: 6px 12px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-check-circle"></i> Sudah Diisi
                                    </span>
                                @else
                                    <span style="background: #fde8e8; color: #dc2626; font-size: 0.78rem; padding: 6px 12px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-times-circle"></i> Belum Diisi
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom 3: Absensi Guru Pembina -->
                            <td style="padding: 16px 20px; text-align: center; vertical-align: middle;">
                                @if(!$item['missing_teacher_attendance'])
                                    <span style="background: #ecfdf5; color: #047857; font-size: 0.78rem; padding: 6px 12px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-check-circle"></i> Sudah Absen
                                    </span>
                                @else
                                    <span style="background: #fef3c7; color: #d97706; font-size: 0.78rem; padding: 6px 12px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-exclamation-circle"></i> Belum Absen
                                    </span>
                                @endif
                            </td>

                            @if($viewMode === 'cumulative')
                            <!-- Kolom 4: Nilai Siswa (Mode Akumulatif) -->
                            <td style="padding: 16px 20px; text-align: center; vertical-align: middle;">
                                @if($item['grades_complete'])
                                    <span style="background: #ecfdf5; color: #047857; font-size: 0.78rem; padding: 6px 12px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-star"></i> Lengkap
                                    </span>
                                @else
                                    @php
                                        $ungraded = max(0, $item['enrolled_students_count'] - $item['graded_students_count']);
                                    @endphp
                                    <span style="background: #eff6ff; color: #2563eb; font-size: 0.78rem; padding: 6px 12px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-edit"></i> {{ $ungraded }} Belum Dinilai
                                    </span>
                                @endif
                            </td>
                            @endif

                            <!-- Kolom 5: Status Kepatuhan & Rincian -->
                            <td style="padding: 16px 20px; vertical-align: middle;">
                                @if($needsAction)
                                    <div style="display: inline-flex; align-items: center; gap: 6px; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 800; margin-bottom: 6px;">
                                        <i class="fas fa-bell"></i> BUTUH TINDAKAN
                                    </div>
                                    <ul style="margin: 4px 0 0 0; padding-left: 18px; font-size: 0.78rem; color: #475569; line-height: 1.4;">
                                        @foreach($reasons as $reason)
                                            <li>{{ $reason }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div style="display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 800;">
                                        <i class="fas fa-check-double"></i> TERTIB / LENGKAP
                                    </div>
                                    <div style="font-size: 0.78rem; color: #64748b; margin-top: 4px;">
                                        Administrasi sesi ini terpenuhi
                                    </div>
                                @endif
                            </td>

                            <!-- Kolom 6: Tindakan Kontak Pembina -->
                            <td style="padding: 16px 20px; text-align: center; vertical-align: middle;">
                                @if($needsAction && $pembina && $pembina->phone)
                                    @php
                                        $phone = trim($pembina->phone);
                                        if (str_starts_with($phone, '0')) {
                                            $phone = '62' . substr($phone, 1);
                                        }
                                        $waUrl = "https://api.whatsapp.com/send?phone={$phone}&text=" . rawurlencode($item['wa_message']);
                                    @endphp
                                    <a href="{{ $waUrl }}" target="_blank" 
                                       style="background: #10b981; color: white; padding: 8px 14px; font-size: 0.8rem; font-weight: 700; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25); transition: all 0.2s;"
                                       title="Ingatkan via WhatsApp">
                                        <i class="fab fa-whatsapp" style="font-size: 0.95rem;"></i> Hubungi
                                    </a>
                                @elseif($needsAction)
                                    <span style="font-size: 0.78rem; color: #94a3b8; font-style: italic; font-weight: 600;">
                                        No. WA belum terdaftar
                                    </span>
                                @else
                                    <span style="font-size: 0.8rem; color: #10b981; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-heart"></i> Tertib
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $viewMode === 'cumulative' ? 6 : 5 }}" style="padding: 40px 20px; text-align: center; color: #94a3b8;">
                                <i class="fas fa-calendar-check" style="font-size: 2.5rem; margin-bottom: 12px; color: #cbd5e1; display: block;"></i>
                                <span style="font-weight: 700; font-size: 1rem; color: #475569; display: block; margin-bottom: 4px;">
                                    Tidak ada data yang cocok dengan kriteria filter.
                                </span>
                                @if($viewMode === 'today' && $onlyScheduled)
                                    <span style="font-size: 0.85rem; color: #64748b;">
                                        Mungkin tidak ada kegiatan eskul yang dijadwalkan pada hari ini ({{ $fullDateIndo }}). Coba nonaktifkan centang <em>"Hanya eskul terjadwal hari ini"</em>.
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: INGATKAN PEMBINA (REKAP DAFTAR & BROADCAST WHATSAPP)                 -->
<!-- ========================================================================= -->
<div id="reminderModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); z-index: 99999; backdrop-filter: blur(5px); justify-content: center; align-items: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 760px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid #e2e8f0; animation: modalFadeIn 0.25s ease-out;">
        
        <!-- Modal Header -->
        <div style="padding: 24px 28px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: #fafafa; border-radius: 20px 20px 0 0;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 46px; height: 46px; border-radius: 14px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #0f172a;">
                        Ingatkan Pembina Eskul
                    </h3>
                    <p style="margin: 3px 0 0 0; font-size: 0.85rem; color: #64748b;">
                        Pelaksanaan: <strong>{{ $fullDateIndo }}</strong> • Total: <strong style="color: #dc2626;">{{ $actionNeededList->count() }} Pembina Belum Lengkap</strong>
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeReminderModal()" style="background: none; border: none; font-size: 1.6rem; color: #94a3b8; cursor: pointer; padding: 4px 8px; line-height: 1;" title="Tutup">&times;</button>
        </div>

        <div style="padding: 24px 28px;">
            
            <!-- Box 1: Draf Pesan Siaran WhatsApp (Untuk Grup Guru / Pembina) -->
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 18px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 10px;">
                    <span style="font-size: 0.88rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-bullhorn" style="color: #0284c7;"></i> Draf Rekapan Siaran WhatsApp (Broadcast Grup)
                    </span>
                    <button type="button" onclick="copyBroadcastText()" id="btnCopyBroadcast" 
                            style="background: #0284c7; color: white; border: none; padding: 6px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                        <i class="far fa-copy"></i> <span id="btnCopyText">Salin Teks Pesan</span>
                    </button>
                </div>
                
                <textarea id="broadcastTextArea" readonly rows="8" 
                          style="width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px 14px; font-family: monospace; font-size: 0.85rem; background: white; color: #334155; resize: vertical; line-height: 1.5;">{{ $broadcastMessage }}</textarea>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                    <span style="font-size: 0.78rem; color: #64748b;">
                        <i class="fas fa-info-circle"></i> Teks ini sudah memuat salam, nama eskul, nama pembina, dan rincian yang belum lengkap.
                    </span>
                    @php
                        $waBroadcastUrl = "https://api.whatsapp.com/send?text=" . rawurlencode($broadcastMessage);
                    @endphp
                    <a href="{{ $waBroadcastUrl }}" target="_blank" 
                       style="background: #10b981; color: white; padding: 6px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fab fa-whatsapp"></i> Buka WhatsApp
                    </a>
                </div>
            </div>

            <!-- Box 2: Daftar Pembina & Chat WA Personal (Japri) -->
            <div>
                <h4 style="margin: 0 0 14px 0; font-size: 1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-users-cog" style="color: #6366f1;"></i> Daftar Pembina yang Perlu Dihubungi Pribadi (Japri):
                </h4>

                @if($actionNeededList->isEmpty())
                    <div style="background: #ecfdf5; color: #047857; padding: 16px; border-radius: 12px; text-align: center; font-weight: 700; font-size: 0.92rem;">
                        <i class="fas fa-check-circle" style="font-size: 1.2rem; vertical-align: middle; margin-right: 6px;"></i>
                        Luar biasa! Tidak ada pembina yang belum melengkapi administrasi pada sesi ini.
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 10px; max-height: 280px; overflow-y: auto; padding-right: 4px;">
                        @foreach($actionNeededList as $act)
                            @php
                                $pmb = $act['pembina'];
                                $esk = $act['eskul'];
                                $pmbName = $pmb ? $pmb->name : ($esk->instructor_name ?? 'Belum ditentukan');
                                $phoneClean = $pmb && $pmb->phone ? preg_replace('/[^0-9]/', '', $pmb->phone) : null;
                                if ($phoneClean && str_starts_with($phoneClean, '0')) {
                                    $phoneClean = '62' . substr($phoneClean, 1);
                                }
                                $personalWaUrl = $phoneClean ? "https://api.whatsapp.com/send?phone={$phoneClean}&text=" . rawurlencode($act['wa_message']) : null;
                            @endphp
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #fff5f5; border: 1px solid #fed7d7; border-radius: 12px; gap: 12px; flex-wrap: wrap;">
                                <div>
                                    <div style="font-weight: 800; color: #1e293b; font-size: 0.95rem;">
                                        {{ $esk->name }} <span style="font-weight: 500; color: #64748b;">— {{ $pmbName }}</span>
                                    </div>
                                    <div style="display: flex; gap: 6px; margin-top: 4px; flex-wrap: wrap;">
                                        @if(!$act['student_attendance_complete'])
                                            <span style="font-size: 0.73rem; background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 6px; font-weight: 700;">
                                                <i class="fas fa-times-circle"></i> Absen Siswa Belum
                                            </span>
                                        @endif
                                        @if($act['missing_teacher_attendance'])
                                            <span style="font-size: 0.73rem; background: #fef3c7; color: #b45309; padding: 2px 8px; border-radius: 6px; font-weight: 700;">
                                                <i class="fas fa-exclamation-circle"></i> Absen Guru Belum
                                            </span>
                                        @endif
                                        @if($pmb && $pmb->phone)
                                            <span style="font-size: 0.73rem; color: #059669; font-weight: 600; padding: 2px 0;">
                                                <i class="fab fa-whatsapp"></i> {{ $pmb->phone }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    @if($personalWaUrl)
                                        <a href="{{ $personalWaUrl }}" target="_blank" 
                                           style="background: #10b981; color: white; padding: 7px 14px; font-size: 0.8rem; font-weight: 700; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);">
                                            <i class="fab fa-whatsapp"></i> Chat Japri
                                        </a>
                                    @else
                                        <span style="font-size: 0.75rem; color: #94a3b8; font-style: italic;">
                                            No WA Tidak Ada
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Modal Footer -->
        <div style="padding: 16px 28px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; border-radius: 0 0 20px 20px;">
            <button type="button" onclick="closeReminderModal()" style="padding: 9px 20px; background: #e2e8f0; color: #475569; border: none; border-radius: 10px; font-weight: 700; font-size: 0.88rem; cursor: pointer;">
                Tutup
            </button>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function openReminderModal() {
        const modal = document.getElementById('reminderModal');
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function closeReminderModal() {
        const modal = document.getElementById('reminderModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function copyBroadcastText() {
        const textArea = document.getElementById('broadcastTextArea');
        if (!textArea) return;

        textArea.select();
        textArea.setSelectionRange(0, 99999); // Mobile support

        navigator.clipboard.writeText(textArea.value).then(() => {
            const btnText = document.getElementById('btnCopyText');
            const originalText = btnText.innerText;
            btnText.innerText = 'Tersalin ke Clipboard! ✓';
            const btn = document.getElementById('btnCopyBroadcast');
            btn.style.background = '#10b981';

            setTimeout(() => {
                btnText.innerText = originalText;
                btn.style.background = '#0284c7';
            }, 2500);
        }).catch(err => {
            alert('Gagal menyalin teks, silakan salin secara manual.');
        });
    }

    // Close on outside click
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('reminderModal');
        if (event.target === modal) {
            closeReminderModal();
        }
    });

    // Close on Escape key
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeReminderModal();
        }
    });
</script>
<style>
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.96);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
@endpush
@endsection
