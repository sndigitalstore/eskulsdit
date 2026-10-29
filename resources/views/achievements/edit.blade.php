@extends('layouts.app')

@section('title', 'Edit Prestasi')
@section('page-title', 'Edit Data Prestasi')

@push('styles')
<style>
    .cat-selector-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }
    .cat-card-option {
        cursor: pointer;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 14px;
        padding: 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        transition: all 0.2s ease;
    }
    .cat-card-option.is-selected {
        border-color: #059669;
        background: #ecfdf5;
    }
    .cat-card-option.is-selected-school {
        border-color: #f59e0b;
        background: #fffbeb;
    }
</style>
@endpush

@section('content')
<div class="card" style="max-width: 820px; margin: 0 auto; border-radius: 16px; padding: 24px;">
    <div style="margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px;">
        <h3 style="margin: 0 0 6px 0; font-size: 1.25rem; font-weight: 800; color: #0f172a;">Edit Data Prestasi</h3>
        <p style="margin: 0; color: #64748b; font-size: 0.88rem;">
            @if($achievement->isSchool())
                Prestasi Lembaga: <strong style="color: #92400e;">{{ $achievement->school_name ?: 'SDIT An Nadzir' }}</strong>
            @else
                Siswa: <strong style="color: #064e3b;">{{ $achievement->student->name ?? 'Siswa Dihapus' }}</strong> (Kelas {{ $achievement->student->class ?? '-' }})
            @endif
        </p>
    </div>

    <form action="{{ route('achievements.update', $achievement->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- 1. KATEGORI PRESTASI --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #0f172a; font-size: 0.88rem; text-transform: uppercase;">
                <i class="fas fa-layer-group" style="color: #059669;"></i> Kategori Prestasi
            </label>
            
            <div class="cat-selector-grid">
                <label class="cat-card-option {{ !$achievement->isSchool() ? 'is-selected' : '' }}" id="cardOptionStudent" onclick="selectEditCategory('student')">
                    <input type="radio" name="category" value="student" id="catRadioStudent" 
                           {{ !$achievement->isSchool() ? 'checked' : '' }} style="margin-top: 4px; accent-color: #059669;">
                    <div>
                        <strong style="color: #064e3b; display: block; font-size: 0.95rem;">🎓 Prestasi Siswa / Individu</strong>
                        <span style="font-size: 0.76rem; color: #047857;">Atas nama perseorangan siswa</span>
                    </div>
                </label>

                <label class="cat-card-option {{ $achievement->isSchool() ? 'is-selected-school' : '' }}" id="cardOptionSchool" onclick="selectEditCategory('school')">
                    <input type="radio" name="category" value="school" id="catRadioSchool" 
                           {{ $achievement->isSchool() ? 'checked' : '' }} style="margin-top: 4px; accent-color: #f59e0b;">
                    <div>
                        <strong style="color: #92400e; display: block; font-size: 0.95rem;">🏆 Prestasi Sekolah / Juara Umum</strong>
                        <span style="font-size: 0.76rem; color: #b45309;">Juara Umum, Akreditasi, Lembaga</span>
                    </div>
                </label>
            </div>
        </div>

        {{-- NAMA LEMBAGA (JIKA SEKOLAH) --}}
        <div id="schoolNameEditGroup" style="{{ $achievement->isSchool() ? 'display: block;' : 'display: none;' }}; margin-bottom: 20px;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #34495e;">
                    <i class="fas fa-school" style="color: #059669;"></i> Atas Nama Lembaga / Kontingen
                </label>
                <input type="text" name="school_name" class="form-control" 
                       value="{{ old('school_name', $achievement->school_name ?: 'SDIT An Nadzir') }}" placeholder="Contoh: SDIT An Nadzir">
            </div>
        </div>

        <input type="hidden" name="student_id" value="{{ $achievement->student_id }}">

        {{-- 2. TAHUN PELAJARAN & SEMESTER --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; background: #fffcf0; padding: 15px; border-radius: 12px; border: 1px solid #f9e79f;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #7d6608;">Tahun Pelajaran</label>
                <select name="academic_year_id" class="form-control" required>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $achievement->academic_year_id == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #7d6608;">Semester</label>
                <select name="semester" class="form-control" required>
                    <option value="1" {{ $achievement->semester == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                    <option value="2" {{ $achievement->semester == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                </select>
            </div>
        </div>

        {{-- 3. NAMA PRESTASI --}}
        <div class="form-group" style="margin-bottom: 18px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #1e293b;">
                <i class="fas fa-medal" style="color: #f59e0b;"></i> Nama Prestasi / Kejuaraan <span style="color: #dc2626;">*</span>
            </label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $achievement->name) }}" required>
        </div>

        {{-- 4. TINGKAT & TANGGAL --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 18px;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #1e293b;">Tingkat</label>
                <select name="level" class="form-control" required>
                    <option value="Sekolah" {{ $achievement->level == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                    <option value="Kecamatan" {{ $achievement->level == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                    <option value="Kabupaten/Kota" {{ $achievement->level == 'Kabupaten/Kota' ? 'selected' : '' }}>Kabupaten/Kota</option>
                    <option value="Provinsi" {{ $achievement->level == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                    <option value="Nasional" {{ $achievement->level == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                    <option value="Internasional" {{ $achievement->level == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                    <option value="Open Turnamen / Event Lainnya" {{ $achievement->level == 'Open Turnamen / Event Lainnya' ? 'selected' : '' }}>Open Turnamen / Event Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #1e293b;">Tanggal Perolehan</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', $achievement->date) }}" required>
            </div>
        </div>

        {{-- 5. PENYELENGGARA --}}
        <div class="form-group" style="margin-bottom: 18px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #1e293b;">Penyelenggara (Opsional)</label>
            <input type="text" name="organizer" class="form-control" value="{{ old('organizer', $achievement->organizer) }}" placeholder="Contoh: Dinas Pendidikan Kota">
        </div>

        {{-- 6. KETERANGAN --}}
        <div class="form-group" style="margin-bottom: 24px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #1e293b;">Keterangan Tambahan (Opsional)</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $achievement->description) }}</textarea>
        </div>

        {{-- 7. TOMBOL AKSI --}}
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                <i class="fas fa-save"></i> Perbarui Prestasi
            </button>
            <a href="{{ $achievement->student_id ? route('students.show', $achievement->student_id) : route('achievements.index') }}" 
               class="btn-submit" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; text-decoration: none; text-align: center; display: inline-flex; align-items: center;">
                Batal
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function selectEditCategory(category) {
        const catRadioStudent = document.getElementById('catRadioStudent');
        const catRadioSchool = document.getElementById('catRadioSchool');
        const cardOptionStudent = document.getElementById('cardOptionStudent');
        const cardOptionSchool = document.getElementById('cardOptionSchool');
        const schoolGroup = document.getElementById('schoolNameEditGroup');

        if (category === 'school') {
            if (catRadioSchool) catRadioSchool.checked = true;
            if (cardOptionSchool) cardOptionSchool.className = 'cat-card-option is-selected-school';
            if (cardOptionStudent) cardOptionStudent.className = 'cat-card-option';
            if (schoolGroup) schoolGroup.style.display = 'block';
        } else {
            if (catRadioStudent) catRadioStudent.checked = true;
            if (cardOptionStudent) cardOptionStudent.className = 'cat-card-option is-selected';
            if (cardOptionSchool) cardOptionSchool.className = 'cat-card-option';
            if (schoolGroup) schoolGroup.style.display = 'none';
        }
    }
</script>
@endpush
@endsection
