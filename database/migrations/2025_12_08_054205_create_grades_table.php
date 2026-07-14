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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('eskul_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['daily', 'sas1', 'sas2']); 
            $table->enum('score', ['A', 'B', 'C']); 
            $table->date('date')->nullable(); // Required for daily, optional for SAS? Or just use date for all.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
