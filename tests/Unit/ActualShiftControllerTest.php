<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mockery;
use Illuminate\Support\Collection as EloquentCollection;
use App\Models\ActualShift;
// use Tests\TestCase;

class ActualShiftControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }
    public function testActualShiftSelectionByDateRangeWithMock()
    {
        $firstDayOfMonth = '2023-10-01';
        $lastDayOfMonth = '2023-10-31';

        $expectedData = [
            [
                'employee_id' => 1,
                'clock_in' => '09:00:00',
                'clock_out' => '18:00:00',
                'day_of_week' => 'Monday',
                'date' => '2023-10-10',
            ],
            [
                'employee_id' => 2,
                'clock_in' => '08:00:00',
                'clock_out' => '17:00:00',
                'day_of_week' => 'Tuesday',
                'date' => '2023-10-15',
            ]
        ];

        $actualShiftMock = Mockery::mock(ActualShift::class);
        $actualShiftMock->shouldReceive('select')
            ->with('employee_id', 'clock_in', 'clock_out', 'day_of_week', 'date')
            ->andReturnSelf();
        $actualShiftMock->shouldReceive('whereBetween')
            ->with('date', [$firstDayOfMonth, $lastDayOfMonth])
            ->andReturnSelf();
        $actualShiftMock->shouldReceive('get')
            ->andReturn(collect($expectedData));

        // モックを使ったデータの取得
        $shifts = $actualShiftMock->select('employee_id', 'clock_in', 'clock_out', 'day_of_week', 'date')
            ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
            ->get()
            ->toArray();
        // dd($shifts);
        // アサーション
        $this->assertEquals($expectedData, $shifts);
    }
}
