<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use App\Enums\ReservationStatus;

class ChannelController extends Controller
{
    public function eventCreate(Room $room){
        
        $calendar = Calendar::create("Room {$room->number} Availability");

        foreach ($room->reservations()->where('status', ReservationStatus::Confirmed)->get() as $res) {
            $calendar->event(
                Event::create('Reserved')
                    ->uniqueIdentifier("res-{$res->id}")
                    ->startsAt($res->check_in_date)
                    ->endsAt($res->check_out_date)
            );
        }

        return response($calendar->get())->header('Content-Type', 'text/calendar');
    }
}
