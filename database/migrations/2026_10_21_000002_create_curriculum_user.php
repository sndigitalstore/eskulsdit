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
        // Check if curriculum user already exists
        $exists = DB::table('users')->where('username', 'kurikulum')->orWhere('role', 'curriculum')->exists();

        if (!$exists) {
            DB::table('users')->insert([
                'name' => 'Tim Kurikulum SDIT AN NADZIR',
                'username' => 'kurikulum',
                'role' => 'curriculum',
                'email' => 'kurikulum@school.com',
                'password' => Hash::make('kurikulum123'),
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
        DB::table('users')->where('username', 'kurikulum')->delete();
    }
};
