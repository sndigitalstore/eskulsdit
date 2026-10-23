@extends('layouts.app')

@section('title', 'Input Sesi Mentoring & Capaian Tahfidz – ' . $group->name)
@section('page-title', 'Input Sesi & Capaian Tahfidz')

@section('content')

<style>
.form-header {
    background: linear-gradient(135deg, #1e3a5f 0%, #0f766e 100%);
    border-radius: 16px 16px 0 0;
    padding: 24px 28px; color: white;
}
.student-card-item {
    padding: 18px 22px;
    border-bottom: 1px solid #e2e8f0;
    background: white;
    transition: background 0.15s;
}
.student-card-item:hover { background: #fafbfc; }
.student-card-item:last-child { border-bottom: none; }

.status-radio { display: none; }
.status-label {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 20px; cursor: pointer;
    font-size: 0.8rem; font-weight: 600; border: 1.5px solid #e2e8f0;
    transition: all 0.15s; color: #64748b; background: #ffffff;
}
.status-radio:checked + .status-label { border-color: transparent; color: white; }
.radio-hadir:checked + .status-label  { background: #16a34a; box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25); }
.radio-sakit:checked + .status-label  { background: #d97706; box-shadow: 0 2px 6px rgba(217, 119, 6, 0.25); }
.radio-izin:checked  + .status-label  { background: #0ea5e9; box-shadow: 0 2px 6px rgba(14, 165, 233, 0.25); }
.radio-alpha:checked + .status-label  { background: #dc2626; box-shadow: 0 2px 6px rgba(220, 38, 38, 0.25); }

.lulus-radio { display: none; }
.lulus-label {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 5px 10px; border-radius: 6px; cursor: pointer;
    font-size: 0.78rem; font-weight: 700; border: 1.5px solid #cbd5e1;
    color: #64748b; background: #ffffff; transition: all 0.15s;
}
.lulus-radio:checked + .lulus-label { border-color: transparent; color: white; }
.radio-opt-lulus:checked + .lulus-label { background: #16a34a; }
.radio-opt-belum:checked + .lulus-label { background: #dc2626; }

.avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: #eff6ff; color: #2563eb;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.9rem; flex-shrink: 0;
}
.btn-submit {
    background: linear-gradient(135deg, #0f766e, #0ea5e9);
    color: white; border: none; padding: 12px 32px;
    border-radius: 10px; font-weight: 800; font-size: 1rem;
    cursor: pointer; display: inline-flex; align-items: center; gap: 10px;
    transition: transform 0.15s, box-shadow 0.15s;
}
.btn-submit:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(15,118,110,0.35); }
.quick-btn {
    background: #f1f5f9; border: 1px solid #e2e8f0;
    color: #475569; padding: 6px 14px; border-radius: 8px;
    font-size: 0.8rem; font-weight: 600; cursor: pointer;
    transition: background 0.15s;
}
.quick-btn:hover { background: #e2e8f0; }

.tahfidz-input-grid {
    display: grid;
    grid-template-columns: 2.2fr 1fr 1.1fr 2fr 1.5fr;
    gap: 12px;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px dashed #e2e8f0;
}
@media (max-width: 950px) {
    .tahfidz-input-grid {
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
}
@media (max-width: 600px) {
    .tahfidz-input-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}
.input-label-sm {
    display: block; font-size: 0.74rem; font-weight: 700; color: #475569;
    margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.4px;
}
.surah-select-box {
    width: 100%;
    padding: 7px 8px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #1e293b;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    background: #ffffff;
    box-sizing: border-box;
}
.ayat-input-box {
    width: 100%;
    padding: 7px 8px;
    font-size: 0.82rem;
    color: #0f172a;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    background: #ffffff;
    box-sizing: border-box;
}
.surah-select-box:focus, .ayat-input-box:focus {
    border-color: #0f766e;
    outline: none;
    box-shadow: 0 0 0 2px rgba(15,118,110,0.15);
}
</style>

<div style="max-width: 1100px; margin: 0 auto; margin-bottom: 40px;">
    <div class="form-header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
            <div>
                <div style="font-size: 0.8rem; opacity: 0.75; margin-bottom: 6px;">
                    <a href="{{ route('tahfidz.attendance.index', ['group' => $group->id, 'year_id' => $yearId]) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Daftar Sesi Mentoring
                    </a>
                </div>
                <h2 style="margin: 0; font-size: 1.45rem; font-weight: 800;">
                    <i class="fas fa-quran" style="opacity: 0.8; margin-right: 8px;"></i>
                    Input Sesi & Capaian Mentoring Tahfidz
                </h2>
                <div style="margin-top: 6px; font-size: 0.85rem; opacity: 0.85;">
                    Kelompok: <strong>{{ $group->name }}</strong> &nbsp;·&nbsp; Pembimbing: <strong>{{ $group->mentor_name }}</strong>
                    @if($group->target_level)
                        &nbsp;·&nbsp; Target: <span style="background: rgba(255,255,255,0.25); padding: 1px 7px; border-radius: 4px; font-weight: 700;">{{ $group->target_level }}</span>
                    @endif
                </div>
            </div>
            <div style="text-align: right; font-size: 0.85rem; opacity: 0.9;">
                <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-weight: 700;">
                    <i class="fas fa-users"></i> {{ $students->count() }} Siswa
                </span>
            </div>
        </div>
    </div>

    <div style="background: white; border-radius: 0 0 16px 16px; border: 1px solid #e2e8f0; border-top: none; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">

        @if($students->isEmpty())
            <div style="text-align: center; padding: 50px; color: #94a3b8;">
                <i class="fas fa-users-slash" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                <p>Belum ada siswa di kelompok ini. Tambahkan siswa terlebih dahulu.</p>
                <a href="{{ route('tahfidz-groups.show', $group->id) }}" style="color: #0f766e;">← Kembali ke kelompok</a>
            </div>
        @else

        <form method="POST" action="{{ route('tahfidz.attendance.store', ['group' => $group->id]) }}">
            @csrf

            {{-- PENGATURAN SESI & FILTER JUZ --}}
            <div style="padding: 20px 24px; border-bottom: 2px solid #f1f5f9; background: #f8fafc; display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.4px;">
                            📅 Tanggal Pelaksanaan Sesi <span style="color: red;">*</span>
                        </label>
                        <input type="date" name="session_date" value="{{ $date }}" required
                            style="padding: 9px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.92rem; color: #0f172a; font-weight: 600; background: white;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.4px;">
                            Tahun Pelajaran <span style="color: red;">*</span>
                        </label>
                        <select name="academic_year_id" required style="padding: 9px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; color: #0f172a; background: white;">
                            @foreach($academicYears as $yr)
                                <option value="{{ $yr->id }}" {{ $yr->id == $yearId ? 'selected' : '' }}>TP {{ $yr->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0f766e; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.4px;">
                            <i class="fas fa-book-open"></i> Juz yang Diampu (Pilihan Surat)
                        </label>
                        <select id="activeJuzSelector" onchange="applyJuzChange(this.value)"
                            style="padding: 9px 12px; border: 1.5px solid #0f766e; border-radius: 8px; font-size: 0.9rem; color: #0f766e; background: #f0fdf4; font-weight: 700; cursor: pointer;">
                            <option value="30" {{ ($defaultJuz ?? 30) == 30 ? 'selected' : '' }}>Juz 30 (An-Naba' s.d An-Nas)</option>
                            <option value="29" {{ ($defaultJuz ?? 30) == 29 ? 'selected' : '' }}>Juz 29 (Al-Mulk s.d Al-Mursalat)</option>
                            <option value="28" {{ ($defaultJuz ?? 30) == 28 ? 'selected' : '' }}>Juz 28 (Al-Mujadilah s.d At-Tahrim)</option>
                            <option value="27" {{ ($defaultJuz ?? 30) == 27 ? 'selected' : '' }}>Juz 27 (Az-Zariyat s.d Al-Hadid)</option>
                            <option value="26" {{ ($defaultJuz ?? 30) == 26 ? 'selected' : '' }}>Juz 26 (Al-Ahqaf s.d Az-Zariyat)</option>
                            @for($j = 25; $j >= 1; $j--)
                                <option value="{{ $j }}" {{ ($defaultJuz ?? 30) == $j ? 'selected' : '' }}>Juz {{ $j }}</option>
                            @endfor
                            <option value="all">Semua Surat Al-Qur'an (114 Surat)</option>
                        </select>
                    </div>
                </div>

                {{-- Quick Buttons --}}
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" onclick="setAllAttendance('hadir')" class="quick-btn" style="border-color: #86efac; color: #16a34a;">
                        <i class="fas fa-check-circle"></i> Semua Hadir
                    </button>
                    <button type="button" onclick="setAllLulus('LULUS')" class="quick-btn" style="border-color: #86efac; color: #16a34a;">
                        <i class="fas fa-award"></i> Semua LULUS
                    </button>
                    <button type="button" onclick="setAllAttendance('alpha')" class="quick-btn" style="border-color: #fca5a5; color: #dc2626;">
                        <i class="fas fa-times-circle"></i> Semua Alpha
                    </button>
                </div>
            </div>

            {{-- DAFTAR SISWA & CAPAIAN --}}
            <div style="padding: 0;">
                @foreach($students as $i => $student)
                    @php 
                        $ex = $existing->get($student->id); 
                        $currStatus = $ex ? $ex->status : 'hadir';
                        $currLulus = $ex && $ex->hafalan_status ? $ex->hafalan_status : 'LULUS';
                    @endphp
                    <div class="student-card-item" id="student_row_{{ $student->id }}">
                        {{-- Baris 1: Identitas & Kehadiran --}}
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div class="avatar">{{ substr($student->name, 0, 1) }}</div>
                                <div>
                                    <div style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                                        {{ $i + 1 }}. {{ $student->name }}
                                    </div>
                                    <div style="font-size: 0.78rem; color: #64748b;">
                                        <span style="background: #f1f5f9; padding: 2px 7px; border-radius: 4px; font-weight: 600; color: #334155;">Kelas {{ $student->class }}</span>
                                        @if($student->nis) · NIS: {{ $student->nis }} @endif
                                        @if($student->parent_phone) · <i class="fab fa-whatsapp" style="color: #25d366;"></i> {{ $student->parent_phone }} @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Status Kehadiran Radio --}}
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                @foreach(['hadir' => ['✅','Hadir'], 'sakit' => ['🤒','Sakit'], 'izin' => ['📄','Izin'], 'alpha' => ['❌','Alpha']] as $val => [$icon, $lbl])
                                    <input type="radio"
                                        id="s{{ $student->id }}_{{ $val }}"
                                        name="attendance[{{ $student->id }}][status]"
                                        value="{{ $val }}"
                                        class="status-radio radio-{{ $val }}"
                                        onchange="toggleHadirSection('{{ $student->id }}', this.value)"
                                        {{ $currStatus === $val ? 'checked' : '' }}>
                                    <label for="s{{ $student->id }}_{{ $val }}" class="status-label">{{ $icon }} {{ $lbl }}</label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Baris 2: Input Capaian Hafalan (Dropdown Surat + Ayat), Jumlah Baris, Status, Tugas Besok, Catatan --}}
                        <div id="hadir_section_{{ $student->id }}" class="tahfidz-input-grid" style="{{ $currStatus !== 'hadir' ? 'opacity: 0.5;' : '' }}">
                            {{-- Capaian Hari Ini --}}
                            <div>
                                <label class="input-label-sm">
                                    📖 Capaian Hari Ini
                                </label>
                                <div style="display: flex; gap: 6px;">
                                    <select class="surah-select-box capaian-surah-select" 
                                            data-student-id="{{ $student->id }}" 
                                            onchange="onCapaianSurahChange({{ $student->id }})" 
                                            style="flex: 2; min-width: 0;">
                                        <option value="">-- Pilih Surat --</option>
                                    </select>
                                    <input type="text" 
                                           class="ayat-input-box capaian-ayat-input" 
                                           data-student-id="{{ $student->id }}" 
                                           oninput="updateCapaianField({{ $student->id }})" 
                                           placeholder="Ayat (mis: 10 / 1-11)" 
                                           style="flex: 1.2; min-width: 0;">
                                </div>
                                <input type="hidden" 
                                       id="capaian_hidden_{{ $student->id }}" 
                                       name="attendance[{{ $student->id }}][capaian]" 
                                       value="{{ $ex?->capaian ?? '' }}">
                                <div id="capaian_preview_{{ $student->id }}" style="font-size: 0.72rem; color: #0f766e; font-weight: 600; margin-top: 3px; min-height: 15px;">
                                    {{ $ex?->capaian ? 'Format: ' . $ex->capaian : '' }}
                                </div>
                            </div>

                            {{-- Jumlah Baris --}}
                            <div>
                                <label class="input-label-sm" title="Jumlah baris mushaf yang dihafal">
                                    📏 Jml Baris
                                </label>
                                <input type="number" 
                                       class="ayat-input-box" 
                                       name="attendance[{{ $student->id }}][jumlah_baris]" 
                                       value="{{ $ex?->jumlah_baris ?? '' }}" 
                                       min="0" 
                                       max="500" 
                                       placeholder="Baris (mis: 15)" 
                                       style="width: 100%; font-weight: 600;">
                                <div style="font-size: 0.7rem; color: #64748b; margin-top: 3px;">
                                    Mis: 15 baris (1 hal)
                                </div>
                            </div>

                            {{-- Status Kelulusan --}}
                            <div>
                                <label class="input-label-sm">
                                    Hasil Capaian
                                </label>
                                <div style="display: flex; gap: 5px;">
                                    <input type="radio" 
                                        id="lulus_{{ $student->id }}_yes" 
                                        name="attendance[{{ $student->id }}][hafalan_status]" 
                                        value="LULUS" 
                                        class="lulus-radio radio-opt-lulus"
                                        onchange="onLulusChange({{ $student->id }}, 'LULUS')"
                                        {{ $currLulus === 'LULUS' ? 'checked' : '' }}>
                                    <label for="lulus_{{ $student->id }}_yes" class="lulus-label" title="✓ Lulus">
                                        ✓ LULUS
                                    </label>

                                    <input type="radio" 
                                        id="lulus_{{ $student->id }}_no" 
                                        name="attendance[{{ $student->id }}][hafalan_status]" 
                                        value="BELUM LULUS" 
                                        class="lulus-radio radio-opt-belum"
                                        onchange="onLulusChange({{ $student->id }}, 'BELUM LULUS')"
                                        {{ $currLulus === 'BELUM LULUS' ? 'checked' : '' }}>
                                    <label for="lulus_{{ $student->id }}_no" class="lulus-label" title="✗ Belum Lulus">
                                        ✗ BELUM
                                    </label>
                                </div>
                            </div>

                            {{-- Tugas Besok --}}
                            <div>
                                <label class="input-label-sm">
                                    📝 Tugas Besok
                                </label>
                                <div style="display: flex; gap: 6px;">
                                    <select class="surah-select-box tugas-surah-select" 
                                            data-student-id="{{ $student->id }}" 
                                            onchange="updateTugasField({{ $student->id }})" 
                                            style="flex: 2; min-width: 0;">
                                        <option value="">-- Pilih Surat --</option>
                                    </select>
                                    <input type="text" 
                                           class="ayat-input-box tugas-ayat-input" 
                                           data-student-id="{{ $student->id }}" 
                                           oninput="updateTugasField({{ $student->id }})" 
                                           placeholder="Ayat (mis: 11-12)" 
                                           style="flex: 1.2; min-width: 0;">
                                </div>
                                <input type="hidden" 
                                       id="tugas_hidden_{{ $student->id }}" 
                                       name="attendance[{{ $student->id }}][tugas_besok]" 
                                       value="{{ $ex?->tugas_besok ?? '' }}">
                                <div id="tugas_preview_{{ $student->id }}" style="font-size: 0.72rem; color: #0284c7; font-weight: 600; margin-top: 3px; min-height: 15px;">
                                    {{ $ex?->tugas_besok ? 'Format: ' . $ex->tugas_besok : '' }}
                                </div>
                            </div>

                            {{-- Catatan Guru --}}
                            <div>
                                <label class="input-label-sm">
                                    💭 Catatan Guru
                                </label>
                                <input type="text" 
                                    id="notes_{{ $student->id }}"
                                    name="attendance[{{ $student->id }}][notes]"
                                    value="{{ $ex?->notes ?? '' }}"
                                    placeholder="Contoh: Barakallah... / Lancarkan lagi.."
                                    class="form-control"
                                    style="padding: 7px 10px; font-size: 0.82rem; border: 1.5px solid #cbd5e1; border-radius: 8px; width: 100%; box-sizing: border-box;">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- FOOTER --}}
            <div style="padding: 22px 24px; border-top: 2px solid #f1f5f9; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div style="font-size: 0.84rem; color: #065f46; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 10px 14px; border-radius: 10px; max-width: 580px; line-height: 1.4;">
                    <strong><i class="fas fa-sync-alt"></i> 1x Simpan, Otomatis Tersinkron Serentak:</strong> 
                    Presensi dan setoran hafalan langsung masuk ke Rekapan Rapor, Ruang Halaqah, dan Kartu Hafalan Siswa sekaligus menyiapkan format pesan WhatsApp siap share.
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('tahfidz-groups.index', ['group_id' => $group->id]) }}" style="background: white; border: 1px solid #cbd5e1; color: #475569; text-decoration: none; padding: 11px 20px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fas fa-arrow-left"></i> Ruang Halaqah
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Simpan Sesi & Siapkan Laporan WA
                    </button>
                </div>
            </div>
        </form>

        @endif
    </div>
</div>

<script>
// Peta Surat per Juz Al-Qur'an
const JUZ_SURAH_MAP = {
    30: ["Surat An-Naba'", "Surat An-Nazi'at", "Surat 'Abasa", "Surat At-Takwir", "Surat Al-Infitar", "Surat Al-Muthaffifin", "Surat Al-Insyiqaq", "Surat Al-Buruj", "Surat At-Tariq", "Surat Al-A'la", "Surat Al-Ghasyiyah", "Surat Al-Fajr", "Surat Al-Balad", "Surat Asy-Syams", "Surat Al-Lail", "Surat Ad-Duha", "Surat Asy-Syarh", "Surat At-Tin", "Surat Al-'Alaq", "Surat Al-Qadr", "Surat Al-Bayyinah", "Surat Az-Zalzalah", "Surat Al-'Adiyat", "Surat Al-Qari'ah", "Surat At-Takatsur", "Surat Al-'Asr", "Surat Al-Humazah", "Surat Al-Fil", "Surat Quraisy", "Surat Al-Ma'un", "Surat Al-Kausar", "Surat Al-Kafirun", "Surat An-Nasr", "Surat Al-Masad", "Surat Al-Ikhlas", "Surat Al-Falaq", "Surat An-Nas"],
    29: ["Surat Al-Mulk", "Surat Al-Qalam", "Surat Al-Haqqah", "Surat Al-Ma'arij", "Surat Nuh", "Surat Al-Jinn", "Surat Al-Muzzammil", "Surat Al-Muddassir", "Surat Al-Qiyamah", "Surat Al-Insan", "Surat Al-Mursalat"],
    28: ["Surat Al-Mujadilah", "Surat Al-Hasyr", "Surat Al-Mumtahanah", "Surat As-Saff", "Surat Al-Jumu'ah", "Surat Al-Munafiqun", "Surat At-Taghabun", "Surat At-Talaq", "Surat At-Tahrim"],
    27: ["Surat Az-Zariyat", "Surat At-Tur", "Surat An-Najm", "Surat Al-Qamar", "Surat Ar-Rahman", "Surat Al-Waqi'ah", "Surat Al-Hadid"],
    26: ["Surat Al-Ahqaf", "Surat Muhammad", "Surat Al-Fath", "Surat Al-Hujurat", "Surat Qaf", "Surat Az-Zariyat"],
    25: ["Surat Fussilat", "Surat Asy-Syura", "Surat Az-Zukhruf", "Surat Ad-Dukhan", "Surat Al-Jasiyah"],
    24: ["Surat Az-Zumar", "Surat Ghafir", "Surat Fussilat"],
    23: ["Surat Yasin", "Surat As-Saffat", "Surat Sad", "Surat Az-Zumar"],
    22: ["Surat Al-Ahzab", "Surat Saba'", "Surat Fatir", "Surat Yasin"],
    21: ["Surat Al-'Ankabut", "Surat Ar-Rum", "Surat Luqman", "Surat As-Sajdah", "Surat Al-Ahzab"],
    20: ["Surat An-Naml", "Surat Al-Qasas", "Surat Al-'Ankabut"],
    19: ["Surat Al-Furqan", "Surat Asy-Syu'ara'", "Surat An-Naml"],
    18: ["Surat Al-Mu'minun", "Surat An-Nur", "Surat Al-Furqan"],
    17: ["Surat Al-Anbiya'", "Surat Al-Hajj"],
    16: ["Surat Al-Kahf", "Surat Maryam", "Surat Thaha"],
    15: ["Surat Al-Isra'", "Surat Al-Kahf"],
    14: ["Surat Al-Hijr", "Surat An-Nahl"],
    13: ["Surat Yusuf", "Surat Ar-Ra'd", "Surat Ibrahim"],
    12: ["Surat Hud", "Surat Yusuf"],
    11: ["Surat At-Taubah", "Surat Yunus", "Surat Hud"],
    10: ["Surat Al-Anfal", "Surat At-Taubah"],
    9: ["Surat Al-A'raf", "Surat Al-Anfal"],
    8: ["Surat Al-An'am", "Surat Al-A'raf"],
    7: ["Surat Al-Ma'idah", "Surat Al-An'am"],
    6: ["Surat An-Nisa'", "Surat Al-Ma'idah"],
    5: ["Surat An-Nisa'"],
    4: ["Surat Ali 'Imran", "Surat An-Nisa'"],
    3: ["Surat Al-Baqarah", "Surat Ali 'Imran"],
    2: ["Surat Al-Baqarah"],
    1: ["Surat Al-Fatihah", "Surat Al-Baqarah"]
};

// Daftar Lengkap 114 Surat Al-Qur'an
const ALL_SURAHS = [
    "Surat Al-Fatihah", "Surat Al-Baqarah", "Surat Ali 'Imran", "Surat An-Nisa'", "Surat Al-Ma'idah", 
    "Surat Al-An'am", "Surat Al-A'raf", "Surat Al-Anfal", "Surat At-Taubah", "Surat Yunus", 
    "Surat Hud", "Surat Yusuf", "Surat Ar-Ra'd", "Surat Ibrahim", "Surat Al-Hijr", 
    "Surat An-Nahl", "Surat Al-Isra'", "Surat Al-Kahf", "Surat Maryam", "Surat Thaha", 
    "Surat Al-Anbiya'", "Surat Al-Hajj", "Surat Al-Mu'minun", "Surat An-Nur", "Surat Al-Furqan", 
    "Surat Asy-Syu'ara'", "Surat An-Naml", "Surat Al-Qasas", "Surat Al-'Ankabut", "Surat Ar-Rum", 
    "Surat Luqman", "Surat As-Sajdah", "Surat Al-Ahzab", "Surat Saba'", "Surat Fatir", 
    "Surat Yasin", "Surat As-Saffat", "Surat Sad", "Surat Az-Zumar", "Surat Ghafir", 
    "Surat Fussilat", "Surat Asy-Syura", "Surat Az-Zukhruf", "Surat Ad-Dukhan", "Surat Al-Jasiyah", 
    "Surat Al-Ahqaf", "Surat Muhammad", "Surat Al-Fath", "Surat Al-Hujurat", "Surat Qaf", 
    "Surat Az-Zariyat", "Surat At-Tur", "Surat An-Najm", "Surat Al-Qamar", "Surat Ar-Rahman", 
    "Surat Al-Waqi'ah", "Surat Al-Hadid", "Surat Al-Mujadilah", "Surat Al-Hasyr", "Surat Al-Mumtahanah", 
    "Surat As-Saff", "Surat Al-Jumu'ah", "Surat Al-Munafiqun", "Surat At-Taghabun", "Surat At-Talaq", 
    "Surat At-Tahrim", "Surat Al-Mulk", "Surat Al-Qalam", "Surat Al-Haqqah", "Surat Al-Ma'arij", 
    "Surat Nuh", "Surat Al-Jinn", "Surat Al-Muzzammil", "Surat Al-Muddassir", "Surat Al-Qiyamah", 
    "Surat Al-Insan", "Surat Al-Mursalat", "Surat An-Naba'", "Surat An-Nazi'at", "Surat 'Abasa", 
    "Surat At-Takwir", "Surat Al-Infitar", "Surat Al-Muthaffifin", "Surat Al-Insyiqaq", "Surat Al-Buruj", 
    "Surat At-Tariq", "Surat Al-A'la", "Surat Al-Ghasyiyah", "Surat Al-Fajr", "Surat Al-Balad", 
    "Surat Asy-Syams", "Surat Al-Lail", "Surat Ad-Duha", "Surat Asy-Syarh", "Surat At-Tin", 
    "Surat Al-'Alaq", "Surat Al-Qadr", "Surat Al-Bayyinah", "Surat Az-Zalzalah", "Surat Al-'Adiyat", 
    "Surat Al-Qari'ah", "Surat At-Takatsur", "Surat Al-'Asr", "Surat Al-Humazah", "Surat Al-Fil", 
    "Surat Quraisy", "Surat Al-Ma'un", "Surat Al-Kausar", "Surat Al-Kafirun", "Surat An-Nasr", 
    "Surat Al-Masad", "Surat Al-Ikhlas", "Surat Al-Falaq", "Surat An-Nas"
];

// Helper parse string teks lama menjadi surah & ayat
function parseSurahAndAyat(text) {
    if (!text) return { surah: '', ayat: '' };
    text = text.trim();
    
    // Cocokkan terhadap ALL_SURAHS
    for (let s of ALL_SURAHS) {
        let cleanSurah = s.replace(/^Surat\s+/i, '');
        // Buat regex fleksibel terhadap spasi atau tanda hubung
        let pattern = '(?:Surat\\s+)?' + cleanSurah.replace(/[-\']/g, '[- \']?');
        let reg = new RegExp('^' + pattern, 'i');
        let m = text.match(reg);
        if (m) {
            let surahPart = s;
            let remainder = text.substring(m[0].length).trim();
            remainder = remainder.replace(/^ayat\s*/i, '').trim();
            return { surah: surahPart, ayat: remainder };
        }
    }
    
    // Fallback: jika format '... ayat ...'
    let m = text.match(/^(.*?)(?:\s+ayat\s+(.*))$/i);
    if (m) {
        return { surah: m[1].trim(), ayat: m[2].trim() };
    }
    return { surah: text, ayat: '' };
}

// Mengisi dropdown pilihan surat sesuai Juz yang aktif
function populateSurahDropdowns(juz) {
    let currentJuzSurahs = [];
    if (juz === 'all') {
        currentJuzSurahs = ALL_SURAHS;
    } else {
        currentJuzSurahs = JUZ_SURAH_MAP[juz] || [];
    }

    const selectElements = document.querySelectorAll('.capaian-surah-select, .tugas-surah-select');
    
    selectElements.forEach(sel => {
        const curVal = sel.value;
        sel.innerHTML = '<option value="">-- Pilih Surat --</option>';
        
        if (juz !== 'all' && currentJuzSurahs.length > 0) {
            const grp = document.createElement('optgroup');
            grp.label = `Juz ${juz} (Target Sesi Ini)`;
            currentJuzSurahs.forEach(surah => {
                const opt = document.createElement('option');
                opt.value = surah;
                opt.textContent = surah;
                grp.appendChild(opt);
            });
            sel.appendChild(grp);

            // Tambahkan grup surat lainnya jika ada santri yang hafalannya di luar juz
            const otherGrp = document.createElement('optgroup');
            otherGrp.label = '── Surat Lainnya (1-114) ──';
            ALL_SURAHS.forEach(surah => {
                if (!currentJuzSurahs.includes(surah)) {
                    const opt = document.createElement('option');
                    opt.value = surah;
                    opt.textContent = surah;
                    otherGrp.appendChild(opt);
                }
            });
            sel.appendChild(otherGrp);
        } else {
            ALL_SURAHS.forEach(surah => {
                const opt = document.createElement('option');
                opt.value = surah;
                opt.textContent = surah;
                sel.appendChild(opt);
            });
        }
        
        // Pulihkan nilai terpilih sebelumnya jika ada
        if (curVal) {
            sel.value = curVal;
        }
    });
}

function applyJuzChange(juz) {
    populateSurahDropdowns(juz);
}

function updateCapaianField(studentId) {
    const surahSelect = document.querySelector(`.capaian-surah-select[data-student-id="${studentId}"]`);
    const ayatInput = document.querySelector(`.capaian-ayat-input[data-student-id="${studentId}"]`);
    const hidden = document.getElementById(`capaian_hidden_${studentId}`);
    const preview = document.getElementById(`capaian_preview_${studentId}`);
    
    let surah = surahSelect ? surahSelect.value.trim() : '';
    let ayat = ayatInput ? ayatInput.value.trim() : '';
    
    let result = '';
    if (surah) {
        if (ayat) {
            let ayatStr = /^ayat\b/i.test(ayat) ? ayat : `ayat ${ayat}`;
            result = `${surah} ${ayatStr}`;
        } else {
            result = surah;
        }
    } else if (ayat) {
        result = `ayat ${ayat}`;
    }
    
    if (hidden) hidden.value = result;
    if (preview) preview.textContent = result ? `Format: ${result}` : '';
}

function onCapaianSurahChange(studentId) {
    updateCapaianField(studentId);
    
    // Otomatis isi Tugas Besok dengan surat yang sama jika belum dipilih
    const surahSelect = document.querySelector(`.capaian-surah-select[data-student-id="${studentId}"]`);
    const tugasSelect = document.querySelector(`.tugas-surah-select[data-student-id="${studentId}"]`);
    if (surahSelect && tugasSelect && !tugasSelect.value && surahSelect.value) {
        tugasSelect.value = surahSelect.value;
        updateTugasField(studentId);
    }
}

function updateTugasField(studentId) {
    const surahSelect = document.querySelector(`.tugas-surah-select[data-student-id="${studentId}"]`);
    const ayatInput = document.querySelector(`.tugas-ayat-input[data-student-id="${studentId}"]`);
    const hidden = document.getElementById(`tugas_hidden_${studentId}`);
    const preview = document.getElementById(`tugas_preview_${studentId}`);
    
    let surah = surahSelect ? surahSelect.value.trim() : '';
    let ayat = ayatInput ? ayatInput.value.trim() : '';
    
    let result = '';
    if (surah) {
        if (ayat) {
            let ayatStr = /^ayat\b/i.test(ayat) ? ayat : `ayat ${ayat}`;
            result = `${surah} ${ayatStr}`;
        } else {
            result = surah;
        }
    } else if (ayat) {
        result = `ayat ${ayat}`;
    }
    
    if (hidden) hidden.value = result;
    if (preview) preview.textContent = result ? `Format: ${result}` : '';
}

function onLulusChange(studentId, val) {
    const noteInput = document.getElementById(`notes_${studentId}`);
    if (noteInput) {
        if (val === 'LULUS' && (!noteInput.value || noteInput.value.includes('Lancarkan'))) {
            noteInput.value = 'Barakallah..';
        } else if (val === 'BELUM LULUS' && (!noteInput.value || noteInput.value.includes('Barakallah'))) {
            noteInput.value = 'Lancarkan lagi ya sholih/sholihah..';
        }
    }
}

function setAllAttendance(status) {
    document.querySelectorAll(`input.status-radio[value="${status}"]`).forEach(r => {
        r.checked = true;
        const studentId = r.id.split('_')[0].replace('s', '');
        toggleHadirSection(studentId, status);
    });
}

function setAllLulus(status) {
    document.querySelectorAll(`input.lulus-radio[value="${status}"]`).forEach(r => {
        r.checked = true;
        const studentId = r.id.split('_')[1];
        if (studentId) onLulusChange(studentId, status);
    });
}

function toggleHadirSection(studentId, status) {
    const section = document.getElementById(`hadir_section_${studentId}`);
    if (section) {
        section.style.opacity = (status === 'hadir') ? '1' : '0.5';
    }
}

// Inisialisasi awal saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    const defaultJuz = document.getElementById('activeJuzSelector') ? document.getElementById('activeJuzSelector').value : '30';
    populateSurahDropdowns(defaultJuz);
    
    // Pulihkan nilai awal dari data yang tersimpan
    document.querySelectorAll('input[id^="capaian_hidden_"]').forEach(hidden => {
        const studentId = hidden.id.replace('capaian_hidden_', '');
        if (hidden.value) {
            const parsed = parseSurahAndAyat(hidden.value);
            const surahSelect = document.querySelector(`.capaian-surah-select[data-student-id="${studentId}"]`);
            const ayatInput = document.querySelector(`.capaian-ayat-input[data-student-id="${studentId}"]`);
            if (surahSelect && parsed.surah) surahSelect.value = parsed.surah;
            if (ayatInput && parsed.ayat) ayatInput.value = parsed.ayat;
            updateCapaianField(studentId);
        }
    });

    document.querySelectorAll('input[id^="tugas_hidden_"]').forEach(hidden => {
        const studentId = hidden.id.replace('tugas_hidden_', '');
        if (hidden.value) {
            const parsed = parseSurahAndAyat(hidden.value);
            const surahSelect = document.querySelector(`.tugas-surah-select[data-student-id="${studentId}"]`);
            const ayatInput = document.querySelector(`.tugas-ayat-input[data-student-id="${studentId}"]`);
            if (surahSelect && parsed.surah) surahSelect.value = parsed.surah;
            if (ayatInput && parsed.ayat) ayatInput.value = parsed.ayat;
            updateTugasField(studentId);
        }
    });
});
</script>

@endsection
