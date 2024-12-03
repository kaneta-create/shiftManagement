<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDefaultShiftRequest;
use App\Http\Requests\UpdateDefaultShiftRequest;
use App\Models\DefaultShift;
use App\Models\RequestShift;
use App\Models\RequestShiftShift;
use App\Models\User;
use Inertia\Inertia;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DefaultShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = DefaultShift::select('employee_id', 'clock_in', 'clock_out', 'day_of_week')->get();
        $userName = User::select('name')->get();
        $shiftInformations = []; // 配列の初期化
        $names = []; // 配列の初期化
        $addedNames = []; // 追加済みの名前を保持する配列
        
        $requestShifts = RequestShift::select('employee_id','requested_date', 'new_clock_in', 'new_clock_out', 'is_requested_day_off')
                    ->get();//後でwherebetweenで期間を指定
        
        

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $now = Carbon::now();
        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
        
        Carbon::setLocale('ja');
        foreach($period as $date){
            $month[] = [
                'date' => $date->format('j'),
                'day_of_week' => $date->translatedFormat('D'),
                'year' => $date->format('Y'),
                'month' => $date->translatedFormat('M')
            ];
            $shiftDates[] = [
                $date->format('j') => $date->translatedFormat('D')
            ];
            
        };
        
        foreach($requestShifts as $requestShift){
                $requestedDate = Carbon::parse($requestShift->requested_date)->format('j');
                foreach($shiftDates as $key => $shiftDate){
                    if($key == $requestedDate){
                        $new_clock_in = $requestShift->new_clock_in;
                        $new_clock_out = $requestShift->new_clock_out;
                        // break;
                    } else {

                    };
                }
                $formattedNewClockIn = intval(date('Hi', strtotime($requestShift->new_clock_in)));
                $formattedNewClockOut = intval(date('Hi', strtotime($requestShift->new_clock_out)));
                $shiftRequests[] = [
                    'employee_id' => $requestShift->employee_id,
                    'requested_date' => $requestedDate,
                    'new_clock_in' => $formattedNewClockIn,
                    'new_clock_out' => $formattedNewClockOut,
                    'day_off' =>$requestShift->is_requested_day_off, 
                ];
            };
        foreach($shifts as $shift){
            $clock_in = $shift->clock_in;
            $clock_out = $shift->clock_out;
            

            $employeeName = $shift->employee->user->name;
            
            
            

            $formattedClockIn = intval(date('Hi', strtotime($clock_in)));
            $formattedClockOut = intval(date('Hi', strtotime($clock_out)));
            
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
            
            $workingTime = $endTime - $startTime;
            if($workingTime % 100 == 0){
                $DayWorkingTime = $workingTime / 100;
            } else {
                $DayWorkingTime = $workingTime;
            }
           
            // dd($DayWorkingTime, $startTime,$workingTime);
            if (!in_array($employeeName, $addedNames)) {
                $names[] = [
                    'employee_name' => $employeeName,
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
            
            // $enDayOfWeeks = [
            //     1 => 'Monday',
            //     2 => 'Tuesday',
            //     3 => 'Wednesday',
            //     4 => 'Thursday',
            //     5 => 'Friday',
            //     6 => 'Saturday',
            //     7 => 'Sunday'
            // ];
            
            // $startDay = Carbon::now()->startOfMonth();
            // $endDay = Carbon::now()->endOfMonth();
            // $nowDate = Carbon::now();
            // $periods = CarbonPeriod::create($startDay, $endDay);
            // function getDate($periods, $shift, $enDayOfWeeks){
            //    $workingDates = [];
            //     foreach($periods as $term){
                    
            //         foreach($enDayOfWeeks as $key => $enDayOfWeek){
            //             $dayOfWeek = $term->format('l');
            //             if($shift->day_of_week == $key){//shift->day_of_weekに合わせた期間内の日付を取得したい
            //                 $trasratedDayOfWeek = $enDayOfWeek;

            //                 $workingDates[] = $term->format('j');
            //             }
            //             // if($dayOfWeek == $shift->day_of_week){
            //             //     $workingDates[] = $term->format('j');
            //             // }
            //         }
            //         return $workingDates;
            //     }
            // };
            // dd(getDate($periods, $shift, $enDayOfWeeks));
            foreach($jpDayOfWeeks as $key => $jpDayOfWeek){
                if($shift->day_of_week == $key){
                    $dayOfWeek = $jpDayOfWeek;
                    
                    $shiftInformations[] = [
                        // 'date' => $dat,
                        'employee_id' => $shift->employee_id,
                        'employee_name' => $employeeName,
                        'clock_in' => $formattedClockIn,
                        'clock_out' => $formattedClockOut,
                        'day_of_week' => $dayOfWeek,
                        'dayWorkingTime' => $DayWorkingTime
                    ];
                }
                        }
            
        };
        
        // dd( $shiftInformations);
        return Inertia::render('shift/index', [
            'names' => $names,
            'shifts' => $shiftInformations,
            'month' => $month,
            'shiftRequests' => $shiftRequests
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
     * @param  \App\Http\Requests\StoreDefaultShiftRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDefaultShiftRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DefaultShift  $defaultShift
     * @return \Illuminate\Http\Response
     */
    public function show(DefaultShift $defaultShift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DefaultShift  $defaultShift
     * @return \Illuminate\Http\Response
     */
    public function edit(DefaultShift $defaultShift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDefaultShiftRequest  $request
     * @param  \App\Models\DefaultShift  $defaultShift
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDefaultShiftRequest $request, DefaultShift $defaultShift)
    {
        //デフォルトシフトの処理分岐コードだが多分使わない　一時保存

        //$requestの配列を作成
        // foreach($defaultShifts as $defaultShift){
        //     foreach($data as $shift){
        //         foreach($dayOfWeekNames as $key => $dayOfWeek){
        //             //削除処理
        //             if($shift[$dayOfWeek]->dayOfNameNumber == $defaultShift->day_of_week && $shift[$dayOfWeek]->start_time == null && $shift[$dayOfWeek]->end_time == null){
        //                 $defaultShift::delete();
        //             }elseif($defaultShift->day_of_week !== $key + 1  && $shift[$dayOfWeek]->start_time !== null && $shift[$dayOfWeek]->end_time !== null){
        //                 Defaultshift::create([
        //                     'employee_id' => $defaultShift->employee_id,
        //                     'clock_in' => $shift[$dayOfWeek]->start_time,
        //                     'clock_out' => $shift[$dayOfWeek]->end_time,
        //                     'day_of_week' => $shift[$dayOfWeek]->dayOfNameNumber,
        //                     'attendance_date' => '2024-09-09'//この処理のコードが書き終わったらこのカラムは削除して、ダミーデータファイルも編集
        //                 ]);
        //             }else{
        //                 $defaultShift->clock_in = $shift[$dayOfWeek]->start_time;
        //                 $defaultShift->clock_out = $shift[$dayOfWeek]->end_time;
        //             };
        //         }
        //     }
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DefaultShift  $defaultShift
     * @return \Illuminate\Http\Response
     */
    public function destroy(DefaultShift $defaultShift)
    {
        //
    }
}
