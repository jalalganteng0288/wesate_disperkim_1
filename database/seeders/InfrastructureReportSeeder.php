<?php

namespace Database\Seeders;

use App\Models\InfrastructureReport; // <-- Import model
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InfrastructureReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Panggil factory untuk membuat 15 data laporan infrastruktur palsu
        InfrastructureReport::factory(15)->create();
    }
}