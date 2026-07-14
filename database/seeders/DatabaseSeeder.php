<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Admin User
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'role' => 'admin',
            'email' => 'admin@school.com',
            'password' => bcrypt('admin123'),
        ]);

        // Guru User
        // Guru User
        $guru = User::create([
            'name' => 'Guru Pembina',
            'username' => 'guru',
            'role' => 'guru',
            'email' => 'guru@school.com',
            'password' => bcrypt('guru123'),
        ]);

        // Add Eskuls
        \App\Models\Eskul::create(['name' => 'Futsal', 'instructor_id' => $guru->id]);
        \App\Models\Eskul::create(['name' => 'Pramuka', 'instructor_id' => $guru->id]);
        \App\Models\Eskul::create(['name' => 'Tari', 'instructor_id' => $guru->id]);
    }
}
