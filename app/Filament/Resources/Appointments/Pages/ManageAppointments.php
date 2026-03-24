<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Carbon\Carbon;
use Filament\Actions\EditAction;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Validation\ValidationException;

class ManageAppointments extends ManageRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
        CreateAction::make()
            ->mutateFormDataUsing(function (array $data): array {
            $this->validateAppointment($data);
            return $data;
            }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make()
                ->mutateFormDataUsing(function (array $data, Appointment $record): array {
                    $this->validateUpdateAppointment($data, $record);
                    return $this->setEndAt($data);
                }),
        ];
    }

    private function validateAppointment(array $data): void
    {
        $startAt = Carbon::parse($data['start_at']);
        $endAt = $startAt->copy()->addMinutes(30);
        $userId = $data['user_id'];
        $pacientId = $data['pacient_id'];

        $available = Schedule::where('user_id', $userId)
            ->where('day_of_week', $startAt->dayOfWeek)
            ->whereTime('start_at', '<=', $startAt->format('H:i:s'))
            ->whereTime('end_at', '>=', $endAt->format('H:i:s'))
            ->exists();

        if ($available) {
            $conflict = Appointment::where('user_id', $userId)
                ->where('start_at', '<', $endAt)
                ->where('end_at', '>', $startAt)
                ->exists();
        } else {
            $conflict = true;
        }

        if ($conflict) {
            throw ValidationException::withMessages([
                'start_at' => 'The doctor does not have available schedules at this time or already has an appointment.',
            ]);
        }
    }

    private function validateUpdateAppointment(array $data, Appointment $appointment): void
    {
        $startAt = Carbon::parse($data['start_at'] ?? $appointment->start_at);
        $userId = $data['user_id'] ?? $appointment->user_id;

        $endAt = $startAt->copy()->addMinutes(30);

        $available = Schedule::where('user_id', $userId)
            ->where('day_of_week', $startAt->dayOfWeek)
            ->whereTime('start_at', '<=', $startAt->format('H:i:s'))
            ->whereTime('end_at', '>=', $endAt->format('H:i:s'))
            ->exists();

        if ($available) {
            $conflict = Appointment::where('user_id', $userId)
                ->where('id', '!=', $appointment->id)
                ->where('start_at', '<', $endAt)
                ->where('end_at', '>', $startAt)
                ->exists();
        } else {
            $conflict = true;
        }

        if ($conflict) {
            throw ValidationException::withMessages([
                'start_at' => 'The doctor does not have available schedules at this time or already has an appointment.',
            ]);
        }
    }
}
