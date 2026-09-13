@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding-bottom: 40px;">
    
    <!-- Hero / Header Section -->
    <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; padding: 28px 32px; border-radius: 20px; margin-bottom: 24px; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px;">
                <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; border: 1px solid rgba(52, 211, 153, 0.3);">
                    <i class="fas fa-shield-alt"></i> Pemantauan Administrasi
                </span>
                <span style="font-size: 0.85rem; color: #94a3b8; font-weight: 600;">
                    Tahun Ajaran: {{ $activeYear ? $activeYear->name : '-' }} (Semester {{ $activeYear ? $activeYear->semester : '-' }})
                </span>
            </div>
            <h2 style="font-size: 1.65rem; font-weight: 800; margin: 0; color: #f8fafc;">
                Kepatuhan Administrasi Pembina
            </h2>
            <p style="margin: 6px 0 0 0; color: #94a3b8; font-size: 0.92rem;">
                Pantau kelengkapan Absensi Guru, Absensi Siswa, dan Input Nilai Siswa secara terpadu tanpa tercecer.
            </p>
        </div>
    </div>

    <!-- Stat Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-bottom: 28px;">
        <div class="card" style="padding: 20px; border-radius: 16px; background: white; border-left: 5px solid #3b82f6; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 0.85rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Total Ekstrakurikuler</span>
                    <h3 style="font-size: 1.8rem; font-weight: 800; color: #1e293b; margin: 4px 0 0 0;">{{ $totalEskuls }}</h3>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-basketball-ball"></i>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 20px; border-radius: 16px; background: white; border-left: 5px solid #10b981; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 0.85rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Lengkap & Patuh</span>
                    <h3 style="font-size: 1.8rem; font-weight: 800; color: #047857; margin: 4px 0 0 0;">{{ $completeCount }}</h3>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 20px; border-radius: 16px; background: white; border-left: 5px solid #ef4444; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 0.85rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Butuh Tindakan</span>
                    <h3 style="font-size: 1.8rem; font-weight: 800; color: #dc2626; margin: 4px 0 0 0;">{{ $actionNeededCount }}</h3>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card" style="padding: 18px 24px; border-radius: 16px; margin-bottom: 24px; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <form method="GET" action="{{ route('compliance.index') }}" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
            
            <!-- Status Filter Tabs / Buttons -->
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <a href="{{ route('compliance.index', ['status' => 'all', 'search' => $searchKeyword]) }}" 
                   style="padding: 8px 18px; border-radius: 10px; font-size: 0.88rem; font-weight: 700; text-decoration: none; transition: all 0.2s; {{ $statusFilter === 'all' ? 'background: #1e293b; color: white;' : 'background: #f1f5f9; color: #64748b;' }}">
                    Semua ({{ $totalEskuls }})
                </a>
                <a href="{{ route('compliance.index', ['status' => 'action_needed', 'search' => $searchKeyword]) }}" 
                   style="padding: 8px 18px; border-radius: 10px; font-size: 0.88rem; font-weight: 700; text-decoration: none; transition: all 0.2s; {{ $statusFilter === 'action_needed' ? 'background: #dc2626; color: white;' : 'background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;' }}">
                    <i class="fas fa-bell"></i> Butuh Tindakan ({{ $actionNeededCount }})
                </a>
                <a href="{{ route('compliance.index', ['status' => 'complete', 'search' => $searchKeyword]) }}" 
                   style="padding: 8px 18px; border-radius: 10px; font-size: 0.88rem; font-weight: 700; text-decoration: none; transition: all 0.2s; {{ $statusFilter === 'complete' ? 'background: #059669; color: white;' : 'background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;' }}">
                    <i class="fas fa-check-double"></i> Lengkap ({{ $completeCount }})
                </a>
            </div>

            <!-- Search Field -->
            <div style="display: flex; align-items: center; gap: 8px;">
                <input type="text" name="search" value="{{ $searchKeyword }}" placeholder="Cari eskul atau nama guru..." 
                       style="padding: 8px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem; outline: none; width: 240px;">
                <button type="submit" style="padding: 8px 16px; background: #2563eb; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.88rem; cursor: pointer;">
                    <i class="fas fa-search"></i> Cari
                </button>
                @if(!empty($searchKeyword) || $statusFilter !== 'all')
                    <a href="{{ route('compliance.index') }}" style="padding: 8px 12px; background: #f1f5f9; color: #64748b; border-radius: 10px; font-size: 0.88rem; text-decoration: none; font-weight: 700;">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Main Compliance Table -->
    <div class="card" style="padding: 0; border-radius: 16px; overflow: hidden; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569;">
                        <th style="padding: 16px 20px; font-weight: 700;">Ekstrakurikuler & Pembina</th>
                        <th style="padding: 16px 20px; font-weight: 700; text-align: center;">Absensi Siswa</th>
                        <th style="padding: 16px 20px; font-weight: 700; text-align: center;">Absensi Guru</th>
                        <th style="padding: 16px 20px; font-weight: 700; text-align: center;">Nilai Siswa</th>
                        <th style="padding: 16px 20px; font-weight: 700;">Status Administrasi</th>
                        <th style="padding: 16px 20px; font-weight: 700; text-align: center;">Aksi Pembina</th>
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
                            
                            <!-- Column 1: Eskul & Pembina -->
                            <td style="padding: 16px 20px;">
                                <div style="font-weight: 800; font-size: 1rem; color: #0f172a; margin-bottom: 4px;">
                                    {{ $esk->name }}
                                </div>
                                <div style="font-size: 0.83rem; color: #64748b; display: flex; align-items: center; gap: 8px;">
                                    <span><i class="fas fa-user-tie" style="color: #94a3b8;"></i> {{ $esk->instructor_name ?? 'Belum ditentukan' }}</span>
                                </div>
                                @if($pembina && $pembina->phone)
                                    <div style="font-size: 0.78rem; color: #059669; font-weight: 600; margin-top: 2px;">
                                        <i class="fab fa-whatsapp"></i> WA: {{ $pembina->phone }}
                                    </div>
                                @endif
                                <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 2px;">
                                    <i class="far fa-clock"></i> {{ $esk->schedule ?? 'Jadwal belum diset' }}
                                </div>
                            </td>

                            <!-- Column 2: Absensi Siswa -->
                            <td style="padding: 16px 20px; text-align: center; vertical-align: middle;">
                                @if($item['student_attendance_complete'])
                                    <span style="background: #ecfdf5; color: #047857; font-size: 0.78rem; padding: 6px 12px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-check-circle"></i> Lengkap
                                    </span>
                                @else
                                    <span style="background: #fde8e8; color: #dc2626; font-size: 0.78rem; padding: 6px 12px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;" title="Tanggal terlewat: {{ implode(', ', $item['missing_student_dates']) }}">
                                        <i class="fas fa-times-circle"></i> {{ count($item['missing_student_dates']) }} Terlewat
                                    </span>
                                @endif
                            </td>

                            <!-- Column 3: Absensi Guru -->
                            <td style="padding: 16px 20px; text-align: center; vertical-align: middle;">
                                @if(!$item['missing_teacher_attendance'])
                                    <span style="background: #ecfdf5; color: #047857; font-size: 0.78rem; padding: 6px 12px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-check-circle"></i> Tercatat ({{ $item['teacher_attendance_count'] }})
                                    </span>
                                @else
                                    <span style="background: #fef3c7; color: #d97706; font-size: 0.78rem; padding: 6px 12px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-exclamation-circle"></i> Belum Absen
                                    </span>
                                @endif
                            </td>

                            <!-- Column 4: Nilai Siswa -->
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

                            <!-- Column 5: Status Overall & Keterangan -->
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
                                        <i class="fas fa-check-double"></i> LENGKAP / PATUH
                                    </div>
                                    <div style="font-size: 0.78rem; color: #64748b; margin-top: 4px;">
                                        Seluruh administrasi tertib
                                    </div>
                                @endif
                            </td>

                            <!-- Column 6: Aksi Hubungi Pembina -->
                            <td style="padding: 16px 20px; text-align: center; vertical-align: middle;">
                                @if($needsAction && $pembina && $pembina->phone)
                                    @php
                                        $phone = trim($pembina->phone);
                                        if (strpos($phone, '0') === 0) {
                                            $phone = '62' . substr($phone, 1);
                                        }
                                        $waUrl = "https://api.whatsapp.com/send?phone={$phone}&text=" . rawurlencode($item['wa_message']);
                                    @endphp
                                    <a href="{{ $waUrl }}" target="_blank" 
                                       style="background: #10b981; color: white; padding: 8px 16px; font-size: 0.82rem; font-weight: 700; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25); transition: all 0.2s;">
                                        <i class="fab fa-whatsapp" style="font-size: 1rem;"></i> Hubungi
                                    </a>
                                @elseif($needsAction)
                                    <span style="font-size: 0.78rem; color: #94a3b8; font-style: italic; font-weight: 600;">
                                        No. WA Pembina belum terdaftar
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
                            <td colspan="6" style="padding: 40px 20px; text-align: center; color: #94a3b8;">
                                <i class="fas fa-folder-open" style="font-size: 2.5rem; margin-bottom: 12px; color: #cbd5e1; display: block;"></i>
                                <span style="font-weight: 600;">Tidak ada data kepatuhan yang cocok dengan filter pencarian Anda.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
