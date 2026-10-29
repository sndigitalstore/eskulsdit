@extends('layouts.app')

@section('title', 'Tambah Prestasi')
@section('page-title', 'Tambah Prestasi')

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
        position: relative;
    }
    .cat-card-option:hover {
        border-color: #cbd5e1;
        background: #f1f5f9;
    }
    .cat-card-option.is-selected {
        border-color: #059669;
        background: #ecfdf5;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.12);
    }
    .cat-card-option.is-selected-school {
        border-color: #f59e0b;
        background: #fffbeb;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
    }
    .cat-icon-badge {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .cat-badge-student { background: #d1fae5; color: #047857; }
    .cat-badge-school { background: #fef3c7; color: #b45309; }
    .suggestion-chips-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
    }
    .chip-btn {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .chip-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    @media (max-width: 640px) {
        .cat-selector-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="card" style="max-width: 820px; margin: 0 auto; border-radius: 16px; padding: 24px;">
    <form action="{{ route('achievements.store') }}" method="POST" id="formCreateAchievement">
        @csrf
        
        @if(session('success'))
            <div style="margin-bottom: 20px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.2rem;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('achievements.index') }}" class="btn-action-header btn-blue" style="font-size: 0.8rem; padding: 5px 10px; text-decoration: none;">
                        <i class="fas fa-list"></i> Lihat Daftar Prestasi
                    </a>
                    @if($student)
                        <a href="{{ route('students.show', $student->id) }}" class="btn-action-header btn-green" style="font-size: 0.8rem; padding: 5px 10px; text-decoration: none;">
                            <i class="fas fa-user"></i> Lihat Data Siswa
                        </a>
                    @endif
                </div>
            </div>
        @endif

        {{-- 1. PILIH KATEGORI PRESTASI (SISWA VS SEKOLAH/JUARA UMUM) --}}
        @if(!$student)
        <div style="margin-bottom: 22px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 800; color: #0f172a; font-size: 0.92rem; text-transform: uppercase; letter-spacing: 0.03em;">
                <i class="fas fa-layer-group" style="color: #059669;"></i> Kategori Prestasi
            </label>
            
            <div class="cat-selector-grid">
                {{-- Opsi 1: Prestasi Siswa --}}
                <label class="cat-card-option {{ old('category', $category) !== 'school' ? 'is-selected' : '' }}" id="cardOptionStudent" onclick="selectCategory('student')">
                    <input type="radio" name="category" value="student" id="catRadioStudent" 
                           {{ old('category', $category) !== 'school' ? 'checked' : '' }} style="margin-top: 4px; accent-color: #059669;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span class="cat-icon-badge cat-badge-student"><i class="fas fa-user-graduate"></i></span>
                            <div>
                                <strong style="font-size: 0.98rem; color: #064e3b; display: block;">Prestasi Siswa / Individu</strong>
                                <span style="font-size: 0.76rem; color: #047857;">Untuk siswa perseorangan / perwakilan kelas</span>
                            </div>
                        </div>
                    </div>
                </label>

                {{-- Opsi 2: Prestasi Sekolah / Juara Umum --}}
                <label class="cat-card-option {{ old('category', $category) === 'school' ? 'is-selected-school' : '' }}" id="cardOptionSchool" onclick="selectCategory('school')">
                    <input type="radio" name="category" value="school" id="catRadioSchool" 
                           {{ old('category', $category) === 'school' ? 'checked' : '' }} style="margin-top: 4px; accent-color: #f59e0b;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span class="cat-icon-badge cat-badge-school"><i class="fas fa-trophy"></i></span>
                            <div>
                                <strong style="font-size: 0.98rem; color: #92400e; display: block;">Prestasi Sekolah / Lembaga</strong>
                                <span style="font-size: 0.76rem; color: #b45309;">Juara Umum, Akreditasi, Kontingen Sekolah</span>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>
        @else
            <input type="hidden" name="category" value="student">
        @endif

        {{-- 2. KOTAK INFORMASI JIKA DIBUKA DARI PROFIL SISWA --}}
        @if($student)
            <div style="margin-bottom: 20px; background: #f8fafc; padding: 15px; border-left: 4px solid #3498db; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div>
                    <p style="margin: 0; color: #444;">
                        Menambahkan prestasi untuk siswa: <br>
                        <strong style="font-size: 1.1rem; color: #2c3e50;">{{ $student->name }}</strong> 
                        <span style="color: #666;">(Kelas {{ $student->class }})</span>
                    </p>
                </div>
                <div>
                    <a href="{{ route('achievements.create') }}" class="btn-action-header btn-blue" style="font-size: 0.82rem; padding: 6px 12px; text-decoration: none;">
                        <i class="fas fa-users"></i> Input Siswa Lain
                    </a>
                </div>
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <input type="hidden" name="from_student" value="1">
            </div>
        @else
            {{-- 3A. KOTAK CARI SISWA (TAMPIL JIKA KATEGORI SISWA) --}}
            <div id="studentSelectionContainer" style="{{ old('category', $category) === 'school' ? 'display: none;' : 'display: block;' }}">
                <div class="form-group" style="margin-bottom: 20px; position: relative;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #34495e;">
                        <i class="fas fa-search" style="color: #0284c7;"></i> Cari Siswa Penerima Prestasi <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" id="studentSearch" class="form-control" placeholder="Ketik Nama atau NIS siswa..." autocomplete="off">
                    <input type="hidden" name="student_id" id="studentId" {{ old('category', $category) !== 'school' ? 'required' : '' }}>
                    
                    <div id="searchSuggestions" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; border-radius: 0 0 8px 8px; z-index: 100; max-height: 220px; overflow-y: auto; box-shadow: 0 8px 16px rgba(0,0,0,0.12);">
                        <!-- Suggestions will appear here -->
                    </div>
                    <small style="color: #64748b; margin-top: 5px; display: block;" id="searchHelp">Pilih siswa dari hasil pencarian nama atau NIS.</small>
                </div>
            </div>

            {{-- 3B. KOTAK NAMA SEKOLAH (TAMPIL JIKA KATEGORI SEKOLAH / JUARA UMUM) --}}
            <div id="schoolSelectionContainer" style="{{ old('category', $category) === 'school' ? 'display: block;' : 'display: none;' }}; margin-bottom: 20px;">
                <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 16px; margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                        <i class="fas fa-award" style="color: #f59e0b; font-size: 1.15rem;"></i>
                        <strong style="color: #92400e; font-size: 0.95rem;">Mode Prestasi Lembaga / Juara Umum Sekolah</strong>
                    </div>
                    <p style="margin: 0; font-size: 0.82rem; color: #b45309; line-height: 1.5;">
                        Prestasi ini akan dicatat atas nama sekolah / kontingen SDIT An Nadzir secara utuh (tidak terikat pada satu profil siswa saja).
                    </p>
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #34495e;">
                        <i class="fas fa-school" style="color: #059669;"></i> Atas Nama Lembaga / Kontingen
                    </label>
                    <input type="text" name="school_name" id="schoolNameInput" class="form-control" 
                           value="{{ old('school_name', 'SDIT An Nadzir') }}" placeholder="Contoh: SDIT An Nadzir / Kontingen SDIT An Nadzir">
                </div>
            </div>
        @endif
        
        {{-- 4. TAHUN PELAJARAN & SEMESTER --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; background: #fffcf0; padding: 15px; border-radius: 12px; border: 1px solid #f9e79f;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #7d6608;">
                    <i class="fas fa-calendar"></i> Tahun Pelajaran
                </label>
                <select name="academic_year_id" class="form-control" required>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ ($activeYear && $activeYear->id == $year->id) ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #7d6608;">
                    <i class="fas fa-calendar-check"></i> Semester
                </label>
                <select name="semester" class="form-control" required>
                    <option value="1" {{ ($activeYear && $activeYear->active_semester == '1') ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                    <option value="2" {{ ($activeYear && $activeYear->active_semester == '2') ? 'selected' : '' }}>Semester 2 (Genap)</option>
                </select>
            </div>
        </div>

        {{-- 5. NAMA PRESTASI DENGAN CHIP SARAN --}}
        <div class="form-group" style="margin-bottom: 18px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #1e293b;">
                <i class="fas fa-medal" style="color: #f59e0b;"></i> Nama Prestasi / Kejuaraan <span style="color: #dc2626;">*</span>
            </label>
            <input type="text" name="name" id="achievementNameInput" class="form-control" 
                   placeholder="Contoh: Juara Umum O2SN Tingkat Kecamatan Cinangka" 
                   value="{{ old('name') }}" required>
            
            {{-- Suggestion Chips untuk Prestasi Sekolah --}}
            <div id="schoolSuggestionChips" style="{{ old('category', $category) === 'school' ? 'display: block;' : 'display: none;' }}">
                <div style="font-size: 0.74rem; color: #64748b; margin-top: 6px; font-weight: 600;">Klik untuk gunakan format contoh:</div>
                <div class="suggestion-chips-wrap">
                    <button type="button" class="chip-btn" onclick="fillAchievementName('Juara Umum O2SN Tingkat Kecamatan')">🏆 Juara Umum O2SN Kecamatan</button>
                    <button type="button" class="chip-btn" onclick="fillAchievementName('Juara Umum Pentas PAI')">🕌 Juara Umum Pentas PAI</button>
                    <button type="button" class="chip-btn" onclick="fillAchievementName('Juara Umum Turnamen Sepak Bola')">⚽ Juara Umum Sepak Bola</button>
                    <button type="button" class="chip-btn" onclick="fillAchievementName('Juara Umum FLS2N')">🎨 Juara Umum FLS2N</button>
                    <button type="button" class="chip-btn" onclick="fillAchievementName('Sekolah Adiwiyata Tingkat Kabupaten')">🌿 Sekolah Adiwiyata</button>
                    <button type="button" class="chip-btn" onclick="fillAchievementName('Akreditasi A (Unggul)')">⭐ Akreditasi A Unggul</button>
                </div>
            </div>

            {{-- Suggestion Chips untuk Prestasi Siswa --}}
            <div id="studentSuggestionChips" style="{{ old('category', $category) !== 'school' ? 'display: block;' : 'display: none;' }}">
                <div style="font-size: 0.74rem; color: #64748b; margin-top: 6px; font-weight: 600;">Contoh:</div>
                <div class="suggestion-chips-wrap">
                    <button type="button" class="chip-btn" onclick="fillAchievementName('Juara 1 Lomba Tahfidz Al-Qur\'an')">🥇 Juara 1 Tahfidz</button>
                    <button type="button" class="chip-btn" onclick="fillAchievementName('Juara 2 Pidato Bahasa Arab')">🥈 Juara 2 Pidato Arab</button>
                    <button type="button" class="chip-btn" onclick="fillAchievementName('Juara 1 Turnamen Futsal')">⚽ Juara 1 Futsal</button>
                    <button type="button" class="chip-btn" onclick="fillAchievementName('Juara 3 Olimpiade Matematika')">🥉 Juara 3 Matematika</button>
                </div>
            </div>
        </div>

        {{-- 6. TINGKAT & TANGGAL PEROLEHAN --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 18px;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #1e293b;">
                    <i class="fas fa-award" style="color: #8b5cf6;"></i> Tingkat <span style="color: #dc2626;">*</span>
                </label>
                <select name="level" class="form-control" required>
                    <option value="">-- Pilih Tingkat --</option>
                    <option value="Sekolah" {{ old('level') == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                    <option value="Kecamatan" {{ old('level') == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                    <option value="Kabupaten/Kota" {{ (old('level') == 'Kabupaten/Kota' || !old('level')) ? 'selected' : '' }}>Kabupaten/Kota</option>
                    <option value="Provinsi" {{ old('level') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                    <option value="Nasional" {{ old('level') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                    <option value="Internasional" {{ old('level') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                    <option value="Open Turnamen / Event Lainnya" {{ old('level') == 'Open Turnamen / Event Lainnya' ? 'selected' : '' }}>Open Turnamen / Event Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #1e293b;">
                    <i class="far fa-calendar-alt" style="color: #059669;"></i> Tanggal Perolehan <span style="color: #dc2626;">*</span>
                </label>
                <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
            </div>
        </div>

        {{-- 7. PENYELENGGARA --}}
        <div class="form-group" style="margin-bottom: 18px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #1e293b;">
                <i class="fas fa-building" style="color: #64748b;"></i> Penyelenggara (Opsional)
            </label>
            <input type="text" name="organizer" class="form-control" value="{{ old('organizer') }}" placeholder="Contoh: Dinas Pendidikan dan Kebudayaan, Kemenag, atau Forum Silaturahmi">
        </div>

        {{-- 8. KETERANGAN TAMBAHAN --}}
        <div class="form-group" style="margin-bottom: 24px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 700; color: #1e293b;">
                <i class="fas fa-align-left" style="color: #64748b;"></i> Keterangan Tambahan (Opsional)
            </label>
            <textarea name="description" class="form-control" rows="3" placeholder="Contoh untuk Juara Umum: Meraih predikat Juara Umum dengan total perolehan 6 Emas dan 2 Perak, diikuti oleh kontingen... / Catatan lainnya...">{{ old('description') }}</textarea>
        </div>

        {{-- 9. TOMBOL SUBMIT --}}
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="submit" name="action" value="stay" class="btn-submit" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                <i class="fas fa-save"></i> Simpan Prestasi
            </button>
            <button type="submit" name="action" value="finish" class="btn-submit" style="background: #334155; border: 1px solid #1e293b;">
                <i class="fas fa-check-circle"></i> Simpan & Kembali ke Daftar
            </button>
            <a href="{{ $student ? route('students.show', $student->id) : route('achievements.index') }}" class="btn-submit" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; text-decoration: none; text-align: center; display: inline-flex; align-items: center;">
                Batal
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function selectCategory(category) {
        const catRadioStudent = document.getElementById('catRadioStudent');
        const catRadioSchool = document.getElementById('catRadioSchool');
        const cardOptionStudent = document.getElementById('cardOptionStudent');
        const cardOptionSchool = document.getElementById('cardOptionSchool');
        const studentContainer = document.getElementById('studentSelectionContainer');
        const schoolContainer = document.getElementById('schoolSelectionContainer');
        const studentIdInput = document.getElementById('studentId');
        const schoolChips = document.getElementById('schoolSuggestionChips');
        const studentChips = document.getElementById('studentSuggestionChips');

        if (category === 'school') {
            if (catRadioSchool) catRadioSchool.checked = true;
            if (cardOptionSchool) cardOptionSchool.className = 'cat-card-option is-selected-school';
            if (cardOptionStudent) cardOptionStudent.className = 'cat-card-option';
            
            if (studentContainer) studentContainer.style.display = 'none';
            if (schoolContainer) schoolContainer.style.display = 'block';
            if (studentIdInput) studentIdInput.removeAttribute('required');
            
            if (schoolChips) schoolChips.style.display = 'block';
            if (studentChips) studentChips.style.display = 'none';
        } else {
            if (catRadioStudent) catRadioStudent.checked = true;
            if (cardOptionStudent) cardOptionStudent.className = 'cat-card-option is-selected';
            if (cardOptionSchool) cardOptionSchool.className = 'cat-card-option';
            
            if (studentContainer) studentContainer.style.display = 'block';
            if (schoolContainer) schoolContainer.style.display = 'none';
            if (studentIdInput) studentIdInput.setAttribute('required', 'required');

            if (schoolChips) schoolChips.style.display = 'none';
            if (studentChips) studentChips.style.display = 'block';
        }
    }

    function fillAchievementName(text) {
        const input = document.getElementById('achievementNameInput');
        if (input) {
            input.value = text;
            input.focus();
        }
    }

    // Ajax pencarian siswa
    const searchInput = document.getElementById('studentSearch');
    const searchSuggestions = document.getElementById('searchSuggestions');
    const studentIdInput = document.getElementById('studentId');
    let timeoutId;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const query = this.value;
            if (studentIdInput) studentIdInput.value = '';

            if (query.length < 2) {
                if (searchSuggestions) searchSuggestions.style.display = 'none';
                return;
            }

            timeoutId = setTimeout(() => {
                fetch(`{{ url('/api/students/search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            let html = '';
                            data.forEach(student => {
                                let statusBadge = '';
                                if (student.status === 'graduated' || student.status === 'lulus') {
                                    statusBadge = '<span style="background: #dcfce7; color: #166534; font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; margin-left: 5px;">LULUS</span>';
                                }
                                
                                html += `
                                    <div class="suggestion-item" onclick="selectStudent('${student.id}', '${escapeHtml(student.name)}', '${escapeHtml(student.class)}')" style="padding: 10px 14px; border-bottom: 1px solid #f1f5f9; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 32px; height: 32px; background: #0284c7; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.82rem; font-weight: bold; flex-shrink: 0;">
                                            ${student.name.charAt(0)}
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; color: #1e293b; display: flex; align-items: center;">
                                                ${student.name} ${statusBadge}
                                            </div>
                                            <div style="font-size: 0.78rem; color: #64748b;">NIS: ${student.nis || '-'} &bull; Kelas: ${student.class}</div>
                                        </div>
                                    </div>
                                `;
                            });
                            searchSuggestions.innerHTML = html;
                            searchSuggestions.style.display = 'block';
                        } else {
                            searchSuggestions.innerHTML = '<div style="padding: 12px; color: #94a3b8; text-align: center; font-size: 0.85rem;">Siswa tidak ditemukan.</div>';
                            searchSuggestions.style.display = 'block';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                    });
            }, 250);
        });

        document.addEventListener('click', function(e) {
            if (searchInput && searchSuggestions && !searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                searchSuggestions.style.display = 'none';
            }
        });
    }

    function selectStudent(id, name, className) {
        if (studentIdInput) studentIdInput.value = id;
        if (searchInput) searchInput.value = `${name} (Kelas ${className})`;
        if (searchSuggestions) searchSuggestions.style.display = 'none';
    }

    function escapeHtml(text) {
        return (text || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }
</script>
@endpush
@endsection
