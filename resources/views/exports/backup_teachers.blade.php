<table>
    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #d1c4e9; border: 1px solid black;">Nama Guru</th>
            <th style="font-weight: bold; background-color: #d1c4e9; border: 1px solid black;">Username</th>
            <th style="font-weight: bold; background-color: #d1c4e9; border: 1px solid black;">Email</th>
            <th style="font-weight: bold; background-color: #d1c4e9; border: 1px solid black;">No HP</th>
            <th style="font-weight: bold; background-color: #d1c4e9; border: 1px solid black;">Eskul Diampu</th>
        </tr>
    </thead>
    <tbody>
        @foreach($teachers as $teacher)
        <tr>
            <td style="border: 1px solid black;">{{ $teacher->name }}</td>
            <td style="border: 1px solid black;">{{ $teacher->username }}</td>
            <td style="border: 1px solid black;">{{ $teacher->email ?? '-' }}</td>
            <td style="border: 1px solid black;">{{ $teacher->phone ?? '-' }}</td>
            <td style="border: 1px solid black;">{{ $teacher->eskul->name ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
