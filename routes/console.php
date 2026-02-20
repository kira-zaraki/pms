<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
use App\Jobs\SyncIcalReservations;
use App\Models\Room;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Room::whereNotNull('ota_ical_import_url')->each(fn ($room) => SyncIcalReservations::dispatch($room));
    echo "\n[iCal Sync] Found rooms to sync.\n";
})->everyFifteenMinutes();