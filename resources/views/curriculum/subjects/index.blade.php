@extends('layouts.app')

@section('title', 'Manajemen Mata Pelajaran – SMILE Kurikulum')
@section('page-title', 'Mata Pelajaran')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">

    <!-- TOP HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="margin: 0; font-size: 1.3rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-book" style="color: #0284c7;"></i> Kelola Mata Pelajaran (Kurikulum Merdeka)
            </h3>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.88rem;">
                Daftar mata pelajaran aktif, kategori kurikulum, dan nilai Kriteria Ketercapaian Tujuan Pembelajaran (KKTP).
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('academic-reports.index') }}" class="btn-action-header" style="background: white; border: 1px solid #cbd5e1; color: #475569; font-weight: 700; padding: 10px 18px; border-radius: 12px; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Kembali ke Rapor
            </a>
            <button type="button" class="btn-action-header btn-blue" onclick="openAddModal()" style="background: #0284c7; font-weight: 700; padding: 10px 18px; border-radius: 12px;">
                <i class="fas fa-plus"></i> Tambah Mapel
            </button>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; border-left: 4px solid #16a34a; padding: 14px 18px; border-radius: 10px; color: #166534; font-weight: 600; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 14px 18px; border-radius: 10px; color: #991b1b; font-weight: 600; margin-bottom: 20px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- SUBJECTS TABLE -->
    <div class="card" style="padding: 0; overflow: hidden; border-radius: 14px;">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse; margin: 0; font-size: 0.88rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569;">
                        <th style="padding: 12px 14px; text-align: center; width: 50px;">No</th>
                        <th style="padding: 12px 14px; text-align: center; width: 90px;">Kode</th>
                        <th style="padding: 12px 14px; text-align: left;">Nama Mata Pelajaran</th>
                        <th style="padding: 12px 14px; text-align: left; width: 180px;">Kelompok / Kategori</th>
                        <th style="padding: 12px 14px; text-align: center; width: 90px;">KKTP</th>
                        <th style="padding: 12px 14px; text-align: center; width: 75px;">Urutan</th>
                        <th style="padding: 12px 14px; text-align: center; width: 90px;">Status</th>
                        <th style="padding: 12px 14px; text-align: center; width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $index => $sb)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px 14px; text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                            <td style="padding: 12px 14px; text-align: center;">
                                <span style="background: #f1f5f9; padding: 3px 8px; border-radius: 6px; font-weight: 800; color: #0284c7; font-size: 0.8rem;">
                                    {{ $sb->code ?: '-' }}
                                </span>
                            </td>
                            <td style="padding: 12px 14px; font-weight: 700; color: #0f172a;">
                                {{ $sb->name }}
                            </td>
                            <td style="padding: 12px 14px; color: #475569;">
                                <span style="display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 0.78rem; font-weight: 600; background: {{ $sb->category === 'Wajib / Nasional' ? '#e0f2fe' : ($sb->category === 'Khas SDIT / Diniyah' ? '#dcfce7' : '#fef3c7') }}; color: {{ $sb->category === 'Wajib / Nasional' ? '#0369a1' : ($sb->category === 'Khas SDIT / Diniyah' ? '#15803d' : '#b45309') }};">
                                    {{ $sb->category }}
                                </span>
                            </td>
                            <td style="padding: 12px 14px; text-align: center; font-weight: 800; color: #1e293b;">
                                {{ (int)$sb->kkm }}
                            </td>
                            <td style="padding: 12px 14px; text-align: center; color: #64748b;">
                                {{ $sb->order_num }}
                            </td>
                            <td style="padding: 12px 14px; text-align: center;">
                                @if($sb->is_active)
                                    <span style="color: #16a34a; font-weight: 700; font-size: 0.8rem;"><i class="fas fa-check-circle"></i> Aktif</span>
                                @else
                                    <span style="color: #94a3b8; font-weight: 600; font-size: 0.8rem;">Nonaktif</span>
                                @endif
                            </td>
                            <td style="padding: 12px 14px; text-align: center;">
                                <button type="button" 
                                        class="btn btn-sm" 
                                        style="background: #f1f5f9; color: #0284c7; border: 1px solid #cbd5e1; padding: 4px 8px; border-radius: 6px; font-weight: 700; margin-right: 4px;"
                                        onclick="openEditModal({{ json_encode($sb) }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('subjects.destroy', $sb->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran {{ $sb->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; padding: 4px 8px; border-radius: 6px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding: 40px; text-align: center; color: #94a3b8;">
                                Belum ada mata pelajaran terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL TAMBAH / EDIT MAPEL -->
