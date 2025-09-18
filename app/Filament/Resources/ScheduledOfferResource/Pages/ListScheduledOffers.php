<?php

namespace App\Filament\Resources\ScheduledOfferResource\Pages;

use App\Filament\Resources\ScheduledOfferResource;
use Filament\Resources\Pages\ListRecords;

class ListScheduledOffers extends ListRecords
{
    protected static string $resource = ScheduledOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}