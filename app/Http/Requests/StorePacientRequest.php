<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePacientRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'dui' => ['required', 'string', 'regex:/^\d{8}-\d$/', 'unique:pacients,dui'],
            'phone_number' => ['required', 'string', 'regex:/^\d{4}-\d{4}$/', 'unique:pacients,phone_number'],
            'gender' => ['required', 'string', 'in: male, female']
        ];
    }
}
