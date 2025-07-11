<?php

namespace App\Filament\Widgets;

use App\Models\LiveStream;
use Filament\Widgets\ChartWidget;

class StreamingActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Activité de Diffusion (7 derniers jours)';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(function ($day) {
            $date = now()->subDays($day);
            return [
                'date' => $date->format('d/m'),
                'count' => LiveStream::whereDate('created_at', $date)->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Diffusions créées',
                    'data' => $days->pluck('count'),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $days->pluck('date'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}