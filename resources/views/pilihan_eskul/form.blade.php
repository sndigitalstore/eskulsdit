<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bg-color: #e0f7fa; /* Light Blue Background */
            --primary-color: #2980b9; /* Strong Blue Primary */
            --border-color: #dadce0;
            --header-border-top: #2980b9;
            --error-color: #d93025;
            --text-color: #202124;
        }
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 40px 20px;
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 640px;
        }
        .logo-header {
            display: block;
            margin: 0 auto 25px auto;
            max-height: 100px;
            width: auto;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
        }
        .card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            padding: 30px;
            margin-bottom: 15px;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .card-header-top {
            border-top: 10px solid var(--header-border-top);
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .form-header h1 {
            font-size: 28px;
            font-weight: 600;
            margin: 0 0 10px 0;
            color: #2d3436;
        }
        .form-description {
            font-size: 15px;
            color: #636e72;
            line-height: 1.6;
        }
        .question-label {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 12px;
            display: block;
            color: #2d3436;
        }
        .required-star {
            color: var(--error-color);
            margin-left: 4px;
        }
        .input-text {
            width: 100%;
            padding: 10px 0;
            border: none;
            border-bottom: 1px solid #dfe6e9;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
            background: transparent;
            font-family: inherit;
        }
        .input-text:focus {
            border-bottom: 2px solid var(--primary-color);
        }
        select.input-select {
            width: 100%;
            max-width: 250px;
            padding: 12px;
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            background: white;
            cursor: pointer;
            font-family: inherit;
            transition: border 0.2s;
        }
        select.input-select:focus {
            border: 2px solid var(--primary-color);
        }
        .radio-option {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.1s;
            margin-bottom: 5px;
        }
        .radio-option:hover {
            background-color: #f7f9fa;
        }
        .radio-option input[type="radio"] {
            margin-right: 15px;
            width: 20px;
            height: 20px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }
        .radio-option span {
            font-size: 15px;
            color: #2d3436;
        }
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 15px;
            box-shadow: 0 4px 6px rgba(0, 184, 148, 0.2);
        }
        .btn-submit:hover {
            background-color: #00a884;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 184, 148, 0.3);
        }
        .clear-form {
            float: right;
            color: #636e72;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            background: none;
            padding: 15px 0;
            transition: color 0.2s;
        }
        .clear-form:hover {
            color: var(--error-color);
        }
        .error-msg {
            color: var(--error-color);
            font-size: 13px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        .error-msg i { margin-right: 5px; }
        
        .footer-branding {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: #636e72;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }
            .card {
                padding: 20px;
            }
            .form-header h1 {
                font-size: 22px;
            }
            .logo-header {
                max-width: 100%;
                height: auto;
            }
            select.input-select {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <form action="{{ route('pilihan-eskul.store') }}" method="POST">
        @csrf
        
        <div class="card card-header-top" style="padding: 0; overflow: hidden;">
            <img src="{{ asset('header_banner.png') }}" alt="Header Banner" style="width: 100%; height: auto; display: block;">
            
            <div style="padding: 25px 30px 30px 30px;">
                <div class="form-header">
                    <h1>{{ $title }}</h1>
                </div>
                
                <div class="form-description">
                    @if($description)
                        {!! nl2br(e($description)) !!}
                    @endif
                </div>

                <div style="border-top: 1px solid #f1f2f6; margin-top: 25px; padding-top: 15px; color: var(--error-color); font-size: 13px;">
                    * Menunjukkan pertanyaan yang wajib diisi
                </div>
            </div>
        </div>

        <!-- Kelas -->
        <div class="card">
            <label class="question-label">Kelas <span class="required-star">*</span></label>
            <select name="class" id="class-select" class="input-select" required onchange="loadStudents()">
                <option value="">Pilih Kelas</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls->name }}" {{ old('class') == $cls->name ? 'selected' : '' }}>{{ $cls->name }}</option>
                @endforeach
            </select>
             @error('class')
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <!-- Nama Lengkap -->
        <div class="card">
            <label class="question-label">Nama Lengkap Siswa <span class="required-star">*</span></label>
            <select name="student_id" id="student-select" class="input-select" style="width: 100%; max-width: 100%;" disabled required>
                <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
            </select>
            <div style="font-size: 12px; color: #888; margin-top: 5px;">
                * Jika nama tidak ada, silakan hubungi admin sekolah.
            </div>
            <div id="current-eskul-info" style="display: none; margin-top: 10px; padding: 10px; background-color: #e8f0fe; color: #1967d2; border-radius: 6px; font-size: 13px;">
            </div>
            @error('student_id')
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <!-- No. WhatsApp Ortu -->
        <div class="card">
            <label class="question-label">No. WhatsApp Orang Tua <span class="required-star">*</span></label>
            <input type="text" name="parent_phone" class="input-text" placeholder="Contoh: 081234567890" value="{{ old('parent_phone') }}" required>
            <div style="font-size: 12px; color: #888; margin-top: 5px;">
                * Digunakan untuk pengiriman notifikasi pendaftaran berhasil.
            </div>
            @error('parent_phone')
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <!-- Pilihan Eskul -->
        <div class="card">
            <label class="question-label">Pilihan Eskul <span class="required-star">*</span></label>
            <div style="margin-top: 10px;">
                @foreach($eskuls as $eskul)
                @php $isFull = $eskul->students_count >= $quota; @endphp
                <label class="radio-option eskul-option" data-target-group="{{ $eskul->target_group }}" style="{{ $isFull ? 'opacity: 0.6; cursor: not-allowed;' : '' }}">
                    <input type="radio" name="eskul_1" value="{{ $eskul->id }}" data-is-full="{{ $isFull ? 'true' : 'false' }}" {{ old('eskul_1') == $eskul->id ? 'checked' : '' }} {{ $isFull ? 'disabled' : '' }} required>
                    <span>{{ $eskul->name }}</span>
                    @if($isFull)
                        <span style="color: #d63031; font-weight: 600; font-size: 12px; margin-left: 5px;">(Penuh)</span>
                    @endif
                </label>
                @endforeach
            </div>
             @error('eskul_1')
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <!-- Pernyataan -->
        <div class="card">
            <div style="font-weight: 500; font-size: 15px; margin-bottom: 20px; line-height: 1.6; color: #2d3436;">
                Dengan ini menyatakan telah memilih ekskul yang sesuai dengan minat anak kami serta akan mengikuti ketentuan yang telah ditetapkan. <span class="required-star">*</span>
            </div>
            <label class="radio-option">
                <input type="radio" name="agreement" value="1" required>
                <span>Ya, saya setuju</span>
            </label>
             @error('agreement')
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 5px;">
            <button type="submit" class="btn-submit">Kirim Pilihan</button>
            <button type="reset" class="clear-form">Kosongkan formulir</button>
        </div>

        <div class="footer-branding">
            SDIT AN NADZIR &copy; {{ date('Y') }}
            @php
                $footerText = \App\Models\Setting::where('key', 'app_credits')->value('value');
            @endphp
            @if($footerText)
                <br>
                <span style="font-size: 0.85em; font-weight: 400; color: #b2bec3;">{{ $footerText }}</span>
            @endif
        </div>
    </form>
</div>


<script>
    var studentsData = {};

    function loadStudents() {
        var classSelect = document.getElementById('class-select');
        var studentSelect = document.getElementById('student-select');
        var infoDiv = document.getElementById('current-eskul-info');
        
        // Reset info when class changes
        if(infoDiv) infoDiv.style.display = 'none';
        resetFormState();

        var selectedClass = classSelect.value;
        var oldStudentId = "{{ old('student_id') }}";

        // Filter eskul options based on class
        filterEskulOptions(selectedClass);

        if (!selectedClass) {
            studentSelect.innerHTML = '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
            studentSelect.disabled = true;
            return;
        }

        studentSelect.innerHTML = '<option value="">Memuat data...</option>';
        studentSelect.disabled = true;

        fetch('{{ route("pilihan-eskul.students") }}?class=' + selectedClass)
            .then(response => response.json())
            .then(data => {
                studentsData = {}; // Clear previous data
                if (data.length > 0) {
                    var options = '<option value="">-- Pilih Nama Siswa --</option>';
                    data.forEach(function(student) {
                        studentsData[student.id] = student;
                        var selected = (oldStudentId == student.id) ? 'selected' : '';
                        options += '<option value="' + student.id + '" ' + selected + '>' + student.name + '</option>';
                    });
                    studentSelect.innerHTML = options;
                    studentSelect.disabled = false;

                    // Trigger change if we have an old value to show info
                    if (oldStudentId) {
                        setTimeout(() => {
                           var event = new Event('change');
                           studentSelect.dispatchEvent(event);
                        }, 50);
                    }
                } else {
                    studentSelect.innerHTML = '<option value="">Tidak ada data siswa di kelas ini</option>';
                    studentSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                studentSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    }

    function resetFormState() {
        var radios = document.querySelectorAll('input[name="eskul_1"]');
        var submitBtn = document.querySelector('.btn-submit');
        
        radios.forEach(r => {
             // Only enable if NOT full
             if (r.getAttribute('data-is-full') !== 'true') {
                 r.disabled = false;
                 r.parentElement.style.opacity = '1';
                 r.parentElement.style.cursor = 'pointer';
             }
        });
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
        submitBtn.innerHTML = 'Kirim Pilihan';
    }

    // Listener for student selection changes
    document.getElementById('student-select').addEventListener('change', function() {
        var studentId = this.value;
        var infoDiv = document.getElementById('current-eskul-info');
        if (!infoDiv) return;

        // Reset first
        resetFormState();
        infoDiv.style.display = 'none';

        var selectedClass = document.getElementById('class-select').value;

        if (studentId && studentsData[studentId]) {
            var s = studentsData[studentId];
            
            // Re-filter eskul list based on student's specific can_choose_sesi_2 status
            filterEskulOptions(selectedClass, s.can_choose_sesi_2);
            
            // Check for Lock
            if (s.is_locked) {
                // LOCK STATE
                infoDiv.style.display = 'block';
                infoDiv.style.backgroundColor = '#ffebee';
                infoDiv.style.color = '#c62828';
                infoDiv.style.border = '1px solid #ffcdd2';
                infoDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <b>PERHATIAN!</b><br>' + s.lock_message;
                
                // Disable Inputs
                var radios = document.querySelectorAll('input[name="eskul_1"]');
                radios.forEach(r => {
                    r.disabled = true;
                    r.checked = false;
                    r.parentElement.style.opacity = '0.5';
                    r.parentElement.style.cursor = 'not-allowed';
                });
                
                // Disable Submit
                var submitBtn = document.querySelector('.btn-submit');
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.innerHTML = 'Terkunci (Wajib Calistung)';
                
            } else if (s.is_already_registered) {
                 // ALREADY REGISTERED (SEM 1 BLOCK)
                 infoDiv.style.display = 'block';
                 infoDiv.style.backgroundColor = '#e0f7fa'; // Light cyan
                 infoDiv.style.color = '#006064'; // Dark cyan
                 infoDiv.style.border = '1px solid #b2ebf2';
                 infoDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + s.already_registered_msg;

                 // Disable Inputs
                 var radios = document.querySelectorAll('input[name="eskul_1"]');
                 radios.forEach(r => {
                    r.disabled = true;
                    r.checked = false;
                    r.parentElement.style.opacity = '0.5';
                    r.parentElement.style.cursor = 'not-allowed';
                 });
                 
                 // Disable Submit
                 var submitBtn = document.querySelector('.btn-submit');
                 submitBtn.disabled = true;
                 submitBtn.style.opacity = '0.5';
                 submitBtn.innerHTML = 'Sudah Terdaftar';

            } else if (s.current_eskul) {
                 // NORMAL INFO STATE (Usually for Sem 2 transfer info)
                 infoDiv.style.display = 'block';
                 infoDiv.style.backgroundColor = '#e8f0fe';
                 infoDiv.style.color = '#1967d2';
                 infoDiv.style.border = 'none';
                 infoDiv.innerHTML = '<i class="fas fa-info-circle"></i> Siswa ini terdaftar di eskul: <b>' + s.current_eskul + '</b>. <br>Memilih eskul baru akan otomatis menggantikan data lama.';
            }
        }
    });

    const form = document.querySelector('form');

    // Clear form on clear button
    document.querySelector('.clear-form').addEventListener('click', (e) => {
        e.preventDefault();
        Swal.fire({
            title: 'Bersihkan Formulir?',
            text: "Seluruh data yang telah diisi akan dikosongkan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2980b9',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Bersihkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.reset();
                location.reload();
            }
        });
    });

    // Clear draft on successful submit (handled by redirect usually, but good practice to clear)
    form.addEventListener('submit', () => {
        // We don't clear immediately because submit might fail validation.
        // We let the Success page or redirect handle it or just keep it until next time.
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonColor: '#2980b9'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#2980b9'
        });
    @endif

    // Filter eskul based on student's class group
    function filterEskulOptions(className, canChooseSesi2 = false) {
        let studentGroup = 'all';
        if (className) {
            if (className.startsWith('1')) {
                studentGroup = canChooseSesi2 ? 'sesi_2' : 'sesi_1';
            } else if (className.startsWith('2') || className.startsWith('3')) {
                studentGroup = 'sesi_2';
            } else if (className.startsWith('4') || className.startsWith('5') || className.startsWith('6')) {
                studentGroup = 'sesi_3';
            }
        }

        let options = document.querySelectorAll('.eskul-option');
        options.forEach(opt => {
            let targetGroup = opt.getAttribute('data-target-group');
            let radio = opt.querySelector('input[type="radio"]');

            if (!className || targetGroup === 'all' || targetGroup === studentGroup) {
                opt.style.display = 'flex';
                let isFull = radio.getAttribute('data-is-full') === 'true';
                if (!isFull) {
                    radio.disabled = false;
                }
            } else {
                opt.style.display = 'none';
                radio.disabled = true;
                radio.checked = false;
            }
        });
    }

    // Trigger initial filter on load if class is pre-selected
    document.addEventListener('DOMContentLoaded', function() {
        var classSelect = document.getElementById('class-select');
        if (classSelect && classSelect.value) {
            loadStudents();
            filterEskulOptions(classSelect.value);
        }
    });
</script>
</body>
</html>
