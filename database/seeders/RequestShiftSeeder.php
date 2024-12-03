<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('request_shifts')->insert([
            [
                'employee_id' => 1,
                'requested_date' => '2024-09-27',
                'new_clock_in' => '08:00:00',
                'new_clock_out' => '12:12:00',
                'is_requested_day_off' => 0

            ],
            [
                'employee_id' => 2,
                'requested_date' => '2024-09-25',
                'new_clock_in' => null,
                'new_clock_out' => null,
                'is_request_day_off' => 1
            ],
            [
                'employee_id' => 3,
                'requested_date' => '2024-09-28',
                'new_clock_in' => '08:00:00',
                'new_clock_out' => '12:12:00',
                'is_requested_day_off' => 0
            ]
            ]);
    }
}
