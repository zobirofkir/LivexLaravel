<?php

namespace App\Filament\Resources\EarningResource\Widgets;

use App\Models\Earning;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EarningStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEarnings = Earning::sum('amount');
        $todayEarnings = Earning::whereDate('created_at', today())->sum('amount');
        $monthEarnings = Earning::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        
        $topEarner = User::withSum('earnings as total_earnings', 'amount')
            ->orderByDesc('total_earnings')
            ->first();
            
        return [
            Stat::make('Total Earnings', number_format($totalEarnings, 2))
                ->description('All time earnings')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
                
            Stat::make("Today's Earnings", number_format($todayEarnings, 2))
                ->description('Earnings today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
                
            Stat::make('This Month', number_format($monthEarnings, 2))
                ->description('Earnings this month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),
                
            Stat::make('Top Earner', $topEarner ? $topEarner->name : 'N/A')
                ->description($topEarner ? number_format($topEarner->total_earnings, 2) : 'No earnings yet')
                ->descriptionIcon('heroicon-m-user')
                ->color('primary'),
        ];
    }
}
