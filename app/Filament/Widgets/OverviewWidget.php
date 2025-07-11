<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\LiveStream;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            
            Stat::make('Total Live Streams', LiveStream::count())
                ->description('All live streams')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color('info'),
            
            Stat::make('Currently Live', LiveStream::where('is_live', true)->count())
                ->description('Active streams')
                ->descriptionIcon('heroicon-m-signal')
                ->color('danger'),
        ];
    }
}
