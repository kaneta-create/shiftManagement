<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\employee;
use App\Models\DefaultShift;

use function GuzzleHttp\default_ca_bundle;

class DefaulShiftTest extends TestCase
{
    use RefreshDatabase;
    // use RefreshDatabase;
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

    public function test_defaultShift_create_ng()
    {
        $response = $this->get('/defaultShifts');
        $response->assertStatus(302);
    }

    public function test_defaultShift_create_ok()
    {
        // ユーザーを2人作成
        $users = User::factory()->count(2)->create();
    
        // 各ユーザーに対応するEmployeeレコードを作成
        $employees = [];
        foreach ($users as $index => $user) {
            $employees[] = Employee::create([
                'user_id' => $user->id,           // 動的にユーザーIDを設定
                'role' => '社員',
                'authority' => $index === 0 ? 3 : 2  // 1人目はauthority 3、2人目はauthority 2
            ]);
        }
    
        // 各Employeeに対してログイン処理とステータスチェック
        foreach ($employees as $employee) {
            $loginUser = $employee->user;
            $response = $this->actingAs($loginUser)->get('/defaultShifts');
            $response->assertStatus(200);
        }
    }
    
    public function test_defaultShift_edit_ng()
    {
        $response = $this->get('/defaultShifts');
        $response->assertStatus(302);
    }

    public function test_defaultShift_edit_ok()
    {
        // ユーザーを2人作成
        $users = User::factory()->count(2)->create();
        $employees = [];
        foreach ($users as $index => $user) {
            $employees[] = Employee::create([
                'user_id' => $user->id,           // 動的にユーザーIDを設定
                'role' => '社員',
                'authority' => $index === 0 ? 3 : 2  // 1人目はauthority 3、2人目はauthority 2
            ]);
        }
    
        foreach ($employees as $employee) {
            $loginUser = $employee->user;
            $response = $this->actingAs($loginUser)->get('/defaultShifts');
            $response->assertStatus(200);
        }
    }

    public function test_defaultShift_store_ok()
    {
        $users = User::factory()->count(2)->create();
        $employees = [];
        foreach ($users as $index => $user) {
            $employees[] = Employee::create([
                'user_id' => $user->id,
                'role' => '社員',
                'authority' => $index === 0 ? 3 : 2
            ]);
        }

        $defaultShifts = [];
        foreach ($employees as $employee) {
            $defaultShifts[] = defaultShift::create([
                'employee_id' => $employee->id,
                'clock_in' => '09:00:00',
                'clock_out' => '17:00:00',
                'day_of_week' => 1
            ]);

            $defaultShifts[] = defaultShift::create([
                'employee_id' => $employee->id,
                'clock_in' => '09:00:00',
                'clock_out' => '17:00:00',
                'day_of_week' => 2
            ]);
        }

        foreach ($defaultShifts as $defaultShift) {
            // PUTリクエストを送信し、リダイレクト先も確認
            $response = $this->followingRedirects()
                            ->put('/defaultShifts/store', $defaultShift->toArray());

            // 成功したことを確認
            $response->assertStatus(200);  // リダイレクト後のページのステータスが200か確認

            // セッションに期待するメッセージがあるか確認
            // $response->assertSessionHas('message', '登録しました');
            // $response->assertSessionHas('status', 'success');

            // タイムスタンプを除外してデータベースのレコードを確認
            $this->assertDatabaseHas('default_shifts', [
                'employee_id' => $defaultShift->employee_id,
                'clock_in' => $defaultShift->clock_in,
                'clock_out' => $defaultShift->clock_out,
                'day_of_week' => $defaultShift->day_of_week,
            ]);
        }
    }

    public function test_updates_or_creates_default_shift_ok()
    {
        // テスト用の従業員とシフトデータを作成
        $user = User::factory()->create();
        // dd($user);
        // リクエストデータ
        $requestData = [
            'employeeNumber' => $user->employee_number,
            '月曜日' => ['start_time' => '09:00', 'end_time' => '17:00', 'dayOfNameNumber' => 1],
            '火曜日' => ['start_time' => null, 'end_time' => null, 'dayOfNameNumber' => 2],
        ];
        $employee = employee::create([
            'user_id' => $user->id,
            'role' => '社員',
            'authority' => 3
        ]);
        DefaultShift::create([
            'employee_id' => $employee->id,
            'clock_in' => '08:00',
            'clock_out' => '16:00',
            'day_of_week' => 1
        ]);

        // シフトの更新処理を呼び出し
        $response = $this->post(route('defaultShifts.update', ['defaultShift' => $employee->id]), $requestData);

        // シフトが正しく作成・更新されたかを確認
        $this->assertDatabaseHas('default_shifts', [
            'employee_id' => $employee->id,
            'day_of_week' => 1,
            'clock_in' => '08:00:00',
            'clock_out' => '16:00:00',
        ]);

        // 火曜日のシフトが削除されたかを確認
        $this->assertDatabaseMissing('default_shifts', [
            'employee_id' => $user->id,
            'day_of_week' => 2,
        ]);

    }


