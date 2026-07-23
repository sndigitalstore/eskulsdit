@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Hero Section -->
<div class="dashboard-hero responsive-flex" style="background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%); padding: 28px 32px; border-radius: 20px; border: 1px solid rgba(226, 232, 240, 0.8); box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.04); margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden;">
    <div style="z-index: 2;">
        <h2 style="font-size: 1.85rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; letter-spacing: -0.5px;">
            <span id="greeting">Selamat Datang</span>, {{ Auth::user()->name }}! 👋
        </h2>
        <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 8px; flex-wrap: wrap;">
            <p style="color: #64748b; font-size: 0.95rem; margin: 0; font-weight: 500;">
                 Tahun Ajaran <b style="color: #0f172a;">{{ $activeYear ? $activeYear->name : '-' }}</b> • Semester <b style="color: #0f172a;">{{ $activeYear ? $activeYear->active_semester : '-' }}</b>
            </p>
            @if(Auth::user()->role === 'admin')
                <span style="background: #e0e7ff; color: #4338ca; font-size: 0.75rem; padding: 4px 12px; border-radius: 20px; font-weight: 700;">Administrator</span>
            @else
                @if($isHomeroomTeacher)
                    <span style="background: #e0f2fe; color: #0369a1; font-size: 0.75rem; padding: 4px 12px; border-radius: 20px; font-weight: 700;">Wali Kelas {{ $homeroomClass }}</span>
                @endif
                @if(Auth::user()->eskul)
                    <span style="background: #ecfdf5; color: #047857; font-size: 0.75rem; padding: 4px 12px; border-radius: 20px; font-weight: 700;">Pembina {{ Auth::user()->eskul->name }}</span>
                @endif
            @endif
        </div>
    </div>
    <div style="text-align: right; z-index: 2;">
        <div id="clock" style="font-size: 2.1rem; font-weight: 800; color: #10b981; letter-spacing: -0.5px;">00:00</div>
        <div style="font-size: 0.88rem; font-weight: 600; color: #64748b;">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</div>
    </div>
    <!-- Decor -->
    <i class="fas fa-chart-line decor-icon" style="position: absolute; right: -15px; bottom: -25px; font-size: 9.5rem; color: rgba(99, 102, 241, 0.05);"></i>
</div>

