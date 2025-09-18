<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Livewire\Attributes\Computed;

class NextPublishCountdown extends BaseWidget
{
    protected static ?string $pollingInterval = '1s';

    protected function getStats(): array
    {
        $moroccoTime = Carbon::now('Africa/Casablanca');
        $nextPublish = $moroccoTime->copy()->setTime(14, 0, 0);
        
        if ($moroccoTime->hour >= 14) {
            $nextPublish->addDay();
        }
        
        $diff = $moroccoTime->diff($nextPublish);
        
        $countdown = sprintf(
            '%02d:%02d:%02d',
            $diff->h + ($diff->days * 24),
            $diff->i,
            $diff->s
        );
        
        return [
            Stat::make('Next Auto-Publish', $countdown)
                ->description('Time until 2:00 PM')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'text-center',
                    'style' => 'font-family: monospace; font-size: 2rem; font-weight: bold;'
                ]),
        ];
    }
}