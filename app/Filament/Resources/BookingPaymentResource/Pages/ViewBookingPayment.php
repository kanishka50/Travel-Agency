<?php

namespace App\Filament\Resources\BookingPaymentResource\Pages;

use App\Filament\Resources\BookingPaymentResource;
use Filament\Resources\Pages\ViewRecord;

class ViewBookingPayment extends ViewRecord
{
    protected static string $resource = BookingPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No edit action for safety
        ];
    }
}
