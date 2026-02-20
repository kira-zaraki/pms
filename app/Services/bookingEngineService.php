<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use App\Models\Guest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class bookingEngineService
{
    // public static function getAvailableRooms($checkIn, $checkOut, $type = null)
    // {
    //     return Room::query()
    //         ->when($type, fn($q) => $q->where('type', $type))
    //         ->when($checkIn && $checkOut, function ($query) use ($checkIn, $checkOut) {
    //             $query->whereDoesntHave('reservations', function ($q) use ($checkIn, $checkOut) {
    //                 $q->whereIn('status', [ReservationStatus::Confirmed, ReservationStatus::Pending])
    //                     ->where(function ($q) use ($checkIn, $checkOut) {
    //                         $q->where('check_in_date', '<', $checkOut)
    //                         ->where('check_out_date', '>', $checkIn);
    //                     });
    //             });
    //         })
    //         ->with('galleries');
    // }

    public static function createBooking(array $data, Room $room)
    {
        return DB::transaction(function () use ($data, $room) {
            $checkIn = Carbon::parse($data['check_in']);
            $checkOut = Carbon::parse($data['check_out']);
            $nights = $checkIn->diffInDays($checkOut);

            $guest = Guest::firstOrCreate(
                ['email' => $data['email']],
                ['full_name' => $data['full_name'], 'phone_number' => $data['phone_number'], 'address' => $data['address']]
            );

            return Reservation::create([
                'guest_id' => $guest->id,
                'room_id' => $room->id,
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'status' => ReservationStatus::Pending,
                'total_nights' => $data['nights'],
                'price_per_night' => $room->price_per_night,
                'total_price' => $data['total_price'],
            ]);
        });
    }
}