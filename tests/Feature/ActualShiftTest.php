<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\employee;
use App\Models\ActualShift;

class ActualShiftTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function test_actualShift_index_ng()
    {
        $response = $this->get('/actualShifts');

        $response->assertStatus(302);
    }

    public function test_actualShift_index_ok()
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
                'email' => 'user' . $index . '@example.com',  // インデックスを調整
                'password' => bcrypt('password'), // パスワードのハッシュ化
                'employee_number' => 1111 + $index
            ]);
    
            // Employeeテーブルに関連するEmployeeレコードを作成
            $employee = Employee::create([
                'user_id' => $user->id,
                'role' => $roleData['role'],       // roleを設定
                'authority' => $roleData['authority'],  // authorityを設定
            ]);
    
            $actualShift = ActualShift::create([
                'employee_id' => $employee->id,
                'clock_in' => '09:00:00',
                'clock_out' => '17:00:00',
                'day_of_week' => $index + 1,
                'date' => '2024-12-13'
            ]);
            // ログイン後、'/dayShifts' にアクセス
            $response = $this->actingAs($user)->get('/actualShifts');
    
            // ステータスコードが200であることを確認
            $response->assertStatus(200);
        }
    }

    public function test_actualShift_update_ok()
    {
        
        // 権限とロールの設定
        $roles = [
            ['authority' => 1, 'role' => 'スタッフ'],
            ['authority' => 2, 'role' => 'マネージャー'],
            ['authority' => 3, 'role' => '管理者'],
        ];

        // 各ロールに対してテストを実行
        foreach ($roles as $index => $roleData) {
            // Userテーブルにユーザーを作成
            $user = User::create([
                'name' => 'User ' . ($index + 1),
                'email' => 'user' . $index . '@example.com',  // インデックスを調整
                'password' => bcrypt('password'), // パスワードのハッシュ化
                'employee_number' => 1111 + $index
            ]);

            // Employeeテーブルに関連するEmployeeレコードを作成
            $employee = Employee::create([
                'user_id' => $user->id,
                'role' => $roleData['role'],       // roleを設定
                'authority' => $roleData['authority'],  // authorityを設定
            ]);

            // ActualShiftを作成
            $actualShift = ActualShift::factory()->create([
                'employee_id' => $employee->id,
                'clock_in' => '09:00:00',
                'clock_out' => '17:00:00',
                'day_of_week' => $index + 1,
                'date' => '2024-12-13'
            ]);

            // 更新データの準備
            $shiftUpdates = [
                [
                    'employee_id' => $employee->id,
                    'date' => '2024-12-13',
                    'clock_in' => '08:00',
                    'clock_out' => '16:00',
                    'isDayOff' => 0
                ],
                [
                    'employee_id' => $employee->id,
                    'date' => '2024-12-14',
                    'clock_in' => '10:00',
                    'clock_out' => '18:00',
                    'isDayOff' => 1  // この場合は休暇日扱い
                ]
            ];

            // リクエストデータの準備
            $requestData = [
                'shiftUpdates' => $shiftUpdates
            ];

            // コントローラーの更新処理を呼び出し
            $response = $this->actingAs($user)->put(route('actualShifts.update', ['actualShift' => $actualShift->id]), $requestData);

            // レスポンスの確認
            $response->assertStatus(302);  // リダイレクトが成功しているか確認

            // データベースが正しく更新されたか確認
            $this->assertDatabaseHas('actual_shifts', [
                'employee_id' => $employee->id,
                'date' => '2024-12-13',
                'clock_in' => '08:00:00',
                'clock_out' => '16:00:00'
            ]);

            $this->assertDatabaseHas('actual_shifts', [
                'employee_id' => $employee->id,
                'date' => '2024-12-14',
                'clock_in' => '09:00:00',  // 休暇の場合は09:00
                'clock_out' => '09:00:00'  // 休暇の場合は09:00
            ]);
        }
    }

    public function test_actualShift_update_ng()
    {
        
        // 権限とロールの設定
        $roles = [
            ['authority' => 1, 'role' => 'スタッフ'],
            ['authority' => 2, 'role' => 'マネージャー'],
            ['authority' => 3, 'role' => '管理者'],
        ];

        // 各ロールに対してテストを実行
        foreach ($roles as $index => $roleData) {
            // Userテーブルにユーザーを作成
            $user = User::create([
                'name' => 'User ' . ($index + 1),
                'email' => 'user' . $index . '@example.com',  // インデックスを調整
                'password' => bcrypt('password'), // パスワードのハッシュ化
                'employee_number' => 1111 + $index
            ]);

            // Employeeテーブルに関連するEmployeeレコードを作成
            $employee = Employee::create([
                'user_id' => $user->id,
                'role' => $roleData['role'],       // roleを設定
                'authority' => $roleData['authority'],  // authorityを設定
            ]);

            // ActualShiftを作成
            $actualShift = ActualShift::factory()->create([
                'employee_id' => $employee->id,
                'clock_in' => '09:00',
                'clock_out' => '17:00:00',
                'day_of_week' => 1,
                'date' => '2024-12-08'
            ]);

            // 更新データの準備
            $shiftUpdates = [
                [
                    'employee_id' => $employee->id,
                    'date' => '2024-12-13',
                    'clock_in' => '08:00',
                    'clock_out' => '16:00',
                    'isDayOff' => 0
                ],
                [
                    'employee_id' => $employee->id,
                    'date' => '2024-12-14',
                    'clock_in' => '10:00',
                    'clock_out' => '18:00',
                    'isDayOff' => 1  // この場合は休暇日扱い
                ]
            ];

            // リクエストデータの準備
            $requestData = [
                'shiftUpdates' => $shiftUpdates
            ];

            // コントローラーの更新処理を呼び出し
            $response = $this->actingAs($user)->put(route('actualShifts.update', ['actualShift' => $actualShift->id]), $requestData);

            // レスポンスの確認
            $response->assertStatus(302);  // リダイレクトが成功しているか確認

            // データベースが正しく更新されたか確認
            $this->assertDatabaseHas('actual_shifts', [
                'employee_id' => $employee->id,
                'date' => '2024-12-13',
                'clock_in' => '08:00:00',
                'clock_out' => '16:00:00'
            ]);

            $this->assertDatabaseHas('actual_shifts', [
                'employee_id' => $employee->id,
                'date' => '2024-12-14',
                'clock_in' => '09:00:00',  // 休暇の場合は09:00
                'clock_out' => '09:00:00'  // 休暇の場合は09:00
            ]);
        }
    }

}