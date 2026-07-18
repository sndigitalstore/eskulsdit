@extends('layouts.app')

@section('title', 'Import Excel Satu Pintu')
@section('page-title', 'Import Excel Satu Pintu')

@push('styles')
<style>
    :root {
        --portal-primary: #1a5276;
        --portal-accent:  #2e86c1;
        --portal-success: #1e8449;
        --portal-warning: #d4ac0d;
        --portal-danger:  #c0392b;
    }

    .portal-hero {
        background: linear-gradient(135deg, #1a5276 0%, #2e86c1 60%, #1abc9c 100%);
        border-radius: 16px;
        padding: 2rem 2.5rem;
        color: #fff;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 8px 32px rgba(26,82,118,.25);
    }
    .portal-hero .hero-icon {
        font-size: 3.5rem;
        opacity: .9;
        flex-shrink: 0;
    }
    .portal-hero h2 { margin: 0 0 .4rem; font-size: 1.6rem; font-weight: 700; }
    .portal-hero p  { margin: 0; opacity: .9; font-size: .97rem; }

    /* Grid Layout */
    .portal-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.5rem;
    }
    @media (max-width: 900px) {
        .portal-grid { grid-template-columns: 1fr; }
    }

    /* Cards */
    .portal-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,.07);
        padding: 1.6rem 1.8rem;
        margin-bottom: 1.2rem;
    }
    .portal-card h3 {
        margin: 0 0 1.2rem;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--portal-primary);
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    /* Dropzone */
    .dropzone-area {
        border: 2.5px dashed #aed6f1;
        border-radius: 12px;
        padding: 2.5rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all .25s;
        background: #f0f8ff;
        position: relative;
    }
    .dropzone-area.dragover {
        border-color: var(--portal-accent);
        background: #e0f2fe;
        transform: scale(1.01);
    }
    .dropzone-area:hover { border-color: var(--portal-accent); }
    .dropzone-area i { font-size: 3rem; color: var(--portal-accent); margin-bottom: .7rem; }
    .dropzone-area p  { margin: .3rem 0; color: #555; }
    .dropzone-area .drop-hint { font-size: .83rem; color: #999; }
    #fileName { margin-top: .8rem; font-weight: 600; color: var(--portal-success); display: none; }

    /* Submit Button */
    .btn-import {
        width: 100%;
        padding: .85rem;
        background: linear-gradient(135deg, var(--portal-primary), var(--portal-accent));
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 1.2rem;
        letter-spacing: .5px;
        transition: opacity .2s, transform .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .6rem;
    }
    .btn-import:hover { opacity: .92; transform: translateY(-1px); }
    .btn-import:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    /* Spinner */
    .spinner { display: none; }
    .spinner-border {
        display: inline-block;
        width: 1.2rem; height: 1.2rem;
        border: 3px solid rgba(255,255,255,.4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Template Download */
    .btn-template {
        display: flex;
        align-items: center;
        gap: .6rem;
        width: 100%;
        padding: .7rem 1rem;
        background: #eaf4fb;
        color: var(--portal-primary);
        border: 1.5px solid #aed6f1;
        border-radius: 9px;
        font-size: .9rem;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s;
        cursor: pointer;
    }
    .btn-template:hover { background: #d4e6f1; }

    /* Sheet Info Cards */
    .sheet-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .8rem;
    }
    .sheet-badge {
        background: #f0f8ff;
        border: 1px solid #d1e8f5;
        border-radius: 10px;
        padding: .7rem .9rem;
    }
    .sheet-badge .sb-title {
        font-weight: 700;
        font-size: .85rem;
        color: var(--portal-primary);
        margin-bottom: .3rem;
        display: flex;
        align-items: center;
        gap: .4rem;
    }
    .sheet-badge ul {
        margin: 0; padding-left: 1.1rem;
        font-size: .78rem; color: #666;
        line-height: 1.7;
    }

    /* Log Table */
    .log-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
    .log-table th {
        background: #f0f4f8;
        padding: .6rem .8rem;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #e0e0e0;
    }
    .log-table td {
        padding: .55rem .8rem;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: top;
    }
    .log-table tr:hover td { background: #f7fbff; }
    .log-empty { text-align: center; color: #aaa; padding: 1.5rem; }

    /* Alerts */
    .alert-success, .alert-danger {
        padding: .85rem 1.2rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        font-size: .93rem;
        display: flex;
        align-items: flex-start;
        gap: .7rem;
    }
    .alert-success { background: #d5f5e3; color: #1a5e38; border: 1px solid #a9dfbf; }
    .alert-danger  { background: #fadbd8; color: #922b21; border: 1px solid #f1948a; }
</style>
@endpush

@section('content')

{{-- Alerts --}}
@if(session('success'))
<div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert-danger"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
@endif

{{-- Hero --}}
<div class="portal-hero">
    <div class="hero-icon"><i class="fas fa-file-import"></i></div>
    <div>
        <h2>Import Excel Satu Pintu</h2>
        <p>Unggah satu file Excel berisi semua data sekolah — siswa, nilai, absensi, prestasi, data guru, dan absensi guru — semuanya diproses otomatis dari lembar kerja (sheet) yang sesuai.</p>
    </div>
</div>

<div class="portal-grid">

    {{-- LEFT: Upload Form --}}
    <div>
        <div class="portal-card">
            <h3><i class="fas fa-cloud-upload-alt"></i> Upload File Excel</h3>

            <form id="importForm" method="POST" action="{{ route('import-portal.import') }}" enctype="multipart/form-data">
                @csrf

                {{-- Dropzone --}}
                <div class="dropzone-area" id="dropzone" onclick="document.getElementById('excelInput').click()">
                    <i class="fas fa-file-excel"></i>
                    <p><strong>Klik atau seret file Excel ke sini</strong></p>
                    <p class="drop-hint">Format: .xlsx atau .xls &bull; Maks. 10 MB</p>
                    <div id="fileName"></div>
                </div>
                <input type="file" id="excelInput" name="excel_file" accept=".xlsx,.xls" style="display:none" required>

                @error('excel_file')
                    <p style="color:#c0392b;font-size:.85rem;margin-top:.5rem;"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</p>
                @enderror

                <button type="submit" class="btn-import" id="importBtn">
                    <i class="fas fa-upload"></i>
                    <span id="btnText">Proses Import Data</span>
                    <span class="spinner" id="spinner"><span class="spinner-border"></span></span>
                </button>
            </form>
        </div>

        {{-- Sheet Info --}}
        <div class="portal-card">
            <h3><i class="fas fa-table"></i> Struktur Sheet yang Dikenali</h3>
            <div class="sheet-info-grid">
                <div class="sheet-badge">
                    <div class="sb-title"><i class="fas fa-users" style="color:#2e86c1"></i> Data Siswa</div>
                    <ul>
                        <li>NIS, Nama Lengkap Siswa</li>
                        <li>Kelas, Ekstrakurikuler</li>
                        <li>Pembina (opsional)</li>
                    </ul>
                </div>
                <div class="sheet-badge">
                    <div class="sb-title"><i class="fas fa-star" style="color:#d4ac0d"></i> Nilai</div>
                    <ul>
                        <li>NIS, Nama, Kelas</li>
                        <li>Ekstrakurikuler</li>
                        <li>Nilai Harian, SAS 1, SAS 2</li>
                    </ul>
                </div>
                <div class="sheet-badge">
                    <div class="sb-title"><i class="fas fa-clipboard-check" style="color:#1e8449"></i> Absensi Siswa</div>
                    <ul>
                        <li>NIS, Nama, Kelas</li>
                        <li>Ekstrakurikuler, Tanggal</li>
                        <li>Status, Catatan</li>
                    </ul>
                </div>
                <div class="sheet-badge">
                    <div class="sb-title"><i class="fas fa-trophy" style="color:#e67e22"></i> Data Prestasi</div>
                    <ul>
                        <li>NIS, Nama Siswa, Kelas</li>
                        <li>Nama Prestasi, Tingkat</li>
                        <li>Penyelenggara, Tanggal</li>
                    </ul>
                </div>
                <div class="sheet-badge">
                    <div class="sb-title"><i class="fas fa-chalkboard-teacher" style="color:#8e44ad"></i> Data Guru</div>
                    <ul>
                        <li>Nama Guru, Username</li>
                        <li>Email, No HP</li>
                        <li>Eskul Diampu</li>
                    </ul>
                </div>
                <div class="sheet-badge">
                    <div class="sb-title"><i class="fas fa-user-clock" style="color:#c0392b"></i> Absensi Guru</div>
                    <ul>
                        <li>Nama Guru, Tanggal</li>
                        <li>Waktu, Status</li>
                        <li>Catatan (opsional)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Template Download + Log --}}
    <div>
        <div class="portal-card">
            <h3><i class="fas fa-download"></i> Template Excel</h3>
            <p style="font-size:.88rem;color:#555;margin-bottom:1rem;">Unduh template resmi dengan 6 sheet siap pakai beserta data contoh untuk memandu pengisian data Anda.</p>
            <a href="{{ route('import-portal.template') }}" class="btn-template">
                <i class="fas fa-file-excel" style="color:#1e8449;font-size:1.1rem;"></i>
                Unduh Template Excel (6 Sheet)
            </a>
        </div>

        <div class="portal-card">
            <h3><i class="fas fa-history"></i> Riwayat Import Terakhir</h3>
            @if($recentLogs->isEmpty())
                <p class="log-empty"><i class="fas fa-inbox"></i><br>Belum ada riwayat import.</p>
            @else
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLogs as $log)
                        <tr>
                            <td style="white-space:nowrap;color:#777;">
                                {{ $log->created_at->diffForHumans() }}
                            </td>
                            <td>{{ $log->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="portal-card" style="background:#fffde7;border:1px solid #f9e79f;">
            <h3 style="color:#b7770d;"><i class="fas fa-lightbulb"></i> Tips Import</h3>
            <ul style="font-size:.85rem;color:#7d6608;line-height:1.8;padding-left:1.2rem;margin:0;">
                <li>Nama sheet harus sesuai (misalnya: <strong>Data Siswa</strong>).</li>
                <li>Baris pertama wajib berisi <strong>header kolom</strong>.</li>
                <li>Data Guru baru akan dibuat dengan password default: <code>password123</code>.</li>
                <li>Data yang sudah ada akan <strong>diperbarui</strong>, bukan diduplikat.</li>
                <li>Gunakan <strong>format tanggal</strong>: YYYY-MM-DD atau DD-MM-YYYY.</li>
            </ul>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
// Dropzone drag & drop
const dropzone  = document.getElementById('dropzone');
const fileInput = document.getElementById('excelInput');
const fileLabel = document.getElementById('fileName');

['dragenter','dragover'].forEach(e => dropzone.addEventListener(e, ev => {
    ev.preventDefault(); dropzone.classList.add('dragover');
}));
['dragleave','drop'].forEach(e => dropzone.addEventListener(e, ev => {
    ev.preventDefault(); dropzone.classList.remove('dragover');
}));
dropzone.addEventListener('drop', ev => {
    const f = ev.dataTransfer.files[0];
    if (f) { fileInput.files = ev.dataTransfer.files; showFile(f.name); }
});
fileInput.addEventListener('change', () => {
    if (fileInput.files[0]) showFile(fileInput.files[0].name);
});

function showFile(name) {
    fileLabel.style.display = 'block';
    fileLabel.innerHTML = '<i class="fas fa-file-excel" style="color:#1e8449"></i> ' + name;
}

// Spinner on submit
document.getElementById('importForm').addEventListener('submit', function() {
    const btn = document.getElementById('importBtn');
    document.getElementById('btnText').textContent = 'Sedang Memproses...';
    document.getElementById('spinner').style.display = 'inline-block';
    btn.disabled = true;
});
</script>
@endpush
