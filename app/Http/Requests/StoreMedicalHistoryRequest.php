<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalHistoryRequest extends FormRequest
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
            'pacient_id' => ['required', 'integer', 'exists:pacients,id'],
            'weight' => ['required', 'numeric', 'gt:0'],
            'height' => ['required', 'numeric', 'gt:0'],
            'chronic_diseases' => ['required', 'string'],
            'allergies' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'medications' => ['required', 'string']
        ];
    }
}
