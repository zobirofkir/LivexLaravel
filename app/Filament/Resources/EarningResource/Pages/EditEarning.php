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
            Actions\DeleteAction::make(),
            Actions\Action::make('viewUser')
                ->label('View User Profile')
                ->icon('heroicon-o-user')
                ->visible(fn () => class_exists('\App\Filament\Resources\UserResource')),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}