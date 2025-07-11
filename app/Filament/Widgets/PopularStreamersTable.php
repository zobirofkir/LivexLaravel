<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PopularStreamersTable extends BaseWidget
{
    protected static ?string $heading = 'Diffuseurs Populaires';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->withCount('liveStreams')
                    ->orderBy('live_streams_count', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('live_streams_count')
                    ->label('Nb Diffusions')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_live')
                    ->boolean()
                    ->label('En Ligne'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Inscrit le')
                    ->date()
                    ->sortable(),
            ]);
    }
}