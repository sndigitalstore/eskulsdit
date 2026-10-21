@extends('layouts.app')

@section('title', 'Catat Kasus Pembinaan Siswa – SMILE Karakter')
@section('page-title', 'Catat Kasus Pembinaan')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus-circle" style="color: #ef4444;"></i> Form Pencatatan Kasus & Pembinaan
            </h3>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.88rem;">
                Catat kronologi kejadian dan langkah pembinaan akhlak siswa secara objektif dan mendidik.
            </p>
        </div>
        <a href="{{ route('character-cases.index') }}" class="btn-action-header btn-dark" style="text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px;">
            <strong style="display: block; margin-bottom: 4px;">Terdapat kesalahan pengisian data:</strong>
            <ul style="margin: 0; padding-left: 20px; font-size: 0.88rem;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="padding: 28px 32px; border-top: 4px solid #ef4444;">
        <form action="{{ route('character-cases.store') }}" method="POST">
            @csrf

            <!-- Section 1: Identitas Siswa & Waktu -->
            <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 16px 0; font-size: 0.95rem; font-weight: 800; color: #334155; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                    1. Identitas Siswa & Waktu Kejadian
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Pilih Siswa <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="student_id" id="student_id" class="form-control" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($students as $st)
                                <option value="{{ $st->id }}" {{ (old('student_id', $selectedStudent->id ?? '') == $st->id) ? 'selected' : '' }}>
                                    {{ $st->name }} (Kelas {{ $st->class }}) - NIS: {{ $st->nis ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Tanggal Kejadian <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Lokasi Kejadian
                        </label>
                        <input type="text" name="location" class="form-control" placeholder="Contoh: Di dalam kelas, Masjid, Lapangan, dll" value="{{ old('location') }}">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Guru / Pembina Pelapor
                        </label>
                        <input type="text" name="reported_by" class="form-control" value="{{ old('reported_by', Auth::user()->name) }}" placeholder="Nama guru / ustadz">
                    </div>
                </div>
            </div>

            <!-- Section 2: Klasifikasi & Kronologi Kasus -->
            <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 16px 0; font-size: 0.95rem; font-weight: 800; color: #334155; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                    2. Uraian & Bobot Kasus
                </h4>

                <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Kategori Kasus <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="category" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Tingkat Keparahan <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="severity" id="severity" class="form-control" onchange="autoSuggestPoint(this.value)" required>
                            <option value="ringan" {{ old('severity', 'ringan') == 'ringan' ? 'selected' : '' }}>Ringan</option>
                            <option value="sedang" {{ old('severity') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="berat" {{ old('severity') == 'berat' ? 'selected' : '' }}>Berat</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Poin Pembinaan <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="number" name="point" id="point" class="form-control" value="{{ old('point', 5) }}" min="0" max="100" required>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                        Judul / Ringkasan Kasus <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Bercanda berlebihan saat sholat berjamaah" value="{{ old('title') }}" required>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                        Kronologi / Detail Kejadian
                    </label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Jelaskan apa yang terjadi secara objektif...">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Section 3: Langkah Tindakan & Status -->
            <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 16px 0; font-size: 0.95rem; font-weight: 800; color: #334155; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                    3. Tindakan Pembinaan & Evaluasi
                </h4>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Tindakan Langsung Guru / Pembina
                        </label>
                        <textarea name="action_taken" rows="2" class="form-control" placeholder="Contoh: Dinasehati secara empatik, diminta mengulang wudhu, dll">{{ old('action_taken') }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Komitmen Siswa / Rencana Tindak Lanjut
                        </label>
                        <textarea name="follow_up" rows="2" class="form-control" placeholder="Contoh: Menulis surat komitmen, meminta maaf kepada teman, dll">{{ old('follow_up') }}</textarea>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: center;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Status Pembinaan <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="status" class="form-control" required>
                            <option value="dalam_pembinaan" {{ old('status', 'dalam_pembinaan') == 'dalam_pembinaan' ? 'selected' : '' }}>Dalam Pembinaan</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai Dibina</option>
                            <option value="panggilan_ortu" {{ old('status') == 'panggilan_ortu' ? 'selected' : '' }}>Panggilan Orang Tua</option>
                        </select>
                    </div>

                    <div style="padding-top: 24px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 600; color: #334155; font-size: 0.9rem;">
                            <input type="checkbox" name="parent_notified" value="1" {{ old('parent_notified') ? 'checked' : '' }} style="width: 18px; height: 18px;">
                            <span>Sudah diberitahukan ke Orang Tua / Wali Siswa</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                <a href="{{ route('character-cases.index') }}" class="btn-action-header btn-dark" style="text-decoration: none; padding: 10px 20px;">
                    Batal
                </a>
                <button type="submit" class="btn-action-header btn-red" style="padding: 10px 24px; font-weight: 700; background: #ef4444; font-size: 0.95rem;">
                    <i class="fas fa-save"></i> Simpan Catatan Kasus
                </button>
            </div>

        </form>
    </div>

</div>

<script>
    function autoSuggestPoint(severity) {
        var pointInput = document.getElementById('point');
        if (severity === 'ringan') {
            pointInput.value = 5;
        } else if (severity === 'sedang') {
            pointInput.value = 15;
        } else if (severity === 'berat') {
            pointInput.value = 30;
        }
    }
</script>
@endsection
