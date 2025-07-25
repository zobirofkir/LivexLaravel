<?php

namespace App\Filament\Resources\EarningResource\Pages;

use App\Filament\Resources\EarningResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEarnings extends ListRecords
{
    protected static string $resource = EarningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add New Earning'),
            Actions\Action::make('export')
                ->label('Export All')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    // Export logic would go here
                    return response()->streamDownload(function () {
                        echo \App\Models\Earning::with('user')
                            ->get()
                            ->map(fn ($earning) => [
                                'User' => $earning->user->name,
                                'Amount' => $earning->amount,
                                'Source' => $earning->source,
                                'Date' => $earning->created_at->format('Y-m-d H:i:s'),
                            ])
                            ->toJson(JSON_PRETTY_PRINT);
                    }, 'all-earnings.json');
                }),
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            EarningResource\Widgets\EarningStats::class,
        ];
    }
}