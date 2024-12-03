<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\employee;

class DayShiftTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_dayShift_index_ng()
    {
        $response = $this->get('/dayShifts');
        $response->assertStatus(302);
    }

    public function test_dayShift_index_ok()
    {
        // 各ユーザーの権限と対応するロールを設定
        $roles = [
            ['authority' => 1, 'role' => 'スタッフ'],
            ['authority' => 2, 'role' => 'マネージャー'],
            ['authority' => 3, 'role' => '管理者'],
        ];
    
        foreach ($roles as $index => $roleData) {
            // Userテーブルにユーザーを作成
            $user = User::create([
                'name' => 'User ' . ($index + 1),
                'email' => 'user' . ($index + 1) . '@example.com',  // インデックスを調整
                'password' => bcrypt('password'), // パスワードのハッシュ化
                'employee_number' => 1111 + $index
            ]);
    
            // Employeeテーブルに関連するEmployeeレコードを作成
            $employee = Employee::create([
                'user_id' => $user->id,
                'role' => $roleData['role'],       // roleを設定
                'authority' => $roleData['authority'],  // authorityを設定
            ]);
    
            // ログイン後、'/dayShifts' にアクセス
            $response = $this->actingAs($user)->get('/dayShifts');
    
            // ステータスコードが200であることを確認
            $response->assertStatus(200);
        }
    }
    
}
