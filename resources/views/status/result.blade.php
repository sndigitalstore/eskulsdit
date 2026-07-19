<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - {{ $student->name }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #f4f6f9;
            --primary-color: #2980b9;
            --card-bg: #ffffff;
            --text-main: #2c3e50;
            --active-color: #27ae60;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 20px;
            color: var(--text-main);
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
        }
        .profile-card {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }
        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-color), var(--active-color));
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
            box-shadow: 0 4px 10px rgba(41, 128, 185, 0.15);
        }
        .student-name {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            color: #1a252f;
        }
        .student-meta {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 5px;
            font-weight: 500;
        }
        .student-nis {
            display: inline-block;
            background: #edf2f7;
            color: #4a5568;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 12px;
            margin-top: 5px;
            font-weight: 600;
        }

        .section-title {
            font-size: 15px;
            font-weight: 600;
            margin-top: 25px;
            margin-bottom: 15px;
            color: #34495e;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }

        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 20px;
            border-left: 3px solid #cbd5e0;
            margin-left: 10px;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -28px;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #cbd5e0;
            border: 3px solid var(--bg-color);
        }
        .timeline-item.active::before {
            background: var(--active-color);
            box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.2);
        }
        .year-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .year-title {
            font-size: 16px;
            font-weight: 700;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .class-badge {
            background: var(--primary-color);
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .active-year-badge {
            background: var(--active-color);
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Eskul Card Inside Year Group */
        .eskul-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            margin-bottom: 15px;
            border: 1px solid #e2e8f0;
        }
        .eskul-header {
            background: #f7fafc;
            padding: 12px 18px;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .eskul-name {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 15px;
        }
        .instructor {
            font-size: 11px;
            color: #718096;
            margin-top: 2px;
        }
        
        .sub-section-title {
            padding: 12px 18px 0;
            margin-bottom: 2px;
            font-size: 12px;
            font-weight: 600;
            color: #718096;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            padding: 12px 18px;
            border-bottom: 1px solid #edf2f7;
        }
        .stat-item {
            text-align: center;
            background: #f8fafc;
            padding: 6px;
            border-radius: 8px;
        }
        .stat-value {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
        }
        .stat-label {
            font-size: 10px;
            color: #95a5a6;
            text-transform: uppercase;
            font-weight: 500;
        }

        .grades-container {
            padding: 15px 18px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .grade-box {
            text-align: center;
            flex: 1;
            background: #fffdf5;
            padding: 8px 10px;
            border-radius: 10px;
            border: 1px solid #fef3c7;
        }
        .grade-value {
            font-size: 20px;
            font-weight: 700;
            color: #d97706;
            display: block;
        }
        .grade-label {
            font-size: 11px;
            color: #7f8c8d;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 15px;
            color: #95a5a6;
            border: 1px solid #e2e8f0;
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 30px;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 600;
            padding: 12px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }
        .btn-back:hover {
            background: #edf2f7;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Profile Header -->
    <div class="profile-card">
        <div class="profile-avatar">
            <i class="fas fa-user-graduate"></i>
        </div>
        <h1 class="student-name">{{ $student->name }}</h1>
        <div class="student-meta">Kelas Terakhir/Aktif: {{ $student->class }}</div>
        @if($student->nis)
            <div class="student-nis">NIS: {{ $student->nis }}</div>
        @endif
    </div>

    <!-- Achievements Section (Historical) -->
    @if($achievements->isNotEmpty())
        <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 15px; padding: 20px; margin-bottom: 25px; text-align: left; box-shadow: 0 4px 10px rgba(217, 119, 6, 0.03);">
            <h3 style="color: #d97706; margin-top: 0; font-size: 15px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-trophy" style="font-size: 1.1rem;"></i> Prestasi & Kejuaraan (Semua Tahun)
            </h3>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                @foreach($achievements as $achievement)
                <div style="background: white; padding: 10px 12px; border-radius: 8px; border-left: 4px solid #f59e0b; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="font-weight: 600; color: #1e293b; font-size: 13px;">{{ $achievement->name }}</div>
                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                        <span style="background: #fef3c7; color: #b45309; padding: 1px 5px; border-radius: 4px; font-weight: 600; font-size: 10px;">{{ $achievement->level }}</span>
                        <span>• {{ \Carbon\Carbon::parse($achievement->date)->translatedFormat('d M Y') }}</span>
                    </div>
                    @if($achievement->description)
                        <div style="font-size: 11px; color: #94a3b8; margin-top: 2px; font-style: italic;">
                            {{ $achievement->description }}
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Academic History Timeline -->
    <div class="section-title">
        <i class="fas fa-history"></i> Riwayat Perkembangan Ekstrakurikuler
    </div>

    @if(count($historyData) > 0)
        <div class="timeline">
            @foreach($historyData as $yearGroup)
                <div class="timeline-item {{ $yearGroup['is_active'] ? 'active' : '' }}">
                    
                    <!-- Year Header -->
                    <div class="year-header">
                        <div class="year-title">
                            <i class="fas fa-calendar-check" style="color: {{ $yearGroup['is_active'] ? 'var(--active-color)' : '#718096' }};"></i>
                            TA {{ $yearGroup['year_name'] }}
                            @if($yearGroup['is_active'])
                                <span class="active-year-badge">Tahun Ajaran Aktif</span>
                            @endif
                        </div>
                        <span class="class-badge">Kelas {{ $yearGroup['student_class'] }}</span>
                    </div>

                    <!-- Eskul Cards in this year -->
                    @foreach($yearGroup['eskul_data'] as $data)
                        <div class="eskul-card">
                            <div class="eskul-header">
                                <div>
                                    <div class="eskul-name">{{ $data['eskul_name'] }}</div>
                                    <div class="instructor">
                                        <i class="fas fa-chalkboard-teacher"></i> {{ $data['instructor'] ?? 'Belum ada pembina' }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Attendance -->
                            <div class="sub-section-title">
                                <i class="fas fa-clipboard-check"></i> Kehadiran
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

                            <!-- Grades -->
                            <div class="sub-section-title">
                                <i class="fas fa-star"></i> Nilai Assesmen
                            </div>
                            <div class="grades-container">
                                @php
                                    $isCalistung = \Illuminate\Support\Str::contains(strtolower($data['eskul_name']), 'calistung') || \Illuminate\Support\Str::contains(strtolower($data['eskul_name']), 'baca');
                                    
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
                                    $hasSas1 = $sas1 !== '-';
                                    $hasSas2 = $sas2 !== '-';
                                @endphp

                                <!-- Semester 1 Grade Box -->
                                <div class="grade-box" style="background: #fffdf5; border-color: #fef3c7;">
                                    <span class="grade-label">Semester 1</span>
                                    @if(is_array($sas1))
                                        <div style="font-size: 12px; text-align: left; margin-top: 6px; line-height: 1.5;">
                                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding-bottom: 2px; margin-bottom: 2px;">
                                                <span>Membaca</span> <strong>{{ $sas1['reading'] ?? '-' }}</strong>
                                            </div>
                                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding-bottom: 2px; margin-bottom: 2px;">
                                                <span>Menulis</span> <strong>{{ $sas1['writing'] ?? '-' }}</strong>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <span>Menghitung</span> <strong>{{ $sas1['counting'] ?? '-' }}</strong>
                                            </div>
                                        </div>
                                    @else
                                        <span class="grade-value" style="color: #d97706; margin-top: 4px;">{{ $sas1 }}</span>
                                    @endif
                                </div>
                                
                                <!-- Semester 2 Grade Box -->
                                <div class="grade-box" style="background: #e3f2fd; border-color: #bbdefb;">
                                    <span class="grade-label" style="color: #1e3a8a;">Semester 2</span>
                                    @if(is_array($sas2))
                                        <div style="font-size: 12px; text-align: left; margin-top: 6px; line-height: 1.5;">
                                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #bbdefb; padding-bottom: 2px; margin-bottom: 2px;">
                                                <span>Membaca</span> <strong style="color: #1e3a8a;">{{ $sas2['reading'] ?? '-' }}</strong>
                                            </div>
                                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #bbdefb; padding-bottom: 2px; margin-bottom: 2px;">
                                                <span>Menulis</span> <strong style="color: #1e3a8a;">{{ $sas2['writing'] ?? '-' }}</strong>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <span>Menghitung</span> <strong style="color: #1e3a8a;">{{ $sas2['counting'] ?? '-' }}</strong>
                                            </div>
                                        </div>
                                    @else
                                        <span class="grade-value" style="color: #1e3a8a; margin-top: 4px;">{{ $sas2 }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-folder-open" style="font-size: 40px; margin-bottom: 15px; opacity: 0.5;"></i>
            <p>Belum ada riwayat kegiatan ekstrakurikuler siswa ini di database.</p>
        </div>
    @endif

    <a href="{{ route('student-status.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Cek Siswa Lain
    </a>
</div>

</body>
</html>
