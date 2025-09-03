<?php

namespace Database\Factories;

use App\Models\User; // <-- Jangan lupa import User
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InfrastructureReport>
 */
class InfrastructureReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id, // Ambil ID user secara acak
            'type' => fake()->randomElement(['jalan', 'drainase', 'lampu']), // Jenis laporan acak
            'description' => fake()->paragraph(4), // Deskripsi dari 4 paragraf acak
            'latitude' => fake()->latitude(-7.3, -7.1), // Latitude di sekitar Garut
            'longitude' => fake()->longitude(107.8, 108.0), // Longitude di sekitar Garut
            'severity' => fake()->randomElement(['rendah', 'sedang', 'tinggi']), // Tingkat keparahan acak
            'status' => fake()->randomElement(['Baru', 'Verifikasi', 'Pengerjaan', 'Selesai']), // Status acak
            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'updated_at' => now(),
        ];
    }
}