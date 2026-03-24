<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class CitasChart extends ChartWidget
{
    protected ?string $heading = 'Citas Chart';

    protected function getData(): array
    {
        $datos = collect(range(6, 0))->map(function ($daysAgo) {
        $fecha = Carbon::today()->subDays($daysAgo);
        return [
            'fecha' => $fecha->translatedFormat('D d/m'),
            'total'=> Appointment::whereDate('start_at', $fecha)->count(),
            ];
        });
        return [
            'datasets'=> [
                [
                    'label' => 'Citas',
                    'data' => $datos->pluck('total')->toArray(),
                    'borderColor'     => '#6366f1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'fill'            => true,
                ],
            ],
            'labels'=> $datos->pluck('fecha')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected static ?int $sort = 2;
    public function getColumnSpan(): array|int|string
    {
        return 'full';
    }
}
