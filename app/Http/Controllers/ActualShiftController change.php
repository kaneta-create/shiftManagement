<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActualShiftRequest;
use App\Http\Requests\UpdateActualShiftRequest;
use App\Models\ActualShift;
use App\Models\User;
use Inertia\Inertia;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ActualShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();
        for($i = 1; $i <= 3; $i++){

            $currentDate = Carbon::now()->copy(); 
            if($i == 1){
                $firstDayOfMonth = $currentDate->startOfMonth()->format('Y-m-d'); // 今月の初日
                $lastDayOfMonth = $currentDate->endOfMonth()->format('Y-m-d'); // 今月の末日
            } else {
                $firstDayOfMonth = $currentDate->addMonth($i - 1)->startOfMonth()->format('Y-m-d'); // 来月以降の初日
                $lastDayOfMonth = $currentDate->addMonth($i - 1)->endOfMonth()->format('Y-m-d'); // 来月以降の末日
            }
            $currentMonth = now()->format('Y-m'); // 今月の年月を取得
            $userIds = DB::table('users')->pluck('id');
            
            foreach ($userIds as $employeeId) {
                $workingHours = DB::table('actual_shifts')
                    ->select(DB::raw("
                        employee_id, 
                        SUM(
                            CASE
                                WHEN TIME_TO_SEC(clock_out) > TIME_TO_SEC(clock_in) 
                                    THEN TIME_TO_SEC(clock_out) - TIME_TO_SEC(clock_in)
                                ELSE 
                                    TIME_TO_SEC(clock_out) + 86400 - TIME_TO_SEC(clock_in)
                            END
                        ) / 3600 AS total_working_hours
                    "))
                    ->where('employee_id', $employeeId)
                    ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                    ->groupBy('employee_id')
                    ->get();
        
                $totalWorkingHours[$i][$employeeId] = $workingHours;
            }
            
            $shifts = ActualShift::select('employee_id', 'clock_in', 'clock_out', 'day_of_week', 'date')->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])->get();
            $userName = User::select('name')->get();
            $shiftInformations = []; // 配列の初期化
            $names = []; // 配列の初期化
            $addedNames = []; // 追加済みの名前を保持する配列
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
            
        };   //for分の終了地点
        
        $totalWorkingTime = intval("");
        foreach($shifts as $shift){
            $clock_in = $shift->clock_in;
            $clock_out = $shift->clock_out;
            $employeeName = $shift->employee->user->name;

            $formattedClockIn = intval(date('Hi', strtotime($clock_in)));
            
            $formattedClockOut = intval(date('Hi', strtotime($clock_out)));
            if($formattedClockOut == 0000){
                $formattedClockOut = 2400;
            };
            $clockInHours = intval(date('H', strtotime($clock_in)));
            $clockInMinutes = intval(date('i', strtotime($clock_in)));

            $clockOutHours = intval(date('H', strtotime($clock_out)));
            $clockOutMinutes = intval(date('i', strtotime($clock_out)));
            if ($clockOutHours == 0) {
                $clockOutHours = 24;
                $endTime = $clockInHours * 100;
            };
            if($clockOutMinutes == 0){
                $clockOutChangedMinutes = 60;
                $endTime = $clockOutHours * 100 + $clockOutChangedMinutes;
            } else {
                $endTime = $clockOutHours * 100;
            };
            if($clockInMinutes == 0){
                $clockInChangedMinutes = 60;
                $startTime = $clockInHours * 100 + $clockInChangedMinutes;
            } else {
                $startTime = $clockInHours * 100;
            };
            
            

            $clockIn = Carbon::parse($shift->clock_in);
            $clockOut = Carbon::parse($shift->clock_out);

            // 日をまたぐ場合の処理
            if ($clockOut->hour === 0 && $clockOut->minute === 0) {
                $clockOut = $clockOut->addDay()->setTime(24, 0);
            }

            $workingTime = $clockOut->diffInHours($clockIn);
            $totalWorkingTime += $workingTime;
            
            if (!in_array($employeeName, $addedNames)) {
                $names[] = [
                    'employee_name' => $employeeName,
                    'employee_id' => $shift->employee_id
                ];
                $addedNames[] = $employeeName; // 名前を追加済みリストに追加
            }

            $dayOfWeek = $shift->day_of_week;
            $jpDayOfWeeks = [
                1 => '月',
                2 => '火',
                3 => '水',
                4 => '木',
                5 => '金',
                6 => '土', 
                7 => '日'
            ];
            
            foreach($jpDayOfWeeks as $key => $jpDayOfWeek){
                if($shift->day_of_week == $key){
                    $dayOfWeek = $jpDayOfWeek;
                    
                    
                    $shiftInformations[] = [
                        'employee_id' => $shift->employee_id,
                        'employee_name' => $employeeName,
                        'clock_in' => $formattedClockIn,
                        'clock_out' => $formattedClockOut,
                        'day_of_week' => $dayOfWeek,
                        'date' => Carbon::parse($shift->date)->format('j'),
                        'full_date' => Carbon::parse($shift->date)->format('Y-m-d'),
                        'f_date' => Carbon::parse($shift->date)->format('m'),
                        // 'dayWorkingTime' => $totalWorkingTime
                    ];
                };
                
            };
            
        };
        
        return Inertia::render('ActualShift/index', [
            'names' => $names,
            'shifts' => $shiftInformations,
            'month' => $month,
            'totalWorkingTimes' => $totalWorkingHours,
            'userId' => $userId
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreActualShiftRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreActualShiftRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ActualShift  $actualShift
     * @return \Illuminate\Http\Response
     */
    public function show(ActualShift $actualShift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ActualShift  $actualShift
     * @return \Illuminate\Http\Response
     */
    public function edit(ActualShift $actualShift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateActualShiftRequest  $request
     * @param  \App\Models\ActualShift  $actualShift
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateActualShiftRequest $request, ActualShift $actualShift)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActualShift  $actualShift
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActualShift $actualShift)
    {
        //
    }
}
