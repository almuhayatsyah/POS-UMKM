<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk', 100);
            $table->decimal('harga_jual', 10, 2);
            $table->string('kategori', 50)->nullable();
            $table->string('url_gambar')->nullable();
            $table->boolean('tersedia')->default(true); // 1 = Ya, 0 = Tidak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
