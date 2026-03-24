<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'day_of_week' => ['sometimes', 'required', 'integer', 'between:0,6'],
            'start_at' => ['sometimes', 'required', 'date_format:H:i'],
            'end_at' => ['sometimes', 'required', 'date_format:H:i', 'after:start_at']
        ];
    }
}
