<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->foreignId('kecamatan_id')->nullable()->after('user_id')->constrained('kecamatans')->onDelete('set null');
        });
    }
    public function down(): void {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropColumn('kecamatan_id');
        });
    }
};