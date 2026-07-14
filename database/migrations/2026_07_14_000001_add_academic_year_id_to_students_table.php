<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds academic_year_id to students table so students are scoped per academic year.
     * Existing students are assigned to the first/oldest academic year (id=1).
     * The unique constraint on 'nis' is replaced with a composite unique on (nis, academic_year_id),
     * so the same NIS can exist again in a new academic year.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Add academic_year_id column (nullable to avoid breaking existing rows)
            $table->foreignId('academic_year_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('academic_years')
                  ->onDelete('cascade');
        });

        // Backfill: assign all existing students to the earliest academic year
        $firstYear = DB::table('academic_years')->orderBy('id')->first();
        if ($firstYear) {
            DB::table('students')
                ->whereNull('academic_year_id')
                ->update(['academic_year_id' => $firstYear->id]);
        }

        // Remove old unique index on nis alone (if it exists)
        // and replace with composite unique (nis, academic_year_id)
        // so the same nis can be re-used in a new academic year
        try {
            Schema::table('students', function (Blueprint $table) {
                $table->dropUnique(['nis']);
            });
        } catch (\Exception $e) {
            // Index may not exist, safe to continue
        }

        Schema::table('students', function (Blueprint $table) {
            // Composite unique: nis must be unique within the same academic year
            $table->unique(['nis', 'academic_year_id'], 'students_nis_academic_year_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            try {
                $table->dropUnique('students_nis_academic_year_unique');
            } catch (\Exception $e) {}

            $table->unique('nis');
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
