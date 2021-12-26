<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($account) {
            if (is_null($account->name)) {
                $account->name = $account->investor->name . ' - ' . $account->asset->name;
            }
        });
    }

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function getTradeOrdersAttribute()
    {
        return TradeOrder::where('buyer_account_id', $this->id)
            ->orWhere('seller_account_id', $this->id)
            ->latest()
            ->get();
    }

    public function summary()
    {
        return $this->hasOne(AccountSummary::class)->where('active', 1);
    }

    // public function summedTradeBuyingOrders()
    // {
        // return $this->hasOne(TradeOrder::class, 'buyer_account_id')
        //     ->select([
        //         'buyer_account_id',
        //         DB::raw('sum(quantity) as quantitytotal'),
        //         DB::raw('sum(quantity * price + transaction_fee) as total')
        //     ])
        //     ->groupBy('buyer_account_id');
    // }

    // public function summedTradeSellingOrders()
    // {
        // return $this->hasOne(TradeOrder::class, 'seller_account_id')
        //     ->select([
        //         'seller_account_id',
        //         DB::raw('sum(quantity) as quantitytotal'),
        //         DB::raw('sum(quantity * price + transaction_fee) as total')
        //     ])
        //     ->groupBy('seller_account_id');
    // }

    // public function getlastPriceAttribute()
    // {
        // return Price::lastPrice($this->asset_id);
    // }
}
