<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Seeder;
use App\Models\DefaultShift;
use App\Models\ActualShift;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class createActualShift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:createActualShift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Actual Shift for three month';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startOfMonth = Carbon::now()->addMonths(2)->startOfMonth(); // 9月の初め
        $endOfMonth = Carbon::now()->addMonths(2)->endOfMonth(); // 11月の終わり
        $now = Carbon::now();
        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

        Carbon::setLocale('ja');
        foreach($period as $date){
            $month[] = [
                'date' => $date->format('Y-m-d'),
                'day_of_week_iso' => $date->dayOfWeekIso,
                'day_of_week' => $date->translatedFormat('D'),
                'year' => $date->format('Y'),
                'month' => $date->translatedFormat('M')
            ];
            $shiftDates[] = [
                $date->format('j') => $date->translatedFormat('D')
            ];
        }

        // defaultShiftテーブルのデータを取得
        $defaultShifts = DefaultShift::select('id', 'employee_id', 'clock_in', 'clock_out', 'day_of_week')->get();

        foreach($defaultShifts as $defaultShift){
            foreach($month as $day){
                if($day['day_of_week_iso'] == $defaultShift->day_of_week){
                    // 実シフトデータを作成して保存
                    ActualShift::create([
                        'date' => $day['date'],
                        'clock_in' => $defaultShift->clock_in,
                        'clock_out' => $defaultShift->clock_out,
                        'day_of_week' => $day['day_of_week_iso'],
                        'employee_id' => $defaultShift->employee_id,
                    ]);
                }
            }
        }
    }
}
