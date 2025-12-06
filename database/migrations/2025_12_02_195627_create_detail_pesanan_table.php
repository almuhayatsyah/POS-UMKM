<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            // Foreign Key ke Pesanan
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            // Foreign Key ke Produk
            $table->foreignId('produk_id')->constrained('produk')->onDelete('restrict'); 
            
            $table->unsignedInteger('jumlah');
            $table->decimal('subtotal_item', 10, 2);
            $table->text('catatan')->nullable(); 
            $table->timestamps();
            
            // Komposit unik: Satu item produk hanya boleh sekali dalam satu pesanan
            $table->unique(['pesanan_id', 'produk_id']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
