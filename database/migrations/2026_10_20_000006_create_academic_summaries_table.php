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
        if (!Schema::hasTable('academic_summaries')) {
            Schema::create('academic_summaries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->string('assessment_type', 20); // STS_1, SAS_1, STS_2, SAS_2
                $table->integer('sick_count')->default(0); // Sakit (S)
                $table->integer('permission_count')->default(0); // Izin (I)
                $table->integer('unexcused_count')->default(0); // Alpa / Tanpa Keterangan (A)
                $table->text('homeroom_notes')->nullable(); // Catatan Wali Kelas
                $table->timestamps();

                $table->unique(['academic_year_id', 'student_id', 'assessment_type'], 'student_assessment_summary_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_summaries');
    }
};
