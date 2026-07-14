@extends('layouts.app')

@section('title', 'Edit Prestasi')
@section('page-title', 'Edit Data Prestasi')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 5px;">Edit Data Prestasi</h3>
        <p style="color: #666;">
            Siswa: <strong>{{ $achievement->student->name }}</strong>
        </p>
    </div>

    <form action="{{ route('achievements.update', $achievement->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; background: #fffcf0; padding: 15px; border-radius: 12px; border: 1px solid #f9e79f;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #7d6608;">Tahun Pelajaran</label>
                <select name="academic_year_id" class="form-control" required>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $achievement->academic_year_id == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #7d6608;">Semester</label>
                <select name="semester" class="form-control" required>
                    <option value="1" {{ $achievement->semester == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                    <option value="2" {{ $achievement->semester == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Nama Prestasi / Kejuaraan</label>
            <input type="text" name="name" class="form-control" value="{{ $achievement->name }}" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Tingkat</label>
                <select name="level" class="form-control" required>
                    <option value="Sekolah" {{ $achievement->level == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                    <option value="Kecamatan" {{ $achievement->level == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                    <option value="Kabupaten/Kota" {{ $achievement->level == 'Kabupaten/Kota' ? 'selected' : '' }}>Kabupaten/Kota</option>
                    <option value="Provinsi" {{ $achievement->level == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                    <option value="Nasional" {{ $achievement->level == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                    <option value="Internasional" {{ $achievement->level == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                <option value="Open Turnamen / Event Lainnya" {{ $achievement->level == 'Open Turnamen / Event Lainnya' ? 'selected' : '' }}>Open Turnamen / Event Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Tanggal Perolehan</label>
                <input type="date" name="date" class="form-control" value="{{ $achievement->date }}" required>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Penyelenggara (Opsional)</label>
            <input type="text" name="organizer" class="form-control" value="{{ $achievement->organizer }}" placeholder="Contoh: Dinas Pendidikan Kota">
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Keterangan Tambahan (Opsional)</label>
            <textarea name="description" class="form-control" rows="3">{{ $achievement->description }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Perbarui Prestasi
            </button>
            <a href="{{ route('students.show', $achievement->student_id) }}" class="btn-submit" style="background: #ccc; border: 1px solid #bbb; color: #333;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
