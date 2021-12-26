<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Investor extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($investor) {
            if (Auth::user()->id != 3) {
                $investor->user_id = Auth::user()->id;
            }
        });
    }

    public function scopeCurrentUser($query)
    {
        if (Auth::user()->id != 3) {
            return $query->where('user_id', Auth::user()->id);
        } else {
            return $query;
        }

    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function summary()
    {
        return $this->hasOne(InvestorSummary::class)->where('active', 1);
    }

    // public function tradeBuyingOrders()
    // {
        // return $this->hasOne(Account::class)
        //     ->select([
        //         'accounts.investor_id',
        //         DB::raw('sum(trade_orders.quantity * trade_orders.price + trade_orders.transaction_fee) as total')
        //     ])
        //     ->leftJoin('trade_orders', 'trade_orders.buyer_account_id', '=', "accounts.id")
        //     ->groupBy('accounts.investor_id');
    // }

    // public function tradeSellingOrders()
    // {
        // return $this->hasOne(Account::class)
        //     ->select([
        //         'accounts.investor_id',
        //         DB::raw('sum(trade_orders.quantity * trade_orders.price + trade_orders.transaction_fee) as total')
        //     ])
        //     ->leftJoin('trade_orders', 'trade_orders.seller_account_id', '=', "accounts.id")
        //     ->groupBy('accounts.investor_id');
    // }

    // public function tradeOrders()
    // {
        // return $this->hasOne(Account::class)
        //     ->select([
        //         'accounts.investor_id',
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
        //     ->groupBy('accounts.investor_id');
    // }

    // public function currentAssets()
    // {
        // return $this->hasOne(Account::class)
        //     ->select([
        //         'accounts.investor_id',
        //         DB::raw('sum(`accounts`.`quantity` * (
        //             select `prices`.`price`
        //             from `prices`
        //             where `prices`.`asset_id` = `accounts`.`asset_id`
        //             order by `prices`.`created_at` desc
        //             limit 1
        //         )) as total')
        //     ])
        //     ->groupBy('accounts.investor_id');
    // }
}
