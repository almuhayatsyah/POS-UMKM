<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'resep';
    protected $fillable = ['produk_id', 'produk_varian_id', 'bahan_baku_id', 'jumlah_bahan'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function produkVarian()
    {
        return $this->belongsTo(ProdukVarian::class);
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }
}
