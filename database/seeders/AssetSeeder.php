<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Asset::factory()->create();
        Asset::factory()->create([
            'name' => 'Gram Altın',
            'price_control' => '/html/body/header/div[2]/div/div[1]/div[1]/a/span[2]',
        ]);
    }
}
