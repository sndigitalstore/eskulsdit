@extends('layouts.app')

@section('title', 'Buat Kelompok ' . $typeLabel)
@section('page-title', 'Buat Kelompok ' . $typeLabel)

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 15px;">
        <a href="{{ $type === 'tilawati' ? route('tilawati-groups.index') : route('tahfidz-groups.index') }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kelompok {{ $typeLabel }}
        </a>
    </div>

@push('styles')
<style>
    .mentoring-form-card {
        padding: 28px;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        background: #ffffff;
    }
    .form-grid-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }
    .form-action-footer {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }
    @media (max-width: 640px) {
        .mentoring-form-card {
            padding: 20px 16px;
            border-radius: 14px;
        }
        .form-grid-2col {
            grid-template-columns: 1fr;
            gap: 14px;
        }
        .form-action-footer {
            flex-direction: column-reverse;
        }
        .form-action-footer .btn-action-header {
            width: 100%;
            text-align: center;
            justify-content: center;
        }
    }
</style>
@endpush

    <div class="card mentoring-form-card">
        <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px; padding-bottom: 18px; border-bottom: 1px solid #f1f5f9;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: {{ $type === 'tilawati' ? '#ecfdf5' : '#f0f9ff' }}; color: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                <i class="fas {{ $type === 'tilawati' ? 'fa-book-reader' : 'fa-quran' }}"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b;">
                    Formulir Kelompok {{ $typeLabel }} Baru
                </h3>
                <p style="margin: 3px 0 0 0; color: #64748b; font-size: 0.88rem;">
                    Kelompok ini dapat diisi siswa dari satu kelas maupun gabungan lintas kelas.
                </p>
            </div>
        </div>

        <form action="{{ $type === 'tilawati' ? route('tilawati-groups.store') : route('tahfidz-groups.store') }}" method="POST">
            @csrf

            <!-- TAHUN PELAJARAN -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                    Tahun Pelajaran <span style="color: #ef4444;">*</span>
                </label>
                <select name="academic_year_id" class="form-control" required style="border-radius: 10px; height: 42px;">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ ($activeYear && $activeYear->id == $year->id) ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- NAMA KELOMPOK -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                    Nama Kelompok <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="name" class="form-control" placeholder="{{ $type === 'tilawati' ? 'Contoh: Tilawati 1A - Jilid 2 (Ust. Ahmad)' : 'Contoh: Tahfidz Juz 30 - Putri (Usth. Maryam)' }}" value="{{ old('name') }}" required style="border-radius: 10px; height: 42px;">
                <small style="color: #64748b; font-size: 0.8rem; margin-top: 4px; display: block;">Beri nama kelompok yang jelas agar mudah dikenali saat pengelompokan siswa.</small>
            </div>

            <!-- PEMBIMBING / MENTOR -->
            <div class="form-grid-2col">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                        Nama Pembimbing / Mentor <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" id="mentorNameInput" name="mentor_name" class="form-control" placeholder="Contoh: Ustadz Ahmad Farhan" value="{{ old('mentor_name') }}" required style="border-radius: 10px; height: 42px;">
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                        Pautkan dengan Akun Guru (Opsional)
                    </label>
                    <select name="mentor_user_id" id="mentorUserSelect" class="form-control" onchange="syncMentorName(this)" style="border-radius: 10px; height: 42px;">
                        <option value="">-- Pilih Akun Guru Terdaftar (Jika Ada) --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" data-name="{{ $teacher->name }}">
                                {{ $teacher->name }} ({{ $teacher->role }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- TARGET / JENJANG & JADWAL -->
            <div class="form-grid-2col">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                        Target / Level Capaian
                    </label>
                    <input type="text" name="target_level" list="targetSuggestions" class="form-control" placeholder="{{ $type === 'tilawati' ? 'Pilih atau ketik jilid (misal: Jilid 3)' : 'Pilih atau ketik juz/surat (misal: Juz 30)' }}" value="{{ old('target_level') }}" style="border-radius: 10px; height: 42px;">
                    <datalist id="targetSuggestions">
                        @if($type === 'tilawati')
                            <option value="Jilid 1">
                            <option value="Jilid 2">
                            <option value="Jilid 3">
                            <option value="Jilid 4">
                            <option value="Jilid 5">
                            <option value="Jilid 6">
                            <option value="Al-Qur'an">
                            <option value="Ghorib & Tajwid">
                        @else
                            <option value="Juz 30 (An-Naba s.d An-Nas)">
                            <option value="Juz 29 (Al-Mulk s.d Al-Mursalat)">
                            <option value="Juz 28 (Al-Mujadilah s.d At-Tahrim)">
                            <option value="Juz 27 (Az-Zariyat s.d Al-Hadid)">
                            <option value="Juz 26 (Al-Ahqaf s.d Az-Zariyat)">
                            <option value="Juz 1 (Al-Fatihah & Al-Baqarah)">
                            <option value="Juz 2">
                            <option value="Juz 3">
                            <option value="Surat Pilihan">
                        @endif
                    </datalist>
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                        Jadwal & Tempat Mentoring (Opsional)
                    </label>
                    <input type="text" name="schedule" class="form-control" placeholder="Contoh: Senin - Kamis, 07:30 - 08:30 (Masjid Lt. 1)" value="{{ old('schedule') }}" style="border-radius: 10px; height: 42px;">
                </div>
            </div>

            <!-- KETERANGAN / CATATAN -->
            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                    Keterangan Tambahan (Opsional)
                </label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Catatan kelompok, target bulanan, atau instruksi khusus..." style="border-radius: 10px;">{{ old('notes') }}</textarea>
            </div>

            <div class="form-action-footer">
                <a href="{{ $type === 'tilawati' ? route('tilawati-groups.index') : route('tahfidz-groups.index') }}" class="btn-action-header btn-dark" style="background: #e2e8f0; color: #334155; padding: 10px 20px; border-radius: 10px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                    Batal
                </a>
                <button type="submit" class="btn-action-header btn-green" style="background: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; color: #ffffff; padding: 10px 24px; font-weight: 700; border-radius: 10px; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fas fa-save"></i> Simpan & Lanjut Isi Anggota Siswa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function syncMentorName(selectElem) {
        const selectedOption = selectElem.options[selectElem.selectedIndex];
        const teacherName = selectedOption.getAttribute('data-name');
        const mentorInput = document.getElementById('mentorNameInput');
        if (teacherName && (!mentorInput.value || mentorInput.value.trim() === '')) {
            mentorInput.value = teacherName;
        }
    }
</script>
@endsection
