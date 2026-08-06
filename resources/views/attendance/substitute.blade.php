<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Guru Pengganti - {{ $eskul->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #eef2ff 100%);
            min-height: 100vh;
            padding: 20px 15px 40px;
            color: #0f172a;
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
        }
        .header-card {
            background: white;
            padding: 24px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.04);
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }
        .header-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 6px; height: 100%;
            background: #10b981;
        }
        .badge-tag {
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 12px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .header-card h1 {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .header-card p {
            color: #64748b;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            padding: 14px;
            border-radius: 14px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .student-card {
            background: white;
            padding: 18px;
            border-radius: 16px;
            margin-bottom: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .student-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .student-name {
            font-weight: 700;
            font-size: 1rem;
            color: #0f172a;
        }
        .student-class {
            font-size: 0.8rem;
            color: #64748b;
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 8px;
            font-weight: 600;
        }
        .status-options {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        .status-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px 5px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s;
            background: #fafafa;
        }
        .status-btn input { display: none; }
        .status-btn span { font-size: 0.78rem; font-weight: 700; margin-top: 4px; }
        
        .status-btn.h:has(input:checked) { background: #dcfce7; border-color: #10b981; color: #15803d; }
        .status-btn.s:has(input:checked) { background: #fef3c7; border-color: #f59e0b; color: #b45309; }
        .status-btn.i:has(input:checked) { background: #e0e7ff; border-color: #6366f1; color: #4338ca; }
        .status-btn.a:has(input:checked) { background: #fee2e2; border-color: #ef4444; color: #b91c1c; }

        .note-input {
            width: 100%;
            margin-top: 10px;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.82rem;
            outline: none;
            transition: border-color 0.2s;
        }
        .note-input:focus { border-color: #10b981; }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-submit:hover { opacity: 0.95; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-card">
            <span class="badge-tag"><i class="fas fa-user-shield me-1"></i> Form Guru Pengganti</span>
            <h1>{{ $eskul->name }}</h1>
            <p>
                <span><i class="fas fa-calendar-alt text-emerald-600"></i> {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</span>
                <span><i class="fas fa-clock"></i> Tautan Akses Sementara</span>
            </p>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle fa-lg"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <form action="{{ route('substitute.attendance.store', $token->token) }}" method="POST">
            @csrf

            @if($eskul->students->isEmpty())
                <div class="header-card" style="text-align: center; color: #64748b;">
                    <i class="fas fa-users-slash fa-2x mb-2" style="display:block;"></i>
                    Belum ada siswa terdaftar di ekstrakurikuler ini.
                </div>
            @else
                @foreach($eskul->students as $index => $student)
                    @php
                        $existing = $existingAttendance->get($student->id);
                        $currentStatus = $existing ? $existing->status : 'present';
                        $currentNote = $existing ? $existing->note : '';
                    @endphp
                    <div class="student-card">
                        <div class="student-info">
                            <div>
                                <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; margin-right: 6px;">#{{ $index + 1 }}</span>
                                <span class="student-name">{{ $student->name }}</span>
                            </div>
                            <span class="student-class">{{ $student->class }}</span>
                        </div>

                        <div class="status-options">
                            <label class="status-btn h">
                                <input type="radio" name="attendance[{{ $student->id }}]" value="present" {{ $currentStatus == 'present' ? 'checked' : '' }}>
                                <i class="fas fa-check"></i>
                                <span>Hadir</span>
                            </label>
                            <label class="status-btn s">
                                <input type="radio" name="attendance[{{ $student->id }}]" value="sick" {{ $currentStatus == 'sick' ? 'checked' : '' }}>
                                <i class="fas fa-medkit"></i>
                                <span>Sakit</span>
                            </label>
                            <label class="status-btn i">
                                <input type="radio" name="attendance[{{ $student->id }}]" value="permission" {{ $currentStatus == 'permission' ? 'checked' : '' }}>
                                <i class="fas fa-envelope"></i>
                                <span>Izin</span>
                            </label>
                            <label class="status-btn a">
                                <input type="radio" name="attendance[{{ $student->id }}]" value="absent" {{ $currentStatus == 'absent' ? 'checked' : '' }}>
                                <i class="fas fa-times"></i>
                                <span>Alpa</span>
                            </label>
                        </div>

                        <input type="text" name="notes[{{ $student->id }}]" value="{{ $currentNote }}" placeholder="Catatan (Opsional, misal: Pulang Awal)" class="note-input">
                    </div>
                @endforeach

                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Simpan Absensi Siswa
                </button>
            @endif
        </form>
    </div>
</body>
</html>
