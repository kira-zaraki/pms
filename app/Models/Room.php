<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\RoomStatus;
use App\Enums\ReservationStatus;
use App\Enums\RoomType;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Support\Str;

class Room extends Model
{
    protected $guarded = [];
    protected $casts = [
        'status' => RoomStatus::class,
        'type' => RoomType::class,
        'is_cleaned' => 'boolean',
        'price_per_night' => 'decimal:2',
    ];


    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    #[Scope]
    protected function availableBetween($query, $checkIn, $checkOut)
    {
        return $query->when($checkIn && $checkOut, function ($query) use ($checkIn, $checkOut) {
            $query->whereDoesntHave('reservations', function ($q) use ($checkIn, $checkOut) {
                $q->where('status', '!=', ReservationStatus::Cancelled)
                ->where(function ($sub) use ($checkIn, $checkOut) {
                    $sub->where('check_in_date', '<', $checkOut)
                        ->where('check_out_date', '>', $checkIn);
                });
            });
        });
    }

    protected static function booted()
    {
        static::creating(function ($room) {
            $room->ical_export_token = Str::random(40);
        });
    }
}
