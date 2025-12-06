<?php

// File: database/migrations/xxxx_xx_xx_xxxxxx_create_resep_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resep', function (Blueprint $table) {
            $table->id();
            // Foreign Key ke Produk
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            // Foreign Key ke Bahan Baku
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->onDelete('cascade');
            
            $table->decimal('jumlah_bahan', 8, 3); // Presisi lebih tinggi untuk jumlah bahan
            
            // Komposit unik: Satu produk tidak boleh punya dua entri bahan yang sama
            $table->unique(['produk_id', 'bahan_baku_id']); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep');
    }
};