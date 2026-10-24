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
        if (!Schema::hasTable('tilawati_sessions')) {
            Schema::create('tilawati_sessions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('mentoring_group_id');
                $table->unsignedBigInteger('academic_year_id')->nullable();
                $table->date('session_date');
                $table->string('jilid', 50)->default('Jilid 1');
                $table->unsignedSmallInteger('peraga_page_start')->default(1);
                $table->unsignedSmallInteger('peraga_page_end')->default(4); // 4 Halaman Alat Peraga
                $table->unsignedSmallInteger('buku_page')->default(1);        // 1 Halaman Buku Tilawati
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('recorded_by')->nullable();
                $table->timestamps();

                $table->unique(['mentoring_group_id', 'session_date'], 'unique_tilawati_group_session');
                $table->index(['mentoring_group_id', 'session_date']);
                $table->index('academic_year_id');
            });
        }

        if (!Schema::hasTable('tilawati_attendances')) {
            Schema::create('tilawati_attendances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tilawati_session_id')->nullable();
                $table->unsignedBigInteger('mentoring_group_id');
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('academic_year_id')->nullable();
                $table->date('session_date');
                $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha'])->default('hadir');
                $table->string('jilid', 50)->default('Jilid 1');
                $table->string('peraga_pages', 50)->nullable(); // Misal "Hal 1 - 4"
                $table->unsignedSmallInteger('buku_page')->nullable(); // Misal 1
                $table->decimal('score', 5, 2)->nullable(); // Nilai skor 0 - 100
                $table->boolean('is_passed')->default(true); // >= 70%: true, < 70%: false
                $table->enum('action', ['lanjut', 'ulang'])->default('lanjut'); // lanjut / ulang
                $table->string('tugas_besok')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('recorded_by')->nullable();
                $table->timestamps();

                $table->unique(['mentoring_group_id', 'student_id', 'session_date'], 'unique_tilawati_attendance');
                $table->index(['mentoring_group_id', 'session_date']);
                $table->index(['student_id', 'session_date']);
                $table->index('academic_year_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tilawati_attendances');
        Schema::dropIfExists('tilawati_sessions');
    }
};
