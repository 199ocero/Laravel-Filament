<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Campus::create([
            'district_id' => 1,
            'name' => 'Campus A',
        ]);

        Campus::create([
            'district_id' => 1,
            'name' => 'Campus B',
        ]);

        Campus::create([
            'district_id' => 2,
            'name' => 'Campus C',
        ]);
    }
}
