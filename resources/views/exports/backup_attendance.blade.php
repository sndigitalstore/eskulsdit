<table>
    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #c9daf8; border: 1px solid black; text-align: center;">No</th>
            <th style="font-weight: bold; background-color: #c9daf8; border: 1px solid black; text-align: center;">Tanggal</th>
            <th style="font-weight: bold; background-color: #c9daf8; border: 1px solid black;">Ekstrakurikuler</th>
            <th style="font-weight: bold; background-color: #c9daf8; border: 1px solid black;">Nama Siswa</th>
            <th style="font-weight: bold; background-color: #c9daf8; border: 1px solid black; text-align: center;">Kelas</th>
            <th style="font-weight: bold; background-color: #c9daf8; border: 1px solid black; text-align: center;">Status</th>
            <th style="font-weight: bold; background-color: #c9daf8; border: 1px solid black;">Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendances as $index => $attendance)
        <tr>
            <td style="border: 1px solid black; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid black; text-align: center;">{{ date('d-m-Y', strtotime($attendance->date)) }}</td>
            <td style="border: 1px solid black;">{{ $attendance->eskul->name ?? '-' }}</td>
            <td style="border: 1px solid black;">{{ $attendance->student->name ?? 'Unknown' }}</td>
            <td style="border: 1px solid black; text-align: center;">{{ $attendance->student->class ?? '-' }}</td>
            <td style="border: 1px solid black; text-align: center;">
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
