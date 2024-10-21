<?php

namespace Database\Seeders;

use App\Models\Investor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestorSeeder extends Seeder
{
    use WithoutModelEvents;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Investor::factory()->create([
            'user_id' => 1,
            'name' => 'Ahmet Helvacı',
        ]);
    }
}
