@extends('layouts.app')

@section('title', 'Pendaftaran Munaqosah Qur\'an')
@section('page-title', 'Pendaftaran Munaqosah Qur\'an')

@section('content')
<div style="max-width: 860px; margin: 0 auto; padding-bottom: 60px;">

    <!-- Top Navigation & Share Banner -->
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 20px;">
        <a href="{{ route('munaqosah.index') }}" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #475569; font-weight: 700; font-size: 0.88rem; background: white; padding: 9px 16px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.03); transition: all 0.2s;" onmouseover="this.style.borderColor='#cbd5e1'" onmouseout="this.style.borderColor='#e2e8f0'">
            <i class="fas fa-arrow-left"></i> Rekapan Munaqosah
        </a>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <button type="button" onclick="copyPublicLink()" style="background: #25d366; color: white; border: none; padding: 8px 16px; border-radius: 12px; font-weight: 700; font-size: 0.84rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);">
                <i class="fab fa-whatsapp"></i> Salin Link Form Online (WA)
            </button>
            <span style="font-size: 0.82rem; font-weight: 700; color: #047857; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 6px 14px; border-radius: 20px;">
                Tahun Ajaran {{ $activeYear->name ?? '-' }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border-left: 5px solid #10b981; padding: 14px 18px; border-radius: 12px; margin-bottom: 22px; color: #065f46; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px; font-weight: 600;">
                <i class="fas fa-check-circle" style="font-size: 1.25rem; color: #10b981;"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #065f46; font-size: 1.1rem; cursor: pointer;">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fef2f2; border-left: 5px solid #ef4444; padding: 14px 18px; border-radius: 12px; margin-bottom: 22px; color: #991b1b;">
            <div style="font-weight: 700; margin-bottom: 4px;"><i class="fas fa-exclamation-triangle"></i> Mohon lengkapi data berikut:</div>
            <ul style="margin: 0; padding-left: 20px; font-size: 0.85rem;">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Card Form -->
    <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 10px 35px rgba(0,0,0,0.04); overflow: hidden;">
        
        <!-- Form Header -->
        <div style="background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0f766e 100%); color: white; padding: 28px 32px; position: relative; overflow: hidden;">
            <div style="position: relative; z-index: 2;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <div style="width: 46px; height: 46px; border-radius: 14px; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
                        <i class="fas fa-award"></i>
                    </div>
                    <div>
                        <h2 style="font-size: 1.55rem; font-weight: 800; margin: 0; letter-spacing: -0.5px;">Formulir Pendaftaran Munaqosah</h2>
                        <p style="margin: 2px 0 0 0; font-size: 0.88rem; opacity: 0.92;">
                            Pengajuan evaluasi kelulusan santri tuntas Juz (Tahfidz) atau Jilid (Tilawati) oleh Guru / Pembimbing.
                        </p>
                    </div>
                </div>
            </div>
            <i class="fas fa-quran" style="position: absolute; right: -15px; bottom: -25px; font-size: 9rem; color: rgba(255,255,255,0.08); z-index: 1;"></i>
        </div>

        <form method="POST" action="{{ route('munaqosah.register.store') }}" style="padding: 32px;">
            @csrf

            <!-- 1. Tipe Pembinaan -->
            <div style="margin-bottom: 26px;">
                <label style="display: block; font-weight: 800; font-size: 0.92rem; color: #0f172a; margin-bottom: 10px;">
                    1. Bidang Pembinaan Munaqosah <span style="color: #ef4444;">*</span>
                </label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <label id="label_tahfidz" style="border: 2px solid {{ $selectedType === 'tahfidz' ? '#059669' : '#e2e8f0' }}; background: {{ $selectedType === 'tahfidz' ? '#f0fdf4' : '#ffffff' }}; border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 14px; cursor: pointer; transition: all 0.2s;">
                        <input type="radio" name="type" value="tahfidz" {{ $selectedType === 'tahfidz' ? 'checked' : '' }} onchange="switchMunaqosahType('tahfidz')" style="width: 20px; height: 20px; accent-color: #059669;">
                        <div>
                            <strong style="color: #065f46; font-size: 1rem; display: block;">
                                <i class="fas fa-star-and-crescent" style="color: #10b981; margin-right: 4px;"></i> Tahfidz Qur'an
                            </strong>
                            <span style="font-size: 0.8rem; color: #047857;">Munaqosah Tuntas Hafalan Juz (Juz 30, 29, 28, dst.)</span>
                        </div>
                    </label>

                    <label id="label_tilawati" style="border: 2px solid {{ $selectedType === 'tilawati' ? '#d97706' : '#e2e8f0' }}; background: {{ $selectedType === 'tilawati' ? '#fffbeb' : '#ffffff' }}; border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 14px; cursor: pointer; transition: all 0.2s;">
                        <input type="radio" name="type" value="tilawati" {{ $selectedType === 'tilawati' ? 'checked' : '' }} onchange="switchMunaqosahType('tilawati')" style="width: 20px; height: 20px; accent-color: #d97706;">
                        <div>
                            <strong style="color: #92400e; font-size: 1rem; display: block;">
                                <i class="fas fa-book-reader" style="color: #f59e0b; margin-right: 4px;"></i> Tilawati Qur'an
                            </strong>
                            <span style="font-size: 0.8rem; color: #b45309;">Munaqosah Kenaikan Jilid Tilawati (Jilid 1 - 6 / Al-Qur'an)</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 2. Pilih Siswa -->
            <div style="margin-bottom: 26px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label style="font-weight: 800; font-size: 0.92rem; color: #0f172a;">
                        2. Pilih Siswa Peserta Munaqosah <span style="color: #ef4444;">*</span>
                    </label>
                    <span style="font-size: 0.78rem; color: #64748b;">Total {{ $students->count() }} santri aktif</span>
                </div>

                <div style="position: relative; margin-bottom: 8px;">
                    <input type="text" id="student_search_box" placeholder="Ketik nama santri atau kelas (cth: Andra, 3A, dll)..." onkeyup="filterStudentList()" 
                           style="width: 100%; padding: 11px 14px 11px 38px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; background: #f8fafc; color: #0f172a; box-sizing: border-box; transition: all 0.2s;">
                    <i class="fas fa-search" style="position: absolute; left: 14px; top: 14px; color: #94a3b8; font-size: 0.9rem;"></i>
                </div>

                <select name="student_id" id="student_select_box" required size="5" onchange="onStudentSelected(this)" 
                        style="width: 100%; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; padding: 8px; background: white; color: #0f172a; outline: none; box-sizing: border-box;">
                    <option value="" disabled {{ !$selectedStudentId ? 'selected' : '' }}>-- Klik untuk memilih salah satu siswa --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ $selectedStudentId == $st->id ? 'selected' : '' }} data-class="{{ $st->class }}">
                            {{ $st->name }} — Kelas {{ $st->class }} @if($st->nis) (NIS: {{ $st->nis }}) @endif
                        </option>
                    @endforeach
                </select>

                <div id="student_selected_badge" style="{{ $selectedStudent ? 'display: flex;' : 'display: none;' }} align-items: center; justify-content: space-between; gap: 10px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 10px; padding: 8px 14px; margin-top: 8px;">
                    <div style="font-size: 0.85rem; color: #166534;">
                        <i class="fas fa-user-check" style="color: #10b981; margin-right: 6px;"></i>
                        Siswa terpilih: <strong id="selected_student_name_text">{{ $selectedStudent->name ?? '' }}</strong> (Kelas {{ $selectedStudent->class ?? '' }})
                    </div>
                    <span style="font-size: 0.75rem; background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 6px; font-weight: 700;">Aktif</span>
                </div>
            </div>

            <!-- 3. Jenjang yang Dituntaskan & Target Lanjutan -->
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 18px; padding: 22px; margin-bottom: 26px;">
                <div style="font-size: 0.92rem; font-weight: 800; color: #0f172a; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-layer-group" style="color: #0f766e;"></i>
                    3. Jenjang Kelulusan & Target Rekomendasi
                </div>

                {{-- Preset Quick Pills untuk Tahfidz --}}
                <div id="pills_tahfidz_wrap" style="{{ $selectedType === 'tahfidz' ? 'display: block;' : 'display: none;' }} margin-bottom: 16px;">
                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">
                        Pilih Cepat Target Juz Tahfidz:
                    </label>
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        @php $presetJuz = [30, 29, 28, 27, 26, 1, 2, 3]; @endphp
                        @foreach($presetJuz as $pz)
                            <button type="button" onclick="setQuickJuz({{ $pz }})" class="btn-pill-level"
                                    style="background: white; border: 1px solid #cbd5e1; color: #334155; padding: 6px 12px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                                Juz {{ $pz }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Preset Quick Pills untuk Tilawati --}}
                <div id="pills_tilawati_wrap" style="{{ $selectedType === 'tilawati' ? 'display: block;' : 'display: none;' }} margin-bottom: 16px;">
                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">
                        Pilih Cepat Kenaikan Jilid Tilawati:
                    </label>
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        @foreach(['Jilid 1', 'Jilid 2', 'Jilid 3', 'Jilid 4', 'Jilid 5', 'Jilid 6', 'Al-Qur\'an / Ghorib'] as $pj)
                            <button type="button" onclick="setQuickJilid('{{ $pj }}')" class="btn-pill-level"
                                    style="background: white; border: 1px solid #cbd5e1; color: #334155; padding: 6px 12px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all 0.15s;">
                                {{ $pj }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">
                            Jenjang yang Dituntaskan <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="current_level" id="current_level" required 
                               value="{{ old('current_level', $suggestedCurrentLevel ?? ($selectedType === 'tahfidz' ? 'Juz 30' : 'Jilid 1')) }}" 
                               placeholder="Contoh: Juz 30" 
                               style="width: 100%; padding: 11px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.92rem; font-weight: 700; color: #0f172a; background: white; box-sizing: border-box;">
                        <small style="color: #64748b; font-size: 0.76rem; display: block; margin-top: 4px;">
                            Juz/Jilid yang telah diselesaikan ananda.
                        </small>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">
                            Rekomendasi Jenjang Lanjutan <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="target_level" id="target_level" required 
                               value="{{ old('target_level', $selectedType === 'tahfidz' ? 'Juz 29' : 'Jilid 2') }}" 
                               placeholder="Contoh: Juz 29" 
                               style="width: 100%; padding: 11px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.92rem; font-weight: 700; color: #0f172a; background: white; box-sizing: border-box;">
                        <small style="color: #64748b; font-size: 0.76rem; display: block; margin-top: 4px;">
                            Jenjang yang dituju setelah dinyatakan lulus.
                        </small>
                    </div>
                </div>
            </div>

            <!-- 4. Kelompok Mentoring Asal -->
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-weight: 800; font-size: 0.92rem; color: #0f172a; margin-bottom: 8px;">
                    4. Kelompok Mentoring Asal Siswa
                </label>
                <select name="from_group_id" id="from_group_select" style="width: 100%; padding: 11px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; color: #334155; background: #f8fafc;">
                    <option value="">-- Pilih Kelompok Asal (Opsional) --</option>
                    @foreach($groups as $grp)
                        <option value="{{ $grp->id }}" {{ $selectedGroupId == $grp->id ? 'selected' : '' }}>
                            [{{ strtoupper($grp->type) }}] {{ $grp->name }} • Target: {{ $grp->target_level ?? '-' }} (Mentor: {{ $grp->mentor_name ?? '-' }})
                        </option>
                    @endforeach
                </select>
                <small style="color: #64748b; font-size: 0.78rem; display: block; margin-top: 4px;">
                    Membantu panitia memetakan pembimbing asal siswa.
                </small>
            </div>

            <!-- 5. Identitas Pengusul & Catatan Kesiapan -->
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-weight: 800; font-size: 0.92rem; color: #0f172a; margin-bottom: 8px;">
                    5. Catatan Kesiapan / Rekomendasi Guru Pembimbing
                </label>
                <textarea name="proposer_notes" id="proposer_notes" rows="3" 
                          placeholder="Tuliskan catatan kesiapan ananda, misal: Ananda telah menyelesaikan hafalan Juz 30 dengan tasmi' 1 majelis di hadapan guru pembimbing dengan predikat lancar dan mutqin." 
                          style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; line-height: 1.5; box-sizing: border-box;">{{ old('proposer_notes') }}</textarea>
                
                {{-- Quick text chips --}}
                <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px;">
                    <button type="button" onclick="appendNote('✓ Telah tasmi\' mandiri 1 majelis dengan lancar')" 
                            style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; color: #475569; cursor: pointer;">
                        + Tasmi' Lancar
                    </button>
                    <button type="button" onclick="appendNote('✓ Makhorijul huruf dan tajwid sangat baik')" 
                            style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; color: #475569; cursor: pointer;">
                        + Tajwid Sangat Baik
                    </button>
                    <button type="button" onclick="appendNote('✓ Direkomendasikan naik ke jenjang berikutnya')" 
                            style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; color: #475569; cursor: pointer;">
                        + Siap Lanjut Jenjang
                    </button>
                </div>
            </div>

            <!-- 6. Plotting Jadwal & Penguji Langsung (Khusus Admin & Kurikulum) -->
            @if(in_array(Auth::user()->role, ['admin', 'headmaster', 'curriculum']))
                <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 16px; padding: 18px 20px; margin-bottom: 26px;">
                    <div style="font-size: 0.86rem; font-weight: 800; color: #1e40af; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-user-shield"></i> Opsi Admin / Kurikulum: Jadwalkan Langsung (Opsional)
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Pilih Dewan Penguji (Munaqisy)</label>
                            <select name="examiner_id" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; background: white;">
                                <option value="">-- Tetapkan Nanti --</option>
                                @foreach($examiners as $ex)
                                    <option value="{{ $ex->id }}">{{ $ex->user->name ?? 'Penguji' }} ({{ ucfirst($ex->specialization) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Tanggal Ujian</label>
                            <input type="date" name="exam_date" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; background: white; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Ruangan / Tempat</label>
                            <input type="text" name="room_location" placeholder="Masjid / Kelas" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; background: white; box-sizing: border-box;">
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submit Buttons -->
            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 22px;">
                <a href="{{ route('munaqosah.index') }}" style="padding: 12px 22px; border-radius: 12px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    Batal
                </a>
                <button type="submit" style="padding: 12px 30px; border-radius: 12px; border: none; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; font-weight: 800; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 16px rgba(5, 150, 105, 0.35); display: inline-flex; align-items: center; gap: 8px; transition: transform 0.15s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='none'">
                    <i class="fas fa-paper-plane"></i> Kirim Pendaftaran Munaqosah
                </button>
            </div>
        </form>

    </div>

</div>

<script>
function switchMunaqosahType(type) {
    const labelTahfidz = document.getElementById('label_tahfidz');
    const labelTilawati = document.getElementById('label_tilawati');
    const pillsTahfidz = document.getElementById('pills_tahfidz_wrap');
    const pillsTilawati = document.getElementById('pills_tilawati_wrap');
    const cur = document.getElementById('current_level');
    const tgt = document.getElementById('target_level');

    if (type === 'tahfidz') {
        labelTahfidz.style.border = '2px solid #059669';
        labelTahfidz.style.background = '#f0fdf4';
        labelTilawati.style.border = '2px solid #e2e8f0';
        labelTilawati.style.background = '#ffffff';

        pillsTahfidz.style.display = 'block';
        pillsTilawati.style.display = 'none';

        cur.placeholder = 'Contoh: Juz 30';
        tgt.placeholder = 'Contoh: Juz 29';
        if (!cur.value || cur.value.startsWith('Jilid')) {
            cur.value = 'Juz 30';
            tgt.value = 'Juz 29';
        }
    } else {
        labelTilawati.style.border = '2px solid #d97706';
        labelTilawati.style.background = '#fffbeb';
        labelTahfidz.style.border = '2px solid #e2e8f0';
        labelTahfidz.style.background = '#ffffff';

        pillsTahfidz.style.display = 'none';
        pillsTilawati.style.display = 'block';

        cur.placeholder = 'Contoh: Jilid 2';
        tgt.placeholder = 'Contoh: Jilid 3';
        if (!cur.value || cur.value.startsWith('Juz')) {
            cur.value = 'Jilid 1';
            tgt.value = 'Jilid 2';
        }
    }
}

function setQuickJuz(juz) {
    const cur = document.getElementById('current_level');
    const tgt = document.getElementById('target_level');
    cur.value = 'Juz ' + juz;

    // Kalkulasi target berikutnya (jika juz 30 -> 29, 29 -> 28, dst)
    let nextJuz = juz - 1;
    if (nextJuz < 1) nextJuz = 'Selesai 30 Juz';
    else nextJuz = 'Juz ' + nextJuz;

    tgt.value = nextJuz;
}

function setQuickJilid(jilid) {
    const cur = document.getElementById('current_level');
    const tgt = document.getElementById('target_level');
    cur.value = jilid;

    const jilidMap = {
        'Jilid 1': 'Jilid 2',
        'Jilid 2': 'Jilid 3',
        'Jilid 3': 'Jilid 4',
        'Jilid 4': 'Jilid 5',
        'Jilid 5': 'Jilid 6',
        'Jilid 6': 'Al-Qur\'an / Ghorib',
        'Al-Qur\'an / Ghorib': 'Tashih Akhir'
    };
    tgt.value = jilidMap[jilid] || 'Jenjang Lanjutan';
}

function filterStudentList() {
    const query = document.getElementById('student_search_box').value.toLowerCase();
    const select = document.getElementById('student_select_box');
    for (let opt of select.options) {
        if (!opt.value) continue;
        const text = opt.text.toLowerCase();
        opt.style.display = text.includes(query) ? '' : 'none';
    }
}

function onStudentSelected(select) {
    const selectedOpt = select.options[select.selectedIndex];
    const badge = document.getElementById('student_selected_badge');
    const nameText = document.getElementById('selected_student_name_text');

    if (selectedOpt && selectedOpt.value) {
        badge.style.display = 'flex';
        nameText.textContent = selectedOpt.text;
    } else {
        badge.style.display = 'none';
    }
}

function appendNote(text) {
    const textarea = document.getElementById('proposer_notes');
    if (!textarea.value.trim()) {
        textarea.value = text;
    } else {
        textarea.value += "\n" + text;
    }
}

function copyPublicLink() {
    const url = "{{ route('munaqosah.public-register') }}";
    const waText = "Assalamu'alaikum Warahmatullahi Wabarakatuh 🌙\n\nBapak/Ibu Guru Pembimbing & Wali Santri SDIT An Nadzir yang kami hormati,\n\nPendaftaran Ujian Munaqosah Qur'an (Tahfidz & Tilawati) kini dapat diajukan secara online melalui tautan resmi berikut:\n\n🔗 " + url + "\n\nMohon pastikan santri telah menuntaskan seluruh materi Juz/Jilid dengan tasmi' mandiri di hadapan guru pembimbing.\n\nJazakumullah Khairan Katsiran 🤲";

    if (navigator.clipboard) {
        navigator.clipboard.writeText(waText).then(() => {
            alert("✓ Teks dan Link WhatsApp Formulir Pendaftaran Munaqosah berhasil disalin ke clipboard! Silakan paste (tempel) di grup WhatsApp.");
        }).catch(() => {
            prompt("Salin teks pesan pendaftaran berikut:", waText);
        });
    } else {
        prompt("Salin teks pesan pendaftaran berikut:", waText);
    }
}
</script>
@endsection
