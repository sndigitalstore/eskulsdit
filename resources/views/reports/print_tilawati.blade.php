<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembinaan Tilawati – {{ $class ? 'Kelas ' . $class : ($groupObj->name ?? 'Semua') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page { 
            size: A4 portrait; 
            margin: 10mm 12mm 12mm 12mm; 
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9.5pt;
            color: #000;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }
        .no-print-bar {
            background: #1e293b;
            color: white;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 9999;
        }
        .btn-print {
            background: #059669;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.9rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-close {
            background: #475569;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
        .page-container {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 15mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .kop-logo { width: 65px; height: auto; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h1 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; color: #000; }
        .kop-text h2 { margin: 2px 0 0 0; font-size: 11pt; font-weight: bold; color: #166534; }
        .kop-text p  { margin: 2px 0 0 0; font-size: 8pt; font-style: italic; color: #333; }
        .title-box { text-align: center; margin: 10px 0 14px; }
        .title-box h3 { margin: 0; font-size: 11pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; letter-spacing: 0.5px; }
        .title-box p  { margin: 4px 0 0; font-size: 9pt; font-family: Arial, sans-serif; color: #334155; }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 12px;
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            margin-bottom: 12px;
        }
        .info-grid div { line-height: 1.4; }

        .report-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-family: Arial, sans-serif; 
            font-size: 8pt; 
            margin-bottom: 12px; 
        }
        .report-table th {
            background: #ecfdf5 !important;
            color: #064e3b;
            font-weight: bold;
            text-align: center;
            border: 1px solid #94a3b8;
            padding: 6px 4px;
        }
        .report-table td { 
            border: 1px solid #cbd5e1; 
            padding: 5px 6px; 
            vertical-align: middle; 
            line-height: 1.25;
        }
        .report-table tr:nth-child(even) td { background: #f8fafc; }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: bold;
        }
        .badge-green { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .badge-yellow { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
        .badge-gray { background: #f1f5f9; color: #475569; }

        .signature-section { 
            margin-top: 24px; 
            font-family: 'Times New Roman', serif; 
            font-size: 9.5pt; 
            page-break-inside: avoid; 
        }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-table td { border: none; text-align: center; width: 33.33%; vertical-align: top; padding: 0 10px; }

        @media print {
            .no-print-bar { display: none !important; }
            body { background: white; }
            .page-container { width: 100%; margin: 0; padding: 0; box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <span style="font-family: sans-serif; font-weight: bold; font-size: 0.95rem;">
            <i class="fas fa-book-reader" style="color: #34d399;"></i>
            Laporan Pembinaan Tilawati – {{ $class ? 'Kelas ' . $class : ($groupObj->name ?? 'Seluruh Siswa') }} ({{ $students->count() }} Siswa)
        </span>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Cetak / Simpan PDF</button>
            <button onclick="window.close()" class="btn-close">Tutup</button>
        </div>
    </div>

    @php
        $currentDate = \Carbon\Carbon::now()->isoFormat('D MMMM Y');
        $headmasterName = \App\Models\Setting::where('key','headmaster_name')->value('value')
            ?? optional(\App\Models\User::where('role','headmaster')->first())->name
            ?? "Nur'asiah, S.Pd.I";
        $firstGroup = $groupObj ?? ($students->first() ? $students->first()->mentoringGroups->first() : null);
        $mentorName = $firstGroup ? ($firstGroup->mentorUser?->name ?? $firstGroup->mentor_name ?? '-') : '-';
    @endphp

    <div class="page-container">
        <!-- KOP SURAT RESMI -->
        <div class="kop-surat">
            <img src="{{ asset('logo.png') }}" class="kop-logo" alt="Logo">
            <div class="kop-text">
                <h1>SDIT AN NADZIR</h1>
                <h2>SISTEM PEMBINAAN QUR'AN & PRESTASI TERPADU (SIM SMILE)</h2>
                <p style="font-size: 8.5pt; color: #475569; margin: 1px 0;">NNS: 101280432037 · NPSN: 20616174</p>
                <p>Bendungan Karet Cisirih KP. Cukang RT. 09 RW. 02 Desa Kamasan Kecamatan Cinangka Kabupaten Serang - Banten 42167</p>
            </div>
        </div>

        <!-- JUDUL LAPORAN -->
        <div class="title-box">
            <h3>LAPORAN PERKEMBANGAN & CAPAIAN PEMBINAAN TILAWATI</h3>
            <p>Tahun Pelajaran {{ $yearName }} • {{ $class ? 'Kelas ' . $class : 'Seluruh Kelas' }}</p>
        </div>

        <!-- INFORMASI / METADATA -->
        <div class="info-grid">
            <div>
                <strong>Satuan Pendidikan:</strong> SDIT AN NADZIR<br>
                <strong>Tahun Pelajaran:</strong> {{ $yearName }}<br>
                <strong>Kelas / Rombel:</strong> {{ $class ? 'Kelas ' . $class : ($groupObj ? 'Kelompok ' . $groupObj->name : 'Semua') }}
            </div>
            <div>
                <strong>Pengampu / Musyrif:</strong> {{ $groupObj ? ($groupObj->mentorUser?->name ?? $groupObj->mentor_name ?? '-') : 'Koordinator Tilawati' }}<br>
                <strong>Wali Kelas:</strong> {{ $homeroomTeacher ? $homeroomTeacher->name : '-' }}<br>
                <strong>Tanggal Dokumen:</strong> {{ $currentDate }}
            </div>
        </div>

        <!-- TABEL RINCIAN SISWA -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 11%;">NIS</th>
                    <th style="width: 23%; text-align: left;">Nama Lengkap Siswa</th>
                    <th style="width: 7%;">Kelas</th>
                    <th style="width: 17%; text-align: left;">Kelompok & Ustadz/ah</th>
                    <th style="width: 11%;">Target Jilid</th>
                    <th style="width: 13%;">Capaian Terakhir</th>
                    <th style="width: 14%;">Catatan Pembina</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $idx => $student)
                    @php
                        $group = $student->mentoringGroups->first();
                    @endphp
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                        <td style="text-align: center; font-family: monospace;">{{ $student->nis ?? '-' }}</td>
                        <td>
                            <strong>{{ $student->name }}</strong>
                        </td>
                        <td style="text-align: center; font-weight: bold;">{{ $student->class }}</td>
                        <td>
                            @if($group)
                                <div style="font-weight: 600; color: #065f46;">{{ $group->name }}</div>
                                <small style="color: #64748b; font-size: 7pt;">
                                    <i class="fas fa-user"></i> {{ $group->mentorUser?->name ?? $group->mentor_name ?? '-' }}
                                </small>
                            @else
                                <span style="color: #94a3b8; font-style: italic;">Belum Berkelompok</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($group && $group->target_level)
                                <span class="badge badge-yellow">{{ $group->target_level }}</span>
                            @else
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($group && $group->pivot && $group->pivot->notes)
                                <span class="badge badge-green">{{ $group->pivot->notes }}</span>
                            @else
                                <span style="color: #94a3b8; font-style: italic;">Sedang Berjalan</span>
                            @endif
                        </td>
                        <td style="font-size: 7.5pt; color: #334155;">
                            {{ $group && $group->notes ? $group->notes : 'Mengikuti bimbingan rutin sesuai target jilid.' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 25px; color: #94a3b8; font-style: italic;">
                            Tidak ada data siswa untuk kriteria filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- TANDA TANGAN AKUNTABEL BERTINGKAT (3 PIHAK) -->
        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        Mengetahui,<br>
                        <strong>Kepala Sekolah</strong><br>
                        <div style="height: 60px;"></div>
                        <strong style="text-decoration: underline;">{{ $headmasterName }}</strong><br>
                        <span style="font-size: 8pt; color: #555;">NIP/NPY. SDIT-AN-201801</span>
                    </td>
                    <td>
                        Diperiksa oleh,<br>
                        <strong>Wali Kelas {{ $class ?? '' }}</strong><br>
                        <div style="height: 60px;"></div>
                        <strong style="text-decoration: underline;">{{ $homeroomTeacher ? $homeroomTeacher->name : '....................................' }}</strong><br>
                        <span style="font-size: 8pt; color: #555;">Guru Wali Kelas</span>
                    </td>
                    <td>
                        Cinangka, {{ $currentDate }}<br>
                        <strong>Pembina / Koord. Tilawati</strong><br>
                        <div style="height: 60px;"></div>
                        <strong style="text-decoration: underline;">{{ $mentorName !== '-' ? $mentorName : '....................................' }}</strong><br>
                        <span style="font-size: 8pt; color: #555;">Musyrif / Pembina Tilawati</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
