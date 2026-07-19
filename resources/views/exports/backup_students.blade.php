<table>
    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #fce5cd; border: 1px solid black;">NIS</th>
            <th style="font-weight: bold; background-color: #fce5cd; border: 1px solid black;">Nama Lengkap Siswa</th>
            <th style="font-weight: bold; background-color: #fce5cd; border: 1px solid black;">Kelas</th>
            <th style="font-weight: bold; background-color: #fce5cd; border: 1px solid black;">Ekstrakurikuler</th>
            <th style="font-weight: bold; background-color: #fce5cd; border: 1px solid black;">Pembina</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
            @if($student->eskuls->isEmpty())
                <tr>
                    <td style="border: 1px solid black;">{{ $student->nis ?? '-' }}</td>
                    <td style="border: 1px solid black;">{{ $student->name }}</td>
                    <td style="border: 1px solid black;">{{ $student->class }}</td>
                    <td style="border: 1px solid black;">-</td>
                    <td style="border: 1px solid black;">-</td>
                </tr>
            @else
                @foreach($student->eskuls as $eskul)
                <tr>
                    <td style="border: 1px solid black;">{{ $student->nis ?? '-' }}</td>
                    <td style="border: 1px solid black;">{{ $student->name }}</td>
                    <td style="border: 1px solid black;">{{ $student->class }}</td>
                    <td style="border: 1px solid black;">{{ $eskul->name }}</td>
                    <td style="border: 1px solid black;">{{ $eskul->instructor_name ?? '-' }}</td>
                </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>