<!-- Stats Grid (Dinamis Sesuai Peran) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 30px;">
    @if(Auth::user()->role === 'admin')
        <!-- Admin Stats: Global -->
        <!-- Students -->
        <div class="card" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: white; border: none; min-height: 140px; padding: 1.5rem; position: relative; overflow: hidden; margin-bottom: 0; box-shadow: 0 10px 25px -4px rgba(79, 70, 229, 0.4);">
            <div style="display: flex; justify-content: space-between; align-items: start; position: relative; z-index: 2;">
                <div>
                    <h3 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 2px;">{{ $studentCount }}</h3>
                    <p style="opacity: 0.9; font-size: 0.9rem; font-weight: 600;">Total Siswa</p>
                </div>
                <div style="background: rgba(255,255,255,0.22); width: 44px; height: 44px; justify-content: center; align-items: center; display: flex; border-radius: 12px; backdrop-filter: blur(4px);">
                     <i class="fas fa-users" style="font-size: 1.25rem;"></i>
                </div>
            </div>
            <div style="position: absolute; bottom: 10px; left: 0; width: 100%; z-index: 1;">
                <svg viewBox="0 0 100 20" preserveAspectRatio="none" style="width:100%; height:40px; filter: drop-shadow(0 4px 2px rgba(0,0,0,0.15));">
                    <path d="M0,15 C15,15 20,5 35,10 C50,15 65,5 80,10 C90,12 95,5 100,8" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-linecap="round" fill="none"/>
                </svg>
            </div>
        </div>

        <!-- Eskuls -->
        <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); color: white; border: none; min-height: 140px; padding: 1.5rem; position: relative; overflow: hidden; margin-bottom: 0; box-shadow: 0 10px 25px -4px rgba(245, 158, 11, 0.4);">
            <div style="display: flex; justify-content: space-between; align-items: start; position: relative; z-index: 2;">
                <div>
                    <h3 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 2px;">{{ $eskulCount }}</h3>
                    <p style="opacity: 0.9; font-size: 0.9rem; font-weight: 600;">Ekstrakurikuler</p>
                </div>
                 <div style="background: rgba(255,255,255,0.22); width: 44px; height: 44px; justify-content: center; align-items: center; display: flex; border-radius: 12px; backdrop-filter: blur(4px);">
                     <i class="fas fa-basketball-ball" style="font-size: 1.25rem;"></i>
                </div>
            </div>
            <div style="position: absolute; bottom: 10px; left: 0; width: 100%; z-index: 1;">
                <svg viewBox="0 0 100 20" preserveAspectRatio="none" style="width:100%; height:40px; filter: drop-shadow(0 4px 2px rgba(0,0,0,0.15));">
                    <path d="M0,10 C15,0 20,20 35,15 C50,10 65,20 80,5 C90,0 95,15 100,10" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-linecap="round" fill="none"/>
                </svg>
            </div>
        </div>

        <!-- Teachers -->
        <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; min-height: 140px; padding: 1.5rem; position: relative; overflow: hidden; margin-bottom: 0; box-shadow: 0 10px 25px -4px rgba(16, 185, 129, 0.4);">
            <div style="display: flex; justify-content: space-between; align-items: start; position: relative; z-index: 2;">
                <div>
                    <h3 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 2px;">{{ $teacherCount }}</h3>
                    <p style="opacity: 0.9; font-size: 0.9rem; font-weight: 600;">Guru Pembina</p>
                </div>
                 <div style="background: rgba(255,255,255,0.22); width: 44px; height: 44px; justify-content: center; align-items: center; display: flex; border-radius: 12px; backdrop-filter: blur(4px);">
                     <i class="fas fa-chalkboard-teacher" style="font-size: 1.25rem;"></i>
                </div>
            </div>
            <div style="position: absolute; bottom: 10px; left: 0; width: 100%; z-index: 1;">
                <svg viewBox="0 0 100 20" preserveAspectRatio="none" style="width:100%; height:40px; filter: drop-shadow(0 4px 2px rgba(0,0,0,0.15));">
                    <path d="M0,5 C15,20 20,5 35,5 C50,5 65,15 80,10 C90,5 95,15 100,5" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-linecap="round" fill="none"/>
                </svg>
            </div>
        </div>

        <!-- Monitor Guru -->
        <div class="card" style="background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); color: white; border: none; min-height: 140px; padding: 1.5rem; position: relative; overflow: hidden; margin-bottom: 0; box-shadow: 0 10px 25px -4px rgba(244, 63, 94, 0.4);">
            <div style="display: flex; justify-content: space-between; align-items: start; position: relative; z-index: 2;">
                <div>
                    <h3 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 2px;">
                        {{ $teacherAttendancePresent ?? 0 }} <span style="font-size: 1rem; opacity: 0.7;">/ {{ $registeredTeacherAccounts ?? 0 }}</span>
                    </h3>
                    <p style="opacity: 0.9; font-size: 0.9rem; font-weight: 600; margin-bottom: 5px;">Hadir Hari Ini</p>
                    <a href="{{ route('teacher-attendance.index') }}" style="color: white; font-size: 0.78rem; font-weight: 600; text-decoration: none; border-bottom: 1px dotted white; position: relative; z-index: 10;">Detail <i class="fas fa-arrow-right"></i></a>
                </div>
                 <div style="background: rgba(255,255,255,0.22); width: 44px; height: 44px; justify-content: center; align-items: center; display: flex; border-radius: 12px; backdrop-filter: blur(4px);">
                     <i class="fas fa-user-clock" style="font-size: 1.25rem;"></i>
                </div>
            </div>
            <div style="position: absolute; bottom: 10px; left: 0; width: 100%; z-index: 1;">
                <svg viewBox="0 0 100 20" preserveAspectRatio="none" style="width:100%; height:40px; filter: drop-shadow(0 4px 2px rgba(0,0,0,0.15));">
                    <path d="M0,20 C15,0 20,10 35,5 C50,0 65,15 80,10 C90,5 95,0 100,10" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-linecap="round" fill="none"/>
                </svg>
            </div>
        </div>
    @else
        <!-- Teacher/Wali Kelas/Pembina Stats -->
        @if($isHomeroomTeacher)
            <!-- Wali Kelas: Total Murid Binaan -->
            <div class="card" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: white; border: none; min-height: 140px; padding: 1.5rem; position: relative; overflow: hidden; margin-bottom: 0; box-shadow: 0 10px 25px -4px rgba(79, 70, 229, 0.4);">
                <div style="display: flex; justify-content: space-between; align-items: start; position: relative; z-index: 2;">
                    <div>
                        <h3 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 2px;">{{ $homeroomStudentCount }}</h3>
                        <p style="opacity: 0.9; font-size: 0.9rem; font-weight: 600;">Siswa Kelas {{ $homeroomClass }}</p>
                    </div>
                    <div style="background: rgba(255,255,255,0.22); width: 44px; height: 44px; justify-content: center; align-items: center; display: flex; border-radius: 12px; backdrop-filter: blur(4px);">
                         <i class="fas fa-school" style="font-size: 1.25rem;"></i>
                    </div>
                </div>
                <div style="position: absolute; bottom: 10px; left: 0; width: 100%; z-index: 1;">
                    <svg viewBox="0 0 100 20" preserveAspectRatio="none" style="width:100%; height:40px; filter: drop-shadow(0 4px 2px rgba(0,0,0,0.15));">
                        <path d="M0,15 C15,15 20,5 35,10 C50,15 65,5 80,10 C90,12 95,5 100,8" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>

            <!-- Wali Kelas: Terdaftar Eskul -->
            <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); color: white; border: none; min-height: 140px; padding: 1.5rem; position: relative; overflow: hidden; margin-bottom: 0; box-shadow: 0 10px 25px -4px rgba(245, 158, 11, 0.4);">
                <div style="display: flex; justify-content: space-between; align-items: start; position: relative; z-index: 2;">
                    <div>
                        <h3 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 2px;">
                            {{ $homeroomRegisteredCount }} <span style="font-size: 1.1rem; opacity: 0.85;">/ {{ $homeroomStudentCount }}</span>
                        </h3>
                        <p style="opacity: 0.9; font-size: 0.9rem; font-weight: 600; margin-bottom: 6px;">Sudah Memilih Eskul</p>
                        <span style="background: rgba(255,255,255,0.25); font-size: 0.75rem; font-weight: 700; padding: 3px 10px; border-radius: 20px;">
                            {{ $homeroomUnregisteredCount }} Belum Daftar
                        </span>
                    </div>
                    <div style="background: rgba(255,255,255,0.22); width: 44px; height: 44px; justify-content: center; align-items: center; display: flex; border-radius: 12px; backdrop-filter: blur(4px);">
                         <i class="fas fa-clipboard-check" style="font-size: 1.25rem;"></i>
                    </div>
                </div>
                <div style="position: absolute; bottom: 10px; left: 0; width: 100%; z-index: 1;">
                    <svg viewBox="0 0 100 20" preserveAspectRatio="none" style="width:100%; height:40px; filter: drop-shadow(0 4px 2px rgba(0,0,0,0.15));">
                        <path d="M0,10 C15,0 20,20 35,15 C50,10 65,20 80,5 C90,0 95,15 100,10" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>
        @endif

        @if(Auth::user()->eskul_id)
            <!-- Pembina: Jumlah Anggota Eskul (Scoped to Pembina Eskul) -->
            <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; min-height: 140px; padding: 1.5rem; position: relative; overflow: hidden; margin-bottom: 0; box-shadow: 0 10px 25px -4px rgba(16, 185, 129, 0.4);">
                <div style="display: flex; justify-content: space-between; align-items: start; position: relative; z-index: 2;">
                    <div>
                        <h3 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 2px;">{{ $studentCount }}</h3>
                        <p style="opacity: 0.9; font-size: 0.9rem; font-weight: 600;">Siswa Terdaftar ({{ Auth::user()->eskul->name }})</p>
                    </div>
                    <div style="background: rgba(255,255,255,0.22); width: 44px; height: 44px; justify-content: center; align-items: center; display: flex; border-radius: 12px; backdrop-filter: blur(4px);">
                         <i class="fas fa-running" style="font-size: 1.25rem;"></i>
                    </div>
                </div>
                <div style="position: absolute; bottom: 10px; left: 0; width: 100%; z-index: 1;">
                    <svg viewBox="0 0 100 20" preserveAspectRatio="none" style="width:100%; height:40px; filter: drop-shadow(0 4px 2px rgba(0,0,0,0.15));">
                        <path d="M0,5 C15,20 20,5 35,5 C50,5 65,15 80,10 C90,5 95,15 100,5" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>

            <!-- Pembina: Jadwal Latihan -->
            <div class="card" style="background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); color: white; border: none; min-height: 140px; padding: 1.5rem; position: relative; overflow: hidden; margin-bottom: 0; box-shadow: 0 10px 25px -4px rgba(244, 63, 94, 0.4);">
                <div style="display: flex; justify-content: space-between; align-items: start; position: relative; z-index: 2;">
                    <div>
                        <h3 style="font-size: 1.3rem; font-weight: 800; margin-top: 5px; margin-bottom: 5px; word-break: break-word; line-height: 1.3;">
                            {{ Auth::user()->eskul->schedule ?? 'Belum diatur' }}
                        </h3>
                        <p style="opacity: 0.9; font-size: 0.9rem; font-weight: 600;">Jadwal Eskul Binaan</p>
                    </div>
                    <div style="background: rgba(255,255,255,0.22); width: 44px; height: 44px; justify-content: center; align-items: center; display: flex; border-radius: 12px; backdrop-filter: blur(4px);">
                         <i class="far fa-calendar-alt" style="font-size: 1.25rem;"></i>
                    </div>
                </div>
                <div style="position: absolute; bottom: 10px; left: 0; width: 100%; z-index: 1;">
                    <svg viewBox="0 0 100 20" preserveAspectRatio="none" style="width:100%; height:40px; filter: drop-shadow(0 4px 2px rgba(0,0,0,0.15));">
                        <path d="M0,20 C15,0 20,10 35,5 C50,0 65,15 80,10 C90,5 95,0 100,10" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>
        @endif
    @endif
