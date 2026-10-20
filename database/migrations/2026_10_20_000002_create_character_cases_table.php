<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('character_cases')) {
            Schema::create('character_cases', function (Blueprint $table) {
                $table->id();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->date('date');
                $table->string('category', 100); // Kedisiplinan, Adab & Akhlak, Kehadiran & Kerapian, Ibadah, Interaksi Sosial, Lainnya
                $table->string('title'); // Ringkasan Kasus / Perilaku
                $table->text('description')->nullable(); // Kronologi / Uraian Detail
                $table->string('location')->nullable(); // Lokasi Kejadian (Kelas, Masjid, Lapangan, dll)
                $table->string('severity', 30)->default('ringan'); // ringan, sedang, berat
                $table->integer('point')->default(5); // Poin Pembinaan / Pelanggaran
                $table->text('action_taken')->nullable(); // Tindakan Langsung Guru / Musyrif
                $table->text('follow_up')->nullable(); // Rencana Tindak Lanjut / Komitmen Siswa
                $table->string('status', 30)->default('dalam_pembinaan'); // dalam_pembinaan, selesai, panggilan_ortu
                $table->string('reported_by')->nullable(); // Guru / Pembina Pelapor
                $table->boolean('parent_notified')->default(false); // Pemberitahuan Orang Tua
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['academic_year_id', 'student_id']);
                $table->index('severity');
                $table->index('status');
                $table->index('date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_cases');
    }
};
