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
        // Using raw statement to avoid doctrine/dbal dependency issues with enums
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE grades MODIFY COLUMN score VARCHAR(255) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting might fail if data contains non-enum values, so we just set it back to varchar or try enum
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE grades MODIFY COLUMN score ENUM('A','B','C') NOT NULL");
    }
};
