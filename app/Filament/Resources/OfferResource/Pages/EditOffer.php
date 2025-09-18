<?php

namespace App\Filament\Resources\OfferResource\Pages;

use App\Filament\Resources\OfferResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

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

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Get the original data to compare changes
        $originalData = $record->toArray();
        
        // Remove fields that shouldn't be scheduled
        $excludeFromScheduling = ['pending_updates', 'scheduled_publish_at', 'has_pending_updates', 'force_refresh', 'created_at', 'updated_at'];
        $schedulableData = array_diff_key($data, array_flip($excludeFromScheduling));
        
        // Check if there are actual changes
        $hasChanges = false;
        foreach ($schedulableData as $key => $value) {
            if (isset($originalData[$key]) && $originalData[$key] != $value) {
                $hasChanges = true;
                break;
            }
        }
        
        if ($hasChanges) {
            // Schedule the update instead of applying immediately
            $record->scheduleUpdate($schedulableData);
            
            $moroccoTime = Carbon::now('Africa/Casablanca');
            $publishTime = $moroccoTime->copy()->setTime(14, 0, 0);
            
            if ($moroccoTime->hour >= 14) {
                $publishTime->addDay();
            }
            
            Notification::make()
                ->title('Update Scheduled')
                ->body("Your offer update has been scheduled to publish at {$publishTime->format('Y-m-d H:i')} (Morocco time).")
                ->success()
                ->send();
        } else {
            // No changes, just update normally
            $record->update($data);
        }
        
        return $record;
    }
}