<div id="subjectModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 16px; width: 100%; max-width: 520px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);">
        <div style="padding: 18px 24px; background: #0284c7; color: white; display: flex; justify-content: space-between; align-items: center;">
            <h4 id="modalTitle" style="margin: 0; font-size: 1.1rem; font-weight: 800;">Tambah Mata Pelajaran</h4>
            <button type="button" onclick="closeModal()" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>

        <form id="subjectForm" method="POST" action="{{ route('subjects.store') }}" style="padding: 24px;">
            @csrf
            <div id="methodContainer"></div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.85rem;">Nama Mata Pelajaran *</label>
                <input type="text" name="name" id="subjName" class="form-control" required placeholder="Contoh: Matematika">
            </div>

            <div style="display: flex; gap: 14px; margin-bottom: 16px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.85rem;">Kode Singkat</label>
                    <input type="text" name="code" id="subjCode" class="form-control" placeholder="Contoh: MAT">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.85rem;">KKTP / KKM *</label>
                    <input type="number" step="0.01" name="kkm" id="subjKkm" class="form-control" value="75" required>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.85rem;">Kelompok / Kategori *</label>
                <select name="category" id="subjCategory" class="form-control" required>
                    <option value="Wajib / Nasional">Wajib / Nasional</option>
                    <option value="Muatan Lokal">Muatan Lokal</option>
                    <option value="Khas SDIT / Diniyah">Khas SDIT / Diniyah</option>
                    <option value="Pengembangan Diri">Pengembangan Diri</option>
                </select>
            </div>

            <div style="display: flex; gap: 14px; margin-bottom: 20px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #475569; font-size: 0.85rem;">Urutan Tampil (No)</label>
                    <input type="number" name="order_num" id="subjOrder" class="form-control" value="0">
                </div>
                <div style="flex: 1; display: flex; align-items: center; padding-top: 24px;">
                    <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; color: #475569; cursor: pointer;">
                        <input type="checkbox" name="is_active" id="subjActive" value="1" checked style="width: 18px; height: 18px;">
                        Mata Pelajaran Aktif
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal()" class="btn btn-secondary" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn btn-primary" style="background: #0284c7; border-radius: 8px; font-weight: 700;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
var modal = document.getElementById('subjectModal');
var form = document.getElementById('subjectForm');
var title = document.getElementById('modalTitle');
var methodContainer = document.getElementById('methodContainer');

function openAddModal() {
    title.innerText = 'Tambah Mata Pelajaran';
    form.action = "{{ route('subjects.store') }}";
    methodContainer.innerHTML = '';
    document.getElementById('subjName').value = '';
    document.getElementById('subjCode').value = '';
    document.getElementById('subjKkm').value = '75';
    document.getElementById('subjCategory').value = 'Wajib / Nasional';
    document.getElementById('subjOrder').value = '0';
    document.getElementById('subjActive').checked = true;
    modal.style.display = 'flex';
}

function openEditModal(subject) {
    title.innerText = 'Edit Mata Pelajaran: ' + subject.name;
    form.action = '/curriculum/subjects/' + subject.id;
    methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('subjName').value = subject.name;
    document.getElementById('subjCode').value = subject.code || '';
    document.getElementById('subjKkm').value = parseFloat(subject.kkm);
    document.getElementById('subjCategory').value = subject.category;
    document.getElementById('subjOrder').value = subject.order_num;
    document.getElementById('subjActive').checked = subject.is_active == 1;
    modal.style.display = 'flex';
}

function closeModal() {
    modal.style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == modal) {
        closeModal();
    }
}
</script>
@endsection
