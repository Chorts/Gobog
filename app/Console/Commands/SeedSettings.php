<?php

namespace App\Console\Commands;

use App\Models\HargaHistory;
use App\Models\Setting;
use Illuminate\Console\Command;

class SeedSettings extends Command
{
    protected $signature   = 'gobog:seed-settings';
    protected $description = 'Seed initial settings for Gobog system';

    public function handle(): void
    {
        if (! Setting::find('harga_gobog')) {
            Setting::set('harga_gobog', '5000');
            HargaHistory::create([
                'harga'      => 5000,
                'keterangan' => 'Harga awal sistem',
            ]);
            $this->info('Settings seeded: harga_gobog = 5000');
        } else {
            $this->info('Settings already exist: harga_gobog = '.Setting::get('harga_gobog'));
        }
    }
}
