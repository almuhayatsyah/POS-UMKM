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
        Schema::table('detail_pesanan', function (Blueprint $table) {
            // Add standard indexes first so FKs have something to hold onto
            $table->index('pesanan_id');
            $table->index('produk_id');
            
            $table->dropUnique('detail_pesanan_pesanan_id_produk_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('detail_pesanan', function (Blueprint $table) {
            // Restore unique (and drop the temp indexes if you want, but strictly unique covers index)
            $table->unique(['pesanan_id', 'produk_id'], 'detail_pesanan_pesanan_id_produk_id_unique');
            $table->dropIndex(['pesanan_id']);
            $table->dropIndex(['produk_id']);
        });
    }
};
