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
        if (!Schema::hasTable('mentoring_groups')) {
            Schema::create('mentoring_groups', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['tilawati', 'tahfidz'])->index();
                $table->string('name');
                $table->string('mentor_name')->nullable();
                $table->foreignId('mentor_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->string('target_level')->nullable(); // e.g. 'Jilid 1', 'Jilid 2', 'Juz 30'
                $table->string('schedule')->nullable();     // e.g. 'Senin & Kamis, 07:30'
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring_groups');
    }
};
