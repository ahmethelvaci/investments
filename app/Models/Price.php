<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Price extends Model
{
    use HasFactory;
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'double',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($price) {
            if (is_null($price->created_at)) {
                $price->created_at = now();
            }
        });

        static::created(function ($price) {
            $price->removeLastPricesFromCache();
        });
    }

    public static function lastPrice($asset_id)
    {
        return Cache::rememberForever('last_price.' . $asset_id, function () use ($asset_id) {
            return self::where('asset_id', $asset_id)->latest()->first();
        });
    }

    public function removeLastPricesFromCache()
    {
        Cache::forget('last_price.' . $this->asset_id);
    }
}
