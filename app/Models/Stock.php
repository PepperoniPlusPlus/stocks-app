<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $ticker
 */
class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticker',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(StockPrice::class);
    }
}
