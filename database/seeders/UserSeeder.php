<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengguna::create([
            'nama' => 'Administrator',
            'email' => 'admin@gmail.com',
            'kata_sandi' => Hash::make('12345678'),
            'peran' => 'ADMIN',
            'email_verified_at' => now(),
        ]);
    }
}
