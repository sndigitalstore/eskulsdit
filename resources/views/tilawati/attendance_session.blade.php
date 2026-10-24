@extends('layouts.app')

@section('title', 'Detail Sesi Pertemuan Tilawati - ' . $group->name)
@section('page-title', 'Detail Sesi Tilawati')

@push('styles')
<style>
    .session-container {
        max-width: 980px;
        margin: 0 auto;
        padding-bottom: 50px;
    }
    .session-hero {
        background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
        border-radius: 20px;
        padding: 24px 28px;
        color: white;
        margin-bottom: 22px;
        box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    .wa-share-banner {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border: 1.5px solid #6ee7b7;
        border-radius: 18px;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.12);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
</style>
@endpush

@section('content')
<div class="session-container">

    <!-- Top Navigation -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
        <a href="{{ route('tilawati.attendance.index', $group->id) }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat Pertemuan
        </a>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('tilawati.attendance.create', ['group' => $group->id, 'date' => $date]) }}" style="background: white; border: 1.5px solid #cbd5e1; color: #1e293b; padding: 7px 14px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; text-decoration: none;">
                <i class="fas fa-edit"></i> Edit Sesi Ini
            </a>
            <a href="{{ route('tilawati.attendance.print-session', ['group' => $group->id, 'date' => $date]) }}" target="_blank" style="background: #334155; color: white; padding: 7px 14px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; text-decoration: none;">
                <i class="fas fa-print"></i> Cetak Lembar Sesi
            </a>
        </div>
    </div>

    <!-- Hero Card -->
    @php
        $formattedDate = \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y');
        $jilid = $session->jilid ?? ($group->target_level ?: 'Jilid 1');
        $peragaStr = $session ? "Hal {$session->peraga_page_start} s/d {$session->peraga_page_end}" : '-';
        $bukuStr = $session ? "Halaman {$session->buku_page}" : '-';
    @endphp
    <div class="session-hero">
        <div>
            <span style="background: rgba(255,255,255,0.2); font-size: 0.74rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; text-transform: uppercase;">
                {{ $formattedDate }}
            </span>
            <h2 style="font-size: 1.6rem; font-weight: 900; margin: 8px 0 4px 0;">
                {{ $group->name }}
            </h2>
            <div style="font-size: 0.88rem; opacity: 0.92; display: flex; gap: 14px; flex-wrap: wrap;">
                <span><i class="fas fa-chalkboard-teacher"></i> Pembimbing: <strong>{{ $group->mentor_name }}</strong></span>
                <span><i class="fas fa-book-reader"></i> {{ $jilid }}</span>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); border-radius: 14px; padding: 12px 20px; text-align: right;">
            <div style="font-size: 0.78rem; opacity: 0.85; text-transform: uppercase; font-weight: 700;">Materi Sesi:</div>
            <div style="font-size: 1rem; font-weight: 900; margin-top: 2px;">
                Peraga: {{ $peragaStr }}
            </div>
            <div style="font-size: 0.95rem; font-weight: 800; color: #fef08a;">
                Buku: {{ $bukuStr }}
            </div>
        </div>
    </div>

    <!-- WhatsApp Share Sekali Klik Banner -->
    @php
        // Bangun teks pesan WA
        $msgWa = "*REKAPAN PEMBELAJARAN TILAWATI SDIT AN NADZIR*\n";
        $msgWa .= "━━━━━━━━━━━━━━━━━━━━━\n";
        $msgWa .= "📖 *Kelompok:* {$group->name}\n";
        $msgWa .= "👨‍🏫 *Pembimbing:* {$group->mentor_name}\n";
        $msgWa .= "📅 *Hari, Tanggal:* {$formattedDate}\n";
        $msgWa .= "📚 *Tingkat / Jilid:* {$jilid}\n";
        $msgWa .= "📋 *Materi Pertemuan:*\n";
        $msgWa .= "   • Alat Peraga: *{$peragaStr}* (4 Halaman)\n";
        $msgWa .= "   • Buku Tilawati: *{$bukuStr}* (1 Halaman)\n";
        $msgWa .= "─────────────────────\n";
        $msgWa .= "📊 *Statistik Kelas:*\n";
        $msgWa .= "• Hadir: {$hadirCount} santri\n";
        $msgWa .= "• Lanjut (≥ 70%): {$passedCount} santri\n";
        if ($repeatCount > 0) {
            $msgWa .= "• Mengulang (< 70%): {$repeatCount} santri\n";
        }
        $msgWa .= "─────────────────────\n";
        $msgWa .= "*Catatan Evaluasi Santri:*\n";

        foreach ($attendances as $idx => $att) {
            $no = $idx + 1;
            $sName = $att->student->name ?? 'Santri';
            if ($att->status === 'hadir') {
                $statusIcon = $att->is_passed ? '✅ Lanjut' : '🔄 Mengulang';
                $scoreStr = $att->score !== null ? " (Nilai: {$att->score})" : '';
                $msgWa .= "{$no}. *{$sName}*: {$statusIcon}{$scoreStr}\n";
                if (!empty($att->notes)) {
                    $msgWa .= "   _Catatan: {$att->notes}_\n";
                }
            } else {
                $stLabel = strtoupper($att->status);
                $msgWa .= "{$no}. *{$sName}*: ⚠️ {$stLabel}\n";
            }
        }
        $msgWa .= "━━━━━━━━━━━━━━━━━━━━━\n";
        $msgWa .= "Barakallahu Fiikum. Mohon Ayah/Bunda senantiasa mendampingi tilawah ananda di rumah.\n";

        $waUrl = "https://api.whatsapp.com/send?text=" . urlencode($msgWa);
    @endphp

    <div class="wa-share-banner">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 48px; height: 48px; border-radius: 14px; background: #25D366; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35);">
                <i class="fab fa-whatsapp"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #064e3b;">
                    Share Rekapan Pertemuan ke Grup WhatsApp
                </h4>
                <p style="margin: 2px 0 0 0; font-size: 0.82rem; color: #047857;">
                    Teruskan otomatis format rekapan 4 halaman alat peraga & 1 halaman buku ke grup kelas / wali murid dengan sekali klik.
                </p>
            </div>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ $waUrl }}" target="_blank" 
               style="background: #25D366; color: white; padding: 10px 18px; border-radius: 12px; font-size: 0.88rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);">
                <i class="fab fa-whatsapp" style="font-size: 1.15rem;"></i> Kirim ke Grup WA
            </a>
            <button type="button" onclick="copyWaText()" id="btnCopyWa"
                    style="background: white; border: 1.5px solid #86efac; color: #166534; padding: 10px 16px; border-radius: 12px; font-size: 0.86rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-copy"></i> Salin Teks
            </button>
        </div>
    </div>

    <!-- Hidden textarea for copy -->
    <textarea id="hiddenWaText" style="position: absolute; left: -9999px;">{{ $msgWa }}</textarea>

    <!-- Table Evaluasi Santri Sesi Ini -->
    <div class="card" style="padding: 0; overflow: hidden; border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        <div style="padding: 16px 22px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">
                <i class="fas fa-list-check" style="color: #059669;"></i> Rincian Nilai & Ketuntasan Santri
            </h3>
            <div style="display: flex; gap: 10px; font-size: 0.82rem; font-weight: 800;">
                <span style="color: #15803d; background: #dcfce7; padding: 3px 10px; border-radius: 8px;">
                    ✓ {{ $passedCount }} Lanjut
                </span>
                <span style="color: #b91c1c; background: #fee2e2; padding: 3px 10px; border-radius: 8px;">
                    ↻ {{ $repeatCount }} Mengulang
                </span>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; text-align: left;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-weight: 800; font-size: 0.82rem; text-transform: uppercase;">
                        <th style="padding: 12px 18px; width: 50px;">No</th>
                        <th style="padding: 12px 18px;">Nama Santri</th>
                        <th style="padding: 12px 18px; text-align: center;">Kehadiran</th>
                        <th style="padding: 12px 18px; text-align: center;">Nilai (Skor)</th>
                        <th style="padding: 12px 18px; text-align: center;">Ketuntasan</th>
                        <th style="padding: 12px 18px;">Tugas Pertemuan Berikutnya</th>
                        <th style="padding: 12px 18px;">Catatan Guru</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $idx => $item)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                            <td style="padding: 12px 18px; color: #64748b; font-weight: 700;">{{ $idx + 1 }}</td>
                            <td style="padding: 12px 18px;">
                                <div style="font-weight: 800; color: #0f172a;">{{ $item->student->name ?? '-' }}</div>
                                <small style="color: #64748b; font-size: 0.78rem;">Kelas: {{ $item->student->class ?? '-' }}</small>
                            </td>
                            <td style="padding: 12px 18px; text-align: center;">
                                @if($item->status === 'hadir')
                                    <span style="background: #ecfdf5; color: #059669; font-weight: 800; padding: 3px 8px; border-radius: 6px; font-size: 0.76rem;">HADIR</span>
                                @elseif($item->status === 'sakit')
                                    <span style="background: #fef3c7; color: #b45309; font-weight: 800; padding: 3px 8px; border-radius: 6px; font-size: 0.76rem;">SAKIT</span>
                                @elseif($item->status === 'izin')
                                    <span style="background: #eff6ff; color: #2563eb; font-weight: 800; padding: 3px 8px; border-radius: 6px; font-size: 0.76rem;">IZIN</span>
                                @else
                                    <span style="background: #fee2e2; color: #dc2626; font-weight: 800; padding: 3px 8px; border-radius: 6px; font-size: 0.76rem;">ALPHA</span>
                                @endif
                            </td>
                            <td style="padding: 12px 18px; text-align: center;">
                                @if($item->score !== null)
                                    <strong style="font-size: 1.05rem; color: #0f172a;">{{ $item->score }}</strong>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td style="padding: 12px 18px; text-align: center;">
                                @if($item->status === 'hadir')
                                    @if($item->is_passed)
                                        <span style="background: #dcfce7; color: #15803d; font-weight: 800; padding: 4px 10px; border-radius: 20px; font-size: 0.76rem;">
                                            ✓ LANJUT
                                        </span>
                                    @else
                                        <span style="background: #fee2e2; color: #b91c1c; font-weight: 800; padding: 4px 10px; border-radius: 20px; font-size: 0.76rem;">
                                            ↻ MENGULANG
                                        </span>
                                    @endif
                                @else
                                    <span style="color: #94a3b8; font-size: 0.8rem;">-</span>
                                @endif
                            </td>
                            <td style="padding: 12px 18px; font-size: 0.82rem; color: #475569;">
                                {{ $item->tugas_besok ?? '-' }}
                            </td>
                            <td style="padding: 12px 18px; font-size: 0.82rem; color: #64748b;">
                                {{ $item->notes ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function copyWaText() {
    const copyText = document.getElementById("hiddenWaText");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);

    const btn = document.getElementById("btnCopyWa");
    btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
    btn.style.background = '#059669';
    btn.style.color = 'white';
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-copy"></i> Salin Teks';
        btn.style.background = 'white';
        btn.style.color = '#166534';
    }, 2000);
}
</script>
@endsection
