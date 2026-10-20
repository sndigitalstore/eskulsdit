<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->id();
                $table->string('code', 20)->nullable();
                $table->string('name', 150);
                $table->string('category', 100)->default('Wajib / Nasional'); // Wajib / Nasional, Muatan Lokal, Khas SDIT / Diniyah
                $table->decimal('kkm', 5, 2)->default(75.00); // Kriteria Ketercapaian Tujuan Pembelajaran (KKTP)
                $table->integer('order_num')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Seed default SDIT Kurikulum Merdeka subjects
            $defaultSubjects = [
                ['code' => 'PAI', 'name' => 'Pendidikan Agama Islam & Budi Pekerti', 'category' => 'Wajib / Nasional', 'kkm' => 75.00, 'order_num' => 1, 'is_active' => true],
                ['code' => 'PPKN', 'name' => 'Pendidikan Pancasila', 'category' => 'Wajib / Nasional', 'kkm' => 75.00, 'order_num' => 2, 'is_active' => true],
                ['code' => 'BIN', 'name' => 'Bahasa Indonesia', 'category' => 'Wajib / Nasional', 'kkm' => 75.00, 'order_num' => 3, 'is_active' => true],
                ['code' => 'MAT', 'name' => 'Matematika', 'category' => 'Wajib / Nasional', 'kkm' => 75.00, 'order_num' => 4, 'is_active' => true],
                ['code' => 'IPAS', 'name' => 'Ilmu Pengetahuan Alam & Sosial (IPAS)', 'category' => 'Wajib / Nasional', 'kkm' => 75.00, 'order_num' => 5, 'is_active' => true],
                ['code' => 'PJOK', 'name' => 'Pendidikan Jasmani, Olahraga, & Kesehatan', 'category' => 'Wajib / Nasional', 'kkm' => 75.00, 'order_num' => 6, 'is_active' => true],
                ['code' => 'SBDP', 'name' => 'Seni & Budaya', 'category' => 'Wajib / Nasional', 'kkm' => 75.00, 'order_num' => 7, 'is_active' => true],
                ['code' => 'ENG', 'name' => 'Bahasa Inggris', 'category' => 'Muatan Lokal', 'kkm' => 75.00, 'order_num' => 8, 'is_active' => true],
                ['code' => 'ARB', 'name' => 'Bahasa Arab', 'category' => 'Khas SDIT / Diniyah', 'kkm' => 75.00, 'order_num' => 9, 'is_active' => true],
                ['code' => 'TAH', 'name' => 'Tahsin & Tahfidz Al-Qur\'an', 'category' => 'Khas SDIT / Diniyah', 'kkm' => 75.00, 'order_num' => 10, 'is_active' => true],
            ];

            $now = now();
            foreach ($defaultSubjects as &$subj) {
                $subj['created_at'] = $now;
                $subj['updated_at'] = $now;
            }
            DB::table('subjects')->insert($defaultSubjects);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
