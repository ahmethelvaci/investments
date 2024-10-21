<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TradeOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'seller_account_id' => null,
            'buyer_account_id' => 1,
            'quantity' => 1,
            'price' => 3000,
            'transaction_fee' => 10,
        ];
    }
}
