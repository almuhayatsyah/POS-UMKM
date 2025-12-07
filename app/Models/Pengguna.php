<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengguna';
    protected $fillable = ['nama', 'email', 'kata_sandi', 'peran'];
    protected $hidden = ['kata_sandi', 'remember_token'];

    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'pengguna_id');
    }

    public function isAdmin()
    {
        return $this->peran === 'ADMIN';
    }

    public function isKasir()
    {
        return $this->peran === 'KASIR';
    }

    public function isDapur()
    {
        return $this->peran === 'DAPUR';
    }
}
