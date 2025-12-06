<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->foreignId('produk_varian_id')->nullable()->after('produk_id')->constrained('produk_varian')->onDelete('set null');
            $table->json('toppings')->nullable()->after('subtotal_item'); // Stores array of selected toppings: [{id, nama, harga}]
        });
    }

    public function down(): void
    {
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->dropForeign(['produk_varian_id']);
            $table->dropColumn(['produk_varian_id', 'toppings']);
        });
    }
};
