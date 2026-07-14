<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Siswa - SDIT AN NADZIR</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #f0f2f5;
            --primary-color: #2980b9;
            --secondary-color: #34495e;
            --accent-color: #f1c40f;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 480px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }
        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header img {
            width: 80px;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-size: 14px;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            transition: border 0.3s;
            box-sizing: border-box;
            background: #fafafa;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            background: white;
            outline: none;
        }
        .btn-check {
            width: 100%;
            padding: 14px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-check:hover {
            background: #2475ab;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(41, 128, 185, 0.3);
        }
        .btn-check i { font-size: 14px; }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #999;
            background: #fafafa;
            border-top: 1px solid #eee;
        }
        .error-msg {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
        }
        /* Mobile responsive adjustments */
        @media (max-width: 480px) {
            .header { padding: 30px 20px; }
            .content { padding: 25px 20px; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="{{ asset('logo.png') }}" alt="Logo SDIT">
        <h1>Portal Orang Tua</h1>
        <p>Cek Status & Prestasi Ekstrakurikuler Siswa</p>
    </div>
    
    <div class="content">
        <form action="{{ route('student-status.search') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Pencarian Data Siswa</label>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 15px; top: 15px; color: #aaa;"></i>
                    <input type="text" name="keyword" class="form-control" placeholder="Masukkan NIS atau Nama Lengkap" required style="padding-left: 45px; font-weight: 500;">
                </div>
                <small style="color: #888; display: block; margin-top: 5px; font-size: 0.8rem;">
                    * Masukkan Nomor Induk Siswa (NIS) atau Nama Lengkap untuk mencari.
                </small>
                @error('keyword')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-check">
                <i class="fas fa-search"></i> Cek Status Siswa
            </button>
        </form>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} SDIT AN NADZIR - Sistem Informasi Ekstrakurikuler
    </div>
</div>

<script>
    function loadStudents() {
        var classSelect = document.getElementById('class-select');
        var studentSelect = document.getElementById('student-select');
        var selectedClass = classSelect.value;

        if (!selectedClass) {
            studentSelect.innerHTML = '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
            studentSelect.disabled = true;
            return;
        }

        studentSelect.innerHTML = '<option value="">Memuat data...</option>';
        studentSelect.disabled = true;

        fetch('{{ route("student-status.students") }}?class=' + selectedClass)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    var options = '<option value="">-- Pilih Nama Siswa --</option>';
                    data.forEach(function(student) {
                        options += '<option value="' + student.id + '">' + student.name + '</option>';
                    });
                    studentSelect.innerHTML = options;
                    studentSelect.disabled = false;
                } else {
                    studentSelect.innerHTML = '<option value="">Tidak ada data siswa</option>';
                    studentSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                studentSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    }
</script>

</body>
</html>
