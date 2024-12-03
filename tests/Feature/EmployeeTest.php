<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\employee;

class EmployeeTest extends TestCase
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

    public function test_employee_index_ng()
    {
        $response = $this->get('/admins');
        $response->assertStatus(302);
    }

    public function test_employee_index_ok()
    {
        $user = User::factory()->create();
        $employee = employee::create([
            'user_id' => $user->id,
            'role' => '社員',
            'authority' => 3
        ]);
        
        $loginUser = $employee->user;
        $response = $this->actingAs($loginUser)->get('/admins');
        $response->assertStatus(200);
    }

    public function test_employee_create_ng()
    {
        $response = $this->get('/admins/create');
        $response->assertStatus(302);
    }

    public function test_employee_create_ok()
    {
        $user = User::factory()->create();
        $employee = employee::create([
            'user_id' => $user->id,
            'role' => '社員',
            'authority' => 3
        ]);
        
        $loginUser = $employee->user;
        $response = $this->actingAs($loginUser)->get('/admins');
        $response->assertStatus(200);
    }

    public function test_employee_delete_ok()
    {
        $user = User::factory()->create();
         // 1. テスト用の従業員を作成
        $this->actingAs($user);
        $employee = employee::create([
            // 'id' => 1,
            'user_id' => $user->id,
            'role' => '社員',
            'authority' => 3
        ]);

        
         // 2. DELETEリクエストを送信
        $response = $this->delete(route('admins.destroy', $employee->id));
 
         // 3. データベースから削除されていることを確認
        $this->assertDatabaseMissing('employees', [
            'user_id' => $user->id,
            'role' => '社員',
            'authority' => 3
        ]);
 
         // 4. リダイレクトとフラッシュメッセージの確認
        $response->assertRedirect(route('admins.index'));
        $response->assertSessionHas([
             'message' => '削除しました。',
             'status' => 'success',
         ]);
    }
    
}
