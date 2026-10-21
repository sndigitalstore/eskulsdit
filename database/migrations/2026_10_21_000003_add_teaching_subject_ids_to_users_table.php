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
        if (!Schema::hasColumn('users', 'teaching_subject_ids')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('teaching_subject_ids')->nullable()->after('homeroom_class');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'teaching_subject_ids')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('teaching_subject_ids');
            });
        }
    }
};
