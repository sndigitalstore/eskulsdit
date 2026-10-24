@extends('layouts.app')

@section('title', 'Munaqisy & Munaqosah Qur\'an')
@section('page-title', 'Munaqisy & Munaqosah Qur\'an')

@section('content')
<div style="padding-bottom: 40px;">

    <!-- Hero Header -->
    <div style="background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0d9488 100%); border-radius: 20px; padding: 26px 30px; color: white; margin-bottom: 25px; box-shadow: 0 10px 25px -5px rgba(6, 95, 70, 0.3); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; position: relative; overflow: hidden;">
        <div style="z-index: 2;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <span style="background: rgba(255,255,255,0.2); font-size: 0.78rem; font-weight: 800; padding: 3px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.05em;">
                    <i class="fas fa-award"></i> Standar Penjamin Mutu Qur'an
                </span>
                <span style="background: rgba(255,255,255,0.15); font-size: 0.78rem; font-weight: 600; padding: 3px 10px; border-radius: 20px;">
                    Tahun Ajaran {{ $activeYear->name ?? '-' }}
                </span>
            </div>
            <h2 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 6px 0; letter-spacing: -0.5px;">
                Munaqisy & Evaluasi Munaqosah
            </h2>
            <p style="margin: 0; font-size: 0.92rem; opacity: 0.92; max-width: 650px; line-height: 1.5;">
                Kelola pendaftaran siswa tuntas juz tahfidz & jilid tilawati, manajemen mentor penguji (munaqisy), penilaian kelulusan berstandar, serta pemindahan otomatis ke jenjang lanjutan.
            </p>
        </div>
        <div style="z-index: 2; display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('munaqosah.register') }}" class="btn-action-header" style="background: #ffffff; color: #065f46; font-weight: 800; padding: 10px 18px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus-circle"></i> Ajukan Siswa Munaqosah
            </a>
            <button type="button" onclick="shareWhatsAppRegistrationLink()" class="btn-action-header" style="background: #25d366; color: white; font-weight: 800; padding: 10px 16px; border-radius: 12px; border: none; box-shadow: 0 4px 15px rgba(37,211,102,0.3); display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                <i class="fab fa-whatsapp"></i> Bagikan Link Online (WA)
            </button>
            <a href="{{ route('munaqosah.print-recap', ['year_id' => $selectedYearId, 'status' => $statusFilter, 'type' => $typeFilter]) }}" target="_blank" class="btn-action-header" style="background: rgba(255,255,255,0.22); color: white; font-weight: 700; padding: 10px 16px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.4); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-print"></i> Cetak Rekap
            </a>
        </div>
        <i class="fas fa-quran" style="position: absolute; right: -15px; bottom: -25px; font-size: 10rem; color: rgba(255,255,255,0.06); z-index: 1;"></i>
    </div>

    <!-- Alert Success / Error -->
    @if(session('success'))
        <div style="background: #ecfdf5; border-left: 5px solid #10b981; padding: 14px 18px; border-radius: 12px; margin-bottom: 22px; color: #065f46; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);">
            <div style="display: flex; align-items: center; gap: 10px; font-weight: 600;">
                <i class="fas fa-check-circle" style="font-size: 1.25rem; color: #10b981;"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #065f46; font-size: 1.1rem; cursor: pointer;">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; border-left: 5px solid #ef4444; padding: 14px 18px; border-radius: 12px; margin-bottom: 22px; color: #991b1b; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);">
            <div style="display: flex; align-items: center; gap: 10px; font-weight: 600;">
                <i class="fas fa-exclamation-circle" style="font-size: 1.25rem; color: #ef4444;"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #991b1b; font-size: 1.1rem; cursor: pointer;">&times;</button>
        </div>
    @endif

    <!-- KPI Metric Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 25px;">
        <!-- Total Registrations -->
        <div class="card" style="background: white; border-radius: 16px; padding: 18px 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Pendaftar</span>
                <h3 style="font-size: 1.85rem; font-weight: 800; color: #0f172a; margin: 4px 0 0 0;">{{ $totalCount }}</h3>
            </div>
            <div style="width: 46px; height: 46px; border-radius: 12px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>

        <!-- Menunggu Ujian -->
        <div class="card" style="background: white; border-radius: 16px; padding: 18px 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.8rem; font-weight: 700; color: #f59e0b; text-transform: uppercase;">Menunggu Ujian</span>
                <h3 style="font-size: 1.85rem; font-weight: 800; color: #d97706; margin: 4px 0 0 0;">{{ $pendingCount + $scheduledCount }}</h3>
            </div>
            <div style="width: 46px; height: 46px; border-radius: 12px; background: #fffbeb; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>

        <!-- Lulus Munaqosah -->
        <div class="card" style="background: white; border-radius: 16px; padding: 18px 20px; border: 1px solid #a7f3d0; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.08); display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.8rem; font-weight: 700; color: #059669; text-transform: uppercase;">Lulus Munaqosah</span>
                <h3 style="font-size: 1.85rem; font-weight: 800; color: #047857; margin: 4px 0 0 0;">{{ $passedCount }}</h3>
            </div>
            <div style="width: 46px; height: 46px; border-radius: 12px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                <i class="fas fa-check-double"></i>
            </div>
        </div>

        <!-- Belum Lulus / Mengulang -->
        <div class="card" style="background: white; border-radius: 16px; padding: 18px 20px; border: 1px solid #fecaca; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.05); display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.8rem; font-weight: 700; color: #dc2626; text-transform: uppercase;">Belum Lulus</span>
                <h3 style="font-size: 1.85rem; font-weight: 800; color: #b91c1c; margin: 4px 0 0 0;">{{ $failedCount }}</h3>
            </div>
            <div style="width: 46px; height: 46px; border-radius: 12px; background: #fef2f2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                <i class="fas fa-redo-alt"></i>
            </div>
        </div>

        <!-- Dewan Penguji (Munaqisy) -->
        <div class="card" style="background: white; border-radius: 16px; padding: 18px 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.8rem; font-weight: 700; color: #6366f1; text-transform: uppercase;">Mentor Penguji</span>
                <h3 style="font-size: 1.85rem; font-weight: 800; color: #4f46e5; margin: 4px 0 0 0;">{{ $examinersCount }}</h3>
            </div>
            <div style="width: 46px; height: 46px; border-radius: 12px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs & Filters -->
    <div style="background: white; border-radius: 18px; padding: 18px 20px; border: 1px solid #e2e8f0; margin-bottom: 22px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px;">
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('munaqosah.index', ['tab' => 'recap', 'status' => 'all', 'year_id' => $selectedYearId]) }}" 
               style="padding: 8px 16px; border-radius: 10px; font-size: 0.86rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; {{ $activeTab === 'recap' ? 'background: #047857; color: white;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="fas fa-list-alt"></i> Semua Rekapan Data ({{ $totalCount }})
            </a>
            <a href="{{ route('munaqosah.index', ['tab' => 'recap', 'status' => 'passed', 'year_id' => $selectedYearId]) }}" 
               style="padding: 8px 16px; border-radius: 10px; font-size: 0.86rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; {{ $statusFilter === 'passed' ? 'background: #10b981; color: white;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="fas fa-check-circle"></i> Sudah Lulus ({{ $passedCount }})
            </a>
            <a href="{{ route('munaqosah.index', ['tab' => 'recap', 'status' => 'failed', 'year_id' => $selectedYearId]) }}" 
               style="padding: 8px 16px; border-radius: 10px; font-size: 0.86rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; {{ $statusFilter === 'failed' ? 'background: #ef4444; color: white;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="fas fa-times-circle"></i> Belum Lulus ({{ $failedCount }})
            </a>
            <a href="{{ route('munaqosah.index', ['tab' => 'schedules', 'year_id' => $selectedYearId]) }}" 
               style="padding: 8px 16px; border-radius: 10px; font-size: 0.86rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; {{ $activeTab === 'schedules' ? 'background: #0284c7; color: white;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="fas fa-calendar-check"></i> Jadwal & Ketersediaan Penguji ({{ $schedulesCount }})
            </a>
            <a href="{{ route('munaqosah.index', ['tab' => 'examiners', 'year_id' => $selectedYearId]) }}" 
               style="padding: 8px 16px; border-radius: 10px; font-size: 0.86rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; {{ $activeTab === 'examiners' ? 'background: #4f46e5; color: white;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="fas fa-user-graduate"></i> Dewan Penguji ({{ $examinersCount }})
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('munaqosah.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <input type="hidden" name="status" value="{{ $statusFilter }}">

            <select name="type" onchange="this.form.submit()" style="padding: 8px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.84rem; background: #f8fafc; font-weight: 600; color: #334155;">
                <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>Semua Tipe (Tilawati & Tahfidz)</option>
                <option value="tahfidz" {{ $typeFilter === 'tahfidz' ? 'selected' : '' }}>Hafalan Tahfidz</option>
                <option value="tilawati" {{ $typeFilter === 'tilawati' ? 'selected' : '' }}>Tilawati Al-Qur'an</option>
            </select>

            <select name="year_id" onchange="this.form.submit()" style="padding: 8px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.84rem; background: #f8fafc; font-weight: 600; color: #334155;">
                @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ $selectedYearId == $ay->id ? 'selected' : '' }}>
                        {{ $ay->name }} (Semester {{ $ay->active_semester }})
                    </option>
                @endforeach
            </select>

            <div style="position: relative;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama siswa / kelas..." 
                       style="padding: 8px 12px 8px 32px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.84rem; width: 200px;">
                <i class="fas fa-search" style="position: absolute; left: 11px; top: 11px; color: #94a3b8; font-size: 0.8rem;"></i>
            </div>

            @if($search || $typeFilter !== 'all' || $statusFilter !== 'all')
                <a href="{{ route('munaqosah.index', ['tab' => $activeTab]) }}" style="color: #ef4444; font-size: 0.82rem; font-weight: 700; text-decoration: none;">
                    <i class="fas fa-undo"></i> Reset
                </a>
            @endif
        </form>
    </div>

    @if($activeTab === 'examiners')
        {{-- TAB DEWAN PENGUJI (MUNAQISY) --}}
        <div style="background: white; border-radius: 20px; padding: 25px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                        Daftar Mentor Penguji (Dewan Munaqisy)
                    </h3>
                    <p style="color: #64748b; font-size: 0.86rem; margin: 0;">
                        Guru dan asatidz yang memiliki wewenang untuk menguji dan menilai kenaikan juz/jilid tilawati siswa.
                    </p>
                </div>
                @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                    <button type="button" onclick="openExaminerModal()" class="btn-action-header" style="background: #4f46e5; padding: 9px 18px; border-radius: 10px;">
                        <i class="fas fa-user-plus"></i> Tambah Penguji Baru
                    </button>
                @endif
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">No</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Nama Penguji</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Spesialisasi</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">No Sertifikat / SK</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Kontak WhatsApp</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Total Siswa Diuji</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Status</th>
                            @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                                <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examiners as $index => $ex)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <td style="padding: 12px 14px; color: #64748b; font-weight: 600;">{{ $index + 1 }}</td>
                                <td style="padding: 12px 14px;">
                                    <div style="font-weight: 700; color: #0f172a;">{{ $ex->user->name ?? '-' }}</div>
                                    <div style="font-size: 0.76rem; color: #64748b;">{{ $ex->user->email ?? '-' }}</div>
                                </td>
                                <td style="padding: 12px 14px;">
                                    @if($ex->specialization === 'both')
                                        <span style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 8px; font-size: 0.76rem; font-weight: 700;">
                                            <i class="fas fa-check-circle"></i> Tahfidz & Tilawati
                                        </span>
                                    @elseif($ex->specialization === 'tahfidz')
                                        <span style="background: #ecfdf5; color: #047857; padding: 4px 10px; border-radius: 8px; font-size: 0.76rem; font-weight: 700;">
                                            <i class="fas fa-star-and-crescent"></i> Khusus Tahfidz
                                        </span>
                                    @else
                                        <span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 8px; font-size: 0.76rem; font-weight: 700;">
                                            <i class="fas fa-book-reader"></i> Khusus Tilawati
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; color: #475569; font-family: monospace; font-size: 0.84rem;">
                                    {{ $ex->certificate_number ?? '-' }}
                                </td>
                                <td style="padding: 12px 14px; color: #475569;">
                                    @if($ex->phone || ($ex->user && $ex->user->phone))
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ex->phone ?? $ex->user->phone) }}" target="_blank" style="color: #059669; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fab fa-whatsapp"></i> {{ $ex->phone ?? $ex->user->phone }}
                                        </a>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; text-align: center; font-weight: 800; color: #0f172a;">
                                    {{ $ex->registrations_count }} Siswa
                                </td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    @if($ex->status === 'active')
                                        <span style="background: #ecfdf5; color: #059669; font-size: 0.75rem; font-weight: 800; padding: 3px 10px; border-radius: 20px;">
                                            Aktif
                                        </span>
                                    @else
                                        <span style="background: #f1f5f9; color: #64748b; font-size: 0.75rem; font-weight: 800; padding: 3px 10px; border-radius: 20px;">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                                    <td style="padding: 12px 14px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center;">
                                            <form method="POST" action="{{ route('munaqosah.examiners.toggle', $ex->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" title="{{ $ex->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}" 
                                                        style="background: #f8fafc; border: 1px solid #cbd5e1; color: #475569; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fas {{ $ex->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('munaqosah.examiners.destroy', $ex->id) }}" onsubmit="return confirm('Hapus penguji ini?')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Penguji" 
                                                        style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 35px; text-align: center; color: #94a3b8;">
                                    <i class="fas fa-user-slash" style="font-size: 2.5rem; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                                    Belum ada mentor penguji (munaqisy) yang didaftarkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($activeTab === 'schedules')
        {{-- TAB JADWAL & KETERSEDIAAN PENGUJI (MUNAQISY) --}}
        <div style="background: white; border-radius: 20px; padding: 25px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                        Jadwal & Ketersediaan Penguji (Munaqisy)
                    </h3>
                    <p style="color: #64748b; font-size: 0.86rem; margin: 0;">
                        Atur jadwal ketersediaan hari, jam, dan kuota para penguji. Jadwal ini akan muncul otomatis di formulir pendaftaran siswa.
                    </p>
                </div>
                @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                    <button type="button" onclick="openAddScheduleModal()" class="btn-action-header" style="background: #0284c7; padding: 9px 18px; border-radius: 10px; color: white; font-weight: 700; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-calendar-plus"></i> + Tambah Jadwal Penguji
                    </button>
                @endif
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; width: 45px;">No</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Nama Penguji (Munaqisy)</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Hari & Waktu</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Ruangan / Tempat</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Kategori</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Ketersediaan / Kuota</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Siswa Terdaftar di Sesi Ini</th>
                            @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                                <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $index => $sch)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <td style="padding: 12px 14px; color: #64748b; font-weight: 600;">{{ $index + 1 }}</td>
                                <td style="padding: 12px 14px;">
                                    <div style="font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <span>{{ $sch->examiner?->user?->name ?? 'Penguji' }}</span>
                                    </div>
                                    @if($sch->examiner?->phone)
                                        <div style="font-size: 0.74rem; color: #64748b; margin-left: 40px;">
                                            <i class="fab fa-whatsapp" style="color: #10b981;"></i> {{ $sch->examiner->phone }}
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px;">
                                    <div style="font-weight: 800; color: #0369a1;">
                                        <i class="far fa-calendar-alt"></i> Hari {{ $sch->day_name }}
                                        @if($sch->date)
                                            <span style="font-weight: 500; font-size: 0.78rem; color: #64748b;">({{ $sch->date->format('d/m/Y') }})</span>
                                        @endif
                                    </div>
                                    <div style="font-size: 0.82rem; color: #334155; font-weight: 600;">
                                        <i class="far fa-clock"></i> Pukul {{ $sch->time_start }} s/d {{ $sch->time_end }} WIB
                                    </div>
                                </td>
                                <td style="padding: 12px 14px; color: #475569;">
                                    <i class="fas fa-map-marker-alt" style="color: #94a3b8; margin-right: 4px;"></i>
                                    {{ $sch->room_location ?? 'Ruang Munaqosah' }}
                                </td>
                                <td style="padding: 12px 14px;">
                                    @if($sch->exam_type === 'all')
                                        <span style="background: #f1f5f9; color: #475569; padding: 3px 8px; border-radius: 6px; font-size: 0.74rem; font-weight: 700;">
                                            Semua
                                        </span>
                                    @elseif($sch->exam_type === 'tahfidz')
                                        <span style="background: #ecfdf5; color: #047857; padding: 3px 8px; border-radius: 6px; font-size: 0.74rem; font-weight: 700;">
                                            Tahfidz
                                        </span>
                                    @else
                                        <span style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 6px; font-size: 0.74rem; font-weight: 700;">
                                            Tilawati
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    @if($sch->is_full)
                                        <span style="background: #ecfdf5; color: #047857; font-weight: 800; padding: 5px 12px; border-radius: 20px; font-size: 0.78rem; border: 1.5px solid #10b981; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-check-circle" style="color: #10b981;"></i> Sudah Terpenuhi ({{ $sch->used_slots }}/{{ $sch->quota }})
                                        </span>
                                    @else
                                        <span style="background: #eff6ff; color: #1e40af; font-weight: 800; padding: 5px 12px; border-radius: 20px; font-size: 0.78rem; border: 1.5px solid #60a5fa; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-clock" style="color: #3b82f6;"></i> Tersedia ({{ $sch->used_slots }}/{{ $sch->quota }})
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px;">
                                    @if($sch->registrations->isNotEmpty())
                                        @foreach($sch->registrations as $reg)
                                            <div style="font-size: 0.8rem; color: #0f172a; font-weight: 700;">
                                                <i class="fas fa-user-check" style="color: #10b981; margin-right: 4px;"></i>
                                                {{ $reg->student->name ?? 'Siswa' }} 
                                                <span style="font-weight: normal; color: #64748b; font-size: 0.75rem;">(Kelas {{ $reg->student->class ?? '-' }} • {{ $reg->current_level }})</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span style="color: #94a3b8; font-size: 0.78rem; font-style: italic;">Belum ada siswa yang mendaftar di slot ini</span>
                                    @endif
                                </td>
                                @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                                    <td style="padding: 12px 14px; text-align: center;">
                                        <form method="POST" action="{{ route('munaqosah.schedules.destroy', $sch->id) }}" onsubmit="return confirm('Hapus slot jadwal penguji ini?')" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Slot Jadwal" 
                                                    style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 35px; text-align: center; color: #94a3b8;">
                                    <i class="fas fa-calendar-times" style="font-size: 2.5rem; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                                    Belum ada jadwal ketersediaan penguji yang ditambahkan.<br>
                                    <span style="font-size: 0.82rem; color: #64748b;">Klik tombol <strong>+ Tambah Jadwal Penguji</strong> di atas untuk mengatur ketersediaan hari dan jam penguji.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        {{-- TAB REKAPAN & DAFTAR EVALUASI MUNAQOSAH --}}
        <div style="background: white; border-radius: 20px; padding: 22px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">
                        Rekapan Peserta Munaqosah
                    </h3>
                    <span style="font-size: 0.82rem; color: #64748b;">
                        Menampilkan {{ $registrations->total() }} data pendaftaran & evaluasi
                    </span>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.86rem;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; width: 45px;">No</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Siswa & Kelas</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Tipe & Jenjang</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Pengusul (Guru)</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700;">Penguji & Jadwal</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Nilai & Predikat</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Hasil</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Status Jenjang</th>
                            <th style="padding: 12px 14px; color: #475569; font-weight: 700; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $index => $item)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <td style="padding: 12px 14px; color: #64748b; font-weight: 600;">
                                    {{ $registrations->firstItem() + $index }}
                                </td>
                                <td style="padding: 12px 14px;">
                                    <div style="font-weight: 800; color: #0f172a; font-size: 0.92rem;">
                                        {{ $item->student->name ?? 'Siswa Tidak Ditemukan' }}
                                    </div>
                                    <div style="font-size: 0.76rem; color: #64748b; display: flex; gap: 8px; align-items: center;">
                                        <span style="background: #e2e8f0; color: #334155; padding: 1px 6px; border-radius: 4px; font-weight: 700;">
                                            Kelas {{ $item->student->class ?? '-' }}
                                        </span>
                                        <span>NIS: {{ $item->student->nis ?? '-' }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 14px;">
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                                        <span style="background: {{ $item->type === 'tilawati' ? '#fef3c7' : '#ecfdf5' }}; color: {{ $item->type === 'tilawati' ? '#92400e' : '#047857' }}; padding: 2px 7px; border-radius: 6px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase;">
                                            {{ $item->type }}
                                        </span>
                                    </div>
                                    <div style="font-size: 0.84rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                        <span>{{ $item->current_level }}</span>
                                        <i class="fas fa-arrow-right" style="color: #10b981; font-size: 0.75rem;"></i>
                                        <span style="color: #059669; background: #ecfdf5; padding: 1px 6px; border-radius: 6px;">{{ $item->target_level }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 14px;">
                                    <div style="font-weight: 700; color: #334155;">{{ $item->proposer_name ?? ($item->proposer->name ?? 'Pengusul') }}</div>
                                    @if($item->proposer_phone)
                                        <div style="font-size: 0.74rem; color: #059669; font-weight: 600;">
                                            <i class="fab fa-whatsapp"></i> {{ $item->proposer_phone }}
                                        </div>
                                    @endif
                                    <div style="font-size: 0.74rem; color: #94a3b8;">
                                        {{ $item->registration_date ? $item->registration_date->isoFormat('D MMM Y') : '-' }}
                                    </div>
                                </td>
                                <td style="padding: 12px 14px;">
                                    @if($item->examinerUser || ($item->examiner && $item->examiner->user))
                                        <div style="font-weight: 700; color: #0f172a;">
                                            <i class="fas fa-user-tie" style="color: #4f46e5; margin-right: 4px;"></i>
                                            {{ $item->examinerUser->name ?? $item->examiner->user->name }}
                                        </div>
                                    @else
                                        <span style="color: #94a3b8; font-style: italic;">Belum ditentukan</span>
                                    @endif

                                    @if($item->exam_date)
                                        <div style="font-size: 0.76rem; color: #64748b; margin-top: 2px;">
                                            <i class="far fa-calendar-alt" style="margin-right: 4px;"></i>
                                            {{ $item->exam_date->isoFormat('D MMM Y') }}
                                            @if($item->exam_time) • {{ $item->exam_time }} @endif
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    @if($item->final_score !== null)
                                        <div style="font-size: 1.15rem; font-weight: 900; color: {{ $item->status === 'passed' ? '#047857' : '#b91c1c' }};">
                                            {{ number_format($item->final_score, 1) }}
                                        </div>
                                        <span style="font-size: 0.72rem; font-weight: 700; color: #475569;">
                                            {{ $item->predicate ?? '-' }}
                                        </span>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    @if($item->status === 'passed')
                                        <span style="background: #ecfdf5; color: #047857; font-size: 0.76rem; font-weight: 800; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-check-circle"></i> LULUS
                                        </span>
                                    @elseif($item->status === 'failed')
                                        <span style="background: #fef2f2; color: #b91c1c; font-size: 0.76rem; font-weight: 800; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-times-circle"></i> BELUM LULUS
                                        </span>
                                    @elseif($item->status === 'scheduled')
                                        <span style="background: #eff6ff; color: #1d4ed8; font-size: 0.76rem; font-weight: 800; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-calendar-check"></i> Terjadwal
                                        </span>
                                    @else
                                        <span style="background: #fffbeb; color: #b45309; font-size: 0.76rem; font-weight: 800; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    @if($item->status === 'passed')
                                        @if($item->transition_status === 'promoted')
                                            <span style="background: #f1f5f9; color: #475569; font-size: 0.74rem; font-weight: 700; padding: 3px 8px; border-radius: 8px;">
                                                <i class="fas fa-check"></i> Sudah Dipindah
                                            </span>
                                        @else
                                            <span style="background: #fef3c7; color: #b45309; font-size: 0.74rem; font-weight: 800; padding: 3px 8px; border-radius: 8px; animation: pulse 2s infinite;">
                                                <i class="fas fa-exclamation-circle"></i> Menunggu Pindah
                                            </span>
                                        @endif
                                    @else
                                        <span style="color: #94a3b8; font-size: 0.8rem;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; text-align: center;">
                                    <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                                        {{-- 1. Penilaian Penguji --}}
                                        <a href="{{ route('munaqosah.score', $item->id) }}" title="Form Penilaian Penguji" 
                                           style="background: #10b981; color: white; padding: 6px 10px; border-radius: 8px; text-decoration: none; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fas fa-edit"></i> {{ $item->final_score !== null ? 'Nilai' : 'Uji' }}
                                        </a>

                                        {{-- 2. Jadwalkan / Pilih Penguji (jika pending) --}}
                                        @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']) && in_array($item->status, ['pending', 'scheduled']))
                                            <button type="button" onclick="openScheduleModal({{ $item->id }}, '{{ addslashes($item->student->name ?? '') }}', '{{ $item->examiner_id ?? '' }}', '{{ $item->exam_date ? $item->exam_date->format('Y-m-d') : '' }}', '{{ $item->exam_time ?? '' }}', '{{ $item->room_location ?? '' }}')" 
                                                    title="Tetapkan Jadwal & Penguji" 
                                                    style="background: #3b82f6; color: white; border: none; padding: 6px 9px; border-radius: 8px; font-size: 0.78rem; cursor: pointer;">
                                                <i class="fas fa-calendar-alt"></i>
                                            </button>
                                        @endif

                                        {{-- 3. Pindahkan Kelompok (Jika Lulus & belum dipindah) --}}
                                        @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']) && $item->status === 'passed' && $item->transition_status !== 'promoted')
                                            <button type="button" onclick="openPromoteModal({{ $item->id }}, '{{ addslashes($item->student->name ?? '') }}', '{{ $item->type }}', '{{ addslashes($item->target_level) }}')" 
                                                    title="Pindahkan ke Kelompok Baru" 
                                                    style="background: #f59e0b; color: white; border: none; padding: 6px 9px; border-radius: 8px; font-size: 0.78rem; cursor: pointer;">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        @endif

                                        {{-- 4. Cetak Sertifikat / Berita Acara (Jika Lulus) --}}
                                        @if($item->status === 'passed')
                                            <a href="{{ route('munaqosah.print-certificate', $item->id) }}" target="_blank" title="Cetak Surat Tanda Lulus" 
                                               style="background: #6366f1; color: white; padding: 6px 9px; border-radius: 8px; text-decoration: none; font-size: 0.78rem;">
                                                <i class="fas fa-certificate"></i>
                                            </a>
                                        @endif

                                        {{-- 5. Hapus Pendaftaran (Khusus Admin / Pembuat) --}}
                                        @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']) || $item->proposer_user_id === Auth::id())
                                            <form method="POST" action="{{ route('munaqosah.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data munaqosah ini?')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus" 
                                                        style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 6px 9px; border-radius: 8px; font-size: 0.78rem; cursor: pointer;">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="padding: 40px; text-align: center; color: #94a3b8;">
                                    <i class="fas fa-inbox" style="font-size: 2.8rem; margin-bottom: 12px; display: block; opacity: 0.4;"></i>
                                    Tidak ada data pendaftaran munaqosah untuk kriteria ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $registrations->links() }}
            </div>
        </div>
    @endif

