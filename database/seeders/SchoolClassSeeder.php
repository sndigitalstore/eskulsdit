<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            '1A', '1B', '1C',
            '2A', '2B', '2C',
            '3A', '3B', '3C',
            '4A', '4B', '4C',
            '5A', '5B', '5C',
            '6A', '6B', '6C',
        ];

        foreach ($classes as $class) {
            SchoolClass::firstOrCreate(['name' => $class]);
        }
    }
}
