<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicalHistoryRequest extends FormRequest
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
            'pacient_id' => ['sometimes','required', 'integer', 'exists:pacients,id'],
            'weight' => ['sometimes', 'required', 'numeric', 'gt:0'],
            'height' => ['sometimes', 'required', 'numeric', 'gt:0'],
            'chronic_diseases' => ['sometimes', 'required', 'string'],
            'allergies' => ['sometimes', 'required', 'string'],
            'date_of_birth' => ['sometimes', 'required', 'date'],
            'medications' => ['sometimes', 'required', 'string']
        ];
    }
}
