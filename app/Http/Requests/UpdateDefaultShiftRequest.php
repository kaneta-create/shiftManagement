<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDefaultShiftRequest extends FormRequest
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
        $dayOfWeekNames = ['月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日', '日曜日'];

        $rules = [
            'employeeNumber' => ['required', 'numeric'],
        ];

        foreach ($dayOfWeekNames as $dayOfWeek) {
            $rules["{$dayOfWeek}.start_time"] = ['nullable', 'date_format:H:i'];
            $rules["{$dayOfWeek}.end_time"] = ['nullable', 'date_format:H:i', "after:{$dayOfWeek}.start_time"];
            $rules["{$dayOfWeek}.dayOfNameNumber"] = ['required_with:' . "{$dayOfWeek}.start_time,{$dayOfWeek}.end_time", 'numeric'];
        }

        return $rules;
    }

}
