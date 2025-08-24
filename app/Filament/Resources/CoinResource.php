<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoinResource\Pages;
use App\Filament\Resources\CoinResource\RelationManagers;
use App\Models\Coin;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CoinResource extends Resource
{
    protected static ?string $model = Coin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Coin Management';

    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('price'),
                TextInput::make('old_price'),
                Hidden::make('user_id')->default(Auth::id()),

            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('price'),
                TextColumn::make('old_price'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCoins::route('/'),
            'create' => Pages\CreateCoin::route('/create'),
            'edit' => Pages\EditCoin::route('/{record}/edit'),
        ];
    }
}
