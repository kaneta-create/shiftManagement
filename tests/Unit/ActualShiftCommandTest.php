<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Models\DefaultShift;
use App\Models\ActualShift;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\User;
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
        $users = User::factory()->count(2)->create();
        // dd($users);
        Organization::create([
            'name' => 'ドンキ'
        ]);
        Organization::create([
                'name' => 'ドンキ1'
        ]);
        $organizations = Organization::select()->get();
        Employee::create([
            'user_id' => $users[0]->id,
            'organization_id' => $organizations[0]->id,
            'role' => '社員',
            'authority' => 1,

        ]);
        Employee::create([
            'user_id' => $users[1]->id,
            'organization_id' => $organizations[0]->id,
            'role' => '社員',
            'authority' => 1
        ]);
        
        $employees = Employee::select()->get();
        $defaultShifts = collect([
            DefaultShift::create([
                'employee_id' => $employees[0]->id,
                'day_of_week' => 1, // 月曜日
                'clock_in' => '09:00:00',
                'clock_out' => '17:00:00',
            ]),
            DefaultShift::create([
                'employee_id' => $employees[1]->id,
                'day_of_week' => 3, // 水曜日
                'clock_in' => '10:00:00',
                'clock_out' => '18:00:00',
            ]),
        ]);
        
        Artisan::call('command:createActualShift');

        // 日付範囲を設定
        $startOfMonth = Carbon::now()->addMonths(2)->startOfMonth();
        $endOfMonth = Carbon::now()->addMonths(2)->endOfMonth();
        $testDates = collect(Carbon::parse($startOfMonth)->daysUntil($endOfMonth))->take(3);
     
        // データベース検証

        foreach ($testDates as $date) {
            foreach ($defaultShifts as $defaultShift) {
                if ($date->dayOfWeekIso == $defaultShift->day_of_week) {
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
