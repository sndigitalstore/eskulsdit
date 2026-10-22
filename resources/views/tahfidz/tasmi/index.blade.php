<style>
    @media (max-width: 768px) {
        .tasmi-content-wrapper {
            padding: 14px 10px !important;
        }
        .tasmi-header-banner {
            padding: 18px 16px !important;
            border-radius: 14px !important;
        }
        .tasmi-header-banner h2 {
            font-size: 1.35rem !important;
        }
        .tasmi-header-actions {
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 8px !important;
        }
        .tasmi-header-actions button,
        .tasmi-header-actions a {
            width: 100% !important;
            justify-content: center !important;
            text-align: center !important;
            box-sizing: border-box !important;
        }
        .tasmi-kpi-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }
        .tasmi-kpi-card {
            padding: 14px !important;
        }
        .tasmi-kpi-num {
            font-size: 1.4rem !important;
        }
        .tasmi-filter-box {
            padding: 12px 14px !important;
        }
        .tasmi-filter-form {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 10px !important;
        }
        .tasmi-filter-group {
            width: 100% !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            gap: 8px !important;
        }
        .tasmi-filter-group select {
            flex-grow: 1 !important;
        }
        .tasmi-search-wrap {
            max-width: 100% !important;
            width: 100% !important;
            margin-left: 0 !important;
        }
        .tasmi-desktop-table {
            display: none !important;
        }
        .tasmi-mobile-cards {
            display: flex !important;
            flex-direction: column !important;
            gap: 12px !important;
        }
        .tasmi-form-grid-2 {
            grid-template-columns: 1fr !important;
        }
    }
    @media (min-width: 769px) {
        .tasmi-mobile-cards {
            display: none !important;
        }
    }
</style>

