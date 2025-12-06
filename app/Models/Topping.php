<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topping extends Model
{
    protected $table = 'topping';
    protected $fillable = ['nama_topping', 'harga'];

    public function resepTopping()
    {
        return $this->hasMany(ResepTopping::class);
    }
}
