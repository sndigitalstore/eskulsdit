@extends('layouts.app')

@section('title', 'Detail Kelompok ' . $typeLabel . ': ' . $group->name)
@section('page-title', 'Kelompok ' . $typeLabel)

@push('styles')
<style>
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0; 
        top: 0; 
        width: 100%; 
        height: 100%; 
        overflow: hidden; 
        background-color: rgba(15, 23, 42, 0.6); 
        backdrop-filter: blur(4px);
        animation: fadeIn 0.25s ease-out;
        align-items: center;
        justify-content: center;
    }
    .modal.active { display: flex; }
    .modal-content {
        background-color: #ffffff;
        width: 92%;
        max-width: 750px;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
        display: flex;
        flex-direction: column;
        max-height: 85vh;
        animation: popIn 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes popIn {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }
    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-body {
        padding: 1.25rem 1.5rem;
        overflow-y: auto;
        flex: 1;
    }
    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: #f8fafc;
        border-radius: 0 0 16px 16px;
    }
    .student-item {
        padding: 10px 14px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: background 0.15s;
    }
    .student-item:hover { background: #f8fafc; }
</style>
@endpush

@section('content')
<div style="margin-bottom: 25px;">
    <!-- BREADCRUMB -->
    <div style="margin-bottom: 15px;">
        <a href="{{ $type === 'tilawati' ? route('tilawati-groups.index') : route('tahfidz-groups.index') }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kelompok {{ $typeLabel }}
        </a>
    </div>

    <!-- GROUP HEADER CARD -->
    <div class="card" style="padding: 24px; margin-bottom: 24px; border-top: 4px solid {{ $type === 'tilawati' ? '#059669' : '#0284c7' }};">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
            <div style="display: flex; align-items: flex-start; gap: 16px;">
                <div style="width: 56px; height: 56px; border-radius: 14px; background: {{ $type === 'tilawati' ? '#ecfdf5' : '#f0f9ff' }}; color: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0;">
                    <i class="fas {{ $type === 'tilawati' ? 'fa-book-reader' : 'fa-quran' }}"></i>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <h2 style="margin: 0; font-size: 1.35rem; font-weight: 800; color: #0f172a;">
                            {{ $group->name }}
                        </h2>
                        <span style="background: {{ $type === 'tilawati' ? '#ecfdf5' : '#f0f9ff' }}; color: {{ $type === 'tilawati' ? '#047857' : '#0369a1' }}; border: 1px solid {{ $type === 'tilawati' ? '#a7f3d0' : '#bae6fd' }}; font-size: 0.75rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase;">
                            {{ $typeLabel }}
                        </span>
                        @if($group->target_level)
                            <span style="background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; font-size: 0.75rem; font-weight: 700; padding: 2px 8px; border-radius: 20px;">
                                <i class="fas fa-bullseye"></i> {{ $group->target_level }}
                            </span>
                        @endif
                    </div>

                    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 10px; font-size: 0.88rem; color: #475569;">
                        <div>
                            <i class="fas fa-chalkboard-teacher" style="color: #64748b;"></i> Pembimbing: 
                            <strong style="color: #1e293b;">{{ $group->mentor_name }}</strong>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt" style="color: #64748b;"></i> TP: 
                            <strong style="color: #1e293b;">{{ $group->academicYear->name ?? '-' }}</strong>
                        </div>
                        @if($group->schedule)
                            <div>
                                <i class="fas fa-clock" style="color: #64748b;"></i> Jadwal: 
                                <strong style="color: #1e293b;">{{ $group->schedule }}</strong>
                            </div>
                        @endif
                    </div>

                    @if($group->notes)
                        <div style="margin-top: 10px; font-size: 0.84rem; color: #64748b; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border-left: 3px solid #cbd5e1;">
                            {{ $group->notes }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <button type="button" onclick="openAddStudentsModal()" class="btn-action-header btn-green" style="background: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }};">
                    <i class="fas fa-user-plus"></i> + Tambah Siswa
                </button>
                <a href="{{ route('mentoring-groups.print-group', $group->id) }}" target="_blank" class="btn-action-header btn-dark" style="background: #334155;">
                    <i class="fas fa-print"></i> Cetak Daftar Kelompok
                </a>
                <a href="{{ $type === 'tilawati' ? route('tilawati-groups.edit', $group->id) : route('tahfidz-groups.edit', $group->id) }}" class="btn-action-header btn-orange">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <!-- MEMBER STUDENTS LIST -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #1e293b;">
                    Daftar Anggota Siswa
                </h3>
                <span style="background: #e2e8f0; color: #1e293b; font-size: 0.8rem; font-weight: 700; padding: 2px 10px; border-radius: 12px;">
                    {{ $group->students->count() }} Siswa
                </span>
            </div>
            @if($group->students->isNotEmpty())
                <span style="font-size: 0.84rem; color: #64748b;">
                    Klik tombol <strong>+ Tambah Siswa</strong> di atas untuk menambahkan siswa lainnya.
                </span>
            @endif
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 0;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px 16px; width: 5%; text-align: center;">No</th>
                        <th style="padding: 12px 16px; text-align: left;">Nama Lengkap Siswa</th>
                        <th style="padding: 12px 16px; text-align: center; width: 12%;">NIS</th>
                        <th style="padding: 12px 16px; text-align: center; width: 10%;">Kelas</th>
                        <th style="padding: 12px 16px; text-align: left;">Catatan / Target Siswa</th>
                        <th style="padding: 12px 16px; text-align: center; width: 14%;">Tanggal Masuk</th>
                        <th style="padding: 12px 16px; text-align: center; width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($group->students as $index => $student)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 16px; text-align: center; font-weight: bold; color: #64748b;">
                                {{ $index + 1 }}
                            </td>
                            <td style="padding: 14px 16px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem;">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a;">{{ $student->name }}</div>
                                        @if($student->parent_phone)
                                            <small style="color: #64748b; font-size: 0.75rem;"><i class="fab fa-whatsapp"></i> {{ $student->parent_phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 14px 16px; text-align: center; color: #475569; font-family: monospace; font-size: 0.9rem;">
                                {{ $student->nis ?? '-' }}
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <span style="background: #f1f5f9; color: #0f172a; padding: 3px 8px; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">
                                    {{ $student->class }}
                                </span>
                            </td>
                            <td style="padding: 14px 16px; color: #475569; font-size: 0.88rem;">
                                {{ $student->pivot->notes ?? '-' }}
                            </td>
                            <td style="padding: 14px 16px; text-align: center; color: #64748b; font-size: 0.85rem;">
                                {{ $student->pivot->created_at ? $student->pivot->created_at->format('d/m/Y') : '-' }}
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <button type="button" onclick="confirmRemoveStudent('{{ $student->id }}', '{{ $student->name }}')" class="btn-action-header btn-red" style="padding: 5px 10px; font-size: 0.78rem;" title="Keluarkan dari Kelompok">
                                    <i class="fas fa-user-minus"></i> Hapus
                                </button>
                                <form id="remove-form-{{ $student->id }}" action="{{ route('mentoring-groups.remove-student', ['group' => $group->id, 'student' => $student->id]) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <div style="font-size: 2.2rem; margin-bottom: 8px; color: #cbd5e1;">
                                    <i class="fas fa-users-slash"></i>
                                </div>
                                <h4 style="margin: 0; color: #475569;">Belum Ada Anggota di Kelompok Ini</h4>
                                <p style="margin: 6px 0 16px 0; font-size: 0.9rem;">Tambahkan siswa ke dalam kelompok ini sekarang.</p>
                                <button type="button" onclick="openAddStudentsModal()" class="btn-action-header btn-green" style="background: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }};">
                                    <i class="fas fa-user-plus"></i> + Tambah Siswa Sekarang
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH SISWA -->
<div id="addStudentsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">
                    Tambah Siswa ke Kelompok: {{ $group->name }}
                </h3>
                <small style="color: #64748b;">Pilih satu atau beberapa siswa untuk dimasukkan ke kelompok ini.</small>
            </div>
            <button type="button" onclick="closeAddStudentsModal()" style="border: none; background: transparent; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div style="padding: 12px 1.5rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 140px;">
                <select id="modalClassFilter" class="form-control" onchange="loadStudentsForModal()" style="padding: 8px 12px; font-size: 0.88rem;">
                    <option value="ALL">-- Semua Kelas --</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls }}">Kelas {{ $cls }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 2; min-width: 200px; position: relative;">
                <input type="text" id="modalSearchFilter" oninput="filterModalStudentList()" placeholder="Cari nama atau NIS..." class="form-control" style="padding: 8px 12px 8px 32px; font-size: 0.88rem;">
                <i class="fas fa-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem;"></i>
            </div>
        </div>

        <form action="{{ route('mentoring-groups.add-students', $group->id) }}" method="POST" id="addStudentsForm">
            @csrf

            <div class="modal-body" style="padding: 0; max-height: 50vh; overflow-y: auto;">
                <div style="padding: 10px 1.5rem; background: #f1f5f9; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 0.85rem; color: #334155; cursor: pointer; margin: 0;">
                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)">
                        Pilih Semua yang Ditampilkan
                    </label>
                    <span id="selectedCountBadge" style="font-size: 0.8rem; font-weight: 700; color: #0284c7;">
                        0 Dipilih
                    </span>
                </div>

                <div id="studentListContainer">
                    <div style="padding: 30px; text-align: center; color: #94a3b8;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem;"></i>
                        <p style="margin: 8px 0 0 0; font-size: 0.88rem;">Memuat daftar siswa...</p>
                    </div>
                </div>
            </div>

            <!-- CATATAN OPSIONAL -->
            <div style="padding: 12px 1.5rem; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <label style="display: block; margin-bottom: 4px; font-size: 0.82rem; font-weight: 600; color: #334155;">
                    Catatan untuk Siswa yang Dipilih (Opsional)
                </label>
                <input type="text" name="notes" placeholder="Contoh: Mulai Jilid 2 Hal 10, atau Juz 30" class="form-control" style="padding: 7px 12px; font-size: 0.85rem;">
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeAddStudentsModal()" class="btn-action-header btn-dark" style="background: #94a3b8; padding: 8px 16px;">
                    Batal
                </button>
                <button type="submit" id="btnSubmitAddStudents" class="btn-action-header btn-green" style="background: {{ $type === 'tilawati' ? '#059669' : '#0284c7' }}; padding: 8px 20px;" disabled>
                    <i class="fas fa-plus-circle"></i> Tambahkan Siswa Terpilih
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let allFetchedStudents = [];

    function openAddStudentsModal() {
        document.getElementById('addStudentsModal').classList.add('active');
        loadStudentsForModal();
    }

    function closeAddStudentsModal() {
        document.getElementById('addStudentsModal').classList.remove('active');
    }

    function loadStudentsForModal() {
        const classVal = document.getElementById('modalClassFilter').value;
        const container = document.getElementById('studentListContainer');
        container.innerHTML = `
            <div style="padding: 30px; text-align: center; color: #94a3b8;">
                <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem;"></i>
                <p style="margin: 8px 0 0 0; font-size: 0.88rem;">Memuat siswa...</p>
            </div>
        `;

        const url = `{{ route('mentoring.api.students-by-class') }}?class=${encodeURIComponent(classVal)}&year_id={{ $group->academic_year_id }}&group_id={{ $group->id }}&type={{ $group->type }}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                allFetchedStudents = data;
                renderStudentList(data);
            })
            .catch(err => {
                container.innerHTML = `
                    <div style="padding: 20px; text-align: center; color: #ef4444;">
                        Gagal memuat data siswa. Silakan coba lagi.
                    </div>
                `;
            });
    }

    function filterModalStudentList() {
        const query = document.getElementById('modalSearchFilter').value.toLowerCase();
        const filtered = allFetchedStudents.filter(s => {
            return s.name.toLowerCase().includes(query) || s.nis.toLowerCase().includes(query) || s.class.toLowerCase().includes(query);
        });
        renderStudentList(filtered);
    }

    function renderStudentList(students) {
        const container = document.getElementById('studentListContainer');
        if (students.length === 0) {
            container.innerHTML = `
                <div style="padding: 30px; text-align: center; color: #94a3b8;">
                    Tidak ditemukan siswa yang cocok.
                </div>
            `;
            updateSelectedBadge();
            return;
        }

        let html = '';
        students.forEach(student => {
            let statusBadge = '';
            let disabledAttr = '';
            
            if (student.is_in_this_group) {
                statusBadge = '<span style="background: #e0f2fe; color: #0369a1; font-size: 0.72rem; padding: 2px 8px; border-radius: 4px; font-weight: 600;"><i class="fas fa-check"></i> Sudah di Kelompok Ini</span>';
                disabledAttr = 'disabled';
            } else if (student.other_group_name) {
                statusBadge = `<span style="background: #fef3c7; color: #b45309; font-size: 0.72rem; padding: 2px 8px; border-radius: 4px; font-weight: 600;"><i class="fas fa-exchange-alt"></i> Pindah dari: ${student.other_group_name}</span>`;
            } else {
                statusBadge = '<span style="background: #ecfdf5; color: #047857; font-size: 0.72rem; padding: 2px 8px; border-radius: 4px; font-weight: 600;">Belum Ada Kelompok</span>';
            }

            html += `
                <label class="student-item" style="cursor: ${student.is_in_this_group ? 'not-allowed' : 'pointer'}; margin: 0; opacity: ${student.is_in_this_group ? '0.6' : '1'};">
                    <input type="checkbox" name="student_ids[]" value="${student.id}" class="student-checkbox" ${disabledAttr} onchange="updateSelectedBadge()">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <strong style="color: #0f172a; font-size: 0.9rem;">${student.name}</strong>
                            <span style="background: #f1f5f9; color: #475569; font-size: 0.75rem; padding: 1px 6px; border-radius: 4px;">Kelas ${student.class}</span>
                            ${statusBadge}
                        </div>
                        <small style="color: #64748b; font-size: 0.78rem;">NIS: ${student.nis}</small>
                    </div>
                </label>
            `;
        });

        container.innerHTML = html;
        updateSelectedBadge();
    }

    function toggleSelectAll(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.student-checkbox:not(:disabled)');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
        });
        updateSelectedBadge();
    }

    function updateSelectedBadge() {
        const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
        document.getElementById('selectedCountBadge').innerText = `${checkedCount} Dipilih`;
        const submitBtn = document.getElementById('btnSubmitAddStudents');
        submitBtn.disabled = (checkedCount === 0);
    }

    function confirmRemoveStudent(id, name) {
        Swal.fire({
            title: 'Keluarkan Siswa?',
            text: `Apakah Anda yakin ingin mengeluarkan siswa "${name}" dari kelompok ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Keluarkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('remove-form-' + id).submit();
            }
        });
    }

    // Close modal on click outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('addStudentsModal');
        if (e.target === modal) {
            closeAddStudentsModal();
        }
    });
</script>
@endsection
