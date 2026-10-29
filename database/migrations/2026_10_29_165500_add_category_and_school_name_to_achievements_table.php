<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            if (!Schema::hasColumn('achievements', 'category')) {
                $table->string('category', 20)->default('student')->after('id');
            }
            if (!Schema::hasColumn('achievements', 'school_name')) {
                $table->string('school_name')->nullable()->after('student_id');
            }
        });

        // Make student_id nullable for institutional/school achievements
        try {
            DB::statement("ALTER TABLE achievements MODIFY student_id BIGINT UNSIGNED NULL");
        } catch (\Exception $e) {
            // Already nullable or handled
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            if (Schema::hasColumn('achievements', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('achievements', 'school_name')) {
                $table->dropColumn('school_name');
            }
        });
    }
};