<div class="content-wrapper tasmi-content-wrapper" style="padding: 24px; max-width: 1300px; margin: 0 auto;">

    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #6ee7b7; color: #065f46; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <i class="fas fa-check-circle" style="font-size: 1.2rem; color: #10b981;"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    {{-- HEADER & ACTION BUTTONS --}}
    <div class="tasmi-header-banner" style="background: linear-gradient(135deg, #064e3b 0%, #0f766e 100%); border-radius: 16px; padding: 26px 30px; color: white; margin-bottom: 24px; box-shadow: 0 8px 24px rgba(6, 78, 59, 0.2); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="flex: 1 1 300px;">
            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.18); padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                <i class="fas fa-award"></i> Program Sertifikasi Hafalan
            </div>
            <h2 style="margin: 0; font-size: 1.65rem; font-weight: 800; letter-spacing: -0.5px;">
                Ujian Tasmi' Al-Qur'an Bil Ghaib
            </h2>
            <p style="margin: 6px 0 0 0; opacity: 0.9; font-size: 0.92rem; line-height: 1.5;">
                Ujian kelulusan sekali duduk (1/2 Juz, 1 Juz, 2 Juz) tanpa melihat mushaf dengan penilaian kesalahan & penerbitan Syahadah Resmi.
            </p>
        </div>
        <div class="tasmi-header-actions" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" onclick="openTasmiModal()"
                    style="background: #10b981; color: white; border: none; padding: 11px 22px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35); transition: transform 0.15s;"
                    onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='none'">
                <i class="fas fa-plus-circle"></i> Input Ujian Tasmi' Baru
            </button>
            <a href="{{ route('tahfidz-groups.index') }}"
               style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 11px 18px; border-radius: 10px; font-weight: 600; font-size: 0.92rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- KPI METRICS --}}
    <div class="tasmi-kpi-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="tasmi-kpi-card" style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border-top: 4px solid #0f766e;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Peserta Tasmi'</div>
            <div class="tasmi-kpi-num" style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $totalExams }} <span style="font-size: 0.9rem; font-weight: 500; color: #94a3b8;">Ujian</span></div>
            <div style="font-size: 0.78rem; color: #0f766e; margin-top: 4px; font-weight: 600;">Semua Kategori Juz</div>
        </div>
        <div class="tasmi-kpi-card" style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border-top: 4px solid #10b981;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Lulus Bersyahadah</div>
            <div class="tasmi-kpi-num" style="font-size: 1.8rem; font-weight: 800; color: #10b981; margin-top: 4px;">{{ $passedCount }} <span style="font-size: 0.9rem; font-weight: 500; color: #94a3b8;">Siswa</span></div>
            <div style="font-size: 0.78rem; color: #059669; margin-top: 4px; font-weight: 600;">Berhak Syahadah Resmi</div>
        </div>
        <div class="tasmi-kpi-card" style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border-top: 4px solid #d97706;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Predikat Mumtaz</div>
            <div class="tasmi-kpi-num" style="font-size: 1.8rem; font-weight: 800; color: #d97706; margin-top: 4px;">{{ $mumtazCount }} <span style="font-size: 0.9rem; font-weight: 500; color: #94a3b8;">Siswa</span></div>
            <div style="font-size: 0.78rem; color: #b45309; margin-top: 4px; font-weight: 600;">Maks. 3 Kesalahan</div>
        </div>
        <div class="tasmi-kpi-card" style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border-top: 4px solid #ef4444;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Perlu Penguatan</div>
            <div class="tasmi-kpi-num" style="font-size: 1.8rem; font-weight: 800; color: #ef4444; margin-top: 4px;">{{ $retestCount }} <span style="font-size: 0.9rem; font-weight: 500; color: #94a3b8;">Siswa</span></div>
            <div style="font-size: 0.78rem; color: #dc2626; margin-top: 4px; font-weight: 600;">Remedial Maqra</div>
        </div>
    </div>

    {{-- FILTER & SEARCH BAR --}}
    <div class="tasmi-filter-box" style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 20px; margin-bottom: 20px;">
        <form class="tasmi-filter-form" method="GET" action="{{ route('tasmi.index') }}" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; width: 100%;">
            <div class="tasmi-filter-group" style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: #475569; white-space: nowrap;">Tahun Ajaran:</label>
                <select name="year_id" onchange="this.form.submit()" style="padding: 7px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.88rem; background: white;">
                    @foreach($academicYears as $y)
                        <option value="{{ $y->id }}" {{ $y->id == $selectedYearId ? 'selected' : '' }}>
                            TP {{ $y->name }} {{ $y->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="tasmi-filter-group" style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: #475569; white-space: nowrap;">Status:</label>
                <select name="status" onchange="this.form.submit()" style="padding: 7px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.88rem; background: white;">
                    <option value="">Semua Status</option>
                    <option value="passed" {{ $statusFilter === 'passed' ? 'selected' : '' }}>Lulus (Bersyahadah)</option>
                    <option value="retest" {{ $statusFilter === 'retest' ? 'selected' : '' }}>Perlu Mengulang</option>
                </select>
            </div>

            <div class="tasmi-search-wrap" style="flex-grow: 1; max-width: 320px; margin-left: auto;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari Nama Siswa / NIS / Kelas..."
                       style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 8px; align-items: center;">
                <button type="submit" style="background: #0f766e; color: white; border: none; padding: 8px 18px; border-radius: 8px; font-weight: 700; cursor: pointer;">
                    <i class="fas fa-search"></i> Cari
                </button>
                @if($search || $statusFilter)
                    <a href="{{ route('tasmi.index') }}" style="color: #64748b; font-size: 0.85rem; text-decoration: none; padding: 7px 10px;">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- DESKTOP TABEL DATA PESERTA UJIAN TASMI' (VISIBLE ON DESKTOP) --}}
    <div class="tasmi-desktop-table" style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.02); margin-bottom: 20px;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <th style="padding: 14px 18px; width: 50px; text-align: center;">No</th>
                    <th style="padding: 14px 18px;">Siswa & Rombel</th>
                    <th style="padding: 14px 18px;">Kategori & Juz</th>
                    <th style="padding: 14px 18px;">Tanggal & Penguji</th>
                    <th style="padding: 14px 18px; text-align: center;">Kesalahan</th>
                    <th style="padding: 14px 18px; text-align: center;">Skor & Predikat</th>
                    <th style="padding: 14px 18px; text-align: center; width: 220px;">Aksi & Cetak</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $index => $exam)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        <td style="padding: 14px 18px; text-align: center; color: #94a3b8; font-weight: 600;">
                            {{ $exams->firstItem() + $index }}
                        </td>
                        <td style="padding: 14px 18px;">
                            <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">
                                {{ $exam->student->name ?? '-' }}
                            </div>
                            <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">
                                Kelas: <strong>{{ $exam->student->class ?? '-' }}</strong>
                                @if($exam->student?->nis) · NIS: {{ $exam->student->nis }} @endif
                            </div>
                        </td>
                        <td style="padding: 14px 18px;">
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: 0.78rem;">
                                {{ $exam->category_label }}
                            </span>
                            <div style="font-weight: 700; color: #047857; font-size: 0.88rem; margin-top: 4px;">
                                <i class="fas fa-bookmark" style="font-size: 0.78rem;"></i> {{ $exam->juz_tested }}
                            </div>
                        </td>
                        <td style="padding: 14px 18px;">
                            <div style="font-weight: 600; color: #1e293b;">
                                {{ \Carbon\Carbon::parse($exam->exam_date)->translatedFormat('d F Y') }}
                            </div>
                            <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">
                                Penguji: <strong>{{ $exam->examiner_name }}</strong>
                            </div>
                        </td>
                        <td style="padding: 14px 18px; text-align: center;">
                            <div style="font-weight: 800; font-size: 1rem; color: {{ $exam->total_mistakes <= 3 ? '#10b981' : ($exam->total_mistakes <= 7 ? '#f59e0b' : '#ef4444') }};">
                                {{ $exam->total_mistakes }} <span style="font-size: 0.75rem; font-weight: 500;">kali</span>
                            </div>
                            <div style="font-size: 0.72rem; color: #64748b; margin-top: 1px;">
                                Fath: {{ $exam->mistakes_fath }} · Tajwid: {{ $exam->mistakes_tajwid }} · Waqaf: {{ $exam->mistakes_waqaf }}
                            </div>
                        </td>
                        <td style="padding: 14px 18px; text-align: center;">
                            <div style="font-weight: 800; font-size: 1.1rem; color: #0f766e;">
                                {{ $exam->final_score }}
                            </div>
                            <span style="display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; background: {{ $exam->status === 'passed' ? '#dcfce7' : '#fee2e2' }}; color: {{ $exam->status === 'passed' ? '#15803d' : '#b91c1c' }}; margin-top: 2px;">
                                {{ $exam->predicate }}
                            </span>
                        </td>
                        <td style="padding: 14px 18px; text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                @if($exam->status === 'passed')
                                    <a href="{{ route('tasmi.certificate', $exam->id) }}" target="_blank"
                                       style="background: #f59e0b; color: #78350f; font-weight: 700; font-size: 0.78rem; padding: 6px 12px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);"
                                       title="Cetak Piagam Syahadah Emas">
                                        <i class="fas fa-certificate"></i> Syahadah
                                    </a>
                                @endif
                                <a href="{{ route('tasmi.report', $exam->id) }}" target="_blank"
                                   style="background: #0f766e; color: white; font-weight: 600; font-size: 0.78rem; padding: 6px 12px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;"
                                   title="Cetak Berita Acara Penilaian">
                                    <i class="fas fa-file-alt"></i> Berita Acara
                                </a>

                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('tasmi.destroy', $exam->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ujian tasmi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 6px;" title="Hapus Ujian">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 50px 20px; color: #94a3b8;">
                            <i class="fas fa-quran" style="font-size: 3rem; margin-bottom: 12px; display: block; opacity: 0.35;"></i>
                            <div style="font-size: 1rem; font-weight: 600; color: #64748b;">Belum Ada Riwayat Ujian Tasmi'</div>
                            <p style="font-size: 0.85rem; margin: 4px 0 16px 0;">Silakan klik tombol "Input Ujian Tasmi' Baru" di atas untuk mencatat peserta ujian tasmi' bil ghaib.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS VIEW (VISIBLE ON SMARTPHONE <= 768px) --}}
    <div class="tasmi-mobile-cards" style="margin-bottom: 20px;">
        @forelse($exams as $index => $exam)
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 12px; border-left: 5px solid {{ $exam->status === 'passed' ? '#10b981' : '#ef4444' }};">
                {{-- Card Header: Nama & Predikat Badge --}}
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <div>
                        <div style="font-weight: 800; color: #0f172a; font-size: 1rem; line-height: 1.3;">
                            {{ $exam->student->name ?? '-' }}
                        </div>
                        <div style="font-size: 0.8rem; color: #64748b; margin-top: 3px;">
                            Kelas: <strong>{{ $exam->student->class ?? '-' }}</strong>
                            @if($exam->student?->nis) · NIS: {{ $exam->student->nis }} @endif
                        </div>
                    </div>
                    <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; background: {{ $exam->status === 'passed' ? '#dcfce7' : '#fee2e2' }}; color: {{ $exam->status === 'passed' ? '#15803d' : '#b91c1c' }}; white-space: nowrap;">
                        {{ $exam->predicate }}
                    </span>
                </div>

                {{-- Kategori & Penguji Bar --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: #f8fafc; padding: 10px 12px; border-radius: 10px; font-size: 0.82rem;">
                    <div>
                        <div style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Kategori & Juz</div>
                        <div style="font-weight: 700; color: #0369a1; margin-top: 2px;">
                            <span style="background: #e0f2fe; padding: 2px 6px; border-radius: 4px; font-size: 0.76rem;">{{ $exam->category_label }}</span>
                        </div>
                        <div style="font-weight: 700; color: #047857; margin-top: 4px; font-size: 0.85rem;">
                            <i class="fas fa-bookmark" style="font-size: 0.75rem;"></i> {{ $exam->juz_tested }}
                        </div>
                    </div>
                    <div>
                        <div style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Waktu & Penguji</div>
                        <div style="font-weight: 600; color: #1e293b; margin-top: 2px;">
                            {{ \Carbon\Carbon::parse($exam->exam_date)->translatedFormat('d M Y') }}
                        </div>
                        <div style="color: #475569; margin-top: 3px; font-size: 0.78rem;">
                            <i class="fas fa-user-tie" style="color: #94a3b8;"></i> {{ $exam->examiner_name }}
                        </div>
                    </div>
                </div>

                {{-- Evaluasi Skor & Detail Kesalahan --}}
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; background: #f1f5f9; border-radius: 10px; font-size: 0.85rem;">
                    <div>
                        <div style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Total Kesalahan</div>
                        <div style="margin-top: 2px;">
                            <strong style="color: {{ $exam->total_mistakes <= 3 ? '#10b981' : ($exam->total_mistakes <= 7 ? '#f59e0b' : '#ef4444') }}; font-size: 0.95rem;">
                                {{ $exam->total_mistakes }}x
                            </strong>
                            <span style="font-size: 0.72rem; color: #64748b; margin-left: 3px;">
                                (F:{{ $exam->mistakes_fath }} T:{{ $exam->mistakes_tajwid }} W:{{ $exam->mistakes_waqaf }})
                            </span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Nilai Akhir</div>
                        <div style="font-weight: 800; font-size: 1.25rem; color: #0f766e; margin-top: 2px;">
                            {{ $exam->final_score }}
                        </div>
                    </div>
                </div>

                @if($exam->notes)
                    <div style="font-size: 0.78rem; color: #64748b; background: #fffbeb; border: 1px dashed #fde68a; padding: 8px 10px; border-radius: 8px; line-height: 1.4;">
                        <i class="fas fa-comment-dots" style="color: #d97706;"></i> <em>{{ $exam->notes }}</em>
                    </div>
                @endif

                {{-- Aksi Buttons --}}
                <div style="display: flex; gap: 8px; align-items: center; margin-top: 4px;">
                    @if($exam->status === 'passed')
                        <a href="{{ route('tasmi.certificate', $exam->id) }}" target="_blank"
                           style="flex: 1; background: #f59e0b; color: #78350f; font-weight: 700; font-size: 0.82rem; padding: 9px 12px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);">
                            <i class="fas fa-certificate"></i> Syahadah
                        </a>
                    @endif
                    <a href="{{ route('tasmi.report', $exam->id) }}" target="_blank"
                       style="flex: 1; background: #0f766e; color: white; font-weight: 600; font-size: 0.82rem; padding: 9px 12px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                        <i class="fas fa-file-alt"></i> Berita Acara
                    </a>
                    @if(Auth::user()->role === 'admin')
                        <form action="{{ route('tasmi.destroy', $exam->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ujian tasmi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #fee2e2; border: 1px solid #fecaca; color: #ef4444; cursor: pointer; padding: 9px 12px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;" title="Hapus Ujian">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; text-align: center; padding: 40px 20px; color: #94a3b8;">
                <i class="fas fa-quran" style="font-size: 2.5rem; margin-bottom: 12px; display: block; opacity: 0.35;"></i>
                <div style="font-size: 0.95rem; font-weight: 600; color: #64748b;">Belum Ada Riwayat Ujian Tasmi'</div>
                <p style="font-size: 0.82rem; margin: 4px 0 14px 0;">Silakan klik tombol "Input Ujian Tasmi' Baru" di atas untuk mencatat peserta ujian tasmi' bil ghaib.</p>
            </div>
        @endforelse
    </div>

    @if($exams->hasPages())
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 18px; margin-bottom: 24px;">
            {{ $exams->links() }}
        </div>
    @endif

</div>

{{-- MODAL INPUT UJIAN TASMI' BARU --}}
<div id="modalTasmi" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 18px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative;">
        <div style="background: linear-gradient(135deg, #064e3b, #0f766e); padding: 20px 24px; color: white; border-radius: 18px 18px 0 0; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-award" style="font-size: 1.3rem; color: #34d399;"></i>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 700;">Formulir Ujian Tasmi' Bil Ghaib</h3>
            </div>
            <button type="button" onclick="closeTasmiModal()" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>

        <form method="POST" action="{{ route('tasmi.store') }}" style="padding: 24px;">
            @csrf
            <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                    Nama Santri / Siswa <span style="color: red;">*</span>
                </label>
                <select name="student_id" required style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.9rem;">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} (Kelas {{ $s->class }}) {{ $s->nis ? '· NIS ' . $s->nis : '' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="tasmi-form-grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Kategori Tasmi' <span style="color: red;">*</span>
                    </label>
                    <select name="category" required style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.9rem;">
                        <option value="satu_juz">Tasmi' 1 Juz Bil Ghaib</option>
                        <option value="setengah_juz">Tasmi' Setengah Juz</option>
                        <option value="dua_juz">Tasmi' 2 Juz Sekaligus</option>
                        <option value="tiga_juz">Tasmi' 3 Juz Sekaligus</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Juz Yang Diuji <span style="color: red;">*</span>
                    </label>
                    <input type="text" name="juz_tested" value="Juz 30" required placeholder="Contoh: Juz 30, Juz 29, Juz 1"
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box;">
                </div>
            </div>

            <div class="tasmi-form-grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Tanggal Ujian <span style="color: red;">*</span>
                    </label>
                    <input type="date" name="exam_date" value="{{ date('Y-m-d') }}" required
                           style="width: 100%; padding: 9px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Halaqah Tahfidz
                    </label>
                    <select name="mentoring_group_id" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.9rem;">
                        <option value="">-- Tanpa Kelompok Khusus --</option>
                        @foreach($tahfidzGroups as $grp)
                            <option value="{{ $grp->id }}">{{ $grp->name }} ({{ $grp->mentor_name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                    Penguji / Munaqisy Tasmi' <span style="color: red;">*</span>
                </label>
                <input type="text" name="examiner_name" required placeholder="Nama Ustadz / Ustadzah Penguji"
                       value="{{ Auth::user()->name }}"
                       style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            {{-- FORM CATATAN KESALAHAN --}}
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                <div style="font-weight: 700; font-size: 0.88rem; color: #064e3b; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-clipboard-check"></i> Rubrik Penghitungan Kesalahan Ujian Sekali Duduk
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                    <div>
                        <label style="display: block; font-size: 0.76rem; font-weight: 600; color: #475569; margin-bottom: 4px;">
                            Salah Fath / Tebak (-2.0)
                        </label>
                        <input type="number" name="mistakes_fath" id="fathInput" value="0" min="0" max="50" required
                               oninput="calcTasmiScore()"
                               style="width: 100%; padding: 8px 10px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box; text-align: center; font-weight: 700;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.76rem; font-weight: 600; color: #475569; margin-bottom: 4px;">
                            Salah Tajwid (-1.0)
                        </label>
                        <input type="number" name="mistakes_tajwid" id="tajwidInput" value="0" min="0" max="50" required
                               oninput="calcTasmiScore()"
                               style="width: 100%; padding: 8px 10px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box; text-align: center; font-weight: 700;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.76rem; font-weight: 600; color: #475569; margin-bottom: 4px;">
                            Salah Waqaf (-0.5)
                        </label>
                        <input type="number" name="mistakes_waqaf" id="waqafInput" value="0" min="0" max="50" required
                               oninput="calcTasmiScore()"
                               style="width: 100%; padding: 8px 10px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box; text-align: center; font-weight: 700;">
                    </div>
                </div>

                {{-- LIVE ESTIMATED SCORE --}}
                <div style="margin-top: 12px; padding-top: 10px; border-top: 1px dashed #cbd5e1; display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 0.8rem; color: #64748b;">
                        Total Kesalahan: <strong id="lblTotalMistakes" style="color: #0f172a;">0 kali</strong>
                    </div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: #047857;">
                        Estimasi Skor: <span id="lblEstimatedScore" style="font-size: 1.1rem; color: #0f766e;">100.0</span>
                        · <span id="lblEstimatedPred" style="color: #10b981;">Mumtaz</span>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                    Catatan & Nasihat Penguji
                </label>
                <textarea name="notes" rows="2" placeholder="Catatan evaluasi kelancaran makharijul huruf atau ayat yang perlu diperkuat..."
                          style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeTasmiModal()"
                        style="padding: 10px 18px; border-radius: 8px; border: 1px solid #cbd5e1; background: white; font-weight: 600; cursor: pointer; color: #475569;">
                    Batal
                </button>
                <button type="submit"
                        style="padding: 10px 24px; border-radius: 8px; border: none; background: #0f766e; color: white; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-save"></i> Simpan Hasil Ujian
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openTasmiModal() {
    const modal = document.getElementById('modalTasmi');
    modal.style.display = 'flex';
}
function closeTasmiModal() {
    const modal = document.getElementById('modalTasmi');
    modal.style.display = 'none';
}

function calcTasmiScore() {
    const fath = parseInt(document.getElementById('fathInput').value) || 0;
    const tajwid = parseInt(document.getElementById('tajwidInput').value) || 0;
    const waqaf = parseInt(document.getElementById('waqafInput').value) || 0;

    const total = fath + tajwid + waqaf;
    const score = Math.max(60, Math.round((100 - (fath * 2.0 + tajwid * 1.0 + waqaf * 0.5)) * 100) / 100);

    let pred = 'Mumtaz';
    let color = '#10b981';

    if (total <= 3) {
        pred = 'Mumtaz (Istimewa)';
        color = '#10b981';
    } else if (total <= 7) {
        pred = 'Jayyid Jiddan (Amat Baik)';
        color = '#0284c7';
    } else if (total <= 12) {
        pred = 'Jayyid (Baik)';
        color = '#d97706';
    } else {
        pred = 'Maqbul (Perlu Remedial)';
        color = '#dc2626';
    }

    document.getElementById('lblTotalMistakes').innerText = total + ' kali';
    document.getElementById('lblEstimatedScore').innerText = score.toFixed(1);
    const predEl = document.getElementById('lblEstimatedPred');
    predEl.innerText = pred;
    predEl.style.color = color;
}
</script>
@endsection
