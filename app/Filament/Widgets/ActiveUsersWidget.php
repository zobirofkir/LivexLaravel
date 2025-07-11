<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActiveUsersWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Utilisateurs Actifs', User::where('is_live', true)->count())
                ->description('Diffuseurs en ligne')
                ->descriptionIcon('heroicon-m-user-circle')
                ->color('warning'),
            
            Stat::make('Nouveaux Aujourd\'hui', User::whereDate('created_at', today())->count())
                ->description('Inscriptions du jour')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),
            
            Stat::make('Total Cette Semaine', User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count())
                ->description('Inscriptions hebdomadaires')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),
        ];
    }
}