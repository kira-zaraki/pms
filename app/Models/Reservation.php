<?php

namespace App\Models;

use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ReservationStatus;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model implements Eventable
{
    protected $fillable = [
        'guest_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'status',
        'total_nights',
        'price_per_night',
        'total_price',
    ];

    protected $casts = [
        'status' => ReservationStatus::class,
        'check_in_date'  => 'date',
        'check_out_date' => 'date',
        'price_per_night' => 'decimal:2',
        'total_price'     => 'decimal:2',
    ];

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function getRangeOfDates(): array
    {
        return collect(CarbonPeriod::create($this->check_in_date, $this->check_out_date))
            ->map(fn ($date) => $date->format('Y-m-d'))
            ->toArray();
    }

    public function toCalendarEvent(): CalendarEvent
    {
        return CalendarEvent::make($this)
            ->title("Room {$this->room->number}: {$this->guest->full_name}")
            ->start($this->check_in_date)
            ->end($this->check_out_date);
    }
}
