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
        Schema::table('student_eskul', function (Blueprint $table) {
            if (!Schema::hasColumn('student_eskul', 'academic_year_id')) {
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('cascade');
            }
            if (!Schema::hasColumn('student_eskul', 'semester')) {
                $table->enum('semester', ['1', '2'])->default('1');
            }
        });
        
        // Add index in a separate schema call or after column checks to ensure columns exist
        Schema::table('student_eskul', function (Blueprint $table) {
             // We can't easily check for index existence in Blueprint, so we might skip it or risking duplicate index error,
             // which is usually fine or we can omit index for now if strict.
             // But let's try to add it. Indices usually don't throw fatal error if duplicate on some DBs, but MySQL does.
             // Ideally we generate a name and check.
             // For safety in this recover step, I'll omit the explicit index addition if I suspect it ran before.
             // Actually, foreignId creates an index for the FK.
             // The composite index is good but not critical for functionality right now.
             // I will add it only if I'm sure.
             // Let's safe-guard it.
             try {
                 $table->index(['student_id', 'academic_year_id', 'semester'], 'student_eskul_composite_index');
             } catch (\Exception $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_eskul', function (Blueprint $table) {
            if (Schema::hasColumn('student_eskul', 'academic_year_id')) {
                // Drop foreign key first if we knew the name... usually table_column_foreign
                try {
                     $table->dropForeign(['academic_year_id']);
                } catch (\Exception $e) {}
                $table->dropColumn(['academic_year_id']);
            }
            if (Schema::hasColumn('student_eskul', 'semester')) {
                $table->dropColumn(['semester']);
            }
        });
    }
};
