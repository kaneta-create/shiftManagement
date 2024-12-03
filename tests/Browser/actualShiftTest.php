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

class actualShiftTest extends DuskTestCase
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

    public function testRequestShiftPage(): void
    {
        $user = User::first();
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
                ->visit('/actualShifts')
                ->assertSee('名前')
                ->assertSee('時間')
                ->assertSee('日数')
                ->assertSee('月');
        });
    }

    public function testOpenModal()
    {
        $user = User::first();
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
                ->visit('/actualShifts')
                // ->waitFor('@open-shift-request-modal-15', 2)
                ->press('15')
                ->pause(1000)
                ->screenshot('shift-request-modal')
                ->assertSee('シフト変更')
                ->press('閉じる')
                ->assertSee('シフト表');
        });
    }

 
    protected function tearDown(): void
    {
        // テストで作成したユーザーと従業員データの削除
        DB::table('employees')->where('user_id', 11111)->delete();
        DB::table('users')->where('employee_number', 11111)->delete();
        // DB::table('actual_shifts')->delete();
        parent::tearDown();
    }
}
