@extends('layouts.app')

@section('title', 'Input Instrumen Supervisi Guru - SIMUTQIN')
@section('page-title', 'Input Supervisi Guru')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

    <!-- TOP HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 8px; background: #ecfdf5; color: #059669; padding: 4px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 700; margin-bottom: 6px;">
                <i class="fas fa-edit"></i> Formulir Observasi & Penilaian
            </div>
            <h2 style="margin: 0; font-size: 1.6rem; font-weight: 900; color: #0f172a; letter-spacing: -0.02em;">
                Input Instrumen Supervisi Guru
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 0.9rem; color: #64748b;">
                Pilih jenis instrumen, isi penilaian indikator (skala 1-4), dan berikan rekomendasi pembinaan.
            </p>
        </div>

        <a href="{{ route('supervisions.index') }}" class="btn" style="background: #f1f5f9; color: #475569; font-weight: 700; padding: 9px 18px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>

    @if ($errors->any())
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 14px 18px; border-radius: 8px; font-size: 0.9rem;">
            <div style="font-weight: 700; margin-bottom: 4px;">Terdapat kesalahan pada input:</div>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('supervisions.store') }}" method="POST" id="supervisionForm">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
            
            <!-- LEFT MAIN: FORM INPUT & RUBRIC -->
            <div style="display: flex; flex-direction: column; gap: 24px;">

                <!-- 1. DATA UMUM SUPERVISI -->
                <div class="card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                    <h3 style="margin: 0 0 18px 0; font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                        <span style="width: 28px; height: 28px; border-radius: 50%; background: #ecfdf5; color: #059669; display: inline-flex; align-items: center; justify-content: center; font-size: 0.85rem;">1</span>
                        Informasi Umum Supervisi
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                        
                        <!-- Guru -->
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.85rem;">
                                Nama Guru yang Disupervisi <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="teacher_id" id="teacherSelect" required class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 0.92rem; font-weight: 600;">
                                <option value="">-- Pilih Guru --</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}" {{ (old('teacher_id', $selectedTeacherId) == $t->id) ? 'selected' : '' }}>
                                        {{ $t->name }} {{ $t->homeroom_class ? '(Wali ' . $t->homeroom_class . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jenis Supervisi -->
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #334155; font-size: 0.85rem;">
                                Jenis Instrumen Supervisi <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px;">
                                <label style="display: flex; align-items: center; gap: 10px; padding: 12px 14px; border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.2s ease;" class="type-selector-card" id="card_type_kbm">
                                    <input type="radio" name="supervision_type" value="kbm" {{ old('supervision_type', $defaultType) == 'kbm' ? 'checked' : '' }} onchange="onTypeChange('kbm')" style="accent-color: #059669; transform: scale(1.15);">
                                    <div>
                                        <div style="font-weight: 800; font-size: 0.88rem; color: #1e293b;"><i class="fas fa-chalkboard-teacher" style="color: #4338ca; margin-right: 4px;"></i> KBM di Kelas</div>
                                        <div style="font-size: 0.72rem; color: #64748b;">Observasi proses belajar</div>
                                    </div>
                                </label>

                                <label style="display: flex; align-items: center; gap: 10px; padding: 12px 14px; border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.2s ease;" class="type-selector-card" id="card_type_modul_ajar">
                                    <input type="radio" name="supervision_type" value="modul_ajar" {{ old('supervision_type', $defaultType) == 'modul_ajar' ? 'checked' : '' }} onchange="onTypeChange('modul_ajar')" style="accent-color: #059669; transform: scale(1.15);">
                                    <div>
                                        <div style="font-weight: 800; font-size: 0.88rem; color: #1e293b;"><i class="fas fa-file-signature" style="color: #b45309; margin-right: 4px;"></i> Modul Ajar/RPP</div>
                                        <div style="font-size: 0.72rem; color: #64748b;">Perencanaan pembelajaran</div>
                                    </div>
                                </label>

                                <label style="display: flex; align-items: center; gap: 10px; padding: 12px 14px; border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.2s ease;" class="type-selector-card" id="card_type_quran">
                                    <input type="radio" name="supervision_type" value="quran" {{ old('supervision_type', $defaultType) == 'quran' ? 'checked' : '' }} onchange="onTypeChange('quran')" style="accent-color: #059669; transform: scale(1.15);">
                                    <div>
                                        <div style="font-weight: 800; font-size: 0.88rem; color: #1e293b;"><i class="fas fa-quran" style="color: #047857; margin-right: 4px;"></i> Al-Qur'an</div>
                                        <div style="font-size: 0.72rem; color: #64748b;">Tahfidz & Tilawati</div>
                                    </div>
                                </label>

                                <label style="display: flex; align-items: center; gap: 10px; padding: 12px 14px; border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.2s ease;" class="type-selector-card" id="card_type_eskul">
                                    <input type="radio" name="supervision_type" value="eskul" {{ old('supervision_type', $defaultType) == 'eskul' ? 'checked' : '' }} onchange="onTypeChange('eskul')" style="accent-color: #059669; transform: scale(1.15);">
                                    <div>
                                        <div style="font-weight: 800; font-size: 0.88rem; color: #1e293b;"><i class="fas fa-shapes" style="color: #be185d; margin-right: 4px;"></i> Ekstrakurikuler</div>
                                        <div style="font-size: 0.72rem; color: #64748b;">Bimbingan minat bakat</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Tanggal Supervisi -->
                        <div>
                            <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.85rem;">
                                Tanggal Pelaksanaan <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="date" name="supervision_date" value="{{ old('supervision_date', date('Y-m-d')) }}" required class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 0.92rem;">
                        </div>

                        <!-- Kelas / Rombel -->
                        <div>
                            <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.85rem;">
                                Kelas / Rombongan Belajar
                            </label>
                            <input type="text" name="class_name" list="classList" value="{{ old('class_name') }}" placeholder="Contoh: Kelas 5A atau Semua Kelas" class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 0.92rem;">
                            <datalist id="classList">
                                @foreach($classes as $c)
                                    <option value="{{ $c->name }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <!-- Mata Pelajaran / Nama Kegiatan -->
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.85rem;">
                                Mata Pelajaran / Nama Kegiatan / Cabang Eskul
                            </label>
                            <input type="text" name="subject_name" list="subjectList" value="{{ old('subject_name') }}" placeholder="Contoh: Matematika, Tahfidz Juz 30, Panahan, Bahasa Arab" class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 0.92rem;">
                            <datalist id="subjectList">
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->name }}"></option>
                                @endforeach
                                @foreach($eskuls as $esk)
                                    <option value="Eskul {{ $esk->name }}"></option>
                                @endforeach
                                <option value="Tahfidz Al-Qur'an"></option>
                                <option value="Tilawati Al-Qur'an"></option>
                            </datalist>
                        </div>

                    </div>
                </div>

                <!-- 2. LEMBAR RUBRIK PENILAIAN INDIKATOR -->
                <div class="card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                <span style="width: 28px; height: 28px; border-radius: 50%; background: #ecfdf5; color: #059669; display: inline-flex; align-items: center; justify-content: center; font-size: 0.85rem;">2</span>
                                <span id="rubricTitleText">Penilaian Indikator Observasi</span>
                            </h3>
                            <p style="margin: 4px 0 0 0; font-size: 0.84rem; color: #64748b;" id="rubricDescText">
                                Berikan penilaian pada setiap aspek berdasarkan fakta pengamatan (Skala 1 - 4).
                            </p>
                        </div>

                        <!-- Skala Guide -->
                        <div style="display: flex; gap: 6px; font-size: 0.75rem; font-weight: 700;">
                            <span style="background: #fef2f2; color: #991b1b; padding: 4px 8px; border-radius: 6px;">1 = Kurang</span>
                            <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 6px;">2 = Cukup</span>
                            <span style="background: #eff6ff; color: #1e40af; padding: 4px 8px; border-radius: 6px;">3 = Baik</span>
                            <span style="background: #ecfdf5; color: #065f46; padding: 4px 8px; border-radius: 6px;">4 = Sangat Baik</span>
                        </div>
                    </div>

                    <!-- CONTAINER INDIKATOR RUBRIK DYNAMIC -->
                    <div id="aspectsContainer" style="display: flex; flex-direction: column; gap: 16px; margin-top: 18px;">
                        <!-- Injected via JavaScript based on selected type -->
                    </div>
                </div>

                <!-- 3. CATATAN KUALITATIF & REKOMENDASI -->
                <div class="card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                    <h3 style="margin: 0 0 18px 0; font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                        <span style="width: 28px; height: 28px; border-radius: 50%; background: #ecfdf5; color: #059669; display: inline-flex; align-items: center; justify-content: center; font-size: 0.85rem;">3</span>
                        Catatan Evaluasi, Apresiasi & Tindak Lanjut
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        
                        <!-- Kelebihan / Apresiasi -->
                        <div>
                            <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #065f46; font-size: 0.85rem;">
                                <i class="fas fa-thumbs-up" style="color: #10b981; margin-right: 4px;"></i> Kelebihan & Hal Positif yang Ditemukan (Strengths)
                            </label>
                            <textarea name="strengths" rows="3" placeholder="Tuliskan apresiasi, kekuatan guru, suasana kelas yang hidup, inovasi media, atau penanaman adab yang terlaksana dengan baik..." class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 0.88rem; line-height: 1.5;">{{ old('strengths') }}</textarea>
                        </div>

                        <!-- Hal yang Perlu Ditingkatkan -->
                        <div>
                            <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #9a3412; font-size: 0.85rem;">
                                <i class="fas fa-lightbulb" style="color: #f59e0b; margin-right: 4px;"></i> Aspek yang Perlu Ditingkatkan / Dibenahi (Areas of Improvement)
                            </label>
                            <textarea name="improvements" rows="3" placeholder="Tuliskan catatan perbaikan, misalnya: manajemen waktu, diferensiasi siswa lambat, konsistensi makhraj, kejelasan LKPD..." class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 0.88rem; line-height: 1.5;">{{ old('improvements') }}</textarea>
                        </div>

                        <!-- Rekomendasi & Tindak Lanjut -->
                        <div>
                            <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #1e3a8a; font-size: 0.85rem;">
                                <i class="fas fa-hand-holding-heart" style="color: #3b82f6; margin-right: 4px;"></i> Rekomendasi Pembinaan & Rencana Tindak Lanjut (Coaching)
                            </label>
                            <textarea name="recommendations" rows="3" placeholder="Saran konkret kepala sekolah: pembinaan berkala dengan kurikulum, saling observasi antarguru, pelatihan media..." class="form-control" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 0.88rem; line-height: 1.5;">{{ old('recommendations') }}</textarea>
                        </div>

                        <!-- Target Tanggal Evaluasi Tindak Lanjut -->
                        <div>
                            <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.85rem;">
                                Target Tanggal Review / Evaluasi Lanjutan (Opsional)
                            </label>
                            <input type="date" name="follow_up_date" value="{{ old('follow_up_date') }}" class="form-control" style="width: 250px; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-size: 0.88rem;">
                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: FLOATING SCORE SUMMARY & SUBMIT -->
            <div style="position: sticky; top: 20px; display: flex; flex-direction: column; gap: 20px;">
                
                <!-- SUMMARY SCORE CARD -->
                <div class="card" style="border: 2px solid #059669; border-radius: 16px; padding: 24px; box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.15); background: #ffffff;">
                    <div style="text-align: center; margin-bottom: 16px;">
                        <span style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">
                            Skor Hasil Supervisi
                        </span>
                        <div id="displayTotalScore" style="font-size: 3.2rem; font-weight: 900; color: #059669; line-height: 1; margin: 10px 0 4px 0;">
                            0.0
                        </div>
                        <div id="displayPredicate" style="font-size: 1rem; font-weight: 800; color: #059669; background: #ecfdf5; display: inline-block; padding: 4px 14px; border-radius: 999px;">
                            Perlu Pembinaan (D)
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div style="background: #f1f5f9; height: 10px; border-radius: 999px; overflow: hidden; margin-bottom: 16px;">
                        <div id="displayProgressBar" style="background: #059669; height: 100%; width: 0%; border-radius: 999px; transition: width 0.3s ease, background 0.3s ease;"></div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.82rem; color: #475569; border-top: 1px dashed #e2e8f0; padding-top: 14px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>Poin Perolehan:</span>
                            <strong id="displayTotalPoints">0</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Poin Maksimal:</span>
                            <strong id="displayMaxPoints">0</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Jumlah Aspek:</span>
                            <strong id="displayAspectCount">0</strong>
                        </div>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <button type="submit" class="btn" style="width: 100%; background: #059669; color: white; font-weight: 800; padding: 12px; border-radius: 10px; font-size: 0.95rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.4);">
                        <i class="fas fa-check-circle"></i> Simpan Hasil Supervisi
                    </button>

                    <a href="{{ route('supervisions.index') }}" style="display: block; text-align: center; margin-top: 12px; font-size: 0.85rem; font-weight: 700; color: #64748b; text-decoration: none;">
                        Batalkan
                    </a>
                </div>

                <!-- INFO BOX -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; font-size: 0.82rem; color: #64748b; line-height: 1.45;">
                    <div style="font-weight: 700; color: #334155; margin-bottom: 4px;">
                        <i class="fas fa-info-circle" style="color: #059669;"></i> Dokumen Resmi Berita Acara
                    </div>
                    Setelah disimpan, lembar supervisi ini dapat langsung dicetak resmi dengan format kop surat dan tanda tangan kepala sekolah untuk arsip akreditasi sekolah.
                </div>

            </div>

        </div>
    </form>

