<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanTenan extends Model
{
    protected $fillable = ['users_id', 'gobogs_id', 'valid_status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function gobog(): BelongsTo
    {
        return $this->belongsTo(Gobog::class, 'gobogs_id');
    }
}
