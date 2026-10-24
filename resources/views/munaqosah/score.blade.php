@extends('layouts.app')

@section('title', 'Form Penilaian Munaqisy')
@section('page-title', 'Form Penilaian Munaqisy')

@push('styles')
<style>
    .score-container {
        max-width: 900px;
        margin: 0 auto;
        padding-bottom: 50px;
    }
    .score-candidate-banner {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);
        border-radius: 22px;
        padding: 25px 30px;
        color: white;
        margin-bottom: 25px;
        box-shadow: 0 10px 25px -5px rgba(6, 78, 59, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    .score-form-card {
        background: white;
        border-radius: 22px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        overflow: hidden;
    }
    .score-form-body {
        padding: 28px;
    }
    .score-criteria-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }
    .live-score-bar {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        border: 2px solid #a7f3d0;
        border-radius: 18px;
        padding: 20px 24px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .decision-radio-wrap {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .score-action-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        border-top: 1px solid #f1f5f9;
        padding-top: 20px;
    }
    @media (max-width: 768px) {
        .score-candidate-banner {
            padding: 20px 18px;
        }
        .score-candidate-banner h2 {
            font-size: 1.35rem !important;
        }
        .score-form-body {
            padding: 18px 16px;
        }
        .score-criteria-grid {
            grid-template-columns: 1fr !important;
            gap: 14px;
        }
        .live-score-bar {
            flex-direction: column;
            align-items: flex-start !important;
            padding: 16px;
        }
        .decision-radio-wrap {
            flex-direction: column;
            width: 100%;
        }
        .decision-radio-wrap label {
            width: 100%;
            box-sizing: border-box;
            justify-content: center;
        }
        .score-action-footer {
            flex-direction: column-reverse;
        }
        .score-action-footer a,
        .score-action-footer button {
            width: 100% !important;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="score-container">

    <!-- Back Navigation -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; flex-wrap: wrap; gap: 10px;">
        <a href="{{ route('munaqosah.index') }}" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #475569; font-weight: 700; font-size: 0.9rem; background: white; padding: 8px 16px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
            <i class="fas fa-arrow-left"></i> Kembali ke Rekapan Munaqosah
        </a>
        <span style="font-size: 0.82rem; font-weight: 800; color: #047857; background: #ecfdf5; padding: 4px 14px; border-radius: 20px;">
            <i class="fas fa-award"></i> Evaluasi Dewan Munaqisy
        </span>
    </div>

    <!-- Candidate Profile Banner -->
    <div class="score-candidate-banner">
        <div>
            <span style="background: rgba(255,255,255,0.2); font-size: 0.76rem; font-weight: 800; padding: 3px 10px; border-radius: 12px; text-transform: uppercase;">
                {{ $registration->type === 'tilawati' ? 'Munaqosah Tilawati' : 'Munaqosah Tahfidz' }}
            </span>
            <h2 style="font-size: 1.6rem; font-weight: 900; margin: 8px 0 4px 0;">
                {{ $registration->student->name ?? 'Siswa' }}
            </h2>
            <div style="font-size: 0.88rem; opacity: 0.92; display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
                <span><i class="fas fa-graduation-cap"></i> Kelas: <strong>{{ $registration->student->class ?? '-' }}</strong></span>
                <span><i class="fas fa-id-card"></i> NIS: <strong>{{ $registration->student->nis ?? '-' }}</strong></span>
                <span><i class="fas fa-user-edit"></i> Pengusul: <strong>{{ $registration->proposer->name ?? '-' }}</strong></span>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); border-radius: 16px; padding: 14px 20px; text-align: center;">
            <span style="font-size: 0.74rem; opacity: 0.85; text-transform: uppercase; font-weight: 700; display: block;">Munaqosah Kenaikan:</span>
            <div style="font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 8px; justify-content: center; margin-top: 2px;">
                <span>{{ $registration->current_level }}</span>
                <i class="fas fa-arrow-right" style="font-size: 0.85rem; color: #a7f3d0;"></i>
                <span style="color: #a7f3d0;">{{ $registration->target_level }}</span>
            </div>
        </div>
    </div>

    <!-- Scoring Rubric Form -->
    <div class="score-form-card">
        <div style="padding: 20px 28px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-star-half-alt" style="color: #f59e0b;"></i> Rubrik Penilaian Standar Munaqisy
            </h3>
            <span style="font-size: 0.8rem; color: #64748b;">Standar Penilaian 0 - 100</span>
        </div>

        <form method="POST" action="{{ route('munaqosah.score.store', $registration->id) }}" class="score-form-body">
            @csrf

            <!-- 4 Criteria Scoring Grid -->
            <div class="score-criteria-grid">
                
                <!-- 1. Fashahah / Kelancaran -->
                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label style="font-weight: 800; font-size: 0.92rem; color: #0f172a;">
                            1. Kelancaran & Fashahah
                        </label>
                        <span style="background: #eff6ff; color: #2563eb; font-size: 0.74rem; font-weight: 800; padding: 2px 8px; border-radius: 6px;">
                            Bobot 30%
                        </span>
                    </div>
                    <p style="font-size: 0.78rem; color: #64748b; margin: 0 0 12px 0;">
                        Kelancaran bacaan, waqaf & ibtida' tepat, tidak terbata-bata atau lupa ayat.
                    </p>
                    <input type="number" step="0.5" min="0" max="100" name="score_fashahah" id="score_fashahah" required 
                           value="{{ old('score_fashahah', $registration->score_fashahah ?? 85) }}" oninput="calculateLiveScore()" 
                           style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 1.15rem; font-weight: 900; color: #0f172a; background: white;">
                </div>

                <!-- 2. Tajwid -->
                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label style="font-weight: 800; font-size: 0.92rem; color: #0f172a;">
                            2. Kaidah Tajwid
                        </label>
                        <span style="background: #eff6ff; color: #2563eb; font-size: 0.74rem; font-weight: 800; padding: 2px 8px; border-radius: 6px;">
                            Bobot 30%
                        </span>
                    </div>
                    <p style="font-size: 0.78rem; color: #64748b; margin: 0 0 12px 0;">
                        Hukum nun/mim mati, mad, ghunnah, ketepatan panjang pendek harakat.
                    </p>
                    <input type="number" step="0.5" min="0" max="100" name="score_tajwid" id="score_tajwid" required 
                           value="{{ old('score_tajwid', $registration->score_tajwid ?? 85) }}" oninput="calculateLiveScore()" 
                           style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 1.15rem; font-weight: 900; color: #0f172a; background: white;">
                </div>

                <!-- 3. Makhraj -->
                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label style="font-weight: 800; font-size: 0.92rem; color: #0f172a;">
                            3. Makharijul & Sifatul Huruf
                        </label>
                        <span style="background: #eff6ff; color: #2563eb; font-size: 0.74rem; font-weight: 800; padding: 2px 8px; border-radius: 6px;">
                            Bobot 25%
                        </span>
                    </div>
                    <p style="font-size: 0.78rem; color: #64748b; margin: 0 0 12px 0;">
                        Ketepatan tempat keluar huruf hijaiyyah, sifat tebal/tipis, hams & jahr.
                    </p>
                    <input type="number" step="0.5" min="0" max="100" name="score_makhraj" id="score_makhraj" required 
                           value="{{ old('score_makhraj', $registration->score_makhraj ?? 85) }}" oninput="calculateLiveScore()" 
                           style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 1.15rem; font-weight: 900; color: #0f172a; background: white;">
                </div>

                <!-- 4. Adab & Lagu / Irama -->
                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label style="font-weight: 800; font-size: 0.92rem; color: #0f172a;">
                            4. Adab & Irama Tilawah
                        </label>
                        <span style="background: #eff6ff; color: #2563eb; font-size: 0.74rem; font-weight: 800; padding: 2px 8px; border-radius: 6px;">
                            Bobot 15%
                        </span>
                    </div>
                    <p style="font-size: 0.78rem; color: #64748b; margin: 0 0 12px 0;">
                        Adab tilawah, ketenangan, tempo tartil, dan keselarasan irama tilawati.
                    </p>
                    <input type="number" step="0.5" min="0" max="100" name="score_adab_lagu" id="score_adab_lagu" required 
                           value="{{ old('score_adab_lagu', $registration->score_adab_lagu ?? 85) }}" oninput="calculateLiveScore()" 
                           style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 1.15rem; font-weight: 900; color: #0f172a; background: white;">
                </div>

            </div>

            <!-- Real-time Score Display Bar -->
            <div class="live-score-bar">
                <div>
                    <span style="font-size: 0.82rem; font-weight: 800; color: #065f46; text-transform: uppercase;">
                        Nilai Akhir Terhitung
                    </span>
                    <div style="display: flex; align-items: baseline; gap: 10px; margin-top: 4px;">
                        <span id="display_final_score" style="font-size: 2.3rem; font-weight: 900; color: #047857;">85.0</span>
                        <span id="display_predicate" style="background: #10b981; color: white; font-size: 0.85rem; font-weight: 800; padding: 4px 14px; border-radius: 20px;">
                            Jayyid Jiddan
                        </span>
                    </div>
                </div>

                <!-- Decision Radio -->
                <div style="width: auto;">
                    <label style="display: block; font-weight: 800; font-size: 0.84rem; color: #065f46; margin-bottom: 6px;">
                        Keputusan Dewan Munaqisy <span style="color: #ef4444;">*</span>:
                    </label>
                    <div class="decision-radio-wrap">
                        <label id="label_pass" style="background: #10b981; color: white; border-radius: 12px; padding: 10px 16px; display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 800; font-size: 0.9rem; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                            <input type="radio" name="status" id="status_pass" value="passed" checked onchange="handleStatusChange('passed')" style="accent-color: #047857;">
                            <i class="fas fa-check-circle"></i> LULUS MUNAQOSAH
                        </label>
                        <label id="label_fail" style="background: white; color: #dc2626; border: 1.5px solid #fecaca; border-radius: 12px; padding: 10px 16px; display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 800; font-size: 0.9rem;">
                            <input type="radio" name="status" id="status_fail" value="failed" onchange="handleStatusChange('failed')" style="accent-color: #dc2626;">
                            <i class="fas fa-times-circle"></i> BELUM LULUS (MENGULANG)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Recommendation & Notes -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 800; font-size: 0.88rem; color: #0f172a; margin-bottom: 6px;">
                    Rekomendasi Jenjang Lanjutan
                </label>
                <input type="text" name="recommendation" id="recommendation_input" 
                       value="{{ old('recommendation', $registration->recommendation ?? ('Naik ke ' . $registration->target_level)) }}" 
                       style="width: 100%; padding: 11px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; font-weight: 600; box-sizing: border-box;">
                <small style="color: #64748b; font-size: 0.76rem; display: block; margin-top: 4px;">
                    Contoh: <em>Naik ke Juz 29</em> atau <em>Ulangi Juz 30 pada bagian Surah An-Naba s/d Al-Muthaffifin</em>.
                </small>
            </div>

            <div style="margin-bottom: 26px;">
                <label style="display: block; font-weight: 800; font-size: 0.88rem; color: #0f172a; margin-bottom: 6px;">
                    Catatan Munaqisy untuk Siswa & Guru Pembimbing
                </label>
                <textarea name="examiner_notes" rows="3" placeholder="Tuliskan catatan khusus, kelebihan bacaan siswa, atau bagian yang perlu dimutqinkan kembali..." 
                          style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; line-height: 1.5; box-sizing: border-box;">{{ old('examiner_notes', $registration->examiner_notes) }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="score-action-footer">
                <a href="{{ route('munaqosah.index') }}" style="padding: 11px 20px; border-radius: 12px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    Batal
                </a>
                <button type="submit" style="padding: 11px 30px; border-radius: 12px; border: none; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; font-weight: 800; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 15px rgba(5, 150, 105, 0.35); display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-save"></i> Simpan & Terbitkan Hasil Munaqosah
                </button>
            </div>

        </form>
    </div>

</div>

<script>
function calculateLiveScore() {
    const fashahah = parseFloat(document.getElementById('score_fashahah').value) || 0;
    const tajwid = parseFloat(document.getElementById('score_tajwid').value) || 0;
    const makhraj = parseFloat(document.getElementById('score_makhraj').value) || 0;
    const adab = parseFloat(document.getElementById('score_adab_lagu').value) || 0;

    const total = ((fashahah * 0.30) + (tajwid * 0.30) + (makhraj * 0.25) + (adab * 0.15)).toFixed(1);
    document.getElementById('display_final_score').textContent = total;

    let predicate = 'Rasib / Kurang';
    let badgeColor = '#ef4444';
    if (total >= 90) {
        predicate = 'Mumtaz (Istimewa)';
        badgeColor = '#059669';
    } else if (total >= 80) {
        predicate = 'Jayyid Jiddan (Sangat Baik)';
        badgeColor = '#10b981';
    } else if (total >= 70) {
        predicate = 'Jayyid (Baik)';
        badgeColor = '#3b82f6';
    } else if (total >= 60) {
        predicate = 'Maqbul (Cukup)';
        badgeColor = '#f59e0b';
    }

    const badge = document.getElementById('display_predicate');
    badge.textContent = predicate;
    badge.style.background = badgeColor;

    // Auto suggest Pass/Fail
    if (total >= 70) {
        handleStatusChange('passed');
        document.getElementById('status_pass').checked = true;
    } else {
        handleStatusChange('failed');
        document.getElementById('status_fail').checked = true;
    }
}

function handleStatusChange(status) {
    const labelPass = document.getElementById('label_pass');
    const labelFail = document.getElementById('label_fail');
    const rec = document.getElementById('recommendation_input');

    if (status === 'passed') {
        labelPass.style.background = '#10b981';
        labelPass.style.color = 'white';
        labelPass.style.border = 'none';

        labelFail.style.background = 'white';
        labelFail.style.color = '#dc2626';
        labelFail.style.border = '1.5px solid #fecaca';

        if (!rec.value || rec.value.includes('Ulangi')) {
            rec.value = 'Naik ke {{ $registration->target_level }}';
        }
    } else {
        labelFail.style.background = '#dc2626';
        labelFail.style.color = 'white';
        labelFail.style.border = 'none';

        labelPass.style.background = 'white';
        labelPass.style.color = '#047857';
        labelPass.style.border = '1.5px solid #a7f3d0';

        if (!rec.value || rec.value.includes('Naik ke')) {
            rec.value = 'Ulangi {{ $registration->current_level }}';
        }
    }
}

// Initial calculation on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateLiveScore();
});
</script>
@endsection
