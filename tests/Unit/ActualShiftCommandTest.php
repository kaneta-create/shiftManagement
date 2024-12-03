<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Models\DefaultShift;
use App\Models\ActualShift;
use Carbon\Carbon;
use Mockery;

class CreateActualShiftCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the createActualShift command.
     *
     * @return void
     */
    public function test_create_actual_shift_command_creates_shifts_correctly()
    {
        // DefaultShift モデルのモックを作成
        $defaultShifts = collect([
            (object) [
                'employee_id' => 1,
                'clock_in' => '09:00:00',
                'clock_out' => '17:00:00',
                'day_of_week' => 1, // 月曜日
            ],
            (object) [
                'employee_id' => 2,
                'clock_in' => '10:00:00',
                'clock_out' => '18:00:00',
                'day_of_week' => 3, // 水曜日
            ],
        ]);

        // モックが `all()` を返すように設定
        $this->partialMock(DefaultShift::class, function ($mock) use ($defaultShifts) {
            $mock->shouldReceive('select->get')->andReturn($defaultShifts);
        });

        // コマンドを実行
        Artisan::call('command:createActualShift');

        // 期待される期間のデータを取得して検証
        $startOfMonth = Carbon::now()->addMonths(2)->startOfMonth();
        $endOfMonth = Carbon::now()->addMonths(2)->endOfMonth();

        $period = Carbon::parse($startOfMonth)->daysUntil($endOfMonth);
        foreach ($period as $date) {
            $dayOfWeekIso = $date->dayOfWeekIso;

            foreach ($defaultShifts as $defaultShift) {
                if ($dayOfWeekIso == $defaultShift->day_of_week) {
                    $this->assertDatabaseHas('actual_shifts', [
                        'date' => $date->format('Y-m-d'),
                        'clock_in' => $defaultShift->clock_in,
                        'clock_out' => $defaultShift->clock_out,
                        'day_of_week' => $defaultShift->day_of_week,
                        'employee_id' => $defaultShift->employee_id,
                    ]);
                }
            }
        }
    }

    protected function tearDown(): void
    {
        Mockery::close(); // Mockery をクリーンアップ
        parent::tearDown();
    }
}
