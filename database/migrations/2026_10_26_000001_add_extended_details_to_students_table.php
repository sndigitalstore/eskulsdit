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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'nisn')) {
                $table->string('nisn')->nullable()->after('nis');
            }
            if (!Schema::hasColumn('students', 'gender')) {
                $table->string('gender', 10)->nullable()->after('class'); // 'L' atau 'P'
            }
            if (!Schema::hasColumn('students', 'birth_place_date')) {
                $table->string('birth_place_date')->nullable()->after('gender'); // contoh: 'Serang, 30 Januari 2019'
            }
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable()->after('birth_place_date');
            }
            if (!Schema::hasColumn('students', 'previous_school')) {
                $table->string('previous_school')->nullable()->after('address'); // Asal Sekolah (TK / PAUD)
            }
            if (!Schema::hasColumn('students', 'father_name')) {
                $table->string('father_name')->nullable()->after('previous_school');
            }
            if (!Schema::hasColumn('students', 'mother_name')) {
                $table->string('mother_name')->nullable()->after('father_name');
            }
            if (!Schema::hasColumn('students', 'father_job')) {
                $table->string('father_job')->nullable()->after('mother_name');
            }
            if (!Schema::hasColumn('students', 'mother_job')) {
                $table->string('mother_job')->nullable()->after('father_job');
            }
            if (!Schema::hasColumn('students', 'father_phone')) {
                $table->string('father_phone')->nullable()->after('mother_job');
            }
            if (!Schema::hasColumn('students', 'mother_phone')) {
                $table->string('mother_phone')->nullable()->after('father_phone');
            }
            if (!Schema::hasColumn('students', 'admission_date')) {
                $table->string('admission_date')->nullable()->after('mother_phone'); // Tgl diterima
            }
            if (!Schema::hasColumn('students', 'school_program')) {
                $table->string('school_program')->nullable()->after('admission_date'); // 'Full Day' atau 'Boarding'
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $cols = [
                'nisn', 'gender', 'birth_place_date', 'address', 'previous_school',
                'father_name', 'mother_name', 'father_job', 'mother_job',
                'father_phone', 'mother_phone', 'admission_date', 'school_program'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('students', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
