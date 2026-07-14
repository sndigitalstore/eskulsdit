@extends('layouts.app')

@section('title', 'Hasil Pencarian')
@section('page-title', 'Hasil Pencarian')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Menampilkan hasil untuk: "<span style="color: var(--accent-color);">{{ $query }}</span>"</h3>
        <span class="badge" style="background: #eef2ff; color: #5381ff; padding: 5px 15px; border-radius: 20px;">
            {{ $students->count() }} Data Ditemukan
        </span>
    </div>

    @if($students->count() > 0)
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Eskul (Aktif)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td style="font-weight: 500;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 35px; height: 35px; background: #f0f7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #5381ff; font-weight: bold;">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            {{ $student->name }}
                        </div>
                    </td>
                    <td><span style="background: #fff5f7; color: #ff7eb3; padding: 4px 10px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;">{{ $student->class }}</span></td>
                    <td>
                        @forelse($student->eskuls as $eskul)
                            <span style="background: #eef2ff; color: #5381ff; padding: 4px 10px; border-radius: 10px; font-size: 0.85rem; margin-right: 5px; display: inline-block; margin-bottom: 3px;">
                                {{ $eskul->name }}
                            </span>
                        @empty
                            <span style="color: #999; font-style: italic;">Tidak ada eskul</span>
                        @endforelse
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('students.edit', $student->id) }}" class="btn-submit" style="padding: 6px 12px; font-size: 0.8rem; background: #fbbf24; box-shadow: none;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('students.card', $student->id) }}" class="btn-submit" style="padding: 6px 12px; font-size: 0.8rem; background: #3498db; box-shadow: none;" target="_blank">
                                <i class="fas fa-id-card"></i> Kartu
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align: center; padding: 50px 0;">
        <img src="https://cdni.iconscout.com/illustration/premium/thumb/search-result-not-found-2130361-1800925.png" alt="Not Found" style="width: 200px; opacity: 0.8;">
        <h4 style="color: #888; margin-top: 20px;">Data tidak ditemukan</h4>
        <p style="color: #aaa;">Coba kata kunci lain atau pastikan ejaan benar.</p>
        <a href="{{ route('dashboard') }}" class="btn-submit" style="margin-top: 20px; width: auto; background: #e0f2fe; color: #0284c7; box-shadow: none;">Kembali ke Dashboard</a>
    </div>
    @endif
</div>
@endsection
