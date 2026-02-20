<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Reservation;
use App\Services\PmsService;
use App\Enums\ReservationStatus;

class RevenueStats extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Revenue', '$' . number_format(Reservation::where('status', ReservationStatus::Confirmed)->sum('total_price'), 2))
                ->description('Total earnings from confirmed bookings')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart(PmsService::revenueData())
                ->color('success'),
        ];
    }
    
    public function getColumns(): int | array
    {
        return 1;
    }
}
