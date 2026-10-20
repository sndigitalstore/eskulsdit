@extends('layouts.app')

@section('title', 'Edit Catatan Kasus Santri – SMILE Karakter')
@section('page-title', 'Edit Catatan Kasus')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-edit" style="color: #e67e22;"></i> Edit Catatan Kasus & Pembinaan
            </h3>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.88rem;">
                Perbarui tindak lanjut atau status penyelesaian kasus santri <strong>{{ $case->student->name ?? '-' }}</strong>.
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

    <div class="card" style="padding: 28px 32px; border-top: 4px solid #e67e22;">
        <form action="{{ route('character-cases.update', $case->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Section 1: Santri Info & Waktu -->
            <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 16px 0; font-size: 0.95rem; font-weight: 800; color: #334155; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                    1. Identitas Santri & Waktu Kejadian
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Nama Santri
                        </label>
                        <input type="text" class="form-control" value="{{ $case->student->name ?? '-' }} (Kelas {{ $case->student->class ?? '-' }})" disabled style="background: #f8fafc; font-weight: 700;">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Tanggal Kejadian <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', $case->date ? \Carbon\Carbon::parse($case->date)->format('Y-m-d') : '') }}" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Lokasi Kejadian
                        </label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $case->location) }}" placeholder="Contoh: Di kelas, Lapangan, dll">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Guru / Pembina Pelapor
                        </label>
                        <input type="text" name="reported_by" class="form-control" value="{{ old('reported_by', $case->reported_by) }}">
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
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('category', $case->category) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Tingkat Keparahan <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="severity" class="form-control" required>
                            <option value="ringan" {{ old('severity', $case->severity) == 'ringan' ? 'selected' : '' }}>Ringan</option>
                            <option value="sedang" {{ old('severity', $case->severity) == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="berat" {{ old('severity', $case->severity) == 'berat' ? 'selected' : '' }}>Berat</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Poin Pembinaan <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="number" name="point" class="form-control" value="{{ old('point', $case->point) }}" min="0" max="100" required>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                        Judul / Ringkasan Kasus <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $case->title) }}" required>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                        Kronologi / Detail Kejadian
                    </label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $case->description) }}</textarea>
                </div>
            </div>

            <!-- Section 3: Tindakan & Status -->
            <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 16px 0; font-size: 0.95rem; font-weight: 800; color: #334155; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                    3. Tindakan Pembinaan & Evaluasi
                </h4>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Tindakan Langsung Guru / Pembina
                        </label>
                        <textarea name="action_taken" rows="2" class="form-control">{{ old('action_taken', $case->action_taken) }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Komitmen Siswa / Rencana Tindak Lanjut
                        </label>
                        <textarea name="follow_up" rows="2" class="form-control">{{ old('follow_up', $case->follow_up) }}</textarea>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: center;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Status Pembinaan <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="status" class="form-control" required>
                            <option value="dalam_pembinaan" {{ old('status', $case->status) == 'dalam_pembinaan' ? 'selected' : '' }}>Dalam Pembinaan</option>
                            <option value="selesai" {{ old('status', $case->status) == 'selesai' ? 'selected' : '' }}>Selesai Dibina</option>
                            <option value="panggilan_ortu" {{ old('status', $case->status) == 'panggilan_ortu' ? 'selected' : '' }}>Panggilan Orang Tua</option>
                        </select>
                    </div>

                    <div style="padding-top: 24px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 600; color: #334155; font-size: 0.9rem;">
                            <input type="checkbox" name="parent_notified" value="1" {{ old('parent_notified', $case->parent_notified) ? 'checked' : '' }} style="width: 18px; height: 18px;">
                            <span>Sudah diberitahukan ke Orang Tua / Wali Santri</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                <a href="{{ route('character-cases.index') }}" class="btn-action-header btn-dark" style="text-decoration: none; padding: 10px 20px;">
                    Batal
                </a>
                <button type="submit" class="btn-action-header btn-orange" style="padding: 10px 24px; font-weight: 700; font-size: 0.95rem;">
                    <i class="fas fa-save"></i> Perbarui Catatan Kasus
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
