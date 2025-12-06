<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->onDelete('set null'); // Siapa kasir/admin yang input
            
            $table->string('nomor_antrian', 20)->unique();
            $table->string('nama_pelanggan', 100)->nullable();
            $table->decimal('total_bayar', 10, 2);
            
            $table->enum('status_pembayaran', ['LUNAS', 'BELUM_BAYAR'])->default('LUNAS');
            $table->enum('status_pesanan', ['DIPROSES', 'SIAP_AMBIL', 'SELESAI'])->default('DIPROSES');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
