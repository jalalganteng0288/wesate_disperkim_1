<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('housing_units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('address');
            $table->string('developer_name');
            $table->string('contact_person');
            $table->string('unit_type'); // Contoh: Subsidi, Komersil
            $table->integer('total_units')->default(0);
            $table->text('photo_gallery')->nullable(); // Simpan path gambar dalam format JSON
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housing_units');
    }
};
