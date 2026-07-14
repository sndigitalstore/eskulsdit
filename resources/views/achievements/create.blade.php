@extends('layouts.app')

@section('title', 'Tambah Prestasi')
@section('page-title', 'Tambah Prestasi Siswa')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('achievements.store') }}" method="POST">
        @csrf
        
        @if($student)
            <div style="margin-bottom: 20px; background: #f8fafc; padding: 15px; border-left: 4px solid #3498db; border-radius: 4px;">
                <p style="margin: 0; color: #444;">
                    Menambahkan prestasi untuk siswa: <br>
                    <strong style="font-size: 1.1rem; color: #2c3e50;">{{ $student->name }}</strong> 
                    <span style="color: #666;">({{ $student->class }})</span>
                </p>
                <input type="hidden" name="student_id" value="{{ $student->id }}">
            </div>
        @else
            <div class="form-group" style="margin-bottom: 20px; position: relative;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #34495e;">Cari Siswa</label>
                <input type="text" id="studentSearch" class="form-control" placeholder="Ketik Nama atau NIS siswa..." autocomplete="off">
                <input type="hidden" name="student_id" id="studentId" required>
                
                <div id="searchSuggestions" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; border-radius: 0 0 8px 8px; z-index: 10; max-height: 200px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <!-- Suggestions will appear here -->
                </div>
                <small style="color: #666; margin-top: 5px; display: block;" id="searchHelp">Pilih siswa dari hasil pencarian.</small>
            </div>

            <script>
                const searchInput = document.getElementById('studentSearch');
                const searchSuggestions = document.getElementById('searchSuggestions');
                const studentIdInput = document.getElementById('studentId');
                let timeoutId;

                searchInput.addEventListener('input', function() {
                    clearTimeout(timeoutId);
                    const query = this.value;
                    studentIdInput.value = ''; // Reset ID if typing

                    if (query.length < 2) {
                        searchSuggestions.style.display = 'none';
                        return;
                    }

                    timeoutId = setTimeout(() => {
                        fetch(`{{ url('/api/students/search') }}?q=${query}`)
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
                                            <div class="suggestion-item" onclick="selectStudent('${student.id}', '${student.name}', '${student.class}')" style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                                                <div style="width: 30px; height: 30px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold;">
                                                    ${student.name.charAt(0)}
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600; color: #333; display: flex; align-items: center;">
                                                        ${student.name} ${statusBadge}
                                                    </div>
                                                    <div style="font-size: 0.8rem; color: #666;">NIS: ${student.nis || '-'} | Kelas: ${student.class}</div>
                                                </div>
                                            </div>
                                        `;
                                    });
                                    searchSuggestions.innerHTML = html;
                                    searchSuggestions.style.display = 'block';
                                } else {
                                    searchSuggestions.innerHTML = '<div style="padding: 10px; color: #888; text-align: center;">Tidak ditemukan.</div>';
                                    searchSuggestions.style.display = 'block';
                                }
                            })
                            .catch(err => {
                                console.error(err);
                            });
                    }, 300);
                });

                function selectStudent(id, name, className) {
                    studentIdInput.value = id;
                    searchInput.value = `${name} (${className})`;
                    searchSuggestions.style.display = 'none';
                }

                // Close suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                        searchSuggestions.style.display = 'none';
                    }
                });
            </script>
        @endif
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; background: #fffcf0; padding: 15px; border-radius: 12px; border: 1px solid #f9e79f;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #7d6608;">Tahun Pelajaran</label>
                <select name="academic_year_id" class="form-control" required>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ ($activeYear && $activeYear->id == $year->id) ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #7d6608;">Semester</label>
                <select name="semester" class="form-control" required>
                    <option value="1" {{ ($activeYear && $activeYear->active_semester == '1') ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                    <option value="2" {{ ($activeYear && $activeYear->active_semester == '2') ? 'selected' : '' }}>Semester 2 (Genap)</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Nama Prestasi / Kejuaraan</label>
            <input type="text" name="name" class="form-control" placeholder="Contoh: Juara 1 Lomba Karate O2SN" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Tingkat</label>
                <select name="level" class="form-control" required>
                    <option value="">-- Pilih Tingkat --</option>
                    <option value="Sekolah">Sekolah</option>
                    <option value="Kecamatan">Kecamatan</option>
                    <option value="Kabupaten/Kota">Kabupaten/Kota</option>
                    <option value="Provinsi">Provinsi</option>
                    <option value="Nasional">Nasional</option>
                    <option value="Internasional">Internasional</option>
                    <option value="Open Turnamen / Event Lainnya">Open Turnamen / Event Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Tanggal Perolehan</label>
                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Penyelenggara (Opsional)</label>
            <input type="text" name="organizer" class="form-control" placeholder="Contoh: Dinas Pendidikan Kota">
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Keterangan Tambahan (Opsional)</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Prestasi
            </button>
            <a href="{{ $student ? route('students.show', $student->id) : route('achievements.index') }}" class="btn-submit" style="background: #ccc; border: 1px solid #bbb; color: #333;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
