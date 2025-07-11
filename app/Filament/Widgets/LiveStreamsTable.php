<?php

namespace App\Filament\Widgets;

use App\Models\LiveStream;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LiveStreamsTable extends BaseWidget
{
    protected static ?string $heading = 'Diffusions Récentes';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LiveStream::query()->with('user')->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Diffuseur'),
                Tables\Columns\IconColumn::make('is_live')
                    ->boolean()
                    ->label('En Direct'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}