<?php

namespace App\Filament\Resources\Reservations\Schemas;

use Carbon\Carbon;
use App\Enums\ReservationStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use App\Models\Room;
use App\Services\PmsService;
use App\Filament\Resources\Guests\Schemas\GuestForm;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('guest_id')
                    ->relationShip('guest', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm(fn (Schema $schema) => GuestForm::configure($schema)->getComponents())
                    ->editOptionForm(fn (Schema $schema) => GuestForm::configure($schema)->getComponents()),
                Select::make('room_id')
                    ->relationShip(
                        name: 'room', 
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query, $get) => PmsService::filterAvailableRooms($query, $get)
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                            $room = Room::find($state);

                        if ($room) {
                            $set('price_per_night', $room->price_per_night);
                        }
                    })
                    ->rules([
                        fn ($get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                            $isAvailable = PmsService::filterAvailableRooms(Room::query(), $get)->where('id', $value)->exists();

                            if (!$isAvailable)
                                $fail("The selected room is no longer available for these dates.");
                        },
                    ]),
                DatePicker::make('check_in_date')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->placeholder('d/m/Y')
                    ->label('Arrival date')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $get, callable $set) =>
                        self::updateTotals($get, $set)
                    )
                    ->disabledDates(function (callable $get, $record) {
                        return PmsService::roomReservationRange($get, $record);
                    }),
                DatePicker::make('check_out_date')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->placeholder('d/m/Y')
                    ->label('Departure date')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $get, callable $set) =>
                        self::updateTotals($get, $set)
                    ),
                Select::make('status')
                    ->options(ReservationStatus::class)
                    ->default('confirmed')
                    ->required(),
                TextInput::make('total_nights')
                    ->numeric(),
                TextInput::make('price_per_night')
                    ->numeric(),
                TextInput::make('total_price')
                    ->numeric()
                    ->prefix('$'),
            ]);
    }

    protected static function updateTotals(callable $get, callable $set): void
    {
        $checkIn = $get('check_in_date');
        $checkOut = $get('check_out_date');
        $price = $get('price_per_night');

        if ($checkIn && $checkOut) {
            $nights = Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));
            $set('total_nights', $nights);

            if ($price) {
                $set('total_price', $nights * $price);
            }
        }
    }
}
