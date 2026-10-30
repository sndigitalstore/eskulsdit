@extends('layouts.app')

@section('title', 'Radar Mutu & Monitoring Kepala Sekolah - SIMUTQIN')
@section('page-title', 'Monitoring Kepala Sekolah')

@section('content')
<div style="max-width: 1400px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

    <!-- 1. HERO BANNER -->
    <div class="card" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 60%, #047857 100%); color: white; border: none; padding: 26px 30px; position: relative; overflow: hidden; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(6, 78, 59, 0.25);">
        <div style="position: absolute; right: 25px; bottom: -20px; font-size: 8.5rem; color: rgba(255, 255, 255, 0.07); pointer-events: none;">
            <i class="fas fa-chart-line"></i>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; position: relative; z-index: 2;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.15); padding: 5px 14px; border-radius: 999px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
                    <i class="fas fa-shield-alt" style="color: #6ee7b7;"></i> Instrumen Penjaminan Mutu & Supervisi Eksekutif
                </div>
                <h2 style="margin: 0; font-size: 1.7rem; font-weight: 900; letter-spacing: -0.02em;">Radar Mutu & Monitoring Kepala Sekolah</h2>
                <p style="margin: 6px 0 0 0; opacity: 0.92; font-size: 0.95rem; max-width: 720px; line-height: 1.5;">
                    Pemantauan holistik kinerja pendidik di SDIT AN NADZIR. Memadukan evaluasi supervisi KBM, modul ajar, pembinaan Al-Qur'an, kepatuhan ekstrakurikuler, dan peringatan dini secara terpadu.
                </p>
                <div style="margin-top: 10px; font-size: 0.85rem; color: #a7f3d0; font-weight: 600;">
                    <i class="fas fa-calendar-alt"></i> Tahun Ajaran Aktif: <strong>{{ $activeYear ? $activeYear->name : '-' }}</strong> &bull; Semester: <strong>{{ $activeYear ? ($activeYear->active_semester == '1' ? '1 (Ganjil)' : '2 (Genap)') : '-' }}</strong>
                </div>
            </div>
            
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('supervisions.create') }}" class="btn-action-header" style="background: #10b981; color: white; font-weight: 700; padding: 11px 20px; border-radius: 12px; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-plus-circle"></i> Input Supervisi Baru
                </a>
                <a href="{{ route('supervisions.index') }}" class="btn-action-header" style="background: rgba(255, 255, 255, 0.18); backdrop-filter: blur(10px); color: white; font-weight: 700; padding: 11px 20px; border-radius: 12px; text-decoration: none; border: 1px solid rgba(255, 255, 255, 0.3); display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-clipboard-list"></i> Arsip & Riwayat Supervisi
                </a>
            </div>
        </div>
    </div>

    <!-- 2. KPI METRICS CARDS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
        <!-- Card 1: Cakupan Supervisi Guru -->
        <div class="card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; display: flex; flex-direction: column; gap: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Cakupan Supervisi Guru</span>
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <div>
                <div style="font-size: 1.85rem; font-weight: 900; color: #0f172a; line-height: 1;">
                    {{ $supervisionCoverage }}%
                </div>
                <div style="font-size: 0.84rem; color: #64748b; margin-top: 5px;">
                    <strong>{{ $totalSupervisedTeachers }}</strong> dari <strong>{{ $totalTeachers }}</strong> guru telah disupervisi
                </div>
            </div>
            <div style="background: #f1f5f9; height: 8px; border-radius: 999px; overflow: hidden;">
                <div style="background: #059669; height: 100%; width: {{ min(100, $supervisionCoverage) }}%; border-radius: 999px;"></div>
            </div>
        </div>

        <!-- Card 2: Rata-Rata Skor Supervisi -->
        <div class="card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; display: flex; flex-direction: column; gap: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Rata-rata Skor Sekolah</span>
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fas fa-star"></i>
                </div>
            </div>
            <div>
                <div style="font-size: 1.85rem; font-weight: 900; color: #0f172a; line-height: 1;">
                    {{ $avgScore }} <span style="font-size: 1rem; color: #94a3b8; font-weight: 600;">/ 100</span>
                </div>
                <div style="font-size: 0.84rem; color: #64748b; margin-top: 5px;">
                    Predikat Rata-rata: 
                    <strong style="color: {{ $avgScore >= 91 ? '#059669' : ($avgScore >= 81 ? '#2563eb' : ($avgScore >= 71 ? '#d97706' : '#dc2626')) }};">
                        {{ $avgScore >= 91 ? 'Sangat Baik (A)' : ($avgScore >= 81 ? 'Baik (B)' : ($avgScore >= 71 ? 'Cukup (C)' : 'Perlu Pembinaan (D)')) }}
                    </strong>
                </div>
            </div>
            <div style="background: #f1f5f9; height: 8px; border-radius: 999px; overflow: hidden;">
                <div style="background: #2563eb; height: 100%; width: {{ min(100, $avgScore) }}%; border-radius: 999px;"></div>
            </div>
        </div>

        <!-- Card 3: Kepatuhan Pembina Eskul -->
        <div class="card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; display: flex; flex-direction: column; gap: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Kepatuhan Pembina Eskul</span>
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #faf5ff; color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
            <div>
                <div style="font-size: 1.85rem; font-weight: 900; color: #0f172a; line-height: 1;">
                    {{ $coachComplianceRate }}%
                </div>
                <div style="font-size: 0.84rem; color: #64748b; margin-top: 5px;">
                    Tingkat Kehadiran & Jurnal Eskul Terverifikasi
                </div>
            </div>
            <div style="background: #f1f5f9; height: 8px; border-radius: 999px; overflow: hidden;">
                <div style="background: #7c3aed; height: 100%; width: {{ min(100, $coachComplianceRate) }}%; border-radius: 999px;"></div>
            </div>
        </div>

        <!-- Card 4: Prestasi & Karakter -->
        <div class="card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; display: flex; flex-direction: column; gap: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Prestasi & Karakter</span>
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #fffbeb; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
            <div>
                <div style="font-size: 1.85rem; font-weight: 900; color: #0f172a; line-height: 1;">
                    {{ $achievementsCount }} <span style="font-size: 0.95rem; font-weight: 600; color: #64748b;">Prestasi</span>
                </div>
                <div style="font-size: 0.84rem; color: #64748b; margin-top: 5px;">
                    {{ $characterAppreciationsCount }} Apresiasi &bull; {{ $characterCasesCount }} Catatan Kasus
                </div>
            </div>
            <div style="display: flex; gap: 8px; font-size: 0.78rem; font-weight: 700;">
                <span style="background: #ecfdf5; color: #047857; padding: 2px 8px; border-radius: 6px;">{{ $characterAppreciationsCount }} Bintang SMILE</span>
                <span style="background: #fef2f2; color: #b91c1c; padding: 2px 8px; border-radius: 6px;">{{ $characterCasesCount }} Kasus</span>
            </div>
        </div>
    </div>

    <!-- 3. MAIN SECTION: DISTRIBUSI & EARLY WARNING -->
    <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 24px; align-items: start;">
        
        <!-- LEFT COLUMN: Analisis Kinerja & Riwayat Terbaru -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Distribusi Predikat & Tipe -->
            <div class="card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <div>
                        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #1e293b;">
                            <i class="fas fa-poll" style="color: #059669; margin-right: 6px;"></i> Distribusi Kinerja Supervisi Guru
                        </h3>
                        <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: #64748b;">Rekapitulasi predikat dan cakupan fokus instrumen.</p>
                    </div>
                </div>

                <!-- Predikat Progress Bars -->
                <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">
                            <span style="color: #059669;"><i class="fas fa-circle" style="font-size: 0.65rem;"></i> Sangat Baik (91-100)</span>
                            <span style="color: #0f172a;">{{ $predicateDistribution['Sangat Baik'] }} Guru</span>
                        </div>
                        <div style="background: #f1f5f9; height: 8px; border-radius: 999px; overflow: hidden;">
                            <div style="background: #059669; height: 100%; width: {{ $totalSupervisedTeachers > 0 ? ($predicateDistribution['Sangat Baik'] / $totalSupervisedTeachers) * 100 : 0 }}%;"></div>
                        </div>
                    </div>

                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">
                            <span style="color: #2563eb;"><i class="fas fa-circle" style="font-size: 0.65rem;"></i> Baik (81-90)</span>
                            <span style="color: #0f172a;">{{ $predicateDistribution['Baik'] }} Guru</span>
                        </div>
                        <div style="background: #f1f5f9; height: 8px; border-radius: 999px; overflow: hidden;">
                            <div style="background: #2563eb; height: 100%; width: {{ $totalSupervisedTeachers > 0 ? ($predicateDistribution['Baik'] / $totalSupervisedTeachers) * 100 : 0 }}%;"></div>
                        </div>
                    </div>

                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">
                            <span style="color: #d97706;"><i class="fas fa-circle" style="font-size: 0.65rem;"></i> Cukup (71-80)</span>
                            <span style="color: #0f172a;">{{ $predicateDistribution['Cukup'] }} Guru</span>
                        </div>
                        <div style="background: #f1f5f9; height: 8px; border-radius: 999px; overflow: hidden;">
                            <div style="background: #d97706; height: 100%; width: {{ $totalSupervisedTeachers > 0 ? ($predicateDistribution['Cukup'] / $totalSupervisedTeachers) * 100 : 0 }}%;"></div>
                        </div>
                    </div>

                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">
                            <span style="color: #dc2626;"><i class="fas fa-circle" style="font-size: 0.65rem;"></i> Perlu Pembinaan (< 71)</span>
                            <span style="color: #0f172a;">{{ $predicateDistribution['Perlu Pembinaan'] }} Guru</span>
                        </div>
                        <div style="background: #f1f5f9; height: 8px; border-radius: 999px; overflow: hidden;">
                            <div style="background: #dc2626; height: 100%; width: {{ $totalSupervisedTeachers > 0 ? ($predicateDistribution['Perlu Pembinaan'] / $totalSupervisedTeachers) * 100 : 0 }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- 4 Tipe Instrumen Cards -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; border-top: 1px dashed #e2e8f0; padding-top: 18px;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: #64748b;">KBM di Kelas</div>
                            <div style="font-size: 1.15rem; font-weight: 900; color: #1e293b;">{{ $typeCounts['kbm'] }} <span style="font-size: 0.75rem; font-weight: 500; color: #94a3b8;">sesi</span></div>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: #64748b;">Modul Ajar/RPP</div>
                            <div style="font-size: 1.15rem; font-weight: 900; color: #1e293b;">{{ $typeCounts['modul_ajar'] }} <span style="font-size: 0.75rem; font-weight: 500; color: #94a3b8;">sesi</span></div>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #ecfdf5; color: #047857; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                            <i class="fas fa-quran"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: #64748b;">Halaqah Qur'an</div>
                            <div style="font-size: 1.15rem; font-weight: 900; color: #1e293b;">{{ $typeCounts['quran'] }} <span style="font-size: 0.75rem; font-weight: 500; color: #94a3b8;">sesi</span></div>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #fce7f3; color: #be185d; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                            <i class="fas fa-shapes"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: #64748b;">Ekstrakurikuler</div>
                            <div style="font-size: 1.15rem; font-weight: 900; color: #1e293b;">{{ $typeCounts['eskul'] }} <span style="font-size: 0.75rem; font-weight: 500; color: #94a3b8;">sesi</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supervisi Terbaru -->
            <div class="card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div>
                        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #1e293b;">
                            <i class="fas fa-history" style="color: #2563eb; margin-right: 6px;"></i> Riwayat Supervisi Terbaru
                        </h3>
                        <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: #64748b;">6 observasi terakhir yang telah dievaluasi.</p>
                    </div>
                    <a href="{{ route('supervisions.index') }}" style="font-size: 0.85rem; font-weight: 700; color: #059669; text-decoration: none;">
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                @if($recentSupervisions->isEmpty())
                    <div style="text-align: center; padding: 35px 20px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        <i class="fas fa-clipboard-check" style="font-size: 2.5rem; color: #94a3b8; margin-bottom: 10px;"></i>
                        <p style="margin: 0; font-weight: 600; color: #64748b;">Belum ada instrumen supervisi yang dicatat.</p>
                        <a href="{{ route('supervisions.create') }}" style="display: inline-block; margin-top: 10px; font-size: 0.85rem; font-weight: 700; color: #059669;">
                            + Mulai Supervisi Guru Sekarang
                        </a>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($recentSupervisions as $s)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; transition: all 0.2s ease;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 0.95rem;">
                                        <i class="fas {{ $s->type_icon }}"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; font-size: 0.92rem; color: #0f172a;">
                                            {{ $s->teacher ? $s->teacher->name : '-' }}
                                        </div>
                                        <div style="font-size: 0.78rem; color: #64748b;">
                                            {{ $s->type_label }} &bull; {{ $s->supervision_date->translatedFormat('d M Y') }}
                                            @if($s->class_name) &bull; {{ $s->class_name }} @endif
                                        </div>
                                    </div>
                                </div>

                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="text-align: right;">
                                        <div style="font-weight: 900; font-size: 1.05rem; color: {{ $s->predicate_color }};">
                                            {{ $s->total_score }}
                                        </div>
                                        <div style="font-size: 0.74rem; font-weight: 700; color: {{ $s->predicate_color }};">
                                            {{ $s->predicate }}
                                        </div>
                                    </div>
                                    <a href="{{ route('supervisions.show', $s->id) }}" style="padding: 6px 12px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; color: #334155; font-size: 0.8rem; font-weight: 700; text-decoration: none;">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- RIGHT COLUMN: EARLY WARNING SYSTEM (GURU BELUM DISUPERVISI) -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <div class="card" style="border: 1px solid #fee2e2; background: #fffaf0; border-radius: 14px; padding: 24px; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.04);">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #991b1b;">
                            Deteksi Dini Supervisi
                        </h3>
                        <p style="margin: 2px 0 0 0; font-size: 0.82rem; color: #7f1d1d;">
                            {{ $unsupervisedTeachers->count() }} Guru Belum Disupervisi Semester Ini
                        </p>
                    </div>
                </div>

                <p style="font-size: 0.85rem; color: #475569; line-height: 1.45; margin-bottom: 16px;">
                    Untuk menjaga standar mutu pengajaran di SDIT AN NADZIR, pastikan setiap guru minimal mendapatkan 1 kali supervisi edukatif setiap semester.
                </p>

                @if($unsupervisedTeachers->isEmpty())
                    <div style="padding: 16px; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px; text-align: center; color: #047857; font-weight: 700; font-size: 0.9rem;">
                        <i class="fas fa-check-circle" style="font-size: 1.2rem; margin-bottom: 4px;"></i><br>
                        Alhamdulillah, seluruh guru telah disupervisi semester ini!
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 8px; max-height: 380px; overflow-y: auto; padding-right: 4px;">
                        @foreach($unsupervisedTeachers as $teacher)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; background: #ffffff; border: 1px solid #fed7aa; border-radius: 8px;">
                                <div>
                                    <div style="font-weight: 800; font-size: 0.88rem; color: #1e293b;">
                                        {{ $teacher->name }}
                                    </div>
                                    <div style="font-size: 0.74rem; color: #64748b;">
                                        @if($teacher->homeroom_class)
                                            Wali Kelas {{ $teacher->homeroom_class }} &bull;
                                        @endif
                                        @if($teacher->eskul)
                                            Pembina {{ $teacher->eskul->name }}
                                        @else
                                            Guru Pengajar
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('supervisions.create', ['teacher_id' => $teacher->id]) }}" style="padding: 5px 10px; background: #059669; color: white; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-decoration: none; white-space: nowrap;">
                                    <i class="fas fa-plus"></i> Supervisi
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Petunjuk Panduan Standar Supervisi -->
            <div class="card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 22px; background: #f8fafc;">
                <h4 style="margin: 0 0 10px 0; font-size: 0.95rem; font-weight: 800; color: #334155;">
                    <i class="fas fa-book-open" style="color: #059669; margin-right: 6px;"></i> Rubrik Penilaian Standar
                </h4>
                <ul style="margin: 0; padding-left: 20px; font-size: 0.83rem; color: #475569; display: flex; flex-direction: column; gap: 8px; line-height: 1.45;">
                    <li><strong>Skala Nilai 1 - 4:</strong> 4 = Sangat Baik, 3 = Baik, 2 = Cukup, 1 = Kurang.</li>
                    <li><strong>Predikat Kinerja:</strong> A (>= 91%), B (81 - 90%), C (71 - 80%), D (< 71%).</li>
                    <li><strong>Tindak Lanjut:</strong> Setiap supervisi wajib dilengkapi catatan apresiasi & rekomendasi perbaikan untuk arsip akreditasi.</li>
                </ul>
            </div>

        </div>

    </div>

</div>
@endsection
