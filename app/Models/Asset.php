<?php

namespace App\Models;

use App\Jobs\FetchAndSetPrices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Asset extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($asset) {
            if (Auth::user()->id != 3) {
                $asset->user_id = Auth::user()->id;
            }
        });

        static::created(function ($asset) {
            FetchAndSetPrices::dispatch($asset);
        });
    }

    public function scopeCurrentUser($query)
    {
        if (Auth::user()->id != 3) {
            return $query->where('user_id', Auth::user()->id);
        }
        return $query;
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function quantity()
    {
        return $this->hasOne(Account::class)
            ->select(['asset_id', DB::raw('sum(quantity) as total')])
            ->groupBy('asset_id');
    }

    public function summary()
    {
        return $this->hasOne(AssetSummary::class)->where('active', 1);
    }
}
