<?php

namespace App\Filament\Resources\OfferResource\Pages;

use App\Filament\Resources\OfferResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOffer extends EditRecord
{
    protected static string $resource = OfferResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Delete Offer')
                ->modalDescription('Are you sure you want to delete this offer? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete it'),
        ];
    }
}
