<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gobog extends Model
{
    protected $fillable = [
        'kode_unik',
        'nilai',
        'foto',
        'qr_code',
        'kode_enkripsi',
        'status',
    ];

    public function penjualanAdmins(): HasMany
    {
        return $this->hasMany(PenjualanAdmin::class, 'gobogs_id');
    }

    public function penjualanTenans(): HasMany
    {
        return $this->hasMany(PenjualanTenan::class, 'gobogs_id');
    }

    public function returnGobogs(): HasMany
    {
        return $this->hasMany(ReturnGobog::class, 'gobogs_id');
    }
}
