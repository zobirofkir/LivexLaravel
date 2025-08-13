<?php

namespace App\Filament\Resources\EarningResource\Pages;

use App\Filament\Resources\EarningResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEarning extends EditRecord
{
    protected static string $resource = EarningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('viewUser')
                ->label('View User Profile')
                ->icon('heroicon-o-user')
                ->visible(fn () => class_exists('\App\Filament\Resources\UserResource')),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Delete Earning')
                ->modalDescription('Are you sure you want to delete this earning record? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete it'),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}