</div>

@if(isset($todaySchedule) && count($todaySchedule) > 0)
    <div class="card" style="margin-bottom: 2rem; background: linear-gradient(to right, #ffffff, #f0fdf4); border-left: 5px solid #10b981;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
             <h3 style="color: #064e3b; font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-calendar-check" style="color: #10b981;"></i> Jadwal Eskul Hari Ini
                <span style="font-size: 0.9rem; font-weight: 400; color: #64748b; margin-left: 10px; background: #d1fae5; padding: 2px 10px; border-radius: 10px;">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>
            </h3>
        </div>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            @foreach($todaySchedule as $eskul)
                <div style="background: white; border: 1px solid #d1fae5; padding: 10px 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.05); transition: transform 0.2s;">
                    <div style="width: 40px; height: 40px; background: #ecfdf5; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #059669;">
                        <i class="fas fa-running"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #064e3b;">{{ $eskul->name }}</div>
                        <div style="font-size: 0.85rem; color: #64748b;">
                            <i class="fas fa-user-tie" style="font-size: 0.75rem;"></i> {{ $eskul->instructor_name ?? 'Belum ada pembina' }}
                        </div>
                    </div>
                     <span style="background: #d1fae5; color: #047857; padding: 3px 8px; border-radius: 5px; font-size: 0.75rem; font-weight: 600;">
                        {{ $eskul->schedule }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if(isset($eskulMissingAttendance) && count($eskulMissingAttendance) > 0)
<div class="card" style="border-left: 5px solid #ff9f43; margin-bottom: 2rem; background: #fff8f0;">
    <div style="display: flex; align-items: flex-start; gap: 15px;">
        <div style="background: #ff9f43; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div style="flex: 1;">
            <h3 style="color: #e67e22; font-size: 1.1rem; margin-bottom: 5px;">
                Perlu Tindakan: Absensi Minggu Ini
            </h3>
            <p style="font-size: 0.9rem; color: #666; margin-bottom: 15px;">
                Eskul berikut belum melakukan input absensi untuk minggu ini ({{ now()->startOfWeek()->format('d M') }} - {{ now()->endOfWeek()->format('d M') }}).
            </p>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                @foreach($eskulMissingAttendance as $eskul)
                    <div style="background: white; border: 1px solid #ffcc80; color: #e67e22; padding: 6px 12px; border-radius: 8px; font-weight: 500; font-size: 0.85rem; display: flex; align-items: center; gap: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                        <span>{{ $eskul->name }}</span>
                        <a href="{{ route('attendance.create', ['eskul_id' => $eskul->id, 'date' => now()->toDateString()]) }}" title="Input Absensi Sekarang" style="background: #e67e22; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: white; text-decoration: none; transition: transform 0.2s;">
                            <i class="fas fa-plus" style="font-size: 0.7rem;"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Left Column -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Wali Kelas Widgets: Belum Daftar & Sebaran Eskul -->
        @if($isHomeroomTeacher)
            <!-- Siswa Belum Memilih Eskul -->
            <div class="card" style="border-left: 5px solid #e74c3c;">
                <h3 style="margin-bottom: 1rem; color: #c0392b; display: flex; justify-content: space-between; align-items: center; font-size: 1.15rem; font-weight: 700;">
                    <span><i class="fas fa-user-slash" style="margin-right: 10px;"></i> Belum Memilih Eskul (Kelas {{ $homeroomClass }})</span>
                    <span class="badge" style="background: #fde8e8; color: #e74c3c; font-size: 0.85rem; padding: 5px 12px; border-radius: 15px;">
                        {{ $homeroomUnregisteredCount }} Anak
                    </span>
                </h3>
                
                @if($homeroomUnregisteredList->isEmpty())
                    <div style="background: #e0fbf0; color: #27ae60; padding: 15px; border-radius: 12px; text-align: center; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> Luar biasa! Semua siswa kelas Anda sudah mendaftar ekskul.
                    </div>
                @else
                    <p style="font-size: 0.85rem; color: #64748b; margin-top: 0; margin-bottom: 15px;">
                        Hubungi orang tua secara langsung melalui WhatsApp untuk mengingatkan pengisian pilihan eskul.
                    </p>
                    <div style="max-height: 250px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; padding-right: 5px;">
                        @foreach($homeroomUnregisteredList as $student)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; background: #fff5f5; border: 1px solid #fed7d7; border-radius: 10px;">
                                <div>
                                    <div style="font-weight: 700; color: #2d3748; font-size: 0.95rem;">{{ $student->name }}</div>
                                    <div style="font-size: 0.8rem; color: #718096; margin-top: 2px;">
                                        <i class="fas fa-phone-alt"></i> Ortu: {{ $student->parent_phone ?? 'Belum ada nomor WA' }}
                                    </div>
                                </div>
                                <div>
                                    @if($student->parent_phone)
                                        @php
                                            $phoneClean = preg_replace('/[^0-9]/', '', $student->parent_phone);
                                            if (str_starts_with($phoneClean, '0')) {
                                                $phoneClean = '62' . substr($phoneClean, 1);
                                            }
                                            $waMsg = urlencode("Assalamu'alaikum wr. wb. Halo Bapak/Ibu wali murid dari Ananda " . $student->name . ", kami menginfokan bahwa Ananda belum memilih kegiatan ekstrakurikuler di sekolah untuk semester ini. Silakan segera mengisi formulir pendaftaran eskul melalui tautan berikut ya: " . route('pilihan-eskul.form') . ". Terima kasih.");
                                        @endphp
                                        <a href="https://wa.me/{{ $phoneClean }}?text={{ $waMsg }}" target="_blank" class="btn-action-header btn-green" style="font-size: 0.8rem; padding: 6px 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; border-radius: 6px;">
                                            <i class="fab fa-whatsapp"></i> Hubungi
                                        </a>
                                    @else
                                        <span style="font-size: 0.75rem; color: #a0aec0; font-style: italic;">No WA Kosong</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Sebaran Eskul Kelas Binaan -->
            <div class="card">
                <h3 style="margin-bottom: 1.2rem; font-size: 1.15rem; font-weight: 700; color: #2c3e50;"><i class="fas fa-chart-pie" style="color: #3498db; margin-right: 10px;"></i> Sebaran Pilihan Eskul (Kelas {{ $homeroomClass }})</h3>
                @if($homeroomEskulDistribution->isEmpty())
                    <div style="text-align: center; color: #999; padding: 1.5rem; background: #fafafa; border-radius: 12px;">Belum ada siswa yang mendaftar eskul.</div>
                @else
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px;">
                        @foreach($homeroomEskulDistribution as $dist)
                            <div style="padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 12px; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 600; color: #334155; font-size: 0.9rem;">{{ $dist->name }}</span>
                                <span style="background: #3498db; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.85rem; flex-shrink: 0;">
                                    {{ $dist->total }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        
        <!-- Announcements (Internal Chat) -->
        @if(isset($announcements) && count($announcements) > 0)
        <div class="card" style="background: #f8fafc; border: 1px solid #e2e8f0;">
            <h3 style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <span><i class="fas fa-bullhorn" style="color: #7367f0; margin-right: 10px;"></i> Pengumuman Terbaru</span>
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('announcements.index') }}" style="font-size: 0.75rem; color: #7367f0; text-decoration: none;">Kelola <i class="fas fa-chevron-right"></i></a>
                @endif
            </h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($announcements as $ann)
                <div style="padding: 15px; border-radius: 12px; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.02); border-left: 4px solid {{ $ann->type == 'info' ? '#3b82f6' : ($ann->type == 'warning' ? '#f59e0b' : ($ann->type == 'success' ? '#10b981' : '#7367f0')) }}">
                    <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem; margin-bottom: 5px;">{{ $ann->title }}</div>
                    <div style="font-size: 0.85rem; color: #475569; line-height: 1.5;">{{ Str::limit($ann->content, 150) }}</div>
                    <div style="margin-top: 8px; font-size: 0.75rem; color: #94a3b8; display: flex; justify-content: space-between;">
                        <span><i class="fas fa-user-edit"></i> {{ $ann->user->name }}</span>
                        <span><i class="fas fa-clock"></i> {{ $ann->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Activity Feed -->
        @if(isset($recentActivities) && count($recentActivities) > 0)
        <div class="card">
            <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-history" style="color: #10b981; margin-right: 10px;"></i> Aktivitas Terbaru</h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($recentActivities as $activity)
                <div style="display: flex; gap: 15px; align-items: center; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                    <div style="width: 35px; height: 35px; background: #ecfdf5; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 0.9rem;">
                        <i class="fas fa-check"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 500; font-size: 0.95rem;">
                            <span style="color: #064e3b;">{{ $activity->student->name ?? 'Siswa' }}</span>
                            <span style="color: #999; font-weight: 400;">hadir di</span>
                            <span style="color: #059669;">{{ $activity->eskul->name ?? 'Eskul' }}</span>
                        </div>
                        <div style="font-size: 0.8rem; color: #aaa; margin-top: 3px;">
                            {{ $activity->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Grade Stats -->
        <div class="card">
            <h3 style="margin-bottom: 1.5rem;">Statistik Kelas & Rombel</h3>
            <table style="margin-top: 0;">
                <thead>
                    <tr>
                        <th width="30%">Tingkat Kelas</th>
                        <th width="30%">Jumlah Rombel</th>
                        <th width="40%">Total Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gradeStatistics ?? [] as $stat)
                    <tr>
                        <td><strong>Kelas {{ $stat['grade'] }}</strong></td>
                        <td>
                            <span class="badge" style="background: #ecfdf5; color: #059669; font-size: 0.9rem; padding: 5px 12px; border-radius: 15px;">
                                {{ $stat['rombel_count'] }} Rombel
                            </span>
                             <div style="font-size: 0.8rem; color: #999; margin-top: 5px;">
                                @foreach($stat['classes'] as $cls)
                                    {{ $cls }}{{ !$loop->last ? ',' : '' }}
                                @endforeach
                            </div>
                        </td>
                        <td style="font-size: 1.1rem; font-weight: 600; color: #064e3b;">
                            {{ $stat['total_students'] }} <span style="font-size: 0.9rem; font-weight: 400; color: #888;">Siswa</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #999; padding: 2rem;">Belum ada data kelas yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- Right Column (Stats & Quick Links) -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- Top Participating Classes -->
        @if(isset($topClasses) && count($topClasses) > 0)
        <div class="card">
            <h3 style="margin-bottom: 1rem; font-size: 1.1rem; display: flex; align-items: center;">
                <i class="fas fa-trophy" style="color: #f1c40f; margin-right: 10px;"></i> Kelas Teraktif
            </h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($topClasses as $index => $classStats)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #fffbe7; border-radius: 10px; border: 1px solid #f9e79f;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-weight: 700; color: #d4ac0d; width: 20px;">#{{ $index + 1 }}</span>
                        <span style="font-weight: 600;">Kelas {{ $classStats->class }}</span>
                    </div>
                    <span style="background: white; padding: 2px 8px; border-radius: 10px; font-size: 0.85rem; font-weight: 600; color: #d4ac0d;">
                        {{ $classStats->total }} Siswa
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Kategori Eskul Diminati -->
        <div class="card" style="background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 25px;">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.1rem; display: flex; align-items: center; gap: 10px; color: #2c3e50;">
                <i class="fas fa-shapes" style="color: #7367f0;"></i> Minat Kategori Eskul
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @php
                    $totalParticipants = array_sum($categoryCounts ?? []);
                @endphp
                
                @foreach($categoryCounts ?? [] as $category => $count)
                    @php
                        $percentage = $totalParticipants > 0 ? round(($count / $totalParticipants) * 100) : 0;
                        $icon = match($category) {
                            'Olahraga' => 'fa-basketball-ball',
                            'Sains' => 'fa-flask',
                            'Bahasa' => 'fa-language',
                            'Seni' => 'fa-palette',
                            default => 'fa-shapes'
                        };
                        $color = match($category) {
                            'Olahraga' => '#ff7e5f',
                            'Sains' => '#00cdac',
                            'Bahasa' => '#5381ff',
                            'Seni' => '#ff416c',
                            default => '#94a3b8'
                        };
                        $bg = match($category) {
                            'Olahraga' => '#fff0ec',
                            'Sains' => '#e6fbf7',
                            'Bahasa' => '#eef2ff',
                            'Seni' => '#ffebee',
                            default => '#f1f5f9'
                        };
                    @endphp
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; background: {{ $bg }}; color: {{ $color }}; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.95rem;">
                                    <i class="fas {{ $icon }}"></i>
                                </div>
                                <span style="font-weight: 600; color: #34495e; font-size: 0.95rem;">{{ $category }}</span>
                            </div>
                            <span style="font-size: 0.9rem; font-weight: 700; color: #2c3e50;">
                                {{ $count }} <span style="font-weight: 400; color: #888; font-size: 0.8rem;">Siswa ({{ $percentage }}%)</span>
                            </span>
                        </div>
                        <div style="width: 100%; height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden;">
                            <div style="width: {{ $percentage }}%; height: 100%; background: {{ $color }}; border-radius: 4px;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Population / Eskul Chart -->
        <div class="card">
            <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Eskul Terpopuler</h3>
            <div style="height: 200px; position: relative;">
                <canvas id="eskulChart"></canvas>
            </div>
        </div>

        <!-- Attendance Chart -->
        <div class="card">
            <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Distribusi Kehadiran</h3>
            <div style="height: 200px; position: relative;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 1rem;">Aksi Cepat</h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @if(auth()->user()->role == 'admin')
                <a href="{{ route('reports.index') }}" style="padding: 15px; background: #f9f9f9; border-radius: 12px; text-decoration: none; color: #333; display: flex; align-items: center; justify-content: space-between; transition: 0.2s;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-file-alt" style="color: #ff7eb3;"></i> Cetak Laporan
                    </div>
                    <i class="fas fa-arrow-right" style="color: #ccc;"></i>
                </a>
                <a href="{{ route('eskuls.index') }}" style="padding: 15px; background: #f9f9f9; border-radius: 12px; text-decoration: none; color: #333; display: flex; align-items: center; justify-content: space-between; transition: 0.2s;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-list" style="color: #5381ff;"></i> Lihat Eskul
                    </div>
                    <i class="fas fa-arrow-right" style="color: #ccc;"></i>
                </a>
                @endif
                
                <!-- Share Form Button -->
                <button onclick="document.getElementById('shareModal').style.display='flex'; generateQRCode();" style="border:none; cursor: pointer; padding: 15px; background: #e0f2fe; border: 1px dashed #3498db; border-radius: 12px; text-decoration: none; color: #0c4a6e; display: flex; align-items: center; justify-content: space-between; transition: 0.2s; width: 100%; font-size: 1rem; text-align: left;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-share-alt" style="color: #0284c7;"></i> Bagikan Formulir
                    </div>
                    <i class="fas fa-chevron-right" style="color: #7dd3fc;"></i>
                </button>
                
                <!-- Share Prestasi Button -->
                <button onclick="document.getElementById('sharePrestasiModal').style.display='flex'; generatePrestasiQRCode();" style="border:none; cursor: pointer; padding: 15px; background: #ecfdf5; border: 1px dashed #2ecc71; border-radius: 12px; text-decoration: none; color: #064e3b; display: flex; align-items: center; justify-content: space-between; transition: 0.2s; width: 100%; font-size: 1rem; text-align: left;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-trophy" style="color: #10b981;"></i> Link Prestasi
                    </div>
                    <i class="fas fa-chevron-right" style="color: #6ee7b7;"></i>
                </button>
            </div>
        </div>
    
    </div>
</div>

<!-- Share Modal (Formulir) -->
<div id="shareModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div style="background: white; width: 450px; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); text-align: center; position: relative; animation: fadeIn 0.3s;">
        <button onclick="document.getElementById('shareModal').style.display='none'" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #999;">&times;</button>
        
        <h3 style="margin-bottom: 20px; font-weight: 700;">Bagikan Formulir</h3>
        
        <div style="margin-bottom: 20px; display: flex; justify-content: center;">
            <canvas id="qr-code"></canvas>
        </div>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border: 1px solid #eee;">
            <input type="text" value="{{ route('pilihan-eskul.form') }}" id="formUrl" readonly style="width: 100%; border: none; background: transparent; color: #555; outline: none; font-family: monospace;">
            <button onclick="copyToClipboard('formUrl')" style="background: white; border: 1px solid #ddd; padding: 5px 10px; border-radius: 8px; cursor: pointer; color: #555; font-size: 0.9rem;" title="Salin Link">
                <i class="fas fa-copy"></i>
            </button>
        </div>

        <div style="display: flex; gap: 10px; justify-content: center;">
            <a href="{{ route('pilihan-eskul.form') }}" target="_blank" class="btn-submit" style="background: #3498db; width: auto; font-size: 0.9rem;">
                <i class="fas fa-external-link-alt"></i> Buka
            </a>
            <a href="https://wa.me/?text=Silakan%20isi%20Formulir%20Pilihan%20Eskul%20SDIT%20AN%20NADZIR%20melalui%20link%20berikut:%20{{ route('pilihan-eskul.form') }}" target="_blank" class="btn-submit" style="background: #25D366; width: auto; font-size: 0.9rem;">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
        </div>
    </div>
</div>

<!-- Share Modal (Prestasi) -->
<div id="sharePrestasiModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div style="background: white; width: 450px; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); text-align: center; position: relative; animation: fadeIn 0.3s;">
        <button onclick="document.getElementById('sharePrestasiModal').style.display='none'" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #999;">&times;</button>
        
        <h3 style="margin-bottom: 20px; font-weight: 700;">Bagikan Link Prestasi</h3>
        
        <div style="margin-bottom: 20px; display: flex; justify-content: center;">
            <canvas id="qr-code-prestasi"></canvas>
        </div>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border: 1px solid #eee;">
            <input type="text" value="{{ route('student-status.index') }}" id="prestasiUrl" readonly style="width: 100%; border: none; background: transparent; color: #555; outline: none; font-family: monospace;">
            <button onclick="copyToClipboard('prestasiUrl')" style="background: white; border: 1px solid #ddd; padding: 5px 10px; border-radius: 8px; cursor: pointer; color: #555; font-size: 0.9rem;" title="Salin Link">
                <i class="fas fa-copy"></i>
            </button>
        </div>

        <div style="display: flex; gap: 10px; justify-content: center;">
            <a href="{{ route('student-status.index') }}" target="_blank" class="btn-submit" style="background: #10b981; width: auto; font-size: 0.9rem;">
                <i class="fas fa-external-link-alt"></i> Buka
            </a>
            <a href="https://wa.me/?text=Cek%20Prestasi%20dan%20Status%20Eskul%20Siswa%20SDIT%20AN%20NADZIR%20di%20sini:%20{{ route('student-status.index') }}" target="_blank" class="btn-submit" style="background: #25D366; width: auto; font-size: 0.9rem;">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
        </div>
    </div>
</div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- Eskul Popularity Chart ---
        const ctxEskul = document.getElementById('eskulChart').getContext('2d');
        
        // Gradient for Bar Chart
        let gradientBlue = ctxEskul.createLinearGradient(0, 0, 0, 400);
        gradientBlue.addColorStop(0, '#34d399'); // Green transition
        gradientBlue.addColorStop(1, '#059669');

        new Chart(ctxEskul, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartEskulLabels) !!},
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: {!! json_encode($chartEskulData) !!},
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(5, 150, 105, 0.8)',
                        'rgba(4, 120, 87, 0.8)',
                        'rgba(52, 211, 153, 0.8)',
                        'rgba(110, 231, 183, 0.8)'
                    ],
                    borderColor: 'transparent',
                    borderWidth: 1,
                    borderRadius: 8,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 10,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f0' },
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // --- Attendance Chart ---
        const ctxAtt = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctxAtt, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alpa'],
                datasets: [{
                    data: {!! json_encode($chartAttendanceData) !!},
                    backgroundColor: [
                        '#2ecc71', // Green
                        '#f1c40f', // Yellow
                        '#3498db', // Blue
                        '#e74c3c'  // Red
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 20 }
                    }
                }
            }
        });
    });
