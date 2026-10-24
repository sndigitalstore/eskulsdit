@extends('layouts.app')

@section('title', 'Rekap Bulanan Tilawati - ' . $group->name)
@section('page-title', 'Rekap Bulanan Tilawati')

@push('styles')
<style>
    .recap-container {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 50px;
    }
    .recap-hero {
        background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0d9488 100%);
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
    .table-recap th, .table-recap td {
        border: 1px solid #e2e8f0;
        padding: 10px 12px;
        font-size: 0.84rem;
    }
</style>
@endpush

@section('content')
<div class="recap-container">

    <!-- Top Navigation -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
        <a href="{{ route('tilawati.attendance.index', $group->id) }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat Pertemuan
        </a>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('tilawati.attendance.monthly.print', ['group' => $group->id, 'month' => $month, 'year' => $year]) }}" target="_blank"
               style="background: #334155; color: white; padding: 8px 16px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-print"></i> Cetak Rekap Bulanan
            </a>
        </div>
    </div>

    <!-- Hero Card -->
    <div class="recap-hero">
        <div>
            <span style="background: rgba(255,255,255,0.2); font-size: 0.74rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; text-transform: uppercase;">
                Laporan & Rekapitulasi Bulanan
            </span>
            <h2 style="font-size: 1.6rem; font-weight: 900; margin: 8px 0 4px 0;">
                {{ $group->name }}
            </h2>
            <div style="font-size: 0.88rem; opacity: 0.92; display: flex; gap: 16px; flex-wrap: wrap;">
                <span><i class="fas fa-chalkboard-teacher"></i> Pembimbing: <strong>{{ $group->mentor_name }}</strong></span>
                <span><i class="fas fa-book-reader"></i> {{ $group->target_level ?: 'Jilid 1' }}</span>
                <span><i class="fas fa-calendar-check"></i> Bulan: <strong>{{ $monthNames[$month] }} {{ $year }}</strong></span>
            </div>
        </div>

        <!-- Filter Bulan & Tahun Form -->
        <form action="{{ route('tilawati.attendance.monthly', $group->id) }}" method="GET" style="display: flex; gap: 8px; align-items: center; background: rgba(255,255,255,0.15); padding: 8px 12px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.25);">
            <select name="month" style="padding: 7px 10px; border-radius: 8px; border: none; font-weight: 700; font-size: 0.84rem; background: white; color: #0f172a;">
                @foreach($monthNames as $mNum => $mName)
                    <option value="{{ $mNum }}" {{ $month == $mNum ? 'selected' : '' }}>{{ $mName }}</option>
                @endforeach
            </select>
            <input type="number" name="year" value="{{ $year }}" style="width: 70px; padding: 7px 8px; border-radius: 8px; border: none; font-weight: 700; font-size: 0.84rem; background: white; color: #0f172a; text-align: center;">
            <button type="submit" style="background: #ffffff; color: #065f46; border: none; padding: 7px 12px; border-radius: 8px; font-weight: 800; font-size: 0.84rem; cursor: pointer;">
                Pilih
            </button>
        </form>
    </div>

    <!-- Matriks Rekap Bulanan Table Card -->
    <div class="card" style="padding: 0; overflow: hidden; border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        <div style="padding: 16px 22px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">
                <i class="fas fa-table" style="color: #059669;"></i> Matriks Kehadiran & Ketuntasan Santri ({{ count($sessions) }} Pertemuan)
            </h3>
            <span style="font-size: 0.78rem; color: #64748b;">
                Simbol: <strong style="color: #15803d;">✓</strong> = Lanjut (≥ 70%) | <strong style="color: #b91c1c;">↻</strong> = Ulang (&lt; 70%) | <strong style="color: #b45309;">S/I/A</strong> = Sakit/Izin/Alpha
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="table-recap" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #f8fafc; color: #475569; font-weight: 800; font-size: 0.8rem; text-transform: uppercase;">
                        <th style="padding: 12px 14px; width: 40px; text-align: center;">No</th>
                        <th style="padding: 12px 14px; min-width: 180px;">Nama Santri</th>
                        <th style="padding: 12px 14px; width: 70px; text-align: center;">Kelas</th>
                        
                        <!-- Kolom Tanggal Sesi -->
                        @foreach($sessions as $sess)
                            <th style="padding: 8px 6px; text-align: center; font-size: 0.74rem; min-width: 65px;" title="Peraga: Hal {{ $sess->peraga_page_start }}-{{ $sess->peraga_page_end }} | Buku: Hal {{ $sess->buku_page }}">
                                {{ $sess->session_date->format('d/m') }}
                                <div style="font-size: 0.68rem; color: #059669; font-weight: 700;">Buku: H.{{ $sess->buku_page }}</div>
                            </th>
                        @endforeach

                        <th style="padding: 12px 10px; text-align: center; width: 60px;">Hadir</th>
                        <th style="padding: 12px 10px; text-align: center; width: 60px;">Lanjut</th>
                        <th style="padding: 12px 10px; text-align: center; width: 60px;">Ulang</th>
                        <th style="padding: 12px 10px; text-align: center; width: 80px;">Ketercapaian</th>
                        <th style="padding: 12px 14px; min-width: 140px;">Posisi Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $idx => $st)
                        @php
                            $stats = $studentStats[$st->id] ?? ['present' => 0, 'passed' => 0, 'repeat' => 0, 'percentage' => 0, 'last_position' => '-'];
                            $stAtts = $attendances->get($st->id, collect())->keyBy(fn($item) => $item->session_date->format('Y-m-d'));
                        @endphp
                        <tr style="transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                            <td style="text-align: center; font-weight: 700; color: #64748b;">{{ $idx + 1 }}</td>
                            <td style="font-weight: 800; color: #0f172a;">{{ $st->name }}</td>
                            <td style="text-align: center; color: #64748b; font-size: 0.8rem;">{{ $st->class ?? '-' }}</td>

                            <!-- Matriks per Tanggal -->
                            @foreach($sessions as $sess)
                                @php
                                    $sDate = $sess->session_date->format('Y-m-d');
                                    $att = $stAtts->get($sDate);
                                @endphp
                                <td style="text-align: center; padding: 6px;">
                                    @if($att)
                                        @if($att->status === 'hadir')
                                            @if($att->is_passed)
                                                <span style="background: #dcfce7; color: #15803d; font-weight: 800; padding: 2px 6px; border-radius: 4px; font-size: 0.74rem;" title="Nilai: {{ $att->score }} (Lanjut)">
                                                    ✓ {{ $att->score }}
                                                </span>
                                            @else
                                                <span style="background: #fee2e2; color: #b91c1c; font-weight: 800; padding: 2px 6px; border-radius: 4px; font-size: 0.74rem;" title="Nilai: {{ $att->score }} (Mengulang)">
                                                    ↻ {{ $att->score }}
                                                </span>
                                            @endif
                                        @elseif($att->status === 'sakit')
                                            <span style="color: #b45309; font-weight: 800; font-size: 0.76rem;">S</span>
                                        @elseif($att->status === 'izin')
                                            <span style="color: #2563eb; font-weight: 800; font-size: 0.76rem;">I</span>
                                        @else
                                            <span style="color: #dc2626; font-weight: 800; font-size: 0.76rem;">A</span>
                                        @endif
                                    @else
                                        <span style="color: #cbd5e1;">-</span>
                                    @endif
                                </td>
                            @endforeach

                            <td style="text-align: center; font-weight: 800; color: #0284c7;">{{ $stats['present'] }}</td>
                            <td style="text-align: center; font-weight: 800; color: #059669;">{{ $stats['passed'] }}</td>
                            <td style="text-align: center; font-weight: 800; color: #e11d48;">{{ $stats['repeat'] }}</td>
                            <td style="text-align: center;">
                                <span style="background: {{ $stats['percentage'] >= 70 ? '#ecfdf5' : '#fff1f2' }}; color: {{ $stats['percentage'] >= 70 ? '#047857' : '#be123c' }}; font-weight: 900; padding: 2px 8px; border-radius: 12px; font-size: 0.78rem;">
                                    {{ $stats['percentage'] }}%
                                </span>
                            </td>
                            <td style="font-weight: 700; color: #0f172a; font-size: 0.82rem;">
                                {{ $stats['last_position'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
