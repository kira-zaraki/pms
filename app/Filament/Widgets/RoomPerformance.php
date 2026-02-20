<?php

namespace App\Filament\Widgets;

use App\Models\Room;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;

class RoomPerformance extends TableWidget
{
    protected static ?int $sort = 4; 

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Room::withCount('reservations')->orderBy('reservations_count', 'desc'))
            ->columns([
                TextColumn::make('name')->label('Room Name'),
                TextColumn::make('type')->badge(),
                TextColumn::make('reservations_count')
                    ->label('Total Bookings')
                    ->sortable(),
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
