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
        Schema::table('students', function (Blueprint $table) {
            $table->index('nis', 'idx_students_nis');
            $table->index('status', 'idx_students_status');
            $table->index('academic_year_id', 'idx_students_academic_year_id');
            $table->index('class', 'idx_students_class');
        });

        Schema::table('eskuls', function (Blueprint $table) {
            $table->index('academic_year_id', 'idx_eskuls_academic_year_id');
            $table->index('is_active', 'idx_eskuls_is_active');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->index(['student_id', 'eskul_id', 'date'], 'idx_attendances_composite');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->index(['student_id', 'eskul_id'], 'idx_grades_student_eskul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('idx_students_nis');
            $table->dropIndex('idx_students_status');
            $table->dropIndex('idx_students_academic_year_id');
            $table->dropIndex('idx_students_class');
        });

        Schema::table('eskuls', function (Blueprint $table) {
            $table->dropIndex('idx_eskuls_academic_year_id');
            $table->dropIndex('idx_eskuls_is_active');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('idx_attendances_composite');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropIndex('idx_grades_student_eskul');
        });
    }
};