</script>

<script>
    function copyToClipboard(elementId) {
        var copyText = document.getElementById(elementId);
        copyText.select();
        copyText.setSelectionRange(0, 99999); 
        navigator.clipboard.writeText(copyText.value);
        
        // Visual feedback
        var btn = event.currentTarget;
        var original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check" style="color: #2ecc71;"></i>';
        setTimeout(function(){ btn.innerHTML = original; }, 2000);
    }

    function generateQRCode() {
        var qr = new QRious({
          element: document.getElementById('qr-code'),
          value: '{{ route('pilihan-eskul.form') }}',
          size: 150,
          level: 'H'
        });
    }

    function generatePrestasiQRCode() {
        var qr = new QRious({
          element: document.getElementById('qr-code-prestasi'),
          value: '{{ route('student-status.index') }}',
          size: 150,
          level: 'H'
        });
    }

    // Digital Clock & Greeting
    setInterval(() => {
        const now = new Date();
        const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        document.getElementById('clock').innerText = timeString;
        
        const hour = now.getHours();
        let greeting = 'Selamat Datang';
        if (hour >= 5 && hour < 12) greeting = 'Selamat Pagi';
        else if (hour >= 12 && hour < 15) greeting = 'Selamat Siang';
        else if (hour >= 15 && hour < 18) greeting = 'Selamat Sore';
        else greeting = 'Selamat Malam';
        
        if(document.getElementById('greeting')) document.getElementById('greeting').innerText = greeting;
    }, 1000);
</script>
@endsection
