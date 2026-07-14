<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - {{ $student->name }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #f0f2f5;
            --primary-color: #2980b9;
            --card-bg: #ffffff;
            --text-main: #2c3e50;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 20px;
            color: var(--text-main);
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        .profile-card {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            background: #e3f2fd;
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 15px;
        }
        .student-name {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }
        .student-meta {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 5px;
        }
        .year-badge {
            display: inline-block;
            background: #27ae60;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 10px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #34495e;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .eskul-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .eskul-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .eskul-name {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 16px;
        }
        .instructor {
            font-size: 12px;
            color: #7f8c8d;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            padding: 20px;
            border-bottom: 1px solid #f1f2f6;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }
        .stat-label {
            font-size: 11px;
            color: #95a5a6;
            text-transform: uppercase;
        }

        .grades-container {
            padding: 20px;
            display: flex;
            justify-content: space-around;
        }
        .grade-box {
            text-align: center;
            width: 45%;
            background: #fff9c4;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #fff59d;
        }
        .grade-value {
            font-size: 24px;
            font-weight: 700;
            color: #f39c12;
            display: block;
        }
        .grade-label {
            font-size: 12px;
            color: #7f8c8d;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 15px;
            color: #95a5a6;
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 30px;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
            padding: 12px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-card">
        <div class="profile-avatar">
            <i class="fas fa-user-graduate"></i>
        </div>
        <h1 class="student-name">{{ $student->name }}</h1>
        <div class="student-meta">Kelas {{ $student->class }}</div>
        <div class="year-badge"><i class="fas fa-calendar-alt"></i> TA {{ $activeYear->name }}</div>
    </div>

    @if($student->achievements->isNotEmpty())
        <div style="background: #fffbe7; border: 1px solid #f9e79f; border-radius: 15px; padding: 20px; margin-bottom: 25px; text-align: left;">
            <h3 style="color: #d4ac0d; margin-top: 0; font-size: 16px; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-trophy" style="font-size: 1.2rem;"></i> Prestasi & Kejuaraan
            </h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($student->achievements as $achievement)
                <div style="background: white; padding: 12px; border-radius: 10px; border-left: 4px solid #f1c40f; box-shadow: 0 2px 5px rgba(0,0,0,0.03);">
                    <div style="font-weight: 600; color: #333;">{{ $achievement->name }}</div>
                    <div style="font-size: 13px; color: #666; margin-top: 3px;">
                        <span style="background: #fcf3cf; color: #d4ac0d; padding: 2px 6px; border-radius: 4px; font-weight: 500; font-size: 11px;">{{ $achievement->level }}</span>
                        <span>• {{ \Carbon\Carbon::parse($achievement->date)->translatedFormat('d M Y') }}</span>
                    </div>
                    @if($achievement->description)
                        <div style="font-size: 12px; color: #999; margin-top: 2px; font-style: italic;">
                            {{ $achievement->description }}
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(count($reportData) > 0)
        @foreach($reportData as $data)
        <div class="eskul-card">
            <div class="eskul-header">
                <div>
                    <div class="eskul-name">{{ $data['eskul_name'] }}</div>
                    <div class="instructor"><i class="fas fa-chalkboard-teacher"></i> {{ $data['instructor'] ?? 'Belum ada pembina' }}</div>
                </div>
            </div>
            
            <div class="section-title" style="padding: 15px 20px 0; margin-bottom: 5px; font-size: 13px;">
                <i class="fas fa-clipboard-check"></i> Riwayat Kehadiran
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value" style="color: #27ae60;">{{ $data['attendance']['H'] }}</div>
                    <div class="stat-label">Hadir</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" style="color: #f1c40f;">{{ $data['attendance']['S'] }}</div>
                    <div class="stat-label">Sakit</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" style="color: #3498db;">{{ $data['attendance']['I'] }}</div>
                    <div class="stat-label">Izin</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" style="color: #e74c3c;">{{ $data['attendance']['A'] }}</div>
                    <div class="stat-label">Alpa</div>
                </div>
            </div>

            <div class="section-title" style="padding: 10px 20px 0; margin-bottom: 5px; font-size: 13px;">
                <i class="fas fa-star"></i> Nilai Assesmen
            </div>
            <div class="grades-container">
                @php
                    $isCalistung = \Illuminate\Support\Str::contains(strtolower($data['eskul_name']), 'calistung');
                    
                    // Helper to process grade
                    $processGrade = function($score) use ($isCalistung) {
                        if ($isCalistung) {
                            $decoded = json_decode($score, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                return $decoded;
                            }
                        }
                        return $score;
                    };

                    $sas1 = $processGrade($data['grades']['sas1']);
                    $sas2 = $processGrade($data['grades']['sas2']);
                @endphp

                <div class="grade-box">
                    <span class="grade-label">Semester 1</span>
                    @if(is_array($sas1))
                        <div style="font-size: 13px; text-align: left; margin-top: 8px; line-height: 1.6;">
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e0e0e0; padding-bottom: 2px; margin-bottom: 2px;">
                                <span>Membaca</span> <strong>{{ $sas1['reading'] ?? '-' }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e0e0e0; padding-bottom: 2px; margin-bottom: 2px;">
                                <span>Menulis</span> <strong>{{ $sas1['writing'] ?? '-' }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Menghitung</span> <strong>{{ $sas1['counting'] ?? '-' }}</strong>
                            </div>
                        </div>
                    @else
                        <span class="grade-value">{{ $sas1 }}</span>
                    @endif
                </div>
                
                <div class="grade-box" style="background: #e3f2fd; border-color: #bbdefb;">
                    <span class="grade-label">Semester 2</span>
                    @if(is_array($sas2))
                        <div style="font-size: 13px; text-align: left; margin-top: 8px; line-height: 1.6;">
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #bbdefb; padding-bottom: 2px; margin-bottom: 2px;">
                                <span>Membaca</span> <strong style="color: #2980b9;">{{ $sas2['reading'] ?? '-' }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #bbdefb; padding-bottom: 2px; margin-bottom: 2px;">
                                <span>Menulis</span> <strong style="color: #2980b9;">{{ $sas2['writing'] ?? '-' }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Menghitung</span> <strong style="color: #2980b9;">{{ $sas2['counting'] ?? '-' }}</strong>
                            </div>
                        </div>
                    @else
                        <span class="grade-value" style="color: #2980b9;">{{ $sas2 }}</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="fas fa-folder-open" style="font-size: 40px; margin-bottom: 15px; opacity: 0.5;"></i>
            <p>Belum ada data kegiatan ekstrakurikuler yang diikuti pada tahun ajaran aktif ini.</p>
        </div>
    @endif

    <a href="{{ route('student-status.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Cek Siswa Lain
    </a>
</div>

</body>
</html>
