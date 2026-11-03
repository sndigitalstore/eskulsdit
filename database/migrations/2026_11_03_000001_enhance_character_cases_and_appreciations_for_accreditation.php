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
        if (Schema::hasTable('character_cases')) {
            Schema::table('character_cases', function (Blueprint $table) {
                if (!Schema::hasColumn('character_cases', 'tppk_category')) {
                    $table->string('tppk_category', 50)->nullable()->default('non_tppk')->after('category');
                }
                if (!Schema::hasColumn('character_cases', 'handling_level')) {
                    $table->string('handling_level', 30)->nullable()->default('wali_kelas')->after('status');
                }
                if (!Schema::hasColumn('character_cases', 'restorative_action')) {
                    $table->text('restorative_action')->nullable()->after('action_taken');
                }
                if (!Schema::hasColumn('character_cases', 'counseling_date')) {
                    $table->date('counseling_date')->nullable()->after('parent_notified');
                }
            });
        }

        if (Schema::hasTable('character_appreciations')) {
            Schema::table('character_appreciations', function (Blueprint $table) {
                if (!Schema::hasColumn('character_appreciations', 'dimension_p5')) {
                    $table->string('dimension_p5', 100)->nullable()->after('category');
                }
                if (!Schema::hasColumn('character_appreciations', 'certificate_no')) {
                    $table->string('certificate_no', 100)->nullable()->after('reward');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('character_cases')) {
            Schema::table('character_cases', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('character_cases', 'tppk_category')) $cols[] = 'tppk_category';
                if (Schema::hasColumn('character_cases', 'handling_level')) $cols[] = 'handling_level';
                if (Schema::hasColumn('character_cases', 'restorative_action')) $cols[] = 'restorative_action';
                if (Schema::hasColumn('character_cases', 'counseling_date')) $cols[] = 'counseling_date';
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }

        if (Schema::hasTable('character_appreciations')) {
            Schema::table('character_appreciations', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('character_appreciations', 'dimension_p5')) $cols[] = 'dimension_p5';
                if (Schema::hasColumn('character_appreciations', 'certificate_no')) $cols[] = 'certificate_no';
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }
    }
};