</div>

{{-- MODAL PENDAFTARAN SISWA MUNAQOSAH --}}
<div id="registerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); z-index: 9999; justify-content: center; align-items: center; padding: 20px; backdrop-filter: blur(4px);">
    <div style="background: white; border-radius: 20px; max-width: 580px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; animation: fadeIn 0.25s ease-out;">
        <div style="background: linear-gradient(135deg, #065f46 0%, #047857 100%); color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-signature"></i> Form Pengajuan Munaqosah
            </h3>
            <button type="button" onclick="closeRegisterModal()" style="background: none; border: none; color: white; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="{{ route('munaqosah.register.store') }}" style="padding: 24px;">
            @csrf
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Pilih Bidang Munaqosah <span style="color: #ef4444;">*</span></label>
                <div style="display: flex; gap: 14px;">
                    <label style="flex: 1; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 14px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="type" value="tahfidz" checked onchange="updateLevelOptions('tahfidz')">
                        <span style="font-weight: 700; color: #065f46;"><i class="fas fa-star-and-crescent"></i> Tahfidz (Hafalan Juz)</span>
                    </label>
                    <label style="flex: 1; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 14px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="type" value="tilawati" onchange="updateLevelOptions('tilawati')">
                        <span style="font-weight: 700; color: #92400e;"><i class="fas fa-book-reader"></i> Tilawati (Jilid)</span>
                    </label>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Pilih Siswa <span style="color: #ef4444;">*</span></label>
                <input type="text" id="student_search_input" placeholder="Ketik nama atau kelas siswa..." onkeyup="filterStudentList()" 
                       style="width: 100%; padding: 9px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; margin-bottom: 6px;">
                <select name="student_id" id="student_select" required size="4" style="width: 100%; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; padding: 6px;">
                    @php
                        $studentsList = \App\Models\Student::activeYear()->active()->orderBy('class')->orderBy('name')->get();
                    @endphp
                    @foreach($studentsList as $st)
                        <option value="{{ $st->id }}">{{ $st->name }} (Kelas {{ $st->class }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Jenjang Saat Ini <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="current_level" id="current_level_input" required placeholder="Contoh: Juz 30" 
                           style="width: 100%; padding: 9px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Target Jenjang Lanjutan <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="target_level" id="target_level_input" required placeholder="Contoh: Juz 29" 
                           style="width: 100%; padding: 9px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Catatan Kesiapan Guru Pengusul</label>
                <textarea name="proposer_notes" rows="2" placeholder="Contoh: Ananda sudah tuntas tasmi' 1 majelis juz 30 dengan predikat lancar." 
                          style="width: 100%; padding: 9px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" onclick="closeRegisterModal()" style="padding: 9px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 9px 20px; border-radius: 10px; border: none; background: #047857; color: white; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(4, 120, 87, 0.25);">
                    <i class="fas fa-paper-plane"></i> Ajukan Munaqosah
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL JADWAL & PENETAPAN PENGUJI --}}
<div id="scheduleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); z-index: 9999; justify-content: center; align-items: center; padding: 20px; backdrop-filter: blur(4px);">
    <div style="background: white; border-radius: 20px; max-width: 500px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden;">
        <div style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;">
                <i class="fas fa-calendar-check"></i> Jadwalkan Ujian Munaqosah
            </h3>
            <button type="button" onclick="closeScheduleModal()" style="background: none; border: none; color: white; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>
        <form id="scheduleForm" method="POST" style="padding: 24px;">
            @csrf
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; color: #64748b; font-weight: 700;">Peserta Siswa:</label>
                <strong id="schedule_student_name" style="font-size: 1.05rem; color: #0f172a;"></strong>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Pilih Mentor Penguji (Munaqisy) <span style="color: #ef4444;">*</span></label>
                <select name="examiner_id" id="schedule_examiner_id" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem;">
                    <option value="">-- Pilih Penguji --</option>
                    @foreach($examiners->where('status', 'active') as $ex)
                        <option value="{{ $ex->id }}">{{ $ex->user->name ?? 'Penguji' }} ({{ ucfirst($ex->specialization) }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Tanggal Ujian <span style="color: #ef4444;">*</span></label>
                    <input type="date" name="exam_date" id="schedule_exam_date" required style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Waktu Sesi</label>
                    <input type="text" name="exam_time" id="schedule_exam_time" placeholder="08:00 - 09:30" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Ruangan / Tempat</label>
                <input type="text" name="room_location" id="schedule_room_location" placeholder="Masjid Lt. 2 / Kelas 3A" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeScheduleModal()" style="padding: 9px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 9px 20px; border-radius: 10px; border: none; background: #2563eb; color: white; font-weight: 800; cursor: pointer;">
                    <i class="fas fa-save"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH PENGUJI (MUNAQISY) --}}
