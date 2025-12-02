<?php

namespace App\Filament\Resources\BookingPaymentResource\Pages;

use App\Filament\Resources\BookingPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingPayments extends ListRecords
{
    protected static string $resource = BookingPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - payments are created automatically when bookings are paid
        ];
    }
}
