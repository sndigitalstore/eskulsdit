@extends('layouts.app')

@section('title', 'Beri Apresiasi Santri – SMILE KARAKTER')
@section('page-title', 'Beri Apresiasi Santri')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus-circle" style="color: #10b981;"></i> Form Pemberian Apresiasi & Kebaikan
            </h3>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.88rem;">
                Apresiasi perilaku mulia, inisiatif terpuji, dan akhlak unggul santri berlandaskan nilai <strong>SMILE</strong>.
            </p>
        </div>
        <a href="{{ route('character-appreciations.index') }}" class="btn-action-header btn-dark" style="text-decoration: none;">
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

    <div class="card" style="padding: 28px 32px; border-top: 4px solid #10b981;">
        <form action="{{ route('character-appreciations.store') }}" method="POST">
            @csrf

            <!-- Section 1: Identitas Santri & Waktu -->
            <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 16px 0; font-size: 0.95rem; font-weight: 800; color: #334155; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                    1. Penerima Apresiasi & Waktu
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Pilih Santri <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="student_id" class="form-control" required>
                            <option value="">-- Pilih Santri --</option>
                            @foreach($students as $st)
                                <option value="{{ $st->id }}" {{ (old('student_id', $selectedStudent->id ?? '') == $st->id) ? 'selected' : '' }}>
                                    {{ $st->name }} (Kelas {{ $st->class }}) - NIS: {{ $st->nis ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Tanggal Apresiasi <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                        Guru / Pembina Pemberi Apresiasi
                    </label>
                    <input type="text" name="given_by" class="form-control" value="{{ old('given_by', Auth::user()->name) }}" placeholder="Nama guru / ustadz / musyrif">
                </div>
            </div>

            <!-- Section 2: Nilai SMILE & Uraian Kebaikan -->
            <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 16px 0; font-size: 0.95rem; font-weight: 800; color: #334155; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                    2. Nilai Karakter SMILE & Bentuk Kebaikan
                </h4>

                <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Kategori Nilai SMILE <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="category" class="form-control" required>
                            <option value="">-- Pilih Nilai Karakter --</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Poin Kebaikan (+) <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="number" name="point" class="form-control" value="{{ old('point', 10) }}" min="1" max="100" required>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                        Judul / Bentuk Perilaku Terpuji <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Menemukan dan mengembalikan uang teman yang terjatuh di masjid" value="{{ old('title') }}" required>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                        Bentuk Apresiasi / Reward Langsung (Opsional)
                    </label>
                    <input type="text" name="reward" class="form-control" placeholder="Contoh: Pujian di depan kelas, Pin Bintang Karakter, Hadiah Alat Tulis" value="{{ old('reward') }}">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                        Detail Aksi Kebaikan
                    </label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Ceritakan bagaimana santri menunjukkan perilaku terpuji tersebut...">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                <a href="{{ route('character-appreciations.index') }}" class="btn-action-header btn-dark" style="text-decoration: none; padding: 10px 20px;">
                    Batal
                </a>
                <button type="submit" class="btn-action-header btn-green" style="padding: 10px 24px; font-weight: 700; background: #10b981; font-size: 0.95rem;">
                    <i class="fas fa-save"></i> Simpan Catatan Apresiasi
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
