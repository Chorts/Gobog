<?php

namespace Database\Seeders;

use App\Models\HargaHistory;
use App\Models\Setting;
use App\Models\Tenan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('harga_gobog', '5000');

        HargaHistory::create([
            'harga' => 5000,
            'keterangan' => 'Harga awal sistem',
        ]);

        $tenan1 = Tenan::create(['nama' => 'Warung Makan Bu Sari']);
        $tenan2 = Tenan::create(['nama' => 'Toko Oleh-oleh Pak Budi']);
        $tenan3 = Tenan::create(['nama' => 'Kedai Minuman Segar']);

        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'tenans_idtenans' => null,
        ]);

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
