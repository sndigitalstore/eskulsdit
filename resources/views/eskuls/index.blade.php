@extends('layouts.app')

@section('title', 'Daftar Eskul')
@section('page-title', 'Ekstrakurikuler')

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
        background-color: rgba(0,0,0,0.5); 
        backdrop-filter: blur(5px);
        animation: fadeIn 0.3s;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background-color: #fefefe;
        border: 1px solid #888;
        width: 90%;
        max-width: 600px;
        border-radius: 15px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        position: relative;
        animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes popIn {
        0% { opacity: 0; transform: scale(0.9); }
        100% { opacity: 1; transform: scale(1); }
    }
    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-body {
        padding: 1.5rem;
        max-height: 60vh;
        overflow-y: auto;
    }
    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #eee;
        text-align: right;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .action-btn {
        padding: 8px;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        transition: 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-view { background: #3498db; }
    .btn-edit { background: #f1c40f; }
    .btn-export { background: #2ecc71; }
    .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover { color: black; }
</style>
@endpush

@section('content')
    <div class="card" style="margin-bottom: 25px;">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>Kegiatan Ekstrakurikuler</h2>
                <p style="color: #888; margin: 0;">Daftar kegiatan, jadwal, dan pembina.</p>
            </div>
            <div style="display: flex; gap: 8px;">
                <button onclick="openModal('create-eskul-modal')" class="btn-action-header btn-blue">
                    <i class="fas fa-plus"></i> Tambah
                </button>
                <button onclick="openModal('update-schedule-modal')" class="btn-action-header btn-orange">
                    <i class="fas fa-clock"></i> Atur Jadwal
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #e0fbf0; color: #2ecc71; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Card Grid Loop -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; margin-bottom: 50px;">
        @foreach($eskuls as $eskul)
        <div class="card" style="padding: 0; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s; background: white;">
            @php
                $currentHistory = $eskul->histories->first();
                $displayName = ($currentHistory && $currentHistory->alias_name) ? $currentHistory->alias_name : $eskul->name;
                $displayInstructor = $currentHistory ? $currentHistory->instructor_name : $eskul->instructor_name;
                $displaySchedule = $currentHistory ? $currentHistory->schedule : $eskul->schedule;
            @endphp

            <!-- Card Header with Gradient -->
            <div style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); padding: 12px 16px; color: white; position: relative;">
                <div style="position: absolute; top: 12px; right: 12px; background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                    <i class="fas fa-users"></i> {{ $eskul->students->count() }}
                </div>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 700; padding-right: 45px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $displayName }}">{{ $displayName }}</h3>
                @if($eskul->name != $displayName)
                <div style="font-size: 0.75rem; opacity: 0.8; font-style: italic; margin-top: 2px;">
                    ({{ $eskul->name }})
                </div>
                @endif
                <div style="margin-top: 4px; display: inline-flex; gap: 4px; margin-bottom: 2px;">
                    @if($eskul->target_group == 'sesi_1')
                        <span style="font-size: 0.65rem; background: #fffbeb; color: #b45309; padding: 2px 6px; border-radius: 4px; font-weight: 700; border: 0.5px solid #fde68a;">Sesi 1: Kelas 1</span>
                    @elseif($eskul->target_group == 'sesi_2')
                        <span style="font-size: 0.65rem; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-weight: 700; border: 0.5px solid #bae6fd;">Sesi 2: Kelas 2-3</span>
                    @elseif($eskul->target_group == 'sesi_3')
                        <span style="font-size: 0.65rem; background: #ecfdf5; color: #047857; padding: 2px 6px; border-radius: 4px; font-weight: 700; border: 0.5px solid #a7f3d0;">Sesi 3: Kelas 4-6</span>
                    @else
                        <span style="font-size: 0.65rem; background: #f3f4f6; color: #4b5563; padding: 2px 6px; border-radius: 4px; font-weight: 700; border: 0.5px solid #e5e7eb;">Semua Kelas</span>
                    @endif
                </div>
                <div style="display: flex; align-items: center; gap: 6px; margin-top: 6px; font-size: 0.8rem; opacity: 0.9;">
                    <i class="far fa-calendar-alt"></i> 
                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $displaySchedule ?? 'Jadwal belum diatur' }}">{{ $displaySchedule ?? 'Jadwal belum diatur' }}</span>
                </div>
            </div>

            <div style="padding: 12px 16px; flex-grow: 1;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 36px; height: 36px; background: #f0f7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: bold; color: #3498db; border: 2px solid #eef2ff; flex-shrink: 0;">
                        {{ substr($displayInstructor ?? '?', 0, 1) }}
                    </div>
                    <div style="min-width: 0;">
                        <div style="font-size: 0.65rem; color: #888; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Pembina</div>
                        <div style="font-weight: 600; color: #333; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $displayInstructor ?? 'Belum ditentukan' }}">{{ $displayInstructor ?? 'Belum ditentukan' }}</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="padding: 10px 16px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; gap: 6px;">
                <button onclick="openModal('view-{{ $eskul->id }}')" style="flex: 1; padding: 6px; background: white; border: 1px solid #cbd5e1; color: #475569; border-radius: 6px; font-weight: 600; font-size: 0.75rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 4px;">
                     <i class="fas fa-eye" style="color: #3498db;"></i> Detail
                </button>
                <button onclick="openModal('edit-{{ $eskul->id }}')" style="padding: 6px 10px; background: #fffbeb; border: 1px solid #fcd34d; color: #d97706; border-radius: 6px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center;" title="Edit">
                     <i class="fas fa-edit"></i>
                </button>
                <button 
                    data-url="{{ route('eskuls.destroy', $eskul->id) }}" 
                    data-name="{{ $eskul->name }}"
                    onclick="openDeleteModal(this.dataset.url, this.dataset.name)" 
                    style="padding: 6px 10px; background: #fef2f2; border: 1px solid #fca5a5; color: #dc2626; border-radius: 6px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center;" title="Hapus">
                     <i class="fas fa-trash"></i>
                </button>
                <a href="{{ route('eskuls.export', $eskul->id) }}" style="flex: 1; padding: 6px; background: #10b981; border: none; color: white; border-radius: 6px; font-weight: 600; font-size: 0.75rem; cursor: pointer; transition: all 0.2s; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 4px;">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </div>
        </div>
        @endforeach
    </div>

