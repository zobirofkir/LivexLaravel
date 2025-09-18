<?php

namespace App\Filament\Resources\ScheduledOfferResource\Pages;

use App\Filament\Resources\ScheduledOfferResource;
use Filament\Resources\Pages\ListRecords;

class ListScheduledOffers extends ListRecords
{
    protected static string $resource = ScheduledOfferResource::class;
    
    protected ?string $pollingInterval = '30s';

    protected function getHeaderActions(): array
    {
        return [];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            ScheduledOfferResource\Widgets\ScheduledOfferStats::class,
        ];
    }
}