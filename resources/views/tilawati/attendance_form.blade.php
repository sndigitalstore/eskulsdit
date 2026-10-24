@extends('layouts.app')

@section('title', 'Input Sesi Pertemuan Tilawati - ' . $group->name)
@section('page-title', 'Input Sesi Pertemuan Tilawati')

@push('styles')
<style>
    .form-container {
        max-width: 1080px;
        margin: 0 auto;
        padding-bottom: 60px;
    }
    .tilawati-form-header {
        background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
        border-radius: 20px;
        padding: 22px 28px;
        color: white;
        margin-bottom: 22px;
        box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    .material-card {
        background: white;
        border-radius: 18px;
        border: 1.5px solid #e2e8f0;
        padding: 22px;
        margin-bottom: 22px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .student-row-card {
        background: white;
        border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        padding: 16px 20px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        display: grid;
        grid-template-columns: 2fr 1.5fr 2fr 2fr;
        align-items: center;
        gap: 16px;
        transition: all 0.15s ease;
    }
    .student-row-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .badge-status-pill {
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 800;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .badge-lanjut {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }
    .badge-ulang {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }
    .quick-preset-btn {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-weight: 700;
        font-size: 0.74rem;
        padding: 4px 8px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .quick-preset-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    @media (max-width: 900px) {
        .student-row-card {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">

    <!-- Top Navigation -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
        <a href="{{ route('tilawati.attendance.index', $group->id) }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat Pertemuan
        </a>
        <span style="font-size: 0.82rem; font-weight: 800; color: #047857; background: #ecfdf5; padding: 4px 14px; border-radius: 20px;">
            <i class="fas fa-book-reader"></i> Evaluasi Pembelajaran Tilawati
        </span>
    </div>

    <!-- Header Banner -->
    <div class="tilawati-form-header">
        <div>
            <span style="background: rgba(255,255,255,0.2); font-size: 0.74rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; text-transform: uppercase;">
                {{ $existingSession ? 'Ubah Sesi Pertemuan' : 'Input Sesi Pertemuan Baru' }}
            </span>
            <h2 style="font-size: 1.55rem; font-weight: 900; margin: 6px 0 2px 0;">
                {{ $group->name }}
            </h2>
            <div style="font-size: 0.86rem; opacity: 0.92; display: flex; gap: 14px; flex-wrap: wrap;">
                <span><i class="fas fa-chalkboard-teacher"></i> Pembimbing: <strong>{{ $group->mentor_name }}</strong></span>
                <span><i class="fas fa-users"></i> {{ $students->count() }} Santri</span>
                <span><i class="fas fa-layer-group"></i> {{ $jilid }}</span>
            </div>
        </div>

        <!-- Quick Set Button for Teacher Efficiency -->
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <button type="button" onclick="setAllPresentAndScore(80)" 
                    style="background: #ffffff; color: #065f46; border: none; padding: 9px 16px; border-radius: 12px; font-weight: 800; font-size: 0.84rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <i class="fas fa-check-double" style="color: #059669;"></i> Set Semua Hadir & Nilai 80 (Lanjut)
            </button>
        </div>
    </div>

    <form action="{{ route('tilawati.attendance.store', $group->id) }}" method="POST">
        @csrf
        <input type="hidden" name="academic_year_id" value="{{ $yearId }}">

        <!-- Card Materi Pertemuan (Peraga 4 Halaman + Buku 1 Halaman) -->
        <div class="material-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-calendar-alt" style="color: #059669;"></i> Tanggal & Materi Pembelajaran Hari Ini
                </h3>
                <span style="font-size: 0.78rem; color: #64748b; background: #f8fafc; padding: 4px 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    Aturan: 4 Hal Peraga & 1 Hal Buku
                </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px;">
                <!-- 1. Tanggal Pertemuan -->
                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                        Tanggal Pertemuan <span style="color: #ef4444;">*</span>:
                    </label>
                    <input type="date" name="session_date" id="session_date" required value="{{ $date }}"
                           onchange="onDateChanged(this.value)"
                           style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; font-weight: 700; color: #0f172a; background: white; box-sizing: border-box;">
                </div>

                <!-- 2. Jilid -->
                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                        Tingkat / Jilid <span style="color: #ef4444;">*</span>:
                    </label>
                    <select name="jilid" required style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; font-weight: 700; color: #0f172a; background: white; box-sizing: border-box;">
                        @foreach(['Jilid 1', 'Jilid 2', 'Jilid 3', 'Jilid 4', 'Jilid 5', 'Jilid 6', 'Al-Qur\'an', 'Pasca Tilawati'] as $j)
                            <option value="{{ $j }}" {{ $jilid === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Alat Peraga (4 Halaman) -->
                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                        Alat Peraga (4 Halaman) <span style="color: #ef4444;">*</span>:
                    </label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="number" min="1" max="200" name="peraga_page_start" id="peraga_start" required 
                               value="{{ $peragaStart }}" oninput="updatePeragaEnd()"
                               placeholder="Hal" style="width: 75px; padding: 10px 8px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.95rem; font-weight: 800; text-align: center;">
                        <span style="font-weight: 800; color: #64748b;">s/d</span>
                        <input type="number" min="1" max="200" name="peraga_page_end" id="peraga_end" required 
                               value="{{ $peragaEnd }}"
                               placeholder="Hal" style="width: 75px; padding: 10px 8px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.95rem; font-weight: 800; text-align: center;">
                        <span style="font-size: 0.74rem; color: #047857; font-weight: 700;">(4 Halaman)</span>
                    </div>
                </div>

                <!-- 4. Buku Tilawati (1 Halaman) -->
                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #0f172a; margin-bottom: 6px;">
                        Buku Tilawati (1 Halaman) <span style="color: #ef4444;">*</span>:
                    </label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="font-weight: 700; color: #64748b; font-size: 0.85rem;">Halaman:</span>
                        <input type="number" min="1" max="100" name="buku_page" id="buku_page" required 
                               value="{{ $bukuPage }}"
                               style="width: 90px; padding: 10px 10px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 1rem; font-weight: 900; color: #0f172a; text-align: center;">
                        <span style="font-size: 0.74rem; color: #2563eb; font-weight: 700;">(Individual)</span>
                    </div>
                </div>
            </div>

            <!-- Catatan Sesi Umum -->
            <div style="margin-top: 14px;">
                <input type="text" name="session_notes" placeholder="Catatan pertemuan hari ini (misal: penguatan makhraj kaf & ta, tempo lagu tilawati)..."
                       value="{{ $existingSession->notes ?? '' }}"
                       style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 0.84rem; box-sizing: border-box;">
            </div>
        </div>

        <!-- Daftar Santri & Rubrik Penilaian Harian -->
        <div style="margin-bottom: 14px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">
                <i class="fas fa-user-graduate" style="color: #059669;"></i> Presensi & Evaluasi Capaian Santri
            </h3>
            <div style="font-size: 0.8rem; color: #64748b;">
                Standar: Nilai <strong>≥ 70% Lanjut</strong> | Nilai <strong>&lt; 70% Mengulang</strong>
            </div>
        </div>

        @foreach($students as $st)
            @php
                $att = $existingAttendances->get($st->id);
                $curStatus = $att ? $att->status : 'hadir';
                $curScore = $att && $att->score !== null ? $att->score : 80;
                $curNotes = $att ? $att->notes : '';
                $isPassed = $curScore >= 70;
            @endphp
            <div class="student-row-card" id="student_card_{{ $st->id }}">
                
                <!-- Kolom 1: Profil Santri -->
                <div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #ecfdf5; color: #047857; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">
                            {{ substr($st->name, 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight: 800; font-size: 0.92rem; color: #0f172a;">{{ $st->name }}</div>
                            <div style="font-size: 0.78rem; color: #64748b;">
                                Kelas: <strong>{{ $st->class ?? '-' }}</strong> | NIS: {{ $st->nis ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom 2: Presensi Kehadiran -->
                <div>
                    <label style="display: block; font-size: 0.74rem; font-weight: 700; color: #64748b; margin-bottom: 4px;">Status Kehadiran:</label>
                    <div style="display: flex; gap: 8px;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #166534; cursor: pointer; display: flex; align-items: center; gap: 3px;">
                            <input type="radio" name="attendance[{{ $st->id }}][status]" value="hadir" {{ $curStatus === 'hadir' ? 'checked' : '' }} onchange="onStatusChanged({{ $st->id }}, 'hadir')" style="accent-color: #059669;"> H
                        </label>
                        <label style="font-size: 0.8rem; font-weight: 700; color: #b45309; cursor: pointer; display: flex; align-items: center; gap: 3px;">
                            <input type="radio" name="attendance[{{ $st->id }}][status]" value="sakit" {{ $curStatus === 'sakit' ? 'checked' : '' }} onchange="onStatusChanged({{ $st->id }}, 'sakit')" style="accent-color: #f59e0b;"> S
                        </label>
                        <label style="font-size: 0.8rem; font-weight: 700; color: #2563eb; cursor: pointer; display: flex; align-items: center; gap: 3px;">
                            <input type="radio" name="attendance[{{ $st->id }}][status]" value="izin" {{ $curStatus === 'izin' ? 'checked' : '' }} onchange="onStatusChanged({{ $st->id }}, 'izin')" style="accent-color: #3b82f6;"> I
                        </label>
                        <label style="font-size: 0.8rem; font-weight: 700; color: #dc2626; cursor: pointer; display: flex; align-items: center; gap: 3px;">
                            <input type="radio" name="attendance[{{ $st->id }}][status]" value="alpha" {{ $curStatus === 'alpha' ? 'checked' : '' }} onchange="onStatusChanged({{ $st->id }}, 'alpha')" style="accent-color: #dc2626;"> A
                        </label>
                    </div>
                </div>

                <!-- Kolom 3: Penilaian & Preset Nilai Cepat -->
                <div id="score_section_{{ $st->id }}" style="{{ $curStatus !== 'hadir' ? 'opacity: 0.35; pointer-events: none;' : '' }}">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <label style="font-size: 0.74rem; font-weight: 700; color: #64748b;">Nilai Ketercapaian:</label>
                        <div style="display: flex; gap: 4px;">
                            <button type="button" class="quick-preset-btn" onclick="setStudentScore({{ $st->id }}, 85)" title="Lanjut Cepat">85</button>
                            <button type="button" class="quick-preset-btn" onclick="setStudentScore({{ $st->id }}, 95)" title="Mumtaz">95</button>
                            <button type="button" class="quick-preset-btn" onclick="setStudentScore({{ $st->id }}, 65)" title="Mengulang" style="color: #b91c1c;">65</button>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="number" step="1" min="0" max="100" 
                               name="attendance[{{ $st->id }}][score]" 
                               id="score_input_{{ $st->id }}" 
                               value="{{ $curScore }}" 
                               oninput="evaluateStudentScore({{ $st->id }})"
                               style="width: 75px; padding: 8px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-weight: 900; font-size: 1.05rem; text-align: center; color: #0f172a;">
                        
                        <!-- Live Badge Lanjut vs Mengulang -->
                        <span id="badge_{{ $st->id }}" class="badge-status-pill {{ $isPassed ? 'badge-lanjut' : 'badge-ulang' }}">
                            {{ $isPassed ? '✓ Lanjut' : '↻ Ulang' }}
                        </span>
                    </div>
                </div>

                <!-- Kolom 4: Catatan Evaluasi Tajwid/Makhraj -->
                <div>
                    <label style="display: block; font-size: 0.74rem; font-weight: 700; color: #64748b; margin-bottom: 4px;">Catatan Evaluasi / Fokus Perbaikan:</label>
                    <input type="text" name="attendance[{{ $st->id }}][notes]" 
                           value="{{ $curNotes }}"
                           placeholder="Contoh: Lancar, perhatikan mad thobi'i..."
                           style="width: 100%; padding: 8px 10px; border-radius: 8px; border: 1.5px solid #e2e8f0; font-size: 0.82rem; box-sizing: border-box;">
                </div>

            </div>
        @endforeach

        <!-- Action Footer -->
        <div style="margin-top: 25px; padding: 20px; background: white; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <a href="{{ route('tilawati.attendance.index', $group->id) }}" style="padding: 11px 20px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; text-decoration: none;">
                Batal
            </a>
            <button type="submit" style="padding: 11px 32px; border-radius: 10px; border: none; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; font-weight: 800; font-size: 0.95rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);">
                <i class="fas fa-save"></i> Simpan Pertemuan & Siapkan Rekapan WA
            </button>
        </div>

    </form>
</div>

<script>
function updatePeragaEnd() {
    const startVal = parseInt(document.getElementById('peraga_start').value) || 1;
    // Otomatis 4 halaman alat peraga
    document.getElementById('peraga_end').value = startVal + 3;
}

function evaluateStudentScore(studentId) {
    const scoreInput = document.getElementById('score_input_' + studentId);
    const badge = document.getElementById('badge_' + studentId);
    const scoreVal = parseFloat(scoreInput.value) || 0;

    if (scoreVal >= 70) {
        badge.className = 'badge-status-pill badge-lanjut';
        badge.innerHTML = '✓ Lanjut';
    } else {
        badge.className = 'badge-status-pill badge-ulang';
        badge.innerHTML = '↻ Ulang';
    }
}

function setStudentScore(studentId, scoreVal) {
    const scoreInput = document.getElementById('score_input_' + studentId);
    scoreInput.value = scoreVal;
    evaluateStudentScore(studentId);
}

function onStatusChanged(studentId, status) {
    const scoreSec = document.getElementById('score_section_' + studentId);
    if (status !== 'hadir') {
        scoreSec.style.opacity = '0.35';
        scoreSec.style.pointerEvents = 'none';
    } else {
        scoreSec.style.opacity = '1';
        scoreSec.style.pointerEvents = 'auto';
    }
}

function setAllPresentAndScore(scoreVal) {
    const inputs = document.querySelectorAll('input[type="radio"][value="hadir"]');
    inputs.forEach(r => {
        r.checked = true;
    });

    const scoreInputs = document.querySelectorAll('input[id^="score_input_"]');
    scoreInputs.forEach(inp => {
        inp.value = scoreVal;
        const sId = inp.id.replace('score_input_', '');
        evaluateStudentScore(sId);
        onStatusChanged(sId, 'hadir');
    });
}

function onDateChanged(newDate) {
    // Optional: bisa reload ke create dengan parameter date jika ingin cek existing sesi
    window.location.href = "{{ route('tilawati.attendance.create', $group->id) }}?date=" + newDate;
}
</script>
@endsection
