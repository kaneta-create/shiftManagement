<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    use DatabaseMigrations;
    public function setUp():void
    {
        Parent::setUp();
        User::factory()->create([
            'employee_number' => 11111,
            'password' => null,
            'temporary_password' => '12345'
        ]);


    }
    
    public function testLoginPageOk(): void
    {
        $user = User::first();
        // dd($user);
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('社員番号')
                    ->assertSee('パスワード');
        });
    }
    //ログインボタンがなぜか押せない
    // public function testLoginSuccess()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/login')
    //                 ->type('employee_number', 11111)
    //                 ->type('password', 'password')
    //                 ->waitFor('#register-button')
    //         $browser->assertVisible('#register-button');
    //         $browser->screenshot('login-next-page');
    //         $browser->assertSee('従業員名');
    //     });
    // }

    public function testFirstLoginAction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->script("document.querySelector('[dusk=\"initial-login-link\"]').click();");
            $browser->screenshot('initial-login');
            $browser->assertSee('パスワードを設定');
        });
    }

    public function testSerFirstPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->script("document.querySelector('[dusk=\"initial-login-link\"]').click();");
            $browser->screenshot('first')
                    ->type('employee_number', 11111)
                    ->type('temporary_password', 12345)
                    ->type('password', 'password')
                    ->type('confirm_password', 'password');
                    // 
            $browser->script("document.getElementById('register-button').click();");
            $browser->screenshot('success-click-button')
                    ->waitForDialog()
                    ->assertDialogOpened('登録が完了しました。ログインしますか？')
                    ->acceptDialog(); 
        });
    }

    
    protected function tearDown(): void
    {
        DB::table('employees')->where('user_id', 11111)->delete();
        // DB::table('actual_shifts')->delete();
        parent::tearDown();
    }

}
