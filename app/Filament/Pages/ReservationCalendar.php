<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\ReservationCalendarWidget;
use App\Filament\Widgets\RoomFilterWidget;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use BackedEnum;

class ReservationCalendar extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar';
    protected string $view = 'filament.pages.reservation-calendar';
    public ?int $room_id = null;
    public ?array $data = [];

    protected function getHeaderWidgets(): array
    {
        return [
            RoomFilterWidget::class,
            ReservationCalendarWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }
}
