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
        if (!Schema::hasTable('eskul_histories')) {
            Schema::create('eskul_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('eskul_id')->constrained()->onDelete('cascade');
                $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
                $table->enum('semester', ['1', '2']);
                $table->string('instructor_name')->nullable();
                $table->string('schedule')->nullable(); // Jadwal juga bisa berubah
                $table->timestamps();

                // Constraint: Satu history per eskul per tahun per semester
                $table->unique(['eskul_id', 'academic_year_id', 'semester']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eskul_histories');
    }
};
