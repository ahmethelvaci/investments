<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'name' => 'Dolar',
            'web_address' => 'https://www.doviz.com',
            'price_control' => '/html/body/header/div[2]/div/div[1]/div[2]/a/span[2]',
        ];
    }
}
