<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tasmi_exams')) {
            Schema::create('tasmi_exams', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->foreignId('mentoring_group_id')->nullable()->constrained('mentoring_groups')->nullOnDelete();
                $table->enum('category', ['setengah_juz', 'satu_juz', 'dua_juz', 'tiga_juz'])->default('satu_juz');
                $table->string('juz_tested')->default('Juz 30');
                $table->date('exam_date');
                $table->string('examiner_name');
                $table->foreignId('examiner_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->unsignedSmallInteger('mistakes_fath')->default(0);     // Kesalahan ditebak / diingatkan
                $table->unsignedSmallInteger('mistakes_waqaf')->default(0);    // Kesalahan waqaf & ibtida
                $table->unsignedSmallInteger('mistakes_tajwid')->default(0);   // Kesalahan kaidah tajwid & makhraj
                $table->unsignedSmallInteger('total_mistakes')->default(0);
                $table->decimal('final_score', 5, 2)->default(100.00);
                $table->string('predicate')->default('Mumtaz');
                $table->enum('status', ['passed', 'retest'])->default('passed');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['student_id', 'academic_year_id']);
                $table->index(['exam_date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tasmi_exams');
    }
};
