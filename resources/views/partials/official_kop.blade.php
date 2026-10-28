{{-- 
    OFFICIAL SCHOOL LETTERHEAD (KOP SURAT RESMI)
    Format visual resmi SDIT AN NADZIR (Ekstraksi langsung dari dokumen Word resmi sekolah)
--}}
<div class="official-kop-container" style="width: 100%; margin-bottom: 22px;">
    @php
        $kopPath = public_path('kop_official.png');
        $kopSrc = file_exists($kopPath) 
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($kopPath)) 
            : asset('kop_official.png');
    @endphp

    <img src="{{ $kopSrc }}" 
         alt="Kop Surat Resmi SDIT AN NADZIR" 
         class="official-kop-img"
         style="width: 100%; height: auto; display: block; max-width: 100%; object-fit: contain; margin: 0 auto;">
</div>
