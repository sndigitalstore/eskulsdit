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
        if (!Schema::hasTable('academic_scores')) {
            Schema::create('academic_scores', function (Blueprint $table) {
                $table->id();
                $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
                $table->string('assessment_type', 20); // STS_1, SAS_1, STS_2, SAS_2
                $table->decimal('score', 5, 2); // 0 - 100
                $table->string('predicate', 10)->nullable(); // A, B, C, D
                $table->text('achievement_note')->nullable(); // Deskripsi Capaian Kompetensi
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->unique(['academic_year_id', 'student_id', 'subject_id', 'assessment_type'], 'student_subject_assessment_idx');
                $table->index(['academic_year_id', 'assessment_type']);
                $table->index(['student_id', 'assessment_type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_scores');
    }
};
