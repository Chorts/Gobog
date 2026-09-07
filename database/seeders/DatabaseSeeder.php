<?php

namespace Database\Seeders;

use App\Models\Tenan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed tenans FIRST — users.tenans_idtenans FK requires tenans to exist
        $tenan1 = Tenan::create(['nama' => 'Warung Makan Bu Sari']);
        $tenan2 = Tenan::create(['nama' => 'Toko Oleh-oleh Pak Budi']);
        $tenan3 = Tenan::create(['nama' => 'Kedai Minuman Segar']);

        // Admin account — no tenan
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'tenans_idtenans' => null,
        ]);

        // Penjual accounts linked to tenans
        User::create([
            'nama' => 'Sari Dewi',
            'username' => 'penjual1',
            'password' => Hash::make('penjual123'),
            'role' => 'penjual',
            'tenans_idtenans' => $tenan1->idtenans,
        ]);

        User::create([
            'nama' => 'Budi Santoso',
            'username' => 'penjual2',
            'password' => Hash::make('penjual123'),
            'role' => 'penjual',
            'tenans_idtenans' => $tenan2->idtenans,
        ]);
    }
}
