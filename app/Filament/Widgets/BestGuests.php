<?php

namespace App\Filament\Widgets;

use App\Models\Guest;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;

class BestGuests extends TableWidget
{
    protected static ?int $sort = 3;
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Guest::withCount('reservations')
                ->orderBy('reservations_count', 'desc')
                ->limit(5))
            ->columns([
                TextColumn::make('full_name')->label('Guest Name'),
                TextColumn::make('reservations_count')->label('Visits'),
                TextColumn::make('email')->icon('heroicon-m-envelope'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
