<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan', 100)->unique();
            $table->decimal('stok_saat_ini', 10, 2)->default(0);
            $table->string('satuan', 20); // KG, BUAH, GR, LITER
            $table->decimal('harga_beli_terakhir', 10, 2)->nullable();
            $table->decimal('stok_minimum', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};