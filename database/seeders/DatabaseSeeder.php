<?php

namespace Database\Seeders;

use App\Jobs\FetchAndSetPrices;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => 'Ahmet Helvacı',
            'email' => 'ahmethelv@gmail.com',
        ]);

        $this->call([
            AssetSeeder::class,
            InvestorSeeder::class,
            AccountSeeder::class,
            TradeOrderSeeder::class,
        ]);

        FetchAndSetPrices::dispatchSync(null, true);
    }
}
