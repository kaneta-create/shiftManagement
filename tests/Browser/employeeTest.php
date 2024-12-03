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

class employeeTest extends DuskTestCase
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
     
    public function testEmployeeIndex()
    {
        $user = User::where('employee_number', 11111)->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)  // 事前にログイン
                ->visit('/admins')
                ->screenshot('test_employee_index2')
                ->assertSee('社員番号')
                ->assertSee('名前')
                ->assertSee('役職')
                ->assertSee('従業員一覧')
                ->assertSee('パスワード');
        });
        $this->assertTrue(true);
    }

    public function testEmployeeCreateView()
    {
        $user = User::where('employee_number', 11111)->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('admins.create')
                ->screenshot('admins_create')
                ->assertSee('社員番号')
                ->assertSee('名前')
                ->assertSee('雇用形態')
                ->assertSee('権限')
                ->assertSee('従業員登録');
        });
        $this->assertTrue(true);
    }

    public function testEmployeeCreateForm()
    {
        $user = User::where('employee_number', 11111)->first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('admins.create')
                ->type('name', 'test')
                ->type('employee_number', 22222)
                ->type('role', 'バイト')
                ->select('authority', 1)
                ->click('@generate-temporary-password')
                ->pause(1000)
                ->assertVisible('@temporary-password-input')
                ->click('@register-button')
                ->pause(1000)
                ->assertPathIs('/admins')
                ->assertSee('登録しました');
        });
    }

    public function testChangeEditView()
    {
        // テスト用ユーザーを取得
        $user = User::where('employee_number', 11111)->first();

        // 特定の defaultShift ID を取得
        $defaultShift = DefaultShift::first(); // 適切な defaultShift レコードを取得
        
        $this->browse(function (Browser $browser) use ($user, $defaultShift) {
            $browser->loginAs($user)
                ->visitRoute('admins.index')
                ->click('@edit-page-button')
                ->pause(1000)
                ->assertPathIs('/defaultShifts/' . $defaultShift->id . '/edit') // 動的に URL を検証
                ->assertSee('勤務時間変更');
        });
    }

    protected function tearDown(): void
    {
        // テストで作成したユーザーと従業員データの削除
        DB::table('employees')->where('user_id', 11111)->delete();
        DB::table('users')->where('employee_number', 11111)->delete();

        parent::tearDown();
    }


}


