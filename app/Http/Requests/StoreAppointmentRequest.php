<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreAppointmentRequest extends FormRequest
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
            'start_at' => ['required', 'datetime_format:Y-m-d H:i', 'before:end_at'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'pacient_id' => ['required', 'integer', 'exists:pacients,id'],

        ];
    }
    public function after(): array
    {
        return [
            function (Validator $validator){
                if ($validator->errors()->has('start_at') || $validator->errors()->has('user_id')) {
                    return;
                }
                $startAt = $this->input('start_at');
                $endAt = $startAt->copy()->addMinutes(30);
                $userId = $this->input('user_id');
                $pacientId = $this->input('pacient_id');

                if($available = Schedule::where('user_id', $userId)
                    ->where('day_of_week', $startAt->dayOfWeek)
                    ->whereTime('start_at', '<=', $startAt->format('H:i'))
                    ->whereTime('end_at', '>=', $endAt->format('H:i'))
                    ->exists())
                {
                    $conflict = Appointment::where('user_id', $userId)
                        ->where(function ($query) use ($startAt, $endAt) {
                            $query->whereBetween('start_at', [$startAt, $endAt])
                                ->orWhereBetween('end_at', [$startAt, $endAt])
                                ->orWhere(function ($query) use ($startAt, $endAt) {
                                    $query->where('start_at', '<=', $startAt)
                                        ->where('end_at', '>=', $endAt);
                                }) -> exists();
                        })
                        ->exists();
                }
                else {
                    $conflict = true;
                }
                if ($conflict) {
                    $validator->errors()->add('start_at', 'The doctor already has an appointment at this time.');
                }
            }
        ];
    }
}
