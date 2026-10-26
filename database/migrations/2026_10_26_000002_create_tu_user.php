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
        $exists = DB::table('users')->where('username', 'TU')->orWhere('username', 'tu')->exists();

        if (!$exists) {
            DB::table('users')->insert([
                'name' => 'Staf Tata Usaha (TU)',
                'username' => 'TU',
                'role' => 'tu',
                'email' => 'tu@school.com',
                'password' => Hash::make('123456'),
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
        DB::table('users')->whereIn('username', ['TU', 'tu'])->delete();
    }
};
