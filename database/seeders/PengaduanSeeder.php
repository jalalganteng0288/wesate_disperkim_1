<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengaduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Panggil PengaduanFactory untuk membuat 20 data pengaduan palsu
        \App\Models\Pengaduan::factory(20)->create();
    }
}