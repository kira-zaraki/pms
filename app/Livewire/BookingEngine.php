<?php

namespace App\Livewire;

use App\Services\bookingEngineService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Livewire\Attributes\Url;

class BookingEngine extends Component implements HasForms
{
    use InteractsWithForms;

    #[Url(keep: true)]
    public $check_in, $check_out;

    #[Computed]
    public function rooms()
    {
        if (!$this->check_in || !$this->check_out) return collect();
        
        return bookingEngineService::getAvailableRooms($this->check_in, $this->check_out);
    }

    protected function getFormSchema(): array {
        return [
            DatePicker::make('check_in')
                ->native(false)
                ->required()
                ->live(),
            DatePicker::make('check_out')
                ->native(false)
                ->required()
                ->after('check_in')
                ->live(),
        ];
    }

    public function render()
    {
        return view('livewire.booking-engine');
    }
}
