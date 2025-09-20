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
        Schema::table('audit_logs', function (Blueprint $table) {
            // 1. Hapus foreign key dulu (nama default: audit_logs_user_id_foreign)
            $table->dropForeign(['user_id']);

            // 2. Ubah kolom menjadi nullable
            // Kita gunakan tipe data eksplisit untuk menghindari kebingungan
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // 3. Tambahkan kembali foreign key
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // 1. Hapus foreign key
            $table->dropForeign(['user_id']);

            // 2. Ubah kolom menjadi NOT nullable
            $table->unsignedBigInteger('user_id')->nullable(false)->change();

            // 3. Tambahkan kembali foreign key
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};