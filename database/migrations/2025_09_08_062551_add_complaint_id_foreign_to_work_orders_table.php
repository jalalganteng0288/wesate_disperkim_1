<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // Kode ini HANYA menambahkan foreign key ke kolom yang sudah ada
            $table->foreign('complaint_id')
                  ->references('id')
                  ->on('pengaduans') // <-- Mengacu ke tabel pengaduans
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // Kode ini untuk menghapus foreign key jika migrasi di-rollback
            $table->dropForeign(['complaint_id']);
        });
    }
};