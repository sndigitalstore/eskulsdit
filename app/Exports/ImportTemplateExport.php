<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new TemplateSheet('Data Siswa', [
                ['NIS', 'Nama Lengkap Siswa', 'Kelas', 'Ekstrakurikuler', 'Pembina'],
                ['1001', 'Ahmad Fauzan', 'IA', 'Pramuka', 'Budi Santoso'],
                ['1002', 'Siti Aminah', 'IB', 'Seni Tari', 'Rini Wulandari'],
            ]),
            new TemplateSheet('Nilai', [
                ['NIS', 'Nama Lengkap Siswa', 'Kelas', 'Ekstrakurikuler', 'Nilai Harian', 'SAS 1', 'SAS 2'],
                ['1001', 'Ahmad Fauzan', 'IA', 'Pramuka', '85', '90', '88'],
                ['1002', 'Siti Aminah', 'IB', 'Seni Tari', '78', '82', '80'],
            ]),
            new TemplateSheet('Absensi Siswa', [
                ['NIS', 'Nama Lengkap Siswa', 'Kelas', 'Ekstrakurikuler', 'Tanggal', 'Status', 'Catatan'],
                ['1001', 'Ahmad Fauzan', 'IA', 'Pramuka', '2025-01-15', 'Hadir', ''],
                ['1002', 'Siti Aminah', 'IB', 'Seni Tari', '2025-01-15', 'Sakit', 'Demam'],
            ]),
            new TemplateSheet('Data Prestasi', [
                ['NIS', 'Nama Siswa', 'Kelas', 'Nama Prestasi', 'Tingkat', 'Penyelenggara', 'Tanggal', 'Keterangan'],
                ['1001', 'Ahmad Fauzan', 'IA', 'Juara 1 Pramuka', 'Kabupaten', 'Dikpora', '2025-02-10', 'Lomba Pionering'],
                ['1002', 'Siti Aminah', 'IB', 'Juara 3 Seni Tari', 'Provinsi', 'Dinas Pendidikan', '2025-03-05', ''],
            ]),
            new TemplateSheet('Data Guru', [
                ['Nama Guru', 'Username', 'Email', 'No HP', 'Eskul Diampu'],
                ['Budi Santoso', 'budi.santoso', 'budi@school.id', '081234567890', 'Pramuka'],
                ['Rini Wulandari', 'rini.wulandari', 'rini@school.id', '081298765432', 'Seni Tari'],
            ]),
            new TemplateSheet('Absensi Guru', [
                ['Nama Guru', 'Tanggal', 'Waktu', 'Status', 'Catatan'],
                ['Budi Santoso', '2025-01-15', '07:30', 'Hadir', ''],
                ['Rini Wulandari', '2025-01-15', '08:00', 'Hadir', ''],
            ]),
        ];
    }
}
