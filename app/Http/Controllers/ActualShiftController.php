<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActualShiftRequest;
use App\Http\Requests\UpdateActualShiftRequest;
use App\Models\ActualShift;
use App\Models\RequestShift;
use App\Models\employee;
use App\Models\User;
use App\Policies\EmployeePolicy;
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
        $userRole = employee::select('authority')->where('user_id', $userId)->first();
        
        $logInUserName = Auth::user()->name;

        //期間指定
        for($i = 1; $i <= 3; $i++){
            
            $currentDate = Carbon::now()->copy(); 
            if($i == 1){
                $firstDayOfMonth = $currentDate->startOfMonth()->format('Y-m-d'); // 今月の初日
                $lastDayOfMonth = $currentDate->endOfMonth()->format('Y-m-d'); // 今月の末日
            } else {
                $firstDayOfMonth = Carbon::now()->copy()->addMonth( $i - 1)->startOfMonth()->format('Y-m-d'); // 来月以降の初日
                $lastDayOfMonth =  Carbon::now()->copy()->addMonth($i - 1)->endOfMonth()->format('Y-m-d'); // 来月以降の末日
            } 

            $userIds = DB::table('users')->pluck('id');
            // dd($userIds);
            foreach ($userIds as $employeeId) {
                $workingHours = DB::table('actual_shifts')
                    ->where('employee_id', $employeeId)
                    ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                    ->whereNot(function($query) {
                        // `clock_in` と `clock_out` が両方とも '09:00:00' であるレコードを除外
                        $query->where('clock_in', '09:00:00')
                            ->where('clock_out', '09:00:00');
                    })
                    ->select('employee_id', 'clock_in', 'clock_out')  // 全ての clock_in と clock_out を選択
                    ->get();
                // dd($workingHours);
                // 合計出勤時間を計算
                $totalWorkingHours = 0;
                $attendance_count = $workingHours->count();
                // dd($attendance_count);

                foreach ($workingHours as $workingHour) {
                    // clock_in と clock_out を Carbon インスタンスに変換
                    $clockIn = Carbon::createFromFormat('H:i:s', $workingHour->clock_in);
                    $clockOut = Carbon::createFromFormat('H:i:s', $workingHour->clock_out);
                    if ($workingHour->clock_out === '00:00:00') {
                        $clockOut->addDay();  // 翌日の時間として計算
                    }
                    // 出勤時間を分単位で計算し、合計出勤時間に追加
                    $totalMinutes = $clockOut->diffInMinutes($clockIn);
                    $totalWorkingHours += $totalMinutes;
                }
                
                // 合計時間を時間単位に変換
                $totalWorkingHoursInHours[$i][$employeeId] = [
                    // 'total_working_hours' => $totalWorkingHours / 60,
                    'total_working_hours' => number_format($totalWorkingHours / 60, 2),
                    // 'employee_id' => $workingHour->employee_id,
                    'employee_id' => $employeeId,
                    'attendance_count' => $attendance_count
                ];
                
            }
            
            // dd($totalWorkingHours);
            $organization_id = employee::where('user_id', $userId)->value('organization_id');
            $employeeIds = Employee::where('organization_id', $organization_id)->pluck('id');
    
            $shifts[] = ActualShift::select('employee_id', 'clock_in', 'clock_out', 'day_of_week', 'date')
            ->whereIn('employee_id', $employeeIds)
            ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
            ->get();
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
        for($j = 0; $j <= 2; $j++){
        foreach($shifts[$j] as $shift){
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
                        $j => [
                            'employee_id' => $shift->employee_id,
                            'employee_name' => $employeeName,
                            'clock_in' => $formattedClockIn,
                            'clock_out' => $formattedClockOut,
                            'day_of_week' => $dayOfWeek,
                            'full_date' => Carbon::parse($shift->date)->format('Y-m-d'),
                            'date' => Carbon::parse($shift->date)->format('j'),
                        ],
                        
                    ];
                    
                };
                
            };
            
        };
        };
        
        return Inertia::render('ActualShift/index', [
            'names' => $names,
            'shifts' => $shiftInformations,
            'month' => $month,
            'totalWorkingTimes' => $totalWorkingHoursInHours,
            'userId' => $userId,
            'userName' => $logInUserName,
            'userRole' => $userRole
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
        // dd($request);
        $shiftDatas = $request->all();
        foreach($shiftDatas['shiftUpdates'] as $shiftData){
            $dayOfWeek = Carbon::parse($shiftData['date'])->dayOfWeekIso;
            if($shiftData['isDayOff'] == 1) {
                $clockIn = '09:00';
                $clockOut = '09:00';
            } else {
                $clockIn = $shiftData['clock_in'] ?? '09:00';  // デフォルト値を設定してnullを防ぐ
                $clockOut = $shiftData['clock_out'] ?? '09:00';  // デフォルト値を設定してnullを防ぐ
            }

            actualShift::updateOrCreate(
                ['employee_id' => $shiftData['employee_id'], 'date' => $shiftData['date']],
                ['clock_in' => $clockIn, 'clock_out' => $clockOut, 'day_of_week' => $dayOfWeek]
            );
        }
        return back();
        // return to_route('actualShifts.index');
        // ->with([
        //     'message' => 'シフト情報を更新しました。',
        //     'status' => 'success'
        // ]);
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
