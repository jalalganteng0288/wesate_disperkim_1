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
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang mempublikasikan
            $table->string('judul');
            $table->string('slug')->unique(); // Untuk URL yang SEO-friendly, e.g., /berita/ini-judul-berita
            $table->longText('konten'); // Menggunakan longText untuk isi berita yang panjang
            $table->string('status')->default('draft'); // Status: 'draft' atau 'published'
            $table->timestamp('published_at')->nullable(); // Tanggal kapan berita dipublikasikan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
