<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfferTimerResource\Pages;
use App\Models\Offer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class OfferTimerResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationGroup = 'Offers';

    protected static ?string $navigationLabel = 'Offers Timer';

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('activation_type', 'timed')
            ->whereNotNull('valid_until')
            ->where('is_active', true)
            ->where('enabled', true)
            ->orderBy('valid_until');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Timer Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                            
                        Forms\Components\DateTimePicker::make('valid_until')
                            ->label('Expiration Time')
                            ->required()
                            ->seconds(false)
                            ->columnSpanFull(),
                            
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->required(),
                            
                        // Forms\Components\Toggle::make('enabled')
                        //     ->label('Enabled')
                        //     ->default(true)
                        //     ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Expires In')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        $expiresAt = \Carbon\Carbon::parse($state);
                        $now = now();
                        
                        if ($now->gt($expiresAt)) {
                            return '<span class="text-danger font-bold">Expired</span>';
                        }
                        
                        $diff = $now->diff($expiresAt);
                        
                        if ($diff->days > 0) {
                            return $diff->format('%d days, %h hours, %i minutes');
                        } elseif ($diff->h > 0) {
                            return $diff->format('%h hours, %i minutes');
                        } else {
                            return $diff->format('%i minutes, %s seconds');
                        }
                    })
                    ->html(),
                    
                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Expiration Date')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                    
                // Tables\Columns\IconColumn::make('enabled')
                //     ->boolean()
                //     ->label('Enabled'),
                    
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('price_sale')
                    ->money('USD')
                    ->sortable()
                    ->label('Sale Price'),
            ])
            ->filters([
                Tables\Filters\Filter::make('active_timers')
                    ->label('Active Timers')
                    ->query(fn (Builder $query) => $query->where('valid_until', '>=', now()))
                    ->toggle(),
                    
                Tables\Filters\Filter::make('expired_timers')
                    ->label('Expired Timers')
                    ->query(fn (Builder $query) => $query->where('valid_until', '<', now()))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('extend')
                    ->label('Extend Timer')
                    ->icon('heroicon-o-clock')
                    ->form([
                        Forms\Components\DateTimePicker::make('extend_until')
                            ->label('Extend Until')
                            ->required()
                            ->seconds(false)
                            ->minDate(fn (Offer $record) => $record->valid_until),
                    ])
                    ->action(function (Offer $record, array $data) {
                        $record->update([
                            'valid_until' => $data['extend_until'],
                            'force_refresh_at' => now(),
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Timer Extended')
                            ->body("The timer for '{$record->title}' has been extended.")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('extend_bulk')
                        ->label('Extend Selected Timers')
                        ->icon('heroicon-o-clock')
                        ->form([
                            Forms\Components\DateTimePicker::make('extend_until')
                                ->label('Extend Until')
                                ->required()
                                ->seconds(false)
                                ->minDate(now()),
                        ])
                        ->action(function ($records, array $data) {
                            $count = 0;
                            foreach ($records as $record) {
                                $record->update([
                                    'valid_until' => $data['extend_until'],
                                    'force_refresh_at' => now(),
                                ]);
                                $count++;
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Timers Extended')
                                ->body("{$count} offer timers have been extended.")
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOfferTimers::route('/'),
            'create' => Pages\CreateOfferTimer::route('/create'),
            'edit' => Pages\EditOfferTimer::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            OfferTimerResource\Widgets\OfferTimerStats::class,
        ];
    }
}
