<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePacientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes','required', 'string', 'max:255'],
            'lastname' => ['sometimes', 'required', 'string', 'max:255'],
            'dui' => ['sometimes', 'required', 'string', 'regex:/^\d{8}-\d$/', 'unique:pacients,dui'],
            'phone_number' => ['sometimes', 'required', 'string', 'regex:/^\d{4}-\d{4}$/', 'unique:pacients,phone_number'],
            'gender' => ['sometimes', 'required', 'string', 'in: male, female']
        ];
    }
}
