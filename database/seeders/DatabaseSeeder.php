<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\ActualShift;
use App\Models\RequestShift;
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
        \App\Models\User::factory(17)->create();

       $this->call([
            EmployeeSeeder::class,
            DefaultShiftSeeder::class,
            ActualShiftSeeder::class,
            RequestShiftSeeder::class
       ]);
    }
}
