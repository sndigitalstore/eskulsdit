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
        Schema::table('eskuls', function (Blueprint $table) {
            $table->integer('quota')->default(25)->after('is_lockable');
            $table->boolean('is_active')->default(true)->after('quota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eskuls', function (Blueprint $table) {
            $table->dropColumn(['quota', 'is_active']);
        });
    }
};
