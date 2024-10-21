<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'investor_id' => 1,
            'asset_id' => 2,
            'name' => $this->faker->name(),
            'quantity' => 1,
        ];
    }
}
