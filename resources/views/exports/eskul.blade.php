<table>
    <thead>
    <tr>
        <th colspan="7" style="font-size: 16px; font-weight: bold; text-align: center; height: 30px;">
            DAFTAR SISWA EKSTRAKURIKULER {{ strtoupper($eskul->name) }}
        </th>
    </tr>
    <tr>
        <th style="background-color: #2ecc71; color: #FFFFFF; font-weight: bold; text-align: center; width: 50px; border: 1px solid #000000;">No</th>
        <th style="background-color: #2ecc71; color: #FFFFFF; font-weight: bold; text-align: center; width: 120px; border: 1px solid #000000;">NIS</th>
        <th style="background-color: #2ecc71; color: #FFFFFF; font-weight: bold; text-align: center; width: 250px; border: 1px solid #000000;">Nama Siswa</th>
        <th style="background-color: #2ecc71; color: #FFFFFF; font-weight: bold; text-align: center; width: 100px; border: 1px solid #000000;">Kelas</th>
        <th style="background-color: #2ecc71; color: #FFFFFF; font-weight: bold; text-align: center; width: 200px; border: 1px solid #000000;">Pilihan Eskul</th>
        <th style="background-color: #2ecc71; color: #FFFFFF; font-weight: bold; text-align: center; width: 200px; border: 1px solid #000000;">Pembina</th>
        <th style="background-color: #2ecc71; color: #FFFFFF; font-weight: bold; text-align: center; width: 150px; border: 1px solid #000000;">Jadwal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($eskul->students as $index => $student)
        <tr>
            <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $student->nis ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $student->name }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $student->class }}</td>
            <td style="border: 1px solid #000000;">{{ $eskul->name }}</td>
            <td style="border: 1px solid #000000;">{{ $eskul->instructor_name ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $eskul->schedule ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
