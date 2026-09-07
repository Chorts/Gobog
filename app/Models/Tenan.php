<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenan extends Model
{
    protected $primaryKey = 'idtenans';

    public $timestamps = false;

    protected $fillable = ['nama'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'tenans_idtenans', 'idtenans');
    }
}
