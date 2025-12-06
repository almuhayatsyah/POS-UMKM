<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $fillable = ['nama_produk', 'kategori', 'harga_jual', 'tersedia'];

    public function resep()
    {
        return $this->hasMany(Resep::class);
    }

    public function varian()
    {
        return $this->hasMany(ProdukVarian::class);
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
