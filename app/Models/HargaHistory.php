<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaHistory extends Model
{
    protected $fillable = ['harga', 'keterangan'];

    protected function casts(): array
    {
        return ['harga' => 'decimal:2'];
    }
}
