<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DefaultShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fullTimeStartHours = ['08:30', '09:00', '10:00', '12:00', '14:00', '16:00'];
        $employees = DB::table('employees')->get();

        foreach ($employees as $employee) {
            $shifts = [];
            $selectedDays = []; // 選択済みの曜日を追跡する配列

            if ($employee->role == '社員') {
                for ($i = 0; $i < 5; $i++) { // 週5日
                    $start = Carbon::createFromTimeString($fullTimeStartHours[array_rand($fullTimeStartHours)]);
                    $end = $start->copy()->addHours(8);

                    // 曜日をランダムに選択するが、重複しないようにする
                    do {
                        $dayOfWeek = Carbon::now()->startOfWeek()->addDays($i)->dayOfWeekIso;
                    } while (in_array($dayOfWeek, $selectedDays));

                    // 選択済みの曜日に追加
                    $selectedDays[] = $dayOfWeek;

                    $shifts[] = [
                        'employee_id' => $employee->id,
                        'clock_in' => $start->format('H:i'),
                        'clock_out' => $end->format('H:i'),
                        'day_of_week' => $dayOfWeek, // 曜日を数字で保存
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            } else {
                $partTimeHours = [3, 4, 5];
                $daysPerWeek = rand(3, 4); // 週3日から4日
                $selectedDays = []; // 選択済みの曜日を追跡する配列を初期化

                for ($i = 0; $i < $daysPerWeek; $i++) {
                    $start = Carbon::createFromTimeString($fullTimeStartHours[array_rand($fullTimeStartHours)]);
                    $end = $start->copy()->addHours($partTimeHours[array_rand($partTimeHours)]);

                    // 曜日をランダムに選択するが、重複しないようにする
                    do {
                        $dayOfWeek = rand(1, 7); // 1は月曜、7は日曜
                    } while (in_array($dayOfWeek, $selectedDays));

                    // 選択済みの曜日に追加
                    $selectedDays[] = $dayOfWeek;

                    $shifts[] = [
                        'employee_id' => $employee->id,
                        'clock_in' => $start->format('H:i'),
                        'clock_out' => $end->format('H:i'),
                        'day_of_week' => $dayOfWeek, // 曜日を数字で保存
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            DB::table('default_shifts')->insert($shifts);
        }

    }
}