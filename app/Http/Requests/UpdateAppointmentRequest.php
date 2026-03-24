<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateAppointmentRequest extends FormRequest
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
            'start_at' => ['sometimes', 'required', 'date_format:Y-m-d H:i:s'],
            'user_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'pacient_id' => ['sometimes', 'required', 'integer', 'exists:pacients,id'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->has('start_at') || $validator->errors()->has('user_id')) {
                    return;
                }

                $appointmentParam = $this->route('appointment');
                $appointmentId = $appointmentParam instanceof Appointment ? $appointmentParam->id : $appointmentParam;
                $appointment = $appointmentParam instanceof Appointment ? $appointmentParam : Appointment::find($appointmentId);

                if (!$appointment) return;

                $startAtInput = $this->input('start_at', $appointment->start_at);
                $userId = $this->input('user_id', $appointment->user_id);

                $startAt = \Carbon\Carbon::parse($startAtInput);
                $endAt = $startAt->copy()->addMinutes(30);

                $available = Schedule::where('user_id', $userId)
                    ->where('day_of_week', $startAt->dayOfWeek)
                    ->whereTime('start_at', '<=', $startAt->format('H:i:s'))
                    ->whereTime('end_at', '>=', $endAt->format('H:i:s'))
                    ->exists();

                if ($available) {
                    $conflict = Appointment::where('user_id', $userId)
                        ->where('id', '!=', $appointmentId)
                        ->where('start_at', '<', $endAt)
                        ->where('end_at', '>', $startAt)
                        ->exists();
                } else {
                    $conflict = true;
                }
                
                if ($conflict) {
                    $validator->errors()->add('start_at', 'The doctor does not have available schedules at this time or already has an appointment.');
                }
            }
        ];
    }
}
