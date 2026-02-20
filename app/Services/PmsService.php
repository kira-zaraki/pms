<?php

namespace App\Services;

use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\ReservationStatus;
use App\Models\Reservation;
use ICal\ICal;

class PmsService{

    public static function filterAvailableRooms(Builder $query, $get){
        $start = $get('check_in_date');
        $end = $get('check_out_date');
        $currentId = $get('id');

        if (!$start || !$end) return $query;

        return $query->when($currentId, fn($query) => $query->whereNot('id', $currentId))
                     ->availableBetween($start, $end);
        
        // $query->whereDoesntHave('reservations', function ($q) use ($start, $end, $currentId) {
        //     $q->when($currentId, fn($query) => $query->whereNot('id', $currentId))
        //       ->where(fn ($query) => 
        //         $query->whereBetween('check_in_date', [$start, $end])
        //               ->orWhereBetween('check_out_date', [$start, $end])
        //               ->orWhere(fn ($sub) => $sub->where('check_in_date', '<=', $start)->where('check_out_date', '>=', $end))
        //     );
        // });
    }

    public static function roomReservationRange($get, $record){
        $roomId = $get('room_id');
        
        if (!$roomId) return [];

        return Reservation::query()
            ->where('room_id', $roomId)
            ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
            ->where('status', '!=', ReservationStatus::Cancelled)
            ->where('check_out_date', '>=', now()->toDateString())
            ->get(['check_in_date', 'check_out_date'])
            ->flatMap(fn ($res) => $res->getRangeOfDates())
            ->toArray();
    }

    public static function getFreeStaffUsers(Builder $query, $record){
        return $query->whereDoesntHave('staff')->when($record, fn ($q) => $q->orWhere('id', $record->user_id));
    }
    
    public static function revenueData(){
       $revenueData = Trend::query(Reservation::where('status', ReservationStatus::Confirmed))
            ->between(start: now()->subDays(6), end: now())
            ->perDay()
            ->sum('total_price')
            ->map(fn (TrendValue $value) => (float) $value->aggregate)->toArray();

        return count($revenueData) > 0 ? $revenueData : [0, 0, 0, 0, 0, 0, 0];
    }

    public static function asyncReservation($room){
        if (!$room->ota_ical_import_url) return;

        $externalGuest = Guest::firstOrCreate(
            ['email' => 'channel-manager@system.com'],
            ['full_name' => 'OTA External Booking', 'phone_number' => 'N/A']
        );

        $ical = new ICal($room->ota_ical_import_url);

        foreach ($ical->events() as $event) {
            Reservation::updateOrCreate(
                ['external_id' => $event->uid],
                [
                    'room_id' => $room->id,
                    'guest_id' => $externalGuest->id,
                    'check_in_date' => $event->dtstart,
                    'check_out_date' => $event->dtend,
                    'status' => ReservationStatus::Confirmed, 
                    'source' => 'ical_sync',
                    'price_per_night' => $room->price_per_night,
                    'total_price' => 0,
                ]
            );
        }

        $this->room->update(['last_sync_at' => now()]);
    }

}
