<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $fillable = ['pesanan_id', 'produk_id', 'produk_varian_id', 'jumlah', 'subtotal_item', 'toppings'];

    protected $casts = [
        'toppings' => 'array',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function produkVarian()
    {
        return $this->belongsTo(ProdukVarian::class);
    }
}
