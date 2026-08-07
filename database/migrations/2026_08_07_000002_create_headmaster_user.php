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
        $exists = DB::table('users')->where('username', 'kepsek')->exists();
        
        if (!$exists) {
            DB::table('users')->insert([
                'name' => 'Kepala Sekolah SDIT AN NADZIR',
                'username' => 'kepsek',
                'role' => 'headmaster',
                'email' => 'kepsek@school.com',
                'password' => Hash::make('AnNadzirKepsek123'),
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
        DB::table('users')->where('username', 'kepsek')->delete();
    }
};
