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
        // One-time fix for existing data
        $activeYear = \Illuminate\Support\Facades\DB::table('academic_years')->where('is_active', true)->first();
        
        if ($activeYear) {
            \Illuminate\Support\Facades\DB::table('student_eskul')
                ->whereNull('academic_year_id')
                ->update([
                    'academic_year_id' => $activeYear->id,
                    'semester' => $activeYear->active_semester ?? '1'
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed really, it's a data fix
    }
};
