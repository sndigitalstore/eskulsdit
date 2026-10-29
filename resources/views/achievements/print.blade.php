<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Prestasi Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Nunito', sans-serif; padding: 40px; background: white; color: #333; }
        
        /* Header Decoration */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 20px;
            border-bottom: 3px double #059669; /* Emerald Green */
            padding-top: 10px;
        }
        .page-header h1 {
            color: #064e3b; /* Deep Green */
            font-size: 24pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            line-height: 1.2;
        }
        .header-logo {
            position: absolute;
            left: 0;
            top: 0;
            height: 80px; /* Adjust size as needed */
            object-fit: contain;
        }
        
        @media print {
            .header-logo {
                -webkit-print-color-adjust: exact;
            }
        }
        .page-header p {
            margin: 5px 0 0;
            color: #047857;
            font-size: 12pt;
        }
        
        /* Colorful Table */
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.05);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
            border: 1px solid #d1fae5;
        }
        
        thead {
            background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
            color: white;
            -webkit-print-color-adjust: exact; /* Ensure background prints */
            print-color-adjust: exact;
        }
        
        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10pt;
        }
        
        /* Zebra Striping */
        tbody tr:nth-child(even) {
            background-color: #f2fbf5; /* Soft mint green */
            -webkit-print-color-adjust: exact;
        }
        
        /* Levels Coloring */
        .level-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
            -webkit-print-color-adjust: exact;
        }
        
        .level-sekolah { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .level-kecamatan { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
        .level-kabupaten { background: #fae8ff; color: #86198f; border: 1px solid #f5d0fe; }
        .level-provinsi { background: #fce7f3; color: #9d174d; border: 1px solid #fbcfe8; }
        .level-nasional { background: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
        .level-internasional { background: #fef08a; color: #854d0e; border: 1px solid #fde047; }
        .level-open { background: #ccfbf1; color: #115e59; border: 1px solid #99f6e4; }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 10pt;
            page-break-inside: avoid;
        }
        
        /* Print Toolbar (Interactive Screen Only) */
        .print-toolbar {
            position: sticky;
            top: 0;
            z-index: 9999;
            background: #ffffff;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            margin: -40px -40px 30px -40px;
        }
        .print-toolbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .print-filter-form {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .print-date-wrap {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 4px 10px;
        }
        .print-input-date {
            border: none;
            background: transparent;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            outline: none;
        }
        .btn-tb {
            border: none;
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }
        .btn-tb-apply { background: #059669; color: white; }
        .btn-tb-quick { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .btn-tb-reset { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .btn-tb-print { background: #0284c7; color: white; box-shadow: 0 2px 6px rgba(2,132,199,0.3); }
        .btn-tb-close { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .btn-tb:hover { filter: brightness(1.05); }

        @media print {
            body { padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .print-toolbar { display: none !important; }
            table { box-shadow: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- Toolbar Interaktif Pra-Cetak (Otomatis Sembunyi Saat Dicetak) -->
    <div class="no-print print-toolbar">
        <div class="print-toolbar-inner">
            <form method="GET" action="{{ route('achievements.print') }}" class="print-filter-form">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="level" value="{{ request('level') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">

                <span style="font-weight: 800; font-size: 13px; color: #064e3b; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-filter"></i> Filter Periode:
                </span>

                <div class="print-date-wrap">
                    <input type="date" name="start_date" value="{{ request('start_date', request('date')) }}" title="Dari Tanggal (Contoh: Tgl 1)" class="print-input-date">
                    <span style="font-size: 11px; font-weight: 700; color: #64748b;">s/d</span>
                    <input type="date" name="end_date" value="{{ request('end_date', request('date')) }}" title="Sampai Tanggal (Contoh: Tgl 30)" class="print-input-date">
                </div>

                <button type="submit" class="btn-tb btn-tb-apply" title="Terapkan rentang tanggal">
                    <i class="fas fa-check"></i> Terapkan
                </button>

                @php
                    $curYear = date('Y');
                    $curMonth = date('m');
                    $preset1_30 = ['start_date' => "{$curYear}-{$curMonth}-01", 'end_date' => "{$curYear}-{$curMonth}-30"];
                    $isActive130 = request('start_date') === $preset1_30['start_date'] && request('end_date') === $preset1_30['end_date'];
                @endphp
                <a href="{{ route('achievements.print', array_merge(request()->query(), $preset1_30)) }}" 
                   class="btn-tb btn-tb-quick" title="Set Tgl 1 - 30 Bulan Ini" style="{{ $isActive130 ? 'background: #f59e0b; color: white;' : '' }}">
                    <i class="far fa-calendar-check"></i> Tgl 1 - 30 (Bulan Ini)
                </a>

                @if(request()->hasAny(['start_date', 'end_date', 'date', 'month', 'year', 'category', 'level', 'search']))
                <a href="{{ route('achievements.print') }}" class="btn-tb btn-tb-reset" title="Tampilkan Semua Data">
                    <i class="fas fa-undo"></i> Reset
                </a>
                @endif
            </form>

            <div style="display: flex; gap: 8px;">
                <button onclick="window.print()" class="btn-tb btn-tb-print" title="Buka dialog cetak / Simpan PDF">
                    <i class="fas fa-print"></i> Cetak Dokumen
                </button>
                <button onclick="window.close()" class="btn-tb btn-tb-close" title="Tutup tab">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @php
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $headmasterSetting = \App\Models\Setting::where('key', 'headmaster_name')->value('value');
        $headmasterUser = \App\Models\User::where('role', 'headmaster')->first();
        $headmasterName = !empty($headmasterSetting) ? $headmasterSetting : ($headmasterUser ? $headmasterUser->name : 'Nur\'asiah, S.Pd.I');
    @endphp

    <div class="page-header">
        <img src="{{ asset('logo.png') }}" class="header-logo" alt="Logo">
        <div style="display: inline-block; width: 100%;"> <!-- Wrap text to center distinct from logo -->
            <div style="font-size: 11pt; font-weight: bold; color: #047857; text-transform: uppercase; letter-spacing: 1px;">AN NADZIR ISLAMIC SCHOOL</div>
            <h1>Laporan Prestasi Siswa</h1>
            @if(!empty($filterDescription))
                <p>SDIT AN NADZIR &bull; <strong>{{ $filterDescription }}</strong></p>
            @else
                <p>SDIT AN NADZIR - Tahun Ajaran {{ $activeYear ? $activeYear->name : '...' }}</p>
            @endif
            <div style="font-size: 10pt; margin-top: 5px; color: #888;">Dicetak pada: {{ now()->translatedFormat('d F Y') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Nama Siswa / Lembaga</th>
                <th width="25%">Nama Prestasi</th>
                <th width="15%">Tingkat</th>
                <th width="20%">Penyelenggara</th>
            </tr>
        </thead>
        <tbody>
            @forelse($achievements as $index => $item)
            @php
                $levelClass = 'level-sekolah';
                if(str_contains(strtolower($item->level), 'kecamatan')) $levelClass = 'level-kecamatan';
                if(str_contains(strtolower($item->level), 'kabupaten')) $levelClass = 'level-kabupaten';
                if(str_contains(strtolower($item->level), 'provinsi')) $levelClass = 'level-provinsi';
                if(str_contains(strtolower($item->level), 'nasional')) $levelClass = 'level-nasional';
                if(str_contains(strtolower($item->level), 'internasional')) $levelClass = 'level-internasional';
                if(str_contains(strtolower($item->level), 'open')) $levelClass = 'level-open';
            @endphp
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}</td>
                <td>
                    @if($item->category === 'school' || empty($item->student_id))
                        <strong style="color: #047857;">🏆 {{ $item->school_name ?: 'SDIT AN NADZIR' }}</strong><br>
                        <span style="font-size: 8.5pt; font-weight: 700; color: #b45309; background: #fef3c7; padding: 1px 6px; border-radius: 4px; display: inline-block; margin-top: 2px;">Prestasi Sekolah</span>
                    @else
                        <strong>{{ $item->student->name ?? 'Siswa Dihapus' }}</strong><br>
                        <span style="font-size: 9pt; color: #666;">Kelas {{ $item->student->class ?? '-' }}</span>
                    @endif
                </td>
                <td>{{ $item->name }}</td>
                <td align="center">
                    <span class="level-badge {{ $levelClass }}">
                        {{ $item->level }}
                    </span>
                </td>
                <td>{{ $item->organizer ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" align="center" style="padding: 24px; color: #64748b; font-style: italic;">
                    Tidak ada data prestasi yang tercatat pada filter / periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature Block -->
    <div class="footer" style="text-align: right; margin-top: 50px; padding-right: 50px;">
        <p>Cinangka, {{ now()->translatedFormat('d F Y') }}</p>
        <p>Mengetahui,</p>
        <p style="margin-bottom: 80px;"><strong>Kepala SDIT AN NADZIR</strong></p>
        
        <p style="text-decoration: underline; font-weight: bold; margin-bottom: 2px;">{{ $headmasterName }}</p>
    </div>

</body>
</html>
