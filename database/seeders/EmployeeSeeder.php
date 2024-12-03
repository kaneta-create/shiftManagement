<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DefaultShift;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::count();
        // $userId = [];
        // for($i=1; $i<=$users; $i++){
        //     $userId[$i] = $userId;
        // }
        DB::table('employees')->insert([
            [
                'user_id' => 1,
                // 'employee_number' => 12345,
                'role' =>  '社員',
                'authority' => 3,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 2,
                // 'employee_number' => 12346,
                'role' =>  '社員',
                'authority' => 3,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 3,
                // 'employee_number' => 34671,
                'role' =>  '社員',
                'authority' => 2,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 4,
                // 'employee_number' => 67316,
                'role' =>  'パート',
                'authority' => 2,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 5,
                // 'employee_number' => 59761,
                'role' =>  'パート',
                'authority' => 2,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 6,
                // 'employee_number' => 12305,
                'role' =>  'パート',
                'authority' => 2,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 7,
                // 'employee_number' => 78345,
                'role' =>  'パート',
                'authority' => 1,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 8,
                // 'employee_number' => 12341,
                'role' =>  'パート',
                'authority' => 1,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 9,
                // 'employee_number' => 12945,
                'role' =>  'パート',
                'authority' => 1,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 10,
                // 'employee_number' => 40345,
                'role' =>  'パート',
                'authority' => 1,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 11,
                // 'employee_number' => 19995,
               'role' =>  'アルバイト',
                'authority' => 1,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 12,
                // 'employee_number' => 12311,
                'role' =>  'アルバイト',
                'authority' => 1,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 13,
                // 'employee_number' => 80045,
                'role' =>  'アルバイト',
                'authority' => 1,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 14,
                // 'employee_number' => 55345,
               'role' =>  'アルバイト',
                'authority' => 1,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 15,
                // 'employee_number' => 12245,
                'role' =>  'アルバイト',
                'authority' => 1,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 16,
                // 'employee_number' => 11945,
                'role' =>  'アルバイト',
                'authority' => 1,
                // 'contract_date' => '2020-08-12',
            ],
            [
                'user_id' => 17,
                // 'employee_number' => 18845,
                'role' =>  'アルバイト',
                'authority' => 1,
                // 'contract_date' => '2020-08-12',
            ],
        ]);
    }
}
