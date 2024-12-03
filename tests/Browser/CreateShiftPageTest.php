<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\employee;
use App\Models\DefaultShift;
use App\Models\ActualShift;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CreateShiftPageTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    use DatabaseMigrations;
    public function setUp(): void
     {
        parent::setUp();
        $user = User::factory()->create([
            'employee_number' => 11111,
            'password' => Hash::make('password')
        ]);
        
        // foreach($users as $user){
        
        $employee = employee::create([
                'user_id' => $user->id,
                'authority' => 3,
                'role' => '社員'
            ]);
        // }
        DefaultShift::create([
            'employee_id' => $employee->id,
            'clock_in' => '09:00:00',
            'clock_out' => '15:00:00',
            'day_of_week' => 1
        ]);

        $startOfMonth = Carbon::now()->startOfMonth(); 
        $endOfMonth = Carbon::now()->addMonths(2)->endOfMonth(); 
        $now = Carbon::now();
        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

        Carbon::setLocale('ja');
        foreach($period as $date){
            $month[] = [
                'date' => $date->format('Y-m-d'),
                'day_of_week_iso' => $date->dayOfWeekIso,
                'day_of_week' => $date->translatedFormat('D'),
                'year' => $date->format('Y'),
                'month' => $date->translatedFormat('M')
            ];
            $shiftDates[] = [
                $date->format('j') => $date->translatedFormat('D')
            ];
        }

        // defaultShiftテーブルのデータを取得
        $defaultShifts = DefaultShift::select('id', 'employee_id', 'clock_in', 'clock_out', 'day_of_week')->get();

        foreach($defaultShifts as $defaultShift){
            foreach($month as $day){
                if($day['day_of_week_iso'] == $defaultShift->day_of_week){
                    // 実シフトデータを作成して保存
                    ActualShift::create([
                        'date' => $day['date'],
                        'clock_in' => $defaultShift->clock_in,
                        'clock_out' => $defaultShift->clock_out,
                        'day_of_week' => $day['day_of_week_iso'],
                        'employee_id' => $defaultShift->employee_id,
                    ]);
                }
            }
        }
     }

    public function testShiftTable()
    {
        $user = User::first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/createShifts')
                ->assertSee('シフト表')
                ->assertSee('時間')
                ->assertSee('日数');
            });
    }
    
    public function testOpenCreateShiftModal()
    {
        $user = User::first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/createShifts')
                ->press('30')
                ->assertSee('シフト変更')
                ->assertSee('出勤時間')
                ->screenshot('createShiftModalPage');

            $browser->script([
                "document.querySelector('select[name=\"employee_name\"]').value = '小泉'",
                "document.querySelector('input[name=\"isDayOff\"][value=\"0\"]').click();",
                "document.getElementById('clock_in').value = '09:00';",
                "document.getElementById('clock_out').value = '17:00';"
            ]);

            $browser->press('保存')
                    ->press('変更をクリア')
                    ->screenshot('modal3');
        });
    }
    
    protected function tearDown(): void
    {
        DB::table('employees')->where('user_id', 11111)->delete();
        DB::table('users')->where('employee_number', 11111)->delete();
        // DB::table('actual_shifts')->delete();
        parent::tearDown();
    }
 
}
