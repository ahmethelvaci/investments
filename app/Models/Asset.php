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

    // public function tradeBuyingOrders()
    // {
        // return $this->hasOne(Account::class)
        //     ->select([
        //         'accounts.asset_id',
        //         DB::raw('sum(trade_orders.quantity * trade_orders.price + trade_orders.transaction_fee) as total')
        //     ])
        //     ->leftJoin('trade_orders', 'trade_orders.buyer_account_id', '=', "accounts.id")
        //     ->groupBy('accounts.asset_id');
    // }

    // public function tradeSellingOrders()
    // {
        // return $this->hasOne(Account::class)
        //     ->select([
        //         'accounts.asset_id',
        //         DB::raw('sum(trade_orders.quantity * trade_orders.price + trade_orders.transaction_fee) as total')
        //     ])
        //     ->leftJoin('trade_orders', 'trade_orders.seller_account_id', '=', "accounts.id")
        //     ->groupBy('accounts.asset_id');
    // }

    // public function tradeOrders()
    // {
        // return $this->hasOne(Account::class)
        //     ->select([
        //         'accounts.asset_id',
        //         DB::raw(
        //             'sum(
        //                 IF(`trade_orders`.`buyer_account_id` = `accounts`.`id`, `trade_orders`.`quantity` * `trade_orders`.`price` + `trade_orders`.`transaction_fee`, 0)
        //             ) - sum(
        //                 IF(`trade_orders`.`seller_account_id` = `accounts`.`id`, `trade_orders`.`quantity` * `trade_orders`.`price` + `trade_orders`.`transaction_fee`, 0)
        //             ) as total'
        //         )
        //     ])
        //     ->leftJoin('trade_orders', function ($join) {
        //         $join->on('trade_orders.buyer_account_id', '=', "accounts.id")
        //             ->orOn('trade_orders.seller_account_id', '=', "accounts.id");
        //     })
        //     ->groupBy('accounts.asset_id');
    // }

    // public function getlastPriceAttribute()
    // {
        // return Price::lastPrice($this->id);
    // }
}
