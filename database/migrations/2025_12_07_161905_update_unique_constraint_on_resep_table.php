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
        Schema::table('resep', function (Blueprint $table) {
            // Add a standard index for produk_id to satisfy the foreign key constraint
            // when the unique index is dropped.
            $table->index('produk_id', 'resep_produk_id_index');
            
            // Drop the old strict unique constraint (product + ingredient)
            $table->dropUnique('resep_produk_id_bahan_baku_id_unique');

            // Add new flexible unique constraint (product + variant + ingredient)
            $table->unique(['produk_id', 'bahan_baku_id', 'produk_varian_id'], 'resep_unique_variant_ingredient');
            
            // Optional: Drop the temporary index if you want to rely on the new unique index for the FK
            // But keeping it is safer for now to avoid "needed in FK" error loop during modifications.
            // $table->dropIndex('resep_produk_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resep', function (Blueprint $table) {
            $table->dropUnique('resep_unique_variant_ingredient');
            $table->unique(['produk_id', 'bahan_baku_id'], 'resep_produk_id_bahan_baku_id_unique');
        });
    }
};
