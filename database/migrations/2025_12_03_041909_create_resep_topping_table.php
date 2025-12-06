<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resep_topping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topping_id')->constrained('topping')->onDelete('cascade');
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->onDelete('cascade');
            $table->decimal('jumlah', 10, 3); // 0.001
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep_topping');
    }
};
