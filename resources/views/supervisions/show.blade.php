@extends('layouts.app')

@section('title', 'Detail Lembar Supervisi - SIMUTQIN')
@section('page-title', 'Detail Supervisi Guru')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

    <!-- TOP HEADER & ACTIONS -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 8px; background: #ecfdf5; color: #059669; padding: 4px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 700; margin-bottom: 6px;">
                <i class="fas fa-check-circle"></i> Hasil Observasi & Penilaian
            </div>
            <h2 style="margin: 0; font-size: 1.6rem; font-weight: 900; color: #0f172a; letter-spacing: -0.02em;">
                Lembar Hasil Supervisi: {{ $supervision->teacher ? $supervision->teacher->name : '-' }}
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 0.9rem; color: #64748b;">
                {{ $supervision->type_label }} &bull; Tanggal: {{ $supervision->supervision_date->translatedFormat('d F Y') }}
            </p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('supervisions.index') }}" class="btn" style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #334155; font-weight: 700; padding: 9px 18px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('supervisions.print', $supervision->id) }}" target="_blank" class="btn" style="background: #059669; color: white; font-weight: 700; padding: 9px 18px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);">
                <i class="fas fa-print"></i> Cetak Berita Acara Resmi
            </a>
            @if(in_array(auth()->user()->role, ['admin', 'headmaster', 'curriculum']))
                <a href="{{ route('supervisions.edit', $supervision->id) }}" class="btn" style="background: #eff6ff; color: #2563eb; font-weight: 700; padding: 9px 18px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: 1.5px solid #bfdbfe;">
                    <i class="fas fa-edit"></i> Edit Data
                </a>
            @endif
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    @if(session('success'))
        <div style="background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; padding: 14px 18px; border-radius: 8px; font-weight: 600; font-size: 0.92rem; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle" style="font-size: 1.2rem; color: #10b981;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- 1. HERO SUMMARY CARD -->
    <div class="card" style="border: 1px solid #e2e8f0; border-radius: 16px; padding: 26px 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); background: #ffffff;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 24px;">
            
            <div style="display: flex; align-items: center; gap: 18px;">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15);">
                    <i class="fas {{ $supervision->type_icon }}"></i>
                </div>
                <div>
                    <div style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 6px; background: #f1f5f9; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 6px;">
                        {{ $supervision->type_label }}
                    </div>
                    <h3 style="margin: 0; font-size: 1.35rem; font-weight: 900; color: #0f172a;">
                        {{ $supervision->teacher ? $supervision->teacher->name : '-' }}
                    </h3>
                    <div style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">
                        @if($supervision->subject_name)
                            <strong>{{ $supervision->subject_name }}</strong> &bull;
                        @endif
                        @if($supervision->class_name)
                            {{ $supervision->class_name }} &bull;
                        @endif
                        Tahun Ajaran {{ $supervision->academicYear ? $supervision->academicYear->name : '-' }} (Semester {{ $supervision->semester }})
                    </div>
                </div>
            </div>

            <!-- Score Box -->
            <div style="display: flex; align-items: center; gap: 20px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 14px 22px;">
                <div style="text-align: right;">
                    <div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #64748b;">
                        Skor Akhir
                    </div>
                    <div style="font-size: 2.2rem; font-weight: 900; color: {{ $supervision->predicate_color }}; line-height: 1.1;">
                        {{ $supervision->total_score }}
                    </div>
                </div>
                <div style="border-left: 2px solid #e2e8f0; padding-left: 16px;">
                    <span style="display: inline-block; padding: 5px 12px; border-radius: 8px; font-size: 0.88rem; font-weight: 800; background: {{ $supervision->predicate_color }}18; color: {{ $supervision->predicate_color }};">
                        {{ $supervision->predicate }}
                    </span>
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 4px;">
                        Supervisor: <strong>{{ $supervision->supervisor ? $supervision->supervisor->name : '-' }}</strong>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- 2. TABEL RINCIAN INDIKATOR OBSERVASI -->
    <div class="card" style="border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
        <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b;">
                <i class="fas fa-list-check" style="color: #059669; margin-right: 6px;"></i> Rincian Penilaian Aspek & Indikator
            </h3>
            <span style="font-size: 0.8rem; font-weight: 700; color: #64748b;">
                Skala Penilaian: 1 s.d. 4
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse; margin: 0;">
                <thead>
                    <tr style="background: #ffffff; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em; text-align: left;">
                        <th style="padding: 12px 18px; width: 45px; text-align: center;">No</th>
                        <th style="padding: 12px 18px;">Indikator Pengamatan</th>
                        <th style="padding: 12px 18px; width: 90px; text-align: center;">Skor (1-4)</th>
                        <th style="padding: 12px 18px; width: 140px; text-align: center;">Kategori</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.88rem; color: #1e293b;">
                    @php
                        $index = 1;
                        $aspects = $supervision->aspects_score ?: [];
                    @endphp
                    @forelse($aspects as $key => $item)
                        @php
                            $score = is_array($item) ? ($item['score'] ?? 3) : (int)$item;
                            $title = is_array($item) ? ($item['title'] ?? $key) : $key;
                            $desc = is_array($item) ? ($item['desc'] ?? '') : '';
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 18px; text-align: center; font-weight: 800; color: #64748b;">
                                {{ $index++ }}
                            </td>
                            <td style="padding: 14px 18px;">
                                <div style="font-weight: 800; color: #0f172a; margin-bottom: 2px;">
                                    {{ $title }}
                                </div>
                                @if($desc)
                                    <div style="font-size: 0.8rem; color: #64748b; line-height: 1.4;">
                                        {{ $desc }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 14px 18px; text-align: center;">
                                <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; font-weight: 900; font-size: 1.05rem; background: {{ $score == 4 ? '#ecfdf5' : ($score == 3 ? '#eff6ff' : ($score == 2 ? '#fffbeb' : '#fef2f2')) }}; color: {{ $score == 4 ? '#059669' : ($score == 3 ? '#2563eb' : ($score == 2 ? '#d97706' : '#dc2626')) }};">
                                    {{ $score }}
                                </span>
                            </td>
                            <td style="padding: 14px 18px; text-align: center;">
                                <span style="font-size: 0.8rem; font-weight: 700; color: {{ $score == 4 ? '#059669' : ($score == 3 ? '#2563eb' : ($score == 2 ? '#d97706' : '#dc2626')) }};">
                                    {{ $score == 4 ? 'Sangat Baik' : ($score == 3 ? 'Baik' : ($score == 2 ? 'Cukup' : 'Kurang')) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 30px; color: #94a3b8;">
                                Rincian aspek tidak tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. CATATAN KUALITATIF & REKOMENDASI BOXES -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 18px;">
        
        <!-- Box 1: Kelebihan -->
        <div class="card" style="border: 1px solid #a7f3d0; background: #f0fdf4; border-radius: 14px; padding: 22px;">
            <div style="display: flex; align-items: center; gap: 8px; color: #065f46; font-weight: 800; font-size: 0.95rem; margin-bottom: 10px;">
                <i class="fas fa-thumbs-up" style="color: #10b981;"></i> Kelebihan & Apresiasi Guru
            </div>
            <div style="font-size: 0.88rem; color: #166534; line-height: 1.55; white-space: pre-line;">
                {{ $supervision->strengths ?: 'Belum ada catatan kelebihan khusus.' }}
            </div>
        </div>

        <!-- Box 2: Area Peningkatan -->
        <div class="card" style="border: 1px solid #fed7aa; background: #fffaf0; border-radius: 14px; padding: 22px;">
            <div style="display: flex; align-items: center; gap: 8px; color: #9a3412; font-weight: 800; font-size: 0.95rem; margin-bottom: 10px;">
                <i class="fas fa-lightbulb" style="color: #f59e0b;"></i> Aspek yang Perlu Ditingkatkan
            </div>
            <div style="font-size: 0.88rem; color: #7c2d12; line-height: 1.55; white-space: pre-line;">
                {{ $supervision->improvements ?: 'Belum ada catatan aspek yang perlu ditingkatkan.' }}
            </div>
        </div>

        <!-- Box 3: Rekomendasi Coaching & Follow up -->
        <div class="card" style="border: 1px solid #bfdbfe; background: #eff6ff; border-radius: 14px; padding: 22px; grid-column: 1 / -1;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 10px;">
                <div style="display: flex; align-items: center; gap: 8px; color: #1e3a8a; font-weight: 800; font-size: 0.95rem;">
                    <i class="fas fa-hand-holding-heart" style="color: #3b82f6;"></i> Rekomendasi & Rencana Tindak Lanjut Kepala Sekolah
                </div>
                @if($supervision->follow_up_date)
                    <div style="font-size: 0.8rem; font-weight: 700; color: #2563eb; background: #dbeafe; padding: 3px 10px; border-radius: 6px;">
                        <i class="fas fa-calendar-check"></i> Target Evaluasi: {{ $supervision->follow_up_date->translatedFormat('d F Y') }}
                    </div>
                @endif
            </div>
            <div style="font-size: 0.88rem; color: #1e40af; line-height: 1.55; white-space: pre-line;">
                {{ $supervision->recommendations ?: 'Belum ada rekomendasi tindak lanjut.' }}
            </div>
        </div>

    </div>

</div>
@endsection
