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
        if (!Schema::hasTable('mentoring_group_students')) {
            Schema::create('mentoring_group_students', function (Blueprint $table) {
                $table->id();
                $table->foreignId('mentoring_group_id')->constrained('mentoring_groups')->cascadeOnDelete();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->string('notes')->nullable(); // e.g. 'Mulai dari Halaman 1', 'Juz 30'
                $table->timestamps();

                $table->unique(['mentoring_group_id', 'student_id'], 'group_student_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring_group_students');
    }
};
