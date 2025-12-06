<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // We need to modify the enum column. 
            // Since Doctrine DBAL doesn't support changing enum values easily, 
            // we will use a raw statement or change it to string if acceptable, 
            // but for now let's try to redefine it.
            // Actually, best practice for enum changes in Laravel/MySQL is raw SQL.
            
            DB::statement("ALTER TABLE pesanan MODIFY COLUMN status_pembayaran ENUM('LUNAS', 'BELUM_BAYAR', 'BELUM_LUNAS') DEFAULT 'BELUM_LUNAS'");
            DB::statement("ALTER TABLE pesanan MODIFY COLUMN status_pesanan ENUM('DIPROSES', 'SIAP_AMBIL', 'SELESAI', 'SIAP_SAJI') DEFAULT 'DIPROSES'");
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // Revert changes if needed, though enum reverts are tricky without knowing exact previous state.
            // We'll leave it as is for now or revert to original set.
        });
    }
};
