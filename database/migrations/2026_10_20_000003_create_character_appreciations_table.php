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
        if (!Schema::hasTable('character_appreciations')) {
            Schema::create('character_appreciations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->date('date');
                $table->string('category', 100); // Sholeh, Muslih, Intellectual Curiosity, Leadership, Environmentally Friendly, Adab & Kesopanan, Prestasi Karakter
                $table->string('title'); // Ringkasan Apresiasi / Kebaikan
                $table->text('description')->nullable(); // Detail Perilaku Positif
                $table->integer('point')->default(10); // Poin Kebaikan
                $table->string('reward')->nullable(); // Bentuk Apresiasi (Piagam, Pujian Terbuka, Bintang Karakter, dll)
                $table->string('given_by')->nullable(); // Guru / Musyrif Pemberi Apresiasi
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['academic_year_id', 'student_id']);
                $table->index('category');
                $table->index('date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_appreciations');
    }
};
