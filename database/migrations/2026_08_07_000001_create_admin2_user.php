<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if user already exists to avoid duplicates
        $exists = DB::table('users')->where('username', 'admin2')->exists();
        
        if (!$exists) {
            DB::table('users')->insert([
                'name' => 'Dzikri Aditama, S.Pd',
                'username' => 'admin2',
                'role' => 'admin',
                'email' => 'admin2@school.com', // default/fallback email
                'password' => Hash::make('Cinangka2'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('username', 'admin2')->delete();
    }
};
