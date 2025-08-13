<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfferResource\Pages;
use App\Filament\Resources\OfferResource\RelationManagers;
use App\Models\Offer;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    
    protected static ?string $navigationGroup = 'Content Management';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Offer Details')
                    ->schema([
                TextInput::make('user_id')
                    ->label('Creator')
                    ->default(fn () => Auth::id())
                    ->required()
                    ->hidden()
                    ->dehydrated(),
                            
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                            
                        FileUpload::make('image')
                            ->image()
                            ->directory('offers')
                            ->maxSize(5120)
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('675'),
                            
                        RichEditor::make('description')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(1),
                    
                Section::make('Pricing & Availability')
                    ->schema([
                        TextInput::make('price')
                            ->label('Original Price')
                            ->numeric()
                            ->prefix('$')
                            ->maxValue(999999.99)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                $discountType = $get('discount_type');
                                $discountPercentage = $get('discount_percentage');
                                if ($discountType === 'percentage' && $discountPercentage && $state) {
                                    $discountedPrice = $state * (1 - $discountPercentage / 100);
                                    $set('price_sale', round($discountedPrice, 2));
                                }
                            }),
                            
                        Select::make('discount_type')
                            ->label('Discount Type')
                            ->options([
                                'fixed' => 'Fixed Price',
                                'percentage' => 'Percentage Discount',
                            ])
                            ->default('fixed')
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                if ($state === 'percentage') {
                                    $price = $get('price');
                                    $discountPercentage = $get('discount_percentage');
                                    if ($price && $discountPercentage) {
                                        $discountedPrice = $price * (1 - $discountPercentage / 100);
                                        $set('price_sale', round($discountedPrice, 2));
                                    }
                                } else {
                                    $set('price_sale', null);
                                }
                            }),
                            
                        TextInput::make('price_sale')
                            ->label('Sale Price')
                            ->numeric()
                            ->prefix('$')
                            ->maxValue(999999.99)
                            ->visible(fn (Get $get) => $get('discount_type') === 'fixed')
                            ->helperText('Enter the discounted price'),
                            
                        TextInput::make('discount_percentage')
                            ->label('Discount Percentage')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100)
                            ->visible(fn (Get $get) => $get('discount_type') === 'percentage')
                            ->helperText('Enter discount percentage (0-100)')
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                $price = $get('price');
                                if ($price && $state) {
                                    $discountedPrice = $price * (1 - $state / 100);
                                    $set('price_sale', round($discountedPrice, 2));
                                }
                            }),
                            
                        DatePicker::make('valid_until')
                            ->label('Valid Until')
                            ->minDate(now()),
                            
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark'),
                    ])->columns(3),
                    
                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('additional_info')
                            ->label('Additional Information')
                            ->placeholder('Enter any additional information about this offer...')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Hidden::make('user_id')->default(Auth::user()->id)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                ImageColumn::make('image')
                    ->circular()
                    ->defaultImageUrl(fn () => asset('images/placeholder.jpg'))
                    ->toggleable(),
                    
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('user.name')
                    ->label('Creator')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('discount_type')
                    ->label('Discount')
                    ->formatStateUsing(function ($record) {
                        if ($record->discount_type === 'percentage' && $record->discount_percentage) {
                            return $record->discount_percentage . '% off';
                        } elseif ($record->discount_type === 'fixed' && $record->price_sale) {
                            return 'Fixed price';
                        }
                        return 'No discount';
                    })
                    ->toggleable(),
                    
                TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(function ($record) {
                        $originalPrice = '$' . number_format($record->price, 2);
                        $discountedPrice = $record->getDiscountedPrice();
                        
                        if ($discountedPrice < $record->price) {
                            $html = '<span style="text-decoration: line-through; color: #6b7280;">' . $originalPrice . '</span> ';
                            $html .= '<span style="color: #dc2626; font-weight: bold;">$' . number_format($discountedPrice, 2) . '</span>';
                            
                            if ($record->discount_type === 'percentage' && $record->discount_percentage) {
                                $html .= ' <span style="color: #059669; font-size: 0.875rem;">(' . $record->discount_percentage . '% off)</span>';
                            }
                            
                            return $html;
                        }
                        
                        return $originalPrice;
                    })
                    ->html()
                    ->sortable(),
                    
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                    
                TextColumn::make('valid_until')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // SelectFilter removed to fix isOptionDisabled error
                    
                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All Offers')
                    ->trueLabel('Active Offers')
                    ->falseLabel('Inactive Offers'),
                    
                Tables\Filters\Filter::make('valid_offers')
                    ->label('Valid Offers')
                    ->query(fn (Builder $query) => $query->where(function ($query) {
                        $query->whereNull('valid_until')
                              ->orWhere('valid_until', '>=', now()->toDateString());
                    }))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggleActive')
                        ->label('Toggle Active Status')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => !$record->is_active]);
                            }
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
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'edit' => Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}
