<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('shutdown199')
        ]);

        $this->call(StatusSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(CampusSeeder::class);
        $this->call(SchoolYearSeeder::class);
        $this->call(YearLevelSeeder::class);
        $this->call(SemesterSeeder::class);
    }
}
