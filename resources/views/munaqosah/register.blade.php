@extends('layouts.app')

@section('title', 'Pendaftaran Munaqosah Qur\'an')
@section('page-title', 'Pendaftaran Munaqosah Qur\'an')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding-bottom: 50px;">

    <!-- Back Button & Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px;">
        <a href="{{ route('munaqosah.index') }}" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #475569; font-weight: 700; font-size: 0.9rem; background: white; padding: 8px 16px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
            <i class="fas fa-arrow-left"></i> Kembali ke Rekapan Munaqosah
        </a>
        <span style="font-size: 0.82rem; font-weight: 700; color: #047857; background: #ecfdf5; padding: 4px 12px; border-radius: 20px;">
            Tahun Ajaran {{ $activeYear->name ?? '-' }}
        </span>
    </div>

    <!-- Main Card Form -->
    <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.04); overflow: hidden;">
        
        <!-- Form Header -->
        <div style="background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0d9488 100%); color: white; padding: 26px 30px; position: relative;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fas fa-file-signature"></i>
                </div>
                <div>
                    <h2 style="font-size: 1.45rem; font-weight: 800; margin: 0;">Pendaftaran Munaqosah Qur'an</h2>
                    <p style="margin: 0; font-size: 0.86rem; opacity: 0.9;">
                        Formulir usulan kelulusan santri tuntas Juz (Tahfidz) atau Jilid (Tilawati) oleh Guru Pembimbing.
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('munaqosah.register.store') }}" style="padding: 30px;">
            @csrf

            <!-- 1. Tipe Pembinaan -->
            <div style="margin-bottom: 22px;">
                <label style="display: block; font-weight: 800; font-size: 0.9rem; color: #0f172a; margin-bottom: 8px;">
                    1. Bidang Pembinaan Munaqosah <span style="color: #ef4444;">*</span>
                </label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <label id="label_tahfidz" style="border: 2px solid #10b981; background: #f0fdf4; border-radius: 14px; padding: 14px 18px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: all 0.2s;">
                        <input type="radio" name="type" value="tahfidz" checked onchange="toggleTypeStyle('tahfidz')" style="width: 18px; height: 18px; accent-color: #059669;">
                        <div>
                            <strong style="color: #065f46; font-size: 0.95rem; display: block;"><i class="fas fa-star-and-crescent"></i> Tahfidz Qur'an</strong>
                            <span style="font-size: 0.78rem; color: #047857;">Munaqosah Tuntas Hafalan Juz (Juz 30, 29, 28, dst.)</span>
                        </div>
                    </label>

                    <label id="label_tilawati" style="border: 2px solid #e2e8f0; background: #ffffff; border-radius: 14px; padding: 14px 18px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: all 0.2s;">
                        <input type="radio" name="type" value="tilawati" onchange="toggleTypeStyle('tilawati')" style="width: 18px; height: 18px; accent-color: #d97706;">
                        <div>
                            <strong style="color: #92400e; font-size: 0.95rem; display: block;"><i class="fas fa-book-reader"></i> Tilawati Qur'an</strong>
                            <span style="font-size: 0.78rem; color: #b45309;">Munaqosah Kenaikan Jilid Tilawati (Jilid 1 - 6 / Al-Qur'an)</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 2. Pilih Siswa -->
            <div style="margin-bottom: 22px;">
                <label style="display: block; font-weight: 800; font-size: 0.9rem; color: #0f172a; margin-bottom: 8px;">
                    2. Pilih Siswa Peserta Munaqosah <span style="color: #ef4444;">*</span>
                </label>
                
                <div style="position: relative; margin-bottom: 8px;">
                    <input type="text" id="student_filter" placeholder="Ketik nama atau kelas siswa untuk menyaring..." onkeyup="filterStudents()" 
                           style="width: 100%; padding: 10px 14px 10px 36px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; background: #f8fafc;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 13px; color: #94a3b8; font-size: 0.85rem;"></i>
                </div>

                <select name="student_id" id="student_picker" required size="5" style="width: 100%; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; padding: 8px;">
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">{{ $st->name }} — Kelas {{ $st->class }} (NIS: {{ $st->nis ?? '-' }})</option>
                    @endforeach
                </select>
                <small style="color: #64748b; font-size: 0.78rem; display: block; margin-top: 4px;">
                    Klik nama siswa pada kotak di atas.
                </small>
            </div>

            <!-- 3. Jenjang Saat Ini & Target Lanjutan -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 22px;">
                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.9rem; color: #0f172a; margin-bottom: 8px;">
                        3. Jenjang yang Dituntaskan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="current_level" id="current_level" required placeholder="Contoh: Juz 30" 
                           style="width: 100%; padding: 11px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; font-weight: 600; color: #0f172a;">
                    <small style="color: #64748b; font-size: 0.76rem; display: block; margin-top: 4px;">
                        Juz atau Jilid yang telah diselesaikan.
                    </small>
                </div>

                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.9rem; color: #0f172a; margin-bottom: 8px;">
                        4. Target Jenjang Lanjutan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="target_level" id="target_level" required placeholder="Contoh: Juz 29" 
                           style="width: 100%; padding: 11px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; font-weight: 600; color: #0f172a;">
                    <small style="color: #64748b; font-size: 0.76rem; display: block; margin-top: 4px;">
                        Jenjang yang direkomendasikan setelah lulus.
                    </small>
                </div>
            </div>

            <!-- 4. Kelompok Saat Ini (Opsional) -->
            <div style="margin-bottom: 22px;">
                <label style="display: block; font-weight: 800; font-size: 0.9rem; color: #0f172a; margin-bottom: 8px;">
                    5. Kelompok Mentoring Asal Siswa (Opsional)
                </label>
                <select name="from_group_id" style="width: 100%; padding: 11px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; color: #334155; background: #f8fafc;">
                    <option value="">-- Pilih Kelompok Asal (Jika Ada) --</option>
                    @foreach($groups as $grp)
                        <option value="{{ $grp->id }}">[{{ strtoupper($grp->type) }}] {{ $grp->name }} (Mentor: {{ $grp->mentor_name ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <!-- 5. Catatan Kesiapan Guru Pengusul -->
            <div style="margin-bottom: 28px;">
                <label style="display: block; font-weight: 800; font-size: 0.9rem; color: #0f172a; margin-bottom: 8px;">
                    6. Catatan Kesiapan / Rekomendasi Guru Pembimbing
                </label>
                <textarea name="proposer_notes" rows="3" placeholder="Contoh: Ananda telah menyelesaikan hafalan Juz 30 dengan tasmi' 1 majelis di hadapan guru pembimbing dengan predikat lancar dan mutqin." 
                          style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; line-height: 1.5;"></textarea>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                <a href="{{ route('munaqosah.index') }}" style="padding: 11px 22px; border-radius: 12px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    Batal
                </a>
                <button type="submit" style="padding: 11px 28px; border-radius: 12px; border: none; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; font-weight: 800; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 15px rgba(5, 150, 105, 0.35); display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-paper-plane"></i> Kirim Pengajuan Munaqosah
                </button>
            </div>
        </form>

    </div>

</div>

<script>
function toggleTypeStyle(type) {
    const labelTahfidz = document.getElementById('label_tahfidz');
    const labelTilawati = document.getElementById('label_tilawati');
    const cur = document.getElementById('current_level');
    const tgt = document.getElementById('target_level');

    if (type === 'tahfidz') {
        labelTahfidz.style.border = '2px solid #10b981';
        labelTahfidz.style.background = '#f0fdf4';
        labelTilawati.style.border = '2px solid #e2e8f0';
        labelTilawati.style.background = '#ffffff';
        cur.placeholder = 'Contoh: Juz 30';
        tgt.placeholder = 'Contoh: Juz 29';
    } else {
        labelTilawati.style.border = '2px solid #f59e0b';
        labelTilawati.style.background = '#fffbeb';
        labelTahfidz.style.border = '2px solid #e2e8f0';
        labelTahfidz.style.background = '#ffffff';
        cur.placeholder = 'Contoh: Jilid 2';
        tgt.placeholder = 'Contoh: Jilid 3';
    }
}

function filterStudents() {
    const query = document.getElementById('student_filter').value.toLowerCase();
    const select = document.getElementById('student_picker');
    for (let opt of select.options) {
        const text = opt.text.toLowerCase();
        opt.style.display = text.includes(query) ? '' : 'none';
    }
}
</script>
@endsection
