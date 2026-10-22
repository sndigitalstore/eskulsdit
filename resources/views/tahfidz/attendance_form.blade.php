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
    padding: 4px 10px; border-radius: 6px; cursor: pointer;
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
    grid-template-columns: 2fr 1.2fr 2fr 2fr;
    gap: 12px;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px dashed #e2e8f0;
}
@media (max-width: 900px) {
    .tahfidz-input-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}
.input-label-sm {
    display: block; font-size: 0.74rem; font-weight: 700; color: #475569;
    margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.4px;
}
</style>

<div style="max-width: 1050px; margin: 0 auto; margin-bottom: 40px;">
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

            {{-- PENGATURAN SESI --}}
            <div style="padding: 20px 24px; border-bottom: 2px solid #f1f5f9; background: #f8fafc; display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                <div style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.4px;">
                            📅 Tanggal Pelaksanaan Sesi <span style="color: red;">*</span>
                        </label>
                        <input type="date" name="session_date" value="{{ $date }}" required
                            style="padding: 9px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; color: #0f172a; font-weight: 600; background: white;">
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
                    <div class="student-card-item">
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

                        {{-- Baris 2: Input Capaian Hafalan, Status Kelulusan, Tugas Besok, Catatan --}}
                        <div id="hadir_section_{{ $student->id }}" class="tahfidz-input-grid" style="{{ $currStatus !== 'hadir' ? 'opacity: 0.5;' : '' }}">
                            {{-- Capaian Hari Ini --}}
                            <div>
                                <label class="input-label-sm">
                                    📖 Capaian Hari Ini
                                </label>
                                <input type="text" 
                                    name="attendance[{{ $student->id }}][capaian]"
                                    value="{{ $ex?->capaian ?? '' }}"
                                    placeholder="Contoh: Surat Al Hasyr ayat 10"
                                    class="form-control"
                                    style="padding: 8px 10px; font-size: 0.85rem; border: 1.5px solid #cbd5e1; border-radius: 8px; width: 100%; box-sizing: border-box;">
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
                                        {{ $currLulus === 'LULUS' ? 'checked' : '' }}>
                                    <label for="lulus_{{ $student->id }}_yes" class="lulus-label" title="✓ Lulus">
                                        ✓ LULUS
                                    </label>

                                    <input type="radio" 
                                        id="lulus_{{ $student->id }}_no" 
                                        name="attendance[{{ $student->id }}][hafalan_status]" 
                                        value="BELUM LULUS" 
                                        class="lulus-radio radio-opt-belum"
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
                                <input type="text" 
                                    name="attendance[{{ $student->id }}][tugas_besok]"
                                    value="{{ $ex?->tugas_besok ?? '' }}"
                                    placeholder="Contoh: Surat Al Hasyr ayat 11-12"
                                    class="form-control"
                                    style="padding: 8px 10px; font-size: 0.85rem; border: 1.5px solid #cbd5e1; border-radius: 8px; width: 100%; box-sizing: border-box;">
                            </div>

                            {{-- Catatan Guru --}}
                            <div>
                                <label class="input-label-sm">
                                    💭 Catatan Guru
                                </label>
                                <input type="text" 
                                    name="attendance[{{ $student->id }}][notes]"
                                    value="{{ $ex?->notes ?? '' }}"
                                    placeholder="Contoh: Barakallah... / Lancarkan lagi.."
                                    class="form-control"
                                    style="padding: 8px 10px; font-size: 0.85rem; border: 1.5px solid #cbd5e1; border-radius: 8px; width: 100%; box-sizing: border-box;">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- FOOTER --}}
            <div style="padding: 22px 24px; border-top: 2px solid #f1f5f9; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div style="font-size: 0.84rem; color: #64748b;">
                    💡 <em>Setelah menekan Simpan, format laporan WhatsApp siap disalin atau dibagikan ke orang tua secara instan.</em>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('tahfidz.attendance.index', ['group' => $group->id]) }}" style="background: white; border: 1px solid #cbd5e1; color: #475569; text-decoration: none; padding: 11px 22px; border-radius: 10px; font-weight: 700; font-size: 0.9rem;">
                        Batal
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
    });
}

function toggleHadirSection(studentId, status) {
    const section = document.getElementById(`hadir_section_${studentId}`);
    if (section) {
        section.style.opacity = (status === 'hadir') ? '1' : '0.5';
    }
}
</script>

@endsection
