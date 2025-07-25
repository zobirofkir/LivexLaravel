<?php

namespace App\Filament\Resources\EarningResource\Pages;

use App\Filament\Resources\EarningResource;
use App\Services\Facades\EarningFacade;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEarning extends CreateRecord
{
    protected static string $resource = EarningResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function handleRecordCreation(array $data): Model
    {
        // Use the EarningService through the facade to create the earning
        $user = \App\Models\User::find($data['user_id']);
        
        return EarningFacade::addEarning(
            $user,
            $data['amount'],
            $data['source']
        );
    }
    
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Earning added successfully';
    }
}