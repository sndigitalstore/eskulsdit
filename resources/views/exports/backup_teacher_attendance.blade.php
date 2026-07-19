<table>
    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #fce8b2; border: 1px solid black;">Nama Guru</th>
            <th style="font-weight: bold; background-color: #fce8b2; border: 1px solid black;">Tanggal</th>
            <th style="font-weight: bold; background-color: #fce8b2; border: 1px solid black;">Waktu</th>
            <th style="font-weight: bold; background-color: #fce8b2; border: 1px solid black;">Status</th>
            <th style="font-weight: bold; background-color: #fce8b2; border: 1px solid black;">Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendances as $attendance)
        <tr>
            <td style="border: 1px solid black;">{{ $attendance->user->name ?? 'Unknown' }}</td>
            <td style="border: 1px solid black;">{{ date('d-m-Y', strtotime($attendance->date)) }}</td>
            <td style="border: 1px solid black;">{{ $attendance->clock_in_time ? date('H:i', strtotime($attendance->clock_in_time)) : '-' }}</td>
            <td style="border: 1px solid black;">
                @if($attendance->status == 'present') Hadir
                @elseif($attendance->status == 'sick') Sakit
                @elseif($attendance->status == 'permission') Izin
                @elseif($attendance->status == 'absent') Alpa
                @else {{ $attendance->status }}
                @endif
            </td>
            <td style="border: 1px solid black;">{{ $attendance->note }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
