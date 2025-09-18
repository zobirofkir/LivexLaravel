<?php

namespace App\Filament\Resources\ScheduledOfferResource\Widgets;

use App\Models\Offer;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ScheduledOfferStats extends BaseWidget
{
    protected static ?string $pollingInterval = '1s';
    
    protected function getStats(): array
    {
        $totalScheduled = Offer::where('has_pending_updates', true)->count();
        $overdueCount = Offer::where('has_pending_updates', true)
            ->where('scheduled_publish_at', '<', Carbon::now('Africa/Casablanca'))
            ->count();
        $todayScheduled = Offer::where('has_pending_updates', true)
            ->whereDate('scheduled_publish_at', Carbon::today('Africa/Casablanca'))
            ->count();
        
        $nextScheduled = Offer::where('has_pending_updates', true)
            ->where('scheduled_publish_at', '>', Carbon::now('Africa/Casablanca'))
            ->orderBy('scheduled_publish_at')
            ->first();
            
        return [
            Stat::make('Total Scheduled', $totalScheduled)
                ->description('Pending updates')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([3, 7, 2, 8, 5, 12, 9])
                ->color('primary'),
                
            Stat::make('Overdue', $overdueCount)
                ->description('Past scheduled time')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
                
            Stat::make('Today', $todayScheduled)
                ->description('Scheduled for today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),
                
            Stat::make('Next Update', $nextScheduled ? $nextScheduled->title : 'None')
                ->description($nextScheduled ? $this->getCountdown($nextScheduled->scheduled_publish_at) : 'No upcoming updates')
                ->descriptionIcon('heroicon-m-arrow-right')
                ->color('info'),
        ];
    }
    
    private function getCountdown($scheduledTime): string
    {
        $now = Carbon::now('Africa/Casablanca');
        $scheduled = $scheduledTime->setTimezone('Africa/Casablanca');
        
        if ($scheduled->isPast()) {
            return 'Overdue';
        }
        
        $diff = $now->diff($scheduled);
        
        if ($diff->days > 0) {
            return $diff->days . 'd ' . $diff->h . 'h ' . $diff->i . 'm';
        } elseif ($diff->h > 0) {
            return $diff->h . 'h ' . $diff->i . 'm';
        } else {
            return $diff->i . 'm ' . $diff->s . 's';
        }
    }
}