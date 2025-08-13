<?php

namespace App\Filament\Resources\VideoResource\Pages;

use App\Filament\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideo extends EditRecord
{
    protected static string $resource = VideoResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Delete Video')
                ->modalDescription('Are you sure you want to delete this video? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete it'),
        ];
    }
}
