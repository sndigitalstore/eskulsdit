<table>
    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #d9ead3; border: 1px solid black;">NIS</th>
            <th style="font-weight: bold; background-color: #d9ead3; border: 1px solid black;">Nama Lengkap Siswa</th>
            <th style="font-weight: bold; background-color: #d9ead3; border: 1px solid black;">Kelas</th>
            <th style="font-weight: bold; background-color: #d9ead3; border: 1px solid black;">Ekstrakurikuler</th>
            <th style="font-weight: bold; background-color: #fff2cc; border: 1px solid black;">Nilai Harian</th>
            <th style="font-weight: bold; background-color: #c9daf8; border: 1px solid black;">SAS 1</th>
            <th style="font-weight: bold; background-color: #c9daf8; border: 1px solid black;">SAS 2</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <tr>
            <td style="border: 1px solid black;">{{ $row->nis ?? '-' }}</td>
            <td style="border: 1px solid black;">{{ $row->student_name }}</td>
            <td style="border: 1px solid black;">{{ $row->class }}</td>
            <td style="border: 1px solid black;">{{ $row->eskul_name }}</td>
            <td style="border: 1px solid black;">{{ $row->daily }}</td>
            <td style="border: 1px solid black;">{{ $row->sas1 }}</td>
            <td style="border: 1px solid black;">{{ $row->sas2 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
