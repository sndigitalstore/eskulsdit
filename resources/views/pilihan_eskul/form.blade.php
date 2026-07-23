<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bg-color: #f1f5f9;
            --primary-color: #10b981;
            --primary-dark: #047857;
            --border-color: rgba(226, 232, 240, 0.8);
            --error-color: #ef4444;
            --text-color: #0f172a;
            --text-muted: #64748b;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 50%, #f8fafc 100%);
            background-attachment: fixed;
            padding: 40px 20px;
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }

        /* Ambient Pastel Blobs */
        .bg-shapes {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.5;
        }

        .shape-1 { width: 500px; height: 500px; background: #c7d2fe; top: -100px; right: -100px; }
        .shape-2 { width: 450px; height: 450px; background: #a7f3d0; bottom: -100px; left: -100px; }

        .container {
            width: 100%;
            max-width: 680px;
            z-index: 10;
            position: relative;
        }

        .card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 26px;
            border: 1px solid rgba(255, 255, 255, 0.95);
            padding: 30px 34px;
            margin-bottom: 22px;
            position: relative;
            box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.05);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: fadeInUp 0.5s ease-out both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px -5px rgba(15, 23, 42, 0.08);
        }

        .form-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.85rem;
            font-weight: 800;
            margin: 0 0 10px 0;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .form-description {
            font-size: 0.98rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .question-label {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 12px;
            display: block;
            color: #0f172a;
        }

        .required-star {
            color: var(--error-color);
            margin-left: 4px;
        }

        .input-text {
            width: 100%;
            padding: 14px 20px;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            font-size: 0.98rem;
            outline: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            background: #ffffff;
            font-family: inherit;
            color: #0f172a;
            font-weight: 500;
        }

        .input-text:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.16), 0 4px 12px rgba(16, 185, 129, 0.08);
        }

        select.input-select {
            width: 100%;
            padding: 14px 44px 14px 20px;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            font-size: 0.98rem;
            outline: none;
            background: #ffffff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E") no-repeat right 16px center/16px 16px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            color: #0f172a;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.02);
        }

        select.input-select:hover {
            border-color: #94a3b8;
            background-color: #f8fafc;
        }

        select.input-select:focus {
            border-color: var(--primary-color);
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.16), 0 4px 12px rgba(16, 185, 129, 0.08);
        }

        .radio-option {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            cursor: pointer;
            border-radius: 16px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 10px;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);
        }

        .radio-option:hover {
            background-color: #f0fdf4;
            border-color: #a7f3d0;
            transform: translateX(4px) translateY(-1px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.08);
        }

        .radio-option:has(input[type="radio"]:checked) {
            background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%);
            border-color: #10b981;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.18);
        }

        .radio-option input[type="radio"] {
            margin-right: 14px;
            width: 22px;
            height: 22px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }

        .radio-option span {
            font-size: 0.98rem;
            font-weight: 700;
            color: #0f172a;
        }

        .btn-submit {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 16px 40px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.45);
        }

        .clear-form {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            background: none;
            padding: 12px 16px;
            transition: color 0.2s;
        }

        .clear-form:hover {
            color: var(--error-color);
        }

        .error-msg {
            color: var(--error-color);
            font-size: 0.88rem;
            margin-top: 8px;
            display: flex;
            align-items: center;
            font-weight: 600;
        }

        .error-msg i { margin-right: 6px; }
        
        .footer-branding {
            text-align: center;
            margin-top: 36px;
            font-size: 0.88rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        @media (max-width: 480px) {
            body { padding: 20px 12px; }
            .card { padding: 20px; }
            .form-header h1 { font-size: 1.45rem; }
        }
    </style>
</head>
<body>

    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

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
                    <option value="{{ $cls }}" {{ old('class') == $cls ? 'selected' : '' }}>{{ $cls }}</option>
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
                <label class="radio-option eskul-option" data-target-group="{{ json_encode($eskul->target_groups) }}" style="{{ $isFull ? 'opacity: 0.6; cursor: not-allowed;' : '' }}">
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
        let studentGroup = null;
        if (className) {
            if (className.startsWith('1')) {
                studentGroup = canChooseSesi2 ? 'sesi_2' : 'sesi_1';
            } else if (className.startsWith('2')) {
                studentGroup = 'sesi_2';
            } else if (className.startsWith('3')) {
                studentGroup = 'sesi_3';
            } else if (className.startsWith('4') || className.startsWith('5') || className.startsWith('6')) {
                studentGroup = 'sesi_4';
            }
        }

        let options = document.querySelectorAll('.eskul-option');
        options.forEach(opt => {
            let targetGroups;
            try {
                targetGroups = JSON.parse(opt.getAttribute('data-target-group'));
            } catch(e) {
                targetGroups = [opt.getAttribute('data-target-group')];
            }
            let radio = opt.querySelector('input[type="radio"]');

            // Tampilkan jika: tidak ada filter, eskul untuk 'all', atau studentGroup cocok
            let show = !className || targetGroups.includes('all') || (studentGroup && targetGroups.includes(studentGroup));

            if (show) {
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
