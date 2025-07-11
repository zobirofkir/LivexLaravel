<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\LiveStream;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Utilisateurs', User::count())
                ->description('Utilisateurs inscrits')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            
            Stat::make('Total Diffusions', LiveStream::count())
                ->description('Toutes les diffusions')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color('info'),
            
            Stat::make('En Direct', LiveStream::where('is_live', true)->count())
                ->description('Diffusions actives')
                ->descriptionIcon('heroicon-m-signal')
                ->color('danger'),
        ];
    }
}