<!-- Render Modals Outside Table -->
@foreach($eskuls as $eskul)
    @php
        $currentHistory = $eskul->histories->first();
        $displayInstructor = $currentHistory ? $currentHistory->instructor_name : $eskul->instructor_name;
        $displaySchedule = $currentHistory ? $currentHistory->schedule : $eskul->schedule;
        $displayName = ($currentHistory && $currentHistory->alias_name) ? $currentHistory->alias_name : $eskul->name;
    @endphp
    <!-- View Students Modal -->
    <div id="view-{{ $eskul->id }}" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Daftar Siswa - {{ $eskul->name }}</h3>
                <span class="close" onclick="closeModal('view-{{ $eskul->id }}')">&times;</span>
            </div>
            <div class="modal-body">
                <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <small style="color: #888; display: block;">Pembina</small>
                        <strong style="color: #333;">{{ $displayInstructor ?? 'Belum ditentukan' }}</strong>
                    </div>
                    <div>
                        <small style="color: #888; display: block;">Jadwal</small>
                        <strong style="color: #333;">{{ $displaySchedule ?? '-' }}</strong>
                    </div>
                </div>

                @if($eskul->students->isEmpty())
                    <p style="text-align: center; color: #888;">Belum ada siswa yang mendaftar.</p>
                @else
                    <table style="margin-top: 0;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eskul->students as $idx => $student)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->class }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="modal-footer">
                <button onclick="closeModal('view-{{ $eskul->id }}')" class="btn-submit" style="background: #eee; color: #333; box-shadow: none;">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Edit Eskul Modal -->
    <div id="edit-{{ $eskul->id }}" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Ekstrakurikuler</h3>
                <span class="close" onclick="closeModal('edit-{{ $eskul->id }}')">&times;</span>
            </div>
            <form action="{{ route('eskuls.update', $eskul->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div style="background: #eef2ff; color: #4338ca; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 0.85rem;">
                        <i class="fas fa-info-circle"></i> Perubahan nama pembina & jadwal hanya akan berlaku untuk <strong>Semester Aktif</strong> saat ini tanpa mengubah data semester lalu.
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Istilah Nama (Alias)</label>
                        <input type="text" name="alias_name" class="form-control" value="{{ $displayName }}" placeholder="Contoh: Karate Kelas Kecil">
                        <small style="color: #888;">Nama yang akan tampil di semester ini saja.</small>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Eskul Utama (Sistem)</label>
                        <input type="text" name="name" class="form-control" value="{{ $eskul->name }}" required>
                        <small style="color: #888;">Gunakan nama tetap untuk database.</small>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Sesi Pendaftaran / Sasaran Kelas</label>
                        @php $currentGroups = $eskul->target_groups; @endphp
                        <div style="display: flex; flex-direction: column; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: #fafafa;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                <input type="checkbox" name="target_group[]" value="all" {{ in_array('all', $currentGroups) ? 'checked' : '' }} style="width:16px;height:16px;" onchange="handleAllCheckbox(this, 'edit-{{ $eskul->id }}')">
                                <span>Semua Kelas (Umum)</span>
                            </label>
                            <hr style="margin: 2px 0; border-color: #eee;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                <input type="checkbox" name="target_group[]" value="sesi_1" {{ in_array('sesi_1', $currentGroups) ? 'checked' : '' }} style="width:16px;height:16px;" class="sesi-cb-edit-{{ $eskul->id }}">
                                <span>Sesi 1: Kelas 1</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                <input type="checkbox" name="target_group[]" value="sesi_2" {{ in_array('sesi_2', $currentGroups) ? 'checked' : '' }} style="width:16px;height:16px;" class="sesi-cb-edit-{{ $eskul->id }}">
                                <span>Sesi 2: Kelas 2</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                <input type="checkbox" name="target_group[]" value="sesi_3" {{ in_array('sesi_3', $currentGroups) ? 'checked' : '' }} style="width:16px;height:16px;" class="sesi-cb-edit-{{ $eskul->id }}">
                                <span>Sesi 3: Kelas 3</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                <input type="checkbox" name="target_group[]" value="sesi_4" {{ in_array('sesi_4', $currentGroups) ? 'checked' : '' }} style="width:16px;height:16px;" class="sesi-cb-edit-{{ $eskul->id }}">
                                <span>Sesi 4: Kelas Besar (Kelas 4-6)</span>
                            </label>
                        </div>
                        <small style="color: #888;">Pilih satu atau lebih sesi. Centang "Semua Kelas" untuk tidak membatasi.</small>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Pembina</label>
                        <input type="text" name="instructor_name" class="form-control" value="{{ $displayInstructor }}">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jadwal Latihan</label>
                        <input type="text" name="schedule" class="form-control" value="{{ $displaySchedule }}">
                    </div>
                    
                    <div style="background: #fff3cd; border: 1px solid #ffeeba; padding: 10px; border-radius: 8px; margin-top: 15px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" name="is_lockable" value="1" {{ $eskul->is_lockable ? 'checked' : '' }} style="width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #856404; display: block;">Modus Penguncian (Wajib Lulus)</strong>
                                <small style="color: #856404;">Jika aktif, siswa di eskul ini <strong>TIDAK BISA PINDAH</strong> kecuali jika mendapat semua nilai "A". (Cocok untuk Calistung)</small>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('edit-{{ $eskul->id }}')" class="btn-submit" style="background: #eee; color: #333; box-shadow: none; margin-right: 10px;">Batal</button>
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>


