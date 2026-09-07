<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'tenans_idtenans',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function tenan(): BelongsTo
    {
        return $this->belongsTo(Tenan::class, 'tenans_idtenans', 'idtenans');
    }
}