    public function test_defaultShift_store_ng()
    {
        // *選択された社員番号は正しくありません。
        $users = User::factory()->count(2)->create();

        $employees = [];
        foreach ($users as $index => $user) {
            $employees[] = Employee::create([
                'user_id' => $user->id,
                'role' => '社員',
                'authority' => $index === 0 ? 3 : 2
            ]);
        

        // $defaultShifts = [];
        // foreach ($employees as $employee) {
        //     $defaultShifts[] = defaultShift::create([
        //         'employee_id' => $employee->id,
        //         'clock_in' => '09:00:00',
        //         'clock_out' => '17:00:00',
        //         'day_of_week' => 1
        //     ]);

        //     $defaultShifts[] = defaultShift::create([
        //         'employee_id' => $employee->id,
        //         'clock_in' => '09:00:00',
        //         'clock_out' => '17:00:00',
        //         'day_of_week' => 2
        //     ]);
        // }

        $params = [
            'employee_number' => 123,
            'clock_in' => '09:00:00',
            'clock_out' => '17:00:00',
            'day_of_week' => 2
        ];
            // PUTリクエストを送信し、リダイレクト先も確認
            $response = $this->actingAs($user)->put('/defaultShifts/store', $params);
            $response->assertStatus(302);  
            // タイムスタンプを除外してデータベースのレコードを確認
         
            $response->assertSessionHasErrors(['employee_number'=>'選択された社員番号は正しくありません']);
        }
    }

    
    // デフォルトシフトの削除処理のテストケース
    // public function test_deletes_shift_when_times_are_null()
    // {
    //     // テスト用の従業員とシフトデータを作成
    //     $user = User::factory()->create();
    //     $this->actingAs($user);
    //     // 既存のシフトデータを作成
    //     $employee = employee::create([
    //         'user_id' => $user->id,
    //         'role' => '社員',
    //         'authority' => 3
    //     ]);

    //     $defaultShift = DefaultShift::create([
    //         'employee_id' => $employee->id,
    //         'day_of_week' => 1,
    //         'clock_in' => '09:00:00',
    //         'clock_out' => '17:00:00',
    //     ]);

    //     // リクエストデータで削除するシフト
    //     $requestData = [
    //         'employeeNumber' => $user->employee_number,
    //         '月曜日' => ['start_time' => null, 'end_time' => null, 'dayOfNameNumber' => 1],
    //         // 'start_time' => null, 'end_time' => null, 'dayOfNameNumber' => 1,
    //         '火曜日' => ['start_time' => '09:00:00', 'end_time' => '17:00:00', 'dayOfNameNumber' => 2],
    //         '水曜日' => ['start_time' => '09:00:00', 'end_time' => '17:00:00', 'dayOfNameNumber' => 3],
    //         '木曜日' => ['start_time' => '09:00:00', 'end_time' => '17:00:00', 'dayOfNameNumber' => 4],
    //         '金曜日' => ['start_time' => '09:00:00', 'end_time' => '17:00:00', 'dayOfNameNumber' => 5],
    //         '土曜日' => ['start_time' => '09:00:00', 'end_time' => '17:00:00', 'dayOfNameNumber' => 6],
    //         '日曜日' => ['start_time' => '09:00:00', 'end_time' => '17:00:00', 'dayOfNameNumber' => 7],
    //     ];

    //     // シフトの更新処理を呼び出し
    //     $response = $this->actingAs($user)->post(route('defaultShifts.update', ['defaultShift' => $defaultShift->employee_id]), $requestData);
    //     // dd($response);
    //     // シフトが削除されたことを確認
    //     $this->assertDatabaseMissing('default_shifts', [
    //         'employee_id' => 11,
    //         // 'day_of_week' => 1,
    //         // 'clock_in' => '09:00:00',
    //         // 'clock_out' => '17:00:00',
    //     ]);

    //     // 正常なレスポンスを期待
    //     // $response->assertSessionHas('success', 'シフトが正常に更新されました');
    // }

    public function test_employee_number_validation_error()
    {
        // テストユーザーの作成（存在しない社員番号にするため無効データで送る）
        $user = User::factory()->create();

        // 不正な社員番号データ
        $data = [
            'employee_number' => '999999', // 存在しない社員番号
            // 他の曜日のシフトデータも用意する必要がある場合は追加します
            '月曜日' => [
                'start_time' => '09:00',
                'end_time' => '17:00',
            ]
        ];

        // ログインしてリクエストを送信
        $response = $this->actingAs($user)->post(route('defaultShifts.store'), $data);
        $response->assertRedirect(); 
        // バリデーションエラーの確認（employee_numberが存在しない場合）
        $response->assertSessionHasErrors([
            'employee_number' => '選択された社員番号は正しくありません。',
        ]);
    }
}
