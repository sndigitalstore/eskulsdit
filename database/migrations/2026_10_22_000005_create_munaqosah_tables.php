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
        if (!Schema::hasTable('munaqisy_examiners')) {
            Schema::create('munaqisy_examiners', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->enum('specialization', ['tilawati', 'tahfidz', 'both'])->default('both');
                $table->string('certificate_number')->nullable();
                $table->string('phone')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('munaqosah_registrations')) {
            Schema::create('munaqosah_registrations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->foreignId('from_group_id')->nullable()->constrained('mentoring_groups')->nullOnDelete();
                $table->foreignId('proposer_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('proposer_notes')->nullable();
                
                $table->foreignId('examiner_id')->nullable()->constrained('munaqisy_examiners')->nullOnDelete();
                $table->foreignId('examiner_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('type', ['tilawati', 'tahfidz'])->index();
                $table->string('current_level'); // e.g. "Juz 30", "Jilid 2"
                $table->string('target_level');  // e.g. "Juz 29", "Jilid 3"
                $table->date('registration_date')->nullable();
                $table->date('exam_date')->nullable();
                $table->string('exam_time')->nullable();
                $table->string('room_location')->nullable();
                $table->enum('status', ['pending', 'scheduled', 'passed', 'failed'])->default('pending')->index();

                // Rubrik Penilaian Penguji
                $table->decimal('score_tajwid', 5, 2)->nullable();
                $table->decimal('score_fashahah', 5, 2)->nullable();
                $table->decimal('score_makhraj', 5, 2)->nullable();
                $table->decimal('score_adab_lagu', 5, 2)->nullable();
                $table->decimal('final_score', 5, 2)->nullable();
                $table->string('predicate')->nullable(); // Mumtaz, Jayyid Jiddan, Jayyid, Maqbul, Rasib
                $table->text('examiner_notes')->nullable();
                $table->string('recommendation')->nullable();
                $table->timestamp('tested_at')->nullable();

                // Integrasi Pindah Jenjang (Dashboard Siswa / Kurikulum)
                $table->enum('transition_status', ['none', 'pending_promotion', 'promoted'])->default('none')->index();
                $table->foreignId('promoted_to_group_id')->nullable()->constrained('mentoring_groups')->nullOnDelete();
                $table->timestamp('promoted_at')->nullable();
                $table->foreignId('promoted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('munaqosah_registrations');
        Schema::dropIfExists('munaqisy_examiners');
    }
};
