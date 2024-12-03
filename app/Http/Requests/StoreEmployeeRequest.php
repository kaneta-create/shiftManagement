<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|regex:/^[^\d]+$/',
            'employee_number' => 'required|integer|unique:users,employee_number',
            'role' => 'required|string|regex:/^[^\d]+$/',
            'authority' => 'required'
        ];
    }
}
