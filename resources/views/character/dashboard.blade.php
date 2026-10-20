@extends('layouts.app')

@section('title', 'Pantauan Karakter – SMILE KARAKTER')
@section('page-title', 'SMILE KARAKTER')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">

    <!-- Top Banner & Quick Actions -->
    <div class="card" style="margin-bottom: 24px; background: linear-gradient(135deg, #064e3b 0%, #065f46 60%, #047857 100%); color: white; border: none; padding: 26px 30px; position: relative; overflow: hidden;">
        <div style="position: absolute; right: 20px; bottom: -15px; font-size: 8rem; color: rgba(255, 255, 255, 0.08); pointer-events: none;">
            <i class="fas fa-smile-beam"></i>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; position: relative; z-index: 2;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.15); padding: 4px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
                    <i class="fas fa-heart" style="color: #fca5a5;"></i> Modul Pembinaan Karakter Siswa
                </div>
                <h2 style="margin: 0; font-size: 1.6rem; font-weight: 900; letter-spacing: -0.02em;">SMILE KARAKTER SDIT AN NADZIR</h2>
                <p style="margin: 6px 0 0 0; opacity: 0.9; font-size: 0.92rem; max-width: 650px;">
                    Pemantauan holistik pembiasaan akhlak santri: <strong>Shiddiq (Jujur)</strong>, <strong>Mandiri</strong>, <strong>Inovatif & Ibadah</strong>, <strong>Leadership</strong>, dan <strong>Empati</strong> secara akuntabel.
                </p>
            </div>
            
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('character-appreciations.create') }}" class="btn-action-header" style="background: #10b981; font-weight: 700; padding: 10px 18px; border-radius: 12px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4); text-decoration: none;">
                    <i class="fas fa-award"></i> Beri Apresiasi
                </a>
                <a href="{{ route('character-cases.create') }}" class="btn-action-header" style="background: #ef4444; font-weight: 700; padding: 10px 18px; border-radius: 12px; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4); text-decoration: none;">
                    <i class="fas fa-exclamation-triangle"></i> Catat Kasus
                </a>
                <a href="{{ route('character.report') }}" class="btn-action-header" style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); font-weight: 700; padding: 10px 18px; border-radius: 12px; text-decoration: none; border: 1px solid rgba(255, 255, 255, 0.3);">
                    <i class="fas fa-file-signature"></i> Rekap Karakter
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card" style="margin-bottom: 24px; padding: 18px 24px;">
        <form action="{{ route('character.dashboard') }}" method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.85rem;">Tahun Pelajaran</label>
                <select name="year_id" class="form-control">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 180px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.85rem;">Filter Kelas</label>
                <select name="class" class="form-control">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($classes as $c)
                        <option value="{{ $c }}" {{ $selectedClass == $c ? 'selected' : '' }}>Kelas {{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div style="min-width: 120px;">
                <button type="submit" class="btn-action-header btn-blue" style="height: 44px; width: 100%; justify-content: center; font-weight: 700;">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- KPI Summary Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 24px;">
        
        <!-- Apresiasi Kebaikan -->
        <div class="card" style="border-left: 5px solid #10b981; display: flex; align-items: center; gap: 16px; padding: 20px;">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: #dcfce7; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0;">
                <i class="fas fa-award"></i>
            </div>
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Apresiasi Terkumpul</div>
                <div style="font-size: 1.8rem; font-weight: 900; color: #1e293b;">{{ $totalAppreciations }} <span style="font-size: 0.9rem; font-weight: 600; color: #64748b;">kali</span></div>
                <div style="font-size: 0.78rem; color: #10b981; font-weight: 600;">
                    <i class="fas fa-star"></i> Total Poin Kebaikan: +{{ $totalAppreciationPoints }}
                </div>
            </div>
        </div>

        <!-- Data Kasus Pembinaan -->
        <div class="card" style="border-left: 5px solid #ef4444; display: flex; align-items: center; gap: 16px; padding: 20px;">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Kasus Pembinaan</div>
                <div style="font-size: 1.8rem; font-weight: 900; color: #1e293b;">{{ $totalCases }} <span style="font-size: 0.9rem; font-weight: 600; color: #64748b;">catatan</span></div>
                <div style="font-size: 0.78rem; color: #64748b; font-weight: 600;">
                    <span style="color: #ef4444;">{{ $activeCases }} dalam bina</span> • <span style="color: #10b981;">{{ $resolvedCases }} selesai</span>
                </div>
            </div>
        </div>

        <!-- Rasio Penyelesaian -->
        <div class="card" style="border-left: 5px solid #0284c7; display: flex; align-items: center; gap: 16px; padding: 20px;">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0;">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Penyelesaian Kasus</div>
                @php
                    $resolvePct = $totalCases > 0 ? round(($resolvedCases / $totalCases) * 100, 1) : 100;
                @endphp
                <div style="font-size: 1.8rem; font-weight: 900; color: #1e293b;">{{ $resolvePct }}%</div>
                <div style="font-size: 0.78rem; color: #0284c7; font-weight: 600;">
                    <i class="fas fa-shield-alt"></i> Komitmen pembinaan guru
                </div>
            </div>
        </div>

        <!-- Akuntabilitas Sekolah -->
        <div class="card" style="border-left: 5px solid #8b5cf6; display: flex; align-items: center; gap: 16px; padding: 20px;">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: #ede9fe; color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0;">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Sistem Monitoring</div>
                <div style="font-size: 1.35rem; font-weight: 900; color: #1e293b;">SMILE Terpadu</div>
                <div style="font-size: 0.78rem; color: #8b5cf6; font-weight: 600;">
                    Terdokumentasi & Terverifikasi
                </div>
            </div>
        </div>

    </div>

    <!-- 2 Column Section: Top Students vs Needing Attention -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px; margin-bottom: 24px;">
        
        <!-- Bintang Karakter (Top Apresiasi) -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; font-size: 1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-star" style="color: #f59e0b;"></i> Bintang Karakter SMILE Teratas
                </h4>
                <a href="{{ route('character-appreciations.index') }}" style="font-size: 0.82rem; color: #10b981; font-weight: 700; text-decoration: none;">
                    Lihat Semua <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <div style="padding: 12px 20px;">
                @forelse($topStudents as $idx => $st)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: {{ !$loop->last ? '1px solid #f1f5f9' : 'none' }};">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $idx == 0 ? '#fef3c7' : ($idx == 1 ? '#e2e8f0' : '#f1f5f9') }}; color: {{ $idx == 0 ? '#b45309' : '#475569' }}; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">
                                {{ $idx + 1 }}
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #1e293b; font-size: 0.92rem;">{{ $st->name }}</div>
                                <div style="font-size: 0.78rem; color: #64748b;">
                                    Kelas {{ $st->class }} • NIS: {{ $st->nis ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span style="display: inline-block; padding: 3px 10px; border-radius: 999px; background: #dcfce7; color: #15803d; font-weight: 800; font-size: 0.82rem;">
                                +{{ $st->total_points ?? 0 }} Poin
                            </span>
                            <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">{{ $st->appreciation_count }} apresiasi</div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: #94a3b8; padding: 24px 0; font-size: 0.9rem;">
                        Belum ada catatan apresiasi karakter untuk filter ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Santri Memerlukan Pendampingan Khusus -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; font-size: 1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-hand-holding-heart" style="color: #ef4444;"></i> Santri Butuh Pendampingan Pembinaan
                </h4>
                <a href="{{ route('character-cases.index') }}" style="font-size: 0.82rem; color: #ef4444; font-weight: 700; text-decoration: none;">
                    Lihat Semua <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <div style="padding: 12px 20px;">
                @forelse($attentionStudents as $idx => $st)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: {{ !$loop->last ? '1px solid #f1f5f9' : 'none' }};">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #fee2e2; color: #b91c1c; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">
                                {{ $idx + 1 }}
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #1e293b; font-size: 0.92rem;">{{ $st->name }}</div>
                                <div style="font-size: 0.78rem; color: #64748b;">
                                    Kelas {{ $st->class }} • NIS: {{ $st->nis ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span style="display: inline-block; padding: 3px 10px; border-radius: 999px; background: #fef3c7; color: #b45309; font-weight: 800; font-size: 0.82rem;">
                                {{ $st->case_count }} Catatan Kasus
                            </span>
                            <div style="margin-top: 2px;">
                                <a href="{{ route('character-cases.create', ['student_id' => $st->id]) }}" style="font-size: 0.72rem; color: #0284c7; text-decoration: none; font-weight: 600;">
                                    + Bina Santri
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: #10b981; padding: 24px 0; font-size: 0.9rem;">
                        <i class="fas fa-check-circle"></i> Alhamdulillah, tidak ada santri yang memiliki catatan kasus berulang.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- 2 Column Section: Recent Cases vs Recent Appreciations -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px;">
        
        <!-- Kasus Terbaru -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 16px 20px; background: #fff5f5; border-bottom: 1px solid #fed7d7; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #9b2c2c;">
                    <i class="fas fa-exclamation-circle" style="color: #e53e3e;"></i> Catatan Kasus Terkini
                </h4>
                <a href="{{ route('character-cases.create') }}" class="btn-action-header" style="background: #e53e3e; padding: 4px 10px; font-size: 0.75rem; border-radius: 6px; text-decoration: none;">
                    + Input Kasus
                </a>
            </div>
            <div style="padding: 12px 20px;">
                @forelse($recentCases as $c)
                    <div style="padding: 10px 0; border-bottom: {{ !$loop->last ? '1px solid #f1f5f9' : 'none' }};">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                            <div>
                                <strong style="color: #1e293b; font-size: 0.9rem;">{{ $c->student->name ?? '-' }}</strong>
                                <span style="font-size: 0.75rem; color: #64748b;">(Kelas {{ $c->student->class ?? '-' }})</span>
                                <div style="font-size: 0.85rem; color: #334155; margin-top: 2px;">{{ $c->title }}</div>
                            </div>
                            <span style="font-size: 0.72rem; padding: 2px 7px; border-radius: 6px; font-weight: 700; background: {{ $c->severity_badge['bg'] }}; color: {{ $c->severity_badge['color'] }}; white-space: nowrap;">
                                {{ $c->severity_badge['label'] }}
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px; font-size: 0.75rem; color: #64748b;">
                            <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($c->date)->format('d/m/Y') }}</span>
                            <span style="color: {{ $c->status_badge['color'] }}; font-weight: 600;">
                                <i class="fas {{ $c->status_badge['icon'] }}"></i> {{ $c->status_badge['label'] }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: #94a3b8; padding: 24px 0; font-size: 0.88rem;">
                        Belum ada data kasus terbaru.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Apresiasi Terbaru -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 16px 20px; background: #f0fdf4; border-bottom: 1px solid #bbf7d0; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #166534;">
                    <i class="fas fa-medal" style="color: #10b981;"></i> Catatan Apresiasi Terkini
                </h4>
                <a href="{{ route('character-appreciations.create') }}" class="btn-action-header" style="background: #10b981; padding: 4px 10px; font-size: 0.75rem; border-radius: 6px; text-decoration: none;">
                    + Beri Apresiasi
                </a>
            </div>
            <div style="padding: 12px 20px;">
                @forelse($recentAppreciations as $a)
                    <div style="padding: 10px 0; border-bottom: {{ !$loop->last ? '1px solid #f1f5f9' : 'none' }};">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                            <div>
                                <strong style="color: #1e293b; font-size: 0.9rem;">{{ $a->student->name ?? '-' }}</strong>
                                <span style="font-size: 0.75rem; color: #64748b;">(Kelas {{ $a->student->class ?? '-' }})</span>
                                <div style="font-size: 0.85rem; color: #334155; margin-top: 2px;">{{ $a->title }}</div>
                            </div>
                            <span style="font-size: 0.75rem; padding: 2px 8px; border-radius: 6px; font-weight: 800; background: #dcfce7; color: #15803d; white-space: nowrap;">
                                +{{ $a->point }} Poin
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px; font-size: 0.75rem; color: #64748b;">
                            <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</span>
                            <span style="font-weight: 600; color: #10b981;">
                                {{ $a->category }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: #94a3b8; padding: 24px 0; font-size: 0.88rem;">
                        Belum ada data apresiasi terbaru.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
