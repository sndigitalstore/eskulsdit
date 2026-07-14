@extends('layouts.app')

@section('title', 'Import Nilai Masal')
@section('page-title', 'Import Nilai Masal')

@section('content')
@section('content')
<div class="card" style="max-width: 600px; margin: 2rem auto; text-align: center;">
    <div style="margin-bottom: 2rem;">
        <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <i class="fas fa-magic" style="font-size: 2rem; color: #fff;"></i>
        </div>
        <h2 style="margin-bottom: 10px;">Smart Import Nilai</h2>
        <p style="color: #666; max-width: 400px; margin: 0 auto; line-height: 1.6;">
            Tidak perlu pusing memilih pengaturan. Cukup upload file Excel data nilai Anda, sistem cerdas kami yang akan mengatur sisanya.
        </p>
    </div>

    @if(session('success'))
        <div style="background: #e0fbf0; color: #2ecc71; padding: 15px; border-radius: 12px; margin-bottom: 20px; text-align: left; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #ffe0e0; color: #e74c3c; padding: 15px; border-radius: 12px; margin-bottom: 20px; text-align: left; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div style="background: #fff; padding: 30px; border-radius: 20px; border: 2px dashed #3498db; position: relative; transition: all 0.3s; cursor: pointer;" onclick="document.getElementById('fileInput').click()">
        <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: #3498db; margin-bottom: 15px;"></i>
        <h3 style="margin-bottom: 5px;">Upload File Excel</h3>
        <p style="color: #999; font-size: 0.9rem;">Klik di sini atau drag & drop file Anda</p>
        
        <form id="uploadForm" action="{{ route('grades.import.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" id="fileInput" name="file" accept=".xlsx, .xls" style="display: none;" onchange="this.form.submit()">
        </form>
    </div>

    <div style="margin-top: 30px; text-align: left; background: #f9f9f9; padding: 20px; border-radius: 12px;">
        <h4 style="margin-bottom: 10px; font-size: 0.95rem; color: #333;"><i class="fas fa-info-circle" style="color: #3498db;"></i> Pastikan Format Excel Anda:</h4>
        <ul style="padding-left: 20px; color: #555; font-size: 0.9rem; line-height: 1.6;">
            <li>Memiliki kolom <strong>Nama Lengkap</strong> (untuk identifikasi siswa).</li>
            <li>Memiliki kolom <strong>Ekstrakurikuler</strong> (sistem akan mencari nama eskul yang cocok).</li>
            <li>Memiliki kolom <strong>SAS 1</strong> atau <strong>SAS 2</strong> untuk nilai.</li>
        </ul >
        <div style="margin-top: 10px; padding: 10px; background: #fff; border: 1px solid #eee; border-radius: 8px; font-family: monospace; font-size: 0.8rem; overflow-x: auto;">
            | Nama Lengkap | Ekstrakurikuler | SAS 1 | SAS 2 |<br>
            | Budi Santoso | Badminton       | 85    | 90    |
        </div>
    </div>

    <div style="margin-top: 30px;">
        <a href="{{ route('grades.index') }}" style="color: #888; text-decoration: none; font-size: 0.9rem;">
            &larr; Kembali ke Menu Nilai
        </a>
    </div>
</div>
@endsection
