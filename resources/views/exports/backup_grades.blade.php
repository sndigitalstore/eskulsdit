<table>
    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #d9ead3; border: 1px solid black; text-align: center;">No</th>
            <th style="font-weight: bold; background-color: #d9ead3; border: 1px solid black; text-align: center;">NIS</th>
            <th style="font-weight: bold; background-color: #d9ead3; border: 1px solid black;">Nama Siswa</th>
            <th style="font-weight: bold; background-color: #d9ead3; border: 1px solid black; text-align: center;">Kelas</th>
            <th style="font-weight: bold; background-color: #d9ead3; border: 1px solid black;">Ekstrakurikuler</th>
            <th style="font-weight: bold; background-color: #fff2cc; border: 1px solid black; text-align: center;">Nilai Harian</th>
            <th style="font-weight: bold; background-color: #c9daf8; border: 1px solid black; text-align: center;">SAS 1</th>
            <th style="font-weight: bold; background-color: #c9daf8; border: 1px solid black; text-align: center;">SAS 2</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $index => $row)
        <tr>
            <td style="border: 1px solid black; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid black; text-align: center;">{{ $row->nis ?? '-' }}</td>
            <td style="border: 1px solid black;">{{ $row->student_name }}</td>
            <td style="border: 1px solid black; text-align: center;">{{ $row->class }}</td>
            <td style="border: 1px solid black;">{{ $row->eskul_name }}</td>
            <td style="border: 1px solid black; text-align: center; white-space: pre-wrap;">{{ $row->daily }}</td>
            <td style="border: 1px solid black; text-align: center; white-space: pre-wrap;">{{ $row->sas1 }}</td>
            <td style="border: 1px solid black; text-align: center; white-space: pre-wrap;">{{ $row->sas2 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
