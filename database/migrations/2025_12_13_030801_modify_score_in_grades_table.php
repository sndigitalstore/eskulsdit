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
        // Change score to varchar to allow storing JSON or other values
        // Using raw statement for non-sqlite drivers to avoid enum change issues,
        // and using native schema builder for SQLite to allow testing.
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'sqlite') {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('score', 255)->nullable()->change();
            });
        } else {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE grades MODIFY COLUMN score VARCHAR(255) NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'sqlite') {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('score', 255)->change();
            });
        } else {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE grades MODIFY COLUMN score ENUM('A','B','C') NOT NULL");
        }
    }
};
