@extends('layouts.app')

@section('title', 'Edit Catatan Apresiasi Santri – SMILE KARAKTER')
@section('page-title', 'Edit Catatan Apresiasi')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-edit" style="color: #e67e22;"></i> Edit Catatan Apresiasi Santri
            </h3>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.88rem;">
                Perbarui rincian apresiasi santri <strong>{{ $appreciation->student->name ?? '-' }}</strong>.
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

    <div class="card" style="padding: 28px 32px; border-top: 4px solid #e67e22;">
        <form action="{{ route('character-appreciations.update', $appreciation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Section 1: Identitas Santri & Waktu -->
            <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 16px 0; font-size: 0.95rem; font-weight: 800; color: #334155; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                    1. Penerima Apresiasi & Waktu
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Nama Santri
                        </label>
                        <input type="text" class="form-control" value="{{ $appreciation->student->name ?? '-' }} (Kelas {{ $appreciation->student->class ?? '-' }})" disabled style="background: #f8fafc; font-weight: 700;">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Tanggal Apresiasi <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', $appreciation->date ? \Carbon\Carbon::parse($appreciation->date)->format('Y-m-d') : '') }}" required>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                        Guru / Pembina Pemberi Apresiasi
                    </label>
                    <input type="text" name="given_by" class="form-control" value="{{ old('given_by', $appreciation->given_by) }}">
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
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('category', $appreciation->category) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                            Poin Kebaikan (+) <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="number" name="point" class="form-control" value="{{ old('point', $appreciation->point) }}" min="1" max="100" required>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                        Judul / Bentuk Perilaku Terpuji <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $appreciation->title) }}" required>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                        Bentuk Apresiasi / Reward Langsung (Opsional)
                    </label>
                    <input type="text" name="reward" class="form-control" value="{{ old('reward', $appreciation->reward) }}" placeholder="Contoh: Piagam, Pujian, Hadiah">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
                        Detail Aksi Kebaikan
                    </label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $appreciation->description) }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                <a href="{{ route('character-appreciations.index') }}" class="btn-action-header btn-dark" style="text-decoration: none; padding: 10px 20px;">
                    Batal
                </a>
                <button type="submit" class="btn-action-header btn-orange" style="padding: 10px 24px; font-weight: 700; font-size: 0.95rem;">
                    <i class="fas fa-save"></i> Perbarui Catatan Apresiasi
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
