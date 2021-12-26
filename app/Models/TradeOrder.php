<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'double',
        'transaction_fee' => 'double',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($tradeOrder) {
            if (!is_null($tradeOrder->seller_account_id)) {
                $tradeOrder->seller_account->decrement('quantity', $tradeOrder->quantity);
            }
            if (!is_null($tradeOrder->buyer_account_id)) {
                $tradeOrder->buyer_account->increment('quantity', $tradeOrder->quantity);
            }
        });
    }

    public function seller_account()
    {
        return $this->belongsTo(Account::class, 'seller_account_id');
    }

    public function buyer_account()
    {
        return $this->belongsTo(Account::class, 'buyer_account_id');
    }
}
