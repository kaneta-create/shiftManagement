<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class organizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organizations')->insert([
            [
                'name' => 'ドンキホーテ1'
            ],
            [
                'name' => 'ドンキホーテ2'
            ],
            [
                'name' => 'ドンキホーテ3'
            ],
            [
                'name' => 'ドンキホーテ4'
            ],
            [
                'name' => 'ドンキホーテ5'
            ],
            [
                'name' => 'ドンキホーテ6'
            ],
            [
                'name' => 'ドンキホーテ7'
            ],
            [
                'name' => 'ドンキホーテ8'
            ],
            [
                'name' => 'ドンキホーテ9'
            ]
        ]);
    }
}