<div id="examinerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); z-index: 9999; justify-content: center; align-items: center; padding: 20px; backdrop-filter: blur(4px);">
    <div style="background: white; border-radius: 20px; max-width: 500px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden;">
        <div style="background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); color: white; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;">
                <i class="fas fa-user-plus"></i> Tambah Mentor Penguji (Munaqisy)
            </h3>
            <button type="button" onclick="closeExaminerModal()" style="background: none; border: none; color: white; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="{{ route('munaqosah.examiners.store') }}" style="padding: 24px;">
            @csrf
            
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Pilih Akun Guru / Asatidz <span style="color: #ef4444;">*</span></label>
                <select name="user_id" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem;">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($teachers as $tc)
                        <option value="{{ $tc->id }}">{{ $tc->name }} ({{ $tc->email }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Spesialisasi Pengujian <span style="color: #ef4444;">*</span></label>
                <select name="specialization" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem;">
                    <option value="both">Keduanya (Tahfidz & Tilawati)</option>
                    <option value="tahfidz">Khusus Tahfidz</option>
                    <option value="tilawati">Khusus Tilawati</option>
                </select>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Nomor Sertifikat / SK Munaqisy</label>
                <input type="text" name="certificate_number" placeholder="Contoh: SK-MNQ/2026/012" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;">
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">No WhatsApp</label>
                <input type="text" name="phone" placeholder="08xxxxxxxxxx" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeExaminerModal()" style="padding: 9px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 9px 20px; border-radius: 10px; border: none; background: #4f46e5; color: white; font-weight: 800; cursor: pointer;">
                    <i class="fas fa-check"></i> Daftarkan Penguji
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PINDAHKAN KELOMPOK / NAIK JENJANG --}}
<div id="promoteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); z-index: 9999; justify-content: center; align-items: center; padding: 20px; backdrop-filter: blur(4px);">
    <div style="background: white; border-radius: 20px; max-width: 520px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden;">
        <div style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-user-check"></i> Pindahkan Kelompok Siswa Lulus
            </h3>
            <button type="button" onclick="closePromoteModal()" style="background: none; border: none; color: white; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>
        <form id="promoteForm" method="POST" style="padding: 24px;">
            @csrf
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px; margin-bottom: 18px;">
                <div style="font-size: 0.82rem; color: #166534; font-weight: 700; margin-bottom: 2px;">Siswa Lulus Munaqosah:</div>
                <div id="promote_student_name" style="font-size: 1.05rem; font-weight: 900; color: #0f172a; margin-bottom: 6px;"></div>
                <div style="font-size: 0.82rem; color: #166534;">
                    Rekomendasi Jenjang Lanjutan: <strong id="promote_target_level" style="color: #047857; text-decoration: underline;"></strong>
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.86rem; color: #334155; margin-bottom: 8px;">
                    Pilih Kelompok Baru Tujuan Siswa <span style="color: #ef4444;">*</span>
                </label>
                <select name="target_group_id" id="target_group_select" required style="width: 100%; padding: 11px 14px; border-radius: 12px; border: 1.5px solid #10b981; font-size: 0.9rem; font-weight: 600; color: #0f172a; background: #f8fafc;">
                    <option value="">-- Pilih Kelompok Mentoring --</option>
                    @foreach($groups as $grp)
                        <option value="{{ $grp->id }}" data-type="{{ $grp->type }}" data-target="{{ $grp->target_level }}">
                            [{{ strtoupper($grp->type) }}] {{ $grp->name }} • Target: {{ $grp->target_level ?? 'Umum' }} (Mentor: {{ $grp->mentor_name ?? '-' }})
                        </option>
                    @endforeach
                </select>
                <small style="display: block; color: #64748b; font-size: 0.78rem; margin-top: 6px;">
                    Siswa otomatis akan dilepaskan dari kelompok sebelumnya dan didaftarkan pada kelompok baru di atas.
                </small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" onclick="closePromoteModal()" style="padding: 9px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 9px 22px; border-radius: 10px; border: none; background: #059669; color: white; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);">
                    <i class="fas fa-check-circle"></i> Pindahkan Siswa Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH JADWAL KETERSEDIAAN PENGUJI --}}
