<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Appointment;

class AppointmentsCalendarWidget extends Widget
{
    protected string $view = 'filament.widgets.appointments-calendar-widget';
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $appointments = Appointment::with(['pacient', 'user'])
            ->get()
            ->map(function (Appointment $appointment) {
                return [
                    'id'    => $appointment->id,
                    'title' => $appointment->pacient->name . ' — ' . $appointment->user->name,
                    'start' => $appointment->start_at,
                    'end'   => $appointment->end_at,
                ];
            })
            ->toArray();

        return [
            'appointments' => $appointments,
        ];
    }
}
