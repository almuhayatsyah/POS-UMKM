<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';
    protected $fillable = ['nama_bahan', 'stok_saat_ini', 'satuan', 'harga_beli_terakhir', 'stok_minimum'];

    public function resep()
    {
        return $this->hasMany(Resep::class, 'bahan_baku_id');
    }
}
