<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tahfidz_semester_exams')) {
            Schema::create('tahfidz_semester_exams', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('mentoring_group_id')->constrained('mentoring_groups')->cascadeOnDelete();
                $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
                $table->enum('semester', ['1', '2'])->default('1');
                $table->date('exam_date');
                $table->foreignId('examiner_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('examiner_name')->nullable();
                $table->decimal('average_score', 5, 2)->default(0);
                $table->string('predicate')->default('Jayyid');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->unique(['student_id', 'academic_year_id', 'semester'], 'std_year_sem_unique');
                $table->index(['mentoring_group_id', 'academic_year_id', 'semester'], 'grp_year_sem_idx');
            });
        }

        if (!Schema::hasTable('tahfidz_semester_exam_items')) {
            Schema::create('tahfidz_semester_exam_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tahfidz_semester_exam_id')->constrained('tahfidz_semester_exams')->cascadeOnDelete();
                $table->string('surah_name');
                $table->unsignedSmallInteger('juz')->nullable();
                $table->decimal('score', 5, 2)->default(0);
                $table->string('predicate')->nullable();
                $table->string('notes')->nullable();
                $table->unsignedSmallInteger('order_num')->default(1);
                $table->timestamps();

                $table->index('tahfidz_semester_exam_id', 'exam_items_exam_id_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tahfidz_semester_exam_items');
        Schema::dropIfExists('tahfidz_semester_exams');
    }
};