<div id="addScheduleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); z-index: 9999; justify-content: center; align-items: center; padding: 20px; backdrop-filter: blur(4px);">
    <div style="background: white; border-radius: 20px; max-width: 520px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; animation: fadeIn 0.25s ease-out;">
        <div style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-calendar-plus"></i> Atur Jadwal & Ketersediaan Penguji
            </h3>
            <button type="button" onclick="closeAddScheduleModal()" style="background: none; border: none; color: white; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="{{ route('munaqosah.schedules.store') }}" style="padding: 24px;">
            @csrf
            
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                    Pilih Mentor Penguji (Munaqisy) <span style="color: #ef4444;">*</span>
                </label>
                <select name="examiner_id" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem; font-weight: 600;">
                    <option value="">-- Pilih Penguji --</option>
                    @foreach($examiners->where('status', 'active') as $ex)
                        <option value="{{ $ex->id }}">{{ $ex->user->name ?? 'Penguji' }} ({{ ucfirst($ex->specialization) }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                        Hari Ketersediaan <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="day_name" required style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem; font-weight: 600;">
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu" selected>Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Ahad">Ahad</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                        Tanggal Spesifik (Opsional)
                    </label>
                    <input type="date" name="date" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                        Jam Mulai <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="time_start" required value="08:00" placeholder="08:00" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem; font-weight: 600;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                        Jam Selesai <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="time_end" required value="09:00" placeholder="09:00" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem; font-weight: 600;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                        Ruangan / Lokasi Ujian
                    </label>
                    <input type="text" name="room_location" placeholder="Contoh: Ruang Musholla / Kelas 1A" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                        Kuota Siswa <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="quota" required min="1" max="20" value="1" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; font-weight: 700;">
                </div>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">
                    Kategori Ujian
                </label>
                <select name="exam_type" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; font-weight: 600;">
                    <option value="all">Semua (Tahfidz & Tilawati)</option>
                    <option value="tahfidz">Khusus Tahfidz</option>
                    <option value="tilawati">Khusus Tilawati</option>
                </select>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Catatan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Catatan kesiapan penguji..." style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" onclick="closeAddScheduleModal()" style="padding: 9px 16px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 9px 20px; border-radius: 10px; border: none; background: #0284c7; color: white; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);">
                    <i class="fas fa-save"></i> Simpan Jadwal Ketersediaan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRegisterModal() {
    document.getElementById('registerModal').style.display = 'flex';
}
function closeRegisterModal() {
    document.getElementById('registerModal').style.display = 'none';
}

function openAddScheduleModal() {
    document.getElementById('addScheduleModal').style.display = 'flex';
}
function closeAddScheduleModal() {
    document.getElementById('addScheduleModal').style.display = 'none';
}

function openScheduleModal(id, studentName, examinerId, examDate, examTime, room) {
    document.getElementById('schedule_student_name').textContent = studentName;
    document.getElementById('schedule_examiner_id').value = examinerId || '';
    document.getElementById('schedule_exam_date').value = examDate || '';
    document.getElementById('schedule_exam_time').value = examTime || '';
    document.getElementById('schedule_room_location').value = room || '';
    document.getElementById('scheduleForm').action = '/munaqosah/' + id + '/schedule';
    document.getElementById('scheduleModal').style.display = 'flex';
}
function closeScheduleModal() {
    document.getElementById('scheduleModal').style.display = 'none';
}

function openExaminerModal() {
    document.getElementById('examinerModal').style.display = 'flex';
}
function closeExaminerModal() {
    document.getElementById('examinerModal').style.display = 'none';
}

function openPromoteModal(id, studentName, type, targetLevel) {
    document.getElementById('promote_student_name').textContent = studentName;
    document.getElementById('promote_target_level').textContent = targetLevel;
    document.getElementById('promoteForm').action = '/munaqosah/' + id + '/promote';
    
    // Auto-filter or highlight matching group options
    const select = document.getElementById('target_group_select');
    for (let opt of select.options) {
        if (!opt.value) continue;
        const optType = opt.getAttribute('data-type');
        const optTarget = opt.getAttribute('data-target') || '';
        if (optType === type) {
            opt.style.display = '';
            if (targetLevel && optTarget.toLowerCase().includes(targetLevel.toLowerCase())) {
                opt.selected = true;
            }
        } else {
            opt.style.display = 'none';
        }
    }
    document.getElementById('promoteModal').style.display = 'flex';
}
function closePromoteModal() {
    document.getElementById('promoteModal').style.display = 'none';
}

function shareWhatsAppRegistrationLink() {
    const publicUrl = "{{ route('munaqosah.public-register') }}";
    const waMsg = "Assalamu'alaikum Warahmatullahi Wabarakatuh 🌙\n\nBapak/Ibu Guru Pembimbing & Orang Tua / Wali Siswa SDIT AN NADZIR yang dirahmati Allah,\n\nBagi siswa yang telah menuntaskan target hafalan Juz Al-Qur'an (Tahfidz) atau Jilid (Tilawati), pendaftaran Ujian Munaqosah resmi kini dapat diajukan secara online melalui tautan berikut:\n\n🔗 " + publicUrl + "\n\nMohon pastikan ananda telah menyelesaikan tasmi' mandiri di hadapan guru pembimbing.\n\nJazakumullah Khairan Katsiran 🤲";

    if (navigator.clipboard) {
        navigator.clipboard.writeText(waMsg).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Teks & Link WA Disalin!',
                html: 'Teks pengantar dan link formulir pendaftaran online munaqosah berhasil disalin ke clipboard.<br><br><a href="https://wa.me/?text=' + encodeURIComponent(waMsg) + '" target="_blank" style="display: inline-block; background: #25d366; color: white; padding: 10px 18px; border-radius: 10px; font-weight: bold; text-decoration: none; margin-top: 8px;"><i class="fab fa-whatsapp"></i> Buka WhatsApp Sekarang</a>',
                showConfirmButton: true,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#059669'
            });
        }).catch(err => {
            prompt("Salin teks pesan pendaftaran berikut:", waMsg);
        });
    } else {
        prompt("Salin teks pesan pendaftaran berikut:", waMsg);
    }
}

function updateLevelOptions(type) {
    const cur = document.getElementById('current_level_input');
    const tgt = document.getElementById('target_level_input');
    if (type === 'tahfidz') {
        cur.placeholder = 'Contoh: Juz 30';
        tgt.placeholder = 'Contoh: Juz 29';
    } else {
        cur.placeholder = 'Contoh: Jilid 2';
        tgt.placeholder = 'Contoh: Jilid 3';
    }
}

function filterStudentList() {
    const query = document.getElementById('student_search_input').value.toLowerCase();
    const select = document.getElementById('student_select');
    for (let option of select.options) {
        const text = option.text.toLowerCase();
        option.style.display = text.includes(query) ? '' : 'none';
    }
}
</script>
@endsection
