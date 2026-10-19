<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahfidz_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('mentoring_group_id')->constrained('mentoring_groups')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->string('surah_name');           // Nama surah
            $table->unsignedSmallInteger('juz')->nullable();      // Juz ke-
            $table->unsignedSmallInteger('start_ayah')->nullable(); // Ayat mulai
            $table->unsignedSmallInteger('end_ayah')->nullable();   // Ayat selesai
            $table->enum('type', ['setoran_baru', 'murojaah'])->default('setoran_baru'); // Jenis setoran
            $table->enum('quality', ['lancar', 'cukup', 'perlu_perbaikan'])->default('lancar'); // Kualitas
            $table->date('session_date');            // Tanggal setoran
            $table->text('notes')->nullable();       // Catatan mentor
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null'); // Mentor
            $table->timestamps();

            $table->index(['student_id', 'academic_year_id']);
            $table->index(['mentoring_group_id', 'session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahfidz_records');
    }
};
