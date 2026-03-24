<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $pacientId = $this->route('pacient')?->id ?? $this->route('pacient');

        return [
            'name' => ['sometimes','required', 'string', 'max:255'],
            'lastname' => ['sometimes', 'required', 'string', 'max:255'],
            'dui' => ['sometimes', 'required', 'string', 'regex:/^\d{8}-\d$/', Rule::unique('pacients', 'dui')->ignore($pacientId)],
            'phone_number' => ['sometimes', 'required', 'string', 'regex:/^\d{4}-\d{4}$/', Rule::unique('pacients', 'phone_number')->ignore($pacientId)],
            'gender' => ['sometimes', 'required', 'string', 'in:male,female']
        ];
    }
}
