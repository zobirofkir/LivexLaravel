<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduledOfferResource\Pages;
use App\Models\Offer;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ScheduledOfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    
    protected static ?string $navigationLabel = 'Scheduled Updates';
    
    protected static ?string $navigationGroup = 'Offers';
    
    protected static ?int $navigationSort = 4;
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('has_pending_updates', true)->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
    
    protected static ?string $pollingInterval = '30s';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('has_pending_updates', true);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->circular()
                    ->defaultImageUrl(fn () => asset('images/placeholder.jpg')),
                
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('posted_by')
                    ->label('Posted By')
                    ->formatStateUsing(function ($record) {
                        return $record->getPostedByName();
                    })
                    ->searchable(['posted_by', 'user.name']),
                
                TextColumn::make('scheduled_publish_at')
                    ->label('Scheduled For')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return $state ? $state->format('Y-m-d H:i') . ' (Morocco)' : 'Not scheduled';
                    }),
                
                TextColumn::make('countdown')
                    ->label('Time Remaining')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->scheduled_publish_at) return 'Not scheduled';
                        
                        $now = \Carbon\Carbon::now('Africa/Casablanca');
                        $scheduled = $record->scheduled_publish_at->setTimezone('Africa/Casablanca');
                        
                        if ($scheduled->isPast()) {
                            return '<span class="text-red-600 font-semibold">Overdue</span>';
                        }
                        
                        $diff = $now->diff($scheduled);
                        
                        if ($diff->days > 0) {
                            return $diff->days . 'd ' . $diff->h . 'h ' . $diff->i . 'm';
                        } elseif ($diff->h > 0) {
                            return $diff->h . 'h ' . $diff->i . 'm';
                        } else {
                            return $diff->i . 'm ' . $diff->s . 's';
                        }
                    })
                    ->html()
                    ->extraAttributes(['class' => 'font-mono'])
                    ->sortable(false),
                
                TextColumn::make('pending_updates')
                    ->label('Pending Changes')
                    ->formatStateUsing(function ($state) {
                        if (!$state || !is_array($state)) return 'No changes';
                        
                        $changes = [];
                        foreach ($state as $field => $value) {
                            $changes[] = ucfirst(str_replace('_', ' ', $field));
                        }
                        return implode(', ', array_slice($changes, 0, 3)) . (count($changes) > 3 ? '...' : '');
                    })
                    ->wrap(),
                
                TextColumn::make('created_at')
                    ->label('Original Created')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->defaultSort('scheduled_publish_at', 'asc')
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('view_changes')
                    ->label('View Changes')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalContent(function (Offer $record) {
                        if (!$record->pending_updates || !is_array($record->pending_updates)) {
                            return view('filament.modal.no-changes');
                        }
                        
                        $changes = [];
                        foreach ($record->pending_updates as $field => $newValue) {
                            $originalValue = $record->getOriginal($field) ?? $record->$field;
                            $changes[] = [
                                'field' => ucfirst(str_replace('_', ' ', $field)),
                                'original' => $originalValue,
                                'new' => $newValue
                            ];
                        }
                        
                        return view('filament.modal.pending-changes', compact('changes'));
                    })
                    ->modalHeading('Pending Changes')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                
                Tables\Actions\Action::make('publish_now')
                    ->label('Publish Now')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Offer $record) {
                        \App\Jobs\PublishScheduledOfferUpdate::dispatchSync($record);
                        
                        Notification::make()
                            ->title('Updates Published')
                            ->body('The pending updates have been published immediately.')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Publish Pending Updates')
                    ->modalDescription('This will immediately publish the pending updates instead of waiting for the scheduled time.')
                    ->modalSubmitActionLabel('Yes, Publish Now'),
                
                Tables\Actions\Action::make('cancel_schedule')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (Offer $record) {
                        $record->update([
                            'pending_updates' => null,
                            'scheduled_publish_at' => null,
                            'has_pending_updates' => false
                        ]);
                        
                        Notification::make()
                            ->title('Schedule Cancelled')
                            ->body('The scheduled updates have been cancelled.')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Scheduled Updates')
                    ->modalDescription('This will cancel the scheduled updates. The changes will be lost.')
                    ->modalSubmitActionLabel('Yes, Cancel'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('publish_all')
                        ->label('Publish All Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $count = 0;
                            foreach ($records as $record) {
                                \App\Jobs\PublishScheduledOfferUpdate::dispatchSync($record);
                                $count++;
                            }
                            
                            Notification::make()
                                ->title('Updates Published')
                                ->body("{$count} scheduled updates have been published.")
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation(),
                    
                    Tables\Actions\BulkAction::make('cancel_all')
                        ->label('Cancel All Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $count = 0;
                            foreach ($records as $record) {
                                $record->update([
                                    'pending_updates' => null,
                                    'scheduled_publish_at' => null,
                                    'has_pending_updates' => false
                                ]);
                                $count++;
                            }
                            
                            Notification::make()
                                ->title('Schedules Cancelled')
                                ->body("{$count} scheduled updates have been cancelled.")
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScheduledOffers::route('/'),
        ];
    }
    
    public static function getWidgets(): array
    {
        return [
            ScheduledOfferResource\Widgets\ScheduledOfferStats::class,
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }
}