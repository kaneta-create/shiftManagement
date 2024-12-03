<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDefaultShiftRequest;
use App\Http\Requests\UpdateDefaultShiftRequest;
use App\Models\DefaultShift;
use App\Models\ActualShift;
use App\Models\employee;
use App\Models\RequestShift;
use App\Models\RequestShiftShift;
use App\Models\User;
use Inertia\Inertia;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DefaultShiftController extends Controller
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

        $shifts = ActualShift::select('employee_id', 'clock_in', 'clock_out', 'day_of_week')->get();
        $userName = User::select('name')->get();
        $shiftInformations = []; // 配列の初期化
        $names = []; // 配列の初期化
        $addedNames = []; // 追加済みの名前を保持する配列

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
        $userId = Auth::id();
        $userRole = employee::select('authority')->where('user_id', $userId)->first();

        $day_of_week = [
            1 => '月曜日',
            2 => '火曜日',
            3 => '水曜日',
            4 => '木曜日',
            5 => '金曜日',
            6 => '土曜日',
            7 => '日曜日'
        ];
        return Inertia::render('DefaultShift/create', [
            'day_of_week_name' => $day_of_week,
            'userRole' => $userRole
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDefaultShiftRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDefaultShiftRequest $request)
    {
        // dd($request);
        //リクエストで送られてきた社員番号からemployee_idを取得し、このテーブルのカラムに保存したい
        $userId = User::select('id')->where('employee_number', $request->employee_number)->first()->id;

        if(DB::table('default_shifts')->where('employee_id', $userId)->exists()) {
            return to_route('admins.index')
            ->with([
                'message' => '登録済みです。変更がある場合は変更画面から変更してください。',
                'status' => 'danger'
            ]);
        } else {
            $daysOfWeek = ['月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日', '日曜日'];
            foreach($daysOfWeek as $day){
                if (!is_null($request[$day]['start_time']) && !is_null($request[$day]['end_time'])){
                    DefaultShift::create([
                        'employee_id' => $userId,
                        'day_of_week' => $request[$day]['dayOfNameNumber'],
                        'clock_in' => $request[$day]['start_time'],
                        'clock_out' => $request[$day]['end_time'],
                    ]);
                }
            }
            return to_route('admins.index')
            ->with([
                'message' => '登録しました',
                'status' => 'success'
            ]);
        }
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
        
        $userId = Auth::id();
        // dd($userId);
        $userRole = employee::select('authority')->where('user_id', $userId)->first();

        $defaultShifts = DefaultShift::select('id', 'employee_id', 'clock_in', 'clock_out', 'day_of_week')->where('employee_id', $defaultShift->id)->get();
        // dd($defaultShifts);
        $day_of_week = [
            1 => '月曜日',
            2 => '火曜日',
            3 => '水曜日',
            4 => '木曜日',
            5 => '金曜日',
            6 => '土曜日',
            7 => '日曜日'
        ];
        $employeeNumber = User::select('employee_number')->where('id', $defaultShift->id)->first();
        
        return Inertia::render('DefaultShift/edit',[
            'defaultShifts' => $defaultShifts,
            'employeeNumber' => $employeeNumber->employee_number,
            'employee_id' => $defaultShift->employee_id,
            'userRole' => $userRole
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDefaultShiftRequest  $request
     * @param  \App\Models\DefaultShift  $defaultShift
     * @return \Illuminate\Http\Response
     */
    // public function update(UpdateDefaultShiftRequest $request, DefaultShift $defaultShift)
    // {
    //     $data = $request->all();
    //     // dd($data);
    //     $dayOfWeekNames = ['月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日', '日曜日'];
    //     $userId = User::where('employee_number' , $data['employeeNumber'])->value('id');
        
    //     foreach ($dayOfWeekNames as $dayOfWeek) {
    //         if (isset($data[$dayOfWeek])) {
    //             $updateShift = $data[$dayOfWeek];
                
    //             if($updateShift['start_time'] !== null && $updateShift['end_time'] !== null){
    //                 $updateData = DefaultShift::updateOrCreate(
    //                     ['employee_id' => $userId, 'day_of_week' => $updateShift['dayOfNameNumber']],
    //                     [
    //                         'clock_in' => $updateShift['start_time'], 
    //                         'clock_out' => $updateShift['end_time'], 
    //                         'day_of_week' => $updateShift['dayOfNameNumber'], 
    //                         'employee_id' => $userId, 
    //                     ]
    //                 );
                    
    //             }
    //         }
    //     }
    //     return to_route('admins.index')
    //     ->with([
    //         'message' => 'シフト情報を更新しました。',
    //         'status' => 'success'
    //     ]);

    // }
    public function update(UpdateDefaultShiftRequest $request, DefaultShift $defaultShift)
    {
        // dd($request);
        $data = $request->all();
        $dayOfWeekNames = ['月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日', '日曜日'];
        
        // ユーザーIDを取得
        $userId = User::where('employee_number', $data['employeeNumber'])->value('id');
        if (!$userId) {
            return back()->withErrors(['employeeNumber' => '従業員番号が見つかりません']);
        }

        // トランザクションの開始
        DB::beginTransaction();
        try {
            foreach ($dayOfWeekNames as $dayOfWeek) {
                if (isset($data[$dayOfWeek])) {
                    $updateShift = $data[$dayOfWeek];

                    // 既存レコードをチェック
                    $existingShift = DefaultShift::where('employee_id', $userId)
                        ->where('day_of_week', $updateShift['dayOfNameNumber'])
                        ->first();

                    if ($updateShift['start_time'] === null && $updateShift['end_time'] === null) {
                        // clock_in と clock_out が両方 null の場合、既存のレコードがあれば削除
                        if ($existingShift) {
                            $existingShift->delete();
                        }
                    } else {
                        // それ以外の場合、レコードを作成または更新
                        DefaultShift::updateOrCreate(
                            ['employee_id' => $userId, 'day_of_week' => $updateShift['dayOfNameNumber']],
                            [
                                'clock_in' => $updateShift['start_time'], 
                                'clock_out' => $updateShift['end_time'], 
                                'day_of_week' => $updateShift['dayOfNameNumber'], 
                                'employee_id' => $userId,
                            ]
                        );
                    }
                }
            }

            // トランザクションをコミット
            DB::commit();
            // return back()->with('success', 'シフトが正常に更新されました');
            return to_route('admins.index')
            ->with([
                'message' => 'シフト情報を更新しました。',
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            // エラーが発生した場合はロールバック
            DB::rollback();
            return back()->withErrors(['error' => 'シフト更新中にエラーが発生しました']);
        }
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
