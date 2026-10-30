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
        if (!Schema::hasTable('supervisions')) {
            Schema::create('supervisions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->string('semester', 10)->default('1');
                $table->foreignId('supervisor_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
                $table->string('supervision_type', 30); // kbm, modul_ajar, quran, eskul
                $table->date('supervision_date');
                $table->string('class_name', 50)->nullable();
                $table->string('subject_name', 120)->nullable();
                $table->json('aspects_score')->nullable(); // detailed scores per indicator
                $table->decimal('total_score', 5, 2)->default(0.00); // 0 - 100
                $table->string('predicate', 40)->default('Cukup'); // Sangat Baik (A), Baik (B), Cukup (C), Perlu Pembinaan (D)
                $table->text('strengths')->nullable(); // Kelebihan / Apresiasi
                $table->text('improvements')->nullable(); // Aspek yang Perlu Ditingkatkan
                $table->text('recommendations')->nullable(); // Rencana Tindak Lanjut & Rekomendasi Coaching
                $table->date('follow_up_date')->nullable(); // Target Tindak Lanjut
                $table->string('status', 20)->default('completed'); // draft, completed
                $table->timestamps();

                $table->index(['academic_year_id', 'semester']);
                $table->index('teacher_id');
                $table->index('supervisor_id');
                $table->index('supervision_type');
                $table->index('supervision_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisions');
    }
};
