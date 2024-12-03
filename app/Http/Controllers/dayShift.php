<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ActualShift;
use App\Models\employee;

class DayShift extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $userRole = employee::select('authority')->where('user_id', $userId)->first();

        for($i = 1; $i <= 3; $i++){
            
            $currentDate = Carbon::now()->copy(); 
            if($i == 1){
                $firstDayOfMonth = $currentDate->startOfMonth()->format('Y-m-d'); // 今月の初日
                $lastDayOfMonth = $currentDate->endOfMonth()->format('Y-m-d'); // 今月の末日
            } else {
                $firstDayOfMonth = Carbon::now()->copy()->addMonth( $i - 1)->startOfMonth()->format('Y-m-d'); // 来月以降の初日
                $lastDayOfMonth =  Carbon::now()->copy()->addMonth($i - 1)->endOfMonth()->format('Y-m-d'); // 来月以降の末日
            } 
            // dd($firstDayOfMonth);
            $periodShifts[] = ActualShift::select('employee_id','clock_in','clock_out','day_of_week','date')
                       ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                       ->get();
            
            if($i == 1){
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();
            } else {
                $startOfMonth = Carbon::now()->addMonth($i-1)->startOfMonth();
                $endOfMonth = Carbon::now()->addMonth($i-1)->endOfMonth();
            }
            
            $now = Carbon::now();
            $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
            
            Carbon::setLocale('ja');
            foreach($period as $date){
                $month[] = [
                    $i => [
                        'Ymd_date' => $date->format('Y-m-d'),
                        'full_date' => $date->format('n月d日'),
                        'date' => $date->format('j'),
                        'day_of_week' => $date->translatedFormat('D'),
                        'year' => $date->format('Y'),
                        'firstMonth' => $date->translatedFormat('M'),
                        'secondMonth' => $date->addMonth(1)->translatedFormat('M'),
                        'thirdMonth' => $date->addMonth(2-1)->translatedFormat('M')
                    ],                    
                ];
                $shiftDates[] = [
                    $date->format('j') => $date->translatedFormat('D')
                ];
                
            };
        };
        // dd($month[0]);
        $shiftInfos = [];
        for($j = 0; $j <= 2; $j++){
            foreach($periodShifts[$j] as $periodShift){
                $employeeName = $periodShift->employee->user->name;

                $shiftInfos[] = [
                    'employeeName' => $employeeName,
                    'clockIn' => $periodShift->clock_in,
                    'clockOut' => $periodShift->clock_out,
                    'date' => $periodShift->date,
                    'clockInHour' => date('H', strtotime($periodShift->clock_in)),
                    'clockInMinutes' => date('i', strtotime($periodShift->clock_in)),
                    'clockOutHour' => date('H', strtotime($periodShift->clock_out)),
                    'clockOutMinutes' => date('i', strtotime($periodShift->clock_out)),
                ];
            }
        }
        $earliestClockIn = ActualShift::min('clock_in');
        // $latestClockOut = ActualShift::select(DB::raw("IF(clock_out = '00:00:00', '24:00:00', clock_out) as latest_clock_out"))
        // ->orderBy('latest_clock_out', 'desc')
        // ->first()
        // ->latest_clock_out;
        $latestClockOutQuery = ActualShift::select(DB::raw("IF(clock_out = '00:00:00', '24:00:00', clock_out) as latest_clock_out"))
    ->orderBy('latest_clock_out', 'desc')
    ->first();

$latestClockOut = $latestClockOutQuery ? $latestClockOutQuery->latest_clock_out : null;


        $ClockInNumber = intval(date('H', strtotime($earliestClockIn)));
        $ClockOutNumber = intval(date('H', strtotime($latestClockOut)));
        if($ClockOutNumber == 0) {
            $ClockOutNumber = 24;
        };
        
        $totalHour = [];
        for($k = $ClockInNumber; $k <= $ClockOutNumber; $k++){
            $totalHour[] = [
                'hour'=>$k
            ];
        };

        return Inertia::render('dayShift/index', [
            'shiftInfos' => $shiftInfos,
            'month' => $month,
            'earliestClockIn' => $earliestClockIn,
            'latestClockOut' => $latestClockOut,
            'totalHour' => $totalHour,
            'userRole' => $userRole
        ]);
    }

    private function generateHourlyTimeSlots($earliestClockIn, $latestClockOut)
    {
        // earliestClockInとlatestClockOutをCarbonインスタンスに変換
        $start = Carbon::createFromFormat('H:i:s', $earliestClockIn);
        $end = Carbon::createFromFormat('H:i:s', $latestClockOut);

        $timeSlots = [];

        // 時間を1時間刻みで進めるループ
        while ($start->lte($end)) {
            $timeSlots[] = $start->format('H:i');
            // 1時間加算
            $start->addHour();
        }

        return $timeSlots;
    }
}
