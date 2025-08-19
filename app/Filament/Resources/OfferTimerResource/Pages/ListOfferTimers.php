<?php

namespace App\Filament\Resources\OfferTimerResource\Pages;

use App\Filament\Resources\OfferTimerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOfferTimers extends ListRecords
{
    protected static string $resource = OfferTimerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            OfferTimerResource\Widgets\OfferTimerStats::class,
        ];
    }
}
