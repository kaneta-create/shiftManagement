<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\employee;
use App\Models\DefaultShift;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class defaultShiftsTest extends DuskTestCase
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
     }

    public function testDefaultShiftUpdatePage()
    {
        $user = User::where('employee_number', 11111)->first();
        $defaultShift = defaultShift::first();
        $this->browse(function (Browser $browser) use ($user, $defaultShift) {
            $browser->loginAs($user)
                    ->visitRoute('defaultShifts.edit', ['defaultShift' => $defaultShift->id])
                    ->screenshot('defaultShift-edit-page')
                    ->assertSee('社員番号')
                    ->assertSee('曜日')
                    ->assertSee('出勤時間')
                    ->assertSee('退勤時間')
                    ->assertSee('削除');
        });
    }

    public function testRegisterDefaultShiftNg()
    {
        $user = User::first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/defaultShifts/create')
                    ->assertSee('シフト登録')
                    ->assertSee('社員番号')
                    ->type('employee_number', 00000)
                    ->scrollTo('#register-button')
                    ->screenshot('createDefaultShiftsPage')
                    ->press('登録')
                    ->waitForText('正しくありません')
                    ->assertSee('正しくありません');
        });
    }

    public function testRegisterDefaultShiftsOK()
    {
        $user = User::first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/defaultShifts/create')
                    ->type('employee_number', 11111)
                    ->select('#clock_in', '09:00')
                    ->select('#clock_out', '17:00')
                    ->scrollTo('#register-button') // 確実にボタンが画面に表示されるようにスクロール
                    ->screenshot('registerShifts');

            $browser->script("document.getElementById('register-button').click();");
            $browser->waitForLocation('/admins')
                    ->assertSee('登録済みです');
        });
        
    }

    protected function tearDown(): void
    {
        DB::table('employees')->where('user_id', 11111)->delete();
        DB::table('users')->where('employee_number', 11111)->delete();
 
        parent::tearDown();
    }
}
