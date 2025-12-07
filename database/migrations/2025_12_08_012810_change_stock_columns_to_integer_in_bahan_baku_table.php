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
        Schema::table('bahan_baku', function (Blueprint $table) {
            $table->integer('stok_saat_ini')->change();
            $table->integer('stok_minimum')->change();
        });
    }

    public function down(): void
    {
        Schema::table('bahan_baku', function (Blueprint $table) {
            $table->decimal('stok_saat_ini', 10, 2)->change();
            $table->decimal('stok_minimum', 10, 2)->change();
        });
    }
};