</div>

{{-- PASS RUBRIC DEFINITIONS TO JAVASCRIPT --}}
<script>
    const rubricsData = @json($rubrics);
    let currentType = '{{ old("supervision_type", $defaultType) }}';
    const initialScores = @json(old('scores', []));

    function onTypeChange(type) {
        currentType = type;
        
        // Update active class on cards
        document.querySelectorAll('.type-selector-card').forEach(card => {
            card.style.borderColor = '#e2e8f0';
            card.style.background = '#ffffff';
        });
        const activeCard = document.getElementById('card_type_' + type);
        if (activeCard) {
            activeCard.style.borderColor = '#059669';
            activeCard.style.background = '#ecfdf5';
        }

        renderRubrics(type);
    }

    function renderRubrics(type) {
        const rubric = rubricsData[type];
        if (!rubric) return;

        document.getElementById('rubricTitleText').textContent = rubric.title;
        document.getElementById('rubricDescText').textContent = rubric.description;

        const container = document.getElementById('aspectsContainer');
        container.innerHTML = '';

        rubric.aspects.forEach((aspect, index) => {
            const aspectDiv = document.createElement('div');
            aspectDiv.style.cssText = 'background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; transition: border-color 0.2s ease;';
            aspectDiv.id = 'aspect_box_' + aspect.id;

            const savedScore = initialScores[aspect.id] || 3;

            aspectDiv.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 280px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span style="width: 22px; height: 22px; border-radius: 50%; background: #059669; color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 800;">
                                ${index + 1}
                            </span>
                            <div style="font-weight: 800; font-size: 0.94rem; color: #0f172a;">
                                ${aspect.title}
                            </div>
                        </div>
                        <div style="font-size: 0.83rem; color: #475569; line-height: 1.45; margin-left: 30px;">
                            ${aspect.desc}
                        </div>
                    </div>

                    <div style="display: flex; gap: 8px; align-items: center;">
                        ${[1, 2, 3, 4].map(score => `
                            <label style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 44px; height: 48px; border-radius: 8px; border: 2px solid #cbd5e1; background: #ffffff; cursor: pointer; transition: all 0.15s ease;" class="score-btn" id="lbl_${aspect.id}_${score}">
                                <input type="radio" name="scores[${aspect.id}]" value="${score}" ${savedScore == score ? 'checked' : ''} onchange="calculateScore()" style="display: none;">
                                <span style="font-weight: 900; font-size: 1rem; color: #1e293b;">${score}</span>
                                <span style="font-size: 0.65rem; font-weight: 700; color: #64748b;">
                                    ${score === 4 ? 'Amat' : (score === 3 ? 'Baik' : (score === 2 ? 'Cukup' : 'Kurang'))}
                                </span>
                            </label>
                        `).join('')}
                    </div>
                </div>
            `;

            container.appendChild(aspectDiv);
        });

        calculateScore();
    }

    function calculateScore() {
        const rubric = rubricsData[currentType];
        if (!rubric) return;

        let totalPoints = 0;
        const maxPoints = rubric.aspects.length * 4;

        rubric.aspects.forEach(aspect => {
            const checked = document.querySelector(`input[name="scores[${aspect.id}]"]:checked`);
            const val = checked ? parseInt(checked.value) : 3;
            totalPoints += val;

            // Highlight buttons
            [1, 2, 3, 4].forEach(score => {
                const lbl = document.getElementById(`lbl_${aspect.id}_${score}`);
                if (lbl) {
                    if (val === score) {
                        lbl.style.borderColor = '#059669';
                        lbl.style.background = '#ecfdf5';
                        lbl.querySelector('span').style.color = '#059669';
                    } else {
                        lbl.style.borderColor = '#cbd5e1';
                        lbl.style.background = '#ffffff';
                        lbl.querySelector('span').style.color = '#1e293b';
                    }
                }
            });
        });

        const percentage = maxPoints > 0 ? ((totalPoints / maxPoints) * 100).toFixed(1) : 0;

        let predicate = 'Perlu Pembinaan (D)';
        let color = '#dc2626';
        let bg = '#fef2f2';

        if (percentage >= 91) {
            predicate = 'Sangat Baik (A)';
            color = '#059669';
            bg = '#ecfdf5';
        } else if (percentage >= 81) {
            predicate = 'Baik (B)';
            color = '#2563eb';
            bg = '#eff6ff';
        } else if (percentage >= 71) {
            predicate = 'Cukup (C)';
            color = '#d97706';
            bg = '#fffbeb';
        }

        document.getElementById('displayTotalScore').textContent = percentage;
        document.getElementById('displayTotalScore').style.color = color;

        const predBadge = document.getElementById('displayPredicate');
        predBadge.textContent = predicate;
        predBadge.style.color = color;
        predBadge.style.background = bg;

        const progressBar = document.getElementById('displayProgressBar');
        progressBar.style.width = percentage + '%';
        progressBar.style.background = color;

        document.getElementById('displayTotalPoints').textContent = totalPoints;
        document.getElementById('displayMaxPoints').textContent = maxPoints;
        document.getElementById('displayAspectCount').textContent = rubric.aspects.length + ' Indikator';
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        onTypeChange(currentType);
    });
</script>
@endsection
