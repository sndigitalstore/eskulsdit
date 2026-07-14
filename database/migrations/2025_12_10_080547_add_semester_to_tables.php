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
        // Add active_semester to academic_years table
        Schema::table('academic_years', function (Blueprint $table) {
            if (!Schema::hasColumn('academic_years', 'active_semester')) {
                $table->enum('active_semester', ['1', '2'])->default('1')->after('is_active');
            }
        });

        // Add semester to grades table
        Schema::table('grades', function (Blueprint $table) {
            if (!Schema::hasColumn('grades', 'semester')) {
                $table->enum('semester', ['1', '2'])->default('1')->after('academic_year_id');
            }
        });

        // Add semester to attendances table
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'semester')) {
                $table->enum('semester', ['1', '2'])->default('1')->after('academic_year_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {
            if (Schema::hasColumn('academic_years', 'active_semester')) {
                $table->dropColumn('active_semester');
            }
        });

        Schema::table('grades', function (Blueprint $table) {
            if (Schema::hasColumn('grades', 'semester')) {
                $table->dropColumn('semester');
            }
        });

        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'semester')) {
                $table->dropColumn('semester');
            }
        });
    }
};
