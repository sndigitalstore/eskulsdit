@extends('layouts.app')

@section('title', 'Kartu Hafalan – ' . $student->name)
@section('page-title', 'Kartu Hafalan Tahfidz')

@section('content')

@php
    $qualityColor = fn($q) => match($q) {
        'lancar'          => '#16a34a',
        'cukup'           => '#d97706',
        'perlu_perbaikan' => '#dc2626',
        default           => '#64748b',
    };
    $qualityLabel = fn($q) => match($q) {
        'lancar'          => 'Lancar',
        'cukup'           => 'Cukup',
        'perlu_perbaikan' => 'Perlu Perbaikan',
        default           => '-',
    };
    $typeColor = fn($t) => $t === 'setoran_baru' ? '#0ea5e9' : '#8b5cf6';
    $typeLabel = fn($t) => $t === 'setoran_baru' ? 'Setoran Baru' : 'Muroja\'ah';
@endphp

<style>
.card-header-custom {
    background: linear-gradient(135deg, #1e3a5f 0%, #0f766e 100%);
    border-radius: 16px 16px 0 0;
    padding: 28px 30px;
    color: white;
}
.stat-box {
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    padding: 16px 20px;
    text-align: center;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.2);
}
.stat-box .stat-num { font-size: 2rem; font-weight: 800; line-height: 1; }
.stat-box .stat-lbl { font-size: 0.75rem; opacity: 0.85; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
.form-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 24px;
}
.form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 14px; }
.form-group label { display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.4px; }
.form-group input, .form-group select, .form-group textarea {
    width: 100%; padding: 9px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px;
    font-size: 0.9rem; background: white; color: #0f172a; box-sizing: border-box;
    transition: border-color 0.2s;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    outline: none; border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15,118,110,0.1);
}
.btn-save {
    background: linear-gradient(135deg, #0f766e, #0ea5e9);
    color: white; border: none; padding: 10px 26px;
    border-radius: 9px; font-weight: 700; font-size: 0.9rem;
    cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
    transition: transform 0.15s, box-shadow 0.15s;
}
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(15,118,110,0.3); }
.table-records th { background: #f1f5f9; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; padding: 10px 12px; }
.table-records td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.88rem; }
.table-records tr:hover td { background: #f8fafc; }
.badge-type { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; color: white; }
.badge-quality { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; color: white; }
.btn-del { background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.85rem; padding: 4px 8px; border-radius: 6px; transition: background 0.15s; }
.btn-del:hover { background: #fee2e2; }
.empty-state { text-align: center; padding: 40px; color: #94a3b8; }
.empty-state i { font-size: 3rem; margin-bottom: 12px; display: block; }
</style>

<div style="max-width: 1000px; margin: 0 auto;">

    {{-- HEADER --}}
    <div class="card-header-custom">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; flex-wrap: wrap;">
            <div>
                <div style="font-size: 0.8rem; opacity: 0.75; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-users"></i>
                    <a href="{{ route('tahfidz-groups.show', $group->id) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ $group->name }}</a>
                    <span>›</span> Kartu Hafalan
                </div>
                <h2 style="margin: 0; font-size: 1.6rem; font-weight: 800;">
                    <i class="fas fa-quran" style="margin-right: 10px; opacity: 0.8;"></i>
                    {{ $student->name }}
                </h2>
                <div style="margin-top: 8px; font-size: 0.85rem; opacity: 0.8;">
                    Kelas {{ $student->class }}
                    @if($student->nis) · NIS: {{ $student->nis }} @endif
                    · Pembimbing: {{ $group->mentor_name }}
                </div>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                {{-- Filter tahun --}}
                <form method="GET" style="display: flex; align-items: center; gap: 6px;">
                    <select name="year_id" onchange="this.form.submit()" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 8px; padding: 7px 10px; font-size: 0.85rem; cursor: pointer;">
                        @foreach($academicYears as $yr)
                            <option value="{{ $yr->id }}" {{ $yr->id == $yearId ? 'selected' : '' }} style="color: #0f172a; background: white;">
                                TP {{ $yr->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('tahfidz.print-card', ['group' => $group->id, 'student' => $student->id, 'year_id' => $yearId]) }}" target="_blank"
                   style="background: #059669; border: 1px solid rgba(255,255,255,0.4); color: white; text-decoration: none; padding: 7px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition: all 0.2s;"
                   onmouseover="this.style.background='#10b981'" onmouseout="this.style.background='#059669'">
                    <i class="fas fa-file-invoice"></i> Cetak Raport Tahfidz
                </a>
                <a href="{{ route('tahfidz-groups.show', $group->id) }}"
                   style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; text-decoration: none; padding: 7px 14px; border-radius: 8px; font-size: 0.85rem; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 20px;">
            <div class="stat-box">
                <div class="stat-num">{{ $totalSetoran }}</div>
                <div class="stat-lbl">Setoran Baru</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">{{ $totalMurojaah }}</div>
                <div class="stat-lbl">Muroja'ah</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">{{ $surahUnique }}</div>
                <div class="stat-lbl">Surah Dicatat</div>
            </div>
        </div>
    </div>

    <div style="background: white; border-radius: 0 0 16px 16px; border: 1px solid #e2e8f0; border-top: none; padding: 28px;">

        @if(session('success'))
            <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- FORM INPUT SETORAN --}}
        <div class="form-card">
            <h4 style="margin: 0 0 18px 0; color: #0f172a; font-size: 1rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus-circle" style="color: #0f766e;"></i> Tambah Catatan Setoran
            </h4>

            <form method="POST" action="{{ route('tahfidz.store', ['group' => $group->id, 'student' => $student->id]) }}">
                @csrf

                <input type="hidden" name="academic_year_id" value="{{ $yearId }}">

                <div class="form-row">
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Nama Surah <span style="color: red;">*</span></label>
                        <input type="text" name="surah_name" list="surah-list" placeholder="misal: Al-Baqarah" required value="{{ old('surah_name') }}">
                        <datalist id="surah-list">
                            @php
                            $surahs = ['Al-Fatihah','Al-Baqarah','Ali Imran','An-Nisa','Al-Maidah','Al-Anam','Al-Araf','Al-Anfal','At-Taubah','Yunus','Hud','Yusuf','Ar-Rad','Ibrahim','Al-Hijr','An-Nahl','Al-Isra','Al-Kahfi','Maryam','Thaha','Al-Anbiya','Al-Hajj','Al-Mu\'minun','An-Nur','Al-Furqan','Asy-Syuara','An-Naml','Al-Qasas','Al-Ankabut','Ar-Rum','Luqman','As-Sajdah','Al-Ahzab','Saba','Fatir','Yasin','As-Saffat','Sad','Az-Zumar','Ghafir','Fussilat','Asy-Syura','Az-Zukhruf','Ad-Dukhan','Al-Jatsiyah','Al-Ahqaf','Muhammad','Al-Fath','Al-Hujurat','Qaf','Az-Zariyat','At-Tur','An-Najm','Al-Qamar','Ar-Rahman','Al-Waqi\'ah','Al-Hadid','Al-Mujadilah','Al-Hasyr','Al-Mumtahanah','As-Saf','Al-Jumuah','Al-Munafiqun','At-Taghabun','At-Talaq','At-Tahrim','Al-Mulk','Al-Qalam','Al-Haqqah','Al-Ma\'arij','Nuh','Al-Jinn','Al-Muzammil','Al-Muddassir','Al-Qiyamah','Al-Insan','Al-Mursalat','An-Naba','An-Nazi\'at','Abasa','At-Takwir','Al-Infitar','Al-Mutaffifin','Al-Insyiqaq','Al-Buruj','At-Tariq','Al-A\'la','Al-Ghasyiyah','Al-Fajr','Al-Balad','Asy-Syams','Al-Lail','Ad-Duha','Asy-Syarh','At-Tin','Al-\'Alaq','Al-Qadr','Al-Bayyinah','Az-Zalzalah','Al-\'Adiyat','Al-Qari\'ah','At-Takasur','Al-\'Asr','Al-Humazah','Al-Fil','Quraisy','Al-Ma\'un','Al-Kausar','Al-Kafirun','An-Nasr','Al-Masad','Al-Ikhlas','Al-Falaq','An-Nas'];
                            @endphp
                            @foreach($surahs as $s)
                                <option value="{{ $s }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label>Juz Ke-</label>
                        <input type="number" name="juz" min="1" max="30" placeholder="1-30" value="{{ old('juz') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Ayat Mulai</label>
                        <input type="number" name="start_ayah" min="1" placeholder="Ayat pertama" value="{{ old('start_ayah') }}">
                    </div>
                    <div class="form-group">
                        <label>Ayat Selesai</label>
                        <input type="number" name="end_ayah" min="1" placeholder="Ayat terakhir" value="{{ old('end_ayah') }}">
                    </div>
                    <div class="form-group">
                        <label>Jenis <span style="color: red;">*</span></label>
                        <select name="type" required>
                            <option value="setoran_baru" {{ old('type') == 'setoran_baru' ? 'selected' : '' }}>Setoran Baru</option>
                            <option value="murojaah" {{ old('type') == 'murojaah' ? 'selected' : '' }}>Muroja'ah</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kualitas <span style="color: red;">*</span></label>
                        <select name="quality" required>
                            <option value="lancar" {{ old('quality') == 'lancar' ? 'selected' : '' }}>⭐ Lancar</option>
                            <option value="cukup" {{ old('quality') == 'cukup' ? 'selected' : '' }}>👍 Cukup</option>
                            <option value="perlu_perbaikan" {{ old('quality') == 'perlu_perbaikan' ? 'selected' : '' }}>⚠️ Perlu Perbaikan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Setoran <span style="color: red;">*</span></label>
                        <input type="date" name="session_date" required value="{{ old('session_date', date('Y-m-d')) }}">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label>Catatan Mentor (opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Catatan tambahan untuk siswa...">{{ old('notes') }}</textarea>
                </div>

                @if($errors->any())
                    <div style="color: #dc2626; font-size: 0.85rem; margin-bottom: 12px;">
                        @foreach($errors->all() as $err) <div>• {{ $err }}</div> @endforeach
                    </div>
                @endif

                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan Setoran
                </button>
            </form>
        </div>

        {{-- TABEL RIWAYAT SETORAN --}}
        <h4 style="margin: 0 0 14px 0; color: #0f172a; font-size: 1rem; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-history" style="color: #0f766e;"></i>
            Riwayat Setoran
            <span style="font-size: 0.8rem; font-weight: 500; color: #64748b;">({{ $records->count() }} catatan)</span>
        </h4>

        @if($records->isEmpty())
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <p style="margin: 0; font-size: 0.95rem;">Belum ada catatan setoran untuk tahun pelajaran ini.</p>
                <p style="margin: 6px 0 0 0; font-size: 0.85rem;">Gunakan form di atas untuk menambahkan setoran pertama.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table class="table-records" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;">No</th>
                            <th style="width: 12%;">Tanggal</th>
                            <th style="width: 22%;">Surah</th>
                            <th style="width: 10%; text-align: center;">Juz</th>
                            <th style="width: 14%; text-align: center;">Ayat</th>
                            <th style="width: 13%; text-align: center;">Jenis</th>
                            <th style="width: 13%; text-align: center;">Kualitas</th>
                            <th>Catatan</th>
                            <th style="width: 5%; text-align: center;">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $i => $rec)
                            <tr>
                                <td style="text-align: center; color: #94a3b8; font-size: 0.8rem;">{{ $i + 1 }}</td>
                                <td style="color: #475569; font-size: 0.85rem;">
                                    {{ \Carbon\Carbon::parse($rec->session_date)->isoFormat('D MMM Y') }}
                                </td>
                                <td style="font-weight: 700; color: #0f172a;">{{ $rec->surah_name }}</td>
                                <td style="text-align: center; color: #0f766e; font-weight: 600;">
                                    {{ $rec->juz ? 'Juz '.$rec->juz : '-' }}
                                </td>
                                <td style="text-align: center; font-size: 0.82rem; color: #475569;">
                                    @if($rec->start_ayah && $rec->end_ayah)
                                        {{ $rec->start_ayah }} – {{ $rec->end_ayah }}
                                    @else -
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge-type" style="background: {{ $typeColor($rec->type) }};">
                                        {{ $typeLabel($rec->type) }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge-quality" style="background: {{ $qualityColor($rec->quality) }};">
                                        {{ $qualityLabel($rec->quality) }}
                                    </span>
                                </td>
                                <td style="font-size: 0.83rem; color: #64748b;">{{ $rec->notes ?? '-' }}</td>
                                <td style="text-align: center;">
                                    <form method="POST" action="{{ route('tahfidz.destroy', $rec->id) }}" onsubmit="return confirm('Hapus setoran {{ $rec->surah_name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-del" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection
