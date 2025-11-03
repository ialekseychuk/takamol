<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'balance',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
