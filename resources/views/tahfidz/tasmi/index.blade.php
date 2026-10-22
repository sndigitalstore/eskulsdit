@extends('layouts.app')

@section('title', 'Ujian Tasmi\' Al-Qur\'an Sekali Duduk')
@section('page-title', 'Ujian Tasmi\' Bil Ghaib')

@section('content')
<div class="content-wrapper" style="padding: 24px; max-width: 1300px; margin: 0 auto;">

    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #6ee7b7; color: #065f46; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <i class="fas fa-check-circle" style="font-size: 1.2rem; color: #10b981;"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    {{-- HEADER & ACTION BUTTONS --}}
    <div style="background: linear-gradient(135deg, #064e3b 0%, #0f766e 100%); border-radius: 16px; padding: 26px 30px; color: white; margin-bottom: 24px; box-shadow: 0 8px 24px rgba(6, 78, 59, 0.2); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.18); padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                <i class="fas fa-award"></i> Program Sertifikasi Hafalan
            </div>
            <h2 style="margin: 0; font-size: 1.65rem; font-weight: 800; letter-spacing: -0.5px;">
                Ujian Tasmi' Al-Qur'an Bil Ghaib
            </h2>
            <p style="margin: 6px 0 0 0; opacity: 0.9; font-size: 0.92rem;">
                Ujian kelulusan sekali duduk (1/2 Juz, 1 Juz, 2 Juz) tanpa melihat mushaf dengan penilaian kesalahan & penerbitan Syahadah Resmi.
            </p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
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
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border-top: 4px solid #0f766e;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Peserta Tasmi'</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $totalExams }} <span style="font-size: 0.9rem; font-weight: 500; color: #94a3b8;">Ujian</span></div>
            <div style="font-size: 0.78rem; color: #0f766e; margin-top: 4px; font-weight: 600;">Semua Kategori Juz</div>
        </div>
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border-top: 4px solid #10b981;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Lulus Bersyahadah</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #10b981; margin-top: 4px;">{{ $passedCount }} <span style="font-size: 0.9rem; font-weight: 500; color: #94a3b8;">Siswa</span></div>
            <div style="font-size: 0.78rem; color: #059669; margin-top: 4px; font-weight: 600;">Berhak Syahadah Resmi</div>
        </div>
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border-top: 4px solid #d97706;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Predikat Mumtaz (Istimewa)</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #d97706; margin-top: 4px;">{{ $mumtazCount }} <span style="font-size: 0.9rem; font-weight: 500; color: #94a3b8;">Siswa</span></div>
            <div style="font-size: 0.78rem; color: #b45309; margin-top: 4px; font-weight: 600;">Maksimal 3 Kesalahan</div>
        </div>
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border-top: 4px solid #ef4444;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Perlu Penguatan (Remedial)</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #ef4444; margin-top: 4px;">{{ $retestCount }} <span style="font-size: 0.9rem; font-weight: 500; color: #94a3b8;">Siswa</span></div>
            <div style="font-size: 0.78rem; color: #dc2626; margin-top: 4px; font-weight: 600;">Ujian Ulang Maqra</div>
        </div>
    </div>

    {{-- FILTER & SEARCH BAR --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap;">
        <form method="GET" action="{{ route('tasmi.index') }}" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; width: 100%;">
            <div style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: #475569;">Tahun Ajaran:</label>
                <select name="year_id" onchange="this.form.submit()" style="padding: 7px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.88rem; background: white;">
                    @foreach($academicYears as $y)
                        <option value="{{ $y->id }}" {{ $y->id == $selectedYearId ? 'selected' : '' }}>
                            TP {{ $y->name }} {{ $y->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: #475569;">Status:</label>
                <select name="status" onchange="this.form.submit()" style="padding: 7px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.88rem; background: white;">
                    <option value="">Semua Status</option>
                    <option value="passed" {{ $statusFilter === 'passed' ? 'selected' : '' }}>Lulus (Bersyahadah)</option>
                    <option value="retest" {{ $statusFilter === 'retest' ? 'selected' : '' }}>Perlu Mengulang</option>
                </select>
            </div>

            <div style="flex-grow: 1; max-width: 320px; margin-left: auto;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari Nama Siswa / NIS / Kelas..."
                       style="width: 100%; padding: 7px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box;">
            </div>

            <button type="submit" style="background: #0f766e; color: white; border: none; padding: 7px 16px; border-radius: 8px; font-weight: 700; cursor: pointer;">
                <i class="fas fa-search"></i> Cari
            </button>
            @if($search || $statusFilter)
                <a href="{{ route('tasmi.index') }}" style="color: #64748b; font-size: 0.85rem; text-decoration: none;">Reset</a>
            @endif
        </form>
    </div>

    {{-- TABEL DATA PESERTA UJIAN TASMI' --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
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

        @if($exams->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
                {{ $exams->links() }}
            </div>
        @endif
    </div>

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

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
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

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
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
