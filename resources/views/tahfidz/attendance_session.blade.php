@extends('layouts.app')

@section('title', 'Detail Sesi Mentoring Tahfidz – ' . \Carbon\Carbon::parse($date)->isoFormat('D MMMM Y'))
@section('page-title', 'Detail Sesi & Laporan Tahfidz')

@section('content')

@php
    $statusColor = fn($s) => match($s) {
        'hadir' => ['bg'=>'#dcfce7','text'=>'#16a34a'],
        'sakit' => ['bg'=>'#fef3c7','text'=>'#d97706'],
        'izin'  => ['bg'=>'#e0f2fe','text'=>'#0369a1'],
        'alpha' => ['bg'=>'#fee2e2','text'=>'#dc2626'],
        default => ['bg'=>'#f1f5f9','text'=>'#64748b'],
    };
    $statusLabel = fn($s) => match($s) {
        'hadir' => 'Hadir', 'sakit' => 'Sakit',
        'izin'  => 'Izin',  'alpha' => 'Alpha', default => '-',
    };
    $hadir = $records->where('status','hadir')->count();
    $sakit = $records->where('status','sakit')->count();
    $izin  = $records->where('status','izin')->count();
    $alpha = $records->where('status','alpha')->count();
    $total = $records->count();
    $lulusCount = $records->where('status', 'hadir')->where('hafalan_status', 'LULUS')->count();
    $sessionDate = \Carbon\Carbon::parse($date);
@endphp

<style>
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 16px; font-weight: 600; }
.back-link:hover { color: #0f766e; }
.summary-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 20px; }
@media (max-width: 768px) {
    .summary-grid { grid-template-columns: repeat(2, 1fr); }
}
.summary-card { border-radius: 12px; padding: 14px; text-align: center; }
.summary-card .num { font-size: 1.8rem; font-weight: 900; line-height: 1; }
.summary-card .lbl { font-size: 0.72rem; font-weight: 700; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px; }

.record-table {
    width: 100%; border-collapse: collapse; margin-top: 0;
}
.record-table th {
    background: #f8fafc; border-bottom: 2px solid #e2e8f0; padding: 12px 14px;
    font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; text-align: left;
}
.record-table td {
    padding: 14px; border-bottom: 1px solid #f1f5f9; vertical-align: top; font-size: 0.88rem;
}
.record-table tr:hover td { background: #fbfcfe; }

.avatar { width: 36px; height: 36px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0; }
.status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
.lulus-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; }

