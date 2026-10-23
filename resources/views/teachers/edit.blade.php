@extends('layouts.app')

@section('title', 'Edit Akun & Penugasan Guru')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto; padding: 28px;">
    
    <div style="border-bottom: 2px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 24px;">
        <h3 style="margin: 0; color: #0f172a; font-size: 1.35rem; font-weight: 800; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-user-cog" style="color: #6366f1;"></i> Edit Akun & Penugasan Guru
        </h3>
        <p style="margin: 6px 0 0 0; color: #64748b; font-size: 0.9rem;">
            Atur peran dinamis untuk <strong>{{ $teacher->name }}</strong>. Satu akun dapat mengemban multi-peran (Wali Kelas, Eskul, Qur'an, dan Mapel) sekaligus.
        </p>
    </div>
    
    <form action="{{ route('teachers.update', $teacher->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- BAGIAN 1: IDENTITAS & LOGIN -->
        <div style="background: #f8fafc; padding: 18px 20px; border-radius: 14px; border: 1px solid #e2e8f0; margin-bottom: 24px;">
            <h4 style="margin: 0 0 14px 0; font-size: 0.95rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-id-card" style="color: #0284c7;"></i> 1. Identitas & Kredensial Login
            </h4>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 700; color: #475569; font-size: 0.85rem; margin-bottom: 6px; display: block;">Nama Lengkap Guru</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $teacher->name) }}" required placeholder="Contoh: Ust. Ahmad, S.Pd">
                    @error('name')<div style="color: red; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 700; color: #475569; font-size: 0.85rem; margin-bottom: 6px; display: block;">Nomor WhatsApp</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $teacher->phone) }}" placeholder="Contoh: 08123456789">
                    @error('phone')<div style="color: red; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 700; color: #475569; font-size: 0.85rem; margin-bottom: 6px; display: block;">Username Login</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username', $teacher->username) }}" required>
                    @error('username')<div style="color: red; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Ganti Password Toggle -->
            <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed #cbd5e1;">
                <span style="font-size: 0.8rem; color: #64748b; font-style: italic;">* Kosongkan bagian password jika tidak ingin mengubah password akun guru ini.</span>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-top: 10px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 700; color: #475569; font-size: 0.85rem; margin-bottom: 6px; display: block;">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control" minlength="6" placeholder="Minimal 6 karakter">
                        @error('password')<div style="color: red; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 700; color: #475569; font-size: 0.85rem; margin-bottom: 6px; display: block;">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" minlength="6" placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: PENUGASAN WALI KELAS & ESKUL -->
        <div style="background: #ffffff; padding: 18px 20px; border-radius: 14px; border: 1.5px solid #e0e7ff; margin-bottom: 24px;">
            <h4 style="margin: 0 0 14px 0; font-size: 0.95rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-chalkboard-teacher" style="color: #4f46e5;"></i> 2. Penugasan Wali Kelas & Pembina Eskul
            </h4>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 700; color: #475569; font-size: 0.85rem; margin-bottom: 6px; display: block;">
                        <i class="fas fa-school" style="color: #0284c7;"></i> Wali Kelas Binaan
                    </label>
                    <select name="homeroom_class" class="custom-select-modern" data-theme="blue" data-icon="fas fa-school" data-placeholder="-- Bukan Wali Kelas (None) --">
                        <option value="">-- Bukan Wali Kelas (None) --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->name }}" {{ old('homeroom_class', $teacher->homeroom_class) == $class->name ? 'selected' : '' }}>
                                Kelas {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    <small style="color: #64748b; margin-top: 4px; display: block; font-size: 0.78rem;">Pilih jika guru bertugas sebagai Wali Kelas.</small>
                    @error('homeroom_class')<div style="color: red; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 700; color: #475569; font-size: 0.85rem; margin-bottom: 6px; display: block;">
                        <i class="fas fa-basketball-ball" style="color: #059669;"></i> Eskul Binaan
                    </label>
                    <select name="eskul_id" class="custom-select-modern" data-theme="emerald" data-icon="fas fa-basketball-ball" data-placeholder="-- Tidak Mengampu Eskul (None) --" data-search-placeholder="Ketik nama eskul...">
                        <option value="">-- Tidak Mengampu Eskul (None) --</option>
                        @foreach($eskuls as $eskul)
                            <option value="{{ $eskul->id }}" data-badge="{{ $eskul->schedule ?: '' }}" {{ old('eskul_id', $teacher->eskul_id) == $eskul->id ? 'selected' : '' }}>
                                {{ $eskul->name }} {{ $eskul->schedule ? "({$eskul->schedule})" : '' }}
                            </option>
                        @endforeach
                    </select>
                    <small style="color: #64748b; margin-top: 4px; display: block; font-size: 0.78rem;">Pilih jika guru bertugas sebagai Pembina Eskul.</small>
                    @error('eskul_id')<div style="color: red; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- BAGIAN 3: PENUGASAN PEMBINA QUR'AN (TILAWATI & TAHFIDZ) -->
        <div style="background: #ffffff; padding: 18px 20px; border-radius: 14px; border: 1.5px solid #d1fae5; margin-bottom: 24px;">
            <h4 style="margin: 0 0 12px 0; font-size: 0.95rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-quran" style="color: #059669;"></i> 3. Penugasan Bimbingan Qur'an (Tilawati & Tahfidz)
            </h4>
            <p style="margin: 0 0 14px 0; color: #64748b; font-size: 0.82rem;">
                Centang kelompok halaqah yang dibimbing oleh guru ini. Guru akan otomatis mendapatkan akses input setoran dan presensi untuk kelompok terpilih.
            </p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
                <!-- Tilawati Groups -->
                <div style="background: #f0fdf4; padding: 12px 14px; border-radius: 10px; border: 1px solid #bbf7d0;">
                    <span style="font-weight: 800; color: #166534; font-size: 0.85rem; display: flex; align-items: center; gap: 6px; margin-bottom: 8px;">
                        <i class="fas fa-book-open"></i> Kelompok Tilawati:
                    </span>
                    @if($tilawatiGroups->isEmpty())
                        <div style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Belum ada data kelompok Tilawati.</div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 6px; max-height: 180px; overflow-y: auto; padding-right: 4px;">
                            @foreach($tilawatiGroups as $grp)
                                @php
                                    $isChecked = in_array($grp->id, old('mentoring_group_ids', $assignedGroupIds));
                                    $isOtherMentor = $grp->mentor_user_id && $grp->mentor_user_id !== $teacher->id;
                                @endphp
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.84rem; cursor: pointer; padding: 4px 6px; border-radius: 6px; background: white; border: 1px solid #e2e8f0;">
                                    <input type="checkbox" name="mentoring_group_ids[]" value="{{ $grp->id }}" {{ $isChecked ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #10b981;">
                                    <span style="font-weight: 700; color: #1e293b;">{{ $grp->name }}</span>
                                    @if($grp->target_level)
                                        <span style="font-size: 0.72rem; color: #047857; background: #dcfce7; padding: 1px 6px; border-radius: 4px;">{{ $grp->target_level }}</span>
                                    @endif
                                    @if($isOtherMentor)
                                        <span style="font-size: 0.7rem; color: #94a3b8; margin-left: auto;">(Saat ini: {{ $grp->mentor_name }})</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Tahfidz Groups -->
                <div style="background: #f0f9ff; padding: 12px 14px; border-radius: 10px; border: 1px solid #bae6fd;">
                    <span style="font-weight: 800; color: #0369a1; font-size: 0.85rem; display: flex; align-items: center; gap: 6px; margin-bottom: 8px;">
                        <i class="fas fa-star-and-crescent"></i> Kelompok Tahfidz:
                    </span>
                    @if($tahfidzGroups->isEmpty())
                        <div style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Belum ada data kelompok Tahfidz.</div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 6px; max-height: 180px; overflow-y: auto; padding-right: 4px;">
                            @foreach($tahfidzGroups as $grp)
                                @php
                                    $isChecked = in_array($grp->id, old('mentoring_group_ids', $assignedGroupIds));
                                    $isOtherMentor = $grp->mentor_user_id && $grp->mentor_user_id !== $teacher->id;
                                @endphp
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.84rem; cursor: pointer; padding: 4px 6px; border-radius: 6px; background: white; border: 1px solid #e2e8f0;">
                                    <input type="checkbox" name="mentoring_group_ids[]" value="{{ $grp->id }}" {{ $isChecked ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #0284c7;">
                                    <span style="font-weight: 700; color: #1e293b;">{{ $grp->name }}</span>
                                    @if($grp->target_level)
                                        <span style="font-size: 0.72rem; color: #0284c7; background: #e0f2fe; padding: 1px 6px; border-radius: 4px;">{{ $grp->target_level }}</span>
                                    @endif
                                    @if($isOtherMentor)
                                        <span style="font-size: 0.7rem; color: #94a3b8; margin-left: auto;">(Saat ini: {{ $grp->mentor_name }})</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- BAGIAN 4: PENUGASAN GURU MATA PELAJARAN (MAPEL) -->
        <div style="background: #ffffff; padding: 18px 20px; border-radius: 14px; border: 1.5px solid #ede9fe; margin-bottom: 26px;">
            <h4 style="margin: 0 0 12px 0; font-size: 0.95rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-graduation-cap" style="color: #7c3aed;"></i> 4. Penugasan Guru Mata Pelajaran (Kurikulum Merdeka)
            </h4>
            <p style="margin: 0 0 14px 0; color: #64748b; font-size: 0.82rem;">
                Centang mata pelajaran yang diampu oleh guru ini. Guru akan mendapatkan shortcut penginputan nilai rapor di dasbor dan berhak mengelola nilai mapel terkait.
            </p>

            @php
                $currentSubjectIds = old('teaching_subject_ids', $teacher->teaching_subject_ids ?? []);
            @endphp

            @if($subjects->isEmpty())
                <div style="font-size: 0.82rem; color: #94a3b8; font-style: italic;">Belum ada daftar mata pelajaran. Tambahkan di menu SMILE Kurikulum.</div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px;">
                    @foreach($subjects as $subj)
                        @php
                            $isSubjChecked = in_array($subj->id, $currentSubjectIds);
                        @endphp
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 0.84rem; cursor: pointer; padding: 8px 12px; border-radius: 10px; background: #faf5ff; border: 1px solid #e9d5ff; transition: all 0.2s;">
                            <input type="checkbox" name="teaching_subject_ids[]" value="{{ $subj->id }}" {{ $isSubjChecked ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #8b5cf6;">
                            <div>
                                <div style="font-weight: 800; color: #4c1d95;">{{ $subj->name }}</div>
                                <div style="font-size: 0.72rem; color: #7c3aed;">
                                    {{ $subj->category }} • KKTP: {{ number_format($subj->kkm, 0) }}
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Tombol Aksi -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            <a href="{{ route('teachers.index') }}" style="padding: 11px 22px; background: #f1f5f9; color: #475569; border-radius: 10px; font-weight: 700; font-size: 0.92rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                Batal
            </a>
            <button type="submit" class="btn-submit" style="padding: 11px 26px; font-size: 0.92rem; font-weight: 800; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i> Simpan Penugasan Guru
            </button>
        </div>

    </form>
</div>
@endsection
