<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Guest;
use App\Models\Reservation;
use App\Services\bookingEngineService;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Livewire\Component;
use App\Enums\ReservationStatus;
use Carbon\Carbon;

class RoomShow extends Component implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;
    
    public Room $room;
    public $check_in, $check_out;
    public $full_name, $email, $phone_number, $address;
    public $nights;
    public $total_price;

    protected function rules(): array
    {
        return [
            'check_in'  => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'full_name'  => 'required|string|min:3',
            'email'     => 'required|email',
            'phone_number'     => 'required',
            'address'     => 'required',
        ];
    }

    public function updatedCheckOut($value)
    {
        if($this->check_in){
            $checkIn = Carbon::parse($this->check_in);
            $checkOut = Carbon::parse($this->check_out);
            $this->nights = $checkIn->diffInDays($checkOut);
            $this->total_price = $this->nights * $this->room->price_per_night;
        }
    }

    public function book()
    {
        $this->validate();
         $isAvailable = Room::availableBetween($this->check_in, $this->check_out)
            ->where('id', $this->room->id)
            ->exists();

        if (!$isAvailable) {
            $this->addError('check_in', 'The room is no longer available for these dates.');
            return;
        }

        bookingEngineService::createBooking($this->all(), $this->room);

        session()->flash('message', 'Reservation successful!');
    }

    public function render()
    {
        return view('livewire.room-show');
    }
}
