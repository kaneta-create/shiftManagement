<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use App\Models\employee;
use App\Models\User;
use App\Models\ActualShift;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Carbon\Carbon;

class DayShiftTest extends TestCase
{
    use RefreshDatabase;
    protected $service;
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('config:clear');
 
        # このように書く
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        
        $this->assertTrue(true);
    }

    public function test_can_get_logined_user()
    {
        $mockedUserId = 1;
        $mockedAuthority = 3;

        $userMock = Mockery::mock(User::class);
        $employeeMock = Mockery::mock(employee::class);

        // Auth::id() のモック設定
            Auth::shouldReceive('id')
            ->once()
            ->andReturn($mockedUserId);
            Auth::id();
        // Employee モデルのselectクエリ結果のモック設定
        $employeeMock->shouldReceive('select')
            ->with('authority')
            ->andReturnSelf();

        $employeeMock->shouldReceive('where')
            ->with('user_id', $mockedUserId)
            ->andReturnSelf();

        $employeeMock->shouldReceive('first')
            ->andReturn((object) ['authority' => $mockedAuthority]);

        $userRole = $employeeMock->select('authority')->where('user_id', $mockedUserId)->first();

        $this->assertEquals($mockedAuthority, $userRole->authority);
    }

    public function test_can_calculate_date_ranges_for_three_months()
    {
        // テスト用の基準日を設定（例: 2023-01-15）
        $fixedDate = Carbon::create(2023, 1, 15);
        
        // Carbon::now() のモック
        Carbon::setTestNow($fixedDate);

        // 期待される開始日と終了日を定義
        $expectedDates = [
            [
                'firstDay' => '2023-01-01', // 今月の初日
                'lastDay' => '2023-01-31'   // 今月の末日
            ],
            [
                'firstDay' => '2023-02-01', // 来月の初日
                'lastDay' => '2023-02-28'   // 来月の末日
            ],
            [
                'firstDay' => '2023-03-01', // 再来月の初日
                'lastDay' => '2023-03-31'   // 再来月の末日
            ]
        ];

        // 3か月分の日付範囲を計算
        $actualDates = [];
        for ($i = 1; $i <= 3; $i++) {
            if ($i === 1) {
                $firstDayOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
                $lastDayOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
            } else {
                $firstDayOfMonth = Carbon::now()->copy()->addMonth($i - 1)->startOfMonth()->format('Y-m-d');
                $lastDayOfMonth = Carbon::now()->copy()->addMonth($i - 1)->endOfMonth()->format('Y-m-d');
            }

            $actualDates[] = [
                'firstDay' => $firstDayOfMonth,
                'lastDay' => $lastDayOfMonth
            ];
        }

        // 期待される日付範囲と実際の範囲が一致するかを確認
        $this->assertEquals($expectedDates, $actualDates);

        // Carbon のテスト日時をリセット
        Carbon::setTestNow();
    }


    public function test_can_get_earliest_clock_in_and_latest_clock_out_using_mock()
    {
        // MockeryでActualShiftモデルをモック化
        $actualShiftMock = Mockery::mock(ActualShift::class);

        // モックが最も早い出勤時間を返す設定
        $actualShiftMock->shouldReceive('min')
            ->with('clock_in')
            ->andReturn('07:00:00');

        // モックが最新の退勤時間を返す設定
        $actualShiftMock->shouldReceive('selectRaw')
            ->with("IF(clock_out = '00:00:00', '24:00:00', clock_out) as latest_clock_out")
            ->andReturnSelf();

        $actualShiftMock->shouldReceive('orderBy')
            ->with('latest_clock_out', 'desc')
            ->andReturnSelf();

        $actualShiftMock->shouldReceive('first')
            ->andReturn((object) ['latest_clock_out' => '24:00:00']);

        // 最も早い出勤時間の取得とアサート
        $earliestClockIn = $actualShiftMock->min('clock_in');
        $this->assertEquals('07:00:00', $earliestClockIn, 'The earliest clock-in time should be 07:00:00');

        // 最新の退勤時間の取得とアサート
        $latestClockOutQuery = $actualShiftMock->selectRaw("IF(clock_out = '00:00:00', '24:00:00', clock_out) as latest_clock_out")
            ->orderBy('latest_clock_out', 'desc')
            ->first();

        $latestClockOut = $latestClockOutQuery ? $latestClockOutQuery->latest_clock_out : null;
        $this->assertEquals('24:00:00', $latestClockOut, 'The latest clock-out time should be converted to 24:00:00 if it is 00:00:00');
    }


}
