<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDefaultShiftRequest extends FormRequest
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
        $days = ['月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日', '日曜日'];

        $rules = [];

        foreach ($days as $day) {
            $rules["{$day}.start_time"] = ['nullable', 'date', 'date_format:H:i'];
            $rules["{$day}.end_time"] = ['nullable', 'date', 'date_format:H:i', "after:{$day}.start_time"];
        }

        return [
            'employee_number' => ['required', 'numeric',Rule::exists('users', 'employee_number'),],
            $rules
        ];
        
    }
}
