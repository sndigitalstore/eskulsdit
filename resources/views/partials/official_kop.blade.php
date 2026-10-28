{{-- 
    OFFICIAL SCHOOL LETTERHEAD (KOP SURAT RESMI)
    Format visual resmi SDIT AN NADZIR (Sesuai dokumen fisik resmi sekolah)
--}}
<div class="official-kop-container" style="width: 100%; margin-bottom: 18px;">
    @php
        $logoPath = public_path('logo.png');
        $logoDataUri = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : asset('logo.png');
    @endphp

    <svg viewBox="0 0 800 120" style="width: 100%; height: auto; display: block; overflow: visible;" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <style>
                .kop-inst-name {
                    font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
                    font-size: 14.5px;
                    font-weight: 700;
                    fill: #005b75;
                    letter-spacing: 0.8px;
                }
                .kop-main-title {
                    font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
                    font-size: 19.5px;
                    font-weight: 900;
                    fill: #ffffff;
                    letter-spacing: 0.4px;
                }
                .kop-detail-text {
                    font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
                    font-size: 9.3px;
                    font-weight: 600;
                    fill: #00566e;
                    letter-spacing: 0.1px;
                }
            </style>
        </defs>

        <!-- Ribbon 3D Fold / Shadow Triangle Underneath -->
        <polygon points="162,79 192,79 162,94" fill="#00465a" />

        <!-- Main Cyan Ribbon Shape (Logo Box + Slant + School Name Banner) -->
        <polygon points="10,6 162,6 202,35 790,35 790,79 162,79 162,94 10,94" fill="#0082a6" />

        <!-- School Logo centered in the left box -->
        <image href="{{ $logoDataUri }}" x="42" y="11" width="78" height="78" preserveAspectRatio="xMidYMid meet" />

        <!-- Institution Name above the banner -->
        <text x="210" y="26" class="kop-inst-name">AN NADZIR ISLAMIC SCHOOL SERANG BANTEN</text>

        <!-- Main School Name inside the cyan banner -->
        <text x="210" y="63" class="kop-main-title">SEKOLAH DASAR ISLAM TERPADU AN NADZIR</text>

        <!-- Address Line 1 -->
        <text x="210" y="96" class="kop-detail-text">Bendungan Karet Cisirih Kp. Cukang RT. 09/02 Desa Kamasan, Kec. Cinangka, Kab. Serang - Banten 42167</text>

        <!-- Address Line 2 (Contact Info) -->
        <text x="210" y="110" class="kop-detail-text">e-mail : sdit_annadzir@hotmail.com &nbsp;&nbsp;&nbsp; web : annadzir.sch.id &nbsp;&nbsp;&nbsp; whatsapp : 0851 6125 2008</text>
    </svg>

    <!-- Elegant Bottom Teal Accent Line -->
    <div style="border-bottom: 2px solid #0082a6; margin-top: 8px; margin-bottom: 16px;"></div>
</div>