/* Modal Styles */
.modal-overlay {
    display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%;
    background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px);
    align-items: center; justify-content: center; animation: fadeIn 0.2s ease-out;
}
.modal-overlay.active { display: flex; }
.modal-box {
    background: white; width: 92%; max-width: 650px; border-radius: 18px;
    box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25); display: flex; flex-direction: column;
    max-height: 88vh; animation: popIn 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.modal-header-wa {
    background: linear-gradient(135deg, #128c7e 0%, #075e54 100%);
    padding: 18px 24px; color: white; border-radius: 18px 18px 0 0;
    display: flex; justify-content: space-between; align-items: center;
}
.modal-body-wa { padding: 20px 24px; overflow-y: auto; flex: 1; }
.modal-footer-wa {
    padding: 14px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0;
    border-radius: 0 0 18px 18px; display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap;
}
</style>

<div style="max-width: 1050px; margin: 0 auto; margin-bottom: 40px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
        <a href="{{ route('tahfidz.attendance.index', ['group' => $group->id, 'year_id' => $yearId]) }}" class="back-link" style="margin-bottom: 0;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Sesi
        </a>
        <div style="display: flex; gap: 8px;">
            <button type="button" onclick="openShareModal()" class="btn-action-header" style="background: #25d366; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(37,211,102,0.35);">
                <i class="fab fa-whatsapp" style="font-size: 1.1rem;"></i> Share Laporan ke WA Ortu
            </button>
            <button type="button" onclick="copyWhatsappText()" class="btn-action-header btn-dark" style="background: #334155; padding: 8px 14px;">
                <i class="fas fa-copy"></i> Salin Format WA
            </button>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i>
                <strong>{{ session('success') }}</strong>
            </div>
            <button type="button" onclick="openShareModal()" style="background: #16a34a; color: white; border: none; padding: 6px 14px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                <i class="fab fa-whatsapp"></i> Buka Format WA
            </button>
        </div>
    @endif

    {{-- HEADER CARD --}}
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 20px; border-radius: 16px;">
        <div style="background: linear-gradient(135deg, #1e3a5f, #0f766e); padding: 24px 28px; color: white; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div>
                <div style="font-size: 0.8rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                    Laporan Pelaksanaan Mentoring Tahfidz
                </div>
                <h3 style="margin: 0; font-size: 1.45rem; font-weight: 800;">
                    <i class="fas fa-calendar-check" style="opacity: 0.85; margin-right: 8px;"></i>
                    {{ $sessionDate->isoFormat('dddd, D MMMM Y') }}
                </h3>
                <div style="margin-top: 6px; font-size: 0.88rem; opacity: 0.85;">
                    Kelompok: <strong>{{ $group->name }}</strong> &nbsp;·&nbsp; Pembimbing: <strong>{{ $group->mentor_name }}</strong>
                </div>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="{{ route('tahfidz.attendance.create', ['group' => $group->id, 'date' => $date, 'year_id' => $yearId]) }}"
                   style="background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.3); color: white; text-decoration: none; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-edit"></i> Edit Sesi
                </a>
                <a href="{{ route('tahfidz.attendance.print-session', ['group' => $group->id, 'date' => $date]) }}" target="_blank"
                   style="background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.3); color: white; text-decoration: none; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-print"></i> Cetak
                </a>
                <form method="POST" action="{{ route('tahfidz.attendance.destroy-session', ['group' => $group->id]) }}" onsubmit="return confirm('Hapus seluruh absensi & capaian sesi ini?')">
                    @csrf @method('DELETE')
                    <input type="hidden" name="date" value="{{ $date }}">
                    <button type="submit" style="background: rgba(239,68,68,0.25); border: 1px solid rgba(239,68,68,0.4); color: white; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 600;">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- STATS GRID --}}
        <div style="padding: 18px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
            <div class="summary-grid">
                <div class="summary-card" style="background: #ecfdf5; border: 1px solid #a7f3d0;">
                    <div class="num" style="color: #059669;">{{ $lulusCount }}</div>
                    <div class="lbl" style="color: #059669;">✓ Lulus Capaian</div>
                </div>
                <div class="summary-card" style="background: #dcfce7; border: 1px solid #bbf7d0;">
                    <div class="num" style="color: #16a34a;">{{ $hadir }}</div>
                    <div class="lbl" style="color: #16a34a;">Hadir</div>
                </div>
                <div class="summary-card" style="background: #fef3c7; border: 1px solid #fde68a;">
                    <div class="num" style="color: #d97706;">{{ $sakit }}</div>
                    <div class="lbl" style="color: #d97706;">Sakit</div>
                </div>
                <div class="summary-card" style="background: #e0f2fe; border: 1px solid #bae6fd;">
                    <div class="num" style="color: #0369a1;">{{ $izin }}</div>
                    <div class="lbl" style="color: #0369a1;">Izin</div>
                </div>
                <div class="summary-card" style="background: #fee2e2; border: 1px solid #fecaca;">
                    <div class="num" style="color: #dc2626;">{{ $alpha }}</div>
                    <div class="lbl" style="color: #dc2626;">Alpha</div>
                </div>
            </div>
        </div>

        {{-- TABLE DETAIL CAPAIAN SISWA --}}
        <div style="overflow-x: auto;">
            <table class="record-table">
                <thead>
                    <tr>
                        <th style="width: 4%; text-align: center;">No</th>
                        <th style="width: 25%;">Nama Siswa</th>
                        <th style="width: 12%; text-align: center;">Kehadiran</th>
                        <th style="width: 22%;">📖 Capaian Hari Ini</th>
                        <th style="width: 12%; text-align: center;">Status</th>
                        <th style="width: 20%;">📝 Tugas Besok</th>
                        <th style="width: 5%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $i => $rec)
                        @php 
                            $sc = $statusColor($rec->status); 
                            $isLulus = (strtoupper($rec->hafalan_status ?? 'LULUS') === 'LULUS');
                        @endphp
                        <tr>
                            <td style="text-align: center; font-weight: 700; color: #94a3b8;">{{ $i + 1 }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="avatar">{{ substr($rec->student->name, 0, 1) }}</div>
                                    <div>
                                        <div style="font-weight: 800; color: #0f172a;">{{ $rec->student->name }}</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">
                                            Kelas {{ $rec->student->class }}{{ $rec->student->nis ? ' · NIS: '.$rec->student->nis : '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="status-badge" style="background: {{ $sc['bg'] }}; color: {{ $sc['text'] }};">
                                    {{ $statusLabel($rec->status) }}
                                </span>
                            </td>
                            <td>
                                @if($rec->status === 'hadir')
                                    <div style="font-weight: 700; color: #1e293b;">
                                        {{ $rec->capaian ?: '-' }}
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-style: italic;">Tidak hadir</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($rec->status === 'hadir')
                                    @if($isLulus)
                                        <span class="lulus-badge" style="background: #dcfce7; color: #166534; border: 1px solid #86efac;">
                                            ✓ LULUS
                                        </span>
                                    @else
                                        <span class="lulus-badge" style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;">
                                            ✗ BELUM
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($rec->status === 'hadir')
                                    <div style="font-weight: 600; color: #0369a1; font-size: 0.85rem;">
                                        {{ $rec->tugas_besok ?: '-' }}
                                    </div>
                                    @if($rec->notes)
                                        <div style="font-size: 0.78rem; color: #64748b; margin-top: 3px;">
                                            <i class="far fa-comment-dots"></i> {{ $rec->notes }}
                                        </div>
                                    @endif
                                @else
                                    <span style="font-size: 0.8rem; color: #64748b;">{{ $rec->notes ?: '-' }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($rec->student->parent_phone)
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $rec->student->parent_phone);
                                        if (str_starts_with($cleanPhone, '0')) {
                                            $cleanPhone = '62' . substr($cleanPhone, 1);
                                        }
                                        $statusStr = $isLulus ? "✓ LULUS" : "✗ BELUM LULUS";
                                        $singleMsg = "Assalamu'alaikum Warahmatullahi Wabarakatuh 🌙\n\n";
                                        $singleMsg .= "Ayah dan Bunda dari ananda *{$rec->student->name}* (Kelas {$rec->student->class}),\n\n";
                                        $singleMsg .= "Alhamdulillah, berikut kami sampaikan laporan capaian hafalan hari ini:\n";
                                        $singleMsg .= "📅 *Tanggal:* " . $sessionDate->isoFormat('dddd, D MMMM Y') . "\n";
                                        if ($rec->status === 'hadir') {
                                            $singleMsg .= "📖 *Capaian Hari Ini:* " . ($rec->capaian ?: '-') . "\n";
                                            $singleMsg .= "✅ *Status:* " . $statusStr . "\n";
                                            $singleMsg .= "📝 *Tugas Besok:* " . ($rec->tugas_besok ?: '-') . "\n";
                                            $singleMsg .= "💭 *Catatan Guru:* " . ($rec->notes ?: ($isLulus ? 'Barakallah..' : 'Tetap semangat ya sholih/sholihah..')) . "\n\n";
                                        } else {
                                            $singleMsg .= "📌 *Status Kehadiran:* " . $statusLabel($rec->status) . "\n";
                                            if ($rec->notes) $singleMsg .= "💭 *Catatan Guru:* " . $rec->notes . "\n\n";
                                        }
                                        $singleMsg .= "Terima kasih atas kerja sama dan bimbingan Ayah & Bunda di rumah. 🤲\n\n_SDIT AN NADZIR_";
                                        $waSingleUrl = "https://api.whatsapp.com/send?phone=" . $cleanPhone . "&text=" . urlencode($singleMsg);
                                    @endphp
                                    <a href="{{ $waSingleUrl }}" target="_blank" title="Kirim WA Personal ke Orang Tua Siswa Ini" style="background: #25d366; color: white; padding: 6px 10px; border-radius: 6px; font-size: 0.82rem; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fab fa-whatsapp"></i> Ortu
                                    </a>
                                @else
                                    <span style="font-size: 0.72rem; color: #94a3b8;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                                Belum ada data sesi pada tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL SHARE WHATSAPP --}}
<div id="shareWhatsappModal" class="modal-overlay {{ $openShare ? 'active' : '' }}">
    <div class="modal-box">
        <div class="modal-header-wa">
            <div>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                    <i class="fab fa-whatsapp" style="font-size: 1.35rem;"></i> Format Laporan WhatsApp
                </h3>
                <small style="opacity: 0.9; font-size: 0.78rem;">Siap dibagikan ke grup WhatsApp orang tua / wali murid.</small>
            </div>
            <button type="button" onclick="closeShareModal()" style="background: none; border: none; color: white; font-size: 1.3rem; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body-wa">
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.4px;">
                Teks Pesan (Dapat Anda sesuaikan sebelum disalin/dikirim):
            </label>
            <textarea id="whatsappTextContent" rows="14" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 14px; font-family: monospace; font-size: 0.85rem; line-height: 1.5; color: #0f172a; background: #f8fafc; box-sizing: border-box; resize: vertical;">{{ $whatsappMessage }}</textarea>
        </div>

        <div class="modal-footer-wa">
            <button type="button" onclick="closeShareModal()" class="btn-action-header btn-dark" style="background: #94a3b8; padding: 9px 18px;">
                Tutup
            </button>
            <button type="button" onclick="copyWhatsappText()" class="btn-action-header btn-dark" style="background: #334155; padding: 9px 18px;">
                <i class="fas fa-copy"></i> Salin Teks
            </button>
            <button type="button" onclick="sendWhatsappDirect()" class="btn-action-header" style="background: #25d366; color: white; padding: 9px 22px; font-weight: 700; box-shadow: 0 4px 12px rgba(37,211,102,0.35);">
                <i class="fab fa-whatsapp"></i> Buka WhatsApp
            </button>
        </div>
    </div>
</div>

<script>
function openShareModal() {
    document.getElementById('shareWhatsappModal').classList.add('active');
}

function closeShareModal() {
    document.getElementById('shareWhatsappModal').classList.remove('active');
}

function copyWhatsappText() {
    const textarea = document.getElementById('whatsappTextContent');
    textarea.select();
    textarea.setSelectionRange(0, 99999); // Mobile
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(textarea.value).then(() => {
            showCopyToast();
        });
    } else {
        document.execCommand('copy');
        showCopyToast();
    }
}

function showCopyToast() {
    if (typeof Toast !== 'undefined') {
        Toast.fire({
            icon: 'success',
            title: 'Teks format WhatsApp berhasil disalin ke clipboard! Siap dipaste ke grup WA.'
        });
    } else if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Disalin!',
            text: 'Teks laporan telah disalin ke clipboard.',
            timer: 2500,
            showConfirmButton: false
        });
    } else {
        alert('Teks format WhatsApp berhasil disalin ke clipboard!');
    }
}

function sendWhatsappDirect() {
    const text = document.getElementById('whatsappTextContent').value;
    const url = `https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
}

// Close modal on outside click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('shareWhatsappModal');
    if (e.target === modal) {
        closeShareModal();
    }
});
</script>

@endsection
