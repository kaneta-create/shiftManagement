<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreemployeeRequest;
use App\Http\Requests\StoreEmployeeRequest as RequestsStoreEmployeeRequest;
use App\Http\Requests\UpdateemployeeRequest;
use App\Models\employee;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();
        $organization_id = employee::where('user_id', $userId)->value('organization_id');
        $employeeIds = Employee::where('organization_id', $organization_id)->pluck('id');
        $employees = employee::select('id', 'user_id', 'role', 'authority')
                    ->where('organization_id', $organization_id)
                    ->paginate(10);
        // dd($employees);
        $userId = Auth::id();
        $userRole = employee::select('authority')->where('user_id', $userId)->first();
        // dd($userId);
        foreach($employees as $employee){
            $name = $employee->user->name;
            $authority = $employee->authority;
            
            if($authority == 3){
                $authorityRank = '管理者権限者';
            } elseif($authority == 2) {
                $authorityRank = 'シフト作成権限者';
            } else {
                $authorityRank = '一般ユーザー';
            };
           
            $employeeInformations[] = [
                'id' => $employee->id,
                'employee_number' => $employee->user->employee_number,
                'role' => $employee->role,
                'authority' => $authorityRank,
                'name' => $name,
                'temporary_password' => $employee->user->temporary_password
            ];
        }
        
        return Inertia::render('employee/index', [
            'paginate' => $employees,
            'employees' => $employeeInformations,
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
        // ユーザーの権限を取得
        $authority = Employee::where('user_id', $userId)->value('authority');
        return Inertia::render('employee/create', [
            'userRole' => $userRole
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEmployeeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestsStoreEmployeeRequest $request)
    {
        // dd($request);
        // $request->validate([
        //     'name' => ['required', 'string'],
        //     'employee_number' => ['required','integer'],
        //     'role' => ['string', 'required'],
        // ]);
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'employee_number' => $request->employee_number,
                // 'password' => Hash::make($request->employee_number),
                'temporary_password' => $request->temporary_password
            ]);
            // dd($user);
            employee::create([
                'user_id' => $user->id,
                'role' => $request->role,
                'authority' => $request->authority,
            ]);
        });
        
        // $flashMessage = session()->flash('message', '登録しました');
        return to_route('admins.index')
        ->with([
            'message' => '登録しました。',
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateemployeeRequest  $request
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateemployeeRequest $request, $id)
    {
        $employee = employee::findOrFail($id);
        $employee->authority = $request->authority;
        $employee->user_id = $request->id;
        $employee->role = $request->role;
        $employee->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(employee $employee, $id)
    {
        $deleteInfo = employee::findOrFail($id);

        try {
            $deleteInfo->delete();
    
            return to_route('admins.index')
            ->with([
                'message' => '削除しました。',
                'status' => 'success'
            ]);

        } catch (\Exception $e) {
            return back()->with([
                'message' => '削除に失敗しました。',
                'status' => 'error'
            ]);
        }
    }
}
