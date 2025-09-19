<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Kecamatan, e.g., "Balubur Limbangan"
            $table->integer('total_desa')->default(0); // e.g., 14
            $table->decimal('luas_wilayah_km2', 8, 2)->default(0); // e.g., 40.00
            $table->integer('populasi')->default(0); // e.g., 40814
            $table->integer('total_rumah')->default(0); // e.g., 13000
            $table->integer('total_rutilahu')->default(0); // e.g., 280
            $table->string('status')->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kecamatans');
    }
};