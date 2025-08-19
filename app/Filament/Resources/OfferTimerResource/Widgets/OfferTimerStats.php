<?php

namespace App\Filament\Resources\OfferTimerResource\Widgets;

use App\Models\Offer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class OfferTimerStats extends BaseWidget
{
    protected function getStats(): array
    {
        $now = now();
        
        $activeTimers = Offer::where('activation_type', 'timed')
            ->where('is_active', true)
            ->where('enabled', true)
            ->whereNotNull('valid_until')
            ->where('valid_until', '>=', $now)
            ->count();
            
        $expiredTimers = Offer::where('activation_type', 'timed')
            ->whereNotNull('valid_until')
            ->where('valid_until', '<', $now)
            ->count();
            
        $expiringToday = Offer::where('activation_type', 'timed')
            ->where('is_active', true)
            ->where('enabled', true)
            ->whereNotNull('valid_until')
            ->whereDate('valid_until', $now->toDateString())
            ->count();
            
        $expiringThisWeek = Offer::where('activation_type', 'timed')
            ->where('is_active', true)
            ->where('enabled', true)
            ->whereNotNull('valid_until')
            ->whereBetween('valid_until', [
                $now->startOfDay(),
                $now->copy()->addDays(7)->endOfDay(),
            ])
            ->count();

        return [
            Stat::make('Active Timers', $activeTimers)
                ->description('Currently active offer timers')
                ->descriptionIcon('heroicon-m-clock')
                ->color('success'),
                
            Stat::make('Expiring Today', $expiringToday)
                ->description('Offers expiring today')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning'),
                
            Stat::make('Expiring This Week', $expiringThisWeek)
                ->description('Offers expiring in the next 7 days')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
                
            Stat::make('Expired Timers', $expiredTimers)
                ->description('Offers with expired timers')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}