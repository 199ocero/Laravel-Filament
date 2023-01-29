<?php

namespace Database\Seeders;

use App\Models\SchoolYear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SchoolYear::create([
            'campus_id' => 1,
            'name' => '2023-2024',
            'status_id' => 1,
        ]);

        SchoolYear::create([
            'campus_id' => 2,
            'name' => '2022-2023',
            'status_id' => 2,
        ]);
    }
}
