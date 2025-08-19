<?php

namespace App\Filament\Resources\OfferTimerResource\Pages;

use App\Filament\Resources\OfferTimerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOfferTimer extends EditRecord
{
    protected static string $resource = OfferTimerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