@endforeach

    <!-- Create Eskul Modal -->
    <div id="create-eskul-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Ekstrakurikuler</h3>
                <span class="close" onclick="closeModal('create-eskul-modal')">&times;</span>
            </div>
            <form action="{{ route('eskuls.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Eskul Utama</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Karate A" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Istilah Nama (Alias) - Opsional</label>
                        <input type="text" name="alias_name" class="form-control" placeholder="Contoh: Karate Kelas Kecil">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Sesi Pendaftaran / Sasaran Kelas</label>
                        <div style="display: flex; flex-direction: column; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: #fafafa;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                <input type="checkbox" name="target_group[]" value="all" checked style="width:16px;height:16px;" onchange="handleAllCheckbox(this, 'create')">
                                <span>Semua Kelas (Umum)</span>
                            </label>
                            <hr style="margin: 2px 0; border-color: #eee;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                <input type="checkbox" name="target_group[]" value="sesi_1" style="width:16px;height:16px;" class="sesi-cb-create">
                                <span>Sesi 1: Kelas 1</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                <input type="checkbox" name="target_group[]" value="sesi_2" style="width:16px;height:16px;" class="sesi-cb-create">
                                <span>Sesi 2: Kelas 2</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                <input type="checkbox" name="target_group[]" value="sesi_3" style="width:16px;height:16px;" class="sesi-cb-create">
                                <span>Sesi 3: Kelas 3</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal;">
                                <input type="checkbox" name="target_group[]" value="sesi_4" style="width:16px;height:16px;" class="sesi-cb-create">
                                <span>Sesi 4: Kelas Besar (Kelas 4-6)</span>
                            </label>
                        </div>
                        <small style="color: #888;">Pilih satu atau lebih sesi. Centang "Semua Kelas" untuk tidak membatasi.</small>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Pembina</label>
                        <input type="text" name="instructor_name" class="form-control" placeholder="Nama Pembina">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jadwal Latihan</label>
                        <input type="text" name="schedule" class="form-control" placeholder="Contoh: Sabtu, 08.00 WIB">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('create-eskul-modal')" class="btn-submit" style="background: #eee; color: #333; box-shadow: none; margin-right: 10px;">Batal</button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="modal">
        <div class="modal-content" style="max-width: 400px; text-align: center; padding: 30px;">
            <div style="margin-bottom: 20px;">
                <div style="width: 80px; height: 80px; background: #fee2e2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #dc2626; font-size: 35px;">
                    <i class="fas fa-trash-alt"></i>
                </div>
            </div>
            <h3 style="margin-bottom: 10px; color: #333;">Hapus Eskul?</h3>
            <p style="color: #666; margin-bottom: 25px; line-height: 1.5;">Anda yakin ingin menghapus <strong id="delete-name"></strong>?<br>Data siswa dan nilai di eskul ini juga akan terhapus.</p>
            
            <form id="delete-form" action="" method="POST">
                @csrf
                @method('DELETE')
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button type="button" onclick="closeModal('delete-modal')" class="btn-submit" style="background: #f3f4f6; color: #374151; box-shadow: none; flex: 1;">Batal</button>
                    <button type="submit" class="btn-submit" style="background: #dc2626; flex: 1; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Update Schedule Modal -->
    <div id="update-schedule-modal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3>Atur Jadwal Serentak</h3>
                <span class="close" onclick="closeModal('update-schedule-modal')">&times;</span>
            </div>
            <form action="{{ route('eskuls.bulk-update-schedule') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div style="background: #fdf2f8; color: #be185d; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Jadwal ini HANYA akan diterapkan pada eskul yang memiliki siswa aktif di tahun ajaran ini.
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Hari & Waktu</label>
                        <input type="text" name="schedule" class="form-control" placeholder="Contoh: Rabu, Pukul 13.00 - 15.00 WIB" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('update-schedule-modal')" class="btn-submit" style="background: #eee; color: #333; box-shadow: none; width: auto; margin-right: 10px;">Batal</button>
                    <button type="submit" class="btn-submit" style="background: #8e44ad; width: auto;">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    
    function openDeleteModal(url, name) {
        document.getElementById('delete-form').action = url;
        document.getElementById('delete-name').innerText = name;
        openModal('delete-modal');
    }
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    }

    /**
     * Jika checkbox 'Semua Kelas' dicentang → uncheck semua sesi spesifik.
     * Jika checkbox sesi spesifik dicentang → uncheck 'Semua Kelas'.
     */
    function handleAllCheckbox(checkbox, formKey) {
        if (checkbox.value === 'all' && checkbox.checked) {
            // Uncheck semua sesi spesifik
            document.querySelectorAll('.sesi-cb-' + formKey).forEach(cb => cb.checked = false);
        }
    }

    // Tambahkan listener untuk setiap sesi spesifik agar uncheck 'all' jika dipilih
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input[type="checkbox"][name="target_group[]"]').forEach(function(cb) {
            if (cb.value !== 'all') {
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        // Cari checkbox 'all' dalam form/modal yang sama
                        var container = this.closest('div[style*="flex-direction: column"]');
                        if (container) {
                            var allCb = container.querySelector('input[value="all"]');
                            if (allCb) allCb.checked = false;
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
