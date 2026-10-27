<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Program Kerja Eskul (untuk akreditasi)
        if (!Schema::hasTable('eskul_programs')) {
            Schema::create('eskul_programs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('eskul_id')->constrained()->onDelete('cascade');
                $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
                $table->enum('semester', ['1', '2']);
                // Identitas Program
                $table->string('visi')->nullable();
                $table->text('misi')->nullable();
                $table->text('tujuan')->nullable();
                $table->text('sasaran')->nullable();
                // Sarana & Prasarana
                $table->text('sarana')->nullable();
                // Sumber dana
                $table->text('sumber_dana')->nullable();
                // Catatan Kepala Sekolah
                $table->text('catatan_kepsek')->nullable();
                $table->string('kepsek_name')->nullable();
                $table->string('nip_kepsek')->nullable();
                $table->timestamps();

                $table->unique(['eskul_id', 'academic_year_id', 'semester']);
            });
        }

        // Tabel Kegiatan dalam Program Kerja (rincian per bulan)
        if (!Schema::hasTable('eskul_program_items')) {
            Schema::create('eskul_program_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('eskul_id')->constrained()->onDelete('cascade');
                $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
                $table->enum('semester', ['1', '2']);
                $table->string('bulan');         // e.g. "Juli", "Agustus"
                $table->string('kegiatan');      // Nama kegiatan
                $table->string('tujuan_kegiatan')->nullable();
                $table->string('tempat')->nullable();
                $table->string('waktu')->nullable();     // e.g. "Minggu ke-1"
                $table->string('penanggung_jawab')->nullable();
                $table->integer('urutan')->default(0);
                $table->timestamps();
            });
        }

        // Tabel Jurnal Kegiatan Eskul (untuk akreditasi)
        if (!Schema::hasTable('eskul_journals')) {
            Schema::create('eskul_journals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('eskul_id')->constrained()->onDelete('cascade');
                $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
                $table->enum('semester', ['1', '2']);
                $table->date('tanggal');
                $table->string('pertemuan_ke')->nullable();   // e.g. "1", "2"
                $table->string('materi');
                $table->text('deskripsi')->nullable();
                $table->integer('hadir')->default(0);
                $table->integer('tidak_hadir')->default(0);
                $table->integer('izin')->default(0);
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('eskul_journals');
        Schema::dropIfExists('eskul_program_items');
        Schema::dropIfExists('eskul_programs');
    }
};
