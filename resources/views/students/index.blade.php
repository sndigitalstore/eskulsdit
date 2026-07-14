@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="card">
@push('styles')
<style>
    .page-header {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-between;
        align-items: center;
    }
    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .filter-group {
        display: flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
    }
    .filter-option {
        padding: 8px 20px;
        border-radius: 10px;
        text-decoration: none;
        color: #64748b;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .filter-option.active {
        background: white;
        color: #3b82f6;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        font-weight: 600;
    }
    .filter-option:hover:not(.active) {
        color: #333;
    }
    
    .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .search-box {
        position: relative;
    }
    .search-input {
        padding: 10px 15px 10px 40px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.9rem;
        width: 250px;
        transition: all 0.3s;
        background: #f8fafc;
    }
    .search-input:focus {
        background: white;
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    

    
    #searchSuggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
        display: none;
        margin-top: 5px;
    }
    
    .suggestion-item {
        padding: 10px 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s;
    }
    
    .suggestion-item:last-child {
        border-bottom: none;
    }
    
    .suggestion-item:hover {
        background: #f8fafc;
    }
    
    .suggestion-info h4 { margin: 0; font-size: 0.9rem; color: #333; }
    .suggestion-info p { margin: 0; font-size: 0.75rem; color: #888; }
</style>
@endpush

    <div class="page-header">
        <div class="header-left">
            <h2 style="font-size: 1.5rem; margin: 0; color: #1e293b;">Daftar Siswa</h2>
            
            <div class="filter-group">
                <a href="{{ route('students.index', ['status' => 'active']) }}" class="filter-option {{ $status == 'active' ? 'active' : '' }}">
                    Aktif
                </a>
                <a href="{{ route('students.index', ['status' => 'graduated']) }}" class="filter-option {{ $status == 'graduated' ? 'active' : '' }}">
                    Lulus
                </a>
            </div>
        </div>
        
        <div class="header-actions">
            <!-- Search -->
            <form action="{{ route('students.index') }}" method="GET" class="search-box" style="position: relative;">
                <input type="hidden" name="status" value="{{ $status }}">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" name="search" placeholder="Cari siswa..." value="{{ request('search') }}" class="search-input" autocomplete="off" data-api-url="{{ route('api.students.search') }}">
                <div id="searchSuggestions"></div>
            </form>

            @if(Auth::user()->role == 'admin')
                <button id="btnBulkDelete" onclick="confirmBulkDelete()" class="btn-action-header btn-red" style="display: none;">
                    <i class="fas fa-trash"></i> Hapus
                </button>
                
                <a href="{{ route('students.create') }}" class="btn-action-header btn-blue">
                    <i class="fas fa-plus"></i> Tambah
                </a>
                
                <button class="btn-action-header btn-green" onclick="openImportModal()">
                    <i class="fas fa-file-excel"></i> Import
                </button>
                
                <a href="{{ route('students.backup') }}" class="btn-action-header btn-dark">
                    <i class="fas fa-download"></i> Backup
                </a>
                @endif
        </div>
    </div>

    @if(session('success'))
        <div style="background: #e0fbf0; color: #2ecc71; padding: 15px; border-radius: 12px; margin-bottom: 20px; text-align: center; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Bulk Delete Form -->
    <form action="{{ route('students.destroy_bulk') }}" method="POST" id="bulkDeleteForm">
        @csrf
        @method('DELETE')
        
        <table id="studentsTable">
            <thead>
                <tr>
                    <th width="5%" style="text-align: center;">
                        <input type="checkbox" id="checkAll" onclick="toggleAll(this)">
                    </th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Ekstrakurikuler</th>
                    <th>Pembina</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td style="text-align: center;">
                         @if(Auth::user()->role == 'admin')
                        <input type="checkbox" name="ids[]" value="{{ $student->id }}" class="check-item" onclick="toggleBtn()">
                        @else
                        -
                        @endif
                    </td>
                    <td><span style="color: #64748b; font-family: monospace;">{{ $student->nis ?? '-' }}</span></td>
                    <td><strong>{{ $student->name }}</strong></td>
                    <td><span style="background: #eee; padding: 5px 10px; border-radius: 8px; font-size: 0.9rem;">{{ $student->class }}</span></td>
                    <td>
                        @foreach($student->eskuls as $eskul)
                            <span class="badge" style="padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; background: #fff0f5; color: #ff7eb3; font-weight: 600; display: inline-block; margin: 2px;">{{ $eskul->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        @foreach($student->eskuls as $eskul)
                            <div style="font-size: 0.9rem; color: #888;">{{ $eskul->instructor_name ?? '-' }}</div>
                        @endforeach
                    </td>
                    <td>
                        @if(Auth::user()->role == 'admin')
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('students.show', $student->id) }}" style="background: #3498db; color: white; padding: 8px; border-radius: 8px; transition: 0.3s;" title="Lihat Riwayat"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('students.edit', array_merge(['student' => $student->id], request()->all())) }}" style="background: #f1c40f; color: white; padding: 8px; border-radius: 8px; transition: 0.3s;"><i class="fas fa-edit"></i></a>
                            <!-- Individual Delete Form -->
                            <!-- Note: Nested forms are invalid HTML, but here we can just use a separate button that submits a hidden form or use the same form concept. 
                                 However, since we wrapped the whole table in a form for bulk delete, we need to be careful.
                                 The standard way is to NOT wrap the table in a form if we have individual forms.
                                 Let's keep separate individual delete buttons outside the bulk form logic or use Javascript to submit.
                                 
                                 BETTER APPROACH: Don't wrap table in form. Use JS to collect IDs and submit a separate hidden form.
                            -->
                             <button type="button" onclick="deleteIndividual('{{ route('students.destroy', $student->id) }}')" style="background: #e74c3c; color: white; padding: 8px; border: none; border-radius: 8px; cursor: pointer; transition: 0.3s;"><i class="fas fa-trash"></i></button>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
                @if($students->isEmpty())
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: #999;">
                        <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; color: #eee;"></i><br>
                        Belum ada data siswa.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </form>
    
    <div style="margin-top: 20px;">
        {{ $students->withQueryString()->links() }}
    </div>
</div>

<!-- Hidden Form for Individual Delete -->
<form id="individualDeleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<div id="importModal" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000; animation: fadeIn 0.3s; align-items: center; justify-content: center;">
    <div class="card" style="width: 500px; padding: 25px; border-radius: 15px; position: relative; background: white; max-height: 90vh; overflow-y: auto;">
        <h3 style="margin-bottom: 1rem; color: #333;">Import Data Siswa</h3>
        
        <!-- Tabs -->
        <div style="display: flex; gap: 15px; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9;">
            <button type="button" onclick="switchTab('paste')" id="tabBtnPaste" style="border: none; background: none; padding: 10px 0; font-weight: 600; color: #3b82f6; border-bottom: 2px solid #3b82f6; cursor: pointer; transition: 0.3s;">Copy-Paste</button>
            <button type="button" onclick="switchTab('file')" id="tabBtnFile" style="border: none; background: none; padding: 10px 0; font-weight: 600; color: #94a3b8; border-bottom: 2px solid transparent; cursor: pointer; transition: 0.3s;">Upload Excel</button>
        </div>

        <!-- Paste Form -->
        <div id="panelPaste">
            <p style="margin-bottom: 1rem; font-size: 0.9rem; color: #64748b;">
                Copy data dari Excel dan Paste disini.<br>
                <small>Format kolom: <strong>Nama | Kelas | Eskul | Pembina</strong></small>
            </p>
            <form action="{{ route('students.store_bulk') }}" method="POST">
                @csrf
                <textarea name="bulk_data" rows="10" class="form-control" placeholder="Adi | 1A | Futsal | Pak Budi&#10;Budi | 1B | Tari | Bu Siti" style="font-family: monospace; width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; resize: vertical;"></textarea>
                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeImportModal()" style="padding: 10px 20px; border: 1px solid #e2e8f0; background: white; border-radius: 8px; cursor: pointer; color: #64748b;">Batal</button>
                    <button type="submit" style="padding: 10px 20px; border: none; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border-radius: 8px; cursor: pointer; font-weight: 500;">Import Text</button>
                </div>
            </form>
        </div>

        <!-- File Form -->
        <div id="panelFile" style="display: none;">
            <p style="margin-bottom: 1rem; font-size: 0.9rem; color: #64748b;">
                Upload file Excel (.xlsx, .xls) berisi data siswa.<br>
                <small>Header kolom: <strong>Nama, Kelas, Eskul, Pembina</strong></small>
            </p>
            <form action="{{ route('students.import_excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 30px; text-align: center; transition: 0.3s; cursor: pointer;" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-file-excel" style="font-size: 3rem; color: #10b981; margin-bottom: 15px;"></i>
                    <p style="margin: 0; color: #64748b; font-weight: 500;">Klik untuk pilih file Excel</p>
                    <input type="file" id="fileInput" name="file" accept=".xlsx, .xls, .csv" required style="display: none;" onchange="updateFileName(this)">
                    <p id="fileNameDisplay" style="margin-top: 10px; color: #3b82f6; font-size: 0.9rem; font-weight: 600;"></p>
                </div>

                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 10px;">
                     <button type="button" onclick="closeImportModal()" style="padding: 10px 20px; border: 1px solid #e2e8f0; background: white; border-radius: 8px; cursor: pointer; color: #64748b;">Batal</button>
                     <button type="submit" style="padding: 10px 20px; border: none; background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 8px; cursor: pointer; font-weight: 500;">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Confirmation Modal -->
<div id="confirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 2000; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div style="background: white; width: 400px; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); text-align: center; animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
        <div style="width: 80px; height: 80px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: 10px; color: #333;">Konfirmasi Hapus</h3>
        <p id="confirmMessage" style="color: #666; margin-bottom: 25px; line-height: 1.5;">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button onclick="closeConfirmModal()" style="padding: 10px 25px; border-radius: 8px; border: 1px solid #ddd; background: white; color: #555; cursor: pointer; font-weight: 500;">Batal</button>
            <button id="btnConfirmAction" style="padding: 10px 25px; border-radius: 8px; border: none; background: #ef4444; color: white; cursor: pointer; font-weight: 600; box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);">Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
    let confirmCallback = null;

    function toggleAll(source) {
        checkboxes = document.getElementsByClassName('check-item');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
        toggleBtn();
    }

    function toggleBtn() {
        var checkboxes = document.querySelectorAll('.check-item:checked');
        var btn = document.getElementById('btnBulkDelete');
        var textSpan = document.getElementById('btnBulkText');
        
        if(checkboxes.length > 0) {
            btn.style.display = 'inline-flex';
            if(textSpan) {
                textSpan.style.display = 'inline';
                textSpan.innerText = 'Hapus (' + checkboxes.length + ')';
            }
        } else {
            btn.style.display = 'none';
        }
    }

    function showConfirmModal(message, callback) {
        document.getElementById('confirmMessage').innerText = message;
        confirmCallback = callback;
        document.getElementById('confirmModal').style.display = 'flex';
        // Focus on the confirm button for accessibility and immediate Enter key usage
        document.getElementById('btnConfirmAction').focus();
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').style.display = 'none';
        confirmCallback = null;
    }

    document.getElementById('btnConfirmAction').onclick = function() {
        if (confirmCallback) {
            confirmCallback();
        }
        closeConfirmModal();
    };

    // Handle Enter key for confirmation
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            var modal = document.getElementById('confirmModal');
            if (modal.style.display === 'flex' && confirmCallback) {
                event.preventDefault(); // Prevent default form submission elsewhere
                confirmCallback();
                closeConfirmModal();
            }
        }
        if (event.key === 'Escape') {
             closeConfirmModal();
        }
        
        // Shortcut for Bulk Delete
        if (event.key === 'Delete') {
            // Avoid triggering if focus is on input/textarea
            if (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA') return;
            
            var count = document.querySelectorAll('.check-item:checked').length;
            if (count > 0) {
                event.preventDefault();
                confirmBulkDelete();
            }
        }
    });

    function confirmBulkDelete() {
        var count = document.querySelectorAll('.check-item:checked').length;
        if (count === 0) return;
        
        showConfirmModal('Apakah Anda yakin ingin menghapus ' + count + ' data siswa yang dipilih?', function() {
            document.getElementById('bulkDeleteForm').submit();
        });
    }

    function deleteIndividual(url) {
        showConfirmModal('Apakah Anda yakin ingin menghapus data siswa ini secara permanen?', function() {
            var form = document.getElementById('individualDeleteForm');
            form.action = url;
            form.submit();
        });
    }

    // Close modal on outside click
    window.onclick = function(event) {
        var modal = document.getElementById('confirmModal');
        if (event.target == modal) {
            closeConfirmModal();
        }
        var importModal = document.getElementById('importModal');
        if (event.target == importModal) {
            closeImportModal();
        }
    }
    
    function openImportModal() {
        document.getElementById('importModal').style.display = 'flex';
    }

    function closeImportModal() {
        document.getElementById('importModal').style.display = 'none';
        // Reset form if needed or close
    }

    function switchTab(tab) {
        document.getElementById('panelPaste').style.display = tab === 'paste' ? 'block' : 'none';
        document.getElementById('panelFile').style.display = tab === 'file' ? 'block' : 'none';
        
        document.getElementById('tabBtnPaste').style.borderColor = tab === 'paste' ? '#3b82f6' : 'transparent';
        document.getElementById('tabBtnPaste').style.color = tab === 'paste' ? '#3b82f6' : '#94a3b8';
        
        document.getElementById('tabBtnFile').style.borderColor = tab === 'file' ? '#3b82f6' : 'transparent';
        document.getElementById('tabBtnFile').style.color = tab === 'file' ? '#3b82f6' : '#94a3b8';
    }

    function updateFileName(input) {
        var fileName = input.files[0] ? input.files[0].name : '';
        document.getElementById('fileNameDisplay').innerText = fileName ? 'File terpilih: ' + fileName : '';
    }

    // Live Search Logic - Moved to public/js/student-search.js
</script>
<script src="{{ asset('js/student-search.js') }}"></script>
</script>
@endsection
