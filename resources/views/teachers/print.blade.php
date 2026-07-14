<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Akun Login Pembina</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 11pt; color: #000; }
        .no-print { margin-bottom: 20px; padding: 10px; background: #eee; text-align: right; }
        .btn { padding: 8px 15px; cursor: pointer; background: #333; color: white; border: none; border-radius: 4px; text-decoration: none; display: inline-block; font-size: 14px;}
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #000; }
        th { background-color: #f0f0f0; padding: 8px; text-align: left; }
        td { padding: 8px; }
        
        @media print {
            .no-print { display: none; }
            @page { margin: 1cm; size: A4; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn">🖨️ Cetak</button>
        <button onclick="window.close()" class="btn" style="background: #d32f2f;">Tutup</button>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">DATA AKUN LOGIN PEMBINA EKSTRAKURIKULER</h2>
        <p style="margin: 5px 0;">SDIT AN NADZIR - Tahun Pelajaran {{ $activeYear->name ?? '-' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 30%;">Nama Pembina</th>
                <th style="width: 20%;">Username</th>
                <th style="width: 20%;">Password Default</th>
                <th style="width: 25%;">Eskul Binaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $index => $teacher)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $teacher->name }}</td>
                <td style="font-family: monospace; font-size: 1.1em;">{{ $teacher->username }}</td>
                <td style="color: #666; font-style: italic;">123456</td>
                <td>{{ $teacher->eskul->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 0.9em; color: #555;">
        <p><strong>Catatan:</strong></p>
        <ul>
            <li>Password "123456" adalah password standar sistem.</li>
            <li>Jika Pembina tidak bisa login dengan password tersebut, silakan minta Admin untuk mereset password melalui tombol "Reset Password" di sistem.</li>
            <li>Harap segera mengganti password setelah berhasil login demi keamanan data.</li>
        </ul>
    </div>
</body>
</html>
