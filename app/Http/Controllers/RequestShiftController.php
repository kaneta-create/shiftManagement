<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestShiftRequest;
use App\Http\Requests\UpdateRequestShiftRequest;
use App\Models\RequestShift;
use Illuminate\Support\Facades\DB;

class RequestShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreRequestShiftRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequestShiftRequest $request)
    {
        dd($request);
        RequestShift::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'requested_date' => $request->date
            ],
            [
                'new_clock_in' => $request->clock_in,
                'new_clock_out' => $request->clock_out,
                'is_requested_day_off' => $request->isDayOff
            ]);

        return to_route('actualShifts.index')
        ->with([
            'message' => '変更しました',
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RequestShift  $requestShift
     * @return \Illuminate\Http\Response
     */
    public function show(RequestShift $requestShift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RequestShift  $requestShift
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestShift $requestShift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRequestShiftRequest  $request
     * @param  \App\Models\RequestShift  $requestShift
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestShiftRequest $request, RequestShift $requestShift)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RequestShift  $requestShift
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestShift $requestShift)
    {
        //
    }
}
