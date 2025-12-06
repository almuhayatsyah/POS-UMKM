<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepTopping extends Model
{
    protected $table = 'resep_topping';
    protected $fillable = ['topping_id', 'bahan_baku_id', 'jumlah'];

    public function topping()
    {
        return $this->belongsTo(Topping::class);
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }
}
