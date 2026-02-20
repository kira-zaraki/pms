<?php

namespace App\Filament\Resources\Reservations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReservationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('check_in_date')
                    ->date(),
                TextEntry::make('check_out_date')
                    ->date(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('total_nights')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('price_per_night')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('total_price')
                    ->money()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('guest_id')
                    ->numeric(),
                TextEntry::make('room_id')
                    ->numeric(),
            ]);
    }
}
