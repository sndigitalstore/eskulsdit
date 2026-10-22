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
        if (Schema::hasTable('tahfidz_attendances')) {
            Schema::table('tahfidz_attendances', function (Blueprint $table) {
                if (!Schema::hasColumn('tahfidz_attendances', 'capaian')) {
                    $table->string('capaian')->nullable()->after('status');
                }
                if (!Schema::hasColumn('tahfidz_attendances', 'hafalan_status')) {
                    $table->string('hafalan_status')->default('LULUS')->nullable()->after('capaian');
                }
                if (!Schema::hasColumn('tahfidz_attendances', 'tugas_besok')) {
                    $table->string('tugas_besok')->nullable()->after('hafalan_status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tahfidz_attendances')) {
            Schema::table('tahfidz_attendances', function (Blueprint $table) {
                if (Schema::hasColumn('tahfidz_attendances', 'tugas_besok')) {
                    $table->dropColumn('tugas_besok');
                }
                if (Schema::hasColumn('tahfidz_attendances', 'hafalan_status')) {
                    $table->dropColumn('hafalan_status');
                }
                if (Schema::hasColumn('tahfidz_attendances', 'capaian')) {
                    $table->dropColumn('capaian');
                }
            });
        }
    }
};
