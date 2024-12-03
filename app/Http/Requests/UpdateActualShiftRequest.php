<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActualShiftRequest extends FormRequest
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
            'shiftUpdates.*.employee_id' => 'required|numeric',
            'shiftUpdates.*.clock_in' => 'nullable|date_format:H:i',
            'shiftUpdates.*.clock_out' => 'nullable|date_format:H:i',
            'shiftUpdates.*.isDayOff' => 'required|in:0,1',
            'shiftUpdates.*.date' => 'required|date_format:Y-m-d',
        ];
    }
}
