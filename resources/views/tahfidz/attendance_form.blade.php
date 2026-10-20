@extends('layouts.app')

@section('title', 'Input Absensi – ' . $group->name)
@section('page-title', 'Input Absensi Sesi')

@section('content')

<style>
.form-header {
    background: linear-gradient(135deg, #1e3a5f 0%, #0f766e 100%);
    border-radius: 16px 16px 0 0;
    padding: 24px 28px; color: white;
}
.student-row {
    display: grid;
    grid-template-columns: 36px 1fr auto 1fr;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s;
}
.student-row:hover { background: #f8fafc; }
.student-row:last-child { border-bottom: none; }
.status-radio { display: none; }
.status-label {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px; border-radius: 20px; cursor: pointer;
    font-size: 0.82rem; font-weight: 600; border: 2px solid #e2e8f0;
    transition: all 0.15s; color: #64748b;
}
.status-radio:checked + .status-label { border-color: transparent; color: white; }
.radio-hadir:checked + .status-label  { background: #16a34a; }
.radio-sakit:checked + .status-label  { background: #d97706; }
.radio-izin:checked  + .status-label  { background: #0ea5e9; }
.radio-alpha:checked + .status-label  { background: #dc2626; }
.radio-hadir + .status-label:hover  { background: #dcfce7; color: #16a34a; border-color: #86efac; }
.radio-sakit + .status-label:hover  { background: #fef3c7; color: #d97706; border-color: #fcd34d; }
.radio-izin  + .status-label:hover  { background: #e0f2fe; color: #0ea5e9; border-color: #7dd3fc; }
.radio-alpha + .status-label:hover  { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }
.avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #eff6ff; color: #2563eb;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.85rem; flex-shrink: 0;
}
.btn-submit {
    background: linear-gradient(135deg, #0f766e, #0ea5e9);
    color: white; border: none; padding: 12px 32px;
    border-radius: 10px; font-weight: 800; font-size: 1rem;
    cursor: pointer; display: inline-flex; align-items: center; gap: 10px;
    transition: transform 0.15s, box-shadow 0.15s;
}
.btn-submit:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(15,118,110,0.35); }
.quick-btn {
    background: #f1f5f9; border: 1px solid #e2e8f0;
    color: #475569; padding: 5px 12px; border-radius: 7px;
    font-size: 0.8rem; font-weight: 600; cursor: pointer;
    transition: background 0.15s;
}
.quick-btn:hover { background: #e2e8f0; }
</style>

<div style="max-width: 900px; margin: 0 auto;">
    <div class="form-header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
            <div>
                <div style="font-size: 0.8rem; opacity: 0.75; margin-bottom: 6px;">
                    <a href="{{ route('tahfidz.attendance.index', ['group' => $group->id, 'year_id' => $yearId]) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Daftar Sesi Absensi
                    </a>
                </div>
                <h2 style="margin: 0; font-size: 1.4rem; font-weight: 800;">
                    <i class="fas fa-clipboard-check" style="opacity: 0.8;"></i>
                    Input Absensi Sesi
                </h2>
                <div style="margin-top: 6px; font-size: 0.85rem; opacity: 0.8;">
                    Kelompok: <strong>{{ $group->name }}</strong> &nbsp;·&nbsp; Pembimbing: {{ $group->mentor_name }}
                </div>
            </div>
            <div style="font-size: 0.85rem; opacity: 0.8;">
                {{ $students->count() }} Siswa Terdaftar
            </div>
        </div>
    </div>

    <div style="background: white; border-radius: 0 0 16px 16px; border: 1px solid #e2e8f0; border-top: none;">

        @if($students->isEmpty())
            <div style="text-align: center; padding: 50px; color: #94a3b8;">
                <i class="fas fa-users-slash" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                <p>Belum ada siswa di kelompok ini. Tambahkan siswa terlebih dahulu.</p>
                <a href="{{ route('tahfidz-groups.show', $group->id) }}" style="color: #0f766e;">← Kembali ke kelompok</a>
            </div>
        @else

        <form method="POST" action="{{ route('tahfidz.attendance.store', ['group' => $group->id]) }}">
            @csrf

            {{-- PENGATURAN SESI --}}
            <div style="padding: 20px 20px 16px; border-bottom: 2px solid #f1f5f9; display: flex; align-items: flex-end; gap: 20px; flex-wrap: wrap;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.4px;">
                        Tanggal Sesi <span style="color: red;">*</span>
                    </label>
                    <input type="date" name="session_date" value="{{ $date }}" required
                        style="padding: 9px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; color: #0f172a;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.4px;">
                        Tahun Pelajaran <span style="color: red;">*</span>
                    </label>
                    <select name="academic_year_id" required style="padding: 9px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; color: #0f172a;">
                        @foreach($academicYears as $yr)
                            <option value="{{ $yr->id }}" {{ $yr->id == $yearId ? 'selected' : '' }}>TP {{ $yr->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Tombol isi semua hadir --}}
                <div style="display: flex; gap: 8px;">
                    <button type="button" onclick="setAll('hadir')" class="quick-btn" style="border-color: #86efac; color: #16a34a;">
                        ✅ Semua Hadir
                    </button>
                    <button type="button" onclick="setAll('alpha')" class="quick-btn" style="border-color: #fca5a5; color: #dc2626;">
                        ❌ Semua Alpha
                    </button>
                </div>
            </div>

            {{-- HEADER TABEL --}}
            <div style="padding: 10px 20px; background: #f8fafc; display: grid; grid-template-columns: 36px 1fr auto 1fr; gap: 12px; align-items: center; border-bottom: 1px solid #e2e8f0;">
                <div></div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Nama Siswa</div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; min-width: 300px; text-align: center;">Status Kehadiran</div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Catatan (opsional)</div>
            </div>

            {{-- DAFTAR SISWA --}}
            @foreach($students as $i => $student)
                @php $ex = $existing->get($student->id); @endphp
                <div class="student-row">
                    {{-- Avatar --}}
                    <div class="avatar">{{ substr($student->name, 0, 1) }}</div>

                    {{-- Nama --}}
                    <div>
                        <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">{{ $student->name }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">Kelas {{ $student->class }}{{ $student->nis ? ' · NIS: '.$student->nis : '' }}</div>
                    </div>

                    {{-- Status radio buttons --}}
                    <div style="display: flex; gap: 6px; flex-wrap: wrap; min-width: 300px; justify-content: center;">
                        @foreach(['hadir' => ['✅','Hadir'], 'sakit' => ['🤒','Sakit'], 'izin' => ['📄','Izin'], 'alpha' => ['❌','Alpha']] as $val => [$icon, $lbl])
                            <input type="radio"
                                id="s{{ $student->id }}_{{ $val }}"
                                name="attendance[{{ $student->id }}][status]"
                                value="{{ $val }}"
                                class="status-radio radio-{{ $val }}"
                                {{ ($ex ? $ex->status : 'hadir') === $val ? 'checked' : '' }}>
                            <label for="s{{ $student->id }}_{{ $val }}" class="status-label">{{ $icon }} {{ $lbl }}</label>
                        @endforeach
                    </div>

                    {{-- Catatan --}}
                    <input type="text" name="attendance[{{ $student->id }}][notes]"
                        placeholder="Catatan..."
                        value="{{ $ex?->notes ?? '' }}"
                        style="padding: 7px 10px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.82rem; color: #0f172a; width: 100%; box-sizing: border-box;">
                </div>
            @endforeach

            {{-- FOOTER --}}
            <div style="padding: 20px; border-top: 2px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; align-items: center;">
                <a href="{{ route('tahfidz.attendance.index', ['group' => $group->id]) }}" style="background: #f1f5f9; color: #475569; text-decoration: none; padding: 11px 22px; border-radius: 10px; font-weight: 600;">
                    Batal
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Simpan Absensi {{ $students->count() }} Siswa
                </button>
            </div>
        </form>

        @endif
    </div>
</div>

<script>
function setAll(status) {
    document.querySelectorAll(`input[type=radio][value="${status}"]`).forEach(r => r.checked = true);
}
</script>

@endsection
