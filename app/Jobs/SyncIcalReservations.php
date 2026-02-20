<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Room;
use App\Services\PmsService;

class SyncIcalReservations implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Room $room)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        PmsService::asyncReservation($this->room);
    }
}
