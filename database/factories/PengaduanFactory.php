<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; // <-- PENTING: Import model User

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengaduan>
 */
class PengaduanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Membuat data palsu yang realistis menggunakan library Faker
            'user_id' => User::inRandomOrder()->first()->id, // Ambil ID user secara acak
            'judul' => fake()->sentence(6), // Membuat judul dari 6 kata acak
            'isi_laporan' => fake()->paragraph(3), // Membuat isi laporan dari 3 paragraf acak
            'status' => fake()->randomElement(['baru', 'pengerjaan', 'selesai']), // Pilih status secara acak
        ];
    }
}