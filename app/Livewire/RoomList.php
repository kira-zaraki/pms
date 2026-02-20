<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use App\Models\Room;
use App\Enums\RoomType;

class RoomList extends Component
{
    #[Url(keep: true)]
    public $check_in, $check_out;

    #[Computed]
    public function rooms()
    {
        return room::availableBetween($this->check_in, $this->check_out)->get();
    }

    public function render()
    {
        $roomTypes = RoomType::cases();
        return view('livewire.room-list', compact('roomTypes'));
    }
}
