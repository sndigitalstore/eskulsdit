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
        // 1. Add academic_year_id to eskuls table
        Schema::table('eskuls', function (Blueprint $table) {
            $table->foreignId('academic_year_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('academic_years')
                  ->onDelete('cascade');
        });

        // 2. Add academic_year_id to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('academic_year_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('academic_years')
                  ->onDelete('cascade');
        });

        // Backfill: Find the active or first academic year
        $activeYear = DB::table('academic_years')->where('is_active', true)->first()
            ?? DB::table('academic_years')->orderBy('id')->first();

        if ($activeYear) {
            // Assign existing eskuls to this year
            DB::table('eskuls')
                ->whereNull('academic_year_id')
                ->update(['academic_year_id' => $activeYear->id]);

            // Assign existing teachers to this year
            DB::table('users')
                ->where('role', 'teacher')
                ->whereNull('academic_year_id')
                ->update(['academic_year_id' => $activeYear->id]);
        }

        // 3. Re-define unique constraints on users table
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropUnique('users_username_unique');
            } catch (\Exception $e) {
                try {
                    $table->dropUnique(['username']);
                } catch (\Exception $ex) {}
            }

            // New composite unique constraint: username must be unique within same year
            $table->unique(['username', 'academic_year_id'], 'users_username_academic_year_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropUnique('users_username_academic_year_unique');
            } catch (\Exception $e) {}

            $table->unique('username', 'users_username_unique');
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });

        Schema::table('eskuls', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
