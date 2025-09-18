<?php

namespace App\Filament\Resources\ScheduledOfferResource\Pages;

use App\Filament\Resources\ScheduledOfferResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScheduledOffer extends EditRecord
{
    protected static string $resource = ScheduledOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
