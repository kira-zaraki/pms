<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use \Guava\Calendar\Filament\CalendarWidget;
use Illuminate\Support\Collection;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Guava\Calendar\ValueObjects\FetchInfo;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Filament\Resources\Reservations\Schemas\ReservationForm;
use Livewire\Attributes\On;

class ReservationCalendarWidget extends CalendarWidget
{
    protected bool $eventClickEnabled = true;
    protected string | null $model = Reservation::class;

    #[Url]
    public ?int $room_id = null;

    public function getEvents(FetchInfo $fetchInfo): Collection | array
    {
        return Reservation::query()
            ->where('check_in_date', '>=', $fetchInfo->start)
            ->where('check_out_date', '<=', $fetchInfo->end)
            ->when($this->room_id, fn ($query) => $query->where('room_id', $this->room_id))
            ->get();
    }

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->model(Reservation::class)
                ->form(fn (Schema $schema) => ReservationForm::configure($schema)->getComponents())
                ->after(function () {
                    $this->refreshRecords(); 
                })
                ->mountUsing(fn ($form, array $arguments) => $form->fill([
                    'check_in_date' => $arguments['start'] ?? null,
                    'check_out_date' => $arguments['end'] ?? null,
                ])),
        ];
    }

    #[On('calendar-filter-updated')]
    public function updateRoomFilter($roomId): void
    {
        $this->room_id = $roomId;
        $this->refreshRecords();
    }

    protected function getEventClickContextMenuActions(): array
    {
        return [
            $this->viewAction(),
            $this->editAction(),
            $this->deleteAction(),
        ];
    }
    
    public static function canView(): bool
    {
        return request()->routeIs('filament.admin.pages.reservation-calendar');
    }
    
}
