<table>
    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #fce5cd; border: 1px solid black; text-align: center;">No</th>
            <th style="font-weight: bold; background-color: #fce5cd; border: 1px solid black;">Nama Siswa</th>
            <th style="font-weight: bold; background-color: #fce5cd; border: 1px solid black; text-align: center;">Kelas</th>
            <th style="font-weight: bold; background-color: #fce5cd; border: 1px solid black;">Ekstrakurikuler Diikuti</th>
            <th style="font-weight: bold; background-color: #fce5cd; border: 1px solid black;">Pembina</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $index => $student)
        @foreach($student->eskuls as $eskul)
        <tr>
            <td style="border: 1px solid black; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid black;">{{ $student->name }}</td>
            <td style="border: 1px solid black; text-align: center;">{{ $student->class }}</td>
            <td style="border: 1px solid black;">{{ $eskul->name }}</td>
            <td style="border: 1px solid black;">{{ $eskul->instructor_name }}</td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>
