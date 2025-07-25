<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EarningResource\Pages;
use App\Models\Earning;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EarningResource extends Resource
{
    protected static ?string $model = Earning::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationLabel = 'User Earnings';
    
    protected static ?string $navigationGroup = 'Finance Management';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(function() {
                        // Filter out users with null names and provide a fallback for display
                        return User::all()->mapWithKeys(function ($user) {
                            $displayName = $user->name ?? "User #{$user->id}";
                            return [$user->id => $displayName];
                        })->toArray();
                    })
                    ->searchable()
                    ->required()
                    ->preload()
                    ->columnSpanFull(),
                    
                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->required()
                    ->numeric()
                    ->minValue(0.01)
                    ->prefix('$')
                    ->columnSpan(1),
                    
                Forms\Components\Select::make('source')
                    ->label('Source')
                    ->options([
                        'live_stream' => 'Live Stream',
                        'gift' => 'Gift',
                        'tip' => 'Tip',
                        'subscription' => 'Subscription',
                        'promotion' => 'Promotion',
                        'bonus' => 'Bonus',
                        'other' => 'Other',
                    ])
                    ->required()
                    ->columnSpan(1),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->placeholder('Add any additional information about this earning')
                    ->columnSpanFull(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => $state ?? "User #{$record->user_id}"),
                    
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('USD')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('source')
                    ->label('Source')
                    ->colors([
                        'primary' => 'other',
                        'success' => 'live_stream',
                        'warning' => 'gift',
                        'danger' => 'tip',
                        'info' => 'subscription',
                        'secondary' => 'promotion',
                        'tertiary' => 'bonus',
                    ]),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('source')
                    ->options([
                        'live_stream' => 'Live Stream',
                        'gift' => 'Gift',
                        'tip' => 'Tip',
                        'subscription' => 'Subscription',
                        'promotion' => 'Promotion',
                        'bonus' => 'Bonus',
                        'other' => 'Other',
                    ]),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                    
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('User'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('exportSelected')
                        ->label('Export Selected')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function ($records) {
                            // Export logic would go here
                            return response()->streamDownload(function () use ($records) {
                                echo $records->map(fn ($record) => [
                                    'User' => $record->user->name ?? "User #{$record->user_id}",
                                    'Amount' => $record->amount,
                                    'Source' => $record->source,
                                    'Date' => $record->created_at->format('Y-m-d H:i:s'),
                                ])->toJson(JSON_PRETTY_PRINT);
                            }, 'earnings-export.json');
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListEarnings::route('/'),
            'create' => Pages\CreateEarning::route('/create'),
            'edit' => Pages\EditEarning::route('/{record}/edit'),
        ];
    }
    
    public static function getWidgets(): array
    {
        return [
            EarningResource\Widgets\EarningStats::class,
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}