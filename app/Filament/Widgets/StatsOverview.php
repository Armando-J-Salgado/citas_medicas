<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pacient;
use App\Models\Appointment;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("Total Pacientes", Pacient::count())
                ->description("Pacientes registrados")
                ->icon("heroicon-o-users")
                ->color('info'),
            Stat::make("Citas de hoy", Appointment::whereDate('start_at', today())->count())
                ->description('Citas programadas para hoy')
                ->icon('heroicon-o-calendar-days')
                ->color('success'),
            Stat::make('Citas del mes', Appointment::whereMonth('start_at', now()->month)->count())
                ->description('Citas en '.now()->translatedFormat('F'))
                ->icon('heroicon-o-chart-bar')
                ->color('warning'),
        ];
    }

    protected static ?int $sort = 1;
}
