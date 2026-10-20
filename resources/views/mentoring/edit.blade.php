@extends('layouts.app')

@section('title', 'Edit Kelompok ' . $typeLabel)
@section('page-title', 'Edit Kelompok ' . $typeLabel)

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 15px;">
        <a href="{{ $type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Detail Kelompok
        </a>
    </div>

    <div class="card" style="padding: 28px;">
        <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px; padding-bottom: 18px; border-bottom: 1px solid #f1f5f9;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: {{ $type === 'tilawati' ? '#ecfdf5' : '#f0f9ff' }}; color: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                <i class="fas {{ $type === 'tilawati' ? 'fa-book-reader' : 'fa-quran' }}"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b;">
                    Edit Data Kelompok {{ $typeLabel }}
                </h3>
                <p style="margin: 3px 0 0 0; color: #64748b; font-size: 0.88rem;">
                    Perbarui informasi kelompok, pembimbing, atau target capaian.
                </p>
            </div>
        </div>

        <form action="{{ $type === 'tilawati' ? route('tilawati-groups.update', $group->id) : route('tahfidz-groups.update', $group->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if(!($isAdmin ?? true))
                <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 0.88rem; color: #1e40af;">
                    <i class="fas fa-info-circle"></i> Anda sedang memperbarui informasi kelompok bimbingan Anda (Target jenjang, jadwal, dan catatan). Nama kelompok dan penugasan mentor diatur oleh Administrator.
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #64748b;">Nama Kelompok</label>
                        <input type="text" class="form-control" value="{{ $group->name }}" disabled style="background: #f8fafc; color: #334155;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #64748b;">Tahun Pelajaran</label>
                        <input type="text" class="form-control" value="{{ $group->academicYear->name ?? '-' }}" disabled style="background: #f8fafc; color: #334155;">
                    </div>
                </div>
            @else
                <!-- TAHUN PELAJARAN -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                        Tahun Pelajaran <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="academic_year_id" class="form-control" required>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ (old('academic_year_id', $group->academic_year_id) == $year->id) ? 'selected' : '' }}>
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
                    <input type="text" name="name" class="form-control" value="{{ old('name', $group->name) }}" required>
                </div>

                <!-- PEMBIMBING / MENTOR -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                            Nama Pembimbing / Mentor <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="mentorNameInput" name="mentor_name" class="form-control" value="{{ old('mentor_name', $group->mentor_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                            Pautkan dengan Akun Guru (Opsional)
                        </label>
                        <select name="mentor_user_id" id="mentorUserSelect" class="form-control" onchange="syncMentorName(this)">
                            <option value="">-- Pilih Akun Guru Terdaftar (Jika Ada) --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" data-name="{{ $teacher->name }}" {{ (old('mentor_user_id', $group->mentor_user_id) == $teacher->id) ? 'selected' : '' }}>
                                    {{ $teacher->name }} ({{ $teacher->role }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <!-- TARGET / JENJANG & JADWAL -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                        Target / Level Capaian
                    </label>
                    <input type="text" name="target_level" list="targetSuggestions" class="form-control" value="{{ old('target_level', $group->target_level) }}">
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
                            <option value="Juz 30 (An-Nas s.d An-Naba)">
                            <option value="Juz 29 (Al-Mursalat s.d Al-Mulk)">
                            <option value="Juz 28">
                            <option value="Juz 1 (Al-Baqarah)">
                            <option value="Surat Pilihan">
                        @endif
                    </datalist>
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                        Jadwal & Tempat Mentoring (Opsional)
                    </label>
                    <input type="text" name="schedule" class="form-control" value="{{ old('schedule', $group->schedule) }}">
                </div>
            </div>

            <!-- KETERANGAN / CATATAN -->
            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155;">
                    Keterangan Tambahan (Opsional)
                </label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $group->notes) }}</textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="{{ $type === 'tilawati' ? route('tilawati-groups.show', $group->id) : route('tahfidz-groups.show', $group->id) }}" class="btn-action-header btn-dark" style="background: #e2e8f0; color: #334155; padding: 10px 20px;">
                    Batal
                </a>
                <button type="submit" class="btn-action-header btn-green" style="background: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; padding: 10px 24px; font-weight: 700;">
                    <i class="fas fa-save"></i> Perbarui Kelompok
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
