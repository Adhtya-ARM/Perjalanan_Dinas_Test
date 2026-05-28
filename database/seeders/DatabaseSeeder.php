<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kota;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Cities (Kota)
        Kota::create([
            'nama' => 'Kota Jakarta',
            'latitude' => -6.208800,
            'longitude' => 106.845600,
            'provinsi' => 'DKI Jakarta',
            'pulau' => 'Jawa',
            'is_overseas' => false,
        ]);

        Kota::create([
            'nama' => 'Kota Bandung',
            'latitude' => -6.917500,
            'longitude' => 107.619100,
            'provinsi' => 'Jawa Barat',
            'pulau' => 'Jawa',
            'is_overseas' => false,
        ]);

        Kota::create([
            'nama' => 'Kota Depok',
            'latitude' => -6.402500,
            'longitude' => 106.794200,
            'provinsi' => 'Jawa Barat',
            'pulau' => 'Jawa',
            'is_overseas' => false,
        ]);

        Kota::create([
            'nama' => 'Singapore',
            'latitude' => 1.352100,
            'longitude' => 103.819800,
            'provinsi' => null,
            'pulau' => null,
            'is_overseas' => true,
        ]);

        // 2. Seed Users
        User::create([
            'name' => 'Pegawai1',
            'username' => 'pegawai1',
            'email' => 'pegawai@perusahaan.com',
            'password' => Hash::make('password'),
            'role' => 'PEGAWAI',
        ]);

        User::create([
            'name' => 'SDM',
            'username' => 'sdm',
            'email' => 'sdm@perusahaan.com',
            'password' => Hash::make('password'),
            'role' => 'DIVISI-SDM',
        ]);

        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@perusahaan.com',
            'password' => Hash::make('password'),
            'role' => 'ADMIN',
        ]);
    }
}
