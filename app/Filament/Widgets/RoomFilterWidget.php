<?php

namespace App\Filament\Widgets;

use App\Models\Room;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;
use Livewire\Attributes\Url;

class RoomFilterWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.widgets.room-filter-widget';

    #[Url]
    public ?int $room_id = null;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('room_id')
                    ->label('Rooms')
                    ->placeholder('Filter by Room')
                    ->options(Room::pluck('name', 'id'))
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->dispatch('calendar-filter-updated', roomId: $state))
                    ->columnSpanFull(),
            ]);
    }

    public static function canView(): bool
    {
        return request()->routeIs('filament.admin.pages.reservation-calendar');
    }
}
