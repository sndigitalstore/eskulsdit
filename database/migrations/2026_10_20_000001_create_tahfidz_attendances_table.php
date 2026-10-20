<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahfidz_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentoring_group_id')->constrained('mentoring_groups')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->date('session_date');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha'])->default('hadir');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Satu siswa satu status per sesi (group + date)
            $table->unique(['mentoring_group_id', 'student_id', 'session_date'], 'unique_tahfidz_attendance');
            $table->index(['mentoring_group_id', 'session_date']);
            $table->index(['student_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahfidz_attendances');
    }
};
