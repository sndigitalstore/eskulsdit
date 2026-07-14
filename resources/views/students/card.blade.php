<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Siswa - {{ $student->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        body {
            background-color: #f1f5f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Outfit', sans-serif;
            padding: 20px;
        }

        .action-bar {
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
        }

        .btn {
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
        }

        .btn-download {
            background: #2563eb;
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-back {
            background: white;
            color: #475569;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        /* CARD DESIGN */
        .id-card-container {
            width: 350px;
            height: 600px;
            background: white;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            overflow: hidden;
            position: relative;
            transform-style: preserve-3d;
        }

        .card-header {
            height: 35%;
            background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%);
            position: relative;
            display: flex;
            justify-content: center;
            padding-top: 30px;
        }

        .pattern-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.5;
        }

        .school-logo-bg {
            position: absolute;
            top: -20px; left: -20px;
            font-size: 15rem;
            color: rgba(255,255,255,0.05);
            transform: rotate(-15deg);
        }

        .profile-pic-container {
            width: 130px;
            height: 130px;
            background: white;
            border-radius: 50%;
            position: absolute;
            bottom: -65px;
            left: 50%;
            transform: translateX(-50%);
            padding: 5px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            /* Default fallback */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            color: white;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
            box-shadow: inset 0 0 20px rgba(255,255,255,0.2);
            font-family: 'Space Grotesk', sans-serif;
        }

        .card-body {
            padding: 80px 30px 40px;
            text-align: center;
        }

        .student-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .student-nis {
            font-size: 0.95rem;
            color: #64748b;
            margin-bottom: 20px;
            letter-spacing: 1px;
            background: #f1f5f9;
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
        }

        .info-grid {
            margin-top: 20px;
            text-align: left;
            background: #f8fafc;
            padding: 20px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }
        
        .info-item:last-child { margin-bottom: 0; }

        .info-label { color: #94a3b8; font-weight: 500; }
        .info-value { color: #334155; font-weight: 600; }

        .qr-section {
            margin-top: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .qr-code-box {
            background: white;
            padding: 10px;
            border-radius: 15px;
            border: 2px dashed #cbd5e1;
        }

        .card-footer {
            margin-top: 15px;
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <a href="{{ route('students.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button onclick="downloadCard()" class="btn btn-download">
            <i class="fas fa-download"></i> Simpan Kartu (PNG)
        </button>
    </div>

    <!-- THE CARD TO DOWNLOAD -->
    <div class="id-card-container" id="student-card">
        <div class="card-header">
            <div class="pattern-overlay"></div>
            <i class="fas fa-school school-logo-bg"></i>
            
            <div style="z-index: 5; text-align: center; color: white;">
                <img src="{{ asset('logo.png') }}" alt="" style="height: 50px; margin-bottom: 5px;">
                <div style="font-weight: 700; font-size: 0.9rem; letter-spacing: 1px;">KARTU DIGITAL</div>
                <div style="font-size: 0.8rem; opacity: 0.8;">SDIT AN NADZIR</div>
            </div>

            @php
                $colors = [
                    'linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%)',
                    'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)',
                    'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)',
                    'linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%)',
                    'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                    'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                    'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                ];
                $colorIndex = ord(substr($student->name, 0, 1)) % count($colors);
                $gradient = $colors[$colorIndex];
            @endphp
            <div class="profile-pic-container">
                @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" alt="Foto Siswa" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    <div class="profile-pic" style="background: {{ $gradient }};">
                       {{ substr($student->name, 0, 1) }}
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body">
            <h2 class="student-name">{{ $student->name }}</h2>
            <div class="student-nis">NIS: {{ $student->nis ?? '-' }}</div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Kelas</span>
                    <span class="info-value">{{ $student->class }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tahun Ajaran</span>
                    <span class="info-value">{{ $activeYear->name ?? '-' }}</span>
                </div>
                <div class="info-item" style="flex-direction: column; gap: 5px;">
                    <span class="info-label">Ekstrakurikuler Aktif:</span>
                    <div class="info-value" style="color: #2563eb;">
                        @forelse($student->eskuls as $eskul)
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <i class="fas fa-check-circle" style="font-size: 0.8rem;"></i> {{ $eskul->name }}
                            </div>
                        @empty
                            <span style="color: #94a3b8;">Tidak ada</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="qr-section">
                <!-- Use hidden input to store URL but render QR immediately -->
                <div class="qr-code-box">
                    <canvas id="qr-code"></canvas>
                </div>
            </div>
            
            <div class="card-footer">
                Scan untuk cek status & prestasi
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var qr = new QRious({
              element: document.getElementById('qr-code'),
              value: '{{ route('student-status.search') }}?q={{ $student->nis }}', // Direct search link
              size: 80,
              level: 'H'
            });
        });

        function downloadCard() {
            const card = document.getElementById('student-card');
            
            html2canvas(card, {
                scale: 3, // High quality
                useCORS: true,
                backgroundColor: null
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Kartu_Siswa_{{ str_replace(" ", "_", $student->name) }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    </script>
</body>
</html>
