<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil – Munaqosah SDIT AN NADZIR</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #059669;
            --primary-dark: #065f46;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f8fafc 100%);
            min-height: 100vh;
            padding: 30px 16px 60px 16px;
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            width: 100%;
            max-width: 580px;
            margin: 0 auto;
        }
        .success-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 16px 40px rgba(0,0,0,0.05);
            padding: 36px 28px;
            text-align: center;
            overflow: hidden;
            position: relative;
        }
        .icon-check-wrap {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin: 0 auto 20px auto;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);
        }
        .title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: #065f46;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        .details-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 20px;
            text-align: left;
            margin-bottom: 24px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #64748b;
            font-weight: 600;
        }
        .detail-val {
            color: #0f172a;
            font-weight: 800;
            text-align: right;
        }
        .badge-status {
            display: inline-block;
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 800;
        }
        .btn-wa {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            background: #25d366;
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 0.95rem;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.35);
            transition: all 0.2s;
            margin-bottom: 12px;
        }
        .btn-wa:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.45);
        }
        .btn-back {
            display: inline-block;
            width: 100%;
            background: #f1f5f9;
            color: #475569;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.88rem;
            border: 1px solid #cbd5e1;
            transition: all 0.2s;
        }
        .btn-back:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="success-card">
        <div class="icon-check-wrap">
            <i class="fas fa-check"></i>
        </div>

        <h1 class="title">Alhamdulillah, Terkirim!</h1>
        <p class="subtitle">
            Pendaftaran Munaqosah siswa telah berhasil dicatat dalam sistem SIMUTQIN SDIT AN NADZIR.
        </p>

        <!-- Details Box -->
        <div class="details-box">
            <div class="detail-row">
                <span class="detail-label">Nomor Pengajuan</span>
                <span class="detail-val" style="color: #059669;">#MNQ-{{ str_pad($registration->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Nama Siswa</span>
                <span class="detail-val">{{ $registration->student->name ?? '-' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Kelas</span>
                <span class="detail-val">Kelas {{ $registration->student->class ?? '-' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Bidang Munaqosah</span>
                <span class="detail-val" style="text-transform: capitalize;">
                    {{ $registration->type === 'tahfidz' ? 'Tahfidz Al-Qur\'an' : 'Tilawati Qur\'an' }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Jenjang Kelulusan</span>
                <span class="detail-val" style="color: #065f46;">
                    {{ $registration->current_level }} <i class="fas fa-arrow-right" style="font-size: 0.7rem; margin: 0 4px;"></i> {{ $registration->target_level }}
                </span>
            </div>
            @if($registration->examiner)
            <div class="detail-row">
                <span class="detail-label">Penguji / Munaqisy</span>
                <span class="detail-val" style="color: #1e3a8a; font-weight: 700;">{{ $registration->examiner->name }}</span>
            </div>
            @endif
            @if($registration->exam_time)
            <div class="detail-row">
                <span class="detail-label">Waktu Ujian</span>
                <span class="detail-val" style="color: #047857; font-weight: 600;">{{ $registration->exam_time }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">Pendaftar / Pengusul</span>
                <span class="detail-val">{{ $registration->proposer_name ?? ($registration->proposer->name ?? 'Guru') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status Verifikasi</span>
                @if($registration->status === 'scheduled')
                    <span class="badge-status" style="background: #ecfdf5; color: #047857;">
                        <i class="fas fa-calendar-check"></i> Terjadwal Otomatis
                    </span>
                @else
                    <span class="badge-status">
                        <i class="fas fa-clock"></i> Menunggu Jadwal Panitia
                    </span>
                @endif
            </div>
        </div>

        @php
            $slotInfo = '';
            if ($registration->examiner) {
                $slotInfo .= "• Penguji: " . $registration->examiner->name . "\n";
            }
            if ($registration->exam_time) {
                $slotInfo .= "• Jadwal: " . $registration->exam_time . "\n";
            }

            $waText = urlencode("Assalamu'alaikum Warahmatullahi Wabarakatuh Panitia Munaqosah SDIT AN NADZIR 🌙\n\nSaya telah mengajukan pendaftaran munaqosah siswa:\n\n" .
                "• No. Pengajuan: #MNQ-" . str_pad($registration->id, 4, '0', STR_PAD_LEFT) . "\n" .
                "• Nama Siswa: " . ($registration->student->name ?? '-') . " (Kelas " . ($registration->student->class ?? '-') . ")\n" .
                "• Bidang: " . ($registration->type === 'tahfidz' ? 'Tahfidz' : 'Tilawati') . "\n" .
                "• Jenjang Diuji: " . $registration->current_level . " -> Target: " . $registration->target_level . "\n" .
                $slotInfo .
                "• Pengusul: " . ($registration->proposer_name ?? 'Guru/Wali') . ($registration->proposer_phone ? " (" . $registration->proposer_phone . ")" : "") . "\n\n" .
                "Mohon konfirmasi verifikasi dan penjadwalan ujian dari Panitia Munaqosah. Terima kasih 🤲");
        @endphp

        <!-- WhatsApp Button -->
        <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="btn-wa">
            <i class="fab fa-whatsapp" style="font-size: 1.3rem;"></i> Bagikan Bukti Pendaftaran ke WA
        </a>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 8px;">
            <a href="{{ route('munaqosah.public-register') }}" class="btn-back">
                <i class="fas fa-plus-circle"></i> Daftar Siswa Lain
            </a>
            <a href="{{ route('student-status.index') }}" class="btn-back" style="background: white;">
                <i class="fas fa-chart-line"></i> Cek Status Siswa
            </a>
        </div>
    </div>

    <div style="text-align: center; margin-top: 24px; font-size: 0.8rem; color: #64748b;">
        <p>&copy; {{ date('Y') }} SDIT AN NADZIR. SIMUTQIN Al-Qur'an.</p>
    </div>

</div>

</body>
</html>
