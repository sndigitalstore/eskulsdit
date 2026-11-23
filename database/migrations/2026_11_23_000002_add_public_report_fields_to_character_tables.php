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
                if (!Schema::hasColumn('character_cases', 'is_public_report')) {
                    $table->boolean('is_public_report')->default(false)->after('parent_notified');
                }
                if (!Schema::hasColumn('character_cases', 'reporter_type')) {
                    $table->string('reporter_type', 50)->nullable()->after('is_public_report');
                }
                if (!Schema::hasColumn('character_cases', 'reporter_contact')) {
                    $table->string('reporter_contact', 100)->nullable()->after('reporter_type');
                }
                if (!Schema::hasColumn('character_cases', 'is_anonymous')) {
                    $table->boolean('is_anonymous')->default(false)->after('reporter_contact');
                }
                if (!Schema::hasColumn('character_cases', 'evidence_file')) {
                    $table->string('evidence_file', 255)->nullable()->after('is_anonymous');
                }
                if (!Schema::hasColumn('character_cases', 'verification_status')) {
                    $table->string('verification_status', 30)->default('verified')->after('evidence_file');
                }
            });
        }

        if (Schema::hasTable('character_appreciations')) {
            Schema::table('character_appreciations', function (Blueprint $table) {
                if (!Schema::hasColumn('character_appreciations', 'is_public_report')) {
                    $table->boolean('is_public_report')->default(false);
                }
                if (!Schema::hasColumn('character_appreciations', 'reporter_type')) {
                    $table->string('reporter_type', 50)->nullable();
                }
                if (!Schema::hasColumn('character_appreciations', 'reporter_contact')) {
                    $table->string('reporter_contact', 100)->nullable();
                }
                if (!Schema::hasColumn('character_appreciations', 'is_anonymous')) {
                    $table->boolean('is_anonymous')->default(false);
                }
                if (!Schema::hasColumn('character_appreciations', 'evidence_file')) {
                    $table->string('evidence_file', 255)->nullable();
                }
                if (!Schema::hasColumn('character_appreciations', 'verification_status')) {
                    $table->string('verification_status', 30)->default('verified');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe no-op or drop if required
    }
};
